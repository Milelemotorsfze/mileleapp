@extends('layouts.main')
@section('content')
    <style>
        iframe{
            height: 400px;
            margin-bottom: 10px;
        }
        .widthinput{
            height: 32px!important;
        }
        .overlay
        {
            position: fixed; /* Positioning and size */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(128,128,128,0.5); /* color */
            display: none; /* making it hidden by default */
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
                                                    <input type="text" class="form-control widthinput" id="pfi_reference_number" autofocus placeholder="Enter PFI Number"
                                                           name="pfi_reference_number" value="{{ old('pfi_reference_number') }}">
                                                    <span id="pfi-error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Vendor</label>
                                                    <select class="form-control widthinput" name="supplier_id" id="supplier-id" multiple >
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
                                                    <label for="choices-single-default" class="form-label">PFI Amount</label>
                                                    <input type="number" class="form-control widthinput pfi-amount" value="" readonly name="amount" min="0" placeholder="PFI Amount">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Document</label>
                                                    <input type="file" id="file" class="form-control widthinput" name="file" accept="application/pdf">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Document</label>
                                                    <input type="file" id="file" class="form-control widthinput" name="file" accept="application/pdf">
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-4 col-md-6 mmc-items-div" hidden>
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Delivery Location</label>
                                                    <input type="text" id="delivery-location" class="form-control widthinput" name="delivery_location" placeholder="Delivery Location">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 mmc-items-div" hidden>
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Currency</label>
                                                    <select class="form-control widthinput" name="currency" id="currency" >
                                                        <option value="USD">USD</option>
                                                        <option value="EUR">EUR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">Comment</label>
                                                    <textarea class="form-control" name="comment" rows="5" cols="25"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-xxl-4 col-lg-6 col-md-12">
                                        <div id="file-preview">
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Add PFI Item Details</h4>
                                    </div>
                                    <div class="card-body">
                                    <div class="row">
                                    <div class="d-flex d-none d-lg-block d-xl-block d-xxl-block">
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-6">
                                                    <label class="form-label text-center">Model</label>
                                                </div>
                                                <div class="col-lg-1 col-md-6">
                                                    <label  class="form-label">SFX</label>
                                                </div>
                                                <div class="col-lg-2 col-md-6">
                                                    <label  class="form-label">Model Line</label>
                                                </div>
                                                <div class="col-lg-1 col-md-6">
                                                    <label  class="form-label">PFI Quantity</label>
                                                </div>
                                                <div class="col-lg-1 col-md-6">
                                                    <label class="form-label">Remaining Quantity</label>
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
                                                <input type="hidden" name="loi_item_ids[]" class="loi_item_ids" value="" >
                                                <div class="d-flex">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none text-center">Model</label>
                                                                <select class="form-select widthinput text-dark models mb-2" index="1"  id="model-1" multiple
                                                                        name="models[]">
                                                                    <option value="" >Select Model</option>
                                                                        @foreach($masterModels as $model)
                                                                            <option value="{{ $model->model }}" >{{ $model->model }}</option>
                                                                        @endforeach
                                                                    </select>  
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                                                <select class="form-control text-dark widthinput sfx mb-2" index="1" multiple name="sfx[]" id="sfx-1"  >
                                                                    <option value="" ></option>
                                                                </select>
                                                            
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Line</label>
                                                                <input type="text" readonly value=""
                                                                    id="model-line-1" class="form-control widthinput mb-2">
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">PFI Quantity</label>
                                                                <input type="number" min="0" max="" 
                                                                id="pfi-quantity-1" oninput=calculateTotalAmount(1) placeholder="0"  name="pfi_quantities[]"
                                                                    class="form-control mb-2 widthinput pfi-quantities" value="0">
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Reamining Quantity</label>
                                                                <input type="text" value="" id="remaining-quantity-1"
                                                                    readonly class="form-control mb-2 widthinput">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label d-lg-none d-xl-none d-xxl-none">Unit Price</label>
                                                                <input type="number" min="0"  required placeholder="0"
                                                                    index="1" name="unit_price[]" oninput=calculateTotalAmount(1) class="form-control widthinput mb-2 unit-prices"
                                                                    id="unit-price-1">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <label class="form-label  d-lg-none d-xl-none d-xxl-none">Total Price</label>
                                                                <input type="number" min="0" readonly class="form-control mb-2 widthinput"
                                                                    id="total-amount-1">
                                                            </div>
                                                         
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                 </div>
                               
                                    </div>
                                </div>

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
        <div class="overlay"></div>
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

          let loi_id = '{{ request()->id }}';
        $('#supplier-id').select2({
            placeholder: "Select Vendor",
            maximumSelectionLength: 1
        });
        $('.models').select2({
            placeholder : 'Select Model',
            maximumSelectionLength: 1
        }).on('change', function() {
            let model = $(this).val();
            let index = $(this).attr('index');
            getSfx(model, index);           
        });
        $('.sfx').select2({
            placeholder : 'Select SFX',
            maximumSelectionLength: 1
        });


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
                supplier_id:{
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
                "pfi_quantities[]": {
                    required: true
                },
                file: {
                    required:true,
                    extension: "pdf",
                    maxsize:5242880 
                },
               
            },
                
            messages: {
                file: {
                    extension: "Please upload file format (pdf)"
                },
                
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


        function getSfx(model, index) {
            $.ajax({
                url:"{{route('demand.get-sfx')}}",
                type: "GET",
                data:
                    {
                        model: model,
                    },
                dataType : 'json',
                success: function(data) {
                    $('#sfx-'+index).empty();
                    jQuery.each(data, function(key,value){
                    $('#sfx-'+index).append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        }
        // check the pfi number is unique within the year
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
            });

        // get the unit price while supplier select

        $(document.body).on('select2:select', "#supplier-id", function (e) {
            // $('#pfi-items-div').attr('hidden',false);
            let loiItems = [];
             $('.loi_item_ids').map(function(){
                loiItems.push($(this).val());
                });
            let supplier = $(this).val();
            let MMC = $(this).find('option:selected').attr("data-is-MMC");

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
                        letter_of_indent_id:loi_id,
                        loi_item_ids:loiItems,
                        action: 'CREATE'
                    },
                    success: function(data) {
                        jQuery.each(data, function(key,value){
                            $('#unit-price-'+key).val(value);
                            calculateTotalAmount(key);
                        });

                    }
                });
            }
        });

        $(document.body).on('select2:unselect', "#supplier-id", function (e) {
            $('.unit-prices').val(0);
        });
     
        function calculatePfiAmount() {
            var sum = 0;
            $('.unit-prices').each(function() {
                var id = $(this).attr('index');
                var quantity = $('#pfi-quantity-'+id).val();
                var eachItemTotal = parseFloat(quantity) * parseFloat(this.value);
                $('#total-amount-'+id).val(eachItemTotal);
                sum = sum + eachItemTotal;
            });

            $('.pfi-amount').val(sum);
           
        }
        
        function calculateTotalAmount(id) {
            var quantity = $('#pfi-quantity-'+id).val();
            var unitPrice = $('#unit-price-'+id).val();
            var eachItemTotal = parseFloat(quantity) * parseFloat(unitPrice);
            $('#total-amount-'+id).val(eachItemTotal);

            calculatePfiAmount();
        }

        $('form').on('submit', function(e){
            $('.overlay').show();
            
            let quantitySum = 0;
            $('.pfi-quantities').each(function() {
                var quantity = $(this).val();
                quantitySum = parseFloat(quantitySum) + parseFloat(quantity);
                
            });
            console.log(quantitySum);
            if(quantitySum <= 0) {
                $('.overlay').hide();
                e.preventDefault();
                alertify.confirm('Atleast one vehicle item is mandatory in PFI.').set({title:"Alert !"})
            }else {
                if($("#form-create").valid()) {
                    $('#form-create').submit();
                }else{
                    $('.overlay').hide();
                    e.preventDefault();
                }
            }
        });
       
    </script>
@endpush