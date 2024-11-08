<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['label'] ?? '' }}: Claim Submission Reminder for {{ $data->boe ?? $data->workOrder->wo_number ?? 'Work Order' }}</title>
</head>
<body>

    <p>{{ $emailContent ?? '' }}</p>

    <p>Work Order: <strong>{{ $data->workOrder->wo_number ?? '' }}</strong></p>
    <p>Customer Name: <strong>{{ $data->workOrder->customer_name ?? 'Unknown Customer' }}</strong></p>
    <p>Type: <strong>{{ $data->workOrder->type_name ?? '' }}</strong></p>

    @if(($data->workOrder->type == 'export_exw' || $data->workOrder->type == 'export_cnf') && $data->workOrder->is_batch == 1 && !empty($data->workOrder->batch))
        <p>Batch: <strong>{{ $data->workOrder->batch }}</strong></p>
    @elseif(($data->workOrder->type == 'export_exw' || $data->workOrder->type == 'export_cnf') && $data->workOrder->is_batch == 0)
        <p>Batch: <strong>Single Work Order</strong></p>
    @endif

    <p>Sales Person: <strong>{{ $data->workOrder->salesPerson->name ?? '' }}</strong></p>
    <p>BOE Number: <strong>{{ $data->boe ?? '' }}</strong></p>
    <p>Declaration Number: <strong>{{ $data->woBoe->declaration_number ?? '' }}</strong></p>

    @if($data->woBoe->declaration_date != '')
        <p>Declaration Date: <strong>{{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->format('d M Y') }}</strong></p>
        
        <p>Deadline for claim submission: <strong>{{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->addDays(58)->format('d M Y') }}</strong></p>
    @endif

    <br>
    <p>Regards,</p>
    <p>Milele Matrix</p>

</body>
</html>
