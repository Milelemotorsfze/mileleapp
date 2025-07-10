@extends('layouts.table')

@section('content')

<div class="card-header">
    <h4 class="card-title">Add New Country</h4>
    <a class="btn btn-sm btn-info float-end" href="{{ route('dm-customers.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <div class="alert alert-danger">
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
    <form action="{{ route('countries.store') }}" method="POST">
        @csrf

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label for="name" class="form-label">Country Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Country Name" required>
        </div>

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label for="nationality" class="form-label">Nationality</label>
            <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality') }}" placeholder="Enter Nationality Value">
        </div>

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label for="iso_3166_code" class="form-label">ISO 3166 Code</label>
            <input type="text" class="form-control" id="iso_3166_code" name="iso_3166_code" value="{{ old('iso_3166_code') }}" placeholder="Enter ISO-3166 Code">
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_african_country" value="0">
            <input type="checkbox" class="form-check-input" id="is_african_country" name="is_african_country" value="1" {{ old('is_african_country', $country->is_african_country ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_african_country">Is African Country?</label>
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>
        <!-- <a href="{{ route('countries.index') }}" class="btn btn-secondary">Cancel</a> -->
    </form>
</div>
@endsection