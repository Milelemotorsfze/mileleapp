@extends('layouts.table')
<style>
  .table-responsive {
  overflow: auto;
  max-height: 650px; /* Adjust the max-height to your desired value */
}
.table-wrapper {
  position: relative;
}
thead th {
  position: sticky;
  top: 0;
  background-color: rgba(116,120,141,.25)!important;
  z-index: 1; /* Ensure the table header is on top of other elements */
}
#table-responsive {
  height: 100vh;
  overflow-y: auto;
}
#dtBasicSupplierInventory {
  width: 100%;
  font-size: 14px;
}
th.nowrap-td {
  white-space: nowrap;
}
.nowrap-td {
    white-space: nowrap;
  }
.select2-container .select2-selection--single {
  height: 34px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 34px;
  right: 6px;
  top: 4px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: #888 transparent transparent transparent;
  border-style: solid;
  border-width: 5px 5px 0 5px;
  height: 0;
  left: 50%;
  margin-left: -4px;
  margin-top: -2px;
  position: absolute;
  top: 50%;
  width: 0;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 34px;
}
.select2-container--default .select2-selection--single .select2-selection__clear {
  line-height: 34px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  background-color: #f8f9fc;
  border-color: #ddd;
  border-radius: 0;
  transition: background-color 0.2s, border-color 0.2s;
}
.select2-container--default .select2-selection--single .select2-selection__arrow:hover {
  background-color: #e9ecef;
  border-color: #bbb;
}
    </style>
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po-details');
                    @endphp
                    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">
            Purchase Orders
        </h4>
      @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-po-details');
                    @endphp
                    @if ($hasPermission)
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('purchasing-order.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Purchasing Order
      </a>
      <div class="clearfix"></div>
      <br>
      @endif
    </div>
    <div class="card-body">
    @if ($errors->has('source_name'))
            <div id="error-message" class="alert alert-danger">
                {{ $errors->first('source_name') }}
            </div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po-details');
                    @endphp
                    @if ($hasPermission)
        <div class="table-responsive" >
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                <th style="vertical-align: middle; text-align: center;">PO Number</th>
                    <th style="vertical-align: middle; text-align: center;">PO Date</th>
                    <th style="vertical-align: middle; text-align: center;">Vendor Name</th>
                    <th style="vertical-align: middle; text-align: center;">Total Vehicles</th>
                    <th class="nowrap-td" id="statuss" style="vertical-align: middle; text-align: center;">Vehicles Status</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $purchasingOrder)
                <tr data-id="{{ $purchasingOrder->id }}" onclick="window.location.href = '{{ route('purchasing-order.show', $purchasingOrder->id) }}'">
                <td style="vertical-align: middle; text-align: center;">{{ $purchasingOrder->po_number }}</td>
                <td style="vertical-align: middle; text-align: center;">{{ date('d-M-Y', strtotime($purchasingOrder->po_date)) }}</td>
                <td style="vertical-align: middle; text-align: center;">
                            @php
                            $resultname = DB::table('vendors')->where('id', $purchasingOrder->vendors_id)->value('trade_name_or_individual_name');
                            @endphp
                            {{ $resultname }}
                        </td>
                        <td style="vertical-align: middle; text-align: center;">
                        @php
                        $vehicleCount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->count();
                        @endphp
                        {{ $vehicleCount }}
                    </td>
                    <td style="text-align: center;">
                        <div style="margin-bottom: 5px;">
  <span class="badge bg-primary">Pending Approval : 10</span>
</div>
<div style="margin-bottom: 5px;">
  <span class="badge bg-secondary">Pending Initiation : 10</span>
</div>
<div style="margin-bottom: 5px;">
  <span class="badge bg-warning text-dark">Payment Initiation : 10</span>
</div>
<div style="margin-bottom: 5px;">
  <span class="badge bg-info text-dark">Payment Released : 05</span>
</div>
<div style="margin-bottom: 5px;">
  <span class="badge bg-success">Payment Debited : 01</span>
</div>
<div style="margin-bottom: 5px;">
  <span class="badge bg-danger">Rejected : 04</span>
</div>
                        </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
        <script>
$(document).ready(function() {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  pageLength: 10,
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (columnName === "statuss") {
        return;
      }

      var selectWrapper = $('<div class="select-wrapper"></div>');
      var select = $('<select class="form-control my-1" multiple><option value="">All</option></select>')
        .appendTo(selectWrapper)
        .select2({
          width: '100%',
          dropdownCssClass: 'select2-blue'
        });
      select.on('change', function() {
        var selectedValues = $(this).val();
        column.search(selectedValues ? selectedValues.join('|') : '', true, false).draw();
      });

      selectWrapper.appendTo($(column.header()));
      $(column.header()).addClass('nowrap-td');
      
      column.data().unique().sort().each(function(d, j) {
        select.append('<option value="' + d + '">' + d + '</option>');
      });
    });
  }
});
  $('.dataTables_filter input').on('keyup', function() {
    dataTable.search(this.value).draw();
  });
});
    </script>
        <script>
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
    <script>
  function confirmCancel() {
    var confirmDialog = confirm("Are you sure you want to cancel this purchase order?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
