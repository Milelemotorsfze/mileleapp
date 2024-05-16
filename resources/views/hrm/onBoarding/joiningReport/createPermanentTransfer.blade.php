@extends('layouts.main')
@include('layouts.formstyle')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-permanent-joining-report']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Create Permanent Internal Transfer Joining Report</h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee_joining_report.index','permanent') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="perIntTransjoiningReportForm" name="perIntTransjoiningReportForm" enctype="multipart/form-data" method="POST" action="{{route('joining_report.store')}}">
		@csrf
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-4 col-md-4 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
							<select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
								@foreach($employees as $employee)
								<option value="{{$employee->id}}">{{$employee->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-4 col-md-4" id="employee_code_div">
						<center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
						<center><span id="employee_code"></span></center>
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
				<h4 class="card-title">Permanent Internal Transfer Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<input type="hidden" name="joining_type" value="internal_transfer">
					<input type="hidden" name="internal_transfer_type" value="permanent">
					<input type="hidden" name="transfer_from_department_id" id="transfer_from_department_id" value="">
					<input type="hidden" name="transfer_from_location_id" id="transfer_from_location_id" value="">
					<div class="col-xxl-4 col-lg-4 col-md-4" id="transfer_from_department_name_div">
						<div><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Transfer From Department') }}</strong></label></div>
						<div><span id="transfer_from_department_name"></span></div>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="transfer_from_location_name_div">
						<div><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Transfer From Location') }}</strong></label></div>
						<div><span id="transfer_from_location_name" value=""></span></div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="transfer_to_department_id" class="col-form-label text-md-end">{{ __('Transfer To Department') }}</label>
							<select name="transfer_to_department_id" id="transfer_to_department_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterDepartments as $department)
								<option id="tranfer_to_{{$department->id}}" value="{{$department->id}}">{{$department->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="joining_location" class="col-form-label text-md-end">{{ __('Transfer To Location') }}</label>
							<select name="joining_location" id="joining_location" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterlocations as $location)
								<option value="{{$location->id}}">{{$location->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="joining_date" class="col-form-label text-md-end">{{ __('Joining Date') }}</label>
						<input id="joining_date" type="date" class="form-control widthinput @error('joining_date') is-invalid @enderror" name="joining_date"
							placeholder="Joining Date" value="" autocomplete="joining_date" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4 radio-main-div" id="change_reporting_manager_div">
						<span class="error">* </span>
						<label for="type" class="col-form-label text-md-end">{{ __('Change Reporting Manager') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="type" name="team_lead_or_reporting_manager" value="" id="department_head" />
									<label for="department_head" id="department_head_label"></label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6" id="rep_div">
									<input type="radio" class="type" name="team_lead_or_reporting_manager" value="" id="division_head" />
									<label for="division_head" id="division_head_label"></label>
								</div>
							</div>
						</fieldset>
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
	var employees = {!! json_encode($employees) !!};
	var masterDeartments = {!! json_encode($masterDepartments) !!}
	var oldEmpIdp ='';
	$(document).ready(function () {
	    $('#employee_code_div').hide();
		$('#designation_div').hide();
		$('#department_div').hide();
	    $('#transfer_from_department_name_div').hide();
		$('#transfer_from_location_name_div').hide();
	    $('#change_reporting_manager_div').hide();
		$('#rep_div').hide();
	    $('#employee_id').select2({
	        allowClear: true,
	        maximumSelectionLength: 1,
	        placeholder:"Choose Employee Name",
	    });
		$('#joining_location').select2({
	        allowClear: true,
			maximumSelectionLength: 1,
	        placeholder:"Choose Joining Location",
	    });	
	    $('#transfer_to_department_id').select2({
	        allowClear: true,
			maximumSelectionLength: 1,
	        placeholder:"Choose Transfer To Department",
	    });
		$('#transfer_to_department_id').change(function (e) {
			var transfer_to_department_id = $('#transfer_to_department_id').val();
			if(transfer_to_department_id != '') {
				if(masterDeartments.length > 0) {
					for(var i=0; i<masterDeartments.length; i++) {	
						$('#change_reporting_manager_div').show();
						if(masterDeartments[i].id == transfer_to_department_id) {
							if(masterDeartments[i].department_head != null && masterDeartments[i].department_head.name != null && masterDeartments[i].department_head_id != null) {
								document.getElementById('department_head').value=masterDeartments[i].department_head_id;  
							    document.getElementById('department_head_label').textContent=masterDeartments[i].department_head.name;  
							}	
							if(masterDeartments[i].department_head != null && masterDeartments[i].department_head_id != null && masterDeartments[i].division != null && masterDeartments[i].division.division_head_id != null && masterDeartments[i].division != null &&
							masterDeartments[i].division.division_head != null && masterDeartments[i].division.division_head.name != null) {
								if(masterDeartments[i].department_head_id != masterDeartments[i].division.division_head_id) {
									$('#rep_div').show();
									document.getElementById('division_head').value=masterDeartments[i].division.division_head_id;  
									document.getElementById('division_head_label').textContent=masterDeartments[i].division.division_head.name;  
								}
								else {
									$('#rep_div').hide();
									document.getElementById('division_head').value='';  
									document.getElementById('division_head_label').textContent=''; 
								}
							}
						}
					}
				}
			}
			else {
				document.getElementById('department_head').value='';  
				document.getElementById('department_head_label').textContent=''; 
				document.getElementById('division_head').value='';  
				document.getElementById('division_head_label').textContent=''; 
				$('#change_reporting_manager_div').hide();
				$('#rep_div').hide();
			}
		})
		$('.employee_id').change(function (e) {
			var employeeId = $('#employee_id').val();
			if(employeeId != '') {
				if(employees.length > 0) {
					for(var i=0; i<employees.length; i++) {						
						if(employees[i].id == employeeId) {
							$('#employee_code_div').show();
							$('#designation_div').show();
							$('#department_div').show();
							$('#transfer_from_department_name_div').show();
							$('#transfer_from_location_name_div').show();
							if(employees[i].emp_profile != null && employees[i].emp_profile.employee_code != null) {
								document.getElementById('employee_code').textContent=employees[i].emp_profile.employee_code;                        
							}
							if(employees[i].emp_profile != null && employees[i].emp_profile.designation != null && employees[i].emp_profile.designation.name != null) {
								document.getElementById('designation').textContent=employees[i].emp_profile.designation.name;
							}
							if(employees[i].emp_profile != null && employees[i].emp_profile.department != null && employees[i].emp_profile.department.name != null) {
								document.getElementById('department').textContent=employees[i].emp_profile.department.name;  
								document.getElementById('transfer_from_department_id').value=employees[i].emp_profile.department_id;  
								document.getElementById('transfer_from_department_name').textContent=employees[i].emp_profile.department.name; 
								oldEmpId = employees[i].emp_profile.department_id;
								$('#tranfer_to_'+oldEmpId).prop('disabled', true);
							}
							if(employees[i].emp_profile != null && employees[i].emp_profile.work_location != null && employees[i].emp_profile.location.name != null) {
								document.getElementById('transfer_from_location_id').value=employees[i].emp_profile.work_location;  
								document.getElementById('transfer_from_location_name').textContent=employees[i].emp_profile.location.name;  
							}
						}
					}
				}               
			}
			else {
				$('#employee_code_div').hide();
				$('#designation_div').hide();
				$('#department_div').hide();
				$('#transfer_from_department_name_div').hide();
				$('#transfer_from_location_name_div').hide();
				document.getElementById('employee_code').textContent=''; 
				document.getElementById('designation').textContent=''; 
				document.getElementById('department').textContent='';   
				document.getElementById('transfer_from_department_id').value='';  
				document.getElementById('transfer_from_department_name').value=''; 
				document.getElementById('transfer_from_location_id').value=''; 
				document.getElementById('transfer_from_location_name').value='';   
				$('#tranfer_to_'+oldEmpId).prop('disabled', false);
			}			
		});
	});
	$('#perIntTransjoiningReportForm').validate({ 
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
	        transfer_from_department_id: {
	            required: true,
	        },
	        team_lead_or_reporting_manager: {
	            required: true,
	        },
	        transfer_from_location_id: {
	            required: true,
	        },
	        transfer_to_department_id: {
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