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
     Documents Info
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Incoming Stocks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Documents Update Pending</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">In Stock Vehicles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Pending BL</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Sold Vehicles</a>
      </li>
    </ul>      
  </div>
  <div class="modal fade documents-modal" id="documents" tabindex="-1" aria-labelledby="documentsLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentsLabel">Documents VIN: <span id="vin"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="vehicleId" name="vehicleId" />
        <div class="form-group mb-3">
          <label for="importDocumentType" class="text-left">Import Document Type</label>
          <select class="form-control" id="importDocumentType" name="importDocumentType">
            <option value="Belgium Docs">Belgium Docs</option>
            <option value="BOE + VCC + Exit">BOE + VCC + Exit</option>
            <option value="Cross Trade">Cross Trade</option>
            <option value="Dubai Trade">Dubai Trade</option>
            <option value="No Records">No Records</option>
            <option value="RTA Possession">RTA Possession</option>
            <option value="RTA Registration">RTA Registration</option>
            <option value="Supplier Docs">Supplier Docs</option>
            <option value="VCC">VCC</option>
            <option value="Zimbabwe">Zimbabwe</option>
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="documentOwnership" class="text-left">Document Ownership</label>
          <select class="form-control" id="documentOwnership" name="documentOwnership">
            <option value="Abdul Azeem">Abdul Azeem</option>
            <option value="Barwil (Supplier)">Barwil (Supplier)</option>
            <option value="Belgium Warehouse">Belgium Warehouse</option>
            <option value="Faisal Raiz">Faisal Raiz</option>
            <option value="Feroz Raiz">Feroz Raiz</option>
            <option value="Globelink (Supplier)">Globelink (Supplier)</option>
            <option value="Milele">Milele</option>
            <option value="Milele Car Trading LLC">Milele Car Trading LLC</option>
            <option value="Milele Motors FZE">Milele Motors FZE</option>
            <option value="No Records">No Records</option>
            <option value="OneWorld Limousine">OneWorld Limousine</option>
            <option value="Supplier">Supplier</option>
            <option value="Trans Car FZE">Trans Car FZE</option>
            <option value="Zimbabwe Docs">Zimbabwe Docs</option>
          </select>
        </div>
        <div class="form-group">
          <label for="documentWith" class="text-left">Document With</label>
          <select class="form-control" id="documentWith" name="documentWith">
            <option value="Accounts">Accounts</option>
            <option value="Finance Department">Finance Department</option>
            <option value="Import Department">Import Department</option>
            <option value="Not Applicable">Not Applicable</option>
            <option value="Supplier">Supplier</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveButton">Save</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade documentsstock-modal" id="documentsstock" tabindex="-1" aria-labelledby="documentsstockLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentsstockLabel">Documents VIN: <span id="vinstock"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="vehicleIdstock" name="vehicleIdstock" />
        <input type="hidden" id="docid" name="docid" />
        <div class="form-group mb-3">
          <label for="importDocumentType" class="text-left">Import Document Type</label>
          <select class="form-control" id="importtypestock" name="importtypestock">
            <option value="Belgium Docs">Belgium Docs</option>
            <option value="BOE + VCC + Exit">BOE + VCC + Exit</option>
            <option value="Cross Trade">Cross Trade</option>
            <option value="Dubai Trade">Dubai Trade</option>
            <option value="No Records">No Records</option>
            <option value="RTA Possession">RTA Possession</option>
            <option value="RTA Registration">RTA Registration</option>
            <option value="Supplier Docs">Supplier Docs</option>
            <option value="VCC">VCC</option>
            <option value="Zimbabwe">Zimbabwe</option>
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="documentOwnership" class="text-left">Document Ownership</label>
          <select class="form-control" id="owershipstock" name="owershipstock">
            <option value="Abdul Azeem">Abdul Azeem</option>
            <option value="Barwil (Supplier)">Barwil (Supplier)</option>
            <option value="Belgium Warehouse">Belgium Warehouse</option>
            <option value="Faisal Raiz">Faisal Raiz</option>
            <option value="Feroz Raiz">Feroz Raiz</option>
            <option value="Globelink (Supplier)">Globelink (Supplier)</option>
            <option value="Milele">Milele</option>
            <option value="Milele Car Trading LLC">Milele Car Trading LLC</option>
            <option value="Milele Motors FZE">Milele Motors FZE</option>
            <option value="No Records">No Records</option>
            <option value="OneWorld Limousine">OneWorld Limousine</option>
            <option value="Supplier">Supplier</option>
            <option value="Trans Car FZE">Trans Car FZE</option>
            <option value="Zimbabwe Docs">Zimbabwe Docs</option>
          </select>
        </div>
        <div class="form-group">
          <label for="documentWith" class="text-left">Document With</label>
          <select class="form-control" id="documentwithstock" name="documentwithstock">
            <option value="Accounts">Accounts</option>
            <option value="Finance Department">Finance Department</option>
            <option value="Import Department">Import Department</option>
            <option value="Not Applicable">Not Applicable</option>
            <option value="Supplier">Supplier</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="updateButton">Update</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade documentspendingbl-modal" id="documentspendingbl" tabindex="-1" aria-labelledby="documentspendingblLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentspendingblLabel">Documents VIN: <span id="vinpendingbl"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="vehicleIdpendingbl" name="vehicleIdpendingbl" />
        <input type="hidden" id="docidpendingbl" name="docidpendingbl" />
        <div class="form-group mb-3">
          <label for="blnumber" class="text-left">BL Number</label>
          <input type="text" id="blnumber" class="form-control" name="blnumber" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveblButton">Save</button>
      </div>
    </div>
  </div>
