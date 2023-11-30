
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
            font-weight: bold;
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
                        <p style="font-weight: bold;text-align:right;margin-bottom: 5px;font-size: 16px;">
                            @if($quotation->document_type == 'Quotation')
                                QUOTATION
                            @else
                                PROFORMA INVOICE
                            @endif
                        </p>
                        <p class="margin-0" style="text-align:right;"> Office No-AF 07, Block A,Samari Retail </p>
                        <p class="margin-0"> Ras al khor, United Arab Emirates </p>
                        <p class="margin-0"> Tel.: +97143235991 | Email: info@milele.com </p>
                        <p class="margin-0"> Website: www.milele.com </p>
                        <p style="font-weight: bold;margin-top: 5px;"> VAT TRN NO. 100057588400003 </p>
                    </td>
                </tr>
            </table>
        </div>
        <div  style="color: black">
            <table style="border: none;">
                <tr style="background-color: #0f2c52;color: #FFFFFF;font-weight: bold">
                    <td colspan="2">Document Details</td>
                    <td colspan="2">Client Details</td>
                    <td colspan="2">Delivery Details</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Document No :</td>
                    <td>{{ $data['document_number'] }}</td>
                    <td style="font-weight: bold;">Customer ID :</td>
                    <td> {{ $data['client_id'] }}</td>
                    <td style="font-weight: bold;"> @if($quotation->shipping_method == 'EXW') Final Destination : @else Place Of Supply :  @endif</td>
                    <td> @if($quotation->shipping_method == 'EXW') {{ $quotationDetail->final_destination  }} @else
                        {{ $quotationDetail->place_of_supply }}  @endif </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;">Document Date :</td>
                    <td>{{ $data['document_date'] }}</td>
                    <td style="font-weight: bold;">Company :</td>
                    <td>{{ $data['company'] }}</td>
                    <td style="font-weight: bold;">
                        @if($quotation->shipping_method == 'EXW') Incoterm :@endif </td>
                    <td> @if($quotation->shipping_method == 'EXW') {{ $quotationDetail->incoterm  }} @endif </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;">Document Validity :</td>
                    <td>{{ $quotationDetail->document_validity }} @if($quotationDetail->document_validity == 1) Day @else Days @endif</td>
                    <td style="font-weight: bold;">Person :</td>
                    <td>{{  $data['client_name']  }} </td>
                    <td style="font-weight: bold;">
                        @if($quotation->shipping_method == 'EXW') Place Of Delivery :@endif </td>
                    <td> @if($quotation->shipping_method == 'EXW') {{ $quotationDetail->place_of_delivery  }} @endif </td>

                </tr>

                <tr>
                    <td style="font-weight: bold;">Sales Person :</td>
                    <td>{{$data['sales_person'] }}</td>
                    <td style="font-weight: bold;">Phone :</td>
                    <td>{{  $data['client_phone']  }} </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sales Office :</td>
                    <td>{{ $data['sales_office']  }}</td>
                    <td style="font-weight: bold;">Email :</td>
                    <td>{{  $data['client_email'] }} </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sales Email :</td>
                    <td>{{ $data['sales_email']  }}</td>
                    <td style="font-weight: bold;">Address :</td>
                    <td>{{  $data['client_address']  }} </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Sales Contact :</td>
                    <td>{{ $data['sales_phone']  }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div  style="color: black">
            <table style="border: none;">
                <tr style="background-color: #0f2c52;color: #FFFFFF;font-weight: bold">
                    <td colspan="2">Payment Details</td>
                    <td colspan="4">Client  Representative</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">System Code :</td>
                    <td>{{ $quotationDetail->system_code }}</td>
                    <td style="font-weight: bold;">Rep Name :</td>
                    <td> {{ $quotationDetail->representative_name }}</td>
                    <td style="font-weight: bold;"> CB Name :</td>
                    <td> {{ $quotationDetail->cb_name }} </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Payment Terms :</td>
                    <td>{{ $quotationDetail->payment_terms }}</td>
                    <td style="font-weight: bold;">Rep No. :</td>
                    <td> {{ $quotationDetail->representative_number }}</td>
                    <td style="font-weight: bold;"> CB No :</td>
                    <td> {{ $quotationDetail->cb_number }} </td>
                </tr>
            </table>
        </div>

        <div class="header">
            <p style="font-weight: bold;padding-top:10px;padding-bottom: 10px;text-align: center"> I. DESCRIPTION AND BREAKDOWN OF GOODS </p>
        </div>
        <table id="details">
            @if($vehicles->count() > 0 || $variants->count() > 0)
                <tr style="background-color: #c9c1ea;font-size: 15px;">
                    <th>VEHICLE</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($vehicles as $key => $vehicle)
                    <tr>
                        <td> <span style="font-weight: bold;font-size: 14px;" > {{ $key+1 }}. </span> {{ $vehicle->description }}</td>
                        <td>{{ $vehicle->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($vehicle->vehicle_unit_price, 2) }} </td>
                        <td> <?php $totalAmount = $vehicle->vehicle_unit_price * $vehicle->quantity ?>
                            {{ $quotation->currency ." ". number_format($totalAmount, 2) }}</td>
                    </tr>
                        @if($vehicle->quotation_addon_items->count() > 0)
                            <tr>
                                <th colspan="4"><span style="text-align: center;padding-left: 20px;"> ADDONS </span> </th>
                            </tr>
                            @foreach($vehicle->quotation_addon_items as $key =>  $addon)
                                <tr style="background-color: #e1c7a8;">
                                    <td style="padding-left: 40px;"><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>
                                        {{ $addon->quotationItem->description ?? ''}}</td>
                                    <td>{{ $addon->quotationItem->quantity ?? ''}}</td>
                                    <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->unit_price, 2) }}</td>
                                    <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                @endforeach
                @foreach($variants as $key => $variant)
                    <tr>
                        <td> <span style="font-weight: bold;font-size: 14px;" > {{ $vehicles->count() + $key+1 }}. </span> {{ $variant->description }}</td>
                        <td>{{ $variant->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($variant->vehicle_unit_price, 2) }}</td>
                        <td> <?php $totalAmount = $variant->vehicle_unit_price * $variant->quantity ?>
                            {{ $quotation->currency ." ". number_format($totalAmount, 2) }}</td>
                    </tr>
                    @if($variant->quotation_addon_items->count() > 0)
                        <tr>
                            <th colspan="4">ADDON</th>
                        </tr>
                        @foreach($variant->quotation_addon_items as $key => $addon)
                            <tr style="background-color: #e1c7a8">
                                <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>
                                    {{ $addon->quotationItem->description ?? ''}}</td>
                                <td>{{ $addon->quotationItem->quantity ?? ''}}</td>
                                <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->unit_price, 2) }}</td>
                                <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
            @if($shippingDocuments->count() > 0 || $shippingCharges->count() > 0)
                <tr style="background-color: #c9c1ea;font-size: 15px;">
                    <th> LOGISTICS</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($shippingCharges as $key => $shippingCharge)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>{{ $shippingCharge->description }}</td>
                        <td>{{ $shippingCharge->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCharge->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCharge->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($shippingDocuments as $key => $shippingDocument)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $shippingCharges->count() + $key+1 }}. </span> {{ $shippingDocument->description }}</td>
                        <td>{{ $shippingDocument->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingDocument->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif

            @if($addons->count() > 0 || $directlyAddedAddons->count() > 0)
                <tr style="background-color: #c9c1ea;font-size: 15px;">
                    <th> ADDONS AND EXTRA ITEM </th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($addons as $key => $addon)
                    <tr>
                        <td> <span style="font-weight: bold;margin-right: 5px;" > {{ $key + 1 }}. </span> {{ $addon->description }}</td>
                        <td>{{ $addon->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($addon->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($addon->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($directlyAddedAddons as $key => $directlyAddedAddon)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $addons->count() + $key+1 }}. </span> {{ $directlyAddedAddon->description }}</td>
                        <td>{{ $directlyAddedAddon->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($directlyAddedAddon->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($directlyAddedAddon->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @if($addonsTotalAmount > 0)
                        <tr>
                            <td colspan="3">Addons</td>
                            <td>  {{ $quotation->currency }} {{ number_format($addonsTotalAmount, 2) }}</td>
                        </tr>
                @endif

            @endif
            @if($shippingCertifications->count() > 0 || $otherDocuments->count() > 0)
                <tr style="background-color: #c9c1ea;font-size: 15px;">
                    <th> COMPLIANCE AND CERTIFICATES</th>
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($shippingCertifications as $key => $shippingCertification)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>  {{ $shippingCertification->description }}</td>
                        <td>{{ $shippingCertification->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCertification->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCertification->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($otherDocuments as $key => $otherDocument)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $shippingCertifications->count() + $key+1 }}. </span>  {{ $otherDocument->description }}</td>
                        <td>{{ $otherDocument->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($otherDocument->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($otherDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
                @if($quotation->document_type == 'Proforma Invoice')
                    <tr style="background-color: #c9c1ea;font-size: 15px;">
                        <th colspan="3"> DEPOSIT / PAYMENT RECEIVED</th>
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
            @if($quotation->document_type == 'Proforma Invoice')
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
            @endif
        </table>
        @if($quotation->document_type == 'Proforma Invoice')
            <p style="font-weight: bold">payment due By: </p>
        @endif
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
        @if($quotation->currency == 'USD')
           <p style="font-weight: bolder"> Currency Exchange </p>
            <p> Bank Payments AED transfers at actuals. USD transfer at {{ $aed_to_usd_rate->value }} and customer must remit $50 equivalent extra to cover for bank fees.
                Cash Payments AED at actuals, USD New Bills $100 at {{ $aed_to_usd_rate->value }}, all other bills at 3.60. </p>
        @elseif($quotation->currency == 'EURO')
            <p style="font-weight: bolder"> Currency Exchange </p>
            <p> Bank Payments AED transfers at actuals. EUR transfer at {{ $aed_to_eru_rate->value }} and customer must remit EUR 50 equivalent extra to cover for bank fees.
                Cash Payments AED at actuals, USD New Bills EUR 100 at {{ $aed_to_eru_rate->value }}, all other bills at
                {{ $aed_to_eru_rate }}. </p>
        @endif
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

