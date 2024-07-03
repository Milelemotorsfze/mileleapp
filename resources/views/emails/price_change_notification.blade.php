<!DOCTYPE html>
<html>
<head>
    <title>Price Change Notification</title>
</head>
<body>
    <p>PO Number: {{ $ponumber }}</p>
    <p>Total Amount of Changes: {{ $totalAmountOfChanges > 0 ? '-' : '+' }}{{ abs($totalAmountOfChanges) }} {{ $orderCurrency }}</p>
    <p>Total Number of Vehicles Changed: {{ $totalVehiclesChanged }}</p>
    <table border="1">
        <thead>
            <tr>
                <th>Vehicle Reference #</th>
                <th>Variant Name</th>
                <th>Old Price ({{ $orderCurrency }})</th>
                <th>New Price ({{ $orderCurrency }})</th>
                <th>Changed By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($priceChanges as $change)
                <tr>
                    <td>{{ $change['vehicle_reference'] }}</td>
                    <td>{{ $change['variant_name'] }}</td>
                    <td>{{ $change['old_price'] }}</td>
                    <td>{{ $change['new_price'] }}</td>
                    <td>{{ $change['changed_by'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
