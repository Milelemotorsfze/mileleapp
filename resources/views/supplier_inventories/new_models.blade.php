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
        .new-models {
            background-color: #bdd5f3;
        }
        .new-model-steering {
            background-color: #d8d4ea;
        }
    </style>
</head>
<body>

<h2 style="text-align: center">Please Add the following Models in Your Master Model List to Upload this Inventory!</h2>
<table class="new-models">
    <tr>
        <td colspan="3" class="heading">New Models - {{ count($newModels) }}</td>
    </tr>
    <tr>
        <th>Model</th>
        <th>SFX</th>
        <th>Model Year</th>
    </tr>
    @foreach($newModels as $newModel)
        <tr>
            <td>{{ $newModel['model'] }}</td>
            <td>{{ $newModel['sfx'] }}</td>
            <td>{{ $newModel['model_year'] }}</td>
        </tr>
    @endforeach
    @if(!$newModels)
        <tr>
            <td colspan="3" style="text-align: center" >No data Available</td>
        </tr>
    @endif
</table>
<br><br>
<table class="new-model-steering">
    <tr>
        <td colspan="4" class="heading">Mistake in Steering - {{ count($newModelsWithSteerings) }}</td>
    </tr>
    <tr>
        <th>Steering</th>
        <th>Model</th>
        <th>SFX</th>
        <th>Model Year</th>
    </tr>
    @foreach($newModelsWithSteerings as $newModelsWithSteering)
        <tr>
            <td>{{ $newModelsWithSteering['steering'] }}</td>
            <td>{{ $newModelsWithSteering['model'] }}</td>
            <td>{{ $newModelsWithSteering['sfx'] }}</td>
            <td>{{ $newModelsWithSteering['model_year'] }}</td>
        </tr>
    @endforeach
    @if(!$newModelsWithSteerings)
        <tr>
            <td colspan="4" style="text-align: center" >No data Available</td>
        </tr>
    @endif
</table>
<br><br>

</body>
</html>

