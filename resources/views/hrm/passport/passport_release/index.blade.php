@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-passport-request','current-user-create-passport-request','edit-passport-request','current-user-edit-passport-request','view-passport-request-list','current-user-view-passport-request-details']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Passport Release Info
	</h4>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-passport-request','current-user-create-passport-request']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('employee-passport_request.create-or-edit','new') }}">
	<i class="fa fa-plus" aria-hidden="true"></i> New Passport Request
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
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-hiring-requests">Pending</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
		</li>
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
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
										@endphp
										@if ($hasPermission)                                           
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_release.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										@endif
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-passport-request','current-user-edit-passport-request']);
										@endphp
										@if ($hasPermission)                                           
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('passport_release.edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										<li>									
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-passport-release-request-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-passport-release-request-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							@include('hrm.passport.passport_release.approve_reject_modal')					
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
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
								@endphp
								@if ($hasPermission)                                           
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_release.show',$data->id)}}">
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
							<th>Employee Name</th>
							<th>Employee ID</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Purpose</th>
							<th>Date</th>
							<th>Reporting Manager</th>
							<th>Division Head</th>
							<th>HR Manager</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $rejectedOne)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->purpose->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>{{ $data->reportingManager->name ?? ''}}</td>
							<td>{{ $data->divisionHead->name ?? ''}}</td>
							<td>{{ $data->hrManager->name ?? ''}}</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-passport-request-details','current-user-view-passport-request-details']);
								@endphp
								@if ($hasPermission)                                           
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('passport_release.show',$data->id)}}">
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
	function inputNumberAbs(currentPriceInput) {
	    var id = currentPriceInput.id;
	    var input = document.getElementById(id);
	    var val = input.value;
	    val = val.replace(/^0+|[^\d.]/g, '');
	    if(val.split('.').length>2) 
	    {
	        val =val.replace(/\.+$/,"");
	    }
	    input.value = val;
	}
</script>
@endpush