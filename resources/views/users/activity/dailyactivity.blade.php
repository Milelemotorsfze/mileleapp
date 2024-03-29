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
                    <th>DateTime</th>
                    <th>Activity</th>
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
            var start = moment('{{ $date }}');
            var end = moment('{{ $date }}');
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
                var userId = '{{ $id }}';
                $.ajax({
                    url: '{{ route('listUsersgetdataac') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_id: userId,
                        start_date: start.format('YYYY-MM-DD'),
                        end_date: end.format('YYYY-MM-DD'),
                    },
                    success: function (response) {
            var data = response.data;
            if (Array.isArray(data)) {
                table.clear().draw();
                for (var i = 0; i < data.length; i++) {
                    var row = data[i];
                    var formattedDateTime = moment(row.created_at).format('DD-MM-YYYY HH:mm:ss');
                    var rowData = [
                        i + 1,
                        row.name,
                        row.email,
                        formattedDateTime,
                        row.activity
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