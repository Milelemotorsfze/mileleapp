@extends('layouts.table')
@section('content')
    <style>
        
        .widthinput{
            height:32px!important;

        }
       
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
                <a  class="btn btn-sm btn-primary float-end" style="margin-right:5px;" title="Model-SFX Detail View" href="{{ route('letter-of-indent-items.index') }}" >
                    <i class="fa fa-table" ></i> </a>
                <div class="card-body">
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
            </div>

            <div class="portfolio">
                <ul class="nav nav-pills nav-fill" id="my-tab">
                   <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="pill" href="#new-LOI">New LOI</a>
                    </li>
                   <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#waiting-for-approval-LOI">Waiting For Supplier Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#waiting-for-ttc-approval-LOI">Waiting For TTC Approval</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#supplier-response-LOI">Supplier Response LOI</a>
                    </li> 
                </ul>
            </div>
            <div class="tab-content">
                <div class="card-body">
                <div class="tab-pane fade show active table-responsive" id="new-LOI">
                        <table class="table table-bordered  table-striped table-editable table-edits table table-condensed new-LOI-table" style = "width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>Action</th>
                                    <th>Send For Supplier Approval</th>
                                    <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Customer Name</th>
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
                                    <th>Comments</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                      
                    </div>
                    <div class="tab-pane fade table-responsive" id="waiting-for-approval-LOI">
                        <table class="table table-bordered  table-striped table-editable table-edits table table-condensed waiting-for-approval-table" style = "width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>Action</th>
                                    <th>Status Update</th>
                                    <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Customer Name</th>
                                    <th>Customer Type</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Sales Person</th>
                                    <th>Status</th>
                                    <th>Is Expired</th>
                                    <th>LOI Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th>LOI Templates</th>
                                    <th>Comments</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                      
                    </div>
                    <div class="tab-pane fade table-responsive" id="waiting-for-ttc-approval-LOI">
                        <table class="table table-bordered table-striped table-editable table-edits table table-condensed waiting-for-ttc-approval-table" style ="width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>Action</th>
                                    <th>Status Update</th>
                                    <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Customer Name</th>
                                    <th>Customer Type</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Sales Person</th>
                                    <th>Status</th>
                                    <th>Is Expired</th>
                                    <th>LOI Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th>LOI Templates</th>
                                    <th>Comments</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                      
                    </div>
                    <div class="tab-pane fade table-responsive" id="supplier-response-LOI">
                        <table class="table table-bordered table-striped table-editable table-edits table table-condensed supplier-response-table" style = "width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>Action</th>
                                    <th>S.No</th>
                                    <th>LOI Number</th>
                                    <th>LOI Date</th>
                                    <th>Customer Name</th>
                                    <th>Customer Type</th>
                                    <th>Country</th>
                                    <th>Category</th>
                                    <th>Dealers</th>
                                    <th>So Number</th>
                                    <th>Sales Person</th>
                                    <th>Is Expired</th>
                                    <th>LOI Quantity</th>
                                    <th>Utilized Quantity</th>
                                    <th>Approved Status</th>
                                    <th>Approved / Rejected Date</th>
                                    <th>Remarks</th>
                                    <th>Comments</th>
                                    <th>LOI Templates</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
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
            searching:true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}",
        columns: [
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {'data' : 'approval_button', 'name': 'approval_button', orderable: false, searchable: false },      
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'client.name', 'name' : 'client.name'},
            {'data' : 'client.customertype', 'name': 'client.customertype' },          
            {'data' : 'country.name', 'name': 'country.name' },        
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'soNumbers.so_number' },  
            {'data' : 'sales_person_id', 'name': 'salesPerson.name' },        
            {'data' : 'submission_status', 'name': 'submission_status' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' },   
            {'data' : 'loi_templates', 'name': 'loi_templates' },   
            {'data' : 'comments', 'name': 'comments' },   
            {'data' : 'created_by', 'name': 'createdBy.name' },      
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updatedBy.name' },        
            {'data' : 'updated_at', 'name': 'updated_at' },        
        ]
        
        });
            var table2 = $('.waiting-for-approval-table').DataTable({
            searching:true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'WAITING_FOR_APPROVAL']) }}",
        columns: [
            {'data': 'action', name: 'action', orderable: false, searchable: false},
            {'data' : 'approval_button', 'name': 'approval_button', orderable: false, searchable: false },    
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex','title' : 'S.NO:', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'client.name', 'name' : 'client.name'},
            {'data' : 'client.customertype', 'name': 'client.customertype' },         
            {'data' : 'country.name', 'name': 'country.name' },         
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'soNumbers.so_number' },  
            {'data' : 'sales_person_id', 'name': 'salesPerson.name' },        
            {'data' : 'submission_status', 'name': 'submission_status' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' }, 
            {'data' : 'utilized_quantity', 'name': 'utilized_quantity' },    
            {'data' : 'loi_templates', 'name': 'loi_templates' },
            {'data' : 'comments', 'name': 'comments' },      
            {'data' : 'created_by', 'name': 'createdBy.name' },       
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updatedBy.name' },        
            {'data' : 'updated_at', 'name': 'updated_at' },  
        ]
        
     });
     var table3 = $('.waiting-for-ttc-approval-table').DataTable({
            searching:true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'WAITING_FOR_TTC_APPROVAL']) }}",
        columns: [
            {'data': 'action', name: 'action', orderable: false, searchable: false},
            {'data' : 'approval_button', 'name': 'approval_button', orderable: false, searchable: false },    
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex','title' : 'S.NO:', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'client.name', 'name' : 'client.name'},
            {'data' : 'client.customertype', 'name': 'client.customertype' },         
            {'data' : 'country.name', 'name': 'country.name' },         
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'soNumbers.so_number' },  
            {'data' : 'sales_person_id', 'name': 'salesPerson.name' },        
            {'data' : 'submission_status', 'name': 'submission_status' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' }, 
            {'data' : 'utilized_quantity', 'name': 'utilized_quantity' },    
            {'data' : 'loi_templates', 'name': 'loi_templates' },
            {'data' : 'comments', 'name': 'comments' },      
            {'data' : 'created_by', 'name': 'createdBy.name' },       
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updatedBy.name' },        
            {'data' : 'updated_at', 'name': 'updated_at' },  
        ]
        
     });
        var table4 = $('.supplier-response-table').DataTable({
            searching:true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('letter-of-indents.index', ['tab' => 'SUPPLIER_RESPONSE']) }}",
        columns: [
            {data: 'action', name: 'action', orderable: false, searchable: false},
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex','title' : 'S.NO:', orderable: false, searchable: false },
            {'data' : 'uuid', 'name' : 'uuid'},
            {'data' : 'date', 'name' : 'date' },
            {'data' : 'client.name', 'name' : 'client.name'},
            {'data' : 'client.customertype', 'name': 'client.customertype' },        
            {'data' : 'country.name', 'name': 'country.name' },        
            {'data' : 'category', 'name': 'category' },        
            {'data' : 'dealers', 'name': 'dealers' },        
            {'data' : 'so_number', 'name': 'soNumbers.so_number' },  
            {'data' : 'sales_person_id', 'name': 'salesPerson.name' },        
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'loi_quantity', 'name': 'loi_quantity' },   
            {'data' : 'utilized_quantity', 'name': 'utilized_quantity' },        
            {'data' : 'status', 'name': 'status' },        
            {'data' : 'loi_approval_date', 'name': 'loi_approval_date' },   
            {'data' : 'review', 'name': 'review' },   
            {'data' : 'comments', 'name': 'comments' },   
            {'data' : 'loi_templates', 'name': 'loi_templates' },   
            {'data' : 'created_by', 'name': 'createdBy.name' },     
            {'data' : 'created_at', 'name': 'created_at' },        
            {'data' : 'updated_by', 'name': 'updatedBy.name' },        
            {'data' : 'updated_at', 'name': 'updated_at' },  
        ]
        
        });

    });

        
       
    </script>
@endpush


















