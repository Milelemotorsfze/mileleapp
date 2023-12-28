@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 300px;
            margin-bottom: 10px;
        }
    </style>
    @can('PFI-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
        @endphp
        @if ($hasPermission)
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">LOI Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-xxl-4 col-lg-4 col-md-6">
                                    <div class="row mt-2">
                                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                                            Customer :
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            {{ $letterOfIndent->customer->name ?? '' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                                            Dealers :
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            {{ $letterOfIndent->dealers }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-6 col-lg-3 fw-bold">
                                            So Number :
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            {{ $letterOfIndent->so_number }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xxl-4 col-lg-4 col-md-6">
                                    <div class="row mt-2">
                                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">
                                            Perefered Location :
                                        </div>
                                        <div class="col-sm-6">
                                            {{ $letterOfIndent->prefered_location }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">
                                            LOI Category :
                                        </div>
                                        <div class="col-sm-6">
                                            {{ $letterOfIndent->category }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row ">
                                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                                            LOI Date :
                                        </div>
                                        <div class="col-sm-6 ">
                                            {{ Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">
                                            Destination :
                                        </div>
                                        <div class="col-sm-6">
                                            {{ $letterOfIndent->destination }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">PFI Details</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pfi.update', $pfi->id) }}" id="form-create" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xxl-8 col-lg-6 col-md-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Number</label>
                                                    <input type="text" class="form-control" id="pfi_reference_number" autofocus placeholder="Enter PFI Number"
                                                           name="pfi_reference_number" value="{{ old('pfi_reference_number', $pfi->pfi_reference_number) }}">
                                                    <span id="pfi-error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Vendor</label>
                                                    <select class="form-control" name="supplier_id" id="supplier-id" multiple >
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{$supplier->id}}" data-is-MMC="{{$supplier->is_MMC}}" data-is-AMS="{{$supplier->is_AMS}}"
                                                            {{ $supplier->id == $pfi->supplier_id ? "selected" : '' }}>
                                                                {{ $supplier->supplier }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Released Date</label>
                                                    <input type="date" class="form-control" value="{{ old('comment', $pfi->pfi_date) }}" name="pfi_date">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Released Amount</label>
                                                    <input type="number" min="0" class="form-control" name="released_amount" placeholder="Released Amount">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Amount</label>
                                                    <input type="number" class="form-control" name="amount" value="{{ old('amount', $pfi->amount) }}" min="0" placeholder="Enter Amount">
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Document</label>
                                                    <input type="file" id="file" class="form-control" name="file" accept="application/pdf">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Comment</label>
                                                    <textarea class="form-control" name="comment" rows="5" cols="25">{{ old('comment', $pfi->comment) }}</textarea>
                                                </div>
                                            </div>
                                            @if($pfi->supplier->is_MMC == true)
                                                <div class="col-lg-4 col-md-6 mmc-items-div" >
                                                    <div class="mb-3">
                                                        <label for="choices-single-default" class="form-label">Delivery Location</label>
                                                        <input type="text" id="delivery-location" class="form-control" name="delivery_location" placeholder="Delivery Location">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 mmc-items-div">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Currency</label>
                                                    <select class="form-control" name="currency" id="currency" >
                                                        <option value="USD">USD</option>
                                                        <option value="EUR">EUR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-lg-8 col-md-6" id="file-preview">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <iframe src="{{ url('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign) }}" ></iframe>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @if($pendingPfiItems->count() > 0 || $approvedPfiItems->count() > 0)
                                        <p class="fw-bold font-size-16 mt-3">Added PFI Items</p>
                                        <div class="d-flex d-none d-lg-block d-xl-block d-xxl-block">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                                        <label class="form-label">Model</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                                        <label  class="form-label">SFX</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                                        <label  class="form-label">Model Year</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                                        <label class="form-label">Quantity</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-3 col-sm-12">
                                                        <label class="form-label">Unit Price</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($approvedPfiItems->count() > 0)
                                        @foreach($approvedPfiItems as $value => $approvedPfiItem)
                                            <div class="d-flex">
                                                <div class="col-lg-12">
                                                    <div class="row mt-2" id="approved-row-{{$approvedPfiItem->id}}">
                                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                            <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->model ?? '' }}" readonly class="form-control mb-2">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                            <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->sfx ?? '' }}" readonly class="form-control mb-2">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label>
                                                            <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->model_year ?? '' }}" readonly class="form-control mb-2">
                                                        </div>

                                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                            <input type="text" value="{{ $approvedPfiItem->quantity }}" readonly class="form-control mb-2">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>
                                                            <input type="text" value="" readonly class="form-control">
                                                        </div>
                                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none"></label>
                                                            <button type="button" class="btn btn-danger btn-sm remove" onclick="removepfi({{ $approvedPfiItem->id }})" >
                                                               Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div id="approved">

                                    </div>
                                </div>
                                <div class="row">
                                    {{--                    @if($pendingPfiItems->count() > 0)--}}
                                    <p class="fw-bold font-size-16 mt-3">Approved Inventory</p>
                                    @foreach($pendingPfiItems as $value => $pendingPfiItem)
                                        <div class="d-flex">
                                            <div class="col-lg-12">
                                                <div class="row mt-2" id="pending-row-{{$pendingPfiItem->id}}">
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <label class="form-label d-block d-sm-none">Model</label>
                                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->model ?? ''}}" readonly class="form-control mb-2">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <label class="form-label d-block d-sm-none">SFX</label>
                                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->sfx ?? ''}}" readonly class="form-control mb-2">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <label class="form-label d-block d-sm-none">Model Year</label>
                                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->model_year ?? ''}}" readonly class="form-control mb-2">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <label class="form-label d-block d-sm-none">Quantity</label>
                                                        <input type="text" value="{{ $pendingPfiItem->quantity }}" readonly class="form-control mb-3">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                                        <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>
                                                        <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->loi_item_unit_price }}" readonly class="form-control">
                                                    </div>
                                                    <div class="col-lg-1 col-md-6 col-sm-12">
                                                        <button type="button" class="btn btn-info btn-sm add-now" onclick="addpfi({{ $pendingPfiItem->id }})" >
                                                            Add Pfi
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    {{--                    @endif--}}
                                </div>
                                <div id="pending">

                                </div>

                                <input type="hidden" value="{{ request()->id }}" name="letter_of_indent_id" id="letter_of_indent_id">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit float-end">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        @endif
    @endcan
    <input type="hidden" value="" id="added-pfi-count">
@endsection
@push('scripts')
    <script>
        var itemCount = '{{ $approvedPfiItems->count() }}';
        $('#added-pfi-count').val(itemCount);
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
        {{--$('.remove').on('click',function(){--}}
        {{--    let id = $(this).attr('data-id');--}}
        {{--    let action = $(this).attr('data-action');--}}
        {{--    let count = '{{ $approvedPfiItems->count() }}';--}}
        {{--    if(count > 1){--}}
        {{--        pfi(id, action);--}}
        {{--    }else {--}}
        {{--        alertify.confirm('Ooops! You can not delete this item.One variant item is mandatory for PFI.').set({title:"Delete Item?"});--}}
        {{--    }--}}
        {{--})--}}
        {{--$('.add-now').on('click',function(){--}}
        {{--    let id = $(this).attr('data-id');--}}
        {{--    let action = $(this).attr('data-action');--}}
        {{--    pfi(id, action);--}}
        {{--})--}}
        $('#supplier-id').select2({
            placeholder: "Select Vendor",
            maximumSelectionLength: 1
        });
        function addpfi(id) {
            let action = 'ADD';
            let url = '{{ route('add_pfi') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    id: id,
                    action: action,
                    pfi_id: '{{ $pfi->id }}'
                },
                success:function (response) {
                    // location.reload();
                    console.log(response);
                    var itemsCount = response.approvedItems;
                    $('#added-pfi-count').val(itemsCount);

                    $('#pending-row-'+id).remove();
                    $('#approved').append('<div class="row mt-2" id="approved-row-'+ response.id+'"><div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label> ' +
                        '<input type="text" value="'+ response.letter_of_indent_item.master_model.model +'" readonly class="form-control mb-2"> </div>' +
                        '<div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">SFX</label> ' +
                        '<input type="text" value="'+ response.letter_of_indent_item.master_model.sfx +'" readonly class="form-control mb-2">' +
                        ' </div><div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Model Year</label>' +
                        '<input type="text" value="'+ response.letter_of_indent_item.master_model.model_year +'" readonly class="form-control mb-2">' +
                        '</div>  <div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Quantity</label>'+
                        '<input type="text" value="'+ response.quantity +'" readonly class="form-control mb-3"> </div>' +
                        '<div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Unit Price</label> ' +
                        '<input type="text" value="" readonly class="form-control"> </div>'+
                        '<div class="col-lg-1 col-md-6"> ' +
                        '<button type="button" class="btn btn-danger btn-sm remove" onclick="removepfi('+ response.id +')" >Remove </button></div></div>'
                    );
                }
            });
        }
        function removepfi(id) {
            let addedPfiCount = $('#added-pfi-count').val();
            let action = 'REMOVE';

            if(addedPfiCount > 1) {
                let url = '{{ route('add_pfi') }}';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        id: id,
                        action: action,
                        pfi_id: '{{ $pfi->id }}'
                    },
                    success:function (response) {
                        var itemsCount = response.approvedItems;
                        $('#added-pfi-count').val(itemsCount);

                        $('#approved-row-'+id).remove();
                        $('#pending').append(' <div class="row mt-2" id="pending-row-'+ response.id+'"><div class="col-lg-2 col-md-6">' +
                            ' <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label> ' +
                            '<input type="text" value="'+ response.letter_of_indent_item.master_model.model +'" readonly class="form-control mb-2"> </div>' +
                            '<div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">SFX</label> ' +
                            '<input type="text" value="'+ response.letter_of_indent_item.master_model.sfx +'" readonly class="form-control mb-2">' +
                            ' </div> <div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Model Year</label>' +
                            '<input type="text" value="'+ response.letter_of_indent_item.master_model.model_year +'" readonly class="form-control mb-2">' +
                            '</div> <div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Quantity</label>'+
                            '<input type="text" value="'+ response.quantity +'" readonly class="form-control mb-3"> </div>' +
                            '<div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Unit Price</label> ' +
                            '<input type="text" value="" readonly class="form-control"> </div>'+
                            '<div class="col-lg-1 col-md-6"> ' +
                            '<button type="button" class="btn btn-info btn-sm add-now" onclick="addpfi('+ response.id +')" >Add Pfi </button> </div></div>'
                        );
                    }
                });
           }else{
                alertify.confirm('Atleast One Item is Mandatory in PFI.You can not delete item.').set({title:"Alert !"})
            }

        }
        $('#pfi_reference_number').keyup(function(){

                $.ajax({
                    type:"POST",
                    async: false,
                    url: "/reference-number-unique-check", // script to validate in server side
                    data: {pfi_reference_number:  $('#pfi_reference_number').val()},
                    success: function(data) {
                        console.log(data);
                        if(data == true) {
                            $('#pfi_reference_number').addClass('is-invalid');
                            $('#pfi-error').text("PFI Number already existing");
                            $('.btn-submit').attr('disabled', true);
                        }else{
                            $('#pfi_reference_number').removeClass('is-invalid');
                            $('#pfi-error').text(" ");
                            $('.btn-submit').attr('disabled', false);

                        }
                    }
                });
            },
        );

        $("#form-create").validate({
            ignore: [],
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
                    extension: 'pdf'
                },
            },

        });
        $(document.body).on('select2:select', "#supplier-id", function (e) {
            let MMC = $(this).find('option:selected').attr("data-is-MMC");
            if(MMC == 1) {
                $('.mmc-items-div').attr('hidden', false);
            }else{
                $('.mmc-items-div').attr('hidden', true);
                $('#delivery-location').val('');
                $('#currency').val('USD');
            }
        });
    </script>
@endpush


