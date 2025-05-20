@extends('layouts.table')
@section('content')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('master-charges-list');
    @endphp
    @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">
                Master Charges
            </h4>
            @can('create-master-charges')
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-master-charges');
                @endphp
                @if ($hasPermission)
                    <a  class="btn btn-sm btn-info float-end" href="{{ route('master-charges.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
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
                    <th>S.No</th>
                    <th>Addon Code</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                    @foreach ($masterCharges as $key => $masterCharge)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $masterCharge->addon_code }}</td>
                            <td>{{ str_replace('_', ' ', $masterCharge->type) }}</td>
                            <td>{{ $masterCharge->name }}</td>
                            <td>{{ $masterCharge->description }}</td>
                            <td>{{ \Carbon\Carbon::parse($masterCharge->created_at)->format('d M Y') }}</td>

                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        </div>
    @endif

 
@endsection





