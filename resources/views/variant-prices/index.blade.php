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
    </div>
{{--    @can('PFI-list')--}}
        <div class="portfolio">
            <ul class="nav nav-pills nav-fill" id="my-tab">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#with-price">Price Added Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#all">Price Not Added</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="with-price">
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
                                    <th>Variant Quantity</th>
                                    <th>Total Vehicle Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($vehicleWithPrices as $key => $vehicleWithPrice)
                               <tr  onclick="window.location.href = '{{ route('variant-price.edit',[ 'id' => $vehicleWithPrice->id, 'type' => '1']) }}'">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $vehicleWithPrice->variant->brand->brand_name ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->master_model_lines->model_line ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->model_detail ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->name }}</td>
                                   <td>{{ $vehicleWithPrice->variant->detail ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->similar_vehicles_with_price->count() ?? '' }} </td>
                                    <td>{{ $vehicleWithPrice->total }}</td>

                                </tr>
                               </a>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="all">
                <div class="card-body">
                    <div class="table-responsive" >
                        <table id="variant-without-price-table" class="table table-striped table-editable table-edits table table-condensed">
                            <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.NO</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Model Description</th>
                                <th>Variant</th>
                                <th>Variant Detail</th>
                                <th>Variant Quantity</th>
                                <th>Total Vehicle Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($vehicleWithoutPrices as $key => $vehicleWithoutPrice)
                                <tr onclick="window.location.href = '{{ route('variant-price.edit',[ 'id' => $vehicleWithoutPrice->id, 'type' => '2']) }}'">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->brand->brand_name ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->master_model_lines->model_line ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->model_detail ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->name }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->detail ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->similar_vehicles_without_price->count() ?? '' }} </td>
                                    <td>{{ $vehicleWithoutPrice->total }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

{{--    @endcan--}}
    <!-- Modal -->

@endsection


















