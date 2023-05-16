@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Calls & Messages Info
    </h4>
    @can('Calls-view')
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
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
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
    <td>{{ $calls->status }}</td>      
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
    downloadCSV(dataTable, 'Call.csv');
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
@endsection