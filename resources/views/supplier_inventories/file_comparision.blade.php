@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">File Comparision</h4>
    </div>
    <div class="card-body">
        @if(Session::has('message'))
            <div class="alert alert-danger">
                {{Session::get('message')}}
            </div>
        @endif

        <form action="{{ route('supplier-inventories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">First File</label>
                        <select class="form-control" data-trigger name="supplier" id="supplier">
                            <option value="" disabled>Select First File</option>
                            @foreach($supplierInventoryDates as $key => $supplierInventoryDate)
                                <option value="{{ $supplierInventoryDate }}">File {{ $key + 1 }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Second File</label>
                        <select class="form-control" data-trigger name="supplier" id="supplier">
                            <option value="" disabled>Select Second File</option>
                            @foreach($supplierInventoryDates as $key => $supplierInventoryDate)
                                <option value="{{ $supplierInventoryDate }}">File {{ $key + 1 }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-dark" > Compare </button>
            </div>
            </br>
        </form>
    </div>
@endsection


