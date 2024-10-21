@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-view','salary-certificate-list']);
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">
        Salary Certification Requests Info

        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-create']);
        @endphp
        @if ($hasPermission)
        <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
        <a class="btn btn-sm btn-success float-end" href="{{ route('employeeRelation.salaryCertificate.create') }}" text-align: right>
            <i class="fa fa-plus" aria-hidden="true"></i> Create New Request
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
</div>
<div class="tab-content" id="selling-price-histories">
    <div class="tab-pane fade show active" id="pending-hiring-requests">
        <div class="card-body">
            <div class="table-responsive">
                <table class="my-datatable table table-striped table-editable table-edits table">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Requested By</th>
                            <!-- <th>Purpose</th> -->
                            <th>Requested For</th>
                            <th>Request Details</th>
                            <th>Bank Name</th>
                            <th>Status</th>
                            <th>Date Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                        <tr data-id="1">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $certificate->creator ? $certificate->creator->name : 'N/A' }}</td>
                            <!-- <td>{{ $certificate->purpose_of_request }}</td> -->
                            <td>{{ $certificate->requestedFor->name }}</td>
                            <td>{{ $certificate->salary_certficate_request_detail }}</td>
                            <td>{{ $certificate->bank_name }}</td>
                            <td>
                                <span class="badge @if ($certificate->status === 'pending') badge-soft-info 
                                @elseif ($certificate->status === 'rejected') badge-soft-warning
                                @elseif ($certificate->status === 'approved') badge-soft-success 
                                @endif ">
                                    <b class="fs-6">{{ ucfirst($certificate->status) }}</b>
                                </span>
                            </td>

                            <td>{{ $certificate->created_at }}</td>
                            <td>

                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-edit']);
                                @endphp
                                @if ($hasPermission)

                                <a data-toggle="popover" data-trigger="hover" title="Edit" class="btn btn-sm btn-info {{ $certificate->status !== 'pending' ? '' : ''}}" href="{{ route('employeeRelation.salaryCertificate.edit', $certificate->id) }}">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </a>
                                @endif

                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-view']);
                                @endphp
                                @if ($hasPermission)
                                <a title="View Details" class="btn btn-sm btn-warning {{ $certificate->status === 'pending' ? 'd-none' : ''}}" href="{{ route('employeeRelation.salaryCertificate.show', $certificate->id) }}">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                @endif
                                @php
                                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['salary-certificate-download']);
                                @endphp
                                @if ($hasPermission)
                                <a title="Download" class="btn btn-sm btn-success {{ $certificate->status === 'approved' ? '' : 'disabled'}}" href="{{ route('employeeRelation.salaryCertificate.downloadCertificate', $certificate->id) }}">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                </a>
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
    <p class="card-title">Sorry ! You don't have permission to access this page</p>
    <a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection