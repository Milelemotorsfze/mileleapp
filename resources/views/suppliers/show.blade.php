@extends('layouts.main')
<style>
    /* .widthClass
    {
        width: 140px !important;
    } */
      .modal
    {
        width: 100% !important;
        height: 100% !important;
        top: 0%!important;
        left: 0%!important;
    }
body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modalForImage {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 10px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%!important;
  height: 100%!important;
  overflow: auto; /* Enable scroll if needed */
  background-color: black; /* Fallback color */
  background-color: rgba(128,128,128,0.5);/* Black w/ opacity */
}

/* Modal Content (image) */
.modalContentForImage {
  padding-top: 100px; /* Location of the box */
  margin: auto;
  display: block;
  width: 100%!important;
  height:auto!important;
  max-width: 700px!important;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 100%!important;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modalContentForImage, #caption {
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)}
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)}
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 50px;
  right: 50px;
  color: black;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modalContentForImage {
    width: 100%;
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

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
@section('content')
@can('addon-supplier-view')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-supplier-view']);
@endphp
@if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Vendor Details</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('suppliers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-header">
                <div class="card-title fw-bold">Primary Information</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Vendor') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->supplier}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                            <label for="choices-single-default" class="form-label fw-bold">{{ __('Vendor Type') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->type}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                            <label for="choices-single-default" class="form-label fw-bold">{{ __('Categories') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                @if($supplier->vendorCategories->count() > 0)
                                    <span>
                                    @foreach($supplier->vendorCategories as $vendorCategory)
                                        {{ $vendorCategory->category }}
                                    @endforeach
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                            <label for="choices-single-default" class="form-label fw-bold">{{ __('Sub Categories') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>
                                     @foreach($supplierTypes as $t)
                                     @if($t->supplier_type == 'spare_parts')
                                         Spare Parts ,
                                     @elseif($t->supplier_type == 'accessories')
                                         Accessories ,
                                     @elseif($t->supplier_type == 'freelancer')
                                         Freelancer ,
                                     @elseif($t->supplier_type == 'garage')
                                         Garage ,
                                     @elseif($t->supplier_type == 'warranty')
                                         Warranty ,
                                     @elseif($t->supplier_type == 'demand_planning')
                                         Demand Planning ,
                                     @elseif($t->supplier_type == 'Bulk')
                                         Bulk ,
                                     @elseif($t->supplier_type == 'Small Segment')
                                         Small Segment ,
                                     @elseif($t->supplier_type == 'Other')
                                         Other ,
                                     @endif
                                 @endforeach
                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Web Address') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->web_adress}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                            <label for="choices-single-default" class="form-label fw-bold">{{ __('Comment') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                            <span>{{$supplier->comment}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Contact Details</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Contact Number') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->contact_number}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Office Phone') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->office_phone}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Person Contact By') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->person_contact_by}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Address') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->address}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-5 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Alternative Contact Number') }}</label>
                            </div>
                            <div class="col-xxl-7 col-md-6 col-sm-12 ">
                                <span>{{$supplier->alternative_contact_number}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-5 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Phone') }}</label>
                            </div>
                            <div class="col-xxl-7 col-md-6 col-sm-12 ">
                                <span>{{$supplier->phone}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __(' Contact Person') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->contact_person}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Email') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->email}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Fax') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->fax}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title fw-bold">Classification</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Passport Number') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->passport_number}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Nationality') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->nationality}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Trade Registration Place') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->trade_registration_place}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Trade License Number') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->trade_license_number}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Trade Registration Place') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->trade_registration_place}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title fw-bold">Preferences</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Preference ID') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->prefered_id}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Preference Label') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->prefered_label}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Notes ') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->notes}}</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Payment Methods') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>
                                    @if(count($otherPaymentMethods) > 0)
                                        @foreach($otherPaymentMethods as $otherPaymentMethod)
                                            {{ $otherPaymentMethod->PaymentMethods->payment_methods }} ,
                                        @endforeach
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-5 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Communication Channels') }}</label>
                            </div>
                            <div class="col-xxl-7 col-md-6 col-sm-12 ">
                                <span>
                                    @if($supplier->is_communication_mobile == true)
                                      Mobile,
                                    @endif
                                    @if($supplier->is_communication_email == true)
                                      Email,
                                    @endif
                                    @if($supplier->is_communication_postal == true)
                                       Postal,
                                    @endif
                                    @if($supplier->is_communication_fax == true)
                                       Fax,
                                    @endif
                                    @if($supplier->is_communication_any == true)
                                      Any
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12">
                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Shipping Address') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->shipping_address}}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xxl-4 col-md-6 col-sm-12 ">
                                <label for="choices-single-default" class="form-label fw-bold">{{ __('Billing Address') }}</label>
                            </div>
                            <div class="col-xxl-8 col-md-6 col-sm-12 ">
                                <span>{{$supplier->billing_address}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title fw-bold">Documents</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-4 col-md-6 col-sm-12 text-center">
                    @if($supplier->passport_copy_file)
                            <h6 class="fw-bold text-center mb-1">Passport</h6>
                            <iframe src="{{ url('vendor/passport/' . $supplier->passport_copy_file) }}" alt="Passport"></iframe>
                            <a href="{{ url('vendor/passport/' . $supplier->passport_copy_file) }}" target="_blank">
                                <button class="btn btn-primary m-2">View</button>
                            </a>
                            <a href="{{ url('vendor/passport/' . $supplier->passport_copy_file) }}" download>
                                <button class="btn btn-info">Download</button>
                            </a>

                        @endif
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12 text-center">
                        @if($supplier->trade_license_file)
                            <h6 class="fw-bold text-center mb-1">Trade License</h6>
                            <iframe src="{{ url('vendor/trade_license/' . $supplier->trade_license_file) }}" alt="Trade License"></iframe>
                            <a href="{{ url('vendor/trade_license/' . $supplier->trade_license_file) }}" target="_blank">
                                <button class="btn btn-primary m-2">View</button>
                            </a>
                            <a href="{{ url('vendor/trade_license/' . $supplier->trade_license_file) }}" download>
                                <button class="btn btn-info">Download</button>
                            </a>
                        @endif
                    </div>
                    <div class="col-xxl-4 col-md-6 col-sm-12 text-center">
                        @if($supplier->passport_copy_file)
                            <h6 class="fw-bold text-center mb-1">VAT Certificate</h6>
                            <iframe src="{{ url('vendor/vat_certificate/' . $supplier->vat_certificate_file) }}" alt="VAT Certificate"></iframe>
                            <a href="{{ url('vendor/vat_certificate/' . $supplier->vat_certificate_file) }}" target="_blank">
                                <button class="btn btn-primary m-2">View</button>
                            </a>
                            <a href="{{ url('vendor/vat_certificate/' . $supplier->vat_certificate_file) }}" download>
                                <button class="btn btn-info">Download</button>
                            </a>
                        @endif
                    </div>
                </div>
                @if($supplier->supplierDocuments->count() > 0)
                    <div class="row m-3">
                        <h6 class="fw-bold text-center mb-13">Other Documents</h6>
                            @foreach($supplier->supplierDocuments as $document)
                            <div class="col-xxl-4 col-md-6 col-sm-12 text-center">
                                <iframe src="{{ url('vendor/other-documents/' . $document->file) }}" alt="Other Document"></iframe>
                                <a href="{{ url('vendor/other-documents/' . $document->file) }}" target="_blank">
                                    <button class="btn btn-primary m-2">View</button>
                                </a>
                                <a href="{{ url('vendor/other-documents/' . $document->file) }}" download>
                                    <button class="btn btn-info">Download</button>
                                </a>

                            </div>
                            @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
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
    <div id="myModal" class="modal modalForImage">
  <span class="closeImage close">&times;</span>
  <img class="modalContentForImage" id="img01">
  <div id="caption"></div>
    <input type="hidden" id="start" value="0">
    <input type="hidden" id="rowperpage" value="{{ $rowperpage }}">
    <input type="hidden" id="totalrecords" value="{{$rowperpage}}">
    <input type="hidden" name="addon_type" id="addon_type" value="{{$data}}">
</div>
    @endif
    @endcan
    <script type="text/javascript">
        var data = {!! json_encode($supplier) !!};
        $(document).ready(function ()
        {
            // $("#adoon").attr("data-placeholder","Choose Addon Name....     Or     Type Here To Search....");
            // $("#adoon").select2();
        });
         // show image in large view
    $('.image-click-class').click(function (e)
    {
        var id =  $(this).attr('id');
        var src = $(this).attr('src');
        var modal = document.getElementById("myModal");
        var img = document.getElementById(id);
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        modal.style.display = "block";
        modalImg.src = src;
        captionText.innerHTML = this.alt;
      })
      $('.closeImage').click(function (e)
      {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
      })
        if(data.contact_number != null)
        {
            var contact_number = window.intlTelInput(document.querySelector("#contact_number"), {
            separateDialCode: true,
            preferredCountries:["ae"],
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
            });
        }
        if(data.alternative_contact_number != null)
        {
        var alternative_contact_number = window.intlTelInput(document.querySelector("#alternative_contact_number"), {
        separateDialCode: true,
        preferredCountries:["ae"],
        hiddenInput: "full",
        utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });
    }
        // $("form").submit(function() {
        // var full_number = contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
        // $("input[name='contact_number[full]'").val(full_number);
        // var full_alternative_contact_number = alternative_contact_number.getNumber(intlTelInputUtils.numberFormat.E164);
        // $("input[name='alternative_contact_number[full]'").val(full_number);
        // });
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

        function checkWindowSize(){
            if($(window).height() >= $(document).height()){
                // Fetch records
                fetchData();
            }
        }
        function onScroll(){
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                var start = Number($('#start').val());

                var totalrecords = Number($('#totalrecords').val());
                var rowperpage = Number($('#rowperpage').val());
                start = start + rowperpage;

                if(start <= totalrecords) {
                    console.log("start value less add 12");
                    $('#start').val(start);
                }
                fetchData(start,totalrecords);
            }
        }
        $(window).scroll(function(){
            onScroll();
        });
        function fetchData(start,totalrecords) {
            var addon_type = $('#addon_type').val();
            var rowperpage = Number($('#rowperpage').val());

            $.ajax({
                url:"{{url('getAddonlists')}}",
                data: {
                    start:start,
                    addon_type:addon_type,
                },
                dataType: 'json',
                success: function(response){
                    var total = parseInt(rowperpage) + parseInt(totalrecords);
                    $('#totalrecords').val(total);
                    $(".each-addon:last").after(response.html).show().fadeIn("slow");
                    // checkWindowSize();
                    var addonIds = response.addonIds;
                    hideModelDescription(addonIds);
                }
            });
        }
    </script>
@endsection

