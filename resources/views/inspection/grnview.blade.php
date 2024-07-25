@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .red-star {
    color: red;
    font-size: 2.2em;
}
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
  </style>
@section('content')
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     GRN Info
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Netsuite GRN</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Netsuite Netsuite GRN Info</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Inspection Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>Old Varaint</th>
                  <th>New Varaint</th>
                  <th>Action</th>
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
                  <th>Inspection Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>Old Varaint</th>
                  <th>New Varaint</th>
                  <th>Netsuite GRN Number</th>
                  <th>Action</th>
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
        ajax: "{{ route('approvalsinspection.addingnetsuitegrn', ['status' => 'pending']) }}",
        columns: [
            { data: 'incidentsnumber', name: 'incident.id' },
            { data: 'vehicle_status', name: 'incident.vehicle_status' },
            { data: 'update_remarks', name: 'incident.update_remarks' },
            { data: 'part_po_number', name: 'incident.part_po_number' },
            { data: 'aging', name: 'aging', searchable: false },
            { data: 'po_number', name: 'purchasing_order.po_number' },
            { data: 'vin', name: 'vehicles.vin' },
            { data: 'engine', name: 'vehicles.engine' },
            { data: 'created_at_pending', name: 'inspection.created_at' },
            { data: 'remark', name: 'inspection.remark' },
            { data: 'type', name: 'incident.type' },
            { data: 'narration', name: 'incident.narration' },
            { data: 'reason', name: 'incident.reason' },
            { data: 'driven_by', name: 'incident.driven_by' },
            { data: 'responsivity', name: 'incident.responsivity' },
            { data: 'model_detail', name: 'varaints.model_detail' },
            { data: 'variant', name: 'varaints.name' },
            { data: 'detail', name: 'varaints.detail' },
            { data: 'my', name: 'varaints.my' },
            { data: 'steering', name: 'varaints.steering' },
            { data: 'seat', name: 'varaints.seat' },
            { data: 'fuel_type', name: 'varaints.fuel_type' },
            { data: 'gearbox', name: 'varaints.gearbox' },
            { data: 'upholestry', name: 'varaints.upholestry' },
            { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
            { data: 'interior_color', name: 'int_color.name' },
            { data: 'exterior_color', name: 'ex_color.name' },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, row) {
                    if (row.status === 'Re Work') {
                        return '<span class="red-star">*</span> ' + data;
                    }
                    return data;
                }
            }
        ]
    });
});
</script>
@endsection