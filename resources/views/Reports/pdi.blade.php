<!DOCTYPE html>
<html>
<head>
    <title>PDI Report</title>
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
            max-height: 80px;
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
            <h1>PDI Report</h1>
            <img src="{{ public_path('images/proforma/milele_logo.png') }}" alt="Company Logo">
        </div>
        <table class="checklist">
            <tr>
                <th>Date:</th>
                <td>{{ date('d/M/Y', strtotime($inspection->created_at)) }}</td>
            </tr>
            <tr>
                <th>VIN:</th>
                <td>{{ $additionalInfo->vin }}</td>
            </tr>
            <tr>
                <th>Make & Model:</th>
                <td>{{ $additionalInfo->model_line }}</td>
            </tr>
            <tr>
                <th>Exterior Color:</th>
                <td>{{ $additionalInfo->ext_colour }}</td>
            </tr>
            <tr>
                <th>Interior Color:</th>
                <td>{{ $additionalInfo->int_colour }}</td>
            </tr>
            <tr>
                <th>Location:</th>
                <td>{{ $additionalInfo->location }}</td>
            </tr>
        </table>
        <hr>
        <h4>GRN Details</h4>
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
        <hr>
        <h4>PDI Details</h4>
        <table class="checklist">
            <thead>
                <tr>
                    <th>Check List Items</th>
                    <th>Receiving</th>
                    <th>Delivery</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($PdiInspectionData as $inspectiondata)
                <tr>
                    <td>{{ $inspectiondata->checking_item }}</td>
                    <td>{{ $inspectiondata->reciving }}</td>
                    <td>{{ $inspectiondata->status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if($incident)
        <div class="extra-items">
            <h3>Incident:</h3>
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
            <p><strong>Remarks:</strong> {{ $inspection->remark }}</p>
        </div>
        <div class="signature">
            <div>
                <p><strong>Created By: </strong>{{ $created_by }}</p>
            </div>
        </div>
    </div>
</body>
</html>