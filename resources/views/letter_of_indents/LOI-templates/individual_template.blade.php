@extends('layouts.main')
@section('content')

    <style>
        @page { size: 700pt }
        .content{
            font-family: arial, sans-serif;

        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        table ,td,th{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            /* padding:10px; */
            border: none;
            /*background-color: #0a58ca;*/

        }

        .last{
            text-align: end;
            margin-left: 20px;
        }
        .fw-bold{
            font-weight: bold;
        }
        .border-outline {
            border: 1px solid #0f0f0f;
            padding: 10px !important;
        }
        @media only screen and (min-device-width: 1200px)
        {
            .container{
                max-width: 850px; !important;
            }
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
                <div class="container" style="padding-bottom: 0px;">
                    <div class="content" style="padding-right: 0px;padding-left: 0px;margin-top: 10px">
                        <form action="{{ route('letter-of-indents.generate-loi') }}" id="form-create">
                            <input type="hidden" name="height" id="total-height" value="">
                            <input type="hidden" name="width" id="width" value="">
                            <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                            <input type="hidden" name="type" value="individual">
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
                                        <select class="form-control widthinput validate-input" name="other_document_order">
                                            <option value="1" {{ $customerOtherDocAdded[0]->order == '1' ? 'selected' : ""}}>Order 1</option>
                                            <option value="2" {{ $customerOtherDocAdded[0]->order == '2' ? 'selected' : ""}} >Order 2</option>
                                            <option value="3" {{ $customerOtherDocAdded[0]->order == '3' ? 'selected' : ""}}>Order 3</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div class="row justify-content-center mt-2">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary" id="submit-button"> Download <i class="fa fa-download"></i></button>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="border-outline mt-3">
                            <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
                            <p> <span class="fw-bold">Subject: </span> Letter of Intent to Purchase Vehicle</p>
                            <p style="margin-bottom: 0px;"> <span class="fw-bold">Full Name: </span> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
                            <p> <span class="fw-bold">Address: </span>  {{  strtoupper($letterOfIndent->country->name) ?? ''}} </p>
                            <p>Dear Milele Motors,</p>
                            <p>I, ({{ ucfirst($letterOfIndent->client->name) ?? 'Customer Name'}}) am writing this letter to express my sincere intention to purchase the following model(s) from your company.</p>
                            <h5 class="fw-bold" style="margin-bottom: 15px;text-decoration: underline;color: black">Requirements:</h5>
                            <table  style="width:100%;">
                                @foreach($letterOfIndentItems as $key => $letterOfIndentItem)             
                                    <tr>
                                        <td> {{$key + 1}}.&nbsp; <span class="fw-bold">Model Description:</span></td>
                                        <td  style="width:80%">
                                            @if($letterOfIndentItem->LOI->dealers == 'Trans Cars')
                                                {{ $letterOfIndentItem->masterModel->transcar_loi_description ?? '' }}
                                            @else
                                                {{ str_replace('- SPECIFICATION ATTACHED IN APPENDIX','',$letterOfIndentItem->masterModel->milele_loi_description ?? '' )  }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" style="padding-left: 20px;"> Quantity:  </td>
                                        <td> {{ $letterOfIndentItem->quantity ?? '' }}</td>
                                    </tr>
                            
                                @endforeach
                            </table>

                            <p style="margin-top:20px;">
                                I understand that this Letter of Intent is not legally binding and merely expresses my genuine interest in
                                purchasing your vehicles under the specified terms. A formal Purchase Agreement will be prepared once this letter is accepted. Furthermore,
                                I would like to declare that the purchased automobile(s) will be registered within the designated country,
                                and this acquisition is not intended for resale purposes.
                            </p>
                            <p style="margin-bottom: 5px;">Sincerely,</p>
                            <p> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
                            @if($letterOfIndent->signature)
                                <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 70px;width: 150px">
                                @endif
                                </p>
                                <br>
                                @if($letterOfIndent->LOIDocuments->count() > 0)
                                    <h5 class="fw-bold text-center">Customer Document</h5>
                                    @if($isCustomerPassport)
                                        <iframe src="{{ url('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}"></iframe>
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
                        </div>

                    </div>
                </div>
            </div>
        @endif
    @endcan
    <script type="text/javascript">
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


