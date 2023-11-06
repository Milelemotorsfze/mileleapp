@extends('layouts.table')
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<style>
.dataTables_wrapper .table>thead>tr>th.sorting {
  vertical-align: middle;
}
  div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
.days-dropdownf {
    background: none;
    text-align: center;
    width: 50px;
    border: none;
    outline: none;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
.circle-button {
    display: inline-block;
    width: 20px;
    height: 20px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    line-height: 20px;
    border-radius: 50%;
    border: 1px solid #FFFF00;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.circle-button::before {
    content: '+';
    font-size: 16px;
}
.circle-buttonr {
    display: inline-block;
    width: 22px;
    height: 22px;
    background-color: #Ff0000;
    color: white;
    text-align: center;
    line-height: 20px;
    border-radius: 30%;
    border: 1px solid #FFFF00;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.circle-buttonr::before {
    content: 'X';
    font-size: 16px;
}
.circle-button:hover {
    background-color: #45a049;
    border: 2px solid #FFFFFF;
}
.contentveh {
            display: none;
        }
    </style>
<div class="card-header">
	<h4 class="card-title">
		Proforma Invoice
		<a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
	</h4>
	<br>
</div>
<div class="card-body">
	<div class="row">
		<div class="col-sm-4">
			Document Details
		</div>
		<div class="col-sm-4">
			Client's Details
		</div>
		<div class="col-sm-4">
			Delivery Details
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					Document No :
				</div>
				<div class="col-sm-6">
					{{$callDetails->id}}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<label for="timeRange">Document Validity:</label>
				</div>
				<div class="col-sm-6">
					<select id="timeRange">
						<option value="1">1 day</option>
						<option value="7">7 days</option>
						<option value="14">14 days</option>
						<option value="30">30 days</option>
						<option value="60">60 days</option>
					</select>
				</div>
			</div>
			@php
			$user = Auth::user();
			$empProfile = $user->empProfile;
			@endphp
			<div class="row">
				<div class="col-sm-6">
					Sales Person :
				</div>
				<div class="col-sm-6">
					{{ Auth::user()->name }}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Sales Office :
				</div>
				<div class="col-sm-6">
					{{ $empProfile->office }}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Sales Email ID :
				</div>
				<div class="col-sm-6">
					{{ Auth::user()->email }}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Sales Contact No :
				</div>
				<div class="col-sm-6">
					{{ $empProfile->phone }}
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					Customer ID :
				</div>
				<div class="col-sm-6">
					{{ $empProfile->id }}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Company :
				</div>
				<div class="col-sm-6">
					<input type="text" name="company" id="company">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<label for="timeRange">Person :</label>
				</div>
				<div class="col-sm-6">
					<input type="text" name="name" id="name" value="{{$callDetails->name}}">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Contact No :
				</div>
				<div class="col-sm-6">
					<input type="text" name="phone_number" id="phone_number" value="{{$callDetails->phone}}">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Email :
				</div>
				<div class="col-sm-6">
					<input type="text" name="email" id="email" value="{{$callDetails->email}}">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Address :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					Final Destination :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Incoterm :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Place of Delivery :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-4">
			Payment Details
		</div>
		<div class="col-sm-8">
			Client's Representative
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					System Code :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Payment Terms :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					Rep Name :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					Rep No :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-6">
					CB Name :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					CB No :
				</div>
				<div class="col-sm-6">
					<input type="text" name="address" id="address">
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
					<thead class="bg-soft-secondary">
						<tr>
							<th>Description</th>
							<th>Code</th>
							<th>Unit Price</th>
							<th>Quantity</th>
							<th>Total Amount</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-1">
			<input type="radio" id="showVehicles" name="contentType">
			<label for="showVehicles">Add Vehicles</label>
		</div>
		<div class="col-sm-1">
			<input type="radio" id="showAccessories" name="contentType">
			<label for="showAccessories">Add Accessories</label>
		</div>
		<div class="col-sm-1">
			<input type="radio" id="showSpareParts" name="contentType">
			<label for="showSpareParts">Add Spare Parts</label>
		</div>
		<div class="col-sm-1">
			<input type="radio" id="showKits" name="contentType">
			<label for="showKits">Add Kits</label>
		</div>
		<div class="col-sm-1">
			<input type="radio" id="showShipping" name="contentType">
			<label for="showShipping">Add Shipping</label>
		</div>
        <div class="col-sm-2">
            <input type="radio" id="showShippingDocuments" name="contentType">
            <label for="showShippingDocuments">Add Shipping Documents</label>
        </div>
        <div class="col-sm-1">
            <input type="radio" id="showCertificates" name="contentType">
            <label for="showCertificates"> Certificate</label>
        </div>
        <div class="col-sm-1">
            <input type="radio" id="showOthers" name="contentType">
            <label for="showOthers">Add Other</label>
        </div>
	</div>
	<div id="vehiclesContent" class="contentveh">
		<hr>
		<div class="row">
			<h4 class="col-lg-2 col-md-6">Search Available Vehicles</h4>
			<div class="col-lg-12 col-md-6 d-flex align-items-end">
				<div class="col-lg-2 col-md-6">
					<label for="brand">Select Brand:</label>
					<select class="form-control col" id="brand" name="brand">
						<option value="">Select Brand</option>
						@foreach($brands as $brand)
						<option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="model_line">Select Model Line:</label>
					<select class="form-control col" id="model_line" name="model_line" disabled>
						<option value="">Select Model Line</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="variant">Select Variant:</label>
					<select class="form-control col" id="variant" name="variant" disabled>
						<option value="">Select Variant</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="interior_color">Interior Color:</label>
					<select class="form-control col" id="interior_color" name="interior_color" disabled>
						<option value="">Select Interior Color</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="exterior_color">Exterior Color:</label>
					<select class="form-control col" id="exterior_color" name="exterior_color" disabled>
						<option value="">Select Exterior Color</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6 d-flex align-items-end justify-content-between">
					<div class="col">
						<button type="button" class="btn btn-primary" id="search-button">Search</button>
					</div>
					<div class="col">
						<button type="button" class="btn btn-outline-warning" id="directadding-button">Directly Adding Into Quotation</button>
					</div>
				</div>
			</div>
		</div>
		<br>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
					<table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
						<thead class="bg-soft-secondary">
							<tr>
								<th>ID</th>
								<th>Status</th>
								<th>VIN</th>
								<th>Brand Name</th>
								<th>Model Line</th>
								<th>Model Details</th>
								<th>Variant Name</th>
								<th>Variant Detail</th>
								<th>Interior Color</th>
								<th>Exterior Color</th>
								<th>Price</th>
								<th style="width:30px;">Add Into Qoutation</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="accessoriesContent" class="contentveh">
        <hr>
		<div class="row">
			<h4 class="col-lg-12 col-md-12">Search Available Accessories</h4>
			<div class="col-lg-12 col-md-6 d-flex align-items-end">
                <div class="col-lg-2 col-md-6">
					<label for="brand">Select Accessory Name:</label>
					<select class="form-control col" id="accessories_addon" name="accessories_addon">
						<option value="">Select Accessory Name</option>
						@foreach($assessoriesDesc as $accessory)
						<option value="{{ $accessory->id }}">{{ $accessory->Addon->name ?? '' }}@if($accessory->description!='') - {{$accessory->description}}@endif</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="brand">Select Brand:</label>
					<select class="form-control col" id="accessories_brand" name="accessories_brand">
						<option value="">Select Brand</option>
                        <option value="allbrands">ALL BRANDS</option>
						@foreach($accessoriesBrands as $brand)
						<option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="model_line">Select Model Line:</label>
					<select class="form-control col" id="accessories_model_line" name="accessories_model_line" disabled>
						<option value="">Select Model Line</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6 d-flex align-items-end justify-content-between">
					<div class="col">
						<button type="button" class="btn btn-primary" id="accessories-search-button">Search</button>
					</div>
					<div class="col">
						<button type="button" class="btn btn-outline-warning" id="directadding-button">Directly Adding Into Quotation</button>
					</div>
				</div>
			</div>
		</div>
        <br>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
                    <table id="dtBasicExample5" class="table table-striped table-editable table-edits table">
						<thead class="bg-soft-secondary">
							<tr>
								<th>ID</th>
								<th>Accessory Name</th>
								<th>Accessory Code</th>
								<th>Brand/Model Line</th>
								<!-- <th>Model Line</th> -->
								<th>Additional Remarks</th>
								<th>Fixing Charge</th>
								<!-- <th>Least Purchase Price(AED)</th> -->
								<th>Selling Price(AED)</th>
								<th style="width:30px;">Add Into Quotation</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="sparePartsContent" class="contentveh">
        <hr>
		<div class="row">
			<h4 class="col-lg-12 col-md-12">Search Available Spare Parts</h4>
			<div class="col-lg-12 col-md-6 d-flex align-items-end">
                <div class="col-lg-2 col-md-6">
					<label for="brand">Select Spare Part Name:</label>
					<select class="form-control col" id="spare_parts_addon" name="spare_parts_addon">
						<option value="">Select Spare Part Name</option>
						@foreach($sparePartsDesc as $spareParts)
						<option value="{{ $spareParts->id }}">{{ $spareParts->Addon->name ?? '' }}@if($spareParts->description!='') - {{$spareParts->description}}@endif</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="brand">Select Brand:</label>
					<select class="form-control col" id="spare_parts_brand" name="spare_parts_brand">
						<option value="">Select Brand</option>
						@foreach($sparePartsBrands as $brand)
						<option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="model_line">Select Model Line:</label>
					<select class="form-control col" id="spare_parts_model_line" name="spare_parts_model_line" disabled>
						<option value="">Select Model Line</option>
					</select>
				</div>
                <div class="col-lg-2 col-md-6">
					<label for="model_description">Select Model Description:</label>
					<select class="form-control col" id="spare_parts_model_description" name="spare_parts_model_description" disabled>
						<option value="">Select Model Description</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6 d-flex align-items-end justify-content-between">
					<div class="col">
						<button type="button" class="btn btn-primary" id="spare_parts-search-button">Search</button>
					</div>
					<div class="col">
						<button type="button" class="btn btn-outline-warning" id="directadding-button">Directly Adding Into Quotation</button>
					</div>
				</div>
			</div>
		</div>
        <br>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
                    <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
						<thead class="bg-soft-secondary">
							<tr>
								<th>ID</th>
								<th>Spare Part Name</th>
								<th>Spare Part Code</th>
								<th>Brand</th>
								<th>Model Line/Model Description/Model Year</th>
                                <th>Part Numbers</th>
								<!-- <th>Model Line</th>
                                <th>Model Description</th> -->
                                <!-- <th>Model Year</th> -->
								<th>Additional Remarks</th>
								<th>Fixing Charge</th>
								<!-- <th>Least Purchase Price(AED)</th> -->
								<th>Selling Price(AED)</th>
								<th style="width:30px;">Add Into Quotation</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="kitsContent" class="contentveh">
        <hr>
		<div class="row">
			<h4 class="col-lg-2 col-md-6">Search Available Kits</h4>
			<div class="col-lg-12 col-md-6 d-flex align-items-end">
                <div class="col-lg-2 col-md-6">
					<label for="brand">Select Kit Name:</label>
					<select class="form-control col" id="kit_addon" name="kit_addon">
						<option value="">Select Kit Name</option>
						@foreach($kitsDesc as $kit)
						<option value="{{ $kit->id }}">{{ $kit->Addon->name ?? '' }}@if($kit->description!='') - {{$kit->description}}@endif</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="brand">Select Brand:</label>
					<select class="form-control col" id="kit_brand" name="kit_brand">
						<option value="">Select Brand</option>
						@foreach($kitsBrands as $brand)
						<option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-lg-2 col-md-6">
					<label for="model_line">Select Model Line:</label>
					<select class="form-control col" id="kit_model_line" name="kit_model_line" disabled>
						<option value="">Select Model Line</option>
					</select>
				</div>
                <div class="col-lg-2 col-md-6">
					<label for="model_description">Select Model Description:</label>
					<select class="form-control col" id="kits_model_description" name="kits_model_description" disabled>
						<option value="">Select Model Description</option>
					</select>
				</div>
				<div class="col-lg-2 col-md-6 d-flex align-items-end justify-content-between">
					<div class="col">
						<button type="button" class="btn btn-primary" id="kit-search-button">Search</button>
					</div>
					<div class="col">
						<button type="button" class="btn btn-outline-warning" id="directadding-button">Directly Adding Into Quotation</button>
					</div>
				</div>
			</div>
		</div>
        <br>
		<br>
		<div class="row">
			<div class="col-lg-12">
				<div class="table-responsive">
                    <table id="dtBasicExample4" class="table table-striped table-editable table-edits table">
						<thead class="bg-soft-secondary">
							<tr>
								<th>ID</th>
								<th>Kit Name</th>
								<th>Kit Code</th>
								<th>Brand/Model Line/Model Description</th>
                                <th>Items/ Quantity</th>
								<!-- <th>Model Line</th>
                                <th>Model Description</th> -->
								<th>Additional Remarks</th>
								<th>Fixing Charge</th>
								<!-- <th>Least Purchase Price(AED)</th> -->
								<th>Selling Price(AED)</th>
								<th style="width:30px;">Add Into Quotation</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div id="shippingContent" class="contentveh">
        <hr>
        <br>
{{--        <div class="card">--}}
{{--            <div class="card-body">--}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="shipping-table" class="table table-striped table-editable table-edits table">
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>Code</th>
                                    <th>Addon Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th style="width:30px;">Add Into Qoutation</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                    @foreach($shippings as $shipping)
                                        <tr>
                                        <td>{{ ++$i }}</td>
                                        <td></td>
                                        <td>{{ $shipping->name }}</td>
                                        <td>{{ $shipping->description  }}</td>
                                        <td>{{ $shipping->price }}</td>
                                        <td><button class="add-button circle-button" data-shipping-id="{{ $shipping->id }}">Add</button></td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
{{--                </div>--}}
{{--            </div>--}}
        </div>

	</div>
	<div id="certificatesContent" class="contentveh">
		waqar5
	</div>
</div>
@endsection
@push('scripts')
<script>
        var radioButtons = document.querySelectorAll('input[type="radio"]');
        var contentDivs = document.querySelectorAll('.contentveh');
        radioButtons.forEach(function (radioButton, index) {
            radioButton.addEventListener("change", function () {
                contentDivs.forEach(function (contentDiv) {
                    contentDiv.style.display = "none";
                });
                if (radioButton.checked) {
                    contentDivs[index].style.display = "block";
                }
            });
        });
    </script>
<script>
        $(document).ready(function() {
            $('#shipping-table').DataTable();
            $('#brand').select2();
            $('#model_line').select2();
            $('#variant').select2();
            $('#interior_color').select2();
            $('#exterior_color').select2();

			$('#accessories_addon').select2();
            $('#accessories_brand').select2();
			$('#accessories_model_line').select2();

            $('#spare_parts_addon').select2();
            $('#spare_parts_brand').select2();
			$('#spare_parts_model_line').select2();
            $('#spare_parts_model_description').select2();

            $('#kit_addon').select2();
            $('#kit_brand').select2();
			$('#kit_model_line').select2();
            $('#kits_model_description').select2();

            $('#brand').on('change', function() {
            var brandId = $(this).val();
            if (brandId) {
                $('#model_line').prop('disabled', false);
                $('#model_line').empty().append('<option value="">Select Model Line</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getmodel', ['brandId' => '__brandId__']) }}'
                        .replace('__brandId__', brandId),
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $('#model_line').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#model_line').prop('disabled', true);
                $('#variant').prop('disabled', true);
                $('#model_line').empty().append('<option value="">Select Model Line</option>');
                $('#variant').empty().append('<option value="">Select Variant</option>');
            }
        });
        $('#model_line').on('change', function() {
            var modelLineId = $(this).val();
            if (modelLineId) {
                $('#variant').prop('disabled', false);
                $('#variant').empty().append('<option value="">Select Variant</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getvariant', ['modelLineId' => '__modelLineId__']) }}'
                        .replace('__modelLineId__', modelLineId),
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $('#variant').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#variant').prop('disabled', true);
                $('#variant').empty().append('<option value="">Select Variant</option>');
            }
        });
        $('#variant').on('change', function() {
            var variantId = $(this).val();
            if (variantId) {
                $('#interior_color').prop('disabled', false);
                $('#exterior_color').prop('disabled', false);
                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getInteriorColors', ['variantId' => '__variantId__']) }}'
                        .replace('__variantId__', variantId),
                    success: function(response) {
                        $('#interior_color').empty().append('<option value="">Select Interior Color</option>');
                        $.each(response, function(key, value) {
                            $('#interior_color').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '{{ route('booking.getExteriorColors', ['variantId' => '__variantId__']) }}'
                        .replace('__variantId__', variantId),
                    success: function(response) {
                        $('#exterior_color').empty().append('<option value="">Select Exterior Color</option>');
                        $.each(response, function(key, value) {
                            $('#exterior_color').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#interior_color').prop('disabled', true);
                $('#exterior_color').prop('disabled', true);
                $('#interior_color').empty().append('<option value="">Select Interior Color</option>');
                $('#exterior_color').empty().append('<option value="">Select Exterior Color</option>');
            }
        });
		$('#accessories_brand').on('change', function() {
            var brandId = $(this).val();
            if (brandId) {
                $('#accessories_model_line').prop('disabled', false);
                $('#accessories_model_line').empty().append('<option value="">Select Model Line</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('quotation.getaddonmodel', ['brandId' => '__brandId__','type'=>'P']) }}'
                        .replace('__brandId__', brandId),
                    success: function(response) {
                        $('#accessories_model_line').append('<option value="allmodellines">All Model Lines</option>');
                        $.each(response, function(key, value) {
                            $('#accessories_model_line').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#accessories_model_line').prop('disabled', true);
                $('#accessories_model_line').empty().append('<option value="">Select Model Line</option>');
            }
        });
        $('#spare_parts_brand').on('change', function() {
            var brandId = $(this).val();
            if (brandId) {
                $('#spare_parts_model_line').prop('disabled', false);
                $('#spare_parts_model_line').empty().append('<option value="">Select Model Line</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('quotation.getaddonmodel', ['brandId' => '__brandId__','type'=>'P']) }}'
                        .replace('__brandId__', brandId),
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $('#spare_parts_model_line').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#spare_parts_model_line').prop('disabled', true);
                $('#spare_parts_model_line').empty().append('<option value="">Select Model Line</option>');
            }
        });
        $('#kit_brand').on('change', function() {
            var brandId = $(this).val();
            if (brandId) {
                $('#kit_model_line').prop('disabled', false);
                $('#kit_model_line').empty().append('<option value="">Select Model Line</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('quotation.getaddonmodel', ['brandId' => '__brandId__','type'=>'P']) }}'
                        .replace('__brandId__', brandId),
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $('#kit_model_line').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#kit_model_line').prop('disabled', true);
                $('#kit_model_line').empty().append('<option value="">Select Model Line</option>');
            }
        });
        $('#spare_parts_model_line').on('change', function() {
            var modelLineId = $(this).val();
            if (modelLineId) {
                $('#spare_parts_model_description').prop('disabled', false);
                $('#spare_parts_model_description').empty().append('<option value="">Select Model Description</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('quotation.getmodeldescription', ['modelLineId' => '__modelLineId__','type'=>'SP']) }}'
                        .replace('__modelLineId__', modelLineId),
                    success: function(response) { console.log(response);
                        $.each(response, function(key, value) {
                            $('#spare_parts_model_description').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#spare_parts_model_description').prop('disabled', true);
                $('#spare_parts_model_description').empty().append('<option value="">Select Model Description</option>');
            }
        });
        $('#kit_model_line').on('change', function() {
            var modelLineId = $(this).val();
            if (modelLineId) {
                $('#kits_model_description').prop('disabled', false);
                $('#kits_model_description').empty().append('<option value="">Select Model Description</option>');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('quotation.getmodeldescription', ['modelLineId' => '__modelLineId__','type'=>'SP']) }}'
                        .replace('__modelLineId__', modelLineId),
                    success: function(response) { console.log(response);
                        $.each(response, function(key, value) {
                            $('#kits_model_description').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#kits_model_description').prop('disabled', true);
                $('#kits_model_description').empty().append('<option value="">Select Model Description</option>');
            }
        });
var secondTable = $('#dtBasicExample2').DataTable({
    searching: false,
    paging: false,
    scrollY: false,
    sorting: false,
    columnDefs: [
        {
            targets: -1,
            data: null,
            defaultContent: '<button class="circle-buttonr remove-button">Remove</button>'
        },
        {
            targets: -2,
            data: null,
            render: function (data, type, row) {
                return '<input type="text" class="qty-editable form-control" value="1"/>';
            }
        },
        {
            targets: -3,
            data: null,
            render: function (data, type, row) {
                return '<input type="text" class="qty-editable form-control" value=""/>';
            }
        },
        {
    targets: -6,
    data: null,
    render: function (data, type, row) {
        var brand = row[3];
        var modelDescription = row[5];
        var interiorColor = row[8];
        var exteriorColor = row[9];
        var combinedValue = brand + ', ' + modelDescription + ', ' + interiorColor + ', ' + exteriorColor;
        return '<input type="text" class="combined-value-editable form-control" value="' + combinedValue + '"/>';
    }
    },
    {
                targets: -5,
                data: null,
                render: function (data, type, row) {
                    var variant = row[6];
                    return variant;
                }
            },
            {
        targets: -4,
        data: null,
        render: function (data, type, row) {
            var price = row[10];
            return '<input type="text" class="price-editable form-control" value="' + price + '"/>';
        }
    }
    ]
    });
    $('#dtBasicExample2 tbody').on('click', '.remove-button', function() {
        var row = secondTable.row($(this).parents('tr'));
        var rowData = row.data();
        var vehicleIdToRemove = rowData[0];
        moveRowToFirstTable(vehicleIdToRemove);
    });
    function moveRowToFirstTable(vehicleId) {
        var firstTable = $('#dtBasicExample1').DataTable();
        var secondTable = $('#dtBasicExample2').DataTable();
        var secondTableRow = secondTable.rows().indexes().filter(function(value, index) {
            return secondTable.cell(value, 0).data() == vehicleId;
        });

        if (secondTableRow.length > 0) {
            var rowData = secondTable.row(secondTableRow).data();
            firstTable.row.add(rowData).draw();
            secondTable.row(secondTableRow).remove().draw();
        }
    }
    $('#submit-button').on('click', function() {
        var selectedData = [];
    secondTable.rows().every(function() {
        var data = this.data();
        var vehicleId = data[0];
        var selectedDays = $(this.node()).find('.days-dropdown').val();

        selectedData.push({ vehicleId: vehicleId, days: selectedDays });
    });
    var dateValue = $('#name').val();
    var callIdValue = $('#call_id').val();
    var etd = $('#etd').val();
    var bookingnotes = $('#bookingnotes').val();
    var requestData = {
        selectedData: JSON.stringify(selectedData),
        date: dateValue,
        call_id: callIdValue,
        bookingnotes: bookingnotes,
        etd: etd
    };
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: '{{ route('booking.store') }}',
        data: requestData,
        headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
            alertify.success('Booking request submitted successfully');
            setTimeout(function() {
                window.location.href = '{{ route('dailyleads.index') }}';
            }, 1000);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
    });
    $(document).on('click', '.remove-button', function() {
        var row = $(this).closest('tr');
        var rowData = [];
        row.find('td').each(function() {
            rowData.push($(this).text());
        });
        moveRowToFirstTable(rowData);
    });
    $(document).on('click', '.add-button', function() {
        var vehicleId = $(this).data('vehicle-id');
        console.log('Add button clicked for vehicle ID:', vehicleId);
        var rowData = [];
        var row = $(this).closest('tr');
        console.log(row);
        row.find('td').each(function() {
            rowData.push($(this).text());
        });
        // pass the type(which table click inside to datatable => inside rendering chcek the row type value then add the data)
        var secondTable = $('#dtBasicExample2').DataTable();
        secondTable.row.add(rowData).draw();
        var firstTable = $('#dtBasicExample1').DataTable();
        firstTable.row(row).remove().draw();
        var shippingTable = $('#shipping-table').DataTable();
        shippingTable.row(row).remove().draw();
    });
    $('#search-button').on('click', function() {
        var variantId = $('#variant').val();
        var interiorColorId = $('#interior_color').val();
        var exteriorColorId = $('#exterior_color').val();

        if (!variantId) {
            alert("Please select a variant before searching.");
            return;
        }
        var url = '{{ route('booking.getbookingvehicles', [':variantId', ':interiorColorId?', ':exteriorColorId?']) }}';
        url = url.replace(':variantId', variantId);
        if (interiorColorId) {
            url = url.replace(':interiorColorId', interiorColorId);
        } else {
            url = url.replace(':interiorColorId?', '');
        }

        if (exteriorColorId) {
            url = url.replace(':exteriorColorId', exteriorColorId);
        } else {
            url = url.replace(':exteriorColorId?', '');
        }
        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                var data = response.map(function(vehicle) {
                    var addButton = '<button class="add-button" data-vehicle-id="' + vehicle.id + '">Add</button>';
                    return [
                        vehicle.id,
                        vehicle.grn_status,
                        vehicle.vin,
                        vehicle.brand,
                        vehicle.model_line,
                        vehicle.model_detail,
                        vehicle.variant_name,
                        vehicle.variant_detail,
                        vehicle.interior_color,
                        vehicle.exterior_color,
                        vehicle.price,
                        addButton
                    ];
                });
                if ($.fn.dataTable.isDataTable('#dtBasicExample1')) {
                    $('#dtBasicExample1').DataTable().destroy();
                }
                $('#dtBasicExample1').DataTable({
                    data: data,
                    columns: [
                        { title: 'ID' },
                        { title: 'Status' },
                        { title: 'VIN' },
                        { title: 'Brand Name' },
                        { title: 'Model Line' },
                        { title: 'Model Detail' },
                        { title: 'Variant Name' },
                        { title: 'Variant Detail' },
                        { title: 'Interior Color' },
                        { title: 'Exterior Color' },
                        { title: 'Price' },
                        {
                            title: 'Actions',
                            render: function(data, type, row) {
                                return '<div class="circle-button add-button" data-vehicle-id="' + row[0] + '"></div>';
                            }
                        }
                    ]
                });
            }
        });
    });
	$('#accessories-search-button').on('click', function() {
        var addonId = $('#accessories_addon').val();
        var brandId = $('#accessories_brand').val();
        var modelLineId = $('#accessories_model_line').val();
        if (!addonId || !brandId || !modelLineId) {
            alert("Please select all the filters before searching.");
            return;
        }
        var url = '{{ route('booking.getbookingAccessories', ['addonId', 'brandId', 'modelLineId']) }}';
        if (addonId) {
            url = url.replace('addonId', addonId);
        } else {
            url = url.replace('addonId?', '');
        }

        if (brandId) {
            url = url.replace('brandId', brandId);
        } else {
            url = url.replace('brandId?', '');
        }

        if (modelLineId) {
            url = url.replace('modelLineId', modelLineId);
        } else {
            url = url.replace('modelLineId?', '');
        }
        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                var slNo = 0;
                var data = response.map(function(accessory) { 
                    slNo = slNo + 1;
                    var addButton = '<button class="accessory-add-button" data-accessory-id="' + accessory.id + '">Add</button>';
                    if(accessory.addon_description.description != null) {
                       var accessoryName = accessory.addon_description.addon.name + ' - ' + accessory.addon_description.description;
                    } 
                    else {
                        var accessoryName = accessory.addon_description.addon.name;
                    }
                    if(accessory.is_all_brands == 'yes') {
                        var accessoryBrand = 'All Brands'
                    }
                    else {
                        var size = 0;
                        size = (accessory.brandModelLine).length;
                        if(size > 0) {
                            var accessoryBrand = '<table><thead><tr><th style="border: 1px solid #c4c4d4">Brand</th><th style="border: 1px solid #c4c4d4">Model Line</th></tr></thead><tbody>';
                            for(var i=0; i < size; i++) {
                                accessoryBrand = accessoryBrand +'<tr><td style="border: 1px solid #c4c4d4">'+accessory.brandModelLine[i].brands.brand_name+'</td>';
                                if(accessory.brandModelLine[i].is_all_model_lines == 'yes') {
                                    accessoryBrand = accessoryBrand +'<td style="border: 1px solid #c4c4d4">All Model Lines</td>';
                                }
                                else {
                                    accessoryBrand = accessoryBrand +'<td style="border: 1px solid #c4c4d4">';
                                    var modelLineSize = 0;
                                    modelLineSize = (accessory.brandModelLine[i].ModelLine).length;
                                    if(modelLineSize > 0) {
                                        accessoryBrand = accessoryBrand + '<table><tbody>';
                                        for(var j=0; j < modelLineSize; j++) {
                                            accessoryBrand = accessoryBrand + '<tr><td>'+ accessory.brandModelLine[i].ModelLine[j].model_lines.model_line +'</td></tr>';
                                        }
                                        accessoryBrand = accessoryBrand + '</tbody></table>';                                      
                                    }
                                    accessoryBrand = accessoryBrand +'</td>';
                                }
                                accessoryBrand = accessoryBrand +'</tr>';
                            }
                            accessoryBrand = accessoryBrand +'</tbody></table>';
                        }
                    }
                    if(accessory.additional_remarks != null) {
                        var accessoryAdditionalRemarks = '';
                    }
                    else {
                        var accessoryAdditionalRemarks = accessory.additional_remarks;
                    }
                    if(accessory.fixing_charges_included == 'yes') {
                        var accessoryFixingCharge = 'Included';
                    }
                    else {
                        var accessoryFixingCharge = accessory.fixing_charge_amount + ' AED';
                    }
                    if(accessory.selling_price != null) {
                        if(accessory.selling_price.selling_price != '0.00' || accessory.selling_price.selling_price != null) {
                            var accessorySellingPrice = accessory.selling_price.selling_price;
                        }
                    }
                    else if(accessory.pending_selling_price != null) {
                        if(accessory.pending_selling_price != null) {
                            if(accessory.pending_selling_price != '0.00' || accessory.pending_selling_price.selling_price != null) {
                                var accessorySellingPrice = accessory.pending_selling_price.selling_price + ' (Approval Awaiting)';
                            }
                        }
                    }
                    else {
                        var accessorySellingPrice = 'Not Added';
                    }
                    return [
                            slNo,
                            accessoryName,
                            accessory.addon_code,
                            accessoryBrand,
                            accessoryAdditionalRemarks,
                            accessoryFixingCharge,
                            // accessory.LeastPurchasePrices.purchase_price_aed,
                            accessorySellingPrice,
                            addButton,
                        ];
                });
                if ($.fn.dataTable.isDataTable('#dtBasicExample5')) {
                    $('#dtBasicExample5').DataTable().destroy();
                }
                $('#dtBasicExample5').DataTable({
                    data: data,
                    columns: [
                        { title: 'ID' },
                        { title: 'Accessory Name' },
                        { title: 'Accessory Code' },
                        { title: 'Brand/Model Lines' },
                        { title: 'Additional Remarks' },
                        { title: 'Fixing Charge'},
                        // { title: 'Least Purchase Price(AED)'}
                        { title: 'Selling Price(AED)'},
                        {
                            title: 'Add Into Quotation',
                            render: function(data, type, row) {
                                return '<div class="circle-button accessory-add-button" data-accessory-id="' + row[0] + '"></div>';
                            }
                        }
                    ]
                });
            }
        });
    });
    $('#spare_parts-search-button').on('click', function() {
        var addonId = $('#spare_parts_addon').val();
        var brandId = $('#spare_parts_brand').val();
        var modelLineId = $('#spare_parts_model_line').val();
        var ModelDescriptionId = $('#spare_parts_model_description').val();
        if (!addonId || !brandId || !modelLineId || !ModelDescriptionId) {
            alert("Please select all the filters before searching.");
            return;
        }
        var url = '{{ route('booking.getbookingSpareParts', ['addonId', 'brandId', 'modelLineId', 'ModelDescriptionId']) }}';
        if (addonId) {
            url = url.replace('addonId', addonId);
        } else {
            url = url.replace('addonId?', '');
        }

        if (brandId) {
            url = url.replace('brandId', brandId);
        } else {
            url = url.replace('brandId?', '');
        }

        if (modelLineId) {
            url = url.replace('modelLineId', modelLineId);
        } else {
            url = url.replace('modelLineId?', '');
        }
        if (ModelDescriptionId) {
            url = url.replace('ModelDescriptionId', ModelDescriptionId);
        } else {
            url = url.replace('ModelDescriptionId?', '');
        }
        $.ajax({
            type: 'GET',
            url: url,
            success: function(response) {
                var slNo = 0;
                var data = response.map(function(sparePart) { 
                    slNo = slNo + 1;
                    var addButton = '<button class="sparepart-add-button" data-sparepart-id="' + sparePart.id + '">Add</button>';
                    if(sparePart.addon_description.description != null) {
                       var sparePartName = sparePart.addon_description.addon.name + ' - ' + sparePart.addon_description.description;
                    } 
                    else {
                        var sparePartName = sparePart.addon_description.addon.name;
                    }
                    if(sparePart.is_all_brands == 'no') {
                        var sparePartBrandName = sparePart.brandModelLine[0].brands.brand_name;
                        var sparePartBrand = '<table><thead><tr><th style="border: 1px solid #c4c4d4">Model Line</th><th style="border: 1px solid #c4c4d4">Model Description</th><th style="border: 1px solid #c4c4d4">Model year</th></tr></thead><tbody>';
                        var modelLineSize = 0;
                        modelLineSize = (sparePart.brandModelLine[0].ModelLine).length;
                        if(modelLineSize > 0) {
                            for(var j=0; j < modelLineSize; j++) {
                                sparePartBrand = sparePartBrand +'<tr><td style="border: 1px solid #c4c4d4">'+sparePart.brandModelLine[0].ModelLine[j].model_lines.model_line+'</td><td style="border: 1px solid #c4c4d4">';
                                var modelDescSize = 0;
                                modelDescSize = (sparePart.brandModelLine[0].ModelLine[j].allDes).length;
                                if(modelDescSize > 0) {
                                    sparePartBrand = sparePartBrand +'<table><tbody>';
                                    for(var i=0; i < modelDescSize; i++) {
                                        sparePartBrand = sparePartBrand +'<tr><td>';
                                        if(i != 0) {
                                            sparePartBrand = sparePartBrand +'<br style="line-height: 3px">';
                                        }
                                        sparePartBrand = sparePartBrand +sparePart.brandModelLine[0].ModelLine[j].allDes[i].model_description+'</td></tr>';
                                    }
                                    sparePartBrand = sparePartBrand +'</tbody></table>';
                                }
                                sparePartBrand = sparePartBrand +'</td><td style="border: 1px solid #c4c4d4">'+sparePart.brandModelLine[0].ModelLine[j].model_year_start;
                                if(sparePart.brandModelLine[0].ModelLine[j].model_year_end != null) {
                                    sparePartBrand = sparePartBrand +' - '+sparePart.brandModelLine[0].ModelLine[j].model_year_end;
                                }
                                sparePartBrand = sparePartBrand +'</td></tr>';
                            }                          
                        }
                        sparePartBrand = sparePartBrand +'</tbody></table>';
                    }
                    var sparePartNumber = '';
                    var partNumbersSize = 0;
                    partNumbersSize = (sparePart.part_numbers).length;
                    if(partNumbersSize > 0) {
                        for(var k=0; k < modelLineSize; k++) {
                            if(sparePart.part_numbers[k]) {
                                if(k != 0) {
                                    sparePartNumber = sparePartNumber + '<br>';
                                }
                                sparePartNumber = sparePart.part_numbers[k].part_number;
                            }
                        }
                    }
                    if(sparePart.additional_remarks != null) {
                        var sparePartAdditionalRemarks = '';
                    }
                    else {
                        var sparePartAdditionalRemarks = sparePart.additional_remarks;
                    }
                    if(sparePart.fixing_charges_included == 'yes') {
                        var sparePartFixingCharge = 'Included';
                    }
                    else {
                        var sparePartFixingCharge = sparePart.fixing_charge_amount + ' AED';
                    }
                    if(sparePart.selling_price != null) {
                        if(sparePart.selling_price.selling_price != '0.00' || sparePart.selling_price.selling_price != null) {
                            var sparePartSellingPrice = sparePart.selling_price.selling_price;
                        }
                    }
                    else if(sparePart.pending_selling_price != null) {
                        if(sparePart.pending_selling_price != null) {
                            if(sparePart.pending_selling_price != '0.00' || sparePart.pending_selling_price.selling_price != null) {
                                var sparePartSellingPrice = sparePart.pending_selling_price.selling_price + ' (Approval Awaiting)';
                            }
                        }
                    }
                    else {
                        var sparePartSellingPrice = 'Not Added';
                    }
                    return [
                            slNo,
                            sparePartName,
                            sparePart.addon_code,
                            sparePartBrandName,
                            sparePartBrand,
                            sparePartNumber,
                            sparePartAdditionalRemarks,
                            sparePartFixingCharge,
                            // sparePart.LeastPurchasePrices.purchase_price_aed,
                            sparePartSellingPrice,
                            addButton,
                        ];
                });
                if ($.fn.dataTable.isDataTable('#dtBasicExample3')) {
                    $('#dtBasicExample3').DataTable().destroy();
                }
                $('#dtBasicExample3').DataTable({
                    data: data,
                    columns: [
                        { title: 'ID' },
                        { title: 'Spare Part Name' },
                        { title: 'Spare Part Code' },
                        { title: 'Brand' },
                        { title: 'Model Lines/Model Description/Model Year' },
                        { title: 'Part Numbers' },
                        { title: 'Additional Remarks' },
                        { title: 'Fixing Charge'},
                        // { title: 'Least Purchase Price(AED)'}
                        { title: 'Selling Price(AED)'},
                        {
                            title: 'Add Into Quotation',
                            render: function(data, type, row) {
                                return '<div class="circle-button sparepart-add-button" data-sparepart-id="' + row[0] + '"></div>';
                            }
                        }
                    ]
                });
            }
        });
    });
    $('#kit-search-button').on('click', function() {
        var addonId = $('#kit_addon').val();
        var brandId = $('#kit_brand').val();
        var modelLineId = $('#kit_model_line').val();
        var ModelDescriptionId = $('#kits_model_description').val();
        if (!addonId || !brandId || !modelLineId || !ModelDescriptionId) {
            alert("Please select all the filters before searching.");
            return;
        }
        var url = '{{ route('booking.getbookingKits', ['addonId', 'brandId', 'modelLineId', 'ModelDescriptionId']) }}';
        if (addonId) {
            url = url.replace('addonId', addonId);
        } else {
            url = url.replace('addonId?', '');
        }

        if (brandId) {
            url = url.replace('brandId', brandId);
        } else {
            url = url.replace('brandId?', '');
        }

        if (modelLineId) {
            url = url.replace('modelLineId', modelLineId);
        } else {
            url = url.replace('modelLineId?', '');
        }
        if (ModelDescriptionId) {
            url = url.replace('ModelDescriptionId', ModelDescriptionId);
        } else {
            url = url.replace('ModelDescriptionId?', '');
        }
        $.ajax({
            type: 'GET',
            url: url,
            // success: function(response) {  console.log(response);
            //     var data = response.map(function(kit) {
            //         var addButton = '<button class="kit-add-button" data-kit-id="' + kit.id + '">Add</button>';
            //         return [
            //             kit.id,
            //             kit.addon_id,
            //             kit.addon_code,
            //             addButton
            //         ];
            //     });
            //     if ($.fn.dataTable.isDataTable('#dtBasicExample4')) {
            //         $('#dtBasicExample4').DataTable().destroy();
            //     }
            //     $('#dtBasicExample4').DataTable({
            //         data: data,
            //         columns: [
            //             { title: 'ID' },
            //             { title: 'Kit Name' },
            //             { title: 'Kit Code' },
            //             {
            //                 title: 'Actions',
            //                 render: function(data, type, row) {
            //                     return '<div class="circle-button kit-add-button" data-kit-id="' + row[0] + '"></div>';
            //                 }
            //             }
            //         ]
            //     });
            // }
            success: function(response) {
                var slNo = 0;
                var data = response.map(function(kit) { 
                    slNo = slNo + 1;
                    var addButton = '<button class="kit-add-button" data-kit-id="' + kit.id + '">Add</button>';
                    var kitName = '';
                    if(kit.addon_name.name != null) {
                       kitName = kit.addon_name.name;
                    }
                    if(kit.is_all_brands == 'no') {
                        var kitBrandName = kit.brandModelLine[0].brands.brand_name;
                        var kitBrand = '<table><thead><tr><th style="border: 1px solid #c4c4d4">Model Line</th><th style="border: 1px solid #c4c4d4">Model Description</th></thead><tbody>';
                        var modelLineSize = 0;
                        modelLineSize = (kit.brandModelLine[0].ModelLine).length;
                        if(modelLineSize > 0) {
                            for(var j=0; j < modelLineSize; j++) {
                                kitBrand = kitBrand +'<tr><td style="border: 1px solid #c4c4d4">'+kit.brandModelLine[0].ModelLine[j].model_lines.model_line+'</td><td style="border: 1px solid #c4c4d4">';
                                var modelDescSize = 0;
                                modelDescSize = (kit.brandModelLine[0].ModelLine[j].allDes).length;
                                if(modelDescSize > 0) {
                                    kitBrand = kitBrand +'<table><tbody>';
                                    for(var i=0; i < modelDescSize; i++) {
                                        kitBrand = kitBrand +'<tr><td>';
                                        if(i != 0) {
                                            kitBrand = kitBrand +'<br style="line-height: 3px">';
                                        }
                                        kitBrand = kitBrand +kit.brandModelLine[0].ModelLine[j].allDes[i].model_description+'</td></tr>';
                                    }
                                    kitBrand = kitBrand +'</tbody></table>';
                                }
                                kitBrand = kitBrand +'</td></tr>';
                            }                          
                        }
                        kitBrand = kitBrand +'</tbody></table>';
                    }
                    var kitItems = '';
                    var itemCount = (kit.kit_items).length;
                    if(itemCount > 0) {
                        kitItems = kitItems + '<table><thead><tr><th style="border: 1px solid #c4c4d4">Item</th><th style="border: 1px solid #c4c4d4">Quantity</th></thead><tbody>'
                        for(var l=0; l<itemCount; l++) {
                            kitItems = kitItems + '<tr><td style="border: 1px solid #c4c4d4">'+kit.kit_items[l].item.addon.name;
                            if(kit.kit_items[l].addon.addon_description.description != null) {
                                kitItems = kitItems + ' - '+kit.kit_items[l].addon.addon_description.description;
                            }
                            kitItems = kitItems +'</td><td style="border: 1px solid #c4c4d4">'+kit.kit_items[l].quantity+'</td></tr>'
                        }
                        kitItems = kitItems + '</tbody></table>'
                    }
                    console.log(kit);
                    if(kit.additional_remarks != null) {
                        var kitAdditionalRemarks = '';
                    }
                    else {
                        var kitAdditionalRemarks = kit.additional_remarks;
                    }
                    if(kit.fixing_charges_included == 'yes') {
                        var kitFixingCharge = 'Included';
                    }
                    else {
                        var kitFixingCharge = kit.fixing_charge_amount + ' AED';
                    }
                    if(kit.selling_price != null) {
                        if(kit.selling_price.selling_price != '0.00' || kit.selling_price.selling_price != null) {
                            var kitSellingPrice = kit.selling_price.selling_price;
                        }
                    }
                    else if(kit.pending_selling_price != null) {
                        if(kit.pending_selling_price != null) {
                            if(kit.pending_selling_price != '0.00' || kit.pending_selling_price.selling_price != null) {
                                var kitSellingPrice = kit.pending_selling_price.selling_price + ' (Approval Awaiting)';
                            }
                        }
                    }
                    else {
                        var kitSellingPrice = 'Not Added';
                    }
                    return [
                            slNo,
                            kitName,
                            kit.addon_code,
                            kitBrandName,
                            kitBrand,
                            kitItems,
                            // kitAdditionalRemarks,
                            // kitFixingCharge,
                            // // kit.LeastPurchasePrices.purchase_price_aed,
                            kitSellingPrice,
                            addButton,
                        ];
                });
                if ($.fn.dataTable.isDataTable('#dtBasicExample4')) {
                    $('#dtBasicExample4').DataTable().destroy();
                }
                $('#dtBasicExample4').DataTable({
                    data: data,
                    columns: [
                        { title: 'ID' },
                        { title: 'Kit Name' },
                        { title: 'Kit Code' },
                        { title: 'Brand' },
                        { title: 'Model Lines/Model Description' },
                        { title: 'Items/ Quantity'},
                        // { title: 'Additional Remarks' },
                        // { title: 'Fixing Charge'},
                        // // { title: 'Least Purchase Price(AED)'}
                        { title: 'Selling Price(AED)'},
                        {
                            title: 'Add Into Quotation',
                            render: function(data, type, row) {
                                return '<div class="circle-button kit-add-button" data-kit-id="' + row[0] + '"></div>';
                            }
                        }
                    ]
                });
            }
        });
    });
    function checkIfRowIsEditable(callId) {
        var isEditable = false;
        $.ajax({
            type: 'GET',
            url: '{{ route('booking.checkingso') }}',
            data: { call_id: callId},
            async: false,
            success: function (response) {
                isEditable = response.editable;
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        return isEditable;
    }
        });

    </script>
@endpush
