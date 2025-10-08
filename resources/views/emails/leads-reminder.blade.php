<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leads Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }

        .summary {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #34495e;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .lead-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .lead-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .pending-days {
            font-weight: bold;
            color: #e74c3c;
        }

        .pending-days.urgent {
            background-color: #fdf2f2;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 25px;
            border-left: 4px solid #f39c12;
        }

        .footer p {
            margin: 0;
            color: #7f8c8d;
            font-size: 14px;
        }

        .no-leads {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }

        .action-button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px 3px;
            font-weight: 500;
            font-size: 12px;
        }

        .action-button:hover {
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="summary">
            @if($leadType === 'new')
                <h1>📞 Daily Leads Reminder</h1>
                <p>Hello <strong>{{ $salesPerson->name }}</strong>!</p>
                <p>You have <strong>{{ $totalLeads }}</strong> unattended leads assigned to you that require immediate attention.</p>
                <p><strong>Note:</strong> If you have already started working on any of these leads, please update their status to avoid receiving duplicate reminders.</p>
            @elseif($leadType === 'contacted_working')
                <h1>📞 Weekly Leads Follow-up</h1>
                <p>Hello <strong>{{ $salesPerson->name }}</strong>!</p>
                <p>You have <strong>{{ $contactedCount }}</strong> leads in "Contacted" status and <strong>{{ $workingCount }}</strong> leads in "Working" status that need your attention.</p>
                <p><strong>Action Required:</strong> Please review these leads and decide whether to qualify or disqualify them to move the sales process forward.</p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Lead Info</th>
                    <th>Customer Name</th>
                    <th>Pending Since</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leadsData as $lead)
                <tr>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 3px;">
                            <a href="{{ $lead['url'] }}" class="lead-link">#{{ $lead['id'] }}</a>
                        </div>
                        <div style="font-size: 11px; color: #666; margin-bottom: 2px;">
                            📞 {{ $lead['phone'] }}
                        </div>
                        <div style="font-size: 11px; color: #666; margin-bottom: 2px;">
                            ✉ {{ $lead['email'] }}
                        </div>
                        <div style="font-size: 11px; color: #666;">
                            📍 {{ $lead['location'] }}
                        </div>
                    </td>
                    <td style="font-weight: bold;">{{ $lead['name'] }}</td>
                    <td>
                        <span class="pending-days {{ $lead['pending_days'] > 3 ? 'urgent' : '' }}">
                            {{ $lead['pending_days'] }} {{ $lead['pending_days'] == 1 ? 'day' : 'days' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ $lead['url'] }}" class="action-button">View Lead</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Milele Motors. All rights reserved.</p>
            <p>If you have any questions, feel free to <a href="mailto:support.dev@milele.com">contact us</a>.</p>
        </div>
    </div>
</body>

</html>