@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    .select2-container {
        width: 100% !important;
    }

    .designation-radio-button {
        margin-left: 15px;
    }

    .designation-radio-main-div {
        margin-top: 12px !important;
    }

    .form-label[for="basicpill-firstname-input"] {
        margin-top: 12px;
    }

    .btn.btn-success.btncenter {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn.btn-success.btncenter:hover {
        background-color: #0000ff;
        font-size: 17px;
        border-radius: 10px;
    }

    /* Media Query for small screens */
    @media (max-width: 767px) {
        .col-lg-12.col-md-12 col-sm-12 {
            text-align: center;
        }
    }

    .error {
        color: #FF0000;
    }

    .iti {
        width: 100%;
    }

    label {
        display: inline-block;
        margin-right: 10px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

    .error-text {
        color: #FF0000;
    }
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">@if($currentQuestionnaire->id == '')Create New @else Edit @endif Questionnaire</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="col-lg-12">
        <div id="flashMessage"></div>
    </div>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <!-- {!! Form::open(array('route' => 'calls.store','method'=>'POST', 'id' => 'calls')) !!} -->
    @include('hrm.hiring.hiring_request.details')
    <div class="row">
        <p><span style="float:right;" class="error">* Required Field</span></p>
    </div>

    <form id="employeeQuestionnaireForm" name="employeeQuestionnaireForm" enctype="multipart/form-data" method="POST" action="">

        <div class="row">
            <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                <div class="row ">
                    <div class="col-lg-12 col-md-12 col-sm-12 ">

                        <label for="designation_type" class="form-label"><span class="error">* </span>Designation Type:</label>
                        <div class="designation-radio-button">
                            <label>
                                <input type="radio" name="designation_type" id="prior_designation" value="prior_designation"> Prior Designation
                            </label>
                            <label>
                                <input type="radio" name="designation_type" id="current_designation" value="current_designation"> Current Designation
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                <div class="row ">
                    <div class="col-lg-12  col-md-12 col-sm-12 ">


                        <label for="hiring_time" class="form-label"><span class="error">* </span>Hiring Time:</label>
                        <div class="designation-radio-button">
                            <label>
                                <input type="radio" name="hiring_time" id="immediate" value="immediate"> Immediate
                            </label>
                            <label>
                                <input type="radio" name="hiring_time" id="one_month" value="one_month"> 1 - Month
                            </label>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row">
                <div class=" col-lg-4 col-md-6 col-sm-6">

                    <label for="designation_id" class="form-label"><span class="error">* </span>Designation Name</label>
                    <select name="designation_id" id="requested_job_title" class="form-control widthinput" multiple="true" autofocus>
                        @foreach($masterDesignations as $masterDesignation)
                        <option value="{{$masterDesignation->id}}">{{$masterDesignation->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-6">
                    <a id="createNewJobTitleButton" data-toggle="popover" data-trigger="hover" title="Create New Job Title" data-placement="top" style="margin-top:38px;" class="btn btn-sm btn-info modal-button" data-modal-id="createNewJobPosition"><i class="fa fa-plus" aria-hidden="true"></i> Create New Job Title</a>
                </div>
                <!-- New Designation div shown on the right side -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <!-- when the user chooses other, show this other new designation div  -->
                    <div class="otherDesignationInputContainer" id="otherDesignationInputContainer" style="display: none">

                        <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Other:</label>
                        <input type="text" placeholder="Other" name="otherDesignation" class="form-control" id="otherDesignationInput">
                    </div>
                </div>
            </div>


            <div class=" col-lg-4 col-md-6 col-sm-6   ">

                <label for="reporting_structure" class="form-label"><span class="error">* </span>Reporting To</label>
                <select name="reporting_structure" id="reporting_structure" class="form-control widthinput" autofocus>
                    <option value=""></option>
                    <option value="management">Management</option>
                    <option value="team_lead">Team Lead / Manager</option>
                </select>
            </div>
            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="location_id" class="form-label"><span class="error">* </span>Work Location</label>
                <select name="location_id" id="location_id" class="form-control widthinput" multiple="true" autofocus>
                    @foreach($masterOfficeLocations as $masterOfficeLocation)
                    <option value="{{$masterOfficeLocation->id}}">{{$masterOfficeLocation->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="number_of_openings" class="form-label"><span class="error">* </span>Number of Hirings : </label>
                <input type="number" placeholder="Number of Hirings" name="number_of_openings" class="form-control" id="number_of_openings">
            </div>


            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="no_of_years_of_experience_in_specific_job_role" class="form-label"><span class="error">* </span>Years of Experience : </label>
                <input type="number" placeholder="No. of years" name="no_of_years_of_experience_in_specific_job_role" class="form-control" id="no_of_years_of_experience_in_specific_job_role">
            </div>


            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="work_time" class="form-label"><span class="error">* </span>Working Hours:</label>
                <div class="input-group">
                    <input type="time" placeholder="From" name="work_time_start" class="form-control" id="work_time_start">
                    <span class="input-group-text">to</span>
                    <input type="time" placeholder="Till" name="work_time_end" class="form-control" id="work_time_end">
                </div>
            </div>



            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Any Specific Company Experience : </label>
                <input type="number" placeholder="Company Experience" name="location" class="form-control" id="locationInput">
            </div>

        </div>

        <div class="row">
            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="industry_experience_id" class="form-label"><span class="error">* </span>Any specific industry experience</label>
                <select name="industry_experience_id" id="industry_experience_id" class="form-control widthinput" multiple="true" onchange="showIndustryExpOtherDiv('otherSpecificIndustryExpInputContainer', this)" autofocus>
                    @foreach($masterSpecificIndustryExperiences as $masterSpecificIndustryExperience)
                    <option value="{{$masterSpecificIndustryExperience->id}}">{{$masterSpecificIndustryExperience->name}}</option>
                    @endforeach
                </select>

            </div>

            <!-- Specifiy div shown on the right side -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <!-- when the user chooses other, show this Specify div  -->
                <div class="otherSpecificIndustryExpInputContainer" id="otherSpecificIndustryExpInputContainer" style="display: none">

                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Specify Other:</label>
                    <input type="text" placeholder="Other" name="otherSpecificIndustryExp" class="form-control" id="otherSpecificIndustryExp">
                </div>
            </div>
        </div>


        <div class="row">
            <div class=" col-lg-4 col-md-6 col-sm-6 ">

                <label for="education" class="form-label"><span class="error">* </span>Education</label>
                <select name="education" id="education" class="form-control widthinput" onchange="showDiv('otherEducationInputContainer', this)" autofocus>
                    <option value=""></option>
                    <option value="high_school">High School</option>
                    <option value="bachelors">Bachelors</option>
                    <option value="pg_in_same_specialisation_or_related_to_department">PG in the same specailisation or related to department</option>
                    <option value="0">Other</option>
                </select>
            </div>

            <!-- Other div shown on the right side -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <!-- when the user chooses other, show this other other div  -->
                <div class="otherEducationInputContainer" id="otherEducationInputContainer" style="display: none">

                    <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Other:</label>
                    <input type="text" placeholder="Other" name="otherEducation" class="form-control" id="otherEducationInput">
                </div>
            </div>
        </div>


        <br />

        <div class="maindd">
            <div id="row-container">
                <div class="row">

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="salary_range_in_aed" class="form-label"><span class="error">* </span>Salary Range:</label>
                        <div class="input-group">
                            <input type="number" placeholder="Min Salary" name="salary_range_start_in_aed" class="form-control" id="salary_range_start_in_aed">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="Max Salary" name="salary_range_end_in_aed" class="form-control" id="salary_range_end_in_aed">
                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="visa_type" class="form-label"><span class="error">* </span>Visa Type</label>
                        <select name="visa_type" id="visa_type" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterVisaTypes as $masterVisaType)
                            <option value="{{$masterVisaType->id}}">{{$masterVisaType->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="nationality" class="form-label"><span class="error">* </span>Nationality</label>
                        <select name="nationality" id="nationality" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterNationality as $Country)
                            <option value="{{$Country->id}}">{{$Country->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="min_age" class="form-label"><span class="error">* </span>Age:</label>
                        <div class="input-group">
                            <input type="number" placeholder="From" name="min_age" class="form-control" id="min_age">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="End" name="max_age" class="form-control" id="max_age">
                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="language_id" class="form-label"><span class="error">* </span>Additional Language(s):</label>
                        <select name="language_id" id="language_id" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterLanguages as $Language)
                            <option value="{{$Language->id}}">{{$Language->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">


                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="required_to_travel_for_work_purpose" class="form-label"><span class="error">* </span>Did he require to travel for work purpose?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="required_to_travel_for_work_purpose" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="required_to_travel_for_work_purpose" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="requires_multiple_industry_experience" class="form-label"><span class="error">* </span>Do candidates require multiple industry experience?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="requires_multiple_industry_experience" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="requires_multiple_industry_experience" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="team_handling_experience_required" class="form-label"><span class="error">* </span>Team handling experience is required?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="team_handling_experience_required" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="team_handling_experience_required" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12   designation-radio-main-div">


                                <label for="required_to_work_on_trial" class="form-label"><span class="error">* </span>Is shortlisted candidate require to work on trial ?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="required_to_work_on_trial" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="required_to_work_on_trial" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                            <!-- if yes, add input  button to enter number of days -->
                            <div class="numberOfDaysInputContainer" style="display: none">

                                <label for="number_of_trial_days" class="form-label"><span class="error">* </span>Enter Number of days:</label>
                                <input type="number" placeholder="no. of days" name="number_of_trial_days" class="form-control" id="number_of_trial_days">
                            </div>


                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 designation-radio-main-div">
                        <div class="row">
                            <div class="col-lg-12">

                                <label for="commission_involved_in_salary" class="form-label"><span class="error">* </span>Is commission involved along with the salary?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="commission_involved_in_salary" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="commission_involved_in_salary" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <!-- Dropdown Container -->
                                    <div class="chooseAmountpercentageDropDownInputContainer" style="display: none;">

                                        <label for="commission_type" class="form-label"><span class="error">* </span>Choose Amount or Percentage</label>
                                        <select name="commission_type" id="commission_type" class="form-control widthinput" onchange="showAmountPercentageInput(this)" autofocus>
                                            <option value="" disabled selected>Choose Option</option>
                                            <option value="amount">Amount</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Amount Input Container -->
                                <div class="col-lg-12 col-md-12 col-sm-12 amountpercentageDropDownInputContainer">
                                    <div class="amountInputContainer" id="amountInputContainer" style="display: none">

                                        <label for="commission_amount" class="form-label"><span class="error">* </span>Enter Amount (in AED):</label>
                                        <input type="number" placeholder="amount" name="commission_amount" class="form-control" id="commission_amount">
                                    </div>

                                    <!-- Percentage Input Container -->
                                    <div class="percentageInputContainer" id="percentageInputContainer" style="display: none">

                                        <label for="commission_percentage" class="form-label"><span class="error">* </span>Enter percentage:</label>
                                        <input type="number" placeholder="percentage" name="commission_percentage" class="form-control" id="commission_percentage">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                </div>


                <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                    <div class="row ">
                        <div class="col-lg-12   designation-radio-main-div">


                            <label for="driving_licence" class="form-label"><span class="error">* </span>Driving Lisence Required?</label>

                            <div class="designation-radio-button">
                                <label>
                                    <input type="radio" name="driving_lisence" id="yes" value="yes"> Yes
                                </label>
                                <label>
                                    <input type="radio" name="driving_lisence" id="no" value="no"> No
                                </label>
                            </div>
                        </div>

                        <!-- if yes, add radio button for: Own car, Expenses done by ? own or Company -->

                    </div>
                </div>
                <div class=" col-lg-4 col-md-6 col-sm-6 ">

                    <div class="drivingLisenceInputContainer" style="display: none">
                        <div class="row ">
                            <div class="col-lg-6 designation-radio-main-div">


                                <label for="own_car" class="form-label"><span class="error">* </span>Own Car</label>

                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="own_car" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="own_car" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6 designation-radio-main-div">


                                <label for="fuel_expenses_by" class="form-label"><span class="error">* </span>Fuels Expenses covered by?</label>

                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="fuel_expenses_by" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="fuel_expenses_by" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="interviewd_by" class="form-label"><span class="error">* </span>Interviewed By:</label>
                        <select name="interviewd_by" id="interviewd_by" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($interviewdByUsers as $User)
                            <option value="{{$User->id}}">{{$User->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class=" col-lg-4 col-md-12 col-sm-12 ">
                        <label for="mandatory_skills" class="form-label"><span class="error">* </span>Top 3 skills / mandatory work experience : </label>
                        <textarea name="mandatory_skills" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        <label for="job_opening_purpose_objective" class="form-label"><span class="error">* </span>Objectives of job purpose of job posting: </label>
                        <textarea name="job_opening_purpose_objective" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        <label for="screening_questions" class="form-label"><span class="error">* </span>Screening Questions: </label>
                        <textarea name="screening_questions" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        <label for="technical_test" class="form-label"><span class="error">* </span>Technical Questions</label>
                        <textarea name="technical_test" class="form-control" rows="3" cols="15"></textarea>
                    </div>

                    <div class=" col-lg-4 col-md-12 col-sm-12  ">
                        <label for="trial_work_job_description" class="form-label"><span class="error">* </span>Job description during trial Working</label>
                        <textarea name="trial_work_job_description" class="form-control" rows="3" cols="15"></textarea>
                    </div>
                </div>

                <div class="row ">

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        <label for="recruitment_source_id" class="form-label"><span class="error">* </span>Recruitment Source:</label>
                        <select name="recruitment_source_id" id="recruitment_source_id" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterRecuritmentSources as $MasterRecuritmentSource)
                            <option value="{{$MasterRecuritmentSource->id}}">{{$MasterRecuritmentSource->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        <label for="department_id" class="form-label"><span class="error">* </span>Division / Department:</label>
                        <select name="department_id" id="department_id" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterDepartments as $MasterDeparment)
                            <option value="{{$MasterDeparment->id}}">{{$MasterDeparment->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">
                        <label for="career_level_id" class="form-label"><span class="error">* </span>Career level:</label>
                        <select name="career_level_id" id="career_level_id" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterExperienceLevels as $MasterExperienceLevel)
                            <option value="{{$MasterExperienceLevel->id}}">{{$MasterExperienceLevel->name}} ( {{$MasterExperienceLevel->number_of_year_of_experience}} )</option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">

                                <label for="experience" class="form-label"><span class="error">* </span>Experience</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="experience" id="local" value="local"> Local
                                    </label>
                                    <label>
                                        <input type="radio" name="experience" id="international" value="international"> International
                                    </label>
                                    <label>
                                        <input type="radio" name="experience" id="home_country" value="home_country"> Home Country
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="travel_experience" class="form-label"><span class="error">* </span>Travel experience?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="travel_experience" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="travel_experience" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="current_or_past_employer_size_start" class="form-label"><span class="error">* </span>Current or Past Employer Size:</label>
                        <div class="input-group">
                            <input type="number" placeholder="From" name="current_or_past_employer_size_start" class="form-control" id="current_or_past_employer_size_start">
                            <span class="input-group-text">to</span>
                            <input type="number" placeholder="Till" name="current_or_past_employer_size_end" class="form-control" id="current_or_past_employer_size_end">
                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="trial_pay_in_aed" class="form-label"><span class="error">* </span>Trial Pay (AED): </label>
                        <input type="number" placeholder="Trial Pay in AED" name="trial_pay_in_aed" class="form-control" id="trial_pay_in_aed">
                    </div>

                </div>

                <div class="row">

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="out_of_office_visit" class="form-label"><span class="error">* </span>Out of Office Visits?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="out_of_office_visit" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="out_of_office_visit" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="remote_work" class="form-label"><span class="error">* </span>Remote Work?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="remote_work" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="remote_work" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class=" col-lg-4 col-md-6 col-sm-6  designation-radio-main-div">
                        <div class="row ">
                            <div class="col-lg-12  ">


                                <label for="international_business_trip_required" class="form-label"><span class="error">* </span>International Business trips required?</label>
                                <div class="designation-radio-button">
                                    <label>
                                        <input type="radio" name="international_business_trip_required" id="yes" value="yes"> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="international_business_trip_required" id="no" value="no"> No
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="probation_length_in_months" class="form-label"><span class="error">* </span>Probation length (months): </label>
                        <input type="number" placeholder="Probation length in months" name="probation_length_in_months" class="form-control" id="probation_length_in_months">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="probation_pay_amount_in_aed" class="form-label"><span class="error">* </span>Probation Pay (AED): </label>
                        <input type="number" placeholder="Probation Pay in AED" name="probation_pay_amount_in_aed" class="form-control" id="probation_pay_amount_in_aed">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="incentives_perks_bonus" class="form-label"><span class="error">* </span>Incentive, Perks, & Bonus: </label>
                        <input type="number" placeholder="Incentives" name="incentives_perks_bonus" class="form-control" id="incentives_perks_bonus">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="kpi" class="form-label"><span class="error">* </span>KPI: </label>
                        <input type="number" placeholder="KPI" name="kpi" class="form-control" id="kpi">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="practical_test" class="form-label"><span class="error">* </span>Practical test: </label>
                        <input type="number" placeholder="Practical test" name="practical_test" class="form-control" id="practical_test">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="trial_objectives_and_evaluation_method" class="form-label"><span class="error">* </span>Trial objectives and Evaluation method: </label>
                        <input type="number" placeholder="Trial objectives and Evaluation method" name="trial_objectives_and_evaluation_method" class="form-control" id="trial_objectives_and_evaluation_method">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="duties_and_tasks" class="form-label"><span class="error">* </span>Duties & Tasks : </label>
                        <input type="number" placeholder="Duties & Tasks" name="duties_and_tasks" class="form-control" id="duties_and_tasks">
                    </div>
                    <div class=" col-lg-4 col-md-6 col-sm-6 ">

                        <label for="next_career_path_id" class="form-label"><span class="error">* </span>Next Career path:</label>
                        <select name="next_career_path_id" id="next_career_path_id" class="form-control widthinput" multiple="true" autofocus>
                            @foreach($masterExperienceLevels as $MasterExperienceLevel)
                            <option value="{{$MasterExperienceLevel->id}}">{{$MasterExperienceLevel->name}} ( {{$MasterExperienceLevel->number_of_year_of_experience}} )</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">

            <label for="basicpill-firstname-input" class="form-label"><span class="error">* </span>Stakeholders for Job Evaluation</label>
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item">
                    <input type="checkbox" id="internal_department_evaluation" name="internal_department_evaluation">
                    <label for="internal_department_evaluation">Internal departments</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="external_vendor_evaluation" name="external_vendor_evaluation">
                    <label for="external_vendor_evaluation">External vendors</label>
                </li>

            </ul>
        </div>
    </form>
</div>
</br>
</br>
<div class="col-lg-12 col-md-12 col-sm-12">
    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
</div>
</br>
@include('hrm.hiring.hiring_request.createJobPosition')
@else
@php
redirect()->route('home')->send();
@endphp
@endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script>
 	var data = {!! json_encode($data) !!};

    $("#number_of_openings").val(data.number_of_openings);
    $("#work_time_start").val(data.work_time_start);
    $("#work_time_end").val(data.work_time_end);
    $("#salary_range_start_in_aed").val(data.salary_range_start_in_aed);
    $("#salary_range_end_in_aed").val(data.salary_range_end_in_aed);
    $("#career_level_id").val(data.experience_level);

    $(document).ready(function() {
        $('#requested_job_title').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Designation Name",
        });
        $('#location_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Work Location",
        });
        $('#industry_experience_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Industry Experience",
        });
        $('#visa_type').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Visa Type",
        });
        $('#nationality').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Your Nationality",
        });
        $('#language_id').select2({
            allowClear: true,
            placeholder: "Choose Additional Languages",
        });
        $('#interviewd_by').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose User Name",
        });
        $('#recruitment_source_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Recruitment Source",
        });
        $('#department_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Department",
        });
        $('#career_level_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Career Level",
        });
        $('#next_career_path_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder: "Choose Next Career Path",
        });
        // Show/hide amountPercentageInputContainer based on radio button selection
        $('input[name="required_to_work_on_trial"]').change(function() {
            if ($(this).val() === 'yes') {
                $('.numberOfDaysInputContainer').show();
            } else {
                $('.numberOfDaysInputContainer').hide();
            }
        });

        $('input[name="driving_lisence"]').change(function() {
            if ($(this).val() === 'yes') {
                $('.drivingLisenceInputContainer').show();
            } else {
                $('.drivingLisenceInputContainer').hide();
            }
        });

        $('input[name="commission_involved_in_salary"]').change(function() {
            if ($(this).val() === 'yes') {
                $('.chooseAmountpercentageDropDownInputContainer').show();
            } else {
                $('.chooseAmountpercentageDropDownInputContainer').hide();
            }
            if ($(this).val() === 'no') {
                $('.amountpercentageDropDownInputContainer').hide();
            } else {
                $('.amountpercentageDropDownInputContainer').show();
            }
        });

        // jQuery.validator.addMethod(
        //     "money",
        //     function(value, element) {
        //         var isValidMoney = /^\d{0,5}(\.\d{0,2})?$/.test(value);
        //         return this.optional(element) || isValidMoney;
        //     },
        //     "Please enter a valid amount "
        // );

        jQuery.validator.addMethod(
            "money",
            function(value, element) {
                var isValidMoney = /^(?:[1-9]\d{0,4}|\d)(\.\d{1,2})?$/.test(value) && value >= 0.01;
                return this.optional(element) || isValidMoney;
            },
            "Please enter a valid amount greater than or equal to 0.01"
        );

        jQuery.validator.addMethod(
            "greaterThanFirstValueValidate",
            function(value, element, param) {
                var startValue = $(param).val();
                var isValid = parseFloat(value) > parseFloat(startValue);
                return this.optional(element) || isValid;
            },
            "End value must be greater than start value"
        );


        jQuery.validator.addMethod(
            "twoDigitValuesWithZero",
            function(value, element) {
                var isValidTotalOpenings = /^\d{1,2}$/.test(value);
                return this.optional(element) || isValidTotalOpenings;
            },
            "Please enter a valid number between 0 and 99"
        );

        jQuery.validator.addMethod(
            "twoDigitValues",
            function(value, element) {
                var isValidTotalOpenings = /^(?:[1-9]|[1-9][0-9])$/.test(value);
                return this.optional(element) && isValidTotalOpenings;
            },
            "Please enter a valid number between 1 and 99"
        );

        jQuery.validator.addMethod(
            "threeDigitValuesWithZero",
            function(value, element) {
                var isValidTotalOpenings = /^\d{1,3}$/.test(value);
                return this.optional(element) || isValidTotalOpenings;
            },
            "Please enter a valid number between 0 and 999"
        );

        jQuery.validator.addMethod(
            "threeDigitValues",
            function(value, element) {
                var isValidTotalOpenings = /^(?:[1-9]|[1-9]\d{1,2})$/.test(value);
                return this.optional(element) && isValidTotalOpenings;
            },
            "Please enter a valid number between 1 and 999"
        );
        jQuery.validator.addMethod(
            "monthValidate",
            function(value, element) {
                var isValidMonth = /^(0?[1-9]|1[0-2])$/.test(value);
                return this.optional(element) || isValidMonth;
            },
            "Please enter a valid month between 1 and 12"
        );

        jQuery.validator.addMethod(
            "commissionPercentageCalculation",
            function(value, element) {
                var isValidTotalOpenings = /^(?:\d{1,2}(\.\d{1,2})?)$/.test(value) && parseFloat(value) >= 0;
                return this.optional(element) || isValidTotalOpenings;
            },
            "Please enter a valid number"
        );


        $('#employeeQuestionnaireForm').validate({
            rules: {
                designation_type: {
                    required: true,
                },
                hiring_time: {
                    required: true,
                },
                designation_id: {
                    required: true,
                },
                reporting_structure: {
                    required: true,
                },
                location_id: {
                    required: true,
                },
                number_of_openings: {
                    required: true,
                    twoDigitValues: true,
                },
                no_of_years_of_experience_in_specific_job_role: {
                    required: true,
                    twoDigitValuesWithZero: true,
                },
                work_time_start: {
                    required: true,
                },
                work_time_end: {
                    required: true,
                },
                specific_company_experience: {
                    required: true,
                },
                industry_experience_id: {
                    required: true,
                },
                education: {
                    required: true,
                },
                salary_range_start_in_aed: {
                    required: true,
                    money: true,
                },
                salary_range_end_in_aed: {
                    required: true,
                    money: true,
                    greaterThanFirstValueValidate: "#salary_range_start_in_aed",
                },
                visa_type: {
                    required: true,
                },
                nationality: {
                    required: true,
                },
                min_age: {
                    required: true,
                    twoDigitValues: true,
                },
                max_age: {
                    required: true,
                    twoDigitValues: true,
                    greaterThanFirstValueValidate: "#min_age",
                },
                language_id: {
                    required: true,
                },
                required_to_travel_for_work_purpose: {
                    required: true,
                },
                requires_multiple_industry_experience: {
                    required: true,
                },
                team_handling_experience_required: {
                    required: true,
                },
                required_to_work_on_trial: {
                    required: true,
                },
                number_of_trial_days: {
                    required: function(element) {
                        return $('input[name="required_to_work_on_trial"]:checked').val() === 'yes';
                    },
                    threeDigitValues: true,
                },
                commission_involved_in_salary: {
                    required: true,
                },

                commission_type: {
                    required: function(element) {
                        return $('input[name="commission_involved_in_salary"]:checked').val() === 'yes';
                    },
                },
                commission_amount: {
                    required: {
                        depends: function(element) {
                            return $('#commission_type').val() === 'amount';
                        }
                    },
                    money: true,
                },
                commission_percentage: {
                    required: {
                        depends: function(element) {
                            return $('#commission_type').val() === 'percentage';
                        }
                    },
                    commissionPercentageCalculation: true,
                },
                driving_licence: {
                    required: true,
                },
                own_car: {
                    required: function(element) {
                        return $('input[name="driving_licence"]:checked').val() === 'yes';
                    },
                },
                fuel_expenses_by: {
                    required: function(element) {
                        return $('input[name="driving_licence"]:checked').val() === 'yes';
                    },
                },
                interviewd_by: {
                    required: true,
                },
                mandatory_skills: {
                    required: true,
                },
                job_opening_purpose_objective: {
                    required: true,
                },
                screening_questions: {
                    required: true,
                },
                technical_test: {
                    required: true,
                },
                trial_work_job_description: {
                    required: true,
                },
                recruitment_source_id: {
                    required: true,
                },
                department_id: {
                    required: true,
                },
                career_level_id: {
                    required: true,
                },
                experience: {
                    required: true,
                },
                travel_experience: {
                    required: true,
                },
                current_or_past_employer_size_start: {
                    required: true,
                    threeDigitValuesWithZero: true,
                },
                current_or_past_employer_size_end: {
                    required: true,
                    threeDigitValues: true,
                    greaterThanFirstValueValidate: "#current_or_past_employer_size_start",

                },
                trial_pay_in_aed: {
                    required: true,
                    money: true,
                },
                out_of_office_visit: {
                    required: true,
                },
                remote_work: {
                    required: true,
                },
                international_business_trip_required: {
                    required: true,
                },
                probation_length_in_months: {
                    required: true,
                    monthValidate: true,
                },
                probation_pay_amount_in_aed: {
                    required: true,
                    money: true,
                },
                incentives_perks_bonus: {
                    required: true,
                },
                kpi: {
                    required: true,
                },
                practical_test: {
                    required: true,
                },
                trial_objectives_and_evaluation_method: {
                    required: true,
                },
                duties_and_tasks: {
                    required: true,
                },
                next_career_path_id: {
                    required: true,
                },
                practical_test: {
                    required: true,
                },
                trial_objectives_and_evaluation_method: {
                    required: true,
                },
            },
        });

    });
</script>

<script>
    function showDiv(divId, element) {
        document.getElementById(divId).style.display = element.value == 0 ? 'block' : 'none';
    }

    function showIndustryExpOtherDiv(divId, element) {
        document.getElementById(divId).style.display = element.value == 5 ? 'block' : 'none';
    }

    // function showAmountPercentageInput(element) {
    //     var selectedValue = element.value;
    //     document.getElementById('amountInputContainer').style.display = selectedValue == '1' ? 'block' : 'none';
    //     document.getElementById('percentageInputContainer').style.display = selectedValue == '2' ? 'block' : 'none';
    // }

    function showAmountPercentageInput(element) {
        var selectedValue = element.value;
        document.getElementById('amountInputContainer').style.display = selectedValue == 'amount' ? 'block' : 'none';
        document.getElementById('percentageInputContainer').style.display = selectedValue == 'percentage' ? 'block' : 'none';
    }
</script>
@endpush