@extends('layouts.main')
@section('content')
<style>
    .invalid-feedback {
        margin-top: 10px !important;
    }

    .error {
        color: red;
    }

    .is-invalid {
        color: red !important;
    }

    .custom-error {
        color: red !important;
        padding-top: 10px;
        margin-bottom: 5px !important;
    }

    .single-input-field {
        padding-bottom: 30px;
    }

    .form-control.is-invalid,
    .was-validated .form-control:invalid {
        color: black !important;
    }
</style>
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('create-model-description');
@endphp
@if ($hasPermission)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Create Master Model Description</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div id="flashMessage"></div>
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
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

        <form id="form-create" action="{{ route('modeldescription.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="choices-single-default" class="form-label">Steering</label>
                    <span class="error">* </span>
                    <select class="form-control select2" autofocus name="steering" id="steering" required>
                        <option value="LHD" {{ old('steering') == 'LHD' ? 'selected' : '' }}>LHD</option>
                        <option value="RHD" {{ old('steering') == 'RHD' ? 'selected' : '' }}>RHD</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="choices-single-default" class="form-label">Brand</label>
                    <span class="error">* </span>
                    <select class="form-control select2 mb-2" autofocus name="brands_id" id="brand" required>
                        <option></option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brands_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->brand_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="model" class="form-label">Model Line</label>
                    <span class="error">* </span>
                    <select class="form-control select2" autofocus name="master_model_lines_id" id="model" required>
                        <option value="" disabled selected>Select a Model Line</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="grade" class="form-label">Grade</label>
                    <select class="form-control select2" name="grade" id="grade">
                        <option value="" disabled selected>Select a Grade</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="specialEditions" class="form-label">Special Editions</label>
                    <input type="text" class="form-control" id="specialEditions" name="specialEditions" placeholder="Enter special edition details">
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="choices-single-default" class="form-label">Engine</label>
                    <span class="error">* </span>
                    <select class="form-control select2" autofocus name="engine" id="engine" required>
                        <option value="" disabled selected>Select Engine</option>
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
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                    <span class="error">* </span>
                    <select class="form-control select2" autofocus name="fuel_type" id="fuel" required>
                        <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="PH" {{ old('fuel_type') == 'PH' ? 'selected' : '' }}>P HEV (Petrol hybrid electrical)</option>
                        <option value="P HEV" {{ old('fuel_type') == 'P HEV' ? 'selected' : '' }}>PHEV (Plug in electrical hybrid)</option>
                        <option value="M HEV" {{ old('fuel_type') == 'M HEV' ? 'selected' : '' }}>M HEV</option>
                        <option value="EV" {{ old('fuel_type') == 'EV' ? 'selected' : '' }}>EV</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="choices-single-default" class="form-label">Gear</label>
                    <span class="error">* </span>
                    <select class="form-control select2" autofocus name="gearbox" id="gear" required>
                        <option value="AT" {{ old('gearbox') == 'AT' ? 'selected' : '' }}>AT</option>
                        <option value="MT" {{ old('gearbox') == 'MT' ? 'selected' : '' }}>MT</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="choices-single-default" class="form-label">Drive Train</label>
                    <select class="form-control select2" autofocus name="drive_train" id="drive_train">
                        <option value="" disabled selected>Drive Train</option>
                        <option value="4X2" {{ old('geadrive_trainrbox') == '4X2' ? 'selected' : '' }}>4X2</option>
                        <option value="4X4" {{ old('geadrive_trainrbox') == '4X4' ? 'selected' : '' }}>4X4</option>
                        <option value="AWD" {{ old('drive_train') == 'AWD' ? 'selected' : '' }}>AWD</option>
                        <!-- <option value="4WD" {{ old('geadrive_trainrbox') == '4WD' ? 'selected' : '' }}>4WD</option> -->
                        <option value="FWD" {{ old('geadrive_trainrbox') == 'FWD' ? 'selected' : '' }}>FWD</option>
                        <option value="RWD" {{ old('geadrive_trainrbox') == 'RWD' ? 'selected' : '' }}>RWD</option>

                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="choices-single-default" class="form-label">Window Type</label>
                    <select class="form-control select2" autofocus name="window_type" id="window_type">
                        <option value="" disabled selected>Select Window Type</option>
                        <option value="P.Window" {{ old('gearbox') == 'P.Window' ? 'selected' : '' }}>P.Window</option>
                        <option value="M.Window" {{ old('gearbox') == 'M.Window' ? 'selected' : '' }}>M.Window</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="specialEditions" class="form-label">Others Important Option</label>
                    <input type="text" class="form-control" id="others" name="others" placeholder="Enter Other details">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12 text-center">
                    <input type="submit" name="submit" value="Submit" class="btn btn-success" />
                </div>
                <div class="col-lg-12 col-md-12" id="model-detail-section">
                    <label for="summary" class="form-label">Model Detail</label>
                    <input type="text" class="form-control" id="summary" name="model_description" readonly>
                </div>
            </div>
        </form>
    </div>
