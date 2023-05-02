@extends('layouts.main')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create Suppliers</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
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
        <form method="POST" enctype="multipart/form-data" action="{{ route('suppliers.store') }}"> 
            @csrf
            <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Supplier') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Contact Person" value="{{ old('supplier') }}" required autocomplete="supplier" autofocus>
                            @error('supplier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="contact_person" class="col-form-label text-md-end">{{ __('Contact Person') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="contact_person" type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" placeholder="Enter Contact Person" value="{{ old('contact_person') }}" required autocomplete="contact_person" autofocus>
                            @error('contact_person')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="contact_number" type="tel" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number[main]" placeholder="Enter Contact Number" value="{{ old('contact_number') }}" required autocomplete="contact_number" autofocus>
                            @error('contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="alternative_contact_number" type="text" class="form-control @error('alternative_contact_number') is-invalid @enderror" name="alternative_contact_number[main]" placeholder="Enter Alternative Contact Number" value="{{ old('alternative_contact_number') }}" autocomplete="alternative_contact_number" autofocus>
                            @error('alternative_contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="person_contact_by" class="col-form-label text-md-end">{{ __('Person Contact By') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="person_contact_by" type="text" class="form-control @error('person_contact_by') is-invalid @enderror" name="person_contact_by" placeholder="Enter Person Contact By" value="{{ old('person_contact_by') }}" required autocomplete="person_contact_by" autofocus>
                            @error('person_contact_by')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="supplier_type" class="col-form-label text-md-end">{{ __('Supplier Type') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <select name="supplier_type" id="supplier_type" class="form-control">
                            <option value="">Choose Supplier Type</option>
                            <option value="spare_parts">Spare Parts</option>
                        </select>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="is_primary_payment_method" class="col-form-label text-md-end">{{ __('Primary Payment Method') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <select id="is_primary_payment_method" name="is_primary_payment_method" class="form-control">
                            <option value="">Choose Payment Method</option>
                            @foreach($paymentMethods as $paymentMethod)
                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->payment_methods}}</option>
                            @endforeach
                        </select>
                            <!-- <input id="is_primary_payment_method" type="text" class="form-control @error('is_primary_payment_method') is-invalid @enderror" name="is_primary_payment_method" placeholder="Enter Supplier Type" value="{{ old('is_primary_payment_method') }}" required autocomplete="is_primary_payment_method" autofocus> -->
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <span class="error">* </span>
                            <label for="is_primary_payment_method" class="col-form-label text-md-end">{{ __('Addons') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <select id="adoon" name="addon_id[]" multiple="true" style="width: 100%;">
                                @foreach($addons as $addon)
                                    <option value="{{$addon->id}}">{{$addon->addon_code}}</option>
                                @endforeach
                            </select>
                            @error('is_primary_payment_method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-6 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-2 col-md-4">
                            <span class="error">* </span>
                            <label for="payment_methods_id" class="col-form-label text-md-end">{{ __('Payment Methods') }}</label>
                        </div>
                        @foreach($paymentMethods as $paymentMethod)
                            <div class="col-xxl-2 col-lg-2 col-md-6">
                                <input name="payment_methods_id[]" class="form-check-input" type="checkbox" value="{{ $paymentMethod->id }}" id="flexCheckIndeterminate">                              
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    {{ $paymentMethod->payment_methods }}
                                </label>
                                @error('payment_methods_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                    </br>
                </div>
                <div class="col-xxl-12 col-lg-12 col-md-12">
                    <button style="float:right;" type="submit" class="btn btn-sm btn-success" id="submit">Submit</button>
                </div>
            </div>
        </form> 
    </div>  
    <script type="text/javascript">
        $(document).ready(function ()
        {
            $("#adoon").attr("data-placeholder","Choose Addon Code....     Or     Type Here To Search....");
            $("#adoon").select2();
        });
        var contact_number = window.intlTelInput(document.querySelector("#contact_number"), {
        separateDialCode: true,
        preferredCountries:["ae"],
        hiddenInput: "full",
        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
        var alternative_contact_number = window.intlTelInput(document.querySelector("#alternative_contact_number"), {
        separateDialCode: true,
        preferredCountries:["ae"],
        hiddenInput: "full",
        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
        $("form").submit(function() {
        var full_number = contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
        $("input[name='contact_number[full]'").val(full_number);
        var full_alternative_contact_number = alternative_contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
        $("input[name='alternative_contact_number[full]'").val(full_number);
        });
    </script>
@endsection
<style>
    .error 
    {
        color: #FF0000;
    }
    .iti 
    { 
        width: 100%; 
    }
</style>