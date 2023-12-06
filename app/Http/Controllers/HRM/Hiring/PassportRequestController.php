<?php

namespace App\Http\Controllers\HRM\Hiring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\PassportRequest;
use App\Models\Masters\PassportRequestPurpose;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use App\Models\Masters\MasterDivisionWithHead;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Employee\Leave;
use App\Models\HRM\Employee\LeaveHistory;
use App\Http\Controllers\UserActivityController;
use Exception;

class PassportRequestController extends Controller
{
    public function index() {
        $page = 'listing';
        $submit_pendings = PassportRequest::where('submit_status','pending')->latest()->get();
        $submit_approved = PassportRequest::where('submit_status','approved')->latest()->get();
        $submit_rejected = PassportRequest::where('submit_status','rejected')->latest()->get();
        $release_pendings = PassportRequest::where('release_submit_status','pending')->latest()->get();
        $release_approved = PassportRequest::where('release_submit_status','approved')->latest()->get();
        $release_rejected = PassportRequest::where('release_submit_status','rejected')->latest()->get();
        return view('hrm.hiring.passport_request.index',compact('submit_pendings','submit_approved','submit_rejected','release_pendings','release_approved','release_rejected','page'));
    }
    public function create() {
        return view('hrm.hiring.passport_request.create');
    }
    public function edit() {
        return view('hrm.hiring.passport_request.edit');
    }
    public function show(string $id) {
        return view('hrm.hiring.passport_request.show');
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new PassportRequest();
            $previous = $next = '';
        }
        else {
            $data = PassportRequest::find($id);
            $previous = PassportRequest::where('status',$data->status)->where('id', '<', $id)->max('id');
            $next = PassportRequest::where('status',$data->status)->where('id', '>', $id)->min('id');
        }
        $masterEmployees = User::whereNot('id','16')->select('id','name')->first();
        $submissionPurpose = PassportRequestPurpose::where('type','submit')->get();
        $releasePurpose = PassportRequestPurpose::where('type','release')->get();
        return view('hrm.hiring.employee_liability.create',compact('id','data','previous','next','masterEmployees','submissionPurpose','releasePurpose'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'purposes_of_submit' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $employee = EmployeeProfile::where('user_id',$request->employee_id)->get();
                $departmentHead = DepartmentHeadApprovals::where('department_id',$employee->department_id)->first();
                $divisionHead = MasterDivisionWithHead::where('id',$employee->division)->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;   
                    $input['hr_manager_id'] = $HRManager->handover_to_id;                
                    $input['department_head_id'] = $departmentHead->approval_by_id;
                    $input['division_head_id'] = $divisionHead->division_head_id;
                    $createRequest = Leave::create($input);
                    $history['leave_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee Leave request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = LeaveHistory::create($history);
                    $history2['leave_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee passport '.$request->purposes_of_submit.' request send to Team Lead / Reporting Manager ( '.$departmentHead->handover_to_name.' - '.$departmentHead->handover_to_email.' ) for approval';
                    $createHistory2 = LeaveHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee Leave Request Created');
                    $successMessage = "Employee Leave Request Created Successfully";
                }
                else {
                    $update = Leave::find($id);
                    if($update) {
                        $update->employee_id = $request->employee_id;
                        $update->type_of_leave = $request->type_of_leave;
                        $update->type_of_leave_description = $request->type_of_leave_description;
                        $update->leave_start_date = $request->leave_start_date;
                        $update->leave_end_date = $request->leave_end_date;
                        $update->total_no_of_days = $request->total_no_of_days;
                        $update->no_of_paid_days = $request->no_of_paid_days;
                        $update->no_of_unpaid_days = $request->no_of_unpaid_days;
                        $update->address_while_on_leave = $request->address_while_on_leave;
                        $update->alternative_home_contact_no = $request->alternative_home_contact_no;
                        $update->alternative_personal_email = $request->alternative_personal_email;
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
                return redirect()->route('employee_leave.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
            }
        }
    }
}
