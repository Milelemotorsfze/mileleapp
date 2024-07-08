@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Account Transitions - {{ $accounts->supplier->supplier }}
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
  </div>
  <div class="card-body">
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Transaction Number</th>
                    <th>Transaction AT</th>
                    <th>PO Number</th>
                    <th>Transaction Type</th>
                    <th>Transaction Amount</th>
                    <th>Currency</th>
                    <th>Transaction By</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($transitions as $transition)
                <tr>
                <td>{{ $transition->purchaseOrder->po_number ?? 'No Order Number' }} - {{ $transition->row_number }}</td>
                <td>{{ $transition->created_at->format('d M Y') }}</td>
                <td>
                    @if($transition->purchaseOrder)
                        <a href="{{ route('purchasing-order.show', $transition->purchaseOrder->id) }}" target="_blank">
                            {{ $transition->purchaseOrder->po_number }}
                        </a>
                    @else
                        No Order Number
                    @endif
                </td>
                    <td>{{ $transition->transaction_type }}</td>
                    <td>{{ number_format($transition->transaction_amount, 0, '', ',') }}</td>
                    <td>{{ $transition->account_currency }}</td>
                    <td>{{ $transition->user->name }}</td>
                    <td>{{ $transition->remarks }}</td>
                    <td>{{ $transition->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection