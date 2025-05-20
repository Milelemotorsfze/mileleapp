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
        <h4 class="card-title">New New Customer</h4>
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
        <form action="{{ route('salescustomers.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>  
			<form action="" method="post" enctype="multipart/form-data">
            <div class="row">
            <div class="col-lg-4 col-md-6">
            <span class="error">* </span>
            <label for="customertype">Customer Type:</label>
            <select class="form-control" id="customertype" name="customertype" required>
            <option value="" disabled selected>Select an Customer Type</option>
                <option value="End User">End User</option>
                <option value="International Trade">International Trade</option>
                <option value="Local Trade">Local Trade</option>
            </select>
            </div>
            </div>
                <div class="row"> 
					<div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                        <label for="basicpill-firstname-input" class="form-label">Customer Name : </label>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'required' => 'required')) !!}
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Customer Phone:</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Phone Number" required>
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Customer Email:</label>
                    {!! Form::email('email', null, array('id' => 'email', 'placeholder' => 'Email','class' => 'form-control')) !!}
                    <input type="hidden" name="user_id" placeholder="Email" class="form-control" value="{{ auth()->user()->id }}">
                    </div>
                    <div class="col-lg-4 col-md-6" id="companyNameField" style="display: none;">
                        <label for="basicpill-firstname-input" class="form-label">Company Name : </label>
                        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Company Name">
                    </div>
                    <div class="col-lg-4 col-md-6">
                    <span class="error">* </span>
                    <label for="basicpill-firstname-input" class="form-label">Source:</label>
    <select name="milelemotors" class="form-control" id="milelemotorsSelect" required>
        <option value="">-- Select Source --</option>
        @foreach ($LeadSource as $source)
            <option value="{{ $source->source_name }}">{{ $source->source_name }}</option>
        @endforeach
    </select>
</div>
<div class="col-lg-4 col-md-6">
    <span class="error">*</span>
    <label for="languageSelect" class="form-label">Preferred Language:</label>
    <select name="language" id="languageSelect" class="form-control select2" required>
        <option value="">Select Language</option>
        <option value="English">English</option>
        <option value="Arabic">Arabic</option>
        <option value="Russian">Russian</option>
        <option value="Urdu">Urdu</option>
        <option value="Hindi">Hindi</option>
        <option value="Kannada">Kannada</option>
        <option value="French">French</option>
        <option value="Malayalam">Malayalam</option>
        <option value="Tamil">Tamil</option>
        <option value="Spanish">Spanish</option>
        <option value="Portuguese">Portuguese</option>
        <option value="Shona">Shona</option>
    </select>
</div>

<div class="col-xs-4 col-sm-12 col-md-4">
    <span class="error">* </span>
    <label for="locationSelect" class="form-label">Destination / Nationality :</label>
    <select name="location" id="locationSelect" class="form-control select2" required>
        <option value="">Select Location</option>
        @foreach ($countries as $country)
            <option value="{{ $country }}">{{ $country }}</option>
        @endforeach
    </select>
</div>
                    <div class="col-lg-4 col-md-6" id="tradeLicenseField">
                    <label for="basicpill-firstname-input" class="form-label">Trade License : </label>
                    <input type="file" id="tradelicense" class="form-control" name="tradelicense">
                    </div>
                    <div class="col-lg-4 col-md-6" id="tenderLicenseField">
                    <label for="basicpill-firstname-input" class="form-label">Tender Copy : </label>
                    <input type="file" id="tender" class="form-control" name="tender">
                    </div>
                    <div class="col-lg-4 col-md-6" id="passportField">
                    <label for="basicpill-firstname-input" class="form-label">Passport : </label>
                    <input type="file" id="passport" class="form-control" name="passport">
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
});
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"></script>
@endpush