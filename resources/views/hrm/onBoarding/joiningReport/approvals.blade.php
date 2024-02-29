@extends('layouts.table')
	@section('content')
		@if(Auth::user()->joining_report_approval['can'] == true)
			@if(count($preparedByPendings) > 0 || count($preparedByApproved) > 0 || count($preparedByRejected) > 0)
				<div class="card-header">
					<h4 class="card-title">
						Employee Joining Report Approvals By Prepared By
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
							<a class="nav-link active" data-bs-toggle="pill" href="#prepared-by-pending-joining-report">Pending </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#prepared-by-approved-joining-report">Approved </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#prepared-by-rejected-joining-report">Rejected </a>
						</li>
					</ul>
				</div>
				<div class="tab-content" id="selling-price-histories" >
					<div class="tab-pane fade show active" id="prepared-by-pending-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($preparedByPendings as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
												{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif		
												
											</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
                                            <td>{{ $data->reportingManager->name ?? '' }}</td>
                                            <td>{{ $data->remarks ?? '' }}</td>
                                            <td>{{ $data->preparedBy->name ?? '' }}</td>
											<td>
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@elseif($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee' && $data->employee_id != NULL)	
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
															</button>
														@endif
													@endif
												@endif
											</td>
											@include('hrm.onBoarding.joiningReport.approve_reject_modal')
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade show" id="prepared-by-approved-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">								
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
										@foreach ($preparedByApproved as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>
											@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif	
												</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
					<div class="tab-pane fade show" id="prepared-by-rejected-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($preparedByRejected as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>
											@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
			</br>
			@if(count($employeePendings) > 0 || count($employeeApproved) > 0 || count($employeeRejected) > 0)
				<div class="card-header">
					<h4 class="card-title">
					Employee Joining Report Approvals By Employee
					</h4>
				</div>
				<div class="portfolio">
					<ul class="nav nav-pills nav-fill" id="my-tab">
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="pill" href="#team-lead-pending-joining-report">Pending </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#team-lead-approved-joining-report">Approved </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#team-lead-rejected-joining-report">Rejected </a>
						</li>
					</ul>
				</div>
				<div class="tab-content" id="selling-price-histories" >
					
					<div class="tab-pane fade show active" id="team-lead-pending-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($employeePendings as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
                                            <td>{{ $data->reportingManager->name ?? '' }}</td>
                                            <td>{{ $data->remarks ?? '' }}</td>
                                            <td>{{ $data->preparedBy->name ?? '' }}</td>
											<td>
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>												
												@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@elseif($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee' && $data->employee_id != NULL)	
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
															</button>
														@endif
													@endif
												@endif
											</td>
											@include('hrm.onBoarding.joiningReport.approve_reject_modal')
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade show" id="team-lead-approved-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($employeeApproved as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
					<div class="tab-pane fade show" id="team-lead-rejected-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($employeeRejected as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
			</br>
			@if(count($HRManagerPendings) > 0 || count($HRManagerApproved) > 0 || count($HRManagerRejected) > 0)
				<div class="card-header">
					<h4 class="card-title">
					Employee Joining Report Approvals By HR Manager
					</h4>
				</div>
				<div class="portfolio">
					<ul class="nav nav-pills nav-fill" id="my-tab">
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="pill" href="#hr-pending-joining-report">Pending </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#hr-approved-joining-report">Approved </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#hr-rejected-joining-report">Rejected </a>
						</li>
					</ul>
				</div>
				<div class="tab-content" id="selling-price-histories" >
					<div class="tab-pane fade show active" id="hr-pending-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($HRManagerPendings as $key => $data)
										<tr data-id="1">
											<td>{{ ++$i }}</td>
											<td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
                                            <td>{{ $data->reportingManager->name ?? '' }}</td>
                                            <td>{{ $data->remarks ?? '' }}</td>
                                            <td>{{ $data->preparedBy->name ?? '' }}</td>
											<td>
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@elseif($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee' && $data->employee_id != NULL)	
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
															</button>
														@endif
													@endif
												@endif
											</td>
											@include('hrm.onBoarding.joiningReport.approve_reject_modal')
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade show" id="hr-approved-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($HRManagerApproved as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
					<div class="tab-pane fade show" id="hr-rejected-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($HRManagerRejected as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
											<td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
            </br>
			@if(count($ReportingManagerPendings) > 0 || count($ReportingManagerApproved) > 0 || count($ReportingManagerRejected) > 0)
				<div class="card-header">
					<h4 class="card-title">
					Employee Joining Report Approvals By Reporting Manager
					</h4>
				</div>
				<div class="portfolio">
					<ul class="nav nav-pills nav-fill" id="my-tab">
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="pill" href="#reporting-manager-pending-joining-report">Pending </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#reporting-manager-approved-joining-report">Approved </a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-bs-toggle="pill" href="#reporting-manager-rejected-joining-report">Rejected </a>
						</li>
					</ul>
				</div>
				<div class="tab-content" id="selling-price-histories" >
					<div class="tab-pane fade show active" id="reporting-manager-pending-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($ReportingManagerPendings as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
                                            <td>{{ $data->reportingManager->name ?? '' }}</td>
                                            <td>{{ $data->remarks ?? '' }}</td>
                                            <td>{{ $data->preparedBy->name ?? '' }}</td>
											<td>
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('joining_report.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@elseif($data->is_auth_user_can_approve['can_approve'] == true && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee' && $data->employee_id != NULL)	
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-joining-report-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
															</button>
														@endif
													@endif
												@endif
											</td>
											@include('hrm.onBoarding.joiningReport.approve_reject_modal')
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade show" id="reporting-manager-approved-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($ReportingManagerApproved as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
					<div class="tab-pane fade show" id="reporting-manager-rejected-joining-report">
						<div class="card-body">
							<div class="table-responsive">
								<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
										@foreach ($ReportingManagerRejected as $key => $data)
										<tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->first_name ?? ''}} {{$data->candidate->last_name ?? ''}}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->name ?? ''}}
												@endif
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->employee_code ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->employee_code ?? ''}}
												@endif												
											</td>
                                            <td>
												@if($data->joining_type == 'new_employee')
													{{ $data->candidate->designation->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
													{{ $data->user->empProfile->designation->name ?? '' }}
												@endif													
											</td>
                                            <td>@if($data->joining_type == 'new_employee')
											{{ $data->candidate->department->name ?? '' }}
												@elseif($data->joining_type == 'internal_transfer' OR $data->joining_type == 'vacations_or_leave')
												{{ $data->user->empProfile->department->name ?? '' }}
												@endif</td>
                                            <td>{{ $data->joining_type_name ?? ''}}</td>
                                            <td>{{ $data->joining_date ?? '' }}</td>
                                            <td>{{ $data->joiningLocation->name ?? '' }}</td>
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
		@endif
@endsection