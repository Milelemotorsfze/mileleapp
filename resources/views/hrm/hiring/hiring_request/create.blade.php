@extends('layouts.main')
<style>
	.spanSub
	{
	background-color: #e4e4e4;
	border: 1px solid #aaa;
	border-radius: 4px;
	box-sizing: border-box;
	display: inline;
	margin-left: 5px;
	margin-top: 5px;
	padding: 0 10px 0 20px;
	position: relative;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
	vertical-align: bottom;
	white-space: nowrap;
	}
	.error
	{
	color: #FF0000;
	}
	.iti
	{
	width: 100%;
	}
	.btn_round
	{
	width: 30px;
	height: 30px;
	display: inline-block;
	text-align: center;
	line-height: 35px;
	margin-left: 10px;
	margin-top: 28px;
	border: 1px solid #ccc;
	color:#fff;
	background-color: #fd625e;
	border-radius:5px;
	cursor: pointer;
	padding-top:7px;
	}
	.btn_round:hover
	{
	color: #fff;
	background: #fd625e;
	border: 1px solid #fd625e;
	}
	.btn_content_outer
	{
	display: inline-block;
	width: 85%;
	}
	.close_c_btn
	{
	width: 30px;
	height: 30px;
	position: absolute;
	right: 10px;
	top: 0px;
	line-height: 30px;
	border-radius: 50%;
	background: #ededed;
	border: 1px solid #ccc;
	color: #ff5c5c;
	text-align: center;
	cursor: pointer;
	}
	.add_icon
	{
	padding: 10px;
	border: 1px dashed #aaa;
	display: inline-block;
	border-radius: 50%;
	margin-right: 10px;
	}
	.add_group_btn
	{
	display: flex;
	}
	.add_group_btn i
	{
	font-size: 32px;
	display: inline-block;
	margin-right: 10px;
	}
	.add_group_btn span
	{
	margin-top: 8px;
	}
	.add_group_btn,
	.clone_sub_task
	{
	cursor: pointer;
	}
	.sub_task_append_area .custom_square
	{
	cursor: move;
	}
	.del_btn_d
	{
	display: inline-block;
	position: absolute;
	right: 20px;
	border: 2px solid #ccc;
	border-radius: 50%;
	width: 40px;
	height: 40px;
	line-height: 40px;
	text-align: center;
	font-size: 18px;
	}
	body
	{
	font-family: Arial;
	}
	/* Style the tab */
	.tab
	{
	overflow: hidden;
	border: 1px solid #ccc;
	background-color: #f1f1f1;
	}
	/* Style the h6 inside the tab */
	.tab h6
	{
	background-color: inherit;
	float: left;
	border: none;
	outline: none;
	cursor: pointer;
	padding: 14px 16px;
	transition: 0.3s;
	font-size: 17px;
	}
	/* Change background color of h6 on hover */
	.tab h6:hover
	{
	background-color: #ddd;
	}
	/* Create an active/current tablink class */
	.tab h6.active
	{
	background-color: #ccc;
	}
	/* Style the tab content */
	.tabcontent
	{
	display: none;
	padding: 6px 12px;
	border: 1px solid #ccc;
	border-top: none;
	}
	.paragraph-class
	{
	margin-top: .25rem;
	font-size: 80%;
	color: #fd625e;
	}
	.required-class
	{
	margin-top: .25rem;
	font-size: 80%;
	color: #fd625e;
	}
	.overlay
	{
	position: fixed; /* Positioning and size */
	top: 0;
	left: 0;
	width: 100vw;
	height: 100vh;
	background-color: rgba(128,128,128,0.5); /* color */
	display: none; /* making it hidden by default */
	}
	.widthinput
	{
	height:32px!important;
	}
	input:focus
	{
	border-color: #495057!important;
	}
	select:focus
	{
	border-color: #495057!important;
	}
	a:focus
	{
	border-color: #495057!important;
	}
