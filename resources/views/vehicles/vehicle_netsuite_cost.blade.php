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
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-netsuite-price');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<a class="btn btn-sm btn-success float-end" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal" style="text-align: right;">
    <i class="fa fa-check" aria-hidden="true"></i> Uploading Vehicle Cost CSV
</a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <h4 class="card-title">
     Netsuite Vehicles Cost
    </h4>
    <br>
  </div>
        <div class="card-body">
            <!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Upload Vehicle Cost CSV</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="uploadForm" action="{{ route('vehiclenetsuitecost.upload') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label for="file" class="form-label">Choose Excel File</label>
            <input type="file" class="form-control" id="file" name="file" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="uploadForm">Upload</button>
      </div>
    </div>
  </div>
</div>
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Vehicle VIN</th>
                  <th>Vehicle Cost</th>
                  <th>Last Update</th>
                  <th>Netsuite Link</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div>  
    </div>
  </div>
  <script>
        $(document).ready(function () {
          var table1 =  $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vehiclenetsuitecost.index') }}",
            columns: [
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'cost', name: 'vehicle_netsuite_cost.cost', render: function(data, type, row) {
            return data ? parseInt(data).toLocaleString() : '';
        }},
                { data: 'last_update', name: 'vehicle_netsuite_cost.updated_at' },
                { 
                    data: 'netsuite_link', 
                    name: 'vehicle_netsuite_cost.netsuite_link',
                    render: function (data, type, row, meta) {
                        return '<button class="btn btn-primary" onclick="window.open(\'' + data + '\', \'_blank\')">Open Link</button>';
                    }
                },
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