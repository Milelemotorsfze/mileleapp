@extends('layouts.table')
@section('content')
 
    @can('PFI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
        @endphp
        @if ($hasPermission)
        <style>
            .medium-width {
                max-width:200px !important;
                min-width:100px !important;
                height:20px!important;
            }
          
            .small-width{
                max-width:150px !important;
            }
        </style>

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
                    <table id="PFI-Items-table" class="table table-bordered table-striped table-editable table-edits table table-condensed" style="width:100%;">
                   
                    <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.No</th>
                                <th>LOI Item Code
                                    <input class="small-width" onkeyup="reload()" type="text" id="code" placeholder="LOI Item Code">
                                </th> 
                                <th>LOI Status
                                    <input type="text" id="loi-status" onkeyup="reload()" placeholder="LOI Status">
                                </th> 
                                <th>PFI Date
                                    <input type="date" class="small-width" onchange="reload()" id="pfi-date" placeholder="PFI Date">
                                </th>                                                                              
                                <th>PFI Number  <input type="text" onkeyup="reload()" class="small-width" id="pfi-number" placeholder="PFI Number"></th>
                                <th>
                                    Customer Name
                                     <select class="medium-width" id="customer-id" multiple onchange="reload()" >
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}"> {{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    Country 
                                    <select class="small-width" id="country-id" multiple onchange="reload()">
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}"> {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </th>  
                                <th>
                                    Vendor  
                                     <select  class="small-width" id="supplier-id" multiple onchange="reload()">
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}"> {{ $supplier->supplier }}</option>
                                        @endforeach
                                    </select>
                                </th>  
                                <th>Currency 
                                    <select  class="small-width" id="currency" onchange="reload()">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </th> 
                                <th>Steering 
                                    <select  class="small-width" id="steering" onchange="reload()">
                                        <option value="LHD">LHD</option>
                                        <option value="RHD">RHD</option>
                                    </select>
                                </th>                              
                                <th>Brand</th>
                                <th>Model Line</th>                           
                                <th>Model</th>
                                <th>SFX</th>
                                <th>PFI Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>PFI Amount</th>
                                <th>Comment</th>                      
                            </tr>
                           
                        </thead>
                        <tbody>
                        </tbody>
                    </table>                      
                </div>                          
            </div>  
        </form>
        @endif
    @endcan
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            
            var table = $('#PFI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:true,
            ajax: {
            url:  "{{ route('pfi-item.list') }}",
            data: function (d) {

                d.code = $('#code').val();  // Add custom parameters to send to the server
                d.status = $('#loi-status').val();
                d.pfi_date = $('#pfi-date').val();
                d.pfi_number = $('#pfi-number').val();
                d.supplier_id = $('#supplier-id').val();
                d.client_id = $('#customer-id').val();
                d.country_id = $('#country-id').val();
         
            }
        },
        columns: [
            {'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'loi_item_code', 'name' : 'letterOfIndentItem.code' , orderable: false},
            {'data' : 'loi_status', 'name' : 'letterOfIndentItem.LOI.status' , orderable: false},
            {'data' : 'pfi_date', 'name' : 'pfi.pfi_date', orderable: false},
            {'data' : 'pfi.pfi_reference_number', 'name' : 'pfi.pfi_reference_number', orderable: false},
            {'data' : 'pfi.customer.name', 'name' : 'pfi.customer.name', orderable: false,},
            {'data' : 'pfi.country.name', 'name' : 'pfi.country.name', orderable: false , },
            {'data' : 'pfi.supplier.supplier', 'name' : 'pfi.supplier.supplier', orderable: false },
            {'data' : 'pfi.currency', 'name' : 'pfi.currency', orderable: false },         
            {'data' : 'master_model.steering', 'name': 'masterModel.steering', orderable: false }, 
            {'data' : 'master_model.model_line.brand.brand_name', 'name': 'masterModel.modelLine.brand.brand_name', orderable: false },        
            {'data' : 'master_model.model_line.model_line', 'name': 'masterModel.modelLine.model_line', orderable: false },
            {'data' : 'master_model.model', 'name': 'masterModel.model', orderable: false },      
            {'data' : 'master_model.sfx', 'name': 'masterModel.sfx', orderable: false },              
            {'data' : 'pfi_quantity', 'name': 'pfi_quantity', orderable: false },   
            {'data' : 'unit_price', 'name': 'unit_price', orderable: false }, 
            {'data' : 'total_price', 'name': 'total_price', orderable: false },         
            {'data' : 'amount', 'name': 'pfi.amount', orderable: false },   
            {'data' : 'pfi.comment', 'name': 'pfi.comment', orderable: false },        
        ]
        });

        // $('#code, #status #pfi-date #customer-id #supplier-id #pfi-number #customer-id').on('keyup change', function() {
        //     table.draw(); // Redraw table with new filters
        // });
        
          
    });
        function reload() {
            var table = $('#PFI-Items-table').DataTable();
            table.draw(); 
        }  
        $('#supplier-id').select2({
            placeholder: "Filter By Vendor",
            maximumSelectionLength: 1
        });
        $('#customer-id').select2({
            placeholder: "Filter By Customer",
            maximumSelectionLength: 1
        });
        $('#country-id').select2({
            placeholder: "Filter By Country",
            maximumSelectionLength: 1
    });

 
    </script>
@endpush


















