@extends('layouts.main')
@section('content')
    @can('edit-master-models')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-master-models');
    @endphp
    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">Edit Master Model</h4>
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
                        <label for="choices-single-default" class="form-label font-size-13 ">Steering</label>
                        <select class="form-control" data-trigger name="steering" >
                            <option value="LHS" {{ $masterModel->steering == "LHS" ? 'selected' : " "}} >LHS</option>
                            <option value="RHS"  {{ $masterModel->steering == "RHS" ? 'selected' : " "}} >RHS</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Model</label>
                        <input type="text" class="form-control" value="{{ $masterModel->model }}" name="model">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">SFX</label>
                        <input type="text" class="form-control" value="{{ $masterModel->sfx }}" name="sfx">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Variant</label>
                        <select class="form-control" name="variant_id" id="variant_id">
                            <option></option>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" {{$masterModel->variant_id == $variant->id ? 'selected' : " "}}>{{$variant->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Amount in USD</label>
                        <input type="number" class="form-control"  name="amount_uae" min="0" value="{{ $masterModel->amount_uae }}">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="basicpill-firstname-input" class="form-label">Amount in EUR</label>
                        <input type="number" class="form-control" name="amount_belgium" min="0" value="{{ $masterModel->amount_belgium }}">
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





