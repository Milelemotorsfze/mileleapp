<!DOCTYPE html>
<html>
<head>
    <title>Price Change Notification</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #00466a;
            color: #ffffff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 0 0 15px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        .content table th, .content table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .content table th {
            background-color: #00466a;
            color: #ffffff;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f2f2f2;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 12px;
            color: #777;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #00466a;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: #ffffff;
            background-color: #00466a;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Price Change Notification</h1>
        </div>
        <div class="content">
            <p>Dear Team,</p>
            <p>We are pleased to inform you that the vehicle Price update has been successfully processed. Below are the details of the changes:</p>
            <table>
                <tr>
                    <th>PO Number</th>
                    <td>{{ $ponumber }}</td>
                </tr>
                <tr>
                    <th>Total Amount of Changes</th>
                    <td>{{ $totalAmountOfChanges > 0 ? '-' : '+' }}{{ abs($totalAmountOfChanges) }} {{ $orderCurrency }}</td>
                </tr>
                <tr>
                    <th>Total Number of Vehicles Changed</th>
                    <td>{{ $totalVehiclesChanged }}</td>
                </tr>
            </table>
            <p>Below are the details of the vehicles:</p>
            <table>
    <tr>
    <th>Vehicle Reference #</th>
                <th>VIN</th>
                <th>Variant Name</th>
                <th>Old Price ({{ $orderCurrency }})</th>
                <th>New Price ({{ $orderCurrency }})</th>
                <th>Changed By</th>
    </tr>
        @foreach($priceChanges as $change)
                <tr>
                    <td>{{ $change['vehicle_reference'] }}</td>
                    <td>{{ $change['Vin'] }}</td>
                    <td>{{ $change['variant_name'] }}</td>
                    <td>{{ $change['old_price'] }}</td>
                    <td>{{ $change['new_price'] }}</td>
                    <td>{{ $change['changed_by'] }}</td>
                </tr>
        @endforeach
</table>
            <p>For more details, you can view the purchase order by clicking the button below:</p>
            <p><a href="{{ $orderUrl }}" class="button">View Purchase Order</a></p>
            <p>Thank you for your attention.</p>
            <p>Sincerely,</p>
            <p>Milele Motors</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Milele Motors. All rights reserved.</p>
            <p>If you have any questions, feel free to <a href="mailto:support.dev@milele.com">contact us</a>.</p>
        </div>
    </div>
</body>
</html>
