@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('bank-info');
@endphp
@if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
     Banks
    </h4>
    <div class="d-flex justify-content-end">
  <a style="float: right;" class="btn btn-sm btn-info  me-2" href="{{ route('bankaccounts.index') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
  <a class="btn btn-sm btn-success" href="{{ route('banks.create') }}" style="text-align: right;">
  <i class="fa fa-plus" aria-hidden="true"></i> Create New Bank
  </a>
</div>
    <br>
  </div>
  <div class="card-body">
  @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Display error messages -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="table-responsive">
    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
    <thead class="bg-soft-secondary">
        <tr>
            <th>Bank Name</th>
            <th>Branch Name</th>
            <th>Contact Person</th>
            <th>Address</th>
            <th>Contact Number</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($banks as $bank)
        <tr>
            <td>{{ $bank->bank_name }}</td>
            <td>{{ $bank->branch_name }}</td>
            <td>{{ $bank->contact_person }}</td>
            <td>{{ $bank->address }}</td>
            <td>{{ $bank->contact_number }}</td>

            <td>
            <a href="{{ route('banks.edit', ['bank' => $bank->id]) }}" class="btn btn-sm btn-warning shadow-sm">Edit</a>
            <form action="{{ route('banks.destroy', ['bank' => $bank->id]) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger shadow-sm">Delete</button>
</form>
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
<script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => {
                        alert.remove();
                    }, 500); // Time for the fade transition
                }, 3000); // Display time in milliseconds
            });
        });
    </script>
@endsection