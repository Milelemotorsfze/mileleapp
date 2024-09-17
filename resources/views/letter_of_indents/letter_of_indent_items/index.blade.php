@extends('layouts.table')
@section('content')
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
                        <a  class="btn btn-sm btn-primary float-end" href="{{ route('letter-of-indent-items.index', ['export' => 'EXCEL'] ) }}" ><i class="fa fa-download" aria-hidden="true"></i> Export</a>
                    @endif
                @endcan
               
                       <a  class="btn btn-sm btn-info float-end" style="margin-right:5px;" title="LOI List View"
                        href="{{ route('letter-of-indents.index') }}" > <i class="fa fa-th-large" ></i> 
                       </a>
               
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

            <div class="card-body">
            <div class="tab-pane fade show active table-responsive">
                    <table class="table table-bordered table-striped table-editable table-edits table table-condensed LOI-Items-table" style="width:100%;">
                        <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.No</th>
                                <th>LOI Number</th>
                                <th>LOI Date</th>
                                <th>LOI Approval Date</th>
                                <th>Dealer</th>
                                <th>Cutsomer Name</th>
                                <th>Cutsomer Type</th>
                                <th>Category</th>
                                <th>Country</th>                
                                <th>Item Code</th>
                                <th>Model</th>
                                <th>SFX</th>
                                <th>Steering</th>
                                <th>Model Line</th>
                                <th>PFI Number - (QTY)</th>
                                <th>Quantity</th>
                                <th>Utilized Quantity</th>
                                <th>Remaining Quantity</th>
                                <th>Sales Person</th>
                                <th>Is Expired</th>  
                                <th>Status</th>   
                                <th>SO Numbers</th>  
                                <th>Approval Remarks</th>
                                <th>LOI Comment</th>                       
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
            var table1 = $('.LOI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:true,
            ajax: "{{ route('letter-of-indent-items.index') }}",
        columns: [
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'l_o_i.uuid', 'name' : 'LOI.uuid', orderable: false},
            {'data' : 'loi_date', 'name' : 'loi_date', orderable: false },
            {'data' : 'loi_approval_date', 'name' : 'LOI.loi_approval_date', orderable: false },
            {'data' : 'l_o_i.dealers', 'name' : 'LOI.dealers' , orderable: false},
            {'data' : 'l_o_i.client.name', 'name' : 'LOI.client.name', orderable: false},
            {'data' : 'l_o_i.client.customertype', 'name': 'LOI.client.customertype', orderable: false },  
            {'data' : 'l_o_i.category', 'name': 'LOI.category', orderable: false },        
            {'data' : 'l_o_i.country.name', 'name': 'LOI.country.name', orderable: false },        
            {'data' : 'code', 'name': 'code', orderable: false },        
            {'data' : 'master_model.model', 'name': 'masterModel.model', orderable: false },        
            {'data' : 'master_model.sfx', 'name': 'masterModel.sfx', orderable: false },  
            {'data' : 'master_model.steering', 'name': 'masterModel.steering', orderable: false },          
            {'data' : 'master_model.model_line.model_line', 'name': 'masterModel.modelLine.model_line', orderable: false },
            {'data' : 'pfi_number', 'name': 'pfiItems.pfi.pfi_reference_number', orderable: false },        
            {'data' : 'quantity', 'name': 'quantity', orderable: false },      
            {'data' : 'utilized_quantity', 'name': 'utilized_quantity', orderable: false },  
            {'data' : 'remaining_quantity', 'name': 'remaining_quantity', orderable: false }, 
            {'data' : 'sales_person_id', 'name': 'LOI.salesPerson.name', orderable: false },     
            {'data' : 'is_expired', 'name': 'is_expired', orderable: false },   
            {'data' : 'status', 'name': 'LOI.status', orderable: false },        
            {'data' : 'so_number', 'name': 'LOI.soNumbers.so_number', orderable: false },
            {'data' : 'l_o_i.review', 'name': 'LOI.review', orderable: false },
            {'data' : 'l_o_i.comments', 'name': 'LOI.comments', orderable: false },
               
        ]
        });
        
    });
 
    </script>
@endpush


















