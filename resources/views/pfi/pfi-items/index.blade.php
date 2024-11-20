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
                height:20px !important;
            }
          
            .small-width{
                min-width:250px !important;
            }
            /* element.style{
                width:250px !important;
            } */
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
                <a  class="btn btn-sm btn-info float-end" style="margin-right:5px;" title="PFI Basic Details List View"
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
            <div class="portfolio">
                <ul class="nav nav-pills nav-fill" id="my-tab">
                   <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="pill" href="#all-PFI">All PFI</a>
                    </li>
                   <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#toyota-PFI">Toyota PFI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#other-brand-PFI">Other Brand PFI</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="card-body">
                    <div class="tab-pane fade show active table-responsive" id="all-PFI" type="all" >
                        <table id="all-PFI-Items-table" class="table table-bordered table-striped table-editable table-edits table table-condensed" style="width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>PFI Item Code
                                        <input class="small-width" onkeyup="reload()" name="pfi-item-code" type="text" id="pfi-item-code-all" placeholder="PFI Item Code">
                                    </th> 
                                    <th>LOI Item Code
                                        <input class="small-width" onkeyup="reload()" name="code" type="text" id="code-all" placeholder="LOI Item Code">
                                    </th> 
                                    <th>PFI Date
                                        <input type="date" class="small-width" onchange="reload()" id="pfi-date-all" placeholder="PFI Date">
                                    </th>                                                                              
                                    <th>PFI Number  <input type="text" onkeyup="reload()" class="small-width" id="pfi-number-all" placeholder="PFI Number"></th>
                                    <th>
                                        Customer Name
                                        <select class="medium-width customer-id" id="customer-id-all" multiple onchange="reload()" >
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}"> {{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        Country 
                                        <select class="small-width country-id" id="country-id-all" multiple onchange="reload()">
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}"> {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>  
                                    <th>
                                        Vendor  
                                        <select  class="small-width supplier-id" id="supplier-id-all" multiple onchange="reload()">
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}"> {{ $supplier->supplier }}</option>
                                            @endforeach
                                        </select>
                                    </th>  
                                    <th>Currency 
                                        <select  class="small-width currency" id="currency-all" onchange="reload()" multiple>
                                        <option></option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </th> 
                                    <th>Steering 
                                        <select  class="small-width steering" id="steering-all" onchange="reload()" multiple>
                                        <option></option>
                                            <option value="LHD">LHD</option>
                                            <option value="RHD">RHD</option>
                                        </select>
                                    </th>                              
                                    <th>Brand
                                        <select class="small-width brand-id" id="brand-id-all" multiple onchange="reload()">
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->id}}"> {{ $brand->brand_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>Model Line
                                        <select class="small-width model-line-id" id="model-line-id-all" multiple onchange="reload()">
                                        @foreach($modelLines as $modelLine)
                                                <option value="{{$modelLine->id}}"> {{ $modelLine->model_line ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </th>                           
                                    <th>Model
                                        <input type="text" onkeyup="reload()" class="small-width" id="model-all" placeholder="Model">
                                    </th>
                                    <th>SFX
                                        <input type="text" onkeyup="reload()" class="small-width" id="sfx-all" placeholder="SFX">
                                    </th>
                                    <th>PFI Quantity
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-quantity-all" placeholder="PFI Quantity">
                                    </th>
                                    <th>Unit Price
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="unit-price-all" placeholder="Unit Price">
                                    </th>
                                    <th>Total Price
                                    <input type="number" min="0" onkeyup="reload()" class="small-width" id="total-price-all" placeholder="Total Price">
                                    </th>
                                    <th>PFI Amount 
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-amount-all" placeholder="PFI Amount">
                                    </th>
                                    <th>Comment
                                        <input type="text"  onkeyup="reload()" class="small-width" id="comment-all" placeholder="comment">
                                    </th>                      
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> 
                    </div>    
                    <div class="tab-pane fade table-responsive" id="toyota-PFI" type="toyota">
                        <table id="toyota-PFI-Items-table" class="table table-bordered table-striped table-editable table-edits table table-condensed" style="width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>PFI Item Code
                                        <input class="small-width" onkeyup="reload()" name="pfi-item-code" type="text" id="pfi-item-code-toyota" placeholder="PFI Item Code">
                                    </th> 
                                    <th>LOI Item Code
                                        <input class="small-width" onkeyup="reload()" name="code" type="text" id="code-toyota" placeholder="LOI Item Code">
                                    </th> 
                                    <th>PFI Date
                                        <input type="date" class="small-width" onchange="reload()" id="pfi-date-toyota" placeholder="PFI Date">
                                    </th>                                                                              
                                    <th>PFI Number  <input type="text" onkeyup="reload()" class="small-width" id="pfi-number-toyota" placeholder="PFI Number"></th>
                                    <th>
                                        Customer Name
                                        <select class="medium-width customer-id" id="customer-id-toyota" multiple onchange="reload()" >
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}"> {{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        Country 
                                        <select class="small-width country-id" id="country-id-toyota" multiple onchange="reload()">
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}"> {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>  
                                    <th>
                                        Vendor  
                                        <select  class="small-width supplier-id" id="supplier-id-toyota" multiple onchange="reload()">
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}"> {{ $supplier->supplier }}</option>
                                            @endforeach
                                        </select>
                                    </th>  
                                    <th>Currency 
                                        <select  class="small-width currency" id="currency-toyota" onchange="reload()" multiple>
                                        <option></option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </th> 
                                    <th>Steering 
                                        <select  class="small-width steering" id="steering-toyota" onchange="reload()" multiple>
                                        <option></option>
                                            <option value="LHD">LHD</option>
                                            <option value="RHD">RHD</option>
                                        </select>
                                    </th>                              
                                    <th>Brand
                                        <select class="small-width brand-id" id="brand-id-toyota" multiple onchange="reload()">
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->id}}"> {{ $brand->brand_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>Model Line
                                        <select class="small-width model-line-id" id="model-line-id-toyota" multiple onchange="reload()">
                                        @foreach($modelLines as $modelLine)
                                                <option value="{{$modelLine->id}}"> {{ $modelLine->model_line ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </th>                           
                                    <th>Model
                                        <input type="text" onkeyup="reload()" class="small-width" id="model-toyota" placeholder="Model">
                                    </th>
                                    <th>SFX
                                        <input type="text" onkeyup="reload()" class="small-width" id="sfx-toyota" placeholder="SFX">
                                    </th>
                                    <th>PFI Quantity
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-quantity-toyota" placeholder="PFI Quantity">
                                    </th>
                                    <th>Unit Price
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="unit-price-toyota" placeholder="Unit Price">
                                    </th>
                                    <th>Total Price
                                    <input type="number" min="0" onkeyup="reload()" class="small-width" id="total-price-toyota" placeholder="Total Price">
                                    </th>
                                    <th>PFI Amount 
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-amount-toyota" placeholder="PFI Amount">
                                    </th>
                                    <th>Comment
                                        <input type="text"  onkeyup="reload()" class="small-width" id="comment-toyota" placeholder="comment">
                                    </th>                      
                                </tr>
                            
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>  
                    <div class="tab-pane fade table-responsive" id="other-brand-PFI" type="other-brand">
                        <table id="other-brand-PFI-Items-table" class="table table-bordered table-striped table-editable table-edits table table-condensed" style="width:100%;">
                            <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>PFI Item Code
                                        <input class="small-width" onkeyup="reload()" name="pfi-item-code" type="text" id="pfi-item-code-other-brand" placeholder="PFI Item Code">
                                    </th> 
                                  
                                    <th>PFI Date
                                        <input type="date" class="small-width" onchange="reload()" id="pfi-date-other-brand" placeholder="PFI Date">
                                    </th>                                                                              
                                    <th>PFI Number  <input type="text" onkeyup="reload()" class="small-width" id="pfi-number-other-brand" placeholder="PFI Number"></th>
                                    <th>
                                        Customer Name
                                        <select class="medium-width customer-id" id="customer-id-other-brand" multiple onchange="reload()" >
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}"> {{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        Country 
                                        <select class="small-width country-id" id="country-id-other-brand" multiple onchange="reload()">
                                            @foreach($countries as $country)
                                                <option value="{{$country->id}}"> {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </th>  
                                    <th>
                                        Vendor  
                                        <select  class="small-width supplier-id" id="supplier-id-other-brand" multiple onchange="reload()">
                                            @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}"> {{ $supplier->supplier }}</option>
                                            @endforeach
                                        </select>
                                    </th>  
                                    <th>Currency 
                                        <select  class="small-width currency" id="currency-other-brand" onchange="reload()" multiple>
                                        <option></option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </th> 
                                    <th>Steering 
                                        <select  class="small-width steering" id="steering-other-brand" onchange="reload()" multiple>
                                        <option></option>
                                            <option value="LHD">LHD</option>
                                            <option value="RHD">RHD</option>
                                        </select>
                                    </th>                              
                                    <th>Brand
                                        <select class="small-width brand-id" id="brand-id-other-brand" multiple onchange="reload()">
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->id}}"> {{ $brand->brand_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>Model Line
                                        <select class="small-width model-line-id" id="model-line-id-other-brand" multiple onchange="reload()">
                                        @foreach($modelLines as $modelLine)
                                                <option value="{{$modelLine->id}}"> {{ $modelLine->model_line ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </th>                           
                                    <th>Model
                                        <input type="text" onkeyup="reload()" class="small-width" id="model-other-brand" placeholder="Model">
                                    </th>
                                    <th>SFX
                                        <input type="text" onkeyup="reload()" class="small-width" id="sfx-other-brand" placeholder="SFX">
                                    </th>
                                    <th>PFI Quantity
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-quantity-other-brand" placeholder="PFI Quantity">
                                    </th>
                                    <th>Unit Price
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="unit-price-other-brand" placeholder="Unit Price">
                                    </th>
                                    <th>Total Price
                                    <input type="number" min="0" onkeyup="reload()" class="small-width" id="total-price-other-brand" placeholder="Total Price">
                                    </th>
                                    <th>PFI Amount 
                                        <input type="number" min="0" onkeyup="reload()" class="small-width" id="pfi-amount-other-brand" placeholder="PFI Amount">
                                    </th>
                                    <th>Comment
                                        <input type="text"  onkeyup="reload()" class="small-width" id="comment-other-brand" placeholder="comment">
                                    </th>                      
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
        $('.supplier-id').select2({
            placeholder: "Vendor",
            maximumSelectionLength: 1
        });
     
        $('.customer-id').select2({
            placeholder: "Customer",
            maximumSelectionLength: 1
        });
        $('.currency').select2({
            placeholder:"Currency",
            maximumSelectionLength: 1
        });
        $('.steering').select2({
            placeholder: "Steering",
            maximumSelectionLength: 1
        });
        $('.country-id').select2({
            placeholder: "Country",
            maximumSelectionLength: 1
        });
        $('.brand-id').select2({
            placeholder: "Brand",
            maximumSelectionLength: 1
        });
        $('.model-line-id').select2({
            placeholder: "Model Line ",
            maximumSelectionLength: 1
        });

        $(document).ready(function () {
            var table1 = $('#all-PFI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:false,
            ajax: {
            url:  "{{ route('pfi-item.list',['tab' => 'all']) }}",
            data: function (d) {
                d.pfi_item_code = $('#pfi-item-code-all').val(); 
                d.code = $('#code-all').val();  // Add custom parameters to send to the server
                // d.status = $('#loi-status').val();
                d.pfi_date = $('#pfi-date-all').val();
                d.pfi_number = $('#pfi-number-all').val();
                d.supplier_id = $('#supplier-id-all').val();
                d.client_id = $('#customer-id-all').val();
                d.country_id = $('#country-id-all').val();
                d.currency = $('#currency-all').val();
                d.steering = $('#steering-all').val();
                d.brand = $('#brand-id-all').val();
                d.model_line = $('#model-line-id-all').val();
                d.model = $('#model-all').val();
                d.sfx = $('#sfx-all').val();
                d.pfi_quantity = $('#pfi-quantity-all').val();
                d.total_price = $('#total-price-all').val();
                d.unit_price = $('#unit-price-all').val();
                d.pfi_amount = $('#pfi-amount-all').val();
                d.comment = $('#comment-all').val();
            
            }
        },
        columns: [
            {'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'code', 'name' : 'code' , orderable: true},
            {'data' : 'loi_item_code', 'name' : 'loi_item_code' , orderable: true},
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
        var table2 = $('#toyota-PFI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:false,
            ajax: {
            url:  "{{ route('pfi-item.list', ['tab' => 'TOYOTA']) }}",
            data: function (d) {
                d.pfi_item_code = $('#pfi-item-code-toyota').val(); 
                d.code = $('#code-toyota').val();  // Add custom parameters to send to the server
                // d.status = $('#loi-status').val();
                d.pfi_date = $('#pfi-date-toyota').val();
                d.pfi_number = $('#pfi-number-toyota').val();
                d.supplier_id = $('#supplier-id-toyota').val();
                d.client_id = $('#customer-id-toyota').val();
                d.country_id = $('#country-id-toyota').val();
                d.currency = $('#currency-toyota').val();
                d.steering = $('#steering-toyota').val();
                d.brand = $('#brand-id-toyota').val();
                d.model_line = $('#model-line-id-toyota').val();
                d.model = $('#model-toyota').val();
                d.sfx = $('#sfx-toyota').val();
                d.pfi_quantity = $('#pfi-quantity-toyota').val();
                d.total_price = $('#total-price-toyota').val();
                d.unit_price = $('#unit-price-toyota').val();
                d.pfi_amount = $('#pfi-amount-toyota').val();
                d.comment = $('#comment-toyota').val();
            
            }
        },
        columns: [
            {'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'code', 'name' : 'code' , orderable: true},
            {'data' : 'loi_item_code', 'name' : 'loi_item_code' , orderable: true},
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
        var table3 = $('#other-brand-PFI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching:false,
            ajax: {
            url:  "{{ route('pfi-item.list', ['tab' => 'OTHER-BRANDS']) }}",
            data: function (d) {
                let activeTabType = $('.tab-pane.active').attr('type');
                d.pfi_item_code = $('#pfi-item-code-'+activeTabType).val(); 
                d.code = $('#code-'+activeTabType).val();  
                d.pfi_date = $('#pfi-date-'+activeTabType).val();
                d.pfi_number = $('#pfi-number-'+activeTabType).val();
                d.supplier_id = $('#supplier-id-'+activeTabType).val();
                d.client_id = $('#customer-id-'+activeTabType).val();
                d.country_id = $('#country-id-'+activeTabType).val();
                d.currency = $('#currency-'+activeTabType).val();
                d.steering = $('#steering-'+activeTabType).val();
                d.brand = $('#brand-id-'+activeTabType).val();
                d.model_line = $('#model-line-id-'+activeTabType).val();
                d.model = $('#model-'+activeTabType).val();
                d.sfx = $('#sfx-'+activeTabType).val();
                d.pfi_quantity = $('#pfi-quantity-'+activeTabType).val();
                d.total_price = $('#total-price-'+activeTabType).val();
                d.unit_price = $('#unit-price-'+activeTabType).val();
                d.pfi_amount = $('#pfi-amount-'+activeTabType).val();
                d.comment = $('#comment-'+activeTabType).val();
            
            }
        },
        columns: [
            {'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false },
            {'data' : 'code', 'name' : 'code' , orderable: true},
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
            let activeTab = $('.tab-pane.active').attr('type');
            if(activeTab == 'all') {
                var table1 = $('#all-PFI-Items-table').DataTable();
                table1.draw();
            }else if(activeTab == 'toyota') {
                var table2 = $('#toyota-PFI-Items-table').DataTable();
                table2.draw();
            }else{
                var table3 = $('#other-brand-PFI-Items-table').DataTable();
                table3.draw();
            }
        }  

        function exportData() {
                let activeTabType = $('.tab-pane.active').attr('type');

                let code = $('#code-'+activeTabType).val(); 
                let pfi_date = $('#pfi-date-'+activeTabType).val();
                let pfi_item_code = $('#pfi-item-code-'+activeTabType).val();
                let pfi_number = $('#pfi-number-'+activeTabType).val();
                let supplier_id = $('#supplier-id-'+activeTabType).val();
                let client_id = $('#customer-id-'+activeTabType).val();
                let country_id = $('#country-id-'+activeTabType).val();
                let currency = $('#currency-'+activeTabType).val();
                let steering = $('#steering-'+activeTabType).val();
                let brand = $('#brand-id-'+activeTabType).val();
                let model_line = $('#model-line-id-'+activeTabType).val();
                let model = $('#model-'+activeTabType).val();
                let sfx = $('#sfx-'+activeTabType).val();
                let pfi_quantity = $('#pfi-quantity-'+activeTabType).val();
                let unit_price = $('#unit-price-'+activeTabType).val();
                let pfi_amount = $('#pfi-amount-'+activeTabType).val();
                let total_price = $('#total-price-'+activeTabType).val();
                let comment = $('#comment-'+activeTabType).val();

            var exportUrl = "{{ route('pfi-item.list')}}"+ "?code="+code+"&pfi_date="+pfi_date+"&pfi_item_code="+pfi_item_code+
            "&pfi_number="+pfi_number+"&supplier_id="+supplier_id+"&country_id="+country_id+"&currency="+currency+"&steering="+steering+
            "&brand="+brand+"&client_id="+ client_id+"&model_line="+model_line+"&model="+model+"&sfx="+sfx+"&unit_price="+unit_price+
            "&pfi_amount="+pfi_amount+"&total_price="+total_price+"&comment="+comment+"&pfi_quantity="+pfi_quantity+"&export=EXCEL";
            

            window.location.href = exportUrl;
        }
        
 
    </script>
@endpush


















