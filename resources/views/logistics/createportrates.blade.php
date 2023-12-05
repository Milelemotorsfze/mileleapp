@extends('layouts.main')
@section('content')
<style>
        /* Add any additional styling here */
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Create Shipping Rate: {{$shippingmedium->name}} - {{$shippingmedium->code}}</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <form action="{{ route('Shipping.storeportrates') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                            <div class="col-lg-4 col-md-6">
                    <label for="from_port" class="form-label">From Port</label>
                    <select name="from_port" class="form-control" id="from_port">
                    <option value="" disabled selected>Select Category</option>
                    @foreach($ports as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                    </select>
                </div>
                <input type="hidden" name="id" value="{{$shippingmedium->id}}" />
                <div class="col-lg-4 col-md-6">
                    <label for="to_port" class="form-label">To Port</label>
                    <select name="to_port" class="form-control" id="to_port">
                    <option value="" disabled selected>Select Category</option>
                    @foreach($ports as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <br><br>
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
            </div>
        </form>
    </div>
    <br>
</div>
@endsection
@push('scripts')
<script>
        $(document).ready(function() {
            $('#to_port').select2();
            $('#from_port').select2();
        });
    </script>
@endpush