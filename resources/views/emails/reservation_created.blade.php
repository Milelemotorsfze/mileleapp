<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order Reserved</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 20px;">
    <p>Dear {{ $salespersonName }},</p>

    <p>The following Purchase Order has been reserved under your name. Please proceed with creating the Sales Order as soon as possible.</p>

    <table style="border-collapse: collapse; border: 1px solid #000; font-size: 14px;" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th style="border: 1px solid #000; text-align: left; background-color: #f2f2f2;">PO Number</th>
                <th style="border: 1px solid #000; text-align: left; background-color: #f2f2f2;">Variant</th>
                <th style="border: 1px solid #000; text-align: left; background-color: #f2f2f2;">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td style="border: 1px solid #000;">{{ $poNumber }}</td>
                <td style="border: 1px solid #000;">{{ $item->variant }}</td>
                <td style="border: 1px solid #000;">{{ $item->qty }}</td>
            </tr>
            @empty
            <tr>
                <td style="border: 1px solid #000;" colspan="3">No variants found for this Purchase Order.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p style="margin-top: 20px;">Best regards,<br>Milele Motors</p>
</body>
</html>
