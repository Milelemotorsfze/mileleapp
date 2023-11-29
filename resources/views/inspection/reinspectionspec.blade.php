@extends('layouts.main')
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
<style>
    .button-container {
    display: flex;
    gap: 10px;
    float: right;
}
.button-containerinner {
    display: flex;
    gap: 10px;
    float: right;
}
    </style>
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<div class="card-header">
    <h4 class="card-title">
     Inspection Report
     <center><b>Vehicle Identification Number:
                    {{$vehicle->vin}}
                </b></center>
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
</div>
<div class="card-body">
    <form id="inspection-form" action="{{ route('inspection.reupdatespec', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
             @method('PUT')
            @csrf
            <h5>Trim Specifications</h5>
<br>
<div class="row">
    <div class="col-md-1">
      <p><strong>Brand</strong></p>
    </div>
    <div class="col-md-1">
      <p>{{$brandname->brand_name}}</p>
    </div>
    <div class="col-md-2">
      <p><strong>Model Line</strong></p>
    </div>
    <div class="col-md-2">
      <p>{{$model_line->model_line}}</p>
    </div>
  </div>
<hr>
<h5>Variant Specifications</h5>
<br>
<input type="hidden" name="variant_id" value="{{$vehicle->varaints_id}}" />
<div class="row">
        @foreach($filteredSpecifications as $specification)
            <div class="col-lg-2 col-md-6 col-sm-12">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">{{ $specification->name }}</label>
                    <select class="form-control" autofocus name="specification_{{ $specification->id }}">
                        {{-- Add a default option if the specification is not selected --}}
                        <option value="" disabled selected>Select an Option</option>
                        {{-- Display options --}}
                        @foreach($specification->options as $option)
                            <option value="{{ $option->id }}" {{ old('specification_' . $specification->id) == $option->id ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>  
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach
    </div>
</br>
    <div class="col-lg-12 col-md-12">
				    <input type="submit" id="submit-buttons" name="submit" value="Update" class="btn btn-success btncenter" />
			        </div>
</form>
</div>
@endsection
@push('scripts')
@endpush