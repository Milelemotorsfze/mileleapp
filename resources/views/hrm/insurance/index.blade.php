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
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-insurance','edit-insurance','view-all-list-insurance','view-current-user-list-insurance','view-all-insurance-details','view-current-user-insurance-details']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Employee Insurance Info
	</h4>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-insurance']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('insurance.create') }}">
	<i class="fa fa-plus" aria-hidden="true"></i> New Insurance
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
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="pending-hiring-requests">
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
							<th>Insurance Policy Number</th>
							<th>Insurance Card Number</th>
							<th>Insurance Policy Start Date</th>
							<th>Insurance Policy End Date</th>
							<th>Insurance Cancellation Done</th>
							<th>Created By</th>
							<th>Updated By</th>
							<th>Updated At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($datas as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>{{ $data->user->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->employee_code ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->department->name ?? '' }}</td>
							<td>{{ $data->insurance_policy_number ?? ''}}</td>
							<td>{{ $data->insurance_card_number ?? ''}}</td>
							<td>
								@if($data->insurance_policy_start_date != '')
								{{ \Carbon\Carbon::parse($data->insurance_policy_start_date)->format('d M Y') ?? ''}}	
								@endif
							</td>
							<td>
								@if($data->insurance_policy_end_date != '')
								{{ \Carbon\Carbon::parse($data->insurance_policy_end_date)->format('d M Y') ?? ''}}	
								@endif
							</td>
							<td>{{ $data->insurance_cancellation_done ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->updatedBy->name ?? ''}}</td>
							<td>
								@if($data->updated_by != NULL)
								{{ \Carbon\Carbon::parse($data->updated_at)->format('d M Y, H:i:s') ?? ''}}	
								@endif	
							</td>
							<td>							
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-insurance-details','view-current-user-insurance-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('insurance.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-insurance']);
								@endphp
								@if ($hasPermission) 								
								<a title="Edit" class="btn btn-sm btn-info" href="{{route('insurance.edit',$data->id)}}">
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