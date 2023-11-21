@extends('layouts.main')
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
<style>
    .button-container {
    display: flex;
    gap: 10px;
    float: right;
}
.button-containerinner {
    display: flex;
    gap: 10px;
    float: right;
}
    </style>
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<div class="card-header">
    <h4 class="card-title">
     Inspection Report
     <center><b>Vehicle Identification Number:
                    {{$vehicle->vin}}
                </b></center>
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('approvalsinspection.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
</div>
<div class="card-body">
<div class="button-container">
    @if($grnpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$grnpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> GRN Pictures</a>
    @endif
    @if($secgrnpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$secgrnpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> GRN-2 Pictures</a>
    @endif
    @if($gdnpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$gdnpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> GDN Pictures</a>
    @endif
    @if($secgdnpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$secgdnpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> GDN-2 Pictures</a>
    @endif
    @if($modificationpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$modificationpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> Modification Pictures</a>
    @endif
    @if($Incidentpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$Incidentpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> Incident Pictures</a>
    @endif
    @if($PDIpicturelink)
    <a class="btn btn-sm btn-primary" href="{{$PDIpicturelink}}" target="_blank"><i class="fa fa-camera" aria-hidden="true"></i> PDI Pictures</a>
    @endif
</div>
<form id="approveForm" action="{{ route('approveInspection') }}" method="POST">
    @csrf
<h5>Trim Specifications</h5>
<br>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Items</th>
                <th>Procurement Value</th>
                <th>Inspection Value</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Brand</td>
                <td>{{$brand->brand_name}}</td>
                <td>
                    <select class="form-control" name="brands_id" disabled>
                    @foreach($allBrands as $singleBrand)
                    <option value="{{$singleBrand->id}}" @if($singleBrand->id == $brands->id) selected @endif>
                    {{$singleBrand->brand_name}}
                    </option>
                    @endforeach
                 </select>
                 <input type="hidden" name="brands_id" value="{{$brands->id}}"/>
                </td>
                <td>
                    @if($brand->id == $brands->id)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
                <td>Model</td>
                <td>{{$model_line->model_line}}</td>
                <td>
                    <select class="form-control" name="master_model_lines_id" disabled>
                    @foreach($model_lines as $models)
                    <option value="{{$models->id}}" @if($models->id == $modal->id) selected @endif>
                    {{$models->model_line}}
                    </option>
                    @endforeach
                 </select>
                 <input type="hidden" name="master_model_lines_id" value="{{$modal->id}}"/>
                </td>
                <td>
                    @if($model_line->id == $modal->id)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
    <td>Steering</td>
    <td>{{$variant->steering}}</td>
    <td>
        <select class="form-control" name="steering">
            <option value="LHD" @if($variant_request->steering == 'LHD') selected @endif>LHD</option>
            <option value="RHD" @if($variant_request->steering == 'RHD') selected @endif>RHD</option>
        </select>
    </td>
    <td>
        @if($variant_request->steering == $variant->steering)
            <i class="fas fa-check text-success"></i>
        @else
            <i class="fas fa-times text-danger"></i>
        @endif
    </td>
</tr>

            <tr>
                <td>Engine</td>
                <td>{{$variant->engine}}</td>
                <td>
                    <select class="form-control" name="engine">
                    <option value="0.8" @if($variant_request->engine == '0.8') selected @endif>0.8</option>
                    <option value="1.2" @if($variant_request->engine == '1.2') selected @endif>1.2</option>
                    <option value="1.4" @if($variant_request->engine == '1.4') selected @endif>1.4</option>
                    <option value="1.5" @if($variant_request->engine == '1.5') selected @endif>1.5</option>
                    <option value="1.6" @if($variant_request->engine == '1.6') selected @endif>1.6</option>
                    <option value="1.8" @if($variant_request->engine == '1.8') selected @endif>1.8</option>
                    <option value="2" @if($variant_request->engine == '2') selected @endif>2</option>
                    <option value="2.2" @if($variant_request->engine == '2.2') selected @endif>2.2</option>
                    <option value="2.4" @if($variant_request->engine == '2.4') selected @endif>2.4</option>
                    <option value="2.5" @if($variant_request->engine == '2.5') selected @endif>2.5</option>
                    <option value="2.7" @if($variant_request->engine == '2.7') selected @endif>2.7</option>
                    <option value="2.8" @if($variant_request->engine == '2.8') selected @endif>2.8</option>
                    <option value="3" @if($variant_request->engine == '3') selected @endif>3</option>
                    <option value="3.3" @if($variant_request->engine == '3.3') selected @endif>3.3</option>
                    <option value="3.5" @if($variant_request->engine == '3.5') selected @endif>3.5</option>
                    <option value="4" @if($variant_request->engine == '4') selected @endif>4</option>
                    <option value="4.2" @if($variant_request->engine == '4.2') selected @endif>4.2</option>
                    <option value="4.4" @if($variant_request->engine == '4.4') selected @endif>4.4</option>
                    <option value="4.5" @if($variant_request->engine == '4.5') selected @endif>4.5</option>
                    <option value="4.8" @if($variant_request->engine == '4.8') selected @endif>4.8</option>
                    <option value="5.3" @if($variant_request->engine == '5.3') selected @endif>5.3</option>
                    <option value="5.6" @if($variant_request->engine == '5.6') selected @endif>5.6</option>
                    <option value="5.7" @if($variant_request->engine == '5.7') selected @endif>5.7</option>
                    <option value="6" @if($variant_request->engine == '6') selected @endif>6</option>
                    <option value="6.2" @if($variant_request->engine == '6.2') selected @endif>6.2</option>
                    <option value="6.7" @if($variant_request->engine == '6.7') selected @endif>6.7</option>
                 </select>
                </td>
                <td>
                    @if($variant_request->engine == $variant->engine)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
                <td>Fuel Type</td>
                <td>{{$variant->fuel_type}}</td>
                <td>
                    <select class="form-control" name="fuel_type">
                    <option value="Petrol" @if($variant_request->fuel_type == 'Petrol') selected @endif>Petrol</option>
                    <option value="Diesel" @if($variant_request->fuel_type == 'Diesel') selected @endif>Diesel</option>
                    <option value="PHEV" @if($variant_request->fuel_type == 'PHEV') selected @endif>PHEV</option>
                    <option value="MHEV" @if($variant_request->fuel_type == 'MHEV') selected @endif>MHEV</option>
                    <option value="EV" @if($variant_request->fuel_type == 'EV') selected @endif>EV</option>
                 </select>
                </td>
                <td>
                    @if($variant_request->fuel_type == $variant->fuel_type)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
                <td>Upholstery</td>
                <td>{{$variant->upholestry}}</td>
                <td>
                    <select class="form-control" name="upholestry">
                    <option value="Leather" @if($variant_request->upholestry == 'Leather') selected @endif>Leather</option>
                    <option value="Fabric" @if($variant_request->upholestry == 'Fabric') selected @endif>Fabric</option>
                    <option value="Vinyl" @if($variant_request->upholestry == 'Vinyl') selected @endif>Vinyl</option>
                    <option value="Microfibre" @if($variant_request->upholestry == 'Microfibre') selected @endif>Microfibre</option>
                 </select>
                </td>
                <td>
                @if($variant_request->upholestry == $variant->upholestry)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
                <td>Gear</td>
                <td>{{$variant->gearbox}}</td>
                <td>
                    <select class="form-control" name="gearbox">
                    <option value="Auto" @if($variant_request->upholestry == 'Auto') selected @endif>Auto</option>
                    <option value="Manual" @if($variant_request->upholestry == 'Manual') selected @endif>Manual</option>
                 </select>
                </td>
                <td>
                @if($variant_request->gearbox == $variant->gearbox)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <input type="hidden" name="coo" value="{{ $variant->coo }}"/>
            <input type="hidden" name="drive_train" value="{{ $variant->drive_train }}"/>
            <tr>
                <td>Model Year</td>
                <td>{{$variant->my}}</td>
                <td>
                @php
                                    $currentYear = date("Y");
                                    $years = range($currentYear + 10, $currentYear - 10);
                                    $years = array_reverse($years);
                                    @endphp
                    <select class="form-control" name="my">
                    @foreach ($years as $year)
                    <option value="{{ $year }}" {{ $variant_request->my == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                 </select>
                </td>
                <td>
                @if($variant_request->my == $variant->my)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
                <td>Interior Colour</td>
                <td>{{$intColor->name}}</td>
                <td>
                    <select class="form-control" name="int_colour">
                    @foreach($intColorall as $intColorall)
                    <option value="{{$intColorall->id}}" @if($intColorall->id == $intColorr->id) selected @endif>
                    {{$intColorall->name}}
                    </option>
                    @endforeach
                 </select>
                </td>
                <td>
                    @if($intColor->id == $intColorr->id)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
            <tr>
                <td>Exterior Colour</td>
                <td>{{$extColor->name}}</td>
                <td>
                    <select class="form-control" name="ex_colour">
                    @foreach($extColorall as $exColorall)
                    <option value="{{$exColorall->id}}" @if($exColorall->id == $extColorr->id) selected @endif>
                    {{$exColorall->name}}
                    </option>
                    @endforeach
                 </select>
                </td>
                <td>
                    @if($extColor->id == $extColorr->id)
                        <i class="fas fa-check text-success"></i>
                        @else
                        <i class="fas fa-times text-danger"></i>
                        @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
<hr>
<h5>Variant Specifications</h5>
<br>
    <div class="row">
    @foreach($data as $item)
    <div class="col-lg-2 col-md-6 col-sm-12">
        <div class="mb-3">
            <label for="choices-single-default" class="form-label">{{ $item['label'] }}</label>
            <select class="form-control" autofocus name="specification_{{ $item['specification_id'] }}">
                @foreach($item['options'] as $optionId => $optionValue)
                    <option value="{{ $optionId }}" {{ old('specification_' . $item['specification_id']) == $optionId || $item['selected'] == $optionValue ? 'selected' : '' }}>
                        {{ $optionValue }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    @endforeach
     </div>
<hr>
    <div class="modal fade extraitems-modal" id="extraitems" tabindex="-1" aria-labelledby="extraitemsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="extraitemsLabel">Extra Items</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <div class="row">
        <div class="col-md-2">
            <ul class="list-group">
            <input type="hidden" id="vehicle_id" value="{{ $inspection->vehicle_id }}">
            <li class="list-group-item">
    @if ($extraItems->contains('item_name', 'sparewheel'))
        <input type="checkbox" id="sparewheel" name="sparewheel" checked>
        <label for="sparewheel">Spare Wheel</label>
        <input class="form-control" type="hidden" name="sparewheel_qty" value="{{ $extraItems->where('item_name', 'sparewheel')->first()->qty }}" placeholder="Qty">
    @else
        <input type="checkbox" id="sparewheel" name="sparewheel">
        <label for="sparewheel">Spare Wheel</label>
        <input class="form-control" type="hidden" name="sparewheel_qty" placeholder="Qty">
    @endif
</li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'jack'))
                    <input type="checkbox" id="jack" name="jack" checked>
                    <label for="jack">Jack</label>
                    <input class="form-control" type="hidden" name="jack_qty" value="{{ $extraItems->where('item_name', 'jack')->first()->qty }}" placeholder="Qty">
                @else
                <input type="checkbox" id="jack" name="jack">
                    <label for="jack">Jack</label>
                    <input class="form-control" type="hidden" name="jack_qty" placeholder="Qty">
                @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'wheel'))
                    <input type="checkbox" id="wheel" name="wheel" checked>
                    <label for="wheel">Wheel Spanner</label>
                    <input class="form-control" type="hidden" name="wheel_qty" value="{{ $extraItems->where('item_name', 'wheel')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="wheel" name="wheel">
                    <label for="wheel">Wheel Spanner</label>
                    <input  class="form-control" type="hidden" name="wheel_qty" placeholder="Qty">
                    @endif
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'firstaid'))
                    <input type="checkbox" id="firstaid" name="firstaid" checked>
                    <label for="firstaid">First Aid Kit / Packing Box</label>
                    <input class="form-control" type="hidden" name="firstaid_qty" value="{{ $extraItems->where('item_name', 'firstaid')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="firstaid" name="firstaid">
                    <label for="firstaid">First Aid Kit / Packing Box</label>
                    <input  class="form-control" type="hidden" name="firstaid_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'floor_mat'))
                    <input type="checkbox" id="floor_mat" name="floor_mat" checked>
                    <label for="floor_mat">Floor Mat</label>
                    <input class="form-control" type="hidden" name="floor_mat_qty" value="{{ $extraItems->where('item_name', 'floor_mat')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="floor_mat" name="floor_mat">
                    <label for="floor_mat">Floor Mat</label>
                    <input class="form-control" type="hidden" name="floor_mat_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'service_book'))
                    <input type="checkbox" id="service_book" name="service_book" checked>
                    <label for="service_book">Service Book & Manual</label>
                    <input class="form-control" type="hidden" name="service_book_qty" value="{{ $extraItems->where('item_name', 'service_book')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="service_book" name="service_book">
                    <label for="service_book">Service Book & Manual</label>
                    <input class="form-control" type="hidden" name="service_book_qty" placeholder="Qty">
                    @endif
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'keys'))
                    <input type="checkbox" id="keys" name="keys" checked>
                    <label for="keys">Keys / Qty</label>
                    <input class="form-control" type="text" name="keys_qty" value="{{ $extraItems->where('item_name', 'keys')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="keys" name="keys">
                    <label for="keys">Keys / Qty</label>
                    <input class="form-control" type="text" name="keys_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'wheelrim'))
                    <input type="checkbox" id="wheelrim" name="wheelrim" checked>
                    <label for="wheelrim">Wheel Rim / Tyres</label>
                    <input class="form-control" type="hidden" name="wheelrim_qty" value="{{ $extraItems->where('item_name', 'wheelrim')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="wheelrim" name="wheelrim">
                    <label for="wheelrim">Wheel Rim / Tyres</label>
                    <input class="form-control" type="hidden" name="wheelrim_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'fire_extinguisher'))
                    <input type="checkbox" id="fire_extinguisher" name="fire_extinguisher" checked>
                    <label for="fire_extinguisher">Fire Extinguisher</label>
                    <input class="form-control" type="hidden" name="fire_extinguisher_qty" value="{{ $extraItems->where('item_name', 'fire_extinguisher')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="fire_extinguisher" name="fire_extinguisher">
                    <label for="fire_extinguisher">Fire Extinguisher</label>
                    <input class="form-control" type="hidden" name="fire_extinguisher_qty" placeholder="Qty">
                    @endif
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'sd_card'))
                    <input type="checkbox" id="sd_card" name="sd_card" checked>
                    <label for="sd_card">SD Card / Remote / H Phones</label>
                    <input class="form-control" type="hidden" name="sd_card_qty" value="{{ $extraItems->where('item_name', 'sd_card')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="sd_card" name="sd_card">
                    <label for="sd_card">SD Card / Remote / H Phones</label>
                    <input class="form-control" type="hidden" name="sd_card_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'ac_system'))
                    <input type="checkbox" id="ac_system" name="ac_system" checked>
                    <label for="ac_system">A/C System</label>
                    <input class="form-control" type="hidden" name="ac_system_qty" value="{{ $extraItems->where('item_name', 'ac_system')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="ac_system" name="ac_system">
                    <label for="ac_system">A/C System</label>
                    <input class="form-control" type="hidden" name="ac_system_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
                <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'dash_board'))
                    <input type="checkbox" id="dash_board" name="dash_board" checked>
                    <label for="dash_board">Dash Board / T Screen / LCD</label>
                    <input class="form-control" type="hidden" name="dash_board_qty" value="{{ $extraItems->where('item_name', 'dash_board')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="dash_board" name="dash_board">
                    <label for="dash_board">Dash Board / T Screen / LCD</label>
                    <input class="form-control" type="hidden" name="dash_board_qty" placeholder="Qty">
                    @endif
                </li>
            </ul>
        </div>
    </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveextraitems()">Save Changes</button>
        </div>
      </div>
    </div>
  </div> 
<div class="button-containerinner">
            <a class="btn btn-sm btn-primary" href="#" onclick="openextraitems('{{ $inspection->id }}')">Edit</a>
            </div>
</br>
<h5>Extra Items:</h5>
    <div class="row">
        <div class="col-md-2">
            <ul class="list-group">
            <li class="list-group-item">
                    @if ($extraItems->contains('item_name', 'sparewheel'))
                        <i class="fas fa-check-circle text-success"></i>
                        <span>{{ $extraItems->where('item_name', 'sparewheel')->first()->qty }}</span>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                    <label for="loss_item_1">Spare Wheel</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'jack'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'jack')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_2">Jack</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'wheel'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'wheel')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_3">Wheel Spanner</label>
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'firstaid'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'firstaid')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_4">First Aid Kit / Packing Box</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'floor_mat'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'floor_mat')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_5">Floor Mat</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'service_book'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'service_book')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_6">Service Book & Manual</label>
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'keys'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'keys')->first()->qty }} - </span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_7">Keys / Qty</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'wheelrim'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'wheelrim')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_8">Wheel Rim / Tyres</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'fire_extinguisher'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'fire_extinguisher')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_9">Fire Extinguisher</label>
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'sd_card'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'sd_card')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_10">SD Card / Remote / H Phones</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'ac_system'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'ac_system')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_11">A/C System</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'dash_board'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'dash_board')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_12">Dash Board / T Screen / LCD</label>
                </li>
            </ul>
        </div>
    </div>
     </br>
