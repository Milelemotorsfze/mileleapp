@extends('layouts.main')
@section('content')
    <style>
        .widthinput
        {
            height:32px!important;
        }
    </style>
    @can('supplier-inventory-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Add Supplier Inventory Record</h4>
                <a  class="btn btn-sm btn-info float-end " href="{{ route('supplier-inventories.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            </div>
            <div class="card-body">
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert"></button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert"> </button>
                        {{ Session::get('message') }}
                    </div>
                @endif
                <form id="form-update" action="{{ route('supplier-inventories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Vendor</label>
                                <select class="form-control widthinput" autofocus name="supplier_id" id="supplier">
                                    <option value="" disabled>Select The Vendor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Dealers</label>
                                <select class="form-control widthinput" data-trigger name="whole_sales" id="wholesaler">
                                    <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}">Trans Cars</option>
                                    <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}">Milele Motors</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Country</label>
                                <select class="form-control widthinput" data-trigger name="country" id="choices-single-default">
                                    <option value='UAE'>UAE</option>
                                    <option value='Belgium'>Belgium</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Estimated Arrival Date </label>
                                <input type="date" name="eta_import" placeholder="Enter Date Of Entry" class="form-control widthinput">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Model</label>
                                <select class="form-select widthinput" multiple name="model" id="model" autofocus>
                                    <option value="" >Select Model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->model }}">{{ $model->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">SFX</label>
                                <select class="form-select widthinput" multiple name="sfx" id="sfx" autofocus>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Chasis</label>
                                <input type="text" name="chasis" placeholder="Enter Chasis" class="form-control widthinput">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Engine Number</label>
                                <input type="text" name="engine_number" placeholder="Enter Engine Number" class="form-control widthinput">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Colour Code</label>
                                <input type="text" name="color_code" placeholder="Enter Colour Code" class="form-control widthinput">
                            </div>
                        </div>  <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Production Month</label>
                                <input type="text" name="pord_month" placeholder="Enter Production Month" class="form-control widthinput">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> PO Arm</label>
                                <input type="text" name="po_arm" placeholder="Enter PO Arm" class="form-control widthinput">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Delivery Note </label>
                                <input type="text" name="delivery_note" placeholder="Enter Delivery Note" class="form-control widthinput">
                            </div>
                        </div>

                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary"> Submit </button>
                    </div>
                </form>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $("#form-update").validate({
            ignore: [],
            rules: {
                model: {
                    required: true,
                }
            },
        });

        $('#model').select2({
            placeholder: 'Select Model',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            getSfx();
        });

        $('#sfx').select2({
            placeholder: 'Select SFX',
            allowClear: true,
            maximumSelectionLength: 1
        });

        function getSfx() {
            let model = $('#model').val();

            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
                    module: 'LOI',
                },
                success:function (data) {
                    console.log(data);
                    $('#sfx').empty();
                    $('#sfx').html('<option value=""> Select SFX </option>');
                    jQuery.each(data, function(key,value){
                        $('#sfx').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        }
    </script>
@endpush

