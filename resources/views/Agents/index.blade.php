@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
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
  </style>
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Agents Details
     <!-- <a class="btn btn-sm btn-success float-end" href="{{ route('Shipping.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New
      </a> -->
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Summary</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Agents With Qoutations</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Agents With SOs</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Agent ID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Detail With</th>
                  <th>Total Qouations</th>
                  <th>Total SO</th>
                  <th>Total Commission</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
                  <th>Qoutations ID</th>
                  <th>Agent ID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Detail With</th>
                  <th>Commission</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab3">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
            <tr>
            <th>SO ID</th>
            <th>Agent ID</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Detail With</th>
                  <th>Qoutations ID</th>
                  <th>Commission</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      </div>
    </div>
  </div>
  <script>
        $(document).ready(function () {
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('agents.index', ['status' => 'summary']) }}",
            columns: [
                { data: 'id', name: 'agents.id' },
                { data: 'name', name: 'agents.name' },
                { data: 'phone', name: 'agents.phone' },
                { data: 'email', name: 'agents.email' },
                { data: 'created_by_names', name: 'quotations.created_at' },
                { data: 'total_quotations', name: 'agents_commission.quotation_id' },
                { data: 'total_sales_orders', name: 'agents_commission.so_id' },
                { data: 'total_commission', name: 'agents_commission.commission' },
            ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('agents.index', ['status' => 'quotationwise']) }}",
            columns: [
                { data: 'quotation_id', name: 'agents_commission.quotation_id' },
                { data: 'id', name: 'agents.id' },
                { data: 'name', name: 'agents.name' },
                { data: 'email', name: 'agents.email' },
                { data: 'phone', name: 'agents.phone' },
                { data: 'names', name: 'users.name' },
                { data: 'commission', name: 'agents_commission.commission' },
            ]
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('agents.index', ['status' => 'sowise']) }}",
            columns: [
              { data: 'so_id', name: 'agents_commission.so_id' },
                { data: 'id', name: 'agents.id' },
                { data: 'name', name: 'agents.name' },
                { data: 'email', name: 'agents.email' },
                { data: 'phone', name: 'agents.phone' },
                { data: 'names', name: 'users.name' },
                { data: 'quotation_id', name: 'agents_commission.quotation_id' },
                { data: 'commission', name: 'agents_commission.commission' },
            ]
        });
});
</script>
@endsection