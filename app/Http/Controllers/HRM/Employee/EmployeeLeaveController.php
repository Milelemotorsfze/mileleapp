<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HRM\Employee\Leave;
use App\Models\HRM\Employee\LeaveHistory;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\Masters\MasterDivisionWithHead;
use Carbon\Carbon;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class EmployeeLeaveController extends Controller
{
    public function checkLeaveDateAlreadyExist(Request $request) {
        $isAlreadyExist['startDate'] = 'no';
        $isAlreadyExist['endDate'] = 'no';
        $data = true;
        if($request->startDate != '' && $request->endDate != '' && $request->EmpId != '') {
            $startDate = Leave::whereIn('status',['pending','approved'])->where('employee_id',$request->EmpId[0])->where('leave_start_date','<=',$request->startDate)
                ->where('leave_end_date','>=',$request->startDate);
            if(isset($request->id) && $request->id != 'new') {
                $startDate = $startDate->whereNot('id',$request->id);
            }
            $startDate = $startDate->get();
            if(count($startDate) > 0) {
                $isAlreadyExist['startDate'] = 'yes';
            }
            $endDate = Leave::whereIn('status',['pending','approved'])->where('employee_id',$request->EmpId[0])->where('leave_start_date','<=',$request->endDate)
                ->where('leave_end_date','>=',$request->endDate);
            if(isset($request->id) && $request->id != 'new') {
                $endDate = $endDate->whereNot('id',$request->id);
            }
            $endDate = $endDate->get();
            if(count($endDate) > 0) {
                $isAlreadyExist['endDate'] = 'yes';
            }
        }    
        if($isAlreadyExist['startDate'] == 'yes' OR $isAlreadyExist['endDate'] == 'yes')  {
            $data = false;
        }
        return response()->json($data);
    }
    public function approvalAwaiting(Request $request) {
        $leavePersonReplacedBy = '';
        $leavePersonReplacedBy = User::orderBy('name','ASC')->whereNot('is_management','yes')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->get();
        $authId = Auth::id();
        $page = 'approval';
        $HRManager = '';
        $deptHead = $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = $employeePendings = $employeeApproved = $employeeRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = [];
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $employeePendings = Leave::where([
            ['action_by_employee','pending'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeApproved = Leave::where([
            ['action_by_employee','approved'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeRejected = Leave::where([
            ['action_by_employee','rejected'],
            ['employee_id',$authId],
            ])->latest()->get();
        $HRManagerPendings = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerApproved = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerRejected = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $ReportingManagerPendings = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_employee','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerApproved = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_department_head','approved'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerRejected = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],                
            ['action_by_department_head','rejected'],
            ['department_head_id',$authId],
            ])->latest()->get();   
        $divisionHeadPendings = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','pending'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadApproved = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','approved'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadRejected = Leave::where([
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],                
            ['action_by_department_head','approved'],
            ['action_by_division_head','rejected'],
            ['division_head_id',$authId],
            ])->latest()->get();
        return view('hrm.leave.approvals',compact('leavePersonReplacedBy','page','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected','employeePendings',
        'employeeApproved','employeeRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected','ReportingManagerPendings','ReportingManagerApproved','ReportingManagerRejected'));
    }
    public function requestAction(Request $request) {
        $message = '';
        $update = Leave::where('id',$request->id)->first();
        // employee--------->HR Manager -------> Reporting Manager  ------------>Division Head       
        if($request->current_approve_position == 'Employee') {
            $update->comments_by_employee = $request->comment;
            $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_employee = $request->status;
            if($request->status == 'approved') {
                $update->action_by_hr_manager = 'pending';
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $update->hr_manager_id = $HRManager->handover_to_id;
                $message = 'Employee passport submit request send to HR Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'HR Manager') {
            $update->comments_by_hr_manager = $request->comment;
            $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_hr_manager = $request->status;
            if($request->status == 'approved') {
                $update->others = $request->others;
                $update->action_by_department_head = 'pending';
                $employee2 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                $leadOrMngr = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$employee2->team_lead_or_reporting_manager)->first();
                $update->department_head_id = $leadOrMngr->approval_by_id;
                $message = 'Employee passport submit request send to Reporting Manager ( '.$update->reportingManager->name.' - '.$update->reportingManager->email.' ) for approval';
            }
            else if($request->status == 'rejected') {
                $update->others = NULL;
            }
        }
        else if($request->current_approve_position == 'Reporting Manager') {
            $update->comments_by_department_head = $request->comment;
            $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_department_head = $request->status;
            if($request->status == 'approved') {
                $update->action_by_division_head = 'pending';
                $update->to_be_replaced_by = $request->to_be_replaced_by[0];
                $employee1 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                $divisionHead1 = MasterDivisionWithHead::where('id',$employee1->department->division_id)->first();
                $update->division_head_id = $divisionHead1->approval_handover_to;
                $message = 'Employee passport submit request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';
            }
            else if($request->status == 'rejected') {    
                $update->to_be_replaced_by = NULL;
            }
        }
        else if($request->current_approve_position == 'Division Head') {
            $update->comments_by_division_head = $request->comment;
            $update->division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_division_head = $request->status;
            if($request->status == 'approved') {
                $update->status = 'approved';
            }
        }
        if($request->status == 'rejected') {
            $update->status = 'rejected';
        }
        $update->update();
        $history['leave_id'] = $request->id;
        if($request->status == 'approved') {
            $history['icon'] = 'icons8-thumb-up-30.png';
        }
        else if($request->status == 'rejected') {
            $history['icon'] = 'icons8-thumb-down-30.png';
        }
        $history['message'] = 'Employee leave submit request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
        $createHistory = LeaveHistory::create($history);  
        if($request->status == 'approved' && $message != '') {
            $history['icon'] = 'icons8-send-30.png';
            $history['message'] = $message;
            $createHistory = LeaveHistory::create($history);
        }
        (new UserActivityController)->createActivity($history['message']);
        return response()->json('success');
        // ,'New Employee Hiring Request '.$request->status.' Successfully'
    }
    public function index() {
        $authId = Auth::id();
        $page = 'listing';
        $pendings = Leave::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['view-leave-list'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-current-user-leave-list'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings = $pendings->get();
        $approved = Leave::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['view-leave-list'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-current-user-leave-list'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved = $approved->get();
        $rejected = Leave::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['view-leave-list'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-current-user-leave-list'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected =$rejected->get();
        $leavePersonReplacedBy = '';
        $leavePersonReplacedBy = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereNot('is_management','yes')->whereHas('empProfile')->get();
        return view('hrm.leave.index',compact('pendings','approved','rejected','page','leavePersonReplacedBy'));
    }
    public function show($id) {
        $data = Leave::where('id',$id)->first();
        $previous = Leave::where('id', '<', $id)->max('id');
        $next = Leave::where('id', '>', $id)->min('id');
        $all = Leave::whereNot('id',$id)->where('employee_id',$data->employee_id)->latest()->get();
        return view('hrm.leave.show',compact('data','previous','next','all'));
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new Leave();
            $previous = $next = '';
        }
        else {
            $data = Leave::where('id',$id)->with('user.empProfile.designation','user.empProfile.department','user.empProfile.location')->first();
            $previous = Leave::where('status',$data->status)->where('id', '<', $id)->max('id');
            $next = Leave::where('status',$data->status)->where('id', '>', $id)->min('id');
        }
        $masterEmployees = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereNot('is_management','yes')->whereHas('empProfile')->with('empProfile.department','empProfile.designation','empProfile.location')->select('id','name')->get();
        return view('hrm.leave.create',compact('id','data','previous','next','masterEmployees'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'type_of_leave' => 'required',
            'leave_start_date' => 'required',
            'leave_end_date' => 'required',
            'total_no_of_days' => 'required',
            'no_of_paid_days' => 'required',
            'no_of_unpaid_days' => 'required',
            'address_while_on_leave' => 'required',
            'alternative_home_contact_no' => 'required',
            'alternative_personal_email' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $startDate =[];
                $endDate = [];
                if($request->leave_start_date != '' && $request->leave_end_date != '' && $request->employee_id != '') {
                    $startDate = Leave::whereIn('status',['pending','approved'])->where('employee_id',$request->employee_id)->where('leave_start_date','<=',$request->leave_start_date)
                        ->where('leave_end_date','>=',$request->leave_start_date);
                    if(isset($request->id) && $request->id != 'new') {
                        $startDate = $startDate->whereNot('id',$request->id);
                    }
                    $startDate = $startDate->get();                         
                    $endDate = Leave::whereIn('status',['pending','approved'])->where('employee_id',$request->employee_id)->where('leave_start_date','<=',$request->leave_end_date)
                        ->where('leave_end_date','>=',$request->leave_end_date);
                    if(isset($request->id) && $request->id != 'new') {
                        $endDate = $endDate->whereNot('id',$request->id);
                    }
                    $endDate = $endDate->get();
                }
                if(count($startDate) == 0 && count($endDate) == 0) {  
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
                    if($id == 'new') {
                        $input['created_by'] = $authId;   
                        $input['hr_manager_id'] = $HRManager->handover_to_id;                
                        $input['department_head_id'] = $employee->leadManagerHandover->approval_by_id;
                        $input['division_head_id'] = $divisionHead->approval_handover_to;
                        $input['alternative_home_contact_no'] = $request->alternative_home_contact_no['full'];
                        if($request->type_of_leave != 'others') {
                            $input['type_of_leave_description'] = NULL;
                        }
                        $createRequest = Leave::create($input);
                        $history['leave_id'] = $createRequest->id;
                        $history['icon'] = 'icons8-document-30.png';
                        $history['message'] = 'Employee Leave request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = LeaveHistory::create($history);
                        $history2['leave_id'] = $createRequest->id;
                        $history2['icon'] = 'icons8-send-30.png';
                        $history2['message'] = 'Employee Leave request send to Employee ( '.$employee->user->name.' - '.$employee->user->email.' ) for approval';
                        $createHistory2 = LeaveHistory::create($history2);
                        (new UserActivityController)->createActivity('Employee Leave Request Created');
                        $successMessage = "Employee Leave Request Created Successfully";
                    }
                    else {
                        $update = Leave::find($id);
                        if($update) {
                            $update->employee_id = $request->employee_id;
                            $update->type_of_leave = $request->type_of_leave;
                            if($request->type_of_leave != 'others') {
                                $update->type_of_leave_description = NULL;
                            }
                            else { 
                                $update->type_of_leave_description = $request->type_of_leave_description;
                            }
                            $update->leave_start_date = $request->leave_start_date;
                            $update->leave_end_date = $request->leave_end_date;
                            $update->total_no_of_days = $request->total_no_of_days;
                            $update->no_of_paid_days = $request->no_of_paid_days;
                            $update->no_of_unpaid_days = $request->no_of_unpaid_days;
                            $update->address_while_on_leave = $request->address_while_on_leave;
                            $update->alternative_home_contact_no = $request->alternative_home_contact_no['full'];
                            $update->alternative_personal_email = $request->alternative_personal_email;
                            $update->status = 'pending';
                            $update->action_by_employee = 'pending';
                            $update->employee_action_at = NULL;
                            // $update->comments_by_employee = NULL;
                            // $update->advance_or_loan_balance = 0.00;
                            // $update->others = NULL;
                            $update->action_by_hr_manager = NULL;
                            $update->hr_manager_id = $HRManager->handover_to_id;  
                            $update->hr_manager_action_at = NULL;
                            // $update->comments_by_hr_manager =NULL:
                            $update->action_by_department_head = NULL;
                            $update->department_head_id = $employee->leadManagerHandover->approval_by_id;
                            $update->department_head_action_at = NULL;
                            // $update->comments_by_department_head = NULL;
                            // $update->to_be_replaced_by 
                            $update->action_by_division_head = NULL;
                            $update->division_head_action_at = NULL;
                            $update->updated_by = $authId;
                            $update->update();
                            $history['leave_id'] = $id;
                            $history['icon'] = 'icons8-edit-30.png';
                            $history['message'] = 'Employee Leave request edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                            $createHistory = LeaveHistory::create($history);
                            (new UserActivityController)->createActivity('Employee Leave Request Edited');
                            $successMessage = "Employee Leave Request Updated Successfully";
                        }
                    }
                    DB::commit();
                    return redirect()->route('employee_leave.index')->with('success',$successMessage);
                }
                else {
                    $errorMsg ="These leave dates are alredy exist in the system";
                    return view('hrm.notaccess',compact('errorMsg'));
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
}
