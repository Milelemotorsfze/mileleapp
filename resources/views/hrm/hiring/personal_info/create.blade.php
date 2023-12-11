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
@canany(['create-employee-hiring-request','edit-employee-hiring-request'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">Personal Information & Documents Shareing Form</h4>
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

		
		<form id="employeeHiringRequestForm" name="employeeHiringRequestForm" enctype="multipart/form-data" method="POST" action="">
		@csrf
		

		<div class="row">
			<div class="col-xxl-2 col-lg-6 col-md-6">
				<span class="error">* </span>
				<label for="request_date" class="col-form-label text-md-end">{{ __('Date') }}</label> : {{Carbon\Carbon::now();}}
				<!-- <input type="date" name="request_date" id="request_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2"> -->
			</div>
			<div class="col-xxl-10 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>			
		</div>
		<br>
		<div class="card">
			<!-- <div class="card-header">
				<h4 class="card-title">Department Information</h4>
			</div> -->
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('First Name') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Last Name') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __("Father’s Full Name" ) }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __("Mother’s Full Name" ) }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
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
                    <div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="nationality" class="col-form-label text-md-end">{{ __('Choose Nationality') }}</label>
							<select name="nationality" id="nationality" multiple="true" class="form-control widthinput" onchange="" autofocus>
								
								<option value="1">aaaaaaaaaa</option>
								<option value="2">ssssssssss</option>
							</select>
						</div>
					</div>
                    <div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="nationality" class="col-form-label text-md-end">{{ __('Choose Marital Status') }}</label>
							<select name="nationality" id="nationality" multiple="true" class="form-control widthinput" onchange="" autofocus>
                            <option value="1">aaaaaaaaaa</option>
								<option value="2">ssssssssss</option>
							</select>
						</div>
					</div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Passport No') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Passport Expiry') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Educational Qualification') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Year of Completion') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="year" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="department_id" class="col-form-label text-md-end">{{ __('Choose Religion') }}</label>
						<select name="department_id" id="department_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
                        <option value="1">aaaaaaaaaa</option>
								<option value="2">ssssssssss</option>
						</select>
					</div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Date Of Birth') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="date" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="location_id" class="col-form-label text-md-end">{{ __('Choose Spoken Languages') }}</label>
						<select name="location_id" id="location_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
                        <option value="1">aaaaaaaaaa</option>
								<option value="2">ssssssssss</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Full Address and Contact Details in UAE</h4>
			</div>
			<div class="card-body">
				<div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Telephone No. (Residence)') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="date" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div> 
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Mobile No.:') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="date" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div> 
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Personal Email Address') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="date" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
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
@include('hrm.hiring.hiring_request.createJobPosition')
<div class="overlay"></div>
@endif
@endcanany
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	var data = '';
	$(document).ready(function () {
		$("#replacement_for_employee_div").hide();
		if(data.request_date){
			document.getElementById('request_date').value = data.request_date;
		}
		else {
			document.getElementById('request_date').valueAsDate = new Date();
		}
		$("#department_id").val(data.department_id);
		$("#location_id").val(data.location_id);
		$("#requested_by").val(data.requested_by);
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
			var isValidMoney = /^\d{0,5}(\.\d{0,2})?$/.test(value);
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
        // submitHandler: function (form) { // for demo
        //     // alert('valid form submitted'); // for demo
        //     // return false; // for demo
		// 	// This function will be called when the form is submitted and passes validation
		// 	// var trade_name_or_individual_name = $('#trade_name_or_individual_name').val();
		// 	$.ajax({
		// 		url: '{{route("employee-hiring-request.store")}}',
		// 		type: 'POST',
		// 		data: {
		// 			'_token': '{{ csrf_token() }}',
		// 			// 'trade_name_or_individual_name': trade_name_or_individual_name
		// 		},
		// 		success: function(response) {
		// 			// if (response.exists) {
		// 			// 	alert("Name Already Existing");
		// 			// } else {
		// 			// 	form.submit();
		// 			// }
		// 		},
		// 		// error: function(xhr) {
		// 		// 	if (xhr.status === 422) {
		// 		// 		alert("Name Already Existing");
		// 		// 	}
		// 		// }
		// 	});
        // }
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