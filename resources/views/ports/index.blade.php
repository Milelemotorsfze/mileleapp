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
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Shipping Ports Info
    </h4>
    <a class="btn btn-sm btn-success float-end" href="{{ route('ports.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Ports
      </a>
                <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a  class="btn btn-sm btn-info float-end" href="{{ route('Shipping.index') }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    <br>
  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Port Name</th>
                  <th>Country</th>
                  <th>Created At</th>
                  <th>Edit</th>
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
            ajax: "{{ route('ports.index')}}",
            columns: [
                { data: 'name', name: 'master_shipping_ports.name' },
                { data: 'countryname', name: 'countries.name' },
                { data: 'created_at', name: 'created_at' },
                {
    data: null,
    render: function (data) {
        var editRoute = "{{ route('ports.edit', ':id') }}";
        editRoute = editRoute.replace(':id', data.id);
        return `<a href="${editRoute}" class="btn btn-info btn-sm">
        <i class="fa fa-arrow-circle-right"></i>
                </a>`;
    }
},
            ]
        });
  });
</script>
@endsection