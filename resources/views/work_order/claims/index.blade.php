@extends('layouts.table')
<head>
    <style>
        th {
            font-size:12px!important;
        }
        td {
            font-size:12px!important;
        }
        .error
        {
        color: #FF0000;
        }
    </style>
</head>
@section('content')
@php
    $canViewClaimInfo = Auth::user()->hasPermissionForSelectedRole(['can-view-vehicle-claims']);
    $canViewWODetails = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details','export-cnf-wo-details','current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
    $canAddClaim = Auth::user()->hasPermissionForSelectedRole(['can-update-vehicle-claims']);
    $canViewClaimLog = Auth::user()->hasPermissionForSelectedRole(['can-view-claim-log']);
@endphp
@if ($canViewClaimInfo)
<body>
    <div class="card-header">
        <h4 class="card-title">Claim Pending BOE Info</h4>
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
            <div class="table-responsive">
                <table class="table table-striped table-editable table-condensed my-datatableclass">
                    <thead style="background-color: #e6f1ff">
                        <tr>
                            <th>Action</th>
                            <th>SO Number</th>
                            <th>WO Number</th>
                            <th>BOE</th>
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
                                <select class="column-filter form-control" id="vin-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <select class="column-filter form-control" id="declaration-number-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th></th>
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

                                                @if ($canAddClaim)
                                                    <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateClaimModal_{{$data->id}}">
                                                        <i class="fa fa-file" aria-hidden="true"></i> Update Claim Info
                                                    </a>
                                                @endif
                                                <!-- @if ($canViewClaimLog)
                                                <li>
                                                    <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Claim Log" class="btn btn-sm btn-info" href="{{route('claim.log',$data->id ?? '')}}">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> Claim Log
                                                    </a>
                                                </li>
                                                @endif -->
                                                
                                            </ul>
                                        </div> 
                                        <div class="modal fade" id="updateClaimModal_{{$data->id}}" tabindex="-1" aria-labelledby="updateClaimModalLabel_{{$data->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl"> <!-- Add modal-dialog here -->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateClaimModalLabel_{{$data->id}}">Update Vehicle Claim Info For {{$data->boe ?? ''}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="docStatusForm_{{$data->id}}" method="POST" enctype="multipart/form-data" action="{{ route('claim.storeOrUpdate') }}" onsubmit="return validateForm({{ $data->id }})">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" value="{{$data->id}}" name="wo_boe_id_{{$data->id}}">
                                                            <div class="row">
                                                                <!-- Claim Date -->
                                                                <div class="col-4">
                                                                    <span class="error">* </span>
                                                                    <label for="claim_date{{$data->id}}" class="form-label">Claim Date:</label>
                                                                    <input type="date" class="form-control" id="claim_date{{ $data->id }}" name="claim_date_{{$data->id}}" 
                                                                        value="{{ now()->format('Y-m-d') }}">
                                                                    <span id="claim_dateError_{{$data->id}}" class="text-danger"></span>
                                                                </div>
                                                                <div class="col-4">
                                                                    <span class="error">* </span>
                                                                    <label for="claim_reference_number_{{ $data->id }}" class="form-label">Claim Reference Number:</label>
                                                                    <input type="text" class="form-control" id="claim_reference_number_{{ $data->id }}" name="claim_reference_number_{{ $data->id }}" pattern="\d*" placeholder="Enter Claim Reference Number" value="">
                                                                    <span id="claimReferenceNumber_Error_{{ $data->id }}" class="text-danger"></span>
                                                                </div>
                                                                <div class="col-4">
                                                                    <span class="error">* </span>
                                                                    <label for="status_{{$data->id}}" class="form-label">BOE Penalty Status :</label>
                                                                    <div class="d-flex align-items-center" style="gap: 10px;"> <!-- Add d-flex and gap for spacing -->
                                                                        <div class="form-check form-check-inline"> <!-- form-check-inline keeps items inline -->
                                                                            <input class="form-check-input" type="radio" name="status_{{$data->id}}" id="status_submitted_{{$data->id}}" value="Submitted">
                                                                            <label class="form-check-label" for="status_submitted_{{$data->id}}">Submitted</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="status_{{$data->id}}" id="status_approved_{{$data->id}}" value="Approved">
                                                                            <label class="form-check-label" for="status_approved_{{$data->id}}">Approved</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="status_{{$data->id}}" id="status_cancelled_{{$data->id}}" value="Cancelled">
                                                                            <label class="form-check-label" for="status_cancelled_{{$data->id}}">Cancelled</label>
                                                                        </div>
                                                                    </div>
                                                                    <span id="claimStatus_Error_{{ $data->id }}" class="text-danger"></span>
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
                                    <td>@if($data->declaration_date != ''){{\Carbon\Carbon::parse($data->declaration_date)->format('d M Y') ?? ''}}@endif</td>
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
            $('#so-filter, #wo-filter, #vin-filter, #declaration-number-filter').select2({
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
            populateDropdown(3, '#vin-filter');
            populateDropdown(4, '#declaration-number-filter');

            // Apply multi-select filter for each dropdown
            $('#so-filter, #wo-filter, #vin-filter, #declaration-number-filter').on('change', function() {
                var columnIndex = $(this).parent().index();
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                table.column(columnIndex).search(searchValue, true, false).draw();  // Use table.column() directly
            });

            // Clear all filters on button click
            $('#clear-filters').click(function() {
                $('#so-filter, #wo-filter, #vin-filter, #declaration-number-filter').val(null).trigger('change');
                table.search('').columns().search('').draw();
            });
        @else
            console.log("No data available to initialize DataTable.");
        @endif        
    });
    function validateForm(id) {
        var claimDate = document.getElementById('claim_date' + id).value;
        var claimReferenceNumber = document.getElementById('claim_reference_number_' + id).value;
        var radioStatus = document.querySelector('input[name="status_' + id + '"]:checked');
        
        // Check for empty fields
        if (!claimDate) {
            document.getElementById('claim_dateError_' + id).textContent = 'Claim Date is required.';
            return false;
        } else {
            document.getElementById('claim_dateError_' + id).textContent = '';
        }
        if (!claimReferenceNumber || !/^[1-9][0-9]*$/.test(claimReferenceNumber)) {
            document.getElementById('claimReferenceNumber_Error_' + id).textContent = 'Please enter a valid positive integer.';
            return false;
        } else {
            document.getElementById('claimReferenceNumber_Error_' + id).textContent = '';      
        }
        if (!radioStatus) {
            document.getElementById('claimStatus_Error_' + id).textContent = 'Please select a BOE Claim Status.';
            return false;
        } else {
            document.getElementById('claimStatus_Error_' + id).textContent = '';          
        }

        return true; // Submit if all validations pass
    }
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


            

