@extends('layouts.main')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Supplier Details</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
            <div class="row">
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Supplier') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            <input class="form-control" value="{{$supplier->supplier}}" readonly>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Contact Person') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            <input class="form-control" value="{{ $supplier->contact_person }}"readonly>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Person Contact By') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            <input class="form-control" value="{{ $supplier->person_contact_by }}" readonly>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Email') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                        <input class="form-control"  value="{{ $supplier->email }}" readonly>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            <input id="contact_number" type="tel" class="form-control" value="{{ $supplier->contact_number }}" readonly>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Alternative Contact') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            <input id="alternative_contact_number" type="tel" class="form-control" value="{{ $supplier->alternative_contact_number }}" readonly>
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label class="col-form-label text-md-end">{{ __('Supplier Type') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            @if($supplier->supplier_type == 'spare_parts')
                            <input class="form-control" value="Spare Parts" readonly>
                            @else
                            <input class="form-control" value="{{ $supplier->spare_parts }}" readonly>
                            @endif
                        </div>
                    </div>
                    </br>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-6 col-md-12">
                            <label for="is_primary_payment_method" class="col-form-label text-md-end">{{ __('Primary Payment Method') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            <input class="form-control" value="{{ $primaryPaymentMethod->PaymentMethods->payment_methods }}" readonly>
                        </div>
                    </div>
                    </br>
                </div> 
                <div class="col-xxl-4 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-2 col-md-4">
                            <label for="payment_methods_id" class="col-form-label text-md-end">{{ __('Secondary Payment Methods') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-6 col-md-12">
                            @if(count($otherPaymentMethods) > 0)
                            <p class="form-control" readonly>
                            @foreach($otherPaymentMethods as $otherPaymentMethod)
                                {{ $otherPaymentMethod->PaymentMethods->payment_methods }} , 
                            @endforeach
                            </p>
                            @else
                            <input class="form-control" value="" readonly>
                            @endif
                        </div>
                    </div>
                    </br>
                </div>
                @if($addons OR $addon1)
                <div class="card-header">
                    <h4 class="card-title" style="background-color:#e6e6ff; color:Black; padding-top:5px; padding-bottom:5px;">
                        <center>
                            Addons
                        </center>
                    </h4>
                    <a id="addonListTableButton" onclick="showAddonTable()" style="float: right; margin-right:5px;" class="btn btn-info">
                    <i class="fa fa-table" aria-hidden="true"></i>
                    </a>  
                    <a id="addonBoxButton" onclick="showAddonBox()" style="float: right; margin-right:5px;" class="btn  btn-info" hidden>
                    <i class="fa fa-th-large" aria-hidden="true"></i>
                    </a> 
                </div>
                @include('addon.listbox')
                @include('addon.table')
                @endif
            </div>
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
        function showAddonTable()
        {
            let addonTable = document.getElementById('addonListTable');
            addonTable.hidden = false
            let addonListTableButton = document.getElementById('addonListTableButton');
            addonListTableButton.hidden = true
            let addonbox = document.getElementById('addonbox');
            addonbox.hidden = true
            let addonBoxButton = document.getElementById('addonBoxButton');
            addonBoxButton.hidden = false
        }
        function showAddonBox()
        {
            let addonTable = document.getElementById('addonListTable');
            addonTable.hidden = true
            let addonListTableButton = document.getElementById('addonListTableButton');
            addonListTableButton.hidden = false
            let addonbox = document.getElementById('addonbox');
            addonbox.hidden = false
            let addonBoxButton = document.getElementById('addonBoxButton');
            addonBoxButton.hidden = true
        }
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