@extends('layouts.main')
@section('content')
<style>
    .invalid-feedback { margin-top: 10px !important; }
    .error { color: red; }
    .is-invalid { color: red !important; }
    .custom-error { color: red !important; padding-top: 10px; margin-bottom: 5px !important; }
    .single-input-field { padding-bottom: 30px; }
    .form-control.is-invalid, .was-validated .form-control:invalid { color: black !important; }
</style>
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('create-model-description');
$oldBrandId = old('brands_id', $modelDescription->modelLine ? $modelDescription->modelLine->brand_id : null);
$oldModelId = old('master_model_lines_id', $modelDescription->model_line_id);
$oldGradeId = old('grade', $modelDescription->master_vehicles_grades_id);
$modelLines = $oldBrandId ? \App\Models\MasterModelLines::where('brand_id', $oldBrandId)->get() : collect();
$grades = $oldModelId ? \App\Models\MasterGrades::where('model_line_id', $oldModelId)->get() : collect();
@endphp
@if ($hasPermission)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Master Model Description</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div id="flashMessage"></div>
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form id="form-edit" action="{{ route('modeldescription.update', $modelDescription->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label class="form-label">Steering</label>
                    <span class="error">* </span>
                    <select class="form-control select2" name="steering" id="steering" required>
                        <option value="LHD" {{ old('steering', $modelDescription->steering) == 'LHD' ? 'selected' : '' }}>LHD</option>
                        <option value="RHD" {{ old('steering', $modelDescription->steering) == 'RHD' ? 'selected' : '' }}>RHD</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Brand</label>
                    <span class="error">* </span>
                    <select class="form-control select2 mb-2" name="brands_id" id="brand" required>
                        <option></option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $oldBrandId == $brand->id ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="model" class="form-label">Model Line</label>
                    <span class="error">* </span>
                    <select class="form-control select2" name="master_model_lines_id" id="model" required>
                        <option value="" disabled>Select a Model Line</option>
                        @foreach($modelLines as $modelLine)
                            <option value="{{ $modelLine->id }}" {{ $oldModelId == $modelLine->id ? 'selected' : '' }}>{{ $modelLine->model_line }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="grade" class="form-label">Grade</label>
                    <select class="form-control select2" name="grade" id="grade">
                        <option value="">-- None --</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ $oldGradeId == $grade->id ? 'selected' : '' }}>{{ $grade->grade_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label for="specialEditions" class="form-label">Special Editions</label>
                    <input type="text" class="form-control" id="specialEditions" name="specialEditions" value="{{ old('specialEditions', $modelDescription->specialEditions) }}">
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label class="form-label">Fuel Type</label>
                    <span class="error">* </span>
                    <select class="form-control select2" name="fuel_type" id="fuel" required>
                        <option value="Petrol" {{ old('fuel_type', $modelDescription->fuel_type) == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="Diesel" {{ old('fuel_type', $modelDescription->fuel_type) == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="PH" {{ old('fuel_type', $modelDescription->fuel_type) == 'PH' ? 'selected' : '' }}>P HEV (Petrol hybrid electrical)</option>
                        <option value="P HEV" {{ old('fuel_type', $modelDescription->fuel_type) == 'P HEV' ? 'selected' : '' }}>PHEV (Plug in electrical hybrid)</option>
                        <option value="M HEV" {{ old('fuel_type', $modelDescription->fuel_type) == 'M HEV' ? 'selected' : '' }}>M HEV</option>
                        <option value="EV" {{ old('fuel_type', $modelDescription->fuel_type) == 'EV' ? 'selected' : '' }}>EV</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6" id="engine-block">
                    <label class="form-label">Engine</label>
                    <span class="error">* </span>
                    <select class="form-control select2" name="engine" id="engine" required>
                        <option value="" disabled>Select Engine</option>
                        @php $engines = ['0.8','1.0','1.2','1.3','1.4','1.5','1.6','1.8','2.0','2.2','2.4','2.5','2.7','2.8','3.0','3.3','3.4','3.5','3.6','3.8','4.0','4.2','4.4','4.5','4.6','4.8','5.0','5.2','5.3','5.5','5.6','5.7','5.9','6.0','6.2','6.7']; @endphp
                        @foreach($engines as $engine)
                        <option value="{{ $engine }}" {{ old('engine', $modelDescription->engine) == $engine ? 'selected' : '' }}>{{ $engine }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label class="form-label">Gear</label>
                    <span class="error">* </span>
                    <select class="form-control select2" name="gearbox" id="gear" required>
                        <option value="AT" {{ old('gearbox', $modelDescription->transmission) == 'AT' ? 'selected' : '' }}>AT</option>
                        <option value="MT" {{ old('gearbox', $modelDescription->transmission) == 'MT' ? 'selected' : '' }}>MT</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label class="form-label">Drive Train</label>
                    <select class="form-control select2" name="drive_train" id="drive_train">
                        <option value="">-- None --</option>
                        <option value="4X2" {{ old('drive_train', $modelDescription->drive_train) == '4X2' ? 'selected' : '' }}>4X2</option>
                        <option value="4X4" {{ old('drive_train', $modelDescription->drive_train) == '4X4' ? 'selected' : '' }}>4X4</option>
                        <option value="AWD" {{ old('drive_train', $modelDescription->drive_train) == 'AWD' ? 'selected' : '' }}>AWD</option>
                        <option value="4WD" {{ old('drive_train', $modelDescription->drive_train) == '4WD' ? 'selected' : '' }}>4WD</option>
                        <option value="FWD" {{ old('drive_train', $modelDescription->drive_train) == 'FWD' ? 'selected' : '' }}>FWD</option>
                        <option value="RWD" {{ old('drive_train', $modelDescription->drive_train) == 'RWD' ? 'selected' : '' }}>RWD</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label class="form-label">Window Type</label>
                    <select class="form-control select2" name="window_type" id="window_type">
                        <option value="">-- None --</option>
                        <option value="P.Window" {{ old('window_type', $modelDescription->window_type) == 'P.Window' ? 'selected' : '' }}>P.Window</option>
                        <option value="MT" {{ old('window_type', $modelDescription->window_type) == 'MT' ? 'selected' : '' }}>MT</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 single-input-field">
                    <label class="form-label">Others Important Option</label>
                    <input type="text" class="form-control" id="others" name="others" value="{{ old('others', $modelDescription->others) }}">
                </div>
                <div class="col-lg-12 col-md-12" id="model-detail-section">
                    <label class="form-label">Model Description</label>
                    <input type="text" class="form-control" id="summary" name="model_description" readonly value="{{ old('model_description', $modelDescription->model_description) }}">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12 text-center">
                    <input type="submit" name="submit" value="Update" class="btn btn-success" />
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
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
            .join(' ')
            .replace(/\s{2,}/g, ' '); // Collapse multiple spaces into one

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
    $(document).ready(function() {
        $('.select2').select2({});
        $('#brand').select2({ placeholder: 'Select Brand' });
        $('#model').select2({ placeholder: 'Select Model Line' });
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
            return this.optional(element) || !/\s-|\s-/.test(value);
        }, "No spaces are allowed around hyphen (-)");
        $.validator.addMethod("noMultipleHyphens", function(value, element) {
            return this.optional(element) || !/--/.test(value);
        }, "Multiple hyphens are not allowed");
        $.validator.addMethod("allowedSpecialSymbols", function(value, element) {
            return this.optional(element) || /^[A-Z0-9\s\/\+]+$/.test(value);
        }, "Only capital letters, numbers, spaces, / and + are allowed.");
        $.validator.addMethod("noSpaceAroundSpecialSymbols", function(value, element) {
            return this.optional(element) || !/\s[\/\+]|[\/\+]\s/.test(value);
        }, "No spaces are allowed around / or + symbols.");
        $.validator.addMethod("noMultipleSpecialSymbols", function(value, element) {
            return this.optional(element) || !/\/\/|\+\+/.test(value);
        }, "Multiple / or + symbols are not allowed.");
        $('#specialEditions, #others').on('input', function() {
            this.value = this.value.toUpperCase();
        });
        $('#model_description').on('input', function() {
            this.value = this.value.toUpperCase();
        });
        $("#form-edit").validate({
            ignore: ":hidden:not(.ignore), :disabled",
            rules: {
                brands_id: { required: true },
                master_model_lines_id: { required: true },
                model_description: { spaceCheck: true, maxlength: 255, noSpaces: true },
                others: { noLeadingTrailingSpaces: true, noMultipleSpaces: true, allowedSpecialSymbols: true, noSpaceAroundSpecialSymbols: true, noMultipleSpecialSymbols: true },
                specialEditions: { noLeadingTrailingSpaces: true, noMultipleSpaces: true, onlyHyphenAllowed: true, noSpaceAroundHyphen: true, noMultipleHyphens: true }
            },
            highlight: function(element) { $(element).addClass('is-invalid'); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); },
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
                        var oldModelId = '{{ old('master_model_lines_id', $modelDescription->model_line_id) }}';
                        if (oldModelId) {
                            $('#model').val(oldModelId).trigger('change.select2');
                        }
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
        var oldBrandId = '{{ old("brands_id", $modelDescription->modelLine ? $modelDescription->modelLine->brand_id : null) }}';
        if (oldBrandId) {
            $('#brand').val(oldBrandId).trigger('change');
        }
        $('#steering, #brand, #model, #grade, #specialEditions, #engine, #fuel, #gear, #drive_train, #window_type, #others').on('change input', updateSummary);
        $('#fuel').on('change', function() {
            const engineSelect = $('#engine');
            if ($(this).val() === 'EV') {
                engineSelect.val(null).trigger('change').prop('disabled', true).rules('remove', 'required');
            } else {
                engineSelect.prop('disabled', false).rules('add', 'required');
            }
            updateSummary();
        });
        if ('{{ old("steering", $modelDescription->steering) }}' || '{{ old("engine", $modelDescription->engine) }}' || '{{ old("fuel_type", $modelDescription->fuel_type) }}' || '{{ old("gearbox", $modelDescription->transmission) }}' || '{{ old("drive_train", $modelDescription->drive_train) }}' || '{{ old("window_type", $modelDescription->window_type) }}' || '{{ old("specialEditions", $modelDescription->specialEditions) }}' || '{{ old("others", $modelDescription->others) }}') {
            updateSummary();
        }
        function toggleEngineBlock() {
            if ($('#fuel').val() === 'EV') {
                $('#engine-block').hide();
            } else {
                $('#engine-block').show();
            }
        }
        toggleEngineBlock();
        $('#fuel').on('change', toggleEngineBlock);
    });
</script>
@endpush 