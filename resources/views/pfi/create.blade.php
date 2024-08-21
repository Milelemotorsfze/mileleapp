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
        .row{
            margin-right: 0px;
            padding-right: 0px;
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
                                                    <label for="choices-single-default" class="form-label">Customer</label>
                                                    <select class="form-control widthinput" name="client_id" id="client_id" multiple >
                                                        @foreach($customers as $customer)
                                                            <option value="{{$customer->id}}" >
                                                                {{ $customer->name }}
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
                                        <div id="pfi-items" >
                                            <div class="row pfi-items-parent-div" id="row-1">
                                                <div class="row pfi-child-item-div-1" id="parentItem" index="1" >
                                                    <div class="row chilItems child-item-1" id="row-1-item-0">
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label text-center">Model</label>
                                                        <select class="form-select widthinput text-dark models mb-2 border-bold"  required
                                                        index="1" item="0" id="model-1-item-0" multiple name="PfiItem[1][model][0]">
                                                            <option value="" >Select Model</option>
                                                                @foreach($masterModels as $model)
                                                                    <option value="{{ $model->model }}" >{{ $model->model }}</option>
                                                                @endforeach
                                                            </select>  
                                                    </div>
                                                    <div class="col-lg-1 col-md-6">
                                                        <label class="form-label ">SFX</label>
                                                        <select class="form-control text-dark widthinput sfx mb-2" required
                                                            multiple name="PfiItem[1][sfx][0]" index="1" item="0" id="sfx-1-item-0">
                                                            <option value="" ></option>
                                                        </select>
                                                    
                                                    </div>
                                                    <div class="col-lg-1 col-md-6">
                                                        <label class="form-label"> LOI Code</label>
                                                        <select class="form-control text-dark widthinput loi-items mb-2" required multiple
                                                        name="PfiItem[1][loi_item][0]" index="1" item="0" id="loi-item-1-item-0" 
                                                        placeholder="LOI Code" >
                                                            <option value="" ></option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-1 col-md-6">
                                                        <label class="form-label ">PFI QTY</label>
                                                        <input type="number" min="0" max="" 
                                                        oninput=calculateTotalAmount(1) placeholder="0" required name="PfiItem[1][pfi_quantity][0]"
                                                            class="form-control mb-2 widthinput pfi-quantities" value="0" placeholder="0"
                                                            index="1" item="0" id="pfi-quantity-1-item-0">
                                                    </div>
                                                    <div class="col-lg-1 col-md-6">
                                                        <label class="form-label">Unused QTY</label>
                                                        <input type="number" value=""
                                                            readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                                            index="1" item="0" id="remaining-quantity-1-item-0">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label ">Unit Price</label>
                                                        <input type="number" min="0"  required placeholder="0" name="PfiItem[1][unit_price][0]" oninput=calculateTotalAmount(1) 
                                                            class="form-control widthinput mb-2 unit-prices" placeholder="Unit price" 
                                                            index="1" item="0" id="unit-price-1-item-0">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label">Total Price</label>
                                                        <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts"
                                                        placeholder="Total Amount" id="total-amount-1-item-0" index="1" item="0">
                                                        <input type="hidden" name="master_model_ids[]" class="master-model-ids" id="master-model-id-1-item-0">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6 col-sm-12" style="margin-top: 30px;">
                                                        <a class="btn btn-primary btn-sm add-more" index="1" item="0"
                                                        title="Add Child PFI Items"> <i class="fas fa-plus"> Add More </i> 
                                                            </a>
                                                        <a class="btn btn-sm btn-danger removePFIButton" id="remove-btn-1" data-index="1"> 
                                                            <i class="fas fa-trash-alt"></i> </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn btn-info btn-sm add-pfi-btn float-end" >
                                            <i class="fas fa-plus"></i> Add LOI Item
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
    <script type="text/javascript">
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
        $('#client_id').select2({
            placeholder: "Select Customer",
            maximumSelectionLength: 1
        });
        $('#model-1-item-0').select2({
            placeholder : 'Select Model',
            maximumSelectionLength: 1
        });
        $('.sfx').select2({
            placeholder : 'Select SFX',
            maximumSelectionLength: 1
        });
        $('.loi-items').select2({
            placeholder : 'Select Code',
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
                client_id:{
                    required:true
                },
                "models[]": {
                    required: true
                },
                "sfx[]": {
                    required: true
                },
                
                file: {
                    required:true,
                    extension: "pdf|png|jpg|jpeg|svg",
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

        // $('form').on('submit', function(e){
        //     $('.overlay').show();
            
        //     let quantitySum = 0;
        //     $('.pfi-quantities').each(function() {
        //         var quantity = $(this).val();
        //         quantitySum = parseFloat(quantitySum) + parseFloat(quantity);
                
        //     });
        //     console.log(quantitySum);
        //     if(quantitySum <= 0) {
        //         $('.overlay').hide();
        //         e.preventDefault();
        //         alertify.confirm('Atleast one vehicle item is mandatory in PFI.').set({title:"Alert !"})
        //     }else {
        //         if($("#form-create").valid()) {
        //             $('#form-create').submit();
        //         }else{
        //             $('.overlay').hide();
        //             e.preventDefault();
        //         }
        //     }
        // });


        $(document.body).on('select2:select', ".models", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#model-'+index+'-item-'+childIndex +'-error').remove();
            getSfx(index, childIndex);
        });
        $(document.body).on('select2:unselect', ".models", function (e) {
           let index = $(this).attr('index');
           let childIndex = $(this).attr('item');
           $('#sfx-'+index+'-item-'+childIndex).empty();
           $('#loi-item-'+index+'-item-'+childIndex).empty();
           $('#remaining-quantity-'+index+'-item-'+childIndex).val("");
           $('#pfi-quantity-'+index+'-item-'+childIndex).val("");
           $('#unit-price-'+index+'-item-'+childIndex).val("");
           $('#total-amount-'+index+'-item-'+childIndex).val("");
           $('#master-model-id-'+index+'-item-'+childIndex).val("");
        //    var model = e.params.data.id;
          
        //    appendSFX(index,model,sfx[0]);
        //    appendModel(index,model);
        //    enableDealer();
      
       });
        $(document.body).on('select2:select', ".sfx", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#sfx-'+index+'-item-'+childIndex +'-error').remove();
            getLOIItemCode(index, childIndex);

            // var value = e.params.data.text;
            // hideSFX(index, value);
           
        });
        $(document.body).on('select2:unselect', ".sfx", function (e) {

            $('#master-model-id-'+index+'-item-'+childIndex).val("");
           
        });
        $(document.body).on('click', ".add-more", function (e) {
            let index = $(this).attr('index');
            let item =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length;
          
             $(".pfi-child-item-div-"+index).append(`
                     <div class="row chilItems child-item-${index}" id="row-${index}-item-${item}">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <select class="form-select widthinput text-dark models" multiple name="PfiItem[${index}][model][${item}]"
                                index="${index}" item="${item}" id="model-${index}-item-${item}" required autofocus>
                               
                            </select>
                            @error('model')
                            <span>
                                <strong >{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                                <select class="form-select widthinput text-dark sfx" required multiple name="PfiItem[${index}][sfx][${item}]" 
                                index="${index}" item="${item}" id="sfx-${index}-item-${item}" >
                                <option value="">Select SFX</option>
                            </select>
                            @error('sfx')
                            <div role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <select class="form-control text-dark widthinput loi-items mb-2" required index="${index}" multiple
                                    name="PfiItem[${index}][loi_item][${item}]" item="${item}" id="loi-item-${index}-item-${item}">
                                    <option value="" ></option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" min="0" max="" placeholder="0" required oninput=calculateTotalAmount(${index}) placeholder="0"  
                                name="PfiItem[${index}][pfi_quantity][${item}]" class="form-control mb-2 widthinput pfi-quantities" value="0"
                                index="${index}" item="${item}" id="pfi-quantity-${index}-item-${item}">
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                    index="${index}" item="${item}" id="remaining-quantity-${index}-item-${item}">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0"  required placeholder="0" index="${index}" name="PfiItem[${index}][unit_price][${item}]" 
                                oninput=calculateTotalAmount(${index}) class="form-control widthinput mb-2 unit-prices"
                                    id="unit-price-${index}-item-${item}" item="${item}" placeholder="Unit price">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" index="${index}"
                                    id="total-amount-${index}-item-${item}" item="${item}" placeholder="Total Price">
                                <input type="hidden" name="master_model_ids[]" class="master-model-ids" id="master-model-id-${index}-item-${item}">
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12">
                                <a class="btn btn-sm btn-danger removePFIItemButton" id="remove-btn-${index}-item-${item}" item="${item}" index="${index}" > 
                                <i class="fas fa-trash-alt"></i> </a>
                            </div>
                        </div>
                    </div>
                   
                    `);
                    let parentSfx = $('#sfx-'+index+'-item-0').val();
                    // populate child models if parent have value
                    if(parentSfx[0]) {
                        let type = 'add-new';
                        getModels(index,item,type); 

                    }else{
                       $('#model-'+index+'-item-'+item).select2({
                         placeholder: 'Select Model',
                         maximumSelectionLength: 1
                     });
                    }
                 
                    
                    $('#sfx-'+index+'-item-'+item).select2({
                        placeholder: 'Select SFX',
                        maximumSelectionLength: 1
                    });
                    $('#loi-item-'+index+'-item-'+item).select2({
                        placeholder: 'Select Code',
                        maximumSelectionLength: 1
                    });
        });
      
        $('.add-pfi-btn').click(function() {
            var index = $("#pfi-items").find(".pfi-items-parent-div").length + 1;
           
           var newRow = `
                <div class="row pfi-items-parent-div" id="row-${index}" >
                     <div class="row pr-0 mr-0 pfi-child-item-div-${index}" id="parentItem" >
                       <div class="row chilItems child-item-${index}" id="row-${index}-item-0">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <select class="form-select widthinput text-dark models" required multiple name="PfiItem[${index}][model][0]"
                                index="${index}" item="0" id="model-${index}-item-0" autofocus>
                                <option value="" >Select Model</option>
                                @foreach($masterModels as $model)
                            <option value="{{ $model->model }}">{{ $model->model }}</option>
                            @endforeach
                            </select>
                            @error('model')
                            <span>
                                <strong >{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                                <select class="form-select widthinput text-dark sfx" multiple required name="PfiItem[${index}][sfx][0]" 
                                index="${index}" item="0" id="sfx-${index}-item-0" >
                                <option value="">Select SFX</option>
                            </select>
                            @error('sfx')
                            <div role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <select class="form-control text-dark widthinput loi-items mb-2" required index="${index}" multiple
                                    name="PfiItem[${index}][loi_item][0]" item="0" id="loi-item-${index}-item-0">
                                    <option value="" ></option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" min="0" max="" placeholder="PFI Qty" placeholder="0"  required
                                name="PfiItem[${index}][pfi_quantity][0]" class="form-control mb-2 widthinput pfi-quantities" value=""
                                index="${index}" item="0" id="pfi-quantity-${index}-item-0" placeholder="0">
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                    index="${index}" item="0" id="remaining-quantity-${index}-item-0">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0"  required placeholder="0" index="${index}" name="PfiItem[${index}][unit_price][0]" 
                                class="form-control widthinput mb-2 unit-prices"
                                    id="unit-price-${index}-item-0" item="0" placeholder="Unit price">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" index="${index}"
                                    id="total-amount-${index}-item-0" item="0" placeholder="Total Price">
                                <input type="hidden" name="master_model_ids[]" class="master-model-ids" id="master-model-id-${index}-item-0">
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <a class="btn btn-primary btn-sm add-more" 
                                 index="${index}" item="0"
                                title="Add Child PFI Items">  <i class="fas fa-plus"> Add More </i> 
                                </a>
                                <a class="btn btn-sm btn-danger removePFIButton" index="${index}" > 
                                <i class="fas fa-trash-alt"></i> </a>
                            </div>
                        </div>
                     </div>
                     </div>
                   
                    </div>
                    `;
                        $('#pfi-items').append(newRow);

                        $('#model-'+index+'-item-0').select2({
                            placeholder: 'Select Model',
                            maximumSelectionLength: 1
                        });
                        $('#sfx-'+index+'-item-0').select2({
                            placeholder: 'Select SFX',
                            maximumSelectionLength: 1
                        });
                        $('#loi-item-'+index+'-item-0').select2({
                        placeholder: 'Select Code',
                        maximumSelectionLength: 1
                    });
                  
        });
        $(document.body).on('click', ".removePFIItemButton", function (e) {
            var index = $(this).attr('index');
            var rowCount =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
            var childIndex = $(this).attr('item');
              
            $(this).closest('#row-' + index + '-item-' + childIndex).remove();
            ReIndex(index);
                 
                
         });
        $(document.body).on('click', ".removePFIButton", function (e) {
            var rowCount = $("#pfi-items").find(".pfi-items-parent-div").length;
            if(rowCount > 1) {

                var indexNumber = $(this).attr('index');
                // populate value later
                // var modelLine = $('#model-line-'+indexNumber).val()
                // var model = $('#model-'+indexNumber).val();
                // var sfx = $('#sfx-'+indexNumber).val();
               
                // if(model[0]) {
                //     appendModel(indexNumber,model[0]);
                // }
                // if(sfx[0]) {
                //     appendSFX(indexNumber,model[0],sfx[0]);
                // }

                $(this).closest('#row-'+indexNumber).remove();

                $('.pfi-items-parent-div').each(function(j){
                    var index = +j + +1;

                    $(this).attr('id', 'row-'+index);
                    $(this).find('#parentItem').attr('class', 'row pr-0 mr-0 pfi-child-item-div-'+index);
                    $(this).find('.chilItems').attr('class', 'row chilItems child-item-'+index);
                    $(this).find('.removeButton').attr('index', index);
                    
                    // child Rows ReIndex
                  
                    var rowCount =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length;
                    ReIndex(index);
               
            });

            }else{
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one PFI Item Required',function (e) {
                }).set({title:"Can't Remove PFI Item"})
            }
        })
        function ReIndex(index) {
            let i = 0;
            $('.child-item-'+index).each(function (i) {

                $(this).attr('id', 'row-'+index+'-item-'+ i);
                $(this).find('.models').attr('name', 'PfiItem['+ index +'][model]['+ i +']');
                $(this).find('.models').attr('item',i);
                $(this).find('.models').attr('id','model-'+index+'-item-'+i);

                $(this).find('.sfx').attr('name', 'PfiItem['+ index +'][sfx]['+ i +']');
                $(this).find('.sfx').attr('item',i);
                $(this).find('.sfx').attr('id','sfx-'+index+'-item-'+i);
                $(this).find('.master-model-ids').attr('id','master-model-id-'+index+'-item-'+i);

                $(this).find('.loi-items').attr('name', 'PfiItem['+ index +'][loi_item]['+ i +']');
                $(this).find('.loi-items').attr('item',i);
                $(this).find('.loi-items').attr('id','loi-item-'+index+'-item-'+i);

                $(this).find('.pfi-quantities').attr('name', 'PfiItem['+ index +'][pfi_quantity]['+ i +']');
                $(this).find('.pfi-quantities').attr('item',i);
                $(this).find('.pfi-quantities').attr('id','pfi-quantity-'+index+'-item-'+i);

                $(this).find('.remaining-quantities').attr('item',i);
                $(this).find('.remaining-quantities').attr('id','remaining-quantity-'+index+'-item-'+i);

                $(this).find('.unit-prices').attr('name', 'PfiItem['+ index +'][unit_price]['+ i +']');
                $(this).find('.unit-prices').attr('item',i);
                $(this).find('.unit-prices').attr('id','unit-price-'+index+'-item-'+i);

                $(this).find('.total-amounts').attr('item',i);
                $(this).find('.total-amounts').attr('id','total-amount-'+index+'-item-'+i);
                $(this).find('.removePFIItemButton').attr('id','remove-button-'+index+'-item-'+i);
                $(this).find('.removePFIItemButton').attr('item',i);

                $(this).find('.models').attr('index', index);
                $(this).find('.sfx').attr('index', index);
                $(this).find('.loi-items').attr('index', index);
                $(this).find('.pfi-quantities').attr('index', index);
                $(this).find('.remaining-quantities').attr('index', index);
                $(this).find('.unit_prices').attr('index', index);
                $(this).find('.add-more').attr('index', index);
                $('#model-'+index+'-item-'+i).select2
                ({
                    placeholder: 'Select Model',
                    maximumSelectionLength:1,
                });
                $('#sfx-'+index+'-item-'+i).select2
                ({
                    placeholder: 'Select SFX',
                    maximumSelectionLength:1,
                });


            });
        }
        
        function getSfx(index,childIndex) {
            $('.overlay').show();
         
            let model = $('#model-'+index+'-item-'+childIndex).val();
            let url = '{{ route('demand.get-sfx') }}';
            var rowCount =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
            var selectedModelIds = [];
            for(let i=0; i<=rowCount; i++)
            {
                var eachSelectedModelId = $('#master-model-id-'+index+'-item-'+i).val();
                if(eachSelectedModelId) {
                    selectedModelIds.push(eachSelectedModelId);
                }
            }
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
                    module: 'PFI',
                    selectedModelIds:selectedModelIds
                },
                success:function (data) {              
                    $('#sfx-'+index+'-item-'+childIndex).empty();
                    $('#sfx-'+index+'-item-'+childIndex).html('<option value=""> Select SFX </option>');                  
                    jQuery.each(data, function(key,value){
                        $('#sfx-'+index+'-item-'+childIndex).append('<option value="'+ value +'">'+ value +'</option>');
                    });
                    
                    $('.overlay').hide();
                  
                }
            });           
       }
       function getLOIItemCode(index,childIndex) {
            $('.overlay').show();
            let customer = $('#client_id').val();
            let model = $('#model-'+index+'-item-'+childIndex).val();
            let sfx = $('#sfx-'+index+'-item-'+childIndex).val();
            let url = '{{ route('loi-item-code') }}';

                $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
                    sfx:sfx[0],
                    client_id:customer[0]
                },
                success:function (data) {
                    console.log(data);
                    let codes = data.codes;
                    $('#loi-item-'+index+'-item-'+childIndex).empty();
                    $('#loi-item-'+index+'-item-'+childIndex).html('<option value=""> Select Code </option>');                      
                    jQuery.each(codes, function(key,value){
                        $('#loi-item-'+index+'-item-'+childIndex).append('<option value="'+ value.id +'">'+ value.code +'</option>');
                    });
                    $('#master-model-id-'+index+'-item-'+childIndex).val(data.master_model_id);
                   
                    $('.overlay').hide();
                }
            });
            
       }
       function getModels(index,item,type) {
            $('.overlay').show();
            let parentModel = $('#model-'+index+'-item-0').val();
            let parentSfx = $('#sfx-'+index+'-item-0').val();
            var rowCount =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
            var selectedModelIds = [];
            for(let i=0; i<=rowCount; i++)
            {
                var eachSelectedModelId = $('#master-model-id-'+index+'-item-'+i).val();

                if(eachSelectedModelId) {
                    selectedModelIds.push(eachSelectedModelId);
                }
            }
            $.ajax({
                url:"{{route('pfi-item.master-models')}}",
                type: "GET",
                data:
                    {
                        model: parentModel[0],
                        sfx:parentSfx[0],
                        selectedModelIds:selectedModelIds
                    },
                dataType : 'json',
                success: function(data) {
                    console.log(data);
                    var size = data.length;
                  
                        let modelDropdownData   = [];
                        $.each(data,function(key,value)
                        {
                            modelDropdownData.push
                            ({
                                id: value.model,
                                text: value.model
                            });
                        });
                        if(type == 'add-new') {
                            $('#model-'+index+'-item-'+item).html("");
                            $('#model-'+index+'-item-'+item).select2({
                                placeholder: 'Select Model',
                                data: modelDropdownData,
                                maximumSelectionLength: 1,
                            });
                        }else{
                            for(let i=1; i<=rowCount; i++)
                            {
                                $('#model-'+index+'-item-'+i).html("");
                                $('#model-'+index+'-item-'+i).select2({
                                    placeholder: 'Select Model',
                                    data: modelDropdownData,
                                    maximumSelectionLength: 1,
                                });
                            }
                        }
                    $('.overlay').hide();
                }
            });
           
       }
    </script>
@endpush