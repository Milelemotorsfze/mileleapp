<div class="col-xxl-12 col-lg-12 col-md-12 supplierAddForKit" id="kitSupplierIdToHideandshow">
    @include('addon.items')
</div>
</br id="kitSupplierBrToHideandshow">
<div class="row" id="kitSupplierButtonToHideandshow">
    <div class="col-xxl-12 col-lg-12 col-md-12">
        <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info buttonForAddNewKitSupplier"><i class="fa fa-plus" aria-hidden="true"></i> Add Supplier</a> 
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
        $('#kitSupplierIdToHideandshow').hide();
        $('#kitSupplierBrToHideandshow').hide();
        $('#kitSupplierButtonToHideandshow').hide();
        $(".leastPurchasePriceAED").on("keyup change", function(e) {
            setLeastPurchasePriceAED();
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
                                                <div class="col-xxl-3 col-lg-6 col-md-12">
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
                                                <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In AED</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price]" id="Supplier${index}TotalPriceAED" type="text" class="leastPurchasePriceAED form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Addons Purchase Price In AED , 1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                                </div>
                                                <div class="col-xxl-4 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	 #F8F8F8;">
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="Supplier${index}TotalPriceUSD" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Addons Purchase Price In USD , 1 USD = 3.6725 AED" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
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
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="Supplier${index}Kit1Quantity" type="number" value="1" min="1" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Quantity" autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyQuantity(${index},1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In AED</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price]" id="Supplier${index}Kit1UnitPriceAED" type="text" class="form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Enter Unit Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyUniTPriceAED(${index},1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_aed_1" style="background-color: 	#F0F0F0;">
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In AED</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price]" id="Supplier${index}Kit1TotalPriceAED" type="text" class="Supplier${index}TotalPriceInAED form-control form-control-sm @error('addon_purchase_price') is-invalid @enderror" placeholder="Total Price In AED" value="{{ old('addon_purchase_price') }}"  autocomplete="addon_purchase_price" autofocus onkeyup="calculateOtherValuesbyTotalPriceAED(${index},1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg-3 col-md-3" id="div_price_in_usd_1" style="background-color: 	#F8F8F8;">
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Unit Price In USD</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="Supplier${index}Kit1UnitPriceUSD" type="text" class="Supplier${index}TotalPriceInUSD form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Unit Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyUnitPriceUSD(${index},1)">
                                                </div>
                                                <div class="col-xxl-2 col-lg- col-md-3" id="div_price_in_usd_1" style="background-color: 	#F8F8F8;">
                                                    <label for="choices-single-default" class="form-label font-size-13 ">Total Price In USD</label>
                                                    <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="Supplier${index}Kit1TotalPriceUSD" type="text" class="form-control form-control-sm @error('addon_purchase_price_in_usd') is-invalid @enderror" placeholder="Enter Total Price In USD" value="{{ old('addon_purchase_price_in_usd') }}"  autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateOtherValuesbyTotalPriceUSD(${index},1)">
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
        $("#Supplier"+supplier+"TotalPriceAED").val(sum);
        setLeastPurchasePriceAED();
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
        $("#Supplier"+supplier+"TotalPriceUSD").val(sum);
    }
    function setLeastPurchasePriceAED()
    {
        const values = Array.from(document.querySelectorAll('.leastPurchasePriceAED')).map(input => input.value);
        var arrayOfNumbers = [];
            values.forEach(v => {
                if(v != '')
                {
                    arrayOfNumbers .push(v);
                }
            });
            var arrayOfNumbers = arrayOfNumbers.map(Number);
            const min = Math.min(...arrayOfNumbers);
            $("#purchase_price").val(min);
    }
            
</script>
