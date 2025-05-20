@extends('layouts.main')
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('View-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
<div class="card-header">
        <h4 class="card-title">Movements Transition</h4>
    @if ($previousId)
    <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($previousId)]) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
    @endif
    <b>Ref No: {{$currentId}}</b>
    @if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($nextId)]) }}">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
    @else
    <a class="btn btn-sm btn-info" href="{{ route('movement.create') }}">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
    @endif
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
        <div class="row">
        <div class="col-lg-2 col-md-6">
    <label for="basicpill-firstname-input" class="form-label">Date Of Movement:</label>
    <div id="date" name="date">
        {{ \Carbon\Carbon::parse($movementref->date)->format('j-M-Y') }}
    </div>
</div>
        </div>
        <br>
        <div class="table-responsive" >
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>VIN</th>
                    <th>Model Line</th>
                    <th>From</th>
                    <th>To</th>
                    <th>SO</th>
                    <th>PO</th>
                    <th>Revised</th>
                </tr>
                </thead>
                <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($movement as $movements)
                        <tr data-id="1">
                        <td>{{ $movements->vin }}</td>
                        @php
                        $modellines = "";
                        $vehicles = DB::table('vehicles')->where('vin', $movements->vin)->first();
                        if($vehicles->varaints_id)
                        {
                        $varaints = DB::table('varaints')->where('id', $vehicles->varaints_id)->first();
                        if($varaints->master_model_lines_id)
                        {
                        $modellines = DB::table('master_model_lines')->where('id', $varaints->master_model_lines_id)->first();
                        $modellines = $modellines ? $modellines->model_line : '';
                        }
                         }
                         @endphp
                        <td>{{ $modellines }}</td>
                        @php
                        $locationfrom = DB::table('warehouse')->where('id', $movements->from)->first();
                        $from = $locationfrom ? $locationfrom->name : '';
                        @endphp
                        <td>{{ $from }}</td>
                        @php
                        $locationto = DB::table('warehouse')->where('id', $movements->to)->first();
                        $to = $locationto ? $locationto->name : '';
                        @endphp
                        <td>{{ $to }}</td>
                        @php
                        $soid = DB::table('vehicles')->where('vin', $movements->vin)->first();
                        $soids = $soid ? $soid->so_id : '';
                        $sonumber = DB::table('so')->where('id', $soids)->first();
                        $so_numbers = $sonumber ? $sonumber->so_number : '';
                        @endphp
                        <td>{{ $so_numbers }}</td>
                        @php
                        $purchasingorderid = DB::table('vehicles')->where('vin', $movements->vin)->first();
                        $purchasingorderids = $purchasingorderid ? $purchasingorderid->purchasing_order_id : '';
                        $purchasing_orders = DB::table('purchasing_order')->where('id', $purchasingorderids)->first();
                        $po_number = $purchasing_orders ? $purchasing_orders->po_number : '';
                        @endphp
                        <td>{{ $po_number }}</td>
                        @php
                        // Check if there is a more recent movement entry for the same VIN
                        $latestMovement = DB::table('movements')
                            ->where('vin', $movements->vin)
                            ->orderBy('created_at', 'desc')
                            ->first();
                    @endphp
                    <td>
                    @if($movementref->created_by == auth()->id())
                        @if ($latestMovement && $latestMovement->id == $movements->id)
                        <form action="{{ route('movement.revised', ['id' => $movements->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                                Revise
                            </button>
                        </form>
                        @endif
                        @endif
                    </td>
                        </tr>
                        @endforeach
                        </tbody>
            </table>
        </div>
		</br>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
    @endsection
@push('scripts')
@endpush