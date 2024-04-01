@extends('layouts.table')
@section('content')
@if(Auth::user()->leave_request_approval['can'] == true)
@if(count($employeePendings) > 0 || count($employeeApproved) > 0 || count($employeeRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Leave Request Approvals By Employee
	</h4>
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
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#employee-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#employee-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#employee-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="employee-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeePendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.leave.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="employee-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="employee-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($HRManagerPendings) > 0 || count($HRManagerApproved) > 0 || count($HRManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Leave Request Approvals By HR Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#hr-manager-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-manager-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-manager-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="hr-manager-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.leave.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hr-manager-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hr-manager-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($ReportingManagerPendings) > 0 || count($ReportingManagerApproved) > 0 || count($ReportingManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Leave Request Approvals By Reporting Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#reporting-manager-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#reporting-manager-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#reporting-manager-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="reporting-manager-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>												
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true )
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.leave.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="reporting-manager-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="reporting-manager-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($divisionHeadPendings) > 0 || count($divisionHeadApproved) > 0 || count($divisionHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Leave Request Approvals By Division Head
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#division-head-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#division-head-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#division-head-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="division-head-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-leave-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.leave.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="division-head-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date!= '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="division-head-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Location</th>
							<th>Joining Date</th>
							<th>Leave Type</th>
							<th>Leave Details</th>
							<th>Leave Start Date</th>
							<th>Leave End Date</th>
							<th>Total Number Of Days</th>
							<th>Number Of Paid Days(If Any)</th>
							<th>Number Of Unpaid Days(If Any)</th>
							<th>Address While On Leave</th>
							<th>Home Contact Number</th>
							<th>Personal Email</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->leave_type ?? ''}}</td>
							<td>{{ $data->type_of_leave_description ?? ''}}</td>
							<td>
								@if($data->leave_start_date != '')
								{{\Carbon\Carbon::parse($data->leave_start_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->leave_end_date != '')
								{{\Carbon\Carbon::parse($data->leave_end_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->total_no_of_days ?? ''}}</td>
							<td>{{ $data->no_of_paid_days ?? ''}}</td>
							<td>{{ $data->no_of_unpaid_days ?? ''}}</td>
							<td>{{ $data->address_while_on_leave ?? ''}}</td>
							<td>{{ $data->alternative_home_contact_no ?? ''}}</td>
							<td>{{ $data->alternative_personal_email ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
@endif
@endsection
@push('scripts')
<script type="text/javascript">
	var ReportingManagerPendings = {!! json_encode($ReportingManagerPendings) !!}
</script>
@endpush