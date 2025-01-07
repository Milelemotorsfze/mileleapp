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
       .error{
        color:red;
       }
    </style>
    @can('PFI-create')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-create');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">New PFI Details</h4>
               
                <a  class="btn btn-sm btn-info float-end mr-2" href="{{ route('pfi.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                @can('LOI-list')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-primary float-end" style="margin-right:5px;" title="Model-SFX Detail View of LOI Items" href="{{ route('letter-of-indent-items.index') }}" >
                        <i class="fa fa-table" ></i> LOI List </a>
                    @endif
                @endcan
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
                                                    <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">PFI Number</label>
                                                    <input type="text" class="form-control widthinput" id="pfi_reference_number" autofocus placeholder="Enter PFI Number"
                                                           name="pfi_reference_number" value="{{ old('pfi_reference_number') }}">
                                                    <span id="pfi-error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">PFI Date</label>
                                                    <input type="date" class="form-control widthinput" value="{{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}"
                                                     name="pfi_date">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">Vendor</label>
                                                    <select class="form-control widthinput" name="supplier_id" id="supplier-id" multiple >
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{$supplier->id}}" {{ $supplier->supplier == 'AMS' ? 'selected' : '' }} data-is-MMC="{{$supplier->is_MMC}}" data-is-AMS="{{$supplier->is_AMS}}" >
                                                                {{ $supplier->supplier }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <span class="error">* </span>
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
                                                    <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">Country</label>
                                                    <select class="form-control widthinput" name="country_id" id="country_id" multiple >
                                                        
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
                                                    <input type="file" id="file" class="form-control widthinput" name="file">
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
                                <div class="alert alert-success m-2 existing-pfi-details" role="alert" hidden >
                                   
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Add PFI Item Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div id="pfi-items" >
                                            <div class="row  pfi-items-parent-div" id="row-1">
                                                <div class="row m-0 pfi-child-item-div-1" id="parentItem" index="1" >
                                                    <div class="row chilItems child-item-1" id="row-1-item-0">
                                                    <div class="col-lg-2 col-md-6">
                                                        <span class="error">* </span>
                                                        <label class="form-label text-center">Model</label>
                                                        <select class="form-select widthinput text-dark models mb-2 border-bold"  required
                                                        index="1" item="0" id="model-1-item-0" multiple name="PfiItem[1][model]">
                                                            <option value="" >Select Model</option>
                                                                @foreach($masterModels as $model)
                                                                    <option value="{{ $model->model }}" >{{ $model->model }}</option>
                                                                @endforeach
                                                            </select>  
                                                    </div>
                                                    <div class="col-lg-1 col-md-6">
                                                        <span class="error">* </span>
                                                        <label class="form-label ">SFX</label>
                                                        <select class="form-control text-dark widthinput sfx mb-2" required
                                                            multiple name="PfiItem[1][sfx]" index="1" item="0" id="sfx-1-item-0">
                                                            <option value="" ></option>
                                                        </select>
                                                    
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                         <span class="error">* </span>
                                                        <label class="form-label ">PFI QTY</label>
                                                        <input type="number" min="1" required name="PfiItem[1][parent_pfi_quantity]" readonly placeholder="PFI Quantity"
                                                            class="form-control mb-2 widthinput parent-pfi-quantities" value="0"
                                                            index="1" item="0" id="pfi-quantity-1-item-0">
                                                    </div>
                                                    
                                                    <div class="col-lg-2 col-md-6">
                                                        <span class="error">* </span>
                                                        <label class="form-label ">Unit Price</label>
                                                        <input type="number" min="0"  required placeholder="Unit Price" name="PfiItem[1][unit_price]" oninput=calculateTotalAmount(1,0) 
                                                            class="form-control widthinput mb-2 unit-prices" placeholder="Unit price" 
                                                            index="1" item="0" id="unit-price-1-item-0">
                                                    </div>
                                                    <div class="col-lg-2 col-md-6">
                                                        <label class="form-label">Total Price</label>
                                                        <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts"
                                                        placeholder="Total Amount" id="total-amount-1-item-0" index="1" item="0">
                                                        <input type="hidden" class="master-model-ids" id="master-model-id-1-item-0">
                                                    </div>
                                                    <div class="col-lg-1 col-md-6 col-sm-12" style="margin-top: 30px;">
                                                        <a class="btn btn-sm btn-danger removePFIButton" id="remove-btn-1" index="1"> 
                                                        <i class="fas fa-trash-alt"></i> </a>
                                                        <a class="btn btn-primary btn-sm add-more disabled" id="add-more-1" index="1" item="0"
                                                        title="Add Child PFI Items" > <i class="fas fa-plus"> </i> 
                                                            </a>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn btn-info btn-sm add-pfi-btn float-end mt-2" >
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
                else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFile.appendChild(image);
                }
        });

        $('#supplier-id').select2({
            placeholder: "Select Vendor",
            maximumSelectionLength: 1
        });
        $('#client_id').select2({
            placeholder: "Select Customer",
            maximumSelectionLength: 1
        });
        $('#country_id').select2({
            placeholder: "Select Country",
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
                amount: {
                    required: true,
                },
                pfi_date: {
                    required: true,
                },               
                supplier_id:{
                    required:true
                },
                client_id:{
                    required:true
                },
                country_id:{
                    required:true
                },
                "models[]": {
                    required: true
                },
                "sfx[]": {
                    required: true
                },
                
                file: {
                    required: function(element) {
                        return $("#supplier-id").find('option:selected').attr("data-is-MMC") == 1;
                    },
                    extension: "pdf|png|jpg|jpeg|svg",
                    maxsize:5242880 
                },
               
            },
                
            messages: {
                file: {
                    extension: "Please upload file format (pdf,png,jpg,jpeg,svg)"
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

        function checkRemainingQty(index,childIndex) {
      
        var loiItems = [];
        $('.loi-items').each(function() {
                var value = $(this).val();
                loiItems.push(value);
        });
      
      }
      $('.btn-submit').click(function (e) {
            e.preventDefault();
           let formValid = true;
              // get all loi items Ids
               var loiItems = $('.loi-items').map(function() {
                    return $(this).val();
                }).get();
                // find duplicate vaules and its ids
                var duplicates = loiItems.filter(function(value, index, self) {
                    return self.indexOf(value) !== index && value.trim() !== '';
                });

                jQuery.each(duplicates, function(key,value){
                    var pfiQty = 0;
                    var remainingQty = 0;
                    let loiItem = 0;
                    $('.loi-items').each(function() {
                    // Check if the selected value exists in the current select element
                        if ($(this).find('option:selected').filter(function() {
                            return $(this).val() == value;
                        }).length > 0) {
                            var index = $(this).attr('index');
                            var childIndex = $(this).attr('item');
                            remainingQty = $('#remaining-quantity-'+index+'-item-'+childIndex).val();
                            currentLOIItemId = 'loi-item-'+index+'-item-'+childIndex;
                            loiItem = $('#' + currentLOIItemId + ' option:selected').text();
                            let currentItemPfiQty = $('#pfi-quantity-'+index+'-item-'+childIndex).val();
                            pfiQty = parseFloat(pfiQty) + parseFloat(currentItemPfiQty);
                        }
                    });
                    if(pfiQty > remainingQty ) {
                         formValid = false;
                         e.preventDefault();
                        alertify.confirm('Total pfi quantity of '+loiItem+' should be less than remaining quantity ( '+remainingQty+' ) ',function (e) {
                        }).set({title:"Invalid Data"});
                        return false;
                    }
                });

                selectedModelIds = [];
                var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
               
                for(let i=1; i<= parentIndex; i++)
                {
                    var eachSelectedModelId = $('#master-model-id-'+i+'-item-0').val();
                    if(eachSelectedModelId) {
                        selectedModelIds.push(eachSelectedModelId);
                    }
                }        

                let url = '{{ route('pfi.get-pfi-brand') }}';
                 // check each parent model for toyota PFI or other brand
                 $.ajax({
                    type:"GET",
                    url: url, 
                    data: {
                        master_model_ids: selectedModelIds
                    },
                    success: function(data) {
                        if(data == true) {
                            if(formValid == true) {
                                if($("#form-create").valid()) {
                                    $('#form-create').unbind('submit').submit();
                                }
                            }
                        }else{
                            e.preventDefault();
                            formValid = false;
                            alertify.confirm('You are selected non-toyota and toyota Brands together which is not allowed!');
                        }
                    }
                });
        });

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
           
            let MMC = $(this).find('option:selected').attr("data-is-MMC");

            if(MMC == 1) {
                $('.mmc-items-div').attr('hidden', false);
            }else{
                $('.mmc-items-div').attr('hidden', true);
                $('#delivery-location').val('');
                $('#currency').val('USD');
            }
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            for(let i=1; i<=parentIndex;i++) 
            {
                getMasterModelId(i)              
            }
        });

        $(document.body).on('select2:unselect', "#supplier-id", function (e) {
            $('.unit-prices').val(0);
           
        });
     
        function calculatePfiAmount() {
            var sum = 0;
            $('.unit-prices').each(function() {
                var index = $(this).attr('index');
                var childIndex = $(this).attr('item');
                var quantity = $('#pfi-quantity-'+index+'-item-'+childIndex).val();
                var eachItemTotal = parseFloat(quantity) * parseFloat(this.value);
                $('#total-amount-'+index+'-item-'+childIndex).val(eachItemTotal);
                sum = sum + eachItemTotal;
            });

            $('.pfi-amount').val(sum);
           
        }
      
      
        $(document.body).on('input', ".parent-pfi-quantities", function (e) {
            var index = $(this).attr('index');
            var sum = 0;
            var quantity = $('#pfi-quantity-'+index+'-item-0').val();
            var unitPrice = $('#unit-price-'+index+'-item-0').val();
            var eachItemTotal = parseFloat(quantity) * parseFloat(unitPrice);
            $('#total-amount-'+index+'-item-0').val(eachItemTotal);
            sum = sum + eachItemTotal;
              calculatePfiAmount();
        });
        
        function calculateTotalAmount(index,childIndex) {
                let totalPfiQty = 0;
                let totalIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                for(let j=1; j<=totalIndex;j++) 
                {
                    let pfiQty = $('#pfi-quantity-'+index+'-item-'+j).val(); 
                   totalPfiQty = parseFloat(totalPfiQty) + parseFloat(pfiQty);             
                }
                if(totalIndex > 0) {
                    $('#pfi-quantity-'+index+'-item-0').val(totalPfiQty);
                }
               
            var unitPrice = $('#unit-price-'+index+'-item-0').val();
            var eachItemTotal = parseFloat(totalPfiQty) * parseFloat(unitPrice);
            $('#total-amount-'+index+'-item-0').val(eachItemTotal);

            calculatePfiAmount();
        }

        ///// start new code ////
      
        $(document.body).on('select2:select', "#client_id", function (e) {
          
            $('#client_id-error').remove();
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            for(let i=1; i<=parentIndex;i++) 
            {
                resetData();
                let type = 'all';
                getModels(i,0,type);
                enableOrDisableAddMoreButton(i);

                let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=0; j<=childIndex;j++) 
                {
                    getLOIItemCode(i,j);
                }
            }
            getCustomerCountries()
        });
        $(document.body).on('select2:select', "#country_id", function (e) {
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
                    
            for(let i=1; i<=parentIndex;i++) 
            {
                enableOrDisableAddMoreButton(i);
            }

        });
        $(document.body).on('select2:unselect', "#country_id", function (e) {
            
            let data =  e.params.data.id;
            let isDataAvailable = false;
           // chcek any item selcted 
           var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;

            for(let i=1; i<=parentIndex;i++) 
            {
                let model = $('#model-'+i+'-item-0').val();
                let sfx = $('#sfx-'+i+'-item-0').val();

                if(model.length > 0 || sfx.length > 0 ){
                     isDataAvailable = true;
                        break;
                                                 
                    } 
            }
            if(isDataAvailable == true) {
                var confirm = alertify.confirm('While unselectiong this option the entire customer pfi items data will be reset to empty!',function (e) {
                    if (e) {
                        resetData();  
                                            
                    }
                    }).set({title:"Are You Sure ?"}).set('oncancel', function(closeEvent){
                        $("#country_id").val(data).trigger('change');
                        
                        }); 
            }else{
                resetData(); 
            }

        });
        $(document.body).on('select2:unselect', "#client_id", function (e) {
            
            let data =  e.params.data.id;
            let isData = false;

           // chcek any item selcted 
           var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;

            for(let i=1; i<=parentIndex;i++) 
            {
                let model = $('#model-'+i+'-item-0').val();
                let sfx = $('#sfx-'+i+'-item-0').val();
                if(model.length > 0 || sfx.length > 0 )
                    {
                        isData = true;
                         break;

                    }                  
            }

            if(isData == true) {
                var confirm = alertify.confirm('While unselectiong this option the entire customer pfi items data will be reset to empty!',function (e) {
                    if (e) {
                        resetData();  
                                            
                    }
                    }).set({title:"Are You Sure ?"}).set('oncancel', function(closeEvent){
                        $("#client_id").val(data).trigger('change');
                        
                        }); 
            }else{
                resetData(); 
            }

        });
        function resetData(){
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
             // country unselect if client id unselect
            $('#country_id').prop("selectedIndex", -1).trigger("change");          
                    
            for(let i=1; i<=parentIndex;i++) 
            {
                enableOrDisableAddMoreButton(i);
              
                $("#model-"+i+"-item-0").prop("selectedIndex", -1).trigger("change");
                $("#sfx-"+i+"-item-0").empty();
                $("#unit-price-"+i+"-item-0").val("");
                $("#total-amount-"+i+"-item-0").val("");
                $("#pfi-quantity-"+i+"-item-0").val(0);
                let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=1; j<=childIndex;j++) 
                {
                    $("#loi-item-"+i+"-item-"+j).empty();
                    $("#pfi-quantity-"+i+"-item-"+j).val("");
                    $("#remaining-quantity-"+i+"-item-"+j).val("");
                    $('#pfi-quantity-'+i+'-item-'+j).removeAttr("max");
                    $('#master-model-id-'+i+'-item-'+j).val("");
                }
            }
        }

        $(document.body).on('select2:select', ".models", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#model-'+index+'-item-'+childIndex +'-error').remove();
            getSfx(index, childIndex);

        });
        $(document.body).on('select2:unselect', ".models", function (e) {
           let index = $(this).attr('index');
           let childIndex = $(this).attr('item');
           var model = e.params.data.id;
                appendModel(index,model);
                var sfx = $('#sfx-'+index+'-item-0').val();
                appendSFX(index,model,sfx[0]);
                $('#sfx-'+index+'-item-0').empty();
                $('#master-model-id-'+index+'-item-0').val("");

                let totalIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                for(let j=1; j<= totalIndex;j++) 
                {                   
                    resetRowData(index,j); 
                }
                enableOrDisableAddMoreButton(index);
       });

       function resetRowData(index,childIndex) {
            // parent row data remove
           $('#pfi-quantity-'+index+'-item-0').val(0);
           $('#unit-price-'+index+'-item-0').val("");
           $('#total-amount-'+index+'-item-0').val("");
          
           $('#loi-item-'+index+'-item-'+childIndex).empty();
           $('#remaining-quantity-'+index+'-item-'+childIndex).val("");
           $('#pfi-quantity-'+index+'-item-'+childIndex).val("");
           $('#pfi-quantity-'+index+'-item-'+childIndex).removeClass('is-invalid');
           $('#pfi-quantity-'+index+'-item-'+childIndex).removeAttr("max");
       }
        $(document.body).on('select2:select', ".sfx", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#sfx-'+index+'-item-'+childIndex +'-error').remove();
            let sfx = $('#sfx-'+index+'-item-0').val();
                
            hideSFX(index, sfx);
            let totalchildIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                for(let j=1; j<=totalchildIndex;j++) 
                {
                    getLOIItemCode(index, j);
                }
            getMasterModelId(index);
            enableOrDisableAddMoreButton(index);
        });
        $(document.body).on('select2:unselect', ".sfx", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            let model = $('#model-'+index+'-item-0').val();
            var value = e.params.data.id;
               // if unselected sfx is in the parent row append corresponding model in every parent line items
                appendSFX(index,model[0],value);
                let totalIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                 $('#master-model-id-'+index+'-item-0').val("");

                for(let j=1; j<= totalIndex;j++) 
                {
                    resetRowData(index,j); 
                }
                enableOrDisableAddMoreButton(index);
        });
        $(document.body).on('select2:select', ".loi-items", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#loi-item-'+index+'-item-'+childIndex +'-error').remove();
            var value = e.params.data.id;
            getLOIItemDetails(index,childIndex);
            hideLOIItemCode(index,childIndex,value);
        });
        $(document.body).on('select2:unselect', ".loi-items", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            var id = e.params.data.id;
            var text = e.params.data.text;
            appendLOIItemCode(index,childIndex,id,text);

            $('#remaining-quantity-'+index+'-item-'+childIndex).val("");
            $('#unit-price-'+index+'-item-'+childIndex).val("");
            $('#total-amount-'+index+'-item-'+childIndex).val("");
            $('#pfi-quantity-'+index+'-item-'+childIndex).val("");
            $('#pfi-quantity-'+index+'-item-'+childIndex).removeAttr("max");
            $('#pfi-quantity-'+index+'-item-'+childIndex).removeClass('is-invalid');
        });
        function hideLOIItemCode(index,childIndex,value) {
            let totalRowIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
            for(let j=1; j<=totalRowIndex;j++) 
            {
                var currentId = 'loi-item-'+index+'-item-'+j;
                var selectedId = 'loi-item-'+index+'-item-'+childIndex;
                if(selectedId != currentId ) {
                    var currentId = 'loi-item-'+index+'-item-'+j;
                    $('#' + currentId + ' option[value=' + value + ']').detach(); 
                }
            }
        }
        function appendLOIItemCode(index,childIndex,id,text)
        {
            var selectedId = 'loi-item-'+index+'-item-'+childIndex;
            let rowIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
            for(let j=1; j<=rowIndex;j++) 
            {             
                var currentId = 'loi-item-'+index+'-item-'+j;                     
                if(selectedId != currentId) {
                    $('#loi-item-'+index+'-item-'+j).append($('<option>', {value: id, text : text}));    
                }
            }
        }
        $(document.body).on('click', ".add-more", function (e) {
            let index = $(this).attr('index');

            let unitPrice =  $("#unit-price-"+index+"-item-0").val();
            let item =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length;

                $(".pfi-child-item-div-"+index).append(`
                     <div class="row pt-2 m-0 chilItems child-item-${index}" id="row-${index}-item-${item}" style="background-color:#eaeaea">
                            <div class="col-lg-2 "></div>
                            <div class="col-lg-2 col-md-6">
                                <select class="form-control text-dark widthinput loi-items mb-2" required index="${index}" multiple
                                    name="PfiItem[${index}][loi_item][${item}]" item="${item}" id="loi-item-${index}-item-${item}">
                                    <option value="" ></option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="1" placeholder="PFI Quantity" required oninput=calculateTotalAmount(${index},${item}) 
                                name="PfiItem[${index}][pfi_quantity][${item}]" class="form-control mb-2 widthinput pfi-quantities" 
                                index="${index}" item="${item}" id="pfi-quantity-${index}-item-${item}">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="Unused Quantity"
                                    index="${index}" item="${item}" id="remaining-quantity-${index}-item-${item}">
                            </div>
                           
                            <div class="col-lg-1 col-md-6 col-sm-12">
                                <a class="btn btn-sm btn-danger removePFIItemButton" id="remove-btn-${index}-item-${item}" item="${item}" index="${index}" > 
                                <i class="fas fa-trash-alt"></i> </a>
                            </div>
                        </div>
                    </div>
                   
                    `);
                    let parentSfx = $('#sfx-'+index+'-item-0').val();
                   
                    $('#loi-item-'+index+'-item-'+item).select2({
                        placeholder: 'Select Code',
                        maximumSelectionLength: 1
                    });
                    getLOIItemCode(index,item);
        });
      
        $('.add-pfi-btn').click(function() {
            var index = $("#pfi-items").find(".pfi-items-parent-div").length + 1;
           
           var newRow = `
                <div class="row pfi-items-parent-div" id="row-${index}" >
                     <div class="row pr-0 m-0 pfi-child-item-div-${index}" id="parentItem" >
                       <div class="row pt-2 chilItems child-item-${index}" id="row-${index}-item-0">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <select class="form-select widthinput text-dark models" required multiple name="PfiItem[${index}][model]"
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
                                <select class="form-select widthinput text-dark sfx" multiple required name="PfiItem[${index}][sfx]" 
                                index="${index}" item="0" id="sfx-${index}-item-0" >
                                <option value="">Select SFX</option>
                            </select>
                            @error('sfx')
                            <div role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                            </div>
                            
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="1" placeholder="PFI Quantity" readonly required  value="0"
                                name="PfiItem[${index}][parent_pfi_quantity]" class="form-control mb-2 widthinput parent-pfi-quantities" 
                                index="${index}" item="0" id="pfi-quantity-${index}-item-0">
                            </div>
                            
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0"  required index="${index}" name="PfiItem[${index}][unit_price]" 
                                class="form-control widthinput mb-2 unit-prices"  oninput=calculateTotalAmount(${index},0)
                                    id="unit-price-${index}-item-0" item="0" placeholder="Unit price">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" index="${index}"
                                    id="total-amount-${index}-item-0" item="0" placeholder="Total Price">
                                <input type="hidden" class="master-model-ids" id="master-model-id-${index}-item-0">
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12">
                             <a class="btn btn-sm btn-danger removePFIButton" index="${index}" > 
                                <i class="fas fa-trash-alt"></i> </a>
                                <a class="btn btn-primary btn-sm add-more disabled" 
                                 index="${index}" item="0" id="add-more-${index}"
                                title="Add Child PFI Items">  <i class="fas fa-plus"> </i> 
                                </a>
                               
                            </div>
                        </div>
                     </div>
                     </div>
                   
                    </div>
                    `;
                $('#pfi-items').append(newRow);
                let type = 'add-new';
                getModels(index,0,type);
                
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
            var childIndex = $(this).attr('item');
            var loiItemId = $('#loi-item-'+index+'-item-'+childIndex).val();
            let currentLOIItemId = 'loi-item-'+index+'-item-'+childIndex;
            let loiItemText = $('#' + currentLOIItemId + ' option:selected').text();
            if(loiItemId[0]) {
                appendLOIItemCode(index,childIndex,loiItemId,loiItemText);
            }
            $(this).closest('#row-' + index + '-item-' + childIndex).remove();
            ReIndex(index);
            calculatePfiAmount();
         });
        $(document.body).on('click', ".removePFIButton", function (e) {
            var rowCount = $("#pfi-items").find(".pfi-items-parent-div").length;
            if(rowCount > 1) {

                var indexNumber = $(this).attr('index');
                var sfx = $('#sfx-'+indexNumber+'-item-0').val();
                var model = $('#model-'+indexNumber+'-item-0').val();
                if(model[0]) {
                    appendModel(indexNumber,model[0]);
                }
                if(sfx[0]) {
                    appendSFX(indexNumber,model[0],sfx[0]);
                }

                $(this).closest('#row-'+indexNumber).remove();

                $('.pfi-items-parent-div').each(function(j){
                    var index = +j + +1;

                    $(this).attr('id', 'row-'+index);
                    $(this).find('#parentItem').attr('class', 'row pr-0 m-0 pfi-child-item-div-'+index);
                    $(this).find('.chilItems').attr('class', 'row pt-2 chilItems child-item-'+index);
                    $(this).find('.removePFIButton').attr('index', index);
                    $(this).find('.add-more').attr('index', index);
                    $(this).find('.add-more').attr('id', 'add-more-'+index);
                    // child Rows ReIndex
                    $(this).find('.models').attr('name', 'PfiItem['+ index +'][model]');
                    $(this).find('.sfx').attr('name', 'PfiItem['+ index +'][sfx]');
                    $(this).find('.unit-prices').attr('name', 'PfiItem['+ index +'][unit_price]');
                    $(this).find('.parent-pfi-quantities').attr('name', 'PfiItem['+ index +'][parent_pfi_quantity]');
                    var rowCount =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length;
                    ReIndex(index);
               
                });
                calculatePfiAmount();
            }else{
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one PFI Item Required',function (e) {
                }).set({title:"Can't Remove PFI Item"})
            }
        })
       
       
        function ReIndex(index) {
            let i = 0;
            $('.child-item-'+index).each(function (i) {
               
                    $(this).attr('id', 'row-'+index+'-item-'+ i);
                    $(this).find('.models').attr('item',i);
                    $(this).find('.models').attr('id','model-'+index+'-item-'+i);
                    $(this).find('.sfx').attr('item',i);
                    $(this).find('.sfx').attr('id','sfx-'+index+'-item-'+i);
                    $(this).find('.parent-pfi-quantities').attr('item',i);
                    $(this).find('.parent-pfi-quantities').attr('id','pfi-quantity-'+index+'-item-'+i);
                    $(this).find('.unit-prices').attr('item',i);
                    $(this).find('.unit-prices').attr('id','unit-price-'+index+'-item-'+i);
               
                $(this).find('.master-model-ids').attr('id','master-model-id-'+index+'-item-'+i);

                $(this).find('.loi-items').attr('name', 'PfiItem['+ index +'][loi_item]['+ i +']');
                $(this).find('.loi-items').attr('item',i);
                $(this).find('.loi-items').attr('id','loi-item-'+index+'-item-'+i);

                $(this).find('.pfi-quantities').attr('name', 'PfiItem['+ index +'][pfi_quantity]['+ i +']');
                $(this).find('.pfi-quantities').attr('item',i);
                $(this).find('.pfi-quantities').attr('id','pfi-quantity-'+index+'-item-'+i);

                $(this).find('.remaining-quantities').attr('item',i);
                $(this).find('.remaining-quantities').attr('id','remaining-quantity-'+index+'-item-'+i);

                $(this).find('.unit-prices').attr('name', 'PfiItem['+ index +'][unit_price]');
                $(this).find('.unit-prices').attr('item',i);
                $(this).find('.unit-prices').attr('id','unit-price-'+index+'-item-'+i);
                $(this).find('.unit-prices').attr('oninput','calculateTotalAmount('+index+','+i+')');

                $(this).find('.total-amounts').attr('item',i);
                $(this).find('.total-amounts').attr('id','total-amount-'+index+'-item-'+i);
                $(this).find('.removePFIItemButton').attr('id','remove-button-'+index+'-item-'+i);
                $(this).find('.removePFIItemButton').attr('item',+i);
                $(this).find('.removePFIItemButton').attr('index',+index);

                $(this).find('.models').attr('index', index);
                $(this).find('.sfx').attr('index', index);
                $(this).find('.loi-items').attr('index', index);
                $(this).find('.parent-pfi-quantities').attr('index', index);
                $(this).find('.pfi-quantities').attr('index', index);
                $(this).find('.remaining-quantities').attr('index', index);
                $(this).find('.unit-prices').attr('index', index);
                $(this).find('.total-amounts').attr('index', index);
              
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
                $('#loi-item-'+index+'-item-'+i).select2
                ({
                    placeholder: 'Select Code',
                    maximumSelectionLength:1,
                });

            });
        }
        
        function getSfx(index,childIndex) {
            $('.overlay').show();
         
            let model = $('#model-'+index+'-item-'+childIndex).val();
            let url = '{{ route('demand.get-sfx') }}';
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            selectedModelIds = [];
            
            if(childIndex == 0) {
                for(let i=1; i<=parentIndex; i++)
                {
                    var eachSelectedModelId = $('#master-model-id-'+i+'-item-0').val();

                    if(eachSelectedModelId) {
                        selectedModelIds.push(eachSelectedModelId);
                    }
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
          
            let customer = $('#client_id').val();
            let country = $('#country_id').val();
            let model = $('#model-'+index+'-item-0').val();
            let sfx = $('#sfx-'+index+'-item-0').val();
            let url = '{{ route('loi-item-code') }}';
            var selectedLOIItemIds = [];
            let totalchildIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
              for(let j=1; j<=totalchildIndex;j++) 
              {
                  var eachSelectedLOIItemId = $('#loi-item-'+index+'-item-'+j).val();
                  if(eachSelectedLOIItemId) {
                      selectedLOIItemIds.push(eachSelectedLOIItemId);
                  }
              }
            if(model.length > 0  && sfx.length > 0) {
                $('.overlay').show();
                $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
                    sfx:sfx[0],
                    client_id:customer[0],
                    country_id:country[0],
                    selectedLOIItemIds:selectedLOIItemIds
                },
                success:function (data) {
                    let codes = data.codes;
                    $('#loi-item-'+index+'-item-'+childIndex).empty();
                    
                     let exactMatchIds = data.parentCodes;
                     let msg = "";
                    if(data.codes) {
                        jQuery.each(codes, function(key,value){
                            if(jQuery.inArray(value.id,exactMatchIds) == -1){
                                msg = "";
                            }else{
                                    msg = '(Exact Match)';
                            }
                            $('#loi-item-'+index+'-item-'+childIndex).append('<option value="'+ value.id +'">'+ value.code+' '+ msg +'</option>');
                        });
                        if(data.parentCodes) {
                            if(childIndex == 1) {
                                $('#loi-item-'+index+'-item-'+childIndex).val(data.parentCodes[0]).trigger("change");
                                getLOIItemDetails(index,childIndex);
                            }
                        }
                    }
                       
                    $('.overlay').hide();
                }
            });
        }           
       }
       function getLOIItemDetails(index,childIndex) {
            let loiItem = $('#loi-item-'+index+'-item-'+childIndex).val();
            let currentLOIItemId = 'loi-item-'+index+'-item-'+childIndex;
            let loiItemText = $('#' + currentLOIItemId + ' option:selected').text();

            let vendor = $('#supplier-id').val();
            let country = $('#country_id').val();
            let customer = $('#client_id').val();
            // chcek the particular loi item is used as another child then manage the 
                         
            if(loiItem.length > 0) {
                $('.overlay').show();

                let url = '{{ route('loi-item-details') }}';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        loi_item_id: loiItem[0],   
                        supplier_id: vendor[0],
                        client_id: customer[0],
                        country_id: country[0]         
                    },
                    success:function (data) {
                        // check the selected LOI Item is selected for any other child , if yes reduce the quantity from remaining qty
                        $('#remaining-quantity-'+index+'-item-'+childIndex).val(data.remaining_quantity);
                        $('#pfi-quantity-'+index+'-item-'+childIndex).attr('max',data.remaining_quantity);
                       
                        calculateTotalAmount(index,childIndex);
                        if(data.isLOIItemPfiExist.length > 0) {
                            $('.existing-pfi-details').html('');
                            let existPfiItems = data.isLOIItemPfiExist;
                             $('.existing-pfi-details').attr('hidden', false);
                             $('.existing-pfi-details').append('<span>This LOI Code (' + loiItemText + ') is already added under PFI Number </span>');
                            jQuery.each(existPfiItems, function(key,value){
                                $('.existing-pfi-details').append('<span>'+ value.pfi.pfi_reference_number +' ( Quantity -'+ value.pfi_quantity +' ) </span>');
                            });
                        }else{

                            $('.existing-pfi-details').attr('hidden', true);
                            $('.existing-pfi-details').html('');
                        }
                        $('.overlay').hide();
                    }
                });
            }         
       }
     
       function getModels(index,item,type) {
           
            let parentModel = $('#model-'+index+'-item-0').val();
            let parentSfx = $('#sfx-'+index+'-item-0').val();
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            var selectedModelIds = [];
          
                for(let i=1; i<=parentIndex; i++)
                {
                    var eachSelectedModelId = $('#master-model-id-'+i+'-item-0').val();

                    if(eachSelectedModelId) {
                        selectedModelIds.push(eachSelectedModelId);
                    }
                }
                $('.overlay').show();
                $.ajax({
                    url:"{{route('pfi-item.models')}}",
                    type: "GET",
                    data:
                        {
                            model: parentModel[0],
                            sfx:parentSfx[0],
                            selectedModelIds:selectedModelIds,
                        },
                    dataType : 'json',
                    success: function(data) {
                    
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
                                for(let i=1; i<=parentIndex; i++)
                                {
                                    $('#model-'+i+'-item-0').html("");
                                    $('#model-'+i+'-item-0').select2({
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
       function getMasterModelId(index) {

        let model = $('#model-'+index+'-item-0').val();
        let sfx = $('#sfx-'+index+'-item-0').val();
        let supplier = $('#supplier-id').val();
            if(supplier.length > 0 && model.length > 0 && sfx.length > 0) {
                $.ajax({
                    type: "GET",
                    url: "{{route('pfi-item.master-model-ids')}}",
                    dataType: "json",
                    data: {
                        model: model[0],
                        sfx:sfx[0],
                        supplier_id:supplier[0]
                    },
                    success:function (data) {        
                        $('#master-model-id-'+index+'-item-0').val(data.master_model_id);
                        $('#unit-price-'+index+'-item-0').val(data.unit_price);
                        calculatePfiAmount();
                    }
                });    
            }
           
       }

        function appendModel(index,model){
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            for(let i=1; i<= parentIndex; i++)
            {
                if(i != index) {
                    let Currentmodel = $('#model-'+i+'-item-0').val();
                    if(model !== Currentmodel[0] ) {
                        // chcek this option value alredy exist in dropdown list or not.
                        var currentId = 'model-'+i+'-item-0';    
                        var isOptionExist = 'no';
                        $('#' + currentId +' option').each(function () {

                            if (this.text == model) {
                                isOptionExist = 'yes';
                                return false;
                            }
                        });
                        if(isOptionExist == 'no'){
                            $('#model-'+i+'-item-0').append($('<option>', {value: model, text : model}))
                        }
                    }
                }
            }
        }
       
        function appendSFX(index,unSelectedmodel,sfx){
           var totalIndex = $("#pfi-items").find(".pfi-items-parent-div").length;

           for(let i=1; i<=totalIndex; i++)
           {
            var model = $('#model-'+i+'-item-0').val();
               if(i != index && unSelectedmodel == model[0] ) {  
                   // chcek the model is same as unselected model,
                   $('#sfx-'+i+'-item-0').append($('<option>', {value: sfx, text : sfx}));     
               }
           }
       }
       function hideSFX(index, value) {
         
         var totalIndex = $("#pfi-items").find(".pfi-items-parent-div").length
         let model = $('#model-'+index+'-item-0').val();
            for(let i=1; i<=totalIndex; i++)
            {
                let currentmodel = $('#model-'+i+'-item-0').val();
                if(i != index && currentmodel == model[0]) {
                    var currentId = 'sfx-' + i+'-item-0';
                    $('#' + currentId + ' option[value=' + value + ']').detach();       
                }
            }
        }
        function enableOrDisableAddMoreButton(index) {
            // check any country is selected or not
            let country = $('#country_id').val();
            let sfx = $('#sfx-'+index+'-item-0').val();
            if(country.length > 0 && sfx.length > 0) {
                let model = $('#model-'+index+'-item-0').val();
                // check sfx is there the model also will be there
                // chcek the selected model and sfx brand is toyota
                $.ajax({
                    url:"{{route('pfi-item.get-brand')}}",
                    type: "GET",
                    data:
                        {
                            model: model[0],
                            sfx:sfx[0],
                        },
                    dataType : 'json',
                    success: function(data) {
                        if(data == 1) {
                            // brand is toyota
                            $('#add-more-'+index).removeClass('disabled');
                            $('#pfi-quantity-'+index+'-item-0').attr('readonly', true);

                        }else{

                            $('#pfi-quantity-'+index+'-item-0').removeAttr('readonly');

                        }
                    }
                })

            }else if(country.length <= 0 || sfx.length <= 0){
                $('#add-more-'+index).addClass('disabled');
               
            }
       }
       function getCustomerCountries() {
        let url = '{{ route('pfi-item.customer-countries')}}';
        let customer = $('#client_id').val();
        if(customer.length > 0) {
            $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        client_id: customer[0],   
                    },
                    success:function (data) {
                        $('#country_id').empty();
                        jQuery.each(data, function(key,value){
                            $('#country_id').append('<option value="'+ value.id +'">'+ value.name+'</option>');
                         });
                        
                    }
                });
        }
       }
       
    </script>
@endpush

