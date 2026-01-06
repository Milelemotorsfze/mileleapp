@extends('layouts.main')
<style>
	.div-custom-badge-font .badge {
		font-size: 119%;
		margin-right: 3px;
	}

	.custom-checkbox {
		width: 30px;
		height: 30px;
		border: 1px solid #ced4da !important;
	}

	#overlay {
		position: fixed;
		display: none;
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0, 0, 0, 0.5);
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
		font-size: 0.7rem !important;
		line-height: 0.1 !important;
	}

	.comment {
		margin-bottom: 20px;
	}

	.currencyClass {
		padding-top: 5px !important;
	}

	.table>:not(caption)>*>* {
		padding: .3rem .3rem !important;
		-webkit-box-shadow: inset 0 0 0 0px var(--bs-table-accent-bg) !important;
	}

	table {
		border-collapse: collapse;
		width: 100%;
	}

	th {
		font-size: 12px !important;
	}

	td {
		font-size: 12px !important;
	}

	#textInput {
		display: none;
	}

	#switchToDropdown {
		display: none;
	}

	.addon_btn_round {
		width: 20px !important;
		height: 14px !important;
		display: inline-block;
		text-align: center;
		line-height: 10px !important;
		margin-left: 0px !important;
		margin-top: 0px !important;
		border: 1px solid #2ab57d;
		color: #fff;
		background-color: #2ab57d;
		border-radius: 5px;
		cursor: pointer;
		padding-top: 1px !important;
	}

	.addon_remove_btn_round {
		width: 20px !important;
		height: 14px !important;
		display: inline-block;
		text-align: center;
		line-height: 10px !important;
		margin-left: 0px !important;
		margin-top: 0px !important;
		border: 1px solid #4ba6ef;
		color: #fff;
		background-color: #4ba6ef;
		border-radius: 5px;
		cursor: pointer;
		padding-top: 1px !important;
	}

	.btn_round {
		width: 20px !important;
		height: 14px !important;
		display: inline-block;
		text-align: center;
		line-height: 10px !important;
		margin-left: 0px !important;
		margin-top: 0px !important;
		border: 1px solid #ccc;
		color: #fff;
		background-color: #fd625e;
		border-radius: 5px;
		cursor: pointer;
		padding-top: 1px !important;
	}

	.btn_round_big {
		margin-top: 37px !important;
		padding-top: 8px !important;
		width: 30px;
		height: 30px;
		display: inline-block;
		text-align: center;
		line-height: 35px;
		margin-left: 10px;
		margin-top: 28px;
		border: 1px solid #ccc;
		color: #fff;
		background-color: #fd625e;
		border-radius: 5px;
		cursor: pointer;
		padding-top: 7px;
	}

	.btn_round_big:hover {
		color: #fff;
		background: #fd625e;
		border: 1px solid #fd625e;
	}

	.card-header {
		background-color: #e6f1ff !important;
	}

	.card-body {
		background-color: #fafcff !important;
	}

	.no-border {
		border: none !important;
	}

	.select2-container {
		width: 100% !important;
	}

	.my-datatable th {
		border-left: 1px solid #e9e9ef;
		border-right: 1px solid #e9e9ef;
		border-top: 1px solid #e9e9ef;
		border-bottom: 1px solid #e9e9ef;
		padding: 3px !important;
		text-align: left;
	}

	.my-datatable td {
		border-left: 1px solid #e9e9ef;
		border-right: 1px solid #e9e9ef;
		border-top: 1px solid #e9e9ef;
		border-bottom: 1px solid #e9e9ef;
		padding: 3px !important;
		text-align: left;
	}

	.my-datatable {
		border-collapse: collapse;
		width: 100%;
	}

	#styled-comment {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		color: transparent;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
</style>
@include('layouts.formstyle')
@section('content')
@php
$canCreateOrEditWO = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo','create-export-cnf-wo','create-local-sale-wo','create-lto-wo',
'edit-all-export-exw-work-order','edit-current-user-export-exw-work-order','edit-current-user-export-cnf-work-order','edit-all-export-cnf-work-order',
'edit-all-local-sale-work-order','edit-current-user-local-sale-work-order']);
$canViewWODetails = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details','export-cnf-wo-details',
'current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
$hasAllSalesPermission = Auth::user()->hasPermissionForSelectedRole(['create-wo-for-all-sales-person']);
$allfieldPermission = Auth::user()->hasPermissionForSelectedRole(['restrict-all-work-order-input-except-general-info']);
$hasAmountPermission = Auth::user()->hasPermissionForSelectedRole(['can-create-and-edit-amount']);
$restrictExceptGeneral = Auth::user()->hasPermissionForSelectedRole(['restrict-all-work-order-input-except-general-info']);
$action = isset($workOrder) ? 'Edit' : 'Create';
$orderType = match($type ?? '') {
'export_exw' => 'Export EXW',
'export_cnf' => 'Export CNF',
'local_sale' => 'Local Sale',
default => '',
};
$formAction = isset($workOrder)
? route('work-order.update', $workOrder->id)
: route('work-order.store');
@endphp
@if ($canCreateOrEditWO)
<div class="card-header">
	<h4 class="card-title">{{ $action }} {{ $orderType }} Work Order</h4>
</div>

