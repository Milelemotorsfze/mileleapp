@extends('layouts.table')
@section('content')
    <style>

        p{
            margin-bottom: 2px;
        }

    </style>
    @can('warranty-sales-view')
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-sales-view']);
    @endphp
    @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">Warranty Info</h4>
            <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="card-body">
            <div class="row m-2" >
                @foreach($warrantyBrands as $warrantyBrand)
                <div class="col-lg-2 col-xxl-2 col-md-6 col-sm-12" >
                    <div class="card" style="min-height: 635px;">
                        <div class="card-body">
                            <div class="text-center bg-soft-secondary p-3 bg-row-{{$warrantyBrand->premium->PolicyName->id}}">
                                <h4 class="text-black-50 " >{{$warrantyBrand->premium->PolicyName->name}}</h4>
                                <p>Claim Limit : {{ $warrantyBrand->premium->claim_limit_in_aed }} AED</p>
                                <p class=" mb-2">Premium : @if($warrantyBrand->selling_price) {{ $warrantyBrand->selling_price }} AED @else Not Available @endif</p>
                                <p>Warranty Period : {{$warrantyBrand->premium->extended_warranty_period}} Months</p>
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

                            <div class="p-3 pb-0">
                                <h6 class="text-center "> BRANDS</h6>
                                <div class="row justify-content-center"  >
                                    @foreach($warrantyBrand->policy_brands as $index => $brand)
                                        @if($index <= 2)
                                            <div class="list-group-flush pb-2 show-less-{{$warrantyBrand->id}}"><i class="fa fa-check"> </i>&nbsp; &nbsp;  {{ $brand->brand_name }} </div>
                                        @endif
                                    @endforeach
                                    @if($warrantyBrand->policy_brands->count() > 3)
                                            @foreach($warrantyBrand->policy_brands as $index => $brand)
                                                <div class="collapse" id="collapseExample-{{$warrantyBrand->id}}">
                                                        <div class="list-group-flush pb-1"  ><i class="fa fa-check"> </i>&nbsp; &nbsp;
                                                            {{ $brand->brand_name }} </div>
                                                </div>
                                            @endforeach
                                        <button class="btn btn-primary btn-sm text-white w-50 collapse-button mb-1" value="More" data-bs-toggle="collapse" href="#collapseExample-{{$warrantyBrand->id}}"
                                                aria-bs-controls="collapseExample-{{$warrantyBrand->id}}" data-id="{{$warrantyBrand->id}}"> View More</button>

                                    @endif
                                </div>
                                <div class="row justify-content-center">
                                    <h6 class="text-center mt-2"> COVERAGES</h6>
                                    @foreach($warrantyBrand->premium->PolicyName->warrantyPolicyCoverageParts as $key => $coveragePart)
                                        @if($key <= 2)
                                            <div class="list-group-flush view-more-{{$warrantyBrand->id}}" ><i class="fa fa-check"> </i>&nbsp;
                                                &nbsp;{{ $coveragePart->warrantyCoveragePart->name }}</div>
                                        @endif
                                    @endforeach
                                    @if($warrantyBrand->premium->PolicyName->warrantyPolicyCoverageParts->count() > 3)
                                        @foreach($warrantyBrand->premium->PolicyName->warrantyPolicyCoverageParts as $key => $coveragePart)
                                            <div class="collapse" id="collapseCoverage-{{$warrantyBrand->id}}">
                                                    <span class="list-group-flush" ><i class="fa fa-check"> </i>&nbsp; &nbsp;{{ $coveragePart->warrantyCoveragePart->name }}</span>
                                            </div>
                                        @endforeach
                                        <button class="btn btn-primary btn-sm text-white w-50 view-more-button mt-2"
                                                data-bs-toggle="collapse" href="#collapseCoverage-{{$warrantyBrand->id}}" data-id="{{$warrantyBrand->id}}"
                                                aria-bs-controls="collapseCoverage">View More</button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
@endif
@endcan
@endsection
@push('scripts')
    <script>
        $(".collapse-button").click(function(){
            var $this = $(this);
            var id = $(this).attr('data-id');

            $this.toggleClass('collapse-button');
            if($this.hasClass('collapse-button')){
                $this.text('View More');
                $('.show-less-'+id).show();
            } else {
                $this.text('View Less');
                $('.show-less-'+id).hide();
            }
        });
        $(".view-more-button").click(function(){
            var $this = $(this);
            var id = $(this).attr('data-id');

            $this.toggleClass('view-more-button');
            if($this.hasClass('view-more-button')){
                $this.text('View More');
                $('.view-more-'+id).show();
            } else {
                $this.text('View Less');
                $('.view-more-'+id).hide();
            }
        });
    </script>
@endpush
