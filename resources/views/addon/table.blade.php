<style>
    .modal-content
    {
        position:fixed;
        top: 50%;
        left: 50%;
        width:30em;
        height:18em;
        margin-top: -9em; /*set to a negative number 1/2 of your height*/
        margin-left: -15em; /*set to a negative number 1/2 of your width*/
        border: 2px solid #e3e4f1;
        background-color: white;
    }
    .modal-title
    {
        margin-top: 10px;
        margin-bottom: 5px;
    }
    .modal-paragraph
    {
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: center;
    }
    .modal-button-class
    {
        margin-top: 20px;
        margin-left: 20px;
        margin-right: 20px;
    }
    .icon-right
    {
        z-index: 10;
        position: absolute;
        right: 0;
        top: 0;
    }
</style>
@if($addon1)
    @if(count($addon1) > 0)
        @canany(['accessories-list', 'spare-parts-list', 'kit-list'])
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['accessories-list','spare-parts-list','kit-list']);
        @endphp
        @if ($hasPermission)
            <div class="card-body">
                <div class="table-responsive" id="addonListTable" hidden>
                    <table id="addonListDataTable" class="table table-striped table-editable table-edits table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Addon Name</th>
                                <th>Addon Type</th>
                                <th>Addon Code</th>
                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Model Description</th>
                                @if($content == '')
                                <th>Lead Time</th>
                                @endif
                                <th>Model Year</th>
                                @if($content != '')
                                <th>Additional Remarks</th>
                                @endif
                                @if($content == '')
                                    @can('supplier-addon-purchase-price-view')
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                    @endphp
                                    @if ($hasPermission)
                                        <th>Purchase Price</th>
                                    @endif
                                    @endcan
                                    <th>Quotation Date</th>
                                @endif
                                @can('addon-least-purchase-price-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                @endphp
                                @if ($hasPermission)
                                    <th>Least Purchase Price</th>
                                @endif
                                @endcan
                                @can('addon-selling-price-view')
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                                @endphp
                                @if ($hasPermission)
                                    <th>Selling Price(AED)</th>
                                @endif
                                @endcan
                                <th>Fixing Charge</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <div hidden>{{$i=0;}}</div>
                        <tbody id="tBodyAddon">
                            @foreach ($addon1 as $key => $addonsdata)
                                @if($addonsdata->is_all_brands == 'yes')
                                    <tr data-id="1" class="{{$addonsdata->id}}_allbrands tr each-addon-table-row" id="{{$addonsdata->id}}_allbrands">
                                        <td>{{ ++$i }}</td>
                                        <td>
                               @if($addonsdata->image)
                                     <img id="myallBrandImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}"
                                                 alt="Addon Image" style="width:100%; height:100px;">
                                @else
                                <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="width:100%; height:125px;" alt="Addon Image"  />
                                    @endif

                                        </td>
                                        <td>{{$addonsdata->AddonName->name}} 
                                        @if(isset($addonsdata->AddonDescription))
                                        @if($addonsdata->AddonDescription->description != '')- {{$addonsdata->AddonDescription->description}}@endif
                                        @endif
                                        </td>
                                        <td>
                                            @if($addonsdata->addon_type_name == 'K')
                                                <label class="badge badge-soft-success">Kit</label>
                                            @elseif($addonsdata->addon_type_name == 'P')
                                                <label class="badge badge-soft-primary">Accessories</label>
                                            @elseif($addonsdata->addon_type_name == 'SP')
                                                <label class="badge badge-soft-warning">Spare Parts</label>
                                            @endif
                                        </td>
                                        <td>{{$addonsdata->addon_code}}</td>
                                        <td>All Brands</td>
                                        <td>All Model Lines</td>
                                        <td></td>
                                        @if($content == '')
                                        <td> @if(isset($addonsdata->least_purchase_price->lead_time_min) || isset($addonsdata->least_purchase_price->lead_time_max))
                                            {{$addonsdata->least_purchase_price->lead_time_min}}
                                            @if($addonsdata->least_purchase_price->lead_time_max != ''
                                            && $addonsdata->least_purchase_price->lead_time_min < $addonsdata->least_purchase_price->lead_time_max)
                                            - {{$addonsdata->least_purchase_price->lead_time_max}} @endif
                                            @if($addonsdata->least_purchase_price->lead_time_min != '' OR $addonsdata->least_purchase_price->lead_time_max != '')
                                            Days
                                            @endif
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            {{$addonsdata->model_year_start}}
                                            @if($addonsdata->model_year_end != '' && $addonsdata->model_year_start != $addonsdata->model_year_end) - {{$addonsdata->model_year_end}} @endif
                                        </td>
                                        @if($content != '')
                                        <td>{{$addonsdata->additional_remarks}}</td>
                                         @endif
                                        
                                        @if($content == '')
                                            @can('supplier-addon-purchase-price-view')
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                            @endphp
                                            @if ($hasPermission)
                                                <td>{{$addonsdata->PurchasePrices->purchase_price_aed}} AED</td>
                                            @endif
                                            @endcan
                                            <td>{{$addonsdata->PurchasePrices->updated_at}}</td>
                                        @endif
                                        @if($addonsdata->least_purchase_price!= null)
                                            @if($addonsdata->least_purchase_price->purchase_price_aed != '')
                                                @can('addon-least-purchase-price-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                                @endphp
                                                @if ($hasPermission)
                                                    <td>{{$addonsdata->least_purchase_price->purchase_price_aed}} AED</td>
                                                @endif
                                                @endcan
                                            @endif
                                        @endif
                                        @can('addon-selling-price-view')
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                                        @endphp
                                        @if ($hasPermission)
                                            <td>
                                                @if($addonsdata->SellingPrice == '' && $addonsdata->PendingSellingPrice == '')
                                                    <label class="badge badge-soft-info">Not Added</label>
                                                @elseif($addonsdata->SellingPrice!= null OR $addonsdata->PendingSellingPrice!= null)
                                                    @if($addonsdata->SellingPrice!= null)
                                                        @if($addonsdata->SellingPrice->selling_price != '')
                                                            {{$addonsdata->SellingPrice->selling_price}} AED
                                                        @endif
                                                    @elseif($addonsdata->PendingSellingPrice!= null)
                                                        @if($addonsdata->PendingSellingPrice->selling_price != '')
                                                            {{$addonsdata->PendingSellingPrice->selling_price}} AED
                                                            <label class="badge badge-soft-danger">Approval Awaiting</label>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                        @endcan
                                        @if($addonsdata->fixing_charges_included)
                                            <td>
                                                @if($addonsdata->fixing_charges_included == 'yes')
                                                    <label class="badge badge-soft-success">Fixing Charge Included</label>
                                                @else
                                                    @if($addonsdata->fixing_charge_amount != '')
                                                        {{$addonsdata->fixing_charge_amount}} AED
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                        <td>
                                        @include('addon.action.tableAddSellingPrice')
                                        @include('addon.action.action')

                                        </td>
                                    </tr>
                                @else

                                    @foreach($addonsdata->AddonTypes as $AddonTypes)

                                        <tr data-id="1" class="
                                            @if($AddonTypes->is_all_model_lines == 'yes')
                                                {{$addonsdata->id}}_{{$AddonTypes->brand_id}}_all_model_lines
                                            @else
                                                {{$addonsdata->id}}_{{$AddonTypes->brand_id}}_{{$AddonTypes->model_id}}
                                            @endif
                                                tr each-addon-table-row" id="{{$addonsdata->id}}_{{$AddonTypes->brand_id}}">
                                            <td>{{ ++$i }}</td>
                                            <td>
                                            @if($addonsdata->image)
                                     <img id="myallModalImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}"
                                                    alt="Addon Image" style="width:100%; height:100px;">
                                @else<img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="width:100%; height:125px;" alt="Addon Image"  />
                                    @endif

                                            </td>
                                            <td>
                                                {{$addonsdata->AddonName->name}}
                                                @if(isset($addonsdata->AddonDescription))
                                                @if($addonsdata->AddonDescription->description != '')- {{$addonsdata->AddonDescription->description}}@endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($addonsdata->addon_type_name == 'K')
                                                    <label class="badge badge-soft-success">Kit</label>
                                                @elseif($addonsdata->addon_type_name == 'P')
                                                    <label class="badge badge-soft-primary">Accessories</label>
                                                @elseif($addonsdata->addon_type_name == 'SP')
                                                    <label class="badge badge-soft-warning">Spare Parts</label>
                                                @endif
                                            </td>
                                            <td>{{$addonsdata->addon_code}}</td>
                                            <td> {{$AddonTypes->brands->brand_name}}</td>
                                            <td>
                                                @if($AddonTypes->is_all_model_lines == 'yes')
                                                    All Model Lines
                                                @else
                                                    {{$AddonTypes->modelLines->model_line ?? ''}}
                                                @endif
                                            </td>
                                            <td>{{$AddonTypes->modelDescription->model_description ?? ''}}</td>
                                            @if($content == '')
                                            <td>
                                                @if(isset($addonsdata->least_purchase_price->lead_time_min) || isset($addonsdata->least_purchase_price->lead_time_max))
                                                    @if($addonsdata->least_purchase_price->lead_time_min != '' OR $addonsdata->least_purchase_price->lead_time_max != '')
                                                    {{$addonsdata->least_purchase_price->lead_time_min}}
                                                    @if($addonsdata->least_purchase_price->lead_time_max != ''
                                                    && $addonsdata->least_purchase_price->lead_time_min < $addonsdata->least_purchase_price->lead_time_max)
                                                    - {{$addonsdata->least_purchase_price->lead_time_max}} @endif
                                                    Days
                                                    @endif
                                                @endif
                                            </td>
                                            @endif
                                            <td>{{$addonsdata->model_year_start}}
                                                @if($addonsdata->model_year_end != '' && $addonsdata->model_year_start != $addonsdata->model_year_end) - {{$addonsdata->model_year_end}} @endif</td>
                                                @if($content != '')
                                                <td>{{$addonsdata->additional_remarks}}</td>
                                                @endif
                                                
{{--                                            //////// countinue from here scroll in table view --}}
                                            @if($content == '')
                                                @can('supplier-addon-purchase-price-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                                @endphp
                                                @if ($hasPermission)
                                                    <td>{{$addonsdata->PurchasePrices->purchase_price_aed}} AED</td>
                                                @endif
                                                @endcan
                                                <td>{{$addonsdata->PurchasePrices->updated_at}}</td>
                                            @endif
                                            @can('addon-least-purchase-price-view')
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                            @endphp

                                            @if ($hasPermission)

{{--                                                @can('addon-least-purchase-price-view')--}}
{{--                                                @php--}}
{{--                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);--}}
{{--                                                @endphp--}}
{{--                                                @if ($hasPermission)--}}
                                                    <td>
                                                        @if($addonsdata->least_purchase_price!= null)
                                                            @if($addonsdata->least_purchase_price->purchase_price_aed != '')

                                                                {{$addonsdata->least_purchase_price->purchase_price_aed}} AED
                                                            @endif
                                                        @endif
                                                    </td>
{{--                                                @endif--}}
{{--                                                @endcan--}}
{{--                                            @endif--}}
{{--                                            @endif--}}
                                            @endif
                                            @endcan
                                            @can('addon-selling-price-view')
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
                                            @endphp
                                            @if ($hasPermission)
                                                <td>
                                                    @if($addonsdata->SellingPrice == '' && $addonsdata->PendingSellingPrice == '')
                                                        <label class="badge badge-soft-info">Not Added</label>
                                                    @elseif($addonsdata->SellingPrice!= null)
                                                        @if($addonsdata->SellingPrice->selling_price != '')
                                                            {{$addonsdata->SellingPrice->selling_price}} AED
                                                        @endif
                                                    @elseif($addonsdata->PendingSellingPrice!= null)
                                                        @if($addonsdata->PendingSellingPrice->selling_price != '')
                                                            {{$addonsdata->PendingSellingPrice->selling_price}} AED
                                                            <label class="badge badge-soft-danger">Approval Awaiting</label>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endif
                                            @endcan
                                            @if($addonsdata->fixing_charges_included)
                                                <td>
                                                    @if($addonsdata->fixing_charges_included == 'yes')
                                                        <label class="badge badge-soft-success">Fixing Charge Included</label>
                                                    @else
                                                        @if($addonsdata->fixing_charge_amount != '')
                                                            {{$addonsdata->fixing_charge_amount}} AED
                                                        @endif
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{$addonsdata->part_number}}</td>
                                            <td>
                                            @include('addon.action.modelAddonSellingPrice')
                                            @include('addon.action.action')
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        @endcanany
    @endif
@endif
