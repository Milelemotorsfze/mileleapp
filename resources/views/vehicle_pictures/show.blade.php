@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title"> Vehicle Picture Details</h4>
    </div>
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
            <label for="choices-single-default" class="form-label"> GRN</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
            <span >{{$vehiclePicture->GRN_link}}</span>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label">GDN</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{ $vehiclePicture->GDN_link }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="choices-single-default" class="form-label"> Modification Link</label>
            </div>
            <div class="col-lg-6 col-md-9 col-sm-12">
                <span>{{$vehiclePicture->modification_link}}</span>
            </div>
        </div>
    </div>
@endsection