</div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Purchasing Order Number</th>
                  <th>Model</th>
                  <th>Qty</th>
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
                <th>VIN</th>
                <th>Model</th>
                  <th>Warehouse</th>
                  <th>Purchasing Order Number</th>
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
                  <th>VIN</th>
                  <th>Import Document Type</th>
                  <th>Document Ownership</th>
                  <th>Document With</th>
                  <th>Model</th>
                  <th>Warehouse</th>
                  <th>PO Number</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab4">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>VIN</th>
                  <th>Import Document Type</th>
                  <th>Document Ownership</th>
                  <th>Document With</th>
                  <th>Model</th>
                  <th>Warehouse</th>
                  <th>PO Number</th>
                  <th>SO Number</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab5">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample5" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>VIN</th>
                  <th>Import Document Type</th>
                  <th>Document Ownership</th>
                  <th>Document With</th>
                  <th>Model</th>
                  <th>Warehouse</th>
                  <th>PO Number</th>
                  <th>SO Number</th>
                  <th>BL Number</th>
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
            ajax: "{{ route('logisticsdocuments.index', ['status' => 'Incoming']) }}",
            columns: [
        { data: 'po_number', name: 'purchasing_order.po_number' },
        { data: 'model_details', name: 'model_details', searchable: false },
        { data: 'vehicle_count', name: 'vehicles.id', searchable: false },
    ],
    columnDefs: [
        {
            targets: 1,
            render: function (data, type, row, meta) {
                // Split and display each model_detail on a new line
                var modelDetailsArray = data.split(',');
                var modelDetailsHtml = '';
                modelDetailsArray.forEach(function (modelDetail) {
                    modelDetailsHtml += modelDetail + '<br>';
                });
                return modelDetailsHtml;
            }
        },
        {
            targets: [0, 2], // Target the first and third columns (0-indexed)
            className: 'text-center'
        }
    ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('logisticsdocuments.index', ['status' => 'Pending']) }}",
            columns: [
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
            ]
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('logisticsdocuments.index', ['status' => 'Instock']) }}",
            columns: [
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'import_type', name: 'documents.import_type' },
                { data: 'owership', name: 'documents.owership' },
                { data: 'document_with', name: 'documents.document_with' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
            ]
        });
        $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('logisticsdocuments.index', ['status' => 'PendingBL']) }}",
            columns: [
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'import_type', name: 'documents.import_type' },
                { data: 'owership', name: 'documents.owership' },
                { data: 'document_with', name: 'documents.document_with' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'so_number', name: 'so.so_number' },
            ]
        });
        $('#dtBasicExample5').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('logisticsdocuments.index', ['status' => 'Sold']) }}",
            columns: [
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'import_type', name: 'documents.import_type' },
                { data: 'owership', name: 'documents.owership' },
                { data: 'document_with', name: 'documents.document_with' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'bl_number', name: 'documents.bl_number' },
            ]
        });
  });
    </script>
