@extends('layouts.table')
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
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
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['Calls-modified', 'approve-reservation']);
@endphp
  @if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
    Lead Distribution
    </h4>
  </div>
  <div class="tab-content">
        <div class="card-body">
        <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Date</th>
                  <th>Sales Person</th>
                  <th>Leads</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $key => $data)
                  <tr data-id="1">
                    <td>{{ date('d-M-Y', strtotime($data->call_date)) }}</td>
                    <td>{{ $data->sales_person_name }}</td>
                    <td>{{ $data->call_count }}</td>     
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      </div>
  </div>
  <script type="text/javascript">
$(document).ready(function () {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (d === 12 || d === 13) {
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
});
</script>
  @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection