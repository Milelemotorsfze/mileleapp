@extends('layouts.table')
@section('content')
<div class="card-header">
        <h4 class="card-title">Stock report change log</h4>
        @if ($previousId)
    <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $previousId) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>Vehicles No: {{$currentId}}</b>
@if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $nextId) }}">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
    <div class="row">
    @php
    $po = DB::table('purchasing_order')->where('id', $vehicle->purchasing_order_id)->first();
    $po_date = $po->po_date ?: '';
    $po_number = $po->po_number ?: '';
    $grn = $vehicle->grn_id ? DB::table('grn')->where('id', $vehicle->grn_id)->first() : null;
    $grn_date = $grn ? $grn->date : null;
    $grn_number = $grn ? $grn->grn_number : null;
    $gdn = $vehicle->gdn_id ? DB::table('gdn')->where('id', $vehicle->gdn_id)->first() : null;
    $gdn_date = $gdn ? $gdn->date : null;
    $gdn_number = $gdn ? $gdn->gdn_number : null;
    use Carbon\Carbon;
    if($po_date){
    $po_date = Carbon::createFromFormat('Y-m-d', $po_date)->format('d-M-Y');
    }
    if($grn_date){
    $grn_date = Carbon::createFromFormat('Y-m-d', $grn_date)->format('d-M-Y');
    }
    if($gdn_date){
    $gdn_date = Carbon::createFromFormat('Y-m-d', $gdn_date)->format('d-M-Y');
    }
    @endphp
    <div class="col-1">PO Number: {{$po_number}}</div>
    <div class="col-2">PO Date: {{$po_date}}</div>
    <div class="col-1">GRN Number: {{$grn_number}}</div>
    <div class="col-2">GRN Date: {{$grn_date}}</div>
    <div class="col-1">GDN Number: {{$gdn_number}}</div>
    <div class="col-2">GDN Date: {{$gdn_date}}</div>
    <div class="col-2">VIN: {{$vehicle->vin}}</div>
</div>
<hr>
    <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Updated By</th>
                <th>Role</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mergedLogs as $vehiclesLog)
            <tr data-id="1">
                <td>{{ date('d-m-Y', strtotime($vehiclesLog->date)) }}</td>
                <td>{{ $vehiclesLog->time }}</td>
                <td>
                    @php
                    $change_by = DB::table('users')->where('id', $vehiclesLog->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ $change_bys }}
                </td>
                <td>{{ $vehiclesLog->field }}</td>
                <td>{{ $vehiclesLog->old_value }}</td>
                <td>{{ $vehiclesLog->new_value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
    @endsection
@push('scripts')
@endpush