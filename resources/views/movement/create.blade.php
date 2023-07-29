@extends('layouts.main')
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">Add New Vehicles Transaction</h4>
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
        <div id="rows-container">
        <div class="row">
        <div class="col-lg-2 col-md-6">
    <label for="basicpill-firstname-input" class="form-label">PO Number</label>
    <select name="vin[]" class="form-control mb-1" id="vin-input" required>
        <option value="" selected disabled>Select PO</option>
        @foreach ($purchasing_order as $purchasing_order)
        <option value="{{ $vin }}">{{ $purchasing_order }}</option>
        @endforeach
    </select>
</div>
        <div class="col-lg-2 col-md-6">
    <label for="basicpill-firstname-input" class="form-label">Vin</label>
    <select name="vin[]" class="form-control mb-1" id="vin-input" required>
        <option value="" selected disabled>Select VIN</option>
        @foreach ($vehicles as $vin)
        <option value="{{ $vin }}">{{ $vin }}</option>
        @endforeach
    </select>
</div>
        <div class="col-lg-1 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">From </label>
        <select class="form-control mb-1" id="from" readonly disabled>
        @foreach ($warehouses as $warehouse)
        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
        @endforeach
        </select>
        <input type="hidden" name="from[]" class="form-control mb-1" id="from-input">
        </div>
<div class="col-lg-1 col-md-6">
<label for="basicpill-firstname-input" class="form-label">To </label>
    <select name="to[]" class="form-control mb-1" id="to" required>
        @foreach ($warehouses as $warehouse)
        @if ($warehouse->name !== 'Supplier')
        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
        @endif
        @endforeach
    </select>
</div>
<div class="col-lg-1 col-md-6">
        <label for="QTY" class="form-label">Brand:</label>
        <input type="text" id="brand" name="brand" class="form-control" placeholder="Variants Detail" readonly>
    </div>
    <div class="col-lg-2 col-md-6">
        <label for="QTY" class="form-label">Model Line:</label>
        <input type="text" id="model-line" name="model-line" class="form-control" placeholder="Variants Detail" readonly>
    </div>
        <div class="col-lg-2 col-md-6">
        <label for="QTY" class="form-label">Variant:</label>
        <input type="text" id="variant" name="variant" class="form-control" placeholder="Variants Detail" readonly>
    </div>
<div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Remarks </label>
            <input type="text" id="remarks" name="remarks[]" class="form-control" placeholder="Remarks">
        </div>
        </div>
        </div>
        <br>
        <div class="row">
        <div class="col-lg-2 col-md-6">
        <div class="btn btn-primary add-row-btn" data-row="1">
         <i class="fas fa-plus"></i> Add More
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
                <div class="col-lg-2 col-md-6">
                    <input type="text" id="model-line${row}" name="model-line[]" class="form-control" placeholder="Model Line" readonly>
                </div>
                <div class="col-lg-2 col-md-6">
                    <input type="text" id="variant${row}" name="variant[]" class="form-control" placeholder="Variant" readonly>
                </div>
                <div class="col-lg-2 col-md-6">
                    <input type="text" id="remarks" name="remarks[]" class="form-control" placeholder="Remarks">
                </div>
                <div class="col-lg-1 col-md-6">
                    <button type="button" class="btn btn-danger remove-row-btn" data-row="${row}">Remove</button>
                </div>
            </div>
            `;
            $('#rows-container').append(newRow);
            $('#vin' + row).select2();
        });
        $('#rows-container').on('change', '.vin', function() {
            var selectedVin = $(this).val();
            var row = $(this).closest('.row').data('row');
            var brandField = $('#brand' + row);
            var fromField = $('#from' + row);
            var fromFieldinput = $('#from-input' + row);
            var modelLineField = $('#model-line' + row);
            var variantField = $('#variant' + row);
            console.log(brandField);
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
    $(document).ready(function() {
        $('#vin-input').change(function() {
            var selectedVin = $(this).val();
            $.ajax({
                url: '{{ route('vehicles.vehiclesdetails') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    vin: selectedVin
                },
                success: function(response) {
                    console.log(response.movement);
                    $('#variant').val(response.variant);
                    $('#brand').val(response.brand);
                    $('#from').val(response.movement);
                    $('#from-input').val(response.movement);
                    $('#model-line').val(response.modelLine);
                }
            });
        });
    });
</script>
<script>
    // Function to check if VIN is duplicated
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

            // Check if VIN is empty or duplicated
            if (vin === '') {
                alert('Please select a VIN for all vehicles.');
                return false;
            } else if (isDuplicateVIN(vin)) {
                alert('Duplicate VINs are not allowed. Please select unique VINs.');
                return false;
            }

            // Store unique VINs in an array
            if (!uniqueVINs.includes(vin)) {
                uniqueVINs.push(vin);
            }
        }

        // At this point, all VINs are unique and not empty
        // You can submit the form or perform any other action here
        return true;
    }
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endpush