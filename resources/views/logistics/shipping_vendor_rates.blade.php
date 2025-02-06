@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.badge.badge-success {
    background-color: #28a745;
    color: #fff;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  /* padding: 4px 8px 4px 8px; */
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
          <!-- Add this HTML at the end of your view file -->
          <div class="modal fade ratesmodal-modal" id="ratesmodal" tabindex="-1" aria-labelledby="ratesmodalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ratesmodalLabel">Update Rates</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="choices-single-default" class="form-label">Current Cost Price:</label>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
              <span id="currentCostPrice"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-12 col-sm-12">
                <label class="form-label font-size-13 text-center">New Cost Price</label>
              </div>
              <div class="col-lg-8 col-md-12 col-sm-12">
                <input type="text" class="form-label" name="cost_price" />
              </div>
              <span id="b_error_492" class="error required-class paragraph-class" style="color:#fd625e; font-size:13px;"></span>
            </div>
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="choices-single-default" class="form-label">Current Selling Price:</label>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
              <span id="currentSellingPrice"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-12 col-sm-12">
                <label class="form-label font-size-13 text-center">New Selling Price</label>
              </div>
              <div class="col-lg-8 col-md-12 col-sm-12">
                <input type="text" class="form-label" name="selling_price" />
                <input type="hidden" name="shippingRateId" id="shippingRateId">
              </div>
              <span id="b_error_492" class="error required-class paragraph-class" style="color:#fd625e; font-size:13px;"></span>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn-save">Save</button>
      </div>
    </div>
  </div>
</div>
        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
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
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-open-modal', function() {
        var shippingRateId = $(this).data('id');

        // AJAX request to get the current selling and cost price
        $.ajax({
            url: '/getShippingRateDetails/' + shippingRateId, // Update the route accordingly
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Update the modal content with current prices
                $('#currentCostPrice').text(response.currentCostPrice);
                $('#currentSellingPrice').text(response.currentSellingPrice);
                
                // Set the shippingRateId in the hidden input field
                $('#shippingRateId').val(shippingRateId);

                // Show the modal
                $('#ratesmodal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching shipping rate details:', error);
            }
        });
    });
});
$(document).ready(function() {
    $(document).on('click', '#btn-save', function() {
        var shippingRateId = $('#shippingRateId').val();
        var newCostPrice = $('input[name="cost_price"]').val();
        var newSellingPrice = $('input[name="selling_price"]').val();

        // Check if either cost price or selling price is not null before making the AJAX request
        if ((newCostPrice !== null || newSellingPrice !== null) && parseFloat(newSellingPrice) > parseFloat(newCostPrice)) {
            $.ajax({
                url: '/updateShippingRate',
                type: 'POST',
                data: {
                    id: shippingRateId,
                    cost_price: newCostPrice,
                    selling_price: newSellingPrice,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    $('#ratesmodal').modal('hide');
                    alertify.success('Shipping rate updated successfully');
        setTimeout(function() {
          window.location.reload();
        }, 1000);
                },
                error: function(error) {
                  alertify.error('Error Not Updated');
        setTimeout(function() {
          window.location.reload();
        }, 1000);
                }
            });
        } else {
          alertify.warning('Both cost price and selling price are null or selling price is not higher than cost price');
        }
    });
});
</script>
    @endpush