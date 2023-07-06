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
                                        <div class="col-xxl-3 col-lg-6 col-md-12">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13" >Choose Suppliers</label>
                                            <select name="kitSupplierAndPrice[1][supplier_id]"  class="kitSuppliers" data-index="1"  id="kitSupplierDropdown1"
                                                    multiple="true" style="width: 100%;">
                                                @foreach($suppliers as $supplier)
                                                    <option class="{{$supplier->id}}" value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="kitSupplierDropdown1Error" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                            <div class="input-group">
                                            <input readonly name="kitSupplierAndPrice[1][supplier_addon_purchase_price_in_aed]" id="Supplier1TotalPriceAED" oninput="inputNumberAbs(this)" class="leastPurchasePriceAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror" placeholder="" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>

                                        </div>
                                        <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                            <div class="input-group">
                                            <input readonly name="kitSupplierAndPrice[1][supplier_addon_purchase_price_in_usd]" id="Supplier1TotalPriceUSD" oninput="inputNumberAbs(this)" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>

                                        </div>
                                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                            <button class="btn_round removeKitSupplier" disabled hidden>
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
                                <div class="col-md-12 apendNewItemHere1 p-0">
                                    <div class="row kitItemRowForSupplier1 kititemdelete" id="row-supplier-1-item-1">
                                        <div class="col-xxl-2 col-lg-6 col-md-12">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                            <select class="form-control widthinput KitSupplierItems" name="kitSupplierAndPrice[1][item][1][kit_item_id]"
                                                    id="kitSupplier1Item1" multiple="true" style="width: 100%;" data-index="1" data-supplier="1">
                                                @foreach($kitItemDropdown as $kitItemDropdownData)
                                                    <option value="{{$kitItemDropdownData->id}}">{{$kitItemDropdownData->addon_code}} ( {{$kitItemDropdownData->AddonName->name}} )</option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="kitSupplier1Item1Error" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-1 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                            <input  name="kitSupplierAndPrice[1][item][1][quantity]" id="Supplier1Kit1Quantity" 
                                            class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Quantity" 
                                            type="number" value="1" min="1"  autocomplete="addon_purchase_price_in_usd" autofocus 
                                            onkeyup="calculateOtherValuesbyQuantity(1,1)" onchange="calculateOtherValuesbyQuantity(1,1)" oninput="validity.valid||(value='1');">
                                            <!-- {{ old('addon_purchase_price_in_usd') }} -->
                                            <span id="Supplier1Kit1QuantityError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                            <div class="input-group">
                                            <input  name="kitSupplierAndPrice[1][item][1][unit_price_in_aed]" id="Supplier1Kit1UnitPriceAED" oninput="inputNumberAbs(this)"
                                              data="unitPriceAED"  class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror"
                                              placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                              onkeyup="calculateOtherValuesbyUniTPriceAED(1,1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>
                                            <span id="Supplier1Kit1UnitPriceAEDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                            <div class="input-group">
                                            <input  name="kitSupplierAndPrice[1][item][1][total_price_in_aed]" id="Supplier1Kit1TotalPriceAED" oninput="inputNumberAbs(this)"
                                            class="Supplier1TotalPriceInAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror"
                                            placeholder="Enter Total Price In AED" value=""  autocomplete="addon_purchase_price" autofocus
                                            onkeyup="calculateOtherValuesbyTotalPriceAED(1,1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>
                                            <span id="Supplier1Kit1TotalPriceAEDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                            <div class="input-group">
                                            <input  name="kitSupplierAndPrice[1][item][1][unit_price_in_usd]" id="Supplier1Kit1UnitPriceUSD" oninput="inputNumberAbs(this)"
                                            class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Unit Price In USD"
                                            value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyUnitPriceUSD(1,1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                                            <span id="Supplier1Kit1UnitPriceUSDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-2 col-lg- col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                                            <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                            <span class="error">* </span>
                                            <div class="input-group">
                                            <input  name="kitSupplierAndPrice[1][item][1][total_price_in_usd]" id="Supplier1Kit1TotalPriceUSD" oninput="inputNumberAbs(this)"
                                            class="Supplier1TotalPriceInUSD form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                            placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus
                                                <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                                            <span id="Supplier1Kit1TotalPriceUSDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                            <button  class="btn_round removeKitItemForSupplier1 "  hidden disabled>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                <a id="addSupplier" style="float: right;" class="btn btn-sm btn-primary addItemForSupplier1" onclick="addItemForSupplier(1)"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</a>
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
            disableDropdown();
        });
        $(document.body).on('select2:unselect', ".kitSuppliers", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
            enableDropdown();
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
            var indexNumber = $(this).attr('data-index');
            var supplierTotalIndex = $('#supplierIndex').val();

            $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                var id = (this.value);
                var text = (this.text);
                addOption(id,text)
            });

            $(this).closest('#row-'+indexNumber).remove();


            {{--for(var supplier=1;supplier<=3;supplier++) {--}}
            {{--    var eachItemTotalIndex =  $(".apendNewItemHere"+supplier).find(".kitItemRowForSupplier"+supplier).length;--}}

            {{--    for(var item=1;item<=3;item++)--}}
            {{--    {--}}
            {{--        $(this).find('.KitSupplierItems').attr('name', 'kitSupplierAndPrice['+ supplier +'][item]['+ item +'][kit_item_id]');--}}
            {{--        $(this).find('.KitSupplierItems').attr('id', 'kitSupplier'+ supplier +'Item'+item);--}}
            {{--        $(this).find('.quantity').attr('name', 'kitSupplierAndPrice['+ supplier +'][item]['+ item +'][quantity]');--}}
            {{--        $(this).find('.quantity').attr('id', 'Supplier'+ supplier +'Kit'+ item +'Quantity');--}}
            {{--        $(this).find('.quantity').attr('onkeyup', 'calculateOtherValuesbyQuantity('+ supplier +','+ item +')');--}}
            {{--        $(this).find('.quantity').attr('onchange', 'calculateOtherValuesbyQuantity('+ supplier +','+ item +')');--}}


            {{--        $(this).find('.unit-price-AED').attr('name', 'kitSupplierAndPrice['+ supplier +'][item]['+ item +'][unit_price_in_aed]');--}}
            {{--        $(this).find('.unit-price-AED').attr('id', 'Supplier'+ supplier +'Kit'+ item +'UnitPriceAED');--}}
            {{--        $(this).find('.unit-price-AED').attr('onkeyup', 'calculateOtherValuesbyUniTPriceAED('+ supplier +','+ item +')');--}}

            {{--        $(this).find('.total-price-AED').attr('id', 'Supplier'+ supplier +'Kit'+ item +'TotalPriceAED');--}}
            {{--        $(this).find('.total-price-AED').attr('name', 'kitSupplierAndPrice['+ supplier +'][item]['+ item +'][total_price_in_aed]');--}}
            {{--        $(this).find('.total-price-AED').attr('onkeyup', 'calculateOtherValuesbyTotalPriceAED('+ supplier +','+ item +')');--}}
            {{--        $(this).find('.total-price-AED').attr('class', 'Supplier'+ supplier +'TotalPriceInAED form-control widthinput @error('addon_purchase_price')--}}
            {{--            is-invalid @enderror total-price-AED');--}}

            {{--        $(this).find('.unit-price-USD').attr('name', 'kitSupplierAndPrice['+ supplier +'][item]['+ item +'][unit_price_in_usd]');--}}
            {{--        $(this).find('.unit-price-USD').attr('id', 'Supplier'+ supplier +'Kit1UnitPriceUSD');--}}
            {{--        $(this).find('.unit-price-USD').attr('onkeyup', 'calculateOtherValuesbyUnitPriceUSD('+ supplier +','+ item +')');--}}
            {{--        $(this).find('.unit-price-USD').attr('class', 'Supplier'+ supplier +'TotalPriceInUSD form-control widthinput @error('addon_purchase_price_in_usd')--}}
            {{--            is-invalid @enderror unit-price-USD');--}}

            {{--        $(this).find('.total-price-USD').attr('name', 'kitSupplierAndPrice['+ supplier +'][item]['+ item +'][total_price_in_usd]');--}}
            {{--        $(this).find('.total-price-USD').attr('id', 'Supplier'+ supplier +'Kit'+ item +'TotalPriceUSD');--}}
            {{--        $(this).find('.total-price-USD').attr('onkeyup', 'calculateOtherValuesbyTotalPriceUSD('+ supplier +','+ item +')');--}}
            {{--    }--}}

            {{--}--}}
            $('.addSupplierForKitRow').each(function(i){

                var index = +i + +1;

                $(this).attr('id','row-'+ index);
                $(this).find('.kitSuppliers').attr('data-index', index);
                $(this).find('.kitSuppliers').attr('id','kitSupplierDropdown'+ index);
                $(this).find('.kitSuppliers').attr('name','kitSupplierAndPrice['+ index +'][supplier_id]');
                $(this).find('.leastPurchasePriceAED').attr('name','kitSupplierAndPrice['+ index +'][supplier_addon_purchase_price_in_aed]');
                $(this).find('.leastPurchasePriceAED').attr('id','Supplier'+ index +'TotalPriceAED');
                $(this).find('.purchase-price-USD').attr('name','kitSupplierAndPrice['+ index +'][supplier_addon_purchase_price_in_usd]');
                $(this).find('.purchase-price-USD').attr('id','Supplier'+ index +'TotalPriceUSD');
                $(this).find('.removeKitSupplier').attr('data-index', index);
                $(this).find('#addSupplier').attr('onclick', 'addItemForSupplier('+ index +')');
                $(this).find('.kititemdelete').attr('id','row-supplier-'+ index +'-item-1');
                $(this).find('#kitItemRow').attr('class','col-md-12 p-0 apendNewItemHere'+index);
                $(this).find('#kitItemSubRow').attr('class','row kititemdelete kitItemRowForSupplier'+index);

                $(this).find('.KitSupplierItems').attr('name', 'kitSupplierAndPrice['+ index +'][item][1][kit_item_id]');
                $(this).find('.KitSupplierItems').attr('id', 'kitSupplier'+ index +'Item1');

                $(this).find('.quantity').attr('name', 'kitSupplierAndPrice['+ index +'][item][1][quantity]');
                $(this).find('.quantity').attr('id', 'Supplier'+ index +'Kit1Quantity');
                $(this).find('.quantity').attr('onkeyup', 'calculateOtherValuesbyQuantity('+ index +',1)');
                $(this).find('.quantity').attr('onchange', 'calculateOtherValuesbyQuantity('+ index +',1)');


                $(this).find('.unit-price-AED').attr('name', 'kitSupplierAndPrice['+ index +'][item][1][unit_price_in_aed]');
                $(this).find('.unit-price-AED').attr('id', 'Supplier'+ index +'Kit1UnitPriceAED');
                $(this).find('.unit-price-AED').attr('onkeyup', 'calculateOtherValuesbyUniTPriceAED('+ index +',1)');

                $(this).find('.total-price-AED').attr('id', 'Supplier'+ index +'Kit1TotalPriceAED');
                $(this).find('.total-price-AED').attr('name', 'kitSupplierAndPrice['+ index +'][item][1][total_price_in_aed]');
                $(this).find('.total-price-AED').attr('onkeyup', 'calculateOtherValuesbyTotalPriceAED('+ index +',1)');
                $(this).find('.total-price-AED').attr('class', 'Supplier'+ index +'TotalPriceInAED form-control widthinput @error('addon_purchase_price')
                    is-invalid @enderror total-price-AED');

                $(this).find('.unit-price-USD').attr('name', 'kitSupplierAndPrice['+ index +'][item][1][unit_price_in_usd]');
                $(this).find('.unit-price-USD').attr('id', 'Supplier'+ index +'Kit1UnitPriceUSD');
                $(this).find('.unit-price-USD').attr('onkeyup', 'calculateOtherValuesbyUnitPriceUSD('+ index +',1)');
                $(this).find('.unit-price-USD').attr('class', 'Supplier'+ index +'TotalPriceInUSD form-control widthinput @error('addon_purchase_price_in_usd')
                    is-invalid @enderror unit-price-USD');

                $(this).find('.remove-kit-items').attr('class', 'btn_round removeKitItemForSupplier'+index+' remove-kit-items');
                // for(var j=2; j<=4; j++) {
                    $(this).find('.quantity').attr('name', 'kitSupplierAndPrice['+ index +'][item][2][quantity]');
                    $(this).find('.quantity').attr('id', 'Supplier'+ index +'Kit2Quantity');
                    $(this).find('.quantity').attr('onkeyup', 'calculateOtherValuesbyQuantity('+ index +',2)');
                    $(this).find('.quantity').attr('onchange', 'calculateOtherValuesbyQuantity('+ index +',2)');

                // }

                // $(this).find('.unit-price-AED').attr('name', 'kitSupplierAndPrice['+ index +'][item][2][unit_price_in_aed]');
                // $(this).find('.unit-price-AED').attr('id', 'Supplier'+ index +'Kit2UnitPriceAED');
                // $(this).find('.unit-price-AED').attr('onkeyup', 'calculateOtherValuesbyUniTPriceAED('+ index +',2)');
                //
                // $(this).find('.total-price-AED').attr('id', 'Supplier'+ index +'Kit2TotalPriceAED');
                // $(this).find('.total-price-AED').attr('name', 'kitSupplierAndPrice['+ index +'][item][2][total_price_in_aed]');
                // $(this).find('.total-price-AED').attr('onkeyup', 'calculateOtherValuesbyTotalPriceAED('+ index +',2)');

                // $(this).find('button').attr('id','remove-'+ index);
                $('#kitSupplierDropdown'+index).select2
                ({
                    placeholder:"Choose Suppliers....     Or     Type Here To Search....",
                    allowClear: true,
                    minimumResultsForSearch: -1,
                });


                $('.kitItemRowForSupplier'+index).each(function(j){
                    // var count = 6;
                    // alert("kitIndex");
                    // alert(index);
                    // alert("ok");

                    // var j = +j + +1;
                    // alert(j);
                    // alert(index);
                    // $(this).find('.quantity').attr('name','test'+kitIndex);
                    // $(this).find('.quantity').attr('name','testfrom'+j);
                    $(this).find('.quantity').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+j+'][quantity]');
                    $(this).find('.quantity').attr('id', 'Supplier'+ index +'Kit'+j+'Quantity');
                    $(this).find('.quantity').attr('onkeyup', 'calculateOtherValuesbyQuantity('+ index +','+j+')');

                    $(this).find('.unit-price-AED').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+j+'][unit_price_in_aed]');
                    $(this).find('.unit-price-AED').attr('id', 'Supplier'+ index +'Kit'+j+'UnitPriceAED');
                    $(this).find('.unit-price-AED').attr('onkeyup', 'calculateOtherValuesbyUniTPriceAED('+ index +','+j+')');

                    $(this).find('.total-price-AED').attr('id', 'Supplier'+ index +'Kit'+j+'TotalPriceAED');
                    $(this).find('.total-price-AED').attr('name', 'kitSupplierAndPrice['+ index +'][item]['+j+'][total_price_in_aed]');
                    $(this).find('.total-price-AED').attr('onkeyup', 'calculateOtherValuesbyTotalPriceAED('+ index +','+j+')');

                })
            });
            setLeastPurchasePriceAED();
        })
    });

    function del(currentRow, supplier)
    {
        currentRow.closest(".kititemdelete").remove();
        calculateTotalPriceInAED(supplier);
        calculateTotalPriceInUSD(supplier);
    }
    function addItemForSupplier(supplier)
    {
        var index = $(".apendNewItemHere"+supplier).find(".kitItemRowForSupplier"+supplier).length + 1;
        $('#kitItemIndex').val(index);
        var selectedAddons = [];
        for(let i=1; i<index; i++)
        {
            var eachSelectedAddon = $('#kitSupplier'+supplier+'Item'+i).val();
            if(eachSelectedAddon) {
                selectedAddons.push(eachSelectedAddon);
            }
        }
        $.ajax({
            url:"{{url('getKitItemsForAddon')}}",
            type: "POST",
            data:
                {
                    filteredArray: selectedAddons,
                    _token: '{{csrf_token()}}'
                },
            dataType : 'json',
            success: function(data) {
                myarray = data;
                var size = myarray.length;
                if (size >= 1) {
                    $(".apendNewItemHere" + supplier).append(`
                        <div class="row kitItemRowForSupplier${supplier} kititemdelete" id="row-supplier-${supplier}-item-${index}">
                            <div class="col-xxl-2 col-lg-6 col-md-12">
                                <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                <select name="kitSupplierAndPrice[${supplier}][item][${index}][kit_item_id]" id="kitSupplier${supplier}Item${index}" multiple="true"
                                 style="width: 100%;" class="KitSupplierItems" data-index="${index}" data-supplier="${supplier}">
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
                                <div class="col-xxl-1 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                    <label for="choices-single-default" class="form-label font-size-13 ">Quantity</label>
                                    <input name="kitSupplierAndPrice[${supplier}][item][${index}][quantity]" id="Supplier${supplier}Kit${index}Quantity"
                                     type="number" value="1" min="1" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror quantity"
                                     placeholder="Enter Quantity" autocomplete="addon_purchase_price_in_usd" autofocus
                                     onkeyup="calculateOtherValuesbyQuantity(${supplier},${index})" onchange="calculateOtherValuesbyQuantity(${supplier},${index})" oninput="validity.valid||(value='1');">
                                </div>
                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                <div class="input-group">
                                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][unit_price_in_aed]" id="Supplier${supplier}Kit${index}UnitPriceAED"
                                    oninput="inputNumberAbs(this)" class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror unit-price-AED"
                                    placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus
                                    onkeyup="calculateOtherValuesbyUniTPriceAED(${supplier},${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                <div class="input-group">
                                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][total_price_in_aed]" id="Supplier${supplier}Kit${index}TotalPriceAED"
                                    oninput="inputNumberAbs(this)" class="Supplier${supplier}TotalPriceInAED total-price-AED form-control widthinput @error('addon_purchase_price')
                                    is-invalid @enderror" placeholder="Enter Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price"
                                    autofocus onkeyup="calculateOtherValuesbyTotalPriceAED(${supplier},${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                                <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                <div class="input-group">
                                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][unit_price_in_usd]" id="Supplier${supplier}Kit${index}UnitPriceUSD"
                                     oninput="inputNumberAbs(this)" class="Supplier${supplier}TotalPriceInUSD form-control widthinput @error('addon_purchase_price_in_usd')
                                        is-invalid @enderror unit-price-USD" placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"
                                         autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyUnitPriceUSD(${supplier},${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-2 col-lg- col-md-3" id="div_price_in_usd_1" style="background-color: #F8F8F8;">
                                <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                <div class="input-group">
                                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][total_price_in_usd]" id="Supplier${supplier}Kit${index}TotalPriceUSD"
                                     oninput="inputNumberAbs(this)" class="form-control widthinput total-price-USD @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                      placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd"
                                       autofocus onkeyup="calculateOtherValuesbyTotalPriceUSD(${supplier},${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <button class="btn_round removeKitItemForSupplier${supplier} removeKitItem" data-index="${index}" data-supplier="${supplier}" >

                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `);

                    let addonDropdownData   = [];
                    $.each(data,function(key,value)
                    {
                        addonDropdownData.push
                        ({

                            id: value.id,
                            text: value.addon_code +' ('+value.addon_name.name +')'
                        });
                    });
                    $('#kitSupplier'+supplier+'Item'+index).html("");
                    $('#kitSupplier'+supplier+'Item'+index).select2
                    ({
                        placeholder:"Choose Items....     Or     Type Here To Search....",
                        allowClear: true,
                        data: addonDropdownData,
                        maximumSelectionLength: 1,
                    });
                }
            }
        });

    }
</script>
