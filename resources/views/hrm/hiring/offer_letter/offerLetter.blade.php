<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
		<style>
			#offer-letter-page .error {
			color: #FF0000;
			}
			#offer-letter-page .m-signature-pad--body canvas {
			position: relative; left: 0; top: 0; width: 100%; height: 100%; border: 1px solid #CCCCCC;
			}
			#offer-letter-page a:link {
			text-decoration: none;
			}
			#offer-letter-page body {
			margin: 0; padding: 0; background-color: white; font: 12pt "Tahoma";
			}
			#offer-letter-page * {
			box-sizing: border-box; -moz-box-sizing: border-box;
			}
			#offer-letter-page .page {
			width: 21cm; min-height: 29.7cm; padding: 2cm; margin: 1cm auto; border: 1px #f5fbff solid; border-radius: 5px; background: #f5fbff; 
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); 
			}
			#offer-letter-page .subpage {
			padding: 1cm; border: 5px #f5fbff solid; height: 256mm; outline: 2cm #f5fbff solid;
			}
			#offer-letter-page @page {
			size: A4; margin: 0;
			}
			#offer-letter-page @media print {
			.page {
			margin: 0; border: initial; border-radius: initial; width: initial; min-height: initial; box-shadow: initial; background: initial;
			page-break-after: always; 
			}
			}
			#offer-letter-page p {
			font-family: "Gill Sans", sans-serif !important; font-size: 13px !important;  width: fit-content!important;
			}
			#offer-letter-page .bold {
			font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: 163px !important;
			}
			#offer-letter-page .normal {
			font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: max-content!important;
			}
			#offer-letter-page .bold1 {
			font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: 40% !important;
			}
			#offer-letter-page .normal1 {
			font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: 40% !important;
			}
			#offer-letter-page table {
			border-collapse: separate; border-spacing: 0 7px;
			}
			#offer-letter-page h1 {
			margin-bottom: 62px!important;
			}
			#offer-letter-page .fit-content {
			justify-content: space-between;
			}
			#offer-letter-page .justify {
			width: 100%;
			}
			#offer-letter-page .justify p {
			text-align: justify;
			}
			#offer-letter-page .normal {
			text-align: justify;
			}
			#offer-letter-page input[type=text] {
			background-color: #f5fbff;
			}
			.alert-success {
			color: #1d7f58;
			background-color: #aae1cb;
			border-color: #95dabe;
			}
			.alert-error {
				color: #b14542;
				background-color: #fec0bf;
				border-color: #feb1af;
			}
			.alert {
			position: relative;
			padding: .75rem 1.25rem;
			margin-bottom: 1rem;
			border: 2px solid transparent;
			border-radius: .25rem;
			}
		</style>
	</head>
	<body id="offer-letter-page">
		@if(isset($success) && $success != '')
		<div class="alert alert-success" id="success-alert">
			{{$success ?? ''}}
		</div>
		@elseif(isset($error) && $error != '')
		<div class="alert alert-error" id="error-alert">
			{{$error ?? ''}}
		</div>
		@endif
		<div class="book">
			<div class="page">
				<div class="subpage justify" style="font-style:Serif;" id="justify">
					<h1 style="color:#034c84;">MILELE</h1>
					<p>{{$data->candidateDetails->offer_letter_code ?? ''}}</br>
						Date: @if($data->offer_letter_send_at != NULL){{Carbon\Carbon::parse($data->offer_letter_send_at)->format('F d,Y')}}@else{{now()->format('F d, Y')}}@endif</br></br>
						<strong>@if($data->gender == 1) Mr. @elseif($data->gender == 2) Ms. @endif {{$data->candidate_name ?? ''}}</strong></br> 
						Passport No: {{$data->candidateDetails->passport_number ?? ''}}</br>   
						Mob Phone: {{$data->candidateDetails->contact_number ?? ''}}</br></br>   
						Sub: <strong>Employment Offer</strong></br></br> 
						<strong>Dear {{$data->candidate_name ?? ''}},</strong></br></br> 
						We refer to your application submitted, and the subsequent Interview you had with us, we are pleased to offer you employment with our 
						Company, in the capacity of " <strong>{{$data->candidateDetails->designation->name ?? ''}}</strong> ".</br></br> 
						This employment offer is subject to the condition that we are able to obtain from the Ministry of Labor and Social Affairs and the 
						Naturalization and Immigration administration at the Ministry of Interior an entry permit for your employment under our Sponsorship.</br></br> 
						Your employment with us shall be pursuant to the following terms and conditions.</br></br> 
					<table>
						<tbody>
							<tr>
								<td class="bold"><strong>Basic Salary</strong></td>
								<td class="normal">AED {{$data->candidateDetails->basic_salary}}/- p.m. ( {{$inwords['basic_salary'] ?? $data->inwords_basic_salary ?? ''}} Only )</td>
							</tr>
							<tr>
								<td class="bold"><strong>Other Allowance</strong></td>
								<td class="normal">AED {{$data->candidateDetails->other_allowances}}/- p.m. ( {{$inwords['other_allowances'] ?? $data->inwords_other_allowances ?? ''}} Only )</td>
							</tr>
							<tr>
								<td class="bold"><strong>Total Salary</strong></td>
								<td class="normal">AED {{$data->candidateDetails->total_salary}}/- p.m. ( {{$inwords['total_salary'] ?? $data->inwords_total_salary ?? ''}} Only )</td>
							</tr>
							<tr>
								<td class="bold"><strong>Place of Work</strong></td>
								<td class="normal">You will be required to perform your duties in the Emirates of Dubai, U.A.E., or in any other Emirates of the
									United Arab Emirates, as we may deem fit at our sole discretion.
								</td>
							</tr>
							<tr>
								<td class="bold"><strong>Leave</strong></td>
								<td class="normal">30 days paid leave upon completion of each year of service with us, subject to the Employer having the sole 
									right to determine the date of such vacation in accordance with work needs at the discretion of the Employer.
								</td>
							</tr>
							<tr>
								<td class="bold"><strong>Medical</strong></td>
								<td class="normal">Medical/Health Insurance will be provided for the candidate itself, not for the family and children and this
									will be done while the employment visa will be processed.
								</td>
							</tr>
							<tr>
								<td class="bold"><strong>Probation Period</strong></td>
								<td class="normal">You will be on probation period for {{$data->candidateDetails->probation_duration_in_months}} months during which your services may be terminated with 14 days’ 
									notice period by employer. During the probationary period, employee has the right to terminate the contract with 30 days’
									notice. During the probation period, employees will not be entitled to leave and other permanent employee benefits.
								</td>
							</tr>
							<tr>
								<td class="bold"><strong>End of Service Benefits</strong></td>
								<td class="normal">You will be entitled to the end of service benefits in accordance with the UAE Labor Law. The candidate 
									should inform written notice period to the HR department before 30 days from the leaving date.
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="page">
				<div class="subpage justify" id="justify1">
					<table>
						<tbody>
							<tr>
								<td class="bold"><strong>Secrecy</strong></td>
								<td class="normal">You should not divulge any information which the company considers trade secrets to any individual or 
									organization. Breach of this regulation will result in immediate termination of your employment and forfeit of the service 
									benefit and may lead to appropriate legal action as per UAE Law in force.
								</td>
							</tr>
							<tr>
								<td class="bold"><strong>General</strong></td>
								<td class="normal">Notwithstanding any of the above terms and conditions under which you will be required to work shall conform
									in all respects with the UAE Labor Laws.
								</td>
							</tr>
							<tr>
								<td class="bold"><strong>Acknowledgement</strong></td>
								<td class="normal">Should you agree with the terms and conditions stated above, please sign, and return a copy of this letter in
									token of our acceptance for further necessary action from our end. This offer letter is valid only for 3 (three) days from 
									the date of this letter unless the same is not accepted, signed, and returned to the undersigned.
								</td>
							</tr>
						</tbody>
					</table>
					<p>
						<strong>
						Employees are prohibited from discussing their own salary or terms and conditions of employment with other employees inside and 
						outside the company. In case you violate this instruction, this letter will be cancelled, and company may terminate the employee 
						with immediate effect.
						</strong>
					</p>
					</br>
					</br>
					<p><strong>Thanking you,</strong></p>
					</br>
					<table>
						<tbody>
							<tr>
								<td class="bold1">
									<strong>{{$data->candidateDetails->offerLetterHr->name ?? $hr->handover_to_name ?? ''}}</strong></br></br>
									HR Manager</br></br>
									@if($data->offer_letter_send_at == NULL && isset($data->isAuth) && $data->isAuth == 1)
									<button type="button" style="border-radius:5px; padding-bottom:5px; color: #fff; background-color: #034c84; border-color: #034c84; padding-top:5px;">
									<a href="{{route('candidate-offer-letter.send',$data->id)}}" style="color:white;">Send Offer Letter</a>
									</button>
									@endif
								</td>
								<td class="normal1">
									<strong>Read and accepted:</strong></br></br> 
									Name: <strong> @if($data->gender == 1) Mr. @elseif($data->gender == 2) Ms. @endif {{$data->candidate_name ?? ''}}</strong></br>
									@if($data->offer_letter_send_at == NULL && isset($data->isAuth) && $data->isAuth == 1)
									Date: ………………… </br> 
									Signature:…………………	                                     
									@endif
								</td>
							</tr>
							<tr>
								<td class="bold1">
								</td>
								<td class="normal1">
									@if($data->offer_letter_send_at != NULL && isset($data->isAuth) && $data->isAuth == 0 && $data->candidateDetails->offer_sign == '')
									<form class="w3-container" action="{{route('offerletter.signed')}}" method="POST" id="candidatepersonalInfoForm"
										name="DAFORM"  enctype="multipart/form-data" target="_self">
										@csrf
										<div class="row">
											<div class="col-xxl-12 col-lg-12 col-md-12">
												<div class="row">
													<div class="col-xxl-12 col-lg-12 col-md-12">
														<div>
															<label for="request_date" class="col-form-label text-md-end">{{ __('Date') }}</label> : {{\Carbon\Carbon::parse(Carbon\Carbon::now())->format('d M Y')}}
														</div>
													</div>
													<div class="col-xxl-12 col-lg-12 col-md-12">
														<label for="request_date" class="col-form-label text-md-end">{{ __('Signature') }} :</label>
														<input type="text" readonly name="signature" id="signature" value="" style="border:none;">
														<input type="hidden" name="id" value="{{$data->id}}">
													</div>
												</div>
												<div id="signature-pad" class="m-signature-pad">
													<div class="m-signature-pad--body">
														<canvas id="signature_canvas"  class="signature-pad form-control @error('signature') is-invalid @enderror"></canvas>
													</div>
												</div>
											</div>
										</div>
										</br>
										<div class="row">
											<div class="col-xxl-6 col-lg-6 col-md-6" style="float:left;">
												<a id="resetSignature" class="btn btn-sm" style="padding-top:5px; padding-bottom:5px; padding-right:5px; padding-left:5px; border-radius:5px; background-color: lightblue; float:left;">Reset Signature</a>
												<button type="submit" id="saveSignature" class="btn btn-sm" style="margin-left:10px; padding-top:3px; padding-bottom:3px; padding-right:3px; padding-left:3px; border-radius:5px; border-color:#fbcc34; background-color: #fbcc34; float:right;">Submit Signature</button>     
											</div>
										</div>
									</form>
									@elseif($data->candidateDetails->offer_signed_at != NULL && isset($data->isAuth) && $data->isAuth == 2)
									Date: {{Carbon\Carbon::parse($data->candidateDetails->offer_signed_at)->format('F d,Y')}}</br>
									<table>
										<tbody>
											<tr>
												<td>Signature:</td>
												<td><img src="{{$data->candidateDetails->offer_sign}}" style="width:35%; height:35%;" alt="Red dot" /></td>
											</tr>
										</tbody>
									</table>
									@endif
									@php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-offer-letter-signature']);
									@endphp                    
									@if($hasPermission && isset($data->canVerifySign) && $data->canVerifySign == true && $data->offer_letter_verified_at == NULL && $data->offer_letter_verified_by == NULL && $data->candidateDetails->offer_sign != NULL)
									<table>
										<tbody>
											<tr>
												<button type="button" class="btn btn-success btn-verify-offer-letter-sign"
													data-id="{{ $data->id }}" data-status="approved"><i class="fa fa-check" aria-hidden="true"></i> Verify Signature</button>
											</tr>
										</tbody>
									</table>
									@endif 
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
		<script type="text/javascript">
			let canvas = document.querySelector('.signature-pad');
			let signatureSaveButton = document.getElementById('saveSignature');
			let signatureResetButton = document.getElementById('resetSignature');
			let signatureInput = document.querySelector('input[name="signature"]');
			let signaturePad = new SignaturePad(canvas);
			signatureResetButton.addEventListener('click', function(event) {
			    signaturePad.clear();
			    signatureInput.value = '';
			    event.preventDefault();
			    return false; 
			});
			signatureSaveButton.addEventListener('click', function(event) {
			    let signatureBlank = signaturePad.isEmpty();
			    if (!signatureBlank) {
			        signatureUrl = signaturePad.toDataURL();
			        signatureInput.value = signatureUrl;
			        $("div.error-messages span").html(''); 
			    }
			    $(signatureInput).valid(); 
			    // event.preventDefault();
			    // return false; 
			});
			$(document).ready(function() {  
			    var wrapper = document.getElementById("signature-pad"),
			    canvas = wrapper.querySelector("canvas"),
			    signaturePad;
			    var signaturePad = new SignaturePad(canvas);
			    signaturePad.minWidth = 1; 
			    signaturePad.maxWidth = 5; 
			    signaturePad.penColor = "#000000"; 
			    signaturePad.backgroundColor = "#FFFFFF"; 
			    function resizeCanvas() {
			        var oldContent = signaturePad.toData();
			        var ratio = Math.max(window.devicePixelRatio || 1, 1);
			        canvas.width = canvas.offsetWidth * ratio;
			        canvas.height = canvas.offsetHeight * ratio;
			        canvas.getContext("2d").scale(ratio, ratio);
			        signaturePad.clear();
			        signaturePad.fromData(oldContent);
			
			        let signaturePad = new SignaturePad(canvas);
			        signaturePad.clear();
			    }
			    window.onresize = resizeCanvas;
			    resizeCanvas();
			    $.validator.addMethod('signaturePresent', function(value, element) {
			        console.log('Checking...');
			        return this.optional(element) || signaturePad.isEmpty();
			    }, "Please provide your signature...");  
			    $('#candidatepersonalInfoForm').validate({ 
			        rules: {
			            signature: {
			                required: true,
			            }
			        },
			    });
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
			                    success: function (data) {	console.log('hi');
			                        console.log(data);						
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
			});
		</script>
	</body>
</html>