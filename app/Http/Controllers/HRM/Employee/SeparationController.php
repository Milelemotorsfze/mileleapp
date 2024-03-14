<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee\Separation;
use App\Models\HRM\Employee\SeparationHistory;
use App\Models\Masters\SeparationTypes;
use App\Models\Masters\SeparationReplacementTypes;
use App\Models\User;
use Validator;
use DB;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;

class SeparationController extends Controller
{
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'last_working_date' => 'required',
            'separation_type' => 'required',
            'replacement' => 'required',
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
                $createRequest = Separation::where('id',$id)->first();
                if($createRequest != '') {                  
                    $createRequest->status = 'pending';
                    $createRequest->employee_id = $request->employee_id;
                    $createRequest->action_by_employee = 'pending';
                    $createRequest->employee_action_at = NULL;                       
                    $createRequest->comments_by_employee = NULL;
                    $createRequest->action_by_takeover_employee = NULL;
                    $createRequest->takeover_employee_id = $request->takeover_employee_id;
                    $createRequest->takeover_employee_action_at = NULL;
                    $createRequest->comments_by_takeover_employee = NULL;
                    $createRequest->action_by_department_head = NULL;
                    $createRequest->department_head_id = $employee->leadManagerHandover->approval_by_id;
                    $createRequest->department_head_action_at = NULL;
                    $createRequest->comments_by_department_head = NULL;
                    $createRequest->action_by_hr_manager = NULL;
                    $createRequest->hr_manager_id = $HRManager->handover_to_id;
                    $createRequest->hr_manager_action_at = NULL;
                    $createRequest->comments_by_hr_manager = NULL;
                    $createRequest->updated_by = $authId;
                    $createRequest->replacement = $request->replacement;
                    $createRequest->separation_type = $request->separation_type;
                    if($request->replacement == 4) {
                        $createRequest->replacement_other = $request->replacement_other;
                        $createRequest->employment_type = NULL;
                    }
                    else {
                        $createRequest->replacement_other = NULL;
                        $createRequest->employment_type = $request->employment_type;
                    }
                    if($request->separation_type == 4) {
                        $createRequest->seperation_type_other = $request->seperation_type_other;
                    }
                    else {
                        $createRequest->seperation_type_other = NULL;
                    }
                    $createRequest->update();
                }
                $history['separations_id'] = $createRequest->id;
                $history['icon'] = 'icons8-document-30.png';
                $history['message'] = 'Separation Employee Handover request Updated by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                $createHistory = SeparationHistory::create($history);
                $history2['separations_id'] = $createRequest->id;
                $history2['icon'] = 'icons8-send-30.png';
                $history2['message'] = 'Separation Employee Handover request send to Employee ( '.$employee->first_name.' '.$employee->last_name.' - '.$employee->personal_email_address.' ) for approval';
                $createHistory2 = SeparationHistory::create($history2);
                (new UserActivityController)->createActivity('Separation Employee Handover Updated');
                $successMessage = "Separation Employee Handover Updated Successfully";                   
                DB::commit();
                return redirect()->route('separation-handover.index')->with('success',$successMessage); 
            }
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        } 
    }
    public function edit($id) {
        $data = Separation::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        $employees = User::whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        $separationTypes = SeparationTypes::all();
        $replacementTypes = SeparationReplacementTypes::all();
        return view('hrm.separation.edit',compact('data','employees','separationTypes','replacementTypes'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'last_working_date' => 'required',
            'separation_type' => 'required',
            'replacement' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $input = $request->all();
                $employ = EmployeeProfile::where('user_id',$request->employee_id)->first();
                if($employ->team_lead_or_reporting_manager != '' && !isset($employ->leadManagerHandover)) {
                    $createHandOvr['lead_or_manager_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['approval_by_id'] = $employ->team_lead_or_reporting_manager;
                    $createHandOvr['created_by'] = $authId;
                    $leadHandover = TeamLeadOrReportingManagerHandOverTo::create($createHandOvr);
                }
                $employee = EmployeeProfile::where('user_id',$request->employee_id)->first();
                $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                // $divisionHead = MasterDivisionWithHead::where('id',$employee->division)->first();
                $input['created_by'] = Auth::id();
                $input['hr_manager_id'] = $HRManager->handover_to_id;                
                $input['department_head_id'] = $employee->leadManagerHandover->approval_by_id;
                $createRequest = Separation::create($input);
                $history['separations_id'] = $createRequest->id;
                $history['icon'] = 'icons8-document-30.png';
                $history['message'] = 'Separation Employee Handover request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                $createHistory = SeparationHistory::create($history);
                $history2['separations_id'] = $createRequest->id;
                $history2['icon'] = 'icons8-send-30.png';
                $history2['message'] = 'Separation Employee Handover request send to Employee ( '.$employee->first_name.' '.$employee->last_name.' - '.$employee->personal_email_address.' ) for approval';
                $createHistory2 = SeparationHistory::create($history2);
                (new UserActivityController)->createActivity('Separation Employee Handover request Created');
                $successMessage = "Separation Employee Handover Created Successfully";                   
                DB::commit();
                return redirect()->route('separation-handover.index')->with('success',$successMessage); 
            }
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }     
    }
    public function create() {
        $employees = User::whereNotIn('id',[1,16])->whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        $separationTypes = SeparationTypes::all();
        $replacementTypes = SeparationReplacementTypes::all();
        return view('hrm.separation.create',compact('employees','separationTypes','replacementTypes'));
    }
    public function index() {
        $pendings = Separation::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-separation-handover'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings =$pendings->get();
        $approved = Separation::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-separation-handover'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved =$approved->get();
        $rejected = Separation::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-separation-employee-handover'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-separation-handover'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected =$rejected->get();
        return view('hrm.separation.index',compact('pendings','approved','rejected'));
    }
    public function show($id) {
        // $data = Separation::where('id',$id)->with('user.empProfile.department','user.empProfile.designation','user.empProfile.location')->first();
        // return view('hrm.separation.show',compact('data'));
        $errorMsg ="Comong Soon ! This page is under development phase now.. You can access later !";
        return view('hrm.notaccess',compact('errorMsg'));
    }
}
