<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
                                        @php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['insert-employee-company-email']);
										@endphp
										@if ($hasPermission && $data->user_id == '')
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Give System Access" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#give-system-access-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> System Access
											</button>
										</li>
										@endif
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-employee-details']);
										@endphp
										@if ($hasPermission)
										<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" 
                                        href="{{route('employee.show',$data->id)}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
											</a>
										</li>
										@endif
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-all-employee-details']);
										@endphp
										@if ($hasPermission)
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" 
                                            href="{{route('employee.edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
									</ul>
								</div>
								<div class="modal fade" id="give-system-access-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('user.createAccessRequest')}}" id="form_{{$data->id}}">
												@csrf
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Send Milele Matrix Sign Up Request To Admin</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body p-3">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-12">
																<div class="row">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Name of employee : {{$data->first_name.' '.$data->last_name}}</label>
																	</div>
																	<input hidden id="name" type="text" class="form-control widthinput @error('name') is-invalid @enderror" name="name"
																		placeholder="Enter Name Of Employee" value="{{$data->first_name.' '.$data->last_name}}" autocomplete="name" autofocus>
																	<input hidden id="id" name="id" value="{{$data->id ?? ''}}">
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Company Email Of Employee</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12 select-button-main-div">
																	<input id="email" type="text" class="form-control widthinput @error('email') is-invalid @enderror" name="email"
																		placeholder="Enter Company Email Of Employee" value="" autocomplete="email" autofocus>
																	</div>																
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary give-system-access-class"
														data-id="{{ $data->id }}" 
														="first">Submit</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</td>