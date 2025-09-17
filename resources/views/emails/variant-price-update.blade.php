<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Variant Price Update Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .info-section {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-section h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        .variant-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .vehicles-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .vehicles-table th,
        .vehicles-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .vehicles-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .vehicles-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .price-change {
            background-color: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
        }
        .reason-section {
            background-color: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .highlight {
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚗 Variant Price Update Notification</h1>
    </div>

    <div class="info-section">
        <h3>📋 Update Information</h3>
        <p><strong>Updated By:</strong> {{ $updatedBy->name ?? 'System' }}</p>
        <p><strong>Update Time:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        <p><strong>Field Updated:</strong> <span class="highlight">{{ ucfirst(str_replace('_', ' ', $field)) }}</span></p>
        <p><strong>Old Value:</strong> <span class="highlight">{{ number_format($oldValue, 0, '.', ',') }}</span></p>
        <p><strong>New Value:</strong> <span class="highlight">{{ number_format($newValue, 0, '.', ',') }}</span></p>
    </div>

    @if($reason)
    <div class="reason-section">
        <h3>📝 Reason for Price Change</h3>
        <p>{{ $reason }}</p>
    </div>
    @endif

    <div class="variant-details">
        <h3>Affected Vehicle</h3>
        @if($vehicles->count() > 0)
        <table class="vehicles-table">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Variant</th>
                    <th>Interior Color</th>
                    <th>Exterior Color</th>
                    <th>Current Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->brand_name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->model_line ?? 'N/A' }}</td>
                    <td>{{ $vehicle->variant_name ?? 'N/A' }}</td>
                    <td>{{ $vehicle->interior_color ?? 'N/A' }}</td>
                    <td>{{ $vehicle->exterior_color ?? 'N/A' }}</td>
                    <td>{{ number_format($vehicle->price, 0, '.', ',') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No vehicles found for this variant.</p>
        @endif
    </div>

    <p>Thank you for your attention.</p>
    <p>Best regards,<br>
</body>
</html>
