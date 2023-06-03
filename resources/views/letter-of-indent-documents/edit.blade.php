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
        <h4 class="card-title">Add New LOI Items</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

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
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13">Customer</label>
                        <select class="form-control" data-trigger name="customer_id" id="customer" readonly>
                            <option> {{ $letterOfIndent->customer->name }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Category</label>
                        <select class="form-control" name="category" readonly >
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}"
                                {{$letterOfIndent->category == \App\Models\LetterOfIndent::LOI_CATEGORY_REAL ? 'selected' : " "}}  >
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_REAL}}
                            </option>
                            <option value="{{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}"
                                {{$letterOfIndent->category == \App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL ? 'selected' : " "}}>
                                {{\App\Models\LetterOfIndent::LOI_CATEGORY_SPECIAL}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">LOI Date</label>
                        <input type="date" class="form-control" id="basicpill-firstname-input" readonly
                               value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}" name="date">
                    </div>
                </div>
            </div>
        <div class="row ">
            @if($letterOfIndentItems->count() > 0)
                <div class="d-flex">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label">Model</label>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label  class="form-label">SFX</label>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label">Varients</label>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label">Qty</label>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($letterOfIndentItems as $value => $letterOfIndentItem)
                    <div class="d-flex">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->model }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->sfx }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->variant_name }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <input type="text" value="{{ $letterOfIndentItem->quantity }}" readonly class="form-control"><br>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <br>
        <form action="{{ route('letter-of-indent-documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="page_name" value="EDIT-PAGE">
            <div class="row" id="deal-doc-upload-div">
                <div class="d-flex">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label">Choose Document</label>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label class="form-label"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <input type="file" name="files[]" class="form-control" multiple id="file-upload" accept="application/pdf, image/*">
                            </div>
                            <input type="hidden" value="{{ $letterOfIndent->id }}" name="letter_of_indent_id">
                            <div class="col-lg-6 col-md-6">
                                <button type="submit" class="btn btn-dark  add-deal-document">Upload & Add New Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <br>
        @foreach($letterOfIndentDocuments as $key => $letterOfIndentDocument)
            <div class="row p-2">
                <div class="col-lg-3 col-md-3">
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
            <div class="modal justify-content-center" id="show-document-{{$letterOfIndentDocument->id}}"  aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog ">
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
        <div class="col-lg-12 col-md-12">
            <a href="{{ route('letter-of-indents.index') }}"> <button type="button" class="btn btn-dark btnright">Finish</button></a>
        </div>
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
    </script>
@endpush

