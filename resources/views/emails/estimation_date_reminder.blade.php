<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Estimation Date Reminder</title>
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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            background-color: #dc3545;
            color: #ffffff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 0 0 15px;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .vehicles-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 14px;
            border: 2px solid #00466a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .vehicles-table th, .vehicles-table td {
            padding: 15px 12px;
            text-align: left;
            border-right: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }
        .vehicles-table th {
            background: #012b4d;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .vehicles-table th:last-child,
        .vehicles-table td:last-child {
            border-right: none;
        }
        .vehicles-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .vehicles-table tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .vehicles-table tr:hover {
            background-color: #e3f2fd;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        .vehicles-table td {
            font-size: 14px;
            color: #333;
        }
        .vehicles-table .sr-no {
            font-weight: bold;
            color: #00466a;
            text-align: center;
        }
        .vehicles-table .eta {
            font-weight: bold;
            color: #dc3545;
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
        .days-left {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        .vehicle-count {
            font-weight: bold;
            color: #00466a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Vehicle Estimation Date Reminder</h1>
        </div>
        
        <div class="content">
            <p>Dear Team,</p>
            
            <div class="alert">
                <strong>⚠️ URGENT NOTICE:</strong> There are <span class="vehicle-count">{{ $vehicles->count() }}</span> vehicle(s) with estimation dates approaching within the next 5 days.
            </div>
            
            <p>We are pleased to inform you that the recorded ETA for the following vehicle(s) is approaching in 5 days. Please confirm with the supplier and update the ETA in case of any changes. :</p>
            
            <table class="vehicles-table">
                <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>PO Number</th>
                        <th>VIN</th>
                        <th>Variant</th>
                        <th>Exterior</th>
                        <th>ETA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $index => $vehicle)
                    <tr>
                        <td class="sr-no">{{ $index + 1 }}</td>
                        <td>{{ $vehicle->purchasingOrder->po_number ?? 'N/A' }}</td>
                        <td>{{ $vehicle->vin ?? 'N/A' }}</td>
                        <td>{{ $vehicle->variant->name ?? 'N/A' }}</td>
                        <td>{{ $vehicle->exterior_color ?? 'N/A' }}</td>
                        <td class="eta">
                            {{ \Carbon\Carbon::parse($vehicle->estimation_date)->format('d-M-Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <p>Thank you for your attention.</p>
            
            <p>Best regards,<br>
        </div>
    </div>
</body>
</html>
