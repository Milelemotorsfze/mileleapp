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
    public function show(string $id) {
        return view('hrm.leave.show');
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new Leave();
            $previous = $next = '';
        }
        else {
            $data = Leave::find($id);
            $previous = Leave::where('status',$data->status)->where('id', '<', $id)->max('id');
            $next = Leave::where('status',$data->status)->where('id', '>', $id)->min('id');
        }
        $masterEmployees = User::whereHas('empProfile')->select('id','name')->get();
        return view('hrm.liability.create',compact('id','data','previous','next','masterEmployees'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'type_of_leave' => 'required',
            'type_of_leave_description' => 'required',
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
                $employee = EmployeeProfile::where('user_id',$request->id)->get();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $departmentHead = DepartmentHeadApprovals::where('department_id',$employee->department_id)->first();
                $divisionHead = MasterDivisionWithHead::where('id',$employee->division)->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;   
                    $input['hr_manager_id'] = $HRManager->handover_to_id;                
                    $input['department_head_id'] = $departmentHead->approval_by_id;
                    $input['division_head_id'] = $divisionHead->approval_handover_to;
                    $createRequest = Leave::create($input);
                    $history['leave_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee Leave request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = LeaveHistory::create($history);
                    $history2['leave_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee hiring request send to '.$hiringManager->approved_by_position_name.' ( '.$hiringManager->handover_to_name.' - '.$hiringManager->handover_to_email.' ) for approval';
                    $createHistory2 = LeaveHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee Leave Request Created');
                    $successMessage = "Employee Leave Request Created Successfully";
                }
                else {
                    $update = Leave::find($id);
                    if($update) {
                        $update->id = $request->id;
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
                return redirect()->route('leave.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
            }
        }
    }
}
