@extends('layouts.main')
<style>
.error {
    color: red;
}
    .heading-background {
  display: inline-block;
  background-color: #f2f2f2;
  padding: 5px 10px;
}
    </style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Edit Master Warehouse</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{route('warehouse.index')}}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger" >
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
        <form id="form-update" action="{{ route('warehouse.update', $warehouse->id) }}" method="POST" >
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                    <span class="error">* </span>
                        <label for="choices-single-default" class="form-label">Name</label>
                        <input type="text" value="{{ old('name', $warehouse->name) }}" name="name" class="form-control " placeholder="Warehouse Name" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 d-flex align-items-center justify-content-center">
                    <div class="mb-3">
                        <label class="form-label d-block"><span class="error">* </span>Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusYes" value="1" {{ $warehouse->status == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusYes">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusNo" value="0" {{ $warehouse->status == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusNo">In-Active</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <h4 class="card-title heading-background text-center">Logs</h4>
	<div class="card-body">
    <div class="table-responsive">
            <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Changed By</th>
                        <th>Field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($warehouselog as $warehouselog)
                <tr data-id="1">
                        <td>{{ date('d-m-Y', strtotime($warehouselog->date)) }}</td>
                        <td>{{$warehouselog->time}}</td>
                        <td>{{$warehouselog->status}}</td>
                        <td>
                            @php
                                $change_by = DB::table('users')->where('id', $warehouselog->created_by)->first();
                                $change_bys = $change_by->name;
                            @endphp
                            {{$change_bys}}
                        </td>
                        <td>{{$warehouselog->field}}</td>
                        <td>{{ $warehouselog->old_value }}</td>
                        <td>{{ $warehouselog->new_value }}</td>
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