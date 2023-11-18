<?php

namespace App\Http\Controllers\HRM\Hiring;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HRM\Hiring\EmployeeHiringRequest;
use App\Models\Masters\MasterJobPosition;
use App\Models\Masters\MasterOfficeLocation;
use App\Models\Masters\MasterVisaType;
use App\Models\Masters\MasterRecuritmentSource;
use App\Models\Masters\MasterDeparment;
use App\Models\Masters\MasterExperienceLevel;
use App\Models\Masters\MasterSpecificIndustryExperience;
use App\Models\HRM\Hiring\EmployeeHiringQuestionnaire;
use App\Models\HRM\Hiring\QuestionnaireLanguagePreference;
use App\Models\HRM\Hiring\EmployeeHiringRequestHistory;
use App\Models\Country;
use App\Models\Language;
use App\Models\User;
use Validator;
use DB;
use Exception;
use App\Http\Controllers\UserActivityController;
class EmployeeHiringQuestionnaireController extends Controller
{
    public function index() {
        return view('hrm.hiring.questionnaire.index');
    }
    public function create($id) {
        $data = EmployeeHiringRequest::where('id',$id)->first();
        $masterDesignations = MasterJobPosition::select('id','name')->get();
        $masterOfficeLocations = MasterOfficeLocation::where('status','active')->select('id','name','address')->get();
        $masterVisaTypes = MasterVisaType::where('status','active')->select('id','name')->get();
        $masterNationality = Country::select('id','name','nationality')->get();
        $masterLanguages = Language::select('id','name')->get();
        $interviewdByUsers = User::whereNot('id',16)->select('id','name')->get();
        $masterRecuritmentSources = MasterRecuritmentSource::select('id','name')->get();
        $masterDepartments = MasterDeparment::select('id','name')->get();
        $masterExperienceLevels = MasterExperienceLevel::select('id','name','number_of_year_of_experience')->get();
        $masterSpecificIndustryExperiences = MasterSpecificIndustryExperience::select('id','name')->get();
        return view('hrm.hiring.questionnaire.create',compact('data','masterDesignations','masterVisaTypes','masterNationality','masterLanguages','interviewdByUsers',
            'masterRecuritmentSources','masterDepartments','masterExperienceLevels','masterSpecificIndustryExperiences'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'hiring_request_id' => 'required',
            'designation_type' => 'required',
            'designation_id' => 'required',
            'no_of_years_of_experience_in_specific_job_role' => 'required',
            'reporting_structure' => 'required',
            'location_id' => 'required',
            'number_of_openings' => 'required',
            'hiring_time' => 'required',
            'work_time_start' => 'required',
            'work_time_end' => 'required',
            'education' => 'required',
            'education_certificates' => 'required',
            'certification' => 'required',
            'industry_experience_id' => 'required',
            'specific_company_experience' => 'required',
            'salary_range_start_in_aed' => 'required',
            'salary_range_end_in_aed' => 'required',
            'visa_type' => 'required',
            'nationality' => 'required',
            'min_age' => 'required',
            'max_age' => 'required',
            'required_to_travel_for_work_purpose' => 'required',
            'requires_multiple_industry_experience' => 'required',
            'team_handling_experience_required' => 'required',
            'own_car' => 'required',
            'fuel_expenses_by' => 'required',
            'required_to_work_on_trial' => 'required',
            'number_of_trial_days' => 'required',
            'commission_involved_in_salary' => 'required',
            'commission_type' => 'required',
            'commission_amount' => 'required',
            'commission_percentage' => 'required',
            'mandatory_skills' => 'required',
            'interviewd_by' => 'required',
            'job_opening_purpose_objective' => 'required',
            'screening_questions' => 'required',
            'technical_test' => 'required',
            'trial_work_job_description' => 'required',
            'internal_department_evaluation' => 'required',
            'external_vendor_evaluation' => 'required',
            'recruitment_source_id' => 'required',
            'experience' => 'required',
            'travel_experience' => 'required',
            'department_id' => 'required',
            'career_level_id' => 'required',
            'current_or_past_employer_size_start' => 'required',
            'current_or_past_employer_size_end' => 'required',
            'trial_pay_in_aed' => 'required',
            'out_of_office_visit' => 'required',
            'remote_work' => 'required',
            'international_business_trip_required' => 'required',
            'probation_length_in_months',
            'probation_pay_amount_in_aed',
            'incentives_perks_bonus',
            'kpi',
            'practical_test',
            'trial_objectives_and_evaluation_method',
            'duties_and_tasks',
            'next_career_path_id',
            'language_id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $input = $request->all();
                $input['created_by'] = $authId;
                $createRequest = EmployeeHiringQuestionnaire::create($input);
                $createLanguage['questionnaire_id'] = $createRequest->id;
                if(count($request->language_id) > 0) {
                    foreach($request->language_id as $language_id) {
                        $createLanguage['language_id'] = $language_id;
                        $languageCreated = QuestionnaireLanguagePreference::create($createLanguage);
                    }
                }
                $history['hiring_request_id'] = $request->id;
                $history['icon'] = 'icons8-questionnaire-30.png';
                $history['message'] = 'Employee hiring request questionnaire created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                $createHistory = EmployeeHiringRequestHistory::create($history);
                (new UserActivityController)->createActivity($createHistory->message);
                DB::commit();
                return redirect()->route('employee-hiring-request.index')
                                    ->with('success','New Employee Hiring Request Questionnaire Created Successfully');
            } 
            catch (\Exception $e) {
                DB::rollback();
            }
        }
    }
    public function edit() {
        return view('hrm.hiring.questionnaire.edit');
    }
}
 