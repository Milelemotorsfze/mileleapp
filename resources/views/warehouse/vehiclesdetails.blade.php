@extends('layouts.table')
<style>
    .table-responsive {
  overflow: auto;
  max-height: 650px; /* Adjust the max-height to your desired value */
}

.table-wrapper {
  position: relative;
}

thead th {
  position: sticky;
  top: 0;
  background-color: rgba(116,120,141,.25)!important;
  z-index: 1; /* Ensure the table header is on top of other elements */
}
#table-responsive {
  height: 100vh; /* Set the container height to match the screen height */
  overflow-y: auto; /* Enable vertical scrolling */
}

#dtBasicSupplierInventory {
  width: 100%; /* Optionally set the table width to 100% */
  font-size: 14px; /* Adjust the font size as needed */
}
.nowrap-td {
    white-space: nowrap;
  }
  /* Additional styles for Select2 dropdown */
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
  width: 0;
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
    $exColours = \App\Models\ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
    $intColours = \App\Models\ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
@endphp
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">View Vehicles Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="row">
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">PO Date : </label>
            <input type="Date" id="po_date" name="po_date" value="{{$purchasingOrder->po_date}}"class="form-control" placeholder="PO Date" readonly>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">PO Number : </label>
            <input type="number" id="po_number" name="po_number" class="form-control" value="{{$purchasingOrder->po_number}}" placeholder="PO Number" readonly>
            <span id="poNumberError" class="error" style="display: none;"></span>
        </div>
        <div class="col-lg-2 col-md-6">
            <label for="basicpill-firstname-input" class="form-label">Vendor : </label>
            <input type="text" id="vendor_name" name="vendor_name" class="form-control" value="{{$vendorsname}}" placeholder="Vendor Name" readonly>
            <span id="poNumberError" class="error" style="display: none;"></span>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                    @endphp
                    @if ($hasPermission)
                <th class="nowrap-td">PO Number</th>
                <th class="nowrap-td">PO Date</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('ETA-timer-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">ETA Timer</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">Estimated Arrival</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">GRN</th>
                <th class="nowrap-td">GRN Date</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">Inspection Date</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">Aging</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">SO Number</th>
                    <th class="nowrap-td">Sales Person</th>
                    @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Reservation Start</th>
                    <th class="nowrap-td">Reservation End</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Sales Remarks</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">GDN</th>
                    <th class="nowrap-td">GDN Date</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Brand</th>
                    <th class="nowrap-td">Model Line</th>
                    <th class="nowrap-td">Model Description</th>
                    <th class="nowrap-td">Variant</th>
                    <th class="nowrap-td">Variant Detail</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vin-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">VIN Number</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-view');
                @endphp
                @if ($hasPermission)
                <th class="nowrap-td">Conversion</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Engine</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Manufacture Year</th>
                    <th class="nowrap-td">Steering</th>
                    <th class="nowrap-td">Seats</th>
                    <th class="nowrap-td">Fuel Type</th>
                    <th class="nowrap-td">Gear</th>
                    <th class="nowrap-td">Ex Colour</th>
                    <th class="nowrap-td">Int Colour</th>
                    <th class="nowrap-td">Upholstery</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">PY MM YYYY</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Allowed Territory</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Warehouse</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-remarks-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Warehouse Remarks</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">Price</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-view');
                @endphp
                @if ($hasPermission)
               <th class="nowrap-td">Import Document Type</th>
               <th class="nowrap-td">Document Ownership</th>
                    <th class="nowrap-td">Documents With</th>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
                @endphp
                @if ($hasPermission)
                    <th class="nowrap-td">BL Number</th>
                @endif
                    <th class="nowrap-td" id="pictures" style="vertical-align: middle;">Pictures View</th>
                    <th class="nowrap-td"id="log" style="vertical-align: middle;">Changes Log</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $vehicles)
                <tr data-id="{{$vehicles->id}}" onclick="openModal({{$vehicles->id}})">
                    @php
                     $name = "";
                     $grn_date = "";
                     $grn_number = "";
                     $gdn_date = "";
                     $gdn_number = "";
                     $aging = "";
                     $salesname = "";
                     $conversions = "";
                     $varaints_name = "";
                     $varaints_detail = "";
                     $brand_name = "";
                     $model_line = "";
                     $varaints_my = "";
                     $varaints_steering = "";
                     $varaints_fuel_type = "";
                     $varaints_seat = "";
                     $varaints_gearbox = "";
                     $varaints_upholestry = "";
                     $po = DB::table('purchasing_order')->where('id', $vehicles->purchasing_order_id)->first();
                     $po_date = $po->po_date;
                     $po_number = $po->po_number;
                     $exColour = $vehicles->ex_colour ? DB::table('color_codes')->where('id', $vehicles->ex_colour)->first() : null;
                     $ex_colours = $exColour ? $exColour->name : null;
                     $intColour = $vehicles->int_colour ? DB::table('color_codes')->where('id', $vehicles->int_colour)->first() : null;
                     $int_colours = $intColour ? $intColour->name : null;
                     $variants = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                     $name = $variants->name;
                     $grn = $vehicles->movement_grn_id ? DB::table('movement_grns')->where('id', $vehicles->movement_grn_id)->first() : null;
                     $grn_date = $grn ? $grn->date : null;
                     $grn_number = $grn ? $grn->grn_number : null;
                     $gdn = $vehicles->gdn_id ? DB::table('gdn')->where('id', $vehicles->gdn_id)->first() : null;
                     $gdn_date = $gdn ? $gdn->date : null;
                     $gdn_number = $gdn ? $gdn->gdn_number : null;
                     $so = $vehicles->so_id ? DB::table('so')->where('id', $vehicles->so_id)->first() : null;
                     $so_number = $so ? $so->so_number : null;
                     $sales_person_id = $so ? $so->sales_person_id : null;
                    $sales_person = $sales_person_id ? DB::table('users')->where('id', $sales_person_id)->first() : null;
                    $salesname = $sales_person ? $sales_person->name : null;
                    $booking = $vehicles->booking_id ? DB::table('booking')->where('id', $vehicles->booking_id)->first() : null;
                    $booking_name = $booking ? $booking->name : null;
                    $warehouse = $vehicles->vin ? DB::table('movements')->where('vin', $vehicles->vin)->latest()->first() : null;
                    $warehouses = $warehouse ? DB::table('warehouse')->where('id', $warehouse->to)->value('name') : null;

                     $result = DB::table('varaints')
                                ->join('brands', 'varaints.brands_id', '=', 'brands.id')
                                ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                                ->where('varaints.id', $vehicles->varaints_id)
                                ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
                                ->first();
                                $varaints_name = $result->name;
                                $varaints_my = $result->my;
                                $varaints_steering = $result->steering;
                                $varaints_fuel_type = $result->fuel_type;
                                $varaints_seat = $result->seat;
                                $varaints_detail = $result->detail;
                                $varaints_gearbox = $result->gearbox;
                                $varaints_upholestry = $result->upholestry;
                                $brand_name = $result->brand_name;
                                $model_line = $result->model_line;
                                $documents = $vehicles->documents_id ? DB::table('documents')->where('id', $vehicles->documents_id)->first() : null;
                                $import_type = $documents ? $documents->import_type : null;
                                $owership = $documents ? $documents->owership : null;
                                $document_with = $documents ? $documents->document_with : null;
                                $bl_status = $documents ? $documents->bl_status : null;
                                $latestRemark = DB::table('vehicles_remarks')->where('vehicles_id', $vehicles->id)->where('department', 'sales')->orderBy('created_at', 'desc')->value('remarks');
                                @endphp
                     @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                    @endphp
                    @if ($hasPermission)
                     <td class="nowrap-td PoNumber">PO - {{ $po_number }}</td>
                     <td class="nowrap-td PoDate">{{ date('d-m-Y', strtotime($po_date)) }}</td>
                     @endif
                     @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                    @endphp
                    @if ($hasPermission)
                     @if ($grn_number)
                     <td class="nowrap-td grnNumber">GRN - {{ $grn_number }}</td>
                     @else
                     <td class="nowrap-td grnNumber">-</td>
                     @endif
                     @if ($grn_date)
                     <td class="nowrap-td grnDate">{{ date('d-m-Y', strtotime($grn_date)) }}</td>
                     @else
                     <td class="nowrap-td grnDate">-</td>
                    @endif
                    @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
                @endphp
                @if ($hasPermission)
                <td class="nowrap-td stockstatus">Incoming</td>
                @if ($grn_number && $so_number =="")
                <td class="nowrap-td stockstatus">Available</td>
                @if ($vehicles->reservation_end_date && $vehicles->reservation_end_date->greaterThan(\Carbon\Carbon::now()))
                  <td class="nowrap-td stockstatus">Booked</td>
              @endif
                @endif
                @endif
                    @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                    @endphp
                    @if ($hasPermission)
                    <td class="nowrap-td">{{ date('d-m-Y', strtotime($vehicles->inspection_date)) }}</td>
                    <input type="hidden" class="inspection" value="{{ $vehicles->inspection_date }}">
                    @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                    @endphp
                    @if ($hasPermission)
                     @if ($grn_date)
                     @php
                     $grn_date = \Carbon\Carbon::parse($grn_date);
                     $aging = $grn_date->diffInDays(\Carbon\Carbon::today());
                     @endphp
                     <td class="nowrap-td">{{ $aging }}</td>
                     @else
                     @php
                          $paymentLog = DB::table('payment_logs')->where('vehicle_id', $vehicles->id)->latest()->first();
                      @endphp

                      @if ($paymentLog)
                          @php
                              $savedDate = $paymentLog->date;
                              $today = now()->format('Y-m-d');
                              $numberOfDays = Carbon\Carbon::parse($savedDate)->diffInDays($today);
                          @endphp
                          <td class="nowrap-td">{{$numberOfDays}}</td>
                      @else
                          <td class="nowrap-td">-</td>
                      @endif
                     @endif
                     @endif
                     @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                    @endphp
                    @if ($hasPermission)
                     @if ($so_number)
                     <td class="nowrap-td so_number">{{ $so_number }}</td>
                     <input type="hidden" class="payment_percentage" value="{{ $vehicles->payment_percentage }}">
                     @else
                     <td class="nowrap-td">-</td>
                     @endif
                     <td class="nowrap-td">{{ $salesname }}</td>
                     <input type="hidden" class="sales_person" value="{{ $sales_person_id }}">
                     @endif
                     @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                    @endphp
                    @if ($hasPermission)
                     <td class="nowrap-td reservation_start_date">{{ $vehicles->reservation_start_date }}</td>
                     <td class="nowrap-td reservation_end_date">{{ $vehicles->reservation_end_date }}</td>
                     @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                    @endphp
                    @if ($hasPermission)
                     @if($latestRemark)
                        <td class="nowrap-td" onclick="event.stopPropagation();">
                            {{ $latestRemark }}
                            <a href="{{ route('vehiclesremarks.viewremarks', $vehicles->id) }}" class="read-more" target="_blank">View All</a>
                        </td>
                    @else
                        <td class="nowrap-td">-</td>
                    @endif
                    @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
                    @endphp
                    @if ($hasPermission)
                     @if ($gdn_number)
                     <td class="nowrap-td gdnNumber">GDN - {{ $gdn_number }}</td>
                     @else
                     <td class="nowrap-td gdnNumber">-</td>
                     @endif
                     @if ($gdn_date)
                     <td class="nowrap-td gdnDate">{{ date('d-M-Y', strtotime($gdn_date)) }}</td>
                     @else
                     <td class="nowrap-td gdnDate">-</td>
                     @endif
                     @endif
                     @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                    @endphp
                    @if ($hasPermission)
                     <td class="nowrap-td brand">{{ $brand_name }}</td>
                     <td class="nowrap-td">{{ $model_line }}</td>
                     <td class="nowrap-td">{{ $vehicles->vin }}</td>
                     <td class="nowrap-td Variant">{{ $varaints_name }}</td>
                     <td class="nowrap-td">{{ $varaints_detail }}</td>
                     @endif
                    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vin-view');
                    @endphp
                    @if ($hasPermission)
                     <td class="nowrap-td Vin">{{ $vehicles->vin }}</td>
                     @endif
                      @php
                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-view');
                      @endphp
                      @if ($hasPermission)
                      <td class="nowrap-td conversion">{{ $vehicles->conversion }}</td>
                      @endif
                      @php
                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
                      @endphp
                      @if ($hasPermission)
                     <td class="nowrap-td Engine">{{ $vehicles->engine }}</td>
                     @endif
                     @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                    @endphp
                    @if ($hasPermission)
                     <td class="nowrap-td">{{ $varaints_my }}</td>
                        <td class="nowrap-td">{{ $varaints_steering }}</td>
                        <td class="nowrap-td">{{ $varaints_seat }}</td>
                        <td class="nowrap-td">{{ $varaints_fuel_type }}</td>
                        <td class="nowrap-td">{{ $varaints_gearbox }}</td>
                        <td class="nowrap-td">{{ $ex_colours }}</td>
                        <input type="hidden" class="ExColour" value="{{ $vehicles->ex_colour }}">
                        <td class="nowrap-td">{{ $int_colours }}</td>
                        <input type="hidden" class="IntColour" value="{{ $vehicles->int_colour }}">
                        <td class="nowrap-td Upholestry">{{ $varaints_upholestry }}</td>
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                        @endphp
                        @if ($hasPermission)
                        @if ($vehicles->ppmmyyy)
                        <input type="hidden" class="Ppmmyy" value="{{ $vehicles->ppmmyyy }}">
                        <td class="nowrap-td">{{ date('d-M-Y', strtotime($vehicles->ppmmyyy)) }}
                        </td>
                        @else
                        <td class="nowrap-td Ppmmyy"></td>
                        @endif
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
                        @endphp
                        @if ($hasPermission)
                        <td class="nowrap-td Territory">{{ $vehicles->territory }}</td>
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
                        @endphp
                        @if ($hasPermission)
                        <td class="nowrap-td">{{ $warehouses }}</td>
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-remarks-view');
                        @endphp
                        @if ($hasPermission)
                        <td class="nowrap-td remarks">{{ $vehicles->remarks }}</td>
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-view');
                        @endphp
                        @if ($hasPermission)
                       <td class="nowrap-td">{{ $vehicles->price }}</td>
                       @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-view');
                        @endphp
                        @if ($hasPermission)
                        <td class="nowrap-td import_type">{{ $import_type }}</td>
                        <td class="nowrap-td owership">{{ $owership }}</td>
                        <td class="nowrap-td document_with">{{ $document_with }}</td>
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
                        @endphp
                        @if ($hasPermission)
                        <td class="nowrap-td bl_status">{{ $bl_status }}</td>
                       @endif
                        <td><a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehiclespictures.viewpictures', $vehicles->id) }}" onclick="event.stopPropagation();" target="_blank"> View Pic</a></td>
                        <td><a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehicleslog.viewdetails', $vehicles->id) }}" onclick="event.stopPropagation();" target="_blank"></i> View Log</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-edit');
                        @endphp
                        @if ($hasPermission)
  <div id="editmodalwarehouse" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form action="{{ route('vehicles.updatewarehouse')}}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-12">
        <label for="gdn_date">Conversions</label>
        <input type="text" class="form-control" id="conversion" name="conversion" value="{{ $vehicles->conversions }}">
        <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="{{ $vehicles->id }}">
      </div>
    </div>
    <div class="col-md-12">
        <label for="gdn_date">Remarks</label>
        <input type="text" class="form-control" id="remarks" name="remarks" value="{{ $vehicles->remarks }}">
      </div>
    </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
    </div>
  </div>
