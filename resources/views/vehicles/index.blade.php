@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
#searchContainer {
      width: 25%;
      float: right;
    }
    #tableSearch {
      width: 100%;
      box-sizing: border-box;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 14px;
    }
    /* Custom pagination container styles */
#paginationContainer {
  float: right;
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
}

/* Custom pagination button styles */
#prevBtn,
#nextBtn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  background-color: #007bff;
  color: #fff;
  cursor: pointer;
  font-size: 14px;
}
/* Custom page info styles */
#pageInfo {
  display: inline-block;
  padding: 8px;
  font-size: 14px;
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin: 0 10px;
}
#prevBtn:hover,
#nextBtn:hover {
  background-color: #0056b3;
}
  .editable-field[contenteditable="true"] {
    background-color: white !important;
    border: 1px solid black  !important;
  }
      .table-responsive {
      overflow: auto;
      max-height: 650px; /* Adjust the max-height to your desired value */
    }
    .table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1; /* Ensure the table header is on top of other elements */
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 14px;
    }
    th.nowrap-td {
      white-space: nowrap;
    }
    .nowrap-td {
        white-space: nowrap;
      }
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
            @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warehouse-edit','conversion-edit',
                     'document-edit','edit-so','edit-reservation']);
                @endphp
                @if ($hasPermission)
                <h4 class="card-title">Pending Vehicles Updates</h4>
                @endif
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
                @endphp
                @if ($hasPermission)
                <h4 class="card-title">Vehicles Status</h4>
                @endif
                <div id="flash-message" class="alert alert-success" style="display: none;"></div>
                <div class="row">
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warehouse-edit','conversion-edit',
                     'document-edit','edit-so','edit-reservation']);
                @endphp
                @if ($hasPermission)
                <div class="col-lg-3 col-md-3 col-sm-12 table-responsive">
                    <table class="table table-striped table-editable table-edits table table-bordered">
                        <thead>
                            <th style="font-size: 12px;">Vehicle Detail Approval</th>
                            <th style="font-size: 12px;">Vehicle QTY</th>
                        </thead>
                        <tbody>
                            <tr  onclick="window.location.href = '{{ route('vehicle-detail-approvals.index') }}'">
                                <td style="font-size: 12px;">
                                    <a href="{{ route('vehicle-detail-approvals.index') }}">
                                        Pending Vehicle Details
                                    </a>
                                </td>
                                <td style="font-size: 12px;">{{ $pendingVehicleDetailForApprovalCount }}</td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                @endphp
                @if ($hasPermission)
  <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
  <table class="table table-striped table-editable table-edits table-bordered">
    <thead>
      <tr>
        <th rowspan="2" style="font-size: 12px; vertical-align: middle;">Vehicle Status</th>
        <th colspan="{{$countwarehouse}}" style="font-size: 12px; text-align: center;">Vehicle Quantity</th>
      </tr>
      <tr>
      @foreach ($warehouses as $warehouses)
        <th style="font-size: 12px;">{{$warehouses->name}}</th>
       @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;">
            Pending Inspection
        </td>
        @php
        $incomingvehicless = DB::table('vehicles')->where('payment_status', 'Incoming Stock')->whereNull('grn_id')->count();
        @endphp
        @foreach ($warehousesveh as $warehousesveh)
        @php
        $pendinginspection = DB::table('vehicles')->where('latest_location', $warehousesveh->id)
            ->whereNull('inspection_date')
            ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehicleinspectionpending.pendinginspection', ['warehouse_id' => $warehousesveh->id]) }}'" style="font-size: 12px;">{{ $pendinginspection }}</td>
        @endforeach
      </tr>
      <tr>
        <td style="font-size: 12px;">
            Pending Inspection Approval
        </td>
        @foreach ($warehousesveher as $warehousesveher)
        @php
        $fieldValues = ['ex_colour', 'int_colour', 'variants_id', 'ppmmyyy', 'inspection_date', 'engine'];
        $countpendings = DB::table('vehicles')
        ->join('vehicle_detail_approval_requests', 'vehicles.id', '=', 'vehicle_detail_approval_requests.vehicle_id')
        ->where('vehicle_detail_approval_requests.status', '=', 'Pending')
        ->where('vehicles.latest_location', '=', "$warehousesveher->id")
        ->where(function ($query) use ($fieldValues) {
        $query->whereIn('field', $fieldValues);
         })
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehicleinspectionapprovals.pendingapprovals', ['warehouse_id' => $warehousesveher->id]) }}'" style="font-size: 12px;">{{ $countpendings }}</td>
        @endforeach
      </tr>
      <tr>
        <td style="font-size: 12px;">
            Incoming Stock
        </td>
        <td onclick="window.location.href = '{{ route('vehiclesincoming.stock') }}'" colspan="{{$countwarehouse}}" style="font-size: 12px; text-align: center;">{{$incomingvehicless}}</td>
      </tr>
    </tbody>
  </table>
