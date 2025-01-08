@extends('layouts.table')
<head>
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
@php
    $canViewPenaltyInfo = Auth::user()->hasPermissionForSelectedRole(['view-vehicle-penalty-report']);
    $canViewWODetails = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details','export-cnf-wo-details','current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
    $canAddPenalty = Auth::user()->hasPermissionForSelectedRole(['can-update-vehicle-penalty']);
@endphp
@if ($canViewPenaltyInfo)
<body>
    <div class="card-header">
        <h4 class="card-title">No Penalties BOE Info</h4>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="table-responsive dragscroll">
                <table class="table table-striped table-editable table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Action</th>
                            <th>SO Number</th>
                            <th>WO Number</th>
                            <th>BOE Number</th>
                            <th>Declaration Number</th>
                            <th>Declaration Date</th>
                        </tr>
                        @if(isset($datas) && count($datas) > 0)
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
                                <select class="column-filter form-control" id="declaration-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <!-- <input type="text" id="declaration-date-filter" placeholder="Search Declaration Date" class="column-filter form-control" /> -->
                            </th>                          
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(isset($datas) && count($datas) > 0)
                            @foreach($datas as $data)
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                            <i class="fa fa-bars" aria-hidden="true"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-start">
                                                @if ($canViewWODetails)
                                                <li>
                                                    <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-info" href="{{route('work-order.show',$data->workOrder->id ?? '')}}">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> View Details
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>                
                                    </td>
                                    <td>{{ $data->workOrder->so_number ?? '' }}</td>
                                    <td>{{ $data->workOrder->wo_number ?? '' }}</td>
                                    <td>{{ $data->boe ?? '' }}</td>
                                    <td>{{ $data->declaration_number ?? '' }}</td>
                                    <td>@if($data->declaration_date != ''){{ \Carbon\Carbon::parse($data->declaration_date)->format('d M Y') }}@endif</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No data history available.</td>
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
            $('#so-filter, #wo-filter, #boe-filter, #declaration-filter').select2({
                placeholder: "Select filter",
                allowClear: true
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
            populateDropdown(4, '#declaration-filter');

            // Apply multi-select filter for each dropdown
            $('#so-filter, #wo-filter, #boe-filter, #declaration-filter').on('change', function() {
                var columnIndex = $(this).parent().index();
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                table.column(columnIndex).search(searchValue, true, false).draw();  // Use table.column() directly
            });

            // Clear all filters on button click
            $('#clear-filters').click(function() {
                $('#so-filter, #wo-filter, #boe-filter, #declaration-filter').val(null).trigger('change');
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


            

