@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
	.form-label {
		margin-top: 0.5rem;
	}
	.iti {
		width: 100%;
	}
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
	.paragraph-class {
		color: red;
		font-size:11px;
	}
	.other-error {
		color: red;
	}
	.table-edits input, .table-edits select {
		height:38px!important;
	}
</style>
@section('content')
<div class="card-header">
	@canany(['view-interview-summary-report-listing'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing']);
	@endphp
	@if ($hasPermission)
	<h4 class="card-title">
		Interview Summary Report Info
	</h4>
	@endif
	@endcanany
	@canany(['create-interview-summary-report'])
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('interview-summary-report.create-or-edit','new')}}">
	    <i class="fa fa-plus" aria-hidden="true"></i> New Interview Summary Report
	</a>
	@endif
	@endcanany
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
@canany(['view-interview-summary-report-listing'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-listing']);
@endphp
@if ($hasPermission)
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#shortlisted-for-interview">Shortlisted Resumes</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#telephonic_interview">Telephonic Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#first_round">1st Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#second_round">2nd Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#third_round">3rd Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#forth_round">4th Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#fifth_round">5th Round</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#not_selected_candidates">Not Selected</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#pending-hiring-requests">Selected & Approval Awaiting </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#approved-hiring-requests">Approved</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#personalinfo_docs">Documents</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#selected_for_job">Selected For Job</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#rejected-hiring-requests">Rejected</a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="shortlisted-for-interview">
		<div class="card-body">
			<div class="table-responsive">
				<table id="shortlisted-table" class="table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
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
										@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany
										@canany(['create-interview-summary-report'])
										@php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Telephonic Interview
											</button>
										</li>
										@endif
										@endcanany
										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.round-summary')}}" id="form_{{$data->id}}">
												@csrf
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
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																		<input type="text" name="round" value="telephonic" hidden>
																		<input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
																		<span id="date_error_{{$data->id}}" class="required-class paragraph-class"></span>
																	</div>
																</div>
																<div class="row">
																	@if(isset($interviewersNames))
																	@if(count($interviewersNames) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Choose Telephonic Interviewers Names</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12 select-button-main-div">
																		<div class="dropdown-option-div">
																			<select name="interviewer_id[]" id="interviewer_id_{{$data->id}}" multiple="true" style="width:100%;"
																				class="interviewer_id form-control widthinput" autofocus>
																				@foreach($interviewersNames as $interviewer)
																				<option value="{{$interviewer->id}}">{{$interviewer->name}}</option>
																				@endforeach
																			</select>
																			<span id="interviewer_id_error_{{$data->id}}" class="required-class paragraph-class"></span>
																		</div>
																	</div>
																	@endif
																	@endif
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Comments</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
																			name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
																			autofocus></textarea>
																		<span id="comment-error-{{$data->id}}" class="required-class paragraph-class"></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary add-interview-summary"
														data-id="{{ $data->id }}" data-status="telephonic">Submit</button>
												</div>
											</form>
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
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
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
										@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										@if($data->employeeHiringRequest->final_status != 'closed')

										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> First Round
											</button>
										</li>
										@endif
										@endcanany

										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.round-summary')}}" id="form_{{$data->id}}">
												@csrf
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
																	<div class="col-xxl-6 col-lg-6 col-md-6"><input type="text" name="id" value="{{$data->id}}" hidden>
																		<input type="text" name="round" value="first" hidden>
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
																			class="interviewer_id form-control widthinput">
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
																		<textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
																			name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
																			autofocus></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary add-interview-summary"
														data-id="{{ $data->id }}" data-status="first">Submit</button>
												</div>
											</form>
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
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
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
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Second Round
											</button>
										</li>
										@endif
										@endcanany
										
										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.round-summary')}}" id="form_{{$data->id}}">
												@csrf
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
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																		<input type="text" name="round" value="second" hidden>
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
																	<div class="col-lg-12 col-md-12 col-sm-12"><textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
																		name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
																		autofocus></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary add-interview-summary"
														data-id="{{ $data->id }}" data-status="second">Submit</button>
												</div>
                                            </form>
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
    <div class="tab-pane fade show" id="second_round">
        <div class="card-body">
            <div class="table-responsive">
                <table id="second-round-table" class="table table-striped table-editable table-edits table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="light">Sl No</th>
                            <th colspan="2" class="dark">
                                <center>Hiring Request</center>
                            </th>
                            <th colspan="3" class="light">
                                <center>Candidate</center>
                            </th>
                            <th colspan="2" class="dark">
                                <center>Rate Appearance</center>
                            </th>
                            <th colspan="3" class="light">
                                <center>Telephonic Round</center>
                            </th>
                            <th colspan="3" class="dark">
                                <center>First Round</center>
                            </th>
                            <th colspan="3" class="light">
                                <center>Second Round</center>
                            </th>
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
                            <td class="dark">Date</td>
                            <td class="dark">Name Of Interviewer</td>
                            <td class="dark">Summary</td>
                            <td class="light">Date</td>
                            <td class="light">Name Of Interviewer</td>
                            <td class="light">Summary</td>
                        </tr>
                    </thead>
                    <tbody>
                        <div hidden>{{$i=0;}}</div>
                        @foreach ($seconds as $key => $data)
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
                            <td>{{$data->date_of_first_round ?? ''}}</td>
                            <td>
                                @if(isset($data->firstRoundInterviewers))
                                @if(count($data->firstRoundInterviewers) > 0)
                                @foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
                                {{ $firstRoundInterviewer->interviewerName->name ?? '' }},
                                @endforeach
                                @endif
                                @endif
                            </td>
                            <td>{{$data->first_round ?? ''}}</td>
                            <td>{{$data->date_of_second_round ?? ''}}</td>
                            <td>
                                @if(isset($data->secondRoundInterviewers))
                                @if(count($data->secondRoundInterviewers) > 0)
                                @foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
                                {{ $secondRoundInterviewer->interviewerName->name ?? '' }},
                                @endforeach
                                @endif
                                @endif
                            </td>
                            <td>{{$data->second_round ?? ''}}</td>
                            <td>{{ $data->createdBy->name ?? ''}}</td>
                            <td>{{ $data->created_at ?? ''}}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
                                            <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                            </a>
                                        </li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
                                            <button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
                                                data-bs-target="#shortlisted-candidate-{{$data->id}}">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Third Round
                                            </button>
                                        </li>
										@endif
										@endcanany
										
										@endif
                                    </ul>
                                </div>
                                <div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog ">
                                        <div class="modal-content">
                                            <form method="POST" action="{{route('interview-summary-report.round-summary')}}" id="form_{{$data->id}}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Third Round Interview Summary</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row">
                                                                    <div class="col-xxl-6 col-lg-6 col-md-6">
                                                                        <label for="date" class="form-label font-size-13">{{ __('Third Round Interview Date') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-6 col-lg-6 col-md-6">
                                                                        <input type="text" name="id" value="{{$data->id}}" hidden>
                                                                        <input type="text" name="round" value="third" hidden>
                                                                        <input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    @if(isset($interviewersNames))
                                                                    @if(count($interviewersNames) > 0)
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">Choose Third Round Interviewers Names</label>
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
                                                                        <textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
                                                                            name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
                                                                            autofocus></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary add-interview-summary"
                                                        data-id="{{ $data->id }}" data-status="third">Submit</button>
                                                </div>
                                            </form>
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
	<div class="tab-pane fade show" id="third_round">
		<div class="card-body">
			<div class="table-responsive">
				<table id="third-round-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($thirds as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Forth Round
											</button>
										</li>
										@endif
										@endcanany
										
										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.round-summary')}}" id="form_{{$data->id}}">
												@csrf
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Forth Round Interview Summary</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body p-3">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-12">
																<div class="row">
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<label for="date" class="form-label font-size-13">{{ __('Forth Round Interview Date') }}</label>
																	</div>
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																		<input type="text" name="round" value="forth" hidden>
																		<input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
																	</div>
																</div>
																<div class="row">
																	@if(isset($interviewersNames))
																	@if(count($interviewersNames) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Choose Forth Round Interviewers Names</label>
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
																		<textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
																			name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
																			autofocus></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary add-interview-summary"
														data-id="{{ $data->id }}" data-status="forth">Submit</button>
												</div>
											</form>
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
	<div class="tab-pane fade show" id="forth_round">
		<div class="card-body">
			<div class="table-responsive">
				<table id="forth-round-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
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
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($forths as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Fifth Round
											</button>
										</li>
										@endif
										@endcanany
										
										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.round-summary')}}" id="form_{{$data->id}}">
												@csrf
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Fifth Round Interview Summary</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body p-3">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-12">
																<div class="row">
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<label for="date" class="form-label font-size-13">{{ __('Fifth Round Interview Date') }}</label>
																	</div>
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																		<input type="text" name="round" value="fifth" hidden>
																		<input type="date" name="date" id="date-{{$data->id}}" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
																	</div>
																</div>
																<div class="row">
																	@if(isset($interviewersNames))
																	@if(count($interviewersNames) > 0)
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Choose Fifth Round Interviewers Names</label>
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
																		<textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
																			name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
																			autofocus></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary add-interview-summary"
														data-id="{{ $data->id }}" data-status="fifth">Submit</button>
												</div>
											</form>
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
	<div class="tab-pane fade show" id="fifth_round">
		<div class="card-body">
			<div class="table-responsive">
				<table id="fifth-round-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($fifths as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Closed" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
											<i class="fa fa-plus" aria-hidden="true"></i> Final Evaluation
											</button>
										</li>
										@endif
										@endcanany
										
										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.final-evaluation')}}" id="final_{{$data->id}}">
												@csrf
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Final Evaluation Of Candidate</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body p-3">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-12">
																<div class="row">
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																		<input type="text" name="round" value="final" hidden>
																	</div>
																</div>
																<div class="row">
																	<div class="col-xxl-12 col-lg-12 col-md-12">
																		<label for="date" class="form-label font-size-13">{{ __('Candidate Selected') }}</label>
																	</div>
																	<div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
																		<fieldset style="margin-top:5px;" class="radio-div-container">
																			<div class="row some-class">
																				<div class="col-xxl-6 col-lg-6 col-md-6">
																					<input type="radio" class="candidate_selected" name="candidate_selected" value="yes" id="yes" />
																					<label for="yes">Yes</label>
																				</div>
																				<div class="col-xxl-6 col-lg-6 col-md-6">
																					<input type="radio" class="candidate_selected" name="candidate_selected" value="no" id="no" />
																					<label for="no">No</label>
																				</div>
																			</div>
																		</fieldset>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<label class="form-label font-size-13">Final Evaluation of Candidate</label>
																	</div>
																	<div class="col-lg-12 col-md-12 col-sm-12">
																		<textarea rows="5" id="comment-{{$data->id}}" type="text" class="form-control @error('comment') is-invalid @enderror"
																			name="comment" placeholder="" value="{{ old('comment') }}"  autocomplete="comment"
																			autofocus></textarea>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary final-interview-summary"
														data-id="{{ $data->id }}" data-status="final">Submit</button>
												</div>
											</form>
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
	<div class="tab-pane fade show" id="not_selected_candidates">
		<div class="card-body">
			<div class="table-responsive">
				<table id="not-selected-candidates-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
							<th rowspan="2" class="light">Final Evaluation Of Candidate</th>
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
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($notSelected as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{$data->final_evaluation_of_candidate ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany
										
										@endif
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
	<div class="tab-pane fade show" id="pending-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="selected-candidates-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
							<th rowspan="2" class="light">Final Evaluation Of Candidate</th>
							<th colspan="4" class="dark">
								<center>HR Manager Approvals</center>
							</th>
							<th rowspan="2" class="light">
								<center>Division Head Name</center>
							</th>
							<th rowspan="2" class="dark">Created By</th>
							<th rowspan="2" class="light">Created At</th>
							<th rowspan="2" class="dark">Current Status</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="dark">Name</td>
							<td class="dark">Action</td>
							<td class="dark">Action At</td>
							<td class="dark">Comments</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pendings as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{$data->final_evaluation_of_candidate ?? ''}}</td>
							<td>{{$data->hrManager->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')
										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany
										
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-interview-summary-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-interview-summary-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-interview-summary-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-interview-summary-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
										@endif
									</ul>
								</div>
							</td>
							@include('hrm.hiring.interview_summary_report.approve_reject_modal')					
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="approved-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
							<th rowspan="2" class="light">Final Evaluation Of Candidate</th>
							<th colspan="4" class="dark">
								<center>HR Manager Approvals</center>
							</th>
							<th colspan="4" class="light">
								<center>Division Head Approvals</center>
							</th>
							<th rowspan="2" class="dark">Created By</th>
							<th rowspan="2" class="light">Created At</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="dark">Name</td>
							<td class="dark">Action</td>
							<td class="dark">Action At</td>
							<td class="dark">Comments</td>
							<td class="light">Name</td>
							<td class="light">Action</td>
							<td class="light">Action At</td>
							<td class="light">Comments</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($approved as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{$data->final_evaluation_of_candidate ?? ''}}</td>
							<td>{{$data->hrManager->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_division_head ?? ''}}</td>
							<td>{{$data->division_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_division_head ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										
										@if($data->employeeHiringRequest->final_status != 'closed')

										@canany(['edit-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('interview-summary-report.create-or-edit',$data->id)}}">
											<i class="fa fa-edit" aria-hidden="true"></i> Edit
											</a>
										</li>
										@endif
										@endcanany

										@canany(['create-interview-summary-report'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report']);
										@endphp
										@if ($hasPermission)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Salary Details" type="button" class="btn btn-info btn-sm"  data-bs-toggle="modal"
												data-bs-target="#shortlisted-candidate-{{$data->id}}">
												<i class="fa fa-plus" aria-hidden="true"></i> Salary Details
											</button>
										</li>
										@endif
										@endcanany

										@if($data->candidate_expected_salary != 0.00 && $data->total_salary != 0.00)
										@canany(['send-candidate-documents-request-form'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['send-candidate-documents-request-form']);
										@endphp
										@if ($hasPermission)
										
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Send Candidate Personal Information Form" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#send-personal-info-form-{{$data->id}}">
												<i class="fa fa-paper-plane" aria-hidden="true"></i> Request Documents
											</button>
										</li>
										@endif
										@endcanany

										@endif
										@endif
									</ul>
								</div>
								<div class="modal fade" id="shortlisted-candidate-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('interview-summary-report.salary')}}" id="salary_{{$data->id}}">
												@csrf
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Update Salary Details</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body p-3">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-12">
																<div class="row">
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																	</div>
																</div>
																<div class="row">
																	<div class="col-xxl-12 col-lg-12 col-md-12">
																		<label for="candidate_expected_salary" class="form-label font-size-13">{{ __('Candidate Expected Salary') }}</label>
																	</div>
																	<div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
																		<div class="input-group">
																			<input name="candidate_expected_salary" id="candidate_expected_salary_{{$data->id}}" class="form-control" required
																			oninput="inputNumberAbs(this)" placeholder="Enter Candidate Expected Salary" value="@if($data->candidate_expected_salary != '0.00') {{$data->candidate_expected_salary}} @endif">
																			<div class="input-group-append">
																				<span class="input-group-text widthinput" id="basic-addon2">AED</span>
																			</div>
																		</div>
																	</div>
																	<div class="col-xxl-12 col-lg-12 col-md-12">
																		<label for="total_salary" class="form-label font-size-13">{{ __('Finalised Salary') }}</label>
																	</div>
																	<div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
																		<div class="input-group">
																			<input name="total_salary" id="total_salary_{{$data->id}}" class="form-control" required
																			oninput="inputNumberAbs(this)" placeholder="Enter Finalised Salary" value="@if($data->total_salary != '0.00') {{$data->total_salary ?? ''}} @endif">
																			<div class="input-group-append">
																				<span class="input-group-text widthinput" id="basic-addon2">AED</span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary candidate-salary"
														data-id="{{ $data->id }}" data-status="final">Submit</button>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div class="modal fade" id="send-personal-info-form-{{$data->id}}"
									tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog ">
										<div class="modal-content">
											<form method="POST" action="{{route('docs.send-email')}}" id="send_email_{{$data->id}}">
												@csrf
												<div class="modal-header">
													<h1 class="modal-title fs-5" id="exampleModalLabel">Send Candidate's Documents Request Form

													</h1>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
												<div class="modal-body p-3">
													<div class="col-lg-12">
														<div class="row">
															<div class="col-12">
																<div class="row">
																	<div class="col-xxl-6 col-lg-6 col-md-6">
																		<input type="text" name="id" value="{{$data->id}}" hidden>
																	</div>
																</div>
																<div class="row">
																	<div class="col-xxl-12 col-lg-12 col-md-12">
																		<label for="email" class="form-label font-size-13">{{ __('Email') }}</label>
																	</div>
																	<div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
																			<input name="email" id="email_{{$data->id}}" class="form-control" required
																			placeholder="Enter Candidate Email" value="@if($data->email){{$data->email}}@endif">																		
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary send-email"
														data-id="{{ $data->id }}" data-status="final">Submit</button>
												</div>
											</form>
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
	<div class="tab-pane fade show" id="selected_for_job">
		<div class="card-body">
			<div class="table-responsive">
				<table id="selected-for-job-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
							<th rowspan="2" class="light">Final Evaluation Of Candidate</th>
							<th colspan="4" class="dark">
								<center>HR Manager Approvals</center>
							</th>
							<th colspan="4" class="light">
								<center>Division Head Approvals</center>
							</th>
							<th rowspan="2" class="dark">Created By</th>
							<th rowspan="2" class="light">Created At</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="dark">Name</td>
							<td class="dark">Action</td>
							<td class="dark">Action At</td>
							<td class="dark">Comments</td>
							<td class="light">Name</td>
							<td class="light">Action</td>
							<td class="light">Action At</td>
							<td class="light">Comments</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($selectedForJob as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{$data->final_evaluation_of_candidate ?? ''}}</td>
							<td>{{$data->hrManager->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_division_head ?? ''}}</td>
							<td>{{$data->division_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_division_head ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
									@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
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
	<div class="tab-pane fade show" id="personalinfo_docs">
		<div class="card-body">
			<div class="table-responsive">
				<table id="selected-for-job-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
							<th rowspan="2" class="light">Final Evaluation Of Candidate</th>
							<th colspan="4" class="dark">
								<center>HR Manager Approvals</center>
							</th>
							<th colspan="4" class="light">
								<center>Division Head Approvals</center>
							</th>
							<th rowspan="2" class="dark">Created By</th>
							<th rowspan="2" class="light">Created At</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="dark">Name</td>
							<td class="dark">Action</td>
							<td class="dark">Action At</td>
							<td class="dark">Comments</td>
							<td class="light">Name</td>
							<td class="light">Action</td>
							<td class="light">Action At</td>
							<td class="light">Comments</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						<input hidden value="{{$docsUploaded->count()}}" id="count_docs">
						
						@foreach ($docsUploaded as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{$data->final_evaluation_of_candidate ?? ''}}</td>
							<td>{{$data->hrManager->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_division_head ?? ''}}</td>
							<td>{{$data->division_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_division_head ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										@canany(['send-candidate-documents-request-form'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['send-candidate-documents-request-form']);
										@endphp
										@if ($hasPermission && $data->candidateDetails->documents_verified_at == NULL)
										
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Resend Candidate Personal Information Form" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#send-personal-info-form-{{$data->id}}">
												<i class="fa fa-paper-plane" aria-hidden="true"></i> Resend Docs Form
											</button>
										</li>
										@endif
										@endcanany

										@canany(['verify-candidates-documents'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents']);
										@endphp
										@if ($hasPermission && $data->candidateDetails->documents_verified_at == NULL)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Verified" type="button" class="btn btn-info btn-sm btn-verify-personalinfo"  data-bs-toggle="modal"
												data-bs-target="#verify-personal-info-form-{{$data->id}}" data-id="{{$data->id}}">
												<i class="fa fa-check" aria-hidden="true"></i> Docs Verified
											</button>
										</li>
										@endif
										@endcanany
										
										@canany(['send-offer-letter'])
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole(['send-offer-letter']);
										@endphp										
										@if($data->candidateDetails->documents_verified_at != NULL && $data->offer_letter_send_at == NULL)
										<li>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Send Offer Letter & Personal Info Form" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#send-offer-letter-{{$data->id}}">
												<i class="fa fa-paper-plane" aria-hidden="true"></i> Send Offer Letter & Personal Info Form
											</button>
										</li>
										@endif
										@endcanany
									</ul>
									<div class="modal fade" id="send-offer-letter-{{$data->id}}"
										tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<form method="POST" action="{{route('personal-info.create-offer-letter')}}" id="offer_letter_{{$data->id}}">
													@csrf
													<div class="modal-header">
														<h1 class="modal-title fs-5" id="exampleModalLabel">Send Offer Letter & Personal Info Form To Candidate

														</h1>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body p-3">
														<div class="col-lg-12">
															<div class="row">
																<div class="col-12">
																	<div class="row">
																		<div class="col-xxl-6 col-lg-6 col-md-6">
																			<input type="text" name="id" value="{{$data->id}}" hidden>
																		</div>
																	</div>
																	<div class="row">
																		<input type="hidden" id="candidateId" name="candidateId" value="{{$data->id}}">
																		<div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
																		<label for="candidate_name" class="form-label font-size-13">{{ __('Candidate Name') }}</label>
																				<input name="candidate_name" id="candidate_name_{{$data->id}}" class="form-control" required
																				placeholder="Enter Candidate Name" value="@if($data->candidate_name){{$data->candidate_name}}@endif">																		
																		</div>
																		<div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
																		<label for="passport_number" class="form-label font-size-13">{{ __('Passport Number') }}</label>
																				<input name="passport_number" id="passport_number_{{$data->id}}" class="form-control" required
																				placeholder="Enter Candidate Passport Number" value="@if($data->candidateDetails->passport_number){{$data->candidateDetails->passport_number}}@endif">																		
																		</div>
																		<div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
																		<label for="contact_number" class="form-label font-size-13">{{ __('Mobile Phone') }}</label>
																		<input id="contact_number_{{$i}}" type="tel" class="widthinput form-control @error('contact_number[full]') is-invalid @enderror"
																				name="contact_number[main]" placeholder="Mobile Number" value="@if($data->candidateDetails->contact_number){{$data->candidateDetails->contact_number}} @endif"
																				autocomplete="contact_number[main]" autofocus oninput="validationOnKeyUp(this)">
																		</div>
																		<div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
																			<label for="email" class="form-label font-size-13">{{ __('Email') }}</label>
																			<input name="email" id="email_{{$data->id}}" class="form-control" required
																				placeholder="Enter Candidate Email" value="@if($data->email){{$data->email}}@endif">																		
																		</div>
																		<div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
																			<label for="job_position" class="form-label font-size-13">{{ __('Job Position') }}</label>
																			<input type="hidden" name="designation_id" value="{{$data->employeeHiringRequest->questionnaire->designation_id}}">
																			<input readonly name="designation_name" id="job_position_{{$data->id}}" class="form-control" required
																				placeholder="Enter Candidate Job Position" value="{{$data->employeeHiringRequest->questionnaire->designation->name ?? ''}}">																		
																		</div>
																		<div class="col-xxl-6 col-lg-6 col-md-6 radio-main-div">
																		<label for="probation_period" class="form-label font-size-13">{{ __('Probation Period') }}</label>
																		<div class="input-group">													
																		<input type="number" name="probation_duration_in_months" id="probation_period_{{$data->id}}" class="form-control" required
																				placeholder="Enter Probation Period" value="{{$data->candidateDetails->probation_length_in_months ?? $data->employeeHiringRequest->questionnaire->probation_length_in_months ?? ''}}">	
																				<div class="input-group-append">
																					<span class="input-group-text widthinput" >Months</span>
																				</div>
																			</div>
																																					
																		</div>
																		<div class="col-xxl-4 col-lg-4 col-md-4 radio-main-div">
																		<label for="basic_salary" class="form-label font-size-13">{{ __('Basic Salary ( Per Month )') }}</label>
																		<div class="input-group">													
																		<input name="basic_salary" id="basic_salary_{{$data->id}}" class="form-control" required data-value="{{$data->id}}"
																				placeholder="Enter Basic Salary" value="@if($data->candidateDetails->basic_salary != 0.00){{$data->candidateDetails->basic_salary}}@else{{($data->total_salary*40)/100}}@endif" oninput="validation(this)">	
																				<div class="input-group-append">
																					<span class="input-group-text widthinput" >AED</span>
																				</div>
																			</div>																	
																		</div>
																		<div class="col-xxl-4 col-lg-4 col-md-4 radio-main-div">
																		<label for="other_allowances" class="form-label font-size-13">{{ __('Other Allowance ( Per Month )') }}</label>
																		<div class="input-group">
																		<input name="other_allowances" id="other_allowances_{{$data->id}}" class="form-control" required data-value="{{$data->id}}"
																		oninput="validation(this)" placeholder="Enter Other Allowance" value="@if($data->candidateDetails->other_allowances != 0.00){{$data->candidateDetails->other_allowances}}@else{{($data->total_salary*60)/100}}@endif">	
																				<div class="input-group-append">
																					<span class="input-group-text widthinput" >AED</span>
																				</div>
																			</div>																										
																		</div>
																		<div class="col-xxl-4 col-lg-4 col-md-4 radio-main-div">
																			<label for="total_salary" class="form-label font-size-13">{{ __('Total Salary ( Per Month )') }}</label>
																			<div class="input-group">
																				<input readonly name="total_salary" id="total_salary_{{$data->id}}" class="form-control" value="@if($data->total_salary){{$data->total_salary}}@endif">	
																				<div class="input-group-append">
																					<span class="input-group-text widthinput" >AED</span>
																				</div>
																			</div>																													
																		</div>																		
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary send-offer-letter"
															data-id="{{ $data->id }}" data-status="final">Submit</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<div class="modal fade" id="send-personal-info-form-{{$data->id}}"
										tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog ">
											<div class="modal-content">
												<form method="POST" action="{{route('personal-info.send-email')}}" id="send_email_{{$data->id}}">
													@csrf
													<div class="modal-header">
														<h1 class="modal-title fs-5" id="exampleModalLabel">Resend Personal Information Form To candidate for Edit

														</h1>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body p-3">
														<div class="col-lg-12">
															<div class="row">
																<div class="col-12">
																	<div class="row">
																		<div class="col-xxl-6 col-lg-6 col-md-6">
																			<input type="text" name="id" value="{{$data->id}}" hidden>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-xxl-12 col-lg-12 col-md-12">
																			<label for="email" class="form-label font-size-13">{{ __('Comments send to candidate') }}</label>
																		</div>
																		<div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
																				<textarea rows="5" name="comment"  id="comments_{{$data->id}}" class="form-control" required
																				placeholder="Comments send to candidate" value=""></textarea>																		
																		</div>
																		<div class="col-xxl-12 col-lg-12 col-md-12">
																			<label for="email" class="form-label font-size-13">{{ __('Email') }}</label>
																		</div>
																		<div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
																				<input name="email" id="email_{{$data->id}}" class="form-control" required
																				placeholder="Enter Candidate Email" value="@if($data->email){{$data->email}}@endif">																		
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
														<button type="submit" class="btn btn-primary send-email"
															data-id="{{ $data->id }}" data-status="final">Submit</button>
													</div>
												</form>
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
	<div class="tab-pane fade show" id="rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-hiring-requests-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th rowspan="2" class="light">Sl No</th>
							<th colspan="2" class="dark">
								<center>Hiring Request</center>
							</th>
							<th colspan="3" class="light">
								<center>Candidate</center>
							</th>
							<th colspan="2" class="dark">
								<center>Rate Appearance</center>
							</th>
							<th colspan="3" class="light">
								<center>Telephonic Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>First Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Second Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Third Round</center>
							</th>
							<th colspan="3" class="light">
								<center>Forth Round</center>
							</th>
							<th colspan="3" class="dark">
								<center>Fifth Round</center>
							</th>
							<th rowspan="2" class="light">Final Evaluation Of Candidate</th>
							<th colspan="4" class="dark">
								<center>HR Manager Approvals</center>
							</th>
							<th colspan="4" class="light">
								<center>Division Head Approvals</center>
							</th>
							<th rowspan="2" class="dark">Created By</th>
							<th rowspan="2" class="light">Created At</th>
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
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="light">Date</td>
							<td class="light">Name Of Interviewer</td>
							<td class="light">Summary</td>
							<td class="dark">Date</td>
							<td class="dark">Name Of Interviewer</td>
							<td class="dark">Summary</td>
							<td class="dark">Name</td>
							<td class="dark">Action</td>
							<td class="dark">Action At</td>
							<td class="dark">Comments</td>
							<td class="light">Name</td>
							<td class="light">Action</td>
							<td class="light">Action At</td>
							<td class="light">Comments</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($rejected as $key => $data)
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
							<td>{{$data->date_of_first_round ?? ''}}</td>
							<td>
								@if(isset($data->firstRoundInterviewers))
								@if(count($data->firstRoundInterviewers) > 0)
								@foreach($data->firstRoundInterviewers as $firstRoundInterviewer)
								{{ $firstRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->first_round ?? ''}}</td>
							<td>{{$data->date_of_second_round ?? ''}}</td>
							<td>
								@if(isset($data->secondRoundInterviewers))
								@if(count($data->secondRoundInterviewers) > 0)
								@foreach($data->secondRoundInterviewers as $secondRoundInterviewer)
								{{ $secondRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->second_round ?? ''}}</td>
							<td>{{$data->date_of_third_round ?? ''}}</td>
							<td>
								@if(isset($data->thirdRoundInterviewers))
								@if(count($data->thirdRoundInterviewers) > 0)
								@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewer)
								{{ $thirdRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->third_round ?? ''}}</td>
							<td>{{$data->date_of_forth_round ?? ''}}</td>
							<td>
								@if(isset($data->forthRoundInterviewers))
								@if(count($data->forthRoundInterviewers) > 0)
								@foreach($data->forthRoundInterviewers as $forthRoundInterviewer)
								{{ $forthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->forth_round ?? ''}}</td>
							<td>{{$data->date_of_fifth_round ?? ''}}</td>
							<td>
								@if(isset($data->fifthRoundInterviewers))
								@if(count($data->fifthRoundInterviewers) > 0)
								@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewer)
								{{ $fifthRoundInterviewer->interviewerName->name ?? '' }},
								@endforeach
								@endif
								@endif
							</td>
							<td>{{$data->fifth_round ?? ''}}</td>
							<td>{{$data->final_evaluation_of_candidate ?? ''}}</td>
							<td>{{$data->hrManager->name ?? ''}}</td>
							<td>{{$data->action_by_hr_manager ?? ''}}</td>
							<td>{{$data->hr_manager_action_at ?? ''}}</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_division_head ?? ''}}</td>
							<td>{{$data->division_head_action_at ?? ''}}</td>
							<td>{{$data->comments_by_division_head ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>{{ $data->created_at ?? ''}}</td>
							<td>
							@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
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
@endcanany
@endsection
@push('scripts')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script type="text/javascript">
	var interviewersNames = {!! json_encode($interviewersNames) !!};
	$(document).ready(function () {
		if($("#count_docs").val() > 0) {
			for(var i=1; i<=$("#count_docs").val(); i++) {
				var contact_number = window.intlTelInput(document.querySelector("#contact_number_"+i), {
					separateDialCode: true,
					preferredCountries:["ae"],
					hiddenInput: "full",
					utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
				});
			}
		}		
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
		jQuery.validator.addMethod("validPassport", function(value, element) {
			return this.optional(element) || /^[A-PR-WYa-pr-wy][1-9]\d\s?\d{4}[1-9]$/i.test(value);
		}, "Passport number is not valid");   
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Letters and spaces only allowed");
		jQuery.validator.addMethod(
			"lessThan",
			function(value, element, param) {
				var bigValue = $(param).val();
				var isValid = parseFloat(value) < parseFloat(bigValue);
				return this.optional(element) || isValid;
			},
			"value must be less than total salary"
		);
		jQuery.validator.addMethod("uniquePassport", 
        function(value, element) {
            var result = false;
			var candidateId = $("#candidateId").val();
            $.ajax({
                type:"POST",
                async: false,
                url: "{{route('employee.uniquePassport')}}", // script to validate in server side
                data: {passportNumber: value,candidateId:candidateId},
                success: function(data) {
                    result = (data == true) ? true : false;
                }
            });
            // return true if username is exist in database
            return result; 
        }, 
        "This Password is already taken! Try another."
    );

		$('.add-interview-summary').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			$('#form_'+id).validate({ 
				rules: {
					date: {
						required: true,
					},
					comment: {
						required: true,
					},
					round: {
						required: true,
					},
					id: {
						required: true,
					},
					'interviewer_id[]': {
						required: true,
					},
				},
			});
		})
		$('.candidate-salary').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			$('#salary_'+id).validate({ 
				rules: {
					candidate_expected_salary: {
						required: true,
					},
					total_salary: {
						required: true,
					},
					id: {
						required: true,
					}           
				},
				errorPlacement: function(error, element) {
					if (element.attr('name') === 'candidate_expected_salary' || element.attr('name') === 'total_salary') {
						error.addClass('other-error');
						error.insertAfter(element.closest('.input-group'));
					} else {
						error.addClass('other-error');
						error.insertAfter(element);
					}
				},
			});
		})
		$('.final-interview-summary').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			$('#final_'+id).validate({ 
				rules: {
					candidate_selected: {
						required: true,
					},
					comment: {
						required: true,
					},
					round: {
						required: true,
					},
					id: {
						required: true,
					},
				},
			});
		})
		$('.send-email').click(function (e) {
	        var id = $(this).attr('data-id');
			$('#send_email_'+id).validate({ 
				rules: {
					email: {
						required: true,
						email: true,
                        accept:"[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}"
					},
				},
			});
		})
		$('.send-offer-letter').click(function (e) {
	        var id = $(this).attr('data-id');
			$('#offer_letter_'+id).validate({ 
				rules: {
					candidate_name: {
                        required: true,
                        lettersonly: true,
                    },
					passport_number: {
                        required: true,
                        validPassport:true,
						uniquePassport: true,
                    },
					"contact_number[main]": {
                        required: true,
                        minlength: 5,
                        maxlength: 20,
                    },
					email: {
						required: true,
						email: true,
                        accept:"[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}"
					},
					designation_id: {
						required: true,
					},
					basic_salary: {
						required: true,
						lessThan: "#total_salary_"+id,
					},
					other_allowances: {
						required: true,
						lessThan: "#total_salary_"+id,
					},
					total_salary: {
						required: true,
					},
					probation_duration_in_months: {
                        required: true,
                        minlength: 1,
                        maxlength: 2,
						max: 12,
						min: 1,
                    },					
				},
			});
		})
				
	})
	function validationOnKeyUp(currentPriceInput) {
	    var id = currentPriceInput.id;
	    var input = document.getElementById(id);
	    var val = input.value;
	    val = val.replace(/^0+|[^\d]/g, '');
	    input.value = val;
	}
	function validation(currentPriceInput) {
		var id = currentPriceInput.id;
		var input = document.getElementById(id);
		var val = input.value;
		val = val.replace(/^0+|[^\d.]/g, '');
		if(val.split('.').length>2)
		{
			val = val.replace(/\.+$/,"");
		}
		input.value = val;	
		var index = $("#"+id).data('value');
		var totalSalary = $("#total_salary_"+index).val();
		if(id == "basic_salary_"+index) {
			document.getElementById('other_allowances_'+index).value=totalSalary-val;
		}
		else if(id == "other_allowances_"+index) {
			document.getElementById('basic_salary_'+index).value=totalSalary-val;
		}
	}
	$('.btn-verify-personalinfo').click(function (e) {
		var id = $(this).attr('data-id');
		let url = '{{ route('docs.verified') }}';
		var confirm = alertify.confirm('Are you sure you verified this candidate documents ?',function (e) {
			if (e) {
				$.ajax({
					type: "POST",
					url: url,
					dataType: "json",
					data: {
						id: id,
						_token: '{{csrf_token()}}'
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
	})
</script>
@endpush