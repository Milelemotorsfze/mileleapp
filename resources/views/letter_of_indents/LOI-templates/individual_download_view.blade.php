
<!DOCTYPE html>
<html>
<head>
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            padding: 10px;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
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
    </style>

</head>
<body>
<div class="row" id="fullpage">
    <div class="content">

        <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
        <p> <span class="fw-bold">Subject: </span> Letter of Intent to Purchase Vehicle</p>
        <p style="margin-bottom: 0px;"> <span class="fw-bold" >Full Name: </span> {{ ucfirst($letterOfIndent->customer->name ?? '') }} </p>
        <p style="margin-top: 0px;"> <span class="fw-bold">Address: </span> Dubai, UAE</p>

        <p>Dear Milele Motors,</p>

        <p>I, ({{ ucfirst($letterOfIndent->customer->name) ?? 'Customer Name'}}) am writing this letter to express my sincere intention to purchase the following models from your company.</p>
        <h3 class="fw-bold" style="margin-bottom: 15px;text-decoration: underline">Requirements:</h3>
        <div style="list-style-type: none;margin-right: 15px;font-size: 14px;">
            @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                <li>{{$key + 1}}.&nbsp;
                    <span class="fw-bold">Model Description:</span>
                    @if($letterOfIndentItem->LOI->dealers == 'Trans Cars')
                        {{ $letterOfIndentItem->masterModel->transcar_loi_description ?? '' }}
                    @else
                        {{ $letterOfIndentItem->masterModel->milele_loi_description ?? '' }}
                    @endif
                </li>
                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fw-bold">Type: </span> Brand New</li>
                <li style="margin-bottom: 10px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fw-bold">Quantity: </span>
                    {{ $letterOfIndentItem->quantity ?? '' }}
                </li>
            @endforeach
        </div>
        <p>
            I understand that this Letter of Intent is not legally binding and merely expresses my genuine interest in
            purchasing your vehicle under the specified terms. A formal Purchase Agreement will be prepared once this letter is accepted. Furthermore,
            I would like to declare that the purchased automobile(s) will be registered within the designated country,
            and this acquisition is not intended for resale purposes.
        </p>
        <p style="margin-bottom: 5px;">Sincerely,</p>
        <p> {{ ucfirst($letterOfIndent->customer->name ?? '') }} </p>
        @if($letterOfIndent->signature)
            <img src="{{ public_path('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 70px;width: 150px">
        @endif

    </div>
</div>
</body>
</html>


