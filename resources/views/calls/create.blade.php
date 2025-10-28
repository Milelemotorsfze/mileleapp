@extends('layouts.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
    .select2-container {
        width: 100% !important;
    }

    .form-label[for="basicpill-firstname-input"] {
        margin-top: 12px;
    }

    .btn.btn-success.btncenter {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn.btn-success.btncenter:hover {
        background-color: #0000ff;
        font-size: 17px;
        border-radius: 10px;
    }

    /* Media Query for small screens */
    @media (max-width: 767px) {
        .col-lg-12.col-md-12 {
            text-align: center;
        }
    }

    .error {
        color: #FF0000;
    }

    .iti {
        width: 100%;
    }

    label {
        display: inline-block;
        margin-right: 10px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0;
    }

    .error-text {
        color: #FF0000;
    }
    .remarks-single-div-container{
        text-align: left !important;
    }

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">New Calls & Messages</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="col-lg-12">
        <div id="flashMessage"></div>
    </div>
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
    {!! Form::open(array('route' => 'calls.store','method'=>'POST', 'id' => 'calls')) !!}
    <div class="row">
        <p><span style="float:right;" class="error">* Required Field</span></p>
    </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                {!! Form::text('name', old('name'), ['placeholder' => 'Name', 'class' => 'form-control']) !!}
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Customer Phone:</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" autocomplete="off" value="{{ old('phone') }}">
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="error">*</span>
                <label for="basicpill-firstname-input" class="form-label">Customer Email:</label>
                {!! Form::email('email', old('email'), ['id' => 'email', 'placeholder' => 'Email', 'class' => 'form-control']) !!}
                <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}" autocomplete="off">
                <div id="emailError" class="error-text"></div>
            </div>
            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="milelemotorsSelect" class="form-label">Source:</label>
                <select name="milelemotors" class="form-control select2" id="milelemotorsSelect" multiple>
                    <option value="">Select Source</option>
                    @foreach ($LeadSource as $source)
                    <option value="{{ $source->source_name }}" {{ old('milelemotors') == $source->source_name ? 'selected' : '' }}>
                        {{ $source->source_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">*</span>
                <label for="languageSelect" class="form-label">Preferred Language:</label>
                <select name="language[]" class="form-control select2" id="languageSelect" multiple>
                    <option value="">Select Language</option>
                    @foreach ($Language as $language)
                    <option value="{{ $language->name }}" {{ old('language') == $language->name ? 'selected' : '' }}>
                        {{ $language->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="locationSelect" class="form-label">Destination:</label>
                <select name="location" class="form-control select2" id="locationSelect" multiple>
                    <option value="">Select Destination</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country }}" {{ old('location') == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                    @endforeach
                    <option value="Not Mentioned" {{ old('location') == 'Not Mentioned' ? 'selected' : '' }}>Not Mentioned</option>
                </select>
            </div>

            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="typeSelect" class="form-label">Type:</label>
                <select name="type" class="form-control select2" id="typeSelect" multiple>
                    <option value="">Select Type</option>
                    <option value="Export" {{ old('type') == 'Export' ? 'selected' : '' }}>Export</option>
                    <option value="Local" {{ old('type') == 'Local' ? 'selected' : '' }}>Local</option>
                    <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="col-lg-4 col-md-6 pt-1">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Strategies:</label>
                <select name="strategy" class="form-control select2" id="strategyInput" multiple>
                    @foreach ($strategy as $strategies)
                    <option value="{{ $strategies->name }}" {{ old('strategy') == $strategies->name ? 'selected' : '' }}>{{ $strategies->name }} </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="prioritySelect" class="form-label">Priority:</label>
                <select name="priority" class="form-control select2" id="prioritySelect" multiple>
                    <option value="">Select Priority</option>
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="hot" {{ old('priority') == 'hot' ? 'selected' : '' }}>Hot</option>
                </select>
            </div>

        </div>
        </br>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="sales-options" class="form-label">Sales Persons Options:</label>
                <div>
                    <label>
                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign" {{ old('sales-option', 'auto-assign') == 'auto-assign' ? 'checked' : '' }}> System Auto Assign
                    </label>
                    <label>
                        <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign" {{ old('sales-option') == 'manual-assign' ? 'checked' : '' }}> Manual Assign
                    </label>
                </div>
            </div>

            <input type="hidden" id="sales-option-value" name="sales-option" value="{{ old('sales-option', 'auto-assign') }}">
            
            <div class="col-lg-4 col-md-6" id="manual-sales-person-list" style="display: none;">
                <span class="error">* </span>
                <label for="sales_person" class="form-label">Sales Person:</label>
                <select name="sales_person_id" id="salesPersonSelect" class="form-control select2" multiple>
                    <option value="">Select Sales Person</option>
                    @foreach ($sales_persons as $sales_person)
                    <option value="{{ $sales_person->id }}" {{ old('sales_person_id') == $sales_person->id ? 'selected' : '' }}>
                        {{ $sales_person->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="maindd">
            <div id="row-container">

                @if(old('model_line_ids'))
                    @foreach(old('model_line_ids') as $id)
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <label for="brandModelSelect" class="form-label">Brand & Model:</label>
                                <select name="model_line_ids[]" class="form-control select2" multiple>
                                    <option value="">Select Brand & Model</option>
                                    @foreach ($modelLineMasters as $modelLineMaster)
                                        @php
                                            $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                                            $combinedValue = $brand ? $brand->brand_name . ' / ' . $modelLineMaster->model_line : 'Unknown Brand / ' . $modelLineMaster->model_line;
                                        @endphp
                                        <option value="{{ $modelLineMaster->id }}" {{ $id == $modelLineMaster->id ? 'selected' : '' }}>
                                            {{ $combinedValue }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <label for="brandModelSelect" class="form-label">Brand & Model:</label>
                            <select name="model_line_ids[]" class="form-control select2" multiple>
                                <option value="">Select Brand & Model</option>
                                @foreach ($modelLineMasters as $modelLineMaster)
                                    @php
                                        $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                                        $combinedValue = $brand ? $brand->brand_name . ' / ' . $modelLineMaster->model_line : 'Unknown Brand / ' . $modelLineMaster->model_line;
                                    @endphp
                                    <option value="{{ $modelLineMaster->id }}" {{ old('model_line_ids.0') == $modelLineMaster->id ? 'selected' : '' }}>
                                        {{ $combinedValue }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-start">
                <div class="btn btn-primary add-row-btn">
                    <i class="fas fa-plus"></i> Add More
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Custom Brand & Model : </label>
            {!! Form::text('custom_brand_model', null, array('placeholder' => 'Custom Brand & Model','class' => 'form-control')) !!}
        </div>
        <!-- <div class="col-lg-12 col-md-12">
            <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
            <textarea id="summernote" name="remarks">{{ old('remarks') }}</textarea>
        </div> -->

        <div class="col-lg-12 col-md-12 mt-3">
            <label class="form-label"><strong>Lead Summary - Qualification Notes:</strong></label>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Car Interested In:</label>
                    <input type="text" name="car_model" class="form-control mb-3" id="car_model" placeholder="Interested car model" value="{{ old('car_model') }}">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Purpose of Purchase:</label>
                    <input type="text" name="purchase_purpose" class="form-control mb-3" id="purchase_purpose" placeholder="Purpose of Purchasing" value="{{ old('purchase_purpose') }}">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>End User:</label>
                    <select class="form-control mb-3" name="end_user" id="end_user">
                        <option value="">Select Value</option>
                        <option value="Yes" {{ strtolower(old('end_user')) == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ strtolower(old('end_user')) == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label for="destination_country" class="form-label">Destination Country:</label>
                    <select class="form-control mb-3 select2" name="destination_country" id="destination_country" multiple>
                        <option value="">Select Destination</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country }}" {{ old('destination_country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Planned Units:</label>
                    <input type="text" name="planned_units" class="form-control mb-3" id="planned_units" placeholder="Planned Units" value="{{ old('planned_units') }}">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Experience with UAE Sourching:</label>
                     <select class="form-control mb-3" name="source_experience" id="source_experience">
                        <option value="">Select Value</option>
                        <option value="Yes" {{ strtolower(old('source_experience')) == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ strtolower(old('source_experience')) == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                 <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Shipping Assistance Required:</label>
                    <select class="form-control mb-3" name="shipping_required" id="shipping_required">
                        <option value="">Select Value</option>
                        <option value="Yes" {{ strtolower(old('shipping_required')) == 'yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ strtolower(old('shipping_required')) == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                 <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Payment Method:</label>
                    <input type="text" name="payment_method" class="form-control mb-3" id="payment_method" placeholder="Payment Method" value="{{ old('payment_method') }}">
                </div>
                 <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Previous Purchase History:</label>
                    <input type="text" name="prev_purchase_history" class="form-control mb-3" id="prev_purchase_history" placeholder="Previous Purchase History" value="{{ old('prev_purchase_history') }}">
                </div>
                 <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>Purchase Timeline:</label>
                    <input type="text" name="purchase_timeline" class="form-control mb-3" id="purchase_timeline" placeholder="Purchase Timeline" value="{{ old('purchase_timeline') }}">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>CSR Price:</label>
                    <input type="text" name="csr_price" class="form-control mb-3" id="csr_price" placeholder="CSR Price" value="{{ old('csr_price') ? number_format(old('csr_price'), 2, '.', ',') : '' }}">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 remarks-single-div-container">
                    <label>CSR Currency:</label>
                    <select class="form-control mb-3" name="csr_currency" id="csr_currency">
                        <option value="AED" {{ old('csr_currency', 'AED') == 'AED' ? 'selected' : '' }}>AED</option>
                        <option value="USD" {{ old('csr_currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('csr_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>
                <div class="col-md-12 remarks-single-div-container">
                    <label>General Remark / Additional Notes:</label>
                    <textarea class="form-control" name="general_remark" id="general_remark" placeholder="Additional Extra Remarks/Notes">{{ old('general_remark') }}</textarea>
                </div>
            </div>
            <!-- Hidden field for compiled remarks -->
            <input type="hidden" name="remarks" id="compiled_remarks">
        </div>

    </div>
    </br>
    </br>
<div class="col-lg-12 col-md-12">
    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
</div>
{!! Form::close() !!}
</br>
</div>
@else
@php
redirect()->route('home')->send();
@endphp
@endif
@endsection
@push('scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['picture', 'link', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0]);
                }
            }
        });

        function uploadImage(file) {
            let data = new FormData();
            data.append("file", file);
            data.append("_token", "{{ csrf_token() }}");

            $.ajax({
                url: "{{ route('summernote.upload') }}",
                method: "POST",
                data: data,
                contentType: false,
                processData: false,
                success: function(url) {
                    $('#summernote').summernote('insertImage', url);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Image upload failed.");
                    console.error(textStatus + " " + errorThrown);
                }
            });
        }
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            maximumSelectionLength: 1,
        });
    });

    const autoAssignOption = document.getElementById('auto-assign-option');
    const manualAssignOption = document.getElementById('manual-assign-option');
    const manualSalesPersonList = document.getElementById('manual-sales-person-list');
    const salesOptionValueField = document.getElementById('sales-option-value');

    autoAssignOption.addEventListener('change', () => {
        manualSalesPersonList.style.display = 'none';
        salesOptionValueField.value = autoAssignOption.value;
    });

    manualAssignOption.addEventListener('change', () => {
        manualSalesPersonList.style.display = 'block';
        salesOptionValueField.value = manualAssignOption.value;
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (manualAssignOption.checked) {
            manualSalesPersonList.style.display = 'block';
            salesOptionValueField.value = manualAssignOption.value;
        } else {
            manualSalesPersonList.style.display = 'none';
            salesOptionValueField.value = autoAssignOption.value;
        }
    });

    $(document).ready(function() {
        var max_fields = 10;
        var wrapper = $("#row-container");
        var add_button = $(".add-row-btn");
        var x = 1;

        // Function to filter and update the dropdown list
        function updateDropdownList() {
            var selectedValues = $('input[name="model_line_id[]"]').map(function() {
                return $(this).val();
            }).get();

            $('.new-select').each(function() {
                var currentInput = $(this);
                var datalistId = currentInput.attr('list');
                var datalist = $('#' + datalistId);
                var options = '';

                $('#brandList option').each(function() {
                    if (selectedValues.indexOf($(this).val()) === -1) {
                        options += '<option value="' + $(this).val() + '" data-value="' + $(this).data('value') + '"></option>';
                    }
                });

                datalist.html(options);
            });
        }

        $(add_button).click(function(e) {
            e.preventDefault();
            if (x < max_fields) {
                x++;
                var selectedValues = $('input[name="model_line_id[]"]').map(function() {
                    return $(this).val();
                }).get();
                var datalist = $('<datalist id="brandList' + x + '"></datalist>');
                var options = '';
                $('#brandList option').each(function() {
                    if (selectedValues.indexOf($(this).val()) === -1) {
                        options += '<option value="' + $(this).val() + '" data-value="' + $(this).data('value') + '"></option>';
                    }
                });
                datalist.html(options);
                var newRow = $('<div class="row"></div>');
                var col1 = $('<div class="col-lg-4 col-md-6"></div>');
                var select = $('<select name="model_line_ids[]" class="form-control select2" multiple><option value="">Select Brand & Model</option></select>');
                    @foreach ($modelLineMasters as $modelLineMaster)
                        @php
                            $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                            $combinedValue = $brand ? $brand->brand_name . ' / ' . $modelLineMaster->model_line : 'Unknown Brand / ' . $modelLineMaster->model_line;
                        @endphp
                        select.append('<option value="{{ $modelLineMaster->id }}">{{ $combinedValue }}</option>');
                    @endforeach

                col1.append(select);
                select.select2({
                    placeholder: "Select an option",
                    maximumSelectionLength: 1
                });
                col1.append(datalist);
                var col2 = $('<div class="col-lg-4 col-md-6 align-self-end"></div>');
                var removeBtn = $('<a href="#" class="remove-row-btn btn btn-danger"><i class="fas fa-minus"></i> Remove</a>');
                col2.append(removeBtn);
                newRow.append(col1);
                newRow.append(col2);
                $(wrapper).append(newRow);
                updateDropdownList();
            }
        });

        $(wrapper).on("click", ".remove-row-btn", function(e) {
            e.preventDefault();
            $(this).closest('.row').remove();
            x--;
            updateDropdownList();
        });

        $(wrapper).on("input", "input[name='model_line_id[]']", function() {
            var selectedBrandInput = $(this);
            var selectedBrandIdInput = selectedBrandInput.next('input[name="model_line_ids[]"]');
            var selectedOption = selectedBrandInput.val();
            var selectedOptionId = selectedBrandInput.siblings('datalist').find('option[value="' + selectedOption + '"]').data('value');
            selectedBrandIdInput.val(selectedOptionId);
            updateDropdownList();
        });
    });
    $(document).ready(function() {
        $('#phone, #email').on('input', function() {
            var phone = $('#phone').val();
            var email = $('#email').val();
            $.ajax({
                url: "{{ route('checkExistence') }}",
                method: 'POST',
                data: {
                    phone: phone,
                    email: email,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#phoneCount').text('Phone count: ' + response.phoneCount);
                    $('#emailCount').text('Email count: ' + response.emailCount);
                    if (response.phoneCount > 0 || response.emailCount > 0) {
                        // var customerNames = response.customerNames.join(', ');
                        // var message = 'Customer Names: ' + customerNames + '<br>';
                        message = 'Phone Count: ' + response.phoneCount + '<br>';
                        message += 'Email Count: ' + response.emailCount;
                        var buttonHtml = '<a href="{{ route('repeatedcustomers') }}?phone=' + encodeURIComponent(phone) + '&email=' + email + '" class="btn btn-primary">See Details</a>';
                        message += '<br>' + buttonHtml;
                        
                        $('#flashMessage').html('<div class="alert alert-info">' + message + '</div>');
                    } else {
                        $('#flashMessage').html('');
                    }
                }
            });
        });
    });
    const languageInput = document.getElementById('languageInput');
    if (languageInput) {
        languageInput.addEventListener('input', function(event) {
            var input = event.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var inputValue = input.value;
            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                if (option.value === inputValue) {
                    input.setCustomValidity('');
                    return;
                }
            }
            input.setCustomValidity('Please select a valid Language from the list.');
        });
    }

    const locationInput = document.getElementById('locationInput');
    if (locationInput) {
        locationInput.addEventListener('input', function(event) {
            var input = event.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var inputValue = input.value;
            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                if (option.value === inputValue) {
                    input.setCustomValidity('');
                    return;
                }
            }
            input.setCustomValidity('Please select a valid Location from the list.');
        });
    }

    const milelemotorsInput = document.getElementById('milelemotorsInput');
    if (milelemotorsInput) {
        milelemotorsInput.addEventListener('input', function(event) {
            var input = event.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var inputValue = input.value;
            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                if (option.value === inputValue) {
                    input.setCustomValidity('');
                    return;
                }
            }
            input.setCustomValidity('Please select a valid Source from the list.');
        });
    }

    const typeInput = document.getElementById('typeInput');
    if (typeInput) {
        typeInput.addEventListener('input', function(event) {
            var input = event.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var inputValue = input.value;
            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                if (option.value === inputValue) {
                    input.setCustomValidity('');
                    return;
                }
            }
            input.setCustomValidity('Please select a valid Type from the list.');
        });
    }


    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', function(event) {
            var emailValue = event.target.value;
            var emailError = document.getElementById('emailError');

            if (emailValue === '') {
                emailInput.classList.remove('invalid');
                if (emailError) emailError.textContent = '';
            } else if (!validateEmail(emailValue)) {
                emailInput.classList.add('invalid');
                if (emailError) emailError.textContent = 'Please enter a valid email address.';
            } else {
                emailInput.classList.remove('invalid');
                if (emailError) emailError.textContent = '';
            }
        });
    }

    // Reuse your email validation function safely
    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    const salesPersonInput = document.getElementById('salesPersonInput');
    if (salesPersonInput) {
        salesPersonInput.addEventListener('input', function(event) {
            var input = event.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var inputValue = input.value;
            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                if (option.value === inputValue) {
                    input.setCustomValidity('');
                    return;
                }
            }
            input.setCustomValidity('Please select a valid Sales Person from the list.');
        });

        const selectedSalesPersonIdInput = document.getElementById('selectedSalesPersonId');
        salesPersonInput.addEventListener('input', function() {
            const selectedOption = Array.from(document.querySelectorAll('#salesList option')).find(option => option.value === salesPersonInput.value);
            if (selectedOption && selectedSalesPersonIdInput) {
                selectedSalesPersonIdInput.value = selectedOption.getAttribute('data-id');
            } else if (selectedSalesPersonIdInput) {
                selectedSalesPersonIdInput.value = '';
            }
        });
    }

    const brandInput = document.getElementById('brandInput');
    const selectedBrandIdInput = document.getElementById('selectedBrandId');

    if (brandInput) {
        brandInput.addEventListener('input', function(event) {
            var input = event.target;
            var list = input.getAttribute('list');
            var options = document.querySelectorAll('#' + list + ' option');
            var inputValue = input.value;
            let valid = false;

            for (var i = 0; i < options.length; i++) {
                if (options[i].value === inputValue) {
                    valid = true;
                    break;
                }
            }

            input.setCustomValidity(valid || inputValue === '' ? '' : 'Please select a valid Brand & Model from the list.');

            // Handle hidden field update safely
            if (selectedBrandIdInput) {
                const selectedOption = Array.from(options).find(option => option.value === inputValue);
                selectedBrandIdInput.value = selectedOption ? selectedOption.getAttribute('data-value') : '';
            }
        });
    }
