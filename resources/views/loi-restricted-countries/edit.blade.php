@extends('layouts.main')
@section('content')
    @can('loi-restricted-country-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('loi-restricted-country-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit LOI Restricted Countries</h4>
                <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                <form id="form-create" action="{{ route('loi-restricted-countries.update', $LOIRestrictedCountry->id) }}" method="POST" >
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Country </label>
                                    <select class="form-control widthinput" multiple name="country_id" id="country" autofocus>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ $country->id == $LOIRestrictedCountry->country_id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Comment </label>
                                    <textarea cols="25" rows="5" class="form-control" name="comment"> {{ old('comment', $LOIRestrictedCountry->comment) }} </textarea>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary ">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('#country').select2({
            placeholder : 'Select Country',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $("#form-create").validate({
            ignore: [],
            rules: {
                brand_name: {
                    required: true,
                    maxlength:255
                },
            },
        });
    </script>
@endpush

