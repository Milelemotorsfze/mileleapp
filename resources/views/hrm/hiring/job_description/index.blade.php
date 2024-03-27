@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-job-description','edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list','view-approved-job-description-list','view-current-user-approved-job-description-list','view-rejected-job-description-list','view-current-user-rejected-job-description-list','view-job-description-details','view-current-user-job-description-details','view-job-description-approvals-details','view-current-user-job-description-approvals-details']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Job Description Info
	</h4>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-job-description']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('employee-hiring-job-description.create-or-edit', ['id' => 'new', 'hiring_id' => 'new']) }}">
	<i class="fa fa-plus" aria-hidden="true"></i> New Job Description
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
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		@php
		$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list']);
		@endphp
		@if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-hiring-requests">Pending</a>
		</li>
		@endif
		@php
		$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list']);
		@endphp
		@if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved</a>
		</li>
		@endif
		@php
		$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-job-description','edit-current-user-job-description','view-pending-job-description-list','view-current-user-pending-job-description-list']);
		@endphp
		@if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
		</li>
		@endif
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>HR Manager Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif	
							</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-job-description-details','view-current-user-job-description-details']);
										@endphp
										@if ($hasPermission) 
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										@endif
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-job-description','edit-current-user-job-description']);
										@endphp
										@if ($hasPermission) 
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('employee-hiring-job-description.create-or-edit',['id' => $data->id, 'hiring_id' => $data->hiring_request_id])}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-hiring-job-description-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-hiring-job-description-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-hiring-job-description-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-hiring-job-description-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>HR Manager Action</th>
							<th>HR Manager Action At</th>
							<th>HR Manager Comment</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif	
							</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>@if($data->department_head_action_at != '')
								{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>@if($data->hr_manager_action_at != '')
								{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-job-description-details','view-current-user-job-description-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>HR Manager Action</th>
							<th>HR Manager Action At</th>
							<th>HR Manager Comment</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif	
							</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>@if($data->department_head_action_at != '')
								{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>@if($data->hr_manager_action_at != '')
								{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y, H:i:s')}}
								@endif	
							</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-job-description-details','view-current-user-job-description-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-job-description','edit-current-user-job-description']);
								@endphp
								@if ($hasPermission) 
								<a title="Edit" class="btn btn-sm btn-info" href="{{route('employee-hiring-job-description.create-or-edit',['id' => $data->id, 'hiring_id' => $data->hiring_request_id])}}">
								<i class="fa fa-edit" aria-hidden="true"></i> 
								</a>
								@endif
							</td>
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