</script>

<script>
    $(document).ready(function () {
        $('#calls').on('submit', function (e) {
            const SEP = '###SEP###';
            let lines = [];

            const model = $('#car_model').val();
            const purpose = $('#purchase_purpose').val();
            const endUser = $('#end_user').val();
            const destinationCountries = $('#destination_country').val() || [];
            const plannedUnits = $('#planned_units').val();
            const experience = $('#source_experience').val();
            const shipping = $('#shipping_required').val();
            const paymentMethod = $('#payment_method').val();
            const prevPurchaseHistory = $('#prev_purchase_history').val();
            const purchaseTimeline = $('#purchase_timeline').val();
            const general = $('#general_remark').val();

            if (model || purpose || endUser || destinationCountries.length || plannedUnits || experience || shipping || paymentMethod || prevPurchaseHistory || purchaseTimeline) {
                lines.push('Lead Summary - Qualification Notes:');
                if (model) lines.push(`1. Car Interested In: ${model}`);
                if (purpose) lines.push(`2. Purpose of Purchase: ${purpose}`);
                if (endUser) lines.push(`3. End User: ${endUser}`);
                if (destinationCountries.length) lines.push(`4. Destination Country: ${destinationCountries.join(', ')}`);
                if (plannedUnits) lines.push(`5. Planned Units: ${plannedUnits}`);
                if (experience) lines.push(`6. Experience with UAE Sourcing: ${experience}`);
                if (shipping) lines.push(`7. Shipping Assistance Required: ${shipping}`);
                if (paymentMethod) lines.push(`8. Payment Method: ${paymentMethod}`);
                if (prevPurchaseHistory) lines.push(`9. Previous Purchase History: ${prevPurchaseHistory}`);
                if (purchaseTimeline) lines.push(`10. Purchase Timeline: ${purchaseTimeline}`);
            }

            if (general && general.trim() !== '') {
                lines.push(`General Remark / Additional Notes: ${general}`);
            }

            const compiled = lines.join(SEP);
            $('#compiled_remarks').val(compiled);
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            preferredCountries: ["ae"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        input.addEventListener('input', function() {
            let value = input.value;

            value = value.replace(/[^\d+]/g, '');

            if (value.indexOf('+') > 0) {
                value = value.replace(/\+/g, '');
            }
            if (value.indexOf('+') !== 0) {
                value = value.replace(/\+/g, '');
            }

            input.value = value;
        });

        $("#calls").on("submit", function() {
            var fullNumber = iti.getNumber();

            $("<input>").attr({
                type: "hidden",
                name: "phone",
                value: fullNumber
            }).appendTo("#calls");

        });
    });
</script>

<script>
    $(document).ready(function() {
        // Format CSR price input with separators
        $('#csr_price').on('input', function() {
            let value = $(this).val().replace(/[^0-9.]/g, '');
            if (value) {
                let parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                $(this).val(parts.join('.'));
            }
        });

        // Clean the value before form submission
        $('#calls').on('submit', function() {
            let csrPriceValue = $('#csr_price').val().replace(/,/g, '');
            $('#csr_price').val(csrPriceValue);
        });
    });
</script>


@endpush