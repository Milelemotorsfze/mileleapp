<!DOCTYPE html>
<html>
<head>
    <title>Daily User Activity Report</title>
    <style>
        /* Add some basic table styles */
        table {
            border-collapse: collapse;
            width: 100%;
            border: 2px solid #000; /* Add a border to the table */
        }

        th, td {
            border: 1px solid #000; /* Add borders to table cells */
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Daily User Activity Report</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Activity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->id ?? ''}}</td>
                    <td>{{ $activity->user->name ?? ''}}</td>
                    <td>{{ $activity->user->email ?? ''}}</td>
                    <td>@if($activity->created_at != ''){{ \Carbon\Carbon::parse($activity->created_at)->format('d-m-Y') }}@endif</td>
                    <td>{{ $activity->activity ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>