</div>
@endif
@php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                        @endphp
                        @if ($hasPermission)
  <div id="editModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Vehicle Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
    <div class="col-3" id="po">PO Number: {{ $po_number }}</div>
    <div class="col-3" id="grn" >GRN Number: {{ $grn_number }}</div>
    <div class="col-3"id="brand" >Brand: {{ $brand_name }}</div>
    <div class="col-3" id="vin">VIN: {{ $vehicles->vin }}</div>
</div>
<hr>
    <form action="{{ route('vehicles.updatedata')}}" method="POST">
    @csrf
    <div class="row">
    <div class="col-md-4">
        <label for="gdn_date">Inspection Date</label>
        <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="{{ $vehicles->id }}">
        <input type="date" class="form-control" id="inspection" name="inspection" value="{{ $vehicles->inspection_date }}">
      </div>
    <div class="col-md-4">
  <label for="variants_name">Variant</label>
  <input type="text" class="form-control" id="variants_name" name="variants_name" list="variantslist" value="{{ $varaints_name }}">
  <datalist id="variantslist">
    @foreach ($varaint as $varainters)
      <option value="{{ $varainters->name }}">{{ $varainters->name }}</option>
    @endforeach
  </datalist>
</div>
      <div class="col-lg-4">
        <label for="gdn_date">Engine</label>
        <input type="text" class="form-control" id="engine" name="engine" value="{{ $vehicles->engine }}">
      </div>
      <div class="col-lg-4">
      <label for="gdn_date">Ex Colour</label>
        <select name="ex_colour" id="ex_colour" class="form-control" placeholder="Exterior Color">
        <option value="">Exterior Color</option>
        @foreach ($exColours as $id => $exColour)
            @if ($id == $vehicles->ex_colour)
                <option value="{{ $id }}" selected>{{ $exColour }}</option>
            @else
                <option value="{{ $id }}">{{ $exColour }}</option>
            @endif
        @endforeach
    </select>
      </div>
      <div class="col-lg-4">
      <label for="gdn_date">Interior Color</label>
        <select name="int_colour" id="int_colour" class="form-control" placeholder="Interior Color">
        <option value="">Interior Color</option>
        @foreach ($intColours as $id => $intColour)
            @if ($id == $vehicles->int_colour)
                <option value="{{ $id }}" selected>{{ $intColour }}</option>
            @else
                <option value="{{ $id }}">{{ $intColour }}</option>
            @endif
        @endforeach
    </select>
      </div>
      <div class="col-md-4">
        <label for="gdn_date">PP MM YYYY</label>
        <input type="date" class="form-control" id="ppmmyy" name="ppmmyy" value="{{ $vehicles->ppmmyyy }}">
      </div>
    </div>
</div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
    </div>
  </div>
</div>
@endif
@php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-edit');
                        @endphp
                        @if ($hasPermission)
  <div id="editModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Logistics Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form action="{{ route('vehicles.updatelogistics')}}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-4">
  <label for="import_document">Import Document Type</label>
  <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="{{ $vehicles->id }}">
        <select name="import_type" id="import_type" class="form-control" placeholder="Import Document Type">
        <option value="Belgium Docs">Belgium Docs</option>
        <option value="BOE + VCC + Exit">BOE + VCC + Exit</option>
        <option value="Cross Trade">Cross Trade</option>
        <option value="Dubai Trade">Dubai Trade</option>
        <option value="Incoming">Incoming</option>
        <option value="No Records">No Records</option>
        <option value="RTA Possession">RTA Possession</option>
        <option value="RTA Registration">RTA Registration</option>
        <option value="Supplier Docs">Supplier Docs</option>
        <option value="VCC">VCC</option>
        <option value="Zimbabwe">Zimbabwe</option>
    </select>
</div>
      <div class="col-md-4">
        <label for="gdn_date">Document Ownership</label>
        <select name="owership" id="owership" class="form-control" placeholder="Docuemnt Ownership">
        <option value="Abdul Azeem">Abdul Azeem</option>
        <option value="Barwil Supplier">Barwil Supplier</option>
        <option value="Belgium Warehouse">Belgium Warehouse</option>
        <option value="Faisal Raiz">Faisal Raiz</option>
        <option value="Feroz Riaz">Feroz Riaz</option>
        <option value="Globelink Supplier">Globelink Supplier</option>
        <option value="Incoming">Incoming</option>
        <option value="Milele">Milele</option>
        <option value="Milele Car Trading LLC">Milele Car Trading LLC</option>
        <option value="Milele Motors FZE">Milele Motors FZE</option>
        <option value="Oneworld Limousine">Oneworld Limousine</option>
        <option value="Supplier">Supplier</option>
        <option value="Trans Car FZE">Trans Car FZE</option>
        <option value="Zimbabwe Docs">Zimbabwe Docs</option>
    </select>
      </div>
      <div class="col-md-4">
        <label for="gdn_date">Document With</label>
        <select name="document_with" id="document_with" class="form-control" placeholder="Docuemnt With">
        <option value="Accounts">Accounts</option>
        <option value="Finance Department">Finance Department</option>
        <option value="Import Department">Import Department</option>
        <option value="Not Applicable">Not Applicable</option>
        <option value="Supplier">Supplier</option>
    </select>
      </div>
      <div class="col-md-4">
        <label for="gdn_date">BL Status</label>
        <select name="bl_status" id="bl_status" class="form-control" placeholder="BL Status">
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
      </div>
