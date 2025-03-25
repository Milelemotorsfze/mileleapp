@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"></script>

<style>
    .error-text {
        color: red;
    }

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
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">Edit Calls & Messages</h4>
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
    {!! Form::open(['route' => ['calls.updatehol'], 'method' => 'POST', 'id' => 'calls']) !!}
    <div class="row">
        <p><span style="float:right;" class="error">* Required Field</span></p>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $calls->name) }}" autocomplete="off">
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="error">* </span>
                <label for="basicpill-firstname-input" class="form-label">Customer Phone:</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="{{ old('phone', $calls->phone) }}" autocomplete="off">
            </div>
            <div class="col-lg-4 col-md-6">
                <span class="error">*</span>
                <label for="basicpill-firstname-input" class="form-label">Customer Email:</label>
                <input type="text" name="email" class="form-control" value="{{ old('email', $calls->email) }}" id="email">
                <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}" autocomplete="off">
                <input type="hidden" name="call_id" value="{{ $calls->id }}" id="call_id">
                <div id="emailError" class="error-text"></div>
            </div>
            @php
            $leadsources = "";
            $leadsource = DB::table('lead_source')->where('id', $calls->source)->first();
            $leadsources = $leadsource->source_name;
            @endphp
            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="milelemotorsSelect" class="form-label">Source:</label>
                <select name="milelemotors" class="form-control select2" id="milelemotorsSelect" multiple>
                    <option value="">Select Source</option>
                    @foreach ($LeadSource as $source)
                    <option value="{{ $source->source_name }}"
                        {{ old('milelemotors', $leadsources) == $source->source_name ? 'selected' : '' }}>
                        {{ $source->source_name }}
                    </option>
                    @endforeach
                </select>
            </div>


            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">*</span>
                <label for="languageSelect" class="form-label">Preferred Language:</label>
                <select name="language" class="form-control select2" id="languageSelect" multiple>
                    <option value="">Select Language</option>
                    @foreach ($Language as $lang)
                    <option value="{{ $lang->name }}"
                        {{ old('language', $calls->language) == $lang->name ? 'selected' : '' }}>
                        {{ $lang->name }}
                    </option>
                    @endforeach
                </select>
            </div>


            <div class="col-xs-4 col-sm-12 col-md-4 pt-3">
                <span class="error">* </span>
                <label for="locationSelect" class="form-label">Destination:</label>
                <select name="location" class="form-control select2" id="locationSelect" multiple>
                    <option value="">Select Destination</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country }}"
                        {{ old('location', $calls->location) == $country ? 'selected' : '' }}>
                        {{ $country }}
                    </option>
                    @endforeach
                    <option value="Not Mentioned"
                        {{ old('location', $calls->location) == 'Not Mentioned' ? 'selected' : '' }}>
                        Not Mentioned
                    </option>
                </select>
            </div>


            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="typeSelect" class="form-label">Type:</label>
                <select name="type" class="form-control select2" id="typeSelect" multiple>
                    <option value="">Select Type</option>
                    <option value="Export" {{ old('type', $calls->type) == 'Export' ? 'selected' : '' }}>Export</option>
                    <option value="Local" {{ old('type', $calls->type) == 'Local' ? 'selected' : '' }}>Local</option>
                    <option value="Other" {{ old('type', $calls->type) == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>


            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="strategySelect" class="form-label">Strategies:</label>
                <select name="strategy" class="form-control select2" id="strategySelect" multiple>
                    <option value="">Select Strategy</option>
                    @foreach ($strategy as $strategies)
                    <option value="{{ $strategies->name }}"
                        {{ old('strategy', $currentStrategyName ?? '') == $strategies->name ? 'selected' : '' }}>
                        {{ $strategies->name }}
                    </option>
                    @endforeach
                </select>
            </div>


            <div class="col-lg-4 col-md-6 pt-3">
                <span class="error">* </span>
                <label for="prioritySelect" class="form-label">Priority:</label>
                <select name="priority" id="prioritySelect" class="form-control select2" multiple>
                    <option value="">Select Priority</option>
                    <option value="normal" {{ old('priority', $calls->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ old('priority', $calls->priority) == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="hot" {{ old('priority', $calls->priority) == 'hot' ? 'selected' : '' }}>Hot</option>
                </select>
            </div>


        </div>
        </br>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="sales-options" class="form-label">Sales Persons Options:</label>
                <div>
                    <label>
                        @php
                        $sales_persons_namess = "";
                        $sales_personsss = DB::table('users')->where('id', $calls->sales_person)->first();
                        $sales_persons_namess = $sales_personsss->name;
                        @endphp
                        <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign" 
                            {{ old('sales-option', 'auto-assign') === 'auto-assign' ? 'checked' : '' }}> {{ $sales_persons_namess }}
                    </label>
                    <input type="hidden" name="old_sales_person_id" value="{{ $calls->sales_person }}">
                    <label>
                    <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign" 
                        {{ old('sales-option') === 'manual-assign' ? 'checked' : '' }}> Manual Assign
                    </label>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" id="manual-sales-person-list" style="display: none;">
                <span class="error">* </span>
                <label for="salesPersonSelect" class="form-label">Sales Person:</label>
                <select name="sales_person_id" id="salesPersonSelect" class="form-control select2" multiple>
                    <option value="">Select Sales Person</option>
                    @foreach ($sales_persons as $sales_person)
                    @php
                    $sales_person_details = DB::table('users')->where('id', $sales_person->model_id)->first();
                    $sales_person_name = $sales_person_details->name ?? '';
                    @endphp
                    <option value="{{ $sales_person->model_id }}" {{ old('sales_person_id') == $sales_person->model_id ? 'selected' : '' }}>
                        {{ $sales_person_name }}
                    </option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="maindd">
        <div id="row-container">
            @php
                $model_line_idss = DB::table('calls_requirement')->where('lead_id', $calls->id)->get();
            @endphp

            @foreach ($model_line_idss as $model_line_idssss)
                @php
                    $model_name = DB::table('master_model_lines')->where('id', $model_line_idssss->model_line_id)->first();
                    $brand = DB::table('brands')->where('id', $model_name->brand_id)->first();
                @endphp
                <div class="row mb-2">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Brand & Model:</label>
                        <select name="model_line_ids[]" class="form-control select2" multiple>
                            <option value="">Select Brand & Model</option>
                            @foreach ($modelLineMasters as $modelLineMaster)
                                @php
                                    $loopBrand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                                    $combinedName = $loopBrand->brand_name . ' / ' . $modelLineMaster->model_line;
                                    $isSelected = ($modelLineMaster->id == $model_line_idssss->model_line_id) ? 'selected' : '';
                                @endphp
                                <option value="{{ $modelLineMaster->id }}" {{ $isSelected }}>{{ $combinedName }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="existing_call_requirement_ids[]" value="{{ $model_line_idssss->id }}" />
                    </div>
                    <div class="col-lg-4 col-md-6 align-self-end">
                        <a href="#" class="remove-row-btn btn btn-danger" data-call-requirement-id="{{ $model_line_idssss->id }}">
                            <i class="fas fa-minus"></i> Remove
                        </a>
                    </div>
                </div>
            @endforeach

            {{-- Placeholder for adding new rows dynamically --}}
            <div class="row mb-2 new-row-template d-none">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Brand & Model:</label>
                    <select name="model_line_ids[]" class="form-control select2">
                        <option value="">Select Brand & Model</option>
                        @foreach ($modelLineMasters as $modelLineMaster)
                            @php
                                $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                            @endphp
                            <option value="{{ $modelLineMaster->id }}">{{ $brand->brand_name }} / {{ $modelLineMaster->model_line }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 align-self-end">
                    <a href="#" class="remove-row-btn btn btn-danger">
                        <i class="fas fa-minus"></i> Remove
                    </a>
                </div>
            </div>
        </div>

        {{-- Add More Button --}}
        <div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-start">
            <div class="btn btn-primary add-row-btn">
                <i class="fas fa-plus"></i> Add More
            </div>
        </div>

        {{-- Custom Brand & Remarks --}}
        <div class="col-lg-4 col-md-6 mt-3">
            <label for="basicpill-firstname-input" class="form-label">Custom Brand & Model : </label>
            <input type="text" placeholder="Custom Brand Model" name="custom_brand_model" class="form-control" value="{{ old('custom_brand_model', $calls->custom_brand_model) }}">
        </div>

        <div class="col-lg-12 col-md-12 mt-3">
            <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
            <textarea name="remarks" id="editor">{{ old('remarks', $calls->remarks) }}</textarea>
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
                    var input = $('<input type="text" placeholder="Select Brand & Model" name="model_line_id[]" class="form-control mb-1 new-select" id="brandInput' + x + '" list="brandList' + x + '" autocomplete="off" /><input type="hidden" name="model_line_ids[]" id="selectedBrandId' + x + '">');
                    col1.append(input);
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
            var call_id = $('#call_id').val();
            $.ajax({
                url: "{{ route('checkExistenceupdatecalls') }}",
                method: 'POST',
                data: {
                    phone: phone,
                    email: email,
                    call_id: call_id,
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
    document.getElementById('languageInput').addEventListener('input', function(event) {
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
    document.getElementById('locationInput').addEventListener('input', function(event) {
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
    document.getElementById('milelemotorsInput').addEventListener('input', function(event) {
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
    document.getElementById('typeInput').addEventListener('input', function(event) {
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
    document.getElementById('brandInput').addEventListener('input', function(event) {
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
        if (inputValue === '') {
            input.setCustomValidity('');
        } else {
            input.setCustomValidity('Please select a valid Brand & Model from the list.');
        }
    });
    document.getElementById('email').addEventListener('input', function(event) {
        var emailInput = event.target;
        var emailValue = emailInput.value;
        var emailError = document.getElementById('emailError');

        if (emailValue === '') {
            emailInput.classList.remove('invalid');
            emailError.textContent = '';
        } else if (!validateEmail(emailValue)) {
            emailInput.classList.add('invalid');
            emailError.textContent = 'Please enter a valid email address.';
        } else {
            emailInput.classList.remove('invalid');
            emailError.textContent = '';
        }
    });
    document.getElementById('salesPersonInput').addEventListener('input', function(event) {
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
    function validateEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    const salesPersonInput = document.getElementById('salesPersonInput');
    const selectedSalesPersonIdInput = document.getElementById('selectedSalesPersonId');
    salesPersonInput.addEventListener('input', function() {
        const selectedOption = Array.from(document.querySelectorAll('#salesList option')).find(option => option.value === salesPersonInput.value);
        if (selectedOption) {
            selectedSalesPersonIdInput.value = selectedOption.getAttribute('data-id');
        } else {
            selectedSalesPersonIdInput.value = '';
        }
    });
    const brandInput = document.getElementById('brandInput');
    const selectedBrandIdInput = document.getElementById('selectedBrandId');

    brandInput.addEventListener('input', function() {
        const selectedOption = Array.from(document.querySelectorAll('#brandList option')).find(option => option.value === brandInput.value);
        
        if (selectedOption) {
            selectedBrandIdInput.value = selectedOption.getAttribute('data-value');
        } else {
            selectedBrandIdInput.value = '';
        }
    });

   $(document).ready(function() {
    $('.remove-row-btn').click(function(e) {
        e.preventDefault();
        var callRequirementId = $(this).data('call-requirement-id');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
        url: '/calls/removerow',
        method: 'POST',
        data: {
            call_requirement_id: callRequirementId,
            _token: csrfToken
        },
        success: function(response) {
            if (response.success) {
            $(this).closest('.col-lg-4').remove();
            }
        }
        });
    });
    });
  $(document).ready(function() {
    $('#brandInputs').on('change', function() {
        var selectedOption = $(this).val();
        var modelLineMasterId = document.querySelector(`#brandList option[value="${selectedOption}"]`).getAttribute('data-value');
        var callRequirementId = $('#callRequirementId').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
      console.log(callRequirementId);
      $.ajax({
        url: '/calls/updaterow',
        type: 'POST',
        data: {
          modelLineMasterId: modelLineMasterId,
          callRequirementId: callRequirementId,
          _token: csrfToken
        },
        success: function(response) {
          console.log(response);
        },
        error: function(xhr, status, error) {
          console.log(error);
        }
      });
    });
  });
</script>

<script>
    $(document).ready(function () {
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            preferredCountries: ["ae"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        input.addEventListener('input', function () {
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

        $("#calls").on("submit", function () {
            var fullNumber = iti.getNumber();
            $("<input>").attr({
                type: "hidden",
                name: "phone",
                value: fullNumber
            }).appendTo("#calls");
        });

        $('.remove-row-btn').click(function(e) {
            e.preventDefault();
            $(this).closest('.row').remove();
        });
    });
</script>

@endpush