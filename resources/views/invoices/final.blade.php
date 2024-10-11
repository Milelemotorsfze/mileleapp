<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Invoice</title>
    <style>
         body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 10px 0;
        }

        .header img {
            width: 300px;
            margin-bottom: 10px;
        }

        .details-row {
            width: 100%;
            display: table;
            margin-bottom: 20px;
        }

        .details-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        /* Style to align the invoice details on the right, but left-align text inside */
        .details-column-right {
            text-align: right;
        }

        .details-column-right p {
            text-align: left; /* Ensure text inside is left-aligned */
            display: inline-block;
        }

        /* Ensures alignment of labels (DATE, POL, POD, etc.) */
        .details-label {
            display: inline-block;
            min-width: 100px; /* Adjust as needed to align labels */
            font-weight: bold;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .subtotal-section {
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 40px;
        }
        .custom-pricing-table {
        display: table;
        width: auto; /* Adjust based on your needs, e.g., set a specific width if required */
        margin-left: auto; /* Push the element to the right */
        font-size: 16px;
        text-align: left; /* Ensure that the text is left-aligned within the columns */
    }
    
    .label-column {
        display: table-cell;
        width: 150px; /* Set the width of the label column */
        font-weight: bold;
        padding-right: 10px; /* Space between label and value */
        vertical-align: top;
    }
    
    .value-column {
        display: table-cell;
        vertical-align: top;
    }
    
    .pricing-row {
        display: table-row;
    }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
        <img src="{{ public_path('images/proforma/milele_logo.png') }}" width="300px" height="80px" ><span class="logo-txt"></span>
            <h2><u>COMMERCIAL INVOICE</u></h2>
            <p>TRN - 100057588400003</p>
        </div>

        <!-- Client and Invoice Details Row -->
        <div class="details-row">
            <div class="details-column">
                <p><strong>TO:</strong> {{ $clientName }}<br> <strong>Email:</strong> {{ $clientEmail }}<br><strong>Phone:</strong> {{ $clientPhone }}</p>
            </div>
            <div class="details-column details-column-right">
                <p>
                    <span class="details-label">INVOICE NO: {{ $invoiceNumber }}</span><br>
                    <span class="details-label">DATE: {{ $invoiceDate }}</span><br>
                    <span class="details-label">POL: {{ $pol }}</span><br>
                    <span class="details-label">POD: {{ $pod }}</span>
                </p>
            </div>
        </div>

        <!-- Invoice Items Table -->
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Gross Amount</th>
                </tr>
            </thead>
            <tbody>
            @foreach($vehicles as $index => $vehicle)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            {{ $vehicle->brand_name }} {{ $vehicle->model_detail }} {{ $vehicle->my }}<br>
            VIN: {{ $vehicle->vin }}
        </td>
        <td>{{ $vehicle->qty }}</td>
        <td>{{ $currency }} {{ number_format((float) $vehicle->rate, 2) }}</td>
        <td>{{ $currency }} {{ number_format((float) $vehicle->ga, 2) }}</td>
    </tr>
    @endforeach
            </tbody>
        </table>
        <div class="subtotal-section">
    <div class="custom-pricing-table">
        <div class="pricing-row">
            <div class="label-column">SUB TOTAL:</div>
            <div class="value-column">{{ $currency }} {{ number_format($sub_total ?? 0) }}</div>
        </div>

        <div class="pricing-row">
            <div class="label-column">Discount:</div>
            <div class="value-column">{{ $currency }} {{ number_format($discount ?? 0) }}</div>
        </div>

        <div class="pricing-row">
            <div class="label-column">Net Amount:</div>
            <div class="value-column">{{ $currency }} {{ number_format($net_amount ?? 0) }}</div>
        </div>

        <div class="pricing-row">
            <div class="label-column">VAT:(0 %)</div>
            <div class="value-column">{{ $currency }} {{ number_format($vat ?? 0) }}</div>
        </div>

        <div class="pricing-row">
            <div class="label-column">Shipping Charges:</div>
            <div class="value-column">{{ $currency }} {{ number_format($shipping_charges ?? 0) }}</div>
        </div>

        <div class="pricing-row">
            <div class="label-column">Gross Amount:</div>
            <div class="value-column">{{ $currency }} {{ number_format($gross_amount ?? 0) }}</div>
        </div>
    </div>
</div>
    </br>
    </br>
        <!-- Footer Acceptance Section -->
        <div class="details-row">
            <div class="details-column">
            <p><strong>Accepted By: ______________________</strong></p>
            </div>
            <div class="details-column details-column-right">
            <p><strong>Accepted Date: ______________________</strong></p>
            </div>
        </div>
    </div>
</body>
</html>