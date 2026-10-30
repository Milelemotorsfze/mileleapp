@php
    $stockTypeLabel = $stockTypeLabel ?? 'Type';
    $stockTypeName = $stockTypeName ?? 'stock_type';
    $hideLabel = $hideLabel ?? false;
    $labelClass = $labelClass ?? 'form-label';
    $inputClass = $inputClass ?? 'form-control';
    $requiredMarkerClass = $requiredMarkerClass ?? 'text-danger';
    $labelSuffix = $labelSuffix ?? '';
    $useStrongLabel = $useStrongLabel ?? false;
    $labelFor = $labelFor ?? $stockTypeName;
    $autofocus = $autofocus ?? false;
    $markerInLabel = $markerInLabel ?? false;
    $selectedStockType = \App\Support\OrderStockType::normalize(old($stockTypeName, $selectedStockType ?? null));
@endphp
@if (!$hideLabel)
@if ($markerInLabel)
<label for="{{ $labelFor }}" class="{{ $labelClass }}"><span class="{{ $requiredMarkerClass }}">*</span> {{ $stockTypeLabel }}{{ $labelSuffix }}</label>
@elseif ($useStrongLabel)
<span class="{{ $requiredMarkerClass }}">* </span>
<label for="{{ $labelFor }}"><strong>{{ $stockTypeLabel }}{{ $labelSuffix }}</strong></label>
@else
<span class="{{ $requiredMarkerClass }}">* </span>
<label for="{{ $labelFor }}" class="{{ $labelClass }}">{{ $stockTypeLabel }}{{ $labelSuffix }}</label>
@endif
@endif
<select class="{{ $inputClass }}" id="{{ $stockTypeName }}" name="{{ $stockTypeName }}" required @if($autofocus) autofocus @endif>
    <option value="" disabled {{ empty($selectedStockType) ? 'selected' : '' }}>Select {{ $stockTypeLabel }}</option>
    @foreach (\App\Support\OrderStockType::options() as $option)
        <option value="{{ $option }}" @selected($selectedStockType === $option)>{{ $option }}</option>
    @endforeach
</select>