</div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
    </div>
  </div>
</div>
@endif
@php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
                        @endphp
                        @if ($hasPermission)
  <div id="editModalso" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Vehicle Sales Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form action="{{ route('vehicles.updateso')}}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-4">
  <label for="so_number">SO Number</label>
    <input type="number" class="form-control" id="so_number" name="so_number" value="{{ $so_number }}">
    <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="{{ $vehicles->id }}">
</div>
@php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-reservation');
                        @endphp
                        @if ($hasPermission)
<div class="col-md-4">
  <label for="so_number">Reservation Start Date</label>
    <input type="date" class="form-control" id="reservation_start_date" name="reservation_start_date" value="{{ $vehicles->reservation_start_date }}">
</div>
<div class="col-md-4">
  <label for="so_number">Reservation Ending Date</label>
    <input type="date" class="form-control" id="reservation_end_date" name="reservation_end_date" value="{{ $vehicles->reservation_end_date }}">
</div>
<div class="col-md-4">
  <label for="gdn_date">Sales Person</label>
  <select name="sales_person" id="sales_person" class="form-control" placeholder="Interior Color">
    <option value="">Sales Person</option>
    @foreach ($sales as $sales)
      <option value="{{ $sales->id }}">{{ $sales->name }}</option>
    @endforeach
  </select>
</div>
@endif
      <div class="col-md-4">
        <label for="gdn_date">Payment Percentage</label>
        <select name="payment_percentage" id="payment_percentage" class="form-control">
                <option value="5%">5%</option>
                <option value="10%">10%</option>
                <option value="20%">20%</option>
                <option value="30%">30%</option>
                <option value="40%">40%</option>
                <option value="50%">50%</option>
                <option value="60%">60%</option>
                <option value="70%">70%</option>
                <option value="80%">80%</option>
                <option value="90%">90%</option>
                <option value="100%">100%</option>
    </select>
      </div>
@php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-reservation');
                        @endphp
                        @if ($hasPermission)
      <div class="col-md-12">
  <label for="sales_remarks">Sales Remarks</label>
    <input type="text" class="form-control" id="sales_remarks" name="remarks" value="">
</div>
@endif
</div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
    </div>
  </div>
</div>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
@endphp
@if ($hasPermission)
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var variant = row.find('.Variant').text();
  var Vin = row.find('.Vin').text();
  var grn = row.find('.grnNumber').text();
  var brand = row.find('.brand').text();
  var po = row.find('.PoNumber').text();
  var Engine = row.find('.Engine').text();
  var ExColour = row.find('.ExColour').val();
  var IntColour = row.find('.IntColour').val();
  var inspection = row.find('.inspection').val();
  var Ppmmyy = row.find('.Ppmmyy').val();
  console.log(inspection);
  $('#variants_name').val(variant);
  $('#vin').val(Vin);
  $('#po').val(po);
  $('#brand').val(brand);
  $('#grn').val(grn);
  $('#engine').val(Engine);
  $('#ex_colour').val(ExColour);
  $('#int_colour').val(IntColour);
  $('#ppmmyy').val(Ppmmyy);
  $('#inspection').val(inspection);
  $('#vehicle_id').val(vehicleId);
  $('#editModal').modal('show');
}
  function saveChanges() {
    var grnNumber = $('#grnNumber').val();
    var grnDate = $('#grnDate').val();
    $('#editModal').modal('hide');
  }
  function cancelChanges() {
    $('#editModal').modal('hide');
  }
  $('#editModal').on('click', '#saveButton', saveChanges);
  $('#editModal').on('click', '[data-dismiss="modal"]', cancelChanges);
