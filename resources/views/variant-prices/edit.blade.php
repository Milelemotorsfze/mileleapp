@extends('layouts.main')
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
        <form action="{{ route('variant-prices.update', $vehicle->id) }}" method="POST" >
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label"> Variant</label>
                        <input type="text" class="form-control" readonly value="{{ $vehicle->variant->name }}" >

                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">Interior Colour</label>
                        <input type="text" class="form-control" name="interior_colour" readonly value="{{ $vehicle->interior->name ?? '' }}" >

                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">Exterior Colour</label>
                        <input type="text" class="form-control" name="exterior_colour" readonly value="{{ $vehicle->exterior->name ?? '' }}">

                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">Price</label>
                        <input type="number" class="form-control" name="price"  value="{{ $vehicle->exterior->name ?? '' }}">

                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary " >Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection


