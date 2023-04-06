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
                <form action="{{route('demands.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Supplier</label>
                                <select class="form-control" data-trigger name="supplier" id="supplier">
                                    <option value="" disabled>Select The Supplier</option>
                                    <option value="TTC">TTC</option>
                                    <option value='AMS'>AMS</option>  
                                    <option value='CPS'>CPS</option>  
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label font-size-13 text-muted">Dealers</label>
                                <select class="form-control" data-trigger name="whole_saler" id="wholesaler">
                                    <option value="Trans_Cars">Trans Cars</option>
                                    <option value="Milele_Motors">Milele Motors</option>                                             
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                                <select class="form-control" data-trigger name="steering" id="steering">
                                    <option value="LHD">LHD</option>
                                    <option value='RHD'>RHD</option>                                             
                                </select>
                            </div>
                        </div>                  
                        </br>						
                    <div class="col-lg-12 col-md-12">
                        <button type="submit" class="btn btn-dark btncenter">Submit</button>
                    </div>  
                </form>
            </div>

@endsection