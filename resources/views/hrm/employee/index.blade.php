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
			<a class="nav-link active" data-bs-toggle="pill" href="#shortlisted-for-interview">Active</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#telephonic_interview">Inactive</a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="shortlisted-for-interview">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="11" class="dark">
								<center>PERSONAL INFORMATION</center>
							</th>
							<th colspan="8" class="light">
								<center>CONTACT INFORMATION</center>
							</th>
							<th colspan="5" class="dark">
								<center>EMERGENCY CONTACT IN UAE</center>
							</th>
							<th colspan="6" class="light">
								<center>EMERGENCY CONTACT IN HOME COUNTRY</center>
							</th>
							<th colspan="16" class="dark">
								<center>VISA INFORMATION</center>
							</th>
							<th colspan="15" class="light">
								<center>EMPLOYMENT INFORMATION</center>
							</th>
							<th colspan="17" class="dark"><center>COMPENSATION & BENEFITS</center></th>
							<th colspan="8" class="light"><center>OFF BOARDING</center></th>
						</tr>
						<tr>
							<td class="dark">Employee Code</td>
							<td class="dark">Name</td>
                            <td class="dark">Designation</td>
							<td class="dark">Department</td>
                            <td class="dark">Gender</td>
							<td class="dark">DOB</td>
                            <td class="dark">Birthday Month</td>
							<td class="dark">Age</td>
                            <td class="dark">Marital Status</td>
							<td class="dark">Religion</td>
                            <td class="dark">Nationality</td>

							<td class="light">Company Number</td>
							<td class="light">Personal Number</td>
							<td class="light">Personal Email</td>
							<td class="light">Company Email</td>
							<td class="light">Father's Name</td>
							<td class="light">Mother's Name</td>
                            <td class="light">UAE Address</td>
							<td class="light">Home Country Address</td>

							<td class="dark">Name</td>
							<td class="dark">Reletion</td>
							<td class="dark">Contact Number</td>
							<td class="dark">Alternative Number</td>
							<td class="dark">Email</td>

							<td class="light">Name</td>
							<td class="light">Reletion</td>
							<td class="light">Contact Number</td>
							<td class="light">Alternative Number</td>
							<td class="light">Email</td>
							<td class="light">Home Country Address</td>

							<td class="dark">CEC/Person Code No.</td>
							<td class="dark">Emirates ID</td>
                            <td class="dark">Emirates ID Expiry</td>
							<td class="dark">Passport Number</td>
                            <td class="dark">Passport Issue Date</td>
							<td class="dark">Passport Expiry</td>
                            <td class="dark">Passport Issued Place</td>
							<td class="dark">Passport Status</td>
                            <td class="dark">Passport Status Remarks</td>
                            <td class="dark">Visa Type</td>
                            <td class="dark">Visa Number</td>
                            <td class="dark">Visa Issue Date</td>
							<td class="dark">Visa Expiry Date</td>
                            <td class="dark">Visa Renewal Reminder</td>
                            <td class="dark">Visa Issuing Country</td>
                            <td class="dark">Sponsorship</td>

                            <td class="light">Joining Date</td>
							<td class="light">Status</td>
							<td class="light">Status Date</td>
							<td class="light">Pobation Duration</td>
							<td class="light">Probation Period Start</td>
							<td class="light">Probation Period End</td>
                            <td class="light">Employment Contract Type</td>
							<td class="light">Employment Contract Start</td>
                            <td class="light">Employment Contract End</td>
							<td class="light">Contract Probation Period</td>
							<!-- <td class="light">Contract Pobation Duration</td> -->
							<td class="light">Contract Probation End</td>
							<td class="light">Work Location</td>
                            <td class="light">Division</td>
							<td class="light">Team Lead/Reporting Manager</td>
                            <td class="light">Division Head</td>

							<td class="dark">Basic Salary</td>
							<td class="dark">Other Allowances</td>
                            <td class="dark">Total Salary</td>
							<td class="dark">Increment Effective Date</td>
                            <td class="dark">Increment Amount</td>
							<td class="dark">Revised Basic Salary</td>
                            <td class="dark">Revised Other Allowance</td>
							<td class="dark">Revised Total Salary</td>
                            <td class="dark">Insurance Policy No.</td>
                            <td class="dark">Insurance Card No.</td>
                            <td class="dark">Insurance Policy Start</td>
                            <td class="dark">Insurance Policy End</td>
							<td class="dark">Birthday Gift PO for Year</td>
                            <td class="dark">Birthday Gift PO Number</td>
                            <td class="dark">Ticket Allowance Eligibility Year</td>
                            <td class="dark">Ticket Allowance Eligibility Date</td>
							<td class="dark">Ticket Allowance PO for Year</td>

							<td class="light">Leaving Type</td>
							<td class="light">Leaveing Reason</td>
							<td class="light">Notice Period to Serve</td>
							<td class="light">Notice Period Duration</td>
							<td class="light">Last Working Day</td>
							<td class="light">Visa Cancellation Received Date</td>
                            <td class="light">Change Status Date/Exit UAE Date</td>
							<td class="light">Insurance Cancellation Done</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($activeEmployees as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employee_code ?? ''}}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->designation->name ?? '' }}</td>
							<td>{{ $data->department->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('d M Y')}}@endif</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('F')}}@endif	</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->age}}@endif</td>
							<td>{{$data->maritalStatus->name ?? ''}}</td>
							<td>{{$data->religionName->name ?? ''}}</td>
							<td>{{$data->countryMaster->nationality ?? $data->countryMaster->name ?? $data->countryMaster->iso_3166_code ?? ''}}</td>

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
	<div class="tab-pane fade show" id="telephonic_interview">
		<div class="card-body">
			<div class="table-responsive">
				
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
</script>

@endpush