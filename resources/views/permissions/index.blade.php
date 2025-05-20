@extends('layouts.table')
@section('content')

    @can('master-permission-list')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-permission-list']);
    @endphp
    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">List Permissions</h4>
        <!-- <a  class="btn btn-sm btn-secondary float-end mr-2" href="{{ route('migrations.index') }}" >
        <i class="fa fa-check" aria-hidden="true"></i> Migration Check</a>  -->
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['master-permission-create']);
            @endphp
            @if ($hasPermission)
                <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('permissions.create') }}" text-align: right>
                    <i class="fa fa-plus" aria-hidden="true"></i> New Permission</a>
            @endif
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
            <table id="permission-table" class="table table-striped table-editable table-edits table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Module</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($permissions as $key => $permission)
                    <tr data-id="1">
                        <td>{{ ++$i }}</td>
                        <td>{{ $permission->module->name ?? '' }}</td>
                        <td>{{ $permission->slug_name }}</td>
                        <td>{{ $permission->description }}</td>
                        <td>
                            @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-permission-edit');
                            @endphp
                            @if ($hasPermission)
                                <a href="{{ route('permissions.edit', $permission->id) }}"  class="btn btn-info btn-sm">
                                    <i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
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

