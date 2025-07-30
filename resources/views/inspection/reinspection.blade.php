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
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <form id="inspection-form" action="{{ route('inspection.reupdate', $inspection->id) }}" method="POST" enctype="multipart/form-data">
             @method('PUT')
            @csrf
            <h5>Trim Specifications</h5>
<br>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Items</th>
                <th>Inspection Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Brand</td>
                <td>
                    <select class="form-control" name="brands_id" disabled>
                    @foreach($allBrands as $singleBrand)
                    <option value="{{$singleBrand->id}}" @if($singleBrand->id == $brands->id) selected @endif>
                    {{$singleBrand->brand_name}}
                    </option>
                    @endforeach
                 </select>
                </td>
            </tr>
            <tr>
                <td>Model Line</td>
                <td>
                    <select class="form-control" name="master_model_lines_id" disabled>
                    @foreach($model_lines as $models)
                    <option value="{{$models->id}}" @if($models->id == $modal->id) selected @endif>
                    {{$models->model_line}}
                    </option>
                    @endforeach
                 </select>
                </td>
            </tr>
            <tr>
    <td>Steering</td>
    <td>
        <select class="form-control" name="steering">
            <option value="LHD" @if($variant_request->steering == 'LHD') selected @endif>LHD</option>
            <option value="RHD" @if($variant_request->steering == 'RHD') selected @endif>RHD</option>
        </select>
    </td>
</tr>

            <tr>
                <td>Engine</td>
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
                    <option value="3.4" @if($variant_request->engine == '3.4') selected @endif>3.4</option>
                    <option value="3.5" @if($variant_request->engine == '3.5') selected @endif>3.5</option>
                    <option value="3.6" @if($variant_request->engine == '3.6') selected @endif>3.6</option>
                    <option value="3.8" @if($variant_request->engine == '3.8') selected @endif>3.8</option>
                    <option value="4" @if($variant_request->engine == '4') selected @endif>4</option>
                    <option value="4.2" @if($variant_request->engine == '4.2') selected @endif>4.2</option>
                    <option value="4.4" @if($variant_request->engine == '4.4') selected @endif>4.4</option>
                    <option value="4.5" @if($variant_request->engine == '4.5') selected @endif>4.5</option>
                    <option value="4.8" @if($variant_request->engine == '4.8') selected @endif>4.8</option>
                    <option value="5.0" @if($variant_request->engine == '5.0') selected @endif>5.0</option>
                    <option value="5.3" @if($variant_request->engine == '5.3') selected @endif>5.3</option>
                    <option value="5.5" @if($variant_request->engine == '5.5') selected @endif>5.5</option>
                    <option value="5.6" @if($variant_request->engine == '5.6') selected @endif>5.6</option>
                    <option value="5.7" @if($variant_request->engine == '5.7') selected @endif>5.7</option>
                    <option value="6" @if($variant_request->engine == '6') selected @endif>6</option>
                    <option value="6.2" @if($variant_request->engine == '6.2') selected @endif>6.2</option>
                    <option value="6.7" @if($variant_request->engine == '6.7') selected @endif>6.7</option>
                 </select>
                </td>
            </tr>
            <tr>
                <td>Fuel Type</td>
                <td>
                    <select class="form-control" name="fuel_type">
                    <option value="Petrol" @if($variant_request->fuel_type == 'Petrol') selected @endif>Petrol</option>
                    <option value="Diesel" @if($variant_request->fuel_type == 'Diesel') selected @endif>Diesel</option>
                    <option value="PHEV" @if($variant_request->fuel_type == 'PHEV') selected @endif>PHEV</option>
                    <option value="MHEV" @if($variant_request->fuel_type == 'MHEV') selected @endif>MHEV</option>
                    <option value="EV" @if($variant_request->fuel_type == 'EV') selected @endif>EV</option>
                 </select>
                </td>
            </tr>
            <tr>
                <td>Upholstery</td>
                <td>
                    <select class="form-control" name="upholestry">
                    <option value="Leather" @if($variant_request->upholestry == 'Leather') selected @endif>Leather</option>
                    <option value="Fabric" @if($variant_request->upholestry == 'Fabric') selected @endif>Fabric</option>
                    <option value="Vinyl" @if($variant_request->upholestry == 'Vinyl') selected @endif>Vinyl</option>
                    <option value="Microfibre" @if($variant_request->upholestry == 'Microfibre') selected @endif>Microfibre</option>
                 </select>
                </td>
            </tr>
            <tr>
                <td>Gear</td>
                <td>
                    <select class="form-control" name="gearbox">
                    <option value="Auto" @if($variant_request->gearbox == 'AT') selected @endif>Auto</option>
                    <option value="Manual" @if($variant_request->gearbox == 'MT') selected @endif>Manual</option>
                 </select>
                </td>
            </tr>
            <input type="hidden" name="coo" value="{{ $variant->coo }}"/>
            <input type="hidden" name="drive_train" value="{{ $variant->drive_train }}"/>
            <tr>
                <td>Model Year</td>
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
            </tr>
            <tr>
                <td>Interior Colour</td>
                <td>
                    <select class="form-control" name="int_colour">
                    @foreach($intColorall as $intColorall)
                    <option value="{{$intColorall->id}}" @if($intColorr && $intColorall->id == $intColorr->id) selected @endif>
                    {{$intColorall->name}}
                    </option>
                    @endforeach
                 </select>
                </td>
            </tr>
            <tr>
                <td>Exterior Colour</td>
                <td>
                    <select class="form-control" name="ex_colour">
                    @foreach($extColorall as $exColorall)
                    <option value="{{$exColorall->id}}" @if($extColorr && $exColorall->id == $extColorr->id) selected @endif>
                    {{$exColorall->name}}
                    </option>
                    @endforeach
                 </select>
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
                <label for="choices-single-default" class="form-label">{{ $item['specification']->name }}</label>
                <select class="form-control" autofocus name="specification_{{ $item['specification']->id }}">
                    {{-- Add a default option if the specification is not selected --}}
                    @if ($item['selected_option_id'] === null)
                        <option value="" disabled selected>Select an Option</option>
                    @endif

                    {{-- Display options --}}
                    @foreach($item['options'] as $option)
                        <option value="{{ $option->id }}" {{ old('specification_' . $item['specification']->id) == $option->id || $item['selected_option_id'] == $option->id ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endforeach
</div>
<hr>
            <h5>Inspection Updates</h5>
            <div class="row">
@if($extra_featuresvalue)
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Extra Features</strong></label>
</div>
<div class="col-md-8">
                    {{$extra_featuresvalue ?? ''}}
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
<hr>
<h5>Manager Remarks</h5>
<div class="row">
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Checking Date</strong></label>
</div>
<div class="col-md-8">
                    {{$inspection->processing_date ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Remarks</strong></label>
</div>
<div class="col-md-8">
                    {{$inspection->process_remarks ?? ''}}
</div>
</div>
</div>
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
            <input type="text" id="vehicle_id" value="{{ $inspection->vehicle_id }}">
            <li class="list-group-item">
    @if ($extraItems->contains('item_name', 'sparewheel'))
        <input type="checkbox" id="sparewheel" name="sparewheel" checked>
        <label for="sparewheel">Spare Wheel</label>
        <input type="hidden" class="form-control" type="number" name="sparewheel_qty" value="{{ $extraItems->where('item_name', 'sparewheel')->first()->qty }}" placeholder="Qty">
    @else
        <input type="checkbox" id="sparewheel" name="sparewheel">
        <label for="sparewheel">Spare Wheel</label>
        <input type="hidden" class="form-control" type="number" name="sparewheel_qty" placeholder="Qty">
    @endif
</li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'jack'))
                    <input type="checkbox" id="jack" name="jack" checked>
                    <label for="jack">Jack</label>
                    <input type="hidden" class="form-control" type="number" name="jack_qty" value="{{ $extraItems->where('item_name', 'jack')->first()->qty }}" placeholder="Qty">
                @else
                <input type="checkbox" id="jack" name="jack">
                    <label for="jack">Jack</label>
                    <input type="hidden" class="form-control" type="number" name="jack_qty" placeholder="Qty">
                @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'wheel'))
                    <input type="checkbox" id="wheel" name="wheel" checked>
                    <label for="wheel">Wheel Spanner</label>
                    <input type="hidden" class="form-control" type="number" name="wheel_qty" value="{{ $extraItems->where('item_name', 'wheel')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="wheel" name="wheel">
                    <label for="wheel">Wheel Spanner</label>
                    <input  type="hidden" class="form-control" type="number" name="wheel_qty" placeholder="Qty">
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
                    <input type="hidden" class="form-control" type="number" name="firstaid_qty" value="{{ $extraItems->where('item_name', 'firstaid')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="firstaid" name="firstaid">
                    <label for="firstaid">First Aid Kit / Packing Box</label>
                    <input  type="hidden" class="form-control" type="number" name="firstaid_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'floor_mat'))
                    <input type="checkbox" id="floor_mat" name="floor_mat" checked>
                    <label for="floor_mat">Floor Mat</label>
                    <input type="hidden" class="form-control" type="number" name="floor_mat_qty" value="{{ $extraItems->where('item_name', 'floor_mat')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="floor_mat" name="floor_mat">
                    <label for="floor_mat">Floor Mat</label>
                    <input type="hidden" class="form-control" type="number" name="floor_mat_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'service_book'))
                    <input type="checkbox" id="service_book" name="service_book" checked>
                    <label for="service_book">Service Book & Manual</label>
                    <input type="hidden" class="form-control" type="number" name="service_book_qty" value="{{ $extraItems->where('item_name', 'service_book')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="service_book" name="service_book">
                    <label for="service_book">Service Book & Manual</label>
                    <input type="hidden" class="form-control" type="number" name="service_book_qty" placeholder="Qty">
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
                    <input class="form-control" type="number" name="keys_qty" value="{{ $extraItems->where('item_name', 'keys')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="keys" name="keys">
                    <label for="keys">Keys / Qty</label>
                    <input class="form-control" type="number" name="keys_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'wheelrim'))
                    <input type="checkbox" id="wheelrim" name="wheelrim" checked>
                    <label for="wheelrim">Wheel Rim / Tyres</label>
                    <input type="hidden" class="form-control" type="number" name="wheelrim_qty" value="{{ $extraItems->where('item_name', 'wheelrim')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="wheelrim" name="wheelrim">
                    <label for="wheelrim">Wheel Rim / Tyres</label>
                    <input type="hidden" class="form-control" type="number" name="wheelrim_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'fire_extinguisher'))
                    <input type="checkbox" id="fire_extinguisher" name="fire_extinguisher" checked>
                    <label for="fire_extinguisher">Fire Extinguisher</label>
                    <input type="hidden" class="form-control" type="number" name="fire_extinguisher_qty" value="{{ $extraItems->where('item_name', 'fire_extinguisher')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="fire_extinguisher" name="fire_extinguisher">
                    <label for="fire_extinguisher">Fire Extinguisher</label>
                    <input type="hidden" class="form-control" type="number" name="fire_extinguisher_qty" placeholder="Qty">
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
                    <input type="hidden" class="form-control" type="number" name="sd_card_qty" value="{{ $extraItems->where('item_name', 'sd_card')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="sd_card" name="sd_card">
                    <label for="sd_card">SD Card / Remote / H Phones</label>
                    <input type="hidden" class="form-control" type="number" name="sd_card_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'ac_system'))
                    <input type="checkbox" id="ac_system" name="ac_system" checked>
                    <label for="ac_system">A/C System</label>
                    <input type="hidden" class="form-control" type="number" name="ac_system_qty" value="{{ $extraItems->where('item_name', 'ac_system')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="ac_system" name="ac_system">
                    <label for="ac_system">A/C System</label>
                    <input type="hidden" class="form-control" type="number" name="ac_system_qty" placeholder="Qty">
                    @endif
                </li>
                </div>
                <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'dash_board'))
                    <input type="checkbox" id="dash_board" name="dash_board" checked>
                    <label for="dash_board">Dash Board / T Screen / LCD</label>
                    <input type="hidden" class="form-control" type="number" name="dash_board_qty" value="{{ $extraItems->where('item_name', 'dash_board')->first()->qty }}" placeholder="Qty">
                    @else
                    <input type="checkbox" id="dash_board" name="dash_board">
                    <label for="dash_board">Dash Board / T Screen / LCD</label>
                    <input type="hidden" class="form-control" type="number" name="dash_board_qty" placeholder="Qty">
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
                    @if ($extraItems->contains('item_name', 'packing'))
                        <i class="fas fa-check-circle text-success"></i>
                        <span>{{ $extraItems->where('item_name', 'packing')->first()->qty }}</span>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                    <label for="loss_item_1">Packing Box</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'warningtriangle'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'warningtriangle')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_2">Warning Triangle</label>
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
                    <label for="loss_item_3">Jack & Wheel Spanner</label>
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
                    <span>{{ $extraItems->where('item_name', 'keys')->first()->qty }} Qty - </span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_7">Keys / Qty</label>
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                @if ($extraItems->contains('item_name', 'trunkcover'))
                    <i class="fas fa-check-circle text-success"></i>
                    <span>{{ $extraItems->where('item_name', 'trunkcover')->first()->qty }}</span>
                @else
                    <i class="fas fa-times-circle text-danger"></i>
                @endif
                    <label for="loss_item_8">Trunk Cover</label>
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
    </div>
     </br>
