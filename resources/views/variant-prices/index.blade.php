@extends('layouts.table')
@section('content')
    <style>
        .modal-xl{
            max-width: 1700px;
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">
            Price List
        </h4>
        <a class="btn btn-sm btn-success float-end" href="{{ route('variantprices.allvariantprice') }}" text-align: right>
        <i class="fa fa-info" aria-hidden="true"></i> Full Price List
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    </div>
    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicle-selling-price');
                                        @endphp
                                        @if ($hasPermission)
        <div class="portfolio">
            <ul class="nav nav-pills nav-fill" id="my-tab">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#active-stock">Active Stock Price</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#inactive-stock">Inactive Stock Price</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="active-stock">
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="variant-with-price-table" class="table table-striped table-editable table-edits table table-condensed">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.NO
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Model Description</th>
                                    <th>Variant</th>
                                    <th>Variant Detail</th>
                                    <th>Price Status</th>
                                    <th>Variant Quantity</th>
                                    <th>Total Vehicle Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($activeStocks as $key => $activeStock)
                               <tr   onclick="window.location.href = '{{ route('variant-price.edit',[ 'id' => $activeStock->id, 'type' => '1']) }}'">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $activeStock->variant->brand->brand_name ?? '' }}</td>
                                    <td>{{ $activeStock->variant->master_model_lines->model_line ?? '' }}</td>
                                    <td>{{ $activeStock->variant->model_detail ?? '' }}</td>
                                    <td>{{ $activeStock->variant->name ?? '' }}</td>
                                    <td>{{ $activeStock->variant->detail ?? '' }}</td>
                                    <td>
                                        @if($activeStock->active_vehicle_price_status == 1)
                                            Available
                                        @else
                                            Not Available
                                        @endif
                                        </td>
                                    <td>{{ $activeStock->similar_vehicles_with_active_stock->count() ?? '' }} </td>
                                    <td>{{ $activeStock->total }}</td>
                                </tr>
                               </a>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="inactive-stock">
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="variant-without-price-table" class="table table-striped table-editable table-edits table table-condensed">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.NO</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Model Year</th>
                                <th>Model Description</th>
                                <th>Variant</th>
                                <th>Variant Description</th>
                                <th>Price Status</th>
                                <th>Variant Quantity</th>
                                <th>Total Vehicle Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($InactiveStocks as $key => $InactiveStock)
                                <tr onclick="window.location.href = '{{ route('variant-price.edit',[ 'id' => $InactiveStock->id, 'type' => '2']) }}'">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $InactiveStock->variant->brand->brand_name ?? '' }}</td>
                                    <td>{{ $InactiveStock->variant->master_model_lines->model_line ?? '' }}</td>
                                    <td>{{ $InactiveStock->variant->my ?? '' }}</td>
                                    <td>{{ $InactiveStock->variant->model_detail ?? '' }}</td>
                                    <td>{{ $InactiveStock->variant->name ?? ''}}</td>
                                    <td>{{ $InactiveStock->variant->detail ?? '' }}</td>
                                    <td>
                                        @if($InactiveStock->inactive_vehicle_price_status == 1)
                                            Available
                                        @else
                                            Not Available
                                        @endif</td>
                                    <td>{{ $InactiveStock->similar_vehicles_with_inactive_stock->count() ?? '' }} </td>
                                    <td>{{ $InactiveStock->total }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endif
@endsection


















