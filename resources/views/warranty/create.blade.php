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
    @can('warranty-create')
    <div class="card-header">
        <h4 class="card-title">Create Warranty</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <form id="createWarrantyForm" name="createWarrantyForm" method="POST" enctype="multipart/form-data" action="{{ route('warranty.store') }}">
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>
                <div class="row">
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Policy Name') }}</label>
                        <select name="warranty_policies_id" id="warranty_policies_id" class="form-control widthinput" autofocus>
                            @foreach($policyNames as $policyName)
                                <option value="{{$policyName->id}}">{{$policyName->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 1') }}</label>
                        <select name="vehicle_category1" id="vehicle_category1" class="form-control widthinput" autofocus>
                                <option value="non_electric">Non Electric</option>
                                <option value="electric">Electric</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 2') }}</label>
                        <select name="vehicle_category2" id="vehicle_category2" class="form-control widthinput" autofocus>
                            <option value="normal_and_premium">Normal And Premium</option>
                            <option value="lux_sport_exotic">Lux/Sport/Exotic</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Years') }}</label>
                        <div class="input-group">
                            <input name="eligibility_year" id="eligibility_year" onkeyup="validationOnKeyUp(this)" type="number" step="any" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Years" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">Years</span>
                            </div>
                            <span id="EligibilityYearsError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Mileage') }}</label>
                        <div class="input-group">
                            <input name="eligibility_milage" id="eligibility_milage" onkeyup="validationOnKeyUp(this)" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Mileage" aria-label="measurement" aria-describedby="basic-addon2">
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
                                    <input type="radio" class="radioFixingCharge" name="is_open_milage" value="yes" id="yes" checked />
                                    <label for="yes">Yes</label>
                                </div>
                                <div class="col-xxl-6 col-lg-6 col-md-6">
                                    <input type="radio" class="radioFixingCharge" name="is_open_milage" value="no" id="no" />
                                    <label for="no">No</label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Period') }}</label>
                        <div class="input-group">
                            <input name="extended_warranty_period" id="extended_warranty_period" onkeyup="validationOnKeyUp(this)" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Extended Warranty Period" aria-label="measurement" aria-describedby="basic-addon2">
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
                            <input name="claim_limit_in_aed" id="claim_limit_in_aed" onkeyup="validationOnKeyUp(this)" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Claim Limit" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                            </div>
                            <span id="ClaimLimitError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control widthinput" autofocus onkeyup="validationOnKeyUp(this)" >
                            <option></option>
                            @foreach($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                            @endforeach
                        </select>
                        <p id="SupplierError" class="invalid-feedback "></p>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4" id="ExtendedWarrantyMileageDiv" hidden>
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Mileage') }}</label>
                        <div class="input-group">
                            <input name="extended_warranty_milage" id="extended_warranty_milage" onkeyup="validationOnKeyUp(this)" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Extended Warranty Mileage" aria-label="measurement" aria-describedby="basic-addon2">
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
                    <div class="form_field_outer" >
                        <div class="row form_field_outer_row" id="row-1">
                            <div class="col-xxl-7 col-lg-7 col-md-6">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>
                                <select name="brandPrice[1][brands][]" id="brands1" data-index="1" multiple="true" style="width: 100%;"  class="form-control widthinput brands" autofocus>
                                    @foreach($brands as $brand)
                                        <option id="brand1Option{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                    @endforeach
                                </select>
                                <span id="Brand1Error" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-2 col-lg-2 col-md-3">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[1][purchase_price]" id="purchase_price1" onkeyup="validationOnKeyUp(this)" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Purchase Price" aria-label="measurement" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                    <span id="Price1Error" class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-lg-2 col-md-3">
                                <span class="error">* </span>
                                <label class="col-form-label text-md-end">{{ __('Selling Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[1][selling_price]" data-index="1" id="selling_price1" onkeyup="validationOnKeyUp(this)"
                                           type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Selling Price">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                    <span id="SellingPrice1Error" class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <button class="btn_round  removeButton" id="remove-1" disabled hidden>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <a onclick="clickAdd()" id="addSupplier" style="float: right;" class="btn btn-sm btn-info addSupplierAndPriceWithoutKit mt-2">
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
    @endcan
    <script type="text/javascript">
    var selectedBrands = [];
    var filteredArray = [];
    var IsOpenMileage = 'yes';
    var save = 1;

    $(document).ready(function ()
    {
        $('#supplier_id').select2({
            placeholder:"Choose Supplier",
        });
        $('#brands1').select2({
            allowClear: true,
            minimumResultsForSearch: -1,
            placeholder:"Choose Brands....     Or     Type Here To Search....",
        });
        var index = 1;
        $('#indexValue').val(index);
        $(document.body).on('select2:select', ".brands", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            hideOption(index,value);
        });
        $(document.body).on('select2:unselect', ".brands", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
        });
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
                $(this).find('select').attr('data-index', index);
                $(this).find('select').attr('id','brands'+ index);
                $(this).find('select').attr('name','brandPrice['+ index +'][brands][]');
                $(this).find('.selling-price').attr('name','brandPrice['+ index +'][selling_price]');
                $(this).find('.purchase-price').attr('name','brandPrice['+ index +'][purchase_price]');
                $(this).find('button').attr('data-index', index);
                $(this).find('button').attr('id','remove-'+ index);
                $('#brands'+index).select2
                ({
                    placeholder:"Choose Brands....     Or     Type Here To Search....",
                    allowClear: true,
                    minimumResultsForSearch: -1,
                });
            });
        })
        function addOption(id,text) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
              $('#brands'+i).append($('<option>', {value: id, text :text}))
            }
        }

        function hideOption(index,value) {
            var indexValue = $('#indexValue').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'brands' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function appendOption(index,data) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#brands'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
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

        // var formInputError = false;
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
            $msg = "Supplier is required";
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
            $msg = "Purchase Price is required";
            showPrice1Error($msg);
            formInputError = true;
            e.preventDefault();
        }
    });
    function clickAdd()
    {
        var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;

        $('#indexValue').val(index);
        var selectedBrands = [];
        for(let i=1; i<index; i++)
        {
            var eachSelectedBrand = $("#brands"+i).val();
            $.each(eachSelectedBrand, function( ind, value )
            {
                selectedBrands.push(value);
            });
        }
        $.ajax({
            url:"{{url('getBranchForWarranty')}}",
            type: "POST",
            data:
                {
                    filteredArray: selectedBrands,
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
                        <div class="row form_field_outer_row" id="row-${index}" >
                            <div class="col-xxl-7 col-lg-7 col-md-5">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>
                                <select name="brandPrice[${index}][brands][]" id="brands${index}" data-index="${index}" required multiple="true" style="width: 100%;"  class="form-control brands" autofocus>

                                </select>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-2 col-lg-2 col-md-3">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[${index}][purchase_price]" type="number" required class="form-control widthinput purchase-price"
                                    onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Purchase Price" >
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                             <div class="col-xxl-2 col-lg-2 col-md-3">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Selling Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[${index}][selling_price]" type="number" class="form-control widthinput selling-price"
                                    onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Selling Price" >
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer" style="margin-top:36px" >
                                <button type="button" class="btn btn-danger removeButton" id="remove-${index}" data-index="${index}" >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                    let brandDropdownData   = [];
                    $.each(data,function(key,value)
                    {
                        brandDropdownData.push
                        ({
                            id: value.id,
                            text: value.brand_name
                        });
                    });
                    $('#brands'+index).html("");
                    $('#brands'+index).select2
                    ({
                        placeholder:"Choose Brands....     Or     Type Here To Search....",
                        allowClear: true,
                        data: brandDropdownData,
                        minimumResultsForSearch: -1,
                    });
                }
            }
        });
    }

    function validationOnKeyUp(clickInput)
    {
        if(save == 2)
        {
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
            if(clickInput.id == 'purchase_price1')
            {
                var value = clickInput.value;
                if(value == '')
                {
                    $msg = "Purchase Price is required";
                    showPrice1Error($msg);
                }
                else
                {
                    removePrice1Error();
                }
            }
            if(clickInput.id == 'brands1')
            {
                value = $('#brands1').val();
                if(value == '')
                {
                    $msg = "Brand is required";
                    showBrand1Error($msg);
                }
                else
                {
                    removeBrand1Error();
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
        showExtendedWarrantyMilage.hidden = true
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
    function showBrand1Error($msg)
    {
        document.getElementById("Brand1Error").textContent=$msg;
        document.getElementById("brands1").classList.add("is-invalid");
        document.getElementById("Brand1Error").classList.add("paragraph-class");
    }
    function removeBrand1Error()
    {
        document.getElementById("Brand1Error").textContent="";
        document.getElementById("brands1").classList.remove("is-invalid");
        document.getElementById("Brand1Error").classList.remove("paragraph-class");
    }
    function showPrice1Error($msg)
    {
        document.getElementById("Price1Error").textContent=$msg;
        document.getElementById("purchase_price1").classList.add("is-invalid");
        document.getElementById("Price1Error").classList.add("paragraph-class");
    }
    function removePrice1Error()
    {
        document.getElementById("Price1Error").textContent="";
        document.getElementById("purchase_price1").classList.remove("is-invalid");
        document.getElementById("Price1Error").classList.remove("paragraph-class");
    }

</script>
@endsection
