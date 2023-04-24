@extends('layouts.datatable')
@section('content')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Sreach Vehicles</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table">
                                        <thead>
                                            <tr>
                                                <th>Steering</th>
                                                <th>Fuel</th>
                                                <th>Model Year</th>
                                                <th>Upholestry</th>
                                                <th>Brand</th>
                                                <th>Model</th>
                                                <th>Sub Model</th>
                                                <th>Variant</th>
                                                <th>Seats</th>
                                                <th>Gear</th>
                                                <th>Int Color</th>
                                                <th>Ex Color</th>
                                                <th>Max Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($vehicles as $vehicles)
                                        <tr data-id="1">
                                                <td>{{$vehicles->steering}}</td>
                                                <td>{{$vehicles->fuel_type}}</td>
                                                <td>{{$vehicles->my}}</td>
                                                <td>{{$vehicles->upholestry}}</td>
                                                <td>{{$vehicles->brand_name}}</td>
                                                <td>{{$vehicles->model_line}}</td>
                                                <td>{{$vehicles->sub_model}}</td>
                                                <td>{{$vehicles->name}}</td>
												<td>{{$vehicles->seats}}</td>
                                                <td>{{$vehicles->gear}}</td>
                                                <td>{{$vehicles->int_color}}</td>
                                                <td>{{$vehicles->ex_color}}</td>
                                                <td>{{$vehicles->max_price}}</td>
                                                <td></td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
    $('#example').DataTable({
        initComplete: function () {
            this.api()
                .columns()
                .every(function (d) {
                    var column = this;
                    var theadname = $("#example th").eq([d]).text();
                    var select = $('<select class="form-control my-1"><option value="">All</option></select>')
                        .appendTo( $(column.header()) )
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
 
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });
 
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                });
        },
    });
});
</script>
<!-- <script type="text/javascript">

/* Each drop-down selection affects the values in the other drop-downs */

var primaryColIdx;
var secondaryColIdx;

$(document).ready(function() {
    $('#example').DataTable( {
        initComplete: function () {
          populateDropdowns(this);
        }
    } );

} );

function populateDropdowns(table) {
    table.api().columns([1,2]).every( function () {
        var column = this;
        //console.log("processing col idx " + column.index());
        var select = $('<select><option value=""></option></select>')
            .appendTo( $(column.footer()).empty() )
            .on( 'change', function () {
                var dropdown = this;
                doFilter(table, dropdown, column);
                rebuildSecondaryDropdown(table, column.index());
            } );

        column.data().unique().sort().each( function ( val, idx ) {
            select.append( '<option value="' + val + '">' + val + '</option>' )
        } );
    } );
}

function doFilter(table, dropdown, column) {
    // first time a drop-down is used, it becomes the primary. This
    // remains the case until the page is refreshed:
    if (primaryColIdx == null) {
        primaryColIdx = column.index();
        secondaryColIdx = (primaryColIdx == 1) ? 2 : 1;
    }

    if (column.index() === primaryColIdx) {
        // reset all the filters because the primary is changing:
        table.api().search( '' ).columns().search( '' );
    }

    var filterVal = $.fn.dataTable.util.escapeRegex($(dropdown).val());
    //console.log("firing dropdown for col idx " + column.index() + " with value " + filterVal);
    column
        .search( filterVal ? '^' + filterVal + '$' : '', true, false )
        .draw();
}

function rebuildSecondaryDropdown(table, primaryColIdx) {
    var secondaryCol;

    table.api().columns(secondaryColIdx).every( function () {
        secondaryCol = this;
    } );

    // get only the unfiltered (unhidden) values for the "other" column:
    var raw = table.api().columns( { search: 'applied' } ).data()[secondaryColIdx];
    // the following uses "spread syntax" (...) for sorting and de-duping:
    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_syntax
    var uniques = [...new Set(raw)].sort();

    var filteredSelect = $('<select><option value=""></option></select>')
        .appendTo( $(secondaryCol.footer()).empty() )
        .on( 'change', function () {
            var dropdown = this;
            doFilter(table, dropdown, secondaryCol);
            //rebuildSecondaryDropdown(table, column.index());
        } );

    uniques.forEach(function (item, index) {
        filteredSelect.append( '<option value="' + item + '">' + item + '</option>' )
    } );

}

</script> -->
@endpush