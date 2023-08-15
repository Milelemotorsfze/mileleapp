@extends('layouts.main')
<style>
    .modal
    {
        width: 100% !important;
        height: 100% !important;
        top: 0%!important;
        left: 0%!important;
    }
            /* .related-addon-header
            {
                background-color:#5156be;
            }
            .related-addon-h4
            {
                padding-top:8px;
                padding-bottom:8px;
                text-align:center;
                color:white;
            } */
            .each-addon
            {
                border-style: solid;
                border-width: 1px;
                border-color: white;
                border-radius: 5px;
                /* margin-top: 10px; */
                padding-top:10px;
                padding-bottom:10px;
                background-color:#f2f2f2;
            }
            /* .related-addon input
            {
                padding-top:0px;
                padding-bottom:0px;
                padding-right:0px;
                padding-left:0px;
            } */
            .related-label
            {
                padding-top:0px;
                padding-bottom:0px;
            }
             /* .related-addon .related-input-div
            {
                margin-top:0px;
                margin-bottom:0px;
                margin-right:0px;
                margin-left:0px;
            } */
            .list2
            {
              margin-right:10px;
                  margin-left:10px;
            }
            .labellist
            {
              border-style: solid;
                  border-width: 1px;
                  border-color: #5156be;
                  border-radius: 5px;
            }
            .labeldesign
            {
              background-color:#6266c4;
              color:white;
              border-color: white;
            }
            .databack1
            {
              background-color:#e6e6ff;
              border-color: white;
            }
            .databack2
            {
              background-color:#f2f2f2;
              border-color: white;
            }
          #blah
            {
                width: 250px;
                height: 250px;
                padding-top:0px;
                margin-top:0px;
            }
        .contain
    {
    object-fit: contain;
    }
</style>
<style>
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
  width: 100%; /* Full width */
  height: 100%; /* Full height */
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
.widthinput
    {
        height:35px!important;
    }
    .widthinput1
    {
        height:25px!important; 
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">
                Kit Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-xxl-4 col-lg-4 col-md-4">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> 
                                Kit Name :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->AddonName->name}}</span>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label">
                                Kit Code :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->addon_code}}</span>
                    </div>
                    @if($supplierAddonDetails->additional_remarks != '')
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Additional Remarks :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $supplierAddonDetails->additional_remarks}}</span>
                    </div>
                    @endif

                    @can('addon-selling-price-view')
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                    @endphp
                    @if ($hasPermission)
                        @if($supplierAddonDetails->SellingPrice!= null OR $supplierAddonDetails->PendingSellingPrice!= null)
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="choices-single-default" class="form-label"> Selling Price :</label>
                            </div>
                            <div class="col-lg-6 col-md-9 col-sm-12">
                                @if($supplierAddonDetails->SellingPrice!= null)
                                    @if($supplierAddonDetails->SellingPrice->selling_price != '')
                                        {{$supplierAddonDetails->SellingPrice->selling_price}} AED
                                    @endif
                                @elseif($supplierAddonDetails->PendingSellingPrice!= null)
                                    @if($supplierAddonDetails->PendingSellingPrice->selling_price != '')
                                        {{$supplierAddonDetails->PendingSellingPrice->selling_price}} AED
                                        <label class="badge badge-soft-danger">Approval Awaiting</label>
                                    @endif
                                @endif
                            </div>
                        @endif
                    @endif
                    @endcan

                </div>
            </div>
            <div class="col-xxl-5 col-lg-5 col-md-5">
                <div class="row">
                    @if($supplierAddonDetails->addon_type_name == 'K')
                        @if($supplierAddonDetails->is_all_brands == 'no')
                            <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                                <center>Brand</center>
                            </div>
                            <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                                <center>Model Line</center>
                            </div>
                            <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                                <center>Model Description</center>
                            </div>
                            @foreach($supplierAddonDetails->AddonTypes as $AddonTypes)
                                <div class="divcolorclass" value="5" hidden>
                                </div>
                                <div class="divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">
                                {{$AddonTypes->brands->brand_name}}
                                </div>
                                <div class="divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">
                                @if($AddonTypes->is_all_model_lines == 'yes')
                                All Model Lines
                                @else
                                {{$AddonTypes->modelLines->model_line}}
                                @endif
                                </div>
                                <div class="divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">
                                {{$AddonTypes->modelDescription->model_description ?? ''}}
                                </div>
                            @endforeach
                        @endif
                    @endif
                 </div>
            </div>
            <div class="col-xxl-3 col-lg-3 col-md-3">
                <div class="row">
                    <center>
                    <img id="blah" src="{{ asset('addon_image/' . $supplierAddonDetails->image) }}" alt="Addon image" class="contain image-click-class" data-modal-id="showImageModal"/>
                    </center>
                </div>
            </div>
        </div>
    </br>

<!--kit common items start-->
<center><h5 class="card-title">
     Kit Items and Prices
</h5></center>
    </br>
    <!-- <div class="card-body"> -->
    <div class="row" style="padding-left:10px; padding-right:10px;">
        <div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px;">
        Previous Purchase Price (AED)
        </div>
        <div class="labellist databack1 col-xxl-2 col-lg-2 col-md-2">
        <input type="text" class="form-control widthinput" placeholder="Previous Purchase Price" value="" readonly>
        </div>

        <div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px;">
        Current Purchase Price (AED)
        </div>
        <div class="labellist databack1 col-xxl-2 col-lg-2 col-md-2">
        <input type="text" class="form-control widthinput" name="addon_code"
                            placeholder="Previous Purchase Price" value="{{$supplierAddonDetails->totalPrice}}" readonly>
        </div>

        <div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px;">
        Selling Price (AED)
        </div>
        <div class="labellist databack1 col-xxl-2 col-lg-2 col-md-2">
        <input type="text" class="form-control widthinput" name="addon_code"
                            placeholder="Previous Purchase Price" value="">
        </div>
    </div>
    <!-- </div> -->
</br>
<div class="card-body" style="border:solid; border-color:#e9e9ef; border-width: 1px; border-radius: .25rem;">
<div class="row">
<div hidden>{{$i=0;}}</div>
    @foreach($supplierAddonDetails->KitItems as $Kit)
    <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
                        <!-- <div class="list2" id="addonbox"> -->
                            <!-- <div class="row related-addon">  -->
                                <div id="" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                                            Item Name
                                        </div>
                                        <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                                            {{$Kit->item->AddonName->name}}
                                        </div>
                                        <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                                            <img id="myImg" src="{{ asset('addon_image/' . $Kit->item->image) }}" class="image-click-class"
                                            style="width:100%; height:125px;" alt="Addon Image"  />
                                        </div>
                                        <div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
                                            <div class="row" style="padding-right:3px; padding-left:3px;">
                                                
                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                                                    Item Code
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{ $Kit->item->addon_code }}
                                                </div>
                                                @if($Kit->item->part_number != '')
                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                                                    Part Number
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{ $Kit->item->part_number}}
                                                </div>
                                                @endif
                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Quantity
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                <input id="quantity_{{$i}}"type="text" class="form-control widthinput1" name="addon_code"
                                                placeholder="Previous Purchase Price" value="{{$Kit->quantity}}" readonly>
                                                    
                                                </div>

                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Purchase Price / Unit
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                <input id="unit_price_{{$i}}"type="text" class="form-control widthinput1" name="addon_code"
                                                placeholder="Previous Purchase Price" value="{{$Kit->leastPriceSupplier->purchase_price_aed}}" readonly>
                                                </div>

                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Total Purchase Price
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                <input id="total_price_{{$i}}"type="text" class="form-control widthinput1" name="addon_code"
                                                placeholder="Previous Purchase Price" value="{{$Kit->totalItemPrice}}" readonly>
                                                 
                                                </div>

                                                

                                            </div>
                                        </div>
                                        <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4" style="padding-top:7px;">
                                            Item Supplier
                                        </div>
                                        <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                                        <select id="supplier_{{$i}}" name="supplier[{{$i}}]" class="form-control widthinput" onchange="calculatePrice(this, {{$i}})" autofocus>
                                            @foreach($Kit->allItemSuppliers as $allItemSuppliers)
                                            <option  value="{{$allItemSuppliers->purchase_price_aed}}">{{$allItemSuppliers->Suppliers->supplier}} ( {{$allItemSuppliers->purchase_price_aed}} AED ) </option>
                                            @endforeach
                                        </select>
                                        </div>
                                        </br>
                                    </div>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    @endforeach
</div>
</div>
<!--kit common items end-->



    <center><h5 class="card-title">
    Vendors Details And Kit Items
</h5></center>
    </br>
        @foreach($supplierAddonDetails->AddonSuppliers as $AddonSuppliers)
            <div class="card-body" style="border:solid; border-color:#e9e9ef; border-width: 1px; border-radius: .25rem;">
                <div class="row">

                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Vendor Name :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span>{{ $AddonSuppliers->Suppliers->supplier}}</span>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Purchase Price In AED :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span>{{ $AddonSuppliers->purchase_price_aed}} AED</span>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Purchase Price In USD :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span>{{ $AddonSuppliers->purchase_price_usd}} USD</span>
                    </div>

                    @if($supplierAddonDetails->addon_type_name == 'SP')
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Quotation Date :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        @if($AddonSuppliers->updated_at != '')
                        <span>{{ $AddonSuppliers->updated_at}} 
                            </span>
                            @else
                            <label class="badge badge-soft-info">Not Added</label>
                            @endif
                    </div>
                    @endif
                    
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Lead Time :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        @if($AddonSuppliers->lead_time_max != '' || $AddonSuppliers->lead_time_min != '')
                        <span>{{ $AddonSuppliers->lead_time_min}} 
                            @if($AddonSuppliers->lead_time_max != '' && $AddonSuppliers->lead_time_min < $AddonSuppliers->lead_time_max) 
                            - {{$AddonSuppliers->lead_time_max}} 
                            @endif Days</span>
                            @else
                            <label class="badge badge-soft-info">Not Added</label>
                            @endif
                    </div>

                    @if($AddonSuppliers->Suppliers->contact_number != '')
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Contact Number :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span>{{ $AddonSuppliers->Suppliers->contact_number}}</span>
                    </div>
                    @endif

                    @if($AddonSuppliers->Suppliers->alternative_contact_number != '')
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Alternative Contact Number :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span>{{ $AddonSuppliers->Suppliers->alternative_contact_number}}</span>
                    </div>
                    @endif

                    @if($AddonSuppliers->Suppliers->email != '')
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Email</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span > {{ $AddonSuppliers->Suppliers->email}}</span>
                    </div>
                    @endif

                    @if($AddonSuppliers->Suppliers->contact_person != '')
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Contact Person :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span > {{ $AddonSuppliers->Suppliers->contact_person}}</span>
                    </div>
                    @endif

                    @if($AddonSuppliers->Suppliers->person_contact_by != '')
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Person Contact By :</label>
                    </div>
                    <div class="col-lg-2 col-md-9 col-sm-12">
                        <span > {{ $AddonSuppliers->Suppliers->person_contact_by}}</span>
                    </div>
                    @endif
                </div>

                </br>
                <div class="row">
                @foreach($AddonSuppliers->Kit as $Kit)
                        <!-- <div class="list2" id="addonbox"> -->
                            <!-- <div class="row related-addon">  -->
                                <div id="" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                                            Item Name
                                        </div>
                                        <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                                            {{$Kit->addon->AddonName->name}}
                                        </div>
                                        <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                                            <img id="myImg" src="{{ asset('addon_image/' . $Kit->addon->image) }}" class="image-click-class"
                                            style="width:100%; height:125px;" alt="Addon Image"  />
                                        </div>
                                        <div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
                                            <div class="row" style="padding-right:3px; padding-left:3px;">
                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Item Type
                                                </div>
                                                <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                                                    @if($Kit->addon->addon_type_name == 'K')
                                                    Kit
                                                    @elseif($Kit->addon->addon_type_name == 'P')
                                                    Accessories
                                                    @elseif($Kit->addon->addon_type_name == 'SP')
                                                    Spare Parts
                                                    @endif
                                                </div>

                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                                                    Item Code
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{ $Kit->addon->addon_code }}
                                                </div>

                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Quantity
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{$Kit->quantity}}
                                                </div>

                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Purchase Price / Unit
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{$Kit->unit_price_in_aed}} AED
                                                </div>

                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                    Total Purchase Price
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{$Kit->total_price_in_aed}} AED
                                                </div>

                                                @if($Kit->addon->part_number != '')
                                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                                                    Part Number
                                                </div>
                                                <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                    {{ $Kit->addon->part_number}}
                                                </div>
                                                @endif

                                            </div>
                                        </div>
                                        </br>
                                    </div>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    @endforeach
                </div>
            </div>
            </br>
        @endforeach
    </div>
    <div id="myModal" class="modal modalForImage">
  <span class="closeImage close">&times;</span>
  <img class="modalContentForImage" id="img01">
  <div id="caption"></div>
</div>
<script type="text/javascript">
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
      function calculatePrice(current, price)
      {
        alert($('#rowIndexCount').val());
      }
      </script>
     
@endsection


