@extends('layouts.main')
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
                    <label for="basicpill-firstname-input" class="form-label">System Code : </label>
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
                    <h4 class="card-title">Select The Vehicles :</h4>
			        </div> 
                    <div class="row">
                    <div class="col-lg-2 col-md-1">
                    <select name="brand" id="brand" class="form-control mb-1">
                                <option value="">Select Brand</option>
                                @foreach ($brand as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-lg-2 col-md-1">
                    <select name="my" id="my" class="form-control mb-1">
                                <option value="">Select Manufacture Year</option>
                            </select>
                    </div>
                    <div class="col-lg-2 col-md-1">
                    <select name="model_line" id="model_line" class="form-control mb-1">
                                <option value="">Select Model Line</option>
                            </select>
                    </div>
                    <div class="col-lg-2 col-md-1">
                    <select name="sub_model" id="sub_model" class="form-control mb-1">
                                <option value="">Select Sub Model</option>
                            </select>
                    </div>
                    <div class="col-lg-2 col-md-1">
                    <select name="variants" id="variants" class="form-control mb-1">
                                <option value="">Select Code</option>
                                @foreach ($variants as $variants)
                                    <option value="{{ $variants }}">{{ $variants->name }}</option>
                                @endforeach
                            </select>
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
                            <th>Colour</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <td></td>
                            <td></td>
                            <td><input type ="text" class="form-control" name="payment_terms" placeholder = "" value = ""></td>
                            <td><input type ="text" class="form-control" name="payment_terms" placeholder = "" value = ""></td>
                            <td><input type ="text" class="form-control" name="payment_terms" placeholder = "" value = ""></td>
                            <td></td>
                            <td><input type ="text" class="form-control" name="payment_terms" placeholder = "" value = ""></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right font-weight-bold">Net Total AED</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right font-weight-bold">Net Total USD</td>
                            <td></td>
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
				    <input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
			        </div>  
        </form>
		</br>
    </div>
    @endcan
@endsection
@push('scripts')
<script type="text/javascript">
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
</script>
@endpush
