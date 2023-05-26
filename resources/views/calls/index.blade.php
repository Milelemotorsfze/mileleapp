@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Calls & Messages Info
    </h4>
    @can('Calls-modified')
    <a class="btn btn-sm btn-success float-end" href="{{ route('calls.createbulk') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Uploading Bulk CSV File
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('calls.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Daily Calls & Messages
      </a>
      <div class="clearfix"></div>
<br>
    @endcan
    @can('Calls-view')
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Pending Calls & Messages to Leads</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Calls & Messages to Leads Converted</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Rejection Inquiry</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Leads Convert to Sales</a>
      </li>
      
    </ul>      
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
      <button class="btn btn-success left" id="export-csv">Export CSV</button>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($data as $key => $calls)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
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
                    $leadsources = "";
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
    <td>
    <div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="{{ route('calls.edit',$calls->id) }}">Edit</a></li>
      <li>
  <a class="dropdown-item delete-link" href="#" data-url="{{ route('calls.destroy', $calls->id) }}">Delete</a>
</li>
    </ul>
  </div>
    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
    @endcan
    @can('Calls-view')
      <div class="tab-pane fade show" id="tab2">
      <button class="btn btn-success left" id="export-csv-lead">Export CSV</button>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Person Remarks</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($convertedleads as $key => $calls)
                <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
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
    <td>{{ $calls->sales_person_remarks }}</td>   
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('Calls-view')
      <div class="tab-pane fade show" id="tab3">
      <button class="btn btn-success left" id="export-rejection">Export CSV</button>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Person Remarks</th>
                  <th>Customer Remarks</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($convertedrejection as $key => $calls)
                <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
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
    <td>{{ $calls->sales_person_remarks }}</td>  
    <td>{{ $calls->client_remarks }}</td>    
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('Calls-view')
      <div class="tab-pane fade show" id="tab4">
      <button class="btn btn-success left" id="export-csv-so">Export CSV</button>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($convertedso as $key => $calls)
                <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
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
  var dataTablea = $('#dtBasicExample1').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample1 th").eq([d]).text();
          if (d === 12) {
            return;
          }
          if (d === 13) {
            return;
          }
          if (d === 0) {
            return;
          }
          if (d === 1) {
            return;
          }
          if (d === 3) {
            return;
          }
          if (d === 4) {
            return;
          }
          if (d === 5) {
            return;
          }
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          column
            .data()
            .unique()
            .sort()
            .each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    }
  });
