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
        <table class="my-datatable table table-striped table-editable table-edits table">
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
                @php $i = 0; @endphp
                @foreach ([
                ['route' => 'employee-passport_request.create-or-edit', 'id' => $id ?? 'new', 'name' => 'Passport Request Form', 'docType' => 'pdf'],
                ['route' => 'employee_liability.create', 'id' => $id ?? 'new', 'name' => 'Employee Liability Form', 'docType' => 'pdf'],
                ] as $form)
                <tr data-id="{{ ++$i }}">
                    <td>{{ $i }}</td>
                    <td>
                        <a class="" href="{{ route($form['route'], ['id' => $form['id']]) }}">
                            <span>{{ $form['name'] }}</span>
                        </a>
                    </td>
                    <td>{{ $form['docType'] }}</td>
                    <td>
                        <a href="#">
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-success" href="{{ route($form['route'], ['id' => $form['id']]) }}">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-sm btn-info" href="#">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <button type="button" class="btn btn-danger btn-sm warranty-delete sm-mt-3" data-id="#" data-url="#">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endif
@endcan
@endsection
@push('scripts')
<script>
    // Your scripts here
</script>
@endpush