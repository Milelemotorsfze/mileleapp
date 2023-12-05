@extends('layouts.table')
@section('content')
<!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
@endphp
@if ($hasPermission) -->

<!-- <div class="card-header">
	<h4 class="card-title">
		Approvals By Recruiting Manager
	</h4>
</div> -->
@if(count($hiringManagerPendings) > 0 || count($hiringManagerApproved) > 0 || count($hiringManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Request Approvals By Recruiting Manager
	</h4>
	<!-- <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> -->
	@if(isset($page))
	@if($page == 'listing')
	<!-- <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('employee-hiring-request.create-or-edit','new') }}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Hiring Request
    </a> -->
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
        <!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
        @endphp
        @if ($hasPermission) -->
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-selling-prices">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-selling-prices">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-selling-prices">Rejected </a>
		</li>
        <!-- @endif
        @endcanany -->
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
    <!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
    @endphp
    @if ($hasPermission) -->
	<div class="tab-pane fade show active" id="pending-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($hiringManagerPendings as $key => $pending)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $pending->request_date ?? '' }}</td>
							<td>{{ $pending->department_name ?? '' }}</td>
							<td>{{ $pending->department_location ?? '' }}</td>
							<td>{{ $pending->requested_by_name ?? '' }}</td>
							<td>{{ $pending->requested_job_name ?? '' }}</td>
							<td>{{ $pending->reporting_to_name ?? '' }}</td>							
							<td>{{ $pending->experience_level_name ?? ''}}</td>
							<td>{{ $pending->salary_range_start_in_aed ?? ''}} - {{$pending->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $pending->work_time_start ?? ''}} - {{$pending->work_time_end ?? ''}}</td>
							<td>{{ $pending->number_of_openings ?? ''}}</td>
							<td>{{$pending->type_of_role_name ?? ''}}</td>
							<td>{{$pending->replacement_for_employee_name ?? ''}}</td>
							<td>{{$pending->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$pending->created_by_name ?? ''}}</td>
							<td>{{$pending->created_at ?? ''}}</td>
							<td><label class="badge badge-soft-info">{{ $pending->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$pending->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create-or-edit',$pending->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								@if(isset($type))
									@if($type == 'approve')
										<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#approve-selling-price-{{$pending->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>
										</button>
										<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
											data-bs-target="#reject-selling-price-{{$pending->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i>
										</button>
									@endif
								@elseif(isset($pending->is_auth_user_can_approve) && $pending->is_auth_user_can_approve != '')
									@if(isset($pending->is_auth_user_can_approve['can_approve']))
										@if($pending->is_auth_user_can_approve['can_approve'] == true)
											<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-selling-price-{{$pending->id}}">
												<i class="fa fa-thumbs-up" aria-hidden="true"></i>
											</button>
											<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-selling-price-{{$pending->id}}">
												<i class="fa fa-thumbs-down" aria-hidden="true"></i>
											</button>
										@endif
									@endif
								@endif
							</td>
							<div class="modal fade" id="edit-selling-price-{{$pending->id}}"  tabindex="-1"
								aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $pending->id) }}"
										method="POST" >
										@csrf
										<div class="modal-content">
											<div class="modal-header">
												<h1 class="modal-title fs-5" id="exampleModalLabel">Update Selling Price</h1>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body p-3">
												<div class="col-lg-12">
													<div class="row">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13 text-muted">Selling Price</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<div class="input-group">
																	<input name="selling_price" id="update_selling_price_{{$pending->id}}"
																		oninput="inputNumberAbs(this)" class="form-control" required
																		placeholder="Enter Selling Price" value="{{$pending->selling_price}}">
																	<div class="input-group-append">
																		<span class="input-group-text widthinput" id="basic-addon2">AED</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary ">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							

							<div class="modal fade" id="approve-selling-price-{{$pending->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Approval</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Position</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
																	{{$pending->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_person']))
																	{{$pending->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
															<input hidden id="current_approve_position_{{$pending->id}}" name="current_approve_position" value="{{$pending->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$pending->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $pending->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>


							<div class="modal fade" id="reject-selling-price-{{$pending->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Rejection</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Position</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
																	{{$pending->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_person']))
																	{{$pending->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
															<input hidden id="current_approve_position_{{$pending->id}}" name="current_approve_position" value="{{$pending->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$pending->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $pending->id }}"
												data-status="rejected">Reject</button>
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
    <div class="tab-pane fade show" id="approved-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($hiringManagerApproved as $key => $approvedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $approvedOne->request_date ?? '' }}</td>
							<td>{{ $approvedOne->department_name ?? '' }}</td>
							<td>{{ $approvedOne->department_location ?? '' }}</td>
							<td>{{ $approvedOne->requested_by_name ?? '' }}</td>
							<td>{{ $approvedOne->requested_job_name ?? '' }}</td>
							<td>{{ $approvedOne->reporting_to_name ?? '' }}</td>							
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
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$approvedOne->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								<!-- @if(isset($approvedOne->questionnaire))
								<a title="Create Questionnaire Checklist" class="btn btn-sm btn-info" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-list" aria-hidden="true"></i>
								</a>
								@else
								<a title="Edit Questionnaire Checklist" class="btn btn-sm btn-primary" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-list" aria-hidden="true"></i>
								</a>
								<a title="Create Job Description" class="btn btn-sm btn-secondary" href="{{route('employee-hiring-job-description.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-address-card" aria-hidden="true"></i>
								</a>
								@endif -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="rejected-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($hiringManagerRejected as $key => $rejectedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $rejectedOne->request_date ?? '' }}</td>
							<td>{{ $rejectedOne->department_name ?? '' }}</td>
							<td>{{ $rejectedOne->department_location ?? '' }}</td>
							<td>{{ $rejectedOne->requested_by_name ?? '' }}</td>
							<td>{{ $rejectedOne->requested_job_name ?? '' }}</td>
							<td>{{ $rejectedOne->reporting_to_name ?? '' }}</td>							
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
							<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
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
    <!-- @endif
    @endcanany -->
</div>
@endif
</br>
@if(count($deptHeadPendings) > 0 || count($deptHeadApproved) > 0 || count($deptHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
    Employee Hiring Request Approvals By Team Lead / Reporting Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
        <!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
        @endphp
        @if ($hasPermission) -->
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-selling-prices">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-selling-prices">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-selling-prices">Rejected </a>
		</li>
        <!-- @endif
        @endcanany -->
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
    <!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
    @endphp
    @if ($hasPermission) -->
	<div class="tab-pane fade show active" id="pending-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($deptHeadPendings as $key => $pending)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $pending->request_date ?? '' }}</td>
							<td>{{ $pending->department_name ?? '' }}</td>
							<td>{{ $pending->department_location ?? '' }}</td>
							<td>{{ $pending->requested_by_name ?? '' }}</td>
							<td>{{ $pending->requested_job_name ?? '' }}</td>
							<td>{{ $pending->reporting_to_name ?? '' }}</td>							
							<td>{{ $pending->experience_level_name ?? ''}}</td>
							<td>{{ $pending->salary_range_start_in_aed ?? ''}} - {{$pending->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $pending->work_time_start ?? ''}} - {{$pending->work_time_end ?? ''}}</td>
							<td>{{ $pending->number_of_openings ?? ''}}</td>
							<td>{{$pending->type_of_role_name ?? ''}}</td>
							<td>{{$pending->replacement_for_employee_name ?? ''}}</td>
							<td>{{$pending->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$pending->created_by_name ?? ''}}</td>
							<td>{{$pending->created_at ?? ''}}</td>
							<td><label class="badge badge-soft-info">{{ $pending->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$pending->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create-or-edit',$pending->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								@if(isset($type))
									@if($type == 'approve')
										<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#approve-selling-price-{{$pending->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>
										</button>
										<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
											data-bs-target="#reject-selling-price-{{$pending->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i>
										</button>
									@endif
								@elseif(isset($pending->is_auth_user_can_approve) && $pending->is_auth_user_can_approve != '')
									@if(isset($pending->is_auth_user_can_approve['can_approve']))
										@if($pending->is_auth_user_can_approve['can_approve'] == true)
											<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-selling-price-{{$pending->id}}">
												<i class="fa fa-thumbs-up" aria-hidden="true"></i>
											</button>
											<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-selling-price-{{$pending->id}}">
												<i class="fa fa-thumbs-down" aria-hidden="true"></i>
											</button>
										@endif
									@endif
								@endif
							</td>
							<div class="modal fade" id="edit-selling-price-{{$pending->id}}"  tabindex="-1"
								aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $pending->id) }}"
										method="POST" >
										@csrf
										<div class="modal-content">
											<div class="modal-header">
												<h1 class="modal-title fs-5" id="exampleModalLabel">Update Selling Price</h1>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body p-3">
												<div class="col-lg-12">
													<div class="row">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13 text-muted">Selling Price</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<div class="input-group">
																	<input name="selling_price" id="update_selling_price_{{$pending->id}}"
																		oninput="inputNumberAbs(this)" class="form-control" required
																		placeholder="Enter Selling Price" value="{{$pending->selling_price}}">
																	<div class="input-group-append">
																		<span class="input-group-text widthinput" id="basic-addon2">AED</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary ">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							

							<div class="modal fade" id="approve-selling-price-{{$pending->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Approval</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Position</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
																	{{$pending->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_person']))
																	{{$pending->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
															<input hidden id="current_approve_position_{{$pending->id}}" name="current_approve_position" value="{{$pending->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$pending->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $pending->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>


							<div class="modal fade" id="reject-selling-price-{{$pending->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Rejection</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Position</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
																	{{$pending->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_person']))
																	{{$pending->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
															<input hidden id="current_approve_position_{{$pending->id}}" name="current_approve_position" value="{{$pending->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$pending->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $pending->id }}"
												data-status="rejected">Reject</button>
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
    <div class="tab-pane fade show" id="approved-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($deptHeadApproved as $key => $approvedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $approvedOne->request_date ?? '' }}</td>
							<td>{{ $approvedOne->department_name ?? '' }}</td>
							<td>{{ $approvedOne->department_location ?? '' }}</td>
							<td>{{ $approvedOne->requested_by_name ?? '' }}</td>
							<td>{{ $approvedOne->requested_job_name ?? '' }}</td>
							<td>{{ $approvedOne->reporting_to_name ?? '' }}</td>							
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
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$approvedOne->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								<!-- @if(isset($approvedOne->questionnaire))
								<a title="Create Questionnaire Checklist" class="btn btn-sm btn-info" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-list" aria-hidden="true"></i>
								</a>
								@else
								<a title="Edit Questionnaire Checklist" class="btn btn-sm btn-primary" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-list" aria-hidden="true"></i>
								</a>
								<a title="Create Job Description" class="btn btn-sm btn-secondary" href="{{route('employee-hiring-job-description.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-address-card" aria-hidden="true"></i>
								</a>
								@endif -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="rejected-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($deptHeadRejected as $key => $rejectedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $rejectedOne->request_date ?? '' }}</td>
							<td>{{ $rejectedOne->department_name ?? '' }}</td>
							<td>{{ $rejectedOne->department_location ?? '' }}</td>
							<td>{{ $rejectedOne->requested_by_name ?? '' }}</td>
							<td>{{ $rejectedOne->requested_job_name ?? '' }}</td>
							<td>{{ $rejectedOne->reporting_to_name ?? '' }}</td>							
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
							<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
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
    <!-- @endif
    @endcanany -->
</div>
@endif
</br>
@if(count($HRManagerPendings) > 0 || count($HRManagerApproved) > 0 || count($HRManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
    Employee Hiring Request Approvals By HR Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
        <!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
        @endphp
        @if ($hasPermission) -->
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#pending-selling-prices">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-selling-prices">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-selling-prices">Rejected </a>
		</li>
        <!-- @endif
        @endcanany -->
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
    <!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
    @endphp
    @if ($hasPermission) -->
	<div class="tab-pane fade show active" id="pending-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($HRManagerPendings as $key => $pending)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $pending->request_date ?? '' }}</td>
							<td>{{ $pending->department_name ?? '' }}</td>
							<td>{{ $pending->department_location ?? '' }}</td>
							<td>{{ $pending->requested_by_name ?? '' }}</td>
							<td>{{ $pending->requested_job_name ?? '' }}</td>
							<td>{{ $pending->reporting_to_name ?? '' }}</td>							
							<td>{{ $pending->experience_level_name ?? ''}}</td>
							<td>{{ $pending->salary_range_start_in_aed ?? ''}} - {{$pending->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $pending->work_time_start ?? ''}} - {{$pending->work_time_end ?? ''}}</td>
							<td>{{ $pending->number_of_openings ?? ''}}</td>
							<td>{{$pending->type_of_role_name ?? ''}}</td>
							<td>{{$pending->replacement_for_employee_name ?? ''}}</td>
							<td>{{$pending->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$pending->created_by_name ?? ''}}</td>
							<td>{{$pending->created_at ?? ''}}</td>
							<td><label class="badge badge-soft-info">{{ $pending->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$pending->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create-or-edit',$pending->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								@if(isset($type))
									@if($type == 'approve')
										<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#approve-selling-price-{{$pending->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>
										</button>
										<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
											data-bs-target="#reject-selling-price-{{$pending->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i>
										</button>
									@endif
								@elseif(isset($pending->is_auth_user_can_approve) && $pending->is_auth_user_can_approve != '')
									@if(isset($pending->is_auth_user_can_approve['can_approve']))
										@if($pending->is_auth_user_can_approve['can_approve'] == true)
											<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-selling-price-{{$pending->id}}">
												<i class="fa fa-thumbs-up" aria-hidden="true"></i>
											</button>
											<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-selling-price-{{$pending->id}}">
												<i class="fa fa-thumbs-down" aria-hidden="true"></i>
											</button>
										@endif
									@endif
								@endif
							</td>
							<div class="modal fade" id="edit-selling-price-{{$pending->id}}"  tabindex="-1"
								aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $pending->id) }}"
										method="POST" >
										@csrf
										<div class="modal-content">
											<div class="modal-header">
												<h1 class="modal-title fs-5" id="exampleModalLabel">Update Selling Price</h1>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body p-3">
												<div class="col-lg-12">
													<div class="row">
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13 text-muted">Selling Price</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<div class="input-group">
																	<input name="selling_price" id="update_selling_price_{{$pending->id}}"
																		oninput="inputNumberAbs(this)" class="form-control" required
																		placeholder="Enter Selling Price" value="{{$pending->selling_price}}">
																	<div class="input-group-append">
																		<span class="input-group-text widthinput" id="basic-addon2">AED</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
												<button type="submit" class="btn btn-primary ">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							

							<div class="modal fade" id="approve-selling-price-{{$pending->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Approval</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Position</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
																	{{$pending->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_person']))
																	{{$pending->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
															<input hidden id="current_approve_position_{{$pending->id}}" name="current_approve_position" value="{{$pending->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$pending->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $pending->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>


							<div class="modal fade" id="reject-selling-price-{{$pending->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Request Rejection</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Position</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
																	{{$pending->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($pending->is_auth_user_can_approve['current_approve_person']))
																	{{$pending->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($pending->is_auth_user_can_approve['current_approve_position']))
															<input hidden id="current_approve_position_{{$pending->id}}" name="current_approve_position" value="{{$pending->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$pending->id}}" class="form-control" name="comment">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $pending->id }}"
												data-status="rejected">Reject</button>
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
    <div class="tab-pane fade show" id="approved-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($HRManagerApproved as $key => $approvedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $approvedOne->request_date ?? '' }}</td>
							<td>{{ $approvedOne->department_name ?? '' }}</td>
							<td>{{ $approvedOne->department_location ?? '' }}</td>
							<td>{{ $approvedOne->requested_by_name ?? '' }}</td>
							<td>{{ $approvedOne->requested_job_name ?? '' }}</td>
							<td>{{ $approvedOne->reporting_to_name ?? '' }}</td>							
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
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
									<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								<!-- <a title="Edit Hiring Request" class="btn btn-sm btn-info" href="{{route('employee-hiring-request.create',$approvedOne->id)}}">
									<i class="fa fa-edit" aria-hidden="true"></i>
								</a> -->
								<!-- @if(isset($approvedOne->questionnaire))
								<a title="Create Questionnaire Checklist" class="btn btn-sm btn-info" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-list" aria-hidden="true"></i>
								</a>
								@else
								<a title="Edit Questionnaire Checklist" class="btn btn-sm btn-primary" href="{{route('employee-hiring-questionnaire.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-list" aria-hidden="true"></i>
								</a>
								<a title="Create Job Description" class="btn btn-sm btn-secondary" href="{{route('employee-hiring-job-description.create-or-edit',$approvedOne->id)}}">
								<i class="fa fa-address-card" aria-hidden="true"></i>
								</a>
								@endif -->
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="rejected-selling-prices">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Request Date</th>
							<th>Department Name</th>
							<th>Department Location</th>
							<th>Requested By</th>
							<th>Requested Job Title</th>
							<th>Reporting To With Position</th>
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
						@foreach ($HRManagerRejected as $key => $rejectedOne)
						<tr data-id="1">
						<td>{{ ++$i }}</td>
							<td>{{ $rejectedOne->request_date ?? '' }}</td>
							<td>{{ $rejectedOne->department_name ?? '' }}</td>
							<td>{{ $rejectedOne->department_location ?? '' }}</td>
							<td>{{ $rejectedOne->requested_by_name ?? '' }}</td>
							<td>{{ $rejectedOne->requested_job_name ?? '' }}</td>
							<td>{{ $rejectedOne->reporting_to_name ?? '' }}</td>							
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
							<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
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
    <!-- @endif
    @endcanany -->
</div>
@endif
<!-- @endif
@endcanany -->
@endsection
@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
	    $('.status-reject-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
	    $('.status-approve-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
	    function approveOrRejectHiringrequest(id, status) {
			var comment = $("#comment-"+id).val();
			var current_approve_position = $("#current_approve_position_"+id).val();
	        let url = '{{ route('employee-hiring-request.request-action') }}';
	        if(status == 'rejected') {
	            var message = 'Reject';
	        }else{
	            var message = 'Approve';
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
							current_approve_position: current_approve_position,
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
</script>
@endpush