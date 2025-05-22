
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        
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
    .sfx-width{
        min-width:30px !important;
    }
    .model-width{
        min-width:100px !important;
    }
    .total-row-tr {
        height:20px;
        border-right:none;
        border-left:none
    }
    .address{
        margin-left:40px;
        margin-top:1px;
        font-size:9px
    }
    .cps-img{
        margin-top:30px;
        margin-left:10px;
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
    footer {
        position: fixed;
        bottom: 0cm;
        left: 0cm;
        right: 0cm;
        text-align: center;
        font-size:10px;
        font-family: arial, sans-serif;
    }
    </style>
</head>
<body>
    <footer>
        <!-- Add footer content -->
        AMS ME FZE - Office 1519, 15th Floor, Tower A, JAFZA ONE, Gate 5, Jebel Ali Free Zone - PO Box 17879 - Dubai - UAE
    </footer>
    <main>
        <div class="page">
        <img src="{{ public_path('images/pfi_ams_logo.jpg') }}" width="75px" height="60px" >
        <img src="{{ public_path('images/pfi_cps_logo.png') }}" class="cps-img"  width="70px" height="40px" >
        <br>
            <p>Thank you for your inquiry. Please find hereunder our offer. </span>
            <p style="font-size:12px;">
            P-F Invoice {{ \Illuminate\Support\Carbon::now()->format('Y')}}  n°: {{ $pfi->pfi_reference_number }}
                <span style="margin-left:200px;"> Date: </span>
                <span class="date"> {{ \Illuminate\Support\Carbon::now()->format('d/m/Y')}} </span>
            </p>
            <p  style="margin-bottom:8px;"> Buyer : MILELE MOTORS FZE<span  style="margin-left:183px;"> End user: </span>
                <span >{{ strtoupper(substr($pfi->customer->name, 0,15)) }} </span>
            </p>
            <p style="margin-left:40px;margin-bottom:0px;width:100%"> <span style="font-size:8px;font-wight:800px;"> SAMARI RETAIL BLOC A</span>
                <span style="margin-left:220px;"> {{ strtoupper($pfi->country->name ?? '') }}</span>
            </p>
            <p class="address" style="font-size:8px;font-wight:800px">RAS EL KHOR- DUBAI-UAE </p>
            <table id="pfi-items">
                <tr>
                    <td>Description</td>
                    <td class="model-width">Product Code</td>
                    <td class="sfx-width"></td>
                    <td>Availability</td>
                    <td>Quantity</td>
                    <td>Unit Price</td>
                    <td>Total Price</td>
                </tr>
                @foreach($pfiItems as $pfiItem)
                    <tr>
                        <td style="width:180px">{{ $pfiItem->masterModel->model_description ?? ''}} </td>
                        <td>{{ $pfiItem->masterModel->pfi_model ??  $pfiItem->masterModel->model}}</td>
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
            <div class="row">
                <img src="{{ public_path('images/pfi_terms_and_conditions1.png') }}" > </img>
                <img src="{{ public_path('images/pfi_terms_and_conditions2.png') }}" > </img>
            </div>
        </div>
    </main>
</body>
</html>


