<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: 700pt }
        .content{
            font-family: arial, sans-serif;
            background-color: #f6f5f5;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #so-details td {
            border: none;
            padding: 5px;
        }
        #so-items td, th{
            border: 1px solid #1c1b1b;
            text-align: left;
            padding: 8px;
        }
        .left {
            text-align: left;
        }
        .last{
            text-align: end;
            margin-left: 20px;
        }
        .hide{
            background-color: #0f0f0f;
            color: #0f0f0f;
        }
        .header{
            background-color: #0f0f0f;
        }.
    </style>
</head>
<body>
    <div class="row">
        <div class="content">
            <div class="header">
                <table>
                    <tr>
                        <td>
                           <img src="{{ public_path('bgm-min.png') }}" width="300px" height="100px" ><span class="logo-txt"></span>
                        </td>
                        {{--                <td style="color: #FFFFFF">--}}
                        {{--                    <h1 style="margin-bottom: 1px;font-size: 38px">Milele Motors</h1>--}}
                        {{--                    <h6 style="margin-top: 1px">Procuring,Sourcing & Stocking Motor Vehicles</h6>--}}
                        {{--                </td>--}}
                        <td class="last">
                            <h1 style="color: #FFFFFF; font-size: 35px;">SALES ORDER</h1>
                        </td>
                    </tr>
                </table>
            </div>

            <b><p style="padding-left: 5px">Milele Motors FZCO</p></b>
            <table id="so-details">
                <tr>
                    <td class="left">VAT TRN - 100057588400003</td>
                    <td></td>
                    <td class="last" style="padding-right: 20px">SO NO: <span class="hide">1234567790898233</span></td>
                </tr>
                <tr>
                    <td class="left">Ras al khor 3, Yard 11 - DAZ</td>
                    <td></td>
                    <td class="last" >Date :
                        <span>
                            {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="">Dubai, U.A.E</td>
                    <td></td>
                    <td class="last ">Customer ID: <span class="hide">123455555</span></td>
                </tr>
                <tr>
                    <td class="left">+97143235991</td>
                    <td></td>
                    <td class="last">Sales Order Type: Sales Of Motor Vehicle</td>
                </tr>
                <tr>
                    <td>
                        <span style="margin-right: 50px;padding-right: 50px"> To </span>
                        <span>
                             @if($letterOfIndent->customer->type == \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL)
                                {{ $letterOfIndent->customer->name }}
                            @else
                                {{ $letterOfIndent->customer->company ?? ''}}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="margin-right: 55px;padding-right: 60px"> </span>
                        <span style="background-color: black;font-size: 35px">zxdfdsgedgrg</span>
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <table id="so-items" >
                <tr class="hide" style="color: #FFFFFF">
                    <th>QUANTITY</th>
                    <th>DESCRIPTION</th>
                    <th>UNIT PRICE</th>
                    <th>LINE TOTAL </th>
                </tr>
                @foreach($letterOfIndentItems as $letterOfIndentItem)
                    <tr>
                        <td>{{$letterOfIndentItem->quantity}}</td>
                        <td>
                            {{ strtoupper($letterOfIndentItem->steering) }}, {{ strtoupper($letterOfIndentItem->Variant->brand->brand_name) ?? ''}},
                            {{ strtoupper($letterOfIndentItem->variant_name) }},{{ strtoupper($letterOfIndentItem->Variant->engine_type) ?? ''}}
                        </td>
                        <td class="hide">3</td>
                        <td class="hide">3</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td>{{ $letterOfIndent->shipment_method }} SHIPMENT AND TRANSPORTATION</td>
                    <td></td>
                    <td></td>
                </tr>

                @for($i=0;$i<=10;$i++)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                <tr style="background-color: #FFFFFF">
                    <td style="border: none;">Name</td>
                    <td style="border: none">
                        <span >
                             @if($letterOfIndent->customer->type == \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL)
                                {{ $letterOfIndent->customer->name }}
                            @else
                                {{ $letterOfIndent->customer->company ?? ''}}
                            @endif
                        </span>
                    </td>
                    <td style="border: none;text-align: end">SUBTOTAL</td>
                    <td class="hide" style="border: none" ></td>
                </tr>
                <tr style="background-color: #FFFFFF">
                    <td style="border: none">Date</td>
                    <td style="border: none">
                        {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y') }}
                    </td>
                    <td style="border: none;text-align: end">SALES VAT</td>
                    <td class="hide" style="border: none" ></td>
                </tr>
                <tr style="background-color: #FFFFFF">
                    <td style="border: none">Signature</td>
                    <td style="border: none"></td>
                    <td style="border: none;text-align: end">TOTAL</td>
                    <td style="background-color: #0f0f0f;border: none" ></td>
                </tr>
            </table>
        </div>
    </div>
{{--    <div class="row">--}}
        <div style="text-align: center;position: absolute;bottom: 0;padding-left: 370px;margin-left: 400px">
            Make all checks payable to Milele Motors FZCO
           <p style="font-weight: bold">THANK YOU FOR YOUR BUSINESS</p>
        </div>
{{--    </div>--}}
    </div>

</body>
</html>


