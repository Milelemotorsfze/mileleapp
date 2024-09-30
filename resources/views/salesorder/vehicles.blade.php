@extends('layouts.table')
@section('content')
<div class="card-header">
        <h4 class="card-title">Commission Detail Vehicle Wise</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
    <p>Salesperson Name: {{ $salesPerson }}</p>
        <p>Invoice Number: {{ $invoice_number }}</p>
        <div class="table-responsive">
        <table id="dtBasicExample2" class="table table-striped table-bordered">
        <thead class="bg-soft-secondary">
                    <tr>
                        <th>Brand</th>
                        <th>Model Line</th>
                        <th>Variant</th>
                        <th>VIN</th>
                        <th>Purchased Cost</th>
                        <th>Sold Cost</th>
                        <th>Gross Profit Margin</th>
                        <th>Commission Rate</th>
                        <th>Total Commission</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($vehicles as $vehicle)
                    <tr>
                            <td>{{ $vehicle->brand_name }}</td>
                            <td>{{ $vehicle->model_line }}</td>
                            <td>{{ $vehicle->variant_name }}</td>
                            <td>{{ $vehicle->vin }}</td>
                            <td>{{ number_format($vehicle->total_vehicle_cost) }}</td>
                            <td>{{ number_format($vehicle->total_rate_in_aed) }}</td>
                            <td>{{ number_format($vehicle->total_rate_in_aed - $vehicle->total_vehicle_cost) }}</td>
                            <td>{{ number_format($vehicle->commission_rate, 2) }}%</td>
                            <td>{{ number_format(($vehicle->total_rate_in_aed - $vehicle->total_vehicle_cost) * ($vehicle->commission_rate / 100), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
