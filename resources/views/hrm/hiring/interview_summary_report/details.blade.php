<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    @media (max-width: 575) {
        .col-lg-4.col-md-3.col-sm-6.col-12 span {
            padding-bottom: 20px;
            /* Adjust the value as needed */
            display: block;
            /* Ensure the span is a block-level element */
        }
    }
</style>
@canany(['view-all-hiring-request-details','view-hiring-request-details-of-current-user'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-details-of-current-user']);
@endphp
@if ($hasPermission)
<div class="card-body">
    <div class="row">
        <div class="col-xxl-12 col-lg-12 col-md-12">
            <!-- <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-1 col-md-3 col-sm-6 col-12">
                            <label for="choices-single-default" class="form-label"> Request Date :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                            <span>{{ $data->request_date ?? '' }}</span>
                        </div>
                        <div class="col-lg-1 col-md-3 col-sm-6 col-12">
                            <label for="choices-single-default" class="form-label"> UUID :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                            <span style="color:#fd625e;"><strong>{{$data->uuid ?? ''}}</strong></span>
                        </div>
                        <div class="col-lg-1 col-md-3 col-sm-6 col-12">
                            <label for="choices-single-default" class="form-label"> Current Status :</label>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                            @if($data->current_status == 'Rejected')
                            <label class="badge badge-soft-danger">{{ $data->current_status ?? '' }}</label>
                            @elseif($data->current_status == 'Approved')
                            <label class="badge badge-soft-success">{{ $data->current_status ?? '' }}</label>
                            @else
                            <label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label>
                            @endif
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
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
                                <span>{{ $data->candidate_name ?? '' }}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Current Status  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span>
                                    @if($data->candidate_current_status == 'Rejected')
                                        <label class="badge badge-soft-danger">{{ $data->candidate_current_status ?? '' }}</label>
                                    @elseif($data->candidate_current_status == 'Candidate Selected And Approved' OR $data->candidate_current_status == 'Candidate Selected And Hiring Request Closed')  
                                        <label class="badge badge-soft-success">{{ $data->candidate_current_status ?? '' }}</label>  
                                    @else
                                        <label class="badge badge-soft-info">{{ $data->candidate_current_status ?? '' }}</label>
                                    @endif
                                </span>
                            </div>                           
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Nationality  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span>{{ $data->nationalities->name ?? '' }}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Gender  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span>{{ $data->genderName->name ?? '' }}</span>
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
                                <span class="texttransform">{{ $data->rate_dress_appearance ?? '' }}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Body Language  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->rate_body_language_appearance ?? '' }}</span>
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
                                <span class="texttransform">{{\Carbon\Carbon::parse($data->date_of_telephonic_interview)->format('d M Y')}}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">
                                    @if(isset($data->telephonicInterviewers))
                                    @if(count($data->telephonicInterviewers) > 0)
                                    @foreach($data->telephonicInterviewers as $telephonicInterviewers)
                                    {{ $telephonicInterviewers->interviewerName->name ?? '' }},</br>
                                    @endforeach
                                    @endif
                                    @endif
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Interview Summary  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->telephonic_interview ?? '' }}</span>
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
                                <span class="texttransform">{{\Carbon\Carbon::parse($data->date_of_first_round)->format('d M Y')}}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">
                                    @if(isset($data->firstRoundInterviewers))
                                    @if(count($data->firstRoundInterviewers) > 0)
                                    @foreach($data->firstRoundInterviewers as $firstRoundInterviewers)
                                    {{ $firstRoundInterviewers->interviewerName->name ?? '' }},</br>
                                    @endforeach
                                    @endif
                                    @endif
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Interview Summary  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->first_round ?? '' }}</span>
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
                                <span class="texttransform">{{\Carbon\Carbon::parse($data->date_of_second_round)->format('d M Y')}}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">
                                    @if(isset($data->secondRoundInterviewers))
                                    @if(count($data->secondRoundInterviewers) > 0)
                                    @foreach($data->secondRoundInterviewers as $secondRoundInterviewers)
                                    {{ $secondRoundInterviewers->interviewerName->name ?? '' }},</br>
                                    @endforeach
                                    @endif
                                    @endif
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Interview Summary  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->second_round ?? '' }}</span>
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
                                <span class="texttransform">{{\Carbon\Carbon::parse($data->date_of_third_round)->format('d M Y')}}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">
                                    @if(isset($data->thirdRoundInterviewers))
                                    @if(count($data->thirdRoundInterviewers) > 0)
                                    @foreach($data->thirdRoundInterviewers as $thirdRoundInterviewers)
                                    {{ $thirdRoundInterviewers->interviewerName->name ?? '' }},</br>
                                    @endforeach
                                    @endif
                                    @endif
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Interview Summary  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->third_round ?? '' }}</span>
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
                                <span class="texttransform">{{\Carbon\Carbon::parse($data->date_of_forth_round)->format('d M Y')}}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">
                                    @if(isset($data->forthRoundInterviewers))
                                    @if(count($data->forthRoundInterviewers) > 0)
                                    @foreach($data->forthRoundInterviewers as $forthRoundInterviewers)
                                    {{ $forthRoundInterviewers->interviewerName->name ?? '' }},</br>
                                    @endforeach
                                    @endif
                                    @endif
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Interview Summary  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->forth_round ?? '' }}</span>
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
                                <span class="texttransform">{{\Carbon\Carbon::parse($data->date_of_fifth_round)->format('d M Y')}}</span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Name Of Interviewers  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">
                                    @if(isset($data->fifthRoundInterviewers))
                                    @if(count($data->fifthRoundInterviewers) > 0)
                                    @foreach($data->fifthRoundInterviewers as $fifthRoundInterviewers)
                                    {{ $fifthRoundInterviewers->interviewerName->name ?? '' }},</br>
                                    @endforeach
                                    @endif
                                    @endif
                                </span>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                <label for="choices-single-default" class="form-label"> Interview Summary  :</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->fifth_round ?? '' }}</span>
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
                        <h4 class="card-title">Final Evaluation Of Candidate</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
                                <span class="texttransform">{{ $data->final_evaluation_of_candidate ?? '' }}</span>
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
                    <h4 class="card-title"><center>Approvals By</center></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <center><h4 class="card-title">HR Manager</h4></center>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Name :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            {{$data->hrManager->name ?? ''}}
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Status :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                        <label class="badge texttransform @if($data->action_by_hr_manager =='pending') badge-soft-info 
                                        @elseif($data->action_by_hr_manager =='approved') badge-soft-success 
                                        @else badge-soft-danger @endif">{{$data->action_by_hr_manager ?? ''}}</label>
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Date & Time :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            {{$data->hr_manager_action_at ?? ''}}
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Comments :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            {{$data->comments_by_hr_manager ?? ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <center><h4 class="card-title">Division Head</h4></center>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Name :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            {{$data->divisionHeadName->name ?? ''}}
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Status :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            <label class="badge texttransform @if($data->action_by_division_head =='pending') badge-soft-info 
                                        @elseif($data->action_by_division_head =='approved') badge-soft-success 
                                        @else badge-soft-danger @endif">{{$data->action_by_division_head ?? ''}}</label>
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Date & Time :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            {{$data->division_head_action_at ?? ''}}
                                        </div>
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            Comments :
                                        </div>
                                        <div class="col-lg-10 col-md-12 col-sm-12">
                                            {{$data->comments_by_division_head ?? ''}}
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
@endif
@endcanany