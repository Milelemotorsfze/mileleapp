@extends('layouts.table')
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-modified');
  @endphp
  @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">
            Master Lead Source
        </h4>
        @can('Calls-modified')
        <a class="btn btn-sm btn-info float-end" href="{{ route('strategy.index') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Strategies Report
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('lead_source.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Master Lead Source
      </a>
      <div class="clearfix"></div>
<br>
    @endcan
    </div>
    <div class="card-body">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($data as $lead_source)
                    <tr data-id="1">
                        <td>{{ $lead_source->id}}</td>
                        <td>{{ $lead_source->source_name }}</td>
                        @if($lead_source->status == "inactive")
                    <td><label class="badge badge-soft-danger">In Active</label></td>
                @else 
                <td><label class="badge badge-soft-success">Active</label></td>
                @endif
                        <td><a title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('lead_source.edit',$lead_source->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @if($lead_source->status == "active")
                        <a title="Create Strategy" data-placement="top" class="btn btn-sm btn-success" href="{{ route('strategy.edit',$lead_source->id) }}"><i class="fa fa-plus-circle" aria-hidden="true"></i></a></td>
                    @endif
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
