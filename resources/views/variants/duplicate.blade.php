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
                <div class="modal fade optionsmodal-modal" id="optionsmodal" tabindex="-1" aria-labelledby="optionsmodalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="optionsmodalLabel">Update Options</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <div class="col-lg-12">
                                        <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label class="form-label font-size-13 text-center">New Option Name</label>
                                            <span class="text-danger">* </span>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <input type="text" class="form-control" placeholder="Enter Attribute Option" name="option_name" id="option_name" />
                                            <input type ="hidden" name="specification-id-input" id="specification-id-input" />
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="savenewoptions()" id="btn-save">Save</button>
                                </div>
                                </div>
                            </div>
                            </div>
                            <!-- <div class="modal fade optionsmodal-modal" id="optionsmodal" tabindex="-1" aria-labelledby="optionsmodalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="optionsmodalLabel">Update Options</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                                        <label class="form-label font-size-13 text-center">New Option Name</label>
                                                    </div>
                                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                                        <input type="text" class="form-label" name="option_name" id="option_name" />
                                                        <input type ="hidden" name="specification-id-input" id="specification-id-input" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="savenewoptions()" id="btn-save">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                <form id="form-create" action="{{ route('variants.store') }}" method="POST">
                    @csrf
                        <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Netsuite Variant Name</label>
                                   <input type = "text" name="netsuite_name" class="form-control" value= "{{$variant->netsuite_name}}"/>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Brand</label>
                                    <input class="form-control" type="text" class="" value="{{$brand->brand_name}}" readonly/>
                                    <input type="hidden" name="brands_id" value="{{$brand->id}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Line</label>
                                    <select class="form-control" name="master_model_lines_id" id="model" readonly>
                                    <option value="{{$masterModelLine->id}}">{{$masterModelLine->model_line}}</option>
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
                                        <option value="{{ $year }}" {{ isset($variant) && $variant->my == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="gear">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Gear</label>
                                    <select class="form-control" autofocus name="gearbox" id="gear">
                                    <option value="AT" {{ isset($variant) && $variant->gearbox == 'AT' ? 'selected' : '' }}>AT</option>
                                    <option value="MT" {{ isset($variant) && $variant->gearbox == 'MT' ? 'selected' : '' }}>MT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="fuel">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                                    <select class="form-control" autofocus name="fuel_type" id="fuel">
                                    <option value="Petrol" {{ isset($variant) && $variant->fuel_type == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="Diesel" {{ isset($variant) && $variant->fuel_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="PH" {{ isset($variant) && $variant->fuel_type == 'PH' ? 'selected' : '' }}>PH</option>
                                    <option value="PHEV" {{ isset($variant) && $variant->fuel_type == 'PHEV' ? 'selected' : '' }}>PHEV</option>
                                    <option value="MHEV" {{ isset($variant) && $variant->fuel_type == 'MHEV' ? 'selected' : '' }}>MHEV</option>
                                    <option value="EV" {{ isset($variant) && $variant->fuel_type == 'EV' ? 'selected' : '' }}>EV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="fuel">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Engine</label>
                                    <select class="form-control" autofocus name="engine" id="engine">
                                            <option value="" {{ isset($variant) && $variant->engine == '' ? 'selected' : '' }}>Please Select the Engine Capacity</option>
                                            <option value="0.8" {{ isset($variant) && $variant->engine == '0.8' ? 'selected' : '' }}>0.8</option>
                                            <option value="1.0" {{ isset($variant) && $variant->engine == '1.0' ? 'selected' : '' }}>1.0</option>
                                            <option value="1.2" {{ isset($variant) && $variant->engine == '1.2' ? 'selected' : '' }}>1.2</option>
                                            <option value="1.3" {{ isset($variant) && $variant->engine == '1.3' ? 'selected' : '' }}>1.3</option>
                                            <option value="1.4" {{ isset($variant) && $variant->engine == '1.4' ? 'selected' : '' }}>1.4</option>
                                            <option value="1.5" {{ isset($variant) && $variant->engine == '1.5' ? 'selected' : '' }}>1.5</option>
                                            <option value="1.6" {{ isset($variant) && $variant->engine == '1.6' ? 'selected' : '' }}>1.6</option>
                                            <option value="1.8" {{ isset($variant) && $variant->engine == '1.8' ? 'selected' : '' }}>1.8</option>
                                            <option value="2.0" {{ isset($variant) && $variant->engine == '2.0' ? 'selected' : '' }}>2.0</option>
                                            <option value="2.2" {{ isset($variant) && $variant->engine == '2.2' ? 'selected' : '' }}>2.2</option>
                                            <option value="2.4" {{ isset($variant) && $variant->engine == '2.4' ? 'selected' : '' }}>2.4</option>
                                            <option value="2.5" {{ isset($variant) && $variant->engine == '2.5' ? 'selected' : '' }}>2.5</option>
                                            <option value="2.7" {{ isset($variant) && $variant->engine == '2.7' ? 'selected' : '' }}>2.7</option>
                                            <option value="2.8" {{ isset($variant) && $variant->engine == '2.8' ? 'selected' : '' }}>2.8</option>
                                            <option value="3.0" {{ isset($variant) && $variant->engine == '3.0' ? 'selected' : '' }}>3.0</option>
                                            <option value="3.3" {{ isset($variant) && $variant->engine == '3.3' ? 'selected' : '' }}>3.3</option>
                                            <option value="3.4" {{ isset($variant) && $variant->engine == '3.4' ? 'selected' : '' }}>3.4</option>
                                            <option value="3.5" {{ isset($variant) && $variant->engine == '3.5' ? 'selected' : '' }}>3.5</option>
                                            <option value="3.6" {{ isset($variant) && $variant->engine == '3.6' ? 'selected' : '' }}>3.6</option>
                                            <option value="3.8" {{ isset($variant) && $variant->engine == '3.8' ? 'selected' : '' }}>3.8</option>
                                            <option value="4.0" {{ isset($variant) && $variant->engine == '4.0' ? 'selected' : '' }}>4.0</option>
                                            <option value="4.2" {{ isset($variant) && $variant->engine == '4.2' ? 'selected' : '' }}>4.2</option>
                                            <option value="4.4" {{ isset($variant) && $variant->engine == '4.4' ? 'selected' : '' }}>4.4</option>
                                            <option value="4.5" {{ isset($variant) && $variant->engine == '4.5' ? 'selected' : '' }}>4.5</option>
                                            <option value="4.6" {{ isset($variant) && $variant->engine == '4.6' ? 'selected' : '' }}>4.6</option>
                                            <option value="4.8" {{ isset($variant) && $variant->engine == '4.8' ? 'selected' : '' }}>4.8</option>
                                            <option value="5.0" {{ isset($variant) && $variant->engine == '5.0' ? 'selected' : '' }}>5.0</option>
                                            <option value="5.3" {{ isset($variant) && $variant->engine == '5.3' ? 'selected' : '' }}>5.3</option>
                                            <option value="5.5" {{ isset($variant) && $variant->engine == '5.5' ? 'selected' : '' }}>5.5</option>
                                            <option value="5.6" {{ isset($variant) && $variant->engine == '5.6' ? 'selected' : '' }}>5.6</option>
                                            <option value="5.7" {{ isset($variant) && $variant->engine == '5.7' ? 'selected' : '' }}>5.7</option>
                                            <option value="5.9" {{ isset($variant) && $variant->engine == '5.9' ? 'selected' : '' }}>5.9</option>
                                            <option value="6.0" {{ isset($variant) && $variant->engine == '6.0' ? 'selected' : '' }}>6.0</option>
                                            <option value="6.2" {{ isset($variant) && $variant->engine == '6.2' ? 'selected' : '' }}>6.2</option>
                                            <option value="6.7" {{ isset($variant) && $variant->engine == '6.7' ? 'selected' : '' }}>6.7</option>
                                        </select>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="steering">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Steering</label>
                                    <select class="form-control" autofocus name="steering" id="steering">
            <option value="LHD" {{ isset($variant) && $variant->steering == 'LHD' ? 'selected' : '' }}>LHD</option>
            <option value="RHD" {{ isset($variant) && $variant->steering == 'RHD' ? 'selected' : '' }}>RHD</option>
        </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="coo">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">COO</label>
                                    <select class="form-control coo" name="coo" id="coo">
                                    <option value="" disabled selected>Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country }}" data-value="{{ $country }}" {{ isset($variant) && $variant->coo == $country ? 'selected' : '' }}>{{ $country }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="drive_train">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Drive Train</label>
                                    <select class="form-control" autofocus name="drive_train" id="drive_train">
                                    <option value="4X2" {{ isset($variant) && $variant->drive_train == '4X2' ? 'selected' : '' }}>4X2</option>
                                    <option value="4X4" {{ isset($variant) && $variant->drive_train == '4X4' ? 'selected' : '' }}>4X4</option>
                                    <option value="AWD" {{ isset($variant) && $variant->drive_train == 'AWD' ? 'selected' : '' }}>AWD</option>
                                    <option value="4WD" {{ isset($variant) && $variant->drive_train == '4WD' ? 'selected' : '' }}>4WD</option>
                                    <option value="FWD" {{ isset($variant) && $variant->drive_train == 'FWD' ? 'selected' : '' }}>FWD</option>
                                    <option value="RWD" {{ isset($variant) && $variant->drive_train == 'RWD' ? 'selected' : '' }}>RWD</option>
                                    <option value="4MATIC" {{ isset($variant) && $variant->drive_train == '4MATIC' ? 'selected' : '' }}>4MATIC</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12" id="Upholstery">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Upholstery</label>
                                    <select class="form-control" autofocus name="upholestry" id="upholstery">
                                    <option value="Leather" {{ isset($variant) && $variant->upholestry == 'Leather' ? 'selected' : '' }}>Leather</option>
                                    <option value="Fabric" {{ isset($variant) && $variant->upholestry == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                    <option value="Vinyl" {{ isset($variant) && $variant->upholestry == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                                    <option value="Leather & Fabric" {{ isset($variant) && $variant->upholestry == 'Leather & Fabric' ? 'selected' : '' }}>Leather & Fabric</option>
                                </select>
                                </div>
                            </div>
                            <div class="row" id="specification-details-container">
    @foreach ($data as $specData)
        <div class="col-lg-4 mb-3">
            <label>{{ $specData['specification']->name }}</label>
            <div class="input-group">
                <select name="specification_{{ $specData['specification']->id }}" data-specification-id="{{ $specData['specification']->id }}" class="form-control specification-dropdown">
                    <option value="" disabled selected>Select an Option</option>
                    @foreach ($specData['options'] as $option)
                        <option value="{{ $option->id }}" @if (in_array($option->id, $specData['selectedOptions'])) selected @endif>
                            {{ $option->name }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" data-specification-id="{{ $specData['specification']->id }}" data-toggle="modal" data-target="#optionsmodal">
                        +
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
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
    // Assuming selectedSpecifications is already defined
    var selectedSpecifications = [];
    $('.specification-dropdown').on('change', function () {
        var selectedValue = $(this).val();
        var specificationId = $(this).data('specification-id');

        // Check if selectedValue is not null or empty
        if (selectedValue !== null && selectedValue !== "") {
            var specIndex = selectedSpecifications.findIndex(spec => spec.specification_id === specificationId);

            if (specIndex !== -1) {
                // Update existing entry
                selectedSpecifications[specIndex].value = selectedValue;
            } else {
                // Add new entry
                selectedSpecifications.push({
                    specification_id: specificationId,
                    value: selectedValue
                });
            }

            $('#selected_specifications').val(JSON.stringify(selectedSpecifications));
        }
    });
    // Trigger change event for each dropdown to capture initially selected values
    $('.specification-dropdown').trigger('change');
</script>
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
$(document).ready(function () {
    function updateModelDetail() {
    var selectedOptions = [];
    var fieldIdOrder = ['steering', 'model', 'engine', 'fuel', 'gear'];
    var gradeOption = null;

    $('input[name^="field_checkbox"]:checked').each(function () {
        var fieldId = $(this).data('field-id');
        var fieldValue = $('#' + fieldId + ' option:selected').text().trim();
        if (fieldId === 'fuel') {
    if (fieldValue === 'Petrol') {
        fieldValue = 'P';
    } else if (fieldValue === 'Diesel') {
        fieldValue = 'D';
    } else if (fieldValue === 'PHEV') {
        fieldValue = 'PHEV';
    } else if (fieldValue === 'MHEV') {
        fieldValue = 'MHEV';
    } else if (fieldValue === 'PH') {
        fieldValue = 'PH';
    } else {
        fieldValue = 'EV';
    }
}
        selectedOptions.push({ fieldId: fieldId, value: fieldValue });

        // Check if the field is "model" and save the grade option
        if (fieldId === 'model') {
            gradeOption = selectedOptions.find(option => option.fieldId === 'model');
        }
    });

    $('input[name^="specification_checkbox"]:checked').each(function () {
        var specificationId = $(this).data('specification-id');
        var selectedValue = $('select[name="specification_' + specificationId + '"]').text();
        var selectedText = $('select[name="specification_' + specificationId + '"] option:selected').text();
        var displayValue = (selectedText.toUpperCase() === 'YES') ? $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text() : selectedText;
        var specificationName = $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text();
        if (specificationName === 'Grade') {
            // If specificationName is "Grade," update the gradeOption
            if (gradeOption) {
                gradeOption.value += ' ' + displayValue;
            } else {
                selectedOptions.push({ fieldId: 'model', value: displayValue });
            }
        } else {
            selectedOptions.push({ specificationId: specificationId, value: displayValue });
        }
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

    var modelDetail = selectedOptions.map(function (option, index, arr) {
        if (option.fieldId === 'fuel' && arr[index - 1]?.fieldId === 'engine') {
            // Combine engine and fuel values without a space
            return arr[index - 1].value + option.value;
        } else if (option.fieldId === 'engine' && arr[index + 1]?.fieldId === 'fuel') {
            // Skip adding engine value, as it will be combined later with fuel
            return '';
        } else {
            return option.value;
        }
    }).filter(Boolean).join(' ').replace(/\s+/g, ' '); // Ensure single spaces only
    $('.model_detail').val(modelDetail.trim()); // Trim final result to remove leading/trailing spaces
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
    var sfxValue = null; // To store the SFX value if found

    // Process specification checkboxes
    $('input[name^="variantcheckbox"]:checked').each(function () {
        var specificationId = $(this).data('specification-id');
        var selectedText = $('select[name="specification_' + specificationId + '"] option:selected').text().trim();
        var displayValue = (selectedText.toUpperCase() === 'YES')
            ? $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text()
            : selectedText;

        console.log(selectedText);

        // Check if the selected option is SFX
        if (selectedText.toUpperCase() === 'SFX') {
            sfxValue = '(' + displayValue + ')'; // Store SFX value
        } else {
            selectedOptionsv.push({ specificationId: specificationId, value: displayValue });
        }
    });

    // Process field checkboxes
    $('input[name^="fieldvariants"]:checked').each(function () {
        var fieldId = $(this).data('field-id');
        var fieldValue = $('#' + fieldId + ' option:selected').text().trim();
        selectedOptionsv.push({ fieldId: fieldId, value: fieldValue });
    });

    // Filter and prioritize SFX
    var Detail = [];

    // Add SFX value first if it exists
    if (sfxValue) {
        Detail.push(sfxValue);
    }

    // Add remaining values, filtering out null or empty values
    Detail = Detail.concat(
        selectedOptionsv
            .map(function (option) {
                return option.value.trim(); // Trim to remove leading/trailing whitespaces
            })
            .filter(function (value) {
                return value !== null && value !== ''; // Filter out null or empty values
            })
    );

    // Join all values into a single string
    $('.variant').val(Detail.join(', '));
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
<script>
    $(document).ready(function () {
        // Attach click event to the plus buttons
        $('.btn-outline-secondary').click(function () {
            var specificationId = $(this).data('specification-id');
            $('#specification-id-input').val(specificationId);
            $('#option_name').val('');
            $('#optionsmodal').modal('show');
        });
    });
</script>
<script>
    function savenewoptions() {
        var specificationId = $('#specification-id-input').val();
        var newOptionValue = $('#option_name').val();

        if(!validateSpacing(newOptionValue)) {
            alertify.confirm("No leading or trailing spaces allowed or No more than one consecutive space is allowed in the address!").set({
                            labels: {ok: "Retry", cancel: "Cancel"},
                            title: "Error",
                        });
            return;
        }
        $.ajax({
            url: '{{ route('variants.saveOption') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                specificationId: specificationId,
                newOption: newOptionValue
            },
            success: function (response) {
                var option = '<option value="' + response.option.id + '">' + response.option.name + '</option>';
                $('select[name="specification_' + specificationId + '"]').append(option);
                alertify.success('Specification Option successfully Added');
                $('#optionsmodal').modal('hide');
            },
            error: function (error) {
                let errors = error.responseJSON.error;
                let errorMessages = '';
                $.each(errors, function(field, messages) {
                    $.each(messages, function(index, message) {
                        errorMessages += `<p>${message}</p>`;
                    });
                });
                alertify.confirm(errorMessages).set({
                            labels: {ok: "Retry", cancel: "Cancel"},
                            title: "Error",
                        });
                
            }
        });
    }

    function validateSpacing(value) {
       const invalidChars = /^\s|\s{2,}|\s$/;
        return !invalidChars.test(value);
    } 
</script>
@endpush