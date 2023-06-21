@extends('layouts.table')
<style>
#table-responsive {
  height: 100vh;
  overflow-y: auto;
}
#dtBasicSupplierInventory {
  width: 100%;
  font-size: 14px;
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
@if (Auth::user()->selectedRole === '2' || Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4' || Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8'|| Auth::user()->selectedRole === '9'|| Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '11'|| Auth::user()->selectedRole === '12'|| Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14'|| Auth::user()->selectedRole === '15'|| Auth::user()->selectedRole === '16'|| Auth::user()->selectedRole === '17'|| Auth::user()->selectedRole === '18'|| Auth::user()->selectedRole === '21'|| Auth::user()->selectedRole === '22')
<div class="card-header">
        <h4 class="card-title">View Vehicles Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
<div class="card-body">
    <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '6'|| Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '8'|| Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14'|| Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22')
                    <th class="nowrap-td">PO Number</th>
                    <th class="nowrap-td">PO Date</th>
                    @endif
                    <th class="nowrap-td">GRN</th>
                    <th class="nowrap-td">GRN Date</th>
                    @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12'|| Auth::user()->selectedRole === '6'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8'|| Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22')
                    <th class="nowrap-td">Aging</th>
                    @endif
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '22' || Auth::user()->selectedRole === '8'||Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '15'|| Auth::user()->selectedRole === '16')
                    <th class="nowrap-td">SO</th>
                    <th class="nowrap-td">SO Date</th>
                    <th class="nowrap-td">Sales Person</th>
                    <th class="nowrap-td">Booking Status</th>
                    @endif
                    @if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' ||Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '14' || Auth::user()->selectedRole === '4' || Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6' || Auth::user()->selectedRole === '15' || Auth::user()->selectedRole === '16' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                    <th class="nowrap-td">GDN</th>
                    <th class="nowrap-td">GDN Date</th>
                    @endif
                    @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10' || Auth::user()->selectedRole === '6' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8')
                    <th class="nowrap-td">Remarks</th>
                    @endif
                    @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6')
                    <th class="nowrap-td">Conversion</th>
                    @endif
                    @if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12'|| Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10' ||Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '14' || Auth::user()->selectedRole === '4' || Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6' || Auth::user()->selectedRole === '15' || Auth::user()->selectedRole === '16' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                    @can('vehicles-detail-view')
                    <th class="nowrap-td">Variant</th>
                    <th class="nowrap-td">Variant Detail</th>
                    <th class="nowrap-td">Brand</th>
                    <th class="nowrap-td">Model Line</th>
                    <th class="nowrap-td">Model Description</th>
                    <th class="nowrap-td">VIN</th>
                    <th class="nowrap-td">Engine</th>
                    <th class="nowrap-td">MY</th>
                    <th class="nowrap-td">Steering</th>
                    <th class="nowrap-td">Seats</th>
                    <th class="nowrap-td">Fuel</th>
                    <th class="nowrap-td">Gear</th>
                    <th class="nowrap-td">Ex Colour</th>
                    <th class="nowrap-td">Int Colour</th>
                    <th class="nowrap-td">Upholestry</th>
                    <th class="nowrap-td">PY MM YYYY</th>
                    <th class="nowrap-td">Warehouse</th>
                    @endcan
                    @endif
                    @can('price-view')
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8')
                    <th class="nowrap-td">Price</th>
                    @endif
                    @endcan
                    @can('territory-view')
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8')
                    <th class="nowrap-td">Territory</th>
                    @endif
                    @endcan
                    @can('document-view')
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8')
                    <th class="nowrap-td">Import Document Type</th>
                    <th class="nowrap-td">Document Ownership</th>
                    <th class="nowrap-td">Documents With</th>
                    <th class="nowrap-td">DUCAMZ IN/OUT</th>
                    <th class="nowrap-td">BL</th>
                    @endif
                    @endcan
                    <th class="nowrap-td">Changes Log</th>
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
                     $booking_name = "";
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
                     $int_colours = $exColour ? $intColour->name : null;
                     $variants = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                     $name = $variants->name;
                     $grn = $vehicles->grn_id ? DB::table('grn')->where('id', $vehicles->grn_id)->first() : null;
                     $grn_date = $grn ? $grn->date : null;
                     $grn_number = $grn ? $grn->grn_number : null;
                     $gdn = $vehicles->gdn_id ? DB::table('gdn')->where('id', $vehicles->gdn_id)->first() : null;
                     $gdn_date = $gdn ? $gdn->date : null;
                     $gdn_number = $gdn ? $gdn->gdn_number : null;
                     $so = $vehicles->so_id ? DB::table('so')->where('id', $vehicles->so_id)->first() : null;
                     $so_date = $so ? $so->so_date : null;
                     $so_number = $so ? $so->so_number : null;
                     $payment_percentage = $so ? $so->payment_percentage : null;
                     $sales_person_id = $so ? $so->sales_person_id : null;
                    $sales_person = $sales_person_id ? DB::table('users')->where('id', $sales_person_id)->first() : null;
                    $salesname = $sales_person ? $sales_person->name : null;
                    $booking = $vehicles->booking_id ? DB::table('booking')->where('id', $vehicles->booking_id)->first() : null;
                    $booking_name = $booking ? $booking->name : null;
                    $conversion = $vehicles->conversion_id ? DB::table('conversion')->where('id', $vehicles->conversion_id)->first() : null;
                    $conversions = $conversion ? $conversion->id : null;
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
                    $bl = $vehicles->bl_id ? DB::table('bl')->where('id', $vehicles->bl_id)->first() : null;
                        $bl_number = $bl ? $bl->bl_number : null;
                     @endphp
                     @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12'|| Auth::user()->selectedRole === '6'|| Auth::user()->selectedRole === '8'|| Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14'|| Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22')
                     <td class="nowrap-td PoDate">{{ date('d-m-Y', strtotime($po_date)) }}</td>
                     <td class="nowrap-td PoNumber">PO - {{ $po_number }}</td>
                     @endif
                     @if ($grn_number)
                     <td class="nowrap-td grnNumber">GRN - {{ $grn_number }}</td>
                     @else
                     <td class="nowrap-td grnNumber">-</td>
                     @endif
                     @if ($grn_date)
                     <td class="nowrap-td grnDate">{{ date('d-m-Y', strtotime($grn_date)) }}
                     @else
                     <td class="nowrap-td grnDate">-</td>
                    @endif
                     </td>
                     @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '6'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22')
                     <td class="nowrap-td">{{ $aging }}</td>
                     @endif
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '22' ||Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '15'|| Auth::user()->selectedRole === '16')
                     @if ($so_number)
                     <td class="nowrap-td so_number">{{ $so_number }}</td>
                     @else
                     <td class="nowrap-td">-</td>
                     @endif
                     @if ($so_date)
                     <td class="nowrap-td">{{ date('d-m-Y', strtotime($so_date)) }}</td>
                     <input type="hidden" class="so_date" value="{{ $so_date }}">
                     <input type="hidden" class="payment_percentage" value="{{ $payment_percentage }}">
                     @else
                     <td class="nowrap-td">-</td>
                     @endif
                     <td class="nowrap-td">{{ $salesname }}</td>
                     <input type="hidden" class="sales_person" value="{{ $sales_person_id }}">
                     <td class="nowrap-td">{{ $booking_name }}</td>
                     @endif
                    @if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '8' ||Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14' || Auth::user()->selectedRole === '4' || Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6' || Auth::user()->selectedRole === '15' || Auth::user()->selectedRole === '16' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                     @if ($gdn_number)
                     <td class="nowrap-td gdnNumber">GDN - {{ $gdn_number }}</td>
                     @else
                     <td class="nowrap-td gdnNumber">-</td>
                     @endif
                     @if ($gdn_date)
                     <td class="nowrap-td gdnDate">{{ date('d-m-Y', strtotime($gdn_date)) }}</td>
                     @else
                     <td class="nowrap-td gdnDate">-</td>
                     @endif
                     @endif
                     @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22' || Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10' || Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '6' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7'|| Auth::user()->selectedRole === '8')
                     <td class="nowrap-td Remarks">{{ $vehicles->remarks }}</td>
                     @endif
                     @if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6')
                     <td class="nowrap-td">{{ $conversions }}</td>
                     @endif
                     @if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '9' || Auth::user()->selectedRole === '10'|| Auth::user()->selectedRole === '8' ||Auth::user()->selectedRole === '13'|| Auth::user()->selectedRole === '14' || Auth::user()->selectedRole === '4' || Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6' || Auth::user()->selectedRole === '15' || Auth::user()->selectedRole === '16' || Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                    @can('vehicles-detail-view')
                     <td class="nowrap-td Variant">{{ $varaints_name }}</td>
                        <td class="nowrap-td">{{ $varaints_detail }}</td>
                        <td class="nowrap-td">{{ $brand_name }}</td>
                        <td class="nowrap-td">{{ $model_line }}</td>
                        <td class="nowrap-td">{{ $vehicles->vin }}</td>
                        <td class="nowrap-td Vin">{{ $vehicles->vin }}</td>
                        <td class="nowrap-td Engine">{{ $vehicles->engine }}</td>
                        <td class="nowrap-td">{{ $varaints_my }}</td>
                        <td class="nowrap-td">{{ $varaints_steering }}</td>
                        <td class="nowrap-td">{{ $varaints_seat }}</td>
                        <td class="nowrap-td">{{ $varaints_fuel_type }}</td>
                        <td class="nowrap-td">{{ $varaints_gearbox }}</td>
                        <td class="nowrap-td">{{ $ex_colours }}
                        <input type="hidden" class="ExColour" value="{{ $vehicles->ex_colour }}">
                        </td>
                        <td class="nowrap-td">{{ $int_colours }}
                        <input type="hidden" class="IntColour" value="{{ $vehicles->int_colour }}">
                        </td>
                        <td class="nowrap-td Upholestry">{{ $varaints_upholestry }}</td>
                        @if ($gdn_date)
                        <td class="nowrap-td">{{ date('d-m-Y', strtotime($vehicles->ppmmyyy)) }}
                        <input type="hidden" class="Ppmmyy" value="{{ $vehicles->ppmmyyy }}">
                        </td>
                        @else
                        <td class="nowrap-td Ppmmyy"></td>
                        @endif
                        <td class="nowrap-td">{{ $warehouses }}</td>
                        @endcan
                    @endif
                    @can('price-view')
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                        <td class="nowrap-td">{{ $vehicles->price }}</td>
                        @endif
                    @endcan
                    @can('territory-view')
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                        <td class="nowrap-td Territory">{{ $vehicles->territory }}</td>
                        @endif
                    @endcan
                    @can('document-view')
                    @if (Auth::user()->selectedRole === '21' || Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12' || Auth::user()->selectedRole === '8' || Auth::user()->selectedRole === '22'|| Auth::user()->selectedRole === '7')
                        <td class="nowrap-td import_type">{{ $import_type }}</td>
                        <td class="nowrap-td owership">{{ $owership }}</td>
                        <td class="nowrap-td document_with">{{ $document_with }}</td>
                        <td class="nowrap-td">{{ $vehicles->documzinout}}</td>
                        <th class="nowrap-td">{{ $bl_number }}</td>
                       @endif
                        @endcan
                        <td>
                        <a title="Vehicles Log Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('vehicleslog.viewdetails', $vehicles->id) }}"><i class="fa fa-car" aria-hidden="true"></i> View Log</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
  @can('vehicles-detail-edit')
  @if (Auth::user()->selectedRole === '13' || Auth::user()->selectedRole === '14')
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
    <form action="{{ route('vehicles.updatedata')}}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-4">
  <label for="variants_name">Variant</label>
  <input type="text" class="form-control" id="variants_name" name="variants_name" list="laList" value="{{ $varaints_name }}">
  <datalist id="laList">
    @foreach ($varaint as $varaints)
      <option value="{{ $varaints->name }}">{{ $varaints->name }}</option>
    @endforeach
  </datalist>
</div>
      <div class="col-md-4">
        <label for="gdn_date">VIN</label>
        <input type="text" class="form-control" id="vin" name="vin" value="{{ $vehicles->vin }}">
      </div>
      <div class="col-md-4">
        <label for="gdn_date">Engine</label>
        <input type="text" class="form-control" id="engine" name="engine" value="{{ $vehicles->engine }}">
      </div>
      <div class="col-md-4">
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
      <div class="col-md-4">
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
        <label for="gdn_date">Territory</label>
        <input type="text" class="form-control" id="territory" name="territory" value="{{ $vehicles->territory }}">
      </div>
      <div class="col-md-4">
        <label for="gdn_date">PP MM YYYY</label>
        <input type="date" class="form-control" id="ppmmyy" name="ppmmyy" value="{{ $vehicles->ppmmyyy }}">
      </div>
    </div>
    <div class="form-group">
      <label for="remarks">Remarks</label>
      <input type="text" class="form-control" id="remarks" name="remarks" value="{{ $vehicles->remarks }}">
      <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="{{ $vehicles->id }}">
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
@endcan
@can('document-edit')
@if (Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12')
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
    <form action="{{ route('vehicles.updateso')}}" method="POST">
    @csrf
    <div class="row">
      <div class="col-md-4">
  <label for="import_document">Import Document Type</label>
        <select name="import_type" id="import_type" class="form-control" placeholder="Import Document Type">
        <option value="">Import Docuemnt</option>
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
        <option value="">Document Ownership</option>
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
        <option value="">Document With</option>
        <option value="Accounts">Accounts</option>
        <option value="Finance Department">Finance Department</option>
        <option value="Import Department">Import Department</option>
        <option value="Not Applicable">Not Applicable</option>
        <option value="Supplier">Supplier</option>
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
@endcan
@can('edit-so')
@if (Auth::user()->selectedRole === '7' || Auth::user()->selectedRole === '8')
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
</div>
<div class="col-md-4">
  <label for="so_number">SO Date</label>
    <input type="date" class="form-control" id="so_date" name="so_date" value="{{ $so_date }}">    
    <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="{{ $vehicles->id }}">
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
@endcan
@can('vehicles-detail-edit')
@if (Auth::user()->selectedRole === '13' || Auth::user()->selectedRole === '14')
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var variant = row.find('.Variant').text();
  var Vin = row.find('.Vin').text();
  var Engine = row.find('.Engine').text();
  var ExColour = row.find('.ExColour').val();
  var IntColour = row.find('.IntColour').val();
  var Territory = row.find('.Territory').text();
  var Ppmmyy = row.find('.Ppmmyy').val();
  var Remarks = row.find('.Remarks').text();
  $('#variants_name').val(variant);
  $('#vin').val(Vin);
  $('#engine').val(Engine);
  $('#ex_colour').val(ExColour);
  $('#int_colour').val(IntColour);
  $('#territory').val(Territory);
  $('#ppmmyy').val(Ppmmyy);
  $('#remarks').val(Remarks);
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
@endcan
@can('document-edit')
@if (Auth::user()->selectedRole === '11' || Auth::user()->selectedRole === '12')
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var import_type = row.find('.import_type').text();
  var owership = row.find('.owership').text();
  var document_with = row.find('.document_with').text();
  $('#import_type').val(import_type);
  $('#owership').val(owership);
  $('#document_with').val(document_with);
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
@endcan
@can('edit-so')
@if (Auth::user()->selectedRole === '7' || Auth::user()->selectedRole === '8')
<script>
  function openModal(vehicleId) {
  var row = $('tr[data-id="' + vehicleId + '"]');
  var so_number = row.find('.so_number').text();
  var so_date = row.find('.so_date').val();
  var sales_person = row.find('.sales_person').val();
  var payment_percentage = row.find('.payment_percentage').val();
  console.log(sales_person);
  $('#so_number').val(so_number);
  $('#so_date').val(so_date);
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
@endcan
<script>
$(document).ready(function() {
  $('.select2').select2();
  var dataTable = $('#dtBasicSupplierInventory').DataTable({
    ordering: false,
    initComplete: function() {
      this.api().columns().every(function(d) {
        var column = this;
        var theadname = $("#dtBasicSupplierInventory th").eq([d]).text();
        var select = $('<select class="form-control my-1"><option value="">All</option></select>')
          .appendTo($(column.header()))
          .on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            column.search(val ? '^' + val + '$' : '', true, false).draw();
          });
        $(column.header()).find('.caret').remove();
        if ($(column.header()).find('input').length > 0) {
          $(column.header()).addClass('nowrap-td');
          var uniqueValues = column.data().toArray().map(function(value) {
            return $(value).find('input').val();
          }).filter(function(value, index, self) {
            return self.indexOf(value) === index;
          });

          uniqueValues.sort().forEach(function(value) {
            select.append('<option value="' + value + '">' + value + '</option>');
          });
        } else {
          column.data().unique().sort().each(function(d, j) {
            select.append('<option value="' + d + '">' + d + '</option>');
          });
        }
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
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
