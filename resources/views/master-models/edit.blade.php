@extends('layouts.main')

<style>
    .custom-error {
        color: red;
        margin-top: 10px !important;
    }
</style>

@section('content')
    @can('edit-master-models')
    
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-master-models');
    @endphp
    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Edit Master Model</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('master-models.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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
        <form id="form-update" action="{{ route('master-models.update', $masterModel->id) }}" method="POST" >
            @method('PUT')
            @csrf
            <div class="row">

                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Model</label>
                        <input type="text" class="form-control"  @if($disableEdit == 1) readonly title="Not allowed to edit! model already used in LOI/PFI/PO" @endif 
                        value="{{ $masterModel->model }}" name="model">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">SFX</label>
                        <input type="text" class="form-control" @if($disableEdit == 1) readonly title="Not allowed to edit! model already used in LOI/PFI/PO" @endif 
                        value="{{ $masterModel->sfx }}" name="sfx">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">Model Year</label>
                            <input type="text" class="form-control" id="model-year" @if($disableEdit == 1) disabled title="Not allowed to edit! model already used in LOI/PFI/PO" @endif 
                            name="model_year" placeholder="Enter Model Year">
                    </div>
                    @if($disableEdit == 1)
                        <input type="hidden" name="model_year" value="{{ $masterModel->model_year }}">
                    @endif
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">New Model</label>
                        <input type="text" class="form-control" name="pfi_model" placeholder="Enter New Model"
                         value="{{ $masterModel->pfi_model }}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label  class="form-label">New SFX</label>
                        <input type="text" class="form-control"  name="pfi_sfx" placeholder="Enter New SFX"
                        value="{{ $masterModel->pfi_sfx }}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 ">Steering</label>
                        <select class="form-control" name="steering" @if($disableVariantEdit == 1) disabled title="Not allowed to edit! already used in LOI/PFI/PO" @endif >
                            <option value="LHD" {{ $masterModel->steering == "LHD" ? 'selected' : " "}} >LHD</option>
                            <option value="RHD"  {{ $masterModel->steering == "RHD" ? 'selected' : " "}} >RHD</option>
                        </select>
                    </div>
                    @if($disableEdit == 1)
                        <input type="hidden" name="steering" value="{{ $masterModel->steering }}">
                    @endif
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Variant</label>
                        <select class="form-control" name="variant_id" @if($disableVariantEdit == 1) disabled title="Not allowed to edit! already used in LOI/PFI/PO" @else 
                        title="Variant is missing" @endif id="variant_id">
                            <option></option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" {{$masterModel->variant_id == $variant->id ? 'selected' : " "}}>{{$variant->name}}</option>
                            @endforeach
                        </select>
                        @if($disableEdit == 1)
                            <input type="hidden" name="variant_id" value="{{ $masterModel->variant_id }}">
                        @endif
                        @if($disableEdit == 1)
                            <input type="hidden" name="variant_id" value="{{ $masterModel->variant_id }}">
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">PFI Model Description</label>
                        <input type="text" class="form-control" name="model_description" placeholder="Model Description"
                         value="{{ $masterModel->model_description }}">
                   </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Amount in USD</label>
                        <input type="number" class="form-control"  name="amount_uae" min="0" 
                        value="{{ $masterModel->amount_uae }}" placeholder="Amount in USD">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Amount in EUR</label>
                        <input type="number" class="form-control" name="amount_belgium" min="0" 
                        value="{{ $masterModel->amount_belgium }}" placeholder="Amount in EUR">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div style="margin-top: 35px;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="trans_car" name="is_transcar" value="1" {{true == $masterModel->is_transcar  ? 'checked' : ''}} >
                            <label class="form-check-label" for="trans_car">Trans Car</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="milele" name="is_milele" value="1" {{true == $masterModel->is_milele  ? 'checked' : ''}}>
                            <label class="form-check-label" for="milele">Milele</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12" id="trans-car-loi-div" @if(empty($masterModel->transcar_loi_description)) hidden @endif >
                        <div class="mb-3">
                            <label class="form-label">Trans Car LOI Description</label>
                            <input type="text" class="form-control" id="transcar-loi-description" value="{{ $masterModel->transcar_loi_description }}"
                                   name="transcar_loi_description" placeholder="LOI Description">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12" id="milele-loi-div" @if(empty($masterModel->milele_loi_description)) hidden @endif >
                        <div class="mb-3">
                            <label class="form-label">Milele LOI Description</label>
                            <input type="text" class="form-control" id="milele-loi-description" value="{{ $masterModel->milele_loi_description }}" name="milele_loi_description"
                                   placeholder="LOI Description">
                        </div>
                    </div>
                </div>
                <div class="card"  id="variant-detail-div" hidden>
                    <div class="card-header">
                        <h4>Variant Detail</h4>
                    </div>
                    <div class="card-body">
                        <div class="row" >
                            <div class="col-sm-4">
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        Brand :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="brand"></dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        Model Line :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="model_line"></dl>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="row">
                                    <div class="col-sm-4">
                                        Netsuite Name :
                                    </div>
                                    <div class="col-sm-8">
                                        <dl id="netsuite-name"></dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        Model Detail :
                                    </div>
                                    <div class="col-sm-8">
                                        <dl id="model-detail"></dl>
                                    </div>
                                </div>
                    
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-3">
                                        Model Year :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="model_year"></dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        Engine :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="engine"></dl>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-3">
                                        Fuel Type :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="fuel_type"></dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        Upholestry :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="upholestry"></dl>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="row">
                                    <div class="col-sm-3">
                                        Seat :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="seat"></dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        COO :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="coo"></dl>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="row">
                                    <div class="col-sm-3">
                                        Gear Box :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="gear-box"></dl>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        Steering :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="steering"></dl>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <div class="row">
                                    <div class="col-sm-3">
                                        Detail :
                                    </div>
                                    <div class="col-sm-9">
                                        <dl id="detail"></dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row" id="variant-items">

                        </div> -->
                    </div>
                </div>
                </br>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
            year: '{{ $masterModel->model_year }}',
            startYear: 2000,
            endYear: 2050,
        });
        $("#form-update").validate({
            ignore: [],
            rules: {
                steering: {
                    required: true,
                    maxlength:255
                },
                model: {
                    required: true,
                    maxlength:255
                },
                sfx: {
                    required: true,
                    maxlength:255
                },
                model_year: {
                    required: true,
                },
                variant_id: {
                    required: true,
                },
            },
            errorPlacement: function(error, element) {
                error.addClass('custom-error');
                if (element.attr("name") === "variant_id") {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
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
                    $('#brand').html(data.variant.brand.brand_name);
                    $('#model_line').html(data.variant.master_model_lines.model_line);
                    $('#model_year').html(data.variant.my);
                    $('#engine').html(data.variant.engine);
                    $('#fuel_type').html(data.variant.fuel_type);
                    $('#seat').html(data.variant.seat);
                    $('#gear-box').html(data.variant.gearbox);
                    $('#steering').html(data.variant.steering);
                    $('#coo').html(data.variant.coo);
                    $('#detail').html(data.variant.detail);
                    $('#model-detail').html(data.variant.model_detail);
                    $('#netsuite-name').html(data.variant.netsuite_name);
                   
                }
            });
        }
        $("#variant_id").select2({
            placeholder:'Choose Variant',
        });
        $('#variant_id').on('change',function() {
            $('#variant_id-error').hide();
            getLOIDescription();
            showOrHideLoiDescription();
            $('#variant-detail-div').attr('hidden', false);
        })
    </script>
@endpush





