
<!DOCTYPE html>
<html>
<head>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" > -->
    <style>
        /*@page { size: 700pt }*/
        
    .page {
        margin-left: 70px;
        margin-right: 70px;
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
        /* min-height:50px !important; */
    }
    </style>
</head>
<body>
    <div class="page">
    <img src="{{ public_path('images/pfi_ams_logo.jpg') }}" width="75px" height="60px" >
    <img src="{{ public_path('images/pfi_cps_logo.png') }}" style="margin-top:30px;margin-left:10px;"  width="70px" height="40px" >
    <br>
        <p>Thank you for your inquiry. Please find hereunder our offer. </span>
        <p style="font-size:12px;">
           P-F Invoice {{ \Illuminate\Support\Carbon::now()->format('Y')}}  n°: {{ $pfi->pfi_reference_number }}
            <span style="margin-left:200px;"> Date: </span>
            <span style="font-weight:normal;margin-left:20px"> {{ \Illuminate\Support\Carbon::now()->format('d/m/Y')}} </span>
        </p>

        <p> Buyer : MILELE MOTORS <span  style="margin-left:200px;"> End user: </span>
            <span style="margin-left:20px;">{{ strtoupper($pfi->customer->name ?? '') }} </span>
        </p>
        <p style="margin-left:40px;margin-bottom:0px;"> <span style="font-size:9px"> SAMARI RETAIL BLOC A</span>
            <span style="margin-left:245px;"> {{ strtoupper($pfi->country->name ?? '')}} </span>
        </p>
        <p style="margin-left:40px;margin-top:1px;font-size:9px">RAS EL KHOR- DUBAI-UAE </p>
        <table style="margin-top:20px;padding:0px;">
            <tr>
                <td>Description</td>
                <td>Product Code</td>
                <td></td>
                <td>Availability</td>
                <td>Quantity</td>
                <td>Unit Price</td>
                <td>Total Price</td>
            </tr>
            @foreach($pfiItems as $pfiItem)
                <tr>
                    <td style="width:200px">HILUX DC 2393CC 2GD-DIESEL 4X4 6P
                    WORK (LOW) J-DECK 6MT CANVAS </td>
                    <td>{{ $pfiItem->masterModel->model ?? '' }}</td>
                    <td>{{ $pfiItem->masterModel->sfx ?? '' }}</td>
                    <td style="font-weight:normal">Stock</td>
                    <td>{{ $pfiItem->pfi_quantity }}</td>
                    <td>{{ $pfi->currency }} {{ number_format($pfiItem->unit_price, 2)}}</td>
                    <td>{{ $pfi->currency }} {{ number_format(($pfiItem->pfi_quantity * $pfiItem->unit_price),2) }}</td>
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
        </table>

        <p>TOTAL EXW Jebel Ali Incoterms ® 2010</p>
        <p>Terms of price :  </p>
    </div>
</body>
</html>


