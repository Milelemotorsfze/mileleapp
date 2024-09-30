@extends('layouts.table')
@section('content')
<div class="card-header">
        <h4 class="card-title">Commission Detail Final Bills Wise Of Sales Person</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <p>Salesperson Name: {{ $salesPerson->name }}</p>
        <p>Month: {{ \Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</p>
        <div class="table-responsive">
        <table id="dtBasicExample2" class="table table-striped table-bordered">
        <thead class="bg-soft-secondary">
                    <tr>
                        <th>Invoice Number</th>
                        <th>Number of Vehicles</th>
                        <th>Total Cost Price</th>
                        <th>Total Sale Price</th>
                        <th>Gross Profit Margin</th>
                        <th>Commission Rate</th>
                        <th>Total Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commissions as $data)
                        <tr>
                            <td>{{ $data->invoice_number }}</td> <!-- Invoice ID -->
                            <td>
        <a href="{{ route('salesperson.vehicles', ['vehicle_invoice_id' => $data->invoice_id]) }}">
            {{ $data->total_invoice_items }}
        </a>
    </td>
                            <td>{{ number_format($data->total_vehicle_cost) }}</td> <!-- Total Cost Price -->
                            <td>{{ number_format($data->total_rate_in_aed) }}</td> <!-- Total Sale Price -->
                            <td>{{ number_format($data->total_rate_in_aed - $data->total_vehicle_cost) }}</td> <!-- Gross Profit Margin -->
                            <td>{{ number_format($data->commission_rate, 2) }}%</td> <!-- Commission Rate -->
                            <td>{{ number_format(($data->total_rate_in_aed - $data->total_vehicle_cost) * ($data->commission_rate / 100), 2) }}</td> <!-- Total Commission -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
