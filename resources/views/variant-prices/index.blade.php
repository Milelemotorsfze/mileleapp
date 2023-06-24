@extends('layouts.table')
@section('content')
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
                    <div class="card-body">
                        <div class="table-responsive" >
                        <table id="Variant-without-price-table" class="table table-striped table-editable table-edits table table-condensed" style="">
                    <thead class="bg-soft-secondary">
                    <tr>
                        <th>S.NO</th>
                        <th>Variant</th>
                        <th>Interior Colour</th>
                        <th>Exterior Colour</th>
                        <th>Price</th>
                        <th>Price Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <div hidden>{{$i=0;}}
                    </div>
                    @foreach ($variantWithPrices as $key => $variantWithPrice)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $variantWithPrice->variant->name }}</td>
                            <td>{{ $variantWithPrice->interior->name ?? '' }}</td>
                            <td>{{ $variantWithPrice->exterior->name ?? '' }}</td>
                            <td>{{ $variantWithPrice->price ?? '' }}</td>
                            <td>
                                <a href="{{ route('variant-prices.edit',$variantWithPrice->varaints_id) }}">
                                    <button type="button" class="btn btn-primary btn-sm "><i class="fa fa-dollar-sign"></i></button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade " id="all">
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive" >
                            <table id="Variant-without-price-table" class="table table-striped table-editable table-edits table table-condensed" style="">
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.NO</th>
                                    <th>Variant</th>
                                    <th>Interior Colour</th>
                                    <th>Exterior Colour</th>
                                    <th>Price Update</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                </div>
                                @foreach ($variantWithoutPrices as $key => $variantWithoutPrice)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $variantWithoutPrice->variant->name }}</td>
                                        <td>{{ $variantWithoutPrice->interior->name ?? '' }}</td>
                                        <td>{{ $variantWithoutPrice->exterior->name ?? '' }}</td>
                                        <td>
                                            <a href="{{ route('variant-prices.edit', $variantWithoutPrice->id) }}">
                                                <button type="button" class="btn btn-primary btn-sm "><i class="fa fa-dollar-sign"></i></button>
                                            </a>
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
{{--    @endcan--}}
@endsection


















