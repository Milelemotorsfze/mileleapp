@extends('layouts.table')
<head>
    <style>
        th {
            font-size:12px!important;
        }
        td {
            font-size:12px!important;
        }
        .widthinput {
            height:32px!important;
        }
        .error
        {
        color: #FF0000;
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
        <h4 class="card-title">Penalized BOE Info</h4>
    </div>

    <div class="card-body">
        @if (Session::has('error'))
            <div class="alert alert-danger" >
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
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
                            <th>Penalty Start</th>
                            <th>Excess Days</th>
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
                                <select class="column-filter form-control" id="days-late-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(isset($datas) && count($datas) > 0)
                            @foreach($datas as $data)
                                @if($data->declaration_date != '')
                                    @php
                                        $daysDifference = '';
                                        $thirtiethDay = \Carbon\Carbon::parse($data->declaration_date)->addDays(29);
                                        $today = \Carbon\Carbon::today();
                                        $daysDifference = $thirtiethDay->diffInDays($today, false) + 1;
                                    @endphp
                                @endif
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

                                                @if ($canAddPenalty)
                                                    <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatePenaltyModal_{{$data->id}}">
                                                        <i class="fa fa-file" aria-hidden="true"></i> Update Penalty Info
                                                    </a>
                                                @endif
                                            </ul>
                                        </div> 
                                        <div class="modal fade" id="updatePenaltyModal_{{$data->id}}" tabindex="-1" aria-labelledby="updatePenaltyModalLabel_{{$data->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update BOE Penalty Info for {{$data->boe ?? ''}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="docStatusForm_{{$data->id}}" method="POST" enctype="multipart/form-data" action="{{ route('penalty.storeOrUpdate') }}" onsubmit="return validateForm({{ $data->id }})">
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="row">
                                                                <!-- Invoice Date -->
                                                                <div class="col-4">
                                                                    <input hidden name="wo_boe_id_{{$data->id}}" value="{{ $data->id ?? '' }}">
                                                                    <input hidden name="wo_boe_{{$data->id}}" value="{{ $data->boe ?? '' }}">
                                                                    <span class="error">* </span>
                                                                    <label for="invoice_date{{$data->id}}" class="form-label" style="font-size: 14px;">Invoice Date:</label>
                                                                    <input type="date" 
                                                                        class="form-control widthinput" 
                                                                        id="invoice_date_{{ $data->id }}" 
                                                                        name="invoice_date_{{$data->id}}" 
                                                                        min="{{ \Carbon\Carbon::parse($data->declaration_date)->addDays(29)->format('Y-m-d') }}" 
                                                                        max="{{ now()->format('Y-m-d') }}"
                                                                        data-declaration-date="{{ $data->declaration_date }}">
                                                                    <span id="invoice_dateError_{{$data->id}}" class="text-danger"></span>
                                                                </div>

                                                                <!-- Invoice Number -->
                                                                <div class="col-4">
                                                                    <span class="error">* </span>
                                                                    <label for="invoice_number_{{$data->id}}" class="form-label" style="font-size: 14px;">Invoice Number:</label>
                                                                    <input type="numbers" class="form-control widthinput" id="invoice_number_{{ $data->id }}" name="invoice_number_{{$data->id}}" placeholder="Enter Invoice Number">                                                                    
                                                                    <span id="invoice_numberError_{{$data->id}}" class="text-danger"></span>
                                                                </div>
                                                             
                                                                <!-- Penalty Amount -->
                                                                <div class="col-4">
                                                                    <span class="error">* </span>
                                                                    <label for="penalty_amount_{{$data->id}}" class="form-label" style="font-size: 14px;">Penalty Amount:</label>
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control widthinput" id="penalty_amount_{{ $data->id }}" name="penalty_amount_{{$data->id}}" placeholder="Enter Penalty Amount" 
                                                                        value="{{ $penalty ?? '' }}" step="0.01" min="0">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                        </div>
                                                                    </div>
                                                                    <span id="penalty_amountError_{{$data->id}}" class="text-danger"></span>
                                                                </div>
                                                                <!-- Penalty Type -->
                                                                <div class="col-8">
                                                                    <span class="error">* </span>
                                                                    <label for="penalty_type_{{$data->id}}" class="form-label mt-2" style="font-size: 14px;">Penalty Type:</label>
                                                                    <select class="form-control select2" id="penalty_type_{{$data->id}}" name="penalty_type_{{$data->id}}[]" style="width: 100%;" multiple>
                                                                        <option value="">Select Penalty Type</option>
                                                                        @foreach ($penaltyTypes as $penaltyType)
                                                                            <option value="{{ $penaltyType->id }}">{{ $penaltyType->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <span id="penalty_typeError_{{$data->id}}" class="text-danger"></span>
                                                                </div>
                                                                <!-- Payment Receipt -->
                                                                <div class="col-4">
                                                                    <label for="payment_receipt_{{$data->id}}" class="form-label mt-2" style="font-size: 14px;">Payment Receipt/Penalty Confirmation :</label>
                                                                    <input type="file" class="form-control widthinput" id="payment_receipt_{{ $data->id }}" name="payment_receipt_{{$data->id}}" placeholder="Enter Payment Receipt">
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
                                    <td>{{ $data->boe ?? '' }}</td>
                                    <td>{{ $data->declaration_number ?? '' }}</td>
                                    <td>@if($data->declaration_date != ''){{ \Carbon\Carbon::parse($data->declaration_date)->format('d M Y') }}@endif</td>
                                    <td>@if($data->declaration_date != ''){{ \Carbon\Carbon::parse($data->declaration_date)->addDays(29)->format('d M Y') }}@endif</td>
                                    <td>{{ $daysDifference }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center">No data history available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(isset($data) && isset($data->id))
    <script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Penalty Type",
            allowClear: true,
            dropdownParent: $('#updatePenaltyModal_{{$data->id}}') // Replace with your actual modal ID
        });
        @if(isset($datas) && count($datas) > 0)
            // Initialize DataTable since $datas has rows
            var table = $('.my-datatableclass').DataTable({  // Use DataTable() here
                paging: true,
                info: true,
                lengthChange: true,
            });

            // Initialize Select2 for multi-select filters
            $('#so-filter, #wo-filter, #boe-filter, #declaration-number-filter, #days-late-filter').select2({
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
            populateDropdown(4, '#declaration-number-filter');
            populateDropdown(7, '#days-late-filter');

            // Apply multi-select filter for each dropdown
            $('#so-filter, #wo-filter, #boe-filter, #declaration-number-filter, #days-late-filter').on('change', function() {
                var columnIndex = $(this).parent().index();
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                table.column(columnIndex).search(searchValue, true, false).draw();  // Use table.column() directly
            });


            // Clear all filters on button click
            $('#clear-filters').click(function() {
                $('#so-filter, #wo-filter, #boe-filter, #declaration-number-filter, #days-late-filter').val(null).trigger('change');
                table.search('').columns().search('').draw();
            });
        @else
            console.log("No data available to initialize DataTable.");
        @endif 
    });
    $(document).on('shown.bs.modal', function (e) {
        const modalId = $(e.target).attr('id');
        $(`#${modalId} .select2`).select2({
            placeholder: "Select Penalty Type",
            allowClear: true,
            dropdownParent: $(`#${modalId}`) // Dynamically reference the modal ID
        });
    });
    function validateForm(id) {
        var invoiceDate = document.getElementById('invoice_date_' + id).value;
        var invoiceNumber = document.getElementById('invoice_number_' + id).value;
        var penaltyAmount =  document.getElementById('penalty_amount_' + id).value;
        var penaltyType =  document.getElementById('penalty_type_' + id).value;

        // Check for empty fields
        if (!invoiceDate) {
            document.getElementById('invoice_dateError_' + id).textContent = 'Invoice Date is required.';
            return false;
        } else {
            document.getElementById('invoice_dateError_' + id).textContent = '';
        }
        if (!invoiceNumber || !/^[1-9][0-9]*$/.test(invoiceNumber)) {
            document.getElementById('invoice_numberError_' + id).textContent = 'Please enter a valid positive integer.';
            return false;
        }else {
            document.getElementById('invoice_numberError_' + id).textContent = '';      
        }
        if (!penaltyAmount) {
            document.getElementById('penalty_amountError_' + id).textContent = 'Penalty Amount is required.';
            return false;
        } else {
            document.getElementById('penalty_amountError_' + id).textContent = '';          
        }
        if (!penaltyType) {
            document.getElementById('penalty_typeError_' + id).textContent = 'Penalty Type is required.';
            return false;
        } else {
            document.getElementById('penalty_typeError_' + id).textContent = '';          
        }
        return true; // Submit if all validations pass
    }
</script>
@endif
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


            

