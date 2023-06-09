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
        {!! Form::open(array('route' => 'purchasing-order.store','method'=>'POST', 'id' => 'purchasing-order')) !!}
    <div class="row">
        <div class="col-lg-2 col-md-6">
            <span class="error">* </span>
            <label for="basicpill-firstname-input" class="form-label">PO Date : </label>
            <input type="Date" id="po_date" name="po_date" class="form-control" placeholder="PO Date" required>
        </div>
        <div class="col-lg-2 col-md-6">
            <span class="error">* </span>
            <label for="basicpill-firstname-input" class="form-label">PO Number : </label>
            <input type="number" id="po_number" name="po_number" class="form-control" placeholder="PO Number" required>
            <span id="poNumberError" class="error" style="display: none;"></span>
        </div>
    </div>
    <div id="variantRowsContainer" style="display: none;">
    <div class="bar">Stock Vehicles</div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <label for="brandInput" class="form-label">Variants:</label>
        </div>
        <div class="col-lg-6 col-md-6">
            <label for="QTY" class="form-label">Variants Detail:</label>
        </div>
        <div class="col-lg-3 col-md-6">
            <label for="QTY" class="form-label">VIN:</label>
        </div>
    </div>
    </div>
        <div class="bar">Add New Vehicles Into Stock</div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <label for="brandInput" class="form-label">Variants:</label>
                <input type="text" placeholder="Select Variants" name="variant_ider[]" list="variantslist" class="form-control mb-1" id="variants_id">
                <datalist id="variantslist">
                    @foreach ($variants as $variant)
                    <option value="{{ $variant->name }}" data-value="{{ $variant->id }}" data-detail="{{ $variant->detail }}">{{ $variant->name }}</option>
                    @endforeach
                </datalist>
                </div>
                <div class="col-lg-6 col-md-6">
                    <label for="QTY" class="form-label">Variants Detail:</label>
                    <input type="text" id="details" name="details" class="form-control" placeholder="Variants Detail" readonly>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="QTY" class="form-label">QTY:</label>
                    <input type="number" id="QTY" name="QTYer" class="form-control" placeholder="QTY">
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
        $('#details').val(detail);
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
            var selectedVariant = $('#variants_id').val();
            var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
            var detail = variantOption.data('detail');
            $('.bar').show();
            for (var i = 0; i < qty; i++) {
                var newRow = $('<div class="row row-space"></div>');
                var variantCol = $('<div class="col-lg-3 col-md-6"><input type="text" name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>');
                var detailCol = $('<div class="col-lg-6 col-md-6"><input type="text" name="detail[]" value="' + detail + '" class="form-control" readonly></div>');
                var vinCol = $('<div class="col-lg-2 col-md-6"><input type="text" name="vin[]" class="form-control" placeholder="VIN"></div>');
                var removeBtn = $('<div class="col-lg-1 col-md-6"><button type="button" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></div>');
                newRow.append(variantCol, detailCol, vinCol, removeBtn);
                $('#variantRowsContainer').append(newRow);
            }
            $('#variants_id').val('');
            $('#details').val('');
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
    $(document).ready(function() {
    $('#po_number').on('blur', function() {
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
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    alert("PO Number Already Existing");
                }
            }
        });
    });
});
</script>
@endpush