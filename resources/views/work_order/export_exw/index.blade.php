@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
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
	.iti {
		width: 100%;
	}
	.texttransform {
		text-transform: capitalize;
	}
	.light {
		background-color:#e6e6e6!important;
		font-weight: 700!important;
	}
	.dark {
		background-color:#d9d9d9!important;
		font-weight: 700!important;
	}
	.paragraph-class {
		color: red;
		font-size:11px;
	}
	.other-error {
		color: red;
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
	@endphp
	@if ($canViewWOList)
	<h4 class="card-title">
    @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @elseif(isset($type) && $type == 'all') All @elseif(isset($type) && $type == 'status_report') Status Report - @endif Work Order Info
	</h4>
	@endif
	@if ($canCreateWO)
		@if(isset($type) && ($type == 'all' || $type == 'status_report'))
		<a style="float: right;" class="btn btn-sm btn-success me-1" href="{{route('work-order-create.create','local_sale')}}">
			<i class="fa fa-plus" aria-hidden="true"></i> New Local Sale Work Order 
		</a>
		<a style="float: right;" class="btn btn-sm btn-success me-1" href="{{route('work-order-create.create','export_cnf')}}">
			<i class="fa fa-plus" aria-hidden="true"></i> New Export CNF Work Order 
		</a>
		<a style="float: right;" class="btn btn-sm btn-success me-1" href="{{route('work-order-create.create','export_exw')}}">
			<i class="fa fa-plus" aria-hidden="true"></i> New Export EXW Work Order 
		</a>
		@elseif(isset($type) && ($type != 'all' || $type != 'status_report'))
			<a style="float: right;" class="btn btn-sm btn-success" href="{{route('work-order-create.create',$type)}}">
				<i class="fa fa-plus" aria-hidden="true"></i> New @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order 
			</a>
		@endif
	@endif
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	@if (Session::has('error'))
	<div class="alert alert-danger" >
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('error') }}
	</div>
	@endif
	@if (Session::has('success'))
	<div class="alert alert-success" id="success-alert">
		<button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
		{{ Session::get('success') }}
	</div>
	@endif
