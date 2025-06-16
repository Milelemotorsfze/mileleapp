@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-grade-list');
@endphp
@if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">Master Grades</h4>
    <div class="d-flex justify-content-end">
      <a style="float: right;" class="btn btn-sm btn-info me-2" href="{{ url()->previous() }}"><i class="fa fa-arrow-left"></i> Back</a>
      <a class="btn btn-sm btn-success" href="{{ route('mastergrade.create') }}"><i class="fa fa-plus"></i> Create Model Grades</a>
    </div>
    <br>
  </div>
  <div class="card-body">
    <!-- Success and Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Grade</th>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mastergrades as $mastergrade)
                <tr>
                    <td>{{ $mastergrade->grade_name }}</td>
                    <td>{{ $mastergrade->modelLine->brand->brand_name ?? 'N/A' }}</td>
                    <td>{{ $mastergrade->modelLine->model_line ?? 'N/A' }}</td>
                    <td>{{ $mastergrade->creator->name ?? 'N/A' }}</td>
                    <td>{{ $mastergrade->created_at->format('d M Y, H:i A') }}</td>
                    <td>
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('update-master-grade');
                        @endphp
                        @if ($hasPermission && $mastergrade->modelDescriptions->isEmpty())
                            <a href="{{ route('mastergrade.edit', $mastergrade->id ) }}" class="btn btn-info btn-sm" title="Edit Grade">
                                <i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#dtBasicExample1').DataTable(); // Initialize DataTables
    });
</script>
@endpush