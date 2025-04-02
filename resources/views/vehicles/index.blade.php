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
  .editing {
    background-color: white !important;
    border: 1px solid black  !important;
}
#searchContainer {
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
    /* Hide the default "Show x entries" dropdown */
  #dtBasicExample1_length {
    display: none;
  }
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    padding: 0;
    margin: 10px 0;
  }
  .pagination-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .pagination-list li {
    margin: 0 5px;
  }

  .pagination-list li a,
  .pagination-list li span {
    padding: 6px 10px;
    border: 1px solid #ddd;
    color: #333;
    text-decoration: none;
  }

  .pagination-list li.active a {
  background-color: #007bff;
  color: #fff;
  border-color: #007bff;
}

  .pagination-list li.disabled span {
    color: #aaa;
    pointer-events: none;
  }

  .page-info {
    margin-right: 20px;
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
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
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
                <!-- <h4 class="card-title">Pending Vehicles Updates</h4> -->
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
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-so','edit-reservation']);
                @endphp
                @if ($hasPermission)
  <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
  <table class="table table-editable table-edits table-bordered">
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
    @php
    $incomingvehicless = DB::table('vehicles')->where('payment_status', 'Incoming Stock')->whereNull('movement_grn_id')->count();
    @endphp
	<tr>
        <td style="font-size: 12px;">
            Incoming Stock
        </td>
        <td onclick="window.location.href = '{{ route('vehiclesincoming.stock') }}'" colspan="{{$countwarehouse}}" style="font-size: 12px; text-align: center;">{{$incomingvehicless}}</td>
    </tr>
	<tr>
        <td style="font-size: 12px;">
            Available Stock
        </td>
        @foreach ($warehousesvehss as $warehousesvehss)
        @php
        $avalibless = DB::table('vehicles')->where('latest_location', $warehousesvehss->id)
        ->whereNotNull('inspection_date')
        ->whereNull('so_id')
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehiclesincoming.avalibless', ['warehouse_id' => $warehousesvehss->id]) }}'" style="font-size: 12px;">{{$avalibless}}</td>
        @endforeach
      </tr>
    <tr>
        <td style="font-size: 12px;">
            Pending Approval (Booking / Reserve)
        </td>
		@foreach ($warehousesveher as $warehousesveher)
@php
$fieldValues = ['so_number', 'so_date', 'sales_person_id', 'reservation_start_date', 'reservation_end_date'];

$countpendingsinspectionso = DB::table('vehicles')
    ->join('vehicle_detail_approval_requests', 'vehicles.id', '=', 'vehicle_detail_approval_requests.vehicle_id')
    ->where('vehicle_detail_approval_requests.status', '=', 'Pending')
    ->where('vehicles.latest_location', '=', $warehousesveher->id)
    ->whereIn('field', $fieldValues)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('so') // Replace with the actual name of your sales order table
            ->whereColumn('so.id', 'vehicles.so_id')
            ->where('so.sales_person_id', auth()->user()->id);
    })
    ->count();
@endphp
        <td onclick="window.location.href = '{{ route('vehicleinspectionpending.pendingapprovalssales', ['warehouse_id' => $warehousesveher->id]) }}'" style="font-size: 12px;">{{ $countpendingsinspectionso }}</td>
        @endforeach
      </tr>
	   <tr>
        <td style="font-size: 12px;">
            Booked / Reverved Stock
        </td>
        @foreach ($warehousessold as $warehousessold)
        @php
        $today = today();
        $recivedandbooked = DB::table('vehicles')->where('latest_location', $warehousessold->id)
        ->whereNotNull('reservation_end_date')
		->where('reservation_end_date', '<=', $today)
		->whereNotNull('so_id')
		->whereNull('gdn_id')
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehiclesincoming.bookedstocked', ['warehouse_id' => $warehousessold->id]) }}'" style="font-size: 12px;">{{$recivedandbooked}}</td>
        @endforeach
      </tr>
    </tbody>
  </table>
</div>
@endif
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-edit');
                @endphp
                @if ($hasPermission)
                <div class="col-lg-3 col-md-3 col-sm-12 table-responsive">
                    <table class="table table-striped table-editable table-edits table table-bordered">
                        <thead>
                            <th style="font-size: 12px;">Vehicle Status</th>
                            <th style="font-size: 12px;">Vehicle Quantity</th>
                        </thead>
                        <tbody>
						@php
    $incomingvehicless = DB::table('vehicles')->where('payment_status', 'Incoming Stock')->whereNull('movement_grn_id')->count();
    @endphp
                            <tr  onclick="window.location.href = '{{ route('vehiclesincoming.stock') }}'">
                                <td style="font-size: 12px;">
                                        Incoming Stock
                                </td>
                                <td style="font-size: 12px;">{{$incomingvehicless}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
@endif
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-edit');
                @endphp
                @if ($hasPermission)
  <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
  <table class="table table-editable table-edits table-bordered">
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
    @php
    $incomingvehicless = DB::table('vehicles')->where('payment_status', 'Incoming Stock')->whereNull('movement_grn_id')->count();
    @endphp
	<tr>
        <td style="font-size: 12px;">
            Incoming Stock
        </td>
        <td colspan="{{$countwarehouse}}" onclick="window.location.href = '{{ route('vehiclesincoming.stock') }}'" style="font-size: 12px; text-align: center;">{{$incomingvehicless}}</td>
    </tr>
	   <tr>
        <td style="font-size: 12px;">
            Pending Newsuit GRN
        </td>
        @foreach ($warehousessold as $warehousessold)
        @php
        $pendinggrnnetsuilt = DB::table('vehicles')->where('latest_location', $warehousessold->id)
        ->whereNotNull('movement_grn_id')
		    ->whereNull('netsuit_grn_number')
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehiclesincoming.pendinggrnnetsuilt', ['warehouse_id' => $warehousessold->id]) }}'" style="font-size: 12px;">{{$pendinggrnnetsuilt}}</td>
        @endforeach
      </tr>
    </tbody>
  </table>
</div>
@endif

                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-po-details');
                @endphp
                @if ($hasPermission)
  <div class="col-lg-12 col-md-12 col-sm-12 table-responsive">
  <table class="table table-editable table-edits table-bordered">
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
    @php
    $incomingvehicless = DB::table('vehicles')->where('payment_status', 'Incoming Stock')->whereNull('movement_grn_id')->count();
    @endphp
	<tr>
        <td style="font-size: 12px;">
            Incoming Stock
        </td>
        <td onclick="window.location.href = '{{ route('vehiclesincoming.stock') }}'" colspan="{{$countwarehouse}}" style="font-size: 12px; text-align: center;">{{$incomingvehicless}}</td>
    </tr>
    <tr>
        <td style="font-size: 12px;">
            Pending Inspection
        </td>
        @foreach ($warehousesveh as $warehousesveh)
        @php
        $pendinginspection = DB::table('vehicles')->where('latest_location', $warehousesveh->id)
            ->whereNotNull('movement_grn_id')
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
            Available Stock
        </td>
        @foreach ($warehousesvehss as $warehousesvehss)
        @php
        $avalibless = DB::table('vehicles')->where('latest_location', $warehousesvehss->id)
        ->whereNotNull('inspection_date')
        ->whereNull('so_id')
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehiclesincoming.avalibless', ['warehouse_id' => $warehousesvehss->id]) }}'" style="font-size: 12px;">{{$avalibless}}</td>
        @endforeach
      </tr>
	   <tr>
        <td style="font-size: 12px;">
            Sold Stock
        </td>
        @foreach ($warehousessold as $warehousessold)
        @php
        $soldvehss = DB::table('vehicles')->where('latest_location', $warehousessold->id)
        ->whereNotNull('so_id')
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehiclesincoming.soldvehss', ['warehouse_id' => $warehousessold->id]) }}'" style="font-size: 12px;">{{$soldvehss}}</td>
        @endforeach
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
  <table class="table table-editable table-edits table-bordered">
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
    @php
    $incomingvehicless = DB::table('vehicles')->where('payment_status', 'Incoming Stock')->whereNull('movement_grn_id')->count();
    @endphp
    <tr style="background-color: yellow !important;">
        <td style="font-size: 12px;">
            Pending Inspection
        </td>
        @foreach ($warehousesveh as $warehousesveh)
        @php
        $pendinginspection = DB::table('vehicles')->where('latest_location', $warehousesveh->id)
            ->whereNotNull('movement_grn_id')
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
      <tr style="background-color: yellow !important;">
        <td style="font-size: 12px;">
            Pending PDI
        </td>
        @foreach ($warehousesvehss as $warehousesvehss)
        @php
        $pendingpdi = DB::table('vehicles')->where('latest_location', $warehousesvehss->id)
        ->whereNotNull('so_id')
        ->whereNull('pdi_date')
        ->count();
        @endphp
        <td onclick="window.location.href = '{{ route('vehiclesincoming.pendingpdis', ['warehouse_id' => $warehousesvehss->id]) }}'" style="font-size: 12px;">{{$pendingpdi}}</td>
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
                        <th style="font-size: 12px;">In Stock</th>
                        <th style="font-size: 12px;">Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size: 12px;">
                            Previous Year
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_YEAR_PURCHASED]) }}';">
                            {{ $previousYearPurchased }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_YEAR_SOLD]) }}';">
                            {{$previousYearSold}}
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
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_MONTH_SOLD]) }}';">
                            {{ $previousMonthPurchased }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_MONTH_SOLD]) }}';">
                            {{ $previousMonthSold }}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_PREVIOUS_MONTH_AVAILABLE]) }}';">
                            {{ $previousMonthAvailable }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px;">
                          Yesterday
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_YESTERDAY_PURCHASED]) }}';">
                            {{ $yesterdayPurchased}}
                        </td>
                        <td style="font-size: 12px;" onclick="window.location='{{ route('vehicle-stock-report.filter',['key' => \App\Models\Vehicles::FILTER_YESTERDAY_SOLD]) }}';">
                            {{ $yesterdaySold }}
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
<button id="applyFilterBtn" class="btn btn-primary">Apply Filters</button>
<a href="{{ route('Vehicles.index') }}" class="btn btn-danger" role="button">
Clear Filters
                                  </a>
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
                                <th style="width:205px;" id="vehicle_id">Ref No</th>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                                    @endphp
                                    @if ($hasPermission)
                                <th id="po_number" class="nowrap-td">PO Number</th>
                                <th id="po_date" class="nowrap-td">PO Date</th>
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
                                <th id="estimation_date" class="nowrap-td">Estimated Arrival</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('grn-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="grn_number" class="nowrap-td">GRN</th>
                                <th id="grn_date" class="nowrap-td">GRN Date</th>
                                <th id="netsuit_grn_number" class="nowrap-td">Netsuit GRN Number</th>
                                <th id="netsuit_grn_date" class="nowrap-td">Netsuit GRN Date</th>
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
                                <th id="inspection_date" class="nowrap-td">GRN Inspection Date</th>
                                <th id="grn_remark" class="nowrap-td">GRN Remarks</th>
                                <th id="qc_remarks" class="nowrap-td">QC Remarks</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                                @endphp
                                @if ($hasPermission)
                                <th class="nowrap-td">Payment Aging</th>
                                <th class="nowrap-td">Stock Aging</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                                @endphp
                                @if ($hasPermission)
                                    <th id="so_number" class="nowrap-td">SO Number</th>
                                    <th id="so_date" class="nowrap-td">SO Date</th>
                                    @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="sales_person_id" class="nowrap-td">Sales Person</th>
                                    <th id="reservation_start_date" class="nowrap-td">Reservation Date</th>
                                    <th id="reservation_end_date" class="nowrap-td">Reservation Due Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('so-remarks');
                                @endphp
                                @if ($hasPermission)
                                    <th id="sales_remarks" style="vertical-align: middle;" class="nowrap-td">Sales Remarks</th>
                                @endif
                                <th id="pdi_date" class="nowrap-td">PDI Inspection Date</th>
                                <th id="pdi_remarks" class="nowrap-td">PDI Remarks</th>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="gdn_number" class="nowrap-td">GDN Number</th>
                                    <th id="gdn_date" class="nowrap-td">GDN Date</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="brand" class="nowrap-td">Brand</th>
                                    <th id="model_line" class="nowrap-td">Model Line</th>
                                    <th id="model_description" class="nowrap-td">Model Description</th>
                                    <th id="variant" id="variant" style="vertical-align: middle;" class="nowrap-td">Variant Name</th>
                                    <th id="variant_details" class="nowrap-td">Variant Detail</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vin-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="vin" class="nowrap-td">VIN Number</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-view');
                                @endphp
                                @if ($hasPermission)
                                <th id="conversion" class="nowrap-td">Conversion</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="engine" class="nowrap-td">Engine</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="model_year" class="nowrap-td">Model Year</th>
                                    <th id="steering" class="nowrap-td">Steering</th>
                                    <th id="seats" class="nowrap-td">Seats</th>
                                    <th id="fuel_type" class="nowrap-td">Fuel Type</th>
                                    <th id="gear" class="nowrap-td">Transmission</th>
                                    <th id="ex_colour" class="nowrap-td" id="ex-colour" style="vertical-align: middle;" style="min-width:150px">Ext Colour</th>
                                    <th id="int_colour" class="nowrap-td" id="int-colour"  style="vertical-align: middle;" style="min-width:150px">Int Colour</th>
                                    <th id="upholestry" class="nowrap-td">Upholstery</th>
                                    <th id="extra_features" class="nowrap-td">Extra Features</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="ppmmyyy" class="nowrap-td">Production Year</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="territory" class="nowrap-td">Allowed Territory</th>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
                                @endphp
                                @if ($hasPermission)
                                    <th id="latest_location" class="nowrap-td">Warehouse</th>
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
                                    <th id="price"class="nowrap-td">Price</th>
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
                                    <th id="bl_number"class="nowrap-td">BL Number</th>
                                    <th id="bl_dms_uploading"class="nowrap-td">BL DMS Upload</th>
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
                                     $grn = $vehicles->movement_grn_id ? DB::table('movement_grns')->where('id', $vehicles->movement_grn_id)->first() : null;
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
                                                $bl_dms_uploading = $documents ? $documents->bl_dms_uploading : null;
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
    $savedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $vehicles->estimation_date);
    $today = \Carbon\Carbon::now();
    $numberOfDaysEta = $today->diffInDays($savedDate, false);
    $sign = ($numberOfDaysEta >= 0) ? '' : '-';
    $numberOfDaysEta = abs($numberOfDaysEta);
