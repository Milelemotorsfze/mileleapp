@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.editing {
    background-color: white !important;
    border: 1px solid black  !important;
}
.upernac {
    margin-top: 1.8rem!important;
}
.float-middle {
  float: none;
  display: block;
  margin: 0 auto;
}
.badge-large {
    font-size: 20px !important;
  }
    </style>
@section('content')
    <div class="card-header">
        @if ($previousId)

    <a class="btn btn-sm btn-info" href="{{ route('purchasing-order.show', $previousId) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>Purchase Order Number : {{$purchasingOrder->po_number}}</b> 
@if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('purchasing-order.show', $nextId) }}">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif

        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        
    </div>
    @php
    $exColours = \App\Models\ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
    $intColours = \App\Models\ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
@endphp
    <div class="card-body">
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
            <div class="card-body">
            <div class="row">
    <div class="col-lg-9 col-md-6 col-sm-12">
        <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-12">
        <label for="choices-single-default" class="form-label"><strong>PO Date</strong></label>
    </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <span>{{date('d-M-Y', strtotime($purchasingOrder->po_date))}}</span>
            </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-12">
        <label for="choices-single-default" class="form-label"><strong>Vendor Name</strong></label>
    </div>
    <div class="col-lg-6 col-md-9 col-sm-12">
        <span>{{ucfirst(strtolower($vendorsname))}}</span>
    </div>
        </div>
        <div class="row">
        <div class="col-lg-2 col-md-3 col-sm-12">
        <label for="choices-single-default" class="form-label"><strong>Total Vehicles</strong></label>
    </div>
    <div class="col-lg-6 col-md-9 col-sm-12">
        <span>{{ count($vehicles) }}</span>
    </div>
        </div>
        <div class="row">
</div>
<br>
<br>
  <div class="row">
  <div class="col-lg-1 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>PO Status</strong></label>
</div>
  <div class="col-lg-3 col-md-3 col-sm-12">
  @if ($purchasingOrder->status === 'Pending Approval')
  <span id="status-badge" class="badge badge-soft-info float-middle badge-large">Not Approved</span>
  @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('delete-po-details');
  @endphp
  @if ($hasPermission)
  <button id="rejection-btn" class="btn btn-danger" onclick="deletepo({{ $purchasingOrder->id }})">Delete</button>
  @endif
  @endif
  @if ($purchasingOrder->status === 'Approved')
    <span id="status-badge" class="badge badge-soft-success float-middle badge-large">Approved</span>
    @elseif ($purchasingOrder->status === 'Rejected')
    <span id="status-badge" class="badge badge-soft-danger float-middle badge-large">Rejected</span>
    @endif
  @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-edit');
  @endphp
  @if ($hasPermission)
  @if ($purchasingOrder->status === 'Pending Approval')
  <button id="approval-btn" class="btn btn-success" onclick="updateStatus('Approved', {{ $purchasingOrder->id }})">Approve</button>
  <button id="rejection-btn" class="btn btn-danger" onclick="updateStatus('Rejected', {{ $purchasingOrder->id }})">Reject</button>
  @endif
  @endif
</div>
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-edit');
  @endphp
  @if ($hasPermission)
  @if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiated Request'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Payment Initiation Request</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="updateallStatus('Approved', {{ $purchasingOrder->id }})">Approve All</button>
  <button id="rejection-btn" class="btn btn-danger" onclick="updateallStatus('Rejected', {{ $purchasingOrder->id }})">Reject All</button>
</div>
@endif
@endif
@if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiated'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Payment Release Request</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
  <button id="approval-btn" class="btn btn-success" onclick="updateallStatusrel('Approved', {{ $purchasingOrder->id }})">Approve All</button>
  <button id="rejection-btn" class="btn btn-danger" onclick="updateallStatusrel('Rejected', {{ $purchasingOrder->id }})">Reject All</button>
</div>
@endif
@endif
  @endif
  @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
  @endphp
  @if ($hasPermission)
  @if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Approved'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Initiation Payment Request</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreq('Approved', {{ $purchasingOrder->id }})">Request for All</button>
</div>
@endif
@endif
@if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Payment Completed'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Vendor Confirmed</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqpocomp('Approved', {{ $purchasingOrder->id }})">Confirmed All</button>
</div>
@endif
@endif
@if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Vendor Confirmed'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Incoming Stock</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqpocompin('Approved', {{ $purchasingOrder->id }})">Incoming All</button>
</div>
@endif
@endif
@endif
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
  @endphp
  @if ($hasPermission)
  @if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('status', 'Request for Payment'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Initiation Payment Request</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfin('Approved', {{ $purchasingOrder->id }})">Request for All</button>
</div>
@endif
@endif
@if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiate Request Approved'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Payment Initiatie</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpay('Approved', {{ $purchasingOrder->id }})">Initiatie for All</button>
</div>
@endif
@endif
@if ($purchasingOrder->status === 'Approved')
  @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Release Approved'))
<div class="col-lg-2 col-md-3 col-sm-12">
  <label for="choices-single-default" class="form-label"><strong>Payment Completed</strong></label>
</div>
  <div class="col-lg-2 col-md-3 col-sm-12">
 
  <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpaycomp('Approved', {{ $purchasingOrder->id }})">Complete All Payments</button>
</div>
@endif
@endif
@endif
</div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <table id="dtBasicExample90" class="table table-striped table-editable table-edits table table-bordered table-sm">
            <thead class="bg-soft-secondary">
            <th style="font-size: 12px;">Status</th>
            <th style="font-size: 12px;">Qty</th>
            </thead>
            <tbody>
            @php
                    $vehiclesapprovedcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Approved')->count();
                    $vehiclesrejectedcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Rejected')->count();
                    $vehiclescountnotapproved = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where(function ($query) {$query->where('status', 'Not Approved')->orWhere('status', 'New Changes');})->count();
                    $vehiclescountpaymentreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Requested')->count();
                    $vehiclescountpaymentrej = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Rejected')->count();
                    $vehiclescountpaymentcom = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Completed')->count();
                    $vehiclescountpaymentincom = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Incoming Stock')->count();
                    $vehiclescountrequestpay = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Request for Payment')->count();
                    $vehiclescountintitail = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiated Request')->count();
                    $vehiclescountintitailreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiate Request Rejected')->count();
                    $vehiclescountintitailapp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiate Request Approved')->count();
                    $vehiclescountintitailrelreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiated')->count();
                    $vehiclescountintitailrelapp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Release Approved')->count();
                    $vehiclescountintitailrelrej = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Release Rejected')->count();
                    $vehiclescountintitailpaycomp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Completed')->count();
                    $vendorpaymentconfirm = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Vendor Confirmed')->count();
                    $vendorpaymentincoming = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Incoming Stock')->count();
                    @endphp
                    <tr>
                    <td style="font-size: 12px;">Vehicles Not Approved</td>
                    <td style="font-size: 12px;">{{ $vehiclescountnotapproved }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"> Vehicles Approved</td>
                    <td style="font-size: 12px;">{{ $vehiclesapprovedcount }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Vehicles Rejected</td>
                    <td style="font-size: 12px;">{{ $vehiclesrejectedcount }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Requested</td>
                    <td style="font-size: 12px;">{{ $vehiclescountrequestpay }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Request Initiated</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitail }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Request Approved</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitailapp }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Request Rejected</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitailreq }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Initiated</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitailrelreq }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Release Approved</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitailrelapp }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Release Rejected</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitailrelrej }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Payment Completed - Acknowledged</td>
                    <td style="font-size: 12px;">{{ $vehiclescountintitailpaycomp }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Vendor Confirmed</td>
                    <td style="font-size: 12px;">{{ $vendorpaymentconfirm }}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">Incoming Stock</td>
                    <td style="font-size: 12px;">{{ $vendorpaymentincoming }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vehicle's Details</h4>
                    <div id="flash-message" class="alert alert-success" style="display: none;"></div>
                    @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
                    <a href="#" class="btn btn-sm btn-primary float-end edit-btn">Edit</a>
                    <a href="#" class="btn btn-sm btn-success float-end update-btn" style="display: none;">Update</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive" >
                    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                            <tr >
                                <th id="serno" style="vertical-align: middle;">Ref No:</th>
                                <th>Variant</th>
                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Variants Detail</th>
                                <th style="vertical-align: middle;" id="estimated">Estimated Arrival</th>
                                <th>Territory</th>
                                <th  style="vertical-align: middle;" id="int_color">Exterior Color</th>
                                <th  style="vertical-align: middle;" id="ex_color">Interior Color</th>
                                <th>VIN Number</th>
                                <th id="serno" style="vertical-align: middle;">Vehicle Status:</th>
                                @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'price-edit']);
                            @endphp
                            @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                <th>Payment Status</th>
                                @endif
                                @endif
                                <th id="action" style="vertical-align: middle;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicles)
                                <tr>
                                @php
                            $variant = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                            $name = $variant->name;
                            $exColour = $vehicles->ex_colour ? DB::table('color_codes')->where('id', $vehicles->ex_colour)->first() : null;
                            $ex_colours = $exColour ? $exColour->name : null;
                            $intColour = $vehicles->int_colour ? DB::table('color_codes')->where('id', $vehicles->int_colour)->first() : null;
                            $int_colours = $intColour ? $intColour->name : null;
                            $detail = $variant->detail;
                            $brands_id = $variant->brands_id;
                            $master_model_lines_id = $variant->master_model_lines_id;
                            $brand = DB::table('brands')->where('id', $brands_id)->first();
                            $brand_names = $brand->brand_name;
                            $master_model_lines_ids = DB::table('master_model_lines')->where('id', $master_model_lines_id)->first();
                            $model_line = $master_model_lines_ids->model_line;
                            @endphp 
                            <td>{{ $vehicles->id }}</td>
                            <td>{{ ucfirst($name) }}</td>
                            <td>{{ ucfirst(strtolower($brand_names)) }}</td>
                            <td>{{ ucfirst(strtolower($model_line)) }}</td>
                            <td>{{ ucfirst(strtolower($detail)) }}</td>
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
							@if ($vehicles->status != 'cancel')
                            <td class="editable-field estimation_date" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            <td class="editable-field territory" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td class="editable-field ex_colour" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td class="editable-field int_colour" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
							@else
							<td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            @endif
							              @endif
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'price-edit']);
                            @endphp
                            @if ($hasPermission)
                            <td>{{ $vehicles->estimation_date }}</td>
                            <td>{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td>
                                <select name="ex_colour[]" class="form-control" placeholder="Exterior Color" disabled>
                                    <option value="">Exterior Color</option>
                                    @foreach ($exColours as $id => $exColour)
                                        @if ($id == $vehicles->ex_colour)
                                            <option value="{{ $id }}" selected>{{ $exColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $exColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="int_colour[]" class="form-control" placeholder="Interior Color" disabled>
                                    <option value="">Interior Color</option>
                                    @foreach ($intColours as $id => $intColour)
                                        @if ($id == $vehicles->int_colour)
                                            <option value="{{ $id }}" selected>{{ $intColour }}</option>
                                        @else
                                            <option value="{{ $id }}">{{ $intColour }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            
                                <td>{{ $vehicles->vin }}</td>
                            @endif
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
                            <td class="editable-field vin" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->vin }}</td>
                            @endif
                            <td>
                            @if($vehicles->status ==="Approved")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @elseif($vehicles->status ==="New Changes")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @elseif($vehicles->status ==="New Vehicles")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @elseif($vehicles->status ==="Rejected")
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @else
                            {{ ucfirst(strtolower( $vehicles->status)) }}
                            @endif
                            </td>
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'price-edit']);
                            @endphp
                            @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                            <td>{{ ucfirst(strtolower($vehicles->payment_status)) }}</td>
                                @endif
                                @endif
                                <td>
                        <div style="display: flex; gap: 10px;">
                        {{-- For Management  --}}
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-edit');
                        @endphp
                        @if ($hasPermission)
                        @if ($vehicles->payment_status === '')
                        @if ($vehicles->status != 'Rejected')
                        <a title="Reject" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.rejecteds', $vehicles->id) }}" onclick="return confirmRejected();"style="white-space: nowrap;">
                            Reject
                        </a>
                        @else
                        <a title="UnReject" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.unrejecteds', $vehicles->id) }}" onclick="return confirmunRejected();" style="white-space: nowrap;">
                            Un-Reject
                        </a>
                        @endif
                        @elseif ($vehicles->payment_status === 'Payment Initiated Request')
                        <div style="display: flex; gap: 10px;">
                        <a title="Payment Initiated Approved" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentreleaseconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px;">
                        Approved
                        </a>
                        <a title="Payment" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.paymentreleaserejected', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px;">
                        Reject
                        </a>
                        @elseif ($vehicles->payment_status === 'Payment Initiated')
                        <div style="display: flex; gap: 10px;">
                        <a title="Payment Release Approved" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentreleasesconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px;">
                        Approved
                        </a>
                        <a title="Payment" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.paymentreleasesrejected', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px;">
                        Reject
                        </a>
                        </div>
                        @endif
                        @endif
                        {{-- For Incoming Confirm  --}}
											@php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->payment_status === 'Vendor Confirmed')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirmincoming', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Incoming Confirmed
											</a>
                      @endif
											@endif
											@endif
											{{-- End For Vendor Confirm  --}}
                        {{-- For Vendor Confirm  --}}
											@php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->payment_status === 'Payment Completed')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirmvendors', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Vendor Confirmed
											</a>
                      @endif
											@endif
											@endif
											{{-- End For Vendor Confirm  --}}
						{{-- End For Management  --}}
						{{-- For Initiate Payment Procurement  --}}
										@php
										$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
										@endphp
										@if ($hasPermission) 
										@if ($purchasingOrder->status === 'Approved')
										@if ($vehicles->status === 'Approved')
										<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Initiate Payment
										</a>
										@endif
										@endif
										@endif
							{{-- End For Initiate Payment procurement  --}}
							{{-- Cancel & Delete for procurement  --}}
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
								@endphp
								@if ($hasPermission)
								@if ($purchasingOrder->status === 'Approved')
								<a title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.cancel', $vehicles->id) }}" onclick="return confirmCancel();" style="white-space: nowrap;">
									Cancel
								</a>
								@elseif ($vehicles->status === 'Pending Approval')
								<a title="Delete" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.deletevehicles', $vehicles->id) }}" onclick="return confirmDelete();" style="white-space: nowrap;">
									Delete
								</a>
								@endif
								@endif
							{{-- End Cancel & Delete For Procurement  --}}
							{{-- For Initiate Payment Finance  --}}
										@php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->status === 'Request for Payment')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentintconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Initiate Payment Request
											</a>
											@endif
											@endif
											@endif
								{{-- End For Initiate Payment Finance  --}}
								{{-- For Release Request  --}}
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
								@endphp
								@if ($hasPermission)
								@if ($purchasingOrder->status === 'Approved')
								@if ($vehicles->payment_status === 'Payment Initiate Request Approved')
								<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
								Payment Initiated
								</a>
								@endif
								@endif
								@endif
								{{-- End For Release Request  --}}
								{{-- For Amount Debited  --}}
									@php
									$hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-payment-details');
									@endphp
									@if ($hasPermission)
									@if ($purchasingOrder->status === 'Approved')
									@if ($vehicles->payment_status === 'Payment Release Approved')
									<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirmdebited', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
									Payment Completed
									</a>
									@endif
									@endif
									@endif
									{{-- End For Amount Debited  --}}
                            </div>
                        </td>
                        </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Add More Vehicles</h4>
                </div>
                <div class="card-body">
                {!! Form::model($purchasingOrder, ['route' => ['purchasing-order.update', $purchasingOrder->id], 'method' => 'PATCH', 'id' => 'purchasing-order']) !!}
                <div id="variantRowsContainer" style="display: none;">
                <div class="table-responsive" >
                    <table id="dtBasicExampledata" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                            <tr >
                                <th>Variants</th>
                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Variants Detail</th>
                                <th>Estimated Arrival</th>
                                <th>Territory</th>
                                <th>Exterior Color</th>
                                <th>Interior Color</th>
                                <th>VIN</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    </div>
            <div class="row">
            <div class="col-lg-2 col-md-6">
                <label for="brandInput" class="form-label">Variants:</label>
                <input type="text" placeholder="Select Variants" name="variant_ider[]" list="variantslist" class="form-control mb-1" id="variants_id" autocomplete="off">
                <datalist id="variantslist">
                @foreach ($variants as $variant)
                <option value="{{ $variant->name }}" data-value="{{ $variant->id }}" data-detail="{{ $variant->detail }}" data-brands_id="{{ $variant->brand_name }}" data-master_model_lines_id="{{ $variant->model_line }}">{{ $variant->name }}</option>
                @endforeach
                </datalist>
                </div>
                <div class="col-lg-1 col-md-6">
                <label for="QTY" class="form-label">Brand:</label>
                <input type="text" id="brands_id" name="brands_id" class="form-control" placeholder="Brand" readonly>
            </div>
            <div class="col-lg-3 col-md-6">
                <label for="QTY" class="form-label">Model Line:</label>
                <input type="text" id="master_model_lines_id" name="master_model_lines_id" class="form-control" placeholder="Model Line" readonly>
            </div>
            <div class="col-lg-4 col-md-6">
                <label for="QTY" class="form-label">Variants Detail:</label>
                <input type="text" id="details" name="details" class="form-control" placeholder="Variants Detail" readonly>
            </div>
            <div class="col-lg-1 col-md-6">
                <label for="QTY" class="form-label">QTY:</label>
                <input type="number" id="QTY" name="QTY" class="form-control" placeholder="QTY">
            </div>
                        <div class="col-lg-1 col-md-6 upernac">
                            <div class="btn btn-primary add-row-btn">
                                <i class="fas fa-plus"></i> Add More
                            </div>
                        </div>
                    </div>
                    <br>
<br>
    <div class="col-lg-12 col-md-12">
        <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" id="submit-button"/>
    </div>
{!! Form::close() !!}
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vehicles Log Details</h4>
                </div>
                <div class="card-body">
            <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
            <tr>
            <th>Ref</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Updated Date</th>
                <th>Updated By</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vehicleslog as $vehicleslog)
        <tr>
        <td>{{ $vehicleslog->vehicles_id}}</td>
        <td>
    @if($vehicleslog->field === "int_colour")
        Interior Colour
    @elseif($vehicleslog->field === "ex_colour")
        Exterior Colour
    @elseif($vehicleslog->field === "payment_status")
        Payment Status
    @elseif($vehicleslog->field === "estimation_date")
        Estimated Date
    @elseif($vehicleslog->field === "territory")
        Territory
    @elseif($vehicleslog->field === "vin")
        VIN
    @else
    {{$vehicleslog->field}}
    @endif
</td>
@if($vehicleslog->field === "int_colour" || $vehicleslog->field === "ex_colour")
@php
$old_value = $vehicleslog->old_value ? DB::table('color_codes')->where('id', $vehicleslog->old_value)->first() : null;
$colourold = $old_value ? $old_value->name : null;
$new_value = $vehicleslog->new_value ? DB::table('color_codes')->where('id', $vehicleslog->new_value)->first() : null;
$colournew = $new_value ? $new_value->name : null;
@endphp
 <td>{{$colourold}}</td>
                                    <td>{{$colournew}}</td>
@elseif($vehicleslog->field === "estimation_date")
@if($vehicleslog->old_value)
<td>{{ date('d-M-Y', strtotime($vehicleslog->old_value)) }}</td>
@else
<td></td>
@endif
<td>{{ date('d-M-Y', strtotime($vehicleslog->new_value)) }}</td>
@else
<td>{{$vehicleslog->old_value}}</td>
<td>{{$vehicleslog->new_value}}</td>
@endif
        <td>{{ date('d-M-Y', strtotime($vehicleslog->date)) }} {{$vehicleslog->time}}</td>
        <td>@php
                    $change_by = DB::table('users')->where('id', $vehicleslog->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ ucfirst(strtolower($change_bys)) }}</td>
        <td>
        @php
        $selected = DB::table('roles')->where('id', $vehicleslog->role)->first();
        $roleselected = $selected ? $selected->name : null;
        @endphp
        {{$roleselected}}</td>
</tr>
@endforeach
        </tbody>
    </table>
</div>
</div>
</div>
<div class="card">
                <div class="card-header">
                    <h4 class="card-title">PO's Log Details</h4>
                </div>
                <div class="card-body">
            <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
            <tr>
            <th>Action</th>
            <th>Variant</th>
            <th>Exterior Color</th>
            <th>Interior Color</th>
            <th>QTY</th>
            <th>Territory</th>
            <th>Estimated Arrival</th>
            <th>Update Date</th>
            <th>Updated By</th>
            <th>Role</th>                  
            </tr>
        </thead>
        <tbody>
    @php
        $previousLog = null;
        $qty = 0;
    @endphp

    @foreach($purchasinglog as $log)
        @php
            // Check if current row is the same as the previous row
            $isSameAsPrevious = ($previousLog &&
            $log->created_by== $previousLog->created_by &&
                $log->estimation_date == $previousLog->estimation_date &&
                $log->territory == $previousLog->territory &&
                $log->ex_colour == $previousLog->ex_colour &&
                $log->int_colour == $previousLog->int_colour &&
                $log->variant == $previousLog->variant &&
                $log->status == $previousLog->status &&
                $log->role == $previousLog->role);
            if (!$isSameAsPrevious) {
                if ($qty > 0) {
                    // Output the row with the previous quantity count
                    echo '<tr>';
                    $change_by = DB::table('users')->where('id', $previousLog->created_by)->first();
                    $change_bys = $change_by->name;
                    $variant = DB::table('varaints')->where('id', $previousLog->variant)->first();
                    $variant_name = $variant->name;
                    echo '<td>'. $previousLog->status .'</td>';
                    echo '<td>'. ucfirst(strtolower($variant_name)) .'</td>';
                    $exColour = $previousLog->ex_colour ? DB::table('color_codes')->where('id', $previousLog->ex_colour)->first() : null;
                    $ex_colours = $exColour ? $exColour->name : null;
                    $intColour = $previousLog->int_colour ? DB::table('color_codes')->where('id', $previousLog->int_colour)->first() : null;
                    $int_colours = $intColour ? $intColour->name : null;
                    echo '<td>'. $ex_colours .'</td>';
                    echo '<td>'. $int_colours .'</td>';
                    echo '<td>'. $qty .'</td>';
                    echo '<td>'. ucfirst(strtolower($previousLog->territory)) .'</td>';
                    echo '<td>'. $previousLog->estimation_date .'</td>';
                    echo '<td>'. date('d-M-Y', strtotime($previousLog->date)) .' '. $previousLog->time .'</td>';
                    echo '<td>'. ucfirst(strtolower($change_bys)) .'</td>';
                    $selected = DB::table('roles')->where('id', $previousLog->role)->first();
                    $roleselected = $selected ? $selected->name : null;
                    echo '<td>'. $roleselected .'</td>';
                    echo '</tr>';
                }
                // Reset the quantity count and update the previous log
                $qty = 1;
                $previousLog = $log;
            } else {
                $qty++; // Increment the quantity count
            }
        @endphp
    @endforeach
    {{-- Output the last group if it exists --}}
