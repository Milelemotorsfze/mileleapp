<?php

namespace App\Models\HRM\Hiring;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterSpecificIndustryExperience;
use App\Models\Masters\MasterVisaType;
use App\Models\Country;
use App\Models\Masters\MasterRecuritmentSource;
use App\Models\Masters\MasterDepartment;
use App\Models\Masters\MasterExperienceLevel;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeHiringQuestionnaire extends Model
{
    use HasFactory, SoftDeletes;
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
        'driving_licence',
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
    protected $appends = [
        'designation_type_name',
        'reporting_structure_name',
        'hiring_time_name',
        'education_name',
        'job_evaluation_stake_holders',
        'experience_name'
    ];
    public function getDesignationTypeNameAttribute() {
        $designationTypeName = '';
        if($this->designation_type == 'prior_designation') {
            $designationTypeName = 'Prior Designation';
        }
        else if($this->designation_type == 'current_designation') {
            $designationTypeName = 'Current Designation';
        }
        return $designationTypeName;
    }
    public function getReportingStructureNameAttribute() {
        $reportingStructureName = '';
        if($this->reporting_structure == 'management') {
            $reportingStructureName = 'Management';
        }
        else if($this->reporting_structure == 'manager') {
            $reportingStructureName = 'Manager';
        }
        else if($this->reporting_structure == 'team_lead') {
            $reportingStructureName = 'Team Lead';
        }
        return $reportingStructureName;
    }
    public function getHiringTimeNameAttribute() {
        $hiringTimeName = '';
        if($this->hiring_time == 'immediate') {
            $hiringTimeName = 'Immediate';
        }
        else if($this->hiring_time == 'one_month') {
            $hiringTimeName = '1 Month';
        }
        return $hiringTimeName;
    }
    public function getEducationNameAttribute() {
        $educationName = '';
        if($this->education == 'high_school') {
            $educationName = 'High School';
        }
        else if($this->education == 'bachelors') {
            $educationName = 'Bachelors';
        }
        else if($this->education == 'pg_in_same_specialisation_or_related_to_department') {
            $educationName = 'PG in the same specailisation or related to department';
        }
        return $educationName;
    }
    public function getJobEvaluationStakeHoldersAttribute() {
        $jobEvaluationStakeHolders = '';
        if($this->internal_department_evaluation == 'yes') {
            $jobEvaluationStakeHolders = 'Internal Departments , ';
        }
        if($this->external_vendor_evaluation == 'yes') {
            $jobEvaluationStakeHolders = $jobEvaluationStakeHolders . 'External Vendors';
        }
        return $jobEvaluationStakeHolders;
    } 
    public function getExperienceNameAttribute() {
        $experienceName = '';
        if($this->experience == 'local') {
            $experienceName = 'Local';
        }
        else if($this->experience == 'international') {
            $experienceName = 'International';
        }
        else if($this->experience == 'home_country') {
            $experienceName = 'Home Country';
        }
        return $experienceName;
    }
    public function designation() {
        return $this->hasOne(MasterJobPosition::class,'id','designation_id');
    }
    public function workLocation() {
        return $this->hasOne(MasterOfficeLocation::class,'id','location_id');
    }
    public function specificIndustryExperience() {
        return $this->hasOne(MasterSpecificIndustryExperience::class,'id','industry_experience_id');
    }
    public function visaType() {
        return $this->hasOne(MasterVisaType::class,'id','visa_type');
    }
    public function nationalities() {
        return $this->hasOne(Country::class,'id','nationality');
    }
    public function interviewedBy() {
        return $this->hasOne(User::class,'id','interviewd_by');
    }
    public function recruitmentSource() {
        return $this->hasOne(MasterRecuritmentSource::class,'id','recruitment_source_id');
    }
    public function department() {
        return $this->hasOne(MasterDepartment::class,'id','department_id');
    }
    public function carrerLevel() {
        return $this->hasOne(MasterExperienceLevel::class,'id','career_level_id');
    }
    public function nextCareerPath() {
        return $this->hasOne(MasterExperienceLevel::class,'id','next_career_path_id');
    }
    public function additionalLanguages() {
        return $this->hasMany(QuestionnaireLanguagePreference::class,'questionnaire_id','id');
    }
}
