@extends('layouts.table')
@section('content')
@canany(['create-joining-report','current-user-create-joining-report','edit-joining-report','current-user-edit-joining-report','view-joining-report-listing','current-user-view-joining-report-listing'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report','current-user-create-joining-report','edit-joining-report','current-user-edit-joining-report','view-joining-report-listing','current-user-view-joining-report-listing']);
@endphp
@if ($hasPermission)                                           
<div class="card-header">
	<h4 class="card-title">
		@if($type == 'new_employee') New Employee @elseif($type == 'temporary') Temporary Internal Transfer @elseif($type == 'permanent') Permanent Internal Transfer @elseif($type == 'vacations_or_leave') Vacations Or Leave @endif Joining Report Info
	</h4>	
	@canany(['create-joining-report','current-user-create-joining-report'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-joining-report','current-user-create-joining-report']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('create_joining_report.create',$type) }}">
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
        @canany(['edit-joining-report','current-user-edit-joining-report','view-joining-report-listing','current-user-view-joining-report-listing'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-joining-report','current-user-edit-joining-report','view-joining-report-listing','current-user-view-joining-report-listing']);
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
							@if($type == 'new_employee')
							<th>Joining Type</th>
							@endif
							@if($type == 'permanent' OR $type == 'temporary')
							<th>Transfer From Department</th>
							<th>Transfer From Date</th>
							<th>Transfer From Location</th>
							<th>Transfer To Department</th>
							@endif
							<th>Joining Date</th>
							<th>Joining Location</th>
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
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate))
								{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->name ?? ''}}
								@endif
							</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate))
								{{ $data->candidate->employee_code ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->employee_code ?? '' }}
								@endif
							</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate) && isset($data->candidate->designation))
								{{ $data->candidate->designation->name ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->designation->name ?? '' }}
								@endif								
							</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate) && isset($data->candidate->department))
								{{ $data->candidate->department->name ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->department->name ?? '' }}
								@endif
								
							</td>
							@if($type == 'new_employee')
							<td>{{ $data->joining_type_name ?? ''}}</td>
							@endif
							@if($type == 'permanent' OR $type == 'temporary')
							<td>@if(isset($data) && isset($data->transferFromDepartment)){{ $data->transferFromDepartment->name ?? ''}} @endif</td>
							<td>
								@if($data->transfer_from_date != NULL)
								{{ \Carbon\Carbon::parse($data->transfer_from_date)->format('d M Y') }}
								@endif
							</td>
							<td>@if(isset($data) && isset($data->transferFromLocation)) {{ $data->transferFromLocation->name ?? ''}} @endif</td>
							<td>@if(isset($data) && isset($data->transferToDepartment)) {{ $data->transferToDepartment->name ?? ''}} @endif</td>
							@endif
							<td>
								@if($data->joining_date != NULL)
								{{ \Carbon\Carbon::parse($data->joining_date)->format('d M Y') }}
								@endif
							</td>
							<td>@if(isset($data) && isset($data->joiningLocation)) {{ $data->joiningLocation->name ?? '' }} @endif</td>
							<td>@if(isset($data) && isset($data->reportingManager)) {{ $data->reportingManager->name ?? '' }} @endif</td>
							<td>{{ $data->remarks ?? '' }}</td>
							<td>@if(isset($data) && isset($data->preparedBy)) {{ $data->preparedBy->name ?? '' }} @endif</td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
								@canany(['view-joining-report-details','current-user-view-joining-report-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-details','current-user-view-joining-report-details']);
								@endphp
								@if ($hasPermission) 
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>

								@endif
								@endcanany
									@if($data->department_head_action_at == NULL)
									@canany(['edit-joining-report','current-user-edit-joining-report'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-joining-report','current-user-edit-joining-report']);
								@endphp
								@if ($hasPermission) 
								<li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('joining_report.edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
										</a>
									</li>

								@endif
								@endcanany
                                    
									@endif
                                    <li>
										@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
												@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')					
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
														data-bs-target="#approve-joining-report-{{$data->id}}">
														<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
													</button>
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
														data-bs-target="#reject-joining-report-{{$data->id}}">
														<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
													</button>
												@elseif($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee' && $data->employee_id != NULL)	
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
														data-bs-target="#approve-joining-report-{{$data->id}}">
														<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
													</button>
													<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
														data-bs-target="#reject-joining-report-{{$data->id}}">
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
							@if($type == 'new_employee')
							<th>Joining Type</th>
							@endif
							@if($type == 'permanent' OR $type == 'temporary')
							<th>Transfer From Department</th>
							<th>Transfer From Date</th>
							<th>Transfer From Location</th>
							<th>Transfer To Department</th>
							@endif
							<th>Joining Date</th>
							<th>Joining Location</th>
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
							<td>@if($type == 'new_employee' && isset($data) && isset($data->candidate))
								{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->name ?? ''}}
								@endif</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate))
								{{ $data->candidate->employee_code ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->employee_code ?? '' }}
								@endif								
							</td>
							<td>
								@if($type == 'new_employee'  && isset($data) && isset($data->candidate) && isset($data->candidate->designation))
								{{ $data->candidate->designation->name ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->designation->name ?? '' }}
								@endif								
							</td>
							<td>
							@if($type == 'new_employee'  && isset($data) && isset($data->candidate) && isset($data->candidate->department))
								{{ $data->candidate->department->name ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->department->name ?? '' }}
								@endif	
								
							</td>
							@if($type == 'new_employee')
							<td>{{ $data->joining_type_name ?? ''}}</td>
							@endif
							@if($type == 'permanent' OR $type == 'temporary')
							<td>@if(isset($data) && isset($data->transferFromDepartment)) {{ $data->transferFromDepartment->name ?? ''}} @endif</td>
							<td>
								@if($data->transfer_from_date != NULL)
								{{ \Carbon\Carbon::parse($data->transfer_from_date)->format('d M Y') }}
								@endif
							</td>
							<td>{{ $data->transferFromLocation->name ?? ''}}</td>
							<td>{{ $data->transferToDepartment->name ?? ''}}</td>
							@endif
							<td>
								@if($data->joining_date != NULL)
								{{ \Carbon\Carbon::parse($data->joining_date)->format('d M Y') }}
								@endif
							</td>
							<td>{{ $data->joiningLocation->name ?? '' }}</td>
							<td>{{ $data->reportingManager->name ?? '' }}</td>
							<td>{{ $data->remarks ?? '' }}</td>
							<td>{{ $data->preparedBy->name ?? '' }}</td>
							<td>
							@canany(['view-joining-report-details','current-user-view-joining-report-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-details','current-user-view-joining-report-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>

								@endif
								@endcanany
								
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
							@if($type == 'new_employee')
							<th>Joining Type</th>
							@endif
							@if($type == 'permanent' OR $type == 'temporary')
							<th>Transfer From Department</th>
							<th>Transfer From Date</th>
							<th>Transfer From Location</th>
							<th>Transfer To Department</th>
							@endif
							<th>Joining Date</th>
							<th>Joining Location</th>
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
							<td>@if($type == 'new_employee' && isset($data) && isset($data->candidate))
								{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->name ?? ''}}
								@endif</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate))
								{{ $data->candidate->employee_code ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->employee_code ?? '' }}
								@endif
								
							</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate) && isset($data->candidate->designation))
								{{ $data->candidate->designation->name ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->designation->name ?? '' }}
								@endif								
							</td>
							<td>
								@if($type == 'new_employee' && isset($data) && isset($data->candidate) && isset($data->candidate->department))
								{{ $data->candidate->department->name ?? '' }}
								@elseif($type == 'permanent' OR $type == 'temporary' OR $type == 'vacations_or_leave')
								{{ $data->user->empProfile->department->name ?? '' }}
								@endif
								</td>
							@if($type == 'new_employee')
							<td>{{ $data->joining_type_name ?? ''}}</td>
							@endif
							@if($type == 'permanent' OR $type == 'temporary')
							<td>@if(isset($data) && isset($data->transferFromDepartment)) {{ $data->transferFromDepartment->name ?? ''}} @endif</td>
							<td>
								@if($data->transfer_from_date != NULL)
								{{ \Carbon\Carbon::parse($data->transfer_from_date)->format('d M Y') }}
								@endif
							</td>
							<td>@if(isset($data) && isset($data->transferFromLocation)) {{ $data->transferFromLocation->name ?? ''}} @endif</td>
							<td>@if(isset($data) && isset($data->transferToDepartment)) {{ $data->transferToDepartment->name ?? ''}} @endif</td>
							@endif
							<td>
								@if($data->joining_date != NULL)
								{{ \Carbon\Carbon::parse($data->joining_date)->format('d M Y') }}
								@endif
							</td>
							<td>@if(isset($data) && isset($data->joiningLocation)) {{ $data->joiningLocation->name ?? '' }} @endif</td>
							<td>@if(isset($data) && isset($data->reportingManager)) {{ $data->reportingManager->name ?? '' }} @endif</td>
							<td>{{ $data->remarks ?? '' }}</td>
							<td>@if(isset($data) && isset($data->preparedBy)) {{ $data->preparedBy->name ?? '' }} @endif</td>
							<td>
							@canany(['view-joining-report-details','current-user-view-joining-report-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-joining-report-details','current-user-view-joining-report-details']);
								@endphp
								@if ($hasPermission) 
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@endcanany
								
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