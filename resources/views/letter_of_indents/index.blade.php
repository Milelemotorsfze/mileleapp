@extends('layouts.table')
@section('content')
    <style>
        /* .modal {
            position: absolute;
            min-height: 500px;
        } */
        .widthinput{
            height:32px!important;

        }
        /* body.modal-open {
            overflow: hidden;
        } */
    </style>
 
    @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    LOI Lists
                </h4>
                @can('LOI-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-create');
                    @endphp
                    @if ($hasPermission)

                        <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.create') }}" ><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                    @endif
                @endcan
                <div class="card-body">
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
                        <div class="alert alert-danger" >
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
            </div>

            <div class="portfolio">
                <ul class="nav nav-pills nav-fill" id="my-tab">
                   <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="pill" href="#new-LOI">New LOI</a>
                    </li>
                   <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#waiting-for-approval-LOI">Waiting For Approval</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#supplier-response-LOI">Supplier Response LOI</a>
                    </li> 
                   
                </ul>
            </div>
            <div class="tab-content">
                <div class="card-body">
                <div class="tab-pane fade show active table-responsive" id="new-LOI">
                        <table class="table table-bordered new-LOI-table" style = "width:100%;">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Cutsomer Name</th>
                                    <th>Customer Type</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Sales Person</th>
                                    <th>Status</th>
                                    <th>Is Expired</th>
                                    <th>LOI Quantity</th>
                                    <th>LOI Templates</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                    <th>Send Supplier Approval</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                      
                    </div>
                    <div class="tab-pane fade table-responsive" id="waiting-for-approval-LOI">
                        <table class="table table-bordered waiting-for-approval-table" style = "width:100%;">
                            <thead>
                                <tr>
                                <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Cutsomer Name</th>
                                    <th>Customer Type</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Sales Person</th>
                                    <th>Status</th>
                                    <th>Is Expired</th>
                                    <th>LOI Quantity</th>
                                    <th>LOI Templates</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                    <th>Approve / Reject </th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                      
                    </div>
                    <div class="tab-pane fade table-responsive" id="supplier-response-LOI">
                        <table class="table table-bordered supplier-response-table" style = "width:100%;">
                            <thead>
                                <tr>
                                <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Cutsomer Name</th>
                                    <th>Customer Type</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Sales Person</th>
                                    <th>Is Expired</th>
                                    <th>LOI Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th>Approvd Status</th>
                                    <th>Approved / Rejected Date</th>
                                    <th>Remarks</th>
                                    <th>LOI Templates</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                    <th>Utilization QTY Update</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                      
                    </div>                             
                </div>  
            </div>
        @endif
    @endcan
@endsection

@push('scripts')

    <script type="text/javascript">
        $(document).ready(function () {
            var table1 = $('.new-LOI-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}",
        columns: [
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'cutomer_name', 'name' : 'cutomer_name'},
            {'data' : 'customer_type', 'name': 'customer_type' },        
            {'data' : 'customer_country', 'name': 'customer_country' },        
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'so_number' },  
            {'data' : 'sales_person', 'name': 'sales_person' },        
            {'data' : 'submission_status', 'name': 'submission_status' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' },   
            {'data' : 'loi_templates', 'name': 'loi_templates', orderable: false, searchable: false },   
            {'data' : 'createdBy', 'name': 'createdBy' },      
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updated_by' },        
            {'data' : 'updated_at', 'name': 'updated_at' },        
            {'data' : 'approval_button', 'name': 'approval_button', orderable: false, searchable: false },      
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        
        });
            var table2 = $('.waiting-for-approval-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'WAITING_FOR_APPROVAL']) }}",
        columns: [
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex','title' : 'S.NO:', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'cutomer_name', 'name' : 'cutomer_name'},
            {'data' : 'customer_type', 'name': 'customer_type' },        
            {'data' : 'customer_country', 'name': 'customer_country' },        
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'so_number' },  
            {'data' : 'sales_person', 'name': 'sales_person' },        
            {'data' : 'submission_status', 'name': 'submission_status' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' },   
            {'data' : 'loi_templates', 'name': 'loi_templates' },   
            {'data' : 'createdBy', 'name': 'createdBy' },      
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updated_by' },        
            {'data' : 'updated_at', 'name': 'updated_at' },  
            {'data' : 'approval_button', 'name': 'approval_button', orderable: false, searchable: false },    
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        
     });
        var table3 = $('.supplier-response-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'SUPPLIER_RESPONSE']) }}",
        columns: [
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex','title' : 'S.NO:', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'cutomer_name', 'name' : 'cutomer_name'},
            {'data' : 'customer_type', 'name': 'customer_type' },        
            {'data' : 'customer_country', 'name': 'customer_country' },        
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'so_number' },  
            {'data' : 'sales_person', 'name': 'sales_person' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' },   
            {'data' : 'utilized_quantity', 'name': 'utilized_quantity' },        
            {'data' : 'submission_status', 'name': 'submission_status' },        
            {'data' : 'loi_approval_date', 'name': 'loi_approval_date' },   
            {'data' : 'review', 'name': 'review' },   
            {'data' : 'loi_templates', 'name': 'loi_templates' },   
            {'data' : 'createdBy', 'name': 'createdBy' },      
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updated_by' },        
            {'data' : 'updated_at', 'name': 'updated_at' },  
            {'data' : 'approval_button', 'name': 'approval_button', orderable: false, searchable: false },           
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        
        });

          
           
           
        });

        
       
    </script>
@endpush


















