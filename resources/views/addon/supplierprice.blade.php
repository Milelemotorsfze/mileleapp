<div class="row" id="notKitSupplier">
    <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
        <div id="London" class="tabcontent">
            <div class="row">
                <div class="card-body">
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-md-12 p-0">
                                <div class="col-md-12 supplierWithoutKit p-0">
                                    <div class="row supplierWithoutKitApendHere" id="row-1" >
                                        <div class="col-xxl-5 col-lg-6 col-md-12">
                                            <span class="error">* </span>
                                            <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                                            <select name="supplierAndPrice[1][supplier_id][]" data-index="1" id="suppliers1" multiple="true" style="width: 100%;"
                                                    onchange="validationOnKeyUp(this)" class="suppliers">
{{--                                                @foreach($suppliers as $supplier)--}}
{{--                                                    <option class="{{$supplier->id}}" value="{{$supplier->id}}">{{$supplier->supplier}}</option>--}}
{{--                                                @endforeach--}}
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
                                            <input  name="supplierAndPrice[1][addon_purchase_price_in_aed]" id="addon_purchase_price_1" oninput="inputNumberAbs(this)"
                                                    class="leastPurchasePriceAEDKIT notKitSupplierPurchasePrice form-control  purchase_price_AED
                                                     widthinput @error('addon_purchase_price') is-invalid @enderror"
                                                    placeholder="Enter Addons Purchase Price In AED , 1 USD = 3.6725 AED"
                                                    value="{{ old('supplierAndPrice[1][addon_purchase_price_in_aed]') }}"  autocomplete="addon_purchase_price"
                                                    autofocus onkeyup="calculateUSD(1)">
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
                                            <input  name="supplierAndPrice[1][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_1"
                                            oninput="inputNumberAbs(this)" class="form-control widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror
                                                    purchase_price_USD" placeholder="Enter Addons Purchase Price In USD , 1 USD = 3.6725 AED"
                                                    value="{{ old('supplierAndPrice[1][addon_purchase_price_in_usd]') }}"  autocomplete="addon_purchase_price_in_usd"
                                                    autofocus onkeyup="calculateAED(1)">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                                    </div>
                                                </div>
                                            <span id="purchasePriceUSDError" class="invalid-feedback"></span>
                                        </div>
                                        <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                            <button class="btn_round  removeButton" disabled hidden>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-12 col-lg-12 col-md-12">
                                <a id="addSupplier" style="float: right;" class="btn btn-sm btn-info addSupplierAndPriceWithoutKit">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add Supplier</a>
                            </div>
                        </div>
                    </div>
                    </br>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="indexValue" value="">
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        $("#suppliers1").select2
        ({
            placeholder:"Choose Suppliers....     Or     Type Here To Search...."
        });
        var index = 1;
        $('#indexValue').val(index);
        $(document.body).on('select2:select', ".suppliers", function (e) {
            var index = $(this).attr('data-index');
            var value = e.params.data.id;
            hideOption(index,value);
        });
        $(document.body).on('select2:unselect', ".suppliers", function (e) {
            var index = $(this).attr('data-index');
            var data = e.params.data;
            appendOption(index,data);
        });
        function addOption(id,text) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                $('#suppliers'+i).append($('<option>', {value: id, text :text}))
            }
        }

        function hideOption(index,value) {
            var indexValue = $('#indexValue').val();
            for (var i = 1; i <= indexValue; i++) {
                if (i != index) {
                    var currentId = 'suppliers' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();
                }
            }
        }
        function appendOption(index,data) {
            var indexValue = $('#indexValue').val();
            for(var i=1;i<=indexValue;i++) {
                if(i != index) {
                    $('#suppliers'+i).append($('<option>', {value: data.id, text : data.text}))
                }
            }
        }
        $(document.body).on('click', ".removeButton", function (e) {
            var indexNumber = $(this).attr('data-index');

            $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
                var id = (this.value);
                var text = (this.text);
                addOption(id,text)
            });

            $(this).closest('#row-'+indexNumber).remove();
            $('.supplierWithoutKitApendHere').each(function(i){
                var index = +i + +1;
                $(this).attr('id','row-'+ index);
                $(this).attr('data-select2-id','select2-data-row-'+ index);
                $(this).find('select').attr('data-index', index);
                $(this).find('select').attr('id','suppliers'+ index);
                $(this).find('select').attr('name','supplierAndPrice['+ index +'][supplier_id][]');
                $(this).find('.AED_price').attr('id','div_price_in_aed_'+ index);
                $(this).find('.purchase_price_AED').attr('name','supplierAndPrice['+ index +'][addon_purchase_price_in_aed]');
                $(this).find('.purchase_price_AED').attr('id','addon_purchase_price_'+index);
                $(this).find('.purchase_price_AED').attr('onkeyup','calculateUSD('+ index +')');

                $(this).find('.USD_price').attr('id','div_price_in_usd_'+ index);
                $(this).find('.purchase_price_USD').attr('name','supplierAndPrice['+ index +'][addon_purchase_price_in_usd]');
                $(this).find('.purchase_price_USD').attr('onkeyup','calculateAED('+ index +')');
                $(this).find('.purchase_price_USD').attr('id','addon_purchase_price_in_usd_'+index);
                $(this).find('.purchase_price_AED').attr('onkeyup','calculateUSD('+ index +')');
                $(this).find('button').attr('data-index', index);
                $('#suppliers'+index).select2
                ({
                    placeholder:"Choose Suppliers....     Or     Type Here To Search....",
                    allowClear: true,
                    minimumResultsForSearch: -1,
                });
            });
            setLeastAEDPrice();

        })
    });

    $("body").on("click",".addSupplierAndPriceWithoutKit", function ()
    {

        var index = $(".supplierWithoutKit").find(".supplierWithoutKitApendHere").length + 1;

        $('#indexValue').val(index);
        var selectedSuppliers = [];
        for(let i=1; i<index; i++)
        {
            var eachSelectedSupplier = $("#suppliers"+i).val();
            $.each(eachSelectedSupplier, function( ind, value )
            {
                selectedSuppliers.push(value);
            });
        }
        $.ajax({
            url:"{{url('getSupplierForAddon')}}",
            type: "POST",
            data:
                {
                    addonType:currentAddonType,
                    filteredArray: selectedSuppliers,
                    _token: '{{csrf_token()}}'
                },
            dataType : 'json',
            success: function(data)
            {
                myarray = data;
                var size= myarray.length;
                if(size >= 1)
                {
                    $(".supplierWithoutKit").append(`
                        <div class="row supplierWithoutKitApendHere" id="row-${index}" >
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="choices-single-default" class="form-label font-size-13">Choose Suppliers</label>
                                <select class="addonClass suppliers" data-index="${index}"  id="suppliers${index}" name="supplierAndPrice[${index}][supplier_id][]"
                                 multiple="true" style="width: 100%;">
                                </select>
                                @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="col-xxl-3 col-lg-3 col-md-3 AED_price" id="div_price_in_aed_${index}">
                                <label for="choices-single-default" class="form-label font-size-13">Purchase Price In AED</label>
                                <div class="input-group">
                                <input name="supplierAndPrice[${index}][addon_purchase_price_in_aed]" id="addon_purchase_price_${index}" oninput="inputNumberAbs(this)"
                                class="leastPurchasePriceAEDKIT notKitSupplierPurchasePrice purchase_price_AED form-control widthinput @error('addon_purchase_price') is-invalid @enderror"
                                 placeholder="Enter Addons Purchase Price In USD ,1 USD = 3.6725 AED" value="{{ old('addon_purchase_price') }}"
                                  autocomplete="addon_purchase_price" autofocus onkeyup="calculateUSD(${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-3 col-md-3 USD_price" id="div_price_in_usd_${index}">
                                <label for="choices-single-default" class="form-label font-size-13 ">Purchase Price In USD</label>
                                <div class="input-group">
                                <input name="supplierAndPrice[${index}][addon_purchase_price_in_usd]" id="addon_purchase_price_in_usd_${index}"
                                oninput="inputNumberAbs(this)" class=" form-control purchase_price_USD widthinput @error('addon_purchase_price_in_usd') is-invalid @enderror"
                                 placeholder="Enter Addons Purchase Price In USD ,1 USD = 3.6725 AED" value="{{ old('addon_purchase_price_in_usd') }}"
                                   autocomplete="addon_purchase_price_in_usd" autofocus onkeyup="calculateAED(${index})">
                                    <div class="input-group-append">
                                        <span class="input-group-text widthinput" id="basic-addon2">USD</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
                                <button class="btn_round removeButton" data-index="${index}" >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `);
                    let brandDropdownData   = [];
                    $.each(data,function(key,value)
                    {
                        brandDropdownData.push
                        ({
                            id: value.id,
                            text: value.supplier
                        });
                    });
                    $('#suppliers'+index).select2
                    ({
                        placeholder:"Choose Supplier....     Or     Type Here To Search....",
                        allowClear: true,
                        data: brandDropdownData,
                        minimumResultsForSearch: -1,
                    });
                }
            }
        });
        });


        //===== delete the form fieed row
        // $("body").on("click", ".removeButtonSupplierWithoutKit", function ()
        // {
        //     alert(currentAddonType);
        //     $(this).closest(".supplierWithoutKitApendHere").remove();
        // });
</script>
