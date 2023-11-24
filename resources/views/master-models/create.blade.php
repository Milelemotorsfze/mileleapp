@extends('layouts.main')
@section('content')
    @can('create-master-models')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-models');
        @endphp
        @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">Add New Models</h4>
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
        <form id="form-create" action="{{ route('master-models.store') }}" method="POST" >
            @csrf
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">Model</label>
                        <input type="text" class="form-control" name="model" placeholder="Enter Model">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">SFX</label>
                        <input type="text" class="form-control"  name="sfx" placeholder="Enter SFX">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">Model Year</label>
                        <input type="text" class="form-control" id="model-year"  name="model_year" placeholder="Enter Model Year">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label font-size-13 ">Steering</label>
                        <select class="form-control" name="steering" >
                            <option value="LHS">LHD</option>
                            <option value='RHS'>RHD</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">Variant</label>
                        <select class="form-control" name="variant_id" id="variant_id" >
                            <option></option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}">{{$variant->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mt-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="trans_car" name="is_transcar" >
                            <label class="form-check-label" for="trans_car">Trans Car</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="milele" name="is_milele">
                            <label class="form-check-label" for="milele">Milele</label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Amount in USD</label>
                        <input type="number" class="form-control"  name="amount_uae" min="0" placeholder="Enter Amount in USD">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Amount in EUR</label>
                        <input type="number" class="form-control" name="amount_belgium" min="0" placeholder="Enter Amount in EUR">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12" hidden id="trans-car-loi-div">
                        <div class="mb-3">
                            <label class="form-label">Trans Car LOI Description</label>
                            <input type="text" class="form-control" id="transcar-loi-description" value="" name="transcar_loi_description" placeholder="LOI Description">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12" hidden id="milele-loi-div">
                        <div class="mb-3">
                            <label class="form-label">Milele LOI Description</label>
                            <input type="text" class="form-control" id="milele-loi-description" value="" name="milele_loi_description" placeholder="LOI Description">
                        </div>
                    </div>
                </div>
                </br>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary ">Submit</button>
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
        $("#model-year").yearpicker({
            startYear: 2000,
            endYear: 2050,
        });
        $("#model-year").yearpicker({
            startYear: 2000,
            endYear: 2050,
        });
        // $("#form-create").validate({
        //     ignore: [],
        //     rules: {
        //         steering: {
        //             required: true,
        //             maxlength:255
        //         },
        //         model: {
        //             required: true,
        //             maxlength:255
        //         },
        //         sfx: {
        //             required: true,
        //             maxlength:255
        //         },
        //         variant_id: {
        //             required: true,
        //         },
        //         amount_uae: {
        //             required: true,
        //         },
        //         amount_belgium: {
        //             required: true,
        //         },
        //     },
        // });
        function showOrHideLoiDescription() {
            let variantId = $("#variant_id").val();
            if(variantId != "") {
                if($("#trans_car").prop('checked') == true) {
                    $('#trans-car-loi-div').attr('hidden', false);
                }else{
                    $('#trans-car-loi-div').attr('hidden', true);
                }
                if($("#milele").prop('checked') == true ){

                    $('#milele-loi-div').attr('hidden', false);
                }else{

                    $('#milele-loi-div').attr('hidden', true);
                }
            }

        }
        $('#trans_car').click(function() {
            showOrHideLoiDescription();
            getLOIDescription();
        });
        $('#milele').click(function() {
            showOrHideLoiDescription();
            getLOIDescription();
        });

        $("#variant_id").attr("data-placeholder","Choose Variant....  Or  Type Here To Search....");
        $("#variant_id").select2();
        $('#variant_id').on('change',function() {

            $('#variant_id-error').hide();
            showOrHideLoiDescription();
            getLOIDescription();
        })
        function getLOIDescription() {
            let url = '{{ route('master-model.get-loi-description') }}';
            let variantId = $("#variant_id").val();

            if($("#milele").prop('checked') == true){
                is_milele = 1;
            }else{
                is_milele = 0;
            }
            if($("#trans_car").prop('checked') == true ){
                is_transcar = 1;
            }else{
                is_transcar = 0;
            }

            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    id: variantId,
                    is_milele: is_milele,
                    is_transcar: is_transcar
                },
                success:function (data) {

                    if(is_milele == 1) {
                        $("#milele-loi-description").val(data.milele_loi_format);
                    }else{

                        $("#milele-loi-description").val(" ");
                    }
                    if(is_transcar == 1) {
                        $("#transcar-loi-description").val(data.transcar_loi_format);
                    }else{
                        $("#transcar-loi-description").val(" ");
                    }
                }
            });
        }
    </script>
@endpush





