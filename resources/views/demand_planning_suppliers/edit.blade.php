@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New Supplier</h4>
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
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('message') }}
            </div>
        @endif
        <form id="form-create" action="{{ route('demand-planning-suppliers.update', $supplier->id) }}" method="POST" >
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $supplier->supplier) }}" autofocus
                               class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <span role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                </br>
                <div class="col-lg-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-dark ">Submit</button>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        $("#form-create").validate({
            rules: {
                name: {
                    required: true,
                },
            },
        });
    </script>
@endpush

