@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Ensure table rows do not wrap text */
table.dataTable {
    font-size: 12px; /* Decrease font size */
    white-space: nowrap; /* Prevent text from wrapping into multiple lines */
}
/* Reduce padding for table cells */
.table>tbody>tr>td, 
.table>tbody>tr>th, 
.table>tfoot>tr>td, 
.table>tfoot>tr>th, 
.table>thead>tr>td, 
.table>thead>tr>th {
    padding: 2px 3px; /* Decrease the padding */
    text-align: center;
    vertical-align: middle;
    white-space: nowrap; /* Prevent text from wrapping */
}
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td
{
   padding: 1px; 
}
/* Reduce the height of the rows */
#dtBasicExample7 tbody tr {
    height: 20px; /* Set a smaller height for the rows */
}

/* Adjust the header row to reduce space */
#dtBasicExample7 thead th {
    padding: 4px 5px; /* Reduce padding in the header */
    font-size: 13px;  /* Slightly reduce the font size in the header */
    white-space: nowrap; /* Prevent header text from wrapping */
}
.table-responsive {
    overflow-x: auto; /* Enable horizontal scrolling if content overflows */
    white-space: nowrap; /* Prevent text wrapping in table cells */
}

/* Ensure the table container takes the full height available */
.table-responsive {
    height: 80vh;
    overflow-y: auto;
}
   .btn-outline-primary {
    margin-bottom: 5px;
    width: 100%;
}
.select2-container--default .select2-search--inline .select2-search__field {
    font-size: 12px !important; /* Adjust the font-size as per your needs */
    width: 100% !important;
}
/* Ensure the Select2 dropdown fits the column width */
table.dataTable thead th select {
    width: 100% !important; /* Ensures the select element fits the header width */
    min-width: 100%; /* Ensures it takes at least 100% width */
}

/* Ensure the Select2 dropdown fits the full header width when opened */
.select2-container {
    width: 100% !important; /* Ensures the container takes full width */
}

/* Ensure the dropdown itself is properly styled */
.select2-dropdown {
    width: auto !important; /* Let the dropdown size adjust dynamically */
    min-width: 100%; /* Ensure the dropdown is at least the width of the select element */
    box-sizing: border-box; /* Makes sure the padding is included in width */
}

/* Ensure proper spacing between dropdown options */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #0056b3;
    padding: 0 5px;
    margin: 3px 5px 3px 0;
    color: black;
    font-size: 12px;
}

/* Highlight the selected items */
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff;
    color: white;
}
.dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    margin-left: -50px;
    margin-top: -30px;
    background: url('https://logosbynick.com/wp-content/uploads/2021/01/animated-gif.gif') no-repeat center center;
    background-size: contain;
    z-index: 1100; /* Higher than the z-index of the <thead> */
    display: none;
}
#dtBasicExample3_processing {
    display: block;
}
#toggleButtonsRow th {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 4px;
    text-align: center;
}

.d-flex.mb-2 {
    margin-bottom: 10px;
}

#dtBasicExample3_wrapper .btn-danger,
#dtBasicExample3_wrapper .btn-success {
    margin-right: 10px;
}
#dtBasicExample6_wrapper .btn-danger,
#dtBasicExample6_wrapper .btn-success {
    margin-right: 10px;
}
#dtBasicExample7_wrapper .btn-danger,
#dtBasicExample7_wrapper .btn-success {
    margin-right: 10px;
}
#dtBasicExample8_wrapper .btn-danger,
#dtBasicExample8_wrapper .btn-success {
    margin-right: 10px;
}
  .text-container {
        display: inline-block; /* Inline block to handle overflow */
        max-width: 300px; /* Adjust this width as needed */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .read-more-btn {
        color: blue;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: underline;
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
        white-space: nowrap; /* Prevent text from wrapping into two lines */
    text-overflow: ellipsis; /* Show ellipsis ("...") if the text overflows */
    overflow: hidden; /* Hide overflowed text */
    width: auto; /* Allow the header to take up available width */
    padding: 8px; /* Adjust padding for better alignment */
    vertical-align: middle; /* Vertically align the text in the middle */
    background-color: rgb(194, 196, 204)!important; /* Ensure sticky header color */
    position: sticky!important; /* Sticky header */
    top: 0;
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
    .comments-header {
    position: sticky;
    top: 0;
    background-color: #fff;
    z-index: 10;
    border-bottom: 1px solid #dee2e6;
}

.fixed-height {
    height: 280px; /* Adjust the height as needed */
    overflow-y: auto;
    border: 1px solid #dee2e6;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

.message-card, .message-reply {
    margin-bottom: 1rem;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}

.message-card .card-body, .message-reply {
    padding: 1rem;
}

.message-reply {
    margin-left: 3rem;
    margin-top: 0.5rem;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.5rem;
}

.reply-input {
    margin-left: 3rem;
    margin-top: 0.5rem;
    position: relative;
}

.avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    font-size: 0.9rem;
}

.avatar-small {
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-name {
    margin-left: 5px;
}

.send-icon {
    position: absolute;
    right: 1px;
    bottom: 2px;
    border: none;
    background: none;
    font-size: 0.1rem;
    color: #28a745;
    cursor: pointer;
}

.send-icongt {
    position: absolute;
    right: 1px;
    bottom: 1px;
    border: none;
    background: none;
    font-size: 0.01rem;
    color: #28a745;
    cursor: pointer;
}
.message-input-wrapper, .reply-input-wrapper {
    position: relative;
}

.message-input-wrapper textarea, .reply-input-wrapper textarea {
    padding-right: 40px;
    width: 100%;
    box-sizing: border-box;
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
     Incoming and Available Vehicles
    </h4>
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chatModalLabel">Comments Box</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-md-12">
          <div class="comments-header">
            <h6>Comments & Remarks</h6>
          </div>
          <div id="messages" class="fixed-height"></div>
          <div class="message-input-wrapper mb-3">
            <textarea id="message" class="form-control main-message" placeholder="Type a message..." rows="1"></textarea>
            <button id="send-message" class="btn btn-success send-icon">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="remarksForm" method="POST" action="{{ route('vehicles.savesalesremarks') }}">
                @csrf
                <input type="hidden" name="vehicle_remarks_id" id="vehicle_remarks_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">Sales Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="salesremarks" name="salesremarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="bookingForm" method="POST" action="{{ route('booking.savedirectly') }}">
                @csrf
                <input type="hidden" name="vehicle_id" id="vehicle_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                    <button type="button" style="margin-left: 10px;" class="btn btn-danger" id="cancelBookingButton">Cancel Booking</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="booking_start_date">Booking Start Date</label>
                        <input type="date" class="form-control" id="booking_start_date" name="booking_start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="booking_end_date">Booking End Date</label>
                        <input type="date" class="form-control" id="booking_end_date" name="booking_end_date" required>
                    </div>
                    <div class="form-group">
                        <label for="user_id">Sales Person</label>
                        <select class="form-control" id="salesperson" name="salesperson" required>
                        <option value="" disabled selected>Select the Sales Person</option>
                        @foreach($salesperson as $salesperson)
                                <option value="{{ $salesperson->id }}">{{ $salesperson->name }}</option>
                            @endforeach
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <div class="modal fade" id="enhancementModal" tabindex="-1" role="dialog" aria-labelledby="enhancementModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="enhancementForm" method="POST" action="{{ route('enhancement.save') }}">
                @csrf
                <input type="hidden" name="vehicle_id" id="vehicle_idenchacment">
                <div class="modal-header">
                    <h5 class="modal-title" id="enhancementModalLabel">Enhancement Details - <span id="vehicleVin"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="booking_start_date">Update Variant</label>
                        <select class="form-control" id="variantSelect" name="variant_id" required>
        <!-- Options will be populated by JavaScript -->
    </select>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Enhancement</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Structure -->
<div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="colorModalLabel">Edit Colors - <span id="vehicleVincolor"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editColorForm" method="POST" action="{{ route('enhancement.savecolour') }}">
                @csrf
                <input type="hidden" name="vehicle_id" id="vehicle_colour">
                    <div class="form-group">
                        <label for="int_color_dropdown">Interior Color</label>
                        <select name = "int_color_dropdown" id="int_color_dropdown" class="form-control">
                            <!-- Options will be populated by AJAX -->
                        </select>
                    </div>
</br>
                    <div class="form-group">
                        <label for="ext_color_dropdown">Exterior Color</label>
                        <select name = "ext_color_dropdown" id="ext_color_dropdown" class="form-control">
                            <!-- Options will be populated by AJAX -->
                        </select>
                    </div>
</br>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="custominspectionModal" tabindex="-1" role="dialog" aria-labelledby="custominspectionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="custominspectionForm" method="POST" action="{{ route('vehicles.savecustominspection') }}">
                @csrf
                <input type="hidden" name="vehicle_id" id="vehicle_idinspection">
                <div class="modal-header">
                    <h5 class="modal-title" id="custominspectionModalLabel">Custom Inspection Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="booking_start_date">Custom Inspection Number</label>
                        <input type="number" class="form-control" id="custom_inspection_number" name="custom_inspection_number" required>
                    </div>
                    <div class="form-group">
                        <label for="custom_inspection_status">Custom Inspection Status</label>
                        <select class="form-control" id="custom_inspection_status" name="custom_inspection_status" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="start">Start</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="imageModalLabel">Vehicle Pictures</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner" id="carouselImages">
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="noImageModal" tabindex="-1" role="dialog" aria-labelledby="noImageModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noImageModalLabel">No Images Available</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        No images are available on the website for this vehicle.
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>     
  @php
    $hasPricePermission = Auth::user()->hasPermissionForSelectedRole('selling-price-stock-report-view');
    $hasManagementPermission = Auth::user()->hasPermissionForSelectedRole('cost-price-link-stock-report');
@endphp

<script>
    var hasPricePermission = @json($hasPricePermission);
    var hasManagementPermission = @json($hasManagementPermission);
</script>
        <div class="card-body">
        @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-export-option');
      @endphp
      @if ($hasPermission)
        <button type="button" class="btn btn-success" onclick="exportToExcel('dtBasicExample3')">
  <i class="bi bi-file-earmark-excel"></i> Export to Excel
</button>
@endif
<div class="table-responsive" style="height: 80vh;">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary" style="position: sticky; top: 0;">
            <tr id="toggleButtonsRow3">
                <!-- Toggle buttons will be added here dynamically -->
            </tr>
            <tr>
            <th>Ser No</th>
            <th>Status</th>
            <th>PO</th>
                  <th>PO Date</th>
                  <th>Estimated Arrival</th>
                  <th>GRN</th>
                  <th>GRN Date</th>
                  <th>Inspection Date</th>
                  <th>Inspection Remarks</th>
                  <th>GRN Report</th>
                  <th>Aging</th>
                  <th>Reservation End</th>
                  <th>Reservation Sales Person</th>
                  <th>SO Date</th>
                  <th>So Number</th>
                  <th>Sales Person</th>
                  <th>Sales Remarks</th>
                  <th>PDI Report</th>
                  <th>Brand</th>
                  <th>Model Line</th>
                  <th>Model Description</th>
                  <th>Variant</th>
                  <th>Variant Detail</th>
                  <th>VIN</th>
                  <th>Engine</th>
                  <th>MY</th>
                  <th>Steering</th>
                  <th>Fuel</th>
                  <th>Gear</th>
                  <th>Ext Colour</th>
                  <th>Int Colour</th>
                  <th>Upholstery</th>
                  <th>Production Year</th>
                  <th>Location</th>
                  <th>Territory</th>
                  <th>Preferred Destination</th>
                  @if ($hasManagementPermission)
                  <th>Vehicle Cost</th>
                  @endif
                  @if ($hasPricePermission)
                     <th>Minimum Commission</th>
                     <th>GP %</th>
                    <th>Price</th>
                @endif
                  <th>Import Type</th>
                  <th>Owership</th>
                  <th>Document With</th>
                  <th>Custom Inspection Number</th>
                  <th>Custom Inspection Status</th>
                  <th>Comments</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
      <div class="modal fade" id="variantview" tabindex="-1" aria-labelledby="variantviewLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="variantviewLabel">View Variants</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
      </div>
    </div>
  </div>
  <script>
        $(document).ready(function () {
        var now = new Date();
    var columns3 = [
        {
        data: null,
        name: 'serial_number',
        render: function (data, type, row, meta) {
            return meta.row + 1;  // This will calculate the serial number based on the row index (meta.row) + 1
        },
        orderable: false,  // Disable ordering for this column
        searchable: false  // Disable searching for this column
    },
        { data: 'id', name: 'vehicles.id' },
        { data: 'po_number', name: 'purchasing_order.po_number' },
        {
    data: 'po_date',
    name: 'purchasing_order.po_date',
    render: function(data, type, row) {
        if (data) {
            // Assuming data is in Y-m-d format (default SQL date format)
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            return formattedDate;
        }
        return ''; // If no date, return empty
    }
},
{
    data: 'estimation_date',
    name: 'vehicles.estimation_date',
    render: function(data, type, row) {
        if (data) {
            // Assuming data is in Y-m-d format (default SQL date format)
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            return formattedDate;
        }
        return ''; // If no date, return empty
    }
},
        { data: 'grn_number', name: 'grn.grn_number' },
        {
    data: 'date',
    name: 'grn.date',
    render: function(data, type, row) {
        if (data) {
            // Assuming data is in Y-m-d format (default SQL date format)
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            return formattedDate;
        }
        return ''; // If no date, return empty
    }
},
        {
    data: 'inspection_date',
    name: 'vehicles.inspection_date',
    render: function(data, type, row) {
        if (data) {
            // Assuming data is in Y-m-d format (default SQL date format)
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            return formattedDate;
        }
        return ''; // If no date, return empty
    }
},
        { data: 'grn_remark', name: 'vehicles.grn_remark' },
        { 
            data: 'id', 
            name: 'id',
            render: function(data, type, row) {
                if (row.grn_inspectionid) {
                    return `<button class="btn btn-info" onclick="generatePDF(${data})">Generate PDF</button>`;
                } else {
                    return 'Not Available';
                }
            }
        },
        { 
            data: null,
            render: function(data, type, row) {
                var grnDate = new Date(row.date); // Assuming `row.date` is the GRN date
                var currentDate = new Date();
                var timeDiff = currentDate - grnDate;
                var daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24)); // Convert time difference to days

                return daysDiff + ' days';
            },
            searchable: false, // Disable searching for this column
            orderable: false // Disable ordering from the server-side for this column
        },
        {
    data: 'reservation_end_date',
    name: 'vehicles.reservation_end_date',
    render: function(data, type, row) {
        if (data) {
            // Assuming data is in Y-m-d format (default SQL date format)
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            return formattedDate;
        }
        return ''; // If no date, return empty
    }
},
        { data: 'bpn', name: 'bp.name' },
        {
    data: 'so_date',
    name: 'so.so_date',
    render: function(data, type, row) {
        if (data) {
            // Assuming data is in Y-m-d format (default SQL date format)
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                day: '2-digit', month: 'short', year: 'numeric'
            });
            return formattedDate;
        }
        return ''; // If no date, return empty
    }
},
        { data: 'so_number', name: 'so.so_number' },
        { data: 'spn', name: 'sp.name' },
        { 
        data: 'sales_remarks', 
        name: 'vehicles.sales_remarks',
        render: function(data, type, row) {
            return data ? data : '';
        }
    },
        { 
            data: 'id', 
            name: 'id',
            render: function(data, type, row) {
                if (row.pdi_inspectionid) {
                    return `<button class="btn btn-info" onclick="generatePDFpdi(${data})">Generate PDF</button>`;
                } else {
                    return 'Not Available';
                }
            }
        },
        { data: 'brand_name', name: 'brands.brand_name' },
        { data: 'model_line', name: 'master_model_lines.model_line' },
        { data: 'model_detail', name: 'varaints.model_detail' },
        { 
            data: 'variant', 
            name: 'varaints.name',
            render: function(data, type, row) {
                return '<a href="#" onclick="openModal(' + row.variant_id + ')" style="text-decoration: underline;">' + data + '</a>';
            }
        },
        {
            data: 'variant_detail',
            name: 'varaints.detail',
            render: function(data, type, row) {
                if (!data) {
                    return '';
                }
                var words = data.split(' ');
                var firstFiveWords = words.slice(0, 5).join(' ') + '...';
                var fullText = data;

                return `
                    <div class="text-container" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        ${firstFiveWords}
                    </div>
                    <button class="read-more-btn" data-fulltext="${fullText}" onclick="showFullText(this)">Read More</button>
                `;
            }
        },
        {
    data: 'vin',
    name: 'vehicles.vin',
    render: function(data, type, row) {
        if (data) {
            var url = 'https://milelemotors.sharepoint.com/:f:/r/sites/source/DMS/Warehouse%20%26%20Operations/VEHICLE%20PICTURES/' + data + '/GRN?csf=1&web=1&e=GPkael';
            return '<a href="' + url + '" target="_blank">' + data + '</a>';
        } else {
            return data;
        }
    }
},
        { data: 'engine', name: 'vehicles.engine', render: function(data, type, row) {
            return '<a href="#" onclick="fetchVehicleData(' + row.id + ')" style="text-decoration: underline;">' + (data ? data : '<i class="fas fa-image"></i>') + '</a>';
        }},
        { data: 'my', name: 'varaints.my' },
        { data: 'steering', name: 'varaints.steering' },
        { data: 'fuel_type', name: 'varaints.fuel_type' },
        { data: 'gearbox', name: 'varaints.gearbox' },
        { 
        data: 'exterior_color', 
        name: 'ex_color.name',
        render: function(data, type, row) {
            return data ? data : '';
        }
    },
    { 
        data: 'interior_color', 
        name: 'int_color.name',
        render: function(data, type, row) {
            return data ? data : '';
        }
    },
        { data: 'upholestry', name: 'varaints.upholestry' },
        {
    data: 'ppmmyyy',
    name: 'vehicles.ppmmyyy',
    render: function(data, type, row) {
        if (data) {
            var dateObj = new Date(data);
            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                year: 'numeric',
                month: 'long'
            });
            return formattedDate;  // Example: "January 2024"
        }
        return ''; // If no date, return empty
    }
},
        { data: 'location', name: 'warehouse.name' },
        { data: 'territory', name: 'vehicles.territory' },
        { data: 'fd', name: 'countries.name' },
    ];
    if (hasPricePermission) {
        if (hasManagementPermission) {
        columns3.push(
            {
    data: 'costprice',
    name: 'costprice',
    searchable: false,
    render: function(data, type, row) {
        if (data) {
            if (row.netsuite_link && hasManagementPermission) {
                return `<a href="${row.netsuite_link}" target="_blank" style="display: inline-block; background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">${data}</a>`;
            } else {
                return `<span style="display: inline-block; background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">${data}</span>`;
            }
        }
        return ''; // Return an empty string if there's no price
    }
});
    }

    columns3.push(
        {
            data: 'minimum_commission', 
            name: 'vehicles.minimum_commission', 
                    render: function(data, type, row) {
                        if (data) {
                            // Convert the string to a float, then format it with commas
                            var formattedminimum_commission = parseFloat(data).toLocaleString('en-US', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });

                            // Return the price wrapped in a span with button-like styling
                            return '<span style="display: inline-block; background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">' + formattedminimum_commission + '</span>';
                        }
                        return ''; // Return an empty string if there's no price
                    }
        },
            { data: 'gp', name: 'vehicles.gp' },
            {
            data: 'price', 
            name: 'vehicles.price', 
                    render: function(data, type, row) {
                        if (data) {
                            // Convert the string to a float, then format it with commas
                            var formattedPrice = parseFloat(data).toLocaleString('en-US', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });

                            // Return the price wrapped in a span with button-like styling
                            return '<span style="display: inline-block; background-color: #28a745; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold;">' + formattedPrice + '</span>';
                        }
                        return ''; // Return an empty string if there's no price
                    }
        });
    }
    columns3.push(
        { data: 'import_type', name: 'documents.import_type' },
        { data: 'owership', name: 'documents.owership' },
        { data: 'document_with', name: 'documents.document_with' },
        { 
        data: 'custom_inspection_number', 
        name: 'vehicles.custom_inspection_number',
        render: function(data, type, row) {
            return data ? data : '';
        }
    },
    { 
        data: 'custom_inspection_status', 
        name: 'vehicles.custom_inspection_status',
        render: function(data, type, row) {
            return data ? data : '';
        }
    },
        {
    data: null,
    name: 'chat',
    render: function(data, type, row) {
        const messageCount = row.message_count || 0; // message_count is now available
        const badgeHtml = messageCount > 0 ? `
            <span style="
                position: absolute;
                top: -10px;
                right: -10px;
                background-color: #cb3365;
                color: #fff;
                padding: 0.25em 0.5em;
                font-size: 75%;
                border-radius: 0.25rem;
                transform: translate(50%, -50%);
            ">
                ${messageCount}
            </span>` : '';
        const buttonClass = messageCount > 0 ? 'btn-warning' : 'btn-primary';

        return `
            <div style="position: relative; display: inline-block;">
                ${badgeHtml}
                <button class="btn ${buttonClass} btn-sm" onclick="openChatModal(${row.id})">
                    Comments
                </button>
            </div>
        `;
    },
    orderable: false,
    searchable: false
},
        );
        var columnMap = {
        0: 'id',
        1: 'vehicles.id',
        2: 'purchasing_order.po_number',
        3: 'purchasing_order.po_date',
        4: 'vehicles.estimation_date',
        5: 'grn.grn_number',
        6: 'grn.date',
        7: 'vehicles.inspection_date',
        8: 'vehicles.grn_remark',
        9: 'grn_inspectionid',
        10: 'vehicles.grn_remark',
        11: 'reservation_end_date',
        12: 'bp.name',
        13: 'so.so_date',
        14: 'so.so_number',
        15: 'sp.name',
        16: 'vehicles.sales_remarks',
        17: 'pdi_inspectionid',
        18: 'brands.brand_name',
        19: 'master_model_lines.model_line',
        20: 'varaints.model_detail',
        21: 'varaints.name',
        22: 'varaints.detail',
        23: 'vehicles.vin',
        24: 'varaints.engine',
        25: 'varaints.my',
        26: 'varaints.steering',
        27: 'varaints.fuel_type',
        28: 'varaints.gear',
        29: 'vehicles.ex_colour',
        30: 'vehicles.int_colour',
        31: 'varaints.upholestry',
        32: 'vehicles.ppmmyyy',
        33: 'warehouse.name',
        34: 'vehicles.territory',
        35: 'countries.name',
        36: 'costprice',
        37: 'vehicles.minimum_commission',
        38: 'vehicles.gp',
        39: 'vehicles.price',
        40: 'documents.import_type',
        41: 'documents.owership',
        42: 'documents.document_with',
        43: 'vehicles.custom_inspection_number',
        44: 'vehicles.custom_inspection_status',
    };
            var table3 = $('#dtBasicExample3').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('vehicles.availablevehicles', ['status' => 'Available Stock']) }}",
        type: "POST",
        data: function (d) {
                d.filters = {};  // Initialize an empty filters object

                $('#dtBasicExample3 thead select').each(function () {
                    var columnIndex = $(this).parent().index(); // Get the column index
                    var columnName = columnMap[columnIndex]; // Map index to column name
                    var value = $(this).val();
                        console.log(columnIndex);
                    // Send filter values using column names, including special `__NULL__` and `__Not EMPTY__`
                    if (value && columnName) {
                        if (value.includes('__NULL__') || value.includes('__Not EMPTY__')) {
                            d.filters[columnName] = value; // Special filters for NULL and non-empty
                        } else if (value.length > 0) {
                            d.filters[columnName] = value; // Regular filter values
                        }
                    }
                });
            },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    columns: columns3,
    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
    pageLength: -1,
    columnDefs: [
        {
            targets: 1,
            render: function (data, type, row) {
                if (row.inspection_id == null && row.inspection_date == null && row.gdn_id == null && row.grn_id == null) {
                    return 'Incoming';
                } else if (row.inspection_id == null && row.inspection_date == null && row.gdn_id == null && row.grn_id != null) {
                    return 'Pending Inspection';
                } else if (row.inspection_date != null && row.so_id == null && (row.reservation_end_date == null || new Date(row.reservation_end_date) < now)) {
                    return 'Available Stock';
                  } else if (row.gdn_id == null && row.so_id == null && new Date(row.reservation_end_date) >= now ) {
                    return 'Booked';
                } else if (row.inspection_date != null && row.gdn_id == null && row.so_id != null && row.grn_id != null) {
                    return 'Sold';
                } else if (row.inspection_date != null && row.gdn_id != null && row.grn_id != null) {
                    return 'Delivered';
                } else {
                    return '';
                }
            }
        }
    ],
    colReorder: true,
    initComplete: function () {
    var api = this.api();

    // For each column in the table, create a dropdown filter
    api.columns().every(function (index) {
        var column = this;
        var columnHeader = $(column.header()).text();  // Get the column header text
        var headerWidth = $(column.header()).outerWidth(); // Get the actual width of the header
        var excludeFilters = ['Variant Detail', 'Actions', 'Comments', 'Status', 'PDI Report', 'GRN Report', 'Aging', 'Vehicle Cost'];
        
        if (excludeFilters.includes(columnHeader)) {
            return; // Skip columns that don't require filters
        }

        // Create a select element with "Empty" and "Not Empty" options
        var select = $('<select multiple="multiple" style="width: 100%">' +
            '<option value="">Filter by ' + columnHeader + '</option>' +
            '<option value="__NULL__">Empty</option>' + // Add the Empty option
            '<option value="__Not EMPTY__">Not Empty</option>' + // Add the Not Empty option
            '</select>')
            .appendTo($(column.header()).empty())  // Append to the header cell
            .on('change', function () {
                var selectedValues = $(this).val();

                // Use ajax.reload to apply filter to the entire table (server-side filtering)
                if (selectedValues && selectedValues.length > 0) {
                    // Store selected values for this column
                    api.settings()[0].ajax.data.filters = api.settings()[0].ajax.data.filters || {};
                    api.settings()[0].ajax.data.filters[index] = selectedValues;
                } else {
                    delete api.settings()[0].ajax.data.filters[index];
                }

                api.ajax.reload(); // Reload the table with new filter data
            });

        // Populate the select element with unique values from the column
        column.data().unique().sort().each(function (d) {
            if (d !== null && d !== '') {  // Skip nulls and empty strings
                if (columnHeader === 'PO Date' || columnHeader === 'Estimated Arrival' || columnHeader === 'Inspection Date' || columnHeader === 'GRN Date' || columnHeader === 'Reservation End' || columnHeader === 'SO Date') {
                    var dateObj = new Date(d);
                    var formattedDate = dateObj.toLocaleDateString('en-GB', {
                        day: '2-digit', month: 'short', year: 'numeric'
                    });
                    select.append('<option value="' + d + '">' + formattedDate + '</option>');
                } else if (columnHeader === 'Production Year') { 
                    var dateObj = new Date(d);
                    var formattedDate = dateObj.toLocaleDateString('en-GB', {
                        year: 'numeric',
                        month: 'long'
                    });
                    select.append('<option value="' + d + '">' + formattedDate + '</option>');
                } else if (columnHeader === 'Price' || columnHeader === 'Minimum Commission') {
                    var formattedValue = parseFloat(d).toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    select.append('<option value="' + d + '">' + formattedValue + '</option>');
                } else {
                    select.append('<option value="' + d + '">' + d + '</option>');
                }
            }
        });
        
        select.select2({
            placeholder: columnHeader, // Placeholder for the column
            allowClear: true,  // Option to clear selections
            dropdownAutoWidth: true,  // Adjust dropdown width based on content
            width: headerWidth + 'px'  // Set the width of the select2 dropdown equal to the header width
        });

        // Set width of the <select> element to match header
        select.css('width', headerWidth + 'px');
    });
}
});
// Create the Hide All and Unhide All buttons
var hideAllButton = $('<button>')
        .text('Hide All')
        .addClass('btn btn-sm btn-danger')
        .on('click', function () {
            table3.columns().every(function () {
                this.visible(false); // Hide all columns
            });
            $('#toggleButtonsRow3').find('button').addClass('btn-primary').removeClass('btn-outline-primary');
        });

    var unhideAllButton = $('<button>')
        .text('Unhide All')
        .addClass('btn btn-sm btn-success')
        .on('click', function () {
            table3.columns().every(function () {
                this.visible(true); // Unhide all columns
            });
            $('#toggleButtonsRow3').find('button').addClass('btn-outline-primary').removeClass('btn-primary');
        });

    // Add the buttons above the table
    $('#dtBasicExample3_wrapper').prepend(
        $('<div class="d-flex mb-2">').append(hideAllButton).append(unhideAllButton)
    );

    // Create toggle buttons for each column
    table3.columns().every(function (index) {
        var column = this;
        var columnTitle = $(column.header()).text();

        // Create a button element
        var toggleButton = $('<button>')
            .text(columnTitle)
            .addClass('btn btn-sm btn-outline-primary')
            .on('click', function () {
                column.visible(!column.visible()); // Toggle column visibility
                $(this).toggleClass('btn-primary btn-outline-primary'); // Toggle button style
            });

        // Add the button above the column header
        $('#toggleButtonsRow3').append($('<th>').append(toggleButton));
    });
table3.on('draw', function () {
    var rowCount = table3.page.info().recordsDisplay;
    if (rowCount > 0) {
        $('.row-badge3').text(rowCount).show();
    } else {
        $('.row-badge3').hide();
    }
});
$('#dtBasicExample3').on('processing.dt', function(e, settings, processing) {
    if (processing) {
        $('#dtBasicExample3_processing').show();
    } else {
        $('#dtBasicExample3_processing').hide();
    }
});
$('#dtBasicExample3 tbody').off('click', 'td');
$('#dtBasicExample3 tbody').on('click', 'td', function () {
    var table = $('#dtBasicExample3').DataTable();
    var cellIndex = table.cell(this).index().column; // Get the clicked cell's column index
    var columnHeader = table.column(cellIndex).header().innerText; // Get the header text of the clicked column
    if (columnHeader.includes('Custom Inspection Number') || columnHeader.includes('Custom Inspection Status')) {
        @php
        $hascustominspectionPermission = Auth::user()->hasPermissionForSelectedRole('add-custom-inspection');
        @endphp
        @if ($hascustominspectionPermission)
            var datainspection = table3.row(this).data();
            opencustominspectionModal(datainspection.id);
        @endif
    }
    else if (columnHeader.includes('Reservation End')) {
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('direct-booking');
    @endphp
    @if ($hasPermission)
        var data = table3.row(this).data();
        openBookingModal(data.id);
    @endif
    }
    else if (columnHeader.includes('Sales Remarks')) {
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('direct-booking');
    @endphp
    @if ($hasPermission)
        var data = table3.row(this).data();
        openremarksModal(data.id);
    @endif
    }
    else if(columnHeader === 'Variant Detail')
{
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('adding-enhancement');
    @endphp
    @if ($hasPermission)
        var data = table3.row(this).data();
        openenhancementModal(data.id);
    @endif
}
if (columnHeader.includes('Ext Colour') || columnHeader.includes('Int Colour')) {
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('editing-colours');
    @endphp
    @if ($hasPermission)
        var data = table3.row(this).data();
        openeditingcolorModal(data.id);
    @endif
}
});
        function handleModalShow(modalId) {
    $(modalId).on('show.bs.modal', function () {
        var scrollTop = $(window).scrollTop();
        $('body').css({
            position: 'fixed',
            top: -scrollTop + 'px',
            width: '100%'
        }).data('scrollTop', scrollTop);
    }).on('hidden.bs.modal', function () {
        var scrollTop = $('body').data('scrollTop');
        $('body').css({
            position: '',
            top: '',
            width: ''
        });
        $(window).scrollTop(scrollTop);
    });
}
handleModalShow('#imageModal');
handleModalShow('#noImageModal');
handleModalShow('#variantview'); // Already existing modal
});
function exportToExcel(tableId) {
    var table = document.getElementById(tableId);
    var rows = table.rows;
    var csvContent = "";
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        for (var j = 0; j < row.cells.length; j++) {
            var cellText = row.cells[j].innerText || row.cells[j].textContent;
            csvContent += '"' + cellText.replace(/"/g, '""') + '",';
        }
        csvContent += "\n";
    }
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, 'export.csv');
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) {
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", "export.csv");
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}
  function generatePDF(vehicleId) {
    var url = `/viewgrnreport/method?vehicle_id=${vehicleId}`;
    window.open(url, '_blank');
}
function generatePDFpdi(vehicleId) {
    var url = `/viewpdireport/method?vehicle_id=${vehicleId}`;
    window.open(url, '_blank');
}
function openModal(id) {
    $.ajax({
        url: '/variants_details/' + id,
        type: 'GET',
        success: function(response) {
            $('#variantview .modal-body').empty();
            var modalBody = $('#variantview .modal-body');
            var variantDetailsTable = $('<table class="table table-bordered"></table>');
            var variantDetailsBody = $('<tbody></tbody>');
            if (response.modifiedVariants) {
            variantDetailsBody.append('<tr><th>Attribute</th><th>Options</th><th>Modified Option</th></tr>');
            if(response.variants.name != response.basevaraint.name)
            {
              variantDetailsBody.append('<tr><th>Name</th><td>' + response.basevaraint.name + '</td><td>' + response.variants.name + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Name</th><td>' + response.variants.name + '</td></tr>');
            }
            if(response.basevaraint.steering != response.variants.steering)
            {
            variantDetailsBody.append('<tr><th>Steering</th></td><td>'+ response.basevaraint.steering +'<td>' + response.variants.steering + '</td></tr>');
            }
            else {
              variantDetailsBody.append('<tr><th>Steering</th></td><td>'+ response.basevaraint.steering +'<td></td></tr>');
            }
            if(response.basevaraint.engine != response.variants.engine)
            {
            variantDetailsBody.append('<tr><th>Engine</th></td><td>'+ response.basevaraint.engine +'<td>' + response.variants.engine + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Engine</th></td><td>'+ response.basevaraint.engine +'<td></td></tr>');
            }
            if(response.basevaraint.my != response.variants.my)
            {
            variantDetailsBody.append('<tr><th>Production Year</th></td><td>'+ response.basevaraint.my +'<td>' + response.variants.my + '</td></tr>');
            }
            else 
            {
            variantDetailsBody.append('<tr><th>Production Year</th></td><td>'+ response.basevaraint.my +'<td></td></tr>');
            }
            if(response.basevaraint.fuel_type != response.variants.fuel_type)
            {
            variantDetailsBody.append('<tr><th>Fuel Type</th></td><td>'+ response.basevaraint.fuel_type +'<td>' + response.variants.fuel_type + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Fuel Type</th></td><td>'+ response.basevaraint.fuel_type +'<td></td></tr>');
            }
            if(response.basevaraint.gearbox != response.variants.gearbox)
            {
            variantDetailsBody.append('<tr><th>Gear</th></td><td>'+ response.basevaraint.gearbox +'<td>' + response.variants.gearbox + '</td></tr>');
            }
            else 
            {
              variantDetailsBody.append('<tr><th>Gear</th></td><td>'+ response.basevaraint.gearbox +'<td></td></tr>');
            }
            if(response.basevaraint.drive_train != response.variants.drive_train)
            {
            variantDetailsBody.append('<tr><th>Drive Train</th></td><td>'+ response.basevaraint.drive_train +'<td>' + response.variants.drive_train + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Drive Train</th></td><td>'+ response.basevaraint.drive_train +'<td></td></tr>');
            }
            if(response.basevaraint.upholestry != response.variants.upholestry)
            {
            variantDetailsBody.append('<tr><th>Upholstery</th></td><td>'+ response.basevaraint.upholestry +'<td>' + response.variants.upholestry + '</td></tr>');
            }
            else
            {
              variantDetailsBody.append('<tr><th>Upholstery</th></td><td>'+ response.basevaraint.upholestry +'<td></td></tr>'); 
            }
            }
            else 
            {
            variantDetailsBody.append('<tr><th>Attribute</th><th>Options</th></tr>');
            variantDetailsBody.append('<tr><th>Name</th><td>' + response.variants.name + '</td></tr>');
            variantDetailsBody.append('<tr><th>Steering</th><td>' + response.variants.steering + '</td></tr>');
            variantDetailsBody.append('<tr><th>Engine</th><td>' + response.variants.engine + '</td></tr>');
            variantDetailsBody.append('<tr><th>Production Year</th><td>' + response.variants.my + '</td></tr>');
            variantDetailsBody.append('<tr><th>Fuel Type</th><td>' + response.variants.fuel_type + '</td></tr>');
            variantDetailsBody.append('<tr><th>Gear</th><td>' + response.variants.gearbox + '</td></tr>');
            variantDetailsBody.append('<tr><th>Drive Train</th><td>' + response.variants.drive_train + '</td></tr>');
            variantDetailsBody.append('<tr><th>Upholstery</th><td>' + response.variants.upholestry + '</td></tr>');
            }
            variantDetailsTable.append(variantDetailsBody);
            modalBody.append('<h5>Variant Details:</h5>');
            modalBody.append(variantDetailsTable);
              modalBody.append('<h5>Attributes Items:</h5>');
              var variantItemsTable = $('<table class="table table-bordered"></table>');
              if (response.modifiedVariants) {
              var variantItemsHeader = $('<thead><tr><th>Attributes</th><th>Options</th><th>Modified Option</th></tr></thead>');
              }
              else{
                var variantItemsHeader = $('<thead><tr><th>Attributes</th><th>Options</th></tr></thead>');
              }
              var variantItemsBody = $('<tbody></tbody>');
              response.variantItems.forEach(function(variantItem) {
                  var specificationName = variantItem.model_specification ? variantItem.model_specification.name : 'N/A';
                  var optionName = variantItem.model_specification_option ? variantItem.model_specification_option.name : 'N/A';
                  var modificationOption = '';
                  if (response.modifiedVariants) {
                      response.modifiedVariants.forEach(function(modifiedVariant) {
                          if (modifiedVariant.modified_variant_items && modifiedVariant.modified_variant_items.name === specificationName) {
                              modificationOption = modifiedVariant.addon ? modifiedVariant.addon.name : '';
                          }
                      });
                      variantItemsBody.append('<tr><td>' + specificationName + '</td><td>' + optionName + '</td><td>' + modificationOption + '</td></tr>');
                  }
                  else{
                    variantItemsBody.append('<tr><td>' + specificationName + '</td><td>' + optionName + '</td></tr>');
                  }
              });
              variantItemsTable.append(variantItemsHeader);
              variantItemsTable.append(variantItemsBody);
              modalBody.append(variantItemsTable);
            if (response.modifiedVariants) {
                modalBody.append('<h5>Modified Attributes Items:</h5>');
                var modifiedVariantTable = $('<table class="table table-bordered"></table>');
                var modifiedVariantHeader = $('<thead><tr><th>Modified Attributes</th><th>Modified Option</th></tr></thead>');
                var modifiedVariantBody = $('<tbody></tbody>');
                response.modifiedVariants.forEach(function(modifiedVariant) {
                    var modifiedVariantName = modifiedVariant.modified_variant_items ? modifiedVariant.modified_variant_items.name : 'N/A';
                    var addonName = modifiedVariant.addon ? modifiedVariant.addon.name : 'N/A';
                    modifiedVariantBody.append('<tr><td>' + modifiedVariantName + '</td><td>' + addonName + '</td></tr>');
                });
                modifiedVariantTable.append(modifiedVariantHeader);
                modifiedVariantTable.append(modifiedVariantBody);
                modalBody.append(modifiedVariantTable);
            }
            $('#variantview').modal('show');
        },
        error: function(xhr, status, error) {
        }
    });
}
function fetchVehicleData(vehicleId) {
    $.ajax({
        url: "{{ route('fetchData') }}",
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            vehicle_id: vehicleId
        },
        success: function(response) {
            if (response.gallery) {
                displayGallery(response.gallery);
                $('#imageModal').modal('show');
            } else {
                alert('No post found');
            }
        },
        error: function(xhr) {
            if (xhr.status === 404) {
                showNoImagePopup();
            } else {
                console.error(xhr);
            }
        }
    });
}

function showNoImagePopup() {
    $('#noImageModal').modal('show');
}
function displayGallery(imageUrls) {
    var carouselImages = document.getElementById("carouselImages");
    carouselImages.innerHTML = "";
    imageUrls.forEach(function(url, index) {
        var div = document.createElement("div");
        div.className = "carousel-item" + (index === 0 ? " active" : "");
        var img = document.createElement("img");
        img.className = "d-block w-100";
        img.src = url;
        div.appendChild(img);
        carouselImages.appendChild(div);
    });
}
</script>
<script>
    function openremarksModal(vehicleId) {
    $('#vehicle_remarks_id').val(vehicleId);
        $.ajax({
        url: '/get-sales-remarks',
        type: 'GET',
        data: { vehicle_id: vehicleId },
        success: function(response) {
            $('#salesremarks').val(response.sales_remarks);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching custom inspection data:', error);
        }
    });
    $('#remarksModal').modal('show');
}
 function openBookingModal(vehicleId) {
    // Set the vehicle ID in the hidden input field
    $('#vehicle_id').val(vehicleId);

    // Now, retrieve the value of vehicle_id from the hidden input field
    var vehicleIdValue = $('#vehicle_id').val();

    $.ajax({
        url: '{{ route('get.reservation') }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            vehicle_id: vehicleIdValue
        },
        success: function(response) {
            if (response && Object.keys(response).length > 0) {
                $('#cancelBookingButton').show();
            } else {
                $('#cancelBookingButton').hide();
            }
        },
        error: function(xhr) {
            console.log('An error occurred while fetching the reservation data.');
        }
    });

    $('#bookingModal').modal('show');
}
function openenhancementModal(vehicleId) {
    $.ajax({
        url: "{{ route('enhancement.getVariants') }}",
        method: "GET",
        data: { vehicle_id: vehicleId },
        success: function(response) {
            if(response.success) {
                let variantOptions = '';
                response.data.variants.forEach(function(variant) {
                    variantOptions += `<option value="${variant.id}">${variant.name}</option>`;
                });
                $('#variantSelect').html(variantOptions);
                $('#vehicleVin').text(response.data.vin);
                $('#vehicle_idenchacment').val(vehicleId);
                $('#enhancementModal').modal('show');
            }
        },
        error: function() {
            alert('Error fetching data. Please try again.');
        }
    });
}
function openeditingcolorModal(vehicleId) {
        $.ajax({
            url: '{{ route('get.color.data') }}',
            type: 'GET',
            data: {
                vehicle_id: vehicleId
            },
            success: function(response) {
                $('#int_color_dropdown').html(response.intColorOptions);
                $('#ext_color_dropdown').html(response.extColorOptions);
                $('#vehicleVincolor').text(response.vin);
                $('#vehicle_colour').val(vehicleId);
                $('#colorModal').modal('show');
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    function opencustominspectionModal(vehicleIdInspection) {
    // Set the vehicle_idinspection value
    $('#vehicle_idinspection').val(vehicleIdInspection);

    // Make an AJAX call to get the custom inspection details
    $.ajax({
        url: '/get-custom-inspection-data',  // The route to get the custom inspection data
        type: 'GET',
        data: { vehicle_id: vehicleIdInspection },
        success: function(response) {
            // Populate the modal fields with the fetched data
            $('#custom_inspection_number').val(response.custom_inspection_number);
            $('#custom_inspection_status').val(response.custom_inspection_status);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching custom inspection data:', error);
        }
    });

    // Show the modal
    $('#custominspectionModal').modal('show');
}
    function showFullText(button) {
        var fullText = button.getAttribute('data-fulltext');
        alert(fullText);
    }
    $('#remarksForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
            $('#remarksModal').modal('hide');
            alertify.success('Remarks saved successfully');
           // Update the corresponding row in the DataTable (assuming table7 is your DataTable variable)
           var table3 = $('#dtBasicExample3').DataTable();
           var vehicleId = $('#vehicle_remarks_id').val();
    // Find the row in the DataTable using the 'id' field (since it's the unique identifier)
    var row = table3.row(function(idx, data, node) {
        return data.id == vehicleId; // Use 'id' to match the row
    });
    // Check if the row exists before attempting to update
    if (row.node()) {
        // Update the row data with new values from the response
        row.data({
            ...row.data(), // Keep other fields intact
            sales_remarks: response.sales_remarks, // Update inspection number
        }).draw(false); // Redraw the row
    } else {
        console.error("No matching row found for vehicle ID: " + vehicleId);
    }
        },
        error: function(xhr) {
    console.log(xhr.responseText); // Log full response for debugging
    var errors = xhr.responseJSON.errors;
    var errorMessages = '';
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            errorMessages += errors[key] + '\n';
        }
    }
    alert('An error occurred:\n' + errorMessages);
}
    });
});
    $('#bookingForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
            $('#bookingModal').modal('hide');
            alertify.success('Booking saved successfully');
           // Update the corresponding row in the DataTable (assuming table7 is your DataTable variable)
           var table3 = $('#dtBasicExample3').DataTable();
           var vehicleId = $('#vehicle_id').val();
    // Find the row in the DataTable using the 'id' field (since it's the unique identifier)
    var row = table3.row(function(idx, data, node) {
        return data.id == vehicleId; // Use 'id' to match the row
    });
    // Check if the row exists before attempting to update
    if (row.node()) {
        // Update the row data with new values from the response
        row.data({
            ...row.data(), // Keep other fields intact
            sales_remarks: response.sales_remarks, // Update inspection number
        }).draw(false); // Redraw the row
    } else {
        console.error("No matching row found for vehicle ID: " + vehicleId);
    }
        },
        error: function(xhr) {
    console.log(xhr.responseText); // Log full response for debugging

    var errors = xhr.responseJSON.errors;
    var errorMessages = '';
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            errorMessages += errors[key] + '\n';
        }
    }
    alert('An error occurred:\n' + errorMessages);
}
    });
});
$('#enhancementForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
            $('#enhancementModal').modal('hide');
            alertify.success('Enhancement saved successfully');
           // Update the corresponding row in the DataTable (assuming table7 is your DataTable variable)
           var table3 = $('#dtBasicExample3').DataTable();
           var vehicleId = $('#vehicle_idenchacment').val();
    // Find the row in the DataTable using the 'id' field (since it's the unique identifier)
    var row = table3.row(function(idx, data, node) {
        return data.id == vehicleId; // Use 'id' to match the row
    });
    // Check if the row exists before attempting to update
    if (row.node()) {
        // Update the row data with new values from the response
        row.data({
            ...row.data(), // Keep other fields intact
            sales_remarks: response.sales_remarks, // Update inspection number
        }).draw(false); // Redraw the row
    } else {
        console.error("No matching row found for vehicle ID: " + vehicleId);
    }
        },
        error: function(xhr) {
    console.log(xhr.responseText); // Log full response for debugging

    var errors = xhr.responseJSON.errors;
    var errorMessages = '';
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            errorMessages += errors[key] + '\n';
        }
    }
    alert('An error occurred:\n' + errorMessages);
}
    });
});
$('#editColorForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
            $('#colorModal').modal('hide');
            alertify.success('Colour Change successfully');
           // Update the corresponding row in the DataTable (assuming table7 is your DataTable variable)
           var table3 = $('#dtBasicExample3').DataTable();
           var vehicleId = $('#vehicle_colour').val();
    // Find the row in the DataTable using the 'id' field (since it's the unique identifier)
    var row = table3.row(function(idx, data, node) {
        return data.id == vehicleId; // Use 'id' to match the row
    });
    // Check if the row exists before attempting to update
    if (row.node()) {
        // Update the row data with new values from the response
        row.data({
            ...row.data(), // Keep other fields intact
            sales_remarks: response.sales_remarks, // Update inspection number
        }).draw(false); // Redraw the row
    } else {
        console.error("No matching row found for vehicle ID: " + vehicleId);
    }
        },
        error: function(xhr) {
    console.log(xhr.responseText); // Log full response for debugging

    var errors = xhr.responseJSON.errors;
    var errorMessages = '';
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            errorMessages += errors[key] + '\n';
        }
    }
    alert('An error occurred:\n' + errorMessages);
}
    });
});
$('#custominspectionForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        success: function(response) {
            $('#custominspectionModal').modal('hide');
            alertify.success('Custom Inspection Update Successfully');
           // Update the corresponding row in the DataTable (assuming table7 is your DataTable variable)
           var table3 = $('#dtBasicExample3').DataTable();
           var vehicleId = $('#vehicle_idinspection').val();
    // Find the row in the DataTable using the 'id' field (since it's the unique identifier)
    var row = table3.row(function(idx, data, node) {
        return data.id == vehicleId; // Use 'id' to match the row
    });
    // Check if the row exists before attempting to update
    if (row.node()) {
        // Update the row data with new values from the response
        row.data({
            ...row.data(), // Keep other fields intact
            custom_inspection_number: response.custom_inspection_number, // Update inspection number
            custom_inspection_status: response.custom_inspection_status // Update inspection status
        }).draw(false); // Redraw the row
    } else {
        console.error("No matching row found for vehicle ID: " + vehicleId);
    }
        },
        error: function(xhr) {
    console.log(xhr.responseText); // Log full response for debugging

    var errors = xhr.responseJSON.errors;
    var errorMessages = '';
    for (var key in errors) {
        if (errors.hasOwnProperty(key)) {
            errorMessages += errors[key] + '\n';
        }
    }
    alert('An error occurred:\n' + errorMessages);
}
    });
});
</script>
<script>
let currentVehicleId = null;
function openChatModal(vehicleId) {
    currentVehicleId = vehicleId;  // Store the vehicleId in a global variable
    $('#chatModal').modal('show');
    loadMessages(vehicleId);
}