</script>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('document-edit');
@endphp
@if ($hasPermission)
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var import_type = row.find('.import_type').text();
  var owership = row.find('.owership').text();
  var document_with = row.find('.document_with').text();
  var bl_status = row.find('.bl_status').text();
  $('#import_type').val(import_type);
  $('#owership').val(owership);
  $('#document_with').val(document_with);
  $('#bl_status').val(bl_status);
  $('#vehicle_id').val(vehicleId);
  $('#editModal').modal('show');
}
  function saveChanges() {
    var grnNumber = $('#grnNumber').val();
    var grnDate = $('#grnDate').val();
    $('#editModal').modal('hide');
  }
  function cancelChanges() {
    $('#editModal').modal('hide');
  }
  $('#editModal').on('click', '#saveButton', saveChanges);
  $('#editModal').on('click', '[data-dismiss="modal"]', cancelChanges);
</script>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
@endphp
@if ($hasPermission)
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var converstion = row.find('.so_number').text();
  var reservation_start_date = row.find('.reservation_start_date').text();
  $('#sales_person').val(sales_person);
  $('#payment_percentage').val(payment_percentage);
  $('#vehicle_id').val(vehicleId);
  $('#editModalso').modal('show');
}
  function saveChanges() {
    var grnNumber = $('#grnNumber').val();
    var grnDate = $('#grnDate').val();
    $('#editModalso').modal('hide');
  }
  function cancelChanges() {
    $('#editModalso').modal('hide');
  }
  $('#editModalso').on('click', '#saveButton', saveChanges);
  $('#editModalso').on('click', '[data-dismiss="modal"]', cancelChanges);
