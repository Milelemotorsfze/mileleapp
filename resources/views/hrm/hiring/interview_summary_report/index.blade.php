@extends('layouts.table')
<style>
	  .texttransform {
    text-transform: capitalize;
  }
  .light {
	background-color:#e6e6e6!important;
	font-weight: 700!important;
  }
  .dark {
	background-color:#d9d9d9!important;
	font-weight: 700!important;
  }
  .paragraph-class 
    {
        color: red;
        font-size:11px;
    }
	</style>
@section('content')
<!-- @canany(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-addon-new-selling-price','approve-addon-new-selling-price','reject-addon-new-selling-price']);
@endphp
@if ($hasPermission) -->
<div class="card-header">
	<h4 class="card-title">
		Interview Summary Report Info
	</h4>
	<!-- <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> -->
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('interview-summary-report.create-or-edit','new')}}">
      <i class="fa fa-plus" aria-hidden="true"></i> New Interview Summary Report
    </a>
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
			<a class="nav-link active" data-bs-toggle="pill" href="#shortlisted-for-interview">Shortlisted For Interview</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#telephonic_interview">Telephonic Interview</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#first_round">First Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#second_round">Second Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#third_round">Third Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#forth_round">Forth Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#fifth_round">Fifth Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#not_selected_candidates">Not Selected Candidates</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#selected_candidates">Selected Candidates</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#pending-hiring-requests">Pending</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
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
	
	<div class="tab-pane fade show active" id="shortlisted-for-interview">
		<div class="card-body">
			<div class="table-responsive">
				<table id="shortlisted-table" class="table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark"><center>Hiring Request</center></th>
							<th colspan="3" class="light"><center>Candidate</center></th>
							<th colspan="2" class="dark"><center>Rate Appearance</center></th>
							<th rowspan="2" class="light">Created By</th>
                            <th rowspan="2" class="dark">Created At</th>
							<th rowspan="2" class="light">Action</th>
						</tr>
						<tr>
							<td class="dark">UUID</td>
							<td class="dark">Job Position</td>
							<td class="light">Name</td>
							<td class="light">Nationality</td>
							<td class="light">Gender</td>
							<td class="dark">Dress</td>
							<td class="dark">Body Language</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($shortlists as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>

                            <td>{{ $data->createdBy->name ?? ''}}</td>
                            <td>{{ $data->created_at ?? ''}}</td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id ?? '')}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
									<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-primary" href="">
											<i class="fa fa-user" aria-hidden="true"></i> Candidate Details
										</a>
									</li>
                                    <li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
										</a>
									</li>
                                    <li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Telephonic Interview
										</button>
									</li>
                                </ul>
                            </div>
							<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Telephonic Interview Summary</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row">
															<div class="col-xxl-6 col-lg-6 col-md-6">
																<label for="date" class="form-label font-size-13">{{ __('Telephonic Interview Date') }}</label>
															</div>
															<div class="col-xxl-6 col-lg-6 col-md-6">
																<!-- <input type="text" name="round" id="round-{{$data->id}}" value="telephonic" hidden> -->
																<input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2" required>
																<span id="date_error_{{$data->id}}" class="required-class paragraph-class"></span>

															</div>
														</div>
														<div class="row">
															@if(isset($interviewersNames))
																@if(count($interviewersNames) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Choose Telephonic Interviewers Names</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<select name="interviewer_id[]" id="interviewer_id_{{$data->id}}" multiple="true" style="width:100%;"
																		class="interviewer_id form-control widthinput" autofocus required>
																			@foreach($interviewersNames as $interviewer)
																				<option value="{{$interviewer->id}}">{{$interviewer->name}}</option>
																			@endforeach
																		</select>
																		<span id="interviewer_id_error_{{$data->id}}" class="required-class paragraph-class"></span>
																	</div>
																@endif
															@endif
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="interview_summary" required>
																</textarea>
																<span id="comment-error-{{$data->id}}" class="required-class paragraph-class"></span>

															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary add-interview-summary"
												data-id="{{ $data->id }}" data-status="telephonic">Submit</button>
										</div>
									</div>
								</div>
							</div>
							</td>			
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="telephonic_interview">
		<div class="card-body">
			<div class="table-responsive">
				<table id="telephonic-interview-round-table" class="table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark"><center>Hiring Request</center></th>
							<th colspan="3" class="light"><center>Candidate</center></th>
							<th colspan="2" class="dark"><center>Rate Appearance</center></th>
							<th colspan="3" class="light"><center>Telephonic Round</center></th>
							<th rowspan="2" class="dark">Created By</th>
                            <th rowspan="2" class="light">Created At</th>
							<th rowspan="2" class="dark">Action</th>
						</tr>
						<tr>
							<td class="dark">UUID</td>
							<td class="dark">Job Position</td>
							<td class="light">Name</td>
							<td class="light">Nationality</td>
							<td class="light">Gender</td>
							<td class="dark">Dress</td>
							<td class="dark">Body Language</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($telephonics as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>							
							<td>{{ $data->date_of_telephonic_interview ?? ''}}</td>
							<td>
								@if(isset($data->telephonicInterviewers))
  									@if(count($data->telephonicInterviewers) > 0)
  										@foreach($data->telephonicInterviewers as $telephonicInterviewers)
											{{ $telephonicInterviewers->interviewerName->name ?? '' }},
										@endforeach
									@endif
								@endif
							</td>
							<td>{{ $data->telephonic_interview ?? ''}}</td>
                            <td>{{ $data->createdBy->name ?? ''}}</td>
                            <td>{{ $data->created_at ?? ''}}</td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id ?? '')}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
									<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-primary" href="">
											<i class="fa fa-user" aria-hidden="true"></i> Candidate Details
										</a>
									</li>
                                    <li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
										</a>
									</li>
                                    <li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> First Round
										</button>
									</li>
                                </ul>
                            </div>
							<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">First Round Interview Summary</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row">
															<div class="col-xxl-6 col-lg-6 col-md-6">
																<label for="date" class="form-label font-size-13">{{ __('First Round Interview Date') }}</label>
															</div>
															<div class="col-xxl-6 col-lg-6 col-md-6">
																<!-- <input type="text" name="round" id="round-{{$data->id}}" value="telephonic" hidden> -->
																<input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
															</div>
														</div>
														<div class="row">
															@if(isset($interviewersNames))
																@if(count($interviewersNames) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Choose First Round Interviewers Names</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<select name="interviewer_id[]" id="interviewer_id_{{$data->id}}" multiple="true" style="width:100%;"
																		class="interviewer_id form-control widthinput" autofocus>
																			@foreach($interviewersNames as $interviewer)
																				<option value="{{$interviewer->id}}">{{$interviewer->name}}</option>
																			@endforeach
																		</select>
																	</div>
																@endif
															@endif
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="interview_summary">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary add-interview-summary"
												data-id="{{ $data->id }}" data-status="first">Submit</button>
										</div>
									</div>
								</div>
							</div>
							</td>					
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="first_round">
		<div class="card-body">
			<div class="table-responsive">
				<table id="first-round-table" class="table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark"><center>Hiring Request</center></th>
							<th colspan="3" class="light"><center>Candidate</center></th>
							<th colspan="2" class="dark"><center>Rate Appearance</center></th>
							<th colspan="3" class="light"><center>Telephonic Round</center></th>
							<th colspan="3" class="dark"><center>First Round</center></th>
							<th rowspan="2" class="light">Created By</th>
                            <th rowspan="2" class="dark">Created At</th>
							<th rowspan="2" class="light">Action</th>
						</tr>
						<tr>
							<td class="dark">UUID</td>
							<td class="dark">Job Position</td>
							<td class="light">Name</td>
							<td class="light">Nationality</td>
							<td class="light">Gender</td>
							<td class="dark">Dress</td>
							<td class="dark">Body Language</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
						</tr>
						<!-- <tr>
							<th>Sl No</th>
							<th>Hiring Request UUID</th>
							<th>Job Position</th>
							<th>Candidate Name</th>
							<th>Nationality</th>
							<th>Gender</th>
							<th>Rate Dress Appearance</th>
							<th>Rate Body Language Appearance</th>

							<th>Date Of Telephonic Interview</th>
							<th>Name Of Telephonic Interviewers</th>
							<th>Telephonic Interview Summary</th>
							<th>Date Of First Round</th>
							<th>Name Of First Round Interviewers</th>
							<th>First Round</th>
                            <th>Created By</th>
                            <th>Created At</th>
							<th>Action</th>
						</tr> -->
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($firsts as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>{{ $data->date_of_telephonic_interview ?? ''}}</td>
							<td>
								@if(isset($data->telephonicInterviewers))
  									@if(count($data->telephonicInterviewers) > 0)
  										@foreach($data->telephonicInterviewers as $telephonicInterviewers)
											{{ $telephonicInterviewers->interviewerName->name ?? '' }},
										@endforeach
									@endif
								@endif
							</td>
							<td>{{ $data->telephonic_interview ?? ''}}</td>
							<td>{{ $data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
  									@if(count($data->firstRoundInterviewers) > 0)
  										@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
											{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
										@endforeach
									@endif
								@endif
							</td>
							<td>{{ $data->first_round ?? ''}}</td>
                            <td>{{ $data->createdBy->name ?? ''}}</td>
                            <td>{{ $data->created_at ?? ''}}</td>
							<td>
							<div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('employee-hiring-request.show',$data->employeeHiringRequest->id ?? '')}}">
											<i class="fa fa-eye" aria-hidden="true"></i> View Details
										</a>
									</li>
									<li><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-primary" href="">
											<i class="fa fa-user" aria-hidden="true"></i> Candidate Details
										</a>
									</li>
                                    <li>
										<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
										</a>
									</li>
                                    <li>
										<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
											data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Second Round
										</button>
									</li>
                                </ul>
                            </div>
							<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Second Round Interview Summary</h1>
											<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
										</div>
										<div class="modal-body p-3">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-12">
														<div class="row">
															<div class="col-xxl-6 col-lg-6 col-md-6">
																<label for="date" class="form-label font-size-13">{{ __('Second Round Interview Date') }}</label>
															</div>
															<div class="col-xxl-6 col-lg-6 col-md-6">
																<!-- <input type="text" name="round" id="round-{{$data->id}}" value="telephonic" hidden> -->
																<input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
															</div>
														</div>
														<div class="row">
															@if(isset($interviewersNames))
																@if(count($interviewersNames) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Choose Second Round Interviewers Names</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<select name="interviewer_id[]" id="interviewer_id_{{$data->id}}" multiple="true" style="width:100%;"
																		class="interviewer_id form-control widthinput" autofocus>
																			@foreach($interviewersNames as $interviewer)
																				<option value="{{$interviewer->id}}">{{$interviewer->name}}</option>
																			@endforeach
																		</select>
																	</div>
																@endif
															@endif
															<div class="col-lg-12 col-md-12 col-sm-12">
																<label class="form-label font-size-13">Comments</label>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12">
																<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="interview_summary">
																</textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary add-interview-summary"
												data-id="{{ $data->id }}" data-status="second">Submit</button>
										</div>
									</div>
								</div>
							</div>
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
<!-- @endif
@endcanany -->
@endsection
@push('scripts')
<script type="text/javascript">
	var interviewersNames = {!! json_encode($interviewersNames) !!};
	$(document).ready(function () {
		var countinterviewersNames = 0;
		countinterviewersNames = interviewersNames.length;
		if(countinterviewersNames > 0 ) {
			for(var i=0; i<countinterviewersNames; i++) {
				$('#interviewer_id_'+interviewersNames[i].id).select2({
					allowClear: true,
					placeholder:"Choose Telephonic Interviewers Names",
					dropdownParent: $('#shortlisted-candidate-'+interviewersNames[i].id)
				});
			}
		}
		$('.add-interview-summary').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        addInterviewSummary(id, status)
	    })
		// $('.status-onhold-button').click(function (e) {
	    //     var id = $(this).attr('data-id');
	    //     var status = $(this).attr('data-status');
	    //     addInterviewSummary(id, status)
	    // })
		// $('.status-cancelled-button').click(function (e) {
	    //     var id = $(this).attr('data-id');
	    //     var status = $(this).attr('data-status');
	    //     addInterviewSummary(id, status)
	    // })
		function addInterviewSummary(id, status) {
			var comment = $("#comment-"+id).val();
			var date = $("#date-"+id).val();
			var interviewers_id = $("#interviewer_id_"+id).val();
	        let url = '{{ route('interview-summary-report.round-summary') }}';
	        // if(status == 'closed') {
	        //     var message = 'Closed';
			// 	var selectedCandidates = $("#interviewer_id_"+id).val();
	        // }
			// else if(status == 'onhold'){
	        //     var message = 'On Hold';
			// 	var selectedCandidates = [];
	        // }
			// else if(status =='cancelled'){
			// 	var message = 'Cancelled';
			// 	var selectedCandidates = [];
			// }
	        // var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee hiring request ?',function (e) {
	        //     if (e) {
	                $.ajax({
	                    type: "POST",
	                    url: url,
	                    dataType: "json",
	                    data: {
	                        id: id,
	                        round: status,
	                        date: date,
							comment: comment,
							interviewers_id: interviewers_id,
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success: function (data) {
							if(data == 'success') { 
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data['error'] == true) { 
								$msg = data['msg'];
								for(var i=0; i<$msg.length; i++) {
									if($msg[i] == 'The date field is required.') {
										showDateError($msg[i], id)
									}
									if($msg[i] == 'The interviewers id field is required.') {
										showInterviewrsError($msg[i], id)
									}
									if($msg[i] == 'The comment field is required.') {
										showCommentError($msg[i], id)
									}
								}
							}
	                    }
	                });
	        //     }
	
	        // }).set({title:"Confirmation"})
	    }
	})
	function showDateError($msg, id) {
		document.getElementById("date_error_"+id).textContent=$msg;
	    document.getElementById("date-"+id).classList.add("is-invalid");
	    document.getElementById("date_error_"+id).classList.add("paragraph-class");
		document.getElementById("date_error_"+id).style.color = "red";
	}
	function showInterviewrsError($msg, id) {
		document.getElementById("interviewer_id_error_"+id).textContent=$msg;
	    document.getElementById("interviewer_id_"+id).classList.add("is-invalid");
	    document.getElementById("interviewer_id_error_"+id).classList.add("paragraph-class");
		document.getElementById("interviewer_id_error_"+id).style.color = "red";
	}
	function showCommentError($msg, id) {
		document.getElementById("comment-error-"+id).textContent=$msg;
	    document.getElementById("comment-"+id).classList.add("is-invalid");
	    document.getElementById("comment-error-"+id).classList.add("paragraph-class");
		document.getElementById("comment-error-"+id).style.color = "red";
	}
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
	// $('.hiring-request-delete').on('click',function(){
    //     let id = $(this).attr('data-id');
    //     let url =  $(this).attr('data-url');
    //     var confirm = alertify.confirm('Are you sure you want to Delete this Employee Hiring Request ?',function (e) {
    //         if (e) {
    //             $.ajax({
    //                 type: "POST",
    //                 url: url,
    //                 dataType: "json",
    //                 data: {
    //                     _method: 'DELETE',
    //                     id: 'id',
    //                     _token: '{{ csrf_token() }}'
    //                 },
    //                 success:function (data) {
    //                     location.reload();
    //                     alertify.success('Employee Hiring Request Deleted successfully.');
    //                 }
    //             });
    //         }
    //     }).set({title:"Delete Employee Hiring Request"})
    // });
</script>

<!-- <script type="text/javascript">
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
	        let url = '{{ route('interview-summary-report.request-action') }}';
	        if(status == 'rejected') {
	            var message = 'Reject';
	        }else{
	            var message = 'Approve';
	        }
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this interview summary report ?',function (e) {
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
    });
</script> -->
@endpush