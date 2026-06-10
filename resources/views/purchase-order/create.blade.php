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
        .form-control {
            height:32px !important;
        }
        .select2-container {
            width: 100% !important;
        }
      .select2-selection__rendered {
            font-size: 12px !important;
            font-weight: 400 !important; /* Adjust this value as needed */
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
                <form action="{{ route('purchasing-order.store') }}" method="POST" id="po-create-form"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="po_from" value="DEMAND_PLANNING">
                    <input type="hidden" name="is_demand_planning_po" value="1">
                    <input type="hidden" name="pfi_id" value="{{ \Crypt::encrypt($pfi->id) }}">
                    
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
                                <input type="text" class="form-control" readonly name="territory" value="Africa">
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
                            <label class="form-label">Total Unit Price</label>
                            <input type="text" name="totalcost" class="form-control" readonly id="total-price" value="0" placeholder="Total Unit Price">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" readonly value="{{ strtoupper($pfi->customer->name ?? '') }}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label class="form-label">PFI Number</label>
                            <input type="text" class="form-control" readonly value="{{ $pfi->pfi_reference_number ?? '' }}">
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
                                <label for="QTY" class="form-label">Brand & Model Line:</label>
                            </div>
                          
                            <div class="col-lg-2 col-md-6">
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
                            <!-- @if($isToyotaPO)
                                <div class="col-lg-1 col-md-6">
                                    <label  class="form-label">Inventory QTY</label>
                                </div>
                            @endif -->
                        </div>
                        @foreach($pfiItems as $key => $pfiItem)
                                <div class="row">
                                    <input type="hidden" id="pfi-item-id-{{$key}}" name="pfi_items[]" value="{{$pfiItem->id ?? ''}}"> 
                                    <input type="hidden" name="item_quantity_selected[]" id="item-quantity-selected-{{$pfiItem->id}}" value="0">
                                    <input type="hidden" id="master-model-id-{{$key}}" name="selected_model_ids[]"  value="{{$pfiItem->masterModel->id ?? ''}}">
                                    <div class="col-lg-2 col-md-6 mt-md-2">
                                        <input type="text"  class="form-control" placeholder="Model" id="model-{{$key}}"
                                            value="{{ $pfiItem->masterModel->model ."-". $pfiItem->masterModel->sfx}}" readonly>
                                    </div>
                                    <div class="col-lg-2 col-md-6 mt-md-2">
                                        <select class="form-control mb-2 variants" id="variant-id-{{$key}}" data-key="{{$key}}" >
                                            @foreach($pfiItem->masterModels as $masterModel)
                                                <option value="{{ $masterModel->variant_id }}" data-model-id="{{$masterModel->id}}"
                                                        data-brand="{{ $masterModel->variant->brand->brand_name ?? '' }}"  data-model-line="{{ $masterModel->variant->master_model_lines->model_line ?? '' }}"
                                                        data-variant-detail="{{ $masterModel->variant->detail ?? '' }}"
                                                    {{ $masterModel->variant_id == $pfiItem->masterModel->variant_id ? 'selected' : ''  }} >{{ $masterModel->variant->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text"   class="form-control" placeholder="Brand" id="brand-{{$key}}"
                                            value="{{$pfiItem->masterModel->variant->brand->brand_name ?? ''}}" readonly>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text"  class="form-control" id="master-model-line-{{$key}}"
                                            value="{{$pfiItem->masterModel->variant->master_model_lines->model_line ?? ''}}"
                                            placeholder="Model Line" readonly>
                                    </div>
                                    <div class="col-lg-2 col-md-6 mt-md-2">
                                        <input type="text" id="variant-detail-{{$key}}" class="form-control"  placeholder="Variants Detail" readonly
                                            value="{{$pfiItem->masterModel->variant->detail ?? ''}}">
                                    </div>
                                    <div class="col-lg-2 col-md-6 mt-md-2">
                                        <input type="text"  class="form-control" id="unit-price-{{$key}}"
                                            value="{{ $pfiItem->unit_price }}"
                                            placeholder="Unit Price" readonly>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="number" id="quantity-{{$key}}" min="0" max="{{ $pfiItem->quantity }}" data-quantity="{{$pfiItem->quantity}}"
                                        data-id="{{ $pfiItem->id }}"  class="form-control qty-{{$pfiItem->id}}" value="{{ $pfiItem->quantity }}" placeholder="QTY" >
                                        <span class="QuantityError-{{$key}} text-danger"></span>
                                    </div>
                                    <!-- @if($isToyotaPO)
                                        <div class="col-lg-1 col-md-6 mt-md-2">
                                            <input type="number" id="inventory-qty-{{$key}}" min="0" readonly data-inventory-qty="{{$pfiItem->inventoryQuantity}}"
                                            data-id="{{ $pfiItem->id }}"  class="form-control inventory-qty-{{$pfiItem->id}}" value="{{ $pfiItem->inventoryQuantity }}" placeholder="QTY">
                                            <span class="InventoryQuantityError-{{$key}} text-danger"></span>
                                        </div>
                                    @endif -->
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
                            <select name="prefered_destination" class="form-control" id="prefered_destination" multiple>
                                <option value="">Select the Prefered Destination</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"{{ $country->id == $pfi->country_id ? 'selected' : ''}} >{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br><br>
                      <div class="row">
                        <input type="hidden" id="can-inventory-allocate" value="0" name="can_inventory_allocate">
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
    let isToyotaPO = "{{ $isToyotaPO }}"
    let totalPOqty = "{{ $totalPOqty }}"
    let isEnableVehicleAdd = true;
    
    $('#prefered_destination').select2({
        placeholder: "Select Prefered Destination",
        maximumSelectionLength: 1
    })
    
    // Auto-select "100% Advance" payment terms when CPS Middle East Automobiles Trading FZE is the vendor
    $(document).ready(function() {
        var vendorName = "{{ $pfi->supplier->supplier ?? '' }}";
        var cpsVendorName = 'CPS Middle East Automobiles Trading FZE';
        
        // Check if CPS vendor is selected (with flexible matching for spaces/variations)
        if (vendorName === cpsVendorName || 
            vendorName.replace(/\s+/g, ' ').trim() === cpsVendorName ||
            vendorName.toLowerCase().includes('cps') && vendorName.toLowerCase().includes('middle east')) {
            
            // Find and select "100% Advance" payment term
            var paymentTermSelect = $('#payment_term_id');
            var advanceOption = paymentTermSelect.find('option').filter(function() {
                var optionText = $(this).text().trim().toLowerCase();
                return optionText.includes('100%') && optionText.includes('advance');
            });
            
            if (advanceOption.length > 0) {
                paymentTermSelect.val(advanceOption.val()).trigger('change');
            } else {
                // If "100% Advance" doesn't exist, try to find similar terms
                var similarOption = paymentTermSelect.find('option').filter(function() {
                    var optionText = $(this).text().trim().toLowerCase();
                    return optionText.includes('advance') || optionText.includes('100');
                });
                
                if (similarOption.length > 0) {
                    paymentTermSelect.val(similarOption.first().val()).trigger('change');
                }
            }
        }
    });
    function checkDuplicateVIN() {
        var vinValues = $('input[name="vin[]"]').map(function() {
            return $(this).val();
        }).get();

        var duplicates = vinValues.filter(function(value, index, self) {
            return self.indexOf(value) !== index && value.trim() !== '';
        });

        if (duplicates.length > 0) {
            alertify.alert("VIN already exists under a different PO. Please choose another VIN.").set({title:"Alert !"});
            formValid = false;
        }

        var allBlank = vinValues.every(function(value) {
            return value.trim() === '';
        });

        if (allBlank) {
            formValid = true;
        } else {
                var formData = $('#po-create-form').serialize();
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
            }

    }
    $('.add-row-btn').click(function(e) {
        $('.bar').show();
        var variantQuantity = '{{ $pfiItems->count() }}';
        var price = 0;
        var sum = $('#total-price').val();
        for (var i = 0; i < variantQuantity; i++) {
            var selectedQty = $('#quantity-'+i).val();
            var pfiQuantity = $('#quantity-'+i).attr('data-quantity');
            var inventoryQuantity = $('#inventory-qty-'+i).attr('data-inventory-qty');
            // if(isToyotaPO == 1) {
            //     // toyota - only one po
            //     // check  pfiQuantity is less than inventory quantity  
            //     if(parseInt(inventoryQuantity) < parseInt(pfiQuantity)) {
            //         isEnableVehicleAdd = false;
            //         alertify.confirm('Required vehicle quanity is not available in the inventory',function (e) {
            //         }).set({title:"Invalid Data"});
            //         return false;
            //     }
            // }else{
                 // not toyota - multiple po 
                // maximum quantity should the the pfi item quantity 
                if(parseInt(pfiQuantity) < parseInt(selectedQty)) {
                    var model = $('#model-'+i).val();
                    alertify.confirm('The Maximum PFI quantity you can enter for the model '+ model +' is ' + pfiQuantity +'.',function (e) {
                    }).set({title:"Invalid Data"});
                    isEnableVehicleAdd = false;
                    return false;
                }else{
                    isEnableVehicleAdd = true;
                }
            // }
        }
       
        if(isEnableVehicleAdd == true) {
            for (var i = 0; i < variantQuantity; i++) {
                // check remaining quantity is available or not
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
                    var pfiItemId = $('#pfi-item-id-'+i).val();
                    var dataid = $('#quantity-'+i).attr('data-id');
                    var price = $('#unit-price-'+i).val();
                    var existingQuantity =  $('#item-quantity-selected-'+dataid).val();
                    var latestQty = parseInt(existingQuantity) + parseInt(qty);
                    $('#item-quantity-selected-'+dataid).val(latestQty);
                    var unitPrices = price * qty;
                    var sum = parseInt(sum) + parseInt(unitPrices);
                    $('#total-price').val(sum);
                    var brandModelLine = masterModelLine+'-'+brand;

                    for (var j = 0; j < qty; j++) {
                        var newRow = $(`<div class="row row-space">
                                    <input type="hidden" name="pfi_item_Ids[]" value="${ pfiItemId }" >
                                    <input type="hidden" id="model-id" name="master_model_id[]" value="${ masterModelId }" >
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text" title="${ model }"  value="${ model }" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text" id="variant-id" title="${ selectedVariant }"   title="${ model }" 
                                         name="variant_id[]" value="${selectedVariant}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text" title="${ brandModelLine }"  value="${ brandModelLine }" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2 col-md-6 mt-md-2">
                                        <textarea name="detail[]" class="form-control" readonly style="width: 100%;">${detail}</textarea>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mb-5 mt-md-2">
                                        <select name="ex_colour[]" class="form-control exterior-colours">
                                            <option value="">Exterior Color</option>
                                                @foreach ($exColours as $colour)
                                                    <option value="{{ $colour->id }}">
                                                        {{ $colour->name }} @if($colour->code) ( {{ $colour->code}}) @endif
                                                    </option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <select name="int_colour[]" class="form-control interior-colours">
                                            <option value="">Interior Color</option>
                                            @foreach ($intColours as $colour)
                                                <option value="{{ $colour->id }}">
                                                    {{ $colour->name }} @if($colour->code) ( {{ $colour->code}}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="date" name="estimated_arrival[]" class="form-control" value="">
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text" name="engine_number[]" class="form-control" >
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text" value="${price}"  title="${price}" name="unit_prices[]" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-1 col-md-6 mt-md-2">
                                        <input type="text" name="vin[]" class="form-control"  title="VIN Number" placeholder="VIN">
                                    </div>
                                </div>`);
                        var removeBtn = $(`<div class="col-lg-1 col-md-6 mt-md-2">
                                            <button type="button" data-unit-price="${price}" data-approved-id="${dataid}" class="btn btn-danger btn-sm remove-row-btn">
                                                <i class="fas fa-times"></i></button>
                                            </div>`);
                       
                            newRow.append(removeBtn);
                        $('#variantRowsContainer').append(newRow);
                        $('.exterior-colours').select2({
                            placeholder: 'Exterior',
                            width: '100%',
                        });
                        $('.interior-colours').select2({
                            placeholder: 'Interior',
                            width: '100%',
                        });
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
    $('#po_number').on('keyup', function() {
        checkPOUnique();
    });

    function checkPOUnique() {
        var poNumber = $('#po_number').val();
        $.ajax({
            url: "{{ route('dp-purchasing-order.checkPONumber') }}",
            async: false,
            type: 'GET',
            data: {
                'poNumber': poNumber
            },
            success: function(response) {
                if(response == true) {
                    formValid = false;
                    $('#po_number').addClass('is-invalid');
                    $('#poNumberError').text("PO Number Already Existing");
                }else{
                    formValid = true;
                    $('#po_number').removeClass('is-invalid');
                    $('#poNumberError').text(" ");
                }
            }   
        });
    }

    $('.variants').on('change', function() {
        var key = $(this).attr('data-key');
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
       
            var poNumber = $('#po_number').val();
            if(poNumber == '') {
                formValid = false;
                $('#po_number').addClass('is-invalid');
                $('#poNumberError').text("This field is required")
            }else{
                formValid = true;
                $('#po_number').removeClass('is-invalid');
                $('#poNumberError').text(" ");
            }
            if(formValid == true) {
                checkPOUnique();
            }

            if(formValid == true) {
                
                if (variantIds.length === 0) {
                    alertify.alert('Please select variant quantity and and add vehicles.').set({title:"Alert !"});
                    formValid = false;
                }else{
                    if(isToyotaPO == 1 && totalPOqty != variantIds.length) {
                        alertify.alert('This is PO For Toyota, So Please utilize all quantity ('+totalPOqty+')').set({title:"Alert !"});
                        formValid = false;
                    }else{
                        formValid = true;
                        checkDuplicateVIN();
                    }
                    
                }
            }

        if(formValid == true) {
            $('#po-create-form').unbind('submit').submit();
             //  mapping confirmation nand colour check if po is for toyota
            // if(isToyotaPO == 1) {
                // let exteriorColours = $('select[name="ex_colour[]"]').map(function() {
                //     return $(this).val();
                // }).get();

                // let interiorColours = $('select[name="int_colour[]"]').map(function() {
                //     return $(this).val();
                // }).get();

                // let masterModelsIds = $('input[name="master_model_id[]"]').map(function() {
                //     return $(this).val();
                // }).get();

                // let msg = '';
                // if(exteriorColours.length > 0 && interiorColours.length > 0) {

                //     $.ajax({
                //     url: "{{ route('dp-purchase-order.inventory-check') }}",
                //     type: 'GET',
                //     data: {
                //         'int_colours': interiorColours,
                //         'ex_colours': exteriorColours,
                //         'master_model_id': masterModelsIds,
                //         'pfi_id': "{{ $pfi->id }}"
                //     },
                //     success: function(response) {
                //         if(response.length > 0) {
                //                msg = "Inventory doest not exist exact colour matches with po vehicles";
                //         }else{
                //             msg = "The exact colour matches  in inventory "
                //         }
                       
                //         }
                //     });
                // }
                // var confirm = alertify.confirm(msg+'Do you want to allocate the PO vehicles with supplier inventory?',function (e) {
                //                         if (e) {
                //                             $('#can-inventory-allocate').val(1);
                //                         }
                //                     }).set({title:"Are You Sure ?"}).set('oncancel', function(closeEvent){
                //                             $('#can-inventory-allocate').val(0);
                //                         });
            // }
          
        }
    });
</script>
@endpush

