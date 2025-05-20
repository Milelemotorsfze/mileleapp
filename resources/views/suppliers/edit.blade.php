@extends('layouts.main')
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" /> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" /> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->



<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> -->

<style>
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
        /* border-radius: 50%; */
        text-align: center;
        line-height: 35px;
        margin-left: 10px;
        margin-top: 0px;
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
        background: #6b4acc;
        border: 1px solid #6b4acc;
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
    .requiredOne
    {
        margin-top: .25rem;
        font-size: 80%;
        color: gray;
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
    iframe{
        min-height: 300px;
    }
</style>
@section('content')
@canany(['addon-supplier-edit', 'addon-supplier-create', 'vendor-edit','demand-planning-supplier-edit'])
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-edit', 'vendor-edit','demand-planning-supplier-edit']);
    @endphp
    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Edit Vendor</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <!-- @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif -->

        <form id="createSupplierForm" name="createSupplierForm" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>
                <input id="supplier_id" name="supplier_id" value="{{ $supplier->id }}" hidden>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Primary Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <span class="error">* </span>
                                        <label for="supplier" class="col-form-label text-md-end">{{ __('Vendor') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="supplier" type="text" class="form-control widthinput @error('supplier') is-invalid @enderror" name="supplier"
                                               placeholder="Individual / Company Name" value="{{ old('supplier', $supplier->supplier) }}"  autocomplete="supplier"
                                               autofocus>
                                        @error('supplier')
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                        <span id="supplierError" class="invalid-feedback"></span>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="type" class="col-form-label text-md-end">{{ __('Vendor Type') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <select class="widthinput form-control" name="type" id="type" autofocus>
                                            <option></option>
                                            <option value="Individual" {{ $supplier->type == 'Individual' ? 'selected' : '' }}>Individual</option>
                                            <option value="Company" {{ $supplier->type == 'Company' ? 'selected' : '' }}>Company</option>
                                        </select>
                                        @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <span class="error">* </span>
                                        <label for="contact_person" class="col-form-label text-md-end">{{ __('Category') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <select class="widthinput form-control" name="categories[]" id="category" multiple  autofocus>

                                            @foreach($masterVendorCategories as $key =>  $vendorCategory)
                                                <option value="{{$vendorCategory->name}}" {{ (in_array($vendorCategory->name, $vendorCategories)) ? 'selected' : '' }}
                                                @if(in_array($key, $nonRemovableVendorCategories)) locked="locked" @endif>
                                                    {{ $vendorCategory->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                        <span id="supplierCategoryError" class="invalid-feedback"></span>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <span class="error">* </span>
                                        <label for="supplier_types" class="col-form-label text-md-end">{{ __('Sub Category') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12" id="mainSelect">
                                        <select name="supplier_types[]"  id="supplier_type" multiple="true" style="width: 100%;"
                                                class="form-control widthinput" autofocus  onchange="validationOnKeyUp(this)">
                                                @foreach($masterSubCategories as $key => $subCategory)
                                                    <option value="{{$subCategory->slug}}" {{ (in_array($subCategory->slug, $vendorSubCategories)) ? 'selected' : ''   }}
                                                    @if(in_array($subCategory->slug, $supAddTypesName)) locked="locked" @endif >
                                                        {{ $subCategory->name }}</option>
                                                @endforeach
                                        </select>
                                        <span id="supplierTypeError" class=" invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="sub category" class="col-form-label text-md-end">{{ __('Web Address') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input class="widthinput form-control" name="web_address" id="web_address"
                                               placeholder="Web Address" autofocus value="{{ old('web_address', $supplier->web_address) }}">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="person_contact_by" class="col-form-label text-md-end">{{ __('Person Contact By') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <select class="form-control widthinput" name="person_contact_by" id="person_contact_by" autofocus>
                                            <option></option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == $supplier->person_contact_by ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="sub category" class="col-form-label text-md-end">{{ __('Comment') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <textarea cols="25" rows="5" class=" form-control" name="comment" placeholder="Comment"
                                                  id="comment" autofocus>{{ $supplier->comment }}</textarea>
                                    </div>
                                </div>
                            </div>

                                <div class="col-xxl-6 col-lg-6 col-md-12" id="vendor-name-checkbox"  @if($supplier->is_MMC == false && $supplier->is_AMS == false) hidden @endif >
                                    <div class="row">
                                        <div class="col-xxl-3 col-lg-6 col-md-12">
                                            <!-- <span class="error">* </span> -->
                                            <label for="mmc-checkbox" class="col-form-label text-md-end">{{ __('Vendor') }}</label>
                                        </div>
                                        <div class="col-xxl-9 col-lg-6 col-md-12">
                                            <input type="checkbox" name="is_mmc" class="demand-planning-vendor-checkbox" id="MMC-checkbox"  {{ old('is_mmc') ? 'checked' : '' }}
                                                {{ $supplier->is_MMC == 1 ? 'checked' : '' }}>
                                            <label for="MMC-checkbox" class="col-form-label text-md-end ml-3 ">{{ __('Is vendor MMC?') }}</label>
                                            <input type="checkbox" name="is_ams" class="demand-planning-vendor-checkbox" id="AMS-checkbox" {{ old('is_mmc') ? 'checked' : '' }}
                                                {{ $supplier->is_AMS == 1 ? 'checked' : '' }}>
                                            <label for="AMS-checkbox" class="col-form-label text-md-end">{{ __('Is vendor AMS?') }}</label>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Contact Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                             <span class="error">* </span>
                                        <label for="contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="contact_number" type="tel" class="widthinput contact form-control @error('contact_number[full]') is-invalid @enderror"
                                               name="contact_number[main]" placeholder="Enter Contact Number" value="{{$supplier->contact_number}}"
                                               autocomplete="contact_number[main]" autofocus onkeyup="validationOnKeyUp(this)">
                                         <span id="contactRequired" class="email-phone required-class"></span>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="alternative_contact_number" type="tel" class="widthinput contact form-control @error('alternative_contact_number[full]')
                                            is-invalid @enderror" name="alternative_contact_number[main]" placeholder="Enter Alternative Contact Number"
                                               value="{{ $supplier->alternative_contact_number }}" autocomplete="alternative_contact_number[full]" autofocus
                                               onkeyup="validationOnKeyUp(this)">

                                        <span id="alternativeContactRequired" class="email-phone required-class"></span>
                                    </div>
                                </div>
                                </br>
                            </div>

                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="phone" class="col-form-label text-md-end">{{ __('Phone') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="phone" type="tel"
                                               class="widthinput contact form-control @error('phone[full]') is-invalid @enderror" maxlength="15"
                                               minlength="5" name="phone[main]" placeholder="Enter Phone" value="{{ old('phone[full]', $supplier->phone) }}"
                                               autocomplete="phone[main]" autofocus onkeyup="validationOnKeyUp(this)">
                                        <span id="phoneRequired" class="phone required-class"></span>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="alternative_contact_number" class="col-form-label text-md-end">{{ __('Office Contact Number') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="office_phone" type="tel"
                                               class="widthinput contact form-control @error('office_phone[full]') is-invalid @enderror" name="office_phone[main]"
                                               placeholder="Enter Office Contact Number" value="{{ old('office_phone[full]', $supplier->office_phone) }}"
                                               minlength="5" maxlength="15" autocomplete="office_phone[full]" autofocus onkeyup="validationOnKeyUp(this)">
                                        <span id="officePhoneRequired" class="office_phone required-class"></span>
                                    </div>
                                </div>
                                </br>
                            </div>

                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="email" type="email" class="widthinput form-control @error('email') is-invalid @enderror" name="email"
                                               placeholder="Enter Email" value="{{ $supplier->email }}" autofocus onkeyup="validationOnKeyUp(this)">
                                        <!-- @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror -->
                                        <span id="emailRequired" class="email-phone required-class"></span>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="fax" class="col-form-label widthinput">{{ __('Fax') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="text" class="form-control" name="fax" placeholder="fax" value="{{old('fax',$supplier->fax)}}">

                                        @error('fax')
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror

                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="address" class="col-form-label widthinput">{{ __('Address') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <textarea cols="25" rows="5" class="form-control" name="address"
                                                  placeholder="Address Details">{{old('address',$supplier->address)}}</textarea>
                                        @error('address_details')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="contact_person" class="col-form-label text-md-end">{{ __('Contact Person') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input id="contact_person" type="text" class="widthinput form-control @error('contact_person') is-invalid @enderror" name="contact_person"
                                               placeholder="Enter Contact Person" value="{{ old('contact_person', $supplier->contact_person) }}"  autocomplete="contact_person" autofocus>
                                        @error('contact_person')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                </br>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Classification</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="contact_number" class="col-form-label widthinput">{{ __('Passport Number') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="text" class="form-control" name="passport_number" placeholder="Passport Number"
                                               value="{{old('passport_number',$supplier->passport_number)}}">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Nationality</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <select name="nationality" class="form-control widthinput" id="nationality">
                                            <option ></option>
                                            <option value="afghan" {{ $supplier->nationality == 'afghan' ? 'selected' : '' }}>Afghan</option>
                                            <option value="albanian" {{ $supplier->nationality == 'albanian' ? 'selected' : '' }}>Albanian</option>
                                            <option value="algerian" {{ $supplier->nationality == 'algerian' ? 'selected' : '' }}>Algerian</option>
                                            <option value="american" {{ $supplier->nationality == 'american' ? 'selected' : '' }}>American</option>
                                            <option value="andorran" {{ $supplier->nationality == 'andorran' ? 'selected' : '' }}>Andorran</option>
                                            <option value="angolan" {{ $supplier->nationality == 'angolan' ? 'selected' : '' }}>Angolan</option>
                                            <option value="antiguans" {{ $supplier->nationality == 'antiguans' ? 'selected' : '' }}>Antiguans</option>
                                            <option value="argentinean" {{ $supplier->nationality == 'argentinean' ? 'selected' : '' }}>Argentinean</option>
                                            <option value="armenian" {{ $supplier->nationality == 'armenian' ? 'selected' : '' }}>Armenian</option>
                                            <option value="australian" {{ $supplier->nationality == 'australian' ? 'selected' : '' }}>Australian</option>
                                            <option value="austrian" {{ $supplier->nationality == 'austrian' ? 'selected' : '' }}>Austrian</option>
                                            <option value="azerbaijani" {{ $supplier->nationality == 'azerbaijani' ? 'selected' : '' }}>Azerbaijani</option>
                                            <option value="bahamian" {{ $supplier->nationality == 'bahamian' ? 'selected' : '' }}>Bahamian</option>
                                            <option value="bahraini" {{ $supplier->nationality == 'bahraini' ? 'selected' : '' }}>Bahraini</option>
                                            <option value="bangladeshi" {{ $supplier->nationality == 'bangladeshi' ? 'selected' : '' }}>Bangladeshi</option>
                                            <option value="barbadian" {{ $supplier->nationality == 'barbadian' ? 'selected' : '' }}>Barbadian</option>
                                            <option value="barbudans" {{ $supplier->nationality == 'barbudans' ? 'selected' : '' }}>Barbudans</option>
                                            <option value="batswana" {{ $supplier->nationality == 'batswana' ? 'selected' : '' }}>Batswana</option>
                                            <option value="belarusian" {{ $supplier->nationality == 'belarusian' ? 'selected' : '' }}>Belarusian</option>
                                            <option value="belgian" {{ $supplier->nationality == 'belgian' ? 'selected' : '' }}>Belgian</option>
                                            <option value="belizean" {{ $supplier->nationality == 'belizean' ? 'selected' : '' }}>Belizean</option>
                                            <option value="beninese" {{ $supplier->nationality == 'beninese' ? 'selected' : '' }}>Beninese</option>
                                            <option value="bhutanese" {{ $supplier->nationality == 'bhutanese' ? 'selected' : '' }}>Bhutanese</option>
                                            <option value="bolivian" {{ $supplier->nationality == 'bolivian' ? 'selected' : '' }}>Bolivian</option>
                                            <option value="bosnian" {{ $supplier->nationality == 'bosnian' ? 'selected' : '' }}>Bosnian</option>
                                            <option value="brazilian" {{ $supplier->nationality == 'brazilian' ? 'selected' : '' }}>Brazilian</option>
                                            <option value="british" {{ $supplier->nationality == 'british' ? 'selected' : '' }}>British</option>
                                            <option value="bruneian" {{ $supplier->nationality == 'bruneian' ? 'selected' : '' }}>Bruneian</option>
                                            <option value="bulgarian" {{ $supplier->nationality == 'bulgarian' ? 'selected' : '' }}>Bulgarian</option>
                                            <option value="burkinabe" {{ $supplier->nationality == 'burkinabe' ? 'selected' : '' }}>Burkinabe</option>
                                            <option value="burmese" {{ $supplier->nationality == 'burmese' ? 'selected' : '' }}>Burmese</option>
                                            <option value="burundian" {{ $supplier->nationality == 'burundian' ? 'selected' : '' }}>Burundian</option>
                                            <option value="cambodian" {{ $supplier->nationality == 'cambodian' ? 'selected' : '' }}>Cambodian</option>
                                            <option value="cameroonian" {{ $supplier->nationality == 'cameroonian' ? 'selected' : '' }}>Cameroonian</option>
                                            <option value="canadian" {{ $supplier->nationality == 'canadian' ? 'selected' : '' }}>Canadian</option>
                                            <option value="cape verdean" {{ $supplier->nationality == 'cape verdean' ? 'selected' : '' }}>Cape Verdean</option>
                                            <option value="central african" {{ $supplier->nationality == 'central african' ? 'selected' : '' }}>Central African</option>
                                            <option value="chadian" {{ $supplier->nationality == 'chadian' ? 'selected' : '' }}>Chadian</option>
                                            <option value="chilean" {{ $supplier->nationality == 'chilean' ? 'selected' : '' }}>Chilean</option>
                                            <option value="chinese" {{ $supplier->nationality == 'chinese' ? 'selected' : '' }}>Chinese</option>
                                            <option value="colombian" {{ $supplier->nationality == 'colombian' ? 'selected' : '' }}>Colombian</option>
                                            <option value="comoran" {{ $supplier->nationality == 'comoran' ? 'selected' : '' }}>Comoran</option>
                                            <option value="congolese" {{ $supplier->nationality == 'congolese' ? 'selected' : '' }}>Congolese</option>
                                            <option value="costa rican" {{ $supplier->nationality == 'costa rican' ? 'selected' : '' }}>Costa Rican</option>
                                            <option value="croatian" {{ $supplier->nationality == 'croatian' ? 'selected' : '' }}>Croatian</option>
                                            <option value="cuban" {{ $supplier->nationality == 'cuban' ? 'selected' : '' }}>Cuban</option>
                                            <option value="cypriot" {{ $supplier->nationality == 'cypriot' ? 'selected' : '' }}>Cypriot</option>
                                            <option value="czech" {{ $supplier->nationality == 'czech' ? 'selected' : '' }}>Czech</option>
                                            <option value="danish" {{ $supplier->nationality == 'danish' ? 'selected' : '' }}>Danish</option>
                                            <option value="djibouti" {{ $supplier->nationality == 'djibouti' ? 'selected' : '' }}>Djibouti</option>
                                            <option value="dominican" {{ $supplier->nationality == 'dominican' ? 'selected' : '' }}>Dominican</option>
                                            <option value="dutch" {{ $supplier->nationality == 'dutch' ? 'selected' : '' }}>Dutch</option>
                                            <option value="east timorese" {{ $supplier->nationality == 'east timorese' ? 'selected' : '' }}>East Timorese</option>
                                            <option value="ecuadorean" {{ $supplier->nationality == 'ecuadorean' ? 'selected' : '' }}>Ecuadorean</option>
                                            <option value="emirian" {{ $supplier->nationality == 'emirian' ? 'selected' : '' }}>Emirian</option>
                                            <option value="equatorial guinean" {{ $supplier->nationality == 'equatorial guinean' ? 'selected' : '' }}>Equatorial Guinean</option>
                                            <option value="eritrean" {{ $supplier->nationality == 'eritrean' ? 'selected' : '' }}>Eritrean</option>
                                            <option value="estonian" {{ $supplier->nationality == 'estonian' ? 'selected' : '' }}>Estonian</option>
                                            <option value="ethiopian" {{ $supplier->nationality == 'ethiopian' ? 'selected' : '' }}>Ethiopian</option>
                                            <option value="fijian" {{ $supplier->nationality == 'fijian' ? 'selected' : '' }}>Fijian</option>
                                            <option value="filipino" {{ $supplier->nationality == 'filipino' ? 'selected' : '' }}>Filipino</option>
                                            <option value="finnish" {{ $supplier->nationality == 'finnish' ? 'selected' : '' }}>Finnish</option>
                                            <option value="french" {{ $supplier->nationality == 'french' ? 'selected' : '' }}>French</option>
                                            <option value="gabonese" {{ $supplier->nationality == 'gabonese' ? 'selected' : '' }}>Gabonese</option>
                                            <option value="gambian" {{ $supplier->nationality == 'gambian' ? 'selected' : '' }}>Gambian</option>
                                            <option value="georgian" {{ $supplier->nationality == 'georgian' ? 'selected' : '' }}>Georgian</option>
                                            <option value="german" {{ $supplier->nationality == 'german' ? 'selected' : '' }}>German</option>
                                            <option value="ghanaian" {{ $supplier->nationality == 'ghanaian' ? 'selected' : '' }}>Ghanaian</option>
                                            <option value="greek" {{ $supplier->nationality == 'greek' ? 'selected' : '' }}>Greek</option>
                                            <option value="grenadian" {{ $supplier->nationality == 'grenadian' ? 'selected' : '' }}>Grenadian</option>
                                            <option value="guatemalan" {{ $supplier->nationality == 'guatemalan' ? 'selected' : '' }}>Guatemalan</option>
                                            <option value="guinea-bissauan" {{ $supplier->nationality == 'guinea-bissauan' ? 'selected' : '' }}>Guinea-Bissauan</option>
                                            <option value="guinean" {{ $supplier->nationality == 'guinean' ? 'selected' : '' }}>Guinean</option>
                                            <option value="guyanese" {{ $supplier->nationality == 'guyanese' ? 'selected' : '' }}>Guyanese</option>
                                            <option value="haitian" {{ $supplier->nationality == 'haitian' ? 'selected' : '' }}>Haitian</option>
                                            <option value="herzegovinian" {{ $supplier->nationality == 'herzegovinian' ? 'selected' : '' }}>Herzegovinian</option>
                                            <option value="honduran" {{ $supplier->nationality == 'honduran' ? 'selected' : '' }}>Honduran</option>
                                            <option value="hungarian" {{ $supplier->nationality == 'hungarian' ? 'selected' : '' }}>Hungarian</option>
                                            <option value="icelander" {{ $supplier->nationality == 'icelander' ? 'selected' : '' }}>Icelander</option>
                                            <option value="indian" {{ $supplier->nationality == 'indian' ? 'selected' : '' }}>Indian</option>
                                            <option value="indonesian" {{ $supplier->nationality == 'indonesian' ? 'selected' : '' }}>Indonesian</option>
                                            <option value="iranian" {{ $supplier->nationality == 'iranian' ? 'selected' : '' }}>Iranian</option>
                                            <option value="iraqi" {{ $supplier->nationality == 'iraqi' ? 'selected' : '' }}>Iraqi</option>
                                            <option value="irish" {{ $supplier->nationality == 'irish' ? 'selected' : '' }}>Irish</option>
                                            <option value="israeli" {{ $supplier->nationality == 'israeli' ? 'selected' : '' }}>Israeli</option>
                                            <option value="italian" {{ $supplier->nationality == 'italian' ? 'selected' : '' }}>Italian</option>
                                            <option value="ivorian" {{ $supplier->nationality == 'ivorian' ? 'selected' : '' }}>Ivorian</option>
                                            <option value="jamaican" {{ $supplier->nationality == 'jamaican' ? 'selected' : '' }}>Jamaican</option>
                                            <option value="japanese" {{ $supplier->nationality == 'japanese' ? 'selected' : '' }}>Japanese</option>
                                            <option value="jordanian" {{ $supplier->nationality == 'jordanian' ? 'selected' : '' }}>Jordanian</option>
                                            <option value="kazakhstani" {{ $supplier->nationality == 'kazakhstani' ? 'selected' : '' }}>Kazakhstani</option>
                                            <option value="kenyan" {{ $supplier->nationality == 'kenyan' ? 'selected' : '' }}>Kenyan</option>
                                            <option value="kittian and nevisian" {{ $supplier->nationality == 'kittian and nevisian' ? 'selected' : '' }}>Kittian and Nevisian</option>
                                            <option value="kuwaiti" {{ $supplier->nationality == 'kuwaiti' ? 'selected' : '' }}>Kuwaiti</option>
                                            <option value="kyrgyz" {{ $supplier->nationality == 'kyrgyz' ? 'selected' : '' }}>Kyrgyz</option>
                                            <option value="laotian" {{ $supplier->nationality == 'laotian' ? 'selected' : '' }}>Laotian</option>
                                            <option value="latvian" {{ $supplier->nationality == 'latvian' ? 'selected' : '' }}>Latvian</option>
                                            <option value="lebanese" {{ $supplier->nationality == 'lebanese' ? 'selected' : '' }}>Lebanese</option>
                                            <option value="liberian" {{ $supplier->nationality == 'liberian' ? 'selected' : '' }}>Liberian</option>
                                            <option value="libyan" {{ $supplier->nationality == 'libyan' ? 'selected' : '' }}>Libyan</option>
                                            <option value="liechtensteiner" {{ $supplier->nationality == 'liechtensteiner' ? 'selected' : '' }}>Liechtensteiner</option>
                                            <option value="lithuanian" {{ $supplier->nationality == 'lithuanian' ? 'selected' : '' }}>Lithuanian</option>
                                            <option value="luxembourger" {{ $supplier->nationality == 'luxembourger' ? 'selected' : '' }}>Luxembourger</option>
                                            <option value="macedonian" {{ $supplier->nationality == 'macedonian' ? 'selected' : '' }}>Macedonian</option>
                                            <option value="malagasy" {{ $supplier->nationality == 'malagasy' ? 'selected' : '' }}>Malagasy</option>
                                            <option value="malawian" {{ $supplier->nationality == 'malawian' ? 'selected' : '' }}>Malawian</option>
                                            <option value="malaysian" {{ $supplier->nationality == 'malaysian' ? 'selected' : '' }}>Malaysian</option>
                                            <option value="maldivan" {{ $supplier->nationality == 'maldivan' ? 'selected' : '' }}>Maldivan</option>
                                            <option value="malian" {{ $supplier->nationality == 'malian' ? 'selected' : '' }}>Malian</option>
                                            <option value="maltese" {{ $supplier->nationality == 'maltese' ? 'selected' : '' }}>Maltese</option>
                                            <option value="marshallese" {{ $supplier->nationality == 'marshallese' ? 'selected' : '' }}>Marshallese</option>
                                            <option value="mauritanian {{ $supplier->nationality == 'mauritanian' ? 'selected' : '' }}">Mauritanian</option>
                                            <option value="mauritian" {{ $supplier->nationality == 'mauritian' ? 'selected' : '' }}>Mauritian</option>
                                            <option value="mexican" {{ $supplier->nationality == 'mexican' ? 'selected' : '' }}>Mexican</option>
                                            <option value="micronesian" {{ $supplier->nationality == 'micronesian' ? 'selected' : '' }}>Micronesian</option>
                                            <option value="moldovan" {{ $supplier->nationality == 'moldovan' ? 'selected' : '' }}>Moldovan</option>
                                            <option value="monacan" {{ $supplier->nationality == 'monacan' ? 'selected' : '' }}>Monacan</option>
                                            <option value="mongolian" {{ $supplier->nationality == 'mongolian' ? 'selected' : '' }}>Mongolian</option>
                                            <option value="moroccan" {{ $supplier->nationality == 'moroccan' ? 'selected' : '' }}>Moroccan</option>
                                            <option value="mosotho" {{ $supplier->nationality == 'motswana' ? 'selected' : '' }}>Mosotho</option>
                                            <option value="motswana" {{ $supplier->nationality == 'motswana' ? 'selected' : '' }}>Motswana</option>
                                            <option value="mozambican" {{ $supplier->nationality == 'mozambican' ? 'selected' : '' }}>Mozambican</option>
                                            <option value="namibian" {{ $supplier->nationality == 'namibian' ? 'selected' : '' }}>Namibian</option>
                                            <option value="nauruan" {{ $supplier->nationality == 'nauruan' ? 'selected' : '' }}>Nauruan</option>
                                            <option value="nepalese" {{ $supplier->nationality == 'nepalese' ? 'selected' : '' }}>Nepalese</option>
                                            <option value="new zealander" {{ $supplier->nationality == 'new zealander' ? 'selected' : '' }}>New Zealander</option>
                                            <option value="ni-vanuatu" {{ $supplier->nationality == 'ni-vanuatu' ? 'selected' : '' }}>Ni-Vanuatu</option>
                                            <option value="nicaraguan" {{ $supplier->nationality == 'nicaraguan' ? 'selected' : '' }}>Nicaraguan</option>
                                            <option value="nigerien" {{ $supplier->nationality == 'nigerien' ? 'selected' : '' }}>Nigerien</option>
                                            <option value="north korean" {{ $supplier->nationality == 'north korean' ? 'selected' : '' }}>North Korean</option>
                                            <option value="northern irish" {{ $supplier->nationality == 'northern irish' ? 'selected' : '' }}>Northern Irish</option>
                                            <option value="norwegian" {{ $supplier->nationality == 'norwegian' ? 'selected' : '' }}>Norwegian</option>
                                            <option value="omani" {{ $supplier->nationality == 'omani' ? 'selected' : '' }}>Omani</option>
                                            <option value="pakistani" {{ $supplier->nationality == 'pakistani' ? 'selected' : '' }}>Pakistani</option>
                                            <option value="palauan" {{ $supplier->nationality == 'palauan' ? 'selected' : '' }}>Palauan</option>
                                            <option value="panamanian" {{ $supplier->nationality == 'panamanian' ? 'selected' : '' }}>Panamanian</option>
                                            <option value="papua new guinean" {{ $supplier->nationality == 'papua new guinean' ? 'selected' : '' }}>Papua New Guinean</option>
                                            <option value="paraguayan" {{ $supplier->nationality == 'paraguayan' ? 'selected' : '' }}>Paraguayan</option>
                                            <option value="peruvian" {{ $supplier->nationality == 'peruvian' ? 'selected' : '' }}>Peruvian</option>
                                            <option value="polish" {{ $supplier->nationality == 'polish' ? 'selected' : '' }}>Polish</option>
                                            <option value="portuguese" {{ $supplier->nationality == 'portuguese' ? 'selected' : '' }}>Portuguese</option>
                                            <option value="qatari" {{ $supplier->nationality == 'qatari' ? 'selected' : '' }}>Qatari</option>
                                            <option value="romanian" {{ $supplier->nationality == 'romanian' ? 'selected' : '' }}>Romanian</option>
                                            <option value="russian" {{ $supplier->nationality == 'russian' ? 'selected' : '' }}>Russian</option>
                                            <option value="rwandan" {{ $supplier->nationality == 'rwandan' ? 'selected' : '' }}>Rwandan</option>
                                            <option value="saint lucian" {{ $supplier->nationality == 'saint lucian' ? 'selected' : '' }}>Saint Lucian</option>
                                            <option value="salvadoran" {{ $supplier->nationality == 'salvadoran' ? 'selected' : '' }}>Salvadoran</option>
                                            <option value="samoan" {{ $supplier->nationality == 'samoan' ? 'selected' : '' }}>Samoan</option>
                                            <option value="san marinese" {{ $supplier->nationality == 'san marinese' ? 'selected' : '' }}>San Marinese</option>
                                            <option value="sao tomean" {{ $supplier->nationality == 'sao tomean' ? 'selected' : '' }}>Sao Tomean</option>
                                            <option value="saudi" {{ $supplier->nationality == 'saudi' ? 'selected' : '' }}>Saudi</option>
                                            <option value="scottish" {{ $supplier->nationality == 'scottish' ? 'selected' : '' }}>Scottish</option>
                                            <option value="senegalese" {{ $supplier->nationality == 'senegalese' ? 'selected' : '' }}>Senegalese</option>
                                            <option value="serbian" {{ $supplier->nationality == 'serbian' ? 'selected' : '' }}>Serbian</option>
                                            <option value="seychellois" {{ $supplier->nationality == 'seychellois' ? 'selected' : '' }}>Seychellois</option>
                                            <option value="sierra leonean" {{ $supplier->nationality == 'sierra leonean' ? 'selected' : '' }}>Sierra Leonean</option>
                                            <option value="singaporean" {{ $supplier->nationality == 'singaporean' ? 'selected' : '' }}>Singaporean</option>
                                            <option value="slovakian" {{ $supplier->nationality == 'slovakian' ? 'selected' : '' }}>Slovakian</option>
                                            <option value="slovenian" {{ $supplier->nationality == 'slovenian' ? 'selected' : '' }}>Slovenian</option>
                                            <option value="solomon islander" {{ $supplier->nationality == 'afghan' ? 'selected' : '' }}>Solomon Islander</option>
                                            <option value="somali" {{ $supplier->nationality == 'somali' ? 'selected' : '' }}>Somali</option>
                                            <option value="south african" {{ $supplier->nationality == 'south african' ? 'selected' : '' }}>South African</option>
                                            <option value="south korean" {{ $supplier->nationality == 'south korean' ? 'selected' : '' }}>South Korean</option>
                                            <option value="spanish" {{ $supplier->nationality == 'spanish' ? 'selected' : '' }}>Spanish</option>
                                            <option value="sri lankan" {{ $supplier->nationality == 'sri lankan' ? 'selected' : '' }}>Sri Lankan</option>
                                            <option value="sudanese" {{ $supplier->nationality == 'sudanese' ? 'selected' : '' }}>Sudanese</option>
                                            <option value="surinamer" {{ $supplier->nationality == 'surinamer' ? 'selected' : '' }}>Surinamer</option>
                                            <option value="swazi" {{ $supplier->nationality == 'swazi' ? 'selected' : '' }}>Swazi</option>
                                            <option value="swedish" {{ $supplier->nationality == 'swedish' ? 'selected' : '' }}>Swedish</option>
                                            <option value="swiss" {{ $supplier->nationality == 'swiss' ? 'selected' : '' }}>Swiss</option>
                                            <option value="syrian" {{ $supplier->nationality == 'syrian' ? 'selected' : '' }}>Syrian</option>
                                            <option value="taiwanese" {{ $supplier->nationality == 'taiwanese' ? 'selected' : '' }}>Taiwanese</option>
                                            <option value="tajik" {{ $supplier->nationality == 'tajik' ? 'selected' : '' }}>Tajik</option>
                                            <option value="tanzanian" {{ $supplier->nationality == 'tanzanian' ? 'selected' : '' }}>Tanzanian</option>
                                            <option value="thai" {{ $supplier->nationality == 'thai' ? 'selected' : '' }}>Thai</option>
                                            <option value="togolese" {{ $supplier->nationality == 'togolese' ? 'selected' : '' }}>Togolese</option>
                                            <option value="tongan" {{ $supplier->nationality == 'tongan' ? 'selected' : '' }}>Tongan</option>
                                            <option value="trinidadian or tobagonian" {{ $supplier->nationality == 'trinidadian or tobagonian' ? 'selected' : '' }}>Trinidadian or Tobagonian</option>
                                            <option value="tunisian" {{ $supplier->nationality == 'tunisian' ? 'selected' : '' }}>Tunisian</option>
                                            <option value="turkish" {{ $supplier->nationality == 'turkish' ? 'selected' : '' }}>Turkish</option>
                                            <option value="tuvaluan" {{ $supplier->nationality == 'tuvaluan' ? 'selected' : '' }}>Tuvaluan</option>
                                            <option value="ugandan" {{ $supplier->nationality == 'ugandan' ? 'selected' : '' }}>Ugandan</option>
                                            <option value="ukrainian" {{ $supplier->nationality == 'ukrainian' ? 'selected' : '' }}>Ukrainian</option>
                                            <option value="uruguayan" {{ $supplier->nationality == 'uruguayan' ? 'selected' : '' }}>Uruguayan</option>
                                            <option value="uzbekistani" {{ $supplier->nationality == 'uzbekistani' ? 'selected' : '' }}>Uzbekistani</option>
                                            <option value="venezuelan" {{ $supplier->nationality == 'venezuelan' ? 'selected' : '' }}>Venezuelan</option>
                                            <option value="vietnamese" {{ $supplier->nationality == 'vietnamese' ? 'selected' : '' }}>Vietnamese</option>
                                            <option value="welsh" {{ $supplier->nationality == 'welsh' ? 'selected' : '' }}>Welsh</option>
                                            <option value="yemenite" {{ $supplier->nationality == 'yemenite' ? 'selected' : '' }}>Yemenite</option>
                                            <option value="zambian" {{ $supplier->nationality == 'zambian' ? 'selected' : '' }}>Zambian</option>
                                            <option value="zimbabwean" {{ $supplier->nationality == 'zimbabwean' ? 'selected' : '' }}>Zimbabwean</option>
                                        </select>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Trade License Number</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="text" class="form-control" name="trade_license_number" placeholder="Trade License Number"
                                               value="{{old('trade_license_number',$supplier->trade_license_number)}}">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Trade Registration Place</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="text" class="form-control" name="trade_registration_place" placeholder="Trade Registration Place"
                                               value="{{old('trade_registration_place',$supplier->trade_registration_place)}}">

                                    </div>
                                </div>
                                </br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Preferences</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="sub category" class="col-form-label text-md-end">{{ __('Preference ID') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input class="widthinput form-control" name="prefered_id" id="prefered_id" placeholder="Preference ID"
                                               autofocus  value="{{old('prefered_id',$supplier->prefered_id)}}">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="Label" class="col-form-label text-md-end">{{ __('Preference Label') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input class="widthinput form-control" name="prefered_label" id="label" placeholder="Label" autofocus
                                               value="{{old('prefered_label',$supplier->prefered_label)}}">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="Label" class="col-form-label text-md-end">{{ __('Communication Channels') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <div class="form-check form-check-inline" >
                                            <input class="form-check-input" name="is_communication_mobile" type="checkbox" id="option1" {{ old('is_communication_mobile') ? 'checked' : '' }}
                                                {{ $supplier->is_communication_mobile == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="option1">Mobile</label>
                                        </div>
                                        <div class="form-check form-check-inline" for="option2">
                                            <input class="form-check-input" name="is_communication_email" type="checkbox" id="option2" {{ old('is_communication_email') ? 'checked' : '' }}
                                                {{ $supplier->is_communication_email == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="option2">Email</label>
                                        </div>
                                        <div class="form-check form-check-inline" for="option3">
                                            <input class="form-check-input" name="is_communication_fax" type="checkbox" id="option3" {{ old('is_communication_fax') ? 'checked' : '' }}
                                                {{ $supplier->is_communication_fax == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="option3">Fax</label>
                                        </div>
                                        <div class="form-check form-check-inline" for="option4">
                                            <input class="form-check-input" name="is_communication_postal" type="checkbox" id="option4" value="postal"
                                                {{ $supplier->is_communication_postal == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="option4">Postal</label>
                                        </div>
                                        <div class="form-check form-check-inline" for="option5">
                                            <input class="form-check-input" name="is_communication_any" type="checkbox" id="option5" value="any"
                                                {{ $supplier->is_communication_any == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="option5">Any</label>
                                        </div>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="Label" class="col-form-label text-md-end">{{ __('Payment Channels') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        @foreach($paymentMethods as $paymentMethod)
                                            <div class="form-check form-check-inline" >
                                                <input class="form-check-input" name="payment_methods[]" type="checkbox" id="inlineCheckbox{{$paymentMethod->id}}"
                                                       value="{{ $paymentMethod->id }}" @if (in_array($paymentMethod->id, $vendorPaymentMethods)) checked="checked" @endif>
                                                <label class="form-check-label" for="inlineCheckbox{{$paymentMethod->id}}">
                                                    {{$paymentMethod->payment_methods}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="shipping_address" class="col-form-label text-md-end">{{ __('Shipping Address') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <textarea cols="25" rows="5" class="form-control" name="shipping_address"
                                                  placeholder="Shipping Address"> {{old('shipping_address',$supplier->shipping_address)}}</textarea>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="Label" class="col-form-label text-md-end">{{ __('Billing Address') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                    <textarea cols="25" rows="5" class=" form-control" name="billing_address" placeholder="Billing Address"
                                              id="billing_address" autofocus> {{old('billing_address',$supplier->billing_address)}}</textarea>
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <!-- <span class="error">* </span> -->
                                        <label for="sub category" class="col-form-label text-md-end">{{ __('Notes') }}</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <textarea cols="25" rows="5" class=" form-control" name="notes" id="note" placeholder="Notes"
                                                  autofocus>{{old('notes',$supplier->notes)}}</textarea>
                                    </div>
                                </div>
                                </br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Upload Documents</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Passport</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="file" class="form-control" id="passport-upload" name="passport_copy_file"
                                               accept="application/pdf, image/*">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Trade License </label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="file" class="form-control" id="trade-licence-upload" name="trade_license_file"
                                               placeholder="Upload Trade License" accept="application/pdf, image/*">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Vat Certificate</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="file" class="form-control" id="vat-certificate-upload" name="vat_certificate_file"
                                               placeholder="Upload Vat Certificate" accept="application/pdf, image/*">
                                    </div>
                                </div>
                                </br>
                            </div>
                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-6 col-md-12">
                                        <label for="choices-single-default" class="form-label widthinput">Other Document</label>
                                    </div>
                                    <div class="col-xxl-9 col-lg-6 col-md-12">
                                        <input type="file" class="form-control" multiple id="documents" name="documents[]"
                                               placeholder="Upload Other Document" accept="application/pdf, image/*">
                                    </div>
                                </div>
                                </br>
                            </div>
                        </div>
                        <div class="card preview-div" @if($supplier->is_any_document_available == false) hidden @endif>
                            <div class="body">
                                <div class="row p-2">
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="file1-preview">
                                            @if($supplier->passport_copy_file)
                                                <h6 class="fw-bold text-center mb-1">Passport</h6>
                                                <iframe src="{{ url('vendor/passport/' . $supplier->passport_copy_file) }}" alt="Passport"></iframe>
                                                <button  type="button" class="btn btn-sm btn-info mt-3 ">
                                                    <a href="{{ url('vendor/passport/' . $supplier->passport_copy_file) }}" download class="text-white">
                                                        Download
                                                    </a>
                                                </button>
                                                <button  type="button" class="btn btn-sm btn-danger mt-3 delete-button"
                                                         data-file-type="PASSPORT"> Delete</button>

                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="file2-preview">
                                            @if($supplier->trade_license_file)
                                                <h6 class="fw-bold text-center">Trade License</h6>
                                                <iframe src="{{ url('vendor/trade_license/' . $supplier->trade_license_file) }}" alt="Trade License "></iframe>
                                                <button  type="button" class="btn btn-sm btn-info mt-3 ">
                                                    <a href="{{ url('vendor/trade_license/' . $supplier->trade_license_file) }}" download class="text-white">
                                                        Download
                                                    </a>
                                                </button>
                                                <button  type="button" class="btn btn-sm btn-danger mt-3 delete-button"
                                                         data-file-type="TRADE_LICENSE"> Delete</button>

                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="file3-preview">
                                            @if($supplier->vat_certificate_file)
                                                <h6 class="fw-bold text-center">VAT Certificate</h6>
                                                <iframe src="{{ url('vendor/vat_certificate/' . $supplier->vat_certificate_file) }}" alt="VAT Certificate"></iframe>
                                                <button  type="button" class="btn btn-sm btn-info mt-3 ">
                                                    <a href="{{ url('vendor/vat_certificate/' . $supplier->vat_certificate_file) }}" download class="text-white">
                                                        Download
                                                    </a>
                                                </button>
                                                <button  type="button" class="btn btn-sm btn-danger mt-3 delete-button" data-file-type="VAT"> Delete</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row p-2 pb-4">
                                    @if($supplier->supplierDocuments->count() > 0)
                                        <h6 class="fw-bold text-center">Other Documents</h6>
                                        @foreach($supplier->supplierDocuments as $document)
                                            <div class="col-lg-4 col-md-12 col-sm-12 text-center" id="preview-div-{{$document->id}}">
                                                <div>
                                                    <iframe src="{{ url('vendor/other-documents/' . $document->file) }}"
                                                         class="mt-2" alt="Other Document"></iframe>
                                                    <button  type="button" class="btn btn-sm btn-info mt-3 ">
                                                        <a href="{{url('vendor/other-documents/' . $document->file)}}" download class="text-white">
                                                            Download
                                                        </a>
                                                    </button>
                                                    <button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button"
                                                        data-id="{{ $document->id }}"> Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="file4-preview">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <div id="tabId" hidden>
            <div class="tab">
                <h6 class="tablinks" onclick="openCity(event, 'addSupplierDynamically')" id="defaultOpen">Add Supplier Addons</h6>
                <h6 class="tablinks" onclick="openCity(event, 'uploadExcel')">Upload Supplier's Addon Excel</h6>
            </div>
            <div id="addSupplierDynamically" class="tabcontent">
                <div class="row">
                    <div class="card-body">
                        <div class="col-xxl-12 col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-md-12 p-0">
                                    <div class="col-md-12 form_field_outer p-0">
                                        <div class="row form_field_outer_row" id="row-1">
                                            <div class="col-xxl-2 col-lg-6 col-md-12">
                                                <label for="choices-single-default" class="form-label font-size-13">Choose Addons</label>
                                                <select class="addons" id="addon_1" data-index="1" name="supplierAddon[1][addon_id][]" multiple="true" style="width: 100%;">
                                                    @foreach($addons as $addon)
                                                        <option class="{{$addon->id}}" id="addon_1_{{$addon->id}}" value="{{$addon->id}}">{{$addon->addon_code}} - ( {{ $addon->AddonName->name }} )</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-xxl-2 col-lg-6 col-md-12">
                                            <label for="choices-single-default" class="form-label font-size-13">Minimum Lead Time</label>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Min</span>
                                                </div>
                                                <input id="lead_time_1" aria-label="measurement" aria-describedby="basic-addon2"
                                                class="lead_time form-control widthinput @error('lead_time') is-invalid @enderror"
                                                name="supplierAddon[1][lead_time]" maxlength="3"
                                                value="{{ old('lead_time') }}"  autocomplete="lead_time" oninput="checkGreater(this, 1)">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                </div>
                                            </div>
                                            <span id="minLeadTimeError_1" class="minLeadTimeError invalid-feedback-lead"></span>

                                        </div>
                                        <div class="col-xxl-2 col-lg-6 col-md-12">
                                            <label for="choices-single-default" class="form-label font-size-13">Maximum Lead Time</label>

                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Max</span>
                                                </div>
                                                <input id="lead_time_max_1" aria-label="measurement" aria-describedby="basic-addon2"
                                                class="lead_time_max form-control widthinput @error('lead_time_max') is-invalid @enderror"
                                                 name="supplierAddon[1][lead_time_max]" oninput="checkGreater(this, 1)"
                                                value="{{ old('lead_time_max') }}"  autocomplete="lead_time_max" maxlength="3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                </div>
                                            </div>
                                            <span id="maxLeadTimeError_1" class="maxLeadTimeError invalid-feedback-lead"></span>
                                        </div>
                                            <div class="col-xxl-1 col-lg-1 col-md-1">
                                                <label for="choices-single-default" class="form-label font-size-13">Currency</label>
                                                <select name="supplierAddon[1][currency]" id="currency_1" class="widthinput form-control" onchange="changeCurrency(1)">
                                                    <option value="AED">AED</option>
                                                    <option value="USD">USD</option>
                                                </select>
                                            </div>
                                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" >
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                <div class="input-group">
                                                    <input id="addon_purchase_price_1" oninput="inputNumberAbs(this)" name="supplierAddon[1][addon_purchase_price]" placeholder="1 USD = 3.6725 AED"
                                                    class="widthinput form-control @error('addon_purchase_price') is-invalid @enderror" value="{{ old('addon_purchase_price') }}"
                                                     autocomplete="addon_purchase_price" autofocus>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" hidden>
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                <div class="input-group">
                                                    <input id="addon_purchase_price_in_usd_1" oninput="inputNumberAbs(this)" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" name="supplierAddon[1][addon_purchase_price_in_usd]" placeholder="Enter Addons Purchase Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                                            </div>

                                                <div class="form-group col-xxl-1 col-lg-1 col-md-1" style="margin-top: 26px;">
                                                    <a class="btn_round btn-danger removeButton" id="remove-1" data-index="1">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn" onclick="clickAdd()">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                </div>
                            </div>
                        </div>
                        </br>
                    </div>
                </div>
            </div>

            <div id="uploadExcel" class="tabcontent">
                <div class="row">
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <label for="choices-single-default" class="form-label font-size-13">Upload Supplier Addon Excel File</label>
                        <input type="file" name="file" placeholder="Choose file" id="supplierAddonExcel" class="form-control" onchange="readURL(this);">
                        <span id="supplierAddonExcelError" class="required-class paragraph-class"></span>
                    </div>
                    <div class="col-xxl-6 col-lg-6 col-md-6"><center>
                        <label for="choices-single-default" class="form-label font-size-13">Download Supplier Addon Excel Template</label>
                        </br>
                        <a  class="btn btn-sm btn-info" href="{{ route('addon.get_student_data') }}"><i class="fa fa-arrow-down" aria-hidden="true"></i> Download Excel Template</a>
                        </center>
                    </div>
                </div>
                <br>
                @include('suppliers.dataerrors')
            </div>
            </br>
        </div>
            <input id="activeTab" name="activeTab" hidden>
            <input id="hiddencontact" name="hiddencontact" value="{{old('hiddencontact')}}" hidden>
            <input id="hiddencontactCountryCode" name="hiddencontactCountryCode" value="{{old('hiddencontactCountryCode')}}" hidden>

            <div class="col-xxl-12 col-lg-12 col-md-12">
                <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
            </div>
        </form>
    </div>

    <input type="hidden" id="passport-file-delete" name="is_passport_delete" value="">
    <input type="hidden" id="vat-file-delete" name="is_vat_delete" value="">
    <input type="hidden" id="trade-license-file-delete" name="is_trade_license_delete" value="">
    <input type="hidden" value="" id="indexValue">
    <div class="overlay"></div>

    @endif
@endcan
    <script type="text/javascript">
        var activeTab = '';
        var PreviousHidden = '';
        // var selectedAddons = [];
        var addonDropdownCount = 1;
        // var previousremoveChecked = '';
        // globalThis.selectedAddons .push(brandId);
      var sub ='1';

        $(document).on("input", ".contact", function() {
            this.value = this.value.replace(/\D/g,'');
        });
      var SupplierTypesVal = {!! json_encode($supplierTypes) !!};
      var supplierAddons = {!! json_encode($supplierAddons) !!};
        const file1InputLicense = document.querySelector("#passport-upload");
        const file2InputLicense = document.querySelector("#trade-licence-upload");
        const file3InputLicense = document.querySelector("#vat-certificate-upload");
        const file4InputLicense = document.querySelector("#documents");

        const previewFile1 = document.querySelector("#file1-preview");
        const previewFile2 = document.querySelector("#file2-preview");
        const previewFile3 = document.querySelector("#file3-preview");
        const previewFile4 = document.querySelector("#file4-preview");

        file1InputLicense.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);
            const files = event.target.files;
            while (previewFile1.firstChild) {
                previewFile1.removeChild(previewFile1.firstChild);
            }
            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFile1.appendChild(iframe);
            }
            else if (file.type.match("image/*"))
            {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();
                image.src = objectUrl;
                previewFile1.appendChild(image);
            }

        });
        file2InputLicense.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);

            const files = event.target.files;
            while (previewFile2.firstChild) {
                previewFile2.removeChild(previewFile2.firstChild);
            }
            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFile2.appendChild(iframe);
            }
            else if (file.type.match("image/*"))
            {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();
                image.src = objectUrl;
                previewFile2.appendChild(image);
            }
        });
        file3InputLicense.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);

            const files = event.target.files;
            while (previewFile3.firstChild) {
                previewFile3.removeChild(previewFile3.firstChild);
            }
            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFile3.appendChild(iframe);
            }
            else if (file.type.match("image/*"))
            {
                const objectUrl = URL.createObjectURL(file);
                const image = new Image();
                image.src = objectUrl;
                previewFile3.appendChild(image);
            }
        });
        file4InputLicense.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);

            const files = event.target.files;
            // while (previewFile4.firstChild) {
            //     previewFile4.removeChild(previewFile4.firstChild);
            // }
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.match("application/pdf")) {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFile4.appendChild(iframe);
                } else if (file.type.match("image/*")) {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFile4.appendChild(image);
                }
            }
        });

        // var deletedDocuments = [];
        var deletedDocuments = new Array();
        $('.document-delete-button').on('click',function(){
            let id = $(this).attr('data-id');
            if (confirm('Are you sure you want to Delete this item ?')) {
                $('#preview-div-'+id).remove();
                deletedDocuments.push(id);
                console.log(deletedDocuments);
            }
        });

        $('.delete-button').on('click',function(){
            var fileType = $(this).attr('data-file-type');
            if (confirm('Are you sure you want to Delete this item ?')) {
                if(fileType == 'PASSPORT') {
                    $('#file1-preview').remove();
                    $('#passport-file-delete').val(1);

                }else if(fileType == 'TRADE_LICENSE') {
                    $('#file2-preview').remove();
                    $('#trade-license-file-delete').val(1);

                }else if(fileType == 'VAT') {
                    $('#file3-preview').remove();
                    $('#vat-file-delete').val(1);

                }
            }
        });

        $(document).ready(function ()
        {
            $('#category').select2({
                minimumResultsForSearch: -1,
                placeholder:"Choose Category",
            });
            $('#person_contact_by').select2({
                placeholder:"Choose Person Contacted By",
            });

            $('#nationality').select2({

                placeholder:"Choose Nationality",
            });
            $('#type').select2({
                minimumResultsForSearch: -1,
                placeholder:"Choose Vendor Type",
            });
            $('.demand-planning-vendor-checkbox').click(function() {
                $('.demand-planning-vendor-checkbox').not(this).prop('checked', false);
            });

            $(document.body).on('select2:unselect', "#category", function (e) {
                getSubCategories();
                $('#vendor-name-checkbox').attr('hidden', true);
                $('#MMC-checkbox').prop('checked', false);
                $('#AMS-checkbox').prop('checked', false);
            })
            $(document.body).on('select2:select', "#category", function (e) {
                getSubCategories();
            });
            $(document.body).on('select2:select', "#supplier_type", function (e) {
                var data = $('#supplier_type').val();
                if(jQuery.inArray("demand_planning", data) === -1) {
                    // demand planning not exits
                    $('#vendor-name-checkbox').attr('hidden', true);
                }else{
                    // demand planning exits
                    $('#vendor-name-checkbox').attr('hidden', false);
                }
            });
            $(document.body).on('select2:unselect', "#supplier_type", function (e) {
                var data = e.params.data.id;
                if(data == 'demand_planning') {
                    // demand planning not exits
                    $('#vendor-name-checkbox').attr('hidden', true);
                    $('#MMC-checkbox').prop('checked', false);
                    $('#AMS-checkbox').prop('checked', false);
                }
            });
            function getSubCategories() {
                var categories =  $('#category').val();
                let url = '{{ route('vendor.sub-categories') }}';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        categories: categories,
                    },
                    success:function (data) {
                        $('#supplier_type').empty();
                        $('#supplier_type').html('<option value="">Select Sub Category</option>');
                        if(data) {
                            jQuery.each(data, function(key,value){
                                $('#supplier_type').append('<option value="'+ value.slug +'">'+ value.name +'</option>');
                            });
                        }
                    }
                });
            }

            // show dynamic div based on supplier type
            if(SupplierTypesVal.includes('accessories') || SupplierTypesVal.includes('spare_parts'))
            {
                document.getElementById("tabId").hidden=false;
            }
            else
            {
                hideDynamic();
            }
            $.ajaxSetup
            ({
                headers:
                {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#addon_1').select2({
                allowClear: true,
                minimumResultsForSearch: -1,
                placeholder:"Choose Addon Name....     Or     Type Here To Search....",
            });
            $("#supplier_type").attr("data-placeholder","Choose Supplier Type....     Or     Type Here To Search....");
            $("#supplier_type").select2();

            var index = 1;
            $('#indexValue').val(index);
            $(document.body).on('select2:select', ".addons", function (e) {
                var index = $(this).attr('data-index');
                var value = e.params.data.id;
                hideOption(index,value);
                dropdownDisable();
            });
            $(document.body).on('select2:unselect', ".addons", function (e) {
                var index = $(this).attr('data-index');
                var data = e.params.data;
                appendOption(index,data);
                dropdownEnable();
            });
            function hideOption(index,value) {
                var indexValue = $('#indexValue').val();
                for (var i = 1; i <= indexValue; i++) {
                    if (i != index) {
                        var currentId = 'addon_' + i;
                        $('#' + currentId + ' option[value=' + value + ']').detach();
                    }
                }
            }
            function appendOption(index,data) {
                var indexValue = $('#indexValue').val();
                for(var i=1;i<=indexValue;i++) {
                    if(i != index) {
                        $('#addon_'+i).append($('<option>', {value: data.id, text : data.text}))
                    }
                }
            }
            $(document.body).on('click', ".removeButton", function (e) {
                var indexNumber = $(this).attr('data-index');

                $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                    var id = (this.value);
                    var text = (this.text);
                    addOption(id,text)
                });

                $(this).closest('#row-'+indexNumber).remove();
                $('.form_field_outer_row').each(function(i){
                    var index = +i + +1;
                    $(this).attr('id','row-'+ index);
                    $(this).find('.addons').attr('data-index', index);
                    $(this).find('.addons').attr('id','addon_'+ index);
                    $(this).find('.currency').attr('id','currency_' + index);
                    $(this).find('.currency').attr('onchange','changeCurrency(' + index + ')');
                    $(this).find('.usd-price-div').attr('id','div_price_in_usd_' + index);
                    $(this).find('.currency').attr('name','supplierAddon['+ index +'][currency]');
                    $(this).find('.addons').attr('name','supplierAddon['+ index +'][addon_id]');
                    $(this).find('.purchase_price_in_USD').attr('name','supplierAddon['+ index +'][addon_purchase_price_in_usd]');
                    $(this).find('.purchase_price_in_AED').attr('name','supplierAddon['+ index +'][addon_purchase_price]');
                    $(this).find('.div-purchase_price_in_AED').attr('id','div_price_in_aed_' + index);
                    $(this).find('.purchase_price_in_AED').attr('id','addon_purchase_price_'+ index);
                    $(this).find('.purchase_price_in_USD').attr('id','addon_purchase_price_in_usd_'+ index);
                    $(this).find('.purchase_price_in_USD').attr('onkeyup','calculateAED('+ index +')');
                    $(this).find('.addon-purchase-price-div').attr('id','div_price_in_aedOne_'+ index);
                    $(this).find('.purchase_price_in_USD').attr('id','addon_purchase_price_in_usd_'+ index);
                    $(this).find('.addon-purchase-price').attr('id', 'addon_purchase_price_'+ index);
                    $(this).find('.addon-purchase-price').attr('name', 'supplierAddon['+ index +'][addon_purchase_price]');
                    $(this).find('a').attr('data-index', index);
                    $(this).find('a').attr('id','remove-'+ index);

                        $(this).find('.lead_time').attr('id','lead_time_'+ index);
                        $(this).find('.lead_time').attr('name','supplierAndPrice['+index+'][lead_time]');
                        $(this).find('.lead_time').attr('oninput','checkGreater(this, '+index+')');
                        $(this).find('.minLeadTimeError').attr('id','minLeadTimeError_'+index);

                        $(this).find('.lead_time_max').attr('id','lead_time_max_'+ index);
                        $(this).find('.lead_time_max').attr('name','supplierAndPrice['+index+'][lead_time_max]');
                        $(this).find('.lead_time_max').attr('oninput','checkGreater(this, '+index+')');
                        $(this).find('.maxLeadTimeError').attr('id','maxLeadTimeError_'+index);

                    $('#addon_'+index).select2
                    ({
                        placeholder:"Choose Addon....     Or     Type Here To Search....",
                        allowClear: true,
                        minimumResultsForSearch: -1,
                    });
                });
                dropdownEnable();
            })
            function addOption(id,text) {
                var indexValue = $('#indexValue').val();
                for(var i=1;i<=indexValue;i++) {
                    $('#addon_'+i).append($('<option>', {value: id, text :text}))
                }
            }

        });
        function clickAdd()
        {
            var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
            var selectedAddonTypes = $("#supplier_type").val();
            $('#indexValue').val(index);
            var selectedAddons = [];
            for(let i=1; i<index; i++)
            {
                var eachSelectedAddon = $("#addon_"+i).val();
                $.each(eachSelectedAddon, function( ind, value )
                {
                    selectedAddons.push(value);
                });
            }
            $.ajax
            ({
                url:"{{ route('addon.getAddonForSupplier')}}",
                type: "POST",
                data:
                    {
                        id: '{{ $supplier->id }}',
                        selectedAddonTypes: selectedAddonTypes,
                        supplierAddons: supplierAddons,
                        filteredArray: selectedAddons,
                        _token: '{{csrf_token()}}'
                    },
                dataType : 'json',
                success: function(data)
                {
                    myarray = data;
                    var size= myarray.length;
                    if(size >= 1)
                    {
                        $(".form_field_outer").append(`
                            <div class="row form_field_outer_row" id="row-${index}">
                                <div class="col-xxl-2 col-lg-6 col-md-12">
                                    <label for="choices-single-default" class="form-label font-size-13">Choose Addons</label>
                                    <select class="addons"  id="addon_${index}" data-index="${index}" name="supplierAddon[${index}][addon_id][]" multiple="true" style="width: 100%;" >
                                    @foreach($addons as $addon)
                                        <option class="{{$addon->id}}" id="addon_${index}_{{$addon->id}}" value="{{$addon->id}}">{{$addon->addon_code}} - ( {{ $addon->AddonName->name }} )</option>
                                                        @endforeach
                                        </select>
                        </div>
                        <div class="col-xxl-2 col-lg-6 col-md-12">
                                            <label for="choices-single-default" class="form-label font-size-13">Minimum Lead Time</label>
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Min</span>
                                                </div>
                                                <input id="lead_time_${index}" aria-label="measurement" aria-describedby="basic-addon2"
                                                class="lead_time form-control widthinput @error('lead_time') is-invalid @enderror"
                                                name="supplierAddon[${index}][lead_time]" maxlength="3"
                                                value="{{ old('lead_time') }}"  autocomplete="lead_time" oninput="checkGreater(this, ${index})">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                </div>
                                            </div>
                                            <span id="minLeadTimeError_${index}" class="minLeadTimeError invalid-feedback-lead"></span>

                                        </div>
                                        <div class="col-xxl-2 col-lg-6 col-md-12">
                                            <label for="choices-single-default" class="form-label font-size-13">Maximum Lead Time</label>

                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Max</span>
                                                </div>
                                                <input id="lead_time_max_${index}" aria-label="measurement" aria-describedby="basic-addon2"
                                                class="lead_time_max form-control widthinput @error('lead_time_max') is-invalid @enderror"
                                                name="supplierAddon[${index}][lead_time_max]" oninput="checkGreater(this, ${index})"
                                                value="{{ old('lead_time_max') }}"  autocomplete="lead_time_max" maxlength="3">
                                                <div class="input-group-append">
                                                    <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                </div>
                                            </div>
                                            <span id="maxLeadTimeError_${index}" class="maxLeadTimeError invalid-feedback-lead"></span>
                                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            <label for="choices-single-default" class="form-label font-size-13">Currency</label>
                            <select name="supplierAddon[${index}][currency]"  id="currency_${index}" class="widthinput form-control currency" onchange="changeCurrency(${index})">
                                        <option value="AED">AED</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <div class="col-xxl-2 col-lg-3 col-md-3 div-purchase_price_in_AED" id="div_price_in_aed_${index}">
                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                    <div class="input-group">
                                    <input id="addon_purchase_price_${index}" oninput="inputNumberAbs(this)" class="widthinput
                                    form-control @error('addon_purchase_price') is-invalid @enderror purchase_price_in_AED" name="supplierAddon[${index}][addon_purchase_price]"
                                    placeholder="1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus >
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                                </div>
                                <div class="col-xxl-2 col-lg-3 col-md-3 usd-price-div" id="div_price_in_usd_${index}" hidden>
                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                    <div class="input-group">
                                    <input id="addon_purchase_price_in_usd_${index}" oninput="inputNumberAbs(this)" class="widthinput form-control purchase_price_in_USD
                                     @error('addon_purchase_price_in_usd') is-invalid @enderror" name="supplierAddon[${index}][addon_purchase_price_in_usd]" placeholder="Enter Addons Purchase Price In USD"
                                      value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                    </div>

                                </div>
                                </div>
                                <div class="form-group col-xxl-1 col-lg-1 col-md-1" style="margin-top: 26px;">
                                    <a class="btn_round btn-danger removeButton" id="remove-${index}" data-index="${index}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                            `);
                        let addonDropdownData   = [];
                        $.each(data,function(key,value)
                        {
                            addonDropdownData.push
                            ({
                                id: value.id,
                                text: value.addon_code +'- ('+value.addon_name.name +')'
                            });
                        });
                        $('#addon_'+index).html("");
                        $('#addon_'+index).select2
                        ({
                            placeholder:"Choose Addons....     Or     Type Here To Search....",
                            allowClear: true,
                            data: addonDropdownData,
                            minimumResultsForSearch: -1,
                        });
                    }
                }
            });
        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
        function openCity(evt, tabName)
        {
            activeTab = tabName;
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++)
            {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++)
            {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        var contact_number = window.intlTelInput(document.querySelector("#contact_number"),
        {
            separateDialCode: true,
            preferredCountries:["ae"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
        var alternative_contact_number = window.intlTelInput(document.querySelector("#alternative_contact_number"),
        {
            separateDialCode: true,
            preferredCountries:["ae"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
        var phone = window.intlTelInput(document.querySelector("#phone"),
            {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "full",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        var office_phone = window.intlTelInput(document.querySelector("#office_phone"),
            {
                separateDialCode: true,
                preferredCountries:["ae"],
                hiddenInput: "full",
                utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        // $("form").submit(function(e)
        $('body').on('submit', '#createSupplierForm', function (e)
        {
            sub = '2';
            var inputSupplier = $('#supplier').val();
            var inputSupplierType = $('#supplier_type').val();
            var inputSupplierCatgeory = $('#category').val();
            var inputContactNumber = $('#contact_number').val();
            var inputAlternativeContactNumber = $('#alternative_contact_number').val();
            var inputEmail  = $('#email').val();
            var inputPhone = $('#phone').val();
            var inputOfficePhone = $('#office_phone').val();

            var formInputError = false;
            if(inputSupplier == '')
            {
                $msg = "Supplier field is required";
                showSupplierError($msg);
                formInputError = true;
                e.preventDefault();
            }
            else{
                removeSupplierError();
            }
            if(inputSupplierType == '')
            {
                $msg = "Supplier type is required";
                showSupplierTypeError($msg);
                formInputError = true;
                e.preventDefault();
            }
            else{
                removeSupplierTypeError();
            }
            if(inputEmail == '')
            {
                $msg = "Email field is required";
                showEmailError($msg);

                formInputError = true;
                e.preventDefault();
            }else{
                
                removeEmailError();
            }

            if(inputSupplierCatgeory == '') {
                $msg = "Supplier Category is required";
                showSupplierCategoryError($msg);
                formInputError = true;
                e.preventDefault();
            }else{
                removeSupplierCategoryError();
            }
            if(inputOfficePhone != '') {
                if (inputOfficePhone.length < 5 ) {
                    console.log("less tahn 5");
                    $msg = "Minimum 5 digits required";
                    showOfficePhoneError($msg);
                    formInputError = true;
                    e.preventDefault();
                } else if (inputOfficePhone.length > 15) {
                    console.log("more tahn 5");
                    $msg = "Maximum 15 digits allowed";
                    showOfficePhoneError($msg);
                    formInputError = true;
                    e.preventDefault();

                } else {
                    removeOfficePhoneError();
                }
            }else{
                removeOfficePhoneError();
            }
           
            if(inputAlternativeContactNumber != '') {
               if(inputAlternativeContactNumber.length > 15) {
                   $msg = "Maximum 15 digits allowed";
                   showAlternativeContactNumberError($msg);
                   formInputError = true;
                   e.preventDefault();
               }else if(inputAlternativeContactNumber.length < 5 && inputAlternativeContactNumber.length > 0) {

                   $msg = "Minimum 5 digits required";
                   showAlternativeContactNumberError($msg);
                   formInputError = true;
                   e.preventDefault();
               }else {
                   removeAlternativeContactNumberError();
               }
            }else {
                   removeAlternativeContactNumberError();
               }
            if(inputContactNumber == '')
            {
                $msg ="Contact number is required";
                showContactNumberError($msg);
                formInputError = true;
                e.preventDefault();
            } else{
                if(inputContactNumber != '') {
                    if(inputContactNumber.length > 15) {
                        $msg = "Maximum 15 digits allowed";
                        showContactNumberError($msg);
                        formInputError = true;
                        e.preventDefault();
                    }else if(inputContactNumber.length < 5 ) {

                        $msg = "Minimum 5 digits required";
                        showContactNumberError($msg);
                        formInputError = true;
                        e.preventDefault();
                    }else {
                        var contactNumber = contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                        var name = $('#supplier').val();
                        var supplierType = $('#supplier_type').val();

                        var url = '{{ route('vendor.vendorUniqueCheck') }}';
                        e.preventDefault();
                        if(contactNumber.length > 0 && name.length > 0 && supplierType.length > 0) {
                            $.ajax({
                                type: "GET",
                                url: url,
                                dataType: "json",
                                data: {
                                    contact_number: contactNumber,
                                    name: name,
                                    supplierType:supplierType,
                                    id: '{{ $supplier->id }}'
                                },
                                success:function (data) {
                                    if(data.error) {
                                        formInputError = true;
                                        $('#submit').html('Save');
                                        $('.overlay').hide();
                                        showContactNumberError(data.error);
                                        removeSupplierError();
                                    }else if(data.name_error) {
                                        removeContactNumberError();
                                        showSupplierError(data.name_error);
                                        formInputError == true;
                                        e.preventDefault();
                                        $('#submit').html('Save');
                                        $('.overlay').hide();
                                    }
                                    else{
                                        removeSupplierError();
                                        removeContactNumberError();
                                        if(formInputError == false )
                                        {
                                            submitForm(e);
                                        }
                                    }
                                }
                      
                          
                            });
                        }
                    }
                }
            }
        });
        function submitForm(e)
        {
            var full_number = contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='contact_number[full]'").val(full_number);
                var full_alternative_contact_number = alternative_contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='alternative_contact_number[full]'").val(full_alternative_contact_number);
                $("input[name='activeTab'").val(activeTab);

                e.preventDefault();
                var actionType = $('#submit').val();
                var formData = new FormData(document.getElementById("createSupplierForm"));
                formData.append('deletedDocuments[]', deletedDocuments);
                console.log(formData);
                var $notifications = $('#notifications')
                $('#submit').html('Sending..');
                $('.overlay').show();
                $.ajax({
                type:'POST',
                url: "{{ route('suppliers.updatedetails') }}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (result) =>
                {
                    console.log(result.data)
                    let dataErrorCard = document.getElementById('dataErrorCard');
                    dataErrorCard.hidden = true
                    if(result.data.headingError)
                    {
                        document.getElementById("supplierAddonExcelError").textContent = result.data.headingError;
                        $('#submit').html('Save');
                    $('.overlay').hide();
                    }
                    else if(result.data.dataError)
                    {
                        document.getElementById("notifications").textContent = '';
                        if(result.data.dataError.length > 0)
                        {
                            let dataErrorCard = document.getElementById('dataErrorCard');
                            dataErrorCard.hidden = false
                            var i = 0;
                            $.each(result.data.dataError, function(key, value)
                            {
                                i = i+1;
                                $notifications.append('<tr><td> '+i+' </td>' +
                                '<td>'+ value.addon_code + '</td>' +
                                '<td>'+ value.currency + '</td>' +
                                '<td>'+ value.purchase_price + '</td>' +
                                '<td>'+ value.lead_time_max + '</td>' +
                                '<td>'+ value.lead_time_min + '</td>' +
                                '<td>'+ value.addonError + '</br>'+value.currencyError+'</br>'+value.priceErrror+'</br>'+value.minLeadTimeErrror+'</br>'+value.maxLeadTimeErrror+'</td>' +
                                '</tr>');
                            });
                        }
                        $('#submit').html('Save');
                        $('.overlay').hide();
                    }
                    else if(result.data.successStore)
                    {
                        document.location.href="{{route('suppliers.index') }}";
                    }
                },
                error: function(data)
                {
                console.log('Error:', data);
                $('#submit').html('Save');
                    $('.overlay').hide();
                }
                });
        }
        //===== delete the form fieed row
          $("body").on("click", ".remove_node_btn_frm_field", function ()
        {
            $(this).closest(".form_field_outer_row").remove();
        });
        function changeCurrency(i)
        {
            var e = document.getElementById("currency_"+i);
            var value = e.value;
            if(value == 'USD')
            {
                let currencyUSD = document.getElementById('div_price_in_usd_'+i);
                currencyUSD.hidden = false
                let currencyAED = document.getElementById('div_price_in_aed_'+i);
                currencyAED.hidden = false
                $("#addon_purchase_price_"+i).attr('disabled','disabled');
                $("#addon_purchase_price_"+i).val('');
            }
            else
            {
                let currencyUSD = document.getElementById('div_price_in_usd_'+i);
                currencyUSD.hidden = true
                $("#addon_purchase_price_"+i).removeAttr('disabled');
                $("#addon_purchase_price_"+i).val('');
            }
            dropdownEnable();
        }
        function calculateAED(i)
        {
            var usd = $("#addon_purchase_price_in_usd_"+i).val();
            var aed = usd * 3.6725;
            if(aed == 0)
            {
                document.getElementById('addon_purchase_price_'+i).value = "";
            }
            else
            {
                document.getElementById('addon_purchase_price_'+i).value = aed;
            }
        }

        function readURL(input)
        {
            var allowedExtension = ['xlsx','xlsm','xlsb','xltx','xltm','xls','xlt','xls','xml','xlam','xla','xlw','xlr'];
            var fileExtension = input.value.split('.').pop().toLowerCase();
            var isValidFile = false;
            for(var index in allowedExtension)
            {
                if(fileExtension === allowedExtension[index])
                {
                    isValidFile = true;
                    break;
                }
            }
            if(!isValidFile)
            {
                document.getElementById("supplierAddonExcelError").textContent='Allowed Extensions are : *.' + allowedExtension.join(', *.');
            }
            else
            {
                document.getElementById("supplierAddonExcelError").textContent='';
                var file = '';
                var file = $('#supplierAddonExcel').val();
            }
        }
    function validationOnKeyUp(clickInput)
        {
            if(clickInput.id == 'supplier_type')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    if(value.legth != 0)
                    {
                        $msg = "Supplier Type is required";
                        showSupplierTypeError($msg);
                        hideDynamic();
                    }
                }
                else
                {
                    removeSupplierTypeError();
                    SupplierTypesVal = $("#supplier_type").val();

                    if(SupplierTypesVal.includes('accessories') || SupplierTypesVal.includes('spare_parts'))
                    {
                        showDynamic();
                    }
                    else
                    {
                        hideDynamic();
                    }
                }
            }
            if(clickInput.id == 'supplier')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    if(value.legth != 0)
                    {
                        $msg = "Supplier field is required";
                        showSupplierError($msg);
                    }
                }
                else
                {
                    removeSupplierError();
                }
            }
            if(clickInput.id == 'category')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    if(value.legth != 0)
                    {
                        $msg = "Supplier Category is required";
                        showSupplierCategoryError($msg);
                    }
                }
                else
                {
                    removeSupplierCategoryError();
                }
            }
            if(clickInput.id == 'contact_number')
            {
                var value = clickInput.value;
                if(value != '')
                {
                    if(value.legth != 0)
                    {
                        if(value.length < 5)
                        {
                            $msg = "Minimum 5 digits required";
                            showContactNumberError($msg);
                        }
                        else if(value.length > 15 )
                        {
                            $msg = "Maximum 15 digits allowed";
                            showContactNumberError($msg);
                        }
                        else
                        {
                            removeContactNumberError();
                        }
                        removeEmailError();
                        removeAlternativeContactNumberError();
                    }
                }
                else
                {
                    if(sub == '2')
                    {
                        $msg ="Contact number is required";
                        showContactNumberError($msg);
                    //     showEmailError($msg);
                    //     showAlternativeContactNumberError($msg);
                    }
                    else
                    {
                        removeContactNumberError();
                    }
                }
            }
             if(clickInput.id == 'alternative_contact_number')
            {
                var value = clickInput.value;
                if(value != '')
                {
                    if(value.legth != 0)
                    {
                        if(value.length < 5)
                        {
                            $msg = "Minimum 5 digits required";
                            showAlternativeContactNumberError($msg);
                        }
                        else if(value.length > 15 )
                        {
                            $msg = "Maximum 15 digits allowed";
                            showAlternativeContactNumberError($msg);
                        }
                        else
                        {
                            removeAlternativeContactNumberError();
                        }
                        removeEmailError();
                        removeContactNumberError();
                    }
                }
                else
                {
                        removeAlternativeContactNumberError();
                }
            }
            if(clickInput.id == 'office_phone')
            {
                var value = clickInput.value;
                if(value != '')
                {
                    if(value.legth != 0)
                    {
                        if(value.length < 5)
                        {
                            $msg = "Minimum 5 digits required";
                            showOfficePhoneError($msg);
                        }
                        else if(value.length > 15 )
                        {
                            $msg = "Maximum 15 digits allowed";
                            showOfficePhoneError($msg);
                        }
                        else
                        {
                            removeOfficePhoneError();
                        }
                    }
                }
                else
                {
                    removeOfficePhoneError();
                }
            }
            if(clickInput.id == 'phone')
            {
                var value = clickInput.value;
                if(value != '')
                {
                    if(value.legth != 0)
                    {
                        if(value.length < 5)
                        {
                            $msg = "Minimum 5 digits required";
                            showPhoneError($msg);
                        }
                        else if(value.length > 15 )
                        {
                            $msg = "Maximum 15 digits allowed";
                            showPhoneError($msg);
                        }
                        else
                        {
                            removePhoneError();
                        }
                    }
                }
                else
                {
                    removePhoneError();
                }
            }
             if(clickInput.id == 'email')
            {
                var value = clickInput.value;
                if(value != '')
                {
                    if(value.legth != 0)
                    {
                        const validateEmail = (email) => {
                        return email.match(
                            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        );
                        };
                        const validate = () => {
                        const $emailRequired = $('#emailRequired');
                        const email = $('#email').val();
                        $emailRequired.text('');

                        if(validateEmail(email))
                        {
                            removeEmailError();
                            removeAlternativeContactNumberError();
                            removeContactNumberError();
                        }
                        else
                        {
                            $msg = email + ' is invalid.'
                            showEmailError($msg);
                        }
                        return false;
                        }
                        $('#email').on('input', validate);
                    }
                }
                else
                {
                    // if(sub == '2')
                    // {
                    //     $msg ="One among contact number or alternative contact number or email is required";
                    //     showContactNumberError($msg);
                    //     showEmailError($msg);
                    //     showAlternativeContactNumberError($msg);
                    // }
                    // else
                    // {
                        removeEmailError();
                    // }
                }
            }
        }
        function showSupplierCategoryError($msg)
        {
            document.getElementById("supplierCategoryError").textContent=$msg;
            document.getElementById("category").classList.add("is-invalid");
            document.getElementById("supplierCategoryError").classList.add("paragraph-class");

        }
        function removeSupplierCategoryError()
        {
            document.getElementById("supplierCategoryError").textContent="";
            document.getElementById("category").classList.remove("is-invalid");
            document.getElementById("supplierCategoryError").classList.remove("paragraph-class");
        }
        function showContactNumberError($msg)
        {
            document.getElementById("contactRequired").textContent=$msg;
            document.getElementById("contactRequired").classList.remove("requiredOne");
            document.getElementById("contactRequired").classList.add("paragraph-class");
            document.getElementById("contact_number").classList.add("is-invalid");
        }
        function removeContactNumberError()
        {
            document.getElementById("contactRequired").textContent="";
            document.getElementById("contactRequired").classList.remove("paragraph-class");
            document.getElementById("contact_number").classList.remove("is-invalid");
        }
        function showAlternativeContactNumberError($msg)
        {
            document.getElementById("alternativeContactRequired").textContent=$msg;
            document.getElementById("alternativeContactRequired").classList.remove("requiredOne");
            document.getElementById("alternativeContactRequired").classList.add("paragraph-class");
            document.getElementById("alternative_contact_number").classList.add("is-invalid");
        }
        function removeAlternativeContactNumberError()
        {
            document.getElementById("alternativeContactRequired").textContent="";
            document.getElementById("alternativeContactRequired").classList.remove("paragraph-class");
            document.getElementById("alternative_contact_number").classList.remove("is-invalid");
        }
        function showPhoneError($msg)
        {
            document.getElementById("phoneRequired").textContent=$msg;
            document.getElementById("phoneRequired").classList.remove("requiredOne");
            document.getElementById("phoneRequired").classList.add("paragraph-class");
            document.getElementById("phone").classList.add("is-invalid");
        }
        function removePhoneError()
        {
            document.getElementById("phoneRequired").textContent="";
            document.getElementById("phoneRequired").classList.remove("paragraph-class");
            document.getElementById("phone").classList.remove("is-invalid");
        }
        function showOfficePhoneError($msg)
        {
            document.getElementById("officePhoneRequired").textContent=$msg;
            document.getElementById("officePhoneRequired").classList.remove("requiredOne");
            document.getElementById("officePhoneRequired").classList.add("paragraph-class");
            document.getElementById("office_phone").classList.add("is-invalid");
        }
        function removeOfficePhoneError()
        {
            document.getElementById("officePhoneRequired").textContent="";
            document.getElementById("officePhoneRequired").classList.remove("paragraph-class");
            document.getElementById("office_phone").classList.remove("is-invalid");
        }
        function showEmailError($msg)
        {
            document.getElementById("emailRequired").textContent=$msg;
            document.getElementById("email").classList.add("is-invalid");
            document.getElementById("emailRequired").classList.remove("requiredOne");
            document.getElementById("emailRequired").classList.add("paragraph-class");
        }
        function removeEmailError()
        {
            document.getElementById("emailRequired").textContent="";
            document.getElementById("email").classList.remove("is-invalid");
            document.getElementById("emailRequired").classList.remove("paragraph-class");
        }
        function showSupplierError($msg)
        {
            document.getElementById("supplierError").textContent=$msg;
            document.getElementById("supplier").classList.add("is-invalid");
            document.getElementById("supplierError").classList.add("paragraph-class");
        }
        function removeSupplierError()
        {
            document.getElementById("supplierError").textContent="";
            document.getElementById("supplier").classList.remove("is-invalid");
            document.getElementById("supplierError").classList.remove("paragraph-class");
        }
        function showSupplierTypeError($msg)
        {
            document.getElementById("supplierTypeError").textContent=$msg;
            document.getElementById("supplier_type").classList.add("is-invalid");
            document.getElementById("supplierTypeError").classList.add("paragraph-class");
        }
        function removeSupplierTypeError()
        {
            document.getElementById("supplierTypeError").textContent="";
            document.getElementById("supplier_type").classList.remove("is-invalid");
            document.getElementById("supplierTypeError").classList.remove("paragraph-class");
        }
    </script>
     <script>
       $(function() {
           $('#supplier_type').select2({
             tags: true,
             placeholder: 'Select an option',
             templateSelection : function (tag, container){
                    // here we are finding option element of tag and
                // if it has property 'locked' we will add class 'locked-tag'
                // to be able to style element in select
                var $option = $('#supplier_type option[value="'+tag.id+'"]');
                if ($option.attr('locked')){
                   $(container).addClass('locked-tag');
                   tag.locked = true;
                }
                return tag.text;
             },
           })
           .on('select2:unselecting', function(e){
                // before removing tag we check option element of tag and
              // if it has property 'locked' we will create error to prevent all select2 functionality
               if ($(e.params.args.data.element).attr('locked')) {
                var confirm = alertify.confirm('You are not able to remove this type',function (e) {
                           }).set({title:"Not Able to Remove"})
                   e.preventDefault();
                }
             });
           $('#category').select2({
               tags: true,
               placeholder: 'Select an option',
               templateSelection : function (tag, container){
                   // here we are finding option element of tag and
                   // if it has property 'locked' we will add class 'locked-tag'
                   // to be able to style element in select
                   var $option = $('#category option[value="'+tag.id+'"]');
                   if ($option.attr('locked')){
                       $(container).addClass('locked-tag');
                       tag.locked = true;
                   }
                   return tag.text;
               },
           })
               .on('select2:unselecting', function(e){
                   // before removing tag we check option element of tag and
                   // if it has property 'locked' we will create error to prevent all select2 functionality
                   if ($(e.params.args.data.element).attr('locked')) {
                       var confirm = alertify.confirm('You are not able to remove this type',function (e) {
                       }).set({title:"Not Able to Remove"})
                       e.preventDefault();
                   }
               });
        });
        function showDynamic()
        {
            var selectedAddonTypes = $("#supplier_type").val();
            $.ajax
            ({
                url:"{{ route('addon.getAddonForSupplier')}}",
                type: "POST",
                data:
                    {
                        selectedAddonTypes: selectedAddonTypes,
                        _token: '{{csrf_token()}}'
                    },
                dataType : 'json',
                success: function(data)
                {
                    myarray = data;
                    var size= myarray.length;
                    if(size >= 1)
                    {
                        let addonDropdownData   = [];
                        $.each(data,function(key,value)
                        {
                            addonDropdownData.push
                            ({
                                id: value.id,
                                text: value.addon_code +'- ('+value.addon_name.name +')'
                            });
                        });
                        var countIndexRow = $(".form_field_outer").find(".form_field_outer_row").length;
                        for (let i = 1; i <= countIndexRow; i++)
                        {
                            $('#addon_'+i).html("");
                            $('#addon_'+i).select2
                            ({
                                placeholder:"Choose Addons....     Or     Type Here To Search....",
                                allowClear: true,
                                data: addonDropdownData,
                                minimumResultsForSearch: -1,
                            });
                        }
                    }
                }
            });
            document.getElementById("tabId").hidden=false;
        }
        function hideDynamic()
        {
            document.getElementById("tabId").hidden=true;
        }
        function inputNumberAbs(currentPriceInput)
        {
            var id = currentPriceInput.id;
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d.]/g, '');
            if(val.split('.').length>2)
            {
                val =val.replace(/\.+$/,"");
            }
            input.value = val;
            if(val != '')
            {
                dropdownDisable();
            }
            else
            {
                dropdownEnable();
            }
        }
        function dropdownDisable()
        {
            document.getElementById("mainSelect").hidden=true;
            document.getElementById("subSelect").hidden=false;
            var selectedSupType = '';
            var selectedSupType = $("#supplier_type").val();
            selectedSupType.forEach((item) => {
                document.getElementById(item).hidden=false;
            });
        }
        function dropdownEnable()
        {
            var canEnableDropdown = 'no';
            if(canEnableDropdown == 'no')
            {
                var countNotKitSuplr = $(".form_field_outer").find(".form_field_outer_row").length;
                for (let i = 1; i <= countNotKitSuplr; i++)
                {
                    if($('#currency_'+i).val() == 'USD')
                    {
                        if($('#addon_'+i).val() == '' && $('#addon_purchase_price_'+i).val() == '' && $('#addon_purchase_price_in_usd_'+i).val() == '')
                        {
                            canEnableDropdown = 'yes';
                            break;
                        }
                    }
                    else
                    {
                        if($('#addon_'+i).val() == '' && $('#addon_purchase_price_'+i).val() == '')
                        {
                            canEnableDropdown = 'yes';
                            break;
                        }
                    }
                }
            }
            if(canEnableDropdown == 'yes')
            {
                document.getElementById("mainSelect").hidden=false;
                document.getElementById("subSelect").hidden=true;
                var selectedSupType = '';
                var selectedSupType = $("#supplier_type").val();
                selectedSupType.forEach((item) => {
                    document.getElementById(item).hidden=true;
                });
            }
        }
        function showAlert()
        {
            var confirm = alertify.confirm('You are not able to edit this field while any Addon is in selection',function (e) {
                   }).set({title:"Remove Addons And Prices"})
        }
        function checkGreater(CurrentInput, row)
        {
            var id = CurrentInput.id
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d]/g, '');
            input.value = val;
            var minLeadTime = $('#lead_time_'+row).val();
            var maxLeadTime = $('#lead_time_max_'+row).val();
            // if(minLeadTime != '')
            // {
            //     document.getElementById('lead_time_max_'+row).readOnly = false;
            // }
            // else
            // {
            //     document.getElementById('lead_time_max_'+row).readOnly = true;
            // }
            if(minLeadTime != '' && maxLeadTime != '')
            {
                if(Number(minLeadTime) > Number(maxLeadTime))
                {
                    var id = CurrentInput.id;
                    if(id == 'lead_time')
                    {
                        showMinLeadTimeError(row);
                        removeMaxLeadTimeError(row);
                    }
                    else
                    {
                        showMaxLeadTimeError(row);
                        removeMinLeadTimeError(row);
                    }
                }
                else
                {
                    removeMinLeadTimeError(row);
                    removeMaxLeadTimeError(row);
                }
            }
            else
            {
                removeMinLeadTimeError(row);
                removeMaxLeadTimeError(row);
            }
        }
        function showMinLeadTimeError(row)
        {
            document.getElementById('minLeadTimeError_'+row).textContent="Enter smaller value than max leadtime";
            document.getElementById('lead_time_'+row).classList.add("is-invalid");
            document.getElementById('minLeadTimeError_'+row).classList.add("paragraph-class");
        }
        function showMaxLeadTimeError(row)
        {
            document.getElementById('maxLeadTimeError_'+row).textContent="Enter higher value than min leadtime";
            document.getElementById('lead_time_max_'+row).classList.add("is-invalid");
            document.getElementById('maxLeadTimeError_'+row).classList.add("paragraph-class");
        }
        function removeMinLeadTimeError(row)
        {
            document.getElementById('minLeadTimeError_'+row).textContent="";
            document.getElementById('lead_time_'+row).classList.remove("is-invalid");
            document.getElementById('minLeadTimeError_'+row).classList.remove("paragraph-class");
        }
        function removeMaxLeadTimeError(row)
        {
            document.getElementById('maxLeadTimeError_'+row).textContent="";
            document.getElementById('lead_time_max_'+row).classList.remove("is-invalid");
            document.getElementById('maxLeadTimeError_'+row).classList.remove("paragraph-class");
        }
</script>
@endsection
