@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title"> Warranty Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    @can('warranty-view')
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Policy Name</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->PolicyName->name}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label">Vehicle Category1</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{ $premium->vehicle_category1 }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Vehicle Category2</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span >{{$premium->vehicle_category2}}</span>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label">Eligibility Years</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{ $premium->eligibility_year }} Years</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Eligibility Mileage</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->eligibility_milage}} KM</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Is Open Mileage</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{ $premium->is_open_milage }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Extended Warranty Period</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->extended_warranty_milage}} </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Claim Limit </label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->claim_limit_in_aed}} </span>
                </div>
            </div>
        </div>
    @endcan
@endsection


