@extends('layouts.main')
@section('content')
    <style>
        .error
        {
            color: #FF0000;
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">Edit Master Addon</h4>
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
        @if (Session::has('message'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert"> x </button>
                {{ Session::get('message') }}
            </div>
        @endif
            <form id="form-update" method="POST" enctype="multipart/form-data" action="{{ route('master-addons.update', $addon->id) }}">
                @csrf
                @method('PUT')
                <div class="row ">
                    <p><span style="float:right;" class="error">* Required Field</span></p>
                    <input type="hidden" id="addon_type" value="{{$addon->addon_type}}">
                    <div class="col-xxl-9 col-lg-6 col-md-12">
                        @if($addon->addon_type != 'K')
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-addon-create']);
                            @endphp
                            @if ($hasPermission)
                                <div class="row">
                                    <div class="col-xxl-2 col-lg-6 col-md-12">
                                        <span class="error">* </span>
                                        <label for="addon_type" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $addon->name) }}">
                                    </div>
                                </div>
                           @endif
                        @else
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-kit-edit');
                            @endphp
                            @if ($hasPermission)
                                <div class="row">
                                    <div class="col-xxl-2 col-lg-6 col-md-12">
                                        <span class="error">* </span>
                                        <label for="kit_km" class="col-form-label text-md-end">{{ __('Kit KiloMeter') }}</label>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <input type="number" name="kit_km" class="form-control" value="{{ old('kit_km', $addon->kit_km) }}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xxl-2 col-lg-6 col-md-12">
                                        <span class="error">* </span>
                                        <label for="kit_year" class="col-form-label text-md-end">{{ __('Kit Year') }}</label>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <input type="number" name="kit_year" class="form-control" value="{{ old('kit_year', $addon->kit_year) }}">
                                    </div>
                                </div>
                            @endif
                       @endif
                    </div>
                    <div class="col-md-12 mt-3 ">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                    </div>
                </div>
            </form>
    </div>
@endsection
@push('scripts')
    <script>
        $("#form-update").validate({
            ignore: [],
            rules: {
                name: {
                    required: function(element){
                        return $("#addon_type").val() != "K";
                    }
                },
                kit_km: {
                    required: function(element){
                        return $("#addon_type").val() == "K";
                    }
                },
                kit_year: {
                    required: function(element){
                        return $("#addon_type").val() == "K";
                    }
                },
            },
        });

    </script>
@endpush

