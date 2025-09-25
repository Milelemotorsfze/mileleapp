@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .red-star {
    color: red;
    font-size: 2.2em;
}
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
  #dtBasicExample1 tbody tr:hover {
    cursor: pointer;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  /* padding: 4px 8px 4px 8px; */
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
@php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('netsuite-gdn-number-adding');
                    @endphp
                    @if ($hasPermission)
    <h4 class="card-title">
     GDN Info
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <div class="alert alert-info" role="alert">
        <i class="fa fa-info-circle"></i>
        <strong>Note:</strong> Only vehicles with completed PDI are shown here. To add GDN, please confirm or complete the PDI first.
    </div>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Netsuite GDN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Netsuite GDN Info</a>
      </li>
    </ul>      
  </div>
  <!-- Modal HTML -->
<div class="modal fade" id="netsuiteModal" tabindex="-1" role="dialog" aria-labelledby="netsuiteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="netsuiteModalLabel">Enter Netsuite GDN</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="netsuiteForm">
        <div class="modal-body">
          <div class="form-group pt-3">
            <label for="grnInput">Netsuite GDN</label>
            <input type="text" class="form-control" id="gdnInput" required>
          </div>
          </div>
          <input type="hidden" id="vehicleId">
          <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
    </div>
  </div>
</div>
<div class="modal fade" id="modalupdateModal" tabindex="-1" role="dialog" aria-labelledby="modalupdateModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalupdateModalLabel">Enter Netsuite GDN</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="modalupdateForm">
        <div class="modal-body">
          <div class="form-group">
            <label for="actionSelect">Action</label>
            <select class="form-control" id="actionSelect" required>
              <option value="update">Update</option>
              <option value="add">Add New</option>
            </select>
          </div>
          <div class="form-group pt-3">
            <label for="grnInputupdate">Netsuite GDN</label>
            <input type="text" class="form-control" id="gdnInputupdate" required>
          </div>
        </div>
        <input type="hidden" id="vehicleIdupdate">
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Movement Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>SO Number</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Movement Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>SO Number</th>
                  <th>Netsuite GDN Number</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function () {
        var table1 = $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('netsuitegdn.addingnetsuitegdn', ['status' => 'pending']) }}",
            columns: [
                { data: 'gdndate', name: 'gdn.date' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'variant', name: 'varaints.name'},
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'so_number', name: 'so.so_number' },
                { data: null, render: function (data, type, row) {
                    return '<button class="btn btn-sm btn-success modaladd" data-id="'+row.id+'"><i class="fa fa-plus" aria-hidden="true"></i> GDN</button>';
                }}
            ]
        });

        var table2 = $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('netsuitegdn.addingnetsuitegdn', ['status' => 'approved']) }}",
            columns: [
                { data: 'gdndateauto', name: 'gdn.date' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'variant', name: 'varaints.name'},
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'gdn_number', name: 'gdn.gdn_number' },
                { data: null, render: function (data, type, row) {
                    return '<button class="btn btn-sm btn-info modalupdate" data-id="'+row.id+'"><i class="fa fa-edit" aria-hidden="true"></i> Update</button>';
                }}
            ]
        });

        $('#dtBasicExample1 tbody').on('click', '.modaladd', function() {
            var data = table1.row($(this).parents('tr')).data();
            $('#vehicleId').val(data.id);
            $('#netsuiteModal').modal('show');
        });

        $('#dtBasicExample2 tbody').on('click', '.modalupdate', function() {
            var data = table2.row($(this).parents('tr')).data();
            $('#vehicleIdupdate').val(data.id);
            $('#modalupdateModal').modal('show');
        });

        $('#netsuiteForm').on('submit', function(e) {
            e.preventDefault();

            var vehicleId = $('#vehicleId').val();
            var gdn = $('#gdnInput').val();
            $.ajax({
                url: "{{ route('netsuitegdn.submit') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    vehicle_id: vehicleId,
                    gdn: gdn
                },
                success: function(response) {
                    $('#netsuiteModal').modal('hide');
                    alertify.success('GDN assigned successfully');
                    table1.ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var response = JSON.parse(xhr.responseText);
                        alertify.error(response.message || 'Validation error occurred');
                    } else {
                        alertify.error('An error occurred. Please try again.');
                    }
                }
            });
        });

        $('#modalupdateForm').on('submit', function(e) {
            e.preventDefault();

            var vehicleId = $('#vehicleIdupdate').val();
            var gdn = $('#gdnInputupdate').val();
            var action = $('#actionSelect').val();
            var url = action === 'update' ? "{{ route('netsuitegdn.submit') }}" : "{{ route('netsuitegdn.add') }}";
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    vehicle_id: vehicleId,
                    gdn: gdn
                },
                success: function(response) {
                    $('#modalupdateModal').modal('hide');
                    alertify.success('GDN updated successfully');
                    table2.ajax.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var response = JSON.parse(xhr.responseText);
                        alertify.error(response.message || 'Validation error occurred');
                    } else {
                        alertify.error('An error occurred. Please try again.');
                    }
                }
            });
        });
    });
</script>
@endif
@endsection