</script>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-edit');
@endphp
@if ($hasPermission)
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var remarks = row.find('.remarks').text();
  var conversion = row.find('.conversion').text();
  $('#remarks').val(remarks);
  $('#conversion').val(conversion);
  $('#vehicle_id').val(vehicleId);
  $('#editmodalwarehouse').modal('show');
}
  function saveChanges() {
    var grnNumber = $('#grnNumber').val();
    var grnDate = $('#grnDate').val();
    $('#editmodalwarehouse').modal('hide');
  }
  function cancelChanges() {
    $('#editmodalwarehouse').modal('hide');
  }
  $('#editmodalwarehouse').on('click', '#saveButton', saveChanges);
  $('#editmodalwarehouse').on('click', '[data-dismiss="modal"]', cancelChanges);
</script>
@endif
<script>
$(document).ready(function() {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  ordering: false,
  pageLength: 10,
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (columnName === "pictures" || columnName === "log") {
        return;
      }

      var selectWrapper = $('<div class="select-wrapper"></div>');
      var select = $('<select class="form-control my-1" multiple><option value="">All</option></select>')
        .appendTo(selectWrapper)
        .select2({
          width: '100%',
          dropdownCssClass: 'select2-blue'
        });

      var dropdownIcon = $('<span class="dropdown-icon"><i class="fas fa-caret-down"></i></span>')
        .appendTo(selectWrapper);

      dropdownIcon.on('click', function(e) {
        select.select2('open');
        e.stopPropagation();
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
  $('.dataTables_filter input').on('keyup', function() {
    dataTable.search(this.value).draw();
  });
});
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
    <script>
  var input = document.getElementById('variants_name');
  var dataList = document.getElementById('variantslist');
  input.addEventListener('input', function() {
    var inputValue = input.value;
    var options = dataList.getElementsByTagName('option');
    var matchFound = false;
    for (var i = 0; i < options.length; i++) {
      var option = options[i];

      if (inputValue === option.value) {
        matchFound = true;
        break;
      }
    }
    if (!matchFound) {
      input.setCustomValidity("Please select a value from the list.");
    } else {
      input.setCustomValidity('');
    }
  });
</script>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
