@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
  <div class="card-header">
    <h4 class="card-title">
     Stock Info
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Incoming</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Pending Inspection</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Available Stock</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Booked</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Sold</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Delivered</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample1')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>Estimated Arrival</th>
                  <th>SO</th>
                  <th>SO Date</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
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
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample2')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>SO</th>
                  <th>SO Date</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab3">
        <div class="card-body">
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample3')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab4">
        <div class="card-body">
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample4')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Reservation Start</th>
                  <th>Reservation End</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
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
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample5')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample5" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>SO Date</th>
                  <th>So Number</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab6">
        <div class="card-body">
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample6')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample6" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>SO Date</th>
                  <th>SO Number</th>
                  <th>Sales Person</th>
                  <th>GDN</th>
                  <th>GDN Date</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
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
         $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Incoming']) }}",
            columns: [
              { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'po_date' },
                { data: 'estimation_date', name: 'vehicles.estimation_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'variant' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'location' }
            ],
            buttons: [
        'excelHtml5' // Add the export to Excel button
    ]
        });
        $('#dtBasicExample2').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Pending Inspection']) }}",
            columns: [
              { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'variant' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'location' }
            ]
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Available Stock']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'variant' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'location' }
            ]
        });
        $('#dtBasicExample4').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Booked']) }}",
            columns: [
              { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'reservation_start_date', name: 'reservation_start_date' },
                { data: 'reservation_end_date', name: 'reservation_end_date' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'variant' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'location' }
            ]
        });
        $('#dtBasicExample5').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Sold']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'so_date', name: 'so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'variant' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'location' }
            ]
        });
        $('#dtBasicExample6').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Delivered']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'date' },
                { data: 'so_date', name: 'so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' },
                { data: 'gdn_number', name: 'gdn.gdn_number' },
                { data: 'gdndate', name: 'gdndate' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'variant' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'location' }
            ]
        });
});
function exportToExcel(tableId) {
    // Get table element by id
    var table = document.getElementById(tableId);
    var rows = table.rows;
    var csvContent = "";

    // Loop through table rows
    for (var i = 0; i < rows.length; i++) {
      var row = rows[i];
      for (var j = 0; j < row.cells.length; j++) {
        // Add cell content to CSV string
        csvContent += row.cells[j].innerText + ",";
      }
      // Add new line after each row
      csvContent += "\n";
    }

    // Create a blob object and download it as a CSV file
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
      navigator.msSaveBlob(blob, 'export.csv');
    } else {
      var link = document.createElement("a");
      if (link.download !== undefined) {
        var url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", "export.csv");
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    }
  }
</script>
@endsection