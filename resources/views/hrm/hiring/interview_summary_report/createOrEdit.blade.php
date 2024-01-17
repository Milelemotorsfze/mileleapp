@extends('layouts.main')
@include('layouts.formstyle')
@section('content')
@canany(['create-interview-summary-report','edit-interview-summary-report'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-interview-summary-report','edit-interview-summary-report']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">@if($interviewSummaryId == 'new')Create New @else Edit @endif Interview Summary Report</h4>
	
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('employee-hiring-request.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<form id="interviewSummaryReportForm" name="interviewSummaryReportForm" enctype="multipart/form-data" method="POST" action="{{route('interview-summary-report.store-or-update',$interviewSummaryId)}}">
		@csrf
		<div class="card">
		<div class="card-body">
			<div class="row">
			<div class="col-xxl-4 col-lg-4 col-md-4 select-button-main-div">
				<div class="dropdown-option-div">
					<span class="error">* </span>
					<label for="hiring_request_id" class="col-form-label text-md-end">{{ __('Employee Hiring Request UUID') }}</label>
					<select name="hiring_request_id" id="hiring_request_id" multiple="true" class="hiring_request_id form-control widthinput" onchange="" autofocus>
						@foreach($hiringrequests as $hiringrequest)
							<option value="{{$hiringrequest->id}}">{{$hiringrequest->uuid}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-xxl-4 col-lg-4 col-md-4" id="job_position_div">
			<center><label for="job_position" class="col-form-label text-md-end"><strong>{{ __('Position') }}</strong></label></center>
			<center><span id="job_position"></span></center>
			</div>
			<div class="col-xxl-4 col-lg-4 col-md-4" id="department_div">
			<center><label for="department" class="col-form-label text-md-end"><strong>{{ __('Department') }}</strong></label></center>
			<center><span id="department"></span></center>
			</div>
			</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Candidate Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-3 col-lg-6 col-md-6">
                        <span class="error">* </span>
                        <label for="candidate_name" class="col-form-label text-md-end">{{ __('Candidate Name') }}</label>
						<input name="round" value="telephonic" hidden>
                        <input id="candidate_name" type="text" class="form-control widthinput @error('candidate_name') is-invalid @enderror" name="candidate_name"
                                placeholder="Candidate Name" value="" autocomplete="candidate_name" autofocus>
                    </div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="nationality" class="col-form-label text-md-end">{{ __('Choose Nationality') }}</label>
							<select name="nationality" id="nationality" multiple="true" class="form-control widthinput" onchange="" autofocus>
								@foreach($masterNationality as $nationality)
									<option value="{{$nationality->id}}">{{$nationality->nationality}} ( {{$nationality->name}} ) </option>
								@endforeach
							</select>
						</div>
					</div>
                    <div class="col-xxl-3 col-lg-3 col-md-3 radio-main-div">
						<span class="error">* </span>
						<label for="gender" class="col-form-label text-md-end">{{ __('Gender') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="gender" name="gender" value="1" id="1" />
                                    <label for="male">Male</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="gender" name="gender" value="2" id="2" />
                                    <label for="female">Female</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="resume_file_name" class="col-form-label text-md-end">{{ __('Upload Resume PDF') }}</label>
						<input type="file" class="form-control" id="resume_file_name" name="resume_file_name"
                                           placeholder="Upload Other Document" accept="application/pdf">
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Telephonic Interview</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-4 col-lg-6 col-md-6">
						<!-- <span class="error">* </span> -->
						<label for="date_of_telephonic_interview" class="col-form-label text-md-end">{{ __('Telephonic Interview Date') }}</label>
                        <input type="date" name="date_of_telephonic_interview" id="date_of_telephonic_interview" class="form-control widthinput" aria-label="measurement" aria-describedby="basic-addon2">
					</div>
					<div class="col-xxl-8 col-lg-6 col-md-6">
						<!-- <span class="error">* </span> -->
						<label for="interviewer_id" class="col-form-label text-md-end">{{ __('Choose Name Of Interviewers') }}</label>
                        <select name="interviewer_id[]" id="interviewer_id" multiple="true" class="form-control widthinput" onchange="" autofocus>
							@foreach($interviewersNames as $interviewers)
								<option value="{{$interviewers->id}}">{{$interviewers->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
                        <label for="interviewer_id" class="col-form-label text-md-end">{{ __('Telephonic Interview Summary') }}</label>
						<textarea rows="5" id="telephonic_interview" type="text" class="form-control @error('telephonic_interview') is-invalid @enderror"
						name="telephonic_interview" placeholder="Telephonic Interview Summary" value="{{ old('telephonic_interview') }}"  autocomplete="telephonic_interview"
						autofocus></textarea>
					</div>
				</div>
			</div>
		</div>
        <div class="card">
			<div class="card-header">
				<h4 class="card-title">Rate the Appearance Of Applicant</h4>
			</div>
			<div class="card-body">
				<div class="row">
                    <div class="col-xxl-12 col-lg-6 col-md-6">
						
                        <fieldset style="margin-top:5px;">
                            <div class="row some-class">
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <!-- <span class="error">* </span> -->
                                    <label for="rate_dress_appearance" class="col-form-label text-md-end">{{ __('Dress') }}</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_dress_appearance" name="rate_dress_appearance" value="poor" id="dress_poor" />
                                    <label for="dress_poor">POOR</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_dress_appearance" name="rate_dress_appearance" value="fair" id="dress_fair" />
                                    <label for="dress_fair">FAIR</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_dress_appearance" name="rate_dress_appearance" value="average" id="dress_average" />
                                    <label for="dress_average">AVERAGE</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_dress_appearance" name="rate_dress_appearance" value="good" id="dress_good" />
                                    <label for="dress_good">GOOD</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_dress_appearance" name="rate_dress_appearance" value="superior" id="dress_superior" />
                                    <label for="dress_superior">SUPERIOR</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
					<div class="col-xxl-12 col-lg-6 col-md-6">
						
					
                        <fieldset style="margin-top:5px;">
                            <div class="row some-class">
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <!-- <span class="error">* </span> -->
                                    <label for="rate_body_language_appearance" class="col-form-label text-md-end">{{ __('Body Language') }}</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_body_language_appearance" name="rate_body_language_appearance" value="poor" id="body_language_poor" />
                                    <label for="body_language_poor">POOR</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_body_language_appearance" name="rate_body_language_appearance" value="fair" id="body_language_fair" />
                                    <label for="body_language_fair">FAIR</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_body_language_appearance" name="rate_body_language_appearance" value="average" id="body_language_average" />
                                    <label for="body_language_average">AVERAGE</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_body_language_appearance" name="rate_body_language_appearance" value="good" id="body_language_good" />
                                    <label for="body_language_good">GOOD</label>
                                </div>
                                <div class="col-xxl-2 col-lg-6 col-md-6">
                                    <input type="radio" class="rate_body_language_appearance" name="rate_body_language_appearance" value="superior" id="body_language_superior" />
                                    <label for="body_language_superior">SUPERIOR</label>
                                </div>
                            </div>
                        </fieldset>
					</div>
				</div>
			</div>
		</div>
		<div class="card preview-div" hidden>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div id="file4-preview">
							@if($currentInterviewReport->resume_file_name)
								<h6 class="fw-bold text-center mb-1">Resume</h6>
								<iframe src="{{ url('resume/' . $currentInterviewReport->resume_file_name) }}" alt="Resume" style="height:1000;"></iframe>
								<button  type="button" class="btn btn-sm btn-info mt-3 ">
									<a href="{{ url('resume/' . $currentInterviewReport->resume_file_name) }}" download class="text-white">
										Download
									</a>
								</button>
								<!-- <button  type="button" class="btn btn-sm btn-danger mt-3 delete-button"
											data-file-type="Resume"> Delete
								</button> -->
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
		</div>
	</form>
</div>
@include('hrm.hiring.hiring_request.createJobPosition')
<div class="overlay"></div>
@endif
@endcan
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	const file4InputLicense = document.querySelector("#resume_file_name");
	const previewFile4 = document.querySelector("#file4-preview");
	var hiringrequests = {!! json_encode($hiringrequests) !!};
	var currentInterviewReport = {!! json_encode($currentInterviewReport) !!};
	var telephonicInterviewersArr =[];
	$(document).ready(function () {
		if(currentInterviewReport.resume_file_name != undefined) {
			$('.preview-div').attr('hidden', false);
		}
		$("#hiring_request_id").val(currentInterviewReport.hiring_request_id);
		$("#candidate_name").val(currentInterviewReport.candidate_name);
		$("#nationality").val(currentInterviewReport.nationality);
		$('#' + currentInterviewReport.gender).prop('checked',true);
		// $("#resume_file_name").val(currentInterviewReport.resume_file_name);
		$("#date_of_telephonic_interview").val(currentInterviewReport.date_of_telephonic_interview);
		if(currentInterviewReport.telephonicInterviewers != undefined) {
			if(currentInterviewReport.telephonicInterviewers.length > 0) {
				for(var i=0; i<currentInterviewReport.telephonicInterviewers.length; i++) {
					telephonicInterviewersArr.push(currentInterviewReport.telephonicInterviewers[i].interviewer_id);
				}
			}
		}
		$("#interviewer_id").val(telephonicInterviewersArr);
		$("#telephonic_interview").val(currentInterviewReport.telephonic_interview);
		$('#dress_' + currentInterviewReport.rate_dress_appearance).prop('checked',true);
		$('#body_language_' + currentInterviewReport.rate_body_language_appearance).prop('checked',true);

		$('#job_position_div').hide();
		$('#department_div').hide();
        $('#nationality').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Nationality",
        });
		$('#interviewer_id').select2({
            allowClear: true,
            placeholder:"Choose Name Of Interviewers",
        });
		$('#hiring_request_id').select2({
            allowClear: true,
			maximumSelectionLength: 1,
            placeholder:"Choose Employee Hiring Request UUID",
        });	
		$('.hiring_request_id').change(function (e) {
			var hiringRequestId = $('#hiring_request_id').val();
			if(hiringRequestId != '') {
				if(hiringrequests.length > 0) {
					for(var i=0; i<hiringrequests.length; i++) {						
						if(hiringrequests[i].id == hiringRequestId) {
							$('#job_position_div').show();
							$('#department_div').show();
							document.getElementById('job_position').textContent=hiringrequests[i].questionnaire.designation.name;
							document.getElementById('department').textContent=hiringrequests[i].questionnaire.department.name;
						}
					}
				}
			}
			else {
				$('#job_position_div').hide();
				$('#department_div').hide();
			}			
		});
	});
	file4InputLicense.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);
			document.getElementById("file4-preview").innerHTML = "";
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match("application/pdf")) {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
					iframe.height = "1000";
                    iframe.src = objectUrl;
                    previewFile4.appendChild(iframe);
                } 
            }
        });
	jQuery.validator.setDefaults({
        errorClass: "is-invalid",
        errorElement: "p",
        
    });
	$('#interviewSummaryReportForm').validate({ // initialize the plugin
		// alert($('#resume_file_name').val());
        rules: {
			candidate_name: {
				required: true,
			},
            nationality: {
                required: true,
            },
            gender: {
                required: true,
            },
			// resume_file_name: {
            //     required: true,
            // },
			hiring_request_id: {
				required: true,
			}
        },
		errorPlacement: function ( error, element ) {
            error.addClass( "invalid-feedback font-size-13" );
			
            if (element.is(':radio') && element.closest('.radio-main-div').length > 0) {
                error.addClass('radio-error');
                error.insertAfter(element.closest('.radio-main-div').find('fieldset.radio-div-container').last());
            }
			else if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
                if (!element.val() || element.val().length === 0) {
                    console.log("Error is here with length", element.val().length);
                    error.addClass('select-error');
                    error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
                } else {
                    console.log("No error");
                }
            }
            else {
                error.insertAfter( element );
            }
        }
    });
</script>
@endsection