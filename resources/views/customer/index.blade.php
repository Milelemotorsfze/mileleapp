@extends('layouts.table')
@section('content')
    @can('list-customer')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-customer');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Customer List
                    @can('create-customer')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-customer');
                        @endphp
                        @if ($hasPermission)
                            <a  class="btn btn-sm btn-info float-end" href="{{ route('dm-customers.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> create</a>
                        @endif
                    @endcan
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive" >
                    <table id="PFI-table" class="table table-striped table-editable table-edits table table-condensed" style="">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Name</th>
                            <th>Customer Type</th>
                            <th>Country </th>
                            <th>Address</th>
                            <th>Created At</th>
                            @can('edit-customer')
                                @php
                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-customer');
                                @endphp
                                @if ($hasPermission)
                                    <th>Action</th>
                                @endif
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($customers as $key => $customer)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->type }}</td>
                                <td>{{ $customer->country->name }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($customer->created_at)->format('d M y') }}</td>
                                @can('edit-customer')
                                    @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-customer');
                                    @endphp
                                    @if ($hasPermission)
                                        <td>
                                            <a title="Edit Addon Details" class="btn btn-sm btn-info" href="{{ route('dm-customers.edit', $customer->id) }}">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
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


















