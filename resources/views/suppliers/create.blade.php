@extends('layouts.main')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<style>
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
        width: 35px;
        height: 35px;
        display: inline-block;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        margin-left: 10px;
        border: 1px solid #ccc;
        cursor: pointer;
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
        color: red;
        font-size:11px;
    }
    .required-class
    {
        font-size:11px;
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create Suppliers</h4>
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
        <form id="createSupplierForm" action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data"> 
        <!-- action="{{ route('suppliers.store') }}" -->
        <!-- method="POST" enctype="multipart/form-data" -->
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Supplier : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Supplier" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus required>
                            <!-- @error('supplier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->
                            <span id="supplierError" class="required-class"></span>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <!-- <span class="error">* </span> -->
                            <label for="contact_person" class="col-form-label text-md-end">{{ __('Contact Person : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="contact_person" type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" placeholder="Enter Contact Person" value="{{ old('contact_person') }}"  autocomplete="contact_person" autofocus>
                            @error('contact_person')
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
                            <label for="contact_number" class="col-form-label text-md-end">{{ __('Contact Number : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="contact_number" type="tel" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number[main]" placeholder="Enter Contact Number" value="{{ old('contact_number') }}"  autocomplete="contact_number" autofocus>
                            <!-- @error('contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->
                            <span id="contactRequired" class="email-phone required-class">One among contact number or alternative contact number or email is required</span>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="alternative_contact_number" type="text" class="form-control @error('alternative_contact_number') is-invalid @enderror" name="alternative_contact_number[main]" placeholder="Enter Alternative Contact Number" value="{{ old('alternative_contact_number') }}" autocomplete="alternative_contact_number" autofocus>
                            <!-- @error('alternative_contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->
                            <span id="alternativeContactRequired" class="email-phone required-class">One among contact number or alternative contact number or email is required</span>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <!-- <span class="error">* </span> -->
                            <label for="email" class="col-form-label text-md-end">{{ __('Email : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email" value="{{ old('email') }}"  autocomplete="email" autofocus>
                            <!-- @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->
                            <span id="emailRequired" class="email-phone required-class">One among contact number or alternative contact number or email is required</span>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <!-- <span class="error">* </span> -->
                            <label for="person_contact_by" class="col-form-label text-md-end">{{ __('Person Contact By : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="person_contact_by" type="text" class="form-control @error('person_contact_by') is-invalid @enderror" name="person_contact_by" placeholder="Enter Person Contact By" value="{{ old('person_contact_by') }}"  autocomplete="person_contact_by" autofocus>
                            @error('person_contact_by')
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
                            <label for="supplier_type" class="col-form-label text-md-end">{{ __('Supplier Type : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <select name="supplier_type" id="supplier_type" class="form-control" required>
                            <option value="">Choose Supplier Type</option>
                            <option value="accessories">Accessories</option>      
                            <option value="freelancer">Freelancer</option>
                            <option value="garage">Garage</option>
                            <option value="spare_parts">Spare Parts</option>
                        </select>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="is_primary_payment_method" class="col-form-label text-md-end">{{ __('Primary Payment Method : ') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <select id="is_primary_payment_method" name="is_primary_payment_method" class="form-control" onchange="secondaryPaymentMethods()" required>
                                <option value="">Choose Payment Method</option>
                                @foreach($paymentMethods as $paymentMethod)
                                <option value="{{$paymentMethod->id}}">{{$paymentMethod->payment_methods}}</option>
                                @endforeach
                            </select>
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div id="secondaryPayments" class="col-xxl-6 col-lg-6 col-md-12" hidden >
                    <div class="row">
                        <div class="col-xxl-3 col-lg-2 col-md-4">
                            <!-- <span class="error">* </span> -->
                            <label for="payment_methods_id" class="col-form-label text-md-end">{{ __('Secondary Payment Methods : ') }}</label>
                        </div>
                        @foreach($paymentMethods as $paymentMethod)
                            <div class="col-xxl-3 col-lg-3 col-md-6" id="{{$paymentMethod->id}}">
                                <input id="payment_methods_id" name="payment_methods_id[]" class="form-check-input" type="checkbox" value="{{ $paymentMethod->id }}">                              
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    {{ $paymentMethod->payment_methods }}
                                </label>
                                @error('payment_methods_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                    </br>
                </div> 
            </div>
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
                                        <div class="row form_field_outer_row">
                                            <div class="col-xxl-6 col-lg-6 col-md-12">
                                                <label for="choices-single-default" class="form-label font-size-13">Choose Addons</label>
                                                <select class="addonClass" id="adoon_1" name="supplierAddon[1][addon_id][]" multiple="true" style="width: 100%;" onchange="changeAddon(1)">
                                                    @foreach($addons as $addon)
                                                        <option id="addon_1_{{$addon->id}}" value="{{$addon->id}}">{{$addon->addon_code}} - ( {{ $addon->AddonName->name }} )</option>
                                                    @endforeach
                                                </select>
                                                @error('is_primary_payment_method')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-xxl-1 col-lg-1 col-md-1">
                                                <label for="choices-single-default" class="form-label font-size-13">Currency</label>
                                                <select name="supplierAddon[1][currency]" id="currency_1" class="form-control" onchange="changeCurrency(1)">
                                                    <option value="AED">AED</option>      
                                                    <option value="USD">USD</option>
                                                </select>
                                            </div>
                                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" hidden>
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                <input id="" type="text" class="form-control @error('addon_purchase_price_in_usd') is-invalid @enderror" name="supplierAddon[1][addon_purchase_price_in_usd]" placeholder="Enter Addons Purchase Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                            </div>
                                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" hidden>
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                <input id="" type="text" class="form-control @error('addon_purchase_price') is-invalid @enderror" name="supplierAddon[1][addon_purchase_price]" placeholder="1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus readonly>
                                            </div>
                                            <div class="col-xxl-4 col-lg-6 col-md-6" id="div_price_in_aedOne_1">
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                <input id="" type="text" class="form-control @error('addon_purchase_price') is-invalid @enderror" name="supplierAddon[1][addon_purchase_price]" placeholder="Enter Addons Purchase Price in AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus>
                                            </div>
                                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                <!-- <button class="btn_round add_node_btn_frm_field" title="Copy or clone this row">
                                                    <i class="fas fa-copy"></i>
                                                </button> -->
                                                <button class="btn_round remove_node_btn_frm_field" disabled>
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn"><i class="fa fa-plus" aria-hidden="true"></i> Add</a> 
                                </div>
                            </div>
                        </div>
                        </br>
                    </div>
                </div>  
            </div>
            <input id="activeTab" name="activeTab" hidden>
            <div id="uploadExcel" class="tabcontent">
                <center>
                    <!-- <input style="width:50%;" id="image" type="file" class="form-control" name="image" autocomplete="image" /> -->
                    <input type="file" name="file" placeholder="Choose file" style="width:50%;" id="image" class="form-control">
                </center>
            </div>
            </br>
            <div class="col-xxl-12 col-lg-12 col-md-12">
                <button style="float:right;" type="submit" class="btn btn-sm btn-success" id="submit">Submit</button>
            </div>
        </form> 
    </div>
    <script type="text/javascript">
        var activeTab = '';
        var PreviousHidden = '';
        // var selectedBrands = [];
        var selectedBrands = new Array();
        // globalThis.selectedBrands .push(brandId);
      
        $(document).ready(function ()
        {
            $("#adoon_1").attr("data-placeholder","Choose Addon Code....     Or     Type Here To Search....");
            $("#adoon_1").select2();
//             $("#adoon_1").select2().on('change', function() {
//     $('#value').select2({data:data[$(this).val()]});
// }).trigger('change');
            // $(document.body).on("change","#adoon_1",function()
            // {
            //     // globalThis.selectedBrands .push(this.value);
            //     alert(this.value);
            //     // alert(selectedBrands);
            // });
//             $('#adoon_1').on("select2:select", function (e) {
//                 var eachSelected = [];
//                 var eachSelected = $('#adoon_1').select2().val();
//                 globalThis.selectedBrands[1] = [];
//                 $.each(eachSelected, function( i, value ) {
//                     globalThis.selectedBrands[1] .push(value); 
//                     alert(selectedBrands);
//             //     // 
//                 // alert( index + ": " + value );
// //                 $("#adoon_1").find(':selected').attr('disabled','disabled');
// // $("#adoon_1").trigger('change');
// // $("#adoon_2").find(':selected').attr('disabled','disabled');
// // $("#adoon_2").trigger('change');
//                 });
// });

            // document.getElementById('adoon_1').addEventListener('change', function() {
            //     console.log('You selected: ', this.value);
            // });
            ///======Clone method
            $("body").on("click", ".add_node_btn_frm_field", function (e) 
            {
                var index = $(e.target).closest(".form_field_outer").find(".form_field_outer_row").length + 1;
                var cloned_el = $(e.target).closest(".form_field_outer_row").clone(true);
                $(e.target).closest(".form_field_outer").last().append(cloned_el).find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);
                $(e.target).closest(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
                //change id
                $(e.target)
                .closest(".form_field_outer")
                .find(".form_field_outer_row")
                .last()
                .find("input[type='text']")
                .attr("id", "mobileb_no_" + index);
                $(e.target)
                .closest(".form_field_outer")
                .find(".form_field_outer_row")
                .last()
                .find("select")
                .attr("id", "no_type_" + index);
                //count++;
               
            });
            $("body").on("click",".add_new_frm_field_btn", function ()
            { 
                var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; $(".form_field_outer").append(`
                    <div class="row form_field_outer_row">
                        <div class="col-xxl-6 col-lg-6 col-md-12">
                            <label for="choices-single-default" class="form-label font-size-13">Choose Addons</label>
                            <select class="addonClass"  id="adoon_${index}" name="supplierAddon[${index}][addon_id][]" multiple="true" style="width: 100%;" onchange="changeAddon(${index})">
                                @foreach($addons as $addon)
                                    <option id="addon_${index}_{{$addon->id}}" value="{{$addon->id}}">{{$addon->addon_code}} - ( {{ $addon->AddonName->name }} )</option>
                                @endforeach
                            </select>
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            <label for="choices-single-default" class="form-label font-size-13">Currency</label>
                            <select name="supplierAddon[${index}][currency]" id="currency_${index}" class="form-control" onchange="changeCurrency(${index})">
                                <option value="AED">AED</option>      
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_${index}" hidden>
                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                            <input id="addon_purchase_price_in_usd_${index}" type="text" class="form-control @error('addon_purchase_price_in_usd') is-invalid @enderror" name="supplierAddon[${index}][addon_purchase_price_in_usd]" placeholder="Enter Addons Purchase Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(${index})">
                        </div>
                        <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_${index}" hidden>
                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                            <input id="addon_purchase_price_${index}" type="text" class="form-control @error('addon_purchase_price') is-invalid @enderror" name="supplierAddon[${index}][addon_purchase_price]" placeholder="1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus readonly>
                        </div>
                        <div class="col-xxl-4 col-lg-6 col-md-6" id="div_price_in_aedOne_${index}">
                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                            <input id="addon_purchase_price_${index}" type="text" class="form-control @error('addon_purchase_price') is-invalid @enderror" name="supplierAddon[${index}][addon_purchase_price]" placeholder="Enter Addons Purchase Price in AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus>
                        </div>
                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                            <button class="btn_round remove_node_btn_frm_field" disabled>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
            `); 
            $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false); $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true); 
            $("#adoon_"+index).attr("data-placeholder","Choose Addon Code....     Or     Type Here To Search....");
            $("#adoon_"+index).select2();
            // $("#adoon_"+index).on("select2:select", function (e) {
                
            //     alert($("#adoon_"+index).select2().val());
            // });
           
//             $("#adoon_"+index).select2().on('change', function() {
//     $('#value').select2({data:data[$(this).val()]});
// }).trigger('change');
            });   
          
        });  
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
        $("form").submit(function(e) 
        {
                        //      adoon_1 currency_1 addon_purchase_price_in_usd_1 addon_purchase_price_1
                    // var input = $('#supplier').val();
            // var input = $('#supplier').val();
            // var input = $('#supplier').val();
            // var input = $('#supplier').val();
            // var input = $('#supplier').val();
            // var input = $('#supplier').val();
            // var inputSupplier = $('#supplier').val();
            // var inputContactPerson = $('#contact_person').val();
            // var inputPersonContactBy = $('#person_contact_by').val();
            // var inputSupplierType = $('#supplier_type').val();
            // var inputIsPrimaryPaymentMethod = $('#is_primary_payment_method').val();
            // var inputPaymentMethodsId = $('#payment_methods_id').val();

            var inputContactNumber = $('#contact_number').val();
            var inputAlternativeContactNumber = $('#alternative_contact_number').val();
            var inputEmail  = $('#email').val();
            // if(inputSupplier == '')
            // {
            //     document.getElementById("supplierError").classList.add("paragraph-class"); 
            //     document.getElementById("supplierError").textContent="S";
            // }
            if(inputContactNumber == '' && inputAlternativeContactNumber == '' && inputEmail == '')
            {
                document.getElementById("contactRequired").classList.add("paragraph-class");
                document.getElementById("emailRequired").classList.add("paragraph-class");
                document.getElementById("alternativeContactRequired").classList.add("paragraph-class");
                e.preventDefault();
            }
            else
            {
                var full_number = contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='contact_number[full]'").val(full_number);
                var full_alternative_contact_number = alternative_contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
                $("input[name='alternative_contact_number[full]'").val(full_alternative_contact_number);
                $("input[name='activeTab'").val(activeTab);
                // activeTab
                // var f = document.getElementById("createSupplierForm");
                // f.setAttribute('method',"post");  
                // f.setAttribute('enctype',"multipart/form-data"); 
                // f.setAttribute('action',"{{ route('suppliers.store') }}");
            }
        });
        function secondaryPaymentMethods()
        {
            var e = document.getElementById("is_primary_payment_method");
            var value = e.value;
            if(PreviousHidden != '')
            {
                let addonTable = document.getElementById(PreviousHidden);
                addonTable.hidden = false
            }
            let addonTable = document.getElementById('secondaryPayments');
            addonTable.hidden = false
            let primaryPaymentMethod = document.getElementById(value);
            primaryPaymentMethod.hidden = true
            PreviousHidden = value;
        }
        function changeAddon(i)
        {
            var eachSelected = [];

                var eachSelected = $('#adoon_'+i).select2().val();
                // globalThis.selectedBrands[i] = [];
                $.each(eachSelected, function( ind, value ) {
                    // globalThis.selectedBrands[i] .push(value); 
                    globalThis.selectedBrands .push(value);
            //     // 
                // alert( index + ": " + value );
//                 $("#adoon_1").find(':selected').attr('disabled','disabled');
// $("#adoon_1").trigger('change');
// $("#adoon_2").find(':selected').attr('disabled','disabled');
// $("#adoon_2").trigger('change');
                });
        }
        function changeCurrency(i)
        {
            alert('hlo');
            var e = document.getElementById("currency_"+i);
            var value = e.value;
            if(value == 'USD')
            {
                let chooseCurrency = document.getElementById('div_price_in_aedOne_'+i);
                chooseCurrency.hidden = true  
                let currencyUSD = document.getElementById('div_price_in_usd_'+i);
                currencyUSD.hidden = false  
                let currencyAED = document.getElementById('div_price_in_aed_'+i);
                currencyAED.hidden = false  
            }
            else
            {
                let chooseCurrency = document.getElementById('div_price_in_aedOne_'+i);
                chooseCurrency.hidden = false  
                let currencyUSD = document.getElementById('div_price_in_usd_'+i);
                currencyUSD.hidden = true  
                let currencyAED = document.getElementById('div_price_in_aed_'+i);
                currencyAED.hidden = true 
            }
        }
        function calculateAED(i)
        {
            alert('lololo');
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
        //===== delete the form fieed row
        $("body").on("click", ".remove_node_btn_frm_field", function () 
        {
            $(this).closest(".form_field_outer_row").remove();
        });
    </script>
@endsection
