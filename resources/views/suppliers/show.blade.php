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
                <div class="card-title">Primary Information</div>
            </div>
            <div class="card-body">
                @if($supplier->supplier)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Vendor') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{$supplier->supplier}}</span>
                        </div>
                    </div>
                @endif
                @if($supplier->type)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Vendor Type') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{$supplier->type}}</span>
                        </div>
                    </div>
                @endif
                @if($supplier->vendorCategories->count() > 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Categories') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>
                                @foreach($supplier->vendorCategories as $vendorCategory)
                                    {{ $vendorCategory->category }}
                                @endforeach
                            </span>
                        </div>
                    </div>
                @endif
                @if(count($supplierTypes) > 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label class="col-form-label text-md-end">{{ __('Vendor Sub Categories') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
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
                @endif
                @if($supplier->comment)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Comment') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{$supplier->comment}}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Contact Details</div>
            </div>
            <div class="card-body">
                @if($supplier->contact_number)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Contact Number') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{ $supplier->contact_number }}</span>
                        </div>
                    </div>
                @endif
                @if($supplier->person_contact_by)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label class="col-form-label text-md-end">{{ __('Person Contact By') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{ $supplier->person_contact_by }}</span>
                        </div>
                    </div>
                @endif
                @if($supplier->email)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label class="col-form-label text-md-end">{{ __('Email') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{ $supplier->email }}</span>
                        </div>
                    </div>
                @endif
                @if($supplier->alternative_contact_number)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{ $supplier->alternative_contact_number }}</span>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Classification</div>
            </div>
            <div class="card-body">
                @if($supplier->supplier)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Vendor') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{$supplier->supplier}}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Preferences</div>
            </div>
            <div class="card-body">
                @if(count($otherPaymentMethods) > 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('payment Methods') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>
                                 @foreach($otherPaymentMethods as $otherPaymentMethod)
                                    {{ $otherPaymentMethod->PaymentMethods->payment_methods }} ,
                                @endforeach
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Documents</div>
            </div>
            <div class="card-body">
                @if($supplier->supplier)
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="choices-single-default" class="form-label">{{ __('Vendor') }}</label>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <span>{{$supplier->supplier}}</span>
                        </div>
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
</div>
    @endif
    @endcan
    <script type="text/javascript">
        var data = {!! json_encode($supplier) !!};
        $(document).ready(function ()
        {
            // $("#adoon").attr("data-placeholder","Choose Addon Code....     Or     Type Here To Search....");
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
    </script>
@endsection

