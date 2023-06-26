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
            <div class="card-body">
                <div class="table-responsive" id="addonListTable" hidden>     
                    <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Addon Name</th>
                                <th>Addon Type</th>
                                <th>Addon Code</th>
                                <th>Brand</th>
                                <th>Model Line</th>
                                <!-- <th>Model Description</th> -->
                                <th>Lead Time</th>
                                <th>Additional Remarks</th>
                                @if($content == '')
                                    @can('supplier-addon-purchase-price-view')
                                        <th>Purchase Price</th>
                                    @endcan
                                @endif
                                @can('addon-least-purchase-price-view')
                                    <th>Least Purchase Price</th>
                                @endcan
                                @can('addon-selling-price-view')
                                    <th>Selling Price(AED)</th>
                                @endcan
                                <th>Fixing Charge</th>
                                <th>Part Number</th>
                                <th>Payment Condition</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div hidden>{{$i=0;}}</div>
                            @foreach ($addon1 as $key => $addonsdata)
                                @if($addonsdata->is_all_brands == 'yes')
                                    <tr data-id="1" class="{{ $addonsdata->addon_details_table_id }} tr">
                                        <td>{{ ++$i }}</td>
                                        <td><img src="{{ asset('addon_image/' . $addonsdata->image) }}" style="width:100%; height:100px;" /></td>
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
                                        <td>{{$addonsdata->lead_time}} Days</td>
                                        <td>{{$addonsdata->additional_remarks}}</td>
                                        @if($content == '')
                                            @can('supplier-addon-purchase-price-view')
                                                <td>{{$addonsdata->PurchasePrices->purchase_price_aed}} AED</td>
                                            @endcan
                                        @endif
                                        @can('addon-least-purchase-price-view')
                                            <td>{{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED</td>
                                        @endcan
                                        @can('addon-selling-price-view')
                                            <td>
                                                @if($addonsdata->SellingPrice == '' && $addonsdata->PendingSellingPrice == '')
                                                    <label class="badge badge-soft-info">Not Created</label>          
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
                                        @include('addon.action.action')
                                            
                                        </td>
                                    </tr>
                                @else
                                    @foreach($addonsdata->AddonTypes as $AddonTypes)
                                        <tr data-id="1" class="{{ $addonsdata->addon_details_table_id }} tr">
                                            <td>{{ ++$i }}</td>                      
                                            <td><img src="{{ asset('addon_image/' . $addonsdata->image) }}" style="width:100%; height:100px;" /></td>
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
                                            <td>{{$addonsdata->lead_time}} Days</td>
                                            <td>{{$addonsdata->additional_remarks}}</td>
                                            @if($content == '')
                                                @can('supplier-addon-purchase-price-view')
                                                    <td>{{$addonsdata->PurchasePrices->purchase_price_aed}} AED</td>
                                                @endcan
                                            @endif
                                            @can('addon-least-purchase-price-view')
                                            <td>{{$addonsdata->LeastPurchasePrices->purchase_price_aed}} AED</td>
                                            @endcan
                                            @can('addon-selling-price-view')
                                                <td>
                                                    @if($addonsdata->SellingPrice == '' && $addonsdata->PendingSellingPrice == '')    
                                                        <label class="badge badge-soft-info">Not Created</label>          
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
        @endcanany
    @endif
@endif