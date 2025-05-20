@extends('layouts.main')
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
@section('content')
<div class="card-header">
    <h4 class="card-title">
     Stock Vehicle Inspection Report
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
    <form id="inspection-form" action="{{ route('dailyinspection.routainupdate', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Checkitems</th>
                            <th>Condition</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <span>Battery Inspection<br>(Check the battery voltage before starting the engine)</span>
                            </td>
                            <td>
                            <select class="form-control" name="condition_battery">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_battery">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Tyre Pressure Inspection</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_tyre_pressure">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_tyre_pressure">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Under Hood Inspection</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_under_hood">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_under_hood">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Starting & Warming</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_starting">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_starting">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>A/C Operation (Cool & Hot)</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_ac">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_ac">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Exterior Inspection & Protective Cover Condition</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_exterior_inspection">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_exterior_inspection">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Interior Inspection & Protective Cover Condition</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_interior_inspection">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_interior_inspection">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Move the Vehicle <br>(To Change the tyre position on ground)</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_vehicle_move">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_vehicle_move">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Paint (Overall)</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_paint">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_paint">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Bumper Front</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_bumper">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_bumper">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Grill</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_grill">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_grill">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Light Front</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_light_front">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_light_front">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Hood</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_hood">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_hood">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Windshield</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_windshield">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_windshield">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Wipers</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_wipers">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_wipers">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Fender Front Left</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_fender_front_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_fender_front_left">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Tire / Rim Front Left</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_tire_rim_front_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_tire_rim_front_left">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Side Step Left (if Applicable)</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_side_step_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_side_step_left">
                            </td>
                        </tr> 
                        <tr>
                            <td>
                                <span>Door Front Left (Check Handles)</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_door_front_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_door_front_left">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Mirror Door Driver</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_mirror_door_driver">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_mirror_door_driver">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Roof / A-pillars Left</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_roof">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_roof">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Door Rear Left (Check Handles)</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_door_rear_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_door_rear_left">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Fender Rear Left</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_fender_rear_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_fender_rear_left">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Bed / Box</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_bed_box">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_bed_box">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Tailgate (Check Handles)</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_tailgate">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_tailgate">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Lights Rear</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_light_rear">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_light_rear">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Bumper Rear</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_bumper_rear">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_bumper_rear">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Underbody Parts (Muffler / Tank)</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_underbody_parts">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_underbody_parts">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Fender Rear Right</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_fender_rear_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_fender_rear_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Tire / Rim Rear Left</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_tire_rim_rear_left">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_tire_rim_rear_left">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Door Rear Right (Check Handles)</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_door_rear_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_door_rear_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Roof / A-pillars Right</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_pillar_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_pillar_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Mirror Door Passenger</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_mirror_door_passenger">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_mirror_door_passenger">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Door Front Right(Check Handles)</span>
                            </td>
                            
                            <td>
                            <select class="form-control" name="condition_door_front_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_door_front_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Side Steps Right (If applicable)</span>
                            </td>
                          
                            <td>
                            <select class="form-control" name="condition_side_steps_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_side_steps_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Fender Front Right</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_fender_front_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_fender_front_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Tire /  Rim Front Right</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_tire_rim_front_right">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_tire_rim_front_right">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Radio Antenna</span>
                            </td>
                           
                            <td>
                            <select class="form-control" name="condition_radio_antenna">
                                <option value="Ok">Ok</option>
                                <option value="Not Ok">Not Ok</option>
                            </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks_radio_antenna">
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
