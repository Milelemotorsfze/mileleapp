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
                            <label for="choices-single-default" class="form-label">GRN</label>
                            <input type="text" value="{{ old('GRN_link', $vehiclePicture->GRN_link) }}" name="GRN_link"
                                   class="form-control mygroup" placeholder="GRN">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">GDN</label>
                            <input type="text" value="{{ old('GDN_link', $vehiclePicture->GDN_link) }}" name="GDN_link"
                                   class="form-control mygroup" placeholder="GDN">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Modification Link</label>
                            <input type="text" value="{{ old('modification_link', $vehiclePicture->modification_link) }}" name="modification_link"
                                   class="form-control mygroup" placeholder="Modification Link">
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
                modification_link:{
                    url:true,
                    require_from_group: [1, '.mygroup']
                },
                GDN_link:{
                    url:true,
                    require_from_group: [1, '.mygroup']
                },
                GRN_link:{
                    url:true,
                    require_from_group: [1, '.mygroup']
                },
                groups: {
                    mygroup: "GDN_link GRN_link modification_link"
                },
            }
        });
    </script>
@endpush

