@extends('layouts.main')
<style>
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
@section('content')
    <div class="card-header">
        <h4 class="card-title"> 
        @if($supplierAddonDetails->addon_type_name == 'K')
            Kit
            @else
            Addon
            @endif
             Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-xxl-4 col-lg-4 col-md-4">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Kit Name :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->AddonName->name}}</span>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Kit Code :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->addon_code}}</span>
                    </div>

                    @if($supplierAddonDetails->part_number != '')
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Part Number :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->part_number}} </span>
                    </div>
        	        @endif

                    @if($supplierAddonDetails->payment_condition != '')
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Payment Condition :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->payment_condition}}</span>
                    </div>
                    @endif

                    @if($supplierAddonDetails->additional_remarks != '')
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Additional Remarks :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->additional_remarks}}</span>
                    </div>
                    @endif

                   
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Fixing Charge Amount :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>
                        @if($supplierAddonDetails->fixing_charges_included == 'yes')
                        Included
                        @elseif($supplierAddonDetails->fixing_charges_included == 'no')
                        {{ $supplierAddonDetails->fixing_charge_amount}} AED
                        @endif
                    </span>
                    </div>
                    @can('addon-selling-price-view')
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
                    @endcan
                    @if($supplierAddonDetails->LeastPurchasePrices != '')
                    @can('addon-least-purchase-price-view')
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Least Purchase Price :</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $supplierAddonDetails->LeastPurchasePrices->purchase_price_aed}} AED</span>
                    </div>
                    @endcan
                    @endif
                </div>
            </div>
            <div class="col-xxl-5 col-lg-5 col-md-5">
                <div class="row">
                    @if($supplierAddonDetails->addon_type_name == 'SP')
                        @if( $supplierAddonDetails->is_all_brands == 'yes')
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="choices-single-default" class="form-label">Brand  :</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <span>All Brands</span>
                            </div>
                        @elseif($supplierAddonDetails->is_all_brands == 'no')
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
                    @else
                        @if( $supplierAddonDetails->is_all_brands == 'yes')
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="choices-single-default" class="form-label">Brand  :</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <span>All Brands</span>
                            </div>
                        @elseif($supplierAddonDetails->is_all_brands == 'no')
                            <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                <center>Brand</center>
                            </div>
                            <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                <center>Model Line</center>
                            </div>
                            @foreach($supplierAddonDetails->AddonTypes as $AddonTypes)
                                <div class="divcolorclass" value="5" hidden>
                                </div>
                                <div class="divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                                {{$AddonTypes->brands->brand_name}}
                                </div>                   
                                <div class="divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                                @if($AddonTypes->is_all_model_lines == 'yes')
                                All Model Lines
                                @else
                                {{$AddonTypes->modelLines->model_line}}
                                @endif
                                </div>
                            @endforeach
                        @endif
                    @endif
                 </div>
            </div>
            <div class="col-xxl-3 col-lg-3 col-md-3">
                <div class="row">
                    <center>
                    <img id="blah" src="{{ asset('addon_image/' . $supplierAddonDetails->image) }}" alt="your image" class="contain" data-modal-id="showImageModal"/>
                    </center>
                </div>
            </div>
        </div>
    </br>
    <center><h5 class="card-title">
    @if($supplierAddonDetails->addon_type_name == 'K')
    Suppliers Details And Kit Items
            @else
            Suppliers Details And Purchase Prices
            @endif
    
</h5></center>
    </br>
        @foreach($supplierAddonDetails->AddonSuppliers as $AddonSuppliers)
            <div class="card-body" style="border:solid; border-color:#e9e9ef; border-width: 1px; border-radius: .25rem;">
                <div class="row">

                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Supplier Name :</label>
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
                                            <img id="myImg" src="{{ asset('addon_image/' . $Kit->addon->image) }}" class="image-click-class" style="width:100%; height:125px;" alt="Addon Image"  />
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
        alert('hihih');
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
      </script>
@endsection


