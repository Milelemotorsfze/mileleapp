@extends('layouts.table')
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<style>
    .widthinput
    {
        height:32px!important;
    }
    .select2-container .select2-selection--single {
        height: unset !important;
    }
  div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
    .full-width {
       width: 100%;
    }
.overlay
{
    position: fixed; /* Positioning and size */
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(128,128,128,0.5); /* color */
    display: none; /* making it hidden by default */
}
.paragraph-class
{
    color: red;
    font-size:11px;
}
.days-dropdownf {
    background: none;
    text-align: center;
    width: 50px;
    border: none;
    outline: none;
  }
/*.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {*/
/*  padding: 4px 8px 4px 8px;*/
/*  text-align: center;*/
/*  vertical-align: middle;*/
/*}*/
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
/*.row{*/
/*    margin-top: 5px;*/
/*}*/
    </style>
<div class="card-header">
	<h4 class="card-title">
		Proforma Invoice
		<a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
	</h4>
	<br>
</div>
<div class="card-body">
<div class="modal fade" id="addAgentModal" tabindex="-1" role="dialog" aria-labelledby="addAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-update2_492" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fs-5" id="adoncode">Add New Agent</h5>
          <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
    <div class="row">
        <div class="col-md-6 form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control mb-3" placeholder="Name">
        </div>

        <div class="col-md-6 form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control mb-3" placeholder="Email">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label for="phone">Phone:</label>
            <input type="number" name="phone" id="phone" class="form-control mb-3" placeholder="Phone">
        </div>

        <div class="col-md-6 form-group">
            <label for="id_category">ID Category:</label>
            <select name="id_category" id="id_category" class="form-control mb-3">
                <option value="national_id">National ID</option>
                <option value="emirates_id">Emirates ID</option>
                <option value="passport_number">Passport Number</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 form-group">
            <label for="number">ID Number:</label>
            <input type="text" name="id_number" id="number" class="form-control mb-3" placeholder="Id NUmber">
        </div>

        <div class="col-md-6 form-group">
            <label for="document">Upload Document:</label>
            <input type="file" name="identification_file" id="document" class="form-control-file mb-3">
        </div>
    </div>
