@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">Addon List</h4>
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('addon.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New Addon</a>
  </div>

@endsection
