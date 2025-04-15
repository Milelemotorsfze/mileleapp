@extends('layouts.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
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
.error 
    {
        color: #FF0000;
    }
    .iti 
    { 
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
.error-text{
    color: #FF0000;
}
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('sales-view');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">New Leads</h4>
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
        {!! Form::open(['route' => 'dailyleads.store', 'method' => 'POST', 'id' => 'calls', 'files' => true]) !!}
            <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
                <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Lead Type : </label>
                    <select class="form-control" id="leadtype" name="leadtype" required>
                    <option value="Normal Deals" data-value="Normal Deals">Normal Deals</option>
                    <option value="Bulk Deals" data-value="Bulk Deals">Bulk Deals</option>
                    <option value="Special Orders" data-value="Special Orders">Special Orders</option>
                    </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Customer : </label>
                    <select id="client_id" name="client_id" class="form-control">
                    <option value="" disabled selected>Select an Customer</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->client->id }}">{{ $client->client->name }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Selling Type : </label>
                    <select class="form-control" id="shippingtype" name="type" required>
                    <option value="" disabled selected>Select an Selling Type</option>
                    <option value="Export" data-value="Export">Export</option>
                    <option value="Local" data-value="Local">Local</option>
                    <option value="Other" data-value="Other">Other</option>
                    </select>
                    </div>
                    <div class="col-lg-4 col-md-6" id="countryexp" style="display: none;">
                    <label for="basicpill-firstname-input" class="form-label">Country of Export : </label>
                    <input type="text" placeholder="Country of Export" name="countryofexport" list="coofexport" class="form-control" id="countryofexport">
                    <datalist id="coofexport">
                    @foreach ($countries as $country)
                    <option value="{{ $country }}" data-value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                    </datalist>
                    </div>
                    </div>
                    
                    </br>
                    <div class="maindd">
                        <div id="row-container">
                            <div class="row">
                                <div class="col-lg-4 col-md-6">
                                    <label for="brandInput" class="form-label">Brand & Models:</label>
                                    <input type="text" placeholder="Select Brand & Model" name="model_line_id[]" list="brandList" class="form-control mb-1" id="brandInput">
                                    <datalist id="brandList">
                                        @foreach ($modelLineMasters as $modelLineMaster)
                                            @php
                                                $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
                                                $brand_name = $brand->brand_name;
                                            @endphp 
                                            <option value="{{ $brand_name }} / {{ $modelLineMaster->model_line }}" data-value="{{ $modelLineMaster->id }}">{{ $brand_name }} / {{ $modelLineMaster->model_line }}</option>
                                        @endforeach
                                    </datalist>
                                    <input type="hidden" name="model_line_ids" id="selectedBrandIds">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-start">
                            <div class="btn btn-primary add-row-btn">
                                <i class="fas fa-plus"></i> Add More
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Custom Brand & Model : </label>
                        {!! Form::text('custom_brand_model', null, array('placeholder' => 'Custom Brand & Model','class' => 'form-control')) !!}
                    </div>
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
                    @endphp
                    @if ($hasPermission)
                    <div class="col-lg-4 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Assign To: </label>
                    <select id="assignto" name="assignto" class="form-control">
                    <option value="" disabled selected>Select a Sales Person</option>
                        @foreach ($sales_persons as $sales_person)
                            <option value="{{ $sales_person->id }}">{{ $sales_person->name }}</option>
                        @endforeach
                    </select>
                    </div>
                    @endif
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
                        <textarea name="remarks" id="editor"></textarea>
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
<script>
    $(document).ready(function() {
        function toggleFields() {
            var shippingtype = document.getElementById("shippingtype");
            var countryexp = document.getElementById("countryexp");
            if (shippingtype && countryexp) {
                if (shippingtype.value === "Export") {
                    countryexp.style.display = "block";
                } else {
                    countryexp.style.display = "none";
                }
            }
        }
        toggleFields();
        var shippingTypeElement = document.getElementById("shippingtype");
        if (shippingTypeElement) {
            shippingTypeElement.addEventListener("change", toggleFields);
        }
    });
</script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('select[name="client_id"]').select2();
        });
        $(document).ready(function() {
    var max_fields = 10;
    var wrapper = $("#row-container");
    var add_button = $(".add-row-btn");
    var x = 1;

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
            var input = $('<input type="text" placeholder="Select Brand & Model" name="model_line_id[]" class="form-control mb-1 new-select" id="brandInput' + x + '" list="brandList' + x + '" autocomplete="off" /><input type="hidden" name="model_line_ids" class="selectedBrandId">');
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
        var selectedOption = selectedBrandInput.val();
        var selectedOptionId = selectedBrandInput.siblings('datalist').find('option[value="' + selectedOption + '"]').data('value');
        var selectedBrandIds = $('input[name="model_line_ids"]', selectedBrandInput.closest('.row')).val() || '[]';
        selectedBrandIds = JSON.parse(selectedBrandIds);
        selectedBrandIds = selectedBrandIds.filter(function(id) {
            return id !== null;
        });
        if (selectedBrandIds.indexOf(selectedOptionId) === -1) {
            selectedBrandIds.push(selectedOptionId);
        }
        $('input[name="model_line_ids"]', selectedBrandInput.closest('.row')).val(JSON.stringify(selectedBrandIds));
        updateDropdownList();
    });
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

        if (!validateEmail(emailValue)) {
            emailInput.classList.add('invalid');
            emailError.textContent = 'Please enter a valid email address.';
        } else {
            emailInput.classList.remove('invalid');
            emailError.textContent = '';
        }
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
    window.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js",
        separateDialCode: false,
        autoFormat: false,
        nationalMode: false
    });
    input.addEventListener('input', function() {
        var currentValue = input.value;
        var newValue = currentValue.replace(/[^0-9]/g, '');
        if (newValue.charAt(0) !== '+') {
            newValue = '+' + newValue;
        }
        if (newValue.length > 15) {
            newValue = newValue.slice(0, 15);
        }
        input.value = newValue;
    });
    iti.events.on("countrychange", function() {
        var countryCode = iti.getSelectedCountryData().dialCode;
        if (input.value && input.value.charAt(0) === '+') {
            input.value = "+" + countryCode + input.value.substr(4);
        } else {
            input.value = "+" + countryCode;
        }
    });
});
</script>
<script>
$(document).ready(function() {
    function toggleCustomerField() {
        var leadType = $('#leadtype').val();
        if (leadType === 'Bulk Deals' || leadType === 'Special Orders') {
            $('#client_id').parent().hide();
        } else {
            $('#client_id').parent().show();
        }
    }
    toggleCustomerField();
    $('#leadtype').on('change', function() {
        toggleCustomerField();
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#assignto').select2({
            placeholder: "Select a Sales Person",
            allowClear: true
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"></script>
@endpush