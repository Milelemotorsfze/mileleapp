@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Ensure table rows do not wrap text */
table.dataTable {
    font-size: 12px; /* Decrease font size */
    white-space: nowrap; /* Prevent text from wrapping into multiple lines */
}
/* Reduce padding for table cells */
.table>tbody>tr>td, 
.table>tbody>tr>th, 
.table>tfoot>tr>td, 
.table>tfoot>tr>th, 
.table>thead>tr>td, 
.table>thead>tr>th {
    padding: 2px 3px; /* Decrease the padding */
    text-align: center;
    vertical-align: middle;
    white-space: nowrap; /* Prevent text from wrapping */
}
table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td
{
   padding: 1px; 
}
/* Reduce the height of the rows */
#dtBasicExample7 tbody tr {
    height: 20px; /* Set a smaller height for the rows */
}

/* Adjust the header row to reduce space */
#dtBasicExample7 thead th {
    padding: 4px 5px; /* Reduce padding in the header */
    font-size: 13px;  /* Slightly reduce the font size in the header */
    white-space: nowrap; /* Prevent header text from wrapping */
}
.table-responsive {
    overflow-x: auto; /* Enable horizontal scrolling if content overflows */
    white-space: nowrap; /* Prevent text wrapping in table cells */
}

/* Ensure the table container takes the full height available */
.table-responsive {
    height: 80vh;
    overflow-y: auto;
}
   .btn-outline-primary {
    margin-bottom: 5px;
    width: 100%;
}
.select2-container--default .select2-search--inline .select2-search__field {
    font-size: 12px !important; /* Adjust the font-size as per your needs */
    width: 100% !important;
}
/* Ensure the Select2 dropdown fits the column width */
table.dataTable thead th select {
    width: 100% !important; /* Ensures the select element fits the header width */
    min-width: 100%; /* Ensures it takes at least 100% width */
}

/* Ensure the Select2 dropdown fits the full header width when opened */
.select2-container {
    width: 100% !important; /* Ensures the container takes full width */
}

/* Ensure the dropdown itself is properly styled */
.select2-dropdown {
    width: auto !important; /* Let the dropdown size adjust dynamically */
    min-width: 100%; /* Ensure the dropdown is at least the width of the select element */
    box-sizing: border-box; /* Makes sure the padding is included in width */
}

/* Ensure proper spacing between dropdown options */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border: 1px solid #0056b3;
    padding: 0 5px;
    margin: 3px 5px 3px 0;
    color: black;
    font-size: 12px;
}

/* Highlight the selected items */
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff;
    color: white;
}
.dataTables_processing {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    margin-left: -50px;
    margin-top: -30px;
    background: url('https://logosbynick.com/wp-content/uploads/2021/01/animated-gif.gif') no-repeat center center;
    background-size: contain;
    z-index: 1100; /* Higher than the z-index of the <thead> */
    display: none;
}
#dtBasicExample3_processing {
    display: block;
}
#toggleButtonsRow th {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 4px;
    text-align: center;
}

.d-flex.mb-2 {
    margin-bottom: 10px;
}

#dtBasicExample3_wrapper .btn-danger,
#dtBasicExample3_wrapper .btn-success {
    margin-right: 10px;
}
#dtBasicExample6_wrapper .btn-danger,
#dtBasicExample6_wrapper .btn-success {
    margin-right: 10px;
}
#dtBasicExample7_wrapper .btn-danger,
#dtBasicExample7_wrapper .btn-success {
    margin-right: 10px;
}
#dtBasicExample8_wrapper .btn-danger,
#dtBasicExample8_wrapper .btn-success {
    margin-right: 10px;
}
  .text-container {
        display: inline-block; /* Inline block to handle overflow */
        max-width: 300px; /* Adjust this width as needed */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .read-more-btn {
        color: blue;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: underline;
    }
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
  #dtBasicExample1 tbody tr:hover {
    cursor: pointer;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
        white-space: nowrap; /* Prevent text from wrapping into two lines */
    text-overflow: ellipsis; /* Show ellipsis ("...") if the text overflows */
    overflow: hidden; /* Hide overflowed text */
    width: auto; /* Allow the header to take up available width */
    padding: 8px; /* Adjust padding for better alignment */
    vertical-align: middle; /* Vertically align the text in the middle */
    background-color: rgb(194, 196, 204)!important; /* Ensure sticky header color */
    position: sticky!important; /* Sticky header */
    top: 0;
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
    .nav-pills .nav-link {
      position: relative;
    }

    .badge-notification {
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(50%, -110%);
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
    }
    .comments-header {
    position: sticky;
    top: 0;
    background-color: #fff;
    z-index: 10;
    border-bottom: 1px solid #dee2e6;
}

.fixed-height {
    height: 280px; /* Adjust the height as needed */
    overflow-y: auto;
    border: 1px solid #dee2e6;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

.message-card, .message-reply {
    margin-bottom: 1rem;
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}

.message-card .card-body, .message-reply {
    padding: 1rem;
}

.message-reply {
    margin-left: 3rem;
    margin-top: 0.5rem;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.5rem;
}

.reply-input {
    margin-left: 3rem;
    margin-top: 0.5rem;
    position: relative;
}

.avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    font-size: 0.9rem;
}

.avatar-small {
    width: 20px;
    height: 20px;
    font-size: 0.7rem;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-name {
    margin-left: 5px;
}

.send-icon {
    position: absolute;
    right: 1px;
    bottom: 2px;
    border: none;
    background: none;
    font-size: 0.1rem;
    color: #28a745;
    cursor: pointer;
}

.send-icongt {
    position: absolute;
    right: 1px;
    bottom: 1px;
    border: none;
    background: none;
    font-size: 0.01rem;
    color: #28a745;
    cursor: pointer;
}
.message-input-wrapper, .reply-input-wrapper {
    position: relative;
}

.message-input-wrapper textarea, .reply-input-wrapper textarea {
    padding-right: 40px;
    width: 100%;
    box-sizing: border-box;
}
  </style>
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="card-body">
    <div class="table-responsive" style="height: 80vh;">
        <table id="dtBasicExample3" class="table table-striped table-editable table-edits table-bordered" style="width:100%;">
            <thead class="bg-soft-secondary" style="position: sticky; top: 0;">
                <tr>
                    <th>Lead Date</th>
                    <th>Selling Type</th>
                    <th>Customer Name</th>
                    <th>Customer Phone</th>
                    <th>Customer Email</th>
                    <th>Preferred Language</th>
                    <th>Location</th>
                    <th>Remarks</th>
                    <th>Created By</th>
                    <th>Assigned By</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rejectedLeads as $lead)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($lead->created_at)->format('d-M-Y') }}</td>
                        <td>{{ $lead->selling_type }}</td>
                        <td>
    <a href="{{ route('calls.leaddetailpage', $lead->customer_id) }}">
        {{ $lead->customer_name ? $lead->customer_name : '(Sample)' }}
    </a>
</td>
                        <td>{{ $lead->customer_phone }}</td>
                        <td>{{ $lead->customer_email }}</td>
                        <td>{{ $lead->preferred_language }}</td>
                        <td>{{ $lead->location }}</td>
                        <td>{{ $lead->remarks }}</td>
                        <td>{{ $lead->created_by_name }}</td>
                        <td>{{ $lead->assigned_by_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
<script>
    $(document).ready(function() {
        $('#dtBasicExample3').DataTable();
    });
</script>