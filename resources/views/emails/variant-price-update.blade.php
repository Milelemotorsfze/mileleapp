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
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .vehicles-table th,
        .vehicles-table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
            vertical-align: middle;
        }
        .vehicles-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        .vehicles-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .vehicles-table tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .vehicles-table tr:hover {
            background-color: #e8f4f8;
        }
        .vehicles-table td {
            border-left: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
        }
        .vehicles-table td:first-child {
            border-left: 1px solid #ddd;
        }
        .vehicles-table td:last-child {
            border-right: 1px solid #ddd;
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
        <h3>🚗 Affected Vehicles ({{ $vehicles->count() }} vehicle{{ $vehicles->count() != 1 ? 's' : '' }})</h3>
        @if($vehicles->count() > 0)
        <div style="overflow-x: auto;">
            <table class="vehicles-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Brand</th>
                        <th style="width: 20%;">Model Line</th>
                        <th style="width: 25%;">Variant</th>
                        <th style="width: 15%;">Interior Color</th>
                        <th style="width: 15%;">Exterior Color</th>
                        <th style="width: 10%; text-align: right;">Current Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $vehicle)
                    <tr>
                        <td><strong>{{ $vehicle->brand_name ?? 'N/A' }}</strong></td>
                        <td>{{ $vehicle->model_line ?? 'N/A' }}</td>
                        <td><em>{{ $vehicle->variant_name ?? 'N/A' }}</em></td>
                        <td>{{ $vehicle->interior_color ?? 'N/A' }}</td>
                        <td>{{ $vehicle->exterior_color ?? 'N/A' }}</td>
                        <td style="text-align: right; font-weight: bold; color: #2c3e50;">{{ number_format($vehicle->price, 0, '.', ',') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p style="text-align: center; color: #666; font-style: italic;">No vehicles found for this variant.</p>
        @endif
    </div>

    <p>Thank you for your attention.</p>
    <p>Best regards,<br>
</body>
</html>
