@extends('layouts.table')
@section('content')
@php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicle-selling-price');
                                        @endphp
                                        @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Edit Price</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ route('variant-prices.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Brand:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->brand->brand_name ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold"> Variant:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span>{{ $vehicle->variant->name ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Variant Detail:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span>{{ $vehicle->variant->detail ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row">
                                <div class="col-lg-5 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Model Year:</label>
                                </div>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->my ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Model:</label>
                                </div>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->master_model_lines->model_line ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Model Description:</label>
                                </div>
                                <div class="col-lg-7 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->model_detail ?? '' }}</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Steering:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->steering ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Fuel Type:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->fuel_type ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Transmission:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->transmission ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Engine Capacity:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->engine ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Seating Capacity:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->seat ?? '' }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-3 col-sm-12">
                                    <label for="choices-single-default" class="form-label fw-bold">Upholstery:</label>
                                </div>
                                <div class="col-lg-8 col-md-9 col-sm-12">
                                    <span> {{ $vehicle->variant->upholestry ?? '' }}</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <form id="price-update" name="price-update" action="{{ route('variant-prices.update', $vehicle->id) }}" method="POST" >
                @csrf
                @method('PUT')
                @foreach($vehicles as $value => $vehicle)
                    <input type="hidden" value="{{ $vehicle->id }}" name="vehicle_ids[]">
                @endforeach

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Vehicle Price Details</h4>
                        @if(request()->type == 1)
                            <button type="button" class="btn btn-sm btn-primary float-end enable-price-filed" >Price Update</button>
                            <button type="submit" class="btn btn-sm btn-success float-end update-prices" hidden> Update</button>
                         @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" >
                            <table id="vehicle-with-price-table" class="table table-striped table-editable table-edits table table-condensed">
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.NO</th>
                                    <th>Interior</th>
                                    <th>Exterior</th>
                                    <th>Stock Quantity</th>
                                    <th>Price Status</th>
                                    <th>Current Price</th>
                                    <th>Effective Date</th>
                                    <th>Previous Price</th>
                                    <th>Previous Price Dated</th>
                                     <th>Updated By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach($vehicles as $key =>  $vehicle)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $vehicle->interior->name ?? ''  }} </td>
                                        <td>{{ $vehicle->exterior->name ?? ''}} </td>
                                        <td>{{ $vehicle->count }}</td>
                                        <td>
                                            @if($vehicle->price)
                                                Available
                                            @else
                                                Not Available
                                            @endif
                                        </td>
                                        <td><input type="number" class="prices w-100" id="price-{{$key+1}}" readonly  name="prices[]" min="0" value="{{$vehicle->price}}"> </td>
                                        <td>{{ $vehicle->updated_at }}</td>
                                        <td>{{ $vehicle->old_price }}</td>
                                        <td>{{ $vehicle->old_price_dated }}</td>
                                        <td>{{ $vehicle->updated_by }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
                <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Variant Price Logs</h4>
                </div>
                <div class="card-body">
                <div class="table-responsive" >
                    <table id="vehicle-price-histories-table" class="table table-striped table-editable table-edits table table-condensed">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Interior</th>
                            <th>Exterior</th>
                            <th>New Price</th>
                            <th>Old Price</th>
                            <th>Updated Date</th>
                            <th>Updated By</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach($variantPriceHistories as $value => $variantPriceHistory)
                            <tr>
                                <td>{{ ++$i  }}</td>
                                <td>{{ $variantPriceHistory->availableColour->interior->name ?? ''  }} </td>
                                <td>{{ $variantPriceHistory->availableColour->exterior->name ?? ''}} </td>
                                <td>{{$variantPriceHistory->new_price}} </td>
                                <td>{{ $variantPriceHistory->old_price }}</td>
                                <td>{{ \Carbon\Carbon::parse($variantPriceHistory->updated_at)->format('d/m/y, H:i:s')  }}</td>
                                <td>{{ $variantPriceHistory->user->name ?? '' }}</td>
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
@push('scripts')
    <script>
        $('.enable-price-filed').click(function() {
            $(this).hide();
            $('.update-prices').attr('hidden',false);
            $('.prices').attr('readonly',false);

        })
        $("#price-update").validate({
            ignore: [],
            rules: {
                "prices[]": {
                    maxlength: 9,
                },
            },
        });
    </script>
@endpush

