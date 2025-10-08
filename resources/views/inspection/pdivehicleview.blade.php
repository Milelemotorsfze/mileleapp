@extends('layouts.main')
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
<style>
    textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    resize: vertical;
}
    </style>
@section('content')
<div class="card-header">
   <h4 class="card-title">
      PDI Report
      <center><b>Vehicle Identification Number:
         {{$vehicleDetails->vin ?? ''}}
         </b>
      </center>
      <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
   </h4>
   <br>
</div>
<div class="card-body">
   <div class="row">
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Brand</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->brand_name ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Model Line</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->model_line ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Model Detail</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->model_detail ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Variant</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->variant_name ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Model Year</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->my ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Steering</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->steering ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Seats</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->seat ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Fuel Type</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->fuel_type ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Transmission</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->gearbox ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Production Year</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->ppmmyyy ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Interior Color</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->int_colour_name ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="row">
            <div class="col-md-4">
               <label><strong>Exterior Color</strong></label>
            </div>
            <div class="col-md-8">
               {{$vehicleDetails->ex_colour_name ?? ''}}
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="col-md-2">
               <label><strong>Variant Detail</strong></label>
            </div>
            <div class="col-md-10">
               {{$vehicleDetails->detail ?? ''}}
            </div>
         </div>
      </div>
   </div>
   <br>
   <hr>
   <h6>GRN Details</h6>
   <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Attributes</th>
                    <th>Option</th>
                    <th>Attributes</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($variantitems as $index => $variantitem)
                @if ($index % 2 == 0)
                <tr>
                    <td>{{$variantitem->model_specification->name ?? 'N/A'}}</td>
                    <td>{{$variantitem->model_specification_option->name ?? 'N/A'}}</td>
                @else
                    <td>{{$variantitem->model_specification->name ?? 'N/A'}}</td>
                    <td>{{$variantitem->model_specification_option->name ?? 'N/A'}}</td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        <hr>
        <h6>PDI Checklists</h6>
   <form id="inspection-form" action="{{ route('pdi.pdiinspection') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="vehicle_id" name="vehicle_id" value="{{$vehicleDetails->id}}">
      <div class="row">
         <div class="col-md-12">
            <div class="table-responsive">
               <table class="table table-bordered">
                  <thead>
                     <tr>
                        <th>SL No</th>
                        <th>Checklist Items</th>
                        <th>Receiving</th>
                        <th>Delivery</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td> 1 </td>
                        <td>
                           <span>Packing Box</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'packing'))
                              <p>AV</p>
                              <input type="hidden" name="packingr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="packingr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="packing">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           2
                        </td>
                        <td>
                           <span>Warning Triangle</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'warningtriangle'))
                              <p>AV</p>
                              <input type="hidden" name="warningtriangler" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="warningtriangler" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="warningtriangle">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                              <option value="In Box">In Box</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           3
                        </td>
                        <td>
                           <span>Jack & WHEEL SPANNER</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'wheel'))
                              <p>AV</p>
                              <input type="hidden" name="wheelr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="wheelr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="wheel">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                              <option value="In Box">In Box</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           4
                        </td>
                        <td>
                           <span>FIRST AID KIT</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'firstaid'))
                              <p>AV</p>
                              <input type="hidden" name="firstaidr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="firstaidr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="firstaid">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                              <option value="In Box">In Box</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           5
                        </td>
                        <td>
                           <span>FLOOR MAT</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'floor_mat'))
                              <p>AV</p>
                              <input type="hidden" name="floor_matr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="floor_matr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="floor_mat">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                              <option value="In Box">In Box</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           6
                        </td>
                        <td>
                           <span>SERVICE BOOK & MANUAL</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'service_book'))
                              <p>AV</p>
                              <input type="hidden" name="service_bookr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="service_bookr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="service_book">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                              <option value="In Box">In Box</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           7
                        </td>
                        <td>
                           <span>KEYS / QTY</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'keys'))
                              <p>AV</p>
                              <input type="hidden" name="keysr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="keysr" value="AV">
                              @endif
                           </div>
                        </td>
                        <td>
                           <input type="text" class="form-control" name="keys" />
                        </td>
                     </tr>
                     <tr>
                        <td>
                           8
                        </td>
                        <td>
                           <span>EXTERIOR PAINT AND BODY</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="exteriorpaint">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           9
                        </td>
                        <td>
                           <span>INTERIOR & UPHOLSTERY</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="interior">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           10
                        </td>
                        <td>
                           <span>TRUNK COVER</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'trunkcover'))
                              <p>AV</p>
                              <input type="hidden" name="trunkcoverr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="trunkcoverr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="trunkcover">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           11
                        </td>
                        <td>
                           <span>FIRE EXTINGUISHER</span>
                        </td>
                        <td>
                           <div class="text-center">
                              @if ($itemsWithQuantities->contains('item_name', 'fire_extinguisher'))
                              <p>AV</p>
                              <input type="hidden" name="fire_extinguisherr" value="AV">
                              @else
                              <p>NA</p>
                              <input type="hidden" name="fire_extinguisherr" value="NA">
                              @endif
                           </div>
                        </td>
                        <td>
                           <select class="form-control" name="fire_extinguisher">
                              <option value="AV">AV</option>
                              <option value="NA">NA</option>
                              <option value="In Box">In Box</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           15
                        </td>
                        <td>
                           <span>CAMERA</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="camera">
                              <option value="360 Degree">360 Degree</option>
                              <option value="RR">RR</option>
                              <option value="FR">FR</option>
                              <option value="NA">NA</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           16
                        </td>
                        <td>
                           <span>STICKER REMOVAL</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="sticker">
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           18
                        </td>
                        <td>
                           <span>PHOTOS 6 Nos</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="photo">
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           19
                        </td>
                        <td>
                           <span>FUEL / BATTERY</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <input type="text" name="fuel" class="form-control" />
                        </td>
                     </tr>
                     <tr>
                        <td>
                           20
                        </td>
                        <td>
                           <span>UNDER HOOD INSPECTION</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="under_hood">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           21
                        </td>
                        <td>
                           <span>OILS AND FLUIDS LEVELS INSPECTION</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="oil">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           22
                        </td>
                        <td>
                           <span>ALL FUNCTIONS OPERATIONS AS PER PO</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="funcationpo">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           23
                        </td>
                        <td>
                           <span>CLEANING AND WASHING</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <select class="form-control" name="washing">
                              <option value="Ok">Ok</option>
                              <option value="Not Ok">Not Ok</option>
                           </select>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           24
                        </td>
                        <td>
                           <span>OTHER REMARKS</span>
                        </td>
                        <td>
                        </td>
                        <td>
                           <input type="text" name="otherremarks" class="form-control"/>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
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
         <div class="col-md-12">
            <label for="pdi_editor">PDI Remarks</label>
            <textarea name="pdi_remarks" id="pdi_editor" rows="5"></textarea>
         </div>
      </div>
</div>
</div>
</br>
<div class="col-lg-12 col-md-12">
<input type="submit" id="submit-button" name="submit" value="Submit" class="btn btn-success btncenter" />
</div>  
</form>
</br>
</br>
</div>
</div>
@endsection
@push('scripts')
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
@endpush
