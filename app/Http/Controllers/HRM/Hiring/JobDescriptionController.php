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

class JobDescriptionController extends Controller
{
    public function index() {
        $pendings = JobDescription::where('status','pending')->latest()->get();
        $approved = JobDescription::where('status','approved')->latest()->get();
        $rejected = JobDescription::where('status','rejected')->latest()->get();
        return view('hrm.hiring.job_description.index',compact('pendings','approved','rejected'));
    }
    public function create() {
        return view('hrm.hiring.job_description.create');
    }
    public function edit() {
        return view('hrm.hiring.job_description.edit');
    }
    public function show(string $id) {
        return view('hrm.hiring.job_description.show');
    }
    public function createOrEdit($id, $hiring_id) {
        $jobDescription = JobDescription::where('id',$id)->first();
        // dd($jobDescription);
        if(!$jobDescription) {
            $jobDescription = new JobDescription();
            $jobDescriptionId = 'new';           
            if($hiring_id != 'new') {
                $currentHiringRequest = EmployeeHiringRequest::where('id',$hiring_id)->first();
            }
            else {
                $currentHiringRequest ='';
            }
        }
        else {
            $jobDescriptionId = $jobDescription->id;
            $currentHiringRequest = EmployeeHiringRequest::where('id',$jobDescription->hiring_request_id)->first();
        }
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        $allHiringRequests = EmployeeHiringRequest::all();
        return view('hrm.hiring.job_description.create',compact('jobDescriptionId','currentHiringRequest','jobDescription','masterOfficeLocations','allHiringRequests'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        dd('hi');
        $validator = Validator::make($request->all(), [
            // 'job_title' => 'required',
            // 'department_id' => 'required',
            'location_id' => 'required',
            // 'reporting_to' => 'required',
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
                $departmentHead = DepartmentHeadApprovals::where('department_id',$request->department_id)->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;
                    $input['department_head_id'] = $departmentHead->approval_by_id;
                    $input['hr_manager_id'] = $HRManager->handover_to_id;
                    $createRequest = JobDescription::create($input);
                    $history['hiring_request_id'] = $id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee hiring job description created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    $history2['hiring_request_id'] = $id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee hiring job description send to '.$departmentHead->approved_by_position_name.' ( '.$departmentHead->handover_to_name.' - '.$departmentHead->handover_to_email.' ) for approval';
                    $createHistory2 = EmployeeHiringRequestHistory::create($history2);
                    (new UserActivityController)->createActivity('New Employee Hiring Job Description Created');
                    $successMessage = "New Employee Hiring Job Description Created Successfully";
                }
                else {
                    $update = JobDescription::find($id);
                    if($update) {
                        $update->job_title = $request->job_title;
                        $update->department_id = $request->department_id;
                        $update->location_id = $request->location_id;
                        $update->reporting_to = $request->reporting_to;
                        $update->job_purpose = $request->job_purpose;
                        $update->duties_and_responsibilities = $request->duties_and_responsibilities;
                        $update->skills_required = $request->skills_required;
                        $update->position_qualification = $request->position_qualification;
                        $update->updated_by = $authId;
                        $update->update();
                        $history['hiring_request_id'] = $id;
                        $history['icon'] = 'icons8-edit-30.png';
                        $history['message'] = 'Employee hiring questionnaire edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = EmployeeHiringRequestHistory::create($history);
                        (new UserActivityController)->createActivity('Employee Hiring Questionnaire Edited');
                        $successMessage = "Employee Hiring Questionnaire Updated Successfully";
                    }
                }
                DB::commit();
                return redirect()->route('employee-hiring-request.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
            }
        }
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = JobDescription::where('id',$request->id)->first();
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
        catch (\Exception $e) {
            // info($e);
            DB::rollback();
        }
    }
}
