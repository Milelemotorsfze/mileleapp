
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        @page { margin: 15px; size:A4}
        .content{
            font-family: "Gill Sans", sans-serif !important;
            background-color: #f6f5f5;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        table {
            font-family: "Gill Sans", sans-serif !important;
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
            /* padding: 5px; */
            font-size: 10px;
        }
        #so-items td, th{
            border: 1px solid #D3D3D3;
            /* text-align: center; */
            /* padding: 8px; */
            font-size: 10px !important;
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
            /* padding-left: 10px; */
        }.
        .country-name{
            display: list-item;
            list-style: none;
            padding-left: 35px;
            margin-left: 55px;
            font-weight:600px;
            font-size:12px;
        }
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
                        <img src="{{ public_path('images/milele_car_logo.png') }}" width="350px" height="70px" >
                        <!-- <span class="logo-txt"></span> -->
                    </td>
                   
                    <td style="text-align: right;margin-left: 10px;">
                        <sapn style="color: #FFFFFF; font-size: 35px;padding-right:20px">SALES ORDER</sapn>
                    </td>
                </tr>
            </table>
        </div>

        <p style="padding-left: 5px;font-weight: 600px;padding-top: 10px;font-size:14px;">Milele Motors FZCO</p>
        <table id="so-details">
            <tr >
                <td class="left"  style="padding-top:0px;">VAT TRN - 100057588400003</td>
                <td  style="padding-top:0px;"></td>
                <td class="last" style="padding-right: 20px;padding-top:0px;color:#D3D3D3;padding-left:70px;">S.O NO.
                     <span class="hide">123456gggg7790898233</span></td>
            </tr>
            <tr>
                <td class="left"  style="padding-top:0px;">Ras al khor 3, Yard 11 - DAZ</td>
                <td  style="padding-top:0px;"></td>
                <td class="last"  style="padding-top:0px;padding-left:80px;"> <span style="color:#D3D3D3"> DATE </span>
                    <span class="left">
                            {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('M d, Y')}}
                        </span>
                </td>
            </tr>
            <tr>
                <td  style="padding-top:0px;">Dubai, U.A.E</td>
                <td  style="padding-top:0px;"></td>
                <td class="last"  style="padding-top:0px;color:#D3D3D3;padding-left:35px;">CUSTOMER ID
                     <span class="hide">12345622227790898233</span>
                                        
                    </td>
            </tr>
            <tr>
                <td class="left"  style="padding-top:0px;">+971 43235991</td>
                <td  style="padding-top:0px;"></td>
                <td class="last"  style="padding-top:0px;"> <span style="color:#D3D3D3"> SALES ORDER TYPE </span>
                 Sales Of Motor Vehicle</td>
            </tr>
            <tr style="padding-bottom:0px">
                <td style="padding-bottom:0px">
                    <span style="margin-right: 50px;padding-right: 18px;color:#D3D3D3;"> TO </span>
                    <!-- <span  style="list-style: none;" > -->
                        <span style="font-weight:600px;font-size:12px;" >{{ strtoupper($letterOfIndent->client->name ?? '') }}</span>
                       <span class="country-name">
                        {{ strtoupper($letterOfIndent->country->name ?? '') }} </span>
                    <!-- </span> -->
                </td>
            </tr>
            <tr style="padding-top:0px">
                <td style="padding:0;">
                    <span style="margin-right: 55px;padding-right: 30px"> </span>
                    <span style="background-color: black;font-size: 35px;color: black">zxtestusewordtests</span>
                </td>
            </tr>
        </table>
        
        <table id="so-items" style="margin-top:8px;margin-bottom:10px;">
            <tr class="hide" style="color: #FFFFFF;text-align:center">
                <th style="padding-left:8px;padding-right:8px;text-align:center;">QUANTITY</th>
                <th>DESCRIPTION</th>
                <th width="100px" style="border-bottom:none">UNIT PRICE</th>
                <th width="100px" style="border-bottom:none">LINE TOTAL </th>
            </tr>
            @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                <tr>
                    <td style="text-align:center">{{$letterOfIndentItem->quantity}}</td>
                    <td style="text-align:left">
                        {{ $letterOfIndentItem->masterModel->milele_loi_description ?? ''}}
                        @if($key == $letterOfIndentItems->count() - 7 && $letterOfIndentItems->count() > 18)
                            <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 180px; height: 130px;"></img>
                        @endif
                    </td>
                    <td class="hide" style="border: none">3</td>
                    <td class="hide" style="border: none">3</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>CNF - SHIPMENT AND TRANSPORTATION
                    @if($letterOfIndentItems->count() <= 18)
                        <img src="{{ public_path('milele_seal.png') }}" class="overlay-image" style="width: 180px; height: 130px;"></img>
                    @endif
                </td>
                <td class="hide" style="border: none"></td>
                <td class="hide" style="border: none"></td>
            </tr>
                @if($letterOfIndentItems->count() <= 18)
                    @for($i=0;$i<=5;$i++)
                        <tr>
                            <td style="padding:8px;"></td>
                            <td style="padding:8px;"></td>
                            <td style="padding:8px;"></td>
                            <td style="padding:8px;"></td>
                        </tr>
                    @endfor
                 @endif

            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                <td colspan="2" style="border: none;font-size: 14px;padding:0px">Name :
                    <span style="margin-left: 3px" >
                        {{ strtoupper($letterOfIndent->client->name ?? '') }}
                    </span>
                </td>

                <td style="border: none;text-align: end;padding:0px">SUBTOTAL</td>
                <td class="hide" style="border: none;padding:0px" ></td>
            </tr>
            <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF" id="date-div">
                <td colspan="2" style="border: none;font-size: 14px;padding:0px">Date / Place :
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

<div style="text-align: center;position: absolute;bottom: 0;margin-left: 200px;">
    Make all checks payable to Milele Motors FZCO
    <p style="font-weight: bold">THANK YOU FOR YOUR BUSINESS</p>
</div>
</div>
    @if($letterOfIndent->LOIDocuments->count() > 0)
        <div class="page_break"></div>
        <div class="row">
            @foreach($letterOfIndent->LOIDocuments as $letterOfIndentDocument)
            <img src="{{ public_path('LOI-Documents/'.$letterOfIndentDocument->loi_document_file) }}"  class="mt-2">
            @endforeach
        </div>
    @endif

</body>
</html>



