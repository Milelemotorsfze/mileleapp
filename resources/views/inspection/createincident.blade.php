@extends('layouts.main')
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<div class="card-header">
    <h4 class="card-title">
     Incident Report
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
</div>
<div class="card-body">
<div class="row">
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
<form action="{{ route('incident.createincidents') }}" method="POST" enctype="multipart/form-data">
                @csrf
<label><strong>Vehicle Identification Number:</strong></label>
</div>
<div class="col-md-6">
<select id="vinSelect" name="vin" style="width: 100%;">
<option value="" disabled selected>Select The VIN</option>
        @foreach($vehicle as $v)
        <option value="{{$v->vin}}">{{$v->vin}}</option>
        @endforeach
    </select>              
</div>
</div>
</div>
</div>
<br>
<hr>
<div class="row">
        <div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Brand</strong></label>
</div>
<div class="col-md-8">
          <span id="brand"></span>          
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Model Line</strong></label>
</div>
<div class="col-md-8">
<span id="modelline"></span> 
                   
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Model Detail</strong></label>
</div>
<div class="col-md-8">
                   <span id="detail"></span> 
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Variant</strong></label>
</div>
<div class="col-md-8">
<span id="variantname"></span> 
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Model Year</strong></label>
</div>
<div class="col-md-8">
<span id="my"></span>     
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Variant Detail</strong></label>
</div>
<div class="col-md-8">
<span id="variantdetail"></span>     
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Steering</strong></label>
</div>
<div class="col-md-8">
<span id="steering"></span>  
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Seats</strong></label>
</div>
<div class="col-md-8">
<span id="seats"></span>         
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Fuel Type</strong></label>
</div>
<div class="col-md-8">
<span id="fuel_type"></span>              
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Transmission</strong></label>
</div>
<div class="col-md-8">
<span id="gearbox"></span>          
</div>
</div>
</div>
<div class="col-md-3">
<div class="row">
<div class="col-md-4">
                    <label><strong>Production Year</strong></label>
</div>
<div class="col-md-8">
<span id="py"></span>               
</div>
</div>
</div>
<div class="col-md-6">
<div class="row">
<div class="col-md-4">
                    <label><strong>Interior Color</strong></label>
</div>
<div class="col-md-8">
<span id="interiorColorName"></span>                
</div>
</div>
</div>
            <div class="col-md-3">
            <div class="row">
            <div class="col-md-4">
                                <label><strong>Exterior Color</strong></label>
            </div>
            <div class="col-md-8">
            <span id="exteriorColorName"></span>          
            </div>
            </div>
            </div>
            </div>
     </br>
     <hr>
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
     </br>
     <hr>
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
        $(document).ready(function() {
            $('#vinSelect').select2();
        });
    </script>
    <script>
$(document).ready(function () {
    $('#vinSelect').on('change', function () {
        var selectedVIN = $(this).val();
        $.ajax({
            type: 'GET',
            url: '{{ route("incident.updatevehicledetails") }}',
            data: { vin: selectedVIN },
            success: function (response) {
                if (!response || Object.keys(response).length === 0) {
                     alert("No vehicle data found for this VIN.");
                     return;
                 }
                $('#brand').text(response.brand);
                $('#modelline').text(response.modelLine);
                $('#variantdetail').text(response.detail);
                $('#detail').text(response.modeldetail);
                $('#variantname').text(response.name);
                $('#my').text(response.my);
                $('#steering').text(response.steering);
                $('#seats').text(response.seat);
                $('#fuel_type').text(response.fuel_type);
                $('#gearbox').text(response.gearbox);
                $('#py').text(response.py);
                $('#interiorColorName').text(response.interiorColorName);
                $('#exteriorColorName').text(response.exteriorColorName);
            },
            error: function () {
                alert('Error fetching vehicle details.');
            }
        });
    });
});
</script>
@endpush
