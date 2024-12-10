<head>
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
</head>
<body>
<div class="row m-0">
    <div class="col-md-1 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">Filter By VIN</label>
    </div>
    <div class="col-md-11 col-xxl-11 col-lg-11 col-sm-12">
        <select id="vin-filter" class="form-control" multiple="multiple">
        </select>
    </div>
</div>
<div class="row mt-1">
    <div class="table-responsive dragscroll">
        <table id="myVehAddonTable" class="table table-striped table-editable table-edits table table-condensed my-datatable" >
            <thead style="background-color: #e6f1ff">
                <tr>
                    <th>History</th>
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
                    <tr class="vehicle-row" data-vin="{{ $vehicle->vin ?? '' }}" data-id="{{ $vehicle->id ?? '' }}" style="border-top: 2px solid rgb(166, 166, 166);" style="background-color : #f6fafe!important;">
                        <td>
                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Vehicle Data History" class="btn btn-sm btn-warning" 
                                    href="{{route('wo-vehicles.data-history',$vehicle->id ?? '')}}">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td>{{ ++$i }}</td>
                        <td>{{ $vehicle->boe_number }}</td>
                        <td class="vin-class">{{ $vehicle->vin ?? '' }}</td> 
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
                        <td>@if($vehicle->created_at != ''){{\Carbon\Carbon::parse($vehicle->created_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                        <td>{{$vehicle->CreatedBy->name ?? ''}}</td>                        
                        <td>@if($vehicle->updated_at != '' && $vehicle->updated_at != $vehicle->created_at){{\Carbon\Carbon::parse($vehicle->updated_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                        <td>{{$vehicle->UpdatedBy->name ?? ''}}</td>
                        <td>@if($vehicle->deleted_at != ''){{\Carbon\Carbon::parse($vehicle->deleted_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                        <td>{{$vehicle->DeletedBy->name ?? ''}}</td>
                    </tr>
                    @if(isset($vehicle->addonsWithTrashed) && count($vehicle->addonsWithTrashed) > 0)
                        <tr class="service-breakdown" data-vin="{{ $vehicle->vin ?? '' }}"><th colspan="@if(isset($type) && $type == 'export_cnf') 29 @else 28 @endif">Service Breakdown</th></tr>
                        @foreach($vehicle->addonsWithTrashed as $addon)
                        <tr class="service-breakdown" data-vin="{{ $vehicle->vin ?? '' }}">
                            <td><a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Addon Data History" class="btn btn-sm btn-warning" 
                        href="{{route('wo-vehicle-addon.data-history',$addon->id ?? '')}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a></td>
                            <td colspan="3">Creatd At : {{ $addon->created_at->format('d M Y, H:i:s') }}</td>
                            <td colspan="5">{{$addon->addon_code ?? 'NA'}}</td>
                            <td colspan="2">Qty : {{$addon->addon_quantity ?? 'NA'}}</td>
                            <td colspan="@if(isset($type) && $type == 'export_cnf') 18 @else 17 @endif">Addon Desc. : {{$addon->addon_description ?? 'NA'}}</td>
                        </tr>
                        @endforeach
                    @else
                    <tr  class="service-breakdown" data-vin="{{ $vehicle->vin ?? '' }}">
                        <td colspan="@if(isset($type) && $type == 'export_cnf') 29 @else 28 @endif">No addons available for this vehicle.</td>
                    </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="@if(isset($type) && $type == 'export_cnf') 29 @else 28 @endif">No data history available.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
     $(document).ready(function() {
       const uniqueVins = new Set();

        $('.vin-class').each(function() {
            const vin = $(this).text().trim();
            if (vin) {
                uniqueVins.add(vin);
            }
        });

        const sortedVins = Array.from(uniqueVins).sort();

        sortedVins.forEach(function(vin) {
            $('#vin-filter').append(new Option(vin, vin));
        });

        $('#vin-filter').select2({
            placeholder: 'Select VIN',
            allowClear: true
        });

        $('#vin-filter').on('change', function() {
            const selectedVins = $(this).val();
            
            if (selectedVins && selectedVins.length > 0) {
                $('.vehicle-row, .service-breakdown').hide();
                selectedVins.forEach(function(vin) {
                    $(`.vehicle-row[data-vin="${vin}"], .service-breakdown[data-vin="${vin}"]`).show();
                });
            } else {
                $('.vehicle-row, .service-breakdown').show();
            }
        });
    });
</script>
</body>