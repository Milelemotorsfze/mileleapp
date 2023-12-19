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
    </style>
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
                        <select class="form-control" name="country" id="country" autofocus>
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
                        <select class="form-control" name="customer_type" id="customer-type">
                            <option value="" disabled>Type</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}>{{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}>{{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}>{{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}>{{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label ">Customer</label>
                        <select class="form-control @error('customer_id') is-invalid @enderror" name="customer_id" id="customer" >
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
                        <input type="date" class="form-control" id="basicpill-firstname-input"  name="date">
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
                        <select class="form-control" name="category" id="choices-single-default">
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
                        <select class="form-control" name="dealers" id="dealer">
                            <option value="Trans Cars">Trans Cars</option>
                            <option value="Milele Motors">Milele Motors</option>
                        </select>
                        @error('dealers')
                        <span role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">So Number</label>
                        <input type="text" class="form-control" name="so_number" placeholder="So Number">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Destination</label>
                        <input type="text" class="form-control" name="destination" placeholder="Destination" >
                        @error('destination')
                        <span role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Prefered Location</label>
                        <input type="text" class="form-control" name="prefered_location" placeholder="Prefered Location" >
                        @error('prefered_location')
                        <span role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">LOI Document</label>
                        <input type="file" name="files[]" id="file-upload" class="form-control text-dark" multiple
                               autofocus accept="application/pdf">
                    </div>
                </div>
                <br>
                <div class="card p-2" >
                    <div class="card-header">
                        <h4 class="card-title ">LOI Items</h4>
                    </div>
                    <div class="card-body">
                        <div id="loi-items" >
                            <div class="row" data-row="1">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <label class="form-label">Model</label>
                                    <select class="form-select widthinput text-dark models" multiple required data-index="1" name="models[]" id="model-1" autofocus>
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
                                <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label">SFX</label>
                                    <select class="form-select widthinput text-dark sfx" multiple required data-index="1" name="sfx[]" id="sfx-1" >
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
                                    <select class="form-select widthinput text-dark model-years" multiple required data-index="1" name="model_year[]" id="model-year-1">
                                        <option value="">Select Model Year</option>
                                    </select>
                                    @error('model_year')
                                    <div role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label class="form-label">LOI Description</label>
                                    <input type="text" readonly placeholder="LOI Description"
                                           class="form-control widthinput text-dark" data-index="1" id="loi-description-1">
                                </div>
                                <div class="col-lg-1 col-md-6 col-sm-12">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" name="quantity[]" placeholder="Quantity" required maxlength="5" data-index="1" class="form-control widthinput text-dark"
                                           step="1" oninput="validity.valid||(value='');" min="0" id="quantity-1">
                                </div>
                                <div class="col-lg-1 col-md-6 col-sm-12">
                                    <label class="form-label">Inventory Qty</label>
                                    <input type="number" readonly id="inventory-quantity-1" value="" data-index="1" class="form-control widthinput" >
                                </div>
                                <div class="col-lg-1 col-md-6 col-sm-12">
                                    <a class="btn btn-sm btn-danger removeButton" id="remove-btn-1" data-index="1" data-index="1" style="margin-top: 30px;" >  <i class="fas fa-trash-alt"></i> </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="btn btn-outline-info btn-sm add-row-btn float-end" data-row="1">
                                    <i class="fas fa-plus"></i> Add LOI Item
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="row mb-3">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-3">
                        <div id="file-preview">

                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div id="image-preview">

                        </div>
                    </div>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit </button>
                </div>
            </div>

        </form>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        const fileInputLicense = document.querySelector("#file-upload");
        const previewFile = document.querySelector("#file-preview");
        const previewImage = document.querySelector("#image-preview");
        fileInputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile.firstChild) {
                previewFile.removeChild(previewFile.firstChild);
            }
            while (previewImage.firstChild) {
                previewImage.removeChild(previewImage.firstChild);
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
                }
                else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewImage.appendChild(image);
                }
            }
        });
        getCustomers();
        $("#form-create").validate({
            ignore: [],
            rules: {
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
                model: {
                    required: true,
                },
                sfx: {
                    required: true,
                },
                model_year: {
                    required: true,
                },
                loi_description: {
                    required: true,
                },
                quantity:{
                    required:true
                },
                "files[]": {
                    extension: "pdf"
                },
                messages: {
                    file: {
                        extension: "Please upload pdf file"
                    }
                }
            },
        });
        $('#country').select2({
            placeholder : 'Select Country'
        });

        $('#country').change(function (){
           getCustomers();
        });
        $('#customer-type').change(function (){
            getCustomers();
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

        var index = 1;
        $('.add-row-btn').click(function() {
            index++;
            var newRow = `
                <div class="row" data-row="${index}">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <select class="form-select widthinput text-dark models" multiple name="models[]" required data-index="${index}" id="model-${index}" autofocus>
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
                     <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                        <select class="form-select widthinput text-dark sfx" multiple name="sfx[]" required data-index="${index}" id="sfx-${index}" >
                            <option value="">Select SFX</option>
                        </select>
                        @error('sfx')
                        <div role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                            <select class="form-select widthinput text-dark model-years" multiple required name="model_year[]" data-index="${index}" id="model-year-${index}">
                                <option value="">Select Model Year</option>
                            </select>
                            @error('model_year')
                            <div role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                        <input type="text" readonly placeholder="LOI Description"
                               class="form-control widthinput text-dark" data-index="${index}" id="loi-description-${index}" >
                   </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <input type="number" name="quantity[]" placeholder="Quantity" maxlength="5" required class="form-control widthinput text-dark"
                               step="1" oninput="validity.valid||(value='');" min="0" data-index="${index}" id="quantity-${index}">
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <input type="number" readonly id="inventory-quantity-${index}" data-index="${index}" value="" class="form-control widthinput" >
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

        });

        $(document.body).on('click', ".removeButton", function (e) {
            alert("ok");
            var indexNumber = $(this).attr('data-index');

            // $(this).closest('#row-'+indexNumber).find("option:selected").each(function() {
            //     var id = (this.value);
            //     var text = (this.text);
            //     addOption(id,text)
            // });

            $(this).closest('#row-'+indexNumber).remove();

            $('.form_field_outer_row').each(function(i){
                var index = +i + +1;
                $(this).attr('data-row', index);
                $(this).find('.models').attr('data-index', index);
                $(this).find('.models').attr('id', 'model-'+index);
                // $(this).find('select').attr('id','vehicles-'+ index);
                // $(this).find('.variant-detail').attr('id','variant-detail-'+index);
                // $(this).find('.select').attr('data-select2-id','select2-data-vehicles-'+index);
                //
                // $(this).find('button').attr('data-index', index);
                // $(this).find('button').attr('id','remove-'+ index);
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
        })

        $(document.body).on('select2:select', ".models", function (e) {
            let index = $(this).attr('data-index');
            getSfx(index);
        });
        $(document.body).on('select2:select', ".sfx", function (e) {
            let index = $(this).attr('data-index');
            getModelYear(index);
        });
        $(document.body).on('select2:select', ".model-years", function (e) {
            let index = $(this).attr('data-index');
            getLOIDescription(index);
        });

        function getSfx(index) {
            let model = $('#model-'+index).val();

            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
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
           console.log(sfx);
           let url = '{{ route('demand.get-model-year') }}';
           $.ajax({
               type: "GET",
               url: url,
               dataType: "json",
               data: {
                   sfx: sfx[0],
                   model:model[0],
               },
               success:function (data) {
                   console.log(data);
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
                   var LOIDescription = data.loi_description;
                   console.log(LOIDescription);
                   $('#inventory-quantity-'+index).val(quantity);
                   $('#loi-description-'+index).val(LOIDescription);
               }
           });
        }
    </script>
@endpush

