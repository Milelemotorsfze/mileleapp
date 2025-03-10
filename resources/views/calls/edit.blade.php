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
.iti__flag {
    background-size: 20px 15px !important; /* Adjust these dimensions based on the actual size of the flag icons in the sprite */
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
			<form id="updateForm" action="{{ route('calls.updatehol') }}" method="post" enctype="multipart/form-data">
                <div class="row"> 
					<div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                        <input type="text" name="name" class="form-control" value="{{ $calls->name }}" autocomplete="off">
                    </div>
                    <div class="col-lg-4 col-md-6">
    <span class="error">* </span>
    <label for="basicpill-firstname-input" class="form-label">Customer Phone:</label>
    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" value="{{ $calls->phone }}" autocomplete="off" autofocus>
    <div class="invalid-feedback">Please enter a valid phone number.</div>
</div>
                    <div class="col-lg-4 col-md-6">
    <span class="error">*</span>
    <label for="basicpill-firstname-input" class="form-label">Customer Email:</label>
    <input type="text" name="email" class="form-control" value="{{ $calls->email }}" id="email">
    <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}" autocomplete="off">
    <input type="hidden" name="call_id" value="{{ $calls->id }}" id="call_id">
    <div id="emailError" class="error-text"></div>
</div>
@php
                    $leadsources = "";
                     $leadsource = DB::table('lead_source')->where('id', $calls->source)->first();
                     $leadsources = $leadsource->source_name;
                     @endphp
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Source:</label>
                    <input type="text" placeholder="Source" name="milelemotors" list="milelemotorsList" class="form-control" id="milelemotorsInput" value="{{ $leadsources }}">
                    <datalist id="milelemotorsList">
                    @foreach ($LeadSource as $source)
                    <option value="{{ $source->source_name }}">{{ $source->source_name }}</option>
                    @endforeach
                    </datalist>
                    </div>
                    <div class="col-lg-4 col-md-6">
    <span class="error">*</span>
    <label for="basicpill-firstname-input" class="form-label">Preferred Language:</label>
    <input type="text" placeholder="Language" name="language" list="laList" class="form-control" id="languageInput" value="{{ $calls->language}}">
    <datalist id="laList">
    @foreach ($Language as $language)
        <option value="{{ $language->name }}" data-value="{{ $language->name }}">{{ $language->name }}</option>
    @endforeach
    </datalist>
</div>
                    <div class="col-xs-4 col-sm-12 col-md-4">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Destination : </label>
                    <input type="text" placeholder="Location" name="location" list="loList" class="form-control" id="locationInput" value="{{ $calls->location}}">
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
                    <input type="text" placeholder="Type" name="type" list="typeList" class="form-control" id="typeInput" value="{{ $calls->type}}">
                    <datalist id="typeList">
                    <option value="Export" data-value="Export">Export</option>
                    <option value="Local" data-value="Export">Local</option>
                    <option value="Other" data-value="Export">Other</option>
                    </datalist>
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
                    <input type="radio" name="sales-option" id="auto-assign-option" value="auto-assign" checked> {{ $sales_persons_namess }}
                    </label>
                    <input type="hidden" name="old_sales_person_id" value="{{ $calls->sales_person }}">
                    <label>
                    <input type="radio" name="sales-option" id="manual-assign-option" value="manual-assign"> Manual Assign
                    </label>
                    </div>
                    </div>
                    <div class="col-lg-4 col-md-6" id="manual-sales-person-list" style="display: none;">
                    <label for="manual-sales-person" class="form-label">Sales Person:</label>
                    <input type="text" placeholder="Sales Persons" name="sales_person" list="salesList" class="form-control" id="salesPersonInput">
                    <datalist id="salesList">
                     @foreach ($sales_persons as $sales_person)
                     @php
                     $sales_person_details = DB::table('users')->where('id', $sales_person->model_id)->first();
                     $sales_person_name = $sales_person_details->name;
                     @endphp
                     <option value="{{ $sales_person_name }}" data-id="{{ $sales_person->model_id }}"></option>      
                     @endforeach
                    </datalist>
                    <input type="hidden" name="sales_person_id" id="selectedSalesPersonId">
                    </div>
                    </div>
<div class="maindd">
    <div id="row-container">
    <label for="brandInput" class="form-label">Brand & Models:</label>
@php
$model_line_idss = DB::table('calls_requirement')->where('lead_id', $calls->id)->get();
@endphp
@foreach ($model_line_idss as $model_line_idssss)
@php
$model_name = DB::table('master_model_lines')->where('id', $model_line_idssss->model_line_id)->first();
$model_names = $model_name->model_line;
$brand_ids = $model_name->brand_id;
$brand = DB::table('brands')->where('id', $brand_ids)->first();
$brand_name = $brand->brand_name;
@endphp 
<div class="row">
    <div class="col-lg-4 col-md-6">
        <input type="text" placeholder="Select Brand & Model" name="model_line_id[]" list="brandList" class="form-control mb-1" id="brandInputs" value="{{ $brand_name }} / {{ $model_names }}">
        <datalist id="brandList">
        @foreach ($modelLineMasters as $modelLineMaster)
            @php
            $brand = DB::table('brands')->where('id', $modelLineMaster->brand_id)->first();
            $brand_name = $brand->brand_name;
            @endphp 
            <option value="{{ $brand_name }} / {{ $modelLineMaster->model_line }}" data-value="{{ $modelLineMaster->id }}">{{ $brand_name }} / {{ $modelLineMaster->model_line }}</option>
        @endforeach
        <input type="hidden" id="callRequirementId" value="{{ $model_line_idssss->id }}" />
        </datalist>
        <input type="hidden" name="model_line_ids[]" id="selectedBrandId" value="">
    </div>
    <div class="col-lg-4 col-md-6">
    <a href="#" class="remove-row-btn btn btn-danger" data-call-requirement-id="{{ $model_line_idssss->id }}">
        <i class="fas fa-minus"></i> Remove
    </a>
</div>
</div>
@endforeach
<div id="row-container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
<input type="hidden" placeholder="Select Brand & Model" name="model_line_id[]" list="brandList" class="form-control mb-1" id="brandInput">
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
</div>
<div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-start">
        <div class="btn btn-primary add-row-btn">
            <i class="fas fa-plus"></i> Add More
        </div>
    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Custom Brand & Model : </label>
                        <input type="text" placeholder="Custom Brand Model" name="custom_brand_model" class="form-control" value="{{ $calls->custom_brand_model}}">
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label for="basicpill-firstname-input" class="form-label">Remarks : </label>
                        <textarea name="remarks" id="editor">{{ $calls->remarks}}</textarea>
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


    // var contact_number = window.intlTelInput(document.querySelector("#phone"),
    //     {
    //         separateDialCode: true,
    //         preferredCountries:["ae"],
    //         hiddenInput: "full",
    //         utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
    //     });


//         $(document).ready(function() {
//     var input = document.querySelector("#phone");

//     if (input) {
//         var contact_number = window.intlTelInput(input, {
//             separateDialCode: true,
//             preferredCountries: ["ae"], 
//             hiddenInput: "full",
//             utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
//         });

//         console.log("Intl-Tel-Input initialized successfully");

//         // Ensure flag appears even if there is a default value
//         setTimeout(function() {
//             if (input.value.trim() !== "") {
//                 contact_number.setNumber(input.value);
//             }
//         }, 500);
//     } else {
//         console.error("Phone input field not found!");
//     }
// });


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

document.addEventListener("DOMContentLoaded", function () {
    var input = document.querySelector("#phone");

    if (!input) {
        console.error("Phone input field not found!");
        return;
    }

    let savedPhone = @json($calls->phone); // Get phone from backend
    console.log("Phone from Backend:", savedPhone);

    // Initialize intlTelInput
    var iti = window.intlTelInput(input, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js",
        separateDialCode: true,
        nationalMode: false,
        autoPlaceholder: "aggressive"
    });

    // Ensure correct country is set based on stored number
    setTimeout(() => {
        let countryData = iti.getSelectedCountryData();
        let currentDialCode = "+" + countryData.dialCode;

        console.log("Initial Dial Code:", currentDialCode);

        if (savedPhone.startsWith("+")) {
            iti.setNumber(savedPhone);
        } else {
            iti.setNumber(currentDialCode + savedPhone);
        }
        input.value = iti.getNumber(intlTelInputUtils.numberFormat.E164);
    }, 500);

    // Prevent country code from changing when deleting numbers
    // input.addEventListener("input", function () {
    //     let currentNumber = iti.getNumber();
    //     let countryData = iti.getSelectedCountryData();
    //     let countryCode = "+" + countryData.dialCode;
    //     let rawNumber = currentNumber.replace(countryCode, "").trim();

    //     console.log("Updated Country Code:", countryCode);
    //     console.log("Raw Number after Edit:", rawNumber);

    //     if (!rawNumber.startsWith(countryCode)) {
    //         input.value = rawNumber;
    //     }
    // });
    input.addEventListener("input", function () {
        let fullPhoneNumber = iti.getNumber(intlTelInputUtils.numberFormat.E164);
        input.value = fullPhoneNumber;
    });

    // Prevent deletion of country code
    input.addEventListener("keydown", function (event) {
        let cursorPos = input.selectionStart;
        let countryData = iti.getSelectedCountryData();
        let countryCode = "+" + countryData.dialCode;

        if (event.key === "Backspace" || event.key === "Delete") {
            if (cursorPos <= countryCode.length) {
                event.preventDefault();
            }
        }
    });

    // Prevent pasting without country code
    input.addEventListener("paste", function (event) {
        event.preventDefault();
        let clipboardData = event.clipboardData.getData("text");
        let countryData = iti.getSelectedCountryData();
        let countryCode = "+" + countryData.dialCode;

        if (clipboardData.startsWith("+")) {
            input.value = clipboardData;
        } else {
            input.value = countryCode + clipboardData.replace(/\D/g, "");
        }
    });

    // Ensure correct formatting when country changes
    iti.events.on("countrychange", function () {
        let selectedCountry = iti.getSelectedCountryData();
        let newDialCode = "+" + selectedCountry.dialCode;
        let newRawNumber = input.value.replace(newDialCode, "").trim();

        console.log("New Dial Code on Country Change:", newDialCode);
        input.value = newRawNumber;
    });

    $('#updateForm').on('submit', function (e) {
        e.preventDefault();

        if (!iti.isValidNumber()) {
            $('#phone').siblings('.invalid-feedback').show();
            return false;
        } else {
            $('#phone').siblings('.invalid-feedback').hide();
        }

        var formData = $(this).serialize();
        console.log("formData is: --- ", formData )
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Form submitted successfully');
            },
            error: function (xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
    });
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
          _token: csrfToken // Include the CSRF token in the request data
        },
        success: function(response) {
          // Handle the response if needed
          console.log(response);
        },
        error: function(xhr, status, error) {
          // Handle the error if needed
          console.log(error);
        }
      });
    });
  });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"></script>
@endpush