@extends('layouts.main')
@section('content')
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            /*background-color: #0a58ca;*/

        }
        .overlay-image {
            position: absolute;
            z-index: 1;
            opacity: 1.8;
        }
        #so-details td {
            border: none;
            padding: 5px;
            font-size: 10px;
            font-family: arial, sans-serif;
        }
        #so-items td, th{
            border: 1px solid #D3D3D3;
            /* text-align: left; */
            /* padding: 8px; */
            font-size: 10px;
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
            padding-right: 20px;
            /* padding-left: 10px; */
            font-family: arial, sans-serif;
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
        .county-name{
            display: list-item;
            list-style: none;
            padding-left: 30px;
            margin-left: 55px;
            font-weight:600px;
            font-size:12px;
        }
    </style>
    <div class="row" >
        <div class="container" style="padding-bottom: 0px;">
            <div class="content" style="padding-right: 0px;padding-left: 0px;margin-top: 10px">
                <form action="{{ route('letter-of-indents.generate-loi') }}">
                    <input type="hidden" name="width" id="width" value="">
                    <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                    <input type="hidden" name="type" value="milele_cars">
                    <input type="hidden" name="download" value="1">

                    <div class="text-end mb-3" style="margin-right: 20px;">
                        <a  class="btn  btn-info float-end " style="margin-left: 10px;" href="{{ route('letter-of-indents.index', ['tab' => 'NEW']) }}" >
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                        <button type="submit" class="btn btn-primary "> Download <i class="fa fa-download"></i></button>
                        </button>
                    </div>
                </form>
                <div class="container border-outline">
                    <div id="full-page" >
                        <div class="header">
                            <table>
                                <tr>
                                    <td>
                                        <img src="{{ url('images/milele_car_logo.png') }}" height="65px" width="90px" >
                                        <!-- <span class="logo-txt"></span> -->
                                    </td>
                                    <td style="text-align: end">
                                        <span style="color: #FFFFFF; font-size: 35px;">SALES ORDER</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="background-color: #f6f5f5">
                            <p style="padding-left: 5px;font-weight: 600px;padding-top: 10px;font-size:14px">Milele Motors FZCO</p>
                            <table id="so-details">
                                <tr>
                                    <td class="left" style="padding-top:0px;">VAT TRN - 100057588400003</td>
                                    <td style="padding-top:0px;"></td>
                                    <td class="last" style="padding-right: 20px;padding-top:0px;color:#D3D3D3">SO NO: <span style="background-color: black;color: black;text-align:left">1234567790898233</span></td>
                                </tr>
                                <tr>
                                    <td class="left" style="padding-top:0px;">Ras al khor 3, Yard 11 - DAZ</td>
                                    <td style="padding-top:0px;"></td>
                                    <td class="last" style="padding-top:0px;padding-right: 70px;" ><span style="color:#D3D3D3"> DATE </span> 
                                        <span class="left"> {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('M d Y')}} </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:0px;">Dubai, U.A.E</td>
                                    <td style="padding-top:0px;"></td>
                                    <td class="last" style="padding-top:0px;color:#D3D3D3;padding-right: 20px;">CUSTOMER ID
                                         <span style="background-color: black;color: black">123455555hhhhhhh</span></td>
                                </tr>
                                <tr>
                                    <td class="left"  style="padding-top:0px;">+971 43235991</td>
                                    <td  style="padding-top:0px;"></td>
                                    <td class="last"  style="padding-top:0px;"> <span style="color:#D3D3D3;">SALES ORDER TYPE </span> Sales Of Motor Vehicle</td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom:0px">
                                        <span style="margin-right: 50px;padding-right: 20px;color:#D3D3D3;"> TO </span>
                                        <!-- <span  style="list-style: none;" > -->
                                        <span style="font-size:12px;font-weight:600px" >{{ strtoupper($letterOfIndent->client->name ?? '') }}</span>
                                       <span class="county-name">{{ strtoupper($letterOfIndent->country->name ?? '') }} </span>
                                    </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0px">
                                        <span style="margin-right: 55px;padding-right: 30px"> </span>
                                        <span style="background-color: black;font-size: 35px;color: black">11111111111111111111r</span>
                                    </td>
                                </tr>
                            </table>
                      
                            <table id="so-items" style="margin-top:5px;" >
                                <tr style="background-color: black;color: #FFFFFF;text-align:center" >
                                    <th style="text-align:center;padding-left:8px;padding-right:8px;">QUANTITY</th>
                                    <th>DESCRIPTION</th>
                                    <th width="100px" style="border-bottom:none">UNIT PRICE</th>
                                    <th width="100px" style="border-bottom:none">LINE TOTAL </th>
                                </tr>
                                @foreach($letterOfIndentItems as $letterOfIndentItem)
                                    <tr>
                                        <td style="text-align:center">{{$letterOfIndentItem->quantity}}</td>
                                        <td>
                                            {{ $letterOfIndentItem->masterModel->milele_loi_description ?? ''}}
                                        </td>
                                        <td style="background-color: black;color: black;border:none;" >0</td>
                                        <td style="background-color: black;color: black;border:none;" >0</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td>CNF - SHIPMENT AND TRANSPORTATION
                                        <img class="overlay-image" src="{{ url('milele_seal.png') }}" style="width: 170px; height: 140px;"></img>
                                    </td>
                                    <td  style="background-color: black;color: black;border:none;"></td>
                                    <td  style="background-color: black;color: black;border:none;"></td>
                                </tr>
                              
                                <tr id="footer-table" style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                                    <td colspan="2" style="border: none;padding-top:0px">Name:
                                        <span style="margin-left: 10px"> {{ strtoupper($letterOfIndent->client->name ?? '') }} </span>
                                    </td>

                                    <td style="border: none;text-align: end;padding-top:0px">SUBTOTAL</td>
                                    <td  style="border: none;background-color: black;color: black;padding-top:0px" ></td>
                                </tr >
                                <tr style="background-color: #FFFFFF;border-left: 1px solid #FFFFFF;" id="date-div">
                                    <td colspan="2" style="border: none;padding-top:0px">Date / Place:
                                        <span>
                                         {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y') }}
                                    </span>
                                        {{--                                    <img class="overlay-image" src="{{ url('milele_seal.png') }}" style="width: 170px; height: 140px;"></img>--}}
                                    </td>
                                    {{--                                <td style="border: none">--}}
                                    {{--                                  --}}
                                    {{--                                </td>--}}
                                    <td style="border: none;text-align: end;padding-top:0px">SALES VAT</td>
                                    <td  style="border: none;background-color: black;color: black;padding-top:0px" ></td>
                                </tr>
                                <tr style=";background-color: #FFFFFF;border-left: 1px solid #FFFFFF">
                                    <td colspan="2" style="border: none;padding-top:0px">
                                        @if($letterOfIndent->signature)
                                            <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 100px;width: 150px">
                                        @endif
                                    </td>

                                    <td style="border: none;text-align: end;padding-top:0px">TOTAL</td>
                                    <td style="background-color: #000000;border: none;padding-top:0px" ></td>
                                </tr>
                            </table>
                        </div>
                        <div class="row">
                            <div id="circle">
                            </div>
                        </div>
                        <div class="row bg-white bottom-0">
                            <div class="text-center">
                                <p>Make all checks payable to Milele Motors FZCO</p>
                                <p style="font-weight: bold">THANK YOU FOR YOUR BUSINESS</p>
                            </div>
                        </div>
                    </div>

                    @if($letterOfIndent->LOIDocuments->count() > 0)
                        <h5 class="fw-bold text-center">Customer Document</h5>
                    @foreach($letterOfIndent->LOIDocuments as $key => $letterOfIndentDocument)
                            <div class="mt-3"  id="remove-doc-{{$letterOfIndentDocument->id}}">
                                @if($letterOfIndentDocument->is_passport)
                                    <iframe src="{{ url('storage/app/public/passports/'.$letterOfIndentDocument->loi_document_file) }}"   height="500px;"></iframe>
                                @elseif($letterOfIndentDocument->is_trade_license)
                                    <iframe src="{{ url('storage/app/public/tradelicenses/'.$letterOfIndentDocument->loi_document_file) }}"  height="500px;"></iframe>
                                @else
                                    <iframe src="{{ url('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}"   height="500px;"></iframe>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>

    <script type="text/javascript">
        const values = ["200", "290", "310","250", "350"];
        const random = Math.floor(Math.random() * values.length);

        var imageWidth = values[random];
        console.log(imageWidth);
        $('#width').val(imageWidth);
        $('.overlay-image').css('left', imageWidth+'px');
    </script>
@endsection
