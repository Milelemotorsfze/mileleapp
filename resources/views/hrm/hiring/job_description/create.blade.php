@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
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
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('create-job-description');
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">{{$jobDescriptionId === 'new' ? "Create" : "Edit"}} Job Description Form</h4>
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
	<div class="row">
		<p><span style="float:right;" class="error">* Required Field</span></p>
	</div>
	<form id="employeeJobDescriptionForm" name="employeeJobDescriptionForm" enctype="multipart/form-data" method="POST" action="{{route('employee-hiring-job-description.store-or-update', $jobDescriptionId )}}">
		@csrf
		<div class="row">
			<div class="col-lg-12 job-desc-top-info">
				<div class="row">
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="uuid" class="form-label heading-name"><span class="error">* </span>{{ __('UUID Number:') }}</label>
							<select name="hiring_request_id" id="uuid_value" class="form-control widthinput" multiple="true" autofocus>
								@foreach($allHiringRequests as $hiringRequests)
								<option value="{{$hiringRequests->id}}">{{$hiringRequests->uuid}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="location_id" class="form-label widthinput heading-name"><span class="error">* </span>Location:</label>
							<select name="location_id" id="location_name" class="form-control widthinput" multiple="true" autofocus>
								@foreach($masterOfficeLocations as $masterOfficeLocation)
								<option value="{{$masterOfficeLocation->id}}">{{$masterOfficeLocation->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class=" col-lg-4 col-md-6 col-sm-6 date-section-div">
						<label for="request_date" class="form-label text-md-end"><span class="error">* </span> {{ __('Choose Date') }}</label>
						<input type="text" name="request_date" id="requested_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2" value="{{$jobDescription->request_date}}">
					</div>
				</div>
				<div class="row job-description-details-div" style="display: none;">
					<div class="col-lg-4 col-md-4 col-sm-4 col-12 title-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="job_title" class="col-form-label widthinput heading-name"><b>Job Title</b></label>
						</div>
						<div class="col-12 job-description-text-value">
							<div name="job_title" class="job-title"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-12 dep-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="department_id" class="col-form-label widthinput heading-name"><b>Department</b></label>
						</div>
						<div class="col-12 job-description-text-value">
							<div name="department_id" class="department-id"></div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-12 reporting-section-div">
						<div class="col-12 job-description-textfield-lable">
							<label for="reporting_to" class="col-form-label widthinput heading-name"><b>Reporting To</b></label>
						</div>
						<div class="job-description-text-value">
							<div name="reporting_to" class="reporting-to"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="job-description-label-div">
				<div class="col-lg-12  job-description-lable-name-1">
					<label for="job_purpose" class="form-label heading-name"><span class="error">* </span>Job Purpose</label>
				</div>
				<div class="col-lg-12  ">
					<textarea cols="25" rows="3" class="form-control" name="job_purpose" placeholder="Job Purpose">{{$jobDescription->job_purpose ?? ''}}</textarea>
				</div>
			</div>
			<div class="job-description-label-div">
				<div class="col-lg-12  job-description-lable-name-1">
					<label for="duties_and_responsibilities" class="form-label heading-name"><span class="error">* </span>Duties and Responsibilities (Generic) of the position </label>
				</div>
				<div class="col-lg-12  ">
					<textarea cols="25" rows="7" class="form-control" name="duties_and_responsibilities" placeholder="Duties and Responsibilities">{{$jobDescription->duties_and_responsibilities ?? ''}}</textarea>
				</div>
			</div>
			<div class="job-description-label-div">
				<div class="col-lg-12  job-description-lable-name-1">
					<label for="skills_required" class="form-label heading-name"><span class="error">* </span>Skills required to fulfil the position </label>
				</div>
				<div class="col-lg-12  ">
					<textarea cols="25" rows="7" class="form-control" name="skills_required" placeholder="Required Skills">{{$jobDescription->skills_required ?? ''}}</textarea>
				</div>
			</div>
			<div class="job-description-label-div">
				<div class="col-lg-12  job-description-lable-name-1">
					<label for="position_qualification" class="form-label heading-name"><span class="error">* </span>Position Qualification (Academic & Professional) </label>
				</div>
				<div class="col-lg-12  ">
					<textarea cols="25" rows="7" class="form-control" name="position_qualification" placeholder="Position Qualification">{{$jobDescription->position_qualification ?? ''}}</textarea>
				</div>
			</div>
		</div>
		<br />
		<div class="col-lg-12 col-md-12 col-sm-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script><script type="text/javascript">
	var data = <?php echo json_encode($allHiringRequests); ?>;
	var jobDescriptionss = <?php echo json_encode($jobDescription); ?>;
	var jobDescriptionLocationId = jobDescriptionss.location_id
	console.log("JD iiis -----", jobDescriptionss.re)
	var requestDate = new Date(jobDescriptionss.request_date);
	
	var currentHiringRequestValue = <?php echo json_encode($currentHiringRequest); ?>;
	console.log("Before if current hiring req", currentHiringRequestValue)
	if (currentHiringRequestValue !== null) {
	    var currentHiringRequestId = currentHiringRequestValue.id;
	    var uuidValue = currentHiringRequestValue.uuid
	    console.log("Current Data --- ;", currentHiringRequestValue);
	}
	
	$(document).ready(function() {
	
	    var selectedUUID = $('#uuid_value').val();
	    updateFieldsBasedOnUUID(selectedUUID);
	
	    $('#location_name').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder: "Location",
	    });
	    $('#uuid_value').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder: "Choose uuid",
	    });
	
	    if (requestDate) {
	
	        $("#requested_date").datepicker({
	            dateFormat: "dd-M-yy",
	            minDate: 0, 
	            changeMonth: true,
	            // showAnim: "explode",
	            showAnim: "slideDown",
	            autoSize: true,
	            onSelect: function(selectedDate) {
	                console.log("Selected Date: ", selectedDate);
	            }
	        }).datepicker('setDate', requestDate);
	        $('#formatted_date').text(requestDate.toLocaleDateString('en-GB', {
	            day: 'numeric',
	            month: 'short',
	            year: 'numeric'
	        }));
	    } else {
	        $("#requested_date").datepicker({
	            dateFormat: "dd-M-yy",
	            minDate: 0, 
	            changeMonth: true,
	            // showAnim: "explode",
	            showAnim: "slideDown",
	            autoSize: true,
	            onSelect: function(selectedDate) {
	                console.log("Selected Date: ", selectedDate);
	            }
	        }).datepicker('setDate', '0');
	    }
	
	    function convertToBackendFormat(displayedDate) {
	        var parsedDate = $.datepicker.parseDate('dd-M-yy', displayedDate);
	        var formattedDate = $.datepicker.formatDate('yy-mm-dd', parsedDate);
	        return formattedDate;
	    }
	
	
	    // Execute with changing uuid value 
	
	    function toggleJobDescriptionDetailsDiv() {
	        var selectedUUID = $('#uuid_value').val();
	        console.log("Selected uuid value in hidden shown div is : ", selectedUUID)
	
	        if (selectedUUID && selectedUUID.length > 0) {
	            $('.job-description-details-div').show();
	        } else {
	            $('.job-description-details-div').hide();
	        }
	    }
	
	    // Execute function when there is data in currentHiringRequestId
	    function setUUIDValueOnReload() {
	        if (currentHiringRequestId) {
	            for (var i = 0; i < data.length; i++) {
	                if (data[i].id == currentHiringRequestId) {
	                    $('#uuid_value').val([data[i].id]).trigger('change');
	                    $('.job-title').text(data[i].questionnaire.designation.name || '');
	                    $('.department-id').text(data[i].questionnaire.department.name || '');
	                    $('.reporting-to').text(data[i].department_head_name || '');
	
	                    if (jobDescriptionLocationId) {
	                        console.log("in JD location value of updated location")
	                        var workLocationId = jobDescriptionLocationId;
	                    } else {
	                        console.log("In old data tbale location value")
	                        var workLocationId = data[i].questionnaire.work_location.id;
	
	                    }
	                    updateLocationDropdown(workLocationId);
	
	                    toggleJobDescriptionDetailsDiv();
	                    break;
	                }
	            }
	        }
	    }
	    setUUIDValueOnReload();
	
	    // Execute function when there is change in uuid value
	
	    function updateFieldsBasedOnUUID(selectedUUID) {
	        for (var i = 0; i < data.length; i++) {
	            if (data[i].id == selectedUUID) {
	                $('.job-title').text(data[i].questionnaire.designation.name || '');
	                $('.department-id').text(data[i].questionnaire.department.name || '');
	                $('.reporting-to').text(data[i].department_head_name || '');
	
	                var workLocationId = data[i].questionnaire.work_location.id;
	                updateLocationDropdown(workLocationId);
	                break;
	            }
	        }
	    }
	
	    // Update the location from dropdown
	
	    function updateLocationDropdown(locationId) {
	        $('#location_name').val([locationId]).trigger('change');
	    }
	
	    function clearFields() {
	        $('.job-title').text('');
	        $('.department-id').text('');
	        $('.reporting-to').text('');
	        $('#location_name').val(null).trigger('change');
	    }
	
	    $('#uuid_value').on('change', function() {
	        var selectedUUID = $(this).val();
	        console.log("selected value of uuid -------------------- in loop is ", selectedUUID)
	        toggleJobDescriptionDetailsDiv();
	        var fieldName = $(this).attr('name');
	        $('#employeeJobDescriptionForm').validate().element('[name="' + fieldName + '"]');
	
	        if (!selectedUUID || selectedUUID.length === 0) {
	            console.log("In Null uuid")
	            clearFields();
	        } else {
	            updateFieldsBasedOnUUID(selectedUUID);
	        }
	    });
	
	    $('#location_name').on('change', function() {
	        var fieldName = $(this).attr('name');
	        $('#employeeJobDescriptionForm').validate().element('[name="' + fieldName + '"]');
	    });
	
	    $('#requested_date').on('change', function() {
	        var newDate = $(this).val();
	        console.log("New Date: ", newDate);
	    });
	
	    $('#employeeJobDescriptionForm').submit(function(event) {
	        var displayedDate = $('#requested_date').val();
	        var formattedDate = convertToBackendFormat(displayedDate);
	        $('#requested_date').val(formattedDate);
	
	        console.log("Data to be sent:", $(this).serialize());
	        // event.preventDefault();
	    });
	
	    $('#employeeJobDescriptionForm').validate({
	        rules: {
	            request_date: {
	                required: true,
	            },
	            hiring_request_id: {
	                required: true,
	            },
	            location_id: {
	                required: true,
	            },
	            job_purpose: {
	                required: true,
	            },
	            duties_and_responsibilities: {
	                required: true,
	            },
	            skills_required: {
	                required: true,
	            },
	            position_qualification: {
	                required: true,
	            },
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