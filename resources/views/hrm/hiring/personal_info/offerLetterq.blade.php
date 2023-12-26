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
    <!-- <div class="card-header" style="background-color:#005ba1!important;">
        <div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
            <img src="{{ asset('logo.png') }}" width="20" height="40" alt="Logo" class="mx-auto">
        </div>
        <h1 class="card-title" style="color:white!important;">
            <center>MILELE</center>
        </h1>
    </div> -->
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" style="background-color:#005ba1!important;">
                                    <div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
                                        <img src="{{ asset('logo.png') }}" width="20" height="40" alt="Logo" class="mx-auto">
                                    </div>
                                    <h1 class="card-title" style="color:white!important;">
                                        <center>MILELE</center>
                                    </h1>
                                </div>
                                <div class="card-body">
                                    <form class="w3-container" action="{{route('candidate.storePersonalinfo')}}" method="POST" id="candidateOfferLetter"
                                        name="DAFORM"  enctype="multipart/form-data" target="_self">
                                        @csrf

MM/OL/00214/2023</br>
Date: December 19, 2023</br></br>
[Candidate Name]</br></br> 
Passport No:</br>   
Mob Phone:</br></br>   
Sub: Employment Offer</br></br> 
Dear [Candidate Name],</br></br> 
We refer to your application submitted, and the subsequent Interview you had with us, we are pleased to offer you employment with our Company, in the capacity 
of "[Job Position]".</br></br> 
This employment offer is subject to the condition that we are able to obtain from the Ministry of Labor and Social Affairs and the Naturalization and 
Immigration administration at the Ministry of Interior an entry permit for your employment under our Sponsorship.</br></br> 
Your employment with us shall be pursuant to the following terms and conditions.</br></br>  
Basic Salary 		AED XXXX/- p.m. (XXXX Dirhams Only)</br></br> 
Other Allowance		AED XXXX/- p.m. (XXXX Dirhams Only)</br></br> 
Total Salary 		AED XXXX/- p.m. (XXXX Dirhams Only)</br></br>                                                        
Place of Work	You will be required to perform your duties in the Emirates of Dubai, U.A.E., or in any other Emirates of the United Arab Emirates, as we may 
deem fit at our sole discretion.</br></br>
Leave	30 days paid leave upon completion of each year of service with us, subject to the Employer having the sole right to determine the date of such vacation 
in accordance with work needs at the discretion of the Employer.</br></br> 
Medical 	Medical/Health Insurance will be provided for the candidate itself, not for the family and children and this will be done while the employment visa 
will be processed.</br></br> 
Probation period	You will be on probation period for X months during which your services may be terminated with 14 days’ notice period by employer. During 
the probationary period, employee has the right to terminate the contract with 30 days’ notice. During the probation period, employees will not be entitled to 
leave and other permanent employee benefits.</br></br> 
End of Service Benefits 	You will be entitled to the end of service benefits in accordance with the UAE Labor Law. The candidate should inform written notice
period to the HR department before 30 days from the leaving date.</br></br> 
Secrecy 	You should not divulge any information which the company considers trade secrets to any individual or organization. Breach of this regulation will 
result in immediate termination of your employment and forfeit of the service benefit and may lead to appropriate legal action as per UAE Law in force.</br></br> 
General 	Notwithstanding any of the above terms and conditions under which you will be required to work shall conform in all respects with the UAE Labor Laws.</br></br>  
Acknowledgement 	Should you agree with the terms and conditions stated above, please sign, and return a copy of this letter in token of our acceptance for further 
necessary action from our end. This offer letter is valid only for 3 (three days from the date of this letter unless the same is not accepted, signed, and 
returned to the undersigned.</br></br> 
Employees are prohibited from discussing their own salary or terms and conditions of employment with other employees inside and outside the company. In case you
 violate this instruction, this letter will be cancelled, and company may terminate the employee with immediate effect.</br></br>
Thanking you,</br></br>
Abdul Rahman Malik</br>				            Read and accepted: 
HR Manager</br>					                Name: [Candidate Name] 

                          Sign:…………………..		 

                                         Date: ………………… 
                                        <!-- <div class="row">
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
                                        <br> -->
                                        <!-- <input name="id" value="" hidden>
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">
                                                    <center>Primary Details</center>
                                                </h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="first_name" class="col-form-label text-md-end">{{ __('First Name') }}</label>
                                                        <input id="first_name" type="text" class="form-control widthinput @error('first_name') is-invalid @enderror" name="first_name"
                                                            placeholder="First Name" value="" autocomplete="first_name" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="last_name" class="col-form-label text-md-end">{{ __('Last Name') }}</label>
                                                        <input id="last_name" type="text" class="form-control widthinput @error('last_name') is-invalid @enderror" name="last_name"
                                                            placeholder="Last Name" value="" autocomplete="last_name" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="name_of_father" class="col-form-label text-md-end">{{ __("Father’s Full Name" ) }}</label>
                                                        <input id="name_of_father" type="text" class="form-control widthinput @error('name_of_father') is-invalid @enderror" name="name_of_father"
                                                            placeholder="Father’s Full Name" value="" autocomplete="name_of_father" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="name_of_mother" class="col-form-label text-md-end">{{ __("Mother’s Full Name" ) }}</label>
                                                        <input id="name_of_mother" type="text" class="form-control widthinput @error('name_of_mother') is-invalid @enderror" name="name_of_mother"
                                                            placeholder="Mother’s Full Name" value="" autocomplete="name_of_mother" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
                                                        <div class="dropdown-option-div">
                                                            <span class="error">* </span>
                                                            <label for="marital_status" class="col-form-label text-md-end">{{ __('Choose Marital Status') }}</label>
                                                            <select name="marital_status" id="marital_status" class="form-control widthinput" autofocus>
                                                                <option></option>
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="passport_number" class="col-form-label text-md-end">{{ __('Passport Number') }}</label>
                                                        <input id="passport_number" type="text" class="form-control widthinput @error('passport_number') is-invalid @enderror" name="passport_number"
                                                            placeholder="Passport Number" value="" autocomplete="passport_number" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="passport_expiry_date" class="col-form-label text-md-end">{{ __('Passport Expiry Date') }}</label>
                                                        <input id="passport_expiry_date" type="date" min="" class="form-control widthinput @error('passport_expiry_date') is-invalid @enderror" name="passport_expiry_date"
                                                            value="" autocomplete="passport_expiry_date" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="educational_qualification" class="col-form-label text-md-end">{{ __('Educational Qualification') }}</label>
                                                        <input id="educational_qualification" type="text" class="form-control widthinput @error('educational_qualification') is-invalid @enderror" name="educational_qualification"
                                                            placeholder="Educational Qualification" value="" autocomplete="educational_qualification" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="year_of_completion" class="col-form-label text-md-end">{{ __('Year of Completion') }}</label>
                                                        <input id="year_of_completion" type="number" min="1950" max="2023" step="1" class="form-control widthinput @error('year_of_completion') is-invalid @enderror" name="year_of_completion"
                                                            placeholder="Year of Completion" value="" autocomplete="year_of_completion" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="religion" class="col-form-label text-md-end">{{ __('Choose Religion') }}</label>
                                                        <select name="religion" id="religion" multiple="true" class="form-control widthinput" autofocus>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="dob" class="col-form-label text-md-end">{{ __('Date Of Birth') }}</label>
                                                        <input id="dob" type="date" class="form-control widthinput @error('dob') is-invalid @enderror" name="dob"
                                                            value="" autocomplete="dob" autofocus>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-6 col-md-6">
                                                        <span class="error">* </span>
                                                        <label for="language_id" class="col-form-label text-md-end">{{ __('Choose Spoken Languages') }}</label>
                                                        <select name="language_id[]" id="language_id" multiple="true" class="form-control widthinput" autofocus>
                                                        
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                       
                                        
                                        <!-- <div class="row">
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-xxl-6 col-lg-6 col-md-6">
                                                        <label for="request_date" class="col-form-label text-md-end">{{ __('Signature') }} :</label>
                                                        <input type="text" name="signature" id="signature" value="" style="border:none;">
                                                    </div>
                                                    <div class="col-xxl-6 col-lg-6 col-md-6">
                                                        <div style="float:right;">
                                                            <label for="request_date" class="col-form-label text-md-end">{{ __('Date') }}</label> : {{\Carbon\Carbon::parse(Carbon\Carbon::now())->format('d M Y')}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="signature-pad" class="m-signature-pad">
                                                    <div class="m-signature-pad--body">
                                                        <canvas id="signature_canvas"  class="signature-pad form-control @error('signature') is-invalid @enderror"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </br> -->
                                        <!-- <div class="row">
                                            <div class="col-xxl-6 col-lg-6 col-md-6" style="float:left;">
                                                <a id="resetSignature" class="btn btn-sm" style="background-color: lightblue; float:left;">Reset Signature</a>
                                                <button id="saveSignature" class="btn btn-sm" style="background-color: #fbcc34; float:left; margin-left:10px;">Save Signature</button>     
                                            </div>
                                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                                <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
                                            </div>
                                        </div> -->
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
                event.preventDefault();
                return false; 
            });
            $(document).ready(function() {                              
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
                }
                window.onresize = resizeCanvas;
                resizeCanvas();             
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
            $.validator.addMethod('signaturePresent', function(value, element) {
                console.log('Checking...');
                return this.optional(element) || signaturePad.isEmpty();
            }, "Please provide your signature...");
	        $('#candidateOfferLetter').validate({ 
                rules: {
                    signature: {
                        required: true,
                    }
                },
            });
        </script>
    </body>
</html>