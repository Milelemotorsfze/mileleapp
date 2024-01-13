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
@canany(['create-liability','current-user-create-liability','edit-liability','current-user-edit-liability'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-liability','current-user-create-liability','edit-liability','current-user-edit-liability']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">@if($id == 'new')Create New @else Edit @endif Employee Liability Request</h4>
	@if($id != 'new')
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-liability.create-or-edit',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-liability.create-or-edit',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	@endif
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee_liability.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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

		
		<form id="employeeLiabilityForm" name="employeeLiabilityForm" enctype="multipart/form-data" method="POST" action="{{route('employee-liability.store-or-update',$id)}}">
		@csrf
		

		<div class="row">
			<!-- <div class="col-xxl-2 col-lg-6 col-md-6">
				<span class="error">* </span>
				<label for="request_date" class="col-form-label text-md-end">{{ __('Choose Date') }}</label>
				<input type="date" name="request_date" id="request_date" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
			</div> -->
			<div class="col-xxl-12 col-lg-6 col-md-6">
				<p><span style="float:right;" class="error">* Required Field</span></p>
			</div>			
		</div>
		<br>
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
                                <option value="{{$employee->id}}" @if($data->employee_id == $employee->id) selected @endif>{{$employee->name}}</option>
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
                    <div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="type" class="col-form-label text-md-end">{{ __('Liability Type') }}</label>
						<fieldset style="margin-top:5px;">
                            <div class="row some-class">
                                <div class="col-xxl-4 col-lg-4 col-md-4">
                                    <input type="radio" class="type" name="type" value="loan" id="loan" @if($data->type == 'loan') checked @endif />
                                    <label for="loan">Loan</label>
                                </div>
                                <div class="col-xxl-4 col-lg-4 col-md-4">
                                    <input type="radio" class="type" name="type" value="advances" id="advances" @if($data->type == 'advances') checked @endif />
                                    <label for="advances">Advances</label>
                                </div>
                                <div class="col-xxl-4 col-lg-4 col-md-4">
                                    <input type="radio" class="type" name="type" value="penalty_or_fine" id="penalty_or_fine" @if($data->type == 'penalty_or_fine') checked @endif />
                                    <label for="penalty_or_fine">Penalty / Fine</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
                    <div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="code" class="col-form-label text-md-end">{{ __('Liability Code') }}</label>
						<input type="text" name="code" id="code"
								class="form-control widthinput" placeholder="Enter Liability Code"
								 aria-label="measurement" aria-describedby="basic-addon2" value="ELF/FINE/DEC_2023/0086">
					</div>
                    <div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="total_amount" class="col-form-label text-md-end">{{ __('Total Amount') }}</label>
						<div class="input-group">
							<input name="total_amount" id="total_amount" value="{{$data->total_amount ?? ''}}"
								class="form-control widthinput" placeholder="Enter Total Amount"
								 aria-label="measurement" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>
                    <div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="no_of_installments" class="col-form-label text-md-end">{{ __('Number Of Installments') }}</label>
						<input name="no_of_installments" id="no_of_installments" onkeyup="" type="number" class="form-control widthinput" 
						onkeypress="return event.charCode >= 48" min="1" placeholder="Number Of Installments" aria-label="measurement" 
						aria-describedby="basic-addon2" value="{{$data->no_of_installments ?? ''}}">
					</div>
                    <div class="col-xxl-4 col-lg-4 col-md-4">
						<span class="error">* </span>
						<label for="amount_per_installment" class="col-form-label text-md-end">{{ __('Amount Per Installment') }}</label>
						<div class="input-group">
							<input name="amount_per_installment" id="amount_per_installment" value="{{$data->amount_per_installment ?? ''}}"
								class="form-control widthinput" placeholder="Enter Amount Per Installment"
								 aria-label="measurement" aria-describedby="basic-addon2">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="basic-addon2">AED</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12 mt-4">
						<textarea rows="5" id="reason" type="text" class="form-control @error('reason') is-invalid @enderror"
						name="reason" placeholder="Enter Reason" value="{{ old('reason') }}"  autocomplete="reason"
						autofocus>{{$data->reason ?? ''}}</textarea>
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
	var oldData = {!! json_encode($data) !!};
	var id = {!! json_encode($id) !!}
	$(document).ready(function () {
		if(id == 'new') {
			$("#passport_number_div").hide();
			$("#joining_date_div").hide();
			$("#designation_div").hide();
			$("#department_div").hide();
			$("#location_div").hide();
		}
		else {
			if(oldData.user.emp_profile.passport_number != null) {
				document.getElementById('passport_number').textContent=oldData.user.emp_profile.passport_number;
			}
			if(oldData.user.emp_profile.company_joining_date != null) {
				document.getElementById('joining_date').textContent=oldData.user.emp_profile.company_joining_date;
			}
			if(oldData.user.emp_profile.designation != null) {
				document.getElementById('designation').textContent=oldData.user.emp_profile.designation.name;
			}
			if(oldData.user.emp_profile.department != null) {
				document.getElementById('department').textContent=oldData.user.emp_profile.department.name;
			}
			if(oldData.user.emp_profile.location != null) {
				document.getElementById('location').textContent=oldData.user.emp_profile.location.name;
			}
		}
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
				document.getElementById('passport_number').textContent = '';
				document.getElementById('joining_date').textContent = '';
				document.getElementById('designation').textContent = '';
				document.getElementById('department').textContent = '';
				document.getElementById('location').textContent = '';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].id == Number(selectedEmpId)) {
						if(data[i].emp_profile.passport_number != null) {
							document.getElementById('passport_number').textContent=data[i].emp_profile.passport_number;
						}
						if(data[i].emp_profile.company_joining_date != null) {
							document.getElementById('joining_date').textContent=data[i].emp_profile.company_joining_date;
						}
						if(data[i].emp_profile.designation != null) {
							document.getElementById('designation').textContent=data[i].emp_profile.designation.name;
						}
						if(data[i].emp_profile.department != null) {
							document.getElementById('department').textContent=data[i].emp_profile.department.name;
						}
						if(data[i].emp_profile.location != null) {
							document.getElementById('location').textContent=data[i].emp_profile.location.name;
						}
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
		$('#' + data.type).prop('checked',true);
		if(data.type == 'replacement') {
			$("#replacement_for_employee_div").show();
			$("#replacement_for_employee").val(data.replacement_for_employee);
		}
		$("#reason").val(data.reason);
		
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
	
	$('.type').click(function() {
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
	$('#employeeLiabilityForm').validate({ // initialize the plugin
        rules: {
			employee_id: {
                required: true,
            },
			type: {
				required: true,
			},           
            code: {
                required: true,
            },
            total_amount: {
                required: true,
				money: true,
            },
			no_of_installments: {
				required: true,
			},			
			amount_per_installment: {
                required: true,
				money: true,
            },
			reason: {
                required: true,
            },
        },
    });
</script>
@endsection