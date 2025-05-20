@extends('layouts.table')
@section('content')

@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-view', 'company-domain-list']);
@endphp

@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">
        Company Domain Records

        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-create']);
        @endphp
        @if ($hasPermission)
        <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
        <a class="btn btn-sm btn-success float-end" href="{{ route('companyDomains.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Create New Domain Record
        </a>
        @endif
    </h4>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

@if (Session::has('error'))
<div class="alert alert-danger">
    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
    {{ Session::get('error') }}
</div>
@endif
@if (Session::has('success'))
<div class="alert alert-success" id="success-alert">
    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
    {{ Session::get('success') }}
</div>
@endif

<div class="tab-content" id="company-domain-records">
    <div class="tab-pane fade show active" id="domain-records">
        <div class="card-body">
            <div class="table-responsive">
                <table class="my-datatable table table-striped table-editable table-edits table">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Domain Code</th>
                            <th>Domain Name</th>
                            <th>Assigned Company</th>
                            <th>Domain Registrar</th>
                            <th>Email Server</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($domains as $domain)
                        <tr data-id="{{ $domain->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $domain->company_domain_code }}</td>
                            <td>{{ $domain->domain_name }}</td>
                            <td>{{ $domain->assigned_company }}</td>
                            <td>{{ $domain->domain_registrar }}</td>
                            <td>{{ $domain->email_server }}</td>
                            <td>{{ $domain->creator ? $domain->creator->name : 'N/A' }}</td>
                            <td>{{ $domain->created_at }}</td>
                            <td>
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-edit']);
                                @endphp
                                @if ($hasPermission)
                                <a data-toggle="popover" data-trigger="hover" title="Edit" class="btn btn-sm btn-info" href="{{ route('companyDomains.edit', $domain->id) }}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                @endif

                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['company-domain-delete']);
                                @endphp
                                @if ($hasPermission)
                                <form action="{{ route('companyDomains.destroy', $domain->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this domain record?')">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="card-header">
    <p class="card-title">Sorry! You don't have permission to access this page</p>
    <a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection