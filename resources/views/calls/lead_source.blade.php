@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Master Lead Source
        </h4>
        @can('Calls-modified')
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
                        <td>{{ $lead_source->status }}</td>
                        <td><a title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('lead_source.edit',$lead_source->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a title="Create Strategy" data-placement="top" class="btn btn-sm btn-success" href="{{ route('strategy.edit',$lead_source->id) }}"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
