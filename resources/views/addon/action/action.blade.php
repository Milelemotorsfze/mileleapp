@can('addon-view')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-view']);
    @endphp
    @if ($hasPermission)
        @if($addonsdata->addon_type_name == 'K')
            <a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('kit.kitItems',$addonsdata->id) }}">
                    <i class="fa fa-eye" aria-hidden="true"></i>
            </a>
        @else
            <a title="View Addon Details" class="btn btn-sm btn-warning" href="{{ route('addon.kitItems',$addonsdata->id) }}">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>
        @endif
    @endif
@endcan
@can('addon-edit')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-edit']);
    @endphp
        @if ($hasPermission)
            @if($addonsdata->addon_type_name == 'K')
                <a title="Edit Addon Details" class="btn btn-sm btn-info" href="{{ route('kit.editDetails',$addonsdata->id) }}">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                </a>

            @else
                <a title="Edit Addon Details" class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addonsdata->id) }}">
                    <i class="fa fa-edit" aria-hidden="true"></i>
                </a>
       @endif
    @endif
@endcan

@can('view-addon-selling-price-history')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-addon-selling-price-history']);
@endphp
@if ($hasPermission)
    <a title="Selling Price History" class="btn btn-sm btn-primary modal-button" href="{{ route('suppliers.sellingPriceHistory',$addonsdata->id) }}">
        <i class="fa fa-history" aria-hidden="true"></i>
    </a>
@endif
@endcan
@can('addon-active-inactive')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-active-inactive']);
@endphp
@if ($hasPermission)
    @if( $addonsdata->status == 'active')
        <button title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary status-inactive-button" data-id="{{ $addonsdata->id }}"
                data-status="inactive" >
            <i class="fa fa-ban" aria-hidden="true"></i>
        </button>
    @else
        <a data-id="{{ $addonsdata->id }}" data-status="active" title="Make Active" data-placement="top" class="btn btn-sm btn-secondary status-active-button" >
            <i class="fa fa-check" aria-hidden="true"></i>
        </a>
    @endif
@endif
@endcan
@can('addon-delete')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-delete']);
@endphp
@if ($hasPermission)
    <button title="Delete" type="button" class="btn btn-danger btn-sm addon-delete sm-mt-3" data-id="{{ $addonsdata->id }}" data-url="{{ route('addon.destroy', $addonsdata->id) }}">
        <i class="fa fa-trash"></i>
    </button>
@endif
@endcan
<script>
     $('.status-active-button').click(function (e) {
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })
        $('.status-inactive-button').click(function (e) {
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })

        function statusChange(id,status) {
            let url = '{{ route('addon.status-change') }}';
            if(status == 'active') {
                var message = 'Active';
            }else{
                var message = 'Inactive';
            }
            var confirm = alertify.confirm('Are you sure you want to '+ message +' this addon ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            window.location.reload();
                            alertify.success(status + " Successfully");
                        }
                    });
                }
            }).set({title:"Status Change"})
        }

    $('.addon-delete').on('click',function(){
        let id = $(this).attr('data-id');
        let url =  $(this).attr('data-url');
        var confirm = alertify.confirm('Are you sure you want to Delete this Addon ?',function (e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        _method: 'DELETE',
                        id: 'id',
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        location.reload();
                        alertify.success('Addon Deleted successfully.');
                    }
                });
            }
        }).set({title:"Delete Addon"})
    });
</script>
