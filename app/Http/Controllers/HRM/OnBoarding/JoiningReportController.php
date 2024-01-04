<?php

namespace App\Http\Controllers\HRM\OnBoarding;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Employee\JoiningReport;
use App\Models\HRM\Employee\JoiningReportHistory;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserActivityController;
use Validator;
use DB;

class JoiningReportController extends Controller
{
    public function index() {
        $pendings = JoiningReport::where('joining_type','new_employee')->where('action_by_department_head',NULL)->orWhere('action_by_department_head','pending')
                    ->get();
        $approved = JoiningReport::where([
            ['joining_type','new_employee'],
            ['action_by_department_head','!=','approved']
        ])->get();
        $rejected = JoiningReport::where([
            ['joining_type','new_employee'],
            ['action_by_department_head','!=','rejected']
        ])->get();
        return view('hrm.onBoarding.joiningReport.index',compact('pendings','approved','rejected'));
    }
    public function create() {
        $candidates = EmployeeProfile::where([
            ['personal_information_verified_at','!=',NULL],
            ['type','candidate'],
        ])->whereHas('interviewSummary', function($q) {
            $q->where('offer_letter_verified_at','!=',NULL);
        })->with('designation','department')->get();
        $masterlocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get(); 
        $reportingTo = User::where([
            ['id','!=',16],
            ['status','active']
        ])->get();
        return view('hrm.onBoarding.joiningReport.create',compact('candidates','masterlocations','reportingTo'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'employee_code' => 'required',
            'joining_type' => 'required',
            'joining_date' => 'required',
            'location_id' => 'required',
            'type' => 'required',
            'team_lead_or_reporting_manager' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                DB::commit();
                $emp = EmployeeProfile::where('id',$request->employee_id)->first();
                if($emp) {
                    $emp->employee_code = $request->employee_code;
                    $emp->team_lead_or_reporting_manager = $request->team_lead_or_reporting_manager;
                    $emp->update();
                }
                $input = $request->all(); 
                if($request->type == 'trial') {
                    $input['trial_period_joining_date'] = $request->joining_date;
                }
                else if($request->type == 'permanent') {
                    $input['permanent_joining_date'] = $request->joining_date;
                }
                $input['permanent_joining_location_id'] = $request->location;
                $input['prepared_by_id'] = Auth::id();
                $input['created_by'] = Auth::id();
                $input['department_head_id'] = $request->team_lead_or_reporting_manager;
                $createJoinRep = JoiningReport::create($input);
                $history['joining_report_id'] = $createJoinRep->id;
                $history['icon'] = 'icons8-document-30.png';
                $history['message'] = 'Employee joining report created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                $createHistory = JoiningReportHistory::create($history);
                $history2['joining_report_id'] = $createJoinRep->id;
                $history2['icon'] = 'icons8-send-30.png';
                $history2['message'] = 'Employee joining report send to Prepared by ( '.Auth::user()->name.' - '.Auth::user()->email.' ) for approval';
                $createHistory2 = JoiningReportHistory::create($history2);
                (new UserActivityController)->createActivity('New Employee joining report Created');               
                $successMessage = 'Candidate Personal Information Form Submitted Successfully.';
                return redirect()->route('joining_report.index');
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function show($id) {
        $previous = $next = '';
        $data = JoiningReport::where('id',$id)->first();
        $previous = JoiningReport::where('id', '<', $id)->max('id');
        $next = JoiningReport::where('id', '>', $id)->min('id');
        $empJoinings = JoiningReport::where('employee_id',$data->employee_id)->get();
        return view('hrm.onBoarding.joiningReport.show',compact('data','previous','next'));
    }
    public function edit($id) {
        $data = JoiningReport::where('id',$id)->first();
        $candidates = EmployeeProfile::where([
            ['personal_information_verified_at','!=',NULL],
            ['type','candidate'],
        ])->whereHas('interviewSummary', function($q) {
            $q->where('offer_letter_verified_at','!=',NULL);
        })->with('designation','department')->get();
        $masterlocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get(); 
        $reportingTo = User::where([
            ['id','!=',16],
            ['status','active']
        ])->get();
        return view('hrm.onBoarding.joiningReport.edit',compact('data','candidates','masterlocations','reportingTo'));
    }
}
