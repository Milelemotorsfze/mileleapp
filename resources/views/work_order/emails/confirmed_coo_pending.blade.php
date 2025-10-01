<!DOCTYPE html>
<html>
<head>
    <title>Sales support confirmed the work order</title>
</head>
<body>
    <p>Dear,</p>
    <p>Sales support confirmed the work order {{ $workOrder->wo_number }}. Please do the COO Office approval.</p>

    <p>
        <strong>Work Order Number:</strong> {{ $workOrder->wo_number }}<br>
        <strong>Customer Name:</strong> {{ $workOrder->customer_name ?? 'Unknown Customer' }}<br>
        <strong>Vehicle Count:</strong> {{ $workOrder->vehicle_count }} Unit<br>
        <strong>Type:</strong> {{ $workOrder->type_name }}<br>
        @if(($workOrder->type == 'export_exw' || $workOrder->type == 'export_cnf') && $workOrder->is_batch == 1 && !empty($workOrder->batch)) 
        <strong>Batch:</strong>  {{ $workOrder->batch }}<br>
        @elseif(($workOrder->type == 'export_exw' || $workOrder->type == 'export_cnf') && $workOrder->is_batch == 0) 
        <strong>Batch:</strong>  Single Work Order<br>
        @endif
        <strong>Sales Person:</strong> {{ $workOrder->salesPerson->name ?? '' }}<br>
    </p>
    <p>
        <a href="{{ $accessLink }}">Click here to view the work order</a><br>
        <a href="{{ $approvalHistoryLink }}">Click here to view the COO Office approval history</a>
    </p>
    <p>Best Regards,<br>Milele matrix</p>
</body>
</html>