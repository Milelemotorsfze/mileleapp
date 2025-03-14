@extends('layouts.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
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
			<form action="" method="post" enctype="multipart/form-data">
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
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Source:</label>
                    <input type="text" placeholder="Source" name="milelemotors" list="milelemotorsList" class="form-control" id="milelemotorsInput" autocomplete="off" value="{{ old('milelemotors') }}">
                    <datalist id="milelemotorsList">
                    @foreach ($LeadSource as $source)
                    <option value="{{ $source->source_name }}">{{ $source->source_name }}</option>
                    @endforeach
                    </datalist>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">*</span>
                    <label for="basicpill-firstname-input" class="form-label">Preferred Language:</label>
                    <input type="text" placeholder="Language" name="language" list="laList" class="form-control" id="languageInput" autocomplete="off" value="{{ old('language') }}">
                    <datalist id="laList">
                    @foreach ($Language as $language)
                        <option value="{{ $language->name }}" data-value="{{ $language->name }}">{{ $language->name }}</option>
                    @endforeach
                    </datalist>
                </div>
                    <div class="col-xs-4 col-sm-12 col-md-4">
                    <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Destination : </label>
                        <input type="text" placeholder="Location" name="location" list="loList" class="form-control" id="locationInput" value="{{ old('location') }}">
                    <datalist id="loList">
                    @foreach ($countries as $country)
                    <option value="{{ $country }}" data-value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                    <option value="Not Mentioned" data-value="Not Mentioned">Not Mentioned</option>
                    </datalist>
                        </div>
                        <div class="col-lg-4 col-md-6">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Type : </label>
                        <input type="text" placeholder="Type" name="type" list="typeList" class="form-control" id="typeInput" value="{{ old('type') }}">
                    <datalist id="typeList">
                    <option value="Export" data-value="Export">Export</option>
                    <option value="Local" data-value="Export">Local</option>
                    <option value="Other" data-value="Export">Other</option>
                    </datalist>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Strategies:</label>
                        <select name="strategy" class="form-control" id="typeInput" >
                            @foreach ($strategy as $strategies)
                            <option value="{{ $strategies->name }}" {{ old('strategy') == $strategies->name ? 'selected' : '' }}>{{ $strategies->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Priority : </label>
                        <input type="text" placeholder="Type" name="priority" list="priorityList" class="form-control" id="priorityInput" value="{{ old('priority') }}" required>
                    <datalist id="priorityList">
                        <option value="Normal">Normal</option>
                        <option value="Low">Low</option>
                        <option value="Hot">Hot</option>
                    </datalist>
                    </div>
                    </div>
                    </br>
                    <div class="row">
                    <div class="col-lg-4 col-md-6">
    <label for="sales-options" class="form-label">Sales Persons Options:</label>
    <div>
        <label>
            <input type="radio" name="sales-option" id="auto-assign-option"  value="auto-assign" {{ old('sales-option', 'auto-assign') == 'auto-assign' ? 'checked' : '' }}> System Auto Assign
        </label>
        <label>
            <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign" {{ old('sales-option') == 'manual-assign' ? 'checked' : '' }}> Manual Assign
        </label>
    </div>
</div>
<div class="col-lg-4 col-md-6" id="manual-sales-person-list" style="display: none;">
    <label for="manual-sales-person" class="form-label">Sales Person:</label>
    <input type="text" placeholder="Sales Persons" name="sales_person" list="salesList" class="form-control" id="salesPersonInput" autocomplete="off" value="{{ old('sales_person') }}">
<datalist id="salesList">
    @foreach ($sales_persons as $sales_person)
        @php
            $sales_person_details = DB::table('users')->where('id', $sales_person->model_id)->first();
            $sales_person_name = $sales_person_details->name;
        @endphp
        <option value="{{ $sales_person_name }}" data-id="{{ $sales_person->model_id }}"></option>      
    @endforeach
</datalist>
<input type="hidden" name="sales_person_id" id="selectedSalesPersonId" value="{{ old('sales_person_id') }}">
                    </div>
                    </div>
                    <div class="maindd">
    <div id="row-container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
            <label for="brandInput" class="form-label">Brand & Models:</label>
<input type="text" placeholder="Select Brand & Model" name="model_line_id[]" list="brandList" class="form-control mb-1" id="brandInput" value="{{ old('model_line_id.0') }}">
<datalist id="brandList">
    @foreach ($modelLineMasters as $modelLineMaster)
        @php
            $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
            $brand_name = $brand->brand_name;
        @endphp 
        <option value="{{ $brand_name }} / {{ $modelLineMaster->model_line }}" data-value="{{ $modelLineMaster->id }}">{{ $brand_name }} / {{ $modelLineMaster->model_line }}</option>
    @endforeach
</datalist>
<input type="hidden" name="model_line_ids[]" id="selectedBrandId" value="{{ old('model_line_ids.0') }}">
            </div>
        </div>
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
                    <div class="col-lg-12 col-md-12">
                        <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
                        <textarea name="remarks" id="editor">{{ old('remarks') }}</textarea>
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

        if (!validateEmail(emailValue) && emailValue !== null) {
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
</script>

<script type="text/javascript">
    $(document).ready(function () {
        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            preferredCountries: ["ae"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        $("#calls").on("submit", function () {
            var fullNumber = iti.getNumber(); // Get full number in international format (+971509909090)
            
            // Set the formatted number in the phone input
            $("<input>").attr({
                type: "hidden",
                name: "phone",
                value: fullNumber
            }).appendTo("#calls");

            console.log("Submitting Phone Number:", fullNumber); // Debugging
        });
    });
</script>


@endpush