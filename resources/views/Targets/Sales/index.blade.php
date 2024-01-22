@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
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
     Sales Target Information
    </h4>
    <a class="btn btn-sm btn-success float-end" href="{{ route('salestargets.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Targets
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <br>
  </div>
  <div class="card-body">
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Sales Person</th>
                    <th>Month</th>
                    <th>Lead Time</th>
                    <th>Other Targets</th>
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
            ajax: "{{ route('salestargets.index') }}",
            columns: [
                { data: 'sales_person_name', name: 'users.name' },
                { data: 'formatted_month', name: 'sales_targets.month' },
                { data: 'lead_time', name: 'sales_targets_lead_time.leads_days' }, // New column for lead time
            ],
        });
    });
</script>
@endsection