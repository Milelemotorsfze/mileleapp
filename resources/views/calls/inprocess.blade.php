@extends('layouts.table')
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
      z-index: 1; /* Ensure the table header is on top of other elements */
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
      width: 100px;
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
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
     Calls & Messages Info (In Process)
    </h4>
    @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
    <a class="btn btn-sm btn-success float-end" href="{{ route('calls.createbulk') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Upload Excel File
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('calls.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Daily Calls & Messages
      </a>
      <div class="clearfix"></div>
<br>
    @endif
    @can('Calls-view')
      <div class="row">
      <div class="col-lg-1">
      <button class="btn btn-success" id="export-excel" style="margin: 10px;">Export CSV</button>
      </div>
      </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Ser No</th>
                  <th>Date</th>
                  <th>Selling Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Lead Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Person Remarks</th>
                </tr>
              </thead>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      </div>
    </div>
  </div>
  <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function () {
    var dataTable = $('#dtBasicExample1').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("calls.inprocess") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'type', name: 'type' },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'sales_person', name: 'sales_person' },
            { data: 'brands_models', name: 'brands_models' },
            { data: 'custom_brand_model', name: 'custom_brand_model' },
            { data: 'lead_source', name: 'lead_source' },
            { data: 'language', name: 'language' },
            { data: 'location', name: 'location' },
            { data: 'remarks_messages', name: 'remarks_messages' },
            { data: 'sales_person_remarks', name: 'sales_person_remarks' },
        ]
    });

    $('#export-excel').on('click', function() {
        var tableData = dataTable.rows().data().toArray();
        var excelData = [
            ['S.No', 'Date', 'Selling Type', 'Customer Name', 'Customer Phone', 'Customer Email', 'Sales Person', 'Brands & Models', 'Custom Model & Brand', 'Source', 'Preferred Language', 'Destination', 'Remarks & Messages', 'Sales Notes']
        ];

        tableData.forEach(function(rowData) {
            var row = [];
            row.push(rowData.DT_RowIndex);
            row.push(rowData.created_at);
            row.push(rowData.type);
            row.push(rowData.name);
            row.push(rowData.phone);
            row.push(rowData.email);
            row.push(rowData.sales_person);
            row.push(rowData.brands_models);
            row.push(rowData.custom_brand_model);
            row.push(rowData.lead_source);
            row.push(rowData.language);
            row.push(rowData.location);
            row.push(rowData.remarks_messages);
            row.push(rowData.sales_person_remarks);
            excelData.push(row);
        });

        var workbook = XLSX.utils.book_new();
        var worksheet = XLSX.utils.aoa_to_sheet(excelData);
        XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');
        var blob = new Blob([s2ab(XLSX.write(workbook, { bookType: 'xlsx', type: 'binary' }))], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Call.xlsx';
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i !== s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
        return view;
    }
});
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection