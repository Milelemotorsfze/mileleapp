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
      <a class="btn btn-sm btn-primary float-end" href="{{ route('vehicle-pictures.index') }}" text-align: right>
        <i class="fa fa-wrench" aria-hidden="true"></i> Modification Vehicles
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
  <form id="pdiForm" action="{{ route('pdi.pdiinspection') }}" method="POST">
    @csrf <!-- Laravel CSRF Token -->
  <div class="modal fade works-modal" id="PDIModal" tabindex="-1" aria-labelledby="PDIModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">PDI Inspection Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <input type="hidden" id="vehicle_id" name="vehicle_id">
      <div class="row">
  <div class="col-md-2">
    <p><strong>Model Line:</strong></p>
    <p><span id="modelLine"></span></p>
</div>
    <div class="col-md-2">
    <p><strong>VIN:</strong></p>
    <p><span id="vin"></span></p>
    </div>
</div>
        <div id="routineInspectionDetails">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Check Items</th>
                <th>Receving Qty</th>
                <th>Receving</th>
                <th>Delivery Qty</th>
                <th>Delivery</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tr>
                            <td>Spare Wheel</td>
                            <td><input type="text" class="form-control" name="qty_sparewheel" id="qty_sparewheel"></td>
                            <td><input type="text" class="form-control" name="recving_sparewheel" id="recving_sparewheel"></td>
                            <td><input type="text" class="form-control" name="dqty_sparewheel"></td>
                            <td>
                              <select class="form-control" name="delivery_sparewheel">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_sparewheel"></td>
                        </tr>
              <tr>
             <td>
                                <span>Jack</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_jack" id="qty_jack"></td>
                            <td><input type="text" class="form-control" name="recving_jack" id="recving_jack"></td>
                            <td><input type="text" class="form-control" name="dqty_jack"></td>
                            <td>
                              <select class="form-control" name="delivery_jack">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_jack"></td>
                            </tr>
                                        <tr>
                                      <td>
                                <span>Wheel Spanner</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_wheel" id="qty_wheel"></td>
                            <td><input type="text" class="form-control" name="recving_wheel" id="recving_wheel"></td>
                            <td><input type="text" class="form-control" name="dqty_wheel"></td>
                            <td>
                              <select class="form-control" name="delivery_wheel">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_wheel"></td>
                            </tr>
                                        <tr>
                                      <td>
                                <span>First Aid Kit / Packing Box</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_firstaid" id="qty_firstaid"></td>
                            <td><input type="text" class="form-control" name="recving_firstaid" id="recving_firstaid"></td>
                            <td><input type="text" class="form-control" name="dqty_firstaid"></td>
                            <td>
                              <select class="form-control" name="delivery_firstaid">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_firstaid"></td>
                              </tr>
                                          <tr>
                                        <td>
                                <span>Floor Mat</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_floor_mat" id="qty_floor_mat"></td>
                            <td><input type="text" class="form-control" name="recving_floor_mat" id="recving_floor_mat"></td>
                            <td><input type="text" class="form-control" name="dqty_floor_mat"></td>
                            <td>
                              <select class="form-control" name="delivery_floor_mat">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_floor_mat"></td>
                            </tr>
                                        <tr>
                                      <td>
                                <span>Service Book & Manual</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_service_book" id="qty_service_book"></td>
                            <td><input type="text" class="form-control" name="recving_service_book" id="recving_service_book"></td>
                            <td><input type="text" class="form-control" name="dqty_service_book"></td>
                            <td>
                              <select class="form-control" name="delivery_service_book">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_service_book"></td>
                          </tr>
                                      <tr>
                                    <td>
                                <span>Keys</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_keys" id="qty_keys"></td>
                            <td><input type="text" class="form-control" name="recving_keys" id="recving_keys"></td>
                            <td><input type="text" class="form-control" name="dqty_keys"></td>
                            <td>
                              <select class="form-control" name="delivery_keys">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_keys"></td>
                            </tr>
                                        <tr>
                                      <td>
                                <span>Wheel Rim / Tyres</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_wheelrim" id="qty_wheelrim"></td>
                            <td><input type="text" class="form-control" name="recving_wheelrim" id="recving_wheelrim"></td>
                            <td><input type="text" class="form-control" name="dqty_wheelrim"></td>
                            <td>
                              <select class="form-control" name="delivery_wheelrim">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_wheelrim"></td>
                            </tr>
                            <tr>
                                      <td>
                                <span>Fire Extinguisher</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_fire_extinguisher" id="qty_fire_extinguisher"></td>
                            <td><input type="text" class="form-control" name="recving_fire_extinguisher" id="recving_fire_extinguisher"></td>
                            <td><input type="text" class="form-control" name="dqty_fire_extinguisher"></td>
                            <td>
                              <select class="form-control" name="delivery_fire_extinguisher">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_fire_extinguisher"></td>
                            </tr>
                            <tr>
                                      <td>
                                <span>SD Card / Remote /  H Phones</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_sd_card" id="qty_sd_card"></td>
                            <td><input type="text" class="form-control" name="recving_sd_card" id="recving_sd_card"></td>
                            <td><input type="text" class="form-control" name="dqty_sd_card"></td>
                            <td>
                              <select class="form-control" name="delivery_sd_card">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_sd_card"></td>
                            </tr>
                            <tr>
                                      <td>
                                <span>A/C System</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_ac_system" id="qty_ac_system"></td>
                            <td><input type="text" class="form-control" name="recving_ac_system" id="recving_ac_system"></td>
                            <td><input type="text" class="form-control" name="dqty_ac_system"></td>
                            <td>
                              <select class="form-control" name="delivery_ac_system">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_ac_system"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Dash Board / T Screen / LCD</span>
                            </td>
                            <td><input type="text" class="form-control" name="qty_dash_board" id="qty_dash_board"></td>
                            <td><input type="text" class="form-control" name="recving_dash_board" id="recving_dash_board"></td>
                            <td><input type="text" class="form-control" name="dqty_dash_board"></td>
                            <td>
                              <select class="form-control" name="delivery_dash_board">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_dash_board"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Exterior Paint & Body</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_paint"></td>
                            <td>
                              <select class="form-control" name="delivery_paint">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_paint"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Interior & Upholstery</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_interior"></td>
                            <td>
                              <select class="form-control" name="delivery_interior">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_interior"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Camera</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_camera"></td>
                            <td>
                              <select class="form-control" name="delivery_camera">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">OK</option>
                              </select>
                          </td>
                            <td><input type="text" class="form-control" name="remarks_camera"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Sticker Removal</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_sticker_removal"></td>
                            <td>
                            <select class="form-control" name="delivery_sticker_removal">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                            </td>
                            <td><input type="text" class="form-control" name="remarks_sticker_removal"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Un Packing / Fitment / PDI</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_packing"></td>
                            <td>
                            <select class="form-control" name="delivery_packing">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                             </td>
                            <td><input type="text" class="form-control" name="remarks_packing"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Fuel / Battery</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_fuel"></td>
                            <td>
                            <select class="form-control" name="delivery_fuel">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                            </td>
                            <td><input type="text" class="form-control" name="remarks_fuel"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Under Hood Inspection</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_under_hood"></td>
                            <td>
                            <select class="form-control" name="delivery_under_hood">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                            </td>
                            <td><input type="text" class="form-control" name="remarks_under_hood"></td>
                            </tr>
                            <tr>
                            <td>
                                <span>Oils and Fluids Levels Inspection</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td><input type="text" class="form-control" name="dqty_oil"></td>
                            <td>
                            <select class="form-control" name="delivery_oil">
                                  <option value="N/A">N/A</option>
                                  <option value="Not Ok">Not Ok</option>
                                  <option value="OK">Ok</option>
                              </select>
                           </td>
                            <td><input type="text" class="form-control" name="remarks_oil"></td>
                            </tr>      
          </table>
        </div>
      </div>
      <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
    </div>
  </div>
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
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 20000);
    }
</script>
<script>
  $(document).ready(function () {
    var table1 = $('#dtBasicExample4').DataTable();
    $('#dtBasicExample4 tbody').on('dblclick', 'tr', function () {
      var data = table1.row(this).data();
      var vehicleId = data.id;
      var inspectionid = data.id;
      $('#vehicle_id').val(vehicleId);
      console.log(vehicleId);
      $.ajax({
    url: '/get-vehicle-extra-items/' + vehicleId,
    method: 'GET',
    success: function (response) {
    var itemsWithQuantities = response.itemsWithQuantities;
    var vehicleDetails = response.vehicleDetails;
    if (vehicleDetails) {
        var vin = vehicleDetails.vin;
        var variantsId = vehicleDetails.variants_id;
        var modelLine = vehicleDetails.masterModelLine ? vehicleDetails.masterModelLine.model_line : '';
    }
    var itemElementMap = {
        'sparewheel': { qty: 'qty_sparewheel', recving: 'recving_sparewheel' },
        'jack': { qty: 'qty_jack', recving: 'recving_jack' },
        'wheel': { qty: 'qty_wheel', recving: 'recving_wheel' },
        'firstaid': { qty: 'qty_firstaid', recving: 'recving_firstaid' },
        'service_book': { qty: 'qty_service_book', recving: 'recving_service_book' },
        'keys': { qty: 'qty_keys', recving: 'recving_keys' },
        'wheelrim': { qty: 'qty_wheelrim', recving: 'recving_wheelrim' },
        'fire_extinguisher': { qty: 'qty_fire_extinguisher', recving: 'recving_fire_extinguisher' },
        'sd_card': { qty: 'qty_sd_card', recving: 'recving_sd_card' },
        'sd_card': { qty: 'qty_sd_card', recving: 'recving_sd_card' },
        'ac_system': { qty: 'qty_ac_system', recving: 'recving_ac_system' },
        'floor_mat': { qty: 'qty_floor_mat', recving: 'recving_floor_mat' },
        'dash_board': { qty: 'qty_dash_board', recving: 'recving_dash_board' },
    };
    for (var itemName in itemElementMap) {
        if (itemElementMap.hasOwnProperty(itemName)) {
            var itemData = itemsWithQuantities.find(item => item.item_name === itemName);
            var itemElements = itemElementMap[itemName];
            if (itemData) {
                $('#' + itemElements.qty).val(itemData.qty);
                $('#' + itemElements.recving).val('Yes');
            } else {
                $('#' + itemElements.qty).val('');
                $('#' + itemElements.recving).val('N/A');
            }
            $('#' + itemElements.recving).prop('readonly', true);
            $('#' + itemElements.qty).prop('readonly', true);
        }
    }
    $('#modelLine').text(modelLine);
    $('#vin').text(vin);
    $('#PDIModal').modal('show');
    },
    error: function (error) {
        console.error('Error fetching vehicle extra items:', error);
    }
  });
    });
  });
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection