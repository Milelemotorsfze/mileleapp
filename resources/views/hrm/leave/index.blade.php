@extends('layouts.table')
<style>
	.required-class {
	margin-top: .25rem;
	font-size: 80%;
	color: #fd625e;
	}
	.widthinput {
	height:32px!important;
	}
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-leave','current-user-create-leave','edit-leave','current-user-edit-leave','view-leave-list','current-user-view-leave-details']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Employee Leave Info
	</h4>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-leave','current-user-create-leave']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('employee-leave.create-or-edit','new') }}">
	<i class="fa fa-plus" aria-hidden="true"></i> New Leave
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
				<table id="pending-hiring-requests-table" class="table table-striped table-editable table-edits table">
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
						@foreach ($pendings as $key => $data)
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
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-leave-details','current-user-view-leave-details']);
										@endphp
										@if ($hasPermission) 
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										@endif
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-leave','current-user-edit-leave']);
										@endphp
										@if ($hasPermission) 
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('employee-leave.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-employee-leave-request-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-employee-hiring-request-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-employee-leave-request-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-employee-hiring-request-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							@include('hrm.leave.approve_reject_modal')					
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
				<table id="approved-hiring-requests-table" class="table table-striped table-editable table-edits table">
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
						@foreach ($approved as $key => $data)
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
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-leave-details','current-user-view-leave-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
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
				<table id="rejected-hiring-requests-table" class="table table-striped table-editable table-edits table">
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
						@foreach ($rejected as $key => $data)
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
							<td>							@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
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
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-leave-details','current-user-view-leave-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$data->id)}}">
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