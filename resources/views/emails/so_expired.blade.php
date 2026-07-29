<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Order Expired</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 20px;">
    <p>Dear {{ $salespersonName }},</p>

    <p>Sales Order <strong>{{ $soNumber }}</strong> (dated {{ $soDate->format('d-M-Y') }}) has been
        <strong>automatically expired</strong> because no Goods Delivery Note (GDN) was issued within 30 days.
        The following stock has been <strong>released back to Available</strong> and is now available for other deals:</p>

    <table style="border-collapse: collapse; border: 1px solid #000; font-size: 14px;" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th style="border: 1px solid #000; text-align: left; background-color: #f2f2f2;">VIN</th>
                <th style="border: 1px solid #000; text-align: left; background-color: #f2f2f2;">Variant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicles as $vehicle)
            <tr>
                <td style="border: 1px solid #000;">{{ $vehicle->vin }}</td>
                <td style="border: 1px solid #000;">{{ $vehicle->variant ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td style="border: 1px solid #000;" colspan="2">No vehicles found for this Sales Order.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 20px;">If this deal is still active, please create a new Sales Order to re-reserve the stock.</p>

    <p style="margin-top: 20px;">Best regards,<br>Milele Motors</p>
</body>
</html>
