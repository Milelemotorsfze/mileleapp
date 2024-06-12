@extends('layouts.main')
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
	.comment {
            margin-bottom: 20px;
        }
        .reply {
            margin-left: 30px; /* Indent replies by 40px */
            margin-top: 10px;
        }
        .reply-button {
            margin-top: 10px;
        }
        .replies {
            margin-left: 30px; /* Indent nested replies by 40px */
        }
	/* .border-top {
		border-top: 1px solid #b3b3b3!important;
	} */
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
		/* font-size:15px!important; */
	}
	td {
		font-size:12px!important;
		/* font-size:15px!important; */
	}
	/* td {
		padding-top: calc(.47rem + 1px);
		padding-bottom: calc(.47rem + 1px);
		margin-bottom: 0;
		font-size: inherit;
		line-height: 1.5;
		font-weight: 500;
	} */
    #work-order-history-table td, th{
        font-size: 14px !important;
    }
    #textInput {
        display: none;
    }
    #switchToDropdown {
        display: none;
    }
    /* label{
        font-size:12px!important;
    } */
	.addon_btn_round {
		width: 20px!important;
		height: 14px!important;
		display: inline-block;
		/* border-radius: 50%; */
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
		/* border-radius: 50%; */
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
	.btn_round
	{
	width: 20px!important;
	height: 14px!important;
	display: inline-block;
	/* border-radius: 50%; */
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
			/* .comment {
            margin-bottom: 20px;
        }
        .reply {
            margin-left: 20px;
            margin-top: 10px;
        }
        .reply-button {
            margin-top: 10px;
        } */

			/* body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
} */

/* .comment-section {
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 400px;
}

.comment-box {
    display: flex;
    flex-direction: column;
}

#comment-input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    padding: 10px;
    background: #007BFF;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}  

button:hover {
    background: #0056b3;
}

#comment-list {
    list-style-type: none;
    padding: 0;
    margin-top: 20px;
}

.comment-item {
    background: #f9f9f9;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
}

.mention {
    color: #007BFF;
    font-weight: bold;
} */
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
	<h4 class="card-title"> Create @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order </h4>
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
	<div class="col-xxl-12 col-lg-12 col-md-12">
		<button style="float:right;" type="submit" class="btn btn-sm btn-success" value="create" id="submit">Submit</button>
	</div>
	<form id="WOForm" name="WOForm" action="{{route('work-order.store')}}" enctype="multipart/form-data" method="POST">
		@csrf
		<a  title="Finance Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success">
		<i class="fa fa-check" aria-hidden="true"></i> Finance Approval
		</a>
		<a  title="COE Office Approval" style="margin-top:0px;margin-bottom:1.25rem;" class="btn btn-sm btn-success">
		<i class="fa fa-check" aria-hidden="true"></i> COE Office Approval
		</a>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<center>General Informations</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<input type="hidden" name="type" id="type" value={{$type ?? ''}}>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="date" class="col-form-label text-md-end">{{ __('Date') }}</label>
						<input id="date" type="date" class="form-control widthinput @error('date') is-invalid @enderror" name="date"
							value="" autocomplete="date" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<span class="error">* </span>
						<label for="so_number" class="col-form-label text-md-end">{{ __('SO Number') }}</label>
						<input id="so_number" type="text" class="form-control widthinput @error('so_number') is-invalid @enderror" name="so_number"
							placeholder="Enter SO Number" value="SO-00" autocomplete="so_number" autofocus onkeyup="setWo()">
					</div>
					@if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<span class="error">* </span>
							<label for="batch" class="col-form-label text-md-end">{{ __('Choose Batch') }}</label>
							<select name="batch" id="batch" class="form-control widthinput" autofocus>
								<option value="">Choose Batch</option>
								<option value="Batch 1">Batch 1</option>
								<option value="Batch 2">Batch 2</option>
								<option value="Batch 3">Batch 3</option>
								<option value="Batch 4">Batch 4</option>
								<option value="Batch 5">Batch 5</option>
								<option value="Batch 6">Batch 6</option>
								<option value="Batch 7">Batch 7</option>
								<option value="Batch 8">Batch 8</option>
								<option value="Batch 9">Batch 9</option>
								<option value="Batch 10">Batch 10</option>
							</select>
						</div>
					</div>
					@endif
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="wo_number" class="col-form-label text-md-end">{{ __('WO Number') }}</label>
						<input id="wo_number" type="text" class="form-control widthinput @error('wo_number') is-invalid @enderror" name="wo_number"
							placeholder="Enter WO" value="WO-" autocomplete="wo_number" autofocus readonly>
					</div>
					<div class="col-xxl-5 col-lg-11 col-md-11">
						<label for="customer_name" class="col-form-label text-md-end">{{ __('Customer Name') }}</label>
						<input hidden id="customer_reference_id" name="customer_reference_id" value="">
						<input hidden id="customer_reference_type" name="customer_reference_type" value="">
						<select id="customer_name" name="existing_customer_name" class="form-control widthinput" multiple="true">
							@foreach($customers as $customer)
							<option value="{{$customer->customer_name ?? ''}}">{{$customer->customer_name ?? ''}}</option>
							@endforeach
						</select>
						<input type="text" id="textInput" placeholder="Enter Customer Name" name="new_customer_name"
							class="form-control widthinput @error('customer_name') is-invalid @enderror">
					</div>
					<div class="col-xxl-1 col-lg-1 col-md-1" id="Other">
						<a  title="Create New Customer" onclick=checkValue() style="margin-top:38px; width:100%;"
							class="btn btn-sm btn-info modal-button"><i class="fa fa-plus" aria-hidden="true"></i> Create New</a>
					</div>
					<div class="col-xxl-1 col-lg-1 col-md-1" id="switchToDropdown" >
						<a title="Choose Customer Name" onclick=switchToDropdown() style="margin-top:38px; width:100%;"
							class="btn btn-sm btn-info modal-button"><i class="fa fa-arrow-down " aria-hidden="true"></i> Choose</a>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6">
						<label for="customer_email" class="col-form-label text-md-end">{{ __('Customer Email ID') }}</label>
						<input id="customer_email" type="text" class="form-control widthinput @error('customer_email') is-invalid @enderror" name="customer_email"
							placeholder="Enter Customer Email ID" value="" autocomplete="customer_email" autofocus>
					</div>
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div">
						<label for="customer_company_number" class="col-form-label text-md-end">{{ __('Customer Contact Number') }}</label>
						<input id="customer_company_number" type="tel" class="widthinput contact form-control @error('customer_company_number[full]')
							is-invalid @enderror" name="customer_company_number[main]" placeholder="Enter Customer Contact Number"
							value="" autocomplete="customer_company_number[full]" autofocus>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="customer_address" class="col-form-label text-md-end">{{ __("Customer Address" ) }}</label>
						<textarea rows="3" id="customer_address" type="text" class="form-control @error('customer_address') is-invalid @enderror"
							name="customer_address" placeholder="Address in UAE" value="{{ old('customer_address') }}"  autocomplete="customer_address"
							autofocus></textarea>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="customer_representative_name" class="col-form-label text-md-end">{{ __("Customer Representative Name" ) }}</label>
						<input id="customer_representative_name" type="text" class="form-control widthinput @error('customer_representative_name') is-invalid @enderror" name="customer_representative_name"
							placeholder="Enter Customer Representative Name" value="" autocomplete="customer_representative_name" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="customer_representative_email" class="col-form-label text-md-end">{{ __('Customer Representative Email ID') }}</label>
						<div class="dropdown-option-div">
							<input id="customer_representative_email" type="text" class="form-control widthinput @error('customer_representative_email') is-invalid @enderror"
								name="customer_representative_email"
								placeholder="Enter Customer Representative Email ID" value="" autocomplete="customer_representative_email" autofocus>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="customer_representative_contact" class="col-form-label text-md-end">{{ __('Customer Representative Contact Number') }}</label>
						<input id="customer_representative_contact" type="tel" class="widthinput contact form-control @error('customer_representative_contact[full]')
							is-invalid @enderror" name="customer_representative_contact[main]" placeholder="Enter Customer Contact Number"
							value="" autocomplete="customer_representative_contact[full]" autofocus>
					</div>
					@if(isset($type) && $type == 'export_exw')
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="freight_agent_name" class="col-form-label text-md-end">{{ __('Freight Agent Name') }}</label>
						<input id="freight_agent_name" type="text" class="form-control widthinput @error('freight_agent_name') is-invalid @enderror"
							name="freight_agent_name"
							placeholder="Enter Freight Agent Name" value="" autocomplete="freight_agent_name" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="freight_agent_email" class="col-form-label text-md-end">{{ __('Freight Agent Email ID') }}</label>
						<input id="freight_agent_email" type="text" class="form-control widthinput @error('freight_agent_email') is-invalid @enderror"
							name="freight_agent_email"
							placeholder="Enter Freight Agent Email ID" value="" autocomplete="freight_agent_email" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="freight_agent_contact_number" class="col-form-label text-md-end">{{ __('Freight Agent Contact Number') }}</label>
						<input id="freight_agent_contact_number" type="tel" class="widthinput contact form-control @error('freight_agent_contact_number[full]')
							is-invalid @enderror" name="freight_agent_contact_number[main]" placeholder="Enter Freight Agent Contact Number"
							value="" autocomplete="freight_agent_contact_number[full]" autofocus>
					</div>
					@endif
					@if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="port_of_loading" class="col-form-label text-md-end">{{ __('Port of Loading') }}</label>
						<input id="port_of_loading" type="text" class="form-control widthinput @error('port_of_loading') is-invalid @enderror"
							name="port_of_loading"
							placeholder="Enter Port of Loading" value="" autocomplete="port_of_loading" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="port_of_discharge" class="col-form-label text-md-end">{{ __('Port of Discharge') }}</label>
						<input id="port_of_discharge" type="text" class="form-control widthinput @error('port_of_discharge') is-invalid @enderror"
							name="port_of_discharge"
							placeholder="Enter Port of Discharge" value="" autocomplete="port_of_discharge" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6">
						<label for="final_destination" class="col-form-label text-md-end">{{ __('Final Destination') }}</label>
						<input id="final_destination" type="text" class="form-control widthinput @error('final_destination') is-invalid @enderror"
							name="final_destination"
							placeholder="Enter Final Destination" value="" autocomplete="final_destination" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 radio-main-div">
						<label for="transport_type" class="col-form-label text-md-end">{{ __('Transport Type') }}</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="transport_type" name="transport_type" value="air" id="air" />
									<label for="air">Air</label>
								</div>
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="transport_type" name="transport_type" value="sea" id="sea" />
									<label for="sea">Sea</label>
								</div>
								<div class="col-xxl-4 col-lg-4 col-md-4">
									<input type="radio" class="transport_type" name="transport_type" value="road" id="road" />
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
						<input id="brn" type="text" class="form-control widthinput @error('brn') is-invalid @enderror" name="brn"
							placeholder="Enter BRN" autocomplete="brn" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="container-number-div">
						<label for="container_number" class="col-form-label text-md-end">{{ __('Container Number') }}</label>
						<input id="container_number" type="text" class="form-control widthinput @error('container_number') is-invalid @enderror" name="container_number"
							placeholder="Enter Container Number" autocomplete="container_number" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6 select-button-main-div" id="airline-div">
						<div class="dropdown-option-div">
							<label for="airline" class="col-form-label text-md-end">{{ __('Choose airline') }}</label>
							<select name="airline" id="airline" multiple="true" class="form-control widthinput" autofocus>
								@foreach($airlines as $airline)
								<option value="{{$airline->name}}">{{$airline->name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="airway-bill-div">
						<label for="airway_bill" class="col-form-label text-md-end">{{ __('Airway Bill') }}</label>
						<input id="airway_bill" type="text" class="form-control widthinput @error('airway_bill') is-invalid @enderror"
							name="airway_bill"
							placeholder="Enter Airway Bill" value="" autocomplete="airway_bill" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="shippingline-div">
						<label for="shipping_line" class="col-form-label text-md-end">{{ __('Shipping Line') }}</label>
						<input id="shipping_line" type="text" class="form-control widthinput @error('shipping_line') is-invalid @enderror"
							name="shipping_line"
							placeholder="Enter Shipping Line" value="" autocomplete="shipping_line" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="forward-import-code-div">
						<label for="forward_import_code" class="col-form-label text-md-end">{{ __('Forward Import Code') }}</label>
						<input id="forward_import_code" type="text" class="form-control widthinput @error('forward_import_code') is-invalid @enderror"
							name="forward_import_code"
							placeholder="Enter Forward Import Code" value="" autocomplete="forward_import_code" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="trailer-number-plate-div">
						<label for="trailer_number_plate" class="col-form-label text-md-end">{{ __('Trailer Number Plate') }}</label>
						<input id="trailer_number_plate" type="text" class="form-control widthinput @error('trailer_number_plate') is-invalid @enderror"
							name="trailer_number_plate"
							placeholder="Enter Trailer Number Plate" value="" autocomplete="trailer_number_plate" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="transportation-company-div">
						<label for="transportation_company" class="col-form-label text-md-end">{{ __('Transportation Company') }}</label>
						<input id="transportation_company" type="text" class="form-control widthinput @error('transportation_company') is-invalid @enderror"
							name="transportation_company"
							placeholder="Enter Transportation Company" value="" autocomplete="transportation_company" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-6 col-md-6" id="transporting-driver-contact-number-div">
						<label for="transporting_driver_contact_number" class="col-form-label text-md-end">{{ __('Transporting Driver Contact Number') }}</label>
						<input id="transporting_driver_contact_number" type="tel" class="widthinput contact form-control @error('transporting_driver_contact_number[full]')
							is-invalid @enderror" name="transporting_driver_contact_number[main]" placeholder="Enter Transporting Driver Contact Number"
							value="" autocomplete="transporting_driver_contact_number[full]" autofocus>
					</div>
					<div class="col-xxl-8 col-lg-6 col-md-6" id="airway-details-div">
						<label for="airway_details" class="col-form-label text-md-end">{{ __('Airway Details') }}</label>
						<input id="airway_details" type="text" class="widthinput contact form-control @error('airway_details')
							is-invalid @enderror" name="airway_details" placeholder="Enter Airway Details"
							value="" autocomplete="airway_details" autofocus>
					</div>
					<div class="col-xxl-8 col-lg-6 col-md-6" id="transportation-company-details-div">
						<label for="transportation_company_details" class="col-form-label text-md-end">{{ __('Transportation Company Details') }}</label>
						<input id="transportation_company_details" type="text" class="widthinput contact form-control @error('transportation_company_details')
							is-invalid @enderror" name="transportation_company_details" placeholder="Enter Transportation Company Details"
							value="" autocomplete="transportation_company_details" autofocus>
					</div>
					@endif
				</div>
				<hr>
				<div class="row">
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="so_total_amount" class="col-form-label text-md-end">SO Total Amount:</label>
						<div class="input-group">
							<input type="text" id="so_total_amount" name="so_total_amount" value="" class="form-control widthinput" placeholder="Enter SO Total Amount" onkeyup="setDepositBalance()">
							<div class="input-group-append">
								<select id="currency" class="form-control widthinput currencyClass" name="currency" onchange="updateCurrency()">
									<option value="AED">AED</option>
									<option value="USD">USD</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="so_vehicle_quantity" class="col-form-label text-md-end"> SO Vehicle Quantity :</label>
						<input id="so_vehicle_quantity" type="number" class="form-control widthinput @error('so_vehicle_quantity') is-invalid @enderror" name="so_vehicle_quantity"
							placeholder="Enter SO Vehicle Quantity" value="" autocomplete="so_vehicle_quantity" autofocus>
					</div>
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="deposit_received_as" class="col-form-label text-md-end"> Deposit Received As :</label>
						<fieldset style="margin-top:5px;" class="radio-div-container">
							<div class="row some-class">
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="deposit_received_as" name="deposit_received_as" value="total_deposit" id="total_deposit" />
									<label for="total_deposit">Total Deposit</label>
								</div>
								<div class="col-xxl-6 col-lg-6 col-md-6">
									<input type="radio" class="deposit_received_as" name="deposit_received_as" value="custom_deposit" id="custom_deposit" />
									<label for="custom_deposit">Custom Deposit</label>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3" id="amount-received-div">
						<label for="amount_received" class="col-form-label text-md-end">Amount Received :</label>
						<div class="input-group">
							<input type="text" value="" class="form-control widthinput" id="amount_received" name="amount_received" placeholder="Enter Total Deposit Received" onkeyup="setDepositBalance()">
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="amount_received_currency">AED</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3" id="balance-amount-div">
						<label for="balance_amount" class="col-form-label text-md-end">Balance Amount :</label>
						<div class="input-group">
							<input type="text" value="" class="form-control widthinput" id="balance_amount" name="balance_amount" placeholder="Enter Balance Amount" readonly>
							<div class="input-group-append">
								<span class="input-group-text widthinput" id="balance_amount_currency">AED</span>
							</div>
						</div>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12" id="deposit-aganist-vehicle-div">
						<label for="deposit_aganist_vehicle" class="col-form-label text-md-end">Deposit Aganist Vehicle :</label>
						<select name="deposit_aganist_vehicle" id="deposit_aganist_vehicle" multiple="true" class="form-control widthinput" autofocus>
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
						<a  title="Add VIN" onclick=addVIN() style="margin-top:38px; float:left;"
							class="btn btn-sm btn-info modal-button"><i class="fa fa-plus" aria-hidden="true"></i> add Vehicle</a>
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
								<th>Interior Coloure</th>
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
							placeholder="Enter Delivery Location" value="" autocomplete="delivery_location" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="delivery_contact_person" class="col-form-label text-md-end"> Delivery Contact Person :</label>
						<input id="delivery_contact_person" type="text" class="form-control widthinput @error('delivery_contact_person') is-invalid @enderror" name="delivery_contact_person"
							placeholder="Enter Delivery Contact Person" value="" autocomplete="delivery_contact_person" autofocus>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="delivery_date" class="col-form-label text-md-end"> Delivery Date  :</label>
						<input id="delivery_date" type="date" class="form-control widthinput @error('delivery_date') is-invalid @enderror" name="delivery_date"
							placeholder="Enter Delivery Date " value="" autocomplete="delivery_date" autofocus>
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
				<div class="card preview-div" hidden>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="signed_pfi-label"></span>
								<div id="signed_pfi-preview">
									@if(isset($candidate->candidateDetails->image_path))
									<div id="signed_pfi-preview1">
										<div class="row">
											<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
												<h6 class="fw-bold text-center mb-1" style="float:left;">Passport Size Photograph</h6>
											</div>
											<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
												<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
												<a href="{{ url('hrm/employee/photo/' . $candidate->candidateDetails->image_path) }}" download class="text-white">
												Download
												</a>
												</button>
												<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
													data-file-type="PASSPORT"> Delete</button>
											</div>
										</div>
										<iframe src="{{ url('hrm/employee/photo/' . $candidate->candidateDetails->image_path) }}" alt="Passport Size Photograph"></iframe>
									</div>
									@endif
								</div>
							</div>
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="signed_contract-label"></span>
								<div id="signed_contract-preview">
									@if(isset($candidate->candidateDetails->signed_contract))
									<div id="signed_contract-preview1">
										<div class="row">
											<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
												<h6 class="fw-bold text-center mb-1" style="float:left;">signed_contract</h6>
											</div>
											<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
												<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
												<a href="{{ url('hrm/employee/signed_contract/' . $candidate->candidateDetails->signed_contract) }}" download class="text-white">
												Download
												</a>
												</button>
												<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
													data-file-type="signed_contract"> Delete</button>
											</div>
										</div>
										<iframe src="{{ url('hrm/employee/signed_contract/' . $candidate->candidateDetails->signed_contract) }}" alt="signed_contract"></iframe>
									</div>
									@endif
								</div>
							</div>
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="payment_receipts-label"></span>
								<div id="payment_receipts-preview">
									@if(isset($candidate->candidateDetails->payment_receipts))
									<div id="payment_receipts-preview1">
										<div class="row">
											<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
												<h6 class="fw-bold text-center mb-1" style="float:left;">payment_receipts</h6>
											</div>
											<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
												<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
												<a href="{{ url('hrm/employee/payment_receipts/' . $candidate->candidateDetails->payment_receipts) }}" download class="text-white">
												Download
												</a>
												</button>
												<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
													data-file-type="payment_receipts"> Delete</button>
											</div>
										</div>
										<iframe src="{{ url('hrm/employee/payment_receipts/' . $candidate->candidateDetails->payment_receipts) }}" alt="payment_receipts"></iframe>
									</div>
									@endif
								</div>
							</div>
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="emirates-id-label"></span>
								<div id="noc-preview">
									@if(isset($candidate->candidateDetails->noc_file))
									<div id="noc-preview1">
										<div class="row">
											<div class="col-lg-6 col-md-12 col-sm-12 mt-1">
												<h6 class="fw-bold text-center mb-1" style="float:left;">Emirates ID</h6>
											</div>
											<div class="col-lg-6 col-md-12 col-sm-12 mb-2">
												<button  type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
												<a href="{{ url('hrm/employee/noc/' . $candidate->candidateDetails->noc_file) }}" download class="text-white">
												Download
												</a>
												</button>
												<button  type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
													data-file-type="EMIRATESID"> Delete</button>
											</div>
										</div>
										<iframe src="{{ url('hrm/employee/noc/' . $candidate->candidateDetails->noc_file) }}" alt="Emirates ID"></iframe>
									</div>
									@endif
								</div>
							</div>
						</div>
						<div class="row mt-4">
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="passport-label">
								@if(isset($candidate->candidateDetails->candidatePassport) && $candidate->candidateDetails->candidatePassport->count() > 0) Passport @endif
								</span>
								@if(isset($candidate->candidateDetails->candidatePassport) && $candidate->candidateDetails->candidatePassport->count() > 0)
								@foreach($candidate->candidateDetails->candidatePassport as $document)
								<div id="preview-div-{{$document->id}}">
									<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
									<a href="{{url('hrm/employee/passport/' . $document->document_path)}}" download class="text-white">
									Download
									</a>
									</button>
									<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}">
									Delete
									</button>
									<iframe src="{{ url('hrm/employee/passport/' . $document->document_path) }}" class="mt-2" alt="Passport"></iframe>
								</div>
								@endforeach
								@endif
								<div id="enduser_trade_license-preview">
								</div>
							</div>
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="national-id-label">
								@if(isset($candidate->candidateDetails->candidateNationalId) && $candidate->candidateDetails->candidateNationalId->count() > 0) National ID @endif
								</span>
								@if(isset($candidate->candidateDetails->candidateNationalId) && $candidate->candidateDetails->candidateNationalId->count() > 0)
								@foreach($candidate->candidateDetails->candidateNationalId as $document)
								<div id="preview-div-{{$document->id}}">
									<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
									<a href="{{url('hrm/employee/enduser_passport/' . $document->document_path)}}" download class="text-white">
									Download
									</a>
									</button>
									<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}">
									Delete
									</button>
									<iframe src="{{ url('hrm/employee/enduser_passport/' . $document->document_path) }}" class="mt-2" alt="National ID"></iframe>
								</div>
								@endforeach
								@endif
								<div id="enduser_passport-preview">
								</div>
							</div>
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="enduser_contract-label">
								@if(isset($candidate->candidateDetails->candidateEduDocs) && $candidate->candidateDetails->candidateEduDocs->count() > 0) Attested Educational Documents @endif
								</span>
								@if(isset($candidate->candidateDetails->candidateEduDocs) && $candidate->candidateDetails->candidateEduDocs->count() > 0)
								@foreach($candidate->candidateDetails->candidateEduDocs as $document)
								<div id="preview-div-{{$document->id}}">
									<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
									<a href="{{url('hrm/employee/enduser_contract/' . $document->document_path)}}" download class="text-white">
									Download
									</a>
									</button>
									<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}">
									Delete
									</button>
									<iframe src="{{ url('hrm/employee/enduser_contract/' . $document->document_path) }}" class="mt-2" alt="Attested Educational Documents"></iframe>
								</div>
								@endforeach
								@endif
								<div id="enduser_contract-preview">
								</div>
							</div>
							<div class="col-lg-3 col-md-12 col-sm-12 mt-2">
								<span class="fw-bold col-form-label text-md-end" id="professional-diploma-certificates-label">
								@if(isset($candidate->candidateDetails->candidateProDipCerti) && $candidate->candidateDetails->candidateProDipCerti->count() > 0) Professional / Diploma Certificates @endif
								</span>
								@if(isset($candidate->candidateDetails->candidateProDipCerti) && $candidate->candidateDetails->candidateProDipCerti->count() > 0)
								@foreach($candidate->candidateDetails->candidateProDipCerti as $document)
								<div id="preview-div-{{$document->id}}">
									<button  type="button" class="btn btn-sm btn-info mt-3 " style="float:right;">
									<a href="{{url('hrm/employee/professional_diploma_certificates/' . $document->document_path)}}" download class="text-white">
									Download
									</a>
									</button>
									<button  type="button" class="btn btn-sm btn-danger mt-3 document-delete-button" style="float:right;" data-id="{{ $document->id }}">
									Delete
									</button>
									<iframe src="{{ url('hrm/employee/professional_diploma_certificates/' . $document->document_path) }}" class="mt-2" alt="Professional / Diploma Certificates"></iframe>
								</div>
								@endforeach
								@endif
								<div id="professional-diploma-certificates-preview">
								</div>
							</div>
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
	</form>
	</br>
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">
				<center>Comments Section</center>
			</h4>
		</div>
		<div class="card-body">
			<div class="row" id="comments-section">
				<!-- Comments will be dynamically inserted here -->
			</div>
			<div class="form-group">
				<label for="new-comment">Add a comment:</label>
				<textarea class="form-control" id="new-comment" rows="3"></textarea>
				<button class="btn btn-sm btn-primary mt-2" onclick="addComment()">Add Comment</button>
			</div>
		</div>
	</div>
	<br>
	<div class="card mt-3">
		<div class="card-header text-center">
			<h4 class="card-title">Record History</h4>
		</div>
		<div class="card-body">
			<div class="row m-0">
				<div class="col-xxl-2 col-lg-2 col-md-6 col-sm-12 mb-2" style="background-color: #e6f1ff" >
					<label class="col-form-label">Filter Region</label>
				</div>
				<div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
					<label class="col-form-label">User</label>
				</div>
				<div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
					<select name="user_id" id="user_id" multiple="true" class="form-control widthinput">
						@foreach($users as $user)
						<option value="{{$user->id ?? ''}}">{{$user->name ?? ''}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
					<label class="col-form-label">Field</label>
				</div>
				<div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
					<input type="text" class="form-control widthinput" placeholder="Field">
				</div>
				<div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
					<label class="col-form-label">History Type</label>
				</div>
				<div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
					<input type="text" class="form-control widthinput" placeholder="History Type">
				</div>
			</div>
			<div class="row mt-1">
				<div class="table-responsive">
					<table id="work-order-history-table" class="table table-striped table-editable table-edits table table-condensed" >
						<thead style="background-color: #e6f1ff">
							<tr>
								<th>Date</th>
								<th>User</th>
								<th>History Type</th>
								<th>Old Value</th>
								<th>New Value</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>{{\Illuminate\Support\Carbon::now()->format('d M Y') }}</td>
								<td> Jacob</td>
								<td>Test Type</td>
								<td>TEST</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<br>
<div class="overlay"></div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script type="text/javascript">
	// Declare commentIdCounter only once
	let commentIdCounter = 1;
    $('#work-order-history-table').DataTable();
    var customers = {!! json_encode($customers) !!};
	var vins = {!! json_encode($vins) !!}
	var type = $("#type").val();
	var addedVins = [];
	var selectedDepositReceivedValue = '';
	var onChangeSelectedVins = [];
	const mentions = ["@Alice", "@Bob", "@Charlie"]; // Example list of mentions
	$(document).ready(function () { 
        hideDependentTransportType();
		$("#boe-div").hide();
		// SELECT 2 START
			$('#customer_name').select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder:"Choose Customer Name",
                // dropdownAutoWidth : true,
                // width: 'auto'
			});
			$('#vin_multiple').select2({
				allowClear: true,
				// maximumSelectionLength: 1,
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
			var customer_company_number = window.intlTelInput(document.querySelector("#customer_company_number"), {
				separateDialCode: true,
				preferredCountries:["ae"],
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
		// INTEL INPUT END

		// TRANSPORT TYPE ONCHANGE START
			$('.transport_type').click(function() {
				if($(this).val() == 'air') {
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
				else if($(this).val() == 'sea') {
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
				else if($(this).val() == 'road') {
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
					var transporting_driver_contact_number = window.intlTelInput(document.querySelector("#transporting_driver_contact_number"), {
						separateDialCode: true,
						preferredCountries:["ae"],
						hiddenInput: "full",
						utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
					});	
					$("#airway-details-div").hide();
					$("#transportation-company-details-div").show();
				}
			});
		// TRANSPORT TYPE ONCHANGE END

		// CUSTOMER NAME ONCHANGE START
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
				addAddon();
			});

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
			$("body").on("click", ".remove_node_btn_frm_field_addon", function () {
				var row = $(this).closest(".addon_input_outer_row");
				row.remove();
				resetRowIndexes();
			});
			// Event listener to handle change event for .dynamicselect2
			$("body").on("change", ".dynamicselect2", function () {
				disableSelectedOptions();
			});

		// BOE DYNAMICALLY ADD AND REMOVE END


		// ON CHANGE OF VIN FETCH ITS RELATED ADDONS START
			$('#vin_multiple').on('change', function() {
				onChangeSelectedVins = $(this).val(); // Get selected VINs
				var index = $(".addon_outer").find(".addon_input_outer_row").length + 1;
				if (onChangeSelectedVins && onChangeSelectedVins.length > 0) {
					if(index == 1) {
						addAddon();
					}
					else {
						resetAddonDropdown();
					}
					
				} else {
					// Clear addons dropdown if no VINs are selected
					$('#addons').empty().trigger('change');
				}
			});
		// ON CHANGE OF VIN FETCH ITS RELATED ADDONS END
	});

	function addComment(parentId = null) {
            const commentText = parentId ? $(`#reply-input-${parentId}`).val() : $('#new-comment').val();
            if (commentText.trim() === '') return;

            const commentId = commentIdCounter++;
            const commentHtml = `
                <div class="comment mt-2" id="comment-${commentId}">
						<div class="col-xxl-1 col-lg-1 col-md-1" style="width:3.33333%;">
							<img class="rounded-circle header-profile-user" src="http://127.0.0.1:8000/images/users/avatar-1.jpg" alt="Header Avatar" style="float: left;">
						</div>
						<div class="col-xxl-11 col-lg-11 col-md-11">${commentText}</br>
							<span style="color:gray;">Rejitha R Prasad</span>
							<span style="color:gray;"> - 30 May 2024, 18:00:00</span></br>
							<button class="btn btn-secondary btn-sm reply-button" onclick="showReplyForm(${commentId})">Reply</button></br>
							<div class="reply-form" id="reply-form-${commentId}" style="display: none;">
								<textarea class="form-control reply" id="reply-input-${commentId}" rows="2" placeholder="Write a reply..."></textarea>
								<button class="btn btn-sm btn-info mt-2" onclick="addComment(${commentId})">Send Reply</button>
							</div>
                    		<div class="replies" id="replies-${commentId}"></div>
						</div>
                </div>
            `;

            if (parentId === null) {
                $('#comments-section').append(commentHtml);
                $('#new-comment').val('');
            } else {
                $(`#replies-${parentId}`).append(commentHtml);
                $(`#reply-input-${parentId}`).val('');
                $(`#reply-form-${parentId}`).hide();
            }
        }

        function showReplyForm(commentId) {
            $(`#reply-form-${commentId}`).toggle();
        }
	// // SHOW MENTION IN COMMENTS SECTION START
	// 	document.getElementById('comment-input').addEventListener('input', showMentions);

	// 	function showMentions(event) {
	// 		const input = event.target.value;
	// 		const mentionList = document.getElementById('mentions-list');
	// 		const mentionTrigger = input.match(/@\w*$/);
			
	// 		if (mentionTrigger) {
	// 			const mentionQuery = mentionTrigger[0].slice(1).toLowerCase();
	// 			const filteredMentions = mentions.filter(mention => mention.toLowerCase().includes(mentionQuery));
				
	// 			mentionList.innerHTML = '';
	// 			filteredMentions.forEach(mention => {
	// 				const mentionItem = document.createElement('div');
	// 				mentionItem.textContent = mention;
	// 				mentionItem.addEventListener('click', () => selectMention(mention));
	// 				mentionList.appendChild(mentionItem);
	// 			});
				
	// 			mentionList.style.display = 'block';
	// 		} else {
	// 			mentionList.style.display = 'none';
	// 		}
	// 	}

	// 	function selectMention(mention) {
	// 		const inputField = document.getElementById('comment-input');
	// 		const inputText = inputField.value;
	// 		const newText = inputText.replace(/@\w*$/, mention + ' ');
	// 		inputField.value = newText;
	// 		document.getElementById('mentions-list').style.display = 'none';
	// 		inputField.focus();
	// 	}

	// 	function postComment() {
	// 		const commentInput = document.getElementById('comment-input');
	// 		const commentText = commentInput.value;
			
	// 		if (commentText.trim()) {
	// 			const commentList = document.getElementById('comment-list');
	// 			const commentItem = document.createElement('li');
	// 			commentItem.className = 'comment-item';
				
	// 			const mentionRegex = /@\w+/g;
	// 			const formattedText = commentText.replace(mentionRegex, match => `<span class="mention">${match}</span>`);
				
	// 			commentItem.innerHTML = formattedText;
	// 			commentList.appendChild(commentItem);
				
	// 			commentInput.value = '';
	// 		}
	// 	}
	// // SHOW MENTION IN COMMENTS SECTION END

	// ADDON DYNAMICALLY ADD AND REMOVE START
		// Function to reset row indexes
		function resetRowIndexes() {
			$(".addon_outer .addon_input_outer_row").each(function(index) {
				var newIndex = index + 1;
				$(this).attr('id', `addon_row_${newIndex}`);
				$(this).find('select').attr('id', `addons_${newIndex}`).data('index', newIndex);
				$(this).find('input[type="number"]').attr('id', `addon_quantity_${newIndex}`);
				$(this).find('textarea').attr('id', `addon_description_${newIndex}`);
			});
		}

		function resetAddonDropdown() {
			$.ajax({
				url: '{{ route('fetch-addons') }}',
				type: 'POST',
				data: {
					vins: onChangeSelectedVins,
					_token: '{{ csrf_token() }}'
				},
				dataType: 'json',
				success: function(response) {console.log(response.charges);
					// Iterate over each dynamicselect2 element to update its options
					$('.dynamicselect2').each(function() { 
						var $dropdown = $(this);
						var currentVal = $dropdown.val(); // Store current selected values

						// Clear current options in addons dropdown
						$dropdown.empty();

						// Populate the addons dropdown with new options
						if (response.charges && response.charges.length > 0) {
							$("#addon-dynamic-div").show();
							$.each(response.charges, function(index, charge) {
								$dropdown.append(
									$('<option></option>').val(charge.addon_code+" - "+charge.addon_name).text(charge.addon_code+" - "+charge.addon_name)
								);
							});
						}
						if (response.addons && response.addons.length > 0) {
							$("#addon-dynamic-div").show();
							$.each(response.addons, function(index, addon) {
								$dropdown.append(
									$('<option></option>').val(addon.addon_code+" - "+addon.addon_name).text(addon.addon_code+" - "+addon.addon_name)
								);
							});
						}
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
									<label for="addons_${index}" class="col-form-label text-md-end">Addon :</label>
									<select name="addons[]" id="addons_${index}" class="form-control widthinput dynamicselect2" data-index="${index}" multiple="true">
										<!-- Add-on options will be dynamically populated -->
									</select>
								</div>
								<div class="col-xxl-12 col-lg-12 col-md-12">
									<label for="addon_quantity_${index}" class="col-form-label text-md-end">Quantity :</label>
									<input type="number" name="addon_quantity[]" id="addon_quantity_${index}" class="form-control widthinput" placeholder="Enter Quantity">
								</div>
							</div>
						</div>
						<div class="col-xxl-9 col-lg-9 col-md-9">
							<label for="addon_description_${index}" class="col-form-label text-md-end">Addon Description :</label>
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

			disableSelectedOptions();
			resetAddonDropdown();
		}
	// ADDON DYNAMICALLY ADD AND REMOVE END

	// BOE DYNAMICALLY ADD AND REMOVE START
		function addChild() {
			var index = $(".form_field_outer").find(".form_field_outer_row").length + 1; 
			if (index <= addedVins.length) { 
				var options = addedVins.map(vin => `<option value="${vin}">${vin}</option>`).join('');
				var newRow = $(`
					<div class="row form_field_outer_row" id="${index}">
						<div class="col-xxl-11 col-lg-11 col-md-11">
							<label for="boe_vin_${index}" class="col-form-label text-md-end">VIN per BOE: ${index}</label>
							<select name="boe[${index}][vin]" id="boe_vin_${index}" class="form-control widthinput dynamicselect2" data-index="${index}" multiple="true">
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

				disableSelectedOptions();
			} else {
				alert("Sorry! You cannot create a number of BOE which is more than the number of VIN.");
			}
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
					.attr('name', `boe[${newIndex}][vin]`)
					.data('index', newIndex);

				// Reinitialize Select2
				$(`#boe_vin_${newIndex}`).select2({
					allowClear: true,
					placeholder: "Choose VIN Per BOE",
				});
			});

			disableSelectedOptions();
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
				$select.select2();
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
							// Get the table body element by ID
							var tableBody = document.querySelector('#myTable tbody');

							var firstRow = document.createElement('tr');
							firstRow.style.borderTop = '2px solid #a6a6a6';
							var secondRow = document.createElement('tr');
							var thirdRow = document.createElement('tr');
							var lastRow = document.createElement('tr');

							// First Row Elements
							var removeIconCell = createCellWithRemoveButton();
							var vinCell = createEditableCell(vins[i]?.vin ?? '', 'Enter VIN','vehicle['+i+'][vin]');
							vinCell.dataset.vin = vins[i]?.vin ?? ''; // Correctly setting the data-vin attribute
							var brandCell = createEditableCell(vins[i]?.variant?.master_model_lines?.brand?.brand_name ?? '', 'Enter Brand','vehicle['+i+'][brand]');
							var variantCell = createEditableCell(vins[i]?.variant?.name ?? '', 'Enter Variant','vehicle['+i+'][variant]');
							var engineCell = createEditableCell(vins[i]?.engine ?? '', 'Enter Engine','vehicle['+i+'][engine]');
							var modelDescriptionCell = createEditableCell(vins[i]?.variant?.master_model_lines?.model_line ?? '', 'Enter Model Description','vehicle['+i+'][model_description]');
							var modelYearCell = createEditableCell(vins[i]?.variant?.my ?? '', 'Enter Model Year','vehicle['+i+'][model_year]');
							var modelYearToMentionOnDocumentsCell = createEditableCell(vins[i]?.variant?.my ?? '', 'Enter Model Year to mention on Documents','vehicle['+i+'][model_year_to_mention_on_documents]');
							var steeringCell = createEditableCell(vins[i]?.variant?.steering ?? '', 'Enter Steering','vehicle['+i+'][steering]');
							var exteriorCell = createEditableCell(vins[i]?.exterior?.name ?? '', 'Enter Exterior Colour','vehicle['+i+'][exterior_colour]');
							var interiorColorCell = createEditableCell(vins[i]?.interior?.name ?? '', 'Enter Interior Colour','vehicle['+i+'][interior_colour]');
							var warehouseCell = createEditableCell(vins[i]?.warehouse_location?.name ?? '', 'Enter Warehouse','vehicle['+i+'][warehouse]');
							var territoryCell = createEditableCell(vins[i]?.territory ?? '', 'Enter Territory','vehicle['+i+'][territory]');
							var preferredDestinationCell = createEditableCell('', 'Enter Preferred Destination','vehicle['+i+'][preferred_destination]');
							var importTypeCell = createEditableCell(vins[i]?.document?.import_type ?? '', 'Enter Import Document Type','vehicle['+i+'][import_document_type]');
							var ownershipCell = createEditableCell(vins[i]?.document?.ownership ?? '', 'Enter Ownership','vehicle['+i+'][ownership_name]');
							var CertificationPerVINCell = createEditableSelect2Cell(vins[i]?.vin);

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
							// firstRow.style.borderTop = '1px solid #b3b3b3';

							// Second Row Elements
							var emptyLabelCell = document.createElement('td');
							emptyLabelCell.colSpan = 1;
							emptyLabelCell.textContent = '';

							var modificationLabelCell = document.createElement('td');
							modificationLabelCell.colSpan = 1;
							modificationLabelCell.textContent = 'Modification/Jobs';

							var modificationInputCell = document.createElement('td');
							modificationInputCell.colSpan = 15;
							var modificationInputElement = document.createElement('input');
							modificationInputElement.name ='vehicle['+i+'][modification_or_jobs_to_perform_per_vin]';
							modificationInputElement.type = 'text';
							modificationInputElement.placeholder = 'Enter Modification Or Jobs to Perform Per VIN';
							modificationInputElement.style.border = 'none';
							modificationInputElement.style.width = '100%';
							modificationInputCell.appendChild(modificationInputElement);

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
							specialRequestInputCell.colSpan = 15;
							var specialRequestInputElement = document.createElement('input');
							specialRequestInputElement.name ='vehicle['+i+'][special_request_or_remarks]';
							specialRequestInputElement.type = 'text';
							specialRequestInputElement.placeholder = 'Special Request or Remarks (Clean Car/ Inspec Damage/ Etc) Salesman Insight Colum Per VIN';
							specialRequestInputElement.style.border = 'none';
							specialRequestInputElement.style.width = '100%';
							specialRequestInputCell.appendChild(specialRequestInputElement);


							// Append cells to the third row
							thirdRow.appendChild(emptyLabelThirdRowCell);
							thirdRow.appendChild(specialRequestLabelCell);
							thirdRow.appendChild(specialRequestInputCell);

							// Last Row Elements
							var createAddon = createAddonCell();
							createAddon.colSpan = 17;
							// Append cells to the last row
							lastRow.appendChild(createAddon);

							// Append rows to the table body
							tableBody.appendChild(firstRow);
							tableBody.appendChild(secondRow);
							tableBody.appendChild(thirdRow);

							// Store the VIN in the first row's data attribute for easy retrieval on click
							$(firstRow).data('vin', vins[i]?.vin ?? '');

							// Store the vin in the third row's data attribute for easy retrieval on click
							$(thirdRow).data('vin', vins[i]?.vin ?? '');

							var allVehicleRows = [firstRow, secondRow, thirdRow];
							// Gather data from all dynamically added addon input fields
							var addonIndex = 1; // Initialize addon index for each vehicle
							$('.addon_input_outer_row').each(function() {
								var addonId = $(this).attr('id').split('_')[2];
								var addonValue = $(`#addons_${addonId}`).val();
								var addonQuantity = $(`#addon_quantity_${addonId}`).val();
								var addonDescription = $(`#addon_description_${addonId}`).val();

								var removeAddonCell = createAddonRemoveButton();
								// Add addonValue, addonQuantity, addonDescription as a row after thirdRow
								var addonRow = document.createElement('tr');

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
								addonQuantityCell.innerHTML = '<input type="hidden" name="vehicle['+i+'][addons]['+addonIndex+'][addon_code]" value="'+addonValue+'"><div class="input-group"><div class="input-group-append"><span style="border:none;background-color:#fafcff;font-size:12px;" class="input-group-text widthinput">Qty</span></div><input  name="vehicle['+i+'][addons]['+addonIndex+'][quantity] style="border:none;font-size:12px;" type="text" value="' + (addonQuantity ?? '') + '" class="form-control widthinput" id="addon_quantity" placeholder="Addon Quantity"></div>';

								var addonDescriptionCell = document.createElement('td');
								addonDescriptionCell.colSpan = 14;
								addonDescriptionCell.innerHTML = '<div class="input-group"><input name="vehicle['+i+'][addons]['+addonIndex+'][description]" style="border:none;font-size:12px;" type="text" value="' + (addonDescription ?? '') + '" class="form-control widthinput" id="addon_description" placeholder="Enter Addon Description"></div>';

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
								thirdRow = addonRow; // Update thirdRow to ensure the next addonRow is inserted correctly
								addonIndex = addonIndex+1;
							});
							tableBody.appendChild(lastRow);
							allVehicleRows.push(lastRow);
							$(removeIconCell).find('.remove-row').data('rows', allVehicleRows);
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
			// console.log(rows);
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
			addon.remove();
			// console.log(addon);
			// if (vin) {
			// 	// Unselect and remove the VIN from all dynamicselect2 class elements
			// 	$(".dynamicselect2").each(function() {
			// 		var selectElement = $(this);
			// 		selectElement.find(`option[value='${vin}']`).prop('selected', false).remove();
			// 		// Trigger change to update the Select2 UI
			// 		selectElement.trigger('change');
			// 	});
			// 	$('select option[value="'+ vin +'"]').prop('disabled', false);
			// }
			// var rows = $(this).data('rows');
			// if (rows) {
			// 	$(rows).each(function() {
			// 		$(this).remove();
			// 	});
			// }
			// findAllVINs();
		});
		// Event delegation to handle add addon for vehicle in the vehicle line level
		$('#myTable').on('click', '.create-addon-row', function() {
			var addonId = '';
			var addonValue = 'This is an addon value';
			var addonQuantity = '';
			var addonDescription = '';

			// var addonId = $(this).attr('id').split('_')[2]; console.log(addonId);
			// var addonValue = $(`#addons_${addonId}`).val();

			var removeAddonCell = createAddonRemoveButton();
			// Add addonValue, addonQuantity, addonDescription as a row after thirdRow
			var addonRow = document.createElement('tr');

			// Addon Row Label
			var serviceBreakdownLabelCell = document.createElement('td');
			serviceBreakdownLabelCell.colSpan = 1;
			serviceBreakdownLabelCell.textContent = 'Service Breakdown'; 
			// Addon Row Elements
			// var addonValueCell = document.createElement('td');
			// addonValueCell.colSpan = 2;
			// addonValueCell.textContent = addonValue;

			var addonValueCell = document.createElement('td');
			addonValueCell.colSpan = 2;
			// addonValueCell.textContent = addonValue;
			addonValueCell.innerHTML = '<select name="addons[]" id="addons_" class="form-control widthinput dynamicselectaddon" data-index="" multiple="true"></select>';

			var addonQuantityCell = document.createElement('td');
			addonQuantityCell.colSpan = 1;
			addonQuantityCell.innerHTML = '<div class="input-group"><div class="input-group-append"><span style="border:none;background-color:#fafcff;font-size:12px;" class="input-group-text widthinput">Qty</span></div><input style="border:none;font-size:12px;" type="text" value="' + (addonQuantity ?? '') + '" class="form-control widthinput" id="addon_quantity" placeholder="Addon Quantity"></div>';

			var addonDescriptionCell = document.createElement('td');
			addonDescriptionCell.colSpan = 14;
			addonDescriptionCell.innerHTML = '<div class="input-group"><input style="border:none;font-size:12px;" type="text" value="' + (addonDescription ?? '') + '" class="form-control widthinput" id="addon_description" placeholder="Enter Addon Description"></div>';

			// Append cells to the addon row
			addonRow.appendChild(removeAddonCell);
			addonRow.appendChild(serviceBreakdownLabelCell);
			addonRow.appendChild(addonValueCell);
			addonRow.appendChild(addonQuantityCell);
			addonRow.appendChild(addonDescriptionCell);
			// Ensure the newly added select element is included in the AJAX response handling
			var newDropdown = $(addonValueCell).find('.dynamicselectaddon');
			var rowVin = [];
			// Fetch addons via AJAX
			$.ajax({
					url: '{{ route('fetch-addons') }}',
					type: 'POST',
					data: {
						vins: rowVin,
						_token: '{{ csrf_token() }}'
					},
					dataType: 'json',
					success: function(response) { 
						// Populate the newDropdown with the response data
						newDropdown.empty();

						// Populate the dropdown with charges
						if (response.charges && response.charges.length > 0) {
							$.each(response.charges, function(index, charge) {
								newDropdown.append(
									$('<option></option>').val(charge.addon_code + " - " + charge.addon_name).text(charge.addon_code + " - " + charge.addon_name)
								);
							});
						}

						// Populate the dropdown with addons
						if (response.addons && response.addons.length > 0) {
							$.each(response.addons, function(index, addon) {
								newDropdown.append(
									$('<option></option>').val(addon.addon_code + " - " + addon.addon_name).text(addon.addon_code + " - " + addon.addon_name)
								);
							});
						}
						$('.dynamicselectaddon').select2({
							allowClear: true,
							maximumSelectionLength: 1,
							placeholder:"Choose Customer Name",
							// dropdownAutoWidth : true,
							// width: 'auto'
						});
					},
					error: function(xhr, status, error) {
						console.error("Error fetching add-ons:", error);
					}
				});

			// WRITE CODE TO APPEND THE ADDON ROW AFTER THE LAST ADDON OF THE ROW VIN OR BEFORE THE ADD ADDON BUTTON

			 var parentElementRemove = $(this).closest('tr');
				var firstRemoveRowButton = parentElementRemove.prevAll('tr').has('.remove-row').first();

				if (firstRemoveRowButton.length) {
					firstRemoveRowButton.after(addonRow);

					// Push addonRow element into data-rows array of firstRemoveRowButton element
					var rowsData = firstRemoveRowButton.find('.remove-row').data('rows') || [];
					rowsData.push(addonRow);
					firstRemoveRowButton.find('.remove-row').data('rows', rowsData);
				} 
				else {
					parentElementRemove.after(addonRow);
				}
				var parentElement = this.parentElement.parentElement;
			parentElement.insertAdjacentElement('beforebegin', addonRow);
			
		});
		function findAllVINs() {
			addedVins = [];
			$('#myTable tbody tr').each(function() {
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
			// inputElement.style.width = '100%';
			cell.appendChild(inputElement);
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
		function createAddonCell() {
			var cell = document.createElement('td');
			var addButton = document.createElement('a');
			addButton.className = 'addon_btn_round create-addon-row';
			addButton.title = 'Create Addon';
			addButton.textContent = '+';
			cell.appendChild(addButton);
			return cell;
		}
		function createEditableSelect2Cell(vin) {
			var cell = document.createElement('td');
			var selectElement = document.createElement('select');
			selectElement.id = 'certification_per_vin_'+vin;
			selectElement.name = 'certification_per_vin';
			selectElement.className = 'form-control widthinput';
			selectElement.multiple = true;
			selectElement.style.width = '100%';

			var options = [
				'RTA Without Number Plate',
				'RTA With Number Plate',
				'Certificate Of Origin',
				'Certificate Of Conformity',
				'QISJ Inspection',
				'EAA Inspection'
			];

			options.forEach(function(optionText) {
				var option = document.createElement('option');
				option.value = optionText;
				option.textContent = optionText;
				selectElement.appendChild(option);
			});

			cell.appendChild(selectElement);

			$(selectElement).select2({
				allowClear: true,
				// maximumSelectionLength: 1, Certification Per VIN
				placeholder: "Choose "
			});

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
			$("#amount-received-div").hide();
			$("#balance-amount-div").hide();
			$("#deposit-aganist-vehicle-div").hide();
		}
	// HIDE FIELDS END

	// CUSTOMER DETAILS SECTION START
		function checkValue() {
			var textInput = document.getElementById('textInput');
			var Other = document.getElementById('Other');
			var switchToDropdown = document.getElementById('switchToDropdown');
			$('#customer_name').next('.select2-container').hide();
			textInput.style.display = 'inline';
			Other.style.display = 'none';
			switchToDropdown.style.display = 'inline';
			$('#customer_address').val('');
		}
		function switchToDropdown() {
			var textInput = document.getElementById('textInput');
			var Other = document.getElementById('Other');
			var switchToDropdown = document.getElementById('switchToDropdown');
			$('#customer_name').next('.select2-container').show();
			textInput.style.display = 'none';
			Other.style.display = 'inline';
			switchToDropdown.style.display = 'none';
			var selectedCustomerName = $('#customer_name').val();
			if(selectedCustomerName.length > 0) {
				setCustomerRelations(selectedCustomerName);
			}
		}
		function setCustomerRelations(selectedCustomerName) {
			$('#customer_address').val('');
			// document.getElementById('customer_email').value = '';
			// document.getElementById('customer_company_number').value = '';
			if(selectedCustomerName != '') {
				for (var i = 0; i < customers.length; i++) {
					if (customers[i].name == selectedCustomerName) {
						if(customers[i].address != null) {
							$('#customer_address').val(customers[i]?.address);
						}
					}
				}
			}
		}
	// CUSTOMER DETAILS SECTION END

	// SET WORK ORDER NUMBER INPUT OF SALES ORDER NUMBER START
		function setWo() {
			var SONumber = $('#so_number').val();
			// Step 1: Split the string to get the part after "SO-"
			let parts = SONumber.split("SO-");
			if (parts.length < 2) {
				throw new Error("Invalid SO Number format");
			}
			// Step 2: Remove leading zeros from the part after "SO-"
			let numberPart = parts[1].replace(/^0+/, '');
			var WONumber = "WO-";
			if(numberPart != '') {
				WONumber = WONumber+numberPart;
			}
			document.getElementById('wo_number').value = WONumber;
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
		function setDepositBalance() {
			var totalAmount = $('#so_total_amount').val();
			var amountReceived = $('#amount_received').val();
			var balanceAmount = '';
			if(totalAmount != '' && amountReceived != '') {
				balanceAmount = Number(totalAmount) - Number(amountReceived);
			}
			document.getElementById('balance_amount').value = balanceAmount;
		}
	// SET DEPOSIT BALANCE END

	// CURRENCY UPDATE START
		function updateCurrency() {
			var currency = document.getElementById("currency").value;
			var currencyText = document.querySelector("#currency option:checked").textContent;
			// document.getElementById("currency-label").textContent = currencyText;
			document.getElementById("amount_received_currency").textContent = currencyText;
			document.getElementById("balance_amount_currency").textContent = currencyText;
		}
	// CURRENCY UPDATE END

	// CLIENT SIDE VALIDATION START
		$.validator.addMethod("SONumberFormat", function(value, element) {
			// Regular expression to match the format SO- followed by exactly 6 digits
			return this.optional(element) || /^SO-\d{6}$/.test(value);
		}, "Please enter a valid order number in the format SO-######");
		// Add custom validation method for contact number
		// $.validator.addMethod("validContactNumber", function(value, element) {
		// 	return this.optional(element) || /^[0-9]$/.test(value);
		// }, "Please enter a valid contact number");
		// $.validator.addMethod("WONumberFormat", function(value, element) {
		// 	// Regular expression to match the format WO- followed by exactly 6 digits
		// 	return this.optional(element) || /^WO-\d{6}$/.test(value);
		// }, "Please enter a valid order number in the format WO-######");
		jQuery.validator.setDefaults({
			errorClass: "is-invalid",
			errorElement: "p",
			errorPlacement: function ( error, element ) {
				error.addClass( "invalid-feedback font-size-13" );
				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				}
				else if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
					if (!element.val() || element.val().length === 0) {
						console.log("Error is here with length", element.val().length);
						error.addClass('select-error');
						error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
					} else {
						console.log("No error");
					}
				}
			else if (element.parent().hasClass('input-group')) {
					error.insertAfter(element.parent());
				}
				else {
					error.insertAfter( element );
				}
			}
		});
		$('#WOForm').validate({ // initialize the plugin
			rules: {
				type: {
					required: true,
				},
				date: {
					required: true,
					date: true,
				},
				so_number: {
					required: true,
					SONumberFormat: true
				},
				batch: {
					required: true,
				},
				// wo_number: {
				// 	required: true,
				// 	WONumberFormat: true
				// },
				customer_name: {
					// required: true,
				},
				customer_email: {
					// required: true,
					email: true,
				},
				customer_company_number: {
					// required: true,
					minlength: 5,
					maxlength: 20,
					// validContactNumber: true,
				},
				customer_address: {
					// required: true,
					// money: true,
					// greaterThanFirstValueValidate: "#salary_range_start_in_aed",
				},
				customer_representative_name: {
					// required: true,
				},
				customer_representative_email: {
					// required: true,
					email: true,
				},
				customer_representative_contact: {
					// required: true,
					minlength: 5,
					maxlength: 20,
				},
				freight_agent_name: {
					// required: true,
				},
				freight_agent_email: {
					// required: true,
					email: true,
				},
				freight_agent_contact_number: {
					// required: true,
					minlength: 5,
					maxlength: 20,
				},
				port_of_loading: {
					required: true,
				},
				port_of_discharge: {
					required: true,
				},
				final_destination: {
					required: true,
				},
				transport_type: {
					// required: true,
				},
				airline: {
					// required: true,
				},
				airway_bill: {
					// required: true,
				},
				shipping_line: {
					// required: true,
				},
				forward_import_code: {
					// required: true,
				},
				brn: {
					// required: true,
				},
				brn_file: {
					// required: true,
				},
				container_number: {
					// required: true,
				},
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
				delivery_location: {
					// required: true,
				},
				delivery_contact_person: {
					// required: true,
				},
				delivery_date: {
					// required: true,
				},
				signed_pfi: {
					// required: true,
				},
				signed_contract: {
					// required: true,
				},
				payment_receipts: {
					// required: true,
				},
				noc: {
					// required: true,
				},
				enduser_trade_license: {
					// required: true,
				},
				enduser_passport: {
					// required: true,
				},
				enduser_contract: {
					// required: true,
				},
			},
			// errorPlacement: function ( error, element ) {
			// 	error.addClass( "invalid-feedback font-size-13" );
			// 	if (element.is('select') && element.closest('.select-button-main-div').length > 0) {
			// 		console.log(element.val().length);
			// 		if (!element.val() || element.val().length === 0 || element.val().length > 0) {
			// 			console.log("Error is here with length", element.val().length);
			// 			error.addClass('select-error');
			// 			error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
			// 		} else {
			// 			console.log("No error");
			// 		}
			// 	}
			// 	else {
			// 		error.insertAfter( element );
			// 	}
			// }
		});
	// CLIENT SIDE VALIDATION END
</script>

@endsection