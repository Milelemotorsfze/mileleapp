@extends('layouts.main')
@section('content')
<div class="card-header">
    <h4 class="card-title">Lead ID : {{$calls->id}}</h4>
</div>
<div class="row p-3">
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
<div class="card " style="min-height: 350px;">
<div class="card-header align-items-center ">
                            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Prospecting</h4>
</div>
        @foreach($prospecting as $prospect)
            <p>{{$prospect->salesnotes}} - {{$prospect->date}}</p>
        @endforeach
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
<div class="card " style="min-height: 350px;">
<div class="card-header align-items-center ">
                            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Demand</h4>
</div>
        @foreach($prospecting as $prospect)
            <p>{{$prospect->salesnotes}} - {{$prospect->date}}</p>
        @endforeach
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
<div class="card " style="min-height: 350px;">
<div class="card-header align-items-center ">
                            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Qoutation</h4>
</div>
        @foreach($quotation as $quotation)
            <p>{{$quotation->sales_notes}}</p>
        @endforeach
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
<div class="card " style="min-height: 350px;">
<div class="card-header align-items-center ">
                            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Negotiation</h4>
</div>
        @foreach($quotation as $quotation)
            <p>{{$quotation->sales_notes}}</p>
        @endforeach
    </div>
</div>
<div class="col-xl-6 col-sm-12 col-md-12 col-xxl-6">
<div class="card " style="min-height: 350px;">
<div class="card-header align-items-center ">
                            <h4 class="card-title mb-0 flex-grow-1 text-center mb-3">Sales Order</h4>
</div>
        @foreach($quotation as $quotation)
            <p>{{$quotation->sales_notes}}</p>
        @endforeach
    </div>
</div>
@endsection
@push('scripts')
@endpush