@extends('layouts.table')
@section('content')
@if(Auth::user()->job_description_approval['can'] == true)
@if(count($deptHeadPendings) > 0 || count($deptHeadApproved) > 0 || count($deptHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Job Description Approvals By Team Lead / Reporting Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#teamlead-pending-hiring-requests">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#teamlead-approved-hiring-requests">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#teamlead-rejected-hiring-requests">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="teamlead-pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-pending-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>HR Manager Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($deptHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
									{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif
							</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#job-description-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#job-description-rejection-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#job-description-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#job-description-rejection-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							<div class="modal fade" id="job-description-approvals-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Job Description Approval</h1>
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
							<div class="modal fade" id="job-description-rejection-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Job Description Approval</h1>
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
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="teamlead-approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-approved-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($deptHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
									{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>@if($data->department_head_action_at != '')
									{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="teamlead-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-rejected-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($deptHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
									{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>@if($data->department_head_action_at != '')
									{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
									</ul>
								</div>
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
@if(count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Hiring Job Description Approvals By HR Manager
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
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="hr-pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="hr-pending-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
									{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>
							@if($data->department_head_action_at != '')
									{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif
							</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#job-description-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#job-description-rejection-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#job-description-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#job-description-rejection-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							<div class="modal fade" id="job-description-approvals-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Job Description Approval</h1>
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
							<div class="modal fade" id="job-description-rejection-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Hiring Job Description Approval</h1>
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
				<table id="hr-approved-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>HR Manager Action</th>
							<th>HR Manager Action At</th>
							<th>HR Manager Comment</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
									{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>							
							<td>
							@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif
							</td>

							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>@if($data->department_head_action_at != '')
									{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y, H:i:s')}}
								@endif
								</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
									</ul>
								</div>
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
				<table id="hr-rejected-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Request Date</th>
							<th>Job Title</th>
							<th>Department Location</th>
							<th>Job Purpose</th>
							<th>Duties and Responsibilities (Generic) of the position</th>
							<th>Skills required at fulfill the position</th>
							<th>Position Qualifications (Academic & Professional)</th>
							<th>Created By</th>
							<th>Created At</th>
							<th>Team Lead/ Manager Name</th>
							<th>Team Lead/ Manager Action</th>
							<th>Team Lead/ Manager Action At</th>
							<th>Team Lead/ Manager Comment</th>
							<th>HR Manager Name</th>
							<th>HR Manager Action</th>
							<th>HR Manager Action At</th>
							<th>HR Manager Comment</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>@if($data->request_date != '')
									{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>

							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>@if($data->department_head_action_at != '')
									{{\Carbon\Carbon::parse($data->department_head_action_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
									</ul>
								</div>
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
	        let url = '{{ route('employee-hiring-job-description.request-action') }}';
	        if(status == 'rejected') {
	            var message = 'Reject';
	        }else{
	            var message = 'Approve';
	        }
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee hiring job description ?',function (e) {
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
</script>
@endpush