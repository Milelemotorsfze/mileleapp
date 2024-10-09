
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            /* padding: 10px; */
            text-align:justify;
            margin-top:40px;
            margin-right:20px !important;
            margin-left:10px !important;
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
            font-size:12px;
            /*background-color: #0a58ca;*/

        }
        .last{
            text-align: right;
            /*margin-left: 20px;*/
        }
        .fw-bold{
            font-weight: bold;
        }
        p{
            font-size: 14px;
        }
        .page_break { page-break-before: always; }
    </style>

</head>
<body>
<div class="row" id="fullpage">
    <div class="content">

        <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
        <p> <span class="fw-bold">Subject: </span> Letter of Intent to Purchase Vehicle</p>
        <p style="margin-bottom: 0px;"> <span class="fw-bold" >Full Name: </span> {{ strtoupper($letterOfIndent->client->name ?? '') }} </p>
        <p style="margin-top: 0px;"> <span class="fw-bold">Address: </span> {{  strtoupper($letterOfIndent->country->name) ?? ''}} </p>

        <p>Dear Milele Motors,</p>

        <p>I, ({{ strtoupper($letterOfIndent->client->name) ?? ''}}) am writing this letter to express my sincere intention to purchase the following model(s) from your company.</p>
        <h3 class="fw-bold" style="margin-bottom: 15px;text-decoration: underline">Requirements:</h3>

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
                <!-- <tr>
                    <td class="fw-bold" style="padding-left: 20px;">  Type: </td>
                    <td>Brand New</td>
                </tr> -->
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
            <img src="{{ public_path('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 70px;width: 150px">
        @endif
    </div>
    <div class="page_break"></div>
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
</div>
    
</body>
</html>


