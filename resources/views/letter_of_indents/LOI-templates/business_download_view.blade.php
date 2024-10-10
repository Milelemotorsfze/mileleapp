
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            font-size:13px;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        table ,td,th{
            /* font-family: arial, sans-serif; */
            border-collapse: collapse;
            /* padding:10px; */
            font-size:12px;
           
            border: 1px solid #1c1b1b;
            /*background-color: #0a58ca;*/
        }
    
        .last{
            text-align: right;
            /*margin-left: 20px;*/
        }
        .page_break { page-break-before: always; }

    </style>

</head>
<body>
<div class="row" id="fullpage">
    <div class="content">
        <h3 class="center" style="text-decoration: underline;color: black;font-size: 16px;">Letter of Intent for Automotive Purchase</h3>
        <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
        <p style="margin-bottom: 0px;"> <span style="font-weight: bold">Company Name: </span> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
        <p>  <span style="font-weight: bold;margin-right:50px;">Address: </span> {{  strtoupper($letterOfIndent->country->name) ?? ''}} </p>
        <p>Dear Sir/Madam,</p>

        <p>I am writing on behalf of {{ strtoupper($letterOfIndent->client->name ?? '') }}  to formally convey our intent to procure automobile(s) from Milele Motors.
            Please find our company's automotive requirements listed with specifications below.</p>
        <table style="width:100%">
            <tr>
                <th style="text-align:center;padding:0px">Brand</th>
                <th style="text-align:center;padding:0px;width:70%">Model Type</th>
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
                            {{ $letterOfIndentItem->masterModel->milele_loi_description ?? '' }}
                        @endif
                      </td>
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
        <p style="margin-bottom:0px;">Sincerely,</p>
        <p> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
        @if($letterOfIndent->signature)
            <img src="{{ public_path('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 70px;width: 150px">
        @endif
    </div>
</div>
<div class="page_break"></div>
    @if($letterOfIndent->LOIDocuments->count() > 0)
        <div class="row">
            @foreach($documents as $document) 
                @if($document->is_passport == 1) 
                    <img src="{{ public_path('storage/app/public/passports/'.$document->loi_document_file) }}" class="mt-2"></iframe>
                @elseif($document->is_trade_license == 1)
                    <img src="{{ public_path('storage/app/public/tradelicenses/'.$document->loi_document_file) }}" class="mt-2"></iframe>
                @else
                    <img src="{{ public_path('customer-other-documents/'.$document->loi_document_file) }}" class="mt-2"></iframe>
                @endif
            @endforeach
        </div>
    @endif
</body>
</html>


