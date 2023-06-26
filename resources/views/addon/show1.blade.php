@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Show User Details</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        <form method="POST" enctype="multipart/form-data" action="{{ route('addon.updatedetails',$addonDetails->id) }}"> 
            @csrf
            <div class="row">
                <div class="col-xxl-9 col-lg-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="addon_id" class="col-form-label text-md-end">{{ __('Addon Name') }}</label>
                        </div>
                        <div class="col-xxl-8 col-lg-5 col-md-11">
                            <input list="cityname" id="addon_id" type="text" class="form-control @error('addon_id') is-invalid @enderror" name="addon_id" placeholder="Choose Addon Name" value="{{ $addonDetails->AddonName->name }}" required autocomplete="addon_id" autofocus readonly>
                            <datalist id="cityname">
                                @foreach($addons as $addon)
                                    <option data-amount="{{$addon->id}}">{{$addon->name}}</option>
                                @endforeach
                            </datalist>
                            @error('addon_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>
                        <!-- <div class="col-xxl-1 col-lg-1 col-md-1">
                            <a style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a> 
                        </div> -->
                    </div>
                    </br>
                    <div class="row">
                        <div class="col-xxl-3 col-lg-6 col-md-12">
                            <label for="addon_code" class="col-form-label text-md-end">{{ __('Addon Code') }}</label>
                        </div>
                        <div class="col-xxl-9 col-lg-6 col-md-12">
                            <input id="addon_code" type="text" class="form-control @error('addon_code') is-invalid @enderror" name="addon_code" placeholder="Enter Addon Code" value="{{ $addonDetails->addon_code }}" required autocomplete="addon_code" autofocus readonly>
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
                            <input id="purchase_price" type="text" class="form-control @error('purchase_price') is-invalid @enderror" name="purchase_price" placeholder="Enter Purchase Price" value="{{ $addonDetails->purchase_price }}" required autocomplete="purchase_price" autofocus readonly>
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
                            <input id="selling_price" type="text" class="form-control @error('selling_price') is-invalid @enderror" name="selling_price" placeholder="Enter Selling Price" value="" required autocomplete="selling_price" autofocus readonly>
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
                            <input id="lead_time" type="text" class="form-control @error('lead_time') is-invalid @enderror" name="lead_time" placeholder="Enter Lead Time" value="{{ $addonDetails->lead_time }}" required autocomplete="lead_time" autofocus readonly>
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
                            <textarea rows="5" id="additional_remarks" type="text" class="form-control @error('additional_remarks') is-invalid @enderror" name="additional_remarks" placeholder="Enter Additional Remarks" required autocomplete="additional_remarks" autofocus readonly>{{ $addonDetails->additional_remarks }}</textarea>
                            @error('additional_remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    </br>
                    <div>
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
                        </div>
                        <!-- </br> -->
                    </div>
                    <div id="dynamic_field">
                        @foreach($addonDetails->AddonTypes as $AddonTypes)
                        </br>
                        <div class="row">
                        <input type="text" name="addon_details_id[]"  value="{{ $AddonTypes->id }}" hidden>
                            <div class="col-xxl-5 col-lg-5 col-md-10">
                                <div class="row">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <input list="cityname1" id="title" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand[]" placeholder="Choose Brand"  value="{{$AddonTypes->brand_id}}" required autocomplete="brand" autofocus readonly>
                                    <datalist id="cityname1">
                                        @foreach($brands as $brand)
                                            <option data-amount="{{$brand->id}}">{{$brand->brand_name}}</option>
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
                                    <input list="cityname2" id="title1" type="text" class="form-control @error('model') is-invalid @enderror" name="model[]" placeholder="Choose Model Line" value="{{$AddonTypes->model_id}}" required autocomplete="model" autofocus readonly>
                                    <datalist id="cityname2">
                                    @foreach($modelLines as $modelLine)
                                            <option data-value="{{$modelLine->id}}" value="{{$modelLine->model_line}}"></option>
                                        @endforeach
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
                                <a id="'+i+'" style="float: right;" class="btn btn-sm btn-danger btn_remove"><i class="fa fa-minus" aria-hidden="true"></i> Remove</a>
                            </div>
                        </div>
                       
                        @endforeach
                        @foreach($addonDetails->AddonSuppliers as $Suppliers)
                        </br>
                        <div class="row">
                        <input type="text" name="addon_details_id[]"  value="{{ $Suppliers->Suppliers->supplier }}">
                           
                         
                           
                        </div>
                       
                        @endforeach
                    </div>
                    <!-- </br> -->
                    <div id="newRow"></div>
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                            <a id="add" style="float: right;" class="btn btn-sm btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Add trim</a> 
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-12">
                    <input type="file" name="image" onchange="readURL(this);" />
                    </br>
                    </br>
                    <img  src="{{ asset('addon_image/' . $addonDetails->image) }}" alt="your image" />
                </div>
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary" id="submit">Submit</button>
              </div>
            </div>
        </form>
		</br>
    </div>
@endsection