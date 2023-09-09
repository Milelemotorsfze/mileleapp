<style>
  .showImage
  {
    width: auto;
    height: auto;
    max-width:1200px;
    max-height:1200px;
  }
  .modal-xl
  {
    max-width: 99% !important;
  }
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
  }
  .related-label
  {
    padding-top:0px;
    padding-bottom:0px;
  }
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
  .overlay
  {
    position: fixed; /* Positioning and size */
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(128,128,128,0.5); /* color */
    display: none; /* making it hidden by default */
  }
  .testtransform { text-transform: lowercase; }

.testtransform:first-letter {
  text-transform: uppercase;
}
.widthClass
{
  width: 160px !important;
  margin-left:3px !important;
}
.widthData
{
  width: 399px !important;
}
</style>
@if($addon1)
  @if(count($addon1) > 0)      
    <div class="list2" id="addonbox">
      <div class="row related-addon">
        @foreach($addon1 as $addonsdata)
          <div id="{{$addonsdata->id}}" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">  
            <div class="row">
              <div class="widthClass labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                Addon Name
              </div>
              <div class="testtransform widthData labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                @if($addonsdata->AddonName->name != '')
                  {{$addonsdata->AddonName->name}}
                @endif
              </div>
              @if($addonsdata->payment_condition)
                <div class="widthClass labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                  Payment Condition
                </div>  
              <div class="testtransform widthData labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                {{$addonsdata->payment_condition}}
              </div>
              @endif
              @if($addonsdata->additional_remarks)
                <div class="widthClass labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
              Additional Remarks
              </div>
              <div class="testtransform widthData labellist databack1 col-xxl-8 col-lg-8 col-md-8">
                {{$addonsdata->additional_remarks}}
              </div>
              @endif
                          
              <div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
                <div class="row" style="padding-right:3px; padding-left:3px;">
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
                    Addon Code
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
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
                  @if($content == '')
                    @if($addonsdata->PurchasePrices!= null)
                      @if($addonsdata->PurchasePrices->purchase_price_aed != '')
                        @can('supplier-addon-purchase-price-view')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                        @endphp
                        @if ($hasPermission)
                          <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                            Purchase Price
                          </div>
                          <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                            {{$addonsdata->PurchasePrices->purchase_price_aed}} AED
                          </div>
                        @endif
                        @endcan
                      @endif
                    @endif
                  @endif
                  @if($addonsdata->LeastPurchasePrices!= null)
                    @if($addonsdata->LeastPurchasePrices->purchase_price_aed != '')
                      @can('addon-least-purchase-price-view')
                      @php
                      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                      @endphp
                      @if ($hasPermission)
                        <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                          Least Purchase Price
                        </div>
                        <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                          {{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED
                        </div>
                      @endif
                      @endcan
                    @endif
                  @endif
                  @can('addon-selling-price-view')
                  @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                    @endphp
                    @if ($hasPermission)
                    @if($addonsdata->SellingPrice!= null OR $addonsdata->PendingSellingPrice!= null)
                      <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                        Selling Price 
                      </div>
                      <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">       
                        @if($addonsdata->SellingPrice!= null)
                          @if($addonsdata->SellingPrice->selling_price != '')
                            {{$addonsdata->SellingPrice->selling_price}} AED
                          @endif
                          @elseif($addonsdata->PendingSellingPrice!= null)
                          @if($addonsdata->PendingSellingPrice->selling_price != '')
                            {{$addonsdata->PendingSellingPrice->selling_price}} AED 
                            </br>
                            <label class="badge badge-soft-danger">Approval Awaiting</label>
                          @endif
                        @endif
                      </div>
                    @endif
                    @endif
                  @endcan
                  <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                    Fixing Charge
                  </div>
                  <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                    @if($addonsdata->fixing_charges_included == 'yes')
                      <label class="badge badge-soft-success">Fixing Charge Included</label>
                    @else
                      @if($addonsdata->fixing_charge_amount != '')
                        {{$addonsdata->fixing_charge_amount}} AED
                      @endif
                    @endif
                  </div>
                  @if($addonsdata->lead_time)
                    <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                      Lead Time
                    </div>
                    <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
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
              <div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
                @if($addonsdata->image)
                      <img id="myImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}" alt="Addon Image" 
                      style="width:100%;max-width:300px;max-height:200px;">
                @endif
              </div> 
              @if($addonsdata->is_all_brands == 'yes')
                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  Brand
                </div>
                <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                  All Brands
                </div>
              @else
                @if($addonsdata->addon_type_name == 'SP')
                  <div class="labellist labeldesign col-xxl-3 col-lg-3 col-md-3">
                    <center>Brand</center>
                  </div>
                  <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
                    <center> 
                        Model Line
                    </center>
                  </div>
                  <div class="labellist labeldesign col-xxl-5 col-lg-5 col-md-5">
                    <center> 
                        Model Description
                    </center>
                  </div>
                @else
                 <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  <center>Brand</center>
                </div>
                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
                  <center>
                      Model Line
                  </center>
                </div>
                @endif
                @foreach($addonsdata->AddonTypes as $AddonTypes)
                  <div class="divcolorclass" value="5" hidden>
                  </div>
                  @if($addonsdata->addon_type_name == 'SP')
                    <div class="testtransform divcolor labellist databack1 col-xxl-3 col-lg-3 col-md-3">
                      {{$AddonTypes->brands->brand_name}}
                    </div>  
                    <div class="testtransform divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">          
                      @if(isset($AddonTypes->modelLines->model_line))
                        {{$AddonTypes->modelLines->model_line}}                        
                        @endif                   
                        @if($AddonTypes->is_all_model_lines == 'yes')
                          All Model Lines
                        @endif                   
                    </div>                 
                    <div class="testtransform divcolor labellist databack1 col-xxl-5 col-lg-5 col-md-5">
                        {{$AddonTypes->modelDescription->model_description ?? ''}} 
                    </div>
                  @else
                    <div class="testtransform divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                      {{$AddonTypes->brands->brand_name}}
                    </div>                   
                    <div class="testtransform divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6">
                        @if(isset($AddonTypes->modelLines->model_line))
                          {{$AddonTypes->modelLines->model_line}}  
                        @endif
                        @if($AddonTypes->is_all_model_lines == 'yes')
                          All Model Lines
                        @endif
                    </div>
                  @endif 
                @endforeach
              @endif      
            </div> 
            </br>
            <div class="row" style="position: absolute; bottom: 3px; right: 5px; ">
              <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12" >
                @include('addon.action.addsellingprice')
                @include('addon.action.action')
                </div> 
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="overlay"></div>
  @else
      <h6 style="text-align:center; padding-top:10px;">No data found !!</h6>
  @endif
  @endif
  