@if($Incident)
<br>
<hr>
<form id="incidentForm">
  @csrf
    <div class="modal fade incidentreport-modal" id="incidentreport" tabindex="-1" aria-labelledby="incidentreportLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
            <select class="form-control" id="incidentType" name="incidentType">
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
          <div class="row">
    <input type="hidden" id="canvas-image" name="canvas_image" />
    <div id="canvas-container" style="margin-top: 20px;"></div>
    <button type="button" id="reset-button" class="btn btn-secondary btncenter">Reset</button>
</div>
    </div>
        </div>
        <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button type="button" id="save-button" class="btn btn-primary">Save Changes</button>
    </div>
      </div>
    </div>
  </div>
  </form>
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
            @if(!$Incident)
     <hr>
        <input class="form-check-input" type="checkbox" id="enableInputsincludent" name="enableInputsincludent">
        <label class="form-check-label" for="enableInputsincludent">Check if Found Incident</label>
        <div id="incidentForm" style="display: none;">
        <br>
    <div class="row">
        <div class="col-md-4">
            <label for="incidentType">Incident Type</label>
            <select class="form-control" id="incidentType" name="incidenttype">
                <option value="Electrical">Electrical</option>
                <option value="Machinical">Machinical</option>
                <option value="Accident">Accident</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="narration">Narration of Accident / Damage</label>
            <input type="text" class="form-control" id="narration" name="narration">
        </div>
        <div class="col-md-4">
            <label for="damageDetails">Damage Details</label>
            <input type="text" class="form-control" id="damageDetails" name="damageDetails">
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <label for="drivenBy">Driven By</label>
            <input type="text" class="form-control" id="drivenBy" name="drivenBy">
        </div>
        <div class="col-md-4">
            <label for="responsibility">Responsibility for Recover the Damages</label>
            <input type="text" class="form-control" id="responsibility" name="responsibility">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="overspeed" name="overspeed">
                    <label for="overspeed">Over-Speed</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="weather" name="weather">
                    <label for="weather">Weather Comditions</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="vehicle_defects" name="vehicle_defects">
                    <label for="vehicle_defects">Vehicle Defects</label>
                </li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="negligence" name="negligence">
                    <label for="negligence">Negligence</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="sudden_halt" name="sudden_halt">
                    <label for="sudden_halt">Sudden Halt</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="road_defects" name="road_defects">
                    <label for="road_defects">Road Defects</label>
                </li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="fatigue" name="fatigue">
                    <label for="fatigue">Fatigue</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="no_safety_distance" name="no_safety_distance">
                    <label for="no_safety_distance">No Safety Distance</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="using_gsm" name="using_gsm">
                    <label for="using_gsm">Using GSM</label>
                </li>
            </ul>
        </div>
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="overtaking" name="overtaking">
                    <label for="overtaking">Overtaking</label>
                </li>
                <li class="list-group-item">
                    <input type="checkbox" id="wrong_action" name="wrong_action">
                    <label for="wrong_action">Wrong Action</label>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
    <div class="col-md-12">
    <input type="hidden" id="canvas-image" name="canvas_image" />
    <div id="canvas-container" style="margin-top: 20px;"></div>
    <button type="button" id="reset-button" class="btn btn-secondary btncenter">Reset</button>
    </div>
