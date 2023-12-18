@extends('layouts.table')
@section('content')
@if(Auth::user()->job_description_approval == true)
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
							<td>{{ $data->request_date ?? '' }}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
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
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
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
							<td>{{ $data->request_date ?? '' }}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>{{$data->department_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
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
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
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
							<td>{{ $data->request_date ?? '' }}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>{{$data->department_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
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
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
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
							<td>{{ $data->request_date ?? '' }}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>{{$data->department_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
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
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
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
							<td>{{ $data->request_date ?? '' }}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>{{$data->department_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
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
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
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
							<td>{{ $data->request_date ?? '' }}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->location->name ?? '' }}</td>
							<td>{{ $data->job_purpose ?? ''}}</td>
							<td>{{ $data->duties_and_responsibilities ?? ''}}</td>
							<td>{{ $data->skills_required ?? ''}}</td>
							<td>{{ $data->position_qualification ?? ''}}</td>
							<td>{{$data->createdBy->name ?? ''}}</td>
							<td>{{$data->departmentHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_department_head ?? ''}}</td>
							<td>{{$data->department_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_department_head ?? ''}}</td>
							<td>{{$data->hrManagerName->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->created_at ?? ''}}</td>
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
                                </ul>
                            </div>
							</td>
							@include('hrm.hiring.job_description.approve_reject_modal')					
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