@extends('layouts.datatable')
@section('content')
@can('daily-leads-create')
    <div class="card-body">
    <h4 class="card-title text-center">Add New Quotation</h4>
    <hr>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="" enctype="multipart/form-data">
        @csrf
            <div class="row">
            <h4 class="card-title">Client's Details</h4>
			</div>  
                <div class="row"> 
					<div class="col-lg-3 col-md-2">
                        <label for="basicpill-firstname-input" class="form-label">Customer Name </label>
                        <input type ="text" class="form-control" name="" placeholder = "name" value = "{{ $data->name }}" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Company</label>
                        <input type ="text" class="form-control" name="company" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Contact No : </label>
                        <input type ="text" class="form-control" name="phone" placeholder = "" value = "{{ $data->phone }}" readonly>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Email : </label>
                        <input type ="text" class="form-control" name="email" placeholder = "" value = "{{ $data->email }}" readonly>
                    </div>
                    <div class="col-lg-3 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Address : </label>
                    <input type ="text" class="form-control" name="address" placeholder = "" value = "">
                    </div>
                    </div>
                    <hr>
                    <div class="row">
            <h4 class="card-title">Delivery Details :</h4>
			</div> 
            <div class="row"> 
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Final Destination : </label>
                    <select name="final_destination" id="country" class="form-control mb-1">
                                <option value="">Select Location</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Incoterm:</label>
                            <select name="incoterm" id="country" class="form-control mb-1">
                                <option value="EXW">EXW</option>
                                <option value="CNF">CNF</option>
                                <option value="CIF">CIF</option>
                            </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Place of Delivery : </label>
                    <select name="place_of_delivery" id="country" class="form-control mb-1">
                                <option value="Ducamz">Ducamz</option>
                                <option value="Jebel Ali">Jebel Ali</option>
                            </select>
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <h4 class="card-title">Payment Details :</h4>
			        </div> 
                    <div class="row">
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Commission : </label>
                    <input type ="text" class="form-control" name="system_code" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Payment Terms : </label>
                    <input type ="text" class="form-control" name="payment_terms" placeholder = "" value = "">
                    </div>
                    </div>
                    <hr>
                    <div class="row">
                    <h4 class="card-title">Client's Representative :</h4>
			        </div> 
                    <div class="row">
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Rep Name : </label>
                    <input type ="text" class="form-control" name="payment_terms" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">Rep NO : </label>
                    <input type ="text" class="form-control" name="payment_terms" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">CB Name : </label>
                    <input type ="text" class="form-control" name="payment_terms" placeholder = "" value = "">
                    </div>
                    <div class="col-lg-2 col-md-6">
                    <label for="basicpill-firstname-input" class="form-label">CB No : </label>
                    <input type ="text" class="form-control" name="payment_terms" placeholder = "" value = "">
                    </div>
                    </div>
                    <hr>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>Code</th>
                            <th>Description</th>
                            <th>Int Colour</th>
                            <th>Ex Colour</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remarks</th>
                            <th>Addons</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($items as $key => $item)
                            <tr> 
                            @php
                     $variant_name = DB::table('varaints')->where('id', $item->varaints_id)->first();
                     $variant_names = $variant_name->name;
                     $my = $variant_name->my;
                     $sub_model = $variant_name->sub_model;
                     $steering = $variant_name->steering;
                     $int_color = $item->int_colour;
                     $ex_color = $item->ex_colour;
                     $max_price = $item->max_price;
                     $brandss_id = DB::table('brands')->where('id', $variant_name->brands_id)->first();
                     $brand_name = $brandss_id->brand_name;
                     $master_model_line = DB::table('master_model_lines')->where('id', $variant_name->master_model_lines_id)->first();
                     $model_line = $master_model_line->model_line;
                     $vehicle_cart = DB::table('vehiclescarts')
                  ->select('*')
                  ->where('vehicle_id', '=', $item->id)
                  ->where('created_by', '=', auth()->user()->id)
                  ->first();
                  $quotation_id = null;
                  $id = null;
                  if (!empty($vehicle_cart)) 
                  {
                  $quotation_id = $vehicle_cart->quotation_id;
                  $vehicle_cart_id = $vehicle_cart->id;
                  }
                     @endphp
                            <td>
                            <a href="{{ url('/remove-vehicle/'.$item->id) }}" class="minus-circle-link">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            </a>
                     {{ $variant_names }}
                            </td>
                            <td>{{ $my }} {{ $steering }} {{ $brand_name }} {{ $model_line }} {{ $sub_model }}</td>
                            <td>{{ $int_color }}</td>
                            <td>{{ $ex_color }}</td>
                            <td><input type="text" class="form-control" name="max_price_{{ $key }}" placeholder="" value="{{ $max_price }}" oninput="updateTotal({{ $key }})"></td>
                            <td><input type="text" class="form-control" name="qty_{{ $key }}" placeholder="" value="" oninput="updateTotal({{ $key }})"></td>
                            <td><input type="text" class="form-control" name="total_{{ $key }}" placeholder="" value="" readonly></td>
                            <td><input type ="text" class="form-control" name="remarks" placeholder = "" value = ""></td>
                            <td>
                            <a data-toggle="popover" data-trigger="hover" title="Make Active" data-placement="top" class="btn btn-sm btn-primary modal-button" data-modal-id="addaddons{{ $vehicle_cart_id }}"><i class="fa fa-plus plus-circle-link" aria-hidden="true"></i></a>
