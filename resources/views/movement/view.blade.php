@extends('layouts.main')
@section('content')
@if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6')
<div class="card-header">
        <h4 class="card-title">Movements Transtion</h4>
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
            <label for="basicpill-firstname-input" class="form-label">Date : </label>
            <input type="Date" id="date" name="date" class="form-control" value = "{{$movementref->date}}" placeholder="Date" readonly>
        </div>
        </div>
        <br>
        <div class="table-responsive" >
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>VIN</th>
                    <th>Model</th>
                    <th>From</th>
                    <th>To</th>
                    <th>SO</th>
                    <th>PO</th>
                </tr>
                </thead>
                <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($movement as $movements)
                        <tr data-id="1">
                        <td>{{ $movements->vin }}</td>
                        <td>{{ $movements->vin }}</td>
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
                        <td>SO - {{ $so_numbers }}</td>
                        @php
                        $purchasingorderid = DB::table('vehicles')->where('vin', $movements->vin)->first();
                        $purchasingorderids = $purchasingorderid ? $purchasingorderid->purchasing_order_id : '';
                        $purchasing_orders = DB::table('purchasing_order')->where('id', $purchasingorderids)->first();
                        $po_number = $purchasing_orders ? $purchasing_orders->po_number : '';
                        @endphp
                        <td>PO - {{ $po_number }}</td>
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