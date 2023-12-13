@extends('layouts.table')
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
<style>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($leadsCount as $lead)
                                                <tr>
                                                    <td>{{ $lead->name }}</td>
                                                    <td>{{ $lead->lead_count }}</td>
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
                        </div><!-- end card header -->
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
                        </div><!-- end card header -->
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
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div><!-- end col -->
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
                                <a class="btn btn-sm btn-success" href="{{ route('calls.addnewleads') }}" text-align: right>
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
                                </div><!-- end card header -->
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
                                </div><!-- end card header -->
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
                                </div><!-- end card header -->

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
<td><a href="{{ route('calls.show', ['call' => $rowsmonth->id, 'brand_id' => $rowsmonth->brand_id, 'model_line_id' => $rowsmonth->model_line_id, 'location' => $rowsmonth->location, 'days' => '30', 'custom_brand_model' => $rowsmonth->custom_brand_model]) }}">{{ $rowsmonth->count }}</a></td>
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
                                                    <td><a href="{{ route('calls.show', ['call' => $rowsweek->id, 'brand_id' => $rowsweek->brand_id, 'model_line_id' => $rowsweek->model_line_id, 'location' => $rowsweek->location, 'days' => '7', 'custom_brand_model' => $rowsweek->custom_brand_model]) }}">{{ $rowsweek->count }}</a></td>
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
<td><a href="{{ route('calls.show', ['call' => $rowsyesterday->id, 'brand_id' => $rowsyesterday->brand_id, 'model_line_id' => $rowsyesterday->model_line_id, 'location' => $rowsyesterday->location, 'days' => '2', 'custom_brand_model' => $rowsyesterday->custom_brand_model]) }}">{{ $rowsyesterday->count }}</a></td>
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
@endpush