function loadMessages(vehicleId) {
    $.get(`/stockmessages/${vehicleId}`, function(data) {
        $('#messages').empty();
        data.forEach(function(message) {
            displayMessage(message);
        });
        scrollToBottom();
    });
}

function scrollToBottom() {
    $('#messages').scrollTop($('#messages')[0].scrollHeight);
}

function formatTimeAgo(date) {
    const now = new Date();
    const messageDate = new Date(date);
    const diff = Math.floor((now - messageDate) / 1000);
    if (diff < 60) return `${diff} seconds ago`;
    if (diff < 3600) return `${Math.floor(diff / 60)} minutes ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} hours ago`;
    return `${Math.floor(diff / 86400)} days ago`;
}

function getInitials(name) {
    return name.charAt(0).toUpperCase();
}

function getAvatarColor(name) {
    const colors = ['#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8'];
    const charCode = name.charCodeAt(0);
    return colors[charCode % colors.length];
}

function displayMessage(message) {
    const replies = message.replies || [];
    const messageTime = formatTimeAgo(message.created_at);
    const userInitial = getInitials(message.user.name);
    const userColor = getAvatarColor(message.user.name);
    const messageHtml = `
        <div class="card message-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="user-info">
                        <div class="avatar" style="background-color: ${userColor};">${userInitial}</div>
                        <strong class="user-name">${message.user.name}</strong>
                    </div>
                    <small class="text-muted">${messageTime}</small>
                </div>
                <p class="mt-2">${message.message}</p>
                <div id="replies-${message.id}">
                    ${replies.map(reply => `
                        <div class="message-reply">
                            <div class="d-flex justify-content-between">
                                <div class="user-info">
                                    <div class="avatar avatar-small" style="background-color: ${getAvatarColor(reply.user.name)};">${getInitials(reply.user.name)}</div>
                                    <strong class="user-name">${reply.user.name}</strong>
                                </div>
                                <small class="text-muted">${formatTimeAgo(reply.created_at)}</small>
                            </div>
                            <p class="mt-1">${reply.reply}</p>
                        </div>
                    `).join('')}
                </div>
                <a href="javascript:void(0)" class="reply-link" data-message-id="${message.id}">Reply</a>
                <div class="reply-input-wrapper input-group mt-2" style="display:none;" id="reply-input-${message.id}">
                    <textarea class="form-control reply-message" placeholder="Reply..." rows="1"></textarea>
                    <button class="btn btn-success btn-sm send-reply" data-message-id="${message.id}">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#messages').append(messageHtml);
}
let csrfToken = $('meta[name="csrf-token"]').attr('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrfToken
    }
});
function sendMessage() {
    const message = $('#message').val();
    if (message.trim() !== '') {
        $.post('/stockmessages', { vehicle_id: currentVehicleId, message: message }, function(data) {
            displayMessage(data);
            $('#message').val('');
            scrollToBottom();
        });
    }
}

function sendReply(messageId) {
    const reply = $(`#reply-input-${messageId}`).find('.reply-message').val();
    if (reply.trim() !== '') {
        $.post('/stockreplies', { message_id: messageId, reply: reply }, function(data) {
            const replyHtml = `
                <div class="message-reply">
                    <div class="d-flex justify-content-between">
                        <div class="user-info">
                            <div class="avatar avatar-small" style="background-color: ${getAvatarColor(data.user.name)};">${getInitials(data.user.name)}</div>
                            <strong class="user-name">${data.user.name}</strong>
                        </div>
                        <small class="text-muted">${formatTimeAgo(data.created_at)}</small>
                    </div>
                    <p class="mt-1">${data.reply}</p>
                </div>
            `;
            $(`#replies-${messageId}`).append(replyHtml);
            $(`#reply-input-${messageId}`).find('.reply-message').val('');
            $(`#reply-input-${messageId}`).hide();
        });
    }
}

$(document).ready(function() {
    $('#send-message').on('click', function() {
        sendMessage();
    });

    $('#message').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            sendMessage();
            e.preventDefault();
        }
    });

    $(document).on('click', '.reply-link', function() {
        const messageId = $(this).data('message-id');
        $(`#reply-input-${messageId}`).toggle();
    });

    $(document).on('keypress', '.reply-message', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            const messageId = $(this).closest('.reply-input-wrapper').attr('id').split('-')[2];
            sendReply(messageId);
            e.preventDefault();
        }
    });

    $(document).on('click', '.send-reply', function() {
        const messageId = $(this).data('message-id');
        sendReply(messageId);
    });
});
</script>
<script>
    document.getElementById('cancelBookingButton').addEventListener('click', function() {
        var vehicleId = document.getElementById('vehicle_id').value;
        
        if(confirm('Are you sure you want to cancel this booking?')) {
            // Send AJAX request to cancel booking
            fetch('{{ route('booking.canceling') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ vehicle_id: vehicleId })
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      alert('Booking canceled successfully.');
                      $('#bookingModal').modal('hide');
                  } else {
                      alert('Failed to cancel the booking.');
                  }
              }).catch(error => console.error('Error:', error));
        }
    });
</script>
@endsection