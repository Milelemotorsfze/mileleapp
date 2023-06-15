@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 400px;
            margin-bottom: 10px;
        }
    </style>
    <div class="card-header">
        <h4 class="card-title">Create New PFI</h4>
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
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Customer</label>
                    <select class="form-control" data-trigger name="customer_id" id="customer" readonly>
                        <option> {{ $letterOfIndent->customer->name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label ">LOI Category</label>
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
                    <label for="choices-single-default" class="form-label">LOI Date</label>
                    <input type="date" class="form-control" id="basicpill-firstname-input" readonly
                           value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}" name="date">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Dealer</label>
                    <input type="text" class="form-control" value="{{ $letterOfIndent->dealers }}" readonly>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Shipping Method</label>
                    <input type="text" class="form-control" value="{{ $letterOfIndent->shipment_method }}" readonly>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <label for="choices-single-default" class="form-label">Supplier</label>
                    <input type="text" class="form-control" value="{{ $letterOfIndent->supplier->supplier ?? '' }}" readonly>
                </div>
            </div>
        </div>
            <div class="row">
            <div class="d-flex d-none d-lg-block d-xl-block d-xxl-block">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label">Model</label>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label  class="form-label">SFX</label>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <label class="form-label">Varient</label>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <label class="form-label">Quantity</label>
                        </div>
                    </div>
                </div>
            </div>
            @if($approvedPfiItems->count() > 0)
                @foreach($approvedPfiItems as $value => $approvedPfiItem)
                    <div class="d-flex">
                        <div class="col-lg-12">
                            <div class="row mt-2">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                    <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->model }}" readonly class="form-control mb-2">
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                    <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->sfx }}" readonly class="form-control mb-2">
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                    <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->variant_name }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                    <input type="text" value="{{ $approvedPfiItem->quantity }}" readonly class="form-control mb-2">
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <label class="form-label d-lg-none d-xl-none d-xxl-none"></label>
                                    <button type="button" class="btn btn-danger btn-sm remove" data-id="{{ $approvedPfiItem->id }}"
                                            data-action="REMOVE" >
                                       Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
            <div class="row">
                @if($pendingPfiItems->count() > 0)
                    <p class="fw-bold font-size-16 mt-3">Approved Inventory</p>
                    @foreach($pendingPfiItems as $value => $pendingPfiItem)
                        <div class="d-flex">
                            <div class="col-lg-12">
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-3">
                                        <label class="form-label d-block d-sm-none">Model</label>
                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->model }}" readonly class="form-control mb-2">
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <label class="form-label d-block d-sm-none">SFX</label>
                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->sfx }}" readonly class="form-control mb-2">
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <label class="form-label d-block d-sm-none">Variant</label>
                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->variant_name }}" readonly class="form-control">
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <label class="form-label d-block d-sm-none">Quantity</label>
                                        <input type="text" value="{{ $pendingPfiItem->quantity }}" readonly class="form-control mb-3">
                                    </div>
                                    <div class="col-lg-2 col-md-2">
                                        <button type="button" class="btn btn-info btn-sm add-now" data-id="{{ $pendingPfiItem->id }}"
                                                data-action="ADD">
                                            Add Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        <br>
        <form action="{{ route('pfi.store') }}" id="form-create" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">PFI No</label>
                           <input type="text" class="form-control" autofocus name="pfi_reference_number" value="{{ old('pfi_reference_number') }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Released Date</label>
                            <input type="date" class="form-control" name="pfi_date">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" min="0" >
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">PFI Document</label>
                            <input type="file" id="file" class="form-control" name="file" accept="application/pdf">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label">Comment</label>
                            <textarea class="form-control" name="comment" rows="5" cols="25"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6" id="file-preview">
                    </div>
                </div>
                <input type="hidden" value="{{ request()->id }}" name="letter_of_indent_id" id="letter_of_indent_id">
                @if($approvedPfiItems->count() > 0)
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-dark">Finish</button>
                    </div>
                @endif
            </form>
    </div>
@endsection
@push('scripts')
    <script>
        const fileInputLicense = document.querySelector("#file");
        const previewFile = document.querySelector("#file-preview");
        fileInputLicense.addEventListener("change", function(event) {
            const files = event.target.files;
            while (previewFile.firstChild) {
                previewFile.removeChild(previewFile.firstChild);
            }
                const file = files[0];
                if (file.type.match("application/pdf"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const iframe = document.createElement("iframe");
                    iframe.src = objectUrl;
                    previewFile.appendChild(iframe);
                }
        });
       $('.remove').on('click',function(){
           let id = $(this).attr('data-id');
           let action = $(this).attr('data-action');
           pfi(id, action);
       })
       $('.add-now').on('click',function(){
           let id = $(this).attr('data-id');
           let action = $(this).attr('data-action');
           pfi(id, action);
       })
       function pfi(id, action) {
           let url = '{{ route('add_pfi') }}';
           $.ajax({
               type: "GET",
               url: url,
               dataType: "json",
               data: {
                   id: id,
                   action: action,
               },
               success:function () {
                   location.reload();
               }
           });
       }
       $("#form-create").validate({
           rules: {
               pfi_reference_number: {
                   required: true,
               },
               pfi_date: {
                   required: true,
               },
               amount: {
                   required: true,
               },
               file:{
                   required:true,
                   extension: 'pdf'
               },
               messages: {
                   file: {
                       extension: "Please upload valid pdf file"
                   }
               }
           }
       });
    </script>
@endpush


