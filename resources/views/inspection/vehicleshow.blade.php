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
                            <textarea name="remarks" id="editor"></textarea>
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
        $('#int_colour').select2({
            placeholder: 'Select Interior Colour'
        })
        $('#ex_colour').select2({
            placeholder: 'Select Exterior Colour'
        })
        $('#model').select2({
            placeholder: 'Select Model'
        })
        $('#brand').on('change',function() {
            $('#brand-error').remove();
        })
        $('#model').on('change',function() {
            $('#model-error').remove();
        })
        $("#form-create").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                    string:true,
                    max:255
                },
                master_model_lines_id:{
                    required:true,
                },
                brands_id:{
                    required:true,
                },
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
                    var select = $('<select class="form-control" name="specification_' + specification.id + '"data-specification-id="' + specification.id + '" required>');
                    select.append('<option value="" disabled selected>Select an Option</option>');

                    options.forEach(function(option) {
                        select.append('<option value="' + option.id + '">' + option.name + '</option>');
                    });

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
                    specificationColumn.append(select);
                    $('#specification-details-container').append(specificationColumn);
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
@endpush
