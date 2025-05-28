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
     Payment Terms
    </h4>
    <a class="btn btn-sm btn-success float-end" href="{{ route('paymentterms.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Payment Terms
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <br>
  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Payment Terms ID</th>
                  <th>Payment Terms Name</th>
                  <th>Description</th>
                  <th>Milestons</th>
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
          $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('paymentterms.index') }}",
            columns: [
                { data: 'id', name: 'payment_terms.id' },
                { data: 'name', name: 'payment_terms.name' },
                { data: 'description', name: 'payment_terms.description' },
                { data: 'payment_milestone', name: 'payment_milestone', },
              
            ],
        });
      });
    </script>
@endsection