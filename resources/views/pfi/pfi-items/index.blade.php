@extends('layouts.table')
@section('content')
 
    @can('PFI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">
                    PFI Items Lists
                </h4>
                 @can('export-pfi-items')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('export-pfi-items');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-primary float-end" href="{{ route('pfi-item.list', ['export' => 'EXCEL'] ) }}" >
                            <i class="fa fa-download" aria-hidden="true"></i> Export</a>
                    @endif
                @endcan 
               
                       <a  class="btn btn-sm btn-info float-end" style="margin-right:5px;" title="LOI List View"
                        href="{{ route('pfi.index') }}" > <i class="fa fa-th-large" ></i> 
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
                    <table class="table table-bordered table-striped table-editable table-edits table table-condensed PFI-Items-table" style="width:100%;">
                        <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.No</th>
                                <th>LOI Item Code</th> 
                                <th>PFI Date</th>                                                                              
                                <th>PFI Number</th>
                                <th>Customer Name </th>
                                <th>Country</th>  
                                <th>Vendor</th>  
                                <th>Currency</th> 
                                <th>Steering</th>                              
                                <th>Brand</th>
                                <th>Model Line</th>                           
                                <th>Model</th>
                                <th>SFX</th>
                                <th>PFI Quantity</th>
                                <th>Unit Price</th>
                                <th>Amount</th>
                                <th>Comment</th>                      
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
            var table1 = $('.PFI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:true,
            ajax: "{{ route('pfi-item.list') }}",
        columns: [
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'letter_of_indent_item.code', 'name' : 'letterOfIndentItem.code' , orderable: false},
            {'data' : 'pfi_date', 'name' : 'pfi_date', orderable: false},
            {'data' : 'pfi.pfi_reference_number', 'name' : 'pfi.pfi_reference_number', orderable: false},
            {'data' : 'pfi.customer.name', 'name' : 'pfi.customer.name', orderable: false },
            {'data' : 'pfi.country.name', 'name' : 'pfi.country.name', orderable: false },
            {'data' : 'pfi.supplier.supplier', 'name' : 'pfi.supplier.supplier', orderable: false },
            {'data' : 'pfi.currency', 'name' : 'pfi.currency', orderable: false },         
            {'data' : 'master_model.steering', 'name': 'masterModel.steering', orderable: false }, 
            {'data' : 'master_model.model_line.brand.brand_name', 'name': 'masterModel.modelLine.brand.brand_name', orderable: false },        
            {'data' : 'master_model.model_line.model_line', 'name': 'masterModel.modelLine.model_line', orderable: false },
            {'data' : 'master_model.model', 'name': 'masterModel.model', orderable: false },      
            {'data' : 'master_model.sfx', 'name': 'masterModel.sfx', orderable: false },              
            {'data' : 'pfi_quantity', 'name': 'pfi_quantity', orderable: false },   
            {'data' : 'unit_price', 'name': 'unit_price', orderable: false },        
            {'data' : 'amount', 'name': 'pfi.amount', orderable: false },   
            {'data' : 'pfi.comment', 'name': 'pfi.comment', orderable: false },        
        ]
        });
        
    });
 
    </script>
@endpush


















