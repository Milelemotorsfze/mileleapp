<?php

namespace App\Http\Controllers\HRM\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HRM\Employee\OverTime;
use App\Models\HRM\Employee\OverTimeDateTime;
use App\Models\HRM\Employee\OverTimeHistory;
use App\Models\User;
use Validator;
use DB;
use App\Http\Controllers\UserActivityController;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\Masters\MasterDivisionWithHead;

class OverTimeController extends Controller
{
    public function index() {
        $pendings = OverTime::where('status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-overtime'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-overtime'])) {
            $pendings = $pendings->where('employee_id',$authId)->latest();
        }
        $pendings =$pendings->get();
        $approved = OverTime::where('status','approved');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-overtime'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-overtime'])) {
            $approved = $approved->where('employee_id',$authId)->latest();
        }
        $approved =$approved->get();
        $rejected = OverTime::where('status','rejected');
        if(Auth::user()->hasPermissionForSelectedRole(['list-all-overtime'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['list-current-user-overtime'])) {
            $rejected = $rejected->where('employee_id',$authId)->latest();
        }
        $rejected =$rejected->get();
        return view('hrm.overtime.index',compact('pendings','approved','rejected'));
    }
    public function create() {
        $employees = User::whereHas('empProfile', function($q) {
            $q = $q->where('type','employee');
        })->with('empProfile.department','empProfile.designation','empProfile.location')->get();
        return view('hrm.overtime.create',compact('employees'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'overtime' => 'required',
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
                $divisionHead = MasterDivisionWithHead::where('id',$employee->division)->first();
                $input = $request->all();
                $input['created_by'] = $authId; 
                $input['hr_manager_id'] = $HRManager->handover_to_id;                
                $input['department_head_id'] = $employee->team_lead_or_reporting_manager;
                $input['division_head_id'] = $divisionHead->approval_handover_to;
                $createRequest = OverTime::create($input);
                if(count($request->overtime) > 0) {
                    foreach($request->overtime as $overtime) {
                        if($overtime['start_datetime'] != NULL && $overtime['end_datetime'] != NULL) {
                            $createTime = [];
                            $time['over_times_id'] = $createRequest->id;
                            $time['start_datetime'] = $overtime['start_datetime'];
                            $time['end_datetime'] = $overtime['end_datetime'];
                            $time['remarks'] = $overtime['remarks'];
                            $createTime = OverTimeDateTime::create($time);
                        }
                    }
                }
                $history['over_times_id'] = $createRequest->id;
                $history['icon'] = 'icons8-document-30.png';
                $history['message'] = 'Employee Overtime Application request created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                $createHistory = OverTimeHistory::create($history);
                $history2['over_times_id'] = $createRequest->id;
                $history2['icon'] = 'icons8-send-30.png';
                $history2['message'] = 'Employee Overtime Application request send to Employee ( '.$employee->first_name.' '.$employee->last_name.' - '.$employee->personal_email_address.' ) for approval';
                $createHistory2 = OverTimeHistory::create($history2);
                (new UserActivityController)->createActivity('Employee OverTime Application Created');
                $successMessage = "Employee OverTime Application Created Successfully";                   
                DB::commit();
                return redirect()->route('overtime.index')->with('success',$successMessage);
            }
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }       
    }
    public function show($id) {
        $data = OverTime::where('id',$id)->first();
        $previous = OverTime::where('id', '<', $id)->max('id');
        $next = OverTime::where('id', '>', $id)->min('id');
        return view('hrm.overtime.show',compact('data','previous','next'));
    }
}
