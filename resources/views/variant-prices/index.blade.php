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
                                    <th>Detail</th>
                                    <th>Variant</th>
                                    <th>Quantity</th>
                                    <th>View Items</th>

                                </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($vehicleWithPrices as $key => $vehicleWithPrice)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $vehicleWithPrice->variant->brand->brand_name ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->master_model_lines->model_line ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->model_detail ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->detail ?? '' }}</td>
                                    <td>{{ $vehicleWithPrice->variant->name }}</td>
                                    <td>{{ $vehicleWithPrice->total ?? '' }} </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#view-vehicle-child-items-{{$vehicleWithPrice->id}}">
                                            View
                                        </button>
                                    </td>
                                    <div class="modal fade" id="view-vehicle-child-items-{{$vehicleWithPrice->id}}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicles</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    @if($vehicleWithPrice->total > 0)
                                                        <div class="row  d-none d-lg-block d-xl-block d-xxl-block">
                                                            <div class="d-flex">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="row">
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label class="form-label">Brand</label>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">Variant</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label class="form-label">My</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label class="form-label">Interior</label>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">Exterior</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label class="form-label"> Price</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label  class="form-label">Quantity</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label class="form-label">Updated By</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label class="form-label">Updated At</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach($vehicleWithPrice->similar_vehicles_with_price as $value => $vehicle)
                                                            <div class="row">
                                                                <div class="d-flex">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="row mt-3">
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Brand</label>
                                                                                <input type="text" value="{{ $vehicle->variant->brand->brand_name ?? '' }}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                                <input type="text" value="{{ $vehicle->variant->name ?? '' }}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">My</label>
                                                                                <input type="text" value="{{ $vehicle->variant->my ?? '' }}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label  class="form-label d-lg-none d-xl-none d-xxl-none">Interior</label>
                                                                                <input type="text" value="{{ $vehicle->interior->name ?? ''  }}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Exterior</label>
                                                                                <input type="text" value="{{ $vehicle->exterior->name ?? ''}}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none"> Price</label>
                                                                                <input type="text" value="{{ $vehicle->price }}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                                <input type="text" value="{{ $vehicle->count }}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Updated By</label>
                                                                                <input type="text" value="{{ $vehicle->variant->availableColor->user->name }}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Updated At</label>
                                                                                <input type="text" value="{{ \Carbon\Carbon::parse( $vehicle->variant->availableColor->updated_at)->format('d M Y') }}"
                                                                                 readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <a href="{{ route('variant-prices.edit', $vehicle->id) }}">
                                                                                    <button type="button" class="btn btn-primary btn-sm mt-1">
                                                                                        Update Price</button>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-center"> No Data Available! </span>
                                                    @endif

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
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
                                <th>Detail</th>
                                <th>Variant</th>
                                <th>Quantity</th>
                                <th>View Items</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div hidden>{{$i=0;}}
                            </div>
                            @foreach ($vehicleWithoutPrices as $key => $vehicleWithoutPrice)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->brand->brand_name ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->master_model_lines->model_line ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->model_detail ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->detail ?? '' }}</td>
                                    <td>{{ $vehicleWithoutPrice->variant->name }}</td>
                                    <td>{{ $vehicleWithoutPrice->total ?? '' }} </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#view-vehicle-without-price-child-items-{{$vehicleWithoutPrice->id}}">
                                            View
                                        </button>
                                    </td>

                                    <div class="modal fade" id="view-vehicle-without-price-child-items-{{$vehicleWithoutPrice->id}}" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicles</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    @if($vehicleWithoutPrice->total > 0)
                                                        <div class="row  d-none d-lg-block d-xl-block d-xxl-block">
                                                            <div class="d-flex">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="row">
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">Brand</label>
                                                                        </div>
                                                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                                                            <label class="form-label">Variant</label>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">My</label>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">Interior</label>
                                                                        </div>
                                                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                                                            <label class="form-label">Exterior</label>
                                                                        </div>
                                                                        <div class="col-lg-1 col-md-12 col-sm-12">
                                                                            <label  class="form-label">Quantity</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @foreach($vehicleWithoutPrice->similar_vehicles_without_price as $value => $vehicle)
                                                            <div class="row">
                                                                <div class="d-flex">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="row mt-3">
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Brand</label>
                                                                                <input type="text" value="{{ $vehicle->variant->brand->brand_name ?? '' }}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-3 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                                                                <input type="text" value="{{ $vehicle->variant->name ?? '' }}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">My</label>
                                                                                <input type="text" value="{{ $vehicle->variant->my ?? '' }}" readonly class="form-control" >
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label  class="form-label d-lg-none d-xl-none d-xxl-none">Interior</label>
                                                                                <input type="text" value="{{ $vehicle->interior->name ?? ''  }}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-2 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Exterior</label>
                                                                                <input type="text" value="{{ $vehicle->exterior->name ?? ''}}" readonly class="form-control">
                                                                            </div>
                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                                <input type="text" value="{{ $vehicle->count }}" readonly class="form-control">
                                                                            </div>

                                                                            <div class="col-lg-1 col-md-12 col-sm-12">
                                                                                <a href="{{ route('variant-prices.edit', $vehicle->id) }}">
                                                                                    <button type="button" class="btn btn-primary btn-sm mt-1" >
                                                                                        Update Price</button>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-center"> No Data Available! </span>
                                                    @endif

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
{{--    @endcan--}}
@endsection


