</div>
@if ($canViewWOList)
<div class="tab-pane fade show" id="telephonic_interview">
		<div class="card-body">
			<div class="row">
				<input type="hidden" name="type" value={{$type ?? ''}}>
				<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div" id="status-filter-div">
					<div class="dropdown-option-div">
						<label for="status-filter" class="col-form-label text-md-end">{{ __('Status') }}</label>
						<select name="status-filter" id="status-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($statuses as $status)
								<option value="{{ $status }}" 
									@if(isset($filters['status_filter']) && in_array($status, $filters['status_filter']))
										selected
									@endif>
									{{ $status }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div" id="sales-support-filter-div">
					<div class="dropdown-option-div">
						<label for="sales-support-filter" class="col-form-label text-md-end">{{ __('Data Confirmation') }}</label>
						<select name="sales_support_filter" id="sales-support-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($salesSupportDataConfirmations as $dataConfirmation)
								<option value="{{ $dataConfirmation }}" 
									@if(isset($filters['sales_support_filter']) && in_array($dataConfirmation, $filters['sales_support_filter']))
										selected
									@endif
								>
									{{ $dataConfirmation }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div" id="finance-approval-filter-div">
					<div class="dropdown-option-div">
						<label for="finance-approval-filter" class="col-form-label text-md-end">{{ __('Fin. Approval') }}</label>
						<select name="finance-approval-filter" id="finance-approval-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($financeApprovalStatuses as $finApproval)
								<option value="{{ $finApproval }}" 
									@if(isset($filters['finance_approval_filter']) && in_array($finApproval, $filters['finance_approval_filter']))
										selected
									@endif
								>
									{{ ucfirst($finApproval) }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div" id="coo-approval-filter-div" hidden>
					<div class="dropdown-option-div">
						<label for="coo-approval-filter" class="col-form-label text-md-end">{{ __('COO Approval') }}</label>
						<select name="coo-approval-filter" id="coo-approval-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($cooApprovalStatuses as $cooApproval)
								<option value="{{ $cooApproval }}"
									@if(isset($filters['coo_approval_filter']) && in_array($cooApproval, $filters['coo_approval_filter']))
										selected
									@endif
								>
									{{ $cooApproval }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div" id="docs-status-filter-div" hidden>
					<div class="dropdown-option-div">
						<label for="docs-status-filter" class="col-form-label text-md-end">{{ __('Documentation') }}</label>
						<select name="docs-status-filter" id="docs-status-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($docsStatuses as $docsStatus)
								<option value="{{ $docsStatus }}"
									@if(isset($filters['docs_status_filter']) && in_array($docsStatus, $filters['docs_status_filter']))
										selected
									@endif
								>
									{{ $docsStatus }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div" id="modification-filter-div" hidden>
					<div class="dropdown-option-div">
						<label for="modification-filter" class="col-form-label text-md-end">{{ __('Modification') }}</label>
						<select name="modification-filter" id="modification-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($vehiclesModificationSummary as $modificationStatus)
								<option value="{{ $modificationStatus }}"
									@if(isset($filters['modification_filter']) && in_array($modificationStatus, $filters['modification_filter']))
										selected
									@endif
								>
									{{ $modificationStatus }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div" id="pdi-filter-div" hidden>
					<div class="dropdown-option-div">
						<label for="pdi-filter" class="col-form-label text-md-end">{{ __('PDI') }}</label>
						<select name="pdi-filter" id="pdi-filter" multiple="true" class="form-control widthinput" autofocus>
							@foreach($pdiSummary as $pdi)
								<option value="{{$pdi}}">{{$pdi}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-2 col-lg-6 col-md-6 select-button-main-div" id="delivery-filter-div" hidden>
					<div class="dropdown-option-div">
						<label for="delivery-filter" class="col-form-label text-md-end">{{ __('Delivery') }}</label>
						<select name="delivery-filter" id="delivery-filter" multiple="true" class="form-control widthinput" autofocus>
						@foreach($deliverySummary as $delivery)
							<option value="{{$delivery}}">
								@if($delivery == 'Delivered')
									Delivered With Documents
								@elseif($delivery == 'Delivered With Docs Hold')
									Delivered/Documents Hold
								@else
									{{$delivery}}
								@endif
							</option>
						@endforeach
						</select>
					</div>
				</div>
				<div class="col-xxl-3 col-lg-6 col-md-6 select-button-main-div" id="apply-filter-div">
					<button id="apply-filters" type="submit" class="btn btn-info btn-sm mb-3" style="margin-top:25px!important;">
						Save & Apply Filters
					</button>
				</div>
			</div></br>
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table" style="width:100%;">
					<thead>
						<tr>
                            <th rowspan="2" class="dark">Action</th>
							<th rowspan="2" class="light">Sl No</th>
							@if(isset($type) && ($type == 'all' || $type == 'status_report'))	
							<th rowspan="2" class="light">Type</th>
							@endif
							<th rowspan="2" class="light">Status</th>
							<th rowspan="2" class="light">Sales Support Data Confirmation</th>
							<th colspan="2" class="dark">
								<center>Approval Status</center>
							</th>
							<th rowspan="2" class="light">Documentation Status</th>
							<th rowspan="2" class="light">Vehicle Modification Status</th>
							<th rowspan="2" class="light">PDI Status</th>
							<th rowspan="2" class="light">Delivery Status</th>
							<th rowspan="2" class="light">Sales Person</th>
                            <th rowspan="2" class="light">SO No</th>                           
                            <th rowspan="2" class="light">WO No</th>                           
                            <th rowspan="2" class="light">Date</th>
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf' || $type == 'all' || $type == 'status_report'))
                                <th rowspan="2" class="light">Batch</th>
                            @endif
							@if(isset($type) && $type != 'status_report')
							<th colspan="4" class="dark">
								<center>Customer</center>
							</th>
							<th colspan="3" class="light">
								<center>Customer Representative</center>
							</th>
                            @if(isset($type) && ($type == 'export_exw'|| $type == 'all'))
							<th colspan="3" class="dark">
								<center>Freight Agent</center>
							</th>
							<th rowspan="2" class="light">Delivery Advise</th>
							<th rowspan="2" class="light">Transfer Of Ownership</th>
                            @endif
							@if(isset($type) && ($type == 'export_cnf'|| $type == 'all'))
								<th rowspan="2" class="light">Cross Trade</th>
							@endif
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf' || $type == 'all'))
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
                            <th colspan="5" class="dark">
								<center>SO</center>
							</th>
                            <th colspan="4" class="light">
								<center>Delivery</center>
							</th>
							@if(isset($type) && ($type == 'export_cnf' || $type == 'all'))
							 	<th rowspan="2" class="light">Prefered Shipping Line</th>
								<th rowspan="2" class="light">Bill of Loading</th>
								<th rowspan="2" class="light">Shipper</th>
								<th rowspan="2" class="light">Consignee</th>
								<th rowspan="2" class="light">Notify Party</th>
								<th rowspan="2" class="light">Special/In Transit/Other Requests</th>
							@endif
                            <th rowspan="2" class="dark">Signed PFI</th>
                            <th rowspan="2" class="dark">Signed Contract</th>
                            <th rowspan="2" class="dark">Payment Receipts</th>
                            <th rowspan="2" class="dark">NOC</th>
                            <th colspan="3" class="light">
                                <center>End User</center>
                            </th>
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
							@endif
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
                            @if(isset($type) && ($type == 'export_exw' || $type == 'all'))
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
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-info" href="{{route('work-order.show',$data->id ?? '')}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i> View Details
                                            </a>
                                        </li>
                                        @endif

										@php
										$isDisabled = !$hasEditConfirmedPermission && $data->sales_support_data_confirmation_at != '';
										@endphp
                                        @if ($caneditWO)
                                        <li>
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info {{ $isDisabled ? 'disabled' : '' }}" href="{{ $isDisabled ? 'javascript:void(0);' : route('work-order.edit', $data->id ?? '') }}">
                                            <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                            </a>
                                        </li>
										@endif
                                        @if ($canViewFinLog)
                                        <li>
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Finance Approval History" class="btn btn-sm btn-info" href="{{route('fetchFinanceApprovalHistory',$data->id)}}">
                                            <i class="fa fa-history" aria-hidden="true"></i> 
											Fin. Approval Log
                                            </a>
                                        </li>
										@endif

										
                                        @if ($canViewCOOLog)
                                        <li>
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="COO Office Approval History" class="btn btn-sm btn-info" href="{{route('fetchCooApprovalHistory',$data->id)}}">
                                            <i class="fa fa-history" aria-hidden="true"></i> COO Approval Log
                                            </a>
                                        </li>
										@endif
										@if($data->sales_support_data_confirmation_at != '' && 
											$data->finance_approval_status == 'Approved' && 
											$data->coo_approval_status == 'Approved')
											
											@if ($canChangeDocStatus)
												<a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateDocStatusModal_{{$data->id}}">
													<i class="fa fa-file" aria-hidden="true"></i> Update Doc Status
												</a>
											@endif
										@endif
										
										@if ($canViewDocLog)
											<li>
												<a class="me-2 btn btn-sm btn-info" style="width:100%; margin-top:2px; margin-bottom:2px;"
													href="{{route('docStatusHistory',$data->id)}}">
													<i class="fas fa-eye"></i> Doc Status Log
												</a>
											</li>
										@endif
										
										@if ($canChangeWOStatus)
											<a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateStatusModal_{{$data->id}}">
												<i class="fa fa-file" aria-hidden="true"></i> Update Status
											</a>
										@endif
										
										@if ($canViewWOStatusLog)
											<li>
												<a class="me-2 btn btn-sm btn-info" style="width:100%; margin-top:2px; margin-bottom:2px;"
													href="{{route('woStatusHistory',$data->id)}}">
													<i class="fas fa-eye"></i> Status Log
												</a>
											</li>
										@endif
									</ul>
								</div> 
								@include('work_order.export_exw.doc_status_update')   
								@include('work_order.export_exw.status_update')                   
                            </td>
							<td>{{ $datas->firstItem() + $loop->index }}</td>
							@if(isset($type) && ($type == 'all'|| $type == 'all' || $type == 'status_report'))	
							<td>{{ $data->type_name ?? '' }}</td>
							@endif
							<td>
								@if($data->latestStatus)
									<label class="badge 
										@if($data->latestStatus->status == 'On Hold') badge-soft-warning
										@elseif($data->latestStatus->status == 'Active') badge-soft-success
										@elseif($data->latestStatus->status == 'Cancelled') badge-soft-danger
										@elseif($data->latestStatus->status == 'Succeeded') badge-soft-primary
										@elseif($data->latestStatus->status == 'Partially Delivered') badge-soft-info
										@endif">
										<strong>{{ strtoupper($data->latestStatus->status) }}</strong>
									</label>
								@endif
							</td>
							<td>
								@php
									$confirmationStatus = $data->sales_support_data_confirmation_at ? 'Confirmed' : 'Not Confirmed';
									$badgeClass = $confirmationStatus == 'Confirmed' ? 'badge-soft-success' : 'badge-soft-danger';
								@endphp
								<label class="badge {{ $badgeClass }}">
									<strong>{{ strtoupper($confirmationStatus) }}</strong>
								</label>
							</td>							
							<td>
								@if($data->can_show_fin_approval === 'yes')
									@php
										$financeStatus = $data->finance_approval_status;
										$badgeClass = match ($financeStatus) {
											'Pending' => 'badge-soft-info',
											'Approved' => 'badge-soft-success',
											'Rejected' => 'badge-soft-danger',
											default => '',
										};
									@endphp
									<label class="badge {{ $badgeClass }}">
										<strong>{{ strtoupper($financeStatus) }}</strong>
									</label>
								@endif
							</td>							
							<td>
								@if($data->can_show_coo_approval === 'yes')
									@php
										$cooStatus = $data->coo_approval_status;
										$badgeClass = match ($cooStatus) {
											'Pending' => 'badge-soft-info',
											'Approved' => 'badge-soft-success',
											'Rejected' => 'badge-soft-danger',
											default => '',
										};
									@endphp
									<label class="badge {{ $badgeClass }}">
										<strong>{{ strtoupper($cooStatus) }}</strong>
									</label>
								@endif
							</td>							
							<td>
								@if($data->sales_support_data_confirmation_at && 
									$data->finance_approval_status === 'Approved' && 
									$data->coo_approval_status === 'Approved') 

									@php
										$badgeClass = match ($data->docs_status) {
											'In Progress' => 'badge-soft-info',
											'Ready' => 'badge-soft-success',
											'Not Initiated' => 'badge-soft-danger',
											default => '',
										};
									@endphp

									<div class="tooltip-container">
										<label class="badge {{ $badgeClass }} docs-status">
											<strong>{{ strtoupper($data->docs_status) }}</strong>
										</label>
										@if($data->latestDocsStatus && $data->latestDocsStatus->documentation_comment)
											<div class="tooltip-text">
												<div class="tooltip-header">Remarks</div>
												<div class="tooltip-body">
													{{ $data->latestDocsStatus->documentation_comment }}
												</div>
											</div>
										@endif
									</div>
								@endif
							</td>

							<td>
								@if($data->sales_support_data_confirmation_at && 
									$data->finance_approval_status === 'Approved' && 
									$data->coo_approval_status === 'Approved')
									
									@php
										$badgeClass = match($data->vehicles_modification_summary) {
											'INITIATED' => 'badge-soft-info',
											'NO MODIFICATIONS' => 'badge-soft-warning',
											'NOT INITIATED' => 'badge-soft-danger',
											'COMPLETED' => 'badge-soft-success',
											default => 'badge-soft-dark',
										};
									@endphp

									<label class="float-end badge {{ $badgeClass }}">
										<strong>{{ strtoupper($data->vehicles_modification_summary) }}</strong>
									</label>
								@endif
							</td>
							<td>
								@if($data->sales_support_data_confirmation_at && 
									$data->finance_approval_status === 'Approved' && 
									$data->coo_approval_status === 'Approved')

									@php
										$badgeClass = match($data->pdi_summary) {
											'SCHEDULED' => 'badge-soft-info',
											'NOT INITIATED' => 'badge-soft-danger',
											'COMPLETED' => 'badge-soft-success',
											default => 'badge-soft-dark',
										};
									@endphp

									<label class="float-end badge {{ $badgeClass }}">
										<strong>{{ strtoupper($data->pdi_summary ?? '') }}</strong>
									</label>
								@endif
							</td>
							<td>
								@if($data->sales_support_data_confirmation_at && 
									$data->finance_approval_status === 'Approved' && 
									$data->coo_approval_status === 'Approved')

									@php
										$badgeClass = match($data->delivery_summary) {
											'READY' => 'badge-soft-info',
											'ON HOLD' => 'badge-soft-danger',
											'DELIVERED WITH DOCS HOLD' => 'badge-soft-warning',
											'DELIVERED' => 'badge-soft-success',
											default => 'badge-soft-dark',
										};
									@endphp

									<label class="float-end badge {{ $badgeClass }}">
										<strong>{{ strtoupper($data->delivery_summary ?? '') }}</strong>
									</label>
								@endif
							</td>
							<td>{{$data->salesPerson->name ?? ''}}</td>
							<td>{{$data->so_number ?? ''}}</td>
                            <td>{{$data->wo_number ?? ''}}</td>
							<td>@if($data->date != ''){{\Carbon\Carbon::parse($data->date)->format('d M Y') ?? ''}}@endif</td>
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'|| $type == 'all' || $type == 'status_report'))															
							    <td>@if($data->is_batch == 0) Single @else {{$data->batch ?? ''}} @endif</td>	
                            @endif	
							@if(isset($type) && $type != 'status_report')					
							<td>{{$data->customer_name ?? ''}}</td>
							<td class="no-click">{{$data->customer_email ?? ''}}</td>
							<td class="no-click">{{$data->customer_company_number ?? ''}}</td>
							<td>{{$data->customer_address ?? ''}}</td>
							<td>{{$data->customer_representative_name ?? ''}}</td>
							<td class="no-click">{{$data->customer_representative_email ?? ''}}</td>
							<td class="no-click">{{$data->customer_representative_contact ?? ''}}</td>	
                            @if(isset($type) && $type == 'export_exw'|| $type == 'all')													
                                <td>{{$data->freight_agent_name ?? ''}}</td>
                                <td class="no-click">{{$data->freight_agent_email ?? ''}}</td>
                                <td class="no-click">{{$data->freight_agent_contact_number ?? ''}}</td>
								<td>@if($data->type == 'export_exw'){{ $data->delivery_advise ?? '' }}@endif</td>
								<td>@if($data->type == 'export_exw'){{ $data->showroom_transfer ?? '' }}@endif</td>
                            @endif
							@if(isset($type) && ($type == 'export_cnf'|| $type == 'all'))
								<td>@if($data->type == 'export_cnf'){{ $data->cross_trade ?? '' }}@endif</td>
							@endif
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'|| $type == 'all'))
								<td>@if($data->type == 'export_exw' || $data->type == 'export_cnf'){{ $data->temporary_exit ?? '' }}@endif</td>
                                <td>{{$data->port_of_loading ?? ''}}</td>
                                <td>{{$data->port_of_discharge ?? ''}}</td>
                                <td>{{$data->final_destination ?? ''}}</td>
                                <td>{{$data->transport_type ?? ''}}</td>
                                <td>
									@if($data->transport_type == 'air' || $data->transport_type == 'sea')
										@if($data->brn_file)
											<a href="{{ url('wo/brn_file/' . $data->brn_file) }}" target="_blank">
												<button class="btn btn-primary mb-1 btn-style">View</button>
											</a>
											<a href="{{ url('wo/brn_file/' . $data->brn_file) }}" download>
												<button class="btn btn-info btn-style">Download</button>
											</a>
										@endif
									@endif
								</td>
                                <td>
									@if($data->transport_type == 'air')
										{{$data->airline ?? ''}}
									@elseif($data->transport_type == 'sea')
										{{$data->shipping_line ?? ''}}
									@elseif($data->transport_type == 'road')
										{{$data->trailer_number_plate ?? ''}}
									@endif									
								</td>
                                <td>
									@if($data->transport_type == 'air')
										{{$data->airway_bill ?? ''}}
									@elseif($data->transport_type == 'sea')
										{{$data->container_number ?? ''}}
									@elseif($data->transport_type == 'road')
										{{$data->transportation_company ?? ''}}
									@endif
								</td>
                                <td class="@if($data->transport_type == 'road') no-click @endif">
									@if($data->transport_type == 'air')
										{{$data->airway_details ?? ''}}
									@elseif($data->transport_type == 'sea')
										{{$data->forward_import_code ?? ''}}
									@elseif($data->transport_type == 'road')
										{{$data->transporting_driver_contact_number ?? ''}}
									@endif
								</td>
                                <td>
									@if($data->transport_type == 'sea')
										{{$data->brn ?? ''}}
									@elseif($data->transport_type == 'road')
										{{$data->transportation_company_details ?? ''}}
									@endif
								</td>
                            @endif
                            <td>{{$data->so_vehicle_quantity ?? ''}}</td>
							<td>
								@if($data->so_total_amount != 0.00 || $data->amount_received != 0.00 || $data->balance_amount != 0.00)
								{{$data->currency ?? ''}}
								@endif
							</td>
							<td>@if($data->so_total_amount != 0.00){{$data->so_total_amount ?? ''}} @endif</td>
							<td>@if($data->amount_received != 0.00){{$data->amount_received ?? ''}} @endif</td>
							<td>@if($data->balance_amount != 0.00){{$data->balance_amount ?? ''}} @endif</td>
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
                            <td>
								@if($data->signed_pfi)
									<a href="{{ url('wo/signed_pfi/' . $data->signed_pfi) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/signed_pfi/' . $data->signed_pfi) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							<td>
								@if($data->signed_contract)
									<a href="{{ url('wo/signed_contract/' . $data->signed_contract) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/signed_contract/' . $data->signed_contract) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							<td>
								@if($data->payment_receipts)
									<a href="{{ url('wo/payment_receipts/' . $data->payment_receipts) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/payment_receipts/' . $data->payment_receipts) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							<td>
								@if($data->noc)
									<a href="{{ url('wo/noc/' . $data->noc) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/noc/' . $data->noc) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							<td>
								@if($data->enduser_trade_license)
									<a href="{{ url('wo/enduser_trade_license/' . $data->enduser_trade_license) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/enduser_trade_license/' . $data->enduser_trade_license) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							<td>
								@if($data->enduser_passport)
									<a href="{{ url('wo/enduser_passport/' . $data->enduser_passport) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/enduser_passport/' . $data->enduser_passport) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
                            <td>
								@if($data->enduser_contract)
									<a href="{{ url('wo/enduser_contract/' . $data->enduser_contract) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/enduser_contract/' . $data->enduser_contract) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							<td>
								@if($data->vehicle_handover_person_id)
									<a href="{{ url('wo/vehicle_handover_person_id/' . $data->vehicle_handover_person_id) }}" target="_blank">
										<button class="btn btn-primary mb-1 btn-style">View</button>
									</a>
									<a href="{{ url('wo/vehicle_handover_person_id/' . $data->vehicle_handover_person_id) }}" download>
										<button class="btn btn-info btn-style">Download</button>
									</a>
								@endif
							</td>
							@endif
							<td>{{$data->CreatedBy->name ?? ''}}</td>
                            <td>@if($data->created_at != ''){{\Carbon\Carbon::parse($data->created_at)->format('d M Y') ?? ''}}@endif</td>
							<td>{{$data->UpdatedBy->name ?? ''}}</td>
                            <td>@if($data->updated_at != '' && $data->updated_by != '' && $data->updated_at != $data->created_at){{\Carbon\Carbon::parse($data->updated_at)->format('d M Y') ?? ''}}@endif</td>
							@if(isset($type) && $type != 'status_report')
							<td>@if($data->sales_support_data_confirmation_at != ''){{$data->salesSupportDataConfirmationBy->name ?? ''}}@endif</td>
                            <td>@if($data->sales_support_data_confirmation_at != '') {{\Carbon\Carbon::parse($data->sales_support_data_confirmation_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
							<td>@if($data->total_number_of_boe != 0){{$data->total_number_of_boe ?? ''}}@endif</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="d-flex justify-content-center mt-4">
					{{ $datas->links() }}
				</div>
			</div>
		</div>
	</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
		$('.tooltip-container').hover(function() {
			$(this).find('.tooltip-text').css('visibility', 'visible').css('opacity', '1');
		}, function() {
			$(this).find('.tooltip-text').css('visibility', 'hidden').css('opacity', '0');
		});
		$('#status-filter').select2({
			allowClear: true,
			placeholder:"Status",
		});
		$('#sales-support-filter').select2({
			allowClear: true,
			placeholder:"Data Confirmation",
		});
		$('#finance-approval-filter').select2({
			allowClear: true,
			placeholder:"Fin. Approval",
		});	
		$('#coo-approval-filter').select2({
			allowClear: true,
			placeholder:"COO Approval",
		});	
		$('#docs-status-filter').select2({
			allowClear: true,
			placeholder:"Documentation",
		});	
		$('#modification-filter').select2({
			allowClear: true,
			placeholder:"Modification",
		});	
		$('#pdi-filter').select2({
			allowClear: true,
			placeholder:"PDI",
		});	
		$('#delivery-filter').select2({
			allowClear: true,
			placeholder:"Delivery",
		});	
		$('#apply-filters').on('click', function(e) {
			e.preventDefault();

			let filterData = {
				status_filter: $('#status-filter').val(),
				sales_support_filter: $('#sales-support-filter').val(),
				finance_approval_filter: $('#finance-approval-filter').val(),
				coo_approval_filter: $('#coo-approval-filter').val(),
				docs_status_filter: $('#docs-status-filter').val(),
				modification_filter: $('#modification-filter').val(),
				pdi_filter: $('#pdi-filter').val(),
				delivery_filter: $('#delivery-filter').val(),
				type: "{{ isset($type) ? $type : '' }}",
				_token: '{{ csrf_token() }}' 
			};

			$.post("{{ route('save.filters') }}", filterData, function(response) {
				window.location.href = "{{ route('work-order.index', '') }}/" + filterData.type;
			}).fail(function() {
				alert("Failed to apply filters. Please try again.");
			});
		});

		$('.my-datatable tbody').on('click', 'tr td:not(.no-click)', function() {
			let row = $(this).closest('tr');
			let workOrderId = row.data('id'); 

			if (workOrderId) {
				window.location.href = `/work-order/${workOrderId}`;
			}
		});
        var table = $('.my-datatable').DataTable({
            "pageLength": 100, 
            "lengthMenu": [10, 25, 50, 100, 200], 
            "order": [], 
            "columnDefs": [{
                "targets": 'no-sort', 
                "orderable": false,
            }],
			"paging": false,          // Disable pagination
			"info": false,            // Disable the "Showing X to Y of Z entries"
			"lengthChange": false,    // Disable the "Show N entries"
            "initComplete": function(settings, json) {
                this.api().columns().every(function() {
                    var column = this;
                    var allEmpty = true;

                    column.data().each(function(data, index) {
                        if (data && $.trim(data) !== '') {
                            allEmpty = false;
                            return false; 
                        }
                    });

                    if (allEmpty) {
                        column.visible(false);
                    }
                });
            }
        });
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@endpush