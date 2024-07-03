<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        /*@page { size: 700pt }*/
        /*.content{*/
        /*    font-family: arial, sans-serif;*/
        /*    background-color: #f6f5f5;*/
        /*}*/
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        #vehicle-details td, th{
            border: 2px solid #1c1b1b;
            text-align: left;
            padding-left: 8px;
            font-size: 14px;

        }
        #basic-details td, th{
            padding-left: 8px;
            font-size: 14px;
        }
        .bg-light-grey{
            background-color: #ababaf;
            color: #ababaf;
        }
        p {
            margin-bottom: 0px;
        }
        .overlay-image {
            position: absolute;
            z-index: 1;
            left:  {{ $width }}px;
            /* bottom: 50px; */

        }
        .page_break { page-break-before: always; }
    </style>
</head>
<body>
<div class="row">
    <div style="text-align: center">
        <img src="{{ public_path('images/trans_car_logo.png') }}"  alt="logo" style="width: 100px;height: 100px;">
    </div>
    <h4 class="fw-bold text-center pt-3">TRANSCARS</h4>
    <p class="text-center ">Convention Center, JAFZA, Dubai, United Arab Emirates </p>
    <p class="text-center">Email:<span style="text-decoration: underline;"> general@transcars.net </span></p>
    <p class="text-center ">Website: <span style="text-decoration: underline;"> www.transcars.net </span> </p>
    <h4 class="fw-bold text-center" style="margin-top: 20px;">QUOTATION </h4>
    <p class="fw-bold text-center">VAT TRN NO. 100057598400003</p>
    <br>
    <div class="card border-dark border-2 mb-2" >
        <table id="basic-details" >
            <tr>
                <td class="fw-bold">CUSTOMER:</td>
                <td class="fw-bold">{{ strtoupper($letterOfIndent->customer->name) }}</td>
                <td class="fw-bold">QUOTATION NUMBER: </td>
                <td><span class="bg-light-grey">53426725967498</span></td>
            </tr>
            <tr>
                <td>Alias: </td>
                <td ><span class="bg-light-grey">53426725967498vfdfvdvdvfvd</span></td>
                <td>Date: </td>
                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}}</td>
            </tr>
            <tr>
                <td>Contact</td>
                <td><span class="bg-light-grey">53426725967498vfdfvdvdvfvd</span></td>
                <td>Salesperson:</td>
                <td><span class="bg-light-grey">53426725967498</span></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><span class="bg-light-grey">53426725967498vfdfvdvdvfvd</span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>{{ $letterOfIndent->customer->country->name ?? '' }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br>
    </div>

    <table id="vehicle-details" >
        <tr class="bg-light-grey text-dark fw-bold">
            <td colspan="5">1. GENERAL INFORMATION</td>
        </tr>
        <tr class="bg-light-grey text-dark fw-bold">
            <th width="50px">SL</th>
            <th>1A. VEHICLE</th>
            <th>QTY</th>
            <th>PRICE </th>
            <th>AMOUNT (USD)</th>
        </tr>

        @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <p style="font-weight: bold">
                        {{ $letterOfIndentItem->masterModel->transcar_loi_description ?? '' }}
                    </p>
                    <p>Model: {{$letterOfIndentItem->masterModel->model_year}}  Brand New Zero km</p>
                </td>
                <td>{{$letterOfIndentItem->quantity}}</td>
                <td class="bg-light-grey"></td>
                <td class="bg-light-grey"></td>
            </tr>
        @endforeach
        <tr class="bg-light-grey text-dark fw-bold">
            <td colspan="5" >1B. LOGISTICS </td>
        </tr>
        <tr>
            <td>  <img src="{{ public_path('images/LOI/transcar_seal.png') }}" class="overlay-image" style="height: 125px;width: 120px;"></td>
            <td>CNF - Shipment Method </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 13px;">
                <p class="fw-bold">GROSS TOTAL (USD) </p>
                <p class="fw-bold">DISCOUNT APPLIED </p>
                <p class="fw-bold">VAT NOT APPLICABLE (EXPORT BILL) </p>
                <p>NET TOTAL VALUE (USD) TOTAL </p>
                <p>INVOICE VALUE (AED) </p>
               
            </td>
            <td class="bg-light-grey text-dark fw-bold" > </td>
            <td class="bg-light-grey text-dark fw-bold" ></td>
            <td class="bg-light-grey text-dark fw-bold" ></td>
        </tr>
    </table>
    <div class="row" style="margin-bottom: 20px;">
        <div style="text-align: center;position: absolute;bottom:0">
            Note: If you have queries or concerns please feel free to contact us on the information provided above.
        </div>
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