</style>
@section('content')
@canany(['demand-planning-supplier-create', 'addon-supplier-create', 'vendor-edit'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-create', 'vendor-edit','demand-planning-supplier-create']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Employee Hiring Request Form</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="employeeHiringRequestForm" name="employeeHiringRequestForm" enctype="multipart/form-data" method="POST">
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
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="department_id" class="col-form-label text-md-end">{{ __('Choose Department Name') }}</label>
						<select name="department_id" id="department_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
							@foreach($masterdepartments as $masterdepartment)
								<option value="{{$masterdepartment->id}}">{{$masterdepartment->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="location_id" class="col-form-label text-md-end">{{ __('Choose Department Location') }}</label>
						<select name="location_id" id="location_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
							@foreach($masterOfficeLocations as $masterOfficeLocation)
								<option value="{{$masterOfficeLocation->id}}">{{$masterOfficeLocation->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="requested_by" class="col-form-label text-md-end">{{ __('Choose Requested By') }}</label>
						<select name="requested_by" id="requested_by" multiple="true" class="form-control widthinput" onchange="" autofocus>						
							@foreach($requestedByUsers as $requestedByUser)
								<option value="{{$requestedByUser->id}}">{{$requestedByUser->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="requested_job_title" class="col-form-label text-md-end">{{ __('Choose Requested Job Title') }}</label>
						<select name="requested_job_title" id="requested_job_title" multiple="true" class="form-control widthinput" onchange="" autofocus>						
							@foreach($masterJobPositions as $masterJobPosition)
								<option value="{{$masterJobPosition->id}}">{{$masterJobPosition->name}}</option>
							@endforeach
						</select>
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
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="reporting_to" class="col-form-label text-md-end">{{ __('Choose Reporting To With Position') }}</label>
						<select name="reporting_to" id="reporting_to" multiple="true" class="form-control widthinput" onchange="" autofocus>
							@foreach($reportingToUsers as $reportingToUser)
								<option value="{{$reportingToUser->id}}">{{$reportingToUser->name}} ( JOB POSITION )</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="experience_level" class="col-form-label text-md-end">Choose Experience Level</label>
						<select name="experience_level" id="experience_level" class="form-control widthinput"  multiple="true" autofocus onchange="">
							@foreach($masterExperienceLevels as $masterExperienceLevel)
							<option value="{{$masterExperienceLevel->id}}">{{$masterExperienceLevel->name}} ( {{$masterExperienceLevel->number_of_year_of_experience}} )</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-2 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="salary_range" class="col-form-label text-md-end">{{ __('Salary Range') }}</label>
						<div class="input-group">
							<input name="salary_range_start_in_aed" id="salary_range_start_in_aed" onkeyup="" oninput="inputNumberAbs(this)"
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
							<input name="salary_range_end_in_aed" id="salary_range_end_in_aed" onkeyup="" oninput="inputNumberAbs(this)"
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
					<div id="replacement_for_employee_div" class="col-xxl-4 col-lg-6 col-md-6">
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
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Detailed Explanation Of New Hiring</h4>
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
<div class="modal" id="createNewJobPosition" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Create New Job Title </h5>
				<button type="button" class="btn btn-secondary btn-sm close form-control" data-dismiss="modal" aria-label="Close" onclick="closemodal()">
				<span aria-hidden="true">X</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" id="createNewJobTitleForm">
					<div class="row modal-row">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							<span class="error">* </span>
							<label for="Job Title" class="col-form-label text-md-end ">Job Title</label>
						</div>
						<div class="col-xxl-12 col-lg-12 col-md-12">
							<input type="text" id="new_job_title" class="form-control @error('new_job_title') is-invalid @enderror" name="new_job_title" value="" required>
							<span id="newTitleError" class="required-class paragraph-class"></span>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" onclick="closemodal()"><i class="fa fa-times"></i> Close</button>
				<button type="button" class="btn btn-primary btn-sm" id="createTitleId" style="float: right;">
				<i class="fa fa-check" aria-hidden="true"></i> Submit</button>
			</div>
		</div>
	</div>
</div>
<div class="overlay"></div>
@endif
@endcan
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		document.getElementById('request_date').valueAsDate = new Date();
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
		$("#replacement_for_employee_div").hide();
	});	
	$('.modal-button').on('click', function() {
		$('#createNewJobPosition').modal('show');
	});	
	function closemodal() {
	    $('.overlay').hide();
		$('#createNewJobPosition').modal('hide');
	}
	$('.type_of_role').click(function() {
		if($(this).val() == 'new_position') {
			$("#replacement_for_employee_div").hide();
		}
		else {
			$("#replacement_for_employee_div").show();
		}
	});
	$('#createTitleId').on('click', function() {
	    var value = $('#new_job_title').val();
	    if(value == '') {
	        $msg = 'Job Title is Required';
	        showNewTitleError($msg);
	    }
	    else {
	        $.ajax({
	            url:"{{url('master-job-position')}}",
	            type: "POST",
	            data:{
	                name: value,
	                _token: '{{csrf_token()}}'
	            },
	            dataType : 'json',
	            success: function(result) { console.log(result);
	                if(result.error) {
	                    $msg = result.error;
	                    showNewTitleError($msg);
	                }
					else {
	                    $('.overlay').hide();
						closemodal();
	                    $('#requested_job_title').append("<option value='" + result.id + "'>" + result.name + "</option>");
	                    $('#requested_job_title').val(result.id);
	                    removeNewTitleError();						
	                }
	            }
	        });
	    }
	});
	function showNewTitleError($msg) {
		document.getElementById("newTitleError").textContent=$msg;
	    document.getElementById("new_job_title").classList.add("is-invalid");
	    document.getElementById("newTitleError").classList.add("paragraph-class");
	}
	function removeNewTitleError() {
	    document.getElementById("newTitleError").textContent="";
	    document.getElementById("new_job_title").classList.remove("is-invalid");
	    document.getElementById("newTitleError").classList.remove("paragraph-class");
	}
	jQuery.validator.setDefaults({
        errorClass: "is-invalid",
        errorElement: "p",
        errorPlacement: function ( error, element ) {
            error.addClass( "invalid-feedback font-size-13" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            }
            else if (element.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + element.attr("id") + "-container").parent();
                error.insertAfter(element);
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
			var isValidMoney = /^\d{0,4}(\.\d{0,2})?$/.test(value);
			return this.optional(element) || isValidMoney;
		},
		"Please enter a valid amount "
	);
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
            },
            experience_level: {
                required: true,
            },
			work_time_start: {
				required: true,
			},
            work_time_end: {
                required: true,
            },
			explanation_of_new_hiring: {
                required: true,
            },
			replacement_for_employee: {
                required: true,
            },
        },
        submitHandler: function (form) { // for demo
            alert('valid form submitted'); // for demo
            return false; // for demo
        }
    });
	
	// $("#employeeHiringRequestForm").validate({
	// 	ignore: [],
	// 	rules: {
	// 		department_id: {
	// 			required: true,
	// 		},
	// 		location_id: {
	// 			required: true,
	// 		},
	// 		requested_by: {
	// 			required: true,
	// 		},
	// 		requested_job_title: {
	// 			required: true,
	// 		},
	// 		// nationality: {
	// 		// 	required: function(element){
	// 		// 		return $("#vendor-type").val() == "Individual";
	// 		// 	}
	// 		// },
	// 		// passport_number: {
	// 		// 	required: function(element){
	// 		// 		return $("#vendor-type").val() == "Individual";
	// 		// 	}
	// 		// },
	// 		// passport_copy_file:{
	// 		// 	required: function(element){
	// 		// 		return $("#vendor-type").val() == "Individual";
	// 		// 	},
	// 		// 	extension: "pdf|png|jpg|jpeg|svg"

	// 		// },
	// 		// trade_registration_place: {
	// 		// 	required: function(element){
	// 		// 		return $("#vendor-type").val() == "Company";
	// 		// 	},
	// 		// },
	// 		// trade_license_number: {
	// 		// 	required: function(element){
	// 		// 		return $("#vendor-type").val() == "Company";
	// 		// 	},
	// 		// },
	// 		// trade_license_file:{
	// 		// 	required: function(element){
	// 		// 		return $("#vendor-type").val() == "Company";
	// 		// 	},
	// 		// 	extension: "pdf|png|jpg|jpeg|svg"

	// 		// },
	// 		// vat_certificate_file:{
	// 		// 	extension: "pdf|png|jpg|jpeg|svg"

	// 		// },
	// 		// address_details:{
	// 		// 	required: true
	// 		// },
	// 		// email:{
	// 		// 	require_from_group: [1, '.mygroup'],
	// 		// 	email: true
	// 		// },
	// 		// phone:{
	// 		// 	require_from_group: [1, '.mygroup'],
	// 		// 	minlength:5,
	// 		// 	maxlength:15,
	// 		// 	number:true
	// 		// },
	// 		// mobile:{
	// 		// 	require_from_group: [1, '.mygroup'],
	// 		// 	minlength:5,
	// 		// 	maxlength:15,
	// 		// 	number:true
	// 		// },
	// 		// alternate_contact_number: {
	// 		// 	minlength:5,
	// 		// 	maxlength:15,
	// 		// 	number:true
	// 		// },
	// 		// messages: {
	// 		// 	passport_copy_file: {
	// 		// 		extension: "File type not allowed.Please refer file type here..(eg: pdf|png|jpg|jpeg|svg..)"
	// 		// 	},
	// 		// 	trade_license_file: {
	// 		// 		extension: "File type not allowed.Please refer file type here..(eg: pdf|png|jpg|jpeg|svg..)"
	// 		// 	},
	// 		// 	vat_certificate_file: {
	// 		// 		extension: "File type not allowed.Please refer file type here..(eg: pdf|png|jpg|jpeg|svg..)"
	// 		// 	}
	// 		// }
	// 	},
	// 	submitHandler: function(form) {
	// 		// This function will be called when the form is submitted and passes validation
	// 		var trade_name_or_individual_name = $('#trade_name_or_individual_name').val();
	// 		$.ajax({
	// 			url: '',
	// 			type: 'POST',
	// 			data: {
	// 				'_token': '{{ csrf_token() }}',
	// 				'trade_name_or_individual_name': trade_name_or_individual_name
	// 			},
	// 			success: function(response) {
	// 				if (response.exists) {
	// 					alert("Name Already Existing");
	// 				} else {
	// 					form.submit();
	// 				}
	// 			},
	// 			error: function(xhr) {
	// 				if (xhr.status === 422) {
	// 					alert("Name Already Existing");
	// 				}
	// 			}
	// 		});
	// 	}
	// });
</script>
@endsection