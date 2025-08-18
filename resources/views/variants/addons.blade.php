@extends('layouts.main')
@section('content')
    @can('variants-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Create Modification Variant</h4>
                @can('variants-list')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('variants-list');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    @endif
                @endcan
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
                <form id="form-create" action="{{ route('variants.variantmodifications') }}" method="POST">
                    @csrf
                        <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Netsuite Variant Name</label>
                                   <input type = "text" name="netsuite_name" class="form-control" value= "{{$variant->netsuite_name}}"/>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Brand</label>
                                    <input class="form-control" type="text" class="" value="{{$brand->brand_name}}" readonly/>
                                    <input type="hidden" name="varaint" value="{{$variant->id}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Line</label>
                                    <select class="form-control" name="master_model_lines_id" id="model" readonly>
                                    <option value="{{$masterModelLine->id}}">{{$masterModelLine->model_line}}</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Year</label>
                                    @php
                                    $currentYear = date("Y");
                                    $years = range($currentYear + 10, $currentYear - 10);
                                    $years = array_reverse($years);
                                    @endphp
                                    <select name="my" class="form-control">
                                        @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ isset($variant) && $variant->my == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Gear</label>
                                    <select class="form-control" autofocus name="gearbox" id="gear">
                                    <option value="AT" {{ isset($variant) && $variant->gearbox == 'AT' ? 'selected' : '' }}>AT</option>
                                    <option value="MT" {{ isset($variant) && $variant->gearbox == 'MT' ? 'selected' : '' }}>MT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                                    <select class="form-control" autofocus name="fuel_type" id="fuel">
                                    <option value="Petrol" {{ isset($variant) && $variant->fuel_type == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                    <option value="Diesel" {{ isset($variant) && $variant->fuel_type == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="PH" {{ isset($variant) && $variant->fuel_type == 'PH' ? 'selected' : '' }}>PH</option>
                                    <option value="PHEV" {{ isset($variant) && $variant->fuel_type == 'PHEV' ? 'selected' : '' }}>PHEV</option>
                                    <option value="MHEV" {{ isset($variant) && $variant->fuel_type == 'MHEV' ? 'selected' : '' }}>MHEV</option>
                                    <option value="EV" {{ isset($variant) && $variant->fuel_type == 'EV' ? 'selected' : '' }}>EV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Engine</label>
                                    <select class="form-control" autofocus name="engine" id="engine">
                                            <option value="" {{ isset($variant) && $variant->engine == '' ? 'selected' : '' }}>Please Select the Engine Capacity</option>
                                            <option value="0.8" {{ isset($variant) && $variant->engine == '0.8' ? 'selected' : '' }}>0.8</option>
                                            <option value="1.0" {{ isset($variant) && $variant->engine == '1.0' ? 'selected' : '' }}>1.0</option>
                                            <option value="1.2" {{ isset($variant) && $variant->engine == '1.2' ? 'selected' : '' }}>1.2</option>
                                            <option value="1.3" {{ isset($variant) && $variant->engine == '1.3' ? 'selected' : '' }}>1.3</option>
                                            <option value="1.4" {{ isset($variant) && $variant->engine == '1.4' ? 'selected' : '' }}>1.4</option>
                                            <option value="1.5" {{ isset($variant) && $variant->engine == '1.5' ? 'selected' : '' }}>1.5</option>
                                            <option value="1.6" {{ isset($variant) && $variant->engine == '1.6' ? 'selected' : '' }}>1.6</option>
                                            <option value="1.8" {{ isset($variant) && $variant->engine == '1.8' ? 'selected' : '' }}>1.8</option>
                                            <option value="2.0" {{ isset($variant) && $variant->engine == '2.0' ? 'selected' : '' }}>2.0</option>
                                            <option value="2.2" {{ isset($variant) && $variant->engine == '2.2' ? 'selected' : '' }}>2.2</option>
                                            <option value="2.4" {{ isset($variant) && $variant->engine == '2.4' ? 'selected' : '' }}>2.4</option>
                                            <option value="2.5" {{ isset($variant) && $variant->engine == '2.5' ? 'selected' : '' }}>2.5</option>
                                            <option value="2.7" {{ isset($variant) && $variant->engine == '2.7' ? 'selected' : '' }}>2.7</option>
                                            <option value="2.8" {{ isset($variant) && $variant->engine == '2.8' ? 'selected' : '' }}>2.8</option>
                                            <option value="3.0" {{ isset($variant) && $variant->engine == '3.0' ? 'selected' : '' }}>3.0</option>
                                            <option value="3.3" {{ isset($variant) && $variant->engine == '3.3' ? 'selected' : '' }}>3.3</option>
                                            <option value="3.4" {{ isset($variant) && $variant->engine == '3.4' ? 'selected' : '' }}>3.4</option>
                                            <option value="3.5" {{ isset($variant) && $variant->engine == '3.5' ? 'selected' : '' }}>3.5</option>
                                            <option value="3.6" {{ isset($variant) && $variant->engine == '3.6' ? 'selected' : '' }}>3.6</option>
                                            <option value="3.8" {{ isset($variant) && $variant->engine == '3.8' ? 'selected' : '' }}>3.8</option>
                                            <option value="4.0" {{ isset($variant) && $variant->engine == '4.0' ? 'selected' : '' }}>4.0</option>
                                            <option value="4.2" {{ isset($variant) && $variant->engine == '4.2' ? 'selected' : '' }}>4.2</option>
                                            <option value="4.4" {{ isset($variant) && $variant->engine == '4.4' ? 'selected' : '' }}>4.4</option>
                                            <option value="4.5" {{ isset($variant) && $variant->engine == '4.5' ? 'selected' : '' }}>4.5</option>
                                            <option value="4.6" {{ isset($variant) && $variant->engine == '4.6' ? 'selected' : '' }}>4.6</option>
                                            <option value="4.8" {{ isset($variant) && $variant->engine == '4.8' ? 'selected' : '' }}>4.8</option>
                                            <option value="5.0" {{ isset($variant) && $variant->engine == '5.0' ? 'selected' : '' }}>5.0</option>
                                            <option value="5.2" {{ isset($variant) && $variant->engine == '5.2' ? 'selected' : '' }}>5.2</option>
                                            <option value="5.3" {{ isset($variant) && $variant->engine == '5.3' ? 'selected' : '' }}>5.3</option>
                                            <option value="5.5" {{ isset($variant) && $variant->engine == '5.5' ? 'selected' : '' }}>5.5</option>
                                            <option value="5.6" {{ isset($variant) && $variant->engine == '5.6' ? 'selected' : '' }}>5.6</option>
                                            <option value="5.7" {{ isset($variant) && $variant->engine == '5.7' ? 'selected' : '' }}>5.7</option>
                                            <option value="6.0" {{ isset($variant) && $variant->engine == '6.0' ? 'selected' : '' }}>6.0</option>
                                            <option value="6.2" {{ isset($variant) && $variant->engine == '6.2' ? 'selected' : '' }}>6.2</option>
                                            <option value="6.7" {{ isset($variant) && $variant->engine == '6.7' ? 'selected' : '' }}>6.7</option>
                                        </select>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Steering</label>
                                    <select class="form-control" autofocus name="steering" id="steering">
            <option value="LHD" {{ isset($variant) && $variant->steering == 'LHD' ? 'selected' : '' }}>LHD</option>
            <option value="RHD" {{ isset($variant) && $variant->steering == 'RHD' ? 'selected' : '' }}>RHD</option>
        </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Drive Train</label>
                                    <select class="form-control" autofocus name="drive_train" id="drive_train">
                                    <option value="AWD" {{ isset($variant) && $variant->drive_train == 'AWD' ? 'selected' : '' }}>AWD</option>
                                    <option value="4WD" {{ isset($variant) && $variant->drive_train == '4WD' ? 'selected' : '' }}>4WD</option>
                                    <option value="FWD" {{ isset($variant) && $variant->drive_train == 'FWD' ? 'selected' : '' }}>FWD</option>
                                    <option value="RWD" {{ isset($variant) && $variant->drive_train == 'RWD' ? 'selected' : '' }}>RWD</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Upholstery</label>
                                    <select class="form-control" autofocus name="upholstery" id="upholstery">
                                    <option value="Leather" {{ isset($variant) && $variant->upholstery == 'Leather' ? 'selected' : '' }}>Leather</option>
                                    <option value="Fabric" {{ isset($variant) && $variant->upholstery == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                    <option value="Vinyl" {{ isset($variant) && $variant->upholstery == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                                    <option value="Leather & Fabric" {{ isset($variant) && $variant->upholstery == 'Leather & Fabric' ? 'selected' : '' }}>Leather & Fabric</option>
                                </select>
                                </div>
                            </div>
                            </div>
                            <div id="accessiores-container">
                        <div class="row" id="accessiores">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <select class="form-control" autofocus name="attributes[]" id="attributes">
                                    <option disabled selected>Please Select the Attributes</option>
                                        @foreach ($modelspecifications as $modelspecifications)
                                            <option value="{{ $modelspecifications->id }}">{{ $modelspecifications->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                <select class="form-control" name="accessories[]" id="accessories">
                                    <option disabled selected>Please Select the Accessories</option>
                                        @foreach ($addonsaccessores as $addon)
                                            @php
                                                $addonName = DB::table('addons')->where('id', $addon->addon_id)->first();
                                            @endphp 
                                            <option value="{{ $addonName->id }}">{{ $addonName->name }} - {{ $addon->AddonDescription->description ?? '' }}- {{ $addon->addon_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addNewRow('accessiores')">Add More Accessories</button>
                            <hr>
                            <div id="spareparts-container">
                                <div class="row" id="spareparts">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <select class="form-control" name="spareparts[]" id="spareparts">
                                        <option disabled selected>Please Select the Spare Parts</option>
                                            @foreach ($spareparts as $sparepart)
                                                @php
                                                    $sparepartName = DB::table('addons')->where('id', $sparepart->addon_id)->first()->name;
                                                @endphp 
                                                <option value="{{ $sparepart->addon_id }}">{{ $sparepartName }} - {{ $sparepart->addon_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="addNewRow('spareparts')">Add More Spare Parts</button>
                                                        <div class="col-12 text-center">
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
        function addNewRow(type) {
        var container = document.getElementById(type + '-container');
        var newRow = container.querySelector('.row').cloneNode(true);
        var removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.className = "btn btn-danger";
        removeButton.textContent = "X";
        removeButton.onclick = function () {
            newRow.parentNode.removeChild(newRow);
        };
        newRow.querySelector('.mb-3').appendChild(removeButton);
        container.querySelector('.row').parentNode.appendChild(newRow);
        }
        </script>
        @endpush