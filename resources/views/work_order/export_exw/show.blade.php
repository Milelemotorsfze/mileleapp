@extends('layouts.table')
<style>
    .comment {
            margin-bottom: 20px;
        }
        .reply {
            margin-left: 30px; /* Indent replies by 40px */
            margin-top: 10px;
        }
        .reply-button {
            margin-top: 10px;
        }
        .replies {
            margin-left: 30px; /* Indent nested replies by 40px */
        }
	.texttransform {
	text-transform: capitalize;
	}
	/* element.style {
	} */
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	background-color: #c1e1fb !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	color: black!important;
	background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important;
	}
	.nav-link:focus{
	color: black!important;
	}
	.nav-link:hover {
	color: black!important;
	}
    .form-label {
        font-size:12px!important;
    }
    .data-font {
        font-size:12px!important;
    }
    .table>:not(caption)>*>* {
		padding: .3rem .3rem!important;
		-webkit-box-shadow: inset 0 0 0 0px var(--bs-table-accent-bg)!important;
	}
    table {
        /* border-collapse: collapse; */
        width: 100%;
    }
    th {
		font-size:12px!important;
		/* font-size:15px!important; */
	}
	td {
		font-size:12px!important;
		/* font-size:15px!important; */
	}
    /* table.dataTable {
        border-collapse: none!important;
    } */
    .custom-border-top {
        /* border-bottom: 1px solid #b3b3b3 !important; */
        border-top: 1px solid #b3b3b3 !important;
    }

