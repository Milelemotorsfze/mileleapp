@extends('layouts.table')

@section('content')

<div class="card-header">
    <h2 class="card-title mb-0">Countries</h2>
    <a class="btn btn-sm btn-info float-end" href="{{ route('dm-customers.index') }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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
    <form method="POST" action="{{ route('countries.update', $country->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label>Name</label>
            <input type="text" name="name" value="{{ $country->name }}" class="form-control" required>
        </div>

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label>Nationality</label>
            <input type="text" name="nationality" value="{{ $country->nationality }}" class="form-control">
        </div>

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label>ISO 3166 Code</label>
            <input type="text" name="iso_3166_code" value="{{ $country->iso_3166_code }}" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_african_country" value="0">
            <input type="checkbox" class="form-check-input" id="is_african_country" name="is_african_country" value="1" {{ old('is_african_country', $country->is_african_country ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_african_country">Is African Country?</label>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('countries.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection