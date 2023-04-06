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
        <form method="POST" enctype="multipart/form-data" action="{{ route('addon.store') }}"> 
            @csrf
            <div class="row">
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-5 col-md-11">
                            <input list="cityname" id="addon_id" type="text" class="form-control @error('addon_id') is-invalid @enderror" name="addon_id" placeholder="Choose Addon Name" value="{{ old('addon_id') }}" required autocomplete="addon_id" autofocus>
                            <datalist id="cityname">
                                @foreach($addons as $addon)
                                    <option data-value="{{$addon->id}}" value="{{$addon->name}}"></option>
                                @endforeach
                            </datalist>
                            @error('addon_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>
                        <div class="col-xxl-1 col-lg-1 col-md-1">
                            <a style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a> 
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="addon_code" type="text" class="form-control @error('addon_code') is-invalid @enderror" name="addon_code" placeholder="Enter Addon Code" value="{{ old('addon_code') }}" required autocomplete="addon_code" autofocus>
                            @error('addon_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="purchase_price" class="col-form-label text-md-end">{{ __('Purchase Price ( AED )') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="purchase_price" type="text" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" placeholder="Enter Purchase Price" value="{{ old('purchase_price') }}" required autocomplete="purchase_price" autofocus>
                            @error('purchase_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="selling_price" class="col-form-label text-md-end">{{ __('Selling Price ( AED )') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="selling_price" type="text" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" placeholder="Enter Selling Price" value="{{ old('selling_price') }}" required autocomplete="selling_price" autofocus>
                            @error('selling_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="lead_time" class="col-form-label text-md-end">{{ __('Lead Time') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="lead_time" type="text" class="form-control @error('lead_time') is-invalid @enderror" name="lead_time" placeholder="Enter Lead Time" value="{{ old('lead_time') }}" required autocomplete="lead_time" autofocus>
                            @error('lead_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="additional_remarks" class="col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control @error('additional_remarks') is-invalid @enderror" name="additional_remarks" placeholder="Enter Additional Remarks" value="{{ old('additional_remarks') }}" required autocomplete="additional_remarks" autofocus></textarea>
                            @error('additional_remarks')
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
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <label for="brand" class="col-form-label text-md-end">{{ __('Brand') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <label for="model" class="col-form-label text-md-end">{{ __('Model Line') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-1 col-lg-1 col-md-2">
                            </div>
                        </div>
                    <div id="dynamic_field">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                    
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <input list="cityname1" id="title" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand[]" placeholder="Choose Brand"  value="" required autocomplete="brand" autofocus>
                                    <datalist id="cityname1">
                                        @foreach($brands as $brand)
                                            <option data-value="{{$brand->id}}" value="{{$brand->brand_name}}"></option>
                                        @endforeach
                                    </datalist>
                                    <!-- <input id="selected" list="browsers" name="browser">
                                    <datalist id="browsers">
                                        <option data-value="1" value="InternetExplorer"></option>
                                        <option data-value="2" value="Firefox"></option>
                                        <option data-value="3" value="Chrome"></option>

                                    </datalist> -->
                                    <!-- <input id="submit" type="submit"> -->
                                    @error('brand')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                   
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <input list="cityname2" id="title1" type="text" class="form-control @error('model') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="" required autocomplete="model" autofocus>
                                    <datalist id="cityname2">
                                        <option value="Boston">
                                        <option value="Cambridge">
                                    </datalist>
                                    @error('model')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-1 col-lg-1 col-md-2">
                            </div>
                        </div>
                    </div>
                    </br>
                    <div id="newRow"></div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                            <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <input type="file" name="image" onchange="readURL(this);" />
                    </br>
                    </br>
                    <img id="blah" src="#" alt="your image" />
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary" id="submit">Submit</button>
              </div>
            </div>
            </br>
            <div class="related-addon-header">
                <h4 class="card-title related-addon-h4">Related Addons</h4>
            </div>
            </br>
            <div class="row related-addon">
                <div class="each-addon col-xxl-4 col-lg-6 col-md-12">
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="addon_id" class="related-label col-form-label text-md-end">{{ __('Addon Name') }}</label>
                            </div>
                            <div class="related-input-div col-xxl-7 col-lg-5 col-md-11">
                                <input list="cityname" id="addon_id" type="text" class="form-control" name="addon_id" value="{{ old('addon_id') }}" required autocomplete="addon_id">
                                <datalist id="cityname">
                                    @foreach($addons as $addon)
                                        <option data-value="{{$addon->id}}" value="{{$addon->name}}"></option>
                                    @endforeach
                                </datalist>
                                @error('addon_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="addon_code" class="related-label col-form-label text-md-end">{{ __('Addon Code') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="addon_code" type="text" class="form-control" name="addon_code" value="{{ old('addon_code') }}" required autocomplete="addon_code" >
                                @error('addon_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="purchase_price" class="related-label col-form-label text-md-end">{{ __('Purchase Price(AED)') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="purchase_price" type="text" class="form-control" name="purchase_price" value="{{ old('purchase_price') }}" required autocomplete="purchase_price">
                                @error('purchase_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="selling_price" class="related-label col-form-label text-md-end">{{ __('Selling Price(AED)') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="selling_price" type="text" class="form-control" name="selling_price" value="{{ old('selling_price') }}" required autocomplete="selling_price">
                                @error('selling_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="lead_time" class="related-label col-form-label text-md-end">{{ __('Lead Time') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="lead_time" type="text" class="form-control" name="lead_time" value="{{ old('lead_time') }}" required autocomplete="lead_time" >
                                @error('lead_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="additional_remarks" class="related-label col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                                <textarea rows="5" id="additional_remarks" type="text" class="form-control" name="additional_remarks" value="{{ old('additional_remarks') }}" required autocomplete="additional_remarks"></textarea>
                                @error('additional_remarks')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                            <img id="" src="#" alt="your image" />
                            </div>
                        </div>
                        </br>
                        <div class="row">
                                <div class="col-xxl-5 col-lg-5 col-md-10">
                                    <div class="row">
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                            <label for="brand" class="related-label col-form-label text-md-end">{{ __('Brand') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-5 col-lg-5 col-md-10">
                                    <div class="row">
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                            <label for="model" class="related-label col-form-label text-md-end">{{ __('Model Line') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-1 col-lg-1 col-md-2">
                                </div>
                            </div>
                        <div id="dynamic_field1">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-5 col-md-10">
                                    <div class="row">
                                        
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <input list="cityname1" id="title" type="text" class="form-control" name="brand[]"  value="" required autocomplete="brand" >
                                        <datalist id="cityname1">
                                            @foreach($brands as $brand)
                                                <option data-value="{{$brand->id}}" value="{{$brand->brand_name}}"></option>
                                            @endforeach
                                        </datalist>
                                        @error('brand')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-5 col-lg-5 col-md-10">
                                    <div class="row">
                                    
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <input list="cityname2" id="title1" type="text" class="form-control" name="model[]" value="" required autocomplete="model" >
                                        <datalist id="cityname2">
                                            <option value="Boston">
                                            <option value="Cambridge">
                                        </datalist>
                                        @error('model')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-1 col-lg-1 col-md-2">
                                </div>
                            </div>
                        </div>
                        </br>
                       
                    </div>
                
                    <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>  
    </br>
@endsection

                               