@extends('layouts.main')
@section('content')
<style>
        /* Add any additional styling here */
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Create New Ports</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <form action="{{ route('ports.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                            <div class="col-lg-4 col-md-6">
                    <label for="from_port" class="form-label">Name</label>
                    <input type = "text" name = "port_name" class = "form-control" />
                </div>
                <div class="col-lg-4 col-md-6">
                <label for="country" class="form-label">Country</label>
                <select name="country[]" class="form-control" id="country" multiple>
                    <option value="" disabled>Select Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
            </div>
            <br><br>
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
            </div>
        </form>
    </div>
    <br>
</div>
@endsection
@push('scripts')
<script>
        $(document).ready(function() {
            $('#country').select2();
        });
    </script>
@endpush