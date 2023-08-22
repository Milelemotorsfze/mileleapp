@extends('layouts.main')
@section('content')
    {{--@php--}}
    {{--    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-brand-create');--}}
    {{--@endphp--}}
    {{--@if ($hasPermission)--}}
    <div class="card-header">
        <h4 class="card-title">Add Module</h4>
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
        <form id="form-create" action="{{ route('modules.store') }}" method="POST" >
            @csrf
            <div class="row">
                <div class="row ">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label"> Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        </div>
                    </div>
                    </br>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary ">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
    {{--@endif--}}
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
            },
        });
    </script>
@endpush

