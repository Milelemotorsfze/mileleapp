<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDI Completion Notification</title>
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
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }

        .vehicle-info {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }

        .highlight {
            color: #28a745;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🚗 PDI Completed - Vehicle Ready for Pickup</h1>
    </div>

    <div class="content">
        <p>Dear {{ $salesPerson->name ?? 'Sales Team' }},</p>

        <p>We are pleased to inform you that the Pre-Delivery Inspection (PDI) has been completed successfully for the following vehicle:</p>

        <div class="vehicle-info">
            <h3>Vehicle Details</h3>
            <div class="info-row">
                <span class="info-label">VIN:</span>
                <span class="info-value">{{ $vehicle->vin }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Brand:</span>
                <span class="info-value">{{ $vehicle->variant->brand->brand_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Model:</span>
                <span class="info-value">{{ $vehicle->variant->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Model Detail:</span>
                <span class="info-value">{{ $vehicle->variant->model_detail ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Model Year:</span>
                <span class="info-value">{{ $vehicle->variant->my ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Engine:</span>
                <span class="info-value">{{ $vehicle->variant->engine ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fuel Type:</span>
                <span class="info-value">{{ $vehicle->variant->fuel_type ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Transmission:</span>
                <span class="info-value">{{ $vehicle->variant->gearbox ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Steering:</span>
                <span class="info-value">{{ $vehicle->variant->steering ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Exterior Color:</span>
                <span class="info-value">{{ $vehicle->exterior->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Interior Color:</span>
                <span class="info-value">{{ $vehicle->interior->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Upholstery:</span>
                <span class="info-value">{{ $vehicle->variant->upholestry ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Seats:</span>
                <span class="info-value">{{ $vehicle->variant->seat ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Current Location:</span>
                <span class="info-value">{{ $vehicle->warehouse->name ?? 'N/A' }}</span>
            </div>
            @if($vehicle->so && $vehicle->so->so_number)
            <div class="info-row">
                <span class="info-label">Sales Order:</span>
                <span class="info-value">{{ $vehicle->so->so_number }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">PDI Completed Date:</span>
                <span class="info-value highlight">{{ \Carbon\Carbon::parse($pdiDate)->format('d-M-Y H:i') }}</span>
            </div>
        </div>

        <p><strong>The vehicle is now ready for pickup and delivery to the customer.</strong></p>

        <p>Thank you for your attention.</p>

        <p>Sincerely,</p>

        <p>Milele Motors</p>
    </div>
</body>

</html>