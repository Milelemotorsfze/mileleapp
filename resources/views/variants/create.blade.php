@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New Variant</h4>
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
        <form id="form-create" action="{{ route('variants.store') }}" method="POST">
            @csrf
                <div class="row">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Name</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control " placeholder="Name">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Brand</label>
                            <select class="form-control" autofocus name="brands_id" id="brand">
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brands_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->brand_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Model Line</label>
                            <select class="form-control" autofocus name="master_model_lines_id" id="model">
                                @foreach($masterModelLines as $masterModelLine)
                                    <option value="{{ $masterModelLine->id }}" {{ old('master_model_lines_id') == $masterModelLine->id ? 'selected' : '' }}>
                                        {{ $masterModelLine->model_line }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Model Description</label>
                            <input type="text" value="{{ old('model_detail') }}" name="model_detail" class="form-control "placeholder="Model Description" required>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Steering</label>
                            <select class="form-control" autofocus name="steering" id="model">
                                <option value="LHD" {{ old('steering') == 'LHD' ? 'selected' : '' }}>LHD</option>
                                <option value="RHD" {{ old('steering') == 'RHD' ? 'selected' : '' }}>RHD</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Fuel Type</label>
                            <select class="form-control" autofocus name="fuel_type" id="model">
                                <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="EV" {{ old('fuel_type') == 'EV' ? 'selected' : '' }}>EV</option>
                                <option value="Gasoline" {{ old('fuel_type') == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Gear Box</label>
                            <select class="form-control" autofocus name="gearbox" id="model">
                                <option value="Auto" {{ old('gearbox') == 'Auto' ? 'selected' : '' }}>Auto</option>
                                <option value="Manual" {{ old('gearbox') == 'Manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">MY</label>
                            @php
                            $currentYear = date("Y");
                            $years = range($currentYear + 10, $currentYear - 10);
                            $years = array_reverse($years);
                            @endphp
                            <select name="my" class="form-control">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ old('my') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Seat</label>
                            <select name="seat" class="form-control">
                                @for($i = 1; $i <= 50; $i++)
                                    <option value="{{ $i }}" {{ old('seat') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Upholstery</label>
                            <select class="form-control" autofocus name="upholestry" id="model">
                                <option value="Fabric" {{ old('upholestry') == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                <option value="Leather" {{ old('upholestry') == 'Leather' ? 'selected' : '' }}>Leather</option>
                                <option value="Fabric + Leather" {{ old('upholestry') == 'Fabric + Leather' ? 'selected' : '' }}>Fabric + Leather</option>
                                <option value="Fabric / Leather" {{ old('upholestry') == 'Fabric / Leather' ? 'selected' : '' }}>Fabric / Leather</option>
                                <option value="Vinyl" {{ old('upholestry') == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Engine Capacity</label>
                            <input type="text" value="{{ old('engine_capacity') }}" name="engine" class="form-control "placeholder="Engine Capacity" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Detail</label>
                            <input type="text" value="{{ old('detail') }}" name="detail" class="form-control "placeholder="Detail" required>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-dark">Submit</button>
                    </div>
                </div>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#brand').select2({
            placeholder: 'Select Brand'
        })
        $('#model').select2({
            placeholder: 'Select Model'
        })
        $('#brand').on('change',function() {
            $('#brand-error').remove();
        })
        $('#model').on('change',function() {
            $('#model-error').remove();
        })
        $("#form-create").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    string:true,
                    max:255
                },
                master_model_lines_id:{
                    required:true,
                },
                brands_id:{
                    required:true,
                },
            }
        });
    </script>
@endpush

