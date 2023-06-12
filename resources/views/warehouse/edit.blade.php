@extends('layouts.main')
<style>
.upernac {
    margin-top: 1.8rem!important;
}
.select2-container {
  width: 100% !important;
}
.form-label[for="basicpill-firstname-input"] {
  margin-top: 12px;
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
  @media (max-width: 767px) {
    .col-lg-12.col-md-12 {
      text-align: center;
    }
  }
.error 
    {
        color: #FF0000;
    }
    .iti 
    { 
        width: 100%; 
    }
    label {
  display: inline-block;
  margin-right: 10px;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    -moz-appearance: none;
    appearance: none; 
    margin: 0; 
}
.error-text{
    color: #FF0000;
}
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@can('create-po-details')
@php
    $exColours = \App\Models\ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
    $intColours = \App\Models\ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
@endphp
<div class="card-header">
        <h4 class="card-title">New Purchasing Order</h4>
        <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div> 
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
        {!! Form::model($purchasingOrder, ['route' => ['purchasing-order.update', $purchasingOrder->id], 'method' => 'PATCH', 'id' => 'purchasing-order']) !!}
    <div class="row">
        <div class="col-lg-2 col-md-6">
            <span class="error">* </span>
            <label for="basicpill-firstname-input" class="form-label">PO Date : </label>
            <input type="Date" id="po_date" name="po_date" value="{{$purchasingOrder->po_date}}"class="form-control" placeholder="PO Date" readonly>
        </div>
        <div class="col-lg-2 col-md-6">
            <span class="error">* </span>
            <label for="basicpill-firstname-input" class="form-label">PO Number : </label>
            <input type="number" id="po_number" name="po_number" class="form-control" value="{{$purchasingOrder->po_number}}" placeholder="PO Number" readonly>
            <span id="poNumberError" class="error" style="display: none;"></span>
        </div>
        <div class="col-lg-2 col-md-6">
            <span class="error">* </span>
            <label for="basicpill-firstname-input" class="form-label">Supplier : </label>
            <input type="number" id="po_number" name="po_number" class="form-control" value="{{$purchasingOrder->supplier}}" placeholder="Supplier Name" readonly>
            <span id="poNumberError" class="error" style="display: none;"></span>
        </div>
    </div>
    <div id="variantRowsContainer">
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
        <div class="col-lg-5 col-md-6">
            <label for="QTY" class="form-label">Variants Detail:</label>
        </div>
        <div class="col-lg-1 col-md-6">
            <label for="exColour" class="form-label">Exterior Color:</label>
        </div>
        <div class="col-lg-1 col-md-6">
            <label for="intColour" class="form-label">Interior Color:</label>
        </div>
        <div class="col-lg-1 col-md-6">
            <label for="QTY" class="form-label">VIN:</label>
        </div>
    </div>
    @foreach ($vehicles as $vehicles)
    <div class="row">
    <div class="col-lg-1 col-md-6">
    @php
    $variant = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
    $name = $variant->name;
    $detail = $variant->detail;
    $brands_id = $variant->brands_id;
    $master_model_lines_id = $variant->master_model_lines_id;
    $brand = DB::table('brands')->where('id', $brands_id)->first();
    $brand_names = $brand->brand_name;
    $master_model_lines_ids = DB::table('master_model_lines')->where('id', $master_model_lines_id)->first();
    $model_line = $master_model_lines_ids->model_line;
    @endphp 
        <input type="text" name="oldvariant_id[]" value="{{$name}}" class="form-control" readonly>
        </div>
        <div class="col-lg-1 col-md-6">
        <input type="text" name="oldbrand[]" value="{{$brand_names}}" class="form-control" readonly>
        </div>
        <div class="col-lg-1 col-md-6">
        <input type="text" name="oldmodel_line[]" value="{{$model_line}}" class="form-control" readonly>
        </div>
        <div class="col-lg-5 col-md-6">
        <input type="text" name="olddetail[]" value="{{$detail}}" class="form-control" readonly>
        </div>
        <div class="col-lg-1 col-md-6">
    <select name="oldex_colour[]" class="form-control" placeholder="Exterior Color">
        <option value="">Exterior Color</option>
        @foreach ($exColours as $id => $exColour)
            @if ($id == $vehicles->ex_colour)
                <option value="{{ $id }}" selected>{{ $exColour }}</option>
            @else
                <option value="{{ $id }}">{{ $exColour }}</option>
            @endif
        @endforeach
    </select>
</div>

<div class="col-lg-1 col-md-6">
    <select name="oldint_colour[]" class="form-control" placeholder="Interior Color">
        <option value="">Interior Color</option>
        @foreach ($intColours as $id => $intColour)
            @if ($id == $vehicles->int_colour)
                <option value="{{ $id }}" selected>{{ $intColour }}</option>
            @else
                <option value="{{ $id }}">{{ $intColour }}</option>
            @endif
        @endforeach
    </select>
</div>
        <div class="col-lg-1 col-md-6">
        <input type="text" name="oldvin[]" value="{{$vehicles->vin}}" class="form-control" placeholder="VIN">
        <input type="hidden" name="id[]" value="{{$vehicles->id}}" class="form-control" placeholder="VIN">
		</div>
		</div>
        <br>
    @endforeach
    </div>
    </div>
    <div class="bar">Add New Vehicles Into Stock</div>
        <div class="row">
            <div class="col-lg-2 col-md-6">
                <label for="brandInput" class="form-label">Variants:</label>
                <input type="text" placeholder="Select Variants" name="variant_ider[]" list="variantslist" class="form-control mb-1" id="variants_id">
                <datalist id="variantslist">
    @foreach ($variants as $variant)
    <option value="{{ $variant->name }}" data-value="{{ $variant->id }}" data-detail="{{ $variant->detail }}" data-brands_id="{{ $variant->brand_name }}" data-master_model_lines_id="{{ $variant->model_line }}">{{ $variant->name }}</option>
    @endforeach
</datalist>
                </div>
                <div class="col-lg-1 col-md-6">
        <label for="QTY" class="form-label">Brand:</label>
        <input type="text" id="brands_id" name="brands_id" class="form-control" placeholder="Brand" readonly>
    </div>
    <div class="col-lg-3 col-md-6">
        <label for="QTY" class="form-label">Model Line:</label>
        <input type="text" id="master_model_lines_id" name="master_model_lines_id" class="form-control" placeholder="Model Line" readonly>
    </div>
    <div class="col-lg-4 col-md-6">
        <label for="QTY" class="form-label">Variants Detail:</label>
        <input type="text" id="details" name="details" class="form-control" placeholder="Variants Detail" readonly>
    </div>
    <div class="col-lg-1 col-md-6">
        <label for="QTY" class="form-label">QTY:</label>
        <input type="number" id="QTY" name="QTY" class="form-control" placeholder="QTY">
    </div>
                <div class="col-lg-1 col-md-6 upernac">
                    <div class="btn btn-primary add-row-btn">
                        <i class="fas fa-plus"></i> Add More
                    </div>
                </div>
            </div>
<br>
<br>
    <div class="col-lg-12 col-md-12">
        <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
    </div>
{!! Form::close() !!}
		</br>
    </div>
    @endcan
@endsection
@push('scripts')
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
</style>
<script>
$(document).ready(function() {
    $('#variants_id').on('input', function() {
        var selectedVariant = $(this).val();
        var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
        if (variantOption.length > 0) {
            var detail = variantOption.data('detail');
            var brands_id = variantOption.data('brands_id');
            var master_model_lines_id = variantOption.data('master_model_lines_id');
            $('#details').val(detail);
            $('#brands_id').val(brands_id);
            $('#master_model_lines_id').val(master_model_lines_id);
            $('#SelectVariantsId').val(selectedVariant);
        }
    });


    $('.add-row-btn').click(function() {
    var selectedVariant = $('#variants_id').val();
    var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
    if (variantOption.length === 0) {
        alert('Invalid variant selected');
        return;
    }
    var qty = $('#QTY').val();
    var detail = variantOption.data('detail');
    var brand = variantOption.data('brands_id');
    var masterModelLine = variantOption.data('master_model_lines_id');
    $('.bar').show();

    // Move the declaration and assignment inside the click event function
    var exColours = <?= json_encode($exColours) ?>;
    var intColours = <?= json_encode($intColours) ?>;

    for (var i = 0; i < qty; i++) {
            var newRow = $('<div class="row row-space"></div>');
            var variantCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>');
            var brandCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="brand[]" value="' + brand + '" class="form-control" readonly></div>');
            var masterModelLineCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="master_model_line[]" value="' + masterModelLine + '" class="form-control" readonly></div>');
            var detailCol = $('<div class="col-lg-5 col-md-6"><input type="text" name="detail[]" value="' + detail + '" class="form-control" readonly></div>');
            var exColourCol = $('<div class="col-lg-1 col-md-6"><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></div>');
            var intColourCol = $('<div class="col-lg-1 col-md-6"><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></div>');
            var vinCol = $('<div class="col-lg-1 col-md-6"><input type="text" name="vin[]" class="form-control" placeholder="VIN"></div>');
            var removeBtn = $('<div class="col-lg-1 col-md-6"><button type="button" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></div>');
            
            // Populate Exterior Colors dropdown
var exColourDropdown = exColourCol.find('select');
for (var id in exColours) {
    if (exColours.hasOwnProperty(id)) {
        exColourDropdown.append($('<option></option>').attr('value', id).text(exColours[id]));
}
}

// Populate Interior Colors dropdown
var intColourDropdown = intColourCol.find('select');
for (var id in intColours) {
    if (intColours.hasOwnProperty(id)) {
        intColourDropdown.append($('<option></option>').attr('value', id).text(intColours[id]));
    }
}
            newRow.append(variantCol, brandCol, masterModelLineCol, detailCol, exColourCol, intColourCol, vinCol, removeBtn);
            $('#variantRowsContainer').append(newRow);
        }
        $('#variants_id').val('');
        $('#QTY').val('');
        $('#variantRowsContainer').show();
    });

    $(document).on('click', '.remove-row-btn', function() {
        var variant = $(this).closest('.row').find('input[name="variant_id[]"]').val();
        var existingOption = $('#variantslist').find('option[value="' + variant + '"]');
        if (existingOption.length === 0) {
            var variantOption = $('<option value="' + variant + '">' + variant + '</option>');
            $('#variantslist').append(variantOption);
        }
        $(this).closest('.row').remove();
        $('.row-space').each(function() {
            if ($(this).next().length === 0) {
                $(this).removeClass('row-space');
            }
        });
        if ($('#variantRowsContainer').find('.row').length === 1) {
            $('.bar').hide();
            $('#variantRowsContainer').hide();
        }
    });
});
</script>
@endpush