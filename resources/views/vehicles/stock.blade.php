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
    .nav-pills .nav-link {
      position: relative;
    }

    .badge-notification {
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(50%, -110%);
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
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
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Incoming
          <span class="badge badge-danger row-badge1 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Pending Inspection
        <span class="badge badge-danger row-badge2 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Available Stock
        <span class="badge badge-danger row-badge3 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Booked
        <span class="badge badge-danger row-badge4 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Sold
        <span class="badge badge-danger row-badge5 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab6">Delivered
        <span class="badge badge-danger row-badge6 badge-notification"></span>
        </a>
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
          var table1 = $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Incoming']) }}",
            columns: [
              { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'estimation_date', name: 'vehicles.estimation_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            buttons: [
        'excelHtml5' // Add the export to Excel button
    ]
        });
        table1.on('draw', function () {
            var rowCount = table1.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge1').text(rowCount).show();
            } else {
                $('.row-badge1').hide();
            }
        });
        var table2 = $('#dtBasicExample2').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Pending Inspection']) }}",
            columns: [
              { data: 'po_number', name: 'purchasing_order.po_number' },
              { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        table2.on('draw', function () {
            var rowCount = table2.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge2').text(rowCount).show();
            } else {
                $('.row-badge2').hide();
            }
        });
        var table3 = $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Available Stock']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        table3.on('draw', function () {
            var rowCount = table3.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge3').text(rowCount).show();
            } else {
                $('.row-badge3').hide();
            }
        });
        var table4 = $('#dtBasicExample4').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Booked']) }}",
            columns: [
              { data: 'po_number', name: 'purchasing_order.po_number' },
              { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'reservation_start_date', name: 'reservation_start_date' },
                { data: 'reservation_end_date', name: 'reservation_end_date' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        table4.on('draw', function () {
            var rowCount = table4.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge4').text(rowCount).show();
            } else {
                $('.row-badge4').hide();
            }
        });
        var table5 = $('#dtBasicExample5').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Sold']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        table5.on('draw', function () {
            var rowCount = table5.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge5').text(rowCount).show();
            } else {
                $('.row-badge5').hide();
            }
        });
        var table6 = $('#dtBasicExample6').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'Delivered']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' },
                { data: 'gdn_number', name: 'gdn.gdn_number' },
                { data: 'gdndate', name: 'gdndate' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        table6.on('draw', function () {
            var rowCount = table6.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge6').text(rowCount).show();
            } else {
                $('.row-badge6').hide();
            }
        });
});
function exportToExcel(tableId) {
    var table = document.getElementById(tableId);
    var rows = table.rows;
    var csvContent = "";
    for (var i = 0; i < rows.length; i++) {
      var row = rows[i];
      for (var j = 0; j < row.cells.length; j++) {
        csvContent += row.cells[j].innerText + ",";
      }
      csvContent += "\n";
    }
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