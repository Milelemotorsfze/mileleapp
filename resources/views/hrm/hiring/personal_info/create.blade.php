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
                position: relative;
                left: 0;
                top: 0;
                width: 100%;
                height: 250px;
                border: 1px solid #CCCCCC;
            }
            .card-header {
				background-color:#e8f3fd!important;
			}
			.btn_round
			{
				margin-top: 34px!important;
				padding-top: 0px!important;
			}
        </style>
        <style>
            .icon-right {
                z-index: 10;
                position: absolute;
                right: 0;
                top: 0;
            }
            .spanSub
            {
            background-color: #e4e4e4;
            border: 1px solid #aaa;
            border-radius: 4px;
            box-sizing: border-box;
            display: inline;
            margin-left: 5px;
            margin-top: 5px;
            padding: 0 10px 0 20px;
            position: relative;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
            white-space: nowrap;
            }
            .error
            {
            color: #FF0000;
            }
            .iti
            {
            width: 100%;
            }
            .btn_round
            {
            width: 30px;
            height: 30px;
            display: inline-block;
            text-align: center;
            line-height: 35px;
            margin-left: 10px;
            margin-top: 28px;
            border: 1px solid #ccc;
            color:#fff;
            background-color: #fd625e;
            border-radius:5px;
            cursor: pointer;
            padding-top:7px;
            }
            .btn_round:hover
            {
            color: #fff;
            background: #fd625e;
            border: 1px solid #fd625e;
            }
            .btn_content_outer
            {
            display: inline-block;
            width: 85%;
            }
            .close_c_btn
            {
            width: 30px;
            height: 30px;
            position: absolute;
            right: 10px;
            top: 0px;
            line-height: 30px;
            border-radius: 50%;
            background: #ededed;
            border: 1px solid #ccc;
            color: #ff5c5c;
            text-align: center;
            cursor: pointer;
            }
            .add_icon
            {
            padding: 10px;
            border: 1px dashed #aaa;
            display: inline-block;
            border-radius: 50%;
            margin-right: 10px;
            }
            .add_group_btn
            {
            display: flex;
            }
            .add_group_btn i
            {
            font-size: 32px;
            display: inline-block;
            margin-right: 10px;
            }
            .add_group_btn span
            {
            margin-top: 8px;
            }
            .add_group_btn,
            .clone_sub_task
            {
            cursor: pointer;
            }
            .sub_task_append_area .custom_square
            {
            cursor: move;
            }
            .del_btn_d
            {
            display: inline-block;
            position: absolute;
            right: 20px;
            border: 2px solid #ccc;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            font-size: 18px;
            }
            body
            {
            font-family: Arial;
            }
            /* Style the tab */
            .tab
            {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            }
            /* Style the h6 inside the tab */
            .tab h6
            {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
            }
            /* Change background color of h6 on hover */
            .tab h6:hover
            {
            background-color: #ddd;
            }
            /* Create an active/current tablink class */
            .tab h6.active
            {
            background-color: #ccc;
            }
            /* Style the tab content */
            .tabcontent
            {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
            }
            .paragraph-class
            {
            margin-top: .25rem;
            font-size: 80%;
            color: #fd625e;
            }
            .required-class
            {
            margin-top: .25rem;
            font-size: 80%;
            color: #fd625e;
            }
            .overlay
            {
            position: fixed; /* Positioning and size */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(128,128,128,0.5); /* color */
            display: none; /* making it hidden by default */
            }
            .widthinput
            {
            height:32px!important;
            }
            input:focus
            {
            border-color: #495057!important;
            }
            select:focus
            {
            border-color: #495057!important;
            }
            a:focus
            {
            border-color: #495057!important;
            }
        </style>
    </head>
    <body data-layout="horizontal">
        <div class="card-header" style="background-color:#005ba1!important;">
            <div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
                <img src="{{ asset('logo.png') }}" width="20" height="40" alt="Logo" class="mx-auto">
            </div>
            <h1 class="card-title" style="color:white!important;"><center>MILELE</center></h1>
		</div>
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header" style="background-color:#d2e7f9!important;">
										<h4 class="card-title"><center>CANDIDATE PERSONAL INFORMATION & DOCUMENTS SHAREING FORM</center></h4>
									</div>
                                    <div class="card-body">
                                        <form class="w3-container" action="{{route('candidate.storePersonalinfo')}}" method="POST" id="candidatepersonalInfoForm"
                                            name="DAFORM"  enctype="multipart/form-data" target="_self">	
                                            <!-- onSubmit="submitForm();" -->
                                            <!-- <form id="candidatepersonalInfoForm" name="candidatepersonalInfoForm" enctype="multipart/form-data" method="POST" action="{{route('employee-hiring-request.store-or-update',1)}}"> -->
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
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <center>Primary Details</center>
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <input name="id" value="{{$candidate->id}}" hidden>
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
                                                                    @foreach($masterMaritalStatus as $maritalStatus)
                                                                    <option value="{{$maritalStatus->id}}">{{$maritalStatus->name}}</option>
                                                                    @endforeach
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
                                                            <!-- {{\Carbon\Carbon::parse(Carbon\Carbon::today())}} -->
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
                                                                @foreach($masterReligion as $religion)
                                                                <option value="{{$religion->id}}">{{$religion->name}}</option>
                                                                @endforeach
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
                                                                @foreach($masterLanguages as $masterLanguage)
                                                                <option value="{{$masterLanguage->id}}" {{ $candidate && $candidate->candidateDetails && $candidate->candidateDetails->candidateLanguages && in_array($masterLanguage->id, $candidate->candidateDetails->candidateLanguages->pluck('language_id')->toArray()) ? 'selected' : '' }}>{{$masterLanguage->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <center>Address and Contact Details in UAE</center>
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xxl-6 col-lg-6 col-md-6">
                                                            <span class="error">* </span>
                                                            <label for="address_uae" class="col-form-label text-md-end">{{ __('Address in UAE') }}</label>													
                                                            <textarea rows="5" id="address_uae" type="text" class="form-control @error('address_uae') is-invalid @enderror"
                                                                name="address_uae" placeholder="Address in UAE" value="{{ old('address_uae') }}"  autocomplete="address_uae"
                                                                autofocus></textarea>
                                                        </div>
                                                        <div class="col-xxl-6 col-lg-6 col-md-6 mt-4">
                                                            <div class="row">
                                                                <div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
                                                                    <span class="error">* </span>
                                                                    <label for="residence_telephone_number" class="col-form-label text-md-end">{{ __('Residence Telephone Number') }}</label>
                                                                </div>
                                                                <div class="col-xxl-8 col-lg-8 col-md-8 mt-2">
                                                                <input id="residence_telephone_number" type="tel" class="widthinput contact form-control @error('residence_telephone_number[full]')
                                                                    is-invalid @enderror" name="residence_telephone_number[main]" placeholder="Enter Alternative Contact Number"
                                                                    value="{{$candidate->candidateDetails->residence_telephone_number ?? ''}}" autocomplete="residence_telephone_number[full]" autofocus
                                                                    >
                                                                </div>
                                                                <div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
                                                                    <span class="error">* </span>
                                                                    <label for="contact_number" class="col-form-label text-md-end">{{ __('Mobile Number:') }}</label>
                                                                </div>
                                                                <div class="col-xxl-8 col-lg-8 col-md-8 mt-2">
                                                                    <input id="contact_number" type="tel" class="widthinput form-control @error('contact_number[full]') is-invalid @enderror"
                                                                        name="contact_number[main]" placeholder="Mobile Number" value="{{old('hiddencontact')}}"
                                                                        autocomplete="contact_number[main]" autofocus>
                                                                </div>
                                                                <div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
                                                                    <span class="error">* </span>
                                                                    <label for="personal_email_address" class="col-form-label text-md-end">{{ __('Personal Email Address') }}</label>
                                                                </div>
                                                                <div class="col-xxl-8 col-lg-8 col-md-8 mt-2">
                                                                    <input id="personal_email_address" type="text" class="form-control widthinput @error('personal_email_address') is-invalid @enderror" name="personal_email_address"
                                                                        placeholder="Personal Email Address" value="" autocomplete="personal_email_address" autofocus>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <center>Dependents</center>
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xxl-3 col-lg-6 col-md-6">
                                                            <label for="spouse_name" class="col-form-label text-md-end">{{ __('Spouse Name') }}</label>
                                                            <input id="spouse_name" type="text" class="form-control widthinput @error('spouse_name') is-invalid @enderror" name="spouse_name"
                                                                placeholder="Spouse Name" value="" autocomplete="spouse_name" autofocus>
                                                        </div>
                                                        <div class="col-xxl-3 col-lg-6 col-md-6">
                                                            <label for="spouse_passport_number" class="col-form-label text-md-end">{{ __('Spouse Passport Number') }}</label>
                                                            <input id="spouse_passport_number" type="text" class="form-control widthinput @error('spouse_passport_number') is-invalid @enderror" name="spouse_passport_number"
                                                                placeholder="Spouse Passport Number" value="" autocomplete="spouse_passport_number" autofocus>
                                                        </div>
                                                        <div class="col-xxl-2 col-lg-6 col-md-6">
                                                            <label for="spouse_passport_expiry_date" class="col-form-label text-md-end">{{ __('Spouse Passport Expiry Date') }}</label>
                                                            <input id="spouse_passport_expiry_date" type="date" class="form-control widthinput @error('spouse_passport_expiry_date') is-invalid @enderror" name="spouse_passport_expiry_date"
                                                                value="" autocomplete="spouse_passport_expiry_date" autofocus>
                                                        </div>
                                                        <div class="col-xxl-2 col-lg-6 col-md-6">
                                                            <label for="spouse_dob" class="col-form-label text-md-end">{{ __('Spouse Date Of Birth') }}</label>
                                                            <input id="spouse_dob" type="date" class="form-control widthinput @error('spouse_dob') is-invalid @enderror" name="spouse_dob"
                                                                value="" autocomplete="spouse_dob" autofocus>
                                                        </div>
                                                        <div class="col-xxl-2 col-lg-6 col-md-6">
                                                            <label for="spouse_nationality" class="col-form-label text-md-end">{{ __('Choose Spouse Nationality') }}</label>
                                                            <select name="spouse_nationality" id="spouse_nationality" multiple="true" class="form-control widthinput" onchange="" autofocus>
                                                                @foreach($masterNationality as $nationality)
                                                                <option value="{{$nationality->id}}">{{$nationality->nationality ?? $nationality->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-12 form_field_outer p-0" id="child">
                                                        <!-- child Row Apend Here -->
                                                    </div>
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                        <a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn">
                                                        <i class="fa fa-plus" aria-hidden="true"></i> Add Child</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <center>Contact in case of Emergency (UAE)</center>
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-12 form_field_outer_contact_uae p-0" id="emergency_contact_uae">
                                                        <div class="row form_field_outer_row">
                                                            <div class="col-xxl-3 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="ecu_name" class="ecu_name col-form-label text-md-end">{{ __('Name') }}</label>
                                                                <input id="ecu_name_1" type="text" class="form-control widthinput @error('ecu_name') is-invalid @enderror" 
                                                                name="ecu[1][name]" data-index=1 placeholder="emergency Contact Person UAE" value="" autofocus>
                                                            </div>
                                                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="ecu_relation" class="col-form-label text-md-end">{{ __('Relation') }}</label>
                                                                <select name="ecu[1][relation]" data-index=1 id="ecu_relation_1" class="form-control widthinput" autofocus>
                                                                    <option></option>
                                                                    @foreach($masterRelations as $relation)
                                                                    <option value="{{$relation->id}}">{{$relation->name}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="ecu_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                                                <input id="ecu_email_1" type="text" class="form-control widthinput @error('ecu_email') is-invalid @enderror" 
                                                                name="ecu[1][email_address]" data-index=1 placeholder="Email" value="" autocomplete="ecu_email" autofocus>
                                                            </div>
                                                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="ecu_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                                                <input id="ecu_contact_number_1" type="tel" class="form-control widthinput @error('ecu_contact_number[main]') is-invalid @enderror" 
                                                                name="ecu[1][contact_number][main]" data-index=1 placeholder="Contact Number" value="" autocomplete="ecu_contact_number[main]" autofocus>
                                                            </div>
                                                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                                                <label for="ecu_alternative_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                                                                <input id="ecu_alternative_number_1" type="tel" class="form-control widthinput @error('ecu_alternative_number[main]') is-invalid @enderror" 
                                                                name="ecu[1][alternative_contact_number][main]" data-index=1 placeholder="Alternative Number" value="" autocomplete="ecu_alternative_number[main]" autofocus>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                        <a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn_contact_uae">
                                                        <i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        <center>Contact in case of Emergency (Home Country)</center>
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-12 form_field_outer_contact_home p-0" id="emergency_contact_home">
                                                        <div class="row form_field_outer_row">
                                                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="ech_home_country_address" class="col-form-label text-md-end">{{ __('Home Country Address') }}</label>													
                                                                <textarea rows="7" id="ech_home_country_address_1" type="text" class="form-control @error('ech_home_country_address') is-invalid @enderror"
                                                                    name="ech[1][home_country_address]" data-index=1 placeholder="Home Country Address" value="{{ old('ech_home_country_address') }}"  autocomplete="ech_home_country_address"
                                                                    autofocus></textarea>
                                                            </div>
                                                            <div class="col-xxl-5 col-lg-5 col-md-5 mt-4">
                                                                <div class="row">
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                                                        <span class="error">* </span>
                                                                        <label for="ech_name" class="col-form-label text-md-end">{{ __('Name') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                                                        <input id="ech_name_1" type="text" class="widthinput form-control @error('ech_name') is-invalid @enderror"
                                                                            name="ech[1][name]" data-index=1 placeholder="Name" value="{{old('ech_name')}}"
                                                                            autocomplete="ech_name" autofocus>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                                                        <span class="error">* </span>
                                                                        <label for="ech_relation" class="col-form-label text-md-end">{{ __('Relation:') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                                                        <select name="ech[1][relation]" data-index=1 id="ech_relation_1" class="form-control widthinput" onchange="" autofocus>
                                                                            <option></option>
                                                                            @foreach($masterRelations as $relation)
                                                                            <option value="{{$relation->id}}">{{$relation->name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                                                        <span class="error">* </span>
                                                                        <label for="ech_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                                                        <input id="ech_email_1" type="text" class="form-control widthinput @error('ech_email') is-invalid @enderror"
                                                                         name="ech[1][email]" data-index=1
                                                                            placeholder="Email" value="" autocomplete="ech_email" autofocus>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                                                        <span class="error">* </span>
                                                                        <label for="ech_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                                                        <input id="ech_contact_number_1" type="tel" class="form-control widthinput @error('ech_contact_number') is-invalid @enderror" 
                                                                        name="ech[1][contact_number][main]" data-index=1
                                                                            placeholder="Contact Number" value="" autocomplete="ech_contact_number" autofocus>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                                                        <label for="ech_alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Number') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                                                        <input id="ech_alternative_contact_number_1" type="tel" class="form-control widthinput @error('ech_alternative_contact_number') is-invalid @enderror" 
                                                                        name="ech[1][alternative_contact_number][main]" data-index=1
                                                                            placeholder="Alternative Contact Number" value="" autocomplete="ech_alternative_contact_number" autofocus>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                        <a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn_contact_home">
                                                        <i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title"><center>Upload Documents</center></h4>
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
                                                                        placeholder="Upload Trade License" accept="application/pdf, image/*">
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
                                                                        placeholder="Upload Passport (First & Second page)" accept="application/pdf, image/*">
                                                        </div>
                                                        <div class="col-xxl-3 col-lg-6 col-md-6">
                                                            <span class="error">* </span>
                                                            <label for="first_name" class="col-form-label text-md-end">{{ __('Attested Educational Documents') }}</label>
                                                            <input type="file" class="form-control" multiple id="educational-docs" name="educational_docs[]"
                                                                        placeholder="Upload Passport (First & Second page)" accept="application/pdf, image/*">
                                                        </div>
                                                        <div class="col-xxl-3 col-lg-6 col-md-6">
                                                            <span class="error">* </span>
                                                            <label for="first_name" class="col-form-label text-md-end">{{ __('Attested Professional Diplomas / Certificates') }}</label>
                                                            <input type="file" class="form-control" multiple id="professional-diploma-certificates" name="professional_diploma_certificates[]"
                                                                        placeholder="Upload Passport (First & Second page)" accept="application/pdf, image/*">
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
                                            <div class="row">
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
                                            </br>
                                            <input type="hidden" id="photo-file-delete" name="is_photo_delete" value="">
                                        <input type="hidden" id="visa-file-delete" name="is_visa_delete" value="">
                                        <input type="hidden" id="resume-file-delete" name="is_resume_delete" value="">   
                                        <input type="hidden" id="emirates-id-file-delete" name="is_emirates_id_delete" value=""> 
                                        <!-- <input type="hidden" id="deleted-files" name="deleted_files" value="">  -->
                                        <select hidden id="deleted-files" name="deleted_files[]" multiple="true">
        </select>
                                            <div class="row">
                                                <div class="col-xxl-6 col-lg-6 col-md-6" style="float:left;">
                                                    <a id="resetSignature" class="btn btn-sm" style="background-color: lightblue; float:left;">Reset Signature</a>
                                                    <button id="saveSignature" class="btn btn-sm" style="background-color: #fbcc34; float:left; margin-left:10px;">Save Signature</button>     
                                                </div>
                                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                                <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
                                            </div>
                                            </div>
                                        </form>	 
                                                                           
                                    </div>
                                    <div class="overlay"></div>
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
            var indexVal = 1;
            var oldNationalIdArr = [];
            var oldPassportArr = [];
            var oldProDipCertiArr = [];
            var oldEduDocsArr = [];

            let canvas = document.querySelector('.signature-pad');
            let signatureSaveButton = document.getElementById('saveSignature');
            let signatureResetButton = document.getElementById('resetSignature');
            let signatureInput = document.querySelector('input[name="signature"]');
            // Initialize a new signaturePad instance.
            let signaturePad = new SignaturePad(canvas);
            // Clear signature pad.
            signatureResetButton.addEventListener('click', function(event) {
                signaturePad.clear();
                signatureInput.value = '';
                event.preventDefault();
                return false; // prevent submission...
            });
            // Save signature pad as data url.
            signatureSaveButton.addEventListener('click', function(event) {
                let signatureBlank = signaturePad.isEmpty();
                if (!signatureBlank) {
                    signatureUrl = signaturePad.toDataURL();
                    // console.log(signatureUrl);
                    signatureInput.value = signatureUrl;
                    $("div.error-messages span").html(''); // Clear messages
                }
                $(signatureInput).valid(); // Call validation on the field after hitting "Save"
                event.preventDefault();
                return false; // prevent submission...
            });

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
                if (file.type.match("application/pdf")) {
                    document.getElementById('passport-size-photograph-label').textContent="Passport Size Photograph";
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFilePhotograph.appendChild(iframe);
                }
                else if (file.type.match("image/*")) {
                    document.getElementById('passport-size-photograph-label').textContent="Passport Size Photograph";
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFilePhotograph.appendChild(image);
                }
            });
            fileInputResume.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                while (previewFileResume.firstChild) {
                    previewFileResume.removeChild(previewFileResume.firstChild);
                }
                const file = files[0];
                if (file.type.match("application/pdf"))
                {
                    document.getElementById('resume-label').textContent="Resume";
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFileResume.appendChild(iframe);
                }
                else if (file.type.match("image/*"))
                {
                    document.getElementById('resume-label').textContent="Resume";
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFileResume.appendChild(image);
                }
            });
            fileInputVisa.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                while (previewFileVisa.firstChild) {
                    previewFileVisa.removeChild(previewFileVisa.firstChild);
                }
                const file = files[0];
                if (file.type.match("application/pdf"))
                {
                    document.getElementById('visa-label').textContent="Visa";
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFileVisa.appendChild(iframe);
                }
                else if (file.type.match("image/*"))
                {
                    document.getElementById('visa-label').textContent="Visa";
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFileVisa.appendChild(image);
                }
            });
            fileInputEmiratesId.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                while (previewFileEmiratesId.firstChild) {
                    previewFileEmiratesId.removeChild(previewFileEmiratesId.firstChild);
                }
                const file = files[0];
                if (file.type.match("application/pdf"))
                {
                    document.getElementById('emirates-id-label').textContent="Emirates ID";
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFileEmiratesId.appendChild(iframe);
                }
                else if (file.type.match("image/*"))
                {
                    document.getElementById('emirates-id-label').textContent="Emirates ID";
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFileEmiratesId.appendChild(image);
                }
            });
            fileInputPassport.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                document.getElementById('passport-label').textContent="Passport";
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match("application/pdf")) {
                        const objectUrl = URL.createObjectURL(file);
                        const iframe = document.createElement("iframe");
                        iframe.src = objectUrl;
                        previewFilePassport.appendChild(iframe);
                    } else if (file.type.match("image/*")) {
                        const objectUrl = URL.createObjectURL(file);
                        const image = new Image();
                        image.src = objectUrl;
                        previewFilePassport.appendChild(image);
                    }
                }
            });
            fileInputNationalId.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                document.getElementById('national-id-label').textContent="National ID";
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match("application/pdf")) {
                        const objectUrl = URL.createObjectURL(file);
                        const iframe = document.createElement("iframe");
                        iframe.src = objectUrl;
                        previewFileNationalId.appendChild(iframe);
                    } else if (file.type.match("image/*")) {
                        const objectUrl = URL.createObjectURL(file);
                        const image = new Image();
                        image.src = objectUrl;
                        previewFileNationalId.appendChild(image);
                    }
                }
            });
            fileInputEducationalDocs.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                document.getElementById('educational-docs-label').textContent="Attested Educational Documents";
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match("application/pdf")) {
                        const objectUrl = URL.createObjectURL(file);
                        const iframe = document.createElement("iframe");
                        iframe.src = objectUrl;
                        previewFileEducationalDocs.appendChild(iframe);
                    } else if (file.type.match("image/*")) {
                        const objectUrl = URL.createObjectURL(file);
                        const image = new Image();
                        image.src = objectUrl;
                        previewFileEducationalDocs.appendChild(image);
                    }
                }
            });
            fileInputProfDiploCertificates.addEventListener("change", function(event) {
                $('.preview-div').attr('hidden', false);
                const files = event.target.files;
                document.getElementById('professional-diploma-certificates-label').textContent="Attested Professional Diplomas / Certificates";
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.match("application/pdf")) {
                        const objectUrl = URL.createObjectURL(file);
                        const iframe = document.createElement("iframe");
                        iframe.src = objectUrl;
                        previewFileProfDiploCertificates.appendChild(iframe);
                    } else if (file.type.match("image/*")) {
                        const objectUrl = URL.createObjectURL(file);
                        const image = new Image();
                        image.src = objectUrl;
                        previewFileProfDiploCertificates.appendChild(image);
                    }
                }
            });
            $(document).ready(function() {                
                if(candidate.candidate_details != null) {
                    $("#first_name").val(candidate.candidate_details.first_name);
                    $("#last_name").val(candidate.candidate_details.last_name);
                    $("#name_of_father").val(candidate.candidate_details.name_of_father);
                    $("#name_of_mother").val(candidate.candidate_details.name_of_mother);
                    $("#marital_status").val(candidate.candidate_details.marital_status);
                    $("#passport_number").val(candidate.candidate_details.passport_number);
                    $("#passport_expiry_date").val(candidate.candidate_details.passport_expiry_date);
                    $("#educational_qualification").val(candidate.candidate_details.educational_qualification);
                    $("#religion").val(candidate.candidate_details.religion);
                    $("#dob").val(candidate.candidate_details.dob);
                    $("#address_uae").val(candidate.candidate_details.address_uae);
                    $("#residence_telephone_number").val(candidate.candidate_details.residence_telephone_number);
                    $("#contact_number").val(candidate.candidate_details.contact_number);
                    $("#personal_email_address").val(candidate.candidate_details.personal_email_address);
                    $("#spouse_name").val(candidate.candidate_details.spouse_name);
                    $("#spouse_passport_number").val(candidate.candidate_details.spouse_passport_number);
                    $("#spouse_passport_expiry_date").val(candidate.candidate_details.spouse_passport_expiry_date);
                    $("#spouse_dob").val(candidate.candidate_details.spouse_dob);
                    $("#spouse_nationality").val(candidate.candidate_details.spouse_nationality);
                    $("#signature").val(candidate.pif_sign);
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
                    if(candidate.candidate_details.candidate_children.length > 0) {
                        for(var i=1; i<=candidate.candidate_details.candidate_children.length; i++) {
                            addChild();
                            $("#child_name_"+i).val(candidate.candidate_details.candidate_children[i-1].child_name);
                            $("#child_passport_number_"+i).val(candidate.candidate_details.candidate_children[i-1].child_passport_number);
                            $("#child_passport_expiry_date_"+i).val(candidate.candidate_details.candidate_children[i-1].child_passport_expiry_date);
                            $("#child_dob_"+i).val(candidate.candidate_details.candidate_children[i-1].child_dob);
                            $("#child_nationality_"+i).val(candidate.candidate_details.candidate_children[i-1].child_nationality);
                        }
                    }
                    else {
                        addChild();
                    }
                    if(candidate.candidate_details.emergency_contact_u_a_e.length > 0) {
                        for(var i=1; i<=candidate.candidate_details.emergency_contact_u_a_e.length; i++) {
                            if(i !=1) {
                                addContactUAE();
                            }
                            $("#ecu_name_"+i).val(candidate.candidate_details.emergency_contact_u_a_e[i-1].name);
                            $("#ecu_relation_"+i).val(candidate.candidate_details.emergency_contact_u_a_e[i-1].relation);
                            $("#ecu_contact_number_"+i).val(candidate.candidate_details.emergency_contact_u_a_e[i-1].contact_number);
                            $("#ecu_alternative_number_"+i).val(candidate.candidate_details.emergency_contact_u_a_e[i-1].alternative_contact_number);
                            $("#ecu_email_"+i).val(candidate.candidate_details.emergency_contact_u_a_e[i-1].email_address);
                        }
                    }
                    if(candidate.candidate_details.emergency_contact_home_country.length > 0) {
                        for(var i=1; i<=candidate.candidate_details.emergency_contact_home_country.length; i++) {
                            if(i !=1) {
                                addContactHome();
                            }
                            $("#ech_name_"+i).val(candidate.candidate_details.emergency_contact_home_country[i-1].name);
                            $("#ech_relation_"+i).val(candidate.candidate_details.emergency_contact_home_country[i-1].relation);
                            $("#ech_contact_number_"+i).val(candidate.candidate_details.emergency_contact_home_country[i-1].contact_number);
                            $("#ech_alternative_contact_number_"+i).val(candidate.candidate_details.emergency_contact_home_country[i-1].alternative_contact_number);
                            $("#ech_email_"+i).val(candidate.candidate_details.emergency_contact_home_country[i-1].email_address);
                            $("#ech_home_country_address_"+i).val(candidate.candidate_details.emergency_contact_home_country[i-1].home_country_address);
                        }
                    }
                    if(candidate.candidate_details.image_path != '' || candidate.candidate_details.resume != '' || candidate.candidate_details.visa ||
                    candidate.candidate_details.emirates_id_file || candidate.candidate_details.candidate_passport.length > 0 ||
                    candidate.candidate_details.candidate_national_id.length > 0 || candidate.candidate_details.candidate_pro_dip_certi.length > 0 ||
                    candidate.candidate_details.candidate_edu_docs.length > 0) {
                        $('.preview-div').attr('hidden', false);
                    }
                    if(candidate.pif_sign != '') {
                        window.addEventListener("load", draw);
                        function draw() {
                            var ctx = document.querySelector('#signature_canvas').getContext('2d');
                            var img1 = new Image();
                            img1.onload = function() {
                                ctx.drawImage(img1, 40, 10);
                            }
                            img1.src = candidate.pif_sign; 
                        }
                    }
                }
                else {
                    addChild();
                }
                // main layout
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
                // canva for signature
                var wrapper = document.getElementById("signature-pad"),
                canvas = wrapper.querySelector("canvas"),
                signaturePad;
                var signaturePad = new SignaturePad(canvas);
                signaturePad.minWidth = 1; //minimale Breite des Stiftes
                signaturePad.maxWidth = 5; //maximale Breite des Stiftes
                signaturePad.penColor = "#000000"; //Stiftfarbe
                signaturePad.backgroundColor = "#FFFFFF"; //Hintergrundfarbe
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
                // function submitForm() {
                //     //Unterschrift in verstecktes Feld übernehmen
                //     document.getElementById('signature').value = signaturePad.toDataURL();
                // }

                // form inputs
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var yyyy = today.getFullYear();
                var dobMaxyyyy = today.getFullYear() - 14;
                var dobMinyyyy = today.getFullYear() - 60;
                today = yyyy+'-'+mm+'-'+dd;
                dobMax = dobMaxyyyy+'-'+mm+'-'+dd;
                dobMin = dobMinyyyy+'-'+mm+'-'+dd;
                if(candidate.candidate_details != null) {
                    if(candidate.candidate_details.passport_expiry_date == '') {
                        document.getElementById("passport_expiry_date").min = today;
                    }
                }         
                document.getElementById("dob").max = dobMax;
                document.getElementById("dob").min = dobMin;
                document.getElementById("spouse_dob").max = dobMax;
                document.getElementById("spouse_dob").min = dobMin;  
                if(candidate.candidate_details != null) {          
                    if(candidate.candidate_details.year_of_completion != '') {
                        var yearOfCompletion = candidate.candidate_details.year_of_completion;
                        $("#year_of_completion").yearpicker({
                            year: yearOfCompletion,
                            startYear: 1950,
                            endYear: yyyy,
                        }); 
                    }
                } 
                else {
                    $("#year_of_completion").yearpicker({
                        startYear: 1950,
                        endYear: yyyy,
                    });
                }
                $('#religion').select2({
					allowClear: true,
					maximumSelectionLength: 1,
					placeholder:"Choose Religion",
				});
				$('#language_id').select2({
					allowClear: true,
					placeholder:"Choose Spoken Languages",
				});
                $('#spouse_nationality').select2({
					allowClear: true,
					placeholder:"Choose Spouse Nationality",
				});	
                var residence_telephone_number = window.intlTelInput(document.querySelector("#residence_telephone_number"), {
                    separateDialCode: true,
                    preferredCountries:["ae"],
                    hiddenInput: "full",
                    utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
                });
				var contact_number = window.intlTelInput(document.querySelector("#contact_number"), {
					separateDialCode: true,
					preferredCountries:["ae"],
					hiddenInput: "full",
					utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
				});
                emergencyContactUAE(1);
                emergencyContactHome(1);
				///======Clone method
				$("body").on("click",".add_new_frm_field_btn", function () { 
					addChild();
				}); 
				$("body").on("click",".add_new_frm_field_btn_contact_uae", function () {
					addContactUAE();
				}); 
				$("body").on("click",".add_new_frm_field_btn_contact_home", function () { 
					addContactHome();
				}); 
				//===== delete the form fieed row
				$("body").on("click", ".remove_node_btn_frm_field", function () {
					$(this).closest(".form_field_outer_row").remove();
				});
				function addChild() {
					var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; 
					$(".form_field_outer").append(`
						<div class="row form_field_outer_row" id="${index}">
							<div class="col-xxl-3 col-lg-6 col-md-6">
								<label for="child_name" class="col-form-label text-md-end">{{ __('Child Name') }}</label>
								<input id="child_name_${index}" type="text" class="child_name form-control widthinput @error('child_name') is-invalid @enderror" name="child[${index}][child_name]"
									placeholder="Child Name" value="" autocomplete="child_name" autofocus data-index="${index}">
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_passport_number" class="col-form-label text-md-end">{{ __('Child Passport Number') }}</label>
								<input id="child_passport_number_${index}" type="text" class="child_passport_number form-control widthinput @error('child_passport_number') is-invalid @enderror" name="child[${index}][child_passport_number]"
									placeholder="Child Passport Number" value="" autocomplete="child_passport_number" autofocus data-index="${index}">
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_passport_expiry_date" class="col-form-label text-md-end">{{ __('Child Passport Expiry Date') }}</label>
								<input id="child_passport_expiry_date_${index}" type="date" class="form-control widthinput @error('child_passport_expiry_date') is-invalid @enderror" name="child[${index}][child_passport_expiry_date]"
									value="" autocomplete="child_passport_expiry_date" autofocus data-index="${index}">
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_dob" class="col-form-label text-md-end">{{ __('Child Date Of Birth') }}</label>
								<input id="child_dob_${index}" type="date" class="form-control widthinput @error('child_dob') is-invalid @enderror" name="child[${index}][child_dob]"
									value="" autocomplete="child_dob" autofocus data-index="${index}">
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_nationality" class="col-form-label text-md-end">{{ __('Child Nationality') }}</label>
								<select name="child[${index}][child_nationality]" id="child_nationality_${index}" class="form-control widthinput" onchange="" autofocus data-index="${index}">
									<option></option>
									@foreach($masterNationality as $nationality)
									<option value="{{$nationality->id}}">{{$nationality->nationality ?? $nationality->name}} </option>
									@endforeach
								</select>
							</div>
							<div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
								<a class="btn_round remove_node_btn_frm_field" title="Remove Row">
								<i class="fas fa-trash-alt"></i>
								</a>
							</div>
						</div>
					`); 
                    document.getElementById("child_dob_"+index).max = today;
                    $("#child_name_"+index).rules('add', {
                        lettersonly: true,
                        required: function(element) {
                            if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#child_dob_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#child_nationality_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#child_passport_number_"+index).rules('add', {
                        validPassport: true,
                        required: function(element) {
                            if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#child_passport_expiry_date_"+index).rules('add', {
                        required: function(element) {
                            if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#child_dob_"+index).rules('add', {
                        required: function(element) {
                            if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#child_name_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#child_nationality_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#child_nationality_"+index).rules('add', {
                        required: function(element) {
                            if($("#child_passport_number_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#child_passport_expiry_date_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#child_name_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#child_dob_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
				}
				function addContactUAE() {
                    var index = indexVal+1;
                    indexVal = indexVal+1;
					// var index = $(".form_field_outer_contact_uae").find(".form_field_outer_row").length + 1; 
					$(".form_field_outer_contact_uae").append(`
                        <div class="row form_field_outer_row" id="emergency_uae_"+${index}>
                            <div class="col-xxl-3 col-lg-6 col-md-6">
                                <span class="error">* </span>
                                <label for="ecu_name" class="ecu_name col-form-label text-md-end">{{ __('Name') }}</label>
                                <input id="ecu_name_${index}" type="text" class="form-control widthinput @error('ecu_name') is-invalid @enderror" 
                                name="ecu[${index}][name]" data-index=${index} placeholder="emergency Contact Person UAE" value="" autofocus>
                            </div>
                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                <span class="error">* </span>
                                <label for="ecu_relation" class="col-form-label text-md-end">{{ __('Relation') }}</label>
                                <select name="ecu[${index}][relation]" data-index=${index} id="ecu_relation_${index}" class="form-control widthinput" autofocus>
                                    <option></option>
                                    @foreach($masterRelations as $relation)
                                    <option value="{{$relation->id}}">{{$relation->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                <span class="error">* </span>
                                <label for="ecu_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                <input id="ecu_email_${index}" type="text" class="form-control widthinput @error('ecu_email') is-invalid @enderror" 
                                name="ecu[${index}][email_address]" data-index=${index} placeholder="Email" value="" autocomplete="ecu_email" autofocus>
                            </div>
                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                <span class="error">* </span>
                                <label for="ecu_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                <input id="ecu_contact_number_${index}" type="tel" class="form-control widthinput @error('ecu_contact_number[main]') is-invalid @enderror" 
                                name="ecu[${index}][contact_number][main]" data-index=${index} placeholder="Contact Number"  value="" autocomplete="ecu_contact_number[main]" autofocus>
                            </div>
                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                <label for="ecu_alternative_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                                <input id="ecu_alternative_number_${index}" type="tel" class="form-control widthinput @error('ecu_alternative_number[main]') is-invalid @enderror" 
                                name="ecu[${index}][alternative_contact_number][main]" data-index=${index} placeholder="Alternative Number"  value="" autocomplete="ecu_alternative_number[main]" autofocus>
                            </div>
                            <div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
                                <a class="btn_round remove_node_btn_frm_field" title="Remove Row">
                                <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
					`); 
                    emergencyContactUAE(index);
				}
				function addContactHome() {
					var index = $(".form_field_outer_contact_home").find(".form_field_outer_row").length + 1; 
					$(".form_field_outer_contact_home").append(`
                        <div class="row form_field_outer_row">
                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                <span class="error">* </span>
                                <label for="ech_home_country_address" class="col-form-label text-md-end">{{ __('Home Country Address') }}</label>													
                                <textarea rows="7" id="ech_home_country_address_${index}" type="text" class="form-control @error('ech_home_country_address') is-invalid @enderror"
                                    name="ech[${index}][home_country_address]" data-index=${index} placeholder="Home Country Address" value="{{ old('ech_home_country_address') }}"  autocomplete="ech_home_country_address"
                                    autofocus></textarea>
                            </div>
                            <div class="col-xxl-5 col-lg-5 col-md-5 mt-4">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <span class="error">* </span>
                                        <label for="ech_name" class="col-form-label text-md-end">{{ __('Name') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                        <input id="ech_name_${index}" type="text" class="widthinput form-control @error('ech_name') is-invalid @enderror"
                                            name="ech[${index}][name]" data-index=${index} placeholder="Name" value="{{old('ech_name')}}"
                                            autocomplete="ech_name" autofocus>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <span class="error">* </span>
                                        <label for="ech_relation" class="col-form-label text-md-end">{{ __('Relation:') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                        <select name="ech[${index}][relation]" data-index=${index} id="ech_relation_${index}" class="form-control widthinput" onchange="" autofocus>
                                            <option></option>
                                            @foreach($masterRelations as $relation)
                                            <option value="{{$relation->id}}">{{$relation->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <span class="error">* </span>
                                        <label for="ech_email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                        <input id="ech_email_${index}" type="text" class="form-control widthinput @error('ech_email') is-invalid @enderror"
                                            name="ech[${index}][email]" data-index=${index}
                                            placeholder="Email" value="" autocomplete="ech_email" autofocus>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <span class="error">* </span>
                                        <label for="ech_contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                        <input id="ech_contact_number_${index}" type="tel" class="form-control widthinput @error('ech_contact_number') is-invalid @enderror" 
                                        name="ech[${index}][contact_number][main]" data-index=${index}
                                            placeholder="Contact Number" value="" autocomplete="ech_contact_number" autofocus>
                                    </div>
                                    <div class="col-xxl-3 col-lg-3 col-md-3">
                                        <label for="ech_alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Number') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-9 col-md-9">
                                        <input id="ech_alternative_contact_number_${index}" type="tel" class="form-control widthinput @error('ech_alternative_contact_number') is-invalid @enderror" 
                                        name="ech[${index}][alternative_contact_number][main]" data-index=${index}
                                            placeholder="Alternative Contact Number" value="" autocomplete="ech_alternative_contact_number" autofocus>
                                    </div>
                                </div>
                            </div>
							<div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
								<a class="btn_round remove_node_btn_frm_field" title="Remove Row">
								<i class="fas fa-trash-alt"></i>
								</a>
							</div>						
						</div>
					`); 
                    emergencyContactHome(index);
				}
                function emergencyContactUAE(index) {                   
                    var emergency_uae_contact = window.intlTelInput(document.querySelector("#ecu_contact_number_"+index), {
                        separateDialCode: true,
                        preferredCountries:["ae"],
                        hiddenInput: "full",
                        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
                    });
                    var emergency_uae_alternative = window.intlTelInput(document.querySelector("#ecu_alternative_number_"+index), {
                        separateDialCode: true,
                        preferredCountries:["ae"],
                        hiddenInput: "full",
                        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
                    });
                    $("#ech_home_country_address_"+index).rules('add', {
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ecu_alternative_number_"+index).rules('add', {
                        minlength: 5,
                        maxlength: 20,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ecu_name_"+index).rules('add', {
                        lettersonly: true,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ecu_relation_"+index).rules('add', {
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ecu_email_"+index).rules('add', {
                        email:true,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ecu_contact_number_"+index).rules('add', {
                        minlength: 5,
                        maxlength: 20,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ecu_name_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ecu_relation_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ecu_alternative_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ecu_alternative_number_"+index).rules('add', {
                        minlength: 5,
                        maxlength: 20,
                    });
                }
                function emergencyContactHome(index) {                   
                    var emergency_uae_contact = window.intlTelInput(document.querySelector("#ech_contact_number_"+index), {
                        separateDialCode: true,
                        preferredCountries:["ae"],
                        hiddenInput: "full",
                        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
                    });
                    var emergency_uae_alternative = window.intlTelInput(document.querySelector("#ech_alternative_contact_number_"+index), {
                        separateDialCode: true,
                        preferredCountries:["ae"],
                        hiddenInput: "full",
                        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
                    });
                    $("#ech_name_"+index).rules('add', {
                        lettersonly: true,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ech_relation_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ech_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ech_relation_"+index).rules('add', {
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ech_name_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ech_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ech_email_"+index).rules('add', {
                        email:true,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ech_name_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ech_relation_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ech_contact_number_"+index).rules('add', {
                        minlength: 5,
                        maxlength: 20,
                        required: function(element) {
                            if(element.getAttribute('data-index') == 1) {
                                return true;
                            }
                            else if($("#ech_name_"+element.getAttribute('data-index')).val().length > 0) {                                
                                return true;
                            }
                            else if($("#ech_relation_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_email_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else if($("#ech_alternative_contact_number_"+element.getAttribute('data-index')).val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    });
                    $("#ech_alternative_contact_number_"+index).rules('add', {
                        minlength: 5,
                        maxlength: 20,
                    });
                }
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
            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-z ]+$/i.test(value);
            }, "Letters and spaces only allowed");

            jQuery.validator.addMethod("validPassport", function(value, element) {
                return this.optional(element) || /^[A-PR-WYa-pr-wy][1-9]\d\s?\d{4}[1-9]$/i.test(value);
            }, "Passport number is not valid");           
            $.validator.addMethod('signaturePresent', function(value, element) {
                console.log('Checking...');
                return this.optional(element) || signaturePad.isEmpty();
            }, "Please provide your signature...");
	        $('#candidatepersonalInfoForm').validate({ 
                rules: {
                    first_name: {
                        required: true,
                        lettersonly: true,
                    },
                    last_name: {
                        required: true,
                        lettersonly: true,
                    },
                    name_of_father: {
                        required: true,
                        lettersonly: true,
                    },
                    name_of_mother: {
                        required: true,
                        lettersonly: true,
                    },
                    marital_status: {
                        required: true,
                    },
                    passport_number: {
                        required: true,
                        validPassport:true,
                    },
                    passport_expiry_date: {
                        required: true,
                    },
                    educational_qualification: {
                        required: true,
                        lettersonly: true,
                    },
                    year_of_completion: {
                        required: true,				
                    },
                    religion: {
                        required: true,
                    },
                    dob: {
                        required: true,
                    },
                    "language_id[]": {
                        required: true,
                    },
                    address_uae: {
                        required: true,
                    },
                    "residence_telephone_number[main]": {
                        required: true,
                        minlength: 5,
                        maxlength: 20,
                    },
                    "contact_number[main]": {
                        required: true,
                        minlength: 5,
                        maxlength: 20,
                    },
                    personal_email_address: {
                        required: true,
                        email:true,
                    },
                    spouse_name: {  
                        lettersonly: true,                     
                        required: function(element){
                            if($("#spouse_passport_number").val().length > 0) {                                
                                return true;
                            }
                            else if($("#spouse_passport_expiry_date").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_dob").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_nationality").val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    },
                    spouse_passport_number: {  
                        validPassport:true,                     
                        required: function(element){
                            if($("#spouse_passport_expiry_date").val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    },
                    spouse_passport_expiry_date: {                       
                        required: function(element){
                            if($("#spouse_passport_number").val().length > 0) {                        
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    },
                    spouse_dob: {                       
                        required: function(element){
                            if($("#spouse_passport_number").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_passport_expiry_date").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_name").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_nationality").val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    },
                    spouse_nationality: {                       
                        required: function(element){
                            if($("#spouse_passport_number").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_passport_expiry_date").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_dob").val().length > 0) {
                                return true;
                            }
                            else if($("#spouse_name").val().length > 0) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        },
                    },
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
                        extension: "jpg|jpeg",
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
                        extension: "docx|rtf|doc|pdf",
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
                        extension: "docx|rtf|doc|pdf|jpg|jpeg",
                    },
                    emirates_id: { 
                        extension: "docx|rtf|doc|pdf|jpg|jpeg",
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
                        extension: "docx|rtf|doc|pdf|jpg|jpeg",
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
                        extension: "docx|rtf|doc|pdf|jpg|jpeg",
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
                        extension: "docx|rtf|doc|pdf|jpg|jpeg",
                    },
                    "professional_diploma_certificates[]": {
                        required: function(element) {
                            if(candidate != null  && candidate.candidate_details != null && candidate.candidate_details.candidate_pro_dip_certi != null && oldProDipCertiArr.length > 0) {
                                return false;
                            }
                            else if($("#professional-diploma-certificates").val().length > 0) {
                                return false;
                            }              
                            else {
                                return true;
                            }
                        },
                        extension: "docx|rtf|doc|pdf|jpg|jpeg",
                    },
                    signature: {
                        required: true,
                    }
                },
            });
            var deletedDocuments = new Array();
            $('.document-delete-button').on('click',function(){
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
            $('.delete-button').on('click',function(){
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