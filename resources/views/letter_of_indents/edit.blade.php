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
                        <select class="form-control" autofocus name="country" id="country" >
                            <option disabled>Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{$country}}" {{ $country == $letterOfIndent->customer->country ? 'selected' : '' }} > {{ $country }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">Customer Type</label>
                        <select class="form-control" name="customer_type" id="customer-type">
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
                        <select class="form-control" data-trigger name="customer_id" id="customer" >
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label text-muted">LOI Category</label>
                        <select class="form-control" name="category" id="choices-single-default">
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
                        <input type="date" class="form-control" id="basicpill-firstname-input" name="date"
                               value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">Dealers</label>
                        <select class="form-control" data-trigger name="dealers" >
                            <option value="Trans Cars" {{ 'Trans Cars' == $letterOfIndent->dealers ? 'selected' : '' }}>Trans Cars</option>
                            <option value="Milele Motors" {{ 'Milele Motors' == $letterOfIndent->dealers ? 'selected' : '' }}>Milele Motors</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label">So Number</label>
                        <input type="text" class="form-control" name="so_number" placeholder="So Number" value="{{ $letterOfIndent->so_number }}">
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
                        <input type="text" class="form-control" name="destination" placeholder="Destination" value="{{ $letterOfIndent->destination }}">
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
                        <input type="text" class="form-control" name="prefered_location" placeholder="Prefered Location" value="{{ $letterOfIndent->prefered_location }}" >
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
                        <input type="file" name="files[]" class="form-control mb-3" multiple
                               autofocus id="file-upload" accept="application/pdf">
                    </div>
                </div>
                @foreach($letterOfIndent->LOIDocuments as $key => $letterOfIndentDocument)
                    <div class="row p-2">
                        <div class="col-12">
                            File {{ $key + 1 }}
                            <button type="button" class="btn btn-primary btn-sm pl-2" data-bs-toggle="modal" data-bs-target="#show-document-{{$letterOfIndentDocument->id}}">
                                View
                            </button>
                            <button type="button" class="btn btn-danger btn-sm loi-doc-button-delete"
                                    data-id="{{ $letterOfIndentDocument->id }}" data-url="{{ route('letter-of-indent-documents.destroy', $letterOfIndentDocument->id) }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Modal -->

                    <div class="modal mb-5 justify-content-center" id="show-document-{{$letterOfIndentDocument->id}}"  aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">LOI Document</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="d-flex">
                                        <div class="col-lg-12">
                                            <div class="row p-2">
                                                <embed src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}" style="height: 400px">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-6">
                        <div id="file-preview">

                        </div>
                    </div>
                    <div class="col-4">
                        <div id="image-preview">

                        </div>
                    </div>
                </div>
                <br>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('letter-of-indent-items.edit', $letterOfIndent->id) }}" >
                        <button type="button" class="btn btn-info"> Next <i class="fa fa-arrow-right"></i></button>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" ></script>

    <script type="text/javascript">

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
            $('#country').select2({
                placeholder: 'Select Country'
            });
            $('#country').change(function () {
                getCustomers();
            });
            $('#customer-type').change(function () {
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

        $("#form-doc-upload").validate({
            ignore: [],
            rules: {
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
        // })
    </script>
@endpush

