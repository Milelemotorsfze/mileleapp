@extends('layouts.table')
@section('content')
@can('warranty-list')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
@endphp
@if ($hasPermission)
<div class="card-header">
  <h4 class="card-title">Employee Relation</h4>
</div>
<div class="card-body">
  <div class="table-responsive">
    <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
      <thead>
        <tr>
          <th>No</th>
          <th>Form Name</th>
          <th>Form Type</th>
          <th>Download</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <div hidden>{{$i=0;}}</div>

        <tr data-id="1">
          <td>{{ ++$i }}</td>

          <td>
            <a class="" href="{{ route('employee.create') }}">
              <span>Form Name</span>
            </a>

          </td>

          <td>
            Pdf/Word
          </td>

          <td> Download Icon
          </td>

          <td>
            <!-- @can('warranty-view')
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-view']);
            @endphp
            @if ($hasPermission) -->
            <a class="btn btn-sm btn-success" href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <!-- @endif
            @endcan
            @can('warranty-edit')
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-edit']);
            @endphp
            @if ($hasPermission) -->
            <a class="btn btn-sm btn-info" href="#"><i class="fa fa-edit" aria-hidden="true"></i></a>
            <!-- @endif
            @endcan
            @can('warranty-delete')
            @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-delete']);
            @endphp
            @if ($hasPermission) -->
            <button type="button" class="btn btn-danger btn-sm warranty-delete sm-mt-3" data-id="#" data-url="#">
              <i class="fa fa-trash"></i>
            </button>
            <!-- @endif
            @endcan -->

          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endif
@endcan
@endsection
@push('scripts')
<script>

</script>
@endpush