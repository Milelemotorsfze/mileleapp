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
            <form action="{{ route('letter-of-indents.generate-loi') }}">
                <input type="hidden" name="height" id="total-height" value="">
                <input type="hidden" name="width" id="width" value="">
                <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                <input type="hidden" name="type" value="business">
                <input type="hidden" name="download" value="1">

                <div class="text-end mb-3">
                    <a  class="btn  btn-info float-end " style="margin-left: 10px;" href="{{ url()->previous() }}" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    <button type="submit" class="btn btn-primary "> Download <i class="fa fa-download"></i></button>
                    </button>
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
                    @foreach($letterOfIndent->LOIDocuments as $key => $letterOfIndentDocument)
                        <div  id="remove-doc-{{$letterOfIndentDocument->id}}">
                            <iframe src="{{ url('/LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  height="500px;" ></iframe>
                        </div>
                    @endforeach
                @endif
            </div>


    </div>
</div>

@endsection


