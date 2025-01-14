@extends('layouts.main')
@section('content')

<div class="card-header">
    <h4 class="card-title">
        {{ $isEdit ? 'Edit Company Domain Record' : 'Create Company Domain Record' }}
    </h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
    </a>
</div>


@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<div class="card-body">
    <form
        id="companyDomainForm"
        name="companyDomainForm"
        action="{{ $isEdit ? route('companyDomains.update', $domain->id) : route('companyDomains.store') }}" method="post">

        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <label for="domain_name" class="form-label"><span class="text-danger">*</span> <b>Domain Name</b></label>
                <input
                    type="text"
                    name="domain_name"
                    class="form-control"
                    id="domain_name"
                    placeholder="Enter domain name"
                    value="{{ $isEdit ? $domain->domain_name : '' }}"
                    required>
                @error('domain_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-4 col-md-6">
                <label for="assigned_company" class="form-label"><span class="text-danger">*</span> <b>Assigned Company</b></label>
                <input
                    type="text"
                    name="assigned_company"
                    class="form-control"
                    id="assigned_company"
                    placeholder="Enter company name"
                    value="{{ $isEdit ? $domain->assigned_company : '' }}"
                    required>
                @error('assigned_company')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-4 col-md-6">
                <label for="domain_registrar" class="form-label"><span class="text-danger">*</span> <b>Domain Registrar</b></label>
                <input
                    type="text"
                    name="domain_registrar"
                    class="form-control"
                    id="domain_registrar"
                    placeholder="Enter registrar name"
                    value="{{ $isEdit ? $domain->domain_registrar : '' }}"
                    required>
                @error('domain_registrar')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-lg-4 col-md-6 mt-3">
                <label for="email_server" class="form-label"><span class="text-danger">*</span> <b>Email Server</b></label>
                <input
                    type="text"
                    name="email_server"
                    class="form-control"
                    id="email_server"
                    placeholder="Enter email server"
                    value="{{ $isEdit ? $domain->email_server : '' }}"
                    required>
                @error('email_server')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <br>
        <div class="col-lg-12 col-md-12">
            <button type="submit" class="btn btn-success">
                {{ $isEdit ? 'Update' : 'Submit' }}
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#companyDomainForm').validate({
            rules: {
                domain_name: {
                    required: true
                },
                assigned_company: {
                    required: true
                },
                domain_registrar: {
                    required: true
                },
                email_server: {
                    required: true
                },
            },
        });
    });
</script>
@endpush