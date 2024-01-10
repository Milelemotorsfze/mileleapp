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
	<h4 class="card-title">@if($id == 'new')Create New @else Edit @endif Employee Liability Request</h4>
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
		

		<!-- <div class="row">
			<div class="col-xxl-2 col-lg-6 col-md-6">
				<span class="error">* </span>
				<label for="request_date" class="col-form-label text-md-end">{{ __('Choose Date') }}</label>
				<input type="date" name="request_date" id="request_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
			</div>
			<div class="col-xxl-10 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>			
		</div>
		<br> -->
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Employee Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
                        <select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
                            @foreach($masterEmployees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
					</div>	
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="passport_number_div">
                        <center><label for="passport_number" class="col-form-label text-md-end"><strong>{{ __('Passport Number') }}</strong></label></center>
                        <center><span id="passport_number"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="joining_date_div">
                        <center><label for="joining_date" class="col-form-label text-md-end"><strong>{{ __('Joining Date') }}</strong></label></center>
                        <center><span id="joining_date"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="designation_div">
                        <center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
                        <center><span id="designation"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="department_div">
                        <center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
                        <center><span id="department"></span></center>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-4" id="location_div">
                        <center><label for="location" class="col-form-label text-md-end"><strong>{{ __('Location') }}</strong></label></center>
                        <center><span id="location"></span></center>
                    </div>                  
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Liability Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-2 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="liability_type" class="col-form-label text-md-end">{{ __('Liability Type') }}</label>
						<fieldset style="margin-top:5px;">
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="liability_type" name="liability_type" value="loan" id="loan" checked />
                                    <label for="loan">Loan</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="liability_type" name="liability_type" value="advances" id="advances" />
                                    <label for="advances">Advances</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="liability_type" name="liability_type" value="penalty_or_fine" id="penalty_or_fine" />
                                    <label for="penalty_or_fine">Penalty / Fine</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
                    <div class="col-xxl-2 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="liability_code" class="col-form-label text-md-end">{{ __('Liability Code') }}</label>
					</div>
                    <div class="col-xxl-2 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="total_amount" class="col-form-label text-md-end">{{ __('Total Amount') }}</label>
						<div class="input-group">
							<input name="total_amount" id="total_amount"
								class="form-control widthinput" placeholder="Enter Total Amount"
								 aria-label="measurement" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
							<p style="padding-left:10px;"> - </p>
						</div>
					</div>
                    <div class="col-xxl-2 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="number_of_installments" class="col-form-label text-md-end">{{ __('Number Of Installments') }}</label>
						<input name="number_of_installments" id="number_of_installments" onkeyup="" type="number" class="form-control widthinput" 
						onkeypress="return event.charCode >= 48" min="1" placeholder="Number Of Installments" aria-label="measurement" 
						aria-describedby="basic-addon2">
					</div>
                    <div class="col-xxl-2 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="amount_per_installment" class="col-form-label text-md-end">{{ __('Amount Per Installment') }}</label>
						<div class="input-group">
							<input name="amount_per_installment" id="amount_per_installment"
								class="form-control widthinput" placeholder="Enter Amount Per Installment"
								 aria-label="measurement" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
							<p style="padding-left:10px;"> - </p>
						</div>
					</div>
					
					
					
					
					
					
				
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Reason</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<textarea rows="5" id="explanation_of_new_hiring" type="text" class="form-control @error('explanation_of_new_hiring') is-invalid @enderror"
						name="explanation_of_new_hiring" placeholder="Enter Reason" value="{{ old('explanation_of_new_hiring') }}"  autocomplete="explanation_of_new_hiring"
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
	var data = {!! json_encode($masterEmployees) !!};
	$(document).ready(function () {
		$("#passport_number_div").hide();
        $("#joining_date_div").hide();
        $("#designation_div").hide();
        $("#department_div").hide();
        $("#location_div").hide();
        $('#employee_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });
        $('#employee_id').on('change', function() {
            var selectedEmpId = $(this).val();
            if(selectedEmpId == '') {
                $("#passport_number_div").hide();
                $("#joining_date_div").hide();
                $("#designation_div").hide();
                $("#department_div").hide();
                $("#location_div").hide();
            }
            else {
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == selectedEmpId) {
                        // console.log("Employee Data test by Rejitha : ", data[i].emp_profile.passport_status);
                        $('.employee-code-id').text(data[i].emp_profile?.employee_code || '');
                        $('.emp-designation').text(data[i].emp_profile?.designation?.name || '');
                        $('.emp-mobile-num').text(data[i].emp_profile?.contact_number || '');
                        $('.emp-department').text(data[i].emp_profile?.department?.name || '');
                        $('.emp-job-location').text(data[i].emp_profile?.location?.name || '');
                        // console.log("Drop down passport request value in update function : ", data[i].passport_with);
                        // $('#passport_request_dropdown').val(data[i].passport_with || '').trigger('change');
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
                $("#passport_number_div").show();
                $("#joining_date_div").show();
                $("#designation_div").show();
                $("#department_div").show();
                $("#location_div").show();
            }          
        });
		if(data.request_date) {
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
		$('#' + data.liability_type).prop('checked',true);
		if(data.liability_type == 'replacement') {
			$("#replacement_for_employee_div").show();
			$("#replacement_for_employee").val(data.replacement_for_employee);
		}
		$("#explanation_of_new_hiring").val(data.explanation_of_new_hiring);
		
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
	
	$('.liability_type').click(function() {
		if($(this).val() == 'loan') {
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
    });
</script>
@endsection