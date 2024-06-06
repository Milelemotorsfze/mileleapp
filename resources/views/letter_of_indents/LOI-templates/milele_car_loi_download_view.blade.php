
<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: A4; }
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
        .overlay-image {
            position: absolute;
            {{--top: {{ $height }}px;--}}
            {{--left: {{ $width }}px;--}}
            /*bottom: 50px;*/
            z-index: 1;
        }
        #so-details td {
            border: none;
            padding: 5px;
        }
        #so-items td, th{
            border: 1px solid #1c1b1b;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
        .left {
            text-align: left;
        }
        .last{
            text-align: end;
            margin-left: 20px;
        }
        .hide{
            background-color: #000000;
            color: #0f0f0f;
        }
        .header{
            background-color: #000000;
            padding-right: 10px;
            padding-left: 10px;
        }.
         #fullpage{height: 0;}
    </style>


</head>
<body>
<div class="row" id="fullpage">
    <div class="content">
        <div class="header" >
            <table>
                <tr>
                    <td>
                        <img src="{{ public_path('images/milele_car_logo.png') }}" width="350px" height="100px" ><span class="logo-txt"></span>
                    </td>
                    {{--                <td style="color: #FFFFFF">--}}
                    {{--                    <h1 style="margin-bottom: 1px;font-size: 38px">Milele Motors</h1>--}}
                    {{--                    <h6 style="margin-top: 1px">Procuring,Sourcing & Stocking Motor Vehicles</h6>--}}
                    {{--                </td>--}}
                    <td style="text-align: right;margin-left: 10px;">
                        <h1 style="color: #FFFFFF; font-size: 35px;">SALES ORDER</h1>
                    </td>
                </tr>
            </table>
        </div>

        <b><p style="padding-left: 5px;font-weight: bold">Milele Motors FZCO</p></b>
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
                <td class="last">Customer ID: <span class="hide">123455555</span></td>
            </tr>
            <tr>
                <td class="left">+971 43235991</td>
                <td></td>
                <td class="last">Sales Order Type: Sales Of Motor Vehicle</td>
            </tr>
            <tr>
                <td>
                    <span style="margin-right: 50px;padding-right: 50px"> To </span>
                    <span  style="list-style: none;" >
                        <span style="display: list-item;padding-left: 30px;margin-left: 55px">{{ $letterOfIndent->customer->name ?? '' }}</span>
                       <span style="display: list-item;padding-left: 30px;margin-left: 55px">{{ $letterOfIndent->customer->country->name ?? '' }} </span>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="margin-right: 55px;padding-right: 30px"> </span>
                    <span style="background-color: black;font-size: 35px;color: black">zxdfdsiediri</span>
                </td>
            </tr>
        </table>
        </br>
        </br>
        <table id="so-items" >
            <tr class="hide" style="color: #FFFFFF">
                <th>QUANTITY</th>
                <th>DESCRIPTION</th>
                <th width="100px">UNIT PRICE</th>
                <th width="100px">LINE TOTAL </th>
            </tr>
            @foreach($letterOfIndentItems as $letterOfIndentItem)
                <tr>
                    <td>{{$letterOfIndentItem->quantity}}</td>
                    <td>
                        {{ $letterOfIndentItem->masterModel->milele_loi_description ?? ''}}
                    </td>
                    <td class="hide" style="border: none">3</td>
                    <td class="hide" style="border: none">3</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>CNF - SHIPMENT AND TRANSPORTATION
                 <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 140px; height: 130px;"></img>

                </td>
                <td class="hide" style="border: none"></td>
                <td class="hide" style="border: none"></td>
            </tr>
            <?php
//            if($letterOfIndentItems->count() >= 5) {
//                $count = 8;
//            }else
//            {
                $count = 6;
//            }
            ?>

            @for($i=0;$i<$count;$i++)
                <tr>
                    <td></td>
                    <td>
{{--                        <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 140px; height: 130px;"></img>--}}
                    </td>
                    <td class="hide" style="border: none"></td>
                    <td class="hide" style="border: none"></td>
                </tr>
            @endfor

            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                <td colspan="2" style="border: none;font-size: 14px">Name :
                    <span style="margin-left: 3px" >
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
            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF" id="date-div">
                <td colspan="2" style="border: none;font-size: 14px;">Date :
                <span style="margin-left: 3px"> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y') }}</span>
{{--                    <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 140px; height: 130px;"></img>--}}
                </td>
{{--                <td style="border: none">--}}

{{--                </td>--}}
                <td style="border: none;text-align: end">SALES VAT</td>
                <td class="hide" style="border: none" ></td>
            </tr>
            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                <td colspan="2" style="border: none;">
                    <span style="margin-left: 3px;margin-bottom: 10px">
                        @if($letterOfIndent->signature)
                            <img src="{{ public_path('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 60px;width: 140px">
                        @endif
                    </span>
                </td>

                <td style="border: none;text-align: end">TOTAL</td>
                <td style="background-color: #000000;border: none" ></td>
            </tr>
        </table>
    </div>
</div>
</br>
</br>

<div style="text-align: center;position: absolute;bottom: 0;margin-left: 200px">
    Make all checks payable to Milele Motors FZCO
    <p style="font-weight: bold">THANK YOU FOR YOUR BUSINESS</p>
</div>
</div>

</body>
</html>



