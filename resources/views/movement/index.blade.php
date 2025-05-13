@extends('layouts.table')
<style>
    #dtBasicExample2 {
        width: 100%;
    }
    table.dataTable thead select {
    width: 100%;
    padding: 4px;
    box-sizing: border-box;
}
.select2-container {
    width: 100% !important;
}

.select2-dropdown {
    border-radius: 4px;
}
</style>
@section('content')
@php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('View-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
    <div class="card-header">
        <h4 class="card-title">
            Movements Info
        </h4>
        @can('View-daily-movemnets')
        @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('edit-daily-movemnets');
                    @endphp
                    @if ($hasPermission)
                    <a class="btn btn-sm btn-primary float-end" href="{{ route('netsuitegdn.addingnetsuitegdn') }}" text-align: right>
                    <i class="fa fa-arrow-right" aria-hidden="true"></i> Netsuite GDN
                </a>
<p class="float-end">&nbsp;&nbsp;&nbsp;</p>
      <a class="btn btn-sm btn-success float-end" href="{{ route('movement.create') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Movement Transaction
      </a>
      <div class="clearfix"></div>
      @endif
      <br>
      <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Movement Transaction</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">All Vehicle Movements</a>
      </li>
    </ul>
    </div>
    @endcan
    @can('View-daily-movemnets')
    <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
    <div class="card-body">
    @if ($errors->has('source_name'))
            <div id="error-message" class="alert alert-danger">
                {{ $errors->first('source_name') }}
            </div>
        @endif

        @if (session('error'))
            <div id="error-message" class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive" >
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                <th>Movement Batch</th>
                <th>Vehicle Quantity</th>
                <th>Created By</th>
                <th>Created Date</th>
                <th>Movement Date</th>
                <th>Action</th>
                        </tr>
                        <tr>
                <th><select id="filter-movement-batch"><option value="">All</option></select></th>
                <th><select id="filter-vehicle-quantity"><option value="">All</option></select></th>
                <th><select id="filter-created-by"><option value="">All</option></select></th>
                <th><select id="filter-created-date"><option value="">All</option></select></th>
                <th><select id="filter-movement-date"><option value="">All</option></select></th>
                <th></th>
            </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($movementreference as $movementreference)
                        <tr data-id="1">
                        <td class="refernacenumber">MOV - {{ $movementreference->id }}</td>
                        @php
                        $vehicles = DB::table('movements')->where('reference_id', $movementreference->id)->count();
                        @endphp
                        <td>{{$vehicles}}</td>
                        @php
                        $created_bys = DB::table('users')->where('id', $movementreference->created_by)->first();
                        $created_by = $created_bys->name ?? '';
                        @endphp
                        <td>{{ $created_by }}</td>
                        <td class="createdDated">{{ date('d-M-Y', strtotime($movementreference->created_at)) }}</td>
                        <td class="createdDated">{{ date('d-M-Y', strtotime($movementreference->date)) }}</td>
                        <td><a title="Details" data-placement="top" class="btn btn-sm btn-primary" href="{{ route('movement.show', $movementreference->id) }}"><i class="fa fa-car" aria-hidden="true"></i> View Details</a></td>
                      </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        </div>  
      </div> 
      @endcan
      @can('View-daily-movemnets')
      <div class="tab-content">
    <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
        <div class="table-responsive" style="height: 74vh;">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
            <thead style="position: sticky; top: 0; background-color: #dcdde3">
            <tr>
                <th>Creation Date</th>
                <th>Movement Date</th>
                <th>VIN</th>
                <th>TRIM</th>
                <th>From</th>
                <th>To</th>
                <th>SO Number</th>
                <th>PO Number</th>
                <th>Inspection No</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
           </div>
        </div>
    </div>
</div>
      @endcan
      <script>
    setTimeout(function() {
        $('#error-message').fadeOut('slow');
    }, 2000);
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 2000);

    $(document).ready(function () {
        $.fn.dataTable.ext.order['mov-number-pre'] = function(data) {
            var match = data.match(/^MOV - (\d+)/);
            return match ? parseInt(match[1], 10) : -1;
        };

        var dataTable = $('#dtBasicExample1').DataTable({
            pageLength: 10,
            columnDefs: [
                { type: 'date', targets: $('.createdDated').index() },
                { type: 'mov-number', targets: $('.refernacenumber').index() }
            ]
        });

        // Event listeners for hardcoded filters in the header
        $('#filter-movement-batch, #filter-vehicle-quantity, #filter-created-by, #filter-created-date, #filter-movement-date').on('change', function() {
            var column = dataTable.column($(this).parent().index());
            column.search(this.value).draw();
        });
    });
