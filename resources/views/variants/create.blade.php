@extends('layouts.main')

<style>
    .custom-error {
        color: red;
        margin-top: 10px !important;
    }
</style>


@section('content')
<style>
    #model_detail-error,
    #model-error {
        margin-top: 10px !important;
    }
</style>
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
    <a class="btn btn-sm btn-info float-end" href="{{ route('variants.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <div class="alert alert-danger">
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
                                <input type="hidden" name="specification-id-input" id="specification-id-input" />
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
    <form id="form-create" action="{{ route('variants.store') }}" method="POST">
        @csrf
        <div class="row">
            <!-- <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Netsuite Variant Name</label>
                                   <input type = "text" name="netsuite_name" class="form-control"/>
                                </div>
                            </div> -->
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Brand<span style="color: red;">*</span></label>
                    <select class="form-control" autofocus name="brands_id" id="brand" required>
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
                    <label for="choices-single-default" class="form-label">Model Line<span style="color: red;">*</span></label>
                    <select class="form-control" autofocus name="master_model_lines_id" id="model" required>
                        <option value="" disabled selected>Select a Model Line</option>
                        @foreach($masterModelLines as $masterModelLine)
                        <option value="{{ $masterModelLine->id }}" {{ old('master_model_lines_id') == $masterModelLine->id ? 'selected' : '' }}>
                            {{ $masterModelLine->model_line }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="mb-3">
                    <label for="model_detail" class="form-label">Model Description <span style="color: red;">*</span></label>
                    <select class="form-control select2" name="model_detail" id="model_detail">
                        <option value="">Select a Model</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="steering" class="form-label">Steering</label>
                    <input type="text" id="steering" name="steering" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="engine" class="form-label">Engine</label>
                    <input type="text" id="engine" name="engine" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="fuel_type" class="form-label">Fuel Type</label>
                    <input type="text" id="fuel_type" name="fuel_type" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="transmission" class="form-label">Transmission</label>
                    <input type="text" id="transmission" name="gearbox" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="window_type" class="form-label">Window Type</label>
                    <input type="text" id="window_type" name="window_type" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="drive_train" class="form-label">Drive Train</label>
                    <input type="text" id="drive_train" name="drive_train" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="netsuite_name" class="form-label">Grade</label>
                    <input type="text" id="netsuite_name" name="grade_name" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="netsuite_name" class="form-label">Special Editions</label>
                    <input type="text" id="specialEditions" name="specialEditions" class="form-control" readonly />
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="netsuite_name" class="form-label">Others</label>
                    <input type="text" id="others" name="others" class="form-control" readonly />
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
                    <select name="my" id="my" class="form-control">
                        @foreach ($years as $year)
                        <option value="{{ $year }}" {{ old('my') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-12" id="gear">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Gear</label>
                    <select class="form-control" autofocus name="gearbox" id="gear">
                        <option value="AT" {{ old('gearbox') == 'AT' ? 'selected' : '' }}>AT</option>
                        <option value="MT" {{ old('gearbox') == 'MT' ? 'selected' : '' }}>MT</option>
                    </select>
                </div>
            </div>
            <!-- <div class="col-lg-2 col-md-6 col-sm-12" id="fuel">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                                    <select class="form-control" autofocus name="fuel_type" id="fuel">
                                        <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="PH" {{ old('fuel_type') == 'PH' ? 'selected' : '' }}>PH</option>
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
                                        <option value="" {{ old('engine') == '' ? 'selected' : '' }}>Please Select The Engine Capacity</option>
                                        <option value="0.8" {{ old('engine') == '0.8' ? 'selected' : '' }}>0.8</option>
                                        <option value="1.0" {{ old('engine') == '1.0' ? 'selected' : '' }}>1.0</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.2</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.3</option>
                                        <option value="1.4" {{ old('engine') == '1.4' ? 'selected' : '' }}>1.4</option>
                                        <option value="1.5" {{ old('engine') == '1.5' ? 'selected' : '' }}>1.5</option>
                                        <option value="1.6" {{ old('engine') == '1.6' ? 'selected' : '' }}>1.6</option>
                                        <option value="1.8" {{ old('engine') == '1.8' ? 'selected' : '' }}>1.8</option>
                                        <option value="2.0" {{ old('engine') == '2.0' ? 'selected' : '' }}>2.0</option>
                                        <option value="2.2" {{ old('engine') == '2.2' ? 'selected' : '' }}>2.2</option>
                                        <option value="2.4" {{ old('engine') == '2.4' ? 'selected' : '' }}>2.4</option>
                                        <option value="2.5" {{ old('engine') == '2.5' ? 'selected' : '' }}>2.5</option>
                                        <option value="2.7" {{ old('engine') == '2.7' ? 'selected' : '' }}>2.7</option>
                                        <option value="2.8" {{ old('engine') == '2.8' ? 'selected' : '' }}>2.8</option>
                                        <option value="3.0" {{ old('engine') == '3.0' ? 'selected' : '' }}>3.0</option>
                                        <option value="3.3" {{ old('engine') == '3.3' ? 'selected' : '' }}>3.3</option>
                                        <option value="3.4" {{ old('engine') == '3.4' ? 'selected' : '' }}>3.4</option>
                                        <option value="3.5" {{ old('engine') == '3.5' ? 'selected' : '' }}>3.5</option>
                                        <option value="3.6" {{ old('engine') == '3.6' ? 'selected' : '' }}>3.6</option>
                                        <option value="3.8" {{ old('engine') == '3.8' ? 'selected' : '' }}>3.8</option>
                                        <option value="4.0" {{ old('engine') == '4.0' ? 'selected' : '' }}>4.0</option>
                                        <option value="4.2" {{ old('engine') == '4.2' ? 'selected' : '' }}>4.2</option>
                                        <option value="4.4" {{ old('engine') == '4.4' ? 'selected' : '' }}>4.4</option>
                                        <option value="4.5" {{ old('engine') == '4.5' ? 'selected' : '' }}>4.5</option>
                                        <option value="4.6" {{ old('engine') == '4.6' ? 'selected' : '' }}>4.6</option>
                                        <option value="4.8" {{ old('engine') == '4.8' ? 'selected' : '' }}>4.8</option>
                                        <option value="5.0" {{ old('engine') == '5.0' ? 'selected' : '' }}>5.0</option>
                                        <option value="5.2" {{ old('engine') == '5.2' ? 'selected' : '' }}>5.2</option>
                                        <option value="5.3" {{ old('engine') == '5.3' ? 'selected' : '' }}>5.3</option>
                                        <option value="5.5" {{ old('engine') == '5.5' ? 'selected' : '' }}>5.5</option>
                                        <option value="5.6" {{ old('engine') == '5.6' ? 'selected' : '' }}>5.6</option>
                                        <option value="5.7" {{ old('engine') == '5.7' ? 'selected' : '' }}>5.7</option>
                                        <option value="5.9" {{ old('engine') == '5.9' ? 'selected' : '' }}>5.9</option>
                                        <option value="6.0" {{ old('engine') == '6.0' ? 'selected' : '' }}>6.0</option>
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
                            </div> -->
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
            <div class="col-lg-2 col-md-6 col-sm-12" id="Upholstery">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Upholstery</label>
                    <select class="form-control" autofocus name="upholestry" id="upholstery">
                        <option value="Leather" {{ old('upholstery') == 'Leather' ? 'selected' : '' }}>Leather</option>
                        <option value="Fabric" {{ old('upholstery') == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                        <option value="Vinyl" {{ old('upholstery') == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                        <option value="Leather & Fabric" {{ old('upholstery') == 'Leather & Fabric' ? 'selected' : '' }}>Leather & Fabric</option>
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
            <div class="col-lg-12 col-md-12 col-sm-12" id="variant">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Variant Details <span style="color: red;">*</span></label>
                    <input type="text" class="form-control variant" name="variant" id="variant" readonly />
                </div>
            </div>
            <div class="row" id="specification-details-container">
            </div>
            <input type="hidden" name="selected_model_id" id="selected_model_id">
            <input type="hidden" name="selected_specifications" id="selected_specifications">
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
    $(document).ready(function() {
        $('#model_detail').select2({
            placeholder: "Select a Model",
            allowClear: true
        }).on('change', function() {
            $('#model_detail-error').remove();
        });
        $('#model').change(function() {
            const modelLineId = $(this).val();
            if (modelLineId) {
                $.ajax({
                    url: `/get-model-details/${modelLineId}`,
                    type: 'GET',
                    success: function(data) {
                        $('#model_detail').empty();
                        $('#model_detail').append('<option value="">Select a Model</option>');
                        data.forEach(model => {
                            $('#model_detail').append(
                                `<option value="${model.id}">${model.model_description}</option>`
                            );
                        });
                        if (data.length > 0) {
                            populateModelDetails(data[0]);
                        }
                    },
                    error: function() {
                        alert('Failed to fetch model details. Please try again.');
                    }
                });
            } else {
                $('#model_detail').empty();
                $('#model_detail').append('<option value="">Select a Model</option>');
                clearModelDetails();
                // updatevariantDetail();
            }
        });
        $('#model_detail').change(function() {
            const selectedModelId = $(this).val();
            if (selectedModelId) {
                $.ajax({
                    url: `/get-model-details/${$('#model').val()}`,
                    type: 'GET',
                    success: function(data) {
                        const selectedModel = data.find(model => model.id == selectedModelId);
                        if (selectedModel) {
                            populateModelDetails(selectedModel);
                        }
                    },
                    error: function() {
                        alert('Failed to fetch model details. Please try again.');
                    }
                });
            } else {
                clearModelDetails();
            }
        });

        function populateModelDetails(model) {
            $('#steering').val(model.steering);
            $('#engine').val(model.engine);
            $('#fuel_type').val(model.fuel_type);
            $('#transmission').val(model.transmission);
            $('#window_type').val(model.window_type);
            $('#drive_train').val(model.drive_train);
            $('#netsuite_name').val(model.grade_name);
            $('#specialEditions').val(model.specialEditions);
            $('#others').val(model.others);
        }

        // Clear the input fields
        function clearModelDetails() {
            $('#steering, #engine, #fuel_type, #transmission, #window_type, #drive_train, #netsuite_name').val('');
        }
    });
</script>
<script>
    $('#brand').select2({
        placeholder: 'Select Brand'
    })
    $('.coo').select2();
    $('.my').select2();
    $('#int_colour').select2({
        placeholder: 'Select Interior Colour'
    })
    $('#ex_colour').select2({
        placeholder: 'Select Exterior Colour'
    })
    $('#model').select2({
        placeholder: 'Select Model'
    })
    $('#brand').on('change', function() {
        $('#brand-error').remove();
    })
    $('#model').on('change', function() {
        $('#model-error').remove();
    })
    $("#form-create").validate({
        ignore: [],
        rules: {
            name: {
                required: true,
                string: true,
                max: 255
            },
            master_model_lines_id: {
                required: true,
            },
            brands_id: {
                required: true,
            },
            model_detail: {
                required: true,
            },
        },
        errorPlacement: function(error, element) {
            error.addClass('custom-error');
            if (element.attr("name") === "master_model_lines_id") {
                error.insertAfter(element.next('.select2'));
            } else {
                error.insertAfter(element);
            }
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#brand').on('change', function() {
            $('#my, #ex, #int, #Upholstery').hide();
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
        $('#coo, #my, #ex, #int, #Upholstery').hide();
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
                        var addButton = $('<a><button class="btn btn-primary btn-sm ml-2">+</button></a>');
                        addButton.on('click', function(event) {
                            event.preventDefault();
                            $('#specification-id-input').val(specification.id);
                            $('#optionsmodal').modal('show');
                        });
                        var selectContainer = $('<div class="d-flex align-items-center"></div>');
                        selectContainer.append(select).append(addButton);
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
                        specificationColumn.append(selectContainer);
                        $('#specification-details-container').append(specificationColumn);
                    });
                }
            });
            $('#selected_model_id').val(selectedModelLineId);
        });
    });
</script>
<script>
    function savenewoptions() {
        var specificationId = $('#specification-id-input').val();
        var newOptionValue = $('#option_name').val();
        $.ajax({
            url: '{{ route('variants.saveOption') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                specificationId: specificationId,
                newOption: newOptionValue
            },
            success: function(response) {
                var option = '<option value="' + response.option.id + '">' + response.option.name + '</option>';
                $('[data-specification-id="' + specificationId + '"]').append(option);
                alertify.success('Specification Option successfully Added');
                $('#optionsmodal').modal('hide');
            },
            error: function(error) {
                let errors = error.responseJSON.error;
                let errorMessages = '';
                $.each(errors, function(field, messages) {
                    $.each(messages, function(index, message) {
                        errorMessages += `<p>${message}</p>`;
                    });
                });
                alertify.confirm(errorMessages).set({
                    labels: {
                        ok: "Retry",
                        cancel: "Cancel"
                    },
                    title: "Error",
                });
            }
        });
    }
</script>
<script>
    // $(document).ready(function () {
    //     function updateModelDetail() {
    //     var selectedOptions = [];
    //     var fieldIdOrder = ['steering', 'model', 'engine', 'fuel', 'gear'];
    //     var gradeOption = null;

    //     $('input[name^="field_checkbox"]:checked').each(function () {
    //         var fieldId = $(this).data('field-id');
    //         var fieldValue = $('#' + fieldId + ' option:selected').text();
    //         if (fieldId === 'fuel') {
    //     if (fieldValue === 'Petrol') {
    //         fieldValue = 'P';
    //     } else if (fieldValue === 'Diesel') {
    //         fieldValue = 'D';
    //     } else if (fieldValue === 'PHEV') {
    //         fieldValue = 'PHEV';
    //     } else if (fieldValue === 'MHEV') {
    //         fieldValue = 'MHEV';
    //     } else if (fieldValue === 'PH') {
    //         fieldValue = 'PH';
    //     } else {
    //         fieldValue = 'EV';
    //     }
    // }
    //         selectedOptions.push({ fieldId: fieldId, value: fieldValue });

    //         // Check if the field is "model" and save the grade option
    //         if (fieldId === 'model') {
    //             gradeOption = selectedOptions.find(option => option.fieldId === 'model');
    //         }
    //     });

    //     $('input[name^="specification_checkbox"]:checked').each(function () {
    //         var specificationId = $(this).data('specification-id');
    //         var selectedValue = $('select[name="specification_' + specificationId + '"]').text();
    //         var selectedText = $('select[name="specification_' + specificationId + '"] option:selected').text();
    //         var displayValue = (selectedText.toUpperCase() === 'YES') ? $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text() : selectedText;
    //         var specificationName = $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text();
    //         if (specificationName === 'Grade') {
    //             // If specificationName is "Grade," update the gradeOption
    //             if (gradeOption) {
    //                 gradeOption.value += ' ' + displayValue;
    //             } else {
    //                 selectedOptions.push({ fieldId: 'model', value: displayValue });
    //             }
    //         } else {
    //             selectedOptions.push({ specificationId: specificationId, value: displayValue });
    //         }
    //     });

    //     selectedOptions.sort(function (a, b) {
    //         var orderA = fieldIdOrder.indexOf(a.fieldId);
    //         var orderB = fieldIdOrder.indexOf(b.fieldId);
    //         if (orderA !== -1 && orderB !== -1) {
    //             return orderA - orderB;
    //         }
    //         if (orderA !== -1) {
    //             return -1;
    //         }
    //         if (orderB !== -1) {
    //             return 1;
    //         }
    //         return 0;
    //     });

    //     var modelDetail = selectedOptions.map(function (option, index, arr) {
    //         if (option.fieldId === 'fuel' && arr[index - 1]?.fieldId === 'engine') {
    //             // Combine engine and fuel values without a space
    //             return arr[index - 1].value + option.value;
    //         } else if (option.fieldId === 'engine' && arr[index + 1]?.fieldId === 'fuel') {
    //             // Skip adding engine value, as it will be combined later with fuel
    //             return '';
    //         } else {
    //             return option.value;
    //         }
    //     }).filter(Boolean).join(' ');

    //     $('.model_detail').val(modelDetail);
    // }
    //             $(document).on('change', 'input[name^="specification_checkbox"], input[name^="field_checkbox"]', function () {
    //                 updateModelDetail();
    //             });
    //             $('#model_detail').on('click', function () {
    //                 createSpecificationCheckboxes();
    //                 createFieldCheckboxes();
    //             });
    //             function createSpecificationCheckboxes() {
    //     $('.specification-checkbox-container').remove();

    //     $('select[name^="specification_"]').each(function () {
    //         var specificationId = $(this).data('specification-id');
    //         var selectedOption = $(this).val();

    //         if (selectedOption && selectedOption !== '' && selectedOption !== null && selectedOption !== 'null') {
    //             var checkboxId = 'checkbox_specification_' + specificationId;
    //             var checkbox = $('<input type="checkbox">')
    //                 .attr('id', checkboxId)
    //                 .attr('name', 'specification_checkbox')
    //                 .data('specification-id', specificationId);
    //             var label = $('<label>')
    //                 .attr('for', checkboxId)
    //                 .text('\u00A0Model');
    //             var checkboxContainer = $('<div class="specification-checkbox-container">')
    //                 .append(checkbox)
    //                 .append(label);
    //             $(this).closest('.col-lg-4').append(checkboxContainer);
    //         }
    //     });
    // }
    //             function createFieldCheckboxes() {
    //                 $('.field-checkbox-container').remove();
    //                 var fields = [
    //                     { id: 'steering', label: 'Steering' },
    //                     { id: 'model', label: 'model' },
    //                     { id: 'coo', label: 'COO' },
    //                     { id: 'my', label: 'Model Year' },
    //                     { id: 'drive_train', label: 'Drive Train' },
    //                     { id: 'gear', label: 'gearbox' },
    //                     { id: 'fuel', label: 'fuel_type' },
    //                     { id: 'engine', label: 'Engine' },
    //                     { id: 'upholstery', label: 'Upholstery' }
    //                 ];
    //                 fields.forEach(function (field) {
    //                     var checkboxId = 'checkbox_field_' + field.id;
    //                     var checkbox = $('<input type="checkbox">')
    //                         .attr('id', checkboxId)
    //                         .attr('name', 'field_checkbox')
    //                         .data('field-id', field.id);
    //                     var label = $('<label>')
    //                         .attr('for', checkboxId)
    //                         .text('\u00A0Model');
    //                     var checkboxContainer = $('<div class="field-checkbox-container">')
    //                         .append(checkbox)
    //                         .append(label);
    //                     $('#' + field.id).closest('.col-lg-2').append(checkboxContainer);
    //                 });
    //             }
    //         });
    $(document).ready(function() {
        function updatevariantDetail() {
            var selectedOptionsv = [];
            var sfxValue = null;
            var sfxExists = false;

            $('input[name^="variantcheckbox"]:checked').each(function() {
                var specificationId = $(this).data('specification-id');
                var selectedValue = $('select[name="specification_' + specificationId + '"]').val();
                var selectedText = $('select[name="specification_' + specificationId + '"] option:selected').text();
                var displayValue = (selectedText.toUpperCase() === 'YES') ?
                    $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text() :
                    selectedText;
                var labelsvalue = $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text();
                if (labelsvalue.toUpperCase() === 'SFX') {
                    // Capture sfxValue to ensure it comes first
                    sfxValue = '(' + displayValue + ')';
                    sfxExists = true; // Set flag to true when SFX is found
                    console.log(sfxExists);
                } else {
                    selectedOptionsv.push({
                        specificationId: specificationId,
                        value: displayValue
                    });
                }
            });

            $('input[name^="fieldvariants"]:checked').each(function() {
                var fieldId = $(this).data('field-id');
                var fieldValue = $('#' + fieldId + ' option:selected').text();
                selectedOptionsv.push({
                    fieldId: fieldId,
                    value: fieldValue
                });
            });

            // Initialize the Detail array
            var Detail = [];

            // Add sfxValue first if it exists
            if (sfxValue) {
                Detail.push(sfxValue);
            }

            // Concatenate other selected options
            selectedOptionsv.forEach(function(option) {
                Detail.push(option.value);
            });

            // Update the variant field
            $('.variant').val(Detail.join(', '));
            return sfxExists;
        }

        $(document).on('change', 'input[name^="variantcheckbox"], input[name^="fieldvariants"]', function() {
            updatevariantDetail();
        });

        $('#variant').on('click', function() {
            createSpecificationCheckboxesv();
            createFieldCheckboxesv();
        });

        // Form Submission Validation
        $('#form-create').on('submit', function(e) {
            var variantValue = $('.variant').val().trim();
            let isSFXExist = false;
            let isSfxSelected = false;
            let SFXspecificationId = '';
            $('select[name^="specification_"]').each(function() {
                var specificationId = $(this).data('specification-id');
                var selectedValue = $('select[name="specification_' + specificationId + '"]').val();

                var labelsvalue = $('select[name="specification_' + specificationId + '"]').closest('.col-lg-4').find('label').first().text();

                if (labelsvalue.toUpperCase() === 'SFX') {
                    isSFXExist = true;
                    SFXspecificationId = specificationId;
                    if (selectedValue) {
                        isSfxSelected = true;
                    }
                    return false;
                }
            });

            if (isSFXExist == false) {
                errorMessages = "Unable to find SFX in attribute list, Please create SFX as variant attribute for the selected Model Line";
                alertify.confirm(errorMessages).set({
                    labels: {
                        cancel: "Cancel"
                    },
                    title: "SFX is Mandatory",
                });

                e.preventDefault();
                return false;
            } else {
                // sfx existing chceck it is selected or not
                if (isSfxSelected == false) {
                    errorMsg = "Please Choose any SFX, This attribute is mandatory to create variant.";
                    alertify.confirm(errorMsg).set({
                        labels: {
                            cancel: "Cancel"
                        },
                        title: "SFX is Mandatory",
                    });
                    $('select[name="specification_' + SFXspecificationId + '"]').addClass('is-invalid');
                    e.preventDefault();
                    return false;
                } else {
                    $('select[name="specification_' + SFXspecificationId + '"]').removeClass('is-invalid');
                }
            }

            // Check if Variant Details is empty
            if (!variantValue) {
                alertify.confirm(" Variant Details is required. Please fill it in before submitting.").set({
                    labels: {
                        cancel: "Cancel"
                    },
                    title: "Error",
                });
                e.preventDefault(); // Prevent form submission
                return false;
            }
        });

        function createSpecificationCheckboxesv() {
            // $('.specification-details-container').remove();

            // $('select[name^="specification_"]').each(function () {
            //     var specificationId = $(this).data('specification-id');
            //     var selectedOption = $(this).val();

            //     if (selectedOption && selectedOption !== '' && selectedOption !== null && selectedOption !== 'null') {
            //         var checkboxIdv = 'checkbox_specification_' + specificationId;
            //         var checkboxv = $('<input type="checkbox">')
            //             .attr('id', checkboxIdv)
            //             .attr('name', 'variantcheckbox')
            //             .data('specification-id', specificationId);
            //         var label = $('<label>')
            //             .attr('for', checkboxIdv)
            //             .text('\u00A0Variant');
            //         var checkboxContainerv = $('<div class="specification-details-container">')
            //             .append(checkboxv)
            //             .append(label);
            //         $(this).closest('.col-lg-4').append(checkboxContainerv);
            //     }
            // });
            $('select[name^="specification_"]').each(function() {
                var specificationId = $(this).data('specification-id');
                var selectedOption = $(this).val();
                var existingCheckbox = $('#checkbox_specification_' + specificationId);

                if (selectedOption && !existingCheckbox.length) {
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
            // $('.field-checkbox-containerv').remove();
            // var fields = [
            //     { id: 'brands_id', label: 'Brand' },
            //     { id: 'master_model_lines_id', label: 'Model Line' },
            //     { id: 'coo', label: 'COO' },
            //     { id: 'my', label: 'Model Year' },
            //     { id: 'gear', label: 'Gear' },
            //     { id: 'upholstery', label: 'Upholstery' }
            // ];
            // fields.forEach(function (field) {
            //     var checkboxIdv = 'checkbox_field_' + field.id;
            //     var checkbox = $('<input type="checkbox">')
            //         .attr('id', checkboxIdv)
            //         .attr('name', 'fieldvariants')
            //         .data('field-id', field.id);
            //     var label = $('<label>')
            //         .attr('for', checkboxIdv)
            //         .text('\u00A0Variant');
            //     var checkboxContainerv = $('<div class="field-checkbox-containerv">')
            //         .append(checkbox)
            //         .append(label);
            //     $('#' + field.id).closest('.col-lg-2').append(checkboxContainerv);
            // });
            var fields = [{
                    id: 'brands_id',
                    label: 'Brand'
                },
                {
                    id: 'master_model_lines_id',
                    label: 'Model Line'
                },
                {
                    id: 'coo',
                    label: 'COO'
                },
                {
                    id: 'my',
                    label: 'Model Year'
                },
                {
                    id: 'gear',
                    label: 'Gear'
                },
                {
                    id: 'gear',
                    label: 'Gear'
                },
                {
                    id: 'specialEditions',
                    label: 'specialEditions'
                },
                {
                    id: 'others',
                    label: 'others'
                },
                {
                    id: 'upholstery',
                    label: 'Upholstery'
                }
            ];

            fields.forEach(function(field) {
                var existingCheckbox = $('#checkbox_field_' + field.id);
                if (!existingCheckbox.length) {
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
                }
            });
        }
    });
</script>
@endpush