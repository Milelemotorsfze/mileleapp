
<!DOCTYPE html>
<html>
<head>
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            background-color: #FFFFFF;
            color: #000000;
            font-size: 12px;
        }
        .header{
            background-color: #0f2c52;
            padding-right: 10px;
            padding-left: 10px;
            color: #FFFFFF;
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
                <td> <span > {{ $quotation->id }} </span> </td>
                <td> <span style="font-weight: bold;">DATE :  </span> </td>
                <td> <span> {{ \Illuminate\Support\Carbon::parse($quotation->created_at)->format('d M Y') }} </span> </td>
            </tr>
            <tr>
                <td> <span style="font-weight: bold;">Sales Person :  </span> </td>
                <td> <span> {{ $data['sales_person'] }}</span> </td>
                <td> <span style="font-weight: bold;">CM Reference No: </span> </td>
                <td> <span> {{ $data['customer_reference_number'] }} </span> </td>
            </tr>
        </table>
        <div class="header">
           <p style="font-weight: bold;padding-top:10px;padding-bottom: 10px;"> CLIENT DETAILS </p>
        </div>
        <div id="details" style="color: black">
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 20px;"> Destination Country:  </span>
                {{ $quotationDetail->final_destination }}</p>
            <p style="margin-bottom: 5px;margin-top: 5px;">
                <span style="font-weight: bold;margin-right: 20px;">  Company/Individual: </span> Individual</p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 45px;">  Contact Person:  </span>
                {{ strtoupper( $data['client_name'] ) }} </p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 100px;">  Email: </span>
                {{  $data['client_email'] }}</p>
            <p style="margin-bottom: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 75px;">  Phone No: </span>
                {{  $data['client_phone'] }}</p>
            <p style="margin-top: 5px;margin-top: 5px;"> <span style="font-weight: bold;margin-right: 80px;"> Address: </span>
                {{  $data['client_address'] }} </p>
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
                        <td>{{ $quotation->currency ." ". number_format($vehicle->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($vehicle->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($variants as $variant)
                    <tr>
                        <td>{{ $variant->description }}</td>
                        <td>{{ $variant->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($variant->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($variant->total_amount, 2) }}</td>
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
                        <td>{{ $quotation->currency ." ". number_format($shippingCharge->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCharge->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($shippingDocuments as $shippingDocument)
                    <tr>
                        <td>{{ $shippingDocument->description }}</td>
                        <td>{{ $shippingDocument->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingDocument->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingDocument->total_amount, 2) }}</td>
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
                        <td>{{ $quotation->currency ." ". number_format($addon->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($addon->total_amount, 2) }}</td>
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
                        <td>{{ $quotation->currency ." ". number_format($shippingCertification->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCertification->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($otherDocuments as $otherDocument)
                    <tr>
                        <td>{{ $otherDocument->description }}</td>
                        <td>{{ $otherDocument->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($otherDocument->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($otherDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
                @if($quotation->document_type == 'Proforma Invoice')
                    <tr>
                        <th colspan="3">05. DEPOSIT / PAYMENT RECEIVED</th>
                        <th>AMOUNT</th>
                    </tr>
                    <tr>
                        <td colspan="3">Deposit</td>
                        <td> {{ $quotation->currency ." ". number_format($quotationDetail->advance_amount, 2) }}</td>

                    </tr>

                @endif
        </table>
        <br>
        <table style="color: black;width: 100%;text-align: right">
            <tr>
                <td style="font-weight: bold;text-align: left">Note:- Third Party Payments will not be accepted.</td>
                <td> </td>
                <td style="font-weight: bold"> SUB TOTAL</td>

                <td style="text-align: end">{{ $quotation->currency ." ". number_format($quotation->deal_value) }} </td>
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
                <td style="text-align: end">{{ $quotation->currency ." ". number_format($quotation->deal_value) }}</td>
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
                <td> {{ number_format($quotationDetail->advance_amount, 2) }} </td>
            </tr>
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold"> Remaining Amount({{ $quotation->currency }})</td>
                <td> {{ $quotation->currency ." ". number_format($quotation->deal_value - $quotationDetail->advance_amount) }} </td>
            </tr>
            @if($quotation->currency != 'AED' && $quotation->shippingDocument == 'EXW')
            <tr>
                <td> </td>
                <td> </td>
                <td style="font-weight: bold"> Remaining Amount(AED)</td>
                <td>

                   @if($quotation->currency == 'USD')
                         {{ $quotation->currency ." ". number_format(($quotation->deal_value - $quotationDetail->advance_amount)  * $aed_to_usd_rate->value, 2) }}
                   @elseif($quotation->currency == 'EUR')
                        {{ $quotation->currency ." ". number_format(($quotation->deal_value - $quotationDetail->advance_amount)  * $aed_to_eru_rate->value, 2) }}
                   @endif

                </td>
            </tr>
            @endif
            <tr>
                <td> </td>
                <td> </td>
                <td>  </td>
                <td style="color: #de2121">  * VAT is not applicable for Export Bill </td>
            </tr>
        </table>
        <p style="font-weight: bold">payment due By: </p>
        <p>I hereby acknowledge to honor the payment by the agreed due date.</p>
         <p> In case of my failure to clear payment on time, I stand to lose the right to my payments and my order may be delayed or subject to cancellation.</p>
        <p> Customs clearance, taxes, duty, value added taxes or any other charges related to the above mentioned goods are the sole responsibility of the client.</p>
        <p style="font-weight: bolder">
            Any payments which are made to Milele Motors FZE are non refundable & the price will be changed based on the new market price, and seller has right to sell the cars
            without prior notice to buyer.
        </p>
        <p >
            Upon initiating any transaction with Milele Motors FZE, the buyer acknowledges and unconditionally agrees to our terms and conditions. It is expressly understood that any payment by the
            buyer, whether as advances, deposits, or other payments, is non-refundable under any circumstances. The buyer confirms the sale and recognizes its binding nature by making
            payments. Furthermore, any products or services procured are strictly non-exchangeable and non-returnable. Even without a physical signature, such a transfer signifies a binding and
            unilateral acceptance of these terms. Before making any transaction, the buyer has had the full opportunity to review these terms in detail, thereby affirming their understanding and
            acceptance.
        </p>
        <table>
            <td style="font-weight: bold">
                <p> Accepted By </p>
                <p> Board of Director : </p>
            </td>
            <td style="font-weight: bold">
                <p>Client Name: </p>
                <p>Signature: </p>
                <p>Date: </p>

            </td>
        </table>
    </div>

</body>
</html>

