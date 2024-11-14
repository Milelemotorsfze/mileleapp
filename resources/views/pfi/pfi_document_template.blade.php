@extends('layouts.main')
@section('content')

    <style>
        @page { size: 700pt }
        
    .page {
        margin-left: 50px;
        margin-right: 100px;
        font-size:10px;
        font-family: arial, sans-serif;
    }
    p{
        font-weight:bold;
    }
    table ,td{
        font-family: arial, sans-serif;
        width: 100%;
        border: 1px solid #1c1b1b;
        border-collapse: collapse;
        font-weight:bold;
        padding:5px;
        text-align:center;
    }
    #pfi-items {
        margin-top:20px;
        margin-bottom:10px;
        padding:0px;
        border-left:none;
        border-right:none;
        border-bottom:none
    }
    .total-row-tr {
        height:20px;
        border-right:none;
        border-left:none
    }
    .address{
        margin-left:40px;
        margin-top:1px;
        font-size:10px;
    }
    .sfx-width{
        min-width:30px !important;
    }
    .cps-img{
        margin-left:0px;
        width:100px !important;
        padding-left:0px;
    }
    .date{
        font-weight:normal;
        margin-left:20px
    }
    .noraml-font {
        font-weight:normal;
        margin:0px;
    }
    .bold{
        font-weight:bold;
    }
    @media only screen and (min-device-width: 1200px)
        {
            .container{
                max-width: 850px; !important;
            }
        }
        .logo{
            width:100px !important;  
         }
         .border-outline {
            border: 1px solid #0f0f0f;
            padding: 10px !important;
        }
    </style>
    @can('PFI-list')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('PFI-list');
        @endphp
        @if ($hasPermission)
        <footer>
            <!-- Add footer content -->
        </footer>
        <div class="card-header">
            <h4 class="card-title">PFI Document </h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ route('pfi.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            <a  class="btn btn-sm btn-primary float-end"  style="margin-right:5px;" href="{{ route('pfi-item.list') }}" title="PFI Item Lists" >
                <i class="fa fa-table" aria-hidden="true"></i>  View PFI Items</a>
                
        </div>
        <div class="card-body">
            <div class="container" >
                <form action="{{ route('pfi.pfi-document') }}">
                    <input type="hidden" name="download" value="1">
                    <input type="hidden" name="id" value="{{ \Crypt::encrypt($pfi->id) }}">
                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-primary text-center mb-4 col-2"> Download <i class="fa fa-download"></i>
                        </button>
                    </div>
                </form>
                <div class="border-outline mt-3">
                    <div class="row">
                        <div class="col-md-2 pr-0">
                            <img src="{{ url('images/pfi_ams_logo.jpg') }}" height="80px" class="logo">

                        </div>
                        <div class="col-md-2 mt-4">
                            <img src="{{ url('images/pfi_cps_logo.png') }}" class="cps-img"  height="70px" >
                        </div>
                    </div>
                    <br>
                    <p>Thank you for your inquiry. Please find hereunder our offer. </span>
                    <p style="font-size:12px;">
                        P-F Invoice {{ \Illuminate\Support\Carbon::now()->format('Y')}}  n°: {{ $pfi->pfi_reference_number }}
                        <span style="margin-left:300px;"> Date: </span>
                        <span class="date"> {{ \Illuminate\Support\Carbon::now()->format('d/m/Y')}} </span>
                    </p>
                    <p style="margin-bottom:8px;"> Buyer : MILELE MOTORS <span  style="margin-left:200px;"> End user: </span>
                        <span style="margin-left:15px;">{{ strtoupper(substr($pfi->customer->name, 0, 15)) }} </span>
                    </p>
                    <p style="margin-left:40px;margin-bottom:0px;"> <span style="font-size:10px;font-weight:bold"> SAMARI RETAIL BLOC A</span>
                        <span style="margin-left:295px;"> {{ strtoupper($pfi->country->name ?? '')}} </span>
                    </p>
                    <p class="address fw-bold">RAS EL KHOR- DUBAI-UAE </p>
                    <table id="pfi-items">
                        <tr>
                            <td>Description</td>
                            <td>Product Code</td>
                            <td class="sfx-width"></td>
                            <td>Availability</td>
                            <td>Quantity</td>
                            <td>Unit Price</td>
                            <td>Total Price</td>
                        </tr>
                        @foreach($pfiItems as $pfiItem)
                            <tr>
                                <td>{{ $pfiItem->masterModel->model_description ?? ''}}</td>
                                <td>{{ $pfiItem->masterModel->pfi_model ?? $pfiItem->masterModel->model }}</td>
                                <td>{{ $pfiItem->masterModel->pfi_sfx ?? $pfiItem->masterModel->sfx }}</td>
                                <td style="font-weight:normal">Stock</td>
                                <td>{{ $pfiItem->pfi_quantity }}</td>
                                <td style="width:80px">{{ $pfi->currency }} {{ number_format($pfiItem->unit_price)}}</td>
                                <td style="width:80px">{{ $pfi->currency }} {{ number_format(($pfiItem->pfi_quantity * $pfiItem->unit_price)) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="height:20px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="height:20px;border:none"></td>
                            <td class="total-row-tr" colspan="6"></td>
                        </tr>
                        <tr>
                            <td style="border:none"></td>
                            <td style="border:2px:solid black;border-right:none" colspan="5">TOTAL EXW Jebel Ali Incoterms ® 2010</td>
                            <td style="border:2px:solid black;border-left:none;" > {{ $pfi->currency }} {{ number_format($pfi->amount)}}</td>
                        </tr>
                    </table>
                    <p  class="noraml-font">
                        <span class="bold">Terms of price : </span>
                        <span class="noraml-font" style="margin-left:100px;">Jebel Ali</span>
                        <span  class="noraml-font" style="margin-left:200px;">Incoterms ® 2010</span>
                    </p>
                    <p  class="noraml-font"> 
                        <span class="bold">Final destination : </span> 
                        <span class="noraml-font" style="margin-left:50px;"> {{ strtoupper($pfi->country->name ?? '') }}</span>
                    </p>
                    <p class="noraml-font"> 
                        <span class="bold">Validity of the offer :</span> 
                        <span class="noraml-font" style="margin-left:40px;"> 30 d</span>
                    </p>
                    <p class="noraml-font" >
                        <span class="bold">Terms of payment :</span> 
                        <span style="margin-left:45px;"> bank transfer</span>
                    </p>
                    <p style="margin:0px"> Warranty :  </p>
                    <p style="margin:0px"> Bank information :  </p>
                    <p class="noraml-font" style="color:red"> ({{$pfi->currency}}) </p>
                    <p class="noraml-font"> STANDARD CHARTERED BANK - Dubai - United Arab Emirates </p>
                    <p class="noraml-font">IBAN: AE04 0440 0001 0123 821 0701 SWIFT: SCBLAEADXXX</p>
                    <p style="margin:0px" >Remarks:</p>
                    <p class="noraml-font">Delivery time mentioned is EXW Jebel Ali and is valid at the date of the proforma and is subject to prior sales.</p>
                    <p class="noraml-font">Prohibition of resale after arrival in <span style="padding-left:30px"> {{ strtoupper($pfi->country->name ?? '')}} </sapn></p>
                    <p class="noraml-font">Copies of original B/L must be submitted to AMS ME</p>
                    <p style="margin:0px">We remain at your disposal for further information.</p>
                    <P style="text-align: center;bottom: 0;font-weight:normal;margin-top:100px">
                        AMS ME FZE - Office 1519, 15th Floor, Tower A, JAFZA ONE, Gate 5, Jebel Ali Free Zone - PO Box 17879 - Dubai - UAE

                    </p>
                
                </div>
            </div>
        </div>
    @endif
    @endcan
  @endsection


