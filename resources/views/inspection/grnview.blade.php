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
     GRN Info
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Netsuite GRN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Netsuite GRN Info</a>
      </li>
    </ul>      
  </div>
  <!-- Modal HTML -->
<div class="modal fade" id="netsuiteModal" tabindex="-1" role="dialog" aria-labelledby="netsuiteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="netsuiteModalLabel">Enter Netsuite GRN</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="netsuiteForm">
        <div class="modal-body">
          <div class="form-group">
            <label for="grnInput">Netsuite GRN</label>
            <input type="text" class="form-control" id="grnInput" required>
          </div>
          </div>
          <input type="hidden" id="vehicleId">
          <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update GRN</button>
          <button type="button" class="btn btn-secondary" id="addNewGrnBtn">Add New GRN</button>
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
                  <th>Inspection Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>PO Number</th>
                  <th> GRN Date </th>
                  <th>Old Varaint</th>
                  <th>New Varaint</th>
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
                  <th>Inspection Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>PO Number</th>
                  <th> GRN Date </th>
                  <th>Old Varaint</th>
                  <th>New Varaint</th>
                  <th>Netsuite GRN Number</th>
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
            ajax: "{{ route('netsuitegrn.addingnetsuitegrn', ['status' => 'pending']) }}",
            columns: [
                { data: 'inspectiondate', name: 'vehicles.inspection_date' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'variant', name: 'varaints.name'},
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_date', name: 'movements_reference.date' }, 
                { data: 'varaints_old', name: 'vehicle_variant_histories.varaints_old' },
                { data: 'varaints_new', name: 'vehicle_variant_histories.varaints_new' },
                { data: null, render: function (data, type, row) {
                    return '<button class="btn btn-sm btn-success modaladd" data-id="'+row.id+'"><i class="fa fa-plus" aria-hidden="true"></i> GRN</button>';
                }}
            ]
        });

        var table2 = $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('netsuitegrn.addingnetsuitegrn', ['status' => 'approved']) }}",
            columns: [
                { data: 'inspectiondate', name: 'vehicles.inspection_date' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'variant', name: 'varaints.name'},
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_date', name: 'movements_reference.date' }, 
                { data: 'varaints_old', name: 'vehicle_variant_histories.varaints_old' },
                { data: 'varaints_new', name: 'vehicle_variant_histories.varaints_new' },
                { data: 'grn_number', name: 'movement_grns.grn_number' },
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
          
            $('#vehicleId').val(data.id);
            $('#netsuiteModal').modal('show');
        });

        $('#netsuiteForm').on('submit', function(e) {
            e.preventDefault();

            var vehicleId = $('#vehicleId').val();
            var grn = $('#grnInput').val();

            $.ajax({
                url: "{{ route('netsuitegrn.submit') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    vehicle_id: vehicleId,
                    grn: grn
                },
                success: function(response) {
                    $('#netsuiteModal').modal('hide');
                    table1.ajax.reload();
                    table2.ajax.reload();
                },
                error: function(response) {
                    alert(response.responseJSON.message);
                }
            });
        });

        $('#addNewGrnBtn').on('click', function() {
            var vehicleId = $('#vehicleId').val();
            var grn = $('#grnInput').val();

            if (!grn) {
                alert('Please enter a Netsuite GRN to add.');
                return;
            }

            $.ajax({
                url: "{{ route('netsuitegrn.add') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    vehicle_id: vehicleId,
                    grn: grn
                },
                success: function(response) {
                    $('#netsuiteModal').modal('hide');
                    table1.ajax.reload();
                    table2.ajax.reload();
                },
                error: function(response) {
                    alert(response.responseJSON.message || 'An error occurred while adding GRN.');
                }
            });
        });

    });
</script>
@endsection