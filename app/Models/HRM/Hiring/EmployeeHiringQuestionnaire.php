<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHiringQuestionnaire extends Model
{
    use HasFactory;
    protected $table = "employee_hiring_questionnaires";
    protected $fillable = [
        'hiring_request_id', 
        'designation_type', 
        'designation_id', 
        'no_of_years_of_experience_in_specific_job_role', 
        'reporting_structure', 
        'location_id', 
        'number_of_openings', 
        'hiring_time', 
        'work_time_start', 
        'work_time_end', 
        'education', 
        'education_certificates', 
        'certification', 
        'industry_experience_id', 
        'specific_company_experience', 
        'salary_range_start_in_aed', 
        'salary_range_end_in_aed', 
        'visa_type', 
        'nationality', 
        'min_age', 
        'max_age', 
        'required_to_travel_for_work_purpose', 
        'requires_multiple_industry_experience', 
        'team_handling_experience_required', 
        'own_car', 
        'fuel_expenses_by', 
        'required_to_work_on_trial', 
        'number_of_trial_days', 
        'commission_involved_in_salary', 
        'commission_type', 
        'commission_amount', 
        'commission_percentage', 
        'mandatory_skills', 
        'interviewd_by', 
        'job_opening_purpose_objective', 
        'screening_questions', 
        'technical_test', 
        'trial_work_job_description', 
        'internal_department_evaluation', 
        'external_vendor_evaluation', 
        'recruitment_source_id', 
        'experience', 
        'travel_experience', 
        'department_id', 
        'career_level_id', 
        'current_or_past_employer_size_start', 
        'current_or_past_employer_size_end', 
        'trial_pay_in_aed', 
        'out_of_office_visit', 
        'remote_work', 
        'international_business_trip_required', 
        'probation_length_in_months', 
        'probation_pay_amount_in_aed', 
        'incentives_perks_bonus', 
        'kpi', 
        'practical_test', 
        'trial_objectives_and_evaluation_method', 
        'duties_and_tasks', 
        'next_career_path_id', 
        'status', 
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
