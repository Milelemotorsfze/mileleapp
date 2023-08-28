@extends('layouts.main')
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">Add New Movement Transection</h4>
        <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
            @if ($lastIdExists)
    <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($movementsReferenceId - 1)]) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>Ref No: {{$movementsReferenceId}}</b>
@if ($NextIdExists)
    <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($movementsReferenceId + 1)]) }}">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
    <div class="col-lg-12">
    <div id="flashMessage"></div>
</div>
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
        <form action="{{ route('movement.store') }}" method="POST" id="purchasing-order">
        @csrf
        <div class="row">
        <div class="col-lg-2 col-md-6">
        <span class="error">* </span>
        <label for="basicpill-firstname-input" class="form-label">Date : </label>
        <input type="Date" id="date" name="date" class="form-control" placeholder="PO Date" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>
        </div>
        <br>
        <div id ="rows-containertitle">
        <div class="row">
        <div class="col-lg-2 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">Vin</label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">PO</label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">SO</label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">From</label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">To </label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="QTY" class="form-label">Brand</label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="QTY" class="form-label">Model Line</label>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="QTY" class="form-label">Variant</label>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Remarks </label>
        </div>
        </div>
        </div>
        <div id ="rows-containerpo">
        </div>
        <div id ="rows-container">
        </div>
        <br>
        <div class="row">
        <div class="col-lg-1 col-md-6">
        <div class="btn btn-primary add-row-btn" data-row="1">
         <i class="fas fa-plus"></i> Add Vehicles
        </div>
        </div>
        <div class="col-lg-4 col-md-6">
    <div class="input-group">
        <select name="po_number" class="form-control mb-1" id="po_number">
            <option value="" selected disabled>Select PO</option>
            @foreach ($purchasing_order as $purchasing_order)
            <option value="{{ $purchasing_order->id }}">{{ $purchasing_order->po_number }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary" type="button" id="generate-button">
    <i class="fas fa-cogs"></i> Generate
</button>
    </div>
</div>
<div class="col-lg-4 col-md-6">
    <div class="input-group">
        <select name="so_number" class="form-control mb-1" id="so_number">
            <option value="" selected disabled>Select SO</option>
            @foreach ($so as $so)
            <option value="{{ $so->id }}">{{ $so->so_number }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary" type="button" id="generate-sobutton">
    <i class="fas fa-cogs"></i> Generate
</button>
    </div>
</div>
        </div>
        </br>
        <div class="col-lg-12 col-md-12">
        <input type="submit" name="submit" value="Submit" onclick="return validateForm();" class="btn btn-success btncenter" />
    </div>
</form>
		</br>
    </div>
    @endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#vin-input').select2();
        $('#po_number').select2();
        $('#so_number').select2();
    });
</script>
<script>
    $(document).ready(function() {
        var row = 1;
        $('.add-row-btn').click(function() {
            row++;
            var newRow = `
            <div class="row" data-row="${row}">
                <div class="col-lg-2 col-md-6">
                    <select name="vin[]" class="form-control mb-1 vin" id="vin${row}">
                        <option value="" selected disabled>Select VIN</option>
                        @foreach ($vehicles as $vin)
                        <option value="{{ $vin }}">{{ $vin }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="po${row}" class="form-control" placeholder="PO Number" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="so_number${row}" class="form-control" placeholder="SO Number" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <select class="form-control mb-1" id="from${row}" readonly disabled>
                        @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="from[]" class="form-control mb-1" id="from-input${row}">
                </div>
                <div class="col-lg-1 col-md-6">
                    <select name="to[]" class="form-control mb-1" id="to${row}" required>
                        @foreach ($warehouses as $warehouse)
                        @if ($warehouse->name !== 'Supplier')
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="brand${row}" name="brand[]" class="form-control" placeholder="Brand" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="model-line${row}" name="model-line[]" class="form-control" placeholder="Model Line" readonly>
                </div>
                <div class="col-lg-1 col-md-6">
                    <input type="text" id="variant${row}" name="variant[]" class="form-control" placeholder="Variant" readonly>
                </div>
                <div class="col-lg-2 col-md-6">
                    <input type="text" id="remarks" name="remarks[]" class="form-control" placeholder="Remarks">
                </div>
                <div class="col-lg-1 col-md-6">
                    <button type="button" class="btn btn-danger btn-sm remove-row-btn" data-row="${row}">Remove</button>
                </div>
            </div>
            `;
            $('#rows-container').append(newRow);
            $('#vin' + row).select2();
            $('#to' + row).select2();
        });
        $('#rows-container').on('change', '.vin', function() {
            var selectedVin = $(this).val();
            var row = $(this).closest('.row').data('row');
            var brandField = $('#brand' + row);
            var fromField = $('#from' + row);
            var fromFieldinput = $('#from-input' + row);
            var SoFeildinput = $('#so_number' + row);
            var PoFeildinput = $('#po' + row);
            var modelLineField = $('#model-line' + row);
            var variantField = $('#variant' + row);
            $.ajax({
                url: '{{ route('vehicles.vehiclesdetails') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    vin: selectedVin
                },
                success: function(response) {
                    variantField.val(response.variant);
                    fromField.val(response.movement);
                    fromFieldinput.val(response.movement);
                    brandField.val(response.brand);
                    modelLineField.val(response.modelLine);
                    SoFeildinput.val(response.so_number);
                    PoFeildinput.val(response.po_number);
                }
            });
        });
        $('#rows-container').on('click', '.remove-row-btn', function() {
            var row = $(this).data('row');
            $('[data-row="' + row + '"]').remove();
        });
        $('.vin').trigger('change');
    });
