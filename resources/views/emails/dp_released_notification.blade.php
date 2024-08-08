<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order Payment Released Notification</title>
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
            <h1>Purchase Order Payment Released</h1>
        </div>
        <div class="content">
            <p>Dear Team,</p>
            <p>We are pleased to inform you that the purchase order payment has been Released. Below are the details:</p>
            <table>
                <tr>
                    <th>PO Number</th>
                    <td>{{ $ponumber }}</td>
                </tr>
                <tr>
                    <th>PFI Number</th>
                    <td>{{ $pl_number }}</td>
                </tr>
                <tr>
                    <th>Payment Amount</th>
                    <td>{{ $transaction_amount }}</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>{{ $totalcost }}</td>
                </tr>
                <tr>
                    <th>Number of Units</th>
                    <td>{{ $transactionCount }} Vehicles</td>
                </tr>
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