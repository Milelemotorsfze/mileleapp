@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 400px;
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
                                    <div class="row ">
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
                                    <div class="row ">
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
                            <form action="{{ route('pfi.store') }}" id="form-create" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-xxl-8 col-lg-6 col-md-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Number</label>
                                                    <input type="number" class="form-control" id="pfi_reference_number" autofocus placeholder="Enter PFI Number"
                                                           name="pfi_reference_number" value="{{ old('pfi_reference_number') }}">
                                                    <span id="pfi-error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Vendor</label>
                                                    <select class="form-control" name="supplier_id" id="supplier-id" multiple >
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{$supplier->id}}" data-is-MMC="{{$supplier->is_MMC}}" data-is-AMS="{{$supplier->is_AMS}}" >
                                                                {{ $supplier->supplier }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Released Date</label>
                                                    <input type="date" class="form-control" name="pfi_date">
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
                                                    <input type="number" class="form-control pfi-amount" value="" readonly name="amount" min="0" placeholder="PFI Amount">
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
                                                    <textarea class="form-control" name="comment" rows="5" cols="25"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mmc-items-div" hidden>
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Delivery Location</label>
                                                    <input type="text" id="delivery-location" class="form-control" name="delivery_location" placeholder="Delivery Location">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mmc-items-div" hidden>
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Currency</label>
                                                    <select class="form-control" name="currency" id="currency" >
                                                        <option value="USD">USD</option>
                                                        <option value="EUR">EUR</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <div id="file-preview">
                                        </div>
                                    </div>
                                </div>
                                <div id="pfi-items-div" hidden>
                                    <hr>
                                    <div class="row">
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
                                                        <!-- <div class="col-lg-2 col-md-6">
                                                            <label  class="form-label">Model Year</label>
                                                        </div> -->
                                                        <div class="col-lg-2 col-md-6">
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
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                                                <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->model ?? '' }}" readonly class="form-control mb-2">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->sfx ?? '' }}" readonly class="form-control mb-2">
                                                            </div>
                                                            <!-- <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label>
                                                                <input type="text" value="{{ $approvedPfiItem->letterOfIndentItem->masterModel->model_year ?? '' }}" readonly class="form-control mb-2">
                                                            </div> -->
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>
                                                                <input type="text" value="{{ $approvedPfiItem->quantity }}" id="quantity-{{ $approvedPfiItem->id }}" readonly class="form-control mb-2">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>
                                                                <input type="number" min="0"  value="0" index="{{ $approvedPfiItem->id }}" name="unit_price[]" class="form-control mb-3 added-unit-prices"
                                                                 id="unit-price-{{ $approvedPfiItem->id}}">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label  d-lg-none d-xl-none d-xxl-none">Total Price</label>
                                                                <input type="number" value="0" min="0" readonly class="form-control mb-3" id="total-amount-{{ $approvedPfiItem->id }}">
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
                                    <div class="row">
                                        {{--                        @if($pendingPfiItems->count() > 0)--}}
                                        <p class="fw-bold font-size-16 mt-3">Approved Inventory</p>
                                        @foreach($pendingPfiItems as $value => $pendingPfiItem)
                                            <div class="d-flex">
                                                <div class="col-lg-12">
                                                    <div class="row mt-2" id="pending-row-{{$pendingPfiItem->id}}">
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-block d-sm-none">Model</label>
                                                            <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->model ?? ''}}" readonly class="form-control mb-2">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-block d-sm-none">SFX</label>
                                                            <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->sfx ?? ''}}" readonly class="form-control mb-2">
                                                        </div>
                                                        <!-- <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-block d-sm-none">Model Year</label>
                                                            <input type="text" value="{{ $pendingPfiItem->letterOfIndentItem->masterModel->variant->name ?? ''}}" readonly class="form-control">
                                                        </div> -->
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-block d-sm-none">Quantity</label>
                                                            <input type="text" value="{{ $pendingPfiItem->quantity }}" id="quantity-{{ $pendingPfiItem->id }}" readonly class="form-control mb-3">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-block d-sm-none">Unit Price</label>
                                                            <input type="number" value="0" index="{{ $pendingPfiItem->id }}" class="form-control mb-3 removed-unit-prices"  id="unit-price-{{ $pendingPfiItem->id }}">
                                                        </div>
                                                        <div class="col-lg-2 col-md-6">
                                                            <label class="form-label d-block d-sm-none">Total Price</label>
                                                            <input type="number" value="0" min="0" readonly class="form-control mb-3" id="total-amount-{{ $pendingPfiItem->id }}">
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

                                <input type="hidden" value="{{ request()->id }}" name="letter_of_indent_id" id="letter_of_indent_id">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit float-end" id="create-pfi">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <br>
            </div>
        @endif
        @endcan
    <input type="hidden" value="" id="added-pfi-count">

