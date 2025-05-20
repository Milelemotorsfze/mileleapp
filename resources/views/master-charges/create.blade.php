@extends('layouts.main')
@section('content')

@can('create-master-charges')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-charges');
@endphp
@if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Create Master Charges</h4>
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
        <form id="form-create" action="{{ route('master-charges.store') }}" method="POST" >
            @csrf
            <div class="row">
                <div class="row ">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label"> Type</label>
                            <select class="form-control" name="type" >
                            <option value="shipping_cost">Shipping Cost</option>
                            <option value='documentation'>Documentation</option>
                            <option value='documentation_on_purchase'>Documentation On Purchase</option>
                            <option value='others'>Others</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Addon Code</label>
                            <input type="text"  class="form-control" placeholder="Addon Code" name="addon_code" value="{{ old('addon_code') }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label"> Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name"  value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label"> Description</label>
                            <input type="text" class="form-control" placeholder="Enter Description" name="description" value="{{ old('description') }}">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
        $("#form-create").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    maxlength:255
                },
                addon_code: {
                    required: true,
                    maxlength:255
                },
                type: {
                    required: true,
                   
                },
            },
        });
    </script>
@endpush

