
<!DOCTYPE html>
<html>
<head>
    <style>
        /*@page { size: 700pt }*/
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
            width: 100%;
            padding:10px;
            border: 1px solid #1c1b1b;
            /*background-color: #0a58ca;*/

        }
        .last{
            text-align: right;
            /*margin-left: 20px;*/
        }

    </style>

</head>
<body>
<div class="row" id="fullpage">
    <div class="content">
        <h3 class="center" style="text-decoration: underline;color: black;font-size: 16px;">Letter of Intent for Automotive Purchase</h3>
        <p class="last">Date:{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}} </p>
        <p style="margin-bottom: 0px;"> <span style="font-weight: bold">Company Name: </span> {{ $letterOfIndent->customer->company_name ?? '' }} </p>
        <p>  <span style="font-weight: bold">Address: </span>  Dubai, UAE</p>
        <p>Dear Sir/Madam,</p>

        <p>I am writing on behalf of {{ $letterOfIndent->customer->company_name ?? '' }}  to formally convey our intent to procure automobile(s) from Milele Motors.
            Please find our company's automotive requirements listed with specifications below.</p>
        <table>
            <tr>
                <th>Brand</th>
                <th>Model Type</th>
                <th>Quantity</th>
            </tr>
            @foreach($letterOfIndentItems as $letterOfIndentItem)
                <tr>
                    <td>
                        @if($letterOfIndentItem->masterModel->variant()->exists())
                            {{ strtoupper($letterOfIndentItem->masterModel->variant->brand->brand_name) ?? ''}}
                      @endif
                    </td>
                    <td>
                        @if($letterOfIndentItem->masterModel->variant()->exists())
                            {{ strtoupper($letterOfIndentItem->masterModel->variant->master_model_lines->model_line) ?? ''}}
                        @endif
                      </td>
                    <td>{{ $letterOfIndentItem->quantity }}</td>

                </tr>
            @endforeach
        </table>
        <br>
        <p>
            As a business, we are committed to complying with all relevant laws and regulations governing vehicle registration,
            taxation, and operational use. The purchased automobiles will be used exclusively for our corporate operations and will not be resold.
        </p>
        <br>
        <p>
            We look forward to your acknowledgment of this Letter of Intent and the subsequent
            steps necessary to conclude this transaction professionally and in accordance with the law.
        </p>
        <p style="margin-bottom:0px;">Sincerely,</p>
        <p> {{ $letterOfIndent->customer->company_name ?? '' }} </p>
    </div>
</div>
</body>
</html>


