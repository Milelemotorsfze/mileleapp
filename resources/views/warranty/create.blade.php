@extends('layouts.main')
<style>
    .error
    {
        color: #FF0000;
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Addon Master</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <form id="createAddonForm" name="createAddonForm" method="POST" enctype="multipart/form-data" action="{{ route('warranty.store') }}">
            @csrf
            <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>                
                <div class="row">
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Policy Name') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Policy Name" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>                      
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Vehicle Category 1') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Vehicle Category 1" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Vehicle Category 2') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Vehicle Category 2" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Years') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Eligibility Years" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>                     
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Eligibility Mileage') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Eligibility Mileage" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Claim Limit') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Claim Limit" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                            <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Period') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Extended Warranty Period" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus>
                            <span id="supplierError" class="invalid-feedback"></span>                      
                    </div>
                    <div class="col-xxl-4 col-lg-4 col-md-6">
                        <span class="error">* </span>
                        <label for="supplier" class="col-form-label text-md-end">{{ __('Is Open Mileage') }}</label>
                        <fieldset>
                            <div class="some-class">
                                <input type="radio" class="radioFixingCharge" name="fixing_charges_included" value="yes" id="yes" checked />
                                <label for="yes">Yes</label>
                                    <input type="radio" class="radioFixingCharge" name="fixing_charges_included" value="no" id="no" />
                                <label for="no">No</label>
                            </div>
                        </fieldset>
                    </div>
                    <span id="supplierError" class="invalid-feedback"></span>
                    <div class="col-xxl-4 col-lg-4 col-md-6" hidden>
                        <span class="error">* </span>
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Extended Warranty Mileage') }}</label>
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Enter Extended Warranty Mileage" value="{{ old('supplier') }}"  autocomplete="supplier" autofocus >
                        <span id="supplierError" class="invalid-feedback"></span>                     
                    </div>
                </div>
            </div>
            </br>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-sm" id="submit">Submit</button>
            </div>
        </form>
    </div>
       
@endsection