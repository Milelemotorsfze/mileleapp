@extends('layouts.main')
<style>
   #interior_colour-error {
        margin-top:10px !important;
    }
    #exterior_colour-error {
        margin-top:10px !important;
    }
    </style>
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
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

                @if (Session::has('error'))
                    <div class="alert alert-danger mt-3 mb-0" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                    <div class="card-body">
                    <div class="modal fade optionsmodal-modal" id="optionsmodal" tabindex="-1" aria-labelledby="optionsmodalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="optionsmodalLabel">Update Options</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <div class="col-lg-12">
                                        <div class="row">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label class="form-label font-size-13 text-center">New Option Name</label>
                                        </div>
                                        <div class="col-lg-8 col-md-12 col-sm-12">
                                            <input type="text" class="form-label" name="option_name" id="option_name" />
                                            <input type ="hidden" name="specification-id-input" id="specification-id-input" />
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="savenewoptions()" id="btn-save">Save</button>
                                </div>
                                </div>
                            </div>
                            </div>
<form id="inspection-form" action="{{ route('inspection.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
             @method('PUT')
            @csrf
                            <div class="row">
                            <input type="hidden" name="brands_id" value="{{ $brandname->id }}">
                            <input type="hidden" name="master_model_lines_id" value="{{ $model_line->id }}">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Brand</label>
                                    <select class="form-control" autofocus name="brands_id" id="brand" disabled>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $brand->id == $brandname->id ? 'selected' : '' }}>
                                        {{ $brand->brand_name }}
                                    </option>
                                @endforeach
                                    </select> 
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Model Line</label>
                                    <select class="form-control" autofocus name="master_model_lines_id" id="model" disabled>
                                    <option value="" disabled selected>Select a Model Line</option>
                                    @foreach($masterModelLines as $masterModelLine)
                                    <option value="{{ $masterModelLine->id }}" {{ $masterModelLine->id == $model_line->id ? 'selected' : '' }}>
                                        {{ $masterModelLine->model_line }}
                                    </option>
                                @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Interior Colour</label>
                                    <select class="form-control" required autofocus name="int_colour" id="interior_colour">
                                    <option value="" disabled selected>Select a Colour</option>
                                    @foreach($int_colours as $int_colours)
                                    <option value="{{ $int_colours->id }}">
                                        {{ $int_colours->name }}
                                    </option>
                                @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" required class="form-label">Exterior Colour</label>
                                    <select class="form-control" autofocus  name="ex_colour" id="exterior_colour">
                                    <option value="" disabled selected>Select a Colour</option>
                                    @foreach($ext_colours as $ext_colours)
                                    <option value="{{ $ext_colours->id }}">
                                        {{ $ext_colours->name }}
                                    </option>
                                @endforeach
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
                                            <option value="{{ $year }}" {{ old('my') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    @php
    // Initialize formattedDate as an empty string
    $formattedDate = '';

    // Check if $vehicle->ppmmyyy exists and matches the expected format
    if (!empty($vehicle->ppmmyyy)) {
        try {
            // Attempt to parse 'Mar-2021' format
            $date = \Carbon\Carbon::createFromFormat('M-Y', $vehicle->ppmmyyy);
            $formattedDate = $date->format('Y-m');
        } catch (\Exception $e) {
            // Handle invalid date format gracefully
            $formattedDate = '';
        }
    }
@endphp
<div class="col-lg-2 col-md-6 col-sm-12">
    <div class="mb-3">
        <label for="production-date" class="form-label">Production Date</label>
        <input  type="month"  id="production-date" name="ppmmyyy"  class="form-control" value="{{ $formattedDate }}" >
    </div>
</div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Gear</label>
                                    <select class="form-control" autofocus name="gearbox" id="gear">
                                        <option value="AT" {{ old('gearbox') == 'AT' ? 'selected' : '' }}>AT</option>
                                        <option value="MT" {{ old('gearbox') == 'MT' ? 'selected' : '' }}>MT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Fuel Type</label>
                                    <select class="form-control" autofocus name="fuel_type" id="fuel">
                                        <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="PH" {{ old('fuel_type') == 'PH' ? 'selected' : '' }}>PH</option>
                                        <option value="PHEV" {{ old('fuel_type') == 'PHEV' ? 'selected' : '' }}>PHEV</option>
                                        <option value="MHEV" {{ old('fuel_type') == 'MHEV' ? 'selected' : '' }}>MHEV</option>
                                        <option value="EV" {{ old('fuel_type') == 'EV' ? 'selected' : '' }}>EV</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Engine</label>
                                    <select class="form-control" autofocus name="engine" id="engine">
                                        <option value="" {{ old('engine') == '' ? 'selected' : '' }}>Please Select The Engine Capacity</option>
                                        <option value="0.8" {{ old('engine') == '0.8' ? 'selected' : '' }}>0.8</option>
                                        <option value="1.0" {{ old('engine') == '1.0' ? 'selected' : '' }}>1.0</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.2</option>
                                        <option value="1.2" {{ old('engine') == '1.2' ? 'selected' : '' }}>1.3</option>
                                        <option value="1.4" {{ old('engine') == '1.4' ? 'selected' : '' }}>1.4</option>
                                        <option value="1.5" {{ old('engine') == '1.5' ? 'selected' : '' }}>1.5</option>
                                        <option value="1.6" {{ old('engine') == '1.6' ? 'selected' : '' }}>1.6</option>
                                        <option value="1.8" {{ old('engine') == '1.8' ? 'selected' : '' }}>1.8</option>
                                        <option value="2.0" {{ old('engine') == '2.0' ? 'selected' : '' }}>2.0</option>
                                        <option value="2.2" {{ old('engine') == '2.2' ? 'selected' : '' }}>2.2</option>
                                        <option value="2.4" {{ old('engine') == '2.4' ? 'selected' : '' }}>2.4</option>
                                        <option value="2.5" {{ old('engine') == '2.5' ? 'selected' : '' }}>2.5</option>
                                        <option value="2.7" {{ old('engine') == '2.7' ? 'selected' : '' }}>2.7</option>
                                        <option value="2.8" {{ old('engine') == '2.8' ? 'selected' : '' }}>2.8</option>
                                        <option value="3.0" {{ old('engine') == '3.0' ? 'selected' : '' }}>3.0</option>
                                        <option value="3.3" {{ old('engine') == '3.3' ? 'selected' : '' }}>3.3</option>
                                        <option value="3.4" {{ old('engine') == '3.4' ? 'selected' : '' }}>3.4</option>
                                        <option value="3.5" {{ old('engine') == '3.5' ? 'selected' : '' }}>3.5</option>
                                        <option value="3.6" {{ old('engine') == '3.6' ? 'selected' : '' }}>3.6</option>
                                        <option value="3.8" {{ old('engine') == '3.8' ? 'selected' : '' }}>3.8</option>
                                        <option value="4.0" {{ old('engine') == '4.0' ? 'selected' : '' }}>4.0</option>
                                        <option value="4.2" {{ old('engine') == '4.2' ? 'selected' : '' }}>4.2</option>
                                        <option value="4.4" {{ old('engine') == '4.4' ? 'selected' : '' }}>4.4</option>
                                        <option value="4.5" {{ old('engine') == '4.5' ? 'selected' : '' }}>4.5</option>
                                        <option value="4.6" {{ old('engine') == '4.6' ? 'selected' : '' }}>4.6</option>
                                        <option value="4.8" {{ old('engine') == '4.8' ? 'selected' : '' }}>4.8</option>
                                        <option value="5.0" {{ old('engine') == '5.0' ? 'selected' : '' }}>5.0</option>
                                        <option value="5.3" {{ old('engine') == '5.3' ? 'selected' : '' }}>5.3</option>
                                        <option value="5.5" {{ old('engine') == '5.5' ? 'selected' : '' }}>5.5</option>
                                        <option value="5.6" {{ old('engine') == '5.6' ? 'selected' : '' }}>5.6</option>
                                        <option value="5.7" {{ old('engine') == '5.7' ? 'selected' : '' }}>5.7</option>
                                        <option value="5.9" {{ old('engine') == '5.9' ? 'selected' : '' }}>5.9</option>
                                        <option value="6.0" {{ old('engine') == '6.0' ? 'selected' : '' }}>6.0</option>
                                        <option value="6.2" {{ old('engine') == '6.2' ? 'selected' : '' }}>6.2</option>
                                        <option value="6.7" {{ old('engine') == '6.7' ? 'selected' : '' }}>6.7</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Steering</label>
                                    <select class="form-control" autofocus name="steering" id="steering">
                                        <option value="LHD" {{ old('steering') == 'LHD' ? 'selected' : '' }}>LHD</option>
                                        <option value="RHD" {{ old('steering') == 'RHD' ? 'selected' : '' }}>RHD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Drive Train</label>
                                    <select class="form-control" autofocus name="drive_train" id="drive_train">
                                        <option value="AWD" {{ old('drive_train') == 'AWD' ? 'selected' : '' }}>AWD</option>
                                        <option value="4WD" {{ old('geadrive_trainrbox') == '4WD' ? 'selected' : '' }}>4WD</option>
                                        <option value="FWD" {{ old('geadrive_trainrbox') == 'FWD' ? 'selected' : '' }}>FWD</option>
                                        <option value="RWD" {{ old('geadrive_trainrbox') == 'RWD' ? 'selected' : '' }}>RWD</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="mb-3">
                                    <label for="choices-single-default" class="form-label">Upholstery</label>
                                    <select class="form-control" autofocus name="upholestry" id="upholstery">
                                        <option value="Leather" {{ old('upholstery') == 'Leather' ? 'selected' : '' }}>Leather</option>
                                        <option value="Fabric" {{ old('upholstery') == 'Fabric' ? 'selected' : '' }}>Fabric</option>
                                        <option value="Vinyl" {{ old('upholstery') == 'Vinyl' ? 'selected' : '' }}>Vinyl</option>
                                        <option value="Leather & Fabric" {{ old('upholstery') == 'Leather & Fabric' ? 'selected' : '' }}>Leather & Fabric</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="specification-details-container">
                            </div>
                            <input type="hidden" name="selected_model_id" id="selected_model_id">
                            <input type="hidden" name="selected_specifications" id="selected_specifications">
            </div>
            <hr>
                    <br>
                    <h4>Extra Items:</h4>
    <div class="row">
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_1" name="packing">
                    <label for="loss_item_1">Packing Box</label>
                    <input type="hidden" class="form-control" name="packing_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_2" name="warningtriangle">
                    <label for="loss_item_2">Warning Triangle</label>
                    <input type="hidden" class="form-control" name="warningtriangle_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_3" name="wheel">
                    <label for="loss_item_3">Jack & Wheel Spanner</label>
                    <input type="hidden" class="form-control" name="wheel_qty" placeholder="Qty">
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_4" name="firstaid">
                    <label for="loss_item_4">First Aid Kit</label>
                    <input type="hidden" class="form-control" name="firstaid_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_5" name="floor_mat">
                    <label for="loss_item_5">Floor Mat</label>
                    <input type="hidden" class="form-control" name="floor_mat_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_6" name="service_book">
                    <label for="loss_item_6">Service Book & Manual</label>
                    <input type="hidden" class="form-control" name="service_book_qty" placeholder="Qty">
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_7" name="keys">
                    <label for="loss_item_7">Keys</label>
                    <input type="text" class="form-control" name="keys_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_8" name="trunkcover">
                    <label for="loss_item_8">Trunk Cover</label>
                    <input type="hidden" class="form-control" name="trunkcover_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_9" name="fire_extinguisher">
                    <label for="loss_item_9">Fire Extinguisher</label>
                    <input type="hidden" class="form-control" name="fire_extinguisher_qty" placeholder="Qty">
                </li>
            </ul>
        </div>
    </div>
     </br>
     <hr>
        <input class="form-check-input" type="checkbox" id="enableInputsincludent" name="enableInputsincludent">
        <label class="form-check-label" for="enableInputsincludent">Check if Found Incident</label>
        <div id="incidentForm" style="display: none;">
        <br>
    <div class="row">
        <div class="col-md-4">
            <label for="incidentType">Incident Type</label>
            <select class="form-control" id="incidentType" name="incidenttype">
                <option value="" disabled selected>Select an Option</option>
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
                    <div class="row">
                        <div class="col-md-23">
                            <label>Extra Features / Items</label>
                            <input type="text" class="form-control" id="extra_features" name="extra_features">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
    </br>
        <div class="col-lg-12 col-md-12">
				    <input type="submit" id="submit-button" name="submit" value="Submit" class="btn btn-success btncenter" />
			        </div>  
    </form>
</div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#interior_colour').select2({
            placeholder: 'Select Interior Colour'
        }).on('change',function() {
            $('#interior_colour-error').remove();
        })
        $('#exterior_colour').select2({
            placeholder: 'Select Exterior Colour'
        }).on('change',function() {
            $('#exterior_colour-error').remove();
        });
       
        $('#enableInputs').change(function() {
            $('#vin, #variant, #newVariantDropdown, #interior_color, #exterior_color').prop('disabled', !this.checked);
        });
    });
</script>
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
        document.getElementById('submit-button').addEventListener('click', function() {
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
<script>
        $('#brand').select2({
            placeholder: 'Select Brand'
        })
        $('.coo').select2();
        
        $('#model').select2({
            placeholder: 'Select Model'
        })
        $('#brand').on('change',function() {
            $('#brand-error').remove();
        })
        $('#model').on('change',function() {
            $('#model-error').remove();
        })
        $("#inspection-form").validate({
            ignore: [],
            rules: {
                ex_colour:{
                    required:true,
                },
                int_colour:{
                    required:true,
                },
                ppmmyyy:{
                    required:true,
                }

            }
        });
    </script>
    <!-- <script>
    $(document).ready(function() {
        $('#brand').on('change', function() {
            $('#fuel, #coo, #steering, #gear, #drive_train, #my, #ex, #int, #engine, #Upholstery').hide();
            $('#specification-details-container').empty();
            var selectedBrandId = $(this).val();
            $.ajax({
                url: '/get-model-lines/' + selectedBrandId,
                type: 'GET',
                success: function(data) {
                    $('#model').empty();
                    $('#model').append('<option value="" disabled selected>Select a Model</option>');
                    $.each(data, function(index, modelLine) {
                        $('#model').append('<option value="' + modelLine.id + '">' + modelLine.model_line + '</option>');
                    });
                },
                error: function(error) {
                    console.log('Error fetching model lines:', error);
                }
            });
        });
    });
</script> -->
<script>
$(document).ready(function() {
    // Function to perform actions based on the selected model line
    function performModelActions(selectedModelLineId) {
        $('#fuel, #coo, #steering, #gear, #drive_train, #my, #ex, #int, #engine, #Upholstery').show();
        selectedSpecifications = [];

        $.ajax({
            type: 'GET',
            url: '/getSpecificationDetails/' + selectedModelLineId,
            success: function(response) {
                var data = response.data;
                $('#specification-details-container').empty();
                var selectedSpecifications = [];

                data.forEach(function(item) {
                    var specification = item.specification;
                    var options = item.options;
                    if (options.length > 0) {
                    var select = $('<select class="form-control" name="specification_' + specification.id + '"data-specification-id="' + specification.id + '">');
                    select.append('<option value="" disabled selected>Select an Option</option>');

                    options.forEach(function(option) {
                        select.append('<option value="' + option.id + '">' + option.name + '</option>');
                    });
                    var addButton = $('<a><button class="btn btn-primary btn-sm ml-2">+</button></a>');
                    addButton.on('click', function (event) {
                        event.preventDefault();
                        $('#specification-id-input').val(specification.id);
                        $('#optionsmodal').modal('show');
                    });
                    var selectContainer = $('<div class="d-flex align-items-center"></div>');
                    selectContainer.append(select).append(addButton);
                    select.on('change', function() {
                        var selectedValue = $(this).val();
                        selectedSpecifications.push({
                            specification_id: specification.id,
                            value: selectedValue
                        });
                        $('#selected_specifications').val(JSON.stringify(selectedSpecifications));
                    });

                    var specificationColumn = $('<div class="col-lg-4 mb-3">');
                    specificationColumn.append('<label class="form-label">' + specification.name + '</label');
                    specificationColumn.append(selectContainer);
                    $('#specification-details-container').append(specificationColumn);
                }
                });
            }
        });

        $('#selected_model_id').val(selectedModelLineId);
    }

    // Call the function on page load
    performModelActions($('#model').val());

    // Bind the function to the change event of the model dropdown
    $('#model').on('change', function() {
        performModelActions($(this).val());
    });
});
</script>
<script>
    function savenewoptions() {
        var specificationId = $('#specification-id-input').val();
        var newOptionValue = $('#option_name').val();
        $.ajax({
            url: '{{ route('variants.saveOption') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                specificationId: specificationId,
                newOption: newOptionValue
            },
            success: function (response) {
                var option = '<option value="' + response.option.id + '">' + response.option.name + '</option>';
                $('[data-specification-id="' + specificationId + '"]').append(option);
                alertify.success('Specification Option successfully Added');
                $('#optionsmodal').modal('hide');
            }
        });
    }
    </script>
@endpush
