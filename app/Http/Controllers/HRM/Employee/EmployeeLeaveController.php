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

class EmployeeLeaveController extends Controller
{
    public function index() {
        $page = 'listing';
        $pendings = Leave::where('status','pending')->latest()->get();
        $approved = Leave::where('status','approved')->latest()->get();
        $rejected = Leave::where('status','rejected')->latest()->get();
        return view('hrm.leave.index',compact('pendings','approved','rejected','page'));
    }
    public function create() {
        return view('hrm.leave.create');
    }
    public function edit() {
        return view('hrm.leave.edit');
    }
    public function show($id) {
        $data = Leave::where('id',$id)->first();
        $previous = Leave::where('id', '<', $id)->max('id');
        $next = Leave::where('id', '>', $id)->min('id');
        return view('hrm.leave.show',compact('data','previous','next'));
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
        $masterEmployees = User::whereHas('empProfile')->with('empProfile.department','empProfile.designation','empProfile.location')->select('id','name')->get();
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
                $authId = Auth::id();
                $employee = EmployeeProfile::where('user_id',$request->employee_id)->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                // $departmentHead = DepartmentHeadApprovals::where('department_id',$employee->department_id)->first();
                $divisionHead = MasterDivisionWithHead::where('id',$employee->division)->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;   
                    $input['hr_manager_id'] = $HRManager->handover_to_id;                
                    $input['department_head_id'] = $employee->team_lead_or_reporting_manager;
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
                    $history2['message'] = 'Employee hiring request send to Employee ( '.$employee->first_name.' '.$employee->last_name.' - '.$employee->personal_email_address.' ) for approval';
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
                            $update->type_of_leave_description == NULL;
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
                        $update->department_head_id = $employee->team_lead_or_reporting_manager;
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
                return redirect()->route('employee_leave.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
}
