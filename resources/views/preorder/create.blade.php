@extends('layouts.table')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            Pre Order Vehicles
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </h4>
    </div>
    <div class="card-body">
    <form action="{{ route('preorder.storespreorder', ['QuotationId' => $quotation->id]) }}" id="form-create" method="POST">
        @csrf
        <div id="dynamicRows">
            <div class="row dynamicRow">
                <div class="col-md-2 mb-3">
                    <label for="model_line">Variant</label>
                    <select class="form-control model-line" name="variant_id[]">
                        <option value="">Select Variant</option>
                        @foreach ($variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="qty">QTY</label>
                    <input type="number" class="form-control qty" name="qty[]" value="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="notes">Notes</label>
                    <input type="text" class="form-control notes" name="notes[]" value="">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" onclick="addRow()">Add More</button>
        <div class="col-lg-12 col-md-12 text-center mt-3">
            <input type="submit" name="submit" value="Submit" class="btn btn-success" />
        </div>  
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.model-line').select2();
});
function addRow() {
    var newRow = document.createElement('div');
    newRow.classList.add('row', 'dynamicRow');
    var rowHtml = `
        <div class="col-md-2 mb-3">
            <label for="model_line">Variant</label>
           <select class="form-control model-line" name="variant_id[]">
                   @foreach ($variants as $variant)
    <option value="{{ $variant->id }}">{{ $variant->name }}</option>
@endforeach
                </select>
        </div>
        <div class="col-md-2 mb-3">
            <label for="qty">QTY</label>
            <input type="number" class="form-control qty" name="qty[]" value="">
        </div>
        <div class="col-md-6 mb-3">
                    <label for="notes">Notes</label>
                    <input type="text" class="form-control notes" name="notes[]" value="">
                </div>
    `;
    newRow.innerHTML = rowHtml;
    document.getElementById('dynamicRows').appendChild(newRow);
    $(newRow).find('.model-line').select2();
}
</script>
@endpush
