<?php

namespace App\Models\HRM\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterDepartment;
use App\Models\HRM\Approvals\TeamLeadOrReportingManagerHandOverTo;
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
        
        'image_path',
        'language',
        'office',
        'phone',
        'visa_status',

        'resume',
        'visa',
        'emirates_id_file',
    ];
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
}
