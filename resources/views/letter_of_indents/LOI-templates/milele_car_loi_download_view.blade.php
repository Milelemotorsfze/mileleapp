
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        /* @page { size: A4; } */
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
            left: {{ $width }}px;
            /*bottom: 50px;*/
            z-index: 1;
        }
        #so-details td {
            border: none;
            padding: 5px;
            font-size: 12px;
        }
        #so-items td, th{
            border: 1px solid #1c1b1b;
            /* text-align: center; */
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
         .page_break { page-break-before: always; }
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

        <b><p style="padding-left: 5px;font-weight: 600px;padding-top: 10px">Milele Motors FZCO</p></b>
        <table id="so-details">
            <tr >
                <td class="left"  style="padding-top:0px;">VAT TRN - 100057588400003</td>
                <td  style="padding-top:0px;"></td>
                <td class="last" style="padding-right: 20px;padding-top:0px;color:#D3D3D3">S.O NO. <span class="hide">1234567790898233</span></td>
            </tr>
            <tr>
                <td class="left"  style="padding-top:0px;">Ras al khor 3, Yard 11 - DAZ</td>
                <td  style="padding-top:0px;"></td>
                <td class="last"  style="padding-top:0px;"> <span style="color:#D3D3D3"> Date </span>
                    <span>
                            {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('M d Y')}}
                        </span>
                </td>
            </tr>
            <tr>
                <td  style="padding-top:0px;">Dubai, U.A.E</td>
                <td  style="padding-top:0px;"></td>
                <td class="last"  style="padding-top:0px;color:#D3D3D3">Customer ID <span class="hide">123455555</span></td>
            </tr>
            <tr>
                <td class="left"  style="padding-top:0px;">+971 43235991</td>
                <td  style="padding-top:0px;"></td>
                <td class="last"  style="padding-top:0px;"> <span style="color:#D3D3D3"> Sales Order Type </span> Sales Of Motor Vehicle</td>
            </tr>
            <tr>
                <td>
                    <span style="margin-right: 50px;padding-right: 20px;color:#D3D3D3"> To </span>
                    <!-- <span  style="list-style: none;" > -->
                        <span >{{ strtoupper($letterOfIndent->client->name ?? '') }}</span>
                       <span style="display: list-item;list-style: none;padding-left: 35px;margin-left: 55px">{{ strtoupper($letterOfIndent->client->country->name ?? '') }} </span>
                    <!-- </span> -->
                </td>
            </tr>
            <tr>
                <td style="padding:0px;">
                    <span style="margin-right: 55px;padding-right: 30px"> </span>
                    <span style="background-color: black;font-size: 35px;color: black">zxtestusewordtests</span>
                </td>
            </tr>
        </table>
        </br>
        <table id="so-items">
            <tr class="hide" style="color: #FFFFFF;text-align:center">
                <th>QUANTITY</th>
                <th>DESCRIPTION</th>
                <th width="100px">UNIT PRICE</th>
                <th width="100px">LINE TOTAL </th>
            </tr>
            @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                <tr>
                    <td style="text-align:center">{{$letterOfIndentItem->quantity}}</td>
                    <td style="text-align:left">
                        {{ $letterOfIndentItem->masterModel->milele_loi_description ?? ''}}
                        @if($key == $letterOfIndentItems->count() - 3 && $letterOfIndentItems->count() > 6)
                            <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 140px; height: 130px;"></img>
                        @endif
                    </td>
                    <td class="hide" style="border: none">3</td>
                    <td class="hide" style="border: none">3</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>CNF - SHIPMENT AND TRANSPORTATION
                    @if($letterOfIndentItems->count() <= 6)
                        <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 140px; height: 130px;"></img>
                    @endif
                </td>
                <td class="hide" style="border: none"></td>
                <td class="hide" style="border: none"></td>
            </tr>
                @if($letterOfIndentItems->count() <= 6)
                    @for($i=0;$i<=5;$i++)
                        <tr>
                            <td></td>
                            <td></td>
                            <td ></td>
                            <td ></td>
                        </tr>
                    @endfor
                 @endif

            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                <td colspan="2" style="border: none;font-size: 14px;padding:0px">Name :
                    <span style="margin-left: 3px" >
                        {{ $letterOfIndent->client->name ?? '' }}
                    </span>
                </td>

                <td style="border: none;text-align: end;padding:0px">SUBTOTAL</td>
                <td class="hide" style="border: none;padding:0px" ></td>
            </tr>
            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF" id="date-div">
                <td colspan="2" style="border: none;font-size: 14px;padding:0px">Date :
                <span style="margin-left: 3px"> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y') }}</span>
                </td>

                <td style="border: none;text-align: end;padding:0px">SALES VAT</td>
                <td class="hide" style="border: none;padding:0px" ></td>
            </tr>
            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                <td colspan="2" style="border: none;padding:0px">
                    <!-- <span style="margin-left: 3px;margin-bottom: 10px"> -->
                        @if($letterOfIndent->signature)
                            <img src="{{ public_path('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 60px;width: 140px">
                        @endif 
                    <!-- </span> -->
                </td>

                <td style="border: none;text-align: end;padding:0px">TOTAL</td>
                <td style="background-color: #000000;border: none;padding:0px" ></td>
              
            </tr>
            <!-- <tr>
                 @if($letterOfIndent->signature)
                    <img src="{{ public_path('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 60px;width: 140px">
                @endif
             </tr> -->
           
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
@if(!empty($imageFiles))
        <div class="page_break"></div>
        <div class="row">
            @foreach($imageFiles as $imageFile)
            <img src="{{ public_path($imageFile) }}" class="mt-2">
            @endforeach
        </div>
    @endif

</body>
</html>



