@extends('layouts.main')
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">Exports Leads</h4>
    </div>
    <div class="card-body">
    <form action="{{ route('calls.exportsleadsform') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row align-items-center">
        <div class="col-lg-3 col-md-6">
            <label for="fromDate" class="form-label">From:</label>
            <input type="date" id="fromDate" name="fromDate" class="form-control">
        </div>
        <div class="col-lg-3 col-md-6">
            <label for="toDate" class="form-label">To:</label>
            <input type="date" id="toDate" name="toDate" class="form-control">
        </div>
        <div class="col-lg-2 col-md-6"> <!-- Adjusted column size -->
            <label class="invisible">Submit</label> <!-- Invisible label for alignment -->
            <input type="submit" name="submit" value="Export" class="btn btn-success">
        </div>
    </div>
</form>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
@endpush