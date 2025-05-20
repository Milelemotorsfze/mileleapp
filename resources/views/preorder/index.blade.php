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
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('pre-order-processing');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <h4 class="card-title">
     Pre Order Info
    </h4>
    <br>
    @can('pre-order-processing')
    <ul class="nav nav-pills nav-fill">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Requests
        <span class="badge badge-danger row-badge2 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Under Process 
        <span class="badge badge-danger row-badge1 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Completed
        <span class="badge badge-danger row-badge3 badge-notification"></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Rejected
        <span class="badge badge-danger row-badge4 badge-notification"></span>
        </a>
      </li>
    </ul>      
  </div>
  <div class="modal fade" id="processingmodel" tabindex="-1" aria-labelledby="processingmodelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="processingmodelLabel">Processing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <input type="text" id="Preorder_id_input" name="pre_orders_items" class="form-control">
        <table class="table">
            <thead>
              <tr>
                <th>PO Number</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="vinTableBody">
            </tbody>
          </table>
          <div class="mb-3">
            <label for="poInput" class="form-label">Po Numbers</label>
            <select id="po-dropdown" class="form-control">
                </select>
                </div>
          <button type="button" class="btn btn-primary btn-sm" onclick="addpoRow()">Add</button>
          </br>
          </br>
          
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="sales-notes" class="form-label">Notes:</label>
            </div>
            <div class="col-md-8">
              <textarea class="form-control" id="notes"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="savepolist()">Save Changes</button>
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
                <th>Pre Order Number</th>
                <th>Booking Date</th>
                  <th>SO Number</th>
                  <th>Sales Person</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Year</th>
                  <th>Selling Price</th>
                  <th>Interior Colour</th>
                  <th>Exterior Colour</th>
                  <th>Qty</th>
                  <th>Teritory</th>
                  <th>Final Dest</th>
                  <th>Details</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      @endcan
      </div>
    </div>
  </div>
  <script>
$(document).ready(function() {
  function fetchPONumbers() {
    $.ajax({
      url: '/get-po-for-presale',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        $('#po-dropdown').empty();
        $('#po-dropdown').append('<option value="">Please Select the PO</option>');
        $.each(response, function(index, poNumber) {
          $('#po-dropdown').append($('<option></option>').text(poNumber));
        });
        $('#po-dropdown').select2({
          dropdownCssClass: "my-select2-dropdown"
      }).on('select2:open', function (e) {
          $('.my-select2-dropdown').css('z-index', 99999);
      });
      },
      error: function(xhr, status, error) {
        console.error('Error fetching PO numbers:', error);
      }
    });
  }
  $('#processingmodel').on('shown.bs.modal', function() {
    fetchPONumbers();
  });
});
function addpoRow() {
  var selectedPoNumber = $('#po-dropdown').val();
  if(selectedPoNumber) {
    $('#vinTableBody').append('<tr><td>' + selectedPoNumber + '</td><td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td></tr>');
    $('#po-dropdown').val(null).trigger('change');
  } else {
    alert('Please select a PO number.');
  }
}

