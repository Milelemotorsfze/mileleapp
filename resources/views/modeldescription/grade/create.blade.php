@extends('layouts.main')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-grade');
@endphp
@if ($hasPermission)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Create Master Model Grades</h4>
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
        <form id="form-create" action="{{ route('mastergrade.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="master_grade" class="form-label"><span class="text-danger">*</span> Master Grade Name</label>
                    <input type="text" name="master_grade" class="form-control" value="{{ old('master_grade') }}" required />
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="brand" class="form-label"><span class="text-danger">*</span> Brand</label>
                    <select class="form-control select2" autofocus name="brands_id" id="brand" required>
                        <option value="" disabled {{ old('brands_id') ? '' : 'selected' }}>Please select brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brands_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->brand_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="model" class="form-label"><span class="text-danger">*</span> Model Line</label>
                    <select class="form-control select2" autofocus name="master_model_lines_id" id="model" required>
                        <option value="" disabled {{ old('master_model_lines_id') ? '' : 'selected' }}>Select a Model Line</option>
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

        // If there's an old brand value, load its model lines
        var oldBrandId = "{{ old('brands_id') }}";
        if (oldBrandId) {
            $.ajax({
                url: '/get-model-lines/' + oldBrandId,
                type: 'GET',
                success: function(data) {
                    $('#model').empty();
                    $('#model').append('<option value="" disabled>Select a Model Line</option>');
                    $.each(data, function(index, modelLine) {
                        var selected = modelLine.id == "{{ old('master_model_lines_id') }}" ? 'selected' : '';
                        $('#model').append('<option value="' + modelLine.id + '" ' + selected + '>' + modelLine.model_line + '</option>');
                    });
                    $('#model').prop('disabled', false);
                },
                error: function(error) {
                    console.log('Error fetching model lines:', error);
                }
            });
        }

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
                        $('#model').empty();
                        $('#model').append('<option value="" disabled>Select a Model Line</option>');
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