</div>
@endif
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
    @endphp
    @if ($hasPermission)
        <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
            <table class="table table-striped table-editable table-edits table-bordered">
                <thead>
                    <tr>
                        <th rowspan="2" style="font-size: 12px; vertical-align: middle;">Time Frame</th>
                        <th colspan="4" style="font-size: 12px; text-align: center;">Stock Status</th>
                    </tr>
                    <tr>
                        <th style="font-size: 12px;">Purchased</th>
                        <th style="font-size: 12px;">Sold</th>
                        <th style="font-size: 12px;">Booked</th>
                        <th style="font-size: 12px;">Available</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size: 12px;">
                            Previous Year
                        </td>
                        <td style="font-size: 12px;">
                        @php
                        $currentYear = \Carbon\Carbon::now()->year;
                        $previousYear = $currentYear - 1;
                        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
                        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
                        $countpreviouseyear = \Illuminate\Support\Facades\DB::table('vehicles')
                            ->whereExists(function ($query) use ($startDate, $endDate) {
                                $query->select(DB::raw(1))
                                    ->from('grn')
                                    ->whereColumn('grn.id', '=', 'vehicles.grn_id')
                                    ->whereBetween('grn.date', [$startDate, $endDate]);
                            })
                            ->count();
                              @endphp
                              {{$countpreviouseyear}}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_YEAR_SOLD]) }}';">
                            {{$previousYearSold}}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_YEAR_BOOKED]) }}';">
                            {{$previousYearBooked}}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_YEAR_AVAILABLE]) }}';">
                              {{ $previousYearAvailable }}
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">
                            Previous Month
                        </td>
                        <td style="font-size: 12px;">
                            @php
                            $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
                            $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

                            $countLastMonth = \Illuminate\Support\Facades\DB::table('vehicles')
                                ->whereExists(function ($query) use ($startDateLastMonth, $endDateLastMonth) {
                                    $query->select(DB::raw(1))
                                        ->from('grn')
                                        ->whereColumn('grn.id', '=', 'vehicles.grn_id')
                                        ->whereBetween('grn.date', [$startDateLastMonth, $endDateLastMonth]);
                                })
                                ->count();
                            @endphp
                        {{$countLastMonth}}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_MONTH_SOLD]) }}';">
                            {{ $previousMonthSold }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_MONTH_BOOKED]) }}';">
                            {{ $previousMonthBooked }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_MONTH_AVAILABLE]) }}';">
                            {{ $previousMonthAvailable }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">
                          Yesterday
                        </td>
                        <td style="font-size: 12px;">
                            @php
                                $startDateLastDay = \Carbon\Carbon::now()->subDay(1)->startOfDay();
                                $endDateLastDay = \Carbon\Carbon::now()->subDay(1)->endOfDay();

                                $countLastDay = \Illuminate\Support\Facades\DB::table('vehicles')
                                    ->whereExists(function ($query) use ($startDateLastDay, $endDateLastDay) {
                                        $query->select(DB::raw(1))
                                            ->from('grn')
                                            ->whereColumn('grn.id', '=', 'vehicles.grn_id')
                                            ->whereBetween('grn.date', [$startDateLastDay, $endDateLastDay]);
                                    })
                                    ->count();
                            @endphp
                            {{$countLastDay}}

                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_YESTERDAY_SOLD]) }}';">
                            {{ $yesterdaySold }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_YESTERDAY_BOOKED]) }}';">
                            {{ $yesterdayBooked }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_YESTERDAY_AVAILABLE]) }}';">
                            {{$yesterdayAvailable}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
  </div>
  @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['inspection-edit','warehouse-edit','conversion-edit',
     'vehicles-detail-edit','enginee-edit','document-edit','edit-so','edit-reservation']);
@endphp
@if ($hasPermission)
<a href="#" class="btn btn-sm btn-primary float-end edit-btn">Edit</a>
<a href="#" class="btn btn-sm btn-success float-end update-btn" style="display: none;">Update</a>
@endif
<br>
<br>
<div id="searchContainer" class="mb-3">
      <!-- Add your full-width search bar or input here -->
      <input type="text" id="tableSearch" placeholder="Search Table">
    </div>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif

            </div>
            <form id="form-update" method="POST" >
                @csrf
                @foreach($data as $value => $vehicle)
                    <input type="hidden" value="{{ $vehicle->id }}" name="vehicle_ids[]">
                @endforeach
                    <div class="card-body">
                        <div class="table-responsive" >
                            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                            <thead class="bg-soft-secondary">
                               <tr>
                                <th>Ref No</th>
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
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
                                @endphp
                                @if ($hasPermission)
                                <th class="nowrap-td">Stock Status</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-view');
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
                                    <th class="nowrap-td">SO Date</th>
                                    @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                @endphp
                                @if ($hasPermission)
                                <th class="nowrap-td">Sales Person</th>
                                    <th class="nowrap-td">Reservation Date</th>
                                    <th class="nowrap-td">Reservation Due Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                                @endphp
                                @if ($hasPermission)
                                    <th id="sales_remarks" style="vertical-align: middle;" class="nowrap-td">Sales Remarks</th>
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
                                    <th id="variant" style="vertical-align: middle;" class="nowrap-td">Variant</th>
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
                                    <th class="nowrap-td">Model Year</th>
                                    <th class="nowrap-td">Steering</th>
                                    <th class="nowrap-td">Seats</th>
                                    <th class="nowrap-td">Fuel Type</th>
                                    <th class="nowrap-td">Transmission</th>
                                    <th class="nowrap-td" id="ex-colour" style="vertical-align: middle;" style="min-width:150px">Ext Colour</th>
                                    <th class="nowrap-td" id="int-colour"  style="vertical-align: middle;" style="min-width:150px">Int Colour</th>
                                    <th class="nowrap-td">Upholstery</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                                @endphp
                                @if ($hasPermission)
                                    <th class="nowrap-td">Production Year</th>
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
                                    <th id="warehouseremarks" style="vertical-align: middle;" class="nowrap-td">Warehouse Remarks</th>
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
                               <th id="importdoc" class="nowrap-td" style="vertical-align: middle;">Import Document Type</th>
                               <th id="ownership" class="nowrap-td" style="vertical-align: middle;">Document Ownership</th>
                                    <th id="documentwith" class="nowrap-td" style="vertical-align: middle;">Documents With</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
                                @endphp
                                @if ($hasPermission)
                                    <th class="nowrap-td">BL Number</th>
                                @endif
                                    <th id="changelogs" class="nowrap-td"id="log" style="vertical-align: middle;">Details</th>
                               </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                                @foreach ($data as $vehicles)
                                <tr>
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
                                     if($po) {

                                          $po_date = $po->po_date;
                                          $po_number = $po->po_number;
                                     }else{
                                          $po_date = "";
                                          $po_number = "";
                                     }

                                     $exColour = $vehicles->ex_colour ? DB::table('color_codes')->where('id', $vehicles->ex_colour)->first() : null;
                                     $ex_colours = $exColour ? $exColour->name : null;
                                     $intColour = $vehicles->int_colour ? DB::table('color_codes')->where('id', $vehicles->int_colour)->first() : null;
                                     $int_colours = $intColour ? $intColour->name : null;
                                     $variants = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                                     if($variants) {
                                     $name = $variants->name;

                                     }
                                     $grn = $vehicles->grn_id ? DB::table('grn')->where('id', $vehicles->grn_id)->first() : null;
                                     $grn_date = $grn ? $grn->date : null;
                                     $grn_number = $grn ? $grn->grn_number : null;
                                     $gdn = $vehicles->gdn_id ? DB::table('gdn')->where('id', $vehicles->gdn_id)->first() : null;
                                     $gdn_date = $gdn ? $gdn->date : null;
                                     $gdn_number = $gdn ? $gdn->gdn_number : null;
                                     $so = $vehicles->so_id ? DB::table('so')->where('id', $vehicles->so_id)->first() : null;
                                     $so_number = $so ? $so->so_number : null;
                                     $so_date = $so ? $so->so_date : null;
                                     $sales_person_id = $so ? $so->sales_person_id : null;
                                    $sales_person = $sales_person_id ? DB::table('users')->where('id', $sales_person_id)->first() : null;
                                    $salesname = $sales_person ? $sales_person->name : null;
//                                    $booking = $vehicles->booking_id ? DB::table('booking')->where('id', $vehicles->booking_id)->first() : null;
//                                    $booking_name = $booking ? $booking->name : null;
                                    $warehouse = $vehicles->vin ? DB::table('movements')->where('vin', $vehicles->vin)->latest()->first() : null;
                                    $warehouses = $warehouse ? DB::table('warehouse')->where('id', $warehouse->to)->value('name') : null;
                                     $result = DB::table('varaints')
                                                ->join('brands', 'varaints.brands_id', '=', 'brands.id')
                                                ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                                                ->where('varaints.id', $vehicles->varaints_id)
                                                ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
                                                ->first();
                                                 if($result) {
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
                                                 }

                                                $documents = $vehicles->documents_id ? DB::table('documents')->where('id', $vehicles->documents_id)->first() : null;
                                                $import_type = $documents ? $documents->import_type : null;
                                                $owership = $documents ? $documents->owership : null;
                                                $document_with = $documents ? $documents->document_with : null;
                                                $bl_number = $documents ? $documents->bl_number : null;
                                                $latestRemarksales = DB::table('vehicles_remarks')->where('vehicles_id', $vehicles->id)->where('department', 'warehouse')->orderBy('created_at', 'desc')->value('remarks');
                                                $latestRemarkwarehouse = DB::table('vehicles_remarks')->where('vehicles_id', $vehicles->id)->where('department', 'sales')->orderBy('created_at', 'desc')->value('remarks');
                                                @endphp
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                                    @endphp
                                    <td>{{ $vehicles->id }}</td>
                                    {{--<td>{{$vehicles->id}}</td>--}}
                                    @if ($hasPermission)
                                     <td class="nowrap-td PoNumber">PO - {{ $po_number }}</td>
                                     <td class="nowrap-td PoDate">{{ date('d-M-Y', strtotime($po_date)) }}</td>
                                     @endif
                                     @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('ETA-timer-view');
                                @endphp
                                @if ($hasPermission)
                                @if($vehicles->estimation_date && $grn_number === null)
                                @php
                                                  $savedDate = $$vehicles->estimation_date;
                                                  $today = now()->format('Y-m-d');
                                                  $numberOfDayseta = \Carbon\Carbon::parse($savedDate)->diffInDays($today);
                                                  @endphp
                                <td class="nowrap-td eta">$numberOfDayseta</td>
                                @elseif($grn_number)
                                <td class="nowrap-td eta">Arrived</td>
                                @else
                                <td class="nowrap-td eta">Incoming</td>
                                @endif
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view');
                                @endphp
                                @if ($hasPermission)
                                @if($vehicles->estimation_date)
                                <td class="nowrap-td eta">{{date('d-M-Y', strtotime($vehicles->estimation_date))}}</td>
                                @else
                                <td class="nowrap-td">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       </td>
                                @endif
                                @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                                    @endphp
                                    @if ($hasPermission)
                                     @if ($grn_number)
                                     <td class="nowrap-td grnNumber">GRN - {{ $grn_number }}</td>
                                     @else
                                     <td class="nowrap-td grnNumber"></td>
                                     @endif
                                     @if ($grn_date)
                                     <td class="nowrap-td grnDate">
                                     {{ date('d-M-Y', strtotime($grn_date)) }}</td>
                                     @else
                                     <td class="nowrap-td grnDate"></td>
                                    @endif
                                    @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
                                @endphp
                                @if ($hasPermission)
                                @if($grn_number === null)
                                <td class="nowrap-td stockstatus">Incoming</td>
                                @elseif($so_number === null && $vehicles->reservation_end_date && $vehicles->reservation_end_date->greaterThan(\Carbon\Carbon::now()))
                                <td class="nowrap-td stockstatus">Reserved</td>
                                @elseif($so_number)
                                <td class="nowrap-td stockstatus">Booked</td>
                                @elseif ($gdn_number)
                                <td class="nowrap-td stockstatus">Sold</td>
                                @else
                                <td class="nowrap-td stockstatus">Available</td>
                                    @endif
                                    @endif
                                    @endif
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-view');
                                    @endphp
                                    @if ($hasPermission)
									                  @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-edit');
                                    @endphp
                                    @if ($hasPermission)
                                    <td class="editable-field inspection_date" data-is-date="true" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}" data-type="date" data-field-name="inspection_date">{{ $vehicles->inspection_date }}</td>
                                    @else
									                  <td>{{ $vehicles->inspection_date }}</td>
									                  @endif
									                  @endif
                                    @php
                                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                                      @endphp
                                      @if ($hasPermission)
                                          {{-- If $grn_date is set and $gdn_number is null --}}
                                          @if ($grn_date && $gdn_date === null)
                                              @php
                                              $grn_date = \Carbon\Carbon::parse($grn_date);
                                              $aging = $grn_date->diffInDays(\Carbon\Carbon::today());
                                              @endphp
                                              <td class="nowrap-td">{{ $aging }}</td>
                                          {{-- If $gdn_number is set, calculate aging as the difference between $grn_date and $gdn_number --}}
                                          @elseif ($gdn_date)
                                              @php
                                              $aging = \Carbon\Carbon::parse($grn_date)->diffInDays($gdn_date);
                                              @endphp
                                              <td class="nowrap-td">{{ $aging }}</td>

                                          {{-- If neither $grn_date nor $gdn_number is set, check for payment logs --}}
                                          @else
                                              @php
                                              $paymentLog = DB::table('payment_logs')->where('vehicle_id', $vehicles->id)->latest()->first();
                                              @endphp
                                              @if ($paymentLog)
                                                  @php
                                                  $savedDate = $paymentLog->date;
                                                  $today = now()->format('Y-m-d');
                                                  $numberOfDays = \Carbon\Carbon::parse($savedDate)->diffInDays($today);
                                                  @endphp
                                                  <td class="nowrap-td">{{ $numberOfDays }}</td>
                                              @else
                                                  <td class="nowrap-td">-</td>
                                              @endif
                                          @endif

                                      @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                                    @endphp
                                    @if ($hasPermission)
									                  @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
                                    @endphp
                                    @if ($hasPermission)
                                     <td class="editable-field so_number" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $so_number }}</td>
                                     <td class="editable-field so_date" data-is-date="true" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}" data-type="date" data-field-name="so_date">{{ $so_date }}</td>
									                    @else
									                    <td>{{ $so_number }}</td>
                                     <td>{{ $so_date }}</td>
                                     @endif
									 @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                    @endphp
                                    @if ($hasPermission)
									@php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-reservation');
                                    @endphp
                                    @if ($hasPermission)
                                    <td class="editable-field reservation_start_date" data-is-date="true" data-type="date" data-vehicle-id="{{ $vehicles->id }}" data-field-name="reservation_start_dates">
                                    {{ $vehicles->reservation_start_date }}</td>
                                    <td class="editable-field reservation_end_date" data-is-date="true" data-type="date" data-vehicle-id="{{ $vehicles->id }}" data-field-name="reservation_end_date">
                                    {{ $vehicles->reservation_end_date }}</td>
									 @else   
								    <td>
                                    {{ $vehicles->reservation_start_date }}</td>
                                    <td>
                                    {{ $vehicles->reservation_end_date }}</td>
									@endif
									@php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('approve-reservation');
                                    @endphp
                                    @if ($hasPermission)
									                  <td class="editable-field sales_person_id" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="sales_person_id" class="form-control" placeholder="sales_person_id" disabled>
                                                <option value=""></option>
                                                @foreach ($sales as $sale)
                                                    <option value="{{ $sale->id }} " {{ $salesname == $sale->name ? 'selected' : '' }}>{{ $sale->name }}</option>
                                                @endforeach
                                            </select>
                                    </td>
									@else
                                    <td>
                                        <select name="sales_person_id" class="form-control" placeholder="sales_person_id" disabled>
                                                <option value=""></option>
                                                @foreach ($sales as $sale)
                                                    <option value="{{ $sale->id }} " {{ $salesname == $sale->name ? 'selected' : '' }}>{{ $sale->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
									@endif
                                    @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                                    @endphp
                                    @if ($hasPermission)
										                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
                                    @endphp
                                    @if ($hasPermission)
                                    <td class="editable-field sales-remarks" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{$latestRemarkwarehouse}}
                                            @if($latestRemarkwarehouse)
                                            <br>
                                                <a href="{{ route('vehiclesremarks.viewremarks', $vehicles->id) }}" class="read-more" target="_blank">View All</a>
                                            @endif
                                        </td>
									                @else
									                <td>{{$latestRemarkwarehouse}}
                                            @if($latestRemarkwarehouse)
                                            <br>
                                                <a href="{{ route('vehiclesremarks.viewremarks', $vehicles->id) }}" class="read-more" target="_blank">View All</a>
                                            @endif
                                    </td>
									                  @endif
                                    @endif
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
                                    @endphp
                                    @if ($hasPermission)
                                     @if ($gdn_number)
                                     <td class="nowrap-td gdnNumber">GDN - {{ $gdn_number }}</td>
                                     @else
                                     <td class="nowrap-td gdnNumber"></td>
                                     @endif
                                         @if ($gdn_date)
                                            <td class="nowrap-td gdnDate">{{ date('d-M-Y', strtotime($gdn_date)) }}</td>
                                            @else
                                            <td class="nowrap-td gdnDate"></td>
                                         @endif
                                     @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                    @endphp
                                    @if ($hasPermission)
									                  @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                                    @endphp
                                    @if ($hasPermission)
                                     <td class="nowrap-td brand" id="brand-{{$vehicles->id}}">
                                        {{ $vehicles->variant->brand->brand_name ?? ''}}
                                     </td>
                                     <td class="nowrap-td" id="model-line-{{$vehicles->id}}">
                                           {{$vehicles->variant->master_model_lines->model_line ?? ''}}
                                     </td>
                                     <td class="nowrap-td" id="model-description-{{$vehicles->id}}">
                                             {{ $vehicles->variant->model_detail ?? '' }}
                                     </td>
                                     <td class="editable-field varaints_id" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="varaints_id" class="form-control" placeholder="varaints_id" disabled>
                                            @foreach($varaint as $variantItem)
                                                @if ($variantItem->master_model_lines_id == $vehicles->variant->master_model_lines_id)
                                                    <option value="{{$variantItem->id}}" {{ $variantItem->id == $vehicles->varaints_id ? "selected" : "" }}>
                                                        {{ $variantItem->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                     <td class="nowrap-td" id="variant-detail-{{ $vehicles->id }}">
                                             {{ $vehicles->detail ?? '' }}
                                     </td>
                                    @else
                                      <td class="nowrap-td brand" id="brand-{{$vehicles->id}}">
                                    {{ $vehicles->variant->brand->brand_name ?? ''}}
                                     </td>
                                     <td class="nowrap-td" id="model-line-{{$vehicles->id}}">
                                     
                                           {{$vehicles->variant->master_model_lines->model_line ?? ''}}
                                     </td>
                                     <td class="nowrap-td" id="model-description-{{$vehicles->id}}">
                                             {{ $vehicles->variant->model_detail ?? '' }}
                                     </td>
                                     <td>
                                    <select name="varaints_id" class="form-control" placeholder="varaints_id" disabled>
                                    @foreach($varaint as $variantItem)
                                         <option value="{{$variantItem->id}}" {{ $variantItem->id == $vehicles->varaints_id ? "selected" : "" }}>
                                             {{ $variantItem->name }}</option>
                                    @endforeach
                                    </select>
                                    </td>
                                     <td class="nowrap-td" id="variant-detail-{{ $vehicles->id }}">
                                             {{ $vehicles->detail ?? '' }}
                                     </td>
                                     @endif
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
										                  @php
                                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-edit');
                                      @endphp
                                      @if ($hasPermission)
                                      <td class="editable-field conversion" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->conversion }}</td>
                                      @else
                                      <td>{{ $vehicles->conversion }}</td>
                                      @endif
                                      @endif
                                      @php
                                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
                                      @endphp
                                      @if ($hasPermission)
										                  @php
                                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-edit');
                                      @endphp
                                      @if ($hasPermission)
                                      <td class="editable-field engine" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                                     @else
                                    <td>{{ $vehicles->engine }}</td>
                                    @endif
                                    @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                    @endphp
                                    @if ($hasPermission)
										                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                                    @endphp
                                    @if ($hasPermission)
                                     <td class="nowrap-td" id="my-{{ $vehicles->id }}">
                                             {{ $vehicles->variant->my }}
                                         </td>
                                        <td class="nowrap-td" id="steering-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->steering }}
                                        </td>
                                        <td class="nowrap-td" id="seat-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->seat }}
                                        </td>
                                        <td class="nowrap-td" id="fuel-type-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->fuel_type }}
                                        </td>
                                        <td class="nowrap-td" id="gearbox-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->gearbox }}
                                        </td>
                                        <td class="editable-field ex_colour" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="ex_colour" class="form-control" placeholder="ex_colour" disabled>
                                                <option value=""></option>
                                                @foreach($exteriorColours as $exColour)
                                                    <option value="{{$exColour->id}} " {{ $exColour->id == $vehicles->ex_colour ? 'selected' : "" }}   >
                                                        {{ $exColour->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="editable-field int_colour" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="int_colour" class="form-control" placeholder="int_colour" disabled>
                                                <option value=""></option>
                                                @foreach($interiorColours as $interiorColour)
                                                    <option value="{{$interiorColour->id}} " {{ $interiorColour->id == $vehicles->int_colour ? 'selected' : "" }}   >
                                                        {{ $interiorColour->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="nowrap-td Upholestry" id="upholestry-{{ $vehicles->id }}">
                                        {{ $vehicles->variant->upholestry ?? '' }}
                                        </td>
										                    @else
										                    <td class="nowrap-td" id="my-{{ $vehicles->id }}">
                                        {{ $vehicles->variant->my }}
                                         </td>
                                        <td class="nowrap-td" id="steering-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->steering }}
                                        </td>
                                        <td class="nowrap-td" id="seat-{{ $vehicles->id }}">
                                        {{ $vehicles->variant->seat }}
                                        </td>
                                        <td class="nowrap-td" id="fuel-type-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->fuel_type }}
                                        </td>
                                        <td class="nowrap-td" id="gearbox-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->gearbox }}
                                        </td>
                                        <td>
                                        <select name="ex_colour" class="form-control" placeholder="ex_colour" disabled>
                                                <option value=""></option>
                                                @foreach($exteriorColours as $exColour)
                                                    <option value="{{$exColour->id}} " {{ $exColour->id == $vehicles->ex_colour ? 'selected' : "" }}   >
                                                        {{ $exColour->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                        <select name="int_colour" class="form-control" placeholder="int_colour" disabled>
                                                <option value=""></option>
                                                @foreach($interiorColours as $interiorColour)
                                                    <option value="{{$interiorColour->id}} " {{ $interiorColour->id == $vehicles->int_colour ? 'selected' : "" }}   >
                                                        {{ $interiorColour->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="nowrap-td Upholestry" id="upholestry-{{ $vehicles->id }}">
                                        {{ $vehicles->variant->upholestry ?? '' }}
                                        </td>
                                        @endif
										                    @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                                        @endphp
                                        @if ($hasPermission)
										                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                                        @endphp
                                        @if ($hasPermission)
                                        <td class="editable-field ppmmyyy" data-is-date="true" data-type="date" contenteditable="false" data-field-name="ppmmyyy" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->ppmmyyy }}</td>
                                        @else
                                      <td>{{ $vehicles->ppmmyyy }}</td>
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
                                        @if ($warehouses === null)
                                        <td class="nowrap-td">Supplier</td>
                                        @else
                                        <td class="nowrap-td">{{ $warehouses }}</td>
                                        @endif
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-remarks-view');
                                        @endphp
                                        @if ($hasPermission)
										                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-edit');
                                        @endphp
                                        @if ($hasPermission)

                                        <td class="editable-field warehouse-remarks" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $latestRemarksales }}
                                            @if($latestRemarksales)
                                            <br>
                                                <a href="{{ route('vehiclesremarks.viewremarks', ['id' => $vehicles->id, 'type' => 'WareHouse'] ) }}" class="read-more" target="_blank">View All</a>
                                            @endif
                                        </td>
                                        @else
                                          <td>{{ $latestRemarksales }}
                                            @if($latestRemarksales)
                                            <br>
                                                <a href="{{ route('vehiclesremarks.viewremarks', ['id' => $vehicles->id, 'type' => 'WareHouse'] ) }}" class="read-more" target="_blank">View All</a>
                                            @endif
                                        </td>
										                    @endif
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
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-edit');
                                        @endphp
                                        @if ($hasPermission)
                                        <td class="editable-field import_type" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="import_type" class="form-control" placeholder="import_type" disabled>
                                                <option value=""></option>
                                                <option value="Belgium Docs" {{ $import_type == 'Belgium Docs' ? 'selected' : ''}}>Belgium Docs</option>
                                                <option value="BOE + VCC + Exit" {{ $import_type == 'BOE + VCC + Exit' ? 'selected' : ''}}>BOE + VCC + Exit</option>
                                                <option value="Cross Trade" {{ $import_type == 'Cross Trade' ? 'selected' : ''}}>Cross Trade</option>
                                                <option value="Dubai Trade" {{ $import_type == 'Dubai Trade' ? 'selected' : ''}}>Dubai Trade</option>
                                                <option value="Incoming" {{ $import_type == 'Incoming' ? 'selected' : ''}}>Incoming</option>
                                                <option value="No Records" {{ $import_type == 'No Records' ? 'selected' : ''}}>No Records</option>
                                                <option value="RTA Possession" {{ $import_type == 'RTA Possession' ? 'selected' : ''}}>RTA Possession</option>
                                                <option value="RTA Registration" {{ $import_type == 'RTA Registration' ? 'selected' : ''}}>RTA Registration</option>
                                                <option value="Supplier Docs" {{ $import_type == 'Supplier Docs' ? 'selected' : ''}}>Supplier Docs</option>
                                                <option value="VCC" {{ $import_type == 'VCC' ? 'selected' : ''}}>VCC</option>
                                                <option value="Zimbabwe" {{ $import_type == 'Zimbabwe' ? 'selected' : ''}}>Zimbabwe</option>
                                            </select>
                                      {{--{{ $import_type }}--}}
                                        </td>
                                        <td class="editable-field owership" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="owership" class="form-control" placeholder="owership" disabled>
                                                <option value=""></option>
                                                <option value="Abdul Azeem" {{ $owership == 'Abdul Azeem' ? 'selected' : ''  }}>Abdul Azeem</option>
                                                <option value="Barwil Supplier" {{ $owership == 'Barwil Supplier' ? 'selected' : ''  }}>Barwil Supplier</option>
                                                <option value="Belgium Warehouse" {{ $owership == 'Belgium Warehouse' ? 'selected' : ''  }}>Belgium Warehouse</option>
                                                <option value="Faisal Raiz" {{ $owership == 'Faisal Raiz' ? 'selected' : ''  }}>Faisal Raiz</option>
                                                <option value="Feroz Riaz" {{ $owership == 'Feroz Riaz' ? 'selected' : ''  }}>Feroz Riaz</option>
                                                <option value="Globelink Supplier" {{ $owership == 'Globelink Supplier' ? 'selected' : ''  }}>Globelink Supplier</option>
                                                <option value="Incoming" {{ $owership == 'Incoming' ? 'selected' : ''  }}>Incoming</option>
                                                <option value="Milele" {{ $owership == 'Milele' ? 'selected' : ''  }}>Milele</option>
                                                <option value="Milele Car Trading LLC" {{ $owership == 'Milele Car Trading LLC' ? 'selected' : ''  }}>Milele Car Trading LLC</option>
                                                <option value="Milele Motors FZE" {{ $owership == 'Milele Motors FZE' ? 'selected' : ''  }}>Milele Motors FZE</option>
                                                <option value="Oneworld Limousine" {{ $owership == 'Oneworld Limousine' ? 'selected' : ''  }}>Oneworld Limousine</option>
                                                <option value="Supplier" {{ $owership == 'Supplier' ? 'selected' : ''  }}>Supplier</option>
                                                <option value="Trans Car FZE" {{ $owership == 'Trans Car FZE' ? 'selected' : ''  }}>Trans Car FZE</option>
                                                <option value="Zimbabwe Docs" {{ $owership == 'Zimbabwe Docs' ? 'selected' : ''  }}>Zimbabwe Docs</option>
                                            </select>
                                     {{--{{ $owership }}--}}
                                        </td>
                                        <td class="editable-field document_with" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="document_with" class="form-control" placeholder="document_with" disabled>
                                                <option value=""></option>
                                                <option value="Accounts" {{ $document_with == 'Accounts' ? 'selected' : ''  }}>Accounts</option>
                                                <option value="Finance Department" {{ $document_with == 'Finance Department' ? 'selected' : ''  }}>Finance Department</option>
                                                <option value="Import Department" {{ $document_with == 'Import Department' ? 'selected' : ''  }}>Import Department</option>
                                                <option value="Not Applicable" {{ $document_with == 'Not Applicable' ? 'selected' : ''  }}>Not Applicable</option>
                                                <option value="Supplier" {{ $document_with == 'Supplier' ? 'selected' : ''  }}>Supplier</option>
                                            </select>
                                    {{--{{ $document_with }}--}}
                                        </td>
                                        @else
                                        <td>
                                        <select name="import_type" class="form-control" placeholder="import_type" disabled>
                                                <option value=""></option>
                                                <option value="Belgium Docs" {{ $import_type == 'Belgium Docs' ? 'selected' : ''}}>Belgium Docs</option>
                                                <option value="BOE + VCC + Exit" {{ $import_type == 'BOE + VCC + Exit' ? 'selected' : ''}}>BOE + VCC + Exit</option>
                                                <option value="Cross Trade" {{ $import_type == 'Cross Trade' ? 'selected' : ''}}>Cross Trade</option>
                                                <option value="Dubai Trade" {{ $import_type == 'Dubai Trade' ? 'selected' : ''}}>Dubai Trade</option>
                                                <option value="Incoming" {{ $import_type == 'Incoming' ? 'selected' : ''}}>Incoming</option>
                                                <option value="No Records" {{ $import_type == 'No Records' ? 'selected' : ''}}>No Records</option>
                                                <option value="RTA Possession" {{ $import_type == 'RTA Possession' ? 'selected' : ''}}>RTA Possession</option>
                                                <option value="RTA Registration" {{ $import_type == 'RTA Registration' ? 'selected' : ''}}>RTA Registration</option>
                                                <option value="Supplier Docs" {{ $import_type == 'Supplier Docs' ? 'selected' : ''}}>Supplier Docs</option>
                                                <option value="VCC" {{ $import_type == 'VCC' ? 'selected' : ''}}>VCC</option>
                                                <option value="Zimbabwe" {{ $import_type == 'Zimbabwe' ? 'selected' : ''}}>Zimbabwe</option>
                                            </select>
                                      {{--{{ $import_type }}--}}
                                        </td>
                                        <td>
                                        <select name="owership" class="form-control" placeholder="owership" disabled>
                                                <option value=""></option>
                                                <option value="Abdul Azeem" {{ $owership == 'Abdul Azeem' ? 'selected' : ''  }}>Abdul Azeem</option>
                                                <option value="Barwil Supplier" {{ $owership == 'Barwil Supplier' ? 'selected' : ''  }}>Barwil Supplier</option>
                                                <option value="Belgium Warehouse" {{ $owership == 'Belgium Warehouse' ? 'selected' : ''  }}>Belgium Warehouse</option>
                                                <option value="Faisal Raiz" {{ $owership == 'Faisal Raiz' ? 'selected' : ''  }}>Faisal Raiz</option>
                                                <option value="Feroz Riaz" {{ $owership == 'Feroz Riaz' ? 'selected' : ''  }}>Feroz Riaz</option>
                                                <option value="Globelink Supplier" {{ $owership == 'Globelink Supplier' ? 'selected' : ''  }}>Globelink Supplier</option>
                                                <option value="Incoming" {{ $owership == 'Incoming' ? 'selected' : ''  }}>Incoming</option>
                                                <option value="Milele" {{ $owership == 'Milele' ? 'selected' : ''  }}>Milele</option>
                                                <option value="Milele Car Trading LLC" {{ $owership == 'Milele Car Trading LLC' ? 'selected' : ''  }}>Milele Car Trading LLC</option>
                                                <option value="Milele Motors FZE" {{ $owership == 'Milele Motors FZE' ? 'selected' : ''  }}>Milele Motors FZE</option>
                                                <option value="Oneworld Limousine" {{ $owership == 'Oneworld Limousine' ? 'selected' : ''  }}>Oneworld Limousine</option>
                                                <option value="Supplier" {{ $owership == 'Supplier' ? 'selected' : ''  }}>Supplier</option>
                                                <option value="Trans Car FZE" {{ $owership == 'Trans Car FZE' ? 'selected' : ''  }}>Trans Car FZE</option>
                                                <option value="Zimbabwe Docs" {{ $owership == 'Zimbabwe Docs' ? 'selected' : ''  }}>Zimbabwe Docs</option>
                                            </select>
                                     {{--{{ $owership }}--}}
                                        </td>
                                        <td class="filterable-column">
                                        <select name="document_with" class="form-control" placeholder="document_with" disabled>
                                                <option value=""></option>
                                                <option value="Accounts" {{ $document_with == 'Accounts' ? 'selected' : ''  }}>Accounts</option>
                                                <option value="Finance Department" {{ $document_with == 'Finance Department' ? 'selected' : ''  }}>Finance Department</option>
                                                <option value="Import Department" {{ $document_with == 'Import Department' ? 'selected' : ''  }}>Import Department</option>
                                                <option value="Not Applicable" {{ $document_with == 'Not Applicable' ? 'selected' : ''  }}>Not Applicable</option>
                                                <option value="Supplier" {{ $document_with == 'Supplier' ? 'selected' : ''  }}>Supplier</option>
                                            </select>
                                    {{--{{ $document_with }}--}}
                                        </td>
                                        @endif
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <td class="editable-field bl_number" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{$bl_number}}</td>
                                       @endif
                                        <td><a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehicleslog.viewdetails', $vehicles->id) }}" onclick="event.stopPropagation();"></i> View Details</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div id="paginationContainer" class="mt-3">
      <!-- Custom pagination buttons -->
      <button id="prevBtn">Previous</button>
      <span id="pageInfo"></span>
      <button id="nextBtn">Next</button>
    </div>
                    </div>
            </form>
        @endif
        <script>
// Function to get data from the editable fields and update the server
function updateData() {
  const editableFields = document.querySelectorAll('.editable-field');
  const updateDataUrl = '{{ route('vehicles.updatedata') }}';
  const updatedData = [];

  editableFields.forEach(field => {
    const vehicleId = field.getAttribute('data-vehicle-id');
    const fieldName = field.classList[1];
    const fieldValue = field.innerText.trim();

    const selectElement = field.querySelector('select');
    if (selectElement) {
      const selectedOption = selectElement.options[selectElement.selectedIndex];
      const selectedValue = selectedOption.value;
      updatedData.push({ id: vehicleId, name: fieldName, value: selectedValue });
    } else {
      updatedData.push({ id: vehicleId, name: fieldName, value: fieldValue });
    }
  });
console.log(updatedData);
  // Perform the fetch request to update the data on the server
  fetch(updateDataUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(updatedData)
  })
  .then(response => response.json())
  .then(data => {
      // Handle the response from the controller if needed
      console.log(data);

      // Display the success flash message on the page
      const flashMessage = document.getElementById('flash-message');
      flashMessage.textContent = 'Record is successfully saved';
      flashMessage.style.display = 'block';

      // Hide the success flash message after 5 seconds
      setTimeout(() => {
        flashMessage.style.display = 'none';
      }, 5000);

      setTimeout(() => {
        window.location.reload();
      }, 2000); // 3.5 seconds delay (3500 milliseconds)
    })
    .catch(error => {
      // Display the error flash message on the page
      const flashMessage = document.getElementById('flash-message');
      flashMessage.textContent = 'Failed to update data. Please try again later.';
      flashMessage.style.display = 'block';

      // Hide the error flash message after 5 seconds
      setTimeout(() => {
        flashMessage.style.display = 'none';
      }, 5000);

      // Handle any errors that occur during the request
      console.error('Error:', error);
    });
}
// Function to handle date fields and convert them to input fields
function handleDateFields() {
  const dateFields = document.querySelectorAll('[data-is-date="true"]');
  dateFields.forEach(field => {
    const fieldValue = field.innerText.trim();
    const inputField = document.createElement('input');
    inputField.type = 'date';
    inputField.name = field.getAttribute('data-field-name');
    inputField.value = fieldValue;
    inputField.classList.add('form-control');
    field.innerHTML = '';
    field.appendChild(inputField);
  });
}

// Get the Edit button and Update Success button
const editBtn = document.querySelector('.edit-btn');
const updateBtn = document.querySelector('.update-btn');

// Add event listener to the Edit button
editBtn.addEventListener('click', () => {
  editBtn.style.display = 'none';
  updateBtn.style.display = 'block';

  const editableFields = document.querySelectorAll('.editable-field');
  editableFields.forEach(field => {
    field.contentEditable = true;
    field.classList.add('editing');

    const selectElement = field.querySelector('select');
    if (selectElement) {
      selectElement.removeAttribute('disabled');
    }
  });

  // Convert date fields to input fields
  handleDateFields();
});

// Add event listener to the Update Success button
updateBtn.addEventListener('click', () => {
  editBtn.style.display = 'block';
  updateBtn.style.display = 'none';

  const editableFields = document.querySelectorAll('.editable-field');
  editableFields.forEach(field => {
    field.contentEditable = false;
    field.classList.remove('editing');

    const selectElement = field.querySelector('select');
    if (selectElement) {
      selectElement.setAttribute('disabled', 'disabled');
    }

    const inputField = field.querySelector('input[type="date"]');
    if (inputField) {
      const fieldValue = inputField.value;
      field.innerHTML = fieldValue;
    }
  });

  updateData(); // Call the function to update the server with the edited data
});
</script>
<script>
  $(document).ready(function() {
    $('.select2').select2();

    // Table #dtBasicExample2
    var dataTable = $('#dtBasicExample1').DataTable({
      "order": [[4, "desc"]],
      pageLength: 50,
      initComplete: function() {
        this.api().columns().every(function(d) {
          var column = this;
          var columnId = column.index();
          var columnName = $(column.header()).attr('id');
          if (columnName === "sales_remarks") {
            return;
          }
          if (columnName === "int-colour") {
            return;
          }
          if (columnName === "ex-colour") {
            return;
          }

          if (columnName === "importdoc") {
            return;
          }
          if (columnName === "ownership") {
            return;
          }
          if (columnName === "documentwith") {
            return;
          }
          if (columnName === "changelogs") {
            return;
          }
          if (columnName === "variant") {
            return;
          }
          var selectWrapper = $('<div class="select-wrapper"></div>');
          var select = $('<select class="form-control my-1" multiple></select>')
            .appendTo(selectWrapper)
            .select2({
              width: '100%',
              dropdownCssClass: 'select2-blue'
            });

          select.on('change', function() {
            var selectedValues = $(this).val();

            // Check if the blank option is selected
            if (selectedValues && selectedValues.includes('')) {
              column.search('^$', true, false); // Filter blank values
            } else {
              column.search(selectedValues ? selectedValues.join('|') : '', true, false); // Filter other selected values
            }

            column.draw();
          });

          selectWrapper.appendTo($(column.header()));
          $(column.header()).addClass('nowrap-td');

          column.data().unique().sort().each(function(d, j) {
            // Add option for blank value
            var optionValue = d === null ? '' : d;
            var optionText = d === null ? 'Blank' : d === '' ? 'Null' : d;
            select.append('<option value="' + optionValue + '">' + optionText + '</option>');
          });
        });
      }
    });
    // Apply search functionality
    $('#tableSearch').on('keyup', function () {
        dataTable.search(this.value).draw();
      });
      // Hide the default search bar
      $('#dtBasicExample1_filter').hide();
      $('#dtBasicExample1_paginate').hide();

// Implement custom pagination
var currentPage = 0;
var pageSize = 50; // Set the number of rows per page here

function showPage(page) {
  dataTable.page(page).draw(false);
  updatePageInfo();
}

function updatePageInfo() {
  var pageInfo = dataTable.page.info();
  $('#pageInfo').text('Page ' + (currentPage + 1) + ' of ' + pageInfo.pages);
}

$('#prevBtn').on('click', function (e) {
  e.preventDefault(); // Prevent default behavior (page reload)
  if (currentPage > 0) {
    currentPage--;
    showPage(currentPage);
  }
});

$('#nextBtn').on('click', function (e) {
  e.preventDefault(); // Prevent default behavior (page reload)
  if (currentPage < dataTable.page.info().pages - 1) {
    currentPage++;
    showPage(currentPage);
  }
});

// Initial page info update
updatePageInfo();
});
</script>
   {{--@endif--}}
@endsection