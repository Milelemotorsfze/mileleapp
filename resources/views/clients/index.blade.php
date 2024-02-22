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
     Customers Information
    </h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('dailyleads.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <a class="btn btn-sm btn-success float-end" href="{{ route('salescustomers.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Customer
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
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Types</th>
                    <th>Source</th>
                    <th>Language</th>
                    <th>Destination</th>
                    <th>Company Name</th>
                    <th>Documents</th>
                    <th>Actions</th>
                    <th>View Details</th>
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
        ajax: "{{ route('salescustomers.index') }}",
        columns: [
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'customertype', name: 'customertype' },
            { data: 'source', name: 'source' },
            { data: 'lauguage', name: 'lauguage' },
            { data: 'destination', name: 'destination' },
            { data: 'company_name', name: 'company_name' },
            { data: 'file_icons', name: 'file_icons', orderable: false, searchable: false },
            {
            data: null,
            render: function(data, type, row) {
                return '<a href="/customer-quotation-direct/' + data.id + '" class="btn btn-sm btn-primary"><i class="fas fa-file-alt"></i> Create Quotation</a>';
            }
        },
        { data: 'view_history_icon', name: 'view_history_icon', orderable: false, searchable: false },
        ],
    });
    $('[data-toggle="tooltip"]').tooltip();
$('#dtBasicExample1').on('click', '.file-icon', function () {
    var fileLink = $(this).data('file');
    var fileType = getFileType(fileLink);
    $('#fileModal').modal('show');
    if (fileType === 'image') {
        $('#modalContent').html('<img src="' + '/storage/' + fileLink + '" class="img-fluid" alt="Image">');
    } else if (fileType === 'pdf') {
    }
});
function getFileType(fileLink) {
    var fileExtension = fileLink.split('.').pop().toLowerCase();
    if (['jpg', 'jpeg', 'png', 'gif'].indexOf(fileExtension) !== -1) {
        return 'image';
    } else if (fileExtension === 'pdf') {
        return 'pdf';
    } else {
        return 'unknown';
    }
}
function getFileType(fileLink) {
    if (fileLink.endsWith('.pdf')) {
        return 'pdf';
    } else if (fileLink.endsWith('.jpg') || fileLink.endsWith('.jpeg') || fileLink.endsWith('.png')) {
        return 'image';
    } else {
        return 'unknown';
    }
}
function openPdfModal(pdfLink) {
    $('#pdfModal').modal('show');
    $('#pdfViewer').attr('src', pdfLink);
}
function openImageModal(imageLink) {
    $('#imageModal').modal('show');
    $('#imageViewer').attr('src', imageLink);
}
$('#dtBasicExample1').on('click', '.view-history-icon', function () {
    var clientId = $(this).data('client-id');
    var clientUrl = "{{ route('salescustomers.viewcustomers', ['clientId' => ':clientId']) }}";
    clientUrl = clientUrl.replace(':clientId', clientId);
    window.location.href = clientUrl;
});
});
</script>
@endsection