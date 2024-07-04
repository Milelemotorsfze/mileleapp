@extends('layouts.table')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="card-header">
    <h4 class="card-title">
        Bank Account Transaction details
    </h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    <br>
</div>
<div class="card-body">
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#dtBasicExample1').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('bankaccounts.show', $bankaccount->id) }}",
        columns: [
            { data: 'created_at', name: 'created_at' },
            { data: 'type', name: 'type' },
            { data: 'amount', name: 'amount' },
            { data: 'created_by', name: 'created_by' }
        ]
    });
});
</script>
@endpush