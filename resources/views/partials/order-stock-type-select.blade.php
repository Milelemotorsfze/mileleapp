@php
    $stockTypeLabel = $stockTypeLabel ?? 'Type';
    $stockTypeName = $stockTypeName ?? 'stock_type';
    $hideLabel = $hideLabel ?? false;
    $labelClass = $labelClass ?? 'form-label';
    $inputClass = $inputClass ?? 'form-control';
    $requiredMarkerClass = $requiredMarkerClass ?? 'text-danger';
    $labelSuffix = $labelSuffix ?? '';
    $useStrongLabel = $useStrongLabel ?? false;
    $selectedStockType = \App\Support\OrderStockType::normalize(old($stockTypeName, $selectedStockType ?? null));
@endphp
@if (!$hideLabel)
<span class="{{ $requiredMarkerClass }}">* </span>
@if ($useStrongLabel)
<label for="{{ $stockTypeName }}"><strong>{{ $stockTypeLabel }}{{ $labelSuffix }}</strong></label>
@else
<label for="{{ $stockTypeName }}" class="{{ $labelClass }}">{{ $stockTypeLabel }}{{ $labelSuffix }}</label>
@endif
@endif
<select class="{{ $inputClass }}" id="{{ $stockTypeName }}" name="{{ $stockTypeName }}" required>
    <option value="" disabled {{ empty($selectedStockType) ? 'selected' : '' }}>Select {{ $stockTypeLabel }}</option>
    @foreach (\App\Support\OrderStockType::options() as $option)
        <option value="{{ $option }}" @selected($selectedStockType === $option)>{{ $option }}</option>
    @endforeach
</select>
