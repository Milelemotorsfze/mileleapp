<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterDepartment;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\HRM\Hiring\JobDescription;
use App\Models\User;
use Carbon\Carbon;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Http\Controllers\UserActivityController;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use Validator;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Employee\EmployeeProfile;

class JobDescriptionController extends Controller
{
    public function index() {
        $authId = Auth::id();
        $pendings = JobDescription::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['view-pending-job-description-list'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-current-user-pending-job-description-list'])) {
            $pendings = $pendings->whereHas('employeeHiringRequest',function($query) use($authId) {
                $query->where('requested_by',$authId);
            })->latest();
        }
        $pendings = $pendings->get();
        $approved = JobDescription::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['view-approved-job-description-list'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-current-user-approved-job-description-list'])) {
            $approved = $approved->whereHas('employeeHiringRequest',function($query) use($authId) {
                $query->where('requested_by',$authId);
            })->latest();
        }
        $approved =$approved->get();
        $rejected = JobDescription::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['view-rejected-job-description-list'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['view-current-user-rejected-job-description-list'])) {
            $rejected = $rejected->whereHas('employeeHiringRequest',function($query) use($authId) {
                $query->where('requested_by',$authId);
            })->latest();
        }
        $rejected=$rejected->get();
        return view('hrm.hiring.job_description.index',compact('pendings','approved','rejected'));
    }
    public function createOrEdit($id, $hiring_id) {
        $authId = Auth::id();
        $jobDescription = JobDescription::where('id',$id);

        $jobDescription =$jobDescription->first();
        if(!$jobDescription) {
            $jobDescription = new JobDescription();
            $jobDescriptionId = 'new';           
            if($hiring_id != 'new') {
                $currentHiringRequest = EmployeeHiringRequest::where('id',$hiring_id)->where('status','approved')->where('final_status','open')
                ->whereDoesntHave('jobDescription');
                if(Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing'])) {
                    $currentHiringRequest = $currentHiringRequest->latest();
                }
                else if(Auth::user()->hasPermissionForSelectedRole(['view-approved-hiring-request-listing-of-current-user'])) {
                    $currentHiringRequest = $currentHiringRequest->where('requested_by',$authId)->latest();
                }
                $currentHiringRequest =$currentHiringRequest->with('questionnaire.designation','questionnaire.department','questionnaire.workLocation')->first();
            }
            else {
                $currentHiringRequest ='';
            }    
            $allHiringRequests = EmployeeHiringRequest::whereHas('questionnaire')->where('status','approved')->where('final_status','open')->whereDoesntHave('jobDescription');
            if(Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing'])) {
                $allHiringRequests = $allHiringRequests->latest();
            }
            else if(Auth::user()->hasPermissionForSelectedRole(['view-approved-hiring-request-listing-of-current-user'])) {
                $allHiringRequests = $allHiringRequests->where('requested_by',$authId)->latest();
            }
            $allHiringRequests = $allHiringRequests->with('questionnaire.designation','questionnaire.department','questionnaire.workLocation')->get();
        }
        else {
            $jobDescriptionId = $jobDescription->id;
            $currentHiringRequest = EmployeeHiringRequest::where('id',$jobDescription->hiring_request_id);
            if(Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing'])) {
                $currentHiringRequest = $currentHiringRequest->latest();
            }
            else if(Auth::user()->hasPermissionForSelectedRole(['view-approved-hiring-request-listing-of-current-user'])) {
                $currentHiringRequest = $currentHiringRequest->where('requested_by',$authId)->latest();
            }
            $currentHiringRequest = $currentHiringRequest->with('questionnaire.designation','questionnaire.department','questionnaire.workLocation')->first();
            $allHiringRequests1 = EmployeeHiringRequest::where('status','approved')->where('final_status','open')->whereDoesntHave('jobDescription');
            if(Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing'])) {
                $allHiringRequests1 = $allHiringRequests1->latest();
            }
            else if(Auth::user()->hasPermissionForSelectedRole(['view-approved-hiring-request-listing-of-current-user'])) {
                $allHiringRequests1 = $allHiringRequests1->where('requested_by',$authId)->latest();
            }
            $allHiringRequests1 = $allHiringRequests1->with('questionnaire.designation','questionnaire.department','questionnaire.workLocation')->get();
            $allHiringRequests2 = EmployeeHiringRequest::where('status','approved')->where('final_status','open')->where('id',$jobDescription->hiring_request_id);
            if(Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing'])) {
                $allHiringRequests2 = $allHiringRequests2->latest();
            }
            else if(Auth::user()->hasPermissionForSelectedRole(['view-approved-hiring-request-listing-of-current-user'])) {
                $allHiringRequests2 = $allHiringRequests2->where('requested_by',$authId)->latest();
            }
            $allHiringRequests2 = $allHiringRequests2->with('questionnaire.designation','questionnaire.department','questionnaire.workLocation')->get();
            $allHiringRequests = $allHiringRequests1->merge($allHiringRequests2);
        }
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        return view('hrm.hiring.job_description.create',compact('jobDescriptionId','currentHiringRequest','jobDescription','masterOfficeLocations','allHiringRequests'));
    }
    public function storeOrUpdate(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'hiring_request_id' => 'required',
            'location_id' => 'required',
            'request_date' => 'required',
            'job_purpose' => 'required',
            'duties_and_responsibilities' => 'required',
            'skills_required' => 'required',
            'position_qualification' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();               
                $hiringRequest = EmployeeHiringRequest::where('id',$request->hiring_request_id)->first();
                $employ = EmployeeProfile::where('user_id',$hiringRequest->requested_by)->first();
                if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                    $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['created_by'] = $authId;
                    $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                }
                $teamLeadOrReportingManager = EmployeeProfile::where('user_id',$hiringRequest->requested_by)->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input = $request->all();
                if($id == 'new' && isset($hiringRequest->questionnaire) && !isset($hiringRequest->jobDescription)) {
                    $input['created_by'] = $authId;
                    $input['department_head_id'] =  $teamLeadOrReportingManager->leadManagerHandover->approval_by_id;
                    $input['action_by_department_head'] = 'pending';
                    $input['hr_manager_id'] = $HRManager->handover_to_id;
                    $createRequest = JobDescription::create($input);
                    $history['hiring_request_id'] = $request->hiring_request_id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee hiring job description created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    $history2['hiring_request_id'] = $request->hiring_request_id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee hiring job description send to Team Lead / Reporting Manager ( '.$teamLeadOrReportingManager->leadManagerHandover->handOverTo->name.' - '.$teamLeadOrReportingManager->leadManagerHandover->handOverTo->email.' ) for approval';
                    $createHistory2 = EmployeeHiringRequestHistory::create($history2);
                    (new UserActivityController)->createActivity('New Employee Hiring Job Description Created');
                    $successMessage = "New Employee Hiring Job Description Created Successfully";
                }
                else if(($id == 'new' OR $id != 'new') && isset($hiringRequest->questionnaire) && isset($hiringRequest->jobDescription)) {
                    $update = JobDescription::where('hiring_request_id',$request->hiring_request_id);
                    if($id != 'new') {
                        $update =$update->where('id',$id);
                    }
                    $update =$update->first();
                    if($update && ($update->status == 'pending' OR $update->status == 'rejected')) {
                        $update->hiring_request_id = $request->hiring_request_id;
                        $update->request_date = $request->request_date;
                        $update->location_id = $request->location_id;
                        $update->job_purpose = $request->job_purpose;
                        $update->duties_and_responsibilities = $request->duties_and_responsibilities;
                        $update->skills_required = $request->skills_required;
                        $update->position_qualification = $request->position_qualification;
                        $update->updated_by = $authId;
                        $update->status = 'pending';
                        $update->action_by_department_head = 'pending';
                        $update->department_head_id = $teamLeadOrReportingManager->leadManagerHandover->approval_by_id;
                        $update->department_head_action_at = NULL;
                        $update->action_by_hr_manager = 'pending';
                        $update->hr_manager_id = $HRManager->handover_to_id;
                        $update->hr_manager_action_at = NULL;
                        $update->update();
                        $history['hiring_request_id'] = $request->hiring_request_id;
                        $history['icon'] = 'icons8-edit-30.png';
                        $history['message'] = 'Employee hiring Job Description edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = EmployeeHiringRequestHistory::create($history);
                        (new UserActivityController)->createActivity('Employee Hiring Job Description Edited');
                        $successMessage = "Employee Hiring Job Description Updated Successfully";
                    }
                    else if(($update && $update->status == 'approved') OR ($update && $update->status == 'rejected')) {
                        $successMessage = "can't update this employee hiring job description ,because it is already ". $update->status;
                    }
                }
                DB::commit();
                return redirect()->route('job_description.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = JobDescription::where('id',$request->id)->first();
            if($update && $update->status == 'pending') {
            if($request->current_approve_position == 'Team Lead / Reporting Manager') {
                $update->comments_by_department_head = $request->comment;
                $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_department_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'pending';
                    $message = 'Employee hiring request send to HR Manager ( '.$update->hrManagerName->name.' - '.$update->hrManagerName->email.' ) for approval';
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
            $history['hiring_request_id'] = $request->id;
            if($request->status == 'approved') {
                $history['icon'] = 'icons8-thumb-up-30.png';
            }
            else if($request->status == 'rejected') {
                $history['icon'] = 'icons8-thumb-down-30.png';
            }
            $history['message'] = 'Employee hiring job description '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
            $createHistory = EmployeeHiringRequestHistory::create($history);  
            if($request->status == 'approved' && $message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = EmployeeHiringRequestHistory::create($history);
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
            // info($e);
            DB::rollback();
        }
    }
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $HRManager = '';
        $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $deptHeadPendings = JobDescription::where([
            ['action_by_department_head','pending'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $deptHeadApproved = JobDescription::where([
            ['action_by_department_head','approved'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $deptHeadRejected = JobDescription::where([
            ['action_by_department_head','rejected'],
            ['department_head_id',$authId],
            ])->latest()->get();
        if($HRManager) {
        $HRManagerPendings = JobDescription::where([
            ['action_by_department_head','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerApproved = JobDescription::where([
            ['action_by_department_head','approved'],
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerRejected = JobDescription::where([
            ['action_by_department_head','approved'],                
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        }
        return view('hrm.hiring.job_description.approvals',compact('page','deptHeadPendings',
        'deptHeadApproved','deptHeadRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected',));
    }
}
