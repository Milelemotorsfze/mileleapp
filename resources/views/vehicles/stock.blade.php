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
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Vehicle Pictures</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner" id="carouselImages">
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="noImageModal" tabindex="-1" role="dialog" aria-labelledby="noImageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noImageModalLabel">No Images Available</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        No images are available on the website for this vehicle.
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
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
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab7">Full Stock
          <span class="badge badge-danger row-badge7 badge-notification"></span>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>Estimated Arrival</th>
                  <th>SO</th>
                  <th>SO Date</th>
                  <th>Sales Person</th>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>SO</th>
                  <th>SO Date</th>
                  <th>Sales Person</th>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>GRN Report</th>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>Reservation Start</th>
                  <th>Reservation End</th>
                  <th>Sales Person</th>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>SO Date</th>
                  <th>So Number</th>
                  <th>Sales Person</th>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>SO Date</th>
                  <th>SO Number</th>
                  <th>Sales Person</th>
                  <th>GDN</th>
                  <th>GDN Date</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div>
      <div class="tab-pane fade show" id="tab7">
        <div class="card-body">
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample7')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
          <div class="table-responsive">
            <table id="dtBasicExample7" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>Status</th>
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
                  <th>PO</th>
                  <th>PO Date</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>SO Date</th>
                  <th>SO Number</th>
                  <th>Sales Person</th>
                  <th>GDN</th>
                  <th>GDN Date</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div>
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
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                }
            },
            { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
              { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'estimation_date', name: 'vehicles.estimation_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'name', name: 'users.name' }
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
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                }
            },
            { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
              { data: 'po_number', name: 'purchasing_order.po_number' },
              { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'name', name: 'users.name' },
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
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                }
            },
            { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
               
                { 
            data: 'id', 
            name: 'id',
            render: function(data, type, row) {
                return `<button class="btn btn-info" onclick="generatePDF(${data})">Generate PDF</button>`;
            }
        }
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
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                    }
                },
                { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'reservation_start_date', name: 'reservation_start_date' },
                { data: 'reservation_end_date', name: 'reservation_end_date' },
                { data: 'name', name: 'users.name' },
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
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                }
            },
            { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'inspection_date', name: 'inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' }
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
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                }
            },
            { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' },
                { data: 'gdn_number', name: 'gdn.gdn_number' },
                { data: 'gdndate', name: 'gdn.date' }
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
        var now = new Date();
        var table7 = $('#dtBasicExample7').DataTable({
          processing: true,
            serverSide: true,
            ajax: "{{ route('vehicles.statuswise', ['status' => 'allstock']) }}",
            columns: [
              { data: 'id', name: 'vehicles.id' },
              { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { 
                data: 'variant', 
                name: 'varaints.name',
                render: function(data, type, row) {
                    return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
                }
            },
            { data: 'vin', name: 'vehicles.vin', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'date', name: 'grn.date' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'name', name: 'users.name' },
                { data: 'gdn_number', name: 'gdn.gdn_number' },
                { data: 'gdndate', name: 'gdn.date' }, 
            ],
            columnDefs: [
        {
            targets: 0,
            render: function (data, type, row) {
              console.log(row);
                if (row.inspection_id == null && row.inspection_date == null && row.gdn_id == null && row.grn_id == null) {
                    return 'Incoming';
                } else if (row.inspection_id == null && row.inspection_date == null && row.gdn_id == null && row.grn_id != null) {
                    return 'Pending Inspection';
                } else if (row.inspection_date != null && row.gdn_id == null && row.so_id == null && row.grn_id != null && (row.reservation_end_date == null || new Date(row.reservation_end_date) < now)) {
                    return 'Available Stock';
                } else if (row.inspection_date != null && row.gdn_id == null && row.so_id == null && new Date(row.reservation_end_date) <= now && row.grn_id != null) {
                    return 'Booked';
                } else if (row.inspection_date != null && row.gdn_id == null && row.so_id != null && row.grn_id != null) {
                    return 'Sold';
                } else if (row.inspection_date != null && row.gdn_id != null && row.grn_id != null) {
                    return 'Delivered';
                } else {
                    return '';
                }
            }
        }
    ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        table7.on('draw', function () {
            var rowCount = table7.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge7').text(rowCount).show();
            } else {
                $('.row-badge7').hide();
            }
        });
        function handleModalShow(modalId) {
    $(modalId).on('show.bs.modal', function () {
        var scrollTop = $(window).scrollTop();
        $('body').css({
            position: 'fixed',
            top: -scrollTop + 'px',
            width: '100%'
        }).data('scrollTop', scrollTop);
    }).on('hidden.bs.modal', function () {
        var scrollTop = $('body').data('scrollTop');
        $('body').css({
            position: '',
            top: '',
            width: ''
        });
        $(window).scrollTop(scrollTop);
    });
}
handleModalShow('#imageModal');
handleModalShow('#noImageModal');
handleModalShow('#variantview'); // Already existing modal
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
  function generatePDF(vehicleId) {
    var url = `/viewgrnreport/method?vehicle_id=${vehicleId}`;
    window.open(url, '_blank');
}
function openModal(id) {
    $.ajax({
        url: '/variants_details/' + id,
        type: 'GET',
        success: function(response) {
            $('#variantview .modal-body').empty();
            var modalBody = $('#variantview .modal-body');
            var variantDetailsTable = $('<table class="table table-bordered"></table>');
            var variantDetailsBody = $('<tbody></tbody>');
            if (response.modifiedVariants) {
            variantDetailsBody.append('<tr><th>Attribute</th><th>Options</th><th>Modified Option</th></tr>');
            if(response.variants.name != response.basevaraint.name)
            {
              variantDetailsBody.append('<tr><th>Name</th><td>' + response.basevaraint.name + '</td><td>' + response.variants.name + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Name</th><td>' + response.variants.name + '</td></tr>');
            }
            if(response.basevaraint.steering != response.variants.steering)
            {
            variantDetailsBody.append('<tr><th>Steering</th></td><td>'+ response.basevaraint.steering +'<td>' + response.variants.steering + '</td></tr>');
            }
            else {
              variantDetailsBody.append('<tr><th>Steering</th></td><td>'+ response.basevaraint.steering +'<td></td></tr>');
            }
            if(response.basevaraint.engine != response.variants.engine)
            {
            variantDetailsBody.append('<tr><th>Engine</th></td><td>'+ response.basevaraint.engine +'<td>' + response.variants.engine + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Engine</th></td><td>'+ response.basevaraint.engine +'<td></td></tr>');
            }
            if(response.basevaraint.my != response.variants.my)
            {
            variantDetailsBody.append('<tr><th>Production Year</th></td><td>'+ response.basevaraint.my +'<td>' + response.variants.my + '</td></tr>');
            }
            else 
            {
            variantDetailsBody.append('<tr><th>Production Year</th></td><td>'+ response.basevaraint.my +'<td></td></tr>');
            }
            if(response.basevaraint.fuel_type != response.variants.fuel_type)
            {
            variantDetailsBody.append('<tr><th>Fuel Type</th></td><td>'+ response.basevaraint.fuel_type +'<td>' + response.variants.fuel_type + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Fuel Type</th></td><td>'+ response.basevaraint.fuel_type +'<td></td></tr>');
            }
            if(response.basevaraint.gearbox != response.variants.gearbox)
            {
            variantDetailsBody.append('<tr><th>Gear</th></td><td>'+ response.basevaraint.gearbox +'<td>' + response.variants.gearbox + '</td></tr>');
            }
            else 
            {
              variantDetailsBody.append('<tr><th>Gear</th></td><td>'+ response.basevaraint.gearbox +'<td></td></tr>');
            }
            if(response.basevaraint.drive_train != response.variants.drive_train)
            {
            variantDetailsBody.append('<tr><th>Drive Train</th></td><td>'+ response.basevaraint.drive_train +'<td>' + response.variants.drive_train + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Drive Train</th></td><td>'+ response.basevaraint.drive_train +'<td></td></tr>');
            }
            if(response.basevaraint.upholestry != response.variants.upholestry)
            {
            variantDetailsBody.append('<tr><th>Upholstery</th></td><td>'+ response.basevaraint.upholestry +'<td>' + response.variants.upholestry + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Upholstery</th></td><td>'+ response.basevaraint.upholestry +'<td></td></tr>'); 
            }
            }
            else 
            {
            variantDetailsBody.append('<tr><th>Attribute</th><th>Options</th></tr>');
            variantDetailsBody.append('<tr><th>Name</th><td>' + response.variants.name + '</td></tr>');
            variantDetailsBody.append('<tr><th>Steering</th><td>' + response.variants.steering + '</td></tr>');
            variantDetailsBody.append('<tr><th>Engine</th><td>' + response.variants.engine + '</td></tr>');
            variantDetailsBody.append('<tr><th>Production Year</th><td>' + response.variants.my + '</td></tr>');
            variantDetailsBody.append('<tr><th>Fuel Type</th><td>' + response.variants.fuel_type + '</td></tr>');
            variantDetailsBody.append('<tr><th>Gear</th><td>' + response.variants.gearbox + '</td></tr>');
            variantDetailsBody.append('<tr><th>Drive Train</th><td>' + response.variants.drive_train + '</td></tr>');
            variantDetailsBody.append('<tr><th>Upholstery</th><td>' + response.variants.upholestry + '</td></tr>');
            }
            variantDetailsTable.append(variantDetailsBody);
            modalBody.append('<h5>Variant Details:</h5>');
            modalBody.append(variantDetailsTable);
              modalBody.append('<h5>Attributes Items:</h5>');
              var variantItemsTable = $('<table class="table table-bordered"></table>');
              if (response.modifiedVariants) {
              var variantItemsHeader = $('<thead><tr><th>Attributes</th><th>Options</th><th>Modified Option</th></tr></thead>');
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
        }
    });
}
function fetchVehicleData(vehicleId) {
    $.ajax({
        url: "{{ route('fetchData') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            vehicle_id: vehicleId
        },
        success: function(response) {
            if (response.gallery) {
                displayGallery(response.gallery);
                $('#imageModal').modal('show');
            } else {
                alert('No post found');
            }
            console.log(response);
        },
        error: function(xhr) {
            if (xhr.status === 404) {
                showNoImagePopup();
            } else {
                console.error(xhr);
            }
        }
    });
}

function showNoImagePopup() {
    $('#noImageModal').modal('show');
}

function displayGallery(imageUrls) {
    var carouselImages = document.getElementById("carouselImages");
    carouselImages.innerHTML = "";
    imageUrls.forEach(function(url, index) {
        var div = document.createElement("div");
        div.className = "carousel-item" + (index === 0 ? " active" : "");
        var img = document.createElement("img");
        img.className = "d-block w-100";
        img.src = url;
        div.appendChild(img);
        carouselImages.appendChild(div);
    });
}
</script>
@endsection