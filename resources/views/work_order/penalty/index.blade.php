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
<body>
    <div class="card-header">
        <h4 class="card-title">Penalized Vehicles Info</h4>
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
                            <th>Penalty Start</th>
                            <th>Excess Days</th>
                            <th>Total Penalty(AED)</th>
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
                        @endif
                    </thead>
                    <tbody>
                        @if(isset($datas) && count($datas) > 0)
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
                                                    <form id="docStatusForm_{{$data->id}}" method="POST" enctype="multipart/form-data" action="{{ route('penalty.storeOrUpdate') }}" onsubmit="return validateForm({{ $data->id }})">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" value="{{$data->id}}" name="wo_vehicle_id_{{$data->id}}">
                                                            <div class="row">
                                                                <!-- Payment Date -->
                                                                <div class="col-2">
                                                                    <label for="payment_date{{$data->id}}" class="form-label" style="font-size: 14px;">Payment Date:</label>
                                                                    <input type="date" 
                                                                        class="form-control" 
                                                                        id="payment_date{{ $data->id }}" 
                                                                        name="payment_date_{{$data->id}}" 
                                                                        value="{{ now()->format('Y-m-d') }}" 
                                                                        min="{{ \Carbon\Carbon::parse($data->woBoe->declaration_date)->addDays(29)->format('Y-m-d') }}" 
                                                                        max="{{ now()->format('Y-m-d') }}"
                                                                        data-declaration-date="{{ $data->woBoe->declaration_date }}">
                                                                    <span id="payment_dateError_{{$data->id}}" class="text-danger"></span>
                                                                </div>

                                                                <!-- Excess Days -->
                                                                <div class="col-3">
                                                                    <input hidden value="{{ $data->id ?? '' }}">
                                                                    <label for="excess_days_{{$data->id}}" class="form-label" style="font-size: 14px;">Excess Export Days Post Expiry:</label>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" id="excess_days_{{ $data->id }}" name="excess_days_{{$data->id}}" 
                                                                            placeholder="Enter Excess Export Days Post Expiry" value="{{ $daysDifference ?? '' }}" min="1" oninput="validity.valid||(value='');" step="1" readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text widthinput" id="basic-addon2">Days</span>
                                                                        </div>
                                                                    </div>
                                                                    <span id="excess_daysError_{{$data->id}}" class="text-danger"></span>
                                                                </div>

                                                                <!-- Total Penalty Amount -->
                                                                <div class="col-2">
                                                                    <label for="total_penalty_amount_{{$data->id}}" class="form-label" style="font-size: 14px;">Total Penalty Amount:</label>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" id="total_penalty_amount_{{ $data->id }}" name="total_penalty_amount_{{$data->id}}" 
                                                                        placeholder="Enter Total Penalty Amount" value="{{ $penalty ?? '' }}" step="0.01" min="0" readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                        </div>
                                                                    </div>
                                                                    <span id="total_penalty_amountError_{{$data->id}}" class="text-danger"></span>
                                                                </div>

                                                                <!-- Amount Paid -->
                                                                <div class="col-2">
                                                                    <label for="amount_paid_{{$data->id}}" class="form-label" style="font-size: 14px;">Amount Paid:</label>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control" id="amount_paid_{{ $data->id }}" name="amount_paid_{{$data->id}}" placeholder="Enter Amount Paid" 
                                                                        value="{{ $penalty ?? '' }}" step="0.01" min="0">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                        </div>
                                                                    </div>
                                                                    <span id="amount_paidError_{{$data->id}}" class="text-danger"></span>
                                                                </div>

                                                                <!-- Payment Receipt -->
                                                                <div class="col-3">
                                                                    <label for="payment_receipt_{{$data->id}}" class="form-label" style="font-size: 14px;">Payment Receipt/Penalty Confirmation :</label>
                                                                    <input type="file" class="form-control" id="payment_receipt_{{ $data->id }}" name="payment_receipt_{{$data->id}}" placeholder="Enter Payment Receipt">
                                                                    <span id="payment_receiptError_{{$data->id}}" class="text-danger"></span>
                                                                </div>
                                                                <!-- Remarks -->
                                                                <div class="col-12 mt-2">
                                                                    <label for="remarks_{{$data->id}}" class="form-label" style="font-size: 14px;">Remarks:</label>
                                                                    <textarea class="form-control" id="remarks_{{$data->id}}" name="remarks" rows="3" style="font-size: 14px;"></textarea>
                                                                    <span id="remarksError_{{$data->id}}" class="text-danger"></span>
                                                                </div>
                                                            </div></br>                                                                                                                                                             
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
                                <td colspan="9" class="text-center">No data history available.</td>
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
            $('#so-filter, #wo-filter, #boe-filter, #vin-filter, #days-late-filter').select2({
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
            populateDropdown(4, '#vin-filter');
            populateDropdown(7, '#days-late-filter');

            // Apply multi-select filter for each dropdown
            $('#so-filter, #wo-filter, #boe-filter, #vin-filter, #days-late-filter').on('change', function() {
                var columnIndex = $(this).parent().index();
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                table.column(columnIndex).search(searchValue, true, false).draw();  // Use table.column() directly
            });

            $('#penalty-amount-filter').on('keyup change', function() {
                table.column($(this).parent().index()).search(this.value).draw();  // Use table.column() directly
            });

            // Clear all filters on button click
            $('#clear-filters').click(function() {
                $('#penalty-amount-filter').val('').trigger('change');
                $('#so-filter, #wo-filter, #boe-filter, #vin-filter, #days-late-filter').val(null).trigger('change');
                table.search('').columns().search('').draw();
            });
        @else
            console.log("No data available to initialize DataTable.");
        @endif        
    });
</script>
</body>
@endsection

