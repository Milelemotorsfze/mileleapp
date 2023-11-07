@extends('layouts.table')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
<div class="card-header">
    <h4 class="card-title">User Login Activity</h4>
</div>
<div class="card-body">
    @can('view-log-activity')
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-log-activity']);
    @endphp
    @if ($hasPermission)
    <div style="position: relative; width: 100%; height: 5vh;">
        <div id="reportrange" style="position: absolute; top: 10px; right: 10px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 280px; text-align: right;">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Login DateTime</th>
                    <th>IP</th>
                    <th>Login Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script>
        $(function () {
            var start = moment().subtract(6, 'days');
            var end = moment();
            var table = $('#dtBasicExample1').DataTable({
                "searching": true,
                "paging": true,
                "pageLength": 10,
            });

            function populateFilterDropdowns() {
                $('#dtBasicExample1 thead select').remove();
                table.columns().every(function () {
                    var column = this;
                    var columnIndex = column[0][0];
                    var columnName = $(column.header()).text().trim();
                    if (columnName) {
                        var select = $('<select class="form-control my-1"><option value="">All</option></select>')
                            .appendTo($(column.header()))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                table.column(columnIndex)
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    }
                });
            }
            function loadDataAndPopulateFilters(start, end) {
                $.ajax({
                    url: '{{ route('listUsersgetdata') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        start_date: start.format('YYYY-MM-DD'),
                        end_date: end.format('YYYY-MM-DD'),
                    },
                    success: function (response) {
    var data = response.data;
    if (Array.isArray(data)) {
        table.clear().draw();
        for (var i = 0; i < data.length; i++) {
            var row = data[i];
            var formattedDateTime = moment(row.created_at).format('YYYY-MM-DD HH:mm:ss');
var dateForURL = moment(formattedDateTime).format('YYYY-MM-DD'); // Format to 'YYYY-MM-DD' for the URL
var rowData = [
    i + 1,
    '<a href="/user/' + row.logine_user.id + '/' + dateForURL + '">' + row.logine_user.name + '</a>',  
    row.logine_user.email, 
    formattedDateTime,
    row.ip, 
    '<label class="badge ' + (row.status === 'success' ? 'badge-soft-success' : 'badge-soft-warning') + '">' + row.status + '</label>'
];
        table.row.add(rowData).draw(false);
    }
}
    populateFilterDropdowns();
},
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
            $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Custom Date Range': [start, end]
        }
    }, function (selectedStart, selectedEnd) {
        $('#reportrange span').html(selectedStart.format('MMMM D, YYYY') + ' - ' + selectedEnd.format('MMMM D, YYYY'));
        loadDataAndPopulateFilters(selectedStart, selectedEnd);
    });

    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    loadDataAndPopulateFilters(start, end);
});
    </script>
    @endif
    @endcan
</div>
</div>
@endsection