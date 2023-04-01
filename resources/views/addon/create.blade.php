@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Addon Master</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        <div class="col-xxl-9 col-lg-6 col-md-12">
            <div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <label for="name" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                </div>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <input list="cityname" id="addon_name" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="addon_name" value="{{ old('addon_name') }}" required autocomplete="addon_name" autofocus>
                    <datalist id="cityname">
                        <option value="Rear Camera">
                        <option value="Cambridge">
                    </datalist>
                    @error('addon_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <label for="name" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                </div>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <label for="name" class="col-form-label text-md-end">{{ __('Purchase Price ( AED )') }}</label>
                </div>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <label for="name" class="col-form-label text-md-end">{{ __('Selling Price ( AED )') }}</label>
                </div>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <label for="name" class="col-form-label text-md-end">{{ __('Lead Time') }}</label>
                </div>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <label for="name" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                </div>
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <textarea rows="5" id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus></textarea>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            </br>
            <div class="row">
                <div class="col-xxl-5 col-lg-5 col-md-10">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="name" class="col-form-label text-md-end">{{ __('Brand') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <input list="cityname1" id="addon_name" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="addon_name" value="{{ old('addon_name') }}" required autocomplete="addon_name" autofocus>
                        <datalist id="cityname1">
                            <option value="Boston">
                            <option value="Cambridge">
                        </datalist>
                        @error('addon_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5 col-lg-5 col-md-10">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="name" class="col-form-label text-md-end">{{ __('Model Line') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                        <input list="cityname2" id="addon_name" type="text" class="form-control @error('addon_name') is-invalid @enderror" name="addon_name" value="{{ old('addon_name') }}" required autocomplete="addon_name" autofocus>
                        <datalist id="cityname2">
                            <option value="Boston">
                            <option value="Cambridge">
                        </datalist>
                        @error('addon_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="col-xxl-1 col-lg-1 col-md-2">
                <a style="float: right;" class="btn btn-sm btn-info" onclick=""><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-6 col-md-12">
        <!-- <textarea rows="22" id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus> -->
        <input type="file" class="form-control" id="customFile" />
        <!-- </textarea> -->
        </div>
        </div>
    </div>  
    </br>
@endsection

                               