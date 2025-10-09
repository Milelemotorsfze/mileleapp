<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Leads Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
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
            vertical-align: top;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .salesperson-cell {
            background-color: #e8f4fd;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            vertical-align: middle;
        }

        .count-cell {
            background-color: #e8f4fd;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            vertical-align: middle;
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

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-new {
            background-color: #fdf2f2;
            color: #e74c3c;
        }

        .status-contacted {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-working {
            background-color: #d1ecf1;
            color: #0c5460;
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

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📊 Daily Leads Report</h1>
            <p>Comprehensive overview of all leads across sales team - {{ now()->format('F j, Y') }}</p>
        </div>

        <div class="summary">
            <h3>📈 Summary</h3>
            <p><strong>Total Leads:</strong> {{ $totalLeads }} | <strong>Sales Persons:</strong> {{ $totalSalesPersons }} | <strong>Report Date:</strong> {{ now()->format('M j, Y g:i A') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Salesman</th>
                    <th style="width: 30%;">Leads</th>
                    <th style="width: 20%;">Status</th>
                    <th style="width: 15%;">Pending Days</th>
                    <th style="width: 15%;">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $salesPersonData)
                    @php
                        $leads = $salesPersonData['leads'];
                        $count = $salesPersonData['count'];
                        $salesPerson = $salesPersonData['salesperson'];
                    @endphp
                    
                    @foreach($leads as $index => $lead)
                        <tr>
                            @if($index === 0)
                                <td class="salesperson-cell" rowspan="{{ $count }}">
                                    {{ $salesPerson->name }}
                                </td>
                            @endif
                            
                            <td>
                                <a href="{{ $lead['url'] }}" class="lead-link">#{{ $lead['id'] }} - {{ $lead['name'] }}</a>
                            </td>
                            
                            <td>
                                <span class="status-badge status-{{ strtolower($lead['status']) }}">
                                    {{ $lead['status'] }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="pending-days {{ $lead['pending_days'] > 3 ? 'urgent' : '' }}">
                                    {{ $lead['pending_days'] }} {{ $lead['pending_days'] == 1 ? 'day' : 'days' }}
                                </span>
                            </td>
                            
                            @if($index === 0)
                                <td class="count-cell" rowspan="{{ $count }}">
                                    {{ $count }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>📋 Report Details:</strong></p>
            <p>This report includes all leads with status: New, Contacted, and Working</p>
            <p>Pending days are calculated from the lead creation date</p>
            <p>&copy; {{ date('Y') }} Milele Motors. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
