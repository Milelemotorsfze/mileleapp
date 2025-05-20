@extends('layouts.main')
@section('content')
    <style>
        .widthinput
        {
            height:32px!important;
        }
        .error{
            color: #f12323;
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
                    @if (Session::has('success'))
                        <div class="alert alert-success" >
                            <button type="button" class="btn-close p-0 close" data-dismiss="alert"></button>
                            {{ Session::get('success') }}
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
                                    <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}">Milele Motors</option>
                                    <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}">Trans Cars</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Country</label>
                                <select class="form-control widthinput" multiple="true" name="country" id="country">
                                    <option value="UAE">UAE</option>
                                    <option value="Belgium">Belgium</option>
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
                                <input type="text" name="chasis" id="chasis" placeholder="Enter Chasis" onkeyup="CheckUniqueChasis()" class="form-control widthinput">
                                <span id="chasis_error" class="error"></span>

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
                                <label for="choices-single-default" class="form-label text-muted">Colour Code</label>
                                <input type="text" name="color_code" placeholder="Enter Colour Code" oninput="checkColorCode()" id="color_code" class="form-control widthinput">
                                <span id="color_code_error" class="error"></span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Production Month</label>
                                <input type="text" name="prod_month" placeholder="Enter Production Month" onkeyup="CheckProductionMonth()" id="pord_month" class="form-control widthinput">
                                <span id="pord_month_error" class="error"></span>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Delivery Note </label>
                                <input type="text" name="delivery_note" oninput="deliveryNote()" id="delivery_note" placeholder="Enter Delivery Note"
                                       class="form-control widthinput">
                                <span id="delivery_note_error" class="error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" id="submit-button" class="btn btn-primary"> Submit </button>
                    </div>
                </form>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        var feildValidInput = true;

        $("#form-update").validate({
            ignore: [],
            rules: {
                model: {
                    required: true,
                },
                sfx: {
                    required: true,
                },
                supplier_id: {
                    required: true,
                },
                whole_sales: {
                    required: true,
                },
                country:{
                    required: true,
                },
                pord_month: {
                    maxlength:6,
                    minlength:6,
                },
                color_code:{
                    minlength:4,
                    maxlength:8
                }
            },
        });

        $('#model').select2({
            placeholder: 'Select Model',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            getSfx();
            CheckProductionMonth();
        });

        $('#sfx').select2({
            placeholder: 'Select SFX',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            CheckProductionMonth();
        });

        $('#country').select2({
            placeholder: 'Select Country',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            deliveryNote();
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
                    $('#sfx').empty();
                    $('#sfx').html('<option value=""> Select SFX </option>');
                    jQuery.each(data, function(key,value){
                        $('#sfx').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        }
        function CheckProductionMonth() {
           let productionMonth =  $('#pord_month').val();
           let model =  $('#model').val();
           let sfx =  $('#sfx').val();
           let url = '{{ route('supplier-inventories.uniqueProductionMonth') }}';

           if(productionMonth.length == 6  && model.length > 0 && sfx.length > 0) {
               var InputId = 'pord_month_error';
               let modelYear =  productionMonth.slice(-2);
               if(modelYear > 12) {
                   feildValidInput = false;
                   $msg = 'production Month should be valid'
                   showValidationError(InputId,$msg);
               }else{
                   $.ajax({
                       type:"GET",
                       url: url,
                       data: {
                           prod_month: productionMonth,
                           model: model,
                           sfx: sfx,
                       },
                       dataType : 'json',
                       success: function(data) {

                           if(data !== 1) {
                               feildValidInput = false;
                               $msg = 'The model,sfx and the requested model year ('+ data +') combination not existing in the system.';

                               showValidationError(InputId,$msg);
                           }else{
                               feildValidInput = true;
                               removeValidationError(InputId);
                           }
                       }
                   });
               }
          }
        }
        function CheckUniqueChasis() {
            let url = '{{ route('supplier-inventories.unique-chasis') }}';
            let chasis = $('#chasis').val();

            if(chasis.length > 0) {
                $.ajax({
                    type:"GET",
                    url: url,
                    data: {
                        chasis: chasis,
                    },
                    dataType : 'json',
                    success: function(data) {
                        var InputId = 'chasis_error';
                        if(data == 1) {
                            feildValidInput = false;
                            $msg = "This chasis is already existing";
                            showValidationError(InputId, $msg);
                        }else{
                            feildValidInput = true;
                            removeValidationError(InputId);
                        }
                    }
                });
            }
        }
        function checkColorCode() {
            let url = '{{ route('supplier-inventories.isExistColorCode') }}';
            let colorCode = $('#color_code').val();
            var InputId = 'color_code_error';

            if(colorCode.length == 5 || colorCode.length == 4) {
                $.ajax({
                    type:"GET",
                    url: url,
                    data: {
                        color_code: colorCode,
                    },
                    dataType : 'json',
                    success: function(data) {
                        if(data == 0) {
                            feildValidInput = false;
                            $msg = "This color code is not existing in our master Color Codes.";
                            showValidationError(InputId, $msg);
                        }else{
                            feildValidInput = true;
                            removeValidationError(InputId);
                        }
                    }
                });
            }else{
                feildValidInput = true;
                removeValidationError(InputId);
            }
        }
        function deliveryNote(){
            let deliveryNote = $('#delivery_note').val();
            let country = $('#country').val();
            let InputId = 'delivery_note_error';
            {{--if(country == '{{ \App\Models\SupplierInventory::COUNTRY_UAE }}') {--}}
            {{--    console.log("ok country");--}}
            {{--    if ($.isNumeric(deliveryNote)) {--}}
            {{--        console.log("numeric");--}}
            {{--        cosnole.log(deliveryNote.length);--}}
            {{--        if (deliveryNote.length < 5) {--}}
            {{--            console.log("ok");--}}
            {{--            $msg = "Delivery Note minimum length should be 5";--}}
            {{--            showValidationError(InputId, $msg);--}}
            {{--            feildValidInput == false;--}}
            {{--        } else {--}}
            {{--            removeValidationError(InputId);--}}
            {{--            feildValidInput == true;--}}
            {{--        }--}}
            {{--    }--}}
            {{--}--}}

            let url = '{{ route('supplier-inventories.check-delivery-note') }}';

            if(deliveryNote.length > 0 && country.length > 0) {
                $.ajax({
                    type:"GET",
                    url: url,
                    data: {
                        country: country,
                        delivery_note: deliveryNote,
                        data_from: 'CREATE'

                    },
                    dataType : 'json',
                    success: function(data) {
                        if(data == 0) {
                            if(country == '{{ \App\Models\SupplierInventory::COUNTRY_BELGIUM }}') {
                                $msg = "Delivery note value will be Waiting or Received.";
                            }else{
                                $msg = "Delivery note will be Waiting or number";
                            }
                            showValidationError(InputId, $msg);
                            feildValidInput == false;
                        }else{
                            removeValidationError(InputId);
                            feildValidInput == true;
                        }
                    }
                });
            }else{
                removeValidationError(InputId);
                feildValidInput == true;
            }
        }

        function showValidationError(InputId,$msg){
          $('#'+InputId).html($msg);
        }
        function removeValidationError(InputId,$msg){
            $('#'+InputId).html("");
        }
        $('form').submit(function (e) {
            if (feildValidInput == false) {
                e.preventDefault();
            }
        });
    </script>
@endpush

