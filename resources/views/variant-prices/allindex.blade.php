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
                        return `<input type="text" class="editable-price" data-varaint-id="${row.varaints_id}" data-int-colour="${row.int_colour}" data-ex-colour="${row.ex_colour}" value="${displayValue}" />`;
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
        });
        $('#dtBasicExample1').on('input', 'input.editable-minimum_commission', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Allow only positive numbers and % sign in GP field (no negative signs)
        $('#dtBasicExample1').on('input', 'input.editable-gp', function () {
            this.value = this.value.replace(/[^0-9%]/g, '');
        });

        // Store the current field data for the modal
        var currentFieldData = {};

        function saveField(varaints_id, int_colour, ex_colour, fieldName, newValue, reason = '') {
            // Remove any commas from price before sending
            if (fieldName === 'price') {
                newValue = newValue.replace(/,/g, ''); // Remove commas
            }

            console.log('Sending data:', {
                varaints_id: varaints_id,
                int_colour: int_colour,
                ex_colour: ex_colour,
                field: fieldName,
                value: newValue,
                reason: reason
            });
            if(newValue.length > 0) {
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
                        reason: reason
                    },
                    success: function(response) {
                        table.ajax.reload(null, false); // Reload the table data
                        // Show success message
                        var confirm = alertify.confirm('Price updated successfully and notification sent to departments!',function (e) {
                        }).set({title:"Success"});
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        for (let field in errors) {
                            errorMessages += errors[field].join(', ') + '\n';
                        }
                        var confirm = alertify.confirm(''+errorMessages+'',function (e) {
                        }).set({title:"Validation Error"});
                        table.ajax.reload(null, false);
                    } else {
                        var confirm = alertify.confirm('Something went wrong',function (e) {
                        }).set({title:"Something went wrong"});
                        table.ajax.reload(null, false);
                    }
                        console.log('Error:', xhr.responseText);
                    }
                });
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
            var reason = $('#priceChangeReason').val().trim();
            
            // Validate reason is not empty
            if (reason === '') {
                alert('Please provide a reason for the price change.');
                return;
            }
            
            // Close modal
            $('#priceChangeReasonModal').modal('hide');
            
            // Proceed with the price update
            saveField(
                currentFieldData.varaints_id,
                currentFieldData.int_colour,
                currentFieldData.ex_colour,
                currentFieldData.field,
                currentFieldData.value,
                reason
            );
        });

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

    if (newValue !== '') {
        newValue = parseInt(newValue).toLocaleString(); // Format number with commas
    }

    $this.val(newValue); // Set the formatted value

    // Show modal for price change reason
    if (newValue !== '') {
        showPriceChangeModal($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'), 'price', newValue.replace(/,/g, ''));
    }
});
$('#dtBasicExample1').on('blur', 'input.editable-minimum_commission', function () {
    var $this = $(this);
    var newValue = $this.val().replace(/[^0-9]/g, ''); // Ensure only digits are kept

    if (newValue !== '') {
        newValue = parseInt(newValue).toLocaleString(); // Format number with commas
    }

    $this.val(newValue); // Set the formatted value

    // Show modal for price change reason
    if (newValue !== '') {
        showPriceChangeModal($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'), 'minimum_commission', newValue.replace(/,/g, ''));
    }
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