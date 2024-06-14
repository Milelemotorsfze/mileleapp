@extends('layouts.main')
@section('content')
    <style>
        iframe {
            min-height: 300px;
            max-height: 500px;
        }
        .bg-light-pink{
            background-color: #ece6e6;
        }
        .widthinput
        {
            height:32px!important;
        }
        .error {
            color: #fd625e;
        }

    </style>
  @can('LOI-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Add New LOI</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

            </div>
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
                <form id="form-create" action="{{ route('letter-of-indents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-control widthinput" multiple name="country" id="country" autofocus>
                                    <option ></option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}"> {{ $country->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label  text-muted">Customer Type</label>
                                <select class="form-control widthinput" multiple name="customer_type" id="customer-type">
{{--                                    <option value="" disabled>Type</option>--}}
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}>{{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}>{{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}>{{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}</option>
                                    <option value={{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}>{{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}</option>
                                </select>
                                <span id="customer-type-error" class="error"></span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label ">Customer</label>
                                <select class="form-control widthinput @error('customer_id') is-invalid @enderror"
                                        name="customer_id" id="customer" multiple>
                                </select>
                                @error('customer_id')
                                <span role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">LOI Date</label>
                                <input type="date" class="form-control widthinput" id="date" max="{{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}"  name="date">
                                @error('date')
                                <span role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">LOI Category</label>
                                <select class="form-control widthinput" name="category" id="choices-single-default">
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
                                @error('category')
                                <span role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Dealer</label>
                                <select class="form-control widthinput" name="dealers" id="dealer">
                                    <option value="Trans Cars">Trans Cars</option>
                                    <option value="Milele Motors">Milele Motors</option>
                                </select>
                                <input type="hidden" name="dealers" value="Trans Cars" id="dealer-input">
                                @error('dealers')
                                <span role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Sales Person</label>
                                <select class="form-control widthinput" multiple name="sales_person_id" id="sales_person_id" autofocus>
                                    <option ></option>
                                    @foreach($salesPersons as $salesPerson)
                                        <option value="{{ $salesPerson->id }}"> {{ $salesPerson->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Template Type </label>
                                <select class="form-control widthinput" multiple name="template_type[]" id="template-type">
                                    <option value="trans_cars">Trans Cars</option>
                                    <option value="milele_cars" disabled>Milele Cars</option>
                                    <option value="individual">Individual</option>
                                    <option value="business">Business</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Customer Document</label>
                                <input type="file" name="files[]" id="file-upload" class="form-control widthinput text-dark" multiple
                                    autofocus>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Signature </label>
                                <input type="file" id="signature-upload" name="loi_signature" accept="image/*" class="form-control widthinput">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger m-2" role="alert" hidden id="country-comment-div">
                        <span id="country-comment"></span><br>
                        <span class="error" id="max-individual-quantity-error"></span>
                        <span class="error" id="min-company-quantity-error"></span>
                        <span class="error" id="max-company-quantity-error"></span>
                        <span class="error" id="company-only-allowed-error"></span>
                    </div>
                    <div class="card" id="soNumberDiv" >
                        <div class="card-header">
                            <h4 class="card-title">
                                 SO Numbers
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row soNumberMain">
                                <div class="col-xxl-4 col-lg-6 col-md-12 soNumberApendHere" id="row-1">
                                    <div class="row mt-2">
                                        <div class="col-xxl-9 col-lg-6 col-md-12">
                                            <input id="so_number_1" type="text" class="form-control widthinput so_number"
                                                oninput=uniqueCheckSoNumber()  name="so_number[1]"
                                                placeholder="SO Number" value="{{ old('so_number') }}" >
                                            <span id="soNumberError_1" class="error is-invalid soNumberError"></span>
                                        </div>

                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <a class="btn btn-sm btn-danger removeSoNumber" data-index="1" >  <i class="fas fa-trash-alt"></i> </a>
                                        </div>
                                   </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-md-12" id="soNumberDivBr" >
                                    <a id="addSoNumberBtn" style="float: right;" class="btn btn-sm btn-info">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add SO Numbers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div id="file-preview">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div id="signature-preview">
                            </div>
                        </div>
                    </div>

                    <div class="alert m-2" role="alert" hidden id="country-comment-div">
                        <span id="country-comment"></span>
                    </div>
                    <div class="row">
                        <div class="card p-2" >
                            <div class="card-header">
                                <h4 class="card-title">LOI Items</h4>
                            </div>
                            <div class="card-body">
                                <div id="loi-items" >
                                    <div class="row Loi-items-row-div" id="row-1">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <label class="form-label">Model</label>
                                            <select class="form-select widthinput text-dark models" multiple data-index="1" name="models[]" id="model-1" autofocus>
                                                <option value="" >Select Model</option>
                                                @foreach($models as $model)
                                                    <option value="{{ $model->model }}">{{ $model->model }}</option>
                                                @endforeach
                                            </select>
                                            @error('model')
                                            <span>
                                        <strong >{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">SFX</label>
                                            <select class="form-select widthinput text-dark sfx" multiple  data-index="1" name="sfx[]" id="sfx-1" >
                                                <option value="">Select SFX</option>
                                            </select>
                                            @error('sfx')
                                            <div role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">Model Year</label>
                                            <select class="form-select widthinput text-dark model-years" multiple  data-index="1" name="model_year[]" id="model-year-1">
                                                <option value="">Select Model Year</option>
                                            </select>
                                            @error('model_year')
                                            <div role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                            <label class="form-label">LOI Description</label>
                                            <input type="text" readonly placeholder="LOI Description"
                                                class="form-control widthinput text-dark loi-descriptions"  data-index="1" id="loi-description-1">
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="quantity[]" placeholder="Quantity"  maxlength="5" data-index="1" class="form-control widthinput quantities text-dark"
                                                step="1" oninput="validity.valid||(value='');" min="1" id="quantity-1">
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <label class="form-label">Inventory Qty</label>
                                            <input type="number" readonly id="inventory-quantity-1" value="" data-index="1" class="form-control widthinput inventory-qty" >
                                            <input type="hidden" name="master_model_ids[]" class="master-model-ids" id="master-model-id-1">
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <a class="btn btn-sm btn-danger removeButton" id="remove-btn-1" data-index="1" style="margin-top: 30px;" >  <i class="fas fa-trash-alt"></i> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn btn-info btn-sm add-row-btn float-end" >
                                            <i class="fas fa-plus"></i> Add LOI Item
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-primary" id="submit-button">Submit </button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')

    <script type="text/javascript">
        let formValid = true;
        let previousSelected = $('#customer-type').val();
        const fileInputLicense = document.querySelector("#file-upload");
        const previewFile = document.querySelector("#file-preview");
      
        fileInputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile.firstChild) {
                previewFile.removeChild(previewFile.firstChild);
            }
            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
                if (file.type.match("application/pdf"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFile.appendChild(iframe);
                }else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFile.appendChild(image);
                }
            }
        });

        const signatureFileInput = document.querySelector("#signature-upload");
        const signaturePreviewFile = document.querySelector("#signature-preview");

        signatureFileInput.addEventListener("change", function(event) {
            const files = event.target.files;
            while (signaturePreviewFile.firstChild) {
                signaturePreviewFile.removeChild(signaturePreviewFile.firstChild);
            }

            const file = files[0];

            const objectUrl = URL.createObjectURL(file);
            const iframe = document.createElement("iframe");
            iframe.src = objectUrl;
            signaturePreviewFile.appendChild(iframe);

        });

        $("#form-create").validate({
            ignore: [],
            rules: {
                country: {
                    required: true,
                },
                customer_id: {
                    required: true,
                },
                category: {
                    required: true,
                },
                customer_type: {
                    required: true,
                },
                date: {
                    required: true,
                },
                dealers:{
                    required:true
                },
                "models[]": {
                    required: true
                },
                "sfx[]": {
                    required: true
                },
                "model_year[]": {
                    required: true
                },
                "quantity[]": {
                    required: true
                },
                "files[]": {
                    required:true,
                    extension: "pdf|png|jpeg|jpg"
                },
                "template_type[]":{
                    required:true
                },
                loi_signature: {
                    required:function(element) {
                        return $("#dealer").val() == 'Milele Motors'
                    },
                    extension: "png|jpeg|jpg|svg"
                },
                messages: {
                    file: {
                        extension: "Please upload file format (Pdf,png,jpeg,jpg)"
                    },
                    loi_signature:{
                        extension: "Please upload Image file format (png,jpeg,jpg,svg)"
                    }
                },
            }
        });

        $.validator.prototype.checkForm = function (){
            this.prepareForm();
            for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
                if (this.findByName( elements[i].name ).length != undefined && this.findByName( elements[i].name ).length > 1) {
                    for (var cnt = 0; cnt < this.findByName( elements[i].name ).length; cnt++) {
                        this.check( this.findByName( elements[i].name )[cnt] );
                    }
                }
                else {
                    this.check( elements[i] );
                }
            }
            return this.valid();
        };
       
        
        $('#submit-button').click(function (e) {
            e.preventDefault();
            uniqueCheckSoNumber();
            if (formValid == true) {
                if($("#form-create").valid()) {
                    $('#form-create').unbind('submit').submit();
                }
            }else{
                e.preventDefault();
            }
        });

        $('#template-type').select2({
            placeholder : 'Select Template Type',
            allowClear: true,
        }).on('change', function() {
            $('#template-type-error').remove();
        });
        $('#sales_person_id').select2({
            placeholder : 'Select Sales Person',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#customer').select2({
            placeholder : 'Select Customer',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            $('#customer-error').remove();
            checkCountryCriterias();
        });
        $('#customer-type').select2({
            placeholder : 'Select Customer Type',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            $('#customer-type-error').remove();
        });
       
        $('#country').select2({
            placeholder : 'Select Country',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            getCustomers();
            checkCountryCriterias();
            $('#country-error').remove();
        });

        $('#date').change(function (){
            checkCountryCriterias();
        });
        $('#customer-type').change(function (){
            getCustomers();
           
            let customerType = $('#customer-type').val();
            $('#template-type').val('').trigger('change');
            if(customerType == '{{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}') {
                $('#template-type option[value=business]').prop('disabled',true);
            }else if(customerType == '{{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}') {
                $('#template-type option[value=individual]').prop('disabled',true);
            }else{
                $('#template-type option[value=individual]').prop('disabled',false);
                $('#template-type option[value=business]').prop('disabled',false);
            }
            checkCountryCriterias();
        });
        $('#model-1').select2({
            placeholder: 'Select Model',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            $(this).valid();
        });
        $('#sfx-1').select2({
            placeholder : 'Select SFX',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            $(this).valid();
        });
        $('#model-year-1').select2({
            placeholder : 'Select Model Year',
            allowClear: true,
            maximumSelectionLength: 1
        }).on('change', function() {
            $(this).valid();
        });

        $('#dealer').change(function () {
            var value = $('#dealer').val();
            $('#dealer-input').val(value);
            getModels('all','dealer-change');
            $('#template-type').val('').trigger('change');
            if(value == 'Trans Cars') {
                $('#template-type option[value=milele_cars]').prop('disabled',true);
                $('#template-type option[value=trans_cars]').prop('disabled',false);

            }else if(value == 'Milele Motors') {
                $('#template-type option[value=trans_cars]').prop('disabled',true);
                $('#template-type option[value=milele_cars]').prop('disabled',false);
            }
        });

        $(document.body).on('input', ".quantities", function (e) {
            checkCountryCriterias();
        });

        function checkCountryCriterias() {
            let url = '{{ route('loi-country-criteria.check') }}';
            var customer = $('#customer').val();
            var date = $('#date').val();
            var customer_type = $('#customer-type').val();
            let total_quantities = 0;
            $(".quantities ").each(function(){
                if($(this).val() > 0) {
                    total_quantities += parseInt($(this).val());
                }
            });
            if(customer.length > 0 && customer_type.length > 0 && total_quantities > 0 && date.length > 0) {
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        loi_date:date,
                        customer_id: customer[0],
                        customer_type: customer_type[0],
                        total_quantities:total_quantities
                    },
                    success:function (data) {
                        console.log(data);
                        if(data.comment) {
                            $('#country-comment-div').attr('hidden', false);
                            $('#country-comment').html(data.comment);
                        }
                        else{
                            $('#country-comment-div').attr('hidden', true);
                        }
                        formValid = true;
                        $('#country-comment-div').removeClass('alert-danger').addClass("alert-success");

                        if(data.customer_type_error) {
                            formValid = false;
                            $('#customer-type-error').html(data.customer_type_error);
                            $('#customer-type-error').attr('hidden', false);
                            $('#country-comment-div').removeClass('alert-success').addClass("alert-danger");
                        }
                        else{
                            $('#customer-type-error').attr('hidden', true);
                        }
                        if (data.max_qty_per_passport_error) {
                            formValid = false;
                            $('#max-individual-quantity-error').html(data.max_qty_per_passport_error);
                            $('#country-comment-div').removeClass('alert-success').addClass("alert-danger");

                        } else { 
                            $('#max-individual-quantity-error').html('');
                        }
                        if(data.min_qty_per_company_error) {
                            formValid = false;                          
                            $('#min-company-quantity-error').html(data.min_qty_per_company_error);
                            $('#country-comment-div').removeClass('alert-success').addClass("alert-danger");
                        }else{

                            $('#min-company-quantity-error').html('');
                            console.log("min company qty error not found");
                        }
                        if(data.max_qty_per_company_error) {
                            formValid = false;
                            $('#max-company-quantity-error').html(data.max_qty_per_company_error);
                            $('#country-comment-div').removeClass('alert-success').addClass("alert-danger");
                        }else{
                          
                            $('#max-company-quantity-error').html('');
                        }
                        if(data.company_only_allowed_error) {
                            formValid = false;                           
                            $('#company-only-allowed-error').html(data.company_only_allowed_error);
                            $('#country-comment-div').removeClass('alert-success').addClass("alert-danger");
                        }else{
                            $('#company-only-allowed-error').html('');
                        }
                    }
                });
            }
        }
       
        function getCustomers() {
            var country = $('#country').val();
            var customer_type = $('#customer-type').val();

            let url = '{{ route('letter-of-indents.get-customers') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    country: country,
                    customer_type: customer_type
                },
                success:function (data) {
                    $('#customer').empty();
                    $('#customer').html('<option value="">Select Customer</option>');
                    jQuery.each(data, function(key,value){
                        $('#customer').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        }

        function getModels(index,type) {

            let dealer = $('#dealer').val();
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

            var selectedModelIds = [];
            for(let i=1; i<=totalIndex; i++)
            {
                var eachSelectedModelId = $('#master-model-id-'+i).val();

                if(eachSelectedModelId) {
                    selectedModelIds.push(eachSelectedModelId);
                }
            }
            $.ajax({
                url:"{{route('demand.getMasterModel')}}",
                type: "GET",
                data:
                    {
                        selectedModelIds: selectedModelIds,
                        dealer:dealer,
                    },
                dataType : 'json',
                success: function(data) {
                    myarray = data;

                    var size = myarray.length;
                    if (size >= 1) {
                        let modelDropdownData   = [];
                        $.each(data,function(key,value)
                        {
                            modelDropdownData.push
                            ({

                                id: value.model,
                                text: value.model
                            });
                        });
                        if(type == 'add-new') {
                            $('#model-' + index).html("");
                            $('#model-' + index).select2({
                                placeholder: 'Select Model',
                                allowClear: true,
                                data: modelDropdownData,
                                maximumSelectionLength: 1,
                            });
                        }else{
                            for(let i=1; i<=totalIndex; i++)
                            {
                                $('#model-' + i).html("");
                                $('#model-' + i).select2({
                                    placeholder: 'Select Model',
                                    allowClear: true,
                                    data: modelDropdownData,
                                    maximumSelectionLength: 1,
                                });
                            }
                        }
                    }
                }
            });
        }

        var index = 1;
        $('.add-row-btn').click(function() {
            var index = $("#loi-items").find(".Loi-items-row-div").length + 1;

           var newRow = `
                <div class="row Loi-items-row-div" id="row-${index}">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <select class="form-select widthinput text-dark models" multiple name="models[]" data-index="${index}" id="model-${index}" autofocus>
                            <option value="" >Select Model</option>
                            @foreach($models as $model)
                        <option value="{{ $model->model }}">{{ $model->model }}</option>
                           @endforeach
                        </select>
                        @error('model')
                        <span>
                            <strong >{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                         <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                            <select class="form-select widthinput text-dark sfx" multiple name="sfx[]"  data-index="${index}" id="sfx-${index}" >
                            <option value="">Select SFX</option>
                        </select>
                        @error('sfx')
                        <div role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                <select class="form-select widthinput text-dark model-years" multiple  name="model_year[]" data-index="${index}" id="model-year-${index}">
                                <option value="">Select Model Year</option>
                            </select>
                            @error('model_year')
                        <div role="alert">
                            <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <input type="text" readonly placeholder="LOI Description"
                                   class="form-control widthinput text-dark loi-descriptions" data-index="${index}" id="loi-description-${index}" >
                   </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <input type="number" name="quantity[]" placeholder="Quantity" maxlength="5" class="form-control widthinput text-dark quantities"
                               step="1" oninput="validity.valid||(value='');" min="1" data-index="${index}" id="quantity-${index}">
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <input type="number" readonly id="inventory-quantity-${index}" data-index="${index}" value="" class="form-control widthinput inventory-qty" >
                          <input type="hidden" name="master_model_ids[]" class="master-model-ids" id="master-model-id-${index}">
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <a class="btn btn-sm btn-danger removeButton" id="remove-btn-${index}" data-index="${index}" >  <i class="fas fa-trash-alt"></i> </a>
                    </div>
                    </div>
                    `;
                        $('#loi-items').append(newRow);

                        $('#model-' + index).select2({
                            placeholder: 'Select Model',
                            allowClear: true,
                            maximumSelectionLength: 1
                        });
                        $('#sfx-' + index).select2({
                            placeholder: 'Select SFX',
                            allowClear: true,
                            maximumSelectionLength: 1
                        });
                        $('#model-year-' + index).select2({
                            placeholder: 'Select Model Year',
                            allowClear: true,
                            maximumSelectionLength: 1
                        });

                    let type = 'add-new';
                    getModels(index,type);
        });

        $(document.body).on('click', ".removeButton", function (e) {
            var rowCount = $("#loi-items").find(".Loi-items-row-div").length;
            if(rowCount > 1) {

                var indexNumber = $(this).attr('data-index');
                var modelYear = $('#model-year-'+indexNumber).val();
                var model = $('#model-'+indexNumber).val();
                var sfx = $('#sfx-'+indexNumber).val();
                if(modelYear[0]) {
                    appendModelYear(indexNumber, model[0],sfx[0],modelYear[0]);
                }
                if(model[0]) {
                    appendModel(indexNumber,model[0]);
                }
                if(sfx[0]) {
                    appendSFX(indexNumber,model[0],sfx[0]);
                }

                $(this).closest('#row-'+indexNumber).remove();

                $('.Loi-items-row-div').each(function(i){
                    var index = +i + +1;
                    $(this).attr('id', 'row-'+index);
                    $(this).find('.models').attr('data-index', index);
                    $(this).find('.models').attr('id', 'model-'+index);
                    $(this).find('.sfx').attr('data-index', index);
                    $(this).find('.sfx').attr('id', 'sfx-'+index);
                    $(this).find('.loi-descriptions').attr('data-index', index);
                    $(this).find('.loi-descriptions').attr('id', 'loi-description-'+index);
                    $(this).find('.model-years').attr('data-index', index);
                    $(this).find('.model-years').attr('id', 'model-year-'+index);
                    $(this).find('.quantities').attr('data-index', index);
                    $(this).find('.quantities').attr('id', 'quantity-'+index);
                    $(this).find('.inventory-qty').attr('data-index', index);
                    $(this).find('.inventory-qty').attr('id', 'inventory-quantity-'+index);
                    $(this).find('.master-model-ids').attr('id', 'master-model-id-'+index);
                    $(this).find('.removeButton').attr('data-index', index);
                    $(this).find('.removeButton').attr('id', 'remove-btn-'+index);

                    $('#model-'+index).select2
                    ({
                        placeholder: 'Select Model',
                        maximumSelectionLength:1,
                        allowClear: true
                    });
                    $('#sfx-'+index).select2
                    ({
                        placeholder: 'Select SFX',
                        maximumSelectionLength:1,
                        allowClear: true
                    });
                    $('#model-year-'+index).select2
                    ({
                        placeholder: 'Select Model Year',
                        maximumSelectionLength:1,
                        allowClear: true
                    });
                });
                checkCountryCriterias();

            }else{
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one LOI Item Required',function (e) {
                }).set({title:"Can't Remove LOI Item"})
            }
            enableDealer();
        })

        $(document.body).on('select2:select', ".models", function (e) {
            let index = $(this).attr('data-index');
            $('#model-'+index+'-error').remove();
            getSfx(index);
            $('#dealer').attr("disabled", true);
        });
        $(document.body).on('select2:select', ".sfx", function (e) {
            let index = $(this).attr('data-index');
            $('#sfx-'+index+'-error').remove();
            getModelYear(index);
        });
        $(document.body).on('select2:select', ".model-years", function (e) {
            let index = $(this).attr('data-index');
            $('#model-year-'+index+'-error').remove();
            getLOIDescription(index);
            var value = e.params.data.text;
            hideModelYear(index, value);

        });

        $(document.body).on('select2:unselect', ".sfx", function (e) {
            let index = $(this).attr('data-index');

            $('#loi-description-'+index).val("");
            $('#master-model-id-'+index).val("");
            $('#inventory-quantity-'+index).val("");
            var modelYear =  $('#model-year-'+index).val();
            var model = $('#model-'+index).val();
            var sfx = e.params.data.id;
            if(modelYear[0]) {
                appendModelYear(index, model[0],sfx,modelYear[0])
            }
            appendSFX(index,model[0],sfx);
            $('#model-year-'+index).empty();

        });
        $(document.body).on('select2:unselect', ".models", function (e) {
            console.log("unselected");
            let index = $(this).attr('data-index');

            var modelYear =  $('#model-year-'+index).val();
            var sfx = $('#sfx-'+index).val();
            var model = e.params.data.id;
            if(modelYear[0]){
                appendModelYear(index, model,sfx[0],modelYear[0])
            }
            appendSFX(index,model,sfx[0]);
            appendModel(index,model);
            enableDealer();

            $('#sfx-'+index).empty();
            $('#model-year-'+index).empty();

            $('#loi-description-'+index).val("");
            $('#master-model-id-'+index).val("");
            $('#inventory-quantity-'+index).val("");
            $('#quantity-'+index).val("");
        });
        $(document.body).on('select2:unselect', ".model-years", function (e) {
            let index = $(this).attr('data-index');
            $('#loi-description-'+index).val("");
            $('#master-model-id-'+index).val("");
            $('#inventory-quantity-'+index).val("");
            $('#quantity-'+index).val("");

            var modelYear = e.params.data.id;
            var model = $('#model-'+index).val();
            var sfx = $('#sfx-'+index).val();
            appendModelYear(index, model[0],sfx[0],modelYear);
            // get the unseleted index and match with each row item if model and sfx is matching append that row
        });

       function getSfx(index) {

            let model = $('#model-'+index).val();
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

            var selectedModelIds = [];
            for(let i=1; i<=totalIndex; i++)
            {
                var eachSelectedModelId = $('#master-model-id-'+i).val();
                if(eachSelectedModelId) {
                    selectedModelIds.push(eachSelectedModelId);
                }
            }

            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
                    selectedModelIds:selectedModelIds,
                    module: 'LOI',
                },
                success:function (data) {
                    $('#inventory-quantity-'+index).val(0);
                    $('#sfx-'+index).empty();
                    $('#loi-description-'+index).val("");
                    $('#sfx-'+index).html('<option value=""> Select SFX </option>');
                    $('#model-year-'+index).html('<option value=""> Select Model Year </option>');

                    jQuery.each(data, function(key,value){
                        $('#sfx-'+index).append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
       }
       function getModelYear(index){

           let model = $('#model-'+index).val();
           let sfx = $('#sfx-'+index).val();
           var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

           var selectedModelIds = [];
           for(let i=1; i<=totalIndex; i++)
           {
               var eachSelectedModelId = $('#master-model-id-'+i).val();
               if(eachSelectedModelId) {
                   selectedModelIds.push(eachSelectedModelId);
               }
           }

           let url = '{{ route('demand.get-model-year') }}';
           $.ajax({
               type: "GET",
               url: url,
               dataType: "json",
               data: {
                   sfx: sfx[0],
                   model:model[0],
                   selectedModelIds:selectedModelIds,
               },
               success:function (data) {
                   $('#model-year-'+index).empty();
                   $('#model-year-'+index).html('<option value=""> Select Model Year </option>');
                   $('#loi-description-'+index).html('<option value=""> Select LOI Description </option>');
                   // $('#inventory-quantity').val(quantity);
                   jQuery.each(data, function(key,value){
                       $('#model-year-'+index).append('<option value="'+ value +'">'+ value +'</option>');
                   });
               }
           });
        }
       function getLOIDescription(index) {
           let model_year = $('#model-year-'+index).val();
           let model = $('#model-'+index).val();
           let sfx = $('#sfx-'+index).val();
           let dealer = $('#dealer').val();

           let url = '{{ route('demand.get-loi-description') }}';
           $.ajax({
               type: "GET",
               url: url,
               dataType: "json",
               data: {
                   sfx: sfx[0],
                   model:model[0],
                   model_year: model_year[0],
                   dealer:dealer,
                   module: 'LOI',
               },
               success:function (data) {
                   $('#loi-description-'+index).val("");

                   let quantity = data.quantity;
                   let modelId = data.master_model_id;
                   var LOIDescription = data.loi_description;
                   console.log(LOIDescription);
                   $('#inventory-quantity-'+index).val(quantity);
                   $('#loi-description-'+index).val(LOIDescription);
                    $('#master-model-id-'+index).val(modelId);
               }
           });
        }
       function appendModelYear(index,unSelectedmodel,unSelectedsfx,unSelectedmodelYear) {

           var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

            for(let i=1; i<=totalIndex; i++)
            {
                if(i != index) {
                    var model = $('#model-'+i).val();
                    var sfx = $('#sfx-'+i).val();
                    if(unSelectedmodel == model[0] && unSelectedsfx == sfx[0]) {
                        $('#model-year-'+i).append($('<option>', {value: unSelectedmodelYear, text : unSelectedmodelYear}))
                    }
                }
            }
       }
       function hideModelYear(index, value) {
           var selectedModel = $('#model-'+index).val();
           var selectedSFX = $('#sfx-'+index).val();

           var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
           for(let i=1; i<=totalIndex; i++)
           {
               if(i != index) {
                   var model = $('#model-'+i).val();
                   var sfx = $('#sfx-'+i).val();

                   if(selectedModel[0] == model[0] && selectedSFX[0] == sfx[0]) {
                       var currentId = 'model-year-' + i;
                       $('#' + currentId + ' option[value=' + value + ']').detach();
                   }
               }
           }
       }
       function appendSFX(index,unSelectedmodel,sfx){
           var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

           for(let i=1; i<=totalIndex; i++)
           {
               if(i != index) {
                   var model = $('#model-'+i).val();
                   if(unSelectedmodel == model[0] ) {
                       // chcek this option value alredy exist in dropdown list or not.
                       var currentId = 'sfx-' + i;
                       var isOptionExist = 'no';
                       $('#' + currentId +' option').each(function () {

                           if (this.text == sfx) {
                                isOptionExist = 'yes';
                               return false;
                           }
                       });
                       console.log(isOptionExist);
                       if(isOptionExist == 'no'){
                           $('#sfx-'+i).append($('<option>', {value: sfx, text : sfx}))

                       }

                   }
               }
           }
       }
       function appendModel(index,unSelectedmodel){
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

            for(let i=1; i<=totalIndex; i++)
            {
                if(i != index) {
                    // if(unSelectedmodel == model[0] ) {
                        // chcek this option value alredy exist in dropdown list or not.
                        var currentId = 'model-' + i;
                        var isOptionExist = 'no';
                        $('#' + currentId +' option').each(function () {

                            if (this.text == unSelectedmodel) {
                                isOptionExist = 'yes';
                                return false;
                            }
                        });
                        console.log(isOptionExist);
                        if(isOptionExist == 'no'){
                            $('#model-'+i).append($('<option>', {value: unSelectedmodel, text : unSelectedmodel}))

                        }

                    // }
                }
            }
        }
       function enableDealer() {
            // check any model year is selected or not
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
            var selectedModels = [];
            for(let i=1; i<=totalIndex; i++)
            {
                var model = $('#model'+i).val();
                if(model) {
                    selectedModels.push(model)
                }
            }
            if(selectedModels.length <= 0) {
                $('#dealer').attr("disabled", false);
            }
       }

       $("#addSoNumberBtn").on("click", function ()
	    {
	        var index = $(".soNumberMain").find(".soNumberApendHere").length + 1;
	        $(".soNumberMain").append(`
	                            <div class="col-xxl-4 col-lg-6 col-md-12 soNumberApendHere mt-2" id="row-${index}">
                                    <div class="row">
                                        <div class="col-xxl-9 col-lg-6 col-md-12">
                                            <input id="so_number_${index}" type="text" class="form-control widthinput so_number" name="so_number[${index}]"
                                            placeholder="So Number" value="{{ old('so_number') }}" oninput=uniqueCheckSoNumber() >
                                            <span id="soNumberError_${index}" class="error is-invalid soNumberError"></span>
                                        </div>
                                        <div class="col-xxl-3 col-lg-1 col-md-1 add_del_btn_outer">
                                            <a class="btn btn-sm btn-danger removeSoNumber" data-index="${index}" >
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
	                            </div>
	        `);
	    });

        $(document.body).on('click', ".removeSoNumber", function (e)
	    {
            var indexNumber = $(this).attr('data-index');
        
           
            $(this).closest('#row-'+indexNumber).remove();
            $('.soNumberApendHere').each(function(i) {
                var index = +i + +1;
                $(this).attr('id','row-'+index);
                $(this).find('.so_number').attr('name', 'so_number['+ index +']');
                $(this).find('.so_number').attr('id', 'so_number_'+index);
                $(this).find('.removeSoNumber').attr('data-index',index);
                $(this).find('.soNumberError').attr('id', 'soNumberError_'+index);
                
            });

            uniqueCheckSoNumber();
           
	});

   
 
    function uniqueCheckSoNumber() {
        var totalIndex = $(".soNumberMain").find(".soNumberApendHere").length;
        var isValid = [];
        for(var j = 1; j <= totalIndex; j++)
        {
            soNumberInput = $('#so_number_'+j).val();
            if(soNumberInput != '')
            {
                var existingSoNumbers = [];
                for(var m = 1; m <= totalIndex; m++)
                {
                    if(m != j)
                    {
                        var soNumberInputOther = '';
                        var soNumberInputOther = $('#so_number_'+m).val();
                        existingSoNumbers.push(soNumberInputOther);
                    }
                }
                if(existingSoNumbers.includes(soNumberInput))
                {
                    $msg = "SO Number is already exist";
                    isValid.push(false);
                    showSoNumberError($msg,j);
                }
                else
                {
                    $msg = "";
                    removeSoNumberError($msg,j);
                }
            }
        }
        if(isValid.includes(false))
        {
            formValid = false;
        }else{
           
            formValid = true;
        }
       
    }
    
    function showSoNumberError($msg,i)
	{
	    document.getElementById("soNumberError_"+i).textContent=$msg;
	    document.getElementById("so_number_"+i).classList.add("is-invalid");
	    document.getElementById("soNumberError_"+i).classList.add("paragraph-class");
      
	}
	function removeSoNumberError($msg,i)
	{
	    document.getElementById("soNumberError_"+i).textContent="";
	    document.getElementById("so_number_"+i).classList.remove("is-invalid");
	    document.getElementById("soNumberError_"+i).classList.remove("paragraph-class");
	}
    </script>
@endpush

