@extends('layouts.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@include('layouts.formstyle')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-all-employee-details']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Edit Employee Information</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="editEmpForm" name="editEmpForm" enctype="multipart/form-data" method="POST" action="{{route('employee.update',$data->id)}}">
		@csrf
		@method('PUT')
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Personal Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employee_code" class="col-form-label text-md-end">{{ __('Employee Code') }}</label>
						<input id="employee_code" type="text" class="form-control widthinput @error('employee_code') is-invalid @enderror" name="employee_code"
							placeholder="Enter Employee Code" value="" autocomplete="employee_code" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="name" class="col-form-label text-md-end">{{ __('Employee Full Name') }}</label>
						<input id="name" type="text" class="form-control widthinput @error('name') is-invalid @enderror" name="name"
							placeholder="Enter Employee Full Name" value="" autocomplete="name" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="first_name" class="col-form-label text-md-end">{{ __('Employee First Name') }}</label>
						<input id="first_name" type="text" class="form-control widthinput @error('first_name') is-invalid @enderror" name="first_name"
							placeholder="Enter Employee First Name" value="" autocomplete="first_name" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="last_name" class="col-form-label text-md-end">{{ __('Employee Last Name') }}</label>
						<input id="last_name" type="text" class="form-control widthinput @error('last_name') is-invalid @enderror" name="last_name"
							placeholder="Enter Employee Last Name" value="" autocomplete="last_name" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="name_of_father" class="col-form-label text-md-end">{{ __("Father’s Full Name" ) }}</label>
						<input id="name_of_father" type="text" class="form-control widthinput @error('name_of_father') is-invalid @enderror" name="name_of_father"
							placeholder="Father’s Full Name" value="" autocomplete="name_of_father" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="name_of_mother" class="col-form-label text-md-end">{{ __("Mother’s Full Name" ) }}</label>
						<input id="name_of_mother" type="text" class="form-control widthinput @error('name_of_mother') is-invalid @enderror" name="name_of_mother"
							placeholder="Mother’s Full Name" value="" autocomplete="name_of_mother" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="educational_qualification" class="col-form-label text-md-end">{{ __('Educational Qualification') }}</label>
						<input id="educational_qualification" type="text" class="form-control widthinput @error('educational_qualification') is-invalid @enderror" name="educational_qualification"
							placeholder="Educational Qualification" value="" autocomplete="educational_qualification" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="year_of_completion" class="col-form-label text-md-end">{{ __('Year of Completion') }}</label>
							<input id="year_of_completion" type="number" min="1950" max="<?= date('Y'); ?>" step="1" class="form-control widthinput @error('year_of_completion') is-invalid @enderror" name="year_of_completion"
								placeholder="Year of Completion" value="" autocomplete="year_of_completion" autofocus>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="marital_status" class="col-form-label text-md-end">{{ __('Choose Marital Status') }}</label>
							<select name="marital_status" id="marital_status" class="form-control widthinput" autofocus>
								<option>Choose Marital Status</option>
								@foreach($masterMaritalStatus as $maritalStatus)
								<option value="{{$maritalStatus->id}}">{{$maritalStatus->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="religion" class="col-form-label text-md-end">{{ __('Choose Religion') }}</label>
							<select name="religion" id="religion" multiple="true" class="form-control widthinput" autofocus>
								@foreach($masterReligion as $religion)
								<option value="{{$religion->id}}">{{$religion->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="nationality" class="col-form-label text-md-end">{{ __('Choose Nationality') }}</label>
							<select name="nationality" id="nationality" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterNationality as $nationality)
								<option value="{{$nationality->id}}">{{$nationality->nationality}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="language_id" class="col-form-label text-md-end">{{ __('Choose Spoken Languages') }}</label>
							<select name="language_id[]" id="language_id" multiple="true" class="form-control widthinput" autofocus>
								@foreach($masterLanguages as $masterLanguage)
								<option value="{{$masterLanguage->id}}">{{$masterLanguage->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="dob" class="col-form-label text-md-end">{{ __('Date Of Birth') }}</label>
						<input id="dob" type="date" class="form-control widthinput @error('dob') is-invalid @enderror" name="dob"
							value="" autocomplete="dob" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3 radio-main-div">
						<span class="error">* </span>
						<label for="gender" class="col-form-label text-md-end">{{ __('Gender') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="gender" name="gender" value="1" id="1" />
									<label for="male">Male</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="gender" name="gender" value="2" id="2" />
									<label for="female">Female</label>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Address and Contact Details in UAE</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="address_uae" class="col-form-label text-md-end">{{ __('Address in UAE') }}</label>													
						<textarea rows="9" id="address_uae" type="text" class="form-control @error('address_uae') is-invalid @enderror"
							name="address_uae" placeholder="Address in UAE" value="{{ old('address_uae') }}"  autocomplete="address_uae"
							autofocus></textarea>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6 mt-4">
						<div class="row">
							<div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
								<span class="error">* </span>
								<label for="company_number" class="col-form-label text-md-end">{{ __('Company Number') }}</label>
							</div>
							<div class="col-xxl-8 col-lg-8 col-md-8 mt-2 select-button-main-div">
								<div class="dropdown-option-div">
									<input id="company_number" type="tel" class="widthinput contact form-control @error('company_number[full]')
										is-invalid @enderror" name="company_number[main]" placeholder="Enter Company Number" oninput="validationOnKeyUp(this)"
										value="{{$candidate->candidateDetails->company_number ?? ''}}" autocomplete="company_number[full]" autofocus
										>
								</div>
							</div>
							<div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
								<span class="error">* </span>
								<label for="contact_number" class="col-form-label text-md-end">{{ __('Personal Phone Number:') }}</label>
							</div>
							<div class="col-xxl-8 col-lg-8 col-md-8 mt-2 select-button-main-div">
								<div class="dropdown-option-div">
									<input id="contact_number" type="tel" class="widthinput form-control @error('contact_number[full]') is-invalid @enderror"
										name="contact_number[main]" placeholder="Enter Personal Phone Number" value="{{old('hiddencontact')}}" oninput="validationOnKeyUp(this)"
										autocomplete="contact_number[main]" autofocus>
								</div>
							</div>
							<div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
								<label for="residence_telephone_number" class="col-form-label text-md-end">{{ __('Residence Telephone Number') }}</label>
							</div>
							<div class="col-xxl-8 col-lg-8 col-md-8 mt-2 select-button-main-div">
								<div class="dropdown-option-div">
									<input id="residence_telephone_number" type="tel" class="widthinput contact form-control @error('residence_telephone_number[full]')
										is-invalid @enderror" name="residence_telephone_number[main]" placeholder="Enter Residence Telephone Number" oninput="validationOnKeyUp(this)"
										value="{{$candidate->candidateDetails->residence_telephone_number ?? ''}}" autocomplete="residence_telephone_number[full]" autofocus
										>
								</div>
							</div>
							<div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
								<span class="error">* </span>
								<label for="email" class="col-form-label text-md-end">{{ __('Company Email Address') }}</label>
							</div>
							<div class="col-xxl-8 col-lg-8 col-md-8 mt-2">
								<input id="email" type="text" class="form-control widthinput @error('email') is-invalid @enderror" name="email"
									placeholder="Enter Company Email Address" value="" autocomplete="email" autofocus>
							</div>
							<div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
								<span class="error">* </span>
								<label for="personal_email_address" class="col-form-label text-md-end">{{ __('Personal Email Address') }}</label>
							</div>
							<div class="col-xxl-8 col-lg-8 col-md-8 mt-2">
								<input id="personal_email_address" type="text" class="form-control widthinput @error('personal_email_address') is-invalid @enderror" name="personal_email_address"
									placeholder="Enter Personal Email Address" value="" autocomplete="personal_email_address" autofocus>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Dependents</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="spouse_name" class="col-form-label text-md-end">{{ __('Spouse Name') }}</label>
						<input id="spouse_name" type="text" class="form-control widthinput @error('spouse_name') is-invalid @enderror" name="spouse_name"
							placeholder="Spouse Name" value="" autocomplete="spouse_name" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="spouse_passport_number" class="col-form-label text-md-end">{{ __('Spouse Passport Number') }}</label>
						<input id="spouse_passport_number" type="text" class="form-control widthinput @error('spouse_passport_number') is-invalid @enderror" name="spouse_passport_number"
							placeholder="Spouse Passport Number" value="" autocomplete="spouse_passport_number" autofocus>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<label for="spouse_passport_expiry_date" class="col-form-label text-md-end">{{ __('Spouse Passport Expiry Date') }}</label>
						<input id="spouse_passport_expiry_date" type="date" class="form-control widthinput @error('spouse_passport_expiry_date') is-invalid @enderror" name="spouse_passport_expiry_date"
							value="" autocomplete="spouse_passport_expiry_date" autofocus>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<label for="spouse_dob" class="col-form-label text-md-end">{{ __('Spouse Date Of Birth') }}</label>
						<input id="spouse_dob" type="date" class="form-control widthinput @error('spouse_dob') is-invalid @enderror" name="spouse_dob"
							value="" autocomplete="spouse_dob" autofocus>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<label for="spouse_nationality" class="col-form-label text-md-end">{{ __('Choose Spouse Nationality') }}</label>
						<select name="spouse_nationality" id="spouse_nationality" multiple="true" class="form-control widthinput" onchange="" autofocus>
							@foreach($masterNationality as $nationality)
							<option value="{{$nationality->id}}">{{$nationality->nationality ?? $nationality->name}} </option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="col-md-12 form_field_outer p-0" id="child">
				</div>
				<div class="col-xxl-12 col-lg-12 col-md-12">
					<a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn">
					<i class="fa fa-plus" aria-hidden="true"></i> Add Child</a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Contact in case of Emergency (UAE)</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="col-md-12 form_field_outer_contact_uae p-0" id="emergency_contact_uae">
					<div class="row form_field_outer_row">
						<div class="col-xxl-3 col-lg-6 col-md-6">
							<span class="error">* </span>
							<label for="ecu_name" class="ecu_name col-form-label text-md-end">{{ __('Name') }}</label>
							<input id="ecu_name_1" type="text" class="form-control widthinput @error('ecu_name') is-invalid @enderror" 
								name="ecu[1][name]" data-index=1 placeholder="emergency Contact Person Name in UAE" value="" autofocus>
						</div>
						<div class="col-xxl-2 col-lg-6 col-md-6">
							<span class="error">* </span>
							<label for="ecu_relation" class="col-form-label text-md-end">{{ __('Relation') }}</label>
							<select name="ecu[1][relation]" data-index=1 id="ecu_relation_1" class="form-control widthinput" autofocus>
								<option>Choose Relation</option>
								@foreach($masterRelations as $relation)
								<option value="{{$relation->id}}">{{$relation->name}} </option>
								@endforeach
							</select>
						</div>
						<div class="col-xxl-2 col-lg-6 col-md-6">
							<span class="error">* </span>
							<label for="ecu_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
							<input id="ecu_email_1" type="text" class="form-control widthinput @error('ecu_email') is-invalid @enderror" 
								name="ecu[1][email_address]" data-index=1 placeholder="Email" value="" autocomplete="ecu_email" autofocus>
						</div>
						<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div">
							<div class="dropdown-option-div">
								<span class="error">* </span>
								<label for="ecu_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
								<input id="ecu_contact_number_1" type="tel" class="form-control widthinput @error('ecu_contact_number[main]') is-invalid @enderror" oninput="validationOnKeyUp(this)"
									name="ecu[1][contact_number][main]" data-index=1 placeholder="Contact Number" value="" autocomplete="ecu_contact_number[main]" autofocus>
							</div>
						</div>
						<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div">
							<div class="dropdown-option-div">
								<span class="error">* </span>
								<label for="ecu_alternative_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
								<input id="ecu_alternative_number_1" type="tel" class="form-control widthinput @error('ecu_alternative_number[main]') is-invalid @enderror" oninput="validationOnKeyUp(this)"
									name="ecu[1][alternative_contact_number][main]" data-index=1 placeholder="Alternative Number" value="" autocomplete="ecu_alternative_number[main]" autofocus>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xxl-12 col-lg-12 col-md-12">
					<a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn_contact_uae">
					<i class="fa fa-plus" aria-hidden="true"></i> Add</a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Contact in case of Emergency (Home Country)</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="col-md-12 form_field_outer_contact_home p-0" id="emergency_contact_home">
					<div class="row form_field_outer_row">
						<div class="col-xxl-6 col-lg-6 col-md-6">
							<span class="error">* </span>
							<label for="ech_home_country_address" class="col-form-label text-md-end">{{ __('Home Country Address') }}</label>													
							<textarea rows="7" id="ech_home_country_address_1" type="text" class="form-control @error('ech_home_country_address') is-invalid @enderror"
								name="ech[1][home_country_address]" data-index=1 placeholder="Home Country Address" value="{{ old('ech_home_country_address') }}"  autocomplete="ech_home_country_address"
								autofocus></textarea>
						</div>
						<div class="col-xxl-5 col-lg-5 col-md-5 mt-4">
							<div class="row">
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<span class="error">* </span>
									<label for="ech_name" class="col-form-label text-md-end">{{ __('Name') }}</label>
								</div>
								<div class="col-xxl-9 col-lg-9 col-md-9">
									<input id="ech_name_1" type="text" class="widthinput form-control @error('ech_name') is-invalid @enderror"
										name="ech[1][name]" data-index=1 placeholder="Name" value="{{old('ech_name')}}"
										autocomplete="ech_name" autofocus>
								</div>
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<span class="error">* </span>
									<label for="ech_relation" class="col-form-label text-md-end">{{ __('Relation:') }}</label>
								</div>
								<div class="col-xxl-9 col-lg-9 col-md-9">
									<select name="ech[1][relation]" data-index=1 id="ech_relation_1" class="form-control widthinput" onchange="" autofocus>
										<option>Choose Relation</option>
										@foreach($masterRelations as $relation)
										<option value="{{$relation->id}}">{{$relation->name}} </option>
										@endforeach
									</select>
								</div>
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<span class="error">* </span>
									<label for="ech_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
								</div>
								<div class="col-xxl-9 col-lg-9 col-md-9">
									<input id="ech_email_1" type="text" class="form-control widthinput @error('ech_email') is-invalid @enderror"
										name="ech[1][email]" data-index=1
										placeholder="Email" value="" autocomplete="ech_email" autofocus>
								</div>
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<span class="error">* </span>
									<label for="ech_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
								</div>
								<div class="col-xxl-9 col-lg-9 col-md-9 select-button-main-div">
									<div class="dropdown-option-div">
										<input id="ech_contact_number_1" type="tel" class="form-control widthinput @error('ech_contact_number') is-invalid @enderror" 
											name="ech[1][contact_number][main]" data-index=1 oninput="validationOnKeyUp(this)"
											placeholder="Contact Number" value="" autocomplete="ech_contact_number" autofocus>
									</div>
								</div>
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<label for="ech_alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Number') }}</label>
								</div>
								<div class="col-xxl-9 col-lg-9 col-md-9 select-button-main-div">
									<div class="dropdown-option-div">
										<input id="ech_alternative_contact_number_1" type="tel" class="form-control widthinput @error('ech_alternative_contact_number') is-invalid @enderror" 
											name="ech[1][alternative_contact_number][main]" data-index=1 oninput="validationOnKeyUp(this)"
											placeholder="Alternative Contact Number" value="" autocomplete="ech_alternative_contact_number" autofocus>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xxl-12 col-lg-12 col-md-12">
					<a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn_contact_home">
					<i class="fa fa-plus" aria-hidden="true"></i> Add</a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Visa Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="cec_or_person_code_number" class="col-form-label text-md-end">{{ __('CEC / Person Code No') }}</label>
						<input id="cec_or_person_code_number" type="text" class="form-control widthinput @error('cec_or_person_code_number') is-invalid @enderror" name="cec_or_person_code_number"
							placeholder="Enter CEC / Person Code No" value="" autocomplete="cec_or_person_code_number" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="emirates_id" class="col-form-label text-md-end">{{ __('Emirates ID') }}</label>
						<input id="emirates_id" type="text" class="form-control widthinput @error('emirates_id') is-invalid @enderror" name="emirates_id"
							placeholder="Enter Emirates ID" value="" autocomplete="emirates_id" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="emirates_expiry" class="col-form-label text-md-end">{{ __('Emirates ID Expiry') }}</label>
						<input id="emirates_expiry" type="date" class="form-control widthinput @error('emirates_expiry') is-invalid @enderror" name="emirates_expiry"
							placeholder="Enter Emirates ID Expiry" value="" autocomplete="emirates_expiry" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="passport_number" class="col-form-label text-md-end">{{ __('Passport Number') }}</label>
						<input id="passport_number" type="text" class="form-control widthinput @error('passport_number') is-invalid @enderror" name="passport_number"
							placeholder="Enter Passport Number" value="" autocomplete="passport_number" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="passport_place_of_issue" class="col-form-label text-md-end">{{ __('Passport Issued Place') }}</label>
						<input id="passport_place_of_issue" type="text" class="form-control widthinput @error('passport_place_of_issue') is-invalid @enderror" name="passport_place_of_issue"
							placeholder="Enter Passport Issued Place" value="" autocomplete="passport_place_of_issue" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="passport_issue_date" class="col-form-label text-md-end">{{ __('Passport Issued Date') }}</label>
						<input id="passport_issue_date" type="date" min="" class="form-control widthinput @error('passport_issue_date') is-invalid @enderror" name="passport_issue_date"
							value="" autocomplete="passport_issue_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="passport_expiry_date" class="col-form-label text-md-end">{{ __('Passport Expiry Date') }}</label>
						<input id="passport_expiry_date" type="date" min="" class="form-control widthinput @error('passport_expiry_date') is-invalid @enderror" name="passport_expiry_date"
							value="" autocomplete="passport_expiry_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="passport_status" class="col-form-label text-md-end">{{ __('Passport Status') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="passport_status" name="passport_status" value="with_employee" id="with_employee" />
									<label for="with_employee">With Employee</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="passport_status" name="passport_status" value="with_milele" id="with_milele" />
									<label for="with_milele">With Milele</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="passport_status_remarks" class="col-form-label text-md-end">{{ __('Passport Status Remarks') }}</label>
						<input id="passport_status_remarks" type="text" class="form-control widthinput @error('passport_status_remarks') is-invalid @enderror" name="passport_status_remarks"
							placeholder="Enter Passport Status Remarks" value="" autocomplete="passport_status_remarks" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="visa_type" class="col-form-label text-md-end">{{ __('Choose Visa Type') }}</label>
							<select name="visa_type" id="visa_type" class="form-control widthinput" autofocus>
								<option>Choose Visa Type</option>
								@foreach($masterVisaTypes as $masterVisaType)
								<option value="{{$masterVisaType->id}}">{{$masterVisaType->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="visa_number" class="col-form-label text-md-end">{{ __("Visa Number" ) }}</label>
						<input id="visa_number" type="text" class="form-control widthinput @error('visa_number') is-invalid @enderror" name="visa_number"
							placeholder="Enter Visa Number" value="" autocomplete="visa_number" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="visa_issue_date" class="col-form-label text-md-end">{{ __("Visa Issue Date" ) }}</label>
						<input id="visa_issue_date" type="date" class="form-control widthinput @error('visa_issue_date') is-invalid @enderror" name="visa_issue_date"
							placeholder="Visa Issue Date" value="" autocomplete="visa_issue_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="visa_expiry_date" class="col-form-label text-md-end">{{ __("Visa Expiry Date" ) }}</label>
						<input id="visa_expiry_date" type="date" class="form-control widthinput @error('visa_expiry_date') is-invalid @enderror" name="visa_expiry_date"
							placeholder="Visa Expiry Date" value="" autocomplete="visa_expiry_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="reminder_date_for_visa_renewal" class="col-form-label text-md-end">{{ __("Reminder Date for Visa Renewal" ) }}</label>
						<input id="reminder_date_for_visa_renewal" type="date" class="form-control widthinput @error('reminder_date_for_visa_renewal') is-invalid @enderror" name="reminder_date_for_visa_renewal"
							placeholder="Reminder Date for Visa Renewal" value="" autocomplete="reminder_date_for_visa_renewal" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="visa_issueing_country" class="col-form-label text-md-end">{{ __('Choose Visa Issuing Country') }}</label>
							<select name="visa_issueing_country" id="visa_issueing_country" class="form-control widthinput" autofocus>
								<option>Choose Visa Issuing Country</option>
								@foreach($masterNationality as $country)
								<option value="{{$country->id}}">{{$country->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="sponsorship" class="col-form-label text-md-end">{{ __('Sponsorship') }}</label>
							<select name="sponsorship" id="sponsorship" class="form-control widthinput" autofocus>
                            <option>Choose Sponsorship</option>
								@foreach($masterSponcerships as $sponcership)
								<option value="{{$sponcership->id}}">{{$sponcership->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Employment Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="company_joining_date" class="col-form-label text-md-end">{{ __('Company Joining Date') }}</label>
						<input id="company_joining_date" type="date" class="form-control widthinput @error('company_joining_date') is-invalid @enderror" name="company_joining_date"
							placeholder="Company Joining Date" value="" autocomplete="company_joining_date" autofocus>
					</div>
					
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="probation_duration_in_months" class="col-form-label text-md-end">{{ __('Pobation Duration') }}</label>
						<div class="input-group">
							<input id="probation_duration_in_months" type="number" class="form-control widthinput @error('probation_duration_in_months') is-invalid @enderror" name="probation_duration_in_months"
								placeholder="Enter Pobation Duration" value="" autocomplete="probation_duration_in_months" autofocus>                            
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">Months</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="probation_period_start_date" class="col-form-label text-md-end">{{ __('Probation Period Start Date') }}</label>
						<input id="probation_period_start_date" type="date" class="form-control widthinput @error('probation_period_start_date') is-invalid @enderror" name="probation_period_start_date"
							placeholder="Enter Probation Period Start Date" value="" autocomplete="probation_period_start_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="probation_period_end_date" class="col-form-label text-md-end">{{ __('Probation Period End Date') }}</label>
						<input id="probation_period_end_date" type="date" class="form-control widthinput @error('probation_period_end_date') is-invalid @enderror" name="probation_period_end_date"
							placeholder="Enter Probation Period End Date" value="" autocomplete="probation_period_end_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employment_contract_type" class="col-form-label text-md-end">{{ __('Employment Contract Type') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="employment_contract_type" name="employment_contract_type" value="limited_contract" id="limited_contract" />
									<label for="limited_contract">Limited Contract</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="employment_contract_type" name="employment_contract_type" value="unlimited_contract" id="unlimited_contract" />
									<label for="unlimited_contract">Unlimited Contract</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employment_contract_start_date" class="col-form-label text-md-end">{{ __('Employment Contract Start Date') }}</label>
						<input id="employment_contract_start_date" type="date" min="" class="form-control widthinput @error('employment_contract_start_date') is-invalid @enderror" name="employment_contract_start_date"
							value="" autocomplete="employment_contract_start_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employment_contract_end_date" class="col-form-label text-md-end">{{ __('Employment Contract End Date') }}</label>
						<input id="employment_contract_end_date" type="text" class="form-control widthinput @error('employment_contract_end_date') is-invalid @enderror" name="employment_contract_end_date"
							placeholder="Enter Employment Contract End Date" value="" autocomplete="employment_contract_end_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employment_contract_probation_period_in_months" class="col-form-label text-md-end">{{ __('Employment Contract Probation Period') }}</label>
						<div class="input-group">
							<input id="employment_contract_probation_period_in_months" type="number" class="form-control widthinput @error('employment_contract_probation_period_in_months') is-invalid @enderror" name="employment_contract_probation_period_in_months"
								placeholder="Enter Employment Contract Probation Period" value="" autocomplete="employment_contract_probation_period_in_months" autofocus>                            
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">Months</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="employment_contract_probation_end_date" class="col-form-label text-md-end">{{ __('Employment Contract Probation End Date') }}</label>
						<input id="employment_contract_probation_end_date" type="date" class="form-control widthinput @error('employment_contract_probation_end_date') is-invalid @enderror" name="employment_contract_probation_end_date"
							placeholder="Enter Employment Contract Probation End Date" value="" autocomplete="employment_contract_probation_end_date" autofocus>
					</div>
                    <div class="col-xxl-3 col-lg-3 col-md-3 radio-main-div">
						<span class="error">* </span>
						<label for="current_status" class="col-form-label text-md-end">{{ __('Current Status') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="current_status" name="current_status" value="active" id="active" />
									<label for="active">Active</label>
								</div>
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="current_status" name="current_status" value="onleave" id="onleave" />
									<label for="onleave">On Leave</label>
								</div>
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="current_status" name="current_status" value="inactive" id="inactive" />
									<label for="inactive">Inactive</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="status_date" class="col-form-label text-md-end">{{ __('Status Date') }}</label>
						<input id="status_date" type="date" class="form-control widthinput @error('status_date') is-invalid @enderror" name="status_date"
							placeholder="Enter Employee Code" value="" autocomplete="status_date" autofocus>
					</div>
                    <div class="col-xxl-3 col-lg-3 col-md-3 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="location_id" class="col-form-label text-md-end">{{ __('Choose Work Location') }}</label>
							<select name="location_id" id="location_id" class="form-control widthinput" onchange="" autofocus>
                                <option>Choose Work Location</option>
								@foreach($masterOfficeLocations as $masterOfficeLocation)
								<option value="{{$masterOfficeLocation->id}}">{{$masterOfficeLocation->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="designation" class="col-form-label text-md-end">{{ __('Choose Designation') }}</label>
							<select name="designation" id="designation" class="form-control widthinput" autofocus>
								<option>Choose Designation</option>
								@foreach($masterJobPositions as $masterJobPosition)
								<option value="{{$masterJobPosition->id}}">{{$masterJobPosition->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="department" class="col-form-label text-md-end">{{ __('Choose Department') }}</label>
							<select name="department" id="department" class="form-control widthinput" autofocus>
								<option>Choose Department</option>
								@foreach($masterdepartments as $masterdepartment)
								<option value="{{$masterdepartment->id}}">{{$masterdepartment->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					
					<!-- <div class="col-xxl-3 col-lg-3 col-md-3 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="division" class="col-form-label text-md-end">{{ __('Choose Division') }}</label>
							<select name="division" id="division" multiple="true" class="form-control widthinput" onchange="" autofocus>
                                <option>Choose Division</option>
								@foreach($MasterDivisionWithHead as $division)
								<option value="{{$division->id}}">{{$division->name}}</option>
								@endforeach
							</select>
						</div>
					</div> -->
					<div class="col-xxl-4 col-lg-4 col-md-4 radio-main-div" id="change_reporting_manager_div">
						<span class="error">* </span>
						<label for="type" class="col-form-label text-md-end">{{ __('Choose Team Lead /Reporting Manager') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="type" name="team_lead_or_reporting_manager" value="" id="department_head"/>
									<label for="department_head" id="department_head_label"></label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6" id="rep_div">
									<input type="radio" class="type" name="team_lead_or_reporting_manager" value="" id="division_head"/>
									<label for="division_head" id="division_head_label"></label>
								</div>
							</div>
						</fieldset>
					</div>
                    
					
					<!-- <div class="col-xxl-3 col-lg-3 col-md-3 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="division" class="col-form-label text-md-end">{{ __('Choose Division Head') }}</label>
							<select name="division" id="division" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($MasterDivisionWithHead as $division)
								<option value="{{$division->id}}">{{$division->name}}</option>
								@endforeach
							</select>
						</div>
						</div> -->               
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Off Boarding Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="leaving_type" class="col-form-label text-md-end">{{ __('Leaving Type') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="leaving_type" name="leaving_type" value="resigned" id="resigned" />
									<label for="resigned">Resigned</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="leaving_type" name="leaving_type" value="terminated" id="terminated" />
									<label for="terminated">Terminated</label>
								</div>
							</div>
						</fieldset>
					</div>
					
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="notice_period_to_serve" class="col-form-label text-md-end">{{ __('Notice Period to Serve') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="notice_period_to_serve" name="notice_period_to_serve" value="yes" id="yes" />
									<label for="yes">Yes</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="notice_period_to_serve" name="notice_period_to_serve" value="no" id="no" />
									<label for="no">No</label>
								</div>
							</div>
						</fieldset>
					</div>
                    <div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="insurance_cancellation_done" class="col-form-label text-md-end">{{ __('Insurance Cancellation Done') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="insurance_cancellation_done" name="insurance_cancellation_done" value="yes" id="yes" />
									<label for="yes">Yes</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="insurance_cancellation_done" name="insurance_cancellation_done" value="no" id="no" />
									<label for="no">No</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="notice_period_duration" class="col-form-label text-md-end">{{ __('Notice Period Duration') }}</label>
						<div class="input-group">
							<input id="notice_period_duration" type="number" class="form-control widthinput @error('notice_period_duration') is-invalid @enderror" name="notice_period_duration"
								placeholder="Enter Notice Period Duration" value="" autocomplete="notice_period_duration" autofocus>                            
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">Days</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="last_working_day" class="col-form-label text-md-end">{{ __('Last Working Day') }}</label>
						<input id="last_working_day" type="date" class="form-control widthinput @error('last_working_day') is-invalid @enderror" name="last_working_day"
							placeholder="Enter Probation Period Start Date" value="" autocomplete="last_working_day" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="visa_cancellation_received_date" class="col-form-label text-md-end">{{ __('Visa Cancellation Received Date') }}</label>
						<input id="visa_cancellation_received_date" type="date" class="form-control widthinput @error('visa_cancellation_received_date') is-invalid @enderror" name="visa_cancellation_received_date"
							placeholder="Enter Visa Cancellation Received Date" value="" autocomplete="visa_cancellation_received_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="change_status_or_exit_UAE_date" class="col-form-label text-md-end">{{ __('Change Status Date/Exit UAE Date') }}</label>
						<input id="change_status_or_exit_UAE_date" type="date" min="" class="form-control widthinput @error('change_status_or_exit_UAE_date') is-invalid @enderror" name="change_status_or_exit_UAE_date"
							value="" autocomplete="change_status_or_exit_UAE_date" autofocus>
					</div>
					
                    <div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
						<span class="error">* </span>
						<label for="leaving_reason" class="col-form-label text-md-end">{{ __('Leaving Reason') }}</label>
						<textarea rows="4" id="leaving_reason" type="text" class="form-control @error('leaving_reason') is-invalid @enderror"
							name="leaving_reason" placeholder="Leaving Reason" value="{{ old('leaving_reason') }}"  autocomplete="leaving_reason"
							autofocus></textarea>
					</div>
				</div>
			</div>
		</div>
		</br>
		<div class="row">
			<div class="col-xxl-6 col-lg-6 col-md-6" style="float:left;">
				<a id="resetSignature" class="btn btn-sm" style="background-color: lightblue; float:left;">Reset Signature</a>
				<button id="saveSignature" class="btn btn-sm" style="background-color: #fbcc34; float:left; margin-left:10px;">Save Signature</button>     
			</div>
			<div class="col-xxl-6 col-lg-6 col-md-6">
				<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
			</div>
		</div>
	</form>
</div>
<div class="overlay"></div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
</script>
<script type="text/javascript">
    var indexVal = 1;
	$(document).ready(function () {
	    $('#religion').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder:"Choose Religion",
	    });
		$('#nationality').select2({
	        allowClear: true,
			maximumSelectionLength: 1,
	        placeholder:"Choose Nationality",
	    });	
	    $('#language_id').select2({
	        allowClear: true,
	        placeholder:"Choose Spoken Languages",
	    });
        $('#spouse_nationality').select2({
			allowClear: true,
			placeholder:"Choose Spouse Nationality",
		});	
        var company_number = window.intlTelInput(document.querySelector("#company_number"), {
			separateDialCode: true,
			preferredCountries:["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});
        var contact_number = window.intlTelInput(document.querySelector("#contact_number"), {
			separateDialCode: true,
			preferredCountries:["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});
        var residence_telephone_number = window.intlTelInput(document.querySelector("#residence_telephone_number"), {
			separateDialCode: true,
			preferredCountries:["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});
        emergencyContactUAE(1);
		emergencyContactHome(1);
        $("body").on("click",".add_new_frm_field_btn", function () { 
			addChild();
		});
        $("body").on("click",".add_new_frm_field_btn_contact_uae", function () {
			addContactUAE();
        }); 
        $("body").on("click",".add_new_frm_field_btn_contact_home", function () { 
            addContactHome();
        }); 
        $("body").on("click", ".remove_node_btn_frm_field", function () {
			$(this).closest(".form_field_outer_row").remove();
		}); 
        function addChild() {
			var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; 
			$(".form_field_outer").append(`
                <div class="row form_field_outer_row" id="${index}">
                <div class="col-xxl-3 col-lg-6 col-md-6">
                <label for="child_name" class="col-form-label text-md-end">{{ __('Child Name') }}</label>
                <input id="child_name_${index}" type="text" class="child_name form-control widthinput @error('child_name') is-invalid @enderror" name="child[${index}][child_name]"
                placeholder="Child Name" value="" autocomplete="child_name" autofocus data-index="${index}">
                </div>
                <div class="col-xxl-2 col-lg-6 col-md-6">
                <label for="child_passport_number" class="col-form-label text-md-end">{{ __('Child Passport Number') }}</label>
                <input id="child_passport_number_${index}" type="text" class="child_passport_number form-control widthinput @error('child_passport_number') is-invalid @enderror" name="child[${index}][child_passport_number]"
                placeholder="Child Passport Number" value="" autocomplete="child_passport_number" autofocus data-index="${index}">
                </div>
                <div class="col-xxl-2 col-lg-6 col-md-6">
                <label for="child_passport_expiry_date" class="col-form-label text-md-end">{{ __('Child Passport Expiry Date') }}</label>
                <input id="child_passport_expiry_date_${index}" type="date" class="form-control widthinput @error('child_passport_expiry_date') is-invalid @enderror" name="child[${index}][child_passport_expiry_date]"
                value="" autocomplete="child_passport_expiry_date" autofocus data-index="${index}">
                </div>
                <div class="col-xxl-2 col-lg-6 col-md-6">
                <label for="child_dob" class="col-form-label text-md-end">{{ __('Child Date Of Birth') }}</label>
                <input id="child_dob_${index}" type="date" class="form-control widthinput @error('child_dob') is-invalid @enderror" name="child[${index}][child_dob]"
                value="" autocomplete="child_dob" autofocus data-index="${index}">
                </div>
                <div class="col-xxl-2 col-lg-6 col-md-6">
                <label for="child_nationality" class="col-form-label text-md-end">{{ __('Child Nationality') }}</label>
                <select name="child[${index}][child_nationality]" id="child_nationality_${index}" class="form-control widthinput" onchange="" autofocus data-index="${index}">
                <option></option>
                @foreach($masterNationality as $nationality)
                <option>Choose Child Nationality</option>
                <option value="{{$nationality->id}}">{{$nationality->nationality ?? $nationality->name}} </option>
                @endforeach
                </select>
                </div>
                <div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
                <a class="btn_round remove_node_btn_frm_field" title="Remove Row">
                <i class="fas fa-trash-alt"></i>
                </a>
                </div>
                </div>
			`); 
            // document.getElementById("child_dob_"+index).max = today;
            // $("#child_name_"+index).rules('add', {
            //     lettersonly: true,
            //     required: function(element) {
            //         if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#child_dob_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#child_nationality_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#child_passport_number_"+index).rules('add', {
            //     validPassport: true,
            //     required: function(element) {
            //         if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#child_passport_expiry_date_"+index).rules('add', {
            //     required: function(element) {
            //         if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#child_dob_"+index).rules('add', {
            //     required: function(element) {
            //         if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#child_name_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#child_nationality_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#child_nationality_"+index).rules('add', {
            //     required: function(element) {
            //         if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#child_name_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#child_dob_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
		}
        function addContactUAE() {
            var index = indexVal+1;
            indexVal = indexVal+1;
			$(".form_field_outer_contact_uae").append(`
                <div class="row form_field_outer_row" id="emergency_uae_"+${index}>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="ecu_name" class="ecu_name col-form-label text-md-end">{{ __('Name') }}</label>
                        <input id="ecu_name_${index}" type="text" class="form-control widthinput @error('ecu_name') is-invalid @enderror" 
                        name="ecu[${index}][name]" data-index=${index} placeholder="emergency Contact Person UAE" value="" autofocus>
                    </div>
                    <div class="col-xxl-2 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="ecu_relation" class="col-form-label text-md-end">{{ __('Relation') }}</label>
                        <select name="ecu[${index}][relation]" data-index=${index} id="ecu_relation_${index}" class="form-control widthinput" autofocus>
                            <option>Choose Relation</option>
                            @foreach($masterRelations as $relation)
                            <option value="{{$relation->id}}">{{$relation->name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="ecu_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                        <input id="ecu_email_${index}" type="text" class="form-control widthinput @error('ecu_email') is-invalid @enderror" 
                        name="ecu[${index}][email_address]" data-index=${index} placeholder="Email" value="" autocomplete="ecu_email" autofocus>
                    </div>
                    <div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div">
                                                    <div class="dropdown-option-div">
                        <span class="error">* </span>
                        <label for="ecu_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                        <input id="ecu_contact_number_${index}" type="tel" class="form-control widthinput @error('ecu_contact_number[main]') is-invalid @enderror" 
                        oninput="validationOnKeyUp(this)" name="ecu[${index}][contact_number][main]" data-index=${index} placeholder="Contact Number"  value="" autocomplete="ecu_contact_number[main]" autofocus>
                    </div>
                    </div>
                    <div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div">
                                                    <div class="dropdown-option-div">
                        <label for="ecu_alternative_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                        <input id="ecu_alternative_number_${index}" type="tel" class="form-control widthinput @error('ecu_alternative_number[main]') is-invalid @enderror" 
                        oninput="validationOnKeyUp(this)" name="ecu[${index}][alternative_contact_number][main]" data-index=${index} placeholder="Alternative Number"  value="" autocomplete="ecu_alternative_number[main]" autofocus>
                    </div>
                    </div>
                    <div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
                        <a class="btn_round remove_node_btn_frm_field" title="Remove Row">
                        <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
			`); 
			emergencyContactUAE(index);
		}
        function addContactHome() {
			var index = $(".form_field_outer_contact_home").find(".form_field_outer_row").length + 1; 
			$(".form_field_outer_contact_home").append(`
                <div class="row form_field_outer_row">
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="ech_home_country_address" class="col-form-label text-md-end">{{ __('Home Country Address') }}</label>													
                        <textarea rows="7" id="ech_home_country_address_${index}" type="text" class="form-control @error('ech_home_country_address') is-invalid @enderror"
                            name="ech[${index}][home_country_address]" data-index=${index} placeholder="Home Country Address" value="{{ old('ech_home_country_address') }}"  autocomplete="ech_home_country_address"
                            autofocus></textarea>
                    </div>
                    <div class="col-xxl-5 col-lg-5 col-md-5 mt-4">
                        <div class="row">
                            <div class="col-xxl-3 col-lg-3 col-md-3">
                                <span class="error">* </span>
                                <label for="ech_name" class="col-form-label text-md-end">{{ __('Name') }}</label>
                            </div>
                            <div class="col-xxl-9 col-lg-9 col-md-9">
                                <input id="ech_name_${index}" type="text" class="widthinput form-control @error('ech_name') is-invalid @enderror"
                                    name="ech[${index}][name]" data-index=${index} placeholder="Name" value="{{old('ech_name')}}"
                                    autocomplete="ech_name" autofocus>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-3">
                                <span class="error">* </span>
                                <label for="ech_relation" class="col-form-label text-md-end">{{ __('Relation:') }}</label>
                            </div>
                            <div class="col-xxl-9 col-lg-9 col-md-9">
                                <select name="ech[${index}][relation]" data-index=${index} id="ech_relation_${index}" class="form-control widthinput" onchange="" autofocus>
                                    <option>Choose Relation</option>
                                    @foreach($masterRelations as $relation)
                                    <option value="{{$relation->id}}">{{$relation->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-3">
                                <span class="error">* </span>
                                <label for="ech_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                            </div>
                            <div class="col-xxl-9 col-lg-9 col-md-9">
                                <input id="ech_email_${index}" type="text" class="form-control widthinput @error('ech_email') is-invalid @enderror"
                                    name="ech[${index}][email]" data-index=${index}
                                    placeholder="Email" value="" autocomplete="ech_email" autofocus>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-3">
                                <span class="error">* </span>
                                <label for="ech_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                            </div>
                            <div class="col-xxl-9 col-lg-9 col-md-9 select-button-main-div">
                                                    <div class="dropdown-option-div">
                                <input id="ech_contact_number_${index}" type="tel" class="form-control widthinput @error('ech_contact_number') is-invalid @enderror" 
                                name="ech[${index}][contact_number][main]" data-index=${index} oninput="validationOnKeyUp(this)"
                                    placeholder="Contact Number" value="" autocomplete="ech_contact_number" autofocus>
                            </div>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-3">
                                <label for="ech_alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Number') }}</label>
                            </div>
                            <div class="col-xxl-9 col-lg-9 col-md-9 select-button-main-div">
                                                    <div class="dropdown-option-div">
                                <input id="ech_alternative_contact_number_${index}" type="tel" class="form-control widthinput @error('ech_alternative_contact_number') is-invalid @enderror" 
                                name="ech[${index}][alternative_contact_number][main]" data-index=${index} oninput="validationOnKeyUp(this)"
                                    placeholder="Alternative Contact Number" value="" autocomplete="ech_alternative_contact_number" autofocus>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
                        <a class="btn_round remove_node_btn_frm_field" title="Remove Row">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>						
			    </div>
			`); 
			emergencyContactHome(index);
		}
        function emergencyContactUAE(index) {                   
            var emergency_uae_contact = window.intlTelInput(document.querySelector("#ecu_contact_number_"+index), {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "full",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
            var emergency_uae_alternative = window.intlTelInput(document.querySelector("#ecu_alternative_number_"+index), {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "full",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
            // $("#ech_home_country_address_"+index).rules('add', {
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ecu_alternative_number_"+index).rules('add', {
            //     minlength: 5,
            //     maxlength: 20,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ecu_name_"+index).rules('add', {
            //     lettersonly: true,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ecu_relation_"+index).rules('add', {
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ecu_email_"+index).rules('add', {
            //     email:true,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ecu_contact_number_"+index).rules('add', {
            //     minlength: 5,
            //     maxlength: 20,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ecu_alternative_number_"+index).rules('add', {
            //     minlength: 5,
            //     maxlength: 20,
            // });
        }
        function emergencyContactHome(index) {                   
            var emergency_uae_contact = window.intlTelInput(document.querySelector("#ech_contact_number_"+index), {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "full",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
            var emergency_uae_alternative = window.intlTelInput(document.querySelector("#ech_alternative_contact_number_"+index), {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "full",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
            // $("#ech_name_"+index).rules('add', {
            //     lettersonly: true,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ech_relation_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ech_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ech_relation_"+index).rules('add', {
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ech_name_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ech_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ech_email_"+index).rules('add', {
            //     email:true,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ech_name_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ech_relation_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ech_contact_number_"+index).rules('add', {
            //     minlength: 5,
            //     maxlength: 20,
            //     required: function(element) {
            //         if(element.getAttribute('data-index') == 1) {
            //             return true;
            //         }
            //         else if($("#ech_name_"+element.getAttribute('data-index')).val().length > 0) {                                
            //             return true;
            //         }
            //         else if($("#ech_relation_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_email_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
            //             return true;
            //         }
            //         else {
            //             return false;
            //         }
            //     },
            // });
            // $("#ech_alternative_contact_number_"+index).rules('add', {
            //     minlength: 5,
            //     maxlength: 20,
            // });
        }
	});
</script>
@endsection