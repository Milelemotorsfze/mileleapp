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
                        <label class="form-label">LOI Description</label>
                        <input type="text" class="form-control" id="loi-description" name="loi_description" placeholder="LOI Description">
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
        $("#form-create").validate({
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
                variant_id: {
                    required: true,
                },
                amount_uae: {
                    required: true,
                },
                amount_belgium: {
                    required: true,
                },
            },
        });
        $("#variant_id").attr("data-placeholder","Choose Variant....  Or  Type Here To Search....");
        $("#variant_id").select2();
        $('#variant_id').on('change',function() {
            $('#variant_id-error').hide();
        })

    </script>
@endpush





