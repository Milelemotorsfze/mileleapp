@extends('layouts.table')
<style>
    table {
        text-align: center;
    }
    th, td {
        vertical-align: middle;
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">
<h4>Strategy Report</h4>
            <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </h4>
    </div>
    <div class="card-body">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
        <div class="table-responsive" >
        <table id="dtBasicSupplierInventory" class="table table-striped table-bordered">
    <thead class="bg-soft-secondary">
    @php
    use Carbon\Carbon;
    $currentMonth = Carbon::now();
    $months = [];
    for ($i = 0; $i < 4; $i++) {
        $months[] = $currentMonth->format('F');
        $currentMonth = $currentMonth->subMonth();
    }
    $months = array_reverse($months);
@endphp
        <tr>
            <th rowspan="2" class="text-center">Lead Source</th>
            @foreach ($months as $month)
            <th colspan="2" class="text-center">{{ $month }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach ($months as $month)
            <th>Lead</th>
            <th>Strategies</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
@foreach ($LeadSource as $source)
    @php
    $today = date('Y-m-d');
$daysPassed = date('j', strtotime($today));
$last30Days = date('Y-m-d', strtotime("-{$daysPassed} days"));
$last60Days = date('Y-m-d', strtotime($last30Days . " -31 days"));
$last90Days = date('Y-m-d', strtotime($last60Days . " -30 days"));
$last120Days = date('Y-m-d', strtotime($last90Days . " -31 days"));
    $strategies = DB::table('lead_source')
    ->join('strategies', 'strategies.lead_source_id', '=', 'lead_source.id')
    ->join('strategies_dates', 'strategies_dates.strategies_id', '=', 'strategies.id')
    ->select('strategies.name')
    ->where('lead_source.id', '=', $source->id)
    ->where(function ($query) use ($last30Days, $today) {
        $query->whereBetween('strategies_dates.starting_date', [$last30Days, $today])
            ->orWhereBetween('strategies_dates.ending_date', [$last30Days, $today]);
    })
    ->get();
    $leads = DB::table('lead_source')
        ->join('calls', 'calls.source', '=', 'lead_source.id')
        ->where('lead_source.id', '=', $source->id)
        ->whereBetween('calls.created_at', [$last30Days, $today])
        ->count();
    $counts = count($strategies);
    $strategies60 = DB::table('lead_source')
        ->join('strategies', 'strategies.lead_source_id', '=', 'lead_source.id')
        ->join('strategies_dates', 'strategies_dates.strategies_id', '=', 'strategies.id')
        ->select('strategies.name')
        ->where('lead_source.id', '=', $source->id)
        ->where(function ($query) use ($last60Days, $last30Days) {
        $query->whereBetween('strategies_dates.starting_date', [$last60Days, $last30Days])
            ->orWhereBetween('strategies_dates.ending_date', [$last60Days, $last30Days]);
    })
    ->get();
    $leads60 = DB::table('lead_source')
        ->join('calls', 'calls.source', '=', 'lead_source.id')
        ->where('lead_source.id', '=', $source->id)
        ->whereBetween('calls.created_at', [$last60Days, $last30Days])
        ->count();
    $counts60 = count($strategies60);
    $strategies90 = DB::table('lead_source')
        ->join('strategies', 'strategies.lead_source_id', '=', 'lead_source.id')
        ->join('strategies_dates', 'strategies_dates.strategies_id', '=', 'strategies.id')
        ->select('strategies.name')
        ->where('lead_source.id', '=', $source->id)
        ->where(function ($query) use ($last90Days, $last60Days) {
        $query->whereBetween('strategies_dates.starting_date', [$last90Days, $last60Days])
            ->orWhereBetween('strategies_dates.ending_date', [$last90Days, $last60Days]);
    })
    ->get();
    $leads90 = DB::table('lead_source')
        ->join('calls', 'calls.source', '=', 'lead_source.id')
        ->where('lead_source.id', '=', $source->id)
        ->whereBetween('calls.created_at', [$last90Days, $last60Days])
        ->count();
    $counts90 = count($strategies90);
    $strategies120 = DB::table('lead_source')
        ->join('strategies', 'strategies.lead_source_id', '=', 'lead_source.id')
        ->join('strategies_dates', 'strategies_dates.strategies_id', '=', 'strategies.id')
        ->select('strategies.name')
        ->where('lead_source.id', '=', $source->id)
        ->where(function ($query) use ($last120Days, $last90Days) {
        $query->whereBetween('strategies_dates.starting_date', [$last120Days, $last90Days])
            ->orWhereBetween('strategies_dates.ending_date', [$last120Days, $last90Days]);
    })
    ->get();
    $leads120 = DB::table('lead_source')
        ->join('calls', 'calls.source', '=', 'lead_source.id')
        ->where('lead_source.id', '=', $source->id)
        ->whereBetween('calls.created_at', [$last120Days, $last90Days])
        ->count();
    $counts120 = count($strategies120);
    @endphp
    <tr>
        <td>{{ $source->source_name }}</td>
        @if ($leads120 == 0)
            <td>-</td>
        @else
            <td>{{ $leads120 }}</td>
        @endif
        @if ($counts120 > 0)
            <td>
                @foreach ($strategies120 as $strategy120)
                    {{ $strategy120->name }}<br>
                @endforeach
            </td>
        @else
            <td>-</td>
        @endif
        @if ($leads90 == 0)
            <td>-</td>
        @else
            <td>{{ $leads90 }}</td>
        @endif
        @if ($counts90 > 0)
            <td>
                @foreach ($strategies90 as $strategy90)
                    {{ $strategy90->name }}<br>
                @endforeach
            </td>
        @else
            <td>-</td>
        @endif
        @if ($leads60 == 0)
            <td>-</td>
        @else
            <td>{{ $leads60 }}</td>
        @endif
        @if ($counts60 > 0)
            <td>
                @foreach ($strategies60 as $strategy60)
                    {{ $strategy60->name }}<br>
                @endforeach
            </td>
        @else
            <td>-</td>
        @endif
        @if ($leads == 0)
            <td>-</td>
        @else
            <td>{{ $leads }}</td>
        @endif
        @if ($counts > 0)
            <td>
                @foreach ($strategies as $strategy)
                    {{ $strategy->name }}<br>
                @endforeach
            </td>
        @else
            <td>-</td>
        @endif
    </tr>
@endforeach
</tbody>
</table>
        </div>
    </div>
@endsection