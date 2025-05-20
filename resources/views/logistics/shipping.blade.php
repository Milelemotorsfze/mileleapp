@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
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
  </style>
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
  <div class="card-header">
    <h4 class="card-title">
     Shipping Info
     <a class="btn btn-sm btn-primary float-end" href="{{ route('ports.index') }}" text-align: right>
        <i class="fa fa-info" aria-hidden="true"></i> Ports
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('Shipping.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New
      </a>
    </h4>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Shippings</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Shipping Documents</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Certificates</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Others</a>
      </li>
    </ul>      
  </div>
  <div class="modal fade" id="price-update-modal" tabindex="-1" role="dialog" aria-labelledby="price-update-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-update2_492" action="{{ route('shipping.updateprice') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fs-5" id="adoncode">Add New Price Addon Code: <span id="addonId"></span></h5>
          <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3">
          <div class="col-lg-12">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="choices-single-default" class="form-label"><strong>Current Selling Price:</strong></label>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-12">
              <span id="currentPrice"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 col-md-12 col-sm-12">
                <label class="form-label font-size-13 text-center"><strong>New Price</strong></label>
              </div>
              <div class="col-lg-8 col-md-12 col-sm-12">
                <input type="text" name="price" />
                <input type="hidden" name="ids" id="hiddenId">
                <input type="hidden" name="tableid" id="tableid">
              </div>
              <span id="b_error_492" class="error required-class paragraph-class" style="color:#fd625e; font-size:13px;"></span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="submit_b_492" class="btn btn-primary btn-sm createAddonId">Submit</button>
        </div>
      </div>
    </form>
  </div>
</div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                  <th>Addon Code No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>View</th>
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
                  <th>Addon Code No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Action</th>
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
                  <th>Addon Code No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Action</th>
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
                  <th>Addon Code No</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price</th>
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
            ajax: "{{ route('Shipping.index', ['status' => 'Shipping']) }}",
            columns: [
              { data: 'code', name: 'shipping_medium.code' },
                { data: 'name', name: 'shipping_medium.name' },
                { data: 'description', name: 'shipping_medium.description' },
                {
    data: null,
    render: function (data) {
        var editRoute = "{{ route('shipping_medium.openmedium', ':id') }}";
        editRoute = editRoute.replace(':id', data.id);
        return `<a href="${editRoute}" class="btn btn-info btn-sm">
        <i class="fa fa-arrow-circle-right"></i>
                </a>`;
    }
}
            ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Shipping.index', ['status' => 'Shipping_document']) }}",
            columns: [
                {
                    data: 'id',
                    name: 'shipping_documents.id',
                    render: function (data) {
                        return 'DP-' + data.toString().padStart(3, '0');
                    }
                },
                { data: 'name', name: 'shipping_documents.name' },
                { data: 'description', name: 'shipping_documents.description' },
                { data: 'price', name: 'shipping_documents.price' },
                {
            data: null,
            render: function (data) {
                return `<button class="btn btn-warning price-update" data-id="${data.id}" data-table-id="dtBasicExample2" data-current-price="${data.price}">
                            <i class="fa fa-plus"></i>
                        </button>`;
            }
        }
            ]
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Shipping.index', ['status' => 'certification']) }}",
            columns: [
                {
                    data: 'id',
                    name: 'shipping_certification.id',
                    render: function (data) {
                        return 'D-' + data.toString().padStart(3, '0');
                    }
                },
                { data: 'name', name: 'shipping_certification.name' },
                { data: 'description', name: 'shipping_certification.description' },
                { data: 'price', name: 'shipping_certification.price' },
                {
            data: null,
            render: function (data) {
                return `<button class="btn btn-warning price-update" data-id="${data.id}" data-table-id="dtBasicExample3" data-current-price="${data.price}">
                            <i class="fa fa-plus"></i>
                        </button>`;
            }
        }
            ]
        });
        $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Shipping.index', ['status' => 'others']) }}",
            columns: [
                {
                    data: 'id',
                    name: 'other_logistics_charges.id',
                    render: function (data) {
                        return 'E-' + data.toString().padStart(3, '0');
                    }
                },
                { data: 'name', name: 'other_logistics_charges.name' },
                { data: 'description', name: 'other_logistics_charges.description' },
                { data: 'price', name: 'other_logistics_charges.price' },
                {
            data: null,
            render: function (data) {
                return `<button class="btn btn-warning price-update" data-id="${data.id}" data-table-id="dtBasicExample4" data-current-price="${data.price}">
                            <i class="fa fa-plus"></i>
                        </button>`;
            }
        }
            ]
        });
});
$(document).on('click', '.price-update', function () {
  var id = $(this).data('id').toString().padStart(3, '0');
  var tableId = $(this).data('table-id');
  if(tableId == "dtBasicExample1"){
    $('#addonId').text('S-' + id);
  }
  else if (tableId == "dtBasicExample2")
  {
    $('#addonId').text('DP-' + id);
  }
  else if (tableId == "dtBasicExample3")
  {
    $('#addonId').text('D-' + id);
  }
  else{
    $('#addonId').text('E-' + id);
  }
    var hiddenId = $(this).data('id');
    $('#hiddenId').val(hiddenId);
    $('#tableid').val(tableId);
    var currentPrice = $(this).data('current-price');
    $('#currentPrice').text(currentPrice);
    $('#price-update-modal').modal('show');
});
    </script>
    <script>
$(document).ready(function () {
    $('#form-update2_492').submit(function (event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#price-update-modal').modal('hide');
                if(response.tableid == "dtBasicExample1"){
                  alertify.success('Shipping Price Updated');
  }
  else if(response.tableid == "dtBasicExample2"){
                  alertify.success('Shipping Documents Price Updated');
  }
  else if(response.tableid == "dtBasicExample3"){
                  alertify.success('Certificates Price Updated');
  }
  else {
                  alertify.success('Other Addons Price Updated');
  }
        setTimeout(function() {
            window.location.reload();
        }, 500);
            },
            error: function (error) {
                console.error(error);
            }
        });
    });
});
</script>
@endsection