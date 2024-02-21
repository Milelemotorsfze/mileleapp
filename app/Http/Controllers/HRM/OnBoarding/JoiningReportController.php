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

class JoiningReportController extends Controller
{
    public function index($type) {
        $authId = Auth::id();
        $pendings = JoiningReport::where(function ($query) {
            $query = $query->where('action_by_prepared_by',NULL)->orWhere('action_by_prepared_by','pending')->orWhere('action_by_prepared_by','approved');
        })
        ->where(function ($query) {
            $query = $query->where('action_by_employee',NULL)->orWhere('action_by_employee','pending')->orWhere('action_by_employee','approved');
        })
        ->where(function ($query) {
            $query = $query->where('action_by_hr_manager',NULL)->orWhere('action_by_hr_manager','pending')->orWhere('action_by_hr_manager','approved');
        })
        ->where(function ($query) {
            $query = $query->where('action_by_department_head',NULL)->orWhere('action_by_department_head','pending');
        })
        ;
        if($type != NULL) {
            $pendings = $pendings->where('joining_type',$type);
        }
        if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings = $pendings->get();
        $approved = JoiningReport::where('action_by_department_head','approved');
        if($type != NULL) {
            $approved = $approved->where('joining_type',$type);
        }
        if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved = $approved->get();
        $rejected = JoiningReport::where(function ($query) {
            $query = $query->where('action_by_department_head','rejected')->orWhere('action_by_hr_manager','rejected')
            ->orWhere('action_by_employee','rejected')->orWhere('action_by_prepared_by','rejected');
        });
        if($type != NULL) {
            $rejected = $rejected->where('joining_type',$type);
        }
        if(Auth::user()->hasPermissionForSelectedRole(['view-joining-report-listing'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-joining-report-listing'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected = $rejected->get();
        return view('hrm.onBoarding.joiningReport.index',compact('pendings','approved','rejected','type'));
    }
    public function create($type) { 
        $candidates = EmployeeProfile::where([
            ['personal_information_verified_at','!=',NULL],
            ['type','candidate'],
        ])->whereHas('interviewSummary', function($q) {
            $q->where('offer_letter_verified_at','!=',NULL);
        })->with('designation','department')->get();
        $masterlocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get(); 
        $reportingTo = User::where([
            ['id','!=',16],
            ['status','active']
        ])->get();
        $masterDepartments = MasterDepartment::get();
        $employees = User::whereHas('empProfile');
        if($type == 'vacations_or_leave') {
            $employees = $employees->whereHas('approvedLeaves');
        }
        $employees = $employees->with('empProfile.designation','empProfile.department','empProfile.location','approvedLeaves')->get();
        if($type == 'new_employee') {
            return view('hrm.onBoarding.joiningReport.create',compact('candidates','masterlocations','reportingTo','type','masterDepartments'));
        }
        else if($type == 'internal_transfer') {
            return view('hrm.onBoarding.joiningReport.createInternalTransfer',compact('employees','masterlocations','reportingTo','type','masterDepartments'));
        }
        else if($type == 'vacations_or_leave') {
            return view('hrm.onBoarding.joiningReport.createVacationsOrLeave',compact('employees','masterlocations','reportingTo','type','masterDepartments'));
        }
    }
    public function store(Request $request) { 
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'employee_code' => 'required',
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
                if($request->joining_type == 'new_employee') {
                    $emp = EmployeeProfile::where('id',$request->employee_id)->first();
                }
                else if($request->joining_type == 'internal_transfer' OR $request->joining_type == 'vacations_or_leave') {
                    $emp = EmployeeProfile::where('user_id',$request->employee_id)->first();
                }
                if($emp) {
                    $emp->employee_code = $request->employee_code;
                    if(isset($request->team_lead_or_reporting_manager)) {
                        $emp->team_lead_or_reporting_manager = $request->team_lead_or_reporting_manager;
                    }
                    $emp->update();
                }
                $input = $request->all(); 
                $input['prepared_by_id'] = Auth::id();
                $input['created_by'] = Auth::id();
                if(isset($request->team_lead_or_reporting_manager)) {
                    $input['department_head_id'] = $request->team_lead_or_reporting_manager;
                }
                else {
                    $input['department_head_id'] = $emp->team_lead_or_reporting_manager;
                }
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input['hr_manager_id'] = $HRManager->handover_to_id;
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
                return redirect()->route('employee_joining_report.index', $request->joining_type);
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function update(Request $request,$id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'employee_code' => 'required',
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
                $emp = EmployeeProfile::where('id',$request->employee_id)->first();
                if($emp) {
                    $emp->employee_code = $request->employee_code;
                    $emp->team_lead_or_reporting_manager = $request->team_lead_or_reporting_manager;
                    $emp->update();
                }
                $createJoinRep = JoiningReport::where('id',$id)->first();
                if($createJoinRep) {
                    $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    if($request->joining_type == 'internal_transfer') {
                        $createJoinRep->transfer_from_department_id = $request->transfer_from_department_id;
                        $createJoinRep->transfer_from_date = $request->transfer_from_date;
                        $createJoinRep->transfer_from_location_id = $request->transfer_from_location_id;
                        $createJoinRep->transfer_to_department_id = $request->transfer_to_department_id;
                    }
                    $createJoinRep->joining_date = $request->joining_date;
                    $createJoinRep->joining_location = $request->joining_location;
                    $createJoinRep->joining_type = $request->joining_type;
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
                    $createJoinRep->department_head_id = $request->team_lead_or_reporting_manager;
                    $createJoinRep->action_by_department_head = NULL;
                    $createJoinRep->department_head_action_at = NULL;
                    $createJoinRep->comments_by_department_head = NULL;                    
                    $createJoinRep->updated_by = Auth::id();
                    $createJoinRep->remarks = $request->remarks;
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
                $successMessage = 'Employee Joining Report Form Editted Successfully.';
                return redirect()->route('employee_joining_report.index', $request->joining_type);
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function show($id) {
        $previous = $next = '';
        $data = JoiningReport::where('id',$id)->first();
        $previous = JoiningReport::where('id', '<', $id)->max('id');
        $next = JoiningReport::where('id', '>', $id)->min('id');
        if($data) {
            $empJoinings = JoiningReport::where('employee_id',$data->employee_id)->get();
        }
        return view('hrm.onBoarding.joiningReport.show',compact('data','previous','next'));
    }
    public function edit($id) {
        $data = JoiningReport::where('id',$id)->first();
        $candidates = EmployeeProfile::where([
            ['personal_information_verified_at','!=',NULL],
            ['type','candidate'],
        ])->whereHas('interviewSummary', function($q) {
            $q->where('offer_letter_verified_at','!=',NULL);
        })->with('designation','department')->get();
        $masterlocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get(); 
        $reportingTo = User::where([
            ['id','!=',16],
            ['status','active']
        ])->get();
        $employees = User::whereHas('empProfile')->with('empProfile.designation','empProfile.department','empProfile.location')->get();
        $masterDepartments = MasterDepartment::get();
        if($data->joining_type == 'new_employee') {
            return view('hrm.onBoarding.joiningReport.edit',compact('data','candidates','masterlocations','reportingTo'));
        }
        else if($data->joining_type == 'internal_transfer') {
            return view('hrm.onBoarding.joiningReport.editInternalTransfer',compact('data','employees','masterlocations','reportingTo','masterDepartments'));
        }
        else if($data->joining_type == 'vacations_or_leave') {
            return view('hrm.onBoarding.joiningReport.editVacationsOrLeave',compact('data','employees','masterlocations','reportingTo'));
        }
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = JoiningReport::where('id',$request->id)->first();
            if($request->current_approve_position == 'Prepared by') {
                $update->comments_by_prepared_by = $request->comment;
                $update->prepared_by_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_prepared_by = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_employee = 'pending';
                    $message = 'Interview Summary Report send to Employee ( '.$update->preparedBy->name.' - '.$update->preparedBy->email.' ) for approval';
                }
                if($update->employee->user_id == NULL) {
                    $data['id'] = Crypt::encrypt($update->id);
                    $data['send_by'] = Auth::user()->name;
                    $data['email'] = $update->employee->personal_email_address;
                    $data['name'] = 'Dear '.$update->employee->first_name.' '.$update->employee->last_name.' ,';
                    $template['from'] = 'no-reply@milele.com'; 
                    $template['from_name'] = 'Milele Matrix';
                    $subject = 'Milele - Employee Joining Report Verification';
                    Mail::send(
                        "hrm.onBoarding.joiningReport.email",
                        ["data"=>$data] ,
                        function($msg) use ($data,$template,$subject) {
                            $msg->to($data['email'], $data['name'])
                                ->from($template['from'],$template['from_name'])
                                ->subject($subject);
                        }
                    );
                }
            }
            else if($request->current_approve_position == 'Employee') {
                $update->comments_by_employee = $request->comment;
                $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_employee = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'pending';
                    $message = 'Interview Summary Report send to HR Manager ( '.$update->hr->name.' - '.$update->hr->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'HR Manager') {        
                $update->comments_by_hr_manager = $request->comment;
                $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_hr_manager = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_department_head = 'pending';
                    $message = 'Interview Summary Report send to Reporting Manager ( '.$update->reportingManager->name.' - '.$update->reportingManager->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Reporting Manager') {
                $update->comments_by_department_head = $request->comment;
                $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_department_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'approved';
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
        catch (\Exception $e) {
            DB::rollback();
            dd($e);
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
                dd($e);
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
            $update->comments_by_employee = $request->comments_by_employee;
            $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_employee = 'approved';
            $update->action_by_hr_manager = 'pending';
            $message = 'Interview Summary Report send to HR Manager ( '.$update->hr->name.' - '.$update->hr->email.' ) for approval';
            $update->update();
            $history['message'] = 'Employee Joining Report verified by '.$update->employee->first_name.' '.$update->employee->last_name.' - '.$update->employee->personal_email_address.' )';
            $createHistory = JoiningReportHistory::create($history);
            if($message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = JoiningReportHistory::create($history);
            }
            (new UserActivityController)->createActivity($history['message']);
            DB::commit();
            $successMessage = 'Employee Joining Report Successfully Verified By Employee.';
            return view('hrm.hiring.documents.successPersonalinfo',compact('successMessage'));
        } 
        catch (\Exception $e) {
            DB::rollback();
            dd($e);
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
            ])->whereHas('employee' , function($q) use($authId){
                $q->where('user_id', $authId);
            })->latest()->get();
        $employeeApproved = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','approved'],
            ])->whereHas('employee' , function($q) use($authId){
                $q->where('user_id', $authId);
            })->latest()->get();
        $employeeRejected = JoiningReport::where([
            ['action_by_prepared_by','approved'],
            ['action_by_employee','rejected'],
            ])->whereHas('employee' , function($q) use($authId){
                $q->where('user_id', $authId);
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
}
