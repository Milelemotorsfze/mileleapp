<!DOCTYPE html>
<html>
<head>
    <title>Work Order Data Updated Notification</title>
    <style>
        .badge-soft-info { background-color: #5bc0de; color: #fff; }
        .badge-soft-success { background-color: #5cb85c; color: #fff; }
        .badge-soft-danger { background-color: #d9534f; color: #fff; }
        th, td {
            border: 1px solid #e9e9ef;
            padding: 5px;
            font-size: 12px!important;
        }
        table {
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <p>Dear Team,</p>
    <p>Data for the following work order has been updated:</p>
    <p>
        <strong>Work Order Number:</strong> {{ $workOrder->wo_number }}<br>
        <strong>Customer Name:</strong> {{ $workOrder->customer_name ?? 'Unknown Customer' }}<br>
        <strong>Vehicle Count:</strong> {{ $workOrder->vehicle_count }} Unit<br>
        <strong>Type:</strong> {{ $workOrder->type_name }}<br>

        @if(in_array($workOrder->type, ['export_exw', 'export_cnf']))
            <strong>Batch:</strong> {{ $workOrder->is_batch ? $workOrder->batch : 'Single Work Order' }}<br>
        @endif 

        <strong>Sales Person:</strong> {{ $workOrder->CreatedBy->name ?? '' }}<br>  
    </p>

    <p>
        <a href="{{ $accessLink }}">Click here to view the work order</a><br><br>
        The following are the data updates:<br><br>
        <strong>Updated By:</strong> {{ $authUserName }}<br>
        <strong>Updated At:</strong> {{ $currentDateTime }}<br>
    </p>

    <table>
        <thead>
            <tr>
                <th>Field</th>
                <th>Type</th>
                <th>Old Value</th>
                <th>New Value</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($comment->wo_histories) && $comment->wo_histories->isNotEmpty())
                @foreach($comment->wo_histories as $item)
                    @if($item->new_value != $item->old_value)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $item->field_name)) }}</td>
                            <td>{{ $item->type }}</td>
                            <td>
                                @if(in_array($item->field_name, ['brn_file', 'signed_pfi', 'signed_contract', 'payment_receipts', 'noc', 'enduser_trade_license', 'enduser_passport', 'enduser_contract', 'vehicle_handover_person_id']))
                                    @if($item->old_value != '')
                                        <a href="{{ url($item->old_value) }}" target="_blank">
                                            <button class="btn btn-primary btn-style">View</button>
                                        </a>
                                        <a href="{{ url($item->old_value) }}" download>
                                            <button class="btn btn-info btn-style">Download</button>
                                        </a>
                                    @endif
                                @else
                                    {{ $item->old_value }}
                                @endif
                            </td>
                            <td>
                                @if(in_array($item->field_name, ['brn_file', 'signed_pfi', 'signed_contract', 'payment_receipts', 'noc', 'enduser_trade_license', 'enduser_passport', 'enduser_contract', 'vehicle_handover_person_id']))
                                    @if($item->new_value != '')
                                        <a href="{{ url($item->new_value) }}" target="_blank">
                                            <button class="btn btn-primary btn-style">View</button>
                                        </a>
                                        <a href="{{ url($item->new_value) }}" download>
                                            <button class="btn btn-info btn-style">Download</button>
                                        </a>
                                    @endif
                                @else
                                    {{ $item->new_value }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>

    <p>Best Regards,<br>Milele Matrix</p>
</body>
</html>
