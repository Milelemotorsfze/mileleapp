@extends('layouts.main')
@section('content')
    <style>
        .row-space {
            margin-bottom: 10px;
        }
        .bar {
            background-color: #778899;
            height: 30px;
            margin: 10px;
            text-align: center;
            color: white;
            line-height: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .btn.btn-success.btncenter {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn.btn-success.btncenter:hover {
            background-color: #0000ff;
            font-size: 17px;
            border-radius: 10px;
        }
    </style>
    @can('create-demand-planning-po')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-demand-planning-po');
        @endphp
        @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">Add New PO</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

        </div>
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger" >
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
                <form action="{{ route('purchasing-order.store') }}" method="POST" id="po-create-form">
                    @csrf
                    <input type="hidden" name="po_from" value="DEMAND_PLANNING">
                    <div class="row">
                        <input type="hidden" value="{{ $pfi->supplier_id }}" name="vendors_id">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="mb-3">
                            <span class="error" style="color:red">* </span>
                                <label for="choices-single-default" class="form-label font-size-13 ">PO Number</label>
                                <input type="text" name="po_number" id="po_number" required class="form-control"  placeholder="Enter PO Number" value="{{old('po_number')}}">
                                <span id="poNumberError" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-6 col-sm-12">
                            <div class="mb-3">
                            <span class="error" style="color:red">* </span>
                                <label for="choices-single-default" class="form-label font-size-13 ">PO Date</label>
                                <input type="date" name="po_date" id="po_date" class="form-control" required  placeholder="Enter PO Date"
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                <span id="poDateError" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <span class="error" style="color:red">* </span>
                                <label for="choices-single-default" class="form-label font-size-13 ">Payment Terms</label>
                              <select name="payment_term_id" class="form-control" id="payment_term_id" required>
                                @foreach($paymentTerms as $paymentTerm)
                                    <option value="{{ $paymentTerm->id }}" >{{ $paymentTerm->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="basicpill-firstname-input" class="form-label">PO Type: </label>
                            <select class="form-control" autofocus name="po_type" required>
                                <option value="Normal">Normal</option>
                                <option value="Payment Adjustment">Payment Adjustment</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label font-size-13 "> Territory </label>
                                <input type="text" class="form-control" readonly value="Africa">
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label font-size-13 "> Vendor </label>
                                <input type="text" class="form-control" readonly value="{{ $pfi->supplier->supplier ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-6 col-sm-12">
                            <div class="mb-3">

                                <label for="choices-single-default" class="form-label font-size-13 "> Currency </label>
                                <input type="text" class="form-control" name="currency" readonly value="{{ $pfi->currency ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label">Total Unit Price:</label>
                            <input type="text" name="totalcost" class="form-control" readonly id="total-price" value="0" placeholder="Total Unit Price">
                        </div>
                    </div>
                    <div id="variantRowsContainer" style="display: none;">
                        <div class="bar">Stock Vehicles</div>
                        <div class="row">
                            <div class="col-lg-1 col-md-6">
                                <label for="brandInput" class="form-label">Model-SFX:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="brandInput" class="form-label">Variant:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="QTY" class="form-label">Brand:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="QTY" class="form-label">Model Line:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="QTY" class="form-label">Variants Detail:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="exColour" class="form-label">Exterior Color:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="intColour" class="form-label">Interior Color:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="exColour" class="form-label">Estimated Arrival:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="engineNumber" class="form-label">Engine Number:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="unitPrice" class="form-label">Unit Price:</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label for="QTY" class="form-label">VIN:</label>
                            </div>
                        </div>
                        </div>
                    <div class="bar">Add New Vehicles Into Stock</div>
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label">Model-SFX</label>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label class="form-label">Variant</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label  class="form-label">Brand</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label  class="form-label">Model Line</label>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label  class="form-label">Variant Detail</label>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label  class="form-label">Unit Price</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label  class="form-label">QTY</label>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <label  class="form-label">Inventory QTY</label>
                            </div>
                        </div>
                        @foreach($pfiVehicleVariants as $key => $pfiVehicleVariant)
                            <div class="row">
                                <input type="hidden" id="loi-item-id-{{$key}}" value="{{$pfiVehicleVariant->letterOfIndentItem->id ?? ''}}">
                                <input type="hidden" name="approved_loi_ids[]" value="{{$pfiVehicleVariant->id}}">
                                <input type="hidden" name="item_quantity_selected[]" id="item-quantity-selected-{{$pfiVehicleVariant->id}}" value="0">
                                <input type="hidden" id="master-model-id-{{$key}}" name="selected_model_ids[]"  value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->id ?? ''}}">
                                <div class="col-lg-2 col-md-6 mt-md-2">
                                    <input type="text"  class="form-control" placeholder="Model" id="model-{{$key}}"
                                           value="{{ $pfiVehicleVariant->letterOfIndentItem->masterModel->model ."-". $pfiVehicleVariant->letterOfIndentItem->masterModel->sfx}}" readonly>
                                </div>
                                <div class="col-lg-2 col-md-6 mt-md-2">
                                    <select class="form-control mb-2 variants" id="variant-id-{{$key}}" data-key="{{$key}}" >
                                        @foreach($pfiVehicleVariant->masterModels as $masterModel)
                                            <option value="{{ $masterModel->variant_id }}" data-model-id="{{$masterModel->id}}"
                                                    data-brand="{{ $masterModel->variant->brand->brand_name ?? '' }}"  data-model-line="{{ $masterModel->variant->master_model_lines->model_line ?? '' }}"
                                                    data-variant-detail="{{ $masterModel->variant->detail ?? '' }}"
                                                {{ $masterModel->variant_id == $pfiVehicleVariant->letterOfIndentItem->masterModel->variant_id ? 'selected' : ''  }} >{{ $masterModel->variant->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6 mt-md-2">
                                    <input type="text"   class="form-control" placeholder="Brand" id="brand-{{$key}}"
                                           value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->brand->brand_name ?? ''}}" readonly>
                                </div>
                                <div class="col-lg-1 col-md-6 mt-md-2">
                                    <input type="text"  class="form-control" id="master-model-line-{{$key}}"
                                           value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->master_model_lines->model_line ?? ''}}"
                                           placeholder="Model Line" readonly>
                                </div>
                                <div class="col-lg-2 col-md-6 mt-md-2">
                                    <input type="text" id="variant-detail-{{$key}}" class="form-control"  placeholder="Variants Detail" readonly
                                           value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->detail ?? ''}}">
                                </div>
                                <div class="col-lg-2 col-md-6 mt-md-2">
                                    <input type="text"  class="form-control" id="unit-price-{{$key}}"
                                           value="{{ $pfiVehicleVariant->unit_price }}"
                                           placeholder="Unit Price" readonly>
                                </div>
                                <div class="col-lg-1 col-md-6 mt-md-2">
                                    <input type="number" id="quantity-{{$key}}" min="0"  oninput="checkQuantity({{$key}})" data-quantity="{{$pfiVehicleVariant->quantity}}"
                                      data-id="{{ $pfiVehicleVariant->id }}"  class="form-control qty-{{$pfiVehicleVariant->id}}" value="{{ $pfiVehicleVariant->quantity }}" placeholder="QTY">
                                    <span class="QuantityError-{{$key}} text-danger"></span>
                                </div>
                                <div class="col-lg-1 col-md-6 mt-md-2">
                                    <input type="number" id="inventory-qty-{{$key}}" min="0" readonly data-inventory-qty="{{$pfiVehicleVariant->inventoryQuantity}}"
                                      data-id="{{ $pfiVehicleVariant->id }}"  class="form-control inventory-qty-{{$pfiVehicleVariant->id}}" value="{{ $pfiVehicleVariant->inventoryQuantity }}" placeholder="QTY">
                                    <span class="InventoryQuantityError-{{$key}} text-danger"></span>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12 justify-content-end">
                            <button type="button" class="btn btn-primary float-end add-row-btn">
                                <i class="fas fa-plus"></i> Add Vehicles
                            </button>
                        </div>
                    </div>
                    <div class="bar">Shipping</div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label for="Incoterm" class="form-label">Shipping Method:</label>
                            <select class="form-control" id="shippingmethod" name="shippingmethod">
                                <option value="EXW">EXW</option>
                                <option value="CNF">CNF</option>
                                <option value="CIF">CIF</option>
                                <option value="FOB">FOB</option>
                                <option value="Local">Local</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label">Prefered Destination:</label>
                            <input type="text" id="fd" name="fd" class="form-control" value="{{old('fd')}}" placeholder="Prefered Destination" >
                        </div>

                    </div>
                    <br><br>
                      <div class="row">
                          <button type="submit" class="btn btncenter btn-success" id="submit-button">Submit</button>
                      </div>
                </form>
        </div>
        @endif
    @endcan

@endsection
@push('scripts')
<script>
    let formValid = true;
    function checkQuantity(key) {
        var selectedQuantity = $('#quantity-'+key).val();
        var variantQuantity = $('#quantity-'+key).attr('data-quantity');
        var inventoryQuantity = $('#inventory-qty-'+key).attr('data-inventory-qty');
        if(parseInt(selectedQuantity) > parseInt(inventoryQuantity)) {
            formValid = false;
            $('.QuantityError-'+key).text("Please Enter Quantity less than inventory Quantity "+inventoryQuantity);
            $('.add-row-btn').attr('disabled', true);
        }
        else if(parseInt(selectedQuantity) > parseInt(variantQuantity)){
            formValid = false;
            $('.QuantityError-'+key).text("Please Enter Quantity less than Maximum allocated Quantity "+variantQuantity);
            $('.add-row-btn').attr('disabled', true);
        }
        else{
            formValid = true;
            $('.QuantityError-'+key).text("");
            $('.add-row-btn').attr('disabled', false);
        }
    }

    function checkDuplicateVIN() {

        var vinValues = $('input[name="vin[]"]').map(function() {
            return $(this).val();
        }).get();

        var duplicates = vinValues.filter(function(value, index, self) {
            return self.indexOf(value) !== index && value.trim() !== '';
        });

        if (duplicates.length > 0) {
            alertify.alert('Duplicate VIN values found. Please ensure all VIN values are unique.').set({title:"Alert !"});
            formValid = false;
        }

        var allBlank = vinValues.every(function(value) {
            return value.trim() === '';
        });

        if (allBlank) {
            formValid = true;
            // $('#po-create-form').unbind('submit').submit();
        } else {
            // if( formValid == true) {
                var formData = $('#po-create-form').serialize();
                console.log(formData);
                $.ajax({
                    url: '{{ route('vehicles.check-create-vins') }}',
                    method: 'POST',
                    data: formData,
                    async:false,
                    cache:false,
                    success: function(response) {
                        if (response === 'duplicate') {
                            alertify.alert('Duplicate VIN values found in the database. Please ensure all VIN values are unique.').set({title:"Alert !"});
                            formValid = false;
                        } else {
                            formValid = true;
                        }
                    },
                    error: function() {
                        alertify.alert('An error occurred while checking for VIN duplication. Please try again.').set({title:"Alert !"});
                        formValid = false;
                    }
                });
            // alert(formValid);
            }
        // }

    }
    $('.add-row-btn').click(function(e) {
        $('.bar').show();
        var variantQuantity = '{{ $pfiVehicleVariants->count() }}';
        var price = 0;

        // Move the declaration and assignment inside the click event function
        var exColours = <?= json_encode($exColours) ?>;
        var intColours = <?= json_encode($intColours) ?>;
        var sum = $('#total-price').val();
        for (var i = 0; i < variantQuantity; i++) {
            checkQuantity(i);
            if(formValid == true) {
                var qty = $('#quantity-'+i).val();
                var actualQuantity = $('#quantity-'+i).attr('data-quantity');
                var remaingQuantity = parseInt(actualQuantity) - parseInt(qty);
                $('#quantity-'+i).attr('data-quantity',remaingQuantity);
                $('#quantity-'+i).val(remaingQuantity);
                var selectedVariant = $('#variant-id-'+i).find(":selected").text();

                var brand = $('#brand-'+i).val();
                var model = $('#model-'+i).val();
                var masterModelLine = $('#master-model-line-'+i).val();
                var detail = $('#variant-detail-'+i).val();
                var masterModelId = $('#master-model-id-'+i).val();
                var loiItemId = $('#loi-item-id-'+i).val();
                var dataid = $('#quantity-'+i).attr('data-id');
                var price = $('#unit-price-'+i).val();
                var existingQuantity =  $('#item-quantity-selected-'+dataid).val();
                var latestQty = parseInt(existingQuantity) + parseInt(qty);
                $('#item-quantity-selected-'+dataid).val(latestQty);
                var unitPrices = price * qty;
                var sum = parseInt(sum) + parseInt(unitPrices);
                $('#total-price').val(sum);

                for (var j = 0; j < qty; j++) {
                    var newRow = $('<div class="row row-space"></div>');
                    var LoiItemCol  = $('<input type="hidden" name="loi_item_Ids[]" value="' + loiItemId + '" >');
                    var masterModelCol  = $('<input type="hidden" id="model-id" name="master_model_id[]" value="' + masterModelId + '" >');
                    var ModelCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" title="'+ model +'"  value="' + model + '" class="form-control" readonly></div>');
                    var variantCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" id="variant-id" title="'+ selectedVariant +'"   title="'+ model +'"  name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>');
                    var brandCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" name="brand[]" title="'+ brand +'"  value="' + brand + '" class="form-control" readonly></div>');
                    var masterModelLineCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" title="'+ masterModelLine +'" name="master_model_line[]" value="' + masterModelLine + '" class="form-control" readonly></div>');
                    var detailCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" name="detail[]" value="' + detail + '"  title="'+ detail +'"  class="form-control" readonly></div>');
                    var exColourCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></div>');
                    var intColourCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></div>');
                    var vinCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" name="vin[]" class="form-control" placeholder="VIN"></div>');
                    var estimatedCol = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="date" name="estimated_arrival[]" class="form-control"></div>');
                    var engineNumber = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" name="engine_number[]" class="form-control"></div>');
                    var unitPrice = $('<div class="col-lg-1 col-md-6 mt-md-2"><input type="text" value="' + price + '"  title="'+ price +'" name="unit_prices[]" readonly class="form-control"></div>');
                    var removeBtn = $('<div class="col-lg-1 col-md-6 mt-md-2"><button type="button" data-unit-price="'+ price +'" data-approved-id="' + dataid + '" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></div>');
                    // Populate Exterior Colors dropdown
                    var exColourDropdown = exColourCol.find('select');
                    for (var id in exColours) {
                        if (exColours.hasOwnProperty(id)) {
                            exColourDropdown.append($('<option></option>').attr('value', id).text(exColours[id]));
                        }
                    }
                    // // Populate Interior Colors dropdown
                    var intColourDropdown = intColourCol.find('select');
                    for (var id in intColours) {
                        if (intColours.hasOwnProperty(id)) {
                            intColourDropdown.append($('<option></option>').attr('value', id).text(intColours[id]));
                        }
                    }
                    newRow.append(LoiItemCol,masterModelCol, ModelCol, variantCol, brandCol, masterModelLineCol, detailCol, exColourCol, intColourCol, estimatedCol, engineNumber, unitPrice, vinCol, removeBtn);
                    $('#variantRowsContainer').append(newRow);
                }
                $('#variantRowsContainer').show();
            }

        }
    });

    $(document).on('click', '.remove-row-btn', function() {

        $(this).closest('.row').remove();

        var Id = $(this).attr('data-approved-id');
        var selectedQuantity = $('.qty-'+Id).val();

        var variantQuantity = $('.qty-'+Id).attr('data-quantity');
        var remainingQty = parseInt(variantQuantity) + 1;
        $('.qty-'+Id).attr('data-quantity',remainingQty);

        var latestQuantity = parseInt(selectedQuantity) + 1;
        $('.qty-'+Id).val(latestQuantity);
        var previousCount =   $('#item-quantity-selected-'+Id).val();
        var latestCount = previousCount - 1;
        $('#item-quantity-selected-'+Id).val(latestCount);

        if ($('#variantRowsContainer').find('.row').length === 1) {
            $('.bar').hide();
            $('#variantRowsContainer').hide();
        }

        var price = $(this).attr('data-unit-price');
        var totalPrice = $('#total-price').val();
        var remainingPrice = totalPrice - price;
        $('#total-price').val(remainingPrice);

    });
    $('#po_number').on('change', function() {
        var poNumber = $('#po_number').val();
        $.ajax({
            url: '{{ route('purchasing-order.checkPONumber') }}',
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'poNumber': poNumber
            },
            success: function(response) {
                $('#poNumberError').hide().text('');
                formValid = true;
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('#poNumberError').text("PO Number Already Existing");
                    formValid = false;
                }
            }
        });
    });

    $('.variants').on('change', function() {
        var key = $(this).attr('data-key');
        console.log(key);
        var model = $(this).find('option:selected').attr("data-model-id");
        var brand = $(this).find('option:selected').attr("data-brand");
        var modelLine = $(this).find('option:selected').attr("data-model-line");
        var variantDetail = $(this).find('option:selected').attr("data-variant-detail");

        $('#master-model-id-'+key).val(model);
        $('#brand-'+key).val(brand);
        $('#master-model-line-'+key).val(modelLine);
        $('#variant-detail-'+key).val(variantDetail);
    });

    $('#submit-button').click(function(e) {
        e.preventDefault();

        var variantIds = $('input[name="variant_id[]"]').map(function() {
            return $(this).val();
        }).get();

        if (variantIds.length === 0) {
            alertify.alert('Please select variant quantity and and add vehicles.').set({title:"Alert !"});
            formValid = false;
        }else{
            // alert("variant is there");
            formValid = true;
            checkDuplicateVIN();
        }
        // alert("inside submit");
        // alert(formValid);
            if( formValid == true) {
                var poNumber = $('#po_number').val();
                if(poNumber == '') {
                    formValid = false;
                    $('#poNumberError').text("This field is required")
                }else{
                    formValid = true;
                    $('#poNumberError').text(" ");
                }
            }
        // alert("after po validation submit");
        // alert(formValid);
        if(formValid == true) {
            $('#po-create-form').unbind('submit').submit();
        }
    });
</script>
@endpush

