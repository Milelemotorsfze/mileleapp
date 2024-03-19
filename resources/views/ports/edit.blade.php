@extends('layouts.main')
@section('content')
<style>
        /* Add any additional styling here */
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Update Port</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <form action="{{ route('ports.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                            <div class="col-lg-4 col-md-6">
                    <label for="from_port" class="form-label">Name</label>
                    <input type = "text" name = "port_name" class = "form-control" value="{{$ports->name}}" />
                </div>
                <div class="col-lg-4 col-md-6">
                    <label for="to_port" class="form-label">Country</label>
                    <select name="country" class="form-control" id="country">
                    <option value="" disabled selected>Select To Country</option>
                    @foreach($countries as $countries)
                        <option value="{{ $countries->id }}">{{ $countries->name }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <br><br>
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="submit" value="Update" class="btn btn-success btncenter">
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