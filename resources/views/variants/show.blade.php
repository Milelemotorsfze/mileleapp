@extends('layouts.main')
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
    <div class="card-header">
        <h4 class="card-title"> Variant Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Name</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->name }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label">Brand</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->brand->brand_name ?? '' }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Model Line</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span >{{$variant->master_model_lines->model_line}}</span>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label">Fuel Type</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->fuel_type }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Gear Box</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->gearbox }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> My</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->my }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Seat</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->seat }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Detail</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->detail }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Upholestry</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $variant->upholestry }}</span>
            </div>
        </div>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection


