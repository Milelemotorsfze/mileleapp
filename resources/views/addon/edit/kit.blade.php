<div class="col-xxl-12 col-lg-12 col-md-12 supplierAddForKit" id="kitSupplierIdToHideandshow">
    @include('addon.edit.items')
</div>
</br id="kitSupplierBrToHid1eandshow">
<div class="row" id="kitSupplierButtonToHideandshow">
    <div class="col-xxl-12 col-lg-12 col-md-12">
        <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info buttonForAddNewKitSupplier"><i class="fa fa-plus" aria-hidden="true"></i> Add Supplier</a>
    </div>
</div>
<input type="hidden" id="kitItemIndex" value="">

<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#kitSupplierDropdown1").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
        $("#kitSupplierDropdown1").select2
        ({
            maximumSelectionLength: 1,
        });
        $('#kitSupplierIdToHideandshow').hide();
        $('#kitSupplierBrToHideandshow').hide();
        $('#kitSupplierButtonToHideandshow').hide();
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
            disableDropdown();
        });
        $(document.body).on('select2:unselect', ".KitSupplierItems", function (e) {
            var index = $(this).attr('data-index');
            var supplier = $(this).attr('data-supplier');
            var data = e.params.data;
            KitItemAppendOption(index,supplier,data);
            enableDropdown();
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
// alert(indexNumber);
// alert(supplier);
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
                                                                <div class="col-xxl-3 col-lg-6 col-md-12">
                                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                                                                    <select name="kitSupplierAndPrice[${index}][supplier_id]" id="kitSupplierDropdown${index}" multiple="true"
                                                                    style="width: 100%;" class="kitSuppliers" data-index="${index}"  >
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
                                                                    placeholder="Enter Addons Purchase Price In AED , 1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  
                                                                    autocomplete="addon_purchase_price"
                                                                    autofocus onkeyup="calculateUSD(1)">
                                                                      <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-4 col-lg-3 col-md-3"  style="background-color: 	 #F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input readonly name="kitSupplierAndPrice[${index}][supplier_addon_purchase_price_in_usd]" id="Supplier${index}TotalPriceUSD"
                                                                    oninput="inputNumberAbs(this)" class="form-control purchase-price-USD widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" 
                                                                    placeholder="Enter Addons Purchase Price In USD , 1 USD = 3.6725 AED" value="{{ old('addon_purchase_price_in_usd') }}"
                                                                      autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>

                                                                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                                    <button class="btn_round removeKitSupplier" data-index="${index}">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
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
                                                        <div class="col-md-12 apendNewItemHere${index} p-0" id="kitItemRow">
                                                            <div class="row kitItemSubRow kitItemRowForSupplier${index} kititemdelete" id="row-supplier-${index}-item-1">
                                                                <div class="col-xxl-2 col-lg-6 col-md-12">
                                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                                                    <select class="form-control widthinput KitSupplierItems"  name="kitSupplierAndPrice[${index}][item][1][kit_item_id]"
                                                                    id="kitSupplier${index}Item1" multiple="true" style="width: 100%;" data-index="1" data-supplier="${index}">
                                                                        @foreach($kitItemDropdown as $kitItemDropdownData)
                                                                        <option value="{{$kitItemDropdownData->id}}">{{$kitItemDropdownData->addon_code}} ( {{$kitItemDropdownData->AddonName->name}} )</option>
                                                                        @endforeach
                                                                    </select>
                                                                        @error('supplier_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                        </span>
                                                                        @enderror
                                                                    </div>
                                                                <div class="col-xxl-1 col-lg-3 col-md-3"  >
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                                                    <input  name="kitSupplierAndPrice[${index}][item][1][quantity]" id="Supplier${index}Kit1Quantity" type="number" value="1" min="1"
                                                                     class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantity" placeholder="Enter Quantity"
                                                                     autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(${index},1)" 
                                                                     onchange="calculateOtherValuesbyQuantity(${index},1)" oninput="validity.valid||(value='1');">
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input  name="kitSupplierAndPrice[${index}][item][1][unit_price_in_aed]" id="Supplier${index}Kit1UnitPriceAED" oninput="inputNumberAbs(this)"
                                                                     class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror unit-price-AED"
                                                                      placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                                                      onkeyup="calculateOtherValuesbyUniTPriceAED(${index},1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                                                    <div class="input-group">
                                                                    <input  name="kitSupplierAndPrice[${index}][item][1][total_price_in_aed]" id="Supplier${index}Kit1TotalPriceAED" oninput="inputNumberAbs(this)"
                                                                     class="Supplier${index}TotalPriceInAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror total-price-AED"
                                                                     placeholder="Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                                                     onkeyup="calculateOtherValuesbyTotalPriceAED(${index},1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg-3 col-md-3"  style="background-color: 	#F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input  name="kitSupplierAndPrice[${index}][item][1][unit_price_in_usd]" id="Supplier${index}Kit1UnitPriceUSD" oninput="inputNumberAbs(this)"
                                                                     class="Supplier${index}TotalPriceInUSD form-control  widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror unit-price-USD"
                                                                      placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                                       onkeyup="calculateOtherValuesbyUnitPriceUSD(${index},1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="col-xxl-2 col-lg- col-md-3" style="background-color: 	#F8F8F8;">
                                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                                                    <div class="input-group">
                                                                    <input  name="kitSupplierAndPrice[${index}][item][1][total_price_in_usd]" id="Supplier${index}Kit1TotalPriceUSD" oninput="inputNumberAbs(this)"
                                                                    class="form-control widthinput total-price-USD @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                                                     placeholder="Enter Total Price In USD"  value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                                    onkeyup="calculateOtherValuesbyTotalPriceUSD(${index},1)">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                                    <button id="removeSupplier${index}Item1" class="btn_round removeKitItemForSupplier${index} remove-kit-items" onclick=del(this,${index}) disabled hidden>
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                                        <a id="addSupplier" style="float: right;" class="btn btn-sm btn-primary addItemForSupplier1" data-supplier="${index}" data-index="1"  onclick="addItemForSupplier(${index})"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</a>
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
                    $("#kitSupplier"+index+"Item1").attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
                    $("#kitSupplier"+index+"Item1").select2
                    ({
                        maximumSelectionLength: 1,
                    });
                    $('#kitSupplierDropdown'+index).html("");
                    $("#kitSupplierDropdown"+index).select2
                    ({
                        placeholder:"Choose Supplier....     Or     Type Here To Search.....",
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
        if(quantity > 1)
        {
            disableDropdown();
        }
        else
        {
            enableDropdown();
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
        // alert(unitPriceAED);
        // alert(totalPriceAED);
        // alert(unitPriceUSD);
        // alert(totalPriceUSD);
        // alert(supplier);
        // alert(kit);
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
        const values = Array.from(document.querySelectorAll('.Supplier'+supplier+'TotalPriceInUSD')).map(input => input.value)
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
                disableDropdown();
            }
            else
            {
                $("#purchase_price").val('');
                enableDropdown();
            }
        }
    }
</script>
