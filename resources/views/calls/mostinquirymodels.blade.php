@extends('layouts.table')
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
  <div class="card-header">
    <h4 class="card-title">
     Calls & Messages Info
    </h4>
    @can('Calls-modified')
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
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks</th>
                  <th>Sales Status</th>
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
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $sales_persons_name }}</td>
                    @php
    $brand_name = '';
    if (!is_null($calls->brand_id)) {
        $brands = DB::table('brands')->where('id', $calls->brand_id)->first();
        if (!is_null($brands)) {
            $brand_name = $brands->brand_name;
        }
    }
@endphp  
<td>{{ $brand_name }}</td>

@php
    $model_line = '';
    if (!is_null($calls->model_line_id)) {
        $model_lines = DB::table('master_model_lines')->where('id', $calls->model_line_id)->first();
        if (!is_null($model_lines)) {
          $model_line = $model_lines->model_line;
        }
    }
@endphp  
<td>{{ $model_line }}</td>
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
    <td>{{ $calls->status }}</td>      
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
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks</th>
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
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $sales_persons_name }}</td>
                    @php
    $brand_name = '';
    if (!is_null($calls->brand_id)) {
        $brands = DB::table('brands')->where('id', $calls->brand_id)->first();
        if (!is_null($brands)) {
            $brand_name = $brands->brand_name;
        }
    }
@endphp  
<td>{{ $brand_name }}</td>

@php
    $model_line = '';
    if (!is_null($calls->model_line_id)) {
        $model_lines = DB::table('master_model_lines')->where('id', $calls->model_line_id)->first();
        if (!is_null($model_lines)) {
          $model_line = $model_lines->model_line;
        }
    }
@endphp  
<td>{{ $model_line }}</td> 
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->source }}</td>
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
      <button class="btn btn-success left" id="export-csv-so">Export CSV</button>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks</th>
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
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $sales_persons_name }}</td>
                    @php
    $brand_name = '';
    if (!is_null($calls->brand_id)) {
        $brands = DB::table('brands')->where('id', $calls->brand_id)->first();
        if (!is_null($brands)) {
            $brand_name = $brands->brand_name;
        }
    }
@endphp  
<td>{{ $brand_name }}</td>

@php
    $model_line = '';
    if (!is_null($calls->model_line_id)) {
        $model_lines = DB::table('master_model_lines')->where('id', $calls->model_line_id)->first();
        if (!is_null($model_lines)) {
          $model_line = $model_lines->model_line;
        }
    }
@endphp  
<td>{{ $model_line }}</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->source }}</td>
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
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks</th>
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
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $sales_persons_name }}</td>
                    @php
    $brand_name = '';
    if (!is_null($calls->brand_id)) {
        $brands = DB::table('brands')->where('id', $calls->brand_id)->first();
        if (!is_null($brands)) {
            $brand_name = $brands->brand_name;
        }
    }
@endphp  
<td>{{ $brand_name }}</td>

@php
    $model_line = '';
    if (!is_null($calls->model_line_id)) {
        $model_lines = DB::table('master_model_lines')->where('id', $calls->model_line_id)->first();
        if (!is_null($model_lines)) {
          $model_line = $model_lines->model_line;
        }
    }
@endphp  
<td>{{ $model_line }}</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    <td>{{ $calls->source }}</td>
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
  var dataTable = $('#dtBasicExample1').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample1 th").eq([d]).text();
          if (d === 14) {
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
          if (d === 9) {
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
    downloadCSV(dataTable, 'Call.csv');
  });
  var dataTable = $('#dtBasicExample2').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample2 th").eq([d]).text();
          if (d === 14) {
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
          if (d === 9) {
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
    downloadCSV(dataTable, 'Call-to-Lead.csv');
  });
  var dataTable = $('#dtBasicExample3').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample3 th").eq([d]).text();
          if (d === 14) {
            return;
          }
          if (d === 15) {
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
          if (d === 9) {
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
    downloadCSV(dataTable, 'Lead-to-So.csv');
  });
  var dataTable = $('#dtBasicExample4').DataTable({
    ordering: false,
    initComplete: function() {
      this.api()
        .columns()
        .every(function(d) {
          var column = this;
          var theadname = $("#dtBasicExample4 th").eq([d]).text();
          if (d === 14) {
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
          if (d === 9) {
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
    downloadCSV(dataTable, 'Lead-to-So.csv');
  });
});
function downloadCSV(dataTable, fileName) {
  var csv = '';
  var rows = dataTable.rows({ 'search': 'applied' }).data();
  var header = dataTable.columns().header();
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
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection