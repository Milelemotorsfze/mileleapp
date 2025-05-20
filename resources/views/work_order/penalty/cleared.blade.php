@extends('layouts.table')
<head>
    <style>
        th {
            font-size:12px!important;
        }
        td {
            font-size:12px!important;
        }
        .btn-style {
            font-size:0.7rem!important;
            line-height: 0.1!important;
        }

        select.form-control {
            min-width: 150px;
        }
        .select2-container {
            min-width: 150px !important;
        }
        .clear-all-filters-section {
            width: 150px !important;  
            min-width: 150px !important;
            max-width: 150px !important;
            padding-right: 25px !important;
        }

    </style>
</head>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-vehicle-penalty-report']);
@endphp
@if ($hasPermission)
<body>
    <div class="card-header">
        <h4 class="card-title">Cleared Penalties Info</h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="table-responsive dragscroll">
                <table class="table table-striped table-editable table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Sl No</th>
                            <th>SO Number</th>
                            <th>WO Number</th>
                            <th>BOE Number</th>
                            <th>Declaration Number</th>
                            <th>Declaration Date</th>
                            <th>Penalty Start</th>
                            <th>Invoice Date</th>
                            <th>Invoice Number</th>
                            <th>Penalty Type</th>
                            <th>Penalty Amount(AED)</th>
                            <th>Payment Receipt</th>
                            <th>Remark</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                        @if(isset($datas) && count($datas) > 0)
                        <tr>
                            <th class="clear-all-filters-section"><button id="clear-filters" class="btn btn-info btn-sm">Clear All Filters</button></th>
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
                                <select class="column-filter form-control" id="declaration-number-filter" multiple="multiple">
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
                                <!-- Invoice Date -->
                            </th>
                            <th>
                                <select class="column-filter form-control" id="invoice-number-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <!-- Penalty Type -->
                            </th>
                            <th>
                                <!-- Penalty Amount(AED) -->
                            </th>
                            <th>
                                <!-- Payment Receipt -->
                            </th>
                            <th>
                                <!-- Remark -->
                            </th>
                            <th>
                                <select class="column-filter form-control" id="created-by-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <!-- Created At -->
                            </th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(isset($datas) && count($datas) > 0)
                            <div hidden>{{$i=0;}}</div>
                            @foreach($datas as $data)
                                @if($data->declaration_date != '')
                                    @php
                                        $daysDifference = '';
                                        $thirtiethDay = \Carbon\Carbon::parse($data->declaration_date)->addDays(29);
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
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $data->workOrder->so_number ?? '' }}</td>
                                    <td>{{ $data->workOrder->wo_number ?? '' }}</td>
                                    <td>{{ $data->boe ?? '' }}</td>
                                    <td>{{ $data->declaration_number ?? '' }}</td>
                                    <td>@if($data->declaration_date != ''){{ \Carbon\Carbon::parse($data->declaration_date)->format('d M Y') }}@endif</td>
                                    <td>@if($data->declaration_date != ''){{ \Carbon\Carbon::parse($data->declaration_date)->addDays(29)->format('d M Y') }}@endif</td>
                                    <td>@if($data->penalty->invoice_date != ''){{ \Carbon\Carbon::parse($data->invoice_date)->format('d M Y') }}@endif</td>
                                    <td>{{ $data->penalty->invoice_number ?? '' }}</td>
                                    <td>
                                        @if($data->penalty && $data->penalty->penaltyTypes)
                                            {{ $data->penalty->penaltyTypes
                                                ->sortBy(fn($penaltyType) => $penaltyType->penaltyTypesName->name) // Sort by name
                                                ->map(fn($penaltyType) => $penaltyType->penaltyTypesName->name) // Extract names
                                                ->join(', ') }} <!-- Join with commas -->
                                        @else
                                            No Penalty Types
                                        @endif
                                    </td>
                                    <td>{{ $data->penalty->penalty_amount ?? '' }}</td>
                                    @component('components.view-download-buttons', ['filePath' => 'work_order/boe_penalty_receipt/', 'fileName' => $data->penalty->payment_receipt])@endcomponent
                                    <td>{{ $data->penalty->remarks ?? '' }}</td>
                                    <td>{{ $data->penalty->createdUser->name ?? '' }}</td>
                                    <td>@if($data->penalty->created_at != ''){{ \Carbon\Carbon::parse($data->penalty->created_at)->format('d M Y') }}@endif</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="14" class="text-center">No data history available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        @if(isset($datas) && count($datas) > 0)
            // Initialize DataTable since $datas has rows
            var table = $('.my-datatableclass').DataTable({  // Use DataTable() here
                paging: true,
                info: true,
                lengthChange: true,
            });
            // Initialize Select2 for multi-select filters
            $('#so-filter, #wo-filter, #boe-filter, #declaration-number-filter, #invoice-number-filter, #created-by-filter').select2({
                placeholder: "Select filter",
                allowClear: true,
                width: 'resolve'
            });

            // Function to populate multi-select dropdowns with unique, sorted data
            function populateDropdown(columnIndex, dropdownId) {
                var uniqueValues = new Set();
                table.column(columnIndex).data().each(function(value) { // Directly use table.column()
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
            populateDropdown(4, '#declaration-number-filter');
            populateDropdown(8, '#invoice-number-filter');
            populateDropdown(12, '#created-by-filter');

            // Apply multi-select filter for each dropdown
            $('#so-filter, #wo-filter, #boe-filter, #declaration-number-filter, #invoice-number-filter, #created-by-filter').on('change', function() {
                var columnIndex = $(this).parent().index();
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                table.column(columnIndex).search(searchValue, true, false).draw();  // Use table.column() directly
            });

            // Clear all filters on button click
            $('#clear-filters').click(function() {
                $('#so-filter, #wo-filter, #boe-filter, #declaration-number-filter, #invoice-number-filter, #created-by-filter').val(null).trigger('change');
                table.search('').columns().search('').draw();
            });
        @else
            console.log("No data available to initialize DataTable.");
        @endif        
    });
</script>
</body>
@else
    <div class="card-header">
        <p class="card-title">Sorry! You don't have permission to access this page.</p>
        <div class="d-flex justify-content-between">
            <a class="btn btn-sm btn-info" href="/">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard
            </a>
            <a class="btn btn-sm btn-info" href="{{ url()->previous() }}">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page
            </a>
        </div>
    </div>
@endif
@endsection

