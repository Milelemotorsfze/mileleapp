@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .btn-danger {
    position: relative;
    z-index: 10;
}
    .editing {
        background-color: white !important;
        border: 1px solid black  !important;
    }
    .short-text {
        display: none;
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
    .bar {
        background-color: #778899;
        height: 30px;
        margin: 10px;
        text-align: center;
        color: white;
        line-height: 30px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    .row-space {
        margin-bottom: 10px;
    }
</style>
@section('content')
<!-- Modal -->
<div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="remarksModalLabel">Enter Remarks</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <textarea id="remarks" class="form-control" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitRemarks">Submit</button>
      </div>
    </div>
  </div>
</div>
<div id="remarksrejModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Payment Release</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="remarksRejForm">
                    @csrf
                    <input type="hidden" id="vehicleId" name="vehicleId">
                    <div class="form-group">
                        <label for="remarksrej">Remarks</label>
                        <textarea id="remarksrej" name="remarks" class="form-control" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="submitRemarksrej" class="btn btn-primary">Submit Remarks</button>
            </div>
        </div>
    </div>
</div>
    <div class="card-header">
        <!-- @if ($previousId)
            <a class="btn btn-sm btn-info" href="{{ route('purchasing-order.show', $previousId) }}">
          <i class="fa fa-arrow-left" aria-hidden="true"></i>
      </a>
      @endif -->
        <b>Purchase Order Number : {{$purchasingOrder->po_number}}</b>
        <!-- @if ($nextId)
            <a class="btn btn-sm btn-info" href="{{ route('purchasing-order.show', $nextId) }}">
         <i class="fa fa-arrow-right" aria-hidden="true"></i>
      </a>
      @endif -->
        <a  class="btn btn-sm btn-info float-end" href="{{ route('purchasing-order.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    @php
        $exColours = \App\Models\ColorCode::where('belong_to', 'ex')->pluck('name', 'id')->toArray();
        $intColours = \App\Models\ColorCode::where('belong_to', 'int')->pluck('name', 'id')->toArray();
    @endphp
    <div class="card-body">
    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                    @endphp
                    @if ($hasPermission)
                    <div class ="row">
                    <div class="col-lg-9 col-md-6 col-sm-6">    
                    <a href="#" class="btn btn-sm btn-primary float-end edit-basic-btn" data-purchase-id="{{ $purchasingOrder->id }}">Edit Basic Details</a>
                    <a href="#" class="btn btn-sm btn-success float-end update-basic-btn" style="display: none;">Update Basic Details</a>
                    </div>
                    </div>
                    @endif
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
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="price-update-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-update_basicdetails" action="{{ route('purchasing-order.updatebasicdetails') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fs-5" id="adoncode">Edit Basic Details <span id="addonId"></span></h5>
          <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4 p-3">
                <label for="vendorName" class="form-label font-size-13 text-center">Vendor Name:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="hidden" id="purchasing_order_id" name="purchasing_order_id" class="form-control" value="{{ $purchasingOrder->id }}">
            <select class="form-control" autofocus name="vendors_id" id="vendors" required>
                <option value="" disabled>Select The Vendor</option>
                @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ ($vendor->supplier === $vendorsname) ? 'selected' : '' }}>{{ $vendor->supplier }}</option>
                @endforeach
            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="paymentTerms" class="form-label font-size-13 text-center">Payment Terms:</label>
            </div>
            <div class="col-md-8 p-3">
            <select name="payment_term_id" class="form-select" id="payment_term" required>
                                <option value="" disabled>Select Payment Term</option>
                                @foreach($payments as $payment)
                                    <option value="{{ $payment->id }}"{{ ($payment->id === $paymentterms->id) ? 'selected' : '' }}>{{ $payment->name }}</option>
                                @endforeach
                            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="currency" class="form-label font-size-13 text-center">Currency:</label>
            </div>
            <div class="col-md-8 p-3">
                <select class="form-control" autofocus name="currency" required>
                <option value="AED" {{ $purchasingOrder->currency === 'AED' ? 'selected' : '' }}>AED</option>
                <option value="USD" {{ $purchasingOrder->currency === 'USD' ? 'selected' : '' }}>USD</option>
                <option value="EUR" {{ $purchasingOrder->currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                <option value="GBP" {{ $purchasingOrder->currency === 'GBP' ? 'selected' : '' }}>GBP</option>
                <option value="JPY" {{ $purchasingOrder->currency === 'JPY' ? 'selected' : '' }}>JPY</option>
            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="shippingMethod" class="form-label font-size-13 text-center">Shipping Method:</label>
            </div>
            <div class="col-md-8 p-3">
            <select class="form-control" id="shippingmethod" name="shippingmethod">
                <option value="EXW" {{ $purchasingOrder->shippingmethod === 'EXW' ? 'selected' : '' }}>EXW</option>
                <option value="CNF" {{ $purchasingOrder->shippingmethod === 'CNF' ? 'selected' : '' }}>CNF</option>
                <option value="CIF" {{ $purchasingOrder->shippingmethod === 'CIF' ? 'selected' : '' }}>CIF</option>
                <option value="FOB" {{ $purchasingOrder->shippingmethod === 'FOB' ? 'selected' : '' }}>FOB</option>
                <option value="Local" {{ $purchasingOrder->shippingmethod === 'Local' ? 'selected' : '' }}>Local</option>
            </select>
            </div>
            <div class="col-md-4 p-3">
                <label for="shippingCost" class="form-label font-size-13 text-center">Shipping Cost:</label>
            </div>
            <div class="col-md-8 p-3">
            <input type="number" id="shippingcost" name="shippingcost" class="form-control" placeholder="Shipping Cost" value="{{$purchasingOrder->shippingcost}}">
            </div>
            <div class="col-md-4 p-3">
            <label for="Incoterm" class="form-label">Port of Loading:</label>
            </div>
            <div class="col-md-8 p-3">
              <input type="text" id="pol" name="pol" class="form-control" placeholder="Port of Loading" value="{{$purchasingOrder->pol}}">
            </div>
            <div class="col-md-4 p-3">
            <label for="Incoterm" class="form-label">Port of Discharge:</label>
            </div>
            <div class="col-md-8 p-3">
              <input type="text" id="pod" name="pod" class="form-control" placeholder="Port of Discharge" value="{{$purchasingOrder->pod}}">
            </div>
            <div class="col-md-4 p-3">
            <label for="Incoterm" class="form-label">Preferred Destination:</label>
            </div>
            <div class="col-md-8 p-3">
              <input type="text" id="fd" name="fd" class="form-control" placeholder="Preferred Destination" value="{{$purchasingOrder->fd}}">
            </div>
        </div>
    </div>
</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="submit_b_492" class="btn btn-primary btn-sm createAddonId">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
            <div class="row">
                <div class="col-lg-9 col-md-6 col-sm-12">
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>PO Type</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $purchasingOrder->po_type }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>PO Date</strong></label>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <span>{{date('d-M-Y', strtotime($purchasingOrder->po_date))}}</span>
                        </div>
                    </div>
                    @if($purchasingOrder->is_demand_planning_purchase_order == true)
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <label for="choices-single-default" class="form-label"><strong>PFI Number</strong></label>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-12">
                                <span> {{ $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->pfi_reference_number ?? '' }} </span>
                            </div>
                        </div>
                    @endif
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
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Payment Terms</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $paymentterms->name }} - {{ $paymentterms->description }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Total Cost</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{ $purchasingOrder->totalcost }} - {{ $purchasingOrder->currency }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Shipping Method</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{$purchasingOrder->shippingmethod}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>Shipping Cost</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{$purchasingOrder->shippingcost}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-3 col-sm-12">
                            <label for="choices-single-default" class="form-label"><strong>POL / POD / FD</strong></label>
                        </div>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <span>{{$purchasingOrder->pol}} / {{$purchasingOrder->pod}} / {{$purchasingOrder->fd}}</span>
                        </div>
                    </div>
                    <div class="row"></div>
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
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('po-approval');
                            @endphp
                            @if ($hasPermission)
                                @if ($purchasingOrder->status === 'Pending Approval')
                                    <button id="approval-btn" class="btn btn-success" onclick="updateStatus('Approved', {{ $purchasingOrder->id }})">Approve</button>
                                    <button id="rejection-btn" class="btn btn-danger" onclick="updateStatus('Rejected', {{ $purchasingOrder->id }})">Reject</button>
                                @endif
                            @endif
                        </div>
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                        @endphp
                        @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiated Request'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Payment Initiation Request</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                    <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpay('Approved', {{ $purchasingOrder->id }})">Approval All</button>
                                    </div>
                                @endif
                            @endif
                            @endif
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
                        @endphp
                        @if ($hasPermission)
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
                                        <label for="choices-single-default" class="form-label"><strong>Initiate Payment Request</strong></label>
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
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('re-payment-request');
                        @endphp
                        @if ($hasPermission)
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Release Rejected'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Re-Request for Payment</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="rerequestpayment('Approved', {{ $purchasingOrder->id }})">Re-Request All</button>
                                    </div>
                                @endif
                            @endif
                            @if ($purchasingOrder->status === 'Approved')
                                @if($vehicles->contains('purchasing_order_id', $purchasingOrder->id) && $vehicles->contains('payment_status', 'Payment Initiate Request Approved'))
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <label for="choices-single-default" class="form-label"><strong>Initiate Payment</strong></label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                        <button id="approval-btn" class="btn btn-success" onclick="allpaymentintreqfinpay('Approved', {{ $purchasingOrder->id }})">Initiatie for All</button>
                                    </div>
                                @endif
                            @endif
                        @endif
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                        @endphp
                        @if ($hasPermission)
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
                        <th style="font-size: 12px;">S.No</th>
                        <th style="font-size: 12px;">Status</th>
                        <th style="font-size: 12px;">Qty</th>
                        <th style="font-size: 12px;">Departments</th>
                        </thead>
                        <tbody>
                        @php
                            $vehiclescancelcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->whereNotNull('deleted_at')->count();
                            $vehiclesapprovedcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Approved')->whereNull('deleted_at')->count();
                            $vehiclesrejectedcount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountnotapproved = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where(function ($query) {$query->where('status', 'Not Approved')->orWhere('status', 'New Changes');})->whereNull('deleted_at')->count();
                            $vehiclescountpaymentreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Requested')->whereNull('deleted_at')->count();
                            $vehiclescountpaymentrej = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountpaymentcom = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Payment Completed')->whereNull('deleted_at')->count();
                            $vehiclescountpaymentincom = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Incoming Stock')->whereNull('deleted_at')->count();
                            $vehiclescountrequestpay = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Request for Payment')->whereNull('deleted_at')->count();
                            $vehiclescountintitail = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiated Request')->whereNull('deleted_at')->count();
                            $vehiclescountintitailreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiate Request Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountintitailapp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiate Request Approved')->whereNull('deleted_at')->count();
                            $vehiclescountintitailrelreq = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Initiated')->whereNull('deleted_at')->count();
                            $vehiclescountintitailrelapp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Release Approved')->whereNull('deleted_at')->count();
                            $vehiclescountintitailrelrej = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Release Rejected')->whereNull('deleted_at')->count();
                            $vehiclescountintitailpaycomp = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Payment Completed')->whereNull('deleted_at')->count();
                            $vendorpaymentconfirm = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Vendor Confirmed')->whereNull('deleted_at')->count();
                            $vendorpaymentincoming = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('payment_status', 'Incoming Stock')->whereNull('deleted_at')->count();
                            $serialNumber = 1;   
                            @endphp
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Vehicles Not Approved</td>
                            <td style="font-size: 12px;">{{ $vehiclescountnotapproved }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr> 
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;"> Vehicles Approved</td>
                            <td style="font-size: 12px;">{{ $vehiclesapprovedcount }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Vehicles Rejected</td>
                            <td style="font-size: 12px;">{{ $vehiclesrejectedcount }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Vehicles Cancel</td>
                            <td style="font-size: 12px;">{{ $vehiclescancelcount }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Payment Requested</td>
                            <td style="font-size: 12px;">{{ $vehiclescountrequestpay }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Payment Request</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitail }}</td>
                            <td style="font-size: 12px;">Finance</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Payment Initiated</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailrelreq }}</td>
                            <td style="font-size: 12px;">CEO</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Payment Released</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailrelapp }}</td>
                            <td style="font-size: 12px;">Finance</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Payment Released Rejected</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailrelrej }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Payment Completed - Acknowledged</td>
                            <td style="font-size: 12px;">{{ $vehiclescountintitailpaycomp }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Vendor Confirmed</td>
                            <td style="font-size: 12px;">{{ $vendorpaymentconfirm }}</td>
                            <td style="font-size: 12px;">Procurement</td>
                        </tr>
                        <tr>
                            <td style="font-size: 12px;">{{ $serialNumber++ }}</td>
                            <td style="font-size: 12px;">Incoming Stock</td>
                            <td style="font-size: 12px;">{{ $vendorpaymentincoming }}</td>
                            <td style="font-size: 12px;">Procurement</td>
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
                            <tr>
                                <th id="serno" style="vertical-align: middle;">Ref No:</th>
                                @can('view-vehicle-model-sfx')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-vehicle-model-sfx');
                                    @endphp
                                    @if ($hasPermission)
                                        <th>Model - SFX</th>
                                   @endif
                                @endcan

                                <th>Brand</th>
                                <th>Model Line</th>
                                <th>Variant</th>
                                <th>Variants Detail</th>
                                <th>Price</th>
                                <th style="vertical-align: middle;" id="int_color">Exterior Color</th>
                                <th  style="vertical-align: middle;" id="ex_color">Interior Color</th>
                                <th>Engine Number</th>
                                <th>VIN Number</th>
                                <th>Territory</th>
                                <th style="vertical-align: middle;" id="estimated">Estimated Arrival</th>
                                <th id="serno" style="vertical-align: middle;">Vehicle Status:</th>
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'po-approval', 'edit-po-colour-details', 'cancel-vehicle-purchased-order']);
                                @endphp
                                @if ($hasPermission)
                                    <th>Payment Status</th>
                                @endif
                                <th id="action" style="vertical-align: middle; text-align: center;">Action</th>
                                <th style="vertical-align: middle; text-align: center;">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($vehicles as $vehicles)
                                <tr>
{{--                                @php--}}
{{--                            $variant = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();--}}
{{--                            $name = $variant->name;--}}
{{--                            $exColour = $vehicles->ex_colour ? DB::table('color_codes')->where('id', $vehicles->ex_colour)->first() : null;--}}
{{--                            $ex_colours = $exColour ? $exColour->name : null;--}}
{{--                            $intColour = $vehicles->int_colour ? DB::table('color_codes')->where('id', $vehicles->int_colour)->first() : null;--}}
{{--                            $int_colours = $intColour ? $intColour->name : null;--}}
{{--                            $detail = $variant->detail;--}}
{{--                            $brands_id = $variant->brands_id;--}}
{{--                            $master_model_lines_id = $variant->master_model_lines_id;--}}
{{--                            $brand = DB::table('brands')->where('id', $brands_id)->first();--}}
{{--                            $brand_names = $brand->brand_name;--}}
{{--                            $master_model_lines_ids = DB::table('master_model_lines')->where('id', $master_model_lines_id)->first();--}}
{{--                            $model_line = $master_model_lines_ids->model_line;--}}
{{--                            @endphp--}}
                            <td>{{ $vehicles->id }}</td>
                            @can('view-vehicle-model-sfx')
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-vehicle-model-sfx');
                                @endphp
                                @if ($hasPermission)
                                    <td>
                                        @if($vehicles->model_id)
                                            {{ $vehicles->masterModel->model ?? ''  }} - {{ $vehicles->masterModel->sfx ?? '' }}
                                        @endif
                                    </td>
                                @endif
                            @endcan
                            <td>{{ ucfirst(strtolower($vehicles->variant->brand->brand_name)) }}</td>
                            <td>{{ ucfirst(strtolower($vehicles->variant->master_model_lines->model_line)) }}</td>
                            <td>{{ ucfirst($vehicles->variant->name) }}</td>
                            <td>
                            <span class="full-text">{{ ucfirst(strtolower($vehicles->variant->detail)) }}</span>
                            <span class="short-text"></span>
                            <a href="#" class="read-more">Read more</a>
                          </td>
                          <td>{{ ucfirst($vehicles->VehiclePurchasingCost->unit_price ?? '') }}</td>
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-po-colour-details');
                            @endphp
                            @if ($hasPermission)
                            @if($vehicles->grn_id === null)
							              @if ($vehicles->status != 'cancel')
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
                            <td class="editable-field engine" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                            <td class="editable-field vin" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->vin }}</td>
                            <td class="editable-field territory" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td class="editable-field estimation_date" contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            @else
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
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->engine }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->vin }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                            <td contenteditable="false" data-vehicle-id="{{ $vehicles->id }}">{{ $vehicles->estimation_date }}</td>
                            @endif
							              @endif
                            @endif
                            @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'po-approval']);
                            @endphp
                            @if ($hasPermission)
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
                            <td>{{ $vehicles->engine }}</td>
                            <td>{{ $vehicles->vin }}</td>
                            <td>{{ ucfirst(strtolower($vehicles->territory)) }}</td>
                                <td>{{ $vehicles->estimation_date }}</td>
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
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-po-payment-details', 'po-approval', 'edit-po-colour-details']);
                            @endphp
                            @if ($hasPermission)
                            <td>{{ ucfirst(strtolower($vehicles->payment_status)) }}</td>
                                @endif
                                <td style ="width:160px;">
                                <div class="row">
                                <div class="col-lg-12" style="display: inline-flex;">
                                <div class="col-lg-8">
                        {{-- For Management  --}}
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('po-approval');
                        @endphp
                        @if ($hasPermission)
                        @if ($vehicles->payment_status === '')
                        @if($vehicles->status == 'Request for Cancel')
                        <a title="Reject" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.approvedcancel', $vehicles->id) }}" onclick="return confirmApprovedcancel();"style="white-space: nowrap;">
                            Approved Cancel
                        </a>
                        @elseif ($vehicles->status != 'Rejected' && $vehicles->status != 'Request for Payment')
                        <a title="Reject" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.cancel', $vehicles->id) }}" onclick="return confirmRejected();"style="white-space: nowrap;">
                            Reject / Cancel
                        </a>
                        @elseif ($vehicles->status == 'Rejected')
                        <a title="UnReject" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.unrejecteds', $vehicles->id) }}" onclick="return confirmunRejected();" style="white-space: nowrap;">
                            Un-Reject
                        </a>
                        @endif
                        @endif
                        @endif
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
                        @endphp
                        @if ($hasPermission)
                        @if ($vehicles->payment_status === 'Payment Initiated')
                        <div style="display: flex; gap: 10px;">
                        <a title="Payment Release Approved" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentreleasesconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px;">
                        Approved
                        </a>
                        <button data-placement="top" class="btn btn-sm btn-danger" onclick="return openModal('{{ $vehicles->id }}');" style="margin-right: 10px;">Reject</button>
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
										@if ($vehicles->status === 'Approved' && $vehicles->payment_status === '')
										<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Initiate Payment
										</a>
										@endif
										@endif
										@endif
							{{-- End For Initiate Payment procurement  --}}
							{{-- For Initiate Payment Finance  --}}
                            @if ($vehicles->payment_status === 'Payment Initiated Request')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
                        @endphp
                        @if ($hasPermission)
                        <div style="display: flex; gap: 10px;">
                        <a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentrelconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
                        Approved
                        </a>
                        @endif
                        @endif
										@php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-request-approval');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->status === 'Request for Payment')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.paymentintconfirm', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Approved Payment
											</a>
											@endif
											@endif
											@endif
                                            @php
											$hasPermission = Auth::user()->hasPermissionForSelectedRole('re-payment-request');
											@endphp
											@if ($hasPermission)
											@if ($purchasingOrder->status === 'Approved')
											@if ($vehicles->payment_status === 'Payment Release Rejected')
											<a title="Payment" data-placement="top" class="btn btn-sm btn-success" href="{{ route('vehicles.repaymentintiation', $vehicles->id) }}" onclick="return confirmPayment();" style="margin-right: 10px; white-space: nowrap;">
											Re-Payment Request
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
									$hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-initiated');
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
                            <div class="col-lg-4">
								{{-- Cancel & Delete for procurement  --}}
								@php
								$hasPermission = Auth::user()->hasPermissionForSelectedRole('cancel-vehicle-purchased-order');
								@endphp
								@if ($hasPermission)
								@if ($purchasingOrder->status === 'Approved'  || $purchasingOrder->status === 'Pending Approval' && $vehicles->payment_status === '')
                                @if($vehicles->status !== "Request for Cancel")
								<a title="Cancel" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.cancel', $vehicles->id) }}" onclick="return confirmCancel();" style="white-space: nowrap;">
									Cancel
								</a>
                                @endif
								@elseif ($vehicles->status === 'Pending Approval')
								<a title="Delete" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('vehicles.deletevehicles', $vehicles->id) }}" onclick="return confirmDelete();" style="white-space: nowrap;">
									Delete
								</a>
								@endif
								@endif
							{{-- End Cancel & Delete For Procurement  --}}
                        </div>
                        </div>
							</div>
                        </td>
                        <td>{{ ucfirst(strtolower($vehicles->procurement_vehicle_remarks)) }}</td>
                        </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('add-more-vehicles-po');
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
                                        <th>Exterior Color</th>
                                        <th>Interior Color</th>
                                        <th>Unit Price</th>
                                        <th>Estimated Arrival</th>
                                        <th>Engine Number</th>
                                        <th>VIN</th>
                                        <th>Territory</th>
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
                                <input type="hidden" id="currency" name="currency" class="form-control" readonly value="{{$purchasingOrder->currency}}">
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
                                <label for="unitprice" class="form-label">Unit Price:</label>
                                <input type="number" id="unit_price" name="unit_price" class="form-control" placeholder="Unit Price">
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

            @can('edit-demand-planning-po')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-demand-planning-po');
                @endphp
                @if ($hasPermission)
{{--                    @if($variantCount > 0)--}}
                       @include('purchase-order.po_add_vehicles')
{{--                    @endif--}}
                @endif
            @endcan

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
                                        {{ ucfirst(strtolower($change_bys)) }}
                                    </td>
                                    <td>
                                        @php
                                            $selected = DB::table('roles')->where('id', $vehicleslog->role)->first();
                                            $roleselected = $selected ? $selected->name : null;
                                        @endphp
                                        {{$roleselected}}
                                    </td>
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
                                    echo '
                                    <tr>
                                       ';
                                       $change_by = DB::table('users')->where('id', $previousLog->created_by)->first();
                                       $change_bys = $change_by->name;
                                       $variant = DB::table('varaints')->where('id', $previousLog->variant)->first();
                                       $variant_name = $variant->name;
                                       echo '
                                       <td>'. $previousLog->status .'</td>
                                       ';
                                       echo '
                                       <td>'. ucfirst(strtolower($variant_name)) .'</td>
                                       ';
                                       $exColour = $previousLog->ex_colour ? DB::table('color_codes')->where('id', $previousLog->ex_colour)->first() : null;
                                       $ex_colours = $exColour ? $exColour->name : null;
                                       $intColour = $previousLog->int_colour ? DB::table('color_codes')->where('id', $previousLog->int_colour)->first() : null;
                                       $int_colours = $intColour ? $intColour->name : null;
                                       echo '
                                       <td>'. $ex_colours .'</td>
                                       ';
                                       echo '
                                       <td>'. $int_colours .'</td>
                                       ';
                                       echo '
                                       <td>'. $qty .'</td>
                                       ';
                                       echo '
                                       <td>'. ucfirst(strtolower($previousLog->territory)) .'</td>
                                       ';
                                       echo '
                                       <td>'. $previousLog->estimation_date .'</td>
                                       ';
                                       echo '
                                       <td>'. date('d-M-Y', strtotime($previousLog->date)) .' '. $previousLog->time .'</td>
                                       ';
                                       echo '
                                       <td>'. ucfirst(strtolower($change_bys)) .'</td>
                                       ';
                                       $selected = DB::table('roles')->where('id', $previousLog->role)->first();
                                       $roleselected = $selected ? $selected->name : null;
                                       echo '
                                       <td>'. $roleselected .'</td>
                                       ';
                                       echo '
                                    </tr>
                                    ';
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
                                    @php
                                        $change_by = DB::table('users')->where('id', $previousLog->created_by)->first();
                                        $change_bys = $change_by->name;
                                        $variant = DB::table('varaints')->where('id', $previousLog->variant)->first();
                                        $variant_name = $variant->name;
                                        $exColour = $previousLog->ex_colour ? DB::table('color_codes')->where('id', $previousLog->ex_colour)->first() : null;
                                        $ex_colours = $exColour ? $exColour->name : null;
                                        $intColour = $previousLog->int_colour ? DB::table('color_codes')->where('id', $previousLog->int_colour)->first() : null;
                                        $int_colours = $intColour ? $intColour->name : null;
                                        $selected = DB::table('roles')->where('id', $previousLog->role)->first();
                                        $roleselected = $selected ? $selected->name : null;
                                    @endphp
                                    <td>{{ $previousLog->status }}</td>
                                    <td>{{ ucfirst(strtolower($variant_name)) }}</td>
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
            let targetUrl;
    function openModal(id) {
        targetUrl = window.location.href; 
        $('#vehicleId').val(id);
        $('#remarksrejModal').modal('show');
        return false;  // Prevent default button behavior
    }

    $('#submitRemarksrej').click(function() {
        var vehicleId = $('#vehicleId').val();
        var remarks = $('#remarksrej').val();
        var token = $('input[name="_token"]').val();
        var url = '{{ route("vehicles.paymentreleasesrejected", ":id") }}';
        url = url.replace(':id', vehicleId);

        if (remarks) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: token,
                    remarks: remarks
                },
                success: function(response) {
                    // Handle the response here
                    $('#remarksrejModal').modal('hide');
                    alert('Remarks submitted successfully');
                    // Redirect to the target URL after successful submission
                    window.location.href = targetUrl;
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    alert('An error occurred. Please try again.');
                }
            });
        } else {
            alert('Please provide remarks.');
        }
    });
</script>
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
         @php
       $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
       @endphp
       @if (!$hasPermission)
        <script>
            const editableFields = document.querySelectorAll('.editable-field');
            const editBtn = document.querySelector('.edit-btn');
            const updateBtn = document.querySelector('.update-btn');
            editBtn.addEventListener('click', () => {
                editBtn.style.display = 'none';
                updateBtn.style.display = 'block';
                editableFields.forEach(field => {
                    field.contentEditable = true;
                    field.classList.add('editing');
                    const selectElement = field.querySelector('select');
                    if (selectElement) {
                        selectElement.removeAttribute('disabled');
                    }
                    const isEstimationDateColumn = field.classList.contains('estimation_date');
                    const fieldValue = field.innerText.trim();
                    const isNullValue = fieldValue === '';
                    if (isNullValue && !isEstimationDateColumn) {
                        return;
                    }
                    if (isEstimationDateColumn) {
                        const inputField = document.createElement('input');
                        inputField.type = 'date';
                        inputField.name = 'oldestimated_arrival[]';
                        inputField.value = fieldValue;
                        inputField.classList.add('form-control');
                        field.innerHTML = '';
                        field.appendChild(inputField);
                    }
                });
            });
            updateBtn.addEventListener('click', () => {
    checkDuplicateVIN(function(vinCheckResult) {
        console.log(vinCheckResult);
        if (vinCheckResult === false) {
            return;
        } else {
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
                console.log(data);
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
        }
    });
});
        </script>
        @endif
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
                    var unitPrice = $('#unit_price').val();
                    console.log(unitPrice);
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
                        var estimatedCol = $('<td><input type="date" name="estimated_arrival[]" class="form-control"></td>');
                        var territoryCol = $('<td><input type="text" name="territory[]" class="form-control"></td>');
                        var exColourCol = $('<td><select name="ex_colour[]" class="form-control"><option value="">Exterior Color</option></select></td>');
                        var intColourCol = $('<td><select name="int_colour[]" class="form-control"><option value="">Interior Color</option></select></td>');
                        var unitPriceCol = $('<td><input type="text" name="unit_prices[]" value="' + unitPrice + '" class="form-control" readonly></td>');
                        var vinCol = $('<td><input type="text" name="vin[]" class="form-control" placeholder="VIN"></td>');
                        var territory = $('<td><input type="text" name="territory[]" class="form-control"></td>');
                        var engineCol = $('<td><input type="text" name="engine_number[]" class="form-control" placeholder="Engine"></td>');
                        var removeBtnCol = $('<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fas fa-times"></i></button></td>');
                        var exColourDropdown = exColourCol.find('select');
                        for (var id in exColours) {
                            if (exColours.hasOwnProperty(id)) {
                                exColourDropdown.append($('<option></option>').attr('value', id).text(exColours[id]));
                            }
                        }
                        var intColourDropdown = intColourCol.find('select');
                        for (var id in intColours) {
                            if (intColours.hasOwnProperty(id)) {
                                intColourDropdown.append($('<option></option>').attr('value', id).text(intColours[id]));
                            }
                        }
                        newRow.append(variantCol, brandCol, masterModelLineCol,exColourCol, intColourCol, unitPriceCol, estimatedCol,engineCol, vinCol, territory, removeBtnCol);
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
       @php
       $hasPermission = Auth::user()->hasPermissionForSelectedRole('payment-release-approval');
       @endphp
       @if (!$hasPermission)
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
        @endif
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
    if (status === 'Rejected') {
        let remarksModal = new bootstrap.Modal(document.getElementById('remarksModal'));
        remarksModal.show();

        document.getElementById('submitRemarks').onclick = function() {
            let remarks = document.getElementById('remarks').value;

            if (remarks.trim() === '') {
                alert('Please enter remarks.');
                return;
            }
            postUpdateStatus(status, orderId, remarks);
            remarksModal.hide();
        };
    } else {
        postUpdateStatus(status, orderId);
    }
}
function postUpdateStatus(status, orderId, remarks = '') {
    let url = '{{ route('purchasing.updateallStatusrel') }}';
    let data = { status: status, orderId: orderId, remarks: remarks };
    
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
            function confirmApprovedcancel() {
                var confirmDialog = confirm("Are you sure you want to Approved this Vehicles Cancel?");
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
            function rerequestpayment(status, orderId) {
                let url = '{{ route('purchasing.rerequestpayment') }}';
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
            $(document).ready(function() {
                var maxLength = 50;
                var $text = $('.full-text');
                var $shortText = $('.short-text');
                var fullText = $text.text();
                var shortText = fullText.substr(0, maxLength);
                $text.text(shortText);
                $shortText.text(fullText.substring(maxLength));

                $('.read-more').click(function(event) {
                    event.preventDefault();
                    var $this = $(this);
                    if ($this.text() === 'Read more') {
                        $this.text('Read less');
                        $text.hide();
                        $shortText.show();
                    } else {
                        $this.text('Read more');
                        $text.show();
                        $shortText.hide();
                    }
                });
            });
        </script>
        <script>
    function checkDuplicateVIN(callback) {
        var vinValues = Array.from(document.querySelectorAll('.editable-field.vin')).map(function(field) {
            return field.innerText.trim();
        });
        var allBlank = vinValues.every(function(value) {
            return value.trim() === '';
        });
        if (allBlank) {
            console.log("All VIN values are blank");
            callback(1);
            // Indicate all values are blank
        } else {
            var Po = "{{$purchasingOrder->id}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ route('vehicles.check-create-vins-inside') }}',
                method: 'POST',
                data: { vins: vinValues, po: Po },
                success: function(response) {
                    if (response === 'duplicate') {
                        alert('Duplicate VIN values found in the database. Please ensure all VIN values are unique.');
                        callback(false); // Indicate duplicate values found
                    } else {
                        callback(1); // Indicate all values are unique
                    }
                },
                error: function() {
                    alert('An error occurred while checking for VIN duplication. Please try again.');
                    callback(false); // Indicate error occurred
                }
            });
        }
    }
</script>
<script>
$(document).ready(function() {
  $('.edit-basic-btn').on('click', function() {
    var purchaseId = $(this).data('purchase-id');
    $('#purchaseId').val(purchaseId); // Set the value in the hidden input field
    $('#editModal').modal('show'); // Show the modal
  });
});
</script>
<script>
$(document).ready(function() {
    $('#form-update_basicdetails').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            success: function(response) {
                // Handle success response, maybe show a success message
                alert('Purchase order details updated successfully!');
                location.reload(); // Refresh the page
            },
            error: function(xhr, status, error) {
                // Handle error response, maybe show an error message
                console.error(xhr.responseText);
                alert('An error occurred while updating purchase order details.');
            }
        });
    });
});
</script>

@endsection
