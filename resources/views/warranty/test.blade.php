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
                        <select name="warranty_policies_id" id="warranty_policies_id" class="form-control" autofocus>
                            @foreach($policyNames as $policyName)
                                <option value="{{$policyName->id}}">{{$policyName->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 1') }}</label>
                        <select name="vehicle_category1" id="vehicle_category1" class="form-control" autofocus>
                            <option value="non_electric">Non Electric</option>
                            <option value="electric">Electric</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Choose Vehicle Category 2') }}</label>
                        <select name="vehicle_category2" id="vehicle_category2" class="form-control" autofocus>
                            <option value="normal_and_premium">Normal And Premium</option>
                            <option value="lux_sport_exotic">Lux/Sport/Exotic</option>
                        </select>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Years') }}</label>
                        <div class="input-group">
                            <input name="eligibility_year" id="eligibility_year" onkeyup="validationOnKeyUp(this)" type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Years" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Years</span>
                            </div>
                            <span id="EligibilityYearsError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Mileage') }}</label>
                        <div class="input-group">
                            <input name="eligibility_milage" id="eligibility_milage" onkeyup="validationOnKeyUp(this)" type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Eligibility Mileage" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">KM</span>
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
                            <input name="extended_warranty_period" id="extended_warranty_period" onkeyup="validationOnKeyUp(this)" type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Extended Warranty Period" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">Months</span>
                            </div>
                            <span id="ExtendedWarrantyPeriodError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Claim Limit') }}</label>
                        <div class="input-group">
                            <input name="claim_limit_in_aed" id="claim_limit_in_aed" onkeyup="validationOnKeyUp(this)" type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Claim Limit" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">AED</span>
                            </div>
                            <span id="ClaimLimitError" class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-lg-3 col-md-4" id="ExtendedWarrantyMileageDiv" hidden>
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Mileage') }}</label>
                        <div class="input-group">
                            <input name="extended_warranty_milage" id="extended_warranty_milage" onkeyup="validationOnKeyUp(this)" type="number" class="form-control" onkeypress="return event.charCode >= 48" min="1" placeholder="Extended Warranty Mileage" aria-label="measurement" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">KM</span>
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
                        <h4 class="card-title">Purchase Prices</h4>
                    </center>
                </div>
                <div class="card-body">
                    <div class="form_field_outer">
                        <div class="row form_field_outer_row">
                            <div class="col-xxl-9 col-lg-8 col-md-8">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>
                                <select name="brandPrice[1][brands][]" id="brands1" data-index="1" multiple="true" style="width: 100%;"  class="form-control brands" autofocus>
                                    @foreach($brands as $brand)
                                        <option id="brand1Option{{$brand->id}}" value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                    @endforeach
                                </select>
                                <span id="Brand1Error" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-2 col-lg-3 col-md-3">
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
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <button class="btn_round  removeButtonSupplierWithoutKit" disabled hidden>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <a onclick="clickAdd()" id="addSupplier" style="float: right;" class="btn btn-sm btn-info addSupplierAndPriceWithoutKit">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </a>
                    </div>
                </div>
                <input type="type" id="indexValue" value="1">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-sm" id="submit" style="float:right;">Submit</button>
            </div>
        </form>
    </div>
    <div class="overlay"></div>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#brands1').select2({
                placeholder: 'Select Brands'
            });


            // $(".brands").on('change', function (e) {
            //     // var index = $(this).attr('data-index');
            //    var ind = $(this).attr('data-index').val();
            //     alert("index".ind);
            // })
            //     for(let i=1; i<=3; i++) {

                    $(document.body).on('select2:select',"#brands2", function (e) {
                        // alert("ok");
                        var indexValue = $('#indexValue').val();
                        let id = e.params.data.id;
                        for(var i=0;i<=indexValue;i++) {
                            if(i !== 2) {
                                var currentId = 'brands'+i;
                                $('#'+currentId+' option[value='+id+']').detach();
                            }
                        }
                    });
            $(document.body).on('select2:unselect',"#brands2", function (e) {
                // alert("ok");
                var indexValue = $('#indexValue').val();
                let data = e.params.data;
                for(var i=0;i<=indexValue;i++) {
                    if(i !== 2) {
                        $('#brands'+i).append($('<option>', {value: data.id, text : data.text}))
                    }
                }
            });
        })
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
            $.ajax
            ({
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
                        <div class="row form_field_outer_row">
                            <div class="col-xxl-9 col-lg-8 col-md-8">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Brands') }}</label>
                                <select name="brandPrice[${index}][brands][]" id="brands${index}" data-index="${index}" multiple="true" style="width: 100%;"  class="form-control brands" autofocus>

                                </select>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                            <div class="col-xxl-2 col-lg-3 col-md-3">
                                <span class="error">* </span>
                                <label for="supplier" class="col-form-label text-md-end">{{ __('Purchase Price') }}</label>
                                <div class="input-group">
                                    <input name="brandPrice[${index}][purchase_price]" type="number" class="form-control widthinput" onkeypress="return event.charCode >= 48" min="1" placeholder="Enter Purchase Price" aria-label="measurement" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                                <span id="supplierError" class="invalid-feedback"></span>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <button class="btn_round  removeButtonSupplierWithoutKit" disabled hidden>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `);
                        $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);
                        $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
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
                            placeholder: 'Select value',
                            allowClear: true,
                            data: brandDropdownData,
                            minimumResultsForSearch: -1,
                            // templateResult: hideSelected,
                        });
                    }
                }
            });
        }


    </script>
@endsection
