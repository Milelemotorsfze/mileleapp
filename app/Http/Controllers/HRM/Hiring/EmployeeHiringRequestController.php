<?php

namespace App\Http\Controllers\HRM\Hiring;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDepartment;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Hiring\EmployeeHiringQuestionnaire;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\User;
use Validator;
use DB;
use Exception;
use Carbon\Carbon;
use App\Http\Controllers\UserActivityController;
// use Haruncpi\LaravelIdGenerator\IdGenerator;

class EmployeeHiringRequestController extends Controller
{
    public function index() {
        $authId = Auth::id();
        $page = 'listing';

        $pendings = EmployeeHiringRequest::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-pending-hiring-request-listing'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-pending-hiring-request-listing-of-current-user'])) {
            $pendings = $pendings->where('requested_by',$authId)->latest();
        }
        $pendings = $pendings->get();

        $approved = EmployeeHiringRequest::where([
            ['status','approved'],
            ['final_status','open'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-approved-hiring-request-listing-of-current-user'])) {
            $approved = $approved->where('requested_by',$authId)->latest();
        }
        $approved =$approved->get();
        
        $closed = EmployeeHiringRequest::where([
            ['status','approved'],
            ['final_status','closed'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing'])) {
            $closed = $closed->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-closed-hiring-request-listing-of-current-user'])) {
            $closed = $closed->where('requested_by',$authId)->latest();
        }
        $closed = $closed->get();

        $onHold = EmployeeHiringRequest::where([
            ['status','approved'],
            ['final_status','onhold'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing'])) {
            $onHold = $onHold->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-closed-hiring-request-listing-of-current-user'])) {
            $onHold = $onHold->where('requested_by',$authId)->latest();
        }
        $onHold = $onHold->get();

        $cancelled = EmployeeHiringRequest::where([
            ['status','approved'],
            ['final_status','cancelled'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing'])) {
            $cancelled = $cancelled->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-closed-hiring-request-listing-of-current-user'])) {
            $cancelled = $cancelled->where('requested_by',$authId)->latest();
        }
        $cancelled = $cancelled->get();

        $rejected = EmployeeHiringRequest::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-closed-hiring-request-listing-of-current-user'])) {
            $rejected = $rejected->where('requested_by',$authId)->latest();
        }
        $rejected = $rejected->get();
        $deleted = [];
        $deleted = EmployeeHiringRequest::onlyTrashed();
        if(Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing'])) {
            $deleted = $deleted->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-closed-hiring-request-listing-of-current-user'])) {
            $deleted = $deleted->where('requested_by',$authId)->latest();
        }
        $deleted = $deleted->get();
        return view('hrm.hiring.hiring_request.index',compact('pendings','approved','closed','onHold','cancelled','rejected','deleted','page'));
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new EmployeeHiringRequest();
            $previous = $next = '';
        }
        else {
            $data = EmployeeHiringRequest::find($id);
            $previous = EmployeeHiringRequest::where('status',$data->status)->where('id', '<', $id)->max('id');
            $next = EmployeeHiringRequest::where('status',$data->status)->where('id', '>', $id)->min('id');
        }
        $masterdepartments = MasterDepartment::where('status','active')->select('id','name')->get();
        $masterExperienceLevels = MasterExperienceLevel::select('id','name','number_of_year_of_experience')->get();
        $masterJobPositions = MasterJobPosition::select('id','name')->get();
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        $requestedByUsers = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        $reportingToUsers = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        $replacementForEmployees = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        return view('hrm.hiring.hiring_request.create',compact('id','data','previous','next','masterdepartments','masterExperienceLevels','masterJobPositions','masterOfficeLocations',
            'requestedByUsers','reportingToUsers','replacementForEmployees'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        // public function store(Request $request){
// $prefix = 'A';
//             $uuid = IdGenerator::generate(['table' => 'employee_hiring_requests', 'length' => 7, 'prefix' => $prefix]);
//         dd($uuid);
        //     $todo = new Todo();
        //     $todo->id = $id;
        //     $todo->title = $request->get('title');
        //     $todo->save();
        
        // }
        // $uuid = IdGenerator::generate(['table' => 'employee_hiring_requests', 'length' => 10, 'prefix' =>'INV-']);
        // dd($uuid);
//output: INV-000001
        $validator = Validator::make($request->all(), [
            'request_date' => 'required',
            'department_id' => 'required',
            'location_id' => 'required',
            'requested_by' => 'required',
            'requested_job_title' => 'required',
            // 'reporting_to' => 'required',
            'experience_level' => 'required',
            'salary_range_start_in_aed' => 'required',
            'salary_range_end_in_aed' => 'required',
            'work_time_start' => 'required',
            // 'reporting_to' => 'required',
            'work_time_end' => 'required',
            'number_of_openings' => 'required',
            'type_of_role' => 'required',
            'explanation_of_new_hiring' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $teamLeadOrReportingManager = EmployeeProfile::where('user_id',$request->requested_by)->first();
                $department = MasterDepartment::where('id',$request->department_id)->first();
                $hiringManager = ApprovalByPositions::where('approved_by_position','Recruiting Manager')->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input = $request->all();
                if($id == 'new') {
                    // Approvals =>  Team Lead/Manager -------> Recruitement(Hiring) manager -----------> Division head ---------> HR manager
                    $input['created_by'] = $authId;
                    // $input['department_head_id'] = $department->approval_by_id;
                    $input['department_head_id'] = $teamLeadOrReportingManager->team_lead_or_reporting_manager;
                    $input['action_by_department_head'] = 'pending';
                    $input['hiring_manager_id'] = $hiringManager->handover_to_id;
                    $input['division_head_id'] = $department->division->approval_handover_to;
                    $input['hr_manager_id'] = $HRManager->handover_to_id;
                    $createRequest = EmployeeHiringRequest::create($input);
                    $history['hiring_request_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee hiring request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    $history2['hiring_request_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee hiring request send to Team Lead / Reporting Manager ( '.$teamLeadOrReportingManager->teamLeadOrReportingManager->name.' - '.$teamLeadOrReportingManager->teamLeadOrReportingManager->email.' ) for approval';
                    // $history2['message'] = 'Employee hiring request send to Team Lead / Reporting Manager ( '.$department->approval_by_name.' - '.$department->approval_by_email.' ) for approval';
                    // $history2['message'] = 'Employee hiring request send to '.$hiringManager->approved_by_position_name.' ( '.$hiringManager->handover_to_name.' - '.$hiringManager->handover_to_email.' ) for approval';
                    $createHistory2 = EmployeeHiringRequestHistory::create($history2);
                    (new UserActivityController)->createActivity('New Employee Hiring Request Created');
                    $successMessage = "New Employee Hiring Request Created Successfully";
                }
                else {
                    $update = EmployeeHiringRequest::find($id);
                    if($update) {
                        $update->request_date = $request->request_date;
                        $update->department_id = $request->department_id;
                        $update->location_id = $request->location_id;
                        $update->requested_by = $request->requested_by;
                        $update->requested_job_title = $request->requested_job_title;
                        // $update->reporting_to = $request->reporting_to;
                        $update->experience_level = $request->experience_level;
                        $update->salary_range_start_in_aed = $request->salary_range_start_in_aed;
                        $update->salary_range_end_in_aed = $request->salary_range_end_in_aed;
                        $update->work_time_start = $request->work_time_start;
                        $update->work_time_end = $request->work_time_end;
                        $update->number_of_openings = $request->number_of_openings;
                        $update->type_of_role = $request->type_of_role;
                        if($request->type_of_role == 'replacement') {
                            $update->replacement_for_employee = $request->replacement_for_employee;
                        }
                        else {
                            $update->replacement_for_employee = NULL;
                        }
                        $update->explanation_of_new_hiring = $request->explanation_of_new_hiring;
                        $update->updated_by = $authId;
                        $update->action_by_department_head = 'pending';
                        $update->department_head_action_at = NULL;
                        // $update->comments_by_department_head = NULL;
                        $update->action_by_hiring_manager = NULL;
                        $update->hiring_manager_action_at =  NULL;
                        // $update->comments_by_hiring_manager = NULL;
                        $update->action_by_division_head = NULL;
                        $update->division_head_action_at = NULL;
                        // $update->comments_by_division_head = NULL;
                        $update->action_by_hr_manager = NULL;
                        $update->hr_manager_action_at = NULL;
                        // $update->comments_by_hr_manager = NULL;                     
                        $update->update();
                        $history['hiring_request_id'] = $id;
                        $history['icon'] = 'icons8-edit-30.png';
                        $history['message'] = 'Employee hiring request edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = EmployeeHiringRequestHistory::create($history);
                        $history2['hiring_request_id'] = $id;
                        $history2['icon'] = 'icons8-send-30.png';
                        $history2['message'] = 'Employee hiring request send to Team Lead / Reporting Manager ( '.$teamLeadOrReportingManager->teamLeadOrReportingManager->name.' - '.$teamLeadOrReportingManager->teamLeadOrReportingManager->email.' ) for approval';
                        $createHistory2 = EmployeeHiringRequestHistory::create($history2);
                        (new UserActivityController)->createActivity('Employee Hiring Request Edited');
                        $successMessage = "Employee Hiring Request Updated Successfully";
                    }
                }
                DB::commit();
                return redirect()->route('employee-hiring-request.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function show($id) {
        $data = EmployeeHiringRequest::where('id',$id)->first();

        $countSelectedForInterview = count($data->selectedForInterview);
        $countTelephonicRoundCompleted = count($data->telephonicRoundCompleted);
        $countFirstRoundCompleted = count($data->firstRoundCompleted);
        $countSecondRoundCompleted = count($data->secondRoundCompleted);
        $countThirdRoundCompleted = count($data->thirdRoundCompleted);
        $countForthRoundCompleted = count($data->forthRoundCompleted);
        $countFifthRoundCompleted = count($data->fifthRoundCompleted);
        $countDivisionHeadApprovalAwaitingCandidates = count($data->divisionHeadApprovalAwaitingCandidates);
        $countHrApprovalAwaitingCandidates = count($data->hrApprovalAwaitingCandidates);
        $countRejectedCandidates = count($data->rejectedCandidates);
        $countApprovedSelectedCandidates = count($data->approvedSelectedCandidates);
        $countSelectedCandidates = count($data->selectedCandidates);
        
        $data->allInterview = [];
        $data->allInterview = $data->selectedCandidates->merge($data->approvedSelectedCandidates)->merge($data->rejectedCandidates)->merge($data->hrApprovalAwaitingCandidates)
                              ->merge($data->divisionHeadApprovalAwaitingCandidates)->merge($data->fifthRoundCompleted)->merge($data->forthRoundCompleted)
                              ->merge($data->thirdRoundCompleted)->merge($data->secondRoundCompleted)->merge($data->firstRoundCompleted)->merge($data->telephonicRoundCompleted)
                              ->merge($data->selectedForInterview);
        foreach($data->allInterview as $oneCandidated) {
            $oneCandidated->isAuth = '';  
            $emp = '';
            $emp = EmployeeProfile::where('interview_summary_id',$oneCandidated->id)->first();
            if($emp && $emp->offer_sign != NULL && $emp->offer_signed_at != NULL && $emp->offer_letter_hr_id != NULL) {
                $oneCandidated->isAuth = 2;
            }
            else if($oneCandidated->offer_letter_send_at != NULL && $emp->offer_sign == NULL && $emp->offer_signed_at == NULL && $emp->offer_letter_hr_id == NULL) {
                $oneCandidated->isAuth = 0;
            }
            $oneCandidated->canVerifySign = true;
        }
        $previous = EmployeeHiringRequest::where('status',$data->status)->where('id', '<', $id)->max('id');
        $next = EmployeeHiringRequest::where('status',$data->status)->where('id', '>', $id)->min('id');
        return view('hrm.hiring.hiring_request.show',compact('data','previous','next','countSelectedForInterview','countTelephonicRoundCompleted','countFirstRoundCompleted',
        'countSecondRoundCompleted','countThirdRoundCompleted','countForthRoundCompleted','countFifthRoundCompleted','countDivisionHeadApprovalAwaitingCandidates',
        'countHrApprovalAwaitingCandidates','countRejectedCandidates','countApprovedSelectedCandidates','countSelectedCandidates'));
    }
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $hiringManager = $HRManager = '';
        $deptHead = 
        $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = 
        $hiringManagerPendings = $hiringManagerApproved = $hiringManagerRejected = 
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $hiringManager = ApprovalByPositions::where([
            ['approved_by_position','Recruiting Manager'],
            ['handover_to_id',$authId]
        ])->first();
        // Approvals =>  Team Lead/Manager -------> Recruitement(Hiring) manager -----------> Division head ---------> HR manager
        // $deptHead = DepartmentHeadApprovals::where([
        //     ['approval_by_id',$authId],
        // ])->pluck('department_id');
        $deptHeadPendings = EmployeeHiringRequest::where([
            ['action_by_department_head','pending'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $deptHeadApproved = EmployeeHiringRequest::where([
            ['action_by_department_head','approved'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $deptHeadRejected = EmployeeHiringRequest::where([
            ['action_by_department_head','rejected'],
            ['department_head_id',$authId],
            ])->latest()->get();
        if($hiringManager) {
            $hiringManagerPendings = EmployeeHiringRequest::where([
                ['action_by_department_head','approved'],
                ['action_by_hiring_manager','pending'],
                ['hiring_manager_id',$authId],
                ])->latest()->get();
            $hiringManagerApproved = EmployeeHiringRequest::where([
                ['action_by_department_head','approved'],
                ['action_by_hiring_manager','approved'],
                ['hiring_manager_id',$authId],
                ])->latest()->get();
            $hiringManagerRejected = EmployeeHiringRequest::where([
                ['action_by_department_head','approved'],
                ['action_by_hiring_manager','rejected'],
                ['hiring_manager_id',$authId],
                ])->latest()->get();
        }
        // if(count($deptHead) > 0) {
           
        // }
        
        $divisionHeadPendings = EmployeeHiringRequest::where([            
            ['action_by_department_head','approved'],
            ['action_by_hiring_manager','approved'],
            ['action_by_division_head','pending'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadApproved = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','approved'],
            ['action_by_division_head','approved'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadRejected = EmployeeHiringRequest::where([
            ['action_by_hiring_manager','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_division_head','rejected'],
            ['division_head_id',$authId],
            ])->latest()->get();

        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        if($HRManager) {
            $HRManagerPendings = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','approved'],
                ['action_by_division_head','approved'],
                ['action_by_hr_manager','pending'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
            $HRManagerApproved = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','approved'],
                ['action_by_division_head','approved'],
                ['action_by_hr_manager','approved'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
            $HRManagerRejected = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','approved'], 
                ['action_by_division_head','approved'],               
                ['action_by_hr_manager','rejected'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
        }
        return view('hrm.hiring.hiring_request.approvals',compact('page','hiringManagerPendings','hiringManagerApproved','hiringManagerRejected','deptHeadPendings',
        'deptHeadApproved','deptHeadRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected'));
    }
    public function requestAction(Request $request) {
        $message = '';
        $update = EmployeeHiringRequest::where('id',$request->id)->first();
        // Approvals =>  Team Lead/Manager -------> Recruitement(Hiring) manager -----------> Division head ---------> HR manager
        
        if($request->current_approve_position == 'Team Lead / Reporting Manager') {
            $update->comments_by_department_head = $request->comment;
            $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_department_head = $request->status;
            if($request->status == 'approved') {
                $update->action_by_hiring_manager = 'pending';
                $message = 'Employee hiring request send to Recruiting Manager ( '.$update->hr_manager_name.' - '.$update->hr_manager_email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Recruiting Manager') {
            $update->comments_by_hiring_manager = $request->comment;
            $update->hiring_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_hiring_manager = $request->status;
            if($request->status == 'approved') {
                $update->action_by_division_head = 'pending';
                $message = 'Employee hiring request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Division Head') {
            $update->comments_by_division_head = $request->comment;
            $update->division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_division_head = $request->status;
            if($request->status == 'approved') {
                $update->action_by_hr_manager = 'pending';
                $message = 'Employee hiring request send to HR Manager ( '.$update->hr_manager_name.' - '.$update->hr_manager_email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'HR Manager') {
            $update->comments_by_hr_manager = $request->comment;
            $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_hr_manager = $request->status;
            if($request->status == 'approved') {
                $update->status = 'approved';
                $update->final_status = 'open';
            }
        }
        if($request->status == 'rejected') {
            $update->status = 'rejected';
        }
        $update->update();
        $history['hiring_request_id'] = $request->id;
        if($request->status == 'approved') {
            $history['icon'] = 'icons8-thumb-up-30.png';
        }
        else if($request->status == 'rejected') {
            $history['icon'] = 'icons8-thumb-down-30.png';
        }
        $history['message'] = 'Employee hiring request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
        $createHistory = EmployeeHiringRequestHistory::create($history);  
        if($request->status == 'approved' && $message != '') {
            $history['icon'] = 'icons8-send-30.png';
            $history['message'] = $message;
            $createHistory = EmployeeHiringRequestHistory::create($history);
        }
        (new UserActivityController)->createActivity($history['message']);
        return response()->json('success');
        // ,'New Employee Hiring Request '.$request->status.' Successfully'
    }
    public function updateFinalStatus(Request $request) {
        $update = EmployeeHiringRequest::where('id',$request->id)->first();
        $update->final_status = $request->status;
        if($request->status == 'cancelled') {
            $update->cancelled_by = Auth::id();
            $update->cancelled_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->cancelled_comment = $request->comment;
        }
        else if($request->status == 'onhold') {
            $update->on_hold_by = Auth::id();
            $update->on_hold_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->on_hold_comment = $request->comment;
        }
        else if($request->status == 'closed') {
            $update->closed_by = Auth::id();
            $update->closed_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->closed_comment = $request->comment;
            if(count($request->selectedCandidates) > 0 ) {
                foreach($request->selectedCandidates as $selectedCandidate) {
                    $candidate = InterviewSummaryReport::where('id',$selectedCandidate)->first();
                    $candidate->seleced_status = 'selected';
                    $candidate->selected_status_by = Auth::id();
                    $candidate->selected_status_at = Carbon::now()->format('Y-m-d H:i:s');
                    $candidate->selected_hiring_request_id = $request->id;
                    $candidate->update();
                }
            }
        }
        $update->update();
        $history['hiring_request_id'] = $request->id;
        if($request->status == 'cancelled') {
            $history['icon'] = 'icons8-cancel-30.png';
        }
        else if($request->status == 'onhold') {
            $history['icon'] = 'icons8-hand-30.png';
        }
        else if($request->status == 'closed') {
            $history['icon'] = 'icons8-check-30.png';
        }
        $history['message'] = 'Employee hiring request '.$request->status.' by ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
        $createHistory = EmployeeHiringRequestHistory::create($history);  
        (new UserActivityController)->createActivity($history['message']);
        return response()->json('success');
    }
    public function destroy($id) {
        $data = EmployeeHiringRequest::where('id',$id)->first();
        $data->deleted_by = Auth::id();
        $data->update();
        $data->delete();
        return response(true);
    }
}
