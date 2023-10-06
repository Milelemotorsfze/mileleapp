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
     Re Inspection
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('approvalsinspection.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
</div>
<div class="card-body">
    <h5>Vehicle Specifications</h5>
    <br>
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
<hr>
<h5>Part Procurement Remarks</h5>
<div class="row">
<div class="col-md-2">
                    <label><strong>Part Purchase Order</strong></label>
</div>
<div class="col-md-10">
                    {{$Incident->part_po_number ?? ''}}
</div>
<div class="col-md-2">
                    <label><strong>Remarks</strong></label>
</div>
<div class="col-md-10">
                    {{$Incident->update_remarks ?? ''}}
</div>
</div>
<hr>
<form method="post" action="{{ route('incident.reinspectionsforapp') }}">
    @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="work">Work Actually Under Taken</label>
                    <input type="text" name="work[]" class="form-control">
                    <input type="hidden" name="Incidentid" class="form-control" value="{{$Incident->id}}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status[]" class="form-control">
                        <option value="Completed">Completed</option>
                        <option value="Remaining">Remaining</option>
                        <option value="Under Process">Under Process</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="remarks">Remarks /  Additional Information</label>
                    <input type="text" name="remarks[]" class="form-control">
                </div>
            </div>
        </div>
        </br>
        <button type="button" id="addRow" class="btn btn-primary">Add Row</button>
        <button  style="float: right; margin-right: 10px;" type="submit" class="btn btn-success">Submit</button>
</form>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $("#addRow").on("click", function () {
            var newRow = `<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="work[]" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="status[]" class="form-control">
                        <option value="Completed">Completed</option>
                        <option value="Remaining">Remaining</option>
                        <option value="Under Process">Under Process</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="remarks[]" class="form-control">
                    </div>
                </div>
            </div>
            </br>`;
            $(newRow).insertBefore("#addRow");
        });
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
@endpush