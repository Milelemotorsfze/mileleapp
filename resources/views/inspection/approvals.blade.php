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
.custom-badge {
  display: inline-block;
  padding: 2px 2px;
  font-size: 12px;
  font-weight: bold;
  border-radius: 4px;
  margin: 4px 4px 0 0;
  background-color: rgba(0, 123, 255, 0.8);
  color: #fff;
}
.custom-badge.primary {
  background-color: rgba(60, 60, 60, 0.8);
}
.custom-badge.info {
  background-color: rgba(23, 162, 184, 0.8);
}
.custom-badge.danger {
  background-color: rgba(220, 53, 69, 0.8);
}
.custom-badge.success {
  background-color: rgba(40, 167, 69, 0.8);
}
.custom-badge.warning {
  background-color: rgba(255, 193, 7, 0.8);
}
.custom-badge.dark {
  background-color: rgba(0, 0, 255, 0.8);
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
    <h4 class="card-title">
     Inspection Approvals Info
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
    @can('inspection-edit')
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Approvals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Re Inspection Approvals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Repaired Inspection Approval</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Approved</a>
      </li>
    </ul>      
  </div>
  <div class="modal fade works-modal" id="routineModal" tabindex="-1" aria-labelledby="routineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Routine Inspection Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <input type="hidden" id="inspection_id" name="inspection_id" value="">
      <div class="row">
  <div class="col-md-2">
    <p><strong>Model Line:</strong></p>
    <p><span id="modelLine"></span></p>
</div>
    <div class="col-md-2">
    <p><strong>VIN:</strong></p>
    <p><span id="vin"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Interior Color:</strong></p>
    <p><span id="intColour"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Exterior Color:</strong></p>
    <p><span id="extColour"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Location:</strong></p>
    <p><span id="location"></span></p>
  </div>
  <div class="col-md-2">
    <p><strong>Model Year:</strong></p>
    <p><span id="model_year"></span></p>
  </div>
</div>
        <div id="routineInspectionDetails">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Check Items</th>
                <th>Spec</th>
                <th>Condition</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div id = "incidentDataSection">
        <div class="row">
        <div class="col-md-2">
          <p><strong>Reason:</strong> <span id="reason"></span></p>
        </div>
        <div class="col-md-2">
          <p><strong>Driven By:</strong> <span id="drivenBy"></span></p>
          </div>
        <div class="col-md-2">
          <p><strong>Detail:</strong> <span id="detail"></span></p>
          </div>
        <div class="col-md-2">
          <p><strong>Narration:</strong> <span id="narration"></span></p>
          </div>
        <div class="col-md-2">
          <p><strong>Type:</strong> <span id="type"></span></p>
          </div>
          <div class="col-md-2">
          <p><strong>Responsivity:</strong> <span id="responsivity"></span></p>
          </div>
          </div>
        </div>
        <img id="incidentImages" src="" alt="Incident Image" />
      </div>
      <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="approvedroutein()">Approved</button>
            </div>
    </div>
  </div>
</div>
<div class="modal fade pdi-modal" id="pdiModal" tabindex="-1" aria-labelledby="pdiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">PDI Inspection Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div id="buttonContainer" class="d-flex justify-content-end"></div>
      <br>
      <input type="hidden" id="inspection_id" name="inspection_id" value="">
      <div class="row">
  <div class="col-md-2">
    <p><strong>Model Line:</strong></p>
    <p><span id="modelLinepdi"></span></p>
</div>
    <div class="col-md-2">
    <p><strong>VIN:</strong></p>
    <p><span id="vinpdi"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Interior Color:</strong></p>
    <p><span id="intColourpdi"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Exterior Color:</strong></p>
    <p><span id="extColourpdi"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Location:</strong></p>
    <p><span id="locationpdi"></span></p>
  </div>
  <div class="col-md-2">
    <p><strong>Model Year:</strong></p>
    <p><span id="model_yearpdi"></span></p>
  </div>
</div>
        <div id="pdiInspectionDetails">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Check List Items</th>
                <th>Reciving Qty</th>
                <th>Reciving</th>
                <th>Delivery Qty</th>
                <th>Delivery</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="row">
    <div class="col-lg-12">
        <p><strong>Remarks</strong></p>
        <textarea id="pdi_remarks" rows="4" placeholder="Enter your remarks here..." style="width: 100%;"></textarea>
    </div>
</div>
        </div>
      <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="approvedpdi()">Approved</button>
            </div>
    </div>
  </div>
</div>
  <div class="modal fade works-modal" id="works" tabindex="-1" aria-labelledby="worksLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="worksLabel">Incident Inspections</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="incidentId" name="incidentId" value="">
            <div id="modalBody" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="savereincidentupdate()">Approved</button>
            </div>
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
                  <th>Stage</th>
                  <th>QC Remarks</th>
                  <th>Changing Fields</th>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>SO Number</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
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
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Inspection Date</th>
                  <th>Stage</th>
                  <th>QC Remarks</th>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>SO Number</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                  <th>Approval Date</th>
                  <th>Approval Remarks</th>
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
                <th>Re Inspection Date</th>
                  <th>Stage</th>
                  <th>QC Remarks</th>
                  <th>Changing Fields</th>
                  <th>PO Number</th>
                  <th>GRN Number</th>
                  <th>SO Number</th>
                  <th>Location</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
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
      <div class="tab-pane fade show" id="tab4">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Incident Number</th>
                <th>Part Purchase Remarks</th>
                  <th>Parts Purchase Order</th>
                  <th>PO Number</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>Inspection Date</th>
                  <th>Type</th>
                  <th>Narration</th>
                  <th>Reason</th>
                  <th>Driven By</th>
                  <th>Responsivity</th>
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
            ajax: "{{ route('approvalsinspection.index', ['status' => 'Pending']) }}",
            columns: [
                { data: 'created_at_formte', name: 'inspection.created_at' },
                { data: 'stage', name: 'inspection.stage' },
                { data: 'remark', name: 'inspection.remark' },
                {
  data: 'changing_fields',
  name: 'vehicle_detail_approval_requests.field',
  render: function(data) {
    if (data === null || data === undefined) {
    return '';
  }
    var fields = data.split(',');
    var badges = [];
    fields.forEach(function(field) {
      var badgeClass = '';
      var badgeText = '';
      switch (field.trim()) {
        case 'Variant Change':
          badgeClass = 'custom-badge primary';
          badgeText = 'Variant';
          break;
          case 'New Variant':
          badgeClass = 'custom-badge primary';
          badgeText = 'Variant';
          break;
        case 'vin':
          badgeClass = 'custom-badge danger';
          badgeText = 'VIN';
          break;
        case 'int_colour':
          badgeClass = 'custom-badge info';
          badgeText = 'Interior Colour';
          break;
        case 'ex_colour':
          badgeClass = 'custom-badge success';
          badgeText = 'Exterior Colour';
          break;
        case 'engine':
          badgeClass = 'custom-badge warning';
          badgeText = 'Engine';
          break;
          case 'extra_features':
          badgeClass = 'custom-badge dark';
          badgeText = 'Extra Features';
          break;
      }
      var badge = '<span class="' + badgeClass + '">' + badgeText + '</span>';
      badges.push(badge);
    });
    return badges.join(' ');
  }
},
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('approvalsinspection.index', ['status' => 'approved']) }}",
            columns: [
              { data: 'created_at_formte', name: 'inspection.created_at' },
                { data: 'stage', name: 'inspection.stage' },
                { data: 'remark', name: 'inspection.remark' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ],
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('approvalsinspection.index', ['status' => 'Reinspectionapproval']) }}",
            columns: [
              { data: 'reinspection_date', name: 'inspection.reinspection_date' },
                { data: 'stage', name: 'inspection.stage' },
                { data: 'reinspection_remarks', name: 'inspection.reinspection_remarks' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'grn.grn_number' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { data: 'detail', name: 'varaints.detail' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('approvalsinspection.index', ['status' => 'reparingapproval']) }}",
            columns: [
                { data: 'incidentsnumber', name: 'incident.id' },
                { data: 'update_remarks', name: 'incident.update_remarks' },
                { data: 'part_po_number', name: 'incident.part_po_number' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'engine', name: 'vehicles.engine' },
                { data: 'reinspection_date', name: 'incident.reinspection_date' },
                { data: 'type', name: 'incident.type' },
                { data: 'narration', name: 'incident.narration' },
                { data: 'reason', name: 'incident.reason' },
                { data: 'driven_by', name: 'incident.driven_by' },
                { data: 'responsivity', name: 'incident.responsivity' },
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
    @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-approve');
  @endphp
  @if ($hasPermission)  
<script>
  $(document).ready(function () {
    var table1 = $('#dtBasicExample1').DataTable();
    $('#dtBasicExample1 tbody').on('dblclick', 'tr', function () {
      var data = table1.row(this).data();
      var vehicleId = data.id;
      var stage = data.stage;
      $('#inspection_id').val(vehicleId);
      if (stage === 'Routine') {
        $.ajax({
      url: '/routine-inspection/' + vehicleId,
      method: 'GET',
      success: function (response) {
        var additionalInfo = response.additionalInfo;
        var routineInspectionData = response.routineInspectionData;
        var incidentData = response.incidentData;
        var assetUrl = "{{ asset('qc/') }}/";
        if(incidentData){
          $('#incidentDataSection').show();
          $('#incidentImages').show();
        $('#reason').text(incidentData.reason);
        $('#drivenBy').text(incidentData.driven_by);
        $('#detail').text(incidentData.detail);
        $('#narration').text(incidentData.narration);
        $('#type').text(incidentData.type);
        $('#responsivity').text(incidentData.responsivity);
        $('#incidentImages').attr('src', assetUrl + incidentData.file_path);
        console.log(incidentData.file_path);
        }else{
          $('#incidentDataSection').hide();
          $('#incidentImages').hide();
        }
        $('#modelLine').text(additionalInfo.model_line);
        $('#vin').text(additionalInfo.vin);
        $('#intColour').text(additionalInfo.int_colour);
        $('#extColour').text(additionalInfo.ext_colour);
        $('#location').text(additionalInfo.location);
        $('#model_year').text(additionalInfo.my);
        var tableHtml = '<table class="table table-bordered"><thead><tr><th>Check Items</th><th>Spec</th><th>Condition</th><th>Remarks</th></tr></thead><tbody>';
        routineInspectionData.forEach(function (row) {
          tableHtml += `<tr>
  <td class="text-left">${row.check_items !== null ? row.check_items : ''}</td>
  <td>${row.spec !== null ? row.spec : ''}</td>
  <td>${row.condition !== null ? row.condition : ''}</td>
  <td>${row.remarks !== null ? row.remarks : ''}</td>
</tr>`;
        });
        tableHtml += '</tbody></table>';
        $('#routineInspectionDetails').html(tableHtml);
        $('#routineModal').modal('show');
      },
      error: function (error) {
        console.error('Error fetching routine inspection details:', error);
      }
    });
  }  else if (stage === 'PDI') {
    $.ajax({
      url: '/pdi-inspection/' + vehicleId,
      method: 'GET',
      success: function (response) {
        var additionalInfo = response.additionalInfo;
        var PdiInspectionData = response.PdiInspectionData;
        var grnpicturelink = response.grnpicturelink;
        var secgrnpicturelink = response.secgrnpicturelink;
        var PDIpicturelink = response.PDIpicturelink;
        var modificationpicturelink = response.modificationpicturelink;
        var Incidentpicturelink = response.Incidentpicturelink;
        var buttonContainerHtml = '';
      if (grnpicturelink) {
        buttonContainerHtml += `<a class="btn btn-sm btn-primary" href="${grnpicturelink}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> GRN Pictures</a>&nbsp;&nbsp;`;
      }
      if (secgrnpicturelink) {
        buttonContainerHtml += `<a class="btn btn-sm btn-primary" href="${secgrnpicturelink}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> GRN-2 Pictures</a>&nbsp;&nbsp;`;
      }
      if (PDIpicturelink) {
        buttonContainerHtml += `<a class="btn btn-sm btn-primary" href="${PDIpicturelink}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> PDI Pictures</a>&nbsp;&nbsp;`;
      }
      if (modificationpicturelink) {
        buttonContainerHtml += `<a class="btn btn-sm btn-primary" href="${modificationpicturelink}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> Modification Pictures</a>&nbsp;&nbsp;`;
      }
      if (Incidentpicturelink) {
        buttonContainerHtml += `<a class="btn btn-sm btn-primary" href="${Incidentpicturelink}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> Incident Pictures</a>`;
      }
        $('#modelLinepdi').text(additionalInfo.model_line);
        $('#vinpdi').text(additionalInfo.vin);
        $('#intColourpdi').text(additionalInfo.int_colour);
        $('#extColourpdi').text(additionalInfo.ext_colour);
        $('#locationpdi').text(additionalInfo.location);
        $('#model_yearpdi').text(additionalInfo.my);
        var tableHtml = '<table class="table table-bordered"><thead><tr><th>Check List Items</th><th>Receving Qty</th><th>Receving</th><th>Delivery Qty</th><th>Delivery</th><th>Remarks</th></tr></thead><tbody>';
        PdiInspectionData.forEach(function (row) {
          tableHtml += `<tr>
  <td class="text-left">${row.checking_item !== null ? row.checking_item : ''}</td>
  <td>${row.reciving_qty !== null ? row.reciving_qty : ''}</td>
  <td>${row.reciving !== null ? row.reciving : ''}</td>
  <td>${row.qty !== null ? row.qty : ''}</td>
  <td>${row.status !== null ? row.status : ''}</td>
  <td>${row.remarks !== null ? row.remarks : ''}</td>
</tr>`;
        });
        tableHtml += '</tbody></table>';
        $('#pdiInspectionDetails').html(tableHtml);
        $('#buttonContainer').html(buttonContainerHtml);
        $('#pdiModal').modal('show');
      },
      error: function (error) {
        console.error('Error fetching routine inspection details:', error);
      }
    });
  }
  else {
      var url = "{{ route('approvalsinspection.show', ['approvalsinspection' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
  }
    });
    var table3 = $('#dtBasicExample3').DataTable();
    $('#dtBasicExample3 tbody').on('dblclick', 'tr', function () {
      var data = table3.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('approvalsinspection.show', ['approvalsinspection' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
    });
    var table2 = $('#dtBasicExample4').DataTable();

$('#dtBasicExample4 tbody').on('dblclick', 'tr', function () {
    var data = table2.row(this).data();
    var incidentId = data.incidentsnumber;
    var vin = data.vin;
    $('#incidentId').val(incidentId);
    $.ajax({
        type: 'GET',
        url: '/get-incident-works/' + incidentId,
        success: function (response) {
    $('#worksLabel').text('View Pictures Details - VIN: ' + vin);
    $('#modalBody').empty();
    var tableHtml = '<table class="table table-bordered"><thead><tr><th>Works</th><th>Status</th><th>Remarks</th></tr></thead><tbody>';
    for (var i = 0; i < response.length; i++) {
        var work = response[i].works;
        var workStatus = response[i].status;
        var workRemarks = response[i].remarks;
        tableHtml += '<tr><td>' + work + '</td><td>' + workStatus + '</td><td>' + workRemarks + '</td></tr>';
    }
    tableHtml += '</tbody></table>';
    $('#modalBody').append(tableHtml);
    $('#works').modal('show');
},
        error: function (error) {
            console.error(error);
        }
    });
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
    function savereincidentupdate() {
    var incidentId = $('#incidentId').val();
    console.log(incidentId);
    $.ajax({
        type: 'POST',
        url: '{{route('incidentupdate.approvals')}}',
        data: { incidentId: incidentId },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          alertify.success('Approved Repaired Inspection');
            setTimeout(function() {
        window.location.reload();
        }, 1000);
        },
        error: function (error) {
            console.error('Error saving incident update:', error);
        }
    });
}
function approvedroutein() {
    var inspectionid = $('#inspection_id').val();
    $.ajax({
        type: 'POST',
        url: '{{route('inspectionapprovalroten.approvalsrotein')}}',
        data: { inspectionid: inspectionid },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          alertify.success('Approved Repaired Inspection');
            setTimeout(function() {
        window.location.reload();
        }, 1000);
        },
        error: function (error) {
            console.error('Error saving incident update:', error);
        }
    });
}
function approvedpdi() {
    var inspectionid = $('#inspection_id').val();
    var remarks = $('#pdi_remarks').val();
    $.ajax({
        type: 'POST',
        url: '{{route('inspectionapprovalpdi.approvalspdi')}}',
        data: { inspectionid: inspectionid,  remarks: remarks  },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          alertify.success('Approved Repaired Inspection');
            setTimeout(function() {
        window.location.reload();
        }, 1000);
        },
        error: function (error) {
            console.error('Error saving incident update:', error);
        }
    });
}
</script>
@endif
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection