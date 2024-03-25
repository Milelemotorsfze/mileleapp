<!doctype html>
<html lang="en">
	<head>
		@include('partials/head-css')
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
		<script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
		<style type="text/css">
			.m-signature-pad--body canvas {
			position: relative; left: 0; top: 0; width: 100%; height: 250px; border: 1px solid #CCCCCC;
			}
			.card-header {
			background-color:#e8f3fd!important;
			}
			.btn_round {
			margin-top: 34px!important; padding-top: 0px!important;
			}
			.error {
			color: #FF0000;
			}
			.iti {
			width: 100%;
			}
			.btn_round {
			width: 30px; height: 30px; display: inline-block; text-align: center; line-height: 35px; margin-left: 10px; margin-top: 28px; border: 1px solid #ccc;
			color:#fff; background-color: #fd625e; border-radius:5px; cursor: pointer; padding-top:7px;
			}
			.btn_round:hover {
			color: #fff; background: #fd625e; border: 1px solid #fd625e;
			}
			body {
			font-family: Arial;
			}
			.widthinput {
			height:32px!important;
			}
			input:focus {
			border-color: #495057!important;
			}
			select:focus    {
			border-color: #495057!important;
			}
			a:focus {
			border-color: #495057!important;
			}
		</style>
	</head>
	<body data-layout="horizontal">
		<div class="card-header" style="background-color:#005ba1!important;">
			<div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
				<img src="{{ asset('logo.png') }}" width="20" height="40" alt="Logo" class="mx-auto">
			</div>
			<h1 class="card-title" style="color:white!important;">
				<center>MILELE</center>
			</h1>
		</div>
		<div id="layout-wrapper">
			<div class="main-content">
				<div class="page-content">
					<div class="container-fluid">
						<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header" style="background-color:#d2e7f9!important;">
										<h4 class="card-title">
											<center>CANDIDATE DOCUMENTS SHARING FORM</center>
										</h4>
									</div>
									<div class="card-body">
										<form class="w3-container" action="{{route('candidate.storeDocs')}}" method="POST" id="candidatepersonalInfoForm"
											name="DAFORM"  enctype="multipart/form-data" target="_self">
											@csrf
											<div class="row">
												<div class="col-xxl-3 col-lg-3 col-md-3">
													<center><label for="candidate_name" class="col-form-label text-md-end" style="font-weight:bold!important;">{{ __('Candidate Name') }}</label> : {{$candidate->candidate_name ?? ''}}</center>
												</div>
												<div class="col-xxl-3 col-lg-3 col-md-3">
													<center><label for="nationality" class="col-form-label text-md-end" style="font-weight:bold!important;">{{ __('Nationality') }}</label> : {{$candidate->nationalities->name ?? ''}}</center>
												</div>
												<div class="col-xxl-3 col-lg-3 col-md-3">
													<center><label for="gender" class="col-form-label text-md-end" style="font-weight:bold!important;">{{ __('Gender') }}</label> : {{$candidate->gendername->name ?? ''}}</center>
												</div>
												<div class="col-xxl-3 col-lg-3 col-md-3">
													<center><label for="job_position" class="col-form-label text-md-end" style="font-weight:bold!important;">{{ __('Job Position') }}</label> : {{$candidate->employeeHiringRequest->questionnaire->designation->name ?? ''}}</center>
												</div>
												<div class="col-xxl-12 col-lg-12 col-md-12">
													<p><span style="float:right;" class="error">* Required Field</span></p>
												</div>
											</div>
											<br>
											<input name="id" value="{{$candidate->id}}" hidden>
											<div class="card">
												<div class="card-header">
													<h4 class="card-title">
														<center>Upload Documents</center>
													</h4>
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('Passport Size Photograph') }}</label>
															<input type="file" class="form-control" id="passport-size-photograph" name="passport_size_photograph"
																accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('Resume') }}</label>
															<input type="file" class="form-control" id="resume" name="resume"
																accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('Visa') }}</label>
															<input type="file" class="form-control" id="visa-file" name="visa"
																accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<label for="first_name" class="col-form-label text-md-end">{{ __('Emirates ID') }}</label>
															<input type="file" class="form-control" id="emirates-id-file" name="emirates_id"
																placeholder="Upload Emirates ID" accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('Passport (First & Second page)') }}</label>
															<input type="file" class="form-control" multiple id="passport-file" name="passport[]"
																placeholder="Upload Passport (First & Second page)" accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('National ID (First & Second page)') }}</label>
															<input type="file" class="form-control" multiple id="national-id-file" name="national_id[]"
																placeholder="Upload National ID (First & Second page)" accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('Attested Educational Documents') }}</label>
															<input type="file" class="form-control" multiple id="educational-docs" name="educational_docs[]"
																placeholder="Upload Attested Educational Documents" accept="application/pdf, image/*">
														</div>
														<div class="col-xxl-3 col-lg-6 col-md-6">
															<span class="error">* </span>
															<label for="first_name" class="col-form-label text-md-end">{{ __('Attested Professional Diplomas / Certificates') }}</label>
															<input type="file" class="form-control" multiple id="professional-diploma-certificates" name="professional_diploma_certificates[]"
																placeholder="Upload Attested Professional Diplomas / Certificates" accept="application/pdf, image/*">
														</div>
													</div>
													<div class="card preview-div" hidden>
														<div class="card-body">
															<div class="row">
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="passport-size-photograph-label"></span>
																	<div id="passport-size-photograph-preview">
																		@if(isset($candidate->candidateDetails->image_path))
																		<div id="passport-size-photograph-preview1">
																			<div class="row">
																				<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
																					<h6 class="fw-bold text-center mb-1" style="float:left;">Passport Size Photograph</h6>
																				</div>
																				<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
																					<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
																					<a href="{{ url('hrm/employee/photo/' . $candidate->candidateDetails->image_path) }}" download class="text-white">
																					Download
																					</a>
																					</button>
																					<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
																						data-file-type="PASSPORT"> Delete</button>
																				</div>
																			</div>
																			<iframe src="{{ url('hrm/employee/photo/' . $candidate->candidateDetails->image_path) }}" alt="Passport Size Photograph"></iframe>                                                                           
																		</div>
																		@endif
																	</div>
																</div>
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="resume-label"></span>
																	<div id="resume-preview">
																		@if(isset($candidate->candidateDetails->resume))
																		<div id="resume-preview1">
																			<div class="row">
																				<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
																					<h6 class="fw-bold text-center mb-1" style="float:left;">Resume</h6>
																				</div>
																				<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
																					<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
																					<a href="{{ url('hrm/employee/resume/' . $candidate->candidateDetails->resume) }}" download class="text-white">
																					Download
																					</a>
																					</button>
																					<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
																						data-file-type="RESUME"> Delete</button>
																				</div>
																			</div>
																			<iframe src="{{ url('hrm/employee/resume/' . $candidate->candidateDetails->resume) }}" alt="Resume"></iframe>                                                                           
																		</div>
																		@endif
																	</div>
																</div>
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="visa-label"></span>
																	<div id="visa-file-preview">
																		@if(isset($candidate->candidateDetails->visa))
																		<div id="visa-file-preview1">
																			<div class="row">
																				<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
																					<h6 class="fw-bold text-center mb-1" style="float:left;">Visa</h6>
																				</div>
																				<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
																					<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
																					<a href="{{ url('hrm/employee/visa/' . $candidate->candidateDetails->visa) }}" download class="text-white">
																					Download
																					</a>
																					</button>
																					<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
																						data-file-type="VISA"> Delete</button>
																				</div>
																			</div>
																			<iframe src="{{ url('hrm/employee/visa/' . $candidate->candidateDetails->visa) }}" alt="Visa"></iframe>                                                                           
																		</div>
																		@endif
																	</div>
																</div>
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="emirates-id-label"></span>
																	<div id="emirates-id-file-preview">
																		@if(isset($candidate->candidateDetails->emirates_id_file))
																		<div id="emirates-id-file-preview1">
																			<div class="row">
																				<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
																					<h6 class="fw-bold text-center mb-1" style="float:left;">Emirates ID</h6>
																				</div>
																				<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
																					<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
																					<a href="{{ url('hrm/employee/emirates_id/' . $candidate->candidateDetails->emirates_id_file) }}" download class="text-white">
																					Download
																					</a>
																					</button>
																					<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
																						data-file-type="EMIRATESID"> Delete</button>
																				</div>
																			</div>
																			<iframe src="{{ url('hrm/employee/emirates_id/' . $candidate->candidateDetails->emirates_id_file) }}" alt="Emirates ID"></iframe>                                                                           
																		</div>
																		@endif
																	</div>
																</div>
															</div>
															<div class="row mt-4">
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="passport-label">
																	@if(isset($candidate->candidateDetails->candidatePassport) && $candidate->candidateDetails->candidatePassport->count() > 0) Passport @endif
																	</span>
																	@if(isset($candidate->candidateDetails->candidatePassport) && $candidate->candidateDetails->candidatePassport->count() > 0)
																	@foreach($candidate->candidateDetails->candidatePassport as $document)
																	<div id="preview-div-{{$document->id}}">
																		<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
																		<a href="{{url('hrm/employee/passport/' . $document->document_path)}}" download class="text-white">
																		Download
																		</a>
																		</button>
																		<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}"> 
																		Delete
																		</button>
																		<iframe src="{{ url('hrm/employee/passport/' . $document->document_path) }}" class="mt-2" alt="Passport"></iframe>                                                                                   
																	</div>
																	@endforeach
																	@endif
																	<div id="passport-file-preview">
																	</div>
																</div>
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="national-id-label">
																	@if(isset($candidate->candidateDetails->candidateNationalId) && $candidate->candidateDetails->candidateNationalId->count() > 0) National ID @endif
																	</span>
																	@if(isset($candidate->candidateDetails->candidateNationalId) && $candidate->candidateDetails->candidateNationalId->count() > 0)
																	@foreach($candidate->candidateDetails->candidateNationalId as $document)
																	<div id="preview-div-{{$document->id}}">
																		<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
																		<a href="{{url('hrm/employee/national_id/' . $document->document_path)}}" download class="text-white">
																		Download
																		</a>
																		</button>
																		<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}"> 
																		Delete
																		</button>
																		<iframe src="{{ url('hrm/employee/national_id/' . $document->document_path) }}" class="mt-2" alt="National ID"></iframe>                                                                                   
																	</div>
																	@endforeach
																	@endif
																	<div id="national-id-file-preview">
																	</div>
																</div>
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="educational-docs-label">
																	@if(isset($candidate->candidateDetails->candidateEduDocs) && $candidate->candidateDetails->candidateEduDocs->count() > 0) Attested Educational Documents @endif
																	</span>
																	@if(isset($candidate->candidateDetails->candidateEduDocs) && $candidate->candidateDetails->candidateEduDocs->count() > 0)
																	@foreach($candidate->candidateDetails->candidateEduDocs as $document)
																	<div id="preview-div-{{$document->id}}">
																		<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
																		<a href="{{url('hrm/employee/educational_docs/' . $document->document_path)}}" download class="text-white">
																		Download
																		</a>
																		</button>
																		<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}"> 
																		Delete
																		</button>
																		<iframe src="{{ url('hrm/employee/educational_docs/' . $document->document_path) }}" class="mt-2" alt="Attested Educational Documents"></iframe>                                                                                   
																	</div>
																	@endforeach
																	@endif
																	<div id="educational-docs-preview">
																	</div>
																</div>
																<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
																	<span class="fw-bold col-form-label text-md-end" id="professional-diploma-certificates-label">
																	@if(isset($candidate->candidateDetails->candidateProDipCerti) && $candidate->candidateDetails->candidateProDipCerti->count() > 0) Professional / Diploma Certificates @endif
																	</span>
																	@if(isset($candidate->candidateDetails->candidateProDipCerti) && $candidate->candidateDetails->candidateProDipCerti->count() > 0)
																	@foreach($candidate->candidateDetails->candidateProDipCerti as $document)
																	<div id="preview-div-{{$document->id}}">
																		<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
																		<a href="{{url('hrm/employee/professional_diploma_certificates/' . $document->document_path)}}" download class="text-white">
																		Download
																		</a>
																		</button>
																		<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}"> 
																		Delete
																		</button>
																		<iframe src="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" class="mt-2" alt="Professional / Diploma Certificates"></iframe>                                                                                   
																	</div>
																	@endforeach
																	@endif
																	<div id="professional-diploma-certificates-preview">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											</br>
											<input type="hidden" id="photo-file-delete" name="is_photo_delete" value="">
											<input type="hidden" id="visa-file-delete" name="is_visa_delete" value="">
											<input type="hidden" id="resume-file-delete" name="is_resume_delete" value="">   
											<input type="hidden" id="emirates-id-file-delete" name="is_emirates_id_delete" value=""> 
											<select hidden id="deleted-files" name="deleted_files[]" multiple="true">
											</select>
											<div class="row">
												<div class="col-xxl-6 col-lg-6 col-md-6">
													<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@include('partials/footer')
			</div>
		</div>
		@include('partials/right-sidebar')
		@include('partials/vendor-scripts')
		@stack('scripts')
		<script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
		<script src="{{ asset('js/app.js') }}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
		<script type="text/javascript">
			var candidate = {!! json_encode($candidate) !!};
			var oldNationalIdArr = [];
			var oldPassportArr = [];
			var oldProDipCertiArr = [];
			var oldEduDocsArr = [];
			const fileInputPhotograph = document.querySelector("#passport-size-photograph");            
			const fileInputResume = document.querySelector("#resume");
			const fileInputVisa = document.querySelector("#visa-file");            
			const fileInputEmiratesId = document.querySelector("#emirates-id-file");
			const fileInputPassport = document.querySelector("#passport-file");
			const fileInputNationalId = document.querySelector("#national-id-file");
			const fileInputEducationalDocs = document.querySelector("#educational-docs");
			const fileInputProfDiploCertificates = document.querySelector("#professional-diploma-certificates");
			const previewFilePhotograph = document.querySelector("#passport-size-photograph-preview");
			const previewFileResume = document.querySelector("#resume-preview");
			const previewFileVisa = document.querySelector("#visa-file-preview");
			const previewFileEmiratesId = document.querySelector("#emirates-id-file-preview");
			const previewFilePassport = document.querySelector("#passport-file-preview");
			const previewFileNationalId = document.querySelector("#national-id-file-preview");
			const previewFileEducationalDocs = document.querySelector("#educational-docs-preview");
			const previewFileProfDiploCertificates = document.querySelector("#professional-diploma-certificates-preview");
			fileInputPhotograph.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFilePhotograph.firstChild) {
			        previewFilePhotograph.removeChild(previewFilePhotograph.firstChild);
			    }
			    const file = files[0];
			    // if (file.type.match("application/pdf")) {
			        document.getElementById('passport-size-photograph-label').textContent="Passport Size Photograph";
			        const objectUrl = URL.createObjectURL(file);
			        const iframe = document.createElement("iframe");
			        iframe.src = objectUrl;
			        previewFilePhotograph.appendChild(iframe);
			    // }
			    // else if (file.type.match("image/*")) {
			    //     document.getElementById('passport-size-photograph-label').textContent="Passport Size Photograph";
			    //     const objectUrl = URL.createObjectURL(file);
			    //     const image = new Image();
			    //     image.src = objectUrl;
			    //     previewFilePhotograph.appendChild(image);
			    // }
			});
			fileInputResume.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileResume.firstChild) {
			        previewFileResume.removeChild(previewFileResume.firstChild);
			    }
			    const file = files[0];
			    // if (file.type.match("application/pdf"))
			    // {
			        document.getElementById('resume-label').textContent="Resume";
			        const objectUrl = URL.createObjectURL(file);
			        const iframe = document.createElement("iframe");
			        iframe.src = objectUrl;
			        previewFileResume.appendChild(iframe);
			    // }
			    // else if (file.type.match("image/*"))
			    // {
			    //     document.getElementById('resume-label').textContent="Resume";
			    //     const objectUrl = URL.createObjectURL(file);
			    //     const image = new Image();
			    //     image.src = objectUrl;
			    //     previewFileResume.appendChild(image);
			    // }
			});
			fileInputVisa.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileVisa.firstChild) {
			        previewFileVisa.removeChild(previewFileVisa.firstChild);
			    }
			    const file = files[0];
			    // if (file.type.match("application/pdf"))
			    // {
			        document.getElementById('visa-label').textContent="Visa";
			        const objectUrl = URL.createObjectURL(file);
			        const iframe = document.createElement("iframe");
			        iframe.src = objectUrl;
			        previewFileVisa.appendChild(iframe);
			    // }
			    // else if (file.type.match("image/*"))
			    // {
			    //     document.getElementById('visa-label').textContent="Visa";
			    //     const objectUrl = URL.createObjectURL(file);
			    //     const image = new Image();
			    //     image.src = objectUrl;
			    //     previewFileVisa.appendChild(image);
			    // }
			});
			fileInputEmiratesId.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileEmiratesId.firstChild) {
			        previewFileEmiratesId.removeChild(previewFileEmiratesId.firstChild);
			    }
			    const file = files[0];
			    // if (file.type.match("application/pdf"))
			    // {
			        document.getElementById('emirates-id-label').textContent="Emirates ID";
			        const objectUrl = URL.createObjectURL(file);
			        const iframe = document.createElement("iframe");
			        iframe.src = objectUrl;
			        previewFileEmiratesId.appendChild(iframe);
			    // }
			    // else if (file.type.match("image/*"))
			    // {
			    //     document.getElementById('emirates-id-label').textContent="Emirates ID";
			    //     const objectUrl = URL.createObjectURL(file);
			    //     const image = new Image();
			    //     image.src = objectUrl;
			    //     previewFileEmiratesId.appendChild(image);
			    // }
			});
			fileInputPassport.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFilePassport.firstChild) {
			        previewFilePassport.removeChild(previewFilePassport.firstChild);
			    }
			    document.getElementById('passport-label').textContent="Passport";
			    for (let i = 0; i < files.length; i++) {
			        const file = files[i];
			        // if (file.type.match("application/pdf")) {
			            const objectUrl = URL.createObjectURL(file);
			            const iframe = document.createElement("iframe");
			            iframe.src = objectUrl;
			            previewFilePassport.appendChild(iframe);
			        // } else if (file.type.match("image/*")) {
			        //     const objectUrl = URL.createObjectURL(file);
			        //     const image = new Image();
			        //     image.src = objectUrl;
			        //     previewFilePassport.appendChild(image);
			        // }
			    }
			});
			fileInputNationalId.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileNationalId.firstChild) {
			        previewFileNationalId.removeChild(previewFileNationalId.firstChild);
			    }
			    document.getElementById('national-id-label').textContent="National ID";
			    for (let i = 0; i < files.length; i++) {
			        const file = files[i];
			        // if (file.type.match("application/pdf")) {
			            const objectUrl = URL.createObjectURL(file);
			            const iframe = document.createElement("iframe");
			            iframe.src = objectUrl;
			            previewFileNationalId.appendChild(iframe);
			        // } else if (file.type.match("image/*")) {
			        //     const objectUrl = URL.createObjectURL(file);
			        //     const image = new Image();
			        //     image.src = objectUrl;
			        //     previewFileNationalId.appendChild(image);
			        // }
			    }
			});
			fileInputEducationalDocs.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileEducationalDocs.firstChild) {
			        previewFileEducationalDocs.removeChild(previewFileEducationalDocs.firstChild);
			    }
			    document.getElementById('educational-docs-label').textContent="Attested Educational Documents";
			    for (let i = 0; i < files.length; i++) {
			        const file = files[i];
			        // if (file.type.match("application/pdf")) {
			            const objectUrl = URL.createObjectURL(file);
			            const iframe = document.createElement("iframe");
			            iframe.src = objectUrl;
			            previewFileEducationalDocs.appendChild(iframe);
			        // } else if (file.type.match("image/*")) {
			        //     const objectUrl = URL.createObjectURL(file);
			        //     const image = new Image();
			        //     image.src = objectUrl;
			        //     previewFileEducationalDocs.appendChild(image);
			        // }
			    }
			});
			fileInputProfDiploCertificates.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileProfDiploCertificates.firstChild) {
			        previewFileProfDiploCertificates.removeChild(previewFileProfDiploCertificates.firstChild);
			    }
			    document.getElementById('professional-diploma-certificates-label').textContent="Attested Professional Diplomas / Certificates";
			    for (let i = 0; i < files.length; i++) {
			        const file = files[i];
			        // if (file.type.match("application/pdf")) {
			            const objectUrl = URL.createObjectURL(file);
			            const iframe = document.createElement("iframe");
			            iframe.src = objectUrl;
			            previewFileProfDiploCertificates.appendChild(iframe);
			        // } else if (file.type.match("image/*")) {
			        //     const objectUrl = URL.createObjectURL(file);
			        //     const image = new Image();
			        //     image.src = objectUrl;
			        //     previewFileProfDiploCertificates.appendChild(image);
			        // }
			    }
			});
			$(document).ready(function() {                
			    if(candidate.candidate_details != null) {
			        if(candidate.candidate_details.candidate_national_id.length > 0) {
			            for(var i=0; i<candidate.candidate_details.candidate_national_id.length; i++) {
			                oldNationalIdArr.push(candidate.candidate_details.candidate_national_id[i].id);
			            }
			        }
			        if(candidate.candidate_details.candidate_passport.length > 0) {
			            for(var i=0; i<candidate.candidate_details.candidate_passport.length; i++) {
			                oldPassportArr.push(candidate.candidate_details.candidate_passport[i].id);
			            }
			        }
			        if(candidate.candidate_details.candidate_pro_dip_certi.length > 0) {
			            for(var i=0; i<candidate.candidate_details.candidate_pro_dip_certi.length; i++) {
			                oldProDipCertiArr.push(candidate.candidate_details.candidate_pro_dip_certi[i].id);
			            }
			        }
			        if(candidate.candidate_details.candidate_edu_docs.length > 0) {
			            for(var i=0; i<candidate.candidate_details.candidate_edu_docs.length; i++) {
			                oldEduDocsArr.push(candidate.candidate_details.candidate_edu_docs[i].id);
			            }
			        }
			        if(candidate.candidate_details.image_path != '' || candidate.candidate_details.resume != '' || candidate.candidate_details.visa ||
			        candidate.candidate_details.emirates_id_file || candidate.candidate_details.candidate_passport.length > 0 ||
			        candidate.candidate_details.candidate_national_id.length > 0 || candidate.candidate_details.candidate_pro_dip_certi.length > 0 ||
			        candidate.candidate_details.candidate_edu_docs.length > 0) {
			            $('.preview-div').attr('hidden', false);
			        }
			    }
			    alertify.set('notifier','position', 'top-right','delay', 40);
			    $('.close').on('click', function() {
			        $('.alert').hide();
			    })
			    ClassicEditor
			    .create(document.querySelector('#editor'))
			    .catch(error => {
			    });
			    $('input[type=file]').on('change',function(){
			        $(this).valid();
			    });
			    $('input[type=date]').on('change',function(){
			        $(this).valid();
			    });
			});
			jQuery.validator.setDefaults({
			    errorClass: "is-invalid",
			    errorElement: "p",
			    errorPlacement: function ( error, element ) {
			        error.addClass( "invalid-feedback font-size-13" );
			        if ( element.prop( "type" ) === "checkbox" ) {
			            error.insertAfter( element.parent( "label" ) );
			        }
			        else if (element.hasClass("select2-hidden-accessible")) {
			            element = $("#select2-" + element.attr("id") + "-container").parent();
			            error.insertAfter(element);
			        }
			        else if (element.parent().hasClass('input-group')) {
			            error.insertAfter(element.parent());
			        }
			        else {
			            error.insertAfter( element );
			        }
			    }
			});
			$('#candidatepersonalInfoForm').validate({ 
			    rules: {
			        passport_size_photograph: { 
			            required: function(element){
			                if(candidate == null && candidate.candidate_details == null && candidate.candidate_details.image_path == null && $("#passport-size-photograph").val().length > 0) {
			                    return false;
			                }
			                else if(candidate != null && candidate.candidate_details != null && candidate.candidate_details.image_path != null && $("#photo-file-delete").val().length == 0) {
			                    return false;
			                }    
			                else if(candidate != null && candidate.candidate_details != null && candidate.candidate_details.image_path != null && $("#photo-file-delete").val().length > 0) {
			                    return true;
			                }                         
			                else {
			                    return true;
			                }
			            },
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        resume: { 
			            required: function(element){
			                if(candidate == null && candidate.candidate_details == null && candidate.candidate_details.resume == null && $("#resume").val().length > 0) {
			                    return false;
			                }
			                else if(candidate != null && candidate.candidate_details != null && candidate.candidate_details.resume != null && $("#resume-file-delete").val().length == 0) {
			                    return false;
			                }    
			                else if(candidate != null && candidate.candidate_details != null && candidate.candidate_details.resume != null && $("#resume-file-delete").val().length > 0) {
			                    return true;
			                }                         
			                else {
			                    return true;
			                }
			            },
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        visa: { 
			            required: function(element) {
			                if(candidate == null && candidate.candidate_details == null && candidate.candidate_details.visa == null && $("#visa-file").val().length > 0) {
			                    return false;
			                }
			                else if(candidate != null && candidate.candidate_details != null && candidate.candidate_details.visa != null && $("#visa-file-delete").val().length == 0) {
			                    return false;
			                }    
			                else if(candidate != null && candidate.candidate_details != null && candidate.candidate_details.visa != null && $("#visa-file-delete").val().length > 0) {
			                    return true;
			                }                         
			                else {
			                    return true;
			                }
			            },
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        emirates_id: { 
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        "passport[]": {
			            required: function(element) {
			                if(candidate != null  && candidate.candidate_details != null && candidate.candidate_details.candidate_passport != null && oldPassportArr.length > 0) {
			                    return false;
			                }
			                else if($("#passport-file").val().length > 0) {
			                    return false;
			                }              
			                else {
			                    return true;
			                }
			            },
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        "national_id[]": {
			            required: function(element) {
			                if(candidate != null  && candidate.candidate_details != null && candidate.candidate_details.candidate_national_id != null && oldNationalIdArr.length > 0) {
			                    return false;
			                }
			                else if($("#national-id-file").val().length > 0) {
			                    return false;
			                }              
			                else {
			                    return true;
			                }
			            },
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        "educational_docs[]": {
			            required: function(element) {
			                if(candidate != null  && candidate.candidate_details != null && candidate.candidate_details.candidate_edu_docs != null && oldEduDocsArr.length > 0) {
			                    return false;
			                }
			                else if($("#educational-docs").val().length > 0) {
			                    return false;
			                }              
			                else {
			                    return true;
			                }
			            },
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			        "professional_diploma_certificates[]": {
			            extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
			            maxsize : 1073741824,
			        },
			    },
			    messages: {
			        passport_size_photograph:{
			            filesize:" file size must be less than 1 GB.",
			        },
			        resume:{
			            filesize:" file size must be less than 1 GB.",
			        },
			        visa:{
			            filesize:" file size must be less than 1 GB.",
			        },
			        emirates_id:{
			            filesize:" file size must be less than 1 GB.",
			        },
			        "passport[]":{
			            filesize:" file size must be less than 1 GB.",
			        },
			        "national_id[]":{
			            filesize:" file size must be less than 1 GB.",
			        },
			        "educational_docs[]":{
			            filesize:" file size must be less than 1 GB.",
			        },
			        "professional_diploma_certificates[]":{
			            filesize:" file size must be less than 1 GB.",
			        },
			    },
			});
			var deletedDocuments = new Array();
			$('.document-delete-button').on('click',function() {
			    let id = $(this).attr('data-id');
			    if (confirm('Are you sure you want to Delete this item ?')) {
			        $('#preview-div-'+id).remove();
			        deletedDocuments.push(Number(id));
			        $("#deleted-files").append('<option value='+Number(id)+' selected>yyyyyyyyy</option>');
			        oldProDipCertiArr = oldProDipCertiArr.filter(x => !deletedDocuments.includes(x));
			        oldPassportArr = oldPassportArr.filter(x => !deletedDocuments.includes(x));
			        oldEduDocsArr = oldEduDocsArr.filter(x => !deletedDocuments.includes(x));
			        oldNationalIdArr = oldNationalIdArr.filter(x => !deletedDocuments.includes(x));
			    }
			});
			$('.delete-button').on('click',function() {
			    var fileType = $(this).attr('data-file-type');
			    if (confirm('Are you sure you want to Delete this item ?')) {
			        if(fileType == 'PASSPORT') {
			            $('#passport-size-photograph-preview1').remove();
			            $('#photo-file-delete').val(1);
			
			        }else if(fileType == 'RESUME') {
			            $('#resume-preview1').remove();
			            $('#resume-file-delete').val(1);
			
			        }else if(fileType == 'VISA') {
			            $('#visa-file-preview1').remove();
			            $('#visa-file-delete').val(1);
			        }
			        else if(fileType == 'EMIRATESID') {
			            $('#emirates-id-file-preview1').remove();
			            $('#emirates-id-file-delete').val(1);
			        }
			    }
			});
		</script>
	</body>
</html>