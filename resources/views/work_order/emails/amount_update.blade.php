<!DOCTYPE html>
<html>
<head>
    <title>Work Order SO Amount Section Data Updated Notification</title>
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
    <p>SO amount section data for the following work order has been updated:</p>
    <p>
        <strong>Work Order Number:</strong> {{ $workOrder->wo_number }}<br>
        <strong>Customer Name:</strong> {{ $workOrder->customer_name ?? 'Unknown Customer' }}<br>
        <strong>Vehicle Count:</strong> {{ $workOrder->vehicle_count }} Unit<br>
        <strong>Type:</strong> {{ $workOrder->type_name }}<br>

        @if(in_array($workOrder->type, ['export_exw', 'export_cnf']))
            <strong>Batch:</strong> {{ $workOrder->is_batch ? $workOrder->batch : 'Single Work Order' }}<br>
        @endif 

        <strong>Sales Person:</strong> {{ $workOrder->salesPerson->name ?? '' }}<br>  
    </p>

    <p>
        <a href="{{ $accessLink }}">Click here to view the work order</a><br><br>
        The following are the SO amount section data updates:<br><br>
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
            @if(empty($comment))
                @foreach (['amount_received' => 'Amount Received', 'balance_amount' => 'Balance Amount', 'currency' => 'Currency', 'deposit_received_as' => 'Deposit Received As', 'so_total_amount' => 'SO Total Amount', 'so_vehicle_quantity' => 'SO Vehicle Quantity'] as $field => $label)
                    @if(!empty($workOrder->$field) && $workOrder->$field != '0.00')
                        <tr>
                            <td>{{ $label }}</td>
                            <td>SET</td>
                            <td></td>
                            <td>
                                @if($field === 'deposit_received_as')
                                    @if($workOrder->$field === 'total_deposit')
                                        Total Deposit
                                    @elseif($workOrder->$field === 'custom_deposit')
                                        Custom Deposit
                                    @else
                                        {{ $workOrder->$field }}
                                    @endif
                                @else
                                    {{ $workOrder->$field }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            @elseif(isset($comment->wo_histories) && $comment->wo_histories->isNotEmpty())
                @foreach($comment->wo_histories as $item)
                    @if($item->new_value != $item->old_value)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $item->field_name)) }}</td>
                            <td>{{ $item->type }}</td>
                            <td>
                                @if($item->field_name === 'deposit_received_as')
                                    @if($item->old_value === 'total_deposit')
                                        Total Deposit
                                    @elseif($item->old_value === 'custom_deposit')
                                        Custom Deposit
                                    @else
                                        {{ $item->old_value }}
                                    @endif
                                @else
                                    {{ $item->old_value }}
                                @endif
                            </td>
                            <td>
                                @if($item->field_name === 'deposit_received_as')
                                    @if($item->new_value === 'total_deposit')
                                        Total Deposit
                                    @elseif($item->new_value === 'custom_deposit')
                                        Custom Deposit
                                    @else
                                        {{ $item->new_value }}
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
