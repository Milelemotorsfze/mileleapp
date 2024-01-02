<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\Masters\MasterGender;
use App\Models\User;
use App\Models\HRM\Employee\EmployeeProfile;
use Illuminate\Support\Facades\Auth;

class InterviewSummaryReport extends Model
{
    use HasFactory;
    protected $table = "interview_summary_reports";
    protected $fillable = [
        'hiring_request_id',
        'resume_file_name',
        'candidate_name',
        'nationality',
        'gender',
        // 'name_of_interviewer',
        // 'date_of_interview',
        'date_of_telephonic_interview',
        'telephonic_interview',
        'rate_dress_appearance',
        'rate_body_language_appearance',
        'date_of_first_round',
        'first_round',
        'date_of_second_round',
        'second_round',
        'date_of_third_round',
        'third_round',
        'date_of_forth_round',
        'forth_round',
        'date_of_fifth_round',
        'fifth_round',
        'final_evaluation_of_candidate',
        'candidate_selected',
        'status',
        'action_by_hr_manager',
        'hr_manager_id',
        'hr_manager_action_at',
        'comments_by_hr_manager',
        'action_by_division_head',
        'division_head_id',
        'division_head_action_at',
        'comments_by_division_head',
        'seleced_status',
        'selected_status_by',
        'selected_status_at',
        'selected_status_comment',
        'selected_hiring_request_id',
        'candidate_expected_salary',
        'offer_letter_send_at',
        'offer_letter_send_by',
        'offer_letter_verified_at',
        'offer_letter_verified_by',
        'total_salary',
        'email',
        'created_by',
        'updated_by',
        'deleted_by',
        'pif_sign',
    ];
    protected $appends = [
        'is_auth_user_can_approve',
        'current_status',
        'candidate_current_status',
    ];
    public function getIsAuthUserCanApproveAttribute() {
        $isAuthUserCanApprove = [];
        $authId = Auth::id();
        if($this->action_by_hr_manager =='pending' && $this->hr_manager_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'HR Manager';
            $isAuthUserCanApprove['current_approve_person'] = $this->hrManager->name;
        }
        else if($this->action_by_hr_manager =='approved' && $this->action_by_division_head =='pending' && $this->division_head_id == $authId) {
            $isAuthUserCanApprove['can_approve'] = true;
            $isAuthUserCanApprove['current_approve_position'] = 'Division Head';
            $isAuthUserCanApprove['current_approve_person'] = $this->divisionHeadName->name;
        }
        return $isAuthUserCanApprove;
    }
    public function getCurrentStatusAttribute() {
        $currentStatus = '';
        if($this->status == 'approved') {
            $currentStatus = 'Approved';
        }
        else if($this->status == 'rejected') {
            $currentStatus = 'Rejected';
        }
        // Approvals =>  HR manager -----------> Division head 
        else if($this->status == 'pending' && $this->action_by_hr_manager == 'pending') {
            $currentStatus = "HR Manager's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_division_head == 'pending') {
            $currentStatus = "Division Head's Approval Awaiting";
        }  
        return $currentStatus;
    }
    public function getCandidateCurrentStatusAttribute() {
        $candidateCurrentStatus = '';
        if($this->status == 'approved' && $this->candidate_selected == 'yes' && $this->seleced_status == 'selected') {
            $candidateCurrentStatus = 'Candidate Selected And Hiring Request Closed';
        }
        else if($this->status == 'approved' && $this->candidate_selected == 'yes' && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = 'Candidate Selected And Approved';
        }
        else if($this->status == 'rejected' && $this->candidate_selected == 'yes' && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = 'Rejected';
        }
        else if($this->status == 'pending' && $this->action_by_hr_manager == 'pending' && $this->candidate_selected == 'yes' && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Candidate Selected And HR Manager's Approval Awaiting";
        }
        else if($this->status == 'pending' && $this->action_by_division_head == 'pending' && $this->candidate_selected == 'yes' && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Candidate Selected And Division Head's Approval Awaiting";
        }  
        else if($this->candidate_selected == NULL && $this->date_of_fifth_round != NULL && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Fifth Round Interview Completed";
        }
        else if($this->candidate_selected == NULL && $this->date_of_forth_round != NULL && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Forth Round Interview Completed";
        }
        else if($this->candidate_selected == NULL && $this->date_of_third_round != NULL && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Third Round Interview Completed";
        }
        else if($this->candidate_selected == NULL && $this->date_of_second_round != NULL && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Second Round Interview Completed";
        }
        else if($this->candidate_selected == NULL && $this->date_of_first_round != NULL && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "First Round Interview Completed";
        }
        else if($this->candidate_selected == NULL && $this->date_of_telephonic_interview != NULL && $this->seleced_status == 'pending') {
            $candidateCurrentStatus = "Telephonic Round Interview Completed";
        }
        else {
            $candidateCurrentStatus = "Resume Shortlisted";
        }
        return $candidateCurrentStatus;
    }
    public function nationalities() {
        return $this->hasOne(Country::class,'id','nationality');
    }
    public function genderName() {
        return $this->hasOne(MasterGender::class,'id','gender');
    }
    public function employeeHiringRequest() {
        return $this->hasOne(EmployeeHiringRequest::class,'id','hiring_request_id');
    }
    public function nameOfInterviewer() {
        return $this->hasOne(User::class,'id','name_of_interviewer');
    }
    public function hrManager() {
        return $this->hasOne(User::class,'id','hr_manager_id');
    }
    public function divisionHeadName() {
        return $this->hasOne(User::class,'id','division_head_id');
    }
    public function createdBy() {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function telephonicInterviewers() {
        return $this->hasMany(Interviewers::class,'interview_summary_report_id','id')->where('round','telephonic');
    }
    public function firstRoundInterviewers() {
        return $this->hasMany(Interviewers::class,'interview_summary_report_id','id')->where('round','first');
    }
    public function secondRoundInterviewers() {
        return $this->hasMany(Interviewers::class,'interview_summary_report_id','id')->where('round','second');
    }
    public function thirdRoundInterviewers() {
        return $this->hasMany(Interviewers::class,'interview_summary_report_id','id')->where('round','third');
    }
    public function forthRoundInterviewers() {
        return $this->hasMany(Interviewers::class,'interview_summary_report_id','id')->where('round','forth');
    }
    public function fifthRoundInterviewers() {
        return $this->hasMany(Interviewers::class,'interview_summary_report_id','id')->where('round','fifth');
    }
    public function candidateDetails() {
        return $this->hasOne(EmployeeProfile::class,'interview_summary_id','id');
    }
    public function offerLetterSendBy() {
        return $this->hasOne(User::class,'id','offer_letter_send_by'); 
    }
    public function offerLetterVerifieddBy() {
        return $this->hasOne(User::class,'id','offer_letter_verified_by'); 
    }
}
