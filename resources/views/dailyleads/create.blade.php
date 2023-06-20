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
@if (Auth::user()->selectedRole === '7' || Auth::user()->selectedRole === '8')
@can('daily-leads-create')
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
        {!! Form::open(array('route' => 'dailyleads.store','method'=>'POST', 'id' => 'calls')) !!}
            <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Customer Phone:</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number">
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">*</span>
                    <label for="basicpill-firstname-input" class="form-label">Customer Email:</label>
                    {!! Form::email('email', null, array('id' => 'email', 'placeholder' => 'Email','class' => 'form-control')) !!}
                    <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
                    <div id="emailError" class="error-text"></div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Source:</label>
                    <input type="text" placeholder="Source" name="milelemotors" list="milelemotorsList" class="form-control" id="milelemotorsInput">
                    <datalist id="milelemotorsList">
                    <option value="Walking Customer" data-value="Walking Customer">Walking Customer</option>
                    <option value="Online" data-value="Online">Online</option>
                    <option value="Direct Reference" data-value="Direct Reference">Direct Reference</option>
                    <option value="Direct Calls" data-value="Direct Calls">Direct Calls</option>
                    <option value="Agent" data-value="Agent">Agent</option>
                    </datalist>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">*</span>
                    <label for="basicpill-firstname-input" class="form-label">Preferred Language:</label>
                    <input type="text" placeholder="Language" name="language" list="laList" class="form-control" id="languageInput">
                    <datalist id="laList">
                        <option value="English" data-value="English">English</option>
                        <option value="Arabic" data-value="English">Arabic</option>
                        <option value="Russian" data-value="English">Russian</option>
                        <option value="Urdu" data-value="English">Urdu</option>
                        <option value="Hindi" data-value="English">Hindi</option>
                        <option value="Kannada" data-value="English">Kannada</option>
                        <option value="French" data-value="English">French</option>
                        <option value="Malayalam" data-value="English">Malayalam</option>
                        <option value="Tamil" data-value="English">Tamil</option>
                        <option value="Spanish" data-value="English">Spanish</option>
                        <option value="Portuguese" data-value="English">Portuguese</option>
                        <option value="Shona" data-value="English">Shona</option>
                    </datalist>
                </div>
                    <div class="col-xs-4 col-sm-12 col-md-4">
                    <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Destination : </label>
                        <input type="text" placeholder="Location" name="location" list="loList" class="form-control" id="locationInput">
                    <datalist id="loList">
                    @foreach ($countries as $country)
                    <option value="{{ $country }}" data-value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                    </datalist>
                        </div>
                        <div class="col-lg-4 col-md-6">
                        <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Type : </label>
                        <input type="text" placeholder="Type" name="type" list="typeList" class="form-control" id="typeInput">
                    <datalist id="typeList">
                    <option value="Export" data-value="Export">Export</option>
                    <option value="Local" data-value="Export">Local</option>
                    <option value="Other" data-value="Export">Other</option>
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
                <input type="hidden" name="model_line_ids[]" id="selectedBrandId">
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
    @endcan
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
    <script type="text/javascript">
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
            var label = $('<label for="brandInput' + x + '" class="form-label">Brand & Models:</label>');
            var input = $('<input type="text" placeholder="Select Brand & Model" name="model_line_id[]" class="form-control mb-1 new-select" id="brandInput' + x + '" list="brandList' + x + '" autocomplete="off" /><input type="hidden" name="model_line_ids[]" id="selectedBrandId' + x + '">');
            col1.append(label);
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

        if (!validateEmail(emailValue)) {
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
    window.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js",
        initialCountry: "ae",
        separateDialCode: false,
        nationalMode: true
    });
    input.addEventListener('input', function() {
        var currentValue = input.value;
        var newValue = currentValue.replace(/[^0-9]/g, '');
        if (newValue.charAt(0) !== '+') {
            newValue = '+' + newValue;
        }
        if (newValue.length > 15) {
            newValue = newValue.slice(0, 15); // Truncate to 15 digits
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"></script>
@endpush