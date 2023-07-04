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
                                        @foreach($addonDetails->AddonSuppliers as $AddonSuppliers)
                                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                                <span class="error">* </span>
                                                <label for="choices-single-default" class="form-label font-size-13">Suppliers</label>
                                                <input value="{{ $AddonSuppliers->Suppliers->supplier}}" type="text" class="form-control widthinput" readonly>
                                            </div>                                        
                                            <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_aed_1" >
                                                <span class="error">* </span>
                                                <label for="choices-single-default" class="form-label font-size-13">Purchase Price In AED</label>
                                                <div class="input-group">
                                                    <input readonly class="form-control widthinput" value="{{ $AddonSuppliers->purchase_price_aed}}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>  
                                                </div> 
                                            </div>
                                            <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                                <span class="error">* </span>
                                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                                <div class="input-group">
                                                    <input readonly class="form-control widthinput" value="{{ $AddonSuppliers->purchase_price_usd}}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>  
                                                </div> 
                                            </div>
                                            <div class="form-group col-xxl-1 col-lg-1 col-md-1">
                                                <button class="btn_round">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        @endforeach
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
                                            <div class="input-group">
                                            <input  name="supplierAndPrice[1][addon_purchase_price_in_aed]" id="addon_purchase_price_1" type="number" min="0" step="any" 
                                            class="leastPurchasePriceAEDKIT notKitSupplierPurchasePrice form-control widthinput @error('addon_purchase_price') is-invalid @enderror" 
                                            placeholder="Enter Addons Purchase Price In AED , 1 USD = 3.6725 AED" value="{{ old('supplierAndPrice[1][addon_purchase_price_in_aed]') }}"  
                                            autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                    </div>  
                                                </div> 
                                            <span id="purchasePriceAEDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="col-xxl-3 col-lg-3 col-md-3" id="div_price_in_usd_1" >
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                            <div class="input-group">
                                            <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1" type="number" min="0" step="any" 
                                            class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror" 
                                            placeholder="Enter Addons Purchase Price In USD , 1 USD = 3.6725 AED" value="{{ old('supplierAndPrice[1][addon_purchase_price_in_usd]') }}"  
                                            autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>  
                                                </div> 
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
                                <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info addSupplierAndPriceWithoutKit"><i class="fa fa-plus" 
                                aria-hidden="true"></i> Add Supplier</a> 
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
        
    });
</script>