<div class="card-body">
	@if ($errors->any())
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<div class="col-12 d-flex flex-wrap div-custom-badge-font">
		@if(isset($workOrder))
		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->status, 'status') }}">
			<strong>{{ strtoupper($workOrder->status ?? '') }}</strong>
		</label>
		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->sales_support_data_confirmation, 'confirmation') }}">
			SALES SUPPORT: <strong>{{ strtoupper($workOrder->sales_support_data_confirmation ?? '') }}</strong>
		</label>

		@if($workOrder->can_show_coo_approval === 'yes')
		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->coo_approval_status, 'approval') }}">
			COO OFFICE: <strong>{{ strtoupper($workOrder->coo_approval_status ?? '') }}</strong>
		</label>
		@endif

		@if($workOrder->can_show_fin_approval === 'yes')
		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->finance_approval_status, 'approval') }}">
			FINANCE: <strong>{{ strtoupper($workOrder->finance_approval_status ?? '') }}</strong>
		</label>
		@endif

		@if($workOrder->sales_support_data_confirmation_at &&
		$workOrder->finance_approval_status === 'Approved' &&
		$workOrder->coo_approval_status === 'Approved')

		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->docs_status, 'docs') }}">
			{{ strtoupper($workOrder->docs_status === 'Ready' ? 'Documents' : 'Documentation') }}:
			<strong>{{ strtoupper($workOrder->docs_status ?? '') }}</strong>
		</label>

		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->vehicles_modification_summary, 'modification') }}">
			MODIFICATION: <strong>{{ strtoupper($workOrder->vehicles_modification_summary ?? '') }}</strong>
		</label>

		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->pdi_summary, 'modification') }}">
			PDI: <strong>{{ strtoupper($workOrder->pdi_summary ?? '') }}</strong>
		</label>

		<label class="badge {{ $workOrder->getFormBadgeClass($workOrder->delivery_summary, 'delivery') }}">
			DELIVERY: <strong>{{ strtoupper($workOrder->delivery_summary ?? '') }}</strong>
		</label>
		@endif
		@endif
	</div>

	<div class="col-12 d-flex flex-wrap align-items-center">
		@if(!empty($previous))
		<a class="btn btn-sm btn-info me-2" href="{{ route('work-order.edit', $previous) }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record
		</a>
		@endif

		@if(!empty($next))
		<a class="btn btn-sm btn-info me-2" href="{{ route('work-order.edit', $next) }}">
			Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i>
		</a>
		@endif

		@include('work_order.export_exw.approvals')

		<a class="btn btn-sm btn-info me-2" href="{{ route('work-order.index', $type) }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> List
		</a>

		@if ($canViewWODetails && isset($workOrder))
		<a title="View Details" class="btn btn-sm btn-info me-2" href="{{ route('work-order.show', $workOrder->id) }}">
			<i class="fa fa-eye" aria-hidden="true"></i> View Details
		</a>
		@endif
		<a class="btn btn-sm btn-success ms-auto" id="submit-from-top">Submit</a>
	</div>
	<br>

	<form id="WOForm" name="WOForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
		@csrf
		@isset($workOrder)
		@method('PUT')
		@endisset
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
					<div class="col-xxl-{{ $hasAllSalesPermission ? '2' : '3' }} col-lg-6 col-md-6">
						<label for="date" class="col-form-label text-md-end">{{ __('Date') }}</label>
						<input type="text" class="form-control widthinput" readonly
							value="{{ isset($workOrder) && $workOrder->date ? \Carbon\Carbon::parse($workOrder->date)->format('d M Y') : \Carbon\Carbon::now()->format('d M Y') }}">
					</div>
					<div class="col-xxl-{{ $hasAllSalesPermission ? '2' : '3' }} col-lg-6 col-md-6">
						<span class="text-danger">* </span>
						<label for="so_number" class="col-form-label text-md-end">{{ __('SO Number') }}</label>
						<input id="so_number" name="so_number" type="text" class="form-control widthinput @error('so_number') is-invalid @enderror" placeholder="Enter SO Number"
							value="{{ isset($workOrder) ? $workOrder->so_number : 'SO-00' }}" autocomplete="so_number" onkeyup="isSOExist()">
					</div>
					@if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
					<div class="col-xxl-1 col-lg-2 col-md-2">
						<label for="is_batch" class="col-form-label text-md-end">Is Batch ?</label></br>
						<input type="checkbox" id="is_batch" name="is_batch" value="yes" class="custom-checkbox @error('is_batch') is-invalid @enderror"
							autocomplete="is_batch" @if(isset($workOrder) && $workOrder->is_batch == 1) checked @endif onchange="toggleBatchDropdown()"
						@if(isset($canDisableBatch) && $canDisableBatch == true) disabled @endif>
					</div>
					<div class="col-xxl-2 col-lg-4 col-md-4 select-button-main-div">
						<div id="batchDropdownSection" class="dropdown-option-div" style="display: @if(isset($workOrder) && $workOrder->is_batch == 1) block @else none @endif;">
							<span class="text-danger">* </span>
							<label for="batch" class="col-form-label text-md-end">{{ __('Choose Batch') }}</label>
							<select name="batch" id="batch" class="form-control widthinput" autofocus onchange="setWo()" @if(isset($canDisableBatch) && $canDisableBatch==true) disabled @endif>
								<option value="">Choose Batch</option>
								@for ($i = 1; $i <= 50; $i++)
									<option value="Batch {{ $i }}" {{ isset($workOrder) && $workOrder->batch == "Batch $i" ? 'selected' : '' }}>Batch {{ $i }}</option>
									@endfor
							</select>
						</div>
					</div>
					@endif
					<div class="col-xxl-{{ $hasAllSalesPermission ? '2' : '3' }} col-lg-6 col-md-6">
						<label for="wo_number" class="col-form-label text-md-end">{{ __('WO Number') }}</label>
						<input id="wo_number" type="text" class="form-control widthinput @error('wo_number') is-invalid @enderror" name="wo_number"
							placeholder="Enter WO" value="{{ isset($workOrder) ? $workOrder->wo_number : 'WO-' }}" autocomplete="wo_number" autofocus readonly>
					</div>

					@if($hasAllSalesPermission)
					<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div" id="sales-person-div">
						<div class="dropdown-option-div">
							<span class="text-danger">* </span>
							<label for="sales_person_id" class="col-form-label text-md-end">{{ __('Choose Sales Person') }}</label>
							<select name="sales_person_id" id="sales_person_id" multiple="true" class="form-control widthinput" autofocus>
								@foreach($salesPersons as $salesPerson)
								<option value="{{ $salesPerson->id }}" {{ isset($workOrder) && $workOrder->sales_person_id == $salesPerson->id ? 'selected' : '' }}>{{ $salesPerson->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					@else
					@if(isset($workOrder))
					<input type="hidden" name="sales_person_id" value="{{ $workOrder->sales_person_id ?? '' }}">
					@else
					<input type="hidden" name="sales_person_id" value="{{ Auth::id() }}">
					@endif
					@endif
					@if(isset($type) && $type == 'local_sale')
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<label for="lto" class="col-form-label text-md-end">LTO</label></br>
						<input type="checkbox" id="lto" name="lto" value="yes" class="custom-checkbox @error('lto') is-invalid @enderror" autocomplete="lto"
							@if(isset($workOrder) && $workOrder->lto == 'yes') checked @endif>
					</div>
					@endif
					<div class="col-xxl-4 col-lg-11 col-md-11">
						<label for="customer_name" class="col-form-label text-md-end">{{ __('Customer Name') }}</label>
						<input hidden id="customer_type" name="customer_type" value="existing">
						<input hidden id="customer_reference_id" name="customer_reference_id" value="">
						<input hidden id="customer_reference_type" name="customer_reference_type" value="">
						<select id="customer_name" name="existing_customer_name" class="form-control widthinput" multiple="true">
							@foreach($customers as $customer)
							<option
								value="{{ e($customer->customer_name ?? '') }}"
								data-id="{{ e(($customer->email ?? '') . '_' . ($customer->phone ?? '')) }}">
								{{ e($customer->customer_name ?? '') }}
							</option>
							@endforeach

						</select>
						<input type="text" id="textInput" placeholder="Enter Customer Name" name="new_customer_name"
							class="form-control widthinput @error('customer_name') is-invalid @enderror" onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-2 col-lg-1 col-md-1" id="Other">
						<a title="Create New Customer" onclick="checkValue()" style="margin-top:38px; width:100%;"
							class="btn btn-sm btn-info modal-button"><i class="fa fa-plus" aria-hidden="true"></i> Create New</a>
					</div>
					<div class="col-xxl-2 col-lg-1 col-md-1" id="switchToDropdown">
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
						</div>
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="customer_address" class="col-form-label text-md-end">{{ __("Customer Address" ) }}</label>
						<textarea rows="3" id="customer_address" type="text" class="form-control @error('customer_address') is-invalid @enderror"
							name="customer_address" placeholder="Address in UAE" value="{{ isset($workOrder) ? $workOrder->customer_address : '' }}" autocomplete="customer_address"
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
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<div class="row">
							<div class="col-xxl-6 col-lg-6 col-md-6">
								<label for="delivery_advise" class="col-form-label text-md-end">Delivery Advise</label></br>
								<input type="checkbox" id="delivery_advise" name="delivery_advise" value="yes" class="custom-checkbox @error('delivery_advise') is-invalid @enderror" autocomplete="delivery_advise"
									@if(isset($workOrder) && $workOrder->delivery_advise == 'yes') checked @endif>
							</div>
							<div class="col-xxl-6 col-lg-6 col-md-6">
								<label for="showroom_transfer" class="col-form-label text-md-end">Transfer Of Ownership</label></br>
								<input type="checkbox" id="showroom_transfer" name="showroom_transfer" value="yes" class="custom-checkbox @error('showroom_transfer') is-invalid @enderror" autocomplete="showroom_transfer"
									@if(isset($workOrder) && $workOrder->showroom_transfer == 'yes') checked @endif>
							</div>
						</div>
					</div>
					@endif
					@if(isset($type) && $type == 'export_cnf')
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<label for="cross_trade" class="col-form-label text-md-end">Cross Trade</label></br>
						<input type="checkbox" id="cross_trade" name="cross_trade" value="yes" class="custom-checkbox @error('cross_trade') is-invalid @enderror" autocomplete="cross_trade"
							@if(isset($workOrder) && $workOrder->cross_trade == 'yes') checked @endif>
					</div>
					@endif
					@if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
					<div class="col-xxl-2 col-lg-2 col-md-2">
						<label for="temporary_exit" class="col-form-label text-md-end">Temporary Exit</label></br>
						<input type="checkbox" id="temporary_exit" name="temporary_exit" value="yes" class="custom-checkbox @error('temporary_exit') is-invalid @enderror" autocomplete="temporary_exit"
							@if(isset($workOrder) && $workOrder->temporary_exit == 'yes') checked @endif>
					</div>
					<div class="{{ in_array($type, ['export_exw', 'export_cnf']) ? 'col-xxl-2 col-lg-6 col-md-6' : 'col-xxl-4 col-lg-6 col-md-6' }}">
						<span class="text-danger">* </span>
						<label for="port_of_loading" class="col-form-label text-md-end">{{ __('Port of Loading') }}</label>
						<input id="port_of_loading" type="text" class="form-control widthinput @error('port_of_loading') is-invalid @enderror"
							name="port_of_loading" onkeyup="sanitizeInput(this)"
							placeholder="Enter Port of Loading" value="{{ isset($workOrder) ? $workOrder->port_of_loading : '' }}" autocomplete="port_of_loading" autofocus>
					</div>

					<div class="{{ in_array($type, ['export_exw', 'export_cnf']) ? 'col-xxl-2 col-lg-6 col-md-6' : 'col-xxl-4 col-lg-6 col-md-6' }}">
						<span class="text-danger">* </span>
						<label for="port_of_discharge" class="col-form-label text-md-end">{{ __('Port of Discharge') }}</label>
						<input id="port_of_discharge" type="text" class="form-control widthinput @error('port_of_discharge') is-invalid @enderror"
							name="port_of_discharge" onkeyup="sanitizeInput(this)"
							placeholder="Enter Port of Discharge" value="{{ isset($workOrder) ? $workOrder->port_of_discharge : '' }}" autocomplete="port_of_discharge" autofocus>
					</div>

					<div class="{{ in_array($type, ['export_exw', 'export_cnf']) ? 'col-xxl-3 col-lg-6 col-md-6' : 'col-xxl-4 col-lg-6 col-md-6' }}">
						<span class="text-danger">* </span>
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
									is-invalid @enderror" name="airway_details" placeholder="Enter Airway Details" onkeyup="sanitizeInput(this)"
							value="{{ isset($workOrder) ? $workOrder->airway_details : '' }}" autocomplete="airway_details" autofocus>
					</div>
					<div class="col-xxl-8 col-lg-6 col-md-6" id="transportation-company-details-div">
						<label for="transportation_company_details" class="col-form-label text-md-end">{{ __('Transportation Company Details') }}</label>
						<input id="transportation_company_details" type="text" class="widthinput contact form-control @error('transportation_company_details')
									is-invalid @enderror" name="transportation_company_details" placeholder="Enter Transportation Company Details" onkeyup="sanitizeInput(this)"
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
											<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
												<a href="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" download class="text-white">
													Download
												</a>
											</button>
											<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
							<option value="{{$vin->vin ?? ''}}">{{$vin->vin ?? ''}} / {{$vin->variant->master_model_lines->brand->brand_name ?? ''}} / {{$vin->variant->master_model_lines->model_line ?? ''}}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12 addon_outer" id="addon-dynamic-div">
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<a title="Add VIN" style="margin-top:38px;float:right;"
							class="btn btn-sm btn-info modal-button add-addon-btn"><i class="fa fa-plus" aria-hidden="true"></i> Addon</a>
					</div>
				</div>
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<a title="Add VIN" style="margin-top:38px; float:left;"
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
					<center>SO Details</center>
				</h4>
			</div>
			<div class="card-body">
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
					<div class="col-xxl-3 col-lg-2 col-md-2">
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
					<div class="col-xxl-2 col-lg-3 col-md-3" id="balance-amount-div">
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
					<center>Questions</center>
				</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<label for="delivery_location" class="col-form-label text-md-end"> Delivery Location :</label>
						<input id="delivery_location" type="text" class="form-control widthinput @error('delivery_location') is-invalid @enderror" name="delivery_location"
							placeholder="Enter Delivery Location" value="{{ isset($workOrder) ? $workOrder->delivery_location : '' }}" autocomplete="delivery_location"
							autofocus onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<label for="delivery_contact_person" class="col-form-label text-md-end"> Delivery Contact Person Name :</label>
						<input id="delivery_contact_person" type="text" class="form-control widthinput @error('delivery_contact_person') is-invalid @enderror" name="delivery_contact_person"
							placeholder="Enter Delivery Contact Person Name" value="{{ isset($workOrder) ? $workOrder->delivery_contact_person : '' }}"
							autocomplete="delivery_contact_person" autofocus onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<label for="delivery_contact_person_number" class="col-form-label text-md-end"> Delivery Contact Person Number :</label>
						<input id="delivery_contact_person_number" type="tel" class="widthinput contact form-control @error('delivery_contact_person_number[full]')
									is-invalid @enderror" name="delivery_contact_person_number[main]" placeholder="Enter Customer Representative Contact Number"
							value="" autocomplete="delivery_contact_person_number[full]" autofocus onkeyup="sanitizeNumberInput(this)">
						<input type="hidden" id="delivery_contact_person_number_full" name="delivery_contact_person_number[full]" value="{{ isset($workOrder) ? $workOrder->delivery_contact_person_number : '' }}">
					</div>
					<div class="col-xxl-3 col-lg-3 col-md-3">
						<label for="delivery_date" class="col-form-label text-md-end"> Delivery Date :</label>
						<input id="delivery_date" type="date" class="form-control widthinput @error('delivery_date') is-invalid @enderror" name="delivery_date"
							placeholder="Enter Delivery Date " value="{{ isset($workOrder) ? $workOrder->delivery_date : '' }}" autocomplete="delivery_date" autofocus
							onkeyup="sanitizeInput(this)">
					</div>
				</div></br>
				<div class="row" id="boe-div">
					<div class="col-xxl-12 col-lg-12 col-md-12 form_field_outer" id="child">
					</div>
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<a id="add" style="float: right;" class="btn btn-sm btn-info add_new_frm_field_btn">
							<i class="fa fa-plus" aria-hidden="true"></i> Add BOE</a>
					</div>
				</div>
				@if(isset($type) && ($type == 'export_cnf'))
				<div class="row">
					<div class="col-xxl-12 col-lg-12 col-md-12">
						<label for="preferred_shipping_line_of_customer" class="col-form-label text-md-end"> Prefered Shipping Line for Customer :</label>
						<input id="preferred_shipping_line_of_customer" type="text" class="form-control widthinput @error('preferred_shipping_line_of_customer') is-invalid @enderror" name="preferred_shipping_line_of_customer"
							placeholder="Enter Prefered Shipping Line for Customer" value="{{ isset($workOrder) ? $workOrder->preferred_shipping_line_of_customer : '' }}"
							autocomplete="preferred_shipping_line_of_customer" autofocus onkeyup="sanitizeInput(this)">
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="bill_of_loading_details" class="col-form-label text-md-end">{{ __("Bill of Loading Details" ) }}</label>
						<textarea rows="5" id="bill_of_loading_details" type="text" class="form-control @error('bill_of_loading_details') is-invalid @enderror"
							name="bill_of_loading_details" placeholder="Enter Bill of Loading Details" value="{{ isset($workOrder) ? $workOrder->bill_of_loading_details : '' }}" autocomplete="bill_of_loading_details"
							autofocus onkeyup="sanitizeInput(this)">{{ isset($workOrder) ? $workOrder->bill_of_loading_details : '' }}</textarea>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="shipper" class="col-form-label text-md-end">{{ __("Shipper" ) }}</label>
						<textarea rows="5" id="shipper" type="text" class="form-control @error('shipper') is-invalid @enderror"
							name="shipper" placeholder="Enter Shipper" value="{{ isset($workOrder) ? $workOrder->shipper : '' }}" autocomplete="shipper"
							autofocus onkeyup="sanitizeInput(this)">{{ isset($workOrder) ? $workOrder->shipper : '' }}</textarea>
					</div>
					<div class="col-xxl-4 col-lg-4 col-md-4">
						<label for="consignee" class="col-form-label text-md-end">{{ __("Consignee" ) }}</label>
						<textarea rows="5" id="consignee" type="text" class="form-control @error('consignee') is-invalid @enderror"
							name="consignee" placeholder="Enter Consignee" value="{{ isset($workOrder) ? $workOrder->consignee : '' }}" autocomplete="consignee"
							autofocus onkeyup="sanitizeInput(this)">{{ isset($workOrder) ? $workOrder->consignee : '' }}</textarea>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6">
						<label for="notify_party" class="col-form-label text-md-end">{{ __("Notify Party" ) }}</label>
						<textarea rows="3" id="notify_party" type="text" class="form-control @error('notify_party') is-invalid @enderror"
							name="notify_party" placeholder="Enter Notify Party" value="{{ isset($workOrder) ? $workOrder->notify_party : '' }}" autocomplete="notify_party"
							autofocus onkeyup="sanitizeInput(this)">{{ isset($workOrder) ? $workOrder->notify_party : '' }}</textarea>
					</div>
					<div class="col-xxl-6 col-lg-6 col-md-6">
						<label for="special_or_transit_clause_or_request" class="col-form-label text-md-end">{{ __("Special/In Transit/Other Requests" ) }}</label>
						<textarea rows="3" id="special_or_transit_clause_or_request" type="text" class="form-control @error('special_or_transit_clause_or_request') is-invalid @enderror"
							name="special_or_transit_clause_or_request" placeholder="Enter Special/In Transit/Other Requests" value="{{ isset($workOrder) ? $workOrder->special_or_transit_clause_or_request : '' }}" autocomplete="special_or_transit_clause_or_request"
							autofocus onkeyup="sanitizeInput(this)">{{ isset($workOrder) ? $workOrder->special_or_transit_clause_or_request : '' }}</textarea>
					</div>
				</div>
				@endif
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/noc/' . $workOrder->noc) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
										<button type="button" class="btn btn-sm btn-info mb-1 " style="float:right;">
											<a href="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" download class="text-white">
												Download
											</a>
										</button>
										<button type="button" class="btn btn-sm btn-danger mb-1 delete-button" style="float:right;"
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
	<p class="card-title">Sorry! You don't have permission to access this page</p>
	<div class="d-flex justify-content-between">
		<a class="btn btn-sm btn-info" href="/">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard
		</a>
		<a class="btn btn-sm btn-info" href="{{ url()->previous() }}">
			<i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page
		</a>
	</div>
</div>
@endif
{{-- Customers --}}
<script id="customers-json" type="application/json">
@php
try {
    $customersJson = json_encode($customers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    if ($customersJson === false) {
        $customersJson = json_encode([], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
} catch (\Exception $e) {
    $customersJson = json_encode([], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}
@endphp
{!! $customersJson !!}
</script>

{{-- VINs --}}
<script id="vins-json" type="application/json">
@php
try {
    $vinsJson = json_encode($vins, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    if ($vinsJson === false) {
        $vinsJson = json_encode([], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
} catch (\Exception $e) {
    $vinsJson = json_encode([], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}
@endphp
{!! $vinsJson !!}
</script>

{{-- JS block to parse safely --}}
<script>
	let customers = [];
	let vins = [];

	try {
		const customersElement = document.getElementById('customers-json');
		const vinsElement = document.getElementById('vins-json');
		
		if (customersElement) {
			const customersText = customersElement.textContent.trim();
			if (customersText) {
				customers = JSON.parse(customersText);
			}
		}
		if (vinsElement) {
			const vinsText = vinsElement.textContent.trim();
			if (vinsText) {
				vins = JSON.parse(vinsText);
			}
		}
		console.log("Customers & VINs loaded successfully");
	} catch (e) {
		console.error("JSON parse failed:", e);
		console.error("Error details:", {
			message: e.message,
			name: e.name,
			stack: e.stack
		});
	}
</script>
<script type="text/javascript">
	let commentIdCounter = 1;
	var customerCount = $("#customerCount").val();
	var type = $("#type").val();
	var addedVins = [];
	var selectedDepositReceivedValue = '';
	var newCustomerEmail = '';
	var newCustomerContact = '';
	var newCustomerAddress = '';
	var selectedCustomerEmail = '';
	var selectedCustomerContact = '';
	var selectedCustomerAddress = '';
	let isBatchChecked = false;
	var onChangeSelectedVins = [];
	var vinWithoutBoe = [];
	var authUserPermission = @json($allfieldPermission ? 'true' : 'false');
	@if(isset($workOrder))
	var workOrder = @json($workOrder);
	@else
	var workOrder = null;
	document.addEventListener("DOMContentLoaded", function() {
		var today = new Date().toISOString().split('T')[0];
		document.getElementById("delivery_date").setAttribute("min", today);
	});
	@endif

	const mentions = ["@Alice", "@Bob", "@Charlie"];
	var input = document.querySelector("#customer_company_number");
	var iti = window.intlTelInput(document.querySelector("#customer_company_number"), {
		separateDialCode: true,
		preferredCountries: ["ae"],
		hiddenInput: "full",
		utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
	});
	var customer_representative_contact = window.intlTelInput(document.querySelector("#customer_representative_contact"), {
		separateDialCode: true,
		preferredCountries: ["ae"],
		hiddenInput: "full",
		utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
	});
	var delivery_contact_person_number = window.intlTelInput(document.querySelector("#delivery_contact_person_number"), {
		separateDialCode: true,
		preferredCountries: ["ae"],
		hiddenInput: "full",
		utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
	});
	if (type == 'export_exw') {
		var freight_agent_contact_number = window.intlTelInput(document.querySelector("#freight_agent_contact_number"), {
			separateDialCode: true,
			preferredCountries: ["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});
	}
	if (type == 'export_exw' || type == 'export_cnf') {
		var transporting_driver_contact_number = window.intlTelInput(document.querySelector("#transporting_driver_contact_number"), {
			separateDialCode: true,
			preferredCountries: ["ae"],
			hiddenInput: "full",
			utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
		});
	}
	$(document).ready(function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		console.log('Is vins an array:', Array.isArray(vins));
		document.getElementById('submit-from-top').addEventListener('click', function() {
			document.getElementById('submit').click();
		});
		$('#sales_person_id').select2({
			allowClear: true,
			maximumSelectionLength: 1,
			placeholder: "Choose Sales Person",
		});
		$('#customer_name').select2({
			allowClear: true,
			maximumSelectionLength: 1,
			placeholder: "Choose Customer Name",
		});


		if (workOrder == null || workOrder.deposit_received_as == null) {
			$("#amount-received-div").hide();
			$("#balance-amount-div").hide();
			$("#deposit-aganist-vehicle-div").hide();
		} else if (workOrder != null && workOrder.deposit_received_as == 'total_deposit') {
			$("#amount-received-div").show();
			$("#balance-amount-div").show();
			$("#deposit-aganist-vehicle-div").hide();
			selectedDepositReceivedValue = 'total_deposit';
			setDepositBalance();
		} else if (workOrder != null && workOrder.deposit_received_as == 'custom_deposit') {
			$("#amount-received-div").show();
			$("#balance-amount-div").show();
			$("#deposit-aganist-vehicle-div").show();
			selectedDepositReceivedValue = 'custom_deposit';
			setDepositAganistVehicleDropdownOptions();
			setDepositBalance();
		}

		if (workOrder != null && (workOrder.signed_pfi != null || workOrder.signed_contract != null || workOrder.payment_receipts != null || workOrder.noc != null ||
				workOrder.enduser_trade_license != null || workOrder.enduser_passport != null || workOrder.enduser_contract != null || workOrder.vehicle_handover_person_id != null)) {
			$('.preview-div').attr('hidden', false);
		}
		if (workOrder != null && (workOrder.brn_file != null)) {
			$('.brn-preview-div').attr('hidden', false);
		}
		if (workOrder !== null && workOrder.customer_reference_id === null && workOrder.customer_name !== null) {
			checkValue();
			$('#textInput').val(workOrder.customer_name);
		} else if (workOrder !== null && (workOrder.customer_reference_id !== null || workOrder.customer_reference_id === 0) && workOrder.customer_name !== null) {
			$("#customer_name").val(workOrder.customer_name).change();
		}
		if (workOrder == null || workOrder.transport_type == null) {
			hideDependentTransportType();
		}
		if (workOrder !== null) {
			$('#customer_address').val(workOrder.customer_address);
			$('#customer_email').val(workOrder.customer_email);
			var customer_company_numberFull = workOrder.customer_company_number ? workOrder.customer_company_number.replace(/\s+/g, '') : '';
			iti.setNumber(customer_company_numberFull);
			var customer_representative_contactFull = workOrder.customer_representative_contact ? workOrder.customer_representative_contact.replace(/\s+/g, '') : '';
			customer_representative_contact.setNumber(customer_representative_contactFull);
			var delivery_contact_person_numberFull = workOrder.delivery_contact_person_number ? workOrder.delivery_contact_person_number.replace(/\s+/g, '') : '';
			delivery_contact_person_number.setNumber(delivery_contact_person_numberFull);
			var freight_agent_contact_numberFull = workOrder.freight_agent_contact_number ? workOrder.freight_agent_contact_number.replace(/\s+/g, '') : '';
			if (freight_agent_contact_number && typeof freight_agent_contact_number.setNumber === 'function') {
				freight_agent_contact_number.setNumber(freight_agent_contact_numberFull);
			}
			if (workOrder.transport_type == 'air') {
				airRelation();
			} else if (workOrder.transport_type == 'sea') {
				seaRelation();
			} else if (workOrder.transport_type == 'road') {
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
			var boeVins = {};
			var allVins = [];

			for (var i = 0; i < workOrder.vehicles.length; i++) {
				drawTableRow(workOrder.vehicles[i]);

				$("#vin_multiple").find('option[value="' + workOrder.vehicles[i].vin + '"]').prop('disabled', true);

				$("#vin_multiple").trigger('change.select2');

				addedVins.push(workOrder.vehicles[i].vin);
				allVins.push(workOrder.vehicles[i].vin);

				var $depositSelect = $("#deposit_aganist_vehicle");
				if (workOrder.vehicles[i].deposit_received == 'yes') {
					var newOption = new Option(workOrder.vehicles[i].vin, workOrder.vehicles[i].vin, true, true);
					$depositSelect.append(newOption).trigger('change');
				} else {
					var newOption = new Option(workOrder.vehicles[i].vin, workOrder.vehicles[i].vin, false, false);
					$depositSelect.append(newOption).trigger('change');
				}

				if (workOrder.vehicles[i].boe_number != null) {
					var boeNumber = workOrder.vehicles[i].boe_number;
					if (!boeVins[boeNumber]) {
						boeVins[boeNumber] = [];
					}
					boeVins[boeNumber].push(workOrder.vehicles[i].vin);
				} else {
					vinWithoutBoe.push(workOrder.vehicles[i].vin);
				}
			}
			var newBoeOption = '';
			allVins.forEach(function(vin) {
				var newBoeOption = new Option(vin, vin, false, false);
			});
			Object.keys(boeVins).forEach(function(boeNumber) {
				addChild();
				var index = $(".form_field_outer").find(".form_field_outer_row").length;
				var $boeSelect = $(`#boe_vin_${index}`);
				$boeSelect.append(newBoeOption);

				boeVins[boeNumber].forEach(function(vin) {
					$boeSelect.find('option[value="' + vin + '"]').prop('selected', true);
				});

				$boeSelect.trigger('change');
			});

			$(".form_field_outer").find(".form_field_outer_row select").each(function() {
				var $select = $(this);
				var selectedVins = [];

				$select.find('option:selected').each(function() {
					selectedVins.push($(this).val());
				});

				allVins.forEach(function(vin) {
					if (selectedVins.indexOf(vin) === -1) {
						$select.find('option[value="' + vin + '"]').prop('disabled', true);
					}
				});
				vinWithoutBoe.forEach(function(vin) {
					$select.find('option[value="' + vin + '"]').prop('disabled', false);
				});
				$select.trigger('change.select2');
			});
			if (authUserPermission === 'true') {
				var table = document.getElementById('myTable');
				if (table) {
					var inputs = table.querySelectorAll('input');
					inputs.forEach(function(input) {
						input.readOnly = true;
					});
					var selects = table.querySelectorAll('select');
					selects.forEach(function(input) {
						input.disabled = true;
					});
				}
				var deleteVehicleLine = document.querySelectorAll('.remove-row');
				deleteVehicleLine.forEach(function(button) {
					button.readOnly = true;
				});
				var deleteAddonLine = document.querySelectorAll('.remove-addon-row');
				deleteAddonLine.forEach(function(button) {
					button.readOnly = true;
				});
				var createAddonLine = document.querySelectorAll('.create-addon-row');
				createAddonLine.forEach(function(button) {
					button.readOnly = true;
				});
			}
		} else {
			$("#boe-div").hide();
		}

		$('#vin_multiple').select2({
			allowClear: true,
			placeholder: "VIN",
		});
		$('#vin').select2({
			allowClear: true,
			maximumSelectionLength: 1,
			placeholder: "VIN",
		});
		$('#user_id').select2({
			allowClear: true,
			maximumSelectionLength: 1,
			placeholder: "Select User",
		});

		function setCustomerRelations(selectedCustomerUniqueId) {
			$('#customer_address').val('');
			$('#customer_email').val('');
			$('#customer_company_number').val('');
			if (selectedCustomerUniqueId != null) {
				for (var i = 0; i < customerCount; i++) {
					if (customers[i].unique_id == selectedCustomerUniqueId) {
						if (customers[i].customer_address != null) {
							$('#customer_address').val(customers[i]?.customer_address);
						}
						if (customers[i].customer_email != null) {
							$('#customer_email').val(customers[i]?.customer_email);
						}
						if (customers[i].customer_company_number != null) {
							var fullPhoneNumber = customers[i].customer_company_number ? customers[i].customer_company_number.replace(/\s+/g, '') : '';
							iti.setNumber(fullPhoneNumber);
							sanitizeNumberInput(input);
						}
					}
				}
			}
		}
		$('.transport_type').click(function() {
			if ($(this).val() == 'air') {
				airRelation();
			} else if ($(this).val() == 'sea') {
				seaRelation();
			} else if ($(this).val() == 'road') {
				roadRelation();
			}
		});

		function airRelation() {
			$("#airline-div").show();
			$('#airline').select2({
				allowClear: true,
				maximumSelectionLength: 1,
				placeholder: "Choose Airline",
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
		$('#customer_name').on('change', function() {
			var selectedCustomerUniqueId = $('#customer_name option:selected').data('id');
			setCustomerRelations(selectedCustomerUniqueId);
		});
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
			setDepositBalance();
		});
		$("body").on("click", ".add_new_frm_field_btn", function() {
			addChild();
		});
		$("body").on("click", ".add-addon-btn", function() {
			if (validateVINSelection()) {
				addAddon();
			}
		});
		$("body").on("click", ".add-vehicle-btn", function() {
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
			$(".addondynamicselect2").each(function() {
				if ($(this).val() === null || $(this).val().length === 0) {
					isValid = false;
					alert("Please select addon.");
					return false;
				}
			});
			return isValid;
		}
		$("body").on("click", ".remove_node_btn_frm_field", function() {
			var row = $(this).closest(".form_field_outer_row");
			var selectElement = row.find('.dynamicselect2');

			if (selectElement.data('select2')) {
				selectElement.select2('destroy');
			}

			var selectedVINs = selectElement.val();
			if (selectedVINs) {
				selectedVINs.forEach(function(vin) {
					$('select option[value="' + vin + '"]').prop('disabled', false);
				});
			}

			row.remove();
			resetIndexes();
		});

		$(document).on('click', '.remove_node_btn_frm_field_addon', function() {
			$(this).closest('.addon_input_outer_row').remove();
			disableAddonSelectedOptions();
			resetRowIndexes();
		});
		$("body").on("change", ".dynamicselect2", function() {
			disableSelectedOptions();
		});
		$(document).on('change', '.addondynamicselect2', function() {
			disableAddonSelectedOptions();
		});



		$('#vin_multiple').on('change', function() {
			onChangeSelectedVins = $(this).val();
			var index = $(".addon_outer").find(".addon_input_outer_row").length + 1;
			if (onChangeSelectedVins && onChangeSelectedVins.length > 0) {
				// if(index == 1) {
				// 	addAddon();
				// }
				// else {
				// resetAddonDropdown();
				// }

			} else {
				$('#addons').empty().trigger('change');
			}
		});
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

		if (type == 'export_exw' || type == 'export_cnf') {
			fileInputBRNFile.addEventListener("change", function(event) {
				$('.brn-preview-div').attr('hidden', false);
				const files = event.target.files;
				while (previewFileBRNFile.firstChild) {
					previewFileBRNFile.removeChild(previewFileBRNFile.firstChild);
				}
				const file = files[0];
				document.getElementById('brn_file_label').textContent = "BRN File";
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
			document.getElementById('signed_pfi_label').textContent = "Signed PFI";
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
			document.getElementById('signed_contract_label').textContent = "Signed Contract";
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
			document.getElementById('payment_receipts_label').textContent = "Payment Receipts";
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
			document.getElementById('noc_label').textContent = "NOC";
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
			document.getElementById('enduser_trade_license_label').textContent = "Enduser Trade License";
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
			document.getElementById('enduser_passport_label').textContent = "Enduser Passport";
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
			document.getElementById('enduser_contract_label').textContent = "Enduser Contract";
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
			document.getElementById('vehicle_handover_person_id_label').textContent = "Vehicle Handover Person ID";
			const objectUrl = URL.createObjectURL(file);
			const iframe = document.createElement("iframe");
			iframe.src = objectUrl;
			previewFileVehicleHandoverPersonID.appendChild(iframe);
		});

		$(document.body).on('select2:select', ".dynamicselectaddon", function(e) {
			var dataId = $(this).attr('data-parant');
			vehicleAddonDropdown(dataId)
		});
		$(document.body).on('select2:unselect', ".dynamicselectaddon", function(e) {
			var dataId = $(this).attr('data-parant');
			vehicleAddonDropdown(dataId)
		});


		if (workOrder != null && workOrder.sales_support_data_confirmation_at != null) {
			var hasEditConfirmedPermission = <?php echo json_encode(Auth::user()->hasPermissionForSelectedRole(['edit-confirmed-work-order'])); ?>;
			var isDisabled = !hasEditConfirmedPermission;
			if (isDisabled) {
				var elements = document.querySelectorAll('#WOForm #submit');
				elements.forEach(function(element) {
					element.disabled = true;
				});
				var submitFromTopButton = document.getElementById('submit-from-top');
				if (submitFromTopButton) {
					submitFromTopButton.disabled = true;
					submitFromTopButton.classList.add('disabled');
				}
				// document.addEventListener("DOMContentLoaded", function() {
				var today = new Date().toISOString().split('T')[0];
				document.getElementById("delivery_date").setAttribute("min", today);
				// });
			}
		}
		initializeMentions('#new-comment');

		function initializeMentions(selector) {
			$(selector).atwho({
				at: "@",
				data: [],
				limit: 10,
				callbacks: {
					remoteFilter: function(query, renderCallback) {
						if (query.length === 0) {
							renderCallback([]);
							return;
						}
						$.ajax({
							url: '/users-search',
							type: 'GET',
							data: {
								query: query
							},
							success: function(response) {
								console.log(response);
								if (response.users && response.users.length > 0) {
									renderCallback(response.users.map(user => ({
										id: user.id,
										name: user.name || 'Unknown User'
									})));
								} else {
									renderCallback([]);
								}
							},
							error: function() {
								console.error('Error fetching user data.');
								renderCallback([]);
							}
						});
					},
					beforeInsert: function(value, $li) {
						const mentionText = value.replace('@', '');
						return `@[${mentionText}]`;
					}
				}
			});
		}

		$('#comments-section').on('click', '.reply-button', function() {
			const commentId = $(this).closest('.comment').data('comment-id');
			initializeMentions(`#reply-input-${commentId}`);
		});
	});
	$.validator.addMethod("customEmail", function(value, element) {
		return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(value);
	}, "Please enter a valid email address");
	$.validator.addMethod("SONumberFormat", function(value, element) {
		return this.optional(element) || /^SO-\d{6}$/.test(value);
	}, "Please enter a valid order number in the format SO-######");

	$.validator.addMethod("notSO000000", function(value, element) {
		return this.optional(element) || value !== "SO-000000";
	}, "SO Number cannot be SO-000000");

	$.validator.addMethod("noSpaces", function(value, element) {
		return this.optional(element) || /^[^\s]+(\s+[^\s]+)*$/.test(value);
	}, "No leading or trailing spaces allowed");

	$.validator.addMethod("numericOnly", function(value, element) {
		value = value.replace(/\s+/g, '');
		return this.optional(element) || /^[0-9+]+$/.test(value);
	}, "Please enter a valid number");

	$.validator.addMethod("validAddress", function(value, element) {
		return this.optional(element) || !/\s\s+/.test(value);
	}, "No more than one consecutive space is allowed in the address");
	$.validator.addMethod("uniqueSO", function(value, element) {
		var result = false;
		var WoId = $("#wo_id").val();
		$.ajax({
			type: "POST",
			async: false,
			url: "{{route('work-order.uniqueSO')}}",
			data: {
				_token: '{{csrf_token()}}',
				so_number: value,
				id: WoId
			},
			success: function(data) {
				result = (data == true) ? true : false;
			}
		});
		return result;
	}, "This SO Number is already taken! Try another.");
	$.validator.addMethod("uniqueWO", function(element) {
		var result = false;
		var WoId = $("#wo_id").val();
		var wo_number = $("#wo_number").val();
		$.ajax({
			type: "POST",
			async: false,
			url: "{{route('work-order.uniqueWO')}}",
			data: {
				_token: '{{csrf_token()}}',
				wo_number: wo_number,
				id: WoId
			},
			success: function(data) {
				result = (data == true) ? true : false;
			}
		});
		return result;
	}, "This WO Number is already taken! Try another.");
	$.validator.addMethod("greaterThanExisting", function(value, element) {
		var numericPart = parseInt(value.split('-')[1], 10);

		return this.optional(element) || numericPart > 6500;
	}, "SO Number must be greater than SO-006500");
	$.validator.addMethod("customDepositVehicleRequired", function(value, element) {
		if (selectedDepositReceivedValue === 'custom_deposit' && addedVins.length > 0) {
			return $('select[name="deposit_aganist_vehicle[]"]').val().length > 0;
		}
		return true;
	}, "At least one vehicle must be selected if deposit is received as custom deposit");

	$.validator.addMethod("allVinsSelected", function(value, element) {
		let selectedVins = new Set();
		$('.dynamicselect2').each(function() {
			$(this).val().forEach(vin => selectedVins.add(vin));
		});

		for (let vin of addedVins) {
			if (!selectedVins.has(vin)) {
				return false;
			}
		}

		return true;
	}, "All work order vehicles should be selected under one BOE per VIN field.");
	$.validator.addMethod("year4digits", function(value, element) {
		return this.optional(element) || /^\d{4}$/.test(value);
	}, "Please enter a valid year with 4 digits.");
	$.validator.addMethod("isExistInSalesOrder", function(value, element) {
		var result = false;
		// Make an AJAX call to the backend to check if the SO number exists
		$.ajax({
			url: '/is-exist-in-sales-order', // Ensure this matches the route defined in web.php
			type: 'POST',
			async: false, // Use synchronous request to wait for the response
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
				so_number: value, // The SO number entered by the user
			},
			success: function(response) {
				if (response.valid) {
					result = true; // SO number exists
				} else {
					result = false; // SO number does not exist
				}
			},
			error: function(xhr) {
				console.error("An error occurred while checking SO number: ", xhr.responseText);
				result = false; // Default to false on error
			}
		});
		return result;
	}, "This SO number is not in the sales order.");


	$('#WOForm').validate({
		rules: {
			type: {
				required: true,
			},
			so_number: {
				required: true,
				noSpaces: true,
				SONumberFormat: true,
				notSO000000: true,
				isExistInSalesOrder: true,
				// uniqueWO: true,
				// uniqueSO: true,
				// greaterThanExisting: true, 
			},
			wo_number: {
				uniqueWO: function() {
					return $("#wo_number").val() !== '';
				}
			},
			batch: {
				required: true,
				// uniqueWO: true,
			},
			sales_person_id: {
				required: true,
			},
			new_customer_name: {
				noSpaces: true,
			},
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
			"delivery_contact_person_number[main]": {
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
			brn_file: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			brn: {
				noSpaces: true,
			},
			container_number: {
				noSpaces: true,
			},
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
			delivery_location: {
				noSpaces: true,
			},
			delivery_contact_person: {
				noSpaces: true,
			},
			delivery_date: {
				date: true,
			},
			preferred_shipping_line_of_customer: {
				noSpaces: true,
			},
			bill_of_loading_details: {
				noSpaces: true,
				validAddress: true,
				maxlength: 255
			},
			shipper: {
				noSpaces: true,
				validAddress: true,
				maxlength: 255
			},
			consignee: {
				noSpaces: true,
				validAddress: true,
				maxlength: 255
			},
			notify_party: {
				noSpaces: true,
				validAddress: true,
				maxlength: 255
			},
			special_or_transit_clause_or_request: {
				noSpaces: true,
				validAddress: true,
				maxlength: 255
			},
			signed_pfi: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			signed_contract: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			payment_receipts: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			noc: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			enduser_trade_license: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			enduser_passport: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			enduser_contract: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
			vehicle_handover_person_id: {
				extension: "jpg|jpeg|png|pdf",
				maxsize: 1073741824,
			},
		},
		messages: {
			brn_file: {
				filesize: " file size must be less than 1 GB.",
			},
			signed_pfi: {
				filesize: " file size must be less than 1 GB.",
			},
			signed_contract: {
				filesize: " file size must be less than 1 GB.",
			},
			payment_receipts: {
				filesize: " file size must be less than 1 GB.",
			},
			noc: {
				filesize: " file size must be less than 1 GB.",
			},
			enduser_trade_license: {
				filesize: " file size must be less than 1 GB.",
			},
			enduser_passport: {
				filesize: " file size must be less than 1 GB.",
			},
			enduser_contract: {
				filesize: " file size must be less than 1 GB.",
			},
			vehicle_handover_person_id: {
				filesize: " file size must be less than 1 GB.",
			},
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent());
			element.addClass('is-invalid');
			if (element.prop("type") === "tel" && element.closest('.select-button-main-div').length > 0) {
				if (!element.val() || element.val().length === 0 || element.val().length > 0) {
					console.log("Error is here with length", element.val().length);
					error.addClass('select-error');
					error.insertAfter(element.closest('.select-button-main-div').find('.dropdown-option-div').last());
				} else {
					console.log("No error");
				}
			}
		},
		highlight: function(element, errorClass, validClass) {
			$(element).addClass(errorClass).removeClass(validClass);
			$(element).next('p.invalid-feedback').show();
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).removeClass(errorClass).addClass(validClass);
			$(element).next('p.invalid-feedback').hide();
			if (!$(element).hasClass(errorClass)) {
				$(element).removeClass('is-invalid');
			}
		},
		submitHandler: function(form) {
			event.preventDefault();
			$('#overlay').show();
			const comments = [];
			if (workOrder == null) {
				const commentElements = document.querySelectorAll('#comments-section .comment');

				if (commentElements.length > 0) {
					commentElements.forEach(comment => {
						const commentId = comment.getAttribute('data-comment-id');
						const parentId = comment.getAttribute('data-parent-id');
						const dateTime = comment.getAttribute('data-date-time');
						const textElement = comment.querySelector('.comment-text');

						const fileElements = comment.querySelectorAll(`.file-preview[data-comment-id="${commentId}"] img, .file-preview[data-comment-id="${commentId}"] embed`);

						const files = Array.from(fileElements).map(file => ({
							src: file.src,
							name: file.alt || file.getAttribute('src').split('/').pop()
						}));

						if (textElement) {
							const text = textElement.textContent.trim();
							comments.push({
								commentId,
								parentId,
								text,
								dateTime,
								files
							});
						} else {
							console.warn('Text element is missing for a comment:', comment);
						}
					});
				} else {
					console.warn('No comments found in #comments-section.');
				}
			}

			if (typeof iti !== 'undefined') {
				$('#customer_company_number_full').val(iti.getNumber());
			}
			if (typeof customer_representative_contact !== 'undefined') {
				$('#customer_representative_contact_full').val(customer_representative_contact.getNumber());
			}
			if (typeof delivery_contact_person_number !== 'undefined') {
				$('#delivery_contact_person_number_full').val(delivery_contact_person_number.getNumber());
			}
			if (typeof freight_agent_contact_number !== 'undefined') {
				$('#freight_agent_contact_number_full').val(freight_agent_contact_number.getNumber());
			}
			if (typeof transporting_driver_contact_number !== 'undefined') {
				$('#transporting_driver_contact_number_full').val(transporting_driver_contact_number.getNumber());
			}

			const elementsByClass = document.getElementsByClassName('transport_type');

			if (elementsByClass.length > 0) {
				Array.from(elementsByClass).forEach(function(element) {
					element.disabled = false;
				});
			}

			const deposit_received_asClass = document.getElementsByClassName('deposit_received_as');

			if (deposit_received_asClass.length > 0) {
				Array.from(deposit_received_asClass).forEach(function(element) {
					element.disabled = false;
				});
			}


			$('#airline').prop('disabled', false).trigger('change');
			$('#currency').prop('disabled', false).trigger('change');
			$('#deposit_aganist_vehicle').prop('disabled', false).trigger('change');
			$('#vin_multiple').prop('disabled', false).trigger('change');
			$('#brn_file').prop('disabled', false).trigger('change');
			$('#signed_pfi').prop('disabled', false).trigger('change');
			$('#signed_contract').prop('disabled', false).trigger('change');
			$('#payment_receipts').prop('disabled', false).trigger('change');
			$('#noc').prop('disabled', false).trigger('change');
			$('#enduser_trade_license').prop('disabled', false).trigger('change');
			$('#enduser_passport').prop('disabled', false).trigger('change');
			$('#enduser_contract').prop('disabled', false).trigger('change');
			$('#vehicle_handover_person_id').prop('disabled', false).trigger('change');
			const isBatchElement = document.getElementById('is_batch');
			if (isBatchElement) {
				isBatchElement.disabled = false;
			}
			const batchElement = document.getElementById('batch');
			if (batchElement) {
				batchElement.disabled = false;
			}
			var table = document.getElementById('myTable');
			if (table) {
				var selects = table.querySelectorAll('select');
				selects.forEach(function(input) {
					input.disabled = false;
				});
			}
			$('.dynamicselect2').prop('disabled', false);
			const formData = new FormData(form);
			formData.append('comments', JSON.stringify(comments));

			fetch(form.action, {
				method: form.method,
				body: formData,
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest'
				}
			}).then(response => {
				if (!response.ok) {
					return response.text().then(text => {
						try {
							const errorData = JSON.parse(text);
							throw new Error(errorData.message || text);
						} catch (e) {
							throw new Error(text);
						}
					});
				}
				return response.json();
			}).then(data => {
				if (data.success) {
					window.location.href = `{{ url('work-order-info') }}/${type}`;
				} else {
					throw new Error(data.message);
				}
			}).catch(error => {
				alert(error.message);
				// if (error.message === "Can't edit the work order because the sales support confirmed the data.") {
				// 	document.querySelectorAll('#WOForm #submit').forEach(function(element) {
				// 		element.disabled = true;
				// 	});
				// 	const submitFromTopButton = document.getElementById('submit-from-top');
				// 	if (submitFromTopButton) {
				// 		submitFromTopButton.disabled = true;
				// 		submitFromTopButton.classList.add('disabled'); 
				// 	}
				// }
				console.error('Form submission error:', error);
			}).finally(() => {
				$('#overlay').hide();
			});
		}
	});

	function sanitizeQuantity(input) {
		let value = input.value;
		value = value.replace(/[^0-9]/g, '');
		input.value = value;
	}

	function sanitizeAmount(input) {
		let value = input.value;
		value = value.replace(/[^0-9.]/g, '');

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
		if (totalAmount != '' && amountReceived != '' && selectedDepositReceivedValue != '') {
			balanceAmount = Number(totalAmount) - Number(amountReceived);
		}
		document.getElementById('balance_amount').value = balanceAmount;
	}



	function resetRowIndexes() {
		$(".addon_outer").find(".addon_input_outer_row").each(function(index, element) {
			var newIndex = index + 1;
			$(element).attr('id', `addon_row_${newIndex}`);
			$(element).find('select')
				.attr('id', `addons_${newIndex}`)
				.data('index', newIndex);
			$(element).find('input[type="number"]').attr('id', `addon_quantity_${newIndex}`);
			$(element).find('textarea').attr('id', `addon_description_${newIndex}`);

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
				$('.addondynamicselect2').each(function() {
					var $dropdown = $(this);
					var currentVal = $dropdown.val();

					$dropdown.empty();

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

					$dropdown.select2({
						allowClear: true,
						maximumSelectionLength: 1,
						placeholder: "Choose Addon",
					});

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
											@foreach($addons as $addon)
												<option value="{{ e($addon->addon_code . ' - ' . $addon->addon_name) }}">
													{{ e($addon->addon_code . ' - ' . $addon->addon_name) }}
												</option>											
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
								<label class="col-form-label text-md-end">Addon Custom Details :</label>
								<textarea name="addon_description[]" id="addon_description_${index}" rows="4" class="form-control" placeholder="Enter Addon Custom Details"></textarea>
							</div>
							<div class="col-xxl-1 col-lg-1 col-md-1 add_del_btn_outer_addon">
								<a class="btn_round_big remove_node_btn_frm_field_addon" title="Remove Row" style="margin-top:50%;">
									<i class="fas fa-trash-alt"></i>
								</a>
							</div>
						</div>
					</div>
				`);

		$(".addon_outer").append(newRow);

		$(`#addons_${index}`).select2({
			allowClear: true,
			maximumSelectionLength: 1,
			placeholder: "Choose Addon",
		});

		disableAddonSelectedOptions();
	}


	function addChild() {
		var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;

		if (getAvailableOptions().length > 0) {
			if (index <= addedVins.length) {
				var options = addedVins.map(vin => `<option value="${vin}">${vin}</option>`).join('');
				var newRow = $(`
							<div class="row form_field_outer_row" id="${index}">
								<div class="col-xxl-11 col-lg-11 col-md-11">
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

				$(".form_field_outer").append(newRow);

				$(`#boe_vin_${index}`).select2({
					allowClear: true,
					placeholder: "Choose VIN Per BOE",
				});

				// $('.dynamicselect2').each(function() {
				// 	$(this).rules('add', {
				// 		required: true,
				// 		allVinsSelected: true,
				// 		messages: {
				// 			required: "This field is required."
				// 		}
				// 	});
				// });
				if (authUserPermission === 'true') {
					$('.dynamicselect2').prop('disabled', true);
					$('.remove_node_btn_frm_field').prop('disabled', true);
					$('.add_new_frm_field_btn').prop('disabled', true);
				}
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
		$(".form_field_outer").find(".form_field_outer_row").each(function(index, element) {
			var newIndex = index + 1;
			$(element).attr('id', newIndex);

			$(element).find('label').attr('for', `boe_vin_${newIndex}`).text(`VIN per BOE: ${newIndex}`);
			$(element).find('select')
				.attr('id', `boe_vin_${newIndex}`)
				.attr('name', `boe[${newIndex}][vin][]`)
				.data('index', newIndex);

			$(`#boe_vin_${newIndex}`).select2({
				allowClear: true,
				placeholder: "Choose VIN Per BOE",
			});
		});

		disableAddonSelectedOptions();
	}

	function disableAddonSelectedOptions() {
		var selectedOptions = [];
		$(".addondynamicselect2").each(function() {
			$(this).find('option:selected').each(function() {
				selectedOptions.push($(this).val());
			});
		});

		$(".addondynamicselect2").each(function() {
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
		});
	}

	function disableSelectedOptions() {
		var selectedOptions = [];
		$(".dynamicselect2").each(function() {
			$(this).find('option:selected').each(function() {
				selectedOptions.push($(this).val());
			});
		});

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

			// $select.select2(); 
		});
	}

	function addVIN() {
		var selectedVIN = $("#vin_multiple").val();
		if (selectedVIN != '' && selectedVIN.length > 0) {
			for (var j = 0; j < selectedVIN.length; j++) {
				for (var i = 0; i < vins.length; i++) {
					if (vins[i].vin != null && vins[i].vin == selectedVIN[j]) {
						var data = {
							id: '',
							vehicle_id: vins[i]?.id ?? '',
							vin: vins[i].vin ?? '',
							brand: vins[i]?.variant?.master_model_lines?.brand?.brand_name ?? '',
							variant: vins[i]?.variant?.name ?? '',
							engine: vins[i]?.engine ?? '',
							model_description: vins[i]?.variant?.model_detail ?? '',
							model_year: vins[i]?.variant?.my ?? '',
							model_year_to_mention_on_documents: vins[i]?.variant?.my ?? '',
							steering: vins[i]?.variant?.steering ?? '',
							exterior_colour: vins[i]?.exterior?.name ?? '',
							interior_colour: vins[i]?.interior?.name ?? '',
							warehouse: vins[i]?.warehouse_location?.name ?? '',
							territory: vins[i]?.territory ?? '',
							preferred_destination: '',
							import_document_type: vins[i]?.document?.import_type ?? '',
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
			if (index > 0) {
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
		var tableBody = document.querySelector('#myTable tbody');

		var firstRow = document.createElement('tr');
		firstRow.style.borderTop = '2px solid #a6a6a6';
		firstRow.className = 'first-row';
		var secondRow = document.createElement('tr');
		var thirdRow = document.createElement('tr');
		var lastRow = document.createElement('tr');

		var removeIconCell = createCellWithRemoveButton();

		var vinCell = document.createElement('td');
		vinCell.innerHTML = '<input type="hidden" name="vehicle[' + data.vehicle_id + '][id]" value="' + (data.id) + '">' +
			'<input type="hidden" name="vehicle[' + data.vehicle_id + '][vehicle_id]" value="' + (data.vehicle_id) + '">' +
			'<input type="hidden" name="vehicle[' + data.vehicle_id + '][vin]" value="' + (data.vin) + '">' +
			(data.vin);
		vinCell.dataset.vin = data.vin;

		var brandCell = createEditableCell(data.brand, 'Enter Brand', 'vehicle[' + data.vehicle_id + '][brand]');
		var variantCell = createEditableCell(data.variant, 'Enter Variant', 'vehicle[' + data.vehicle_id + '][variant]');
		var engineCell = createEditableCell(data.engine, 'Enter Engine', 'vehicle[' + data.vehicle_id + '][engine]');
		var modelDescriptionCell = createEditableCell(data.model_description, 'Enter Model Description', 'vehicle[' + data.vehicle_id + '][model_description]');
		var modelYearCell = createEditableCell(data.model_year, 'Enter Model Year', 'vehicle[' + data.vehicle_id + '][model_year]');
		var modelYearToMentionOnDocumentsCell = createEditableCell(data.model_year_to_mention_on_documents, 'Enter Model Year to mention on Documents', 'vehicle[' + data.vehicle_id + '][model_year_to_mention_on_documents]');
		var steeringCell = createEditableCell(data.steering, 'Enter Steering', 'vehicle[' + data.vehicle_id + '][steering]');
		var exteriorCell = createEditableCell(data.exterior_colour, 'Enter Exterior Colour', 'vehicle[' + data.vehicle_id + '][exterior_colour]');
		var interiorColorCell = createEditableCell(data.interior_colour, 'Enter Interior Colour', 'vehicle[' + data.vehicle_id + '][interior_colour]');
		var warehouseCell = createEditableCell(data.warehouse, 'Enter Warehouse', 'vehicle[' + data.vehicle_id + '][warehouse]');
		var territoryCell = createEditableCell(data.territory, 'Enter Territory', 'vehicle[' + data.vehicle_id + '][territory]');
		var preferredDestinationCell = createEditableCell(data.preferred_destination, 'Enter Preferred Destination', 'vehicle[' + data.vehicle_id + '][preferred_destination]');
		var importTypeCell = createEditableCell(data.import_document_type, 'Enter Import Document Type', 'vehicle[' + data.vehicle_id + '][import_document_type]');
		var ownershipCell = createEditableCell(data.ownership_name, 'Enter Ownership', 'vehicle[' + data.vehicle_id + '][ownership_name]');
		var CertificationPerVINCell = createEditableSelect2Cell(data.vin, data.vehicle_id, data.certification_per_vin);
		if (type == 'export_cnf') {
			var shipmentCell = createEditableSelect2ShipmentCell(data.vin, data.vehicle_id, data.shipment);
			// var shipmentCell = createEditableCell(data.shipment, 'Enter Shipment','vehicle['+data.vehicle_id+'][shipment]');
		}

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
		if (type == 'export_cnf') {
			firstRow.appendChild(shipmentCell);
		}

		var emptyLabelCell = document.createElement('td');
		emptyLabelCell.colSpan = 1;
		emptyLabelCell.textContent = '';

		var modificationLabelCell = document.createElement('td');
		modificationLabelCell.colSpan = 1;
		modificationLabelCell.textContent = 'Modification/Jobs';

		var modificationInputCell = document.createElement('td');
		if (type == 'export_cnf') {
			modificationInputCell.colSpan = 16;
		} else {
			modificationInputCell.colSpan = 15;
		}
		var modificationInputElement = document.createElement('input');
		modificationInputElement.name = 'vehicle[' + data.vehicle_id + '][modification_or_jobs_to_perform_per_vin]';
		modificationInputElement.type = 'text';
		modificationInputElement.placeholder = 'Enter Modification Or Jobs to Perform Per VIN';
		modificationInputElement.style.border = 'none';
		modificationInputElement.style.width = '100%';
		modificationInputElement.value = data.modification_or_jobs_to_perform_per_vin;
		modificationInputCell.appendChild(modificationInputElement);

		$(modificationInputElement).rules('add', {
			noSpaces: true,
			messages: {
				noSpaces: "No leading or trailing spaces allowed."
			}
		});

		secondRow.appendChild(emptyLabelCell);
		secondRow.appendChild(modificationLabelCell);
		secondRow.appendChild(modificationInputCell);

		var emptyLabelThirdRowCell = document.createElement('td');
		emptyLabelThirdRowCell.colSpan = 1;
		emptyLabelThirdRowCell.textContent = '';

		var specialRequestLabelCell = document.createElement('td');
		specialRequestLabelCell.colSpan = 1
		specialRequestLabelCell.textContent = 'Special Request/Remarks';

		var specialRequestInputCell = document.createElement('td');
		if (type == 'export_cnf') {
			specialRequestInputCell.colSpan = 16;
		} else {
			specialRequestInputCell.colSpan = 15;
		}
		var specialRequestInputElement = document.createElement('input');
		specialRequestInputElement.name = 'vehicle[' + data.vehicle_id + '][special_request_or_remarks]';
		specialRequestInputElement.type = 'text';
		specialRequestInputElement.placeholder = 'Special Request or Remarks (Clean Car/ Inspec Damage/ Etc) Salesman Insight Colum Per VIN';
		specialRequestInputElement.style.border = 'none';
		specialRequestInputElement.style.width = '100%';
		specialRequestInputElement.value = data.special_request_or_remarks;
		specialRequestInputCell.appendChild(specialRequestInputElement);
		$(specialRequestInputCell).rules('add', {
			noSpaces: true,
			messages: {
				noSpaces: "No leading or trailing spaces allowed."
			}
		});

		thirdRow.appendChild(emptyLabelThirdRowCell);
		thirdRow.appendChild(specialRequestLabelCell);
		thirdRow.appendChild(specialRequestInputCell);

		var createAddon = createAddonCell(data.vehicle_id);
		if (type == 'export_cnf') {
			createAddon.colSpan = 18;
		} else {
			createAddon.colSpan = 17;
		}
		lastRow.appendChild(createAddon);

		tableBody.appendChild(firstRow);
		tableBody.appendChild(secondRow);
		tableBody.appendChild(thirdRow);

		$(firstRow).data('vin', data.vin);

		$(thirdRow).data('vin', data.vin);

		var allVehicleRows = [firstRow, secondRow, thirdRow];
		var addonIndex = 0;
		var currentRow = thirdRow;
		if (data.addons && data.addons.length > 0) {
			for (var j = 0; j < data.addons.length; j++) {

				var DateToBeformat = new Date(data.addons[j].created_at);

				var optionsDate = {
					day: '2-digit',
					month: 'short',
					year: 'numeric'
				};
				var optionsTime = {
					hour: '2-digit',
					minute: '2-digit',
					second: '2-digit'
				};

				var formattedDate = DateToBeformat.toLocaleDateString('en-GB', optionsDate);
				var formattedTime = DateToBeformat.toLocaleTimeString('en-GB', optionsTime);

				var addonDate = formattedDate + ', ' + formattedTime;


				var addonId = data.addons[j].id;
				var addonValue = data.addons[j].addon_code;
				var addonQuantity = data.addons[j].addon_quantity;
				var addonDescription = data.addons[j].addon_description;
				if (addonValue != null) {
					currentRow = drawTableAddon(allVehicleRows, currentRow, data, addonIndex, addonId, addonDate, addonValue, addonQuantity, addonDescription);
					addonIndex++;
				}
			}
		}

		$('.addon_input_outer_row').each(function() {
			var addonId = $(this).attr('id').split('_')[2];
			var addonDate = '';
			var addonValue = $(`#addons_${addonId}`).val();
			var addonQuantity = $(`#addon_quantity_${addonId}`).val();
			var addonDescription = $(`#addon_description_${addonId}`).val();
			if (addonValue != null) {
				currentRow = drawTableAddon(allVehicleRows, currentRow, data, addonIndex, addonId, addonDate, addonValue, addonQuantity, addonDescription);
				addonIndex++;
			}
		});
		tableBody.appendChild(lastRow);
		allVehicleRows.push(lastRow);
		$(removeIconCell).find('.remove-row').data('rows', allVehicleRows);
	}

	function drawTableAddon(allVehicleRows, thirdRow, data, addonIndex, addonId, addonDate, addonValue, addonQuantity, addonDescription) {
		var removeAddonCell = createAddonRemoveButton();

		var addonRow = document.createElement('tr');
		addonRow.className = data.vehicle_id;

		var serviceBreakdownLabelCell = document.createElement('td');
		serviceBreakdownLabelCell.colSpan = 1;
		serviceBreakdownLabelCell.textContent = 'Service Breakdown';

		var serviceBreakdownDateLabelCell = document.createElement('td');
		serviceBreakdownDateLabelCell.colSpan = 1;
		serviceBreakdownDateLabelCell.textContent = addonDate;

		var addonValueCell = document.createElement('td');
		addonValueCell.colSpan = 2;
		addonValueCell.textContent = addonValue;

		var addonQuantityCell = document.createElement('td');
		addonQuantityCell.colSpan = 1;
		addonQuantityCell.innerHTML = '<input class="child_addon_id_' + data.vehicle_id + '" type="hidden" name="vehicle[' + data.vehicle_id + '][addons][' + addonIndex + '][id]" value="' + (addonId ?? '') + '">' +
			'<input type="hidden" class="child_addon_' + data.vehicle_id + '" name="vehicle[' + data.vehicle_id + '][addons][' + addonIndex + '][addon_code]" value="' + addonValue + '" id="addons_' + data.vehicle_id + '_' + addonIndex + '">' +
			'<div class="input-group">' +
			'<div class="input-group-append">' +
			'<span style="border:none;background-color:#fafcff;font-size:12px;" class="input-group-text widthinput">Qty</span>' +
			'</div>' +
			'<input name="vehicle[' + data.vehicle_id + '][addons][' + addonIndex + '][addon_quantity]" style="border:none;font-size:12px;" type="text" value="' + (addonQuantity ?? '') + '" class="form-control widthinput" id="addon_quantity_' + data.vehicle_id + '_' + addonIndex + '" placeholder="Addon Quantity">' +
			'</div>';

		var addonDescriptionCell = document.createElement('td');
		if (type == 'export_cnf') {
			addonDescriptionCell.colSpan = 14;
		} else {
			addonDescriptionCell.colSpan = 13;
		}
		addonDescriptionCell.innerHTML = '<input name="vehicle[' + data.vehicle_id + '][addons][' + addonIndex + '][addon_description]" style="border:none;font-size:12px;" type="text" value="' + (addonDescription ?? '') + '" class="form-control widthinput" id="addon_description_' + data.vehicle_id + '_' + addonIndex + '" placeholder="Enter Addon Custom Details">';

		addonRow.appendChild(removeAddonCell);
		addonRow.appendChild(serviceBreakdownLabelCell);
		addonRow.appendChild(serviceBreakdownDateLabelCell);
		addonRow.appendChild(addonValueCell);
		addonRow.appendChild(addonQuantityCell);
		addonRow.appendChild(addonDescriptionCell);

		allVehicleRows.push(addonRow);

		thirdRow.insertAdjacentElement('afterend', addonRow);
		$("#addon_quantity_" + data.vehicle_id + "_" + addonIndex).rules('add', {
			digits: true,
			min: 1
		});
		$("#addon_description_" + data.vehicle_id + "_" + addonIndex).rules('add', {
			noSpaces: true,
		});
		return addonRow;
	}

	$('#myTable').on('click', '.remove-row', function() {

		var vin = $(this).closest('tr').data('vin');
		if (vin) {
			$(".dynamicselect2").each(function() {
				var selectElement = $(this);
				selectElement.find(`option[value='${vin}']`).prop('selected', false).remove();
				selectElement.trigger('change');
			});
			$('select option[value="' + vin + '"]').prop('disabled', false);
		}
		var rows = $(this).data('rows');
		if (rows) {
			$(rows).each(function() {
				$(this).remove();
			});
		}
		findAllVINs();
	});
	$('#myTable').on('click', '.remove-addon-row', function() {
		var addon = $(this).closest('tr');
		var className = addon.attr('class');

		addon.remove();

		var allVehicleRows = $('#myTable tr.' + className);
		allVehicleRows.each(function(index) {
			var row = $(this);
			row.find('.child_addon_' + className).attr('name', 'vehicle[' + className + '][addons][' + index + '][addon_code]').attr('id', 'addons_' + className + '_' + index);

			row.find('div.input-group input[type="text"]').attr('name', 'vehicle[' + className + '][addons][' + index + '][addon_quantity]').attr('id', 'addon_quantity_' + className + '_' + index);

			row.find('td input[id^="addon_description_"]').attr('name', 'vehicle[' + className + '][addons][' + index + '][addon_description]').attr('id', 'addon_description_' + className + '_' + index);

			row.find('.child_addon_id_' + className).attr('name', 'vehicle[' + className + '][addons][' + index + '][id]');

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
	$('#myTable').on('click', '.create-addon-row', function() {
		var dataId = $(this).closest('td').data('id');

		var addonIndex = 0;
		$('.' + dataId).each(function(index) {
			addonIndex = index + 1;
		});
		var addonValue = '';
		var addonQuantity = '';
		var addonDescription = '';

		var removeAddonCell = createAddonRemoveButton();

		var addonRow = document.createElement('tr');
		addonRow.className = dataId;

		var serviceBreakdownLabelCell = document.createElement('td');
		serviceBreakdownLabelCell.colSpan = 1;
		serviceBreakdownLabelCell.textContent = 'Service Breakdown';

		var serviceBreakdownDateLabelCell = document.createElement('td');
		serviceBreakdownDateLabelCell.colSpan = 1;
		serviceBreakdownDateLabelCell.textContent = '';

		var addonValueCell = document.createElement('td');
		addonValueCell.colSpan = 2;
		addonValueCell.innerHTML = '<select name="vehicle[' + dataId + '][addons][' + addonIndex + '][addon_code]" id="addons_' + dataId + '_' + addonIndex + '" class="child_addon_' + dataId + ' form-control widthinput dynamicselectaddon" data-parant="' + dataId + '" multiple="true">' +
			'@foreach($addons as $addon)<option value="{{$addon->addon_code}} - {{$addon->addon_name}}">{{$addon->addon_code}} - {{$addon->addon_name}}</option>@endforeach' +
			'@foreach($charges as $charge)<option value="{{$charge->addon_code}} - {{$charge->addon_name}}">{{$charge->addon_code}} - {{$charge->addon_name}}</option>@endforeach' +
			'</select>';
		var addonQuantityCell = document.createElement('td');
		addonQuantityCell.colSpan = 1;
		addonQuantityCell.innerHTML = '<div class="input-group">' +
			'<div class="input-group-append">' +
			'<span style="border:none;background-color:#fafcff;font-size:12px;" class="input-group-text widthinput">Qty</span>' +
			'</div>' +
			'<input name="vehicle[' + dataId + '][addons][' + addonIndex + '][addon_quantity]" style="border:none;font-size:12px;" type="text" value="' + (addonQuantity ?? '') + '" class="form-control widthinput" id="addon_quantity_' + dataId + '_' + addonIndex + '" placeholder="Addon Quantity">' +
			'</div>';

		var addonDescriptionCell = document.createElement('td');
		if (type == 'export_cnf') {
			addonDescriptionCell.colSpan = 13;
		} else {
			addonDescriptionCell.colSpan = 14;
		}
		addonDescriptionCell.innerHTML = '<input name="vehicle[' + dataId + '][addons][' + addonIndex + '][addon_description]" style="border:none;font-size:12px;" type="text" value="' + (addonDescription ?? '') + '" class="form-control widthinput" id="addon_description_' + dataId + '_' + addonIndex + '" placeholder="Enter Addon Custom Details">';

		addonRow.appendChild(removeAddonCell);
		addonRow.appendChild(serviceBreakdownLabelCell);
		addonRow.appendChild(serviceBreakdownDateLabelCell);
		addonRow.appendChild(addonValueCell);
		addonRow.appendChild(addonQuantityCell);
		addonRow.appendChild(addonDescriptionCell);

		var parentElementRemove = $(this).closest('tr');
		var firstRemoveRowButton = parentElementRemove.prevAll('tr').has('.remove-row').first();

		if (firstRemoveRowButton.length) {
			firstRemoveRowButton.after(addonRow);

			var rowsData = firstRemoveRowButton.find('.remove-row').data('rows') || [];
			rowsData.push(addonRow);
			firstRemoveRowButton.find('.remove-row').data('rows', rowsData);
		} else {
			parentElementRemove.after(addonRow);
		}
		var parentElement = this.parentElement.parentElement;
		parentElement.insertAdjacentElement('beforebegin', addonRow);
		$('#addons_' + dataId + '_' + addonIndex).select2({
			allowClear: true,
			maximumSelectionLength: 1,
			placeholder: "Choose Addon"
		});
		$('.dynamicselectaddon').each(function() {
			$(this).rules('add', {
				required: true,
				messages: {
					required: "This field is required."
				}
			});
		});
		$("#addon_quantity_" + dataId + "_" + addonIndex).rules('add', {
			digits: true,
			min: 1
		});
		$("#addon_description_" + dataId + "_" + addonIndex).rules('add', {
			noSpaces: true,
		});
		vehicleAddonDropdown(dataId);
	});

	function vehicleAddonDropdown(dataId) {
		var selectedAddonValues = [];
		$('.' + dataId).find('.child_addon_' + dataId).each(function() {
			if ($(this).is('input')) {
				selectedAddonValues.push($(this).val());
			} else if ($(this).is('select') && $(this).val()[0] != undefined) {
				selectedAddonValues.push($(this).val()[0]);
			}
		});
		$('.' + dataId).find('select').each(function() {
			var selectElement = $(this);
			var currentSelectedValue = selectElement.val();
			selectElement.find('option').each(function() {
				var optionValue = $(this).val();
				if (selectedAddonValues.includes(optionValue) && optionValue !== currentSelectedValue[0]) {
					$(this).prop('disabled', true);
				} else {
					$(this).prop('disabled', false);
				}
			});
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
		if (addedVins.length > 1) {

			$("#boe-div").show();
			var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
			if (index == 1) {
				addChild();
			}
		} else {
			$("#boe-div").hide();
		}
		if (selectedDepositReceivedValue == 'custom_deposit') {
			setDepositAganistVehicleDropdownOptions();
		}
	}
	document.addEventListener('DOMContentLoaded', function() {
		const table = document.getElementById('myTable');

		document.addEventListener('DOMContentLoaded', function() {
			const removeButtons = document.querySelectorAll('.remove-btn');

			removeButtons.forEach(button => {
				button.addEventListener('click', function(event) {
					const row = event.target.closest('tr');
					row.remove();
				});
			});
		});
	});

	function createEditableCell(value, placeHolder, name) {
		var cell = document.createElement('td');
		var inputElement = document.createElement('input');
		inputElement.type = 'text';
		inputElement.placeholder = placeHolder;
		inputElement.name = name;
		inputElement.value = value;
		inputElement.style.border = 'none';

		var uniqueId = name.replace(/[\[\]]+/g, '_');
		inputElement.id = uniqueId;

		cell.appendChild(inputElement);

		setTimeout(function() {
			var rules = {
				noSpaces: true,
				messages: {
					noSpaces: "No leading or trailing spaces allowed."
				}
			};

			if (name.includes('model_year') || name.includes('model_year_to_mention_on_documents')) {
				rules.year4digits = true;
			}

			$('#' + uniqueId).rules('add', rules);
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
		cell.setAttribute('data-id', vehicle_id);
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

		var options = [{
				value: 'rta_without_number_plate',
				text: 'RTA Without Number Plate'
			},
			{
				value: 'rta_with_number_plate',
				text: 'RTA With Number Plate'
			},
			{
				value: 'certificate_of_origin',
				text: 'Certificate Of Origin'
			},
			{
				value: 'certificate_of_conformity',
				text: 'Certificate Of Conformity'
			},
			{
				value: 'qisj_inspection',
				text: 'QISJ Inspection'
			},
			{
				value: 'eaa_inspection',
				text: 'EAA Inspection'
			}
		];
		options.forEach(function(optionData) {
			var option = document.createElement('option');
			option.value = optionData.value;
			option.textContent = optionData.text;

			if (certification_per_vin == optionData.value) {
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

		$(selectElement).val(certification_per_vin).trigger('change');

		return cell;
	}

	function createEditableSelect2ShipmentCell(vin, vehicle_id, shipment) {
		// var shipmentCell = createEditableCell(data.shipment, 'Enter Shipment','vehicle['+data.vehicle_id+'][shipment]');
		var cell = document.createElement('td');
		var selectElement = document.createElement('select');
		selectElement.id = 'shipment_' + vin;
		selectElement.name = 'vehicle[' + vehicle_id + '][shipment]';
		selectElement.className = 'form-control widthinput';
		selectElement.multiple = true;
		selectElement.style.width = '100%';

		var options = [{
				value: '20 Ft',
				text: '20 Ft'
			},
			{
				value: '40 Ft 2 Car Loading',
				text: '40 Ft 2 Car Loading'
			},
			{
				value: '40 Ft 3 Car Loading',
				text: '40 Ft 3 Car Loading'
			},
			{
				value: '40 Ft 4 Car Loading',
				text: '40 Ft 4 Car Loading'
			},
			{
				value: 'RORO',
				text: 'RORO'
			},
		];
		options.forEach(function(optionData) {
			var option = document.createElement('option');
			option.value = optionData.value;
			option.textContent = optionData.text;

			if (shipment == optionData.value) {
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
				var selectedValues = shipment.map(function(value) {
					return options.find(option => option.value === value);
				});
				callback(selectedValues);
			}
		});

		$(selectElement).val(shipment).trigger('change');

		return cell;
	}

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

	function checkValue() {
		selectedCustomerEmail = $('#customer_email').val();
		selectedCustomerAddress = $('#customer_address').val();
		selectedCustomerContact = $('#customer_company_number').val();
		$('#customer_type').val('new');
		var textInput = document.getElementById('textInput');
		var Other = document.getElementById('Other');
		var switchToDropdown = document.getElementById('switchToDropdown');

		var selectedCustomerName = $('#customer_name').val();
		$('#customer_reference_id').val(selectedCustomerName);

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

		var newCustomerName = $('#textInput').val();
		$('#customer_reference_id').val(newCustomerName);

		$('#customer_name').next('.select2-container').show();
		textInput.style.display = 'none';
		Other.style.display = 'inline';
		switchToDropdown.style.display = 'none';

		var selectedCustomerName = $('#customer_name').val();
		if (selectedCustomerName.length > 0) {
			$('#customer_address').val(selectedCustomerAddress);
			$('#customer_email').val(selectedCustomerEmail);
			$('#customer_company_number').val(selectedCustomerContact);
		} else {
			$('#customer_address').val('');
			$('#customer_email').val('');
			$('#customer_company_number').val('');
		}
	}

	function isSOExist() {
		console.log('inside isSOExist');
		var SONumber = $('#so_number').val().trim();
		var editWoId = '';
		if (workOrder != null) {
			editWoId = workOrder.id;
		}
		console.log("isEdit is - " + editWoId);
		// Ensure the SO Number is valid (including custom validation isExistInSalesOrder)
		if (!$('#so_number').valid()) {
			console.log("SO number validation failed.");
			return; // Stop execution if validation fails
		}
		console.log("SO number validation passed. Proceeding with the AJAX call.");
		var selectedBatch = '';
		if ($('#batch').length && (type == 'export_exw' || type == 'export_cnf')) {
			selectedBatch = $('#batch').val();
		}
		// Call the additional AJAX request to process the SO Number
		$.ajax({
			url: '/check-so-number',
			method: 'POST',
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				so_number: SONumber,
				work_order_id: editWoId ? editWoId : null
			},
			success: function(response) {
				if (response.exists) {
					if (response.is_batch == '1') {
						document.getElementById('is_batch').checked = true;
						if (response.largest_batch != 0) {
							if (workOrder != null && workOrder.so_number == SONumber) {
								NextNum = response.largest_batch;
							} else {
								NextNum = response.largest_batch + 1;
							}
							document.getElementById('batch').value = "Batch " + NextNum;
							document.getElementById('batchDropdownSection').style.display = 'block';
						}
						isBatchChecked = true;
					} else {
						document.getElementById('is_batch').checked = false;
						document.getElementById('batch').value = null;
						document.getElementById('batchDropdownSection').style.display = 'none';
						isBatchChecked = false;
					}
					setWo();
					document.getElementById('is_batch').disabled = true;
					document.getElementById('batch').disabled = true;
				} else {
					document.getElementById('batch').value = null;
					document.getElementById('is_batch').checked = false;
					document.getElementById('batchDropdownSection').style.display = 'none';
					isBatchChecked = false;
					setWo();
					document.getElementById('is_batch').disabled = false;
					document.getElementById('batch').disabled = false;
				}
			},
			error: function(xhr) {
				console.error(xhr.responseText);
			}
		});
	}

	function setWo() {
		var SONumber = $('#so_number').val().trim();
		var selectedBatch = '';

		if (type == 'export_exw' || type == 'export_cnf') {
			selectedBatch = $('#batch').val();
		}

		if (SONumber === '') {
			document.getElementById('wo_number').value = '';
			return;
		}

		let parts = SONumber.split("SO-");
		if (parts.length !== 2 || parts[0] !== '') {
			document.getElementById('wo_number').value = '';
			return;
		}

		let numberPart = parts[1];
		if (numberPart === '' || numberPart.length !== 6) {
			document.getElementById('wo_number').value = '';
			return;
		}

		let WONumber = '';

		if (type === 'local_sale') {
			WONumber = "WO-" + numberPart + "-LS";
		} else {
			if (isBatchChecked) {
				let batchNumber = '';
				if (selectedBatch && typeof selectedBatch === 'string') {
					batchNumber = selectedBatch.replace(/\D/g, '');
				}
				if (selectedBatch === '' || batchNumber === '') {
					document.getElementById('wo_number').value = '';
					return;
				}

				let formattedBatchNumber = batchNumber.padStart(2, '0');

				WONumber = "WO-" + numberPart + "-B" + formattedBatchNumber;
			} else {
				WONumber = "WO-" + numberPart + "-SW";
			}
		}

		document.getElementById('wo_number').value = WONumber;
	}


	function setDepositAganistVehicleDropdownOptions() {
		var previouslySelectedValues = $('#deposit_aganist_vehicle').val() || [];
		$('#deposit_aganist_vehicle').empty();
		addedVins.forEach(function(vin) {
			$('#deposit_aganist_vehicle').append(new Option(vin, vin));
		});

		$('#deposit_aganist_vehicle').select2({
			allowClear: true,
			placeholder: "Choose Vehicle"
		});

		$('#deposit_aganist_vehicle').val(previouslySelectedValues.filter(function(value) {
			return addedVins.includes(value);
		})).trigger('change');
	}


	function updateCurrency() {
		var currency = document.getElementById("currency").value;
		var currencyText = document.querySelector("#currency option:checked").textContent;
		document.getElementById("amount_received_currency").textContent = currencyText;
		document.getElementById("balance_amount_currency").textContent = currencyText;
	}

	function sanitizeInput(input) {
		input.value = input.value.replace(/\s\s+/g, ' ');
	}

	function sanitizeNumberInput(input) {
		input.value = input.value.replace(/[^0-9]/g, '');
	}

	$('.delete-button').on('click', function() {
		var fileType = $(this).attr('data-file-type');
		if (confirm('Are you sure you want to Delete this item ?')) {
			if (fileType == 'BRN_File') {
				$('#brn_file_preview1').remove();
				$('#brn-file-file-delete').val(1);

			} else if (fileType == 'Signed_PFI') {
				$('#signed_pfi_preview1').remove();
				$('#signed-pfi-delete').val(1);

			} else if (fileType == 'Signed_Contract') {
				$('#signed_contract_preview1').remove();
				$('#signed-contract-delete').val(1);
			} else if (fileType == 'Payment_Receipts') {
				$('#payment_receipts_preview1').remove();
				$('#payment-receipts-file-delete').val(1);
			} else if (fileType == 'NOC') {
				$('#noc_preview1').remove();
				$('#noc-file-delete').val(1);

			} else if (fileType == 'Enduser_Trade_License') {
				$('#enduser_trade_license_preview1').remove();
				$('#enduser-trade-license-delete').val(1);
			} else if (fileType == 'Enduser_Passport') {
				$('#enduser_passport_preview1').remove();
				$('#enduser-passport-delete').val(1);
			} else if (fileType == 'Enduser_Contract') {
				$('#enduser_contract_preview1').remove();
				$('#enduser-contract-file-delete').val(1);
			} else if (fileType == 'Vehicle_Handover_Person_ID') {
				$('#vehicle_handover_person_id_preview1').remove();
				$('#vehicle-handover-person-id-file-delete').val(1);
			}
		}
	});

	function toggleBatchDropdown() {
		const isBatchCheckbox = document.getElementById('is_batch');

		if (isBatchCheckbox) {
			isBatchChecked = isBatchCheckbox.checked;

			const batchDropdown = document.getElementById('batchDropdownSection');

			if (isBatchChecked) {
				batchDropdown.style.display = 'block';
			} else {
				batchDropdown.style.display = 'none';
			}
		}
		setWo();
	}

	document.addEventListener('DOMContentLoaded', function() {
		const isBatchCheckbox = document.getElementById('is_batch');

		if (isBatchCheckbox) {
			toggleBatchDropdown();

			isBatchChecked = isBatchCheckbox.checked;
		}
		setWo();
	});

	function updateStyledComment() {
		let text = $('#new-comment').val();
		text = text.replace(/@\[(\w+)\]/g, '<span class="mention" style="color: blue;">@$1</span>');
		$('#styled-comment').html(text);
	}
</script>
@if ($hasAmountPermission)
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const fieldsToDisable = [
			'so_total_amount',
			'so_vehicle_quantity',
			'deposit_received_as',
			'amount_received',
			'balance_amount',
		];

		fieldsToDisable.forEach(function(field) {
			const elementsById = document.getElementById(field);
			const elementsByClass = document.getElementsByClassName(field);

			if (elementsById) {
				elementsById.readOnly = true;
			}

			if (elementsByClass.length > 0) {
				Array.from(elementsByClass).forEach(function(element) {
					element.disabled = true;
				});
			}
			$('#currency').prop('disabled', true).trigger('change');
			$('#deposit_aganist_vehicle').prop('disabled', true).trigger('change');
		});
	});
</script>
@endif
@if ($restrictExceptGeneral)
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const fieldsToDisable = [
			'transport_type',
			'brn',
			'container_number',
			'airline_reference_id',
			'airway_bill',
			'shipping_line',
			'forward_import_code',
			'trailer_number_plate',
			'transportation_company',
			'transporting_driver_contact_number',
			'airway_details',
			'transportation_company_details',
			'so_total_amount',
			'so_vehicle_quantity',
			'deposit_received_as',
			'amount_received',
			'balance_amount',
			'delivery_location',
			'delivery_contact_person',
			'delivery_date',
		];

		fieldsToDisable.forEach(function(field) {
			const elementsById = document.getElementById(field);
			const elementsByClass = document.getElementsByClassName(field);

			if (elementsById) {
				elementsById.readOnly = true;
			}

			if (elementsByClass.length > 0) {
				Array.from(elementsByClass).forEach(function(element) {
					element.disabled = true;
				});
			}
			$('#airline').prop('disabled', true).trigger('change');
			$('#currency').prop('disabled', true).trigger('change');
			$('#deposit_aganist_vehicle').prop('disabled', true).trigger('change');
			$('#vin_multiple').prop('disabled', true).trigger('change');
			$('#brn_file').prop('disabled', true).trigger('change');
			$('#signed_pfi').prop('disabled', true).trigger('change');
			$('#signed_contract').prop('disabled', true).trigger('change');
			$('#payment_receipts').prop('disabled', true).trigger('change');
			$('#noc').prop('disabled', true).trigger('change');
			$('#enduser_trade_license').prop('disabled', true).trigger('change');
			$('#enduser_passport').prop('disabled', true).trigger('change');
			$('#enduser_contract').prop('disabled', true).trigger('change');
			$('#vehicle_handover_person_id').prop('disabled', true).trigger('change');
		});
		var deleteButtons = document.querySelectorAll('.delete-button');
		deleteButtons.forEach(function(button) {
			button.disabled = true;
		});
	});
</script>
@endif

@endsection