@endsection
@push('scripts')
    <script>
        $('#supplier-id').select2({
            placeholder: "Select Vendor",
            maximumSelectionLength: 1
        });
        $('.added-unit-prices').on('input', function(){
        
            calculatePfiAmount();   
        });
        $('form').on('submit', function(e){
            let addedPfiCount = $('#added-pfi-count').val();
            if(addedPfiCount <= 0) {
                e.preventDefault();
                alertify.confirm('Atleast One Variant item is Mandatory in PFI.').set({title:"Alert !"})
            }else {
                if($("#form-create").valid()) {
                    $('#form-create').submit();
                }else{
                    e.preventDefault();
                }
            }
        });

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
        function addpfi(id) {
            let supplier = $('#supplier-id').val();
            let action = 'ADD';
            let url = '{{ route('add_pfi') }}';
            let discount = $('#discount-'+id).val();
            let unitPrice = $('#unit-price-'+id).val();

            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    id: id,
                    supplier_id:supplier[0],
                    discount:discount,
                    unit_price:unitPrice,
                    action: action
                },
                success:function (response) {
                    // location.reload();
                    // console.log(response);
                    var itemsCount = response.approvedItems;
                    $('#added-pfi-count').val(itemsCount);

                    $('#pending-row-'+id).remove();
                    $('#approved').append('<div class="row mt-2" id="approved-row-'+ response.id+'"><div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label> ' +
                        '<input type="text" value="'+ response.letter_of_indent_item.master_model.model +'" readonly class="form-control mb-2"> </div>' +
                        '<div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">SFX</label> ' +
                        '<input type="text" value="'+ response.letter_of_indent_item.master_model.sfx +'" readonly class="form-control mb-2">' +
                        ' </div> <div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Quantity</label>'+
                        '<input type="text" value="'+ response.quantity +'" readonly id="quantity-'+ response.id +'" class="form-control mb-3"> </div>' +
                        '<div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Unit Price</label>'+
                        '<input type="number" value="'+ unitPrice +'" index="'+ response.id +'"  name="unit_price[]" class="form-control mb-3 added-unit-prices"  id="unit-price-'+ response.id +'"> </div>'+
                        ' <div class="col-lg-2 col-md-6"> <label class="form-label d-block d-sm-none">Total Amount</label>'+
                        '<input type="number" value="" min="0" id="total-amount-'+ response.id +'" class="form-control mb-3"> </div>'+
                        '<div class="col-lg-1 col-md-6"> ' +
                        '<button type="button" class="btn btn-danger btn-sm remove" onclick="removepfi('+ response.id +')" >Remove </button></div></div>'
                    );
                    calculatePfiAmount();
                }
            });
        }
        function removepfi(id) {

            let action = 'REMOVE';
            let supplier = $('#supplier-id').val();
            let url = '{{ route('add_pfi') }}';
            let addedPfiCount = $('#added-pfi-count').val();
            let discount = $('#discount-'+id).val();
            let unitPrice = $('#unit-price-'+id).val();

            if(addedPfiCount > 1) {
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        id: id,
                        supplier_id:supplier[0],
                        discount:discount,
                        unit_price:unitPrice,
                        action: action
                    },
                    success:function (response) {

                        var itemsCount = response.approvedItems;
                        $('#added-pfi-count').val(itemsCount);
                        
                        $('#approved-row-'+id).remove();
                        $('#pending').append(' <div class="row mt-2" id="pending-row-'+ response.id+'"><div class="col-lg-2 col-md-6">' +
                            ' <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label> ' +
                            '<input type="text" value="'+ response.letter_of_indent_item.master_model.model +'" readonly class="form-control mb-2"> </div>' +
                            '<div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label> ' +
                            '<input type="text" value="'+ response.letter_of_indent_item.master_model.sfx +'" readonly class="form-control mb-2">' +
                            ' </div> <div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Quantity</label>'+
                            '<input type="text" value="'+ response.quantity +'" readonly id="quantity-'+ response.id +'" class="form-control mb-3"> </div>' +
                            '<div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>'+
                            '<input type="text" value="'+ unitPrice +'" index="'+response.id +'" class="form-control mb-3 removed-unit-prices" id="unit-price-'+ response.id +'"> </div>'+
                            ' <div class="col-lg-2 col-md-6"> <label class="form-label d-lg-none d-xl-none d-xxl-none">Total Price</label>'+
                            '<input type="text" value="" min="0"  id="total-amount-'+ response.id +'" class="form-control mb-3"> </div>'+
                            '<div class="col-lg-1 col-md-6"> ' +
                            '<button type="button" class="btn btn-info btn-sm add-now" onclick="addpfi('+ response.id +')" >Add Pfi </button> </div></div>'
                        );

                       calculatePfiAmount();
                    }
                });
            }else{
                alertify.confirm('Atleast One item is Mandatory in PFI.You can not delete item.').set({title:"Alert !"})
            }
        }

        function calculatePfiAmount() {
            var sum = 0;
            $('.added-unit-prices').each(function() {
                var id = $(this).attr('index');  
                console.log(id);
                var quantity = $('#quantity-'+id).val();      
                var eachItemTotal = parseFloat(quantity) * parseFloat(this.value);
                console.log(eachItemTotal);
                console.log(quantity);
                $('#total-amount-'+id).val(eachItemTotal);

                sum = sum + eachItemTotal;
            });  
            $('.removed-unit-prices').each(function() {
                    var id = $(this).attr('index');  
                    console.log(id);
                    var quantity = $('#quantity-'+id).val();      
                    var eachItemTotal = parseFloat(quantity) * parseFloat(this.value);
                    console.log(eachItemTotal);
                    console.log(quantity);
                    $('#total-amount-'+id).val(eachItemTotal);
                       
            });
          
        
        }
          

        $('#pfi_reference_number').keyup(function(){

                $.ajax({
                    type:"POST",
                    async: false,
                    url: "/reference-number-unique-check", // script to validate in server side
                    data: {pfi_reference_number:  $('#pfi_reference_number').val()},
                    success: function(data) {

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
                   required:true,
                   extension: 'pdf'
               },
               supplier_id:{
                   required:true
               }
           },
       });
        $(document.body).on('select2:unselect', "#supplier-id", function (e) {
            $('#pfi-items-div').attr('hidden', true);
        });
        $(document.body).on('select2:select', "#supplier-id", function (e) {
            $('#pfi-items-div').attr('hidden',false);

            let supplier = $(this).val();
            let MMC = $(this).find('option:selected').attr("data-is-MMC");
            let letter_of_indent_id = $('#letter_of_indent_id').val();

            if(MMC == 1) {
                $('.mmc-items-div').attr('hidden', false);
            }else{
                $('.mmc-items-div').attr('hidden', true);
                $('#delivery-location').val('');
                $('#currency').val('USD');
            }
             
            if(supplier) {
                $.ajax({
                    type:"GET",
                    url: "{{ route('loi-item.unit-price') }}",
                    data: {
                        supplier_id:supplier[0],
                        letter_of_indent_id:letter_of_indent_id
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

    </script>
@endpush


