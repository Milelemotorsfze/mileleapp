@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Suppliers Master</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('SupplierController') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        <form method="POST" enctype="multipart/form-data" action="{{ route('suppliers.create') }}"> 
            @csrf
            <div class="row">
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="supplier" class="col-form-label text-md-end">{{ __('Supplier') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="supplier" type="text" class="form-control @error('supplier') is-invalid @enderror" name="supplier" placeholder="Choose Supplier Name" value="{{ old('supplier') }}" required autocomplete="supplier" autofocus>
                            
                            @error('supplier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="contact_person" class="col-form-label text-md-end">{{ __('Contact Person') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="contact_person" type="text" class="form-control @error('contact_person') is-invalid @enderror" name="contact_person" placeholder="Enter Contact Person" value="{{ old('contact_person') }}" required autocomplete="contact_person" autofocus>
                            @error('contact_person')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="contact_number" class="col-form-label text-md-end">{{ __('Contact Number') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="contact_number" type="text" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number" placeholder="Enter Contact Number" value="{{ old('contact_number') }}" required autocomplete="contact_number" autofocus>
                            @error('contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="alternative_contact_number" class="col-form-label text-md-end">{{ __('Alternative Contact Number') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="alternative_contact_number" type="text" class="form-control @error('alternative_contact_number') is-invalid @enderror" name="alternative_contact_number" placeholder="Enter Alternative Contact Number" value="{{ old('alternative_contact_number') }}" required autocomplete="alternative_contact_number" autofocus>
                            @error('alternative_contact_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="email" class="col-form-label text-md-end">{{ __('Email') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <textarea rows="5" id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" autofocus></textarea>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary" id="submit">Submit</button>
              </div>
            </div>
            </br>
        </form> 
    </div>  
    </br>
@endsection