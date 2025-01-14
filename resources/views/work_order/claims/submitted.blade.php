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
@endphp
@if ($canViewClaimInfo)
<body>
    <div class="card-header">
        <h4 class="card-title">Claim Submitted BOE Info</h4>
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
                            <th>BOE</th>
                            <th>Claim Date</th>
                            <th>Claim Reference Number</th>
                            <th>Submitted By</th>
                            <th>Submitted At</th>
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
                            <th></th>
                            <th>
                                <select class="column-filter form-control" id="claim-reference-number-filter" multiple="multiple">
                                    <!-- Options will be dynamically added via JS -->
                                </select>
                            </th>
                            <th>
                                <select class="column-filter form-control" id="submitted-by-filter" multiple="multiple">
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
                                                    <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateClaimStatusModal_{{$data->id}}">
                                                        <i class="fa fa-file" aria-hidden="true"></i> Update Claim Status
                                                    </a>
                                                @endif
                                            </ul>
                                        </div> 
                                        <div class="modal fade" id="updateClaimStatusModal_{{$data->id}}" tabindex="-1" aria-labelledby="updateClaimStatusModalLabel_{{$data->id}}" aria-hidden="true">
                                            <div class="modal-dialog"> <!-- Add modal-dialog here -->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateClaimStatusModalLabel_{{$data->id}}">Update BOE Claim Status For {{$data->boe ?? ''}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="docStatusForm_{{$data->id}}" method="POST" enctype="multipart/form-data" action="{{ route('claim.updateStatus') }}" onsubmit="return validateForm({{ $data->id }})">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" value="{{$data->id}}" name="wo_boe_id_{{$data->id}}">
                                                            <div class="row">
                                                                <!-- Use d-flex to align * and label in the same line -->
                                                                <div class="d-flex align-items-center mb-2" style="gap: 5px;">
                                                                    <span class="error">*</span>
                                                                    <label for="status_{{$data->id}}" class="form-label mb-0">BOE Claim Status:</label>
                                                                </div>
                                                                <div class="d-flex align-items-center" style="gap: 10px;"> <!-- Add d-flex and gap for spacing -->
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
                                    <td>@if($data->claim->claim_date != ''){{ \Carbon\Carbon::parse($data->claim->claim_date)->format('d M Y') }}@endif</td>
                                    <td>{{ $data->claim->claim_reference_number ?? '' }}</td>
                                    <td>{{ $data->claim->createdUser->name ?? '' }}</td>
                                    <td>@if($data->claim->created_at != ''){{ \Carbon\Carbon::parse($data->claim->created_at)->format('d M Y') }}@endif</td>
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
            $('#so-filter, #wo-filter, #boe-filter, #claim-reference-number-filter, #submitted-by-filter').select2({
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
            populateDropdown(5, '#claim-reference-number-filter');
            populateDropdown(6, '#submitted-by-filter');
            // Apply multi-select filter for each dropdown
            $('#so-filter, #wo-filter, #boe-filter, #claim-reference-number-filter, #submitted-by-filter').on('change', function() {
                var columnIndex = $(this).parent().index();
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                table.column(columnIndex).search(searchValue, true, false).draw();  // Use table.column() directly
            });

            // Clear all filters on button click
            $('#clear-filters').click(function() {
                $('#so-filter, #wo-filter, #boe-filter, #claim-reference-number-filter, #submitted-by-filter').val(null).trigger('change');
                table.search('').columns().search('').draw();
            });
        @else
            console.log("No data available to initialize DataTable.");
        @endif        
    });
    function validateForm(id) { 
        var radioStatus = document.querySelector('input[name="status_' + id + '"]:checked');
        console.log(radioStatus);
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