</div>
</div>
     </br>
     </br>
     @endif
    <div class="form-group">
        <h5>Inspection Remarks</h5>
        <textarea id="process_remarks" name="process_remarks" class="form-control" rows="4" placeholder="Enter Remarks here"></textarea>
    </div>
</br>
    <div class="col-lg-12 col-md-12">
				    <input type="submit" id="submit-buttons" name="submit" value="Update" class="btn btn-success btncenter" />
			        </div>
</form>
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
    var inspection_id = $('#inspection_id').val();
    var vin = $('#vin').val();
    var int_colour = $('#int_colour').val();
    var ex_colour = $('#ex_colour').val();
    var extra_features = $('#extra_features').val();
    var remark = $('#remarks').val();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var data = {
        engine: engine,
        vin: vin,
        int_colour: int_colour,
        ex_colour: ex_colour,
        inspection_id: inspection_id,
        extra_features: extra_features,
        remark: remark,
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
            alertify.success('Inspection Updated');
        setTimeout(function() {
            location.reload();
        }, 1000);
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
$(document).ready(function () {
    $("#save-button").click(function () {
        var canvasImageDataURL = $("#canvas-image").val();
        var formData = new FormData($("#incidentForm")[0]);
        var reasons = [];
        $("#incidentreport input[type=checkbox]:checked").each(function() {
            reasons.push($(this).attr("name"));
        });
        for (var i = 0; i < reasons.length; i++) {
            formData.append('reasons[]', reasons[i]);
        }
        formData.append('canvas_image', canvasImageDataURL);
        $.ajax({
            type: "POST",
            url: "{{ route('approvalsinspection.updateincident') }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alertify.success('Incident Report Updated');
                location.reload();
            },
            error: function(error) {
                console.error(error);
            }
        });
    });
});
</script>
@if($Incident)
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var stage = new Konva.Stage({
        container: 'canvas-container',
        width: window.innerWidth,
        height: window.innerHeight,
    });
    var layer = new Konva.Layer();
    stage.add(layer);
    var isDrawing = false;
    var lastLine;

    stage.on('mousedown touchstart', function(e) {
        if (isDrawing) return;
        isDrawing = true;
        var pos = stage.getPointerPosition();
        lastLine = new Konva.Line({
            stroke: 'black',
            strokeWidth: 2,
            points: [pos.x, pos.y],
        });

        layer.add(lastLine);
    });

    stage.on('mousemove touchmove', function() {
        if (!isDrawing) return;

        var pos = stage.getPointerPosition();
        var newPoints = lastLine.points().concat([pos.x, pos.y]);
        lastLine.points(newPoints);
        layer.batchDraw();
    });

    stage.on('mouseup touchend', function() {
        isDrawing = false;
    });

    var backgroundImage = new Image();
    backgroundImage.src = '{{ asset('mm.jpg') }}';
    backgroundImage.onload = function() {
        var aspectRatio = backgroundImage.width / backgroundImage.height;
        var maxWidth = window.innerWidth * 0.4;
        var maxHeight = window.innerHeight * 0.4;
        var canvasWidth = maxWidth;
        var canvasHeight = canvasWidth / aspectRatio;

        if (canvasHeight > maxHeight) {
            canvasHeight = maxHeight;
            canvasWidth = canvasHeight * aspectRatio;
        }

        stage.width(canvasWidth);
        stage.height(canvasHeight);
        var background = new Konva.Image({
            image: backgroundImage,
            x: 0,
            y: 0,
            width: canvasWidth,
            height: canvasHeight,
        });

        layer.add(background);
        layer.draw();
    };

    window.addEventListener('resize', function() {
        var canvasWidth = window.innerWidth;
        var canvasHeight = stage.height() * (canvasWidth / stage.width());

        if (canvasHeight > window.innerHeight) {
            canvasHeight = window.innerHeight;
            canvasWidth = canvasHeight * (stage.width() / stage.height());
        }

        stage.width(canvasWidth);
        stage.height(canvasHeight);
        layer.batchDraw();
    });

    var form = document.getElementById('incidentForm');
    
    // Capture and set the canvas image data before form submission
    document.getElementById('save-button').addEventListener('click', function() {
        var canvas = stage.toDataURL();
        document.getElementById('canvas-image').value = canvas;
        form.submit();
    });

    document.getElementById('reset-button').addEventListener('click', function() {
        layer.destroy();
        layer = new Konva.Layer();
        stage.add(layer);
        var background = new Konva.Image({
            image: backgroundImage,
            x: 0,
            y: 0,
            width: stage.width(),
            height: stage.height(),
        });

        layer.add(background);
        layer.batchDraw();
    });
});
</script>
@endif
@if(!$Incident)
<script>
    var showFormCheckbox = document.getElementById("enableInputsincludent");
    var incidentForm = document.getElementById("incidentForm");
    showFormCheckbox.addEventListener("change", function () {
        if (showFormCheckbox.checked) {
            incidentForm.style.display = "block";
        } else {
            incidentForm.style.display = "none";
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var stage = new Konva.Stage({
            container: 'canvas-container',
            width: window.innerWidth,
            height: window.innerHeight,
        });
        var layer = new Konva.Layer();
        stage.add(layer);
        var isDrawing = false;
        var lastLine;
        
        stage.on('mousedown touchstart', function(e) {
            if (isDrawing) return;
            isDrawing = true;
            var pos = stage.getPointerPosition();
            lastLine = new Konva.Line({
                stroke: 'black',
                strokeWidth: 2,
                points: [pos.x, pos.y],
            });

            layer.add(lastLine);
        });

        stage.on('mousemove touchmove', function() {
            if (!isDrawing) return;

            var pos = stage.getPointerPosition();
            var newPoints = lastLine.points().concat([pos.x, pos.y]);
            lastLine.points(newPoints);
            layer.batchDraw();
        });

        stage.on('mouseup touchend', function() {
            isDrawing = false;
        });

        var backgroundImage = new Image();
        backgroundImage.src = '{{ asset('mm.jpg') }}';
        backgroundImage.onload = function() {
            var aspectRatio = backgroundImage.width / backgroundImage.height;
            var maxWidth = window.innerWidth * 0.8;
            var maxHeight = window.innerHeight * 0.6;
            var canvasWidth = maxWidth;
            var canvasHeight = canvasWidth / aspectRatio;

            if (canvasHeight > maxHeight) {
                canvasHeight = maxHeight;
                canvasWidth = canvasHeight * aspectRatio;
            }

            stage.width(canvasWidth);
            stage.height(canvasHeight);
            var background = new Konva.Image({
                image: backgroundImage,
                x: 0,
                y: 0,
                width: canvasWidth,
                height: canvasHeight,
            });

            layer.add(background);
            layer.draw();
        };

        window.addEventListener('resize', function() {
            var canvasWidth = window.innerWidth;
            var canvasHeight = stage.height() * (canvasWidth / stage.width());

            if (canvasHeight > window.innerHeight) {
                canvasHeight = window.innerHeight;
                canvasWidth = canvasHeight * (stage.width() / stage.height());
            }

            stage.width(canvasWidth);
            stage.height(canvasHeight);
            layer.batchDraw();
        });
        var form = document.getElementById('inspection-form');
        document.getElementById('submit-buttons').addEventListener('click', function() {
            var canvas = stage.toDataURL();
            document.getElementById('canvas-image').value = canvas;
            form.submit();
        });
        document.getElementById('reset-button').addEventListener('click', function() {
            layer.destroy();
            layer = new Konva.Layer();
            stage.add(layer);
            var background = new Konva.Image({
                image: backgroundImage,
                x: 0,
                y: 0,
                width: stage.width(),
                height: stage.height(),
            });

            layer.add(background);
            layer.batchDraw();
        });
    });
</script>
@endif
@endpush