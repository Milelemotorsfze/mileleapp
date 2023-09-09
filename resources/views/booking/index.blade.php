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
      z-index: 1; /* Ensure the table header is on top of other elements */
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
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-view');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
     Booking Info
     <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </h4>
    <br>
    @can('sales-view')
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Pending Approvals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Active Without SO</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">Active With SO</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab4">Expired</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab5">Rejected Booking</a>
      </li>
    </ul>      
  </div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Approved and Rejected Booking ID: <span id="modalIdValue"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="days" class="form-label">Days:</label>
                        <select class="form-select" id="days" name="days">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" id="apstatus" name="apstatus">
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div id="currentDateRow1">Current Date: </div>
                    <div id="futureDateRow2">Future Date: </div>
                    <div id="reasonInput" style="display: none;">
                        <label for="reason" class="form-label">Reason for Rejection:</label>
                        <input type="text" class="form-control" id="reason" name="reason">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveData()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModalws" tabindex="-1" role="dialog" aria-labelledby="editModalwsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalwsLabel">Approved and Rejected Booking ID: <span id="modalIdValuews"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="mb-3">
                        <label for="days" class="form-label">Extended Days:</label>
                        <select class="form-select" id="days" name="days">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" id="apstatus" name="apstatus">
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveData()">Save changes</button>
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
                  <th>Booking ID</th>
                  <th>SO Number</th>
                  <th>Lead ID</th>
                  <th>Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                  <th>Days</th>
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
    @can('sales-view')
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Booking ID</th>
                  <th>Lead ID</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                  <th>Start Date</th>
                  <th>End Date</th>
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
      @can('sales-view')
      <div class="tab-pane fade show" id="tab3">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Booking ID</th>
                  <th>SO Number</th>
                  <th>Lead ID</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('sales-view')
      <div class="tab-pane fade show" id="tab4">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample4" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Booking ID</th>
                  <th>SO Number</th>
                  <th>Lead ID</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      @can('sales-view')
      <div class="tab-pane fade show" id="tab5">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample5" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Booking ID</th>
                  <th>SO Number</th>
                  <th>Lead ID</th>
                  <th>Date</th>
                  <th>VIN</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Variant Name</th>
                  <th>Variant Detail</th>
                  <th>Interior Color</th>
                  <th>Exterior Color</th>
                  <th>Days</th>
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
<!-- Modal for Editing Data -->
  <script>
        $(document).ready(function () {
            const editModal = $('#editModal'); // Reference to the modal
            const editModalws = $('#editModalws'); // Reference to the modal
        $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('booking.index', ['status' => 'New']) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'so_number', name: 'so_number' },
                { data: 'calls_id', name: 'calls_id' },
                { data: 'date', name: 'date' },
                { data: 'vin', name: 'vin' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'model_line', name: 'model_line' },
                { data: 'variant', name: 'variant' },
                { data: 'variant_details', name: 'variant_details' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'days', name: 'days' },
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row) {
                        return `
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${data}" data-days="${row.days}">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>`;
                }
                },
            ]
        });
        $('#dtBasicExample2').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('booking.index', ['status' => 'Approved Without SO']) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'calls_id', name: 'calls_id' },
                { data: 'vin', name: 'vin' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'model_line', name: 'model_line' },
                { data: 'variant', name: 'variant' },
                { data: 'variant_details', name: 'variant_details' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'booking_start_date', name: 'booking_start_date' },
                { data: 'booking_end_date', name: 'booking_end_date' },
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row) {
                        return `
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModalws" data-id="${data}">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>`;
                }
                },
            ]
        });
        $('#dtBasicExample3').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('booking.index', ['status' => 'Approved With SO']) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'so_number', name: 'so_number' },
                { data: 'calls_id', name: 'calls_id' },
                { data: 'vin', name: 'vin' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'model_line', name: 'model_line' },
                { data: 'variant', name: 'variant' },
                { data: 'variant_details', name: 'variant_details' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'booking_start_date', name: 'booking_start_date' },
                { data: 'booking_end_date', name: 'booking_end_date' },
            ]
        });
        $('#dtBasicExample4').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('booking.index', ['status' => 'Expire']) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'so_number', name: 'so_number' },
                { data: 'calls_id', name: 'calls_id' },
                { data: 'vin', name: 'vin' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'model_line', name: 'model_line' },
                { data: 'variant', name: 'variant' },
                { data: 'variant_details', name: 'variant_details' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'booking_start_date', name: 'booking_start_date' },
                { data: 'booking_end_date', name: 'booking_end_date' },
            ]
        });
        $('#dtBasicExample5').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('booking.index', ['status' => 'Rejected']) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'so_number', name: 'so_number' },
                { data: 'calls_id', name: 'calls_id' },
                { data: 'date', name: 'date' },
                { data: 'vin', name: 'vin' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'model_line', name: 'model_line' },
                { data: 'variant', name: 'variant' },
                { data: 'variant_details', name: 'variant_details' },
                { data: 'interior_color', name: 'interior_color' },
                { data: 'exterior_color', name: 'exterior_color' },
                { data: 'days', name: 'days' },
            ]
        });
    // // Event handler for when the modal is about to be shown
    // editModal.on('show.bs.modal', function (event) {
    //     const button = $(event.relatedTarget);
    //     const id = button.data('id');
    //     const days = button.data('days');

    //     // Populate the modal with the id and days values
    //     $('#modalIdValue').text(id);
    //     $('#days').val(days); // Set the selected value in the dropdown
    // });
        // Event handler for when the modal is about to be shown
        editModalws.on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');

        // Populate the modal with the id and days values
        $('#modalIdValuews').text(id);
    });
    $('#saveButton').on('click', function () {
        saveData(); // Call the saveData function to handle the AJAX request
    });
});
function saveData() {
    const id = $('#modalIdValue').text();
    const days = $('#days').val(); // Get the selected value from the dropdown
    const status = $('#apstatus').val();

    // Create an object to send as data in the AJAX request
    const data = {
        id: id,
        days: days,
        status: status
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // Make an AJAX POST request to your controller
    $.ajax({
        url: "{{ route('booking.approval') }}",
        method: 'POST',
        data: data,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
            $('#editModal').modal('hide');
            alertify.success('Booking Update successfully');
        setTimeout(function() {
            window.location.reload();
        }, 2000);
        },
        error: function (error) {
            console.error(error);
        }
    });
}
$(document).ready(function () {
    $('#editModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');
        const days = button.data('days');
        $('#modalIdValue').text(id);
        $('#days').val(days);
        updateDateValues(days);
    });
    $('#days').on('change', function () {
        const days = parseInt($(this).val());
        updateDateValues(days);
    });
    function updateDateValues(days) {
        const currentDate = new Date();
        const futureDate = new Date(currentDate);
        futureDate.setDate(currentDate.getDate() + days);
        const currentDateFormatted = formatDate(currentDate);
        const futureDateFormatted = formatDate(futureDate);
        $('#currentDateRow1').text('Booking Start Date: ' + currentDateFormatted);
        $('#futureDateRow2').text('Booking Ending Date: ' + futureDateFormatted);
    }
    $('#apstatus').on('change', function () {
        const status = $(this).val();
        updateFieldsBasedOnStatus(status);
    });
    function updateFieldsBasedOnStatus(status) {
        if (status === 'Rejected') {
            $('#currentDateRow1').hide();
            $('#futureDateRow2').hide();
            $('#reasonInput').show();
        } else {
            $('#currentDateRow1').show();
            $('#futureDateRow2').show();
            $('#reasonInput').hide();
        }
    }
    function formatDate(date) {
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'short' });
        const year = date.getFullYear();
        return `${day} - ${month} - ${year}`;
    }
});
    </script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection