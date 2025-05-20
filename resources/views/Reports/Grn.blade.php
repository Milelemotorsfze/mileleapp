<!DOCTYPE html>
<html>
<head>
    <title>GRN Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header img {
            max-height: 80px; /* Adjust the height as needed */
            margin-left: auto;
        }
        .basic-info {
            width: 100%;
            margin-bottom: 20px;
            text-align: right;
        }
        .basic-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .basic-info-table th, .basic-info-table td {
            text-align: left;
            padding: 8px;
        }
        .checklist {
            font-size: 12px;
            width: 100%;
            border-collapse: collapse;
        }
        .checklist th, .checklist td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
        }
        .checklist th {
            background-color: #f2f2f2;
        }
        .extra-items {
            font-size: 12px;
            width: 100%;
            margin-top: 20px;
        }
        .extra-items div {
            font-size: 12px;
            display: inline-block;
            width: 15%;
            margin: 1px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .extra-items div span {
            display: block;
            margin-top: 1px;
        }
        .footer {
            margin-top: 20px;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .signature div {
            width: 45%;
        }
        .signature div p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="header">
        <h1>GRN Report</h1>
        <img src="{{ public_path('images/proforma/milele_logo.png') }}" alt="Company Logo">
    </div>
        
        <table class="checklist">
            <table class="basic-info-table">
                <tr>
                    <th>Date:</th>
                    <td>{{ date('d/M/Y', strtotime($grn_date)) }}</td>
                </tr>
                <tr>
                    <th>VIN:</th>
                    <td>{{$vehicle->vin}}</td>
                </tr>
                <tr>
                    <th>Engine Number:</th>
                    <td>{{$vehicle->engine}}</td>
                </tr>
                <tr>
                    <th>Variant Name:</th>
                    <td>{{$variant->name}}</td>
                </tr>
                <tr>
                    <th>Model Year:</th>
                    <td>{{$variant->my}}</td>
                </tr>
                <tr>
                    <th>Make & Model:</th>
                    <td>{{$variant->brand->brand_name}} - {{$variant->master_model_lines->model_line}}</td>
                </tr>
                <tr>
    <th>Exterior Color:</th>
    <td>{{ $vehicle->exterior->name ?? 'Not Available' }}</td>
</tr>
<tr>
    <th>Interior Color:</th>
    <td>{{ $vehicle->interior->name ?? 'Not Available' }}</td>
</tr>
            </table>
        </div>
        <hr>
        <table class="checklist">
            <thead>
                <tr>
                    <th>Attributes</th>
                    <th>Option</th>
                    <th>Attributes</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($variantitems as $index => $variantitem)
                @if ($index % 2 == 0)
                <tr>
                    <td>{{$variantitem->model_specification->name}}</td>
                    <td>{{$variantitem->model_specification_option->name}}</td>
                @else
                    <td>{{$variantitem->model_specification->name}}</td>
                    <td>{{$variantitem->model_specification_option->name}}</td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        Extra Features : {{$vehicle->extra_features}}
        <div class="extra-items">
            <h3>Extra Items:</h3>
            <br>
            @foreach ($vehicleItems as $vehicleitem)
            <div>
            @if($vehicleitem->item_name == "packing")
            <span>Packing</span>
            @elseif($vehicleitem->item_name == "warningtriangle")
            <span>Warning Triangle</span>
            @elseif($vehicleitem->item_name == "wheel")
            <span>Wheel</span>
            @elseif($vehicleitem->item_name == "firstaid")
            <span>First Aid</span>
            @elseif($vehicleitem->item_name == "floor_mat")
            <span>Floor Mat</span>
            @elseif($vehicleitem->item_name == "service_book")
            <span>Service Book</span>
            @elseif($vehicleitem->item_name == "keys")
            <span>Keys</span>
            <span>{{$vehicleitem->qty}}</span>
            @elseif($vehicleitem->item_name == "fire_extinguisher")
            <span>Fire Extinguisher</span>
            @elseif($vehicleitem->item_name == "trunkcover")
            <span>Trunk Cover</span>
            @else
            <span>{{$vehicleitem->item_name}}</span>
            @endif
            </div>
            @endforeach
        </div>
        @if($incident)
        <div class="extra-items">
            <h3>Incident:</h3>
            <table class="checklist">
            <table class="basic-info-table">
                <tr>
                    <th>Incident Type:</th>
                    <td>{{ $incident->type }}</td>
                </tr>
                <tr>
                    <th>Narration of Accident / Damage:</th>
                    <td>{{ $incident->narration }}</td>
                </tr>
                <tr>
                    <th>Damage Details:</th>
                    <td>{{ $incident->detail }}</td>
                </tr>
                <tr>
                    <th>Driven By:</th>
                    <td>{{ $incident->driven_by }}</td>
                </tr>
                <tr>
                <th>Responsibility for Recover the Damages:</th>
                <td>{{ $incident->responsivity }}</td>
            </tr>
            <tr>
                <th>Reasons:</th>
                <td>{{ $incident->reason }}</td>
            </tr>
            </table>
        </div>
        <br>
        <img src="{{ public_path('qc/' . $incident->file_path) }}" alt="Incident Picture" style="width: 100%; height: auto;">
        <br>
        @endif
        <div class="footer">
            <p><strong>Receiving Remarks:</strong> {{$vehicle->grn_remark}}</p>
        </div>
        <div class="signature">
            <div>
                <p><strong>Created By: </strong>{{$created_by}}</p>
            </div>
        </div>
    </div>
</body>
</html>
