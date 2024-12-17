@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
	.widthinput {
        height: 32px !important;
    }
	.btn-full-width {
		width: 100%;
		margin-top: 2px;
		margin-bottom: 2px;
	}
	.tooltip-container {
		position: relative;
		display: inline-block;
		cursor: pointer;
	}
	.tooltip-text {
		visibility: hidden;
		width: 300px; 
		background-color: #e1efff; 
		color: #000;
		text-align: left;
		border-radius: 5px;
		padding: 10px;
		position: absolute;
		z-index: 1;
		right: -320px; 
		top: 50%;
		transform: translateY(-50%);
		border: 1px solid #99c2ff; 
		font-size: 14px;
		opacity: 0;
		transition: opacity 0.3s ease-in-out;
		box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
	}
	.tooltip-header {
		font-weight: bold;
		background-color: #b4d2f7;
		padding: 5px;
		border-bottom: 1px solid #99c2ff;
		text-align: center;
	}
	.tooltip-body {
		padding: 10px;
		white-space: pre-line; 
	}
	.tooltip-container:hover .tooltip-text {
		visibility: visible;
		opacity: 1;
	}
	.btn-style {
		font-size:0.7rem!important;
		line-height: 0.1!important;
	}
    th {
		font-size:12px!important;
	}
	td {
		font-size:12px!important;
	}
	.form-label {
		margin-top: 0.5rem;
	}
	.light {
		background-color:#e6e6e6!important;
		font-weight: 700!important;
	}
	.dark {
		background-color:#d9d9d9!important;
		font-weight: 700!important;
	}
</style>
@section('content')
<div class="card-header">
	@php
		$canViewWOList = Auth::user()->hasPermissionForSelectedRole(['list-export-exw-wo','view-current-user-export-exw-wo-list',
			'list-export-cnf-wo','view-current-user-export-cnf-wo-list','list-export-local-sale-wo',
			'view-current-user-local-sale-wo-list','list-lto-wo']);
		$canCreateWO = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo','create-export-cnf-wo',
			'create-local-sale-wo']);
		$canViewWODetails = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details',
			'export-cnf-wo-details','current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
		$caneditWO = Auth::user()->hasPermissionForSelectedRole(['edit-all-export-exw-work-order',
			'edit-current-user-export-exw-work-order','edit-current-user-export-cnf-work-order','edit-all-export-cnf-work-order',
			'edit-all-local-sale-work-order','edit-current-user-local-sale-work-order']);
		$hasEditConfirmedPermission = Auth::user()->hasPermissionForSelectedRole(['edit-confirmed-work-order']);
		$canViewFinLog = Auth::user()->hasPermissionForSelectedRole(['view-finance-approval-history']);
		$canViewCOOLog = Auth::user()->hasPermissionForSelectedRole(['view-coo-approval-history']);
		$canChangeDocStatus = Auth::user()->hasPermissionForSelectedRole(['can-change-documentation-status']);
		$canViewDocLog = Auth::user()->hasPermissionForSelectedRole(['view-doc-status-log']);
		$canChangeWOStatus = Auth::user()->hasPermissionForSelectedRole(['can-change-status']);
		$canViewWOStatusLog = Auth::user()->hasPermissionForSelectedRole(['view-wo-status-log']);
		$canDeleteWO = Auth::user()->hasPermissionForSelectedRole(['delete-work-order']);
	@endphp
	@if ($canViewWOList && isset($type))
		<h4>
			@switch($type)
				@case('export_exw')
					Export EXW
					@break
				@case('export_cnf')
					Export CNF
					@break
				@case('local_sale')
					Local Sale
					@break
				@case('all')
					All
					@break
				@case('status_report')
					Status Report -
					@break
				@default
					Work Order Info
			@endswitch
			Work Order Info
		</h4>
	@endif
	@if ($canCreateWO && isset($type))
		@if($type == 'all' || $type == 'status_report')
			<a style="float: right;" class="btn btn-sm btn-success me-1" href="{{ route('work-order-create.create', 'local_sale') }}">
				<i class="fa fa-plus" aria-hidden="true"></i> New Local Sale Work Order 
			</a>
			<a style="float: right;" class="btn btn-sm btn-success me-1" href="{{ route('work-order-create.create', 'export_cnf') }}">
				<i class="fa fa-plus" aria-hidden="true"></i> New Export CNF Work Order 
			</a>
			<a style="float: right;" class="btn btn-sm btn-success me-1" href="{{ route('work-order-create.create', 'export_exw') }}">
				<i class="fa fa-plus" aria-hidden="true"></i> New Export EXW Work Order 
			</a>
		@else
			<a style="float: right;" class="btn btn-sm btn-success" href="{{ route('work-order-create.create', $type) }}">
				<i class="fa fa-plus" aria-hidden="true"></i> New 
				@switch($type)
					@case('export_exw')
						Export EXW
						@break
					@case('export_cnf')
						Export CNF
						@break
					@case('local_sale')
						Local Sale
						@break
				@endswitch
				Work Order 
			</a>
		@endif
	@endif
</div>
@if ($canViewWOList)
    <div class="tab-pane fade show" id="telephonic_interview">
        <div class="card-body">
			<div class="row">
				<input type="hidden" name="type" value="{{ $type ?? '' }}">				
				@php
					$filterOptions = [
						'status-filter' => [
							'label' => 'Status',
							'options' => $statuses,
							'selected' => $filters['status_filter'] ?? []
						],
						'sales-support-filter' => [
							'label' => 'Data Confirmation',
							'options' => $salesSupportDataConfirmations,
							'selected' => $filters['sales_support_filter'] ?? []
						],
						'finance-approval-filter' => [
							'label' => 'Fin. Approval',
							'options' => $financeApprovalStatuses,
							'selected' => $filters['finance_approval_filter'] ?? []
						],
						'coo-approval-filter' => [
							'label' => 'COO Approval',
							'options' => $cooApprovalStatuses,
							'selected' => $filters['coo_approval_filter'] ?? []
						],
						'docs-status-filter' => [
							'label' => 'Documentation',
							'options' => $docsStatuses,
							'selected' => $filters['docs_status_filter'] ?? []
						],
					];
				@endphp
				@foreach($filterOptions as $id => $filter)
					<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div">
						<div class="dropdown-option-div">
							<label for="{{ $id }}" class="col-form-label text-md-end">{{ __($filter['label']) }}</label>
							<select name="{{ $id }}" id="{{ $id }}" multiple class="form-control widthinput">
								@foreach($filter['options'] as $option)
									<option value="{{ $option }}" 
										{{ in_array($option, $filter['selected']) ? 'selected' : '' }}>
										{{ ucfirst($option) }}
									</option>
								@endforeach
							</select>
						</div>
					</div>
				@endforeach
				<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div">
					<button id="apply-filters" type="submit" class="btn btn-info btn-sm mb-3" style="margin-top:25px!important;">
						Save & Apply Filters
					</button>
				</div>
			</div>
			<div class="row">
				<div class="col-xxl-2 col-lg-6 col-md-6 ms-auto d-flex justify-content-end select-button-main-div">
					<div class="dropdown-option-div me-2"> <!-- Add spacing between input and button -->
						<label for="search" class="col-form-label text-md-end">Search</label>
						<input id="search" name="search" type="text" class="form-control widthinput" placeholder="Search" autocomplete="search" value="{{ isset($search) ? $search : '' }}">
					</div>
					<button id="apply_search" type="button" class="btn btn-info btn-sm mb-3" style="margin-top:25px!important;">
						Search
					</button>
				</div>
			</div>
			<br/>
			<div class="table-responsive dragscroll">
				<table class="my-datatable table table-striped table-editable" style="width:100%;">
					<thead>
						<tr>
							<th rowspan="2" class="dark">Action</th>
							<th rowspan="2" class="light">Sl No</th>
							@if(isset($type) && ($type == 'all' || $type == 'status_report'))
								<th rowspan="2" class="light">Type</th>
							@endif
							<th rowspan="2" class="light">Status</th>
							<th rowspan="2" class="light">Sales Support Data Confirmation</th>
							<th colspan="2" class="dark text-center">Approval Status</th>
							<th rowspan="2" class="light">Documentation Status</th>
							<th rowspan="2" class="light">Vehicle Modification Status</th>
							<th rowspan="2" class="light">PDI Status</th>
							<th rowspan="2" class="light">Delivery Status</th>
							<th rowspan="2" class="light">Sales Person</th>
							<th rowspan="2" class="light">SO No</th>
							<th rowspan="2" class="light">WO No</th>
							<th rowspan="2" class="light">Date</th>
							@if(isset($type) && in_array($type, ['export_exw', 'export_cnf', 'all', 'status_report']))
								<th rowspan="2" class="light">Batch</th>
							@endif
							@if(isset($type) && $type != 'status_report')
								<th colspan="4" class="dark text-center">Customer</th>
								<th colspan="3" class="light text-center">Customer Representative</th>
								@if(isset($type) && in_array($type, ['export_exw', 'all']))
									<th colspan="3" class="dark text-center">Freight Agent</th>
									<th rowspan="2" class="light">Delivery Advise</th>
									<th rowspan="2" class="light">Transfer Of Ownership</th>
								@endif
								@if(isset($type) && in_array($type, ['export_cnf', 'all']))
									<th rowspan="2" class="light">Cross Trade</th>
								@endif
								@if(isset($type) && in_array($type, ['local_sale', 'all']))
									<th rowspan="2" class="light">LTO</th>
								@endif
								@if(isset($type) && in_array($type, ['export_exw', 'export_cnf', 'all']))
									<th rowspan="2" class="light">Temporary Exit</th>
									<th rowspan="2" class="light">Port Of Loading</th>
									<th rowspan="2" class="light">Port Of Discharge</th>
									<th rowspan="2" class="light">Final Destination</th>
									<th rowspan="2" class="light">Transport Type</th>
									<th rowspan="2" class="light">BRN File</th>
									<th rowspan="2" class="light">Airline/Shipping Line/Trailer No.</th>
									<th rowspan="2" class="light">AWB/Container No./Transportation Company</th>
									<th rowspan="2" class="light">Airway Info/Fwd Import Code/Driver Contact No.</th>
									<th rowspan="2" class="light">BRN/Transportation Com. Info</th>
								@endif
								<th colspan="5" class="dark text-center">SO</th>
								<th colspan="4" class="light text-center">Delivery</th>
								@if(isset($type) && in_array($type, ['export_cnf', 'all']))
									<th rowspan="2" class="light">Preferred Shipping Line</th>
									<th rowspan="2" class="light">Bill of Lading</th>
									<th rowspan="2" class="light">Shipper</th>
									<th rowspan="2" class="light">Consignee</th>
									<th rowspan="2" class="light">Notify Party</th>
									<th rowspan="2" class="light">Special/In Transit/Other Requests</th>
								@endif
								<th rowspan="2" class="dark">Signed PFI</th>
								<th rowspan="2" class="dark">Signed Contract</th>
								<th rowspan="2" class="dark">Payment Receipts</th>
								<th rowspan="2" class="dark">NOC</th>
								<th colspan="3" class="light text-center">End User</th>
								<th rowspan="2" class="dark">Handover Person ID</th>
							@endif
							<th rowspan="2" class="light">Created By</th>
							<th rowspan="2" class="light">Created At</th>
							<th rowspan="2" class="dark">Last Updated By</th>
							<th rowspan="2" class="dark">Last Updated At</th>
							@if(isset($type) && $type != 'status_report')
								<th rowspan="2" class="dark">Sales Support Data Confirmation By</th>
								<th rowspan="2" class="dark">Sales Support Data Confirmation At</th>
								<th rowspan="2" class="dark">Total Number Of BOE</th>
								<th rowspan="2" class="dark">Has Claim</th>
							@endif
							<th rowspan="2" class="light">Vehilce Count</th>
						</tr>
						<tr>
							<td class="dark">Finance</td>
							<td class="dark">COO Office</td>
							@if(isset($type) && $type != 'status_report')
								<td class="dark">Name</td>
								<td class="dark">Email</td>
								<td class="dark">Contact</td>
								<td class="dark">Address</td>
								<td class="light">Name</td>
								<td class="light">Email</td>
								<td class="light">Contact</td>
								@if(isset($type) && in_array($type, ['export_exw', 'all']))
									<td class="dark">Name</td>
									<td class="dark">Email</td>
									<td class="dark">Contact</td>
								@endif
								<td class="dark">Vehicle Qty</td>
								<td class="dark">Currency</td>
								<td class="dark">Amount</td>
								<td class="dark">Deposit</td>
								<td class="dark">Balance</td>
								<td class="light">Location</td>
								<td class="light">Contact Person Name</td>
								<td class="light">Contact Person No.</td>
								<td class="light">Date</td>
								<td class="light">Trade License</td>
								<td class="light">Passport</td>
								<td class="light">Contract</td>
							@endif
						</tr>
					</thead>
					<tbody>
						@foreach ($datas as $key => $data)
							<tr data-id="{{$data->id ?? ''}}">
								<td class="no-click">
									<div class="dropdown">
										<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
											<i class="fa fa-bars" aria-hidden="true"></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-start">
											@if ($canViewWODetails)
												<li>
													<a title="View Details" class="btn btn-sm btn-info btn-full-width" href="{{ route('work-order.show', $data->id ?? '') }}">
														<i class="fa fa-eye" aria-hidden="true"></i> View Details
													</a>
												</li>
											@endif											
											@php
												$isDisabled = !$hasEditConfirmedPermission && $data->sales_support_data_confirmation_at != '';
											@endphp
											@if ($caneditWO)
												<li>
													<a title="Edit" class="btn btn-sm btn-info btn-full-width {{ $isDisabled ? 'disabled' : '' }}" 
														href="{{ $isDisabled ? 'javascript:void(0);' : route('work-order.edit', $data->id ?? '') }}">
														<i class="fa fa-edit" aria-hidden="true"></i> Edit
													</a>
												</li>
											@endif
											@if ($canViewFinLog)
												<li>
													<a title="Finance Approval History" class="btn btn-sm btn-info btn-full-width" href="{{ route('fetchFinanceApprovalHistory', $data->id) }}">
														<i class="fa fa-history" aria-hidden="true"></i> Fin. Approval Log
													</a>
												</li>
											@endif
											@if ($canViewCOOLog)
												<li>
													<a title="COO Office Approval History" class="btn btn-sm btn-info btn-full-width" href="{{ route('fetchCooApprovalHistory', $data->id) }}">
														<i class="fa fa-history" aria-hidden="true"></i> COO Approval Log
													</a>
												</li>
											@endif
											@if ($data->sales_support_data_confirmation_at && $data->finance_approval_status == 'Approved' && $data->coo_approval_status == 'Approved' && $canChangeDocStatus)
												<li>
													<a class="btn btn-sm btn-info btn-full-width" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateDocStatusModal_{{$data->id}}">
														<i class="fa fa-file" aria-hidden="true"></i> Update Doc Status
													</a>
												</li>
											@endif
											@if ($canViewDocLog)
												<li>
													<a title="Doc Status Log" class="btn btn-sm btn-info btn-full-width" href="{{ route('docStatusHistory', $data->id) }}">
														<i class="fas fa-eye"></i> Doc Status Log
													</a>
												</li>
											@endif
											@if ($canChangeWOStatus)
												<li>
													<a class="btn btn-sm btn-info btn-full-width" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateStatusModal_{{$data->id}}">
														<i class="fa fa-file" aria-hidden="true"></i> Update Status
													</a>
												</li>
											@endif
											@if ($canViewWOStatusLog)
												<li>
													<a title="Status Log" class="btn btn-sm btn-info btn-full-width" href="{{ route('woStatusHistory', $data->id) }}">
														<i class="fas fa-eye"></i> Status Log
													</a>
												</li>
											@endif
											@if ($canDeleteWO && $data->sales_support_data_confirmation_at == '')
												<li>
													<a 
														title="Delete" 
														class="btn btn-sm btn-info btn-full-width" 
														href="javascript:void(0);" 
														onclick="confirmDelete('{{ route('workorder.destroy', $data->id) }}', '{{ $data->wo_number }}')">
														<i class="fas fa-trash"></i> Delete
													</a>
												</li>
											@endif						
										</ul>
									</div>
									@include('work_order.export_exw.doc_status_update')
									@include('work_order.export_exw.status_update')
								</td>
								<td>{{ $datas->firstItem() + $loop->index }}</td>
								@if(isset($type) && ($type == 'all' || $type == 'status_report'))
									<td>{{ $data->type_name ?? '' }}</td>
								@endif
								<td>
									@if($data->latestStatus)
										<label class="badge {{ $data->latestStatusBadgeClass() }}">
											<strong>{{ strtoupper($data->latestStatus->status) }}</strong>
										</label>
									@endif
								</td>
								<td>
									@php
										$confirmationStatus = $data->sales_support_data_confirmation_at ? 'Confirmed' : 'Not Confirmed';
										$badgeClass = $confirmationStatus === 'Confirmed' ? 'badge-soft-success' : 'badge-soft-danger';
									@endphp
									<label class="badge {{ $badgeClass }}">
										<strong>{{ strtoupper($confirmationStatus) }}</strong>
									</label>
								</td>
								<td>
									@if($data->can_show_fin_approval === 'yes')
										<label class="badge {{ $data->financeApprovalBadgeClass() }}">
											<strong>{{ strtoupper($data->finance_approval_status) }}</strong>
										</label>
									@endif
								</td>
								<td>
									@if($data->can_show_coo_approval === 'yes')
										<label class="badge {{ $data->cooApprovalBadgeClass() }}">
											<strong>{{ strtoupper($data->coo_approval_status) }}</strong>
										</label>
									@endif
								</td>
								<td>
									@if($data->sales_support_data_confirmation_at && $data->finance_approval_status === 'Approved' && $data->coo_approval_status === 'Approved')
										<div class="tooltip-container">
										<label class="badge {{ $data->getBadgeClass($data->docs_status) }}">
											<strong>{{ strtoupper($data->docs_status) }}</strong>
										</label>
										@if($data->latestDocsStatus && $data->latestDocsStatus->documentation_comment)
											@if(isset($data->latestDocsStatus) && $data->latestDocsStatus->documentation_comment != null)
												<div class="tooltip-text">
													<div class="tooltip-header">Remarks</div>
													<div class="tooltip-body">
														{{ $data->latestDocsStatus->documentation_comment }}
													</div>
												</div>
											@endif
										@endif
									@endif
									</div>
								</td>
								<td>
									@if($data->sales_support_data_confirmation_at && $data->finance_approval_status === 'Approved' && $data->coo_approval_status === 'Approved')
										<label class="badge {{ $data->getBadgeClass($data->vehicles_modification_summary) }}">
											<strong>{{ strtoupper($data->vehicles_modification_summary) }}</strong>
										</label>
									@endif
								</td>
								<td>
									@if($data->sales_support_data_confirmation_at && $data->finance_approval_status === 'Approved' && $data->coo_approval_status === 'Approved')
										<label class="badge {{ $data->getBadgeClass($data->pdi_summary) }}">
											<strong>{{ strtoupper($data->pdi_summary ?? '') }}</strong>
										</label>
									@endif
								</td>
								<td>
									@if($data->sales_support_data_confirmation_at && $data->finance_approval_status === 'Approved' && $data->coo_approval_status === 'Approved')
										<label class="badge {{ $data->getBadgeClass($data->delivery_summary) }}">
											<strong>{{ strtoupper($data->delivery_summary ?? '') }}</strong>
										</label>
									@endif
								</td>
								<td>{{ $data->salesPerson->name ?? '' }}</td>
								<td>{{ $data->so_number ?? '' }}</td>
								<td>{{ $data->wo_number ?? '' }}</td>
								<td>{{ $data->formatDate($data->date) }}</td>
								@if(isset($type) && in_array($type, ['export_exw', 'export_cnf', 'all', 'status_report']))
									<td>{{ $data->is_batch == 0 ? 'Single' : ($data->batch ?? '') }}</td>
								@endif
								@if(isset($type) && $type != 'status_report')
									<td>{{ $data->customer_name ?? '' }}</td>
									<td class="no-click">{{ $data->customer_email ?? '' }}</td>
									<td class="no-click">{{ $data->customer_company_number ?? '' }}</td>
									<td>{{ $data->customer_address ?? '' }}</td>
									<td>{{ $data->customer_representative_name ?? '' }}</td>
									<td class="no-click">{{ $data->customer_representative_email ?? '' }}</td>
									<td class="no-click">{{ $data->customer_representative_contact ?? '' }}</td>								
								@if(isset($type) && $type == 'export_exw'|| $type == 'all')													
									<td>{{$data->freight_agent_name ?? ''}}</td>
									<td class="no-click">{{$data->freight_agent_email ?? ''}}</td>
									<td class="no-click">{{$data->freight_agent_contact_number ?? ''}}</td>
									<td>@if($data->type == 'export_exw'){{ $data->delivery_advise ?? '' }}@endif</td>
									<td>@if($data->type == 'export_exw'){{ $data->showroom_transfer ?? '' }}@endif</td>
								@endif
								@if(isset($type) && $type == 'export_cnf'|| $type == 'all')		
									<td>@if($data->type == 'export_cnf') {{ $data->cross_trade ?? '' }} @endif</td>
								@endif
								@if(isset($type) && $type == 'local_sale'|| $type == 'all')		
									<td>@if($data->type == 'local_sale') {{ $data->lto ?? '' }} @endif</td>
								@endif
								@if(isset($type) && in_array($type, ['export_exw', 'export_cnf', 'all']))
									<td>{{ $data->temporary_exit ?? '' }}</td>
									<td>{{ $data->port_of_loading ?? '' }}</td>
									<td>{{ $data->port_of_discharge ?? '' }}</td>
									<td>{{ $data->final_destination ?? '' }}</td>
									<td>{{ $data->transport_type ?? '' }}</td>
									@component('components.view-download-buttons', ['filePath' => 'wo/brn_file/', 'fileName' => $data->brn_file])@endcomponent
									<td>{{ $data->getTransportField('name') }}</td>
									<td>{{ $data->getTransportField('id') }}</td>
									<td class="{{ $data->transport_type == 'road' ? 'no-click' : '' }}">{{ $data->getTransportField('details') }}</td>
									<td>{{ $data->transport_type === 'sea' || $data->transport_type === 'road' ? $data->getTransportField('additional') : '' }}</td>
								@endif
								<td>{{ $data->so_vehicle_quantity ?? '' }}</td>
								<td>{{ $data->currency ?? '' }}</td>
								<td>{{ $data->so_total_amount != 0.00 ? $data->so_total_amount : '' }}</td>
								<td>{{ $data->amount_received != 0.00 ? $data->amount_received : '' }}</td>
								<td>{{ $data->balance_amount != 0.00 ? $data->balance_amount : '' }}</td>
								<td>{{$data->delivery_location ?? ''}}</td>
								<td>{{$data->delivery_contact_person ?? ''}}</td>
								<td class="no-click">{{$data->delivery_contact_person_number ?? ''}}</td>
								<td>@if($data->delivery_date != ''){{\Carbon\Carbon::parse($data->delivery_date)->format('d M Y') ?? ''}}@endif</td>
								@if(isset($type) && ($type == 'export_cnf'|| $type == 'all'))
									<td>{{$data->preferred_shipping_line_of_customer ?? ''}}</td>
									<td>{{$data->bill_of_loading_details ?? ''}}</td>
									<td>{{$data->shipper ?? ''}}</td>
									<td>{{$data->consignee ?? ''}}</td>
									<td>{{$data->notify_party ?? ''}}</td>
									<td>{{$data->special_or_transit_clause_or_request ?? ''}}</td>
								@endif
								@component('components.view-download-buttons', ['filePath' => 'wo/signed_pfi/', 'fileName' => $data->signed_pfi])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/signed_contract/', 'fileName' => $data->signed_contract])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/payment_receipts/', 'fileName' => $data->payment_receipts])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/noc/', 'fileName' => $data->noc])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/enduser_trade_license/', 'fileName' => $data->enduser_trade_license])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/enduser_passport/', 'fileName' => $data->enduser_passport])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/enduser_contract/', 'fileName' => $data->enduser_contract])@endcomponent
								@component('components.view-download-buttons', ['filePath' => 'wo/vehicle_handover_person_id/', 'fileName' => $data->vehicle_handover_person_id])@endcomponent
								@endif
								<td>{{ $data->CreatedBy->name ?? '' }}</td>
								<td>{{ $data->formatDate($data->created_at) }}</td>
								<td>{{ $data->UpdatedBy->name ?? '' }}</td>
								<td>{{ $data->formatDate($data->updated_at) }}</td>
								@if(isset($type) && $type != 'status_report')
									<td>{{ $data->salesSupportDataConfirmationBy->name ?? '' }}</td>
									<td>{{ $data->formatDate($data->sales_support_data_confirmation_at) }}</td>
									<td>{{ $data->total_number_of_boe != 0 ? $data->total_number_of_boe : '' }}</td>
									<td>{{ $data->has_claim ?? ''}}</td>
								@endif
								<td>{{ $data->vehicles->count() ?? 0 }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="d-flex justify-content-left mt-4">
					{{ $datas->links() }}
				</div>
			</div>
		</div>
    </div>
@else
    <div class="card-header">
        <p class="card-title">Sorry! You don't have permission to access this page.</p>
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
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('.tooltip-container').hover(
            function() {
                $(this).find('.tooltip-text').css({ visibility: 'visible', opacity: '1' });
            },
            function() {
                $(this).find('.tooltip-text').css({ visibility: 'hidden', opacity: '0' });
            }
        );
        const selectFilters = [
            { id: '#status-filter', placeholder: "Status" },
            { id: '#sales-support-filter', placeholder: "Data Confirmation" },
            { id: '#finance-approval-filter', placeholder: "Fin. Approval" },
            { id: '#coo-approval-filter', placeholder: "COO Approval" },
            { id: '#docs-status-filter', placeholder: "Documentation" },
            { id: '#modification-filter', placeholder: "Modification" },
            { id: '#pdi-filter', placeholder: "PDI" },
            { id: '#delivery-filter', placeholder: "Delivery" }
        ];
        selectFilters.forEach(filter => {
            $(filter.id).select2({ allowClear: true, placeholder: filter.placeholder });
			// Manually remove aria-hidden on Select2 initialization
			$(filter.id).removeAttr('aria-hidden');
        });
        $('#apply-filters').on('click', function(e) {
            e.preventDefault();

            const filterData = {
                status_filter: $('#status-filter').val(),
                sales_support_filter: $('#sales-support-filter').val(),
                finance_approval_filter: $('#finance-approval-filter').val(),
				coo_approval_filter: $('#coo-approval-filter').val(),
				docs_status_filter: $('#docs-status-filter').val(),
                type: "{{ isset($type) ? $type : '' }}",
                _token: '{{ csrf_token() }}'
            };

            $.post("{{ route('save.filters') }}", filterData)
                .done(function() {
                    window.location.href = "{{ route('work-order.index', '') }}/" + filterData.type;
                })
                .fail(function() {
                    alert("Failed to apply filters. Please try again.");
                });
        });

		$('.my-datatable tbody').on('dblclick', 'tr td:not(.no-click)', function() {
			const workOrderId = $(this).closest('tr').data('id');
			if (workOrderId) window.location.href = `/work-order/${workOrderId}`;
		});
        const table = $('.my-datatable').DataTable({
			pageLength: 100,
			lengthMenu: [10, 25, 50, 100, 200],
			order: [],
			columnDefs: [{ targets: 'no-sort', orderable: false }],
			paging: false,
			info: false,
			lengthChange: false,
			searching: false,  // Disables the search box
			initComplete: function() {
				hideEmptyColumns(this.api());
			}
		});

        function hideEmptyColumns(tableApi) {
			tableApi.columns().every(function() {
				const column = this;
				const allEmpty = column.data().toArray().every(data => !data || $.trim(data) === '');
				if (allEmpty) column.visible(false);
			});
		}
    });
	$(document).on('select2:open', function(e) {
		const selectId = e.target.id;
		const searchField = document.querySelector(`#select2-${selectId}-container`);
		if (searchField) searchField.focus();
	});
	$('.form-control').on('select2:open', function() {
		$(this).removeAttr('aria-hidden');
	});
	function confirmDelete(url, woNumber) {
		var message = "delete this work order " + woNumber; // Add the work order number
		alertify.confirm(
			'Are you sure you want to ' + message + '?',
			function (confirmed) { // 'confirmed' will be true if OK is clicked
				if (confirmed) {
					$.ajax({
						type: "POST",
						url: url,
						data: {
							_method: 'DELETE', // Emulates DELETE HTTP method
							_token: '{{ csrf_token() }}'
						},
						success: function () {
							window.location.reload();
							alertify.success("Work order " + woNumber + " deleted successfully");
						},
						error: function () {
							alertify.error("An error occurred while deleting work order " + woNumber);
						}
					});
				} else {
					alertify.error("Deletion canceled for work order " + woNumber);
				}
			}
		).set({ title: "Confirm Deletion" });
	}
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	document.getElementById('apply_search').addEventListener('click', function() {
		const searchValue = document.getElementById('search').value;
		const type = 'all';  // Modify this if 'type' should be dynamically set based on other input

		// Redirect to the URL with both 'type' and 'search' parameters
		window.location.href = `/work-order-info/${type}?search=${encodeURIComponent(searchValue)}`;
	});
</script>
@endpush