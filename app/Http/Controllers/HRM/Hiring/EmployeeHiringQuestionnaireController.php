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
    public function createOrEdit($id) {
        $currentQuestionnaire = EmployeeHiringQuestionnaire::where('hiring_request_id',$id)->first();
        if(!$currentQuestionnaire) {
            $currentQuestionnaire = new EmployeeHiringQuestionnaire();
            $questionnaireId = 'new';
        }
        else {
            $questionnaireId = $currentQuestionnaire->id;
        }
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
        return view('hrm.hiring.questionnaire.create',compact('data','questionnaireId','currentQuestionnaire','masterDesignations','masterVisaTypes','masterNationality','masterLanguages',
        'interviewdByUsers','masterRecuritmentSources','masterDepartments','masterExperienceLevels','masterSpecificIndustryExperiences','masterOfficeLocations'));
    }
    public function storeOrUpdate(Request $request, $id) {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
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
            // 'education_certificates' => 'required',
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
            'driving_licence' => 'required',
            // 'own_car' => 'required',
            // 'fuel_expenses_by' => 'required',
            'required_to_work_on_trial' => 'required',
            // 'number_of_trial_days' => 'required',
            'commission_involved_in_salary' => 'required',
            // 'commission_type' => 'required',
            // 'commission_amount' => 'required',
            // 'commission_percentage' => 'required',
            'mandatory_skills' => 'required',
            'interviewd_by' => 'required',
            'job_opening_purpose_objective' => 'required',
            'screening_questions' => 'required',
            'technical_test' => 'required',
            'trial_work_job_description' => 'required',
            // 'internal_department_evaluation' => 'required',
            // 'external_vendor_evaluation' => 'required',
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
            // DB::beginTransaction();
            // try {
                // dd('let me know when reach here......');
                $authId = Auth::id();
                $input = $request->all();
                $update = EmployeeHiringQuestionnaire::where('hiring_request_id',$id)->first();
                if($update) {
                    $update->designation_type  = $request->designation_type ;
                    $update->designation_id  = $request->designation_id ;
                    $update->no_of_years_of_experience_in_specific_job_role  = $request->no_of_years_of_experience_in_specific_job_role ;
                    $update->reporting_structure  = $request->reporting_structure ;
                    $update->location_id  = $request->location_id ;
                    $update->number_of_openings  = $request->number_of_openings ;
                    $update->hiring_time  = $request->hiring_time ;
                    $update->work_time_start  = $request->work_time_start ;
                    $update->work_time_end  = $request->work_time_end ;
                    $update->education  = $request->education ;
                    if($request->education == 'pg_in_same_specialisation_or_related_to_department') {
                        $update->education_certificates  = $request->education_certificates ;
                    }
                    else {
                        $update->education_certificates  = NULL ;
                    }
                    $update->certification  = $request->certification ;
                    $update->industry_experience_id  = $request->industry_experience_id ;
                    $update->specific_company_experience  = $request->specific_company_experience ;
                    $update->salary_range_start_in_aed  = $request->salary_range_start_in_aed ;
                    $update->salary_range_end_in_aed  = $request->salary_range_end_in_aed ;
                    $update->visa_type  = $request->visa_type ;
                    $update->nationality  = $request->nationality ;
                    $update->min_age  = $request->min_age ;
                    $update->max_age  = $request->max_age ;
                    $update->required_to_travel_for_work_purpose  = $request->required_to_travel_for_work_purpose ;
                    $update->requires_multiple_industry_experience  = $request->requires_multiple_industry_experience ;
                    $update->team_handling_experience_required  = $request->team_handling_experience_required ;
                    $update->driving_licence = $request->driving_licence;
                    if($request->driving_licence == 'yes') {
                        $update->own_car  = $request->own_car ;
                        $update->fuel_expenses_by  = $request->fuel_expenses_by ;
                    }
                    else {
                        $update->own_car  = NULL;
                        $update->fuel_expenses_by  = NULL;
                    }
                    $update->required_to_work_on_trial = $request->required_to_work_on_trial;
                    if($request->required_to_work_on_trial == 'yes') {
                        $update->number_of_trial_days  = $request->number_of_trial_days;
                    }
                    else {
                        $update->number_of_trial_days  = NULL;
                    }
                    $update->commission_involved_in_salary = $request->commission_involved_in_salary;
                    if($request->commission_involved_in_salary == 'yes') {
                        $update->commission_type  = $request->commission_type;
                        if($request->commission_type == 'amount') {
                            $update->commission_amount  = $request->commission_amount;
                            $update->commission_percentage  = NULL;
                        }
                        else if($request->commission_type == 'percentage') {
                            $update->commission_percentage  = $request->commission_percentage;
                            $update->commission_amount = 0.00;
                        }
                    }
                    else {
                        $update->commission_type  = NULL ;
                        $update->commission_percentage  = NULL;
                        $update->commission_amount = 0.00;
                    }
                    $update->mandatory_skills  = $request->mandatory_skills ;
                    $update->interviewd_by  = $request->interviewd_by ;
                    $update->job_opening_purpose_objective = $request->job_opening_purpose_objective ;
                    $update->screening_questions  = $request->screening_questions ;
                    $update->technical_test  = $request->technical_test ;
                    $update->trial_work_job_description  = $request->trial_work_job_description ;
                    $update->internal_department_evaluation  = $request->internal_department_evaluation ;
                    $update->external_vendor_evaluation  = $request->external_vendor_evaluation ;
                    $update->recruitment_source_id  = $request->recruitment_source_id ;
                    $update->experience = $request->experience ;
                    $update->travel_experience = $request->travel_experience ;
                    $update->department_id = $request->department_id ;
                    $update->career_level_id = $request->career_level_id ;
                    $update->current_or_past_employer_size_start = $request->current_or_past_employer_size_start ;
                    $update->current_or_past_employer_size_end = $request->current_or_past_employer_size_end ;
                    $update->trial_pay_in_aed = $request->trial_pay_in_aed ;
                    $update->out_of_office_visit = $request->out_of_office_visit ;
                    $update->remote_work = $request->remote_work ;
                    $update->international_business_trip_required = $request->international_business_trip_required ;
                    $update->probation_length_in_months = $request->probation_length_in_months ;
                    $update->probation_pay_amount_in_aed = $request->probation_pay_amount_in_aed ;
                    $update->incentives_perks_bonus = $request->incentives_perks_bonus ;
                    $update->kpi = $request->kpi ;
                    $update->practical_test = $request->practical_test ;
                    $update->trial_objectives_and_evaluation_method = $request->trial_objectives_and_evaluation_method ;
                    $update->duties_and_tasks = $request->duties_and_tasks ;
                    $update->next_career_path_id = $request->next_career_path_id ;
                    $update->updated_by = $authId;
                    $update->update();
                    $oldLanguages = QuestionnaireLanguagePreference::where('questionnaire_id',$id)->get();
                    foreach($oldLanguages as $oldLanguage) {
                        $oldLanguage->delete();
                    }
                    $createLanguage['questionnaire_id'] = $id;
                    if(count($request->language_id) > 0) {
                        foreach($request->language_id as $language_id) {
                            $createLanguage['language_id'] = $language_id;
                            $languageCreated = QuestionnaireLanguagePreference::create($createLanguage);
                        }
                    }
                    $history['hiring_request_id'] = $id;
                    $history['icon'] = 'icons8-edit-30.png';
                    $history['message'] = 'Employee hiring questionnaire edited by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    (new UserActivityController)->createActivity('Employee Hiring Questionnaire Edited');
                    $successMessage = "Employee Hiring Questionnaire Updated Successfully";
                }
                else {
                    $input['created_by'] = $authId;
                    if($request->commission_involved_in_salary == 'yes') {
                        $input['commission_type']  = $request->commission_type;
                        if($request->commission_type == 'amount') {
                            $input['commission_amount']  = $request->commission_amount;
                            $input['commission_percentage']  = NULL;
                        }
                        else if($request->commission_type == 'percentage') {
                            $input['commission_percentage']  = $request->commission_percentage;
                            $input['commission_amount'] = 0.00;
                        }
                    }
                    else {
                        $input['commission_type']  = NULL ;
                        $input['commission_percentage']  = NULL;
                        $input['commission_amount'] = 0.00;
                    }
                    $input['hiring_request_id'] = $id;
                    $createRequest = EmployeeHiringQuestionnaire::create($input);
                    $createLanguage['questionnaire_id'] = $createRequest->id;
                    if(count($request->language_id) > 0) {
                        foreach($request->language_id as $language_id) {
                            $createLanguage['language_id'] = $language_id;
                            $languageCreated = QuestionnaireLanguagePreference::create($createLanguage);
                        }
                    }
                    $history['hiring_request_id'] = $id;
                    $history['icon'] = 'icons8-questionnaire-30.png';
                    $history['message'] = 'Employee hiring request questionnaire created by '.Auth::user()->name.' ( '.Auth::user()->email.' )';
                    $createHistory = EmployeeHiringRequestHistory::create($history);
                    (new UserActivityController)->createActivity($createHistory->message);
                    $successMessage = "New Employee Hiring Questionnaire Created Successfully";
                }
                // DB::commit();
                return redirect()->route('employee-hiring-request.index')
                                    ->with('success','New Employee Hiring Request Questionnaire Created Successfully');
            // } 
            // catch (\Exception $e) {
            //     DB::rollback();
            // }
        }
    }
    public function edit() {
        return view('hrm.hiring.questionnaire.edit');
    }
}
 