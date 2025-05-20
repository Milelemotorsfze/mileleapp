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
  /* padding: 4px 8px 4px 8px; */
  text-align: center;
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
    .nav-pills .nav-link {
      position: relative;
    }

    .badge-notification {
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(50%, -110%);
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
    }
  </style>
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('Calls-view');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Sales Person Info
    </h4>
    <br>
  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Sales Person</th>
                  <th>Remarks</th>
                  <th>Last Updated By</th>
                  <th>Last Updated On</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
          </div>
  <script>
        $(document).ready(function () {
          var table1 =  $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('sale_person_status.index')}}",
            columns: [
                { data: 'salespersonname', name: 'salespersonname' },
                { data: 'remarks', name: 'sales_person_status.remarks' },
                { data: 'created_by', name: 'sales_person_status.created_by' },
                { data: 'created_at', name: 'sales_person_status.created_at' },
                { data: 'status', name: 'sales_person_status.status' },
            ]
        });
  });
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection