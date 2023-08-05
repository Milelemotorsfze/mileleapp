@extends('layouts.table')
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
  </style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('content')
@can('Calls-view')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
                    @endphp
                    @if ($hasPermission)
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <!-- card -->
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

                                        <div class="col-6">
                                        <canvas id="totalleads"></canvas>
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
              <div class="row">
              <div class="col-xl-8">
                
              <div class="card">
              <div style="text-align: center;">
        <h3>Daily Calls & Messages Leads</h3>
    </div>
        <canvas id="barChart"></canvas>
</div>
</div>
                            <div class="col-xl-4">
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
<td>{{ $brand_name }}</td>

@php
    $model_line = '';
    if (!is_null($rowsmonth->model_line_id)) {
        $model_lines = DB::table('master_model_lines')->where('id', $rowsmonth->model_line_id)->first();
        if (!is_null($model_lines)) {
          $model_line = $model_lines->model_line;
        }
    }
@endphp  
<td>{{ $model_line }}</td>
                                                            <td>{{ $rowsmonth->location }}</td>
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
                                                    <td>{{ $brand_name }}</td>
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
                                                    <td>{{ $model_line }}</td>
                                                    <td>{{ $rowsweek->location }}</td>
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
<td>{{ $brand_name }}</td>
@php
    $model_line = '';
    if (!is_null($rowsyesterday->model_line_id)) {
        $model_lines = DB::table('master_model_lines')->where('id', $rowsyesterday->model_line_id)->first();
        if (!is_null($model_lines)) {
          $model_line = $model_lines->model_line;
        }
    }
@endphp  
<td>{{ $model_line }}</td>
                                                            <td>{{ $rowsyesterday->location }}</td>
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
                        <!-- end col -->
</div>
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
<script>
var chartData = {!! json_encode($chartData) !!};
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
    @endif
    @endcan
    <!-- <div id="root"></div>
    <link href="static/css/main.073c9b0a.css" rel="stylesheet">
    <script src="static/js/main.03fee2c2.js"></script> -->
@endsection