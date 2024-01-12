@extends('layouts.main')
@section('content')
    <style>
        iframe {
            min-height: 300px;
            max-height: 500px;
        }
        .modal-content{
            width: 1000px;
            height: 550px;
        }
        .widthinput
        {
            height:32px!important;
        }

    </style>

    <div class="card-header">
        <h4 class="card-title">Edit LOI</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
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
        <form action="{{ route('letter-of-indents.update', $letterOfIndent->id) }}" method="POST" enctype="multipart/form-data" id="form-doc-upload">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label"> Country</label>
                        <select class="form-control widthinput" autofocus multiple name="country" id="country" >
                            <option disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" {{ $country->id == $letterOfIndent->customer->country_id ? 'selected' : '' }} > {{ $country->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">Customer Type</label>
                        <select class="form-control widthinput" name="customer_type" id="customer-type">
                            <option value="" disabled>Select Customer Type</option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL == $letterOfIndent->customer->type ? 'selected' : ''}}>
                                {{ \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL }}
                            </option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY == $letterOfIndent->customer->type ? 'selected' : ''}}>
                                {{ \App\Models\Customer::CUSTOMER_TYPE_COMPANY }}
                            </option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT == $letterOfIndent->customer->type ? 'selected' : ''}} >
                                {{ \App\Models\Customer::CUSTOMER_TYPE_GOVERMENT }}
                            </option>
                            <option value={{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}
                                {{ \App\Models\Customer::CUSTOMER_TYPE_NGO == $letterOfIndent->customer->type ? 'selected' : ''}} >
                                {{ \App\Models\Customer::CUSTOMER_TYPE_NGO }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Customer</label>
                        <select class="form-control widthinput" data-trigger name="customer_id" id="customer" >
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">LOI Category</label>
                        <select class="form-control widthinput" name="category" id="choices-single-default">
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_MANAGEMENT_REQUEST}}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_MANAGEMENT_REQUEST == $letterOfIndent->category ? 'selected' : ''}} >
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_MANAGEMENT_REQUEST}}
                            </option>
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_END_USER_CHANGED}}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_END_USER_CHANGED == $letterOfIndent->category ? 'selected' : ''}} >
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_END_USER_CHANGED }}
                            </option>
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_REAL == $letterOfIndent->category ? 'selected' : ''}} >
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}
                            </option>
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL == $letterOfIndent->category ? 'selected' : ''}} >
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}
                            </option>
                            <option value="{{ \App\Models\LetterOfIndent::LOI_CATEGORY_QUANTITY_INFLATE }}"
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_QUANTITY_INFLATE == $letterOfIndent->category ? 'selected' : ''}} >
                                {{ \App\Models\LetterOfIndent::LOI_CATEGORY_QUANTITY_INFLATE }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">LOI Date</label>
                        <input type="date" class="form-control widthinput" id="basicpill-firstname-input" name="date"
                               value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Dealers</label>
                        <select class="form-control widthinput" disabled  id="dealer" name="dealers" >
                            <option value="Trans Cars" {{ 'Trans Cars' == $letterOfIndent->dealers ? 'selected' : '' }}>Trans Cars</option>
                            <option value="Milele Motors" {{ 'Milele Motors' == $letterOfIndent->dealers ? 'selected' : '' }}>Milele Motors</option>
                        </select>
                        <input name="dealers" type="hidden" value="{{ $letterOfIndent->dealers }}" id="dealer-input" >
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">So Number</label>
                        <input type="text" class="form-control widthinput" name="so_number" placeholder="So Number" value="{{ $letterOfIndent->so_number }}">
                        @error('so_number')
                        <span role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Destination</label>
                        <input type="text" class="form-control widthinput" name="destination" placeholder="Destination" value="{{ $letterOfIndent->destination }}">
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
                        <input type="text" class="form-control widthinput" name="prefered_location" placeholder="Prefered Location" value="{{ $letterOfIndent->prefered_location }}" >
                        @error('prefered_location')
                        <span role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">LOI Document</label>
                        <input type="file" name="files[]" class="form-control widthinput mb-3" multiple
                               autofocus id="file-upload" accept="application/pdf">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label class="form-label">Signature</label>
                        <input type="file" id="signature" name="loi_signature" class="form-control widthinput" accept="application/pdf">
                    </div>
                </div>
            </div>
            <div class="row">
                @if($letterOfIndent->signature)
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label class="form-label fw-bold">Signature</label>
                        <div class="mb-3" id="signature-preview">
                            <iframe src="{{ url('/LOI-Signature/'.$letterOfIndent->signature) }}"></iframe>
                        </div>
                    </div>
                @endif
                @if($letterOfIndent->LOIDocuments->count() > 0)
                    <label class="form-label fw-bold">LOI Document</label>
                    @foreach($letterOfIndent->LOIDocuments as $key => $letterOfIndentDocument)
                        <div class="col-lg-3 col-md-6 col-sm-12 " id="remove-doc-{{$letterOfIndentDocument->id}}">
                            <iframe src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}" style="height: 300px;"></iframe>
                            <a href="#"  data-id="{{ $letterOfIndentDocument->id }}"
                               class="btn btn-danger text-center mt-2 remove-doc-button"><i class="fa fa-trash"></i> </a>
                        </div>
                    @endforeach
                @endif
                <div class="col-lg-3 col-md-6 col-sm-12"  >
                    <div id="file-preview">
                    </div>
                </div>
            </div>
            <div class="card mt-2" >
                    <div class="card-header">
                        <h4 class="card-title ">LOI Items</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label class="form-label">Model</label>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">SFX</label>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">Model Year</label>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label class="form-label">LOI Description</label>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12">
                                <label class="form-label">Quantity</label>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12">
                                <label class="form-label">Inventory Qty</label>
                            </div>
                        </div>
                        <div id="loi-items" >
                            @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                                <div class="row Loi-items-row-div" id="row-{{$key+1}}">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
{{--                                        <label class="form-label">Model</label>--}}
                                        <select class="form-select widthinput text-dark models" multiple data-index="{{$key+1}}" name="models[]" id="model-{{$key+1}}" autofocus>
                                            <option value="" >Select Model</option>
                                            @foreach($models as $model)
                                                <option value="{{ $model->model }}" {{ $letterOfIndentItem->masterModel->model == $model->model ? 'selected' : '' }}>{{ $model->model }}</option>
                                            @endforeach
                                        </select>
                                        @error('model')
                                        <span>
                                            <strong >{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
{{--                                        <label class="form-label">SFX</label>--}}
                                        <select class="form-select widthinput text-dark sfx" multiple  data-index="{{$key+1}}" name="sfx[]" id="sfx-{{$key+1}}" >
                                            @foreach($letterOfIndentItem->sfxLists as $sfx)
                                                <option value="{{ $sfx}}" {{$sfx == $letterOfIndentItem->masterModel->sfx ? 'selected' : ''}} >{{ $sfx }}</option>
                                            @endforeach
                                        </select>
                                        @error('sfx')
                                        <div role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
{{--                                        <label class="form-label">Model Year</label>--}}
                                        <select class="form-select widthinput text-dark model-years" multiple  data-index="{{$key+1}}" name="model_year[]" id="model-year-{{$key+1}}">
                                            @foreach($letterOfIndentItem->modelYearLists as $modelYear)
                                                <option value="{{ $modelYear }}" {{$modelYear == $letterOfIndentItem->masterModel->model_year ? 'selected' : ''}} >{{ $modelYear }}</option>
                                            @endforeach
                                        </select>
                                        @error('model_year')
                                        <div role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
{{--                                        <label class="form-label">LOI Description</label>--}}
                                        <input type="text" readonly placeholder="LOI Description"
                                               class="form-control widthinput text-dark loi-descriptions"
                                               value="{{$letterOfIndentItem->loi_description}}" data-index="{{$key+1}}" id="loi-description-{{$key+1}}">
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-12">
{{--                                        <label class="form-label">Quantity</label>--}}
                                        <input type="number" name="quantity[]" placeholder="Quantity"  maxlength="5" value="{{$letterOfIndentItem->quantity}}" data-index="{{$key+1}}"
                                               class="form-control widthinput quantities text-dark"
                                               step="1" oninput="validity.valid||(value='');" min="0" id="quantity-{{$key+1}}">
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-12">
{{--                                        <label class="form-label">Inventory Qty</label>--}}
                                        <input type="number" readonly id="inventory-quantity-{{$key+1}}" value="{{$letterOfIndentItem->inventory_quantity}}" data-index="{{$key+1}}" class="form-control widthinput inventory-qty" >
                                        <input type="hidden" name="master_model_ids[]" class="master-model-ids" value="{{$letterOfIndentItem->master_model_id}}" id="master-model-id-{{$key+1}}">
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-12">
                                        <a class="btn btn-sm btn-danger removeButton" id="remove-btn-{{$key+1}}" data-index="{{$key+1}}"  >  <i class="fas fa-trash-alt"></i> </a>
                                    </div>
                                </div>
                            @endforeach

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
            <select name="deletedIds[]" id="deleted-docs" hidden="hidden" multiple>
            </select>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary float-end">Update</button>
            </div>

        </form>
    </div>
@endsection
@push('scripts')
    <script>
        let deletedDocumetIds = [];
        const fileInputLicense = document.querySelector("#file-upload");
        const previewFile = document.querySelector("#file-preview");
        fileInputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            // while (previewFile.firstChild) {
            //     previewFile.removeChild(previewFile.firstChild);
            // }
            // while (previewImage.firstChild) {
            //     previewImage.removeChild(previewImage.firstChild);
            // }
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
                // else if (file.type.match("image/*"))
                // {
                //     const objectUrl = URL.createObjectURL(file);
                //     const image = new Image();
                //     image.src = objectUrl;
                //     previewImage.appendChild(image);
                // }
            }
        });

        const signatureFileInput = document.querySelector("#signature");
        const signaturePreviewFile = document.querySelector("#signature-preview");
        signatureFileInput.addEventListener("change", function(event) {
            const files = event.target.files;
            while (signaturePreviewFile.firstChild) {
                signaturePreviewFile.removeChild(signaturePreviewFile.firstChild);
            }

            const file = files[0];
            if (file.type.match("application/pdf"))
            {
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                signaturePreviewFile.closest('.row').appendChild(iframe);
            }

        });

            getCustomers();

            $('#country').select2({
                placeholder: 'Select Country',
                allowClear: true,
                maximumSelectionLength: 1,
            }).on('change', function() {
                getCustomers();
            });
            $('#dealer').change(function () {
                var value = $('#dealer').val();
                $('#dealer-input').val(value);

                getModels('all','dealer-change');

            });

            $('#customer-type').change(function () {
                getCustomers();
            });

            $('.remove-doc-button').click(function () {
                let id = $(this).attr('data-id');
                $('#remove-doc-'+id).remove();
                deletedDocumetIds.push(id);
                $('#deleted-docs').empty();

                jQuery.each(deletedDocumetIds, function (key, value) {
                    console.log(value);
                    $('#deleted-docs').append('<option value="' + value + '" >' + value+ '</option>');
                    $("#deleted-docs option").attr("selected", "selected");
                });
            });
            var LOICount = '{{ $letterOfIndentItems->count() }}';

            for(var i=1;i<=LOICount;i++) {
                $('#model-'+i).select2({
                    placeholder: 'Select Model',
                    allowClear: true,
                    maximumSelectionLength: 1
                }).on('change', function() {
                    $(this).valid();
                });
                $('#sfx-'+i).select2({
                    placeholder : 'Select SFX',
                    allowClear: true,
                    maximumSelectionLength: 1
                }).on('change', function() {
                    $(this).valid();
                });
                $('#model-year-'+i).select2({
                    placeholder : 'Select Model Year',
                    allowClear: true,
                    maximumSelectionLength: 1
                }).on('change', function() {
                    $(this).valid();
                });
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
                    success: function (data) {
                        $('#customer').empty();
                        jQuery.each(data, function (key, value) {
                            var selectedId = '{{ $letterOfIndent->customer_id }}';
                            $('#customer').append('<option value="' + value.id + ' " >' + value.name + '</option>');
                        });
                    }
                });
            }
            $('.loi-doc-button-delete').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
        //
        // jQuery.validator.addMethod('required_check', function(value, element) {
        //     let dealer = $('#dealer').val();
        //     if(dealer == 'Milele Motors') {
        //         return false;
        //     }else{
        //         return true;
        //     }
        // },'This feild is required');

        $("#form-doc-upload").validate({
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
                    extension: "pdf"
                },
                messages: {
                    file: {
                        extension: "Please upload pdf file"
                    }
                }
            },
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
                        <input type="number" name="quantity[]" placeholder="Quantity" maxlength="5" value="1" class="form-control widthinput text-dark quantities"
                               step="1" oninput="validity.valid||(value='');" min="0" data-index="${index}" id="quantity-${index}">
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
                enableDealer();

            }else{
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one LOI Item Required',function (e) {
                }).set({title:"Can't Remove LOI Item"})

            }
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
            $('#model-year-'+index).empty();

            var modelYear =  $('#model-year-'+index).val();
            var model = $('#model-'+index).val();
            var sfx = e.params.data.id;
            if(modelYear[0]){
                appendModelYear(index, model[0],sfx,modelYear[0])
            }
            appendSFX(index,model[0],sfx)

        });
        $(document.body).on('select2:unselect', ".models", function (e) {
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

        });
        $(document.body).on('select2:unselect', ".model-years", function (e) {
            let index = $(this).attr('data-index');
            $('#loi-description-'+index).val("");
            $('#master-model-id-'+index).val("");
            $('#inventory-quantity-'+index).val("");

            var modelYear = e.params.data.id;
            var model = $('#model-'+index).val();
            var sfx = $('#sfx-'+index).val();
            appendModelYear(index, model[0],sfx[0],modelYear);

            // get the unseleted index and match with each row  item if model and sfx is matching append that row
        });

        function getModels(index,type) {

            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
            let dealer = $('#dealer').val();

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
                        let modelDropdownData = [];
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
                var model = $('#model-'+i).val();
                if(model[0]) {
                    selectedModels.push(model[0])
                }
            }
            if(selectedModels.length <= 0) {
                $('#dealer').attr("disabled", false);
            }
        }

    </script>
@endpush

