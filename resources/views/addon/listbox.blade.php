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
    /* .widthClass
    {
      width: 164px !important;
      margin-left:3px !important;
    }
    .widthData
    {
      width: 467px !important;
    }
    @media only screen and (max-device-width: 480px)
        {
            #showImage
            {
                width: 100%;
                height: 100%;
            }
            #blah
            {
                width: 200px;
                height: 200px;
            }
        }
        @media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)
        {
            #showImage
            {
                width: 100%;
                height: 100%;
            }
        }
        @media only screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)
        {
            #showImage
            {
                width: 100%;
                height: 100%;
            }
        }
        @media only screen and (max-device-width: 1280px)
        {

        }
        @media only screen and (min-device-width: 1280px)
        {
          .widthClass
        {
          width: 164px !important;
          margin-left:3px !important;
        }
        .widthData
        {
          width: 467px !important;
        }
        }   */
</style>
@if($addon1)
    @if(count($addon1) > 0)
        <div class="list2" id="addonbox">
            <div class="row related-addon">
                @foreach($addon1 as $value => $addonsdata)
                    <div id="{{$addonsdata->id}}" class="each-addon col-xxl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="row">
                            <div class="col-xxl-7 col-lg-7 col-md-12 col-sm-12 col-12">
                                <div class="row" style="padding-right:3px; padding-left:3px;">
                                    <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                        Addon Name
                                    </div>
                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                        @if($addonsdata->AddonName->name != '')
                                            {{$addonsdata->AddonName->name}} 
                                            @if(isset($addonsdata->AddonDescription))
                                                @if($addonsdata->AddonDescription->description != '')- {{$addonsdata->AddonDescription->description}}@endif
                                            @endif
                                        @endif
                                    </div>
                                    <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                        Addon Code
                                    </div>
                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                        {{$addonsdata->addon_code}}
                                    </div>
                                    <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                        Addon Type
                                    </div>
                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                        @if($addonsdata->addon_type_name == 'K')
                                            Kit
                                        @elseif($addonsdata->addon_type_name == 'P')
                                            Accessories
                                        @elseif($addonsdata->addon_type_name == 'SP')
                                            Spare Parts
                                        @endif
                                    </div>
                                    @if($content == '')
                                        @if($addonsdata->PurchasePrices->lead_time_min != '' OR $addonsdata->PurchasePrices->lead_time_max != '')
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                            Lead Time
                                        </div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                            {{$addonsdata->PurchasePrices->lead_time_min}}
                                            @if($addonsdata->PurchasePrices->lead_time_max != '' && $addonsdata->PurchasePrices->lead_time_min < $addonsdata->PurchasePrices->lead_time_max)
                                                - {{$addonsdata->PurchasePrices->lead_time_max}}
                                            @endif
                                            Days
                                        </div>
                                        @endif
                                    @endif
                                    @if($content == '')
                                        @if($addonsdata->PurchasePrices!= null)
                                            @if($addonsdata->PurchasePrices->purchase_price_aed != '')
                                                @can('supplier-addon-purchase-price-view')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                                    @endphp
                                                    @if ($hasPermission)
                                                    <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                        Purchase Price
                                                    </div>
                                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                        {{$addonsdata->PurchasePrices->purchase_price_aed}} AED
                                                    </div>
                                                    @endif
                                                @endcan
                                            @endif
                                        @endif
                                    @endif
                                    @if($content == '')
                                        @if($addonsdata->addon_type_name == 'SP')
                                            @if($addonsdata->PurchasePrices!= null)
                                                @if($addonsdata->PurchasePrices->updated_at != '')
                                                <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    Quotation Date
                                                </div>
                                                <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    {{$addonsdata->PurchasePrices->updated_at}}
                                                </div>
                                                @endif
                                            @endif
                                        @endif
                                        @if($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'P')
                                            @if($addonsdata->least_purchase_price != null)
                                                @if($addonsdata->least_purchase_price->purchase_price_aed != '')
                                                    @can('addon-least-purchase-price-view')
                                                        @php
                                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                            Least Purchase Price
                                                        </div>
                                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                            {{$addonsdata->least_purchase_price->purchase_price_aed}} AED
                                                        </div>
                                                        @endif
                                                    @endcan
                                                @endif
                                            @endif
                                        @else
                                            @if($addonsdata->LeastPurchasePrices != '')
                                                @can('addon-least-purchase-price-view')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                                    @endphp
                                                    @if ($hasPermission)
                                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                            Least Purchase Price
                                                        </div>
                                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                        {{$addonsdata->LeastPurchasePrices}} AED
                                                        </div>
                                                    @endif
                                                @endcan
                                            @endif
                                        @endif
                                    @else
                                        @if($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'P')
                                            @if(isset($addonsdata->LeastPurchasePrices->purchase_price_aed))
                                                @can('addon-least-purchase-price-view')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                                    @endphp
                                                    @if ($hasPermission)
                                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                            Least Purchase Price
                                                        </div>
                                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                            {{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED
                                                        </div>
                                                    @endif
                                                @endcan
                                            @endif
                                        @else
                                            @if($addonsdata->LeastPurchasePrices != '')
                                                @can('addon-least-purchase-price-view')
                                                    @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                                    @endphp
                                                    @if ($hasPermission)
                                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                            Least Purchase Price
                                                        </div>
                                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                        {{$addonsdata->LeastPurchasePrices}} AED
                                                        </div>
                                                    @endif
                                                @endcan
                                            @endif
                                        @endif
                                    @endif
                                    @can('addon-selling-price-view')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                                        @endphp
                                        @if ($hasPermission)
                                            @if($addonsdata->SellingPrice!= null OR $addonsdata->PendingSellingPrice!= null)
                                                <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                    Selling Price
                                                </div>
                                                <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
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
                                    @if($addonsdata->addon_type_name == 'P' OR $addonsdata->addon_type_name == 'SP')
                                    @if($addonsdata->fixing_charges_included)
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                            Fixing Charge
                                        </div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                            @if($addonsdata->fixing_charges_included == 'yes')
                                                <label class="badge badge-soft-success">Fixing Charge Included</label>
                                            @else
                                                @if($addonsdata->fixing_charge_amount != '')
                                                    {{$addonsdata->fixing_charge_amount}} AED
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    @endif
                                    @if($addonsdata->lead_time)
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                            Lead Time
                                        </div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                            @if($content == '')
                                                @if($addonsdata->PurchasePrices->lead_time_min != '' OR $addonsdata->PurchasePrices->lead_time_max != '')
                                                    <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                                        Lead Time
                                                    </div>
                                                    <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                                        {{$addonsdata->PurchasePrices->lead_time_min}}
                                                        @if($addonsdata->PurchasePrices->lead_time_max != '' && $addonsdata->PurchasePrices->lead_time_min < $addonsdata->PurchasePrices->lead_time_max)
                                                            - {{$addonsdata->PurchasePrices->lead_time_max}}
                                                        @endif
                                                        Days
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    @if($addonsdata->model_year_start OR $addonsdata->model_year_end)
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                            Model Year
                                        </div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                            {{$addonsdata->model_year_start}}
                                            @if($addonsdata->model_year_end != '' && $addonsdata->model_year_start != $addonsdata->model_year_end) - {{$addonsdata->model_year_end}} @endif
                                        </div>
                                    @endif
                                    @if($addonsdata->additional_remarks)
                                        <div class="labellist labeldesign col-xxl-5 col-lg-6 col-md-6 col-sm-12 col-12">
                                            Additional Remarks
                                        </div>
                                        <div class="labellist databack1 col-xxl-7 col-lg-6 col-md-6 col-sm-12 col-12">
                                            {{$addonsdata->additional_remarks}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-5 col-lg-5 col-md-12 col-sm-12 col-12" style="padding-right:3px; padding-left:3px;">
                                @if($addonsdata->image)
                                    @if (file_exists(public_path().'/addon_image/'.$addonsdata->image))
                                    <img id="myImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}"
                                    alt="Addon Image" style="max-height:159px; max-width:232px;">
                                    @else
                                    <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                    @endif
                                @else
                                    <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                @endif
                            </div>
                            @if($addonsdata->is_all_brands == 'yes')
                                <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6 col-6">
                                    Brand
                                </div>
                                <div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                    All Brands
                                </div>
                            @else
                                @if($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'K')
                                    <div class="labellist labeldesign col-xxl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                                        <center>Brand</center>
                                    </div>
                                    <div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                                        <center>
                                            Model Line
                                        </center>
                                    </div>
                                    <div class="labellist labeldesign col-xxl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                        <center>
                                            Model Description
                                        </center>
                                    </div>
                                @else
                                    <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                        <center>Brand</center>
                                    </div>
                                    <div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                        <center>
                                            Model Line
                                        </center>
                                    </div>
                                @endif
                                <input type="hidden" id="addon-type-count-{{$addonsdata->id}}" value="{{$addonsdata->AddonTypes->count()}}">
                            @foreach($addonsdata->AddonTypes as $key =>$AddonTypes)
                                <div class="divcolorclass" value="5" hidden>
                                </div>
                                @if($addonsdata->addon_type_name == 'SP' OR $addonsdata->addon_type_name == 'K')
                                    <div class="testtransform divcolor labellist databack1 addon-{{$addonsdata->id}}-brand-{{$key}} col-xxl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                                        {{$AddonTypes->brands->brand_name}}
                                    </div>
                                    <div class="testtransform divcolor labellist databack1 addon-{{$addonsdata->id}}-model-line-{{$key}} col-xxl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                                        @if(isset($AddonTypes->modelLines->model_line))
                                            {{$AddonTypes->modelLines->model_line}}
                                        @endif
                                        @if($AddonTypes->is_all_model_lines == 'yes')
                                            All Model Lines
                                        @endif
                                    </div>
                                    <div class="testtransform divcolor labellist databack1 addon-{{$addonsdata->id}}-model-number-{{$key}} col-xxl-5 col-lg-5 col-md-5 col-sm-5 col-5">
                                        {{$AddonTypes->modelDescription->model_description ?? ''}}
                                    </div>
                                @else
                                    <div class="testtransform divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                                        {{$AddonTypes->brands->brand_name}}
                                    </div>
                                    <div class="testtransform divcolor labellist databack1 col-xxl-6 col-lg-6 col-md-6 col-sm-6 col-6">
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
                            @if($addonsdata->is_all_brands == 'no' && $addonsdata->AddonTypes->count() > 3)
                                <div class="row justify-content-center mt-1">
                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                        <button title="View More Model Descriptions" class="btn btn-sm btn-info view-more text-center"
                                            onclick="viewMore({{$addonsdata->id}})"
                                            id="view-more-{{$addonsdata->id}}"  data-key="{{$key}}" >
                                        View More <i class="fa fa-arrow-down"></i>
                                        </button>
                                        <button title="View More Model Descriptions" hidden class="btn btn-sm btn-info view-less text-center"
                                            onclick="viewLess({{$addonsdata->id}})"     id="view-less-{{$addonsdata->id}}" data-key="{{$key}}" >
                                        View Less <i class="fa fa-arrow-up"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        </br>
                        <div class="row" style="position: absolute; bottom: 3px; right: 5px;">
                            <div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
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
<script>
    var addons = [];
    var addonCount = '{{$addon1->count()}}';
    var addonsIds = {!! json_encode($addonIds)  !!};
    $(document).ready(function () {
        hideModelDescription(addonsIds);

    });

    function hideModelDescription(addonsIds)
    {
        for(var i=0;i<=addonCount;i++) {
        var addonTypeCount = $('#addon-type-count-'+addonsIds[i]).val();

        if(addonTypeCount > 3) {
            for(var j=3;j<=addonTypeCount;j++) {
                $('.addon-'+addonsIds[i]+'-brand-'+j).attr('hidden',true);
                $('.addon-'+addonsIds[i]+'-model-line-'+j).attr('hidden',true);
                $('.addon-'+addonsIds[i]+'-model-number-'+j).attr('hidden', true);
            }
        }
    }

    }
    function viewMore(addonId) {

        var addonTypeCount = $('#addon-type-count-'+addonId).val();
        $('#view-more-'+addonId).attr('hidden', true);
        $('#view-less-'+addonId).attr('hidden', false);
        for(var j=3;j<=addonTypeCount;j++) {
            $('.addon-'+addonId+'-brand-'+j).attr('hidden',false);
            $('.addon-'+addonId+'-model-line-'+j).attr('hidden',false);
            $('.addon-'+addonId+'-model-number-'+j).attr('hidden', false);

        }
    }
    function viewLess(addonId) {

        var addonTypeCount = $('#addon-type-count-'+addonId).val();
        $('#view-more-'+addonId).attr('hidden', false);
        $('#view-less-'+addonId).attr('hidden', true);

        for(var j=3;j<=addonTypeCount;j++) {
            $('.addon-'+addonId+'-brand-'+j).attr('hidden',true);
            $('.addon-'+addonId+'-model-line-'+j).attr('hidden',true)
            $('.addon-'+addonId+'-model-number-'+j).attr('hidden', true);

        }
    }
</script>
