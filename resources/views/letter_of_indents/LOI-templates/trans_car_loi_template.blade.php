<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        @page { size: 700pt }
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
            border: 3px solid #1c1b1b;
            text-align: left;
            padding-left: 8px;

        }
        #basic-details td, th{
            padding-left: 8px;

        }
        .bg-light-grey{
            background-color: #aeb3bb;
        }
        p {
          margin-bottom: 0px;
         }


    </style>
</head>
<body>
<div class="row">
    <img src="{{  public_path('trans_car_logo.png') }}"  alt="logo" style="width: 100px;height: 100px; margin-left: auto;display: block;
            margin-right: auto;">
    </span>
    <h4 class="fw-bold text-center pt-3">TRANSCARS</h4>
    <p class="text-center ">Convention Center, JAFZA, Dubai, United Arab Emirates </p>
    <p class="text-center">Email: qeneral@transcars.net</p>
    <p class="text-center ">Website: www.transcars.n</p>
    <h4 class="fw-bold text-center ">QUOTATION </h4>
    <p class="fw-bold text-center">VAT TRN NO. 100057598400003</p>
    <br>
    <div class="card border-dark border-3 mb-2" >
        <table id="basic-details" >
            <tr>
                <td class="fw-bold">CUSTOMER:</td>
                <td class="fw-bold">{{ strtoupper($letterOfIndent->customer->name) }}</td>
                <td>QUOTATION NUMBER: </td>
                <td><span class="bg-secondary text-secondary">53426725967498</span></td>
            </tr>
            <tr>
                <td>Alias: </td>
                <td ><span class="bg-secondary text-secondary">53426725967498vfdfvdvdvfvd</span></td>
                <td>Date: </td>
                <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}}</td>
            </tr>
            <tr>
                <td>Contact</td>
                <td><span class="bg-secondary text-secondary">53426725967498vfdfvdvdvfvd</span></td>
                <td>Salesperson:</td>
                <td><span class="bg-secondary text-secondary">53426725967498</span></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><span class="bg-secondary text-secondary">53426725967498vfdfvdvdvfvd</span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>{{ $letterOfIndent->customer->country }}</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br>
    </div>

    <table id="vehicle-details" >
        <tr class="bg-light-grey">
            <td colspan="5">1. GENERAL INFORMATION</td>
        </tr>
        <tr class="bg-light-grey">
            <th>SL</th>
            <th>1A. VEHICLE</th>
            <th>QTY</th>
            <th>PRICE </th>
            <th>AMOUNT (USD)</th>
        </tr>

        @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <p> {{ $letterOfIndentItem->variant_name }} {{ $letterOfIndentItem->Variant->engine_type ?? ''}} {{ $letterOfIndentItem->steering }}</p>
                    <p>Make: {{$letterOfIndentItem->Variant->brand->brand_name ?? ''}}</p>
                    <p>Model: </p>
                </td>
                <td >{{$letterOfIndentItem->quantity}}</td>
                <td class="bg-secondary "></td>
                <td class="bg-secondary "></td>
            </tr>
        @endforeach
        <tr class="bg-light-grey">
            <td colspan="5" >1B. LOGISTICS </td>
        </tr>
        <tr>
            <td> </td>
            <td>{{ $letterOfIndent->shipment_method }} - Shipment Method </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                <p class="fw-bold">ROSS TOTAL (USD) </p>
                <p class="fw-bold">DISCOUNT APPLIED </p>
                <p class="fw-bold">VAT NOT APPLICABLE (EXPORT BILL) </p>
                <p>NET TOTAL VALUE (USD) TOTAL </p>
                <p>INVOICE VALUE (AED) </p>
            </td>
            <td> </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <div class="row">
        <div style="text-align: center;position: absolute;bottom:0">
            Note: If you have queries or concerns please feel free to contact us on the information provided above.

        </div>
    </div>
</div>
</body>
</html>


