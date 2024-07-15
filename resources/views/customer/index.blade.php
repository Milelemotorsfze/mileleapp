@extends('layouts.table')
@section('content')
    <style>
        .modal{
            max-height: 600px;
        }
    </style>
    @can('list-customer')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('list-customer');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    Customer List
                    @can('create-customer')
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-customer');
                        @endphp
                        @if ($hasPermission)
                            <a  class="btn btn-sm btn-info float-end" title="Create New Customer" href="{{ route('dm-customers.create') }}" >
                                <i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                        @endif
                    @endcan
                </h4>
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br>
                        <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive" >
                    <table id="PFI-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Name</th>
                            <th>Customer Type</th>
                            <th>Country </th>
                            <th>Address</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($customers as $key => $customer)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->customertype }}</td>
                                <td>{{ $customer->country->name ?? '' }}</td>
                                <td>{{ $customer->address }}</td>
                                <td> {{ $customer->createdBy->name ?? ''}} </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($customer->created_at)->format('d M Y') }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm document-btn" title="To view Customer Documents" data-bs-toggle="modal" data-bs-target="#view--docs-{{$customer->id}}">
                                        <i class="fa fa-file-pdf"></i>
                                    </button>

                                    @can('edit-customer')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-customer');
                                        @endphp
                                        @if ($hasPermission)
                                        <a title="Edit Customer Details" class="btn btn-sm btn-info" href="{{ route('dm-customers.edit', $customer->id) }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </a>
                                        @endif
                                    @endcan
                                    @can('delete-customer')
                                        @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('delete-customer');
                                        @endphp
                                        @if ($hasPermission)
                                            @if($customer->is_deletable == true && $customer->created_by == Auth::id())
                                                <button data-url="{{ route('dm-customers.destroy', $customer->id) }}" data-id="{{ $customer->id }}"
                                                    class="btn btn-danger btn-sm btn-delete"><i class="fa fa-trash"></i></button>
                                            @endif
                                        @endif
                                    @endcan
                                </td>

                                <div class="modal fade" id="view--docs-{{$customer->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> Customer Documents</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if($customer->passport)
                                                    <div class="row p-2">
                                                        <h4>Passport</h4>
                                                        <div class="col-lg-12">
                                                            <div class="row p-2">
                                                                <embed src="{{ url('storage/app/public/passports/'.$customer->passport) }}"  width="400" height="600"></embed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($customer->tradelicense)
                                                    <div class="row p-2">
                                                        <h4>Trade License</h4>
                                                        <div class="col-lg-12">
                                                            <div class="row p-2">
                                                                <embed src="{{ url('storage/app/public/tradelicenses/'.$customer->tradelicense) }}"  width="400" height="600"></embed>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endcan

@endsection
@push('scripts')
<script>
 $('#PFI-table').on('click', '.btn-delete', function (e) {
        var url = $(this).data('url');
        var id = $(this).data('id');
        var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        _method: 'DELETE',
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success:function (data) {
                        location.reload();
                        alertify.success('Customer Deleted successfully.');
                    }
                });
            }
        }).set({title:"Delete Item"})
    });
 </script>
@endpush

















