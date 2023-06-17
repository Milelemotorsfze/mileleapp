@extends('layouts.main')
<style>
            .related-addon-header
            {
                background-color:#5156be;
            }
            .related-addon-h4
            {
                padding-top:8px;
                padding-bottom:8px;
                text-align:center;
                color:white;
            }
            .related-addon .each-addon
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
            .related-addon input
            {
                padding-top:0px;
                padding-bottom:0px;
                padding-right:0px;
                padding-left:0px;
                /* height:50%; */
            }
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
              /* #addonListTable{
            display: none;
          } */
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title"> Kit Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Kit Name :</label>
            </div>
            <div class="col-lg-2 col-md-9 col-sm-12">
                <span></span>
            </div>
        </div>
        @foreach($supplierAddonDetails as $supplierAddonDetail)
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Supplier Name :</label>
                </div>
                <div class="col-lg-2 col-md-9 col-sm-12">
                    <span>{{$supplierAddonDetail->Suppliers->supplier}}</span>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label">Purchase Price In AED :</label>
                </div>
                <div class="col-lg-2 col-md-9 col-sm-12">
                    <span>{{$supplierAddonDetail->purchase_price_aed}} AED</span>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label">Purchase Price In USD :</label>
                </div>
                <div class="col-lg-2 col-md-9 col-sm-12">
                    <span >{{$supplierAddonDetail->purchase_price_usd}} USD</span>
                </div>
            </div>
            </br> 
            <div class="row">
                @foreach($supplierAddonDetail->Kit as $Kit)
                    <div class="list2" id="addonbox">
                        <div class="row related-addon"> 
                            <div id="" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">  
                                <div class="row">
                                    <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                                        Item Name
                                    </div>
                                    <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                                        {{$Kit->addon->AddonName->name}}
                                    </div>
                                    <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                                        <img src="{{ asset('addon_image/' . $Kit->addon->image) }}" style="width:100%; height:125px;" alt="Addon Image"  />
                                    </div>
                                    <div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
                                        <div class="row" style="padding-right:3px; padding-left:3px;">
                                            <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                                                Item Code
                                            </div>
                                            <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                                                {{ $Kit->addon->addon_code }}
                                            </div>
                                            <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                                                Addon Type
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
                                        </div>                     
                                    </div> 
                                    </br>
                                </div>  
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection


