@extends('layouts.table')
<head>
    <meta charset="UTF-8">
    <!-- Load jQuery before DataTables and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        th {
            font-size:12px!important;
        }
        td {
            font-size:12px!important;
        }
    </style>
</head>
@section('content')
<body>
    <div class="card-header">
        <h4 class="card-title">Vehicle Penalty Info</h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped table-editable table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Action</th>
                            <th>SO Number</th>
                            <th>WO Number</th>
                            <th>BOE Number</th>
                            <th>VIN Number</th>
                            <th>Declaration Date</th>
                            <th>Penalty Start Date</th>
                            <th>Number Of Days Late For Export</th>
                            <th>Total Penalty Amount (AED)</th>
                        </tr>
                        <tr>
                            <th><button id="clear-filters" class="btn btn-info btn-sm">Clear All Filters</button></th>
                            <th>
                                <select class="column-filter form-control" id="so-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <select class="column-filter form-control" id="wo-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <select class="column-filter form-control" id="boe-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <select class="column-filter form-control" id="vin-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <!-- <input type="text" id="declaration-date-filter" placeholder="Search Declaration Date" class="column-filter form-control" /> -->
                            </th>
                            <th>
                                <!-- <input type="text" id="penalty-start-date-filter" placeholder="Penalty Start Date" class="column-filter form-control" /> -->
                            </th>
                            <th>
                                <select class="column-filter form-control" id="days-late-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th><input type="text" placeholder="Search Total Penalty" class="column-filter form-control" id="penalty-amount-filter"/></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($datas) > 0)
                        @foreach($datas as $data)
                            @if($data->woBoe->declaration_date != '')
                                @php
                                    $daysDifference = '';
                                    $thirtiethDay = \Carbon\Carbon::parse($data->woBoe->declaration_date)->addDays(29);
                                    $today = \Carbon\Carbon::today();
                                    $daysDifference = $thirtiethDay->diffInDays($today, false) + 1;
                                @endphp
                            @endif
                            @if(isset($daysDifference))
                                @php
                                    $penalty = $daysDifference * 200;
                                @endphp
                            @endif
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-start">
                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details','export-cnf-wo-details','current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
                                            @endphp
                                            @if ($hasPermission)
                                            <li>
                                                <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-info" href="{{route('work-order.show',$data->workOrder->id ?? '')}}">
                                                <i class="fa fa-eye" aria-hidden="true"></i> View Details
                                                </a>
                                            </li>
                                            @endif

                                            @php
                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-update-vehicle-penalty']);
                                            @endphp
                                            @if ($hasPermission)
                                                <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatePenaltyModal_{{$data->id}}">
                                                    <i class="fa fa-file" aria-hidden="true"></i> Update Penalty Info
                                                </a>
                                            @endif
                                        </ul>
                                    </div> 
                                    <div class="modal fade" id="updatePenaltyModal_{{$data->id}}" tabindex="-1" aria-labelledby="updatePenaltyModalLabel_{{$data->id}}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl"> <!-- Add modal-dialog here -->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updatePenaltyModalLabel_{{$data->id}}">Update Vehicle Penalty Info For {{$data->vin ?? ''}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form id="docStatusForm_{{$data->id}}" method="POST" enctype="multipart/form-data" action="{{ route('penalty.store') }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <input hidden value="{{ $data->id ?? '' }}">
                                                                <label for="excess_days_{{$data->id}}" class="form-label" style="font-size: 14px;">Excess Export Days Post Expiry:</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control declaration-number" id="excess_days_{{ $data->id }}" name="excess_days_{{$data->id}}" 
                                                                        placeholder="Enter Excess Export Days Post Expiry" value="{{ $daysDifference ?? ''}}" min="1" oninput="validity.valid||(value='');" step="1" readonly>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                                    </div>
                                                                </div>
                                                                <span id="excess_daysError_{{$data->id}}" class="text-danger"></span>
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="total_penalty_amount_{{$data->id}}" class="form-label" style="font-size: 14px;">Total Penalty Amount:</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control declaration-number" id="total_penalty_amount_{{ $data->id }}" name="total_penalty_amount_{{$data->id}}" 
                                                                    placeholder="Enter Total Penalty Amount" value="{{ $penalty ?? '' }}" step="0.01" min="0" readonly>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                <span id="total_penalty_amountError_{{$data->id}}" class="text-danger"></span>
                                                            </div>                                                       
                                                            <div class="col-3">
                                                                <label for="amount_paid_{{$data->id}}" class="form-label" style="font-size: 14px;">Amount Paid:</label>
                                                                <div class="input-group">
                                                                    <input type="number" class="form-control declaration-number" id="amount_paid_{{ $data->id }}" name="amount_paid_{{$data->id}}" placeholder="Enter Amount Paid" 
                                                                    value="{{ $penalty ?? '' }}" step="0.01" min="0">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                    </div>
                                                                </div>
                                                                <span id="amount_paidError_{{$data->id}}" class="text-danger"></span>
                                                            </div>
                                                            <div class="col-3">
                                                                <label for="payment_receipt_{{$data->id}}" class="form-label" style="font-size: 14px;">Payment Receipt:</label>
                                                                <input type="file" class="form-control declaration-number" id="payment_receipt_{{ $data->id }}" name="payment_receipt_{{$data->id}}" placeholder="Enter Payment Receipt">
                                                                <span id="payment_receiptError_{{$data->id}}" class="text-danger"></span>
                                                            </div>
                                                            <div class="col-12">
                                                                <label for="remarks_{{$data->id}}" class="form-label" style="font-size: 14px;">Remarks:</label>
                                                                <textarea class="form-control" id="remarks_{{$data->id}}" name="remarks" rows="3" style="font-size: 14px;"></textarea>
                                                                <span id="remarksError_{{$data->id}}" class="text-danger"></span>
                                                            </div>
                                                        </div>                                                   
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>                   
                                </td>
                                <td>{{ $data->workOrder->so_number ?? '' }}</td>
                                <td>{{ $data->workOrder->wo_number ?? '' }}</td>
                                <td>{{ $data->woBoe->boe ?? '' }}</td>
                                <td>{{ $data->vin ?? '' }}</td>
                                <td>@if($data->woBoe->declaration_date != ''){{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->format('d M Y') }}@endif</td>
                                <td>@if($data->woBoe->declaration_date != ''){{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->addDays(29)->format('d M Y') }}@endif</td>
                                <td>{{ $daysDifference }}</td>
                                <td>{{ $penalty }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No data history available.</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('.my-datatableclass').DataTable({
            paging: true,
            info: true,
            lengthChange: true,
        });

        // Initialize Select2 for multi-select filters
        $('#so-filter, #wo-filter, #boe-filter, #vin-filter, #days-late-filter').select2({
            placeholder: "Select filter",
            allowClear: true
        });

        // Function to populate multi-select dropdowns with unique, sorted data
        function populateDropdown(columnIndex, dropdownId) {
            var uniqueValues = new Set();
            table.column(columnIndex).data().each(function(value) {
                if (value !== '') {
                    uniqueValues.add(value);
                }
            });

            uniqueValues = Array.from(uniqueValues).sort(); // Sort the values in ascending order

            uniqueValues.forEach(function(value) {
                $(dropdownId).append('<option value="' + value + '">' + value + '</option>');
            });
        }

        // Populate filters
        populateDropdown(1, '#so-filter');
        populateDropdown(2, '#wo-filter');
        populateDropdown(3, '#boe-filter');
        populateDropdown(4, '#vin-filter');
        populateDropdown(7, '#days-late-filter');

        // Apply multi-select filter for each dropdown
        $('#so-filter, #wo-filter, #boe-filter, #vin-filter, #days-late-filter').on('change', function() {
            var columnIndex = $(this).parent().index();
            var selectedOptions = $(this).val();
            var searchValue = selectedOptions ? selectedOptions.join('|') : '';
            table.column(columnIndex).search(searchValue, true, false).draw();
        });

        $('#penalty-amount-filter').on('keyup change', function() {
            table.column($(this).parent().index()).search(this.value).draw();
        });

        // // Get min and max dates for Declaration Date and Penalty Start Date
        // function getMinMaxDates(columnIndex) {
        //     var dates = table.column(columnIndex).data().filter(function(value) {
        //         return value !== '';
        //     }).map(function(value) {
        //         return moment(value, 'DD/MM/YYYY');
        //     }).sort();
        //     return {
        //         minDate: dates[0] || moment(), // Default to today if no dates found
        //         maxDate: dates[dates.length - 1] || moment(),
        //     };
        // }

        // // Date range picker for Declaration Date
        // var declarationDates = getMinMaxDates(5); // Declaration Date is in column 5
        // $('#declaration-date-filter').daterangepicker({
        //     autoUpdateInput: false,
        //     minDate: declarationDates.minDate,
        //     maxDate: declarationDates.maxDate,
        //     locale: {
        //         format: 'DD/MM/YYYY',
        //         cancelLabel: 'Clear'
        //     }
        // });

        // // On date selection for Declaration Date
        // $('#declaration-date-filter').on('apply.daterangepicker', function(ev, picker) {
        //     $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        //     table.draw();
        // });

        //         // Clear date filter for Declaration Date
        //         $('#declaration-date-filter').on('cancel.daterangepicker', function(ev, picker) {
        //     $(this).val('');  // Clear the input field
        //     table.draw();  // Redraw the DataTable to remove the date filter
        // });

        // // Date range picker for Penalty Start Date
        // var penaltyStartDates = getMinMaxDates(6); // Penalty Start Date is in column 6
        // $('#penalty-start-date-filter').daterangepicker({
        //     autoUpdateInput: false,
        //     minDate: penaltyStartDates.minDate,
        //     maxDate: penaltyStartDates.maxDate,
        //     locale: {
        //         format: 'DD/MM/YYYY',
        //         cancelLabel: 'Clear'
        //     }
        // });

        // // On date selection for Penalty Start Date
        // $('#penalty-start-date-filter').on('apply.daterangepicker', function(ev, picker) {
        //     $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        //     table.draw();  // Redraw the DataTable to apply the date filter
        // });

        // // Clear date filter for Penalty Start Date
        // $('#penalty-start-date-filter').on('cancel.daterangepicker', function(ev, picker) {
        //     $(this).val('');  // Clear the input field
        //     table.draw();  // Redraw the DataTable to remove the date filter
        // });

        // Clear all filters on button click
        $('#clear-filters').click(function() {
            // Clear text inputs
            $('#penalty-amount-filter').val('').trigger('change');
            // Clear Select2 dropdowns
            $('#so-filter').val(null).trigger('change');
            $('#wo-filter').val(null).trigger('change');
            $('#boe-filter').val(null).trigger('change');
            $('#vin-filter').val(null).trigger('change');
            $('#days-late-filter').val(null).trigger('change');
            // Clear date range picker
            // $('#date-time-filter').val('');
            // $('#date-time-filter').data('daterangepicker').setStartDate(moment().startOf('day'));
            // $('#date-time-filter').data('daterangepicker').setEndDate(moment().endOf('day'));
            // $('#date-time-filter').trigger('cancel.daterangepicker');

            // Redraw the table
            table.search('').columns().search('').draw();
        });
    });
    function submiDtocStatus(workOrderId, woNumber) {
        document.querySelectorAll('.text-danger').forEach(function(span) {
            span.textContent = ''; // Clear all error messages
        });
        const selectedStatus = document.querySelector('#updatePenaltyModal_' + workOrderId + ' input[name="docStatus_' + workOrderId + '"]:checked');
        if (!selectedStatus) {
            alertify.error('Please select a documentation status.');
            return; // Exit if no status is selected
        }
        let valid = true; // Assume form is valid
        if (selectedStatus.value === 'Ready') {
            document.querySelectorAll('.boe-set').forEach((boeSet, index) => {
                const declarationNumberField = document.getElementById('declarationNumber_'+workOrderId+'_' + index);
                // console.log()
                if (declarationNumberField) {
                    const declarationNumber = declarationNumberField.value; 
                    if (declarationNumber && (!/^\d{13}$/.test(declarationNumber) || parseInt(declarationNumber) <= 0)) {
                        document.getElementById('declarationNumberError_'+workOrderId+'_' + index).textContent = 'Please enter a valid 13-digit positive Declaration Number.';
                        valid = false;
                    }
                }
            });
        }
        if (!valid) {
            return; // Stop submission if validation fails
        }
        const boeData = [];
        document.querySelectorAll('.boe-set').forEach((boeSet, index) => {
            const boeNumberField = document.getElementById(`boeNumber_${workOrderId}_${index}`);
            const boeField = document.getElementById(`boe_${workOrderId}_${index}`);
            const declarationNumberField = document.getElementById(`declarationNumber_${workOrderId}_${index}`);
            const declarationDateField = document.getElementById(`declarationDate_${workOrderId}_${index}`);
            
            if (boeNumberField && declarationNumberField && declarationDateField) {
                boeData.push({                    
                    boe_number: boeField.value,  // BOE Number
                    boe: boeNumberField.value,  // BOE Number
                    declaration_number: declarationNumberField.value,  // Declaration Number (if provided)
                    declaration_date: declarationDateField.value  // Declaration Date
                });
            }
        });

        const comment = document.getElementById('docComment_' + workOrderId).value;

        // Display confirmation dialog
        alertify.confirm(
            'Confirmation Required',
            `Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus.value}?`,
            function() { // If the user clicks "OK"
                $.ajax({
                    url: '/update-wo-doc-status',
                    method: 'POST',
                    data: {
                        workOrderId: workOrderId,
                        status: selectedStatus.value,
                        comment: comment,
                        boeData: boeData,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $('#updatePenaltyModal_' + workOrderId).modal('hide');
                        location.reload(); // Reload the page after success
                    },
                    error: function(xhr) {
                        alertify.error('Failed to update status');
                    }
                });
            },
            function() { // If the user clicks "Cancel"
                alertify.error('Action canceled');
            }
        );
    }
</script>

</body>
@endsection

