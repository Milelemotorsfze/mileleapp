<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <style>
        .select2-container {
            width: 100% !important;
        }
        table {
            width:100% !important;
        }
    </style>
</head>
<body>
<div class="row mt-1">
    <div class="table-responsive">
        <!-- Clear All Filters Button -->
        <button id="clear-filters" class="btn btn-info btn-sm mb-3">Clear All Filters</button>

        <table class="table table-striped table-editable table-edits table-condensed my-datatableclass">
            <thead style="background-color: #e6f1ff">
                <tr>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
                <!-- <tr>
                    <th><input type="text" id="date-time-filter" placeholder="Search Date & Time" class="column-filter form-control" /></th>
                    <th>
                        <select class="column-filter form-control" id="user-filter" multiple="multiple">
                        </select>
                    </th>
                    <th>
                        <select class="column-filter form-control" id="type-filter" multiple="multiple">
                        </select>
                    </th>
                    <th>
                        <select class="column-filter form-control" id="field-filter" multiple="multiple">
                        </select>
                    </th>
                    <th><input type="text" placeholder="Search Old Value" class="column-filter form-control" /></th>
                    <th><input type="text" placeholder="Search New Value" class="column-filter form-control" /></th>
                </tr> -->
            </thead>
            <tbody>
                @foreach($data as $dataone)
                    <tr>
                        <td>{{ $dataone->changed_at->format('d M Y, H:i:s') }}</td>
                        <td>{{ $dataone->user->name }}</td> 
                        <td>{{ $dataone->type }}</td>
                        <td>{{ $dataone->field }}</td>                      
                        <td>{{ $dataone->old_value }}</td>
                        <td>{{ $dataone->new_value }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script> 
        
<script type="text/javascript">
    $(document).ready(function() {
        // $('#user_id').select2({
        //     allowClear: true,
        //     placeholder: "Select User",
        // });

        // // Initialize DataTable with column filters
        // var table = $('.my-datatableclass').DataTable();

        // // Initialize date and time range picker
        // $('#date-time-filter').daterangepicker({
        //     autoUpdateInput: false,  // Do not auto-update the input with the selected range
        //     timePicker: true,
        //     timePickerIncrement: 30,
        //     locale: {
        //         format: 'MM/DD/YYYY hh:mm A',
        //         cancelLabel: 'Clear'
        //     }
        // });

        // // Apply the date range filter on user selection
        // $('#date-time-filter').on('apply.daterangepicker', function(ev, picker) {
        //     $(this).val(picker.startDate.format('MM/DD/YYYY hh:mm A') + ' - ' + picker.endDate.format('MM/DD/YYYY hh:mm A'));
        //     table.draw();
        // });

        // // Clear the date range filter
        // $('#date-time-filter').on('cancel.daterangepicker', function(ev, picker) {
        //     $(this).val('');
        //     // picker.setStartDate(moment().startOf('day'));
        //     // picker.setEndDate(moment().endOf('day'));
        //     table.draw();
        // });

        // // Custom filtering function for date and time range
        // $.fn.dataTable.ext.search.push(
        //     function(settings, data, dataIndex) {
        //         var min = $('#date-time-filter').data('daterangepicker').startDate;
        //         var max = $('#date-time-filter').data('daterangepicker').endDate;
        //         var startDate = moment(data[0], 'DD MMM YYYY, HH:mm:ss');
        //         if (!min.isValid() || !max.isValid() || $('#date-time-filter').val() === '') {
        //             return true;
        //         }
        //         return startDate.isBetween(min, max, undefined, '[]');
        //     }
        // );

        // // Get unique user names for the dropdown filter
        // var userColumnIndex = 1; // Index of the User column
        // table.column(userColumnIndex).data().unique().sort().each(function (d, j) {
        //     $('#user-filter').append('<option value="' + d + '">' + d + '</option>')
        // });

        // // Initialize Select2 for the user filter
        // $('#user-filter').select2({
        //     placeholder: "Select User",
        //     allowClear: true
        // });

        // // Get unique types for the multi-select dropdown filter
        // var typeColumnIndex = 2; // Index of the Type column
        // table.column(typeColumnIndex).data().unique().sort().each(function (d, j) {
        //     $('#type-filter').append('<option value="' + d + '">' + d + '</option>')
        // });

        // // Initialize Select2 for the type filter
        // $('#type-filter').select2({
        //     placeholder: "Select Type",
        //     allowClear: true
        // });
        // // Get unique field names for the multi-select dropdown filter
        // var fieldColumnIndex = 3; // Index of the Field column
        // table.column(fieldColumnIndex).data().unique().sort().each(function (d, j) {
        //     $('#field-filter').append('<option value="' + d + '">' + d + '</option>')
        // });

        // // Initialize Select2 for the field filter
        // $('#field-filter').select2({
        //     placeholder: "Select Field",
        //     allowClear: true
        // });
        // // Apply the search
        // table.columns().every(function () {
        //     var that = this;

        //     $('input', this.header()).on('keyup change clear', function () {
        //         if (that.search() !== this.value) {
        //             that.search(this.value).draw();
        //         }
        //     });

        //     $('select', this.header()).on('change', function () {
        //         var selectedOptions = $(this).val();
        //         var searchValue = selectedOptions ? selectedOptions.join('|') : '';
        //         that.search(searchValue, true, false).draw();
        //     });
        // });

        // // Clear all filters on button click
        // $('#clear-filters').click(function() {
        //     // Clear text inputs
        //     $('.column-filter').val('').trigger('change');
        //     // Clear Select2 dropdowns
        //     $('#user-filter').val(null).trigger('change');
        //     $('#type-filter').val(null).trigger('change');
        //     $('#field-filter').val(null).trigger('change');
        //     // Clear date range picker
        //     $('#date-time-filter').val('');
        //     // $('#date-time-filter').data('daterangepicker').setStartDate(moment().startOf('day'));
        //     // $('#date-time-filter').data('daterangepicker').setEndDate(moment().endOf('day'));
        //     $('#date-time-filter').trigger('cancel.daterangepicker');

        //     // Redraw the table
        //     table.search('').columns().search('').draw();
        // });
    });
</script>
</body>
