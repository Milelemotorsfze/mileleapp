@extends('layouts.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
	#overlay {
		position: fixed;
		display: none;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0,0,0,0.5);
		z-index: 2;
		cursor: wait;
	}
	#overlay-content {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		color: white;
	}
	.btn-style {
		font-size:0.7rem!important;
		line-height: 0.1!important;
	}
	.comment {
		margin-bottom: 20px;
	}
	.reply {
		margin-left: 30px; 
		margin-top: 10px;
	}
	.replies {
		margin-left: 30px; 
	}
	.currencyClass {
		padding-top:5px!important;
	}
	.table>:not(caption)>*>* {
		padding: .3rem .3rem!important;
		-webkit-box-shadow: inset 0 0 0 0px var(--bs-table-accent-bg)!important;
	}
	table {
        border-collapse: collapse;
        width: 100%;
    }
	th {
		font-size:12px!important;
	}
	td {
		font-size:12px!important;
	}
    #work-order-history-table td, th{
        font-size: 14px !important;
    }
    #textInput {
        display: none;
    }
    #switchToDropdown {
        display: none;
    }
	.addon_btn_round {
		width: 20px!important;
		height: 14px!important;
		display: inline-block;
		text-align: center;
		line-height: 10px!important;
		margin-left: 0px!important;
		margin-top: 0px!important;
		border: 1px solid #2ab57d;
		color:#fff;
		background-color: #2ab57d;
		border-radius:5px;
		cursor: pointer;
		padding-top:1px!important;
	}
	.addon_remove_btn_round {
		width: 20px!important;
		height: 14px!important;
		display: inline-block;
		text-align: center;
		line-height: 10px!important;
		margin-left: 0px!important;
		margin-top: 0px!important;
		border: 1px solid #4ba6ef;
		color:#fff;
		background-color: #4ba6ef;
		border-radius:5px;
		cursor: pointer;
		padding-top:1px!important;
	}
	.btn_round {
		width: 20px!important;
		height: 14px!important;
		display: inline-block;
		text-align: center;
		line-height: 10px!important;
		margin-left: 0px!important;
		margin-top: 0px!important;
		border: 1px solid #ccc;
		color:#fff;
		background-color: #fd625e;
		border-radius:5px;
		cursor: pointer;
		padding-top:1px!important;
	}
	.btn_round_big {
		margin-top: 37px!important; padding-top: 8px!important;
		width: 30px; height: 30px; display: inline-block; text-align: center; line-height: 35px; margin-left: 10px; margin-top: 28px; border: 1px solid #ccc;
			color:#fff; background-color: #fd625e; border-radius:5px; cursor: pointer; padding-top:7px;
	}
	.btn_round_big:hover {
		color: #fff; background: #fd625e; border: 1px solid #fd625e;
	}
	.card-header {
		background-color:#e6f1ff!important;
	}
	.card-body {
		background-color:#fafcff!important;
	}
	.no-border {
		border:none!important;
	}
    .select2-container {
        width: 100% !important;
    }
</style>
@include('layouts.formstyle')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo','create-export-cnf-wo','create-local-sale-wo','create-lto-wo']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title"> @if(isset($workOrder)) Edit @else Create @endif @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order </h4>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{ route('work-order.index',$type) }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> List</a>
</div>
<div class="card-body">
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
	@include('work_order.export_exw.approvals')
	<div class="row">
		<div class="col-xxl-12 col-lg-12 col-md-12">
			<button style="float:right;" class="btn btn-sm btn-success" id="submit-from-top">Submit</button>
		</div>
	</div>
	</br>
		<form id="WOForm" name="WOForm" action="{{ isset($workOrder) ? route('work-order.update', $workOrder->id) : route('work-order.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
    @if(isset($workOrder))
        @method('PUT')
    @endif 
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>General Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<input type="hidden" name="customerCount" id="customerCount" value={{$customerCount ?? ''}}>
					<input type="hidden" name="wo_id" id="wo_id" value={{ isset($workOrder) ? $workOrder->id : '' }}>
					<input type="hidden" name="type" id="type" value={{$type ?? ''}}>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<!-- <span class="error">* </span> -->
						<label for="date" class="col-form-label text-md-end">{{ __('Date') }}</label>
						<input type="text" class="form-control widthinput" readonly 
						value="{{ isset($workOrder) && $workOrder->date ? \Carbon\Carbon::parse($workOrder->date)->format('d M Y') : \Carbon\Carbon::now()->format('d M Y') }}">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="so_number" class="col-form-label text-md-end">{{ __('SO Number') }}</label>
						<input id="so_number" name="so_number" type="text" class="form-control widthinput @error('so_number') is-invalid @enderror" placeholder="Enter SO Number"
							value="{{ isset($workOrder) ? $workOrder->so_number : 'SO-00' }}" autocomplete="so_number" onkeyup="setWo()"
							@if(isset($workOrder) && $workOrder->so_number != '') readonly @endif>
					</div>
					@if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							
							<span class="error">* </span>
							<label for="batch" class="col-form-label text-md-end">{{ __('Choose Batch') }}</label>
							<select name="batch" id="batch" class="form-control widthinput" autofocus>
								<option value="">Choose Batch</option>
								@for ($i = 1; $i <= 10; $i++)
									<option value="Batch {{ $i }}" {{ isset($workOrder) && $workOrder->batch == "Batch $i" ? 'selected' : '' }}>Batch {{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>
					@endif
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="wo_number" class="col-form-label text-md-end">{{ __('WO Number') }}</label>
						<input id="wo_number" type="text" class="form-control widthinput @error('wo_number') is-invalid @enderror" name="wo_number"
							placeholder="Enter WO" value="{{ isset($workOrder) ? $workOrder->wo_number : 'WO-' }}" autocomplete="wo_number" autofocus readonly>
					</div>
					<div class="col-xxl-5 col-lg-11 col-md-11">
						<label for="customer_name" class="col-form-label text-md-end">{{ __('Customer Name') }}</label>
                        <input hidden id="customer_type" name="customer_type" value="existing">
                        <input hidden id="customer_reference_id" name="customer_reference_id" value="">
                        <input hidden id="customer_reference_type" name="customer_reference_type" value="">
                        <select id="customer_name" name="existing_customer_name" class="form-control widthinput" multiple="true">
                            @foreach($customers as $customer)
                            <option value="{{$customer->customer_name ?? ''}}"
							>{{$customer->customer_name ?? ''}}</option>
                            @endforeach
                        </select> 
						<input type="text" id="textInput" placeholder="Enter Customer Name" name="new_customer_name"
							class="form-control widthinput @error('customer_name') is-invalid @enderror" onkeyup="sanitizeInput(this)">
					</div>
                    <div class="col-xxl-1 col-lg-1 col-md-1" id="Other">
                        <a title="Create New Customer" onclick="checkValue()" style="margin-top:38px; width:100%;"
                            class="btn btn-sm btn-info modal-button"><i class="fa fa-plus" aria-hidden="true"></i> Create New</a>
                    </div>
                    <div class="col-xxl-1 col-lg-1 col-md-1" id="switchToDropdown">
                        <a title="Choose Customer Name" onclick="switchToDropdown()" style="margin-top:38px; width:100%;"
                            class="btn btn-sm btn-info modal-button"><i class="fa fa-arrow-down " aria-hidden="true"></i> Choose</a>
                    </div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="customer_email" class="col-form-label text-md-end">{{ __('Customer Email ID') }}</label>
						<input id="customer_email" type="text" class="form-control widthinput @error('customer_email') is-invalid @enderror" name="customer_email"
							placeholder="Enter Customer Email ID" value="{{ isset($workOrder) ? $workOrder->customer_email : '' }}" autocomplete="customer_email" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
						<label for="customer_company_number" class="col-form-label text-md-end">{{ __('Customer Contact Number') }}</label>
							<input id="customer_company_number" type="tel" class="widthinput contact form-control @error('customer_company_number[full]') is-invalid @enderror" 
							name="customer_company_number[main]" placeholder="Enter Customer Contact Number" value="" autocomplete="customer_company_number[full]" autofocus 
							onkeyup="sanitizeNumberInput(this)">
							<input type="hidden" id="customer_company_number_full" name="customer_company_number[full]" value="{{ isset($workOrder) ? $workOrder->customer_company_number : '' }}">
					</div></div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="customer_address" class="col-form-label text-md-end">{{ __("Customer Address" ) }}</label>
						<textarea rows="3" id="customer_address" type="text" class="form-control @error('customer_address') is-invalid @enderror"
							name="customer_address" placeholder="Address in UAE" value="{{ isset($workOrder) ? $workOrder->customer_address : '' }}"  autocomplete="customer_address"
							autofocus onkeyup="sanitizeInput(this)"></textarea>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="customer_representative_name" class="col-form-label text-md-end">{{ __("Customer Representative Name" ) }}</label>
						<input id="customer_representative_name" type="text" class="form-control widthinput @error('customer_representative_name') is-invalid @enderror" name="customer_representative_name"
							placeholder="Enter Customer Representative Name" value="{{ isset($workOrder) ? $workOrder->customer_representative_name : '' }}" 
							autocomplete="customer_representative_name" autofocus onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="customer_representative_email" class="col-form-label text-md-end">{{ __('Customer Representative Email ID') }}</label>
						<div class="dropdown-option-div">
							<input id="customer_representative_email" type="text" class="form-control widthinput @error('customer_representative_email') is-invalid @enderror"
								name="customer_representative_email"
								placeholder="Enter Customer Representative Email ID" value="{{ isset($workOrder) ? $workOrder->customer_representative_email : '' }}" autocomplete="customer_representative_email" autofocus>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
						<label for="customer_representative_contact" class="col-form-label text-md-end">{{ __('Customer Representative Contact Number') }}</label>
						<input id="customer_representative_contact" type="tel" class="widthinput contact form-control @error('customer_representative_contact[full]')
							is-invalid @enderror" name="customer_representative_contact[main]" placeholder="Enter Customer Representative Contact Number"
							value="" autocomplete="customer_representative_contact[full]" autofocus onkeyup="sanitizeNumberInput(this)">
							<input type="hidden" id="customer_representative_contact_full" name="customer_representative_contact[full]" value="{{ isset($workOrder) ? $workOrder->customer_representative_contact : '' }}">
					</div>
					</div>
					@if(isset($type) && $type == 'export_exw')
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="freight_agent_name" class="col-form-label text-md-end">{{ __('Freight Agent Name') }}</label>
						<input id="freight_agent_name" type="text" class="form-control widthinput @error('freight_agent_name') is-invalid @enderror"
							name="freight_agent_name"
							placeholder="Enter Freight Agent Name" value="{{ isset($workOrder) ? $workOrder->freight_agent_name : '' }}" autocomplete="freight_agent_name" 
							autofocus onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="freight_agent_email" class="col-form-label text-md-end">{{ __('Freight Agent Email ID') }}</label>
						<input id="freight_agent_email" type="text" class="form-control widthinput @error('freight_agent_email') is-invalid @enderror"
							name="freight_agent_email" 
							placeholder="Enter Freight Agent Email ID" value="{{ isset($workOrder) ? $workOrder->freight_agent_email : '' }}" autocomplete="freight_agent_email" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
						<label for="freight_agent_contact_number" class="col-form-label text-md-end">{{ __('Freight Agent Contact Number') }}</label>
						<input id="freight_agent_contact_number" type="tel" class="widthinput contact form-control @error('freight_agent_contact_number[full]')
							is-invalid @enderror" name="freight_agent_contact_number[main]" placeholder="Enter Freight Agent Contact Number"
							value="" autocomplete="freight_agent_contact_number[full]" autofocus onkeyup="sanitizeNumberInput(this)">
							<input type="hidden" id="freight_agent_contact_number_full" name="freight_agent_contact_number[full]" value="{{ isset($workOrder) ? $workOrder->freight_agent_contact_number : '' }}">
					</div>
					</div>
					@endif
					@if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
					<div class="col-xxl-4 col-lg-6 col-md-6">
                        <span class="error">* </span>
						<label for="port_of_loading" class="col-form-label text-md-end">{{ __('Port of Loading') }}</label>
						<input id="port_of_loading" type="text" class="form-control widthinput @error('port_of_loading') is-invalid @enderror"
							name="port_of_loading" onkeyup="sanitizeInput(this)"
							placeholder="Enter Port of Loading" value="{{ isset($workOrder) ? $workOrder->port_of_loading : '' }}" autocomplete="port_of_loading" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
                        <span class="error">* </span>
						<label for="port_of_discharge" class="col-form-label text-md-end">{{ __('Port of Discharge') }}</label>
						<input id="port_of_discharge" type="text" class="form-control widthinput @error('port_of_discharge') is-invalid @enderror"
							name="port_of_discharge" onkeyup="sanitizeInput(this)"
							placeholder="Enter Port of Discharge" value="{{ isset($workOrder) ? $workOrder->port_of_discharge : '' }}" autocomplete="port_of_discharge" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
                        <span class="error">* </span>
						<label for="final_destination" class="col-form-label text-md-end">{{ __('Final Destination') }}</label>
						<input id="final_destination" type="text" class="form-control widthinput @error('final_destination') is-invalid @enderror"
							name="final_destination" onkeyup="sanitizeInput(this)"
							placeholder="Enter Final Destination" value="{{ isset($workOrder) ? $workOrder->final_destination : '' }}" autocomplete="final_destination" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 radio-main-div">
						<label for="transport_type" class="col-form-label text-md-end">{{ __('Transport Type') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="transport_type" name="transport_type" value="air" id="air" 
										{{ isset($workOrder) && $workOrder->transport_type == 'air' ? 'checked' : '' }} />
									<label for="air">Air</label>
								</div>
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="transport_type" name="transport_type" value="sea" id="sea" 
										{{ isset($workOrder) && $workOrder->transport_type == 'sea' ? 'checked' : '' }} />
									<label for="sea">Sea</label>
								</div>
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="transport_type" name="transport_type" value="road" id="road" 
										{{ isset($workOrder) && $workOrder->transport_type == 'road' ? 'checked' : '' }} />
									<label for="road">Road</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="brn-file-div">
						<label for="brn_file" class="col-form-label text-md-end">{{ __('Upload BRN') }}</label>
						<input type="file" class="form-control widthinput" id="brn_file" name="brn_file"
							accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="brn-div">
						<label for="brn" class="col-form-label text-md-end">{{ __('BRN') }}</label>
						<input id="brn" type="text" class="form-control widthinput @error('brn') is-invalid @enderror" name="brn" onkeyup="sanitizeInput(this)"
							placeholder="Enter BRN" autocomplete="brn" value="{{ isset($workOrder) ? $workOrder->brn : '' }}" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="container-number-div">
						<label for="container_number" class="col-form-label text-md-end">{{ __('Container Number') }}</label>
						<input id="container_number" type="text" class="form-control widthinput @error('container_number') is-invalid @enderror" name="container_number"
							placeholder="Enter Container Number" autocomplete="container_number" value="{{ isset($workOrder) ? $workOrder->container_number : '' }}" autofocus
							onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div" id="airline-div">
						<div class="dropdown-option-div">
							<label for="airline" class="col-form-label text-md-end">{{ __('Choose Airline') }}</label>
							<select name="airline" id="airline" multiple="true" class="form-control widthinput" autofocus>
								@foreach($airlines as $airline)
									<option value="{{$airline->name}}" 
										{{ isset($workOrder) && $workOrder->airline == $airline->name ? 'selected' : '' }}>{{$airline->name}}
									</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="airway-bill-div">
						<label for="airway_bill" class="col-form-label text-md-end">{{ __('Airway Bill') }}</label>
						<input id="airway_bill" type="text" class="form-control widthinput @error('airway_bill') is-invalid @enderror"
							name="airway_bill" onkeyup="sanitizeInput(this)"
							placeholder="Enter Airway Bill" value="{{ isset($workOrder) ? $workOrder->airway_bill : '' }}" autocomplete="airway_bill" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="shippingline-div">
						<label for="shipping_line" class="col-form-label text-md-end">{{ __('Shipping Line') }}</label>
						<input id="shipping_line" type="text" class="form-control widthinput @error('shipping_line') is-invalid @enderror"
							name="shipping_line" onkeyup="sanitizeInput(this)"
							placeholder="Enter Shipping Line" value="{{ isset($workOrder) ? $workOrder->shipping_line : '' }}" autocomplete="shipping_line" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="forward-import-code-div">
						<label for="forward_import_code" class="col-form-label text-md-end">{{ __('Forward Import Code') }}</label>
						<input id="forward_import_code" type="text" class="form-control widthinput @error('forward_import_code') is-invalid @enderror"
							name="forward_import_code" onkeyup="sanitizeInput(this)"
							placeholder="Enter Forward Import Code" value="{{ isset($workOrder) ? $workOrder->forward_import_code : '' }}" autocomplete="forward_import_code" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="trailer-number-plate-div">
						<label for="trailer_number_plate" class="col-form-label text-md-end">{{ __('Trailer Number Plate') }}</label>
						<input id="trailer_number_plate" type="text" class="form-control widthinput @error('trailer_number_plate') is-invalid @enderror"
							name="trailer_number_plate" onkeyup="sanitizeInput(this)"
							placeholder="Enter Trailer Number Plate" value="{{ isset($workOrder) ? $workOrder->trailer_number_plate : '' }}" autocomplete="trailer_number_plate" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="transportation-company-div">
						<label for="transportation_company" class="col-form-label text-md-end">{{ __('Transportation Company') }}</label>
						<input id="transportation_company" type="text" class="form-control widthinput @error('transportation_company') is-invalid @enderror"
							name="transportation_company" onkeyup="sanitizeInput(this)"
							placeholder="Enter Transportation Company" value="{{ isset($workOrder) ? $workOrder->transportation_company : '' }}" autocomplete="transportation_company" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div" id="transporting-driver-contact-number-div">
					<div class="dropdown-option-div">
						<label for="transporting_driver_contact_number" class="col-form-label text-md-end">{{ __('Transporting Driver Contact Number') }}</label>
						<input id="transporting_driver_contact_number" type="tel" class="widthinput contact form-control @error('transporting_driver_contact_number[full]')
							is-invalid @enderror" name="transporting_driver_contact_number[main]" placeholder="Enter Transporting Driver Contact Number"
							value="" autocomplete="transporting_driver_contact_number[full]" autofocus onkeyup="sanitizeNumberInput(this)">
							<input type="hidden" id="transporting_driver_contact_number_full" name="transporting_driver_contact_number[full]" value="{{ isset($workOrder) ? $workOrder->transporting_driver_contact_number : '' }}">
					</div>
					</div>
					<div class="col-xxl-8 col-lg-6 col-md-6" id="airway-details-div">
						<label for="airway_details" class="col-form-label text-md-end">{{ __('Airway Details') }}</label>
						<input id="airway_details" type="text" class="widthinput contact form-control @error('airway_details')
							is-invalid @enderror" name="airway_details" placeholder="Enter Airway Details"  onkeyup="sanitizeInput(this)"
							value="{{ isset($workOrder) ? $workOrder->airway_details : '' }}" autocomplete="airway_details" autofocus>
					</div>
					<div class="col-xxl-8 col-lg-6 col-md-6" id="transportation-company-details-div">
						<label for="transportation_company_details" class="col-form-label text-md-end">{{ __('Transportation Company Details') }}</label>
						<input id="transportation_company_details" type="text" class="widthinput contact form-control @error('transportation_company_details')
							is-invalid @enderror" name="transportation_company_details" placeholder="Enter Transportation Company Details"  onkeyup="sanitizeInput(this)"
							value="{{ isset($workOrder) ? $workOrder->transportation_company_details : '' }}" autocomplete="transportation_company_details" autofocus>
					</div>
                        <div class="row brn-preview-div" hidden>
							<div class="col-lg-12 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="brn_file_label"></span>
								<div id="brn_file_preview">
									@if(isset($workOrder->brn_file))
									<div id="brn_file_preview1">
										<div class="row">
											<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
												<h6 class="fw-bold text-center mb-1" style="float:left;">BRN File</h6>
											</div>
											<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
												<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
												<a href="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" download class="text-white">
												Download
												</a>
												</button>
												<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
													data-file-type="BRN_File"> Delete</button>
											</div>
										</div>
										<iframe src="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" alt="BRN File"></iframe>
									</div>
									@endif
								</div>
							</div>
						</div>
					@endif
				</div>
				<hr>
				<div class="row">
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="so_total_amount" class="col-form-label text-md-end">SO Total Amount:</label>
						<div class="input-group">
							<input type="text" id="so_total_amount" name="so_total_amount" value="{{ isset($workOrder) ? $workOrder->so_total_amount : '' }}" 
							class="form-control widthinput" placeholder="Enter SO Total Amount" onkeyup="sanitizeAmount(this)">
							<div class="input-group-append">
								<select id="currency" class="form-control widthinput currencyClass" name="currency" onchange="updateCurrency()">
									<option value="AED" {{ isset($workOrder) && $workOrder->currency == 'AED' ? 'selected' : '' }}>AED</option>
									<option value="USD" {{ isset($workOrder) && $workOrder->currency == 'USD' ? 'selected' : '' }}>USD</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="so_vehicle_quantity" class="col-form-label text-md-end"> SO Vehicle Quantity :</label>
						<input id="so_vehicle_quantity" type="number" class="form-control widthinput @error('so_vehicle_quantity') is-invalid @enderror" name="so_vehicle_quantity"
							placeholder="Enter SO Vehicle Quantity" value="{{ isset($workOrder) ? $workOrder->so_vehicle_quantity : '' }}" autocomplete="so_vehicle_quantity" 
							autofocus onkeyup="sanitizeQuantity(this)">
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="deposit_received_as" class="col-form-label text-md-end"> Deposit Received As :</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="deposit_received_as" name="deposit_received_as" value="total_deposit" id="total_deposit" 
									{{ isset($workOrder) && $workOrder->deposit_received_as == 'total_deposit' ? 'checked' : '' }} />
									<label for="total_deposit">Total Deposit</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="deposit_received_as" name="deposit_received_as" value="custom_deposit" id="custom_deposit"
									{{ isset($workOrder) && $workOrder->deposit_received_as == 'custom_deposit' ? 'checked' : '' }} />
									<label for="custom_deposit">Custom Deposit</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3" id="amount-received-div">
						<label for="amount_received" class="col-form-label text-md-end">Amount Received :</label>
						<div class="input-group">
							<input type="text" class="form-control widthinput" id="amount_received" name="amount_received" placeholder="Enter Total Deposit Received" 
							value="{{ isset($workOrder) ? $workOrder->amount_received : '' }}" onkeyup="sanitizeAmount(this)">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="amount_received_currency">{{ isset($workOrder) ? $workOrder->currency : 'AED' }}</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3" id="balance-amount-div">
						<label for="balance_amount" class="col-form-label text-md-end">Balance Amount :</label>
						<div class="input-group">
							<input type="text" class="form-control widthinput" id="balance_amount" name="balance_amount" placeholder="Enter Balance Amount" 
							value="{{ isset($workOrder) ? $workOrder->balance_amount : '' }}" readonly>
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="balance_amount_currency">{{ isset($workOrder) ? $workOrder->currency : 'AED' }}</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12" id="deposit-aganist-vehicle-div">
						<label for="deposit_aganist_vehicle" class="col-form-label text-md-end">Deposit Against Vehicle :</label>
						<select name="deposit_aganist_vehicle[]" id="deposit_aganist_vehicle" multiple="true" class="form-control widthinput" autofocus>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Vehicle Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="vin_multiple" class="col-form-label text-md-end">{{ __('VIN') }}</label>
						<select id="vin_multiple" name="vin_multiple" class="form-control widthinput" multiple="true">
							@foreach($vins as $vin)
							<option value="{{$vin->vin ?? ''}}">{{$vin->vin ?? ''}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12 addon_outer" id="addon-dynamic-div">
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<a  title="Add VIN" style="margin-top:38px;float:right;"
							class="btn btn-sm btn-info modal-button add-addon-btn"><i class="fa fa-plus" aria-hidden="true"></i> Addon</a>
					</div>
				</div>
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<a  title="Add VIN" style="margin-top:38px; float:left;"
							class="btn btn-sm btn-info modal-button add-vehicle-btn"><i class="fa fa-plus" aria-hidden="true"></i> add Vehicle</a>
					</div>
				</div>
				</br>
				<div class="row">
					<div class="table-responsive">
						<table id="myTable" class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
							<tr style="border-bottom:1px solid #b3b3b3;">
								<th>Action</th>
								<th>VIN</th>
								<th>Brand</th>
								<th>Variant</th>
								<th>Engine</th>
								<th>Model Description</th>
								<th>Model Year</th>
								<th>Model Year to mention on Documents</th>
								<th>Steering</th>
								<th>Exterior Colour</th>
								<th>Interior Colour</th>
								<th>Warehouse</th>
								<th>Territory</th>
								<th>Preferred Destination</th>
								<th>Import Document Type</th>
								<th>Ownership Name</th>
								<th>Certification Per VIN</th>
								@if(isset($type) && $type == 'export_cnf')
								<th>Shipment</th>
								@endif
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Questions</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="delivery_location" class="col-form-label text-md-end"> Delivery Location :</label>
						<input id="delivery_location" type="text" class="form-control widthinput @error('delivery_location') is-invalid @enderror" name="delivery_location"
							placeholder="Enter Delivery Location" value="{{ isset($workOrder) ? $workOrder->delivery_location : '' }}" autocomplete="delivery_location" 
							autofocus  onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="delivery_contact_person" class="col-form-label text-md-end"> Delivery Contact Person :</label>
						<input id="delivery_contact_person" type="text" class="form-control widthinput @error('delivery_contact_person') is-invalid @enderror" name="delivery_contact_person"
							placeholder="Enter Delivery Contact Person" value="{{ isset($workOrder) ? $workOrder->delivery_contact_person : '' }}" 
							autocomplete="delivery_contact_person" autofocus onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="delivery_date" class="col-form-label text-md-end"> Delivery Date  :</label>
						<input id="delivery_date" type="date" class="form-control widthinput @error('delivery_date') is-invalid @enderror" name="delivery_date"
							placeholder="Enter Delivery Date " value="{{ isset($workOrder) ? $workOrder->delivery_date : '' }}" autocomplete="delivery_date" autofocus
							onkeyup="sanitizeInput(this)">
					</div>
				</div>
				<div class="row" id="boe-div">
					<div class="col-xxl-12 col-lg-12 col-md-12 form_field_outer" id="child">
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn">
						<i class="fa fa-plus" aria-hidden="true"></i> Add BOE</a>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>Attachments</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="signed_pfi" class="col-form-label text-md-end">{{ __('Signed PFI') }}</label>
						<input type="file" class="form-control" id="signed_pfi" name="signed_pfi"
							accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="signed_contract" class="col-form-label text-md-end">{{ __('Signed Contract') }}</label>
						<input type="file" class="form-control" id="signed_contract" name="signed_contract"
							accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="payment_receipts" class="col-form-label text-md-end">{{ __('Payment Receipts') }}</label>
						<input type="file" class="form-control" id="payment_receipts" name="payment_receipts"
							accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="noc" class="col-form-label text-md-end">{{ __('NOC') }}</label>
						<input type="file" class="form-control" id="noc" name="noc"
							placeholder="Upload NOC" accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="enduser_trade_license" class="col-form-label text-md-end">{{ __('End User Trade License') }}</label>
						<input type="file" class="form-control" multiple id="enduser_trade_license" name="enduser_trade_license"
							placeholder="Upload End User Trade License" accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="enduser_passport" class="col-form-label text-md-end">{{ __('End User Passport Copy') }}</label>
						<input type="file" class="form-control" multiple id="enduser_passport" name="enduser_passport"
							placeholder="Upload National ID (First & Second page)" accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="enduser_contract" class="col-form-label text-md-end">{{ __('End User Contract') }}</label>
						<input type="file" class="form-control" multiple id="enduser_contract" name="enduser_contract"
							placeholder="Upload Attested Educational Documents" accept="application/pdf, image/*">
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="vehicle_handover_person_id" class="col-form-label text-md-end">{{ __('ID For The Person To Handover The Vehicle') }}</label>
						<input type="file" class="form-control" multiple id="vehicle_handover_person_id" name="vehicle_handover_person_id"
							placeholder="Upload Attested Educational Documents" accept="application/pdf, image/*">
					</div>
				</div>
                <br>
                <div class="row preview-div" hidden>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="signed_pfi_label"></span>
                        <div id="signed_pfi_preview">
                            @if(isset($workOrder->signed_pfi))
                            <div id="signed_pfi_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Signed PFI</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Signed_PFI"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" alt="Signed PFI"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="signed_contract_label"></span>
                        <div id="signed_contract_preview">
                            @if(isset($workOrder->signed_contract))
                            <div id="signed_contract_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Signed Contract</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Signed_Contract"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" alt="Signed Contract"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="payment_receipts_label"></span>
                        <div id="payment_receipts_preview">
                            @if(isset($workOrder->payment_receipts))
                            <div id="payment_receipts_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Payment Receipts</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Payment_Receipts"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" alt="Payment Receipts"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="noc_label"></span>
                        <div id="noc_preview">
                            @if(isset($workOrder->noc))
                            <div id="noc_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">NOC</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/noc/' . $workOrder->noc) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="NOC"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/noc/' . $workOrder->noc) }}" alt="NOC"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="enduser_trade_license_label"></span>
                        <div id="enduser_trade_license_preview">
                            @if(isset($workOrder->enduser_trade_license))
                            <div id="enduser_trade_license_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Enduser Trade License</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Enduser_Trade_License"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" alt="Enduser Trade License"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="enduser_passport_label"></span>
                        <div id="enduser_passport_preview">
                            @if(isset($workOrder->enduser_passport))
                            <div id="enduser_passport_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Enduser Passport</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Enduser_Passport"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" alt="Enduser Passport"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="enduser_contract_label"></span>
                        <div id="enduser_contract_preview">
                            @if(isset($workOrder->enduser_contract))
                            <div id="enduser_contract_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Enduser Contract</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Enduser_Contract"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" alt="Enduser Contract"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12 mt-2">
                        <span class="fw-bold col-form-label text-md-end" id="vehicle_handover_person_id_label"></span>
                        <div id="vehicle_handover_person_id_preview">
                            @if(isset($workOrder->vehicle_handover_person_id))
                            <div id="vehicle_handover_person_id_preview1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12 mt-1">
                                        <h6 class="fw-bold text-center mb-1" style="float:left;">Vehicle Handover Person ID</h6>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mb-2">
                                        <button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
                                        <a href="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" download class="text-white">
                                        Download
                                        </a>
                                        </button>
                                        <button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
                                            data-file-type="Vehicle_Handover_Person_ID"> Delete</button>
                                    </div>
                                </div>
                                <iframe src="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" alt="Vehicle Handover Person ID"></iframe>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<div class="card  no-border">
			<div class="card-body">
				<div class="col-xxl-12 col-lg-12 col-md-12">
					<button style="float:left;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
				</div>
			</div>
		</div>
		<input type="hidden" id="brn-file-file-delete" name="is_brn_file_delete" value="">
		<input type="hidden" id="signed-pfi-delete" name="is_signed_pfi_delete" value="">
		<input type="hidden" id="signed-contract-delete" name="is_signed_contract_delete" value="">   
		<input type="hidden" id="payment-receipts-file-delete" name="is_payment_receipts_delete" value=""> 
		<input type="hidden" id="noc-file-delete" name="is_noc_delete" value="">
		<input type="hidden" id="enduser-trade-license-delete" name="is_enduser_trade_license_delete" value="">
		<input type="hidden" id="enduser-passport-delete" name="is_enduser_passport_delete" value="">   
		<input type="hidden" id="enduser-contract-file-delete" name="is_enduser_contract_delete" value=""> 
		<input type="hidden" id="vehicle-handover-person-id-file-delete" name="is_vehicle_handover_person_id_delete" value=""> 
	</form>
	</br>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">
				<center>Comments Section</center>
			</h4>
		</div>
		<div class="card-body">
			@if(isset($workOrder))
			@include('work_order.export_exw.comments')
			@else
			@include('work_order.export_exw.createComments')
			@endif
		</div>
	</div>
	<br>
	@if(isset($workOrder))
	<div class="card mt-3">
		<div class="card-header text-center">
			<h4 class="card-title">Data History</h4>
		</div>
		<div class="card-body">
			<div class="portfolio">
				<ul class="nav nav-pills nav-fill" id="my-tab">
					<li class="nav-item">
						<a class="nav-link form-label active" data-bs-toggle="pill" href="#wo_data_history"> WO Data History</a>
					</li>   
					<li class="nav-item">
						<a class="nav-link form-label" data-bs-toggle="pill" href="#wo_vehicle_data_history"> WO Vehicles & Addons Data History</a>
					</li>                          
				</ul>
			</div>
			</br>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="wo_data_history">
					<div class="card-header text-center">
						<center style="font-size:12px;">WO Data History</center>
					</div>
					<div class="card-body">
						@include('work_order.export_exw.data_history')
					</div>
				</div>
				<div class="tab-pane fade" id="wo_vehicle_data_history">
					<div class="card-header text-center">
						<center style="font-size:12px;">WO Vehicles & Addons Data History</center>
					</div>
					<div class="card-body">
						@include('work_order.export_exw.vehicle_data_history')
					</div>
				</div>

			</div>
		</div>
	</div>
	@endif
</div>
<br>
<div id="overlay">
	<div id="overlay-content">
		<h2>Submitting, please wait...</h2>
	</div>
</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
<script type="text/javascript">
	
	// Declare commentIdCounter only once
	let commentIdCounter = 1;
    // $('#work-order-history-table').DataTable();
    var customers = {!! json_encode($customers) !!};
	var vins = {!! json_encode($vins) !!}
	var customerCount =  $("#customerCount").val();
	var type = $("#type").val();
	var addedVins = [];
	var selectedDepositReceivedValue = '';
	var newCustomerEmail = '';
	var newCustomerContact = '';
	var newCustomerAddress = '';
	var selectedCustomerEmail = '';
	var selectedCustomerContact = '';
	var selectedCustomerAddress = '';
	var onChangeSelectedVins = [];
	@if(isset($workOrder))
        var workOrder = {!! json_encode($workOrder) !!};
    @else
        var workOrder = null;
    @endif

	const mentions = ["@Alice", "@Bob", "@Charlie"]; // Example list of mentions
	var input = document.querySelector("#customer_company_number");
	var iti = window.intlTelInput(input, { 
		separateDialCode: true,
		preferredCountries: ["ae"],
		hiddenInput: "full",
		utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
	});
	var customer_representative_contact = window.intlTelInput(document.querySelector("#customer_representative_contact"), {
		separateDialCode: true,
		preferredCountries:["ae"],
		hiddenInput: "full",
		utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
	});
	if(type == 'export_exw') {
		var freight_agent_contact_number = window.intlTelInput(document.querySelector("#freight_agent_contact_number"), {
			separateDialCode: true,
			preferredCountries:["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});
	}
	if(type == 'export_exw' || type == 'export_cnf') {
		var transporting_driver_contact_number = window.intlTelInput(document.querySelector("#transporting_driver_contact_number"), {
			separateDialCode: true,
			preferredCountries:["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});	
	}
	$(document).ready(function () { 
		document.getElementById('submit-from-top').addEventListener('click', function() { 
			  // Trigger a click on the submit button of the form
			  document.getElementById('submit').click();
		});
		// SELECT 2 START
			$('#customer_name').select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder:"Choose Customer Name",
			});


			if(workOrder == null || workOrder.deposit_received_as == null) {
				$("#amount-received-div").hide();
				$("#balance-amount-div").hide();
				$("#deposit-aganist-vehicle-div").hide();
			}
			else if(workOrder != null && workOrder.deposit_received_as == 'total_deposit') {
				$("#amount-received-div").show();
				$("#balance-amount-div").show();
				$("#deposit-aganist-vehicle-div").hide();
				selectedDepositReceivedValue = 'total_deposit';
			}
			else if(workOrder != null && workOrder.deposit_received_as == 'custom_deposit') {
				$("#amount-received-div").show();
				$("#balance-amount-div").show();
				$("#deposit-aganist-vehicle-div").show();
				selectedDepositReceivedValue = 'custom_deposit';
				setDepositAganistVehicleDropdownOptions();
			}

			if(workOrder != null && (workOrder.signed_pfi != null || workOrder.signed_contract != null || workOrder.payment_receipts != null || workOrder.noc != null ||
			workOrder.enduser_trade_license != null || workOrder.enduser_passport != null || workOrder.enduser_contract != null || workOrder.vehicle_handover_person_id != null )) {
				$('.preview-div').attr('hidden', false);
			}
			if(workOrder != null && (workOrder.brn_file != null )) {
				$('.brn-preview-div').attr('hidden', false);
			}
			if (workOrder !== null && workOrder.customer_reference_id === null && workOrder.customer_name !== null) {
				checkValue();
				$('#textInput').val(workOrder.customer_name);
			} else if (workOrder !== null && (workOrder.customer_reference_id !== null || workOrder.customer_reference_id === 0) && workOrder.customer_name !== null) {
				$("#customer_name").val(workOrder.customer_name).change();
			}
			if(workOrder == null || workOrder.transport_type == null) {
				hideDependentTransportType();
			}
			if(workOrder !== null) {
				$('#customer_address').val(workOrder.customer_address);
				$('#customer_email').val(workOrder.customer_email);

				// Check if workOrder.customer_company_number is not null or undefined
				var fullPhoneNumber = workOrder.customer_company_number ? workOrder.customer_company_number.replace(/\s+/g, '') : '';

				iti.setNumber(fullPhoneNumber);
				sanitizeNumberInput(input);

				var customer_representative_contactFull = workOrder.customer_representative_contact ? workOrder.customer_representative_contact.replace(/\s+/g, '') : '';
				customer_representative_contact.setNumber(customer_representative_contactFull);
				sanitizeNumberInput(input);

				var freight_agent_contact_numberFull = workOrder.freight_agent_contact_number ? workOrder.freight_agent_contact_number.replace(/\s+/g, '') : '';
				freight_agent_contact_number.setNumber(freight_agent_contact_numberFull);
				sanitizeNumberInput(input);

				$('#customer_representative_contact').val(workOrder.customer_representative_contact);
				$('#freight_agent_contact_number').val(workOrder.freight_agent_contact_number);
				if(workOrder.transport_type == 'air') {
					airRelation();
				}
				else if(workOrder.transport_type == 'sea') {
					seaRelation();
				}
				else if(workOrder.transport_type == 'road') {
					roadRelation();
					var transporting_driver_contact_numberFull = workOrder.transporting_driver_contact_number ? workOrder.transporting_driver_contact_number.replace(/\s+/g, '') : '';
					transporting_driver_contact_number.setNumber(transporting_driver_contact_numberFull);
					sanitizeNumberInput(input);				
				}
			}
			
			if (workOrder != null && workOrder.vehicles && workOrder.vehicles.length > 0) {
				if (workOrder != null && workOrder.vehicles && workOrder.vehicles.length == 1) {
					$("#boe-div").hide();
				}
				// Initialize an object to track VINs by BOE number
				var boeVins = {};
				var allVins = []; // Array to keep track of all VINs

				for (var i = 0; i < workOrder.vehicles.length; i++) {
					drawTableRow(workOrder.vehicles[i]);

					// Find the option and disable it in the vin_multiple dropdown
					$("#vin_multiple").find('option[value="' + workOrder.vehicles[i].vin + '"]').prop('disabled', true);

					// Refresh the select2 control
					$("#vin_multiple").trigger('change.select2');

					addedVins.push(workOrder.vehicles[i].vin);
					allVins.push(workOrder.vehicles[i].vin); // Track all VINs

					// Handle the deposit_aganist_vehicle dropdown
					var $depositSelect = $("#deposit_aganist_vehicle");
					if (workOrder.vehicles[i].deposit_received == 'yes') {
						// Add the VIN as a selected option
						var newOption = new Option(workOrder.vehicles[i].vin, workOrder.vehicles[i].vin, true, true);
						$depositSelect.append(newOption).trigger('change');
					} else {
						// Add the VIN as an unselected option
						var newOption = new Option(workOrder.vehicles[i].vin, workOrder.vehicles[i].vin, false, false);
						$depositSelect.append(newOption).trigger('change');
					}

					if (workOrder.vehicles[i].boe_number != null) {
						var boeNumber = workOrder.vehicles[i].boe_number;
						if (!boeVins[boeNumber]) {
							boeVins[boeNumber] = [];
						}
						boeVins[boeNumber].push(workOrder.vehicles[i].vin);
					}
				}
				var newBoeOption = '';
				allVins.forEach(function(vin) { 
					var newBoeOption = new Option(vin, vin, false, false);
				});
				// Add rows for each BOE number and set the corresponding VINs
				Object.keys(boeVins).forEach(function (boeNumber) {
					addChild();
					var index = $(".form_field_outer").find(".form_field_outer_row").length;
					var $boeSelect = $(`#boe_vin_${index}`);
					$boeSelect.append(newBoeOption);

					// Set the corresponding VINs for this BOE as selected
					boeVins[boeNumber].forEach(function(vin) {
						$boeSelect.find('option[value="' + vin + '"]').prop('selected', true);
					});

					// Refresh the select2 control
					$boeSelect.trigger('change');
				});

				// Disable options in each BOE select2 if they are selected in at least one dropdown
				$(".form_field_outer").find(".form_field_outer_row select").each(function() {
					var $select = $(this);
					var selectedVins = [];

					// Collect selected VINs in this select element
					$select.find('option:selected').each(function() {
						selectedVins.push($(this).val());
					});

					// Disable options that are selected in other dropdowns
					allVins.forEach(function(vin) {
						if (selectedVins.indexOf(vin) === -1) { // Don't disable the option if it's selected in this dropdown
							$select.find('option[value="' + vin + '"]').prop('disabled', true);
						}
					});
					$select.trigger('change.select2');
				});
			}
			else {
				$("#boe-div").hide();
			}
			
			$('#vin_multiple').select2({
				allowClear: true,
				placeholder:"VIN",
			});
			$('#vin').select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder:"VIN",
			});
			$('#user_id').select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder:"Select User",
			});
		// SELECT 2 END

		// INTEL INPUT START
			
			// Function to set customer relations
			function setCustomerRelations(selectedCustomerName) {
				$('#customer_address').val('');
				$('#customer_email').val('');
				$('#customer_company_number').val('');            
				if (selectedCustomerName != null && selectedCustomerName.length > 0) {
					for (var i = 0; i < customerCount; i++) {
						if (customers[i].customer_name == selectedCustomerName[0]) { 
							if (customers[i].customer_address != null) {
								$('#customer_address').val(customers[i]?.customer_address);
							}
							if (customers[i].customer_email != null) {
								$('#customer_email').val(customers[i]?.customer_email);
							}
							if (customers[i].customer_company_number != null) { 
								var fullPhoneNumber = customers[i].customer_company_number ? customers[i].customer_company_number.replace(/\s+/g, '') : '';

								// Use intlTelInput instance to set the full phone number without spaces
								iti.setNumber(fullPhoneNumber);
								// Call sanitizeNumberInput on the current input
								sanitizeNumberInput(input);
							}
						}
					}
				}
			}
			
		// INTEL INPUT END

		// TRANSPORT TYPE ONCHANGE START
			$('.transport_type').click(function() {
				if($(this).val() == 'air') {
					airRelation();
				}
				else if($(this).val() == 'sea') {
					seaRelation();
				}
				else if($(this).val() == 'road') {
					roadRelation();
				}
			});

			// TRANSPORT TYPE AIR RELATED DATA
			function airRelation() {
				$("#airline-div").show();
				$('#airline').select2({
					allowClear: true,
					maximumSelectionLength: 1,
					placeholder:"Choose Airline",
				});
				$("#airway-bill-div").show();
				$("#brn-div").hide();
				$("#brn-file-div").show();
				$("#container-number-div").hide();
				$("#trailer-number-plate-div").hide();
				$("#transportation-company-div").hide();
				$("#forward-import-code-div").hide();
				$("#shippingline-div").hide();
				$("#transporting-driver-contact-number-div").hide();
				$("#airway-details-div").show();
				$("#transportation-company-details-div").hide();
			}
			// TRANSPORT TYPE SEA RELATED DATA
			function seaRelation() {
				$("#airline-div").hide();
				$("#airway-bill-div").hide();
				$("#shippingline-div").show();
				$("#forward-import-code-div").show();
				$("#brn-div").show();
				$("#brn-file-div").show();
				$("#container-number-div").show();
				$("#trailer-number-plate-div").hide();
				$("#transportation-company-div").hide();
				$("#transporting-driver-contact-number-div").hide();
				$("#airway-details-div").hide();
				$("#transportation-company-details-div").hide();
			}
			// TRANSPORT TYPE ROAD RELATED DATA
			function roadRelation() {
				$("#airline-div").hide();
				$("#airway-bill-div").hide();
				$("#shippingline-div").hide();
				$("#forward-import-code-div").hide();
				$("#brn-div").hide();
				$("#brn-file-div").hide();
				$("#container-number-div").hide();
				$("#trailer-number-plate-div").show();
				$("#transportation-company-div").show();
				$("#transporting-driver-contact-number-div").show();
				$("#airway-details-div").hide();
				$("#transportation-company-details-div").show();
			}
		// TRANSPORT TYPE ONCHANGE END

		// CUSTOMER NAME ONCHANGE START
			 // Handle customer name change
			 $('#customer_name').on('change', function() { 
				var selectedCustomerName = $(this).val(); 
				setCustomerRelations(selectedCustomerName);
			});
		// CUSTOMER NAME ONCHANGE END

		// DEPOSIT RECEIVED AS ONCHANGE START
			$('.deposit_received_as').click(function() { 
				selectedDepositReceivedValue = $('input[name="deposit_received_as"]:checked').val();
				if (selectedDepositReceivedValue == 'total_deposit') {
					$("#amount-received-div").show();
					$("#balance-amount-div").show();
					$("#deposit-aganist-vehicle-div").hide();
				} else if (selectedDepositReceivedValue == 'custom_deposit') {
					$("#amount-received-div").show();
					$("#balance-amount-div").show();
					$("#deposit-aganist-vehicle-div").show();
					setDepositAganistVehicleDropdownOptions();
				}
			});
		// DEPOSIT RECEIVED AS ONCHANGE END

		// BOE DYNAMICALLY ADD AND REMOVE START
			// Event listener to add new form fields
			$("body").on("click", ".add_new_frm_field_btn", function () {
				addChild();
			});
			$("body").on("click", ".add-addon-btn", function () {
				if (validateVINSelection()) {
					addAddon();
				}
			});
			$("body").on("click", ".add-vehicle-btn", function () {
				if (validateVINSelection() && validateAddonSelection()) {
					addVIN();
				}
			});
			function validateVINSelection() {
				var selectedVIN = $("#vin_multiple").val();
				if (!selectedVIN || selectedVIN.length === 0) {
					alert("Please select at least one VIN.");
					return false;
				}
				return true;
			}
			function validateAddonSelection() {
				var isValid = true;
				$(".addondynamicselect2").each(function () {
					if ($(this).val() === null || $(this).val().length === 0) {
						isValid = false;
						alert("Please select addon.");
						return false; // Break out of the loop
					}
				});
				return isValid;
			}
			// Event listener to remove form fields and reset indexes
			$("body").on("click", ".remove_node_btn_frm_field", function () {
				var row = $(this).closest(".form_field_outer_row");
				var selectElement = row.find('.dynamicselect2');

				// Destroy Select2 instance before removing the row
				if (selectElement.data('select2')) {
					selectElement.select2('destroy');
				}

				// Enable the VIN options before removing the row
				var selectedVINs = selectElement.val(); 
				if (selectedVINs) {
					selectedVINs.forEach(function(vin) {
						$('select option[value="' + vin + '"]').prop('disabled', false);
					});
				}

				row.remove();
				resetIndexes();
			});
			
			// Event delegation for dynamically added elements
			$(document).on('click', '.remove_node_btn_frm_field_addon', function () {
				$(this).closest('.addon_input_outer_row').remove();
				disableAddonSelectedOptions(); // Re-check disabled options after removing a row
				resetRowIndexes();
			});
			// Event listener to handle change event for .dynamicselect2
			$("body").on("change", ".dynamicselect2", function () {
				disableSelectedOptions();
			});
			// Add change event listener for dynamically added .addondynamicselect2 elements
			$(document).on('change', '.addondynamicselect2', function() {
				disableAddonSelectedOptions();
			});
			
		// BOE DYNAMICALLY ADD AND REMOVE END


		// ON CHANGE OF VIN FETCH ITS RELATED ADDONS START
			$('#vin_multiple').on('change', function() {
				onChangeSelectedVins = $(this).val(); // Get selected VINs
				var index = $(".addon_outer").find(".addon_input_outer_row").length + 1;
				if (onChangeSelectedVins && onChangeSelectedVins.length > 0) {
					// if(index == 1) {
					// 	addAddon();
					// }
					// else {
						// resetAddonDropdown();
					// }
					
				} else {
					// Clear addons dropdown if no VINs are selected
					$('#addons').empty().trigger('change');
				}
			});
		// ON CHANGE OF VIN FETCH ITS RELATED ADDONS END

        // SHOW FILE UPLOAD DATA START
            const fileInputBRNFile = document.querySelector("#brn_file");            
			const fileInputSignedPFI = document.querySelector("#signed_pfi");
			const fileInputSignedContract = document.querySelector("#signed_contract");            
			const fileInputPaymentReceipts = document.querySelector("#payment_receipts");
			const fileInputNOC = document.querySelector("#noc");
			const fileInputEnduserTradeLicense = document.querySelector("#enduser_trade_license");
			const fileInputEnduserPassport = document.querySelector("#enduser_passport");
			const fileInputEnduserContract = document.querySelector("#enduser_contract");
            const fileInputVehicleHandoverPersonID = document.querySelector("#vehicle_handover_person_id");

            const previewFileBRNFile = document.querySelector("#brn_file_preview");
			const previewFileSignedPFI = document.querySelector("#signed_pfi_preview");
			const previewFileSignedContract = document.querySelector("#signed_contract_preview");
			const previewFilePaymentReceipts = document.querySelector("#payment_receipts_preview");
			const previewFileNOC = document.querySelector("#noc_preview");
			const previewFileEnduserTradeLicense = document.querySelector("#enduser_trade_license_preview");
			const previewFileEnduserPassport = document.querySelector("#enduser_passport_preview");
			const previewFileEnduserContract = document.querySelector("#enduser_contract_preview");
            const previewFileVehicleHandoverPersonID = document.querySelector("#vehicle_handover_person_id_preview");

			if(type =='export_exw' || type == 'export_cnf') {
				fileInputBRNFile.addEventListener("change", function(event) { 
					$('.brn-preview-div').attr('hidden', false);
					const files = event.target.files;
					while (previewFileBRNFile.firstChild) {
						previewFileBRNFile.removeChild(previewFileBRNFile.firstChild);
					}
					const file = files[0];
					document.getElementById('brn_file_label').textContent="BRN File";
					const objectUrl = URL.createObjectURL(file);
					const iframe = document.createElement("iframe");
					iframe.src = objectUrl;
					previewFileBRNFile.appendChild(iframe);
				});
			}
           
            fileInputSignedPFI.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileSignedPFI.firstChild) {
			        previewFileSignedPFI.removeChild(previewFileSignedPFI.firstChild);
			    }
			    const file = files[0];
                document.getElementById('signed_pfi_label').textContent="Signed PFI";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileSignedPFI.appendChild(iframe);
			});

            fileInputSignedContract.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileSignedContract.firstChild) {
			        previewFileSignedContract.removeChild(previewFileSignedContract.firstChild);
			    }
			    const file = files[0];
                document.getElementById('signed_contract_label').textContent="Signed Contract";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileSignedContract.appendChild(iframe);
			});

            fileInputPaymentReceipts.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFilePaymentReceipts.firstChild) {
			        previewFilePaymentReceipts.removeChild(previewFilePaymentReceipts.firstChild);
			    }
			    const file = files[0];
                document.getElementById('payment_receipts_label').textContent="Payment Receipts";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFilePaymentReceipts.appendChild(iframe);
			});

            fileInputNOC.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileNOC.firstChild) {
			        previewFileNOC.removeChild(previewFileNOC.firstChild);
			    }
			    const file = files[0];
                document.getElementById('noc_label').textContent="NOC";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileNOC.appendChild(iframe);
			});

            fileInputEnduserTradeLicense.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileEnduserTradeLicense.firstChild) {
			        previewFileEnduserTradeLicense.removeChild(previewFileEnduserTradeLicense.firstChild);
			    }
			    const file = files[0];
                document.getElementById('enduser_trade_license_label').textContent="Enduser Trade License";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileEnduserTradeLicense.appendChild(iframe);
			});

            fileInputEnduserPassport.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileEnduserPassport.firstChild) {
			        previewFileEnduserPassport.removeChild(previewFileEnduserPassport.firstChild);
			    }
			    const file = files[0];
                document.getElementById('enduser_passport_label').textContent="Enduser Passport";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileEnduserPassport.appendChild(iframe);
			});

            fileInputEnduserContract.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileEnduserContract.firstChild) {
			        previewFileEnduserContract.removeChild(previewFileEnduserContract.firstChild);
			    }
			    const file = files[0];
                document.getElementById('enduser_contract_label').textContent="Enduser Contract";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileEnduserContract.appendChild(iframe);
			});

            fileInputVehicleHandoverPersonID.addEventListener("change", function(event) {
			    $('.preview-div').attr('hidden', false);
			    const files = event.target.files;
			    while (previewFileVehicleHandoverPersonID.firstChild) {
			        previewFileVehicleHandoverPersonID.removeChild(previewFileVehicleHandoverPersonID.firstChild);
			    }
			    const file = files[0];
                document.getElementById('vehicle_handover_person_id_label').textContent="Vehicle Handover Person ID";
                const objectUrl = URL.createObjectURL(file);
                const iframe = document.createElement("iframe");
                iframe.src = objectUrl;
                previewFileVehicleHandoverPersonID.appendChild(iframe);
			});
        // SHOW FILE UPLOAD DATA END

		$(document.body).on('select2:select', ".dynamicselectaddon", function (e) {
            var dataId = $(this).attr('data-parant');
			vehicleAddonDropdown(dataId)
        });
		$(document.body).on('select2:unselect', ".dynamicselectaddon", function (e) {
            var dataId = $(this).attr('data-parant');
			vehicleAddonDropdown(dataId)
        });

		
		if(workOrder != null && workOrder.sales_support_data_confirmation_at != null) {
			// Select all input, select, and textarea elements and disable them
			var elements = document.querySelectorAll('#WOForm #submit');
			// #WOForm input, #WOForm select, #WOForm textarea, 
			elements.forEach(function(element) {
				element.disabled = true;
			});
		}
	});
	// ADD CUSTOM VALIDATION RULES START
        // Add custom validation rule for email
        $.validator.addMethod("customEmail", function(value, element) {
            return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(value);
        }, "Please enter a valid email address");
        // Custom method to validate SO number format
		$.validator.addMethod("SONumberFormat", function(value, element) {
			// Regular expression to match the format SO- followed by exactly 6 digits
			return this.optional(element) || /^SO-\d{6}$/.test(value);
		}, "Please enter a valid order number in the format SO-######");

        // Custom method to ensure SO number is not SO-000000
		$.validator.addMethod("notSO000000", function(value, element) {
			return this.optional(element) || value !== "SO-000000";
		}, "SO Number cannot be SO-000000");

        // Custom method to ensure no leading or trailing spaces
		$.validator.addMethod("noSpaces", function(value, element) {
			return this.optional(element) || /^[^\s]+(\s+[^\s]+)*$/.test(value);
		}, "No leading or trailing spaces allowed");

        // Add custom validation rule for numeric input only (excluding spaces)
        $.validator.addMethod("numericOnly", function(value, element) {
            return this.optional(element) || /^[0-9+]+$/.test(value);
        }, "Please enter a valid number");

        // Add custom validation rule for address field (no multiple consecutive spaces)
        $.validator.addMethod("validAddress", function(value, element) {
            return this.optional(element) || !/\s\s+/.test(value);
        }, "No more than one consecutive space is allowed in the address");
		// Custom method to check if the SO number is unique
		$.validator.addMethod("uniqueSO", function(value, element) {
			var result = false;
			var WoId = $("#wo_id").val(); 
			$.ajax({
				type: "POST",
				async: false,
				url: "{{route('work-order.uniqueSO')}}", // script to validate in server side
				data: {_token: '{{csrf_token()}}', so_number: value, id: WoId},
				success: function(data) {
					result = (data == true) ? true : false;
				}
			});
			return result; 
		}, "This SO Number is already taken! Try another.");

		// Custom method to validate at least one vehicle is selected when deposit_received_as is custom_deposit
		$.validator.addMethod("customDepositVehicleRequired", function(value, element) {
			if (selectedDepositReceivedValue === 'custom_deposit' && addedVins.length > 0) {
				return $('select[name="deposit_aganist_vehicle[]"]').val().length > 0;
			}
			return true;
		}, "At least one vehicle must be selected if deposit is received as custom deposit");

		$.validator.addMethod("allVinsSelected", function(value, element) {
			// Get all selected VINs across all dynamicselect2 elements
			let selectedVins = new Set();
			$('.dynamicselect2').each(function() {
				$(this).val().forEach(vin => selectedVins.add(vin));
			});

			// Check if each VIN in addedVins is in the selectedVins set
			for (let vin of addedVins) {
				if (!selectedVins.has(vin)) {
					return false;
				}
			}

			return true;
		}, "All work order vehicles should be selected under one BOE per VIN field.");
		// Add a custom validator method for checking if the value is a year with 4 digits
		$.validator.addMethod("year4digits", function(value, element) {
			return this.optional(element) || /^\d{4}$/.test(value);
		}, "Please enter a valid year with 4 digits.");
    // ADD CUSTOM VALIDATION RULE END

	// CLIENT SIDE VALIDATION START
	
        $('#WOForm').validate({ // initialize the plugin 
            rules: {
                type: {
                    required: true,
                },
                so_number: {
					required: true,
					noSpaces: true,
					SONumberFormat: true,
					notSO000000: true,
					uniqueSO: true,
				},
                batch: {
                    required: true,
                },
                // customer_reference_id: {

                // },
                // customer_reference_type: {

                // },
                new_customer_name: {
                    noSpaces: true,
                },
                // existing_customer_name: {

                // }
                customer_email: {
                    noSpaces: true,
                    customEmail: true,
                },
                "customer_company_number[main]": {
                    numericOnly: true,
                    minlength: 5,
                    maxlength: 20,
                },
                customer_address: {
					noSpaces: true,
                    validAddress: true,
                    maxlength: 255
                },
                customer_representative_name: {
                    noSpaces: true,
                },
                customer_representative_email: {
                    noSpaces: true,
                    customEmail: true,
                },
                "customer_representative_contact[main]": {
                    numericOnly: true,
                    minlength: 5,
                    maxlength: 20,
                },
                freight_agent_name: {
                    noSpaces: true,
                },
                freight_agent_email: {
                    noSpaces: true,
                    customEmail: true,
                },
                "freight_agent_contact_number[main]": {
                    numericOnly: true,
                    minlength: 5,
                    maxlength: 20,
                },
                port_of_loading: {
                    required: true,
					noSpaces: true,
                },
                port_of_discharge: {
                    required: true,
                    noSpaces: true,
                },
                final_destination: {
                    required: true,
                    noSpaces: true,
                },
                // transport_type: {
                // },
                brn_file: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                brn: {
                    noSpaces: true,
                },
                container_number: {
                    noSpaces: true,
                },
                // airline_reference_id: {
                // }
                // airline: {
                // },
                airway_bill: {
                    noSpaces: true,
                },
                shipping_line: {
                    noSpaces: true,
                },
                forward_import_code: {
                    noSpaces: true,
                },
                trailer_number_plate: {
                    noSpaces: true,
                },
                transportation_company: {
                    noSpaces: true,
                },
                "transporting_driver_contact_number[main]": {
                    numericOnly: true,
                    minlength: 5,
                    maxlength: 20,
                },
                airway_details: {
                    noSpaces: true,
                },
                transportation_company_details: {
                    noSpaces: true,
                },
                // currency: {
                // },
                so_total_amount: {
					noSpaces: true,
					number: true,
					min: 0
                },
                so_vehicle_quantity: {
					digits: true,
					min: 1 
                },
				"deposit_aganist_vehicle[]": {
					customDepositVehicleRequired: true
				},
                // amount_received: {
                // },
                // balance_amount: {
                // },				
                delivery_location: {
                    noSpaces: true,
                },
                delivery_contact_person: {
                    noSpaces: true,
                },
                delivery_date: {
                    date: true,
                },
                signed_pfi: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                signed_contract: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                payment_receipts: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                noc: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                enduser_trade_license: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                enduser_passport: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                enduser_contract: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                vehicle_handover_person_id: {
                    extension: "jpg|jpeg|png|gif|tiff|psd|pdf|eps|ai|indd|raw|docx|rtf|doc",
                    maxsize : 1073741824,
                },
                // DYNAMIC FIELDS
                // vin: {
                // 	// required: true,
                // },
                // brand: {
                // 	// required: true,
                // },
                // variant: {
                // 	// required: true,
                // },
                // engine: {
                // 	// required: true,
                // },
                // model_description: {
                // 	// required: true,
                // },
                // model_year: {
                // 	// required: true,
                // },
                // model_year: {
                // 	// required: true,
                // },
                // steering: {
                // 	// required: true,
                // },
                // exterior_colour: {
                // 	// required: true,
                // },
                // interior_colour: {
                // 	// required: true,
                // },
                // warehouse: {
                // 	// required: true,
                // },
                // territory: {
                // 	// required: true,
                // },
                // preferred_destination: {
                // 	// required: true,
                // },
                // import_document_type: {
                // 	// required: true,
                // },
                // ownership_name: {
                // 	// required: true,
                // },
                // modification_or_jobs_to_perform_per_vin: {
                // 	// required: true,
                // },
                // certification_per_vin: {
                // 	// required: true,
                // },
                // special_request_or_remarks: {
                // 	// required: true,
                // },
            },
            messages: {
                brn_file:{
                    filesize:" file size must be less than 1 GB.",
                },
                signed_pfi:{
                    filesize:" file size must be less than 1 GB.",
                },
                signed_contract:{
                    filesize:" file size must be less than 1 GB.",
                },
                payment_receipts:{
                    filesize:" file size must be less than 1 GB.",
                },
                noc:{
                    filesize:" file size must be less than 1 GB.",
                },
                enduser_trade_license:{
                    filesize:" file size must be less than 1 GB.",
                },
                enduser_passport:{
                    filesize:" file size must be less than 1 GB.",
                },
                enduser_contract:{
                    filesize:" file size must be less than 1 GB.",
                },
                vehicle_handover_person_id: {
                    filesize:" file size must be less than 1 GB.",
                },
            },
			errorPlacement: function(error, element) {
				error.appendTo(element.parent()); 
				element.addClass('is-invalid');
				if ( element.prop( "type" ) === "tel" && element.closest('.select-button-main-div').length > 0 ) {
					if (!element.val() || element.val().length === 0 || element.val().length > 0) {
						console.log("Error is here with length", element.val().length);
						error.addClass('select-error');
						error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
					} else {
						console.log("No error");
					}
				}
			},
			highlight: function (element, errorClass, validClass) {
				$(element).addClass(errorClass).removeClass(validClass);
				$(element).next('p.invalid-feedback').show();
			},
			unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass(errorClass).addClass(validClass);
				$(element).next('p.invalid-feedback').hide();
				if (!$(element).hasClass(errorClass)) {
					$(element).removeClass('is-invalid');
				}
			},
			submitHandler: function(form) {  
				// Prevent default form submission
				event.preventDefault();
				// Show the overlay
				$('#overlay').show();
				// Collect all comments
				const comments = [];
				if (workOrder == null) {
					const commentElements = document.querySelectorAll('#comments-section .comment');
					
					if (commentElements.length > 0) {
						commentElements.forEach(comment => {
							const commentId = comment.getAttribute('data-comment-id');
							const parentId = comment.getAttribute('data-parent-id');
							const dateTime = comment.getAttribute('data-date-time');
							const textElement = comment.querySelector('.comment-text');
							const fileElements = comment.querySelectorAll(`.file-preview[data-comment-id="${commentId}"] img`);
							const files = Array.from(fileElements).map(file => ({
								src: file.src,
								name: file.alt
							}));

							if (textElement) {
								const text = textElement.textContent.trim();
								comments.push({ commentId, parentId, text, dateTime, files });
							} else {
								console.warn('Text element is missing for a comment:', comment);
							}
						});
					} else {
						console.warn('No comments found in #comments-section.');
					}
				}

				// Update phone numbers if elements are defined
				if (typeof iti !== 'undefined') {
					$('#customer_company_number_full').val(iti.getNumber());
				}
				if (typeof customer_representative_contact !== 'undefined') {
					$('#customer_representative_contact_full').val(customer_representative_contact.getNumber());
				}
				if (typeof freight_agent_contact_number !== 'undefined') {
					$('#freight_agent_contact_number_full').val(freight_agent_contact_number.getNumber());
				}
				if (typeof transporting_driver_contact_number !== 'undefined') {
					$('#transporting_driver_contact_number_full').val(transporting_driver_contact_number.getNumber());
				}

				// Append comments to form data
				const formData = new FormData(form);
				formData.append('comments', JSON.stringify(comments));

				// Send form data via AJAX
				fetch(form.action, {
					method: form.method,
					body: formData,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					}
				}).then(response => {
					if (!response.ok) {
						return response.text().then(text => { throw new Error(text) });
					}
					return response.json();
				}).then(data => {
					if (data.success) {
						window.location.href = `{{ url('work-order-info') }}/${type}`;
					} else {
						throw new Error(data.message);
					}
				}).catch(error => {
					console.error('Form submission error:', error);
				}).finally(() => {
					// Hide the overlay
					$('#overlay').hide();
				});
			}
        });
    // CLIENT SIDE VALIDATION END
	function sanitizeQuantity(input) {
		let value = input.value;
		// Remove non-numeric characters and ensure it's a positive integer
		value = value.replace(/[^0-9]/g, '');
		input.value = value;
	}
	function sanitizeAmount(input) {
		let value = input.value;
		// Remove non-numeric characters except for dots
		value = value.replace(/[^0-9.]/g, '');

		// Remove multiple dots
		const parts = value.split('.');
		if (parts.length > 2) {
			value = parts[0] + '.' + parts.slice(1).join('');
		}

		input.value = value;
		setDepositBalance();
	}

	function setDepositBalance() {
		var totalAmount = $('#so_total_amount').val();
		var amountReceived = $('#amount_received').val();
		var balanceAmount = '';
		if(totalAmount != '' && amountReceived != '') {
			balanceAmount = Number(totalAmount) - Number(amountReceived);
		}
		document.getElementById('balance_amount').value = balanceAmount;
	}
		
  

	// ADDON DYNAMICALLY ADD AND REMOVE START
		// Function to reset row indexes
		function resetRowIndexes() {
			$(".addon_outer").find(".addon_input_outer_row").each(function (index, element) {
				var newIndex = index + 1;
				$(element).attr('id', `addon_row_${newIndex}`);
				$(element).find('select')
					.attr('id', `addons_${newIndex}`)
					.data('index', newIndex);
				$(element).find('input[type="number"]').attr('id', `addon_quantity_${newIndex}`);
				$(element).find('textarea').attr('id', `addon_description_${newIndex}`);

				// Reinitialize Select2 for the updated elements
				$(`#addons_${newIndex}`).select2({
					allowClear: true,
					maximumSelectionLength: 1,
					placeholder: "Choose Addon",
				});
			});
			disableAddonSelectedOptions();
		}

		function resetAddonDropdown() {
			return $.ajax({
				url: '{{ route('fetch-addons') }}',
				type: 'POST',
				data: {
					vins: onChangeSelectedVins,
					_token: '{{ csrf_token() }}'
				},
				dataType: 'json',
				success: function(response) {
					// Iterate over each dynamicselect2 element to update its options
					$('.addondynamicselect2').each(function() { 
						var $dropdown = $(this);
						var currentVal = $dropdown.val(); // Store current selected values

						// Clear current options in addons dropdown
						$dropdown.empty();

						// Populate the addons dropdown with new options
						if (response.charges && response.charges.length > 0) {
							$("#addon-dynamic-div").show();
							$.each(response.charges, function(index, charge) {
								$dropdown.append(
									$('<option></option>').val(charge.addon_code + " - " + charge.addon_name).text(charge.addon_code + " - " + charge.addon_name)
								);
							});
						}
						if (response.addons && response.addons.length > 0) {
							$("#addon-dynamic-div").show();
							$.each(response.addons, function(index, addon) {
								$dropdown.append(
									$('<option></option>').val(addon.addon_code + " - " + addon.addon_name).text(addon.addon_code + " - " + addon.addon_name)
								);
							});
						}

						// Re-initialize Select2 with options
						$dropdown.select2({
							allowClear: true,
							maximumSelectionLength: 1,
							placeholder: "Choose Addon",
						});

						// Re-set the previously selected values
						$dropdown.val(currentVal).trigger('change');
					});
				},
				error: function(xhr, status, error) {
					console.error("Error fetching add-ons:", error);
				}
			});
		}
		function addAddon() {
			var index = $(".addon_outer").find(".addon_input_outer_row").length + 1;
			var newRow = $(`
				<div class="row addon_input_outer_row" id="addon_row_${index}">
					<div class="row">
						<div class="col-xxl-2 col-lg-2 col-md-2">
							<div class="row">
								<div class="col-xxl-12 col-lg-12 col-md-12">
									<label class="col-form-label text-md-end">Addon :</label>
									<select name="addons[]" id="addons_${index}" class="form-control widthinput addondynamicselect2" data-index="${index}" multiple="true">
										<!-- Add-on options will be dynamically populated -->
										@foreach($addons as $addon)
										<option value="{{$addon->addon_code}} - {{$addon->addon_name}}">{{$addon->addon_code}} - {{$addon->addon_name}}</option>
										@endforeach
										@foreach($charges as $charge)
										<option value="{{$charge->addon_code}} - {{$charge->addon_name}}">{{$charge->addon_code}} - {{$charge->addon_name}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-xxl-12 col-lg-12 col-md-12">
									<label class="col-form-label text-md-end">Quantity :</label>
									<input type="number" name="addon_quantity[]" id="addon_quantity_${index}" class="form-control widthinput" placeholder="Enter Quantity">
								</div>
							</div>
						</div>
						<div class="col-xxl-9 col-lg-9 col-md-9">
							<label class="col-form-label text-md-end">Addon Description :</label>
							<textarea name="addon_description[]" id="addon_description_${index}" rows="4" class="form-control" placeholder="Enter Addon Description"></textarea>
						</div>
						<div class="col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer_addon">
							<a class="btn_round_big remove_node_btn_frm_field_addon" title="Remove Row" style="margin-top:50%;">
								<i class="fas fa-trash-alt"></i>
							</a>
						</div>
					</div>
				</div>
			`);

			// Append the new row to the container
			$(".addon_outer").append(newRow);

			// Initialize Select2 only on the newly added element
			$(`#addons_${index}`).select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder: "Choose Addon",
			});

			// Call to update disabled options
			disableAddonSelectedOptions();
		}

	// ADDON DYNAMICALLY ADD AND REMOVE END

	// BOE DYNAMICALLY ADD AND REMOVE START
		function addChild() {
			var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;

			// Check if there are any available options
			if (getAvailableOptions().length > 0) {
				if (index <= addedVins.length) {
					var options = addedVins.map(vin => `<option value="${vin}">${vin}</option>`).join('');
					var newRow = $(`
						<div class="row form_field_outer_row" id="${index}">
							<div class="col-xxl-11 col-lg-11 col-md-11">
								<span class="error">* </span>
								<label for="boe_vin_${index}" class="col-form-label text-md-end">VIN per BOE: ${index}</label>
								<select name="boe[${index}][vin][]" id="boe_vin_${index}" class="form-control widthinput dynamicselect2" data-index="${index}" multiple="true">
									${options}
								</select>
							</div>
							<div class="col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer">
								<a class="btn_round_big remove_node_btn_frm_field" title="Remove Row">
									<i class="fas fa-trash-alt"></i>
								</a>
							</div>
						</div>
					`);

					// Append the new row to the container
					$(".form_field_outer").append(newRow);

					// Initialize Select2 only on the newly added element
					$(`#boe_vin_${index}`).select2({
						allowClear: true,
						placeholder: "Choose VIN Per BOE",
					});

					// Add validation rules for all dynamicselect2 elements
					$('.dynamicselect2').each(function() {
						$(this).rules('add', {
							required: true,
							allVinsSelected: true,
							messages: {
								required: "This field is required."
							}
						});
					});

					disableSelectedOptions();
				} else {
					alert("Sorry! You cannot create a number of BOE which is more than the number of VIN.");
				}
			} else {
				alert("Sorry! No available options to select.");
			}
		}
		function getAvailableOptions() {
			var selectedOptions = [];
			$(".dynamicselect2").each(function() {
				$(this).find('option:selected').each(function() {
					selectedOptions.push($(this).val());
				});
			});

			return addedVins.filter(vin => !selectedOptions.includes(vin));
		}
		function resetIndexes() {
			// Loop through each .form_field_outer_row and reset the index
			$(".form_field_outer").find(".form_field_outer_row").each(function(index, element) {
				var newIndex = index + 1; // Index starts from 0, so add 1 to start from 1
				$(element).attr('id', newIndex);

				// Update the label text, IDs, and names
				$(element).find('label').attr('for', `boe_vin_${newIndex}`).text(`VIN per BOE: ${newIndex}`);
				$(element).find('select')
					.attr('id', `boe_vin_${newIndex}`)
					.attr('name', `boe[${newIndex}][vin][]`)
					.data('index', newIndex);

				// Reinitialize Select2
				$(`#boe_vin_${newIndex}`).select2({
					allowClear: true,
					placeholder: "Choose VIN Per BOE",
				});
			});

			disableAddonSelectedOptions();
		}
		function disableAddonSelectedOptions() {
			// Get all selected options
			var selectedOptions = [];
			$(".addondynamicselect2").each(function () {
				$(this).find('option:selected').each(function () {
					selectedOptions.push($(this).val());
				});
			});

			// Disable the selected options in all .addondynamicselect2 elements
			$(".addondynamicselect2").each(function () {
				var $select = $(this);
				$select.find('option').each(function () {
					if (selectedOptions.includes($(this).val())) {
						if (!$(this).is(':selected')) {
							$(this).prop('disabled', true);
						}
					} else {
						$(this).prop('disabled', false);
					}
				});
			});
		}

		function disableSelectedOptions() { 
			// Get all selected options
			var selectedOptions = [];
			$(".dynamicselect2").each(function() {
				$(this).find('option:selected').each(function() {
					selectedOptions.push($(this).val());
				});
			});

			// Disable the selected options in all .dynamicselect2 elements
			$(".dynamicselect2").each(function() {
				var $select = $(this);
				$select.find('option').each(function() {
					if (selectedOptions.includes($(this).val())) {
						if (!$(this).is(':selected')) {
							$(this).prop('disabled', true);
						}
					} else {
						$(this).prop('disabled', false);
					}
				});

				// Refresh Select2 to apply changes
				// $select.select2(); // COMMENTED BECAUSE OF UNSELECT OF  VIN PER BOE CAUSE ERROR IN CONSOLE
			});
		}
	// BOE DYNAMICALLY ADD AND REMOVE END

	// ADD AND REMOVE VEHICLE TO WO START
		function addVIN() {
			var selectedVIN = $("#vin_multiple").val();
			if (selectedVIN != '' && selectedVIN.length > 0) {
				for (var j = 0; j < selectedVIN.length; j++) {
					for (var i = 0; i < vins.length; i++) {
						if (vins[i].vin != null && vins[i].vin == selectedVIN[j]) {
							var data = { 
								id: '',
								vehicle_id : vins[i]?.id ?? '',
								vin: vins[i].vin ?? '',
								brand : vins[i]?.variant?.master_model_lines?.brand?.brand_name ?? '',
								variant: vins[i]?.variant?.name ?? '',
								engine : vins[i]?.engine ?? '',
								model_description: vins[i]?.variant?.model_detail ?? '',
								model_year : vins[i]?.variant?.my ?? '',
								model_year_to_mention_on_documents: vins[i]?.variant?.my ?? '',
								steering : vins[i]?.variant?.steering ?? '',
								exterior_colour: vins[i]?.exterior?.name ?? '',
								interior_colour : vins[i]?.interior?.name ?? '',
								warehouse: vins[i]?.warehouse_location?.name ?? '',
								territory : vins[i]?.territory ?? '',
								preferred_destination: '',
								import_document_type : vins[i]?.document?.import_type ?? '',
								ownership_name: vins[i]?.document?.ownership ?? '',
								certification_per_vin: '',
								modification_or_jobs_to_perform_per_vin: '',
								special_request_or_remarks: '',
								shipment: '',
							};
							drawTableRow(data);
						}
					}
				}
				var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; 
				if(index > 0) {
					// Append selectedVIN data as dropdown option for all dynamicselect2 class
					$(".dynamicselect2").each(function() { 
						var selectElement = $(this);  
						selectedVIN.forEach(function(vin) {
							if (selectElement.find(`option[value='${vin}']`).length === 0) {
								selectElement.append(`<option value="${vin}">${vin}</option>`);
							}
						});
					});
				}
			}
			$('#vin_multiple').val(null).trigger('change');
			$('#vin_multiple option').each(function() {
				if (selectedVIN.includes($(this).val())) {
					$(this).prop('disabled', true);
				}
			});
			findAllVINs();
			$('.addon_input_outer_row').each(function() {
				$(this).remove();
			});
		}
		function drawTableRow(data) {
			// Get the table body element by ID
			var tableBody = document.querySelector('#myTable tbody');

			var firstRow = document.createElement('tr');
			firstRow.style.borderTop = '2px solid #a6a6a6';
			firstRow.className = 'first-row';
			var secondRow = document.createElement('tr');
			var thirdRow = document.createElement('tr');
			var lastRow = document.createElement('tr');

			// First Row Elements
			var removeIconCell = createCellWithRemoveButton();

			var vinCell = document.createElement('td');
			vinCell.innerHTML = '<input type="hidden" name="vehicle[' +data.vehicle_id+ '][id]" value="' + (data.id) + '">'
				+'<input type="hidden" name="vehicle[' +data.vehicle_id+ '][vehicle_id]" value="' + (data.vehicle_id) + '">'
				+ '<input type="hidden" name="vehicle[' +data.vehicle_id+ '][vin]" value="' + (data.vin) + '">'
				+ (data.vin);
			vinCell.dataset.vin = data.vin; // Correctly setting the data-vin attribute

			var brandCell = createEditableCell(data.brand, 'Enter Brand','vehicle['+data.vehicle_id+'][brand]');
			var variantCell = createEditableCell(data.variant, 'Enter Variant','vehicle['+data.vehicle_id+'][variant]');
			var engineCell = createEditableCell(data.engine, 'Enter Engine','vehicle['+data.vehicle_id+'][engine]');
			var modelDescriptionCell = createEditableCell(data.model_description, 'Enter Model Description','vehicle['+data.vehicle_id+'][model_description]');
			var modelYearCell = createEditableCell(data.model_year, 'Enter Model Year','vehicle['+data.vehicle_id+'][model_year]');
			var modelYearToMentionOnDocumentsCell = createEditableCell(data.model_year_to_mention_on_documents, 'Enter Model Year to mention on Documents','vehicle['+data.vehicle_id+'][model_year_to_mention_on_documents]');
			var steeringCell = createEditableCell(data.steering, 'Enter Steering','vehicle['+data.vehicle_id+'][steering]');
			var exteriorCell = createEditableCell(data.exterior_colour, 'Enter Exterior Colour','vehicle['+data.vehicle_id+'][exterior_colour]');
			var interiorColorCell = createEditableCell(data.interior_colour, 'Enter Interior Colour','vehicle['+data.vehicle_id+'][interior_colour]');
			var warehouseCell = createEditableCell(data.warehouse, 'Enter Warehouse','vehicle['+data.vehicle_id+'][warehouse]');
			var territoryCell = createEditableCell(data.territory, 'Enter Territory','vehicle['+data.vehicle_id+'][territory]');
			var preferredDestinationCell = createEditableCell(data.preferred_destination, 'Enter Preferred Destination','vehicle['+data.vehicle_id+'][preferred_destination]');
			var importTypeCell = createEditableCell(data.import_document_type, 'Enter Import Document Type','vehicle['+data.vehicle_id+'][import_document_type]');
			var ownershipCell = createEditableCell(data.ownership_name, 'Enter Ownership','vehicle['+data.vehicle_id+'][ownership_name]');
			var CertificationPerVINCell = createEditableSelect2Cell(data.vin,data.vehicle_id,data.certification_per_vin);
			if(type == 'export_cnf') {
				var shipmentCell = createEditableCell(data.shipment, 'Enter Shipment','vehicle['+data.vehicle_id+'][shipment]');
			}

			// Append cells to the first row
			firstRow.appendChild(removeIconCell);
			firstRow.appendChild(vinCell);
			firstRow.appendChild(brandCell);
			firstRow.appendChild(variantCell);
			firstRow.appendChild(engineCell);
			firstRow.appendChild(modelDescriptionCell);
			firstRow.appendChild(modelYearCell);
			firstRow.appendChild(modelYearToMentionOnDocumentsCell);
			firstRow.appendChild(steeringCell);
			firstRow.appendChild(exteriorCell);
			firstRow.appendChild(interiorColorCell);
			firstRow.appendChild(warehouseCell);
			firstRow.appendChild(territoryCell);
			firstRow.appendChild(preferredDestinationCell);
			firstRow.appendChild(importTypeCell);
			firstRow.appendChild(ownershipCell);
			firstRow.appendChild(CertificationPerVINCell);
			if(type == 'export_cnf') {
				firstRow.appendChild(shipmentCell);
			}
			// firstRow.style.borderTop = '1px solid #b3b3b3';

			// Second Row Elements
			var emptyLabelCell = document.createElement('td');
			emptyLabelCell.colSpan = 1;
			emptyLabelCell.textContent = '';

			var modificationLabelCell = document.createElement('td');
			modificationLabelCell.colSpan = 1;
			modificationLabelCell.textContent = 'Modification/Jobs';

			var modificationInputCell = document.createElement('td');
			if(type == 'export_cnf') {
				modificationInputCell.colSpan = 16;
			}
			else {
				modificationInputCell.colSpan = 15;
			}
			var modificationInputElement = document.createElement('input');
			modificationInputElement.name ='vehicle['+data.vehicle_id+'][modification_or_jobs_to_perform_per_vin]';
			modificationInputElement.type = 'text';
			modificationInputElement.placeholder = 'Enter Modification Or Jobs to Perform Per VIN';
			modificationInputElement.style.border = 'none';
			modificationInputElement.style.width = '100%';
			modificationInputElement.value= data.modification_or_jobs_to_perform_per_vin;
			modificationInputCell.appendChild(modificationInputElement);

			// Add validation rule for modificationInputElement
			$(modificationInputElement).rules('add', {
				noSpaces: true,
				messages: {
					noSpaces: "No leading or trailing spaces allowed."
				}
			});

			// Append cells to the second row
			secondRow.appendChild(emptyLabelCell);
			secondRow.appendChild(modificationLabelCell);
			secondRow.appendChild(modificationInputCell);

			// Third Row Elements
			var emptyLabelThirdRowCell = document.createElement('td');
			emptyLabelThirdRowCell.colSpan = 1;
			emptyLabelThirdRowCell.textContent = '';

			var specialRequestLabelCell = document.createElement('td');
			specialRequestLabelCell.colSpan = 1
			specialRequestLabelCell.textContent = 'Special Request/Remarks';

			var specialRequestInputCell = document.createElement('td');
			if(type == 'export_cnf') {
				specialRequestInputCell.colSpan = 16;
			}
			else {
				specialRequestInputCell.colSpan = 15;
			}
			var specialRequestInputElement = document.createElement('input');
			specialRequestInputElement.name ='vehicle['+data.vehicle_id+'][special_request_or_remarks]';
			specialRequestInputElement.type = 'text';
			specialRequestInputElement.placeholder = 'Special Request or Remarks (Clean Car/ Inspec Damage/ Etc) Salesman Insight Colum Per VIN';
			specialRequestInputElement.style.border = 'none';
			specialRequestInputElement.style.width = '100%';
			specialRequestInputElement.value = data.special_request_or_remarks;
			specialRequestInputCell.appendChild(specialRequestInputElement);
			// Add validation rule for specialRequestInputCell
			$(specialRequestInputCell).rules('add', {
				noSpaces: true,
				messages: {
					noSpaces: "No leading or trailing spaces allowed."
				}
			});

			// Append cells to the third row
			thirdRow.appendChild(emptyLabelThirdRowCell);
			thirdRow.appendChild(specialRequestLabelCell);
			thirdRow.appendChild(specialRequestInputCell);

			// Last Row Elements
			var createAddon = createAddonCell(data.vehicle_id);
			if(type == 'export_cnf') {
				createAddon.colSpan = 18;
			}
			else {
				createAddon.colSpan = 17;
			}
			// Append cells to the last row
			lastRow.appendChild(createAddon);

			// Append rows to the table body
			tableBody.appendChild(firstRow);
			tableBody.appendChild(secondRow);
			tableBody.appendChild(thirdRow);

			// Store the VIN in the first row's data attribute for easy retrieval on click
			$(firstRow).data('vin', data.vin);

			// Store the vin in the third row's data attribute for easy retrieval on click
			$(thirdRow).data('vin', data.vin);

			var allVehicleRows = [firstRow, secondRow, thirdRow];
			var addonIndex = 0; // Initialize addon index for each vehicle
			var currentRow = thirdRow; // Start with the thirdRow
			if (data.addons && data.addons.length > 0) {
				for (var j = 0; j < data.addons.length; j++) {
					var addonId = data.addons[j].id; // Set the correct addonId based on your logic
					var addonValue = data.addons[j].addon_code;
					var addonQuantity = data.addons[j].addon_quantity;
					var addonDescription = data.addons[j].addon_description;
					if(addonValue != null) {
						currentRow = drawTableAddon(allVehicleRows,currentRow,data,addonIndex,addonId,addonValue,addonQuantity,addonDescription);
						addonIndex++; // Increment addonIndex after calling drawTableAddon
					}
				}
			}

			// Gather data from all dynamically added addon input fields
			$('.addon_input_outer_row').each(function() { 
				var addonId = $(this).attr('id').split('_')[2];
				var addonValue = $(`#addons_${addonId}`).val();
				var addonQuantity = $(`#addon_quantity_${addonId}`).val();
				var addonDescription = $(`#addon_description_${addonId}`).val();
				if(addonValue != null) { 
					currentRow = drawTableAddon(allVehicleRows,currentRow,data,addonIndex,addonId,addonValue,addonQuantity,addonDescription);
					addonIndex++; // Increment addonIndex after calling drawTableAddon
				}
			});
			tableBody.appendChild(lastRow);
			allVehicleRows.push(lastRow);
			$(removeIconCell).find('.remove-row').data('rows', allVehicleRows);
		}
		function drawTableAddon(allVehicleRows, thirdRow, data, addonIndex, addonId, addonValue, addonQuantity, addonDescription) { 
			var removeAddonCell = createAddonRemoveButton();
			
			// Add addonValue, addonQuantity, addonDescription as a row after thirdRow
			var addonRow = document.createElement('tr');
			addonRow.className = data.vehicle_id; // Add the vehicle_id as a class for addonRow

			// Addon Row Label
			var serviceBreakdownLabelCell = document.createElement('td');
			serviceBreakdownLabelCell.colSpan = 1;
			serviceBreakdownLabelCell.textContent = 'Service Breakdown'; 

			// Addon Row Elements
			var addonValueCell = document.createElement('td');
			addonValueCell.colSpan = 2;
			addonValueCell.textContent = addonValue;

			var addonQuantityCell = document.createElement('td');
			addonQuantityCell.colSpan = 1;
			addonQuantityCell.innerHTML = '<input type="hidden" name="vehicle['+data.vehicle_id+'][addons]['+addonIndex+'][id]" value="'+(addonId ?? '')+'">'
				+ '<input type="hidden" class="child_addon_'+data.vehicle_id+'" name="vehicle['+data.vehicle_id+'][addons]['+addonIndex+'][addon_code]" value="'+addonValue+'" id="addons_' + data.vehicle_id + '_' + addonIndex + '">'
				+ '<div class="input-group">'
				+ '<div class="input-group-append">'
				+ '<span style="border:none;background-color:#fafcff;font-size:12px;" class="input-group-text widthinput">Qty</span>'
				+ '</div>'
				+ '<input name="vehicle['+data.vehicle_id+'][addons]['+addonIndex+'][addon_quantity]" style="border:none;font-size:12px;" type="text" value="' + (addonQuantity ?? '') + '" class="form-control widthinput" id="addon_quantity_'+data.vehicle_id+'_' + addonIndex + '" placeholder="Addon Quantity">'
				+ '</div>';

			var addonDescriptionCell = document.createElement('td');
			if(type == 'export_cnf') {
				addonDescriptionCell.colSpan = 15;
			}
			else {
				addonDescriptionCell.colSpan = 14;
			}
			addonDescriptionCell.innerHTML = '<input name="vehicle['+data.vehicle_id+'][addons]['+addonIndex+'][addon_description]" style="border:none;font-size:12px;" type="text" value="'+(addonDescription ?? '')+'" class="form-control widthinput" id="addon_description_'+data.vehicle_id+'_' + addonIndex + '" placeholder="Enter Addon Description">';

			// Append cells to the addon row
			addonRow.appendChild(removeAddonCell);
			addonRow.appendChild(serviceBreakdownLabelCell);
			addonRow.appendChild(addonValueCell);
			addonRow.appendChild(addonQuantityCell);
			addonRow.appendChild(addonDescriptionCell);
			
			// Insert the new row into the array
			allVehicleRows.push(addonRow);
			
			// Append the addon row after the third row
			thirdRow.insertAdjacentElement('afterend', addonRow);
			// Add validation rules for all dynamicselectaddon elements
			$("#addon_quantity_"+data.vehicle_id+"_"+addonIndex).rules('add', {
				digits: true,
				min: 1 // Ensure it's a positive integer
			});
			$("#addon_description_"+data.vehicle_id+"_"+addonIndex).rules('add', {
				noSpaces: true,
			});
			return addonRow; // Return the newly created addonRow to be used as the next reference row
		}

		// Event delegation to handle remove button click for dynamically added rows
		$('#myTable').on('click', '.remove-row', function() {

			var vin = $(this).closest('tr').data('vin'); // Assuming each row has a data-vin attribute
			if (vin) {
				// Unselect and remove the VIN from all dynamicselect2 class elements
				$(".dynamicselect2").each(function() {
					var selectElement = $(this);
					selectElement.find(`option[value='${vin}']`).prop('selected', false).remove();
					// Trigger change to update the Select2 UI
					selectElement.trigger('change');
				});
				$('select option[value="'+ vin +'"]').prop('disabled', false);
			}
			var rows = $(this).data('rows');
			if (rows) {
				$(rows).each(function() {
					$(this).remove();
				});
			}
			findAllVINs();
		});
		// Event delegation to handle remove button click for remove addons row from vehicle table section
		$('#myTable').on('click', '.remove-addon-row', function() {
			var addon = $(this).closest('tr'); // Assuming each row has a data-vin attribute
			var className = addon.attr('class'); // Get the class name of the tr
			
			addon.remove();

			// Find all rows with the same class name and reset their indices
			var allVehicleRows = $('#myTable tr.' + className);
			allVehicleRows.each(function(index) {
				var row = $(this);			
				// Update hidden input field for addon_code
				row.find('.child_addon_'+className).attr('name', 'vehicle['+className+'][addons]['+index+'][addon_code]').attr('id', 'addons_'+className+'_'+index);

				// Update quantity input field within the specific div structure
				row.find('div.input-group input[type="text"]').attr('name', 'vehicle[' + className + '][addons][' + index + '][addon_quantity]').attr('id','addon_quantity_' + className + '_' + index);

				// Update description input field within the <td> element
				row.find('td input[id^="addon_description_"]').attr('name', 'vehicle[' + className + '][addons][' + index + '][addon_description]').attr('id', 'addon_description_' + className + '_' + index);
				
				// Update hidden input field for addon ID
				row.find('input[type="hidden"][name^="vehicle[' + className + '][addons]"]').attr('name', 'vehicle[' + className + '][addons][' + index + '][id]');

				// Check if the element is a select element and re-initialize select2
				if (row.find('select.child_addon_' + className).length > 0) {
					$('#addons_' + className + '_' + index).select2({
						allowClear: true,
						maximumSelectionLength: 1,
						placeholder: "Choose Addon"
					});
				}				
			});
			vehicleAddonDropdown(className)
		});
		// Event delegation to handle add addon for vehicle in the vehicle line level
		$('#myTable').on('click', '.create-addon-row', function() {
			// Get the data-id for this td
			var dataId = $(this).closest('td').data('id');
			
			// Find the next addon index for this particular dataId
			var addonIndex = 0;
			$('.' + dataId).each(function(index) {
				addonIndex = index + 1; // Adjust the index based on 0 or 1 based indexing 
			});
			var addonValue = '';
			var addonQuantity = '';
			var addonDescription = '';

			var removeAddonCell = createAddonRemoveButton();

			// Add addonValue, addonQuantity, addonDescription as a row after thirdRow
			var addonRow = document.createElement('tr');
			addonRow.className = dataId; // Add the vehicle_id as a class for addonRow

			// Addon Row Label
			var serviceBreakdownLabelCell = document.createElement('td');
			serviceBreakdownLabelCell.colSpan = 1;
			serviceBreakdownLabelCell.textContent = 'Service Breakdown';

			// Addon Row Elements
			var addonValueCell = document.createElement('td');
			addonValueCell.colSpan = 2;
			addonValueCell.innerHTML = '<select name="vehicle['+dataId+'][addons]['+addonIndex+'][addon_code]" id="addons_'+dataId+'_'+addonIndex+'" class="child_addon_'+dataId+' form-control widthinput dynamicselectaddon" data-parant="'+dataId+'" multiple="true">'
				+ '@foreach($addons as $addon)<option value="{{$addon->addon_code}} - {{$addon->addon_name}}">{{$addon->addon_code}} - {{$addon->addon_name}}</option>@endforeach'
				+ '@foreach($charges as $charge)<option value="{{$charge->addon_code}} - {{$charge->addon_name}}">{{$charge->addon_code}} - {{$charge->addon_name}}</option>@endforeach'
				+ '</select>';
			var addonQuantityCell = document.createElement('td');
			addonQuantityCell.colSpan = 1;
			addonQuantityCell.innerHTML = '<div class="input-group">'
			+'<div class="input-group-append">'
			+'<span style="border:none;background-color:#fafcff;font-size:12px;" class="input-group-text widthinput">Qty</span>'
			+'</div>'
			+'<input name="vehicle['+dataId+'][addons]['+addonIndex+'][addon_quantity]" style="border:none;font-size:12px;" type="text" value="' + (addonQuantity ?? '') + '" class="form-control widthinput" id="addon_quantity_'+dataId+ '_'+ addonIndex + '" placeholder="Addon Quantity">'
			+'</div>';

			var addonDescriptionCell = document.createElement('td');
			if(type == 'export_cnf') {
				addonDescriptionCell.colSpan = 14;
			}
			else {
				addonDescriptionCell.colSpan = 15;
			}
			addonDescriptionCell.innerHTML = '<input name="vehicle['+dataId+'][addons]['+addonIndex+'][addon_description]" style="border:none;font-size:12px;" type="text" value="' + (addonDescription ?? '') + '" class="form-control widthinput" id="addon_description_'+dataId+ '_' + addonIndex + '" placeholder="Enter Addon Description">';
			
			// Append cells to the addon row
			addonRow.appendChild(removeAddonCell);
			addonRow.appendChild(serviceBreakdownLabelCell);
			addonRow.appendChild(addonValueCell);
			addonRow.appendChild(addonQuantityCell);
			addonRow.appendChild(addonDescriptionCell);

			// Append the addon row after the last addon of the row VIN or before the add addon button
			var parentElementRemove = $(this).closest('tr');
			var firstRemoveRowButton = parentElementRemove.prevAll('tr').has('.remove-row').first();

			if (firstRemoveRowButton.length) {
				firstRemoveRowButton.after(addonRow);

				// Push addonRow element into data-rows array of firstRemoveRowButton element
				var rowsData = firstRemoveRowButton.find('.remove-row').data('rows') || [];
				rowsData.push(addonRow);
				firstRemoveRowButton.find('.remove-row').data('rows', rowsData);
			} else {
				parentElementRemove.after(addonRow);
			}
			var parentElement = this.parentElement.parentElement;
			parentElement.insertAdjacentElement('beforebegin', addonRow);
			// Initialize select2 after appending the row to the DOM
			$('#addons_' + dataId + '_' + addonIndex).select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder: "Choose Addon"
			});
				// Add validation rules for all dynamicselectaddon elements
				$('.dynamicselectaddon').each(function() {
                    $(this).rules('add', {
                        required: true,
                        messages: {
                            required: "This field is required."
                        }
                    });
                });
				$("#addon_quantity_"+dataId+"_"+addonIndex).rules('add', {
					digits: true,
					min: 1 // Ensure it's a positive integer
				});
				$("#addon_description_"+dataId+"_"+addonIndex).rules('add', {
					noSpaces: true,
				});
			vehicleAddonDropdown(dataId);
		});
		function vehicleAddonDropdown(dataId) {
			// Collect all selected addon values for the specific dataId
			var selectedAddonValues = [];
			$('.' + dataId).find('.child_addon_' + dataId).each(function() {
				if ($(this).is('input')) {
					selectedAddonValues.push($(this).val());
				} else if ($(this).is('select') && $(this).val()[0] != undefined) {  
					selectedAddonValues.push($(this).val()[0]);
				}
			});
			// Disable the collected options in all select elements for the dataId
			$('.' + dataId).find('select').each(function() {
				var selectElement = $(this);
				var currentSelectedValue = selectElement.val();
				selectElement.find('option').each(function() {
					var optionValue = $(this).val();
					// Disable options that are in the selectedAddonValues array but are not the currently selected value
					if (selectedAddonValues.includes(optionValue) && optionValue !== currentSelectedValue[0]) {
						$(this).prop('disabled', true);
					} else {
						$(this).prop('disabled', false);
					}
				});
				// Refresh select2 to show disabled options
				selectElement.trigger('change.select2');
			});
		}

		function findAllVINs() {
			addedVins = [];
			$('#myTable tbody .first-row').each(function() {
				var addedVin = $(this).data('vin'); 
				if (addedVin) {
					addedVins.push(addedVin);
				}
			});
			if(addedVins.length > 1) {

				$("#boe-div").show();
				var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
				if(index == 1) {
					addChild();
				}
			}
			else {
				$("#boe-div").hide();
			}
			if(selectedDepositReceivedValue == 'custom_deposit') {
				setDepositAganistVehicleDropdownOptions();
			}
		}
		document.addEventListener('DOMContentLoaded', function() {
			const table = document.getElementById('myTable');

			document.addEventListener('DOMContentLoaded', function() {
				// Attach event listeners to all remove buttons
				const removeButtons = document.querySelectorAll('.remove-btn');

				removeButtons.forEach(button => {
					button.addEventListener('click', function(event) {
						// Find the row to be removed
						const row = event.target.closest('tr');
						// Remove the row
						row.remove();
					});
				});
			});
		});
		function createEditableCell(value, placeHolder,name) {
			var cell = document.createElement('td');
			var inputElement = document.createElement('input');
			inputElement.type = 'text';
			inputElement.placeholder = placeHolder;
			inputElement.name = name;
			inputElement.value = value;
			inputElement.style.border = 'none';

			// Generate a unique ID for the input element
			var uniqueId = name.replace(/[\[\]]+/g, '_'); // Replace square brackets with underscores
			inputElement.id = uniqueId;

			// Append the input element to the cell
			cell.appendChild(inputElement);

			// Add validation rules using the ID as the selector
			setTimeout(function() {
				var rules = {
					noSpaces: true,
					messages: {
						noSpaces: "No leading or trailing spaces allowed."
					}
				};
				
				// Add custom validation for model year fields
				if (name.includes('model_year') || name.includes('model_year_to_mention_on_documents')) {
					rules.year4digits = true;
				}
				
				// Apply the validation rules
				$('#'+uniqueId).rules('add', rules);
			}, 0);

			return cell;
		}
		function createCellWithRemoveButton() {
			var cell = document.createElement('td');
			var removeButton = document.createElement('a');
			removeButton.className = 'btn_round remove-row';
			removeButton.title = 'Remove Vehicle';
			removeButton.textContent = 'x';
			cell.appendChild(removeButton);
			return cell;
		}

		function createAddonRemoveButton() {
			var cell = document.createElement('td');
			var removeButton = document.createElement('a');
			removeButton.className = 'addon_remove_btn_round remove-addon-row';
			removeButton.title = 'Remove Addon';
			removeButton.textContent = '-';
			cell.appendChild(removeButton);
			return cell;
		}
		function createAddonCell(vehicle_id) {
			var cell = document.createElement('td');
			cell.setAttribute('data-id', vehicle_id); // Setting the data-id attribute
			var addButton = document.createElement('a');
			addButton.className = 'addon_btn_round create-addon-row';
			addButton.title = 'Create Addon';
			addButton.textContent = '+';
			cell.appendChild(addButton);
			return cell;
		}
		function createEditableSelect2Cell(vin, vehicle_id, certification_per_vin) {
			var cell = document.createElement('td');
			var selectElement = document.createElement('select');
			selectElement.id = 'certification_per_vin_' + vin;
			selectElement.name = 'vehicle[' + vehicle_id + '][certification_per_vin]';
			selectElement.className = 'form-control widthinput';
			selectElement.multiple = true;
			selectElement.style.width = '100%';

			var options = [
				{ value: 'rta_without_number_plate', text: 'RTA Without Number Plate' },
				{ value: 'rta_with_number_plate', text: 'RTA With Number Plate' },
				{ value: 'certificate_of_origin', text: 'Certificate Of Origin' },
				{ value: 'certificate_of_conformity', text: 'Certificate Of Conformity' },
				{ value: 'qisj_inspection', text: 'QISJ Inspection' },
				{ value: 'eaa_inspection', text: 'EAA Inspection' }
			];
			options.forEach(function(optionData) {
				var option = document.createElement('option');
				option.value = optionData.value;
				option.textContent = optionData.text;

				if (certification_per_vin==optionData.value) {
					option.selected = true;
				}
				
				selectElement.appendChild(option);
			});

			cell.appendChild(selectElement);

			$(selectElement).select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder: "Choose ",
				initSelection: function(element, callback) {
					var selectedValues = certification_per_vin.map(function(value) {
						return options.find(option => option.value === value);
					});
					callback(selectedValues);
				}
			});

			// Set the selected values for select2
			$(selectElement).val(certification_per_vin).trigger('change');

			return cell;
		}


	// ADD AND REMOVE VEHICLE TO WO END

	// HIDE FIELDS START
		function hideDependentTransportType() {
			$("#airline-div").hide();
			$("#airway-bill-div").hide();
			$("#shippingline-div").hide();
			$("#forward-import-code-div").hide();
			$("#brn-div").hide();
			$("#brn-file-div").hide();
			$("#container-number-div").hide();
			$("#trailer-number-plate-div").hide();
			$("#transportation-company-div").hide();
			$("#transporting-driver-contact-number-div").hide();
			$("#airway-details-div").hide();
			$("#transportation-company-details-div").hide();
		}
	// HIDE FIELDS END

	// CUSTOMER DETAILS SECTION START
        function checkValue() {
			selectedCustomerEmail = $('#customer_email').val();
			selectedCustomerAddress = $('#customer_address').val();
			selectedCustomerContact = $('#customer_company_number').val();
            $('#customer_type').val('new');
            var textInput = document.getElementById('textInput');
            var Other = document.getElementById('Other');
            var switchToDropdown = document.getElementById('switchToDropdown');
            
            // Store the current select value
            var selectedCustomerName = $('#customer_name').val();
            // $('#customer_reference_type').val('select');
            $('#customer_reference_id').val(selectedCustomerName);
            
            // Hide the select2 container and show the text input
            $('#customer_name').next('.select2-container').hide();
            textInput.style.display = 'inline';
            Other.style.display = 'none';
            switchToDropdown.style.display = 'inline';
            $('#customer_address').val(newCustomerAddress);
			$('#customer_email').val(newCustomerEmail);
			$('#customer_company_number').val(newCustomerContact);
        }

        function switchToDropdown() {
			newCustomerEmail = $('#customer_email').val();
			newCustomerAddress = $('#customer_address').val();
			newCustomerContact = $('#customer_company_number').val();
            $('#customer_type').val('existing');
            var textInput = document.getElementById('textInput');
            var Other = document.getElementById('Other');
            var switchToDropdown = document.getElementById('switchToDropdown');
            
            // Store the current text input value
            var newCustomerName = $('#textInput').val();
            // $('#customer_reference_type').val('input');
            $('#customer_reference_id').val(newCustomerName);
            
            // Show the select2 container and hide the text input
            $('#customer_name').next('.select2-container').show();
            textInput.style.display = 'none';
            Other.style.display = 'inline';
            switchToDropdown.style.display = 'none';
            
            var selectedCustomerName = $('#customer_name').val();
            if (selectedCustomerName.length > 0) {
                // setCustomerRelations(selectedCustomerName);
				$('#customer_address').val(selectedCustomerAddress);
				$('#customer_email').val(selectedCustomerEmail);
				$('#customer_company_number').val(selectedCustomerContact);
            }
			else {
				$('#customer_address').val('');
				$('#customer_email').val('');
				$('#customer_company_number').val('');
			}
        }

       
		
	// CUSTOMER DETAILS SECTION END

	// SET WORK ORDER NUMBER INPUT OF SALES ORDER NUMBER START
	function setWo() {
        var SONumber = $('#so_number').val().trim(); // Get the value of the SO Number input and trim any whitespace

        if (SONumber === '') { // Check if the input is empty
            document.getElementById('wo_number').value = ''; // Clear the WO Number field
            return; // Exit the function
        }

        // Step 1: Split the string to get the part after "SO-"
        let parts = SONumber.split("SO-");
        if (parts.length !== 2 || parts[0] !== '') { // Check if the format is invalid
            document.getElementById('wo_number').value = ''; // Clear the WO Number field
            return; // Exit the function
        }

        // Step 2: Remove leading zeros from the part after "SO-"
        let numberPart = parts[1].replace(/^0+/, '');
        if (numberPart === '') { // Check if the number part is empty after removing leading zeros
            document.getElementById('wo_number').value = ''; // Clear the WO Number field
            return; // Exit the function
        }

        var WONumber = "WO-" + numberPart; // Construct the WO Number
        document.getElementById('wo_number').value = WONumber; // Set the WO Number field
    }
	// SET WORK ORDER NUMBER INPUT OF SALES ORDER NUMBER END

	// SET DEPOSIT BALANCE START
		function setDepositAganistVehicleDropdownOptions() {
			// Get the previously selected values
			var previouslySelectedValues = $('#deposit_aganist_vehicle').val() || [];
			// Empty the select element before adding new options
			$('#deposit_aganist_vehicle').empty();
			// Add new options to the select element
			addedVins.forEach(function(vin) {
				$('#deposit_aganist_vehicle').append(new Option(vin, vin));
			});

			// Initialize or reinitialize Select2
			$('#deposit_aganist_vehicle').select2({
				allowClear: true,
				placeholder: "Choose Vehicle"
			});

			// Reselect previously selected values if they still exist
			$('#deposit_aganist_vehicle').val(previouslySelectedValues.filter(function(value) {
				return addedVins.includes(value);
			})).trigger('change');
		}
		
	// SET DEPOSIT BALANCE END

	// CURRENCY UPDATE START
		function updateCurrency() {
			var currency = document.getElementById("currency").value;
			var currencyText = document.querySelector("#currency option:checked").textContent;
			document.getElementById("amount_received_currency").textContent = currencyText;
			document.getElementById("balance_amount_currency").textContent = currencyText;
		}
	// CURRENCY UPDATE END

    function sanitizeInput(input) {
        // Replace multiple spaces with a single space
        input.value = input.value.replace(/\s\s+/g, ' ');
    }

    function sanitizeNumberInput(input) {
        // Remove any non-numeric characters
        input.value = input.value.replace(/[^0-9]/g, '');
    }
	
	$('.delete-button').on('click',function() {
		var fileType = $(this).attr('data-file-type');
		if (confirm('Are you sure you want to Delete this item ?')) {
			if(fileType == 'BRN_File') {
				$('#brn_file_preview1').remove();
				$('#brn-file-file-delete').val(1);
	
			}else if(fileType == 'Signed_PFI') {
				$('#signed_pfi_preview1').remove();
				$('#signed-pfi-delete').val(1);
	
			}else if(fileType == 'Signed_Contract') {
				$('#signed_contract_preview1').remove();
				$('#signed-contract-delete').val(1);
			}
			else if(fileType == 'Payment_Receipts') {
				$('#payment_receipts_preview1').remove();
				$('#payment-receipts-file-delete').val(1);
			}
			else if(fileType == 'NOC') {
				$('#noc_preview1').remove();
				$('#noc-file-delete').val(1);
	
			}else if(fileType == 'Enduser_Trade_License') {
				$('#enduser_trade_license_preview1').remove();
				$('#enduser-trade-license-delete').val(1);
			}
			else if(fileType == 'Enduser_Passport') {
				$('#enduser_passport_preview1').remove();
				$('#enduser-passport-delete').val(1);
			}
			else if(fileType == 'Enduser_Contract') {
				$('#enduser_contract_preview1').remove();
				$('#enduser-contract-file-delete').val(1);
			}
			else if(fileType == 'Vehicle_Handover_Person_ID') {
				$('#vehicle_handover_person_id_preview1').remove();
				$('#vehicle-handover-person-id-file-delete').val(1);
			}
		}
	});
</script>

@endsection
