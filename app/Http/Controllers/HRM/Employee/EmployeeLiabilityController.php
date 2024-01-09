<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\Liability;
use App\Models\HRM\Employee\LiabilityHistory;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\User;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Approvals\DepartmentHeadApprovals;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\Masters\MasterDivisionWithHead;
use Exception;

class EmployeeLiabilityController extends Controller
{
    public function index() {
        $page = 'listing';
        $pendings = Liability::where('status','pending')->latest()->get();
        $approved = Liability::where('status','approved')->latest()->get();
        $rejected = Liability::where('status','rejected')->latest()->get();
        return view('hrm.liability.index',compact('pendings','approved','rejected','page'));
    }
    public function create() {
        return view('hrm.liability.create');
    }
    public function edit() {
        return view('hrm.liability.edit');
    }
    public function show(string $id) {
        return view('hrm.liability.show');
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new Liability();
            $previous = $next = '';
        }
        else {
            $data = Liability::find($id);
            $previous = Liability::where('status',$data->status)->where('id', '<', $id)->max('id');
            $next = Liability::where('status',$data->status)->where('id', '>', $id)->min('id');
        }
        $masterEmployees = User::whereHas('empProfile')->select('id','name')->get();
        return view('hrm.liability.create',compact('id','data','previous','next','masterEmployees'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'request_date' => 'required',
            'loan' => 'required',
            'loan_amount' => 'required',
            'advances' => 'required',
            'advances_amount' => 'required',
            'penalty_or_fine' => 'required',
            'penalty_or_fine_amount' => 'required',
            'total_amount' => 'required',
            'no_of_installments' => 'required',
            'amount_per_installment' => 'required',
            'reason' => 'required',
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
                $financeManager = ApprovalByPositions::where('approved_by_position','Finance Manager')->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $divisionHead = MasterDivisionWithHead::where('id',$employee->division)->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;                   
                    $input['department_head_id'] = $departmentHead->approval_by_id;
                    $input['finance_manager_id'] = $financeManager->handover_to_id;
                    $input['hr_manager_id'] = $HRManager->handover_to_id;
                    $input['division_head_id'] = $divisionHead->approval_handover_to;
                    $createRequest = Liability::create($input);
                    $history['liability_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee liability request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = LiabilityHistory::create($history);
                    $history2['liability_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee liability request send to Team Lead / Reporting Manager ( '.$departmentHead->handover_to_name.' - '.$departmentHead->handover_to_email.' ) for approval';
                    $createHistory2 = LiabilityHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee Liability Request Created');
                    $successMessage = "Employee Liability Hiring Request Created Successfully";
                }
                else {
                    $update = Liability::find($id);
                    if($update) {
                        $update->employee_id = $request->employee_id;
                        $update->request_date = $request->request_date;
                        $update->loan = $request->loan;
                        $update->loan_amount = $request->loan_amount;
                        $update->advances = $request->advances;
                        $update->advances_amount = $request->advances_amount;
                        $update->penalty_or_fine = $request->penalty_or_fine;
                        $update->penalty_or_fine_amount = $request->penalty_or_fine_amount;
                        $update->total_amount = $request->total_amount;
                        $update->no_of_installments = $request->no_of_installments;
                        $update->amount_per_installment = $request->amount_per_installment;
                        $update->number_of_openings = $request->number_of_openings;
                        $update->reason = $request->reason;
                        $update->updated_by = $authId;
                        $update->update();
                        $history['liability_id'] = $id;
                        $history['icon'] = 'icons8-edit-30.png';
                        $history['message'] = 'Employee liability request edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                        $createHistory = LiabilityHistory::create($history);
                        (new UserActivityController)->createActivity('Employee Liability Request Edited');
                        $successMessage = "Employee Liability Request Updated Successfully";
                    }
                }
                DB::commit();
                return redirect()->route('employee_liability.index')
                                    ->with('success',$successMessage);
            } 
            catch (\Exception $e) {
                DB::rollback();
            }
        }
    }
}
