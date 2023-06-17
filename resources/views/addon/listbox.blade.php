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
@if($addon1)
        @if(count($addon1) > 0)
<div class="list2" id="addonbox">
      <div class="row related-addon">
       
        @foreach($addon1 as $addonsdata)
          <div id="{{$addonsdata->id}}" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">  
            <div class="row">
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                Addon Name
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                @if($addonsdata->AddonName->name != '')
                {{$addonsdata->AddonName->name}}
                @endif
              </div>
              @if($addonsdata->payment_condition)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Payment Condition
              </div>
              <div class="labellist databack2 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->payment_condition}}
              </div>
              @endif
              @if($addonsdata->additional_remarks)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Additional Remarks
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->additional_remarks}}
              </div>
              @endif 
              <!-- @if($addonsdata->payment_condition)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Payment Condition
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->payment_condition}}
              @endif
              @if($addonsdata->additional_remarks)
              <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Additional Remarks
              </div>
              <div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
              {{$addonsdata->additional_remarks}}
              </div>
              @endif -->
              <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                @if($addonsdata->image)
                <img src="{{ asset('addon_image/' . $addonsdata->image) }}" style="width:100%; height:155px;" alt="Addon Image" onclick="showImage()" />
                @endif
                <!-- @if($addonsdata->additional_remarks)
                <div class="labellist labeldesign col-xxl-12 col-lg-12 col-md-12">
                  <center>Additional Remarks</center>
                </div>
                <div class=" labellist databack1 col-xxl-12 col-lg-12 col-md-12">
                  {{$addonsdata->additional_remarks}}
                </div>
                @endif -->
                <!-- @if($addonsdata->payment_condition)
                <div class="labellist labeldesign col-xxl-12 col-lg-12 col-md-12">
                  <center>Payment Condition</center>
                </div>
                <div class=" labellist databack1 col-xxl-12 col-lg-12 col-md-12">
                  {{$addonsdata->payment_condition}}
                </div>
                @endif -->
                
              </div>
              
              <div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
                <div class="row" style="padding-right:3px; padding-left:3px;">
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                    Addon Code
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->addon_code}}
                  </div>
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Addon Type
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    @if($addonsdata->addon_type_name == 'K')
                    Kit
                    @elseif($addonsdata->addon_type_name == 'P')
                    Accessories
                    @elseif($addonsdata->addon_type_name == 'SP')
                    Spare Parts
                    @endif
                  </div>
                  @if($addonsdata->PurchasePrices!= null)
                  @if($addonsdata->PurchasePrices->purchase_price_aed != '')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Purchase Price
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->PurchasePrices->purchase_price_aed}} AED
                  </div>
                  @endif
                  @endif
                  @if($addonsdata->LeastPurchasePrices!= null)
                  @if($addonsdata->LeastPurchasePrices->purchase_price_aed != '')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Least Purchase Price
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED
                  </div>
                  @endif
                  @endif
                  @if($addonsdata->SellingPrice!= null)
                  @if($addonsdata->SellingPrice->selling_price != '')
                  
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Selling Price 
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->SellingPrice->selling_price}} AED
                  </div>
                  @endif
                  @endif
                  @if($addonsdata->fixing_charge_amount)
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  Fixing Charge
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->fixing_charge_amount}} AED
                  </div>
                  @endif
                  @if($addonsdata->lead_time)
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Lead Time
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->lead_time}} Days
                  </div>
                  @endif
                  @if($addonsdata->part_number)
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  Part Number
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    {{$addonsdata->part_number}} 
                  </div>
                  @endif
                
                  
                 
                </div>                     
              </div> 
               
              @if($addonsdata->is_all_brands == 'yes')
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Brand
                  </div>
                  <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
                   All Brands
                  </div>
                  @else
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    <center>Brand</center>
                  </div>
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    <center>Model Line</center>
                  </div>
                  @foreach($addonsdata->AddonTypes as $AddonTypes)
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
            </div> 
            </br>
            <div class="row" style="position: absolute; bottom: 3px; right: 5px; ">
              <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12" >
                @if($addonsdata->addon_type_name == 'K')
                <a class="btn btn-sm btn-primary" href="{{ route('addon.kitItems',$addonsdata->id) }}">
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i> Items
                </a>
                @endif
                <a class="btn btn-sm btn-success" href="{{ route('addon.view',$addonsdata->id) }}">
                  <i class="fa fa-eye" aria-hidden="true"></i> View
                </a>
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addonsdata->id) }}">
                  <i class="fa fa-edit" aria-hidden="true"></i> Edit
                </a>
                
              </div>     
            </div>
          </div>
          <!-- </br> -->
          
        @endforeach
       
        <!-- </br> -->
      </div>
    </div>
    @endif
        @endif