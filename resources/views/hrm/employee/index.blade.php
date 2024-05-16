@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
	.form-label {
	margin-top: 0.5rem;
	}
	.iti {
	width: 100%;
	}
	.texttransform {
	text-transform: capitalize;
	}
	.light {
	background-color:#e6e6e6!important;
	font-weight: 700!important;
	}
	.dark {
	background-color:#d9d9d9!important;
	font-weight: 700!important;
	}
	.paragraph-class {
	color: red;
	font-size:11px;
	}
	.other-error {
	color: red;
	}
	.table-edits input, .table-edits select {
	height:38px!important;
	}
</style>
@section('content')
<div class="card-header">
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-employee-listing']);
	@endphp
	@if ($hasPermission)
	<h4 class="card-title">
		Employee Informations
	</h4>
	@endif
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="">
	<i class="fa fa-plus" aria-hidden="true"></i> New Employee
	</a>
	@endif
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	@if (Session::has('error'))
	<div class="alert alert-danger" >
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('error') }}
	</div>
	@endif
	@if (Session::has('success'))
	<div class="alert alert-success" id="success-alert">
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('success') }}
	</div>
	@endif
</div>
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-employee-listing']);
@endphp
@if ($hasPermission)
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#active_personal_info">Personal Info</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#active_contact_info">Contact Info</a>
		</li>
        <li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#active_visa_info">Visa Info</a>
		</li>
        <li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#active_employment_info">Employment Info</a>
		</li>
        <li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#active_compensation_benefits">Compensation & Benefits</a>
		</li>
        <li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#active_off_boarding">Off Boarding</a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="active_personal_info">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
                            <!-- <th>Sl No</th> -->
							<th>Code</th>
							<th>Name</th>
                            <th>Designation</th>
							<th>Department</th>
                            <th>Gender</th>
							<th>DOB</th>
                            <th>Birthday Month</th>
							<th>Age</th>
                            <th>Marital Status</th>
							<th>Religion</th>
                            <th>Nationality</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<!-- <td>{{ ++$i }}</td> -->
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? $data->first_name.' '.$data->last_name ?? '' }}</td>
							<td>{{ $data->designation->name ?? '' }}</td>
							<td>{{ $data->department->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('d M Y')}}@endif</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('F')}}@endif	</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->age}}@endif</td>
							<td>{{$data->maritalStatus->name ?? ''}}</td>
							<td>{{$data->religionName->name ?? ''}}</td>
							<td>{{$data->countryMaster->nationality ?? $data->countryMaster->name ?? $data->countryMaster->iso_3166_code ?? ''}}</td>
                            @include('hrm.employee.action')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="active_contact_info">
		<div class="card-body">
			<div class="table-responsive">
			<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="light">Code</th>
                            <th rowspan="2" class="dark">Name</th>
							<th colspan="8" class="light">
								<center>CONTACT INFORMATION</center>
							</th>
							<th colspan="5" class="dark">
								<center>EMERGENCY CONTACT IN UAE</center>
							</th>
							<th colspan="6" class="light">
								<center>EMERGENCY CONTACT IN HOME COUNTRY</center>
							</th>
						</tr>
						<tr>
							<td class="light">Company Number</td>
							<td class="light">Personal Number</td>
							<td class="light">Personal Email</td>
							<td class="light">Company Email</td>
							<td class="light">Father's Name</td>
							<td class="light">Mother's Name</td>
                            <td class="light">UAE Address</td>
							<td class="light">Home Address</td>

							<td class="dark">Name</td>
							<td class="dark">Relation</td>
							<td class="dark">Contact Number</td>
							<td class="dark">Alternative Number</td>
							<td class="dark">Email</td>

							<td class="light">Name</td>
							<td class="light">Relation</td>
							<td class="light">Contact Number</td>
							<td class="light">Alternative Number</td>
							<td class="light">Email</td>
							<td class="light">Home Address</td>

							
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? '' }}</td>
                            <td>{{$data->company_number ?? ''}}</td>
							<td>{{$data->contact_number ?? ''}}</td>
							
							<td>{{$data->personal_email_address ?? ''}}</td>
							<td>{{$data->user->email ?? ''}}</td>
							<td>{{$data->name_of_father ?? ''}}</td>
							<td>{{$data->name_of_mother ?? ''}}</td>
							<td>{{$data->address_uae ?? ''}}</td>
							<td>{{$data->address_home ?? ''}}</td>

							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>

							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>							
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
    <div class="tab-pane fade show" id="active_visa_info">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
                            <!-- <th>Sl No</th> -->
							<th>Code</th>
							<th>Name</th>
                            <th>CEC/Person Code No.</th>
							<th>Emirates ID</th>
                            <th>Emirates ID Expiry</th>
							<th>Passport Number</th>
                            <th>Passport Issue Date</th>
							<th>Passport Expiry</th>
                            <th>Passport Issued Place</th>
							<th>Passport Status</th>
                            <th>Passport Status Remarks</th>
                            <th>Visa Type</th>
                            <th>Visa Number</th>
                            <th>Visa Issue Date</th>
							<th>Visa Expiry Date</th>
                            <th>Visa Renewal Reminder</th>
                            <th>Visa Issuing Country</th>
                            <th>Sponsorship</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<!-- <td>{{ ++$i }}</td> -->
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? $data->first_name.' '.$data->last_name ?? '' }}</td>
							<td>{{$data->cec_or_person_code_number ?? ''}}</td>
							<td>{{$data->emirates_id ?? ''}}</td>
							<td>@if($data->emirates_expiry != ''){{\Carbon\Carbon::parse($data->emirates_expiry)->format('d M Y')}}@endif</td>
							<td>{{$data->passport_number ?? ''}}</td>
							<td>@if($data->passport_issue_date != ''){{\Carbon\Carbon::parse($data->passport_issue_date)->format('d M Y')}}@endif</td>
							<td>@if($data->passport_expiry_date != ''){{\Carbon\Carbon::parse($data->passport_expiry_date)->format('d M Y')}}@endif</td>
							<td>{{$data->passport_place_of_issue ?? ''}}</td>
							<td>{{$data->passport_status_name ?? ''}}</td>
							<td></td>
							<td>{{$data->visaType->name ?? ''}}</td>
							<td>{{$data->visa_number ?? ''}}</td>
							<td>@if($data->visa_issue_date != ''){{\Carbon\Carbon::parse($data->visa_issue_date)->format('d M Y')}}@endif</td>
							<td>@if($data->visa_expiry_date != ''){{\Carbon\Carbon::parse($data->visa_expiry_date)->format('d M Y')}}@endif</td>
							<td>@if($data->reminder_date_for_visa_renewal != ''){{\Carbon\Carbon::parse($data->reminder_date_for_visa_renewal)->format('d M Y')}}@endif</td>
							<td>@if($data->visa_issueing_country == 253) UAE @else {{$data->visaIssueCountry->name ?? ''}} @endif</td>
							<td>{{$data->sponsorshipName->name ?? ''}}</td>
                            <!-- @include('hrm.employee.action') -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
    <div class="tab-pane fade show" id="active_employment_info">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
                            <!-- <th>Sl No</th> -->
							<th>Code</th>
							<th>Name</th>
                            <th>Joining Date</th>
							<th>Status</th>
							<th>Status Date</th>
							<th>Pobation Duration</th>
							<th>Probation Period Start</th>
							<th>Probation Period End</th>
                            <th>Employment Contract Type</th>
							<th>Employment Contract Start</th>
                            <th>Employment Contract End</th>
							<th>Contract Probation Period</th>
							<th>Contract Probation End</th>
							<th>Work Location</th>
                            <th>Division</th>
							<th>Team Lead/Reporting Manager</th>
                            <th>Division Head</th>

                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<!-- <td>{{ ++$i }}</td> -->
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? $data->first_name.' '.$data->last_name ?? '' }}</td>
							<td>@if($data->company_joining_date != ''){{\Carbon\Carbon::parse($data->company_joining_date)->format('d M Y')}}@endif</td>
							<td>{{$data->current_status_name ?? ''}}</td>
							<td>@if($data->status_date != ''){{\Carbon\Carbon::parse($data->status_date)->format('d M Y')}}@endif</td>
							<td>{{$data->probation_duration_in_months ?? ''}}@if($data->probation_duration_in_months > 0 ) Months @endif</td>
							<td>@if($data->probation_period_start_date != ''){{\Carbon\Carbon::parse($data->probation_period_start_date)->format('d M Y')}}@endif</td>
							<td>@if($data->probation_period_end_date != ''){{\Carbon\Carbon::parse($data->probation_period_end_date)->format('d M Y')}}@endif</td>
							<td>{{$data->employment_contract_name ?? ''}}</td>
							<td>@if($data->employment_contract_start_date != ''){{\Carbon\Carbon::parse($data->employment_contract_start_date)->format('d M Y')}}@endif</td>
							<td>@if($data->employment_contract_end_date != ''){{\Carbon\Carbon::parse($data->employment_contract_end_date)->format('d M Y')}}@endif</td>
							<td>{{$data->employment_contract_probation_period_in_months ?? ''}}@if($data->employment_contract_probation_period_in_months > 0 ) Months @endif</td>
							<td>@if($data->employment_contract_probation_end_date != ''){{\Carbon\Carbon::parse($data->employment_contract_probation_end_date)->format('d M Y')}}@endif</td>
							<td>{{$data->location->name ?? ''}}</td>
							<td>{{$data->department->division->name ?? ''}}</td>
							<td>{{$data->teamLeadOrReportingManager->name ?? ''}}</td>
							<td>{{$data->department->division->divisionHead->name ?? ''}}</td>
                            <!-- @include('hrm.employee.action') -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
    <div class="tab-pane fade show" id="active_compensation_benefits">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
                            <!-- <th>Sl No</th> -->
							<th>Code</th>
							<th>Name</th>
                            <th>Basic Salary</th>
							<th>Other Allowances</th>
                            <th>Total Salary</th>
							<th>Increment Effective Date</th>
                            <th>Increment Amount</th>
							<th>Revised Basic Salary</th>
                            <th>Revised Other Allowance</th>
							<th>Revised Total Salary</th>
                            <th>Insurance Policy No.</th>
                            <th>Insurance Card No.</th>
                            <th>Insurance Policy Start</th>
                            <th>Insurance Policy End</th>
							<th>Birthday Gift PO for Year</th>
                            <th>Birthday Gift PO Number</th>
                            <th>Ticket Allowance Eligibility Year</th>
                            <th>Ticket Allowance Eligibility Date</th>
							<th>Ticket Allowance PO for Year</th>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<!-- <td>{{ ++$i }}</td> -->
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? $data->first_name.' '.$data->last_name ?? '' }}</td>
							<td>{{$data->basic_salary .' AED' ?? ''}}</td>
							<td>{{$data->other_allowances .' AED' ?? ''}}</td>
							<td>{{$data->total_salary .' AED' ?? ''}}</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<!-- @include('hrm.employee.action') -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
    <div class="tab-pane fade show" id="active_off_boarding">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
                            <!-- <th>Sl No</th> -->
							<th>Code</th>
							<th>Name</th>
                            <td>Leaving Type</td>
							<td>Leaveing Reason</td>
							<td>Notice Period to Serve</td>
							<td>Notice Period Duration</td>
							<td>Last Working Day</td>
							<td>Visa Cancellation Received Date</td>
                            <td>Change Status Date/Exit UAE Date</td>
							<td>Insurance Cancellation Done</td>
                            <th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<!-- <td>{{ ++$i }}</td> -->
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? $data->first_name.' '.$data->last_name ?? '' }}</td>
							<td class="texttransform">{{ $data->leaving_type ?? '' }}</td>
							<td>{{ $data->leaving_reason ?? '' }}</td>
							<td class="texttransform">{{$data->notice_period_to_serve ?? ''}}</td>
							<td>{{ $data->notice_period_duration ?? '' }} @if($data->notice_period_duration != '') Days @endif</td>
							<td>@if($data->last_working_day != ''){{\Carbon\Carbon::parse($data->last_working_day)->format('d M Y') ?? ''}}@endif</td>
							<td>@if($data->visa_cancellation_received_date != ''){{\Carbon\Carbon::parse($data->visa_cancellation_received_date)->format('d M Y') ?? ''}}@endif</td>
							<td>@if($data->change_status_or_exit_UAE_date != ''){{\Carbon\Carbon::parse($data->change_status_or_exit_UAE_date)->format('d M Y') ?? ''}}@endif</td>
							<td class="texttransform">{{ $data->insurance_cancellation_done ?? '' }}</td>
                           	<!-- @include('hrm.employee.action') -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')
<script type="text/javascript">
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
	jQuery.validator.setDefaults({
	       errorClass: "is-invalid",
	       errorElement: "p",
	       errorPlacement: function ( error, element ) {
	           error.addClass( "invalid-feedback font-size-13" );
	           if ( element.prop( "type" ) === "tel" ) {
	               if (!element.val() || element.val().length === 0) {
	                   console.log("Error is here with length", element.val().length);
	                   error.addClass('select-error');
	                   error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	               } else {
	                   console.log("No error");
	               }
	           }
	           else
			 if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
	               if (!element.val() || element.val().length === 0) {
	                   console.log("Error is here with length", element.val().length);
	                   error.addClass('select-error');
	                   error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
	               } else {
	                   console.log("No error");
	               }
	           }
			// else if (element.parent().hasClass('input-group')) {
	           //     error.insertAfter(element.parent());
	           // }
	           else {
	               error.insertAfter( element );
	           }
	       }
	   });
	   jQuery.validator.addMethod("uniqueEmail", 
	       function(value, element) {
	           var result = false;
	           $.ajax({
	               type:"POST",
	               async: false,
	               url: "{{route('user.uniqueEmail')}}", // script to validate in server side
	               data: {email: value},
	               success: function(data) {
	                   result = (data == true) ? true : false;
	               }
	           });
	           // return true if username is exist in database
	           return result; 
	       }, 
	       "This Email is already taken! Try another."
	   );
	   $.validator.addMethod("domain", function(value, element) {
    return this.optional(element) || /^.+@milele\.com$/.test(value);
}, "Please enter an email address with the domain '@milele.com'");

	$('.give-system-access-class').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			$('#form_'+id).validate({ 
				rules: {
					email: {
						required: true,
						email: true,
						uniqueEmail : true,
						domain: true
					},
					name: {
						required:true,
					}
				},
			});
		})
</script>

@endpush