<div class="modal modal-class" id="addaddons{{ $vehicle_cart_id }}" >
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Addons</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                <table id="myTable{{$vehicle_cart_id}}" class="table table-striped table-editable table-edits table datatable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Addon Name</th>
                                <th>Addon Code</th>
                                <th>Lead Time</th>
                                <th>Additional Remarks</th>
                                <th>Price(AED)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $model_line_id = DB::table('vehiclescarts')
                                    ->select('varaints.master_model_lines_id')
                                    ->join('vehicles', 'vehiclescarts.vehicle_id', '=', 'vehicles.id')
                                    ->join('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                                    ->where('vehiclescarts.id', '=', $vehicle_cart_id)
                                    ->first();
                                $data = DB::table('addon_types')
                                    ->join('addon_details', 'addon_types.addon_details_id', '=', 'addon_details.id')
                                    ->join('addons', 'addon_details.addon_id', '=', 'addons.id')
                                    ->where('addon_types.model_id', '=', $model_line_id->master_model_lines_id) // access the property here
                                    ->select('*', 'addon_details.id as idp')
                                    ->get();
                            @endphp
                            @foreach ($data as $key => $addon)
                                <tr>
                                    <td><img src="{{ $addon->image }}" alt="Addon thumbnail" style="width: 100px;"></td>
                                    <td>{{ $addon->name }}</td>
                                    <td>{{ $addon->addon_code }}</td>
                                    <td>{{ $addon->lead_time }}</td>
                                    <td>{{ $addon->additional_remarks }}</td>
                                    <td>{{ $addon->selling_price }}</td>
                                    <td>
                                    <a href="#" class="plus-circle-link addadones" id="addadones_{{ $addon->idp }}_{{$vehicle_cart_id}}">
                                         <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                         </a>
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
      </td>
<div class="addone-check" id="addone-check">

                </div>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6"><a href="#" class="btn-addmore"><i class="fa fa-plus"></i>Add More Vehicles</a><td>
                </tr>
                <tr>
        <td colspan="6" class="text-right font-weight-bold">Net Total AED</td>
        <td><input type="text" class="form-control" name="net_total_aed" id="net_total_aed" placeholder="" value="" readonly></td>
        </tr>
        <tr>
        <td colspan="6" class="text-right font-weight-bold">Net Total USD</td>
        <td><input type="text" class="form-control" name="net_total_usd" id="net_total_usd" placeholder="" value="" readonly></td>
        </tr>  
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
			        </div>  
                    </br>
                    </br> 
			        <div class="col-lg-12 col-md-12">
				    <input type="submit" name="submit" value="Submit" class="btn btn-successe btn-sm btncenter" />
			        </div>  
        </form>
		</br>
    </div>
    @endcan
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('.modal-button').on('click', function() {
        var modalId = $(this).data('modal-id');
        $('#' + modalId).addClass('modalshow');
        $('#' + modalId).removeClass('modalhide');
        $('#' + modalId + ' .datatable').DataTable(); // Initialize DataTable
    });

    $('.btn-close').on('click', function() {
        $('.modal').addClass('modalhide');
        $('.modal').removeClass('modalshow');
    });
    $('.addadones').click(function(){
                var id = this.id;
                var split_id = id.split("_");
                var anu = split_id[0];
                var addon_id = split_id[1];
                var cart_id = split_id[2];
                let url = "{{ route('quotation.addone-insert') }}";
               if(cart_id != '')
             {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
                 });
                 $.ajax({
                     url: url,
                     method:"POST",
                     data:{cart_id:cart_id,addon_id:addon_id,anu:anu},
                     dataType:"JSON",
                     success:function(data)
                     {
                     //console.log(data);
                    $('#addadones_' + addon_id +'_'+ cart_id).closest('.addadones').remove();
                     }
                }); 
            }
        });
});
$('#variants').select2();
$('#brand').select2();
$('#my').select2();
$('#model_line').select2();
$('#sub_model').select2();
$('#brand').on('change',function(){
            let brand = $(this).val();
            let url = '{{ route('quotation.get-my') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    brand: brand
                },
                success:function (data) {
                    
                     $('select[name="my"]').empty();
                     $('select[name="model_line"]').empty();
                     $('select[name="sub_model"]').empty();
                     $('#my').html('<option value=""> Select Manufacture Year </option>');
                     $('#model_line').html('<option value=""> Select Model Line </option>');
                     $('#sub_model').html('<option value=""> Select Sub Model </option>');
                     jQuery.each(data, function(key,value){
                       $('select[name="my"]').append('<option value="'+ value +'">'+ value +'</option>');
                     });
                }
            });
        });
        $('#my').on('change',function(){
            let brand = $('#brand').val();
            let my = $(this).val();
            let url = '{{ route('quotation.get-model-line') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    my: my, brand: brand
                },
                success:function (data) {
                     $('select[name="model_line"]').empty();
                     $('select[name="sub_model"]').empty();
                     $('#model_line').html('<option value=""> Select Model Line </option>');
                     $('#sub_model').html('<option value=""> Select Sub Model </option>');
                     jQuery.each(data, function(key,value){
                       $('select[name="model_line"]').append('<option value="'+ value +'">'+ value +'</option>');
                     });
                }
            });
        });
        $('#model_line').on('change',function(){
            let brand = $('#brand').val();
            let my = $('#my').val();
            let model_line = $(this).val();
            let url = '{{ route('quotation.get-sub-model') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    my: my, brand: brand, model_line: model_line
                },
                success:function (data) {
                    console.log(data);
                     jQuery.each(data, function(key,value){
                       $('select[name="sub_model"]').append('<option value="'+ value +'">'+ value +'</option>');
                     });
                }
            });
        });
        function updateTotal(key) {
        var max_price = parseFloat(document.getElementsByName('max_price_' + key)[0].value);
        var qty = parseFloat(document.getElementsByName('qty_' + key)[0].value);
        var total = max_price * qty;
        document.getElementsByName('total_' + key)[0].value = total.toFixed(2);
        var netTotalAED = 0;
        var netTotalUSD = 0;
        @foreach($items as $key => $item)
            var itemTotal = parseFloat(document.getElementsByName('total_' + {{ $key }})[0].value);
            if (!isNaN(itemTotal)) {
                    netTotalAED += itemTotal;
                    netTotalUSD += itemTotal;
            }
        @endforeach
        document.getElementById('net_total_aed').value = netTotalAED.toFixed(2);
        document.getElementById('net_total_usd').value = (netTotalUSD / 3.67).toFixed(2);
    }
</script>
@endpush
