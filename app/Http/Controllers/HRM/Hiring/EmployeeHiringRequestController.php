<?php

namespace App\Http\Controllers\HRM\Hiring;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\MasterDeparment;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Hiring\EmployeeHiringQuestionnaire;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\User;
use Validator;
use DB;
use Exception;
use Carbon\Carbon;
use App\Http\Controllers\UserActivityController;

class EmployeeHiringRequestController extends Controller
{
    public function index() {
        $page = 'listing';
        $pendings = EmployeeHiringRequest::where('status','pending')->latest()->get();
        $approved = EmployeeHiringRequest::where('status','approved')->latest()->get();
        $rejected = EmployeeHiringRequest::where('status','rejected')->latest()->get();
        $deleted = [];
        $deleted = EmployeeHiringRequest::onlyTrashed()->get();
        return view('hrm.hiring.hiring_request.index',compact('pendings','approved','rejected','deleted','page'));
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
        $masterdepartments = MasterDeparment::where('status','active')->select('id','name')->get();
        $masterExperienceLevels = MasterExperienceLevel::select('id','name','number_of_year_of_experience')->get();
        $masterJobPositions = MasterJobPosition::where('status','active')->select('id','name')->get();
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        $requestedByUsers = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        $reportingToUsers = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        $replacementForEmployees = User::whereNotIn('id',['1','16'])->select('id','name')->get();
        return view('hrm.hiring.hiring_request.create',compact('id','data','previous','next','masterdepartments','masterExperienceLevels','masterJobPositions','masterOfficeLocations',
            'requestedByUsers','reportingToUsers','replacementForEmployees'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            'request_date' => 'required',
            'department_id' => 'required',
            'location_id' => 'required',
            'requested_by' => 'required',
            'requested_job_title' => 'required',
            'reporting_to' => 'required',
            'experience_level' => 'required',
            'salary_range_start_in_aed' => 'required',
            'salary_range_end_in_aed' => 'required',
            'work_time_start' => 'required',
            'reporting_to' => 'required',
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
                $departmentHead = DepartmentHeadApprovals::where('department_id',$request->department_id)->first();
                $hiringManager = ApprovalByPositions::where('approved_by_position','Recruiting Manager')->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;
                    $input['hiring_manager_id'] = $hiringManager->handover_to_id;
                    $input['department_head_id'] = $departmentHead->approval_by_id;
                    $input['hr_manager_id'] = $HRManager->handover_to_id;
                    $createRequest = EmployeeHiringRequest::create($input);
                    $history['hiring_request_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee hiring request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    $history2['hiring_request_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee hiring request send to '.$hiringManager->approved_by_position_name.' ( '.$hiringManager->handover_to_name.' - '.$hiringManager->handover_to_email.' ) for approval';
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
                        $update->reporting_to = $request->reporting_to;
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
                        $update->update();
                        $history['hiring_request_id'] = $id;
                        $history['icon'] = 'icons8-edit-30.png';
                        $history['message'] = 'Employee hiring request edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = EmployeeHiringRequestHistory::create($history);
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
            }
        }
    }
    public function show($id) {
        $data = EmployeeHiringRequest::where('id',$id)->first();
        $previous = EmployeeHiringRequest::where('status',$data->status)->where('id', '<', $id)->max('id');
        $next = EmployeeHiringRequest::where('status',$data->status)->where('id', '>', $id)->min('id');
        return view('hrm.hiring.hiring_request.show',compact('data','previous','next'));
    }
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $hiringManager = $HRManager = '';
        $deptHead = $hiringManagerPendings = $hiringManagerApproved = $hiringManagerRejected = $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $hiringManager = ApprovalByPositions::where([
            ['approved_by_position','Recruiting Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $deptHead = DepartmentHeadApprovals::where([
            ['approval_by_id',$authId],
        ])->pluck('department_id');
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        if($hiringManager) {
            $hiringManagerPendings = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','pending'],
                ['hiring_manager_id',$authId],
                ])->latest()->get();
            $hiringManagerApproved = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['hiring_manager_id',$authId],
                ])->latest()->get();
            $hiringManagerRejected = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','rejected'],
                ['hiring_manager_id',$authId],
                ])->latest()->get();
        }
        if(count($deptHead) > 0) {
            $deptHeadPendings = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','pending'],
                ['department_head_id',$authId],
                ])->latest()->get();
            $deptHeadApproved = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','pending'],
                ['department_head_id',$authId],
                ])->latest()->get();
            $deptHeadRejected = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','pending'],
                ['department_head_id',$authId],
                ])->latest()->get();
        }
        if($HRManager) {
            $HRManagerPendings = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','approved'],
                ['action_by_hr_manager','pending'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
            $HRManagerApproved = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','approved'],
                ['action_by_hr_manager','pending'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
            $HRManagerRejected = EmployeeHiringRequest::where([
                ['action_by_hiring_manager','approved'],
                ['action_by_department_head','approved'],                
                ['action_by_hr_manager','pending'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
        }
        return view('hrm.hiring.hiring_request.approvals',compact('page','hiringManagerPendings','hiringManagerApproved','hiringManagerRejected','deptHeadPendings',
        'deptHeadApproved','deptHeadRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected',));
    }
    public function requestAction(Request $request) {
        $message = '';
        $update = EmployeeHiringRequest::where('id',$request->id)->first();
        if($request->current_approve_position == 'Recruiting Manager') {
            $update->comments_by_hiring_manager = $request->comment;
            $update->hiring_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_hiring_manager = $request->status;
            if($request->status == 'approved') {
                $update->action_by_department_head = 'pending';
                $message = 'Employee hiring request send to Department Head ( '.$update->department_head_name.' - '.$update->department_head_email.' ) for approval';
            }
        }
        else if($request->current_approve_position == 'Department Head') {
            $update->comments_by_department_head = $request->comment;
            $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
            $update->action_by_department_head = $request->status;
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
    public function destroy($id) {
        $data = EmployeeHiringRequest::where('id',$id)->first();
        $data->deleted_by = Auth::id();
        $data->update();
        $data->delete();
        return response(true);
    }
}
