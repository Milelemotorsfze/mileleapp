<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{asset('css/custom/daterangepicker.css')}}" />
    <script type="text/javascript" src="{{asset('js/custom/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/custom/daterangepicker.min.js')}}"></script>
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
                <tr>
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
                </tr>
            </thead>
            <tbody>
                @if(isset($workOrder) && isset($workOrder->dataHistories) && count($workOrder->dataHistories) > 0)
                    @foreach($workOrder->dataHistories as $dataHistory)
                        <tr>
                            <td>{{ $dataHistory->changed_at->format('d M Y, H:i:s') }}</td>
                            <td>{{ $dataHistory->user->name }}</td> 
                            <td>{{ $dataHistory->type }}</td>
                            <td>{{ $dataHistory->field }}</td>
                            @if(in_array($dataHistory->field_name, ['brn_file', 'signed_pfi', 'signed_contract', 'payment_receipts', 'noc', 'enduser_trade_license', 'enduser_passport', 'enduser_contract', 'vehicle_handover_person_id']))
                                <td>
                                    @if($dataHistory->old_value != '')
                                        <a href="{{ url($dataHistory->old_value) }}" target="_blank">
                                            <button class="btn btn-primary btn-style">View</button>
                                        </a>
                                        <a href="{{ url($dataHistory->old_value) }}" download>
                                            <button class="btn btn-info btn-style">Download</button>
                                        </a>
                                    @endif
                                </td>
                                <td> 
                                    @if($dataHistory->new_value != '')
                                        <a href="{{ url($dataHistory->new_value) }}" target="_blank">
                                            <button class="btn btn-primary btn-style">View</button>
                                        </a>
                                        <a href="{{ url($dataHistory->new_value) }}" download>
                                            <button class="btn btn-info btn-style">Download</button>
                                        </a>
                                    @endif
                                </td>
                            @elseif($dataHistory->field === 'Sales Person')
                                <td>
                                    {{ $dataHistory->old_value ? \App\Models\User::find($dataHistory->old_value)->name ?? 'Unknown User' : '' }}
                                </td>
                                <td>
                                    {{ $dataHistory->new_value ? \App\Models\User::find($dataHistory->new_value)->name ?? 'Unknown User' : '' }}
                                </td>
                            @elseif($dataHistory->field === 'Is Batch')
                                <td>
                                    {{ $dataHistory->old_value == 1 ? 'Yes' : ($dataHistory->old_value == 0 ? 'No' : '') }}
                                </td>
                                <td>
                                    {{ $dataHistory->new_value == 1 ? 'Yes' : ($dataHistory->new_value == 0 ? 'No' : '') }}
                                </td>
                            @else
                            <td>{{ $dataHistory->old_value }}</td>
                            <td>{{ $dataHistory->new_value }}</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">No data history available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script> 
        
<script type="text/javascript">
    $(document).ready(function() {
        $('#user_id').select2({
            allowClear: true,
            placeholder: "Select User",
        });

        var table = $('.my-datatableclass').DataTable();

        $('#date-time-filter').daterangepicker({
            autoUpdateInput: false,  
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY hh:mm A',
                cancelLabel: 'Clear'
            }
        });

        $('#date-time-filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY hh:mm A') + ' - ' + picker.endDate.format('MM/DD/YYYY hh:mm A'));
            table.draw();
        });

        $('#date-time-filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            // picker.setStartDate(moment().startOf('day'));
            // picker.setEndDate(moment().endOf('day'));
            table.draw();
        });

        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = $('#date-time-filter').data('daterangepicker').startDate;
                var max = $('#date-time-filter').data('daterangepicker').endDate;
                var startDate = moment(data[0], 'DD MMM YYYY, HH:mm:ss');
                if (!min.isValid() || !max.isValid() || $('#date-time-filter').val() === '') {
                    return true;
                }
                return startDate.isBetween(min, max, undefined, '[]');
            }
        );

        var userColumnIndex = 1; 
        table.column(userColumnIndex).data().unique().sort().each(function (d, j) {
            $('#user-filter').append('<option value="' + d + '">' + d + '</option>')
        });

        $('#user-filter').select2({
            placeholder: "Select User",
            allowClear: true
        });

        var typeColumnIndex = 2; 
        table.column(typeColumnIndex).data().unique().sort().each(function (d, j) {
            $('#type-filter').append('<option value="' + d + '">' + d + '</option>')
        });

        $('#type-filter').select2({
            placeholder: "Select Type",
            allowClear: true
        });
        var fieldColumnIndex = 3; 
        table.column(fieldColumnIndex).data().unique().sort().each(function (d, j) {
            $('#field-filter').append('<option value="' + d + '">' + d + '</option>')
        });

        $('#field-filter').select2({
            placeholder: "Select Field",
            allowClear: true
        });
        table.columns().every(function () {
            var that = this;

            $('input', this.header()).on('keyup change clear', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });

            $('select', this.header()).on('change', function () {
                var selectedOptions = $(this).val();
                var searchValue = selectedOptions ? selectedOptions.join('|') : '';
                that.search(searchValue, true, false).draw();
            });
        });

        $('#clear-filters').click(function() {
            $('.column-filter').val('').trigger('change');
            $('#user-filter').val(null).trigger('change');
            $('#type-filter').val(null).trigger('change');
            $('#field-filter').val(null).trigger('change');
            $('#date-time-filter').val('');
            // $('#date-time-filter').data('daterangepicker').setStartDate(moment().startOf('day'));
            // $('#date-time-filter').data('daterangepicker').setEndDate(moment().endOf('day'));
            $('#date-time-filter').trigger('cancel.daterangepicker');

            table.search('').columns().search('').draw();
        });
    });
</script>
</body>
