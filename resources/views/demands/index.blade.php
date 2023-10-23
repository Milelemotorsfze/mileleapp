@extends('layouts.table')
@section('content')
    @can('demand-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Demand Lists
                </h4>
                @can('demand-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-create');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('demands.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
                            <th>Vendor</th>
                            <th>Dealer</th>
                            <th>Steering</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            @canany('demand-edit','demand-view')
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['demand-edit','demand-view']);
                                @endphp
                                @if ($hasPermission)
                                    <th>Actions</th>
                                @endif
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                            @foreach ($demands as $key => $demand)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $demand->supplier->supplier ?? '' }}</td>
                                    <td>{{ $demand->whole_saler }}</td>
                                    <td>{{ $demand->steering }}</td>
                                    <td>{{ $demand->createdBy->name ?? '' }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($demand->created_at)->format('d M Y') ?? '' }}</td>
                                    @canany('demand-edit','demand-view')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['demand-edit','demand-view']);
                                        @endphp
                                        @if ($hasPermission)
                                            <td>
                                                <a data-placement="top" href="{{ route('demands.edit', $demand->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                            </td>
                                        @endif
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



