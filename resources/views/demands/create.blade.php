@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New Demands</h4>
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
        <form action="{{ route('demands.store') }}" method="POST" >
        @csrf
            <div class="row">
                <div class="row demand-div">
                    <div class="col-lg-3 col-md-3">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 "> Supplier</label>
                            <select class="form-control" data-trigger name="supplier_id" id="supplier">
                                <option value="" disabled>Select The Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13">Dealers</label>
                            <select class="form-control" data-trigger name="whole_saler" id="whole-saler">
                                <option value="Trans Cars">Trans Cars</option>
                                <option value="Milele Motors">Milele Motors</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13">Steering</label>
                            <select class="form-control" data-trigger name="steering" id="steering">
                                <option value="LHD">LHD</option>
                                <option value='RHD'>RHD</option>
                            </select>
                        </div>
                    </div>
                    </br>
                    <div class="col-lg-12 col-md-12">
                        <button type="submit" class="btn btn-dark btncenter" id="add-demand">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
@endsection

