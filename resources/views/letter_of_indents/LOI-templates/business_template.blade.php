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
            /*width: 100%;*/
            border: 1px solid #1c1b1b;
            /*background-color: #0a58ca;*/

        }
        .last{
            text-align: end;
            margin-left: 20px;
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
    </style>

    <div class="container" style="padding-bottom: 0px;">
        <div class="content" style="padding-right: 0px;padding-left: 0px;margin-top: 10px">
            <form action="{{ route('letter-of-indents.generate-loi') }}" id="form-create">
                <input type="hidden" name="height" id="total-height" value="">
                <input type="hidden" name="width" id="width" value="">
                <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                <input type="hidden" name="type" value="business">
                <input type="hidden" name="download" value="1">

                <div class="row mb-4 mt-4 mb-4">
                    @if($isCustomerPassport)
                        <div class="col-md-3 col-lg-3">
                            <label>Passport</label>
                            <select class="form-control widthinput" name="passport_order" id="passport-order" >
                                <option value="1" {{ $isCustomerPassport->order == '1' ? 'selected' : ""}}>Order 1</option>
                                <option value="2" {{ $isCustomerPassport->order == '2' ? 'selected' : ""}}>Order 2</option>
                                <option value="3" {{ $isCustomerPassport->order == '3' ? 'selected' : ""}}>Order 3</option>
                            </select>
                        </div>
                    @endif
                    @if($isCustomerTradeLicense)
                        <div class="col-md-3 col-lg-3">
                            <label>Trade License</label>
                            <select class="form-control widthinput" name="trade_license_order" id="trade-license-order" >
                                <option value="1" {{ $isCustomerTradeLicense->order == '1' ? 'selected' : ""}}>Order 1</option>
                                <option value="2" {{ $isCustomerTradeLicense->order == '2' ? 'selected' : ""}}>Order 2</option>
                                <option value="3" {{ $isCustomerTradeLicense->order == '3' ? 'selected' : ""}}>Order 3</option>
                            </select>
                        </div>
                    @endif
                    @if($customerOtherDocAdded->count() > 0)
                        <div class="col-md-3 col-lg-3">
                            <label>Other Document</label>
                            <select class="form-control widthinput" name="other_document_order" id="other-document-order" >
                                <option value="1" {{ $customerOtherDocAdded[0]->order == '1' ? 'selected' : ""}}>Order 1</option>
                                <option value="2" {{ $customerOtherDocAdded[0]->order == '2' ? 'selected' : ""}} >Order 2</option>
                                <option value="3" {{ $customerOtherDocAdded[0]->order == '3' ? 'selected' : ""}}>Order 3</option>
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3 col-lg-3 mt-4">
                        <a  class="btn  btn-info float-end " style="margin-left: 2px;" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" >
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        <button type="submit" class="btn btn-primary" id="submit-button"> Download <i class="fa fa-download"></i></button>
                        </button>
                    </div>
                </div>
            </form>
            <div class="border-outline">
                <h4 class="center" style="text-decoration: underline;color: black">Letter of Intent for Automotive Purchase</h4>
                <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
                <p style="margin-bottom: 0px;"> <span style="font-weight: bold">Company Name: </span> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
                <p>  <span style="font-weight: bold;margin-right:50px;">Address: </span>  {{  strtoupper($letterOfIndent->country->name) ?? ''}}</p>
                <p>Dear Sir/Madam,</p>

                <p>I am writing on behalf of {{ strtoupper($letterOfIndent->client->name ?? '') }}  to formally convey our intent to procure automobile(s) from Milele Motors.
                    Please find our company's automotive requirements listed with specifications below.</p>
                <table class="table table-responsive">
                    <tr >
                        <th style="text-align:center;padding:0px" >Brand</th>
                        <th style="padding:0px;text-align:center;">Model Type</th>
                        <th style="text-align:center;padding:0px">Quantity</th>
                    </tr>
                    @foreach($letterOfIndentItems as $letterOfIndentItem)
                        <tr>
                            <td style="text-align:center;padding:0px;font-style:italic;">
                                @if($letterOfIndentItem->masterModel->variant()->exists())
                                    {{ strtoupper($letterOfIndentItem->masterModel->variant->brand->brand_name) ?? ''}}
                                @endif
                            </td>
                            <td style="padding-top:0px;padding-bottom:0px;font-style:italic;">
                                @if($letterOfIndentItem->LOI->dealers == 'Trans Cars')
                                    {{ $letterOfIndentItem->masterModel->transcar_loi_description ?? '' }}
                                @else
                                    {{ str_replace('- SPECIFICATION ATTACHED IN APPENDIX','',$letterOfIndentItem->masterModel->milele_loi_description ?? '' ) }}
                                @endif
                            </td >
                            <td style="text-align:center;padding:0px;font-style:italic;">{{ $letterOfIndentItem->quantity }}</td>
                        </tr>
                    @endforeach
                </table>
                <br>
                <p>
                    As a business, we are committed to complying with all relevant laws and regulations governing vehicle registration,
                    taxation, and operational use. The purchased automobiles will be used exclusively for our corporate operations and will not be resold.
                </p>
            
                <p>
                    We look forward to your acknowledgment of this Letter of Intent and the subsequent
                    steps necessary to conclude this transaction professionally and in accordance with the law.
                </p>
                <p style="margin-bottom: 5px;">Sincerely,</p>
                <p> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
                @if($letterOfIndent->signature)
                    <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 70px;width: 150px">
                @endif
                <br>
                @if($letterOfIndent->LOIDocuments->count() > 0)
                      <h5 class="fw-bold text-center">Customer Document</h5>
                    @if($isCustomerPassport)
                        <iframe src="{{ url('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}"   height="500px;"></iframe>
                    @endif
                    @if($isCustomerTradeLicense)
                        <iframe src="{{ url('storage/app/public/tradelicenses/'.$isCustomerTradeLicense->loi_document_file) }}"  height="500px;"></iframe>
                    @endif
                    @foreach($customerOtherDocAdded as $letterOfIndentDocument)
                        <div class="mt-3" >
                        <iframe src="{{ url('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}"   height="500px;"></iframe>
                        </div>
                    @endforeach
                @endif
            </div>


    </div>
</div>
<script type="text/javascript">
        $('#submit-button').click(function (e) {
            e.preventDefault();

            let val1 = $('#passport-order').val();
            let val2 = $('#trade-license-order').val();
            let val3 = $('#other-document-order').val();
            // check the form
            if (val1 === val2 || val1 === val3 || val2 === val3) {
                alertify.error('You are not allowed to choose same orders');
                e.preventDefault();
            }else{
                $('#form-create').unbind('submit').submit();
            }
        
        });
    </script>
@endsection


