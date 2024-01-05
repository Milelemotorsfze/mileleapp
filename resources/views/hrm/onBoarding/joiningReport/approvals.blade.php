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
				<div class="tab-content" id="selling-price-histories" >
					<div class="tab-pane fade show active" id="hiring-manager-pending-hiring-requests">
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												
												@if(isset($type))
													@if($type == 'approve')
														<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
															data-bs-target="#approve-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-up" aria-hidden="true"></i>
														</button>
														<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
															data-bs-target="#reject-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-down" aria-hidden="true"></i>
														</button>
													@endif
												@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true)
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@endif
													@endif
												@endif
											</td>
											<div class="modal fade" id="edit-selling-price-{{$data->id}}"  tabindex="-1"
												aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $data->id) }}"
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
																					<input name="selling_price" id="update_selling_price_{{$data->id}}"
																						oninput="inputNumberAbs(this)" class="form-control" required
																						placeholder="Enter Selling Price" value="{{$data->selling_price}}">
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
											

											<div class="modal fade" id="approve-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Approval</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
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
																data-id="{{ $data->id }}" data-status="approved">Approve</button>
														</div>
													</div>
												</div>
											</div>


											<div class="modal fade" id="reject-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Rejection</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																				</textarea>
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
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
					<div class="tab-pane fade show" id="hiring-manager-rejected-hiring-requests">
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
											<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
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
					Employee Joining Report Approvals By Team Lead / Reporting Manager
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
				<div class="tab-content" id="selling-price-histories" >
					
					<div class="tab-pane fade show active" id="team-lead-pending-hiring-requests">
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												
												@if(isset($type))
													@if($type == 'approve')
														<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
															data-bs-target="#approve-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-up" aria-hidden="true"></i>
														</button>
														<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
															data-bs-target="#reject-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-down" aria-hidden="true"></i>
														</button>
													@endif
												@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true)
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@endif
													@endif
												@endif
											</td>
											<div class="modal fade" id="edit-selling-price-{{$data->id}}"  tabindex="-1"
												aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $data->id) }}"
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
																					<input name="selling_price" id="update_selling_price_{{$data->id}}"
																						oninput="inputNumberAbs(this)" class="form-control" required
																						placeholder="Enter Selling Price" value="{{$data->selling_price}}">
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
											

											<div class="modal fade" id="approve-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Approval</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
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
																data-id="{{ $data->id }}" data-status="approved">Approve</button>
														</div>
													</div>
												</div>
											</div>


											<div class="modal fade" id="reject-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Rejection</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																				</textarea>
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
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
					<div class="tab-pane fade show" id="team-lead-rejected-hiring-requests">
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
											<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
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
											<td>{{ $data->request_date ?? '' }}</td>
											<td>{{ $data->department_name ?? '' }}</td>
											<td>{{ $data->department_location ?? '' }}</td>
											<td>{{ $data->requested_by_name ?? '' }}</td>
											<td>{{ $data->requested_job_name ?? '' }}</td>
											<td>{{ $data->reporting_to_name ?? '' }}</td>							
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												@if(isset($type))
													@if($type == 'approve')
														<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
															data-bs-target="#approve-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-up" aria-hidden="true"></i>
														</button>
														<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
															data-bs-target="#reject-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-down" aria-hidden="true"></i>
														</button>
													@endif
												@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true)
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@endif
													@endif
												@endif
											</td>
											<div class="modal fade" id="edit-selling-price-{{$data->id}}"  tabindex="-1"
												aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $data->id) }}"
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
																					<input name="selling_price" id="update_selling_price_{{$data->id}}"
																						oninput="inputNumberAbs(this)" class="form-control" required
																						placeholder="Enter Selling Price" value="{{$data->selling_price}}">
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
											

											<div class="modal fade" id="approve-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Approval</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
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
																data-id="{{ $data->id }}" data-status="approved">Approve</button>
														</div>
													</div>
												</div>
											</div>


											<div class="modal fade" id="reject-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Rejection</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																				</textarea>
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
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
					<div class="tab-pane fade show" id="hr-rejected-hiring-requests">
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
											<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
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
					Employee Joining Report Approvals By HR Manager
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->id)}}">
													<i class="fa fa-eye" aria-hidden="true"></i>
												</a>
												@if(isset($type))
													@if($type == 'approve')
														<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
															data-bs-target="#approve-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-up" aria-hidden="true"></i>
														</button>
														<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
															data-bs-target="#reject-selling-price-{{$data->id}}">
															<i class="fa fa-thumbs-down" aria-hidden="true"></i>
														</button>
													@endif
												@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
													@if(isset($data->is_auth_user_can_approve['can_approve']))
														@if($data->is_auth_user_can_approve['can_approve'] == true)
															<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
																data-bs-target="#approve-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-up" aria-hidden="true"></i>
															</button>
															<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
																data-bs-target="#reject-selling-price-{{$data->id}}">
																<i class="fa fa-thumbs-down" aria-hidden="true"></i>
															</button>
														@endif
													@endif
												@endif
											</td>
											<div class="modal fade" id="edit-selling-price-{{$data->id}}"  tabindex="-1"
												aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<form id="form-update" action="{{ route('addon.UpdateSellingPrice', $data->id) }}"
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
																					<input name="selling_price" id="update_selling_price_{{$data->id}}"
																						oninput="inputNumberAbs(this)" class="form-control" required
																						placeholder="Enter Selling Price" value="{{$data->selling_price}}">
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
											

											<div class="modal fade" id="approve-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Approval</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
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
																data-id="{{ $data->id }}" data-status="approved">Approve</button>
														</div>
													</div>
												</div>
											</div>


											<div class="modal fade" id="reject-selling-price-{{$data->id}}"
												tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog ">
													<div class="modal-content">
														<div class="modal-header">
															<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Joining Report Rejection</h1>
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
																				<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment">
																				</textarea>
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
												<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$approvedOne->id)}}">
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
					<div class="tab-pane fade show" id="hr-rejected-hiring-requests">
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
											<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$rejectedOne->id)}}">
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
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee Joining Report ?',function (e) {
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