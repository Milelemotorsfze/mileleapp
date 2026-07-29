<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Order GDN Review</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 20px;">
    <p>Dear {{ $salespersonName }},</p>

    <p>Sales Order <strong>{{ $soNumber }}</strong> (dated {{ $soDate->format('d-M-Y') }}) still has
        <strong>no Goods Delivery Note (GDN) issued</strong>. It will be
        <strong>automatically expired in {{ $daysLeft }} day{{ $daysLeft == 1 ? '' : 's' }}</strong>
        on <strong>{{ $expiryDate->format('d-M-Y') }}</strong>, and the reserved stock below will be
        released back to Available.</p>

    <p>Please take one of the following actions before then:</p>
    <ul>
        <li><strong>Proceed with the deal</strong> &mdash; issue the GDN to keep the stock reserved.</li>
        <li><strong>Cancel the Sales Order</strong> &mdash; if the deal is no longer active, so the stock is freed for others.</li>
    </ul>

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
                <td style="border: 1px solid #000;" colspan="2">No undelivered vehicles found for this Sales Order.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 20px;">Best regards,<br>Milele Motors</p>
</body>
</html>
