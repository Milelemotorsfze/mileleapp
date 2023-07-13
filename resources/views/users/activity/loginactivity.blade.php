@extends('layouts.table')
@section('content')
@can('view-log-activity')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-log-activity']);
@endphp
@if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">User Login Activity</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>No</th>
              <th>User Name</th>
              <th>Login DateTime</th>
              <th>IP</th>
              <th>Login Status</th>
            </tr>
          </thead>
          <tbody>
            <div hidden>{{$i=0;}}</div>
            @foreach ($users as $key => $user)
            <tr data-id="1">
              <td>{{ ++$i }}</td>
              <td>{{ $user->logineUser->name }}</td>
              <td>{{ $user->created_at }}</td>
              <td>{{ $user->ip }}</td>
              <td>
              @if($user->status == 'success')
                                                <label class="badge badge-soft-success">Success</label>
                                            @elseif($user->status == 'failed')
                                                <label class="badge badge-soft-warning">Failed</label>
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

