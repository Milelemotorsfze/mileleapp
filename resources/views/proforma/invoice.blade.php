@extends('layouts.table')
<div id="csrf-token" data-token="{{ csrf_token() }}"></div>
@section('content')
<style>
     div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
    .quotation-items-addons {
        padding-left: 10px;
    }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  /* padding: 4px 8px 4px 8px; */
  text-align: center;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1;
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
    }
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
    vertical-align: middle;
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
<div class="modal fade" id="vinmodal" tabindex="-1" role="dialog" aria-labelledby="vinmodalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="adoncode">Add New VIN</h5>
          <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <!-- Table for VIN and Action columns -->
          <table class="table">
            <thead>
              <tr>
                <th>VIN</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="vinTableBody">
              <!-- VIN and Action rows will be dynamically added here -->
            </tbody>
          </table>
          <!-- Text field and "Add More" button -->
          <div class="mb-3">
            <label for="vinInput" class="form-label">Stock VIN</label>
            <select id="vehicle-dropdown" class="form-control">
                </select>
                </div>
            <div class="mb-3">
            <label for="vinInput" class="form-label">Custom VIN</label>
            <input type="text" class="form-control" id="vinInput">
          </div>
          <button type="button" class="btn btn-primary btn-sm" onclick="addVinRow()">Add</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="submit_b_492" class="btn btn-primary btn-sm" onclick="submitModal()">Submit</button>
        </div>
      </div>
  </div>
