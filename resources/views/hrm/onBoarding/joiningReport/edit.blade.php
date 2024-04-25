@extends('layouts.main')
@include('layouts.formstyle')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-joining-report','current-user-edit-joining-report','dept-emp-edit-joining-report']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Edit New Employee Joining Report</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee_joining_report.index','new_employee') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="joiningReportForm" name="joiningReportForm" enctype="multipart/form-data" method="POST" action="{{route('joining_report.update',$data->id)}}">
		@csrf
		@method('PUT')
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-4 col-md-4 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
							<select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
							@foreach($candidates as $candidate)
							<option value="{{$candidate->id}}" @if($data->candidate_id == $candidate->id) selected @endif>{{$candidate->first_name}} {{$candidate->last_name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-4 col-md-4" id="employee_code_div">
						<center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
						<input id="employee_code" type="text" class="form-control widthinput @error('employee_code') is-invalid @enderror" name="employee_code"
							placeholder="Candidate Name" value="{{$data->candidate->employee_code}}" autocomplete="employee_code" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-4 col-md-4" id="designation_div">
						<center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
						<center><span id="designation">{{$data->candidate->designation->name ?? ''}}</span></center>
					</div>
					<div class="col-xxl-3 col-lg-4 col-md-4" id="department_div">
						<center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
						<center><span id="department">{{$data->candidate->department->name ?? ''}}</span></center>
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
									<input type="radio" class="type" name="new_emp_joining_type" value="trial_period" id="trial_period" @if($data->new_emp_joining_type == 'trial_period') checked @endif />
									<label for="trial_period">Trial Period</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="type" name="new_emp_joining_type" value="permanent" id="permanent" @if($data->new_emp_joining_type == 'permanent') checked @endif />
									<label for="permanent">Permanent</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="joining_date" class="col-form-label text-md-end">{{ __('Joining Date') }}</label>
						<input id="joining_date" type="date" class="form-control widthinput @error('joining_date') is-invalid @enderror" name="joining_date"
							placeholder="Candidate Name" value="{{$data->joining_date ?? ''}}" autocomplete="joining_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="joining_location" class="col-form-label text-md-end">{{ __('Choose Location') }}</label>
							<select name="joining_location" id="joining_location" multiple="true" class="form-control widthinput" onchange="" autofocus>
							@foreach($masterlocations as $location)
							<option value="{{$location->id}}" @if($location->id == $data->joining_location) selected @endif>{{$location->name}}</option>
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
							<option value="{{$reportingToId->id}}" @if($reportingToId->id == $data->department_head_id) selected @endif >{{$reportingToId->name}}</option>
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
						<textarea rows="5" name="remarks" placeholder="Enter Remarks" class="form-control">{{$data->remarks ?? ''}}</textarea>
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
	var candidates = {!! json_encode($candidates) !!};
	var data = {!! json_encode($data) !!};
	$(document).ready(function () {
	    $('#employee_id').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder:"Choose Employee Name",
	    });
		$('#joining_location').select2({
	        allowClear: true,
			maximumSelectionLength: 1,
	        placeholder:"Choose Employee Hiring Request UUID",
	    });	
	    $('#team_lead_or_reporting_manager').select2({
	        allowClear: true,
			maximumSelectionLength: 1,
	        placeholder:"Choose Reporting Manager",
	    });
		if(data != null && data.candidate != null && data.candidate.candidate_joining_type != null && data.candidate.candidate_joining_type == 'permanent')	{
			$('#permanent').prop('checked',true);
			$('#trial_period').attr("disabled",true);
			if(data.candidate.candidate_trial_joining_date != null) {
				document.getElementById("joining_date").min = data.candidate.candidate_trial_joining_date;
				var alreadySelectedDate = $("#joining_date").val();
			}
		}
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
	jQuery.validator.addMethod("uniqueCandidateEmpCode", 
	       function(value, element) {
	           var result = false;
				var employeeId = $("#employee_id").val();
	           $.ajax({
	               type:"POST",
	               async: false,
	               url: "{{route('employee.uniqueCandidateEmpCode')}}", // script to validate in server side
	               data: {_token: '{{csrf_token()}}',employeeCode: value,employeeId:employeeId},
	               success: function(data) {
	                   result = (data == true) ? true : false;
	               }
	           });
	           // return true if username is exist in database
	           return result; 
	       }, 
	       "This Employee Code is already taken! Try another."
	   );
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
	        joining_location: {
	            required: true,
	        },
	        new_emp_joining_type: {
	            required: true,
	        },
	        joining_type: {
	            required: true,
	        },
	        employee_code: {
	            required: true,
				minlength: 4,
	            maxlength: 4,
				uniqueCandidateEmpCode: true,
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
	            } 
				else {
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