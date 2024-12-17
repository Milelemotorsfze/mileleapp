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
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class EmployeeLiabilityController extends Controller
{
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $HRManager = '';
        // employee -------> Reporting Manager  ----Finance Manager--------->HR Manager-------->Division Head
        $deptHead = $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = $employeePendings = $employeeApproved = $employeeRejected = 
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected =
        $financeManagerPendings = $financeManagerApproved = $financeManagerRejected = [];
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        $employeePendings = Liability::where([
            ['action_by_employee','pending'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeApproved = Liability::where([
            ['action_by_employee','approved'],
            ['employee_id',$authId],
            ])->latest()->get();
        $employeeRejected = Liability::where([
            ['action_by_employee','rejected'],
            ['employee_id',$authId],
            ])->latest()->get();
        $ReportingManagerPendings = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','pending'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerApproved = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $ReportingManagerRejected = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','rejected'],
            ['department_head_id',$authId],
            ])->latest()->get();
        $financeManagerPendings = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_finance_manager','pending'],
            ['finance_manager_id',$authId],
            ])->latest()->get();
        $financeManagerApproved = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_finance_manager','approved'],
            ['finance_manager_id',$authId],
            ])->latest()->get();
        $financeManagerRejected = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_finance_manager','rejected'],
            ['finance_manager_id',$authId],
            ])->latest()->get();
        $HRManagerPendings = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_finance_manager','approved'],
            ['action_by_hr_manager','pending'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerApproved = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_finance_manager','approved'],
            ['action_by_hr_manager','approved'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $HRManagerRejected = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_finance_manager','approved'],
            ['action_by_hr_manager','rejected'],
            ['hr_manager_id',$authId],
            ])->latest()->get();
        $divisionHeadPendings = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_finance_manager','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','pending'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadApproved = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],
            ['action_by_finance_manager','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','approved'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadRejected = Liability::where([
            ['action_by_employee','approved'],
            ['action_by_department_head','approved'],                
            ['action_by_finance_manager','approved'],
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','rejected'],
            ['division_head_id',$authId],
            ])->latest()->get();
        return view('hrm.liability.approvals',compact('page','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected','employeePendings',
        'employeeApproved','employeeRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected','ReportingManagerPendings','ReportingManagerApproved',
        'ReportingManagerRejected','financeManagerPendings','financeManagerApproved','financeManagerRejected'));
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = Liability::where('id',$request->id)->first();
            // employee -------> Reporting Manager---->Finance Manager--------->HR Manager-------->Division Head
            if($request->current_approve_position == 'Employee') {
                $update->comments_by_employee = $request->comment;
                $update->employee_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_employee = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_department_head = 'pending';
                    $employee2 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                    $leadOrMngr = TeamLeadOrReportingManagerHandOverTo::where('lead_or_manager_id',$employee2->team_lead_or_reporting_manager)->first();
                    $update->department_head_id = $leadOrMngr->approval_by_id;
                    $message = 'Employee Liability Request send to Reporting Manager ( '.$update->reportingManager->name.' - '.$update->reportingManager->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Reporting Manager') {
                $update->comments_by_department_head = $request->comment;
                $update->department_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_department_head = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_finance_manager = 'pending';
                    $FinanceManager = ApprovalByPositions::where('approved_by_position','Finance Manager')->first();
                $update->finance_manager_id = $FinanceManager->handover_to_id;
                    $message = 'Employee Liability Request send to Finance Manager ( '.$update->financeManager->name.' - '.$update->financeManager->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Finance Manager') {        
                $update->comments_by_finance_manager = $request->comment;
                $update->finance_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_finance_manager = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_hr_manager = 'pending';
                    $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                    $update->hr_manager_id = $HRManager->handover_to_id;
                    $message = 'Employee Liability Request send to HR Manager ( '.$update->hrManager->name.' - '.$update->hrManager->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'HR Manager') {
                $update->comments_by_hr_manager = $request->comment;
                $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_hr_manager = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_division_head = 'pending';
                    $employee1 = EmployeeProfile::where('user_id',$update->employee_id)->first();
                $divisionHead1 = MasterDivisionWithHead::where('id',$employee1->department->division_id)->first();
                $update->division_head_id = $divisionHead1->approval_handover_to;
                    $message = 'Employee Liability Request send to Division Head ( '.$update->divisionHead->name.' - '.$update->divisionHead->email.' ) for approval';               
                }
            }
            else if($request->current_approve_position == 'Division Head') {
                $update->comments_by_division_head = $request->comment;
                $update->division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_division_head = $request->status;
                if($request->status == 'approved') {
                    $update->status = 'approved'; 
                    $update->action_by_division_head = 'approved';
                }
            }
            if($request->status == 'rejected') {
                $update->status = 'rejected'; 
            }
            $update->update();
            $history['liability_id'] = $update->id;
            if($request->status == 'approved') {
                $history['icon'] = 'icons8-thumb-up-30.png';
            }
            else if($request->status == 'rejected') {
                $history['icon'] = 'icons8-thumb-down-30.png';
            }
            $history['message'] = 'Employee Liability Request '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
            $createHistory = LiabilityHistory::create($history);  
            if($request->status == 'approved' && $message != '') {
                $history['icon'] = 'icons8-send-30.png';
                $history['message'] = $message;
                $createHistory = LiabilityHistory::create($history);
            }
            (new UserActivityController)->createActivity($history['message']);
            DB::commit();
            return response()->json('success');
        } 
        catch (\Exception $e) {
            DB::rollback();
            info($e);
            $errorMsg ="Something went wrong! Contact your admin";
            return view('hrm.notaccess',compact('errorMsg'));
        }
    }
    public function index() {
        $authId = Auth::id();
        $page = 'listing';
        $pendings = Liability::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['view-liability-list'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-liability-list'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings = $pendings->get();
        $approved = Liability::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['view-liability-list'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-liability-list'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved = $approved->get();
        $rejected = Liability::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['view-liability-list'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['current-user-view-liability-list'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected = $rejected->get();
        return view('hrm.liability.index',compact('pendings','approved','rejected','page'));
    }
    public function show($id) {
        $data = Liability::where('id',$id)->first();
        $previous = Liability::where('id', '<', $id)->max('id');
        $next = Liability::where('id', '>', $id)->min('id');
        return view('hrm.liability.show',compact('data','previous','next'));
    }
    public function createOrEdit($id) {
        if($id == 'new') {
            $data = new Liability();
            $previous = $next = '';
        }
        else {
            $data = Liability::where('id',$id)->with('user.empProfile.designation','user.empProfile.department','user.empProfile.location')->first();
            $previous = Liability::where('status',$data->status)->where('id', '<', $id)->max('id');
            $next = Liability::where('status',$data->status)->where('id', '>', $id)->min('id');
        }
        $masterEmployees = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereNot('is_management','yes')->whereHas('empProfile')->with('empProfile.designation','empProfile.department','empProfile.location')->select('id','name')->get();
        return view('hrm.liability.create',compact('id','data','previous','next','masterEmployees'));
    }
    public function storeOrUpdate(Request $request, $id) { 
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'type' => 'required',
            // 'code' => 'required',
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
                $latestRow = Liability::withTrashed()->orderBy('id', 'desc')->first();
                $length = 4;
                $offset = 4;
                $prefix = "";
                if($latestRow){
                    $latestUUID =  $latestRow->code; 
                    $newCode =  str_pad($latestUUID + 1, 4, 0, STR_PAD_LEFT);
                    $code =  $prefix.$newCode;
                }else{
                    $code = $prefix.'0001';
                }
                $authId = Auth::id();
                $employ = EmployeeProfile::where('user_id',$request->employee_id)->first();
                if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                    $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['created_by'] = $authId;
                    $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                }
                $employee = EmployeeProfile::where('user_id',$request->employee_id)->first();
                $financeManager = ApprovalByPositions::where('approved_by_position','Finance Manager')->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                $divisionHead = MasterDivisionWithHead::where('id',$employee->department->division_id)->first();
                $input = $request->all();
                if($id == 'new') {
                    $input['created_by'] = $authId;                   
                    $input['department_head_id'] = $employee->leadManagerHandover->approval_by_id;
                    $input['finance_manager_id'] = $financeManager->handover_to_id;
                    $input['hr_manager_id'] = $HRManager->handover_to_id;
                    $input['division_head_id'] = $divisionHead->approval_handover_to;
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                    $input['request_date'] = Carbon::now($dubaiTimeZone);
                    $input['code'] = $code;
                    $createRequest = Liability::create($input);
                    $history['liability_id'] = $createRequest->id;
                    $history['icon'] = 'icons8-document-30.png';
                    $history['message'] = 'Employee liability request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = LiabilityHistory::create($history);
                    $history2['liability_id'] = $createRequest->id;
                    $history2['icon'] = 'icons8-send-30.png';
                    $history2['message'] = 'Employee liability request send to Team Lead / Reporting Manager ( '.$employee->leadManagerHandover->handOverTo->name.' - '.$employee->leadManagerHandover->handOverTo->email.' ) for approval';
                    $createHistory2 = LiabilityHistory::create($history2);
                    (new UserActivityController)->createActivity('Employee Liability Request Created');
                    $successMessage = "Employee Liability Hiring Request Created Successfully";
                }
                else {
                    $update = Liability::find($id);
                    if($update) {
                        $update->employee_id = $request->employee_id;
                        $update->type = $request->type;
                        $update->total_amount = $request->total_amount;
                        $update->no_of_installments = $request->no_of_installments;
                        $update->amount_per_installment = $request->amount_per_installment;
                        $update->no_of_installments = $request->no_of_installments;
                        $update->reason = $request->reason;
                        $update->updated_by = $authId;
                        $update->action_by_employee = 'pending';
                        $update->employee_action_at = NULL;
                        $update->comments_by_employee = NULL;
                        $update->action_by_department_head = NULL;
                        $update->department_head_action_at = NULL;
                        $update->comments_by_department_head = NULL;
                        $update->action_by_finance_manager = NULL;
                        $update->finance_manager_action_at = NULL;
                        $update->comments_by_finance_manager = NULL;
                        $update->action_by_hr_manager = NULL;
                        $update->hr_manager_action_at = NULL;
                        $update->comments_by_hr_manager = NULL;
                        $update->action_by_division_head = NULL;
                        $update->division_head_action_at = NULL;
                        $update->comments_by_division_head = NULL;
                        $update->department_head_id =  $employee->leadManagerHandover->approval_by_id;
                        $update->finance_manager_id = $financeManager->handover_to_id;
                        $update->hr_manager_id = $HRManager->handover_to_id;
                        $update->division_head_id = $divisionHead->approval_handover_to;
                        $update->employee_id = $request->employee_id;
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
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
}
