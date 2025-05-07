@extends('layouts.main')
@section('content')
    <style>
        iframe {
            min-height: 400px;
            max-height: 400px;
        }
        .modal-content{
            width: 1000px;
            height: 550px;
        }
        .widthinput
        {
            height:32px!important;
        }
        .error, .custom-error {
            color: #fd625e;
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
    @can('LOI-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit LOI</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                <form action="{{ route('letter-of-indents.update', $letterOfIndent->id) }}" method="POST" enctype="multipart/form-data" 
                         id="form-update">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label"> Country</label>
                                <select class="form-control widthinput" autofocus multiple name="country" id="country" >
                                    <option disabled>Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" {{ $country->id == $letterOfIndent->country_id ? 'selected' : '' }} > {{ $country->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Customer Type</label>
                                <select class="form-control widthinput" name="customer_type"  id="customer-type">
                                    <option value="" disabled>Select Customer Type</option>
                                    <option value={{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL == $letterOfIndent->client->customertype ? 'selected' : ''}}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}
                                    </option>
                                    <option value={{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY == $letterOfIndent->client->customertype ? 'selected' : ''}}>
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}
                                    </option>
                                    <option value={{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT == $letterOfIndent->client->customertype ? 'selected' : ''}} >
                                        {{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Customer</label>
                                <select class="form-control widthinput" multiple name="client_id" id="customer" >
                                    @foreach($possibleCustomers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ $letterOfIndent->client_id == $customer->id ? 'selected' : '' }} > {{ $customer->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                        <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">LOI Date</label>
                                <input type="date" class="form-control widthinput" id="date" name="date"
                                    value="{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('Y-m-d') }}"  max="{{ \Illuminate\Support\Carbon::today()->format('Y-m-d') }}" >
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">LOI Category</label>
                                <select class="form-control widthinput" multiple name="category" id="loi-category">
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
                                <label for="choices-single-default" class="form-label">Dealers</label>
                                <select class="form-control widthinput" disabled  id="dealer" name="dealers" >
                                    <option value="Trans Cars" {{ 'Trans Cars' == $letterOfIndent->dealers ? 'selected' : '' }}>Trans Cars</option>
                                    <option value="Milele Motors" {{ 'Milele Motors' == $letterOfIndent->dealers ? 'selected' : '' }}>Milele Motors</option>
                                </select>
                                <input name="dealers" type="hidden" value="{{ $letterOfIndent->dealers }}" id="dealer-input" >
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Sales Person</label>
                                <select class="form-control widthinput" multiple name="sales_person_id" id="sales_person_id" autofocus>
                                    <option ></option>
                                    @foreach($salesPersons as $salesPerson)
                                        <option value="{{ $salesPerson->id }}" {{ $salesPerson->id == $letterOfIndent->sales_person_id ? 'selected' : ''}}>
                                            {{ $salesPerson->name }}
                                         </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Template Type </label>
                                <select class="form-control widthinput" multiple name="template_type[]" id="template-type">
                                    <option value="trans_cars" {{ in_array('trans_cars', $LOITemplates) ? 'selected' : '' }}
                                        {{ $letterOfIndent->dealers == 'Milele Motors' ? 'disabled' : '' }}>Trans Cars</option>
                                    <option value="milele_cars"  {{ in_array('milele_cars',$LOITemplates) ? 'selected' : '' }}
                                        {{ $letterOfIndent->dealers == 'Trans Cars' ? 'disabled' : '' }}>Milele Cars</option>
                                    <option value="individual" {{ in_array('individual',$LOITemplates) ? 'selected' : '' }}
                                        {{ $letterOfIndent->client->customertype == 'Company' || $letterOfIndent->client->customertype == 'Government' ? 'disabled' : '' }}>Individual</option>
                                    <option value="business" {{ in_array('business',$LOITemplates) ? 'selected' : '' }}
                                        {{ $letterOfIndent->client->customertype == 'Individual' ? 'disabled' : '' }}>Business</option>
                                        <option value="general" {{ in_array('general',$LOITemplates) ? 'selected' : '' }}>General</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Signature</label>
                                <input type="file" id="signature" name="loi_signature" class="form-control widthinput" accept="image/*" >
                            </div>
                        </div>
                    </div>
                    <!-- Customer Documents -->
              
                    <div class="card mb-3 mt-3" id="customer-files">
                        <div class="card-header">
                            <h4 class="card-title">
                                 Customer Documents
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                         
                                <div class="col-lg-4 col-md-6 col-sm-12 text-center sign-div">
                                    <div class="mb-3" id="signature-preview">
                                        @if($letterOfIndent->signature)
                                        <label class="text-center">Signature File</label>
                                            <iframe src="{{ url('/LOI-Signature/'.$letterOfIndent->signature) }}" ></iframe>
                                            <a href="#" class="btn btn-danger text-center mt-2 remove-signature-button"><i class="fa fa-trash"></i> </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row customer-doc-div">
                                    <!-- consider the LOI selected customer passport or any doc and current passport or any doc, it may differ -->
                                @if($isCustomerPassport)
                                
                                    <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-2">
                                        <h6> Passport </h6>
                                        <iframe src="{{ url('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}"></iframe>
                                        <button type="button" hidden id="add-old-passport" class="btn btn-info btn-sm text-center mt-2 add-passport-button"
                                        onclick="addPassportToLOI()" >
                                            Add to LOI </button>
                                        <button type="button" id="remove-old-passport" class="btn btn-danger btn-sm text-center mt-2 remove-passport-button"
                                        onclick="removePassportFromLOI()">
                                        Remove From LOI </button>   
                                    </div>
                                    @if($isCustomerPassport->loi_document_file != $letterOfIndent->client->passport)
                                    
                                        <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-2">
                                            <h6> Latest Passport </h6>
                                            <iframe src="{{ url('storage/app/public/passports/'.$letterOfIndent->client->passport) }}"></iframe>
                                            <button type="button" id="add-new-passport" onclick="addLatestPassportToLOI()" class="btn btn-info btn-sm text-center mt-2">
                                                Add to LOI </button>
                                            <button type="button" id="remove-new-passport" hidden onclick="removeLatestPassportToLOI()" class="btn btn-danger btn-sm text-center mt-2">                                        Remove From LOI </button>               
                                        </div> 
                                    @endif
                                @elseif($letterOfIndent->client->passport)          
                                    <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-2">
                                        <h6> Passport </h6>
                                        <iframe src="{{ url('storage/app/public/passports/'.$letterOfIndent->client->passport) }}"></iframe>
                                        <button type="button" onclick="addPassportToLOI()" class="btn btn-info btn-sm text-center mt-2 add-passport-button">
                                            Add to LOI </button>
                                        <button type="button" hidden onclick="removePassportFromLOI()" class="btn btn-danger btn-sm text-center mt-2 remove-passport-button">
                                        Remove From LOI </button>               
                                    </div>  
                                @endif
                                @if($isCustomerTradeLicense)  
                                    <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-2">
                                        <h6> Trade License </h6>
                                        <iframe src="{{ url('storage/app/public/tradelicenses/'.$isCustomerTradeLicense->loi_document_file) }}"></iframe>
                                        <button type="button" hidden  id="add-old-trade-license" onclick="addTradeDocToLOI()" class="btn btn-info btn-sm text-center mt-2 add-trade-license-button">
                                        Add to LOI </button>    
                                        <button type="button" onclick="removeTradeDocFromLOI()"  id="remove-old-trade-license" class="btn btn-danger btn-sm text-center mt-2 remove-trade-license-button">
                                        Remove From LOI </button>
                                    </div>
                                @if($isCustomerTradeLicense->loi_document_file != $letterOfIndent->client->tradelicense)
                                
                                    <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-2">
                                        <h6> Latest Trade License </h6>
                                        <iframe src="{{ url('storage/app/public/tradelicenses/'.$letterOfIndent->client->tradelicense) }}"></iframe>
                                        <button type="button" id="add-new-trade-license" onclick="addLatestTradeLicenseToLOI()" class="btn btn-info btn-sm text-center mt-2">
                                            Add to LOI </button>
                                        <button type="button" id="remove-new-trade-license" hidden onclick="removeLatestTradeLicenseToLOI()" class="btn btn-danger btn-sm text-center mt-2">                                        Remove From LOI </button>               
                                    </div> 
                                @endif
                                @elseif($letterOfIndent->client->tradelicense)
                                    <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-2">
                                        <h6> Trade License  </h6>
                                        <iframe src="{{ url('storage/app/public/tradelicenses/'.$letterOfIndent->client->tradelicense) }}"></iframe>
                                        <button type="button" class="btn btn-info btn-sm text-center mt-2 add-trade-license-button"
                                        onclick="addTradeDocToLOI()"> Add to LOI </button>    
                                        <button type="button" hidden onclick="removeTradeDocFromLOI()" class="btn btn-danger btn-sm text-center mt-2 remove-trade-license-button">
                                        Remove From LOI </button>     
                                    </div>    
                                @endif
                                @if($customerOtherDocNotAdded->count() > 0 ||  $customerOtherDocAdded->count() > 0)
                                    <h6 class="text-center p-2"> Other Documents </h6>
                                        @foreach($customerOtherDocAdded as $key => $CustomerOtherDoc)
                                        <!-- <iframe src="{{ url('customer-other-documents/'.$CustomerOtherDoc->loi_document_file) }}"></iframe> -->
                                            <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                                                <iframe src="{{ url('customer-other-documents/'.$CustomerOtherDoc->loi_document_file) }}"></iframe>
                                                <button type="button" data-id="{{ $CustomerOtherDoc->id }}" id="remove-customer-doc-{{ $CustomerOtherDoc->id }}" 
                                                class="btn btn-danger btn-sm text-center mt-2 remove-other-cus-doc">
                                                Remove From LOI </button>
                                                <button type="button" hidden class="btn btn-info btn-sm text-center mt-2 add-other-cus-doc"
                                                data-id="{{ $CustomerOtherDoc->id }}" id="add-customer-doc-{{ $CustomerOtherDoc->id }}" >
                                                    Add to LOI </button>
                                            </div>
                                        @endforeach
                                    <!--  refereing the client document table for not added Document -->
                                    @foreach($customerOtherDocNotAdded as $docNotAdded)
                                        <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                                            <iframe src="{{ url('customer-other-documents/'.$docNotAdded->document) }}"></iframe>
                                            <button type="button" onclick="addDocToLOI({{ $docNotAdded->id }})"  class="btn btn-info btn-sm text-center mt-2"
                                            id="add-other-doc-{{ $docNotAdded->id }}" > Add to LOI </button>

                                            <button type="button" hidden onclick="removeDocToLOI({{ $docNotAdded->id }})"
                                            id="remove-other-doc-{{ $docNotAdded->id }}" class="btn btn-danger btn-sm text-center mt-2">
                                            Remove From LOI </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>     
                        </div>
                    </div>
                        <!-- Add so Numbers -->
                    <div class="card" id="soNumberDiv">
                        <div class="card-header">
                            <h4 class="card-title">
                                So Numbers
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row soNumberMain">
                                <div hidden>{{$i=0;}}</div>
                                @foreach($letterOfIndent->soNumbers as $soNumber)
                                <div id="rowIndexCount" hidden value="{{$i+1}}">{{$i=$i+1;}}</div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 soNumberApendHere" id="row-{{$i}}">
                                    <div class="row mt-2">
                                        <div class="col-xxl-9 col-lg-6 col-md-12">
                                            <input id="so_number_{{$i}}" type="text" class="form-control widthinput so_number" name="so_number[{{$i}}]"
                                                placeholder="So Number" value="{{$soNumber->so_number}}"
                                                autocomplete="so_number" oninput=uniqueCheckSoNumber()>
                                            <span id="soNumberError_{{$i}}" class="error is-invalid soNumberError"></span>
                                        </div>
                                        <div class="col-xxl-3 col-lg-1 col-md-1">
                                            <a class="btn btn-sm btn-danger removeSoNumber" data-index="{{$i}}" >
                                            <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-md-12" id="soNumberDivBr">
                                    <a id="addSoNumberBtn" style="float: right;" class="btn btn-sm btn-info">
                                    <i class="fa fa-plus" aria-hidden="true"></i> Add So Numbers</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger m-2 country-validation" role="alert" hidden id="country-comment-div">
                        <span id="country-comment"></span><br>
                    </div>
                    <div class="alert alert-danger m-2 country-validation" role="alert" hidden id="loi-country-validation-div">                       
                        <span class="error" id="validation-error"></span>
                    </div>
                    <div class="card mt-2" >
                            <div class="card-header">
                                <h4 class="card-title">LOI Items</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <label class="form-label">Model</label>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                        <label class="form-label">SFX</label>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                        <label class="form-label">Model Line</label>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                        <label class="form-label">LOI Description</label>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-12">
                                        <label class="form-label">Quantity</label>
                                    </div>
                                    <div class="col-lg-1 col-md-6 col-sm-12">
                                        <label class="form-label">Inventory Qty</label>
                                    </div>
                                </div>
                                <div id="loi-items" >
                                    @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                                        <div class="row Loi-items-row-div" id="row-{{$key+1}}">
                                            <div class="col-lg-2 col-md-6 col-sm-12">
                                                <select class="form-select widthinput text-dark models" multiple data-index="{{$key+1}}" name="models[]" id="model-{{$key+1}}" autofocus>
                                                    <option value="" >Select Model</option>
                                                    @foreach($models as $model)
                                                        <option value="{{ $model->model }}" {{ $letterOfIndentItem->masterModel->model == $model->model ? 'selected' : '' }}>{{ $model->model }}</option>
                                                    @endforeach
                                                </select>
                                                @error('model')
                                                <span>
                                                    <strong >{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                                <select class="form-select widthinput text-dark sfx" multiple  data-index="{{$key+1}}" name="sfx[]" id="sfx-{{$key+1}}" >
                                                    @foreach($letterOfIndentItem->sfxLists as $sfx)
                                                        <option value="{{ $sfx}}" {{$sfx == $letterOfIndentItem->masterModel->sfx ? 'selected' : ''}} >{{ $sfx }}</option>
                                                    @endforeach
                                                </select>
                                                @error('sfx')
                                                <div role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                                <input type="text" readonly placeholder="Model Line"
                                                    class="form-control widthinput text-dark model-lines"
                                                    value="{{$letterOfIndentItem->masterModel->modelLine->model_line ?? ''}}" data-index="{{$key+1}}" id="model-line-{{$key+1}}">

                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                                <input type="text" readonly placeholder="LOI Description"
                                                    class="form-control widthinput text-dark loi-descriptions"
                                                    value="{{$letterOfIndentItem->loi_description}}" data-index="{{$key+1}}" id="loi-description-{{$key+1}}">
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-12">
                                                <input type="number" name="quantity[]" placeholder="Quantity"  maxlength="5" value="{{$letterOfIndentItem->quantity}}" data-index="{{$key+1}}"
                                                    class="form-control widthinput quantities text-dark"
                                                    step="1" oninput="validity.valid||(value='');" min="0" id="quantity-{{$key+1}}">
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-12">
                                                <input type="number" readonly id="inventory-quantity-{{$key+1}}" value="{{$letterOfIndentItem->inventory_quantity}}" data-index="{{$key+1}}" class="form-control widthinput inventory-qty" >
                                                <input type="hidden" name="master_model_ids[]" class="master-model-ids" value="{{$letterOfIndentItem->master_model_id}}" id="master-model-id-{{$key+1}}">
                                            </div>
                                            <div class="col-lg-1 col-md-6 col-sm-12">
                                                <a class="btn btn-sm btn-danger removeButton" id="remove-btn-{{$key+1}}" data-index="{{$key+1}}"  >  <i class="fas fa-trash-alt"></i> </a>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="btn btn-info btn-sm add-row-btn float-end" >
                                            <i class="fas fa-plus"></i> Add LOI Item
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <input type="hidden" name="is_signature_removed" id="is_signature_removed" value="0">
                    <select name="deletedIds[]" id="deleted-docs" hidden="hidden" multiple>
                    </select>
                    <select name="customer_other_documents_Ids[]" id="added-customer-docs" hidden="hidden" multiple>
                    </select>
                    <input type="hidden" value="{{ $isCustomerPassport ? 1 : 0 }}" current-data="{{ $isCustomerPassport ? 1 : 0 }}" name="is_passport_added" id="add-passport-to-loi">
                    <input type="hidden" value="{{ $isCustomerTradeLicense ? 1 : 0 }}" current-data="{{ $isCustomerTradeLicense ? 1 : 0 }}"  name="is_trade_license_added" id="add-trade-license-to-loi">
                    
                    <!-- <input type="hidden" id="other-document-count" value="{{ $customerOtherDocAdded->count() }}" > -->
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary float-end" id="submit-button">Update</button>
                    </div>
                </form>
            </div>
            <input type="hidden" id="is-country-validation-error" value="0">
            <div class="overlay"></div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        var previousSelected = $('#customer-type').val();
        let formValid = true;
        let deletedDocumetIds = [];
        let AddedDocumetIds = [];
        let signature = '{{ $letterOfIndent->signature }}';
        let totalDocumentCount = '{{ $letterOfIndent->LOIDocuments->count() }}';
       
        const signatureFileInput = document.querySelector("#signature");
        const signaturePreviewFile = document.querySelector("#signature-preview");

        signatureFileInput.addEventListener("change", function(event) {
            const files = event.target.files;
            while (signaturePreviewFile.firstChild) {
                signaturePreviewFile.removeChild(signaturePreviewFile.firstChild);
            }
            for (let i = 0; i < files.length; i++)
            {
                const file = files[i];
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                signaturePreviewFile.appendChild(iframe);
            }
        });

            let dealer = '{{ $letterOfIndent->dealers }}';
            showSignatureRemoveButton(dealer);

            $('#loi-category').select2({
                placeholder : 'Select LOI Category',
                allowClear: true,
                maximumSelectionLength: 1
            }).on('change', function() {
                $('#loi-category-error').remove();
            });
            $('#template-type').select2({
                placeholder : 'Select Template Type',
                allowClear: true,
                maximumSelectionLength: 1
            }).on('change', function() {
                $('#template-type-error').remove();
            });

            $('#sales_person_id').select2({
                placeholder : 'Select Sales Person',
                allowClear: true,
                maximumSelectionLength: 1
            });
            $('#date').change(function (){
                checkCountryCriterias();
            });
            $('#country').select2({
                placeholder: 'Select Country',
                allowClear: true,
                maximumSelectionLength: 1,
            }).on('change', function() {
                getCustomers();
                $('.customer-doc-div').html('');
                getModels('all','all');
              
            });
            $('#customer').select2({
                placeholder : 'Select Customer',
                allowClear: true,
                maximumSelectionLength: 1
            }).on('change', function() {
                $('#customer-error').remove();
                checkCountryCriterias();
                let customer = $('#customer').val();
                if(customer.length > 0) {
                    showCustomerDocuments();
                }else{
                    // $('#customer-files').attr('hidden',true);
                    $('.customer-doc-div').html('');
                }
            });

            $('#dealer').change(function () {
                var value = $('#dealer').val();
                $('#dealer-input').val(value);
                showSignatureRemoveButton(value)
                getModels('all','dealer-change');
            
                var confirm = alertify.confirm('You want to choose LOI template again if you are changing the Dealer?',function (e) {
                    if (e) {
                        $('#template-type').val('').trigger('change');
                        if (value == 'Trans Cars') {
                            $('#template-type option[value=milele_cars]').prop('disabled', true);
                            $('#template-type option[value=trans_cars]').prop('disabled', false);

                        } else if (value == 'Milele Motors') {
                            $('#template-type option[value=trans_cars]').prop('disabled', true);
                            $('#template-type option[value=milele_cars]').prop('disabled', false);
                        }
                    }

                }).set({title:"Are You Sure?"});
            });
   
            $('#customer-type').change(function () {
                getCustomers();
               
                let customerType = $('#customer-type').val();
            
                var confirm = alertify.confirm('You want to choose LOI template again if you are changing the Customer Type!',function (e) {
                    if (e) {
                    
                        previousSelected = customerType;
                    
                        $('#template-type').val('').trigger('change');
                        if (customerType == '{{ \App\Models\Clients::CUSTOMER_TYPE_INDIVIDUAL }}') {
                            $('#template-type option[value=business]').prop('disabled', true);
                            $('#template-type option[value=individual]').prop('disabled', false);

                        } else if (customerType == '{{ \App\Models\Clients::CUSTOMER_TYPE_COMPANY }}' || customerType == '{{ \App\Models\Clients::CUSTOMER_TYPE_GOVERMENT }}') {
                            $('#template-type option[value=individual]').prop('disabled', true);
                            $('#template-type option[value=business]').prop('disabled', false);
                        } else {
                            $('#template-type option[value=individual]').prop('disabled', false);
                            $('#template-type option[value=business]').prop('disabled', false);
                        }
                        $('.customer-doc-div').html('');
                       
                    }
                }).set({title:"Are You Sure?"}).set('oncancel', function(closeEvent){ 
                    $('#customer-type').val(previousSelected);
                } );

                checkCountryCriterias();
             
            });


        $('.remove-signature-button').click(function () {
            $('#is_signature_removed').val(1);
            $('#signature-preview').hide();
        });

        $(document.body).on('input', ".quantities", function (e) {
            checkCountryCriterias();
        });
        function showCustomerDocuments() {
            $('#add-passport-to-loi').val(0);
            $('#add-trade-license-to-loi').val(0);
            $('#added-customer-docs').empty();
            $('#deleted-docs').empty();

            let client_id = $('#customer').val();
            let url = '{{ route('loi.customer-documents') }}';
            if(client_id.length > 0) {
                $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    client_id: client_id[0],
                
                },
                 success:function (data){
                    let otherDocuments = data.customer_documents;
                    
                    if(otherDocuments.length > 0 || data.passport_file || data.trade_license_file)
                        {
                            $('#customer-files').attr('hidden',false);
                            if(data.passport_file)
                            {
                                let passportUrl = 'storage/app/public/passports/'+data.passport_file;
                                $('.customer-doc-div').append(`<div class="col-md-4 col-lg-4 text-center">
                                        <h6>Passport</h6>
                                        <iframe src="{{ url('${passportUrl}')}}"  width="500px;" height="300px;"></iframe>
                                        <button type="button"  onclick="addPassportToLOI()" 
                                        class="btn btn-info btn-sm text-center mt-2 add-passport-LOI">
                                        Add to LOI </a>
                                        <button type="button"  hidden onclick="removePassportFromLOI()"
                                         class="btn btn-danger btn-sm text-center mt-2 remove-passport-LOI">
                                            Remove From LOI </a>
                                    </div>
                                    `);  
                            }
                            if(data.trade_license_file)
                            {
                                let tradelicenseUrl = 'storage/app/public/tradelicenses/'+ data.trade_license_file;
                                $('.customer-doc-div').append(`<div class="col-md-4 col-lg-4 text-center">
                                        <h6>Trade License</h6>
                                        <iframe src="{{ url('${tradelicenseUrl}')}}"  width="500px;" height="300px;"></iframe>
                                        <button type="button" onclick="addTradeDocToLOI()" 
                                        class="btn btn-info btn-sm text-center mt-2 add-trade-license-LOI">
                                        Add to LOI </a>
                                        <button type="button" hidden onclick="removeTradeDocFromLOI()"
                                         class="btn btn-danger btn-sm text-center mt-2 remove-trade-license-LOI">
                                            Remove From LOI </a>
                                    </div>
                                    `); 
                                
                            }           
                            $(otherDocuments.length > 0 )
                            {
                                $('.customer-doc-div').append(`<h6 class="text-center mt-2">Other Documents</h6>`);
                                jQuery.each(otherDocuments, function(key,value){
                                    let fileurl = 'customer-other-documents/'+value.document;
                                    let id = value.id;
                                    $('.customer-doc-div').append(`<div class="col-md-4 col-lg-4 text-center">
                                        <iframe src="{{ url('${fileurl}')}}"  width="500px;" height="300px;"></iframe>
                                        <button type="button" id="add-LOI-${id}" onclick="addtoLOI(${id})" class="btn btn-info btn-sm text-center mt-2">
                                        Add to LOI </button>
                                       <button type="button" id="remove-LOI-${id}" onclick="removeFromLOI(${id})" hidden class="btn btn-danger btn-sm text-center mt-2">
                                        Remove From LOI </button>
                                        </div>
                                        `);                       
                                });
                            }
                                     
                        }
                        if(otherDocuments.length <= 0 && data.passort_file && data.trade_license_file)
                        {
                            $('#customer-files').attr('hidden',true);
                            $('.customer-doc-div').html();
                        }                  
                    }
                });
            }
        }

        function checkCountryCriterias() {
            let url = '{{ route('loi-country-criteria.check') }}';
            var customer = $('#customer').val();
            var country = $('#country').val();
            var customer_type = $('#customer-type').val();
            var date = $('#date').val();
            let total_quantities = 0;

            $(".quantities ").each(function(){
                if($(this).val() > 0) {
                    total_quantities += parseInt($(this).val());
                }
            });

                var model_lines = $('.model_lines').val();
                var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
                var selectedModelLineIds = [];
                for(let i=1; i<=totalIndex; i++)
                {
                    var eachSelectedModelLineId = $('#model-line-'+i).val();

                    if(eachSelectedModelLineId) {
                        selectedModelLineIds.push(eachSelectedModelLineId);
                    }
                }
            if(customer.length > 0 && customer_type.length > 0 && date.length > 0) {
              
                $('.overlay').show();
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        loi_date:date,
                        customer_id: customer[0],
                        country_id:country[0],
                        customer_type: customer_type,
                        total_quantities:total_quantities,
                        selectedModelLineIds:selectedModelLineIds
                    },
                    success:function (data) {
                        $('#is-country-validation-error').val(data.error);
                       
                        if(data.comment) {
                            $('#country-comment-div').attr('hidden', false);
                            $('#country-comment').html(data.comment);
                        }
                        else{
                            $('#country-comment-div').attr('hidden', true);
                        }
                        if(data.error == 1) {
                           
                            $('.country-validation').removeClass('alert-success').addClass("alert-danger");
                            $('#loi-country-validation-div').attr('hidden', false);
                           
                        }else{
                           
                            $('.country-validation').removeClass('alert-danger').addClass("alert-success");
                            $('#loi-country-validation-div').attr('hidden', true);
                        }
                       
                        if(data.validation_error) {
                            $('#validation-error').html(data.validation_error);
                            $('#validation-error').attr('hidden', false);
                         
                        }
                        else{
                            $('#validation-error').attr('hidden', true);
                        }

                        $('.overlay').hide();
                    }
                });
            }
        }

        // Cutsomer document making dynamic section

            $('.remove-other-cus-doc').click(function () {
                let id = $(this).attr('data-id');
                $('#remove-doc-'+id).remove();
                deletedDocumetIds.push(id);
                $('#deleted-docs').empty();

                jQuery.each(deletedDocumetIds, function (key, value) {
                    $('#deleted-docs').append('<option value="' + value + '" >' + value+ '</option>');
                    $("#deleted-docs option").attr("selected", "selected");
                });
                $(this).attr('hidden',true);
                $('#add-customer-doc-'+id).attr('hidden', false);
                let type = "subtract";
                updateDocumentCount(type);
            });
            // to undo already added customer doc delete
            $('.add-other-cus-doc').click(function () {
                let id = $(this).attr('data-id');
                deletedDocumetIds = jQuery.grep(deletedDocumetIds, function(value) {
                return value != id;
                });
                $("#deleted-docs option[value='"+id+"']").remove();
                $(this).attr('hidden',true);
                $('#remove-customer-doc-'+id).attr('hidden', false);
                let type = "add";
                updateDocumentCount(type); 
                // other-document-count
                
            });
           
            function addDocToLOI(id) {
                $('#remove-doc-'+id).remove();
                AddedDocumetIds.push(id);
                $('#added-customer-docs').empty();

                jQuery.each(AddedDocumetIds, function (key, value) {
                    $('#added-customer-docs').append('<option value="' + value + '" >' + value+ '</option>');
                    $("#added-customer-docs option").attr("selected", "selected");
                });
               
                $('#add-other-doc-'+id).attr('hidden',true);
                $('#remove-other-doc-'+id).attr('hidden', false);
                let type = "add";
                updateDocumentCount(type); 
            }
            function removeDocToLOI(id) {
                AddedDocumetIds = jQuery.grep(AddedDocumetIds, function(value) {
                return value != id;
                });
                $("#added-customer-docs  option[value='"+id+"']").remove();
                $("#deleted-docs option[value='"+id+"']").remove();
                $('#add-other-doc-'+id).attr('hidden',false);
                $('#remove-other-doc-'+id).attr('hidden', true);
                let type = "subtract";
                updateDocumentCount(type);
            }
            function updateDocumentCount(type) {
                if(type == 'add') {
                    totalDocumentCount = parseInt(totalDocumentCount) + 1;
                }else{
                    if(totalDocumentCount > 0) {
                        totalDocumentCount = parseInt(totalDocumentCount) - 1;                   
                    }
                  }
            }
            function addLatestTradeLicenseToLOI() {
                var confirm = alertify.confirm('Are you sure ?  while adding latest tradeLicense the old tradeLicense will remove by default',function (e) {
                   // if value 2 remove old passport and add latest passport for the loi
                //    if value 1 chcek passport is alredy existing or not , if not existing create new loi document
                // if value 0 remove all the passport
                    $('#add-trade-license-to-loi').val(2);
                    $('#add-old-trade-license').addClass('disabled');
                    $('#remove-old-trade-license').addClass('disabled');

                    $('#add-new-trade-license').attr('hidden', true);
                    $('#remove-new-trade-license').attr('hidden', false);

                }).set({title:"Alert !"})
            }
            function removeLatestTradeLicenseToLOI() {
                var confirm = alertify.confirm('Are you sure ? while removing latest tradeLicense the old tradeLicense will add by default!',function (e) {
                   // if value 2 remove old passport and add latest passport for the loi
                //    if value 1 chcek passport is alredy existing or not , if not existing create new loi document
                // if value 0 remove all the passport
                    let value =$('#add-trade-license-to-loi').attr("current-data");
                    $('#add-trade-license-to-loi').val(value);
                    $('#add-old-trade-license').removeClass('disabled');
                    $('#remove-old-trade-license').removeClass('disabled');

                    $('#add-new-trade-license').attr('hidden', false);
                    $('#remove-new-trade-license').attr('hidden', true);

                }).set({title:"Alert !"})
            }
            function addLatestPassportToLOI() {
                var confirm = alertify.confirm('Are you sure ?  while adding latest passport the old passport will remove by default',function (e) {
                   // if value 2 remove old passport and add latest passport for the loi
                //    if value 1 chcek passport is alredy existing or not , if not existing create new loi document
                // if value 0 remove all the passport
                    $('#add-passport-to-loi').val(2);
                    $('#add-old-passport').addClass('disabled');
                    $('#remove-old-passport').addClass('disabled');

                    $('#add-new-passport').attr('hidden', true);
                    $('#remove-new-passport').attr('hidden', false);

                }).set({title:"Alert !"})
               
            }
            
            function removeLatestPassportToLOI() {
                var confirm = alertify.confirm('Are you sure ? while removing latest passport the old passport will add by default!',function (e) {
                   // if value 2 remove old passport and add latest passport for the loi
                //    if value 1 chcek passport is alredy existing or not , if not existing create new loi document
                // if value 0 remove all the passport
                    let value =$('#add-passport-to-loi').attr("current-data");
                    $('#add-passport-to-loi').val(value);
                    $('#add-old-passport').removeClass('disabled');
                    $('#remove-old-passport').removeClass('disabled');

                    $('#add-new-passport').attr('hidden', false);
                    $('#remove-new-passport').attr('hidden', true);

                }).set({title:"Alert !"})
               
            }

            function addPassportToLOI() {
                $('#add-passport-to-loi').val(1);
                $('.add-passport-button').attr('hidden', true);
                $('.remove-passport-button').attr('hidden', false);
                let type = 'add';
                updateDocumentCount(type);
                $('#add-passport-to-loi').attr("current-data",1);
            }
            function removePassportFromLOI() {
                $('#add-passport-to-loi').val(0);
                $('.add-passport-button').attr('hidden', false);
                $('.remove-passport-button').attr('hidden', true);
                let type = 'subtract';
                updateDocumentCount(type);
                $('#add-passport-to-loi').attr("current-data",0);
            }
            function addTradeDocToLOI() {
                $('#add-trade-license-to-loi').val(1);
                $('.add-trade-license-button').attr('hidden', true);
                $('.remove-trade-license-button').attr('hidden', false);
                let type = 'add';
                updateDocumentCount(type);
            }
            function removeTradeDocFromLOI() {
                $('#add-trade-license-to-loi').val(0);
                $('.add-trade-license-button').attr('hidden', false);
                $('.remove-trade-license-button').attr('hidden', true);
                let type = 'subtract';
                updateDocumentCount(type);
            }


            ////////////////////////////////////
            var LOICount = '{{ $letterOfIndentItems->count() }}';

            for(var i=1;i<=LOICount;i++) {
                $('#model-'+i).select2({
                    placeholder: 'Select Model',
                    allowClear: true,
                    maximumSelectionLength: 1
                }).on('change', function() {
                    $(this).valid();
                });
                $('#sfx-'+i).select2({
                    placeholder : 'Select SFX',
                    allowClear: true,
                    maximumSelectionLength: 1
                }).on('change', function() {
                    $(this).valid();
                });
               
            }

            function getCustomers() {
                $('.overlay').show();
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
                            var selectedId = '{{ $letterOfIndent->client_id }}';
                            $('#customer').append('<option value="' + value.id + ' " >' + value.name + '</option>');
                            checkCountryCriterias();
                        });
                        $('.overlay').hide();
                    }
                });
            }


        $("#form-update").validate({
            ignore: [],
            rules: {
                client_id: {
                    required: true,
                },
                category: {
                    required: true,
                },
                customer_type: {
                    required: true,
                },
                date: {
                    required: true,
                },
                dealers:{
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
                "quantity[]": {
                    required: true
                },
                "template_type[]":{
                    required:true
                },
                
                loi_signature: {
                    required:function(element) {
                        return $("#template-type").val() != 'general' && signature == '';
                    },
                    extension: "png|jpeg|jpg|svg",
                      maxsize:5242880  
                }
            },
                messages: {
                    
                    loi_signature:{
                        extension: "Please upload Image file format (png,jpeg,jpg,svg)"
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('custom-error');
                    var name = element.attr("name");
                    if (name === "country" || name === "client_id" || name === "category" || 
                        name.match(/\bmodels\[\]\b/) || name.match(/\bsfx\[\]\b/) || name.match(/\btemplate_type\[\]\b/)) {
                        if (element.data('select2')) {
                            error.insertAfter(element.next('.select2'));
                        } else {
                            error.insertAfter(element.next('.select2'));
                        }
                    } else {
                        error.insertAfter(element.next('.select2'));
                    }
                }

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

        var index = 1;
        $('.add-row-btn').click(function() {
            var index = $("#loi-items").find(".Loi-items-row-div").length + 1;

            var newRow = `
                <div class="row Loi-items-row-div" id="row-${index}">
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <select class="form-select widthinput text-dark models" multiple name="models[]" data-index="${index}" id="model-${index}" autofocus>
                            <option value="" >Select Model</option>
                            @foreach($models as $model)
                                <option value="{{ $model->model }}">{{ $model->model }}</option>
                            @endforeach
                        </select>
                        @error('model')
                            <span>
                                <strong >{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                     <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                        <select class="form-select widthinput text-dark sfx" multiple name="sfx[]"  data-index="${index}" id="sfx-${index}" >
                            <option value="">Select SFX</option>
                        </select>
                        @error('sfx')
                            <div role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                          <input type="text" readonly placeholder="Model Line" class="form-control widthinput text-dark model-lines"
                           data-index="${index}" id="model-line-${index}">

                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                        <input type="text" readonly placeholder="LOI Description"
                        class="form-control widthinput text-dark loi-descriptions" data-index="${index}" id="loi-description-${index}" >
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <input type="number" name="quantity[]" placeholder="Quantity" maxlength="5" value="" class="form-control widthinput text-dark quantities"
                               step="1" oninput="validity.valid||(value='');" min="0" data-index="${index}" id="quantity-${index}">
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <input type="number" readonly id="inventory-quantity-${index}" data-index="${index}" value="" class="form-control widthinput inventory-qty" >
                          <input type="hidden" name="master_model_ids[]" class="master-model-ids" id="master-model-id-${index}">
                    </div>
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <a class="btn btn-sm btn-danger removeButton" id="remove-btn-${index}" data-index="${index}" >  <i class="fas fa-trash-alt"></i> </a>
                    </div>
                </div>
                    `;
            $('#loi-items').append(newRow);

            $('#model-' + index).select2({
                placeholder: 'Select Model',
                allowClear: true,
                maximumSelectionLength: 1
            });
            $('#sfx-' + index).select2({
                placeholder: 'Select SFX',
                allowClear: true,
                maximumSelectionLength: 1
            });
            let type = 'add-new';
            getModels(index,type);
        });

        $(document.body).on('click', ".removeButton", function (e) {
            var rowCount = $("#loi-items").find(".Loi-items-row-div").length;
            if(rowCount > 1) {

                var indexNumber = $(this).attr('data-index');
                var modelLine = $('#model-line-'+indexNumber).val();
                var model = $('#model-'+indexNumber).val();
                var sfx = $('#sfx-'+indexNumber).val();
               
                if(model[0]) {
                    appendModel(indexNumber,model[0]);
                }
                if(sfx[0]) {
                    appendSFX(indexNumber,model[0],sfx[0]);
                }

                $(this).closest('#row-'+indexNumber).remove();

                $('.Loi-items-row-div').each(function(i){
                    var index = +i + +1;
                    $(this).attr('id', 'row-'+index);
                    $(this).find('.models').attr('data-index', index);
                    $(this).find('.models').attr('id', 'model-'+index);
                    $(this).find('.sfx').attr('data-index', index);
                    $(this).find('.sfx').attr('id', 'sfx-'+index);
                    $(this).find('.loi-descriptions').attr('data-index', index);
                    $(this).find('.loi-descriptions').attr('id', 'loi-description-'+index);
                    $(this).find('.model-lines').attr('data-index', index);
                    $(this).find('.model-lines').attr('id', 'model-line-'+index);
                    $(this).find('.quantities').attr('data-index', index);
                    $(this).find('.quantities').attr('id', 'quantity-'+index);
                    $(this).find('.inventory-qty').attr('data-index', index);
                    $(this).find('.inventory-qty').attr('id', 'inventory-quantity-'+index);
                    $(this).find('.master-model-ids').attr('id', 'master-model-id-'+index);
                    $(this).find('.removeButton').attr('data-index', index);
                    $(this).find('.removeButton').attr('id', 'remove-btn-'+index);

                    $('#model-'+index).select2
                    ({
                        placeholder: 'Select Model',
                        maximumSelectionLength:1,
                        allowClear: true
                    });
                    $('#sfx-'+index).select2
                    ({
                        placeholder: 'Select SFX',
                        maximumSelectionLength:1,
                        allowClear: true
                    });
                  
                });
                enableDealer();
                checkCountryCriterias();

            }else{
                var confirm = alertify.confirm('You are not able to remove this row, Atleast one LOI Item Required',function (e) {
                }).set({title:"Can't Remove LOI Item"})

            }
        })

        $(document.body).on('select2:select', ".models", function (e) {
            let index = $(this).attr('data-index');
            $('#model-'+index+'-error').remove();
            getSfx(index);
            $('#dealer').attr("disabled", true);
        });
        $(document.body).on('select2:select', ".sfx", function (e) {
            let index = $(this).attr('data-index');
            $('#sfx-'+index+'-error').remove();
            getLOIDescription(index);
            var value = e.params.data.text;
            hideSFX(index, value);
         
        });
       
        $(document.body).on('select2:unselect', ".sfx", function (e) {
            let index = $(this).attr('data-index');

            $('#loi-description-'+index).val("");
            $('#model-line-'+index).val("");
            $('#master-model-id-'+index).val("");
            $('#inventory-quantity-'+index).val("");
            var model = $('#model-'+index).val();
            var sfx = e.params.data.id;
            appendSFX(index,model[0],sfx);
            $('#quantity-'+index).val("");

        });
        $(document.body).on('select2:unselect', ".models", function (e) {
            let index = $(this).attr('data-index');
            var sfx = $('#sfx-'+index).val();
            var model = e.params.data.id;
            appendSFX(index,model,sfx[0]);
            appendModel(index,model);
            enableDealer();

            $('#sfx-'+index).empty();
            $('#model-line-'+index).val("");
            $('#loi-description-'+index).val("");
            $('#master-model-id-'+index).val("");
            $('#inventory-quantity-'+index).val("");
            $('#quantity-'+index).val("");

        });

        function showSignatureRemoveButton(value) {
            if(value == 'Trans Cars') {
                $('.remove-signature-button').attr('hidden', false);
            }else{
                $('.remove-signature-button').attr('hidden', true);
            }
        }
        function getModels(index,type) {
            $('.overlay').show();
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
            let dealer = $('#dealer').val();
            var country = $('#country').val();
            var selectedModelIds = [];
            for(let i=1; i<=totalIndex; i++)
            {
                var eachSelectedModelId = $('#master-model-id-'+i).val();

                if(eachSelectedModelId) {
                    selectedModelIds.push(eachSelectedModelId);
                }
            }
           
            $.ajax({
                url:"{{route('demand.getMasterModel')}}",
                type: "GET",
                data:
                    {
                        selectedModelIds: selectedModelIds,
                        dealer:dealer,
                        country_id:country[0]
                    },
                dataType : 'json',
                success: function(data) {
                    myarray = data;

                    var size = myarray.length;
                    if (size >= 1) {
                        let modelDropdownData = [];
                        $.each(data,function(key,value)
                        {
                            modelDropdownData.push
                            ({

                                id: value.model,
                                text: value.model
                            });
                        });
                        if(type == 'add-new') {
                            $('#model-' + index).html("");
                            $('#model-' + index).select2({
                                placeholder: 'Select Model',
                                allowClear: true,
                                data: modelDropdownData,
                                maximumSelectionLength: 1,
                            });
                        }else{
                            for(let i=1; i<=totalIndex; i++)
                            {
                                $('#model-' + i).html("");
                                $('#model-' + i).select2({
                                    placeholder: 'Select Model',
                                    allowClear: true,
                                    data: modelDropdownData,
                                    maximumSelectionLength: 1,
                                });
                            }
                        }
                    }
                    $('.overlay').hide();
                }
            });
        }
        function getSfx(index) {
            $('.overlay').show();
            let model = $('#model-'+index).val();
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

            var selectedModelIds = [];
            for(let i=1; i<=totalIndex; i++)
            {
                var eachSelectedModelId = $('#master-model-id-'+i).val();
                if(eachSelectedModelId) {
                    selectedModelIds.push(eachSelectedModelId);
                }
            }

            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model[0],
                    selectedModelIds:selectedModelIds,
                    module: 'LOI',
                },
                success:function (data) {
                    $('#inventory-quantity-'+index).val(0);
                    $('#sfx-'+index).empty();
                    $('#loi-description-'+index).val("");
                    $('#sfx-'+index).html('<option value=""> Select SFX </option>');

                    jQuery.each(data, function(key,value){
                        $('#sfx-'+index).append('<option value="'+ value +'">'+ value +'</option>');
                    });
                    $('.overlay').hide();
                }
            });
        }
      
        function getLOIDescription(index) {
            $('.overlay').show();
            let model = $('#model-'+index).val();
            let sfx = $('#sfx-'+index).val();
            let dealer = $('#dealer').val();

            let url = '{{ route('demand.get-loi-description') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    sfx: sfx[0],
                    model:model[0],
                    dealer:dealer,
                    module: 'LOI',
                },
                success:function (data) {
                    $('#loi-description-'+index).val("");
                    let quantity = data.quantity;
                    let modelId = data.master_model_id;
                    var LOIDescription = data.loi_description;

                    $('#inventory-quantity-'+index).val(quantity);
                    $('#loi-description-'+index).val(LOIDescription);
                    $('#master-model-id-'+index).val(modelId);
                    $('#model-line-'+index).val(data.model_line);
                    $('.overlay').hide();
                    checkCountryCriterias();
                }
            });
        }

        function appendSFX(index,unSelectedmodel,sfx){
           var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

           for(let i=1; i<=totalIndex; i++)
           {
            var model = $('#model-'+i).val();
               if(i != index && unSelectedmodel == model[0] ) {  
                   // chcek the model is same as unselected model,
                   $('#sfx-'+i).append($('<option>', {value: sfx, text : sfx}));     
               }
           }
       }
       function hideSFX(index, value) {
         
         var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
         let model = $('#model-'+index).val();        
            for(let i=1; i<=totalIndex; i++)
            {
                let currentmodel = $('#model-'+i).val();               
                if(i != index && currentmodel == model[0]) {
                   
                    var currentId = 'sfx-' + i;
                    $('#' + currentId + ' option[value=' + value + ']').detach();       
                }
            }
        }

        function appendModel(index,unSelectedmodel){
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;

            for(let i=1; i<=totalIndex; i++)
            {
                if(i != index) {
                    let model = $('#model-'+i).val();
        
                    // if(unSelectedmodel == model[0] ) {
                        // chcek this option value alredy exist in dropdown list or not.
                        var currentId = 'model-' + i;
                        var isOptionExist = 'no';
                        $('#' + currentId +' option').each(function () {

                            if (this.text == unSelectedmodel) {
                                isOptionExist = 'yes';
                                return false;
                            }
                        });
                        if(isOptionExist == 'no'){
                            $('#model-'+i).append($('<option>', {value: unSelectedmodel, text : unSelectedmodel}))

                        }
                    // }
                }
            }
        }
        function enableDealer() {
            // check any model year is selected or not
            var totalIndex = $("#loi-items").find(".Loi-items-row-div").length;
            var selectedModels = [];
            for(let i=1; i<=totalIndex; i++)
            {
                var model = $('#model-'+i).val();
                if(model[0]) {
                    selectedModels.push(model[0])
                }
            }
            if(selectedModels.length <= 0) {
                $('#dealer').attr("disabled", false);
            }
        }
        $("#addSoNumberBtn").on("click", function ()
	       {
	           var index = $(".soNumberMain").find(".soNumberApendHere").length + 1;
	           $(".soNumberMain").append(`
	                               <div class="col-xxl-4 col-lg-6 col-md-12 soNumberApendHere" id="row-${index}">
	                               <div class="row mt-2">
	                               <div class="col-xxl-9 col-lg-6 col-md-12">
	                                   <input id="so_number_${index}" type="text" class="form-control widthinput so_number" name="so_number[${index}]"
	                                   placeholder="So Number" value="{{ old('so_number') }}"
	                                   autocomplete="so_number" oninput=uniqueCheckSoNumber()>
	                                   <span id="soNumberError_${index}" class="error is-invalid soNumberError"></span>
	                               </div>
	                               <div class="col-xxl-3 col-lg-1 col-md-1 add_del_btn_outer">
	                                   <a class="btn btn-sm btn-danger removeSoNumber" data-index="${index}" >
	                                       <i class="fas fa-trash-alt"></i>
	                                   </a>
	                               </div>
	                               </div>
	                               </div>
	           `);
	       });
	       $(document.body).on('click', ".removeSoNumber", function (e)
	       {
            var indexNumber = $(this).attr('data-index');
    
            $(this).closest('#row-'+indexNumber).remove();
            $('.soNumberApendHere').each(function(i) {
                var index = +i + +1;
                $(this).attr('id','row-'+index);
                $(this).find('.so_number').attr('name', 'so_number['+ index +']');
                $(this).find('.so_number').attr('id', 'so_number_'+index);
                $(this).find('.removeSoNumber').attr('data-index',index);
                $(this).find('.soNumberError').attr('id', 'soNumberError_'+index);
              
            });
            uniqueCheckSoNumber();
	   });

       function uniqueCheckSoNumber() {
            var totalIndex = $(".soNumberMain").find(".soNumberApendHere").length;
            var isValid = [];
            for(var j = 1; j <= totalIndex; j++)
            {
                soNumberInput = $('#so_number_'+j).val();
                if(soNumberInput != '')
                {
                    var existingSoNumbers = [];
                    for(var m = 1; m <= totalIndex; m++)
                    {
                        if(m != j)
                        {
                            var soNumberInputOther = '';
                            var soNumberInputOther = $('#so_number_'+m).val();
                            existingSoNumbers.push(soNumberInputOther);
                        }
                    }
                    if(existingSoNumbers.includes(soNumberInput))
                    {
                        $msg = "So number is already exist";
                        isValid.push(false);
                        showSoNumberError($msg,j);
                    }
                    else
                    {
                        $msg = "";
                        removeSoNumberError($msg,j);
                    }
                }
            }
            if(isValid.includes(false))
            {
                formValid = false;
            }else{
            
                formValid = true;
            }  
        }
        $('#submit-button').click(function (e) {
            e.preventDefault();
            let isValidCountryCheck = $('#is-country-validation-error').val();
            let ispassportAdded = $('#add-passport-to-loi').val();
            let isTradeLicenseAdded = $('#add-trade-license-to-loi').val();
            
            uniqueCheckSoNumber();
            if(ispassportAdded != 0 || isTradeLicenseAdded == 1 || totalDocumentCount > 0) {
                if (formValid == true && isValidCountryCheck == 0) {
                    if($("#form-update").valid()) {
                        $('#form-update').unbind('submit').submit();
                    }
                }else{
                    e.preventDefault();
                }
            }else{
                var confirm = alertify.confirm('Atleast one Customer Document Required! If customer document is not showing please add Customer documents in customer master data',function (e) {
                }).set({title:"Error !"});
                e.preventDefault();
            }            
        });

        function showSoNumberError($msg,i)
	{
	    document.getElementById("soNumberError_"+i).textContent=$msg;
	    document.getElementById("so_number_"+i).classList.add("is-invalid");
	    document.getElementById("soNumberError_"+i).classList.add("paragraph-class");
      
	}
	function removeSoNumberError($msg,i)
	{
	    document.getElementById("soNumberError_"+i).textContent="";
	    document.getElementById("so_number_"+i).classList.remove("is-invalid");
	    document.getElementById("soNumberError_"+i).classList.remove("paragraph-class");
	}
    </script>
@endpush

