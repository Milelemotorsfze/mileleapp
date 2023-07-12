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
        <form action="{{ route('vehicles.update-vehicle-details') }}" method="POST" >
            @csrf
            @foreach($data as $value => $vehicle)
                <input type="hidden" value="{{ $vehicle->id }}" name="vehicle_ids[]">
            @endforeach
            @if ($hasPermission)
                <div class="card-header">
                    <h4 class="card-title">View Vehicles Details</h4>
                {{--    @php--}}
                {{--        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');--}}
                {{--    @endphp--}}
                {{--    @if ($hasPermission)--}}
                        <button type="button" class="btn btn-sm btn-primary float-end edit-vehicle">Edit</button>
                        <button type="submit" class="btn btn-sm btn-success float-end update-vehicle-details" hidden >Update</button>
                {{--    @endif--}}
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
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
                                    <th class="nowrap-td" style="min-width:150px">Ex Colour</th>
                                    <th class="nowrap-td" style="min-width:150px">Int Colour</th>
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
                                    <tr >
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
                                                    $bl_status = $documents ? $documents->bl_status : null;
                                                    $latestRemark = DB::table('vehicles_remarks')->where('vehicles_id', $vehicles->id)->where('department', 'sales')->orderBy('created_at', 'desc')->value('remarks');
                                                    @endphp
                                         @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                                        @endphp
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
                                            <td class="nowrap-td">
                                                <input type="date" class="form-control inspection-date" readonly name="inspection_dates[]" value="{{ $vehicles->inspection_date }}">
                                            </td>

{{--                                        <input type="hidden" class="inspection" value="{{date('d-M-Y', strtotime($vehicles->inspection_date)) }}">--}}
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
                                         <td class="nowrap-td brand">
                                           <span id="brand-{{$vehicles->id}}">  {{ $vehicles->variant->brand->brand_name ?? ''}} </span>
{{--                                             <span id="brand-{{$vehicles->id}}"></span>--}}
                                         </td>
                                         <td class="nowrap-td">
                                           <span id="model-line-{{$vehicles->id}}">
                                               {{$vehicles->variant->master_model_lines->model_line ?? ''}}
                                           </span>
                                         </td>
                                         <td class="nowrap-td">{{ $vehicles->vin }}</td>
                                         <td class="nowrap-td Variant">
                                             <select class="form-control variant" data-vehicle-id="{{$vehicles->id}}" id="variant-{{$vehicles->id}}"
                                                     data-brand="{{$vehicles->variant->brand->brand_name ?? '' }}" data-model-line="{{ $vehicles->variant->master_model_lines->model_line ?? '' }}"

                                                     name="variants_ids[]" disabled  >
                                                @foreach($varaint as $variantItem)
                                                     <option value="{{$variantItem->id}}" {{ $variantItem->id == $vehicles->varaints_id ? "selected" : "" }}> {{ $variantItem->name }}</option>
                                                @endforeach
                                             </select>
                                         </td>
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
                                         <td class="nowrap-td Engine">
                                             <input type="text" readonly class="form-control engine" name="engines[]" value=" {{ $vehicles->engine }}">
                                            </td>
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
                                            <td class="nowrap-td">
                                                <select class="form-control exterior_colour " name="exterior_colours[]" readonly  >
                                                    @foreach($exteriorColours as $exColour)
                                                        <option value="{{$exColour->id}} " {{ $exColour->id == $vehicles->ex_colour ? 'selected' : "" }}   >
                                                            {{ $exColour->name }}</option>
                                                    @endforeach
                                                </select>
{{--                                                {{ $ex_colours }}--}}
                                            </td>
{{--                                            <input type="hidden" class="ExColour" value="{{ $vehicles->ex_colour }}">--}}
                                            <td class="nowrap-td">
                                                <select class="form-control interior_colour " name="interior_colours[]" readonly  >
                                                    @foreach($interiorColours as $interiorColour)
                                                        <option value="{{$interiorColour->id}} " {{ $interiorColour->id == $vehicles->int_colour ? 'selected' : "" }}   >
                                                            {{ $interiorColour->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
{{--                                            <input type="hidden" class="IntColour" value="{{ $vehicles->int_colour }}">--}}
                                            <td class="nowrap-td Upholestry">{{ $varaints_upholestry }}</td>
                                            @endif
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
                                            @endphp
                                            @if ($hasPermission)
{{--                                            @if ($vehicles->ppmmyyy)--}}
{{--                                            <input type="hidden" class="Ppmmyy" value="{{ $vehicles->ppmmyyy }}">--}}
                                            <td class="nowrap-td">
{{--                                                {{ date('d-M-Y', strtotime($vehicles->ppmmyyy)) }}--}}
                                                <input type="date" class="form-control py-mm-yyyy" name="pymmyyyy[]" value="{{ $vehicles->ppmmyyy }}" readonly>
                                            </td>
{{--                                            @else--}}
{{--                                            <td class="nowrap-td Ppmmyy"></td>--}}
{{--                                            @endif--}}
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
                                                                @php
                                              $pictures = DB::table('vehicle_pictures')->where('vehicle_id', $vehicles->id)->latest()->first();
                                              $pictures_link = $pictures ? $pictures->vehicle_picture_link : null;
                                          @endphp
                                            <td>
                                            @if ($pictures_link)
                                            <a title="Vehicles Pictures Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ $pictures_link }}" onclick="event.stopPropagation();" target="_blank">View Pic</a>
                                                @else
                                                <a title="Vehicles Pictures Details" data-placement="top" class="btn btn-sm btn-primary" href="" onclick="event.stopPropagation();"> View Pic</a>
                                                @endif
                                              </td>
                                            <td><a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehicleslog.viewdetails', $vehicles->id) }}" onclick="event.stopPropagation();" target="_blank"></i> View Log</a></td>
                                        </tr>
                                    @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            @endif
        </form>

{{--@php--}}
{{--    $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-edit');--}}
{{--@endphp--}}
{{--@if ($hasPermission)--}}
<script>
        @php
        // QC
           $hasPermissionInspectionEdit = Auth::user()->hasPermissionForSelectedRole('inspection-edit');
           $hasPermissionVehicleVariant = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-edit');
           $hasPermissionEngineEdit = Auth::user()->hasPermissionForSelectedRole('enginee-edit');
           $hasPermissionPP_MM_YYEdit = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');


         @endphp
        $('.variant').change(function () {
            var Id = $(this).val();
            var vehicleId = $(this).attr('data-vehicle-id');
            var brand = $(this).attr('data-brand');
            var modelLine = $(this).attr('data-model-line');

            var url = '{{ route('vehicles.getVehicleDetails') }}';

            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    variant_id: Id,
                },
                success:function (data) {
                    console.log(data);
                    $('#brand-'+vehicleId).html(data.brand);
                    $('#model-line-'+vehicleId).text(data.model_line);
                }
            });
        })

       $('.edit-vehicle').click(function() {
           // alert("ok");
           $('.edit-vehicle').hide();
           $('.update-vehicle-details').attr('hidden',false);
          @if($hasPermissionInspectionEdit)
           $('.inspection-date').attr('readonly',false);
          @endif
          @if($hasPermissionVehicleVariant)
            $('.variant').attr('disabled',false);
            $('.exterior_colour').attr('readonly',false);
            $('.interior_colour').attr('readonly',false);
          @endif
         @if($hasPermissionEngineEdit)
          $('.engine').attr('readonly',false);
         @endif

        @if($hasPermissionPP_MM_YYEdit)
         $('.py-mm-yyyy').attr('readonly',false);

        @endif

       })
   </script>
   {{--@endif--}}
   @endsection
