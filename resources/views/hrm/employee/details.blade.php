<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
	@media (max-width: 575) {
	.col-lg-4.col-md-3.col-sm-6.col-12 span {
	padding-bottom: 20px;
	display: block;
	}
	}
    table {
        border-collapse: collapse;
        width: 100%;
    }
	.vertical-heading th {
		font-size: 14px;
	}
    th, td {
        border: 1px solid #e9e9ef;
        padding: 8px;
        text-align: left;
    }
	.texttransform {
	text-transform: capitalize;
	}
</style>
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
@endphp
@if ($hasPermission)
<div class="card-body">
	<div class="portfolio">
		<ul class="nav nav-pills nav-fill" id="my-tab">
            <li class="nav-item">
				<a class="nav-link active" data-bs-toggle="pill" href="#personal-info-{{$data->id}}"> Personal & Contact Info</a>
			</li>
            <!-- <li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#contact-info-{{$data->id}}"> Contact Info</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#visa-info-{{$data->id}}"> Visa & Employment Info</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#compensation-benefits-{{$data->id}}"> Compensation & Benefits</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#off-boarding-{{$data->id}}"> Off Boarding</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#interview-summary-{{$data->id}}"> Interview Summary Report</a>
			</li> -->
			@if(isset($data) && $data->documents_form_submit_at != NULL)
			@php
			$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
			@endphp
			@if ($hasPermission)
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#documents-{{$data->id}}"> Documents</a>
			</li>
			@if($data->offer_letter_send_at != '')
			<li class="nav-item">
				<a class="nav-link" data-bs-toggle="pill" href="#job-offer-letter-{{$data->id}}"> Job Offer Letter</a>
			</li>
			@endif
			
			@endif
			@endif
		</ul>
	</div>
	</br>
	<div class="tab-content">
		<div class="tab-pane fade show" id="interview-summary-{{$data->id}}">
			<div class="row">
				<div class="col-xxl-6 col-lg-6 col-md-12">
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Candidate Information</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->candidate_name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Current Status  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>
										@if($data->candidate_current_status == 'Rejected')
										<label class="badge badge-soft-danger">{{ $data->candidate_current_status ?? 'NA' }}</label>
										@elseif($data->candidate_current_status == 'Candidate Selected And Approved' OR $data->candidate_current_status == 'Candidate Selected And Hiring Request Closed')  
										<label class="badge badge-soft-success">{{ $data->candidate_current_status ?? 'NA' }}</label>  
										@else
										<label class="badge badge-soft-info">{{ $data->candidate_current_status ?? 'NA' }}</label>
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Nationality  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->nationalities->name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Gender  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->genderName->name ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Rate the Appearance Of Applicant</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Dress  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->rate_dress_appearance ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Body Language  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->rate_body_language_appearance ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@if($data->date_of_telephonic_interview)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Telephonic Round Interview Summary</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if($data->date_of_telephonic_interview != '')
										{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}
										@else
										NA
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if(isset($data->telephonicInterviewers))
										@if(count($data->telephonicInterviewers) > 0)
										@foreach($data->telephonicInterviewers as $telephonicInterviewers)
										{{ $telephonicInterviewers->interviewerName->name ?? 'NA' }},</br>
										@endforeach
										@endif
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Interview Summary  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->telephonic_interview ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($data->date_of_first_round)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">First Round Interview Summary</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if($data->date_of_first_round != '')
										{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}
										@else
										NA
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if(isset($data->firstRoundInterviewers))
										@if(count($data->firstRoundInterviewers) > 0)
										@foreach($data->firstRoundInterviewers as $firstRoundInterviewers)
										{{ $firstRoundInterviewers->interviewerName->name ?? 'NA' }},</br>
										@endforeach
										@endif
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Interview Summary  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->first_round ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($data->date_of_second_round)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Second Round Interview Summary</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if($data->date_of_second_round != '')
										{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}
										@else
										NA
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if(isset($data->secondRoundInterviewers))
										@if(count($data->secondRoundInterviewers) > 0)
										@foreach($data->secondRoundInterviewers as $secondRoundInterviewers)
										{{ $secondRoundInterviewers->interviewerName->name ?? 'NA' }},</br>
										@endforeach
										@endif
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Interview Summary  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->second_round ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($data->date_of_third_round)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Third Round Interview Summary</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if($data->date_of_third_round != '')
										{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}
										@else
										NA
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if(isset($data->thirdRoundInterviewers))
										@if(count($data->thirdRoundInterviewers) > 0)
										@foreach($data->thirdRoundInterviewers as $thirdRoundInterviewers)
										{{ $thirdRoundInterviewers->interviewerName->name ?? 'NA' }},</br>
										@endforeach
										@endif
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Interview Summary  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->third_round ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($data->date_of_forth_round)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Forth Round Interview Summary</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if($data->date_of_forth_round != '')
										{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}
										@else
										NA
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if(isset($data->forthRoundInterviewers))
										@if(count($data->forthRoundInterviewers) > 0)
										@foreach($data->forthRoundInterviewers as $forthRoundInterviewers)
										{{ $forthRoundInterviewers->interviewerName->name ?? 'NA' }},</br>
										@endforeach
										@endif
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Interview Summary  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->forth_round ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($data->date_of_fifth_round)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Fifth Round Interview Summary</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if($data->date_of_fifth_round != '')
										{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}
										@else
										NA
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">
										@if(isset($data->fifthRoundInterviewers))
										@if(count($data->fifthRoundInterviewers) > 0)
										@foreach($data->fifthRoundInterviewers as $fifthRoundInterviewers)
										{{ $fifthRoundInterviewers->interviewerName->name ?? 'NA' }},</br>
										@endforeach
										@endif
										@endif
										</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Interview Summary  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span class="texttransform">{{ $data->fifth_round ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
					@if($data->final_evaluation_of_candidate)
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-lg-8 col-md-8 col-sm-8 col-8">
										<h4 class="card-title">Final Evaluation Of Candidate</h4>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-4">
										@if($data->candidate_selected == 'no')
										<label class="badge badge-soft-danger">Not Selected</label>
										@elseif($data->candidate_selected == 'yes')  
										<label class="badge badge-soft-success">Selected</label> 
										@endif
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-6 col-12">
										<span class="texttransform">{{ $data->final_evaluation_of_candidate ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
				</div>
				<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
					@if($data->resume_file_name)
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<h4 class="card-title">Resume</h4>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
									<button style="float:right;" type="button" class="btn btn-sm btn-info mt-3 ">
									<a href="{{ url('resume/' . $data->resume_file_name) }}" download class="text-white">
									Download
									</a>
									</button>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<iframe src="{{ url('resume/' . $data->resume_file_name) }}" alt="Resume" style="height:1000;"></iframe>
							</div>
						</div>
					</div>
					@endif
					@if($data->hr_manager_id)
					<div class="card">
						<div class="card-header" style="background-color:#e8f3fd;">
							<h4 class="card-title">
								<center>Approvals By</center>
							</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="card">
										<div class="card-header">
											<center>
												<h4 class="card-title">HR Manager</h4>
											</center>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-lg-2 col-md-12 col-sm-12">
													Name :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->hrManager->name ?? 'NA'}}
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Status :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													<label class="badge texttransform @if($data->action_by_hr_manager =='pending') badge-soft-info 
														@elseif($data->action_by_hr_manager =='approved') badge-soft-success 
														@else badge-soft-danger @endif">{{$data->action_by_hr_manager ?? 'NA'}}</label>
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Date & Time :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													@if($data->hr_manager_action_at != '')
													{{ \Carbon\Carbon::parse($data->hr_manager_action_at)->format('d M Y, H:i:s') }}
													@else
													NA
													@endif
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Comments :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->comments_by_hr_manager ?? 'NA'}}
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="card">
										<div class="card-header">
											<center>
												<h4 class="card-title">Division Head</h4>
											</center>
										</div>
										<div class="card-body">
											<div class="row">
												<div class="col-lg-2 col-md-12 col-sm-12">
													Name :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->divisionHeadName->name ?? 'NA'}}
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Status :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													<label class="badge texttransform @if($data->action_by_division_head =='pending') badge-soft-info 
														@elseif($data->action_by_division_head =='approved') badge-soft-success 
														@else badge-soft-danger @endif">{{$data->action_by_division_head ?? 'NA'}}</label>
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Date & Time :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													@if($data->division_head_action_at != '')
													{{ \Carbon\Carbon::parse($data->division_head_action_at)->format('d M Y, H:i:s') }}
													@else 
												NA
													@endif
												</div>
												<div class="col-lg-2 col-md-12 col-sm-12">
													Comments :
												</div>
												<div class="col-lg-10 col-md-12 col-sm-12">
													{{$data->comments_by_division_head ?? 'NA'}}
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xxl-8 col-lg-8 col-md-8">	
			</div>
			<div class="col-xxl-4 col-lg-4 col-md-4">
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
		</div>
		<div class="tab-pane fade show" id="documents-{{$data->id}}">
			<div class="card">
				<div class="card-header">
					<div class="card-title fw-bold">Documents</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							@php
							$hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents']);
							@endphp
							@if ($hasPermission && $data->documents_verified_at == NULL && $data->documents_form_send_at != NULL && $data->documents_form_submit_at != NULL)
							<!-- && $data->documents_form_send_at < $data->documents_form_submit_at -->
							<button style="margin-top:2px; margin-right:2px; margin-bottom:2px; float:right" title="Verified" type="button" class="btn btn-info btn-sm btn-verify-docs"  data-bs-toggle="modal"
								data-bs-target="#verify-docs-{{$data->id}}" data-id="{{$data->id}}">
							<i class="fa fa-check" aria-hidden="true"></i> Verified Documents
							</button>
							@endif
							@php
							$hasPermission = Auth::user()->hasPermissionForSelectedRole(['send-candidate-documents-request-form']);
							@endphp
							@if ($hasPermission && $data->documents_verified_at == NULL)	            							
							<button style="margin-top:2px; margin-right:2px; margin-bottom:2px; float:right" title="Resend Candidate Personal Information Form" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
								data-bs-target="#send-docs-form-{{$data->id}}">
							<i class="fa fa-paper-plane" aria-hidden="true"></i> Resend Docs Form
							</button>
							@endif
							<div class="modal fade" id="send-docs-form-{{$data->id}}"
								tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog ">
									<div class="modal-content">
										<form method="POST" action="{{route('docs.send-email')}}" id="send_email_{{$data->id}}">
											@csrf
											<div class="modal-header">
												<h1 class="modal-title fs-5" id="exampleModalLabel">Resend Documents Request Form To candidate for Edit
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
					</div>
					<div class="row">
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							@if($data->image_path)
							<div class="row">
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center">
									<h6 class="fw-bold text-center mb-1" style="float:left">Passport Size Photograph</h6>
								</div>
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
									<a href="{{ url('hrm/employee/photo/' . $data->image_path) }}" target="_blank">
									<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
									</a>
									<a href="{{ url('hrm/employee/photo/' . $data->image_path) }}" download>
									<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
									</a>
								</div>
							</div>
							<iframe src="{{ url('hrm/employee/photo/' . $data->image_path) }}" alt="Passport Size Photograph"></iframe>
							@endif
						</div>
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							@if($data->resume)
							<div class="row">
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center">
									<h6 class="fw-bold text-center mb-1" style="float:left">Resume</h6>
								</div>
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
									<a href="{{ url('hrm/employee/resume/' . $data->resume) }}" target="_blank">
									<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
									</a>
									<a href="{{ url('hrm/employee/resume/' . $data->resume) }}" download>
									<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
									</a>
								</div>
							</div>
							<iframe src="{{ url('hrm/employee/resume/' . $data->resume) }}" alt="Resume"></iframe>
							@endif
						</div>
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							@if($data->visa)
							<div class="row">
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center">
									<h6 class="fw-bold text-center mb-1" style="float:left">Visa</h6>
								</div>
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
									<a href="{{ url('hrm/employee/visa/' . $data->visa) }}" target="_blank">
									<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
									</a>
									<a href="{{ url('hrm/employee/visa/' . $data->visa) }}" download>
									<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
									</a>
								</div>
							</div>
							<iframe src="{{ url('hrm/employee/visa/' . $data->visa) }}" alt="Visa"></iframe>
							@endif
						</div>
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center">
							@if($data->emirates_id_file)
							<div class="row">
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
									<h6 class="fw-bold text-center mb-1" style="float:left">Emirates ID</h6>
								</div>
								<div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
									<a href="{{ url('hrm/employee/emirates_id/' . $data->emirates_id_file) }}" target="_blank">
									<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
									</a>
									<a href="{{ url('hrm/employee/emirates_id/' . $data->emirates_id_file) }}" download>
									<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
									</a>
								</div>
							</div>
							<iframe src="{{ url('hrm/employee/emirates_id/' . $data->emirates_id_file) }}" alt="Emirates ID"></iframe>
							@endif
						</div>
					</div>
					@if($data->candidatePassport->count() > 0)
					<div class="row m-3">
						<h6 class="fw-bold text-center mb-13">Passport (First & Second page)</h6>
						@foreach($data->candidatePassport as $document)
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							<a href="{{ url('hrm/employee/passport/' . $document->document_path) }}" target="_blank">
							<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
							</a>
							<a href="{{ url('hrm/employee/passport/' . $document->document_path) }}" download>
							<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
							</a>
							<iframe src="{{ url('hrm/employee/passport/' . $document->document_path) }}" alt="Passport (First & Second page)"></iframe>                                 
						</div>
						@endforeach
					</div>
					@endif
					@if($data->candidateNationalId->count() > 0)
					<div class="row m-3">
						<h6 class="fw-bold text-center mb-13">National ID (First & Second page)</h6>
						@foreach($data->candidateNationalId as $document)
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							<a href="{{ url('hrm/employee/national_id/' . $document->document_path) }}" target="_blank">
							<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
							</a>
							<a href="{{ url('hrm/employee/national_id/' . $document->document_path) }}" download>
							<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
							</a>
							<iframe src="{{ url('hrm/employee/national_id/' . $document->document_path) }}" alt="National ID (First & Second page)"></iframe>                                  
						</div>
						@endforeach
					</div>
					@endif
					@if($data->candidateEduDocs->count() > 0)
					<div class="row m-3">
						<h6 class="fw-bold text-center mb-13">Attested Educational Documents</h6>
						@foreach($data->candidateEduDocs as $document)
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							<a href="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" target="_blank">
							<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
							</a>
							<a href="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" download>
							<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
							</a>
							<iframe src="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" alt="Attested Educational Documents"></iframe>                                  
						</div>
						@endforeach
					</div>
					@endif
					@if($data->candidateProDipCerti->count() > 0)
					<div class="row m-3">
						<h6 class="fw-bold text-center mb-13">Attested Professional Diplomas / Certificates</h6>
						@foreach($data->candidateProDipCerti as $document)
						<div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
							<a href="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" target="_blank">
							<button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
							</a>
							<a href="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" download>
							<button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
							</a>
							<iframe src="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" alt="Attested Professional Diplomas / Certificates"></iframe>                                   
						</div>
						@endforeach
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="tab-pane fade show" id="job-offer-letter-{{$data->id}}">
			<div class="card">
				<div class="card-header">
					<div class="card-title fw-bold">Job Offer Letter</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-xxl-12 col-lg-12 col-md-12">
							@if($data->offer_letter_fileName)
							<div class="card">
								<div class="card-header">
									<div class="row">
										<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
											<h4 class="card-title">Resume</h4>
										</div>
										<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
											<button style="float:right;" type="button" class="btn btn-sm btn-info mt-3 ">
											<a href="{{ url('hrm/employee/offer_letter/' . $data->offer_letter_fileName) }}" download class="text-white">
											Download
											</a>
											</button>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<iframe src="{{ url('hrm/employee/offer_letter/' . $data->offer_letter_fileName) }}" alt="Offer Letter" style="height:1000;"></iframe>
									</div>
								</div>
							</div>
							@else
							@include('hrm.employee.offerLetter')
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade show active" id="personal-info-{{$data->id}}">
			<div class="row">
				<div class="col-xxl-6 col-lg-6 col-md-12">
					<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Primary Details</h4>
							</div>
							<div class="card-body">
								<div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Employee Code  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->employee_code ?? 'NA' }}</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Employee Full Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->first_name.' '.$data->last_name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> First Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->first_name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Last Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->last_name ?? 'NA' }}</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Designation  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->designation->name ?? 'NA' }}</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Department  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->department->name ?? 'NA' }}</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Gender  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->genderName->name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Date Of Birth  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>
										@if(isset($data) && isset($data) && $data->dob != '')
										{{\Carbon\Carbon::parse($data->dob)->format('d M Y') ?? 'NA'}}
										@else
										NA
										@endif
										</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Birthday Month  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>
										@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('F')}} @else NA @endif
										</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Age  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>
										@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->age}} @else NA @endif
										</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Marital Status  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->maritalStatus->name ?? 'NA' }}</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Religion  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->religionName->name ?? 'NA' }}</span>
									</div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Nationality  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{$data->countryMaster->nationality ?? $data->countryMaster->name ?? $data->countryMaster->iso_3166_code ?? 'NA'}}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Father’s Full Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->name_of_father ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Mother’s Full Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->name_of_mother ?? 'NA' }}</span>
									</div>									
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Passport Number  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->passport_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Passport Expiry Date  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>@if(isset($data) && isset($data) && $data->passport_expiry_date != ''){{\Carbon\Carbon::parse($data->passport_expiry_date)->format('d M Y')}} @else NA @endif</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Educational Qualification  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->educational_qualification ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Year of Completion  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $data->year_of_completion ?? 'NA' }}</span>
									</div>                                   
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Spoken Languages  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>
										@if(isset($data->employeeLanguages))
										@foreach($data->employeeLanguages as $language)
										{{ $language->language->name ?? 'NA' }} ,
										@endforeach
										@endif
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Dependents</h4>
						</div>
						<div class="card-body">
							@if(isset($data) && isset($data) && (isset($data->spouse_name) OR isset($data->spouse_passport_number) OR 
							isset($data->spouse_passport_expiry_date) OR isset($data->spouse_dob) OR isset($data->spouseNationality->name)))
							<div class="row">
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Spouse Name  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{ $data->spouse_name ?? 'NA' }}</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Spouse Passport Number  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{ $data->spouse_passport_number ?? 'NA' }}</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Spouse Passport Expiry Date  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>
									@if(isset($data) && isset($data) && $data->spouse_passport_expiry_date != '')
									{{\Carbon\Carbon::parse($data->spouse_passport_expiry_date)->format('d M Y') ?? 'NA'}}
									@else
									NA
									@endif
									</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Spouse Date Of Birth  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>
									@if(isset($data) && isset($data) && $data->spouse_dob != '')
									{{\Carbon\Carbon::parse($data->spouse_dob)->format('d M Y') ?? 'NA'}}
									@else
									NA
									@endif
									</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Spouse Nationality  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{ $data->spouseNationality->name ?? 'NA' }}</span>
								</div>
							</div>
							@else
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-12">
									<label for="choices-single-default" class="form-label">Spouse details not added in the system</label>
								</div>
							</div>
							@endif
							<div class="card-header">
								<h4 class="card-title">Children</h4>
							</div>
							</br>
							@if(isset($data->employeeChildren) && count($data->employeeChildren) > 0)
							@foreach($data->employeeChildren as $children)
							<div class="row" style="border:1px solid #e9e9ef; margin-bottom:10px;">
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Child Name  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{ $children->child_name ?? 'NA' }}</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Child Passport Number  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{ $children->child_passport_number ?? 'NA' }}</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Child Passport Expiry Date  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span> @if($children->child_passport_expiry_date != '') {{\Carbon\Carbon::parse($children->child_passport_expiry_date)->format('d M Y') }} @else NA @endif</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Child Date Of Birth  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{\Carbon\Carbon::parse($children->child_dob)->format('d M Y') ?? 'NA'}}</span>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Child Nationality  :</label>
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-12">
									<span>{{ $children->childNationality->name ?? 'NA' }}</span>
								</div>
							</div>
							@endforeach
							@else
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-12">
									<label for="choices-single-default" class="form-label">Children details not added in the system</label>
								</div>
							</div>
							@endif
						</div>
					</div>
				</div>
				<div class="col-xxl-6 col-lg-6 col-md-12">
                	<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Address and Contact Details in UAE</h4>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Company Phone Number  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->company_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Personal Phone Number  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->contact_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Residence Telephone Number  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->residence_telephone_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Company Email Address  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->user->email ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Personal Email Address  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->personal_email_address ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Father  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->name_of_father ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name Of Mother  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->name_of_mother ?? 'NA' }}</span>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Address in UAE  :</label>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-12">
										<span>{{ $data->candidateDetails->address_uae ?? 'NA' }}</span>
									</div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Contact in case of Emergency (UAE)</h4>
							</div>
							<div class="card-body">
								@if(isset($data->empEmergencyContactUAE) && count($data->empEmergencyContactUAE) > 0)
								@foreach($data->empEmergencyContactUAE as $contactUAE)                                          
								<div class="row" style="border:1px solid #e9e9ef; margin-bottom:10px;">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactUAE->name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Relation  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactUAE->relationName->name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Email  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactUAE->email_address ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Contact Number  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactUAE->contact_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Alternative Contact Number  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactUAE->alternative_contact_number ?? 'NA' }}</span>
									</div>
								</div>
								@endforeach
                                @else
                                <div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<label for="choices-single-default" class="form-label">Emergency contact details in UAE have not been added to the system</label>
									</div>
								</div>
								@endif
							</div>
						</div>
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Contact in case of Emergency (Home Country)</h4>
							</div>
							<div class="card-body">
								@if(isset($data->empEmergencyContactHomeCountry) && count($data->empEmergencyContactHomeCountry) > 0)
								@foreach($data->empEmergencyContactHomeCountry as $contactHomeCountry)                                          
								<div class="row" style="border:1px solid #e9e9ef; margin-bottom:10px;">
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Name  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactHomeCountry->name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Relation  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactHomeCountry->relationName->name ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Email  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactHomeCountry->email_address ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Contact Number  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactHomeCountry->contact_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Alternative Contact Number  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactHomeCountry->alternative_contact_number ?? 'NA' }}</span>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-6 col-12">
										<label for="choices-single-default" class="form-label"> Home Country Address  :</label>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-12">
										<span>{{ $contactHomeCountry->home_country_address ?? 'NA' }}</span>
									</div>
								</div>
								@endforeach
                                @else
                                <div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<label for="choices-single-default" class="form-label">Emergency contact details in Home Country have not been added to the system</label>
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="visa-info-{{$data->id}}">
			<div class="row">
                <div class="col-xxl-6 col-lg-6 col-md-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Visa Information</h4>
						</div>
						<div class="card-body">
							<div class="row">											
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> CEC / Person Code No.  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->cec_or_person_code_number ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Emirates ID  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->emirates_id ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Emirates ID Expiry  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->emirates_expiry != ''){{\Carbon\Carbon::parse($data->emirates_expiry)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Passport Number  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->passport_number ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Passport Issue Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->passport_issue_date != ''){{\Carbon\Carbon::parse($data->passport_issue_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Passport Expiry  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->passport_expiry_date != ''){{\Carbon\Carbon::parse($data->passport_expiry_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Passport Issued Place  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->passport_place_of_issue ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Passport Status  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->passport_status_name ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Passport Status Remarks  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>NA</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Visa Type  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->visaType->name ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Visa Number  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->visa_number ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Visa Issue Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->visa_issue_date != ''){{\Carbon\Carbon::parse($data->visa_issue_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Visa Expiry Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->visa_expiry_date != ''){{\Carbon\Carbon::parse($data->visa_expiry_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Reminder Date for Visa Renewal  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->reminder_date_for_visa_renewal != ''){{\Carbon\Carbon::parse($data->reminder_date_for_visa_renewal)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Visa Issuing Country  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->visaIssueCountry->name ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Sponsorship  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->sponsorshipName->name ?? 'NA' }}</span>
								</div>
							</div>
						</div>
					</div>
                </div>
				<div class="col-xxl-6 col-lg-6 col-md-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Employment Information</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Company Joining Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->company_joining_date != ''){{\Carbon\Carbon::parse($data->company_joining_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Current Status  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->current_status_name ?? ''}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Status Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->status_date != ''){{\Carbon\Carbon::parse($data->status_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Pobation Duration  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->probation_duration_in_months .' Months' ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Probation Period Start Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->probation_period_start_date != ''){{\Carbon\Carbon::parse($data->probation_period_start_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Probation Period End Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->probation_period_end_date != ''){{\Carbon\Carbon::parse($data->probation_period_end_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Employment Contract Type  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->employment_contract_name ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Employment Contract Start Date  :</label>
								</div>	
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->employment_contract_start_date != ''){{\Carbon\Carbon::parse($data->employment_contract_start_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Employment Contract End Date :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->employment_contract_end_date != ''){{\Carbon\Carbon::parse($data->employment_contract_end_date)->format('d M Y')}} @else 'NA' @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Employment Contract Probation Period  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->employment_contract_probation_period_in_months.' Months' ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Employment Contract Probation End Date  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->employment_contract_probation_end_date != ''){{\Carbon\Carbon::parse($data->employment_contract_probation_end_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Work Location  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->location->name ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Division  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->department->division->name ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Team Lead /Reporting Manager  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->teamLeadOrReportingManager->name ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Division Head  :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->department->division->divisionHead->name ?? 'NA'}}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="compensation-benefits-{{$data->id}}">
			<div class="row">
                <div class="col-xxl-6 col-lg-6 col-md-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Salary & Increment Information</h4>
						</div>
						<div class="card-body">
							<div class="row">											
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Basic Salary :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->basic_salary .' AED' ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Other Allowances :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->other_allowances .' AED' ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Total Salary :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{$data->total_salary .' AED' ?? 'NA'}}</span>
								</div>
							</div>
							<div class="card-header">
								<h4 class="card-title">Increments</h4>
							</div>
							</br>
							@if(isset($data->increments) && count($data->increments) > 0) 
							<div class="table-responsive">
							<table class="vertical-heading">
								<tr>
									<th>Increment Effective Date:</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>
											@if($data->increments[$i]->increament_effective_date != '')
											{{\Carbon\Carbon::parse($data->increments[$i]->increament_effective_date)->format('d M Y') ?? ''}}
											@else
											NA
											@endif
										</td>
									@endfor								
								</tr>
								<tr>
									<th>Basic Salary(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->basic_salary ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Other Allowances(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->other_allowances ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Total Salary(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->total_salary ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Increment Amount(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->increment_amount ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Revised Basic Salary(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->revised_basic_salary ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Revised Other Allowance(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->revised_other_allowance ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Revised Total Salary(AED):</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->revised_total_salary ?? 'NA'}}</td>
									@endfor	
								</tr>
								<tr>
									<th>Request Date:</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>
											@if($data->increments[$i]->created_at != '')
											{{\Carbon\Carbon::parse($data->increments[$i]->created_at)->format('d M Y') ?? ''}}
											@else
											NA
											@endif
										</td>
									@endfor	
								</tr>
								<tr>
									<th>Created By:</th>
									@for ($i = 0; $i < count($data->increments); $i++)
										<td>{{$data->increments[$i]->createdBy->name ?? 'NA'}}</td>
									@endfor	
								</tr>
							</table>
							</div>
							@else
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-12">
									<label for="choices-single-default" class="form-label">There are no details about salary increments added in this system.</label>
								</div>
							</div>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Insurance Policy Information</h4>
						</div>
						<div class="card-body">
							@if(isset($data->insurance) && count($data->insurance) > 0) 
								<div class="table-responsive">
								<table class="vertical-heading">
									<tr>
										<th>Insurance Policy Number:</th>
										@for ($i = 0; $i < count($data->insurance); $i++)
											<td>
												{{$data->insurance[$i]->insurance_policy_number ?? 'NA'}}
											</td>
										@endfor								
									</tr>
									<tr>
										<th>Insurance Card Number:</th>
										@for ($i = 0; $i < count($data->insurance); $i++)
											<td>
												{{$data->insurance[$i]->insurance_card_number ?? 'NA'}}
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Insurance Policy Start Date:</th>
										@for ($i = 0; $i < count($data->insurance); $i++)
											<td>
												@if($data->insurance[$i]->insurance_policy_start_date != '')
												{{\Carbon\Carbon::parse($data->insurance[$i]->insurance_policy_start_date)->format('d M Y') ?? ''}}
												@else
												NA
												@endif
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Insurance Policy End Date:</th>
										@for ($i = 0; $i < count($data->insurance); $i++)
											<td>
												@if($data->insurance[$i]->created_at != '')
												{{\Carbon\Carbon::parse($data->insurance[$i]->created_at)->format('d M Y') ?? ''}}
												@else
												NA
												@endif
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Request Date:</th>
										@for ($i = 0; $i < count($data->insurance); $i++)
											<td>
												@if($data->insurance[$i]->created_at != '')
												{{\Carbon\Carbon::parse($data->insurance[$i]->created_at)->format('d M Y') ?? ''}}
												@else
												NA
												@endif
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Created By:</th>
										@for ($i = 0; $i < count($data->insurance); $i++)
											<td>{{$data->insurance[$i]->createdBy->name ?? 'NA'}}</td>
										@endfor	
									</tr>
								</table>
								</div>
							@else
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<label for="choices-single-default" class="form-label">There are no details about insurance policy added in this system.</label>
									</div>
								</div>
							@endif
						</div>
					</div>
                </div>
				<div class="col-xxl-6 col-lg-6 col-md-6">
				<div class="card">
						<div class="card-header">
							<h4 class="card-title">Ticket Allowance Information</h4>
						</div>
						<div class="card-body">
							@if(isset($data->ticket) && count($data->ticket) > 0) 
								<div class="table-responsive">
								<table class="vertical-heading">
									<tr>
										<th>Ticket Allowance Eligibility Year:</th>
										@for ($i = 0; $i < count($data->ticket); $i++)
											<td>
												{{$data->ticket[$i]->eligibility_year ?? 'NA'}}
											</td>
										@endfor								
									</tr>
									<tr>
										<th>Ticket Allowance Eligibility Date:</th>
										@for ($i = 0; $i < count($data->ticket); $i++)
											<td>
												@if($data->ticket[$i]->eligibility_date != '')
												{{\Carbon\Carbon::parse($data->ticket[$i]->eligibility_date)->format('d M Y') ?? ''}}
												@else
												NA
												@endif
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Ticket Allowance PO Year:</th>
										@for ($i = 0; $i < count($data->ticket); $i++)
											<td>{{$data->ticket[$i]->po_year ?? 'NA'}}</td>
										@endfor	
									</tr>
									<tr>
										<th>Ticket Allowance PO Number:</th>
										@for ($i = 0; $i < count($data->ticket); $i++)
											<td>{{$data->ticket[$i]->po_number ?? 'NA'}}</td>
										@endfor	
									</tr>
									<tr>
										<th>Request Date:</th>
										@for ($i = 0; $i < count($data->ticket); $i++)
											<td>
												@if($data->ticket[$i]->created_at != '')
												{{\Carbon\Carbon::parse($data->ticket[$i]->created_at)->format('d M Y') ?? ''}}
												@else
												NA
												@endif
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Created By:</th>
										@for ($i = 0; $i < count($data->ticket); $i++)
											<td>{{$data->ticket[$i]->createdBy->name ?? 'NA'}}</td>
										@endfor	
									</tr>
								</table>
								</div>
							@else
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<label for="choices-single-default" class="form-label">There are no details about ticket allowance added in this system.</label>
									</div>
								</div>
							@endif
						</div>
					</div>
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Birthday Gift PO Information</h4>
						</div>
						<div class="card-body">
							@if(isset($data->birthdayGift) && count($data->birthdayGift) > 0) 
								<div class="table-responsive">
								<table class="vertical-heading">
									<tr>
										<th>Birthday Gift PO For Year:</th>
										@for ($i = 0; $i < count($data->birthdayGift); $i++)
											<td>
												{{$data->birthdayGift[$i]->po_year ?? 'NA'}}
											</td>
										@endfor								
									</tr>
									<tr>
										<th>Birthday Gift PO Number:</th>
										@for ($i = 0; $i < count($data->birthdayGift); $i++)
											<td>
												{{$data->birthdayGift[$i]->po_number ?? 'NA'}}
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Request Date:</th>
										@for ($i = 0; $i < count($data->birthdayGift); $i++)
											<td>
												@if($data->birthdayGift[$i]->created_at != '')
												{{\Carbon\Carbon::parse($data->birthdayGift[$i]->created_at)->format('d M Y') ?? ''}}
												@else
												NA
												@endif
											</td>
										@endfor	
									</tr>
									<tr>
										<th>Created By:</th>
										@for ($i = 0; $i < count($data->birthdayGift); $i++)
											<td>{{$data->birthdayGift[$i]->createdBy->name ?? 'NA'}}</td>
										@endfor	
									</tr>
								</table>
								</div>
							@else
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-12">
										<label for="choices-single-default" class="form-label">There are no details about birthday gift PO added in this system.</label>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="off-boarding-{{$data->id}}">
			<div class="row">
                <div class="col-xxl-12 col-lg-12 col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Off Boarding Information</h4>
						</div>
						<div class="card-body">
							<div class="row">											
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Leaving Type :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span class="texttransform">{{ $data->leaving_type ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label">Leaving Reason :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->leaving_reason ?? 'NA' }}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Notice Period to Serve :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span class="texttransform">{{$data->notice_period_to_serve ?? 'NA'}}</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Notice Period Duration :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>{{ $data->notice_period_duration ?? 'NA' }} @if($data->notice_period_duration != '') Days @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Last Working Day :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->last_working_day != ''){{\Carbon\Carbon::parse($data->last_working_day)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Visa Cancellation Received Date :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->visa_cancellation_received_date != ''){{\Carbon\Carbon::parse($data->visa_cancellation_received_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Change Status Date/Exit UAE Date :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span>@if($data->change_status_or_exit_UAE_date != ''){{\Carbon\Carbon::parse($data->change_status_or_exit_UAE_date)->format('d M Y')}} @else NA @endif</span>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<label for="choices-single-default" class="form-label"> Insurance Cancellation Done :</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-12">
									<span class="texttransform">{{ $data->insurance_cancellation_done ?? 'NA' }}</span>
								</div>								
							</div>
						</div>
					</div>
                </div>
			</div>
		</div>
	</div>
</div>
@push('scripts')
<script type="text/javascript">
	$('.btn-verify-docs').click(function (e) {
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
	                    _token: '{{ csrf_token() }}'
	                },
	                success: function (data) {							
	                    if(data == 'success') {
	                        window.location.reload();
	                        alertify.success(status + " Successfully")
	                    }
	                    else if(data == 'error') {
							window.location.reload();
							alertify.error("Can't Verify, It was verified already..")
	                    }
	                }
	            });
	        }
	    }).set({title:"Confirmation"})
	})
	$('.btn-verify-personalinfo').click(function (e) {
	    var id = $(this).attr('data-id');
	    let url = '{{ route('personal-info.verified') }}';
	    var confirm = alertify.confirm('Are you sure you verified this candidate personal information ?',function (e) {
	        if (e) {
	            $.ajax({
	                type: "POST",
	                url: url,
	                dataType: "json",
	                data: {
	                    id: id,
	                    _token: '{{ csrf_token() }}'
	                },
	                success: function (data) {							
	                    if(data == 'success') {
	                        window.location.reload();
	                        alertify.success(status + " Successfully")
	                    }
	                    else if(data == 'error') {
							window.location.reload();
	                        alertify.error(status + "Can't verify! It has already been verified.")
	                    }
	                }
	            });
	        }
	    }).set({title:"Confirmation"})
	})
	$('.btn-verify-offer-letter-sign').click(function (e) {
	    var id = $(this).attr('data-id');
	    let url = '{{ route('offer_letter_sign.verified') }}';
	    var confirm = alertify.confirm('Are you sure you verified this candidate offer letter signature ?',function (e) {
	        if (e) {
	            $.ajax({
	                type: "POST",
	                url: url,
	                dataType: "json",
	                data: {
	                    id: id,
	                    _token: '{{ csrf_token() }}'
	                },
	                success: function (data) {	console.log('hlo'); console.log(data);					
	                    if(data == 'success') {
	                        window.location.reload();
	                        alertify.success(status + " Successfully")
	                    }
	                    else if(data == 'error') {
							window.location.reload();
	                        alertify.error("Can't verify! It has already been verified")
	                    }
	                }
	            });
	        }
	    }).set({title:"Confirmation"})
	})
</script>
@endpush
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
