@extends('layouts.main')
@include('layouts.formstyle')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report','current-user-create-joining-report']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Create Temporary Internal Transfer Joining Report</h4>
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
								@foreach($employees as $employee)
								<option value="{{$employee->id}}">{{$employee->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-4 col-md-4" id="employee_code_div">
						<center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
						<center><span id="employee_code_name"></span></center>
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
				<h4 class="card-title">Temporary Internal Transfer Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<input type="hidden" name="joining_type" value="internal_transfer">
					<input type="hidden" name="internal_transfer_type" value="temporary">
					<input type="hidden" name="transfer_from_department_id" id="transfer_from_department_id" value="">
					<input type="hidden" name="transfer_from_location_id" id="transfer_from_location_id" value="">
					<input type="hidden" name="employee_code" id="employee_code" value="">
					<div class="col-xxl-6 col-lg-6 col-md-6" id="transfer_from_department_name_div">
						<div >
							<center><label for="transfer_from_department_id" class="col-form-label text-md-end">{{ __('Transfer From Department') }}</label></center>
						</div>
						<div>
							<center><span id="transfer_from_department_name"></span></center>
						</div>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6" id="transfer_from_location_name_div">
						<div>
							<center><label for="transfer_from_location_id" class="col-form-label text-md-end">{{ __('Transfer From Location') }}</label></center>
						</div>
						<div>
							<center><span id="transfer_from_location_name"></span></center>
						</div>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="transfer_from_date" class="col-form-label text-md-end">{{ __('Transfer From Date') }}</label>
						<input id="transfer_from_date" type="date" class="form-control widthinput @error('transfer_from_date') is-invalid @enderror" name="transfer_from_date"
							placeholder="Transfer From Date" value="" autocomplete="transfer_from_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="joining_date" class="col-form-label text-md-end">{{ __('Transfer To Date') }}</label>
						<input id="joining_date" type="date" class="form-control widthinput @error('joining_date') is-invalid @enderror" name="joining_date"
							placeholder="Transfer From Date" value="" autocomplete="joining_date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
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
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
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
	var oldEmpId ='';
	$(document).ready(function () {
	    $('#employee_code_div').hide();
	$('#designation_div').hide();
	$('#department_div').hide();
	$('#transfer_from_department_name_div').hide();
	$('#transfer_from_location_name_div').hide();
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
					document.getElementById('employee_code_name').textContent=employees[i].emp_profile.employee_code;     
					document.getElementById('employee_code').value=employees[i].emp_profile.employee_code;                        
				}
				if(employees[i].emp_profile != null && employees[i].emp_profile.designation != null && employees[i].emp_profile.designation.name != null) {
					document.getElementById('designation').textContent=employees[i].emp_profile.designation.name;
				}
				if(employees[i].emp_profile != null && employees[i].emp_profile.department != null && employees[i].emp_profile.department.name != null) {
					document.getElementById('department').textContent=employees[i].emp_profile.department.name;  
					document.getElementById('transfer_from_department_id').value=employees[i].emp_profile.department_id;  
					document.getElementById('transfer_from_department_name').textContent=employees[i].emp_profile.department.name; 
					oldEmpId = employees[i].emp_profile.department_id;
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
	document.getElementById('employee_code_name').textContent=''; 
	document.getElementById('employee_code').textContent=''; 
	document.getElementById('designation').textContent=''; 
	document.getElementById('department').textContent='';   
	document.getElementById('transfer_from_department_id').value='';  
	document.getElementById('transfer_from_department_name').textContent=''; 
	document.getElementById('transfer_from_location_id').value=''; 
	document.getElementById('transfer_from_location_name').textContent='';   
	}			
	});
	});
	jQuery.validator.setDefaults({
	    errorClass: "is-invalid",
	    errorElement: "p",     
	});
	jQuery.validator.addMethod("greaterStart", function (value, element, params) {
	    var startDate = $('#transfer_from_date').val();
	    var endDate = $('#joining_date').val();
	
	    if( startDate >= endDate) {
	        return false;
	    }else{
	        return true;
	    }
	},'Must be greater than start date.');
	jQuery.validator.addMethod("deptLoc", function (value, element, params) {
	var fromDept = '';
	    var fromLoc = '';
	var toDept = '';
	    var toLoc = '';
	    fromDept = $('#transfer_from_department_id').val();
	    fromLoc = $('#transfer_from_location_id').val();
	toDept = $('#transfer_to_department_id').val();
	    toLoc = $('#joining_location').val();
	    if(fromDept == toDept && fromLoc == toLoc) {
	        return false;
	    }else{
	        return true;
	    }
	},"can't transfer to the same departmenta and location");
	$('#joiningReportForm').validate({ 
	    rules: {
	        employee_id: {
	required: true,
	},
	joining_date: {
	required: true,
	greaterStart: true,
	},
	        joining_location: {
	            required: true,
	deptLoc: true,
	        },
	        transfer_from_date: {
	            required: true,
	        },
	        transfer_to_department_id: {
	            required: true,
	        },
	    },
	errorPlacement: function ( error, element ) {
	        error.addClass( "invalid-feedback font-size-13" );
	
	if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
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