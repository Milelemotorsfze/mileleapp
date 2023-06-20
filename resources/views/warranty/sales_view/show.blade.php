@extends('layouts.table')
@section('content')
    <style>

        p{
            margin-bottom: 2px;
        }

    </style>
    @can('warranty-list')
        <div class="card-header">
            <h4 class="card-title">Warranty Info</h4>
        </div>
        <div class="card-body">
            <div class="row m-2">
                @foreach($warrantyBrands as $warrantyBrand)
                <div class="col-lg-2 col-xxl-2 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center bg-soft-secondary p-3 bg-row-{{$warrantyBrand->premium->PolicyName->id}}">
                                <h4 class="text-black-50 " >{{$warrantyBrand->premium->PolicyName->name}}</h4>
                                <i class="fa fa-wave-square"></i>
                                <p>Claim Limit : {{ $warrantyBrand->premium->claim_limit_in_aed }} AED</p>
                                <p class=" mb-2">Premium : @if($warrantyBrand->selling_price) {{ $warrantyBrand->selling_price }} AED @else Not Available @endif</p>
                                <p>Extended Warranty Period : {{$warrantyBrand->premium->extended_warranty_period}} Months</p>
                                <p>Eligibility : {{ $warrantyBrand->premium->eligibility_year }} Year / {{ $warrantyBrand->premium->eligibility_milage }} KM </p>
                                <p>Extended KM : @if($warrantyBrand->premium->is_open_milage == true) Open Milage @endif  {{ $warrantyBrand->premium->extended_warranty_milage }} </p>
                                <p>Vehicle Category :
                                    @if($warrantyBrand->premium->vehicle_category1 == 'non_electric')
                                        Non Electric /
                                    @elseif($warrantyBrand->premium->vehicle_category1 == 'electric')
                                        Electric /
                                    @endif
                                    @if($warrantyBrand->premium->vehicle_category2 == 'normal_and_premium')
                                        Normal And Premium
                                    @elseif($warrantyBrand->premium->vehicle_category2 == 'lux_sport_exotic')
                                        Lux Sport Exotic
                                    @endif</p>
                            </div>

                            <div class="bg-light p-3">
                                <h6 class="text-center text-black-50"> BRANDS</h6>
                                <div class="row justify-content-center border"  >
                                    @foreach($warrantyBrand->policy_brands as $index => $brand)
{{--                                        @if($index <= 2)--}}
                                            <div class="list-group-flush" style="margin-left: 50px" ><i class="fa fa-check"> </i>  {{ $brand->brand_name }} </div>
{{--                                        @else--}}
{{--                                            @if($warrantyBrand->policy_brands->count() > 3)--}}
{{--                                            <button class="btn bg-row-{{$warrantyBrand->premium->PolicyName->id}} btn-sm text-white w-25"--}}
{{--                                                    data-bs-toggle="collapse" href="#collapseExample-{{$warrantyBrand->id}}"  aria-bs-controls="collapseExample"> More</button>--}}
{{--                                                <div class="collapse" id="collapseExample-{{$warrantyBrand->id}}">--}}
{{--                                                    <div class="card-body bg-light">--}}
{{--                                                        <div class="list-group-flush"  ><i class="fa fa-check"> </i>--}}
{{--                                                            {{ $brand->brand_name }} </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                        @endif--}}
                                    @endforeach
                                </div>
{{--                                <div class="row">--}}
{{--                                @foreach($warrantyBrand->premium->PolicyName->warrantyPolicyCoverageParts as $key => $coveragePart)--}}
{{--                                        @if($key <= 2)--}}
{{--                                            <span class="list-group-flush" ><i class="fa fa-shield-alt"> </i>{{ $coveragePart->warrantyCoveragePart->name }}</span>--}}
{{--                                        @else--}}
{{--                                            @if($warrantyBrand->premium->PolicyName->warrantyPolicyCoverageParts->count() > 3)--}}
{{--                                                <button class="btn bg-row-{{$warrantyBrand->premium->PolicyName->id}} btn-sm text-white w-25"--}}
{{--                                                        data-bs-toggle="collapse" href="#collapseCoverage-{{$warrantyBrand->id}}"  aria-bs-controls="collapseExample"> More</button>--}}
{{--                                                <div class="collapse" id="collapseCoverage-{{$warrantyBrand->id}}">--}}
{{--                                                    <div class="card-body bg-light">--}}
{{--                                                        <span class="list-group-flush" ><i class="fa fa-shield-alt"> </i>{{ $coveragePart->warrantyCoveragePart->name }}</span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                        @endif--}}
{{--                                @endforeach--}}
{{--                                </div>--}}
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
@endcan
@endsection