</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="submit_b_492" class="btn btn-primary btn-sm">Submit</button>
        </div>
      </div>
    </form>
  </div>
  </div>
    <form action="{{ route('quotation-items.store') }}" id="form-create" method="POST" >
        @csrf
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-4">
                        Document Type :
                    </div>
                    <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input document_type" type="checkbox" name="document_type" id="inlineCheckbox1" value="Quotation" checked>
                            <label class="form-check-label" for="inlineCheckbox1">Quotation</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input document_type" type="checkbox" name="document_type" id="inlineCheckbox2" value="Proforma">
                            <label class="form-check-label" for="inlineCheckbox2">Proforma Invoice</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-2">
                        Category :
                    </div>
                    <div class="col-sm-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input shipping_method @error('shipping_method') is-invalid @enderror" type="checkbox"
                                   name="shipping_method" id="CNF" value="CNF" >
                            <label class="form-check-label" for="CNF">Local</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input shipping_method @error('shipping_method') is-invalid @enderror" type="checkbox"
                                   name="shipping_method" id="EXW" value="EXW" checked>
                            <label class="form-check-label" for="EXW">Export</label>
                        </div>
                    </div>
                    @error('shipping_method')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-2">
                        Currency :
                    </div>
                    <div class="col-sm-4">
                        <select class="form-select" name="currency" id="currency">
                            <option>AED</option>
                            <option>USD</option>
                            <option>EUR</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
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
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <label for="timeRange">Document Validity:</label>
                    </div>
                    <div class="col-sm-6">
                        <select id="timeRange" name="document_validity" class="form-select">
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
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Person :
                    </div>
                    <div class="col-sm-6">
                        {{ Auth::user()->name }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Office :
                    </div>
                    <div class="col-sm-6">
                    {{ isset($empProfile->office) ? $empProfile->office : '' }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Email ID :
                    </div>
                    <div class="col-sm-6">
                        {{ Auth::user()->email }}
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Contact No :
                    </div>
                    <div class="col-sm-6">
                    {{ isset($empProfile->phone) ? $empProfile->phone : '' }}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
{{--                <div class="row">--}}
{{--                    <div class="col-sm-6">--}}
{{--                        Customer ID :--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-6">--}}
{{--                        {{ $empProfile->id }}--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Company :
                    </div>
                    <div class="col-sm-6">
                        <input type="text"  class="form-control form-control-xs" value="{{ $callDetails->company_name }}" name="company_name" id="company" placeholder="Company Name">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <label for="timeRange">Person :</label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="name"  placeholder="Person Name"  class="form-control form-control-xs" id="person" value="{{$callDetails->name}}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Contact No :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="phone"  class="form-control form-control-xs" minlength="5" maxlength="15" id="contact_number" value="{{$callDetails->phone}}" placeholder="Phone">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Email :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="email" id="email"  class="form-control form-control-xs"  value="{{$callDetails->email}}" placeholder="Email">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Address :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="address"  class="form-control form-control-xs" placeholder="Address" value="{{ $callDetails->address }}"  id="address">
                    </div>
                </div>
            </div>
            <div class="col-sm-4" >
                <div id="export-shipment">
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Final Destination :
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control form-control-xs" placeholder="Destination" name="final_destination" id="final_destination">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Incoterm :
                        </div>
                        <div class="col-sm-6">
                        <select name="incoterm" id="incoterm" class="form-control form-control-xs">
                            <option value="EXW">EXW</option>
                            <option value="CNF">CNF</option>
                            <option value="CIF">CIF</option>
                            <option value="FOB">FOB</option>
                            <option value="Local Registration">Local Registration</option>
                        </select>
                    </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Place of Delivery :
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="place_of_delivery" id="place_of_delivery" class="form-control form-control-xs" placeholder="Place of Delivery">
                        </div>
                    </div>
                </div>
                <div class="row mt-2" hidden id="local-shipment">
                    <div class="col-sm-6">
                        Place of Supply :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="place_of_supply" class="form-control form-control-xs" placeholder="Place Of Supply">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-2">
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
                <div class="row mt-2">
                    <div class="col-sm-6">
                        System Code :
                    </div>
                    <div class="col-sm-6">
                        <input type="number" name="system_code" id="system_code" class="form-control form-control-xs" placeholder="System Code">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Payment Terms :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="payment_terms" id="payment_terms" class="form-control form-control-xs" placeholder="Payment Terms">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Rep Name :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="representative_name" id="representative_name" class="form-control form-control-xs" placeholder="Rep. Name">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Rep No :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="representative_number" id="representative_number" class="form-control form-control-xs" placeholder="Rep. Number">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        CB Name:
                    </div>
                    <div class="col-sm-6">
                    <div class="input-group">
                        <select name="cb_name" id="cb_name" class="form-control form-control-xs">
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="addAgentModal()">
                                +
                            </button>
                        </div>
                    </div>
                </div>
                </div>
                <input type="hidden" name="agents_id" id="agents_id" value="">
            <input type="hidden" name="selected_cb_name" id="selected_cb_name" value="">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        CB No:
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="cb_number" id="cb_number" class="form-control form-control-xs" placeholder="CB Number" readonly>
                    </div>
                </div>
            </div>
            <div class="col-sm-4"  id="advance-amount-div" hidden>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Advance Amount :
                    </div>
                    <div class="col-sm-6">
                        <input type="number" min="0" class="form-control form-control-xs advance-amount"
                               name="advance_amount" id="advance-amount" placeholder="Advance Amount" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-12">
                <div class="row mt-2">
                    <div class="col-sm-2">
                        Remarks :
                    </div>
                    <div class="col-sm-10">
                        <input type="text"  class="form-control form-control-xs "
                               name="remarks" placeholder="Remarks" >
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header">
                <h4>Quotation Items</h4>
            </div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table id="dtBasicExample2" class="table table-responsive table-striped table-editable table-edits table">
                                <thead class="bg-soft-secondary">
                                    <tr>
                                        <th>Description</th>
                                        <th style="margin-left: 10px;">Code</th>
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
            <div class="row total-div" hidden>
                <div class="col-lg-9 text-end">
                    <label class="fw-bold font-size-16">Total (AED) :</label>
                </div>
                <div class="col-lg-2">
                    <input type="hidden" value="{{ $callDetails->id }}" name="calls_id" >
                    <input type="number" readonly id="total" name="total"  placeholder="Total Amount" class="fw-bold form-control" value="">
                </div>
            </div>
            <div class="row mt-2" id="selected-currency-div" hidden >
                <div class="col-lg-9 text-end">
                    <label class="fw-bold font-size-16">Total (<span id="selected-currency"> </span>) :</label>
                </div>
                <div class="col-lg-2">
                    <input type="number" readonly id="total_in_selected_currency" name="deal_value" placeholder="Total Amount" class="fw-bold form-control" value="">
                </div>
            </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-12">
                <input type="radio" id="showVehicles" name="contentType">
                <label for="showVehicles">Add Vehicles</label>
            </div>
            <div class="col-lg-1 col-md-3 col-sm-12">
            <input type="radio" id="showAccessories" name="contentType">
                <label for="showAccessories">Add Accessories</label>
            </div>
            <div class="col-lg-1 col-md-3 col-sm-12">
            <input type="radio" id="showSpareParts" name="contentType">
                <label for="showSpareParts">Add Spare Parts</label>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-12">
            <input type="radio" id="showKits" name="contentType">
                <label for="showKits">Add Kits</label>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-12">
            <input type="radio" id="showShipping" name="contentType">
                <label for="showShipping">Add Shipping</label>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showShippingDocuments" name="contentType">
                <label for="showShippingDocuments">Add Shipping Documents</label>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-12">
            <input type="radio" id="showCertificates" name="contentType">
                <label for="showCertificates"> Certificate</label>
            </div>
            <div class="col-lg-1 col-md-2 col-sm-12">
            <input type="radio" id="showOthers" name="contentType">
                <label for="showOthers">Add Other</label>
            </div>
        </div>
        <div id="vehiclesContent" class="contentveh">
            <div class="card">
            <div class="card-header">
                <h4 >Search Available Vehicles</h4>
                <div class="row justify-content-end">
                    <div class="col-lg-4 col-sm-12 col-md-4" >
                        <a class="btn btn-outline-warning float-end mb-4" data-table="vehicle-table" id="directadding-button"> Directly Adding Into Quotation</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1 col-md-6 col-sm-12">
                        <label class="form-label"> Brand</label>
                        <select class="form-control col" id="brand" name="brand" style="width: 100%">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6" style="margin-top: 26px;">
                        <a id="addnewBrandButton" data-toggle="popover" data-trigger="hover" title="Create New Brand" data-placement="top" style="float: right;"
                           class="btn btn-info modal-button" data-modal-id="createNewBrand"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <label class="form-label"> Model Line</label>
                        <select class="form-control col" id="model_line" style="width: 100%" name="model_line" disabled >
                            <option value="">Select Model Line</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6 add-new-model-line-div" style="margin-top: 26px;" hidden>
                        <a id="createNewModelLineButton" data-toggle="popover" data-trigger="hover" title="Create New Model Line" data-placement="top" style="float: right;"
                           class="btn btn-info modal-model-line-button" data-modal-id="createNewModelLine"><i class="fa fa-plus" aria-hidden="true"></i> Add Model Line</a>

                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"> Variant</label>
                        <select class="form-control col" id="variant" style="width: 100%" name="variant" disabled>
                            <option value="">Select Variant</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Interior Color</label>
                        <select class="form-control col" id="interior_color" style="width: 100%" name="interior_color" disabled>
                            <option value="">Select Interior Color</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Exterior Color</label>
                        <select class="form-control col" id="exterior_color" style="width: 100%" name="exterior_color" disabled>
                            <option value="">Select Exterior Color</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-6" style="margin-top: 26px;">
                        <button type="button" class="btn btn-primary" id="search-button">Search</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
                                    <thead class="bg-soft-secondary">
                                    <tr>
                                        {{--<th>ID</th>--}}
                                        {{--<th>Status</th>--}}
                                        {{--<th>VIN</th>--}}
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
        </div>
        </div>
        <div id="accessoriesContent" class="contentveh">
            <div class="card">
                <div class="card-header">
                    <h4>Search Available Accessories</h4>
                    <div class="row">
                            <div class="col-lg-3 col-md-6" style="margin-right: 10px;">
                                <label for="brand"> Accessory Name</label>
                                <select class="form-select col" id="accessories_addon" name="accessories_addon" style="width: 100%">
                                    <option value="">Select Accessory Name</option>
                                    @foreach($assessoriesDesc as $accessory)
                                        <option value="{{ $accessory->id }}">{{ $accessory->Addon->name ?? '' }}@if($accessory->description!='') - {{$accessory->description}}@endif</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6" style="margin-right: 10px;">
                                <label for="brand"> Brand</label>
                                <select class="form-select col" id="accessories_brand" name="accessories_brand" style="width: 100%">
                                    <option value="">Select Brand</option>
                                    <!-- <option value="allbrands">ALL BRANDS</option> -->
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6" style="margin-right: 10px;">
                                <label for="model_line"> Model Line</label>
                                <select class="form-select col" id="accessories_model_line" style="width: 100%" name="accessories_model_line" disabled>
                                    <option value="">Select Model Line</option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6" style="margin-top: 26px;" >
                                <button type="button" class="btn btn-primary" id="accessories-search-button">Search</button>
                            </div>
                            <div class="col-lg-3 col-md-6" style="margin-top: 26px;">
                                <button type="button" class="btn btn-outline-warning" data-table="accessories-table" id="directadding-button">Directly Adding Into Quotation</button>
                            </div>
                        </div>
                </div>
                <div class="card-body">
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
            </div>
        </div>
        <div id="sparePartsContent" class="contentveh">
           <div class="card">
               <div class="card-header">
                   <h4>Search Available Spare Parts</h4>
                   <div class="row">
                       <div class="col-lg-2 col-md-6">
                           <label for="brand"> Spare Part Name</label>
                           <select class="form-control full-width" id="spare_parts_addon" name="spare_parts_addon" style="width: 100%">
                               <option value="">Select Spare Part Name</option>
                               @foreach($sparePartsDesc as $spareParts)
                                   <option value="{{ $spareParts->id }}">{{ $spareParts->Addon->name ?? '' }}@if($spareParts->description!='') - {{$spareParts->description}}@endif</option>
                               @endforeach
                               <option value="Other">Other</option>
                           </select>
                       </div>
                       <div class="col-lg-2 col-md-6">
                           <label for="brand"> Brand</label>
                           <select class="form-select" id="spare_parts_brand" name="spare_parts_brand" style="width: 100%">
                               <option value="">Select Brand</option>
                               @foreach($brands as $brand)
                                   <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                               @endforeach
                           </select>
                       </div>
                       <div class="col-lg-2 col-md-6">
                           <label for="model_line"> Model Line</label>
                           <select class="form-select" id="spare_parts_model_line" name="spare_parts_model_line" disabled style="width: 100%">
                               <option value="">Select Model Line</option>
                           </select>
                       </div>
                       <div class="col-lg-2 col-md-6">
                           <label for="model_description"> Model Description</label>
                           <select class="form-select" id="spare_parts_model_description" name="spare_parts_model_description" disabled style="width: 100%">
                               <option value="">Select Model Description</option>
                           </select>
                       </div>
                       <div class="col-lg-1 col-md-6" style="margin-top: 26px;">
                           <div class="col">
                               <button type="button" class="btn btn-primary" id="spare_parts-search-button">Search</button>
                           </div>
                       </div>
                       <div class="col-lg-3 col-md-6" style="margin-top: 26px;">
                           <button type="button" class="btn btn-outline-warning" data-table="spare-part-table" id="directadding-button">Directly Adding Into Quotation</button>
                        </div>
                   </div>
               </div>
               <div class="card-body">
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
           </div>
        </div>
        <div id="kitsContent" class="contentveh">
            <div class="card">
                <div class="card-header">
                    <h4>Search Available Kits</h4>
                    <div class="row">
                            <div class="col-lg-2 col-md-6">
                                <label for="brand"> Kit Name</label>
                                <select class="form-control col" id="kit_addon" name="kit_addon" style="width: 100%">
                                    <option value="">Select Kit Name</option>
                                    @foreach($kitsDesc as $kit)
                                        <option value="{{ $kit->id }}">{{ $kit->Addon->name ?? '' }}@if($kit->description!='') - {{$kit->description}}@endif</option>
                                    @endforeach
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label for="brand"> Brand</label>
                                <select class="form-control col" id="kit_brand" name="kit_brand" style="width: 100%">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label for="model_line"> Model Line</label>
                                <select class="form-control col" id="kit_model_line" name="kit_model_line" disabled style="width: 100%">
                                    <option value="">Select Model Line</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label for="model_description"> Model Description</label>
                                <select class="form-control col" id="kits_model_description" name="kits_model_description" disabled style="width: 100%">
                                    <option value="">Select Model Description</option>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6" style="margin-top: 26px">
                                <button type="button" class="btn btn-primary" id="kit-search-button">Search</button>
                            </div>
                            <div class="col-lg-3 col-md-6" style="margin-top: 26px">
                                <button type="button" class="btn btn-outline-warning" data-table="kit-table" id="directadding-button">Directly Adding Into Quotation</button>
                            </div>
                        </div>
                </div>
                <div class="card-body">
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
                                                <button class="add-button circle-button" data-button-type="Shipping" data-shipping-id="{{ $shipping->id }}"></button>
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
                                                    <button class="add-button circle-button" data-button-type="Shipping-Document"
                                                            data-shipping-document-id="{{ $shippingDocument->id }}" ></button>
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
                                                    <button class="add-button circle-button" data-button-type="Certification"
                                                            data-certification-id="{{ $certification->id }}"></button>
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
                                                    <button class="add-button circle-button" data-button-type="Other"
                                                    data-other-id="{{ $otherDocument->id }}"></button>
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
        <br>
        <input type="hidden" id="old-currency-type" value="">
        <input type="hidden" id="current-currency-type" value="AED">
        <button type="submit" class="btn btn-primary" id="submit-button" disabled>Submit</button>
    </form>
    <div class="overlay">
{{--        <div class="modal" id="createNewBrand" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">--}}
{{--            <div class="modal-dialog modal-dialog-centered" role="document">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="exampleModalCenteredLabel" style="text-align:center;"> Create New Brand </h5>--}}
{{--                        <button type="button" class="btn btn-secondary btn-sm close form-control modal-close" data-dismiss="modal" aria-label="Close">--}}
{{--                            <span aria-hidden="true">X</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <form method="POST" enctype="multipart/form-data">--}}
{{--                            @csrf--}}
{{--                            <div class="row modal-row">--}}
{{--                                <div class="col-xxl-12 col-lg-12 col-md-12">--}}
{{--                                    <span class="error">* </span>--}}
{{--                                    <label for="name" class="col-form-label text-md-end ">Brand Name</label>--}}
{{--                                </div>--}}
{{--                                <div class="col-xxl-12 col-lg-12 col-md-12">--}}
{{--								<input type="text" id="new_brand_name" class="form-control @error('brand_name') is-invalid @enderror" name="brand_name"--}}
{{--                                          placeholder="Enter Brand Name" value="{{ old('brand_name') }}" oninput="checkValidation()" autofocus>--}}
{{--                                    <span id="newBrandError" class="is-invalid"></span>--}}
{{--                                    @error('brand_name')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--								<strong>{{ $message }}</strong>--}}
{{--								</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary btn-sm modal-close" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>--}}
{{--                        <button type="button" class="btn btn-primary btn-sm" id="createBrandId" style="float: right;">--}}
{{--                            <i class="fa fa-check" aria-hidden="true"></i> Submit</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="modal" id="createNewModelLine" tabindex="-1" role="dialog" aria-labelledby="exampleModalLineCenteredLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLineCenteredLabel" style="text-align:center;"> Create New Model Line </h5>
                        <button type="button" class="btn btn-secondary btn-sm close form-control modal-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row modal-row">
                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <span class="error">* </span>
                                    <label for="name" class="col-form-label text-md-end" >Brand</label>
                                </div>
                                <div class="col-xxl-9 col-lg-9 col-md-9 col-sm-12">
                                    <input type="text" class="form-control new_brand  @error('brand_name') is-invalid @enderror" oninput="checkBrandValidation()" placeholder="Enter Brand Name" id="new-brand"  name="brand_name" hidden>
                                    <div id="brand-list-div">
                                        <select onchange="checkBrandValidation()" class="form-control new_brand @error('brand_name') is-invalid @enderror"
                                                name="brand_name" id="brand-from-list" style="width: 100%">
                                            <option></option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span id="newBrandError" class="is-invalid" style="margin-top: 20px" ></span>
                                </div>

                                <div class="col-xxl-3 col-lg-3 col-md-3 col-sm-12">
                                    <a> <button type="button" class="btn btn-info add-new-button" >Add New</button> </a>
                                    <a> <button type="button" class="btn btn-info add-existing-brand-button" hidden>Add From List</button> </a>
                                </div>
                                <div class="col-xxl-12 col-lg-12 col-md-12 mt-2">
                                    <span class="error">* </span>
                                    <label for="name" class="col-form-label text-md-end">Model Line</label>
                                </div>
                                <div class="col-xxl-12 col-lg-12 col-md-12">
                                    <input type="text" id="new_model_line_name" class="form-control  @error('model_line') is-invalid @enderror" name="model_line"
                                           placeholder="Enter Model Line Name" value="{{ old('model_line') }}" oninput="checkModelLine()" autofocus>
                                    <span id="newModelLineError" class="is-invalid"></span>
                                    @error('model_line')
                                    <span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
								</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm modal-close" data-dismiss="modal" ><i class="fa fa-times"></i> Close</button>
                        <button type="button" class="btn btn-primary btn-sm" id="createModelLineId" style="float: right;">
                            <i class="fa fa-check" aria-hidden="true"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
     function addAgentModal() {
  $('#addAgentModal').modal('show');
}
$(document).ready(function () {
    $('#cb_name').change(function () {
        var selectedAgentId = $(this).val();
        var selectedAgentName = $(this).find(':selected').text();

        $('#agents_id').val(selectedAgentId);
        $('#selected_cb_name').val(selectedAgentName);
    });
    // Fetch agent names dynamically on page load
    fetchAgentData();

    // Function to fetch agent names via AJAX
    function fetchAgentData() {
        $.ajax({
            url: "{{ route('agents.getAgentNames') }}",
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                // Clear existing options
                $('#cb_name').empty();
                $('#cb_name').append('<option value="" disabled selected>Select Agent</option>');
                // Add fetched options
                $.each(data, function (index, agent) {
                    $('#cb_name').append('<option value="' + agent.id + '">' + agent.name + '</option>');
                });

                // Update CB No on change
                $('#cb_name').change(function () {
                    var selectedAgentId = $(this).val();
                    var selectedAgent = data.find(agent => agent.id == selectedAgentId);

                    if (selectedAgent) {
                        $('#cb_number').val(selectedAgent.phone).trigger('change');
                    } else {
                        $('#cb_number').val('').trigger('change');
                    }
                });
            },
            error: function (error) {
                console.error('Error fetching agent data:', error);
            }
        });
    }

    // Intercept form submission and handle it through AJAX
    $('#form-update2_492').submit(function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: "{{ route('agents.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#cb_name').append('<option value="' + response.agent_id + '">' + response.name + '</option>');
                $('#cb_name').val(response.agent_id).trigger('change');
                $('#cb_number').val(response.phone).trigger('change');
                $('#form-update2_492')[0].reset();
                $('#addAgentModal').modal('hide');
            },
            error: function (error) {
                console.error('Error submitting form:', error);
            }
        });
    });
});
</script>
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

    function checkBrandValidation()
    {
        var value = $("#brand-from-list").val();
        var newBrand = $('#new-brand').val();
        // alert(value);
        if(value == '' || newBrand == '')
        {
            $msg = 'Brand Name is Required';
            showNewBrandError($msg);
        }
        else
        {
            // alert("ok");
            $msg=""
            removeNewBrandError($msg);
        }
    }
    function checkModelLine() {
        var value = $('#new_model_line_name').val();
        if(value == '')
        {
            $msg = 'Model Line is Required';
            showNewModelLineError($msg);
        }
        else
        {
            removeNewModelLineError();
        }
    }
    function showNewModelLineError($msg) {
        document.getElementById("newModelLineError").textContent=$msg;
        document.getElementById("new_model_line_name").classList.add("is-invalid");
        document.getElementById("newModelLineError").classList.add("paragraph-class");
    }
    function removeNewModelLineError()
    {
        document.getElementById("newModelLineError").textContent="";
        document.getElementById("new_model_line_name").classList.remove("is-invalid");
        document.getElementById("newModelLineError").classList.remove("paragraph-class");
    }
    function showNewBrandError($msg)
    {
        document.getElementById("newBrandError").textContent=$msg;
        document.getElementById("brand-from-list").classList.add("is-invalid");
        document.getElementById("new-brand").classList.remove("is-invalid");
        document.getElementById("newBrandError").classList.add("paragraph-class");
    }
    function removeNewBrandError($msg)
    {
        document.getElementById("newBrandError").textContent=$msg;
        document.getElementById("brand-from-list").classList.remove("is-invalid");
        document.getElementById("new-brand").classList.remove("is-invalid");
        document.getElementById("newBrandError").classList.remove("paragraph-class");
    }

    $(document).ready(function() {
        $('.add-new-button').on('click', function(){

            $('#brand-list-div').attr('hidden', true);
            $('#new-brand').attr('hidden', false);
            $('.add-existing-brand-button').attr('hidden', false);
            $('.add-new-button').hide();
            $("#brand-from-list option:selected").prop("selected", false);
            $("#brand-from-list").trigger('change.select2');

        });
        $('.add-existing-brand-button').on('click', function(){
            $('#new-brand').attr('hidden', true);
            $('#new-brand').val("");
            $('#brand-list-div').attr('hidden', false);
            $('.add-new-button').show();
            $('.add-existing-brand-button').attr('hidden', true);

        });

        $('#dtBasicExample2 tbody').on('click', '.checkbox-hide', function(e) {
            var id = this.id;
            if($(this).is(':unchecked')) {
                $('#'+ id).val(null);
            }else{
                $('#'+ id).val("yes");
            }
        });
        $('.modal-close').on('click', function(){
            $('.overlay').hide();
            $('.modal').removeClass('modalshow');
            $('.modal').addClass('modalhide');
            $('#new_brand_name').val("");
            $('#new_model_line_name').val("");
            removeNewBrandError();
            removeNewModelLineError();
        });
        $('#createBrandId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var value = $('#new_brand_name').val();
            if(value == '')
            {
                $msg = 'Brand Name is Required';
                showNewBrandError($msg);
            }
            else
            {
                $.ajax
                ({
                    url:"{{route('brands.store')}}",
                    type: "POST",
                    data:
                        {
                            brand_name: value,
                            request_from: 'Quotation',
                            _token: '{{csrf_token()}}'
                        },
                    dataType : 'json',
                    success: function(result)
                    {
                        console.log(result);
                        if(result.error) {
                            $msg = result.error;
                            showNewBrandError($msg);
                        }else{
                            $('.overlay').hide();
                            $('.modal').removeClass('modalshow');
                            $('.modal').addClass('modalhide');
                            $('#brand').append("<option value='" + result.id + "'>" + result.brand_name + "</option>");
                            $('#brand').val(result.id);
                            $('#new_brand_name').val("");
                            $('.add-new-model-line-div').prop('hidden', false);
                            $('#model_line').attr('disabled', false)
                            $msg = "";
                            removeNewBrandError();
                        }
                    }
                });
            }
        });
        $('#createModelLineId').on('click', function()
        {
            // create new addon and list new addon in addon list
            var model_line = $('#new_model_line_name').val();
            var brand = $("input[name=brand_name]").val();
            var existingBrand = $("#brand-from-list").val();
            var newBrand = $('#new-brand').val();
            checkBrandValidation();
            checkModelLine();
            if(existingBrand != "") {
                var barnd = existingBrand;
            }else{
                var brand = newBrand;
            }

             if(model_line != "" && brand != "") {
                 alert("ok");
                    $.ajax
                    ({
                        url:"{{route('model-lines.store')}}",
                        type: "POST",
                        data:
                            {
                                model_line: model_line,
                                brand_id: brand,
                                request_from: 'Quotation',
                                _token: '{{csrf_token()}}'
                            },
                        dataType : 'json',
                        success: function(result)
                        {
                            if(result.error) {
                                $msg = result.error;
                                showNewModelLineError($msg);
                            }else{
                                $('.overlay').hide();
                                $('.modal').removeClass('modalshow');
                                $('.modal').addClass('modalhide');
                                $('#model_line').append("<option value='" + result.id + "'>" + result.model_line + "</option>");
                                $('#model_line').val(result.id);
                                $('#model_line').prop('disabled', false);

                                $('#new_model_line_name').val("");
                                $msg = "";
                                removeNewModelLineError();
                            }
                        }
                    });

                }

        });

        $('.modal-button').on('click', function()
        {
            var modalId = $(this).data('modal-id');
            showOrHideModal(modalId);
        });
        $('.modal-model-line-button').on('click', function()
        {
            var modalId = $(this).data('modal-id');
            showOrHideModal(modalId);
        });
        function showOrHideModal(modalId) {
            $('.overlay').show();
            $('#' + modalId).addClass('modalshow');
            $('#' + modalId).removeClass('modalhide');

        }
        var shippingTable = $('#shipping-table').DataTable();
        var shippingDocumentTable = $('#shipping-document-table').DataTable();
        var certificationTable = $('#certification-table').DataTable();
        var otherTable = $('#other-document-table').DataTable();

        $('#brand').select2();

        $('#brand-from-list').select2({
            placeholder: "Select Brand"
        });
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

        $('input[name="document_type"]').on('change', function() {
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            var documentType = $(this).val();
            if(documentType == 'Proforma') {
                $('#advance-amount-div').attr('hidden', false);
            }else{
                $('#advance-amount').val();
                $('#advance-amount-div').attr('hidden', true);
            }
        });

        $('input[name="shipping_method"]').on('change', function() {
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            var shippingMethod = $(this).val();
            if(shippingMethod == 'CNF') {
                $('#export-shipment').attr('hidden', true);
                $('#local-shipment').attr('hidden', false);

            }else{
                $('#export-shipment').attr('hidden', false);
                $('#local-shipment').attr('hidden', true);

            }
            showPriceInSelectedValue();
            calculateTotalSum();
        });

        function showPriceInSelectedValue() {
            var count = secondTable.data().length;
            // alert(count);
            var currency = $('#currency').val();
            if(count > 0) {
                if (currency != 'AED') {
                    var shippingMethod = $('.shipping_method:checked').val();
                    if (shippingMethod == 'EXW') {
                        $('.total-div').attr("hidden", false)
                    } else {
                        $('.total-div').attr("hidden", true)
                    }
                    $('#selected-currency-div').attr("hidden", false);
                    $('#selected-currency').html(currency);

                } else {
                    $('.total-div').attr("hidden", false);
                    $('#selected-currency-div').attr("hidden", true);
                    $('#selected-currency').html("");
                    $('#total_in_selected_currency').val(" ");
                }
            }
        }
        $('#currency').on('change', function() {
            var currency = $(this).val();
            showPriceInSelectedValue();
            var oldCurrecy = $('#old-currency-type').val();
            var PreviousCurrencyType = $('#current-currency-type').val();
            if(oldCurrecy == PreviousCurrencyType == 'AED') {
                $('#old-currency-type').val(currency);
            }else{
                $('#old-currency-type').val(PreviousCurrencyType);
            }

            $('#current-currency-type').val(currency);
            var oldCurrecyType = $('#old-currency-type').val();
            if(oldCurrecyType == 'AED') {
                if(currency == 'USD') {
                    var value = '{{ $aed_to_usd_rate->value }}';
                    var operand = 'Divide';

                }else if(currency == 'EUR') {
                    var value = '{{ $aed_to_eru_rate->value }}';
                    var operand = 'Divide';
                }
            }else if(oldCurrecyType == 'USD') {
                if(currency == 'AED') {
                    var value = '{{ $aed_to_usd_rate->value }}';
                    var operand = 'Multiply';

                }else if(currency == 'EUR') {
                    var value = '{{ $usd_to_eru_rate->value }}';
                    var operand = 'Divide';

                }
            }
            else if(oldCurrecyType == 'EUR') {
                if(currency == 'AED') {
                    var value = '{{ $aed_to_eru_rate->value }}';
                    var operand = 'Multiply';

                }else if(currency == 'USD') {
                    var value = '{{ $usd_to_eru_rate->value }}';
                    var operand = 'Multiply';
                }
            }
            ConvertRequestedPrice(value,operand);

            calculateTotalSum();
        });
        function ConvertRequestedPrice(value,operand) {
            var count = secondTable.data().length;
            value = parseFloat(value);
            for (var i = 1; i <= count; i++) {
                var price = $('#price-' + i).val();
                var quantity = $('#quantity-' + i).val();
                if(operand == 'Divide') {
                    var convertedPrice = parseFloat(price) / value;
                }else if(operand == 'Multiply') {
                    var convertedPrice = parseFloat(price) * value;
                }
                $('#price-' + i).val(convertedPrice.toFixed(3));
                var amount = parseFloat(convertedPrice) * parseFloat(quantity);
                $('#total-amount-' + i).val(amount.toFixed(3));
            }
        }
        $('#brand').on('change', function() {
        var brandId = $(this).val();
        if (brandId ) {
            $('#model_line').prop('disabled', false);
            $('.add-new-model-line-div').prop('hidden', false);
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
            $('.add-new-model-line-div').prop('hidden', true);
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
                success: function(response) {
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
                success: function(response) {
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
                    var directAdd = "";
                    var directAdd = 'Direct-Add';

                    return '<button class="circle-buttonr remove-button"  data-button-type="'+ directAdd +'">Remove</button>';

            }
            // defaultContent: '<button class="circle-buttonr remove-button" >Remove</button>'
            },
            {
                targets: -2,
                data: null,
                render: function (data, type, row) {
                    var price = "";
                    if(row['button_type'] == 'Vehicle') {
                        var price = row[7];

                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {
                        var price = row[4];
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        var price = row[4];
                    }
                    // calculate
                    var amount = price * 1;
                    var addon = 0;
                        if(row['table_type'] == 'addon-table') {
                            var addon = 1;

                        }
                    var modelLine = row['model_line_id'];
                    return '<input type="hidden" name="is_addon[]" value="'+ addon +'" ><input type="hidden" value="'+ row['model_type'] +'" name="types[]" >' +
                        '<input type="hidden" name="model_lines[]" value="'+ modelLine +'" > <input type="hidden" name="reference_ids[]" value="'+ row['id'] +'"  >' +
                        '<input type="text"  value="'+ amount +'" class="total-amount-editable form-control" name="total_amounts[]" id="total-amount-'+ row['index'] +'" readonly />';
                }
            },
            {
                targets: -3,
                data: null,
                render: function (data, type, row) {
                    return '<input type="number" min="0"  value="1" step="1" class="qty-editable form-control" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)"  required name="quantities[]"  id="quantity-'+ row['index'] +'" />';
                }
            },
            {
                targets: -6,
                data: null,
                render: function (data, type, row) {
                    var combinedValue = "";
                    if(row['button_type'] == 'Vehicle') {
                        var brand = row[0];
                        var modelDescription = row[2];
                        var interiorColor = row[5];
                        var exteriorColor = row[6];
                        var combinedValue = brand + ', ' + modelDescription + ', ' + interiorColor + ', ' + exteriorColor;
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {
                        combinedValue = row[2]+', '+row[3];
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        combinedValue = row[2] + ' , ' + row[3];

                    }else if(row['button_type'] == 'Direct-Add') {
                        var comma0 = comma1 = comma2 = comma3 = comma4 = comma5 = ", ";
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
                        if(row[6] == "") {
                            var comma5 = " ";
                        }
                        combinedValue =  row[1] + comma1 + row[2] + comma2 + row[3]+ comma3 + row[4] + comma4 + row[5]+ comma5 + row[6];
                        if(row['table_type'] !== 'vehicle-table') {
                            combinedValue = row[0] + comma0 + combinedValue;
                        }
                    }
                    var arrayIndex = row['index'] - 1;
                    return '<div class="row" style="flex-wrap: unset">' +
                        '<input type="checkbox" style="height: 20px;width: 15px;margin-right: 5px;" name="is_hide['+ arrayIndex  +']" value="yes" class="checkbox-hide" checked id="checkbox-'+ row['index'] +'"> ' +
                        '<input type="text" name="descriptions[]" required class="combined-value-editable form-control" value="' + combinedValue + '"/>' +
                        '</div> ';
                }
            },
            {
                targets: -5,
                data: null,
                render: function (data, type, row) {

                    var code = "";
                    if(row['button_type'] == 'Vehicle') {
                        var code = row[3];
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {

                        var code = row[1];
                    }else if(row['button_type'] == 'Direct-Add') {
                        var code = row[2];
                        // if(row['table_type'] == 'vehicle-table') {
                        //     var code = row[2]
                        // }
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        code = row[1];
                    }

                    return '<span style="margin-left: 10px;">'+ code +'</span>';
                }
            },
            {
                targets: -4,
                data: null,
                render: function (data, type, row) {
                    var price = "";
                    if(row['button_type'] == 'Vehicle') {
                        var price = row[7];
                    }else{
                        var price = row[4];
                    }
                    var currency = $('#currency').val();

                    if(currency == 'USD') {
                        var value = '{{ $aed_to_usd_rate->value }}';
                        var price = price / parseFloat(value);
                    }else if(currency == 'ERU') {
                        var value = '{{ $aed_to_eru_rate->value }}';
                        var price = price / parseFloat(value);
                    }
                    return '<input type="number" min="0" name="prices[]" required class="price-editable form-control" id="price-'+ row['index'] +'" value="' + price + '"/>' +
                        '    <span id="priceError' +  row['index'] +'" class=" invalid-feedback"></span>';
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
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Shipping"  data-shipping-id="'+ row['id']+'"></button>']).draw();
        }
        else if(row['button_type'] == 'Vehicle') {
            var table = $('#dtBasicExample1').DataTable();
            table.row.add(['', row[0],row[1],row[2],row[3],row[4],row[5],row[6],row[7],row[8],row[9],row[10],
                '<button class="add-button circle-button" data-button-type="Vehicle" data-variant-id="'+ row['id']+'"></button>']).draw();
        }
        else if(row['button_type'] == 'Shipping-Document') {
            var table = shippingDocumentTable;
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Shipping-Document"  data-shipping-document-id="'+ row['id']+'"></button>']).draw();
        }
        else if(row['button_type'] == 'Certification') {
            var table = certificationTable;
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Certification" data-certification-id="'+ row['id']+'" ></button>']).draw();
        }
        else if(row['button_type'] == 'Other') {
            var table = otherTable;
            table.row.add(['', row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Other" data-other-id="'+ row['id']+'" ></button>']).draw();
        }
        else if(row['button_type'] == 'Accessory') {
            var table = $('#dtBasicExample5').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],row[6],'<button class="add-button circle-button" data-button-type="Accessory"  data-accessory-id="'+ row['id']+'" ></button>']).draw();
        }
        else if(row['button_type'] == 'SparePart') {
            var table = $('#dtBasicExample3').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],row[6],row[7],'<button class="add-button circle-button" data-sparepart-id="'+ row['id']+'" data-button-type="SparePart" ></button>']).draw();
        }
        else if(row['button_type'] == 'Kit') {
            var table = $('#dtBasicExample4').DataTable();
            table.row.add(['', row[1],row[2],row[3],row[4],row[5],'<button class="add-button circle-button" data-kit-id="'+ row['id'] +'"  data-button-type="Kit" ></button>']).draw();
        }

        var index = $(this).closest('tr').index();
        secondTable.row(index).remove().draw();
        // var data = secondTable.rows().data();

        $('#dtBasicExample2 tr').each(function(i){

           $(this).find('td input.price-editable').attr('id','price-'+ i);
           $(this).find('td input.qty-editable').attr('id','quantity-'+ i);
           $(this).find('td input.total-amount-editable').attr('id','total-amount-'+ i);
        });

        if(row['button_type'] != 'Direct-Add') {
            resetSerialNumber(table);
        }

        // total div logic
        var tableLength = secondTable.data().length;
            if(tableLength == 0) {
                $('.total-div').attr('hidden', true);
            }
            calculateTotalSum();
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
    $('#submit-button').on('click', function(e) {
        var selectedData = [];
        secondTable.rows().every(function() {
        var data = this.data();
        var vehicleId = data[0];
        var selectedDays = $(this.node()).find('.days-dropdown').val();

        selectedData.push({ vehicleId: vehicleId, days: selectedDays });
            // let formValid = true;
            // let rowCount =  secondTable.data().length;
            // for (let i = 1; i <= rowCount; i++) {
            //     var inputPrice = $('#price-' + i).val();
            //     if (inputPrice == '') {
            //         $msg = "Price is required";
            //         showPriceError($msg, i);
            //         formValid = true;
            //     }
            // }
            //
            // if(formValid == true) {
            //     e.preventDefault();
            // }
    });
    function showPriceError($msg,i)
    {
        document.getElementById("priceError"+i).textContent=$msg;
        document.getElementById("price"+i).classList.add("is-invalid");
        document.getElementById("priceError"+i).classList.add("paragraph-class");
    }
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
            row['table_type'] = 'vehicle-table';
            var brand = $('#brand option:selected').val();
            if(brand != "") {

                var brand = $('#brand option:selected').text();
            }
            var modelLine = $('#model_line option:selected').val();
            if(modelLine != "") {
                var modelLine = $('#model_line option:selected').text();
            }else{
                alertify.confirm('Please Choose Model line to add this in quotation!').set({title:"Alert !"});
            }
            var variant = $("#variant option:selected").val();
            if(variant != "") {
                row['id'] = variant;
                row['model_type'] = 'Vehicle';
                var variant = $('#variant option:selected').text();

            }else{
                var modelId = $('#model_line option:selected').val();
                row['id'] = modelId;
                row['model_type'] = 'ModelLine';
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
            row['table_type'] = 'addon-table';

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
                row['id'] = modelLine;
                row['model_type'] = 'ModelLine';
                row['model_line_id'] = modelLine;

                var modelLine = $('#accessories_model_line option:selected').text();

            }else{
                alertify.confirm('Please Choose Model line to add this in quotation!').set({title:"Alert !"});
            }
        }else if(tableType == 'spare-part-table') {
            row['table_type'] = 'addon-table';
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
                row['id'] = modelLine;
                row['model_type'] = 'ModelLine';
                row['model_line_id'] = modelLine;
                var modelLine = $('#spare_parts_model_line option:selected').text();
            }else{
                alertify.confirm('Please Choose Model line to add this in quotation!').set({title:"Alert !"});
            }
            var modelNumber = $('#spare_parts_model_description option:selected').val();
            if(modelNumber != "") {

                var modelNumber = $('#spare_parts_model_description option:selected').text();
            }

        }else if(tableType == 'kit-table') {
            row['table_type'] = 'addon-table';
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
                row['id'] = modelLine;
                row['model_type'] = 'ModelLine';
                row['model_line_id'] = modelLine;

                var modelLine = $('#kit_model_line option:selected').text();

            }else{
                alertify.confirm('Please Choose Model line to add this in quotation!').set({title:"Alert !"});

            }
            var modelNumber = $('#kit_model_description option:selected').val();
            if(modelNumber != "") {

                var modelNumber = $('#kit_model_description option:selected').text();
            }
        }
        var index = secondTable.data().length + 1;

        row.push(addon);
        row.push(brand);
        row.push(modelLine);
        row.push(modelNumber);
        row.push(interiorColor);
        row.push(exteriorColor);
        row.push(variant);
        row['button_type'] = 'Direct-Add';
        row['index'] = index;
        if(modelLine != "") {
            table.row.add(row).draw();
        }
        $('.total-div').attr('hidden', false);

        enableOrDisableSubmit();
        showPriceInSelectedValue();
        console.log(row);
    });
    $(document).on('click', '.add-button', function() {
        var secondTable = $('#dtBasicExample2').DataTable();

        var rowData = [];
        var buttonType = $(this).data('button-type');
        var index = secondTable.data().length + 1;

        var row = $(this).closest('tr');

        rowData['button_type'] = buttonType;
        rowData['model_type'] = buttonType;
        rowData['index'] = index;
        row.find('td').each(function() {
            rowData.push($(this).html());
        });
        var secondTable = $('#dtBasicExample2').DataTable();

        if(buttonType == 'Shipping') {
            var table = shippingTable;
            var id = $(this).data('shipping-id');

        }
        else if(buttonType == 'Shipping-Document') {
            var table = shippingDocumentTable;
            var id = $(this).data('shipping-document-id');

        }
        else if(buttonType == 'Certification') {
            var table = certificationTable;
            var id = $(this).data('certification-id');

        }
        else if(buttonType == 'Other') {
            var table = otherTable;
            var id = $(this).data('other-id');

        }
        else if(buttonType == 'Vehicle') {
            var table = $('#dtBasicExample1').DataTable();
            var id = $(this).data('variant-id');
        }
        else if(buttonType == 'Accessory') {
            var table = $('#dtBasicExample5').DataTable();
            var id = $(this).data('accessory-id');

        }
        else if(buttonType == 'SparePart') {
            var table = $('#dtBasicExample3').DataTable();
            var id = $(this).data('sparepart-id');

        }
        else if(buttonType == 'Kit') {
            var table = $('#dtBasicExample4').DataTable();
            var id = $(this).data('kit-id');
        }
        var modelLineId = $(this).data('model-line-id');
        rowData['model_line_id'] = modelLineId;
        rowData['id'] = id;
        secondTable.row.add(rowData).draw();
        table.row(row).remove().draw();
        resetSerialNumber(table);
        // total amount div logic
        $('.total-div').attr('hidden', false);
        CalculateTotalAmount(index);
        calculateTotalSum();
        // enableOrDisableSubmit();
        showPriceInSelectedValue();
        console.log(rowData);
    });

    function resetSerialNumber(table) {
        table.$('tbody tr').each(function(i){
            $($(this).find('td')[0]).html(i+1);
        });
    }
        $('#dtBasicExample2 tbody').on('input', '.price-editable', function(e) {
            var index =  $(this).closest('tr').index() + 1;
            CalculateTotalAmount(index);
            calculateTotalSum();

        });

        $('#dtBasicExample2 tbody').on('input', '.qty-editable', function(e) {
            var index =  $(this).closest('tr').index() + 1;
            CalculateTotalAmount(index);
            calculateTotalSum();

        });
        function CalculateTotalAmount(index) {
            var table = $('#dtBasicExample2').DataTable();
            var unitPrice = $('#price-'+index).val();
            var quantity = $('#quantity-'+index).val();
            var totalAmount = parseFloat(unitPrice) * parseFloat(quantity);
            $('#total-amount-'+index).val(totalAmount.toFixed(3));

        }
        function calculateTotalSum(){
            var count = secondTable.data().length;
            var currency = $('#currency').val();

            var totalAmount = 0;
            for(var i=1;i<= count;i++) {
                var amount = $('#total-amount-'+i).val();
                totalAmount = parseFloat(totalAmount) + parseFloat(amount);
            }

            if(currency == 'AED') {
                $('#total').val(totalAmount.toFixed(3));

            }
            $('#total_in_selected_currency').val(totalAmount.toFixed(3));
                if(currency == 'USD') {
                // USD TO AED CONVERSION
                    var value = '{{ $aed_to_usd_rate->value }}';
                    var total = parseFloat(totalAmount) * value;
                    $('#total').val(total.toFixed(3));
                }else if(currency == 'EUR') {
                    // EUR TO AED CONVERSION
                    var value = '{{ $aed_to_eru_rate->value }}';
                    var total = parseFloat(totalAmount) * value;
                    $('#total').val(total.toFixed(3));
                }

             enableOrDisableSubmit();

        }
        function enableOrDisableSubmit(){
            var count = secondTable.data().length;

            if(count > 0) {
                $('#submit-button').attr("disabled", false);
            }else{
                $('#submit-button').attr("disabled", true);
            }
        }
    $('#search-button').on('click', function() {
        var modelLineId = $('#model_line').val();
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
                    var addButton = '<button class="add-button" data-button-type="Vehicle" data-variant-id="'+ variantId +'" >Add</button>';
                    return [
                        // vehicle.id,
                        // vehicle.grn_status,
                        // vehicle.vin,
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
                        // { title: 'ID' },
                        // { title: 'Status' },
                        // { title: 'VIN' },
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
                                return '<div class="circle-button add-button" data-variant-id="'+ variantId +'" data-button-type="Vehicle" ></div>';
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
                    var addButton = '<button class="add-button" data-button-type="Accessory" data-model-line-id="'+ modelLineId +'" data-accessory-id="' + accessory.id + '">Add</button>';
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
                                return '<div class="circle-button add-button" data-button-type="Accessory" data-model-line-id="'+ modelLineId +'" data-accessory-id="' + row[0] + '"></div>';
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
                    var addButton = '<button class="add-button" data-button-type="SparePart" data-model-line-id="'+ modelLineId +'"  data-sparepart-id="' + sparePart.id + '">Add</button>';
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
                                return '<div class="circle-button add-button" data-button-type="SparePart" data-model-line-id="'+ modelLineId +'"  data-sparepart-id="' + row[0] + '"></div>';
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
                    var addButton = '<button class="add-button" data-button-type="Kit" data-model-line-id="'+ modelLineId +'" data-kit-id="' + kit.id + '">Add</button>';
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
                        kitItems = kitItems + '<table><thead><tr><th style="border: 1px solid #c4c4d4" hidden>Sl No</th><th style="border: 1px solid #c4c4d4">Item</th><th style="border: 1px solid #c4c4d4">Quantity</th></thead><tbody>'
                        var itemSlNo = 0;
                        for(var l=0; l<itemCount; l++) {
                            itemSlNo = itemSlNo+1;
                            kitItems = kitItems + '<tr><td style="border: 1px solid #c4c4d4" hidden>'+itemSlNo+'</td><td style="border: 1px solid #c4c4d4">'+kit.kit_items[l].item.addon.name;
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
                                return '<div class="circle-button add-button" data-button-type="Kit" data-model-line-id="'+ modelLineId +'" data-kit-id="' + row[0] + '"></div>';
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
