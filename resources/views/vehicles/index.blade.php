@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <h4 class="card-title">View Vehicles Details</h4>
                <div id="flash-message" class="alert alert-success" style="display: none;"></div>
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['inspection-edit','warehouse-edit','conversion-edit',
                     'vehicles-detail-edit','enginee-edit','document-edit','bl-edit','edit-so','edit-reservation']);
                @endphp
                @if ($hasPermission)
                <a href="#" class="btn btn-sm btn-primary float-end edit-btn">Edit</a>
                    <a href="#" class="btn btn-sm btn-success float-end update-btn" style="display: none;">Update</a>
                @endif
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
                                    <th class="nowrap-td">Sales Person</th>
                                    @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
                                @endphp
                                @if ($hasPermission)
                                    <th class="nowrap-td">Reservation Date</th>
                                    <th class="nowrap-td">Reservation Due Date</th>
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
                                    <th class="nowrap-td">Model Year</th>
                                    <th class="nowrap-td">Steering</th>
                                    <th class="nowrap-td">Seats</th>
                                    <th class="nowrap-td">Fuel Type</th>
                                    <th class="nowrap-td">Transmission</th>
                                    <th class="nowrap-td" style="min-width:150px">Ext Colour</th>
                                    <th class="nowrap-td" style="min-width:150px">Int Colour</th>
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
                                    <th class="nowrap-td"id="log" style="vertical-align: middle;">Changes Log</th>
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
                                                $latestRemarksales = DB::table('vehicles_remarks')->where('vehicles_id', $vehicles->id)->where('department', 'sales')->orderBy('created_at', 'desc')->value('remarks');
                                                $latestRemarkwarehouse = DB::table('vehicles_remarks')->where('vehicles_id', $vehicles->id)->where('department', 'warehouse')->orderBy('created_at', 'desc')->value('remarks');
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
                                <td class="nowrap-td eta">ETA</td>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view');
                                @endphp
                                @if ($hasPermission)
                                <td class="nowrap-td eta">{{date('d-M-Y', strtotime($vehicles->estimation_date))}}</td>
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
                                     <td class="nowrap-td grnDate">
                                     {{ date('d-M-Y', strtotime($grn_date)) }}</td>
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
                                    <td class="editable-field sales_person_id" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                        <select name="sales_person_id" class="form-control" placeholder="sales_person_id" disabled>
                                                <option value=""></option>
                                                @foreach ($sales as $sale)
                                                    <option value="{{ $sale->id }} " {{ $salesname == $sale->name ? 'selected' : '' }}>{{ $sale->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    <td class="editable-field reservation_start_date" data-is-date="true" data-type="date" data-vehicle-id="{{ $vehicles->id }}" data-field-name="reservation_start_dates">
                                    {{ $vehicles->reservation_start_date }}</td>
                                    <td class="editable-field reservation_end_date" data-is-date="true" data-type="date" data-vehicle-id="{{ $vehicles->id }}" data-field-name="reservation_end_date">
                                    {{ $vehicles->reservation_end_date }}</td>
									                  @else
                                    <td>
                                        <select name="sales_person_id" class="form-control" placeholder="sales_person_id" disabled>
                                                <option value=""></option>
                                                @foreach ($sales as $sale)
                                                    <option value="{{ $sale->id }} " {{ $salesname == $sale->name ? 'selected' : '' }}>{{ $sale->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
									                  <td>
                                    {{ $vehicles->reservation_start_date }}</td>
                                    <td>
                                    {{ $vehicles->reservation_end_date }}</td>	
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
                                                <a href="{{ route('vehiclesremarks.viewremarks', $vehicles->id) }}" class="read-more" target="_blank">View All</a>
                                            @endif
                                        </td>
									                @else 
									                <td>{{$latestRemarkwarehouse}}
                                            @if($latestRemarkwarehouse)
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
										                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
                                    @endphp
                                    @if ($hasPermission)
                                     <td class="nowrap-td brand">
                                       <span id="brand-{{$vehicles->id}}">  {{ $vehicles->variant->brand->brand_name ?? ''}} </span>
                                     </td>
                                     <td class="nowrap-td">
                                       <span id="model-line-{{$vehicles->id}}">
                                           {{$vehicles->variant->master_model_lines->model_line ?? ''}}
                                       </span>
                                     </td>
                                     <td class="nowrap-td">
                                         <span id="model-description-{{$vehicles->id}}">
                                             {{ $vehicles->variant->model_detail ?? '' }}
                                         </span>
                                     </td>
                                     <td class="editable-field varaints_id" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                    <select name="varaints_id" class="form-control" placeholder="varaints_id" disabled>
                                    @foreach($varaint as $variantItem)
                                                 <option value="{{$variantItem->id}}" {{ $variantItem->id == $vehicles->varaints_id ? "selected" : "" }}>
                                                     {{ $variantItem->name }}</option>
                                            @endforeach
                                    </select>
                                    </td>
                                     <td class="nowrap-td">
                                         <span id="variant-detail-{{ $vehicles->id }}">
                                             {{ $vehicles->detail ?? '' }}
                                         </span>
                                     </td>
                                    @else
                                      <td class="nowrap-td brand">
                                       <span id="brand-{{$vehicles->id}}">  {{ $vehicles->variant->brand->brand_name ?? ''}} </span>
                                     </td>
                                     <td class="nowrap-td">
                                       <span id="model-line-{{$vehicles->id}}">
                                           {{$vehicles->variant->master_model_lines->model_line ?? ''}}
                                       </span>
                                     </td>
                                     <td class="nowrap-td">
                                         <span id="model-description-{{$vehicles->id}}">
                                             {{ $vehicles->variant->model_detail ?? '' }}
                                         </span>
                                     </td>
                                     <td>
                                    <select name="varaints_id" class="form-control" placeholder="varaints_id" disabled>
                                    @foreach($varaint as $variantItem)
                                                 <option value="{{$variantItem->id}}" {{ $variantItem->id == $vehicles->varaints_id ? "selected" : "" }}>
                                                     {{ $variantItem->name }}</option>
                                            @endforeach
                                    </select>
                                    </td>
                                     <td class="nowrap-td">
                                         <span id="variant-detail-{{ $vehicles->id }}">
                                             {{ $vehicles->detail ?? '' }}
                                         </span>
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
                                         								                                         <td class="nowrap-td">
                                             <span id="my-{{ $vehicles->id }}"> {{ $vehicles->variant->my }}</span>
                                         </td>
                                        <td class="nowrap-td">
                                            <span id="steering-{{ $vehicles->id }}"> {{ $vehicles->variant->steering }}</span>
                                        </td>
                                        <td class="nowrap-td">
                                            <span id="seat-{{ $vehicles->id }}"> {{ $vehicles->variant->seat }}</span>
                                        </td>
                                        <td class="nowrap-td">
                                            <span id="fuel-type-{{ $vehicles->id }}"> {{ $vehicles->variant->fuel_type }}</span>
                                        </td>
                                        <td class="nowrap-td">
                                            <span id="gearbox-{{ $vehicles->id }}"> {{ $vehicles->variant->gearbox }}</span>
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
                                        <td class="nowrap-td Upholestry">
                                          <span id="upholestry-{{ $vehicles->id }}"> </span>  {{ $vehicles->variant->upholestry ?? '' }}
                                        </td>
										                    @else
										                    <td class="nowrap-td">
                                             <span id="my-{{ $vehicles->id }}"> {{ $vehicles->variant->my }}</span>
                                         </td>
                                        <td class="nowrap-td">
                                            <span id="steering-{{ $vehicles->id }}"> {{ $vehicles->variant->steering }}</span>
                                        </td>
                                        <td class="nowrap-td">
                                            <span id="seat-{{ $vehicles->id }}"> {{ $vehicles->variant->seat }}</span>
                                        </td>
                                        <td class="nowrap-td">
                                            <span id="fuel-type-{{ $vehicles->id }}"> {{ $vehicles->variant->fuel_type }}</span>
                                        </td>
                                        <td class="nowrap-td">
                                            <span id="gearbox-{{ $vehicles->id }}"> {{ $vehicles->variant->gearbox }}</span>
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
                                        <td class="nowrap-td Upholestry">
                                          <span id="upholestry-{{ $vehicles->id }}"> </span>  {{ $vehicles->variant->upholestry ?? '' }}
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
                                        <td class="editable-field ppmmyyy" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->ppmmyyy }}</td>
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
                                        <td class="nowrap-td">{{ $warehouses }}</td>
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
                                            @if($latestRemarkwarehouse)
                                                <a href="{{ route('vehiclesremarks.viewremarks', ['id' => $vehicles->id, 'type' => 'WareHouse'] ) }}" class="read-more" target="_blank">View All</a>
                                            @endif
                                        </td>
                                        @else
                                          <td>{{ $latestRemarkwarehouse }}
                                            @if($latestRemarkwarehouse)
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
                                        <td>
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
                                        <td><a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehicleslog.viewdetails', $vehicles->id) }}" onclick="event.stopPropagation();"></i> View Log</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
      flashMessage.textContent = 'Update Data Sucessfully Or Submit The Approval Request Successfully';
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

   {{--@endif--}}
@endsection
