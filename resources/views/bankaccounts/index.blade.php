@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Bank Accounts
    </h4>
    <div class="d-flex justify-content-between">
  <div class="d-flex">
    <a class="btn btn-sm btn-secondary me-2" href="#" style="text-align: left;">
      Total Current Balance is {{ number_format($totalBalanceAED, 0, '', ',') }} AED
    </a>
    <a class="btn btn-sm btn-info" href="#" style="text-align: left;">
      Total Available Funds is {{ number_format($availableFunds, 0, '', ',') }} AED
    </a>
  </div>
  <div class="d-flex">
    <a class="btn btn-sm btn-success me-2" href="{{ route('bankaccounts.create') }}" style="text-align: right;">
      <i class="fa fa-plus" aria-hidden="true"></i> Create Bank Account
    </a>
    <a class="btn btn-sm btn-primary" href="{{ route('banks.index') }}" style="text-align: right;">
      Banks <i class="fa fa-arrow-right" aria-hidden="true"></i> 
    </a>
  </div>
</div>
    <br>
  </div>
<div class="modal fade" id="updateBalanceModal" tabindex="-1" role="dialog" aria-labelledby="updateBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateBalanceModalLabel">Update Current Balance</h5>
                <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateBalanceForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="bankAccountId">
                    <div class="form-group">
                        <label for="currentBalance"><strong>Current Balance</strong></label>
                        <p id="currentBalance" class="form-control-plaintext"></p>
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="newBalance"><strong>New Balance</strong></label>
                        <input type="number" name="new_balance" id="newBalance" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Balance</button>
                </div>
            </form>
        </div>
    </div>
</div>
  <div class="card-body">
  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="table-responsive">
    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
    <thead class="bg-soft-secondary">
        <tr>
            <th>Entity</th>
            <th>Bank Name</th>
            <th>Account Number</th>
            <th>Current Balance</th>
            <th>Currency</th>
            <th>Update Balance</th>
            <th>View Updates Log</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bankaccounts as $bankaccount)
        <tr>
            <td>{{ $bankaccount->entity }}</td>
            <td>{{ $bankaccount->bank->bank_name }}</td>
            <td>{{ $bankaccount->account_number }}</td>
            <td>{{ number_format($bankaccount->current_balance, 0, '', ',') }}</td>
            <td>{{ $bankaccount->currency }}</td>
            <td>
                <a href="#" class="btn btn-sm btn-primary btn-rounded shadow-sm updateBalanceModal" data-toggle="modal" data-target="#updateBalanceModal" data-id="{{ $bankaccount->id }}" data-balance="{{ number_format($bankaccount->current_balance, 0, '.', ',') }}">Update Balance</a>
            </td>
            <td>
                <a href="{{ route('bankaccount.show', ['id' => $bankaccount->id]) }}" class="btn btn-sm btn-success shadow-sm">View</a>
            </td>
            <td>
            <a href="{{ route('bankaccounts.edit', ['bankaccount' => $bankaccount->id]) }}" class="btn btn-sm btn-warning shadow-sm">Edit</a>
            <form action="{{ route('bankaccounts.destroy', ['bankaccount' => $bankaccount->id]) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger shadow-sm">Delete</button>
</form>
</td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('.updateBalanceModal').on('click', function(event) {
            event.preventDefault();
            var button = $(this);
            var id = button.data('id');
            var balance = button.data('balance');
            var modal = $('#updateBalanceModal');
            modal.find('#bankAccountId').val(id);
            modal.find('#currentBalance').text(balance);
            modal.modal('show');
        });
        $('#updateBalanceForm').on('submit', function (e) {
            e.preventDefault();

            var formData = {
                id: $('#bankAccountId').val(),
                new_balance: $('#newBalance').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                type: 'POST',
                url: '{{ route("bankaccounts.update_balance") }}',
                data: formData,
                success: function (response) {
                    alert('Balance updated successfully.');
                    $('#updateBalanceModal').modal('hide');
                    alertify.success('Current Balance Update Successfully');
                    location.reload();
                },
                error: function (response) {
                    alert('An error occurred while updating the balance.');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Automatically close alerts after 5 seconds
        setTimeout(function() {
            $(".alert").alert('close');
        }, 5000);
    });
</script>
@endsection