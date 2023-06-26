<div class="row addSupplierForKitRow">
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
                                            <select name="kitSupplierAndPrice[1][supplier_id] kitSuppliers"  data-index="1"  id="kitSupplierDropdown1"
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
                                            <input readonly name="kitSupplierAndPrice[1][supplier_addon_purchase_price_in_aed]" id="Supplier1TotalPriceAED" type="number" min="0" step="any" class="leastPurchasePriceAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror" placeholder="" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>

                                        </div>
                                        <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                            <div class="input-group">
                                            <input readonly name="kitSupplierAndPrice[1][supplier_addon_purchase_price_in_usd]" id="Supplier1TotalPriceUSD" type="number" min="0" step="any" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
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
                                    <div class="row kitItemRowForSupplier1 kititemdelete">
                                        <div class="col-xxl-2 col-lg-6 col-md-12">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                            <select class="form-control widthinput" name="kitSupplierAndPrice[1][item][1][kit_item_id]" id="kitSupplier1Item1" multiple="true" style="width: 100%;">
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
                                            <input  name="kitSupplierAndPrice[1][item][1][quantity]" id="Supplier1Kit1Quantity" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Quantity" type="number" value="1" min="1"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(1,1)">
                                            <!-- {{ old('addon_purchase_price_in_usd') }} -->
                                            <span id="Supplier1Kit1QuantityError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                            <div class="input-group">
                                            <input  name="kitSupplierAndPrice[1][item][1][unit_price_in_aed]" id="Supplier1Kit1UnitPriceAED" type="number" min="0" step="any"  data="unitPriceAED"  class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyUniTPriceAED(1,1)">
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
                                            <input  name="kitSupplierAndPrice[1][item][1][total_price_in_aed]" id="Supplier1Kit1TotalPriceAED" type="number" min="0" step="any" class="Supplier1TotalPriceInAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Total Price In AED" value=""  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyTotalPriceAED(1,1)">
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
                                            <input  name="kitSupplierAndPrice[1][item][1][unit_price_in_usd]" id="Supplier1Kit1UnitPriceUSD" type="number" min="0" step="any" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyUnitPriceUSD(1,1)">
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
                                            <input  name="kitSupplierAndPrice[1][item][1][total_price_in_usd]" id="Supplier1Kit1TotalPriceUSD" type="number" min="0" step="any" class="Supplier1TotalPriceInUSD form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyTotalPriceUSD(1,1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                                            <span id="Supplier1Kit1TotalPriceUSDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                            <button  class="btn_round removeKitItemForSupplier1" onclick=del(this,1) hidden disabled>
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
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][quantity]" id="Supplier${supplier}Kit${index}Quantity" type="number" value="1" min="1" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Quantity" autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(${supplier},${index})">
                </div>
                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                    <div class="input-group">
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][unit_price_in_aed]" id="Supplier${supplier}Kit${index}UnitPriceAED" type="number" min="0" step="any" class="form-control widthinput @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyUniTPriceAED(${supplier},${index})">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>
                </div>
                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                    <div class="input-group">
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][total_price_in_aed]" id="Supplier${supplier}Kit${index}TotalPriceAED" type="number" min="0" step="any" class="Supplier${supplier}TotalPriceInAED form-control widthinput @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyTotalPriceAED(${supplier},${index})">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>
                                                </div>
                </div>
                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                    <div class="input-group">
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][unit_price_in_usd]" id="Supplier${supplier}Kit${index}UnitPriceUSD" type="number" min="0" step="any" class="Supplier${supplier}TotalPriceInUSD form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyUnitPriceUSD(${supplier},${index})">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                </div>
                <div class="col-xxl-2 col-lg- col-md-3" id="div_price_in_usd_1" style="background-color: #F8F8F8;">
                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                    <div class="input-group">
                    <input  name="kitSupplierAndPrice[${supplier}][item][${index}][total_price_in_usd]" id="Supplier${supplier}Kit${index}TotalPriceUSD" type="number" min="0" step="any" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyTotalPriceUSD(${supplier},${index})">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
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
