@extends('layouts.main')
<style>
	.radio-main-div {
        margin-top: 12px !important;
    }
	.radio-error,
    .select-error,
    .other-error {
        color: #fd625e;
    }
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
@canany(['create-joining-report'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Create Joining Report</h4>
	
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
	<form id="joiningReportForm" name="joiningReportForm" enctype="multipart/form-data" method="POST" action="{{route('joining_report.store')}}">
		@csrf
		<div class="card">
		<div class="card-body">
			<div class="row">
			<div class="col-xxl-3 col-lg-4 col-md-4 select-button-main-div">
				<div class="dropdown-option-div">
					<span class="error">* </span>
					<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
					<select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
						@foreach($candidates as $candidate)
							<option value="{{$candidate->id}}">{{$candidate->first_name}} {{$candidate->last_name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-xxl-3 col-lg-4 col-md-4" id="employee_code_div">
				<center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
				<input id="employee_code" type="text" class="form-control widthinput @error('employee_code') is-invalid @enderror" name="employee_code"
                                placeholder="Candidate Name" value="{{$candidate->employee_code}}" autocomplete="employee_code" autofocus>
			</div>
			<div class="col-xxl-3 col-lg-4 col-md-4" id="designation_div">
				<center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
				<center><span id="designation"></span></center>
			</div>
            <div class="col-xxl-3 col-lg-4 col-md-4" id="department_div">
				<center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
				<center><span id="department"></span></center>
			</div>
			</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Joining Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <input type="hidden" name="joining_type" value="new_employee">
                    <div class="col-xxl-3 col-lg-3 col-md-3 radio-main-div">
						<span class="error">* </span>
						<label for="type" class="col-form-label text-md-end">{{ __('Joining Type') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="type" name="type" value="trial" id="trial" />
                                    <label for="trial">Trial Period</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="type" name="type" value="permanent" id="permanent" />
                                    <label for="permanent">Permanent</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="joining_date" class="col-form-label text-md-end">{{ __('Joining Date') }}</label>
                        <input id="joining_date" type="date" class="form-control widthinput @error('joining_date') is-invalid @enderror" name="joining_date"
                                placeholder="Candidate Name" value="" autocomplete="joining_date" autofocus>
                    </div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="permanent_joining_location_id" class="col-form-label text-md-end">{{ __('Choose Location') }}</label>
							<select name="permanent_joining_location_id" id="permanent_joining_location_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterlocations as $location)
									<option value="{{$location->id}}">{{$location->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="team_lead_or_reporting_manager" class="col-form-label text-md-end">{{ __('Choose Reporting Manager') }}</label>
							<select name="team_lead_or_reporting_manager" id="team_lead_or_reporting_manager" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($reportingTo as $reportingToId)
									<option value="{{$reportingToId->id}}">{{$reportingToId->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="additional_remarks" class="col-form-label text-md-end">{{ __('Remarks') }}</label>
					</div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <textarea rows="5" name="remarks" placeholder="Enter Remarks" class="form-control"></textarea>
                    </div>
                </div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
<div class="overlay"></div>
@endif
@endcan
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
    var candidates = {!! json_encode($candidates) !!};
	$(document).ready(function () {
        $('#employee_code_div').hide();
		$('#designation_div').hide();
		$('#department_div').hide();
        $('#employee_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });
		$('#permanent_joining_location_id').select2({
            allowClear: true,
			maximumSelectionLength: 1,
            placeholder:"Choose Employee Hiring Request UUID",
        });	
        $('#team_lead_or_reporting_manager').select2({
            allowClear: true,
			maximumSelectionLength: 1,
            placeholder:"Choose Reporting Manager",
        });	
		$('.employee_id').change(function (e) {
			var employeeId = $('#employee_id').val();
			if(employeeId != '') {
				if(candidates.length > 0) {
					for(var i=0; i<candidates.length; i++) {						
						if(candidates[i].id == employeeId) {
							$('#employee_code_div').show();
                            $('#designation_div').show();
                            $('#department_div').show();
							document.getElementById('designation').textContent=candidates[i].designation.name;
                            document.getElementById('department').textContent=candidates[i].department.name;
						}
					}
				}               
			}
			else {
				$('#employee_code_div').hide();
				$('#designation_div').hide();
                $('#department_div').hide();
			}			
		});
	});
	jQuery.validator.setDefaults({
        errorClass: "is-invalid",
        errorElement: "p",     
    });
	$('#joiningReportForm').validate({ 
        rules: {
            employee_id: {
				required: true,
			},
			joining_date: {
				required: true,
			},
            permanent_joining_location_id: {
                required: true,
            },
            type: {
                required: true,
            },
            joining_type: {
                required: true,
            },
            employee_code: {
                required: true,
            },
            team_lead_or_reporting_manager: {
                required: true,
            },
        },
		errorPlacement: function ( error, element ) {
            error.addClass( "invalid-feedback font-size-13" );
			
            if (element.is(':radio') && element.closest('.radio-main-div').length > 0) {
                error.addClass('radio-error');
                error.insertAfter(element.closest('.radio-main-div').find('fieldset.radio-div-container').last());
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
            else {
                error.insertAfter( element );
            }
        }
    });
</script>
@endsection