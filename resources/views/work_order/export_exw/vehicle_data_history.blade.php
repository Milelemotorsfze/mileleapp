<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="row m-0">
    <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">Date Range</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <input type="text" id="date_range" class="form-control widthinput" placeholder="Date Range">
    </div>
    <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">User</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <select name="user_id" id="user_id" multiple="true" class="form-control widthinput">
            @foreach($users as $user)
            <option value="{{$user->id ?? ''}}">{{$user->name ?? ''}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">Type</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <input type="text" id="type" class="form-control widthinput" placeholder="Type">
    </div>
    <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">Field</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <input type="text" id="field" class="form-control widthinput" placeholder="Field">
    </div>
</div>
<div class="row mt-1">
    <div class="table-responsive">
        <table class="table table-striped table-editable table-edits table table-condensed my-datatable" >
            <thead style="background-color: #e6f1ff">
                <tr>
                    <th>Action</th>
                    <th>Sl No</th>
                    <th>BOE Number</th>
                    <th>VIN</th>
                    <th>Brand</th>
                    <th>Variant</th>
                    <th>Engine</th>
                    <th>Model Description</th>
                    <th>Model Year</th>
                    <th>Model Year To Mention On Documents</th>
                    <th>Steering</th>
                    <th>Exterior Colour</th>
                    <th>Interior Colour</th>
                    <th>Warehouse</th>
                    <th>Territory</th>
                    <th>Preferred Destination</th>
                    <th>Import Type</th>
                    <th>Ownership Name</th>
                    <th>Modification Or Jobs To Perform Per VIN</th>
                    <th>Certification Per VIN</th>
                    <th>Special Request Or Remarks</th>
                    @if(isset($type) && $type == 'export_cnf')
                    <th>Shipment</th>
                    @endif
                    <th>Deposit Received</th>
                    <th>Created At</th>
                    <th>Created By</th>
                    <th>Last Updated At</th>
                    <th>Last Updated By</th>
                    <th>Deleted At</th>
                    <th>Deleted By</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($workOrder) && isset($workOrder->vehiclesWithTrashed) && count($workOrder->vehiclesWithTrashed) > 0)
            <div hidden>{{$i=0;}}</div>
                @foreach($workOrder->vehiclesWithTrashed as $vehicle)
                    <tr>
                        <td><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Vehicle Data History" class="btn btn-sm btn-warning" 
                        href="{{route('wo-vehicles.data-history',$vehicle->id ?? '')}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a></td>
                        <td>{{ ++$i }}</td>
                        <td>{{ $vehicle->boe_number }}</td>
                        <td>{{ $vehicle->vin ?? '' }}</td> 
                        <td>{{ $vehicle->brand ?? ''}}</td>
                        <td>{{ $vehicle->variant ?? '' }}</td>
                        <td>{{ $vehicle->engine ?? ''}}</td> 
                        <td>{{ $vehicle->model_description ?? '' }}</td>
                        <td>{{ $vehicle->model_year ?? ''}}</td>
                        <td>{{ $vehicle->model_year_to_mention_on_documents ?? '' }}</td> 
                        <td>{{ $vehicle->steering ?? ''}}</td>
                        <td>{{ $vehicle->exterior_colour ?? '' }}</td>
                        <td>{{ $vehicle->interior_colour ?? ''}}</td> 
                        <td>{{ $vehicle->warehouse ?? '' }}</td>
                        <td>{{ $vehicle->territory ?? ''}}</td>
                        <td>{{ $vehicle->preferred_destination ?? ''}}</td>
                        <td>{{ $vehicle->import_document_type_name ?? ''}}</td>
                        <td>{{ $vehicle->ownership_name ?? ''}}</td>
                        <td>{{ $vehicle->modification_or_jobs_to_perform_per_vin ?? ''}}</td>
                        <td>{{ $vehicle->certification_per_vin ?? ''}}</td>
                        <td>{{ $vehicle->special_request_or_remarks ?? ''}}</td>
                        @if(isset($type) && $type == 'export_cnf')
                        <td>{{ $vehicle->shipment ?? ''}}</td>
                        @endif
                        <td>{{ $vehicle->deposit_received ?? ''}}</td>
                        <td>{{$vehicle->CreatedBy->name ?? ''}}</td>
                        <td>@if($vehicle->created_at != ''){{\Carbon\Carbon::parse($vehicle->created_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                        <td>{{$vehicle->UpdatedBy->name ?? ''}}</td>
                        <td>@if($vehicle->updated_at != '' && $vehicle->updated_at != $vehicle->created_at){{\Carbon\Carbon::parse($vehicle->updated_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                        <td>{{$vehicle->DeletedBy->name ?? ''}}</td>
                        <td>@if($vehicle->deleted_at != ''){{\Carbon\Carbon::parse($vehicle->deleted_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                    </tr>
                    @if(isset($vehicle->addonsWithTrashed) && count($vehicle->addonsWithTrashed) > 0)
                        <tr><th colspan="26">Service Breakdown</th></tr>
                        @foreach($vehicle->addonsWithTrashed as $addon)
                        <tr>
                            <td><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Addon Data History" class="btn btn-sm btn-warning" 
                        href="{{route('wo-vehicle-addon.data-history',$addon->id ?? '')}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a></td>
                            <td colspan="5">{{$addon->addon_code ?? 'NA'}}</td>
                            <td colspan="2">Qty : {{$addon->addon_quantity ?? 'NA'}}</td>
                            <td colspan="18">{{$addon->addon_description ?? 'NA'}}</td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="26">No addons available for this vehicle.</td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="5">No data history available.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

