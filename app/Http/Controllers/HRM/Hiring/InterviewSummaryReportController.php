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

class InterviewSummaryReportController extends Controller
{
    public function index() {
        $shortlists = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round',NULL],
            ['date_of_first_round',NULL],
            ['date_of_telephonic_interview',NULL],
            ['status','pending'],
        ])->latest()->get();
        $telephonics = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round',NULL],
            ['date_of_first_round',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ])->latest()->get();
        $firsts = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ])->latest()->get();
        $seconds = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ])->latest()->get();
        $thirds = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ])->latest()->get();
        $forths = InterviewSummaryReport::where([
            ['date_of_fifth_round',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
        ])->latest()->get();
        $fifths = InterviewSummaryReport::where([
            ['date_of_fifth_round','!=',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['candidate_selected','==',NULL],
            ['status','pending'],
        ])->latest()->get();
        $notSelected = InterviewSummaryReport::where([
            ['date_of_fifth_round','!=',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
            ['candidate_selected','no'],
        ])->latest()->get();
        $pendings = InterviewSummaryReport::where([
            ['date_of_fifth_round','!=',NULL],
            ['date_of_forth_round','!=',NULL],
            ['date_of_third_round','!=',NULL],
            ['date_of_second_round','!=',NULL],
            ['date_of_first_round','!=',NULL],
            ['date_of_telephonic_interview','!=',NULL],
            ['status','pending'],
            ['candidate_selected','yes'],
        ])->latest()->get();
        // $pendings = InterviewSummaryReport::where('status','pending')->latest()->get();
        $approved = InterviewSummaryReport::where('status','approved')->where('seleced_status','pending')->latest()->get(); 
        $rejected = InterviewSummaryReport::where('status','rejected')->where('seleced_status','pending')->latest()->get();
        $selectedForJob = InterviewSummaryReport::where('status','approved')->where('seleced_status','selected')->latest()->get(); 
        $interviewersNames = User::whereNot('id',16)->select('id','name')->get();
        return view('hrm.hiring.interview_summary_report.index',compact('shortlists','telephonics','firsts','seconds','thirds','forths','fifths','notSelected',
        'pendings','approved','selectedForJob','rejected','interviewersNames'));
    }
    public function createOrEdit($id) {
        $currentInterviewReport = InterviewSummaryReport::with('telephonicInterviewers')->where('id',$id)->first();
        if(!$currentInterviewReport) {
            $currentInterviewReport = new InterviewSummaryReport();
            $interviewSummaryId = 'new';
        }
        else {
            $interviewSummaryId = $currentInterviewReport->id;
        }
        $hiringrequests = EmployeeHiringRequest::where('final_status','open')->whereHas('questionnaire')->whereHas('jobDescription', function($q){
            $q->where('status', 'approved');
        })->with('questionnaire.department','questionnaire.designation')->get();
        $data = EmployeeHiringRequest::where('id',$id)->first();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterGender = MasterGender::whereIn('id',[1,2])->get();
        $interviewersNames = User::whereNot('id',16)->select('id','name')->get();
        return view('hrm.hiring.interview_summary_report.createOrEdit',compact('id','data','masterNationality','interviewSummaryId','currentInterviewReport',
        'masterGender','interviewersNames','hiringrequests'));
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
                if($update) {
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
                }
                $update->update();
                DB::commit();
                return redirect()->route('interview-summary-report.index')
                                    ->with('success','Final Evaluation Of Candidate Created Successfully');
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
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
                if($update) {
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
                }
                $update->update();
                if(isset($request->interviewer_id)) {
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
                                    ->with('success',$request->round.' Round Interview Summary Report Created Successfully');
            } 
            catch (\Exception $e) {
                DB::rollback();
                dd($e);
            }
        }
    }
    public function requestAction(Request $request) {
        DB::beginTransaction();
        try {
            $message = '';
            $update = InterviewSummaryReport::where('id',$request->id)->first();
            if($request->current_approve_position == 'HR Manager') {
                $update->comments_by_hr_manager = $request->comment;
                $update->hr_manager_action_at = Carbon::now()->format('Y-m-d H:i:s');
                $update->action_by_hr_manager = $request->status;
                if($request->status == 'approved') {
                    $update->action_by_division_head = 'pending';
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
        catch (\Exception $e) {
            // info($e);
            DB::rollback();
            dd($e);
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
                    $update->hiring_request_id  = $request->hiring_request_id ;
                    $update->candidate_name  = $request->candidate_name ;
                    $update->nationality  = $request->nationality ;
                    $update->gender  = $request->gender ;
                    $update->telephonic_interview = $request->telephonic_interview;
                    $update->date_of_telephonic_interview  = $request->date_of_telephonic_interview ;
                    $update->rate_dress_appearance  = $request->rate_dress_appearance ;
                    $update->rate_body_language_appearance  = $request->rate_body_language_appearance ;
                    if($request->resume_file_name) {
                        $update->resume_file_name  = $fileName;
                    }
                    $update->updated_by = $authId;
                    $update->update();
                    $previousInterviewers = Interviewers::where('interview_summary_report_id',$update->id)->where('round',$request->round)->get();
                    if(count($previousInterviewers) > 0) {
                        foreach($previousInterviewers as $previousInterviewer) {
                            $previousInterviewer->delete();
                        }
                    }
                    $createInterviewer['interview_summary_report_id'] = $update->id;
                    $createInterviewer['round'] = $request->round;
                    if(isset($request->interviewer_id)) {
                        if(count($request->interviewer_id) > 0) {
                            foreach($request->interviewer_id as $interviewer_id) {
                                $createInterviewer['interviewer_id'] = $interviewer_id;
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
                                    ->with('success','Interview Summary Report Created Successfully');
            } 
            catch (\Exception $e) {
                DB::rollback();               
                dd($e);
            }
        }
    }
    public function show($id) {
        $data = InterviewSummaryReport::where('id',$id)->first();
        $previous = InterviewSummaryReport::where('hiring_request_id',$data->hiring_request_id)->where('id', '<', $id)->max('id');
        $next = InterviewSummaryReport::where('hiring_request_id',$data->hiring_request_id)->where('id', '>', $id)->min('id');
        return view('hrm.hiring.interview_summary_report.show',compact('data','previous','next')); 
    }
}