// Function to remove row from table
function removeRow(button) {
  $(button).closest('tr').remove();
}
// Function to save PO list changes
function savepolist() {
  // Array to store all selected PO numbers
  var selectedPOs = [];
  // Collect all selected PO numbers from the table
  $('#vinTableBody tr').each(function() {
    selectedPOs.push($(this).find('td:first').text());
  });
  // Get the notes
  var notes = $('#notes').val();
  var Preorder_id_input = $('#Preorder_id_input').val();
  // Create a data object to send to the backend
  var data = {
    po_numbers: selectedPOs,
    Preorder_id_input: Preorder_id_input,
    notes: notes
  };
  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  // Send an AJAX request to the backend to save changes
  $.ajax({
    url: '/save-po-list-preorder',
    type: 'POST',
    dataType: 'json',
    data: data,
    headers: {
      'X-CSRF-TOKEN': csrfToken
    },
    success: function(response) {
    
        alertify.success('PO Adding Preorder successfully');
        $('#processingmodel').modal('hide');
        setTimeout(function() {
          window.location.reload();
        }, 1000);
    },
    error: function(xhr, status, error) {
      // Handle error response from the backend
      console.error('Error saving changes:', error);
      // Optionally, display an error message to the user
    }
  });
}
    function openModalp(PreorderID) {
  $('#Preorder_id_input').val(PreorderID);
  $('#processingmodel').modal('show');
}
        $(document).ready(function () {
          var table1 =  $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('preorder.index', ['status' => 'Pending']) }}",
            columns: [
              { data: 'pre_order_number', name: 'pre_order_number' },
              { data: 'so_number', name: 'so.so_number' },
              { data: 'so_number', name: 'so.so_number' },
              { data: 'salesperson', name: 'salesperson' },
              { data: 'brand_name', name: 'brands.brand_name' },
              { data: 'model_line', name: 'master_model_lines.model_line' },
              { data: 'modelyear', name: 'pre_orders_items.modelyear' },
              { data: 'modelyear', name: 'pre_orders_items.modelyear' },
              { data: 'exterior', name: 'exterior' },
              { data: 'interior', name: 'interior' },
              { data: 'qty', name: 'pre_orders_items.qty' },
              { data: 'countryname', name: 'countryname' },
              { data: 'countryname', name: 'countryname' },
              { data: 'description', name: 'pre_orders_items.description' },
              {
                    data: 'id',
                    name: 'id',
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Update Preorder Processing">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" onclick="openModalp(${data})">Processing</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="openModalr(${data})">Rejected</a></li>
                                </ul>
                            </div>`;
                    }
                },
            ]
        });
        $('#dtBasicExample1').on('click', '.read-more a', function(e) {
    e.preventDefault();
    var rowData = table1.row($(this).closest('tr')).data();
    alert("Full text: " + rowData.detail);
});
        var table2 = $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: "{{ route('inspection.index', ['status' => 'Incoming']) }}",
            columns: [
                { data: 'po_date', name: 'purchasing_order.po_date' },
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { 
            data: 'detail', 
            name: 'varaints.detail',
            render: function(data, type, row) {
                if (type === 'display' && data.length > 50) {
                    return data.substr(0, 50) + '<span class="read-more">... <a href="#">Read More</a></span>';
                } else {
                    return data;
                }
            }
        },
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
            drawCallback: function(settings) {
        var api = this.api();
        console.log(api.rows().data().toArray());
    }
        });
        $('#dtBasicExample2').on('click', '.read-more a', function(e) {
    e.preventDefault();
    var rowData = table2.row($(this).closest('tr')).data();
    // You can handle the "read more" action here, e.g., show a modal with full text.
    alert("Full text: " + rowData.detail);
});
        table2.on('draw', function () {
            var rowCount = table2.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge2').text(rowCount).show();
            } else {
                $('.row-badge2').hide();
            }
        });
        var table3 = $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'stock']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'movement_grns.grn_number' },
                { data: 'processing_date', name: 'inspection.processing_date' },
                { data: 'process_remarks', name: 'inspection.process_remarks' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { 
            data: 'detail', 
            name: 'varaints.detail',
            render: function(data, type, row) {
                if (type === 'display' && data.length > 50) {
                    return data.substr(0, 50) + '<span class="read-more">... <a href="#">Read More</a></span>';
                } else {
                    return data;
                }
            }
        },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample3').on('click', '.read-more a', function(e) {
    e.preventDefault();
    var rowData = table3.row($(this).closest('tr')).data();
    // You can handle the "read more" action here, e.g., show a modal with full text.
    alert("Full text: " + rowData.detail);
});
        table3.on('draw', function () {
            var rowCount = table3.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge3').text(rowCount).show();
            } else {
                $('.row-badge3').hide();
            }
        });
        var table4 = $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'Pending PDI']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'movement_grns.grn_number' },
                { data: 'inspection_date', name: 'vehicles.inspection_date' },
                { data: 'grn_remark', name: 'vehicles.grn_remark' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { 
            data: 'detail', 
            name: 'varaints.detail',
            render: function(data, type, row) {
                if (type === 'display' && data.length > 50) {
                    return data.substr(0, 50) + '<span class="read-more">... <a href="#">Read More</a></span>';
                } else {
                    return data;
                }
            }
        },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample4').on('click', '.read-more a', function(e) {
    e.preventDefault();
    var rowData = table4.row($(this).closest('tr')).data();
    // You can handle the "read more" action here, e.g., show a modal with full text.
    alert("Full text: " + rowData.detail);
});
        table4.on('draw', function () {
            var rowCount = table4.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge4').text(rowCount).show();
            } else {
                $('.row-badge4').hide();
            }
        });
        var table5 = $('#dtBasicExample5').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'Pending Re Inspection']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'movement_grns.grn_number' },
                { data: 'created_ats', name: 'inspection.created_at' },
                { data: 'inspectionremark', name: 'inspection.remark' },  
                { data: 'processing_date', name: 'inspection.processing_date' },
                { data: 'process_remarks', name: 'inspection.process_remarks' }, 
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { 
            data: 'detail', 
            name: 'varaints.detail',
            render: function(data, type, row) {
                if (type === 'display' && data.length > 50) {
                    return data.substr(0, 50) + '<span class="read-more">... <a href="#">Read More</a></span>';
                } else {
                    return data;
                }
            }
        },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'seat', name: 'varaints.seat' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'gearbox', name: 'varaints.gearbox' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'ppmmyyy', name: 'vehicles.ppmmyyy' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample5').on('click', '.read-more a', function(e) {
    e.preventDefault();
    var rowData = table5.row($(this).closest('tr')).data();
    // You can handle the "read more" action here, e.g., show a modal with full text.
    alert("Full text: " + rowData.detail);
});
        table5.on('draw', function () {
            var rowCount = table5.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge5').text(rowCount).show();
            } else {
                $('.row-badge5').hide();
            }
        });
        var table6 =  $('#dtBasicExample6').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspection.index', ['status' => 'Spec Re Inspection']) }}",
            columns: [
                { data: 'po_number', name: 'purchasing_order.po_number' },
                { data: 'grn_number', name: 'movement_grns.grn_number' },
                { data: 'so_date', name: 'so.so_date' },
                { data: 'so_number', name: 'so.so_number' },
                { data: 'location', name: 'warehouse.name' },
                { data: 'vin', name: 'vehicles.vin' },
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { data: 'model_detail', name: 'varaints.model_detail' },
                { data: 'variant', name: 'varaints.name' },
                { 
            data: 'detail', 
            name: 'varaints.detail',
            render: function(data, type, row) {
                if (type === 'display' && data.length > 50) {
                    return data.substr(0, 50) + '<span class="read-more">... <a href="#">Read More</a></span>';
                } else {
                    return data;
                }
            }
        },
                { data: 'my', name: 'varaints.my' },
                { data: 'steering', name: 'varaints.steering' },
                { data: 'fuel_type', name: 'varaints.fuel_type' },
                { data: 'upholestry', name: 'varaints.upholestry' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
            ]
        });
        $('#dtBasicExample6').on('click', '.read-more a', function(e) {
    e.preventDefault();
    var rowData = table6.row($(this).closest('tr')).data();
    // You can handle the "read more" action here, e.g., show a modal with full text.
    alert("Full text: " + rowData.detail);
});
        table6.on('draw', function () {
            var rowCount = table6.page.info().recordsDisplay;
            if (rowCount > 0) {
                $('.row-badge6').text(rowCount).show();
            } else {
                $('.row-badge6').hide();
            }
        });
});
    </script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample1').DataTable();
    $('#dtBasicExample1 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('inspection.show', ['inspection' => 'id']) }}";
      url = url.replace('id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
$(document).ready(function () {
  var table = $('#dtBasicExample3').DataTable();
  $('#dtBasicExample3 tbody').on('dblclick', 'tr', function () {
    var data = table.row(this).data();
    var vehicleId = data.id;
    var url = "{{ route('inspection.instock', ['id' => ':vehicleId']) }}"; // Note the change here
    url = url.replace(':vehicleId', vehicleId); // Replace :vehicleId with the actual vehicleId
    window.location.href = url;
  });
});
</script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample5').DataTable();
    $('#dtBasicExample5 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('reinspection.reshow', ['id' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample4').DataTable();
    $('#dtBasicExample4 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      var url = "{{ route('inspection.pdiinspection', ['id' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
  $(document).ready(function () {
    var table = $('#dtBasicExample6').DataTable();
    $('#dtBasicExample6 tbody').on('dblclick', 'tr', function () {
      var data = table.row(this).data();
      var vehicleId = data.id;
      console.log(vehicleId);
      var url = "{{ route('inspection.reinspectionspec', ['id' => ':id']) }}";
      url = url.replace(':id', vehicleId);
      window.location.href = url;
    });
  });
</script>
<script>
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 20000);
    }
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection