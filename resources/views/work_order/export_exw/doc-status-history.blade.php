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
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-doc-status-log']);
        @endphp
        @if ($hasPermission)
            <h4 class="card-title">
                @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order Documentation Status History
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
            @include('work_order.export_exw.doc_approval')
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
                <label for="choices-single-default" class="form-label"><strong>Has Claim</strong></label> : {{$workOrder->has_claim ?? ''}}             
            </div>
            @if(isset($workOrder->boe) && count($workOrder->boe) > 0)
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <table class="table table-striped table-editable table-edits table-condensed my-datatableclass">
                        <thead style="background-color: #e6f1ff">
                            <tr>
                                <th>BOE Number</th>
                                <th>Declaration Number</th>
                                <th>Declaration date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workOrder->boe as $one)
                                <tr>
                                    <td>{{ $one->boe ?? '' }}</td>
                                    <td>{{ $one->declaration_number ?? ''}}</td>
                                    <td>@if($one->declaration_date != ''){{\Carbon\Carbon::parse($one->declaration_date)->format('d M Y') ?? ''}}@endif</td>                               
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="row mt-1">
            <div class="table-responsive">
                <table class="table table-striped table-editable table-edits table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Sl No</th>
                            <th>Status</th>
                            <th>Comment</th>
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
                                        <label class="badge @if($one->is_docs_ready == 'In Progress') badge-soft-info @elseif($one->is_docs_ready == 'Ready') badge-soft-success @elseif($one->is_docs_ready == 'Not Initiated') badge-soft-danger @endif">{{ $one->is_docs_ready ?? ''}}</label>
                                    </td>
                                    <td>{{ $one->documentation_comment ?? '' }}</td>
                                    <td>{{ $one->user->name ?? '' }}</td>
                                    <td>@if($one->doc_status_changed_at != ''){{ $one->doc_status_changed_at->format('d M Y,  h:i:s A') ?? '' }}@endif</td>                                 
                                </tr>
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
    </div>
</div>
</body>
@endsection