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
                                            {{ $letterOfIndent->client->name ?? '' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 col-md-6 col-lg-3 fw-bold">
                                            So Number :
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            @foreach($letterOfIndent->soNumbers as $key => $LoiSoNumber)
                                                {{ $LoiSoNumber->so_number }}
                                                @if(($key + 1) !== $letterOfIndent->soNumbers->count()) , @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xxl-4 col-lg-4 col-md-6">
                                    <div class="row mt-2">
                                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">
                                            Country :
                                        </div>
                                        <div class="col-sm-6">
                                            {{ $letterOfIndent->client->country->name ?? '' }}
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
                                        <div class="col-sm-6 col-md-6 col-lg-3 fw-bold">
                                            Dealers :
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6">
                                            {{ $letterOfIndent->dealers }}
                                        </div>
                                    </div>
{{--                                    <div class="row">--}}
{{--                                        <div class="col-sm-6 col-md-6 col-lg-4 fw-bold">--}}
{{--                                            Destination :--}}
{{--                                        </div>--}}
{{--                                        <div class="col-sm-6">--}}
{{--                                            {{ $letterOfIndent->destination }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
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
                                                    <input type="number" class="form-control pfi-amount" readonly name="amount" value="{{ old('amount', $pfi->amount) }}" min="0" placeholder="Enter Amount">
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

                                <div id="pfi-items-div" >
                                    <hr>
                                    <div class="row" id="added-pfi-row-div" >
                                        @if($pendingPfiItems->count() > 0 || $approvedPfiItems->count() > 0)
                                            <p class="fw-bold font-size-16 mt-3">Added PFI Items</p>

                                            <div class="d-flex d-none d-lg-block d-xl-block d-xxl-block">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label">Model</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label  class="form-label">SFX</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label  class="form-label">Model Year</label>
                                                        </div>
                                                        <div class="col-lg-1 col-md-6">
                                                            <label class="form-label">Quantity</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label">Unit Price</label>
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label">Total Price</label>
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
                                                            <input type="hidden" name="selectedIds[]" value="{{ $approvedPfiItem->id }}" id="selected-id-{{ $approvedPfiItem->id }}">
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->model ?? '' }}"
                                                                       id="model-{{ $approvedPfiItem->id }}" readonly class="form-control mb-2">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->sfx ?? '' }}"
                                                                       id="sfx-{{ $approvedPfiItem->id }}" readonly class="form-control mb-2">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label>
                                                                <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->model_year ?? '' }}"
                                                                       id="model-year-{{ $approvedPfiItem->id }}" readonly class="form-control mb-2">
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                <input type="text" value="{{ $approvedPfiItem->quantity }}" id="quantity-{{ $approvedPfiItem->id }}"
                                                                       readonly class="form-control mb-2">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>
                                                                <input type="number" min="0"  required value="{{ $approvedPfiItem->unit_price ?? 0 }}"  oninput="calculatePfiAmount()"
                                                                       index="{{ $approvedPfiItem->id }}" name="unit_price[]" class="form-control mb-3 added-unit-prices"
                                                                       id="unit-price-{{ $approvedPfiItem->id}}">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                    <?php $totalAmount = $approvedPfiItem->quantity * $approvedPfiItem->unit_price ?>
                                                                <label class="form-label  d-lg-none d-xl-none d-xxl-none">Total Price</label>
                                                                <input type="number" value="{{ $totalAmount }}" min="0" readonly class="form-control mb-3"
                                                                       id="total-amount-{{ $approvedPfiItem->id }}">
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none"></label>
                                                                <button type="button" class="btn btn-danger btn-sm remove" onclick="removepfi({{ $approvedPfiItem->id }})"  >
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
                                    <div class="row" id="pending-pfi-row-div">
                                        {{--                        @if($pendingPfiItems->count() > 0)--}}
                                        <p class="fw-bold font-size-16 mt-3">Pending Items to Add PFI </p>
                                        <div class="d-flex d-none d-lg-block d-xl-block d-xxl-block">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label">Model</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label  class="form-label">SFX</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label  class="form-label">Model Year</label>
                                                    </div>
                                                    <div class="col-lg-1 col-md-6">
                                                        <label class="form-label">Quantity</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label">Unit Price</label>
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label">Total Price</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($pendingPfiItems as $value => $pendingPfiItem)
                                            <div class="d-flex">
                                                <div class="col-lg-12">
                                                    <div class="row mt-2" id="pending-row-{{$pendingPfiItem->id}}">
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                            <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->model ?? ''}}"
                                                                   id="model-{{ $pendingPfiItem->id }}" readonly class="form-control mb-2">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                            <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->sfx ?? ''}}"
                                                                   id="sfx-{{ $pendingPfiItem->id }}" readonly class="form-control mb-2">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label>
                                                            <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->model_year ?? ''}}"
                                                                   id="model-year-{{ $pendingPfiItem->id }}"  readonly class="form-control">
                                                        </div>
                                                        <div class="col-lg-1 col-md-6">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                            <input type="text" value="{{ $pendingPfiItem->quantity }}" id="quantity-{{ $pendingPfiItem->id }}"
                                                                   readonly class="form-control mb-3">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>
                                                            <input type="number" value="{{ $pendingPfiItem->unit_price ?? 0}}" index="{{ $pendingPfiItem->id }}" oninput="calculateTotalAmount({{$pendingPfiItem->id}})" class="form-control mb-3 removed-unit-prices"
                                                                   id="unit-price-{{ $pendingPfiItem->id }}">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <?php $totalAmount = $pendingPfiItem->quantity * $pendingPfiItem->unit_price ?>
                                                            <label class="form-label d-lg-none d-xl-none d-xxl-none">Total Price</label>
                                                            <input type="number" value="{{ $totalAmount }}" min="0" readonly class="form-control mb-3" id="total-amount-{{ $pendingPfiItem->id }}">
                                                        </div>
                                                        <div class="col-lg-1 col-md-6">
                                                            <button type="button" class="btn btn-info btn-sm add-now" onclick="addpfi({{ $pendingPfiItem->id }})" >
                                                                Add Pfi
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        {{--                        @endif--}}
                                        <div id="pending">

                                        </div>
                                    </div>
                                </div>

{{--                                <input type="hidden" value="{{ request()->id }}" name="letter_of_indent_id" id="letter_of_indent_id">--}}
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
        showOrHideItemsDiv();

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

        $('#supplier-id').select2({
            placeholder: "Select Vendor",
            maximumSelectionLength: 1
        });

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
                supplier_id:{
                    required:true
                },
                "unit_price[]": {
                    required: true
                },
            },

        });
        $(document.body).on('select2:select', "#supplier-id", function (e) {
            $('#pfi-items-div').attr('hidden',false);

            let MMC = $(this).find('option:selected').attr("data-is-MMC");
            if(MMC == 1) {
                $('.mmc-items-div').attr('hidden', false);
            }else{
                $('.mmc-items-div').attr('hidden', true);
                $('#delivery-location').val('');
                $('#currency').val('USD');
            }

            let supplier = $(this).val();
            let letter_of_indent_id = '{{ $pfi->letter_of_indent_id }}';

            if(supplier) {
                $.ajax({
                    type:"GET",
                    url: "{{ route('loi-item.unit-price') }}",
                    data: {
                        supplier_id:supplier[0],
                        pfi_id: '{{ $pfi->id }}',
                        letter_of_indent_id:letter_of_indent_id,
                        action: 'EDIT'
                    },
                    success: function(data) {
                        var approvedItems = data.approvedItemUnitPrices;
                        var pendingItems = data.pendingItemUnitPrices;

                        jQuery.each(approvedItems, function(key,value){
                            $('#unit-price-'+key).val(value);
                        });
                        jQuery.each(pendingItems, function(key,value){
                            $('#unit-price-'+key).val(value);
                        });

                        calculatePfiAmount();
                    }
                });
            }
        });

        function showOrHideItemsDiv() {
            let totalIndex = $('#added-pfi-count').val();
            let availablependingItems = parseInt('{{ $pendingPfiItems->count() }}') + parseInt('{{ $approvedPfiItems->count() }}');
            if(totalIndex > 0) {
                $('#added-pfi-row-div').attr('hidden', false);
            }else{
                $('#added-pfi-row-div').attr('hidden', true);
            }
            if(availablependingItems == totalIndex) {
                $('#pending-pfi-row-div').attr('hidden', true);
            }else{
                $('#pending-pfi-row-div').attr('hidden', false);

            }
        }
        function addpfi(id) {
            let model = $('#model-'+id).val();
            let sfx = $('#sfx-'+id).val();
            let modelYear = $('#model-year-'+id).val();
            let quantity = $('#quantity-'+id).val();
            let unitPrice = $('#unit-price-'+id).val();

            let totalCount = $('#added-pfi-count').val();
            let latestCount = parseInt(totalCount) + 1;
            $('#added-pfi-count').val(latestCount);

            $('#pending-row-'+id).remove();
            $('#approved').append('<div class="row mt-2" id="approved-row-'+ id+'">' +
                '<input type="hidden" name="selectedIds[]" value="'+ id +'" id="selected-id-'+ id +'"> <div class="col-lg-2 col-md-6">' +
                ' <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label> ' +
                '<input type="text" value="'+ model +'" id="model-'+ id+'" readonly class="form-control mb-2"> </div>' +
                '<div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label> ' +
                '<input type="text" value="'+ sfx +'" readonly id="sfx-'+ id+'" class="form-control mb-2">' +
                ' </div> <div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label> ' +
                '<input type="text" value="'+ modelYear +'" id="model-year-'+ id+'" readonly class="form-control mb-2">' +
                ' </div> <div class="col-lg-1 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>'+
                '<input type="text" value="'+ quantity +'" readonly id="quantity-'+ id +'" class="form-control mb-3"> </div>' +
                '<div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>'+
                '<input type="number" required value="'+ unitPrice +'" index="'+ id +'"  oninput="calculatePfiAmount()"   ' +
                'name="unit_price[]" class="form-control mb-3 added-unit-prices"  id="unit-price-'+ id +'"> </div>'+
                ' <div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Total Amount</label>'+
                '<input type="number" value="" min="0" readonly id="total-amount-'+ id +'" class="form-control mb-3"> </div>'+
                '<div class="col-lg-1 col-md-6"> ' +
                '<button type="button" class="btn btn-danger btn-sm remove" onclick="removepfi('+ id +')" >Remove </button></div></div>'
            );
            calculatePfiAmount();
            showOrHideItemsDiv();
        }
        function removepfi(id) {
            let addedPfiCount = $('#added-pfi-count').val();
            let model = $('#model-'+id).val();
            let sfx = $('#sfx-'+id).val();
            let modelYear = $('#model-year-'+id).val();
            let quantity = $('#quantity-'+id).val();
            let unitPrice = $('#unit-price-'+id).val();

            if(addedPfiCount > 1) {
                let totalCount = $('#added-pfi-count').val();
                let latestCount = parseInt(totalCount) - 1;
                $('#added-pfi-count').val(latestCount);

                $('#approved-row-'+id).remove();
                $('#pending').append(' <div class="row mt-2" id="pending-row-'+ id+'"><div class="col-lg-2 col-md-6">' +
                    ' <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label> ' +
                    '<input type="text" value="'+ model +'" id="model-'+ id+'" readonly class="form-control mb-2"> </div>' +
                    '<div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label> ' +
                    '<input type="text" value="'+ sfx +'" id="sfx-'+ id+'" readonly class="form-control mb-2">' +
                    ' </div><div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label> ' +
                    '<input type="text" value="'+ modelYear +'" id="model-year-'+ id+'" readonly class="form-control mb-2">' +
                    ' </div> <div class="col-lg-1 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>'+
                    '<input type="text" value="'+ quantity +'" readonly id="quantity-'+ id +'" class="form-control mb-3"> </div>' +
                    '<div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>'+
                    '<input type="text" value="'+ unitPrice +'" oninput="calculateTotalAmount('+ id +')" index="'+ id +'" class="form-control mb-3 removed-unit-prices" id="unit-price-'+ id +'"> </div>'+
                    ' <div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Total Price</label>'+
                    '<input type="text" value="" min="0" readonly  id="total-amount-'+ id +'" class="form-control mb-3"> </div>'+
                    '<div class="col-lg-1 col-md-6"> ' +
                    '<button type="button" class="btn btn-info btn-sm add-now" onclick="addpfi('+ id +')" >Add Pfi </button> </div></div>'
                );

                calculatePfiAmount();
                showOrHideItemsDiv();
            }else{
                alertify.confirm('Atleast One item is Mandatory in PFI.You can not delete item.').set({title:"Alert !"})
            }
        }

        function calculatePfiAmount() {
            var sum = 0;
            $('.added-unit-prices').each(function() {
                var id = $(this).attr('index');
                var quantity = $('#quantity-'+id).val();
                var eachItemTotal = parseFloat(quantity) * parseFloat(this.value);

                $('#total-amount-'+id).val(eachItemTotal);
                sum = sum + eachItemTotal;
            });

            $('.pfi-amount').val(sum);
            $('.removed-unit-prices').each(function() {
                var id = $(this).attr('index');
                var quantity = $('#quantity-'+id).val();
                var eachItemTotal = parseFloat(quantity) * parseFloat(this.value);

                $('#total-amount-'+id).val(eachItemTotal);
            });
        }

        function calculateTotalAmount(id) {
            var quantity = $('#quantity-'+id).val();
            var unitPrice = $('#unit-price-'+id).val();
            var eachItemTotal = parseFloat(quantity) * parseFloat(unitPrice);
            $('#total-amount-'+id).val(eachItemTotal);
        }
        $(document.body).on('select2:unselect', "#supplier-id", function (e) {
            $('#pfi-items-div').attr('hidden', true);
        });
    </script>
@endpush


