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
  /* padding: 4px 8px 4px 8px; */
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      /* position: sticky!important; */
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
     <a class="btn btn-sm btn-primary float-end" href="{{ route('netsuitegrn.addingnetsuitegrn') }}" text-align: right>
    <i class="fa fa-arrow-right" aria-hidden="true"></i> Netsuite GRN
</a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
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
  <div class="modal fade works-modal" id="routineModal" tabindex="-1" aria-labelledby="routineModalLabel" aria-hidden="true" data-inspectionid="">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                <th>Condition</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-approve');
                @endphp
                @if ($hasPermission) 
        <div id = "incidentDataSection">
        <div class="row">
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
          <div class="col-md-12">
          <p><strong>Reason:</strong> <span id="reason"></span></p>
        </div>
          </div>
        </div>
        <img id="incidentImages" src="" alt="Incident Image" />
      </div>
@else
        <div id ="incidentContainer">
</div>
</div>
@endif
      <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-approve');
                @endphp
                @if ($hasPermission) 
                <button type="button" class="btn btn-success" onclick="approvedroutein()">Approved</button>
                @else
                <button type="button" class="btn btn-success" id="updateButton">Update</button>
                @endif
            </div>
    </div>
  </div>
</div>
<div class="modal fade pdi-modal" id="pdiModal" tabindex="-1" aria-labelledby="pdiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">PDI Inspection Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div id="buttonContainer" class="d-flex justify-content-end"></div>
      <br>
      <input type="hidden" id="inspection_id" name="inspection_id" value="">
      <input type="hidden" id="inspection_idpdi" name="inspection_idpdi" value="">
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
            <thead class="">
              <tr>
                <th>Check List Items</th>
                <th>Reciving</th>
                <th>Delivery</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="row">
    <div class="col-lg-12">
        <p><strong>PDI Remarks</strong></p>
       <div id="pdiremarks"></div>
    </div>
</div>
<br>
<div id ="incidentContainer">
</div>
<div class="row">
    <div class="col-lg-12">
        <p><strong>Manager Remarks</strong></p>
        <textarea id="mangerremarks" rows="4" placeholder="Enter your remarks here..." style="width: 100%;"></textarea>
    </div>
</div>
        </div>
      <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-approve');
                @endphp
                @if ($hasPermission) 
                <button type="button" class="btn btn-success" onclick="approvedpdi()">Approved PDI</button>
                <div id="incidentapp">
                </div>
                @else
                <button type="button" class="btn btn-success" id="updateButtonpdi">Update</button>
                @endif
            </div>
    </div>
  </div>
</div>
<div class="modal fade pdi-modal" id="incidentModal" tabindex="-1" aria-labelledby="incidentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Incident Inspection Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div id="buttonContainer" class="d-flex justify-content-end"></div>
      <br>
      <input type="hidden" id="inspection_id" name="inspection_id" value="">
      <div class="row">
  <div class="col-md-2">
    <p><strong>Model Line:</strong></p>
    <p><span id="modelLineincident"></span></p>
</div>
    <div class="col-md-2">
    <p><strong>VIN:</strong></p>
    <p><span id="vinincident"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Interior Color:</strong></p>
    <p><span id="intColourincident"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Exterior Color:</strong></p>
    <p><span id="extColourincident"></span></p>
    </div>
    <div class="col-md-2">
    <p><strong>Location:</strong></p>
    <p><span id="locationincident"></span></p>
  </div>
  <div class="col-md-2">
    <p><strong>Model Year:</strong></p>
    <p><span id="model_yearincident"></span></p>
  </div>
</div>
<br>
<div id ="incidentContainerincident">
</div>
<div class="row">
    <div class="col-lg-12">
        <p><strong>Manager Remarks</strong></p>
        <textarea id="mangerremarks" rows="4" placeholder="Enter your remarks here..." style="width: 100%;"></textarea>
    </div>
</div>
        </div>
      <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-approve');
                @endphp
                @if ($hasPermission) 
                <div id="incidentappincident">
                </div>
                @else
                <button type="button" class="btn btn-success" id="updateButtonpdi">Update</button>
                @endif
            </div>
    </div>
  </div>
</div>
  <div class="modal fade works-modal" id="works" tabindex="-1" aria-labelledby="worksLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="worksLabel">Incident Inspections</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <input type="hidden" id="incidentId" name="incidentId" value="">
            <div id="modalBody" class="modal-body">
            </div>
            <!-- <label for="remarks">Remarks:</label>
    <input type="text" id="remarks" name="remarks"> -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="reworkButton" style="float: right; margin-right: 10px;" type="button" class="btn btn-warning">Re Work</button>
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
                  <th>Inspection Person</th>
                  <th>Stage</th>
                  <th>QC Remarks</th>
                  <th>PO Number</th>
                  <!-- <th>GRN Number</th> -->
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
                <th>Inspection Person</th>
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
                <th>Inspection Person</th>
                  <th>Stage</th>
                  <th>QC Remarks</th>
                  <th>PO Number</th>
                  <!-- <th>GRN Number</th> -->
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
                  <th>Inspection Person</th>
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
      <div class="modal fade" id="variantDetailModal" tabindex="-1" aria-labelledby="variantDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="variantDetailModalLabel">Full Detail</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="variantDetailModalBody" style="white-space: pre-wrap;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  function showFullText(button) {
        var fullText = button.getAttribute('data-fulltext');
        alert(fullText);
    }
        $(document).ready(function () {
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('approvalsinspection.index', ['status' => 'Pending']) }}",
            columns: [
                { data: 'created_at_formte', name: 'inspection.created_at' },
                { data: 'created_by_name', name: 'users.name' },
                { data: 'stage', name: 'inspection.stage' },
                {
                  data: 'remark',
                  name: 'inspection.remark',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="QC Remarks" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                // { data: 'grn_number', name: 'movement_grns.grn_number' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                {
                  data: 'detail',
                  name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Variant Detail" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },
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
              { data: 'created_by_name', name: 'users.name' },
              { data: 'stage', name: 'inspection.stage' },
              {
                  data: 'remark',
                  name: 'inspection.remark',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="QC Remarks" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },                
                { data: 'po_number', name: 'purchasing_order.po_number' },
                {
                    data: 'grn_number',
                    name: 'movement_grns.grn_number',
                    render: function(data, type, row) {
                        if (row.inspection_status == 'Approved') {
                          
                            return data;
                        }
                        return ''; 
                    }
                },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                {
                  data: 'detail',
                  name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Variant Detail" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },                
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                { data: 'approval_date', name: 'inspection.approval_date' },
                { data: 'approval_remarks', name: 'inspection.approval_remarks' },
            ],
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('approvalsinspection.index', ['status' => 'Reinspectionapproval']) }}",
            columns: [
              { data: 'reinspection_date', name: 'inspection.reinspection_date' },
              { data: 'created_by_name', name: 'users.name' },
                { data: 'stage', name: 'inspection.stage' },
                {
                  data: 'reinspection_remarks',
                  name: 'inspection.reinspection_remarks',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="QC Remarks" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                // { data: 'grn_number', name: 'movement_grns.grn_number' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                {
                  data: 'detail',
                  name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Variant Detail" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },
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
                { data: 'created_by_name', name: 'users.name' },
                { data: 'reinspection_date', name: 'incident.reinspection_date' },
                { data: 'type', name: 'incident.type' },
                { data: 'narration', name: 'incident.narration' },
                { data: 'reason', name: 'incident.reason' },
                { data: 'driven_by', name: 'incident.driven_by' },
                { data: 'responsivity', name: 'incident.responsivity' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                {
                  data: 'detail',
                  name: 'varaints.detail',
                  render: function (data, type, row) {
                    if (type === 'display' && data) {
                      let words = data.split(/\s+/);
                      if (words.length > 5) {
                        let shortText = words.slice(0, 5).join(' ') + '...';
                        return `${shortText} <a href="#" class="read-more-link" data-title="Variant Detail" data-detail="${encodeURIComponent(data)}">Read More</a>`;
                      } else {
                        return data;
                      }
                    }
                    return '';
                  }
                },
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
        var inspectionid = response.inspection;
        var assetUrl = "{{ asset('qc/') }}/";
        if(incidentData){
          $('#incidentDataSection').show();
          $('#incidentImages').show();
          var reasons = JSON.parse(incidentData.reason);
var reasonsHTML = '<div class="col-md-12"><div class="row"><div class="col-md-2"><label class="form-label"><strong>Reason</strong></label></div><div class="col-md-3">';

var columnCount = 3;
var reasonsPerColumn = Math.ceil(reasons.length / columnCount);

for (var i = 0; i < columnCount; i++) {
  reasonsHTML += '<ul class="list-group">';
  for (var j = i * reasonsPerColumn; j < (i + 1) * reasonsPerColumn && j < reasons.length; j++) {
    var reason = reasons[j];
    var reasonName = reason.replace('_', ' ').replace(/\b\w/g, function (l) {
      return l.toUpperCase();
    });
    reasonsHTML += '<li class="list-group-item">' + reasonName + '</li>';
  }
  reasonsHTML += '</ul></div><div class="col-md-3">';
}
reasonsHTML += '</div></div>';
$('#reason').html(reasonsHTML);
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
        var tableHtml = '<table class="table table-bordered"><thead><tr><th>Check Items</th><th>Condition</th><th>Remarks</th></tr></thead><tbody>';
        routineInspectionData.forEach(function (row) {
          tableHtml += `<tr>
  <td class="text-left">${row.check_items !== null ? row.check_items : ''}</td>
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
  }
  else if (stage === 'Incident')
  {
    $.ajax({
      url: '/incident-inspection/' + vehicleId,
      method: 'GET',
      success: function (response) { 
        var additionalInfo = response.additionalInfo;
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
      if (response.incidentDetails) {
  var incidentHtml = `
    <h5>Incident Report</h5>
    <div class="row">
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Incident Type</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.type ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Narration Of Accident / Damage</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.narration ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Damage Details</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.detail ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Driven By</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.driven_by ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Responsibility for Recover the Damages</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.responsivity ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Reason</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.reason ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            ${response.incidentDetails.file_path ? 
              `<img src="{{ asset('qc/') }}/${response.incidentDetails.file_path}" alt="Incident Image">` : 
              'No image available'}
          </div>
        </div>
      </div>
    <br>`;
  $('#incidentContainerincident').html(incidentHtml);
  var approvedPdiButton = '<button type="button" class="btn btn-warning" onclick="approvedincidentsonly()">Approved Incident Only</button>';
        $('#incidentappincident').html(approvedPdiButton);
    } else {
        $('#incidentappincident').hide();
    }
        $('#modelLineincident').text(additionalInfo.model_line);
        $('#vinincident').text(additionalInfo.vin);
        $('#intColourincident').text(additionalInfo.int_colour);
        $('#extColourincident').text(additionalInfo.ext_colour);
        $('#locationincident').text(additionalInfo.location);
        $('#model_yearincident').text(additionalInfo.my);
        $('#buttonContainer').html(buttonContainerHtml);
        $('#incidentModal').modal('show');
      },
      error: function (error) {
        console.error('Error fetching routine inspection details:', error);
      }
    });
  }  
  else if (stage === 'PDI') {
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
        var remarks = response.remarks;
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
      if (response.incidentDetails) {
  var incidentHtml = `
    <h5>Incident Report</h5>
    <div class="row">
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Incident Type</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.type ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Narration Of Accident / Damage</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.narration ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Damage Details</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.detail ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Driven By</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.driven_by ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Responsibility for Recover the Damages</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.responsivity ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label><strong>Reason</strong></label>
          </div>
          <div class="col-md-8">
            ${response.incidentDetails.reason ?? ''}
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            ${response.incidentDetails.file_path ? 
              `<img src="{{ asset('qc/') }}/${response.incidentDetails.file_path}" alt="Incident Image">` : 
              'No image available'}
          </div>
        </div>
      </div>
    <br>`;
  $('#incidentContainer').html(incidentHtml);
  var approvedPdiButton = '<button type="button" class="btn btn-warning" onclick="approvedincidentsonly()">Approved Incident Only</button>';
        $('#incidentapp').html(approvedPdiButton);
    } else {
        $('#incidentapp').hide();
    }
        $('#modelLinepdi').text(additionalInfo.model_line);
        $('#vinpdi').text(additionalInfo.vin);
        $('#intColourpdi').text(additionalInfo.int_colour);
        $('#extColourpdi').text(additionalInfo.ext_colour);
        $('#locationpdi').text(additionalInfo.location);
        $('#model_yearpdi').text(additionalInfo.my);
        $('#pdiremarks').text(remarks.remark);
        var tableHtml = '<table class="table table-bordered"><thead><tr><th>Check List Items</th><th>Receving</th><th>Delivery</th></tr></thead><tbody>';
        PdiInspectionData.forEach(function (row) {
          tableHtml += `<tr>
  <td class="text-left">${row.checking_item !== null ? row.checking_item : ''}</td>
  <td>${row.reciving !== null ? row.reciving : ''}</td>
  <td>${row.status !== null ? row.status : ''}</td>
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
            $('#worksLabel').text('View Incident Details - VIN: ' + vin);
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
            $.ajax({
                type: 'GET',
                url: '/get-pdi-inspection/' + incidentId,
                success: function (pdiResponse) {
                  var pdiHtml = '<h3>PDI Inspection Data</h3>';
                        pdiHtml += '<table class="table table-bordered"><thead><tr><th>Checking Item</th><th>Receiving</th><th>Status</th></tr></thead><tbody>';
                        for (var j = 0; j < pdiResponse.length; j++) {
                            var checkingItem = pdiResponse[j].checking_item;
                            var receiving = pdiResponse[j].reciving;
                            var status = pdiResponse[j].status;
                            pdiHtml += '<tr><td>' + checkingItem + '</td><td>' + receiving + '</td><td>' + status + '</td></tr>';
                        }
                        pdiHtml += '</tbody></table>';
                        $('#modalBody').append(pdiHtml);
                    },
                error: function (error) {
                    console.error(error);
                }
            });
            $.ajax({
                type: 'GET',
                url: '/get-incident-details/' + incidentId,
                success: function (incidentResponse) {
                  var incidentHtml = '<div class="row">';
    incidentHtml += '<div class="col-md-4"><label><strong>Narration Of Accident / Damage</strong></label></div>';
    incidentHtml += '<div class="col-md-8">' + (incidentResponse.narration || '') + '</div>';
    incidentHtml += '<div class="col-md-3">';
    incidentHtml += '<div class="row">';
    incidentHtml += '<div class="col-md-4"><label><strong>Damage Details</strong></label></div>';
    incidentHtml += '<div class="col-md-8">' + (incidentResponse.detail || '') + '</div>';
    incidentHtml += '</div></div>';
    incidentHtml += '<div class="col-md-3">';
    incidentHtml += '<div class="row">';
    incidentHtml += '<div class="col-md-4"><label><strong>Driven By</strong></label></div>';
    incidentHtml += '<div class="col-md-8">' + (incidentResponse.driven_by || '') + '</div>';
    incidentHtml += '</div></div>';
    incidentHtml += '<div class="col-md-6">';
    incidentHtml += '<div class="row">';
    incidentHtml += '<div class="col-md-4"><label><strong>Responsibility for Recover the Damages</strong></label></div>';
    incidentHtml += '<div class="col-md-8">' + (incidentResponse.responsivity || '') + '</div>';
    incidentHtml += '</div></div>';
    incidentHtml += '<div class="col-md-3">';
    incidentHtml += '<div class="row">';
    incidentHtml += '<div class="col-md-4"><label><strong>Reason</strong></label></div>';
    incidentHtml += '<div class="col-md-8">' + (incidentResponse.reason || '') + '</div>';
    incidentHtml += '</div></div>';
    incidentHtml += '<div class="col-md-12">';
    incidentHtml += '<div class="row">';
    incidentHtml += '<div class="col-md-12">';
    if (incidentResponse.file_path) {
        incidentHtml += '<img src="' + incidentResponse.file_path + '" alt="Incident Image">';
    } else {
        incidentHtml += 'No image available';
    }
    incidentHtml += '</div></div></div>';
    incidentHtml += '</div>';
                    $('#modalBody').append(incidentHtml);
                },
                error: function (error) {
                    console.error(error);
                }
            });

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
    var remarks = $('#mangerremarks').val();
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
    function approvedincidentsonly() {
    var inspectionid = $('#inspection_id').val();
    var remarks = $('#mangerremarks').val();
    $.ajax({
        type: 'POST',
        url: '{{route('inspectionapprovalpdi.approvedincidentsonly')}}',
        data: { inspectionid: inspectionid,  remarks: remarks  },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          alertify.success('Approved Incident Success');
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
    <script>

    $('body').on('click', '.read-more-link', function (e) {
      e.preventDefault();
      const fullText = decodeURIComponent($(this).data('detail'));
      const title = $(this).data('title') || 'Full Text';
      $('#variantDetailModalLabel').text(title);
      $('#variantDetailModalBody').html(fullText); 
      $('#variantDetailModal').modal('show');
    });

    $(document).ready(function() {
        $("#reworkButton").click(function() { 
        var incidentId = $("#incidentId").val();
        var requestData = {
          _token: "{{ csrf_token() }}",
            incidentId: incidentId
        };
            $.ajax({
                type: "POST",
                url: "{{ route('incident.reinspectionsforrem') }}",
                data: requestData,
                success: function(response) {
                    alertify.success('Re Work Update successfully');
                    window.location.href = "{{ route('incident.index') }}";
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
@else
<script>
  $(document).ready(function () {
    var table1 = $('#dtBasicExample1').DataTable();
    $('#dtBasicExample1 tbody').on('dblclick', 'tr', function () {
      var data = table1.row(this).data();
      var vehicleId = data.id;
      var stage = data.stage;
      if (stage === 'Routine') {
        $.ajax({
      url: '/routine-inspection/' + vehicleId,
      method: 'GET',
      success: function (response) {
        var additionalInfo = response.additionalInfo;
        var routineInspectionData = response.routineInspectionData;
        var incidentData = response.incidentData;
        var inspectionid = response.inspection;
        console.log(inspectionid);
        var assetUrl = "{{ asset('qc/') }}/";
        var hasInspectionEditPermission = true;
        if(incidentData){
          var incidentHtml = `
    <h5>Incident Report</h5>
    <div class="row">
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Incident Type</strong></label>
          </div>
          <div class="col-md-8">
            <select class="form-control" id="incidentTypeInput">
              <option value="Electrical" ${incidentData.type === 'Electrical' ? 'selected' : ''}>Electrical</option>
              <option value="Mechanical" ${incidentData.type === 'Mechanical' ? 'selected' : ''}>Mechanical</option>
              <option value="Accident" ${incidentData.type === 'Accident' ? 'selected' : ''}>Accident</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Narration Of Accident / Damage</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="narrationInput" value="${incidentData.narration ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Driven By</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="drivenByInput" value="${incidentData.driven_by ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Damage Details</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="damageDetailsInput" value="${incidentData.detail ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Responsibility for Recover the Damages</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="responsivityInput" value="${incidentData.responsivity ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-2">
            <label class="form-label"><strong>Reason</strong></label>
          </div>
          <div class="col-md-5">
            <ul class="list-group">
              <li class="list-group-item">
                <input type="checkbox" id="overspeedInput" ${incidentData.reason.includes('overspeed') ? 'checked' : ''}>
                <label for="overspeedInput">Over-Speed</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" id="weatherInput" ${incidentData.reason.includes('weather') ? 'checked' : ''}>
                <label for "weatherInput">Weather Conditions</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" id="vehicleDefectsInput" ${incidentData.reason.includes('vehicle_defects') ? 'checked' : ''}>
                <label for="vehicleDefectsInput">Vehicle Defects</label>
              </li>
              <li class="list-group-item">
            <input type="checkbox" id="negligenceInput" ${incidentData.reason.includes('negligence') ? 'checked' : ''}>
            <label for="negligenceInput">Negligence</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="suddenHaltInput" ${incidentData.reason.includes('sudden_halt') ? 'checked' : ''}>
            <label for="suddenHaltInput">Sudden Halt</label>
          </li>
            </ul>
          </div>
          <div class="col-md-5">
          <ul class="list-group">
          <li class="list-group-item">
            <input type="checkbox" id="roadDefectsInput" ${incidentData.reason.includes('road_defects') ? 'checked' : ''}>
            <label for="roadDefectsInput">Road Defects</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="fatigueInput" ${incidentData.reason.includes('fatigue') ? 'checked' : ''}>
            <label for="fatigueInput">Fatigue</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="noSafetyDistanceInput" ${incidentData.reason.includes('no_safety_distance') ? 'checked' : ''}>
            <label for="noSafetyDistanceInput">No Safety Distance</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="usingGSMInput" ${incidentData.reason.includes('using_gsm') ? 'checked' : ''}>
            <label for="usingGSMInput">Using GSM</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="overtakingInput" ${incidentData.reason.includes('overtaking') ? 'checked' : ''}>
            <label for="overtakingInput">Overtaking</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="wrongActionInput" ${incidentData.reason.includes('wrong_action') ? 'checked' : ''}>
            <label for="wrongActionInput">Wrong Action</label>
          </li>
        </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        ${incidentData.file_path ? 
          `<img src="{{ asset('qc/') }}/${incidentData.file_path}" alt="Incident Image" class="img-thumbnail">` : 
          'No image available'}
      </div>
    </div>
  </div>
  <br>`;
  $('#incidentContainer').html(incidentHtml);
  var approvedPdiButton = '<button type="button" class="btn btn-warning" onclick="approvedincidentsonly()">Approved Incident Only</button>';
  $('#incidentapp').html(approvedPdiButton);
} else {
        $('#incidentapp').hide();
    }
        $('#modelLine').text(additionalInfo.model_line);
        $('#inspection_id').val(inspectionid);
        $('#vin').text(additionalInfo.vin);
        $('#intColour').text(additionalInfo.int_colour);
        $('#extColour').text(additionalInfo.ext_colour);
        $('#location').text(additionalInfo.location);
        $('#model_year').text(additionalInfo.my);
        var tableHtml = '<table class="table table-bordered"><thead><tr><th>Check Items</th><th>Condition</th><th>Remarks</th></tr></thead><tbody>';
        routineInspectionData.forEach(function (row) {
          tableHtml += '<tr>';
          tableHtml += '<td class="text-left">' + (row.check_items !== null ? row.check_items : '') + '</td>';
          tableHtml += '<td>';
          tableHtml += '<select class="editable-condition form-control">';
          tableHtml += '<option value="Ok" ' + (row.condition === 'Ok' ? 'selected' : '') + '>Ok</option>';
          tableHtml += '<option value="Not Ok" ' + (row.condition === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
          tableHtml += '</select>';
          tableHtml += '</td>';
          tableHtml += '<td><input type="text" class="editable-remarks form-control" value="' + (row.remarks !== null ? row.remarks : '') + '"></td>';
          tableHtml += '</tr>';
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
        var remarks = response.remarks;
        var inspectionid = remarks.id;
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
      if (response.incidentDetails) {
  var incidentHtml = `
    <h5>Incident Report</h5>
    <div class="row">
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Incident Type</strong></label>
          </div>
          <div class="col-md-8">
            <select class="form-control" id="incidentTypeInput">
              <option value="Electrical" ${response.incidentDetails.type === 'Electrical' ? 'selected' : ''}>Electrical</option>
              <option value="Mechanical" ${response.incidentDetails.type === 'Mechanical' ? 'selected' : ''}>Mechanical</option>
              <option value="Accident" ${response.incidentDetails.type === 'Accident' ? 'selected' : ''}>Accident</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Narration Of Accident / Damage</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="narrationInput" value="${response.incidentDetails.narration ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Driven By</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="drivenByInput" value="${response.incidentDetails.driven_by ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Damage Details</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="damageDetailsInput" value="${response.incidentDetails.detail ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-4">
            <label class="form-label"><strong>Responsibility for Recover the Damages</strong></label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="responsivityInput" value="${response.incidentDetails.responsivity ?? ''}">
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-2">
            <label class="form-label"><strong>Reason</strong></label>
          </div>
          <div class="col-md-5">
            <ul class="list-group">
              <li class="list-group-item">
                <input type="checkbox" id="overspeedInput" ${response.incidentDetails.reason.includes('overspeed') ? 'checked' : ''}>
                <label for="overspeedInput">Over-Speed</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" id="weatherInput" ${response.incidentDetails.reason.includes('weather') ? 'checked' : ''}>
                <label for "weatherInput">Weather Conditions</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" id="vehicleDefectsInput" ${response.incidentDetails.reason.includes('vehicle_defects') ? 'checked' : ''}>
                <label for="vehicleDefectsInput">Vehicle Defects</label>
              </li>
              <li class="list-group-item">
            <input type="checkbox" id="negligenceInput" ${response.incidentDetails.reason.includes('negligence') ? 'checked' : ''}>
            <label for="negligenceInput">Negligence</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="suddenHaltInput" ${response.incidentDetails.reason.includes('sudden_halt') ? 'checked' : ''}>
            <label for="suddenHaltInput">Sudden Halt</label>
          </li>
            </ul>
          </div>
          <div class="col-md-5">
          <ul class="list-group">
          <li class="list-group-item">
            <input type="checkbox" id="roadDefectsInput" ${response.incidentDetails.reason.includes('road_defects') ? 'checked' : ''}>
            <label for="roadDefectsInput">Road Defects</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="fatigueInput" ${response.incidentDetails.reason.includes('fatigue') ? 'checked' : ''}>
            <label for="fatigueInput">Fatigue</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="noSafetyDistanceInput" ${response.incidentDetails.reason.includes('no_safety_distance') ? 'checked' : ''}>
            <label for="noSafetyDistanceInput">No Safety Distance</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="usingGSMInput" ${response.incidentDetails.reason.includes('using_gsm') ? 'checked' : ''}>
            <label for="usingGSMInput">Using GSM</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="overtakingInput" ${response.incidentDetails.reason.includes('overtaking') ? 'checked' : ''}>
            <label for="overtakingInput">Overtaking</label>
          </li>
          <li class="list-group-item">
            <input type="checkbox" id="wrongActionInput" ${response.incidentDetails.reason.includes('wrong_action') ? 'checked' : ''}>
            <label for="wrongActionInput">Wrong Action</label>
          </li>
        </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-12">
        ${response.incidentDetails.file_path ? 
          `<img src="{{ asset('qc/') }}/${response.incidentDetails.file_path}" alt="Incident Image" class="img-thumbnail">` : 
          'No image available'}
      </div>
    </div>
  </div>
  <br>`;
  $('#incidentContainer').html(incidentHtml);
  var approvedPdiButton = '<button type="button" class="btn btn-warning" onclick="approvedincidentsonly()">Approved Incident Only</button>';
  $('#incidentapp').html(approvedPdiButton);
} else {
        $('#incidentapp').hide();
    }
        $('#modelLinepdi').text(additionalInfo.model_line);
        $('#vinpdi').text(additionalInfo.vin);
        $('#inspection_idpdi').val(inspectionid);
        $('#intColourpdi').text(additionalInfo.int_colour);
        $('#extColourpdi').text(additionalInfo.ext_colour);
        $('#locationpdi').text(additionalInfo.location);
        $('#model_yearpdi').text(additionalInfo.my);
        $('#pdiremarks').html('<textarea class="form-control" id="remarksInput" rows="4" style="width: 100%;">' + (remarks.remark ? remarks.remark : '') + '</textarea>');
        var tableHtml = '<table class="table table-bordered"><thead><tr><th>Check List Items</th><th>Receving</th><th>Delivery</th></tr></thead><tbody>';
        PdiInspectionData.forEach(function (row) {
          tableHtml += '<tr>';
    tableHtml += '<td class="text-left">' + (row.checking_item !== null ? row.checking_item : '') + '</td>';
    tableHtml += '<td class="text-left">' + (row.reciving !== null ? row.reciving : '') + '</td>';
    tableHtml += '<td>';
    if (row.checking_item === 'FUEL / BATTERY' || row.checking_item === 'OTHER REMARKS') {
        tableHtml += '<input type="text" class="status-input form-control" value="' + (row.status ? row.status : '') + '">';
    }
    else{
    tableHtml += '<select class="status-dropdown form-control">';
    if (row.checking_item === 'Spare Wheel') {
      tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
      tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
    } else if (row.checking_item === 'Jack') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
    } 
    else if (row.checking_item === 'FIRST AID KIT') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
        tableHtml += '<option value="In Box" ' + (row.status === 'In Box' ? 'selected' : '') + '>In Box</option>';
    }
    else if (row.checking_item === 'FLOOR MAT') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
    }
    else if (row.checking_item === 'SERVICE BOOK & MANUAL') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
        tableHtml += '<option value="In Box" ' + (row.status === 'In Box' ? 'selected' : '') + '>In Box</option>';
    }
    else if (row.checking_item === 'KEYS / QTY') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
        tableHtml += '<option value="In Box" ' + (row.status === 'In Box' ? 'selected' : '') + '>In Box</option>';
    }
    else if (row.checking_item === 'EXTERIOR PAINT AND BODY') {
        tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else if (row.checking_item === 'INTERIOR & UPHOLSTERY') {
      tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else if (row.checking_item === 'WHEEL RIM / TYRES') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
    }
    else if (row.checking_item === 'FIRE EXTINGUISHER') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
        tableHtml += '<option value="In Box" ' + (row.status === 'In Box' ? 'selected' : '') + '>In Box</option>';
    }
    else if (row.checking_item === 'SD Card / Remote / H Phones') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
    }
    else if (row.checking_item === 'A/C System') {
        tableHtml += '<option value="AV" ' + (row.status === 'AV' ? 'selected' : '') + '>AV</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
    }
    else if (row.checking_item === 'DASHBOARD / T SCREEN / LCD') {
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
        tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else if (row.checking_item === 'CAMERA') {
        tableHtml += '<option value="360 Degree" ' + (row.status === '360 Degree' ? 'selected' : '') + '>360 Degree</option>';
        tableHtml += '<option value="RR" ' + (row.status === 'RR' ? 'selected' : '') + '>RR</option>';
        tableHtml += '<option value="FR" ' + (row.status === 'FR' ? 'selected' : '') + '>FR</option>';
        tableHtml += '<option value="NA" ' + (row.status === 'NA' ? 'selected' : '') + '>NA</option>';
    }
    else if (row.checking_item === 'STICKER REMOVAL') {
        tableHtml += '<option value="Yes" ' + (row.status === 'Yes' ? 'selected' : '') + '>Yes</option>';
        tableHtml += '<option value="No" ' + (row.status === 'No' ? 'selected' : '') + '>No</option>';
    }
    else if (row.checking_item === 'PACKING BOX') {
      tableHtml += '<option value="Yes" ' + (row.status === 'Yes' ? 'selected' : '') + '>Yes</option>';
        tableHtml += '<option value="No" ' + (row.status === 'No' ? 'selected' : '') + '>No</option>';
    }
    else if (row.checking_item === 'PHOTOS 6 Nos') {
      tableHtml += '<option value="Yes" ' + (row.status === 'Yes' ? 'selected' : '') + '>Yes</option>';
        tableHtml += '<option value="No" ' + (row.status === 'No' ? 'selected' : '') + '>No</option>';
    }
    else if (row.checking_item === 'UNDER HOOD INSPECTION') {
      tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else if (row.checking_item === 'OILS AND FLUIDS LEVELS INSPECTION') {
      tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else if (row.checking_item === 'ALL FUNCTIONS OPERATIONS AS PER PO') {
      tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else if (row.checking_item === 'CLEANING AND WASHING') {
      tableHtml += '<option value="Ok" ' + (row.status === 'Ok' ? 'selected' : '') + '>Ok</option>';
        tableHtml += '<option value="Not Ok" ' + (row.status === 'Not Ok' ? 'selected' : '') + '>Not Ok</option>';
    }
    else
    {
    }
    tableHtml += '</select>';
  }
    tableHtml += '</td>';
    tableHtml += '</tr>';
});
tableHtml += '</tbody></table>';
        $('#pdiInspectionDetails').html(tableHtml);
        $('#buttonContainer').html(buttonContainerHtml);
        $('#mangerremarks').closest('.row').hide();
        $('#pdiModal').modal('show');
      },
      error: function (error) {
        console.error('Error fetching routine inspection details:', error);
      }
    });
  }
  else {
    var url = "{{ route('inspectionedit.edit', ['id' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
  }
    });
  });
  $('#updateButton').on('click', function() {
    var inspectionid = $('#inspection_id').val();
    var updatedData = [];
    
    // Initialize incidentData object outside of the loop
    var incidentData = {
        type: $('#incidentTypeInput').val(),
        narration: $('#narrationInput').val(),
        detail: $('#damageDetailsInput').val(),
        driven_by: $('#drivenByInput').val(),
        responsivity: $('#responsivityInput').val(),
        reason: [
            $('#overspeedInput').is(':checked') ? 'overspeed' : '',
            $('#weatherInput').is(':checked') ? 'weather' : '',
            $('#vehicleDefectsInput').is(':checked') ? 'vehicle_defects' : '',
            $('#negligenceInput').is(':checked') ? 'negligence' : '',
            $('#suddenHaltInput').is(':checked') ? 'sudden_halt' : '',
            $('#roadDefectsInput').is(':checked') ? 'road_defects' : '',
            $('#fatigueInput').is(':checked') ? 'fatigue' : '',
            $('#noSafetyDistanceInput').is(':checked') ? 'no_safety_distance' : '',
            $('#usingGSMInput').is(':checked') ? 'using_gsm' : '',
            $('#overtakingInput').is(':checked') ? 'overtaking' : '',
            $('#wrongActionInput').is(':checked') ? 'wrong_action' : '',
        ].filter(Boolean),
    };

    $('#routineInspectionDetails tbody tr').each(function(index, row) {
        var checkItems = $(row).find('.text-left').text();
        var spec = $(row).find('.editable-spec').val();
        var condition = $(row).find('.editable-condition').val();
        var remarks = $(row).find('.editable-remarks').val();
        updatedData.push({ check_items: checkItems, spec: spec, condition: condition, remarks: remarks });
    });
console.log(incidentData);
    $.ajax({
        url: '/update-routine-inspection',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            inspectionid: inspectionid,
            updatedData: updatedData,
            incidentData: incidentData
        },
        success: function(response) {
            alertify.success('Routine Inspection Update successfully');
            setTimeout(function() {
                window.location.reload();
            }, 500);
            $('#routineModal').modal('hide');
        },
        error: function(error) {
            console.error('Error updating routine inspection:', error);
        }
    });
});
$(document).on('click', '#updateButtonpdi', function () {
  var inspectionid = $('#inspection_idpdi').val();
  var remarks = {
    remark: $('#remarksInput').val(),
  };
  var PDIInspectionData = [];
  $('#pdiInspectionDetails tbody tr').each(function (index, row) {
    var checking_item = $(row).find('td:eq(0)').text();
    var reciving = $(row).find('td:eq(1)').text();
    var status = '';
    var selectInput = $(row).find('select.status-dropdown');
    if (selectInput.length > 0) {
      status = selectInput.val();
    } else {
      status = $(row).find('input.status-input').val();
    }
    PDIInspectionData.push({
      checking_item: checking_item,
      reciving: reciving,
      status: status,
    });
  });
  var incidentData = {
    type: $('#incidentTypeInput').val(),
    narration: $('#narrationInput').val(),
    detail: $('#damageDetailsInput').val(),
    driven_by: $('#drivenByInput').val(),
    responsivity: $('#responsivityInput').val(),
    reason: [
      $('#overspeedInput').is(':checked') ? 'overspeed' : '',
      $('#weatherInput').is(':checked') ? 'weather' : '',
      $('#vehicleDefectsInput').is(':checked') ? 'vehicle_defects' : '',
      $('#negligenceInput').is(':checked') ? 'negligence' : '',
      $('#suddenHaltInput').is(':checked') ? 'sudden_halt' : '',
      $('#roadDefectsInput').is(':checked') ? 'road_defects' : '',
      $('#fatigueInput').is(':checked') ? 'fatigue' : '',
      $('#noSafetyDistanceInput').is(':checked') ? 'no_safety_distance' : '',
      $('#usingGSMInput').is(':checked') ? 'using_gsm' : '',
      $('#overtakingInput').is(':checked') ? 'overtaking' : '',
      $('#wrongActionInput').is(':checked') ? 'wrong_action' : '',
    ].filter(Boolean),
  };
  var updateData = {
    remarks: remarks,
    PDIInspectionData: PDIInspectionData,
    incidentData: incidentData,
  };
console.log(incidentData);
  $.ajax({
    url: '/update-pdi-inspection',
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      inspectionid: inspectionid,
      updatedData: updateData
    },
    success: function (response) {
      alertify.success('PDI Inspection Update successfully');
      setTimeout(function() {
        window.location.reload();
      }, 500);
      $('#pdiModal').modal('hide');
    },
    error: function (error) {
      console.error('Error updating data:', error);
    },
  });
});
  </script>
    @endif
    @else
        @php
            redirect()->route('home')->send();
        @endphp
    @endif
    @endsection