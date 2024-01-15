<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB; // Import the DB facade here
use App\Models\HRM\Employee\EmployeeProfile;
use App\Models\HRM\Employee\PassportRequest;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\HRM\Hiring\JobDescription;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use App\Models\HRM\Employee\JoiningReport;
use App\Models\HRM\Employee\PassportRelease;
use App\Models\HRM\Employee\Liability;
use App\Models\HRM\Employee\Leave;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'selected_role', // Add the selected_role column here
        'sales_rap',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $appends = [
        'passport_with',
        'can_submit_or_release_passport',
        'hiring_request_approval',
        'job_description_approval',
        'interview_summary_report_approval',
        'candidate_docs_varify',
        'verify_offer_letters',
        'candidate_personal_information_varify',
        'joining_report_approval',
        'passport_submit_request_approval',
        'passport_release_request_approval',
        'liability_request_approval',
        'leave_request_approval',
        'advance_or_loan_balance',
    ];
    public function getPassportWithAttribute() {
        $passportWith = 'with_employee';
        
        // $passportRequest = PassportRequest::where('employee_id',$this->id)->where('passport_status','with_company')->latest('id')->first();
        // if($passportRequest) {
        //     $passportWith = 'with_company';
        // }
        if($this->empProfile->passport_status == 'with_milele') {
            $passportWith = 'with_company';
        }
        return $passportWith;
    }
    public function getCanSubmitOrReleasePassportAttribute() {
        $canSubmitOrReleasePassport = false;
        if($this->id != 16) {
            if($this->empProfile->type == 'employee' && ($this->empProfile->passport_status == null OR $this->empProfile->passport_status == 'with_employee')) {
                $isSubmitPending = PassportRequest::where([
                    ['employee_id',$this->id],
                    ['submit_status','pending'],
                ])->first();
                if($isSubmitPending == null) {
                    $canSubmitOrReleasePassport = true;
                }
            }
            else if($this->empProfile->type == 'employee' && $this->empProfile->passport_status == 'with_milele') {
                $isReleasePending = PassportRelease::where([
                    ['employee_id',$this->id],
                    ['release_submit_status','pending'],
                ])->first();
                if($isReleasePending == null) {
                    $canSubmitOrReleasePassport = true;
                }
            }
        }
        return $canSubmitOrReleasePassport;
    }
    public function getHiringRequestApprovalAttribute() {
        $hiringRequestApproval['can'] = false;
        $hiringRequestApproval['count'] = 0;
        $hiringManagerPendings = $hiringManagerApproved = $hiringManagerRejected = $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = $HRManagerPendings 
        = $HRManagerApproved = $HRManagerRejected = [];
        $hiringManagerPendings = EmployeeHiringRequest::where([['action_by_hiring_manager','pending'],['hiring_manager_id',$this->id],])->latest()->get();
        $hiringManagerApproved = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['hiring_manager_id',$this->id],])->latest()->get();
        $hiringManagerRejected = EmployeeHiringRequest::where([['action_by_hiring_manager','rejected'],['hiring_manager_id',$this->id],])->latest()->get();
        $deptHeadPendings = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['action_by_department_head','pending'],
            ['department_head_id',$this->id],])->latest()->get();
        $deptHeadApproved = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['action_by_department_head','pending'],
            ['department_head_id',$this->id],])->latest()->get();
        $deptHeadRejected = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['action_by_department_head','pending'],
            ['department_head_id',$this->id],])->latest()->get();
        $HRManagerPendings = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['action_by_department_head','approved'],
            ['action_by_hr_manager','pending'],['hr_manager_id',$this->id],])->latest()->get();
        $HRManagerApproved = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['action_by_department_head','approved'],
            ['action_by_hr_manager','pending'],['hr_manager_id',$this->id],])->latest()->get();
        $HRManagerRejected = EmployeeHiringRequest::where([['action_by_hiring_manager','approved'],['action_by_department_head','approved'],                
            ['action_by_hr_manager','pending'],['hr_manager_id',$this->id],])->latest()->get();
        if(count($hiringManagerPendings) > 0 OR count($hiringManagerApproved) > 0 OR count($hiringManagerRejected) > 0 OR count($deptHeadPendings) > 0 OR 
        count($deptHeadApproved) > 0 OR count($deptHeadRejected) > 0 OR count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0) {
            $hiringRequestApproval['can'] = true;
            $hiringRequestApproval['count'] = count($hiringManagerPendings) + count($deptHeadPendings) + count($HRManagerPendings);
        }
        return $hiringRequestApproval;
    }
    public function getJobDescriptionApprovalAttribute() {
        $jobDescriptionApproval['can'] = false;
        $jobDescriptionApproval['count'] = 0;
        $deptHeadPendings = $deptHeadApproved = $deptHeadRejected = $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $deptHeadPendings = JobDescription::where([['action_by_department_head','pending'],['department_head_id',$this->id],])->latest()->get();
        $deptHeadApproved = JobDescription::where([['action_by_department_head','approved'],['department_head_id',$this->id],])->latest()->get();
        $deptHeadRejected = JobDescription::where([['action_by_department_head','rejected'],['department_head_id',$this->id],])->latest()->get();
        $HRManagerPendings = JobDescription::where([['action_by_department_head','approved'],['action_by_hr_manager','pending'],['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerApproved = JobDescription::where([['action_by_department_head','approved'],['action_by_hr_manager','approved'],['hr_manager_id',$this->id],
            ])->latest()->get();
        $HRManagerRejected = JobDescription::where([['action_by_department_head','approved'],['action_by_hr_manager','rejected'],['hr_manager_id',$this->id],
            ])->latest()->get();
        if(count($deptHeadPendings) > 0 OR count($deptHeadApproved) > 0 OR count($deptHeadRejected) > 0 OR count($HRManagerPendings) > 0 OR 
            count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0) {
                $jobDescriptionApproval['can'] = true;
                $jobDescriptionApproval['count'] = count($deptHeadPendings) + count($HRManagerPendings);
            }
        return $jobDescriptionApproval;
    }
    public function getInterviewSummaryReportApprovalAttribute() {
        $interviewSummaryReportApproval['can'] = false;
        $interviewSummaryReportApproval['count'] = 0;
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = $HRManagerPendings = $HRManagerApproved = $HRManagerRejected = [];
        $HRManagerPendings = InterviewSummaryReport::where([['action_by_hr_manager','pending'],['hr_manager_id',$this->id],])->latest()->get();
        $HRManagerApproved = InterviewSummaryReport::where([['action_by_hr_manager','approved'],['hr_manager_id',$this->id],])->latest()->get();
        $HRManagerRejected = InterviewSummaryReport::where([['action_by_hr_manager','rejected'],['hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadPendings = InterviewSummaryReport::where([['action_by_hr_manager','approved'],['action_by_division_head','pending'],
            ['hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadApproved = InterviewSummaryReport::where([['action_by_hr_manager','approved'],['action_by_division_head','approved'],
            ['hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadRejected = InterviewSummaryReport::where([['action_by_hr_manager','approved'],['action_by_division_head','rejected'],
            ['hr_manager_id',$this->id],])->latest()->get();
        if(count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0 OR count($divisionHeadPendings) > 0 OR 
        count($divisionHeadApproved) > 0 OR count($divisionHeadRejected) > 0 ) {
            $interviewSummaryReportApproval['can'] = true;
            $interviewSummaryReportApproval['count'] = count($HRManagerPendings) + count($divisionHeadPendings);
        }
        return $interviewSummaryReportApproval;
    }
    public function getCandidateDocsVarifyAttribute() {
        $pendingdocsUploaded = 0;
        $pendingdocsUploaded = InterviewSummaryReport::where('status','approved')->where('seleced_status','pending')
        ->whereHas('candidateDetails', function($q){
            $q->where('documents_verified_at', NULL);
        })->latest()->count();
        return $pendingdocsUploaded;
    }
    public function getVerifyOfferLettersAttribute() {
        $verifyOffers = 0;
        $verifyOffers = InterviewSummaryReport::where([
            ['status','approved'],
            ['seleced_status','pending'],
            ['offer_letter_send_at','!=',NULL],
            ['offer_letter_verified_at',NULL],
        ])
            // where('status','approved')->where('seleced_status','pending')->where('offer_letter_send_at','!=',NULL)
        ->whereHas('candidateDetails', function($q){
            $q->where('documents_verified_at','!=', NULL);
        })->latest()->count();
        return $verifyOffers;
    }
    public function getCandidatePersonalInformationVarifyAttribute() {
        $pendingPersonalInfo = 0;
        $pendingPersonalInfo = InterviewSummaryReport::where('status','approved')->where('seleced_status','pending')
        ->whereHas('candidateDetails', function($q){
            $q->where('documents_verified_at','!=',NULL)->where('personal_information_verified_at',NULL)->where('personal_information_created_at','!=',NULL);
        })->latest()->count();
        return $pendingPersonalInfo;
    }
    public function getJoiningReportApprovalAttribute() {
        $authId = $this->id;
        $joiningReportApproval['can'] = false;
        $joiningReportApproval['count'] = 0;
        $preparedByPendings = $preparedByApproved = $preparedByRejected = $employeePendings = $employeeApproved = $employeeRejected = $HRManagerPendings 
        = $HRManagerApproved = $HRManagerRejected = $ReportingManagerPendings = $ReportingManagerApproved = $ReportingManagerRejected = [];
        $preparedByPendings = JoiningReport::where([['action_by_prepared_by','pending'],['prepared_by_id',$this->id],])->latest()->get();
        $preparedByApproved = JoiningReport::where([['action_by_prepared_by','approved'],['prepared_by_id',$this->id],])->latest()->get();
        $preparedByRejected = JoiningReport::where([['action_by_prepared_by','rejected'],['prepared_by_id',$this->id],])->latest()->get();
        $employeePendings = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','pending']])->whereHas('employee' , function($q) use($authId){
            $q->where('user_id', $authId);
        })->latest()->get();
        $employeeApproved = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved']])->whereHas('employee' , function($q) use($authId){
            $q->where('user_id', $authId);
        })->latest()->get();
        $employeeRejected = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','rejected']])->whereHas('employee' , function($q) use($authId){
            $q->where('user_id', $authId);
        })->latest()->get();
        $HRManagerPendings = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved'],['action_by_hr_manager','pending'],
            ['hr_manager_id',$this->id],])->latest()->get();
        $HRManagerApproved = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved'],['action_by_hr_manager','approved'],
            ['hr_manager_id',$this->id],])->latest()->get();
        $HRManagerRejected = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved'],['action_by_hr_manager','rejected'],
            ['hr_manager_id',$this->id],])->latest()->get();  
        $ReportingManagerPendings = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved'],['action_by_hr_manager','approved'],
            ['action_by_department_head','pending'],['department_head_id',$this->id],])->latest()->get();
        $ReportingManagerApproved = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved'],['action_by_hr_manager','approved'],
            ['action_by_department_head','approved'],['department_head_id',$this->id],])->latest()->get();
        $ReportingManagerRejected = JoiningReport::where([['action_by_prepared_by','approved'],['action_by_employee','approved'],['action_by_hr_manager','approved'],
            ['action_by_department_head','rejected'],['department_head_id',$this->id],])->latest()->get();  
        if(count($preparedByPendings) > 0 OR count($preparedByApproved) > 0 OR count($preparedByRejected) > 0 OR count($employeePendings) > 0 OR 
        count($employeeApproved) > 0 OR count($employeeRejected) > 0 OR count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0
        OR count($ReportingManagerPendings) > 0 OR count($ReportingManagerApproved) > 0 OR count($ReportingManagerRejected) > 0) {
            $joiningReportApproval['can'] = true;
            $joiningReportApproval['count'] = count($preparedByPendings) + count($employeePendings) + count($HRManagerPendings) + count($ReportingManagerPendings);
        }
        return $joiningReportApproval;
    }
    public function getPassportSubmitRequestApprovalAttribute() {
        $authId = $this->id;
        $passportSubmitRequestApproval['can'] = false;
        $passportSubmitRequestApproval['count'] = 0;
        // employee -------> Reporting Manager  ------------>Division Head--------->HR Manager
        $employeePendings = $employeeApproved = $employeeRejected = 
        $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = 
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = 
        $hrPendings = $hrApproved = $hrRejected = [];

        $employeePendings = PassportRequest::where([['submit_action_by_employee','pending'],['employee_id',$this->id],])->latest()->get();
        $employeeApproved = PassportRequest::where([['submit_action_by_employee','approved'],['employee_id',$this->id],])->latest()->get();
        $employeeRejected = PassportRequest::where([['submit_action_by_employee','rejected'],['employee_id',$this->id],])->latest()->get();
        $reportingManagerPendings = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_department_head','pending']])->latest()->get();
        $reportingManagerApproved = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_department_head','approved']])->latest()->get();
        $reportingManagerRejected = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_department_head','rejected']])->latest()->get();
        $divisionHeadPendings = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_department_head','approved'],['submit_action_by_division_head','pending'],
            ['submit_hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadApproved = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_department_head','approved'],['submit_action_by_division_head','approved'],
            ['submit_hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadRejected = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_department_head','approved'],['submit_action_by_division_head','rejected'],
            ['submit_hr_manager_id',$this->id],])->latest()->get();  
        $hrPendings = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_division_head','approved'],
            ['submit_action_by_hr_manager','pending'],['submit_hr_manager_id',$this->id],])->latest()->get();
        $hrApproved = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_division_head','approved'],
            ['submit_action_by_hr_manager','approved'],['submit_hr_manager_id',$this->id],])->latest()->get();
        $hrRejected = PassportRequest::where([['submit_action_by_employee','approved'],['submit_action_by_division_head','approved'],
            ['submit_action_by_hr_manager','rejected'],['submit_hr_manager_id',$this->id],])->latest()->get();  
        if(count($employeePendings) > 0 OR count($employeeApproved) > 0 OR count($employeeRejected) > 0 OR count($reportingManagerPendings) > 0 OR 
        count($reportingManagerApproved) > 0 OR count($reportingManagerRejected) > 0 OR count($divisionHeadPendings) > 0 OR count($divisionHeadApproved) > 0 OR count($divisionHeadRejected) > 0
        OR count($hrPendings) > 0 OR count($hrApproved) > 0 OR count($hrRejected) > 0) {
            $passportSubmitRequestApproval['can'] = true;
            $passportSubmitRequestApproval['count'] = count($employeePendings) + count($reportingManagerPendings) + count($divisionHeadPendings) + count($hrPendings);
        }
        return $passportSubmitRequestApproval;
    }
    public function getPassportReleaseRequestApprovalAttribute() {
        $authId = $this->id;
        $passportReleaseRequestApproval['can'] = false;
        $passportReleaseRequestApproval['count'] = 0;
        // employee -------> Reporting Manager  ------------>Division Head--------->HR Manager
        $employeePendings = $employeeApproved = $employeeRejected = 
        $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = 
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected = 
        $hrPendings = $hrApproved = $hrRejected = [];

        $employeePendings = PassportRelease::where([['release_action_by_employee','pending'],['employee_id',$this->id],])->latest()->get();
        $employeeApproved = PassportRelease::where([['release_action_by_employee','approved'],['employee_id',$this->id],])->latest()->get();
        $employeeRejected = PassportRelease::where([['release_action_by_employee','rejected'],['employee_id',$this->id],])->latest()->get();
        $reportingManagerPendings = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_department_head','pending']])->latest()->get();
        $reportingManagerApproved = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_department_head','approved']])->latest()->get();
        $reportingManagerRejected = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_department_head','rejected']])->latest()->get();
        $divisionHeadPendings = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_department_head','approved'],['release_action_by_division_head','pending'],
            ['release_hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadApproved = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_department_head','approved'],['release_action_by_division_head','approved'],
            ['release_hr_manager_id',$this->id],])->latest()->get();
        $divisionHeadRejected = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_department_head','approved'],['release_action_by_division_head','rejected'],
            ['release_hr_manager_id',$this->id],])->latest()->get();  
        $hrPendings = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_division_head','approved'],
            ['release_action_by_hr_manager','pending'],['release_hr_manager_id',$this->id],])->latest()->get();
        $hrApproved = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_division_head','approved'],
            ['release_action_by_hr_manager','approved'],['release_hr_manager_id',$this->id],])->latest()->get();
        $hrRejected = PassportRelease::where([['release_action_by_employee','approved'],['release_action_by_division_head','approved'],
            ['release_action_by_hr_manager','rejected'],['release_hr_manager_id',$this->id],])->latest()->get();  
        if(count($employeePendings) > 0 OR count($employeeApproved) > 0 OR count($employeeRejected) > 0 OR count($employeePendings) > 0 OR 
        count($employeeApproved) > 0 OR count($employeeRejected) > 0 OR count($divisionHeadPendings) > 0 OR count($divisionHeadApproved) > 0 OR count($divisionHeadRejected) > 0
        OR count($hrPendings) > 0 OR count($hrApproved) > 0 OR count($hrRejected) > 0) {
            $passportReleaseRequestApproval['can'] = true;
            $passportReleaseRequestApproval['count'] = count($employeePendings) + count($reportingManagerPendings) + count($divisionHeadPendings) + count($hrPendings);
        }
        return $passportReleaseRequestApproval;
    }
    public function getLiabilityRequestApprovalAttribute() {
        $authId = $this->id;
        $liabilityRequestApproval['can'] = false;
        $liabilityRequestApproval['count'] = 0;
         // employee -------> Reporting Manager  ----Finance Manager--------->HR Manager-------->Division Head
        $employeePendings = $employeeApproved = $employeeRejected = 
        $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = 
        $financeManagerPendings = $financeManagerApproved = $financeManagerRejected =  $hrPendings = $hrApproved = $hrRejected =
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected =  [];

        $employeePendings = Liability::where([['action_by_employee','pending'],['employee_id',$this->id],])->latest()->get();
        $employeeApproved = Liability::where([['action_by_employee','approved'],['employee_id',$this->id],])->latest()->get();
        $employeeRejected = Liability::where([['action_by_employee','rejected'],['employee_id',$this->id],])->latest()->get();
        $reportingManagerPendings = Liability::where([['action_by_employee','approved'],['action_by_department_head','pending'],['department_head_id',$this->id],])->latest()->get();
        $reportingManagerApproved = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['department_head_id',$this->id],])->latest()->get();
        $reportingManagerRejected = Liability::where([['action_by_employee','approved'],['action_by_department_head','rejected'],['department_head_id',$this->id],])->latest()->get();
        $financeManagerPendings = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','pending'],
            ['finance_manager_id',$this->id],])->latest()->get();
        $financeManagerApproved = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],
            ['finance_manager_id',$this->id],])->latest()->get();
        $financeManagerRejected = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','rejected'],
            ['finance_manager_id',$this->id],])->latest()->get();  
        $hrPendings = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],
            ['action_by_hr_manager','pending'],['hr_manager_id',$this->id],])->latest()->get();
        $hrApproved = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],
            ['action_by_hr_manager','approved'],['hr_manager_id',$this->id],])->latest()->get();
        $hrRejected = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],
            ['action_by_hr_manager','rejected'],['hr_manager_id',$this->id],])->latest()->get();  
        $divisionHeadPendings = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],['action_by_hr_manager','approved'],
            ['action_by_division_head','pending'],['division_head_id',$this->id],])->latest()->get();
        $divisionHeadApproved = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],['action_by_hr_manager','approved'],
            ['action_by_division_head','approved'],['division_head_id',$this->id],])->latest()->get();
        $divisionHeadRejected = Liability::where([['action_by_employee','approved'],['action_by_department_head','approved'],['action_by_finance_manager','approved'],['action_by_hr_manager','approved'],
            ['action_by_division_head','rejected'],['division_head_id',$this->id],])->latest()->get();  
        if(count($employeePendings) > 0 OR count($employeeApproved) > 0 OR count($employeeRejected) > 0 OR count($reportingManagerPendings) > 0 OR 
        count($reportingManagerApproved) > 0 OR count($reportingManagerRejected) > 0 OR count($financeManagerPendings) > 0 OR count($financeManagerApproved) > 0 OR count($financeManagerRejected) > 0
        OR count($hrPendings) > 0 OR count($hrApproved) > 0 OR count($hrRejected) > 0 OR count($divisionHeadPendings) > 0 OR count($divisionHeadApproved) > 0 OR count($divisionHeadRejected) > 0) {
            $liabilityRequestApproval['can'] = true;
            $liabilityRequestApproval['count'] = count($employeePendings) + count($reportingManagerPendings) + count($financeManagerPendings) + count($hrPendings) + count($divisionHeadPendings);
        }
        return $liabilityRequestApproval;
    }
    public function getLeaveRequestApprovalAttribute() {
        $authId = $this->id;
        $leaveRequestApproval['can'] = false;
        $leaveRequestApproval['count'] = 0;
        // employee --------->HR Manager-------> Reporting Manager-------->Division Head
        $employeePendings = $employeeApproved = $employeeRejected = 
        $hrPendings = $hrApproved = $hrRejected =
        $reportingManagerPendings = $reportingManagerApproved = $reportingManagerRejected = 
        $divisionHeadPendings = $divisionHeadApproved = $divisionHeadRejected =  [];
        $employeePendings = Leave::where([['action_by_employee','pending'],['employee_id',$this->id],])->latest()->get();
        $employeeApproved = Leave::where([['action_by_employee','approved'],['employee_id',$this->id],])->latest()->get();
        $employeeRejected = Leave::where([['action_by_employee','rejected'],['employee_id',$this->id],])->latest()->get();
        $hrPendings = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','pending'],['hr_manager_id',$this->id],])->latest()->get();
        $hrApproved = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['hr_manager_id',$this->id],])->latest()->get();
        $hrRejected = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','rejected'],['hr_manager_id',$this->id],])->latest()->get();
        $reportingManagerPendings = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['action_by_department_head','pending'],
            ['department_head_id',$this->id],])->latest()->get();
        $reportingManagerApproved = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['action_by_department_head','approved'],
            ['department_head_id',$this->id],])->latest()->get();
        $reportingManagerRejected = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['action_by_department_head','rejected'],
            ['department_head_id',$this->id],])->latest()->get();  
        $divisionHeadPendings = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['action_by_department_head','approved'],
            ['action_by_division_head','pending'],['division_head_id',$this->id],])->latest()->get();
        $divisionHeadApproved = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['action_by_department_head','approved'],
            ['action_by_division_head','approved'],['division_head_id',$this->id],])->latest()->get();
        $divisionHeadRejected = Leave::where([['action_by_employee','approved'],['action_by_hr_manager','approved'],['action_by_department_head','approved'],
            ['action_by_division_head','rejected'],['division_head_id',$this->id],])->latest()->get();   
        if(count($employeePendings) > 0 OR count($employeeApproved) > 0 OR count($employeeRejected) > 0 OR count($reportingManagerPendings) > 0 OR 
        count($reportingManagerApproved) > 0 OR count($reportingManagerRejected) > 0
        OR count($hrPendings) > 0 OR count($hrApproved) > 0 OR count($hrRejected) > 0 OR count($divisionHeadPendings) > 0 OR count($divisionHeadApproved) > 0 OR count($divisionHeadRejected) > 0) {
            $leaveRequestApproval['can'] = true;
            $leaveRequestApproval['count'] = count($employeePendings) + count($reportingManagerPendings) + count($hrPendings) + count($divisionHeadPendings);
        }
        return $leaveRequestApproval;
    }
    public function getAdvanceOrLoanBalanceAttribute() {
        $advanceOrLoanBalance = 0;
        $advanceOrLoanBalance = Liability::where([
            ['employee_id',$this->id],['status','approved'],
        ])->sum('total_amount');
        return $advanceOrLoanBalance;
    }
    public function getSelectedRoleAttribute() {
        return $this->attributes['selected_role'] ?? $this->roles()->first()->name;
    }
    public function hasPermissionForSelectedRole($permissionName) {
        $selectedRole = $this->selected_role;
        if(is_array($permissionName)) {
            if(count($permissionName) > 0) {
                return DB::table('role_has_permissions')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', $selectedRole)
                    ->whereIn('permissions.name', $permissionName)
                    ->exists();
            }
        }
        else {
            if ($selectedRole) {
                return DB::table('role_has_permissions')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('role_has_permissions.role_id', $selectedRole)
                    ->where('permissions.name', $permissionName)
                    ->exists();
            }
        }
        return false;
    }
    public function empProfile() {
        return $this->hasOne(EmployeeProfile::class, 'user_id')->where('type','employee');
    }
    public function approvedLeaves() {
        return $this->hasMany(Leave::class, 'employee_id')->where([
            ['status','approved'],
            ['joining_reports_id',NULL],
        ]);
    }
}
