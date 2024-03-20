<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterDepartment;
use App\Models\Masters\MasterMaritalStatus;
use App\Models\Masters\MasterReligion;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;
use App\Models\HRM\Employee\EmployeeSpokenLanguage;
use App\Models\Country;
use App\Models\EmpDoc;
use App\Models\HRM\Hiring\InterviewSummaryReport;
use App\Models\HRM\Employee\JoiningReport;
class EmployeeProfile extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "emp_profile";
    protected $fillable = [
        'type',
        'user_id',
        'interview_summary_id',
        'employee_code',
        'first_name',
        'last_name',
        'designation_id',
        'department_id',
        'gender',
        'dob',
        'birthday_month',
        'age',
        'marital_status',
        'religion',
        'nationality',
        'company_number',
        'contact_number',
        'personal_email_address',
        'name_of_father',
        'name_of_mother',
        'address_uae',
        'e_c_p_name_in_uae',
        'e_c_p_mobile_number_in_uae',
        'e_c_p_relation_in_uae',
        'e_c_p_email_in_uae',
        'address_home',
        'e_c_p_name_in_h_c',
        'e_c_p_mobile_number_in_h_c',
        'e_c_p_relation_in_h_c',
        'e_c_p_email_in_h_c',
        'cec_or_person_code_number',
        'emirates_id',
        'emirates_expiry',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'passport_place_of_issue',
        'passport_status',
        'visa_type',
        'visa_number',
        'visa_issue_date',
        'visa_expiry_date',
        'reminder_date_for_visa_renewal',
        'visa_issueing_country',
        'sponsorship',
        'company_joining_date',
        'current_status',
        'status_date',
        'probation_duration_in_months',
        'probation_period_start_date',
        'probation_period_end_date',
        'employment_contract_type',
        'employment_contract_start_date',
        'employment_contract_end_date',
        'employment_contract_probation_period_in_months',
        'employment_contract_probation_end_date',
        'work_location',
        'division',
        'team_lead_or_reporting_manager',
        'division_head',
        'basic_salary',
        'other_allowances',
        'total_salary',
        'increament_effective_date',
        'increment_amount',
        'revised_basic_salary',
        'revised_other_allowance',
        'revised_total_salary',
        'insurance_policy_number',
        'insurance_card_number',
        'insurance_policy_start_date',
        'insurance_policy_end_date',
        'leaving_type',
        'leaving_reason',
        'notice_period_to_serve',
        'notice_period_duration',
        'last_working_day',
        'visa_cancellation_received_date',
        'change_status_or_exit_UAE_date',
        'insurance_cancellation_done',
        'created_by',
        'updated_by',
        'deleted_by',

        'educational_qualification',
        'year_of_completion',
        'residence_telephone_number',
        'spouse_name',
        'spouse_passport_number',
        'spouse_passport_expiry_date',
        'spouse_dob',
        'spouse_nationality',
        'personal_information_created_at',
        'personal_information_send_at',
        'personal_information_verified_at',
        'personal_information_created_by',
        'personal_information_verified_by',
        'documents_verified_at',
        'documents_verified_by',

        'image_path',
        'language',
        'office',
        'phone',
        'visa_status',

        'resume',
        'visa',
        'emirates_id_file',

        'offer_sign',
        'offer_signed_at',
        'offer_letter_hr_id',
        'offer_letter_fileName',
        
    ];
    protected $appends = [
        'candidate_joining_type',
        'candidate_trial_joining_date',
    ];
    public function getCandidateJoiningTypeAttribute() {
        $candidateJoiningType = 'any';
        $joiningReport = JoiningReport::where([
            ['joining_type','new_employee'],
            ['new_emp_joining_type','trial_period'],
            ['status','approved'],
        ])->first();
        if($joiningReport) {
            $candidateJoiningType = 'permanent';
        }
        return $candidateJoiningType;
    }
    public function getCandidateTrialJoiningDateAttribute() {
        $candidateJoiningType = '';
        $joiningReport = JoiningReport::where([
            ['joining_type','new_employee'],
            ['new_emp_joining_type','trial_period'],
            ['status','approved'],
        ])->first();
        if($joiningReport) {
            $candidateJoiningType = $joiningReport->joining_date;
        }
        return $candidateJoiningType;
    }
    public function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function teamLeadOrReportingManager() {
        return $this->hasOne(User::class,'id','team_lead_or_reporting_manager');
    }
    public function designation() {
        return $this->hasOne(MasterJobPosition::class,'id','designation_id');
    }
    public function location() {
        return $this->hasOne(MasterOfficeLocation::class,'id','work_location');
    }
    public function department() {
        return $this->hasOne(MasterDepartment::class,'id','department_id');
    }
    public function leadManagerHandover() {
        return $this->hasOne(TeamLeadOrReportingManagerHandOverTo::class,'lead_or_manager_id','team_lead_or_reporting_manager');
    }
    public function maritalStatus() {
        return $this->hasOne(MasterMaritalStatus::class,'id','marital_status');
    }
    public function religionName() {
        return $this->hasOne(MasterReligion::class,'id','religion');
    }
    public function candidateLanguages() {
        return $this->hasMany(EmployeeSpokenLanguage::class,'candidate_id','id');
    }
    public function emergencyContactUAE() {
        return $this->hasMany(UAEEmergencyContact::class,'candidate_id','id');
    }
    public function emergencyContactHomeCountry() {
        return $this->hasMany(HomeCountryEmergencyContact::class,'candidate_id','id');
    }
    public function spouseNationality() {
        return $this->hasOne(Country::class,'id','spouse_nationality');
    }
    public function candidateChildren() {
        return $this->hasMany(Children::class,'candidate_id','id');
    }
    public function candidatePassport() {
        return $this->hasMany(EmpDoc::class,'candidate_id','id')->where('document_name','passport');
    }
    public function candidateNationalId() {
        return $this->hasMany(EmpDoc::class,'candidate_id','id')->where('document_name','national_id');
    }
    public function candidateEduDocs() {
        return $this->hasMany(EmpDoc::class,'candidate_id','id')->where('document_name','educational_docs');
    }
    public function candidateProDipCerti() {
        return $this->hasMany(EmpDoc::class,'candidate_id','id')->where('document_name','professional_diploma_certificates');
    }
    public function offerLetterHr() {
        return $this->hasOne(User::class,'id','offer_letter_hr_id'); 
    }
    public function interviewSummary() {
        return $this->belongsTo(InterviewSummaryReport::class,'interview_summary_id','id'); 
    }
    public function candidateJoiningReport() {
        return $this->hasMany(JoiningReport::class,'candidate_id','id');
    }
    
}
