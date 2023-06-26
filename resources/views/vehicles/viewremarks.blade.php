@extends('layouts.table')
@section('content')
<div class="card-header">
        <h4 class="card-title">View Vehicles Remarks</h4>
        @if ($previousId)
    <a class="btn btn-sm btn-info" href="{{ route('vehiclesremarks.viewremarks', $previousId) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>Vehicle No: {{$currentId}}</b>
@if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('vehiclesremarks.viewremarks', $nextId) }}">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Created By</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($remarks as $remarks)
            <tr data-id="1">
                <td>{{ date('d-m-Y', strtotime($remarks->date)) }}</td>
                <td>{{ $remarks->time }}</td>
                <td>
                    @php
                    $change_by = DB::table('users')->where('id', $remarks->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ $change_bys }}
                </td>
                <td>{{ $remarks->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
    @endsection
@push('scripts')
@endpush