$('#my-table_filter').hide();
  $('#export-csv').on('click', function() {
    downloadCSVa(dataTablea, 'Call.csv');
  });
  var dataTableb = $('#dtBasicExample2').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample2 th").eq([d]).text();
          if (d === 12) {
            return;
          }
          if (d === 13) {
            return;
          }
          if (d === 0) {
            return;
          }
          if (d === 1) {
            return;
          }
          if (d === 3) {
            return;
          }
          if (d === 4) {
            return;
          }
          if (d === 5) {
            return;
          }
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          column
            .data()
            .unique()
            .sort()
            .each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    }
  });
  $('#export-csv-lead').on('click', function() {
    downloadCSVb(dataTableb, 'Call-to-Lead.csv');
  });
  var dataTablec = $('#dtBasicExample3').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample3 th").eq([d]).text();
          if (d === 12) {
            return;
          }
          if (d === 13) {
            return;
          }
          if (d === 14) {
            return;
          }
          if (d === 0) {
            return;
          }
          if (d === 1) {
            return;
          }
          if (d === 3) {
            return;
          }
          if (d === 4) {
            return;
          }
          if (d === 5) {
            return;
          }
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          column
            .data()
            .unique()
            .sort()
            .each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    }
  });
  $('#export-rejection').on('click', function() {
    downloadCSVc(dataTablec, 'rejection.csv');
  });
  var dataTabled = $('#dtBasicExample4').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample4 th").eq([d]).text();
          if (d === 12) {
            return;
          }
          if (d === 13) {
            return;
          }
          if (d === 0) {
            return;
          }
          if (d === 1) {
            return;
          }
          if (d === 3) {
            return;
          }
          if (d === 4) {
            return;
          }
          if (d === 5) {
            return;
          }
          var select = $('<select class="form-control my-1"><option value="">All</option></select>')
            .appendTo($(column.header()))
            .on('change', function() {
              var val = $.fn.dataTable.util.escapeRegex($(this).val());
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
          column
            .data()
            .unique()
            .sort()
            .each(function(d, j) {
              select.append('<option value="' + d + '">' + d + '</option>');
            });
        });
    }
  });
  $('#export-csv-so').on('click', function() {
    downloadCSVd(dataTabled, 'Lead-to-So.csv');
  });
});
function downloadCSVa(dataTablea, fileName) {
  var csv = '';
  var rows = dataTablea.rows({ 'search': 'applied' }).data();
  var header = dataTablea.columns().header();
  var headerArray = [];
  $(header).each(function() {
    headerArray.push($(this).text());
  });
  csv += headerArray.join(',') + '\r\n';
  $(rows).each(function(index, row) {
    var rowData = [];
    $(row).each(function() {
      rowData.push(this);
    });
    csv += rowData.join(',') + '\r\n';
  });
  var link = document.createElement('a');
  link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
  link.setAttribute('download', fileName);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
function downloadCSVb(dataTableb, fileName) {
  var csv = '';
  var rows = dataTableb.rows({ 'search': 'applied' }).data();
  var header = dataTableb.columns().header();
  var headerArray = [];
  $(header).each(function() {
    headerArray.push($(this).text());
  });
  csv += headerArray.join(',') + '\r\n';
  $(rows).each(function(index, row) {
    var rowData = [];
    $(row).each(function() {
      rowData.push(this);
    });
    csv += rowData.join(',') + '\r\n';
  });
  var link = document.createElement('a');
  link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
  link.setAttribute('download', fileName);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
function downloadCSVc(dataTablec, fileName) {
  var csv = '';
  var rows = dataTablec.rows({ 'search': 'applied' }).data();
  var header = dataTablec.columns().header();
  var headerArray = [];
  $(header).each(function() {
    headerArray.push($(this).text());
  });
  csv += headerArray.join(',') + '\r\n';
  $(rows).each(function(index, row) {
    var rowData = [];
    $(row).each(function() {
      rowData.push(this);
    });
    csv += rowData.join(',') + '\r\n';
  });
  var link = document.createElement('a');
  link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
  link.setAttribute('download', fileName);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
function downloadCSVd(dataTabled, fileName) {
  var csv = '';
  var rows = dataTabled.rows({ 'search': 'applied' }).data();
  var header = dataTabled.columns().header();
  var headerArray = [];
  $(header).each(function() {
    headerArray.push($(this).text());
  });
  csv += headerArray.join(',') + '\r\n';
  $(rows).each(function(index, row) {
    var rowData = [];
    $(row).each(function() {
      rowData.push(this);
    });
    csv += rowData.join(',') + '\r\n';
  });
  var link = document.createElement('a');
  link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv));
  link.setAttribute('download', fileName);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
$(document).ready(function() {
  $('.delete-link').click(function(e) {
    e.preventDefault();

    var url = $(this).data('url');

    if (confirm('Are you sure you want to delete this item?')) {
      $.ajax({
        url: url,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(result) {
          // Handle successful deletion, e.g., show a success message
          console.log('Item deleted successfully.');
          location.reload();
        },
        error: function(xhr) {
          // Handle error response, e.g., show an error message
          console.log('Error deleting item.');
          location.reload();
        }
      });
    } else {
      // If "No" is clicked, redirect back to the previous page
      location.reload();
    }
  });
});
</script>
@endsection