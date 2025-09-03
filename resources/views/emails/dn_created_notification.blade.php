<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DN Created - Vehicle {{ ucfirst($vehicleStatus) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #1c6192;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .vehicle-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #1c6192;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
        .highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚚 DN Created For {{ ucfirst($vehicleStatus) }} Vehicle</h1>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $salesperson->name }}</strong>,</p>
        
        <p>We are pleased to inform you that the following vehicles’ DN has been received, and the ETA has been updated as follows.</p>
        
        <h3>Vehicles with DN Created:</h3>
        <div class="vehicle-table">
            <table style="width: 100%; max-width: 100%; border-collapse: collapse; margin: 15px 0; background-color: white; border-radius: 5px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); table-layout: fixed;">
                <thead>
                    <tr style="background-color: #1c6192; color: white;">
                        <th style="padding: 8px; text-align: left; border: none; width: 12%; font-size: 12px;">Sr. No</th>
                        <th style="padding: 8px; text-align: left; border: none; width: 15%; font-size: 12px;">PO Number</th>
                        <th style="padding: 8px; text-align: left; border: none; width: 25%; font-size: 12px;">VIN</th>
                        <th style="padding: 8px; text-align: left; border: none; width: 20%; font-size: 12px;">Variant</th>
                        <th style="padding: 8px; text-align: left; border: none; width: 15%; font-size: 12px;">Exterior</th>
                        <th style="padding: 8px; text-align: left; border: none; width: 13%; font-size: 12px;">ETA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $vehicle)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 8px; border: none; font-size: 12px; word-wrap: break-word;">{{ $vehicle['srNo'] }}</td>
                        <td style="padding: 8px; border: none; font-size: 12px; word-wrap: break-word;">{{ $vehicle['poNumber'] }}</td>
                        <td style="padding: 8px; border: none; font-size: 12px; word-wrap: break-word;">{{ $vehicle['vin'] }}</td>
                        <td style="padding: 8px; border: none; font-size: 12px; word-wrap: break-word;">{{ $vehicle['variant'] }}</td>
                        <td style="padding: 8px; border: none; font-size: 12px; word-wrap: break-word;">{{ $vehicle['exterior'] }}</td>
                        <td style="padding: 8px; border: none; font-size: 12px; word-wrap: break-word;">{{ $vehicle['eta'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <p>For more details, please refer to the stock report. Thank you for your attention.</p>
        <p>Best Regards,</p>
    </div>
</body>
</html>
