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
        .addon-table th {
            border-left: 1px solid #e9e9ef; 
            border-right: 1px solid #e9e9ef; 
            border-top: 1px solid #e9e9ef; 
            border-bottom: 1px solid #e9e9ef; 
            padding: 3px!important; 
            text-align: left; 
        }
 
        .addon-table td {
            border-left: 1px solid #e9e9ef; 
            border-right: 1px solid #e9e9ef; 
            border-top: 1px solid #e9e9ef; 
            border-bottom: 1px solid #e9e9ef; 
            padding: 3px!important; 
            text-align: left; 
        }
 
        .addon-table {
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
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['wo-vehicle-delivery-status-log']);
        @endphp
        @if ($hasPermission)
            <h4 class="card-title">
                @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order Vehicle Delivery Status History
            </h4>
            <a class="btn btn-sm btn-info float-end form-label" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details','export-cnf-wo-details','current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
            @endphp
            @if ($hasPermission && isset($workOrder))
                <a title="View Details" class="btn btn-sm btn-info me-2" href="{{route('work-order.show',$workOrder->id ?? '')}}">
                <i class="fa fa-eye" aria-hidden="true"></i> Work Order Details
                </a>
            @endif	
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
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <label for="choices-single-default" class="form-label"><strong>WO Number</strong></label> : {{$workOrder->wo_number ?? ''}}             
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <label for="choices-single-default" class="form-label"><strong>VIN</strong></label> : {{$vehicle->vin ?? ''}}             
            </div>
        </div>
        <div class="row mt-1">
            <div class="table-responsive">
                <table class="table table-striped table-editable table-edits table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Sl No</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Delivery At</th>                            
                            <th>location</th>
                            <th>GDN Number</th>
                            <th>Delivered At</th>
                            <th>Docs Delivery Date</th>
                            <th>Updated By</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data) > 0)
                            <div hidden>{{$i=0;}}</div>
                            @foreach($data as $one)
                                <tr data-id="{{ $one->id }}">
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <label class="badge 
                                            @if($one->status == 'Ready') 
                                                badge-soft-info 
                                            @elseif($one->status == 'Delivered') 
                                                badge-soft-success 
                                            @elseif($one->status == 'On Hold') 
                                                badge-soft-danger  
                                            @elseif($one->status == 'Delivered With Docs Hold') 
                                                badge-soft-warning 
                                            @endif">
                                            
                                            @if($one->status == 'Delivered')
                                                DELIVERED WITH DOCUMENTS
                                            @elseif($one->status == 'Delivered With Docs Hold')
                                                DELIVERED/DOCUMENTS HOLD
                                            @else
                                                {{ strtoupper($one->status) ?? '' }}
                                            @endif
                                        </label>
                                    </td>
                                    <td>{{ $one->comment ?? '' }}</td>
                                    <td>
                                        @if(!empty($one->delivery_at))
                                            {{ \Carbon\Carbon::parse($one->delivery_at)->format('d M Y, h:i:s A') }}
                                        @endif
                                    </td>                                     
                                    <td>{{$one->locationName->name ?? ''}}</td> 
                                    <td>{{$one->gdn_number ?? ''}}</td>  
                                    <td>
                                        @if(!empty($one->delivered_at))
                                            {{ \Carbon\Carbon::parse($one->delivered_at)->format('d M Y') }}
                                        @endif
                                    </td>                                  
                                    <td>
                                        @if(!empty($one->doc_delivery_date))
                                            {{ \Carbon\Carbon::parse($one->doc_delivery_date)->format('d M Y') }}
                                        @endif
                                    </td>  
                                    <td>{{ $one->user->name ?? '' }}</td>
                                    <td>
                                        @if(!empty($one->created_at))
                                            {{ \Carbon\Carbon::parse($one->created_at)->format('d M Y, h:i:s A') }}
                                        @endif
                                    </td>                                
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">No data history available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
@endsection