@if ($qty > 0)
    <tr>
        <td>{{ $previousLog->status }}</td>
        <td>{{ ucfirst(strtolower($variant_name)) }}</td>
        @php
        $exColour = $previousLog->ex_colour ? DB::table('color_codes')->where('id', $previousLog->ex_colour)->first() : null;
        $ex_colours = $exColour ? $exColour->name : null;
        $intColour = $previousLog->int_colour ? DB::table('color_codes')->where('id', $previousLog->int_colour)->first() : null;
        $int_colours = $intColour ? $intColour->name : null;
        @endphp
        <td>{{ $ex_colours }}</td>
        <td>{{ $int_colours }}</td>
        <td>{{ $qty }}</td>
        <td>{{ ucfirst(strtolower($previousLog->territory)) }}</td>
        <td>{{ $previousLog->estimation_date }}</td>
        <td>{{ date('d-M-Y', strtotime($previousLog->date)) }} {{ $previousLog->time }}</td>
        <td>{{ ucfirst(strtolower($change_bys)) }}</td>
        <td>{{ $roleselected }}</td>   
    </tr>
@endif

</tbody>
</table>
</div>
</div>
</div>
    </div>
    <script>
  $(document).ready(function() {
    $('.select2').select2();

    // Table #dtBasicExample2
    var dataTable2 = $('#dtBasicExample2').DataTable({
        "order": [[4, "desc"]],
      pageLength: 10,
      initComplete: function() {
        this.api().columns().every(function(d) {
          var column = this;
          var columnId = column.index();
          var columnName = $(column.header()).attr('id');
          if (columnName === "statuss") {
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

        // Table #dtBasicExample2
        var dataTable2 = $('#dtBasicExample1').DataTable({
        "order": [[4, "desc"]],
      pageLength: 10,
      initComplete: function() {
        this.api().columns().every(function(d) {
          var column = this;
          var columnId = column.index();
          var columnName = $(column.header()).attr('id');
          if (columnName === "estimated") {
            return;
          }
          if (columnName === "action") {
            return;
          }
          if (columnName === "int_color") {
            return;
          }
          if (columnName === "ex_color") {
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
    // Table #dtBasicExample3
    var dataTable3 = $('#dtBasicExample3').DataTable({
        "order": [[7, "desc"]],
      pageLength: 10,
      initComplete: function() {
        this.api().columns().every(function(d) {
          var column = this;
          var columnId = column.index();
          var columnName = $(column.header()).attr('id');
          if (columnName === "statuss") {
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

    $('.dataTables_filter input').on('keyup', function() {
      dataTable2.search(this.value).draw();
      dataTable3.search(this.value).draw();
    });
  });
</script>
<script>
// Get all editable fields
const editableFields = document.querySelectorAll('.editable-field');
// Get the Edit button and Update Success button
const editBtn = document.querySelector('.edit-btn');
const updateBtn = document.querySelector('.update-btn');
// Add event listener to the Edit button
editBtn.addEventListener('click', () => {
  // Toggle the Edit and Update Success buttons
  editBtn.style.display = 'none';
  updateBtn.style.display = 'block';
  // Enable editing for all editable fields and change their color
  editableFields.forEach(field => {
    field.contentEditable = true;
    field.classList.add('editing');
    // Remove the "disabled" attribute from the select elements
    const selectElement = field.querySelector('select');
    if (selectElement) {
      selectElement.removeAttribute('disabled');
    }
    // Check if the field belongs to the "Estimation Date" column
    const isEstimationDateColumn = field.classList.contains('estimation_date');
    // Check if the field contains a null value and is not in the "Estimation Date" column
    const fieldValue = field.innerText.trim();
    const isNullValue = fieldValue === '';
    if (isNullValue && !isEstimationDateColumn) {
      return;
    }
    // Replace the non-editable field with an editable input field
    if (isEstimationDateColumn) {
      const inputField = document.createElement('input');
      inputField.type = 'date';
      inputField.name = 'oldestimated_arrival[]';
      inputField.value = fieldValue;
      inputField.classList.add('form-control');
      // Replace the field with the input field
      field.innerHTML = '';
      field.appendChild(inputField);
    }
  });
});
updateBtn.addEventListener('click', () => {
  updateBtn.style.display = 'none';
  editBtn.style.display = 'block';
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
  const updatedData = [];
  editableFields.forEach(field => {
    const fieldName = field.classList[1]; 
    const fieldValue = field.innerText.trim();
  const selectElement = field.querySelector('select');
  if (selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const selectedValue = selectedOption.value;
    const selectedText = selectedOption.text;
    updatedData.push({ id: field.getAttribute('data-vehicle-id'), name: fieldName, value: selectedValue });
  } else {
    updatedData.push({ id: field.getAttribute('data-vehicle-id'), name: fieldName, value: fieldValue });
  }
});
console.log(updatedData);
fetch('{{ route('purchasing.updateData') }}', {
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

    // Display the flash message on the page
    const flashMessage = document.getElementById('flash-message');
    flashMessage.textContent = 'Data updated successfully';
    flashMessage.style.display = 'block';

    // Hide the flash message after 5 seconds
    setTimeout(() => {
        flashMessage.style.display = 'none';
    }, 2000);
})
.catch(error => {
    // Handle any errors that occur during the request
    console.error(error);
});
});
</script>
<script>
    $(document).ready(function() {
    $('#variants_id').on('input', function() {
        var selectedVariant = $(this).val();
        var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
        if (variantOption.length > 0) {
            var detail = variantOption.data('detail');
            var brands_id = variantOption.data('brands_id');
            var master_model_lines_id = variantOption.data('master_model_lines_id');
            $('#details').val(detail);
            $('#brands_id').val(brands_id);
            $('#master_model_lines_id').val(master_model_lines_id);
            $('#SelectVariantsId').val(selectedVariant);
        }
    });


    $('.add-row-btn').click(function() {
    var selectedVariant = $('#variants_id').val();
    var variantOption = $('#variantslist').find('option[value="' + selectedVariant + '"]');
    if (variantOption.length === 0) {
        alert('Invalid variant selected');
        return;
    }
    var qty = $('#QTY').val();
    var detail = variantOption.data('detail');
    var brand = variantOption.data('brands_id');
    var masterModelLine = variantOption.data('master_model_lines_id');
    $('.bar').show();

    // Move the declaration and assignment inside the click event function
    var exColours = <?= json_encode($exColours) ?>;
    var intColours = <?= json_encode($intColours) ?>;

    for (var i = 0; i < qty; i++) {
        var newRow = $('<tr></tr>');
            var variantCol = $('<td><input type="hidden" name="variant_id[]" value="' + selectedVariant + '" class="form-control" readonly></div>' + selectedVariant + '</td>');
            var brandCol = $('<td>' + brand + '</td>');
            var masterModelLineCol = $('<td>' + masterModelLine + '</td>');
            var detailCol = $('<td>' + detail + '</td>');
            var estimatedCol = $('<td><input type="date" name="estimated_arrival[]" class="form-control"></td>');
            var territoryCol = $('<td><input type="text" name="territory[]" class="form-control"></td>');
            var exColourCol = $('<td><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></td>');
            var intColourCol = $('<td><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></td>');
            var vinCol = $('<td><input type="text" name="vin[]" class="form-control" placeholder="VIN"></td>');
            var removeBtnCol = $('<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></td>');
// Populate Exterior Colors dropdown
var exColourDropdown = exColourCol.find('select');
            for (var id in exColours) {
                if (exColours.hasOwnProperty(id)) {
                    exColourDropdown.append($('<option></option>').attr('value', id).text(exColours[id]));
                }
            }
            // Populate Interior Colors dropdown
            var intColourDropdown = intColourCol.find('select');
            for (var id in intColours) {
                if (intColours.hasOwnProperty(id)) {
                    intColourDropdown.append($('<option></option>').attr('value', id).text(intColours[id]));
                }
            }

            newRow.append(variantCol, brandCol, masterModelLineCol, detailCol, estimatedCol, territoryCol, exColourCol, intColourCol, vinCol, removeBtnCol);
            $('#dtBasicExampledata tbody').append(newRow);
        }
        $('#variants_id').val('');
        $('#QTY').val('');
        $('#variantRowsContainer').show();
    });

    $(document).on('click', '.remove-row-btn', function() {
    var rowToRemove = $(this).closest('tr');
    var variant = rowToRemove.find('input[name="variant_id[]"]').val();
    var existingOption = $('#variantslist').find('option[value="' + variant + '"]');
    if (existingOption.length === 0) {
        var variantOption = $('<option value="' + variant + '">' + variant + '</option>');
        $('#variantslist').append(variantOption);
    }
    rowToRemove.remove();
    $('.row-space').each(function() {
        if ($(this).next().length === 0) {
            $(this).removeClass('row-space');
        }
    });
    if ($('#variantRowsContainer').find('.row').length === 1) {
        $('.bar').hide();
        $('#variantRowsContainer').hide();
    }
});
});
    </script>
    <script>
  var input = document.getElementById('variants_id');
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
<script>
  function updateStatus(status, orderId) {
  let url = '{{ route('purchasing.updateStatus') }}';
  let data = { status: status, orderId: orderId };

  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      console.error('Error updating status:', error);
    });
}
function updateallStatus(status, orderId) {
  let url = '{{ route('purchasing.updateallStatus') }}';
  let data = { status: status, orderId: orderId };

  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function updateallStatusrel(status, orderId) {
  let url = '{{ route('purchasing.updateallStatusrel') }}';
  let data = { status: status, orderId: orderId };

  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function deletepo(id) {
  let url = '{{ route('purchasing-order.deletes', ['id' => ':id']) }}';
  url = url.replace(':id', id);
  fetch(url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
  })
    .then(response => {
      console.log('Response status:', response.status);
      return response.json();
    })
    .then(data => {
      console.log('Response data:', data);
      window.location.href = '{{ route('purchasing-order.index') }}';
    })
    .catch(error => {
        window.location.href = '{{ route('purchasing-order.index') }}';
    });
}
    </script>
    <script>
  function confirmCancel() {
    var confirmDialog = confirm("Are you sure you want to cancel this Vehicles?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
<script>
  function confirmPayment() {
    var confirmDialog = confirm("Are you sure you want to Payment this Vehicles?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
<script>
  function confirmRejected() {
    var confirmDialog = confirm("Are you sure you want to Reject this Vehicles?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
<script>
  function confirmunRejected() {
    var confirmDialog = confirm("Are you sure you want to Un-Reject this Vehicles?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
  function allpaymentintreq(status, orderId) {
  let url = '{{ route('purchasing.allpaymentreqss') }}';
  let data = { status: status, orderId: orderId };
console.log(data);
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function allpaymentintreqfin(status, orderId) {
  let url = '{{ route('purchasing.allpaymentreqssfin') }}';
  let data = { status: status, orderId: orderId };
console.log(data);
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function allpaymentintreqfinpay(status, orderId) {
  let url = '{{ route('purchasing.allpaymentreqssfinpay') }}';
  let data = { status: status, orderId: orderId };
console.log(data);
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function allpaymentintreqfinpaycomp(status, orderId) {
  let url = '{{ route('purchasing.allpaymentreqssfinpaycomp') }}';
  let data = { status: status, orderId: orderId };
console.log(data);
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function allpaymentintreqpocomp(status, orderId) {
  let url = '{{ route('purchasing.allpaymentintreqpocomp') }}';
  let data = { status: status, orderId: orderId };
console.log(data);
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
function allpaymentintreqpocompin(status, orderId) {
  let url = '{{ route('purchasing.allpaymentintreqpocompin') }}';
  let data = { status: status, orderId: orderId };
console.log(data);
  fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data),
  })
    .then(response => response.json())
    .then(data => {
      console.log('Status update successful');
      window.location.reload();
    })
    .catch(error => {
      window.location.reload();
    });
}
</script>
@endsection