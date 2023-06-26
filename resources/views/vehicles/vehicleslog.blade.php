@extends('layouts.table')
@section('content')
<div class="card-header">
        <h4 class="card-title">Edit Stock Record Log</h4>
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
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Vehicle Changes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Sales Support Changes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Document Changes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">SO</a>
      </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
    <div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Changed By</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiclesLog as $vehiclesLog)
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
</div>
</div>
<div class="tab-content">
      <div class="tab-pane fade show" id="tab2"> 
    <div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Changed By</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vehiclesLogforso as $vehiclesLogforso)
            <tr data-id="1">
                <td>{{ date('d-m-Y', strtotime($vehiclesLogforso->date)) }}</td>
                <td>{{ $vehiclesLogforso->time }}</td>
                <td>
                    @php
                    $change_by = DB::table('users')->where('id', $vehiclesLogforso->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ $change_bys }}
                </td>
                <td>{{ $vehiclesLogforso->field }}</td>
                <td>{{ $vehiclesLogforso->old_value }}</td>
                <td>{{ $vehiclesLogforso->new_value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
<div class="tab-content">
      <div class="tab-pane fade show" id="tab3"> 
<div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Changed By</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
        @foreach($documentsLog as $documentsLog)
            <tr data-id="1">
                <td>{{ date('d-m-Y', strtotime($documentsLog->date)) }}</td>
                <td>{{ $documentsLog->time }}</td>
                <td>
                    @php
                    $change_by = DB::table('users')->where('id', $documentsLog->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ $change_bys }}
                </td>
                <td>{{ $documentsLog->field }}</td>
                <td>{{ $documentsLog->old_value }}</td>
                <td>{{ $documentsLog->new_value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    </div>
</div>
</div>
<div class="tab-content">
      <div class="tab-pane fade show" id="tab4"> 
    <div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Changed By</th>
                <th>Field</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
        @foreach($soLog as $soLog)
            <tr data-id="1">
                <td>{{ date('d-m-Y', strtotime($soLog->date)) }}</td>
                <td>{{ $soLog->time }}</td>
                <td>
                    @php
                    $change_by = DB::table('users')->where('id', $soLog->created_by)->first();
                    $change_bys = $change_by->name;
                    @endphp
                    {{ $change_bys }}
                </td>
                <td>{{ $soLog->field }}</td>
                <td>{{ $soLog->old_value }}</td>
                <td>{{ $soLog->new_value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</div>
    @endsection
@push('scripts')
@endpush