@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add/ Update Supplier Inventory Record</h4>
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
        <form action="{{ route('supplier-inventories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-lg-2 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Supplier</label>
                    <select class="form-control" data-trigger name="supplier" id="supplier">
                        <option value="" disabled>Select The Supplier</option>
                        <option value="TTC">TTC</option>
                        <option value="AMS">AMS</option>
                        <option value="CPS">CPS</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Dealers</label>
                    <select class="form-control" data-trigger name="whole_sales" id="wholesaler">
                        <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}">Trans Cars</option>
                        <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}">Milele Motors</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Country</label>
                    <select class="form-control" data-trigger name="country" id="choices-single-default">
                        <option value='UAE'>UAE</option>
                        <option value='Belguim'>Belguim</option>
                    </select>
                </div>
            </div>
            <input type="hidden" class="form-control" id="datepicker-datetime" name="date" value=""/>
            <div class="col-lg-8 col-md-6">
                <div class="col-4">
                    <input type="file" name="file" class="form-control" >
                </div>
            </div>
            </br>
            <div class="col-4">
                <button type="submit" class="btn btn-dark" > Upload </button>
            </div>
            </form>
        </form>
    </div>
@endsection

