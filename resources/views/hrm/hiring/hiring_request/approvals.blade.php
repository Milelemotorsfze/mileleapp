@extends('layouts.table')
@section('content')
@if(Auth::user()->hiring_request_approval['can'] == true)
<!-- Approvals =>  Team Lead/Manager ------- Recruitement(Hiring) manager ----------- Division head --------- HR manager-->
@if(count($deptHeadPendings) > 0 || count($deptHeadApproved) > 0 || count($deptHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Request Approvals By Team Lead / Reporting Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#team-lead-pending-hiring-requests">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#team-lead-approved-hiring-requests">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#team-lead-rejected-hiring-requests">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="hiring-request-approvals-histories" >
	<div class="tab-pane fade show active" id="team-lead-pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
						@foreach ($deptHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name ?? ''}}</td>
							<td>{{$data->replacement_for_employee_name ?? ''}}</td>
							<td>{{$data->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$data->created_by_name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>												
								@if(isset($type))
								@if($type == 'approve')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							<div class="modal fade" id="approve-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $data->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="reject-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="reject-comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $data->id }}"
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
	<div class="tab-pane fade show" id="team-lead-approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($deptHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="team-lead-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($deptHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
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
</br>
@if(count($hiringManagerPendings) > 0 || count($hiringManagerApproved) > 0 || count($hiringManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Request Approvals By Recruiting Manager
	</h4>
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
			<a class="nav-link active" data-bs-toggle="pill" href="#hiring-manager-pending-hiring-requests">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hiring-manager-approved-hiring-requests">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hiring-manager-rejected-hiring-requests">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="hiring-request-approvals-histories" >
	<div class="tab-pane fade show active" id="hiring-manager-pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
						@foreach ($hiringManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name ?? ''}}</td>
							<td>{{$data->replacement_for_employee_name ?? ''}}</td>
							<td>{{$data->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$data->created_by_name ?? ''}}</td>
							<td>
								@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
								@if(isset($type))
								@if($type == 'approve')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							<div class="modal fade" id="approve-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $data->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="reject-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="reject-comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $data->id }}"
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
	<div class="tab-pane fade show" id="hiring-manager-approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($hiringManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hiring-manager-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($hiringManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
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
</br>	
@if(count($divisionHeadPendings) > 0 || count($divisionHeadApproved) > 0 || count($divisionHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Request Approvals By Division Head
	</h4>
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
			<a class="nav-link active" data-bs-toggle="pill" href="#division-head-pending-hiring-requests">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#division-head-approved-hiring-requests">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#division-head-rejected-hiring-requests">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="hiring-request-approvals-histories" >
	<div class="tab-pane fade show active" id="division-head-pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
						@foreach ($divisionHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name ?? ''}}</td>
							<td>{{$data->replacement_for_employee_name ?? ''}}</td>
							<td>{{$data->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$data->created_by_name ?? ''}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
								@if(isset($type))
								@if($type == 'approve')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							<div class="modal fade" id="approve-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $data->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="reject-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="reject-comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $data->id }}"
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
	<div class="tab-pane fade show" id="division-head-approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="division-head-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
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
</br>			
@if(count($HRManagerPendings) > 0 || count($HRManagerApproved) > 0 || count($HRManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Request Approvals By HR Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#hr-pending-hiring-requests">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-approved-hiring-requests">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-rejected-hiring-requests">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="hiring-request-approvals-histories" >
	<div class="tab-pane fade show active" id="hr-pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
						@foreach ($HRManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name ?? ''}}</td>
							<td>{{$data->replacement_for_employee_name ?? ''}}</td>
							<td>{{$data->explanation_of_new_hiring ?? ''}}</td>
							<td>{{$data->created_by_name ?? ''}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
								@if(isset($type))
								@if($type == 'approve')
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-hiring-request-approvals-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
								</button>
								@endif
								@endif
								@endif
							</td>
							<div class="modal fade" id="approve-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Approval By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-success status-approve-button"
												data-id="{{ $data->id }}" data-status="approved">Approve</button>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="reject-hiring-request-approvals-{{$data->id}}"
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
																@if(isset($data->is_auth_user_can_approve['current_approve_position']))
																{{$data->is_auth_user_can_approve['current_approve_position']}}
																@endif
															</div>
														</div>
														<div class="row mt-2">
															<div class="col-lg-6 col-md-6 col-sm-6">
																<label class="form-label font-size-13">Rejection By Name</label>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6">
																@if(isset($data->is_auth_user_can_approve['current_approve_person']))
																{{$data->is_auth_user_can_approve['current_approve_person']}}
																@endif
															</div>
														</div>
														@if(isset($data->is_auth_user_can_approve['current_approve_position']))
														<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
														@endif
														<div class="row mt-2">
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="reject-comment-{{$data->id}}" class="form-control" name="comment"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $data->id }}"
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
	<div class="tab-pane fade show" id="hr-approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hr-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>UUID</th>
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{$data->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
								@endif
							</td>
							<td>{{ $data->department_name ?? '' }}</td>
							<td>{{ $data->department_location ?? '' }}</td>
							<td>{{ $data->requested_by_name ?? '' }}</td>
							<td>{{ $data->requested_job_name ?? '' }}</td>
							<td>{{ $data->divisionHead->name ?? '' }}</td>
							<td>{{ $data->experience_level_name ?? ''}}</td>
							<td>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</td>
							<td>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</td>
							<td>{{ $data->number_of_openings ?? ''}}</td>
							<td>{{$data->type_of_role_name}}</td>
							<td>{{$data->replacement_for_employee_name}}</td>
							<td>{{$data->explanation_of_new_hiring}}</td>
							<td>{{$data->created_by_name}}</td>
							<td>@if($data->created_at != '')
								{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}
								@endif
							</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i> View Details
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
@endif
@endsection
@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		var comment = '';
	    $('.status-reject-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			comment = $("#reject-comment-"+id).val();
	        approveOrRejectHiringrequest(id, status,comment)
	    })
	    $('.status-approve-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			comment = $("#comment-"+id).val();
	        approveOrRejectHiringrequest(id, status,comment)
	    })
	    function approveOrRejectHiringrequest(id, status,comment) {
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
								window.location.reload();
								alertify.error("Can't Update the status, because it is already updated")
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