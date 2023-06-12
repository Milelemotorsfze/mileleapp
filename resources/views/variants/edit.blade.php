@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Variant</h4>
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
        <form id="form-update" action="{{ route('variants.update', $variant->id) }}" method="POST" >
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Name</label>
                        <input type="text" value="{{ old('name', $variant->name) }}" name="name" class="form-control " placeholder="Name">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label"> Brand</label>
                        <select class="form-control" autofocus name="brands_id" id="brand">
                            <option></option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"  {{ $variant->brands_id == $brand->id ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Model</label>
                        <select class="form-control" autofocus name="master_model_lines_id" id="model">
                            <option></option>
                            @foreach($masterModelLines as $masterModelLine)
                                <option value="{{ $masterModelLine->id }}" {{ $variant->master_model_lines_id == $masterModelLine->id ? 'selected' : '' }}>
                                    {{ $masterModelLine->model_line }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Fuel Type</label>
                        <input type="text" value="{{ old('fuel_type', $variant->fuel_type) }}" name="fuel_type" class="form-control " placeholder="Fuel Type">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Gear Box</label>
                        <input type="text" value="{{ old('gearbox', $variant->gearbox) }}" name="gearbox" class="form-control "
                               placeholder="Gear Box">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">My</label>
                        <input type="text" value="{{ old('my', $variant->my) }}" name="my" class="form-control "
                               placeholder="My">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Detail</label>
                        <input type="text" value="{{ old('detail', $variant->detail) }}" name="detail" class="form-control "
                               placeholder="Detail">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Seat</label>
                        <input type="text" value="{{ old('seat', $variant->seat) }}" name="seat" class="form-control "
                               placeholder="Seat">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Upholestry</label>
                        <input type="text" value="{{ old('upholestry', $variant->upholestry) }}" name="upholestry" class="form-control "
                               placeholder="Upholestry">
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
        $("#form-update").validate({
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

