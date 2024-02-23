<div class="card">
    <div class="card-header">
        <h4 class="card-title">Add More Vehicles</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('purchasing-order.update', $purchasingOrder->id) }}" method="POST" id="po-create-form" >
            @csrf
            @method('PUT')
            <input type="hidden" name="po_from" value="DEMAND_PLANNING">

            <div id="VehiclevariantRowsContainer" style="display: none;">
                <div class="bar">Stock Vehicles</div>
                <div class="row">
                    <div class="col-lg-1 col-md-6">
                        <label for="brandInput" class="form-label">Variants:</label>
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <label for="QTY" class="form-label">Brand:</label>
                    </div>
                    <div class="col-lg-1 col-md-6">
                        <label for="QTY" class="form-label">Model Line:</label>
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
            <div class="bar">Add New Vehicles Into PO</div>
            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Variant</label>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label  class="form-label">Brand</label>
                </div>
                <div class="col-lg-2 col-md-6">
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
            <div class="row">
                @foreach($pfiVehicleVariants as $key => $pfiVehicleVariant)
                    <div class="row">
                        <input type="hidden" name="approved_loi_ids[]" value="{{$pfiVehicleVariant->id}}">
                        <input type="hidden" name="item_quantity_selected[]" id="item-quantity-selected-{{$pfiVehicleVariant->id}}" value="0">
                        <input type="hidden" id="master-model-id-{{$key}}" name="selected_model_ids[]"  value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->id ?? ''}}">
                        <div class="col-lg-2 col-md-6">
                            <select class="form-control mb-2 variants" id="variant-id-{{$key}}" data-key="{{$key}}" >
                                @foreach($pfiVehicleVariant->masterModels as $masterModel)
                                    <option value="{{ $masterModel->variant_id }}" data-model-id="{{$masterModel->id}}"
                                            data-brand="{{ $masterModel->variant->brand->brand_name ?? '' }}"
                                            data-model-line="{{ $masterModel->variant->master_model_lines->model_line ?? '' }}"
                                            data-variant-detail="{{ $masterModel->variant->detail ?? '' }}"{{ $masterModel->variant_id == $pfiVehicleVariant->letterOfIndentItem->masterModel->variant_id ? 'selected' : ''  }} >{{ $masterModel->variant->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <input type="text"   class="form-control" placeholder="Brand" id="brand-{{$key}}"
                                   value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->brand->brand_name ?? ''}}" readonly>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <input type="text"  class="form-control" id="master-model-line-{{$key}}"
                                   value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->master_model_lines->model_line ?? ''}}"
                                   placeholder="Model Line" readonly>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <input type="text" id="variant-detail-{{$key}}" class="form-control"  placeholder="Variants Detail" readonly
                                   value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->detail ?? ''}}">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <input type="text"  class="form-control" id="unit-price-{{$key}}"
                                   value="{{ $pfiVehicleVariant->unit_price }}"
                                   placeholder="Unit Price" readonly>
                        </div>
                        <div class="col-lg-1 col-md-6">
                            <input type="number" id="quantity-{{$key}}" min="0"  oninput="checkQuantity({{$key}})" data-quantity="{{$pfiVehicleVariant->quantity}}"
                                   data-id="{{ $pfiVehicleVariant->id }}"  class="form-control qty-{{$pfiVehicleVariant->id}}" value="{{ $pfiVehicleVariant->quantity }}" placeholder="QTY">
                            <span class="QuantityError-{{$key}} text-danger"></span>
                        </div>
                        <div class="col-lg-1 col-md-6">
                            <input type="number" id="inventory-qty-{{$key}}" min="0" readonly data-inventory-qty="{{$pfiVehicleVariant->inventoryQuantity}}"
                                   data-id="{{ $pfiVehicleVariant->id }}"  class="form-control inventory-qty-{{$pfiVehicleVariant->id}}" value="{{ $pfiVehicleVariant->inventoryQuantity }}" placeholder="QTY">
                            <span class="InventoryQuantityError-{{$key}} text-danger"></span>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-1 col-md-6 upernac" style="text-align: right">
                    <div class="btn btn-primary add-vehicle-btn" >
                        <i class="fas fa-plus"></i> Add Vehicle
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <button type="submit" class="btn btncenter btn-success" id="submit-add-more-vehicle-button">Submit</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
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

        } else {
            $.ajax({
                url: '{{ route('vehicles.check-create-vins') }}',
                method: 'POST',
                data:{
                    vin: vinValues,
                    _token: '{{ csrf_token() }}'
                },
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
    $(document).ready(function() {
        $('.add-vehicle-btn').click(function (e) {

            // if(formValid == true) {
            $('.bar').show();
            var variantQuantity = '{{ $pfiVehicleVariants->count() }}';
            var price = 0;

            // Move the declaration and assignment inside the click event function
            var exColours = <?= json_encode($exColours) ?>;
            var intColours = <?= json_encode($intColours) ?>;
            var sum = $('#total-price').val();
            for (var i = 0; i < variantQuantity; i++) {
                checkQuantity(i);
                if (formValid == true) {
                    var qty = $('#quantity-' + i).val();
                    var actualQuantity = $('#quantity-' + i).attr('data-quantity');
                    var remaingQuantity = parseInt(actualQuantity) - parseInt(qty);
                    $('#quantity-' + i).attr('data-quantity', remaingQuantity);
                    $('#quantity-' + i).val(remaingQuantity);
                    var selectedVariant = $('#variant-id-' + i).find(":selected").text();

                    var brand = $('#brand-' + i).val();
                    var masterModelLine = $('#master-model-line-' + i).val();
                    var detail = $('#variant-detail-' + i).val();
                    var masterModelId = $('#master-model-id-' + i).val();
                    var dataid = $('#quantity-' + i).attr('data-id');
                    var price = $('#unit-price-' + i).val();
                    var existingQuantity = $('#item-quantity-selected-' + dataid).val();
                    var latestQty = parseInt(existingQuantity) + parseInt(qty);
                    $('#item-quantity-selected-' + dataid).val(latestQty);
                    var unitPrices = price * qty;
                    var sum = parseInt(sum) + parseInt(unitPrices);
                    $('#total-price').val(sum);

                    for (var j = 0; j < qty; j++) {
                        var newRow = $('<div class="row row-space"></div>');
                        var masterModelCol = $('<input type="hidden" id="model-id" name="master_model_id[]" value="' + masterModelId + '" >');
                        var variantCol = $('<div class="col-lg-1 col-md-6"><input type="text" id="variant-id"  name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>');
                        var brandCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="brand[]" value="' + brand + '" class="form-control" readonly></div>');
                        var masterModelLineCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="master_model_line[]" value="' + masterModelLine + '" class="form-control" readonly></div>');
                        var detailCol = $('<div class="col-lg-2 col-md-6"><input type="text" name="detail[]" value="' + detail + '" class="form-control" readonly></div>');
                        var exColourCol = $('<div class="col-lg-1 col-md-6"><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></div>');
                        var intColourCol = $('<div class="col-lg-1 col-md-6"><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></div>');
                        var vinCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="vin[]" class="form-control" placeholder="VIN"></div>');
                        var estimatedCol = $('<div class="col-lg-1 col-md-6"><input type="date" name="estimated_arrival[]" class="form-control"></div>');
                        var engineNumber = $('<div class="col-lg-1 col-md-6"><input type="text" name="engine_number[]" class="form-control"></div>');
                        var unitPrice = $('<div class="col-lg-1 col-md-6"><input type="text" value="' + price + '" name="unit_prices[]" readonly class="form-control"></div>');
                        var removeBtn = $('<div class="col-lg-1 col-md-6"><button type="button" data-unit-price="' + price + '" data-approved-id="' + dataid + '" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></div>');
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
                        newRow.append(masterModelCol, variantCol, brandCol, masterModelLineCol, detailCol, exColourCol, intColourCol, estimatedCol, engineNumber, unitPrice, vinCol, removeBtn);
                        $('#VehiclevariantRowsContainer').append(newRow);


                    }
                }

            }

            $('#VehiclevariantRowsContainer').show();
            // }

        });

        $(document).on('click', '.remove-row-btn', function () {

            $(this).closest('.row').remove();

            var Id = $(this).attr('data-approved-id');
            var selectedQuantity = $('.qty-' + Id).val();

            var variantQuantity = $('.qty-' + Id).attr('data-quantity');
            var remainingQty = parseInt(variantQuantity) + 1;
            $('.qty-' + Id).attr('data-quantity', remainingQty);

            var latestQuantity = parseInt(selectedQuantity) + 1;
            $('.qty-' + Id).val(latestQuantity);
            var previousCount = $('#item-quantity-selected-' + Id).val();
            var latestCount = previousCount - 1;
            $('#item-quantity-selected-' + Id).val(latestCount);

            if ($('#VehiclevariantRowsContainer').find('.row').length === 1) {
                $('.bar').hide();
                $('#VehiclevariantRowsContainer').hide();
            }

            var price = $(this).attr('data-unit-price');
            var totalPrice = $('#total-price').val();
            var remainingPrice = totalPrice - price;
            $('#total-price').val(remainingPrice);

        });

        $('.variants').on('change', function () {
            var key = $(this).attr('data-key');
            console.log(key);
            var model = $(this).find('option:selected').attr("data-model-id");
            var brand = $(this).find('option:selected').attr("data-brand");
            var modelLine = $(this).find('option:selected').attr("data-model-line");
            var variantDetail = $(this).find('option:selected').attr("data-variant-detail");

            $('#master-model-id-' + key).val(model);
            $('#brand-' + key).val(brand);
            $('#master-model-line-' + key).val(modelLine);
            $('#variant-detail-' + key).val(variantDetail);
        });

        $('#submit-add-more-vehicle-button').click(function (e) {
            e.preventDefault();

            var variantIds = $('input[name="variant_id[]"]').map(function () {
                return $(this).val();
            }).get();

            if (variantIds.length === 0) {
                alertify.alert('Please select variant quantity and and add vehicles.').set({title: "Alert !"});
                formValid = false;
            } else {
                // alert("variant is there");
                formValid = true;
                checkDuplicateVIN();
            }
            if (formValid == true) {
                $('#po-create-form').unbind('submit').submit();
            }
        });
    });
</script>
