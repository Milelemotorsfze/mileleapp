@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.file-icon {
    margin-right: 10px;
}
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
  #dtBasicExample1 tbody tr:hover {
    cursor: pointer;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
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
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Vehicle Delivery Invoice Information
    </h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('vehicleinvoice.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Vehicle Invoice
      </a>
    <br>
  </div>
  <div class="card-body">
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
            
                <th>Invoice Number</th>
                <th>Date</th>
                <th>Client Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>POL</th>
                <th>POD</th>
                <th>Sub Total</th>
                <th>Discount</th>
                <th>Net Amount</th>
                <th>VAT</th>
                <th>Shipping Charges</th>
                <th>Gross Amount</th>
                <th>Invoice</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
$(document).ready(function () {
    $('#dtBasicExample1').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('vehicleinvoice.index') }}",
        columns: [
            { data: 'invoice_number', name: 'vehicle_invoice.invoice_number' },
            { 
                data: 'date', 
                name: 'vehicle_invoice.date',
                render: function (data, type, row) {
                    if (data) {
                        var date = new Date(data);
                        var options = { year: 'numeric', month: 'short', day: 'numeric' };
                        return date.toLocaleDateString('en-GB', options);
                    }
                    return data;
                }
            },
            { data: 'name', name: 'clients.name' },
            { data: 'phone', name: 'clients.phone' },
            { data: 'email', name: 'clients.email' },
            { data: 'pol_name', name: 'pol_name' },
            { data: 'pod_name', name: 'pod_name' },
            { data: 'sub_total', name: 'vehicle_invoice.sub_total' },
            { data: 'discount', name: 'vehicle_invoice.discount' },
            { data: 'net_amount', name: 'vehicle_invoice.net_amount' },
            { data: 'vat', name: 'vehicle_invoice.vat' },
            { data: 'shipping_charges', name: 'vehicle_invoice.shipping_charges' },
            { data: 'gross_amount', name: 'vehicle_invoice.gross_amount' },
            { 
            data: 'id', 
            name: 'id',
            render: function(data, type, row) {
                if (row.id) {
                    return `<button class="btn btn-info btn-sm" onclick="generatePDF(${data})">PDF</button>`;
                } else {
                    return 'Not Available';
                }
            }
        },
        ],
    });
});
function generatePDF(vehicle_invoiceid) {
    var url = `/viewinvoicereport/method?vehicle_invoiceid=${vehicle_invoiceid}`;
    window.open(url, '_blank');
}
</script>
@endsection