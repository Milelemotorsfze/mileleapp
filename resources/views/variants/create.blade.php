@extends('layouts.main')
@section('content')
    @can('variants-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Add New Variant</h4>
                @can('variants-list')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-list');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('variants.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    @endif
                @endcan
            </div>
            <div class="card-body">
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
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
                <form id="form-create" action="{{ route('variants.store') }}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Brand</label>
                                    <select class="form-control" autofocus name="brands_id" id="brand">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brands_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->brand_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Line</label>
                                    <select class="form-control" autofocus name="master_model_lines_id" id="model">
                                    <option value="" disabled selected>Select a Model Line</option>
                                        @foreach($masterModelLines as $masterModelLine)
                                            <option value="{{ $masterModelLine->id }}" {{ old('master_model_lines_id') == $masterModelLine->id ? 'selected' : '' }}>
                                                {{ $masterModelLine->model_line }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="coo">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">COO</label>
                                    <select class="form-control coo" name="coo" id="coo">
                                        <option value="" disabled selected>Select Country</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country }}" data-value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="my">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Year</label>
                                    @php
                                    $currentYear = date("Y");
                                    $years = range($currentYear + 10, $currentYear - 10);
                                    $years = array_reverse($years);
                                    @endphp
                                    <select name="my" class="form-control">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ old('my') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="drive_train">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Drive Train</label>
                                    <select class="form-control" autofocus name="drive_train" id="drive_train">
                                        <option value="Auto" {{ old('drive_train') == 'AWD' ? 'selected' : '' }}>AWD</option>
                                        <option value="Manual" {{ old('geadrive_trainrbox') == 'RWD' ? 'selected' : '' }}>RWD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="gear">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Gear</label>
                                    <select class="form-control" autofocus name="gearbox" id="gear">
                                        <option value="Auto" {{ old('gearbox') == 'Auto' ? 'selected' : '' }}>Auto</option>
                                        <option value="Manual" {{ old('gearbox') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="fuel">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                                    <select class="form-control" autofocus name="fuel_type" id="fuel">
                                        <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="PHEV" {{ old('fuel_type') == 'PHEV' ? 'selected' : '' }}>PHEV</option>
                                        <option value="MHEV" {{ old('fuel_type') == 'MHEV' ? 'selected' : '' }}>MHEV</option>
                                        <option value="EV" {{ old('fuel_type') == 'EV' ? 'selected' : '' }}>EV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="fuel">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Engine</label>
                                    <select class="form-control" autofocus name="engine" id="engine">
                                        <option value="0.8" {{ old('engine') == '0.8' ? 'selected' : '' }}>0.8</option>
                                        <option value="1" {{ old('engine') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.2</option>
                                        <option value="1.4" {{ old('engine') == '1.4' ? 'selected' : '' }}>1.4</option>
                                        <option value="1.5" {{ old('engine') == '1.5' ? 'selected' : '' }}>1.5</option>
                                        <option value="1.6" {{ old('engine') == '1.6' ? 'selected' : '' }}>1.6</option>
                                        <option value="1.8" {{ old('engine') == '1.8' ? 'selected' : '' }}>1.8</option>
                                        <option value="2" {{ old('engine') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="2.2" {{ old('engine') == '2.2' ? 'selected' : '' }}>2.2</option>
                                        <option value="2.4" {{ old('engine') == '2.4' ? 'selected' : '' }}>2.4</option>
                                        <option value="2.5" {{ old('engine') == '2.5' ? 'selected' : '' }}>2.5</option>
                                        <option value="2.7" {{ old('engine') == '2.7' ? 'selected' : '' }}>2.7</option>
                                        <option value="2.8" {{ old('engine') == '2.8' ? 'selected' : '' }}>2.8</option>
                                        <option value="3" {{ old('engine') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="3.3" {{ old('engine') == '3.3' ? 'selected' : '' }}>3.3</option>
                                        <option value="3.5" {{ old('engine') == '3.5' ? 'selected' : '' }}>3.5</option>
                                        <option value="4" {{ old('engine') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="4.2" {{ old('engine') == '4.2' ? 'selected' : '' }}>4.2</option>
                                        <option value="4.4" {{ old('engine') == '4.4' ? 'selected' : '' }}>4.4</option>
                                        <option value="4.5" {{ old('engine') == '4.5' ? 'selected' : '' }}>4.5</option>
                                        <option value="4.8" {{ old('engine') == '4.8' ? 'selected' : '' }}>4.8</option>
                                        <option value="5.3" {{ old('engine') == '5.3' ? 'selected' : '' }}>5.3</option>
                                        <option value="5.6" {{ old('engine') == '5.6' ? 'selected' : '' }}>5.6</option>
                                        <option value="5.7" {{ old('engine') == '5.7' ? 'selected' : '' }}>5.7</option>
                                        <option value="6" {{ old('engine') == '6' ? 'selected' : '' }}>6</option>
                                        <option value="6.2" {{ old('engine') == '6.2' ? 'selected' : '' }}>6.2</option>
                                        <option value="6.7" {{ old('engine') == '6.7' ? 'selected' : '' }}>6.7</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="steering">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Steering</label>
                                    <select class="form-control" autofocus name="steering" id="steering">
                                        <option value="LHD" {{ old('steering') == 'LHD' ? 'selected' : '' }}>LHD</option>
                                        <option value="RHD" {{ old('steering') == 'RHD' ? 'selected' : '' }}>RHD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="Upholstery">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Upholstery</label>
                                    <select class="form-control" autofocus name="upholestry" id="upholstery">
                                        <option value="Leather" {{ old('steering') == 'Leather' ? 'selected' : '' }}>Leather</option>
                                        <option value="Fabric" {{ old('steering') == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-lg-2 col-md-6 col-sm-12" id="int">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Interior Colour</label>
                                    <select class="form-control" autofocus name="int_colour[]" id="int_colour" multiple>
                                        @foreach($int_colour as $color)
                                            <option value="{{ $color->id }}" {{ (is_array(old('int_colour')) && in_array($color->id, old('int_colour'))) ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-lg-2 col-md-6 col-sm-12" id="ex">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Exterior Colour</label>
                                    <select class="form-control" autofocus name="ex_colour[]" id="ex_colour" multiple>
                                        @foreach($ex_colour as $color)
                                            <option value="{{ $color->id }}" {{ (is_array(old('ex_colour')) && in_array($color->id, old('ex_colour'))) ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="row" id="specification-details-container">
                            </div>
                            <input type="hidden" name="selected_model_id" id="selected_model_id">
                            <input type="hidden" name="selected_specifications" id="selected_specifications">
                            <div class="col-lg-12 col-md-12 col-sm-12" id="model_detail">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Description</label>
                                    <input type="text" class="form-control model_detail" name="model_detail" id="model_detail" readonly/>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12" id="variant">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Variant Details</label>
                                    <input type="text" class="form-control variant" name="variant" id="variant" readonly/>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                </form>
            </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('#brand').select2({
            placeholder: 'Select Brand'
        })
        $('.coo').select2();
        $('#int_colour').select2({
            placeholder: 'Select Interior Colour'
        })
        $('#ex_colour').select2({
            placeholder: 'Select Exterior Colour'
        })
        $('#model').select2({
            placeholder: 'Select Model'
        })
        $('#brand').on('change',function() {
            $('#brand-error').remove();
        })
        $('#model').on('change',function() {
            $('#model-error').remove();
        })
        $("#form-create").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    string:true,
                    max:255
                },
                master_model_lines_id:{
                    required:true,
                },
                brands_id:{
                    required:true,
                },
            }
        });
    </script>
    <script>
    $(document).ready(function() {
        $('#brand').on('change', function() {
            $('#fuel, #coo, #steering, #gear, #drive_train, #my, #ex, #int, #engine, #Upholstery').hide();
            $('#specification-details-container').empty();
            var selectedBrandId = $(this).val();
            $.ajax({
                url: '/get-model-lines/' + selectedBrandId,
                type: 'GET',
                success: function(data) {
                    $('#model').empty();
                    $('#model').append('<option value="" disabled selected>Select a Model</option>');
                    $.each(data, function(index, modelLine) {
                        $('#model').append('<option value="' + modelLine.id + '">' + modelLine.model_line + '</option>');
                    });
                },
                error: function(error) {
                    console.log('Error fetching model lines:', error);
                }
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $('#fuel, #coo, #steering, #gear, #drive_train, #my, #ex, #int, #engine, #Upholstery').hide();
    $('#model').on('change', function() {
        $('#fuel, #coo, #steering, #gear, #drive_train, #my, #ex, #int, #engine, #Upholstery').show();
        var selectedModelLineId = $(this).val();
        selectedSpecifications = [];
        $.ajax({
            type: 'GET',
            url: '/getSpecificationDetails/' + selectedModelLineId,
            success: function(response) {
                var data = response.data;
                $('#specification-details-container').empty();
                var selectedSpecifications = [];
                data.forEach(function(item) {
                    var specification = item.specification;
                    var options = item.options;
                    var select = $('<select class="form-control" name="specification_' + specification.id + '"data-specification-id="' + specification.id + '">');
                    select.append('<option value="" disabled selected>Select an Option</option>');
                    options.forEach(function(option) {
                        select.append('<option value="' + option.id + '">' + option.name + '</option>');
                    });
                    select.on('change', function() {
                        var selectedValue = $(this).val();
                        selectedSpecifications.push({
                            specification_id: specification.id,
                            value: selectedValue
                        });
                        $('#selected_specifications').val(JSON.stringify(selectedSpecifications));
                    });
                    var specificationColumn = $('<div class="col-lg-4 mb-3">');
                    specificationColumn.append('<label class="form-label">' + specification.name + '</label');
                    specificationColumn.append(select);
                    $('#specification-details-container').append(specificationColumn);
                });
            }
        });
        $('#selected_model_id').val(selectedModelLineId);
    });
});
$(document).ready(function () {
    function updateModelDetail() {
    var selectedOptions = [];
    var fieldIdOrder = ['steering', 'model', 'engine', 'fuel', 'gear'];

    $('input[name^="field_checkbox"]:checked').each(function () {
        var fieldId = $(this).data('field-id');
        var fieldValue = $('#' + fieldId + ' option:selected').text();
        if (fieldId === 'fuel') {
            fieldValue = fieldValue.charAt(0);
        }

        selectedOptions.push({ fieldId: fieldId, value: fieldValue });
    });

    $('input[name^="specification_checkbox"]:checked').each(function () {
        var specificationId = $(this).data('specification-id');
        var selectedValue = $('select[name="specification_' + specificationId + '"]').text();
        var selectedText = $('select[name="specification_' + specificationId + '"] option:selected').text();
        var displayValue = (selectedText.toUpperCase() === 'YES') ? $('select[name="specification_' + specificationId + '"]').siblings('label').text() : selectedText;
        selectedOptions.push({ specificationId: specificationId, value: displayValue });
    });
    selectedOptions.sort(function (a, b) {
        var orderA = fieldIdOrder.indexOf(a.fieldId);
        var orderB = fieldIdOrder.indexOf(b.fieldId);
        if (orderA !== -1 && orderB !== -1) {
            return orderA - orderB;
        }
        if (orderA !== -1) {
            return -1;
        }
        if (orderB !== -1) {
            return 1;
        }
        return 0;
    });

    var modelDetail = selectedOptions.map(function (option) {
        return option.value;
    }).join(' ');

    $('.model_detail').val(modelDetail);
}
            $(document).on('change', 'input[name^="specification_checkbox"], input[name^="field_checkbox"]', function () {
                updateModelDetail();
            });
            $('#model_detail').on('click', function () {
                createSpecificationCheckboxes();
                createFieldCheckboxes();
            });
            function createSpecificationCheckboxes() {
    $('.specification-checkbox-container').remove();
    
    $('select[name^="specification_"]').each(function () {
        var specificationId = $(this).data('specification-id');
        var selectedOption = $(this).val();
        
        if (selectedOption && selectedOption !== '' && selectedOption !== null && selectedOption !== 'null') {
            var checkboxId = 'checkbox_specification_' + specificationId;
            var checkbox = $('<input type="checkbox">')
                .attr('id', checkboxId)
                .attr('name', 'specification_checkbox')
                .data('specification-id', specificationId);
            var label = $('<label>')
                .attr('for', checkboxId)
                .text('\u00A0Model');
            var checkboxContainer = $('<div class="specification-checkbox-container">')
                .append(checkbox)
                .append(label);
            $(this).closest('.col-lg-4').append(checkboxContainer);
        }
    });
}
            function createFieldCheckboxes() {
                $('.field-checkbox-container').remove();
                var fields = [
                    { id: 'steering', label: 'Steering' },
                    { id: 'model', label: 'model' },
                    { id: 'coo', label: 'COO' },
                    { id: 'my', label: 'Model Year' },
                    { id: 'drive_train', label: 'Drive Train' },
                    { id: 'gear', label: 'gearbox' },
                    { id: 'fuel', label: 'fuel_type' },
                    { id: 'engine', label: 'Engine' },
                    { id: 'upholstery', label: 'Upholstery' }
                ];
                fields.forEach(function (field) {
                    var checkboxId = 'checkbox_field_' + field.id;
                    var checkbox = $('<input type="checkbox">')
                        .attr('id', checkboxId)
                        .attr('name', 'field_checkbox')
                        .data('field-id', field.id);
                    var label = $('<label>')
                        .attr('for', checkboxId)
                        .text('\u00A0Model');
                    var checkboxContainer = $('<div class="field-checkbox-container">')
                        .append(checkbox)
                        .append(label);
                    $('#' + field.id).closest('.col-lg-2').append(checkboxContainer);
                });
            }
        });
        $(document).ready(function () {
    function updatevariantDetail() {
        var selectedOptionsv = [];
        $('input[name^="variantcheckbox"]:checked').each(function () {
            var specificationId = $(this).data('specification-id');
            var selectedValue = $('select[name="specification_' + specificationId + '"]').text();
            var selectedText = $('select[name="specification_' + specificationId + '"] option:selected').text();
            var displayValue = (selectedText.toUpperCase() === 'YES') ? $('select[name="specification_' + specificationId + '"]').siblings('label').text() : selectedText;
            selectedOptionsv.push({ specificationId: specificationId, value: displayValue });
        });
        $('input[name^="fieldvariants"]:checked').each(function () {
            var fieldId = $(this).data('field-id');
            var fieldValue = $('#' + fieldId + ' option:selected').text();
            selectedOptionsv.push({ fieldId: fieldId, value: fieldValue });
        });
        var Detail = selectedOptionsv.map(function (option) {
            return option.value;
        }).join(', ');
        $('.variant').val(Detail);
    }
            $(document).on('change', 'input[name^="variantcheckbox"], input[name^="fieldvariants"]', function () {
                updatevariantDetail();
            });
            $('#variant').on('click', function () {
                createSpecificationCheckboxesv();
                createFieldCheckboxesv();
            });
            function createSpecificationCheckboxesv() {
    $('.specification-details-container').remove();
    
    $('select[name^="specification_"]').each(function () {
        var specificationId = $(this).data('specification-id');
        var selectedOption = $(this).val();
        
        if (selectedOption && selectedOption !== '' && selectedOption !== null && selectedOption !== 'null') {
            var checkboxIdv = 'checkbox_specification_' + specificationId;
            var checkboxv = $('<input type="checkbox">')
                .attr('id', checkboxIdv)
                .attr('name', 'variantcheckbox')
                .data('specification-id', specificationId);
            var label = $('<label>')
                .attr('for', checkboxIdv)
                .text('\u00A0Variant');
            var checkboxContainerv = $('<div class="specification-details-container">')
                .append(checkboxv)
                .append(label);
            $(this).closest('.col-lg-4').append(checkboxContainerv);
        }
    });
}
            function createFieldCheckboxesv() {
                $('.field-checkbox-containerv').remove();
                var fields = [
                    { id: 'steering', label: 'Steering' },
                    { id: 'brands_id', label: 'Brand' },
                    { id: 'master_model_lines_id', label: 'Model Line' },
                    { id: 'coo', label: 'COO' },
                    { id: 'my', label: 'Model Year' },
                    { id: 'drive_train', label: 'Drive Train' },
                    { id: 'gear', label: 'Gear' },
                    { id: 'fuel', label: 'Fuel Type' },
                    { id: 'engine', label: 'Engine' },
                    { id: 'upholstery', label: 'Upholstery' }
                ];
                fields.forEach(function (field) {
                    var checkboxIdv = 'checkbox_field_' + field.id;
                    var checkbox = $('<input type="checkbox">')
                        .attr('id', checkboxIdv)
                        .attr('name', 'fieldvariants')
                        .data('field-id', field.id);
                    var label = $('<label>')
                        .attr('for', checkboxIdv)
                        .text('\u00A0Variant');
                    var checkboxContainerv = $('<div class="field-checkbox-containerv">')
                        .append(checkbox)
                        .append(label);
                    $('#' + field.id).closest('.col-lg-2').append(checkboxContainerv);
                });
            }
        });
</script>
@endpush
