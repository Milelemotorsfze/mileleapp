<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
    @media (max-width: 575) {
        .col-lg-4.col-md-3.col-sm-6.col-12 span {
            padding-bottom: 20px;
            display: block;
        }
    }
</style>
@canany(['view-interview-summary-report-details'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details']);
@endphp
@if ($hasPermission)
<div class="card-body">
    <div class="portfolio">
        <ul class="nav nav-pills nav-fill" id="my-tab">      
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="pill" href="#interview-summary"> Interview Summary Report</a>
            </li>
            @if(isset($data->candidateDetails))
            @canany(['view-interview-summary-report-details'])
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details']);
            @endphp
            @if ($hasPermission)
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#personal-info"> Personal Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="pill" href="#documents"> Documents</a>
            </li>
            @endcanany
            @endif
            @endif
        </ul>
    </div>
    </br>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="interview-summary">
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
        @if(isset($data->candidateDetails))
        @canany(['view-interview-summary-report-details'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details']);
        @endphp 
        @if ($hasPermission)    
        <div class="tab-pane fade show" id="personal-info">
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
                                        <label for="choices-single-default" class="form-label"> First Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->first_name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Last Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->last_name ?? '' }}</span>
                                    </div>                           
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Father’s Full Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->name_of_father ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Mother’s Full Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->name_of_mother ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Marital Status  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->maritalStatus->name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Passport Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->passport_number ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Passport Expiry Date  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>@if($data->candidateDetails->passport_expiry_date != ''){{\Carbon\Carbon::parse($data->candidateDetails->passport_expiry_date)->format('d M Y')}} @endif</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Educational Qualification  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->educational_qualification ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Year of Completion  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->year_of_completion ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Religion  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->religionName->name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Date Of Birth  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{\Carbon\Carbon::parse($data->candidateDetails->dob)->format('d M Y') ?? ''}}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Spoken Languages  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>
                                            @if(isset($data->candidateDetails->candidateLanguages))
                                            @foreach($data->candidateDetails->candidateLanguages as $language)
                                            {{ $language->language->name ?? '' }} ,
                                            @endforeach
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Dependents</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Spouse Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->spouse_name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Spouse Passport Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->spouse_passport_number ?? '' }}</span>
                                    </div>                           
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Spouse Passport Expiry Date  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{\Carbon\Carbon::parse($data->candidateDetails->spouse_passport_expiry_date)->format('d M Y') ?? ''}}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Spouse Date Of Birth  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{\Carbon\Carbon::parse($data->candidateDetails->spouse_dob)->format('d M Y') ?? ''}}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Spouse Nationality  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->spouseNationality->name ?? '' }}</span>
                                    </div>
                                </div>
                                @if(isset($data->candidateDetails->candidateChildren))
                                <div class="card-header">
                                <h4 class="card-title">Children</h4>
                                </div>
                                </br>
                                @foreach($data->candidateDetails->candidateChildren as $children)
                                <div class="row" style="border:1px solid #e9e9ef; margin-bottom:10px;">
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Child Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $children->child_name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Child Passport Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $children->child_passport_number ?? '' }}</span>
                                    </div>                           
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Child Passport Expiry Date  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span> @if($children->child_passport_expiry_date != '') {{\Carbon\Carbon::parse($children->child_passport_expiry_date)->format('d M Y') }} @endif</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Child Date Of Birth  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{\Carbon\Carbon::parse($children->child_dob)->format('d M Y') ?? ''}}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Child Nationality  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $children->childNationality->name ?? '' }}</span>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
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
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Residence Telephone Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->residence_telephone_number ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Mobile Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->contact_number ?? '' }}</span>
                                    </div>                           
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Personal Email Address  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->personal_email_address ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Address in UAE  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $data->candidateDetails->address_uae ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Contact in case of Emergency (UAE)</h4>
                            </div>
                            <div class="card-body">
                            @if(isset($data->candidateDetails->emergencyContactUAE))
                            @foreach($data->candidateDetails->emergencyContactUAE as $contactUAE)                                          
                                <div class="row" style="border:1px solid #e9e9ef; margin-bottom:10px;">
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactUAE->name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Relation  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactUAE->relationName->name ?? '' }}</span>
                                    </div>                           
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Email  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactUAE->email_address ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Contact Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactUAE->contact_number ?? '' }}</span>
                                    </div>
                                    @if($contactUAE->alternative_contact_number != '')
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Alternative Contact Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactUAE->alternative_contact_number ?? '' }}</span>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Contact in case of Emergency (Home Country)</h4>
                            </div>
                            <div class="card-body">
                            @if(isset($data->candidateDetails->emergencyContactHomeCountry))
                            @foreach($data->candidateDetails->emergencyContactHomeCountry as $contactHomeCountry)                                          
                                <div class="row" style="border:1px solid #e9e9ef; margin-bottom:10px;">
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Name  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactHomeCountry->name ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Relation  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactHomeCountry->contact_number ?? '' }}</span>
                                    </div>                           
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Email  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactHomeCountry->email_address ?? '' }}</span>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Contact Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactHomeCountry->contact_number ?? '' }}</span>
                                    </div>
                                    @if($contactHomeCountry->alternative_contact_number != '')
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Alternative Contact Number  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactHomeCountry->alternative_contact_number ?? '' }}</span>
                                    </div>
                                    @endif
                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                        <label for="choices-single-default" class="form-label"> Home Country Address  :</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                        <span>{{ $contactHomeCountry->home_country_address ?? '' }}</span>
                                    </div>
                                </div>
                            @endforeach
                            @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show" id="documents">
            <div class="card">
                <div class="card-header">
                    <div class="card-title fw-bold">Documents</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                        @if($data->candidateDetails->image_path)
                        <div class="row">
                            <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                <h6 class="fw-bold text-center mb-1" style="float:left">Passport Size Photograph</h6>
                            </div>
                            <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                <a href="{{ url('hrm/employee/photo/' . $data->candidateDetails->image_path) }}" target="_blank">
                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                </a>
                                <a href="{{ url('hrm/employee/photo/' . $data->candidateDetails->image_path) }}" download>
                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                </a>
                            </div>
                        </div>
                        <iframe src="{{ url('hrm/employee/photo/' . $data->candidateDetails->image_path) }}" alt="Passport Size Photograph" style="height:400px;"></iframe>
                                

                            @endif
                        </div>
                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                            @if($data->candidateDetails->resume)
                                <div class="row">
                                    <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                        <h6 class="fw-bold text-center mb-1" style="float:left">Resume</h6>
                                    </div>
                                    <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                        <a href="{{ url('hrm/employee/resume/' . $data->candidateDetails->resume) }}" target="_blank">
                                            <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                        </a>
                                        <a href="{{ url('hrm/employee/resume/' . $data->candidateDetails->resume) }}" download>
                                            <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                        </a>
                                    </div>
                                </div>
                                <iframe src="{{ url('hrm/employee/resume/' . $data->candidateDetails->resume) }}" alt="Resume" style="height:400px;"></iframe>
                            @endif
                        </div>
                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                            @if($data->candidateDetails->visa)
                                <div class="row">
                                    <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                        <h6 class="fw-bold text-center mb-1" style="float:left">Visa</h6>
                                    </div>
                                    <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                        <a href="{{ url('hrm/employee/visa/' . $data->candidateDetails->visa) }}" target="_blank">
                                            <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                        </a>
                                        <a href="{{ url('hrm/employee/visa/' . $data->candidateDetails->visa) }}" download>
                                            <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                        </a>
                                    </div>
                                </div>
                                <iframe src="{{ url('hrm/employee/visa/' . $data->candidateDetails->visa) }}" alt="Visa" style="height:400px;"></iframe>
                            @endif
                        </div>
                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                            @if($data->candidateDetails->emirates_id_file)
                                <div class="row">
                                    <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                        <h6 class="fw-bold text-center mb-1" style="float:left">Emirates ID</h6>
                                    </div>
                                    <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                        <a href="{{ url('hrm/employee/emirates_id/' . $data->candidateDetails->emirates_id_file) }}" target="_blank">
                                            <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                        </a>
                                        <a href="{{ url('hrm/employee/emirates_id/' . $data->candidateDetails->emirates_id_file) }}" download>
                                            <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                        </a>
                                    </div>
                                </div>
                                <iframe src="{{ url('hrm/employee/emirates_id/' . $data->candidateDetails->emirates_id_file) }}" alt="Emirates ID" style="height:400px;"></iframe>
                            @endif
                        </div>
                    </div>
                    @if($data->candidateDetails->candidatePassport->count() > 0)
                        <div class="row m-3">
                            <h6 class="fw-bold text-center mb-13">Passport (First & Second page)</h6>
                            @foreach($data->candidateDetails->candidatePassport as $document)
                            <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                <a href="{{ url('hrm/employee/passport/' . $document->document_path) }}" target="_blank">
                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                </a>
                                <a href="{{ url('hrm/employee/passport/' . $document->document_path) }}" download>
                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                </a>
                                <iframe src="{{ url('hrm/employee/passport/' . $document->document_path) }}" alt="Passport (First & Second page)" style="height:400px;"></iframe>                                 
                            </div>
                            @endforeach
                        </div>
                    @endif
                    @if($data->candidateDetails->candidateNationalId->count() > 0)
                        <div class="row m-3">
                            <h6 class="fw-bold text-center mb-13">National ID (First & Second page)</h6>
                                @foreach($data->candidateDetails->candidateNationalId as $document)
                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                    <a href="{{ url('hrm/employee/national_id/' . $document->document_path) }}" target="_blank">
                                        <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                    </a>
                                    <a href="{{ url('hrm/employee/national_id/' . $document->document_path) }}" download>
                                        <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                    </a>
                                    <iframe src="{{ url('hrm/employee/national_id/' . $document->document_path) }}" alt="National ID (First & Second page)" style="height:400px;"></iframe>                                  
                                </div>
                                @endforeach
                        </div>
                    @endif
                    @if($data->candidateDetails->candidateEduDocs->count() > 0)
                        <div class="row m-3">
                            <h6 class="fw-bold text-center mb-13">Attested Educational Documents</h6>
                                @foreach($data->candidateDetails->candidateEduDocs as $document)
                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                    <a href="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" target="_blank">
                                        <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                    </a>
                                    <a href="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" download>
                                        <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                    </a>
                                    <iframe src="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" alt="Attested Educational Documents" style="height:400px;"></iframe>                                  
                                </div>
                                @endforeach
                        </div>
                    @endif
                    @if($data->candidateDetails->candidateProDipCerti->count() > 0)
                        <div class="row m-3">
                            <h6 class="fw-bold text-center mb-13">Attested Professional Diplomas / Certificates</h6>
                                @foreach($data->candidateDetails->candidateProDipCerti as $document)
                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                    <a href="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" target="_blank">
                                        <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                    </a>
                                    <a href="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" download>
                                        <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                    </a>
                                    <iframe src="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" alt="Attested Professional Diplomas / Certificates" style="height:400px;"></iframe>                                   
                                </div>
                                @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endcanany
        @endif
        @endif
    </div>    
</div>
@endif
@endcanany