@include('addon.action.addsellingprice')
@can('addon-view')
    <a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('addon.kitItems',$addonsdata->id) }}">
        <i class="fa fa-eye" aria-hidden="true"></i>
    </a>
@endcan
@can('addon-edit')
    <a title="Edit Addon Details" class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addonsdata->id) }}">
        <i class="fa fa-edit" aria-hidden="true"></i>
    </a>
@endcan
@can('view-addon-selling-price-history')
    <a title="Selling Price History" class="btn btn-sm btn-primary modal-button" href="{{ route('suppliers.sellingPriceHistory',$addonsdata->id) }}">
        <i class="fa fa-history" aria-hidden="true"></i>
    </a>
@endcan
@can('addon-active-inactive')
    @if( $addonsdata->status == 'active')
        <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top"class="btn btn-sm btn-secondary modal-button" 
                data-modal-id="makeInactiveAddon{{$addonsdata->id}}">
            <i class="fa fa-ban" aria-hidden="true"></i>
        </a>                                                        
    @else
        <a data-toggle="popover" data-trigger="hover" title="Make Active" data-placement="top" class="btn btn-sm btn-secondary modal-button"
                data-modal-id="makeActiveAddon{{$addonsdata->id}}">
            <i class="fa fa-check" aria-hidden="true"></i>
        </a>                                                   
    @endif
@endcan