<script>
  $(document).ready(function () {
    var table1 = $('#dtBasicExample2').DataTable();
    $('#dtBasicExample2 tbody').on('dblclick', 'tr', function () {
    var data = table1.row(this).data();
    var vehicleId = data.id;
    var vin = data.vin;
    $('#vehicleId').val(vehicleId);
    $('#vin').text(vin);
    $('#documents').modal('show');
});
    var table2 = $('#dtBasicExample3').DataTable();
    $('#dtBasicExample3 tbody').on('dblclick', 'tr', function () {
    var data = table2.row(this).data();
    var vehicleId = data.id;
    var docid = data.docid;
    var importtype = data.import_type;
    var owership = data.owership;
    var documentwith = data.document_with;
    var vin = data.vin;
    $('#vehicleIdstock').val(vehicleId);
    $('#importtypestock').val(importtype);
    $('#owershipstock').val(owership);
    $('#docid').val(docid);
    $('#documentwithstock').val(documentwith);
    $('#vinstock').text(vin);
    $('#documentsstock').modal('show');
});
var table3 = $('#dtBasicExample4').DataTable();
    $('#dtBasicExample4 tbody').on('dblclick', 'tr', function () {
    var data = table3.row(this).data();
    var vehicleId = data.id;
    var docid = data.docid;
    var vin = data.vin;
    $('#vehicleIdpendingbl').val(vehicleId);
    $('#docidpendingbl').val(docid);
    $('#vinpendingbl').text(vin);
    $('#documentspendingbl').modal('show');
});
$('#saveButton').on('click', function () {
    var vehicleId = $('#vehicleId').val();
    var importDocumentType = $('#importDocumentType').val();
    var documentOwnership = $('#documentOwnership').val();
    var documentWith = $('#documentWith').val();
    var dataToSend = {
      vehicleId: vehicleId,
      importDocumentType: importDocumentType,
      documentOwnership: documentOwnership,
      documentWith: documentWith
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    $.ajax({
      url: '{{ route("logisticsdocuments.store") }}',
      type: 'POST',
      data: dataToSend,
      success: function (response) {
        alertify.success('Document Status Updated');
        $('#documents').modal('hide');
        $('#dtBasicExample2').DataTable().ajax.reload(null, false);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
      }
    });
  });
  $('#updateButton').on('click', function () {
    var vehicleId = $('#vehicleIdstock').val();
    var importDocumentType = $('#importtypestock').val();
    var documentOwnership = $('#owershipstock').val();
    var documentWith = $('#documentwithstock').val();
    var documentId = $('#docid').val();
    var dataToSend = {
        documentId: documentId,
      vehicleId: vehicleId,
      importDocumentType: importDocumentType,
      documentOwnership: documentOwnership,
      documentWith: documentWith
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    $.ajax({
      url: '{{ route("logisticsdocuments.updatedoc")}}',
      type: 'POST',
      data: dataToSend,
      success: function (response) {
        alertify.success('Document Status Updated');
        $('#documentsstock').modal('hide');
        $('#dtBasicExample3').DataTable().ajax.reload(null, false);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
      }
    });
  });
  $('#saveblButton').on('click', function () {
    var vehicleId = $('#vehicleIdpendingbl').val();
    var blnumber = $('#blnumber').val();
    var documentId = $('#docidpendingbl').val();
    var dataToSend = {
    documentId: documentId,
    vehicleId: vehicleId,
    blnumber: blnumber,     
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    $.ajax({
      url: '{{ route("logisticsdocuments.updatedocbl")}}',
      type: 'POST',
      data: dataToSend,
      success: function (response) {
        alertify.success('BL Document Status Updated');
        $('#documentspendingbl').modal('hide');
        $('#dtBasicExample4').DataTable().ajax.reload(null, false);
      },
      error: function (xhr, status, error) {
        console.error('Error:', error);
      }
    });
  });
});
</script>

@endsection