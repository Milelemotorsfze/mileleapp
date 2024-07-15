@extends('layouts.table')
<style>
     /* Custom style for Select2 dropdown with orange highlight */
     .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1e90ff;
        color: white;
    }
  .table-responsive {
  overflow: auto;
  max-height: 650px; /* Adjust the max-height to your desired value */
}
.table-wrapper {
  position: relative;
}
thead th {
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
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po-details');
                    @endphp
                    @if ($hasPermission)
    <div class="card-header">
    @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-available-funds');
                    @endphp
                    @if ($hasPermission)
    <a class="btn btn-sm btn-info" href="#" style="text-align: right; margin-right: 10px;">
    Total Outflows : {{ number_format($suggestedPaymentTotalAED, 0, '', ',') }} AED
</a>
<a class="btn btn-sm btn-success" href="#" style="text-align: right;">
    Total Available Funds is {{ number_format($availableFunds, 0, '', ',') }} AED
</a>
@endif
  <br>
  <br>
        <h4 class="card-title">
            Purchase Orders Summary
        </h4>
        <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
        <table id="dtBasicExample21" class="table table-striped table-editable table-edits table table-bordered table-sm">
        <thead>
       <th style="font-size: 12px;">Not Approved Purchase Order</th>
       <th style="font-size: 12px;">Approved Purchase Order</th>
       <th style="font-size: 12px;">In-Progress Purchase Order</th>
       <th style="font-size: 12px;">Closed Purchase Order</th>
       <th style="font-size: 12px;">Cancel Purchase Order</th>
    </thead>
    <tbody>
        <tr>
        <td onclick="window.location='{{ route('purchasing.filter', ['status' => 'Pending Approval']) }}';">
        @php
        $pendongpoapproval = 0;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Pending Approval')->count();
        @endphp
        @else
        @php
        $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Pending Approval')
        ->count();
        @endphp
        @endif
        @if ($pendongpoapproval > 0)
            {{ $pendongpoapproval }}
        @else
            0
        @endif
    </td>
    <td onclick="window.location='{{ route('purchasing.filterapprovedonly', ['status' => 'Approved']) }}';">
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $userId = auth()->user()->id;
        $alreadyapproved = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Approved');
    })
    ->count();
        @endphp
        @else
        @php
        $userId = auth()->user()->id;
        $alreadyapproved = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Approved');
    })
    ->count();
        @endphp
        @endif
        @if ($alreadyapproved > 0)
            {{ $alreadyapproved }}
        @else
            0
        @endif
    </td>
    <td onclick="window.location='{{ route('purchasing.filterapproved', ['status' => 'Approved']) }}';">
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
    $userId = auth()->user()->id;
    $inProgressPurchasingOrders = DB::table('vehicles')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('purchasing_order')
                ->whereColumn('vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->where('purchasing_order.status', '<>', 'Cancelled');
        })
        ->where(function ($query) {
            $query->where('status', 'Request for Payment')
                ->orWhere(function ($query) {
                    $query->whereNotIn('payment_status', ['Payment Initiate Request Rejected', 'Request Rejected', 'Payment Release Rejected', 'Incoming Stock'])
                          ->where(function ($query) {
                              $query->whereNotNull('payment_status')
                                    ->where('payment_status', '<>', '');
                          });
                });
        })
        ->distinct('vehicles.purchasing_order_id')
        ->count('vehicles.purchasing_order_id');
        @endphp
        @else
        @php    
        $inProgressPurchasingOrders = DB::table('vehicles')
    ->whereExists(function ($query) use ($userId) {
        $query->select(DB::raw(1))
            ->from('purchasing_order')
            ->whereColumn('vehicles.purchasing_order_id', '=', 'purchasing_order.id')
            ->where('purchasing_order.status', '<>', 'Cancelled');
    })
    ->where(function ($query) {
        $query->where('status', 'Request for Payment')
            ->orWhere(function ($query) {
                $query->whereNotIn('payment_status', ['Payment Initiate', 'Request Rejected', 'Payment Release Rejected', 'Incoming Stock'])
                    ->where(function ($query) {
                        $query->whereNotNull('payment_status')
                            ->where('payment_status', '<>', '');
                    });
            });
    })
    ->distinct('vehicles.purchasing_order_id')
    ->count('vehicles.purchasing_order_id');
@endphp
@endif
    @if ($inProgressPurchasingOrders > 0)
        {{ $inProgressPurchasingOrders }}
    @else
        0
    @endif
</td>
<td onclick="window.location='{{ route('purchasing.filterincomings', ['status' => 'Approved']) }}';">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
    $completedPos = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
    ->orwhere('purchasing_order.status', 'Cancelled')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Incoming Stock');
    })
        ->count();
    @endphp
        @else
        @php
    $completedPos = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Incoming Stock');
    })
    ->count();
    @endphp
    @endif
    @if ($completedPos > 0)
        {{ $completedPos }}
    @else
        0
    @endif
</td>
<td onclick="window.location='{{ route('purchasing.filtercancel', ['status' => 'Cancelled']) }}';">
        @php
        $pendongpoapproval = 0;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Cancelled')->count();
        @endphp
        @else
        @php
        $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Cancelled')
        ->count();
        @endphp
        @endif
        @if ($pendongpoapproval > 0)
            {{ $pendongpoapproval }}
        @else
            0
        @endif
    </td>
        </tr>
    </tbody>
  </table>
</div>
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-po-details');
                    @endphp
                    @if ($hasPermission)
      <div class="col-lg-3 col-md-3 col-sm-12">
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('purchasing-order.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Purchasing Order
      </a>
      <div class="clearfix"></div>
      <br>
      </div>
      @endif
</div>
<hr>
<h4 class="card-title">
            Pending Task
        </h4>
        <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
        <table id="dtBasicExample21" class="table table-striped table-editable table-edits table table-bordered table-sm">
        <thead>
       <th style="font-size: 12px;">BOD Task</th>
       <th style="font-size: 12px;">PO QTY</th>
    </thead>
    <tbody>
<tr onclick="window.location='{{ route('purchasing.filterpaymentrel', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterpaymentrel', ['status' => 'Approved']) }}">
        Pending Payment Release
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    @endphp
    @if ($hasPermission)
    @php
    $pendingpaymentrelsa = DB::table('purchasing_order')
                ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Initiated');
            })
            ->count();
        @endphp
        @else
        @php
        $pendingpaymentrelsa = DB::table('purchasing_order')
                ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Initiated');
            })
            ->count();
            @endphp
        @endif
        @if ($pendingpaymentrelsa > 0)
            {{ $pendingpaymentrelsa }}
        @else
            No records found
        @endif
    </td>
</tr>
</tbody>
  </table>
</div>
<div class="col-lg-3 col-md-3 col-sm-12">
        <table id="dtBasicExample21" class="table table-striped table-editable table-edits table table-bordered table-sm">
        <thead>
       <th style="font-size: 12px;">Finance Task</th>
       <th style="font-size: 12px;">PO QTY</th>
    </thead>
    <tbody>
<tr onclick="window.location='{{ route('purchasing.filterpendingrelease', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterpendingrelease', ['status' => 'Approved']) }}">
        Pending Payment Release Initiation
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    @endphp
    @if ($hasPermission)
    @php
    $pendingreleasereqs = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Initiated Request');
            })
            ->count();
        @endphp
        @else
        @php
        $pendingreleasereqs = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Initiated Request');
            })
            ->count();
            @endphp
            @endif
        @if ($pendingreleasereqs > 0)
            {{ $pendingreleasereqs }}
        @else
            No records found
        @endif
    </td>
</tr>
<tr onclick="window.location='{{ route('purchasing.filterpendingdebits', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterpendingdebits', ['status' => 'Approved']) }}">
        Pending Payment Completion
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    @endphp
    @if ($hasPermission)
    @php
    $pendingdebitaps = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Release Approved');
            })
            ->count();
        @endphp
        @else
        @php
    $pendingdebitaps = DB::table('purchasing_order')
    ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Release Approved');
            })
            ->count();
        @endphp
        @endif
        @if ($pendingdebitaps > 0)
            {{ $pendingdebitaps }}
        @else
            No records found
        @endif
    </td>
</tr>
</tbody>
  </table>
</div>
<div class="col-lg-3 col-md-3 col-sm-12">
        <table id="dtBasicExample21" class="table table-striped table-editable table-edits table table-bordered table-sm">
        <thead>
       <th style="font-size: 12px;">Procurement Manager Task</th>
       <th style="font-size: 12px;">PO QTY</th>
    </thead>
    <tbody>
    <tr onclick="window.location='{{ route('purchasing.filter', ['status' => 'Pending Approval']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filter', ['status' => 'Pending Approval']) }}">
            Pending Purchase Order Approval
        </a>
    </td>
    <td style="font-size: 12px;">
        @php
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('po-approval');
        @endphp
        @if ($hasPermission)
        @php
        $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Pending Approval')->count();
        @endphp
        @else
        @php
        $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Pending Approval')->where(function($query) use ($userId) {
        $query->where('created_by', $userId)
              ->orWhere('created_by', 16);
        })
        ->count();
        @endphp
        @endif
        @if ($pendongpoapproval > 0)
            {{ $pendongpoapproval }}
        @else
            No records found
        @endif
    </td>
</tr>
<tr onclick="window.location='{{ route('purchasing.filterpayment', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterpayment', ['status' => 'Approved']) }}">
        Pending Payment Request
        </a>
    </td>
    <td style="font-size: 12px;">
        @php
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    @endphp
    @if ($hasPermission)
    @php
        $pendingints = DB::table('purchasing_order')
            ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.status', 'Request for Payment');
            })
            ->count();
        @endphp
        @else
        @php
        $pendingints = DB::table('purchasing_order')
            ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.status', 'Request for Payment');
            })
            ->count();
        @endphp
        @endif
        @if ($pendingints > 0)
            {{ $pendingints }}
        @else
            No records found
        @endif
    </td>
</tr>
<tr onclick="window.location='{{ route('purchasing.filterpaymentrejectioned', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterpaymentrejectioned', ['status' => 'Approved']) }}">
        Rejected Payment Releases
        </a>
    </td>
    <td style="font-size: 12px;">
        @php
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    @endphp
    @if ($hasPermission)
    @php
        $paymentreleasedrejection = DB::table('purchasing_order')
            ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Release Rejected');
            })
            ->count();
        @endphp
        @else
        @php
        $paymentreleasedrejection = DB::table('purchasing_order')
            ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.payment_status', 'Payment Release Rejected');
            })
            ->count();
        @endphp
        @endif
        @if ($paymentreleasedrejection > 0)
            {{ $paymentreleasedrejection }}
        @else
            No records found
        @endif
    </td>
</tr>
</tbody>
  </table>
</div>
<div class="col-lg-3 col-md-3 col-sm-12">
        <table id="dtBasicExample21" class="table table-striped table-editable table-edits table table-bordered table-sm">
        <thead>
       <th style="font-size: 12px;">Procurement Task</th>
       <th style="font-size: 12px;">PO QTY</th>
    </thead>
    <tbody>
    <tr onclick="window.location='{{ route('purchasing.paymentinitiation', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.paymentinitiation', ['status' => 'Approved']) }}">
        Pending Payments Initiation
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $pendingvendorfol = DB::table('purchasing_order')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->where('status', 'Approved');
            });
    })
    ->count();
@endphp
@else
@php
$pendingvendorfol = DB::table('purchasing_order')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->where('status', 'Approved');
            });
    })
    ->count();
@endphp
@endif
        @if ($pendingvendorfol > 0)
            {{ $pendingvendorfol }}
        @else
            No records found
        @endif
    </td>
</tr>
    <tr onclick="window.location='{{ route('purchasing.filterpendingfellow', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterpendingfellow', ['status' => 'Approved']) }}">
        Pending Vendor Follow Up
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $pendingvendorfol = DB::table('purchasing_order')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->Where('payment_status', 'Payment Completed');
            });
    })
    ->count();
@endphp
@else
@php
$pendingvendorfol = DB::table('purchasing_order')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->Where('payment_status', 'Payment Completed');
            });
    })
    ->count();
@endphp
@endif
        @if ($pendingvendorfol > 0)
            {{ $pendingvendorfol }}
        @else
            No records found
        @endif
    </td>
</tr>
<tr onclick="window.location='{{ route('purchasing.pendingvins', ['status' => 'missingvins']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.pendingvins', ['status' => 'missingvins']) }}">
        Missing Vin Numbers POs
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $pendingvins= DB::table('purchasing_order')
        ->where('created_by', '!=', '16')
    ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('vehicles')
                  ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                  ->whereNull('deleted_at')
                  ->whereNull('vin'); // Check for at least one VIN being null
        })
    ->count();
@endphp
@else
@php
$pendingvins = DB::table('purchasing_order')
->where('created_by', '!=', '16')
    ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('vehicles')
                  ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                  ->whereNull('deleted_at')
                  ->whereNull('vin'); // Check for at least one VIN being null
        })
    ->count();
@endphp
@endif
        @if ($pendingvins > 0)
            {{ $pendingvins }}
        @else
            No records found
        @endif
    </td>
<tr onclick="window.location='{{ route('purchasing.filterconfirmation', ['status' => 'Approved']) }}';">
    <td style="font-size: 12px;">
        <a href="{{ route('purchasing.filterconfirmation', ['status' => 'Approved']) }}">
        Pending Incoming Confirmation
        </a>
    </td>
    <td style="font-size: 12px;">
    @php
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        @endphp
        @if ($hasPermission)
        @php
        $pendingvendorfol = DB::table('purchasing_order')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->where('payment_status', 'Vendor confirmed');
            });
    })
    ->count();
@endphp
@else
@php
$pendingvendorfol = DB::table('purchasing_order')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->where('payment_status', 'Vendor confirmed');
            });
    })
    ->count();
@endphp
@endif
        @if ($pendingvendorfol > 0)
            {{ $pendingvendorfol }}
        @else
            No records found
        @endif
    </td>
</tr>
</tr>
</tbody>
  </table>
</div>
</div>
</div>
    <div class="card-body">
    @if ($errors->has('source_name'))
            <div id="error-message" class="alert alert-danger">
                {{ $errors->first('source_name') }}
            </div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-po-details','demand-planning-po-list']);
                    @endphp
                    @if ($hasPermission)
<h4 class="card-title">
            Purchase Order List
        </h4>
        <div class="table-responsive" >
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                <thead class="bg-soft-secondary">
                <tr>
                <th style="vertical-align: middle; text-align: center;">PO Number</th>
                    <th style="vertical-align: middle; text-align: center;">PO Date</th>
                    <th style="vertical-align: middle; text-align: center;">Vendor Name</th>
                    <th style="vertical-align: middle; text-align: center;">Total Vehicles</th>
                    <th style="vertical-align: middle; text-align: center;">Total Cost</th>
                    <th class="nowrap-td" id="statuss" style="vertical-align: middle; text-align: center;">Vehicle Status</th>
                </tr>
                </thead>
                <tbody id="poTableBody">
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $purchasingOrder)
                <tr data-id="{{ $purchasingOrder->id }}" data-url="{{ route('purchasing-order.show', $purchasingOrder->id) }}" class="clickable-row">
                <td style="vertical-align: middle; text-align: center;">{{ $purchasingOrder->po_number }}</td>
                <td style="vertical-align: middle; text-align: center;">{{ date('d-M-Y', strtotime($purchasingOrder->po_date)) }}</td>
                <td style="vertical-align: middle; text-align: center;">
                            @php
                            $resultname = DB::table('suppliers')->where('id', $purchasingOrder->vendors_id)->value('supplier');
                            @endphp
                            {{ $resultname }}
                        </td>
                        <td style="vertical-align: middle; text-align: center;">
                        @php
                        $vehicleCount = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->wherenull('deleted_at')->count();
                        @endphp
                        {{ $vehicleCount }}
                    </td>
                    <td style="vertical-align: middle; text-align: center;">
                        @if(isset($purchasingOrder->totalcost) && $purchasingOrder->totalcost != 0)
                            {{ $purchasingOrder->currency }} {{ number_format($purchasingOrder->totalcost) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                    <table id="dtBasicExample20" class="table table-striped table-editable table-edits table table-bordered table-sm">
                    <thead>
                    <th style="font-size: 12px;">Status</th>
                    <th style="font-size: 12px;">Qty</th>
    </thead>
    <tbody>
      @php

    $vehiclescountnotapproved = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Approved')->count();
    $vehiclescountpaymentreq = DB::table('vehicles')
    ->where('purchasing_order_id', $purchasingOrder->id)
    ->where(function ($query) {
        $query->where('status', 'Request for Payment')
              ->whereNot('status', 'Rejected')
              ->orWhere(function ($query) {
                $query->whereNotIn('payment_status', ['Payment Initiate Request Rejected', 'Request Rejected', 'Payment Release Rejected', 'Incoming Stock'])
                      ->where(function ($query) {
                          $query->whereNotNull('payment_status')
                                ->where('payment_status', '<>', '');
                      });
            });
    })
    ->count();
    $vendorpaymentconfirm = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Incoming Stock')->count();
    @endphp
    <tr>
        <td style="font-size: 12px;">Remaining Approved Vehicle</td>
        <td style="font-size: 12px;">{{$vehiclescountnotapproved}}</td>
        </tr>
        <tr>
        <td style="font-size: 12px;">In Progress</td>
        <td style="font-size: 12px;">{{$vehiclescountpaymentreq}}</td>
        </tr>
        <tr>
        <td style="font-size: 12px;">Incoming Stock</td>
        <td colspan="3" style="font-size: 12px;">{{$vendorpaymentconfirm}}</td>
        </tr>
        </tbody>
  </table>
    </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
        <script>
$(document).ready(function() {
  $('.select2').select2();
  var dataTable = $('#dtBasicExample1').DataTable({
  pageLength: 10,
  order: [[1, 'desc']],
        columnDefs: [
            {
                targets: 1,
                type: 'date',
            }
        ],
  initComplete: function() {
    this.api().columns().every(function(d) {
      var column = this;
      var columnId = column.index();
      var columnName = $(column.header()).attr('id');
      if (columnName === "statuss") {
        return;
      }

      var selectWrapper = $('<div class="select-wrapper"></div>');
      var select = $('<select class="form-control my-1" multiple><option value="">All</option></select>')
        .appendTo(selectWrapper)
        .select2({
          width: '100%'
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

  // Apply custom CSS class after Select2 dropdown is opened
  $(document).on('select2:open', function(e) {
    $('.select2-dropdown').addClass('select2-orange-highlight');
  });
});
</script>
<script>
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
</script>
<script>
  function confirmCancel() {
    var confirmDialog = confirm("Are you sure you want to cancel this purchase order?");
    if (confirmDialog) {
      return true;
    } else {
      return false;
    }
  }
</script>
<script>
    function filterPOByStatus(status) {
        var tableRows = document.querySelectorAll('#poTableBody tr');
        tableRows.forEach(function(row) {
            var rowStatus = row.querySelector('td:last-child').textContent;
            if (rowStatus.trim() !== status.trim()) {
                row.style.display = 'none';
            } else {
                row.style.display = 'table-row';
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
    // Attach click event listener to all elements with the class 'clickable-row'
    document.querySelectorAll('.clickable-row').forEach(function(element) {
        element.addEventListener('click', function(e) {
            // Fetch the URL from the data-url attribute
            const url = this.getAttribute('data-url');

            // Check if Ctrl key is pressed or the right mouse button was used
            if (e.ctrlKey || e.button === 1) {
                // Open the link in a new tab
                window.open(url, '_blank');
            } else if (e.button === 0) { // Left click without Ctrl
                // Navigate in the same tab
                window.location.href = url;
            }
        });

        // Prevent the default context menu on right click
        element.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            // Fetch the URL again for consistency
            const url = this.getAttribute('data-url');
            // Open in a new tab on right click
            window.open(url, '_blank');
        });
    });
});
</script>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
