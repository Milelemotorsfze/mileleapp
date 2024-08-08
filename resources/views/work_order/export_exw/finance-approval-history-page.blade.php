@extends('layouts.main')
<head>
    <meta charset="UTF-8">
    <!-- Load jQuery before DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
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
        .addon-table th {
            border-left: 1px solid #e9e9ef; /* Add a left border to each header cell */
            border-right: 1px solid #e9e9ef; /* Add a right border to each header cell */
            border-top: 1px solid #e9e9ef; /* Add a top border to each header cell */
            border-bottom: 1px solid #e9e9ef; /* Add a bottom border to each header cell */
            padding: 3px!important; /* Add padding for better readability */
            text-align: left; /* Align text to the left */
        }

        /* Style for the table cells */
        .addon-table td {
            border-left: 1px solid #e9e9ef; /* Add a left border to each cell */
            border-right: 1px solid #e9e9ef; /* Add a right border to each cell */
            border-top: 1px solid #e9e9ef; /* Add a top border to each cell */
            border-bottom: 1px solid #e9e9ef; /* Add a bottom border to each cell */
            padding: 3px!important; /* Add padding for better readability */
            text-align: left; /* Align text to the left */
        }

        /* Style for the entire table */
        .addon-table {
            border-collapse: collapse; /* Ensure borders do not double */
            width: 100%; /* Make the table take up the full width */
        }
    </style>
</head>
@section('content')
<body>
<div class="card">
    <div class="card-header">
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-finance-approval-history']);
        @endphp
        @if ($hasPermission)
        <h4 class="card-title">
        @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order Finance Approval History
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
            <div class="table-responsive">
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
                                        <label class="badge @if($approval->status == 'pending') badge-soft-info @elseif($approval->status == 'approved') badge-soft-success @elseif($approval->status == 'rejected') badge-soft-danger @endif">{{ $approval->status ?? ''}}</label>
                                    </td>
                                    <td>@if($approval->action_at != '')
                                            {{ \Carbon\Carbon::parse($approval->action_at)->format('d M Y, H:i:s') }}
                                        @endif
                                    </td>
                                    <td>{{ $approval->comments ?? '' }}</td>
                                    <td>{{ $approval->user->name ?? '' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-details-btn" data-id="{{ $approval->id }}">Details</button>
                                        @if($approval->status == 'pending')
                                            <a title="Finance Approval" style="margin-top:0px;" class="btn btn-sm btn-info" 
                                            data-bs-toggle="modal" data-bs-target="#financeApprovalModal_{{$approval->id}}">
                                                <i class="fas fa-hourglass-start" title="Finance Approval"></i> Approval
                                            </a>
                                            <!-- Modal -->
                                            <div class="modal fade" id="financeApprovalModal_{{$approval->id}}" tabindex="-1" aria-labelledby="financeApprovalModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="financeApprovalModalLabel">Finance Approval</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="financeComment_{{$approval->id}}" class="form-label">Comments</label>
                                                                <textarea class="form-control" id="financeComment_{{$approval->id}}" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-sm btn-danger btn-finance-approval" id="rejectButton" 
                                                            data-id="{{ $approval->id}}"
                                                            data-status="reject">Reject</button>
                                                            <button type="button" class="btn btn-sm btn-success btn-finance-approval" id="approveButton" 
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
                                        <table style="font-size:12px!important;">
                                            @if($approval->status == 'pending')
                                                <tr>
                                                    <th style="background-color: #dbecff;">Amount Received</th>
                                                    <th style="background-color: #dbecff;">Balance Amount</th>
                                                    <th style="background-color: #dbecff;">Currency</th>                                                    
                                                    <th style="background-color: #dbecff;">Deposit Received As</th>
                                                    <th style="background-color: #dbecff;">SO Total Amount</th>
                                                    <th style="background-color: #dbecff;">SO Vehicle Quantity</th>
                                                </tr>
                                                <tr>
                                                    <td>{{ $approval->workOrder->amount_received ?? '' }}</td>
                                                    <td>{{ $approval->workOrder->balance_amount ?? '' }}</td>
                                                    <td>{{ $approval->workOrder->currency ?? '' }}</td>
                                                    <td>
                                                        @if($approval->workOrder->deposit_received_as == 'total_deposit') 
                                                            Total Deposit
                                                        @elseif($approval->workOrder->deposit_received_as == 'custom_deposit')
                                                            Custom Deposit
                                                        @endif
                                                    </td>
                                                    <td>{{ $approval->workOrder->so_total_amount ?? '' }}</td>
                                                    <td>{{ $approval->workOrder->so_vehicle_quantity ?? '' }}</td>
                                                </tr>
                                                @if($approval->workOrder->deposit_received_as == 'custom_deposit')
                                                    <tr> 
                                                        <td colspan="6">
                                                            <table style="font-size:12px!important;">
                                                                <tr style="background-color: #dbecff;">
                                                                    <td><strong>Deposit Against Vehicles</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        @php
                                                                            $sortedVehicles = $approval->workOrder->depositAganistVin->sortBy('vin');
                                                                            $vinList = $sortedVehicles->pluck('vin')->filter()->implode(', ');
                                                                        @endphp
                                                                        {{$vinList}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($approval->workOrder->vehicles->count() > 0)
                                                    <tr>
                                                        <td colspan="6">
                                                            <table style="font-size:12px!important;" class="addon-table">
                                                                @php $serviceBreakdownShown = false; @endphp
                                                                @foreach($approval->workOrder->vehicles->sortBy('vin') as $vehicle)
                                                                    @if(isset($vehicle->addons) && $vehicle->addons->count() > 0)
                                                                        @if(!$serviceBreakdownShown)
                                                                            <tr style="border-top:1px solid #e9e9ef;background-color: #dbecff;">
                                                                                <th colspan="3">Service Breakdown</th>
                                                                            </tr>
                                                                            @php $serviceBreakdownShown = true; @endphp
                                                                        @endif
                                                                        <tr style="border-top:1px solid #e9e9ef;background-color:#f5faff;">
                                                                            <th colspan="3">Vin : {{$vehicle->vin ?? ''}}</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Addon Name</th>
                                                                            <th>Quantity</th>
                                                                            <th>Addon Description</th>
                                                                        </tr>
                                                                        @foreach($vehicle->addons->sortBy('addon_code') as $addon)
                                                                            <tr>
                                                                                <td>{{$addon->addon_code ?? ''}}</td>
                                                                                <td>{{$addon->addon_quantity ?? ''}}</td>
                                                                                <td>{{$addon->addon_description ?? ''}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @else
                                                <tr style="background-color: #dbecff;">
                                                    @foreach($sortedHistories as $history)
                                                        @php
                                                            $label = $history->field;
                                                        @endphp
                                                        <th>{{ $label }}</th>
                                                    @endforeach
                                                </tr>
                                                <tr>
                                                    @foreach($sortedHistories as $history)
                                                    @php
                                                    $newVal = $history->new_value;
                                                    if ($history->new_value == 'total_deposit') {
                                                        $newVal = 'Total Deposit';
                                                    } elseif ($history->new_value == 'custom_deposit') {
                                                        $newVal = 'Custom Deposit';
                                                    }
                                                    @endphp
                                                        <td>{{ $newVal }}</td>
                                                    @endforeach
                                                </tr>
                                                @if(count($approval->appVehAgaDepo) > 0)
                                                    <tr>
                                                        <td colspan="6">
                                                            <table style="font-size:12px!important;">
                                                                <tr style="background-color: #dbecff;">
                                                                    <td><strong>Deposit Against Vehicles</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        @php
                                                                            $vinList = $approval->appVehAgaDepo->pluck('vehicle.vin')->filter()->sort()->implode(', ');
                                                                        @endphp
                                                                        {{$vinList}}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif                                              
                                                @if(count($approval->vehicleAddonRecordHistories) > 0)
                                                    <tr>
                                                        <td colspan="6">
                                                            <table style="font-size:12px!important;">
                                                                <tr style="border-top:1px solid #e9e9ef;background-color: #dbecff;">
                                                                    <th colspan="4">Service Breakdown</th>
                                                                </tr>
                                                                <tr style="border-top:1px solid #e9e9ef;background-color: #dbecff;">
                                                                    <th>VIN</th>
                                                                    <th>Addon Code</th>
                                                                    <th>Addon Quantity</th>
                                                                    <th>Addon Description</th>
                                                                </tr>
                                                                @php
                                                                    $groupedByVin = $approval->vehicleAddonRecordHistories->groupBy(function($history) {
                                                                        return $history->addon->vehicle->vin ?? 'N/A';
                                                                    });
                                                                @endphp
                                                                @foreach($groupedByVin as $vin => $vinHistories)
                                                                    @php
                                                                        $rowCount = $vinHistories->groupBy('w_o_vehicle_addon_id')->count();
                                                                    @endphp
                                                                    @foreach($vinHistories->groupBy('w_o_vehicle_addon_id') as $addonId => $addonHistories)
                                                                        @php
                                                                            $addonCode = $addonHistories->firstWhere('field_name', 'addon_code')->new_value ?? 'N/A';
                                                                            $addonQuantity = $addonHistories->firstWhere('field_name', 'addon_quantity')->new_value ?? 'N/A';
                                                                            $addonDescription = $addonHistories->firstWhere('field_name', 'addon_description')->new_value ?? 'N/A';
                                                                        @endphp
                                                                        <tr  style="border-bottom:1px solid #e9e9ef;">
                                                                            @if ($loop->first)
                                                                                <td rowspan="{{$rowCount}}">{{$vin}}</td>
                                                                            @endif
                                                                            <td >{{$addonCode}}</td>
                                                                            <td>{{$addonQuantity}}</td>
                                                                            <td>{{$addonDescription}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endforeach
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif                                                                                         
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
        // Initialize DataTable with column filters
        // var table = $('.my-datatableclass').DataTable();
        
        $('.view-details-btn').on('click', function() {
            var id = $(this).data('id');
            var detailsRow = $('#details-' + id);

            if (detailsRow.is(':visible')) {
                detailsRow.hide();
            } else {
                detailsRow.insertAfter($('tr[data-id="' + id + '"]')).show();
            }
        });

        $('.btn-finance-approval').click(function (e) { 
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var comments = $('#financeComment_'+id).val();
            let url = '{{ route('work-order.finance-approval') }}';
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
