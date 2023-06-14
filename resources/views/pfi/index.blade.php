@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            PFI List
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table table-condensed" style="">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>S.NO</th>
                    <th>Reference Number</th>
                    <th>Date</th>
                    <th>Customer Name </th>
                    <th>Customer Country</th>
                    <th>Amount</th>
                    <th>Comment</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($pfis as $key => $pfi)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $pfi->pfi_reference_number }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($pfi->pfi_date)->format('d M y') }}</td>
                        <td>{{ $pfi->letterOfIndent->customer->name }}</td>
                        <td>{{ $pfi->letterOfIndent->customer->country  }}</td>
                        <td>{{ $pfi->amount }}</td>
                        <td>{{ $pfi->comment }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

