@if($Incident)
<br>
<hr>
    <div class="modal fade incidentreport-modal" id="incidentreport" tabindex="-1" aria-labelledby="incidentreportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="incidentreportLabel">Incident Report</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="engine" class="form-label">Incident Type:</label>
            </div>
            <div class="col-md-8">
            <select class="form-control" id="incidentType" name="incidenttype">
                <option value="Electrical" @if ("Electrical" == $Incident->type) selected="selected" @endif >Electrical</option>
                <option value="Machinical" @if ("Machinical" == $Incident->type) selected="selected" @endif >Machinical</option>
                <option value="Accident" @if ("Accident" == $Incident->type) selected="selected" @endif >Accident</option>
            </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="vin" class="form-label">Narration of Accident / Damage:</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="narration" name="narration" value="{{$Incident->narration ?? ''}}">
            <input type="hidden" class="form-control" id="Incidentid" name="Incidentid" value="{{$Incident->id ?? ''}}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="int_colour" class="form-label">Damage Details:</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="damageDetails" name="damageDetails" value=" {{$Incident->detail ?? ''}}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="ex_colour" class="form-label">Driven By:</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="drivenBy" name="drivenBy" value="{{$Incident->driven_by ?? ''}}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="extra_features" class="form-label">Responsibility for Recover The Damages:</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="responsibility" name="responsibility" value="{{$Incident->responsivity ?? ''}}">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="ex_colour" class="form-label">Reasons:</label>
            </div>
            <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="overspeed" name="overspeed" {{ strpos($Incident->reason ?? '', 'overspeed') !== false ? 'checked' : '' }}>
                    <label for="overspeed">Over-Speed</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="weather" name="weather" {{ strpos($Incident->reason ?? '', 'weather') !== false ? 'checked' : '' }}>
                    <label for="weather">Weather Comditions</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="vehicle_defects" name="vehicle_defects" {{ strpos($Incident->reason ?? '', 'vehicle_defects') !== false ? 'checked' : '' }}>
                    <label for="vehicle_defects">Vehicle Defects</label>
                </li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="negligence" name="negligence" {{ strpos($Incident->reason ?? '', 'negligence') !== false ? 'checked' : '' }}>
                    <label for="negligence">Negligence</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="sudden_halt" name="sudden_halt" {{ strpos($Incident->reason ?? '', 'sudden_halt') !== false ? 'checked' : '' }}>
                    <label for="sudden_halt">Sudden Halt</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="road_defects" name="road_defects" {{ strpos($Incident->reason ?? '', 'road_defects') !== false ? 'checked' : '' }}>
                    <label for="road_defects">Road Defects</label>
                </li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="fatigue" name="fatigue" {{ strpos($Incident->reason ?? '', 'fatigue') !== false ? 'checked' : '' }}>
                    <label for="fatigue">Fatigue</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="no_safety_distance" name="no_safety_distance" {{ strpos($Incident->reason ?? '', 'no_safety_distance') !== false ? 'checked' : '' }}>
                    <label for="no_safety_distance">No Safety Distance</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="using_gsm" name="using_gsm" {{ strpos($Incident->reason ?? '', 'using_gsm') !== false ? 'checked' : '' }}>
                    <label for="using_gsm">Using GSM</label>
                </li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="overtaking" name="overtaking" {{ strpos($Incident->reason ?? '', 'overtaking') !== false ? 'checked' : '' }}>
                    <label for="overtaking">Overtaking</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="wrong_action" name="wrong_action" {{ strpos($Incident->reason ?? '', 'wrong_action') !== false ? 'checked' : '' }}>
                    <label for="wrong_action">Wrong Action</label>
                </li>
            </ul>
        </div>
    </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="remark" class="form-label">Remarks:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="editor"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveincidents()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
        <div class="button-containerinner">
        <a class="btn btn-sm btn-primary" href="#" onclick="incidentreport('{{ $inspection->id }}')">Edit</a>
            </div>
