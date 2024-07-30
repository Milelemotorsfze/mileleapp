<!-- resources/views/test.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Vehicle Data</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>VIN</th>
                <th>Variant</th>
                <th>Brand</th>
                <th>Interior</th>
                <th>Exterior</th>
                <th>Warehouse Location</th>
                <th>Document</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vins as $vin)
                <tr>
                    <td>{{ $vin->vin }}</td>
                    <td>{{ $vin->variant->name ?? 'N/A' }}</td>
                    <td>{{ $vin->variant->master_model_lines->brand->name ?? 'N/A' }}</td>
                    <td>{{ $vin->interior->name ?? 'N/A' }}</td>
                    <td>{{ $vin->exterior->name ?? 'N/A' }}</td>
                    <td>{{ $vin->warehouseLocation->name ?? 'N/A' }}</td>
                    <td>{{ $vin->document->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>