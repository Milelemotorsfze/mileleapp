<!DOCTYPE html>
<html>
<head>
    <title>Work Order Vehicle Data Updated Notification</title>
    <style>
        .badge-soft-info { background-color: #5bc0de; color: #fff; }
        .badge-soft-success { background-color: #5cb85c; color: #fff; }
        .badge-soft-danger { background-color: #d9534f; color: #fff; }
    </style>
</head>
<body>
    <p>Dear Team,</p>
    <p>Vehicle data for the following work order has been updated:</p>
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
    </p>
    <p>
        <a href="{{ $accessLink }}">Click here to view the work order</a><br>
        The following are the vehicle data updates:<br> 
        
        <div class="row">
            <div class="col-xxl-11 col-lg-11 col-md-11">
                <div class="comment-details">
                    @if(isset($newComment->new_vehicles) && count($newComment->new_vehicles) > 0)                  
                    <table class="my-datatable" style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                        <thead>
                            <tr>
                            <th colspan="19" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px; background-color:#e6f1ff!important;">
                                {{ count($newComment->new_vehicles) }} vehicles added as new
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @endif
                    @if(isset($newComment->removed_vehicles) && count($newComment->removed_vehicles) > 0)                  
                    <table class="my-datatable" style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                        <thead>
                            <tr>
                                <th colspan="19" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px; background-color:#e6f1ff!important;">
                                {{ count($newComment->removed_vehicles) }} vehicles removed
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @endif
                    @if(isset($newComment->updated_vehicles) && count($newComment->updated_vehicles) > 0)                  
                    <table class="my-datatable" style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                        <thead>
                            <tr>
                                <th colspan="4" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #e9e9ef; background-color:#e6f1ff!important;">
                                {{ count($newComment->updated_vehicles) }} vehicles data updated
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </p>
    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>
