@extends('layouts.table')
<style>
  .light {
	background-color:#e6e6e6!important;
	font-weight: 700!important;
  }
  .dark {
	background-color:#d9d9d9!important;
	font-weight: 700!important;
  }
	</style>
@section('content')
@canany(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
	'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
	'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
	'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
	'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
	'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user'
	,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
	,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request','edit-employee-hiring-request','view-all-pending-hiring-request-listing',
	'view-all-approved-hiring-request-listing','view-all-closed-hiring-request-listing','view-all-on-hold-hiring-request-listing',
	'view-all-cancelled-hiring-request-listing','view-all-rejected-hiring-request-listing','view-pending-hiring-request-listing-of-current-user',
	'view-approved-hiring-request-listing-of-current-user','view-closed-hiring-request-listing-of-current-user','view-on-hold-hiring-request-listing-of-current-user',
	'view-cancelled-hiring-request-listing-of-current-user','view-rejected-hiring-request-listing-of-current-user','view-all-deleted-hiring-request-listing',
	'view-deleted-hiring-request-listing-of-current-user','view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user'
	,'view-all-hiring-request-history','view-all-hiring-request-approval-details','view-all-hiring-request-history','view-all-hiring-request-approval-details'
	,'view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Request Info
	</h4>
	@if(isset($page))
	@if($page == 'listing')
	@canany(['create-employee-hiring-request'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-employee-hiring-request']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{ route('employee-hiring-request.create-or-edit','new') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Hiring Request
    </a>
	<!-- <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('employee-passport_request.create-or-edit','new') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Passport Request
    </a> -->
	@endif
	@endcanany
	@endif
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
        @canany(['view-all-pending-hiring-request-listing','view-pending-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-pending-hiring-request-listing','view-pending-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-hiring-requests">Pending</a>
		</li>
        @endif
        @endcanany
		@canany(['view-all-approved-hiring-request-listing','view-approved-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing','view-approved-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved(Open)</a>
		</li>
        @endif
        @endcanany
		@canany(['view-all-closed-hiring-request-listing','view-closed-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing','view-closed-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#closed-hiring-requests">Closed</a>
		</li>
        @endif
        @endcanany
		@canany(['view-all-on-hold-hiring-request-listing','view-on-hold-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-on-hold-hiring-request-listing','view-on-hold-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#on-hold-hiring-requests">On Hold</a>
		</li>
        @endif
        @endcanany
		@canany(['view-all-cancelled-hiring-request-listing','view-cancelled-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-cancelled-hiring-request-listing','view-cancelled-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#cancelled-hiring-requests">Cancelled</a>
		</li>
        @endif
        @endcanany
		@canany(['view-all-rejected-hiring-request-listing','view-rejected-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-rejected-hiring-request-listing','view-rejected-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
		</li>
        @endif
        @endcanany
		@canany(['view-all-deleted-hiring-request-listing','view-deleted-hiring-request-listing-of-current-user'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-deleted-hiring-request-listing','view-deleted-hiring-request-listing-of-current-user']);
        @endphp
        @if ($hasPermission)
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#deleted-hiring-requests">Deleted</a>
		</li>
        @endif
        @endcanany
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	@canany(['view-all-pending-hiring-request-listing','view-pending-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-pending-hiring-request-listing','view-pending-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-pane fade show active" id="pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Current Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->uuid ?? ''}}</td>
							<td>
								<!-- {{ $data->request_date ?? '' }} -->
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								<!-- @if($data->request_date) -->
                                    <!-- {{ \Carbon\Carbon::parse($data->request_date)->format('d M y') }} -->
                                <!-- @endif -->
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name ?? ''}}</td>
							<td>{{$data->replacement_for_employee_name ?? ''}}</td>
							<td>{{$data->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$data->created_by_name ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
								
									@canany(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details'])
									@php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details']);
									@endphp
									@if ($hasPermission)
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
									@endif
									@endcanany
									@canany(['edit-employee-hiring-request'])
									@php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-employee-hiring-request']);
									@endphp
									@if ($hasPermission)
                                    <li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit Hiring Request
										</a>
									</li>
									@endif
									@endcanany
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
												@if($data->is_auth_user_can_approve['can_approve'] == true)
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
                                    <li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Delete" type="button" class="btn btn-secondary btn-sm hiring-request-delete sm-mt-3" data-id="{{ $data->id }}" data-url="{{ route('employee-hiring-request.destroy', $data->id) }}">
											<i class="fa fa-trash"></i> Delete
										</button>
									</li>
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.hiring_request.approve_reject_modal')					
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
	@endcanany
	@canany(['view-all-approved-hiring-request-listing','view-approved-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-approved-hiring-request-listing','view-approved-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
    <div class="tab-pane fade show" id="approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<!-- <th>Reporting To With Position</th> -->
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<!-- <th>Current Status</th> -->
							<th>Action</th>
							
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $approvedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $approvedOne->uuid ?? ''}}</td>
							<td>{{ $approvedOne->request_date ?? '' }}</td>
							<td>{{ $approvedOne->department_name ?? '' }}</td>
							<td>{{ $approvedOne->department_location ?? '' }}</td>
							<td>{{ $approvedOne->requested_by_name ?? '' }}</td>
							<td>{{ $approvedOne->requested_job_name ?? '' }}</td>
							<!-- <td>{{ $approvedOne->reporting_to_name ?? '' }}</td>							 -->
							<td>{{ $approvedOne->experience_level_name ?? ''}}</td>
							<td>{{ $approvedOne->salary_range_start_in_aed ?? ''}} - {{$approvedOne->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $approvedOne->work_time_start ?? ''}} - {{$approvedOne->work_time_end ?? ''}}</td>
							<td>{{ $approvedOne->number_of_openings ?? ''}}</td>
							<td>{{$approvedOne->type_of_role_name}}</td>
							<td>{{$approvedOne->replacement_for_employee_name}}</td>
							<td>{{$approvedOne->explanation_of_new_hiring}}</td>
							<td>{{$approvedOne->created_by_name}}</td>
							<td>{{$approvedOne->created_at}}</td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
									@canany(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details'])
									@php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details']);
									@endphp
									@if ($hasPermission)
                                    <li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
									@endif
									@endcanany
                                    <li>
										@if(isset($approvedOne->questionnaire))
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit Questionnaire Checklist" class="btn btn-sm btn-primary" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
										<i class="fa fa-list" aria-hidden="true"></i> Edit Questionnaire
										</a>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Create Job Description" class="btn btn-sm btn-secondary" href="{{ route('employee-hiring-job-description.create-or-edit', ['id' => 'new', 'hiring_id' => $approvedOne->id]) }}">
										<i class="fa fa-address-card" aria-hidden="true"></i> Add Job Description
										</a>
										<!-- <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Create Interview Summary Report" class="btn btn-sm btn-warning" href="{{route('interview-summary-report.create-or-edit',$approvedOne->id)}}">
										<i class="fa fa-plus" aria-hidden="true"></i> Interview Summary
										</a> -->
										@else
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Create Questionnaire Checklist" class="btn btn-sm btn-info" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
										<i class="fa fa-list" aria-hidden="true"></i> Create Questionnaire
										</a>
									@endif
									</li>
									<li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#closed-hiring-request-{{$approvedOne->id}}">
											<i class="fa fa-check" aria-hidden="true"></i> Closed
										</button>
									</li>
                                    <li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="On Hold" type="button" class="btn btn-primary btn-sm"  data-bs-toggle="modal"
											data-bs-target="#on-hold-hiring-request-{{$approvedOne->id}}">
											<i class="fa fa-hand-rock" aria-hidden="true"></i> On Hold
										</button>
									</li>
                                    <li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Cancelled" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
											data-bs-target="#cancelled-hiring-request-{{$approvedOne->id}}">
											<i class="fa fa-ban" aria-hidden="true"></i> Cancelled
										</button>
									</li>
                                </ul>
                            </div>
								
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$approvedOne->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								
							</td>
							<div class="modal fade" id="cancelled-hiring-request-{{$approvedOne->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Cancelled</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$approvedOne->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger status-cancelled-button"
												data-id="{{ $approvedOne->id }}" data-status="cancelled">Submit</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="on-hold-hiring-request-{{$approvedOne->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request On Hold</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$approvedOne->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary status-onhold-button"
												data-id="{{ $approvedOne->id }}" data-status="onhold">Submit</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="closed-hiring-request-{{$approvedOne->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Closed</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															@if(isset($approvedOne->shortlistedCandidates))
																@if(count($approvedOne->shortlistedCandidates) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Selected Candidates</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<select name="candidate_id[]" id="candidate_id_{{$approvedOne->id}}" multiple="true" style="width:100%;"
																		class="candidate_id form-control widthinput" autofocus>
																			@foreach($approvedOne->shortlistedCandidates as $shortlistedCandidate)
																				<option value="{{$shortlistedCandidate->id}}" selected>{{$shortlistedCandidate->candidate_name}}</option>
																			@endforeach
																		</select>
																	</div>
																@endif
															@endif
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$approvedOne->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary status-closed-button"
												data-id="{{ $approvedOne->id }}" data-status="closed">Submit</button>
										</div>
									</div>
								</div>
							</div>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
	@endcanany
	@canany(['view-all-closed-hiring-request-listing','view-closed-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-closed-hiring-request-listing','view-closed-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-pane fade show" id="closed-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="closed-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<!-- <th>Reporting To With Position</th> -->
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<!-- <th>Current Status</th> -->
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($closed as $key => $closedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $closedOne->uuid ?? ''}}</td>
							<td>{{ $closedOne->request_date ?? '' }}</td>
							<td>{{ $closedOne->department_name ?? '' }}</td>
							<td>{{ $closedOne->department_location ?? '' }}</td>
							<td>{{ $closedOne->requested_by_name ?? '' }}</td>
							<td>{{ $closedOne->requested_job_name ?? '' }}</td>
							<!-- <td>{{ $closedOne->reporting_to_name ?? '' }}</td>							 -->
							<td>{{ $closedOne->experience_level_name ?? ''}}</td>
							<td>{{ $closedOne->salary_range_start_in_aed ?? ''}} - {{$closedOne->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $closedOne->work_time_start ?? ''}} - {{$closedOne->work_time_end ?? ''}}</td>
							<td>{{ $closedOne->number_of_openings ?? ''}}</td>
							<td>{{$closedOne->type_of_role_name}}</td>
							<td>{{$closedOne->replacement_for_employee_name}}</td>
							<td>{{$closedOne->explanation_of_new_hiring}}</td>
							<td>{{$closedOne->created_by_name}}</td>
							<td>{{$closedOne->created_at}}</td>
							<td>
								@canany(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details']);
								@endphp
								@if ($hasPermission)
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$closedOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@endcanany
							
							<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$closedOne->id)}}">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a> -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
	@endcanany
	@canany(['view-all-on-hold-hiring-request-listing','view-on-hold-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-on-hold-hiring-request-listing','view-on-hold-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-pane fade show" id="on-hold-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="on-hold-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<!-- <th>Reporting To With Position</th> -->
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<!-- <th>Current Status</th> -->
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($onHold as $key => $onHoldOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
						<td>{{$onHoldOne->uuid ?? ''}}</td>
							<td>{{ $onHoldOne->request_date ?? '' }}</td>
							<td>{{ $onHoldOne->department_name ?? '' }}</td>
							<td>{{ $onHoldOne->department_location ?? '' }}</td>
							<td>{{ $onHoldOne->requested_by_name ?? '' }}</td>
							<td>{{ $onHoldOne->requested_job_name ?? '' }}</td>
							<!-- <td>{{ $onHoldOne->reporting_to_name ?? '' }}</td>							 -->
							<td>{{ $onHoldOne->experience_level_name ?? ''}}</td>
							<td>{{ $onHoldOne->salary_range_start_in_aed ?? ''}} - {{$onHoldOne->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $onHoldOne->work_time_start ?? ''}} - {{$onHoldOne->work_time_end ?? ''}}</td>
							<td>{{ $onHoldOne->number_of_openings ?? ''}}</td>
							<td>{{$onHoldOne->type_of_role_name}}</td>
							<td>{{$onHoldOne->replacement_for_employee_name}}</td>
							<td>{{$onHoldOne->explanation_of_new_hiring}}</td>
							<td>{{$onHoldOne->created_by_name}}</td>
							<td>{{$onHoldOne->created_at}}</td>
							<td>
								@canany(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details']);
								@endphp
								@if ($hasPermission)
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$onHoldOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@endcanany
							
							<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$onHoldOne->id)}}">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a> -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
	@endcanany
	@canany(['view-all-cancelled-hiring-request-listing','view-cancelled-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-cancelled-hiring-request-listing','view-cancelled-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-pane fade show" id="cancelled-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="cancelled-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<!-- <th>Reporting To With Position</th> -->
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<!-- <th>Current Status</th> -->
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($cancelled as $key => $cancelledOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
						<td>{{ $cancelledOne->uuid ?? ''}}</td>
							<td>{{ $cancelledOne->request_date ?? '' }}</td>
							<td>{{ $cancelledOne->department_name ?? '' }}</td>
							<td>{{ $cancelledOne->department_location ?? '' }}</td>
							<td>{{ $cancelledOne->requested_by_name ?? '' }}</td>
							<td>{{ $cancelledOne->requested_job_name ?? '' }}</td>
							<!-- <td>{{ $cancelledOne->reporting_to_name ?? '' }}</td>							 -->
							<td>{{ $cancelledOne->experience_level_name ?? ''}}</td>
							<td>{{ $cancelledOne->salary_range_start_in_aed ?? ''}} - {{$cancelledOne->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $cancelledOne->work_time_start ?? ''}} - {{$cancelledOne->work_time_end ?? ''}}</td>
							<td>{{ $cancelledOne->number_of_openings ?? ''}}</td>
							<td>{{$cancelledOne->type_of_role_name}}</td>
							<td>{{$cancelledOne->replacement_for_employee_name}}</td>
							<td>{{$cancelledOne->explanation_of_new_hiring}}</td>
							<td>{{$cancelledOne->created_by_name}}</td>
							<td>{{$cancelledOne->created_at}}</td>
							<td>
								@canany(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details']);
								@endphp
								@if ($hasPermission)
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$cancelledOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@endcanany
							
							<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$cancelledOne->id)}}">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a> -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
	@endcanany
	@canany(['view-all-rejected-hiring-request-listing','view-rejected-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-rejected-hiring-request-listing','view-rejected-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-pane fade show" id="rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<!-- <th>Reporting To With Position</th> -->
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<!-- <th>Current Status</th> -->
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $rejectedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
						<td>{{ $rejectedOne->uuid ?? ''}}</td>
							<td>{{ $rejectedOne->request_date ?? '' }}</td>
							<td>{{ $rejectedOne->department_name ?? '' }}</td>
							<td>{{ $rejectedOne->department_location ?? '' }}</td>
							<td>{{ $rejectedOne->requested_by_name ?? '' }}</td>
							<td>{{ $rejectedOne->requested_job_name ?? '' }}</td>
							<!-- <td>{{ $rejectedOne->reporting_to_name ?? '' }}</td>							 -->
							<td>{{ $rejectedOne->experience_level_name ?? ''}}</td>
							<td>{{ $rejectedOne->salary_range_start_in_aed ?? ''}} - {{$rejectedOne->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $rejectedOne->work_time_start ?? ''}} - {{$rejectedOne->work_time_end ?? ''}}</td>
							<td>{{ $rejectedOne->number_of_openings ?? ''}}</td>
							<td>{{$rejectedOne->type_of_role_name}}</td>
							<td>{{$rejectedOne->replacement_for_employee_name}}</td>
							<td>{{$rejectedOne->explanation_of_new_hiring}}</td>
							<td>{{$rejectedOne->created_by_name}}</td>
							<td>{{$rejectedOne->created_at}}</td>
							<td>
								@canany(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details'])
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-history-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-approval-details-of-current-user','view-hiring-request-details-of-current-user','view-all-hiring-request-history','view-all-hiring-request-approval-details']);
								@endphp
								@if ($hasPermission)
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@endif
								@endcanany
							
							<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$rejectedOne->id)}}">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a> -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
	@endcanany
	@canany(['view-all-deleted-hiring-request-listing','view-deleted-hiring-request-listing-of-current-user'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-deleted-hiring-request-listing','view-deleted-hiring-request-listing-of-current-user']);
	@endphp
	@if ($hasPermission)
	<div class="tab-pane fade show" id="deleted-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="deleted-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<!-- <th>Reporting To With Position</th> -->
							<th>Experience Level</th>
							<th>Salary Range(AED)</th>
							<th>Work Time</th>
							<th>Number Of Openings</th>
							<th>Type Of Role</th>
							<th>Replacement For Employee</th>
							<th>Detailed Explanation Of New Hiring</th>
							<th>Created By</th>
							<th>Created At</th>
							<!-- <th>Current Status</th> -->
							<!-- <th>Action</th> -->
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($deleted as $key => $deletedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
						<td>{{ $deletedOne->uuid ?? ''}}</td>
							<td>{{ $deletedOne->request_date ?? '' }}</td>
							<td>{{ $deletedOne->department_name ?? '' }}</td>
							<td>{{ $deletedOne->department_location ?? '' }}</td>
							<td>{{ $deletedOne->requested_by_name ?? '' }}</td>
							<td>{{ $deletedOne->requested_job_name ?? '' }}</td>
							<!-- <td>{{ $deletedOne->reporting_to_name ?? '' }}</td>							 -->
							<td>{{ $deletedOne->experience_level_name ?? ''}}</td>
							<td>{{ $deletedOne->salary_range_start_in_aed ?? ''}} - {{$deletedOne->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $deletedOne->work_time_start ?? ''}} - {{$deletedOne->work_time_end ?? ''}}</td>
							<td>{{ $deletedOne->number_of_openings ?? ''}}</td>
							<td>{{$deletedOne->type_of_role_name}}</td>
							<td>{{$deletedOne->replacement_for_employee_name}}</td>
							<td>{{$deletedOne->explanation_of_new_hiring}}</td>
							<td>{{$deletedOne->created_by_name}}</td>
							<td>{{$deletedOne->created_at}}</td>
							<td>
							<!-- <a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$deletedOne->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a> -->
							<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$deletedOne->id)}}">
								<i class="fa fa-edit" aria-hidden="true"></i>
							</a> -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@endif
    @endcanany
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
		$('.status-closed-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        updateFinalStatusHiringrequest(id, status)
	    })
		$('.status-onhold-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        updateFinalStatusHiringrequest(id, status)
	    })
		$('.status-cancelled-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        updateFinalStatusHiringrequest(id, status)
	    })
		function updateFinalStatusHiringrequest(id, status) {
			var comment = $("#comment-"+id).val();
	        let url = '{{ route('employee-hiring-request.final-status') }}';
	        if(status == 'closed') {
	            var message = 'Closed';
				var selectedCandidates = $("#candidate_id_"+id).val();
	        }
			else if(status == 'onhold'){
	            var message = 'On Hold';
				var selectedCandidates = [];
	        }
			else if(status =='cancelled'){
				var message = 'Cancelled';
				var selectedCandidates = [];
			}
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee hiring request ?',function (e) {
	            if (e) {
	                $.ajax({
	                    type: "POST",
	                    url: url,
	                    dataType: "json",
	                    data: {
	                        id: id,
	                        status: status,
	                        comment: comment,
							selectedCandidates: selectedCandidates,
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success: function (data) {
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {

							}
	                    }
	                });
	            }
	
	        }).set({title:"Confirmation"})
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