@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
	.designation-radio-button {
	margin-left: 15px;
	}
	.radio-button-main-div {
	margin-top: 12px !important;
	}
	.radio-error,
	.select-error,
	.other-error {
	color: red;
	}
	#employeeQuestionnaireForm .form-label {
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
$hasPermission = Auth::user()->hasPermissionForSelectedRole('create-questionnaire');
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">@if($currentQuestionnaire->id == '')Create New @else Edit @endif Questionnaire</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
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
	@if (Session::has('error'))
	<div class="alert alert-danger" >
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('error') }}
	</div>
	@endif
	@if (Session::has('success'))
	<div class="alert alert-success" id="success-alert">
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('success') }}
	</div>
	@endif
	@include('hrm.hiring.hiring_request.details')
	<div class="row">
		<p><span style="float:right;" class="error">* Required Field</span></p>
	</div>
	<form id="employeeQuestionnaireForm" name="employeeQuestionnaireForm" enctype="multipart/form-data" method="POST" action="{{route('employee-hiring-questionnaire.store-or-update',$data->id)}}">
		@csrf
		<input type="hidden" name="questionnaire_id" value="{{$currentQuestionnaire->id ?? ''}}">
		<div class="row">
			<div class=" col-lg-4 col-md-6 col-sm-6 radio-button-main-div">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 radio-div-container">
						<label for="designation_types" class="form-label"><span class="error">* </span>{{ __('Designation Type:') }}</label>
						<div class="designation-radio-button">
							<label>
							<input type="radio" name="designation_type" id="prior_designations" value="prior_designation" @if($currentQuestionnaire->designation_type == 'prior_designation') checked @endif> Prior Designation
							</label>
							<label>
							<input type="radio" name="designation_type" id="current_designations" value="current_designation" @if($currentQuestionnaire->designation_type == 'current_designation') checked @endif> Current Designation
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
				<div class="dropdown-option-div">
					<label for="designation_id" class="form-label"><span class="error">* </span>{{ __('Designation Name') }}</label>
					<select name="designation_id" id="requested_job_title" class="form-control widthinput" multiple="true" autofocus>
					@foreach($masterDesignations as $masterDesignation)
					<option value="{{$masterDesignation->id}}" {{ $data && $data->questionnaire && $data->questionnaire->designation && $masterDesignation->id == $data->questionnaire->designation->id ? 'selected' : '' }}>{{$masterDesignation->name}}</option>
					@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a id="createNewJobTitleButton" data-toggle="popover" data-trigger="hover" title="Create New Job Title" data-placement="top" style="margin-top:43px;" class="btn btn-sm btn-info modal-button" data-modal-id="createNewJobPosition"><i class="fa fa-plus" aria-hidden="true"></i> Create New Job Title</a>
			</div>
		</div>
		<div class="row ">
			<div class=" col-lg-4 col-md-6 col-sm-6 ">
				<label for="no_of_years_of_experience_in_specific_job_role" class="form-label"><span class="error">* </span>{{ __('Years of Experience :') }} </label>
				<input type="number" placeholder="No. of years" name="no_of_years_of_experience_in_specific_job_role" class="form-control" id="no_of_years_of_experience_in_specific_job_role" value="{{$data->questionnaire->no_of_years_of_experience_in_specific_job_role ?? ''}}">
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
				<div class="dropdown-option-div">
					<label for="reporting_structure" class="form-label"><span class="error">* </span>{{ __('Reporting To') }}</label>
					<select name="reporting_structure" id="reporting_structure" class="form-control widthinput" multiple="true" autofocus>
					<option value="management" {{ $data && $data->questionnaire && $data->questionnaire->reporting_structure == 'management' ? 'selected' : '' }}>Management</option>
					<option value="team_lead" {{ $data && $data->questionnaire && $data->questionnaire->reporting_structure == 'team_lead' ? 'selected' : '' }}>Team Lead / Manager</option>
					</select>
				</div>
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
				<div class="dropdown-option-div">
					<label for="location_id" class="form-label"><span class="error">* </span>{{ __('Work Location') }}</label>
					<select name="location_id" id="location_id" class="form-control widthinput" multiple="true" autofocus>
					@foreach($masterOfficeLocations as $masterOfficeLocation)
					<option value="{{$masterOfficeLocation->id}}" {{ $data && $data->questionnaire && $data->questionnaire->workLocation && $masterOfficeLocation->id == $data->questionnaire->workLocation->id ? 'selected' : '' }}>{{$masterOfficeLocation->name}}</option>
					@endforeach
					</select>
				</div>
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 ">
				<label for="number_of_openings" class="form-label"><span class="error">* </span>{{ __('Number of Hirings :') }} </label>
				<input type="number" placeholder="Number of Hirings" name="number_of_openings" class="form-control" id="number_of_openings" value="{{$data->questionnaire->number_of_openings ?? ''}}">
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 radio-button-main-div">
				<div class="row ">
					<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-div-container">
						<label for="hiring_time" class="form-label"><span class="error">* </span>{{ __('Hiring Time:') }}</label>
						<div class="designation-radio-button">
							<label>
							<input type="radio" name="hiring_time" id="immediate" value="immediate" @if($currentQuestionnaire->hiring_time == 'immediate') checked @endif> Immediate
							</label>
							<label>
							<input type="radio" name="hiring_time" id="one_month" value="one_month" @if($currentQuestionnaire->hiring_time == 'one_month') checked @endif> 1 - Month
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-8 col-sm-12">
				<label for="work_time" class="form-label"><span class="error">* </span>{{ __('Working Hours:') }}</label>
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="input-group">
							<input type="time" placeholder="From" name="work_time_start" class="form-control" id="work_time_start" value="{{$data->questionnaire->work_time_start ?? ''}}">
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-12">
						<div class="input-group">
							<input type="time" placeholder="Till" name="work_time_end" class="form-control" id="work_time_end" value="{{$data->questionnaire->work_time_end ?? ''}}">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class=" col-lg-4 col-md-6 col-sm-6 ">
				<label for="education" class="form-label"><span class="error">* </span>{{ __('Education') }}</label>
				<select name="education" id="education" class="form-control widthinput" autofocus>
					<option value="" disabled selected>Choose Option</option>
					<option value="secondary_school_or_below" {{ $data && $data->questionnaire && $data->questionnaire->education == 'secondary_school_or_below' ? 'selected' : '' }}>Secondary School Or Below</option>
					<option value="high_school" {{ $data && $data->questionnaire && $data->questionnaire->education == 'high_school' ? 'selected' : '' }}>High School</option>
					<option value="bachelors" {{ $data && $data->questionnaire && $data->questionnaire->education == 'bachelors' ? 'selected' : '' }}>Bachelors</option>
					<option value="pg_in_same_specialisation_or_related_to_department" {{ $data && $data->questionnaire && $data->questionnaire->education == 'pg_in_same_specialisation_or_related_to_department' ? 'selected' : '' }}>PG in the same specialization or related to the department</option>
				</select>
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6" id="educationCertificatesDiv" style="display: none;">
				<label for="education_certificates" class="form-label"><span class="error">* </span>{{ __('Education Certificates :') }} </label>
				<input type="text" placeholder="Educational Certificates" name="education_certificates" class="form-control" id="education_certificates" value="{{$data->questionnaire->education_certificates ?? ''}}">
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 ">
				<label for="certification" class="form-label">{{ __('Certification :') }} </label>
				<input type="text" placeholder="Certification" name="certification" class="form-control" id="certification" value="{{$data->questionnaire->certification ?? ''}}">
			</div>
		</div>
		<div class="row">
			<div class=" col-lg-4 col-md-6 col-sm-6 ">
				<label for="specific_company_experience" class="form-label"><span class="error">* </span>{{ __('Any Specific Company Experience :') }} </label>
				<input type="text" placeholder="Company Experience" name="specific_company_experience" class="form-control" id="specific_company_experience" value="{{$data->questionnaire->specific_company_experience ?? ''}}">
			</div>
			<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
				<div class="dropdown-option-div">
					<label for="industry_experience_id" class="form-label"><span class="error">* </span>{{ __('Any specific industry experience') }}</label>
					<select name="industry_experience_id" id="requested_industry_experience" class="form-control widthinput" multiple="true" autofocus>
					@foreach($masterSpecificIndustryExperiences as $masterSpecificIndustryExperience)
					<option value="{{$masterSpecificIndustryExperience->id}}" {{ $data && $data->questionnaire && $data->questionnaire->specificIndustryExperience->id && $masterSpecificIndustryExperience->id == $data->questionnaire->specificIndustryExperience->id ? 'selected' : '' }}>{{$masterSpecificIndustryExperience->name}}</option>
					@endforeach
					</select>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-6">
				<a id="createNewIndustryExperienceButton" data-toggle="popover" data-trigger="hover" title="Create New Industry Experience" data-placement="bottom" style="margin-top:43px;" class="btn btn-sm btn-info industry-exp-modal-button" data-modal-id="createNewIndustryExperience"><i class="fa fa-plus" aria-hidden="true"></i> Create New Industry Experience</a>
			</div>
		</div>
		<br />
		<div class="maindd">
			<div id="row-container">
				<div class="row">
					<div class="col-lg-4 col-md-8 col-sm-8">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
								<label for="salary_range" class="form-label"><span class="error">* </span>{{ __('Salary Start ') }}</label>
								<div class="input-group">
									<input name="salary_range_start_in_aed" id="salary_range_start_in_aed" class="form-control widthinput" placeholder="Start" aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->questionnaire->salary_range_start_in_aed ?? ''}}">
									<div class="input-group-append">
										<span class="input-group-text widthinput" id="basic-addon2">AED</span>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6">
								<label for="salary_range" class="form-label"><span class="error">* </span>{{ __('Salary End') }}</label>
								<div class="input-group">
									<input name="salary_range_end_in_aed" id="salary_range_end_in_aed" class="form-control widthinput" placeholder="End" aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->questionnaire->salary_range_end_in_aed ?? ''}}">
									<div class="input-group-append">
										<span class="input-group-text widthinput" id="basic-addon2">AED</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="visa_type" class="form-label"><span class="error">* </span>{{ __('Visa Type') }}</label>
							<select name="visa_type" id="visa_type" class="form-control widthinput" multiple="true" autofocus>
							@foreach($masterVisaTypes as $masterVisaType)
							<option value="{{$masterVisaType->id}}" {{ $data && $data->questionnaire && $data->questionnaire->visa_type && $masterVisaType->id == $data->questionnaire->visa_type ? 'selected' : '' }}>{{$masterVisaType->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="nationality" class="form-label"><span class="error">* </span>{{ __('Nationality') }}</label>
							<select name="nationality" id="nationality" class="form-control widthinput" multiple="true" autofocus>
							@foreach($masterNationality as $Country)
							<option value="{{$Country->id}}" {{ $data && $data->questionnaire && $data->questionnaire->nationality && $Country->id == $data->questionnaire->nationality ? 'selected' : '' }}>{{$Country->nationality}} ({{$Country->name}})</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<label for="age" class="form-label">{{ __('Age:') }}</label>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="input-group">
									<span class="input-group-text">From</span>
									<input type="number" placeholder="From" name="min_age" class="form-control" id="min_age" value="{{$data->questionnaire->min_age ?? ''}}">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="input-group">
									<span class="input-group-text">to</span>
									<input type="number" placeholder="End" name="max_age" class="form-control" id="max_age" value="{{$data->questionnaire->max_age ?? ''}}">
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="language_id" class="form-label"><span class="error">* </span>{{ __('Additional Language(s):') }}</label>
							<select name="language_id[]" id="language_id" class="form-control widthinput" multiple autofocus>
							@foreach($masterLanguages as $Language)
							<option value="{{$Language->id}}" {{ $data && $data->questionnaire && $data->questionnaire->additionalLanguages && in_array($Language->id, $data->questionnaire->additionalLanguages->pluck('language_id')->toArray()) ? 'selected' : '' }}>
							{{$Language->name}}
							</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-div-container">
								<label for="required_to_travel_for_work_purpose" class="form-label"><span class="error">* </span>{{ __('Did he require to travel for work purpose?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="required_to_travel_for_work_purpose" id="yes" value="yes" @if($currentQuestionnaire->required_to_travel_for_work_purpose == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="required_to_travel_for_work_purpose" id="no" value="no" @if($currentQuestionnaire->required_to_travel_for_work_purpose == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-div-container">
								<label for="requires_multiple_industry_experience" class="form-label"><span class="error">* </span>{{ __('Do candidates require multiple industry experience?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="requires_multiple_industry_experience" id="yes" value="yes" @if($currentQuestionnaire->requires_multiple_industry_experience == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="requires_multiple_industry_experience" id="no" value="no" @if($currentQuestionnaire->requires_multiple_industry_experience == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12  radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-div-container">
								<label for="team_handling_experience_required" class="form-label"><span class="error">* </span>{{ __('Team handling experience is required?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="team_handling_experience_required" id="yes" value="yes" @if($currentQuestionnaire->team_handling_experience_required == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="team_handling_experience_required" id="no" value="no" @if($currentQuestionnaire->team_handling_experience_required == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 ">
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-button-main-div">
								<div class="radio-div-container">
									<label for="required_to_work_on_trial" class="form-label"><span class="error">* </span>{{ __('Is shortlisted candidate require to work on trial ?') }}</label>
									<div class="designation-radio-button">
										<label>
										<input type="radio" name="required_to_work_on_trial" id="yes" value="yes" @if($currentQuestionnaire->required_to_work_on_trial == 'yes') checked @endif> Yes
										</label>
										<label>
										<input type="radio" name="required_to_work_on_trial" id="no" value="no" @if($currentQuestionnaire->required_to_work_on_trial == 'no') checked @endif> No
										</label>
									</div>
								</div>
							</div>
							<!-- if yes, add input  button to enter number of days -->
							<div class="numberOfDaysInputContainer" style="display: none">
								<label for="number_of_trial_days" class="form-label"><span class="error">* </span>{{ __('Enter Number of days:') }}</label>
								<input type="number" placeholder="no. of days" name="number_of_trial_days" class="form-control" id="number_of_trial_days" value="{{$data->questionnaire->number_of_trial_days ?? ''}}">
							</div>
						</div>
					</div>
					<!-- Left Side -->
					<div class="col-lg-4 col-md-12 col-sm-12 radio-button-main-div">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-div-container">
								<label for="commission_involved_in_salary" class="form-label"><span class="error">* </span>{{ __('Is commission involved along with the salary?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="commission_involved_in_salary" id="yes" value="yes" @if($currentQuestionnaire->commission_involved_in_salary == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="commission_involved_in_salary" id="no" value="no" @if($currentQuestionnaire->commission_involved_in_salary == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<!-- Right Side -->
					<div class="col-lg-4 col-md-12 col-sm-12 commissionInputContainer" style="display: none">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12">
								<!-- Dropdown Container -->
								<div class="chooseAmountpercentageDropDownInputContainer" style="display: none;">
									<label for="commission_type" class="form-label"><span class="error">* </span>{{ __('Choose Option') }}</label>
									<select name="commission_type" id="commission_type" class="form-control widthinput" onchange="showAmountPercentageInput(this)" autofocus>
										<option value="" disabled selected>Choose Option</option>
										<option value="amount" {{ $data && $data->questionnaire && $data->questionnaire->commission_type == 'amount' ? 'selected' : '' }}>Amount</option>
										<option value="percentage" {{ $data && $data->questionnaire && $data->questionnaire->commission_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
									</select>
								</div>
							</div>
							<!-- Amount Input Container -->
							<div class="col-lg-6 col-md-6 col-sm-12 amountDropDownInputContainer" id="amountDropDownInputContainer" style="display: none">
								<div class="amountInputContainer" id="amountInputContainer">
									<label for="commission_amount" class="form-label"><span class="error">* </span>{{ __('Amount:') }}</label>
									<div class="input-group">
										<input type="number" name="commission_amount" id="commission_amount" class="form-control widthinput" placeholder="amount" aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->questionnaire->commission_amount ?? ''}}">
										<div class="input-group-append">
											<span class="input-group-text widthinput" id="basic-addon2">AED</span>
										</div>
									</div>
								</div>
							</div>
							<!-- Percentage Input Container -->
							<div class="col-lg-6 col-md-6 col-sm-12 percentageDropDownInputContainer" id="percentageDropDownInputContainer" style="display: none">
								<div class="percentageInputContainer" id="percentageInputContainer">
									<label for="commission_percentage" class="form-label"><span class="error">* </span>{{ __('Enter percentage:') }}</label>
									<input type="number" placeholder="percentage" name="commission_percentage" class="form-control" id="commission_percentage" value="{{$data->questionnaire->commission_percentage ?? ''}}">
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 radio-div-container">
								<label for="driving_licence" class="form-label"><span class="error">* </span>{{ __('Driving Licence Required?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="driving_licence" id="yes" value="yes" @if($currentQuestionnaire->driving_licence == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="driving_licence" id="no" value="no" @if($currentQuestionnaire->driving_licence == 'no') checked @endif> No
									</label>
								</div>
							</div>
							<!-- if yes, add radio button for: Own car, Expenses done by ? own or Company -->
						</div>
					</div>
					<div class="col-lg-8 col-md-6 col-sm-12 drivingLisenceInputContainer" style="display: none">
						<div class="  ">
							<div class="row ">
								<div class="col-lg-6 radio-button-main-div">
									<div class="radio-div-container">
										<label for="own_car" class="form-label"><span class="error">* </span>{{ __('Car accompanied By?') }}</label>
										<div class="designation-radio-button">
											<label>
											<input type="radio" name="own_car" id="own" value="yes" @if($currentQuestionnaire->own_car == 'yes') checked @endif> own
											</label>
											<label>
											<input type="radio" name="own_car" id="company" value="no" @if($currentQuestionnaire->own_car == 'no') checked @endif> Company
											</label>
										</div>
									</div>
								</div>
								<div class="col-lg-6 radio-button-main-div">
									<div class="radio-div-container">
										<label for="fuel_expenses_by" class="form-label"><span class="error">* </span>{{ __('Fuels Expenses covered by?') }}</label>
										<div class="designation-radio-button">
											<label>
											<input type="radio" name="fuel_expenses_by" id="company" value="company" @if($currentQuestionnaire->fuel_expenses_by == 'company') checked @endif> Company
											</label>
											<label>
											<input type="radio" name="fuel_expenses_by" id="own" value="own" @if($currentQuestionnaire->fuel_expenses_by == 'own') checked @endif> Own
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="interviewd_by" class="form-label"><span class="error">* </span>{{ __('Interview Organized By:') }}</label>
							<select name="interviewd_by" id="interviewd_by" class="form-control widthinput" multiple="true" autofocus>
							@foreach($interviewdByUsers as $User)
							<option value="{{$User->id}}" {{$data && $data->questionnaire && $data->questionnaire->interviewd_by && $User->id == $data->questionnaire->interviewd_by ? 'selected' : '' }}>{{$User->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class=" col-lg-4 col-md-12 col-sm-12 ">
						<label for="mandatory_skills" class="form-label"><span class="error">* </span>{{ __('Top 3 skills / mandatory work experience :') }} </label>
						<textarea name="mandatory_skills" class="form-control" rows="3" cols="15">{{$data->questionnaire->mandatory_skills ?? ''}}</textarea>
					</div>
					<div class=" col-lg-4 col-md-12 col-sm-12  ">
						<label for="job_opening_purpose_objective" class="form-label"><span class="error">* </span>{{ __('Objectives of job purpose of job posting: ') }}</label>
						<textarea name="job_opening_purpose_objective" class="form-control" rows="3" cols="15">{{$data->questionnaire->job_opening_purpose_objective ?? ''}}</textarea>
					</div>
					<div class=" col-lg-4 col-md-12 col-sm-12  ">
						<label for="screening_questions" class="form-label"><span class="error">* </span>{{ __('Screening Questions: ') }}</label>
						<textarea name="screening_questions" class="form-control" rows="3" cols="15">{{$data->questionnaire->screening_questions ?? ''}}</textarea>
					</div>
					<div class=" col-lg-4 col-md-12 col-sm-12  ">
						<label for="technical_test" class="form-label"><span class="error">* </span>{{ __('Technical Questions') }}</label>
						<textarea name="technical_test" class="form-control" rows="3" cols="15">{{$data->questionnaire->technical_test ?? ''}}</textarea>
					</div>
					<div class=" col-lg-4 col-md-12 col-sm-12  ">
						<label for="trial_work_job_description" class="form-label"><span class="error">* </span>{{ __('Job description during trial Working') }}</label>
						<textarea name="trial_work_job_description" class="form-control" rows="3" cols="15">{{$data->questionnaire->trial_work_job_description ?? ''}}</textarea>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12 col-12 ">
						<div class="stakeholders-main-div">
							<label for="job_evaluation_stake_holders" id="job_evaluation_stake_holders" class="form-label"><span class="error">* </span>{{ __('Stakeholders for Job Evaluation') }}</label>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12" id="internal_or_external_evaluation">
								<div class="form-check form-check-inline col-lg-12 col-md-12 col-sm-12 col-12">
									<input class="form-check-input" name="internal_department_evaluation" type="checkbox" id="internal_department_evaluation" value="internal_department_evaluation" {{ $data && $data->questionnaire && str_contains($data->questionnaire->job_evaluation_stake_holders, 'Internal Departments') ? 'checked' : '' }}>
									<label class="form-check-label" for="internal_department_evaluation">Internal Departments</label>
								</div>
								<div class="form-check form-check-inline col-lg-12 col-md-12 col-sm-12 col-12">
									<input class="form-check-input" name="external_vendor_evaluation" type="checkbox" id="external_vendor_evaluation" value="external_vendor_evaluation" {{ $data && $data->questionnaire && str_contains($data->questionnaire->job_evaluation_stake_holders, 'External Vendors') ? 'checked' : '' }}>
									<label class="form-check-label" for="external_vendor_evaluation">External vendors</label>
								</div>
							</div>
							<div class="stakeholders-error-container">
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 radio-button-main-div select-button-main-div">
						<div class="dropdown-option-div">
							<label for="recruitment_source_id" class="form-label"><span class="error">* </span>{{ __('Recruitment Source:') }}</label>
							<select name="recruitment_source_id" id="recruitment_source_id" class="form-control widthinput" multiple="true" autofocus>
							@foreach($masterRecuritmentSources as $MasterRecuritmentSource)
							<option value="{{$MasterRecuritmentSource->id}}" {{$data && $data->questionnaire && $data->questionnaire->recruitment_source_id && $MasterRecuritmentSource->id == $data->questionnaire->recruitment_source_id ? 'selected' : '' }}>{{$MasterRecuritmentSource->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 radio-div-container">
								<label for="experience" class="form-label"><span class="error">* </span>{{ __('Experience') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="experience" id="local" value="local" @if($currentQuestionnaire->experience == 'local') checked @endif> Local
									</label>
									<label>
									<input type="radio" name="experience" id="international" value="international" @if($currentQuestionnaire->experience == 'international') checked @endif> International
									</label>
									<label>
									<input type="radio" name="experience" id="home_country" value="home_country" @if($currentQuestionnaire->experience == 'home_country') checked @endif> Home Country
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 radio-div-container">
								<label for="travel_experience" class="form-label"><span class="error">* </span>{{ __('Travel experience?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="travel_experience" id="yes" value="yes" @if($currentQuestionnaire->travel_experience == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="travel_experience" id="no" value="no" @if($currentQuestionnaire->travel_experience == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="department_id" class="form-label"><span class="error">* </span>{{ __('Division / Department:') }}</label>
							<select name="department_id" id="department_id" class="form-control widthinput" multiple="true" autofocus>
							@foreach($masterDepartments as $MasterDeparment)
							<option value="{{$MasterDeparment->id}}" {{$data && $data->questionnaire && $data->questionnaire->department_id && $MasterDeparment->id == $data->questionnaire->department_id ? 'selected' : '' }}>{{$MasterDeparment->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="career_level_id" class="form-label"><span class="error">* </span>{{ __('Career level:') }}</label>
							<select name="career_level_id" id="career_level_id" class="form-control widthinput" multiple="true" autofocus>
								@foreach($masterExperienceLevels as $MasterExperienceLevel)
								<option value="{{$MasterExperienceLevel->id}}">{{$MasterExperienceLevel->name}} ( {{$MasterExperienceLevel->number_of_year_of_experience}} )</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 col-sm-12">
						<label for="current_or_past_employer_size_start" class="form-label">{{ __('Current or Past Employer Size:') }}</label>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="input-group">
									<span class="input-group-text">From</span>
									<input type="number" placeholder="From" name="current_or_past_employer_size_start" class="form-control" id="current_or_past_employer_size_start" value="{{$data->questionnaire->current_or_past_employer_size_start ?? ''}}">
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-12">
								<div class="input-group">
									<span class="input-group-text">to</span>
									<input type="number" placeholder="Till" name="current_or_past_employer_size_end" class="form-control" id="current_or_past_employer_size_end" value="{{$data->questionnaire->current_or_past_employer_size_end ?? ''}}">
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="trial_pay_in_aed" class="form-label"><span class="error">* </span>{{ __('Trial Pay (AED):') }} </label>
						<input type="number" placeholder="Trial Pay in AED" name="trial_pay_in_aed" class="form-control" id="trial_pay_in_aed" value="{{$data->questionnaire->trial_pay_in_aed ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 radio-div-container">
								<label for="out_of_office_visit" class="form-label"><span class="error">* </span>{{ __('Out of Office Visits?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="out_of_office_visit" id="yes" value="yes" @if($currentQuestionnaire->out_of_office_visit == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="out_of_office_visit" id="no" value="no" @if($currentQuestionnaire->out_of_office_visit == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 radio-div-container">
								<label for="remote_work" class="form-label"><span class="error">* </span>{{ __('Remote Work?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="remote_work" id="yes" value="yes" @if($currentQuestionnaire->remote_work == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="remote_work" id="no" value="no" @if($currentQuestionnaire->remote_work == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-12 radio-button-main-div">
						<div class="row ">
							<div class="col-lg-12 radio-div-container">
								<label for="international_business_trip_required" class="form-label"><span class="error">* </span>{{ __('International Business trips required?') }}</label>
								<div class="designation-radio-button">
									<label>
									<input type="radio" name="international_business_trip_required" id="yes" value="yes" @if($currentQuestionnaire->international_business_trip_required == 'yes') checked @endif> Yes
									</label>
									<label>
									<input type="radio" name="international_business_trip_required" id="no" value="no" @if($currentQuestionnaire->international_business_trip_required == 'no') checked @endif> No
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="probation_length_in_months" class="form-label"> <span class="error">* </span>{{ __('Probation length (months):') }}</label>
						<input type="number" placeholder="Probation length in months" name="probation_length_in_months" class="form-control" id="probation_length_in_months" value="{{$data->questionnaire->probation_length_in_months ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="probation_pay_amount_in_aed" class="form-label"><span class="error">* </span> {{ __('Probation Pay (AED):') }}</label>
						<input type="number" placeholder="Probation Pay in AED" name="probation_pay_amount_in_aed" class="form-control" id="probation_pay_amount_in_aed" value="{{$data->questionnaire->probation_pay_amount_in_aed ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="incentives_perks_bonus" class="form-label"><span class="error">* </span>{{ __('Incentive, Perks, & Bonus:') }} </label>
						<input type="text" placeholder="Incentives" name="incentives_perks_bonus" class="form-control" id="incentives_perks_bonus" value="{{$data->questionnaire->incentives_perks_bonus ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="kpi" class="form-label"><span class="error">* </span>{{ __('KPI: ') }}</label>
						<input type="text" placeholder="KPI" name="kpi" class="form-control" id="kpi" value="{{$data->questionnaire->kpi ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="practical_test" class="form-label"><span class="error">* </span>{{ __('Practical test:') }} </label>
						<input type="text" placeholder="Practical test" name="practical_test" class="form-control" id="practical_test" value="{{$data->questionnaire->practical_test ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="trial_objectives_and_evaluation_method" class="form-label"><span class="error">* </span>{{ __('Trial objectives and Evaluation method: ') }}</label>
						<input type="text" placeholder="Trial objectives and Evaluation method" name="trial_objectives_and_evaluation_method" class="form-control" id="trial_objectives_and_evaluation_method" value="{{$data->questionnaire->trial_objectives_and_evaluation_method ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 ">
						<label for="duties_and_tasks" class="form-label"><span class="error">* </span>{{ __('Duties & Tasks : ') }}</label>
						<input type="text" placeholder="Duties & Tasks" name="duties_and_tasks" class="form-control" id="duties_and_tasks" value="{{$data->questionnaire->duties_and_tasks ?? ''}}">
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="next_career_path_id" class="form-label"><span class="error">* </span>{{ __('Next Career path:') }}</label>
							<select name="next_career_path_id" id="next_career_path_id" class="form-control widthinput" multiple="true" autofocus>
							@foreach($masterExperienceLevels as $MasterExperienceLevel)
							<option value="{{$MasterExperienceLevel->id}}" {{$data && $data->questionnaire && $data->questionnaire->next_career_path_id && $MasterExperienceLevel->id == $data->questionnaire->next_career_path_id ? 'selected' : '' }}>{{$MasterExperienceLevel->name}} ( {{$MasterExperienceLevel->number_of_year_of_experience}} )</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		</br>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
@include('hrm.hiring.questionnaire.createIndustryExperience')
<div class="overlay"></div>
@include('hrm.hiring.hiring_request.createJobPosition')
<div class="overlay"></div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	var data = <?php echo json_encode($data); ?>;
	console.log("Data is ", data)
	
	$(document).ready(function() {
	
	    var requestedDesignationName = {{$data -> questionnaire -> designation -> id ?? 'null'}};
	    console.log("Updated requested designation name : ", requestedDesignationName);
	    if (requestedDesignationName !== null) {
	
	        $("#requested_job_title option").each(function() {
	            var optionValue = $(this).val();
	
	            if (optionValue == requestedDesignationName) {
	                $(this).prop("selected", true);
	            }
	        });
	    } else {
	        console.log("In else of requested job title designation name id", data.requested_job_title)
	        $("#requested_job_title").val(data.requested_job_title);
	    }
	
	    // Location name validate from Hiring request form
	
	    var requestedLocationName = {{$data -> questionnaire -> workLocation -> id ?? 'null'}};
	       
	    console.log("Updated requested location name : ", requestedLocationName);
	    if (requestedLocationName !== null) {
	
	        $("#location_id option").each(function() {
	            var optionValue = $(this).val();
	
	            if (optionValue == requestedLocationName) {
	                $(this).prop("selected", true);
	            }
	        });
	    } else {
	        console.log("In else of requested locatio name id")
	        $("#location_id").val(data.location_id);
	    }
	
	    var currentNumberOfOpenings = $("#number_of_openings").val();
	    console.log("current position opening value is: ", currentNumberOfOpenings)
	    console.log("old position opening value is: ", data.number_of_openings);
	    if (currentNumberOfOpenings && data.number_of_openings !== currentNumberOfOpenings) {
	        console.log("ifffffffffff")
	        $("#number_of_openings").val(currentNumberOfOpenings);
	    } else {
	        console.log("elseeeeeeeee")
	        $("#number_of_openings").val(data.number_of_openings)
	    };
	
	
	    var currentStartTimeValue = $("#work_time_start").val();
	    console.log("current starting value of work time is: ", currentStartTimeValue)
	    console.log("old starting value of work time is: ", data.work_time_start);
	    if (currentStartTimeValue && data.work_time_start !== currentStartTimeValue) {
	        console.log("ifffffffffff")
	        $("#work_time_start").val(currentStartTimeValue);
	    } else {
	        console.log("elseeeeeeeee")
	        $("#work_time_start").val(data.work_time_start)
	    };
	
	
	    var currentEndTimeValue = $("#work_time_end").val();
	    console.log("current ending value of work time is: ", currentEndTimeValue)
	    console.log("old ending value of work time is: ", data.work_time_end);
	    if (currentEndTimeValue && data.work_time_end !== currentEndTimeValue) {
	        console.log("ifffffffffff")
	        $("#work_time_end").val(currentEndTimeValue);
	    } else {
	        console.log("elseeeeeeeee")
	        $("#work_time_end").val(data.work_time_end)
	    };
	
	
	    var currentStartingSalaryValue = $("#salary_range_start_in_aed").val();
	    console.log("current starting value of salary is: ", currentStartingSalaryValue)
	    console.log("old starting value of salary is: ", data.salary_range_start_in_aed);
	    if (currentStartingSalaryValue && data.salary_range_start_in_aed !== currentStartingSalaryValue) {
	        console.log("ifffffffffff")
	        $("#salary_range_start_in_aed").val(currentStartingSalaryValue);
	    } else {
	        console.log("elseeeeeeeee")
	        $("#salary_range_start_in_aed").val(data.salary_range_start_in_aed)
	    };
	
	
	    var currentEndingSalaryValue = $("#salary_range_end_in_aed").val();
	    console.log("current ending value of salary is: ", currentEndingSalaryValue)
	    console.log("old ending value of salary is: ", data.salary_range_end_in_aed);
	    if (currentEndingSalaryValue && data.salary_range_end_in_aed !== currentEndingSalaryValue) {
	        console.log("ifffffffffff")
	        $("#salary_range_end_in_aed").val(currentEndingSalaryValue);
	    } else {
	        console.log("elseeeeeeeee")
	        $("#salary_range_end_in_aed").val(data.salary_range_end_in_aed)
	    };
	
	    var requestedDepartmentName = {{$data -> questionnaire -> department -> id ?? 'null'}};
	    console.log("Updated requested department name : ", requestedDepartmentName);
	    if (requestedDepartmentName !== null) {
	
	        $("#department_id option").each(function() {
	            var optionValue = $(this).val();
	
	            if (optionValue == requestedDepartmentName) {
	                $(this).prop("selected", true);
	            }
	        });
	    } else {
	        console.log("In else of requested department name id", data.department_id)
	        $("#department_id").val(data.department_id);
	    }
	
	
	    var backendCareerLevelId = {{ $data -> questionnaire -> carrerLevel -> id ?? 'null' }};
	    console.log("Updated Career Level ID: ", backendCareerLevelId);
	    if (backendCareerLevelId !== null) {
	
	        $("#career_level_id option").each(function() {
	            var optionValue = $(this).val();
	
	            if (optionValue == backendCareerLevelId) {
	                $(this).prop("selected", true);
	            }
	        });
	    } else {
	        console.log("In else of career vlvl")
	        $("#career_level_id").val(data.experience_level);
	    }
	
	    $('select[multiple="true"]').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder: "Choose...",
	    });
	
	    $('#language_id').select2({
	        minimumResultsForSearch: -1,
	        placeholder: "Choose Additional Languages",
	    });
	
	});
	
	$('select[multiple="true"]').on('change', function() {
	    var fieldName = $(this).attr('name');
	    $('#employeeQuestionnaireForm').validate().element('[name="' + fieldName + '"]');
	});
	$('#language_id').on('change', function() {
	    var fieldName = $(this).attr('name');
	    $('#employeeQuestionnaireForm').validate().element('[name="' + fieldName + '"]');
	});
	
	
	$('#language_id').on('change', function() {
	    var selectedValues = $(this).val();
	    console.log("Selected Language IDs:", selectedValues);
	});
	
	$("#education").change(function() {
	    if ($(this).val() === "pg_in_same_specialisation_or_related_to_department") {
	        $("#educationCertificatesDiv").show();
	    } else {
	        $("#educationCertificatesDiv").hide();
	    }
	}).change();
	
	function handleTrialWorkDaysVisibility() {
	    var TrialWorkDaysValue = $('input[name="required_to_work_on_trial"]:checked').val();
	
	    if (TrialWorkDaysValue === 'yes') {
	        $('.numberOfDaysInputContainer').show();
	    } else {
	        $('.numberOfDaysInputContainer').hide();
	    }
	}
	
	$('input[name="required_to_work_on_trial"]').change(handleTrialWorkDaysVisibility);
	handleTrialWorkDaysVisibility();
	
	
	function handleCommissionVisibility() {
	    var commissionValue = $('input[name="commission_involved_in_salary"]:checked').val();
	
	    if (commissionValue === 'yes') {
	        $('.commissionInputContainer, .chooseAmountpercentageDropDownInputContainer').show();
	        $('.amountDropDownInputContainer, .percentageDropDownInputContainer').hide();
	    } else {
	        $('.commissionInputContainer, .chooseAmountpercentageDropDownInputContainer').hide();
	        $('.amountDropDownInputContainer, .percentageDropDownInputContainer').show();
	    }
	}
	
	$('input[name="commission_involved_in_salary"]').change(handleCommissionVisibility);
	handleCommissionVisibility();
	
	
	$('input[name="driving_licence"]').change(function() {
	    setTimeout(function() {
	        if ($('input[name="driving_licence"]:checked').val() === 'yes') {
	            $('.drivingLisenceInputContainer').show();
	        } else {
	            $('.drivingLisenceInputContainer').hide();
	        }
	    }, 100);
	});
	
	$('input[name="driving_licence"]').change();
	
	
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
	        return this.optional(element) || isValidTotalOpenings;
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
	        return this.optional(element) || isValidTotalOpenings;
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
	
	jQuery.validator.addMethod(
	    "timeDifferenceValidate",
	    function(value, element, params) {
	        var startTime = $(params[0]).val();
	        var endTime = value;
	
	        var timeDifference = calculateTimeDifference(startTime, endTime);
	        return timeDifference >= 9;
	    },
	    "The time difference must be greater than or equal to 9 hours"
	);
	
	function calculateTimeDifference(startTime, endTime) {
	    var start = new Date("01/01/2023 " + startTime);
	    var end = new Date("01/01/2023 " + endTime);
	    var timeDifference = Math.abs(end - start) / 36e5;
	    return timeDifference;
	}
	
	$.validator.addMethod("atLeastOneCheckbox", function(value, element) {
	    return $('input[name="internal_department_evaluation"]').is(':checked') || $('input[name="external_vendor_evaluation"]').is(':checked');
	        }, "Please select at least one value");
	
	
	$('#employeeQuestionnaireForm').submit(function(event) {
	
	    console.log("Data to be sent:", $(this).serialize());
	    // event.preventDefault();
	});
	
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
	            timeDifferenceValidate: ["#work_time_start"]
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
	            twoDigitValues: true,
	        },
	        max_age: {
	            twoDigitValues: true,
	            greaterThanFirstValueValidate: "#min_age",
	        },
	        'language_id[]': {
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
	            required: {
	                function(element) {
	                    return $('input[name="driving_licence"]:checked').val() === 'yes';
	                }
	            },
	        },
	        fuel_expenses_by: {
	            required: {
	                function(element) {
	                    return $('input[name="driving_licence"]:checked').val() === 'yes';
	                }
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
	        'internal_department_evaluation': {
	            atLeastOneCheckbox: true
	        },
	        'external_vendor_evaluation': {
	            atLeastOneCheckbox: true
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
	            threeDigitValuesWithZero: true,
	        },
	        current_or_past_employer_size_end: {
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
	    groups: {
	        job_evaluation_stake_holders: "internal_department_evaluation external_vendor_evaluation"
	    },
	
	    errorPlacement: function(error, element) {
	        console.log("Error placement function called");
	        console.log("Element:", element);
	
	        if (element.is(':radio') && element.closest('.radio-button-main-div').length > 0) {
	            error.addClass('radio-error');
	            error.insertAfter(element.closest('.radio-button-main-div').find('div.radio-div-container').last());
	        } else if (element.is(':checkbox') && element.closest('.col-lg-4').length > 0) {
	            error.addClass('checkbox-error');
	            error.insertAfter(element.closest('.stakeholders-main-div').find('.stakeholders-error-container'));
	        } else if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
	            if (!element.val() || element.val().length === 0) {
	                console.log("Error is here with length", element.val().length);
	                error.addClass('select-error');
	                error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	            } else {
	                console.log("No error");
	            }
	        } else if (element.attr('name') === 'min_age' || element.attr('name') === 'max_age' ||
	            element.attr('name') === 'work_time_start' || element.attr('name') === 'work_time_end' ||
	            element.attr('name') === 'salary_range_start_in_aed' || element.attr('name') === 'salary_range_end_in_aed' ||
	            element.attr('name') === 'current_or_past_employer_size_start' || element.attr('name') === 'current_or_past_employer_size_end' || element.attr('name') === 'commission_amount') {
	            error.addClass('other-error');
	            error.insertAfter(element.closest('.input-group'));
	        } else {
	            error.addClass('other-error');
	            error.insertAfter(element);
	        }
	    },
	
	
	});
</script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
	    function showAmountPercentageInput(element) {
	        var selectedValue = element.value;
	        document.getElementById('amountDropDownInputContainer').style.display = selectedValue == 'amount' ? 'block' : 'none';
	        document.getElementById('percentageDropDownInputContainer').style.display = selectedValue == 'percentage' ? 'block' : 'none';
	    }
	    var selectElement = document.getElementById('commission_type');
	
	    selectElement.addEventListener('change', function() {
	        showAmountPercentageInput(this);
	    });
	
	    showAmountPercentageInput(selectElement);
	});
</script>
@endpush