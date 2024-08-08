@extends('layouts.main')
@section('content')
@php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-bank');
@endphp
@if ($hasPermission)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Bank</h4>
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
            <form action="{{ route('banks.update', $bank->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="bank_name">Bank Name: <span class="text-danger">*</span></label>
                        <input type="text" id="bank_name" class="form-control" name="bank_name" value="{{ $bank->bank_name }}" required>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="branch_name">Branch Name:</label>
                        <input type="text" id="branch_name" class="form-control" name="branch_name" value="{{ $bank->branch_name }}">
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="contact_person">Contact Person:</label>
                        <input type="text" id="contact_person" class="form-control" name="contact_person" value="{{ $bank->contact_person }}">
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="address">Address:</label>
                        <input type="text" id="address" class="form-control" name="address" value="{{ $bank->address }}">
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="contact_number">Contact Number: <span class="text-danger">*</span></label>
                        <input type="number" id="contact_number" class="form-control" name="contact_number" value="{{ $bank->contact_number }}" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12 text-center">
                        <input type="submit" name="submit" value="Update" class="btn btn-success" />
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
@endpush