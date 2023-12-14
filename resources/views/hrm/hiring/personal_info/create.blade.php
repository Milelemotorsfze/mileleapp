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
                                                                <option value="{{$masterLanguage->id}}">{{$masterLanguage->name}}</option>
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
                                                                    <input id="residence_telephone_number" type="number" class="widthinput form-control @error('residence_telephone_number[full]') is-invalid @enderror"
                                                                        name="residence_telephone_number[main]" placeholder="Residence Telephone Number" value="{{old('hiddencontact')}}"
                                                                        autocomplete="residence_telephone_number[main]" autofocus>
                                                                </div>
                                                                <div class="col-xxl-4 col-lg-4 col-md-4 mt-2">
                                                                    <span class="error">* </span>
                                                                    <label for="contact_number" class="col-form-label text-md-end">{{ __('Mobile Number:') }}</label>
                                                                </div>
                                                                <div class="col-xxl-8 col-lg-8 col-md-8 mt-2">
                                                                    <input id="contact_number" type="number" class="widthinput form-control @error('contact_number[full]') is-invalid @enderror"
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
                                                    <div class="col-md-12 form_field_outer_contact_uae p-0">
                                                        <div class="row form_field_outer_row">
                                                            <div class="col-xxl-3 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="child_name" class="child_name col-form-label text-md-end">{{ __('Name') }}</label>
                                                                <input id="uae_name_1" type="text" class="form-control widthinput @error('child_name') is-invalid @enderror" name="ssss"
                                                                    placeholder="Child Name" value="" autocomplete="child_name" autofocus>
                                                            </div>
                                                            <div class="col-xxl-3 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="child_nationality" class="col-form-label text-md-end">{{ __('Relation') }}</label>
                                                                <select name="child_najlity" id="uae_relation_1" class="form-control widthinput" onchange="" autofocus>
                                                                    <option>Choose Relation</option>
                                                                    @foreach($masterRelations as $relation)
                                                                    <option value="{{$relation->id}}">{{$relation->name}} </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-xxl-3 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="child_passport_number" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                                                <input id="uae_email_1" type="text" class="form-control widthinput @error('child_passport_number') is-invalid @enderror" name="chilber"
                                                                    placeholder="Email" value="" autocomplete="child_passport_number" autofocus>
                                                            </div>
                                                            <div class="col-xxl-2 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="child_passport_expiry_date" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                                                <input id="uae_contact_1" type="date" class="form-control widthinput @error('child_passport_expiry_date') is-invalid @enderror" name="child_ort_expiry_date"
                                                                    value="" autocomplete="child_passport_expiry_date" autofocus>
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
                                                    <div class="col-md-12 form_field_outer_contact_home p-0">
                                                        <div class="row form_field_outer_row">
                                                            <div class="col-xxl-6 col-lg-6 col-md-6">
                                                                <span class="error">* </span>
                                                                <label for="address_uae" class="col-form-label text-md-end">{{ __('Home Country Address') }}</label>													
                                                                <textarea rows="6" id="home_address_1" type="text" class="form-control @error('address_uae') is-invalid @enderror"
                                                                    name="address_uae" placeholder="Home Country Address" value="{{ old('address_uae') }}"  autocomplete="address_uae"
                                                                    autofocus></textarea>
                                                            </div>
                                                            <div class="col-xxl-5 col-lg-5 col-md-5 mt-4">
                                                                <div class="row">
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
                                                                        <span class="error">* </span>
                                                                        <label for="residence_telephone_number" class="col-form-label text-md-end">{{ __('Name') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
                                                                        <input id="home_name_1" type="number" class="widthinput form-control @error('residence_telephone_number[full]') is-invalid @enderror"
                                                                            name="residence_telephone_number[main]" placeholder="Name" value="{{old('hiddencontact')}}"
                                                                            autocomplete="residence_telephone_number[main]" autofocus>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
                                                                        <span class="error">* </span>
                                                                        <label for="contact_number" class="col-form-label text-md-end">{{ __('Relation:') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
                                                                        <select name="child_nationality" id="home_relation_1" class="form-control widthinput" onchange="" autofocus>
                                                                            <option>Choose Relation</option>
                                                                            @foreach($masterRelations as $relation)
                                                                            <option value="{{$relation->id}}">{{$relation->name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
                                                                        <span class="error">* </span>
                                                                        <label for="personal_email_address" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
                                                                        <input id="home_email_1" type="text" class="form-control widthinput @error('personal_email_address') is-invalid @enderror" name="personal_email_address"
                                                                            placeholder="Email" value="" autocomplete="personal_email_address" autofocus>
                                                                    </div>
                                                                    <div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
                                                                        <span class="error">* </span>
                                                                        <label for="personal_email_address" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                                                    </div>
                                                                    <div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
                                                                        <input id="home_contact_1" type="text" class="form-control widthinput @error('personal_Contact Number_address') is-invalid @enderror" name="personal_Contact Number_address"
                                                                            placeholder="Contact Number" value="" autocomplete="personal_email_address" autofocus>
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
                                            <div class="row">
                                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                                    <label for="request_date" class="col-form-label text-md-end">{{ __('Signature') }} :</label>
                                                </div>
                                                <div class="col-xxl-6 col-lg-6 col-md-6" style="float:right;">
                                                    <label for="request_date" class="col-form-label text-md-end">{{ __('Date') }}</label> : {{\Carbon\Carbon::parse(Carbon\Carbon::now())->format('d M Y')}}
                                                </div>
                                            </div>
                                            <div id="signature-pad" class="m-signature-pad">
                                                <div class="m-signature-pad--body">
                                                    <canvas></canvas>
                                                    <input type="hidden" name="signature" id="signature" value="">
                                                </div>
                                            </div>
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                                <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
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
            $(document).ready(function() {
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
                document.getElementById("passport_expiry_date").min = today;
                document.getElementById("dob").max = dobMax;
                document.getElementById("dob").min = dobMin;
                document.getElementById("spouse_dob").max = dobMax;
                document.getElementById("spouse_dob").min = dobMin;
                $("#year_of_completion").yearpicker({
                    startYear: 1950,
                    endYear: yyyy,
                });
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
                addChild();
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
									placeholder="Child Passport Number" value="" autocomplete="child_passport_number" autofocus>
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_passport_expiry_date" class="col-form-label text-md-end">{{ __('Child Passport Expiry Date') }}</label>
								<input id="child_passport_expiry_date_${index}" type="date" class="form-control widthinput @error('child_passport_expiry_date') is-invalid @enderror" name="child[${index}][child_passport_expiry_date]"
									value="" autocomplete="child_passport_expiry_date" autofocus>
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_dob" class="col-form-label text-md-end">{{ __('Child Date Of Birth') }}</label>
								<input id="child_dob_${index}" type="date" class="form-control widthinput @error('child_dob') is-invalid @enderror" name="child[${index}][child_dob]"
									value="" autocomplete="child_dob" autofocus>
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="child_nationality" class="col-form-label text-md-end">{{ __('Child Nationality') }}</label>
								<select name="child[${index}][child_nationality]" id="child_nationality_${index}" class="form-control widthinput" onchange="" autofocus>
									<option>Choose Child Nationality</option>
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
                    $("#child .child_name").each(function () {
                        $(this).rules('add', {
                            lettersonly: true,
                            required: function(element){console.log(element["data-index"]);
                                if($("#spouse_passport_number").val().length > 0) {                                
                                    return true;
                                }
                            //     // else if($("#child_passport_expiry_date_".${index}).val().length > 0) {
                            //     //     return true;
                            //     // }
                            //     // else if($("#child_dob_".${index}).val().length > 0) {
                            //     //     return true;
                            //     // }
                            //     // else if($("#child_nationality_".${index}).val().length > 0) {
                            //     //     return true;
                            //     // }
                            //     // else {
                            //     //     return false;
                            //     // }
                            },
                        });
                    });
                    $("#child .child_passport_number").each(function () {
                        $(this).rules('add', {
                            validPassport: true,
                        });
                    });
				}
				function addContactUAE() {
					var index = $(".form_field_outer_contact_uae").find(".form_field_outer_row").length + 1; 
					$(".form_field_outer_contact_uae").append(`
						<div class="row form_field_outer_row">
							<div class="col-xxl-3 col-lg-6 col-md-6">
								<label for="gg_name" class="col-form-label text-md-end">{{ __('Name') }}</label>
								<input id="fld_name_${index}" type="text" class="form-control widthinput @error('gg_name') is-invalid @enderror" name="gg_name"
									placeholder="gg Name" value="" autocomplete="gg_name" autofocus>
							</div>
							<div class="col-xxl-3 col-lg-6 col-md-6">
								<label for="gg_nationality" class="col-form-label text-md-end">{{ __('Relation') }}</label>
								<select name="gg_nationality" id="gg_nationality_${index}" class="form-control widthinput" onchange="" autofocus>
									<option>Choose Relation</option>
									@foreach($masterRelations as $relation)
										<option value="{{$relation->id}}">{{$relation->name}} </option>
									@endforeach
								</select>
							</div>
							<div class="col-xxl-3 col-lg-6 col-md-6">
								<label for="gg_passport_number" class="col-form-label text-md-end">{{ __('Email') }}</label>
								<input id="gg_passport_number_${index}" type="text" class="form-control widthinput @error('gg_passport_number') is-invalid @enderror" name="gg_passport_number"
									placeholder="Email" value="" autocomplete="gg_passport_number" autofocus>
							</div>
							<div class="col-xxl-2 col-lg-6 col-md-6">
								<label for="gg_passport_expiry_date" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
								<input id="gg_passport_expiry_date_${index}" type="date" class="form-control widthinput @error('gg_passport_expiry_date') is-invalid @enderror" name="gg_passport_expiry_date"
									value="" autocomplete="gg_passport_expiry_date" autofocus>
							</div>
							<div class="col-xxl-1 col-lg-6 col-md-6 add_del_btn_outer">
								<a class="btn_round remove_node_btn_frm_field" title="Remove Row">
								<i class="fas fa-trash-alt"></i>
								</a>
							</div>
						</div>
					`); 
				}
				function addContactHome() {
					var index = $(".form_field_outer_contact_home").find(".form_field_outer_row").length + 1; 
					$(".form_field_outer_contact_home").append(`
						<div class="row form_field_outer_row">
							<div class="col-xxl-6 col-lg-6 col-md-6">
								<span class="error">* </span>
								<label for="address_uae" class="col-form-label text-md-end">{{ __('Home Country Address') }}</label>													
								<textarea rows="6" id="home_address_${index}" type="text" class="form-control @error('address_uae') is-invalid @enderror"
									name="address_uae" placeholder="Home Country Address" value="{{ old('address_uae') }}"  autocomplete="address_uae"
									autofocus></textarea>
							</div>
							<div class="col-xxl-5 col-lg-5 col-md-5 mt-4">
								<div class="row">
									<div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
										<span class="error">* </span>
										<label for="residence_telephone_number" class="col-form-label text-md-end">{{ __('Name') }}</label>
									</div>
									<div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
										<input id="home_name_${index}" type="number" class="widthinput form-control @error('residence_telephone_number[full]') is-invalid @enderror"
											name="residence_telephone_number[main]" placeholder="Name" value="{{old('hiddencontact')}}"
												autocomplete="residence_telephone_number[main]" autofocus onkeyup="validationOnKeyUp(this)">
									</div>
									<div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
										<span class="error">* </span>
										<label for="contact_number" class="col-form-label text-md-end">{{ __('Relation:') }}</label>
									</div>
									<div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
									<select name="gg_nationality" id="home_relation_${index}" class="form-control widthinput" onchange="" autofocus>
										<option>Choose Relation</option>
										@foreach($masterRelations as $relation)
										<option value="{{$relation->id}}">{{$relation->name}} </option>
										@endforeach
									</select>
									</div>
									<div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
										<span class="error">* </span>
										<label for="personal_email_address" class="col-form-label text-md-end">{{ __('Email') }}</label>
									</div>
									<div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
										<input id="home_email_${index}" type="text" class="form-control widthinput @error('personal_email_address') is-invalid @enderror" name="personal_email_address"
											placeholder="Email" value="" autocomplete="personal_email_address" autofocus>
									</div>
									<div class="col-xxl-3 col-lg-3 col-md-3 mt-1">
										<span class="error">* </span>
										<label for="personal_email_address" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
									</div>
									<div class="col-xxl-9 col-lg-9 col-md-9 mt-1">
										<input id="home_contact_${index}" type="text" class="form-control widthinput @error('personal_Contact Number_address') is-invalid @enderror" name="personal_Contact Number_address"
											placeholder="Contact Number" value="" autocomplete="personal_email_address" autofocus>
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
                },
            });
        </script>
    </body>
</html>