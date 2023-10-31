@extends('layouts.main')
<style>
	.labellist{
	margin-top: 5px;
	}
	.modal
	{
	width: 100% !important;
	height: 100% !important;
	top: 0%!important;
	left: 0%!important;
	}
	/* .related-addon-header
	{
	background-color:#5156be;
	}
	.related-addon-h4
	{
	padding-top:8px;
	padding-bottom:8px;
	text-align:center;
	color:white;
	} */
	.each-addon
	{
	border-style: solid;
	border-width: 1px;
	border-color: white;
	border-radius: 5px;
	/* margin-top: 10px; */
	padding-top:10px;
	padding-bottom:10px;
	background-color:#f2f2f2;
	}
	/* .related-addon input
	{
	padding-top:0px;
	padding-bottom:0px;
	padding-right:0px;
	padding-left:0px;
	} */
	.related-label
	{
	padding-top:0px;
	padding-bottom:0px;
	}
	/* .related-addon .related-input-div
	{
	margin-top:0px;
	margin-bottom:0px;
	margin-right:0px;
	margin-left:0px;
	} */
	.list2
	{
	margin-right:10px;
	margin-left:10px;
	}
	.labellist
	{
	border-style: solid;
	border-width: 1px;
	border-color: #5156be;
	border-radius: 5px;
	}
	.labeldesign
	{
	background-color:#6266c4;
	color:white;
	border-color: white;
	}
	.databack1
	{
	background-color:#e6e6ff;
	border-color: white;
	}
	.databack2
	{
	background-color:#f2f2f2;
	border-color: white;
	}
	#blah
	{
	width: 250px;
	height: 250px;
	padding-top:0px;
	margin-top:0px;
	}
	.contain
	{
	object-fit: contain;
	}
</style>
<style>
	body {font-family: Arial, Helvetica, sans-serif;}
	#myImg {
	border-radius: 5px;
	cursor: pointer;
	transition: 0.3s;
	}
	#myImg:hover {opacity: 0.7;}
	/* The Modal (background) */
	.modalForImage {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	padding-top: 10px; /* Location of the box */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: black; /* Fallback color */
	background-color: rgba(128,128,128,0.5);/* Black w/ opacity */
	}
	/* Modal Content (image) */
	.modalContentForImage {
	padding-top: 100px; /* Location of the box */
	margin: auto;
	display: block;
	width: 100%!important;
	height:auto!important;
	max-width: 700px!important;
	}
	/* Caption of Modal Image */
	#caption {
	margin: auto;
	display: block;
	width: 100%!important;
	max-width: 700px;
	text-align: center;
	color: #ccc;
	padding: 10px 0;
	height: 150px;
	}
	/* Add Animation */
	.modalContentForImage, #caption {
	-webkit-animation-name: zoom;
	-webkit-animation-duration: 0.6s;
	animation-name: zoom;
	animation-duration: 0.6s;
	}
	@-webkit-keyframes zoom {
	from {-webkit-transform:scale(0)}
	to {-webkit-transform:scale(1)}
	}
	@keyframes zoom {
	from {transform:scale(0)}
	to {transform:scale(1)}
	}
	/* The Close Button */
	.close {
	position: absolute;
	top: 50px;
	right: 50px;
	color: black;
	font-size: 40px;
	font-weight: bold;
	transition: 0.3s;
	}
	.close:hover,
	.close:focus {
	color: black;
	text-decoration: none;
	cursor: pointer;
	}
	/* 100% Image Width on Smaller Screens */
	@media only screen and (max-width: 700px){
	.modalContentForImage {
	width: 100%;
	}
	}
	.widthinput
	{
	height:35px!important;
	}
	.widthinput1
	{
	height:25px!important;
	}
</style>
@section('content')
@section('content')
@can('addon-view')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-view']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">
		Kit Details
	</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('kit.kitItems',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first" href="{{ route('kit.kitItems',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	<a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
	@if (Session::has('success'))
	<div class="alert alert-success" id="success-alert">
		<button type="button" class="btn-close p-0 close" data-dismiss="alert"></button>
		{{ Session::get('success') }}
	</div>
	@endif
	<div class="row">
		<div class="col-xxl-4 col-lg-4 col-md-4">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<label for="choices-single-default" class="form-label">
					Kit Name :</label>
				</div>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<span>{{ $supplierAddonDetails->AddonName->name}}</span>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12">
					<label for="choices-single-default" class="form-label">
					Kit Code :</label>
				</div>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<span>{{ $supplierAddonDetails->addon_code}}</span>
				</div>
				@if($supplierAddonDetails->additional_remarks != '')
				<div class="col-lg-6 col-md-6 col-sm-12">
					<label for="choices-single-default" class="form-label"> Additional Remarks :</label>
				</div>
				<div class="col-lg-6 col-md-9 col-sm-12">
					<span>{{ $supplierAddonDetails->additional_remarks}}</span>
				</div>
				@endif
				@can('addon-selling-price-view')
				@php
				$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-selling-price-view']);
				@endphp
				@if ($hasPermission)
				@if($supplierAddonDetails->SellingPrice!= null OR $supplierAddonDetails->PendingSellingPrice!= null)
				<div class="col-lg-6 col-md-6 col-sm-12">
					<label for="choices-single-default" class="form-label"> Selling Price :</label>
				</div>
				<div class="col-lg-6 col-md-9 col-sm-12">
					@if($supplierAddonDetails->SellingPrice!= null)
					@if($supplierAddonDetails->SellingPrice->selling_price != '')
					{{$supplierAddonDetails->SellingPrice->selling_price}} AED
					@endif
					@elseif($supplierAddonDetails->PendingSellingPrice!= null)
					@if($supplierAddonDetails->PendingSellingPrice->selling_price != '')
					{{$supplierAddonDetails->PendingSellingPrice->selling_price}} AED
					<label class="badge badge-soft-danger">Approval Awaiting</label>
					@endif
					@endif
				</div>
				@endif
				@endif
				@endcan
			</div>
		</div>
		<div class="col-xxl-5 col-lg-5 col-md-5">
			<div class="row">
				@if($supplierAddonDetails->addon_type_name == 'K')
				@if($supplierAddonDetails->is_all_brands == 'no')
				<div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
					<center>Brand</center>
				</div>
				<div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
					<center>Model Line</center>
				</div>
				<div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
					<center>Model Description</center>
				</div>
				@foreach($supplierAddonDetails->AddonTypes as $AddonTypes)
				<div class="divcolorclass" value="5" hidden>
				</div>
				<div class="divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">
					{{$AddonTypes->brands->brand_name}}
				</div>
				<div class="divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">
					@if($AddonTypes->is_all_model_lines == 'yes')
					All Model Lines
					@else
					{{$AddonTypes->modelLines->model_line}}
					@endif
				</div>
				<div class="divcolor labellist databack1 col-xxl-4 col-lg-4 col-md-4">
					{{$AddonTypes->modelDescription->model_description ?? ''}}
				</div>
				@endforeach
				@endif
				@endif
			</div>
		</div>
		<div class="col-xxl-3 col-lg-3 col-md-3">
			<div class="row">
				</br>
				<center>
					@if($supplierAddonDetails->image)
					@if (file_exists(public_path().'/addon_image/'.$supplierAddonDetails->image))
					<img id="blah" src="{{ url('addon_image/' . $supplierAddonDetails->image) }}" alt="Addon image"
						class="contain image-click-class"
						data-modal-id="showImageModal"/>
                                    @else
                                    <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                    @endif
					
					@else<img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
						style="width:200px; height: 200px;;" alt="Addon Image"  />
					@endif
				</center>
			</div>
		</div>
	</div>
	</br>
	<!--kit common items start-->
	<center>
		<h5 class="card-title">
			Kit Items and Prices
		</h5>
	</center>
	</br>
	<!-- <div class="card-body"> -->
	<form action="{{ route('kit.priceStore') }}" method="POST">
		@csrf
		<input type="text" class="form-control widthinput" name="addon_details_id"
			placeholder="" value="{{$supplierAddonDetails->id}}" hidden>
		<div class="row" id="purchase_price_row" style="padding-left:10px; padding-right:10px;">
			<div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px; margin-top:10px;">
				Previous Purchase Price (AED)
			</div>
			<div class="labellist databack1 col-xxl-2 col-lg-2 col-md-2" style="margin-top:10px;">
				<input type="text" class="form-control widthinput" placeholder="" value="{{ $previousPurchasePrice }}" id="previous_purchase_price"
					name="previous_purchase_price" readonly>
			</div>
			<div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px; margin-top:10px;">
				Least Purchase Price (AED)
			</div>
			<div class="labellist databack1 col-xxl-2 col-lg-2 col-md-2" style="margin-top:10px;">
				<input type="text" class="form-control widthinput" id="least_purchase_price"
					placeholder="" value="{{$supplierAddonDetails->totalPrice}}" readonly>
			</div>
			<div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px; margin-top:10px;">
				Current Purchase Price (AED)
			</div>
			<div class="labellist databack1 col-xxl-2 col-lg-1 col-md-2" style="margin-top:10px;">
				<input type="text" class="form-control widthinput" name="current_purchase_price" id="current_purchase_price"
					placeholder="" value="{{$supplierAddonDetails->totalPrice}}" readonly>
			</div>
		</div>
		<div class="row" style="padding-left:10px; padding-right:10px;">
			<div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px; margin-top:10px;">
				Current Selling Price (AED)
			</div>
			<div class="labellist databack1 col-xxl-2 col-lg-1 col-md-2" style="margin-top:10px;">
				<input type="text" class="form-control widthinput" name="previous_selling_price" id="previous_selling_price"
					placeholder="" value="{{ $previousSellingPrice }}" readonly>
			</div>
			<div class="labellist labeldesign col-xxl-2 col-lg-2 col-md-2" style="padding-top:7px; margin-top:10px;">
				New Selling Price (AED)
			</div>
			<div class="labellist databack1 col-xxl-2 col-lg-1 col-md-2" style="margin-top:10px;">
				<input type="text" class="form-control widthinput" name="current_selling_price" id="current_selling_price"
					placeholder="" value="">
			</div>
			@can('create-kit-purchase-and-selling-prices')
			@php
			$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-kit-purchase-and-selling-prices']);
			@endphp
			@if ($hasPermission)
			<div class=" col-xxl-4 col-lg-1 col-md-2" style="margin-top:13px;">
				<button type="submit" class="btn btn-success btn-sm" id="submit" style="float:right;">Save New Prices</button>
			</div>
			@endif
			@endcan
		</div>
	</form>
	<!-- </div> -->
	</br>
	<div class="card-body" style="border:solid; border-color:#e9e9ef; border-width: 1px; border-radius: .25rem;">
		<div class="row">
			<div hidden>{{$i=0;}}</div>
			@foreach($supplierAddonDetails->KitItems as $Kit)
			<div id="rowIndexCount" hidden value="5">{{$i=$i+1;}}</div>
			<!-- <div class="list2" id="addonbox"> -->
			<!-- <div class="row related-addon">  -->
			<div id="" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">
				<div class="row">
					<div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">
						Item Name
					</div>
					<div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
						{{$Kit->item->Addon->name}} @if($Kit->item->description != '') - {{$Kit->item->description}} @endif
					</div>
					<div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">
						@if($Kit->countArray > 0)
						@if(isset($Kit->least_price_vendor->supplierAddonDetails))
						@if($Kit->least_price_vendor->supplierAddonDetails->image)
						@if (file_exists(public_path().'/addon_image/'.$Kit->least_price_vendor->supplierAddonDetails->image))
						<img id="addon-item-image-{{$i}}" src="{{ url('addon_image/' . $Kit->least_price_vendor->supplierAddonDetails->image) }}" class="image-click-class"
							style="width:100%; height:125px;" alt="Addon Image"  />
                                    @else
                                    <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                    @endif
						
						@endif
						@elseif(count($Kit->SpWithoutVendorPartNos) > 0)
						@if (file_exists(public_path().'/addon_image/'.$Kit->latestPartNoSp->image))
						<img id="addon-item-image-{{$i}}" src="{{ url('addon_image/' . $Kit->latestPartNoSp->image) }}" class="image-click-class"
							style="width:100%; height:125px;" alt="Addon Image"  />
                                    @else
                                    <img src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class"
                                    style="max-height:159px; max-width:232px;" alt="Addon Image"  />
                                    @endif
						
						@endif
						@else
						<img id="addon-item-image-{{$i}}" src="{{ url('addon_image/imageNotAvailable.png') }}" class="image-click-class" style="width:100%; height:125px;" alt="Addon Image"  />
						@endif
					</div>
					<div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
						<div class="row" style="padding-right:3px; padding-left:3px;">
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
								Item Code
							</div>
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
								{{--                                    {{ $Kit->item->addon_code }}--}}
								<span id="item_code_{{$i}}">
									@if(count($Kit->addon_part_numbers) > 0)
									{{ $Kit->least_price_vendor->supplierAddonDetails->addon_code}}
									@elseif(count($Kit->SpWithoutVendorPartNos) > 0)
									{{$Kit->latestPartNoSp->addon_code}}
									@else
									NOT AVAILABLE
									@endif
									<!-- @if($Kit->countArray > 0 &&  isset($Kit->least_price_vendor->supplierAddonDetails))
										{{ $Kit->least_price_vendor->supplierAddonDetails->addon_code}}
										@elseif(count($Kit->SpWithoutVendorPartNos) > 0)
										{{$Kit->latestPartNoSp}}
										NOT AVAILABLE {{$Kit->latestPartNoSp}}
										@endif -->
								</span>
								<!-- type="hidden"  -->
								<input type="hidden" id="item-code-id-{{$i}}" 
									value="@if(count($Kit->addon_part_numbers)>0){{ $Kit->least_price_vendor->supplierAddonDetails->id}}@elseif(count($Kit->SpWithoutVendorPartNos)>0){{$Kit->latestPartNoSp->id}}@else NOT AVAILABLE @endif">
								<!-- @if($Kit->countArray > 0 &&  isset($Kit->least_price_vendor->supplierAddonDetails))
									{{ $Kit->least_price_vendor->supplierAddonDetails->id}}
									@else
									NOT AVAILABLE
									@endif" id="item-code-id-{{$i}} -->
							</div>
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">
								Part Number
							</div>
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
								<select class="form-control widthinput" onchange="onChangePartNo(this, {{$i}})" autofocus id="part-number-{{$i}}">
									@if(count($Kit->addon_part_numbers) > 0)
									@foreach($Kit->addon_part_numbers as $partNumbers)
									<option data-sup="yes" value="{{$partNumbers->id}}">{{$partNumbers->part_number}}</option>
									@endforeach
									@elseif(count($Kit->SpWithoutVendorPartNos) > 0)
									@foreach($Kit->SpWithoutVendorPartNos as $partNumbers)
									<option data-sup="no" value="{{$partNumbers->id}}" class="{{$partNumbers->addondetails->addon_code}}" data-id ="{{$partNumbers->addondetails->id}}">{{$partNumbers->part_number}} </option>
									@endforeach
									@else
									<option>NOT AVAILABLE</option>
									@endif
								</select>
							</div>
							<!-- <div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
								@if(count($Kit->addon_part_numbers) > 0)
								<select class="form-control widthinput" autofocus id="part-number-{{$i}}">
								    @foreach($Kit->addon_part_numbers as $partNumbers)
								        <option  value="{{$partNumbers->id}}">{{$partNumbers->part_number}} </option>
								    @endforeach
								</select>
								@elseif(count($Kit->SpWithoutVendorPartNos) > 0)
								<select class="form-control widthinput" onchange="onChangePartNo(this, {{$i}})" autofocus id="part-number-{{$i}}">
								        @foreach($Kit->SpWithoutVendorPartNos as $partNumbers)
								        <option class="{{$partNumbers->addondetails->addon_code}}" data-id ="{{$partNumbers->addondetails->id}}"
								         value="{{$partNumbers->id}}">{{$partNumbers->part_number}} </option>
								        @endforeach
								</select>
								@else
								NOT AVAILABLE
								@endif
								</div> -->
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
								Quantity
							</div>
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
								<input id="quantity_{{$i}}"type="text" class="form-control widthinput1"
									placeholder="Previous Purchase Price" value="{{$Kit->quantity}}" readonly>
							</div>
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
								Purchase Price / Unit
							</div>
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
								@if($Kit->least_price_vendor != '')
								<input id="unit_price_{{$i}}" type="text" class="form-control widthinput1 purchase_price" name="purchase_price_aed"
									placeholder="Previous Purchase Price" value="{{$Kit->least_price_vendor->purchase_price_aed ?? ''}}" readonly>
								@else
								<input type="text" class="form-control widthinput1 purchase_price" name="purchase_price_aed"
									value="NOT AVAILABLE" readonly>
								@endif
							</div>
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">
								Total Purchase Price
							</div>
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">
								<input id="total_price_{{$i}}"type="text" class="form-control widthinput1"
									placeholder="Previous Purchase Price" value="{{$Kit->kit_item_total_purchase_price}}" readonly>
							</div>
						</div>
					</div>
					<div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4" style="padding-top:7px;">
						Item Supplier
					</div>
					<div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">
						@if(count($Kit->kit_item_vendors) > 0)
						<select id="supplier_{{$i}}" name="supplier[{{$i}}]" class="form-control widthinput" onchange="calculatePrice(this, {{$i}})"
							autofocus>
							@if(count($Kit->SpWithoutVendorPartNos) > 0)
							<option data-id="not_available" value="not_available">SUPPLIER NOT AVAILABLE</option>
							@endif
							@foreach($Kit->kit_item_vendors as $itemVendor)
							<option  data-id="{{$itemVendor->id}}" value="{{$itemVendor->purchase_price_aed}}"
							{{$itemVendor->id == $Kit->least_price_vendor->id ? 'selected' : ''}} >
							{{$itemVendor->Suppliers->supplier ?? ''}} ( {{$itemVendor->purchase_price_aed}} AED ) </option>
							@endforeach
						</select>
						@else
						NOT AVAILABLE
						@endif
					</div>
					</br>
				</div>
				<div class="row" hidden id="purchase-price-div-{{$i}}">
					<div class="col-lg-9 col-md-12 col-sm-12 p-0">
						<input type="number" id="purchase_price_{{$i}}" onkeyup="removePurchasePriceError({{$i}})"
							value="" name="purchase_price" class="form-control" placeholder="Enter Purchase Price (AED)" >
						<span id="purchase-price-error-{{$i}}"></span>
					</div>
					<div class="col-lg-3 col-md-12 col-sm-12 mb-1">
						@canany(['addon-edit'])
						@php
						$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-edit']);
						@endphp
						@if ($hasPermission)
						<button type="button" class="btn btn-info price-update-button" data-index="{{$i}}"
							data-kit-id="{{$Kit->id}}">Update</button>
						@endif
						@endcan
					</div>
				</div>
				<div class="row" >
					<div class="col-lg-12 col-md-12 col-sm-12 mb-1">
						@if($Kit->countArray > 0)
						@canany(['addon-edit'])
						@php
						$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-edit']);
						@endphp
						@if ($hasPermission)
						<button type="button" class="float-end btn btn-primary spare-part-edit-button btn-sm"
							data-kit-id="{{ $Kit->addon_details_id }}" data-index="{{$i}}" title="Add New Vendor">
						<i class="fa fa-plus" aria-hidden="true"></i> Add New Vendor</button>
						@endif
						@endcanany
						@endif
						@canany(['addon-create'])
						@php
						$hasPermission = Auth::user()->hasPermissionForSelectedRole(['addon-create']);
						@endphp
						@if ($hasPermission)
						<a class="float-end btn btn-sm btn-success" title="Add New Spare Part"
							href="{{ route('addon.create',['kit_item_id' => $Kit->id,'kit_id' => $id]) }}">
						<i class="fa fa-plus" aria-hidden="true"></i> New Spare Part
						</a>
						@endif
						@endcanany
						@if(count($Kit->kit_item_vendors) > 0)
						@can('supplier-new-purchase-price')
						@php
						$hasPermission = Auth::user()->hasPermissionForSelectedRole(['supplier-new-purchase-price']);
						@endphp
						@if ($hasPermission)
						<button type="button" id="price-show-button-{{$i}}" class="float-end btn btn-warning btn-sm purchase-price-edit-button"
							is-show="1" data-index="{{$i}}" title="Add New Purchase Price">
						<i class="fa fa-plus"></i> New Purchase Price
						</button>
						@endif
						@endcan
						@endif
					</div>
				</div>
			</div>
			<!-- </div> -->
			<!-- </div> -->
			@endforeach
		</div>
	</div>
	<!--kit common items end-->
	{{--    
	<center>
		<h5 class="card-title">--}}
			{{--    Vendors Details And Kit Items--}}
			{{--
		</h5>
	</center>
	--}}
	{{--    </br>--}}
	{{--        @foreach($supplierAddonDetails->AddonSuppliers as $AddonSuppliers)--}}
	{{--            
	<div class="card-body" style="border:solid; border-color:#e9e9ef; border-width: 1px; border-radius: .25rem;">
		--}}
		{{--                
		<div class="row">
			--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Vendor Name :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span>{{ $AddonSuppliers->Suppliers->supplier}}</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Purchase Price In AED :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span>{{ $AddonSuppliers->purchase_price_aed}} AED</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Purchase Price In USD :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span>{{ $AddonSuppliers->purchase_price_usd}} USD</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    @if($supplierAddonDetails->addon_type_name == 'SP')--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Quotation Date :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        @if($AddonSuppliers->updated_at != '')--}}
				{{--                        <span>{{ $AddonSuppliers->updated_at}}--}}
				{{--                            </span>--}}
				{{--                            @else--}}
				{{--                            <label class="badge badge-soft-info">Not Added</label>--}}
				{{--                            @endif--}}
				{{--                    
			</div>
			--}}
			{{--                    @endif--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Lead Time :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        @if($AddonSuppliers->lead_time_max != '' || $AddonSuppliers->lead_time_min != '')--}}
				{{--                        <span>{{ $AddonSuppliers->lead_time_min}}--}}
				{{--                            @if($AddonSuppliers->lead_time_max != '' && $AddonSuppliers->lead_time_min < $AddonSuppliers->lead_time_max)--}}
				{{--                            - {{$AddonSuppliers->lead_time_max}}--}}
				{{--                            @endif Days</span>--}}
				{{--                            @else--}}
				{{--                            <label class="badge badge-soft-info">Not Added</label>--}}
				{{--                            @endif--}}
				{{--                    
			</div>
			--}}
			{{--                    @if($AddonSuppliers->Suppliers->contact_number != '')--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label"> Contact Number :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span>{{ $AddonSuppliers->Suppliers->contact_number}}</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    @endif--}}
			{{--                    @if($AddonSuppliers->Suppliers->alternative_contact_number != '')--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Alternative Contact Number :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span>{{ $AddonSuppliers->Suppliers->alternative_contact_number}}</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    @endif--}}
			{{--                    @if($AddonSuppliers->Suppliers->email != '')--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Email</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span > {{ $AddonSuppliers->Suppliers->email}}</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    @endif--}}
			{{--                    @if($AddonSuppliers->Suppliers->contact_person != '')--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Contact Person :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span > {{ $AddonSuppliers->Suppliers->contact_person}}</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    @endif--}}
			{{--                    @if($AddonSuppliers->Suppliers->person_contact_by != '')--}}
			{{--                    
			<div class="col-lg-2 col-md-3 col-sm-12">--}}
				{{--                        <label for="choices-single-default" class="form-label">Person Contact By :</label>--}}
				{{--                    
			</div>
			--}}
			{{--                    
			<div class="col-lg-2 col-md-9 col-sm-12">--}}
				{{--                        <span > {{ $AddonSuppliers->Suppliers->person_contact_by}}</span>--}}
				{{--                    
			</div>
			--}}
			{{--                    @endif--}}
			{{--                
		</div>
		--}}
		{{--                </br>--}}
		{{--                
		<div class="row">
			--}}
			{{--                    @foreach($AddonSuppliers->Kit as $Kit)--}}
			{{--                        <!-- <div class="list2" id="addonbox"> -->--}}
			{{--                            <!-- <div class="row related-addon">  -->--}}
			{{--                                
			<div id="" class="each-addon col-xxl-4 col-lg-4 col-md-6 col-sm-12">
				--}}
				{{--                                    
				<div class="row">
					--}}
					{{--                                        
					<div class="labellist labeldesign col-xxl-4 col-lg-4 col-md-4">--}}
						{{--                                            Item Name--}}
						{{--                                        
					</div>
					--}}
					{{--                                        
					<div class="labellist databack1 col-xxl-8 col-lg-8 col-md-8">--}}
						{{--                                            {{$Kit->addon->AddonName->name}}--}}
						{{--                                        
					</div>
					--}}
					{{--                                        
					<div class="col-xxl-5 col-lg-5 col-md-4 col-sm-4" style="padding-right:3px; padding-left:3px;">--}}
						{{--                                            <img id="myImg" src="{{ asset('addon_image/' . $Kit->addon->image) }}" class="image-click-class"--}}
						{{--                                            style="width:100%; height:125px;" alt="Addon Image"  />--}}
						{{--                                        
					</div>
					--}}
					{{--                                        
					<div class="col-xxl-7 col-lg-7 col-md-8 col-sm-8" >
						--}}
						{{--                                            
						<div class="row" style="padding-right:3px; padding-left:3px;">
							--}}
							{{--                                                
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    Item Type--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist databack1 col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    @if($Kit->addon->addon_type_name == 'K')--}}
								{{--                                                    Kit--}}
								{{--                                                    @elseif($Kit->addon->addon_type_name == 'P')--}}
								{{--                                                    Accessories--}}
								{{--                                                    @elseif($Kit->addon->addon_type_name == 'SP')--}}
								{{--                                                    Spare Parts--}}
								{{--                                                    @endif--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">--}}
								{{--                                                    Item Code--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    {{ $Kit->addon->addon_code }}--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    Quantity--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    {{$Kit->quantity}}--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    Purchase Price / Unit--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    {{$Kit->unit_price_in_aed}} AED--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    Total Purchase Price--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    {{$Kit->total_price_in_aed}} AED--}}
								{{--                                                
							</div>
							--}}
							{{--                                                @if($Kit->addon->part_number != '')--}}
							{{--                                                
							<div class="labellist labeldesign col-xxl-6 col-lg-6 col-md-5">--}}
								{{--                                                    Part Number--}}
								{{--                                                
							</div>
							--}}
							{{--                                                
							<div class="labellist databack2 col-xxl-6 col-lg-6 col-md-6">--}}
								{{--                                                    {{ $Kit->addon->part_number}}--}}
								{{--                                                
							</div>
							--}}
							{{--                                                @endif--}}
							{{--                                            
						</div>
						--}}
						{{--                                        
					</div>
					--}}
					{{--                                        </br>--}}
					{{--                                    
				</div>
				--}}
				{{--                                
			</div>
			--}}
			{{--                            <!-- </div> -->--}}
			{{--                        <!-- </div> -->--}}
			{{--                    @endforeach--}}
			{{--                
		</div>
		--}}
		{{--            
	</div>
	--}}
	{{--            </br>--}}
	{{--        @endforeach--}}
</div>
<div id="myModal" class="modal modalForImage">
	<span class="closeImage close">&times;</span>
	<img class="modalContentForImage" id="img01">
	<div id="caption"></div>
</div>
@endif
@endcan
<script type="text/javascript">
	// show image in large view
	var data = {!! json_encode($supplierAddonDetails) !!};
	var base_url = window.location.origin;
	var lengthKitItems = 0;
	$(document).ready(function () {
	    console.log(base_url);
	            lengthKitItems = data.kit_items.length;
	            var values = [];
	            values = Array.from(document.querySelectorAll('.purchase_price')).map(input => input.value);
	            if(values != '' || values !=',')
	            {
	                if(values.includes("NOT AVAILABLE"))
	                {
	                    $("#previous_purchase_price").val("NOT AVAILABLE");
	                    $("#current_purchase_price").val("NOT AVAILABLE");
	                    $("#least_purchase_price").val("NOT AVAILABLE");
	                }
	            }
	        });
	    $('.purchase-price-edit-button').click(function (e) {
	        var index = $(this).attr('data-index');
	        var show = $(this).attr('is-show');
	
	        if(show == 1) {
	            $('#purchase-price-div-'+index).attr('hidden',false);
	            $('#price-show-button-'+index).attr('is-show',0);
	        }else{
	            $('#purchase_price_'+index).val('');
	            $('#purchase-price-div-'+index).attr('hidden',true);
	            $('#price-show-button-'+index).attr('is-show',1);
	        }
	    });
	    $('.price-update-button').click(function (e) {
	        var index = $(this).attr('data-index');
	        var kit_id = $(this).attr('data-kit-id');
	        var supplier_addon_id = $('#supplier_'+index).find('option:selected').attr('data-id');
	        var purchase_price = $('#purchase_price_'+index).val();
	        if(purchase_price != '') {
	            $.ajax({
	                url: "{{ url('createNewSupplierAddonPrice')}}",
	                type: "POST",
	                data:
	                    {
	                        id: supplier_addon_id,
	                        name:purchase_price,
	                        kit_id:kit_id,
	                        _token: '{{csrf_token()}}'
	                    },
	                dataType: "json",
	                success: function (data) {
	                    alertify.success("Purchase Price Updated Successfully");
	                    setTimeout(function(){
	                        location.reload();
	                    }, 2000);
	                }
	            })
	        }else{
	            $('#purchase-price-error-'+index).text("Purchase Price required");
	            $('#purchase_price_'+index).addClass('is-invalid');
	            $('#purchase-price-error-'+index).addClass('text-danger');
	            e.preventDefault();
	        }
	    })
	    function removePurchasePriceError(index){
	        $('#purchase-price-error-'+index).text(" ");
	        $('#purchase_price_'+index).removeClass('is-invalid');
	        $('#purchase-price-error-'+index).removeClass('text-danger');
	    }
	    $('.spare-part-edit-button').click(function (e) {
	        var index = $(this).attr('data-index');
	        var kit_id = $(this).attr('data-kit-id');
	        var addonDetailId = $('#item-code-id-'+index).val();
	        // alert(addonDetailId);
	        // alert(kit_id);
	
	        url = '{{ url('addons/details/edit') }}' + "/"+addonDetailId + "?kit_id="+kit_id
	        window.location.href = url;
	    });
	
	    $('.image-click-class').click(function (e)
	    {
	        var id =  $(this).attr('id');
	        var src = $(this).attr('src');
	        var modal = document.getElementById("myModal");
	        var img = document.getElementById(id);
	        var modalImg = document.getElementById("img01");
	        var captionText = document.getElementById("caption");
	        modal.style.display = "block";
	        modalImg.src = src;
	        captionText.innerHTML = this.alt;
	      })
	      $('.closeImage').click(function (e)
	      {
	        var modal = document.getElementById("myModal");
	        modal.style.display = "none";
	      })
	      function onChangePartNo(current, index)
	      {
	        var datasup = $('#part-number-' + index + ' option[value='+current.value+']').attr('data-sup');
	        if(datasup == 'no')
	        {
	            var sp  = $('#part-number-' + index + ' option[value='+current.value+']').attr('class');
	            var spId  = $('#part-number-' + index + ' option[value='+current.value+']').attr('data-id');
	            let span = document.getElementById("item_code_"+index);
	            let spanInputId = document.getElementById("item-code-id-"+index);
	            span.textContent = sp;
	            spanInputId.value = spId;
	            //change image based on selected part numbers spare part image
	        }
	      }
	      function calculatePrice(current, index)
	      {
	        var CurrentPurchasePrice = 0;
	        var kit_quantity = $('#quantity_'+index).val();
	        var item_price = $('#supplier_'+index).val();
	        var id = $('#supplier_'+index).find('option:selected').attr('data-id');
	        if(id == 'not_available')
	        {
	            $('#unit_price_'+index).val('NOT AVAILABLE');
	            $('#total_price_'+index).val('NOT AVAILABLE');
	            $('#item_code_'+index).text(data.kit_items[index-1].latestPartNoSp.addon_code);
	            $('#item-code-id-'+index).val(data.kit_items[index-1].latestPartNoSp.addon_id);
	            $('#part-number-'+index).empty();
	            var partNo = data.kit_items[index-1].SpWithoutVendorPartNos;
	            jQuery.each(partNo, function (key, value) {
	                $('#part-number-'+index).append('<option data-sup="no" value="'+ value.id +'" class="'+value.addondetails.addon_code+'" data-id="'+value.addondetails.id+'">'+value.part_number+'</option>');
	            });
	            document.getElementById('price-show-button-'+index).style.visibility='hidden';
	            var image = '';
	            var image = base_url+'/addon_image/'+data.kit_items[index-1].latestPartNoSp.image;
	            $("#addon-item-image-"+index).attr('src', image);
	        }
	        else
	        {
	            $('#unit_price_'+index).val(item_price);
	            var item_total_price = kit_quantity * item_price;
	            $('#total_price_'+index).val(item_total_price);
	
	            $.ajax({
	                url: "{{url('getPartNumbers')}}",
	                type: "GET",
	                data:
	                    {
	                        id: id,
	                    },
	                dataType: "json",
	                success: function (data) {
	                    $('#item_code_'+index).text(data.item_code);
	                    $('#item-code-id-'+index).val(data.item_id);
	                    var image = data.item_image;
	                    $("#addon-item-image-"+index).attr('src', image);
	                    $('#part-number-'+index).empty();
	                    var data = data.part_number;
	                    jQuery.each(data, function (key, value) {
	                        {{--var selectedId = '{{ $letterOfIndent->customer_id }}';--}}
	                        $('#part-number-'+index).append('<option data-sup="yes" value="' + value.id + ' " >' + value.part_number + '</option>');
	                    });
	                }
	            });
	            document.getElementById('price-show-button-'+index).style.visibility='visible';
	            for(var i=1; i<=lengthKitItems; i++)
	            {
	                var quantity = 0;
	                var price = 0;
	                var totalItemPrice = 0;
	                quantity = $("#quantity_"+i).val();
	                price = $('#supplier_'+i).val();
	
	                totalItemPrice = quantity * price;
	                CurrentPurchasePrice = CurrentPurchasePrice + totalItemPrice;
	            }
	            document.getElementById("current_purchase_price").value = CurrentPurchasePrice;
	        }
	      }
	      $('form').on('submit', function (e)
	        {
	            var previousPurchaseprice = '';
	            var currentPurchasePrice = '';
	            var previousSellingPrice = '';
	            var currentSellingPrice = '';
	            previousPurchaseprice = $("#previous_purchase_price").val();
	            currentPurchasePrice = $("#current_purchase_price").val();
	            previousSellingPrice = $("#previous_selling_price").val();
	            currentSellingPrice = $("#current_selling_price").val();
				if(currentPurchasePrice != '' || currentPurchasePrice != 'NOT AVAILABLE') {
					if(currentSellingPrice != '') {
						if(Number(currentPurchasePrice) > Number(currentSellingPrice)) {
							alert('New selling price must be greater than current purchase price');
							e.preventDefault();
						}
					}
				}
	            var values = [];
	            if(previousPurchaseprice != "NOT AVAILABLE" && currentPurchasePrice != "NOT AVAILABLE" && previousPurchaseprice == currentPurchasePrice )
	            {
	                e.preventDefault();
	            }
	            if(currentSellingPrice == '' || currentSellingPrice == previousSellingPrice)
	            {
	                e.preventDefault();
	            }
	        });
</script>
@endsection
