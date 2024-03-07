@extends('layouts.table')
@section('content')
@if(Auth::user()->interview_summary_report_approval == true)
@if(count($HRManagerPendings) > 0 OR count($HRManagerApproved) > 0 OR count($HRManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Interview Summary Report Approvals By HR Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#hr-pending-interview-summary">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-approved-interview-summary">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-rejected-interview-summary">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="hr-pending-interview-summary">
		<div class="card-body">
			<div class="table-responsive">
				<table id="hr-pending-interview-summary-table" class="table table-striped table-editable table-edits table data-table-class">
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
							<th colspan="2" class="dark">
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
							<!-- <td class="dark">Action At</td>
								<td class="dark">Comments</td> -->
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>
							@if($data->date_of_telephonic_interview != '')
									{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
								@endif
							</td>
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
							<td>@if($data->date_of_first_round != '')
									{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_second_round != '')
									{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_third_round != '')
									{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_forth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_fifth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
								@endif</td>
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
							<!-- <td>{{$data->hr_manager_action_at ?? ''}}</td>
								<td>{{$data->comments_by_hr_manager ?? ''}}</td> -->
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
							<td><label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label></td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')										
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							<div class="modal fade" id="approve-interview-summary-approvals-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Interview Summary Report Approval</h1>
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
																<textarea rows="5" id="comments{{$data->id}}" class="form-control" name="comment">
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
							<div class="modal fade" id="reject-interview-summary-approvals-{{$data->id}}"
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
																<textarea rows="5" id="reject-comments{{$data->id}}" class="form-control" name="comment">
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
	<div class="tab-pane fade show" id="hr-approved-interview-summary">
		<div class="card-body">
			<div class="table-responsive">
				<table id="hr-approved-interview-summary-table" class="table table-striped table-editable table-edits table data-table-class">
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
						@foreach ($HRManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>@if($data->date_of_telephonic_interview != '')
									{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_first_round != '')
									{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_second_round != '')
									{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_third_round != '')
									{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_forth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_fifth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
								@endif</td>
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
							<td>
							@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y')}}
								@endif
							</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
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
	<div class="tab-pane fade show" id="hr-rejected-interview-summary">
		<div class="card-body">
			<div class="table-responsive">
				<table id="hr-rejected-interview-summary-table" class="table table-striped table-editable table-edits table data-table-class">
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
						@foreach ($HRManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>@if($data->date_of_telephonic_interview != '')
									{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_first_round != '')
									{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_second_round != '')
									{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_third_round != '')
									{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_forth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_fifth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y')}}
								@endif</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
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
</div>
@endif
</br>
@if(count($divisionHeadPendings) > 0 || count($divisionHeadApproved) > 0 || count($divisionHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Interview Summary Report Approvals By Department Head
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#teamlead-pending-interview-summary">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#teamlead-approved-interview-summary">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#teamlead-rejected-interview-summary">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="teamlead-pending-interview-summary">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-pending-interview-summary-table" class="table table-striped table-editable table-edits table data-table-class">
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
						@foreach ($divisionHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>@if($data->date_of_telephonic_interview != '')
									{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_first_round != '')
									{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_second_round != '')
									{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_third_round != '')
									{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_forth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_fifth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y')}}
								@endif</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<!-- <td>{{$data->action_by_division_head ?? ''}}</td> -->
							<!-- <td>{{$data->division_head_action_at ?? ''}}</td> -->
							<!-- <td>{{$data->comments_by_division_head ?? ''}}</td> -->
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>
							@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif
								</td>
							<td>
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										@include('hrm.hiring.interview_summary_report.viewDetailsActionBtn')
										<li>
											@if(isset($type))
											@if($type == 'approve')
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i>  Approve 
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@elseif(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
											@if(isset($data->is_auth_user_can_approve['can_approve']))
											@if($data->is_auth_user_can_approve['can_approve'] == true)
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
												data-bs-target="#approve-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve
											</button>
											<button style="width:100%; margin-top:2px; margin-bottom:2px;" title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
												data-bs-target="#reject-interview-summary-approvals-{{$data->id}}">
											<i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject
											</button>
											@endif
											@endif
											@endif
										</li>
									</ul>
								</div>
							</td>
							<div class="modal fade" id="approve-interview-summary-approvals-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<div class="modal-header">
											<h1 class="modal-title fs-5" id="exampleModalLabel">Interview Summary Report Approval</h1>
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
																<textarea rows="5" id="comments{{$data->id}}" class="form-control" name="comment">
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
							<div class="modal fade" id="reject-interview-summary-approvals-{{$data->id}}"
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
																<textarea rows="5" id="reject-comments{{$data->id}}" class="form-control" name="comment">
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
	<div class="tab-pane fade show" id="teamlead-approved-interview-summary">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-approved-interview-summary-table" class="table table-striped table-editable table-edits table data-table-class">
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
						@foreach ($divisionHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>@if($data->date_of_telephonic_interview != '')
									{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_first_round != '')
									{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_second_round != '')
									{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_third_round != '')
									{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_forth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_fifth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y')}}
								@endif</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_division_head ?? ''}}</td>
							<td>@if($data->division_head_action_at != '')
									{{\Carbon\Carbon::parse($data->division_head_action_at)->format('d M Y')}}
								@endif
								</td>
							<td>{{$data->comments_by_division_head ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
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
	<div class="tab-pane fade show" id="teamlead-rejected-interview-summary">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-rejected-interview-summary-table" class="table table-striped table-editable table-edits table data-table-class">
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
						@foreach ($divisionHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->employeeHiringRequest->uuid ?? ''}}</td>
							<td>{{ $data->employeeHiringRequest->questionnaire->designation->name ?? '' }}</td>
							<td>{{ $data->candidate_name ?? '' }}</td>
							<td>{{ $data->nationalities->name ?? '' }}</td>
							<td>{{ $data->genderName->name ?? '' }}</td>
							<td class="texttransform">{{ $data->rate_dress_appearance ?? ''}}</td>
							<td class="texttransform">{{ $data->rate_body_language_appearance ?? ''}}</td>
							<td>@if($data->date_of_telephonic_interview != '')
									{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_first_round != '')
									{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
								@endif
								</td>
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
							<td>@if($data->date_of_second_round != '')
									{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_third_round != '')
									{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_forth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->date_of_fifth_round != '')
									{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
								@endif</td>
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
							<td>@if($data->hr_manager_action_at != '')
									{{\Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y')}}
								@endif</td>
							<td>{{$data->comments_by_hr_manager ?? ''}}</td>
							<td>{{$data->divisionHeadName->name ?? ''}}</td>
							<td>{{$data->action_by_division_head ?? ''}}</td>
							<td>@if($data->division_head_action_at != '')
									{{\Carbon\Carbon::parse($data->division_head_action_at)->format('d M Y')}}
								@endif</td>
							<td>{{$data->comments_by_division_head ?? ''}}</td>
							<td>{{ $data->createdBy->name ?? ''}}</td>
							<td>@if($data->created_at != '')
									{{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s')}}
								@endif</td>
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
</div>
@endif
</br>
@endif
@endsection
@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		$('.status-reject-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			comments = $("#reject-comments"+id).val();
	        approveOrRejectHiringrequest(id, status,comments)
	    })
	    $('.status-approve-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
			comments = $("#comments"+id).val();
	        approveOrRejectHiringrequest(id, status,comments)
	    })
        function approveOrRejectHiringrequest(id, status,comments) {
			
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
	                        comment: comments,
							current_approve_position: current_approve_position,
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success: function (data) {console.log(data);
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