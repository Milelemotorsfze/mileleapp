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
                                        @foreach ($vehicles as $vehicle)
                                        <tr data-id="1">
                                                <td>{{$vehicle->steering}}</td>
                                                <td>{{$vehicle->fuel_type}}</td>
                                                <td>{{$vehicle->my}}</td>
                                                <td>{{$vehicle->upholestry}}</td>
                                                <td>{{$vehicle->brand_name}}</td>
                                                <td>{{$vehicle->model_line}}</td>
                                                <td>{{$vehicle->sub_model}}</td>
                                                <td>{{$vehicle->name}}</td>
												<td>{{$vehicle->seats}}</td>
                                                <td>{{$vehicle->gear}}</td>
                                                <td>{{$vehicle->int_color}}</td>
                                                <td>{{$vehicle->ex_color}}</td>
                                                <td>{{$vehicle->max_price}}</td>
                                                <td><input type="button" name="btn" value="Add Vehicle" class="addvehicles" id="addvehicles_{{ $vehicle->veh_id }}" />
                                                <input type="hidden" class="quotation_id "value="{{ $quotation->id }}" />
                                            </td>
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
                    if (d === 13) {
                        return;
                    }
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
    $('.addvehicles').click(function(){
                var id = this.id;
                var split_id = id.split("_");
                var actiond = split_id[0];
                var vehicles_id = split_id[1];
                var quotation_id = $('.quotation_id').val();
                let url = "{{ route('quotation.vehicles-insert') }}";
               if(vehicles_id != '')
             {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
                 });
                 $.ajax({
                     url: url,
                     method:"POST",
                     data:{vehicles_id:vehicles_id, actiond:actiond, quotation_id:quotation_id},
                     dataType:"JSON",
                     success:function(data)
                     {
                     $('#addvehicles_' + vehicles_id).closest('tr').remove();
                     var userId = {{ auth()->user()->id }};
                    function updateVehicleCount() {
                    $.ajax({
                        url: '/get-vehicle-count/' + userId,
                        type: 'GET',
                        success: function(data) {
                        $('.cart-icon-number').text(data);
                        }
                    });
                    }
                    updateVehicleCount();
                    // setInterval(updateVehicleCount, 500);
                     }
                }); 
            }
        });
});
</script>
@endpush