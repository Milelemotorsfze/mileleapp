<!DOCTYPE html>
<html>
<head>
    <title>Work Order Documentation Status Changed Notification</title>
    <style>
        .badge-soft-info { background-color: #5bc0de; color: #fff; }
        .badge-soft-success { background-color: #5cb85c; color: #fff; }
        .badge-soft-danger { background-color: #d9534f; color: #fff; }
    </style>
</head>
<body>
    <p>Dear,</p>
    <p>The following work order documentation status has been changed to 
        <label class="badge @if($status == 'Ready') badge-soft-success @elseif($status == 'Initiated') badge-soft-info @elseif($status == 'Not Initiated') badge-soft-danger @endif">
            {{ strtoupper($status) }}
        </label>:
    </p>
    <p>
        <strong>Work Order Number:</strong> {{ $workOrder->wo_number }}<br>
        <strong>Customer Name:</strong> {{ $workOrder->customer_name ?? 'Unknown Customer' }}<br>
        <strong>Vehicle Count:</strong> {{ $workOrder->vehicle_count }} Unit<br>
        <strong>Type:</strong> {{ $workOrder->type_name }}<br>
        @if(($workOrder->type == 'export_exw' || $workOrder->type == 'export_cnf') && $workOrder->is_batch == 1 && !empty($workOrder->batch)) 
            <strong>Batch:</strong> {{ $workOrder->batch }}<br>
        @elseif(($workOrder->type == 'export_exw' || $workOrder->type == 'export_cnf') && $workOrder->is_batch == 0) 
            <strong>Batch:</strong> Single Work Order<br>
        @endif
        <strong>Sales Person:</strong> {{ $workOrder->salesPerson->name ?? 'NA' }}<br><br>
        <strong>Status Changed By:</strong> {{ $userName ?? 'Unknown User' }}<br>
        @if(!empty($comments))
            <strong>Status Changed Remarks:</strong> {{ $comments }}<br>
        @endif
        <strong>Status Changed At:</strong> @if($datetime) {{ \Carbon\Carbon::parse($datetime)->format('d M Y, h:i:s A') }} @else NA @endif<br>
    </p><br>
    @if(empty($isCustomerEmail))
        <a href="{{ $accessLink }}">Click here to view the work order</a><br>
        <a href="{{ $statusLogLink }}">Click here to view the documentation Status log</a>
    @endif
    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>