@endphp

<td class="nowrap-td eta">
    {{ $sign . $numberOfDaysEta }} {{ $numberOfDaysEta == 1 ? 'Day' : 'Days' }}
</td>
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
                                <td class="nowrap-td estimation_date">{{date('d-M-Y', strtotime($vehicles->estimation_date))}}</td>
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
                                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-edit');
                                      @endphp
                                      @if ($hasPermission)
                                      <td class="editable-field netsuit_grn_number" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->netsuit_grn_number }}</td>
                                      <td class="editable-field netsuit_grn_date" data-is-date="true" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}" data-type="date" data-field-name="netsuit_grn_date">{{ $vehicles->netsuit_grn_date }}</td>
                                      @else
                                      <td>{{ $vehicles->netsuit_grn_number }}</td>
                                      <td>
                                        @if($vehicles->netsuit_grn_date)
                                        {{ date('d-M-Y', strtotime($vehicles->netsuit_grn_date)) }}
                                      @else
                                      {{$vehicles->netsuit_grn_date}}
                                      @endif
                                      </td>
                                      @endif
                                 @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
                                @endphp
                                @if ($hasPermission)
                                @if($grn_number === null)
                                <td class="nowrap-td stockstatus">Incoming</td>
                                @elseif($so_number === null && $vehicles->reservation_end_date && $vehicles->reservation_end_date > \Carbon\Carbon::now())
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
  								                  @if (!empty($vehicles->inspection_date))
                                    <td class="inspection_date">{{ date('d-M-Y', strtotime($vehicles->inspection_date)) }}</td>
                                    @else
                                    <td class="inspection_date"></td>
                                    @endif
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                                    @endphp
                                    @if ($hasPermission)
                                    @if ($vehicles->movement_grn_id && $vehicles->so_id === null)
                                    <td class="editable-field grn_remark" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->grn_remark }}</td>
                                    <td class="editable-field qc_remarks" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->qc_remarks }}</td>
									                  @else
                                    <td>{{ $vehicles->grn_remark }}</td>
                                    <td>{{ $vehicles->qc_remarks }}</td>
                                    @endif
                                    @else
                                    <td>{{ $vehicles->grn_remark }}</td>
                                    <td>{{ $vehicles->qc_remarks }}</td>
                                    @endif
                                    @endif
                                    @php
                                          $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                                          @endphp
                                          @if ($hasPermission)
                                          @php
                                          $paymentLog = DB::table('payment_logs')->where('vehicle_id', $vehicles->id)->latest()->first();
                                          @endphp
										                      @if($grn_date !== null)
                                          @if ($paymentLog)
                                          @php
                                          $savedDate = $paymentLog->date;
                                          $today = Carbon\Carbon::parse($grn_date)->format('Y-m-d');
                                          $numberOfDays = \Carbon\Carbon::parse($savedDate)->diffInDays($today);
                                          @endphp
													                <td class="nowrap-td">{{ $numberOfDays }}</td>
										                      @else
                                          <td class="nowrap-td"></td>
                                          @endif
                                          @else
                                          @if ($paymentLog)
                                          @php
                                          $savedDate = $paymentLog->date;
                                          $today = now()->format('Y-m-d');
                                          $numberOfDays = \Carbon\Carbon::parse($savedDate)->diffInDays($today);
                                          @endphp
													                <td class="nowrap-td">{{ $numberOfDays }}</td>
                                          @else
                                          <td class="nowrap-td"></td>
                                          @endif
                                          @endif
                                      @endif
                                    @php
                                      $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
                                      @endphp
                                      @if ($hasPermission)
                                          @if ($grn_date && $gdn_date === null)
                                              @php
                                              $grn_date = \Carbon\Carbon::parse($grn_date);
                                              $aging = $grn_date->diffInDays(\Carbon\Carbon::today());
                                              @endphp
                                              <td class="nowrap-td">{{ $aging }}</td>
                                          @elseif ($gdn_date)
                                              @php
                                              $aging = \Carbon\Carbon::parse($grn_date)->diffInDays($gdn_date);
                                              @endphp
                                              <td class="nowrap-td">{{ $aging }}</td>
                                          @else
                                              <td class="nowrap-td"></td>
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
                                     <td class="editable-field so_date" data-is-date="true" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}" data-type="date" data-field-name="so_date">@if ($so_date)
                                        {{ date('d-M-Y', strtotime($so_date)) }}
                                        @else
                                        {{$so_date}}
                                        @endif</td>
									                    @else
									                    <td>{{ $so_number }}</td>
                                      <td>@if ($so_date)
                                        {{ date('d-M-Y', strtotime($so_date)) }}
                                        @else
                                        {{$so_date}}
                                        @endif
                                      </td>
                                     @endif
									                  @endif
                                     @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                    @endphp
                                    @if ($hasPermission)
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
									@php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-reservation');
                                    @endphp
                                    @if ($hasPermission)
                                    <td class="editable-field reservation_start_date" data-is-date="true" data-type="date" data-vehicle-id="{{ $vehicles->id }}" data-field-name="reservation_start_date">
                                    {{ $vehicles->reservation_start_date }}</td>
                                    <td class="editable-field reservation_end_date" data-is-date="true" data-type="date" data-vehicle-id="{{ $vehicles->id }}" data-field-name="reservation_end_date">
                                    {{ $vehicles->reservation_end_date }}</td>
									                 @else
								                    <td class="reservation_start_date">
                                      @if($vehicles->reservation_start_date)
                                      {{ $vehicles->reservation_start_date }}
                                      @else
                                    date('d-M-Y', strtotime($vehicles->reservation_start_date))
                                    @endif
                                    </td>
                                    <td class="reservation_end_date">
                                      @if($vehicles->reservation_end_date)
                                    {{ $vehicles->reservation_end_date }}
                                  @else
                                  date('d-M-Y', strtotime($vehicles->reservation_end_date))
                                  @endif
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
                                    @if (!empty($vehicles->pdi_date))
                                    <td class="pdi_date">{{ date('d-M-Y', strtotime($vehicles->pdi_date)) }}</td>
                                    @else
                                    <td class="pdi_date"></td>
                                    @endif
                                    @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                                    @endphp
                                    @if ($hasPermission)
                                    @if ($vehicles->so_id && $vehicles->gdn_id === null)
                                    <td class="editable-field pdi_remarks" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->pdi_remarks }}</td>
                                    @else
                                    <td>{{ $vehicles->pdi_remarks }}</td>
                                    @endif
                                    @else
                                    <td>{{ $vehicles->pdi_remarks }}</td>
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
                                        {{ ucfirst(strtolower($vehicles->variant->brand->brand_name ?? '')) }}
                                     </td>
                                     <td class="nowrap-td" id="model-line-{{$vehicles->id}}">
                                     {{ ucfirst(strtolower($vehicles->variant->master_model_lines->model_line ?? '')) }}
                                     </td>
                                     <td class="nowrap-td" id="model-description-{{$vehicles->id}}">
                                     {{ ucfirst(strtolower($vehicles->variant->model_detail ?? '')) }}
                                     </td>
                                     @if($vehicles->movement_grn_id === null || $vehicles->gdn_id !== null)
                                     <td>
                                    <select name="varaints_id" class="form-control" placeholder="varaints_id" disabled>
                                    @foreach($varaint as $variantItem)
                                         <option value="{{$variantItem->id}}" {{ $variantItem->id == $vehicles->varaints_id ? "selected" : "" }}>
                                             {{ $variantItem->name }}</option>
                                    @endforeach
                                    </select>
                                    </td>
                                     @else
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
                                    @endif
                                     <td class="nowrap-td" id="variant-detail-{{ $vehicles->id }}">
                                     {{ ucfirst(strtolower($vehicles->detail ?? '' )) }}
                                     </td>
                                    @else
                                      <td class="nowrap-td brand" id="brand-{{$vehicles->id}}">
                                      {{ ucfirst(strtolower($vehicles->variant->brand->brand_name ?? '')) }}
                                     </td>
                                     <td class="nowrap-td" id="model-line-{{$vehicles->id}}">
                                     {{ ucfirst(strtolower($vehicles->variant->master_model_lines->model_line ?? '')) }}
                                     </td>
                                     <td class="nowrap-td" id="model-description-{{$vehicles->id}}">
                                     {{ ucfirst(strtolower($vehicles->variant->model_detail ?? '')) }}
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
                                     {{ ucfirst(strtolower($vehicles->detail ?? '' )) }}
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
                                      <td class="editable-field conversion" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->conversion)) }}</td>
                                      @else
                                      <td>
                                      {{ ucfirst(strtolower($vehicles->conversion)) }}</td>
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
                                      @if($vehicles->movement_grn_id === null || $vehicles->gdn_id !== null)
                                      <td>{{ $vehicles->engine }}</td>
                                      @else
                                      <td class="editable-field engine" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                                     @endif
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
                                        {{ $vehicles->variant->my ?? 'null' }}
                                         </td>
                                        <td class="nowrap-td" id="steering-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->steering ?? 'null'  }}
                                        </td>
                                        <td class="nowrap-td" id="seat-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->seat ?? 'null'  }}
                                        </td>
                                        <td class="nowrap-td" id="fuel-type-{{ $vehicles->id }}">
                                        {{ ucfirst(strtolower($vehicles->variant->fuel_type ?? 'null' )) }}
                                        </td>
                                        <td class="nowrap-td" id="gearbox-{{ $vehicles->id }}">
                                        {{ ucfirst(strtolower($vehicles->variant->gearbox ?? 'null' )) }}
                                        </td>
                                        @if($vehicles->movement_grn_id === null || $vehicles->gdn_id !== null)
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
                                        @else
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
                                        @endif
                                        <td class="nowrap-td Upholestry" id="upholestry-{{ $vehicles->id }}">
                                        {{ ucfirst(strtolower($vehicles->variant->upholestry ?? '' )) }}
                                        </td>
                                        @if($vehicles->movement_grn_id === null || $vehicles->gdn_id !== null)
                                        <td class="nowrap-td">{{ ucfirst(strtolower($vehicles->extra_features)) }}</td>
                                        @else
                                        <td class="editable-field extra_features" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->extra_features)) }}</td>
										                    @endif
                                        @else
										                    <td class="nowrap-td" id="my-{{ $vehicles->id }}">
                                        {{ $vehicles->variant->my ?? 'null'  }}
                                         </td>
                                        <td class="nowrap-td" id="steering-{{ $vehicles->id }}">
                                            {{ $vehicles->variant->steering ?? 'null'  }}
                                        </td>
                                        <td class="nowrap-td" id="seat-{{ $vehicles->id }}">
                                        {{ $vehicles->variant->seat ?? 'null'  }}
                                        </td>
                                        <td class="nowrap-td" id="fuel-type-{{ $vehicles->id }}">
                                        {{ ucfirst(strtolower($vehicles->variant->fuel_type ?? 'null' )) }}
                                        </td>
                                        <td class="nowrap-td" id="gearbox-{{ $vehicles->id }}">
                                        {{ ucfirst(strtolower($vehicles->variant->gearbox ?? 'null' )) }}
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
                                        {{ ucfirst(strtolower($vehicles->variant->upholestry ?? '' )) }}
                                        </td>
                                        <td class="nowrap-td">{{ ucfirst(strtolower($vehicles->extra_features )) }}</td>
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
                                        @if($vehicles->movement_grn_id === null || $vehicles->gdn_id !== null)
                                        <td>{{ $vehicles->ppmmyyy }}</td>
                                        @else
                                        <td class="editable-field ppmmyyy" data-is-date="true" data-type="month" contenteditable="false" data-field-name="ppmmyyy" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->ppmmyyy }}</td>
                                        @endif
                                        @else
                                      <td>{{ $vehicles->ppmmyyy }}</td>
                                      @endif
                                      @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
                                        @endphp
                                        @if ($hasPermission)
                                        <td class="nowrap-td Territory">{{ ucfirst(strtolower($vehicles->territory )) }}</td>
                                        @endif
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
                                        @endphp
                                        @if ($hasPermission)
                                        @if ($warehouses === null)
                                        @if($vehicles->movement_grn_id === null)
                                        <td class="nowrap-td">Supplier</td>
                                        @elseif($vehicles->gdn_id !== null)
                                        <td class="nowrap-td">Customer</td>
                                        @else
                                        <td class="nowrap-td">Supplier</td>
                                        @endif
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
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-edit');
                                        @endphp
                                        @if ($hasPermission)
                                        <td class="editable-field bl_number" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{$bl_number}}</td>
                                        <td class="editable-field bl_dms_uploading" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="bl_dms_uploading" class="form-control" placeholder="bl_dms_uploading" disabled>
                                                <option value=""></option>
                                                <option value="Yes" {{ $bl_dms_uploading == 'Yes' ? 'selected' : ''  }}>Yes</option>
                                                <option value="No" {{ $bl_dms_uploading == 'No' ? 'selected' : ''  }}>No</option>
                                            </select>
                                    {{--{{ $document_with }}--}}
                                        </td>
                                        @else
                                        <td>{{$bl_number}}</td>
                                        <td class="filterable-column">
                                        <select name="bl_dms_uploading" class="form-control" placeholder="bl_dms_uploading" disabled>
                                                <option value=""></option>
                                                <option value="Yes" {{ $bl_dms_uploading == 'Yes' ? 'selected' : ''  }}>Yes</option>
                                                <option value="No" {{ $bl_dms_uploading == 'No' ? 'selected' : ''  }}>No</option>
                                            </select>
                                    {{--{{ $document_with }}--}}
                                        </td>
                                        @endif
                                        @endif
                                        <td style="display: flex; white-space: nowrap;"><a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehicleslog.viewdetails', $vehicles->id) }}" onclick="event.stopPropagation();"></i> View Details</a></td>
                                      </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div id="paginationContainer" class="mt-3">
                        {{ $data->links() }}
                    </div>
                    </div>
            </form>
        @endif
        <script>
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
      console.log(data);
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
    const fieldType = field.getAttribute('data-type');
    const inputField = document.createElement('input');

    if (fieldType === 'date') {
      inputField.type = 'date';
      inputField.value = fieldValue;
    } else if (fieldType === 'month') {
      inputField.type = 'month';

      if (fieldValue) {
        const [month, year] = fieldValue.split(' ');
        inputField.value = `${year}-${month.padStart(2, '0')}`; // Ensure two-digit month
      }
    }
    inputField.name = field.getAttribute('data-field-name');
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

    const inputField = field.querySelector('input[type="date"], input[type="month"]');
    if (inputField) {
      const fieldValue = inputField.value;
      if (inputField.type === 'date') {
        field.innerHTML = fieldValue;
      } else if (inputField.type === 'month') {
        const [year, month] = fieldValue.split('-');
        if (year && month) { // Check if both year and month are defined
          field.innerHTML = `${month} ${year}`;
        }
      }
    }
  });
  updateData(); // Call the function to update the server with the edited data
});
</script>
<script>
$(document).ready(function() {
  var currentFilters = new URLSearchParams(window.location.search); // Get the current filters from the query string
  function updateFilters(columnName, searchQuery) {
    // Update the currentFilters with the new filter
    currentFilters.append('columnName[]', columnName);
    currentFilters.append('searchQuery[]', searchQuery);
  }
  function getQueryStringWithFilters() {
    // Return the updated query string with all the filters
    return '?' + currentFilters.toString();
  }
// Function to display the applied filters in the search inputs
function displayAppliedFilters() {
  // Loop through each search input in the table header
  $('#dtBasicExample1 thead input').each(function() {
    var columnName = $(this).data('column-name'); // Get the data attribute value for column name

    // Get the search query for the current column from the current filters
    var searchQuery = currentFilters.getAll('searchQuery[]').find(function(query, index) {
      return currentFilters.getAll('columnName[]')[index] === columnName;
    });

    // Display the search query in the input if it exists in the filters
    if (searchQuery !== undefined && searchQuery !== '') {
      $(this).val(searchQuery);
    } else {
      $(this).val(''); // Clear the search input if no filter exists or the filter is empty for this column
    }
  });
}
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
    "order": [[4, "desc"]],
    pageLength: 100,
    columnDefs: [
  { type: 'date', targets: $('.PoDate').index() },
  { type: 'date', targets: $('.grnDate').index() },
  { type: 'date', targets: $('.estimation_date').index() },
  { type: 'date', targets: $('.netsuit_grn_date').index('.editable-field') },
  { type: 'date', targets: $('.inspection_date').index('.editable-field') },
  { type: 'date', targets: $('.so_date').index('.editable-field') },
  { type: 'date', targets: $('.reservation_start_date').index('.editable-field') },
  { type: 'date', targets: $('.reservation_end_date').index('.editable-field') },
  { type: 'date', targets: $('.pdi_date').index('.editable-field') },
  { type: 'date', targets: $('.gdnDate').index() }
],
    initComplete: function() {
      this.api().columns().every(function(d) {
        var column = this;
        var columnId = column.index();
        var columnName = $(column.header()).attr('id');
        if (columnName === "changelogs") {
          return;
        }
        // Add search input at the top of each column
       // Inside the initComplete function when creating search inputs
    var searchInput = $('<input type="text" class="form-control form-control-sm" placeholder="">')
  .appendTo($(column.header()))
  .attr('data-column-name', columnName) // Add the data attribute for column name
  .on('keyup', function() {
    column.search($(this).val()).draw();
  });
        $(column.header()).addClass('nowrap-td');

        column.data().unique().sort().each(function(d, j) {
          // Add option for blank value
          var optionValue = d === null ? '' : d;
          var optionText = d === null ? 'Blank' : d === '' ? 'Null' : d;
          searchInput.append('<option value="' + optionValue + '">' + optionText + '</option>');
        });
      });
    },
    searchCols: [{ // Set the default search to the top of each column
      search: ''
    }]
  });

   // Call the function to display the applied filters on page load
  displayAppliedFilters();

// Function to handle the Apply Filters button click event
$('#applyFilterBtn').on('click', function() {
  var filters = [];

  // Loop through each search input in the table header
  $('#dtBasicExample1 thead input').each(function() {
    var columnName = $(this).data('column-name'); // Get the data attribute value for column name
    var searchQuery = $(this).val();

    // If the search input has data, add it to the filters array
    if (searchQuery !== '') {
      filters.push({
        columnName: columnName,
        searchQuery: searchQuery
      });
    }
  });

  // Update the filters in the URL
  filters.forEach(function(filter) {
    updateFilters(filter.columnName, filter.searchQuery);
  });

  // Create the query string with the filters for all columns
  var queryString = getQueryStringWithFilters();

  // Update the URL using the History API without reloading the page
  var newUrl = window.location.href.split('?')[0] + queryString;
  history.pushState({ path: newUrl }, '', newUrl);

  // Redirect to the filtered route with the updated query string
  window.location.href = newUrl;
});
  // Hide the default search bar
  $('#dtBasicExample1_filter').hide();
  $('#dtBasicExample1_paginate').hide();
  // Implement custom pagination using DataTables API
  var currentPage = dataTable.page.info().page; // Get the initial active page
  function showPage(page) {
    dataTable.page(page).draw(false);
  }
  function updatePageInfo() {
    var pageInfo = dataTable.page.info();
    console.log(pageInfo);
    $('#pageInfo').text('Page ' + (currentPage + 1) + ' of ' + pageInfo.pages);
  }
  // Event handler for table draw event
  dataTable.on('draw.dt', function () {
    currentPage = dataTable.page.info().page; // Update the currentPage variable
    updatePageInfo();
  });

  // Initial page info update
  updatePageInfo();
});
</script>
   {{--@endif--}}
@endsection
