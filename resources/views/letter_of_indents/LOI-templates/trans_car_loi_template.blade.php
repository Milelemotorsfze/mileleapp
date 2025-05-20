@extends('layouts.main')
@section('content')
    <style>
        @page { size: A4; }
        @media only screen and (min-device-width: 1200px)
        {
            .container{
                max-width: 803px; !important;
            }
        }
        table {
            font-family: arial, sans-serif;
            /*border-collapse: collapse;*/

            width: 100%;
        }
        #vehicle-details td, th{
            border: 2px solid #1c1b1b;
            text-align: left;
            padding-left: 8px;

        }
        #basic-details td, th{
            padding-left: 8px;

        }
        .bg-light-grey{
            background-color: #ababaf;
            color: #ababaf;
        }
        p {
          margin-bottom: 0px;
         }
        .overlay-image {
            position: absolute;
            z-index: 1;
        }
        .border-outline {
            border: 1px solid #0f0f0f;
            padding: 10px !important;
        }
        iframe{
            height: 400px;
        }
    </style>
     @can('LOI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('LOI-list');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">LOI Template</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="container mb-4 "  >
                        <form action="{{ route('letter-of-indents.generate-loi') }}" class="mb-3" id="form-create">
                            <input type="hidden" name="width" id="width" value="">
                            <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                            <input type="hidden" name="type" value="trans_cars">

                            <input type="hidden" name="download" value="1">
                                <div class="row justify-content-center">
                                    @if($isCustomerPassport)
                                        <div class="col-md-6 col-lg-3  text-center">
                                            <label>Passport</label>
                                            <select class="form-control widthinput validate-input" name="passport_order" >
                                                <option value="1" {{ $isCustomerPassport->order == '1' ? 'selected' : ""}}>Order 1</option>
                                                <option value="2" {{ $isCustomerPassport->order == '2' ? 'selected' : ""}}>Order 2</option>
                                                <option value="3" {{ $isCustomerPassport->order == '3' ? 'selected' : ""}}>Order 3</option>
                                            </select>
                                        </div>
                                    @endif
                                    @if($isCustomerTradeLicense)
                                        <div class="col-md-6 col-lg-3  text-center">
                                            <label>Trade License</label>
                                            <select class="form-control widthinput validate-input" name="trade_license_order" >
                                                <option value="1" {{ $isCustomerTradeLicense->order == '1' ? 'selected' : ""}}>Order 1</option>
                                                <option value="2" {{ $isCustomerTradeLicense->order == '2' ? 'selected' : ""}}>Order 2</option>
                                                <option value="3" {{ $isCustomerTradeLicense->order == '3' ? 'selected' : ""}}>Order 3</option>
                                            </select>
                                        </div>
                                    @endif
                                    @if($customerOtherDocAdded->count() > 0)
                                        <div class="col-md-6 col-lg-3  text-center">
                                            <label>Other Document</label>
                                            <select class="form-control widthinput validate-input" name="other_document_order"  >
                                                <option value="1" {{ $customerOtherDocAdded[0]->order == '1' ? 'selected' : ""}}>Order 1</option>
                                                <option value="2" {{ $customerOtherDocAdded[0]->order == '2' ? 'selected' : ""}} >Order 2</option>
                                                <option value="3" {{ $customerOtherDocAdded[0]->order == '3' ? 'selected' : ""}}>Order 3</option>
                                            </select>
                                        </div>
                                    @endif
                                </div>
                                <div id="error-message" style="color:red;"></div>
                                <div class="row justify-content-center mt-2">
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary" id="submit-button"> Download <i class="fa fa-download"></i></button>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            
                        <div  class="container border-outline" style="margin-right: 50px;margin-left: 50px;" >
                            <div id="full-page">
                                <img src="{{  url('images/trans_car_logo.png') }}"  alt="logo" style="width: 100px;height: 100px; margin-left: auto;display: block;
                                margin-right: auto;">
                                </span>
                                <h4 class="fw-bold text-center pt-3">TRANSCARS</h4>
                                <p class="text-center ">Convention Center, JAFZA, Dubai, United Arab Emirates </p>
                                <p class="text-center">Email: <span style="text-decoration: underline;">general@transcars.net</span></p>
                                <p class="text-center ">Website:<span style="text-decoration: underline;"> www.transcars.net </span></p>
                                <h4 class="fw-bold text-center" style="margin-top: 20px;">QUOTATION </h4>
                                <p class="fw-bold text-center">VAT TRN NO. 100057598400003</p>
                                <br>
                                <div class="card border-dark border-2 mb-2" >
                                    <table id="basic-details" >
                                        <tr>
                                            <td class="fw-bold">CUSTOMER:</td>
                                            <td class="fw-bold">{{ strtoupper($letterOfIndent->client->name) }}</td>
                                            <td class="fw-bold">QUOTATION NUMBER: </td>
                                            <td><span class="bg-light-grey ">53426725967498</span></td>
                                        </tr>
                                        <tr>
                                            <td>Alias: </td>
                                            <td ><span class="bg-light-grey ">53426725967498vfdfvdvdvfvd</span></td>
                                            <td>Date: </td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Contact:</td>
                                            <td><span class="bg-light-grey ">53426725967498vfdfvdvdvfvd</span></td>
                                            <td>Salesperson:</td>
                                            <td><span class="bg-light-grey">53426725967498</span></td>
                                        </tr>
                                        <tr>
                                            <td>Address: </td>
                                            <td><span class="bg-light-grey">53426725967498vfdfvdvdvfvd</span></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>{{ strtoupper($letterOfIndent->country->name ?? '') }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <br>
                                </div>
                                <table id="vehicle-details" >
                                    <tr class="bg-light-grey text-dark fw-bold"">
                                    <td colspan="5">1. GENERAL INFORMATION</td>
                                    </tr>
                                    <tr class="bg-light-grey text-dark fw-bold"">
                                    <th width="50px">S No:</th>
                                    <th>1A. VEHICLE</th>
                                    <th>QTY</th>
                                    <th>PRICE </th>
                                    <th>AMOUNT (USD)</th>
                                    </tr>

                                    @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                                        <tr >
                                            <td >{{ $key + 1 }}</td>
                                            <td>
                                                <p style="font-weight: bold">
                                                    {{ $letterOfIndentItem->masterModel->transcar_loi_description ?? '' }}
                                                </p>
                                                <p>Make: {{$letterOfIndentItem->masterModel->variant->brand->brand_name ?? ''}}</p>
                                                <p>Model: {{$letterOfIndentItem->masterModel->model_year}} Brand New Zero km</p>
                                            </td>
                                            <td >{{$letterOfIndentItem->quantity}}</td>
                                            <td class="bg-light-grey " ></td>
                                            <td class="bg-light-grey "></td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-light-grey text-dark fw-bold">
                                    <td colspan="5"  >1B. LOGISTICS </td>
                                    </tr>
                                    <tr>
                                        <td>  <img src="{{ url('images/LOI/transcar_seal.png') }}" class="overlay-image" style="height: 125px;width: 140px"> </td>
                                        <td>CNF - Shipment Method </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <p class="fw-bold">GROSS TOTAL (USD) </p>
                                            <p class="fw-bold">DISCOUNT APPLIED </p>
                                            <p class="fw-bold">VAT NOT APPLICABLE (EXPORT BILL) </p>
                                            <p>NET TOTAL VALUE (USD) TOTAL </p>
                                            <p>INVOICE VALUE (AED) </p>
                                        
                                        </td>
                                        <td class="bg-light-grey text-dark fw-bold"> </td>
                                        <td class="bg-light-grey text-dark fw-bold" ></td>
                                        <td class="bg-light-grey text-dark fw-bold" ></td>
                                    </tr>
                                </table>
                                <div class="pb-2 ">
                                    <div style="text-align: center;bottom: 0">
                                        Note: If you have queries or concerns please feel free to contact us on the information provided above.
                                    </div>
                                    <p id="test"></p>
                            </div>
                        </div>
                        <span class="pt-5">
                        @if($letterOfIndent->LOIDocuments->count() > 0)
                            <h5 class="fw-bold text-center">Customer Document</h5>
                            @if($isCustomerPassport)
                                <iframe src="{{ url('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}" ></iframe>
                            @endif
                            @if($isCustomerTradeLicense)
                                <iframe src="{{ url('storage/app/public/tradelicenses/'.$isCustomerTradeLicense->loi_document_file) }}"></iframe>
                            @endif
                            @foreach($customerOtherDocAdded as $letterOfIndentDocument)
                                <div class="mt-3" >
                                <iframe src="{{ url('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}"></iframe>
                                </div>
                            @endforeach
                        @endif
                        </span>
                        
                    </div>
                </div>
            </div>
        @endif
    @endcan
    <script type="text/javascript">
        
        const values = ["240", "260", "280","310"];
        const random = Math.floor(Math.random() * values.length);
        var imageWidth = values[random];
    
        $('#width').val(imageWidth);
        $('.overlay-image').css('left', imageWidth+'px');
       
        function isArrayUnique(arr) {
            return new Set(arr).size === arr.length;
        }
        $('#form-create').on('submit', function(e) {
            e.preventDefault(); // Prevent form submission

            let values = [];
            $('.validate-input').each(function() {
            let value = $(this).val();
           
            if (!value) {
                alertify.confirm("All fields must be filled.",function (e) {
                }).set({title:"Error"});
            }else{
                values.push(value);
            }
        });
        if (isArrayUnique(values)) {
            this.submit();
        } else {
            alertify.confirm( "Each field must have a unique Order",function (e) {
            }).set({title:"Error"});
        }
    });

    </script>
@endsection


