@extends('layouts.main')
@section('content')

@can('master-brand-edit')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-edit');
@endphp
@if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Edit Brand</h4>
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
        <form id="form-update" action="{{ route('brands.update',$brand->id) }}" method="POST" >
            @csrf
            @method('PUT')
            <div class="row">
                <div class="row ">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label"> Name</label>
                            <input type="text" class="form-control" name="brand_name" value="{{ old('brand_name',$brand->brand_name) }}">
                        </div>
                    </div>
                    </br>
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
        $("#form-update").validate({
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
