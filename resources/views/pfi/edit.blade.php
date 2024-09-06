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
    @can('pfi-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('pfi-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit PFI</h4>
               
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
                            <form action="{{ route('pfi.update', $pfi->id) }}" id="form-update" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-xxl-8 col-lg-6 col-md-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">PFI Number</label>
                                                    <input type="text" class="form-control widthinput" id="pfi_reference_number" autofocus placeholder="Enter PFI Number"
                                                           name="pfi_reference_number" value="{{ old('pfi_reference_number', $pfi->pfi_reference_number) }}">
                                                    <span id="pfi-error" class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">Vendor</label>
                                                    <select class="form-control widthinput" name="supplier_id" id="supplier-id" multiple >
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{$supplier->id}}"  {{ $supplier->id == $pfi->supplier_id ? "selected" : '' }} data-is-MMC="{{$supplier->is_MMC}}"
                                                             data-is-AMS="{{$supplier->is_AMS}}" >
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
                                                            <option value="{{$customer->id}}"  {{ $customer->id == $pfi->client_id ? "selected" : '' }} >
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
                                                    @foreach($customerCountries as $customerCountry)
                                                            <option value="{{$customerCountry->id}}"  {{ $customerCountry->id == $pfi->country_id ? "selected" : '' }} >
                                                                {{ $customerCountry->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label for="choices-single-default" class="form-label">PFI Amount</label>
                                                    <input type="number" class="form-control widthinput pfi-amount" value="{{old('amount', $pfi->amount)}}" readonly name="amount" min="0" placeholder="PFI Amount">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">PFI Document</label>
                                                    <input type="file" id="file" class="form-control widthinput" name="file">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                <span class="error">* </span>
                                                    <label for="choices-single-default" class="form-label">PFI Date</label>
                                                    <input type="date" value="{{ Illuminate\Support\Carbon::parse($pfi->pfi_date)->format('Y-m-d') }}"
                                                     class="form-control widthinput" name="pfi_date">
                                                </div>
                                            </div>
                                          
                                                <!-- <span class="" hidden> -->
                                                    <div class="col-lg-4 col-md-6 mmc-items-div" hidden>
                                                        <div class="mb-3">
                                                            <label for="choices-single-default" class="form-label">Delivery Location</label>
                                                            <input type="text" id="delivery-location" class="form-control" name="delivery_location"
                                                            placeholder="Delivery Location">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6 mmc-items-div" hidden>
                                                        <div class="mb-3">
                                                            <label for="choices-single-default" class="form-label">Currency</label>
                                                            <select class="form-control" name="currency" id="currency" >
                                                                <option value="USD" {{ $pfi->currency == 'USD' ? 'selected' : ''}}>USD</option>
                                                                <option value="EUR" {{ $pfi->currency == 'EUR' ? 'selected' : ''}}>EUR</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <!-- </span> -->
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
                                        <iframe src="{{ url('PFI_document_withoutsign/'.$pfi->pfi_document_without_sign) }}" ></iframe>
                                   
                                        </div>
                                    </div>                                  
                                </div>
                                
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Add PFI Item Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            <div class="col-lg-2 col-md-6 col-sm-12">
                                                <span class="error">* </span>
                                                <label class="form-label">Model</label>
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                                                <span class="error">* </span>
                                                <label class="form-label">SFX</label>
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                                <span class="error">* </span>
                                                <label class="form-label">LOI Code</label>
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                                                <span class="error">* </span>
                                                <label class="form-label">PFI QTY</label>
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-12">
                                                <label class="form-label">Unused QTY</label>
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12">
                                                <span class="error">* </span>
                                                <label class="form-label">Unit Price</label>
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12">
                                                <label class="form-label">Total Price</label>
                                            </div>
                                        </div>
                                        <div id="pfi-items" >
                                            @foreach($parentPfiItems as $key => $pfi_item) 
                                                <div class="row pfi-items-parent-div" id="row-{{$key+1}}">
                                                    <div class="row pfi-child-item-div-{{$key+1}}" id="parentItem" index="{{$key+1}}" >
                                                        <div class="row chilItems child-item-{{$key+1}}" id="row-{{$key+1}}-item-0">
                                                            <div class="col-lg-2 col-md-6">
                                                                <select class="form-select widthinput text-dark models mb-2 border-bold"  required
                                                                index="{{$key+1}}" item="0" id="model-{{$key+1}}-item-0" multiple name="PfiItem[{{$key+1}}][model][0]">
                                                                    <option value="" >Select Model</option>
                                                                        @foreach($masterModels as $model)
                                                                            <option value="{{ $model->model }}" {{ $pfi_item->masterModel->model == $model->model ? 'selected' : '' }}>{{ $model->model }}</option>
                                                                        @endforeach
                                                                    </select>  
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <select class="form-control text-dark widthinput sfx mb-2" required multiple 
                                                                name="PfiItem[{{$key+1}}][sfx][0]" index="{{$key+1}}" item="0" id="sfx-{{$key+1}}-item-0">
                                                                    @foreach($pfi_item->sfxLists as $sfx)
                                                                        <option value="{{ $sfx}}" {{$sfx == $pfi_item->masterModel->sfx ? 'selected' : ''}} >{{ $sfx }}</option>
                                                                    @endforeach
                                                                </select>
                                                            
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <select class="form-control text-dark widthinput loi-items mb-2" required multiple
                                                                name="PfiItem[{{$key+1}}][loi_item][0]" index="{{$key+1}}" item="0" id="loi-item-{{$key+1}}-item-0" 
                                                                placeholder="LOI Code" >
                                                                    @if($pfi_item->is_loi_available == 'YES')
                                                                        @foreach($pfi_item->LOIItemCodes as $LOIItemCode)
                                                                            <option value="{{ $LOIItemCode->id }}" {{$LOIItemCode->id == $pfi_item->letterOfIndentItem->id ? 'selected' : ''}} >
                                                                        {{ $LOIItemCode->code }}</option>
                                                                        @endforeach
                                                                    @else
                                                                
                                                                        <option value="NULL" selected>NO LOI</option> 
                                                                        
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <input type="number" min="1" oninput=calculateTotalAmount({{$key+1}},0) required name="PfiItem[{{$key+1}}][pfi_quantity][0]"
                                                                    class="form-control mb-2 widthinput pfi-quantities" placeholder="0"
                                                                    index="{{$key+1}}" item="0" id="pfi-quantity-{{$key+1}}-item-0" value="{{$pfi_item->pfi_quantity }}">
                                                            </div>
                                                            <div class="col-lg-1 col-md-6">
                                                                <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                                                    index="{{$key+1}}" item="0" id="remaining-quantity-{{$key+1}}-item-0" 
                                                                    value="{{$pfi_item->remainingQuantity }}">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <input type="number" min="0"  required placeholder="0" name="PfiItem[{{$key+1}}][unit_price][0]" oninput=calculateTotalAmount({{$key+1}},0) 
                                                                    class="form-control widthinput mb-2 unit-prices" placeholder="Unit price" 
                                                                    index="{{$key+1}}" item="0" id="unit-price-{{$key+1}}-item-0" value="{{$pfi_item->unit_price }}">
                                                            </div>
                                                            <div class="col-lg-2 col-md-6">
                                                                <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" 
                                                                placeholder="Total Amount" id="total-amount-{{$key+1}}-item-0" index="{{$key+1}}" item="0"
                                                                value="{{ $pfi_item->totalAmount }}">
                                                                <input type="hidden" class="master-model-ids" id="master-model-id-{{$key+1}}-item-0" value="{{ $pfi_item->master_model_id }}">
                                                            </div>
                                                            <div class="col-lg-1 col-md-6 col-sm-12" >
                                                                <a class="btn btn-primary btn-sm add-more" id="add-more-{{$key+1}}" index="{{$key+1}}" item="0"
                                                                title="Add Child PFI Items" > <i class="fas fa-plus"> </i> 
                                                                    </a>
                                                                <a class="btn btn-sm btn-danger removePFIButton" id="remove-btn-{{$key+1}}" index="{{$key+1}}"> 
                                                                    <i class="fas fa-trash-alt"></i> </a>
                                                            </div>
                                                        </div>
                                                        @foreach($pfi_item->childPfiItems as $child_Key => $childPfiItem)
                                                            <div class="row chilItems child-item-{{$key+1}}" id="row-{{$key+1}}-item-{{$child_Key+1}}">
                                                                <div class="col-lg-2 col-md-6 col-sm-12">
                                                                    <select class="form-select widthinput text-dark models" multiple name="PfiItem[{{$key+1}}][model][{{$child_Key+1}}]"
                                                                        index="{{$key+1}}" item="{{$child_Key+1}}" id="model-{{$key+1}}-item-{{$child_Key+1}}" required autofocus>
                                                                        @foreach($childPfiItem->masterModels as $childModel)
                                                                            <option value="{{ $childModel->model }}" {{ $childPfiItem->masterModel->model == $childModel->model ? 'selected' : '' }} >{{ $childModel->model }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                
                                                                </div>
                                                                <div class="col-lg-1 col-md-6 col-sm-12 mb-3">
                                                                    <select class="form-select widthinput text-dark sfx"  multiple name="PfiItem[{{$key+1}}][sfx][{{$child_Key+1}}]" 
                                                                    index="{{$key+1}}" item="{{$child_Key+1}}" required id="sfx-{{$key+1}}-item-{{$child_Key+1}}" >
                                                                    
                                                                    @foreach($childPfiItem->sfxLists as $sfx)
                                                                        <option value="{{ $sfx}}" {{ $childPfiItem->masterModel->sfx == $sfx ? 'selected' : ''}} >{{ $sfx }}</option>
                                                                    @endforeach
                                                                    </select>
                                                                    
                                                                </div>
                                                                <div class="col-lg-2 col-md-6">
                                                                    <select class="form-control text-dark widthinput loi-items mb-2" required index="{{$key+1}}" multiple
                                                                        name="PfiItem[{{$key+1}}][loi_item][{{$child_Key+1}}]" item="{{$child_Key+1}}" id="loi-item-{{$key+1}}-item-{{$child_Key+1}}">
                                                                        @if($childPfiItem->is_loi_available == 'YES')
                                                                            @foreach($childPfiItem->LOIItemCodes as $LOIItemCode)
                                                                                <option value="{{ $LOIItemCode->id }}" {{$LOIItemCode->id == $childPfiItem->letterOfIndentItem->id ? 'selected' : ''}}>
                                                                                {{ $LOIItemCode->code }}</option>
                                                                            @endforeach
                                                                         @else
                                                                            <option value="NULL" selected>NO LOI</option> 
                                                                        @endif
                                                                       
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-1 col-md-6">
                                                                    <input type="number" min="1" placeholder="0" required oninput=calculateTotalAmount({{$key+1}},{{$child_Key+1}}) 
                                                                    name="PfiItem[{{$key+1}}][pfi_quantity][{{$child_Key+1}}]" class="form-control mb-2 widthinput pfi-quantities" 
                                                                    index="{{$key+1}}" item="{{$child_Key+1}}" id="pfi-quantity-{{$key+1}}-item-{{$child_Key+1}}" value="{{ $childPfiItem->pfi_quantity}}">
                                                                </div>
                                                                <div class="col-lg-1 col-md-6">
                                                                    <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                                                        index="{{$key+1}}" item="{{$child_Key+1}}" id="remaining-quantity-{{$key+1}}-item-{{$child_Key+1}}"  value="{{ $childPfiItem->remainingQuantity}}">
                                                                </div>
                                                                <div class="col-lg-2 col-md-6">
                                                                    <input type="number" min="0"  required placeholder="0" index="{{$key+1}}" name="PfiItem[{{$key+1}}][unit_price][{{$child_Key+1}}]" 
                                                                    oninput=calculateTotalAmount({{$key+1}},{{$child_Key+1}})  class="form-control widthinput mb-2 unit-prices"  value="{{ $childPfiItem->unit_price}}"
                                                                        id="unit-price-{{$key+1}}-item-{{$child_Key+1}}" item="{{$child_Key+1}}" placeholder="Unit price">
                                                                </div>
                                                                <div class="col-lg-2 col-md-6">
                                                                    <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" index="{{$key+1}}"
                                                                        id="total-amount-{{$key+1}}-item-{{$child_Key+1}}" item="{{$child_Key+1}}" placeholder="Total Price" value="{{ $childPfiItem->totalAmount}}">
                                                                    <input type="hidden" class="master-model-ids" id="master-model-id-{{$key+1}}-item-{{$child_Key+1}}"  value="{{ $childPfiItem->masterModel->id}}">
                                                                </div>
                                                                <div class="col-lg-1 col-md-6 col-sm-12">
                                                                    <a class="btn btn-sm btn-danger removePFIItemButton" id="remove-btn-{{$key+1}}-item-{{$child_Key+1}}" item="{{$child_Key+1}}" 
                                                                    index="{{$key+1}}" > 
                                                                    <i class="fas fa-trash-alt"></i> </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
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
                else if (file.type.match("image/*"))
                {
                    const objectUrl = URL.createObjectURL(file);
                    const image = new Image();
                    image.src = objectUrl;
                    previewFile.appendChild(image);
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
        $('#country_id').select2({
            placeholder: "Select Country",
            maximumSelectionLength: 1
        });
        $('.models').select2({
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
       
        $("#form-update").validate({
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
            $( document ).ready(function() {
                hideorShowMMCDiv();
                let type = 'Edit';
            
            });

            function hideorShowMMCDiv() {
                let MMC = $('#supplier-id').find('option:selected').attr("data-is-MMC");
            if(MMC == 1) {
                $('.mmc-items-div').attr('hidden', false);
            }else{
                $('.mmc-items-div').attr('hidden', true);
                $('#delivery-location').val('');
                $('#currency').val('USD');
            }
            }

        // get the unit price while supplier select

        $(document.body).on('select2:select', "#supplier-id", function (e) {
            // $('#pfi-items-div').attr('hidden',false);
            let loiItems = [];
             $('.loi_item_ids').map(function(){
                loiItems.push($(this).val());
                });
            // let supplier = $(this).val();
            hideorShowMMCDiv();
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            for(let i=1; i<=parentIndex;i++) 
            {
                let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
               
                for(let j=0; j<=childIndex;j++) 
                {
                    getLOIItemDetails(i,j);                 
                }
            }
        });

        $(document.body).on('select2:unselect', "#supplier-id", function (e) {
            $('.unit-prices').val(0);
            $('.remaining-quantities').val(0);
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
        
        function calculateTotalAmount(index,childIndex) {
            var quantity = $('#pfi-quantity-'+index+'-item-'+childIndex).val();
            var unitPrice = $('#unit-price-'+index+'-item-'+childIndex).val();
            if(childIndex == 0) {
                let totalIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                for(let j=1; j<=totalIndex;j++) 
                {
                   $('#unit-price-'+index+'-item-'+j).val(unitPrice);              
                }
            }
            var eachItemTotal = parseFloat(quantity) * parseFloat(unitPrice);
            $('#total-amount-'+index+'-item-'+childIndex).val(eachItemTotal);

            calculatePfiAmount();
        }

        // $('form').on('submit', function(e){
        //     $('.overlay').show();
            
        //     // let quantitySum = 0;
        //     // $('.pfi-quantities').each(function() {
        //     //     var quantity = $(this).val();
        //     //     quantitySum = parseFloat(quantitySum) + parseFloat(quantity);
                
        //     // });
        //     // if(quantitySum <= 0) {
        //     //     $('.overlay').hide();
        //     //     e.preventDefault();
        //     //     alertify.confirm('Atleast one vehicle item is mandatory in PFI.').set({title:"Alert !"})
        //     // }else {
        //         if($("#form-update").valid()) {
        //             $('#form-update').submit();
        //         }else{
        //             $('.overlay').hide();
        //             e.preventDefault();
        //         }
        //     // }
        // });

        ///// start new code ////
      
        $(document.body).on('select2:select', "#client_id", function (e) {
           // pass any type to include selected Ids in the query
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
                    // call unit price,remaining qty, total quantity
                }
            }
            getCustomerCountries()
        });
        $(document.body).on('select2:unselect', "#country_id", function (e) {
            
            let data =  e.params.data.id;
            let isDataAvailable = false;
           // chcek any item selcted 
           var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;

            for(let i=1; i<=parentIndex;i++) 
            {
                let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=0; j<=childIndex;j++) 
                {
                    let model = $('#model-'+i+'-item-'+j).val();
                    let sfx = $('#sfx-'+i+'-item-'+j).val();
                    let loiCode = $('#loi-item-'+i+'-item-'+j).val();
                    if(model.length > 0 || sfx.length > 0 || loiCode.length > 0 ){
                     isDataAvailable = true;
                        break;
                                                 
                    }                                                
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
                let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=0; j<=childIndex;j++) 
                {
                    let model = $('#model-'+i+'-item-'+j).val();
                    let sfx = $('#sfx-'+i+'-item-'+j).val();
                    let loiCode = $('#loi-item-'+i+'-item-'+j).val();
                    if(model.length > 0 || sfx.length > 0 || loiCode.length > 0 ){
                        isData = true;
                            break;
                                               
                    }                                             
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

            for(let i=1; i<=parentIndex;i++) 
            {
                enableOrDisableAddMoreButton(i);
              
                let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=0; j<=childIndex;j++) 
                {
                    if(j == 0) {
                        $("#model-"+i+"-item-"+j).prop("selectedIndex", -1).trigger("change");
                      
                    }else{
                        $("#model-"+i+"-item-"+j).empty();
                       
                    }
                    $("#sfx-"+i+"-item-"+j).empty();
                    $("#loi-item-"+i+"-item-"+j).empty();
                    $("#pfi-quantity-"+i+"-item-"+j).val("");
                    $("#remaining-quantity-"+i+"-item-"+j).val("");
                    $("#unit-price-"+i+"-item-"+j).val("");
                    $("#total-amount-"+i+"-item-"+j).val("");
                    $('#pfi-quantity-'+i+'-item-'+j).removeAttr("max");
                    $('#master-model-id-'+i+'-item-'+j).val("");
                    // country unselect if client id unselect
                    $('#country_id').prop("selectedIndex", -1).trigger("change");     
                    
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
           let sfx =  $('#sfx-'+index+'-item-0').val();
           // if unselected model is in the parent row append model in every parent line items
           if(childIndex == 0) {              
                if(sfx.length > 0) {
                    appendParentModel(index,model,sfx[0]);
                }
                let childIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                for(let j=0; j<=childIndex;j++) 
                {
                    if(j !=0){
                       $('#model-'+index+'-item-'+j).empty();
                    }
                    $('#sfx-'+index+'-item-'+j).empty();
                    resetRowData(index,j); 
                }
                enableOrDisableAddMoreButton(index);
           }else{
               
                 var loiItemId = $('#loi-item-'+index+'-item-'+childIndex).val();
                 var loiItemText = $('#loi-item-'+index+'-item-'+childIndex).text();
                appendLOIItemCode(index,childIndex,loiItemId[0],loiItemText,model,sfx[0]);
                $('#sfx-'+index+'-item-'+childIndex).empty();
                resetRowData(index,childIndex); 
           }
        //    var confirm = alertify.confirm('By changing model child items data will be reset!',function (e) {
        //         if (e) {
        //             resetData();                               
        //         }
        //     }).set({title:"Are You Sure ?"}).set('oncancel', function(closeEvent){
        //         $("#model-"+index+'-item-0').val(model).trigger('change');
                
        //         });  
       
           // call append LOI Item code if same model am
           
       });

       function resetRowData(index,childIndex,input) {
            
           $('#loi-item-'+index+'-item-'+childIndex).empty();
           $('#remaining-quantity-'+index+'-item-'+childIndex).val("");
           $('#pfi-quantity-'+index+'-item-'+childIndex).val("");
           $('#unit-price-'+index+'-item-'+childIndex).val("");
           $('#total-amount-'+index+'-item-'+childIndex).val("");
           $('#master-model-id-'+index+'-item-'+childIndex).val("");
           $('#pfi-quantity-'+index+'-item-'+childIndex).removeClass('is-invalid');
           $('#pfi-quantity-'+index+'-item-'+childIndex).removeAttr("max");
       }
        $(document.body).on('select2:select', ".sfx", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#sfx-'+index+'-item-'+childIndex +'-error').remove();
                // if selected sfx is in the parent row hide corresponding model in every parent line items
            if(childIndex == 0) {
                hideParentModel(index);
                hideParentSFX(index);
                let type = 'all';
                getChildModels(index,0,type);
            }
            getLOIItemCode(index, childIndex);
            enableOrDisableAddMoreButton(index);
            // hideSFX(index, value);
           
        });
        $(document.body).on('select2:unselect', ".sfx", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            let model = $('#model-'+index+'-item-0').val();
            var value = e.params.data.id;
            $('#master-model-id-'+index+'-item-'+childIndex).val("");
               // if unselected sfx is in the parent row append corresponding model in every parent line items
            if(childIndex == 0) {
                appendParentSFX(index,value);
                let childIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
                for(let j=0; j<=childIndex;j++) 
                {
                    if(j !=0){
                       $('#sfx-'+index+'-item-'+j).empty();
                    }
                    resetRowData(index,j); 
                }
                appendParentModel(index,model[0],value);
                enableOrDisableAddMoreButton(index);
            }else{
                var loiItemId = $('#loi-item-'+index+'-item-'+childIndex).val();
                var loiItemText = $('#loi-item-'+index+'-item-'+childIndex).text();
                appendLOIItemCode(index,childIndex,loiItemId[0],loiItemText,model[0],value);
                resetRowData(index,childIndex);
            }
           
         
        });
        $(document.body).on('select2:select', ".loi-items", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
            $('#loi-item-'+index+'-item-'+childIndex +'-error').remove();
            var value = e.params.data.id;
            hideLOIItemCode(index,childIndex,value);
            getLOIItemDetails(index,childIndex);
        });
        $(document.body).on('select2:unselect', ".loi-items", function (e) {
            let index = $(this).attr('index');
            let childIndex = $(this).attr('item');
           
            var id = e.params.data.id;
            var text = e.params.data.text;
            let model = $('#model-'+index+'-item-'+childIndex).val();
            let sfx = $('#sfx-'+index+'-item-'+childIndex).val();

            appendLOIItemCode(index,childIndex,id,text,model[0],sfx[0]);
            $('#remaining-quantity-'+index+'-item-'+childIndex).val("");
            $('#unit-price-'+index+'-item-'+childIndex).val("");
            $('#total-amount-'+index+'-item-'+childIndex).val("");
            $('#pfi-quantity-'+index+'-item-'+childIndex).val("");
            $('#pfi-quantity-'+index+'-item-'+childIndex).removeAttr("max");
            $('#pfi-quantity-'+index+'-item-'+childIndex).removeClass('is-invalid');

        });

        $(document.body).on('click', ".add-more", function (e) {
            let index = $(this).attr('index');

            let unitPrice =  $("#unit-price-"+index+"-item-0").val();
            let item =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length;
            let loiItem = $('#loi-item-'+index+'-item-0').val();
               if(loiItem[0] == 'NULL')  {
                var confirm = alertify.confirm('This model and sfx do not have any LOI, So you can not add child items',function (e) {
                }).set({title:"Can't Add Child PFI Item"})
               }else{
          
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
                            <div class="col-lg-2 col-md-6">
                                <select class="form-control text-dark widthinput loi-items mb-2" required index="${index}" multiple
                                    name="PfiItem[${index}][loi_item][${item}]" item="${item}" id="loi-item-${index}-item-${item}">
                                    <option value="" ></option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" min="1" placeholder="0" required oninput=calculateTotalAmount(${index},${item}) 
                                name="PfiItem[${index}][pfi_quantity][${item}]" class="form-control mb-2 widthinput pfi-quantities" 
                                index="${index}" item="${item}" id="pfi-quantity-${index}-item-${item}">
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                    index="${index}" item="${item}" id="remaining-quantity-${index}-item-${item}">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0"  placeholder="0" name="PfiItem[${index}][unit_price][${item}]" value="${unitPrice}"
                               oninput=calculateTotalAmount(${index},${item}) class="form-control widthinput mb-2 unit-prices"
                                    id="unit-price-${index}-item-${item}" item="${item}"  index="${index}" placeholder="Unit price">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" index="${index}"
                                    id="total-amount-${index}-item-${item}" item="${item}" placeholder="Total Price">
                                <input type="hidden" class="master-model-ids" id="master-model-id-${index}-item-${item}">
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
                        getChildModels(index,item,type);

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
                }
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
                            <div class="col-lg-2 col-md-6">
                                <select class="form-control text-dark widthinput loi-items mb-2" required index="${index}" multiple
                                    name="PfiItem[${index}][loi_item][0]" item="0" id="loi-item-${index}-item-0">
                                    <option value="" ></option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" min="1" placeholder="0" required oninput=calculateTotalAmount(${index},0) 
                                name="PfiItem[${index}][pfi_quantity][0]" class="form-control mb-2 widthinput pfi-quantities" 
                                index="${index}" item="0" id="pfi-quantity-${index}-item-0">
                            </div>
                            <div class="col-lg-1 col-md-6">
                                <input type="number" readonly class="form-control mb-2 widthinput remaining-quantities" placeholder="0"
                                    index="${index}" item="0" id="remaining-quantity-${index}-item-0">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0"  required placeholder="0" name="PfiItem[${index}][unit_price][0]" 
                                class="form-control widthinput mb-2 unit-prices"  index="${index}" oninput=calculateTotalAmount(${index},0) 
                                    id="unit-price-${index}-item-0" item="0" placeholder="Unit price">
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <input type="number" min="0" readonly class="form-control mb-2 widthinput total-amounts" index="${index}"
                                    id="total-amount-${index}-item-0" item="0" placeholder="Total Price">
                                <input type="hidden" class="master-model-ids" id="master-model-id-${index}-item-0">
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12">
                                <a class="btn btn-primary btn-sm add-more disabled" 
                                 index="${index}" item="0" id="add-more-${index}"
                                title="Add Child PFI Items">  <i class="fas fa-plus"> </i> 
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
            // var rowCount =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
            var childIndex = $(this).attr('item');
            var sfx = $('#sfx-'+index+'-item-'+childIndex).val();
            var model = $('#model-'+index+'-item-'+childIndex).val();
            var loiItemId = $('#loi-item-'+index+'-item-'+childIndex).val();
            var loiItemText = $('#loi-item-'+index+'-item-'+childIndex).text();
           
            if(loiItemId[0]) {
                appendLOIItemCode(index,childIndex,loiItemId,loiItemText,model[0],sfx[0]);
            }
              
            $(this).closest('#row-' + index + '-item-' + childIndex).remove();
            ReIndex(index);
                 
                
         });
        $(document.body).on('click', ".removePFIButton", function (e) {
            var rowCount = $("#pfi-items").find(".pfi-items-parent-div").length;
            if(rowCount > 1) {

                var indexNumber = $(this).attr('index');
                var sfx = $('#sfx-'+indexNumber+'-item-0').val();
                var model = $('#model-'+indexNumber+'-item-0').val();
                
                if(sfx[0]) {
                    appendParentModel(indexNumber,model[0],sfx[0]);                 
                }

                $(this).closest('#row-'+indexNumber).remove();

                $('.pfi-items-parent-div').each(function(j){
                    var index = +j + +1;

                    $(this).attr('id', 'row-'+index);
                    $(this).find('#parentItem').attr('class', 'row pr-0 mr-0 pfi-child-item-div-'+index);
                    $(this).find('.chilItems').attr('class', 'row chilItems child-item-'+index);
                    $(this).find('.removePFIButton').attr('index', index);
                    $(this).find('.add-more').attr('index', index);
                    $(this).find('.add-more').attr('id', 'add-more-'+index);
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
                $(this).find('.pfi-quantities').attr('oninput','calculateTotalAmount('+index+','+i+')');

                $(this).find('.remaining-quantities').attr('item',i);
                $(this).find('.remaining-quantities').attr('id','remaining-quantity-'+index+'-item-'+i);

                $(this).find('.unit-prices').attr('name', 'PfiItem['+ index +'][unit_price]['+ i +']');
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
                $(this).find('.pfi-quantities').attr('index', index);
                $(this).find('.remaining-quantities').attr('index', index);
                $(this).find('.unit_prices').attr('index', index);
              
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
            let model = $('#model-'+index+'-item-'+childIndex).val();
            let sfx = $('#sfx-'+index+'-item-'+childIndex).val();
            let url = '{{ route('loi-item-code') }}';
            var selectedLOIItemIds = [];
                var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
                for(let i=1; i<=parentIndex;i++) 
                {
                    let childIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                    for(let j=0; j<=childIndex;j++) 
                    {
                        var eachSelectedLOIItemId = $('#loi-item-'+i+'-item-'+j).val();
                        if(eachSelectedLOIItemId) {
                            selectedLOIItemIds.push(eachSelectedLOIItemId);
                        }
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
                    if(data.is_loi_available == 'NO') {
                        let id = 'loi-item-'+index+'-item-'+childIndex;
                        $('#'+id).append('<option value="NULL">NO LOI</option>');
                        $('#'+id ).val("NULL").trigger('change');
                           
                    }else{
                        jQuery.each(codes, function(key,value){                          
                            $('#loi-item-'+index+'-item-'+childIndex).append('<option value="'+ value.id +'">'+ value.code +'</option>');
                         });
                    }
                    $('#master-model-id-'+index+'-item-'+childIndex).val(data.master_model_id);
                    $('.overlay').hide();
                }
            });
        }           
       }
       function getLOIItemDetails(index,childIndex) {
            let loiItem = $('#loi-item-'+index+'-item-'+childIndex).val();
            let vendor = $('#supplier-id').val();
            if(loiItem.length > 0) {
                $('.overlay').show();

                let url = '{{ route('loi-item-details') }}';
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        loi_item_id: loiItem[0],   
                        supplier_id: vendor[0]            
                    },
                    success:function (data) {
                        $('#remaining-quantity-'+index+'-item-'+childIndex).val(data.remaining_quantity);
                        $('#pfi-quantity-'+index+'-item-'+childIndex).attr('max',data.remaining_quantity);
                        $('#unit-price-'+index+'-item-'+childIndex).val(data.unit_price);
                        calculateTotalAmount(index,childIndex)
                        $('.overlay').hide();
                    }
                });
            }
                
       }
       function getModels(index,item,type) {
           
            // let customer = $('#client_id').val();
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
            // if(customer.length > 0) {
                $('.overlay').show();
                $.ajax({
                    url:"{{route('pfi-item.master-models')}}",
                    type: "GET",
                    data:
                        {
                            model: parentModel[0],
                            sfx:parentSfx[0],
                            selectedModelIds:selectedModelIds,
                            is_child:'No'
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
            // }          
       }
       function getChildModels(index,item,type) {
            let customer = $('#client_id').val();
            let country = $('#country_id').val();
            let parentModel = $('#model-'+index+'-item-0').val();
            let parentSfx = $('#sfx-'+index+'-item-0').val();
            if(customer.length > 0 && parentModel.length > 0 && parentSfx.length > 0) {
                $('.overlay').show();
                $.ajax({
                    url:"{{route('pfi-item.master-models')}}",
                    type: "GET",
                    data:
                        {
                            model: parentModel[0],
                            sfx:parentSfx[0],
                            is_child:'Yes',
                            customer:customer[0],
                            country_id:country[0],
                        },
                    dataType : 'json',
                    success: function(data) {
                        let rowIndex =  $(".pfi-child-item-div-"+index).find(".child-item-"+index).length - 1;
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
                        } else{
                                for(let i=1; i<=rowIndex; i++)
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
       }
       function hideLOIItemCode(index,childIndex,value) {
            
        var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            for(let i=1; i<=parentIndex;i++) 
            {
                let rowIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=0; j<=rowIndex;j++) 
                {
                    var currentId = 'loi-item-'+i+'-item-'+j;
                    var selectedId = 'loi-item-'+index+'-item-'+childIndex;
    
                    if(selectedId != currentId ) {
                        var currentId = 'loi-item-'+i+'-item-'+j;
                        $('#' + currentId + ' option[value=' + value + ']').detach(); 
                    }
                }
            }

       }
       function appendLOIItemCode(index,childIndex,id,text,selectedmodel,selectedsfx)
        {
            var selectedId = 'loi-item-'+index+'-item-'+childIndex;
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
            for(let i=1; i<=parentIndex;i++) 
            {           
                let rowIndex =  $(".pfi-child-item-div-"+i).find(".child-item-"+i).length - 1;
                for(let j=0; j<=rowIndex;j++) 
                {             
                    var currentId = 'loi-item-'+i+'-item-'+j;                     
                    let currentmodel = $('#model-'+i+'-item-'+j).val();
                    let currentsfx = $('#sfx-'+i+'-item-'+j).val();
                 
                    if(selectedId != currentId && selectedmodel == currentmodel[0] && selectedsfx == currentsfx[0]) {
                        $('#loi-item-'+i+'-item-'+j).append($('<option>', {value: id, text : text}));    
                    }
                }
            }    
        }

        function hideParentModel(index) {
            var selectedId = 'model-'+index+'-item-0';
            let model = $('#model-'+index+'-item-0').val(); 
            let sfx = $('#sfx-'+index+'-item-0').val(); 
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
                for(let i=1; i<=parentIndex;i++) 
                { 
                    var currentId = 'model-'+i+'-item-0';
                    let currentModel = $('#' + currentId).val();
                    let currentsfx = $('#sfx-'+i+'-item-0').val(); 
                    if(selectedId != currentId && model[0] == currentModel[0] && sfx[0] == currentsfx[0]) {                  
                        $('#' + currentId + ' option[value=' + model[0] + ']').detach(); 
                        // if(currentModel[0] == model[0]) {
                            $('#sfx-'+i+'-item-0').empty();
                        // }
                      
                    }
                  
                }
        }
        function appendParentModel(index,model,sfx) {
            var selectedId = 'model-'+index+'-item-0';
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
                for(let i=1; i<=parentIndex;i++) 
                { 
                    var currentId = 'model-'+i+'-item-0';                     
                    let currentmodel = $('#model-'+i+'-item-0').val();
                    let currentsfx = $('#sfx-'+i+'-item-0').val();
                    if(selectedId != currentId && model != currentmodel[0] && sfx != currentsfx[0]) {
                        $('#model-'+i+'-item-0').append($('<option>', {value: model, text : model}));    
                    }
                }
        }
        function hideParentSFX(index) {
            var selectedId = 'sfx-'+index+'-item-0';
            let model = $('#model-'+index+'-item-0').val(); 
            let sfx = $('#sfx-'+index+'-item-0').val(); 
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
                for(let i=1; i<=parentIndex;i++) 
                { 
                    var currentId = 'sfx-'+i+'-item-0';
                    let currentModel = $('#model-'+i+'-item-0').val(); 
                    if(selectedId != currentId && model[0] == currentModel[0] ) {                  
                        $('#' + currentId + ' option[value=' + sfx[0] + ']').detach(); 
                        // if(currentModel[0] == model[0]) {
                            // $('#sfx-'+i+'-item-0').empty();
                        // }
                      
                    }
                }
                  
        }
        function appendParentSFX(index,value) {
            var selectedId = 'sfx-'+index+'-item-0';
            let model = $('#model-'+index+'-item-0').val(); 
            var parentIndex = $("#pfi-items").find(".pfi-items-parent-div").length;
                for(let i=1; i<=parentIndex;i++) 
                { 
                    var currentId = 'sfx-'+i+'-item-0';
                    let currentModel = $('#model-'+i+'-item-0').val(); 
                    if(selectedId != currentId && model[0] == currentModel[0] ) {                  
                        $('#sfx-'+i+'-item-0').append($('<option>', {value: value, text : value})); 
                      
                    }
                }                
        }
        function enableOrDisableAddMoreButton(index) {
            // check any customer is selected or not
            let customer = $('#client_id').val();
            let sfx = $('#sfx-'+index+'-item-0').val();
            if(customer.length > 0 && sfx.length > 0) {
                // check sfx is there the model also will be there
                $('#add-more-'+index).removeClass('disabled');
            }else if(customer.length <= 0 || sfx.length <= 0){
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

