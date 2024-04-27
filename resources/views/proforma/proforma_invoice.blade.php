
<!DOCTYPE html>
<html>
<head>
    <style>
        #customCell {
    text-align: left !important;
}
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            background-color: #FFFFFF;
            color: #000000;
            font-size: 12px;
        }
        .header{
            /*background-color: #0f2c52;*/
            padding-right: 10px;
            padding-left: 10px;
            color: #000000;
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
                        <img src="{{ public_path('images/proforma/milele_logo.png') }}" width="300px" height="80px" ><span class="logo-txt"></span>
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
                        <p class="margin-0"> Ras Al khor, United Arab Emirates </p>
                        <p class="margin-0"> Tel.: +97143235991 | Email: info@milele.com </p>
                        <p class="margin-0"> Website: www.milele.com </p>
                        <p style="font-weight: bold;margin-top: 5px;"> VAT TRN NO. 100057588400003 </p>
                    </td>
                </tr>
            </table>
        </div>
        <div style="color: black">
    <table style="border: none;">
    <tr style="font-weight: bold;background-color: #bbbbbd">
            <td style="font-weight: bold;">Document Details</td>
            <td style="font-weight: bold;">Client Details</td>
            <td style="font-weight: bold;">Delivery Details</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">
                <table style="border: none;">
                    <tr>
                        <td style="font-weight: bold;">Document No :</td>
                        <td>{{ $data['document_number'] }}</td>
                    </tr>
                    @if($data['document_date'])
                    <tr>
                        <td style="font-weight: bold;">Document Date :</td>
                        <td>{{ $data['document_date'] }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: bold;">Validity :</td>
                        <td>{{ $quotationDetail->document_validity }} @if($quotationDetail->document_validity == 1) Day @else Days @endif</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;">Sales Person :</td>
                        <td>{{ $data['sales_person'] }}</td>
                    </tr>
                    @if($data['sales_office'])
                    <tr>
                        <td style="font-weight: bold;">Sales Office :</td>
                        <td>{{ $data['sales_office'] }}</td>
                    </tr>
                    @endif
                    @if($data['sales_email'])
                    <tr>
                        <td style="font-weight: bold;">Sales Email :</td>
                        <td>{{ $data['sales_email'] }}</td>
                    </tr>
                    @endif
                    @if($data['sales_phone'])
                    <tr>
                        <td style="font-weight: bold;">Sales Contact :</td>
                        <td>{{ $data['sales_phone'] }}</td>
                    </tr>
                    @endif
                </table>
            </td>
            <td style="vertical-align: top;">
                <table style="border: none;">
                    <tr>
                        <td style="font-weight: bold;">Customer:</td>
                        <td>{{ $data['client_name'] }}</td>
                    </tr>
                    @if($data['client_phone'])
                    <tr>
                    <td style="font-weight: bold;">Phone :</td>
                    <td>{{  $data['client_phone']  }} </td>
                    </tr>
                    @endif
                    @if($data['company'])
                    <tr>
                    <td style="font-weight: bold;">Company :</td>
                    <td>{{  $data['company']  }} </td>
                    </tr>
                    @endif
                    @if($data['client_contact_person'])
                    <tr>
                    <td style="font-weight: bold;">Contact Person :</td>
                    <td>{{  $data['client_contact_person']  }} </td>
                    </tr>
                    @endif
                    @if($data['client_email'])
                    <tr>
                    <td style="font-weight: bold;">Email :</td>
                    <td>{{  $data['client_email']  }} </td>
                    </tr>
                    @endif
                    @if($data['client_address'])
                    <tr>
                    <td style="font-weight: bold;">Address :</td>
                    <td>{{  $data['client_address']  }} </td>
                    </tr>
                    @endif
                </table>
            </td>
            <td style="vertical-align: top;">
                <table style="border: none;">
                    <tr>
                        <td style="font-weight: bold;">@if($quotation->shipping_method == 'EXW') Final Des : @else Place Of Supply :  @endif</td>
                        <td>@if($quotation->shipping_method == 'EXW') {{ $quotationDetail->country->name ?? '' }} @else {{ $quotationDetail->place_of_supply }}  @endif </td>
                    </tr>
                    <tr>
                    <td style="font-weight: bold;">
                        @if($quotation->shipping_method == 'EXW') Incoterm :@endif </td>
                    <td> @if($quotation->shipping_method == 'EXW') {{ $quotationDetail->incoterm  }} @endif </td>
                    </tr>
                    <tr>
                    <td style="font-weight: bold;">
                        @if($quotation->shipping_method == 'EXW') POD :@endif </td>
                    <td> @if($quotation->shipping_method == 'EXW') {{ $quotationDetail->shippingPort->name ?? ''   }} @endif </td>
                    </tr>
                    <tr>
                    <td style="font-weight: bold;">
                        @if($quotation->shipping_method == 'EXW') POL :@endif </td>
                    <td> @if($quotation->shipping_method == 'EXW') {{ $quotationDetail->shippingPortOfLoad->name ??''   }} @endif </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<div  style="color: black">
            <table style="border: none;">
                <tr style="background-color: #bbbbbd;color: #000000;font-weight: bold">
                @if($quotationDetail->payment_terms)
                    <td colspan="2">Payment Details</td>
                    @endif
                    @if ($quotationDetail->representative_name || $quotationDetail->cb_name )
                    <td colspan="4">Client  Representative</td>
                    @endif
                </tr>
                <tr>
                    @if($quotationDetail->payment_terms)
                    <td style="font-weight: bold;">Payment Terms :     {{ $quotationDetail->payment_terms }}</td>
                    <td></td>
                    @endif
                    @if($quotationDetail->representative_name)
                    <td style="font-weight: bold;">Rep Name      :     {{ $quotationDetail->representative_name }}</td>
                    <td> </td>
                    @endif
                    @if($quotationDetail->cb_name)
                    <td style="font-weight: bold;">CR Name       :     {{ $quotationDetail->cb_name }}</td>
                    <td style="text-align: left !important;">  </td>
                    @endif
                </tr>
                <tr>
                @if($quotationDetail->payment_terms)
                    <td></td>
                    <td></td>
                @endif
                    @if($quotationDetail->representative_name)
                    <td style="font-weight: bold;">Rep No.       :     {{ $quotationDetail->representative_number }}</td>
                    <td> </td>
                    @endif
                    @if($quotationDetail->cb_name)
                    <td style="font-weight: bold;"> CR No        :     {{ $quotationDetail->cb_number }}</td>
                    <td id="customCell"> </td>
                    @endif
                </tr>
            </table>
        </div>
        @if(isset($multiplecp))
    <div style="color: black">
        <table style="border: none;">
            <tr style="background-color: #bbbbbd;color: #000000;font-weight: bold">
                <td colspan="10">Other Client Representative</td>
            </tr>
            <tr>
            @foreach($multiplecp as $multiplecps)
                <?php $quotationcpname = DB::table('agents')->where('id', $multiplecps->agents_id)->first(); ?>
                <td style="font-weight: bold;"> CR Name :</td>
                <td> {{ $quotationcpname->name ?? '' }} </td>
            @endforeach
            </tr>
            <tr>
            @foreach($multiplecp as $multiplecps)
                <?php $quotationcpnum = DB::table('agents')->where('id', $multiplecps->agents_id)->first(); ?>
                <td style="font-weight: bold;"> CR Number :</td>
                <td> {{ $quotationcpnum->phone ?? '' }} </td>
            @endforeach
            </tr>
        </table>
    </div>
@endif
        <table id="details">
            @if($vehicles->count() > 0  || $otherVehicles->count() || $vehicleWithBrands->count() > 0)
                <tr style="font-size: 12px;background-color: #bbbbbd;">
                    <th>VEHICLE</th>
                    @if($quotationDetail->cb_name)
                    <th>SYSTEM CODE</th>
                    @endif
                    <th>QTY</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach($vehicles as $key => $vehicle)
                    <tr style="color: #02023f">
                            <?php $shippingPerVehiclequantityPrice = $shippingChargeDistriAmount / $vehicle->quantity;
                            $vehicleUnitPrice = $vehicle->vehicle_unit_price + $shippingPerVehiclequantityPrice;
                            $totalAmount = $vehicleUnitPrice * $vehicle->quantity ?>
                        <td><span style="font-weight: bold;font-size: 14px;" > {{ $key+1 }}. </span> {{ $vehicle->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{$vehicle->system_code_currency ."". $vehicle->system_code_amount }}</td>
                        @endif
                        <td>{{ $vehicle->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($vehicleUnitPrice, 2) }} </td>
                        <td>
                            {{ $quotation->currency ." ". number_format($totalAmount, 2) }}</td>
                    </tr>
                        @if($vehicle->quotation_addon_items->count() > 0)
                            @foreach($vehicle->quotation_addon_items as $key =>  $addon)
                                <tr style="color: #643702;">
                                    <td style="padding-left: 40px;"><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>
                                        {{ $addon->quotationItem->description ?? ''}}</td>
                                    <td> {{$addon->quotationItem->system_code_currency ."". $addon->quotationItem->system_code_amount }}</td>
                                    <td>{{ $addon->quotationItem->quantity ?? ''}}</td>
                                    <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->unit_price, 2) }}</td>
                                    <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                @endforeach
{{--                @foreach($variants as $key => $variant)--}}
{{--                    <tr style="color: #02023f;">--}}
{{--                        <td> <span style="font-weight: bold;font-size: 14px;" > {{ $vehicles->count() + $key+1 }}. </span> {{ $variant->description }}</td>--}}
{{--                        <td> {{$variant->system_code_currency ."".$variant->system_code_amount }}</td>--}}
{{--                        <td>{{ $variant->quantity }}</td>--}}
{{--                        <td>{{ $quotation->currency ." ". number_format($variant->vehicle_unit_price, 2) }}</td>--}}
{{--                        <td> <?php $totalAmount = $variant->vehicle_unit_price * $variant->quantity ?>--}}
{{--                            {{ $quotation->currency ." ". number_format($totalAmount, 2) }}</td>--}}
{{--                    </tr>--}}
{{--                    @if($variant->quotation_addon_items->count() > 0)--}}
{{--                        @foreach($variant->quotation_addon_items as $key => $addon)--}}
{{--                            <tr style="color: #643702">--}}
{{--                                <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>--}}
{{--                                    {{ $addon->quotationItem->description ?? ''}}</td>--}}
{{--                                <td> {{$addon->quotationItem->system_code_currency ."".$addon->quotationItem->system_code_amount }}</td>--}}
{{--                                <td>{{ $addon->quotationItem->quantity ?? ''}}</td>--}}
{{--                                <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->unit_price, 2) }}</td>--}}
{{--                                <td>{{ $quotation->currency ." ". number_format($addon->quotationItem->total_amount, 2) }}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                @endforeach--}}
                @foreach($otherVehicles as $key => $otherVehicle)
                    <tr style="color: #02023f;">
                            <?php $shippingPerVehiclequantityPrice = $shippingChargeDistriAmount / $otherVehicle->quantity;
                            $vehicleUnitPrice = $otherVehicle->vehicle_unit_price + $shippingPerVehiclequantityPrice;
                            $totalAmount = $vehicleUnitPrice * $otherVehicle->quantity ?>
                        <td> <span style="font-weight: bold;font-size: 14px;" > {{  $vehicles->count() + $key+1 }}. </span> {{ $otherVehicle->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{ $otherVehicle->system_code_currency ."". $otherVehicle->system_code_amount }}</td>
                        @endif
                        <td>{{ $otherVehicle->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($vehicleUnitPrice, 2) }}</td>
                        <td>
{{--                                <?php $totalAmount = $otherVehicle->vehicle_unit_price * $otherVehicle->quantity ?>--}}
                            {{ $quotation->currency ." ". number_format($totalAmount, 2) }}</td>
                    </tr>
                    @if($otherVehicle->quotation_addon_items->count() > 0)
                        @foreach($otherVehicle->quotation_addon_items as $key => $otherVehicleAddon)
                            <tr style="color: #643702">
                                <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>
                                    {{ $otherVehicleAddon->quotationItem->description ?? ''}}</td>
                                    @if($quotationDetail->cb_name)
                                <td> {{$otherVehicleAddon->quotationItem->system_code_currency ."". $otherVehicleAddon->quotationItem->system_code_amount }}</td>
                                @endif
                                <td>{{ $otherVehicleAddon->quotationItem->quantity ?? ''}}</td>
                                <td>{{ $quotation->currency ." ". number_format($otherVehicleAddon->quotationItem->unit_price, 2) }}</td>
                                <td>{{ $quotation->currency ." ". number_format($otherVehicleAddon->quotationItem->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                @foreach($vehicleWithBrands as $key => $vehicleWithBrand)
                    <tr style="color: #02023f;">
                            <?php $shippingPerVehiclequantityPrice = $shippingChargeDistriAmount / $vehicleWithBrand->quantity;
                            $vehicleUnitPrice = $vehicleWithBrand->vehicle_unit_price + $shippingPerVehiclequantityPrice;
                            $totalAmount = $vehicleUnitPrice * $vehicleWithBrand->quantity ?>
                        <td> <span style="font-weight: bold;font-size: 14px;" > {{$vehicles->count() + $otherVehicles->count() + $key+1 }}. </span> {{ $vehicleWithBrand->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{ $vehicleWithBrand->system_code_currency ."". $vehicleWithBrand->system_code_amount }}</td>
                        @endif
                        <td>{{ $vehicleWithBrand->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($vehicleUnitPrice, 2) }}</td>
                        <td>
{{--                                <?php $totalAmount = $vehicleWithBrand->vehicle_unit_price * $vehicleWithBrand->quantity ?>--}}
                            {{ $quotation->currency ." ". number_format($totalAmount, 2) }}</td>
                    </tr>
                    @if($vehicleWithBrand->quotation_addon_items->count() > 0)
                        @foreach($vehicleWithBrand->quotation_addon_items as $key => $otherVehicleWithBrandAddon)
                            <tr style="color: #643702">
                                <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>
                                    {{ $otherVehicleWithBrandAddon->quotationItem->description ?? ''}}</td>
                                    @if($quotationDetail->cb_name)
                                <td> {{$otherVehicleWithBrandAddon->quotationItem->system_code_currency ."". $otherVehicleWithBrandAddon->quotationItem->system_code_amount }}</td>
                                @endif
                                <td>{{ $otherVehicleWithBrandAddon->quotationItem->quantity ?? ''}} </td>
                                <td>{{ $quotation->currency ." ". number_format($otherVehicleWithBrandAddon->quotationItem->unit_price, 2) }}</td>
                                <td>{{ $quotation->currency ." ". number_format($otherVehicleWithBrandAddon->quotationItem->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
            @if($shippingDocuments->count() > 0 || $shippingCharges->count() > 0)
                <tr style="font-size: 12px;">
                    <th colspan="5">LOGISTICS</th>
{{--                    <th>SYSTEM CODE</th>--}}
{{--                    <th>QTY</th>--}}
{{--                    <th>PRICE</th>--}}
{{--                    <th>AMOUNT</th>--}}
                </tr>
                @foreach($shippingCharges as $key => $shippingCharge)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>{{ $shippingCharge->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{$shippingCharge->system_code_currency ."". $shippingCharge->system_code_amount }}</td>
                        @endif
                        <td>{{ $shippingCharge->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCharge->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCharge->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($shippingDocuments as $key => $shippingDocument)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $shippingCharges->count() + $key+1 }}. </span> {{ $shippingDocument->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{$shippingDocument->system_code_currency ."". $shippingDocument->system_code_amount }}</td>
                        @endif
                        <td>{{ $shippingDocument->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingDocument->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif

            @if($addons->count() > 0 )
                <tr style="font-size: 12px;">
                    <th colspan="5"> ADDONS AND EXTRA ITEM </th>
{{--                    <th>SYSTEM CODE</th>--}}
{{--                    <th>QTY</th>--}}
{{--                    <th>PRICE</th>--}}
{{--                    <th>AMOUNT</th>--}}
                </tr>
                @foreach($addons as $key => $addon)
                    <tr>
                        <td> <span style="font-weight: bold;margin-right: 5px;" > {{ $key + 1 }}. </span> {{ $addon->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{ $addon->system_code_currency ."". $addon->system_code_amount }}</td>
                        @endif
                        <td>{{ $addon->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($addon->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($addon->total_amount, 2) }}</td>
                    </tr>
                @endforeach
{{--                @foreach($directlyAddedAddons as $key => $directlyAddedAddon)--}}
{{--                    <tr>--}}
{{--                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $addons->count() + $key+1 }}. </span> {{ $directlyAddedAddon->description }}</td>--}}
{{--                        <td> {{ $directlyAddedAddon->system_code_currency ."". $directlyAddedAddon->system_code_amount }}</td>--}}
{{--                        <td>{{ $directlyAddedAddon->quantity }}</td>--}}
{{--                        <td>{{ $quotation->currency ." ". number_format($directlyAddedAddon->unit_price, 2) }}</td>--}}
{{--                        <td>{{ $quotation->currency ." ". number_format($directlyAddedAddon->total_amount, 2) }}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
                    @foreach($OtherAddons as $key => $OtherAddon)
                        <tr>
                            <td><span style="font-weight: bold;margin-right: 5px;" > {{ $addons->count() + $key+1 }}. </span> {{ $OtherAddon->description }}</td>
                            @if($quotationDetail->cb_name)
                            <td> {{$OtherAddon->system_code_currency ."". $OtherAddon->system_code_amount }}</td>
                            @endif
                            <td>{{ $OtherAddon->quantity }}</td>
                            <td>{{ $quotation->currency ." ". number_format($OtherAddon->unit_price, 2) }}</td>
                            <td>{{ $quotation->currency ." ". number_format($OtherAddon->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                @if($addonsTotalAmount > 0)
                    <tr>
                        <td colspan="4">Addons</td>
                        <td>  {{ $quotation->currency }} {{ number_format($addonsTotalAmount, 2) }}</td>
                    </tr>
                @endif

            @endif
            @if($shippingCertifications->count() > 0 || $otherDocuments->count() > 0)
                <tr style="font-size: 12px;">
                    <th colspan="5"> COMPLIANCE AND CERTIFICATES</th>
{{--                    <th>SYSTEM CODE</th>--}}
{{--                    <th>QTY</th>--}}
{{--                    <th>PRICE</th>--}}
{{--                    <th>AMOUNT</th>--}}
                </tr>
                @foreach($shippingCertifications as $key => $shippingCertification)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $key+1 }}. </span>  {{ $shippingCertification->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{ $shippingCertification->system_code_currency ."". $shippingCertification->system_code_amount }}</td>
                        @endif
                        <td>{{ $shippingCertification->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCertification->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($shippingCertification->total_amount, 2) }}</td>
                    </tr>
                @endforeach
                @foreach($otherDocuments as $key => $otherDocument)
                    <tr>
                        <td><span style="font-weight: bold;margin-right: 5px;" > {{ $shippingCertifications->count() + $key+1 }}. </span>  {{ $otherDocument->description }}</td>
                        @if($quotationDetail->cb_name)
                        <td> {{$otherDocument->system_code_currency ."". $otherDocument->system_code_amount }}</td>
                        @endif
                        <td>{{ $otherDocument->quantity }}</td>
                        <td>{{ $quotation->currency ." ". number_format($otherDocument->unit_price, 2) }}</td>
                        <td>{{ $quotation->currency ." ". number_format($otherDocument->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        
        <table style="color: black;width: 100%;">
    <tr>
        <td style="font-weight: bold;text-align: left;vertical-align: top;width: 55%;">
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <p>Note:- Third Party Payments will not be accepted.</p>
            @if($quotation->document_type == 'Proforma Invoice')
                @if($quotationDetail->selected_bank == "rak-aed")
                Account Name : MlLELE MOTORS FZE<br>
                IBAN : AE230400000882723910001<br>
                Account No : 0882723910001<br>
                Swift Code : NRAKAEAK<br>
                Bank Name : RAK BANK<br>
                Branch Name : DRAGON MART<br>
                Bank Address : DUBAI UAE<br>
                @elseif ($quotationDetail->selected_bank == "rak-usd")
                Account Name : MlLELE MOTORS FZE<br>
                IBAN : AE930400000882723910002<br>
                Account No : 0882723910002<br>
                Swift Code : NRAKAEAK<br>
                Bank Name : RAK BANK<br>
                Branch Name : DRAGON MART<br>
                Bank Address : DUBAI UAE<br>
                @elseif ($quotationDetail->selected_bank == "city-aed")
                Account Name : MlLELE MOTORS FZE<br>
                IBAN : AE880211000000110720211<br>
                Account No : 0110720211<br>
                Swift Code : CITIAEAD<br>
                Bank Name : CITI BANK N.A<br>
                Branch Name : AL WASL BRANCH<br>
                Bank Address : DUBAI UAE<br>
                @else
                Account Name : MlLELE MOTORS FZE<br>
                IBAN : AE380211000000110720238<br>
                Account No : 0110720238<br>
                Swift Code : CITIAEAD<br>
                Bank Name : CITI BANK N.A<br>
                Branch Name : AL WASL BRANCH<br>
                Bank Address : DUBAI UAE<br>
                @endif
            </div>
            @endif
        </td>
        <td style="vertical-align: top;width: 45%;">
    <table style="width: 100%;">
    @if($quotation->currency == "AED")
    <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                Net Total In AED:
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                {{ $quotation->currency ." ". number_format($quotation->deal_value) }}
            </td>
        </tr>
    @endif
    @if($quotation->currency == "USD")
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                Net Total In USD:
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                {{ $quotation->currency ." ". number_format($quotation->deal_value) }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                Net Total In AED:
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                {{"AED ". number_format($quotation->deal_value * 3.65) }}
            </td>
        </tr>
        @endif
        @if($quotation->currency == "EUR")
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                Net Total In EUR:
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                {{ $quotation->currency ." ". number_format($quotation->deal_value) }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                Net Total In AED:
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px; width: 50%;">
                {{"AED ". number_format($quotation->deal_value * 4) }}
            </td>
        </tr>
        @endif
        @if($quotation->document_type == 'Proforma Invoice')
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                Advance Paid:
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                {{ $quotation->currency ." ". number_format($quotationDetail->advance_amount, 2) }}
            </td>
        </tr>
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                Remaining Amount({{ $quotation->currency }}):
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                {{ $quotation->currency ." ". number_format($quotation->deal_value - $quotationDetail->advance_amount) }}
            </td>
        </tr>
        @if($quotation->currency != 'AED' && $quotation->shippingDocument == 'EXW')
        <tr>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                Remaining Amount(AED):
            </td>
            <td style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                @if($quotation->currency == 'USD')
                {{ $quotation->currency ." ". number_format(($quotation->deal_value - $quotationDetail->advance_amount)  * $aed_to_usd_rate->value, 2) }}
                @elseif($quotation->currency == 'EUR')
                {{ $quotation->currency ." ". number_format(($quotation->deal_value - $quotationDetail->advance_amount)  * $aed_to_eru_rate->value, 2) }}
                @endif
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="2" style="border: 1px solid #ccc; padding: 5px; margin-bottom: 5px;">
                * VAT is not applicable for Export Bill
            </td>
        </tr>
        @endif
    </table>
</td>

    </tr>
</table>
        @if($quotation->document_type == 'Proforma Invoice')
        @if($quotationDetail->advance_amount)
        <br>
        <table>
                    <tr style="font-size: 12px;">
                        <th colspan="4"> DEPOSIT / PAYMENT RECEIVED</th>
                        <th>AMOUNT</th>
                    </tr>
                    <tr>
                        <td colspan="4">Deposit</td>
                        <td> {{ $quotation->currency ." ". number_format($quotationDetail->advance_amount, 2) }}</td>
                    </tr>
                    </table>
                @endif
                @endif
    <br>
    
        @if($quotation->document_type == 'Proforma Invoice')
        @php
        $due_date = $quotationDetail->due_date;
        $formatted_due_date = date("j F Y", strtotime($due_date));
        @endphp
        <p style="font-weight: bolder">Payment Due Date:  {{$formatted_due_date}}</p>
        @endif
        <p style="text-align: justify;">
        I hereby acknowledge to honor the payment by the agreed due date.
        In case of my failure to clear payment on time, I stand to lose the right to my payments and my order may be delayed or subject to cancellation.
        @if($quotation->shipping_method == 'CNF')
        Customs clearance, taxes, duty, value added taxes or any other charges related to the above mentioned goods are the sole responsibility of the client.
        @endif
            Any payments which are made to Milele Motors FZE are non refundable & the price will be changed based on the new market price, and seller has right to sell the cars
            without prior notice to buyer.
            Upon initiating any transaction with Milele Motors FZE, the buyer acknowledges and unconditionally agrees to our terms and conditions. It is expressly understood that any payment by the
            buyer, whether as advances, deposits, or other payments, is non-refundable under any circumstances. The buyer confirms the sale and recognizes its binding nature by making
            payments. Furthermore, any products or services procured are strictly non-exchangeable and non-returnable. Even without a physical signature, such a transfer signifies a binding and
            unilateral acceptance of these terms. Before making any transaction, the buyer has had the full opportunity to review these terms in detail, thereby affirming their understanding and
            acceptance.
            </p>
        @if($quotation->shipping_method == 'EXW')
               <p style="font-weight: bolder"> Currency Exchange </p>
               Bank Payments AED transfers at actuals. USD transfer at  {{ $aed_to_usd_rate->value }} and customer must remit $50 equivalent extra to cover for bank fees.
                Cash Payments AED at actuals, USD New Bills $100 at {{ $aed_to_usd_rate->value }}, all other bills at 3.60.
            @if($quotation->currency == 'EURO')
                <p style="font-weight: bolder"> Currency Exchange </p>
Bank Payments AED transfers at actuals. EUR transfer at {{ $aed_to_eru_rate->value }} and customer must remit EUR 50 equivalent extra to cover for bank fees.
Cash Payments AED at actuals, USD New Bills EUR 100 at {{ $aed_to_eru_rate->value }}, all other bills at
                    {{ $aed_to_eru_rate }}.
            @endif
        @endif
        <div class="footer">
        <p>Client Name: {{ $data['client_name'] }}</p>
        <p>Signature: _______________________</p>
        <p>Date: {{ $data['document_date'] }}</p>
    </div>
    </div>
</body>
</html>

