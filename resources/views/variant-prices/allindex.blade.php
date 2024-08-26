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
<div class="table-responsive" style="height: 74vh;">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
        <thead class="bg-soft-secondary" style="position: sticky; top: 0; z-index: 1000;">
                <tr>
                    <th>Brand</th>
                    <th>Model Line</th>
                    <th>Variant</th>
                    <th>Interior Colour</th>
                    <th>Exterior Colour</th>
                    <th>GP</th>
                    <th>Price</th>
                </tr>
                <tr>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
                    <th><select class="filter-select"><option value="">All</option></select></th>
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
                { data: 'name', name: 'varaints.name' },
                { data: 'interior_color', name: 'int_color.name' },
                { data: 'exterior_color', name: 'ex_color.name' },
                {
                    data: 'gp', 
                    name: 'vehicles.gp', 
                    render: function (data, type, row) {
                        console.log(row);
                        var displayValue = data === null || data == 0 ? '' : data;
                        return `<input type="text" class="editable-gp" data-varaint-id="${row.varaints_id}" data-int-colour="${row.int_colour}" data-ex-colour="${row.ex_colour}" value="${displayValue}" />`;
                    }
                },
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

        // Allow only positive numbers and % sign in GP field (no negative signs)
        $('#dtBasicExample1').on('input', 'input.editable-gp', function () {
            this.value = this.value.replace(/[^0-9%]/g, '');
        });

        function saveField(varaints_id, int_colour, ex_colour, fieldName, newValue) {
    // Remove any commas from price before sending
    if (fieldName === 'price') {
        newValue = newValue.replace(/,/g, ''); // Remove commas
    }

    console.log('Sending data:', {
        varaints_id: varaints_id,
        int_colour: int_colour,
        ex_colour: ex_colour,
        field: fieldName,
        value: newValue
    });

    $.ajax({
        url: "{{ route('variantprices.allvariantpriceupdate') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            varaints_id: varaints_id,
            int_colour: int_colour,
            ex_colour: ex_colour,
            field: fieldName,
            value: newValue
        },
        success: function(response) {
            console.log('Response:', response);
            table.ajax.reload(null, false); // Reload the table data
        },
        error: function(xhr) {
            console.log('Error:', xhr.responseText);
        }
    });
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

    if (newValue !== '') {
        newValue = parseInt(newValue).toLocaleString(); // Format number with commas
    }

    $this.val(newValue); // Set the formatted value

    // Save the updated Price value, removing commas before sending
    saveField($this.data('varaint-id'), $this.data('int-colour'), $this.data('ex-colour'), 'price', newValue.replace(/,/g, ''));
});
    });
</script>
@endsection