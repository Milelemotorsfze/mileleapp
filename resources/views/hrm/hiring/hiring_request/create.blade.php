@extends('layouts.main')
@include('layouts.formstyle')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','edit-current-user-hiring-request']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">@if($id == 'new')Create New @else Edit @endif Employee Hiring Request</h4>
	@if($id != 'new')
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.create-or-edit',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.create-or-edit',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	@endif
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee-hiring-request.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="employeeHiringRequestForm" name="employeeHiringRequestForm" enctype="multipart/form-data" method="POST" action="{{route('employee-hiring-request.store-or-update',$id)}}">
		@csrf
		<div class="row">
			<div class="col-xxl-2 col-lg-6 col-md-6">
				<span class="error">* </span>
				<label for="request_date" class="col-form-label text-md-end">{{ __('Choose Date') }}</label>
				<input type="date" name="request_date" id="request_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
			</div>
			<div class="col-xxl-10 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
		</div>
		<br>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Department Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div" id="department_main_div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="department_id" class="col-form-label text-md-end">{{ __('Choose Department Name') }}</label>
							<select name="department_id" id="department_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterdepartments as $masterdepartment)
								<option value="{{$masterdepartment->id}}">{{$masterdepartment->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="location_id" class="col-form-label text-md-end">{{ __('Choose Department Location') }}</label>
							<select name="location_id" id="location_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterOfficeLocations as $masterOfficeLocation)
								<option value="{{$masterOfficeLocation->id}}">{{$masterOfficeLocation->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div" id="requested_by_main_div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="requested_by" class="col-form-label text-md-end">{{ __('Choose Requested By') }}</label>
							<select name="requested_by" id="requested_by" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($requestedByUsers as $requestedByUser)
								<option value="{{$requestedByUser->id}}">{{$requestedByUser->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="requested_job_title" class="col-form-label text-md-end">{{ __('Choose Requested Job Title') }}</label>
							<select name="requested_job_title" id="requested_job_title" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterJobPositions as $masterJobPosition)
								<option value="{{$masterJobPosition->id}}">{{$masterJobPosition->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<a id="createNewJobTitleButton" data-toggle="popover" data-trigger="hover" title="Create New Job Title" data-placement="top" style="margin-top:38px;"
							class="btn btn-sm btn-info modal-button" data-modal-id="createNewJobPosition"><i class="fa fa-plus" aria-hidden="true"></i> Create New Job Title</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Position Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="experience_level" class="col-form-label text-md-end">Choose Experience Level</label>
							<select name="experience_level" id="experience_level" class="form-control widthinput"  multiple="true" autofocus onchange="">
								@foreach($masterExperienceLevels as $masterExperienceLevel)
								<option value="{{$masterExperienceLevel->id}}">{{$masterExperienceLevel->name}} ( {{$masterExperienceLevel->number_of_year_of_experience}} )</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="salary_range" class="col-form-label text-md-end">{{ __('Salary Range') }}</label>
						<div class="input-group">
							<input name="salary_range_start_in_aed" id="salary_range_start_in_aed"
								class="form-control widthinput" placeholder="Enter Salary Range Start"
								aria-label="measurement" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
							<p style="padding-left:10px;"> - </p>
						</div>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6" style="padding-top:38px;">
						<div class="input-group">
							<input name="salary_range_end_in_aed" id="salary_range_end_in_aed"
								class="form-control widthinput" placeholder="Enter Salary Range End"
								aria-label="measurement" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="work_time" class="col-form-label text-md-end">{{ __('Work Time') }}</label>
						<div class="input-group">
							<input type="time" name="work_time_start" id="work_time_start" class="form-control widthinput" placeholder="Enter Work Time Start" 
								aria-label="measurement" aria-describedby="basic-addon2">
							<p style="padding-left:10px;"> - </p>
						</div>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<div class="input-group" style="padding-top:38px;">
							<input type="time" name="work_time_end" id="work_time_end" class="form-control widthinput" 
								placeholder="Enter Work Time End" aria-label="measurement" 
								aria-describedby="basic-addon2">
						</div>
					</div>
					<div class="col-xxl-2 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="number_of_openings" class="col-form-label text-md-end">{{ __('Number Of Openings') }}</label>
						<input name="number_of_openings" id="number_of_openings" onkeyup="" type="number" class="form-control widthinput" 
							onkeypress="return event.charCode >= 48" min="1" placeholder="Number Of Openings" aria-label="measurement" 
							aria-describedby="basic-addon2">
					</div>
					<div class="col-xxl-2 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="type_of_role" class="col-form-label text-md-end">{{ __('Type Of Role') }}</label>
						<fieldset style="margin-top:5px;">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="type_of_role" name="type_of_role" value="new_position" id="new_position" checked />
									<label for="new_position">New Position</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="type_of_role" name="type_of_role" value="replacement" id="replacement" />
									<label for="replacement">Replacement</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div id="replacement_for_employee_div" class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="replacement_for_employee" class="col-form-label text-md-end">Choose Replacement For Employee</label>
							<select name="replacement_for_employee" id="replacement_for_employee" class="form-control widthinput"  multiple="true" autofocus onchange="">
								@foreach($replacementForEmployees as $replacementForEmployee)
								<option value="{{$replacementForEmployee->id}}">{{$replacementForEmployee->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title"><span class="error">* </span>Detailed Explanation Of New Hiring</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<textarea rows="5" id="explanation_of_new_hiring" type="text" class="form-control @error('explanation_of_new_hiring') is-invalid @enderror"
							name="explanation_of_new_hiring" placeholder="Enter Detailed Explanation Of New Hiring" value="{{ old('explanation_of_new_hiring') }}"  autocomplete="explanation_of_new_hiring"
							autofocus></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
@include('hrm.hiring.hiring_request.createJobPosition')
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
	var data = {!! json_encode($data) !!};
	var user = {!! json_encode($user) !!};
	$(document).ready(function () {
		$("#replacement_for_employee_div").hide();
		if(data.request_date) {
			document.getElementById('request_date').value = data.request_date;
		}
		else {
			document.getElementById('request_date').valueAsDate = new Date();
		}
		if(user.department != '') {
			$("#department_id").val(user.department);
			$("#department_main_div").hide();
		}
		else{
			$("#department_id").val(data.department_id);
			$("#department_main_div").show();
		}
		$("#location_id").val(data.location_id);
		if(user.id != '') {
			$("#requested_by").val(user.id);
			$("#requested_by_main_div").hide();
		}
		else{
			$("#requested_by").val(data.requested_by);
			$("#requested_by_main_div").show();
		}		
		$("#requested_job_title").val(data.requested_job_title);
		$("#reporting_to").val(data.reporting_to);
		$("#experience_level").val(data.experience_level);
		$("#salary_range_start_in_aed").val(data.salary_range_start_in_aed);
		$("#salary_range_end_in_aed").val(data.salary_range_end_in_aed);
		$("#work_time_start").val(data.work_time_start);
		$("#work_time_end").val(data.work_time_end);
		$("#number_of_openings").val(data.number_of_openings);
		$('#' + data.type_of_role).prop('checked',true);
		if(data.type_of_role == 'replacement') {
			$("#replacement_for_employee_div").show();
			$("#replacement_for_employee").val(data.replacement_for_employee);
		}
		$("#explanation_of_new_hiring").val(data.explanation_of_new_hiring);
		$('#department_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Department Name",
	       });
		$('#location_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Department Location",
	       });
		$('#requested_by').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Requested By",
	       });
		$('#requested_job_title').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Requested Job Title",
	       });
		$('#reporting_to').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Reporting To With Position",
	       });
		$('#experience_level').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Experience Level",
	       });
		$('#replacement_for_employee').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Replacement For Employee",
	       });
	});	
	
	$('.type_of_role').click(function() {
		if($(this).val() == 'new_position') {
			$("#replacement_for_employee_div").hide();
		}
		else {
			$("#replacement_for_employee_div").show();
			$('#replacement_for_employee').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Replacement For Employee",
	       });
		}
	});
	
	jQuery.validator.setDefaults({
	       errorClass: "is-invalid",
	       errorElement: "p",
	       errorPlacement: function ( error, element ) {
	           error.addClass( "invalid-feedback font-size-13" );
	           if ( element.prop( "type" ) === "checkbox" ) {
	               error.insertAfter( element.parent( "label" ) );
	           }
	           else if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
	               if (!element.val() || element.val().length === 0) {
	                   console.log("Error is here with length", element.val().length);
	                   error.addClass('select-error');
	                   error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	               } else {
	                   console.log("No error");
	               }
	           }
			else if (element.parent().hasClass('input-group')) {
	               error.insertAfter(element.parent());
	           }
	           else {
	               error.insertAfter( element );
	           }
	       }
	   });
	jQuery.validator.addMethod(
		"money",
		function(value, element) {
			var isValidMoney = /^\d{0,5}(\.\d{0,2})?$/.test(value);
			return this.optional(element) || isValidMoney;
		},
		"Please enter a valid amount "
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
	$('#employeeHiringRequestForm').validate({ // initialize the plugin
	       rules: {
			request_date: {
				required: true,
			},
	           department_id: {
	               required: true,
	           },
	           location_id: {
	               required: true,
	           },
			requested_by: {
	               required: true,
	           },
	           requested_job_title: {
	               required: true,
	           },
			reporting_to: {
				required: true,
			},
	           number_of_openings: {
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
	           experience_level: {
	               required: true,
	           },
			work_time_start: {
				required: true,
			},
	           work_time_end: {
	               required: true,
				timeDifferenceValidate: ["#work_time_start"],
	           },
			explanation_of_new_hiring: {
	               required: true,
	           },
			replacement_for_employee: {
	               required: true,
	           },
	       },
	   });
</script>
@endsection