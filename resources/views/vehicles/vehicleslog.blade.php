@extends('layouts.main')
<style>
.log-section {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 5px;
}

.log-title {
  font-size: 20px;
  margin-bottom: 15px;
}

.log-list {
  list-style-type: none;
  padding-left: 0;
}

.log-list li {
  margin-bottom: 10px;
}

.log-label {
  font-weight: bold;
  color: #555555;
}

.log-value {
  color: #888888;
}
</style>
@section('content')
<div class="card-header">
        <h4 class="card-title">Edit Stock Record Log</h4>
        @if ($previousId)
    <a class="btn btn-sm btn-info" href="">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
@endif
<b>VIN No: {{$currentId}}</b>
@if ($nextId)
    <a class="btn btn-sm btn-info" href="">
       <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Changed By</th>
                        <th>Department</th>
                        <th>Field</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>June 20, 2023</td>
                        <td>10:30 AM</td>
                        <td>Ali Atif</td>
                        <td>Sales</td>
                        <td>SO Number</td>
                        <td>5432</td>
                        <td>7654</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endsection
@push('scripts')
@endpush