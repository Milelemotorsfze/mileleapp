<!DOCTYPE html>
<html>
<head>
    <title>Work Order COO Office Approval Notification</title>
    <style>
        .badge-soft-info { background-color: #5bc0de; color: #fff; }
        .badge-soft-success { background-color: #5cb85c; color: #fff; }
        .badge-soft-danger { background-color: #d9534f; color: #fff; }
    </style>
</head>
<body>
    <p>Dear Team,</p>
    @if($status == 'approved')
        <p>COO has approved the work order {{ $workOrder->wo_number }}. Please do the Finance approval.</p>
    @else
        <p>The following work order has been processed:</p>
    @endif
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
        <strong>Sales Person:</strong> {{ $workOrder->salesPerson->name ?? '' }}<br>
        <strong>Approval Status:</strong> 
        <label class="badge @if($status == 'pending') badge-soft-info @elseif($status == 'approved') badge-soft-success @elseif($status == 'rejected') badge-soft-danger @endif">
            {{ ucfirst($status) }}
        </label><br>
        <strong>@if($status == 'approved') Approved @elseif($status == 'rejected') Rejected @endif By:</strong> {{ $userName ?? 'Unknown User' }}<br>
        @if(!empty($comments))
            <strong>Approval Comments:</strong> {{ $comments }}<br>
        @endif
    </p>
    <p>
        <a href="{{ $accessLink }}">Click here to view the work order</a><br>
        @if($status == 'approved')
            <a href="{{ $approvalHistoryLink }}">Click here to view the Finance approval history</a>
        @else
            <a href="{{ $approvalHistoryLink }}">Click here to view the coo office approval history</a>
        @endif
    </p>
    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>
