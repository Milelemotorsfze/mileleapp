<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        .heading {
            text-align: center;
            font-weight: bold;
        }
        .count-span {
            text-align: center;
            font-weight: bold;
        }

    </style>
</head>
<body>

<h2>Supplier Inventoy Reports</h2>
    <table>
        <span class="count-span">{{ count($newlyAddedRows) }}</span>
        <tr>
            <td colspan="4" class="heading">Newly Added Rows</td>
        </tr>
        <tr>
            <th>Model</th>
            <th>SFX</th>
            <th>Chasis</th>
            <th>Engine Number</th>
            <th>Color Code</th>
        </tr>
    @foreach($newlyAddedRows as $newlyAddedRow)
        <tr>
            <td>{{ $newlyAddedRow['model'] }}</td>
            <td>{{ $newlyAddedRow['sfx'] }}</td>
            <td>{{ $newlyAddedRow['chasis'] }}</td>
            <td>{{ $newlyAddedRow['engine_number'] }}</td>
            <td>{{ $newlyAddedRow['color_code'] }}</td>
        </tr>
    @endforeach

</table>
<br>
<table>
    <span class="count-span">{{ count($updatedRows) }}</span>
    <tr>
        <td colspan="4" class="heading">Updated Added Rows</td>
    </tr>
    <tr>
        <th>Model</th>
        <th>SFX</th>
        <th>Chasis</th>
        <th>Engine Number</th>
        <th>Color Code</th>
    </tr>
    @foreach($updatedRows as $updatedRow)
        <tr>
            <td>{{ $updatedRow['model'] }}</td>
            <td>{{ $updatedRow['sfx'] }}</td>
            <td>{{ $updatedRow['chasis'] }}</td>
            <td>{{ $updatedRow['engine_number'] }}</td>
            <td>{{ $updatedRow['color_code'] }}</td>

        </tr>
    @endforeach
</table>
<table>
    <span class="count-span">{{ count($updatedRows) }}</span>
    <tr>
        <td colspan="4" class="heading">Deleted Rows</td>
    </tr>
    <tr>
        <th>Model</th>
        <th>SFX</th>
        <th>Chasis</th>
        <th>Engine Number</th>
<th>Color Code</th>
    </tr>
    @foreach($deletedRows as $deletedRow)
        <tr>
            <td>{{ $deletedRow['model']  }}</td>
            <td>{{ $deletedRow['sfx']  }}</td>
            <td>{{ $deletedRow['chasis']  }}</td>
            <td>{{ $deletedRow['engine_number']  }}</td>
            <td>{{ $deletedRow['color_code']}}</td>


        </tr>
    @endforeach
</table>
</body>
</html>