</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','export-cnf-wo-details','local-sale-wo-details']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title form-label"> Work Order Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first form-label" href="{{ route('work-order.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first form-label" href="{{ route('work-order.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	<a  class="btn btn-sm btn-info float-end form-label" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
<div class="card-body">
	<div class="tab-content">
		<div class="tab-pane fade show active" id="requests">
			<br>
			<div class="card">
				<div class="card-header" style="background-color:#e8f3fd;">
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> SO Number </strong></label></center>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><span class="data-font">{{ $workOrder->so_number ?? '' }}</span></center>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> WO Number</strong></label></center>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><span class="data-font">{{ $workOrder->wo_number ?? '' }}</span></center>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> Date</strong></label></center>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><span class="data-font">@if($workOrder->date != ''){{\Carbon\Carbon::parse($workOrder->date)->format('d M Y') ?? ''}}@endif</span></center>
							</div>
						</div>
                        @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <center><label for="choices-single-default" class="form-label"> <strong> Batch </strong></label></center>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <center><span class="data-font">{{ $workOrder->batch ?? '' }}</span></center>
                                </div>
                            </div>
                        @endif
					</div>
				</div>
				<div class="card-body">
                    <div class="portfolio">
                        <ul class="nav nav-pills nav-fill" id="my-tab">
                            <li class="nav-item">
                                <a class="nav-link active form-label" data-bs-toggle="pill" href="#personal-info-{{$workOrder->id}}"> General Info</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="pill" href="#contact-info-{{$workOrder->id}}"> Contact Info</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#visa-info-{{$workOrder->id}}"> Vehicles & Addons</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#compensation-benefits-{{$workOrder->id}}"> Comments Section</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#off-boarding-{{$workOrder->id}}"> Data History</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="pill" href="#interview-summary-{{$workOrder->id}}"> Interview Summary Report</a>
                            </li> -->
                            @if(isset($workOrder) && $workOrder->documents_form_submit_at != NULL)
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-interview-summary-report-details','requestedby-view-interview-summary','organizedby-view-interview-summary']);
                            @endphp
                            @if ($hasPermission)
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="pill" href="#documents-{{$workOrder->id}}"> Documents</a>
                            </li>
                            @if($workOrder->offer_letter_send_at != '')
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="pill" href="#job-offer-letter-{{$workOrder->id}}"> Job Offer Letter</a>
                            </li>
                            @endif
                            
                            @endif
                            @endif
                        </ul>
                    </div>
                    </br>
                    <div class="tab-content">
                        <div class="tab-pane fade show" id="interview-summary-{{$workOrder->id}}">
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
                                                        <span>{{ $workOrder->candidate_name ?? 'NA' }}</span>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Current Status  :</label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span>
                                                        @if($workOrder->candidate_current_status == 'Rejected')
                                                        <label class="badge badge-soft-danger">{{ $workOrder->candidate_current_status ?? 'NA' }}</label>
                                                        @elseif($workOrder->candidate_current_status == 'Candidate Selected And Approved' OR $workOrder->candidate_current_status == 'Candidate Selected And Hiring Request Closed')  
                                                        <label class="badge badge-soft-success">{{ $workOrder->candidate_current_status ?? 'NA' }}</label>  
                                                        @else
                                                        <label class="badge badge-soft-info">{{ $workOrder->candidate_current_status ?? 'NA' }}</label>
                                                        @endif
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Nationality  :</label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span>{{ $workOrder->nationalities->name ?? 'NA' }}</span>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Gender  :</label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span>{{ $workOrder->genderName->name ?? 'NA' }}</span>
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
                                                        <span class="texttransform">{{ $workOrder->rate_dress_appearance ?? 'NA' }}</span>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Body Language  :</label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span class="texttransform">{{ $workOrder->rate_body_language_appearance ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($workOrder->date_of_telephonic_interview)
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
                                                        @if($workOrder->date_of_telephonic_interview != '')
                                                        {{\Carbon\Carbon::parse($workOrder->date_of_telephonic_interview)->format('d M Y')}}
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
                                                        @if(isset($workOrder->telephonicInterviewers))
                                                        @if(count($workOrder->telephonicInterviewers) > 0)
                                                        @foreach($workOrder->telephonicInterviewers as $telephonicInterviewers)
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
                                                        <span class="texttransform">{{ $workOrder->telephonic_interview ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->date_of_first_round)
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
                                                        @if($workOrder->date_of_first_round != '')
                                                        {{\Carbon\Carbon::parse($workOrder->date_of_first_round)->format('d M Y')}}
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
                                                        @if(isset($workOrder->firstRoundInterviewers))
                                                        @if(count($workOrder->firstRoundInterviewers) > 0)
                                                        @foreach($workOrder->firstRoundInterviewers as $firstRoundInterviewers)
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
                                                        <span class="texttransform">{{ $workOrder->first_round ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->date_of_second_round)
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
                                                        @if($workOrder->date_of_second_round != '')
                                                        {{\Carbon\Carbon::parse($workOrder->date_of_second_round)->format('d M Y')}}
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
                                                        @if(isset($workOrder->secondRoundInterviewers))
                                                        @if(count($workOrder->secondRoundInterviewers) > 0)
                                                        @foreach($workOrder->secondRoundInterviewers as $secondRoundInterviewers)
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
                                                        <span class="texttransform">{{ $workOrder->second_round ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->date_of_third_round)
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
                                                        @if($workOrder->date_of_third_round != '')
                                                        {{\Carbon\Carbon::parse($workOrder->date_of_third_round)->format('d M Y')}}
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
                                                        @if(isset($workOrder->thirdRoundInterviewers))
                                                        @if(count($workOrder->thirdRoundInterviewers) > 0)
                                                        @foreach($workOrder->thirdRoundInterviewers as $thirdRoundInterviewers)
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
                                                        <span class="texttransform">{{ $workOrder->third_round ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->date_of_forth_round)
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
                                                        @if($workOrder->date_of_forth_round != '')
                                                        {{\Carbon\Carbon::parse($workOrder->date_of_forth_round)->format('d M Y')}}
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
                                                        @if(isset($workOrder->forthRoundInterviewers))
                                                        @if(count($workOrder->forthRoundInterviewers) > 0)
                                                        @foreach($workOrder->forthRoundInterviewers as $forthRoundInterviewers)
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
                                                        <span class="texttransform">{{ $workOrder->forth_round ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->date_of_fifth_round)
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
                                                        @if($workOrder->date_of_fifth_round != '')
                                                        {{\Carbon\Carbon::parse($workOrder->date_of_fifth_round)->format('d M Y')}}
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
                                                        @if(isset($workOrder->fifthRoundInterviewers))
                                                        @if(count($workOrder->fifthRoundInterviewers) > 0)
                                                        @foreach($workOrder->fifthRoundInterviewers as $fifthRoundInterviewers)
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
                                                        <span class="texttransform">{{ $workOrder->fifth_round ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->final_evaluation_of_candidate)
                                    <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                                                        <h4 class="card-title">Final Evaluation Of Candidate</h4>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                                        @if($workOrder->candidate_selected == 'no')
                                                        <label class="badge badge-soft-danger">Not Selected</label>
                                                        @elseif($workOrder->candidate_selected == 'yes')  
                                                        <label class="badge badge-soft-success">Selected</label> 
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-6 col-12">
                                                        <span class="texttransform">{{ $workOrder->final_evaluation_of_candidate ?? 'NA' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                    @if($workOrder->resume_file_name)
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <h4 class="card-title">Resume</h4>
                                                </div>
                                                <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                    <button style="float:right;" type="button" class="btn btn-sm btn-info mt-3 ">
                                                    <a href="{{ url('resume/' . $workOrder->resume_file_name) }}" download class="text-white">
                                                    Download
                                                    </a>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <iframe src="{{ url('resume/' . $workOrder->resume_file_name) }}" alt="Resume" style="height:1000;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($workOrder->hr_manager_id)
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
                                                                    {{$workOrder->hrManager->name ?? 'NA'}}
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    Status :
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <label class="badge texttransform @if($workOrder->action_by_hr_manager =='pending') badge-soft-info 
                                                                        @elseif($workOrder->action_by_hr_manager =='approved') badge-soft-success 
                                                                        @else badge-soft-danger @endif">{{$workOrder->action_by_hr_manager ?? 'NA'}}</label>
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    Date & Time :
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    @if($workOrder->hr_manager_action_at != '')
                                                                    {{ \Carbon\Carbon::parse($workOrder->hr_manager_action_at)->format('d M Y, H:i:s') }}
                                                                    @else
                                                                    NA
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    Comments :
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    {{$workOrder->comments_by_hr_manager ?? 'NA'}}
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
                                                                    {{$workOrder->divisionHeadName->name ?? 'NA'}}
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    Status :
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    <label class="badge texttransform @if($workOrder->action_by_division_head =='pending') badge-soft-info 
                                                                        @elseif($workOrder->action_by_division_head =='approved') badge-soft-success 
                                                                        @else badge-soft-danger @endif">{{$workOrder->action_by_division_head ?? 'NA'}}</label>
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    Date & Time :
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    @if($workOrder->division_head_action_at != '')
                                                                    {{ \Carbon\Carbon::parse($workOrder->division_head_action_at)->format('d M Y, H:i:s') }}
                                                                    @else 
                                                                NA
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-2 col-md-12 col-sm-12">
                                                                    Comments :
                                                                </div>
                                                                <div class="col-lg-10 col-md-12 col-sm-12">
                                                                    {{$workOrder->comments_by_division_head ?? 'NA'}}
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
                                <div class="modal fade" id="send-personal-info-form-{{$workOrder->id}}"
                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog ">
                                        <div class="modal-content">
                                            <form method="POST" action="{{route('personal-info.send-email')}}" id="send_email_{{$workOrder->id}}">
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
                                                                        <input type="text" name="id" value="{{$workOrder->id}}" hidden>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                                        <label for="email" class="form-label font-size-13">{{ __('Comments send to candidate') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
                                                                        <textarea rows="5" name="comment"  id="comments_{{$workOrder->id}}" class="form-control" required
                                                                            placeholder="Comments send to candidate" value=""></textarea>																		
                                                                    </div>
                                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                                        <label for="email" class="form-label font-size-13">{{ __('Email') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
                                                                        <input name="email" id="email_{{$workOrder->id}}" class="form-control" required
                                                                            placeholder="Enter Candidate Email" value="@if($workOrder->email){{$workOrder->email}}@endif">																		
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary send-email"
                                                        data-id="{{ $workOrder->id }}" data-status="final">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="documents-{{$workOrder->id}}">
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
                                            @if ($hasPermission && $workOrder->documents_verified_at == NULL && $workOrder->documents_form_send_at != NULL && $workOrder->documents_form_submit_at != NULL)
                                            <!-- && $workOrder->documents_form_send_at < $workOrder->documents_form_submit_at -->
                                            <button style="margin-top:2px; margin-right:2px; margin-bottom:2px; float:right" title="Verified" type="button" class="btn btn-info btn-sm btn-verify-docs"  data-bs-toggle="modal"
                                                data-bs-target="#verify-docs-{{$workOrder->id}}" data-id="{{$workOrder->id}}">
                                            <i class="fa fa-check" aria-hidden="true"></i> Verified Documents
                                            </button>
                                            @endif
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['send-candidate-documents-request-form']);
                                            @endphp
                                            @if ($hasPermission && $workOrder->documents_verified_at == NULL)	            							
                                            <button style="margin-top:2px; margin-right:2px; margin-bottom:2px; float:right" title="Resend Candidate Personal Information Form" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
                                                data-bs-target="#send-docs-form-{{$workOrder->id}}">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i> Resend Docs Form
                                            </button>
                                            @endif
                                            <div class="modal fade" id="send-docs-form-{{$workOrder->id}}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{route('docs.send-email')}}" id="send_email_{{$workOrder->id}}">
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
                                                                                    <input type="text" name="id" value="{{$workOrder->id}}" hidden>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                                                    <label for="email" class="form-label font-size-13">{{ __('Comments send to candidate') }}</label>
                                                                                </div>
                                                                                <div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
                                                                                    <textarea rows="5" name="comment"  id="comments_{{$workOrder->id}}" class="form-control" required
                                                                                        placeholder="Comments send to candidate" value=""></textarea>																		
                                                                                </div>
                                                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                                                    <label for="email" class="form-label font-size-13">{{ __('Email') }}</label>
                                                                                </div>
                                                                                <div class="col-xxl-12 col-lg-12 col-md-12 radio-main-div">
                                                                                    <input name="email" id="email_{{$workOrder->id}}" class="form-control" required
                                                                                        placeholder="Enter Candidate Email" value="@if($workOrder->email){{$workOrder->email}}@endif">																		
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary send-email"
                                                                    data-id="{{ $workOrder->id }}" data-status="final">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                            @if($workOrder->image_path)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Passport Size Photograph</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('hrm/employee/photo/' . $workOrder->image_path) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('hrm/employee/photo/' . $workOrder->image_path) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('hrm/employee/photo/' . $workOrder->image_path) }}" alt="Passport Size Photograph"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                            @if($workOrder->resume)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Resume</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('hrm/employee/resume/' . $workOrder->resume) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('hrm/employee/resume/' . $workOrder->resume) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('hrm/employee/resume/' . $workOrder->resume) }}" alt="Resume"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                            @if($workOrder->visa)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Visa</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('hrm/employee/visa/' . $workOrder->visa) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('hrm/employee/visa/' . $workOrder->visa) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('hrm/employee/visa/' . $workOrder->visa) }}" alt="Visa"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                            @if($workOrder->emirates_id_file)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center mb-5">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Emirates ID</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('hrm/employee/emirates_id/' . $workOrder->emirates_id_file) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('hrm/employee/emirates_id/' . $workOrder->emirates_id_file) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('hrm/employee/emirates_id/' . $workOrder->emirates_id_file) }}" alt="Emirates ID"></iframe>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="job-offer-letter-{{$workOrder->id}}">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title fw-bold">Job Offer Letter</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                            @if($workOrder->offer_letter_fileName)
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <h4 class="card-title">Resume</h4>
                                                        </div>
                                                        <div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                            <button style="float:right;" type="button" class="btn btn-sm btn-info mt-3 ">
                                                            <a href="{{ url('hrm/employee/offer_letter/' . $workOrder->offer_letter_fileName) }}" download class="text-white">
                                                            Download
                                                            </a>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <iframe src="{{ url('hrm/employee/offer_letter/' . $workOrder->offer_letter_fileName) }}" alt="Offer Letter" style="height:1000;"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="personal-info-{{$workOrder->id}}">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">General Informations</center>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> SO Number </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->so_number ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> WO Number </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->wo_number ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Date </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->date != ''){{\Carbon\Carbon::parse($workOrder->date)->format('d M Y') ?? 'NA'}}@endif</span>
                                                </div>
                                                @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))							
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Batch </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span class="data-font">{{$workOrder->batch ?? 'NA'}}</span>
                                                    </div>
                                                @endif	
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Name </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_name ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Email </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_email ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Company Email </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_company_number ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Address </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_address ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Representative Name</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_representative_name ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Representative Email</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_representative_email ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Customer Representative Contact</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->customer_representative_contact ?? 'NA'}}</span>
                                                </div>
                                                @if(isset($type) && $type == 'export_exw')	
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Freight Agent Name </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span class="data-font">{{$workOrder->freight_agent_name ?? 'NA'}}</span>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Freight Agent Email </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span class="data-font">{{$workOrder->freight_agent_email ?? 'NA'}}</span>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                        <label for="choices-single-default" class="form-label"> Freight Agent Contact Number </label>
                                                    </div>
                                                    <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span class="data-font">{{$workOrder->freight_agent_contact_number ?? 'NA'}}</span>
                                                    </div>
                                                @endif   
                                                @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Port Of Loading </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->port_of_loading ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Port Of Discharge </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->port_of_discharge ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Final Destination </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->final_destination ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Transport Type </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->transport_type ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> BRN Fille </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->brn_file ?? 'NA'}}</span>
                                                </div>                              
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Airline </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->airline ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Airway Bill </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->airway_bill ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Airway Details </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->airway_details ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> BRN </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->brn ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Container Number </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->container_number ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Shipping Line </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->shipping_line ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Forward Import Code </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->forward_import_code ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Trailer Number Plate </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->trailer_number_plate ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Transporting Driver Contact Number </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->transporting_driver_contact_number ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Transportation Company </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->transportation_company ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Transportation Company Details</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->transportation_company_details ?? 'NA'}}</span>
                                                </div>                              
                                                @endif         
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> SO Vehicle Quantity </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->so_vehicle_quantity ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> SO Total Amount </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->so_total_amount ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Amount Received </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->amount_received ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Balance Amount </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->balance_amount ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Delivery Location </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->delivery_location ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Delivery Contact Person </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->delivery_contact_person ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Delivery Date </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->delivery_date != ''){{\Carbon\Carbon::parse($workOrder->delivery_date)->format('d M Y') ?? 'NA'}}@endif</span>
                                                </div>                                 
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Signed PFI </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->signed_pfi ?? 'NA'}}</span>
                                                </div>                            
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Signed Contract </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->signed_contract ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Payment Receipts </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->payment_receipts ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> NOC </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->noc ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> End User Trade License </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->enduser_trade_license ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> End User Passport </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->enduser_passport ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> End User Contract </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->enduser_contract ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label">Vehicle Handover To Person ID</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->vehicle_handover_person_id ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Created By</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->CreatedBy->name ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Created At </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->created_at != ''){{\Carbon\Carbon::parse($workOrder->created_at)->format('d M Y, H:i:s') ?? 'NA'}}@endif</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Updated By </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->updated_at != '' && $workOrder->updated_at != $workOrder->created_at){{\Carbon\Carbon::parse($workOrder->updated_at)->format('d M Y, H:i:s') ?? 'NA'}}@endif</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Sales Support Data Confirmation By</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->salesSupportDataConfirmationBy->name ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Sales Support Data Confirmation At</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->sales_support_data_confirmation_at != ''){{\Carbon\Carbon::parse($workOrder->sales_support_data_confirmation_at)->format('d M Y, H:i:s') ?? 'NA'}}@endif</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Finance Approval By </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->financeApprovalBy->name ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Finance Approved At </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->finance_approved_at != ''){{\Carbon\Carbon::parse($workOrder->finance_approved_at)->format('d M Y, H:i:s') ?? 'NA'}}@endif</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> COE Office Approval By </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">{{$workOrder->coeOfficeApprovalBy->name ?? 'NA'}}</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> COE Office Approved At </label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">@if($workOrder->coe_office_approved_at != ''){{\Carbon\Carbon::parse($workOrder->coe_office_approved_at)->format('d M Y, H:i:s') ?? 'NA'}}@endif</span>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                    <label for="choices-single-default" class="form-label"> Total Number Of BOE:</label>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                    <span class="data-font">NA</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="visa-info-{{$workOrder->id}}">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">Vehicles and Addons Informations</center>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="myTable" class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
                                                <tr style="border-bottom:1px solid #b3b3b3;">
                                                    <th>BOE</th>
                                                    <th>VIN</th>
                                                    <th>Brand</th>
                                                    <th>Variant</th>
                                                    <th>Engine</th>
                                                    <th>Model Description</th>
                                                    <th>Model Year</th>
                                                    <th>Model Year to mention on Documents</th>
                                                    <th>Steering</th>
                                                    <th>Exterior Colour</th>
                                                    <th>Interior Colour</th>
                                                    <th>Warehouse</th>
                                                    <th>Territory</th>
                                                    <th>Preferred Destination</th>
                                                    <th>Import Document Type</th>
                                                    <th>Ownership Name</th>
                                                    <th>Certification Per VIN</th>
                                                    @if(isset($type) && $type == 'export_cnf')
                                                    <th>Shipment</th>
                                                    @endif
                                                </tr>
                                                @if(isset($workOrder->vehicles) && count($workOrder->vehicles) > 0)
                                                @foreach($workOrder->vehicles as $vehicle)
                                                <tr class="custom-border-top">
                                                    <td>{{$vehicle->boe_number ?? 'NA'}}</td>
                                                    <td>{{$vehicle->vin ?? 'NA'}}</td>
                                                    <td>{{$vehicle->brand ?? 'NA'}}</td>
                                                    <td>{{$vehicle->variant ?? 'NA'}}</td>
                                                    <td>{{$vehicle->engine ?? 'NA'}}</td>
                                                    <td>{{$vehicle->model_description ?? 'NA'}}</td>
                                                    <td>{{$vehicle->model_year ?? 'NA'}}</td>
                                                    <td>{{$vehicle->model_year_to_mention_on_documents ?? 'NA'}}</td>
                                                    <td>{{$vehicle->steering ?? 'NA'}}</td>
                                                    <td>{{$vehicle->exterior_colour ?? 'NA'}}</td>
                                                    <td>{{$vehicle->interior_colour ?? 'NA'}}</td>
                                                    <td>{{$vehicle->warehouse ?? 'NA'}}</td>
                                                    <td>{{$vehicle->territory ?? 'NA'}}</td>
                                                    <td>{{$vehicle->preferred_destination ?? 'NA'}}</td>
                                                    <td>{{$vehicle->import_document_type ?? 'NA'}}</td>
                                                    <td>{{$vehicle->ownership_name ?? 'NA'}}</td>
                                                    <td>{{$vehicle->certification_per_vin ?? 'NA'}}</td>
                                                    @if(isset($type) && $type == 'export_cnf')
                                                    <td>{{$vehicle->shipment ?? 'NA'}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Modification/Jobs</th>
                                                    <td colspan="16">{{$vehicle->modification_or_jobs_to_perform_per_vin ?? 'NA'}}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Special Request/Remarks</th>
                                                    <td colspan="16">{{$vehicle->special_request_or_remarks ?? 'NA'}}</td>
                                                </tr>
                                                @if(isset($vehicle->addons) && count($vehicle->addons) > 0)
                                                <tr>
                                                    <th colspan="18">Service Breakdown</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="1">Addon Code</th>
                                                    <th colspan="2">Addon Name</th>
                                                    <th colspan="1">Quantity</th>
                                                    <th colspan="14">Addon Description</th>
                                                </tr>
                                                @foreach($vehicle->addons as $addon)
                                                <tr>
                                                    <td colspan="1">{{$addon->addon_code ?? 'NA'}}</td>
                                                    <td colspan="2">{{$addon->addon_name ?? 'NA'}}</td>
                                                    <td colspan="1">{{$addon->addon_quantity ?? 'NA'}}</td>
                                                    <td colspan="14">{{$addon->addon_description ?? 'NA'}}</td>
                                                </tr>
                                                @endforeach
                                                @endif
                                                @endforeach
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="compensation-benefits-{{$workOrder->id}}">
                            @include('work_order.export_exw.comments')
                            <!-- <div class="row">
                                <div class="row" id="comments-section">

                                </div>
                                <div class="form-group">
                                    <label for="new-comment">Add a comment:</label>
                                    <textarea class="form-control" id="new-comment" rows="3"></textarea>
                                    <button class="btn btn-sm btn-primary mt-2" onclick="addComment()">Add Comment</button>
                                </div>
                            </div> -->
                        </div>
                        <div class="tab-pane fade" id="off-boarding-{{$workOrder->id}}">
                            @include('work_order.export_exw.data_history')
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')

@endpush