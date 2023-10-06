@extends('layouts.main')
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
<div class="card-body">
        <div class="row">
        <div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Brand</strong></label>
</div>
<div class="col-md-8">
                    {{$brand->brand_name ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Model Line</strong></label>
</div>
<div class="col-md-8">
                    {{$model_line->model_line ?? ''}}
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Model Detail</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->model_detail ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Variant</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->name ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Model Year</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->my ?? ''}}
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Variant Detail</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->detail ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Steering</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->steering ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Seats</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->seat ?? ''}}
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Fuel Type</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->fuel_type ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Transmission</strong></label>
</div>
<div class="col-md-8">
                    {{$variant->gearbox ?? ''}}
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Production Year</strong></label>
</div>
<div class="col-md-8">
                    {{$vehicle->ppmmyyy ?? ''}}
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Interior Color</strong></label>
</div>
<div class="col-md-8">
                    {{$intColor->name ?? ''}}
</div>
</div>
</div>
            <div class="col-md-3">
            <div class="row">
            <div class="col-md-4">
                                <label><strong>Exterior Color</strong></label>
            </div>
            <div class="col-md-8">
                            {{$extColor->name ?? ''}}
            </div>
            </div>
            </div>
            </div>
            <hr>
            <div class="modal fade" id="newVariantModal" tabindex="-1" aria-labelledby="newVariantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newVariantModalLabel">Add New Variant</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Variant Name</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="variantname" value="">
            <input type="hidden" class="form-control" id="brands_id" value="{{$variant->brands_id}}">
            <input type="hidden" class="form-control" id="master_model_lines_id" value="{{$variant->master_model_lines_id}}">
            <div id="validation-message"></div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="date" class="form-label">Model Description</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="model_description" value="">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="deal-value-input" class="form-label">Model Year:</label>
            </div>
            <div class="col-md-8">
              <div class="input-group">
              @php
                            $currentYear = date("Y");
                            $years = range($currentYear + 10, $currentYear - 10);
                            $years = array_reverse($years);
                            @endphp
                            <select name="my" class="form-control">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Variant Details:</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="detail" value="">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Engine Capacity:</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="form-control" id="engine" value="">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Transmission:</label>
            </div>
            <div class="col-md-8">
            <select class="form-control" autofocus name="gearbox" id="model">
                                <option value="Auto">Auto</option>
                                <option value="Manual">Manual</option>
                            </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Fuel Type:</label>
            </div>
            <div class="col-md-8">
            <select class="form-control" autofocus name="fuel_type" id="model">
                                <option value="Diesel">Diesel</option>
                                <option value="EV">EV</option>
                                <option value="Gasoline">Gasoline</option>
                            </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Steering:</label>
            </div>
            <div class="col-md-8">
            <select class="form-control" autofocus name="steering" id="model">
                                <option value="LHD">LHD</option>
                                <option value="RHD">RHD</option>
                            </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Seat Capacity:</label>
            </div>
            <div class="col-md-8">
            <select name="seat" class="form-control">
                                @for($i = 1; $i <= 50; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Upholstery:</label>
            </div>
            <div class="col-md-8">
            <select class="form-control" autofocus name="upholestry" id="model">
                                <option value="Fabric">Fabric</option>
                                <option value="Leather">Leather</option>
                                <option value="Fabric + Leather">Fabric + Leather</option>
                                <option value="Fabric / Leather">Fabric / Leather</option>
                                <option value="Vinyl">Vinyl</option>
                            </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="saveVariant()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>
            <form id="inspection-form" action="{{ route('inspection.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
             @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <input class="form-check-input" type="checkbox" id="enableInputs" name="enableInputs">
                    <label class="form-check-label" for="enableInputs">Check if Found Changes</label>
                    <br>
                    <div class="row">
                    <div class="col-md-4">
                            <label>Engine</label>
                            <input type="text" class="form-control" id="engine" name="engine">
                        </div>
                        <div class="col-md-4">
                            <label>VIN</label>
                            <input type="text" class="form-control" id="vin" name="vin" disabled>
                        </div>
                        <div class="col-md-4">
                            <label>Existing Variant</label>
                            <div class="input-group">
                            <select class="form-control" name="varaints_id" id="variant" onchange="toggleNewVariants()" disabled>
                                <option value="">Not Available</option>
                                @foreach ($variants as $variantOption)
                                    <option value="{{ $variantOption->id }}" @if ($variantOption->id === $variant->id) selected @endif>
                                        {{ $variantOption->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="#" onclick="openModal('{{ $vehicle->id }}')">+</a>
                            </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Interior Color</label>
                            <select class="form-control" name="interior_color" id="interior_color" disabled>
                            <option value="">Please Select</option>
                                @foreach ($int_colours as $int_colours)
                                    <option value="{{ $int_colours->id }}" @if ($int_colours->id === $vehicle->int_colour) selected @endif>
                                        {{ $int_colours->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Exterior Color</label>
                            <select class="form-control" name="exterior_color" id="exterior_color" disabled>
                            <option value="">Please Select</option>
                                @foreach ($ext_colours as $ext_colours)
                                    <option value="{{ $ext_colours->id }}" @if ($ext_colours->id === $vehicle->ex_colour) selected @endif>
                                        {{ $ext_colours->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" id="newVariantDropdown" style="display: none;">
    <label>New Variants</label>
    <select class="form-control" name="newVariantDropdown">
        @foreach ($newvariants as $newVariant)
            <option value="{{ $newVariant->id }}">{{ $newVariant->name }}</option>
        @endforeach
    </select>
</div>
                    </div>
                    <br>
                    <hr>
                    <h4>Extra Items:</h4>
    <div class="row">
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_1" name="sparewheel">
                    <label for="loss_item_1">Spare Wheel</label>
                    <input type="text" class="form-control" name="sparewheel_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_2" name="jack">
                    <label for="loss_item_2">Jack</label>
                    <input type="text" class="form-control" name="jack_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_3" name="wheel">
                    <label for="loss_item_3">Wheel Spanner</label>
                    <input type="text" class="form-control" name="wheel_qty" placeholder="Qty">
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_4" name="firstaid">
                    <label for="loss_item_4">First Aid Kit / Packing Box</label>
                    <input type="text" class="form-control" name="firstaid_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_5" name="floor_mat">
                    <label for="loss_item_5">Floor Mat</label>
                    <input type="text" class="form-control" name="floor_mat_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_6" name="service_book">
                    <label for="loss_item_6">Service Book & Manual</label>
                    <input type="text" class="form-control" name="service_book_qty" placeholder="Qty">
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
                    <input type="checkbox" id="loss_item_8" name="wheelrim">
                    <label for="loss_item_8">Wheel Rim / Tyres</label>
                    <input type="text" class="form-control" name="wheelrim_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_9" name="fire_extinguisher">
                    <label for="loss_item_9">Fire Extinguisher</label>
                    <input type="text" class="form-control" name="fire_extinguisher_qty" placeholder="Qty">
                </li>
            </ul>
        </div>
        <div class="col-md-2">
            <ul class="list-group">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_10" name="sd_card">
                    <label for="loss_item_10">SD Card / Remote / H Phones</label>
                    <input type="text" class="form-control" name="sd_card_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_11" name="ac_system">
                    <label for="loss_item_11">A/C System</label>
                    <input type="text" class="form-control" name="ac_system_qty" placeholder="Qty">
                </li>
                </div>
        <div class="col-md-2">
                <li class="list-group-item">
                    <input type="checkbox" id="loss_item_12" name="dash_board">
                    <label for="loss_item_12">Dash Board / T Screen / LCD</label>
                    <input type="text" class="form-control" name="dash_board_qty" placeholder="Qty">
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
                            <label>Extra Features</label>
                            <input type="text" class="form-control" id="extra_features" name="extra_features">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Remarks</label>
                            <textarea name="remarks" id="editor"></textarea>
                        </div>
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
      function openModal(vehicleid) {
  $('#newVariantModal').data('vehicle-id', vehicleid);
  $('#newVariantModal').modal('show');
}
</script>
<script>
    $(document).ready(function() {
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
      function openModal(vehicleid) {
  $('#newVariantModal').data('vehicle-id', vehicleid);
  $('#newVariantModal').modal('show');
}
</script>
<script>
    function toggleNewVariants() {
        var variantSelect = document.getElementById('variant');
        var newVariantDropdown = document.getElementById('newVariantDropdown');
        if (variantSelect.value === "") {
            newVariantDropdown.style.display = 'block';
        } else {
            newVariantDropdown.style.display = 'none';
        }
    }
</script>
<script>
function saveVariant() {
  var modelDescription = $('#model_description').val();
  var variant = $('#variantname').val();
  var brands = $('#brands_id').val();
  var master_model_lines = $('#master_model_lines_id').val();
  var modelYear = $('select[name="my"]').val();
  var variantDetails = $('#detail').val();
  var engineCapacity = $('#engine').val();
  var transmission = $('select[name="gearbox"]').val();
  var fuelType = $('select[name="fuel_type"]').val();
  var steering = $('select[name="steering"]').val();
  var seatCapacity = $('select[name="seat"]').val();
  var upholstery = $('select[name="upholestry"]').val();
  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  var formData = {
    variant: variant,
    brands_id: brands,
    master_model_lines_id: master_model_lines,
    model_description: modelDescription,
    model_year: modelYear,
    variant_details: variantDetails,
    engine_capacity: engineCapacity,
    transmission: transmission,
    fuel_type: fuelType,
    steering: steering,
    seat_capacity: seatCapacity,
    upholstery: upholstery,
    _token: csrfToken
  };
  checkVariantName();
  const errorMessage = validationMessage.querySelector('.text-danger');
  if (errorMessage) {
    alertify.error('Variant Already Existing');
    return;
  }
  $.ajax({
    type: 'POST',
    url: '{{route('variantrequests.store')}}',
    data: formData,
    success: function(response) {
      $('#newVariantModal').modal('hide');
      setTimeout(function() {
        window.location.reload();
        }, 1000);
    },
    error: function(error) {
      console.error(error);
    }
  });
}
</script>
<script>
  const variantInput = document.getElementById('variantname');
  variantInput.addEventListener('input', checkVariantName);
  const validationMessage = document.getElementById('validation-message');
  function checkVariantName() {
    const variantName = variantInput.value;
    fetch(`/check-variant?name=${encodeURIComponent(variantName)}`, {
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
</script>
@endpush
