@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1;
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
    }
  </style>
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Shipping Vendors Rates: {{$to_port->name}} - {{$from_port->name}}
     <a  class="btn btn-sm btn-info float-end" href="{{ route('shipping_medium.openmedium', ['id' => $shipping->shipping_medium_id]) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
     <a class="btn btn-sm btn-success float-end" href="{{ route('shipping_rate.shippingrates_create', ['id' => $shipping->id]) }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New
      </a>
    </h4>
    <br>
  </div>

        <div class="card-body">
        {!! $html->table(['class' => 'table table-bordered table-striped table-responsive ']) !!}
        </div>  

      </div>
    </div>
  </div>
@endsection
@push('scripts')
        {!! $html->scripts() !!}
        <script>
    $(document).ready(function () {
        // Assuming you are using jQuery
        $(document).on('click', '.btn-select', function () {
            var shippingRateId = $(this).data('id');
            var shippingId = '{{ $shipping->id }}';
            $.ajax({
                url: '/select-shipping-rate/' + shippingRateId, // Replace with your actual route
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    shipping_id: shippingId,
                },
                success: function (response) {
                    // Handle success response, e.g., update the UI
                    alertify.success('Shipping rate selection updated successfully');
        setTimeout(function() {
          window.location.reload();
        }, 1000);
                },
                error: function (error) {
                    // Handle error, if any
                    console.error(error);
                }
            });
        });
    });
</script>
    @endpush