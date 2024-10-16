<!DOCTYPE html>
<html>
<head>
    <title>Work Order Vehicle Delivery Status Changed Notification</title>
    <style>
        .badge-soft-info { background-color: #5bc0de; color: #fff; }
        .badge-soft-success { background-color: #5cb85c; color: #fff; }
        .badge-soft-danger { background-color: #d9534f; color: #fff; }
    </style>
</head>
<body>
    <p>Dear,</p>
    <p>The following work order vehicle delivery status has been changed to 
        <label class="badge @if($status == 'Delivered') badge-soft-success @elseif($status == 'Ready') badge-soft-info @elseif($status == 'Delivered With Docs Hold') badge-soft-warning @elseif($status == 'On Hold') badge-soft-danger @endif">
            {{ strtoupper($status) }}
        </label>:
    </p>
    <p>
        <strong>VIN:</strong> {{ $woVehicle->vin }}<br>
        <strong>Work Order Number:</strong> {{ $workOrder->wo_number }}<br>
        <strong>Customer Name:</strong> {{ $workOrder->customer_name ?? 'Unknown Customer' }}<br>
        <strong>Vehicle Count:</strong> {{ $workOrder->vehicle_count }} Unit(s)<br>
        <strong>Type:</strong> {{ $workOrder->type_name }}<br>
        @if($workOrder->type == 'export_exw' || $workOrder->type == 'export_cnf')
            @if($workOrder->is_batch == 1)
                <strong>Batch:</strong> {{ $workOrder->batch }}<br>
            @else
                <strong>Batch:</strong> Single Work Order<br>
            @endif
        @endif
        <strong>Sales Person:</strong> {{ $workOrder->salesPerson->name ?? 'N/A' }}<br><br>
        @if(!empty($statusTracking->doc_delivery_date))
            <strong>Docs Delivery Date:</strong> {{ \Carbon\Carbon::parse($statusTracking->doc_delivery_date)->format('d M Y') }}<br>
        @endif
        @if(!empty($statusTracking->gdn_number))
            <strong>GDN Number:</strong> {{ $statusTracking->gdn_number }}<br>
        @endif
        @if(!empty($statusTracking->location))
            <strong>Location:</strong> {{ $statusTracking->locationName->name }}<br>
        @endif
        <strong>Status Changed By:</strong> {{ $userName }}<br>
        @if(!empty($comments))
            <strong>Status Changed Remarks:</strong> {{ $comments }}<br>
        @endif
        <strong>Status Changed At:</strong> {{ \Carbon\Carbon::parse($datetime)->format('d M Y, h:i:s A') }}<br>
    </p>

    <p>
        @if(empty($isCustomerEmail))
        <a href="{{ $accessLink }}">Click here to view the work order</a><br>
            <a href="{{ $statusLogLink }}">Click here to view the vehicle delivery status log</a>
        @endif
    </p>
    
    <br>
    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>