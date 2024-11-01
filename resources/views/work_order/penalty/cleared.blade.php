@extends('layouts.table')
<head>
    <style>
        th {
            font-size:12px!important;
        }
        td {
            font-size:12px!important;
        }
        .btn-style {
            font-size:0.7rem!important;
            line-height: 0.1!important;
        }
    </style>
</head>
@section('content')
<body>
    <div class="card-header">
        <h4 class="card-title">Cleared Penalties Info</h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-editable table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Sl No</th>
                            <th>SO Number</th>
                            <th>WO Number</th>
                            <th>BOE Number</th>
                            <th>VIN Number</th>
                            <th>Declaration Date</th>
                            <th>Penalty Start</th>
                            <th>Excess Days</th>
                            <th>Total Penalty(AED)</th>
                            <th>Amount Paid(AED)</th>
                            <th>Payment Date</th>
                            <th>Payment Receipt</th>
                            <th>Remark</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($datas) && count($datas) > 0)
                            @foreach($datas as $data)
                                @if($data->woBoe->declaration_date != '')
                                    @php
                                        $daysDifference = '';
                                        $thirtiethDay = \Carbon\Carbon::parse($data->woBoe->declaration_date)->addDays(29);
                                        $today = \Carbon\Carbon::today();
                                        $daysDifference = $thirtiethDay->diffInDays($today, false) + 1;
                                    @endphp
                                @endif
                                @if(isset($daysDifference))
                                    @php
                                        $penalty = $daysDifference * 200;
                                    @endphp
                                @endif
                                <div hidden>{{$i=0;}}</div>
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $data->workOrder->so_number ?? '' }}</td>
                                    <td>{{ $data->workOrder->wo_number ?? '' }}</td>
                                    <td>{{ $data->woBoe->boe ?? '' }}</td>
                                    <td>{{ $data->vin ?? '' }}</td>
                                    <td>@if($data->woBoe->declaration_date != ''){{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->format('d M Y') }}@endif</td>
                                    <td>@if($data->woBoe->declaration_date != ''){{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->addDays(29)->format('d M Y') }}@endif</td>
                                    <td>{{ $data->penalty->excess_days ?? '' }}</td>
                                    <td>{{ $data->penalty->total_penalty_amount ?? '' }}</td>
                                    <td>{{ $data->penalty->amount_paid ?? '' }}</td>
                                    <td>@if($data->penalty->payment_date != ''){{ \Carbon\Carbon::parse($data->woBoe->payment_date)->format('d M Y') }}@endif</td>
                                    @props(['filePath', 'fileName'])
                                    <td class="no-click">
                                        @if(isset($data->penalty->payment_receipt))
                                            @php
                                                $filePath = 'storage/'; // Update to the correct path if needed
                                                $fileName = $data->penalty->payment_receipt;
                                            @endphp

                                            <a href="{{ url($filePath . $fileName) }}" target="_blank">
                                                <button class="btn btn-primary mb-1 btn-style">View</button>
                                            </a>
                                            <a href="{{ url($filePath . $fileName) }}" download>
                                                <button class="btn btn-info btn-style">Download</button>
                                            </a>
                                        @else
                                            <span>No receipt available</span>
                                        @endif
                                    </td>
                                    <td>{{ $data->penalty->remarks ?? '' }}</td>
                                    <td>{{ $data->penalty->createdUser->name ?? '' }}</td>
                                    <td>@if($data->penalty->created_at != ''){{ \Carbon\Carbon::parse($data->woBoe->created_at)->format('d M Y') }}@endif</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">No data history available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        @if(isset($datas) && count($datas) > 0)
            // Initialize DataTable since $datas has rows
            var table = $('.my-datatableclass').DataTable({  // Use DataTable() here
                paging: true,
                info: true,
                lengthChange: true,
            });
        @else
            console.log("No data available to initialize DataTable.");
        @endif        
    });
</script>
</body>
@endsection

