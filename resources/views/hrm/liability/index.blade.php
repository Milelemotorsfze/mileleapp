@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-liability','current-user-create-liability','edit-liability','current-user-edit-liability','view-liability-list','current-user-view-liability-list']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Employee Liability Info
	</h4>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-liability','current-user-create-liability']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('employee-liability.create-or-edit','new') }}">
	<i class="fa fa-plus" aria-hidden="true"></i> New Liability
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
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if($data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-liability-details','current-user-view-liability-details']);
										@endphp
										@if ($hasPermission)  
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										@endif
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-liability','current-user-edit-liability']);
										@endphp
										@if ($hasPermission)  
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('employee-liability.create-or-edit',$data->id) }}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-employee-liability-request-{{$data->id}}">
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
												data-bs-target="#approve-employee-liability-request-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-employee-liability-request-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							@include('hrm.liability.approve_reject_modal')					
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
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if($data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-liability-details','current-user-view-liability-details']);
								@endphp
								@if ($hasPermission)  
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
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
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if($data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-liability-details','current-user-view-liability-details']);
								@endphp
								@if ($hasPermission)  
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
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