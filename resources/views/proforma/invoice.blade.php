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
					<div class="col" >
						<button type="button" class="btn btn-outline-warning" data-table="vehicle-table" id="directadding-button">Directly Adding Into Quotation</button>
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
                        <!-- <option value="allbrands">ALL BRANDS</option> -->
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
                    <div class="col" >
                        <button type="button" class="btn btn-outline-warning" data-table="accessories-table" id="directadding-button">Directly Adding Into Quotation</button>
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
								<th>Accessory Code</th>
								<th>Accessory Name</th>
								<th>Brand/Model Line</th>
								<th>Selling Price(AED)</th>
								<th>Additional Remarks</th>
								<th>Fixing Charge</th>
								<!-- <th>Least Purchase Price(AED)</th> -->
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
                    <div class="col" >
                        <button type="button" class="btn btn-outline-warning" data-table="spare-part-table" id="directadding-button">Directly Adding Into Quotation</button>
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
								<th>Spare Part Code</th>
								<th>Spare Part Name</th>
								<th>Brand/Model Line/Model Description</th>
								<!-- <th>Model Line/Model Description/Model Year</th> -->
								<th>Selling Price(AED)</th>
                                <th>Part Numbers</th>
								<th>Additional Remarks</th>
								<th>Fixing Charge</th>
								<!-- <th>Least Purchase Price(AED)</th> -->
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
						<button type="button" class="btn btn-outline-warning" data-table="kit-table" id="directadding-button">Directly Adding Into Quotation</button>
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
								<th>Kit Code</th>
								<th>Kit Name</th>
								<th>Brand/Model Line/Model Description</th>
								<!-- <th>Model Line/Model Description</th> -->
                                <th>Items/ Quantity</th>
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
        <div class="card">
            <div class="card-header">
                <h4>Available Shipping Charges</h4>
            </div>
            <div class="card-body">
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
                                    <th style="width:30px;">Add Into Quotation</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                    @foreach($shippings as $shipping)
                                        <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $shipping->code }} </td>
                                        <td>{{ $shipping->name }}</td>
                                        <td>{{ $shipping->description  }}</td>
                                        <td>{{ $shipping->price }}</td>
                                        <td>
                                            <button class="add-button circle-button" data-button-type="Shipping"></button>
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

	</div>
	<div id="shippingDocumentContent" class="contentveh">
        <hr>
        <br>
        <div class="card">
            <div class="card-header">
                <h4>Available Shipping Documents</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="shipping-document-table" class="table table-striped table-editable table-edits table">
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>Code</th>
                                    <th>Addon Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th style="width:30px;">Add Into Quotation</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                    @foreach($shippingDocuments as $shippingDocument)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $shippingDocument->code }}</td>
                                            <td>{{ $shippingDocument->name }}</td>
                                            <td>{{ $shippingDocument->description  }}</td>
                                            <td>{{ $shippingDocument->price }}</td>
                                            <td>
                                                <button class="add-button circle-button" data-button-type="Shipping-Document"></button>
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

    </div>
    <div id="certificateContent" class="contentveh">
        <hr>
        <br>
        <div class="card">
            <div class="card-header">
                <h4>Available Certifications</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="certification-table" class="table table-striped table-editable table-edits table">
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>Code</th>
                                    <th>Addon Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th style="width:30px;">Add Into Quotation</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                    @foreach($certifications as $certification)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $certification->code }}</td>
                                            <td>{{ $certification->name }}</td>
                                            <td>{{ $certification->description  }}</td>
                                            <td>{{ $certification->price }}</td>
                                            <td>
                                                <button class="add-button circle-button" data-button-type="Certification"></button>
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

    </div>
    <div id="otherContent" class="contentveh">
        <hr>
        <br>
        <div class="card">
            <div class="card-header">
                <h4>Available Other Documents</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="other-document-table" class="table table-striped table-editable table-edits table">
                                <thead class="bg-soft-secondary">
                                <tr>
                                    <th>S.No</th>
                                    <th>Code</th>
                                    <th>Addon Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th style="width:30px;">Add Into Quotation</th>
                                </tr>
                                </thead>
                                <tbody>
                                <div hidden>{{$i=0;}}
                                    @foreach($otherDocuments as $otherDocument)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $otherDocument->code }}</td>
                                            <td>{{ $otherDocument->name }}</td>
                                            <td>{{ $otherDocument->description  }}</td>
                                            <td>{{ $otherDocument->price }}</td>
                                            <td>
                                                <button class="add-button circle-button" data-button-type="Other"></button>
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
        var shippingTable = $('#shipping-table').DataTable();
        var shippingDocumentTable = $('#shipping-document-table').DataTable();
        var certificationTable = $('#certification-table').DataTable();
        var otherTable = $('#other-document-table').DataTable();

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
                    // $('#accessories_model_line').append('<option value="allmodellines">All Model Lines</option>');
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
                render: function (data, type, row) {
                if(row['button_type'] == 'Direct-Add') {
                    return '<button class="circle-buttonr remove-button"  data-button-type="Direct-Add"  >Remove</button>';

                }else{
                    return '<button class="circle-buttonr remove-button" >Remove</button>';

                }
            }
            // defaultContent: '<button class="circle-buttonr remove-button" >Remove</button>'
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

                    var combinedValue = "";
                    if(row['button_type'] == 'Vehicle') {
                        var brand = row[3];
                        var modelDescription = row[5];
                        var interiorColor = row[8];
                        var exteriorColor = row[9];
                        var combinedValue = brand + ', ' + modelDescription + ', ' + interiorColor + ', ' + exteriorColor;
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {
                        combinedValue = row[2]+', '+row[3];
                    }
                    else if(row['button_type'] == 'Direct-Add') {
                        combinedValue =  row[0] + ', ' + row[1] + ', ' + row[2] + ', ' + row[3];
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        combinedValue = row[2] + ' , ' + row[3];

                        combinedValue = row[2]+', '+row[3];
                    }else if(row['button_type'] == 'Direct-Add') {
                        var comma0 = comma1 = comma2 = comma3 = comma4 = ", ";
                        if(row[1] == "") {
                            var comma0 = " ";
                        }
                        if(row[2] == "") {
                            var comma1 = " ";
                        }
                        if(row[3] == "") {
                            var comma2 = " ";
                        }
                        if(row[4] == "") {
                            var comma3 = " ";
                        }
                        if(row[5] == "") {
                            var comma4 = " ";
                        }

                        combinedValue =  row[0] + comma0 + row[1] + comma1 + row[2] + comma2 + row[3]+ comma3 + row[4] + comma4 + row[5];
                    }
                    else if(['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        combinedValue = 'koo';
                    }
                    return '<input type="text" class="combined-value-editable form-control" value="' + combinedValue + '"/>';
                }
            },
            {
                targets: -5,
                data: null,
                render: function (data, type, row) {
                    var code = "";
                    if(row['button_type'] == 'Vehicle') {
                        var code = row[6];
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {

                        var code = row[1];
                    }else if(row['button_type'] == 'Direct-Add') {
                        var code = row[6];
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        code = row[1];
                    }
                    return code;
                }
            },
            {
                targets: -4,
                data: null,
                render: function (data, type, row) {
                    var price = "";
                    if(row['button_type'] == 'Vehicle') {
                        var price = row[10];
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {
                        var price = row[4];
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        var price = row[4];
                    }
                    return '<input type="text" class="price-editable form-control" value="' + price + '"/>';
                }
            }
        ]
    });
    $('#dtBasicExample2 tbody').on('click', '.remove-button', function(e) {
        // var row = secondTable.row($(this).parents('tr'));
        let row = secondTable.row(e.target.closest('tr')).data();
        // var row = $(this).closest('tr');
        if(row['button_type'] == 'Shipping') {
            var table = $('#shipping-table').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Shipping" ></button>']).draw();
        }
        else if(row['button_type'] == 'Vehicle') {
            var table = $('#dtBasicExample1').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],row[6],row[7],row[8],row[9],row[10],
                '<button class="add-button circle-button" data-button-type="Vehicle" ></button>']).draw();
        }
        else if(row['button_type'] == 'Shipping-Document') {
            var table = shippingDocumentTable;
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Shipping-Document" ></button>']).draw();
        }
        else if(row['button_type'] == 'Certification') {
            var table = certificationTable;
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Certification" ></button>']).draw();
        }
        else if(row['button_type'] == 'Other') {
            var table = otherTable;
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Other" ></button>']).draw();
        }
        else if(row['button_type'] == 'Accessory') {
            var table = $('#dtBasicExample5').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],row[6],'<button class="add-button circle-button" data-button-type="Accessory" ></button>']).draw();
        }
        else if(row['button_type'] == 'SparePart') {
            var table = $('#dtBasicExample3').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],row[6],row[7],'<button class="add-button circle-button" data-button-type="SparePart" ></button>']).draw();
        }
        else if(row['button_type'] == 'Kit') {
            var table = $('#dtBasicExample4').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],'<button class="add-button circle-button" data-button-type="Kit" ></button>']).draw();
        }

        var index = $(this).closest('tr').index();
        secondTable.row(index).remove().draw();
        if(row['button_type'] != 'Direct-Add') {
            // alert("ok");
            resetSerialNumber(table);
        }
        // alert("inside romove function");
        // var rowData = row.data();
        // var vehicleIdToRemove = rowData[0];
        // moveRowToFirstTable(vehicleIdToRemove);
    });
    // function moveRowToFirstTable(vehicleId) {
    //     var firstTable = $('#dtBasicExample1').DataTable();
    //     var secondTable = $('#dtBasicExample2').DataTable();
    //     var secondTableRow = secondTable.rows().indexes().filter(function(value, index) {
    //         return secondTable.cell(value, 0).data() == vehicleId;
    //     });
    //     // console.log(secondTableRow.length);
    //     if (secondTableRow.length > 0) {
    //         console.log("inside removal");
    //
    //         var rowData = secondTable.row(secondTableRow).data();
    //         firstTable.row.add(rowData).draw();
    //         secondTable.row(secondTableRow).remove().draw();
    //     }
    //
    // }
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
    // $(document).on('click', '.remove-button', function() {
    //     var row = $(this).closest('tr');
    //     var rowData = [];
    //     row.find('td').each(function() {
    //         rowData.push($(this).text());
    //     });
    //     moveRowToFirstTable(rowData);
    // });

    $(document).on('click', '#directadding-button', function() {
        var tableType = $(this).attr('data-table');
        var table = $('#dtBasicExample2').DataTable();
        var row = [];
        var addon = "";
        var modelLine = "";
        var modelNumber = "";
        var variant = "";
        var interiorColor = "";
        var exteriorColor = "";

        if(tableType == 'vehicle-table') {

            var brand = $('#brand option:selected').val();
            if(brand != "") {

                var brand = $('#brand option:selected').text();
            }
            var modelLine = $('#model_line option:selected').val();
            if(modelLine != "") {

                var modelLine = $('#model_line option:selected').text();
            }
            var variant = $("#variant option:selected").val();
            if(variant != "") {

                var variant = $('#variant option:selected').text();

            }
            var interiorColor = $('#interior_color option:selected').val();
            if(interiorColor != "") {

                var interiorColor = $('#interior_color option:selected').text();

            }
            var exteriorColor = $('#exterior_color option:selected').val();
            if(exteriorColor != "") {

                var exteriorColor = $('#exterior_color option:selected').text();
            }

        }else if(tableType == 'accessories-table') {

            var addon =  $('#accessories_addon option:selected').val();
            if(addon != "") {

                var addon = $('#accessories_addon option:selected').text();

            }
            var brand = $('#accessories_brand option:selected').val();
            if(brand != "") {

                var brand = $('#accessories_brand option:selected').text();

            }
            var modelLine = $('#accessories_model_line option:selected').val();
            if(modelLine != "") {

                var modelLine = $('#accessories_model_line option:selected').text();

            }
        }else if(tableType == 'spare-part-table') {

            var addon =  $('#spare_parts_addon option:selected').val();
            if(addon != "") {

                var addon = $('#spare_parts_addon option:selected').text();

            }
            var brand = $('#spare_parts_brand option:selected').val();
            if(brand != "") {

                var brand = $('#spare_parts_brand option:selected').text();
            }
            var modelLine = $('#spare_parts_model_line option:selected').val();
            if(modelLine != "") {

                var modelLine = $('#spare_parts_model_line option:selected').text();
            }
            var modelNumber = $('#spare_parts_model_description option:selected').val();
            if(modelNumber != "") {

                var modelNumber = $('#spare_parts_model_description option:selected').text();
            }

        }else if(tableType == 'kit-table') {

            var addon =  $('#kit_addon option:selected').val();
            if(addon != "") {

                var addon = $('#kit_addon option:selected').text();

            }
            var brand = $('#kit_brand option:selected').val();
            if(brand != "") {

                var brand = $('#kit_brand option:selected').text();

            }
            var modelLine = $('#kit_model_line option:selected').val();
            if(modelLine != "") {

                var modelLine = $('#kit_model_line option:selected').text();

            }
            var modelNumber = $('#kit_model_description option:selected').val();
            if(modelNumber != "") {

                var modelNumber = $('#kit_model_description option:selected').text();
            }
        }


        row.push(addon);
        row.push(brand);
        row.push(modelLine);
        row.push(modelNumber);
        row.push(interiorColor);
        row.push(exteriorColor);
        row.push(variant);
        row['button_type'] = 'Direct-Add';
        table.row.add(row).draw();

    });
    $(document).on('click', '.add-button', function() {
        var vehicleId = $(this).data('vehicle-id');
        console.log('Add button clicked for vehicle ID:', vehicleId);
        var rowData = [];
        var buttonType = $(this).data('button-type');

        var row = $(this).closest('tr');
        rowData['button_type'] = buttonType;
        row.find('td').each(function() {
            rowData.push($(this).text());
        });
        var secondTable = $('#dtBasicExample2').DataTable();
        secondTable.row.add(rowData).draw();
        if(buttonType == 'Shipping') {
            var table = shippingTable;
        }
        else if(buttonType == 'Shipping-Document') {
            var table = shippingDocumentTable;
        }
        else if(buttonType == 'Certification') {
            var table = certificationTable;
        }
        else if(buttonType == 'Other') {
            var table = otherTable;
        }
        else if(buttonType == 'Vehicle') {
            var table = $('#dtBasicExample1').DataTable();
        }
        else if(buttonType == 'Accessory') {
            var table = $('#dtBasicExample5').DataTable();
        }
        else if(buttonType == 'SparePart') {
            var table = $('#dtBasicExample3').DataTable();
        }
        else if(buttonType == 'Kit') {
            var table = $('#dtBasicExample4').DataTable();
        }
        table.row(row).remove().draw();
        resetSerialNumber(table);
    });
    function resetSerialNumber(table) {
        table.$('tbody tr').each(function(i){
            $($(this).find('td')[0]).html(i+1);
        });
    }
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
                    var addButton = '<button class="add-button" data-button-type="Vehicle" data-vehicle-id="' + vehicle.id + '">Add</button>';
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
                                return '<div class="circle-button add-button" data-button-type="Vehicle" data-vehicle-id="' + row[0] + '"></div>';
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
                    var addButton = '<button class="add-button" data-button-type="Accessory" data-accessory-id="' + accessory.id + '">Add</button>';
                    if(accessory.addon_description.description != null) {
                       var accessoryName = accessory.addon_description.addon.name + ' - ' + accessory.addon_description.description;
                    }
                    else {
                        var accessoryName = accessory.addon_description.addon.name;
                    }
                    // if(accessory.is_all_brands == 'yes') {
                    //     var accessoryBrand = 'All Brands'
                    // }
                    // else {
                        var accessoryBrand = accessory.brandModelLine;
                        // var size = 0;
                        // size = (accessory.brandModelLine).length;
                        // if(size > 0) {
                        //     var accessoryBrand = '<table><thead><tr><th style="border: 1px solid #c4c4d4">Brand</th><th style="border: 1px solid #c4c4d4">Model Line</th></tr></thead><tbody>';
                        //     for(var i=0; i < size; i++) {
                        //         accessoryBrand = accessoryBrand +'<tr><td style="border: 1px solid #c4c4d4">'+accessory.brandModelLine[i].brands.brand_name+'</td>';
                        //         if(accessory.brandModelLine[i].is_all_model_lines == 'yes') {
                        //             accessoryBrand = accessoryBrand +'<td style="border: 1px solid #c4c4d4">All Model Lines</td>';
                        //         }
                        //         else {
                        //             accessoryBrand = accessoryBrand +'<td style="border: 1px solid #c4c4d4">';
                        //             var modelLineSize = 0;
                        //             modelLineSize = (accessory.brandModelLine[i].ModelLine).length;
                        //             if(modelLineSize > 0) {
                        //                 accessoryBrand = accessoryBrand + '<table><tbody>';
                        //                 for(var j=0; j < modelLineSize; j++) {
                        //                     accessoryBrand = accessoryBrand + '<tr><td>'+ accessory.brandModelLine[i].ModelLine[j].model_lines.model_line +'</td></tr>';
                        //                 }
                        //                 accessoryBrand = accessoryBrand + '</tbody></table>';
                        //             }
                        //             accessoryBrand = accessoryBrand +'</td>';
                        //         }
                        //         accessoryBrand = accessoryBrand +'</tr>';
                        //     }
                        //     accessoryBrand = accessoryBrand +'</tbody></table>';
                        // }
                    // }
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
                    // else if(accessory.pending_selling_price != null) {
                    //     if(accessory.pending_selling_price != null) {
                    //         if(accessory.pending_selling_price != '0.00' || accessory.pending_selling_price.selling_price != null) {
                    //             var accessorySellingPrice = accessory.pending_selling_price.selling_price + ' (Approval Awaiting)';
                    //         }
                    //     }
                    // }
                    // else {
                    //     var accessorySellingPrice = 'Not Added';
                    // }
                    else {
                        var accessorySellingPrice = '';
                    }
                    return [
                            slNo,
                            accessory.addon_code,
                            accessoryName,
                            accessoryBrand,
                            accessorySellingPrice,
                            accessoryAdditionalRemarks,
                            accessoryFixingCharge,
                            // accessory.LeastPurchasePrices.purchase_price_aed,
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
                        { title: 'Accessory Code' },
                        { title: 'Accessory Name' },
                        { title: 'Brand/Model Lines' },
                        { title: 'Selling Price(AED)'},
                        { title: 'Additional Remarks' },
                        { title: 'Fixing Charge'},
                        // { title: 'Least Purchase Price(AED)'}
                        {
                            title: 'Add Into Quotation',
                            render: function(data, type, row) {
                                return '<div class="circle-button add-button" data-button-type="Accessory" data-accessory-id="' + row[0] + '"></div>';
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
                    console.log(sparePart);
                    slNo = slNo + 1;
                    var addButton = '<button class="add-button" data-button-type="SparePart" data-sparepart-id="' + sparePart.id + '">Add</button>';
                    if(sparePart.addon_description.description != null) {
                       var sparePartName = sparePart.addon_description.addon.name + ' - ' + sparePart.addon_description.description;
                    }
                    else {
                        var sparePartName = sparePart.addon_description.addon.name;
                    }
                    // if(sparePart.is_all_brands == 'no') {
                    //     var sparePartBrandName = sparePart.brandModelLine[0].brands.brand_name;
                    //     var sparePartBrand = '<table><thead><tr><th style="border: 1px solid #c4c4d4">Model Line</th><th style="border: 1px solid #c4c4d4">Model Description</th><th style="border: 1px solid #c4c4d4">Model year</th></tr></thead><tbody>';
                    //     var modelLineSize = 0;
                    //     modelLineSize = (sparePart.brandModelLine[0].ModelLine).length;
                    //     if(modelLineSize > 0) {
                    //         for(var j=0; j < modelLineSize; j++) {
                    //             sparePartBrand = sparePartBrand +'<tr><td style="border: 1px solid #c4c4d4">'+sparePart.brandModelLine[0].ModelLine[j].model_lines.model_line+'</td><td style="border: 1px solid #c4c4d4">';
                    //             var modelDescSize = 0;
                    //             modelDescSize = (sparePart.brandModelLine[0].ModelLine[j].allDes).length;
                    //             if(modelDescSize > 0) {
                    //                 sparePartBrand = sparePartBrand +'<table><tbody>';
                    //                 for(var i=0; i < modelDescSize; i++) {
                    //                     sparePartBrand = sparePartBrand +'<tr><td>';
                    //                     if(i != 0) {
                    //                         sparePartBrand = sparePartBrand +'<br style="line-height: 3px">';
                    //                     }
                    //                     sparePartBrand = sparePartBrand +sparePart.brandModelLine[0].ModelLine[j].allDes[i].model_description+'</td></tr>';
                    //                 }
                    //                 sparePartBrand = sparePartBrand +'</tbody></table>';
                    //             }
                    //             sparePartBrand = sparePartBrand +'</td><td style="border: 1px solid #c4c4d4">'+sparePart.brandModelLine[0].ModelLine[j].model_year_start;
                    //             if(sparePart.brandModelLine[0].ModelLine[j].model_year_end != null) {
                    //                 sparePartBrand = sparePartBrand +' - '+sparePart.brandModelLine[0].ModelLine[j].model_year_end;
                    //             }
                    //             sparePartBrand = sparePartBrand +'</td></tr>';
                    //         }
                    //     }
                    //     sparePartBrand = sparePartBrand +'</tbody></table>';
                    // }
                    var sparePartBrandName = sparePart.brandModelLineModelDescription;
                    var sparePartNumber = '';
                    var partNumbersSize = 0;
                    partNumbersSize = (sparePart.part_numbers).length;
                    if(partNumbersSize > 0) {
                        for(var k=0; k < partNumbersSize; k++) {
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
                    // else if(sparePart.pending_selling_price != null) {
                    //     if(sparePart.pending_selling_price != null) {
                    //         if(sparePart.pending_selling_price != '0.00' || sparePart.pending_selling_price.selling_price != null) {
                    //             var sparePartSellingPrice = sparePart.pending_selling_price.selling_price + ' (Approval Awaiting)';
                    //         }
                    //     }
                    // }
                    // else {
                    //     var sparePartSellingPrice = 'Not Added';
                    // }
                    else {
                        var sparePartSellingPrice = '';
                    }
                    return [
                            slNo,
                            sparePart.addon_code,
                            sparePartName,
                            sparePartBrandName,
                            // sparePartBrand,
                            sparePartSellingPrice,
                            sparePartNumber,
                            sparePartAdditionalRemarks,
                            sparePartFixingCharge,
                            // sparePart.LeastPurchasePrices.purchase_price_aed,
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
                        { title: 'Spare Part Code' },
                        { title: 'Spare Part Name' },
                        { title: 'Brand/Model Lines/Model Description' },
                        { title: 'Selling Price(AED)'},
                        // { title: 'Model Lines/Model Description/Model Year' },
                        { title: 'Part Numbers' },
                        { title: 'Additional Remarks' },
                        { title: 'Fixing Charge'},
                        // { title: 'Least Purchase Price(AED)'}
                        {
                            title: 'Add Into Quotation',
                            render: function(data, type, row) {
                                return '<div class="circle-button add-button" data-button-type="SparePart" data-sparepart-id="' + row[0] + '"></div>';
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
            success: function(response) {
                var slNo = 0;
                var data = response.map(function(kit) {
                    slNo = slNo + 1;
                    var addButton = '<button class="add-button" data-button-type="Kit" data-kit-id="' + kit.id + '">Add</button>';
                    var kitName = '';
                    if(kit.addon_name.name != null) {
                       kitName = kit.addon_name.name;
                    }
                    var kitBrandName = kit.brandModelLineModelDescription;
                    // if(kit.is_all_brands == 'no') {
                    //     var kitBrandName = kit.brandModelLine[0].brands.brand_name;
                    //     var kitBrand = '<table><thead><tr><th style="border: 1px solid #c4c4d4">Model Line</th><th style="border: 1px solid #c4c4d4">Model Description</th></thead><tbody>';
                    //     var modelLineSize = 0;
                    //     modelLineSize = (kit.brandModelLine[0].ModelLine).length;
                    //     if(modelLineSize > 0) {
                    //         for(var j=0; j < modelLineSize; j++) {
                    //             kitBrand = kitBrand +'<tr><td style="border: 1px solid #c4c4d4">'+kit.brandModelLine[0].ModelLine[j].model_lines.model_line+'</td><td style="border: 1px solid #c4c4d4">';
                    //             var modelDescSize = 0;
                    //             modelDescSize = (kit.brandModelLine[0].ModelLine[j].allDes).length;
                    //             if(modelDescSize > 0) {
                    //                 kitBrand = kitBrand +'<table><tbody>';
                    //                 for(var i=0; i < modelDescSize; i++) {
                    //                     kitBrand = kitBrand +'<tr><td>';
                    //                     if(i != 0) {
                    //                         kitBrand = kitBrand +'<br style="line-height: 3px">';
                    //                     }
                    //                     kitBrand = kitBrand +kit.brandModelLine[0].ModelLine[j].allDes[i].model_description+'</td></tr>';
                    //                 }
                    //                 kitBrand = kitBrand +'</tbody></table>';
                    //             }
                    //             kitBrand = kitBrand +'</td></tr>';
                    //         }
                    //     }
                    //     kitBrand = kitBrand +'</tbody></table>';
                    // }
                    var kitItems = '';
                    var itemCount = (kit.kit_items).length;
                    if(itemCount > 0) {
                        kitItems = kitItems + '<table><thead><tr><th style="border: 1px solid #c4c4d4">Item</th><th style="border: 1px solid #c4c4d4">Quantity</th></thead><tbody>'
                        for(var l=0; l<itemCount; l++) {
                            console.log(kit.kit_items[l].item.description);
                            kitItems = kitItems + '<tr><td style="border: 1px solid #c4c4d4">'+kit.kit_items[l].item.addon.name;
                            if(kit.kit_items[l].item.description != null) {
                                kitItems = kitItems + ' - '+kit.kit_items[l].item.description;
                            }
                            kitItems = kitItems +'</td><td style="border: 1px solid #c4c4d4">'+kit.kit_items[l].quantity+'</td></tr>'
                        }
                        kitItems = kitItems + '</tbody></table>'
                    }
                    if(kit.selling_price != null) {
                        if(kit.selling_price.selling_price != '0.00' || kit.selling_price.selling_price != null) {
                            var kitSellingPrice = kit.selling_price.selling_price;
                        }
                    }
                    // else if(kit.pending_selling_price != null) {
                    //     if(kit.pending_selling_price != null) {
                    //         if(kit.pending_selling_price != '0.00' || kit.pending_selling_price.selling_price != null) {
                    //             var kitSellingPrice = kit.pending_selling_price.selling_price + ' (Approval Awaiting)';
                    //         }
                    //     }
                    // }
                    // else {
                    //     var kitSellingPrice = 'Not Added';
                    // }
                    else {
                        var kitSellingPrice = '';
                    }
                    return [
                            slNo,
                            kit.addon_code,
                            kitName,
                            kitBrandName,
                            // kitBrand,
                            kitSellingPrice,
                            kitItems,
                            // kit.LeastPurchasePrices.purchase_price_aed,
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
                        { title: 'Kit Code' },
                        { title: 'Kit Name' },
                        { title: 'Brand/Model Lines/Model Description' },
                        { title: 'Selling Price(AED)'},
                        // { title: 'Model Lines/Model Description' },
                        { title: 'Items/ Quantity'},
                        // { title: 'Least Purchase Price(AED)'}
                        {
                            title: 'Add Into Quotation',
                            render: function(data, type, row) {
                                return '<div class="circle-button add-button" data-button-type="Kit" data-kit-id="' + row[0] + '"></div>';
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