</div>
<div class="modal fade" id="addAgentModal" tabindex="-1" role="dialog" aria-labelledby="addAgentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-update2_492" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fs-5 mb-1" id="adoncode">Add New Agent</h5>
        <h6 class="modal-subtitle text-muted" id="adoncode">(Please avoid adding dummy or duplicate details)</h6>
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
  <div class="modal fade" id="addMoreAgents" tabindex="-1" role="dialog" aria-labelledby="addMoreAgentsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-update3_492" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="adoncode">Add More Agents</h5>
          <button type="button" class="btn-close closeSelPrice" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row">
            <div class="col-md-12 form-group">
              <label for="name">CR Name:</label>
              <select name="cb_name" id="cb_name_more" class="form-control form-control-xs">
              </select>
            </div>
            <div class="col-md-4 form-group mt-3">
            <button type="button" id="addAgentButton" class="btn btn-primary btn-sm">Add</button>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <label for="selectedAgents">Selected Agents:</label>
              <ul id="selectedAgentsList" class="list-group">
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm closeSelPrice" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="submit_cb_492" class="btn btn-primary btn-sm">Save</button>
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
                    <div class="col-sm-4">
                        Category :
                    </div>
                    <div class="col-sm-8">
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
                    <div class="col-sm-4">
                        Nature of Deal :
                    </div>
                    <div class="col-sm-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="nature_of_deal" id="regular_deal" value="regular_deal" required {{ old('nature_of_deal') == 'regular_deal' ? 'checked' : '' }}>
                            <label class="form-check-label" for="regular_deal">Regular deal</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="nature_of_deal" id="letter_of_credit" value="letter_of_credit" required {{ old('nature_of_deal') == 'letter_of_credit' ? 'checked' : '' }}>
                            <label class="form-check-label" for="letter_of_credit">Letter of credit</label>
                        </div>
                    </div>
                    <div id="nature-of-deal-error" class="text-danger" style="display: none;"></div>
                </div>
            </div>
            <div class="col-sm-4 pt-2">
                <div class="row">
                    <div class="col-sm-4">
                        Currency :
                    </div>
                    <div class="col-sm-6">
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
               <strong> Document Details </strong>
            </div>
            <div class="col-sm-4">
                <strong> Client's Details </strong>
            </div>
            <div class="col-sm-4">
               <strong> Delivery Details </strong>
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
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
                    @endphp
                    @if ($hasPermission)
                    <div class="row mt-2">
                    <div class="col-sm-6">
                        Sales Person :
                    </div>
                    <div class="col-sm-6">
                    <select id="salespersons" name="salespersons" class="form-select" required>
                        <option disabled selected>Select a Salesperson</option>
                        @if(!empty($sales_persons) && $sales_persons->count())
                            @foreach ($sales_persons as $sales_person)
                                <option value="{{ $sales_person->id }}">{{ $sales_person->name }}</option>      
                            @endforeach
                        @else
                            <option disabled>No salespersons available</option>
                        @endif
                    </select>

                    </div>
                </div>
                    @else
                @php
                $user = \Illuminate\Support\Facades\Auth::user();
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
                    {{ isset($empProfile->location->name) ? $empProfile->location->name : '' }}
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
                @endif
            </div>
            <div class="col-sm-4">
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Client Category :
                    </div>
                    <div class="col-sm-6">
                      <select class="form-control" id="client_category" name="client_category">
                          <option value="Individual"> Individual </option>
                          <option value="Company"> Company </option>
                      </select>
                    </div>
                </div>
                <div class="row mt-2" id="contact-person-div" hidden>
                    <div class="col-sm-6">
                        Contact Person :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-xs" name="contact_person" id="contact-person" placeholder="Contact Person">
                    </div>
                </div>
                <div class="row mt-2" id="company-div" hidden>
                    <div class="col-sm-6">
                        Company :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-xs" name="company_name" id="company" placeholder="Company Name">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        <label for="timeRange">Customer :</label>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="name"  placeholder="Person Name"  class="form-control form-control-xs" id="person" value="{{$callDetails->name}}">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Contact Number :
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
                            Incoterm :
                        </div>
                        <div class="col-sm-6">
                        <select name="incoterm" id="incoterm" class="form-control form-control-xs">
                            <option></option>
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
                            Port of Loading :
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control col" id="to_shipping_port" multiple name="to_shipping_port_id" style="width: 100%">
                                <option></option>
                                @foreach($shippingPorts as $shippingPort)
                                    <option value="{{ $shippingPort->id }}">{{ $shippingPort->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Final Destination :
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control col" id="country" name="country_id" style="width: 100%">
                            <option disabled selected>Select Final Destination</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" >{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Country Of Discharge :
                        </div>
                        <div class="col-sm-6">
                        <select name="countryofdischarge" id="countryofdischarge" class="form-control form-control-xs">
                        <option disabled selected>Select Country Of Discharge</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" >{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            Port of Discharge :
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control col" id="shipping_port" name="from_shipping_port_id" style="width: 100%">
                            <option disabled selected>Select Port of Discharge</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" hidden id="local-shipment">
                    <div class="col-sm-6">
                        Place of Supply :
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="place_of_supply" readonly id="place_of_supply" class="form-control form-control-xs" placeholder="Place Of Supply">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-2">
            <div class="col-sm-4">
                <strong> Payment Details </strong>
            </div>
            <div class="col-sm-8">
                <strong> Client's Representative </strong>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4">
{{--                <div class="row mt-2">--}}
{{--                    <div class="col-sm-6">--}}
{{--                        System Code :--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-6">--}}
{{--                        <input type="number" name="system_code" id="system_code" class="form-control form-control-xs" placeholder="System Code">--}}
{{--                    </div>--}}
{{--                </div>--}}
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
                        CR Name:
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
                        CR No:
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="cb_number" id="cb_number" class="form-control form-control-xs" placeholder="CB Number" readonly>
                    </div>
                </div>
                <br>
                <button class="float-end btn btn-primary" type="button" onclick="addMoreAgents()">+ Add More Agent</button>
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
            <div class="col-sm-4"  id="due-date-div" hidden>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Payment Due Date :
                    </div>
                    <div class="col-sm-6">
                        <input type="date" min="0" class="form-control form-control-xs due-date"
                               name="due_date" id="due-date" placeholder="Due Date" >
                        <span class="required-message" style="display: none; color: red;">This field is required</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-4"  id="select-bank-div" hidden>
                <div class="row mt-2">
                    <div class="col-sm-6">
                        Bank For Payment :
                    </div>
                    <div class="col-sm-6">
                    <select name="select_bank" class="form-control">
                        <option value="rak-aed">RAK BANK AED</option>
                        <option value="rak-usd">RAK BANK USD</option>
                        <option value="rak-eur">RAK BANK EUR</option>
                        <option value="rak-aud">RAK BANK AUD</option>
                        <option value="rak-jpy">RAK BANK JPY</option>
                        <option value="hbz-aed">HBZ BANK AED</option>
                        <option value="hbz-usd">HBZ BANK USD</option>
                        <option value="hbz-eur">HBZ BANK EUR</option>
                        <option value="hbz-jpy">HBZ BANK JPY</option>
                    </select>
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
        <div class="row mt-2">
            <div class="col-sm-12">
                <div class="row mt-2">
                    <div class="col-sm-2">
                        Third Party Payment :
                    </div>
                    <div class="col-sm-2">
                    <select name="thirdpartypayment" class="form-control">
                        <option value="No">No</option>
                    </select>
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
                                        <th>System Code</th>
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
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showVehicles" name="contentType" data-target="#vehiclesContent">
                <label for="showVehicles" class="quotation-items-addons">Add Vehicles</label>
            </div>
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showAccessories" name="contentType" data-target="#accessoriesContent">
                <label for="showAccessories" class="quotation-items-addons">Add Accessories</label>
            </div>
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showSpareParts" name="contentType" data-target="#sparePartsContent">
                <label for="showSpareParts" class="quotation-items-addons">Add Spare Parts</label>
            </div>
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showKits" name="contentType" data-target="#kitsContent">
                <label for="showKits" class="quotation-items-addons">Add Kits</label>
            </div>
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showShipping" name="contentType" data-target="#shippingContent">
                <label for="showShipping" class="quotation-items-addons">Add Shipping</label>
            </div>
            <div class="col-lg-2 align-items-baseline col-md-3 col-sm-12">
                <input type="radio" id="showShippingDocuments" name="contentType" data-target="#shippingDocumentContent">
                <label for="showShippingDocuments" class="quotation-items-addons">Add Shipping Documents</label>
            </div>
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showCertificates" name="contentType" data-target="#certificateContent">
                <label for="showCertificates" class="quotation-items-addons">Certificate</label>
            </div>
            <div class="d-flex align-items-baseline col-lg-2 col-md-3 col-sm-12">
                <input type="radio" id="showOthers" name="contentType" data-target="#otherContent">
                <label for="showOthers" class="quotation-items-addons">Add Other</label>
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
                            <option value="Other"> Other</option>
                        </select>
                    </div>
{{--                    <div class="col-lg-1 col-md-6" style="margin-top: 26px;">--}}
{{--                        <a id="addnewBrandButton" data-toggle="popover" data-trigger="hover" title="Create New Brand" data-placement="top" style="float: right;"--}}
{{--                           class="btn btn-info modal-button" data-modal-id="createNewBrand"><i class="fa fa-plus" aria-hidden="true"></i> Add Brand</a>--}}
{{--                    </div>--}}
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <label class="form-label"> Model Line</label>
                        <select class="form-control col" id="model_line" style="width: 100%" name="model_line" disabled >
                            <option value="">Select Model Line</option>
                        </select>
                    </div>
{{--                    <div class="col-lg-1 col-md-6 add-new-model-line-div" style="margin-top: 26px;" >--}}
{{--                        <a id="createNewModelLineButton" data-toggle="popover" data-trigger="hover" title="Create New Model Line" data-placement="top" style="float: right;"--}}
{{--                           class="btn btn-info modal-model-line-button" data-modal-id="createNewModelLine"><i class="fa fa-plus" aria-hidden="true"></i> Add Model Line</a>--}}

{{--                    </div>--}}
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label"> Variant</label>
                        <select class="form-control col" id="variant" style="width: 100%" name="variant" disabled>
                            <option value="">Select Variant</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Exterior Color</label>
                        <select class="form-control col" id="exterior_color" style="width: 100%" name="exterior_color" disabled>
                            <option value="">Select Exterior Color</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">Interior Color</label>
                        <select class="form-control col" id="interior_color" style="width: 100%" name="interior_color" disabled>
                            <option value="">Select Interior Color</option>
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
                                        <th>S.No:</th>
                                        {{--<th>Status</th>--}}
                                        {{--<th>VIN</th>--}}
                                        <th>Brand Name</th>
                                        <th>Model Line</th>
                                        <th>Model Details</th>
                                        <th>Variant Name</th>
                                        <th>Variant Detail</th>
                                        <th>Exterior Color</th>
                                        <th>Interior Color</th>
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
                                        <option value="{{ $accessory->id }}" data-id="{{ $accessory->Addon->id }}" >{{ $accessory->Addon->name ?? '' }}@if($accessory->description!='') - {{$accessory->description}}@endif</option>
                                    @endforeach
                                    <option value="Other" data-id="Other"> Other</option>
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
                                    <option value="Other"> Other</option>
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
                                        <th>S.No:</th>
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
                                   <option value="{{ $spareParts->id }}" data-id="{{ $spareParts->Addon->id }}"  >{{ $spareParts->Addon->name ?? '' }}@if($spareParts->description!='') - {{$spareParts->description}}@endif</option>
                               @endforeach
                               <option value="Other" data-id="Other">Other</option>
                           </select>
                       </div>
                       <div class="col-lg-2 col-md-6">
                           <label for="brand"> Brand</label>
                           <select class="form-select" id="spare_parts_brand" name="spare_parts_brand" style="width: 100%">
                               <option value="">Select Brand</option>
                               @foreach($brands as $brand)
                                   <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                               @endforeach
                               <option value="Other"> Other</option>

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
                                       <th>S.No: </th>
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
                                        <option value="{{ $kit->id }}" data-id="{{ $kit->Addon->id }}">{{ $kit->Addon->name ?? '' }}@if($kit->description!='') - {{$kit->description}}@endif</option>
                                    @endforeach
                                    <option value="Other" data-id="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <label for="brand"> Brand</label>
                                <select class="form-control col" id="kit_brand" name="kit_brand" style="width: 100%">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                    <option value="Other"> Other</option>

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
                                        <th>S.No:</th>
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
                                <table id="shipping-table" class="table table-striped table-editable table-edits table" width="100%">
                                    <thead class="bg-soft-secondary">
                                    <tr>
                                        <th>S.No:</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Sailing Date</th>
                                        <th>ETA</th>
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
                                        <th>S.No:</th>
                                        <th>Code</th>
                                        <th>Name</th>
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
                                        <th>S.No:</th>
                                        <th>Code</th>
                                        <th> Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th style="width:30px;">Add Into Quotation</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <div hidden>{{$i=0;}}
                                        @foreach($certifications as $certification)
                                            <tr>
                                                <td> {{ ++$i }}</td>
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
                                        <th>S.No:</th>
                                        <th>Code</th>
                                        <th> Name</th>
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
{{--    <div class="overlay">--}}
{{--        <div class="modal" id="createNewModelLine" tabindex="-1" role="dialog" aria-labelledby="exampleModalLineCenteredLabel" aria-hidden="true">--}}
{{--            <div class="modal-dialog modal-dialog-centered" role="document">--}}
{{--                <div class="modal-content">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h5 class="modal-title" id="exampleModalLineCenteredLabel" style="text-align:center;"> Create New Model Line </h5>--}}
{{--                        <button type="button" class="btn btn-secondary btn-sm close form-control modal-close" data-dismiss="modal" aria-label="Close">--}}
{{--                            <span aria-hidden="true">X</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div class="modal-body">--}}
{{--                        <form method="POST" enctype="multipart/form-data" id="create-model-line-form">--}}
{{--                            @csrf--}}
{{--                            <div class="row modal-row">--}}
{{--                                <div class="col-xxl-12 col-lg-12 col-md-12">--}}
{{--                                    <span class="error">* </span>--}}
{{--                                    <label for="name" class="col-form-label text-md-end" >Brand</label>--}}
{{--                                </div>--}}
{{--                                <div class="col-xxl-9 col-lg-9 col-md-9 col-sm-12" id="new-brand-div" hidden>--}}
{{--                                    <input type="text" class="form-control new_brand  @error('brand_name') is-invalid @enderror" oninput="checkBrandValidation()" placeholder="Enter Brand Name" id="new-brand"  name="brand_name" >--}}
{{--                                    <span id="newBrandError" class="is-invalid" style="margin-top: 20px" ></span>--}}
{{--                                </div>--}}
{{--                                <div class="col-xxl-9 col-lg-9 col-md-9 col-sm-12" id="brand-list-div">--}}
{{--                                    <select onchange="checkBrandValidation()" class="form-control new_brand @error('brand_name') is-invalid @enderror"--}}
{{--                                            name="brand_name" id="brand-from-list" style="width: 100%">--}}
{{--                                        <option></option>--}}
{{--                                        @foreach($brands as $brand)--}}
{{--                                            <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    <span id="existingBrandError" class="is-invalid" style="margin-top: 20px" ></span>--}}
{{--                                </div>--}}
{{--                                <div class="col-xxl-3 col-lg-3 col-md-3 col-sm-12">--}}
{{--                                    <a> <button type="button" class="btn btn-info add-new-button" >Add New</button> </a>--}}
{{--                                    <a> <button type="button" class="btn btn-info add-existing-brand-button" hidden>Add From List</button> </a>--}}
{{--                                </div>--}}
{{--                                <div class="col-xxl-12 col-lg-12 col-md-12 mt-2">--}}
{{--                                    <span class="error">* </span>--}}
{{--                                    <label for="name" class="col-form-label text-md-end">Model Line</label>--}}
{{--                                </div>--}}
{{--                                <div class="col-xxl-12 col-lg-12 col-md-12">--}}
{{--                                    <input type="text" id="new_model_line_name" class="form-control  @error('model_line') is-invalid @enderror" name="model_line"--}}
{{--                                           placeholder="Enter Model Line Name" value="{{ old('model_line') }}" oninput="checkModelLine()" autofocus>--}}
{{--                                    <span id="newModelLineError" class="is-invalid"></span>--}}
{{--                                    @error('model_line')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--								<strong>{{ $message }}</strong>--}}
{{--								</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary btn-sm modal-close" data-dismiss="modal" ><i class="fa fa-times"></i> Close</button>--}}
{{--                        <button type="button" class="btn btn-primary btn-sm" id="createModelLineId" style="float: right;">--}}
{{--                            <i class="fa fa-check" aria-hidden="true"></i> Submit</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>
<div class="modal fade addonsModal-modal" id="addonsModal" tabindex="-1" aria-labelledby="addonsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addonsModalLabel">Adding Addons</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="col-lg-12">
    <!-- ... Your other HTML content ... -->
    <input type="hidden" name="modelIdInput" id="modelIdInput" />
    <input type="hidden" name="brandIdInput" id="brandIdInput" />
    <div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <label class="form-label font-size-13 text-center">Addon Type</label>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <select class="form-select" name="addonTypevehicles" id="addontypes">
        <option value="" selected disabled>Select Type</option>
            <option value="accessories">Accessories</option>
            <option value="spareParts">Spare Parts</option>
            <option value="kits">Kits</option>
        </select>
    </div>
</div>
<br>
<div id="accessoriesDropdownDiv" class="row" style="display:none;">
    <div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <label class="form-label">Accessories</label>
        </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
        <select class="form-select" name="accessoriesDropdown">
            <!-- Populate options dynamically using JavaScript -->
        </select>
    </div>
    </div>
</div>

<div id="sparePartsDropdownDiv" class="row" style="display:none;">
    <div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <label class="form-label">Spare Parts</label>
</div>
<div class="col-lg-8 col-md-12 col-sm-12">
        <select class="form-select" name="sparePartsDropdown">
            <!-- Populate options dynamically using JavaScript -->
        </select>
    </div>
</div>
</div>

<div id="kitsDropdownDiv" class="row" style="display:none;">
    <div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <label class="form-label">Kits</label>
        </div>
<div class="col-lg-8 col-md-12 col-sm-12">
        <select class="form-select" name="kitsDropdown">
            <!-- Populate options dynamically using JavaScript -->
        </select>
    </div>
     </div>
</div>
<div class="row">
    <div class="col-lg-12 text-end mt-3">
        <button type="button" class="btn btn-outline-warning" data-row-id = "" data-model-line-id = "" data-model-line-ids = "" data-index-rowstt = "" data-brand-ids = "" data-brand-id = "" id="directadding-button-ad" sparepart-id-directad = "">Directly Adding Into Quotation</button>
    </div>
    </div>
<!-- DataTable Container -->
<hr>
<div id="addonDataTableContainer" class="row" style="display:none;">
    <div class="col-lg-12">
        <table id="addonDataTable" class="display table table-striped table-editable table-edits table-bordered">
            <!-- DataTable content will be added dynamically -->
        </table>
    </div>
</div>
      </div>
    </div>
  </div>
</div>
 <input type="hidden" name="is_shipping_charge_added" value="0" id="is-shipping-charge-added">
@endsection
@push('scripts')
<script>
     function addAgentModal() {
        $('#addAgentModal').modal('show');
    }
    function addMoreAgents() {
        $('#addMoreAgents').modal('show');
    }
$(document).ready(function () {
    $('#cb_name').change(function () {
        console.log("pouch");
        var selectedAgentId = $(this).val();
        var selectedAgentName = $(this).find(':selected').text();
        $('#agents_id').val(selectedAgentId);
        $('#selected_cb_name').val(selectedAgentName);
        if (selectedAgentId) {
                        $('.system-code').removeAttr('disabled');
                    } else {
                        $('.system-code').attr('disabled', 'disabled');
                    }
    });
    fetchAgentData();
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
        var name = $('#name').val().trim();
        var phone = $('#phone').val().trim();
    if (name === "" || phone === "") {
        alert('Name and phone number cannot be blank.');
        return false;
    }
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
$(document).ready(function () {
  fetchAgentData();
  function fetchAgentData() {
    $.ajax({
      url: "{{ route('agents.getAgentNames') }}",
      method: 'GET',
      dataType: 'json',
      success: function (data) {
        $('#cb_name_more').empty();
        $('#cb_name_more').append('<option value="" disabled selected>Select Agent</option>');

        $.each(data, function (index, agent) {
          $('#cb_name_more').append('<option value="' + agent.id + '">' + agent.name + '</option>');
        });

        $('#cb_name_more').change(function () {
          var selectedAgentId = $(this).val();
          var selectedAgent = data.find(agent => agent.id == selectedAgentId);

          if (selectedAgent) {
            $('#cb_number_more').val(selectedAgent.phone).trigger('change');
          } else {
            $('#cb_number_more').val('').trigger('change');
          }
        });
      },
      error: function (error) {
        console.error('Error fetching agent data:', error);
      }
    });
  }

  $('#addAgentButton').click(function () {
    var selectedAgentId = $('#cb_name_more').val();
    var selectedAgentName = $('#cb_name_more option:selected').text();

    if (selectedAgentId && selectedAgentName) {
      var listItem = '<li class="list-group-item d-flex justify-content-between align-items-center">' + selectedAgentName +
        '<button type="button" class="btn btn-danger btn-sm removeAgentButton" data-agent-id="' + selectedAgentId + '">Remove</button></li>';
      $('#selectedAgentsList').append(listItem);
    }
  });

  $(document).on('click', '.removeAgentButton', function () {
    $(this).closest('li').remove();
  });
  $('#submit_cb_492').click(function (event) {
    event.preventDefault(); // Prevent the default form submission
    
    var modalAgentIds = [];
    $('#selectedAgentsList').find('.removeAgentButton').each(function () {
        modalAgentIds.push($(this).data('agent-id'));
    });

    var otherAgentIds = $('#agents_id').val().split(',').filter(Boolean);
    var combinedAgentIds = modalAgentIds.concat(otherAgentIds);

    $('#agents_id').val(combinedAgentIds.join(','));
    $('#addMoreAgents').modal('hide');
});
  });
</script>
<script>
        $(document).ready(function () {
    $('[name="contentType"]').on('change', function () {
        $('.contentveh').hide();
        const target = $(this).data('target');
        $(target).show();
    });
});

</script>
<script>
    // get the shipping medium charges based on port selected

    function checkBrandValidation()
    {
        var value = $("#brand-from-list").val();
        var newBrand = $('#new-brand').val();

        if(value == '')
        {
            $msg = 'Brand Name is Required';
            showExisingBrandError($msg);
        }else
        {
            $msg="";
            removeExistingBrandError($msg);
        }
       if(newBrand == '') {
            $msg = 'Brand Name is Required';
            showNewBrandError($msg);
        }
        else
        {
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
        document.getElementById("new-brand").classList.add("is-invalid");
        document.getElementById("new-brand").classList.remove("is-invalid");
        document.getElementById("newBrandError").classList.add("paragraph-class");
    }
    function removeNewBrandError($msg)
    {
        document.getElementById("newBrandError").textContent=$msg;
        document.getElementById("new-brand").classList.remove("is-invalid");
        document.getElementById("new-brand").classList.remove("is-invalid");
        document.getElementById("newBrandError").classList.remove("paragraph-class");
    }
    function showExisingBrandError($msg)
    {
        document.getElementById("existingBrandError").textContent=$msg;
        document.getElementById("brand-from-list").classList.add("is-invalid");
        document.getElementById("brand-from-list").classList.remove("is-invalid");
        document.getElementById("existingBrandError").classList.add("paragraph-class");
    }
    function removeExistingBrandError($msg)
    {
        document.getElementById("existingBrandError").textContent=$msg;
        document.getElementById("brand-from-list").classList.remove("is-invalid");
        document.getElementById("brand-from-list").classList.remove("is-invalid");
        document.getElementById("existingBrandError").classList.remove("paragraph-class");
    }
    function showPriceError($msg,i)
    {
        document.getElementById("priceError"+i).textContent=$msg;
        document.getElementById("price"+i).classList.add("is-invalid");
        document.getElementById("priceError"+i).classList.add("paragraph-class");
    }
    $(document).ready(function() {
        $('#brand').select2();

        $('#brand-from-list').select2({
            placeholder: "Select Brand"
        });
        $('#country').select2({
            placeholder: "Select Final Destination",
            maximumSelectionLength: 1,

        }).on('select2:unselecting', function(e){
            // before removing tag we check option element of tag and
            // if it has property 'locked' we will create error to prevent all select2 functionality
            var shippingAddedCount = $('#is-shipping-charge-added').val();
                if(shippingAddedCount > 0) {
                    $("#country option:selected").attr("locked", true);
                    if ($(e.params.args.data.element).attr('locked', true)) {
                        var confirm = alertify.confirm('Please remove selected Shipping Charges to change delivery details', function (e) {
                        }).set({title: "Not Able to Remove"})
                        e.preventDefault();
                    }
                }else{
                    $("#country option:selected").attr("locked", false);
                    $('#shipping_port').empty();
                    var table = $('#shipping-table').DataTable();
                    table.clear().draw();
                }
        });
        $('#shipping_port').select2({
            placeholder: "Select Port Of Discharge",
            maximumSelectionLength: 1,

        }).on('select2:unselecting', function(e){
            // before removing tag we check option element of tag and
            // if it has property 'locked' we will create error to prevent all select2 functionality
            var shippingAddedCount = $('#is-shipping-charge-added').val();
            if(shippingAddedCount > 0) {
                $("#shipping_port option:selected").attr("locked", true);
                if ($(e.params.args.data.element).attr('locked', true)) {
                    var confirm = alertify.confirm('Please remove selected Shipping Charges to change delivery details', function (e) {
                    }).set({title: "Not Able to Remove"})
                    e.preventDefault();
                }
            }else{
                $("#shipping_port option:selected").attr("locked", false);
                var table = $('#shipping-table').DataTable();
                table.clear().draw();
            }
        });

        $('#to_shipping_port').select2({
            placeholder: "Select Port Of Loading",
            maximumSelectionLength: 1,

        }).on('select2:unselecting', function(e){
            // before removing tag we check option element of tag and
            // if it has property 'locked' we will create error to prevent all select2 functionality
            var shippingAddedCount = $('#is-shipping-charge-added').val();
            if(shippingAddedCount > 0) {
                $("#to_shipping_port option:selected").attr("locked", true);
                if ($(e.params.args.data.element).attr('locked', true)) {
                    var confirm = alertify.confirm('Please remove selected Shipping Charges to change delivery details', function (e) {
                    }).set({title: "Not Able to Remove"})
                    e.preventDefault();
                }
            }else{
                $("#to_shipping_port option:selected").attr("locked", false);
                var table = $('#shipping-table').DataTable();
                table.clear().draw();
            }
        });
        $('#incoterm').select2({
            placeholder: "Select Incoterm"
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

        $('#country').select2();
        $('#countryofdischarge').select2().on('select2:select', function(e){
        let country = $(this).val();
        let url = '{{ route('quotation.shipping_ports') }}';
        $.ajax({
            type: "GET",
            url: url,
            dataType: "json",
            data: { country_id: country },
            success:function (data) {
                $('#shipping_port').empty();
                $('#shipping_port').html('<option value="">Select Shipping Port</option>');
                $.each(data, function(key, value) {
                    $('#shipping_port').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                });
            }
        });
    });

// Initialize the shipping port Select2
$('#shipping_port').select2();

        $('#client_category').on('change',function(){
            let clientCategory = $(this).val();
            if(clientCategory == 'Company') {
                let company = '{{ $callDetails->company_name }}';
                 $('#contact-person-div').attr('hidden', false);
                 $('#company-div').attr('hidden', false);
                 $('#company').val(company);
            }else{
                $('#contact-person-div').attr('hidden', true);
                $('#company-div').attr('hidden', true);

                $('#contact-person').val('');
                $('#company').val('');
            }
        });
        $('#to_shipping_port').on('change',function(){
            getShippingCharges();
        });
        $('#shipping_port').on('change',function(){
            getShippingCharges();
        });
        function getShippingCharges() {
            let fromShippingPortId = $('#shipping_port').val();
            let toShippingPortId = $('#to_shipping_port').val();
            console.log(fromShippingPortId);
            var table = $('#shipping-table').DataTable();
            let url = '{{ route('quotation.shipping_charges') }}';
            if(fromShippingPortId) {
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "json",
                    data: {
                        from_shipping_port_id: fromShippingPortId,
                        to_shipping_port_id: toShippingPortId,
                    },
                    success:function (response) {
                        console.log(response);
                        var slNo = 0;
                        var data = response.map(function(response) {
                            slNo = slNo + 1;
                            var addButton = '<div class="add-button circle-button" data-button-type="Shipping" data-shipping-id="'+ response.id +'" ></div>';

                            return [
                                slNo,
                                response.shipping_medium.code,
                                response.shipping_medium.name,
                                response.shipping_medium.description,
                                response.price,
                                response.sailing_date,
                                response.ETA,
                                addButton
                            ];
                        });
                        if ($.fn.dataTable.isDataTable('#shipping-table')) {
                            table.destroy();
                        }
                        $('#shipping-table').DataTable({
                            data: data,
                            columns: [
                                { title: 'S.No:' },
                                { title: 'Code' },
                                { title: 'Name' },
                                { title: 'Description' },
                                { title: 'Price' },
                                { title: 'Sailing Date' },
                                { title: 'ETA' },
                                { title: 'Add Into Quotation' }
                            ]
                        });
                    }
                });
            }
        }

        $('.add-new-button').on('click', function(){

            $('#brand-list-div').attr('hidden', true);
            $('#new-brand-div').attr('hidden', false);
            $('.add-existing-brand-button').attr('hidden', false);
            $('.add-new-button').hide();
            $("#brand-from-list option:selected").prop("selected", false);
            $("#brand-from-list").trigger('change.select2');

        });
        $('.add-existing-brand-button').on('click', function(){
            $('#new-brand-div').attr('hidden', true);
            $('#new-brand').val("");
            $('#brand-list-div').attr('hidden', false);
            $('.add-new-button').show();
            $('.add-existing-brand-button').attr('hidden', true);

        });

        $('#dtBasicExample2 tbody').on('click', '.checkbox-hide', function(e) {
            var id = this.id;

            var tableType = $('#'+id).attr('data-table-type');
            if(tableType == "Vehicle") {
                this.checked=!this.checked;
                alertify.confirm('Vehicle cannot be hide!').set({title:"Alert !"});
            }
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

        // $('#createModelLineId').on('click', function()
        // {
        //     // create new addon and list new addon in addon list
        //     var model_line = $('#new_model_line_name').val();
        //     var existingBrand = $("#brand-from-list").val();
        //     var newBrand = $('#new-brand').val();
        //     checkBrandValidation();
        //     checkModelLine();
        //     var brand = newBrand;
        //     var brandType = 'NEW';
        //     if(existingBrand) {
        //         var brand = existingBrand;
        //         var brandType = 'EXISTING';
        //     }
        //      if(model_line != "" && brand != "") {
        //         $.ajax
        //         ({
        //             url:"{{route('modelline-or-brand.store')}}",
        //             type: "POST",
        //             data:
        //                 {
        //                     model_line: model_line,
        //                     brand: brand,
        //                     brandType: brandType,
        //                     _token: '{{csrf_token()}}'
        //                 },
        //             dataType : 'json',
        //             success: function(result)
        //             {
        //                 if(result.brand_error) {
        //                     $msg = result.brand_error;
        //                     showNewBrandError($msg);
        //                 }
        //                 if(result.model_line_error) {
        //                     $msg = result.model_line_error;
        //                     showNewModelLineError($msg);
        //                 }
        //                 if(result.model_line_error == "" ){
        //                     if(result.brand_error == "") {

        // {{--                        $('.overlay').hide();--}}
        // {{--                        $('.modal').removeClass('modalshow');--}}
        // {{--                        $('.modal').addClass('modalhide');--}}
        // {{--                        var id = result.model_line.brand_id;--}}
        // {{--                        if(brandType == 'NEW') {--}}
        // {{--                            $('#brand').append("<option value='" + result.model_line.brand_id + "'>" + result.brand_name + "</option>");--}}
        // {{--                            $('#accessories_brand').append("<option value='" + result.model_line.brand_id + "'>" + result.brand_name + "</option>");--}}
        // {{--                            $('#spare_parts_brand').append("<option value='" + result.model_line.brand_id + "'>" + result.brand_name + "</option>");--}}
        // {{--                            $('#kit_brand').append("<option value='" + result.model_line.brand_id + "'>" + result.brand_name + "</option>");--}}

        // {{--                        }--}}
        // {{--                        $('#brand').val(id);--}}
        // {{--                        $('#brand').trigger('change.select2');--}}

        // {{--                        $('#model_line').append("<option  value='" + result.model_line.id + "'>" + result.model_line.model_line + "</option>");--}}
        // {{--                        $('#model_line').val(result.model_line.id);--}}
        // {{--                        // $('#model_line').trigger('change');--}}

        // {{--                        $('#model_line').prop('disabled', false);--}}

        // {{--                        $('#new_model_line_name').val(" ");--}}
        // {{--                        $('#brand-from-list').val(" ");--}}
        // {{--                        $('#new-brand').val(" ");--}}
        // {{--                        $('#brand-from-list').trigger('change.select2');--}}

        // {{--                        $msg = "";--}}
        // {{--                        removeNewModelLineError();--}}
        // {{--                        removeNewBrandError();--}}
        // {{--                    }--}}
        // {{--                }--}}
        // {{--            }--}}
        // {{--        });--}}
        // {{--     }--}}
        // {{--});--}}


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

        $('input[name="document_type"]').on('change', function() {
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            var documentType = $(this).val();
            if(documentType == 'Proforma') {
                $('#advance-amount-div').attr('hidden', false);
                $('#due-date-div').attr('hidden', false);
                $('#due-date').prop('required', true);
                $('#select-bank-div').attr('hidden', false);
                $('.required-message').show();
            }else{
                $('#advance-amount').val();
                $('#advance-amount-div').attr('hidden', true);
                $('#due-date-div').attr('hidden', true);
                $('#due-date').prop('required', false);
                $('#select-bank-div').attr('hidden', true);
                $('.required-message').hide();
            }
        });

        $('input[name="shipping_method"]').on('change', function() {
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            var shippingMethod = $(this).val();
            if(shippingMethod == 'CNF') {
                $('#export-shipment').attr('hidden', true);
                $('#local-shipment').attr('hidden', false);
                $('#country').val('').trigger('change');
                $('#incoterm').val('').trigger('change');
                $('#shipping_port').val('').trigger('change');
                $('#to_shipping_port').val('').trigger('change');
                $('#place_of_supply').val('DUCAMZ, Free Zone');

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
            $('#model_line').empty().append('<option value="">Select Model Line</option>');
            $('#variant').prop('disabled', true);
            $('#variant').empty().append('<option value="">Select Variant</option>');
            $.ajax({
                type: 'GET',
                url: '{{ route('booking.getmodel', ['brandId' => '__brandId__']) }}'
                    .replace('__brandId__', brandId),
                success: function(response) {
                    $.each(response, function(key, value) {
                        $('#model_line').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#model_line').append('<option value="Other">Other</option>');
                    if(brandId == 'Other') {
                        $('#model_line').val('Other');
                        $('#model_line').trigger('change');

                    }
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
                    $.each(response, function(key, value) {
                        $('#accessories_model_line').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#accessories_model_line').append('<option value="Other">Other</option>');
                    if(brandId == 'Other') {
                        $('#accessories_model_line').val('Other');
                        $('#accessories_model_line').trigger('change');

                    }
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
                    $('#spare_parts_model_line').append('<option value="Other">Other</option>');
                    if(brandId == 'Other') {
                        $('#spare_parts_model_line').val('Other');
                        $('#spare_parts_model_line').trigger('change');

                    }
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
                    $('#kit_model_line').append('<option value="Other">Other</option>');
                    if(brandId == 'Other') {
                        $('#kit_model_line').val('Other');
                        $('#kit_model_line').trigger('change');
                    }
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
            render: function (data, type, row, index) {
                var directAdd = 'Direct-Add';
                var removeButtonHtml = '<button type="button" class="circle-buttonr remove-button" data-button-type="' + directAdd + '">Remove</button>';
                if (row['button_type'] === 'Vehicle' || row['table_type'] === 'vehicle-table') {
                    var addonsButtonHtml = '<button type="button" class="btn btn-success btn-sm addons-button" style="margin-left: 5px; border-radius: 10px;" data-model-type="' + row.model_type + '" data-model-line-id="' + row.modallineidad + '" data-number="' + row.number + '" data-index="' + index.row + '" data-row-id="' + row.id + '"><i class="fa fa-asterisk"></i></button>';
                    var vinButtonHtml = '<button type="button" class="btn btn-primary btn-sm vin-button" style="margin-left: 5px; border-radius: 10px;" data-model-type="' + row.model_type + '" data-model-line-id="' + row.modallineidad + '" data-number="' + row.number + '" data-index="' + index.row + '" data-row-id="' + row.id + '" " data-row-vins="' + row.model_line_id + '"><i class="fa fa-car"></i></button>';
                    return removeButtonHtml + addonsButtonHtml + vinButtonHtml;
                } else {
                    return removeButtonHtml;
                }
            }
            },
            {
                targets: -2,
                data: null,
                render: function (data, type, row) {
                    var price = "";
                    var uuid = "";
                    var addon = 0;
                    if(row['button_type'] == 'Vehicle') {
                        var price = row[8];
                        var uuid = row['number'];
                        // $('#checkbox-'+ row['index']).prop('disabled', true);
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {
                        var price = row[4];
                    }
                    else if(row['button_type'] == 'Accessory' || row['button_type'] == 'SparePart' || row['button_type'] == 'Kit') {
                        var price = row[4];
                        var uuid = row['rowId'];
                        var addon = 1;
                    }
                    // calculate
                    var amount = price * 1;
                    if(row['table_type'] == 'vehicle-table') {
                        var uuid = row['number'];
                        // $('#checkbox-'+ row['index']).prop('disabled', true);
                    }
                    if(row['table_type'] == 'addon-table') {
                        var addon = 1;
                        var uuid = row['rowId'];
                    }
                    return '<input type="hidden" name="addon_types[]" value="'+ row['addon_type'] +'" > <input type="hidden" name="brand_ids[]" value="'+ row['brand_id'] +'" >' +
                        '<input type="hidden" name="model_line_ids[]" value="'+ row['model_line_id'] +'" >' +
                        '<input type="hidden" name="model_description_ids[]" value="'+ row['model_description_id'] +'" >' +
                        '<input type="hidden" name="is_addon[]" value="'+ addon +'" ><input type="hidden" value="'+ row['model_type'] +'" name="types[]" >' +
                        '<input type="hidden" name="uuids[]" value="'+ uuid +'" > <input type="hidden" name="reference_ids[]" value="'+ row['id'] +'"  >' +
                        '<input type="text"  value="'+ amount +'" class="total-amount-editable form-control" name="total_amounts[]" id="total-amount-'+ row['index'] +'" readonly />'+
                        '<input type="hidden" name="vinnumbers[]" value="' + row['hiddenVIN'] + '" />' ;
                }
            },
            {
                targets: -5,
                data: null,
                render: function (data, type, row) {
                    var agentId = $("#agents_id").val();
                    var disabledAttr = agentId ? '' : 'disabled';
                    return '<div class="input-group"> ' +
                               '<input type="text" min="0" value="1" step="1" class="system-code form-control" name="system_code_amount[]" id="system-code-amount-'+ row['index'] +'" ' + disabledAttr + ' />' +
                               '<div class="input-group-append"> ' +
                                   '<select class="form-control system-code-currency" name="system_code_currency[]" id="system-code-currency-'+ row['index'] +'">' +
                                       '<option value="A">A</option><option value="U">U</option>' +
                                   '</select>' +
                               '</div> ' +
                           '</div>';
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
                targets: -7,
                data: null,
                render: function (data, type, row) {
                    var combinedValue = "";
                    var tableType = row['button_type'];
                    if(row['button_type'] == 'Vehicle') {
                        var brand = row[1];
                        var modelDescription = row[3];
                        var interiorColor = row[7];
                        var exteriorColor = row[6];
                        var combinedValue = brand + ', ' + modelDescription + ', ' + exteriorColor + ', ' + interiorColor;

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
                        combinedValue =  row[1] + comma1 + row[2] + comma2 + row[3]+ comma3 + row[5] + comma4 + row[4]+ comma5 + row[6];
                        if(row['table_type'] !== 'vehicle-table') {

                            combinedValue = row[0] + comma0 + combinedValue;
                        }

                        if(row['table_type'] == 'vehicle-table') {
                            var tableType = "Vehicle";
                        }
                    }

                    // $('#checkbox-2').attr('disabled', true);
                    var arrayIndex = row['index'] - 1;
                    return '<div class="row" style="flex-wrap: unset;margin-left: 2px;">' +
                        '<input type="checkbox" style="height: 20px;width: 15px;margin-right: 5px;" data-table-type="'+ tableType +'" name="is_hide['+ arrayIndex  +']" value="yes" class="checkbox-hide"' +
                        ' checked id="checkbox-'+ row['index'] +'"> ' +
                        '<input type="text" name="descriptions[]" required class="combined-value-editable form-control" value="' + combinedValue + '"/>' +
                        '</div> ';
                }
            },
            {
                targets: -6,
                data: null,
                render: function (data, type, row) {
                    var code = "";
                    if(row['button_type'] == 'Vehicle') {
                        var code = row[4];
                    }
                    else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {

                        var code = row[1];
                    }else if(row['button_type'] == 'Direct-Add') {

                        if(row[2] != 'Other') {
                            var code = row[2];
                        }
                        if(row['table_type'] == 'vehicle-table' && row[6] != "") {
                            var code = row[6];
                        }
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
                        var price = row[8];
                    }else if(row['button_type'] == 'Shipping' || row['button_type'] == 'Shipping-Document' || row['button_type'] == 'Certification' || row['button_type'] == 'Other') {
                        var price = row[4];
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
                        '    <span id="priceError' +  row['index'] +'" class="price-error invalid-feedback"></span>';
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
            table.row.add([row[0],row[1],row[2],row[3],row[4],row[5],row[6],'<button class="add-button circle-button" data-button-type="Shipping"  data-shipping-id="'+ row['id']+'"></button>']).draw();
            var shipppingAddedCount =   $('#is-shipping-charge-added').val();
            var count = shipppingAddedCount - 1;
            $('#is-shipping-charge-added').val(count);
            // if(count <= 0) {
            //     $('#country').attr('disabled', false);
            // }
        }
        else if(row['button_type'] == 'Vehicle') {
            var table = $('#dtBasicExample1').DataTable();
            table.row.add([ row[0],row[1],row[2],row[3],row[4],row[5],row[6],row[7],row[8],row[9],row[10],
                '<button class="add-button circle-button" data-button-type="Vehicle" data-brand-id="'+ row['brand_id'] +'" data-modellineidad="'+ row['model_line_id'] +'"' +
                '   data-variant-id="'+ row['id']+'"></button>']).draw();
        }
        else if(row['button_type'] == 'Shipping-Document') {
            var table = shippingDocumentTable;
            table.row.add([row[0],row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Shipping-Document"  data-shipping-document-id="'+ row['id']+'"></button>']).draw();
        }
        else if(row['button_type'] == 'Certification') {
            var table = certificationTable;
            table.row.add([row[0],row[1],row[2],row[3],row[4],'<button class="add-button circle-button" data-button-type="Certification" data-certification-id="'+ row['id']+'" ></button>']).draw();
        }
        else if(row['button_type'] == 'Other') {
            var table = otherTable;
            table.row.add([row[0],row[1],row[2],row[3],row[4], '<button class="add-button circle-button" data-button-type="Other" data-other-id="'+ row['id']+'" ></button>']).draw();
        }
        else if(row['button_type'] == 'Accessory') {
            var table = $('#dtBasicExample5').DataTable();
            table.row.add([ row[0],row[1],row[2],row[3],row[4],row[5],row[6],'<button class="add-button circle-button" data-button-type="Accessory" ' +
            ' data-brand-id="' + row['brand_id'] + '" data-model-line-id="'+  row['model_line_id'] +'" data-accessory-id="'+ row['id']+'" ></button>']).draw();
        }
        else if(row['button_type'] == 'SparePart') {
            var table = $('#dtBasicExample3').DataTable();
            table.row.add([ row[0],row[1],row[2],row[3],row[4],row[5],row[6],row[7],'<button class="add-button circle-button" data-brand-id="'+  row['brand_id'] +'"' +
            ' data-model-description-id="'+  row['model_description_id'] +'"' +
            ' data-model-line-id="'+  row['model_line_id'] +'" data-sparepart-id="'+ row['id']+'" data-button-type="SparePart" ></button>']).draw();
        }
        else if(row['button_type'] == 'Kit') {
            var table = $('#dtBasicExample4').DataTable();
            table.row.add([ row[0],row[1],row[2],row[3],row[4],row[5],'<button class="add-button circle-button" data-brand-id="'+ row['brand_id'] +'"' +
            ' data-model-line-id="'+  row['model_line_id'] +'"  data-model-description-id="'+ row['model_description_id'] +'" data-kit-id="'+ row['id'] +'"  ' +
            'data-button-type="Kit" ></button>']).draw();
        }

        var index = $(this).closest('tr').index();
        secondTable.row(index).remove().draw();
        if(row['button_type'] != 'Direct-Add') {
            resetSerialNumber(table);
        }
        // total div logic
        var tableLength = secondTable.data().length;
            if(tableLength == 0) {
                $('.total-div').attr('hidden', true);
            }
            calculateTotalSum();
            resetIndex();
    });
    $('#submit-button').on('click', function(e) {
        
        $('.text-danger').hide();
        let hasError = false;
        if (!$('input[name="nature_of_deal"]:checked').val()) {
            $('#nature-of-deal-error').text('Please select the Nature of Deal.').show();
            hasError = true;
        }

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

    $(document).on('click', '#directadding-button', function() {
        var tableType = $(this).attr('data-table');
        var uniqueNumber = new Date().getTime() + '-' + Math.floor(Math.random() * 1000000);
        var table = $('#dtBasicExample2').DataTable();
        var row = [];
        var addon = "";
        var brand = "";
        var modelLine = "";
        var modelyear = "";
        var modelNumber = "";
        var variant = "";
        var interiorColor = "";
        var exteriorColor = "";
        row['brand_id'] = "";
        row['model_line_id'] = "";
        row['model_description_id'] = "";
        row['addon_type'] = "";
        row['rowId'] = "";

        if(tableType == 'vehicle-table') {
            row['table_type'] = 'vehicle-table';
            var brandId = $('#brand option:selected').val();
            if(brandId != "") {
                var brand = $('#brand option:selected').text();
                if(brandId != "Other") {
                    row['brand_id'] = brandId;
                }
            }
            var modelLineId = $('#model_line option:selected').val();
            if(modelLineId != "") {
                if(modelLineId != "Other") {
                    row['model_line_id'] = modelLineId;
                }
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
                var brandId =  $('#brand option:selected').val();
                if(brandId == "Other") {
                    // brand and model line is not known
                    if(modelId == 'Other') {
                        row['id'] =  brandId;
                        row['model_type'] = 'Other-Vehicle';
                    }
                }else{
                    if(modelId == 'Other') {
                        row['id'] =  $('#brand option:selected').val();
                        row['model_type'] = 'Brand';
                    }else{
                        row['id'] = modelId;
                        row['model_type'] = 'ModelLine';
                    }
                }
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
            row['addon_type'] = 'P';

            var addonId =  $('#accessories_addon option:selected').val();

            var brandId = $('#accessories_brand option:selected').val();
            if(brandId != "") {
                if(brandId != "Other") {
                    row['brand_id'] = brandId;
                }
                var brand = $('#accessories_brand option:selected').text();
            }
            var modelLineId = $('#accessories_model_line option:selected').val();
            if(modelLineId != "") {
                if(modelLineId != "Other") {
                    row['model_line_id'] = modelLineId;
                }
                var modelLine = $('#accessories_model_line option:selected').text();
            }
            if(addonId != "") {
                var Id =  $('#accessories_addon option:selected').attr('data-id');
                var addon = $('#accessories_addon option:selected').text();
                row['model_type'] = 'Addon';
                row['id'] = Id;

            }else{
                alertify.confirm('Please Choose addon to add this in quotation!').set({title:"Alert !"});

            }
        }else if(tableType == 'spare-part-table') {
            row['table_type'] = 'addon-table';
            row['addon_type'] = 'SP';

            var addonId =  $('#spare_parts_addon option:selected').val();

            if(addonId != "") {
                var Id =  $('#spare_parts_addon option:selected').attr('data-id');
                var addon = $('#spare_parts_addon option:selected').text();

                row['model_type'] = 'Addon';
                row['id'] = Id;
            }else{
                alertify.confirm('Please Choose addon to add this in quotation!').set({title:"Alert !"});
            }

            var brandId = $('#spare_parts_brand option:selected').val();

            if(brandId != "") {
                if(brandId != "Other") {
                    row['brand_id'] = brandId;
                }
                var brand = $('#spare_parts_brand option:selected').text();
            }

            var modelLineId = $('#spare_parts_model_line option:selected').val();

            if(modelLineId != "") {
                if(modelLineId != "Other") {
                    row['model_line_id'] = modelLineId;
                }
                var modelLine = $('#spare_parts_model_line option:selected').text();
            }

            var modelDescriptionId = $('#spare_parts_model_description option:selected').val();

            if(modelDescriptionId != "") {
                if(modelDescriptionId != "Other") {
                    row['model_description_id'] = modelDescriptionId;
                }
                var modelNumber = $('#spare_parts_model_description option:selected').text();
            }

        }else if(tableType == 'kit-table') {
            row['table_type'] = 'addon-table';
            row['addon_type'] = 'K';

            var addonId =  $('#kit_addon option:selected').val();
            if(addonId != "") {
                var Id =  $('#kit_addon option:selected').attr('data-id');
                var addon = $('#kit_addon option:selected').text();

                row['model_type'] = 'Addon';
                row['id'] = Id;
            }else{
                alertify.confirm('Please Choose addon to add this in quotation!').set({title:"Alert !"});
            }

            var brandId = $('#kit_brand option:selected').val();
            if(brandId != "") {
                if(brandId != "Other") {
                    row['brand_id'] = brandId;
                }
                var brand = $('#kit_brand option:selected').text();

            }
            var modelLineId = $('#kit_model_line option:selected').val();
            if(modelLineId != "") {
                if(modelLineId != "Other") {
                    row['model_line_id'] = modelLineId;
                }
                var modelLine = $('#kit_model_line option:selected').text();

            }
            var modelDescriptionId = $('#kits_model_description option:selected').val();

            if(modelDescriptionId != "") {
                if(modelDescriptionId != "Other") {
                    row['model_description_id'] = modelDescriptionId;
                }
                var modelNumber = $('#kits_model_description option:selected').text();
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
        row['number'] = uniqueNumber;

        if(tableType == 'kit-table' || tableType == 'spare-part-table' || tableType == 'accessories-table'){
            if(addonId != "") {
                table.row.add(row).draw();
            }
        }else{
            if(modelLine != "") {
                table.row.add(row).draw();
            }
        }

        $('.total-div').attr('hidden', false);

        enableOrDisableSubmit();
        showPriceInSelectedValue();

    });
    $(document).on('click', '.add-button', function() {
        var secondTable = $('#dtBasicExample2').DataTable();
        var uniqueNumber = new Date().getTime() + '-' + Math.floor(Math.random() * 1000000);
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

        rowData['brand_id'] = "";
        rowData['model_line_id'] = "";
        rowData['model_description_id'] = "";
        rowData['rowId'] = "";
        rowData['addon_type'] = "";

        if(buttonType == 'Shipping') {
            var table = $('#shipping-table').DataTable();
            var id = $(this).data('shipping-id');

            var shipppingAddedCount =   $('#is-shipping-charge-added').val();
            var count = +shipppingAddedCount + +1;
            $('#is-shipping-charge-added').val(count);
            // $('#country').attr('disabled', true);
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
            var modallineidad = $(this).data('modellineidad');
            rowData['brand_id'] = $(this).data('brand-id');
            rowData['model_line_id'] = modallineidad;

        }
        else if(buttonType == 'Accessory') {
            var table = $('#dtBasicExample5').DataTable();
            var id = $(this).data('accessory-id');
            // rowData['addon_type'] = 'P';
            // rowData['brand_id'] = $(this).data('brand-id');
            // rowData['model_line_id'] = $(this).data('model-line-id');

        }
        else if(buttonType == 'SparePart') {
            var table = $('#dtBasicExample3').DataTable();
            var id = $(this).data('sparepart-id');
            // rowData['addon_type'] = 'SP';
            // rowData['brand_id'] = $(this).data('brand-id');
            // rowData['model_line_id'] = $(this).data('model-line-id');
            // rowData['model_description_id'] = $(this).data('model-description-id');

        }
        else if(buttonType == 'Kit') {
            var table = $('#dtBasicExample4').DataTable();
            var id = $(this).data('kit-id');
            // rowData['addon_type'] = 'K';
            // rowData['brand_id'] = $(this).data('brand-id');
            // rowData['model_line_id'] = $(this).data('model-line-id');
            // rowData['model_description_id'] = $(this).data('model-description-id');
        }
        var modelLineId = $(this).data('model-line-id');

        rowData['modallineidad'] = modallineidad;
        rowData['id'] = id;
        rowData['number'] = uniqueNumber;
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

    $('#search-button').on('click', function() {
        var modelLineId = $('#model_line').val();
        var brandId = $('#brand').val();
        var variantId = $('#variant').val();
        var interiorColorId = $('#interior_color').val();
        var exteriorColorId = $('#exterior_color').val();
        if (!variantId) {
            alert("Please select a variant before searching.");
            return;
        }
        // if (!variantId) {
        //     if (!modelLineId) {
        //         alert("Please select a modelLine before searching.");
        //         return;
        //     } else{
        //         if(modelLineId != 'Other') {
        //             alert("Please select a variant before searching.");
        //             return;
        //         }
        //     }
        //
        // }
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
                var slNo = 0;
                var data = response.map(function(vehicle) {
                    slNo = slNo + 1;
                    var addButton = '<button class="circle-button add-button" data-button-type="Vehicle" data-brand-id="'+ brandId +'" data-variant-id="'+ variantId +'" data-modellineidad="'+ modelLineId +'">Add</button>';
                    return [
                        slNo,
                        // vehicle.grn_status,
                        // vehicle.vin,
                        vehicle.brand,
                        vehicle.model_line,
                        vehicle.model_detail,
                        vehicle.variant_name,
                        vehicle.variant_detail,
                        vehicle.exterior_color,
                        vehicle.interior_color,
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
                        { title: 'S.No:' },
                        // { title: 'Status' },
                        // { title: 'VIN' },
                        { title: 'Brand Name' },
                        { title: 'Model Line' },
                        { title: 'Model Detail' },
                        { title: 'Variant Name' },
                        { title: 'Variant Detail' },
                        { title: 'Exterior Color' },
                        { title: 'Interior Color' },
                        { title: 'Price' },
                        {
                            title: 'Actions',
                            // render: function(data, type, row) {
                            //     return '<div class="circle-button add-button" data-variant-id="'+ variantId +'" data-button-type="Vehicle"  data-modellineidad="'+ modelLineId +'"></div>';
                            // }
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
                console.log(response);
                var slNo = 0;
                var data = response.map(function(accessory) {
                    slNo = slNo + 1;
                    var addButton = '<div class="circle-button add-button" data-button-type="Accessory" data-brand-id="'+ brandId +'"' +
                        ' data-model-line-id="'+ modelLineId +'" data-accessory-id="' + accessory.id + '">Add</div>';
                    if(accessory.addon_description.description != null) {
                       var accessoryName = accessory.addon_description.addon.name + ' - ' + accessory.addon_description.description;
                    }
                    else {
                        var accessoryName = accessory.addon_description.addon.name;
                    }

                        var accessoryBrand = accessory.brandModelLine;

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
                        { title: 'S.No:' },
                        { title: 'Accessory Code' },
                        { title: 'Accessory Name' },
                        { title: 'Brand/Model Lines' },
                        { title: 'Selling Price(AED)'},
                        { title: 'Additional Remarks' },
                        { title: 'Fixing Charge'},
                        // { title: 'Least Purchase Price(AED)'}
                        {
                            title: 'Add Into Quotation',
                            // render: function(data, type, row) {
                            //     return '<div class="circle-button add-button" data-button-type="Accessory" data-model-line-id="'+ modelLineId +'" data-accessory-id="' + row[0] + '"></div>';
                            // }
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
                    var addButton = '<button class="circle-button add-button" data-button-type="SparePart" data-brand-id="'+ brandId +'"' +
                        ' data-model-description-id="'+ ModelDescriptionId +'"' +
                        ' data-model-line-id="'+ modelLineId +'"  data-sparepart-id="' + sparePart.id + '">Add</button>';
                    if(sparePart.addon_description.description != null) {
                       var sparePartName = sparePart.addon_description.addon.name + ' - ' + sparePart.addon_description.description;
                    }
                    else {
                        var sparePartName = sparePart.addon_description.addon.name;
                    }

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
                        { title: 'S.No: ' },
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
                            // render: function(data, type, row) {
                            //     return '<div class="circle-button add-button" data-button-type="SparePart" data-model-line-id="'+ modelLineId +'"  data-sparepart-id="' + row[0] + '"></div>';
                            // }
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
                    var addButton = '<button class="circle-button add-button" data-button-type="Kit" data-brand-id="'+ brandId +'"' +
                        ' data-model-description-id="'+ ModelDescriptionId +'" data-model-line-id="'+ modelLineId +'" data-kit-id="' + kit.id + '">Add</button>';
                    var kitName = '';
                    if(kit.addon_name.name != null) {
                       kitName = kit.addon_name.name;
                    }
                    var kitBrandName = kit.brandModelLineModelDescription;
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
                        { title: 'S.No:' },
                        { title: 'Kit Code' },
                        { title: 'Kit Name' },
                        { title: 'Brand/Model Lines/Model Description' },
                        { title: 'Selling Price(AED)'},
                        // { title: 'Model Lines/Model Description' },
                        { title: 'Items/ Quantity'},
                        // { title: 'Least Purchase Price(AED)'}
                        {
                            title: 'Add Into Quotation',
                            // render: function(data, type, row) {
                            //     return '<div class="circle-button add-button" data-button-type="Kit" data-model-line-id="'+ modelLineId +'" data-kit-id="' + row[0] + '"></div>';
                            // }
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
            $('#dtBasicExample2').on('click', '.addons-button', function () {
                var Indexdatarows = $(this).data('index');
                var modaltype = $(this).data('model-type');
                var rowbmid = $(this).data('row-id');
                var RowId = $(this).data('number');
                if(modaltype == "ModelLine" || modaltype == "Brand" || modaltype == "Vehicle")
                {
                var modelLineId = rowbmid;
                
                }
                else
                {
                var modelLineId = $(this).data('model-line-id');
                }
                $('#addonsModal').modal('show');
                clearDataTable();
                $.ajax({
                    url: '/addons-modal-forqoutation/' + modelLineId,
                    method: 'GET',
                    data: { modaltype: modaltype },
                    success: function (data) {
                        populateDropdowns(data);
                        $('#modelIdInput').val(data.modelLineId);
                        $('#brandIdInput').val(data.brands);
                        // If the button data attributes don't exist, set default values
                        $('#directadding-button-ad').data('model-line-id', data.modelLineIdname);
                        $('#directadding-button-ad').data('brand-id', data.brand_name);
                        $('#directadding-button-ad').data('model-line-ids', data.modelLineId);
                        $('#directadding-button-ad').data('brand-ids', data.brands);
                        $('#directadding-button-ad').data('index-rowstt', Indexdatarows);
                        $('#directadding-button-ad').data('row-id', RowId);
                    },
                    error: function (error) {
                        console.error('Error fetching addons:', error);
                    }
                });
                function clearDataTable() {
                    if ($.fn.DataTable.isDataTable('#addonDataTable')) {
                        $('#addonDataTable').DataTable().clear().destroy();
                        $('#addonDataTableContainer').hide();
                    }
                }
                function populateDropdowns(data) {
                    populateDropdown('accessories', data.assessoriesDesc);
                    populateDropdown('spareParts', data.sparePartsDesc);
                    populateDropdown('kits', data.kitsDesc);
                    $('select[name="addonTypevehicles"]').change(function () {
                        var selectedType = $(this).val();
                        $('#accessoriesDropdownDiv, #sparePartsDropdownDiv, #kitsDropdownDiv').hide();
                        $('#' + selectedType + 'DropdownDiv').show();
                    });
                    function populateDropdown(type, options) {
                        var dropdown = $('select[name="' + type + 'Dropdown"]');
                        dropdown.empty();
                        dropdown.append('<option value="" selected disabled>Select Please</option>');
                        $.each(options, function (index, value) {
                            if(value.description != null){
                                dropdown.append('<option value="' + value.id + '" data-id="' + value.ids + '">' + value.name + ' - ' + value.description + '</option>');
                            }
                            else{
                                dropdown.append('<option value="' + value.id + '" data-id="' + value.ids + '">' + value.name + '</option>');
                            }

                        });
                        dropdown.append('<option value="" data-id="">Other</option>');
                    }
                    // Change event for the second dropdown
                    $('select.form-select').change(function () {
                        var selectedId = $(this).val();
                        var selectedType = $('select[name="addonTypevehicles"]').val();
                        var brandId = $('#brandIdInput').val();
                        var modelLineId = $('#modelIdInput').val();
                        var ModelDescriptionId = 'ModelDescriptionId';
                        clearDataTable();
                        if(modelLineId === "undefined")
                        {
                            var modelLineId = 'modelLineId';
                        }
                        if(brandId === "")
                        {
                            var brandId = 'brandId';
                        }
                        if(selectedType == "accessories"){
                            // Make an AJAX request to the controller with the selected data
                            $.ajax({
                                url: '/get-booking-accessories/' + selectedId + '/' + brandId + '/' + modelLineId,
                                method: 'GET',
                                success: function (response) {
                                    // Update the DataTable with the received data
                                    updateDataTable(response);
                                },
                                error: function (error) {
                                    console.error('Error fetching addon data:', error);
                                }
                            });
                        }
                        else if (selectedType == "spareParts"){
                            $.ajax({
                                url: '/get-booking-spare-parts/' + selectedId + '/' + brandId + '/' + modelLineId + '/' + ModelDescriptionId,
                                type: 'GET',
                                success: function (response) {
                                    // Update the DataTable with the received data
                                    updateDataTablespareparts(response);
                                },
                                error: function (error) {
                                    console.error('Error fetching addon data:', error);
                                }
                            });
                        }
                        else if(selectedType == "kits"){
                            $.ajax({
                                url: '/get-booking-kits/' + selectedId + '/' + brandId + '/' + modelLineId + '/' + ModelDescriptionId,
                                type: 'GET',
                                success: function (response) {
                                    // Update the DataTable with the received data
                                    updateDataTablekits(response);
                                },
                                error: function (error) {
                                    console.error('Error fetching addon data:', error);
                                }
                            });
                        }
                    });
                    function updateDataTablekits(response) {
                        var slNo = 0;
                var data = response.map(function(kit) {
                    slNo = slNo + 1;
                    var kitid = kit.id;
                    var addButton = '<div class="circle-button add-button-addonsinner" data-button-type="Kit" data-row-id="' + RowId + '" data-kit-id="' + kitid + '" data-kit-id-test="' + Indexdatarows + '"></div>';
                    var kitName = '';
                    if(kit.addon_name.name != null) {
                       kitName = kit.addon_name.name;
                    }
                    var kitBrandName = kit.brandModelLineModelDescription;
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
                    else {
                        var kitSellingPrice = '';
                    }
                    return [
                            slNo,
                            kit.addon_code,
                            kitName,
                            kitBrandName,
                            kitSellingPrice,
                            kitItems,
                            addButton,
                        ];
                });
                if ($.fn.dataTable.isDataTable('#addonDataTable')) {
                    $('#addonDataTable').DataTable().destroy();
                }
                $('#addonDataTable').DataTable({
                    data: data,
                    columns: [
                        { title: 'S.No:' },
                        { title: 'Kit Code' },
                        { title: 'Kit Name' },
                        { title: 'Brand/Model Lines/Model Description' },
                        { title: 'Selling Price(AED)'},
                        { title: 'Items/ Quantity'},
                        {
                                    title: 'Add Into Quotation',
                        }
                    ]
                });
                        $('#addonDataTableContainer').show();
                    }
                    function updateDataTablespareparts(response) {
                        var slNo = 0;
                        var data = response.map(function(sparePart) {
                            slNo = slNo + 1;
                            var sparePartID = sparePart.id;
                            var addButton = '<div class="circle-button add-button-addonsinner" data-button-type="SparePart" data-row-id="' + RowId + '" data-sparepart-id="' + sparePartID + '" data-sparepart-id-test="' + Indexdatarows + '"></div>';
                            if(sparePart.addon_description.description != null) {
                                var sparePartName = sparePart.addon_description.addon.name + ' - ' + sparePart.addon_description.description;
                            }
                            else {
                                var sparePartName = sparePart.addon_description.addon.name;
                            }

                            var sparePartBrandName = sparePart.brandModelLineModelDescription;
                            console.log(sparePart.brandModelLineModelDescription);
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

                            else {
                                var sparePartSellingPrice = '';
                            }
                            return [
                                slNo,
                                sparePart.addon_code,
                                sparePartName,
                                sparePartBrandName,
                                sparePartSellingPrice,
                                sparePartFixingCharge,
                                addButton,
                            ];
                        });
                        if ($.fn.DataTable.isDataTable('#addonDataTable')) {
                            $('#addonDataTable').DataTable().destroy();
                        }
                        $('#addonDataTable').DataTable({
                            data: data,
                            columns: [
                                { title: 'S.No:' },
                                { title: 'Spare Part Code' },
                                { title: 'Spare Part Name' },
                                { title: 'Brand/Model Lines/Model Description' },
                                { title: 'Selling Price(AED)'},
                                { title: 'Fixing Charge'},
                                {
                                    title: 'Add Into Quotation',
                                }
                            ]
                        });
                        $('#addonDataTableContainer').show();
                    }
                    function updateDataTable(response) {
                        var slNo = 0;
                        var data = response.map(function(accessory) {
                            slNo = slNo + 1;
                            var accessoryId = accessory.id;
                            var addButtonadn = '<div class="circle-button add-button-addonsinner" data-button-type="Accessory" data-row-id="' + RowId + '" data-accessory-id="' + accessoryId + '"data-index-rowas="' + Indexdatarows + '"></div>';
                            if(accessory.addon_description.description != null) {
                                var accessoryName = accessory.addon_description.addon.name + ' - ' + accessory.addon_description.description;
                            }
                            else {
                                var accessoryName = accessory.addon_description.addon.name;
                            }
                            var accessoryBrand = accessory.brandModelLine;
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

                            else {
                                var accessorySellingPrice = '';
                            }
                            return [
                                slNo,
                                accessory.addon_code,
                                accessoryName,
                                accessoryBrand,
                                accessorySellingPrice,
                                accessoryFixingCharge,
                                addButtonadn,
                            ];
                        });
                        if ($.fn.DataTable.isDataTable('#addonDataTable')) {
                            $('#addonDataTable').DataTable().destroy();
                        }
                        $('#addonDataTable').DataTable({
                            data: data,
                            columns: [
                                { title: 'S.No:' },
                                { title: 'Accessory Code' },
                                { title: 'Accessory Name' },
                                { title: 'Brand/Model Lines' },
                                { title: 'Selling Price(AED)'},
                                { title: 'Fixing Charge'},
                                {
                                    title: 'Add Into Quotation',
                                }
                            ]
                        });
                        $('#addonDataTableContainer').show();
                    }
                }
            });
            // Initialize VIN data from local storage
  var vinData = JSON.parse(localStorage.getItem('vinData')) || {};
  $('#dtBasicExample2').on('click', '.vin-button', function () {
var modallineidad = $(this).data('row-vins'); // Corrected to 'model-line-id'
if(modallineidad === null)
{
    var modallineidad = $(this).data('model-line-id');  
}
var RowId = $(this).data('number');
  $.ajax({
    url: '/get-vehicles-vin-first',
    type: 'POST',
    data: {
        modallineidad: modallineidad,
      _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
    },
    success: function(response) {
  // Clear existing options
  $('#vehicle-dropdown').empty();
  // Add placeholder option
  $('#vehicle-dropdown').append($('<option>', {
    value: '',
    text: 'Please select Vin',
    selected: true
  }));
  // Populate dropdown with vehicles
  response.vehicles.forEach(function(vehicle) {
    $('#vehicle-dropdown').append($('<option>', {
      value: vehicle.vin,
      text: vehicle.vin
    }));
  });
  $('#vehicle-dropdown').select2({
    dropdownCssClass: "my-select2-dropdown" // Add a custom class to the dropdown
}).on('select2:open', function (e) {
    $('.my-select2-dropdown').css('z-index', 99999); // Set a high z-index when the dropdown is opened
});
},
    error: function(xhr, status, error) {
      // Handle error
      console.error(xhr.responseText);
    }
  });
  // Set the RowId in the modal trigger button
  $('#vinmodal').data('rowId', RowId);
  // Retrieve VINs from localStorage for the clicked row
  var storedVins = JSON.parse(localStorage.getItem('vinData'))[RowId] || [];
  // Populate modal table with stored VINs
  populateModalTable(RowId, storedVins);
  $('#vinmodal').modal('show');
});
$('#vinmodal').on('shown.bs.modal', function () {
  var RowId = $('#vinmodal').data('rowId');
  var storedVins = JSON.parse(localStorage.getItem('vinData'))[RowId] || [];
  populateModalTable(RowId, storedVins);
});
  function populateModalTable(RowId) {
    console.log(RowId);
    $('#vinTableBody').empty();
    if (vinData.hasOwnProperty(RowId)) {
      var savedVins = vinData[RowId];
      for (var i = 0; i < savedVins.length; i++) {
        var vin = savedVins[i];
        var rowHtml = '<tr><td>' + vin + '</td><td><button type="button" class="btn btn-danger btn-sm removeVinRow">Remove</button></td></tr>';
        $('#vinTableBody').append(rowHtml);
      }
    }
  }

  function addVinRow(RowId) {
  var RowId = $('#vinmodal').data('rowId');
  var vinInput = $('#vinInput').val();
  var selectedVehicle = $('#vehicle-dropdown').val();
  if (selectedVehicle) {
    vinInput = selectedVehicle;
  }

  if (vinInput !== '') {
    if (!vinData.hasOwnProperty(RowId)) {
      vinData[RowId] = [];
    }
    vinData[RowId].push(vinInput);
    populateModalTable(RowId);
  }
  $('#vinInput').val('');
  $('#vehicle-dropdown').val('');
  $('#vehicle-dropdown option[value=""]').prop('selected', true).text('Please select Vin');
}
  $(document).on('click', '.removeVinRow', function () {
  var RowId = $('#vinmodal').data('rowId');
  var vinToRemove = $(this).closest('tr').find('td:first').text();
  if (vinData.hasOwnProperty(RowId)) {
    vinData[RowId] = vinData[RowId].filter(function (vin) {
      return vin !== vinToRemove;
    });
    populateModalTable(RowId);
  }
});
function submitModal() {
    var RowId = $('#vinmodal').data('rowId');
    var mainTable = $('#dtBasicExample2').DataTable();
    var storedVins = JSON.parse(localStorage.getItem('vinData'))[RowId] || [];
    var savedVins = vinData[RowId];
    updateSecondTable(RowId, savedVins);
    $('#vinmodal').modal('hide');
}
function updateSecondTable(RowId, savedVins) {
    var secondTable = $('#dtBasicExample2').DataTable();
    var data = secondTable.rows().data().toArray();
    for (var i = 0; i < data.length; i++) {
        var rowData = data[i];
        console.log(rowData['number']);
        if (rowData['number'] === RowId) {
            var secondTableRow = secondTable.row(i);
            if (secondTableRow && secondTableRow.node()) {
                var existingData = secondTableRow.data();
                existingData['hiddenVIN'] = savedVins.join(', ');
                secondTableRow.data(existingData).draw();
                // console.log('Updated Row Data:', existingData);
                return;
            }
        }
    }
    console.error('Row with RowId ' + RowId + ' not found in the second table.');
}
            $('#addonDataTable').on('click', '.add-button-addonsinner', function () {
                var table = $('#addonDataTable').DataTable();
                var rowData = [];
                var mainTable = $('#dtBasicExample2').DataTable();
                var buttonType = $(this).data('button-type');
                rowData['model_type'] = buttonType;
                var index = mainTable.data().length + 1;
                rowData['button_type'] = buttonType;
                rowData['index'] = index;

                rowData['brand_id'] = "";
                rowData['model_line_id'] = "";
                rowData['model_description_id'] = "";
                rowData['addon_type'] = "";

                if(buttonType == 'Accessory') {
                    var datainc = $(this).data('index-rowas');
                    var id = $(this).data('accessory-id');
                    var rowId = $(this).data('row-id');
                }
                else if(buttonType == 'SparePart') {
                    var datainc = $(this).data('sparepart-id-test');
                    var id = $(this).data('sparepart-id');
                    var rowId = $(this).data('row-id');
                }
                else if(buttonType == 'Kit') {
                    var datainc = $(this).data('kit-id-test');
                    var id = $(this).data('kit-id');
                    var rowId = $(this).data('row-id');
                }
                rowData['id'] = id;
                rowData['rowId'] = rowId;
                var row = $(this).closest('tr');
                row.find('td').each(function() {
                    rowData.push($(this).html());
                });
                var subRowId = $(this).data('row-id');
                var newIndex = parseInt(datainc) + 1;
                var addedRow = mainTable.row.add(rowData).draw();
                table.row(row).remove().draw();

                var currentIndex = mainTable.row(addedRow.node()).index();
                if (currentIndex !== newIndex) {
                    var data = mainTable.data().toArray();
                    data.splice(currentIndex, 1);
                    data.splice(newIndex, 0, rowData);
                    mainTable.clear().rows.add(data).draw();
                }
                resetSerialNumber(table);
                // total amount div logic
                CalculateTotalAmount(index);
                calculateTotalSum();
                resetIndex();
                // enableOrDisableSubmit();
            });
            $(document).on('click', '#directadding-button-ad', function () {
                var brandId = $(this).data('brand-id');
                var brandIds = $(this).data('brand-ids');
                var selectedAddonType = $('#addontypes').val();
                console.log(selectedAddonType);
                var modelLineId = $(this).data('model-line-id');
                var modelLineIds = $(this).data('model-line-ids');
                var tableType = $(this).attr('data-table');
                var table = $('#addonDataTable').DataTable();
                var modelLine = "";
                var modelNumber = "";
                var variant = "";
                var interiorColor = "";
                var exteriorColor = "";
                var rowData = [];
                var mainTable = $('#dtBasicExample2').DataTable();
                rowData['table_type'] = 'addon-table';
                rowData['button_type'] = 'Direct-Add';
                var index = mainTable.data().length + 1;
                rowData['index'] = index;
                rowData['brand'] = brandId;
                rowData['modelLine'] = modelLineId;
                rowData['brand_id'] = brandIds;
                rowData['model_line_id'] = modelLineIds;
                var datainc = $(this).data('index-rowstt');
                if(selectedAddonType == "accessories")
                {
                rowData['addon_type'] = 'P';
                var selectedOption = $('select[name="accessoriesDropdown"] option:selected');
                // var id = selectedOption.val();
                var id = selectedOption.data('id');
                if(id == "")
                {
                 id = "Other";
                }
                var addons = selectedOption.text();
                var rowId = $(this).data('row-id');
                }
                else if ( selectedAddonType == "spareParts")
                {
                rowData['addon_type'] = 'SP';
                var selectedOption = $('select[name="sparePartsDropdown"] option:selected');
                // var id = selectedOption.val();
                var id = selectedOption.data('id');
                if(id == "")
                {
                 id = "Other";
                }
                var addons = selectedOption.text();
                var rowId = $(this).data('row-id');
                }
                else
                {
                rowData['addon_type'] = 'K';
                var selectedOption = $('select[name="kitsDropdown"] option:selected');
                // var id = selectedOption.val();
                var id = selectedOption.data('id');
                if(id == "")
                {
                 id = "Other";
                }
                var addons = selectedOption.text();
                var rowId = $(this).data('row-id');
                }
                rowData['id'] = id;
                rowData['rowId'] = rowId;
                var row = $(this).closest('tr');
                rowData.push(addons);
                rowData.push(brandId);
                rowData.push(modelLineId);
                rowData.push(modelNumber);
                rowData.push(interiorColor);
                rowData.push(exteriorColor);
                rowData.push(variant);
                rowData.push(id);
                rowData['model_description_id'] = '';
                rowData['model_type'] = 'Addon';
                rowData['button_type'] = 'Direct-Add';
                rowData['table_type'] = 'addon-table';
                var subRowId = $(this).data('row-id');
                var newIndex = parseInt(datainc) + 1;
                console.log(rowData);
                var addedRow = mainTable.row.add(rowData).draw();
                table.row(row).remove().draw();
                var currentIndex = mainTable.row(addedRow.node()).index();
                if (currentIndex !== newIndex) {
                    var data = mainTable.data().toArray();
                    data.splice(currentIndex, 1);
                    data.splice(newIndex, 0, rowData);
                    mainTable.clear().rows.add(data).draw();
                }
                resetSerialNumber(table);
                // total amount div logic
                CalculateTotalAmount(index);
                calculateTotalSum();
                resetIndex();
                // enableOrDisableSubmit();
            });
            // Event handler for removing a row
            $('#dtBasicExample2').on('click', '.remove-row-button', function () {
                var row = $(this).closest('tr');
                var mainTable = $('#dtBasicExample2').DataTable();
                mainTable.row(row).remove().draw();
                // if(row['button_type'] != 'Direct-Add') {
                //     resetSerialNumber(table);
                // }

                // total div logic
                var tableLength = secondTable.data().length;
                if(tableLength == 0) {
                    $('.total-div').attr('hidden', true);
                }
                calculateTotalSum();
            });

            function resetSerialNumber(table) {

                table.$('tbody tr').each(function(i){
                    $($(this).find('td')[0]).html(i+1);
                });
            }
            function CalculateTotalAmount(index) {
                var table = $('#dtBasicExample2').DataTable();
                var unitPrice = $('#price-'+index).val();
                var quantity = $('#quantity-'+index).val();
                var totalAmount = parseFloat(unitPrice) * parseFloat(quantity);
                $('#total-amount-'+index).val(totalAmount.toFixed(3));

            }
            function calculateTotalSum(){
                var secondTable = $('#dtBasicExample2').DataTable();
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
                var secondTable = $('#dtBasicExample2').DataTable();
                var count = secondTable.data().length;
                if(count > 0) {
                    $('#submit-button').attr("disabled", false);
                }else{
                    $('#submit-button').attr("disabled", true);
                }
            }
            function resetIndex() {
                var table = $("#dtBasicExample2 tr");
                table.each(function(i){
                    var checkboxIndex = i - 1;
                   $(this).find(".total-amount-editable").attr('id','total-amount-' + i);
                   $(this).find(".qty-editable").attr('id','quantity-' + i);
                   $(this).find(".price-editable").attr('id','price-' + i);
                    $(this).find(".system-code").attr('id','system-code-amount-' + i);
                    $(this).find(".price-editable").attr('id','price-' + i);
                    $(this).find(".system-code-currency").attr('id','system-code-currency-' + i);
                    $(this).find(".checkbox-hide").attr('id','checkbox-' + i);
                    $(this).find(".checkbox-hide").attr('name','is_hide['+ checkboxIndex +']');
                    $(this).find(".price-error").attr('id','priceError'+ checkboxIndex);
                });
            }
        </script>
        <script>
        $(document).ready(function() {
            $('#countryofdischarge').prop('disabled', true).html('<option value="" disabled selected>Select Shipping Country</option>');
    $('#country').change(function() {
        var countryId = $(this).val();
        var countryName = $('#country option:selected').text();
        if (countryId) {
            $('#countryofdischarge').prop('disabled', false).empty().html('<option value="" disabled selected>Select Shipping Country</option>');
            $.ajax({
                url: '/countries/' + countryId + '/neighbors',
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#countryofdischarge').empty();
                    $('#countryofdischarge').html('<option value="" disabled selected>Select Shipping Country</option>');
                    $('#countryofdischarge').append('<option value="' + countryId + '">' + countryName + '</option>');
                    $.each(data, function(key, value) {
                        if (key != countryId) {
                            $('#countryofdischarge').append('<option value="' + key + '">' + value + '</option>');
                        }
                    });
                }
            });
        } else {
            $('#countryofdischarge').empty();
            $('#countryofdischarge').prop('disabled', true).empty().html('<option value="" disabled selected>Select Country first</option>');
        }
    });
});
</script>
@endpush