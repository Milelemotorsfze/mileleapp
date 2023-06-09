@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Vehicle Picture</h4>
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
        <form id="form-update" action="{{ route('vehicle-pictures.update', $vehiclePicture->id) }}" method="POST" >
            @method('PUT')
            @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label"> VIN</label>
                            <select class="form-control" autofocus name="vin" id="vin">
                                <option></option>
                                @foreach($vins as $vin)
                                    <option value="{{ $vin->id }}" {{ $vehiclePicture->vehicle_id == $vin->id ? 'selected' : '' }} >{{ $vin->vin }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Vehicle Detail</label>
                            <input type="text" value="{{ $vehiclePicture->vehicle->variant->detail ?? '' }}" class="form-control" readonly >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">GRN</label>
                            <input type="text" value="{{ old('GRN_link', $vehiclePicture->GRN_link) }}" name="GRN_link" class="form-control" placeholder="GRN">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">GDN</label>
                            <input type="text" value="{{ old('GDN_link', $vehiclePicture->GDN_link) }}" name="GDN_link" class="form-control" placeholder="GDN">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Modification Link</label>
                            <input type="text" value="{{ old('modification_link', $vehiclePicture->modification_link) }}" name="modification_link" class="form-control"
                                   placeholder="Modification Link">
                        </div>
                    </div>
                    </br>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-dark " >Submit</button>
                    </div>
                </div>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#vin').select2({
            placeholder: 'Select VIN'
        })
        $("#form-update").validate({
            ignore: [],
            rules: {
                vin: {
                    required: true,
                },
                modification_link:{
                    url:true
                },
                GDN_link:{
                    url:true
                },
                GRN_link:{
                    url:true
                }
            }
        });
    </script>
@endpush