</script>
<script>
    function isDuplicateVIN(vin) {
        var vinInputs = document.getElementsByName('vin[]');
        var count = 0;
        for (var i = 0; i < vinInputs.length; i++) {
            if (vinInputs[i].value === vin) {
                count++;
            }
            if (count > 1) {
                return true;
            }
        }
        return false;
    }

    // Function to handle form submission
    function validateForm() {
        var vinInputs = document.getElementsByName('vin[]');
        var uniqueVINs = [];
        for (var i = 0; i < vinInputs.length; i++) {
            var vin = vinInputs[i].value;
            if (isDuplicateVIN(vin)) {
                alert('Duplicate VINs are not allowed. Please select unique VINs.');
                return false;
            }
            if (!uniqueVINs.includes(vin)) {
                uniqueVINs.push(vin);
            }
        }
        return true;
    }
</script>
<script>
 $(document).ready(function () {
        $("#generate-button").click(function () {  // Bind to the Generate button's click event
            var selectedPOId = $("#po_number").val();  // Get the selected PO ID
            $.ajax({
                type: "GET",
                url: "{{ route('vehicles.getVehiclesDataformovement') }}",
                data: { po_id: selectedPOId },
                dataType: "json",
                success: function (response) {
                    response.forEach(function (vehicle) {
                        var rowHtml = `
                            <div class="row">
                            <div class="col-lg-2 col-md-6">
                                    <input type="text" name="vin[]" class="form-control" placeholder="VIN" readonly value="${vehicle.vin}">
                                </div>
                            <div class="col-lg-1 col-md-6">
                                    <input type="text" name="vin[]" class="form-control" placeholder="PO Number" readonly value="${vehicle.po_number}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="vin[]" class="form-control" placeholder="SO Number" readonly value="${vehicle.so_number}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                <input type="text" class="form-control mb-1" readonly value="${vehicle.warehouseNames}">
                                    <input type="hidden" name="from[]" class="form-control mb-1"value="${vehicle.warehouseName}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <select name="to[]" class="form-control mb-1" id="to" required>
                                        @foreach ($warehouses as $warehouse)
                                            @if ($warehouse->name !== 'Supplier')
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="brand" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.brand}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="model-line" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.modelLine}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="variant" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.variant}">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <input type="text" name="remarks[]" class="form-control" placeholder="Remarks">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <button type="button" class="btn btn-danger btn-sm remove-row-btn">Remove</button>
                                </div>
                            </div>
                        `;

                        $("#rows-containerpo").append(rowHtml);
                    });
                    $(".remove-row-btn").on("click", function () {
                        $(this).closest(".row").remove();
                    });
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>
<script>
 $(document).ready(function () {
        $("#generate-sobutton").click(function () {  // Bind to the Generate button's click event
            var selectedSOId = $("#so_number").val();  // Get the selected PO ID
            $.ajax({
                type: "GET",
                url: "{{ route('vehicles.getVehiclesDataformovementso') }}",
                data: { so_id: selectedSOId },
                dataType: "json",
                success: function (response) {
                    response.forEach(function (vehicle) {
                        var rowHtml = `
                            <div class="row">
                            <div class="col-lg-2 col-md-6">
                                    <input type="text" name="vin[]" class="form-control" placeholder="VIN" readonly value="${vehicle.vin}">
                                </div>
                            <div class="col-lg-1 col-md-6">
                                    <input type="text" class="form-control" placeholder="PO Number" readonly value="${vehicle.po_number}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" class="form-control" placeholder="SO Number" readonly value="${vehicle.so_number}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                <input type="text" class="form-control mb-1" readonly value="${vehicle.warehouseNames}">
                                    <input type="hidden" name="from[]" class="form-control mb-1"value="${vehicle.warehouseName}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <select name="to[]" class="form-control mb-1" id="to" required>
                                        @foreach ($warehouses as $warehouse)
                                            @if ($warehouse->name !== 'Supplier')
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="brand" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.brand}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="model-line" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.modelLine}">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <input type="text" name="variant" class="form-control" placeholder="Variants Detail" readonly value="${vehicle.variant}">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <input type="text" name="remarks[]" class="form-control" placeholder="Remarks">
                                </div>
                                <div class="col-lg-1 col-md-6">
                                    <button type="button" class="btn btn-danger btn-sm remove-row-btn">Remove</button>
                                </div>
                            </div>
                        `;

                        $("#rows-containerpo").append(rowHtml);
                    });
                    $(".remove-row-btn").on("click", function () {
                        $(this).closest(".row").remove();
                    });
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endpush