<div class="col-xxl-12 col-lg-12 col-md-12 supplierAddForKit"   id="kitSupplier">
    @include('addon.items')
</div>
</br id="kitSupplierBr">
<div class="row" id="kitSupplierButton">
    <div class="col-xxl-12 col-lg-12 col-md-12">
        <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info buttonForAddNewKitSupplier"><i class="fa fa-plus" aria-hidden="true"></i> Add Supplier1</a> 
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#kitSupplierDropdown1").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
        $("#kitSupplierDropdown1").select2
        ({
            maximumSelectionLength: 1,
        });
    });
    $("body").on("click",".buttonForAddNewKitSupplier", function ()
    { 
        var index = $(".supplierAddForKit").find(".addSupplierForKitRow").length + 1;
        $(".supplierAddForKit").append(`
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
                                                <div class="col-xxl-5 col-lg-6 col-md-12">
                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                                                    <select name="supplierAndPrice[1][supplier_id][]" id="kitSupplierDropdown${index}" multiple="true" style="width: 100%;">
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
                                                    <button class="btn_round removeKitSupplier" disabled>
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
                                        <div class="col-md-12 apendNewItemHere${index} p-0">
                                            <div class="row kitItemRowForSupplier${index} kititemdelete">
                                                <div class="col-xxl-2 col-lg-6 col-md-12">
                                                    <label for="choices-single-default" class="form-label font-size-13">Choose Items</label>
                                                    <select class="form-control form-control-sm" name="supplierAndPrice[1][supplier_id][]" id="kitSupplier${index}Item1" multiple="true" style="width: 100%;">
                                                        @foreach($kitItemDropdown as $kitItemDropdownData)
                                                            <option value="{{$kitItemDropdownData->id}}">{{$kitItemDropdownData->addon_code}}</option>
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
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Quantity" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" >
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price]" id="addon_purchase_price_1" type="text" class="form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" >
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price]" id="addon_purchase_price_1" type="text" class="form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg- col-md-3" id="div_price_in_usd_1" >
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                </div>
                                                <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                                    <button  class="btn_round removeKitItemForSupplier${index} remove-item-for-supplier" disabled hidden>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <a id="addSupplier" style="float: right;" class="btn btn-sm btn-primary addItemForSupplier1" onclick="addItemForSupplier(${index})"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</a> 
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
        $(".apendNewItemHere"+index).find(".removeKitItemForSupplier"+index+":not(:first)").prop("disabled", false); $(".supplierAddForKit").find(".removeKitItemForSupplier"+index).first().prop("disabled", true); 
        $(".supplierAddForKit").find(".removeKitSupplier:not(:first)").prop("disabled", false); $(".supplierAddForKit").find(".removeKitSupplier").first().prop("disabled", true); 
        $("#kitSupplier"+index+"Item1").attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
        $("#kitSupplier"+index+"Item1").select2
        ({
            // maximumSelectionLength: 1,
        });
        $("#kitSupplierDropdown"+index).attr("data-placeholder","Choose Supplier....     Or     Type Here To Search....");
        $("#kitSupplierDropdown"+index).select2
        ({
            // maximumSelectionLength: 1,
        });
    }); 
    $("body").on("click", ".removeKitItemForSupplier", function () 
    {
        $(this).closest(".kititemdelete").remove();
    });
    $("body").on("click", ".removeKitSupplier", function () 
    {
        $(this).closest(".addSupplierForKitRow").remove();
    });
    function calculateUSD(i)
    {
        var aed = $("#addon_purchase_price_"+i).val();
        var usd = aed / 3.6725;
        var usd = usd.toFixed(4);
        if(usd == 0)
        {
            document.getElementById('addon_purchase_price_in_usd_'+i).value = "";
        }
        else
        {
            document.getElementById('addon_purchase_price_in_usd_'+i).value = usd;
        }
    }
    // function removeKitItemForSupplier(supplier)
    // {
    //     alert('kok');
    //     // $(this).closest(".kitItemRowForSupplier"+supplier).remove();
    // }
    
</script>
