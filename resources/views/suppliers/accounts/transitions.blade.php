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
                    <th>Transaction AT</th>
                    <th>PO Number</th>
                    <th>Transaction Type</th>
                    <th>Currency</th>
                    <th>Transaction Amount</th>
                    <th>Adjustment Amount</th>
                    <th>Total Amount</th>
                    <th>Transaction By</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($transitions as $transition)
                <tr>
                    <td>{{ $transition->created_at }}</td>
                    <td>{{ $transition->purchaseOrder->order_number ?? 'No Order Number' }}</td>
                    <td>{{ $transition->transaction_type }}</td>
                    <td>{{ $transition->account_currency }}</td>
                    <td>{{ $transition->transaction_amount }}</td>
                    <td>{{ $transition->adjustamount }}</td>
                    <td>{{ $transition->totalamount }}</td>
                    <td>{{ $transition->created_by }}</td>
                    <td>{{ $transition->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection