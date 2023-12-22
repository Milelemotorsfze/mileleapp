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
							@include('hrm.hiring.interview_summary_report.approve_reject_modal')					
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
	<div class="tab-pane fade show" id="hr-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="hr-rejected-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
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
	<div class="tab-pane fade show" id="teamlead-rejected-hiring-requests">
		<div class="card-body">
			<div class="table-responsive">
				<table id="teamlead-rejected-hiring-requests-table" class="table table-striped table-editable table-edits table data-table-class">
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
</br>
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