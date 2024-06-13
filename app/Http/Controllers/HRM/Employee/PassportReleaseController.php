<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\PassportRelease;
use App\Models\HRM\Employee\PassportReleaseHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\User;
use App\Models\Masters\PassportRequestPurpose;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\Masters\MasterDivisionWithHead;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class PassportReleaseController extends Controller
{
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $HRManager = '';
        $deptHead = $employeePendings = $employeeApproved = $employeeRejected = $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = 
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = $hrManagerPendings = $hrManagerApproved = $hrManagerRejected = [];
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $employeePendings = PassportRelease::where([
            ['release_action_by_employee','pending'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeApproved = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeRejected = PassportRelease::where([
            ['release_action_by_employee','rejected'],
            ['employee_id',$authId],
            ])->latest()->get();
        $reportingManagerPendings = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','pending'],
            ['release_department_head_id',$authId],
            ])->latest()->get();
        $reportingManagerApproved = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],
            ['release_department_head_id',$authId],
            ])->latest()->get();
        $reportingManagerRejected = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','rejected'],
            ['release_department_head_id',$authId],
            ])->latest()->get();
        $divisionHeadPendings = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],
            ['release_action_by_division_head','pending'],
            ['release_division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadApproved = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],
            ['release_action_by_division_head','approved'],
            ['release_division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadRejected = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],                
            ['release_action_by_division_head','rejected'],
            ['release_division_head_id',$authId],
            ])->latest()->get();       
        $hrManagerPendings = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],
            ['release_action_by_division_head','approved'],
            ['release_action_by_hr_manager','pending'],
            ['release_hr_manager_id',$authId],
            ])->latest()->get();
        $hrManagerApproved = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],
            ['release_action_by_division_head','approved'],
            ['release_action_by_hr_manager','approved'],
            ['release_hr_manager_id',$authId],
            ])->latest()->get();
        $hrManagerRejected = PassportRelease::where([
            ['release_action_by_employee','approved'],
            ['release_action_by_department_head','approved'],                
            ['release_action_by_division_head','approved'],
            ['release_action_by_hr_manager','rejected'],
            ['release_hr_manager_id',$authId],
            ])->latest()->get();
        return view('hrm.passport.passport_release.approvals',compact('page','employeePendings','employeeApproved','employeeRejected','reportingManagerPendings',
        'reportingManagerApproved','reportingManagerRejected','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected','hrManagerPendings','hrManagerApproved','hrManagerRejected'));
    }
    public function edit($id) {
        $data = PassportRelease::where('id',$id)->first();
        $Users = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereNot('is_management','yes')->whereHas('empProfile')->get();
        $masterEmployees = [];
        $currentUser = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereNot('is_management','yes')->where('id',$data->employee_id)->first();        
        if($currentUser) {
            array_push($masterEmployees,$currentUser);  
        }
        foreach($Users as $User) {
            if($User->can_submit_or_release_passport == true) {
                array_push($masterEmployees,$User);  
            }
        }
        $submissionPurpose = PassportRequestPurpose::where('type','submit')->get();
        $releasePurpose = PassportRequestPurpose::where('type','release')->get();
        return view('hrm.passport.passport_release.edit',compact('data','masterEmployees','submissionPurpose','releasePurpose'));
    }
    public function index() {
        $authId = Auth::id();
        $pendings = PassportRelease::where('release_submit_status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-passport-request-list'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings =$pendings->get();
        $approved = PassportRelease::where('release_submit_status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-passport-request-list'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved =$approved->get();
        $rejected = PassportRelease::where('release_submit_status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['view-passport-request-list'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-passport-request-list'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected =$rejected->get();
        return view('hrm.passport.passport_release.index',compact('pendings','approved','rejected'));
    }
    public function requestAction(Request $request) {
        $message = '';
        $update = PassportRelease::where('id',$request->id)->first();
        // employee -------> Reporting Manager  ------------>Division Head--------->HR Manager       
        if($request->current_approve_position == 'Employee') {
            $update->release_comments_by_employee = $request->comment;
            $update->release_employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_employee = $request->status;
            if($request->status == 'approved') {
                $update->release_action_by_department_head = 'pending';
                $employee2 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                $leadOrMngr = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$employee2->team_lead_or_reporting_manager)->first();
                $update->release_department_head_id = $leadOrMngr->approval_by_id;
                $message = 'Employee passport release request send to Reporting Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Reporting Manager') {
            $update->release_comments_by_department_head = $request->comment;
            $update->release_department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_department_head = $request->status;
            if($request->status == 'approved') {
                $update->release_action_by_division_head = 'pending';
                $employee1 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                $divisionHead1 = MasterDivisionWithHead::where('id',$employee1->department->division_id)->first();
                $update->release_division_head_id = $divisionHead1->approval_handover_to;
                $message = 'Employee passport release request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Division Head') {
            $update->release_comments_by_division_head = $request->comment;
            $update->release_division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_division_head = $request->status;
            if($request->status == 'approved') {
                $update->release_action_by_hr_manager = 'pending';
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $update->release_hr_manager_id = $HRManager->handover_to_id;
                $message = 'Employee passport release request send to HR Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'HR Manager') {
            $update->release_comments_by_hr_manager = $request->comment;
            $update->release_hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->release_action_by_hr_manager = $request->status;
            if($request->status == 'approved') {
                $update->release_submit_status = 'approved';
                $emp = EmployeeProfile::where('user_id',$update->employee_id)->first();
                $emp->passport_status = 'with_employee';
                $emp->update();
            }
        }
        if($request->status == 'rejected') {
            $update->release_submit_status = 'rejected';
        }
        $update->update();
        $history['passport_release_id'] = $request->id;
        if($request->status == 'approved') {
            $history['icon'] = 'icons8-thumb-up-30.png';
        }
        else if($request->status == 'rejected') {
            $history['icon'] = 'icons8-thumb-down-30.png';
        }
        $history['message'] = 'Employee passport release request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
        $createHistory = PassportReleaseHistory::create($history);  
        if($request->status == 'approved' && $message != '') {
            $history['icon'] = 'icons8-send-30.png';
            $history['message'] = $message;
            $createHistory = PassportReleaseHistory::create($history);
        }
        (new UserActivityController)->createActivity($history['message']);
        return response()->json('success');
        // ,'New Employee Hiring Request '.$request->status.' Successfully'
    }
    public function show($id) {
        $data = PassportRelease::where('id',$id)->first();
        $previous = PassportRelease::where('id', '<', $id)->max('id');
        $next = PassportRelease::where('id', '>', $id)->min('id');
        return view('hrm.passport.passport_release.show',compact('data','previous','next'));
    }
}
