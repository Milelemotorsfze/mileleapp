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
            border: 1px solid #1c1b1b;
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
       .new-row {
           background-color: #bdd5f3;
       }
        .updated-row {
            background-color: #d8d4ea;
        }
        .deleted-row {
            background-color: #e5beb2;
        }
    </style>
</head>
<body>

<h2 style="text-align: center">Supplier Inventory Reports</h2>
    <table class="new-row">
        <tr>
            <td colspan="5" class="heading">Newly Added Rows - {{ count($newlyAddedRows) }}</td>
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
    @if(!$newlyAddedRows)
        <tr>
            <td colspan="5" style="text-align: center" >No data Added</td>
        </tr>
    @endif
</table>
<br><br>
<table class="updated-row">
    <tr>
        <td colspan="5" class="heading">Updated Added Rows - {{ count($updatedRows) }}</td>
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
    @if(!$updatedRows)
        <tr>
            <td colspan="5" style="text-align: center" >No data Updated</td>
        </tr>
    @endif
</table>
<br><br>
<table class="deleted-row">
    <tr>
        <td colspan="5" class="heading">Deleted Rows - {{ count($deletedRows) }}</td>
    </tr>
    <tr>
        <th>Model</th>
        <th>SFX</th>
        <th>Chasis</th>
        <th>Engine Number </th>
        <th>Color Code</th>
    </tr>

    @foreach($deletedRows as $deletedRow)
        <tr>
            <td>{{ $deletedRow->masterModel->model ?? '' }}</td>
            <td>{{ $deletedRow->masterModel->sfx ?? '' }}</td>
            <td>{{ $deletedRow->chasis  }}</td>
            <td>{{ $deletedRow->engine_number }}</td>
            <td>{{ $deletedRow->color_code }}</td>
        </tr>
    @endforeach
    @if($deletedRows->count() <= 0)
        <tr>
            <td colspan="5" style="text-align: center" >No data Deleted</td>
        </tr>
    @endif
</table>
</body>
</html>

