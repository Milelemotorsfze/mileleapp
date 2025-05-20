<?php

namespace App\Http\Controllers\HRM\Hiring;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use App\Models\HRM\Hiring\Interviewers;
use Carbon\Carbon;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Http\Controllers\UserActivityController;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\Masters\MasterGender;
use App\Models\Masters\MasterDepartment;
use App\Models\Masters\MasterJobPosition;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\User;
use Validator;
use App\Models\HRM\Approvals\ApprovalByPositions;
use App\Models\HRM\Employee\EmployeeProfile;
use App\Http\Controllers\HRM\Hiring\CandidatePersonalInfoController;
use App\Models\Masters\MasterDivisionWithHead;

class InterviewSummaryReportController extends Controller
{
    public function index() {
        $authId = Auth::id();
        $shortlists = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round',NULL],
            ['date_of_first_round',NULL],
            ['date_of_telephonic_interview',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $shortlists = $shortlists->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $shortlists = $shortlists->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $shortlists = $shortlists->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $shortlists = $shortlists->get();
        $telephonics = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round',NULL],
            ['date_of_first_round',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $telephonics = $telephonics->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $telephonics = $telephonics->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $telephonics = $telephonics->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $telephonics = $telephonics->get();
        $firsts = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $firsts = $firsts->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $firsts = $firsts->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $firsts = $firsts->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $firsts = $firsts->get();
        $seconds = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $seconds = $seconds->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $seconds = $seconds->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $seconds = $seconds->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $seconds = $seconds->get();
        $thirds = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $thirds = $thirds->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $thirds = $thirds->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $thirds = $thirds->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $thirds = $thirds->get();
        $forths = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $forths = $forths->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $forths = $forths->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $forths = $forths->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $forths = $forths->get();
        $fifths = InterviewSummaryReport::where([
            ['date_of_fifth_round','!=',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['candidate_selected',NULL],
            ['status','pending'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $fifths = $fifths->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $fifths = $fifths->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $fifths = $fifths->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $fifths = $fifths->get();
        $notSelected = InterviewSummaryReport::where([
            ['date_of_fifth_round','!=',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
            ['candidate_selected','no'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $notSelected = $notSelected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $notSelected = $notSelected->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $notSelected = $notSelected->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $notSelected = $notSelected->get();
        $pendings = InterviewSummaryReport::where([
            ['date_of_fifth_round','!=',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
            ['candidate_selected','yes'],
        ]);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $pendings = $pendings->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $pendings = $pendings->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $pendings = $pendings->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $pendings = $pendings->get();
        $approved = InterviewSummaryReport::where('status','approved')->where('seleced_status','pending')->whereDoesntHave('candidateDetails');
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $approved = $approved->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $approved = $approved->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $approved = $approved->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $approved = $approved->get();
        $rejected = InterviewSummaryReport::where('status','rejected')->where('seleced_status','pending');
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $rejected = $rejected->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $rejected = $rejected->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $rejected = $rejected->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $rejected = $rejected->get();
        $selectedForJob = InterviewSummaryReport::where('status','approved')->where('seleced_status','selected');
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $selectedForJob = $selectedForJob->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $selectedForJob = $selectedForJob->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $selectedForJob = $selectedForJob->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $selectedForJob = $selectedForJob->get(); 
        $docsUploaded = InterviewSummaryReport::where('status','approved')->where('seleced_status','pending')->whereHas('candidateDetails');
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $docsUploaded = $docsUploaded->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $docsUploaded = $docsUploaded->whereHas('employeeHiringRequest', function($q1) use($authId) {
                $q1->where('requested_by',$authId);
            })->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $docsUploaded = $docsUploaded->whereHas('employeeHiringRequest.questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $docsUploaded = $docsUploaded->get();
        $interviewersNames = User::orderBy('name','ASC')->where('status','active')->whereHas('empProfile')->whereNotIn('id',[1,16])->select('id','name')->get();
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing','requestedby-view-interview-summary-listing','organizedby-view-interview-summary-listing'])) {
            return view('hrm.hiring.interview_summary_report.index',compact('shortlists','telephonics','firsts','seconds','thirds','forths','fifths','notSelected',
            'pendings','approved','selectedForJob','docsUploaded','rejected','interviewersNames'));
        }
        else {
            $errorMsg ="Sorry ! You don't have permission to access this page";
            return view('hrm.notaccess',compact('errorMsg'));
        }      
    }
    public function createOrEdit($id) {
        $authId = Auth::id();
        $currentInterviewReport = InterviewSummaryReport::with('telephonicInterviewers','firstRoundInterviewers','secondRoundInterviewers',
        'thirdRoundInterviewers','forthRoundInterviewers','fifthRoundInterviewers')->where('id',$id)->first();
        if(!$currentInterviewReport) {
            $currentInterviewReport = new InterviewSummaryReport();
            $interviewSummaryId = 'new';
        }
        else {
            $interviewSummaryId = $currentInterviewReport->id;
        }
        $hiringrequests = EmployeeHiringRequest::where('final_status','open')->whereHas('questionnaire')->whereHas('jobDescription', function($q){
            $q->where('status', 'approved');
        })->with('questionnaire.department','questionnaire.designation');
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $hiringrequests = $hiringrequests->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $hiringrequests = $hiringrequests->where('requested_by',$authId)->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $hiringrequests = $hiringrequests->whereHas('questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $hiringrequests = $hiringrequests->get();
        $data = EmployeeHiringRequest::where('id',$currentInterviewReport->hiring_request_id);
        if(Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing'])) {
            $data = $data->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['requestedby-view-interview-summary-listing'])) {
            $data = $data->where('requested_by',$authId)->latest();
        }
        else if(Auth::user()->hasPermissionForSelectedRole(['organizedby-view-interview-summary-listing'])) {
            $data = $data->whereHas('questionnaire', function($q2) use($authId) {
                $q2->where('interviewd_by',$authId);
            })->latest();
        }
        $data = $data->first();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterGender = MasterGender::whereIn('id',[1,2])->get();
        $interviewersNames = User::orderBy('name','ASC')->where('status','active')->whereNotIn('id',[1,16])->whereHas('empProfile')->select('id','name')->get();
        if($id == 'new') {
            return view('hrm.hiring.interview_summary_report.create',compact('id','data','masterNationality','interviewSummaryId','currentInterviewReport',
            'masterGender','interviewersNames','hiringrequests'));   
        }
        else if($data == '') {
            $errorMsg ="Sorry ! You don't have permission to access this page";
            return view('hrm.notaccess',compact('errorMsg'));
        }
        else {
            return view('hrm.hiring.interview_summary_report.createOrEdit',compact('id','data','masterNationality','interviewSummaryId','currentInterviewReport',
            'masterGender','interviewersNames','hiringrequests'));
        }
    }
    public function finalEvaluation(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'round' => 'required',         
            'candidate_selected' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $update = InterviewSummaryReport::where('id',$request->id)->first();
                if($update && $update->status == 'pending') {
                    if($request->round == 'final') {
                        $update->candidate_selected = $request->candidate_selected;
                        $update->final_evaluation_of_candidate = $request->comment;
                    }
                    if($request->candidate_selected == 'yes') {
                        $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                        $update->hr_manager_id = $HRManager->handover_to_id;
                        $update->action_by_hr_manager = 'pending';
                        $hiringRequest = EmployeeHiringRequest::where('id',$update->hiring_request_id)->first();
                        $update->division_head_id = $hiringRequest->division_head_id;
                    }
                    $msg = 'Final Evaluation Of Candidate Created Successfully';
                }
                else if(($update && $update->status == 'approved') OR ($update && $update->status == 'rejected')) {
                    $msg = "can't update this interview summary report ,because it is already ". $update->status;
                }
                $update->update();
                DB::commit();
                return redirect()->route('interview-summary-report.index')
                                    ->with('success',$msg);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function updateRoundSummary(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'round' => 'required',         
            'date' => 'required',
            'interviewer_id' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            // $data['error'] = true;
            // $data['msg'] = $validator->messages()->all();
            // return response()->json($data);
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $update = InterviewSummaryReport::where('id',$request->id)->first();
                if($update && $update->status == 'pending') {
                    if($request->round == 'telephonic') {
                        $update->date_of_telephonic_interview = $request->date;
                        $update->telephonic_interview = $request->comment;
                    }
                    elseif($request->round == 'first') {
                        $update->date_of_first_round = $request->date;
                        $update->first_round = $request->comment;
                    }
                    elseif($request->round == 'second') {
                        $update->date_of_second_round = $request->date;
                        $update->second_round = $request->comment;
                    }
                    elseif($request->round == 'third') {
                        $update->date_of_third_round = $request->date;
                        $update->third_round = $request->comment;
                    }
                    elseif($request->round == 'forth') {
                        $update->date_of_forth_round = $request->date;
                        $update->forth_round = $request->comment;
                    }
                    elseif($request->round == 'fifth') {
                        $update->date_of_fifth_round = $request->date;
                        $update->fifth_round = $request->comment;
                    }
                    $msg = $request->round.' Round Interview Summary Report Created Successfully';
                }
                if(($update && $update->status == 'approved') OR ($update && $update->status == 'rejected')) {
                    $msg = "can't update this interview summary report ,because it is already ". $update->status;
                }
                $update->update();
                if(isset($request->interviewer_id) && $update && $update->status == 'pending') {
                    if(count($request->interviewer_id) > 0) {
                        foreach($request->interviewer_id as $interviewer) {
                            $createInterviewer['interview_summary_report_id'] = $request->id;
                            $createInterviewer['round'] = $request->round;
                            $createInterviewer['interviewer_id'] = $interviewer;
                            $createInterviewerData = Interviewers::create($createInterviewer);
                        }
                    }
                }
                DB::commit();
                // return response()->json('success');
                return redirect()->route('interview-summary-report.index',)
                                    ->with('success',$msg);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = InterviewSummaryReport::where('id',$request->id)->first();
            if($update && $update->status == 'pending') {
                
            
            if($request->current_approve_position == 'HR Manager') {
                $update->comments_by_hr_manager = $request->comment;
                $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_hr_manager = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_division_head = 'pending';
                    $employee1 = EmployeeProfile::where('user_id',$update->employeeHiringRequest->requested_by)->first();
                    $divisionHead1 = MasterDivisionWithHead::where('id',$employee1->department->division_id)->first();
                    $update->division_head_id = $divisionHead1->approval_handover_to;
                    $message = 'Interview Summary Report send to Division Head ( '.$update->divisionHeadName->name.' - '.$update->divisionHeadName->email.' ) for approval';
                }
            }
            else if($request->current_approve_position == 'Division Head') {
                $update->comments_by_division_head = $request->comment;
                $update->division_head_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_division_head = $request->status;
                if($request->status == 'approved') {
                    $update->status = 'approved';
                }
            }
            if($request->status == 'rejected') {
                $update->status = 'rejected';
            }
            $update->update();
            $history['hiring_request_id'] = $update->hiring_request_id;
            if($request->status == 'approved') {
                $history['icon'] = 'icons8-thumb-up-30.png';
            }
            else if($request->status == 'rejected') {
                $history['icon'] = 'icons8-thumb-down-30.png';
            }
            $history['message'] = 'Interview Summary Report '.$request->status.' by '.$request->current_approve_position.' ( '.Auth::user()->name.' - '.Auth::user()->email.' )';
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
        else {
            return response()->json('error'); 
        }
        } 
        catch (\Exception $e) {
            DB::rollback();
            info($e);
            $errorMsg ="Something went wrong! Contact your admin";
            return view('hrm.notaccess',compact('errorMsg'));
        }
    }
    public function storeOrUpdate(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'hiring_request_id' => 'required',
            'candidate_name' => 'required',
            'nationality' => 'required',
            'gender' => 'required',
            // 'resume_file_name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $input = $request->all();
                $update = InterviewSummaryReport::where('id',$id)->first();
                if($request->resume_file_name) {
                    $fileName = $authId . '_' . time() . '.'. $request->resume_file_name->extension();
                    $type = $request->resume_file_name->getClientMimeType();
                    $size = $request->resume_file_name->getSize();
                    $request->resume_file_name->move(public_path('resume'), $fileName);
                }
                if($update) {
                    if($update->status == 'pending') {
                    $update->hiring_request_id  = $request->hiring_request_id ;
                    $update->candidate_name  = $request->candidate_name ;
                    $update->nationality  = $request->nationality ;
                    $update->gender  = $request->gender ;
                    $update->telephonic_interview = $request->telephonic_interview;
                    $update->date_of_telephonic_interview  = $request->date_of_telephonic_interview ;
                    $update->first_round = $request->first_round;
                    $update->date_of_first_round  = $request->date_of_first_round ;
                    $update->second_round = $request->second_round;
                    $update->date_of_second_round  = $request->date_of_second_round ;
                    $update->third_round = $request->third_round;
                    $update->date_of_third_round  = $request->date_of_third_round ;
                    $update->forth_round = $request->forth_round;
                    $update->date_of_forth_round  = $request->date_of_forth_round ;
                    $update->fifth_round = $request->fifth_round;
                    $update->date_of_fifth_round  = $request->date_of_fifth_round ;
                    $update->rate_dress_appearance  = $request->rate_dress_appearance ;
                    $update->rate_body_language_appearance  = $request->rate_body_language_appearance ;
                    if($request->resume_file_name) {
                        $update->resume_file_name  = $fileName;
                    }
                    $update->updated_by = $authId;
                    if(isset($request->candidate_selected)) {
                        $update->candidate_selected = $request->candidate_selected;
                        if($request->candidate_selected == 'yes') {
                            $HRManager = ApprovalByPositions::where('approved_by_position','HR Manager')->first();
                            $update->hr_manager_id = $HRManager->handover_to_id;
                            $update->action_by_hr_manager = 'pending';
                            $hiringRequest = EmployeeHiringRequest::where('id',$update->hiring_request_id)->first();
                            $update->division_head_id = $hiringRequest->division_head_id;
                        }
                        elseif($request->candidate_selected == 'no') {
                            $update->hr_manager_id = NULL;
                            $update->action_by_hr_manager = NULL;
                            $update->division_head_id = NULL;
                        }
                    }
                    if(isset($request->final_evaluation_of_candidate)) {
                        $update->final_evaluation_of_candidate = $request->final_evaluation_of_candidate;
                    }
                    $update->update();
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round',$request->round)->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    if(isset($request->interviewer_id)) {
                        if($request->interviewer_id != '' && count($request->interviewer_id) > 0) {
                            foreach($request->interviewer_id as $interviewer_id) {
                                $createInterviewer = [];
                                $createInterviewer['interview_summary_report_id'] = $update->id;
                                $createInterviewer['round'] = $request->round;
                                $createInterviewer['interviewer_id'] = $interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }
                    $previousInterviewers = [];
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round','first')->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    if(isset($request->first_interviewer_id)) {
                        if($request->first_interviewer_id != '' && count($request->first_interviewer_id) > 0) {
                            foreach($request->first_interviewer_id as $first_interviewer_id) {
                                $createInterviewer = [];
                                $createInterviewer['interview_summary_report_id'] = $update->id;
                                $createInterviewer['round'] = 'first';
                                $createInterviewer['interviewer_id'] = $first_interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }
                    $previousInterviewers = [];
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round','second')->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    if(isset($request->first_interviewer_id)) {
                        if($request->second_interviewer_id != '' && count($request->second_interviewer_id) > 0) {
                            foreach($request->second_interviewer_id as $second_interviewer_id) {
                                $createInterviewer = [];
                                $createInterviewer['interview_summary_report_id'] = $update->id;
                                $createInterviewer['round'] = 'second';
                                $createInterviewer['interviewer_id'] = $second_interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }
                    $previousInterviewers = [];
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round','third')->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    if(isset($request->third_interviewer_id)) {
                        if($request->third_interviewer_id != '' && count($request->third_interviewer_id) > 0) {
                            foreach($request->third_interviewer_id as $third_interviewer_id) {
                                $createInterviewer = [];
                                $createInterviewer['interview_summary_report_id'] = $update->id;
                                $createInterviewer['round'] = 'third';
                                $createInterviewer['interviewer_id'] = $third_interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }                   
                    $previousInterviewers = [];
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round','forth')->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    if(isset($request->forth_interviewer_id)) {
                        if($request->forth_interviewer_id != '' && count($request->forth_interviewer_id) > 0) {
                            foreach($request->forth_interviewer_id as $forth_interviewer_id) {
                                $createInterviewer = [];
                                $createInterviewer['interview_summary_report_id'] = $update->id;
                                $createInterviewer['round'] = 'forth';
                                $createInterviewer['interviewer_id'] = $forth_interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }
                    $previousInterviewers = [];
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round','fifth')->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    if(isset($request->fifth_interviewer_id)) {
                        if($request->fifth_interviewer_id != '' && count($request->fifth_interviewer_id) > 0) {
                            foreach($request->fifth_interviewer_id as $fifth_interviewer_id) {
                                $createInterviewer = [];
                                $createInterviewer['interview_summary_report_id'] = $update->id;
                                $createInterviewer['round'] = 'fifth';
                                $createInterviewer['interviewer_id'] = $fifth_interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }
                    $history['hiring_request_id'] = $update->hiring_request_id;
                    $history['icon'] = 'icons8-edit-30.png';
                    $history['message'] = $request->candidate_name.' Interview Summary updated by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    (new UserActivityController)->createActivity('Interview Summary updated');
                    $successMessage = "Interview Summary updated Successfully";
                }
                else {
                    $successMessage = "can't update this interview summary report ,because it is already ". $update->status;
                }
                }
                else {    
                    if($request->resume_file_name) {
                        $input['resume_file_name'] = $fileName;
                    }
                    $input['created_by'] = $authId;  
                    $createRequest = InterviewSummaryReport::create($input);
                    $createInterviewer['interview_summary_report_id'] = $createRequest->id;
                    $createInterviewer['round'] = $request->round;
                    if(isset($request->interviewer_id)) {
                        if(count($request->interviewer_id) > 0) {
                            foreach($request->interviewer_id as $interviewer_id) {
                                $createInterviewer['interviewer_id'] = $interviewer_id;
                                $intervierCreated = Interviewers::create($createInterviewer);
                            }
                        }
                    }
                    $history['hiring_request_id'] = $createRequest->hiring_request_id;
                    $history['icon'] = 'icons8-questionnaire-30.png';
                    $history['message'] = $request->candidate_name.' Interview Summary created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    (new UserActivityController)->createActivity($createHistory->message);
                    $successMessage = "Interview Summary created Successfully";
                }
                DB::commit();
                return redirect()->route('interview-summary-report.index')
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
    public function show($id) {
        $data = InterviewSummaryReport::where('id',$id)->first();
        $previous = InterviewSummaryReport::where('hiring_request_id',$data->hiring_request_id)->where('id', '<', $id)->max('id');
        $next = InterviewSummaryReport::where('hiring_request_id',$data->hiring_request_id)->where('id', '>', $id)->min('id');
        $emp = EmployeeProfile::where('interview_summary_id',$id)->first();
        $hr = ApprovalByPositions::where('approved_by_position','HR Manager')->first(); 
        $inwords = [];  
        $data->isAuth = '';            
        if($emp) {
            $inwords['basic_salary'] = (new CandidatePersonalInfoController)->decimalNumberInWords($emp->basic_salary);
            $inwords['other_allowances'] = (new CandidatePersonalInfoController)->decimalNumberInWords($emp->other_allowances);
            $inwords['total_salary'] = (new CandidatePersonalInfoController)->decimalNumberInWords($emp->total_salary);
            if($emp->offer_sign != NULL && $emp->offer_signed_at != NULL && $emp->offer_letter_hr_id != NULL) {
                $data->isAuth = 2;
            }
            else if($data->offer_letter_send_at != NULL && $emp->offer_sign == NULL && $emp->offer_signed_at == NULL && $emp->offer_letter_hr_id == NULL) {
                $data->isAuth = 1;
            }
        }
        $data->canVerifySign = true;
        return view('hrm.hiring.interview_summary_report.show',compact('data','previous','next','emp','inwords','hr')); 
    }
    public function salary(Request $request) { 
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $update = InterviewSummaryReport::where('id',$request->id)->first();
                if($update && $update->offer_letter_verified_at == NULL) {
                    $update->candidate_expected_salary = $request->candidate_expected_salary;
                    $update->total_salary = $request->total_salary;
                    $msg = 'Salary Details Of Candidate Created Successfully';
                }
                else {
                    $msg = "can't update this candidate salary details ,because this candidate's salary already verified ";
                }
                $update->update();
                DB::commit();
                return redirect()->route('interview-summary-report.index')
                                    ->with('success',$msg);
            } 
            catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg ="Something went wrong! Contact your admin";
                return view('hrm.notaccess',compact('errorMsg'));
            }
        }
    }
    public function approvalAwaiting(Request $request) {
        $authId = Auth::id();
        $page = 'approval';
        $HRManager = '';
        $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = [];     
        $HRManager = ApprovalByPositions::where([
            ['approved_by_position','HR Manager'],
            ['handover_to_id',$authId]
        ])->first();
        if($HRManager) {
            $HRManagerPendings = InterviewSummaryReport::where([
                ['action_by_hr_manager','pending'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
            $HRManagerApproved = InterviewSummaryReport::where([
                ['action_by_hr_manager','approved'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
            $HRManagerRejected = InterviewSummaryReport::where([
                ['action_by_hr_manager','rejected'],
                ['hr_manager_id',$authId],
                ])->latest()->get();
        }
        $divisionHeadPendings = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','pending'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadApproved = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','approved'],
            ['division_head_id',$authId],
            ])->latest()->get();
        $divisionHeadRejected = InterviewSummaryReport::where([
            ['action_by_hr_manager','approved'],
            ['action_by_division_head','rejected'],                
            ['division_head_id',$authId],
            ])->latest()->get();
        return view('hrm.hiring.interview_summary_report.approvals',compact('page','divisionHeadPendings','divisionHeadApproved','divisionHeadRejected','HRManagerPendings','HRManagerApproved','HRManagerRejected',));
    }
}
