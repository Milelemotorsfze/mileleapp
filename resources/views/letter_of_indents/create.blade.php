@extends('layouts.main')
@section('content')
    <style>
        iframe {
            min-height: 300px;
            max-height: 500px;
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
                                <option value="{{$country}}"> {{ $country }} </option>
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
                        <select class="form-control" name="dealers" >
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
                        <label for="choices-single-default" class="form-label font-size-13 ">So Number</label>
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
                    <button type="submit" class="btn btn-primary" >Next<i class="fa fa-arrow-right " style="margin-left: 3px;"></i> </button>
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
    </script>
@endpush

