@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.file-icon {
    margin-right: 10px;
}
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
  #dtBasicExample1 tbody tr:hover {
    cursor: pointer;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1;
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
    }
  </style>
@section('content')
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Customers Account Transtion Information
    </h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('salescustomers.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Deposit
      </a>
    <br>
  </div>
<div class="modal" tabindex="-1" role="dialog" id="fileModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
  <div class="card-body">
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Transition ID</th>
                    <th>Date</th>
                    <th>Transition Type</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Remarks</th>
                    <th>Created By</th>
                    <th>View Slip</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
        $(document).ready(function () {
            $('#dtBasicExample1').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('clienttransitions.clienttransitions', ['client_id' => $client->id]) }}",
                columns: [
                    { data: 'id', name: 'client_account_transition.id' },
                    { data: 'formatted_created_at', name: 'client_account_transition.created_at' },
                    { data: 'transition_type', name: 'client_account_transition.transition_type' },
                    { data: 'currency', name: 'client_account_transition.currency' },
                    { data: 'amount', name: 'client_account_transition.amount' },
                    { data: 'remarks', name: 'client_account_transition.remarks' },
                    { data: 'name', name: 'users.name' },
                    { data: 'remarks', name: 'client_account_transition.remarks' },
                ]
            });
        });
    </script>
@endsection