@extends('layouts.main')
<style>
    .modal-xl
    {
        max-width: 99% !important;
    }
    #blah
    {
        width: 300px;
        height: 300px;
    }
    #showImage
    {
        width: auto;
        height: auto;
        max-width:1200px;
    }
    @media only screen and (max-device-width: 480px)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
        #blah
        {
            width: 200px;
            height: 200px;
        }
    }
    @media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
    }
    @media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
    }
    @media only screen and (max-device-width: 1280px)
    {
        #showImage
        {
            width: 100%;
            height: 100%;
        }
    }
    .contain
    {
    object-fit: contain;
    }
    .error
    {
        color: #FF0000;
    }
    .paragraph-class
    {
        color: red;
        font-size:11px;
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
    .paragraph-class
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
    /* .card
    {
        margin-right:20px!important;
        margin-left:20px!important;
    } */
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
<div class="card-header">
    <h4 class="card-title">Kit Vendors and Purchase Price</h4>
</div>
<form id="createAddonForm" name="createAddonForm" method="POST" enctype="multipart/form-data" action="{{ route('kit.store') }}">
            @csrf
          <input hidden value={{$id}} name="kit_addon_id">
<div class="card-body supplierAddForKit">



<div class="row addSupplierForKitRow" id="row-1" >
                            <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
                                <div id="London" class="tabcontent">
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 p-0">
                                                        <div class="col-md-12 p-0">
                                                            <div class="row">
                                                                <div class="col-xxl-4 col-lg-6 col-md-12">
                                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Vendors</label>
                                                                    <select name="kitSupplierAndPrice[1][supplier_id]" id="kitSupplierDropdown1" multiple="true"
                                                                    style="width: 100%;" class="kitSuppliers" data-index="1" required >
                                                                        @foreach($suppliers as $supplier)
                                                                    <option class="{{$supplier->id}}" value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                        @error('supplier_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input readonly name="kitSupplierAndPrice[1][supplier_addon_purchase_price_in_aed]" id="Supplier1TotalPriceAED"
                                                                    oninput="inputNumberAbs(this)"
                                                                    class="leastPurchasePriceAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror"

                                                                    autocomplete="addon_purchase_price"
                                                                    autofocus onkeyup="calculateUSD(1)">
                                                                      <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-3 col-lg-3 col-md-3"  style="background-color: 	 #F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input readonly name="kitSupplierAndPrice[1][supplier_addon_purchase_price_in_usd]" id="Supplier1TotalPriceUSD"
                                                                    oninput="inputNumberAbs(this)" class="form-control purchase-price-USD widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror"

                                                                      autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>

                                                                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                                    <a class="btn_round removeKitSupplier" data-index="1">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="card-title">Kit Items And Purchase Price</h4>
                                <div id="London" class="tabcontent">
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 p-0">
                                                    <div hidden>{{$j=0;}}</div>
                                                        @foreach($kitItemDropdown as $kitItemDropdownData)
                                                        <div id="rowDesCount1" hidden value="{{$j+1}}">{{$j=$j+1;}}</div>
                                                        <div class="col-md-12 apendNewItemHere1 p-0" id="kitItemRow">
                                                            <div class="row kitItemSubRow kitItemRowForSupplier1 kititemdelete" id="row-supplier-1-item-{{$j}}">
                                                                <div class="col-xxl-3 col-lg-6 col-md-12">
                                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                                                    <input hidden name="kitSupplierAndPrice[1][item][{{$j}}][kit_id]" id="kit_id_1_{{$j}}" value="{{$kitItemDropdownData->addon_details_id}}">
                                                                    <input hidden name="kitSupplierAndPrice[1][item][{{$j}}][kit_item_id]" id="kit_item_id_1_{{$j}}" value="{{$kitItemDropdownData->item_id}}">
                                                                    <input readonly class="form-control widthinput"
                                                                        value="{{$kitItemDropdownData->item->addon_code}} ( {{$kitItemDropdownData->item->AddonName->name}} )">
                                                                        @error('supplier_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                <div class="col-xxl-1 col-lg-3 col-md-3"  >
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                                                    <input value={{$kitItemDropdownData->quantity}} name="kitSupplierAndPrice[1][item][{{$j}}][quantity]" id="Supplier1Kit{{$j}}Quantity" type="number" value="1" min="1"
                                                                     class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantity" placeholder="Enter Quantity"
                                                                     autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(1,{{$j}})"
                                                                     onchange="calculateOtherValuesbyQuantity(1,{{$j}})" oninput="validity.valid||(value='1');" readonly>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[1][item][{{$j}}][unit_price_in_aed]" id="Supplier1Kit{{$j}}UnitPriceAED" oninput="inputNumberAbs(this)"
                                                                     class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror unit-price-AED"
                                                                      placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                                                      onkeyup="calculateOtherValuesbyUniTPriceAED(1,{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[1][item][{{$j}}][total_price_in_aed]" id="Supplier1Kit{{$j}}TotalPriceAED" oninput="inputNumberAbs(this)"
                                                                     class="Supplier1TotalPriceInAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror total-price-AED"
                                                                     placeholder="Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                                                     onkeyup="calculateOtherValuesbyTotalPriceAED(1,{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3"  style="background-color: 	#F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[1][item][{{$j}}][unit_price_in_usd]" id="Supplier1Kit{{$j}}UnitPriceUSD" oninput="inputNumberAbs(this)"
                                                                     class=" form-control  widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror unit-price-USD"
                                                                      placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                                       onkeyup="calculateOtherValuesbyUnitPriceUSD(1,{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg- col-md-3" style="background-color: 	#F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[1][item][{{$j}}][total_price_in_usd]" id="Supplier1Kit{{$j}}TotalPriceUSD" oninput="inputNumberAbs(this)"
                                                                    class="Supplier1TotalPriceInUSD form-control widthinput total-price-USD @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                                                     placeholder="Enter Total Price In USD"  value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                                    onkeyup="calculateOtherValuesbyTotalPriceUSD(1,{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>
                                            </br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
<input type="hidden" id="supplierIndex" value="">
</div>
</br id="kitSupplierBrToHid1eandshow">
<div class="row" id="kitSupplierButtonToHideandshow">
    <div class="col-xxl-12 col-lg-12 col-md-12">
        <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info buttonForAddNewKitSupplier"><i class="fa fa-plus" aria-hidden="true"></i> Add Vendor</a>
    </div>
</div>
<input type="hidden" id="kitItemIndex" value="">

<div class="col-md-12">
</br>
                    <button type="submit" class="btn btn-primary" id="submit" style="float:right;">Submit</button>
                </div>
</form>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#kitSupplierDropdown1").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
        $("#kitSupplierDropdown1").select2
        ({
            maximumSelectionLength: 1,
        });
        // $('#kitSupplierIdToHideandshow').hide();
        // $('#kitSupplierBrToHideandshow').hide();
        // $('#kitSupplierButtonToHideandshow').hide();
        $(".leastPurchasePriceAED").on("keyup change", function(e) {
            setLeastPurchasePriceAED();
        });

        var indexvalue = 1;

        $('#kitItemIndex').val(indexvalue);
        /////////// keit item add section //////////////
        $(document.body).on('select2:select', ".KitSupplierItems", function (e) {
            var index = $(this).attr('data-index');
            var supplier = $(this).attr('data-supplier');
            var value = e.params.data.id;
            KitItemHideOption(index,supplier,value);
            // disableDropdown();
        });
        $(document.body).on('select2:unselect', ".KitSupplierItems", function (e) {
            var index = $(this).attr('data-index');
            var supplier = $(this).attr('data-supplier');
            var data = e.params.data;
            KitItemAppendOption(index,supplier,data);
            // enableDropdown();
        });
        function KitItemHideOption(index,supplier,value) {
            var indexValue = $('#kitItemIndex').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'kitSupplier'+supplier+'Item' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function KitItemAppendOption(index,supplier,data) {
            var indexValue = $('#kitItemIndex').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#kitSupplier'+supplier+'Item'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        function KitItemAddOption(id,supplier,text) {
            var indexValue = $('#supplierIndex').val();
            for(var i=1;i<=indexValue;i++) {
                $('#kitSupplier'+supplier+'Item'+i).append($('<option>', {value: id, text :text}))
            }
        }
        function ReCalculatePurchasePriceAED(supplier,indexNumber) {
            var totalPriceInAED = $('#Supplier'+ supplier +'Kit'+indexNumber+'TotalPriceAED').val();
            var TotalpurchasePriceInAED = $('#Supplier'+ supplier +'TotalPriceAED').val();
            var latestPurchasePrice = TotalpurchasePriceInAED - totalPriceInAED;
            $('#Supplier'+ supplier +'TotalPriceAED').val(latestPurchasePrice);
        }

        function ReCalculatePurchasePriceUSD(supplier,indexNumber) {
            var totalPriceInUSD = $('#Supplier'+ supplier +'Kit'+indexNumber+'TotalPriceUSD').val();
            var TotalpurchasePriceInUSD = $('#Supplier'+ supplier +'TotalPriceUSD').val();
            var latestPurchasePrice = TotalpurchasePriceInUSD - totalPriceInUSD;
            $('#Supplier'+ supplier +'TotalPriceUSD').val(latestPurchasePrice);
        }

        $(document.body).on('click', ".removeKitItem", function (e) {
            var indexNumber = $(this).attr('data-index');
            var supplier = $(this).attr('data-supplier');

            $(this).closest('#row-supplier-'+supplier+'-item-'+indexNumber).find("option:selected").each(function() {
                var id = (this.value);
                var text = (this.text);
                KitItemAddOption(id,supplier,text)
            });
            ReCalculatePurchasePriceAED(supplier,indexNumber)
            ReCalculatePurchasePriceUSD(supplier,indexNumber)

            $(this).closest('#row-supplier-'+supplier+'-item-'+indexNumber).remove();

            $('.kitItemRowForSupplier'+supplier).each(function(i){
                var index = +i + +1;
                $(this).attr('id','row-supplier-'+supplier+'-item-'+index);
                $(this).find('.KitSupplierItems').attr('data-index', index);
                $(this).find('.KitSupplierItems').attr('id','kitSupplier'+supplier+'Item'+index);
                $(this).find('.KitSupplierItems').attr('name','kitSupplierAndPrice['+supplier+'][item]['+index+'][kit_item_id]');

                $(this).find('.quantity').attr('name', 'kitSupplierAndPrice['+supplier+'][item]['+index+'][quantity]');
                $(this).find('.quantity').attr('id', 'Supplier'+supplier+'Kit'+index+'Quantity');
                $(this).find('.quantity').attr('onkeyup', 'calculateOtherValuesbyQuantity('+supplier+','+index+')');
                $(this).find('.quantity').attr('onchange', 'calculateOtherValuesbyQuantity('+supplier+','+index+')');

                $(this).find('.unit-price-AED').attr('name', 'kitSupplierAndPrice['+supplier+'][item]['+index+'][unit_price_in_aed]');
                $(this).find('.unit-price-AED').attr('id', 'Supplier'+supplier+'Kit'+index+'UnitPriceAED');
                $(this).find('.unit-price-AED').attr('onkeyup', 'calculateOtherValuesbyUniTPriceAED('+supplier+','+index+')');

                $(this).find('.total-price-AED').attr('id', 'Supplier'+supplier+'Kit'+index+'TotalPriceAED');
                $(this).find('.total-price-AED').attr('name', 'kitSupplierAndPrice['+supplier+'][item]['+index+'][total_price_in_aed]');
                $(this).find('.total-price-AED').attr('onkeyup', 'calculateOtherValuesbyTotalPriceAED('+ index +',1)');
                $(this).find('.total-price-AED').attr('class', 'Supplier'+supplier+'TotalPriceInAED total-price-AED form-control widthinput @error('addon_purchase_price')
                    is-invalid @enderror');

                $(this).find('.unit-price-USD').attr('name', 'kitSupplierAndPrice['+supplier+'][item]['+index+'][unit_price_in_usd]');
                $(this).find('.unit-price-USD').attr('id', 'Supplier'+supplier+'Kit'+index+'UnitPriceUSD');
                $(this).find('.unit-price-USD').attr('onkeyup', 'calculateOtherValuesbyUnitPriceUSD('+supplier+','+index+')');


                $(this).find('.total-price-USD').attr('name', 'kitSupplierAndPrice['+supplier+'][item]['+index+'][total_price_in_usd]');
                $(this).find('.total-price-USD').attr('id', 'Supplier'+supplier+'Kit'+index+'TotalPriceUSD');
                $(this).find('.total-price-USD').attr('onkeyup', 'calculateOtherValuesbyTotalPriceUSD('+supplier+','+index+')');

                $(this).find('.removeKitItem').attr('data-index', index);
                $(this).find('.removeKitItem').attr('data-supplier', supplier);


                // $(this).find('button').attr('id','remove-'+ index);
                $('#kitSupplier'+supplier+'Item'+index).select2
                ({
                    placeholder:"Choose Items....     Or     Type Here To Search....",
                    allowClear: true,
                    maximumSelectionLength: 1,
                });
            });
            setLeastPurchasePriceAED();

        })
        /////////// supplier Add Section ///////////////

    });
    $("body").on("click",".buttonForAddNewKitSupplier", function ()
    {
        var index = $(".supplierAddForKit").find(".addSupplierForKitRow").length + 1;
        $('#supplierIndex').val(index);
        var currentAddonType = $('#addon_type').val();
        var selectedAddonsSuppliers = [];
        for(let i=1; i<index; i++)
        {
            var eachSelectedSupplier = $("#kitSupplierDropdown"+i).val();
            if(eachSelectedSupplier) {
                selectedAddonsSuppliers.push(eachSelectedSupplier);
            }
        }
        $.ajax({
            url:"{{url('getSupplierForAddon')}}",
            type: "POST",
            data:
                {
                    addonType:currentAddonType,
                    filteredArray: selectedAddonsSuppliers,
                    _token: '{{csrf_token()}}'
                },
            dataType : 'json',
            success: function(data)
            {
                myarray = data;
                var size= myarray.length;
                if(size >= 1)
                {
                    $(".supplierAddForKit").append(`
                        <div class="row addSupplierForKitRow" id="row-${index}" >
                            <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
                                <div id="London" class="tabcontent">
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 p-0">
                                                        <div class="col-md-12 p-0">
                                                            <div class="row">
                                                                <div class="col-xxl-4 col-lg-6 col-md-12">
                                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Vendors</label>
                                                                    <select name="kitSupplierAndPrice[${index}][supplier_id]" id="kitSupplierDropdown${index}" multiple="true"
                                                                    style="width: 100%;" class="kitSuppliers" data-index="${index}" required >
                                                                        @foreach($suppliers as $supplier)
                                                                    <option class="{{$supplier->id}}" value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                        @error('supplier_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input readonly name="kitSupplierAndPrice[${index}][supplier_addon_purchase_price_in_aed]" id="Supplier${index}TotalPriceAED"
                                                                    oninput="inputNumberAbs(this)"
                                                                    class="leastPurchasePriceAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror"

                                                                    autocomplete="addon_purchase_price"
                                                                    autofocus onkeyup="calculateUSD(1)">
                                                                      <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-3 col-lg-3 col-md-3"  style="background-color: 	 #F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input readonly name="kitSupplierAndPrice[${index}][supplier_addon_purchase_price_in_usd]" id="Supplier${index}TotalPriceUSD"
                                                                    oninput="inputNumberAbs(this)" class="form-control purchase-price-USD widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror"

                                                                      autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>

                                                                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                                    <a class="btn_round removeKitSupplier" data-index="${index}">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="card-title">Kit Items And Purchase Price</h4>
                                <div id="London" class="tabcontent">
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 p-0">
                                                    <div hidden>{{$j=0;}}</div>
                                                        @foreach($kitItemDropdown as $kitItemDropdownData)
                                                        <div id="rowDesCount${index}" hidden value="{{$j+1}}">{{$j=$j+1;}}</div>
                                                        <div class="col-md-12 apendNewItemHere${index} p-0" id="kitItemRow">
                                                            <div class="row kitItemSubRow kitItemRowForSupplier${index} kititemdelete" id="row-supplier-${index}-item-{{$j}}">
                                                                <div class="col-xxl-3 col-lg-6 col-md-12">
                                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                                                    <input hidden name="kitSupplierAndPrice[${index}][item][{{$j}}][kit_id]" id="kit_id_${index}_{{$j}}" value="{{$kitItemDropdownData->addon_details_id}}">
                                                                    <input hidden name="kitSupplierAndPrice[${index}][item][{{$j}}][kit_item_id]" id="kit_item_id_${index}_{{$j}}" value="{{$kitItemDropdownData->item_id}}">
                                                                    <input readonly class="form-control widthinput"
                                                                        value="{{$kitItemDropdownData->item->addon_code}} ( {{$kitItemDropdownData->item->AddonName->name}} )">
                                                                        @error('supplier_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                <div class="col-xxl-1 col-lg-3 col-md-3"  >
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                                                    <input value={{$kitItemDropdownData->quantity}} name="kitSupplierAndPrice[${index}][item][{{$j}}][quantity]" id="Supplier${index}Kit{{$j}}Quantity" type="number" value="1" min="1"
                                                                     class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantity" placeholder="Enter Quantity"
                                                                     autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(${index},{{$j}})"
                                                                     onchange="calculateOtherValuesbyQuantity(${index},{{$j}})" oninput="validity.valid||(value='1');" readonly>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[${index}][item][{{$j}}][unit_price_in_aed]" id="Supplier${index}Kit{{$j}}UnitPriceAED" oninput="inputNumberAbs(this)"
                                                                     class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror unit-price-AED"
                                                                      placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                                                      onkeyup="calculateOtherValuesbyUniTPriceAED(${index},{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[${index}][item][{{$j}}][total_price_in_aed]" id="Supplier${index}Kit{{$j}}TotalPriceAED" oninput="inputNumberAbs(this)"
                                                                     class="Supplier${index}TotalPriceInAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror total-price-AED"
                                                                     placeholder="Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                                                     onkeyup="calculateOtherValuesbyTotalPriceAED(${index},{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3"  style="background-color: 	#F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[${index}][item][{{$j}}][unit_price_in_usd]" id="Supplier${index}Kit{{$j}}UnitPriceUSD" oninput="inputNumberAbs(this)"
                                                                     class=" form-control  widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror unit-price-USD"
                                                                      placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                                       onkeyup="calculateOtherValuesbyUnitPriceUSD(${index},{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg- col-md-3" style="background-color: 	#F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input required name="kitSupplierAndPrice[${index}][item][{{$j}}][total_price_in_usd]" id="Supplier${index}Kit{{$j}}TotalPriceUSD" oninput="inputNumberAbs(this)"
                                                                    class="Supplier${index}TotalPriceInUSD form-control widthinput total-price-USD @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                                                     placeholder="Enter Total Price In USD"  value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                                    onkeyup="calculateOtherValuesbyTotalPriceUSD(${index},{{$j}})">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                            </div>
                                            </br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                    let supplierDropdownData   = [];
                    $.each(data,function(key,value)
                    {
                        supplierDropdownData.push
                        ({
                            id: value.id,
                            text: value.supplier
                        });
                    });
                    $("#kitSupplier"+index+"Item1").attr("data-placeholder","Choose Items....     Or     Type Here To Search....");
                    $("#kitSupplier"+index+"Item1").select2
                    ({
                        maximumSelectionLength: 1,
                    });
                    $('#kitSupplierDropdown'+index).html("");
                    $("#kitSupplierDropdown"+index).select2
                    ({
                        placeholder:"Choose Vendor....     Or     Type Here To Search.....",
                        data: supplierDropdownData,
                        allowClear: true,
                        maximumSelectionLength: 1,
                    });
                }
            }
        });

    });
    $("body").on("click", ".removeKitItemForSupplier", function ()
    {
        // alert('hig');
        $(this).closest(".kititemdelete").remove();
        // calculateTotalPriceInAED();
        // calculateTotalPriceInUSD();
    });
    // $("body").on("click", ".removeKitSupplier", function ()
    // {
    //     $(this).closest(".addSupplierForKitRow").remove();
    //     setLeastPurchasePriceAED();
    // });

    // function removeKitItemForSupplier(supplier)
    // {
    //     alert('kok');
    //     // $(this).closest(".kitItemRowForSupplier"+supplier).remove();
    // }
    function calculateOtherValuesbyUniTPriceAED(supplier,kit)
    {
        var quantity = $("#Supplier"+supplier+"Kit"+kit+"Quantity").val();
        var unitPriceAED = $("#Supplier"+supplier+"Kit"+kit+"UnitPriceAED").val();
        calculateRelatedofUnitPriceAED(quantity,unitPriceAED,supplier,kit);
    }
    function calculateOtherValuesbyTotalPriceAED(supplier,kit)
    {
        var quantity = $("#Supplier"+supplier+"Kit"+kit+"Quantity").val();
        var totalPriceAED = $("#Supplier"+supplier+"Kit"+kit+"TotalPriceAED").val();
        calculateRelatedofTotalPriceAED(quantity,totalPriceAED,supplier,kit);
    }
    function calculateOtherValuesbyUnitPriceUSD(supplier,kit)
    {
        var quantity = $("#Supplier"+supplier+"Kit"+kit+"Quantity").val();
        var unitPriceUSD = $("#Supplier"+supplier+"Kit"+kit+"UnitPriceUSD").val();
        calculateRelatedofUnitPriceUSD(quantity,unitPriceUSD,supplier,kit);
    }
    function calculateOtherValuesbyTotalPriceUSD(supplier,kit)
    {
        var quantity = $("#Supplier"+supplier+"Kit"+kit+"Quantity").val();
        var totalPriceUSD = $("#Supplier"+supplier+"Kit"+kit+"TotalPriceUSD").val();
        calculateRelatedofTotalPriceUSD(quantity,totalPriceUSD,supplier,kit);
    }
    function calculateOtherValuesbyQuantity(supplier,kit)
    {
        var quantity = $("#Supplier"+supplier+"Kit"+kit+"Quantity").val();
        var unitPriceAED = $("#Supplier"+supplier+"Kit"+kit+"UnitPriceAED").val();
        var totalPriceAED = $("#Supplier"+supplier+"Kit"+kit+"TotalPriceAED").val();
        var unitPriceUSD = $("#Supplier"+supplier+"Kit"+kit+"UnitPriceUSD").val();
        var totalPriceUSD = $("#Supplier"+supplier+"Kit"+kit+"TotalPriceUSD").val();
        if(unitPriceAED != '')
        {
            calculateRelatedofUnitPriceAED(quantity,unitPriceAED,supplier,kit);
        }
        else if(totalPriceAED != '')
        {
            calculateRelatedofTotalPriceAED(quantity,totalPriceAED,supplier,kit);
        }
        else if(unitPriceUSD != '')
        {
            calculateRelatedofUnitPriceUSD(quantity,unitPriceUSD,supplier,kit);
        }
        else if(totalPriceUSD != '')
        {
            calculateRelatedofTotalPriceUSD(quantity,totalPriceUSD,supplier,kit);
        }
    }
    function showRelatedValues(unitPriceAED,totalPriceAED,unitPriceUSD,totalPriceUSD,supplier,kit)
    {
        if(unitPriceAED == 0)
        {
            $("#Supplier"+supplier+"Kit"+kit+"UnitPriceAED").val("");
        }
        else
        {
            $("#Supplier"+supplier+"Kit"+kit+"UnitPriceAED").val(unitPriceAED);
        }
        if(totalPriceAED == 0)
        {
            $("#Supplier"+supplier+"Kit"+kit+"TotalPriceAED").val("");
            calculateTotalPriceInAED(supplier);
        }
        else
        {
            $("#Supplier"+supplier+"Kit"+kit+"TotalPriceAED").val(totalPriceAED);
            calculateTotalPriceInAED(supplier);
        }
        if(unitPriceUSD == 0)
        {
            $("#Supplier"+supplier+"Kit"+kit+"UnitPriceUSD").val("");
        }
        else
        {
            $("#Supplier"+supplier+"Kit"+kit+"UnitPriceUSD").val(unitPriceUSD);
        }
        if(totalPriceUSD == 0)
        {
            $("#Supplier"+supplier+"Kit"+kit+"TotalPriceUSD").val("");
            calculateTotalPriceInUSD(supplier);
        }
        else
        {
            $("#Supplier"+supplier+"Kit"+kit+"TotalPriceUSD").val(totalPriceUSD);
            calculateTotalPriceInUSD(supplier);
        }
    }
    function calculateRelatedofUnitPriceAED(quantity,unitPriceAED,supplier,kit)
    {
        var totalPriceAED = quantity * unitPriceAED;
        totalPriceAED = totalPriceAED.toFixed(4);
        totalPriceAED = parseFloat(totalPriceAED);
        var unitPriceUSD = unitPriceAED / 3.6725;
        unitPriceUSD = unitPriceUSD.toFixed(4);
        unitPriceUSD = parseFloat(unitPriceUSD);
        var totalPriceUSD = totalPriceAED / 3.6725;
        totalPriceUSD = totalPriceUSD.toFixed(4);
        totalPriceUSD = parseFloat(totalPriceUSD);
        showRelatedValues(unitPriceAED,totalPriceAED,unitPriceUSD,totalPriceUSD,supplier,kit);
    }
    function calculateRelatedofTotalPriceAED(quantity,totalPriceAED,supplier,kit)
    {
        var unitPriceAED = totalPriceAED / quantity;
        unitPriceAED = unitPriceAED.toFixed(4);
        unitPriceAED = parseFloat(unitPriceAED);
        var unitPriceUSD = totalPriceAED / 3.6725;
        unitPriceUSD = unitPriceUSD.toFixed(4);
        unitPriceUSD = parseFloat(unitPriceUSD);
        var totalPriceUSD = unitPriceAED / 3.6725;
        totalPriceUSD = totalPriceUSD.toFixed(4);
        totalPriceUSD = parseFloat(totalPriceUSD);
        showRelatedValues(unitPriceAED,totalPriceAED,unitPriceUSD,totalPriceUSD,supplier,kit);
    }
    function calculateRelatedofUnitPriceUSD(quantity,unitPriceUSD,supplier,kit)
    {
        var totalPriceUSD = unitPriceUSD * quantity;
        totalPriceUSD = totalPriceUSD.toFixed(4);
        totalPriceUSD = parseFloat(totalPriceUSD);
        var unitPriceAED = unitPriceUSD * 3.6725;
        unitPriceAED = unitPriceAED.toFixed(4);
        unitPriceAED = parseFloat(unitPriceAED);
        var totalPriceAED = totalPriceUSD * 3.6725;
        totalPriceAED = totalPriceAED.toFixed(4);
        totalPriceAED = parseFloat(totalPriceAED);
        // alert(unitPriceUSD);
        // alert(totalPriceUSD);
        showRelatedValues(unitPriceAED,totalPriceAED,unitPriceUSD,totalPriceUSD,supplier,kit);
    }
    function calculateRelatedofTotalPriceUSD(quantity,totalPriceUSD,supplier,kit)
    {
        var unitPriceUSD = totalPriceUSD / quantity;
        unitPriceUSD = unitPriceUSD.toFixed(4);
        unitPriceUSD = parseFloat(unitPriceUSD);
        var unitPriceAED = unitPriceUSD * 3.6725;
        unitPriceAED = unitPriceAED.toFixed(4);
        unitPriceAED = parseFloat(unitPriceAED);
        var totalPriceAED = totalPriceUSD * 3.6725;
        totalPriceAED = totalPriceAED.toFixed(4);
        totalPriceAED = parseFloat(totalPriceAED);
        showRelatedValues(unitPriceAED,totalPriceAED,unitPriceUSD,totalPriceUSD,supplier,kit);
    }
    function  calculateTotalPriceInAED(supplier)
    {
        const values = Array.from(document.querySelectorAll('.Supplier'+supplier+'TotalPriceInAED')).map(input => input.value)
        var arrayOfNumbers = values.map(Number);
        let myNums = arrayOfNumbers;
        let sum = 0;
        for (let i = 0; i < myNums.length; i++ )
        {
            sum += myNums[i];
        }
        if(sum == '0')
        {
            $("#Supplier"+supplier+"TotalPriceAED").val('');
            setLeastPurchasePriceAED();
        }
        else
        {
            $("#Supplier"+supplier+"TotalPriceAED").val(sum);
            setLeastPurchasePriceAED();
        }
    }
    function  calculateTotalPriceInUSD(supplier)
    {
        const values = Array.from(document.querySelectorAll('.Supplier'+supplier+'TotalPriceInUSD')).map(input => input.value);
        var arrayOfNumbers = values.map(Number);
        let myNums = arrayOfNumbers;
        let sum = 0;
        for (let i = 0; i < myNums.length; i++ )
        {
            sum += myNums[i];
        }
        if(sum == '0')
        {
            $("#Supplier"+supplier+"TotalPriceUSD").val('');
        }
        else
        {
            $("#Supplier"+supplier+"TotalPriceUSD").val(sum);
        }
    }
    function setLeastPurchasePriceAED()
    {
        const values = Array.from(document.querySelectorAll('.leastPurchasePriceAED')).map(input => input.value);
        if(values != '')
        {
            var arrayOfNumbers = [];
            values.forEach(v => {
                if(v != '')
                {
                    arrayOfNumbers .push(v);
                }
            });
            var size= arrayOfNumbers.length;
            if(size >= 1)
            {
                var arrayOfNumbers = arrayOfNumbers.map(Number);
                const min = Math.min(...arrayOfNumbers);
                $("#purchase_price").val(min);
                // disableDropdown();
            }
            else
            {
                $("#purchase_price").val('');
                // enableDropdown();
            }
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#kitSupplier1Item1").attr("data-placeholder","Choose Items....     Or     Type Here To Search....");
        $("#kitSupplier1Item1").select2
        ({
            maximumSelectionLength: 1,
        });
        var index = 1;
        $('#supplierIndex').val(index);

        $(document.body).on('select2:select', ".kitSuppliers", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            hideOption(index,value);
            // disableDropdown();
        });
        $(document.body).on('select2:unselect', ".kitSuppliers", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
            // enableDropdown();
        });
        function hideOption(index,value) {
            var indexValue = $('#supplierIndex').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'kitSupplierDropdown' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function appendOption(index,data) {
            var indexValue = $('#supplierIndex').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#kitSupplierDropdown'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        function addOption(id,text) {
            var indexValue = $('#supplierIndex').val();
            for(var i=1;i<=indexValue;i++) {
                $('#kitSupplierDropdown'+i).append($('<option>', {value: id, text :text}))

            }
        }
        $(document.body).on('click', ".removeKitSupplier", function (e) {
            var countRow = 0;
            var countRow = $(".supplierAddForKit").find(".addSupplierForKitRow").length;
            if(countRow > 1)
            {
                var indexNumber = $(this).attr('data-index');
                var supplierTotalIndex = $('#supplierIndex').val();
                $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                    var id = (this.value);
                    var text = (this.text);
                    addOption(id,text)
                });
                $(this).closest('#row-'+indexNumber).remove();
                $('.addSupplierForKitRow').each(function(i){
                    var index = +i + +1;

                    $(this).attr('id','row-'+ index);
                    $(this).find('#row-'+ index).attr('class','row addSupplierForKitRow');

                    $(this).find('.kitSuppliers').attr('name','kitSupplierAndPrice['+ index +'][supplier_id]');
                    $(this).find('.kitSuppliers').attr('id','kitSupplierDropdown'+ index);
                    $(this).find('.kitSuppliers').attr('data-index', index);

                    $(this).find('.leastPurchasePriceAED').attr('name','kitSupplierAndPrice['+ index +'][supplier_addon_purchase_price_in_aed]');
                    $(this).find('.leastPurchasePriceAED').attr('id','Supplier'+ index +'TotalPriceAED');

                    $(this).find('.purchase-price-USD').attr('name','kitSupplierAndPrice['+ index +'][supplier_addon_purchase_price_in_usd]');
                    $(this).find('.purchase-price-USD').attr('id','Supplier'+ index +'TotalPriceUSD');

                    $(this).find('.removeKitSupplier').attr('data-index', index);

                    // $(this).find('#kitItemRow').attr('class','col-md-12 p-0 apendNewItemHere'+index);

                    $(this).find('#addSupplier').attr('onclick', 'addItemForSupplier('+ index +')');

                    var oldIndex = '';
                    oldIndex = index+1;
                    itemcount = $(".apendNewItemHere"+oldIndex).find(".kitItemRowForSupplier"+oldIndex).length;
                    for (var i = 1; i <= itemcount; i++) {
                        $(this).find('#row-supplier-'+oldIndex+'-item-'+i).attr('id','row-supplier-'+index+'-item-'+i);


                        $(this).find('#kitSupplier'+oldIndex+'Item'+i).attr('id', 'kitSupplier'+ index +'Item'+i);
                        $('#kitSupplier'+index+'Item'+i).select2
                        ({
                            placeholder:"Choose Suppliers....     Or     Type Here To Search....",
                            allowClear: true,
                            minimumResultsForSearch: -1,
                        });

                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'Quantity').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+i+'][quantity]');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'Quantity').attr('onkeyup', 'calculateOtherValuesbyQuantity('+ index +','+i+')');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'Quantity').attr('onchange', 'calculateOtherValuesbyQuantity('+ index +','+i+')');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'Quantity').attr('id', 'Supplier'+ index +'Kit'+i+'Quantity');

                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceAED').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+i+'][unit_price_in_aed]');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceAED').attr('onkeyup', 'calculateOtherValuesbyUniTPriceAED('+ index +','+i+')');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceAED').attr('id', 'Supplier'+ index +'Kit'+i+'UnitPriceAED');

                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceAED').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+i+'][total_price_in_aed]');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceAED').attr('onkeyup', 'calculateOtherValuesbyTotalPriceAED('+ index +','+i+')');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceAED').attr('class', 'Supplier'+ index +'TotalPriceInAED form-control widthinput @error('addon_purchase_price')
                            is-invalid @enderror total-price-AED');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceAED').attr('id', 'Supplier'+ index +'Kit'+i+'TotalPriceAED');

                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceUSD').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+i+'][unit_price_in_usd]');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceUSD').attr('onkeyup', 'calculateOtherValuesbyUnitPriceUSD('+ index +','+i+')');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceUSD').attr('class', 'form-control widthinput @error('addon_purchase_price_in_usd')
                            is-invalid @enderror unit-price-USD');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'UnitPriceUSD').attr('id', 'Supplier'+ index +'Kit'+i+'UnitPriceUSD');

                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceUSD').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+i+'][total_price_in_usd]');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceUSD').attr('onkeyup', 'calculateOtherValuesbyTotalPriceUSD('+ index +','+i+')');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceUSD').attr('class', 'Supplier'+ index +'TotalPriceInUSD total-price-USD form-control widthinput @error('addon_purchase_price_in_usd')
                            is-invalid @enderror');
                        $(this).find('#Supplier'+oldIndex+'Kit'+i+'TotalPriceUSD').attr('id', 'Supplier'+ index +'Kit'+i+'TotalPriceUSD');

                        $(this).find('#removeSupplier'+oldIndex+'Item'+i).attr('data-supplier', index);
                        $(this).find('#removeSupplier'+oldIndex+'Item'+i).attr('data-index', i);
                        $(this).find('#removeSupplier'+oldIndex+'Item'+i).attr('class', 'btn_round removeKitItemForSupplier'+index+' removeKitItem');
                        $(this).find('#removeSupplier'+oldIndex+'Item'+i).attr('id', 'removeSupplier'+index+'Item'+i);

                        $(this).find('#kit_id_'+oldIndex+'_'+i).attr('name', 'kitSupplierAndPrice['+index+'][item]['+i+'][kit_id]');
                        $(this).find('#kit_id_'+oldIndex+'_'+i).attr('id', 'kit_id_'+index+'_'+i);

                        $(this).find('#kit_item_id_'+oldIndex+'_'+i).attr('name', 'kitSupplierAndPrice['+index+'][item]['+i+'][kit_item_id]');
                        $(this).find('#kit_item_id_'+oldIndex+'_'+i).attr('id', 'kit_item_id_'+index+'_'+i);

                    }
                    $(this).find('.apendNewItemHere'+oldIndex).attr('class','apendNewItemHere'+index);
                    $(this).find('.kitItemRowForSupplier'+oldIndex).attr('class','row kititemdelete kitItemRowForSupplier'+index);
                    $('#kitSupplierDropdown'+index).select2
                    ({
                        placeholder:"Choose Vendors....     Or     Type Here To Search....",
                        allowClear: true,
                        minimumResultsForSearch: -1,
                    });
                });
                setLeastPurchasePriceAED();
            }
            else
            {
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one Vendor and Price Required',function (e) {
                }).set({title:"Can't Remove Vendor And Prices"})
            }
        })
    });
    function inputNumberAbs(currentPriceInput)
        {

            var id = currentPriceInput.id
            var input = document.getElementById(id);
            var val = input.value;
            val = val.replace(/^0+|[^\d.]/g, '');
            if(val.split('.').length>2)
            {
                val =val.replace(/\.+$/,"");
            }
            input.value = val;
            if(currentPriceInput.id == 'fixing_charge_amount' && sub == '2')
            {
                var value = currentPriceInput.value;
                if(value == '')
                {

                    if(value.legth != 0)
                    {
                        $msg = "Fixing Charge Amount is required";
                        showFixingChargeAmountError($msg);
                    }
                }
                else
                {
                    removeFixingChargeAmountError();
                }
            }
        }
</script>
@endsection
