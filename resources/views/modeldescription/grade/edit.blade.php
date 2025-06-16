@extends('layouts.main')
@section('content')

<style>
    .is-invalid,
    .is-invalid-border {
        border-color: red !important;
    }

    .is-invalid {
        color: red !important;
    }

    .custom-error {
        color: red !important;
        padding-top: 10px;
    }
</style>

@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('update-master-grade');
@endphp
@if ($hasPermission)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Update Master Model Grades</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div id="flashMessage"></div>
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
        <form id="form-create" action="{{ route('mastergrade.update', $grade->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="master_grade" class="form-label"><span class="text-danger">*</span> Master Grade Name</label>
                    <input type="text" name="master_grade" class="form-control" placeholder="Enter Master Grade Name" value="{{ old('master_grade', $grade->grade_name) }}" required />
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="brand" class="form-label"><span class="text-danger">*</span> Brand</label>
                    <select class="form-control select2" autofocus name="brands_id" id="brand" required>
                        <option value="" disabled selected>Please select brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}"
                            {{ old('brands_id', optional(optional($grade->modelLine)->brand)->id) == $brand->id ? 'selected' : '' }}>
                            {{ $brand->brand_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="model" class="form-label"><span class="text-danger">*</span> Model Line</label>
                    <select class="form-control select2" autofocus name="master_model_lines_id" id="model" required>
                        <option value="{{ $grade->model_line_id ?? '' }}" selected>{{ optional($grade->modelLine)->model_line ?? 'Select a Model Line' }}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12 text-center">
                    <input type="submit" name="submit" value="Submit" class="btn btn-success" />
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
        // Initialize select2
        $('.select2').select2();

        $('.select2').on('change', function() {
            $(this).valid();
        });

        $.validator.addMethod("noLeadingTrailingSpaces", function(value, element) {
            return this.optional(element) || !/^\s|\s$/.test(value);
        }, "No leading or trailing spaces are allowed.");

        // No multiple consecutive spaces
        $.validator.addMethod("noMultipleSpaces", function(value, element) {
            return this.optional(element) || !/\s{2,}/.test(value);
        }, "Multiple consecutive spaces are not allowed.");

        // Only alphanumeric, +, and -
        $.validator.addMethod("onlyAlphaNumPlusMinus", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9+\-\s]+$/.test(value);
        }, "Only letters, numbers, plus (+), and minus (-) symbols are allowed.");

        // No spaces around + or -
        $.validator.addMethod("noSpaceAroundSymbols", function(value, element) {
            return this.optional(element) || !/\s[+-]|[+-]\s/.test(value);
        }, "No spaces are allowed around '+' or '-' symbols.");

        // No multiple consecutive symbols like ++, --, +-, -+
        $.validator.addMethod("noConsecutiveSymbols", function(value, element) {
            return this.optional(element) || !/([+-]){2,}/.test(value) && !/[\+\-]{2,}/.test(value);
        }, "Multiple consecutive symbols (+ or -) are not allowed.");

        $("#form-create").validate({
            ignore: [],
            rules: {
                master_grade: {
                    required: true,
                    maxlength: 255,
                    noLeadingTrailingSpaces: true,
                    noMultipleSpaces: true,
                    onlyAlphaNumPlusMinus: true,
                    noSpaceAroundSymbols: true,
                    noConsecutiveSymbols: true
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "brands_id" || element.attr("name") === "master_model_lines_id") {
                    error.addClass('custom-error');
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2-container').find('.select2-selection--single').addClass('is-invalid-border');
                    $(element).next('.select2-container').find('.select2-selection__rendered').addClass('is-invalid-border');
                } else {
                    $(element).addClass('is-invalid-border');
                }
            },
            unhighlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                    $(element).next('.select2-container').find('.select2-selection--single').removeClass('is-invalid-border');
                    $(element).next('.select2-container').find('.select2-selection__rendered').removeClass('is-invalid-border');
                } else {
                    $(element).removeClass('is-invalid-border');
                }
            }
        });

        // Handle brand change event
        $('#brand').on('change', function() {
            var selectedBrandId = $(this).val();
            if (selectedBrandId) {
                $.ajax({
                    url: '/get-model-lines/' + selectedBrandId,
                    type: 'GET',
                    success: function(data) {
                        console.log(data)
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
    });
</script>
@endpush