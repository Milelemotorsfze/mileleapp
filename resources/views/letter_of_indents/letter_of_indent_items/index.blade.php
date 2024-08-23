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

                        <a  class="btn btn-sm btn-primary float-end" href="{{ route('letter-of-indent-items.index', ['export' => 'EXCEL'] ) }}" ><i class="fa fa-download" aria-hidden="true"></i> Export</a>
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

            <div class="card-body">
            <div class="tab-pane fade show active table-responsive">
                    <table class="table table-bordered LOI-Items-table" style = "width:100%;">
                        <thead>
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
            {'data' : 'l_o_i.id', 'name' : 'LOI.id'},
            {'data' : 'loi_date', 'name' : 'loi_date' },
            {'data' : 'loi_approval_date', 'name' : 'LOI.loi_approval_date' },
            {'data' : 'l_o_i.dealers', 'name' : 'LOI.dealers' },
            {'data' : 'l_o_i.client.name', 'name' : 'LOI.client.name'},
            {'data' : 'l_o_i.client.customertype', 'name': 'LOI.client.customertype' },  
            {'data' : 'l_o_i.category', 'name': 'LOI.category' },        
            {'data' : 'l_o_i.client.country.name', 'name': 'LOI.client.country.name' },        
            {'data' : 'code', 'name': 'code' },        
            {'data' : 'master_model.model', 'name': 'masterModel.model' },        
            {'data' : 'master_model.sfx', 'name': 'masterModel.sfx' },  
            {'data' : 'master_model.steering', 'name': 'masterModel.steering' },          
            {'data' : 'master_model.model_line.model_line', 'name': 'masterModel.modelLine.model_line' },        
            {'data' : 'quantity', 'name': 'quantity' },      
            {'data' : 'utilized_quantity', 'name': 'utilized_quantity' },  
            {'data' : 'remaining_quantity', 'name': 'remaining_quantity' }, 
            {'data' : 'sales_person_id', 'name': 'LOI.salesPerson.name' },     
            {'data' : 'is_expired', 'name': 'is_expired' },   
            {'data' : 'status', 'name': 'LOI.status' },        
            {'data' : 'so_number', 'name': 'LOI.soNumbers.so_number' },
            {'data' : 'l_o_i.review', 'name': 'LOI.review' },
            {'data' : 'l_o_i.comments', 'name': 'LOI.comments' },
               
        ]
        });
        
    });
 
    </script>
@endpush


















