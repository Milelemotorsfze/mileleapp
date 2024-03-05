@extends('layouts.main')
@include('layouts.formstyle')
@section('content')
@canany(['create-joining-report','current-user-create-joining-report'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report','current-user-create-joining-report']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Create Vacations Or Leave Joining Report</h4>
	
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
	<form id="vacOrlejoiningReportForm" name="vacOrlejoiningReportForm" enctype="multipart/form-data" method="POST" action="{{route('joining_report.store')}}">
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
				<input id="employee_code" type="text" class="form-control widthinput @error('employee_code') is-invalid @enderror" name="employee_code"
                                placeholder="employee Code" autocomplete="employee_code" autofocus>
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
				<h4 class="card-title">Leave Joining Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <input type="hidden" name="joining_type" value="vacations_or_leave">
                    <div class="col-xxl-4 col-lg-4 col-md-4">
                        <span class="error">* </span>
                        <label for="joining_date" class="col-form-label text-md-end">{{ __('Joining Date') }}</label>
                        <input id="joining_date" type="date" class="form-control widthinput @error('joining_date') is-invalid @enderror" name="joining_date"
                                placeholder="Candidate Name" value="" autocomplete="joining_date" autofocus>
                    </div>
					<div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="joining_location" class="col-form-label text-md-end">{{ __('Choose Location') }}</label>
							<select name="joining_location" id="joining_location" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterlocations as $location)
									<option value="{{$location->id}}">{{$location->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
					<label for="choose_leaves" class="col-form-label text-md-end">{{ __('Choose Leaves') }}</label> </br>  
					<select name="choose_leaves[]" id="choose_leaves" multiple="true" class="form-control widthinput" onchange="" autofocus>
						
					</select>
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
    var employees = {!! json_encode($employees) !!};
	$(document).ready(function () {
        $('#employee_code_div').hide();
		$('#designation_div').hide();
		$('#department_div').hide();
        $('#employee_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Employee Name",
        });
		$('#joining_location').select2({
            allowClear: true,
			maximumSelectionLength: 1,
            placeholder:"Choose Employee Joining Location",
        });
		$('#choose_leaves').select2({
            allowClear: true,
            placeholder:"Choose Employee Leaves",
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
							if(employees[i].emp_profile != null && employees[i].emp_profile.employee_code != null) {
								document.getElementById('employee_code').value=employees[i].emp_profile.employee_code;                        
							}
							if(employees[i].emp_profile != null && employees[i].emp_profile.designation != null && employees[i].emp_profile.designation.name != null) {
								document.getElementById('designation').textContent=employees[i].emp_profile.designation.name;
							}
							if(employees[i].emp_profile != null && employees[i].emp_profile.department != null && employees[i].emp_profile.department.name != null) {
								document.getElementById('department').textContent=employees[i].emp_profile.department.name;  
							}
							for(var j=0; j<employees[i].approved_leaves.length; j++) {
								$('#choose_leaves').append('<option value="'+employees[i].approved_leaves[j].id+'">'+employees[i].approved_leaves[j].leave_type+' ('+employees[i].approved_leaves[j].leave_start_date+' To '+employees[i].approved_leaves[j].leave_end_date+')</option>')
								// .append('<option value="'+ value +'" > File '+ key +'(' + value + ')'+'</option>');
							}
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
	$('#vacOrlejoiningReportForm').validate({ 
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
            employee_code: {
                required: true,
            },
			choose_leaves: {
				required:true,
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