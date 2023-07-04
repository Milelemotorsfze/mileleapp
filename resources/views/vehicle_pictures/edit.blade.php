@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Vehicle Picture</h4>
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
                            <label for="choices-single-default" class="form-label">Variant Detail</label>
                            <input type="text" value="{{ $vehiclePicture->vehicle->variant->detail ?? '' }}" id="variant-detail"
                                   class="form-control" readonly >
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Vehicle Picture Link</label>
                            <input type="text" value="{{ old('vehicle_picture_link', $vehiclePicture->vehicle_picture_link) }}" name="vehicle_picture_link"
                                   class="form-control " placeholder="Vehicle Picture Link">
                        </div>
                    </div>
                    </br>
                    @can('vehicles-picture-edit')
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-dark" >Submit</button>
                    </div>
                    @endcan
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
        $('#vin').on('change',function(){
            $('#vin-error').remove();
            var vehicle_id = $('#vin').val();
            let url = '{{ route('vehicle-pictures.variant-details') }}'
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    id: vehicle_id,
                },
                success:function (response) {
                    $('#variant-detail').val(response.data);
                }
            });
        })
        $("#form-update").validate({
            ignore: [],
            rules: {
                vin: {
                    required: true,
                },
                vehicle_picture_link:{
                    url:true,
                    required:true
                },
            }
        });
    </script>
@endpush

