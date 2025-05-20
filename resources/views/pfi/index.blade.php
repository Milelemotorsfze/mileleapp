@extends('layouts.table')
@section('content')
    @can('PFI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    PFI Lists
                    @can('PFI-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end mr-3" href="{{ route('pfi.create') }}" >
                            <i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                    @endif
                @endcan
                <a  class="btn btn-sm btn-primary float-end"  style="margin-right:5px;" href="{{ route('pfi-item.list') }}" title="PFI Item Lists" >
                <i class="fa fa-table" aria-hidden="true"></i>  View PFI Items</a>
                
                @if (count($errors) > 0)
                        <div class="alert alert-danger mt-3 mb-0">
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
                        <div class="alert alert-danger mt-3 mb-0" >
                            <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                            {{ Session::get('error') }}
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success mt-3 mb-0" id="success-alert">
                            <button type="button" class="btn-close p-0 close " data-dismiss="alert">x</button>
                            {{ Session::get('success') }}
                        </div>
                    @endif
                
            </div>

            <div class="card-body">
                <div class="table-responsive" >
                    <table id="PFI-table" class="table table-striped table-editable table-edits table table-condensed" 
                        style="width:100%;" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Actions</th>
                            <th>S.NO</th>
                            <th>PFI Number</th>
                            <th>PO Number</th>
                            <th>PFI Date</th>
                            <th>Customer Name </th>
                            <th>Country</th>                   
                            <th>Delivery Location</th>
                            <th>Vendor</th>    
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Released Amount</th>
                            <th>Release Date</th>
                            <th>Comment</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                            <th>Updated Date</th>
                            <th>Updated By</th>                          
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endcan
    
@endsection
@push('scripts')
<script type="text/javascript">
    
        $(document).ready(function () {
            var table1 = $('#PFI-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:true,
            ajax: "{{ route('pfi.index') }}",
        columns: [
            {'data': 'action', 'name': 'action', orderable: false, searchable: false},
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'pfi_reference_number', 'name' : 'pfi_reference_number', orderable: false },
            {'data' : 'po_number', 'name' : 'po_number', orderable: false },
            {'data' : 'pfi_date', 'name' : 'pfi_date', orderable: false},
            {'data' : 'customer.name', 'name': 'customer.name', orderable: false },                    
            {'data' : 'country.name', 'name': 'country.name', orderable: false },        
            {'data' : 'delivery_location', 'name': 'delivery_location', orderable: false },        
            {'data' : 'supplier.supplier', 'name': 'supplier.supplier', orderable: false },    
            {'data' : 'currency', 'name': 'currency', orderable: false },        
            {'data' : 'amount', 'name': 'amount', orderable: false },  
            {'data' : 'released_amount', 'name': 'released_amount', orderable: false },          
            {'data' : 'released_date', 'name': 'released_date', orderable: false },        
            {'data' : 'comment', 'name': 'comment', orderable: false },      
            {'data' : 'created_at', 'name': 'created_at', orderable: false }, 
            {'data' : 'created_by', 'name': 'createdBy.name', orderable: false },    
            {'data' : 'updated_at', 'name': 'updated_at', orderable: false }, 
            {'data' : 'updated_by', 'name': 'updatedBy.name', orderable: false },    
                        
        ]
            })
        });
    </script>
@endpush


















