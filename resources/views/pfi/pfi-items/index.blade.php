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
                        <button class="btn btn-sm btn-primary float-end" type="button" onclick="exportData()" >
                            <i class="fa fa-download" aria-hidden="true"></i> Export</button>
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
                                    <input class="small-width" onkeyup="reload()" name="code" type="text" id="code" placeholder="LOI Item Code">
                                </th> 
                                <!-- <th>LOI Status
                                    <input type="text" id="loi-status" onkeyup="reload()" placeholder="LOI Status">
                                </th>  -->
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
                                    <select  class="small-width" id="currency" onchange="reload()" multiple>
                                       <option></option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </th> 
                                <th>Steering 
                                    <select  class="small-width" id="steering" onchange="reload()" multiple>
                                    <option></option>
                                        <option value="LHD">LHD</option>
                                        <option value="RHD">RHD</option>
                                    </select>
                                </th>                              
                                <th>Brand
                                    <select class="small-width" id="brand-id" multiple onchange="reload()">
                                        @foreach($brands as $brand)
                                            <option value="{{$brand->id}}"> {{ $brand->brand_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>Model Line
                                    <select class="small-width" id="model-line-id" multiple onchange="reload()">
                                    @foreach($modelLines as $modelLine)
                                            <option value="{{$modelLine->id}}"> {{ $modelLine->model_line ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </th>                           
                                <th>Model
                                    <input type="text" onkeyup="reload()" class="small-width" id="model" placeholder="Model">
                                </th>
                                <th>SFX
                                    <input type="text" onkeyup="reload()" class="small-width" id="sfx" placeholder="SFX">
                                </th>
                                <th>PFI Quantity
                                    <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-quantity" placeholder="PFI Quantity">
                                </th>
                                <th>Unit Price
                                    <input type="number" min="0" onkeyup="reload()" class="small-width" id="unit-price" placeholder="Unit Price">
                                 </th>
                                <th>Total Price
                                <input type="number" min="0" onkeyup="reload()" class="small-width" id="total-price" placeholder="Total Price">
                                </th>
                                <th>PFI Amount 
                                    <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-amount" placeholder="PFI Amount">
                                </th>
                                <th>Comment
                                    <input type="text"  onkeyup="reload()" class="small-width" id="comment" placeholder="comment">
                                 </th>                      
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
            var table = $('#PFI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:false,
            ajax: {
            url:  "{{ route('pfi-item.list') }}",
            data: function (d) {

                d.code = $('#code').val();  // Add custom parameters to send to the server
                // d.status = $('#loi-status').val();
                d.pfi_date = $('#pfi-date').val();
                d.pfi_number = $('#pfi-number').val();
                d.supplier_id = $('#supplier-id').val();
                d.client_id = $('#customer-id').val();
                d.country_id = $('#country-id').val();
                d.currency = $('#currency').val();
                d.steering = $('#steering').val();
                d.brand = $('#brand-id').val();
                d.model_line = $('#model-line-id').val();
                d.model = $('#model').val();
                d.sfx = $('#sfx').val();
                d.pfi_quantity = $('#pfi-quantity').val();
                d.total_price = $('#total-price').val();
                d.unit_price = $('#unit-price').val();
                d.pfi_amount = $('#pfi-amount').val();
                d.comment = $('#comment').val();
            
            }
        },
        columns: [
            {'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'loi_item_code', 'name' : 'letterOfIndentItem.code' , orderable: false},
            // {'data' : 'loi_status', 'name' : 'letterOfIndentItem.LOI.status' , orderable: false},
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

       
    });
        function reload() {
            var table = $('#PFI-Items-table').DataTable();
            table.draw(); 
        }  

        function exportData() {
            // var table = $('#PFI-Items-table').DataTable();
            // table.draw(); 
                let code = $('#code').val(); 
                console.log(code); // Add custom parameters to send to the server
                // let status = $('#loi-status').val();
                let pfi_date = $('#pfi-date').val();
                let pfi_number = $('#pfi-number').val();
                let supplier_id = $('#supplier-id').val();
                let client_id = $('#customer-id').val();
                let country_id = $('#country-id').val();
                let currency = $('#currency').val();
                let steering = $('#steering').val();
                let brand = $('#brand-id').val();
                let model_line = $('#model-line-id').val();
                let model = $('#model').val();
                let sfx = $('#sfx').val();
                let pfi_quantity = $('#pfi-quantity').val();
                let unit_price = $('#unit-price').val();
                let pfi_amount = $('#pfi-amount').val();
                let total_price = $('#total-price').val();
                let comment = $('#comment').val();

            var exportUrl = "{{ route('pfi-item.list')}}"+ "?code="+code+"&pfi_date="+pfi_date+
            "&pfi_number="+pfi_number+"&supplier_id="+supplier_id+"&country_id="+country_id+"&currency="+currency+"&steering="+steering+
            "&brand="+brand+"&client_id="+ client_id+"&model_line="+model_line+"&model="+model+"&sfx="+sfx+"&unit_price="+unit_price+
            "&pfi_amount="+pfi_amount+"&total_price="+total_price+"&comment="+comment+"&pfi_quantity="+pfi_quantity+"&export=EXCEL";
            

            window.location.href = exportUrl;
        }
        $('#supplier-id').select2({
            placeholder: "Vendor",
            maximumSelectionLength: 1
        });
        $('#customer-id').select2({
            placeholder: "Customer",
            maximumSelectionLength: 1
        });
        $('#currency').select2({
            placeholder:"Currency",
            maximumSelectionLength: 1
        });
        $('#steering').select2({
            placeholder: "Steering",
            maximumSelectionLength: 1
        });
        $('#country-id').select2({
            placeholder: "Country",
            maximumSelectionLength: 1
        });
        $('#brand-id').select2({
            placeholder: "Brand",
            maximumSelectionLength: 1
        });
        $('#model-line-id').select2({
            placeholder: "Model Line ",
            maximumSelectionLength: 1
        });


 
    </script>
@endpush


