<h5>Incident Report</h5>
            <div class="row">
        <div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Incident Type</strong></label>
</div>
<div class="col-md-8">
                    {{$Incident->type ?? ''}}
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Narration Of Accident / Damage</strong></label>
</div>
<div class="col-md-8">
                    {{$Incident->narration ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Damage Details</strong></label>
</div>
<div class="col-md-8">
                    {{$Incident->detail ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Driven By</strong></label>
</div>
<div class="col-md-8">
                    {{$Incident->driven_by ?? ''}}
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Responsibility for Recover the Damages</strong></label>
</div>
<div class="col-md-8">
                    {{$Incident->responsivity ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Reason</strong></label>
</div>
<div class="col-md-8">
                    {{$Incident->reason ?? ''}}
</div>
</div>
</div>
<div class="col-md-12">
<div class="row">
<div class="col-md-12">
    @if ($Incident->file_path)
        <img src="{{ asset('qc/' . $Incident->file_path) }}" alt="Incident Image">
    @else
        No image available
    @endif
</div>
</div>
</div>
</div>
@endif
<hr>
<div class="row">
    @if($extra_featuresvalue)
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-4">
                    <label><strong>Extra Features</strong></label>
                </div>
                <div class="col-md-8">
                    {{$extra_featuresvalue}}
                </div>
            </div>
        </div>
    @endif
</div>
<br>
<h5>Inspection Remarks</h5>
<div class="row">
    <div class="col-md-12">
        {!! $inspection->remark !!}
    </div>
</div>
@if($inspection->process_remarks)
    <br>
    <h5>Manager Remarks</h5>
    <div class="row">
        <div class="col-md-12">
            {!! $inspection->process_remarks !!}
        </div>
    </div>
@endif
@if($inspection->reinspection_remarks)
    <br>
    <h5>Re Inspection Remarks</h5>
    <div class="row">
        <div class="col-md-12">
            {!! $inspection->reinspection_remarks !!}
        </div>
    </div>
@endif
<hr>
    <input type="hidden" name="inspection_id" value="{{ $inspection->id }}">
    <input type="hidden" id="buttonValue" name="buttonValue" value="">
    <div class="form-group">
        <h5>Manager Remarks</h5>
        <textarea id="process_remarks" name="process_remarks" class="form-control" rows="4" placeholder="Enter Manager Remarks"></textarea>
    </div>
</form>
<a style="float: right;" class="btn btn-success" onclick="setButtonValue('approve')">
    <i class="fa fa-check" aria-hidden="true"></i> Approve Inspection
</a>
@if($inspection->stage != "Incident")
@if($inspection->status == "Pending")
<a style="float: right; margin-right: 10px;" class="btn btn-danger" onclick="setButtonValue('reinspect')">
    <i class="fa fa-times" aria-hidden="true"></i> Re Inspection
</a>
@endif
@endif
</br>
</div>
@endsection
@push('scripts')
<script>
function openModalp(InspectionId) {
  $('#inspectiondetail').data('inspection-id', InspectionId);
  $('#inspectiondetail').modal('show');
}
function openextraitems(InspectionId) {
  $('#extraitems').data('inspection-id', InspectionId);
  $('#extraitems').modal('show');
}
function Newvariant(InspectionId) {
  $('#newvariant').data('inspection-id', InspectionId);
  $('#newvariant').modal('show');
}
function incidentreport(InspectionId) {
  $('#incidentreport').data('inspection-id', InspectionId);
  $('#incidentreport').modal('show');
}
</script>
<script>
    $(document).ready(function () {
        $('input[name="variantType"]').change(function () {
            $('.fields-to-toggle').hide();
            const selectedValue = $(this).val();
            $(`.${selectedValue}-variant-fields`).show();
        });
    });
</script>
<script>
function saveinspectiondetails() {
    var engine = $('#engine').val();
    var ins  ection_id = $('#inspection_id').val();
    var vin = $('#vin').val();
    var int_colour = $('#int_colour').val();
    var ex_colour = $('#ex_colour').val();
    var extra_features = $('#extra_features').val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var data = {
        engine: engine,
        vin: vin,
        int_colour: int_colour,
        ex_colour: ex_colour,
        inspection_id: inspection_id,
        extra_features: extra_features,
        _token: csrfToken
    };
    $.ajax({
        type: 'POST',
        url: '{{route('approvalsinspection.updateinspectionupdates')}}',
        data: data,
        dataType: 'json',
        success: function(response) {
            alertify.success('Inspection Updated');
        setTimeout(function() {
            location.reload();
        }, 1000);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
function saveextraitems() {
    var vehicle_id = $('#vehicle_id').val();
    var dataToSend = { vehicle_id: vehicle_id };

    $('#extraitems input[type=checkbox]').each(function() {
        var itemName = $(this).attr('name');
        var qtyInput = $('[name="' + itemName + '_qty"]');
        var qty = qtyInput.val();
        var isChecked = $(this).is(':checked');
        
        // Include data for both checked and unchecked items
        dataToSend[itemName] = { checked: isChecked, qty: qty };
    });
    console.log(dataToSend);
    $.ajax({
        url: '{{ route('approvalsinspection.updateextraitems') }}',
        type: 'POST',
        data: dataToSend,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alertify.success('Extra Items Updated');
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(error) {
            alert('Error saving data.');
        }
    });
}
function saveincidents() {
        var formData = {
            incidentType: $("#incidentType").val(),
            narration: $("#narration").val(),
            damageDetails: $("#damageDetails").val(),
            drivenBy: $("#drivenBy").val(),
            Incidentid: $("#Incidentid").val(),
            responsibility: $("#responsibility").val(),
            reasons: []
        };
        $("#incidentreport input[type=checkbox]:checked").each(function() {
            formData.reasons.push($(this).attr("name"));
        });
        $.ajax({
            type: "POST",
            url: "{{ route('approvalsinspection.updateincident') }}",
            data: formData,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
    function savevariantsd() {
    var selectedVariantType = $("#newvariant input[name='variantType']:checked").val();
    var formData = {
        variantType: selectedVariantType,
        variantname: $("#newvariantname").val(),
        modeldetail: $("#newmodeldetail").val(),
        my: $("#newmy").val(),
        inspection_id: $("#inspection_id").val(),
        detail: $("#newdetail").val(),
        engine: $("#newengine").val(),
        gearbox: $("select[name='newgearbox']").val(),
        fueltype: $("select[name='newfuel_type']").val(),
        steering: $("select[name='newsteering']").val(),
        seat: $("select[name='newseat']").val(),
        upholestry: $("select[name='newupholestry']").val(),
        remark: $("#editor").val(),
        variant: $("#variant").val(),
        newvariantid: $("#newvariantid").val(),
    };
    console.log(formData);
    checkVariantName();
  const errorMessage = validationMessage.querySelector('.text-danger');
  if (errorMessage) {
    alertify.error('Variant Already Existing');
    return;
  }
    $.ajax({
        type: "POST",
        url: "{{ route('approvalsinspection.savevariantsd') }}",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alertify.success('Variant Updated');
            setTimeout(function() {
        window.location.reload();
        }, 1000);
        },
        error: function(xhr, status, error) {
            setTimeout(function() {
        window.location.reload();
        }, 1000);
        }
    });
}
</script>
<script>
  const variantInput = document.getElementById('newvariantname');
  variantInput.addEventListener('input', checkVariantName);
  const validationMessage = document.getElementById('validation-message');
  function checkVariantName() {
    const variantName = variantInput.value;
    fetch(`/check-org-variant?name=${encodeURIComponent(variantName)}`, {
      method: 'GET',
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.exists) {
        validationMessage.innerHTML = '<p class="text-danger">Variant name already exists in the system</p>';
      } else {
        validationMessage.innerHTML = '<p class="text-success">Variant name is available</p>';
      }
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  }
  function setButtonValue(value) {
    document.getElementById('buttonValue').value = value;
    if (value === 'approve') {
        if (confirm('Are you sure you want to approve?')) {
            document.getElementById('approveForm').submit();
        }
    } else if (value === 'reinspect') {
        if (confirm('Are you sure you want to request reinspection?')) {
            document.getElementById('approveForm').submit();
        }
    }
}
</script>
@endpush