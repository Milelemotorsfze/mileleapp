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
                  <th>Purchase Type</th>
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
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($data as $key => $calls)
                <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-M-Y', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                     @php
                     $sales_persons_name = "";
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $sales_persons_name }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $calls->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    @php
                     $leadsource = DB::table('lead_source')->where('id', $calls->source)->first();
                     $leadsources = $leadsource->source_name;
                     @endphp
                    <td>{{ $leadsources }}</td>
                    <td>{{ $calls->language }}</td>
                    <td>{{ $calls->location }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>
    @php
    $sales_notes = "";
    if ($calls->status == "Prospecting") {
        $result = DB::table('prospectings')->where('calls_id', $calls->id)->first();
        if ($result) {
            $sales_notes = $result->salesnotes;
        }
    } elseif ($calls->status == "New Demand") {
        $result = DB::table('demand')->where('calls_id', $calls->id)->first();
        if ($result) {
            $sales_notes = $result->salesnotes;
        }
    } elseif ($calls->status == "Quoted") {
        $result = DB::table('quotations')->where('calls_id', $calls->id)->first();
        if ($result) {
            $sales_notes = $result->sales_notes;
        }
    } else {
        $result = DB::table('negotiations')->where('calls_id', $calls->id)->first();
        if ($result) {
            $sales_notes = $result->sales_notes;
        }
    }
@endphp

<td>{{ $sales_notes }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      </div>
    </div>
  </div>
  <script type="text/javascript">
$(document).ready(function () {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  pageLength: 10,
  columnDefs: [
    { type: 'date', targets: [1] }
  ],
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
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
$('#my-table_filter').hide();
$('#export-excel').on('click', function() {
    var filteredData = dataTable.rows({ search: 'applied' }).data();
    var data = [];
    filteredData.each(function(rowData) {
        var row = [];
        for (var i = 0; i < rowData.length; i++) {
                row.push(rowData[i]);
        }
        data.push(row);
    });
    var excelData = [
        ['S.No', 'Date', 'Purchase Type', 'Customer Name', 'Customer Phone', 'Customer Email', 'Sales Person', 'Brands & Models', 'Custom Model & Brand', 'Source', 'Preferred Language', 'Destination', 'Remarks & Messages', 'Sales Notes']
    ];
    excelData = excelData.concat(data);
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