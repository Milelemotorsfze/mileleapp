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
        }
        #so-details td {
            border: none;
            padding: 5px;
        }
        #so-items td, th{
            border: 1px solid #1c1b1b;
            text-align: left;
            padding: 8px;
            font-size: 14px;
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
            padding: 10px;
        }
        @media only screen and (min-device-width: 1200px)
            {
            .container{
                max-width: 1000px; !important;
            }
        }
    </style>
    <div class="row" id="full-page">
        <div class="container" style="padding-bottom: 0px;">
            <div class="content" style="padding-right: 0px;padding-left: 0px;margin-top: 10px">
                <form action="{{ route('letter-of-indents.generate-loi') }}">
                    <input type="hidden" name="height" id="total-height" value="">
                    <input type="hidden" name="width" id="width" value="">
                    <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
                    <input type="hidden" name="type" value="MILELE_CAR">
                    <input type="hidden" name="download" value="1">

                    <div class="text-end mb-3">
                        <a  class="btn  btn-info float-end " style="margin-left: 10px;" href="{{ url()->previous() }}" >
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                            <button type="submit" class="btn btn-primary "> Download <i class="fa fa-download"></i></button>
                        </button>
                    </div>
                </form>
                <div class="container">
                    <div class="header">
                        <table>
                            <tr>
                                <td>
                                    <img src="{{ url('images/milele_car_logo.png') }}" height="75px" width="100px" ><span class="logo-txt"></span>
                                </td>
                                <td style="text-align: end">
                                    <h1 style="color: #FFFFFF; font-size: 35px;">SALES ORDER</h1>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div style="background-color: #f6f5f5">
                        <b><p style="padding-left: 5px;font-weight: bold;padding-top: 10px">Milele Motors FZCO</p></b>
                        <table id="so-details">
                            <tr>
                                <td class="left">VAT TRN - 100057588400003</td>
                                <td></td>
                                <td class="last" style="padding-right: 20px">SO NO: <span style="background-color: black;color: black">1234567790898233</span></td>
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
                                <td class="last ">Customer ID: <span style="background-color: black;color: black">123455555</span></td>
                            </tr>
                            <tr>
                                <td class="left">+971 43235991</td>
                                <td></td>
                                <td class="last">Sales Order Type: Sales Of Motor Vehicle</td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="margin-right: 50px;padding-right: 50px"> To </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="margin-right: 55px;padding-right: 60px"> </span>
                                    <span style="background-color: black;font-size: 35px;color: black">zxdfdsiediri</span>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <br>
                        <table id="so-items" >
                            <tr style="background-color: black;color: #FFFFFF" >
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
                                    <td style="background-color: black;color: black" >0</td>
                                    <td style="background-color: black;color: black" >0</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>CNF - SHIPMENT AND TRANSPORTATION</td>
                                <td  style="background-color: black;color: black"></td>
                                <td  style="background-color: black;color: black"></td>
                            </tr>
                            <?php
                            if($letterOfIndentItems->count() >= 2) {
                                $count = 0;
                            }else
                            {
                                $count = 4;
                            }
                            ?>
                            @for($i=0;$i<$count;$i++)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td  style="background-color: black;color: black"></td>
                                    <td  style="background-color: black;color: black"></td>
                                </tr>
                            @endfor
                            <tr id="footer-table" style="background-color: #FFFFFF">
                                <td style="border: none;">Name:
                                    <span style="margin-left: 10px">
                                         @if($letterOfIndent->customer->type == \App\Models\Customer::CUSTOMER_TYPE_INDIVIDUAL)
                                            {{ $letterOfIndent->customer->name }}
                                        @else
                                            {{ $letterOfIndent->customer->company ?? ''}}
                                        @endif
                                    </span>
                                </td>
                                <td style="border: none">

                                </td>
                                <td style="border: none;text-align: end">SUBTOTAL</td>
                                <td  style="border: none;background-color: black;color: black" ></td>
                            </tr >
                            <tr style="background-color: #FFFFFF" id="date-div">
                                <td style="border: none">Date:
                                    <span>
                                         {{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td style="border: none">
                                    <img class="overlay-image" src="{{ url('milele_seal.png') }}" style="width: 170px; height: 140px;"></img>
                                </td>
                                <td style="border: none;text-align: end">SALES VAT</td>
                                <td  style="border: none;background-color: black;color: black" ></td>
                            </tr>
                            <tr style="background-color: #FFFFFF">
                                <td style="border: none">
                                    @if($letterOfIndent->signature)
                                        <img src="{{ url('LOI-Signature/'.$letterOfIndent->signature) }}" style="height: 70px;width: 150px">
                                    @endif
                                </td>
                                <td style="border: none">

                                </td>
                                <td style="border: none;text-align: end">TOTAL</td>
                                <td style="background-color: #000000;border: none" ></td>
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
            </div>
        </div>
        </div>
    </div>

    <script type="text/javascript">
        var height = document.getElementById('full-page').offsetHeight;
        var tableFooterHeight = document.getElementById('footer-table').offsetHeight;

        const values = ["200", "290", "310","250", "350"];
        const random = Math.floor(Math.random() * values.length);

        var imageWidth = values[random];
        console.log(imageWidth);
        var headerHeight = (6 * tableFooterHeight);

        var imageHeight = height - headerHeight;
        $('#total-height').val(imageHeight - 80);
        $('#width').val(imageWidth);
        $('.overlay-image').css('left', imageWidth+'px');
        $('.overlay-image').css('top', imageHeight+'px' )

    </script>
@endsection