</script>
<script>
    $(document).ready(function() {
        var table = $('#dtBasicExample2').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ route('movement.index') }}", // Change this to your actual route

            // Disable sorting for all columns
            "ordering": false,

            // Define column-specific filters
            "columns": [
                { data: 'created_at', name: 'created_at' },
                { data: 'date', name: 'date' },
                { data: 'vin', name: 'vin' },
                { data: 'model_detail', name: 'model_detail' },
                { data: 'from_name', name: 'from_name' },
                { data: 'to_name', name: 'to_name' },
                { data: 'so_number', name: 'so_number' },
                { data: 'po_number', name: 'po_number' },
                { data: 'custom_inspection_number', name: 'custom_inspection_number' },
                { data: 'remarks', name: 'remarks' }
            ]
        });

        // Add individual column filters
        $('#dtBasicExample2 thead tr').clone(true).appendTo('#dtBasicExample2 thead');
        $('#dtBasicExample2 thead tr:eq(1) th').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });
    });
    $(document).ready(function () {
    // Custom sorting for dates in the format dd-MMM-yyyy
    $.fn.dataTable.ext.type.order['custom-date-pre'] = function (data) {
        var dateParts = data.split('-');
        var day = parseInt(dateParts[0], 10);
        var month = new Date(Date.parse(dateParts[1] +" 1, 2022")).getMonth() + 1;
        var year = parseInt(dateParts[2], 10);
        return new Date(year, month - 1, day).getTime();
    };

    // Custom numeric sorting for MOV - XXX format
    $.fn.dataTable.ext.type.order['mov-numeric-pre'] = function (data) {
        var num = data.match(/\d+/); // Extract the numeric part from the string
        return num ? parseInt(num[0], 10) : 0; // Convert to an integer for sorting
    };

    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#dtBasicExample1')) {
        $('#dtBasicExample1').DataTable().destroy();
    }

    // Initialize DataTables with custom sorting applied to specific columns
    var table = $('#dtBasicExample1').DataTable({
        order: [[0, 'desc']], // 0 = first column, 'desc' = descending
        columnDefs: [
            { type: 'mov-numeric', targets: 0 }, // Apply MOV - XXX sorting to the first column
            { type: 'custom-date', targets: [3, 4] } // Apply custom date sorting to the 4th and 5th columns (index 3 and 4)
        ],
        initComplete: function () {
            // Populate dropdowns with unique values
            this.api().columns().every(function () {
                var column = this;
                var select = $('select', column.header());
                select.empty();
                select.append('<option value="">All</option>');

                // Retrieve unique values for each column and append to the select element
                column.data().unique().sort().each(function (d, j) {
                    if (d) { // Ensure that the value is not empty or undefined
                        select.append('<option value="' + d + '">' + d + '</option>');
                    }
                });

                // Initialize Select2 on the dropdown
                select.select2({
                    placeholder: "Select a value",
                    allowClear: true
                });
            });
        }
    });

    // Apply the filter with exact match
    $('#dtBasicExample1 thead').on('change', 'select', function () {
        var columnIndex = $(this).parent().index();
        var searchTerm = this.value;

        if (searchTerm !== "") {
            // Use a regular expression to match exact value
            table.column(columnIndex).search('^' + searchTerm + '$', true, false).draw();
        } else {
            // Clear the search if no value is selected
            table.column(columnIndex).search('').draw();
        }
    });
});
</script>

            </div>
            @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
            @endsection