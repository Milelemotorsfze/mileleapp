@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
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
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-edit');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<a class="btn btn-sm btn-Success float-end" href="{{ route('approvalsinspection.index') }}" text-align: right>
        <i class="fa fa-check" aria-hidden="true"></i> Vehicle Approvals
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-primary float-end" href="{{ route('vehicle_pictures.pending') }}" text-align: right>
        <i class="fa fa-camera" aria-hidden="true"></i> Vehicles Pictures
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-primary float-end" href="{{ route('incident.index') }}" text-align: right>
        <i class="fa fa-info" aria-hidden="true"></i> Incident Vehicles
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <h4 class="card-title">
     Inspection Info
    </h4>
    <br>
    @can('inspection-edit')
    <ul class="nav nav-pills nav-fill">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab2">Incoming Vehicles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab1">Pending Inspections</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Stock Vehicles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Pending PDI</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Pending Re-Inspection</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>PO Date</th>
                  <th>PO Number</th>
                  <th>GRN Date</th>
                  <th>GRN Number</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th> 
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      <div class="tab-pane fade show  active" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>PO Date</th>
                  <th>PO Number</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th> 
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('inspection-edit')
      <div class="tab-pane fade show" id="tab3">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>Last Inspection Date</th>
                  <th>Last Inspection Remarks</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th> 
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('inspection-edit')
      <div class="tab-pane fade show" id="tab4">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>SO Date</th>
                  <th>So Number</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th> 
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab5">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample5" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Checking Date</th>
                  <th>Manager Remarks</th>
                  <th>SO Date</th>
                  <th>So Number</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Model Year</th>
                  <th>Steering</th>
                  <th>Seats</th>
                  <th>Fuel Type</th>
                  <th>Transmission</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      </div>
    </div>
  </div>
  <script>
        $(document).ready(function () {
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'Pending']) }}",
            columns: [
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('inspection.index', ['status' => 'Incoming']) }}",
            columns: [
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ],
            drawCallback: function(settings) {
        var api = this.api();
        console.log(api.rows().data().toArray());
    }
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'stock']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'processing_date', name: 'inspection.processing_date' },
                { data: 'process_remarks', name: 'inspection.process_remarks' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'Pending PDI']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'inspection_date', name: 'vehicles.inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample5').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'Pending Re Inspection']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'created_ats', name: 'inspection.created_at' },
                { data: 'remark', name: 'inspection.remark' },  
                { data: 'processing_date', name: 'inspection.processing_date' },
                { data: 'process_remarks', name: 'inspection.process_remarks' }, 
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
});
    </script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample1').DataTable();
    $('#dtBasicExample1 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('inspection.show', ['inspection' => 'id']) }}";
      url = url.replace('id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
$(document).ready(function () {
  var table = $('#dtBasicExample3').DataTable();
  $('#dtBasicExample3 tbody').on('dblclick', 'tr', function () {
    var data = table.row(this).data();
    var vehicleId = data.id;
    var url = "{{ route('inspection.instock', ['id' => ':vehicleId']) }}"; // Note the change here
    url = url.replace(':vehicleId', vehicleId); // Replace :vehicleId with the actual vehicleId
    window.location.href = url;
  });
});
</script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample5').DataTable();
    $('#dtBasicExample5 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('reinspection.reshow', ['id' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample4').DataTable();
    $('#dtBasicExample4 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('inspection.pdiinspection', ['id' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 20000);
    }
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection