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
                <div class="col-md-4 mb-3">
             <label for="model_line">Model Line</label>
                <select class="form-control model-line" name="master_model_lines_id[]">
                    <option value="">Select Model Line</option>
                    @php
                        $uniqueVariantIds = [];
                    @endphp
                    @foreach ($modelLines as $quotationItemId => $variants)
                        @foreach ($variants as $variantId => $modelLine)
                            @if (!in_array($variantId, $uniqueVariantIds))
                                @php
                                    $uniqueVariantIds[] = $variantId;
                                @endphp
                                <option value="{{ $variantId }}">{{ $modelLine }}</option>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>
                    <div class="col-md-2 mb-3">
                        <label for="today_date">Interior Colour</label>
                        <select class="form-control int-colour" name="int_colour[]">
                            @foreach($intcolourcode as $incolourcode)
                            <option value="{{ $incolourcode->id }}">{{ $incolourcode->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="today_date">Exterior Colour</label>
                        <select class="form-control ex-colour" name="ex_colour[]">
                            @foreach($excolourcode as $colourcodeex)
                            <option value="{{ $colourcodeex->id }}">{{ $colourcodeex->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="model_year">Model Year</label>
                        <select class="form-control model-year" name="modelyear[]">
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for ($i = $currentYear + 1; $i >= $currentYear - 5; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="today_date">QTY</label>
                        <input type="number" class="form-control qty" name="qty[]" value="">
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="addRow()">Add More</button>
            <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
			        </div>  
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
     $(document).ready(function() {
        // Apply Select2 to the dropdowns in the first row
        $('.model-line').select2();
        $('.int-colour').select2();
        $('.ex-colour').select2();
        $('.model-year').select2();
    });
        function addRow() {
        var newRow = document.createElement('div');
        newRow.classList.add('row', 'dynamicRow');

        var rowHtml = `
        <div class="col-md-4 mb-3">
                <select class="form-control model-line" name="master_model_lines_id[]">
                    <option value="">Select Model Line</option>
                    @php
                        $uniqueVariantIds = [];
                    @endphp
                    @foreach ($modelLines as $quotationItemId => $variants)
                        @foreach ($variants as $variantId => $modelLine)
                            @if (!in_array($variantId, $uniqueVariantIds))
                                @php
                                    $uniqueVariantIds[] = $variantId;
                                @endphp
                                <option value="{{ $variantId }}">{{ $modelLine }}</option>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <select class="form-control int-colour" name="int_colour[]">
                    @foreach($intcolourcode as $incolourcode)
                    <option value="{{ $incolourcode->id }}">{{ $incolourcode->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <select class="form-control ex-colour" name="ex_colour[]">
                    @foreach($excolourcode as $colourcodeex)
                    <option value="{{ $colourcodeex->id }}">{{ $colourcodeex->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <select class="form-control model-year" name="modelyear[]">
                    @php
                        $currentYear = date('Y');
                    @endphp
                    @for ($i = $currentYear + 1; $i >= $currentYear - 5; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <input type="number" class="form-control qty" name="qty[]" value="">
            </div>
        `;

        newRow.innerHTML = rowHtml;
        document.getElementById('dynamicRows').appendChild(newRow);

        // Apply Select2 to the newly added select elements
        $('.model-line').select2();
        $('.int-colour').select2();
        $('.ex-colour').select2();
        $('.model-year').select2();
    }
</script>
@endpush
