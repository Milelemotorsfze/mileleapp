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
                                <th>Lead Time</th>
                                <th>Additional Remarks</th>
                                @if($content == '')
                                    @can('supplier-addon-purchase-price-view')
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                    @endphp
                                    @if ($hasPermission)
                                        <th>Purchase Price</th>
                                    @endif
                                    @endcan
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
                                <th>Part Number</th>
                                <th>Payment Condition</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <div hidden>{{$i=0;}}</div>
                        <tbody id="tBodyAddon">                           
                            @foreach ($addon1 as $key => $addonsdata)
                                @if($addonsdata->is_all_brands == 'yes')
                                    <tr data-id="1" class="{{$addonsdata->id}}_allbrands tr" id="{{$addonsdata->id}}_allbrands">
                                        <td>{{ ++$i }}</td>                                    
                                        <td><img id="myallBrandImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}" alt="Addon Image" 
                                        style="width:100%; height:100px;"></td>
                                        <td>{{$addonsdata->AddonName->name}}</td>
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
                                        <td>{{$addonsdata->lead_time}} Days</td>
                                        <td>{{$addonsdata->additional_remarks}}</td>
                                        @if($content == '')
                                            @can('supplier-addon-purchase-price-view')
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                            @endphp
                                            @if ($hasPermission)
                                                <td>{{$addonsdata->PurchasePrices->purchase_price_aed}} AED</td>
                                            @endif
                                            @endcan
                                        @endif
                                        @if($addonsdata->LeastPurchasePrices!= null)
                                            @if($addonsdata->LeastPurchasePrices->purchase_price_aed != '')
                                                @can('addon-least-purchase-price-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                                @endphp
                                                @if ($hasPermission)
                                                    <td>{{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED</td>
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
                                        <td>
                                            @if($addonsdata->fixing_charges_included == 'yes')
                                                <label class="badge badge-soft-success">Fixing Charge Included</label>
                                            @else
                                                @if($addonsdata->fixing_charge_amount != '')
                                                    {{$addonsdata->fixing_charge_amount}} AED
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{$addonsdata->part_number}}</td>
                                        <td>{{$addonsdata->payment_condition}}</td>
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
                                                tr" id="{{$addonsdata->id}}_{{$AddonTypes->brand_id}}">
                                            <td>{{ ++$i }}</td>    
                                            <td><img id="myallModalImg_{{$addonsdata->id}}" class="image-click-class" src="{{ asset('addon_image/' . $addonsdata->image) }}" alt="Addon Image" 
                                            style="width:100%; height:100px;"></td>
                                            <td>{{$addonsdata->AddonName->name}}</td>
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
                                                    {{$AddonTypes->modelLines->model_line}}
                                                @endif
                                            </td>
                                            <td>{{$AddonTypes->modelDescription->model_description ?? ''}}</td>
                                            <td>{{$addonsdata->lead_time}} Days</td>
                                            <td>{{$addonsdata->additional_remarks}}</td>
                                            @if($content == '')
                                                @can('supplier-addon-purchase-price-view')
                                                @php
                                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-addon-purchase-price-view']);
                                                @endphp
                                                @if ($hasPermission)
                                                    <td>{{$addonsdata->PurchasePrices->purchase_price_aed}} AED</td>
                                                @endif
                                                @endcan
                                            @endif
                                            @can('addon-least-purchase-price-view')
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-least-purchase-price-view']);
                                            @endphp
                                            @if ($hasPermission)
                                            <td>{{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED</td>
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
                                            <td>
                                                @if($addonsdata->fixing_charges_included == 'yes')
                                                    <label class="badge badge-soft-success">Fixing Charge Included</label>
                                                @else
                                                    @if($addonsdata->fixing_charge_amount != '')
                                                        {{$addonsdata->fixing_charge_amount}} AED
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{$addonsdata->part_number}}</td>
                                            <td>{{$addonsdata->payment_condition}}</td>
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