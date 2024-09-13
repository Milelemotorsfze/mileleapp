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
    <p>The following work order vehicle Delivery status has been changed to 
        <label class="badge @if($status == 'Delivered') badge-soft-success @elseif($status == 'Ready') badge-soft-info @elseif($status == 'Delivered With Docs Hold') badge-soft-warning @elseif($status == 'On Hold') @endif">
            {{ strtoupper($status) }}
        </label>:
    </p>
    <p>
        <strong>VIN:</strong> {{ $woVehicle->vin }}<br><br>
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
        @if(isset($statusTracking) && !empty($statusTracking->doc_delivery_date))
            <strong>Docs Delivery Date:</strong> 
            @if(!empty($statusTracking->doc_delivery_date))
                {{ \Carbon\Carbon::parse($statusTracking->doc_delivery_date)->format('d M Y, h:i:s A') }}
            @endif<br>
        @endif
        @if(isset($statusTracking) && !empty($statusTracking->gdn_number))
            <strong>GDN Number:</strong> {{ $statusTracking->gdn_number ?? 'NA' }}<br>
        @endif
        @if(isset($statusTracking) && isset($statusTracking->location) && !empty($statusTracking->location->name))
            <strong>Location:</strong> {{ $statusTracking->location->name ?? 'NA' }}<br>
        @endif
        <strong>Status Changed By:</strong> {{ $userName ?? 'Unknown User' }}<br>
        @if(!empty($comments))
            <strong>Status Changed Remarks:</strong> {{ $comments }}<br>
        @endif
        <strong>Status Changed At:</strong> @if($datetime) {{ \Carbon\Carbon::parse($datetime)->format('d M Y, h:i:s A') }} @else NA @endif<br>
    </p><br>
    <p>
    @if(empty($isCustomerEmail))
            <a href="{{ $accessLink }}">Click here to view the work order</a><br>
            <a href="{{ $statusLogLink }}">Click here to view the work order vehicle pdi status log</a>
    @endif       
    </p><br>
    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>
