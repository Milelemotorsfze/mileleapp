@extends('layouts.table')
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
}
.capitalize-first-letter {
        text-transform: capitalize;
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
        <div class="modal fade" id="variantview" tabindex="-1" aria-labelledby="variantviewLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="variantviewLabel">View Variants</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
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
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->brand->brand_name ?? 'null' }}</td>
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->master_model_lines->model_line ?? 'null' }}</td>
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->model_detail ?? 'null' }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->my ?? 'null' )) }}</td>
                        <td class="nowrap-td capitalize-first-letter">
    <a href="#" onclick="openModal('{{ $variant->id ?? '' }}')" style="text-decoration: underline;">
        {{ $variant->name ?? 'null' }}
    </a>
</td>
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->detail ?? 'null' }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->engine ?? 'null' )) }}</td>
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->gearbox ?? 'null' }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->fuel_type ?? 'null' )) }}</td>
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->steering ?? 'null' }}</td>
                        <td class="nowrap-td capitalize-first-letter">{{ $variant->seat ?? 'null' }}</td>
                        <td class="nowrap-td">{{ ucfirst(strtolower($variant->upholestry ?? 'null' )) }}</td>
                        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('variant-edit');
                    @endphp
                    @if ($hasPermission)
                    <!-- <td class="nowrap-td">
                                <a data-placement="top" href="{{ route('variants.edit', $variant->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                </a>
                                </td> -->
                                @if($variant->category != "Modified")
                                <td class="nowrap-td">
                                <a data-placement="top" href="{{ route('variants.variantsaddons', $variant->id) }}" class="btn btn-primary btn-sm"><i class="fa fa fa-plus">Add Addons</i>
                                </a>
                                <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
                                <a data-placement="top" href="{{ route('variants.edit', $variant->id) }}" class="btn btn-info btn-sm"><i class="fa fa fa-clone">Duplicate</i>
                                </a>
                                </td>
                                @else
                                <td class="nowrap-td">
                                </td>
                                @endif
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
<script>
function openModal(id) {
    $.ajax({
        url: '/variants_details/' + id,
        type: 'GET',
        success: function(response) {
            // Assuming response contains data returned from the server
            // Update the modal with the received data
            $('#variantview .modal-body').empty(); // Clear previous content
            var modalBody = $('#variantview .modal-body');
            
            // Create a table to display variant details
            var variantDetailsTable = $('<table class="table table-bordered"></table>');
            var variantDetailsBody = $('<tbody></tbody>');
            variantDetailsBody.append('<tr><th>Attribute</th><th>Options</th><th>Modification Options</th></tr>');
            variantDetailsBody.append('<tr><th>Name</th><td>' + response.variants.name + '</td></tr>');
            variantDetailsBody.append('<tr><th>Steering</th><td>' + response.variants.steering + '</td><td></td></tr>');
            variantDetailsBody.append('<tr><th>Engine</th><td>' + response.variants.engine + '</td><td></td></tr>');
            variantDetailsBody.append('<tr><th>Production Year</th><td>' + response.variants.my + '</td><td></td></tr>');
            variantDetailsBody.append('<tr><th>Fuel Type</th><td>' + response.variants.fuel_type + '</td><td></td></tr>');
            variantDetailsBody.append('<tr><th>Gear</th><td>' + response.variants.gearbox + '</td><td></td></tr>');
            variantDetailsBody.append('<tr><th>Drive Train</th><td>' + response.variants.drive_train + '</td><td></tr>');
            variantDetailsBody.append('<tr><th>Upholstery</th><td>' + response.variants.upholestry + '</td><td></td></tr>');
            variantDetailsTable.append(variantDetailsBody);
            modalBody.append('<h5>Variant Details:</h5>');
            modalBody.append(variantDetailsTable);
            
            // Create a table to display variant items
              modalBody.append('<h5>Attributes Items:</h5>');
              var variantItemsTable = $('<table class="table table-bordered"></table>');
              if (response.modifiedVariants) {
              var variantItemsHeader = $('<thead><tr><th>Attributes</th><th>Options</th><th>Modification Option</th></tr></thead>');
              }
              else{
                var variantItemsHeader = $('<thead><tr><th>Attributes</th><th>Options</th></tr></thead>');
              }
              var variantItemsBody = $('<tbody></tbody>');
              console.log(response.variantItems);
              response.variantItems.forEach(function(variantItem) {
                  var specificationName = variantItem.model_specification ? variantItem.model_specification.name : 'N/A';
                  var optionName = variantItem.model_specification_option ? variantItem.model_specification_option.name : 'N/A';
                  var modificationOption = '';
                  // Check if modifiedVariantItems match model_specification
                  if (response.modifiedVariants) {
                      response.modifiedVariants.forEach(function(modifiedVariant) {
                          if (modifiedVariant.modified_variant_items && modifiedVariant.modified_variant_items.name === specificationName) {
                              modificationOption = modifiedVariant.addon ? modifiedVariant.addon.name : '';
                          }
                      });
                      variantItemsBody.append('<tr><td>' + specificationName + '</td><td>' + optionName + '</td><td>' + modificationOption + '</td></tr>');
                  }
                  else{
                    variantItemsBody.append('<tr><td>' + specificationName + '</td><td>' + optionName + '</td></tr>');
                  }
              });
              variantItemsTable.append(variantItemsHeader);
              variantItemsTable.append(variantItemsBody);
              modalBody.append(variantItemsTable);
            
            // Create a table to display modified variant items and addons
            if (response.modifiedVariants) {
                modalBody.append('<h5>Modified Attributes Items:</h5>');
                var modifiedVariantTable = $('<table class="table table-bordered"></table>');
                var modifiedVariantHeader = $('<thead><tr><th>Modified Attributes</th><th>Modified Option</th></tr></thead>');
                var modifiedVariantBody = $('<tbody></tbody>');
                response.modifiedVariants.forEach(function(modifiedVariant) {
                  console.log(modifiedVariant);
                    var modifiedVariantName = modifiedVariant.modified_variant_items ? modifiedVariant.modified_variant_items.name : 'N/A';
                    var addonName = modifiedVariant.addon ? modifiedVariant.addon.name : 'N/A';
                    modifiedVariantBody.append('<tr><td>' + modifiedVariantName + '</td><td>' + addonName + '</td></tr>');
                });
                modifiedVariantTable.append(modifiedVariantHeader);
                modifiedVariantTable.append(modifiedVariantBody);
                modalBody.append(modifiedVariantTable);
            }
            
            $('#variantview').modal('show');
        },
        error: function(xhr, status, error) {
            // Handle error
        }
    });
}
</script>
@endsection



