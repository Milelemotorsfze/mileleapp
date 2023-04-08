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
            <div class="row">
                @foreach($demands as $demand)
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Supplier</label>
                        <input type="text" value="{{$demand->supplier}}" class="form-control" readonly/>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Dealers</label>
                        <input type="text" value="{{$demand->whole_saler}}" class="form-control" readonly/>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                        <input type="text" value="{{$demand->steering}}" class="form-control" readonly/>
                    </div>
                </div>
                <div class ="d-flex">
                    <div class = "col-lg-4">
                        <div class = "row">
                    <div class="col-lg-3 col-md-3">
                        <label for="basicpill-firstname-input" class="form-label">Model</label>
                        <select class="form-control" data-trigger name="supplier" id="supplier">
                            <option value="" disabled>Select The Supplier</option>
                            <option value="TTC">TTC</option>
                            <option value="AMS">AMS</option>
                            <option value="CPS">CPS</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <label for="basicpill-firstname-input" class="form-label">Sfx</label>
                        <div id="presfx">
                        </div>
                    </div>
                </div>

                </br>
                @endforeach
                <div class="col-lg-12 col-md-12">
                    <button type="submit" class="btn btn-dark btncenter">Submit</button>
                </div>
            </div>


@endsection
