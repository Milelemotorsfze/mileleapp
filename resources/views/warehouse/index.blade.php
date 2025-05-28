@extends('layouts.table')
<style>
    .status-item {
        cursor: pointer;
        border-radius: 30px;
        padding: 1px;
        background-color: #007bff;
        color: white;
        font-size: 14px;
        text-align: center;
        transition: transform 0.2s, background-color 0.3s;
        margin: 0px 5px
    }

    .status-item:hover {
        transform: scale(1.01);
    }

    .purchase-order-summary-data {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 8px 0px;
        flex-wrap: wrap;
    }

    .purchase-order-summary-title,
    .purchase-order-pending-task-title {
        text-align: center;
        background-color: #4ba6ef54;
        padding: 5px 0px;
        color: black;
    }

    .approved-item {
        background-color: rgb(9, 145, 0);
    }

    .in-progress-item {
        background-color: rgba(9, 145, 0, 0.66);
    }

    .not-approved-item {
        background-color: rgba(9, 145, 0, 0.45);
    }


    .closed-item {
        background-color: rgba(255, 0, 0, 0.55);
    }

    .cancelled-item {
        background-color: rgb(225, 0, 0);
    }


    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1e90ff;
        color: white;
    }

    .table-responsive {
        overflow: auto;
        max-height: 650px;
    }

    .table-wrapper {
        position: relative;
    }

    thead th {
        top: 0;
        background-color: #4ba6ef54 !important;
        z-index: 1;
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

    @media screen and (max-width: 991px) {
        .status-item {
            padding: 5px;
            margin: 5px
        }

        .purchase-order-summary-data {
            justify-content: start;
        }
    }
    @media screen and (max-width: 767) {

        .purchase-order-summary-data {
            justify-content: center;
        }
    }
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-po-details','demand-planning-po-list']);
@endphp
@if ($hasPermission)
<div class="card-header">
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-available-funds');
    @endphp
    @if ($hasPermission)
    <a class="btn btn-sm btn-info" href="#" style="text-align: right; margin-right: 10px;">
        Total Outflows : {{ number_format($suggestedPaymentTotalAED, 0, '', ',') }} AED / {{ number_format($suggestedPaymentTotalUSD, 0, '', ',') }} USD
    </a>
    <a class="btn btn-sm btn-success" href="#" style="text-align: right;">
        Total Available Funds is {{ number_format($availableFunds, 0, '', ',') }} AED / {{ number_format($availableFundsUSD, 0, '', ',') }} USD
    </a>
    @endif
    <br>
    <br>
    <h4 class="card-title purchase-order-summary-title">
        Purchase Orders Summary
    </h4>
    <div class="row">
        <div class="purchase-order-summary-data w-100 text-center mx-0 pb-1 pt-1">

            <div class="col-8 col-md-6 col-lg-2">
                <div class="status-item approved-item" style="cursor: pointer;" onclick="window.location='{{ route('purchasing.filterapprovedonly', ['status' => 'Approved']) }}';">
                    <strong>Approved :
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
                        ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('supplier_account_transaction')
                        ->whereColumn('purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id');
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
                        ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('supplier_account_transaction')
                        ->whereColumn('purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id');
                        })
                        ->count();
                        @endphp
                        @endif
                        @if ($alreadyapproved > 0)
                        {{ $alreadyapproved }}
                        @else
                        0
                        @endif
                    </strong>
                </div>
            </div>

            <div class="col-8 col-md-6 col-lg-2">
                <div class="status-item in-progress-item" style="cursor: pointer;" onclick="window.location='{{ route('purchasing.filterapproved', ['status' => 'Approved']) }}';">
                    <strong>In Progress :
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
                        @endphp
                        @if ($hasPermission)
                        @php
                        $userId = auth()->user()->id;
                        $inProgressPurchasingOrders = DB::table('purchasing_order')
                        ->where(function ($query) {
                        $query->where('purchasing_order.status', 'Approved')
                        ->orWhereNot('purchasing_order.status', 'Cancelled');
                        })
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicles')
                        ->whereColumn('purchasing_order.id', 'vehicles.purchasing_order_id')
                        ->where('vehicles.status', 'Approved')
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicle_purchasing_cost')
                        ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                        ->where(function ($query) {
                        $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                        ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                        });
                        });
                        })
                        ->count();
                        @endphp
                        @else
                        @php
                        $inProgressPurchasingOrders = DB::table('purchasing_order')
                        ->where(function ($query) {
                        $query->where('purchasing_order.status', 'Approved')
                        ->orWhereNot('purchasing_order.status', 'Cancelled');
                        })
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicles')
                        ->whereColumn('purchasing_order.id', 'vehicles.purchasing_order_id')
                        ->where('vehicles.status', 'Approved')
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicle_purchasing_cost')
                        ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                        ->where(function ($query) {
                        $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                        ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                        });
                        });
                        })
                        ->count();
                        @endphp
                        @endif
                        @if ($inProgressPurchasingOrders > 0)
                        {{ $inProgressPurchasingOrders }}
                        @else
                        0
                        @endif
                    </strong>
                </div>
            </div>

            <div class="col-8 col-md-6 col-lg-2">
                <div class="status-item not-approved-item" style="cursor: pointer;" onclick="window.location='{{ route('purchasing.filter', ['status' => 'Pending Approval']) }}';">
                    <strong>Not Approved :
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
                    </strong>
                </div>
            </div>


            <div class="col-8 col-md-6 col-lg-2">
                <div class="status-item closed-item" style="cursor: pointer;" onclick="window.location='{{ route('purchasing.filterincomings', ['status' => 'Approved']) }}';">
                    <strong>Closed :
                        @php
                        $userId = auth()->user()->id;
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
                        @endphp
                        @if ($hasPermission)
                        @php
                        $completedPos = DB::table('purchasing_order')
                        ->where(function ($query) {
                        $query->where('purchasing_order.status', 'Approved')
                        ->orWhere('purchasing_order.status', 'Cancelled');
                        })
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicles')
                        ->whereColumn('purchasing_order.id', 'vehicles.purchasing_order_id')
                        ->where('vehicles.status', 'Approved')
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicle_purchasing_cost')
                        ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                        ->where(function ($query) {
                        $query->whereColumn('vehicle_purchasing_cost.unit_price', 'vehicle_purchasing_cost.total_paid_amount');
                        });
                        });
                        })
                        ->count();
                        @endphp
                        @else
                        @php
                        $completedPos = DB::table('purchasing_order')
                        ->where(function ($query) {
                        $query->where('purchasing_order.status', 'Approved')
                        ->orWhere('purchasing_order.status', 'Cancelled');
                        })
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicles')
                        ->whereColumn('purchasing_order.id', 'vehicles.purchasing_order_id')
                        ->where('vehicles.status', 'Approved')
                        ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                        ->from('vehicle_purchasing_cost')
                        ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                        ->where(function ($query) {
                        $query->whereColumn('vehicle_purchasing_cost.unit_price', 'vehicle_purchasing_cost.total_paid_amount');
                        });
                        });
                        })
                        ->count();
                        @endphp
                        @endif
                        @if ($completedPos > 0)
                        {{ $completedPos }}
                        @else
                        0
                        @endif
                    </strong>
                </div>
            </div>
            <div class="col-8 col-md-6 col-lg-2">
                <div class="status-item cancelled-item" style="cursor: pointer;" onclick="window.location='{{ route('purchasing.filtercancel', ['status' => 'Cancelled']) }}';">
                    <strong>Cancelled :
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
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <h4 class="card-title purchase-order-pending-task-title">
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
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Pre-Debit');
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $pendingpaymentrelsa = DB::table('purchasing_order')
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Pre-Debit');
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
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Request For Payment');
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $pendingreleasereqs = DB::table('purchasing_order')
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Request For Payment');
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
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Released');
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $pendingdebitaps = DB::table('purchasing_order')
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Released');
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
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Initiate Payment Request');
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $pendingints = DB::table('purchasing_order')
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Initiate Payment Request');
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
                                Rejected
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
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Rejected');
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $paymentreleasedrejection = DB::table('purchasing_order')
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Rejected');
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
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('vehicles')
                            ->whereColumn('vehicles.purchasing_order_id', 'purchasing_order.id')
                            ->where('vehicles.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('vehicle_purchasing_cost')
                            ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                            ->where(function ($query) {
                            $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                            ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                            });
                            });
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $pendingvendorfol = DB::table('purchasing_order')
                            ->where('purchasing_order.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('vehicles')
                            ->whereColumn('vehicles.purchasing_order_id', 'purchasing_order.id')
                            ->where('vehicles.status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('vehicle_purchasing_cost')
                            ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                            ->where(function ($query) {
                            $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                            ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                            });
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
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Debit')
                            ->whereNull('supplier_account_transaction.vendor_payment_status');
                            })
                            ->count();
                            @endphp
                            @else
                            @php
                            $pendingvendorfol = DB::table('purchasing_order')
                            ->where('status', 'Approved')
                            ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                            ->from('supplier_account_transaction')
                            ->whereColumn('purchasing_order.id', 'supplier_account_transaction.purchasing_order_id')
                            ->where('supplier_account_transaction.transaction_type', 'Debit')
                            ->whereNull('supplier_account_transaction.vendor_payment_status');
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
                        <!-- <tr onclick="window.location='{{ route('purchasing.filterconfirmation', ['status' => 'Approved']) }}';">
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
</tr> -->
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
    <div class="table-responsive">
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
                            {{ $purchasingOrder->currency }} {{ number_format($purchasingOrder->totalcost, 2) }}
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

                                $vehiclescountnotapproved = DB::table('vehicles')->where('purchasing_order_id', $purchasingOrder->id)->where('status', 'Not Approved')->count();
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
            // Conversion rates from other currencies to AED
            var conversionRates = {
                'AED': 1,
                'USD': 3.67,
                'EUR': 4.1,
                'JPY': 0.034,
                'CAD': 2.85,
                'PHP' : 0.063,
                'SAR' : 0.98,
                // Add other currencies and their conversion rates here
            };

            // Custom sorting function for currency
            $.fn.dataTable.ext.type.order['currency-pre'] = function(data) {
                if (data === 'N/A') {
                    return 0;
                }

                var matches = data.match(/([\D]+)?\s?([\d,\.]+)/);
                if (matches && matches[2]) {
                    var currency = matches[1] ? matches[1].trim() : 'AED'; // Default to 'AED' if currency is missing
                    var number = parseFloat(matches[2].replace(/,/g, ''));
                    var conversionRate = conversionRates[currency] || 1; // Default to 1 if currency not found
                    return number * conversionRate;
                }
                return 0;
            };

            $('.select2').select2();
            var dataTable = $('#dtBasicExample1').DataTable({
                pageLength: 10,
                order: [
                    [1, 'desc']
                ], // Date column descending
                columnDefs: [{
                        targets: 1,
                        type: 'date',
                        orderSequence: ['desc', 'asc'] // Date column only descending
                    },
                    {
                        targets: 4, // Ensure the correct column index for the total cost
                        type: 'currency',
                        orderSequence: ['asc', 'desc'] // Total cost ascending
                    }
                    // You can specify other columns if needed
                ],
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
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

                        column.data().unique().sort(function(a, b) {
                            var aValue = $.fn.dataTable.ext.type.order['currency-pre'](a);
                            var bValue = $.fn.dataTable.ext.type.order['currency-pre'](b);
                            return aValue - bValue;
                        }).each(function(d) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    });
                }
            });

            $('.dataTables_filter input').on('keyup', function() {
                dataTable.search(this.value).draw();
            });

            // Apply custom CSS class after Select2 dropdown is opened
            $(document).on('select2:open', function() {
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