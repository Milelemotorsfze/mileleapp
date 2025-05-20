@extends('layouts.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
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
@section('content')
@canany(['demand-planning-supplier-create', 'addon-supplier-create', 'vendor-edit'])
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-create', 'vendor-edit','demand-planning-supplier-create']);
    @endphp
    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Create Vendors</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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

        <form id="createSupplierForm" name="createSupplierForm" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>
            </div>
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
                                           placeholder="Individual / Company Name" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus
                                           onchange="validationOnKeyUp(this)">
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
                                        <option ></option>
                                        <option value="Individual">Individual</option>
                                        <option value="Company">Company</option>
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
                                        <option></option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}">{{ $category->name }}</option>
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
                                            class="form-control widthinput" autofocus
                                            onchange="validationOnKeyUp(this)">
                                            <option value="">Choose Sub Category</option>
                                    </select>
                                    <span id="supplierTypeError" class=" invalid-feedback"></span>
                                </div>
{{--                                <div class="col-xxl-9 col-lg-6 col-md-12" id="subSelect" hidden onclick="showAlert()">--}}
{{--                                    <div id="supplier_type_sub" style="width: 100%; background-color:#e4e4e4;" class="form-control widthinput">--}}
{{--                                        <span id="accessories" class="spanSub" hidden>Accessories</span>--}}
{{--                                        <span id="freelancer" class="spanSub" hidden>Freelancer</span>--}}
{{--                                        <span id="garage" class="spanSub" hidden>Garage</span>--}}
{{--                                        <span id="spare_parts" class="spanSub" hidden>Spare Parts</span>--}}
{{--                                        <span id="warranty" class="spanSub" hidden>Warranty</span>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
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
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
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
                                    <label for="sub category" class="col-form-label text-md-end">{{ __('Web Address') }}</label>
                                </div>
                                <div class="col-xxl-9 col-lg-6 col-md-12">
                                    <input class="widthinput form-control" name="web_address" id="web_address" placeholder="Web Address" autofocus>
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
                                    <textarea cols="25" rows="5" class=" form-control" name="comment" placeholder="Comment" id="comment" autofocus></textarea>
                                </div>
                            </div>
                            </br>
                        </div>

                        <div class="col-xxl-6 col-lg-6 col-md-12" hidden id="vendor-name-checkbox">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-6 col-md-12">
                                    <!-- <span class="error">* </span> -->
                                    <label  class="col-form-label text-md-end">{{ __('Vendor') }}</label>
                                </div>
                                <div class="col-xxl-9 col-lg-6 col-md-12">
                                    <input type="checkbox" name="is_mmc" id="MMC-checkbox" class="demand-planning-vendor-checkbox">
                                    <label for="MMC-checkbox" class="col-form-label text-md-end ml-3">{{ __('Is vendor MMC?') }}</label>
                                    <input type="checkbox" name="is_ams" id="AMS-checkbox" class="demand-planning-vendor-checkbox">
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
                                    <input id="contact_number" type="number" class="widthinput form-control @error('contact_number[full]') is-invalid @enderror"
                                           name="contact_number[main]" placeholder="Enter Contact Number" value="{{old('hiddencontact')}}"
                                            autocomplete="contact_number[main]" autofocus onkeyup="validationOnKeyUp(this)">
                                    <span id="contactRequired" class="contact_number required-class"></span>
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
                                    <input id="alternative_contact_number" type="number"
                                           class="widthinput form-control @error('alternative_contact_number[full]') is-invalid @enderror" maxlength="15"
                                           minlength="5" name="alternative_contact_number[main]"
                                           placeholder="Enter Alternative Contact Number" value="{{ old('alternative_contact_number[full]') }}"
                                           autocomplete="alternative_contact_number[full]" autofocus onkeyup="validationOnKeyUp(this)">
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
                                    <input id="phone" type="number"
                                           class="widthinput form-control @error('phone[full]') is-invalid @enderror" maxlength="15"
                                           minlength="5" name="phone[main]" placeholder="Enter Phone" value="{{ old('phone[full]') }}"
                                           autocomplete="phone[full]" autofocus onkeyup="validationOnKeyUp(this)">
                                     <span id="phoneRequired" class="phone required-class"></span>
                                </div>
                            </div>
                            </br>
                        </div>
                        <div class="col-xxl-6 col-lg-6 col-md-12">
                            <div class="row">
                                <div class="col-xxl-3 col-lg-6 col-md-12">
                                    <label for="office_phone" class="col-form-label text-md-end">{{ __('Office Contact Number') }}</label>
                                </div>
                                <div class="col-xxl-9 col-lg-6 col-md-12">
                                    <input id="office_phone" type="number"
                                           class="widthinput form-control @error('office_phone[full]') is-invalid @enderror" name="office_phone[main]"
                                           placeholder="Enter Office Contact Number" value="{{ old('office_phone[full]') }}" minlength="5" maxlength="15"
                                           autocomplete="office_phone[full]" autofocus onkeyup="validationOnKeyUp(this)">
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
                                    <input id="email" type="email" class="widthinput form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email"
                                           value="{{ old('email') }}" autofocus onkeyup="validationOnKeyUp(this)">
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
                                    <input type="text" class="form-control" name="fax" placeholder="fax">

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
                                    <textarea cols="25" rows="5" class="form-control" name="address" placeholder="Address Details"></textarea>
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
                                           placeholder="Enter Contact Person" value="{{ old('contact_person') }}"  autocomplete="contact_person" autofocus>
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
                                    <label for="passport_number" class="col-form-label widthinput">{{ __('Passport Number') }}</label>
                                </div>
                                <div class="col-xxl-9 col-lg-6 col-md-12">
                                    <input type="text" class="form-control" name="passport_number" placeholder="Passport Number">
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
                                        <option value="afghan">Afghan</option>
                                        <option value="albanian">Albanian</option>
                                        <option value="algerian">Algerian</option>
                                        <option value="american">American</option>
                                        <option value="andorran">Andorran</option>
                                        <option value="angolan">Angolan</option>
                                        <option value="antiguans">Antiguans</option>
                                        <option value="argentinean">Argentinean</option>
                                        <option value="armenian">Armenian</option>
                                        <option value="australian">Australian</option>
                                        <option value="austrian">Austrian</option>
                                        <option value="azerbaijani">Azerbaijani</option>
                                        <option value="bahamian">Bahamian</option>
                                        <option value="bahraini">Bahraini</option>
                                        <option value="bangladeshi">Bangladeshi</option>
                                        <option value="barbadian">Barbadian</option>
                                        <option value="barbudans">Barbudans</option>
                                        <option value="batswana">Batswana</option>
                                        <option value="belarusian">Belarusian</option>
                                        <option value="belgian">Belgian</option>
                                        <option value="belizean">Belizean</option>
                                        <option value="beninese">Beninese</option>
                                        <option value="bhutanese">Bhutanese</option>
                                        <option value="bolivian">Bolivian</option>
                                        <option value="bosnian">Bosnian</option>
                                        <option value="brazilian">Brazilian</option>
                                        <option value="british">British</option>
                                        <option value="bruneian">Bruneian</option>
                                        <option value="bulgarian">Bulgarian</option>
                                        <option value="burkinabe">Burkinabe</option>
                                        <option value="burmese">Burmese</option>
                                        <option value="burundian">Burundian</option>
                                        <option value="cambodian">Cambodian</option>
                                        <option value="cameroonian">Cameroonian</option>
                                        <option value="canadian">Canadian</option>
                                        <option value="cape verdean">Cape Verdean</option>
                                        <option value="central african">Central African</option>
                                        <option value="chadian">Chadian</option>
                                        <option value="chilean">Chilean</option>
                                        <option value="chinese">Chinese</option>
                                        <option value="colombian">Colombian</option>
                                        <option value="comoran">Comoran</option>
                                        <option value="congolese">Congolese</option>
                                        <option value="costa rican">Costa Rican</option>
                                        <option value="croatian">Croatian</option>
                                        <option value="cuban">Cuban</option>
                                        <option value="cypriot">Cypriot</option>
                                        <option value="czech">Czech</option>
                                        <option value="danish">Danish</option>
                                        <option value="djibouti">Djibouti</option>
                                        <option value="dominican">Dominican</option>
                                        <option value="dutch">Dutch</option>
                                        <option value="east timorese">East Timorese</option>
                                        <option value="ecuadorean">Ecuadorean</option>
                                        <option value="egyptian">Egyptian</option>
                                        <option value="emirian">Emirian</option>
                                        <option value="equatorial guinean">Equatorial Guinean</option>
                                        <option value="eritrean">Eritrean</option>
                                        <option value="estonian">Estonian</option>
                                        <option value="ethiopian">Ethiopian</option>
                                        <option value="fijian">Fijian</option>
                                        <option value="filipino">Filipino</option>
                                        <option value="finnish">Finnish</option>
                                        <option value="french">French</option>
                                        <option value="gabonese">Gabonese</option>
                                        <option value="gambian">Gambian</option>
                                        <option value="georgian">Georgian</option>
                                        <option value="german">German</option>
                                        <option value="ghanaian">Ghanaian</option>
                                        <option value="greek">Greek</option>
                                        <option value="grenadian">Grenadian</option>
                                        <option value="guatemalan">Guatemalan</option>
                                        <option value="guinea-bissauan">Guinea-Bissauan</option>
                                        <option value="guinean">Guinean</option>
                                        <option value="guyanese">Guyanese</option>
                                        <option value="haitian">Haitian</option>
                                        <option value="herzegovinian">Herzegovinian</option>
                                        <option value="honduran">Honduran</option>
                                        <option value="hungarian">Hungarian</option>
                                        <option value="icelander">Icelander</option>
                                        <option value="indian">Indian</option>
                                        <option value="indonesian">Indonesian</option>
                                        <option value="iranian">Iranian</option>
                                        <option value="iraqi">Iraqi</option>
                                        <option value="irish">Irish</option>
                                        <option value="israeli">Israeli</option>
                                        <option value="italian">Italian</option>
                                        <option value="ivorian">Ivorian</option>
                                        <option value="jamaican">Jamaican</option>
                                        <option value="japanese">Japanese</option>
                                        <option value="jordanian">Jordanian</option>
                                        <option value="kazakhstani">Kazakhstani</option>
                                        <option value="kenyan">Kenyan</option>
                                        <option value="kittian and nevisian">Kittian and Nevisian</option>
                                        <option value="kuwaiti">Kuwaiti</option>
                                        <option value="kyrgyz">Kyrgyz</option>
                                        <option value="laotian">Laotian</option>
                                        <option value="latvian">Latvian</option>
                                        <option value="lebanese">Lebanese</option>
                                        <option value="liberian">Liberian</option>
                                        <option value="libyan">Libyan</option>
                                        <option value="liechtensteiner">Liechtensteiner</option>
                                        <option value="lithuanian">Lithuanian</option>
                                        <option value="luxembourger">Luxembourger</option>
                                        <option value="macedonian">Macedonian</option>
                                        <option value="malagasy">Malagasy</option>
                                        <option value="malawian">Malawian</option>
                                        <option value="malaysian">Malaysian</option>
                                        <option value="maldivan">Maldivan</option>
                                        <option value="malian">Malian</option>
                                        <option value="maltese">Maltese</option>
                                        <option value="marshallese">Marshallese</option>
                                        <option value="mauritanian">Mauritanian</option>
                                        <option value="mauritian">Mauritian</option>
                                        <option value="mexican">Mexican</option>
                                        <option value="micronesian">Micronesian</option>
                                        <option value="moldovan">Moldovan</option>
                                        <option value="monacan">Monacan</option>
                                        <option value="mongolian">Mongolian</option>
                                        <option value="moroccan">Moroccan</option>
                                        <option value="mosotho">Mosotho</option>
                                        <option value="motswana">Motswana</option>
                                        <option value="mozambican">Mozambican</option>
                                        <option value="namibian">Namibian</option>
                                        <option value="nauruan">Nauruan</option>
                                        <option value="nepalese">Nepalese</option>
                                        <option value="new zealander">New Zealander</option>
                                        <option value="ni-vanuatu">Ni-Vanuatu</option>
                                        <option value="nicaraguan">Nicaraguan</option>
                                        <option value="nigerien">Nigerien</option>
                                        <option value="north korean">North Korean</option>
                                        <option value="northern irish">Northern Irish</option>
                                        <option value="norwegian">Norwegian</option>
                                        <option value="omani">Omani</option>
                                        <option value="pakistani">Pakistani</option>
                                        <option value="palauan">Palauan</option>
                                        <option value="panamanian">Panamanian</option>
                                        <option value="papua new guinean">Papua New Guinean</option>
                                        <option value="paraguayan">Paraguayan</option>
                                        <option value="peruvian">Peruvian</option>
                                        <option value="polish">Polish</option>
                                        <option value="portuguese">Portuguese</option>
                                        <option value="qatari">Qatari</option>
                                        <option value="romanian">Romanian</option>
                                        <option value="russian">Russian</option>
                                        <option value="rwandan">Rwandan</option>
                                        <option value="saint lucian">Saint Lucian</option>
                                        <option value="salvadoran">Salvadoran</option>
                                        <option value="samoan">Samoan</option>
                                        <option value="san marinese">San Marinese</option>
                                        <option value="sao tomean">Sao Tomean</option>
                                        <option value="saudi">Saudi</option>
                                        <option value="scottish">Scottish</option>
                                        <option value="senegalese">Senegalese</option>
                                        <option value="serbian">Serbian</option>
                                        <option value="seychellois">Seychellois</option>
                                        <option value="sierra leonean">Sierra Leonean</option>
                                        <option value="singaporean">Singaporean</option>
                                        <option value="slovakian">Slovakian</option>
                                        <option value="slovenian">Slovenian</option>
                                        <option value="solomon islander">Solomon Islander</option>
                                        <option value="somali">Somali</option>
                                        <option value="south african">South African</option>
                                        <option value="south korean">South Korean</option>
                                        <option value="spanish">Spanish</option>
                                        <option value="sri lankan">Sri Lankan</option>
                                        <option value="sudanese">Sudanese</option>
                                        <option value="surinamer">Surinamer</option>
                                        <option value="swazi">Swazi</option>
                                        <option value="swedish">Swedish</option>
                                        <option value="swiss">Swiss</option>
                                        <option value="syrian">Syrian</option>
                                        <option value="taiwanese">Taiwanese</option>
                                        <option value="tajik">Tajik</option>
                                        <option value="tanzanian">Tanzanian</option>
                                        <option value="thai">Thai</option>
                                        <option value="togolese">Togolese</option>
                                        <option value="tongan">Tongan</option>
                                        <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                                        <option value="tunisian">Tunisian</option>
                                        <option value="turkish">Turkish</option>
                                        <option value="tuvaluan">Tuvaluan</option>
                                        <option value="ugandan">Ugandan</option>
                                        <option value="ukrainian">Ukrainian</option>
                                        <option value="uruguayan">Uruguayan</option>
                                        <option value="uzbekistani">Uzbekistani</option>
                                        <option value="venezuelan">Venezuelan</option>
                                        <option value="vietnamese">Vietnamese</option>
                                        <option value="welsh">Welsh</option>
                                        <option value="yemenite">Yemenite</option>
                                        <option value="zambian">Zambian</option>
                                        <option value="zimbabwean">Zimbabwean</option>
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
                                    <input type="text" class="form-control" name="trade_license_number" placeholder="Trade License Number">
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
                                    <input type="text" class="form-control" name="trade_registration_place" placeholder="Trade Registration Place">

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
                                    <input class="widthinput form-control" name="prefered_id" id="prefered_id" placeholder="Preference ID" autofocus>
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
                                    <input class="widthinput form-control" name="prefered_label" id="label" placeholder="Label" autofocus>
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
                                        <input class="form-check-input" name="is_communication_mobile" type="checkbox" id="option1" value="mobile">
                                        <label class="form-check-label" for="option1">Mobile</label>
                                    </div>
                                    <div class="form-check form-check-inline" for="option2">
                                        <input class="form-check-input" name="is_communication_email" type="checkbox" id="option2" value="email">
                                        <label class="form-check-label" for="option2">Email</label>
                                    </div>
                                    <div class="form-check form-check-inline" for="option3">
                                        <input class="form-check-input" name="is_communication_fax" type="checkbox" id="option3" value="fax" >
                                        <label class="form-check-label" for="option3">Fax</label>
                                    </div>
                                    <div class="form-check form-check-inline" for="option4">
                                        <input class="form-check-input" name="is_communication_postal" type="checkbox" id="option4" value="postal">
                                        <label class="form-check-label" for="option4">Postal</label>
                                    </div>
                                    <div class="form-check form-check-inline" for="option5">
                                        <input class="form-check-input" name="is_communication_any" type="checkbox" id="option5" value="any" >
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
                                            <input class="form-check-input" name="payment_methods[]" value="{{ $paymentMethod->id }}" type="checkbox"  id="inlineCheckbox{{$paymentMethod->id}}">
                                            <label class="form-check-label" for="inlineCheckbox{{$paymentMethod->id}}">{{$paymentMethod->payment_methods}}</label>
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
                                    <textarea cols="25" rows="5" class="form-control" name="shipping_address" placeholder="Shipping Address"></textarea>
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
                                              id="billing_address" autofocus></textarea>
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
                                    <textarea cols="25" rows="5" class=" form-control" name="notes" id="note" placeholder="Notes" autofocus></textarea>
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
                    <div class="card preview-div" hidden>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div id="file1-preview">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div id="file2-preview">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div id="file3-preview">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div id="file4-preview">
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
                                                <select class="addons" id="addon_1" data-index="1" name="supplierAddon[1[addon_id][]"  multiple="true" style="width: 100%;">
                                                </select>
                                                @error('is_primary_payment_method')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
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
                                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1">
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                <div class="input-group">
                                                    <input id="addon_purchase_price_1" oninput="inputNumberAbs(this)"
                                                    class="widthinput form-control @error('addon_purchase_price') is-invalid @enderror"
                                                    name="supplierAddon[1][addon_purchase_price]" placeholder="1 USD = 3.6725 AED"
                                                    value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" hidden>
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                <div class="input-group">
                                                    <input id="addon_purchase_price_in_usd_1" oninput="inputNumberAbs(this)"
                                                    class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                                    name="supplierAddon[1][addon_purchase_price_in_usd]" placeholder="Enter Addons Purchase Price In USD"
                                                    value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                    onkeyup="calculateAED(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                <a class="btn_round removeButton" id="remove-1" data-index="1">
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
                        <a  class="btn btn-sm btn-info" href="{{ route('addon.get_student_data') }}">
                            <i class="fa fa-arrow-down" aria-hidden="true"></i> Download Excel Template</a>
                        </center>
                    </div>
                </div>
                <br>
                @include('suppliers.dataerrors')
            </div>
            </br>

            <input id="activeTab" name="activeTab" hidden>
            <input id="hiddencontact" name="hiddencontact" value="{{old('hiddencontact')}}" hidden>
            <input id="hiddencontactCountryCode" name="hiddencontactCountryCode" value="{{old('hiddencontactCountryCode')}}" hidden>
        </div>

            <div class="col-xxl-12 col-lg-12 col-md-12">
                <button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
            </div>
        </form>
    </div>
    <input type="hidden" value="" id="indexValue">
    <input type="hidden" value="0" id="vendor-unique-error">
    <div class="overlay"></div>
    @endif
    @endcan
    <script type="text/javascript">
        var activeTab = '';
        var PreviousHidden = '';
        var filteredArray = [];
        var addonDropdownCount = 1;
        // var formInputError = false;
      var sub ='1';
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

        $(document).ready(function ()
        {
            $('#category-vehicle').hide();
            $('#category-parts').hide();

            $('#category').select2({
                minimumResultsForSearch: -1,
                placeholder:"Choose Category",
            });

            $('#nationality').select2({
                placeholder:"Choose Nationality",
            });
            $('#person_contact_by').select2({
                placeholder:"Choose Person Contacted By",
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

            $('#addon_1').select2({
                allowClear: true,
                minimumResultsForSearch: -1,
                placeholder:"Choose Addon Name....     Or     Type Here To Search....",
            });
            $("#supplier_type").attr("data-placeholder","Choose Sub Category....     Or     Type Here To Search....");
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
                // var countRow = $(".form_field_outer").find(".form_field_outer_row").length;
                // if(countRow > 1)
                // {
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
                        $(this).attr('data-select2-id','select2-data-row-'+ index);
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
                        // $(this).find('.addon-purchase-price-div').attr('id','div_price_in_aedOne_'+ index);
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
                // }
                // else
                // {
                //     var confirm = alertify.confirm('You are not able to edit this field because atleast ',function (e) {
                //    }).set({title:"Not Able To Remove"})
                // }

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
            var selectedAddonTypes = $("#supplier_type").val();
            $.ajax
            ({
                url:"{{ route('addon.getAddonForSupplier')}}",
                type: "POST",
                data:
                    {
                        selectedAddonTypes: selectedAddonTypes,
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
                                    <select class="addons"  id="addon_${index}" data-index="${index}"
                                    name="supplierAddon[${index}][addon_id][]" multiple="true" style="width: 100%;">
                                    @foreach($addons as $addon)
                                <option class="{{$addon->id}}" id="addon_${index}_{{$addon->id}}"
                                value="{{$addon->id}}">{{$addon->addon_code}} - ( {{ $addon->AddonName->name }} )</option>
                                        @endforeach
                                </select>
                                    @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                                    <select name="supplierAddon[${index}][currency]"  id="currency_${index}" class="widthinput form-control currency"
                                    onchange="changeCurrency(${index})">
                                        <option value="AED">AED</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <div class="col-xxl-2 col-lg-3 col-md-3 div-purchase_price_in_AED" id="div_price_in_aed_${index}">
                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                    <div class="input-group">
                                    <input id="addon_purchase_price_${index}" oninput="inputNumberAbs(this)" class="widthinput
                                    form-control @error('addon_purchase_price') is-invalid @enderror purchase_price_in_AED"
                                    name="supplierAddon[${index}][addon_purchase_price]"
                                    placeholder="1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus >
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                                </div>

                                <div class="col-xxl-2 col-lg-3 col-md-3 usd-price-div" id="div_price_in_usd_${index}" hidden>
                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                    <div class="input-group">
                                    <input id="addon_purchase_price_in_usd_${index}" oninput="inputNumberAbs(this)"
                                    class="widthinput form-control purchase_price_in_USD
                                     @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                     name="supplierAddon[${index}][addon_purchase_price_in_usd]" placeholder="Enter Addons Purchase Price In USD"
                                      value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                      onkeyup="calculateAED(${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                    </div>

                                </div>
                                </div>

                                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                    <a class="btn_round removeButton" id="remove-${index}" data-index="${index}">
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

        function VendorUniqueCheck() {
            var contactNumber =  contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
            var name = $('#supplier').val();
            var supplierType = $('#supplier_type').val();
            var url = '{{ route('vendor.vendorUniqueCheck') }}';
            if(contactNumber.length > 0 && name.length > 0 && supplierType.length > 0) {
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        contact_number: contactNumber,
                        name: name,
                        supplierType:supplierType,
                    },
                    success:function (data) {
                        if(data.error) {
                            removeSupplierError();
                            showContactNumberError(data.error);
                            // formInputError == true;
                            $('#vendor-unique-error').val(1)
                        }else if(data.name_error) {
                            removeContactNumberError();
                            showSupplierError(data.name_error);
                            // formInputError == true;
                            $('#vendor-unique-error').val(1)

                        }
                        else{
                            removeSupplierError();
                            removeContactNumberError();
                            // formInputError = false;
                            $('#vendor-unique-error').val(0)
                        }
                        // console.log(formInputError);
                    }

                });
            }

        }
        // $("form").submit(function(e)
        $('body').on('submit', '#createSupplierForm', function (e)
        {
            sub = '2';
            var inputSupplier = $('#supplier').val();
            var inputSupplierType = $('#supplier_type').val();
            var inputSupplierCatgeory = $('#category').val();
            var inputPhone = $('#phone').val();
            var inputOfficePhone = $('#office_phone').val();

                var inputPaymentMethodsId = $('#is_primary_payment_method').val();
                var inputContactNumber = $('#contact_number').val();
                var inputAlternativeContactNumber = $('#alternative_contact_number').val();
                var inputEmail  = $('#email').val();

                var formInputError = false;

                if(inputPhone != '') {
                    if (inputPhone.length < 5 && inputPhone.length != 0) {

                        $msg = "Minimum 5 digits required";
                        showPhoneError($msg);
                        formInputError = true;
                        e.preventDefault();

                    } else if (inputPhone.length > 15) {

                        $msg = "Maximum 15 digits allowed";
                        showPhoneError($msg);
                        formInputError = true;
                        e.preventDefault();

                    } else {
                        removePhoneError();
                    }
                }
                else
                {
                    removePhoneError();
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
                }
                else{
                    removeOfficePhoneError();
                }
                if(inputAlternativeContactNumber != '') {
                    if(inputAlternativeContactNumber.length > 15) {
                        $msg = "Maximum 15 digits allowed";
                        showAlternativeContactNumberError($msg);
                        formInputError = true;
                        e.preventDefault();
                    }else if(inputAlternativeContactNumber.length < 5 ) {

                        $msg = "Minimum 5 digits required";
                        showAlternativeContactNumberError($msg);
                        formInputError = true;
                        e.preventDefault();
                    }else {
                        removeAlternativeContactNumberError();
                    }
                }
                else{
                    removeAlternativeContactNumberError();
                }
                if(inputSupplier == '')
                {
                    $msg = "Supplier field is required";
                    showSupplierError($msg);

                    formInputError = true;
                    e.preventDefault();
                }
                if(inputEmail == '')
                {
                    $msg = "Email field is required";
                    showEmailError($msg);

                    formInputError = true;
                    e.preventDefault();
                }
                
                if(inputSupplierType == '')
                {
                    $msg = "Supplier type is required";
                    showSupplierTypeError($msg);
                    formInputError = true;
                    e.preventDefault();
                }
                if(inputSupplierCatgeory == '') {
                    $msg = "Supplier Category is required";
                    showSupplierCategoryError($msg);
                    formInputError = true;
                    e.preventDefault();
                }
                if(inputPaymentMethodsId == '')
                {
                    $msg = "Primary Payment method is required";
                    showPaymentMethodsError($msg)
                    formInputError = true;
                    e.preventDefault();
                }
                if(inputContactNumber == '' )
                {
                    $msg ="Contact number is required";
                    showContactNumberError($msg);
                    formInputError = true;
                    e.preventDefault();
                }
                else
                {
                    if(inputContactNumber.length != 0) {
                        if (inputContactNumber.length < 5) {
                            $msg ="Minimum 5 digits required";
                            showContactNumberError($msg);
                            formInputError = true;
                            e.preventDefault();

                    } else if(inputContactNumber.length > 15) {
                        $msg ="Maximum 15 digits allowed";
                        showContactNumberError($msg);
                        formInputError = true;
                        e.preventDefault();
                    }

                }

            }
            var isVendorDuplicate = $('#vendor-unique-error').val();
                console.log(isVendorDuplicate);
            if(formInputError == false && isVendorDuplicate == 0)
            {
                var full_number = contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='contact_number[full]'").val(full_number);
                var full_alternative_contact_number = alternative_contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='alternative_contact_number[full]'").val(full_alternative_contact_number);
                $("input[name='activeTab'").val(activeTab);
                e.preventDefault();
                var actionType = $('#submit').val();
                var formData = new FormData(this);
                console.log(formData);
                var $notifications = $('#notifications')
                $('#submit').html('Sending..');
                $('.overlay').show();
                $.ajax({
                type:'POST',
                url: "{{ route('suppliers.store') }}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (result) =>
                {
                    let dataErrorCard = document.getElementById('dataErrorCard');
                    dataErrorCard.hidden = true
                    console.log(result);
                    console.log(result.data);
                    console.log(result.data.headingError);
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
            }else{

                $('.overlay').hide();
                e.preventDefault();
            }
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
                VendorUniqueCheck();
                var value = clickInput.value;
                if(value == '')
                {
                    if(value.length != 0)
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
            if(clickInput.id == 'category')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    if(value.length != 0)
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
            if(clickInput.id == 'phone') {
                var value = clickInput.value;
                if (value != '') {
                    if (value.length != 0) {
                        if (value.length < 5) {
                            $msg = "Minimum 5 digits required";
                            showPhoneError($msg);

                        } else if (value.length > 15) {
                            $msg = "Maximum 15 digits allowed";
                            showPhoneError($msg);
                        } else {
                            removePhoneError();
                        }
                    }
                }
            }
            if(clickInput.id == 'office_phone') {
                var value = clickInput.value;
                if (value != '') {
                    if (value.length != 0) {
                        if (value.length < 5) {
                            $msg = "Minimum 5 digits required";
                            showOfficePhoneError($msg);

                        } else if (value.length > 15) {
                            $msg = "Maximum 15 digits allowed";
                            showOfficePhoneError($msg);
                        } else {
                            removeOfficePhoneError();
                        }
                    }
                }
            }
            if(clickInput.id == 'supplier')
            {
                VendorUniqueCheck();
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
            if(clickInput.id == 'contact_number')
            {
                VendorUniqueCheck();
                var value = clickInput.value;
                if(value != '')
                {
                    if (value.length != 0) {
                        if (value.length < 5) {
                            $msg = "Minimum 5 digits required";
                            showContactNumberError($msg);

                        } else if (value.length > 15) {
                            $msg = "Maximum 15 digits allowed";
                            showContactNumberError($msg);
                        } else {
                            removeContactNumberError();
                        }
                    }
                }
                else
                {
                    if(sub == '2')
                    {
                        $msg ="Contact number is required";
                        showContactNumberError($msg);
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
                    if (value.length != 0) {
                        if (value.length < 5) {
                            $msg = "Minimum 5 digits required";
                            showAlternativeContactNumberError($msg);

                        } else if (value.length > 15) {
                            $msg = "Maximum 15 digits allowed";
                            showAlternativeContactNumberError($msg);
                        } else {
                            removeAlternativeContactNumberError();
                        }
                    }
                }

            }
             if(clickInput.id == 'email')
            {
                var value = clickInput.value;
                if(value != '')
                {
                    if(value.length != 0)
                    {
                        const validateEmail = (email) => {
                        return email.match(
                            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[(com|net|org|cc|info|name|biz|tv|us|ws|mobi|de|am|fm|me|ca|bz|com.bz|net.bz|es|asia|co|se|xxx|la|buzz)]{2,}))$/
                        );
                        };
                        const validate = () => {
                        const $emailRequired = $('#emailRequired');
                        const email = $('#email').val();
                        $emailRequired.text('');

                        if(validateEmail(email))
                        {
                            removeEmailError();
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

            }
          
        }
        function showContactNumberError($msg)
        {
            document.getElementById("contactRequired").textContent=$msg;
            document.getElementById("contactRequired").classList.add("paragraph-class");
            document.getElementById("contact_number").classList.add("is-invalid");
        }
        function removeContactNumberError()
        {
            document.getElementById("contactRequired").textContent="";
            document.getElementById("contactRequired").classList.remove("paragraph-class");
            document.getElementById("contact_number").classList.remove("is-invalid");
        }
        function showPhoneError($msg)
        {
            document.getElementById("phoneRequired").textContent=$msg;
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
            document.getElementById("officePhoneRequired").classList.add("paragraph-class");
            document.getElementById("office_phone").classList.add("is-invalid");
        }
        function removeOfficePhoneError()
        {
            document.getElementById("officePhoneRequired").textContent="";
            document.getElementById("officePhoneRequired").classList.remove("paragraph-class");
            document.getElementById("office_phone").classList.remove("is-invalid");
        }
        function showAlternativeContactNumberError($msg)
        {
            document.getElementById("alternativeContactRequired").textContent=$msg;
            document.getElementById("alternativeContactRequired").classList.add("paragraph-class");
            document.getElementById("alternative_contact_number").classList.add("is-invalid");
        }
        function removeAlternativeContactNumberError()
        {
            document.getElementById("alternativeContactRequired").textContent="";
            document.getElementById("alternativeContactRequired").classList.remove("paragraph-class");
            document.getElementById("alternative_contact_number").classList.remove("is-invalid");
        }
        function showEmailError($msg)
        {
            document.getElementById("emailRequired").textContent=$msg;
            document.getElementById("email").classList.add("is-invalid");
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
        function showPaymentMethodsError($msg)
        {
            document.getElementById("paymentMethodsError").textContent=$msg;
            document.getElementById("is_primary_payment_method").classList.add("is-invalid");
            document.getElementById("paymentMethodsError").classList.add("paragraph-class");
        }
        function removePaymentMethodsError()
        {
            document.getElementById("paymentMethodsError").textContent="";
            document.getElementById("is_primary_payment_method").classList.remove("is-invalid");
            document.getElementById("paymentMethodsError").classList.remove("paragraph-class");
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
            // alert(val);
            if(val != '')
            {
                dropdownDisable();
            }
            else
            {
                dropdownEnable();
            }
        }
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

