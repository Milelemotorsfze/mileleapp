@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Allow Select2 dropdown to adjust dynamically based on content */
    .select2-container {
    width: 100% !important; /* Forces the width to match its parent */
}

.select2-dropdown {
    min-width: auto !important; /* Allows dynamic resizing */
    width: auto !important; /* Adjusts to content */
}

/* Make sure the dropdown appears above the modal */
.select2-container--open {
    z-index: 9999 !important;
}

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
    white-space: nowrap;
    cursor: grab;
}
   .btn-outline-primary {
    margin-bottom: 5px;
    width: 100%;
}
.select2-dropdown.select2-dropdown--below {
    position: inherit !important;
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
    z-index: 1100;
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
     Sales Order
    </h4>
</div>
<!-- Modal for Updating Sales Person -->
<div class="modal fade" id="updateSalespersonModal" tabindex="-1" aria-labelledby="updateSalespersonLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateSalespersonLabel">Update Sales Person</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateSalespersonForm">
                    <!-- Hidden Field for Sales Order ID -->
                    <input type="hidden" id="salesOrderId" name="sales_order_id">

                    <label for="salespersonSelect">Select Sales Person:</label>
                    <select class="form-control" id="salespersonSelect" name="salesperson_id">
                        <option value="">Select Sales Person</option>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSalesperson">Update</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalLabel">File Viewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="fileViewer" width="100%" height="500" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        <div class="card-body">
        <div class="table-responsive dragscroll" style="height: 80vh;">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table table-bordered" style = "width:100%;">
            <thead class="bg-soft-secondary" style="position: sticky; top: 0;">
            <tr>
                  <th>SO Number</th>
                  <th>Sales Date</th>
                  <th>Sales Person</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Quotation Date</th>
                  <th>Quotation Value</th>
                  <th>Quotation Notes</th>
                  <th>View Quotation</th>
                  <th>SO Update</th>
                  <th>Quotation Versions</th>
                  <th>SO Canacel</th>
                 
                </tr>
                <tr>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th><select><option value="">All</option></select></th>
        <th></th> <!-- No filter for the Update button -->
        <th></th> <!-- No filter for the Cancel button -->
        <th></th>
    </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div> 
        </div>  
        <div id="soCount" data-so-count="{{ $soCount }}"></div>

        <script>
   $(document).ready(function () {
    var dataTable8 = $('#dtBasicExample3').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('salesorder.index', ['status' => 'SalesOrder']) }}",
        columns: [
            { data: 'so_number', name: 'so.so_number' },
            {
                data: 'so_date',
                name: 'so.so_date',
                render: function(data, type, row) {
                    if (data) {
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
    data: 'name',
    name: 'users.name',
    render: function(data, type, row) {
        if (row.calls_id === null) { // Check if calls_id is not null
            return `<a href="#" class="update-salesperson" 
                        data-so-id="${row.soid}" 
                        data-current-salesperson="${row.name}">
                        ${data}
                    </a>`;
        } else {
            return data; // If calls_id is null, return plain text
        }
    }
},
            { data: 'customername', name: 'calls.name' },
            { data: 'phone', name: 'calls.phone' },
            { data: 'email', name: 'calls.email' },
            {
                data: 'created_at',
                name: 'quotations.created_at',
                render: function(data, type, row) {
                    if (data) {
                        var dateObj = new Date(data);
                        var formattedDate = dateObj.toLocaleDateString('en-GB', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });
                        return formattedDate;
                    }
                    return ''; // If no date, return empty
                }
            },
            { data: 'deal_value', name: 'quotations.deal_value', type: 'num' },
            { data: 'sales_notes', name: 'quotations.sales_notes' },
            {
                data: 'file_path',
                name: 'file_path',
                searchable: false,
                render: function (data, type, row) {
                    if (data) {
                        return `<i class="fas fa-file-alt view-file" data-file="${data}" style="cursor: pointer;" onclick="openModalfile('${data}')"></i>`;
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'calls_id',
                name: 'quotations.calls_id',
                searchable: false,
                render: function (data, type, row) {
                    if (row.calls_id !== null) {
                    // const updatesaleorder = `{{ url('salesorder/update') }}/${data}`;
                    let so_id = row.soid; // Check if quotation_id is not null
                    const updatesaleorder = `{{ route('salesorder.edit', ':id') }}`.replace(':id', so_id);

                    const soCount = document.getElementById('soCount').dataset.soCount;
                    
                    if(so_id > soCount) {
                        return `<a class="btn btn-sm btn-info" href="${updatesaleorder}" title="Update Sales Order"><i class="fa fa-window-maximize" aria-hidden="true"></i></a>`;
                    }
                }
                return ''; 
                }
            },
             {
                data: 'calls_id',
                name: 'quotations.calls_id',
                searchable: false,
                render: function (data, type, row) {
                    if (row.calls_id !== null) { 
                     let so_id = row.soid;
                    const showSQuotations = `{{ route('so.quotation-versions', ':id') }}`.replace(':id', so_id);
                    return `<a class="btn btn-sm btn-primary" href="${showSQuotations}" title="show Quotations"><i class="fa fa-eye" aria-hidden="true"></i></a>`;
                }
                return ''; 
                }
            },
            {
                data: 'soid',
                name: 'so.id',
                searchable: false,
                orderable: false,
                render: function (data, type, row) {
                    if (row.calls_id !== null) { // Check if quotation_id is not null
                    return `<button class="btn btn-sm btn-danger" onclick="cancelSO(${data})" title="Cancel Sales Order">Cancel SO</button>`;
                    }
                    return '';
                }
            }
        ],
        columnDefs: [
        {
            targets: 7, // Target the deal_value column
            render: function (data, type, row) {
                // Ensure proper numeric formatting
                return type === 'display' || type === 'filter'
                    ? parseFloat(data).toFixed(2) // Format for display
                    : parseFloat(data); // Use numeric for sorting
            },
            type: 'num' // Explicitly set type to numeric
        }
    ],
        pageLength: -1,
        initComplete: function () {
            // Apply dropdown filters to each column
            this.api().columns().every(function (index) {
                if (index === 10 || index === 11) {
    return; // Skip adding a filter for these columns
}
                var column = this;
                var select = $('<select multiple="multiple" style="width: 100%"><option value="">All</option></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function () {
                        var selectedValues = $(this).val();
                        if (selectedValues && selectedValues.length) {
                            // Filter by selected values, allowing for multiple
                            column.search(selectedValues.join('|'), true, false).draw();
                        } else {
                            // No selected values, clear the filter
                            column.search('', true, false).draw();
                        }
                    });

                // Populate the select options dynamically, handling date formatting
                column.data().unique().sort().each(function (d, j) {
                    if (index === 10 || index === 11) {
    return; // Skip adding a filter for these columns
}
                    if (index === 1 || index === 6) { // Assuming date columns are 3 and 8
                        if (d) {
                            var dateObj = new Date(d);
                            var formattedDate = dateObj.toLocaleDateString('en-GB', {
                                day: '2-digit', month: 'short', year: 'numeric'
                            });
                            select.append('<option value="' + d + '">' + formattedDate + '</option>');
                        }
                    } else {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    }
                });

                // Initialize Select2 on the select element with a custom width to fit the column
                select.select2({
                    dropdownAutoWidth: true, // Adjust dropdown width to fit the content
                    placeholder: 'Filter'
                });
            });
        }
    });

    // Global definition of the cancelSO function
    window.cancelSO = function (id) {
        if (confirm('Are you sure you want to cancel this Sales Order?')) {
            window.location.href = `{{ url('salesorder/cancel') }}/${id}`;
        }
    }
});
    var url = '{{ asset('storage/quotation_files/') }}';
            var QuotationUrl = url + '/' + '{{request()->quotationFilePath}}';
            if('{{ request()->quotationFilePath }}' ) {
              window.open(QuotationUrl, '_blank');
          }
     function openModalfile(filePath) {
    const baseUrl = "{{ asset('storage/') }}"; // The base URL to the public storage directory
    const fileUrl = baseUrl + '/' + filePath; // Add a slash between baseUrl and filePath
    $('#fileViewer').attr('src', fileUrl);
    $('#fileModal').modal('show');
}
$('#fileModal').on('hidden.bs.modal', function () {
    $('#fileViewer').attr('src', '');
});
$(document).ready(function () {
    $(document).on('click', '.update-salesperson', function (e) {
    e.preventDefault();

    let soId = $(this).data('so-id'); // Get Sales Order ID
    let currentSalesperson = $(this).data('current-salesperson'); // Get current Salesperson

    // Set Sales Order ID inside the modal's hidden input field
    $('#salesOrderId').val(soId);

    // Fetch Salespersons via AJAX
    $.ajax({
        url: "{{ route('salespersons.list') }}",
        type: "GET",
        success: function (response) {
            let dropdown = $('#salespersonSelect');
            dropdown.empty();
            dropdown.append('<option value="">Select Sales Person</option>');

            $.each(response.salespersons, function (key, salesperson) {
                let selected = (salesperson.name === currentSalesperson) ? 'selected' : '';
                dropdown.append(`<option value="${salesperson.id}" ${selected}>${salesperson.name}</option>`);
            });

            // Show the modal
            $('#updateSalespersonModal').modal('show');
        }
    });
});


$('#saveSalesperson').click(function () {
    let soId = $('#salesOrderId').val(); // Get Sales Order ID
    let selectedSalespersonId = $('#salespersonSelect').val(); // Get Selected Sales Person ID

    if (!selectedSalespersonId) {
        alert("Please select a sales person.");
        return;
    }

    $.ajax({
        url: "{{ route('salesorder.updateSalesperson') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            sales_order_id: soId, // Send Sales Order ID
            salesperson_id: selectedSalespersonId
        },
        success: function (response) {
            if (response.success) {
                alert('Sales Person Updated Successfully!');
                $('#updateSalespersonModal').modal('hide');

                // Reload DataTable to reflect changes
                $('#dtBasicExample3').DataTable().ajax.reload();
            } else {
                alert('Failed to update Sales Person. Please try again.');
            }
        },
        error: function () {
            alert('Error updating Sales Person.');
        }
    });
});
});
</script>
@endsection