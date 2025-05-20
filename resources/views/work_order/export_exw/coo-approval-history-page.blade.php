@extends('layouts.main')
<head>
    <meta charset="UTF-8">
    <style>
        .select2-container {
            width: 100% !important;
        }
        table {
            width: 100% !important;
        }
        .details-row {
            display: none;
            background-color: white;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: -1px;
        }
        .veh-table th {
    border-left: 1px solid #e9e9ef; 
    border-right: 1px solid #e9e9ef;
    border-top: 1px solid #e9e9ef; 
    border-bottom: 1px solid #e9e9ef; 
    padding: 3px!important; 
    text-align: left; 
}


.veh-table td {
    border-left: 1px solid #e9e9ef; 
    border-right: 1px solid #e9e9ef; 
    border-top: 1px solid #e9e9ef; 
    border-bottom: 1px solid #e9e9ef; 
    padding: 3px!important; 
    text-align: left; 
}

.veh-table {
    border-collapse: collapse; 
    width: 100%; 
}
    </style>
</head>
@section('content')
<body>
<div class="card">
    <div class="card-header">
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-coo-approval-history']);
        @endphp
        @if ($hasPermission)
        <h4 class="card-title">
        @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order COO Approval History
        </h4>
        <a class="btn btn-sm btn-info float-end form-label" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

        @endif
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger">
            <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
            {{ Session::get('error') }}
        </div>
        @endif
        @if (Session::has('success'))
        <div class="alert alert-success" id="success-alert">
            <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
            {{ Session::get('success') }}
        </div>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <label for="choices-single-default" class="form-label"><strong>WO Number</strong></label> :
                @if(count($data) > 0)
                    {{ $data->first()->workOrder->wo_number ?? '' }}
                @endif
            </div>
        </div>
        <div class="row mt-1">
            <div class="table-responsive dragscroll">
                <table class="table table-striped table-editable table-edits table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Sl No</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Action At</th>
                            <th>Comments</th>
                            <th>Approval By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data) > 0)
                            <div hidden>{{$i=0;}}</div>
                            @foreach($data as $approval)
                                <tr data-id="{{ $approval->id }}">
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $approval->created_at->format('d M Y, H:i:s') ?? '' }}</td>
                                    <td>
                                    @if($approval->status == 'pending' && $approval->workOrder->can_show_coo_approval == 'yes')
                                        <label class="badge @if($approval->status == 'pending') badge-soft-info @endif">{{ $approval->status ?? ''}}</label>
                                    @elseif($approval->status == 'approved' || $approval->status == 'rejected')                                        
                                        <label class="badge @if($approval->status == 'approved') badge-soft-success @elseif($approval->status == 'rejected') badge-soft-danger @endif">{{ $approval->status ?? ''}}</label>
                                    @endif
                                    </td>
                                    <td>@if($approval->action_at != '')
                                            {{ \Carbon\Carbon::parse($approval->action_at)->format('d M Y, H:i:s') }}
                                        @endif
                                    </td>
                                    <td>{{ $approval->comments ?? '' }}</td>
                                    <td>{{ $approval->user->name ?? '' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-details-btn" data-id="{{ $approval->id }}">Details</button>
                                        @if($approval->status == 'pending' && $approval->workOrder->can_show_coo_approval == 'yes')
                                            <a title="COO Approval" style="margin-top:0px;" class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" data-bs-target="#financeApprovalModal_{{$approval->id}}">
                                                <i class="fas fa-hourglass-start" title="COO Approval"></i> Approval
                                            </a>
                                            <div class="modal fade" id="financeApprovalModal_{{$approval->id}}" tabindex="-1" aria-labelledby="financeApprovalModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="financeApprovalModalLabel">COO Approval</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="cooComment_{{$approval->id}}" class="form-label">Comments</label>
                                                                <textarea class="form-control" id="cooComment_{{$approval->id}}" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-sm btn-danger btn-coo-approval" id="rejectButton" 
                                                            data-id="{{ $approval->id}}"
                                                            data-status="reject">Reject</button>
                                                            <button type="button" class="btn btn-sm btn-success btn-coo-approval" id="approveButton" 
                                                            data-id="{{ $approval->id}}"
                                                            data-status="approve">Approve</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="details-row" id="details-{{ $approval->id }}">
                                @php
                                $sortedHistories = $approval->recordHistories->sortBy('field');
                                @endphp
                                    <td colspan="7">
                                        <table style="font-size:12px!important;" class="veh-table">
                                            <tr>
                                                <th>BOE</th>
                                                <th>VIN</th>
                                                <th>Brand</th>
                                                <th>Variant</th>
                                                <th>Engine</th>
                                                <th>Model Description</th>
                                                <th>Model Year</th>
                                                <th>Model Year to mention on Documents</th>
                                                <th>Steering</th>
                                                <th>Exterior Colour</th>
                                                <th>Interior Colour</th>
                                                <th>Warehouse</th>
                                                <th>Territory</th>
                                                <th>Preferred Destination</th>
                                                <th>Import Document Type</th>
                                                <th>Ownership Name</th>
                                                <th>Certification Per VIN</th>
                                                @if(isset($type) && $type == 'export_cnf')
                                                <th>Shipment</th>
                                                @endif
                                                <th>Deposit Received</th>
                                            </tr>
                                            @if($approval->status == 'pending')
                                                @if(count($approval->workOrder->vehicles) > 0)                                                   
                                                @php
                                                $vehicles = $approval->workOrder->vehicles->sortBy('vin');
                                                @endphp

                                                @foreach($vehicles as $vehicle)
                                                    <tr style="border-top:1px solid #b3b3b3;">
                                                        <td>{{$vehicle->boe_number ?? ''}}</td>
                                                        <td>{{$vehicle->vin ?? ''}}</td>
                                                        <td>{{$vehicle->brand ?? ''}}</td>
                                                        <td>{{$vehicle->variant ?? ''}}</td>
                                                        <td>{{$vehicle->engine ?? ''}}</td>
                                                        <td>{{$vehicle->model_description ?? ''}}</td>
                                                        <td>{{$vehicle->model_year ?? ''}}</td>
                                                        <td>{{$vehicle->model_year_to_mention_on_documents ?? ''}}</td>
                                                        <td>{{$vehicle->steering ?? ''}}</td>
                                                        <td>{{$vehicle->exterior_colour ?? ''}}</td>
                                                        <td>{{$vehicle->interior_colour ?? ''}}</td>
                                                        <td>{{$vehicle->warehouse ?? ''}}</td>
                                                        <td>{{$vehicle->territory ?? ''}}</td>
                                                        <td>{{$vehicle->preferred_destination ?? ''}}</td>
                                                        <td>{{$vehicle->import_document_type ?? ''}}</td>
                                                        <td>{{$vehicle->ownership_name ?? ''}}</td>
                                                        <td>{{$vehicle->certification_per_vin_name ?? ''}}</td>
                                                        @if(isset($type) && $type == 'export_cnf')
                                                            <td>{{$vehicle->shipment ?? ''}}</td>
                                                        @endif
                                                        <td>{{$vehicle->deposit_received ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2">Modification/Jobs</th>
                                                        <td colspan="17">{{$vehicle->modification_or_jobs_to_perform_per_vin ?? ''}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2">Special Request/Remarks</th>
                                                        <td colspan="17">{{$vehicle->special_request_or_remarks ?? ''}}</td>
                                                    </tr>
                                                @endforeach

                                                @endif
                                            @else
                                            @php
                                            $groupedHistories = collect(json_decode($approval->vehicleRecordHistories, true))->groupBy('w_o_vehicle_id');
                                            @endphp

                                            @foreach($groupedHistories as $w_o_vehicle_id => $histories)
                                                @php
                                                $vehicleData = [];
                                                foreach ($histories as $history) {
                                                    $vehicleData[$history['field_name']] = $history['new_value'];
                                                }
                                                @endphp
                                                <tr style="border-top:1px solid #b3b3b3;">
                                                    <td>{{$vehicleData['boe_number'] ?? ''}}</td>
                                                    <td>{{$vehicleData['vin'] ?? ''}}</td>
                                                    <td>{{$vehicleData['brand'] ?? ''}}</td>
                                                    <td>{{$vehicleData['variant'] ?? ''}}</td>
                                                    <td>{{$vehicleData['engine'] ?? ''}}</td>
                                                    <td>{{$vehicleData['model_description'] ?? ''}}</td>
                                                    <td>{{$vehicleData['model_year'] ?? ''}}</td>
                                                    <td>{{$vehicleData['model_year_to_mention_on_documents'] ?? ''}}</td>
                                                    <td>{{$vehicleData['steering'] ?? ''}}</td>
                                                    <td>{{$vehicleData['exterior_colour'] ?? ''}}</td>
                                                    <td>{{$vehicleData['interior_colour'] ?? ''}}</td>
                                                    <td>{{$vehicleData['warehouse'] ?? ''}}</td>
                                                    <td>{{$vehicleData['territory'] ?? ''}}</td>
                                                    <td>{{$vehicleData['preferred_destination'] ?? ''}}</td>
                                                    <td>{{$vehicleData['import_document_type'] ?? ''}}</td>
                                                    <td>{{$vehicleData['ownership_name'] ?? ''}}</td>
                                                    <td>{{$vehicleData['certification_per_vin_name'] ?? ''}}</td>
                                                    @if(isset($type) && $type == 'export_cnf')
                                                        <td>{{$vehicleData['shipment'] ?? ''}}</td>
                                                    @endif
                                                    <td>{{$vehicleData['deposit_received'] ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Modification/Jobs</th>
                                                    <td colspan="17">{{$vehicleData['modification_or_jobs_to_perform_per_vin'] ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Special Request/Remarks</th>
                                                    <td colspan="17">{{$vehicleData['special_request_or_remarks'] ?? ''}}</td>
                                                </tr>
                                            @endforeach

                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">No data history available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('.view-details-btn').on('click', function() {
            var id = $(this).data('id');
            var detailsRow = $('#details-' + id);

            if (detailsRow.is(':visible')) {
                detailsRow.hide();
            } else {
                detailsRow.insertAfter($('tr[data-id="' + id + '"]')).show();
            }
        });

        $('.btn-coo-approval').click(function (e) { 
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var comments = $('#cooComment_'+id).val();
            let url = '{{ route('work-order.coe-office-approval') }}';
            alertify.confirm('Are you sure you want to '+status+' this work order ?', function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status: status,
                            comments: comments,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {                        
                            if(data == 'success') {
                                window.location.reload();
                                alertify.success(status + " Successfully")
                            }
                            else if(data == 'error') {
                                window.location.reload();
                                alertify.error("Can't Approve, It was approved already..")
                            }
                        }
                    });
                }
            }).set({title:"Confirmation"});
        });
    });
</script>
</body>
@endsection
