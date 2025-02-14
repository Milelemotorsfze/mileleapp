@extends('layouts.table')
<style>
	.texttransform {
	text-transform: capitalize;
	}
	/* element.style {
	} */
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	background-color: #c1e1fb !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	/* color: black!important; */
	/* background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important; */
	background: #072c47 !important;
	}
	.nav-link:focus{
	color: black!important;
	}
	.nav-link:hover {
	color: black!important;
	}
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['dept-emp-view-joining-report-details','view-joining-report-details','current-user-view-joining-report-details','view-permanent-joining-report-details','view-current-user-permanent-joining-report-details',]);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Employee Joining Report Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('joining_report.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('joining_report.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	<a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
<div class="card-body">
	<div class="row">
		<div class="col-xxl-6 col-lg-6 col-md-12">
			<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Employee Details</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Employee Name :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								@if($data->joining_type == 'new_employee')
								{{ $data->candidate->first_name ?? '' }} {{ $data->candidate->last_name ?? '' }}
								@else
								{{ $data->user->empProfile->first_name ?? $data->user->name ?? '' }} {{ $data->user->empProfile->last_name ?? '' }}
								@endif
								</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Employee Code :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								@if($data->joining_type == 'new_employee')
								{{ $data->candidate->employee_code ?? '' }}
								@else
								{{ $data->user->empProfile->employee_code ?? '' }}
								@endif                                       
								</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Designation :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								@if($data->joining_type == 'new_employee')
								{{ $data->candidate->employee_code ?? '' }}
								@else
								{{ $data->user->empProfile->designation->name ?? '' }}
								@endif                                       
								</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Department :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								@if($data->joining_type == 'new_employee')
								{{ $data->candidate->department->name ?? '' }}
								@else
								{{ $data->user->empProfile->department->name ?? '' }}
								@endif   
								</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Reporting Manager :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								{{ $data->reportingManager->name ?? '' }}
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Joining Details</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Joining Type :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->joining_type_name ?? ''}}</span>
							</div>
							@if($data->joining_type_name == 'Internal Transfer - Temporary' OR $data->joining_type_name == 'Internal Transfer - Permanent')
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Transfer From Department :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>@if(isset($data) && isset($data->transferFromDepartment)){{ $data->transferFromDepartment->name ?? ''}} @endif</span>
							</div>
							@endif
							@if($data->joining_type_name == 'Internal Transfer - Temporary')
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Transfer From Date :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								@if($data->transfer_from_date != NULL)
								{{ \Carbon\Carbon::parse($data->transfer_from_date)->format('d M Y') }}
								@endif
								</span>
							</div>
							@endif
							@if($data->joining_type_name == 'Internal Transfer - Temporary' OR $data->joining_type_name == 'Internal Transfer - Permanent')
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Transfer From Location :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>@if(isset($data) && isset($data->transferFromLocation)) {{ $data->transferFromLocation->name ?? ''}} @endif</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Transfer To Department :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>@if(isset($data) && isset($data->transferToDepartment)) {{ $data->transferToDepartment->name ?? ''}} @endif</span>
							</div>
							@endif
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> @if($data->joining_type_name == 'Internal Transfer - Temporary' OR $data->joining_type_name == 'Internal Transfer - Permanent') Transfer To @else Joining @endif Date :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>
								@if($data->joining_date != NULL)
								{{ \Carbon\Carbon::parse($data->joining_date)->format('d M Y') }}
								@endif
								</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> @if($data->joining_type_name == 'Internal Transfer - Temporary' OR $data->joining_type_name == 'Internal Transfer - Permanent') Transfer To @endif Location :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->joiningLocation->name ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Remarks :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->remarks ?? '' }}</span>
							</div>
							<div class="col-lg-5 col-md-5 col-sm-6 col-12">
								<label for="choices-single-default" class="form-label"> Prepared By :</label>
							</div>
							<div class="col-lg-7 col-md-7 col-sm-6 col-12">
								<span>{{ $data->preparedBy->name ?? '' }}</span>
							</div>
						</div>
					</div>
				</div>
				@if($data->joining_type_name == 'Vacations Or Leave')
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Leave Details</h4>
					</div>
					<div class="card-body">
						<div class="tab-pane fade show active">
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-editable table-edits table">
										<thead>
											<tr>
												<th>Sl No</th>
												<th>Leave Type</th>
												<th>Leave Details</th>
												<th>Leave Start Date</th>
												<th>Leave End Date</th>
												<th>Total Number Of Days</th>
												<th>Number Of Paid Days(If Any)</th>
												<th>Number Of Unpaid Days(If Any)</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<div hidden>{{$i=0;}}</div>
											@foreach ($data->leave as $key => $leave)
											<tr data-id="1">
												<td>{{ ++$i }}</td>
												<td>{{ $leave->leave_type ?? ''}}</td>
												<td>{{ $leave->type_of_leave_description ?? ''}}</td>
												<td>
													@if($leave->leave_start_date != '')
													{{\Carbon\Carbon::parse($leave->leave_start_date)->format('d M Y') ?? ''}}
													@endif
												</td>
												<td>
													@if($leave->leave_end_date != '')
													{{\Carbon\Carbon::parse($leave->leave_end_date)->format('d M Y') ?? ''}}
													@endif
												</td>
												<td>{{ $leave->total_no_of_days ?? ''}}</td>
												<td>{{ $leave->no_of_paid_days ?? ''}}</td>
												<td>{{ $leave->no_of_unpaid_days ?? ''}}</td>
												<td>
													@php
													$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-leave-details','current-user-view-leave-details']);
													@endphp
													@if ($hasPermission) 
													<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_leave.show',$leave->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i> View Details
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
				</div>
				@endif
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							<center>History</center>
						</h4>
					</div>
					<div class="card-body">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							@if(isset($data->history))
							@foreach($data->history as $history)
							<div class="row">
								<div class="col-xxl-1 col-lg-1 col-md-1">
									<img src="{{ asset('icons/' . $history->icon) }}" style="width:30px;height:30px;">
								</div>
								<div class="col-xxl-11 col-lg-11 col-md-11">
									{{$history->message ?? ''}} </br> <span style="color:gray">
									@if($history->created_at != '')
									{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, H:i:s') }}
									@endif
									</span>
								</div>
							</div>
							</br>
							@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">
						<center>Approvals By</center>
					</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">Prepared by</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{ $data->preparedBy->name ?? '' }}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->action_by_prepared_by =='pending') badge-soft-info 
												@elseif($data->action_by_prepared_by =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->action_by_prepared_by ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->prepared_by_action_at != '')
											{{ \Carbon\Carbon::parse($data->prepared_by_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->comments_by_prepared_by ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">Employee</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->joining_type == 'new_employee')
											{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
											@else
											{{ $data->user->empProfile->first_name ?? $data->user->name ?? ''}} {{$data->user->empProfile->last_name ?? ''}}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->action_by_employee =='pending') badge-soft-info 
												@elseif($data->action_by_employee =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->action_by_employee ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->employee_action_at != '')
											{{ \Carbon\Carbon::parse($data->employee_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->comments_by_employee ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">HR Manager</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->hr->name ?? ''}}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->action_by_hr_manager =='pending') badge-soft-info 
												@elseif($data->action_by_hr_manager =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->action_by_hr_manager ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->hr_manager_action_at != '')
											{{ \Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->comments_by_hr_manager ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header">
									<center>
										<h4 class="card-title">Reporting Manager</h4>
									</center>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-lg-2 col-md-12 col-sm-12">
											Name :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{ $data->reportingManager->name ?? '' }}
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Status :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											<label class="badge texttransform @if($data->action_by_department_head =='pending') badge-soft-info 
												@elseif($data->action_by_department_head =='approved') badge-soft-success 
												@else badge-soft-danger @endif">{{$data->action_by_department_head ?? ''}}</label>
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Date & Time :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											@if($data->department_head_action_at != '')
											{{ \Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s') }}
											@endif
										</div>
										<div class="col-lg-2 col-md-12 col-sm-12">
											Comments :
										</div>
										<div class="col-lg-10 col-md-12 col-sm-12">
											{{$data->comments_by_department_head ?? ''}}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
<script>
	$(document).ready(function () {
	
	    $('.delete-button').on('click',function(){
	        let id = $(this).attr('data-id');
	        let url =  $(this).attr('data-url');
	        var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
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
	                        alertify.success('Item Deleted successfully.');
	                    }
	                });
	            }
	        }).set({title:"Delete Item"})
	    });
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
</script>
@endpush