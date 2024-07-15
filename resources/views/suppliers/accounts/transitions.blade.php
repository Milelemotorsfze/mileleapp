@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('vendor-accounts');
  @endphp
  @if ($hasPermission)
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
                    <th>Vehicles Count</th>
                    <th>Remarks</th>
                    @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('transition-approved');
  @endphp
  @if ($hasPermission)
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            @foreach ($transitions as $transition)
                <tr data-transition-id="{{ $transition->id }}">
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
                    <td>{{ $transition->vehicle_count }}</td>
                    <td>{{ $transition->remarks }}</td>
                    @php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('transition-approved');
  @endphp
  @if ($hasPermission)
                    <td>
                    @if($transition->transaction_type == "Pre-Debit" && $transition->status == "pending")
                        <button class="btn btn-success btn-sm" onclick="handleAction('approve', {{ $transition->id }})">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="showRejectModal({{ $transition->id }})">Reject</button>
                    @endif
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for rejection remarks -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Reject Transaction</h5>
        <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="rejectForm">
          <input type="hidden" id="rejectTransitionId">
          <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea class="form-control" id="remarks" rows="3" required></textarea>
          </div>
</br>
          <button type="submit" class="btn btn-danger">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function handleAction(action, transitionId, remarks = '') {
    $.ajax({
        url: '{{ route("transition.action") }}', // Update this route to your controller method
        type: 'POST',
        data: {
            id: transitionId,
            action: action,
            remarks: remarks
        },
        success: function(response) {
            alertify.success('Transitions Updated Successfully');
            setTimeout(function() {
            window.location.reload();
        }, 500);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}

function showRejectModal(transitionId) {
    $('#rejectTransitionId').val(transitionId);
    $('#rejectModal').modal('show');
}

$('#rejectForm').on('submit', function(event) {
    event.preventDefault();
    var transitionId = $('#rejectTransitionId').val();
    var remarks = $('#remarks').val();
    handleAction('reject', transitionId, remarks);
    $('#rejectModal').modal('hide');
});

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
@section('scripts')
@endsection