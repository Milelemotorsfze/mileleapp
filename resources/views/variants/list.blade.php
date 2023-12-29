@extends('layouts.table')
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
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
    <div class="card-header">
        <h4 class="card-title">
            Variants Info
        </h4>
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
        @endphp
        @if ($hasPermission)
        <a  class="btn btn-sm btn-info float-end" href="{{ route('variants.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create Varitants</a>
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a  class="btn btn-sm btn-primary float-end" href="{{ route('model-lines.index') }}" ><i class="fa fa-info-circle" aria-hidden="true"></i> Model Lines</a>
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a  class="btn btn-sm btn-primary float-end" href="{{ route('brands.index') }}" ><i class="fa fa-info-circle" aria-hidden="true"></i> Brands</a>
        @endif
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-error" id="error-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Model Description</th>
                    <th>Model Year</th>
                    <th>Variant</th>
                    <th>Variant Detail</th>
                    <th>Engine Capacity</th>
                    <th>Transmission</th>
                    <th>Fuel Type</th>
                    <th>Steering</th>
                    <th>Seating Capacity</th>
                    <th>Upholstery</th>                    
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
                    @endphp
                    @if ($hasPermission)
                    <th>Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($variants as $key => $variant)
                    <tr data-id="1">
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->brand->brand_name ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->master_model_lines->model_line ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->model_detail ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->my ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->name ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->detail ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->engine ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->gearbox ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->fuel_type ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->steering ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->seat ?? 'null' )) }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->upholestry ?? 'null' )) }}</td>
                        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
                    @endphp
                    @if ($hasPermission)
                    <!-- <td class="nowrap-td">
                                <a data-placement="top" href="{{ route('variants.edit', $variant->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                </a>
                                </td> -->
                                <td class="nowrap-td">
                                <a data-placement="top" href="{{ route('variants.edit', $variant->id) }}" class="btn btn-info btn-sm"><i class="fa fa fa-clone">Duplicate</i>
                                </a>
                                </td>
                            @endif
                            <!-- @can('variants-delete')
                                @if($variant->is_deletable == true)
                                <a data-placement="top" id="{{ $variant->id }}" href="{{ route('variants.destroy',$variant->id) }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </a>
                                @endif
                            @endcan -->
                        
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $('.btn-delete').on('click',function(e){
            e.preventDefault();
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>
    <script type="text/javascript">
$(document).ready(function () {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample3').DataTable({
  pageLength: 20,
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
@endsection



