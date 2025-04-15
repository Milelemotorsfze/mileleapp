@extends('layouts.main')
@section('content')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-model-description');
@endphp
@if ($hasPermission)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Master Model Description</h4>
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="card-body">
            <div id="flashMessage"></div>
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
            <form action="{{ route('banks.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                <div class="col-lg-4 col-md-6 mb-3">
                    <label for="choices-single-default" class="form-label">Steering</label>
                                    <select class="form-control" autofocus name="steering" id="steering">
                                        <option value="LHD" {{ old('steering') == 'LHD' ? 'selected' : '' }}>LHD</option>
                                        <option value="RHD" {{ old('steering') == 'RHD' ? 'selected' : '' }}>RHD</option>
                                    </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                    <label for="choices-single-default" class="form-label">Brand</label>
                                    <select class="form-control" autofocus name="brands_id" id="brand">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brands_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->brand_name }}
                                            </option>
                                        @endforeach
                                    </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                    <label for="choices-single-default" class="form-label">Model Line</label>
                                    <select class="form-control" autofocus name="master_model_lines_id" id="model">
                                    <option value="" disabled selected>Select a Model Line</option>
                                        @foreach($masterModelLines as $masterModelLine)
                                            <option value="{{ $masterModelLine->id }}" {{ old('master_model_lines_id') == $masterModelLine->id ? 'selected' : '' }}>
                                                {{ $masterModelLine->model_line }}
                                            </option>
                                        @endforeach
                                                    </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="choices-single-default" class="form-label">Engine</label>
                                    <select class="form-control" autofocus name="engine" id="engine">
                                        <option value="" {{ old('engine') == '' ? 'selected' : '' }}>Please Select The Engine Capacity</option>
                                        <option value="0.8" {{ old('engine') == '0.8' ? 'selected' : '' }}>0.8</option>
                                        <option value="1.0" {{ old('engine') == '1.0' ? 'selected' : '' }}>1.0</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.2</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.3</option>
                                        <option value="1.4" {{ old('engine') == '1.4' ? 'selected' : '' }}>1.4</option>
                                        <option value="1.5" {{ old('engine') == '1.5' ? 'selected' : '' }}>1.5</option>
                                        <option value="1.6" {{ old('engine') == '1.6' ? 'selected' : '' }}>1.6</option>
                                        <option value="1.8" {{ old('engine') == '1.8' ? 'selected' : '' }}>1.8</option>
                                        <option value="2.0" {{ old('engine') == '2.0' ? 'selected' : '' }}>2.0</option>
                                        <option value="2.2" {{ old('engine') == '2.2' ? 'selected' : '' }}>2.2</option>
                                        <option value="2.4" {{ old('engine') == '2.4' ? 'selected' : '' }}>2.4</option>
                                        <option value="2.5" {{ old('engine') == '2.5' ? 'selected' : '' }}>2.5</option>
                                        <option value="2.7" {{ old('engine') == '2.7' ? 'selected' : '' }}>2.7</option>
                                        <option value="2.8" {{ old('engine') == '2.8' ? 'selected' : '' }}>2.8</option>
                                        <option value="3.0" {{ old('engine') == '3.0' ? 'selected' : '' }}>3.0</option>
                                        <option value="3.3" {{ old('engine') == '3.3' ? 'selected' : '' }}>3.3</option>
                                        <option value="3.4" {{ old('engine') == '3.4' ? 'selected' : '' }}>3.4</option>
                                        <option value="3.5" {{ old('engine') == '3.5' ? 'selected' : '' }}>3.5</option>
                                        <option value="3.6" {{ old('engine') == '3.6' ? 'selected' : '' }}>3.6</option>
                                        <option value="3.8" {{ old('engine') == '3.8' ? 'selected' : '' }}>3.8</option>
                                        <option value="4.0" {{ old('engine') == '4.0' ? 'selected' : '' }}>4.0</option>
                                        <option value="4.2" {{ old('engine') == '4.2' ? 'selected' : '' }}>4.2</option>
                                        <option value="4.4" {{ old('engine') == '4.4' ? 'selected' : '' }}>4.4</option>
                                        <option value="4.5" {{ old('engine') == '4.5' ? 'selected' : '' }}>4.5</option>
                                        <option value="4.6" {{ old('engine') == '4.6' ? 'selected' : '' }}>4.6</option>
                                        <option value="4.8" {{ old('engine') == '4.8' ? 'selected' : '' }}>4.8</option>
                                        <option value="5.0" {{ old('engine') == '5.0' ? 'selected' : '' }}>5.0</option>
                                        <option value="5.3" {{ old('engine') == '5.3' ? 'selected' : '' }}>5.3</option>
                                        <option value="5.5" {{ old('engine') == '5.5' ? 'selected' : '' }}>5.5</option>
                                        <option value="5.6" {{ old('engine') == '5.6' ? 'selected' : '' }}>5.6</option>
                                        <option value="5.7" {{ old('engine') == '5.7' ? 'selected' : '' }}>5.7</option>
                                        <option value="5.9" {{ old('engine') == '5.9' ? 'selected' : '' }}>5.9</option>
                                        <option value="6.0" {{ old('engine') == '6.0' ? 'selected' : '' }}>6.0</option>
                                        <option value="6.2" {{ old('engine') == '6.2' ? 'selected' : '' }}>6.2</option>
                                        <option value="6.7" {{ old('engine') == '6.7' ? 'selected' : '' }}>6.7</option>
                                    </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                                                    <select class="form-control" autofocus name="fuel_type" id="fuel">
                                                        <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                                        <option value="PH" {{ old('fuel_type') == 'PH' ? 'selected' : '' }}>PH</option>
                                                        <option value="PHEV" {{ old('fuel_type') == 'PHEV' ? 'selected' : '' }}>PHEV</option>
                                                        <option value="MHEV" {{ old('fuel_type') == 'MHEV' ? 'selected' : '' }}>MHEV</option>
                                                        <option value="EV" {{ old('fuel_type') == 'EV' ? 'selected' : '' }}>EV</option>
                                                    </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="choices-single-default" class="form-label">Gear</label>
                                                    <select class="form-control" autofocus name="gearbox" id="gear">
                                                        <option value="AT" {{ old('gearbox') == 'AT' ? 'selected' : '' }}>AT</option>
                                                        <option value="MT" {{ old('gearbox') == 'MT' ? 'selected' : '' }}>MT</option>
                                                    </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="choices-single-default" class="form-label">Window Type</label>
                                                    <select class="form-control" autofocus name="window_type" id="window_type">
                                                        <option value="P.Window" {{ old('gearbox') == 'P.Window' ? 'selected' : '' }}>P.Window</option>
                                                        <option value="MT" {{ old('gearbox') == 'MT' ? 'selected' : '' }}>MT</option>
                                                    </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                    <label for="choices-single-default" class="form-label">Drive Train</label>
                                    <select class="form-control" autofocus name="drive_train" id="drive_train">
                                    <option value="4X2" {{ old('geadrive_trainrbox') == '4X2' ? 'selected' : '' }}>4X2</option>
                                    <option value="4X4" {{ old('geadrive_trainrbox') == '4X4' ? 'selected' : '' }}>4X4</option>
                                        <option value="AWD" {{ old('drive_train') == 'AWD' ? 'selected' : '' }}>AWD</option>
                                        <option value="4WD" {{ old('geadrive_trainrbox') == '4WD' ? 'selected' : '' }}>4WD</option>
                                        <option value="FWD" {{ old('geadrive_trainrbox') == 'FWD' ? 'selected' : '' }}>FWD</option>
                                        <option value="RWD" {{ old('geadrive_trainrbox') == 'RWD' ? 'selected' : '' }}>RWD</option>

                                    </select>
                                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12 text-center">
                        <input type="submit" name="submit" value="Submit" class="btn btn-success" />
                    </div>
                </div>
            </form>
        </div>
    </div>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
@endpush