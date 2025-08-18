@extends('layouts.table')
@section('content')
    @can('view-model-lines-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-model-lines-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Master Model Lines
                </h4>
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            @can('view-model-lines-list')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-model-lines-create');
                @endphp
                @if ($hasPermission)
                    <a  class="btn btn-sm btn-info float-end" href="{{ route('model-lines.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                @endif
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
                            <th>Ref.No</th>
                            <th>Brand</th>
                            <th>Model Line</th>
                            <th>Attributes</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            @can('master-model-lines-edit')
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-model-lines-edit');
                                @endphp
                                @if ($hasPermission)
                                    <th>Action</th>
                                @endif
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}

                            @foreach ($modelLines as $key => $modelLine)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $modelLine->brand->brand_name ?? '' }}</td>
                                    <td>{{ $modelLine->model_line ?? ''}}</td>
                                    <td>
                                    <a data-placement="top" href="{{ route('model-lines.specification', $modelLine->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                    </td>
                                    <td>{{ $modelLine->createdBy->name ?? '' }}</td>
                                    <td>{{ $modelLine->created_at ?? '' }}</td>
                                    @can('master-model-lines-edit')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-model-lines-edit');
                                        @endphp
                                        <td>
                                            @if ($hasPermission && $modelLine->modelDescriptions->isNotEmpty())
                                            <a data-placement="top" href="{{ route('model-lines.edit', $modelLine->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endif
                                        </td>
                                    @endcan
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

       @endif
        @endcan
@endsection



