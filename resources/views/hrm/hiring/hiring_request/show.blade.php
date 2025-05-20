@extends('layouts.table')
<style>
	.texttransform {
	text-transform: capitalize;
	}
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	background-color: #c1e1fb !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	/* color: black; */
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
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary','view-questionnaire-details','view-current-user-questionnaire','view-all-hiring-request-details','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> Employee Hiring Request Details</h4>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-12">
			@if($previous != '')
			<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
			@endif
			@if($next != '')
			<a  class="btn btn-sm btn-info float-first" href="{{ route('employee-hiring-request.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			@endif
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-12">
			<center><label for="choices-single-default" class="form-label"> UUID :</label>
				<span style="color:#fd625e;"><strong>{{$data->uuid ?? ''}}</strong></span>
			</center>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-12">
			<a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
		</div>
	</div>
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
	<div class="portfolio">
		<ul class="nav nav-pills nav-fill" id="my-tab">
			@php
			$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-details-of-current-user']);
			@endphp
			@if ($hasPermission)
			<li class="nav-item">
				<a class="nav-link active" data-bs-toggle="pill" href="#requests"> Hiring Request</a>
			</li>
			@endif
			@php
			$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-history','view-all-hiring-request-approval-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user']);
			@endphp
			@if ($hasPermission)
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#approvals-and-history"> Approvals and History</a>
			</li>
			@endif
			@php
			$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-questionnaire-details','view-current-user-questionnaire']);
			@endphp
			@if ($hasPermission)
			@if(isset($data->questionnaire) OR isset($data->jobDescription)) 
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#questionnaire-and-job-descriptions">Questionnaire and Job Description</a>
			</li>
			@endif
			@endif
			@php
			$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
			@endphp
			@if ($hasPermission)
			@if(count($data->allInterview) > 0)
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#interview-summary-report">Interview Summary Report</a>
			</li>
			@endif
			@endif
		</ul>
	</div>
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-details-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-content">
		<div class="tab-pane fade show active" id="requests">
			<br>
			<div class="card">
				<div class="card-header" style="background-color:#e8f3fd;">
					<div class="row">
						<div class="col-lg-10 col-md-3 col-sm-6 col-12">
							<h4 class="card-title">
								<center>Hiring request Info</center>
							</h4>
						</div>
						<div class="col-lg-2 col-md-3 col-sm-6 col-12">
							@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
							@if(isset($data->is_auth_user_can_approve['can_approve']))
							@if($data->is_auth_user_can_approve['can_approve'] == true)
							<button style="float:right;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
								data-bs-target="#reject-employee-hiring-request-{{$data->id}}">
							<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
							</button>
							<button style="float:right; margin-right:5px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
								data-bs-target="#approve-employee-hiring-request-{{$data->id}}">
							<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
							</button>
							@include('hrm.hiring.hiring_request.approve_reject_modal')
							@endif
							@endif
							@endif
							@if($data->status == 'pending')
							@php
							$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-employee-hiring-request','edit-current-user-hiring-request']);
							@endphp
							@if ($hasPermission)
							<a style="float:right; margin-right:5px;" title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create-or-edit',$data->id)}}">
							<i class="fa fa-edit" aria-hidden="true"></i> Edit
							</a>
							@endif
							@endif
						</div>
					</div>
				</div>
				<div class="card-body">
					@include('hrm.hiring.hiring_request.details')
				</div>
			</div>
		</div>
	</div>
	@endif
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-history','view-all-hiring-request-approval-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-content">
		<div class="tab-pane fade show" id="approvals-and-history">
			<br>
			<div class="row">
				@php
				$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-approval-details','view-hiring-request-approval-details-of-current-user']);
				@endphp
				@if ($hasPermission)
				<div class="col-xxl-6 col-lg-6 col-md-6">
					<div class="card">
						<div class="card-header" style="background-color:#e8f3fd;">
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
												<h4 class="card-title">Team Lead / Reporting Manager</h4>
											</center>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-lg-2 col-md-12 col-sm-12">
													Name :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->department_head_name ?? ''}}
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
													@if($data->department_head_action_at != '')
													{{$data->comments_by_department_head ?? ''}}
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="card">
										<div class="card-header">
											<center>
												<h4 class="card-title">Recruiting Manager</h4>
											</center>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-lg-2 col-md-12 col-sm-12">
													Name :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->hiring_manager_name ?? ''}}
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Status :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													<label class="badge texttransform @if($data->action_by_hiring_manager =='pending') badge-soft-info 
														@elseif($data->action_by_hiring_manager =='approved') badge-soft-success 
														@else badge-soft-danger @endif">{{$data->action_by_hiring_manager ?? ''}}</label>
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Date & Time :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													@if($data->hiring_manager_action_at != '')
													{{ \Carbon\Carbon::parse($data->hiring_manager_action_at)->format('d M Y, H:i:s') }}
													@endif
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Comments :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													@if($data->hiring_manager_action_at != '')
													{{$data->comments_by_hiring_manager ?? ''}}
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="card">
										<div class="card-header">
											<center>
												<h4 class="card-title">Division Head</h4>
											</center>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-lg-2 col-md-12 col-sm-12">
													Name :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->divisionHead->name ?? ''}}
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Status :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													<label class="badge texttransform @if($data->action_by_division_head =='pending') badge-soft-info 
														@elseif($data->action_by_division_head =='approved') badge-soft-success 
														@else badge-soft-danger @endif">{{$data->action_by_division_head ?? ''}}</label>
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Date & Time :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													@if($data->division_head_action_at != '')
													{{ \Carbon\Carbon::parse($data->division_head_action_at)->format('d M Y, H:i:s') }}
													@endif
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Comments :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													@if($data->division_head_action_at != '')
													{{$data->comments_by_division_head ?? ''}}
													@endif
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
													{{$data->hr_manager_name ?? ''}}
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
													@if($data->hr_manager_action_at != '')
													{{$data->comments_by_hr_manager ?? ''}}
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				@php
				$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-history','view-hiring-request-history-of-current-user']);
				@endphp
				@if ($hasPermission)
				<div class="col-xxl-6 col-lg-6 col-md-6">
					<div class="card">
						<div class="card-header" style="background-color:#e8f3fd;">
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
				@endif
			</div>
		</div>
	</div>
	@endif
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-questionnaire-details','view-current-user-questionnaire']);
	@endphp
	@if ($hasPermission)
	<div class="tab-content">
		<div class="tab-pane fade show" id="questionnaire-and-job-descriptions">
			<br>
			<div class="row">
				@include('hrm.hiring.hiring_request.questionnaire_details')
				@include('hrm.hiring.hiring_request.job_description_details')
			</div>
		</div>
	</div>
	@endif
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
	@endphp
	@if ($hasPermission)
	@if(count($data->allInterview) > 0)
	<div class="tab-content">
		<div class="tab-pane fade show" id="interview-summary-report">
			<br>
			<div class="row">
				@include('hrm.hiring.hiring_request.interviewSummaryReport')
			</div>
		</div>
	</div>
	@endif
	@endif
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