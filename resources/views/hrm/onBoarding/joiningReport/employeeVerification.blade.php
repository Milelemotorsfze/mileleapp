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
        <!-- <style>#signature_canvas { background-color: rgba(158, 167, 184, 0.2); }</style> -->
        <style type="text/css">
            canvas.style.background = "red"; 
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
                                        <center>Employee Joining Report</center>
                                    </h4>
                                </div>
                                <div class="card-body">            
                                        <div class="row">
                                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Employee Details</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Employee Name :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->employee->first_name ?? '' }} {{ $data->employee->last_name ?? '' }}</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Employee Code :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->employee->employee_code ?? '' }}</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Designation :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->employee->designation->name ?? '' }}</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Department :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->employee->department->name ?? '' }}</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Reporting Manager :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->reportingManager->name ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Joining Details</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Joining Type :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>@if($data->trial_period_joining_date) Trial Period Joining @elseif($data->permanent_joining_date) Permanent Joining @endif</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Joining Date :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>
                                                                @if($data->trial_period_joining_date)
                                                                {{\Carbon\Carbon::parse($data->trial_period_joining_date)->format('d M Y')}}
                                                            @elseif($data->permanent_joining_date)
                                                            {{\Carbon\Carbon::parse($data->permanent_joining_date)->format('d M Y')}}
                                                            @endif</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Location :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->permanentJoiningLocation->name ?? '' }}</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Remarks :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span>{{ $data->remarks ?? '' }}</span>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Prepared By :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span>{{ $data->preparedBy->name ?? '' }}</span>
                                                        </div>
                                                        @if($data->comments_by_employee != NULL)
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Remarks By Employee :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span>{{ $data->comments_by_employee ?? '' }}</span>
                                                        </div>
                                                        @endif
                                                        @if($data->employee_action_at != NULL)
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Employee Verified At :</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                        <span>
                                                            @if($data->employee_action_at != '')
                                                            {{\Carbon\Carbon::parse($data->employee_action_at)->format('d M y, H:i:s')}}
                                                            @endif
                                                        </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                        </div> 
                                        @if($data->employee_action_at == NULL)
                                        <form class="w3-container" action="{{route('employee_joining_report.verified')}}" method="POST" id="candidatepersonalInfoForm"
                                            name="DAFORM"  enctype="multipart/form-data" target="_self">
                                            @csrf                                      
                                            <div class="row">   
                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <div class="row">
                                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                                            <label for="comments_by_employee" class="col-form-label text-md-end">{{ __('Employee Remarks(If Any)') }}</label>
                                                        </div>
                                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                                            <input type="hidden" name="id" value="{{$data->id}}">
                                                            <textarea rows="5" name="comments_by_employee" placeholder="Enter Employee Remarks(If Any)" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                                              
                                            </div>
                                            </br>
                                            <div class="row">   
                                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                                    <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Click Here To Verify</button>
                                                </div>
                                            </div>
                                        </form>
                                        @endif
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
                    id: {
                        required: true,
                        lettersonly: true,
                    },
                },
            });
        </script>
    </body>
</html>