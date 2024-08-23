<!DOCTYPE html>
<html>
<head>
    <title>New Work Order Notification</title>
</head>
<body>
    <p>Dear Team,</p>
    <p>A new work order has been created:</p>
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
        <strong>Sales Person:</strong> {{ $workOrder->CreatedBy->name ?? '' }}<br>
    </p>
    <p>
        <a href="{{ $accessLink }}">Click here to view the work order</a>
    </p>
    <p>Best Regards,<br>Milele matrix</p>
</body>
</html>