@extends('layouts.main')
<style>
    .error
    {
        color: #FF0000;
    }
    input:focus
    {
        border-color: #495057!important;
    }
    select:focus
    {
        border-color: #495057!important;
    }
    .widthinput
    {
        height:32px!important;
    }
    .paragraph-class
    {
        color: red;
        font-size:11px;
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
</style>
@section('content')
@can('warranty-edit')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-edit']);
@endphp
@if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Edit Warranty</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <form id="createWarrantyForm" name="createWarrantyForm" method="POST" enctype="multipart/form-data" action="{{ route('warranty.update',$premium->id) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>
                <input name="id" value="{{ $premium->id}}" hidden>
                <div class="row">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Policy Name') }}</label>
                        <select name="warranty_policies_id" id="warranty_policies_id" multiple="true" class="form-control widthinput"
                                onchange="validationOnKeyUp(this)"  autofocus>
                            @foreach($policyNames as $policyName)
                                <option value="{{$policyName->id}}" {{$policyName->id == $premium->warranty_policies_id  ? 'selected' : ''}}>
                                    {{$policyName->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 1') }}</label>
                        <select name="vehicle_category1" onchange="validationOnKeyUp(this)"  id="vehicle_category1" multiple="true" class="form-control widthinput" autofocus>
                                <option value="non_electric" {{"non_electric" == $premium->vehicle_category1  ? 'selected' : ''}}>Non Electric</option>
                                <option value="electric" {{"electric" == $premium->vehicle_category1  ? 'selected' : ''}}>Electric</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 2') }}</label>
                        <select name="vehicle_category2" onchange="validationOnKeyUp(this)"  id="vehicle_category2" multiple="true" class="form-control widthinput" autofocus>
                            <option value="normal_and_premium" {{"normal_and_premium" == $premium->vehicle_category2  ? 'selected' : ''}}>Normal And Premium</option>
                            <option value="lux_sport_exotic" {{"lux_sport_exotic" == $premium->vehicle_category2  ? 'selected' : ''}}>Lux/Sport/Exotic</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Criteria') }}</label>
                        <div class="input-group">
                        <select name="eligibility_year" id="eligibility_year" class="form-control widthinput" autofocus>
                                <option value="1" {{"1" == $premium->eligibility_year  ? 'selected' : ''}}>1 Year</option>
                                <option value="2" {{"2" == $premium->eligibility_year  ? 'selected' : ''}}>2 Years</option>
                                <option value="3" {{"3" == $premium->eligibility_year  ? 'selected' : ''}}>3 Years</option>
                                <option value="4" {{"4" == $premium->eligibility_year  ? 'selected' : ''}}>4 Years</option>
                                <option value="5" {{"5" == $premium->eligibility_year  ? 'selected' : ''}}>5 Years</option>
                        </select>
                            <!-- <input name="eligibility_year" id="eligibility_year" onkeyup="validationOnKeyUp(this)" value="{{ $premium->eligibility_year }}" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Years" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">Years</span>
                            </div> -->
                            <span id="EligibilityYearsError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Mileage') }}</label>
                        <div class="input-group">
                            <input name="eligibility_milage" id="eligibility_milage" onkeyup="validationOnKeyUp(this)" value="{{ $premium->eligibility_milage }}" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Mileage" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">KM</span>
                            </div>
                            <span id="EligibilityMileageError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Is Open Mileage') }}</label>
                        <fieldset>
                            <div class="row some-class">
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="is_open_milage" value="yes" id="yes" {{"yes" == $premium->is_open_milage  ? 'checked' : ''}} />
                                    <label for="yes">Yes</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="is_open_milage" value="no" id="no" {{"no" == $premium->is_open_milage  ? 'checked' : ''}} />
                                    <label for="no">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Warranty Period') }}</label>
                        <div class="input-group">
                            <input name="extended_warranty_period" id="extended_warranty_period" onkeyup="validationOnKeyUp(this)" value="{{ $premium->extended_warranty_period }}" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Extended Warranty Period" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">Months</span>
                            </div>
                            <span id="ExtendedWarrantyPeriodError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Claim Limit') }}</label>
                        <div class="input-group">
                            <input name="claim_limit_in_aed" id="claim_limit_in_aed" onkeyup="validationOnKeyUp(this)" value="{{ $premium->claim_limit_in_aed }}"
                            oninput="inputNumberAbs(this)" class="form-control widthinput" placeholder="Enter Claim Limit" aria-label="measurement"
                            aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                            </div>
                            <span id="ClaimLimitError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">Vendor</label>
                        <select name="supplier_id" onchange="validationOnKeyUp(this)"  id="supplier_id" multiple="true" class="form-control widthinput" autofocus >
                            <option></option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}" {{ $supplier->id == $premium->supplier_id ? 'selected' : '' }} >{{$supplier->supplier}}</option>
                            @endforeach
                        </select>
                        <span id="SupplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4" id="ExtendedWarrantyMileageDiv" @if($isOpenMilage === 'yes') hidden @endif>
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Mileage') }}</label>
                        <div class="input-group">
                            <input name="extended_warranty_milage" id="extended_warranty_milage" onkeyup="validationOnKeyUp(this)"
                                   value="{{ $premium->extended_warranty_milage }}" type="number" class="form-control widthinput"
                                   onkeypress="return event.charCode >= 48" min="1" placeholder="Extended Warranty Mileage" aria-label="measurement"
                                   aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">KM</span>
                            </div>
                            <span id="ExtendedWarrantyMilageError" class="invalid-feedback"></span>
                        </div>
                    </div>
                </div>
            </div>
            </br>
            <div class="card"  id="kitSupplier" >
                <div class="card-header">
                    <center>
                        <h4 class="card-title">Warranty Brands</h4>
                    </center>
                </div>
                <div class="card-body">

                    <div class="form_field_outer">
                    <div hidden>{{$i=0;}}</div>
                        @foreach($existingBrands as $existingBrand)
                        <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
                        <div class="row form_field_outer_row" id="row-{{$i}}" >
                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <span class="error">* </span>
                                <label for="country" class="col-form-label text-md-end">{{ __('Country') }}</label>
                                <select name="brandPrice[{{$i}}][country]" id="regions{{$i}}" data-index="{{$i}}" multiple="true" style="width: 100%;"
                                        class="form-control regions" autofocus required>
                                    @foreach($brandRegions as $region)
                                        <option  value="{{$region->id}}" {{ $existingBrand->brand_region_id == $region->id ? 'selected' : '' }}>
                                            {{$region->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <span id="Country1Error" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>
                                <select name="brandPrice[{{$i}}][brands][]" id="brands{{$i}}" data-index="{{$i}}" multiple="true" style="width: 100%;"
                                 class="form-control brands" autofocus required>
                                    @foreach($existingBrand->brand as $brand)
                                    <option id="brand1Option{{$brand->id}}" value="{{$brand->id}}" selected>{{$brand->brand_name}}</option>
                                    @endforeach
                                    @foreach($brands as $brand)
                                        <option id="brand1Option{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                    @endforeach
                                </select>
                                <span id="brandError" class="invalid-feedback"></span>
                            </div>

                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[{{$i}}][purchase_price]" oninput="inputNumberAbs(this)" class="form-control widthinput purchase-price"
                                    id="purchase_price{{$i}}" placeholder="Enter Purchase Price" aria-label="measurement" aria-describedby="basic-addon2"
                                     value="{{$existingBrand->price}}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" >AED</span>
                                    </div>
                                </div>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>

                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Selling Price') }}
                                    @if($existingBrand->is_selling_price_approved == 1) ( Approved )
                                    @elseif($existingBrand->is_selling_price_approved == 0)
                                       ( Approval Awaiting )
                                    @elseif($existingBrand->is_selling_price_approved == 2)
                                        ( Not Added )
                                    @endif
                                </label>
                                <div class="input-group">
                                    <input class="form-control widthinput pending_selling_price"  id="pending_selling_price{{$i}}"
                                           value="@if($existingBrand->is_selling_price_approved != 2) @if($existingBrand->selling_price != NULL){{$existingBrand->selling_price}} AED @endif @endif" readonly>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('New Selling Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[{{$i}}][selling_price]" oninput="inputNumberAbs(this)" class="form-control widthinput selling-price"
                                    id="selling_price{{$i}}" placeholder="Enter Selling Price" value="">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" >AED</span>
                                    </div>
                                </div>
                                <span id="sellingError" class="invalid-feedback"></span>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-6 add_del_btn_outer" style="margin-top:36px" >
                                <button type="button" class="btn btn-danger removeButton" id="remove-{{$i}}" data-index="{{$i}}" >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <a onclick="clickAdd()" style="float: right;" class="btn btn-sm btn-info mt-2">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-sm" id="submit" style="float:right;">Submit</button>
            </div>
        </form>
    </div>
    <input type="hidden" id="indexValue" value="">

    <div class="overlay"></div>
    @endif
    @endcan
<script type="text/javascript">
    var selectedBrands = [];
    var filteredArray = [];
    var save = 1;
    var existingBrands = {!! json_encode($existingBrands) !!};
    var lengthExistingBrand ='';
    $(document).ready(function ()
    {
        lengthExistingBrand = existingBrands.length;

        $('#supplier_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Vendor",
        });
        $('#warranty_policies_id').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Policy",
        });
        $('#vehicle_category1').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Vehicle Category",
        });
        $('#vehicle_category2').select2({
            allowClear: true,
            maximumSelectionLength: 1,
            placeholder:"Choose Vehicle Category",
        });
        $(document.body).on('select2:select', "#warranty_policies_id", function (e) {
            showBrandDiv();
        })
        $(document.body).on('select2:select', "#vehicle_category1", function (e) {
            showBrandDiv();
        })
        $(document.body).on('select2:select', "#vehicle_category2", function (e) {
            showBrandDiv();
        })
        $(document.body).on('select2:select', "#supplier_id", function (e) {
            showBrandDiv();
        })
        for(let i=1; i<=lengthExistingBrand; i++)
        {
            $('#brands'+i).select2({
            allowClear: true,
            minimumResultsForSearch: -1,
            placeholder:"Choose Brands....     Or     Type Here To Search....",
            });
            $('#regions'+i).select2({
                allowClear: true,
                maximumSelectionLength:1,
                placeholder:"Choose Country... Or Search Here...",
            });
        }

        $('.remove-item').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });

        var index = lengthExistingBrand;
        $('#indexValue').val(index);
        ////////////////////////
        $(document.body).on('select2:unselect', "#warranty_policies_id", function (e) {
            e.preventDefault();
            var field = 'Policy';
            var value = e.params.data.id;
            RelatedDataCheck(field,value)
        });
        $(document.body).on('select2:unselect', "#vehicle_category1", function (e) {
            e.preventDefault();
            var field = 'VehicleCategory1';
            var value = e.params.data.id;
            RelatedDataCheck(field,value)
        });
        $(document.body).on('select2:unselect', "#vehicle_category2", function (e) {
            e.preventDefault();
            var field = 'VehicleCategory2';
            var value = e.params.data.id;
            RelatedDataCheck(field,value)

        });
        $(document.body).on('select2:unselect', "#supplier_id", function (e) {
            e.preventDefault();
            var field = 'Vendor';
            var value = e.params.data.id;
            RelatedDataCheck(field,value)
        });
        $(document.body).on('select2:select', ".regions", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            checkUniqueWarranty(index, value);
        });
        $(document.body).on('select2:unselect', ".regions", function (e) {
            var index = $(this).attr('data-index');
            $('#brands'+index).empty();
            $('#purchase_price'+index).val('');
            $('#selling_price'+index).val('');
            $('#pending_selling_price'+index).val('');

        });
        ////////////////////////
        $(document.body).on('select2:unselect', ".brands", function (e) {
            var index = $(this).attr('data-index');
            var data =  $('#regions'+index).val();
            var brand =  e.params.data;
            appendOption(index,data,brand);
        });
        $(document.body).on('select2:select', ".brands", function (e) {
            var index = $(this).attr('data-index');
            var value = $('#regions'+index).val();
            var brand = e.params.data.id;
            hideOption(index,value,brand);
        });
        function hideOption(index,value,brand) {
            var brandTotalIndex = $(".form_field_outer").find(".form_field_outer_row").length;
            for(let i=1; i<=brandTotalIndex; i++)
            {
                var country = $('#regions'+i).val();
                console.log(country[0]);
                console.log(value[0]);
                if(country[0] == value[0]) {

                    if(index != i) {
                        console.log("not clicked item")
                        var currentId = 'brands' + i;
                        $('#' + currentId + ' option[value=' + brand + ']').detach();
                    }
                }
            }
        }
        function appendOption(index,data,brand) {
            // check if this brand country is choosen anywhere in list, if yes find the corresponding brand
            // dropdown id and append data in that dropdown nly
            var brandTotalIndex = $(".form_field_outer").find(".form_field_outer_row").length;
            for(let i=1; i<=brandTotalIndex; i++)
            {
                var country = $('#regions'+i).val();
                console.log(country);
                console.log(data[0]);
                if(country[0] == data[0]) {

                    if(index != i) {
                        $('#brands'+i).append($('<option>', {value: brand.id, text : brand.text}))
                    }
                }
            }
        }
        function addOption(id,text,country) {
            //get the
            var brandTotalIndex = $(".form_field_outer").find(".form_field_outer_row").length;
            for(var i=1;i<=brandTotalIndex;i++) {
                var eachCountry = $('#regions'+i).val();
                if(country[0] == eachCountry[0]) {
                    $('#brands'+i).append($('<option>', {value: id, text :text}))
                }
            }
        }
        function showBrandDiv() {
            var policy = $('#warranty_policies_id').val();
            if(policy != '')
            {
                var vehicleCategory1 = $('#vehicle_category1').val();
                if(vehicleCategory1 != '') {
                    var vehicleCategory2 = $('#vehicle_category2').val();
                    if(vehicleCategory2 != '') {
                        var vendor = $('#supplier_id').val();
                        if(vendor != '') {
                            $('#kitSupplier').attr('hidden', false);
                        }
                    }
                }
            }
        }
        $(document.body).on('click', ".removeButton", function (e) {
            var countRow = 0;
            var countRow = $(".form_field_outer").find(".form_field_outer_row").length;
            if(countRow > 1)
            {
                var indexNumber = $(this).attr('data-index');
                // alert('#row-'+indexNumber);
                $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                    var id = (this.value);
                    var text = (this.text);
                    var country = $('#regions'+indexNumber).val();
                    addOption(id,text,country)
                });
                $(this).closest('#row-'+indexNumber).remove();

                $('.form_field_outer_row').each(function(i){
                    var index = +i + +1;
                    $(this).attr('id','row-'+ index);
                    $(this).find('.brands').attr('data-index', index);
                    $(this).find('.brands').attr('id','brands'+ index);
                    $(this).find('.brands').attr('name','brandPrice['+ index +'][brands][]');
                    $(this).find('.regions').attr('data-index', index);
                    $(this).find('.regions').attr('id','regions'+ index);
                    $(this).find('.regions').attr('name','brandPrice['+ index +'][regions]');
                    $(this).find('.selling-price').attr('name','brandPrice['+ index +'][selling_price]');
                    $(this).find('.selling-price').attr('id','selling_price'+index);
                    $(this).find('.purchase-price').attr('id','purchase_price'+index);
                    $(this).find('.pending_selling_price').attr('id','pending_selling_price'+index);
                    $(this).find('.purchase-price').attr('name','brandPrice['+ index +'][purchase_price]');
                    $(this).find('button').attr('data-index', index);
                    $(this).find('button').attr('id','remove-'+ index);
                    $('#brands'+index).select2
                    ({
                        placeholder:"Choose Brands....     Or     Type Here To Search....",
                        allowClear: true,
                        minimumResultsForSearch: -1,
                    });
                    $('#regions'+index).select2
                    ({
                        placeholder:"Choose Country.. Or Search Here....",
                        allowClear: true,
                        minimumResultsForSearch: -1,
                    });
                });
            }
            else
            {
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one Brand and Price Required',function (e) {
                }).set({title:"Can't Remove Brands And Prices"})
            }

        })

        function  RelatedDataCheck(field,value) {

            var brandTotalIndex = $(".form_field_outer").find(".form_field_outer_row").length;

            var brands = [];
            var countries = [];
            var purcahsePrice = [];
            var sellingPrice = [];
            if (brandTotalIndex > 0) {
                for(let i=1; i<=brandTotalIndex; i++)
                {
                    var eachBrand = $('#brands'+i).val();
                    var eachRegion = $('#regions'+i).val();
                    var eachSellingPrice = $('#selling_price'+i).val();
                    var eachPurchasePrice = $('#purchase_price'+i).val();

                    if(eachBrand != '') {
                        brands.push(eachBrand);
                    }
                    if(eachRegion != '') {
                        countries.push(eachRegion);
                    }
                    if(eachSellingPrice != '') {
                        sellingPrice.push(eachSellingPrice);
                    }
                    if(eachPurchasePrice != '') {
                        purcahsePrice.push(eachPurchasePrice);
                    }
                }
                var brandCount = brands.length;
                var regionCount = countries.length;
                var sellingPriceCount = sellingPrice.length;
                var purcahsePriceCount = purcahsePrice.length;

                if(brandCount > 0 || regionCount > 0 || sellingPriceCount > 0 || purcahsePriceCount > 0) {
                    if(field == 'Vendor' ) {
                        $("#supplier_id").val(value).trigger('change');
                    }else if(field == 'Policy') {
                        $("#warranty_policies_id").val(value).trigger('change');
                    }else if(field == 'VehicleCategory1') {
                        $("#vehicle_category1").val(value).trigger('change');

                    }else if(field == 'VehicleCategory2') {
                        $("#vehicle_category2").val(value).trigger('change');
                    }
                    var confirm = alertify.confirm('You are not able to edit this field while any Items in Brand and Country.' +
                        'Please remove those items to edit this field.', function (e) {
                    }).set({title: "Remove Brands and Countries"})
                }else{
                    $('#kitSupplier').attr('hidden', true);
                }
            }
        }

        function checkUniqueWarranty(index, value) {
            var brandTotalIndex = $(".form_field_outer").find(".form_field_outer_row").length;
            var selectedBrands = [];
            for(let i=1; i<brandTotalIndex; i++)
            {
                var eachSelectedCountry = $("#regions"+i).val();
                if(eachSelectedCountry == value) {
                    var eachSelectedBrand = $("#brands"+i).val();
                    $.each(eachSelectedBrand, function( ind, value )
                    {
                        selectedBrands.push(value);
                    });
                }
            }

            var policy = $('#warranty_policies_id').val();
            var vehicle_category1 = $('#vehicle_category1').val();
            var vehicle_category2 = $('#vehicle_category2').val();
            var supplier_id = $("#supplier_id").val();

            $.ajax({
                url: "{{url('getBrandForWarranty')}}",
                type: "GET",
                data:
                    {
                        brand_region_id: value,
                        warranty_policies_id:policy,
                        vehicle_category1:vehicle_category1,
                        vehicle_category2:vehicle_category2,
                        supplier_id:supplier_id,
                        selectedBrands:selectedBrands
                    },
                dataType : 'json',
                success: function (data) {
                    $('#brands'+index).empty();
                    $('#brands'+index).html('<option value=""> Select Brand</option>');
                    // check if any option chooses current region if choosen then remove that brand in this array

                    jQuery.each(data, function(key,value){
                        $('#brands'+index).append('<option value="'+ value.id +'">'+ value.brand_name +'</option>');
                    });
                }
            });
        }
    });

    $('body').on('submit', '#createWarrantyForm', function (e)
    {
        save = 2;
        var inputEligibilityYears = $('#eligibility_year').val();
        var inputEligibilityMileage = $('#eligibility_milage').val();
        var inputExtendedWarrantyPeriod = $('#extended_warranty_period').val();
        var inputClaimLimit = $('#claim_limit_in_aed').val();
        var inputSupplierId = $('#supplier_id').val();
        var inputBrands1 = $('#brands1').val();
        var inputPurchasePrice1 = $('#purchase_price1').val();
        var inputCountry1 = $('#regions1').val();
        var inputPolicy = $('#warranty_policies_id').val();
        var inputVehicleCategory1 = $('#vehicle_category1').val();
        var inputVehicleCategory2 = $('#vehicle_category2').val();


        if(inputCountry1 == '')
        {
            $msg = "Country is required";
            showCountry1Error($msg);
            formInputError = true;
            e.preventDefault();
        }
        if(inputEligibilityYears == '')
        {
            $msg = "Eligibility Years is required";
            showEligibilityYearsError($msg);
            formInputError = true;
            e.preventDefault();
        }
        if(inputEligibilityMileage == '')
        {
            $msg = "Eligibility Mileage is required";
            showEligibilityMileageError($msg);
            formInputError = true;
            e.preventDefault();
        }
        if(inputExtendedWarrantyPeriod == '')
        {
            $msg = "Extended Warranty Period is required";
            showExtendedWarrantyPeriodError($msg);
            formInputError = true;
            e.preventDefault();
        }
        if(inputClaimLimit == '')
        {
            $msg = "Claim Limit is required";
            showClaimLimitError($msg);
            formInputError = true;
            e.preventDefault();
        }
        if(inputSupplierId == '')
        {
            $msg = "Vendor is required";
            showSupplierError($msg);
            formInputError = true;
            e.preventDefault();
        }
        if(IsOpenMileage == 'no')
        {
            var inputExtendedWarrantyMilage = $('#extended_warranty_milage').val();
            if(inputExtendedWarrantyMilage == '')
            {
                $msg = "Extended Warranty Milage is required";
                showExtendedWarrantyMilageError($msg);
                formInputError = true;
                e.preventDefault();
            }
        }
        if(save = '2')
        {
            if(inputBrands1 == '')
            {
                $msg = "Brand is required";
                showBrand1Error($msg);
                formInputError = true;
                e.preventDefault();
            }
        }
        if(inputPurchasePrice1 == '')
        {
            $msg = "Price is required";
            showPrice1Error($msg);
            formInputError = true;
            e.preventDefault();
        }

    });
    function clickAdd()
    {
        var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
        $('#indexValue').val(index);
        {{--var selectedBrands = [];--}}
        {{--for(let i=1; i<index; i++)--}}
        {{--{--}}
        {{--    var eachSelectedBrand = $("#brands"+i).val();--}}
        {{--    $.each(eachSelectedBrand, function( ind, value )--}}
        {{--    {--}}
        {{--        selectedBrands.push(value);--}}
        {{--    });--}}
        {{--}--}}
        {{--$.ajax--}}
        {{--({--}}
        {{--    url:"{{url('getBranchForWarranty')}}",--}}
        {{--    type: "POST",--}}
        {{--    data:--}}
        {{--        {--}}
        {{--            filteredArray: selectedBrands,--}}
        {{--            id: '{{ $premium->id }}',--}}
        {{--            _token: '{{csrf_token()}}'--}}
        {{--        },--}}
        {{--    dataType : 'json',--}}
        {{--    success: function(data)--}}
        {{--    {console.log(data);--}}
        {{--        myarray = data;--}}
        {{--        var size= myarray.length;--}}
        {{--        if(size >= 1)--}}
        {{--        {--}}
                    $(".form_field_outer").append(`
                        <div class="row form_field_outer_row" id="row-${index}" >
                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Country') }}</label>
                                <select name="brandPrice[${index}][country]" id="regions${index}" data-index="${index}" required multiple="true" style="width: 100%;"
                                    class="form-control widthinput regions" autofocus onchange="validationOnKeyUp(this)">
                                        @foreach($brandRegions as $region)
                                            <option value="{{$region->id}}">{{$region->name}}</option>
                                        @endforeach
                                </select>
                                    <span id="Country1Error" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-45 col-lg-5 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>
                                <select name="brandPrice[${index}][brands][]" id="brands${index}" data-index="${index}" multiple="true" style="width: 100%;"
                                class="form-control brands" autofocus required>

                                </select>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[${index}][purchase_price]" oninput="inputNumberAbs(this)" class="form-control widthinput purchase-price"
                                    id="purchase_price${index}" placeholder="Enter Purchase Price" aria-label="measurement" aria-describedby="basic-addon2" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" >AED</span>
                                    </div>
                                </div>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                             <div class="col-xxl-2 col-lg-2 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Selling Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[${index}][selling_price]" oninput="inputNumberAbs(this)" class="form-control widthinput selling-price"
                                    id="selling_price${index}" placeholder="Enter Selling Price" >
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" >AED</span>
                                    </div>
                                </div>
                                <span id="sellingError" class="invalid-feedback"></span>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-6 add_del_btn_outer" style="margin-top:36px" >
                                <button type="button" class="btn btn-danger removeButton" id="remove-${index}" data-index="${index}" >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                    // let brandDropdownData   = [];
                    //
                    // $.each(data,function(key,value)
                    // {
                    //     brandDropdownData.push
                    //     ({
                    //         id: value.id,
                    //         text: value.brand_name
                    //     });
                    // });
                    //
                    // $('#brands'+index).html("");
                    $('#brands'+index).select2
                    ({
                        placeholder:"Choose Brands....     Or     Type Here To Search....",
                        allowClear: true,
                        // data: brandDropdownData,
                        minimumResultsForSearch: -1,
                        // templateResult: hideSelected,
                    });
                    $('#regions'+index).select2
                    ({
                        placeholder:"Choose Country... Or Search Here...",
                        allowClear: true,
                        maximumSelectionLength:1,
                        // data: brandDropdownData,
                        minimumResultsForSearch: -1,
                    });
        //         }
        //     }
        // });
    }

    function validationOnKeyUp(clickInput)
    {
        if(save == 2)
        {
            if(clickInput.id == 'regions1')
            {
                value = $('#regions1').val();
                if(value == '')
                {
                    $msg = "Country is required";
                    showCountry1Error($msg);
                }
                else
                {
                    removeCountry1Error();
                }
            }
            if(clickInput.id == 'warranty_policies_id')
            {
                value = $('#warranty_policies_id').val();
                if(value == '')
                {
                    $msg = "Policy is required";
                    showPolicyError($msg);
                }
                else
                {
                    removePolicyError();
                }
            }
            if(clickInput.id == 'vehicle_category1')
            {
                value = $('#vehicle_category1').val();
                if(value == '')
                {
                    $msg = "Category is required";
                    showVehicleCategory1Error($msg);
                }
                else
                {
                    removeVehicleCategory1Error();
                }
            }
            if(clickInput.id == 'vehicle_category2')
            {
                value = $('#vehicle_category2').val();
                if(value == '')
                {
                    $msg = "Category is required";
                    showVehicleCategory2Error($msg);
                }
                else
                {
                    removeVehicleCategory2Error();
                }
            }
            if(clickInput.id == 'eligibility_year')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Eligibility Years is required";
                    showEligibilityYearsError($msg);
                }
                else
                {
                    removeEligibilityYearsError();
                }
            }
            if(clickInput.id == 'eligibility_milage')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Eligibility Mileage is required";
                    showEligibilityMileageError($msg);
                }
                else
                {
                    removeEligibilityMileageError();
                }
            }
            if(clickInput.id == 'extended_warranty_period')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Extended Warranty Period is required";
                    showExtendedWarrantyPeriodError($msg);
                }
                else
                {
                    removeExtendedWarrantyPeriodError();
                }
            }
            if(clickInput.id == 'claim_limit_in_aed')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Claim Limit is required";
                    showClaimLimitError($msg);
                }
                else
                {
                    removeClaimLimitError();
                }
            }
            if(clickInput.id == 'supplier_id')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Supplier is required";
                    showSupplierError($msg);
                }
                else
                {
                    removeSupplierError();
                }
            }
            if(clickInput.id == 'extended_warranty_milage')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Extended Warranty Milage is required";
                    showExtendedWarrantyMilageError($msg);
                }
                else
                {
                    removeExtendedWarrantyMilageError();
                }
            }

        }
    }

    $('.radioFixingCharge').click(function()
    {
        IsOpenMileage = $(this).val();
        if($(this).val() == 'yes')
        {
            hideExtendedWarrantyMileage();
        }
        else
        {
            showExtendedWarrantyMileage();
        }
    });
    function showExtendedWarrantyMileage()
    {
        let showExtendedWarrantyMilage = document.getElementById('ExtendedWarrantyMileageDiv');
        showExtendedWarrantyMilage.hidden = false
    }
    function hideExtendedWarrantyMileage()
    {
        let showExtendedWarrantyMilage = document.getElementById('ExtendedWarrantyMileageDiv');
        // $('#extended_warranty_milage').val(0);
        showExtendedWarrantyMilage.hidden = true
    }

    function showVehicleCategory1Error($msg)
    {
        document.getElementById("VehicleCategory1Error").textContent=$msg;
        document.getElementById("vehicle_category1").classList.add("is-invalid");
        document.getElementById("VehicleCategory1Error").classList.add("paragraph-class");
    }
    function showVehicleCategory2Error($msg)
    {
        document.getElementById("VehicleCategory2Error").textContent=$msg;
        document.getElementById("vehicle_category2").classList.add("is-invalid");
        document.getElementById("VehicleCategory2Error").classList.add("paragraph-class");
    }
    function showPolicyError($msg)
    {
        document.getElementById("PolicyError").textContent=$msg;
        document.getElementById("warranty_policies_id").classList.add("is-invalid");
        document.getElementById("PolicyError").classList.add("paragraph-class");
    }
    function removeVehicleCategory1Error()
    {
        document.getElementById("VehicleCategory1Error").textContent="";
        document.getElementById("vehicle_category1").classList.remove("is-invalid");
        document.getElementById("VehicleCategory1Error").classList.remove("paragraph-class");
    }
    function removeVehicleCategory2Error()
    {
        document.getElementById("VehicleCategory2Error").textContent="";
        document.getElementById("vehicle_category2").classList.remove("is-invalid");
        document.getElementById("VehicleCategory2Error").classList.remove("paragraph-class");
    }
    function removePolicyError()
    {
        document.getElementById("PolicyError").textContent="";
        document.getElementById("warranty_policies_id").classList.remove("is-invalid");
        document.getElementById("PolicyError").classList.remove("paragraph-class");
    }

    function showEligibilityYearsError($msg)
    {
        document.getElementById("EligibilityYearsError").textContent=$msg;
        document.getElementById("eligibility_year").classList.add("is-invalid");
        document.getElementById("EligibilityYearsError").classList.add("paragraph-class");
    }
    function removeEligibilityYearsError()
    {
        document.getElementById("EligibilityYearsError").textContent="";
        document.getElementById("eligibility_year").classList.remove("is-invalid");
        document.getElementById("EligibilityYearsError").classList.remove("paragraph-class");
    }
    function showEligibilityMileageError($msg)
    {
        document.getElementById("EligibilityMileageError").textContent=$msg;
        document.getElementById("eligibility_milage").classList.add("is-invalid");
        document.getElementById("EligibilityMileageError").classList.add("paragraph-class");
    }
    function removeEligibilityMileageError()
    {
        document.getElementById("EligibilityMileageError").textContent="";
        document.getElementById("eligibility_milage").classList.remove("is-invalid");
        document.getElementById("EligibilityMileageError").classList.remove("paragraph-class");
    }
    function showExtendedWarrantyPeriodError($msg)
    {
        document.getElementById("ExtendedWarrantyPeriodError").textContent=$msg;
        document.getElementById("extended_warranty_period").classList.add("is-invalid");
        document.getElementById("ExtendedWarrantyPeriodError").classList.add("paragraph-class");
    }
    function removeExtendedWarrantyPeriodError()
    {
        document.getElementById("ExtendedWarrantyPeriodError").textContent="";
        document.getElementById("extended_warranty_period").classList.remove("is-invalid");
        document.getElementById("ExtendedWarrantyPeriodError").classList.remove("paragraph-class");
    }
    function showClaimLimitError($msg)
    {
        document.getElementById("ClaimLimitError").textContent=$msg;
        document.getElementById("claim_limit_in_aed").classList.add("is-invalid");
        document.getElementById("ClaimLimitError").classList.add("paragraph-class");
    }
    function removeClaimLimitError()
    {
        document.getElementById("ClaimLimitError").textContent="";
        document.getElementById("claim_limit_in_aed").classList.remove("is-invalid");
        document.getElementById("ClaimLimitError").classList.remove("paragraph-class");
    }
    function showSupplierError($msg)
    {
        document.getElementById("SupplierError").textContent=$msg;
        document.getElementById("supplier_id").classList.add("is-invalid");
        document.getElementById("SupplierError").classList.add("paragraph-class");
    }
    function removeSupplierError()
    {
        document.getElementById("SupplierError").textContent="";
        document.getElementById("supplier_id").classList.remove("is-invalid");
        document.getElementById("SupplierError").classList.remove("paragraph-class");
    }
    function showExtendedWarrantyMilageError($msg)
    {
        document.getElementById("ExtendedWarrantyMilageError").textContent=$msg;
        document.getElementById("extended_warranty_milage").classList.add("is-invalid");
        document.getElementById("ExtendedWarrantyMilageError").classList.add("paragraph-class");
    }
    function removeExtendedWarrantyMilageError()
    {
        document.getElementById("ExtendedWarrantyMilageError").textContent="";
        document.getElementById("extended_warranty_milage").classList.remove("is-invalid");
        document.getElementById("ExtendedWarrantyMilageError").classList.remove("paragraph-class");
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
    }

</script>
@endsection
