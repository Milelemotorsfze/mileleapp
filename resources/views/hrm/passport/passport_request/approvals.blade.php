@extends('layouts.table')
@section('content')
@if(Auth::user()->passport_submit_request_approval['can'] == true)
@if(count($employeePendings) > 0 || count($employeeApproved) > 0 || count($employeeRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Passport Submit Request Approvals By Employee
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
			<a class="nav-link active" data-bs-toggle="pill" href="#prepared-by-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#prepared-by-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#prepared-by-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="prepared-by-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeePendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i>
								</button>
								@elseif($data->is_auth_user_can_approve['can_approve'] == true)	
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.passport.passport_request.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="prepared-by-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
	<div class="tab-pane fade show" id="prepared-by-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
@if(count($reportingManagerPendings) > 0 || count($reportingManagerApproved) > 0 || count($reportingManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Passport Submit Request Approvals By Reporting Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#team-lead-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#team-lead-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#team-lead-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="team-lead-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($reportingManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>												
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i>
								</button>
								@elseif($data->is_auth_user_can_approve['can_approve'] == true)	
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.passport.passport_request.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="team-lead-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($reportingManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
	<div class="tab-pane fade show" id="team-lead-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($reportingManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
		Employee Passport Submit Request Approvals By Division Head
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#hr-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="hr-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i>
								</button>
								@elseif($data->is_auth_user_can_approve['can_approve'] == true)	
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.passport.passport_request.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hr-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
	<div class="tab-pane fade show" id="hr-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
@if(count($hrManagerPendings) > 0 || count($hrManagerApproved) > 0 || count($hrManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Passport Submit Request Approvals By HR Manager
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
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($hrManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i>
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i>
								</button>
								@elseif($data->is_auth_user_can_approve['can_approve'] == true)	
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-passport-submit-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.passport.passport_request.approve_reject_modal')
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
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($hrManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Request Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($hrManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y')}}
								@endif										
							</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_request.show',$data->id)}}">
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