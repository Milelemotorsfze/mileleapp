@extends('layouts.main')
@section('content')
@if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6')
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
        <input type="Date" id="date" name="date" class="form-control" placeholder="Date" required>
        </div>
        </div>
        <br>
        <div id="rows-container">
        <div class="row">
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Vin </label>
            <input type="text" placeholder="Select VIN" name="vin[]" list="vinlist" class="form-control mb-1" id="vin">
                <datalist id="vinlist">
                 @foreach ($vehicles as $vin)
                <option value="{{ $vin }}">{{ $vin }}</option>
                @endforeach
            </datalist>
        </div>
        <div class="col-lg-1 col-md-6">
        <label for="basicpill-firstname-input" class="form-label">From </label>
        <select name="from[]" class="form-control mb-1" id="from" readonly>
        @foreach ($warehouses as $warehouse)
        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
        @endforeach
    </select>
</div>
<div class="col-lg-1 col-md-6">
<label for="basicpill-firstname-input" class="form-label">To </label>
    <select name="to[]" class="form-control mb-1" id="to">
        @foreach ($warehouses as $warehouse)
        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
        <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
    </div>
</form>
		</br>
    </div>
    @endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var row = 1;
        
        $('.add-row-btn').click(function() {
            row++;
            var newRow = `
            <div class="row" data-row="${row}">
                <div class="col-lg-2 col-md-6">
                    <input type="text" placeholder="Select VIN" name="vin[]" list="vinlist" class="form-control mb-1 vin" id="vin${row}">
                    <datalist id="vinlist">
                        @foreach ($vehicles as $vin)
                        <option value="{{ $vin }}">{{ $vin }}</option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-lg-1 col-md-6">
                    <select name="from[]" class="form-control mb-1" id="from${row}" readonly>
                        @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-1 col-md-6">
                    <select name="to[]" class="form-control mb-1" id="to${row}">
                        @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
        });
        
        $('#rows-container').on('change', '.vin', function() {
            var selectedVin = $(this).val();
            var row = $(this).closest('.row').data('row');
            var brandField = $('#brand' + row);
            var fromField = $('#from' + row);
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
        $('#vin').change(function() {
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
                    $('#model-line').val(response.modelLine);
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