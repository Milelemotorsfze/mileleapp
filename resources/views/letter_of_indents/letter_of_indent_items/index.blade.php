@extends('layouts.table')
@section('content')
    @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
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
            .widthinput{
                height:32px!important;
                width:200px !important;
            }
        </style>
            <div class="card-header">
                <h4 class="card-title">
                    LOI Lists
                </h4>
                @can('LOI-create')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-create');
                    @endphp
                    @if ($hasPermission)
                    <button class="btn btn-sm btn-primary float-end" type="button" onclick="exportData()" >
                            <i class="fa fa-download" aria-hidden="true"></i> Export</button>
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
                 <div class="row mb-3">
                    <div class="col-md-2 col-lg-2 col-sm-12" style="padding-right:0px">
                        <label class="form-label fw-bold">LOI From Date</label>
                        <input type="date" class="form-control widthinput" onchange="validate()" id="loi-from-date" placeholder="LOI Date From">
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-12" style="padding-left:0px">
                        <label class="form-label fw-bold">LOI To Date </label>
                        <input type="date" class="form-control widthinput" onchange="validate()"  id="loi-to-date" placeholder="LOI Date To">
                    </div>
                </div>
                    <table class="table table-bordered table-striped table-editable table-edits table table-condensed LOI-Items-table" style="width:100%;">
                        <thead class="bg-soft-secondary">
                            <tr>
                                <th>S.No</th>
                                <th>
                                    LOI Number
                                    <input class="small-width" onkeyup="reload()" type="text" id="uuid" placeholder="LOI Number">
                                </th>
                                <th>
                                    LOI Date
                                    <!-- <input type="date" class="small-width" onchange="reload()" id="LOI-date-from" placeholder="LOI Date From"> -->
                               
                                    <input type="date" class="small-width" onchange="reload()" id="LOI-date" placeholder="LOI Date To">
                                </th>
                                <th>
                                    LOI Approval Date
                                    <input type="date" class="small-width" onchange="reload()" id="loi_approval_date" placeholder="Approval Date">
                                </th>
                                <th>
                                    Dealer
                                    <select class="small-width" id="dealer" onchange="reload()" multiple>
                                       <option></option>
                                       <option value="Milele Motors">Milele Motors</option>
                                       <option value="Trans Cars">Trans Cars</option>  
                                    </select>
                                </th>
                                <th>
                                    Customer Name
                                    <select class="medium-width" id="customer-id" multiple onchange="reload()" >
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}"> {{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    Customer Type
                                    <select class="small-width" id="customer-type" onchange="reload()" multiple>
                                        <option></option>
                                        <option value={{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}>{{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                        <option value={{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}>{{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}</option>
                                        <option value={{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}>{{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}</option>
                                    </select>
                                </th>
                                <th>
                                    Category
                                    <select class="small-width" multiple id="category"  onchange="reload()">
                                        <option></option>
                                        <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_MANAGEMENT_REQUEST}}">
                                            {{\App\Models\LetterOfIndent::LOI_CATEGORY_MANAGEMENT_REQUEST}}
                                        </option>
                                        <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_END_USER_CHANGED}}">
                                            {{\App\Models\LetterOfIndent::LOI_CATEGORY_END_USER_CHANGED}}
                                        </option>
                                        <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}">
                                            {{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}
                                        </option>
                                        <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}">
                                            {{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}
                                        </option>
                                        <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_QUANTITY_INFLATE}}">
                                            {{ \App\Models\LetterOfIndent::LOI_CATEGORY_QUANTITY_INFLATE }}
                                        </option>
                                    </select>
                                </th>
                                <th>
                                    Country
                                    <select class="small-width" multiple id="country_id" onchange="reload()">
                                        <option ></option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"> {{ $country->name }} </option>
                                        @endforeach
                                    </select>
                                </th>                
                                <th>
                                    Item Code
                                    <input class="small-width" onkeyup="reload()" type="text" id="loi_item_code" placeholder="LOI Item Code">
                                </th>
                                <th>
                                    Model
                                    <input class="small-width" onkeyup="reload()" type="text" id="model" placeholder="Model">                        
                                </th>
                                <th>
                                    SFX
                                    <input class="small-width" onkeyup="reload()" type="text" id="sfx" placeholder="SFX">
                                </th>
                                <th>
                                    Steering
                                    <select class="small-width" id="steering" onchange="reload()" multiple>
                                       <option></option>
                                       <option value="LHD">LHD</option>
                                       <option value="RHD">RHD</option>  
                                    </select>
                                </th>
                                <th>
                                    Model Line
                                    <select class="small-width" multiple id="model_line" onchange="reload()">
                                        <option ></option>
                                        @foreach($modelLines as $modelLine)
                                            <option value="{{ $modelLine->id }}"> {{ $modelLine->model_line }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    PFI Number - (QTY)
                                    <input class="small-width" onkeyup="reload()" type="text" id="pfi_number" placeholder="PFI Number">
                                </th>
                                <th>
                                    Quantity
                                    <input class="small-width" onkeyup="reload()" type="number" id="quantity" placeholder="Quantity">
                                </th>
                                <th>
                                    Utilized Quantity
                                    <input class="small-width" onkeyup="reload()" type="number" id="utilized_quantity" placeholder="Utilized Quantity">
                                </th>
                                <th>
                                    Remaining Quantity
                                    <input class="small-width" onkeyup="reload()" type="number" id="remaining_quantity" placeholder="Unused Quantity">
                                </th>
                                <th>
                                    Sales Person
                                    <select class="small-width" multiple id="sales_person" onchange="reload()">
                                        <option ></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"> {{ $user->name }} </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    Is Expired
                                    <select class="small-width" id="is-expired" onchange="reload()" multiple>
                                       <option></option>
                                       <option value="1">Expired</option>
                                       <option value="0">Not Expired</option> 
                                    </select>
                                </th>  
                                <th>Status
                                    <select class="small-width" id="status" onchange="reload()" multiple>
                                       <option></option>
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_NEW }}">New</option>
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_WAITING_FOR_TTC_APPROVAL }}">Waiting For TTC Approval</option>  
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL }}">Waiting For Approval</option>  
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED }}">Approved By Supplier</option>
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_TTC_APPROVED }}">TTC Approved</option>    
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED }}">Rejected By Supplier</option> 
                                       <option value="{{ \App\Models\LetterOfIndent::LOI_STATUS_TTC_REJECTED }}">TTC Rejected</option>   
                                    </select>
                                </th>   
                                <th>
                                    SO Numbers
                                    <input class="small-width" onkeyup="reload()" type="text" id="so_number" placeholder="SO Number">
                                </th>  
                                <th>
                                    Approval Remarks
                                    <input class="small-width" onkeyup="reload()" type="text" id="review" placeholder="Remarks">
                                </th>
                                <th>
                                    LOI Comment
                                    <input class="small-width" onkeyup="reload()" type="text" id="comments" placeholder="Comment">
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

            var table1 = $('.LOI-Items-table').DataTable({      
            processing: true,
            serverSide: true,
            searching: false,
            ajax:{
                url:  "{{ route('letter-of-indent-items.index') }}",
                data: function (d) {

                d.uuid = $('#uuid').val(); 
                d.loi_date = $('#LOI-date').val();
                d.loi_approval_date = $('#loi_approval_date').val();
                d.dealer = $('#dealer').val();
                d.client_id = $('#customer-id').val();
                d.customer_type = $('#customer-type').val();
                d.country_id = $('#country_id').val();
                d.category = $('#category').val();
                d.loi_item_code = $('#loi_item_code').val();
                d.model = $('#model').val();
                d.sfx = $('#sfx').val();
                d.steering = $('#steering').val();
                d.model_line = $('#model_line').val();
                d.quantity = $('#quantity').val();
                d.utilized_quantity = $('#utilized_quantity').val();
                d.remaining_quantity = $('#remaining_quantity').val();
                d.sales_person = $('#sales_person').val();
                d.is_expired = $('#is-expired').val();
                d.status = $('#status').val();
                d.so_number = $('#so_number').val();
                d.review = $('#review').val();
                d.comments = $('#comments').val();
                d.pfi_number = $('#pfi_number').val();
                d.loi_from_date = $('#loi-from-date').val();
                d.loi_to_date = $('#loi-to-date').val();
                
                }
            },
        columns: [
            {'data': 'DT_RowIndex', 'name': 'DT_RowIndex', orderable: false, searchable: false},
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
        function reload() {
            var table1 = $('.LOI-Items-table').DataTable();
            table1.draw(); 
        }  
        function exportData() {
            let uuid = $('#uuid').val(); 
            let loi_date = $('#LOI-date').val();
            let loi_from_date = $('#loi-from-date').val();
            let loi_to_date = $('#loi-to-date').val();
            let loi_approval_date = $('#loi_approval_date').val();
            let dealer = $('#dealer').val();
            let client_id = $('#customer-id').val();
            let customer_type = $('#customer-type').val();
            let country_id = $('#country_id').val();   
            let category = $('#category').val();
            let loi_item_code = $('#loi_item_code').val();
            let model = $('#model').val();
            let sfx = $('#sfx').val();
            let steering = $('#steering').val();
            let model_line = $('#model_line').val();
            let quantity = $('#quantity').val();
            let utilized_quantity = $('#utilized_quantity').val();
            let remaining_quantity = $('#remaining_quantity').val();
            let sales_person = $('#sales_person').val();
            let is_expired = $('#is-expired').val();
            let status = $('#status').val();
            let so_number = $('#so_number').val();
            let review = $('#review').val();
            let comments = $('#comments').val();
            let pfi_number = $('#pfi_number').val();

            var exportUrl = "{{ route('letter-of-indent-items.index')}}"+"?uuid="+uuid+"&loi_date="+loi_date+"&loi_from_date="+loi_from_date+
                    "&loi_to_date="+loi_to_date+"&loi_approval_date="+loi_approval_date+"&dealer="+dealer+"&client_id="+client_id+
                    "&customer_type="+customer_type+"&country_id="+country_id+"&category="+category+
                    "&loi_item_code="+loi_item_code+"&model="+model+ "&sfx="+sfx+"&steering="+steering+"&model_line="+model_line+
                    "&quantity="+quantity+"&utilized_quantity="+utilized_quantity+"&remaining_quantity="+remaining_quantity+
                    "&sales_person="+sales_person+"&is_expired="+is_expired+"&status="+status+"&so_number="+so_number+
                    "&review="+review+"&pfi_number="+pfi_number+"&comments="+comments+"&export=EXCEL";
           
            window.location.href = exportUrl;
        }
        
        $('#dealer').select2({
            placeholder: "Dealer",
            maximumSelectionLength: 1
        });
        $('#dealer').select2({
            placeholder: "Dealer",
            maximumSelectionLength: 1
        });
        $('#customer-id').select2({
            placeholder: "Customer",
            maximumSelectionLength: 1
        });
        $('#customer-type').select2({
            placeholder: "Customer Type",
            maximumSelectionLength: 1
        });
        $('#country_id').select2({
            placeholder: "Country",
            maximumSelectionLength: 1
        });
        $('#category').select2({
            placeholder: "Category",
            maximumSelectionLength: 1
        });
        $('#steering').select2({
            placeholder: "Steering",
            maximumSelectionLength: 1
        });
        $('#model_line').select2({
            placeholder: "Model Line",
            maximumSelectionLength: 1
        });
        $('#sales_person').select2({
            placeholder: "Sales Person",
            maximumSelectionLength: 1
        });
        $('#is-expired').select2({
            placeholder: "Is Expired",
            maximumSelectionLength: 1
        });
        $('#status').select2({
            placeholder: "Status",
            // maximumSelectionLength: 1
        });
       function validate() {
          
        // document.getElementById("loi-date-from").addEventListener("change", function(event) {
      // Get the "From" and "To" date values
        const fromDate = new Date(document.getElementById("loi-from-date").value);
        const toDate = new Date(document.getElementById("loi-to-date").value);

        // Check if "To Date" is greater than "From Date"
        if (toDate <= fromDate) {
            event.preventDefault(); // Prevent form submission
            alert("The 'To Date' must be later than the 'From Date'.");
        }else{
            reload();
        }
        // });

       }
    </script>
@endpush


















