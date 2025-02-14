@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 400px;
            margin-bottom: 10px;
        }
        .custom-error {
            color: red;
        }
    </style>
    @can('edit-customer')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-customer');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit Customer</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('dm-customers.index') }}" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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

                <form action="{{ route('dm-customers.update', $customer->id) }}" id="form-update" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name"  placeholder="Enter Name" value="{{ old('name', $customer->name) }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-control" name="country_id[]" multiple id="country" autofocus>
                                    <option ></option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" {{ in_array($country->id, $customerCountries) ? 'selected' : '' }}> {{ $country->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label  text-muted">Customer Type</label>
                                <select class="form-control" name="type" id="customer-type">
                                    <option value="" disabled>Type</option>
                                    <option value="{{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}" {{ $customer->customertype == \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL ? 'selected' : '' }}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}</option>
                                    <option value="{{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}" {{ $customer->customertype == \App\Models\Clients::CUSTOMER_TYPE_COMPANY ? 'selected' : '' }}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}</option>
                                    <option value="{{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}"  {{ $customer->customertype == \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT ? 'selected' : '' }}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}</option>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Passport File</label>
                                <input type="file" class="form-control" name="passport_file" accept='image/*' id="file1-upload"  placeholder="Upload Passport">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Trade License</label>
                                <input type="file" class="form-control" id="file2-upload" accept='image/*' name="trade_license_file" placeholder="Upload Trade License">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Other Document</label>
                                <input type="file" class="form-control" id="file3-upload"  accept='image/*' multiple  name="other_document_file[]" placeholder="Upload Other Document">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Address</label>
                                <textarea class="form-control" name="address" rows="5" cols="25">{{ old('address', $customer->address) }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6" id="file-preview">
                        </div>
                    </div>
                        <div class="card preview-file"> 
                            <div class="card-header">
                                <h4 class="card-title">Customer Document</h4> </div>
                                <div class="card-body">
                                    
                                 <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="file1-preview">
                                            @if($customer->passport)
                                                <h6 class="fw-bold text-center">Passport</h6>
                                                <iframe src="{{ url('storage/app/public/passports/' .$customer->passport) }}"
                                                width="500px;" height="300px;" alt="Passport"></iframe>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="file2-preview">
                                            @if($customer->tradelicense)
                                                <h6 class="fw-bold text-center">Trade License</h6>
                                                <iframe src="{{ url('storage/app/public/tradelicenses/' .$customer->tradelicense) }}" 
                                                width="500px;" height="300px;" alt="Trade License"></iframe>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($customer->clientDocuments->count() > 0)
                                    <h6 class="fw-bold text-center other-doc-title p-2" style="background-color:#E9EAEC">Other Documents</h6>
                                    <div class="row">
                                        @foreach($customer->clientDocuments as $key => $customerDocument)
                                        <div class="col-lg-4 col-md-12 col-sm-12 text-center" id="remove-doc-{{$customerDocument->id}}">
                                            <iframe src="{{ url('customer-other-documents/' . $customerDocument->document) }}" alt="File"></iframe>
                                            <a href="#"  data-id="{{ $customerDocument->id }}"
                                            class="btn btn-danger text-center mt-2 remove-doc-button"><i class="fa fa-trash"></i> </a>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-12 col-sm-12 text-center">
                                        <div id="preview-other-documents">
                                        </div>
                                    </div>
                                </div>
                                
                                <select name="deletedIds[]" id="deleted-docs" hidden="hidden" multiple>
                                </select>
                                 <input type="hidden" id="is-passport-or-trade-licence-added" value="{{ $ispassortOrTradeLicenseAvailable }}">
                                <input type="hidden" id="remaining-customer-document-count" value="{{ $customer->clientDocuments->count() }}" >
                            </div>
                        </div>
                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary" id="submit-button">Submit</button>
                    </div>

                </form>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>       
        let cusDocCount = '{{ $customer->clientDocuments->count() }}';
        let deletedDocumetIds = [];

        const fileInputLicense1 = document.querySelector("#file1-upload");
        const fileInputLicense2 = document.querySelector("#file2-upload");
        const fileInputLicense3 = document.querySelector("#file3-upload");

        const previewFile1 = document.querySelector("#file1-preview");
        const previewFile2 = document.querySelector("#file2-preview");
        const previewFile3 = document.querySelector("#preview-other-documents");

        fileInputLicense1.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);
            const files = event.target.files;
            while (previewFile1.firstChild) {
                previewFile1.removeChild(previewFile1.firstChild);
            }

            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
                if (file.type.match("application/pdf"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFile1.appendChild(iframe);
                }
                else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFile1.appendChild(image);
                }
            }
            $('#is-passport-or-trade-licence-added').val(1);

        });
        fileInputLicense2.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);

            const files = event.target.files;
            while (previewFile2.firstChild) {
                previewFile2.removeChild(previewFile2.firstChild);
            }
            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
                if (file.type.match("application/pdf"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFile2.appendChild(iframe);
                }
                else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFile2.appendChild(image);
                }
            }
            $('#is-passport-or-trade-licence-added').val(1);

        });
        
        fileInputLicense3.addEventListener("change", function(event) {
            $('.preview-div').attr('hidden', false);

            const files = event.target.files;
            while (previewFile3.firstChild) {
                previewFile3.removeChild(previewFile3.firstChild);
            }
            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
                if (file.type.match("application/pdf"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFile3.appendChild(iframe);
                }
                else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    console.log(image);
                    previewFile3.appendChild(image);
                }
            }
            let remainingCount = $('#remaining-customer-document-count').val(0);
            let deletedCount = $('#deleted-docs option').length;
            console.log(deletedCount);
            let existingDocumentCount = cusDocCount - parseInt(deletedCount);
            let totalDocumentCount = parseInt(existingDocumentCount) + parseInt(files.length);
            $('#remaining-customer-document-count').val(totalDocumentCount);

        });


        $('#country').select2({
            placeholder: 'Select Country'
        })
        $('#country').change(function (){
            $('#country-error').remove();
        })
        $("#form-update").validate({
            rules: {
                name: {
                    required: true,
                },
                type: {
                    required: true,
                },
                "country_id[]": {
                    required: true,
                },
                passport_file:{
                      extension: "png|jpeg|jpg"
                },
                trade_license_file:{
                     extension: "png|jpeg|jpg"
                },
                "other_document_file[]": {
                    extension: "png|jpeg|jpg",
                    maxsize:5242880 
                },
            },
            messages: {
                trade_license_file: {
                    extension: "Please upload image file format (png,jpeg,jpg)"
                },
                passport_file:{
                    extension: "Please upload Image file format (png,jpeg,jpg)"
                },
                other_document_file: {
                    extension: "Please upload file format (png,jpeg,jpg)"
                },
            },
            errorPlacement: function(error, element) {
                    error.addClass('custom-error');
                    var name = element.attr("name");
                    if (name.match(/\country_id\[\]\b/)) {
                        if (element.data('select2')) {
                            error.insertAfter(element.next('.select2'));
                        } else {
                            error.insertAfter(element.next('.select2'));
                        }
                    } else {
                        error.insertAfter(element.next('.select2'));
                    }
            }
        });

        $('.remove-doc-button').click(function () {
                let id = $(this).attr('data-id');
                console.log(id);
                $('#remove-doc-'+id).remove();
                deletedDocumetIds.push(id);
                $('#deleted-docs').empty();

                jQuery.each(deletedDocumetIds, function (key, value) {

                    $('#deleted-docs').append('<option value="' + value + '" >' + value+ '</option>');
                    $("#deleted-docs option").attr("selected", "selected");
                });
                let remainingCount = $('#remaining-customer-document-count').val();
                    remainingCount = parseInt(remainingCount) - 1;
                $('#remaining-customer-document-count').val(remainingCount);

            });
            $('#submit-button').click(function (e) {
                    e.preventDefault();
                let isFileCount = $('#remaining-customer-document-count').val();
                let isPasssOrLicenseExist = $('#is-passport-or-trade-licence-added').val();
                if (isFileCount > 0 || isPasssOrLicenseExist == 1) {
                    if($("#form-update").valid()) {
                        $('#form-update').unbind('submit').submit();
                        // alert("submit");
                        e.preventDefault();
                    }
                }else{
                    var confirm = alertify.confirm('Atleast one Document Required',function (e) {
                    }).set({title:"Error !"})
                    e.preventDefault();
                }
            
        });

        
    </script>
@endpush


