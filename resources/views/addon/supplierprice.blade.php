<div class="col-xxl-12 col-lg-12 col-md-12" id="notKitSupplier">
    <div class="row">
        <div class="col-md-12 p-0">
            <div class="col-md-12 supplierWithoutKit p-0">
                <div class="row supplierWithoutKitApendHere">
                    <div class="col-xxl-5 col-lg-6 col-md-12">
                        <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                        <select name="supplierAndPrice[1][supplier_id][]" id="itemArr1" multiple="true" style="width: 100%;">
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
                    <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                        <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                        <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Addons Purchase Price In USD , 1 USD = 3.6725 AED" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                    </div>
                    <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_aed_1" >
                        <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                        <input  name="supplierAndPrice[1][addon_purchase_price]" id="addon_purchase_price_1" type="text" class="form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Addons Purchase Price In AED , 1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
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
                <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_usd_${index}">
                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                    <input name="supplierAndPrice[${index}][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_${index}" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror"  placeholder="Enter Addons Purchase Price In USD ,1 USD = 3.6725 AED" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(${index})">
                </div>
                <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_aed_${index}">
                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                    <input name="supplierAndPrice[${index}][addon_purchase_price]" id="addon_purchase_price_${index}" type="text" class="form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror"  placeholder="Enter Addons Purchase Price In USD ,1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(${index})">
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
        });
    }); 
</script>