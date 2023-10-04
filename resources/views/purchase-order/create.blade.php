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
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13">PO Name</label>
                            <input type="text" name="po_name" class="form-control" placeholder="Enter PO Name" value="{{old('po_name')}}">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 ">PO Number</label>
                            <input type="text" name="po_number" id="po_number" class="form-control" placeholder="Enter PO Number" value="{{old('po_number')}}">
                            <span id="poNumberError" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 ">PO Date</label>
                            <input type="date" name="po_date" id="po_date" class="form-control" placeholder="Enter PO Date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            <span id="poNumberError" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 ">Vendor</label>
                            <select class="form-control" name="vendors_id" id="vendors_id" >
                                @foreach($vendors as $vendor)
                                    <option value="{{$vendor->id}}"> {{$vendor->supplier}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
{{--                    <div class="col-lg-3 col-md-6 col-sm-12">--}}
{{--                        <div class="mb-3">--}}
{{--                            <label for="choices-single-default" class="form-label font-size-13 ">PO Type</label>--}}
{{--                            <select class="form-control" name="po_type" id="po_type" >--}}
{{--                                <option value="Normal"> Normal</option>--}}
{{--                                <option value="Payment Adjustment"> Payment Adjustment</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                <div id="variantRowsContainer" style="display: none;">
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
                        <div class="col-lg-3 col-md-6">
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
                            <label for="exColour" class="form-label">Territory:</label>
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
                            <label for="brandInput" class="form-label">Variant:</label>
                        </div>
                        <div class="col-lg-1 col-md-6">
                            <label for="QTY" class="form-label">Brand:</label>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="QTY" class="form-label">Model Line:</label>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <label for="QTY" class="form-label">Variant Detail:</label>
                        </div>
                        <div class="col-lg-1 col-md-6">
                            <label for="QTY" class="form-label">QTY:</label>
                        </div>
                    </div>
                    @foreach($pfiVehicleVariants as $key => $pfiVehicleVariant)
                        <div class="row">
                            <div class="col-lg-2 col-md-6">
                                <input type="text" placeholder="Select Variants" name="variant[]" list="variantslist"
                                       class="form-control mb-1" id="variant-id-{{$key}}" autocomplete="off"
                                       data-id="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant_id}}"
                                       value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->name ?? ''}}" readonly>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="text"  name="brands_id" class="form-control" placeholder="Brand" id="brand-{{$key}}"
                                       value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->brand->brand_name ?? ''}}" readonly>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <input type="text" name="master_model_lines_id" class="form-control" id="master-model-line-{{$key}}"
                                       value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->master_model_lines->model_line ?? ''}}"
                                       placeholder="Model Line" readonly>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <input type="text" id="variant-detail-{{$key}}" name="details" class="form-control"  placeholder="Variants Detail" readonly
                                       value="{{$pfiVehicleVariant->letterOfIndentItem->masterModel->variant->detail ?? ''}}">
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" id="quantity-{{$key}}"  oninput="checkQuantity({{$key}})" data-quantity="{{$pfiVehicleVariant->quantity}}"  class="form-control"
                                       value="{{ $pfiVehicleVariant->quantity }}" placeholder="QTY">
                                <span class="QuantityError-{{$key}} text-danger"></span>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-12 justify-content-end">
                        <button type="button" class="btn btn-primary float-end add-row-btn">
                            <i class="fas fa-plus"></i> Add Vehicles
                        </button>
                    </div>
                </div>
                  <div class="row">
                      <button type="submit" class="btn btncenter btn-success" >Submit</button>
                  </div>
            </form>
    </div>
@endsection
@push('scripts')
<script>
    let formValid = true;
    function checkQuantity(key) {
        var selectedQuantity = $('#quantity-'+key).val();
        var variantQuantity = $('#quantity-'+key).attr('data-quantity');

        if(selectedQuantity > variantQuantity) {
            formValid = false;
            $('.QuantityError-'+key).text("Please Enter Quantity less than "+variantQuantity)
        }else{
            formValid = true;
            $('.QuantityError-'+key).text("");
        }
    }
    $('.add-row-btn').click(function() {

        if(formValid == true) {
            $('.bar').show();
            var variantQuantity = '{{ $pfiVehicleVariants->count() }}';

            // Move the declaration and assignment inside the click event function
            var exColours = <?= json_encode($exColours) ?>;
            var intColours = <?= json_encode($intColours) ?>;
            for (var i = 0; i < variantQuantity; i++) {
                var qty = $('#quantity-'+i).val();
                var selectedVariant = $('#variant-id-'+i).val();
                var brand = $('#brand-'+i).val();
                var masterModelLine = $('#master-model-line-'+i).val();
                var detail = $('#variant-detail-'+i).val();

                for (var j = 0; j < qty; j++) {
                    var newRow = $('<div class="row row-space"></div>');
                    var variantCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>');
                    var brandCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="brand[]" value="' + brand + '" class="form-control" readonly></div>');
                    var masterModelLineCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="master_model_line[]" value="' + masterModelLine + '" class="form-control" readonly></div>');
                    var detailCol = $('<div class="col-lg-3 col-md-6"><input type="text" name="detail[]" value="' + detail + '" class="form-control" readonly></div>');
                    var exColourCol = $('<div class="col-lg-1 col-md-6"><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></div>');
                    var intColourCol = $('<div class="col-lg-1 col-md-6"><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></div>');
                    var vinCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="vin[]" class="form-control" placeholder="VIN"></div>');
                    var estimatedCol = $('<div class="col-lg-1 col-md-6"><input type="date" name="estimated_arrival[]" class="form-control"></div>');
                    var territory = $('<div class="col-lg-1 col-md-6"><input type="text" name="territory[]" class="form-control"></div>');
                    var removeBtn = $('<div class="col-lg-1 col-md-6"><button type="button" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></div>');
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
                    newRow.append(variantCol, brandCol, masterModelLineCol, detailCol, exColourCol, intColourCol, estimatedCol, territory, vinCol, removeBtn);
                    $('#variantRowsContainer').append(newRow);
                }
            }

            $('#variantRowsContainer').show();
        }

    });
    $(document).on('click', '.remove-row-btn', function() {
        // var variant = $(this).closest('.row').find('input[name="variant_id[]"]').val();
        // var existingOption = $('#variantslist').find('option[value="' + variant + '"]');
        // if (existingOption.length === 0) {
        //     var variantOption = $('<option value="' + variant + '">' + variant + '</option>');
        //     $('#variantslist').append(variantOption);
        // }
        $(this).closest('.row').remove();
        // $('.row-space').each(function() {
        //     if ($(this).next().length === 0) {
        //         $(this).removeClass('row-space');
        //     }
        // });
        if ($('#variantRowsContainer').find('.row').length === 1) {
            $('.bar').hide();
            $('#variantRowsContainer').hide();
        }
    });
    $('#po_number').on('change', function() {
        var poNumber = $(this).val();
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
</script>
@endpush

