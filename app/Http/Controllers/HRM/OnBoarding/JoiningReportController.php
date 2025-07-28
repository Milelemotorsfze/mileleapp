<?php

namespace App\Http\Controllers\HRM\OnBoarding;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\JoiningReport;
use App\Models\HRM\Employee\JoiningReportHistory;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use Validator;
use DB;
use App\Models\HRM\Approvals\ApprovalByPositions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Models\Masters\MasterDepartment;
use App\Models\HRM\Employee\Leave;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class JoiningReportController extends Controller
{
    public function checkTempDateExist(Request $request) { 
        $transfer_from_date = [];
        $joining_date = [];
        $data = true;
        if($request->transfer_from_date != '' && $request->joining_date != '' && $request->employee_id != '' && count($request->employee_id) > 0) {
            $transfer_from_date = JoiningReport::whereIn('status',['pending','approved'])
            ->where('employee_id',$request->employee_id[0])
            ->where('transfer_from_date','<=',$request->transfer_from_date)
            ->where('joining_date','>=',$request->transfer_from_date)
            ->where('joining_type','internal_transfer')
            ->where('internal_transfer_type','temporary');
            if(isset($request->id) && $request->id != 'new') { 
                $transfer_from_date = $transfer_from_date->whereNot('id',$request->id);
            }
            $transfer_from_date = $transfer_from_date->get();
            $joining_date = JoiningReport::whereIn('status',['pending','approved'])
            ->where('employee_id',$request->employee_id[0])
            ->where('transfer_from_date','<=',$request->joining_date)
            ->where('joining_date','>=',$request->joining_date)
            ->where('joining_type','internal_transfer')
            ->where('internal_transfer_type','temporary');
            if(isset($request->id) && $request->id != 'new') {
                $joining_date = $joining_date->whereNot('id',$request->id);
            }
            $joining_date = $joining_date->get();
        }   
        if(count($transfer_from_date) > 0 OR count($joining_date) > 0)  {
            $data = false;
        }
        return response()->json($data);
    }
    public function index($type) {
        $authId = Auth::id();
        $authUserDept = '';
        $authUserDept = EmployeeProfile::where('user_id',$authId)->first();
        $pendings = JoiningReport::where(function ($query) {
            $query = $query->where('action_by_prepared_by',NULL)->orWhere('action_by_prepared_by','pending')->orWhere('action_by_prepared_by','approved');
        })->where(function ($query1) {
            $query1 = $query1->where('action_by_employee',NULL)->orWhere('action_by_employee','pending')->orWhere('action_by_employee','approved');
        })->where(function ($query2) {
            $query2 = $query2->where('action_by_hr_manager',NULL)->orWhere('action_by_hr_manager','pending')->orWhere('action_by_hr_manager','approved');
        })->where(function ($query3) {
            $query3 = $query3->where('action_by_department_head',NULL)->orWhere('action_by_department_head','pending');
        });
        if(($type != NULL && $type == 'temporary') OR ($type != NULL && $type == 'permanent')) {
            $pendings = $pendings->where('joining_type','internal_transfer')->where('internal_transfer_type',$type);
        }
        else if($type != NULL) {
            $pendings = $pendings->where('joining_type',$type);
        }
        if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','view-permanent-joining-report-listing'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-view-joining-report-listing'])) {
            $pendings = $pendings->where(function ($query4) use($authId,$authUserDept) {
                $query4->whereHas('candidate' , function($query6) use($authId,$authUserDept) {
                    $query6->where('department_id',$authUserDept->department_id);
                })->orWhereHas('user.empProfile', function($query7) use($authId) {
                    $query7->where('team_lead_or_reporting_manager',$authId);
                });
            })->latest();
        }
        $pendings = $pendings->get();
        $approved = JoiningReport::where('action_by_department_head','approved');
        if(($type != NULL && $type == 'temporary') OR ($type != NULL && $type == 'permanent')) {
            $approved = $approved->where('joining_type','internal_transfer')->where('internal_transfer_type',$type);
        }
        else if($type != NULL) {
            $approved = $approved->where('joining_type',$type);
        }
        if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','view-permanent-joining-report-listing'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-view-joining-report-listing'])) {
            $approved = $approved->where(function ($query5) use($authId,$authUserDept){
                $query5->whereHas('candidate' , function($query8) use($authId,$authUserDept) {
                    $query8->where('department_id',$authUserDept->department_id);
                })->orWhereHas('user.empProfile', function($query9) use($authId) {
                    $query9->where('team_lead_or_reporting_manager',$authId);
                });
            })->latest();
        }
        $approved = $approved->get();
        $rejected = JoiningReport::where(function ($query) {
            $query = $query->where('action_by_department_head','rejected')->orWhere('action_by_hr_manager','rejected')
            ->orWhere('action_by_employee','rejected')->orWhere('action_by_prepared_by','rejected');
        });
        if(($type != NULL && $type == 'temporary') OR ($type != NULL && $type == 'permanent')) {
            $rejected = $rejected->where('joining_type','internal_transfer')->where('internal_transfer_type',$type);
        }
        else if($type != NULL) {
            $rejected = $rejected->where('joining_type',$type);
        }
        if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','view-permanent-joining-report-listing'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-view-joining-report-listing'])) {
            $rejected = $rejected->where(function ($query10) use($authId,$authUserDept){
                $query10->whereHas('candidate' , function($query11) use($authId,$authUserDept) {
                    $query11->where('department_id',$authUserDept->department_id);
                })->orWhereHas('user.empProfile', function($query12) use($authId) {
                    $query12->where('team_lead_or_reporting_manager',$authId);
                });
            })->latest();
        }
        $rejected = $rejected->get();
        return view('hrm.onBoarding.joiningReport.index',compact('pendings','approved','rejected','type'));
    }
    public function create($type) { 
        $authId = Auth::id();
        $authUserDept = '';
        $authUserDept = EmployeeProfile::where('user_id',$authId)->first();
        $candidates = $masterlocations = $reportingTo = $masterDepartments = $employees = [];
        $candidates = EmployeeProfile::orderBy('first_name', 'ASC')->where([
            ['personal_information_verified_at','!=',NULL],
            ['type','candidate'],
        ])->whereHas('interviewSummary', function($q) {
            $q->where('offer_letter_verified_at','!=',NULL);
        })->where(function ($query5) {
            $query5->whereDoesntHave('candidateJoiningReport')
            ->orWhereDoesntHave('candidateJoiningReport', function($query) {
                $query->where('status','pending')->orWhere(function($query6) {
                        $query6->where('joining_type','new_employee')->where('new_emp_joining_type','permanent')->whereIn('status',['pending','approved']);
                    })->orWhere(function($query7) {
                            $query7->where(function($query8){
                            $query8->where('joining_type','new_employee')->where('new_emp_joining_type','permanent')->whereIn('status',['pending','approved']);
                            })->where(function($query9){
                                $query9->where('joining_type','new_employee')->where('new_emp_joining_type','trial_period')->where('status','approved');
                            });        
            // ->orWhereDoesntHave('candidateJoiningReport', function($query6) {
            //     $query6->where('joining_type','new_employee')->where('new_emp_joining_type','permanent')->whereIn('status',['pending','approved']);
            // })->orWhereDoesntHave('candidateJoiningReport', function($query7) {
            //     $query7->where(function($query8){
            //     $query8->where('joining_type','new_employee')->where('new_emp_joining_type','permanent')->whereIn('status',['pending','approved']);
            //     })->where(function($query9){
            //         $query9->where('joining_type','new_employee')->where('new_emp_joining_type','trial_period')->where('status','approved');
                });        
            })
            ;
        });
        // if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','view-permanent-joining-report-listing'])) {
        //     $candidates = $candidates->latest();
        // }
        // else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
        //     $rejected = $rejected->where('employee_id',$authId)->latest();
        // }
        // else 
        if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-create-joining-report']) && isset($authUserDept) && $authUserDept->department_id) {
            $candidates = $candidates->where('department_id',$authUserDept->department_id)->latest();
        }
        $candidates = $candidates->with('designation','department.departmentHead','department.division.divisionHead')->get();
        $masterlocations = MasterOfficeLocation::orderBy('name', 'ASC')->where('status','active')->select('id','name','address')->get(); 
        $reportingTo = User::orderBy('name', 'ASC')->where('status','active')->where('status','active')->whereNotIn('id',[1,16])->get();
        $masterDepartments = MasterDepartment::orderBy('name', 'ASC')->whereNot('name','Management')->with('departmentHead','division.divisionHead')->get();
        $employees = User::orderBy('name', 'ASC')->where('status','active')->whereNotIn('id',[1,16])->whereNot('is_management','yes')->whereHas('empProfile');
        if($type == 'vacations_or_leave') {
            $employees = $employees->whereHas('approvedLeaves');
        }
        // if($type == 'permanent' OR $type == 'temporary') {
        //     $employees = $employees->where(function ($query2) use($type) {
        //         $query2->whereDoesntHave('joiningReport')
        //         ->orWhereDoesntHave('joiningReport', function ($query1) use($type) {
        //             $query1->where('status','pending')->where('joining_type','internal_transfer')->where('internal_transfer_type',$type);
        //         });
        //     });   
        // }
        // if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing','view-permanent-joining-report-listing'])) {
        //     $employees = $employees->latest();
        // }
        // else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
        //     $rejected = $rejected->where('employee_id',$authId)->latest();
        // }
        // else 
        if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-create-joining-report'])) {
            $employees = $employees->whereHas('empProfile', function($query7) use($authId) {
                    $query7->where('team_lead_or_reporting_manager',$authId);
            })->latest();
        }
        $employees = $employees->with('joiningReport','empProfile.designation','empProfile.department.departmentHead','empProfile.department.division.divisionHead','empProfile.location','approvedLeaves')->get();
        if($type == 'new_employee') {
            return view('hrm.onBoarding.joiningReport.create',compact('candidates','masterlocations','reportingTo','type','masterDepartments'));
        }
        else if($type == 'temporary') {
            return view('hrm.onBoarding.joiningReport.createInternalTransfer',compact('employees','masterlocations','reportingTo','type','masterDepartments'));
        }
        else if($type == 'permanent') {
            return view('hrm.onBoarding.joiningReport.createPermanentTransfer',compact('employees','masterlocations','reportingTo','type','masterDepartments'));
        }
        else if($type == 'vacations_or_leave') {
            return view('hrm.onBoarding.joiningReport.createVacationsOrLeave',compact('employees','masterlocations','reportingTo','type','masterDepartments'));
        }
    }
    public function store(Request $request) {  
        $authId = Auth::id();
        $oldRepMangr = '';
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            // 'employee_code' => 'required',
            'joining_type' => 'required',
            'joining_date' => 'required',
            'joining_location' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                DB::commit();
                $type = '';
                $type = $request->joining_type;
                if($request->joining_type == 'new_employee') {
                    $emp = EmployeeProfile::where('id',$request->employee_id)->first();
                }
                else if($request->joining_type == 'internal_transfer' OR $request->joining_type == 'vacations_or_leave') {
                    $employ = EmployeeProfile::where('user_id',$request->employee_id)->first();
                    if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                        $createHandOvr = [];
                        $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                        $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                        $createHandOvr['created_by'] = $authId;
                        $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                    }
                    $emp = EmployeeProfile::where('user_id',$request->employee_id)->first();
                }
                if($emp) {
                    if($request->employee_code != '') {
                        $emp->employee_code = $request->employee_code;
                    }
                    if(isset($request->team_lead_or_reporting_manager) && $request->joining_type == 'new_employee') {
                        // $emp->team_lead_or_reporting_manager = $request->team_lead_or_reporting_manager;
                    }
                    if($request->joining_type == 'internal_transfer' && $request->internal_transfer_type == 'permanent') {
                        $oldRepMangr = $emp->team_lead_or_reporting_manager;
                        // $emp->team_lead_or_reporting_manager = $request->team_lead_or_reporting_manager;
                        $type = $request->internal_transfer_type;
                        $emp->work_location = $request->joining_location;
                    }
                    else if($request->joining_type == 'internal_transfer' && $request->internal_transfer_type == 'temporary') {
                        $type = $request->internal_transfer_type;
                    }
                    $emp->update();
                }
                $input = $request->all(); 
                $input['prepared_by_id'] = Auth::id();
                $input['created_by'] = Auth::id();
                if(isset($request->team_lead_or_reporting_manager)) {
                    $hanovr = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$request->team_lead_or_reporting_manager)->first();
                    if($hanovr == '') {
                        $createHandOvr = [];
                        $createHandOvr['lead_or_manager_id'] = $request->team_lead_or_reporting_manager;
                        $createHandOvr['approval_by_id'] = $request->team_lead_or_reporting_manager;
                        $createHandOvr['created_by'] = $authId;
                        $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                    }
                    $hanovrData = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$request->team_lead_or_reporting_manager)->first();
                    if($hanovrData) {
                        $input['department_head_id'] = $hanovrData->approval_by_id;
                    }
                   
                    if($request->joining_type == 'internal_transfer' && $request->internal_transfer_type == 'permanent') {
                        $input['new_reporting_manager'] = $request->team_lead_or_reporting_manager;
                        $input['old_reporting_manager'] = $oldRepMangr;
                    }
                }
                else {
                    $input['department_head_id'] = $emp->leadManagerHandover->approval_by_id;
                }
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input['hr_manager_id'] = $HRManager->handover_to_id;
                if($request->joining_type == 'new_employee') {
                    $input['candidate_id'] = $request->employee_id;
                    $input['employee_id'] = NULL;
                }
                else if($request->joining_type == 'internal_transfer' OR $request->joining_type == 'vacations_or_leave') {
                    $input['employee_id'] = $request->employee_id;
                    $input['candidate_id'] = NULL;
                }
                if($request->joining_type == 'internal_transfer') {
                    $input['internal_transfer_type'] = $request->internal_transfer_type;
                }
                if($request->joining_type == 'new_employee') {
                    $input['new_reporting_manager'] = $request->team_lead_or_reporting_manager;
                }
                $createJoinRep = JoiningReport::create($input);
                if($request->joining_type == 'vacations_or_leave') {
                    if(count($request->choose_leaves) > 0) {
                        foreach($request->choose_leaves as $leave_id) {
                            $leave = Leave::where('id',$leave_id)->first();
                            $leave->joining_reports_id = $createJoinRep->id;
                            $leave->update();
                        }
                    }
                }              
                $history['joining_report_id'] = $createJoinRep->id;
                $history['icon'] = 'icons8-document-30.png';
                $history['message'] = 'Employee joining report created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                $createHistory = JoiningReportHistory::create($history);
                $history2['joining_report_id'] = $createJoinRep->id;
                $history2['icon'] = 'icons8-send-30.png';
                $history2['message'] = 'Employee joining report send to Prepared by ( '.Auth::user()->name.' - '.Auth::user()->email.' ) for approval';
                $createHistory2 = JoiningReportHistory::create($history2);
                (new UserActivityController)->createActivity('New Employee joining report Created');               
                $successMessage = 'Employee Joining Report Created Successfully.';
                return redirect()->route('employee_joining_report.index', $type);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function update(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            // 'employee_code' => 'required',
            'joining_type' => 'required',
            'joining_date' => 'required',
            'joining_location' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                DB::commit();
                $authId = Auth::id();
                $type = '';
                $type = $request->joining_type;
                if($request->joining_type == 'internal_transfer' && $request->internal_transfer_type == 'permanent') {
                    $type = $request->internal_transfer_type;
                }
                else if($request->joining_type == 'internal_transfer' && $request->internal_transfer_type == 'temporary') {
                    $type = $request->internal_transfer_type;
                }
                if($request->joining_type == 'new_employee') {
                    $employ = EmployeeProfile::where('id',$request->employee_id)->first();
                    $employ->employee_code = $request->employee_code;                   
                }
                else {
                    $employ = EmployeeProfile::where('user_id',$request->employee_id)->first();
                }
                if($employ && $request->joining_type == 'new_employee') {
                    $employ->team_lead_or_reporting_manager = $request->team_lead_or_reporting_manager;
                }
                $employ->update();
                if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                    $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['created_by'] = $authId;
                    $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                }
                if($request->joining_type == 'new_employee') {
                    $emp = EmployeeProfile::where('id',$request->employee_id)->first();
                }
                else {
                    $emp = EmployeeProfile::where('user_id',$request->employee_id)->first();
                }
                $createJoinRep = JoiningReport::where('id',$id)->first();
                if($createJoinRep && $createJoinRep->status == 'pending') {
                    $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    if($request->joining_type == 'internal_transfer') {
                        $createJoinRep->transfer_from_department_id = $request->transfer_from_department_id;
                        $createJoinRep->transfer_from_date = $request->transfer_from_date;
                        $createJoinRep->transfer_from_location_id = $request->transfer_from_location_id;
                        $createJoinRep->transfer_to_department_id = $request->transfer_to_department_id;
                    }
                    if(($request->joining_type == 'internal_transfer' && $request->internal_transfer_type == 'permanent') || $request->joining_type == 'new_employee') {
                        $createJoinRep->new_reporting_manager = $request->team_lead_or_reporting_manager;
                    }
                    else{
                        $createJoinRep->new_reporting_manager = NULL;
                    }
                    $createJoinRep->joining_date = $request->joining_date;
                    $createJoinRep->joining_location = $request->joining_location;
                    $createJoinRep->joining_type = $request->joining_type;
                    $createJoinRep->new_emp_joining_type = $request->new_emp_joining_type;
                    $createJoinRep->prepared_by_id = Auth::id();
                    $createJoinRep->action_by_prepared_by = 'pending';
                    $createJoinRep->prepared_by_action_at = NULL;
                    $createJoinRep->comments_by_prepared_by = NULL;
                    $createJoinRep->employee_id = $request->employee_id;
                    $createJoinRep->action_by_employee = 'pending';
                    $createJoinRep->employee_action_at = NULL;
                    $createJoinRep->comments_by_employee = NULL;
                    $createJoinRep->hr_manager_id = $HRManager->handover_to_id;
                    $createJoinRep->action_by_hr_manager = NULL;
                    $createJoinRep->hr_manager_action_at = NULL;
                    $createJoinRep->comments_by_hr_manager = NULL;
                    $createJoinRep->department_head_id = $emp->leadManagerHandover->approval_by_id;
                    $createJoinRep->action_by_department_head = NULL;
                    $createJoinRep->department_head_action_at = NULL;
                    $createJoinRep->comments_by_department_head = NULL;                    
                    $createJoinRep->updated_by = Auth::id();
                    $createJoinRep->remarks = $request->remarks;
                    $createJoinRep->status = 'pending';
                    $createJoinRep->update();
                    $history['joining_report_id'] = $createJoinRep->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee joining report updated by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = JoiningReportHistory::create($history);
                    $history2['joining_report_id'] = $createJoinRep->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee joining report send to Prepared by ( '.Auth::user()->name.' - '.Auth::user()->email.' ) for approval';
                    $createHistory2 = JoiningReportHistory::create($history2);
                    (new UserActivityController)->createActivity('New Employee joining report Updated');   
                }   
                else {
                    // "can't update this joining report ,because it is already ". $update->status;
                }           
                $successMessage = 'Employee Joining Report Form Editted Successfully.';
                return redirect()->route('employee_joining_report.index', $type);
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
        $previous = $next = '';
        $data = JoiningReport::where('id',$id)->first();
        $previous = JoiningReport::where('id', '<', $id)->max('id');
        $next = JoiningReport::where('id', '>', $id)->min('id');
        if($data->joining_type == 'new_employee') {
            $empJoinings = JoiningReport::where('candidate_id',$data->candidate_id)->get();
        }else {
            $empJoinings = JoiningReport::where('employee_id',$data->employee_id)->get();
        }
        return view('hrm.onBoarding.joiningReport.show',compact('data','previous','next'));
    }
    public function edit($id) {
        $authId = Auth::id();
        $authUserDept = '';
        $authUserDept = EmployeeProfile::where('user_id',$authId)->first();
        $data = JoiningReport::where('id',$id)->with('candidate.department.division.divisionHead','candidate.department.departmentHead')->first();
        $candidates = EmployeeProfile::where([
            ['personal_information_verified_at','!=',NULL],
            ['type','candidate'],
        ])->whereHas('interviewSummary', function($q) {
            $q->where('offer_letter_verified_at','!=',NULL);
        });
        if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-edit-joining-report']) && $data->joining_type == 'new_employee') {
        $candidates = $candidates->where('department_id',$authUserDept->department_id)->latest();
        }
        $candidates = $candidates->with('designation','department.departmentHead','department.division.divisionHead')->get();
        $masterlocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get(); 
        $reportingTo = User::orderBy('name','ASC')->whereNotIn('id',[1,16])->where('status','active')->get();
        $employees = User::orderBy('name','ASC');
        if(Auth::user()->hasPermissionForSelectedRole(['dept-emp-edit-joining-report']) && $data->joining_type != 'new_employee') {
            $employees = $employees->where(function ($query4) use($authId){
                $query4-whereHas('empProfile', function($query7) use($authId) {
                    $query7->where('team_lead_or_reporting_manager',$authId);
                });
            })->latest();
        }
        $employees = $employees->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->with('empProfile.designation','empProfile.department','empProfile.location')->get();
        $masterDepartments = MasterDepartment::whereNot('name','Management')->with('departmentHead','division.divisionHead')->get();
        if($data->joining_type == 'new_employee') {
            return view('hrm.onBoarding.joiningReport.edit',compact('data','candidates','masterlocations','reportingTo'));
        }
        else if($data->joining_type == 'internal_transfer' && $data->internal_transfer_type == 'temporary') {
            return view('hrm.onBoarding.joiningReport.editInternalTransfer',compact('data','employees','masterlocations','reportingTo','masterDepartments'));
        }
        else if($data->joining_type == 'internal_transfer' && $data->internal_transfer_type == 'permanent') {
            return view('hrm.onBoarding.joiningReport.editPermanentTransfer',compact('data','employees','masterlocations','reportingTo','masterDepartments'));
        }
        else if($data->joining_type == 'vacations_or_leave') {
            return view('hrm.onBoarding.joiningReport.editVacationsOrLeave',compact('data','employees','masterlocations','reportingTo'));
        }
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $authId = Auth::id();
            $update = JoiningReport::where('id',$request->id)->first();
            if($update && $update->status == 'pending' && (
                ($request->current_approve_position == 'Prepared by' && $update->action_by_prepared_by == 'pending') 
                OR ($request->current_approve_position == 'Employee' && $update->action_by_employee == 'pending') 
                OR ($request->current_approve_position == 'HR Manager' && $update->action_by_hr_manager == 'pending') 
                OR ($request->current_approve_position == 'Reporting Manager' && $update->action_by_department_head == 'pending'))) {
            if($request->current_approve_position == 'Prepared by') {
                $update->comments_by_prepared_by = $request->comment;
                $update->prepared_by_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_prepared_by = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_employee = 'pending';
                    $message = 'Interview Summary Report send to Employee ( '.$update->preparedBy->name.' - '.$update->preparedBy->email.' ) for approval';
                }
                else {
                    $update->status = 'rejected';
                    $update->action_by_employee = NULL;
                }
                if($update->joining_type == 'new_employee' && $update->candidate->user_id == NULL && $request->status == 'approved') {
                    $data['id'] = Crypt::encrypt($update->id);
                    $data['send_by'] = Auth::user()->name;
                    $data['email'] = $update->candidate->personal_email_address;
                    $data['name'] = 'Dear '.$update->candidate->first_name.' '.$update->candidate->last_name.' ,';
                    $template['from'] = 'no-reply@milele.com'; 
                    $template['from_name'] = 'Milele Matrix';
                    $subject = 'Milele - Employee Joining Report Verification';
                    try {
                        Mail::send(
                            "hrm.onBoarding.joiningReport.email",
                            ["data"=>$data] ,
                            function($msg) use ($data,$template,$subject) {
                                $msg->to($data['email'], $data['name'])
                                    ->from($template['from'],$template['from_name'])
                                    ->subject($subject);
                            }
                        );
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }
                }
            }
            else if($request->current_approve_position == 'Employee') {
                $update->comments_by_employee = $request->comment;
                $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_employee = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'pending';
                    $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $update->hr_manager_id = $HRManager->handover_to_id;
                    $message = 'Interview Summary Report send to HR Manager ( '.$update->hr->name.' - '.$update->hr->email.' ) for approval';
                }else {
                    $update->status = 'rejected';
                }
            }
            else if($request->current_approve_position == 'HR Manager') {        
                $update->comments_by_hr_manager = $request->comment;
                $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_hr_manager = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_department_head = 'pending';
                    if(($update->joining_type == 'new_employee') || ($update->joining_type == 'internal_transfer' && $update->internal_transfer_type == 'permanent')) {
                        $leadOrMngr = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$update->new_reporting_manager)->first();
                        $update->department_head_id = $leadOrMngr->approval_by_id;
                    }
                    else {
                         $employee2 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                        $leadOrMngr = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$employee2->team_lead_or_reporting_manager)->first();
                        $update->department_head_id = $leadOrMngr->approval_by_id;
                    }
                    $message = 'Interview Summary Report send to Reporting Manager ( '.$update->reportingManager->name.' - '.$update->reportingManager->email.' ) for approval';
                }else {
                    $update->status = 'rejected';
                }
            }
            else if($request->current_approve_position == 'Reporting Manager') {
                $update->comments_by_department_head = $request->comment;
                $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_department_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'approved';
                    $update->status = 'approved';
                }else {
                    $update->status = 'rejected';
                }
                if($update->joining_type == 'internal_transfer' && $update->internal_transfer_type == 'permanent') {
                    $empUpdate = EmployeeProfile::where('user_id',$update->employee_id)->first();
                    if($empUpdate) {
                        $empUpdate->department_id = $update->transfer_to_department_id;
                        $empUpdate->team_lead_or_reporting_manager = $update->new_reporting_manager;
                        $empUpdate->updated_by = $authId;
                        $empUpdate->update();
                    }
                }
                else if($update->joining_type == 'new_employee' && $update->new_emp_joining_type == 'permanent') {
                    $empUpdate = EmployeeProfile::where('id',$update->candidate_id)->first();
                    if($empUpdate) {
                        $joinDate = JoiningReport::where([
                            ['candidate_id','==',$update->candidate_id],
                            ['joining_type','==',$update->joining_type],
                            ['status','approved'],
                        ])->orderBy('joining_date','DESC')->first();
                        if($joinDate) {
                            $empUpdate->company_joining_date = $joinDate->joining_date;
                        }
                        // if($empUpdate->user_id == '') {
                        //     $createUser['name'] = $empUpdate->first_name.' '.$empUpdate->last_name;
                        //     $createUser['created_by'] = $authId;
                        //     $createUserData = User::create($createUser);
                        //     $empUpdate->user_id = $createUserData->id;
                        //     $getallJoin = JoiningReport::where([
                        //         ['candidate_id','==',$update->candidate_id],
                        //         ['joining_type','==',$update->joining_type],
                        //     ])->get();
                        //     if(count($getallJoin) > 0) {
                        //         foreach($getallJoin as $getJoin) {
                        //             $getJoin['employee_id'] = $createUserData->id;
                        //             $getJoin['updated_by'] = $authId;
                        //             $getJoin->update(); 
                        //         }
                        //     }
                        // }
                        $empUpdate->work_location = $update->joining_location;
                        $empUpdate->department_id = $update->transfer_to_department_id ?? $update->candidate->interviewSummary->employeeHiringRequest->department_id ?? '';
                        $empUpdate->team_lead_or_reporting_manager = $update->new_reporting_manager;
                        $empUpdate->gender = $update->candidate->interviewSummary->gender ?? '';
                        $empUpdate->nationality = $update->candidate->interviewSummary->nationality ?? '';
                        $empUpdate->type = 'employee';
                        $empUpdate->updated_by = $authId;
                        $empUpdate->update();
                    }
                }
            }
            $update->update();
            $history['joining_report_id'] = $update->id;
            if($request->status == 'approved') {
                $history['icon'] = 'icons8-thumb-up-30.png';
            }
            else if($request->status == 'rejected') {
                $history['icon'] = 'icons8-thumb-down-30.png';
            }
            $history['message'] = 'Employee Joining Report '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
            $createHistory = JoiningReportHistory::create($history);  
            if($request->status == 'approved' && $message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = JoiningReportHistory::create($history);
            }
            (new UserActivityController)->createActivity($history['message']);
            DB::commit();
            return response()->json('success');
        }
        else {
            return response()->json('error');  
        }

        } 
        catch (\Exception $e) {
            DB::rollback();
            info($e);
            $errorMsg ="Something went wrong! Contact your admin";
            return view('hrm.notaccess',compact('errorMsg'));
        }
    }
    public function employeeVerification($id) {
        if($id != NULL) {
            DB::beginTransaction();
            try {
                $id = Crypt::decrypt($id); 
                $data = JoiningReport::where('id',$id)->first(); 
                DB::commit();
                return view('hrm.onBoarding.joiningReport.employeeVerification',compact('data'));
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
        else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }
    public function employeeVerified(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = JoiningReport::where('id',$request->id)->first();
            if($update && $update->status == 'pending') {

           
            $update->comments_by_employee = $request->comments_by_employee;
            $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_employee = 'approved';
            $update->action_by_hr_manager = 'pending';
            $message = 'Interview Summary Report send to HR Manager ( '.$update->hr->name.' - '.$update->hr->email.' ) for approval';
            $update->update();
            $history['joining_report_id'] = $request->id;
            $history['icon'] = 'icons8-thumb-up-30.png';
            $history['message'] = 'Employee Joining Report verified by '.$update->candidate->first_name.' '.$update->candidate->last_name.' - '.$update->candidate->personal_email_address.' )';
            $createHistory = JoiningReportHistory::create($history);
            if($message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = JoiningReportHistory::create($history);
            }
            (new UserActivityController)->createActivity($history['message']);
            $successMessage = 'Employee Joining Report Successfully Verified By Employee.';

        }
        else {
            $successMessage = "can't update this candidatejoinig report ,because it is already ". $update->status;;

        }
            DB::commit();
            return view('hrm.hiring.documents.successPersonalinfo',compact('successMessage'));
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
        $deptHead = $preparedByPendings = $preparedByApproved = $preparedByRejected = $employeePendings = $employeeApproved = $employeeRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = [];
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $preparedByPendings = JoiningReport::where([
            ['action_by_prepared_by','pending'],
            ['prepared_by_id',$authId],
            ])->latest()->get();
        $preparedByApproved = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['prepared_by_id',$authId],
            ])->latest()->get();
        $preparedByRejected = JoiningReport::where([
            ['action_by_prepared_by','rejected'],
            ['prepared_by_id',$authId],
            ])->latest()->get();
        $employeePendings = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','pending'],
            ])->whereHas('user' , function($q) use($authId){
                $q->where('id', $authId);
            })->latest()->get();
        $employeeApproved = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],
            ])->whereHas('user' , function($q) use($authId){
                $q->where('id', $authId);
            })->latest()->get();
        $employeeRejected = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','rejected'],
            ])->whereHas('user' , function($q) use($authId){
                $q->where('id', $authId);
            })->latest()->get();
        if($HRManager) {
        $HRManagerPendings = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerApproved = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerRejected = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],                
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        }
        $ReportingManagerPendings = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerApproved = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_department_head','approved'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerRejected = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],                
            ['action_by_hr_manager','approved'],
            ['action_by_department_head','rejected'],
            ['department_head_id',$authId],
            ])->latest()->get();
        return view('hrm.onBoarding.joiningReport.approvals',compact('page','preparedByPendings','preparedByApproved','preparedByRejected','employeePendings',
        'employeeApproved','employeeRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected','ReportingManagerPendings','ReportingManagerApproved','ReportingManagerRejected'));
    }
    public function uniqueJoiningReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'joining_type' => 'required',
            'employeeId' => 'required',
            'new_emp_joining_type' => 'required',
            'joining_date' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            try {
                $jr = JoiningReport::where([
                    ['joining_type',$request->joining_type],
                    ['candidate_id',$request->employeeId[0]],
                ]);
                if(isset($request->joining_report_id)) {
                    $jr = $jr->whereNot('id',$request->joining_report_id);
                }
                if($request->new_emp_joining_type == 'trial_period') {
                    $jr = $jr->whereIn('new_emp_joining_type',['trial_period','permanent'])->whereIn('status',['pending','approved']);
                }
                else if($request->new_emp_joining_type == 'permanent') {
                    $jr = $jr->where(function($query) {
                        $query->where('new_emp_joining_type','trial_period')->where('status','pending');
                    })->orWhere(function($query1) {
                        $query1->where('new_emp_joining_type','permanent')->whereIn('status',['pending','approved']);
                    });
                }
                $jr = $jr->get();
                if(count($jr) > 0) {
                    return false;
                }
                else {
                    return true;
                }
           } 
           catch (\Exception $e) {
               info($e);
           }
        }
    }
}
