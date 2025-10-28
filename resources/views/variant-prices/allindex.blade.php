@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
        /* Ensure the select2 rendered text stays inside the dropdown */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 28px; /* Adjust this line height to match your dropdown height */
        padding-left: 8px; /* Add some padding for better alignment */
    }
    /* Ensure the dropdown itself doesn't overflow or look weird */
    .select2-container--default .select2-selection--single {
        height: auto; /* Ensure auto height for the dropdown */
        min-height: 38px; /* Ensure minimum height to match other inputs */
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #cbdada; /* Adjust this color as needed */
        color: #212529;
    }
    /* Fix the alignment of the arrow inside the dropdown */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%; /* Make the arrow container full height */
        top: 0; /* Align to the top */
        right: 8px; /* Adjust right padding if needed */
    }
    /* Prevent overlapping or overflow issues with the dropdown box */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-right: 30px; /* Adjust to prevent text from overlapping with the arrow */
    }
    /* Add some margin at the bottom of the select box */
    .select2-container {
        margin-bottom: 15px; /* Adjust as needed */
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
    .pending-price-change {
        background-color: #fff3cd !important;
        border-color: #ffeaa7 !important;
    }
    .csr-required-blink {
        animation: blink 1s infinite;
        border-color: #dc3545 !important;
    }
    @keyframes blink {
        0%, 50% { background-color: #f8d7da; }
        51%, 100% { background-color: #fff; }
    }
    .update-prices-btn {
        padding: 2px 6px !important;
        font-size: 11px !important;
        line-height: 1.2 !important;
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
     Variant Price Update
    </h4>
    <br>
</div>
<div class="card-body">
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

<!-- Price Change Reason Modal -->
<div class="modal fade" id="priceChangeReasonModal" tabindex="-1" aria-labelledby="priceChangeReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="priceChangeReasonModalLabel">Price Change Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="priceChangeReasonForm">
                    <div class="mb-3">
                        <label for="priceChangeReason" class="form-label">Please provide a reason for the price change:</label>
                        <textarea class="form-control" id="priceChangeReason" name="reason" rows="4" placeholder="Enter the reason for changing the price..." maxlength="500"></textarea>
                        <div class="form-text">Maximum 500 characters</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPriceChange">Confirm Price Change</button>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive" style="height: 74vh;">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
        <thead class="bg-soft-secondary" style="position: sticky; top: 0;">
                <tr>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Variant</th>
                    <th>Interior Colour</th>
                    <th>Exterior Colour</th>
                    <th>Minimum Commission</th>
                    <!-- <th>GP</th> -->
                    <th>Price</th>
                    <th>CSR Price</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <!-- <th><select class="filter-select"><option value="">All</option></select></th> -->
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>  
</div>  
<script>
    $(document).ready(function () {
        var table = $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('variantprices.allvariantprice') }}",
            columns: [
                { data: 'brand_name', name: 'brands.brand_name' },
                { data: 'model_line', name: 'master_model_lines.model_line' },
                { 
            data: 'name', 
            name: 'varaints.name',
            render: function(data, type, row) {
                return '<a href="#" onclick="openModal(' + row.varaints_id + ')" style="text-decoration: underline;">' + data + '</a>';
            }
        },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                {
                    data: 'minimum_commission', 
                    name: 'vehicles.minimum_commission', 
                    render: function (data, type, row) {
                        var displayValue = data === null || data == 0 ? '' : data;
                        return `<input type="text" class="editable-minimum_commission" data-varaint-id="${row.varaints_id}" data-int-colour="${row.int_colour}" data-ex-colour="${row.ex_colour}" value="${displayValue}" />`;
                    }
                },
                // {
                //     data: 'gp', 
                //     name: 'vehicles.gp', 
                //     render: function (data, type, row) {
                //         var displayValue = data === null || data == 0 ? '' : data;
                //         return `<input type="text" class="editable-gp" data-varaint-id="${row.varaints_id}" data-int-colour="${row.int_colour}" data-ex-colour="${row.ex_colour}" value="${displayValue}" />`;
                //     }
                // },
                {
                    data: 'price', 
                    name: 'vehicles.price', 
                    render: function (data, type, row) {
                        var displayValue = data === null || data == 0 ? '' : data;
                        var originalValue = data === null || data == 0 ? '0' : data;
                        return `<input type="text" class="editable-price" data-varaint-id="${row.varaints_id}" data-int-colour="${row.int_colour}" data-ex-colour="${row.ex_colour}" data-original-value="${originalValue}" value="${displayValue}" />`;
                    }
                },
                {
                    data: 'csr_price', 
                    name: 'vehicles.csr_price', 
                    render: function (data, type, row) {
                        var displayValue = data === null || data == 0 ? '' : parseFloat(data).toLocaleString('en-US', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 2
                        });
                        var originalValue = data === null || data == 0 ? '0' : data;
                        return `<input type="text" class="editable-csr_price" data-varaint-id="${row.varaints_id}" data-int-colour="${row.int_colour}" data-ex-colour="${row.ex_colour}" data-original-value="${originalValue}" value="${displayValue}" />`;
                    }
                },
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<button class="btn btn-xs btn-primary update-prices-btn" 
                                data-varaint-id="${row.varaints_id}" 
                                data-int-colour="${row.int_colour}" 
                                data-ex-colour="${row.ex_colour}"
                                disabled>
                                Update
                                </button>`;
                    }
                },
            ],
            pageLength: -1,
            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var select = $(column.header()).find('select');
                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="'+d+'">'+d+'</option>');
                    });
                    select.select2({
                        width: '100%'
                    });
                    select.on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });
                });
            }
        });

        // Allow only positive numbers in Price field (no negative signs)
        $('#dtBasicExample1').on('input', 'input.editable-price', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            var varaints_id = $(this).data('varaint-id');
            var int_colour = $(this).data('int-colour');
            var ex_colour = $(this).data('ex-colour');
            checkUpdateButton(varaints_id, int_colour, ex_colour);
        });
        $('#dtBasicExample1').on('input', 'input.editable-minimum_commission', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        $('#dtBasicExample1').on('input', 'input.editable-csr_price', function () {
            let value = $(this).val().replace(/[^0-9.]/g, '');
            if (value) {
                let parts = value.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                $(this).val(parts.join('.'));
            }
            var varaints_id = $(this).data('varaint-id');
            var int_colour = $(this).data('int-colour');
            var ex_colour = $(this).data('ex-colour');
            checkUpdateButton(varaints_id, int_colour, ex_colour);
        });

        // Allow only positive numbers and % sign in GP field (no negative signs)
        $('#dtBasicExample1').on('input', 'input.editable-gp', function () {
            this.value = this.value.replace(/[^0-9%]/g, '');
        });

        // Store the current field data for the modal
        var currentFieldData = {};
        var originalPrice = null; // Store original price for comparison
        

        function saveField(varaints_id, int_colour, ex_colour, fieldName, newValue, reason = '', notifyDepartments = false) {
            // Remove any commas from price before sending
            if (fieldName === 'price') {
                newValue = newValue.replace(/,/g, ''); // Remove commas
            }

            console.log('saveField called with:', {
                varaints_id: varaints_id,
                int_colour: int_colour,
                ex_colour: ex_colour,
                field: fieldName,
                value: newValue,
                reason: reason,
                notify_departments: notifyDepartments,
                notify_departments_type: typeof notifyDepartments
            });
            
            // Additional debugging for price updates
            if (fieldName === 'price' || fieldName === 'csr_price') {
                console.log('Price field update details:', {
                    field: fieldName,
                    value: newValue,
                    timestamp: new Date().toISOString()
                });
            }
            // Always make the AJAX call if we reach this point (price has changed)
            $.ajax({
                url: "{{ route('variantprices.allvariantpriceupdate') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    varaints_id: varaints_id,
                    int_colour: int_colour,
                    ex_colour: ex_colour,
                    field: fieldName,
                    value: newValue,
                    reason: reason,
                    notify_departments: notifyDepartments
                },
                success: function(response) {
                    console.log('AJAX Success:', response);
                    console.log('notifyDepartments in success:', notifyDepartments);
                    
                    // Update the specific field value in the table without full reload
                    var fieldInput = $(`input.editable-${fieldName}[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
                    var formattedValue = parseFloat(response.new_value || newValue).toLocaleString();
                    fieldInput.val(formattedValue);
                    fieldInput.data('original-value', response.new_value || newValue);
                    
                    // Update button status after successful update
                    checkUpdateButton(varaints_id, int_colour, ex_colour);
                    
                    // Show success message based on notification preference
                    var message = notifyDepartments ? 
                        'Price updated successfully and notification sent to departments!' : 
                        'Price updated successfully!';
                    console.log('Showing success message:', message);
                    var alert = alertify.alert(message, function () {
                        // No need to reload the page
                    }).set({title:"Success"});
                },
                error: function(xhr) {
                    console.log('AJAX Error:', xhr);
                    console.log('Error status:', xhr.status);
                    console.log('Error response:', xhr.responseText);
                    if (xhr.status === 422) {
                        const response = xhr.responseJSON;
                        
                        // Check if it's a CSR price validation error
                        if (response.requires_csr_price) {
                            var confirm = alertify.alert(response.error, function (e) {
                                // Focus on the CSR price field for this variant
                                var csrPriceInput = $(`input.editable-csr_price[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
                                csrPriceInput.focus();
                            }).set({title:"CSR Price Required"});
                        } else if (response.field && response.error) {
                            // Handle field-specific validation errors (like price must be > 0)
                            var fieldName = response.field.replace('_', ' ');
                            var confirm = alertify.alert(response.error, function (e) {
                                // Focus on the specific field that has the error
                                var fieldInput = $(`input.editable-${response.field}[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
                                fieldInput.focus();
                            }).set({title: fieldName + " Validation Error"});
                        } else {
                            // Handle other validation errors
                            const errors = response.errors;
                            let errorMessages = '';
                            for (let field in errors) {
                                errorMessages += errors[field].join(', ') + '\n';
                            }
                            var confirm = alertify.confirm(''+errorMessages+'',function (e) {
                            }).set({title:"Validation Error"});
                        }
                    } else {
                        var confirm = alertify.confirm('Something went wrong',function (e) {
                        }).set({title:"Something went wrong"});
                    }
                }
            }); 
           
        }

        // Function to check if update button should be enabled
        function checkUpdateButton(varaints_id, int_colour, ex_colour) {
            var priceInput = $(`input.editable-price[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
            var csrPriceInput = $(`input.editable-csr_price[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
            var updateBtn = $(`button.update-prices-btn[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
            
            var priceValue = priceInput.val().replace(/[^0-9]/g, '');
            var csrPriceValue = csrPriceInput.val().replace(/[^0-9]/g, '');
            var priceNumeric = parseFloat(priceValue) || 0;
            var csrPriceNumeric = parseFloat(csrPriceValue) || 0;
            
            // Get original values
            var originalPrice = parseFloat(priceInput.data('original-value') || '0') || 0;
            var originalCsrPrice = parseFloat(csrPriceInput.data('original-value') || '0') || 0;
            
            // Check if both fields have valid values and at least one has changed
            var priceChanged = priceNumeric !== originalPrice;
            var csrPriceChanged = csrPriceNumeric !== originalCsrPrice;
            var bothValid = priceNumeric > 0 && csrPriceNumeric > 0;
            var hasChanges = priceChanged || csrPriceChanged;
            
            if (bothValid && hasChanges) {
                updateBtn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
            } else {
                updateBtn.prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
            }
        }

        // Function to show price change reason modal
        function showPriceChangeModal(varaints_id, int_colour, ex_colour, fieldName, newValue) {
            currentFieldData = {
                varaints_id: varaints_id,
                int_colour: int_colour,
                ex_colour: ex_colour,
                field: fieldName,
                value: newValue
            };
            
            // Clear previous reason
            $('#priceChangeReason').val('');
            
            // Show modal
            $('#priceChangeReasonModal').modal('show');
        }

        // Handle confirm price change button
        $('#confirmPriceChange').on('click', function() {
            console.log('Confirm price change button clicked');
            var reason = $('#priceChangeReason').val().trim();
            console.log('Reason provided:', reason);
            
            // Validate reason is not empty
            if (reason === '') {
                alert('Please provide a reason for the price change.');
                return;
            }
            
            // Close modal
            $('#priceChangeReasonModal').modal('hide');
            
            // Update both price and CSR price
            updateBothPrices(currentFieldData, reason);
        });
        
        // Function to update both prices
        function updateBothPrices(data, reason) {
            var priceChanged = data.price !== data.original_price;
            var csrPriceChanged = data.csr_price !== data.original_csr_price;
            
            if (priceChanged && csrPriceChanged) {
                // Both changed, show notification dialog only for price (not CSR price)
                var dialog = alertify.confirm(
                    'Both Price and CSR Price have been updated. Do you want to notify departments about the Price change? (CSR Price updates will not trigger notifications)'
                );
                dialog.set({ title: "Notify Departments?", labels: { ok: "Notify", cancel: "Don't Notify" } });
                dialog.set('onok', function(){
                    // Update price with notification enabled
                    updatePriceField(data.varaints_id, data.int_colour, data.ex_colour, 'price', data.price, reason, true);
                    // Update CSR price without notification (always false)
                    updatePriceField(data.varaints_id, data.int_colour, data.ex_colour, 'csr_price', data.csr_price, reason, false);
                });
                dialog.set('oncancel', function(){
                    // Update price without notification
                    updatePriceField(data.varaints_id, data.int_colour, data.ex_colour, 'price', data.price, reason, false);
                    // Update CSR price without notification (always false)
                    updatePriceField(data.varaints_id, data.int_colour, data.ex_colour, 'csr_price', data.csr_price, reason, false);
                });
            } else if (priceChanged) {
                // Only price changed
                updatePriceField(data.varaints_id, data.int_colour, data.ex_colour, 'price', data.price, reason, false);
            } else if (csrPriceChanged) {
                // Only CSR price changed - no notification dialog
                updatePriceField(data.varaints_id, data.int_colour, data.ex_colour, 'csr_price', data.csr_price, reason, false);
            }
        }
        
        // Function to update a single price field
        function updatePriceField(varaints_id, int_colour, ex_colour, field, value, reason, notifyDepartments) {
            saveField(varaints_id, int_colour, ex_colour, field, value, reason, notifyDepartments);
        }

// Handling the GP input blur event
$('#dtBasicExample1').on('blur', 'input.editable-gp', function () {
    var $this = $(this);
    var newValue = $this.val();
    var isPercentage = newValue.includes('%');
    var numericValue = parseFloat(newValue.replace('%', ''));

    if (isPercentage && (numericValue > 100 || numericValue < 0)) {
        alert('GP as a percentage cannot be more than 100% or less than 0%');
        $this.val(''); // Reset to empty if invalid
    } else if (isPercentage && !isNaN(numericValue)) {
        $this.val(Math.floor(numericValue) + '%'); // Ensure value is floored and has %
    } else if (!isNaN(numericValue)) {
        $this.val(Math.floor(numericValue)); // Allow non-percentage values, floored
    }

    // Save the updated GP value
    saveField($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'), 'gp', $this.val());
});

// Handling the Price input blur event
$('#dtBasicExample1').on('blur', 'input.editable-price', function () {
    var $this = $(this);
    var newValue = $this.val().replace(/[^0-9]/g, ''); // Ensure only digits are kept

    // If new value is empty, set it to 0 for comparison
    if (newValue === '') {
        newValue = '0';
    } else {
        newValue = parseInt(newValue).toLocaleString(); // Format number with commas
    }

    $this.val(newValue); // Set the formatted value
    
    // Check update button status
    checkUpdateButton($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'));
});
$('#dtBasicExample1').on('blur', 'input.editable-minimum_commission', function () {
    var $this = $(this);
    var newValue = $this.val().replace(/[^0-9]/g, ''); // Ensure only digits are kept
    var originalValue = $this.data('original-value') || '0'; // Get original value

    if (newValue !== '') {
        newValue = parseInt(newValue).toLocaleString(); // Format number with commas
    }

    $this.val(newValue); // Set the formatted value

    // Only process if there's an actual value change
    var numericNewValue = parseFloat(newValue.replace(/,/g, '')) || 0;
    var numericOriginalValue = parseFloat(originalValue.replace(/,/g, '')) || 0;
    
    if (numericNewValue !== numericOriginalValue) {
        // Validate that minimum commission is not 0 or empty
        if (numericNewValue <= 0) {
            alert('Minimum Commission cannot be 0 or empty. Please enter a valid value.');
            // Reset to original value
            var originalFormattedValue = numericOriginalValue === 0 ? '' : numericOriginalValue.toLocaleString();
            $this.val(originalFormattedValue);
            return;
        }
        
        // Value has changed and is valid, save directly without reason modal
        saveField($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'), 'minimum_commission', newValue.replace(/,/g, ''), '', false);
    }
    // If no change, do nothing
});

$('#dtBasicExample1').on('blur', 'input.editable-csr_price', function () {
    var $this = $(this);
    var rawValue = $this.val().replace(/,/g, ''); // Remove commas but keep decimal points
    
    if (rawValue !== '') {
        var numericValue = parseFloat(rawValue);
        if (!isNaN(numericValue)) {
            var formattedValue = numericValue.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
            $this.val(formattedValue);
        }
    }

    // Check update button status
    checkUpdateButton($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'));
});

// Handle update button click
$('#dtBasicExample1').on('click', 'button.update-prices-btn', function () {
    var $this = $(this);
    var varaints_id = $this.data('varaint-id');
    var int_colour = $this.data('int-colour');
    var ex_colour = $this.data('ex-colour');
    
    var priceInput = $(`input.editable-price[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
    var csrPriceInput = $(`input.editable-csr_price[data-varaint-id="${varaints_id}"][data-int-colour="${int_colour}"][data-ex-colour="${ex_colour}"]`);
    
    var priceValue = priceInput.val().replace(/[^0-9]/g, '');
    var csrPriceValue = csrPriceInput.val().replace(/,/g, ''); // Remove commas but keep decimal points
    var originalPrice = priceInput.data('original-value') || '0';
    var originalCsrPrice = csrPriceInput.data('original-value') || '0';
    
    // Store the data for the modal
    currentFieldData = {
        varaints_id: varaints_id,
        int_colour: int_colour,
        ex_colour: ex_colour,
        price: priceValue,
        csr_price: csrPriceValue,
        original_price: originalPrice,
        original_csr_price: originalCsrPrice
    };
    
    // Clear previous reason
    $('#priceChangeReason').val('');
    
    // Update modal title
    $('#priceChangeReasonModalLabel').text('Update Prices - Please provide reason');
    
    // Show modal
    $('#priceChangeReasonModal').modal('show');
});
    });
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
</script>
@endsection