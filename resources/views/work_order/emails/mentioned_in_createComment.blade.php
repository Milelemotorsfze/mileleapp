<!DOCTYPE html>
<html>
<head>
    <title>New Work Order Comment Notification</title>
</head>
<body>
    <!-- <p>Dear {{ $user->name }},</p> -->
    <p>You were mentioned in a comment by {{ $comment->user->name }} on work order {{ $workOrder->wo_number }} at @if($comment->created_at != '')
								{{\Carbon\Carbon::parse($comment->created_at)->format('d M Y,  h:i:s A')}}
								@endif</p>
    <p>{{ $comment['text'] }}</p>
    <br>
    <p>
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
        <a href="{{ $accessLink }}">Click here to view the work order</a><br><br>
        <!-- <a href="{{ $accessLinkWithComment }}">Click here to view the comment</a> -->
        
    </p>
    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>
