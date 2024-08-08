@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('vendor-accounts');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Vendor Accounts
    </h4>
    <br>
  </div>
  <div class="card-body">
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Vendor Name</th>
                    <th>Currency</th>
                    <th>Openining Balance</th>
                    <th>Current Balance</th>
                    <th>Transitions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                <tr>
                    <td>{{ $account->supplier->supplier }}</td>
                    <td>{{ $account->currency }}</td>
                    <td>
                        <button class="btn btn-sm {{ $account->opening_balance >= 0 ? 'btn-success' : 'btn-danger' }}" 
                                {{ $account->opening_balance >= 0 ? '' : 'disabled' }}>
                            {{ number_format($account->opening_balance, 2, '.', ',') }}
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-sm {{ $account->current_balance >= 0 ? 'btn-success' : 'btn-danger' }}" 
                                {{ $account->current_balance >= 0 ? '' : 'disabled' }}>
                            {{ number_format($account->current_balance, 2, '.', ',') }}
                        </button>
                    </td>
                    <td>
                    <a href="{{ route('vendoraccount.view', $account->id) }}" class="btn btn-sm btn-primary btn-rounded shadow-sm"><i class="fas fa-eye"></i> View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection