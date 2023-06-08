<div class="row" id="notKitSupplier">
    <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
        <div id="London" class="tabcontent">
            <div class="row">
                <div class="card-body">
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-md-12 p-0">
                                <div class="col-md-12 supplierWithoutKit p-0">
                                    <div class="row supplierWithoutKitApendHere">
                                        <div class="col-xxl-5 col-lg-6 col-md-12">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                                            <select name="supplierAndPrice[1][supplier_id][]" id="itemArr1" multiple="true" style="width: 100%;" onchange="validationOnKeyUp(this)">
                                                @foreach($suppliers as $supplier)
                                                    <option class="{{$supplier->id}}" value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                                                @endforeach
                                            </select>                           
                                            @error('supplier_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="supplierError" class="invalid-feedback"></span>
                                        </div>                                        
                                        <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_aed_1" >
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13">Purchase Price In AED</label>
                                            <input  name="supplierAndPrice[1][addon_purchase_price_in_aed]" id="addon_purchase_price_1" type="number" class="leastPurchasePriceAEDKIT notKitSupplierPurchasePrice form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Addons Purchase Price In AED , 1 USD = 3.6725 AED" value="{{ old('supplierAndPrice[1][addon_purchase_price_in_aed]') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                            <span id="purchasePriceAEDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                            <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1" type="number" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Addons Purchase Price In USD , 1 USD = 3.6725 AED" value="{{ old('supplierAndPrice[1][addon_purchase_price_in_usd]') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                            <span id="purchasePriceUSDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                            <button class="btn_round  removeButtonSupplierWithoutKit" disabled hidden>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info addSupplierAndPriceWithoutKit"><i class="fa fa-plus" aria-hidden="true"></i> Add Supplier</a> 
                            </div>
                        </div>
                    </div>
                    </br>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#kitSupplier1Item1").attr("data-placeholder","Choose Items....     Or     Type Here To Search....");
        $("#kitSupplier1Item1").select2
        ({
            maximumSelectionLength: 1,
        });
    });

    // $("body").on("click",".addItemForSupplier1", function ()
    // { 
       
    // }); 
    function del(currentRow, supplier)
    {
        currentRow.closest(".kititemdelete").remove();
        calculateTotalPriceInAED(supplier);
        calculateTotalPriceInUSD(supplier);
    }
    function addItemForSupplier(supplier)
    {
        var index = $(".apendNewItemHere"+supplier).find(".kitItemRowForSupplier"+supplier).length + 1; 
        $(".apendNewItemHere"+supplier).append(`
            <div class="row kitItemRowForSupplier${supplier} kititemdelete">
                <div class="col-xxl-2 col-lg-6 col-md-12">
                    <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                    <select name="kitSupplierAndPrice[${supplier}][item][${index}][kit_item_id]" id="kitSupplier${supplier}Item${index}" multiple="true" style="width: 100%;">
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
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][quantity]" id="Supplier${supplier}Kit${index}Quantity" type="number" value="1" min="1" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Quantity" autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(${supplier},${index})">
                </div>
                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][unit_price_in_aed]" id="Supplier${supplier}Kit${index}UnitPriceAED" type="number" class="form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyUniTPriceAED(${supplier},${index})">
                </div>
                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][total_price_in_aed]" id="Supplier${supplier}Kit${index}TotalPriceAED" type="number" class="Supplier${supplier}TotalPriceInAED form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyTotalPriceAED(${supplier},${index})">
                </div>
                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][unit_price_in_usd]" id="Supplier${supplier}Kit${index}UnitPriceUSD" type="number" class="Supplier${supplier}TotalPriceInUSD form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyUnitPriceUSD(${supplier},${index})">
                </div>
                <div class="col-xxl-2 col-lg- col-md-3" id="div_price_in_usd_1" style="background-color: #F8F8F8;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][total_price_in_usd]" id="Supplier${supplier}Kit${index}TotalPriceUSD" type="number" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyTotalPriceUSD(${supplier},${index})">
                </div>
                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                    <button class="btn_round removeKitItemForSupplier${supplier}" onclick=del(this,${supplier}) disabled>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            `); 
        $(".apendNewItemHere"+supplier).find(".removeKitItemForSupplier"+supplier+":not(:first)").prop("disabled", false); $(".apendNewItemHere"+supplier).find(".removeKitItemForSupplier"+index).first().prop("disabled", true); 
        $("#kitSupplier"+supplier+"Item"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
        $("#kitSupplier"+supplier+"Item"+index).select2
        ({
            maximumSelectionLength: 1,
        });  
    }
</script>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#itemArr1").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
        $("#itemArr1").select2
        ({
            maximumSelectionLength: 1,
        });
    });
    $("body").on("click",".addSupplierAndPriceWithoutKit", function ()
    { 
        var index = $(".supplierWithoutKit").find(".supplierWithoutKitApendHere").length + 1; 
        $(".supplierWithoutKit").append(`
            <div class="row supplierWithoutKitApendHere">
                <div class="col-xxl-5 col-lg-6 col-md-12">
                    <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                    <select class="addonClass"  id="supplierArray${index}" name="supplierAndPrice[${index}][supplier_id][]" multiple="true" style="width: 100%;" onchange="showAndHideSupplierDropdownOptions(${index})">
                        @foreach($suppliers as $supplier)
                            <option class="{{$supplier->id}}" value="{{$supplier->id}}">{{$supplier->supplier}}</option>
                        @endforeach
                    </select>
                    @error('is_primary_payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_aed_${index}">
                    <label for="choices-single-default" class="form-label font-size-13">Purchase Price In AED</label>
                    <input name="supplierAndPrice[${index}][addon_purchase_price_in_aed]" id="addon_purchase_price_${index}" type="number" class="leastPurchasePriceAEDKIT notKitSupplierPurchasePrice form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror"  placeholder="Enter Addons Purchase Price In USD ,1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(${index})">
                </div>
                <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_usd_${index}">
                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                    <input name="supplierAndPrice[${index}][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_${index}" type="number" class=" form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror"  placeholder="Enter Addons Purchase Price In USD ,1 USD = 3.6725 AED" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(${index})">
                </div>
                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                    <button class="btn_round removeButtonSupplierWithoutKit" disabled>
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            `); 
            $(".supplierWithoutKit").find(".removeButtonSupplierWithoutKit:not(:first)").prop("disabled", false); $(".supplierWithoutKit").find(".removeButtonSupplierWithoutKit").first().prop("disabled", true); 
            $("#supplierArray"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
            $("#supplierArray"+index).select2
            ({
                // maximumSelectionLength: 1,
            });
        //===== delete the form fieed row
        $("body").on("click", ".removeButtonSupplierWithoutKit", function () 
        {
            $(this).closest(".supplierWithoutKitApendHere").remove();
            setLeastAEDPrice();
        });
        // function delNotKitSupplier(currentRow)
        // {
        //     currentRow.closest(".supplierWithoutKitApendHere").remove();
        //     setLeastPurchasePriceAEDKitSupplier();
        // }
        // function setLeastPurchasePriceAEDKitSupplier()
        // {
        //     const values = Array.from(document.querySelectorAll('.leastPurchasePriceAEDKIT')).map(input => input.value);
        //     alert(values);
        //     var arrayOfNumbers = [];
        //         values.forEach(v => {
        //             if(v != '')
        //             {
        //                 arrayOfNumbers .push(v);
        //             }
        //         });
        //         var arrayOfNumbers = arrayOfNumbers.map(Number);
        //         const min = Math.min(...arrayOfNumbers);
        //         $("#purchase_price").val(min);
        // }
    }); 
</script>