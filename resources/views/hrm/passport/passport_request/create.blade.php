@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
	.select-error,
	.other-error {
	color: red;
	}
	.btn.btn-success.btncenter {
	width: 10%;
	}
	.job-description-label-div {
	margin-top: 20px !important;
	}
	.col-form-label {
	padding-bottom: 0px;
	}
	.dep-section-div,
	.title-section-div,
	.reporting-section-div {
	margin-top: 20px !important;
	}
	.job-description-lable-name,
	.job-description-lable-name-1 {
	font-size: 16px;
	display: flex;
	align-items: center;
	}
	.job-description-textfield-lable,
	.job-description-text-value
	{
	font-size: 16px;
	display: flex;
	align-items: center;
	justify-content: center;
	}
	@media (max-width: 991px) {
	.date-section-div {
	padding-top: 10px;
	}
	}
	@media (max-width: 767px) {
	.date-section-div {
	padding-top: 10px;
	}
	}
	@media (max-width: 991px) {
	.location-section-div {
	padding-top: 10px;
	}
	}
	.error {
	color: #FF0000;
	}
	.error-text {
	color: #FF0000;
	}
	@media (max-width: 425px) {
	.heading-name {
	font-size: 14px !important;
	}
	.job-description-text-value {
	font-size: 13px !important;
	}
	}
	.submit-passport-section-div {
	padding: 10px;
	}
	.other-checklist-div {
	padding: 5px 20px;
	}
	.submit-passport-para {
	padding: 25px 0px 0px 10px;
	}
	li.list-group-item {
	border: none;
	}
	.btn.btn-success.btncenter {
	width: 10%;
	}
	.col-form-label {
	padding-bottom: 0px;
	}
	.form-label[for="basicpill-firstname-input"] {
	margin-top: 12px;
	margin-left: 10px;
	}
	.heading-name,
	.job-desc-top-info {
	margin-left: 10px;
	}
	.passport-request-lable-name,
	.passport-request-lable-name-1 {
	font-size: 16px;
	display: flex;
	align-items: center;
	}
	.passport-request-lable-name-1 {
	margin-top: 20px;
	}
	.other-input-container {
	padding: 10px 0px 13px 20px;
	}
	.passportSubmitReleaseDropDownInputContainer,
	.other-container-div {
	margin-left: 18px;
	}
	.passportSubmitReleaseDropDownInputContainer {
	margin-top: 30px;
	}
	input.job-desc-signature {
	padding: 60px 0;
	}
	.job-desc-signature-name {
	display: flex;
	text-align: center;
	justify-items: center;
	}
	.top-margin-input {
	margin-top: 1px !important;
	}
	div.col-lg-6.col-md-6.col-6.manager-1 {
	padding-right: 0px;
	}
	div.col-lg-6.col-md-6.col-6.manager-2 {
	padding-left: 0px;
	}
	.btn.btn-success.btncenter {
	background-color: #28a745;
	color: #fff;
	border: none;
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
	@media (max-width: 787px) {
	.btn.btn-success.btncenter {
	width: 30%;
	}
	.job-desc-top-info {
	margin-left: 1px;
	}
	}
	@media (max-width: 767px) {
	.emp-id-section-div,
	.mobile-num-section-div,
	.location-section-div,
	.sign-date-section-div {
	margin: 20px 0px 0px 0px !important;
	}
	.passportSubmitReleaseDropDownInputContainer {
	margin-left: 1px;
	}
	}
	.error {
	color: #FF0000;
	}
	.error-text {
	color: #FF0000;
	}
	@media (max-width: 425px) {
	.approvals-managers {
	font-size: smaller;
	}
	.heading-name {
	font-size: smaller !important;
	}
	}
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-passport-request','current-user-create-passport-request']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Create Passport Request Form</h4>
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
	<div class="row">
		<p><span style="float:right;" class="error">* Required Field</span></p>
	</div>
	<form id="employeePassportRequestForm" name="employeePassportRequestForm" enctype="multipart/form-data" method="POST" action="{{route('employee-passport_request.store-or-update', $id)}}">
		@csrf
		<div class="row">
			<div class="col-lg-12 job-desc-top-info">
				<div class="row">
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="employee_id" class="form-label heading-name"><span class="error">* </span>{{ __('Employee Name') }}</label>
							<select name="employee_id" id="employee_name_id" class="form-control widthinput" multiple="true" autofocus>
								@foreach($masterEmployees as $User)
								<option value="{{ $User->id }}">{{ $User->name ?? ''}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<br />
				<div class="row passport-request-details-div" style="display: none;">
					<div class="col-lg-4 col-md-4 col-sm-4 col-6 title-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="employee_code_id" class="col-form-label widthinput heading-name"><b>Employee Code</b></label>
						</div>
						<div class="col-12 job-description-text-value">
							<div name="employee_code_id" class="employee-code-id"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-6 dep-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="emp_designation" class="col-form-label widthinput heading-name"><b>Designation</b></label>
						</div>
						<div class="col-12 job-description-text-value">
							<div name="emp_designation" class="emp-designation"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-6 reporting-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="emp_mobile_num" class="col-form-label widthinput heading-name"><b>Mobile No.</b></label>
						</div>
						<div class="job-description-text-value">
							<div name="emp_mobile_num" class="emp-mobile-num"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-6 dep-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="emp_department" class="col-form-label widthinput heading-name"><b>Department</b></label>
						</div>
						<div class="col-12 job-description-text-value">
							<div name="emp_department" class="emp-department"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-6 reporting-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="emp_job_location" class="col-form-label widthinput heading-name"><b>Location</b></label>
						</div>
						<div class="job-description-text-value">
							<div name="emp_job_location" class="emp-job-location"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br />
		<div class="col-lg-12 col-md-12 col-sm-12 col-12 passportSubmitReleaseDropDownInputContainer" id="passportSubmitReleaseDropDownInputContainer" style="display: none;">
			<hr />
			<label for="choose-passport-req" class="form-label"><span class="error">* </span><b>Choose your option for the passport:</b></label>
			<div class="col-lg-4 col-md-6 col-sm-12 col-12">
				<select name="passport_request_dropdown" id="passport_request_dropdown" class="form-control widthinput" disabled
					>
					<option value="with_employee">Submission of Passport</option>
					<option value="with_company">Release of Passport</option>
				</select>
			</div>
			<br />
			<div class="col-lg-12 ">
				<div class="submitPassportInputContainer" id="submitPassportInputContainer" style="display: none;">
					<div class="submit-passport-section-div">
						<div>
							<h6>Submit Passport:</h6>
						</div>
						<p class="form-label"><span class="error">* </span><strong>I, the undersigned,</strong> consent to authorize
							the Human Resource Department to take possession of my passport for purposes of :
						</p>
						<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
							<div class="dropdown-option-div">
								<select name="purposes_of_submit" id="purposes_of_submit_id" class="form-control widthinput" multiple="true" autofocus>
									@foreach($submissionPurpose as $PassportRequestPurpose)
									<option value="{{ $PassportRequestPurpose->id }}">{{ $PassportRequestPurpose->name ?? ''}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-12">
							<p class="submit-passport-para">However, I can withdraw my passport when required by fulfilling the necessary requirements</p>
						</div>
					</div>
				</div>
				<div class="releasePassportInputContainer" id="releasePassportInputContainer" style="display: none;">
					<div class="release-passport-section-div">
						<div>
							<h6>Release Passport:</h6>
						</div>
						<p class="form-label"><span class="error">* </span><strong>I, the undersigned,</strong> consent to authorize
							the Human Resource Department to take possession of my passport for purposes of :
						</p>
						<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
							<div class="dropdown-option-div">
								<select name="purposes_of_release" id="purposes_of_release_id" class="form-control widthinput" multiple="true" autofocus>
									@foreach($releasePurpose as $PassportRequestPurpose)
									<option value="{{ $PassportRequestPurpose->id }}">{{ $PassportRequestPurpose->name ?? ''}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="other-specific-passport-release-option" style="display: none;">
							<div class=" col-lg-4 col-md-6 col-sm-6 ">
								<label for="release_purpose" class="form-label"> Specify, if Others</label>
								<input type="text" placeholder="Please Specify Other" name="release_purpose" class="form-control" id="other_release_purpose" value="">
							</div>
						</div>
						<div class="col-lg-10 col-md-10 col-sm-10 col-12">
							<p class="submit-passport-para">However, I can withdraw my passport when required by fulfilling the necessary requirements</p>
						</div>
					</div>
				</div>
				<hr />
			</div>
		</div>
		</br>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
</br>
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
	$(document).ready(function() {
	
	    var data = <?php echo json_encode($masterEmployees); ?>;
	    console.log("Passport Request Form User data  -----", data);
	
	    $('#employee_name_id').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder: "Choose Employee Name",
	    });
	
	    $('#purposes_of_submit_id').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder: "Choose Passport Submission Purpose",
	    });
	
	    $('#purposes_of_release_id').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder: "Choose Passport Release Purpose",
	    });
	
	
	    function togglePassportRequestDetailsDiv() {
	        var selectedEmpId = $('#employee_name_id').val();
	        console.log("Selected emp id value in hidden shown div is : ", selectedEmpId)
	
	        if (selectedEmpId && selectedEmpId.length > 0) {
	            $('.passport-request-details-div').show();
	        } else {
	            $('.passport-request-details-div').hide();
	        }
	    }
	
	    function setEmpNameOnReload() {
	        for (var i = 0; i < data.length; i++) {
	            $('#employee_name_id').val([data[i].id]).trigger('change');
	            console.log("Emp code is ; ", data[i].emp_profile?.employee_code || '')
	            $('.employee-code-id').text(data[i].emp_profile?.employee_code || '');
	            $('.emp-designation').text(data[i].emp_profile?.designation?.name || '');
	            $('.emp-mobile-num').text(data[i].emp_profile?.contact_number || '');
	            $('.emp-department').text(data[i].emp_profile?.department?.name || '');
	            $('.emp-job-location').text(data[i].emp_profile?.location?.name || '');
	            $('#passport_request_dropdown').val(data[i].passport_with || '').trigger('change');
	            togglePassportRequestDetailsDiv();
	            break;
	        }
	    }
	
	
	    function updateFieldsBasedOnEmpId(selectedEmpId) {
	        for (var i = 0; i < data.length; i++) {
	            if (data[i].id == selectedEmpId) {
	                $('.employee-code-id').text(data[i].emp_profile?.employee_code || '');
	                $('.emp-designation').text(data[i].emp_profile?.designation?.name || '');
	                $('.emp-mobile-num').text(data[i].emp_profile?.contact_number || '');
	                $('.emp-department').text(data[i].emp_profile?.department?.name || '');
	                $('.emp-job-location').text(data[i].emp_profile?.location?.name || '');
	                if(data[i].emp_profile.passport_status == 'with_milele') { 
	                    $('#passport_request_dropdown').val('with_company' || '').trigger('change');
	                }
	                else if(data[i].emp_profile.passport_status == 'with_employee') {
	                    $('#passport_request_dropdown').val('with_employee' || '').trigger('change');
	                }
	                showPassportRequestInput();
	                break;
	            }
	        }
	    }
	
	
	    function clearFields() {
	
	        var container = $('#passportSubmitReleaseDropDownInputContainer');
	        container.hide();
	        $('.employee-code-id').text('');
	        $('.emp-designation').text('');
	        $('.emp-mobile-num').text('');
	        $('.emp-department').text('');
	        $('.emp-job-location').text('');
	        $('.passport_request_dropdown').val('');
	        $('.passport-submit-release-main-div').hide();
	        $('.passportSubmitReleaseDropDownInputContainer').hide();
	
	    }
	
	    function showPassportRequestInput() {
	        console.log("Dropdown changed!");
	
	        var container = $('#passportSubmitReleaseDropDownInputContainer');
	
	        if ($('#passport_request_dropdown').length) {
	            var selectedValue = $('#passport_request_dropdown').val();
	
	            $('#submitPassportInputContainer').toggle(selectedValue == 'with_employee');
	            $('#releasePassportInputContainer').toggle(selectedValue == 'with_company');
	
	            if (selectedValue == 'with_employee' || selectedValue == 'with_company') {
	                container.show();
	                $('#purposes_of_submit_id').select2({
	                    allowClear: true,
	                    maximumSelectionLength: 1,
	                    placeholder: "Choose Passport Submission Purpose",
	                });
	
	                $('#purposes_of_release_id').select2({
	                    allowClear: true,
	                    maximumSelectionLength: 1,
	                    placeholder: "Choose Passport Release Purpose",
	                });
	            } else {
	                container.hide();
	            }
	        } else {
	            console.error("Dropdown element not found!");
	        }
	    }
	
	    $('#employee_name_id').on('change', function() {
	        var selectedEmpId = $(this).val();
	        console.log("selected value of emp id -------------------- in on change 1 is ", selectedEmpId);
	        togglePassportRequestDetailsDiv();
	        var fieldName = $(this).attr('name');
	        $('#employeePassportRequestForm').validate().element('[name="' + fieldName + '"]');
	
	        if (!selectedEmpId || selectedEmpId.length === 0) {
	            console.log("In Null emp id")
	            clearFields();
	        } else {
	            updateFieldsBasedOnEmpId(selectedEmpId);
	        }
	    });
	    $('#passport_request_dropdown').on('change', showPassportRequestInput);
	
	    $('#purposes_of_release_id').on('change', function() {
	        var selectedValue = $(this).val();
	        if (selectedValue == 13) {
	            $('.other-specific-passport-release-option').show();
	        } else {
	            $('.other-specific-passport-release-option').hide();
	        }
	
	        var fieldName = $(this).attr('name');
	        $('#employeePassportRequestForm').validate().element('[name="' + fieldName + '"]');
	    });
	
	    $('#purposes_of_submit_id').on('change', function() {
	        var fieldName = $(this).attr('name');
	        $('#employeePassportRequestForm').validate().element('[name="' + fieldName + '"]');
	    });
	
	
	    $('#employeePassportRequestForm').submit(function(event) {
	
	        console.log("Data to be sent:", $(this).serialize());
	    });
	
	    $('#employeePassportRequestForm').validate({
	        rules: {
	            employee_id: {
	                required: true,
	            },
	            purposes_of_submit: {
	                required: true,
	            },
	            purposes_of_release: {
	                required: true,
	            },
	            "passport_submit[]": {
	                required: true,
	            },
	            "passport_release[]": {
	                required: true,
	            },
	            release_purpose: {
	                required: true,
	            }
	        },
	
	        errorPlacement: function(error, element) {
	            console.log("Error placement function called");
	
	            if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
	                if (!element.val() || element.val().length === 0) {
	                    console.log("Error is here with length", element.val().length);
	                    error.addClass('select-error');
	                    error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	                } else {
	                    console.log("No error");
	                }
	            } else {
	                error.addClass('other-error');
	                error.insertAfter(element);
	            }
	        },
	    });
	
	});
</script>
@endpush