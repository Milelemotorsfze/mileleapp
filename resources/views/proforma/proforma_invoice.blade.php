
<!DOCTYPE html>
<html>
<head>
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            background-color: #FFFFFF;
            color: #FFFFFF;
            font-size: 12px;
        }
        .header{
            background-color: #0f2c52;
            padding-right: 10px;
            padding-left: 10px;
        }
        table {
            width: 100%;
        }
         .margin-0{
           margin-top: 0px;
           margin-bottom: 0px;
         }
        #details {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            color: black;
        }

        #details td, th {
            border: 1px solid #1c1b1b;
            text-align: left;
            padding: 8px;
        }

        /*#details tr:nth-child(even) {*/
        /*    background-color: #dddddd;*/
        /*}*/
    </style>

</head>
<body>
    <div class="content">
        <div class="header">
            <table style="width: 100%;">
                <tr>
                    <td>
                        <img src="{{ public_path('images/proforma/proforma_logo.png') }}" width="300px" height="85px" ><span class="logo-txt"></span>
                    </td>
                    <td style="text-align:right;font-size: 10px;">
                        <p style="font-weight: bold;text-align:right;margin-bottom: 5px;"> PROFORMA INVOICE </p>
                        <p class="margin-0" style="text-align:right;"> Office No-AF 07, Block A,Samari Retail </p>
                        <p class="margin-0"> Ras al khor, United Arab Emirates </p>
                        <p class="margin-0"> Tel.: +97143235991 | Email: info@milele.com </p>
                        <p class="margin-0"> Website: www.milele.com </p>
                        <p style="font-weight: bold;margin-top: 5px;"> VAT TRN NO. 100057588400003 </p>
                    </td>
                </tr>
            </table>
        </div>
        <table style="background-color: #FFFFFF;color: black">
            <tr>
                <td> <span style="font-weight: bold;">Proforma Invoice No :  </span> </td>
                <td> <span >PI001219_V001</span> </td>
                <td> <span style="font-weight: bold;">DATE :  </span> </td>
                <td> <span> {{ \Illuminate\Support\Carbon::parse($quotation->created_at)->format('d M Y') }} </span> </td>
            </tr>
            <tr>
                <td> <span style="font-weight: bold;">Sales Person :  </span> </td>
                <td> <span>Hanif M Afiq A K</span> </td>
                <td> <span style="font-weight: bold;">CM Reference No: </span> </td>
                <td> <span> CM_755 </span> </td>
            </tr>
        </table>
        <div class="header">
           <p style="font-weight: bold;padding-top:10px;padding-bottom: 10px;"> CLIENT DETAILS </p>
        </div>
        <div id="details" style="color: black">
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 20px;"> Destination Country:  </span> Angola</p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 20px;">  Company/Individual: </span> Individual</p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 45px;">  Contact Person:  </span> MS. Ludmila Cristina A De E </p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 100px;">  Email: </span> anu@gmail.com</p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 75px;">  Phone No: </span> +971 588477855</p>
            <p style="margin-top: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 80px;"> Address: </span> Samarai Retail </p>
        </div>
        <div class="header">
            <p style="font-weight: bold;padding-top:10px;padding-bottom: 10px;text-align: center"> I. DESCRIPTION AND BREAKDOWN OF GOODS </p>
        </div>
        <table id="details">
            @if($vehicles->count() > 0 || $variants->count() > 0)
                <tr>
                    <th>01. VEHICLE</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($vehicles as $vehicle)
                    <tr>
                        <td>{{ $vehicle->description }}</td>
                        <td>{{ $vehicle->quantity }}</td>
                        <td>{{ number_format($vehicle->unit_price, 2) }}</td>
                        <td>{{ number_format($vehicle->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($variants as $variant)
                    <tr>
                        <td>{{ $variant->description }}</td>
                        <td>{{ $variant->quantity }}</td>
                        <td>{{ number_format($variant->unit_price, 2) }}</td>
                        <td>{{ number_format($variant->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            @if($shippingDocuments->count() > 0 || $shippingCharges->count() > 0)
                <tr>
                    <th>02. LOGISTICS</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($shippingCharges as $shippingCharge)
                    <tr>
                        <td>{{ $shippingCharge->description }}</td>
                        <td>{{ $shippingCharge->quantity }}</td>
                        <td>{{ number_format($shippingCharge->unit_price, 2) }}</td>
                        <td>{{ number_format($shippingCharge->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($shippingDocuments as $shippingDocument)
                    <tr>
                        <td>{{ $shippingDocument->description }}</td>
                        <td>{{ $shippingDocument->quantity }}</td>
                        <td>{{ number_format($shippingDocument->unit_price, 2) }}</td>
                        <td>{{ number_format($shippingDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif

            @if($addons->count() > 0)
                <tr>
                    <th>03. ADD ONS AND EXTRA ITEM </th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($addons as $addon)
                    <tr>
                        <td>{{ $addon->description }}</td>
                        <td>{{ $addon->quantity }}</td>
                        <td>{{ number_format($addon->unit_price, 2) }}</td>
                        <td>{{ number_format($addon->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
            @if($shippingCertifications->count() > 0 || $otherDocuments->count() > 0)
                <tr>
                    <th>04. COMPLIANCE AND CERTIFICATES</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($shippingCertifications as $shippingCertification)
                    <tr>
                        <td>{{ $shippingCertification->description }}</td>
                        <td>{{ $shippingCertification->quantity }}</td>
                        <td>{{ number_format($shippingCertification->unit_price, 2) }}</td>
                        <td>{{ number_format($shippingCertification->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($otherDocuments as $otherDocument)
                    <tr>
                        <td>{{ $otherDocument->description }}</td>
                        <td>{{ $otherDocument->quantity }}</td>
                        <td>{{ number_format($otherDocument->unit_price, 2) }}</td>
                        <td>{{ number_format($otherDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        <br>
        <table style="color: black;width: 100%;text-align: right">
            <tr>
                <td style="font-weight: bold;text-align: left">Note:- Third Party Payments will not be accepted.</td>
                <td> </td>
                <td style="font-weight: bold"> SUB TOTAL</td>

                <td style="text-align: end">{{ number_format($quotation->deal_value) }} </td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold">Discount</td>
                <td> </td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold">Net Amount</td>
                <td style="text-align: end">{{ number_format($quotation->deal_value) }}</td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold">VAT:(0%)</td>
                <td>0.00</td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold">Gross Amount</td>
                <td> </td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold"> Advance Paid</td>
                <td> </td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold"> Remaining Amount</td>
                <td> </td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td>  </td>
                <td style="color: #de2121">  * VAT is not applicable for Export Bill </td>
            </tr>
        </table>
    </div>

</body>
</html>

