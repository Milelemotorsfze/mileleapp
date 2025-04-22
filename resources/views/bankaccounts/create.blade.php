@extends('layouts.main')
@section('content')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-bank-account');
@endphp

@if ($hasPermission)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Create Bank Account</h4>
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="card-body">
            <div id="flashMessage"></div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('bankaccounts.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="entity">Entity: <span class="text-danger">*</span></label>
                        <input type="text" id="entity" class="form-control" name="entity" required>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="bank_name">Bank Name: <span class="text-danger">*</span></label>
                        <select id="bank_master_id" class="form-control" name="bank_master_id" required>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="account_number">Account Number: <span class="text-danger">*</span></label>
                        <input type="text" id="account_number" class="form-control" name="account_number" required>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="currency">Currency: <span class="text-danger">*</span></label>
                        <select id="currency" class="form-control" name="currency" required>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="JPY">JPY</option>
                            <option value="CAD">CAD</option>
                            <option value="AED">AED</option>
                            <option value="PHP">PHP</option>
                            <option value="SAR">SAR</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="current_balance">Current Balance: <span class="text-danger">*</span></label>
                        <input type="number" id="current_balance" class="form-control" name="current_balance" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12 text-center">
                        <input type="submit" name="submit" value="Submit" class="btn btn-success" />
                    </div>
                </div>
            </form>
        </div>
    </div>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@push('scripts')
<script>
        $(document).ready(function() {
            $('#bank_master_id').select2();
            $('#currency').select2();
        });
    </script>
@endpush
