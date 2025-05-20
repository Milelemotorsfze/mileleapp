@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-department-listing','view-current-user-department-lising']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		Master Department Information
	</h4>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-department']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('department.create') }}">
	<i class="fa fa-plus" aria-hidden="true"></i> New Department
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
							<th>Department</th>
							<th>Department Head</th>
							<th>Approval HandOver To (Optional)</th>
							<th>Department Division</th>
							<th>Division Head</th>
							<th>Is Demand Planning</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($data as $key => $dataOne)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $dataOne->name ?? ''}}</td>
							<td>{{ $dataOne->departmentHead->name ?? '' }}</td>
							<td>
								@if($dataOne->department_head_id != $dataOne->approval_by_id)
								{{ $dataOne->departmentApprovalBy->name ?? '' }}
								@endif
							</td>
							<td>{{ $dataOne->division->name ?? '' }}</td>
							<td>{{ $dataOne->division->divisionHead->name ?? '' }}</td>
							<td> 
								@if($dataOne->is_demand_planning == 1)
									Yes
								@else
									No
								@endif
							</td>
							<td>
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-department-details']);
								@endphp
								@if ($hasPermission)  
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('department.show',$dataOne->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-department','edit-current-user-department']);
								@endphp
								@if ($hasPermission)
								<a title="Edit" class="btn btn-sm btn-info" href="{{route('department.edit',$dataOne->id) }}">
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