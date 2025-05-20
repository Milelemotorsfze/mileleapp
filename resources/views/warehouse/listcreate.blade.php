@extends('layouts.main')

<style>
    .error {
        color: red;
    }
</style>

@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New Master Warehouse</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <div class="row">
            <p><span style="float:right;" class="error">* Required Field</span></p>
			</div>
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
        <form id="form-create" action="{{ route('warehouse.store') }}" method="POST">
            @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="mb-3">
                        <span class="error">* </span>
                            <label for="choices-single-default" class="form-label">Name</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control " placeholder="Warehouse Name" required>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-center justify-content-center">
                        <div class="mb-3">
                            <label class="form-label d-block"><span class="error">* </span>Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusYes" value="1" checked>
                                <label class="form-check-label" for="statusYes">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusNo" value="0">
                                <label class="form-check-label" for="statusNo">In-Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
@endpush