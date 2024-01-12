@extends('layouts.main')
@section('content')
    <style>
        @page { size: A4; }
        @media only screen and (min-device-width: 1200px)
        {
            .container{
                max-width: 803px; !important;
            }
        }
        table {
            font-family: arial, sans-serif;
            /*border-collapse: collapse;*/

            width: 100%;
        }
        #vehicle-details td, th{
            border: 2px solid #1c1b1b;
            text-align: left;
            padding-left: 8px;

        }
        #basic-details td, th{
            padding-left: 8px;

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
        }

    </style>
<div class="row">
    <div class="container" >
        <form action="{{ route('letter-of-indents.generate-loi') }}">
            <input type="hidden" name="height" id="total-height" value="">
            <input type="hidden" name="width" id="width" value="">
            <input type="hidden" name="id" value="{{ $letterOfIndent->id }}">
            <input type="hidden" name="type" value="TRANS_CAR">

            <input type="hidden" name="download" value="1">
        <div class="text-end mt-3">
            <a  class="btn  btn-info float-end " style="margin-left: 10px;" href="{{ url()->previous() }}" >
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            <button type="submit" class="btn btn-primary mr-3"> Download <i class="fa fa-download"></i></button>
        </div>
        </form>
        <div class="container" style="margin-right: 50px;margin-left: 50px;" id="full-page">
            <img src="{{  url('images/trans_car_logo.png') }}"  alt="logo" style="width: 100px;height: 100px; margin-left: auto;display: block;
            margin-right: auto;">
            </span>
            <h4 class="fw-bold text-center pt-3">TRANSCARS</h4>
            <p class="text-center ">Convention Center, JAFZA, Dubai, United Arab Emirates </p>
            <p class="text-center">Email: <span style="text-decoration: underline;">general@transcars.net</span></p>
            <p class="text-center ">Website:<span style="text-decoration: underline;"> www.transcars.net </span></p>
            <h4 class="fw-bold text-center" style="margin-top: 20px;">QUOTATION </h4>
            <p class="fw-bold text-center">VAT TRN NO. 100057598400003</p>
            <br>
            <div class="card border-dark border-2 mb-2" >
                <table id="basic-details" >
                    <tr>
                        <td class="fw-bold">CUSTOMER:</td>
                        <td class="fw-bold">{{ strtoupper($letterOfIndent->customer->name) }}</td>
                        <td class="fw-bold">QUOTATION NUMBER: </td>
                        <td><span class="bg-light-grey ">53426725967498</span></td>
                    </tr>
                    <tr>
                        <td>Alias: </td>
                        <td ><span class="bg-light-grey ">53426725967498vfdfvdvdvfvd</span></td>
                        <td>Date: </td>
                        <td>{{ \Illuminate\Support\Carbon::parse($letterOfIndent->date)->format('d/m/Y')}}</td>
                    </tr>
                    <tr>
                        <td>Contact:</td>
                        <td><span class="bg-light-grey ">53426725967498vfdfvdvdvfvd</span></td>
                        <td>Salesperson:</td>
                        <td><span class="bg-light-grey">53426725967498</span></td>
                    </tr>
                    <tr>
                        <td>Address: </td>
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
                <tr class="bg-light-grey text-dark fw-bold"">
                <td colspan="5">1. GENERAL INFORMATION</td>
                </tr>
                <tr class="bg-light-grey text-dark fw-bold"">
                <th width="50px">S No:</th>
                <th>1A. VEHICLE</th>
                <th>QTY</th>
                <th>PRICE </th>
                <th>AMOUNT (USD)</th>
                </tr>

                @foreach($letterOfIndentItems as $key => $letterOfIndentItem)
                    <tr >
                        <td >{{ $key + 1 }}</td>
                        <td>
                            <p style="font-weight: bold"> {{ $letterOfIndentItem->masterModel->variant->master_model_lines->model_line ?? '' }} {{ $letterOfIndentItem->masterModel->variant->engine_type ?? ''}}
                                {{ $letterOfIndentItem->masterModel->variant->fuel_type ?? ''}} {{ $letterOfIndentItem->masterModel->steering }}</p>
                            <p>Make: {{$letterOfIndentItem->masterModel->variant->brand->brand_name ?? ''}}</p>
                            <p>Model: {{$letterOfIndentItem->masterModel->model_year}} Brand New Zero km</p>
                        </td>
                        <td >{{$letterOfIndentItem->quantity}}</td>
                        <td class="bg-light-grey " ></td>
                        <td class="bg-light-grey "></td>
                    </tr>
                @endforeach
                <tr class="bg-light-grey text-dark fw-bold">
                <td colspan="5"  >1B. LOGISTICS </td>
                </tr>
                <tr>
                    <td> </td>
                    <td>CNF - Shipment Method </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="fw-bold">GROSS TOTAL (USD) </p>
                        <p class="fw-bold">DISCOUNT APPLIED </p>
                        <p class="fw-bold">VAT NOT APPLICABLE (EXPORT BILL) </p>
                        <p>NET TOTAL VALUE (USD) TOTAL </p>
                        <p>INVOICE VALUE (AED) </p>
                        <img src="{{ url('images/trans_car_seal.png') }}" class="overlay-image" style="height: 125px;width: 140px">
                    </td>
                    <td class="bg-light-grey text-dark fw-bold"> </td>
                    <td class="bg-light-grey text-dark fw-bold" ></td>
                    <td class="bg-light-grey text-dark fw-bold" ></td>
                </tr>
            </table>
            <div class="pb-2 ">
                <div style="text-align: center;bottom: 0">
                    Note: If you have queries or concerns please feel free to contact us on the information provided above.
                </div>
                <p id="test"></p>
        </div>

    </div>
</div>
    <script type="text/javascript">
        var height = document.getElementById('full-page').offsetHeight;
        const values = ["240", "260", "220"];
        const random = Math.floor(Math.random() * values.length);
        var imageWidth = values[random];
        console.log(imageWidth);
        var imageHeight = height - 150;
        $('#total-height').val(imageHeight);
        $('#width').val(imageWidth);
        $('.overlay-image').css('left', imageWidth+'px');
        $('.overlay-image').css('top', imageHeight+'px' )

    </script>
@endsection


