@extends('layouts.main')
@include('layouts.formstyle')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-leave','current-user-create-leave','edit-leave','current-user-edit-leave']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">@if($id == 'new')Create New @else Edit @endif Employee Leave Request</h4>
	@if($id != 'new')
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-leave.create-or-edit',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-leave.create-or-edit',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	@endif
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee_leave.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
	<form id="employeeLeaveForm" name="employeeLeaveForm" enctype="multipart/form-data" method="POST" action="{{route('employee-leave.store-or-update',$id)}}">
		@csrf
		<div class="row">
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
					<div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="employee_id" class="col-form-label text-md-end">{{ __('Employee Name') }}</label>
							<select name="employee_id" id="employee_id" multiple="true" class="employee_id form-control widthinput" onchange="" autofocus>
							@foreach($masterEmployees as $employee)
							<option value="{{$employee->id}}" @if($data->employee_id == $employee->id) selected @endif>{{$employee->name}}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="employee_code_div">
						<center><label for="employee_code" class="col-form-label text-md-end"><strong>{{ __('Employee Code') }}</strong></label></center>
						<center><span id="employee_code"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="designation_div">
						<center><label for="designation" class="col-form-label text-md-end"><strong>{{ __('Designation') }}</strong></label></center>
						<center><span id="designation"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="department_div">
						<center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
						<center><span id="department"></span></center>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="joining_date_div">
						<center><label for="joining_date" class="col-form-label text-md-end"><strong>{{ __('Joining Date') }}</strong></label></center>
						<center><span id="joining_date"></span></center>
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
				<h4 class="card-title">Leave Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-8 col-lg-8 col-md-8">
						<span class="error">* </span>
						<label for="type_of_leave" class="col-form-label text-md-end">{{ __('Leave Type') }}</label>
						<fieldset style="margin-top:5px;">
							<div class="row some-class">
								<div class="col-xxl-2 col-lg-2 col-md-2">
									<input type="radio" class="type_of_leave" name="type_of_leave" value="annual" id="annual" @if($data->type_of_leave == 'annual') checked @endif />
									<label for="annual">Annual</label>
								</div>
								<div class="col-xxl-2 col-lg-2 col-md-2">
									<input type="radio" class="type_of_leave" name="type_of_leave" value="sick" id="sick" @if($data->type_of_leave == 'sick') checked @endif />
									<label for="sick">Sick</label>
								</div>
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<input type="radio" class="type_of_leave" name="type_of_leave" value="unpaid" id="unpaid" @if($data->type_of_leave == 'unpaid') checked @endif />
									<label for="unpaid">Unpaid</label>
								</div>
								<div class="col-xxl-3 col-lg-3 col-md-3">
									<input type="radio" class="type_of_leave" name="type_of_leave" value="maternity_or_peternity" id="maternity_or_peternity" @if($data->type_of_leave == 'maternity_or_peternity') checked @endif />
									<label for="maternity_or_peternity">Maternity/Paternity</label>
								</div>
								<div class="col-xxl-2 col-lg-2 col-md-2">
									<input type="radio" class="type_of_leave" name="type_of_leave" value="others" id="others" @if($data->type_of_leave == 'others') checked @endif />
									<label for="others">Others</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4" id="other_leave_type">
						<span class="error">* </span>
						<label for="type_of_leave_description" class="col-form-label text-md-end">{{ __('Mention Leave Type If Others') }}</label>
						<input type="text" name="type_of_leave_description" id="type_of_leave_description"
							class="form-control widthinput" placeholder="Enter Leave Type If Others"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->type_of_leave_description ?? ''}}">
					</div>
				</div>
				<div class="row">
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="leave_start_date" class="col-form-label text-md-end">{{ __('Leave Start Date') }}</label>
						<input type="date" name="leave_start_date" id="leave_start_date" onchange="calculateDays()"
							class="form-control widthinput"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->leave_start_date ?? ''}}">
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<span class="error">* </span>
						<label for="leave_end_date" class="col-form-label text-md-end">{{ __('Leave End Date') }}</label>
						<input type="date" name="leave_end_date" id="leave_end_date" onchange="calculateDays()"
							class="form-control widthinput"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->leave_end_date ?? ''}}">
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<span class="error">* </span>
						<label for="total_no_of_days" class="col-form-label text-md-end">{{ __('Total Number Of Days') }}</label>
						<input type="number" name="total_no_of_days" id="total_no_of_days"
							class="form-control widthinput" placeholder="Enter Total Number Of Days"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->total_no_of_days ?? ''}}">
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<span class="error">* </span>
						<label for="no_of_paid_days" class="col-form-label text-md-end">{{ __('Number Of Paid Days(If Any)') }}</label>
						<input type="number" name="no_of_paid_days" id="no_of_paid_days" oninput="calculateNotPaid()"
							class="form-control widthinput" placeholder="Enter Number Of Paid Days(If Any)"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->no_of_paid_days ?? ''}}">
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<span class="error">* </span>
						<label for="no_of_unpaid_days" class="col-form-label text-md-end">{{ __('Number Of Unpaid Days(If Any)') }}</label>
						<input type="number" name="no_of_unpaid_days" id="no_of_unpaid_days" oninput="calculatePaid()"
							class="form-control widthinput" placeholder="Enter Number Of Unpaid Days(If Any)"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->no_of_unpaid_days ?? ''}}">
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Contact Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="address_while_on_leave" class="col-form-label text-md-end">{{ __('Address while on leave') }}</label>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<textarea rows="5" id="address_while_on_leave" type="text" class="form-control @error('address_while_on_leave') is-invalid @enderror"
							name="address_while_on_leave" placeholder="Enter Additional Remarks" value="{{ old('address_while_on_leave') }}"  autocomplete="address_while_on_leave"
							autofocus>{{$data->address_while_on_leave ?? ''}}</textarea>
						@error('address_while_on_leave')
						<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="alternative_home_contact_no" class="col-form-label text-md-end">{{ __('Home Contact Number') }}</label>
							<input type="tel" name="alternative_home_contact_no[main]" id="alternative_home_contact_no"
								class="form-control widthinput" placeholder="Enter Home Contact Number"
								aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->alternative_home_contact_no}}" oninput="validationOnKeyUp(this)">                                 
						</div>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="alternative_personal_email" class="col-form-label text-md-end">{{ __('Personal Email ') }}</label>
						<input type="text" name="alternative_personal_email" id="alternative_personal_email"
							class="form-control widthinput" placeholder="Enter Personal Email"
							aria-label="measurement" aria-describedby="basic-addon2" value="{{$data->alternative_personal_email ?? ''}}">
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
	var data = {!! json_encode($masterEmployees) !!};
	var oldData = {!! json_encode($data) !!};
	var id = {!! json_encode($id) !!}
	$(document).ready(function () {
		$('#employee_id').select2({
	           allowClear: true,
	           maximumSelectionLength: 1,
	           placeholder:"Choose Employee Name",
	       });
		if(id == 'new') {
			$("#employee_code_div").hide();
			$("#joining_date_div").hide();
			$("#designation_div").hide();
			$("#department_div").hide();
			$("#location_div").hide();
	           $("#other_leave_type").hide();
		}
		else {
			if(oldData.user.emp_profile.employee_code != null) {
				document.getElementById('employee_code').textContent=oldData.user.emp_profile.employee_code;
			}
			if(oldData.user.emp_profile.company_joining_date != null) {
				var userDate = oldData.user.emp_profile.company_joining_date; // Replace with your actual date
				var date = new Date(userDate);
				var day = date.getDate();
				var month = date.toLocaleString("default", { month: "short" }); // Get abbreviated month
				var year = date.getFullYear();
				var formattedDate = day + " " + month + " " + year;
				document.getElementById('joining_date').textContent=formattedDate;
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
			if(oldData.type_of_leave == 'others') {
				$("#other_leave_type").show();
			}
			else {
				$("#other_leave_type").hide();
			}
		}
	       $('.type_of_leave').click(function() {
	           if($(this).val() == 'others') {
	               $("#other_leave_type").show();
	           }
	           else {
	               $("#other_leave_type").hide();
	           }
	       });
	       $('#employee_id').on('change', function() {
	           var selectedEmpId = $(this).val();
	           if(selectedEmpId == '') {
	               $("#employee_code_div").hide();
	               $("#joining_date_div").hide();
	               $("#designation_div").hide();
	               $("#department_div").hide();
	               $("#location_div").hide();
	           }
	           else {
				document.getElementById('employee_code').textContent = '';
				document.getElementById('joining_date').textContent = '';
				document.getElementById('designation').textContent = '';
				document.getElementById('department').textContent = '';
				document.getElementById('location').textContent = '';
	               for (var i = 0; i < data.length; i++) {
	                   if (data[i].id == Number(selectedEmpId)) {
						if(data[i].emp_profile.employee_code != null) {
							document.getElementById('employee_code').textContent=data[i].emp_profile.employee_code;
						}
						if(data[i].emp_profile.company_joining_date != null) {
							var userDate = data[i].emp_profile.company_joining_date; // Replace with your actual date
							var date = new Date(userDate);
							var day = date.getDate();
							var month = date.toLocaleString("default", { month: "short" }); // Get abbreviated month
							var year = date.getFullYear();
							var formattedDate = day + " " + month + " " + year;
							document.getElementById('joining_date').textContent=formattedDate;
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
	               $("#employee_code_div").show();
	               $("#joining_date_div").show();
	               $("#designation_div").show();
	               $("#department_div").show();
	               $("#location_div").show();
	           }     
	       });
		var alternative_home_contact_no = window.intlTelInput(document.querySelector("#alternative_home_contact_no"), {
			separateDialCode: true,
			preferredCountries:["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});   
	});	
	// Adding a custom validation method named "endDateGreater"
    $.validator.addMethod("endDateGreater", function(value, element) {
        var startDate = $('#leave_start_date').val();
        var endDate = value;
        var start = new Date(startDate);
        var end = new Date(endDate);
        // Check if end date is greater than start date
        return this.optional(element) || end >= start;
    }, "End date must be greater than or equal to start date.");

	$.validator.addMethod("checkTotalDays", function(value, element) {
		var startDate = $('#leave_start_date').val(); 
		var endDate = $('#leave_end_date').val();  
		if(startDate != '' && endDate != '') {
			var start = new Date(startDate);
			var end = new Date(endDate);
			var difference = end - start;
			var days = difference / (1000 * 60 * 60 * 24) +1;
		}
        return this.optional(element) || value == days;
    }, "Total number of days must be same as the date differences");
	
	$.validator.addMethod("sumDays", function(value, element) {
		var totalDays = $('#total_no_of_days').val(); 
		var paidDays = $('#no_of_paid_days').val();  
		var unPaidDays = $('#no_of_unpaid_days').val();
		var daysSum =Number(paidDays)+Number(unPaidDays);
        return this.optional(element) || totalDays == daysSum;
    }, "Total days must be the sum of paid and unpaid days");

	$.validator.addMethod("paidZero", function(value, element) {
		var isUnpaid = false;
		var paidDays = $('#no_of_paid_days').val();  
		if ($('#unpaid').is(':checked') && paidDays > 0) {
			isUnpaid = true; 
        }
        return this.optional(element) || isUnpaid == false;
    }, "If leave type is unpaid , then we cannot give any paid days");
	
	$.validator.addMethod("dateExist", function(value, element) {
	    var result = false;
		var startDate = $('#leave_start_date').val(); 
		var endDate = $('#leave_end_date').val(); 
		var EmpId = $("#employee_id").val();
		$.ajax({
			type:"POST",
			async: false,
			url: "{{route('leave.checkLeaveDateAlreadyExist')}}", // script to validate in server side
			data: {startDate: startDate,
			endDate: endDate,
			EmpId: EmpId,
			id : id,
			_token: '{{csrf_token()}}'},
			success: function(data) {
				result = (data == true) ? true : false;
			}
		});
		return result; 
	}, "This date is alredy exist in the system, Please select different date.");
    // Applying validation rules to your form
    $("#employeeLeaveForm").validate({
        rules: {
            leave_start_date: {
                required: true,
                date: true, // Validates the start date format
				dateExist: true,
            },
            leave_end_date: {
                required: true,
                date: true, // Validates the end date format
                endDateGreater: true, // Custom rule for comparing dates
				dateExist: true,
            },
			employee_id: {
				required: true,
				// dateExist: true,
			},
			type_of_leave: {
				required: true,
			},           
			type_of_leave_description: {
				required: true,
			},
			total_no_of_days: {
				required: true,
				min:1,
				checkTotalDays: true, // Custom rule for checking total number of days
			},
			no_of_paid_days: {
				required: true,
				min:0,
				sumDays:true,
				paidZero:true,
			},
			no_of_unpaid_days: {
				required: true,
				min:0,
				sumDays:true,
			},
			address_while_on_leave: {
				required:true,
			},
			"alternative_home_contact_no[main]": {
				required:true,
				minlength: 5,
				maxlength: 20,
			},
			alternative_personal_email: {
				required:true,
				email:true,
			},
			type_of_leave_description: {                       
				required: function(element){
					if($("#type_of_leave").val() == 'others') {
						return false;
					}
					else {
						return true;
					}
				},
			},
        },
        messages: {
            leave_start_date: {
                required: "Please enter a start date.",
                date: "Please enter a valid date."
            },
            leave_end_date: {
                required: "Please enter an end date.",
                date: "Please enter a valid date.",
                endDateGreater: "End date must be greater than or equal to start date."
            },
			total_no_of_days: {
				checkTotalDays: "Total number of days must be same as the date differences"
			},
        },
		errorPlacement: function(error, element) {
            error.appendTo(element.parent());
			if ( element.prop( "type" ) === "tel" && element.closest('.select-button-main-div').length > 0 ) {
				if (!element.val() || element.val().length === 0) {
	                   console.log("Error is here with length", element.val().length);
	                   error.addClass('select-error');
	                   error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	               } else {
	                   console.log("No error");
	               }
	           }
        }
    });
	   function validationOnKeyUp(currentPriceInput) {
	       var id = currentPriceInput.id;
	       var input = document.getElementById(id);
	       var val = input.value;
	       val = val.replace(/^0+|[^\d]/g, '');
	       input.value = val;
	   }
	   function calculateDays() { 
		var startDate = $('#leave_start_date').val(); 
		var endDate = $('#leave_end_date').val();     
		if(startDate != '' && endDate != '') {
			var start = new Date(startDate);
			var end = new Date(endDate);
			var difference = end - start;
			var days = difference / (1000 * 60 * 60 * 24) +1;
			document.getElementById('total_no_of_days').value = days;
		}
	   }
	   function calculateNotPaid() {
		var totalDays = $('#total_no_of_days').val(); 
		var paidDays = $('#no_of_paid_days').val();  
		if(totalDays != '' && paidDays != '') {
			document.getElementById('no_of_unpaid_days').value = totalDays-paidDays;
		}
		else if(paidDays == '') {
			document.getElementById('no_of_unpaid_days').value = ''; 
		}
	   }
	   function calculatePaid() {
		var totalDays = $('#total_no_of_days').val(); 
		var unPaidDays = $('#no_of_unpaid_days').val();
		if(totalDays != '' && unPaidDays != '') {
			document.getElementById('no_of_paid_days').value = totalDays-unPaidDays;
		}
		else if(unPaidDays == '') {
			document.getElementById('no_of_paid_days').value = ''; 
		}
	   }
</script>
@endsection