@extends('layouts.table')
@section('content')
@canany(['create-joining-report','edit-joining-report','view-joining-report-listing'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report','edit-joining-report','view-joining-report-listing']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		New Employee Joining Report Info
	</h4>	
	@canany(['create-joining-report'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('joining_report.create') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Joining Report
    </a>
	@endif
	@endcanany
	
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
        @canany(['edit-joining-report','view-joining-report-listing'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-joining-report','view-joining-report-listing']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-hiring-requests">Pending</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
		</li>
        @endif
        @endcanany
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
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Joining Type</th>
							<th>Joining Date</th>
							<th>Location</th>
							<th>Reporting Manager</th>
							<th>Remarks</th>
							<th>Prepared By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employee->first_name ?? ''}} {{$data->employee->last_name ?? ''}}</td>
							<td>{{ $data->employee->employee_code ?? '' }}</td>
							<td>{{ $data->employee->designation->name ?? '' }}</td>
							<td>{{ $data->employee->department->name ?? '' }}</td>
							<td>@if($data->trial_period_joining_date) Trial Period Joining @elseif($data->permanent_joining_date) Permanent Joining @endif</td>
							<td>{{ $data->trial_period_joining_date ?? $data->permanent_joining_date ?? '' }}</td>
							<td>{{ $data->permanentJoiningLocation->name ?? '' }}</td>
							<td>{{ $data->reportingManager->name ?? '' }}</td>
							<td>{{ $data->remarks ?? '' }}</td>
							<td>{{ $data->preparedBy->name ?? '' }}</td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
									@if($data->department_head_action_at == NULL)
                                    <li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('joining_report.edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
										</a>
									</li>
									@endif
                                    <li>
										@if(isset($type))
											@if($type == 'approve')
												<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
													data-bs-target="#approve-employee-hiring-request-{{$data->id}}">
													<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
												</button>
												<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
													data-bs-target="#reject-employee-hiring-request-{{$data->id}}">
													<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
												</button>
											@endif
										@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
												@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')					
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
														data-bs-target="#approve-employee-hiring-request-{{$data->id}}">
														<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
													</button>
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
														data-bs-target="#reject-employee-hiring-request-{{$data->id}}">
														<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
													</button>
												@elseif($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee' && $data->employee->user_id != NULL)	
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
														data-bs-target="#approve-employee-hiring-request-{{$data->id}}">
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
							@include('hrm.onBoarding.joiningReport.approve_reject_modal')					
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
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Joining Type</th>
							<th>Joining Date</th>
							<th>Location</th>
							<th>Reporting Manager</th>
							<th>Remarks</th>
							<th>Prepared By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $data)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $data->employee->first_name ?? ''}} {{$data->employee->last_name ?? ''}}</td>
							<td>{{ $data->employee->employee_code ?? '' }}</td>
							<td>{{ $data->employee->designation->name ?? '' }}</td>
							<td>{{ $data->employee->department->name ?? '' }}</td>
							<td>@if($data->trial_period_joining_date) Trial Period Joining @elseif($data->permanent_joining_date) Permanent Joining @endif</td>
							<td>{{ $data->trial_period_joining_date ?? $data->permanent_joining_date ?? '' }}</td>
							<td>{{ $data->permanentJoiningLocation->name ?? '' }}</td>
							<td>{{ $data->reportingManager->name ?? '' }}</td>
							<td>{{ $data->remarks ?? '' }}</td>
							<td>{{ $data->preparedBy->name ?? '' }}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
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
	<div class="tab-pane fade show" id="rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Employee Name</th>
							<th>Employee Code</th>
							<th>Designation</th>
							<th>Department</th>
							<th>Joining Type</th>
							<th>Joining Date</th>
							<th>Location</th>
							<th>Reporting Manager</th>
							<th>Remarks</th>
							<th>Prepared By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $data)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $data->employee->first_name ?? ''}} {{$data->employee->last_name ?? ''}}</td>
							<td>{{ $data->employee->employee_code ?? '' }}</td>
							<td>{{ $data->employee->designation->name ?? '' }}</td>
							<td>{{ $data->employee->department->name ?? '' }}</td>
							<td>@if($data->trial_period_joining_date) Trial Period Joining @elseif($data->permanent_joining_date) Permanent Joining @endif</td>
							<td>{{ $data->trial_period_joining_date ?? $data->permanent_joining_date ?? '' }}</td>
							<td>{{ $data->permanentJoiningLocation->name ?? '' }}</td>
							<td>{{ $data->reportingManager->name ?? '' }}</td>
							<td>{{ $data->remarks ?? '' }}</td>
							<td>{{ $data->preparedBy->name ?? '' }}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
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
@endcanany
@endsection
@push('scripts')
<script type="text/javascript">
	var approved = {!! json_encode($approved) !!};
	$(document).ready(function () {
		var countApproved = 0;
		countApproved = approved.length;
		if(countApproved > 0 ) {
			for(var i=0; i<countApproved; i++) {
				$('#candidate_id_'+approved[i].id).select2({
					allowClear: true,
					placeholder:"Choose Selected Candidates Name",
					dropdownParent: $('#closed-hiring-request-'+approved[i].id)
				});
			}
		}
	})
	function inputNumberAbs(currentPriceInput) 
	{
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
	$('.hiring-request-delete').on('click',function(){
        let id = $(this).attr('data-id');
        let url =  $(this).attr('data-url');
        var confirm = alertify.confirm('Are you sure you want to Delete this Employee Hiring Request ?',function (e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        _method: 'DELETE',
                        id: 'id',
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        location.reload();
                        alertify.success('Employee Hiring Request Deleted successfully.');
                    }
                });
            }
        }).set({title:"Delete Employee Hiring Request"})
    });
</script>
@endpush