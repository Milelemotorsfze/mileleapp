<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee\OverTime;
use App\Models\HRM\Employee\OverTimeDateTime;
use App\Models\HRM\Employee\OverTimeHistory;
use App\Models\User;
use Validator;
use DB;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\Masters\MasterDivisionWithHead;
use Carbon\Carbon;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class OverTimeController extends Controller
{
    public function index() {
        $authId = Auth::id();
        $pendings = OverTime::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-overtime'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-overtime'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings =$pendings->get();
        $approved = OverTime::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-overtime'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-overtime'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved =$approved->get();
        $rejected = OverTime::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-overtime'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-overtime'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected =$rejected->get();
        return view('hrm.overtime.index',compact('pendings','approved','rejected'));
    }
    public function create() {
        $employees = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16,2,26,31,78])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.overtime.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'overtime' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $startTime =[];
                $endTime = [];
                if(count($request->overtime) > 0) {
                    foreach($request->overtime as $overtime) {
                        if($overtime['start_datetime'] != NULL && $overtime['end_datetime'] != NULL && $request->employee_id) {
                            $startTime = OverTimeDateTime::where('start_datetime','<=',$overtime['start_datetime'])
                            ->where('end_datetime','>=',$overtime['start_datetime'])
                            ->whereHas('overtime', function($q) use($request) {
                                    $q->where('employee_id',$request->employee_id);
                            })->get();                          
                            $endTime = OverTimeDateTime::where('start_datetime','<=',$overtime['end_datetime'])
                                ->where('end_datetime','>=',$overtime['end_datetime'])
                                ->whereHas('overtime', function($q) use($request) {
                                        $q->where('employee_id',$request->employee_id);
                                })->get();
                        }
                    }
                }
                if(count($startTime) == 0 && count($endTime) == 0) {                  
                    $authId = Auth::id();
                    $employ = EmployeeProfile::where('user_id',$request->employee_id)->first();
                    if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                        $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                        $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                        $createHandOvr['created_by'] = $authId;
                        $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                    }
                    $employee = EmployeeProfile::where('user_id',$request->employee_id)->first();
                    $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    $divisionHead = MasterDivisionWithHead::where('id',$employee->department->division_id)->first();
                    $input = $request->all();
                    $input['created_by'] = $authId; 
                    $input['hr_manager_id'] = $HRManager->handover_to_id;                
                    $input['department_head_id'] = $employee->leadManagerHandover->approval_by_id;
                    $input['division_head_id'] = $divisionHead->approval_handover_to;
                    $createRequest = OverTime::create($input);
                    if(count($request->overtime) > 0) {
                        foreach($request->overtime as $overtime) {
                            if($overtime['start_datetime'] != NULL && $overtime['end_datetime'] != NULL) {
                                $createTime = [];
                                $time['over_times_id'] = $createRequest->id;
                                $time['start_datetime'] = $overtime['start_datetime'];
                                $time['end_datetime'] = $overtime['end_datetime'];
                                $time['remarks'] = $overtime['remarks'];
                                $createTime = OverTimeDateTime::create($time);
                            }
                        }
                    }
                    $history['over_times_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee Overtime Application request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = OverTimeHistory::create($history);
                    $history2['over_times_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee Overtime Application request send to Employee ( '.$employee->first_name.' - '.$employee->personal_email_address.' ) for approval';
                    $createHistory2 = OverTimeHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee OverTime Application Created');
                    $successMessage = "Employee OverTime Application Created Successfully";                   
                    DB::commit();
                    return redirect()->route('overtime.index')->with('success',$successMessage); 
                }
                else {
                    return redirect()->back()->withInput();
                }
            }
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }       
    }
    public function show($id) {
        $data = OverTime::where('id',$id)->first();
        $previous = OverTime::where('id', '<', $id)->max('id');
        $next = OverTime::where('id', '>', $id)->min('id');
        return view('hrm.overtime.show',compact('data','previous','next'));
    }
    public function checkOvertimeAlreadyExist(Request $request) {
        $isAlreadyExist['startTime'] = 'no';
        $isAlreadyExist['endTime'] = 'no';
        if($request->startTime != '' && $request->endTime != '' && $request->EmpId != '') {
            $startTime = OverTimeDateTime::where('start_datetime','<=',$request->startTime)
                ->where('end_datetime','>=',$request->startTime)
                ->whereHas('overtime', function($q) use($request) {
                        $q->where('employee_id',$request->EmpId[0]);
                });
            if(isset($request->overtimeId) && $request->overtimeId != '') {
                $startTime = $startTime->whereNot('over_times_id',$request->overtimeId);
            }
            $startTime = $startTime->get();
            if(count($startTime) > 0) {
                $isAlreadyExist['startTime'] = 'yes';
            }
            $endTime = OverTimeDateTime::where('start_datetime','<=',$request->endTime)
                ->where('end_datetime','>=',$request->endTime)
                ->whereHas('overtime', function($q) use($request) {
                        $q->where('employee_id',$request->EmpId[0]);
                });
            if(isset($request->overtimeId) && $request->overtimeId != '') {
                $endTime = $endTime->whereNot('over_times_id',$request->overtimeId);
            }
            $endTime = $endTime->get();
            if(count($endTime) > 0) {
                $isAlreadyExist['endTime'] = 'yes';
            }
        }      
        return response()->json($isAlreadyExist);
    }   
    public function edit($id) {
        $data = OverTime::where('id', $id)->with('times','user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        $employees = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16,2,26,31,78])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.overtime.edit',compact('data','employees'));
    }  
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'overtime' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $startTime =[];
                $endTime = [];
                if(count($request->overtime) > 0) {
                    foreach($request->overtime as $overtime) { 
                        if($overtime['start_datetime'] != NULL && $overtime['end_datetime'] != NULL && $request->employee_id) {
                            $startTime = OverTimeDateTime::where('start_datetime','<=',$overtime['start_datetime'])
                            ->where('end_datetime','>=',$overtime['start_datetime'])
                            ->whereHas('overtime', function($q) use($request) {
                                    $q->where('employee_id',$request->employee_id);
                            });
                            if(isset($overtime['time_id']) && $overtime['time_id'] > 0) {
                                $startTime = $startTime->where('over_times_id','!=',$overtime['time_id'])->where('deleted_at',NULL);
                            }
                            $startTime = $startTime->get();                          
                            $endTime = OverTimeDateTime::where('start_datetime','<=',$overtime['end_datetime'])
                                ->where('end_datetime','>=',$overtime['end_datetime'])
                                ->whereHas('overtime', function($q) use($request) {
                                        $q->where('employee_id',$request->employee_id);
                                });
                            if(isset($overtime['time_id']) && $overtime['time_id'] > 0) {
                                $endTime = $endTime->where('over_times_id','!=',$overtime['time_id'])->where('deleted_at',NULL);
                            }
                            $endTime = $endTime->get();
                        }
                    }
                } 
                if(count($startTime) == 0 && count($endTime) == 0) {                  
                    $authId = Auth::id();
                    $employ = EmployeeProfile::where('user_id',$request->employee_id)->first();
                    if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                        $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                        $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                        $createHandOvr['created_by'] = $authId;
                        $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                    }
                    $employee = EmployeeProfile::where('user_id',$request->employee_id)->first();
                    $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    $divisionHead = MasterDivisionWithHead::where('id',$employee->department->division_id)->first();
                    $createRequest = OverTime::where('id',$id)->first();
                    if($createRequest != '') {
                        $createRequest->employee_id = $request->employee_id;
                        $createRequest->status = 'pending';
                        $createRequest->action_by_employee = 'pending';
                        $createRequest->employee_action_at = NULL;                       
                        $createRequest->comments_by_employee = NULL;
                        $createRequest->action_by_department_head = NULL;
                        $createRequest->department_head_id = $employee->leadManagerHandover->approval_by_id;
                        $createRequest->department_head_action_at = NULL;
                        $createRequest->comments_by_department_head = NULL;
                        $createRequest->action_by_division_head = NULL;
                        $createRequest->division_head_id = $divisionHead->approval_handover_to;
                        $createRequest->division_head_action_at = NULL;
                        $createRequest->comments_by_division_head = NULL;
                        $createRequest->action_by_hr_manager = NULL;
                        $createRequest->hr_manager_id = $HRManager->handover_to_id;
                        $createRequest->hr_manager_action_at = NULL;
                        $createRequest->comments_by_hr_manager = NULL;
                        $createRequest->updated_by = $authId;
                        $createRequest->update();
                    }
                    $deleteOld = OverTimeDateTime::where('over_times_id',$id)->get();
                    foreach($deleteOld as $delete) {
                        $delete->delete();
                    }
                    if(count($request->overtime) > 0) {
                        foreach($request->overtime as $overtime) {
                            if($overtime['start_datetime'] != NULL && $overtime['end_datetime'] != NULL) {
                                $createTime = [];
                                $time['over_times_id'] = $createRequest->id;
                                $time['start_datetime'] = $overtime['start_datetime'];
                                $time['end_datetime'] = $overtime['end_datetime'];
                                $time['remarks'] = $overtime['remarks'];
                                $createTime = OverTimeDateTime::create($time);
                            }
                        }
                    }
                    $history['over_times_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee Overtime Application request Updated by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = OverTimeHistory::create($history);
                    $history2['over_times_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee Overtime Application request send to Employee ( '.$employee->first_name.' - '.$employee->personal_email_address.' ) for approval';
                    $createHistory2 = OverTimeHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee OverTime Application Updated');
                    $successMessage = "Employee OverTime Application Updated Successfully";                   
                    DB::commit();
                    return redirect()->route('overtime.index')->with('success',$successMessage); 
                }
                else {
                    return redirect()->back()->withInput();
                }
            }
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        } 
    }  
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = OverTime::where('id',$request->id)->first();
            // employee -------> Reporting Manager---->Finance Manager--------->HR Manager-------->Division Head
            // Employee -----------> Reporting Manager ---------> Division Head-------> HR Manager 
            if($request->current_approve_position == 'Employee') {
                $update->comments_by_employee = $request->comment;
                $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_employee = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_department_head = 'pending';
                    $message = 'Employee OverTime Request send to Reporting Manager ( '.$update->reportingManager->name.' - '.$update->reportingManager->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Reporting Manager') {
                $update->comments_by_department_head = $request->comment;
                $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_department_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_division_head = 'pending';
                    $message = 'Employee OverTime Request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Division Head') {        
                $update->comments_by_division_head = $request->comment;
                $update->division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_division_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'pending';
                    $message = 'Employee OverTime Request send to HR Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'HR Manager') {
                $update->comments_by_hr_manager = $request->comment;
                $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_hr_manager = $request->status;
                if($request->status == 'approved') {
                    $update->status = 'approved'; 
                }
            }
            if($request->status == 'rejected') {
                $update->status = 'rejected'; 
            }
            $update->update();
            $history['over_times_id'] = $update->id;
            if($request->status == 'approved') {
                $history['icon'] = 'icons8-thumb-up-30.png';
            }
            else if($request->status == 'rejected') {
                $history['icon'] = 'icons8-thumb-down-30.png';
            }
            $history['message'] = 'Employee OverTime Request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
            $createHistory = OverTimeHistory::create($history);  
            if($request->status == 'approved' && $message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = OverTimeHistory::create($history);
            }
            (new UserActivityController)->createActivity($history['message']);
            DB::commit();
            return response()->json('success');
        } 
        catch (\Exception $e) {
            DB::rollback();
            info($e);
            $errorMsg ="Something went wrong! Contact your admin";
            return view('hrm.notaccess',compact('errorMsg'));
        }
    }   
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $HRManager = '';
        // employee -------> Reporting Manager  ----Finance Manager--------->HR Manager-------->Division Head
        // Employee -----------> Reporting Manager ---------> Division Head-------> HR Manager 
        $deptHead = $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = $employeePendings = $employeeApproved = $employeeRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = [];
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $employeePendings = OverTime::where([
            ['action_by_employee','pending'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeApproved = OverTime::where([
            ['action_by_employee','approved'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeRejected = OverTime::where([
            ['action_by_employee','rejected'],
            ['employee_id',$authId],
            ])->latest()->get();
        $ReportingManagerPendings = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerApproved = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerRejected = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','rejected'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $divisionHeadPendings = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','pending'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadApproved = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','approved'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadRejected = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_division_head','rejected'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $HRManagerPendings = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerApproved = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','approved'],
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerRejected = OverTime::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_division_head','approved'],
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        return view('hrm.overtime.approvals',compact('page','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected','employeePendings',
        'employeeApproved','employeeRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected','ReportingManagerPendings','ReportingManagerApproved',
        'ReportingManagerRejected','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected'));
    }
}

