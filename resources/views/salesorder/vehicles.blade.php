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
                @php
                    $total_vehicle_cost = 0;
                    $total_sold_cost = 0;
                    $total_profit_margin = 0;
                    $total_commission = 0;
                @endphp
                @foreach ($vehicles as $vehicle)
                    @php
                        $profit_margin = $vehicle->total_rate_in_aed - $vehicle->total_vehicle_cost;
                        $commission = $profit_margin * ($vehicle->commission_rate / 100);
                        $total_vehicle_cost += $vehicle->total_vehicle_cost;
                        $total_sold_cost += $vehicle->total_rate_in_aed;
                        $total_profit_margin += $profit_margin;
                        $total_commission += $commission;
                    @endphp
                    <tr>
                        <td>{{ $vehicle->brand_name }}</td>
                        <td>{{ $vehicle->model_line }}</td>
                        <td>{{ $vehicle->variant_name }}</td>
                        <td>{{ $vehicle->vin }}</td>
                        <td>{{ number_format($vehicle->total_vehicle_cost) }}</td>
                        <td>{{ number_format($vehicle->total_rate_in_aed) }}</td>
                        <td>{{ number_format($profit_margin) }}</td>
                        <td>{{ number_format($vehicle->commission_rate, 2) }}%</td>
                        <td>{{ number_format($commission, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    // Query to find the appropriate commission slot for the total profit margin
                    $commissionSlot = DB::table('commission_slots')
                        ->where('min_sales', '<=', $total_profit_margin)
                        ->where(function($query) use ($total_profit_margin) {
                            $query->where('max_sales', '>=', $total_profit_margin)
                                  ->orWhereNull('max_sales');
                        })
                        ->orderBy('min_sales', 'desc')
                        ->first();
                    
                    // Get the rate from the commission slot, or default to 0
                    $total_commission_rate = $commissionSlot ? $commissionSlot->rate : 0;
                @endphp
                <tr>
                    <th colspan="4" style="text-align:right">Total:</th>
                    <th>{{ number_format($total_vehicle_cost) }}</th>
                    <th>{{ number_format($total_sold_cost) }}</th>
                    <th>{{ number_format($total_profit_margin) }}</th>
                    <th>{{ number_format($total_commission_rate, 2) }}%</th> <!-- Display Total Commission Rate -->
                    <th>{{ number_format($total_commission, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection