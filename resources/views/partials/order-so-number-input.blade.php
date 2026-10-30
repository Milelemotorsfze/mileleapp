@php
    $soNumberName = $soNumberName ?? 'so_number';
    $stockTypeName = $stockTypeName ?? 'stock_type';
    $labelClass = $labelClass ?? 'form-label';
    $inputClass = $inputClass ?? 'form-control';
    $labelSuffix = $labelSuffix ?? '';
    // Grid column class for the whole field (e.g. 'col-lg-2'), so a hidden field leaves no empty column.
    $wrapperClass = $wrapperClass ?? null;
    // Optional grid columns for a label/input row layout (e.g. 'col-md-4 p-3' / 'col-md-8 p-3').
    $labelColumnClass = $labelColumnClass ?? null;
    $inputColumnClass = $inputColumnClass ?? null;
    $shownDisplay = $labelColumnClass ? 'contents' : 'block';
    $soNumberTypes = \App\Support\OrderStockType::soNumberTypes();
    $showSoNumber = \App\Support\OrderStockType::allowsSoNumber(old($stockTypeName, $selectedStockType ?? null));
@endphp
<div id="{{ $soNumberName }}_wrapper" style="display: {{ $showSoNumber ? $shownDisplay : 'none' }};" @if($wrapperClass) class="{{ $wrapperClass }}" @endif>
    @if($labelColumnClass)<div class="{{ $labelColumnClass }}">@endif
    <label for="{{ $soNumberName }}" class="{{ $labelClass }}">SO Number{{ $labelSuffix }}</label>
    @if($labelColumnClass)</div>@endif
    @if($inputColumnClass)<div class="{{ $inputColumnClass }}">@endif
    <input type="text" class="{{ $inputClass }}" id="{{ $soNumberName }}" name="{{ $soNumberName }}" maxlength="100"
           placeholder="Enter SO Number" value="{{ old($soNumberName, $selectedSoNumber ?? '') }}" @if(!$showSoNumber) disabled @endif>
    @if($inputColumnClass)</div>@endif
</div>
<script>
    (function () {
        var soNumberTypes = @json($soNumberTypes);
        var shownDisplay = @json($shownDisplay);
        var stockType = document.getElementById(@json($stockTypeName));
        var wrapper = document.getElementById(@json($soNumberName . '_wrapper'));
        var input = document.getElementById(@json($soNumberName));
        if (!stockType || !wrapper || !input) {
            return;
        }
        stockType.addEventListener('change', function () {
            var show = soNumberTypes.indexOf(stockType.value) !== -1;
            wrapper.style.display = show ? shownDisplay : 'none';
            input.disabled = !show;
            if (!show) {
                input.value = '';
            }
        });
    })();
</script>