</div>
@else
@php
redirect()->route('home')->send();
@endphp
@endif
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({});

        $('.select2').on('change', function() {
            $(this).valid();
        });

        $('#brand').select2({
            placeholder: 'Select Brand',
        }).on('change', function() {
            $('#brand-error').remove();
        });
        $('#model').select2({
            placeholder: 'Select Model Line',
        }).on('change', function() {
            $('#model-error').remove();
        });
        $.validator.addMethod("spaceCheck", function(value, element) {
            return this.optional(element) || !/\s\s+/.test(value);
        }, "No more than one consecutive space is allowed in the feild");

        $.validator.addMethod("noSpaces", function(value, element) {
            return this.optional(element) || /^[^\s]+(\s+[^\s]+)*$/.test(value);
        }, "No leading or trailing spaces allowed");

        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, "Only alphanumeric characters are allowed.");

        $.validator.addMethod("alphanumericDot", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9.]+$/.test(value);
        }, "Only letters, numbers, and dots are allowed.");

        $.validator.addMethod("noLeadingTrailingSpaces", function(value, element) {
            return this.optional(element) || !/^\s|\s$/.test(value);
        }, "No leading or trailing spaces are allowed");

        $.validator.addMethod("noMultipleSpaces", function(value, element) {
            return this.optional(element) || !/\s{2,}/.test(value);
        }, "Multiple consecutive spaces are not allowed.");

        $.validator.addMethod("onlyHyphenAllowed", function(value, element) {
            return this.optional(element) || /^[A-Z0-9\s-]+$/.test(value);
        }, "Only capital letters, numbers, spaces, and hyphen (-) are allowed");

        $.validator.addMethod("noSpaceAroundHyphen", function(value, element) {
            return this.optional(element) || !/\s-|-\s/.test(value);
        }, "No spaces are allowed around hyphen (-)");

        $.validator.addMethod("noMultipleHyphens", function(value, element) {
            return this.optional(element) || !/--/.test(value);
        }, "Multiple hyphens are not allowed");

        // Validation method for allowed symbols for others' input
        $.validator.addMethod("allowedSpecialSymbols", function(value, element) {
            return this.optional(element) || /^[A-Z0-9\s\/\+]+$/.test(value);
        }, "Only capital letters, numbers, spaces, / and + are allowed.");

        // Validation method for no spaces around symbols for others' input 
        $.validator.addMethod("noSpaceAroundSpecialSymbols", function(value, element) {
            return this.optional(element) || !/\s[\/\+]|[\/\+]\s/.test(value);
        }, "No spaces are allowed around / or + symbols.");

        // Validation method for no multiple symbols for others' input
        $.validator.addMethod("noMultipleSpecialSymbols", function(value, element) {
            return this.optional(element) || !/\/\/|\+\+/.test(value);
        }, "Multiple / or + symbols are not allowed.");

        // Convert specialEditions and others input to uppercase
        $('#specialEditions, #others').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Convert model_description input to uppercase
        $('#model_description').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        $("#form-create").validate({
            ignore: ":hidden:not(.ignore), :disabled",
            rules: {
                brands_id: {
                    required: true,
                },
                master_model_lines_id: {
                    required: true,
                },
                model_description: {
                    // required: true,
                    spaceCheck: true,
                    maxlength: 255,
                    noSpaces: true
                },
                others: {
                    noLeadingTrailingSpaces: true,
                    noMultipleSpaces: true,
                    allowedSpecialSymbols: true,
                    noSpaceAroundSpecialSymbols: true,
                    noMultipleSpecialSymbols: true
                },
                specialEditions: {
                    noLeadingTrailingSpaces: true,
                    noMultipleSpaces: true,
                    onlyHyphenAllowed: true,
                    noSpaceAroundHyphen: true,
                    noMultipleHyphens: true
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function(error, element) {
                const arrName = ["steering", "brands_id", "master_model_lines_id", "engine", "fuel_type", "gearbox"];
                if (arrName.includes(element.attr("name"))) {
                    error.addClass('custom-error');
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $('#brand').on('change', function() {
            var selectedBrandId = $(this).val();
            if (selectedBrandId) {
                $.ajax({
                    url: '/get-model-lines/' + selectedBrandId,
                    type: 'GET',
                    success: function(data) {
                        $('#model').empty();
                        $('#model').append('<option value="" disabled selected>Select a Model Line</option>');
                        $.each(data, function(index, modelLine) {
                            $('#model').append('<option value="' + modelLine.id + '">' + modelLine.model_line + '</option>');
                        });
                        $('#model').prop('disabled', false);
                    },
                    error: function(error) {
                        console.log('Error fetching model lines:', error);
                    }
                });
            } else {
                $('#model').empty();
                $('#model').append('<option value="" disabled selected>Select a Model Line</option>');
                $('#model').prop('disabled', true);
            }
        });
        $('#model').on('change', function() {
            var selectedModelId = $(this).val();
            if (selectedModelId) {
                $.ajax({
                    url: '/get-grades/' + selectedModelId,
                    type: 'GET',
                    success: function(data) {
                        $('#grade').empty();
                        $('#grade').append('<option value="" disabled selected>Select a Grade</option>');
                        $.each(data, function(index, grade) {
                            $('#grade').append('<option value="' + grade.id + '">' + grade.grade_name + '</option>');
                        });
                        $('#grade').prop('disabled', false);
                    },
                    error: function(error) {
                        console.log('Error fetching grades:', error);
                    }
                });
            } else {
                $('#grade').empty();
                $('#grade').append('<option value="" disabled selected>Select a Grade</option>');
                $('#grade').prop('disabled', true);
            }
        });
    });
    $(document).ready(function() {
        function updateSummary() {
            var steering = $('#steering').val() || '';
            var brand = $('#brand option:selected').text() || '';
            var model = $('#model option:selected').text() || '';
            var grade = $('#grade').val() && $('#grade').val() !== 'Select a Grade' ? $('#grade option:selected').text() : '';
            var engine = $('#engine').val() || '';
            var fuel = $('#fuel').val() || '';
            if (fuel === 'Petrol') {
                fuel = 'P';
            } else if (fuel === 'Diesel') {
                fuel = 'D';
            } else if (fuel === 'PH') {
                fuel = 'P HEV';
            }
            var gear = $('#gear').val() ? $('#gear').val() : '';
            var driveTrain = $('#drive_train').val() ? $('#drive_train').val() : '';
            var windowType = $('#window_type').val() ? $('#window_type').val() : '';
            var specialEditions = $('#specialEditions').val() ? $('#specialEditions').val() : '';
            var others = $('#others').val() ? $('#others').val() : '';
            var engineFuel = engine + fuel;
            var summary = [
                    steering,
                    model,
                    grade,
                    specialEditions,
                    engineFuel,
                    gear,
                    driveTrain,
                    windowType,
                    others
                ]
                .filter(Boolean)
                .join(' ');

            // Check if all required fields are filled
            let allRequiredFilled = true;
            if (!steering || !brand || !model || !fuel || !gear) {
                allRequiredFilled = false;
            }

            // Conditionally check engine field if fuel is NOT EV
            if (allRequiredFilled && fuel !== 'EV' && !engine) {
                allRequiredFilled = false;
            }

            if (allRequiredFilled) {
                $('#summary').val(summary.toUpperCase());
            } else {
                $('#summary').val('');
            }
        }
        $('#steering, #brand, #model, #grade, #specialEditions, #engine, #fuel, #gear, #drive_train, #window_type, #others').on('change input', updateSummary);
    });

    $(document).ready(function() {
        $('#fuel').on('change', function() {
            const engineSelect = $('#engine');

            if ($(this).val() === 'EV') {
                // Reset and disable engine selection
                engineSelect.val(null).trigger('change'); // Reset value and update select2 UI
                engineSelect.rules('remove', 'required'); // Remove required rule for validation
                engineSelect.prop('disabled', true); // Disable the dropdown
            } else {
                // Enable and make required
                engineSelect.prop('disabled', false); // Enable the dropdown
                engineSelect.rules('add', 'required'); // Add required rule for validation
            }
            updateSummary(); // Always call updateSummary after fuel type changes just to align with EV type
        });
    });
</script>
@endpush