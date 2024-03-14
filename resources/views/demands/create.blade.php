@extends('layouts.main')
@section('content')
    @can('demand-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Add New Demand</h4>
                <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                    @if (Session::has('success'))
                        <div class="alert alert-success" id="success-alert">
                            <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                <form action="{{ route('demands.store') }}" method="POST" >
                @csrf
                    <div class="row">
                        <div class="row demand-div">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label"> Vendor</label>
                                    <select class="form-control" autofocus name="supplier_id" id="vendor">
                                        <option value="" disabled>Select The Vendor</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Dealers</label>
                                    <select class="form-control" data-trigger name="whole_saler" id="whole-saler">
                                        <option value="Trans Cars">Trans Cars</option>
                                        <option value="Milele Motors">Milele Motors</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Steering</label>
                                    <select class="form-control" data-trigger name="steering" id="steering">
                                        <option value="LHD">LHD</option>
                                        <option value='RHD'>RHD</option>
                                    </select>
                                </div>
                            </div>
                            </br>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary " id="add-demand">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $("#vendor").attr("data-placeholder", "Choose Vendor....     Or     Type Here To Search....");
        $("#vendor").select2({});
    </script>
@endpush

