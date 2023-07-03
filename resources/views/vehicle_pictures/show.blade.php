@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title"> Vehicle Picture Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    @can('vehicles-picture-view')
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> VIN</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{$vehiclePicture->vehicle->vin}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label">Variant Detail</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $vehiclePicture->vehicle->variant->detail ?? '' }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
            <label for="choices-single-default" class="form-label"> Vehicle Picture Link</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
            <span> <a href="{{$vehiclePicture->vehicle_picture_link }}" target="_blank">{{ $vehiclePicture->vehicle_picture_link }}</a></span>
            </div>
        </div>
    </div>
    @endcan
@endsection


