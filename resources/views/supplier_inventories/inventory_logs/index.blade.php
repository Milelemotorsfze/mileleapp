@extends('layouts.table')
@section('content')
    @can('inventory-log-details')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('inventory-log-details');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Inventory Log Details
                </h4>
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
                            <th>S.No:</th>
                            <th>Action</th>
                            <th>updated By</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                            @foreach ($supplierInventoryLogs as $key => $supplierInventoryLog)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $supplierInventoryLog->action }}</td>
                                    <td>{{ $supplierInventoryLog->updatedBy->name ?? '' }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($supplierInventoryLog->created_at)->format('d M Y') ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    @endcan
@endsection



