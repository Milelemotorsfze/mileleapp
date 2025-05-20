@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('model-description-info');
@endphp
@if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
     Model Description
    </h4>
    <div class="d-flex justify-content-end">
  <!-- Back Button -->
  <a class="btn btn-sm btn-info me-2" href="{{ url()->previous() }}">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
  </a>
  <a class="btn btn-sm btn-primary me-2" href="{{ route('mastergrade.index') }}">
    <i class="fa fa-plus" aria-hidden="true"></i> Master Grades
  </a>

  <!-- Create Model Description Button -->
  <a class="btn btn-sm btn-success" href="{{ route('modeldescription.create') }}">
    <i class="fa fa-plus" aria-hidden="true"></i> Create Model Description
  </a>

  <!-- Master Grades Button -->
  
</div>
    <br>
  </div>
  <div class="card-body">
  @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Display error messages -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="table-responsive">
    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
    <thead class="bg-soft-secondary">
        <tr>
            <th>Model Description</th>
            <th>Brand</th>
            <th>Model Line</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            <a href="" class="btn btn-sm btn-warning shadow-sm">Edit</a>
            </td>
        </tr>
    </tbody>
</table>
    </div>
</div>
</div>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection