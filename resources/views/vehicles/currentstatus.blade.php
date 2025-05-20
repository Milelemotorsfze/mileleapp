@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .card-header {
        background-color: #007bff; /* Primary color for header */
        color: white;
    }

    .card-header .row div {
        padding: 5px; /* Padding for header cells */
        border-right: 1px solid #dee2e6; /* Light border for separation */
    }

    .card-header .row div:last-child {
        border-right: none; /* Remove border for the last cell */
    }

    .card-body .row {
        /* border-bottom: 1px solid #dee2e6;  */
        margin: 0;
    }

    .card-body .row div {
        padding: 1px; /* Padding for data rows */
    }

    .card-body .row:last-child {
        border-bottom: none; /* Remove border for the last row */
    }
</style>
@section('content')
  <div class="card-header">
    <h4 class="card-title">
     Vehicle Status
    </h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
        <!-- Search Input -->
        <div class="row mb-4">
            <div class="col-4">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Enter the Vehicle VIN" required>
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Data Display -->
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <div class="row">
            <div class="col-3"><strong>Previous Status</strong></div>
            <div class="col-3"><strong>Current Status</strong></div>
            <div class="col-3"><strong>Next Stage</strong></div>
        </div>
    </div>
    <div id="dataRows" class="card-body p-0">
        <!-- Dynamic Data Rows will be appended here -->
    </div>
</div>
    </div>
    <!-- Loading spinner -->
    <div id="loadingSpinner" class="text-center d-none">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
  </div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    $('#searchButton').on('click', function() {
        let searchQuery = $('#searchInput').val();
        let _token = $('meta[name="csrf-token"]').attr('content');

        if (!searchQuery) {
            alert('Please enter a search term.');
            return;
        }

        $('#loadingSpinner').removeClass('d-none');

        $.ajax({
            url: "{{ route('vehicles.statussearch') }}", // Ensure this route matches the one defined in web.php
            type: "POST",
            data: {
                search: searchQuery,
                _token: _token
            },
            success: function(response) {
                console.log(response);
                let dataRows = $('#dataRows');
                dataRows.empty();
                if(response.data.length > 0) {
                    response.data.forEach(function(row) {
                        dataRows.append(`
                            <div class="row">
                                <div class="col-3">${row.previous_status}</div>
                                <div class="col-3">${row.current_status}</div>
                                <div class="col-3">${row.next_stage}</div>
                            </div>
                        `);
                    });
                } else {
                    dataRows.append(`
                        <div class="row">
                            <div class="col-12 text-center py-3">No results found</div>
                        </div>
                    `);
                }

                $('#loadingSpinner').addClass('d-none');
            },
            error: function() {
                alert('An error occurred while fetching data. Please try again.');
                $('#loadingSpinner').addClass('d-none');
            }
        });
    });
});
</script>
@endpush