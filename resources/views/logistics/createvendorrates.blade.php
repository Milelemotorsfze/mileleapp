@extends('layouts.main')
@section('content')
<style>
        .hidden {
            display: none;
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">Create Shipping Rate: {{$to_port->name}} - {{$from_port->name}}</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        <div class="row">
        <form action="{{ route('Shipping.storevendorrates') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" name="id" value="{{$shipping->id}}"/>
                <div id="dynamicRows">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <label for="vendor_id" class="form-label">Vendors</label>
                    <select name="vendor_id[]" class="form-control vendor-select">
                        <option value="" disabled selected>Select Vendor</option>
                        @foreach($vendors as $vendors)
                                    <option value="{{ $vendors->id }}">{{ $vendors->supplier }}</option>
                                @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Cost Price</label>
                    <input type="number" class="form-control" name="cost_price[]" required/>
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Selling Price</label>
                    <input type="number" class="form-control" name="selling_price[]"required/>
                </div>
                <div class="col-lg-2 col-md-1">
                <button type="button" class="btn btn-danger delete-row" style="margin-top: 30px;">X</button>
                </div>
            </div>
        </div>
        <br>
        <button type="button" class="btn btn-primary" id="addRow">Add New Row</button>
        <br><br>
        <div class="col-lg-12 col-md-12">
            <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
        </div>
        </form>
        <br>
    </div>
    @endsection
    @push('scripts')
    <script>
        $(document).ready(function () {
            $("#addRow").on("click", function () {
                var newRow = $("#dynamicRows .row:first").clone();
                newRow.find('input, select').val('');
                newRow.find('.delete-row').show();
                $("#dynamicRows").append(newRow);
            });
            $(document).on("click", ".delete-row", function () {
                if ($("#dynamicRows .row").length > 1) {
                    $(this).closest(".row").remove();
                } else {
                    alert("At least one row is required.");
                }
            });
        });
    </script>
    @endpush