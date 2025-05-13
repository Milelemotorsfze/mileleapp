@extends('layouts.table')

@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Master Warehouse Info
        </h4>
        @can('warehouse-edit')
            <a  class="btn btn-sm btn-info float-end" href="{{ route('warehouse.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
        @endcan
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>Name</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($warehouselist as $key => $warehouselist)
                    <tr data-id="1">
                        <td>{{ $warehouselist->name ?? ''}}</td>
                        <td>
                        @php
                        $names = $warehouselist->created_by ? DB::table('users')->where('id', $warehouselist->created_by )->first() : null;;
                        $created_bys = $names ? $names->name : null;
                        @endphp
                        {{ $created_bys ?? '' }}</td>
                        <td>
                            @if($warehouselist->status == 1)
                                <span class="badge bg-success fs-6">Active</span>
                            @else
                                <span class="badge bg-danger fs-6">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @can('warehouse-edit')
                                <a data-placement="top" href="{{ route('warehouse.edit', $warehouselist->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection