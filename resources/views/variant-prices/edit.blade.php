@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Price</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label"> Variant</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $vehicle->variant->name }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Brand</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ $vehicle->variant->brand->brand_name ?? '' }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Model</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ $vehicle->variant->master_model_lines->model_line ?? '' }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Model Description</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ $vehicle->variant->model_detail ?? '' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Variant Detail</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $vehicle->vehicle->variant->detail ?? '' }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">Model Year</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span> {{ $vehicle->variant->my ?? '' }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('variant-prices.update', $vehicle->id) }}" method="POST" >
                @csrf
                @method('PUT')
                @foreach($vehicles as $value => $vehicle)
                    <input type="hidden" value="{{ $vehicle->id }}" name="vehicle_ids[]">
                @endforeach

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Vehicle Price Details</h4>
                        <button type="button" class="btn btn-sm btn-primary float-end enable-price-filed" >Price Update</button>
                        <button type="submit" class="btn btn-sm btn-primary float-end update-prices" hidden> Update</button>

                    </div>
                    <div class="card-body">

                        <div class="table-responsive" >
                            <table id="vehicle-with-price-table" class="table table-striped table-editable table-edits table table-condensed">
                                <thead class="bg-soft-secondary">
                                <tr >
                                    <th>S.NO</th>
                                    <th>Updated Date</th>
                                    <th>Interior</th>
                                    <th>Exterior</th>
                                    <th>Quantity</th>
                                    @if(request()->type == 1)
                                        <th>Previous Price Dated</th>
                                        <th>Previous Price</th>
                                    @endif
                                    <th>New Price</th>
                                   @if(request()->type == 1)
                                     <th>Updated By</th>
                                   @endif
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach($vehicles  as  $vehicle)

                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ \Carbon\Carbon::parse($vehicle->updated_at)->format('d M Y') }}</td>

                                        <td>{{ $vehicle->interior->name ?? ''  }} </td>
                                        <td>{{ $vehicle->exterior->name ?? ''}} </td>
                                        <td>{{ $vehicle->count }}</td>
                                        @if(request()->type == 1)
                                            <td>{{ $vehicle->old_price_dated }}</td>
                                            <td>{{ $vehicle->old_price }}</td>
                                        @endif
                                        <td><input type="number" class="prices" readonly  name="prices[]" min="0" value="{{$vehicle->price}}"> </td>
                                        @if(request()->type == 1)
                                            <td>{{ $vehicle->updated_by }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </form>
            @if(request()->type == 1)
                <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Variant Price Histories</h4>
                </div>
                <div class="card-body">
                <div class="table-responsive" >
                    <table id="vehicle-price-histories-table" class="table table-striped table-editable table-edits table table-condensed">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Updated Date</th>
                            <th>Interior</th>
                            <th>Exterior</th>
                            <th>Old Price</th>
                            <th>New Price</th>
                            <th>Updated By</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach($variantPriceHistories as $value => $variantPriceHistory)
                            <tr>
                                <td>{{ ++$i  }}</td>
                                <td>{{ \Carbon\Carbon::parse($variantPriceHistory->updated_at)->format('d M Y') }}</td>
                                <td>{{ $variantPriceHistory->availableColour->interior->name ?? ''  }} </td>
                                <td>{{ $variantPriceHistory->availableColour->exterior->name ?? ''}} </td>
                                <td>{{ $variantPriceHistory->old_price }}</td>
                                <td>{{$variantPriceHistory->new_price}} </td>
                                <td>{{ $variantPriceHistory->user->name ?? '' }}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
            @endif
    </div>
@endsection
@push('scripts')
    <script>
        $('.enable-price-filed').click(function() {
            $(this).hide();
            $('.update-prices').attr('hidden',false);
            $('.prices').attr('readonly',false);

        })
    </script>
@endpush

