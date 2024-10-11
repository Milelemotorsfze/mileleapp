<!DOCTYPE html>
<html>
<head>
    <title>Reminder: Export Documents Expiry for Work Order BOE</title>
</head>
<body>
    <h1>Reminder 1</h1>

    <p>Dear {{ $salesperson->name ?? 'Salesperson' }},</p>

    <p>
        Kindly remind the customer that the export documents are about to expire for this work order, 
        and we will be canceling and then reprocessing these documents. The customer will be charged 
        1560 AED for the cancellation and reprocessing.
    </p>

    <p>
        Please note, if the customer has taken the vehicles out of DUCAMZ, kindly ask them to bring 
        the vehicles back for cancellation and reprocessing.
    </p>

    <p>Work Order BOE Number: <strong>{{ $boe->boe_number }}</strong></p>
    <p>Declaration Date: <strong>{{ \Carbon\Carbon::parse($boe->declaration_date)->format('d M Y') }}</strong></p>

    <p>Regards,</p>
    <p>The Logistics Team</p>
</body>
</html>
