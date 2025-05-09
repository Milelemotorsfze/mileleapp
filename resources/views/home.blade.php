@extends('layouts.table')
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
<style>
   .card {
   border-radius: 8px;
   border: 1px solid #ddd;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
   }
   .card-header {
   background-color: #f8f9fa;
   border-bottom: 1px solid #ddd;
   padding: 10px 15px;
   }
   .card-title {
   font-size: 1rem;
   font-weight: 600;
   }
   .card-body {
   padding: 15px;
   }
   #reasonBarChart {
   max-width: 100%;
   height: 400px;
   }
   .details-control {
   cursor: pointer;
   color: blue;
   }
   .details-control:hover {
   text-decoration: underline;
   }
   .my-text {
   font-weight: bold;
   font-size: 20px;
   background: linear-gradient(to right, #4a90e2, #2170eb);
   padding: 10px 15px;
   border-radius: 10px;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
   color: #fff;
   text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
   }
   /* Style for the "Read More" link as a button */
   .read-more-link {
   display: inline-block;
   padding: 10px 20px;
   background-color: white;
   color: black;
   border: 1px solid black;
   text-decoration: none;
   border-radius: 4px;
   margin-top: 10px;
   float: right;
   }
   .total-row {
   font-weight: bold;
   color: black;
   }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('dp-dashboard');
@endphp
@if ($hasPermission)
<div class="card">
   <div class="card-body px-3">
      <div class="text-right mb-3">
         <a href="{{ url('/export-uae-vehicle-stock') }}" class="btn btn-success">
         Export to Excel
         </a>
      </div>
      <div class="table-responsive px-3">
         <div class="card-header align-items-center">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">UAE Vehicle Stock</h4>
         </div>
         <table id="vehicleStockTable" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th></th>
                  <!-- For the expandable control -->
                  <th>Model</th>
                  <th>SFX</th>
                  <th>Variant Name</th>
                  <th>Free Stock</th>
                  <th>Total Quantity</th>
               </tr>
            </thead>
            <tbody>
               @foreach($dpdashboarduae as $variant)
               <tr>
                  <td>
                     <a href="#" class="expand-row" data-variant-id="{{ $variant->varaints_id }}">+</a>
                  </td>
                  @php
                  $modelsfx = DB::table('master_models')->where('variant_id', $variant->varaints_id)->first();
                  @endphp
                  <td>{{ $modelsfx->model ?? '' }}</td>
                  <td>{{ $modelsfx->sfx ?? '' }}</td>
                  <td>{{ $variant->variant_name }}</td>
                  <td>
                     @php
                     $vehicleCount = DB::table('vehicles')
                     ->where('varaints_id', $variant->varaints_id)
                     ->whereNull('so_id')
                     ->whereNull('gdn_id')
                     ->whereNotNull('vin')
                     ->whereNotIn('latest_location', ['102', '153', '147'])
                     ->where('vehicles.status', 'Approved')
                     ->where(function ($query) {
                     $query->whereNull('vehicles.reservation_end_date')
                     ->orWhere('vehicles.reservation_end_date', '<', now());
                     })
                     ->count();
                     @endphp
                     {{ $vehicleCount }}
                  </td>
                  <td>
                     @php
                     $vehicleCountfull = DB::table('vehicles')
                     ->where('varaints_id', $variant->varaints_id)
                     ->where('vehicles.status', 'Approved')
                     ->whereNotNull('vin')
                     ->whereNull('gdn_id')
                     ->count();
                     @endphp
                     {{ $vehicleCountfull }}
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
<div class="card">
   <div class="card-body px-0">
      <div class="table-responsive px-3">
         <div class="text-right mb-3">
            <a href="{{ url('/export-belgium-vehicle-stock') }}" class="btn btn-success">
            Export to Excel
            </a>
         </div>
         <div class="card-header align-items-center">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Belgium Vehicle Stock</h4>
         </div>
         <table id="vehicleStockTablebelgium" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th></th>
                  <!-- For the expandable control -->
                  <th>Model</th>
                  <th>SFX</th>
                  <th>Variant Name</th>
                  <th>Free Stock</th>
                  <th>Total Quality</th>
               </tr>
            </thead>
            <tbody>
               @foreach($dpdashboardnon as $variant)
               <tr>
                  <td>
                     <a href="#" class="expand-row-belgium" data-variant-id="{{ $variant->varaints_id }}">+</a>
                  </td>
                  @php
                  $modelsfx = DB::table('master_models')->where('variant_id', $variant->varaints_id)->first();
                  @endphp
                  <td>{{ $modelsfx->model ?? '' }}</td>
                  <td>{{ $modelsfx->sfx ?? '' }}</td>
                  <td>{{ $variant->variant_name }}</td>
                  <td>
                     @php
                     $vehicleCount = DB::table('vehicles')
                     ->where('varaints_id', $variant->varaints_id)
                     ->whereNull('so_id')
                     ->whereNotNull('vin')
                     ->whereNotIn('latest_location', ['102', '153', '147'])
                     ->where('vehicles.status', 'Approved')
                     ->whereNull('gdn_id')
                     ->where(function ($query) {
                     $query->whereNull('vehicles.reservation_end_date')
                     ->orWhere('vehicles.reservation_end_date', '<', now());
                     })
                     ->count();
                     @endphp
                     {{ $vehicleCount }}
                  </td>
                  <td>
                     @php
                     $vehicleCountfull = DB::table('vehicles')
                     ->where('varaints_id', $variant->varaints_id)
                     ->where('vehicles.status', 'Approved')
                     ->whereNotNull('vin')
                     ->whereNull('gdn_id')
                     ->count();
                     @endphp
                     {{ $vehicleCountfull }}
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('finance-dashboard-summary');
@endphp
@if ($hasPermission)
<div class="card">
   <div class="card-body px-0">
      <div class="table-responsive px-3">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Purchase Orders Summary</h4>
         </div>
         <table id="dtBasicExample2" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th>Department</th>
                  <th>Pending Approvals</th>
                  <th>Approved</th>
                  <th>Request for Payment Initination</th>
                  <th>Request for Payment Release</th>
                  <th>Payment Completed</th>
                  <th>Closed PO</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>
                     Demand & Planning
                  </td>
                  <td onclick="window.location='{{ route('purchasing.filter', ['status' => 'Pending Approval']) }}';">
                     @php
                     $pendongpoapproval = DB::table('purchasing_order')->where('status', 'Pending Approval')->count();
                     @endphp
                     {{$pendongpoapproval}}
                  </td>
                  <td onclick="window.location='{{ route('purchasing.filterapprovedonly', ['status' => 'Approved']) }}';">
                     @php
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
                     {{$alreadyapproved}}
                  </td>
                  <td onclick="window.location='{{ route('purchasing.filterapproved', ['status' => 'Approved']) }}';">
                     @php
                     $inProgressPurchasingOrders = DB::table('vehicles')
                     ->whereExists(function ($query) {
                     $query->select(DB::raw(1))
                     ->from('purchasing_order')
                     ->whereColumn('vehicles.purchasing_order_id', '=', 'purchasing_order.id');
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
                     {{$inProgressPurchasingOrders}}
                  </td>
                  <td>
                     @php
                     $requestpaymentinitination = DB::table('vehicles')
                     ->whereExists(function ($query) {
                     $query->select(DB::raw(1))
                     ->from('purchasing_order')
                     ->whereColumn('vehicles.purchasing_order_id', '=', 'purchasing_order.id');
                     })
                     ->where(function ($query) {
                     $query->where('status', 'Payment Requested')
                     ->orWhere(function ($query) {
                     $query->where('payment_status', ['Payment Initiate Request Approved'])
                     ->where(function ($query) {
                     $query->whereNotNull('payment_status')
                     ->where('payment_status', '<>', '');
                     });
                     });
                     })
                     ->distinct('vehicles.purchasing_order_id')
                     ->count('vehicles.purchasing_order_id');
                     @endphp
                     {{$requestpaymentinitination}}
                  </td>
                  <td>
                     Demand & Planning
                  </td>
                  <td onclick="window.location='{{ route('purchasing.filterincomings', ['status' => 'Approved']) }}';">
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
                     {{$completedPos}}
                  </td>
               </tr>
               <tr>
                  <td>
                     Procurement
                  </td>
                  <td>
                     Demand & Planning
                  </td>
                  <td>
                     Demand & Planning
                  </td>
                  <td>
                     Demand & Planning
                  </td>
                  <td>
                     Demand & Planning
                  </td>
                  <td>
                     Demand & Planning
                  </td>
                  <td>
                     Demand & Planning
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <!-- end card body -->
</div>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('sale-team-dashboard');
@endphp
@if ($hasPermission)
@if ($undersalesleads->isNotEmpty())
<div class="card">
   <div class="card-body px-0">
      <div class="table-responsive px-3">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Showroom Sales Person Leads Summary</h4>
         </div>
         <table id="dtBasicExample2" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th>Sales Person</th>
                  <th>Bulk Leads</th>
                  <th>Pending Leads</th>
                  <th>Response Time</th>
                  <th>Prospectings</th>
                  <th>Fellow Up</th>
                  <th>Quotation Issued</th>
                  <th>Rejected</th>
                  <th>Sales Order</th>
               </tr>
            </thead>
            <tbody>
               @forelse ($undersalesleads as $undersaleslead)
               <tr>
                  <td>{{ $undersaleslead->salespersonname }}</td>
                  @php
                  $bulkleads = DB::table('calls')
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->whereIn('calls.leadtype', ['Bulk Deals', 'Special Orders'])
                  ->count();
                  @endphp
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Bulk Deals']) }}">{{ $bulkleads }}</a></td>
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Pending Leads']) }}">{{ $undersaleslead->lead_count }}</a></td>
                  @php
                  $responsetime = null;
                  $responsetime = DB::table('calls')
                  ->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id')
                  ->where('calls.status', '!=', 'New')
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, calls.created_at, prospectings.created_at)) as average_response_time'))
                  ->first();
                  @endphp
                  <td>{{ $responsetime ? number_format($responsetime->average_response_time, 0) . ' Hrs' : 'N/A' }}</td>
                  @php
                  $openLeadsCount = DB::table('calls')
                  ->where(function ($query) use ($undersaleslead) {
                  $query->Where('calls.status', '=', 'Prospecting');
                  })
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Prospecting']) }}">{{ $openLeadsCount }}</a></td>
                  @php
                  $openLeadsCountfellow = DB::table('calls')
                  ->where(function ($query) use ($undersaleslead) {
                  $query->where('calls.status', '=', 'Follow Up');
                  })
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Follow Up']) }}">{{ $openLeadsCountfellow }}</a></td>
                  @php
                  $closedLeadsCount = DB::table('calls')
                  ->where(function ($query) use ($undersaleslead) {
                  $query->where('calls.status', '=', 'Closed');
                  })
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  @php
                  $closedLeadsCountrejected = DB::table('calls')
                  ->where(function ($query) use ($undersaleslead) {
                  $query->Where('calls.status', '=', 'Rejected');
                  })
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  @php
                  $closedLeadsCountquoted = DB::table('calls')
                  ->where(function ($query) use ($undersaleslead) {
                  $query->Where('calls.status', '=', 'Quoted');
                  })
                  ->where('calls.sales_person', '=', $undersaleslead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Quoted']) }}">{{ $closedLeadsCountquoted }}</a></td>
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Rejected']) }}">{{ $closedLeadsCountrejected }}</a></td>
                  <td><a href="{{ route('sales.summary', ['sales_person_id' => $undersaleslead->sales_person, 'count_type' => 'Sales Order']) }}">{{ $closedLeadsCount }}</a></td>
               </tr>
               @empty
               <tr>
                  <td colspan="5" class="text-center">No pending leads found.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
   <!-- end card body -->
</div>
@endif
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('gp-dashboard');
$currentMonth = request()->get('month') ?? now()->format('Y-m');
@endphp
@if ($hasPermission)
@if (!empty($undersalesleads))
<div class="card">
   <div class="card-body px-0">
      <div class="table-responsive px-3">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Sold Vehicles GP & Commission Summary</h4>
            <form id="filterForm" action="{{ route('home') }}" method="GET">
               <div class="row">
                  <div class="col-md-4">
                     <select class="form-control" name="month" id="monthSelector" onchange="this.form.submit()">
                     @for ($i = 0; $i < 12; $i++)
                     @php
                     $date = now()->startOfMonth()->subMonths($i)->format('Y-m');
                     $selected = ($currentMonth === $date) ? 'selected' : '';
                     @endphp
                     <option value="{{ $date }}" {{ $selected }}>
                     {{ now()->startOfMonth()->subMonths($i)->format('F Y') }}
                     </option>
                     @endfor
                     </select>
                  </div>
               </div>
            </form>
         </div>
         <table id="dtBasicExample5" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th>Sales Person</th>
                  <th>Number of Invoices</th>
                  <th>Number of Vehicles</th>
                  <th>Total Cost Price</th>
                  <th>Total Sale Price</th>
                  <th>Gross Profit Margin</th>
                  <th>Commission Rate</th>
                  <th>Total Commission</th>
               </tr>
            </thead>
            <tbody>
               @foreach ($commissons as $data)
               <tr>
                  <td><a href="{{ route('salesperson.commissions', ['sales_person_id' => $data->sales_person_id, 'month' => request()->get('month') ?? now()->format('Y-m')]) }}">
                     {{ $data->name }}
                     </a>
                  </td>
                  <td>{{ $data->total_invoices }}</td>
                  <td>{{ $data->total_invoices_items }}</td>
                  <td>{{ number_format($data->total_vehicle_cost, 2) }}</td>
                  <td>{{ number_format($data->total_rate_in_aed, 2) }}</td>
                  <td>{{ number_format($data->total_rate_in_aed - $data->total_vehicle_cost, 2) }}</td>
                  <td>{{ number_format($data->commission_rate, 2) }}%</td>
                  <td>{{ number_format(($data->total_rate_in_aed - $data->total_vehicle_cost) * ($data->commission_rate / 100), 2) }}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
   <!-- end card body -->
</div>
@endif
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-log-activity');
@endphp
@if ($hasPermission)
<div class="card">
   <div class="card-body px-0">
      <div class="table-responsive px-3">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Sales Persons Pending Leads</h4>
         </div>
         <table id="dtBasicExample2" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th>Sales Person</th>
                  <th>Pending Leads</th>
                  <th>Response Time</th>
                  <th>Open Leads</th>
                  <th>Lead Closed</th>
                  <th>Lead Shifted (Last 7 Days)</th>
               </tr>
            </thead>
            <tbody>
               @forelse ($leadsCount as $lead)
               <tr>
                  <td>{{ $lead->salespersonname }}</td>
                  <td>{{ $lead->lead_count }}</td>
                  @php
                  $responsetime = null;
                  $responsetime = DB::table('calls')
                  ->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id')
                  ->where('calls.status', '!=', 'New')
                  ->where('calls.sales_person', '=', $lead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, calls.created_at, prospectings.created_at)) as average_response_time'))
                  ->first();
                  @endphp
                  <td>{{ $responsetime ? number_format($responsetime->average_response_time, 0) . ' Hrs' : 'N/A' }}</td>
                  @php
                  $openLeadsCount = DB::table('calls')
                  ->where(function ($query) use ($lead) {
                  $query->where('calls.status', '!=', 'Closed')
                  ->orWhere('calls.status', '!=', 'Rejected');
                  })
                  ->where('calls.sales_person', '=', $lead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  <td>{{ $openLeadsCount }}</td>
                  @php
                  $closedLeadsCount = DB::table('calls')
                  ->where(function ($query) use ($lead) {
                  $query->where('calls.status', '=', 'Closed')
                  ->orWhere('calls.status', '=', 'Rejected');
                  })
                  ->where('calls.sales_person', '=', $lead->sales_person)
                  ->whereDate('calls.created_at', '>=', '2023-10-01')
                  ->count();
                  @endphp
                  <td>{{ $closedLeadsCount }}</td>
               </tr>
               @empty
               <tr>
                  <td colspan="2" class="text-center">No pending leads found.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
   <!-- end card body -->
</div>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-log-activity');
@endphp
@if ($hasPermission)
<div class="card">
   <div class="card-body px-0">
      <div class="table-responsive px-3">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Sales Person KPI</h4>
         </div>
         <table id="dtBasicExample3" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
               <tr>
                  <th>Sales Person</th>
                  <th>Walking / Direct Leads</th>
                  <th>Marketing Target Ideas</th>
                  <th>Export Sale</th>
                  <th>Local Sale</th>
                  <th>Lease to Own</th>
                  <th>Google Review</th>
                  <th>Service kits</th>
                  <th>Shipping</th>
                  <th>Accessiors</th>
                  <th>Unique Customers</th>
               </tr>
            </thead>
            <tbody>
               @forelse ($sales_personsname as $sales_personsname)
               <tr>
                  <td>{{ $sales_personsname->name }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
                  @php
                  $marketingtarget = '';
                  $marketingtarget = DB::table('strategies')
                  ->where('strategies.target_sales_person', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $marketingtarget }}</td>
                  @php
                  $exportsales = '';
                  $exportsales = DB::table('so')
                  ->where('so.sales_person_id', '=', $sales_personsname->id)
                  ->where('so.sales_type', '=', "Export")
                  ->count();
                  @endphp
                  <td>{{ $exportsales }}</td>
                  @php
                  $localsale = '';
                  $localsale = DB::table('so')
                  ->where('so.sales_person_id', '=', $sales_personsname->id)
                  ->where('so.sales_type', '=', "Local")
                  ->count();
                  @endphp
                  <td>{{ $localsale }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
                  @php
                  $callCount = '';
                  $callCount = DB::table('calls')
                  ->where('calls.created_by', '=', $sales_personsname->id)
                  ->count();
                  @endphp
                  <td>{{ $callCount }}</td>
               </tr>
               @empty
               <tr>
                  <td colspan="2" class="text-center">No pending leads found.</td>
               </tr>
               @endforelse
            </tbody>
         </table>
      </div>
   </div>
   <!-- end card body -->
</div>
@endif
{{--   @can('parts-procurement-dashboard-view')--}}
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('parts-procurement-dashboard-view');
@endphp
@if ($hasPermission)
<div class="row p-3">
   <div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
      <div class="card " style="min-height: 550px;">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Addon Selling Prices</h4>
            <select id="addon_type" name="addon_type"  class="form-control float-end p-2" style="width: 20%"  >
               <option value="P" >Accessories</option>
               <option value="SP" >Spare Parts</option>
               <option value="K">Kit</option>
            </select>
            <div class="flex-shrink-0">
               <ul class="nav nav-tabs-custom rounded card-header-tabs" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" data-bs-toggle="tab" href="#selling-price-not-added" id="with-out-selling-price-tab" role="tab">
                     Without Selling Price
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-bs-toggle="tab" href="#pending-selling-price" id="pending-price-tab" role="tab">
                     Pending
                     </a>
                  </li>
               </ul>
               <!-- end nav tabs -->
            </div>
         </div>
         <!-- end card header -->
         <div class="tab-content"  >
            <div class="tab-pane fade show active" id="selling-price-not-added">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="addon-without-selling-prices"  class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Addon Code</th>
                              <th>Addon Name</th>
                           </tr>
                        </thead>
                        <tbody id="table-without-selling-price-body">
                           @foreach($withOutSellingPrices as $row)
                           <tr>
                              <td>{{$row->addon_code }}</td>
                              <td>{{$row->AddonName->name ?? ''}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="tab-pane fade " id="pending-selling-price">
               <div class="card-body">
                  <div class="table-responsive ">
                     <table id="addon-pending-selling-prices" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Addon Code</th>
                              <th>Addon Name</th>
                              <th>Selling Price</th>
                              <th>Requested By</th>
                           </tr>
                        </thead>
                        <tbody id="table-pending-selling-price-body">
                           @foreach($pendingSellingPrices as $row)
                           <tr>
                              <td>{{$row->addonDetail->addon_code ?? ''}}</td>
                              <td>{{$row->addonDetail->AddonName->name ?? ''}}</td>
                              <td>{{$row->selling_price }}</td>
                              <td>{{$row->CreatedBy->name ?? ''}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
      <div class="card " style="min-height: 550px;">
         <div class="card-header align-items-center ">
            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Recently Added Addons</h4>
            <div class="flex-shrink-0">
               <ul class="nav nav-tabs-custom rounded card-header-tabs" role="tablist">
                  <li class="nav-item">
                     <a class="nav-link active" data-bs-toggle="tab" href="#latest-accessories"  role="tab">
                     Accessories
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link " data-bs-toggle="tab" href="#latest-spare-parts"  role="tab">
                     Spare Parts
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" data-bs-toggle="tab" href="#latest-kits"  role="tab">
                     Kits
                     </a>
                  </li>
               </ul>
               <!-- end nav tabs -->
            </div>
         </div>
         <!-- end card header -->
         <div class="tab-content" >
            <div class="tab-pane fade show active" id="latest-accessories">
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="table-latest-accessories" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Addon Code</th>
                              <th>Addon Name</th>
                           </tr>
                        </thead>
                        <tbody >
                           @foreach($recentlyAddedAccessories as $row)
                           <tr>
                              <td>{{$row->addon_code }}</td>
                              <td>{{$row->AddonName->name ?? ''}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="tab-pane fade " id="latest-spare-parts">
               <div class="card-body">
                  <div class="table-responsive ">
                     <table id="table-latest-spare-parts" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Addon Code</th>
                              <th>Addon Name</th>
                           </tr>
                        </thead>
                        <tbody >
                           @foreach($recentlyAddedSpareParts as $row)
                           <tr>
                              <td>{{$row->addon_code ?? ''}}</td>
                              <td>{{$row->AddonName->name ?? ''}}</td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <div class="tab-pane fade " id="latest-kits">
               <div class="card-body">
                  <div class="table-responsive ">
                     <table id="table-latest-kits" class="table table-striped table-bordered">
                        <thead>
                           <tr>
                              <th>Addon Code</th>
                              <th>Addon Name</th>
                           </tr>
                        </thead>
                        <tbody>
                           @if($recentlyAddedKits->count() > 0)
                           @foreach($recentlyAddedKits as $row)
                           <tr>
                              <td>{{$row->addon_code ?? ''}}</td>
                              <td>{{$row->AddonName->name ?? ''}}</td>
                           </tr>
                           @endforeach
                           @else
                           <tr>
                              <td colspan="4">No Data Available</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif
{{--    @endcan--}}
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
@endphp
@if ($hasPermission)
<div class="row">
   <div class="col-xl-12 col-md-12">
      <!-- card -->
      <div class="card card-h-50">
         <!-- card body -->
         <div class="card-body">
            <div class="row align-items-center">
               <div style="text-align: center;">
                  <h3>Daily Calls & Messages Leads</h3>
               </div>
               <div style="position: relative; width: 100%; height: 5vh;">
                  <div id="reportrange" style="position: absolute; top: 10px; right: 10px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 280px; text-align: right;">
                     <i class="fa fa-calendar"></i>&nbsp;
                     <span></span> <i class="fa fa-caret-down"></i>
                  </div>
               </div>
               <form id="date-range-form" method="POST">
                  @csrf
                  <input type="hidden" name="start_date" id="start_date">
                  <input type="hidden" name="end_date" id="end_date">
               </form>
               <div id="chartContainer" style="width: 100%; height: 350px;">
                  <canvas id="barChart"></canvas>
               </div>
            </div>
         </div>
         <!-- end card body -->
      </div>
      <!-- end card -->
   </div>
   <!-- end col -->
   <!-- @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
      @endphp
      @if ($hasPermission)
          <div class="col-xl-5 col-md-6">
              <div class="card card-h-100">
                  <div class="card-body">
                      <div class="row align-items-center">
                          <div class="col-6">
                          <span class="my-text">Variants</span><br><br>
                              <h5 class="mb-3">
                              Last 30 Days :  <span class="counter-value" data-target="{{ $totalvariantcount }}">0</span><br><br>
                              Last 7 Days  :  <span class="counter-value" data-target="{{ $totalvariantcount7days }}">0</span><br><br>
                              Today      :  <span class="counter-value" data-target="{{ $totalvariantcounttoday }}">0 </span>
                              </h4>
                          </div>
                          <div class="col-6">
                          <canvas id="totalvariantss"></canvas>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      @endif
      @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
      @endphp
      @if ($hasPermission)
          <div class="col-xl-2 col-md-6">
              <div class="card card-h-50">
                  <div class="card-body">
                      <div class="row align-items-center">
                          <div class="col-12">
                              <span class="text-muted mb-3 lh-1 d-block text-truncate">Variants Without Pictures</span>
                              <h4 class="mb-3">
                                  <span class="counter-value" data-target="{{ $countpendingpictures }}">0</span>
                              </h4>
                          </div>
                      </div>
                      <div class="text-nowrap">
                          <span class="badge bg-soft-success text-success">+ {{ $countpendingpicturesdays }}</span>
                          <span class="ms-1 text-muted font-size-13">Since last week</span>
                      </div>
                      <hr>
                      <div class="row align-items-center">
                          <div class="col-12">
                              <span class="text-muted mb-3 lh-1 d-block text-truncate">Variants Without Videos</span>
                              <h4 class="mb-3">
                              <span class="counter-value" data-target="{{ $countpendingreels }}">0</span>
                              </h4>
                      </div>
                      <div class="text-nowrap">
                          <span class="badge bg-soft-success text-success">+ {{ $countpendingreelsdays }}</span>
                          <span class="ms-1 text-muted font-size-13">Since last week</span>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      @endif -->
   @endif
   @php
   $hasPermission = Auth::user()->hasPermissionForSelectedRole('approve-reservation');
   @endphp
   @if ($hasPermission)
   <div class="row">
      <div class="col-xl-12">
         <div class="card">
            <div class="col-lg-6">
               <a class="btn btn-sm btn-success" href="{{ route('dailyleads.create') }}" text-align: right>
               <i class="fa fa-plus" aria-hidden="true"></i> Add New Lead
               </a>
            </div>
            <div class="card-header align-items-center d-flex">
               <h4 class="card-title mb-0 flex-grow-1">Lead Distribution</h4>
               <div class="flex-shrink-0">
                  <div style="position: relative; width: 100%; height: 5vh;">
                     <div id="leadsdis" style="position: absolute; top: 10px; right: 10px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 280px; text-align: right;">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                     </div>
                  </div>
                  <form id="date-range-form" method="POST">
                     @csrf
                     <input type="hidden" name="start_date" id="start_date">
                     <input type="hidden" name="end_date" id="end_date">
                  </form>
               </div>
            </div>
            <!-- end card header -->
            <div class="card-body px-0">
               <div class="table-responsive px-3">
                  <table id="dtBasicExample1" class="table table-striped table-bordered">
                     <thead class="bg-soft-secondary">
                        <th id="dateColumn">
                           Date
                        </th>
                        <th id="sales_person">
                           Sales Person
                        </th>
                        <th>
                           Marketing Leads
                        </th>
                        <th>
                           Additional Leads
                        </th>
                        <th>
                           Emails
                        </th>
                        <th>
                           Calls
                        </th>
                        <th>
                           Walking Customer
                        </th>
                        <th>
                           Direct Reference
                        </th>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
                  <div id="totalRowContainer"></div>
               </div>
            </div>
         </div>
         <!-- end tab content -->
      </div>
      <!-- end card body -->
   </div>
</div>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('leads-summary-dashboard');
@endphp
@if ($hasPermission)
<div class="row">
   <!-- Full-width Chart Section -->
   <div class="col-12">
      <div class="card shadow-sm" style="margin: 20px; padding: 20px;">
         <div class="card-header align-items-center d-flex justify-content-between">
            <h5 class="card-title mb-0 flex-grow-1">Leads Rejection Reasons Summary</h5>
         </div>
         <div class="card-body text-center">
            <!-- Date Range Picker for Reason Chart -->
            <div id="reasonReportrange" style="cursor: pointer; padding: 5px; border: 1px solid #ccc; width: 250px; text-align: right;">
               <i class="fa fa-calendar"></i>&nbsp;
               <span></span> <i class="fa fa-caret-down"></i>
            </div>
            <input type="hidden" id="reason_start_date">
            <input type="hidden" id="reason_end_date">
            <!-- Bar Chart Canvas -->
            <canvas id="reasonBarChart"></canvas>
         </div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-xl-12">
      <div class="card">
         <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Leads Stage Summary Report</h4>
            <div class="flex-shrink-0">
               <div style="position: relative; width: 100%; height: 5vh;">
                  <div id="leadsstatuswise" style="position: absolute; top: 10px; right: 10px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 280px; text-align: right;">
                     <i class="fa fa-calendar"></i>&nbsp;
                     <span></span> <i class="fa fa-caret-down"></i>
                  </div>
               </div>
               <form id="date-range-form" method="POST">
                  @csrf
                  <input type="hidden" name="start_date" id="start_date">
                  <input type="hidden" name="end_date" id="end_date">
               </form>
            </div>
         </div>
         <!-- end card header -->
         <div class="card-body px-0">
            <div class="table-responsive px-3">
               <table id="dtBasicExample4" class="table table-striped table-bordered">
                  <thead class="bg-soft-secondary">
                     <th id="dateColumn">
                        Date
                     </th>
                     <th>
                        Sales Person
                     </th>
                     <th>
                        New / Pending
                     </th>
                     <th>
                        Contacted
                     </th>
                     <th>
                        Working
                     </th>
                     <th>
                        Qualify
                     </th>
                     <th>
                        Disqualify
                     </th>
                     <th>
                        Converted
                     </th>
                     <th>
                        Quotation
                     </th>
                     <th>
                        Pre-order
                     </th>
                     <th>
                        Sales Order
                     </th>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
               <div id="totalRowContainer"></div>
            </div>
         </div>
      </div>
      <!-- end tab content -->
   </div>
   <!-- end card body -->
</div>
</div>
@endif
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
@endphp
@if ($hasPermission)
<div class="row">
   <div class="col-xl-5">
      <div class="card card-h-100">
         <!-- card body -->
         <div class="card-body">
            <div class="row align-items-center">
               <div class="col-6">
                  <span class="my-text">Leads</span><br><br>
                  <h5 class="mb-3">
                  Last 30 Days :  <span class="counter-value" data-target="{{ $totalleadscount }}">0</span><br><br>
                  Last 7 Days  :  <span class="counter-value" data-target="{{ $totalleadscount7days }}">0</span><br><br>
                  Today      :  <span class="counter-value" data-target="{{ $totalleadscounttoday }}">0</span>
                  </h4>
               </div>
            </div>
            <div class="row align-items-center">
               <div class="col-12">
                  <canvas id="totalleads"></canvas>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-xl-7">
      <div class="card">
         <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Lead Distribution</h4>
            <div class="flex-shrink-0">
               <div style="position: relative; width: 100%; height: 5vh;">
                  <div id="leadsdis" style="position: absolute; top: 10px; right: 10px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 280px; text-align: right;">
                     <i class="fa fa-calendar"></i>&nbsp;
                     <span></span> <i class="fa fa-caret-down"></i>
                  </div>
               </div>
               <form id="date-range-form" method="POST">
                  @csrf
                  <input type="hidden" name="start_date" id="start_date">
                  <input type="hidden" name="end_date" id="end_date">
               </form>
            </div>
         </div>
         <!-- end card header -->
         <div class="card-body px-0">
            <div class="table-responsive px-3">
               <table id="dtBasicExample1" class="table table-striped table-bordered">
                  <thead>
                     <th>
                        Date
                     </th>
                     <th>
                        Sales Person
                     </th>
                     <th>
                        Leads
                     </th>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
               <div id="readMoreLinkContainer">
                  <!-- "Read More" link will be added here -->
               </div>
            </div>
         </div>
      </div>
      <!-- end tab content -->
   </div>
   <!-- end card body -->
</div>
<div class="row">
<div class="col-xl-12">
   <div class="card">
      <div class="card-header align-items-center d-flex">
         <h4 class="card-title mb-0 flex-grow-1">Most Inquiry Model Line</h4>
         <div class="flex-shrink-0">
            <ul class="nav justify-content-end nav-tabs-custom rounded card-header-tabs" role="tablist">
               <li class="nav-item">
                  <a class="nav-link active" data-bs-toggle="tab" href="#transactions-all-tab" role="tab">
                  Last 30 Days
                  </a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#transactions-buy-tab" role="tab">
                  Last 7 Days
                  </a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" data-bs-toggle="tab" href="#transactions-sell-tab" role="tab">
                  Yesterday
                  </a>
               </li>
            </ul>
            <!-- end nav tabs -->
         </div>
      </div>
      <!-- end card header -->
      <div class="card-body px-0">
         <div class="tab-content">
            <div class="tab-pane active" id="transactions-all-tab" role="tabpanel">
               <div class="table-responsive px-3">
                  <table class="table table-striped table-bordered">
                     <th>
                        Brand
                     </th>
                     <th>
                        Model Line
                     </th>
                     <th>
                        Country
                     </th>
                     <th>
                        Region
                     </th>
                     <th>
                        Number of Inquiry
                     </th>
                     <tbody>
                        @foreach ($rowsmonth as $key => $rowsmonth)
                        <tr>
                           @php
                           $brand_name = '';
                           if (!is_null($rowsmonth->brand_id)) {
                           $brands = DB::table('brands')->where('id', $rowsmonth->brand_id)->first();
                           if (!is_null($brands)) {
                           $brand_name = $brands->brand_name;
                           }
                           }
                           @endphp
                           <td>{{ ucwords(strtolower($brand_name))}}</td>
                           @php
                           $model_line = '';
                           if (!is_null($rowsmonth->model_line_id)) {
                           $model_lines = DB::table('master_model_lines')->where('id', $rowsmonth->model_line_id)->first();
                           if (!is_null($model_lines)) {
                           $model_line = $model_lines->model_line;
                           }
                           }
                           @endphp
                           <td>{{ ucwords(strtolower($model_line))}}</td>
                           <td>{{ ucwords(strtolower($rowsmonth->location))}}</td>
                           @php
                           $regionsg = DB::table('regions')->where('country_name', $rowsmonth->location)->first();
                           $regionsf = $regionsg ? $regionsg->region_name : '';
                           @endphp
                           <td>{{ $regionsf }}</td>
                           <td><a href="{{ route('calls.showcalls', ['call' => $rowsmonth->id, 'brand_id' => $rowsmonth->brand_id, 'model_line_id' => $rowsmonth->model_line_id, 'location' => $rowsmonth->location, 'days' => '30', 'custom_brand_model' => $rowsmonth->custom_brand_model]) }}">{{ $rowsmonth->count }}</a></td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- end tab pane -->
            <div class="tab-pane" id="transactions-buy-tab" role="tabpanel">
               <div class="table-responsive px-3">
                  <table class="table table-striped table-bordered">
                     <th>
                        Brand
                     </th>
                     <th>
                        Model Line
                     </th>
                     <th>
                        Country
                     </th>
                     <th>
                        Region
                     </th>
                     <th>
                        Number of Inquiry
                     </th>
                     <tbody>
                        @foreach ($rowsweek as $key => $rowsweek)
                        <tr>
                           @php
                           $brand_name = '';
                           if (!is_null($rowsweek->brand_id)) {
                           $brands = DB::table('brands')->where('id', $rowsweek->brand_id)->first();
                           if (!is_null($brands)) {
                           $brand_name = $brands->brand_name;
                           }
                           }
                           @endphp
                           <td>{{ ucwords(strtolower($brand_name))}}</td>
                           @php
                           $model_line = '';
                           if (!is_null($rowsweek->model_line_id)) {
                           $model_lines = DB::table('master_model_lines')->where('id', $rowsweek->model_line_id)->first();
                           if (!is_null($model_lines)) {
                           $model_line = $model_lines->model_line;
                           }
                           }
                           else{
                           $model_line = $rowsweek->custom_brand_model;
                           }
                           @endphp
                           <td>{{ ucwords(strtolower($model_line))}}</td>
                           <td>{{ ucwords(strtolower($rowsweek->location))}}</td>
                           @php
                           $regionsg = DB::table('regions')->where('country_name', $rowsweek->location)->first();
                           $regionsf = $regionsg ? $regionsg->region_name : '';
                           @endphp
                           <td>{{ $regionsf }}</td>
                           <td><a href="{{ route('calls.showcalls', ['call' => $rowsweek->id, 'brand_id' => $rowsweek->brand_id, 'model_line_id' => $rowsweek->model_line_id, 'location' => $rowsweek->location, 'days' => '7', 'custom_brand_model' => $rowsweek->custom_brand_model]) }}">{{ $rowsweek->count }}</a></td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- end tab pane -->
            <div class="tab-pane" id="transactions-sell-tab" role="tabpanel">
               <div class="table-responsive px-3">
                  <table class="table table-striped table-bordered">
                     <th>
                        Brand
                     </th>
                     <th>
                        Model Line
                     </th>
                     <th>
                        Country
                     </th>
                     <th>
                        Region
                     </th>
                     <th>
                        Number of Inquiry
                     </th>
                     <tbody>
                        @foreach ($rowsyesterday as $key => $rowsyesterday)
                        <tr>
                           @php
                           $brand_name = '';
                           if (!is_null($rowsyesterday->brand_id)) {
                           $brands = DB::table('brands')->where('id', $rowsyesterday->brand_id)->first();
                           if (!is_null($brands)) {
                           $brand_name = $brands->brand_name;
                           }
                           }
                           @endphp
                           <td>{{ ucwords(strtolower($brand_name))}}</td>
                           @php
                           $model_line = '';
                           if (!is_null($rowsyesterday->model_line_id)) {
                           $model_lines = DB::table('master_model_lines')->where('id', $rowsyesterday->model_line_id)->first();
                           if (!is_null($model_lines)) {
                           $model_line = $model_lines->model_line;
                           }
                           }
                           @endphp
                           <td>{{ ucwords(strtolower($model_line))}}</td>
                           <td>{{ ucwords(strtolower($rowsyesterday->location))}}</td>
                           @php
                           $regionsg = DB::table('regions')->where('country_name', $rowsyesterday->location)->first();
                           $regionsf = $regionsg ? $regionsg->region_name : '';
                           @endphp
                           <td>{{ $regionsf }}</td>
                           <td>
                              @if (!empty($rowsyesterday->id) && !empty($rowsyesterday->brand_id) && !empty($rowsyesterday->model_line_id) && !empty($rowsyesterday->location))
                              <a href="{{ route('calls.showcalls', [
                                 'call' => $rowsyesterday->id, 
                                 'brand_id' => $rowsyesterday->brand_id, 
                                 'model_line_id' => $rowsyesterday->model_line_id, 
                                 'location' => $rowsyesterday->location, 
                                 'days' => '2', 
                                 'custom_brand_model' => $rowsyesterday->custom_brand_model ?? null
                                 ]) }}">
                              {{ $rowsyesterday->count }}
                              </a>
                              @else
                              <span>Invalid Data</span>
                              @endif
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
            <!-- end tab pane -->
         </div>
         <!-- end tab content -->
      </div>
      <!-- end card body -->
   </div>
   <!-- end card -->
</div>
<!-- end card -->
<!-- @php
   $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
   @endphp
   @if ($hasPermission)
   <div class="row">
       <div class="col-xl-6">
       <div class = "card">
       <div class = "card-header">
         <h5>Variants Without Pictures</h5>
         <div class = "card-body">
       <div class="table-responsive">
   <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
   <thead>
   <tr>
   <th>S.No</th>
   <th>Variant Name</th>
   <th>Brand</th>
   <th>Model</th>
   <th>Exterior Colour</th>
   <th>Interior Colour</th>
   <th>Action</th>
   </tr>
   </thead>
   <tbody>
   <div hidden>{{$i=0;}}</div>
   @foreach ($variants as $variantsp)
   <tr data-id="1">
   <td>{{ ++$i }}</td>
   <td>{{ $variantsp->name }}</td>
   @php
    $brand = DB::table('brands')->where('id', $variantsp->brands_id)->first();
    $brand_name = $brand->brand_name;
    @endphp
   <td>{{ $brand_name }}</td>
   @php
    $model = DB::table('master_model_lines')->where('id', $variantsp->master_model_lines_id)->first();
    $model_line = $model->model_line;
    @endphp
   <td>{{ $model_line }}</td>
   <td>{{ $variantsp->int_colour }}</td>
   <td>{{ $variantsp->ext_colour }}</td>
   <td><a data-placement="top" class="btn btn-sm btn-success" href="{{ route('variant_pictures.edit',$variantsp->id) }}"><i class="fa fa-camera" aria-hidden="true"></i></a>
   </td>
   </tr>
   @endforeach
   </tbody>
   </table>
   </div>
   </div>
   </div>
   </div>
       </div>
       <div class="col-xl-6">
       <div class = "card">
       <div class = "card-header">
         <h5>Variants Without Videos</h5>
         <div class = "card-body">
       <div class="table-responsive">
   <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
   <thead>
   <tr>
   <th>S.No</th>
   <th>Variant Name</th>
   <th>Brand</th>
   <th>Model</th>
   <th>Exterior Colour</th>
   <th>Interior Colour</th>
   <th>Action</th>
   </tr>
   </thead>
   <tbody>
   <div hidden>{{$i=0;}}</div>
   @foreach ($reels as $reels)
   <tr data-id="1">
   <td>{{ ++$i }}</td>
   <td>{{ $reels->name }}</td>
   @php
    $brand = DB::table('brands')->where('id', $reels->brands_id)->first();
    $brand_name = $brand->brand_name;
    @endphp
   <td>{{ $brand_name }}</td>
   @php
    $model = DB::table('master_model_lines')->where('id', $reels->master_model_lines_id)->first();
    $model_line = $model->model_line;
    @endphp
   <td>{{ $model_line }}</td>
   <td>{{ $reels->int_colour }}</td>
   <td>{{ $reels->ext_colour }}</td>
   <td><a data-placement="top" class="btn btn-sm btn-info" href="{{ route('variant_pictures.editreels',$reels->id) }}"><i class="fa fa-film" aria-hidden="true"></i></a></td>
   </td>
   </tr>
   @endforeach
   </tbody>
   </table>
   </div>
   </div>
   </div>
   </div>
       </div>
       @endif -->
<!-- end col -->
@endif
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
       // Example data from your controller
       let data = @json($dataforpie);
   console.log(data);
       // Extract initial labels and counts for the chart
       let labels = data.map(item => item.Reason);
       let counts = data.map(item => item.count);
   
       // Initialize the Bar Chart
       const ctx = document.getElementById('reasonBarChart').getContext('2d');
       const reasonBarChart = new Chart(ctx, {
           type: 'bar',
           data: {
               labels: labels,
               datasets: [{
                   label: 'Lead Rejection Reasons',
                   data: counts,
                   backgroundColor: [
                       'rgba(255, 99, 132, 0.2)',
                       'rgba(54, 162, 235, 0.2)',
                       'rgba(255, 206, 86, 0.2)',
                       'rgba(75, 192, 192, 0.2)',
                       'rgba(153, 102, 255, 0.2)',
                       'rgba(255, 159, 64, 0.2)'
                   ],
                   borderColor: [
                       'rgba(255, 99, 132, 1)',
                       'rgba(54, 162, 235, 1)',
                       'rgba(255, 206, 86, 1)',
                       'rgba(75, 192, 192, 1)',
                       'rgba(153, 102, 255, 1)',
                       'rgba(255, 159, 64, 1)'
                   ],
                   borderWidth: 1
               }]
           },
           options: {
               responsive: true,
               scales: {
                   y: {
                       beginAtZero: true
                   }
               },
               plugins: {
                   legend: {
                       position: 'top',
                   },
                   tooltip: {
                       enabled: true
                   }
               },
               onClick: function(event, elements) {
       if (elements.length > 0) {
           const firstElement = elements[0];
           
           // Use dataIndex directly for the index instead of elements[0].index
           const index = firstElement?.index ?? firstElement?.datasetIndex;
   
           if (index !== undefined && index < labels.length) {
               let selectedReason = labels[index];
   
               console.log("Clicked index (using dataIndex):", index);
               console.log("Selected reason:", selectedReason);
   
               let startDate = $('#reason_start_date').val();
               let endDate = $('#reason_end_date').val();
   
               // Redirect to the page with the correct query parameters
               window.location.href = `/show_leads_rejection?start_date=${startDate}&end_date=${endDate}&reason=${encodeURIComponent(selectedReason)}`;
           } else {
               console.log("Error: Unable to determine index or reason.");
           }
       } else {
           console.log("No element clicked.");
       }
   }
           }
       });
       // Function to update the Reason Chart based on date range
       function updateReasonChart() {
       let startDate = $('#reason_start_date').val();
       let endDate = $('#reason_end_date').val();
   
       $.ajax({
           url: '/reasondata',  // Replace with your endpoint URL
           type: 'GET',
           data: { start_date: startDate, end_date: endDate },
           success: function(response) {
               // Update chart data and labels with the response data
               reasonBarChart.data.labels = response.labels;
               reasonBarChart.data.datasets[0].data = response.counts;
               
               // Update the local `labels` and `counts` arrays used in onClick
               labels = response.labels;
               counts = response.counts;
   
               // Redraw the chart with the new data
               reasonBarChart.update();
           }
       });
   }
       // Initialize Date Range Picker
       function cb(start, end) {
           $('#reasonReportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
           $('#reason_start_date').val(start.format('YYYY-MM-DD'));
           $('#reason_end_date').val(end.format('YYYY-MM-DD'));
           updateReasonChart();
       }
   
       var today = moment();
       var yesterday = moment().subtract(1, 'days');
       var last7Days = moment().subtract(6, 'days');
       var last30Days = moment().subtract(29, 'days');
       var thisMonthStart = moment().startOf('month');
       var thisMonthEnd = moment().endOf('month');
       var lastMonthStart = moment().subtract(1, 'month').startOf('month');
       var lastMonthEnd = moment().subtract(1, 'month').endOf('month');
   
       $('#reasonReportrange').daterangepicker({
           startDate: last7Days,
           endDate: today,
           ranges: {
               'Today': [today, today],
               'Yesterday': [yesterday, yesterday],
               'Last 7 Days': [last7Days, today],
               'Last 30 Days': [last30Days, today],
               'This Month': [thisMonthStart, thisMonthEnd],
               'Last Month': [lastMonthStart, lastMonthEnd]
           }
       }, cb);
   
       cb(last7Days, today); //
   });
</script>
<script>
   $(function() {
       function cb(start, end) {
           $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
           $('#start_date').val(start.format('YYYY-MM-DD'));
           $('#end_date').val(end.format('YYYY-MM-DD'));
           updateCharts();
       }
   
       var today = moment();
       var yesterday = moment().subtract(1, 'days');
       var last7Days = moment().subtract(6, 'days');
       var last30Days = moment().subtract(29, 'days');
       var thisMonthStart = moment().startOf('month');
       var thisMonthEnd = moment().endOf('month');
       var lastMonthStart = moment().subtract(1, 'month').startOf('month');
       var lastMonthEnd = moment().subtract(1, 'month').endOf('month');
   
       $('#reportrange').daterangepicker({
           startDate: last7Days,
           endDate: today,
           ranges: {
               'Today': [today, today],
               'Yesterday': [yesterday, yesterday],
               'Last 7 Days': [last7Days, today],
               'Last 30 Days': [last30Days, today],
               'This Month': [thisMonthStart, thisMonthEnd],
               'Last Month': [lastMonthStart, lastMonthEnd]
           }
       }, cb);
   
       cb(last7Days, today);
   });
   
   function updateCharts() {
       var startDate = $('#start_date').val();
       var endDate = $('#end_date').val();
       $.ajax({
           url: '{{ route('homemarketing.update-charts') }}',
           type: 'POST',
           data: {
               _token: '{{ csrf_token() }}',
               start_date: startDate,
               end_date: endDate
           },
           success: function(response) {
               var chartData = response.chartData;
               var existingBarChart = Chart.getChart('barChart');
               if (existingBarChart) {
                   existingBarChart.destroy();
               }
               $('#barChart').attr('width', $('#chartContainer').width());
               $('#barChart').attr('height', 350);
               var ctx = document.getElementById('barChart').getContext('2d');
               var barChart = new Chart(ctx, {
                   type: 'bar',
                   data: chartData,
                   options: {
                       scales: {
                           x: {
                               type: 'category',
                               stacked: true
                           },
                           y: {
                               stacked: true
                           }
                       }
                   }
               });
           },
           error: function(error) {
               console.error(error);
           }
       });
   }
</script>
<script>
   var totalleads = {!! json_encode($totalleads) !!};
   var ctx = document.getElementById('totalleads').getContext('2d');
       var myChart = new Chart(ctx, {
           type: 'line',
           data: totalleads,
           options: {
               scales: {
                   x: {
                       ticks: {
                           display: true
                       },
                       grid: {
                           display: true
                       }
                   },
                   y: {
                       display: false,
                       grid: {
                           display: false
                       }
                   }
               },
               plugins: {
                   legend: {
                       display: false
                   }
               }
           }
       });
       var totalvariantss = {!! json_encode($totalvariantss) !!};
   var ctx = document.getElementById('totalvariantss').getContext('2d');
       var myChart = new Chart(ctx, {
           type: 'line',
           data: totalvariantss,
           options: {
               scales: {
                   x: {
                       ticks: {
                           display: true
                       },
                       grid: {
                           display: true
                       }
                   },
                   y: {
                       display: false,
                       grid: {
                           display: false
                       }
                   }
               },
               plugins: {
                   legend: {
                       display: false
                   }
               }
           }
       });
       
</script>
<script type="text/javascript">
   $(function() {
       var start = moment().subtract(6, 'days');
       var end = moment();
       var table = $('#dtBasicExample1').DataTable({
           "searching": true,
           @if (Auth::user()->hasPermissionForSelectedRole('approve-reservation'))
           "paging": false,
           "pageLength": -1,
           @else
           "paging": true,
           "pageLength": 7,
           @endif
       });
       function populateFilterDropdowns() {
       $('#dtBasicExample1 thead select').remove();
       table.columns().every(function () {
           var column = this;
           var columnIndex = column[0][0];
           var columnName = $(column.header()).text().trim();
           if (columnName) {
               var select = $('<select class="form-control my-1"><option value="">All</option></select>')
                   .appendTo($(column.header()))
                   .on('change', function () {
                       var val = $.fn.dataTable.util.escapeRegex($(this).val());
                       table.column(columnIndex)
                           .search(val ? '^' + val + '$' : '', true, false)
                           .draw();
                   });
               column.data().unique().sort().each(function (d, j) {
                   select.append('<option value="' + d + '">' + d + '</option>');
               });
           }
       });
   }
       function loadDataAndPopulateFilters(start, end) {
           $.ajax({
               url: '{{ route('homemarketing.leaddistruition') }}',
               method: 'POST',
               data: {
                   _token: '{{ csrf_token() }}',
                   start_date: start.format('YYYY-MM-DD'),
                   end_date: end.format('YYYY-MM-DD'),
               },
               success: function(response) {
                   table.clear().draw();
                   var totalCallCount = 0;
                   var totalCallCount27 = 0;
                   var totalCallCount16 = 0;
                   var totalCallCount6 = 0;
                   var totalCallCount35 = 0;
                   var totalCallCount40 = 0;
                   $.each(response.data, function(index, item) {
                       var formattedDate = moment(item.call_date).format('DD-MMM-YYYY');
                       var row = [
                           formattedDate,
                           item.sales_person_name,
                           item.call_count,
                       ];
                       if ({{ Auth::user()->hasPermissionForSelectedRole('approve-reservation') ? 'true' : 'false' }}) {
                           row.push(
                               item.call_count_27,
                               item.call_count_16,
                               item.call_count_6,
                               item.call_count_35,
                               item.call_count_40
                           );
                           totalCallCount += parseInt(item.call_count);
                           totalCallCount27 += parseInt(item.call_count_27);
                           totalCallCount16 += parseInt(item.call_count_16);
                           totalCallCount6 += parseInt(item.call_count_6);
                           totalCallCount35 += parseInt(item.call_count_35);
                           totalCallCount40 += parseInt(item.call_count_40);
                       }
                       table.row.add(row).draw(false);
                   });
                   populateFilterDropdowns();
   
                   if ({{ Auth::user()->hasPermissionForSelectedRole('approve-reservation') ? 'true' : 'false' }}) {
                       var totalRow = [
                           'Total',
                           '',
                           totalCallCount,
                           totalCallCount27,
                           totalCallCount16,
                           totalCallCount6,
                           totalCallCount35,
                           totalCallCount40
                       ];
                       var totalRowNode = table.row.add(totalRow).draw(false).node();
                       $(totalRowNode).addClass('total-row');
                   }
               }
           });
       }
       loadDataAndPopulateFilters(start, end);
       $('#leadsdis').daterangepicker({
           startDate: start,
           endDate: end,
           ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
           }
       }, function(selectedStart, selectedEnd) {
           $('#leadsdis span').html(selectedStart.format('MMMM D, YYYY') + ' - ' + selectedEnd.format('MMMM D, YYYY'));
           loadDataAndPopulateFilters(selectedStart, selectedEnd);
       });
   
       $('#leadsdis span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
   });
</script>
<!-- <div id="root"></div>
   <link href="static/css/main.073c9b0a.css" rel="stylesheet">
   <script src="static/js/main.03fee2c2.js"></script> -->
@endsection
@push('scripts')
<script>
   $("#addon_type").change(function(){
   
       var addonType = $(this).val();
   
       $.ajax
       ({
           url: 'addon-dashboard/sellingPriceFilter',
           type: "GET",
           dataType: "json",
           data:{
               addon_type:addonType
           },
           success:function(response)
           {
               var withOutSellingPrices = response.withOutSellingPrices;
               var pendingSellingPrices = response.pendingSellingPrices;
   
               $('#addon-without-selling-prices').DataTable().destroy();
               $("#table-without-selling-price-body").empty();
               withOutSellingPrices.forEach(function(item) {
                       var row = '<tr >' +
                           '<td>' + item.addon_code + '</td>' +
                           '<td>' + item.addon_name.name  + '</td>';
   
                       $("#table-without-selling-price-body").append(row);
                   })
              $("#addon-without-selling-prices").DataTable();
   
                   ///// pending ///////
               $('#addon-pending-selling-prices').DataTable().destroy();
               $("#table-pending-selling-price-body").empty();
               pendingSellingPrices.forEach(function(item) {
                   var row = '<tr >' +
                       '<td>' + item.addon_detail.addon_code + '</td>' +
                       '<td>' + item.addon_detail.addon_name.name + '</td>'+
                       '<td>' + item.selling_price + '</td>' +
                       '<td>' + item.created_by.name + '</td>';
   
                   $("#table-pending-selling-price-body").append(row);
               })
               $("#addon-pending-selling-prices").DataTable();
           }
       });
   
   
   });
</script>
<!-- Add JavaScript to handle row expansion -->
<script>
   $(document).ready(function() {
       // Initialize DataTables
       var table = $('#vehicleStockTable').DataTable({
           "paging": true,
           "searching": true,
           "ordering": true,
           "order": [],
           "columnDefs": [
               { "orderable": false, "targets": 0 }
           ]
       });
   
       // Handle row expansion
       $('#vehicleStockTable tbody').on('click', 'a.expand-row', function(e) {
           e.preventDefault();
           var tr = $(this).closest('tr');
           var row = table.row(tr);
           var variantId = $(this).data('variantId');
   
           if (row.child.isShown()) {
               // This row is already open - close it
               row.child.hide();
               tr.removeClass('shown');
               $(this).text('+');
           } else {
               // Open this row - create a detail row
               var detailHtml = getDetailRowHtml(variantId);
               row.child(detailHtml).show();
               tr.addClass('shown');
               $(this).text('-');
           }
       });
   
       // Function to get the HTML for the detail row
       function getDetailRowHtml(variantId) {
           var detailHtml = '<table class="table table-bordered">' +
                               '<thead class="bg-light">' +
                                   '<tr>' +
                                       '<th>Interior Colour</th>' +
                                       '<th>Exterior Colour</th>' +
                                       '<th>Free Stock</th>' +
                                       '<th>Total Stock</th>' +
                                   '</tr>' +
                               '</thead>' +
                               '<tbody>';
   
           // Fetch data via an AJAX call or using existing data
           $.ajax({
               url: '/vehicle-details-dp',  // Route defined in web.php
               method: 'GET',
               data: { variant_id: variantId },
               async: false, // For simplicity, keep this synchronous
               success: function(data) {
                   data.details.forEach(function(detail) {
                       detailHtml += '<tr>' +
                                       '<td>' + detail.intColourName + '</td>' +
                                       '<td>' + detail.exColourName + '</td>' +
                                       '<td>' + detail.freeStock + '</td>' +
                                       '<td>' + detail.totalStock + '</td>' +
                                     '</tr>';
                   });
               }
           });
   
           detailHtml += '</tbody></table>';
   
           return detailHtml;
       }
   });
       
</script>
<script>
   $(document).ready(function() {
       // Initialize DataTables
       var table = $('#vehicleStockTablebelgium').DataTable({
           "paging": true,
           "searching": true,
           "ordering": true,
           "order": [],
           "columnDefs": [
               { "orderable": false, "targets": 0 }
           ]
       });
   
       // Handle row expansion
       $('#vehicleStockTablebelgium tbody').on('click', 'a.expand-row-belgium', function(e) {
           e.preventDefault();
           var tr = $(this).closest('tr');
           var row = table.row(tr);
           var variantId = $(this).data('variantId');
   
           if (row.child.isShown()) {
               // This row is already open - close it
               row.child.hide();
               tr.removeClass('shown');
               $(this).text('+');
           } else {
               // Open this row - create a detail row
               var detailHtmlbelgium = getDetailRowHtml(variantId);
               row.child(detailHtmlbelgium).show();
               tr.addClass('shown');
               $(this).text('-');
           }
       });
   
       // Function to get the HTML for the detail row
       function getDetailRowHtml(variantId) {
           var detailHtmlbelgium = '<table class="table table-bordered">' +
                               '<thead class="bg-light">' +
                                   '<tr>' +
                                       '<th>Interior Colour</th>' +
                                       '<th>Exterior Colour</th>' +
                                       '<th>Free Stock</th>' +
                                       '<th>Total Stock</th>' +
                                   '</tr>' +
                               '</thead>' +
                               '<tbody>';
   
           // Fetch data via an AJAX call or using existing data
           $.ajax({
               url: '/vehicle-details-dpbelgium',  // Route defined in web.php
               method: 'GET',
               data: { variant_id: variantId },
               async: false, // For simplicity, keep this synchronous
               success: function(data) {
                   data.details.forEach(function(detailbelgium) {
                       detailHtmlbelgium += '<tr>' +
                                       '<td>' + detailbelgium.intColourName + '</td>' +
                                       '<td>' + detailbelgium.exColourName + '</td>' +
                                       '<td>' + detailbelgium.freeStock + '</td>' +
                                       '<td>' + detailbelgium.totalStock + '</td>' +
                                     '</tr>';
                   });
               }
           });
   
           detailHtmlbelgium += '</tbody></table>';
   
           return detailHtmlbelgium;
       }
   });
   document.getElementById('monthSelector').addEventListener('change', function() {
       const selectedMonth = this.value;
       const url = document.getElementById('filterForm').action;
       
       fetch(`${url}?month=${selectedMonth}`, {
           headers: {
               'X-Requested-With': 'XMLHttpRequest',
           },
       })
       .then(response => response.text())
       .then(data => {
           // Replace the commission table with the updated data
           document.getElementById('commissionTable').innerHTML = data;
       })
       .catch(error => console.error('Error:', error));
   });
       
</script>
<script type="text/javascript">
   $(function() {
       var start = moment().subtract(6, 'days');
       var end = moment();
   
       // Initialize DataTable with search option
       var table = $('#dtBasicExample4').DataTable({
           "searching": true,
       });
   
       // Function to calculate and display total row based on filtered data
       function calculateAndDisplayTotals() {
           var totals = {
               New: 0, Contacted: 0, Working: 0, Qualify: 0, 
               Rejected: 0, Closed: 0, Converted: 0, Quoted: 0, 
               Prospecting: 0, NewDemand: 0
           };
   
           // Calculate totals based on currently displayed rows
           table.rows({ filter: 'applied' }).data().each(function(rowData) {
               if (rowData.length > 2) { // Ensuring rowData has the necessary columns
                   totals.New += parseInt(rowData[2]) || 0;
                   totals.Contacted += parseInt(rowData[3]) || 0;
                   totals.Working += parseInt(rowData[4]) || 0;
                   totals.Qualify += parseInt(rowData[5]) || 0;
                   totals.Rejected += parseInt(rowData[6]) || 0;
                   totals.Converted += parseInt(rowData[7]) || 0;
                   totals.Quoted += parseInt(rowData[8]) || 0;
                   totals.Prospecting += parseInt(rowData[9]) || 0;
                   totals.NewDemand += parseInt(rowData[10]) || 0;
                   totals.Closed += parseInt(rowData[11]) || 0;
               }
           });
   
           // Remove any existing total row
           table.row('.total-row').remove();
   
           // Add the total row
           var totalRow = [
               'Total', '', totals.New, totals.Contacted, totals.Working,
               totals.Qualify, totals.Rejected, totals.Converted,
               totals.Quoted, totals.Prospecting, totals.NewDemand,
               totals.Closed
           ];
           var totalRowNode = table.row.add(totalRow).draw(false).node();
           $(totalRowNode).addClass('total-row');
       }
   
       // Populate filter dropdowns for each column
       function populateFilterDropdowns() {
           $('#dtBasicExample4 thead select').remove();
           table.columns().every(function() {
               var column = this;
               var columnIndex = column.index();
               var columnName = $(column.header()).text().trim();
   
               if (columnName) {
                   var select = $('<select class="form-control my-1"><option value="">All</option></select>')
                       .appendTo($(column.header()))
                       .on('change', function() {
                           var val = $.fn.dataTable.util.escapeRegex($(this).val());
                           table.column(columnIndex)
                               .search(val ? '^' + val + '$' : '', true, false)
                               .draw();
   
                           // Recalculate totals after filter is applied
                           calculateAndDisplayTotals();
                       });
                   
                   column.data().unique().sort().each(function(d) {
                       select.append('<option value="' + d + '">' + d + '</option>');
                   });
               }
           });
       }
   
       // Load data and populate filters based on date range
       function loadDataAndPopulateFilters(start, end) {
           $.ajax({
               url: '{{ route('homemarketing.leadstatuswise') }}',
               method: 'POST',
               data: {
                   _token: '{{ csrf_token() }}',
                   start_date: start.format('YYYY-MM-DD'),
                   end_date: end.format('YYYY-MM-DD'),
               },
               success: function(response) {
                   table.clear().draw();
   
                   $.each(response.data, function(index, item) {
                       var formattedDate = moment(item.call_date).format('DD-MMM-YYYY');
                       
                       // Conditionally populate the row array based on user permission
                       var row = [
                           formattedDate,
                           item.sales_person_name,
                       ];
   
                       @if (Auth::user()->hasPermissionForSelectedRole('leads-working-analysis'))
                           row.push(
                               item.call_count_New, item.call_count_contacted,
                               item.call_count_working, item.call_count_qualify,
                               item.call_count_Rejected, item.call_count_converted,
                               item.call_count_quoted, item.call_count_Prospecting,
                               item.call_count_new_demand, item.call_count_closed
                           );
                       @else
                           row.push(item.call_count);
                       @endif
   
                       table.row.add(row).draw(false);
                   });
   
                   populateFilterDropdowns();
   
                   // Calculate totals after loading data
                   calculateAndDisplayTotals();
               }
           });
       }
   
       loadDataAndPopulateFilters(start, end);
   
       // Date Range Picker
       $('#leadsstatuswise').daterangepicker({
           startDate: start,
           endDate: end,
           ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
           }
       }, function(selectedStart, selectedEnd) {
           $('#leadsstatuswise span').html(selectedStart.format('MMMM D, YYYY') + ' - ' + selectedEnd.format('MMMM D, YYYY'));
           loadDataAndPopulateFilters(selectedStart, selectedEnd);
       });
   
       $('#leadsstatuswise span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
   });
</script>
@endpush