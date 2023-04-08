  <!-- <div class="related-addon-header">
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
            </div> -->












            <div class="row related-addon">
              @foreach($addon1 as $addonsdata)
                <div class="each-addon col-xxl-4 col-lg-6 col-md-12">
                    <div class="col-xxl-12 col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="addon_id" class="related-label col-form-label text-md-end">{{ __('Addon Name') }}</label>
                            </div>
                            <div class="related-input-div col-xxl-7 col-lg-5 col-md-11">
                                <input list="cityname" id="addon_id" type="text" class="form-control" name="addon_id" value="{{ $addonsdata->AddonName->name }}" required autocomplete="addon_id">
                                
                               
                                
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="addon_code" class="related-label col-form-label text-md-end">{{ __('Addon Code') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="addon_code" type="text" class="form-control" name="addon_code" value="{{ $addonsdata->addon_code }}" required autocomplete="addon_code" >
                              
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="purchase_price" class="related-label col-form-label text-md-end">{{ __('Purchase Price(AED)') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="purchase_price" type="text" class="form-control" name="purchase_price" value="{{ $addonsdata->purchase_price }}" required autocomplete="purchase_price">
                              
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="selling_price" class="related-label col-form-label text-md-end">{{ __('Selling Price(AED)') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="selling_price" type="text" class="form-control" name="selling_price" value="{{ $addonsdata->selling_price }}" required autocomplete="selling_price">
                              
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="lead_time" class="related-label col-form-label text-md-end">{{ __('Lead Time') }}</label>
                            </div>
                            <div class="col-xxl-7 col-lg-6 col-md-12">
                                <input id="lead_time" type="text" class="form-control" name="lead_time" value="{{ $addonsdata->lead_time }}" required autocomplete="lead_time" >
                              
                            </div>
                        </div>
                        </br>
                        <div class="row">
                            <div class="col-xxl-5 col-lg-6 col-md-12">
                                <label for="additional_remarks" class="related-label col-form-label text-md-end">{{ __('Additional Remarks') }}</label>
                                <textarea rows="5" id="additional_remarks" type="text" class="form-control" name="additional_remarks" value="{{ $addonsdata->additional_remarks }}" required autocomplete="additional_remarks"></textarea>
                               
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
                            @foreach($addonsdata->AddonTypes as $AddonTypes)
                        <div id="">
                            <div class="row">
                                <div class="col-xxl-5 col-lg-5 col-md-10">
                                    <div class="row">
                                        
                                        <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <input list="cityname1" id="title" type="text" class="form-control" name="brand[]"  value="{{ $AddonTypes->brand_id }}" required autocomplete="brand" >
                                        <datalist id="cityname1">
                                          
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
                                        <input list="cityname2" id="title1" type="text" class="form-control" name="model[]" value="{{ $AddonTypes->model_id }}" required autocomplete="model" >
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
                        @endforeach
                    </div>
                
                    <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                    </div>
                </div>
              @endforeach
            </div>