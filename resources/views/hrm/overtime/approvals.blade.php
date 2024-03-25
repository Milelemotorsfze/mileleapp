@extends('layouts.table')
@section('content')
@if(Auth::user()->overtime_request_approval['can'] == true)
@if(count($employeePendings) > 0 || count($employeeApproved) > 0 || count($employeeRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Overtime Request Approvals By Employee
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
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeePendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.overtime.approve_reject_modal')
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
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
		Employee Overtime Request Approvals By Reporting Manager
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
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>												
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true )
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.overtime.approve_reject_modal')
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
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
		Employee Overtime Application Request Approvals By Division Head
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
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.overtime.approve_reject_modal')
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
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
		Employee Overtime Application Request Approvals By HR Manager
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
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-overtime-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.overtime.approve_reject_modal')
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
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
							<th>Total Number Of Overtime Hours</th>
							<th>Overtime Start Date</th>
							<th>Overtime End Date</th>
							<th>Name Of Reporting Manager</th>
							<th>Name Of Division Head</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->location->name ?? '' }}</td>
							<td>{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y') ?? ''}}</td>
							<td>{{ $data->total_hours ?? ''}}</td>
							<td>
								@if($data->minStartDateTime->start_datetime != '')
								{{\Carbon\Carbon::parse($data->minStartDateTime->start_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>
								@if($data->maxStartDateTime->end_datetime != '')
								{{\Carbon\Carbon::parse($data->maxStartDateTime->end_datetime)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->current_status ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('overtime.show',$data->id)}}">
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
@endif
@endsection