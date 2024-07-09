@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
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
	.table-edits input, .table-edits select {
	height:38px!important;
	}
</style>
@section('content')
<div class="card-header">
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-export-exw-wo','list-export-cnf-wo','list-export-local-sale-wo','list-lto-wo']);
	@endphp
	@if ($hasPermission)
	<h4 class="card-title">
    @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order Info
	</h4>
	@endif
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['create-export-exw-wo','create-export-cnf-wo','create-local-sale-wo']);
	@endphp
	@if ($hasPermission)
	<a style="float: right;" class="btn btn-sm btn-success" href="{{route('work-order-create.create',$type)}}">
	<i class="fa fa-plus" aria-hidden="true"></i> New @if(isset($type) && $type == 'export_exw') Export EXW @elseif(isset($type) && $type == 'export_cnf') Export CNF @elseif(isset($type) && $type == 'local_sale') Local Sale @endif Work Order 
	</a>
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
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['list-export-exw-wo','list-export-cnf-wo','list-export-local-sale-wo','list-lto-wo']);
@endphp
@if ($hasPermission)
<div class="tab-pane fade show" id="telephonic_interview">
		<div class="card-body">
			<div class="table-responsive">
				<table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
					<thead>
						<tr>
                            <th rowspan="2" class="dark">Action</th>
							<th rowspan="2" class="light">Sl No</th>
                            <th rowspan="2" class="light">SO No</th>                           
                            <th rowspan="2" class="light">WO No</th>                           
                            <th rowspan="2" class="light">Date</th>
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                                <th rowspan="2" class="light">Batch</th>
                            @endif
							<th colspan="4" class="dark">
								<center>Customer</center>
							</th>
							<th colspan="3" class="light">
								<center>Customer Representative</center>
							</th>
                            @if(isset($type) && $type == 'export_exw')
							<th colspan="3" class="dark">
								<center>Freight Agent</center>
							</th>
                            @endif
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                                <th rowspan="2" class="light">Port Of Loading</th>
                                <th rowspan="2" class="light">Port Of Discharge</th>
                                <th rowspan="2" class="light">Final Destination</th>
                                <th rowspan="2" class="light">Transport Type</th>
                                <th rowspan="2" class="light">BRN File</th>

                                <th rowspan="2" class="dark">Airline</th>
                                <th rowspan="2" class="dark">Airway Bill</th>
                                <th rowspan="2" class="dark">Airway Details</th>

                                <th rowspan="2" class="light">BRN</th>
                                <th rowspan="2" class="light">Container Number</th>                               
                                <th rowspan="2" class="light">Shipping Line</th>
                                <th rowspan="2" class="light">Forward Import Code</th>

                                <th rowspan="2" class="dark">Trailer Number Plate</th>
                                <th colspan="3" class="light">
                                    <center>Transportation</center>
                                </th>
                            @endif
                            <th colspan="5" class="dark">
								<center>SO</center>
							</th>
                            <th colspan="3" class="light">
								<center>Delivery</center>
							</th>
                            <th rowspan="2" class="dark">Signed PFI</th>
                            <th rowspan="2" class="dark">Signed Contract</th>
                            <th rowspan="2" class="dark">Payment Receipts</th>
                            <th rowspan="2" class="dark">NOC</th>
                            <th colspan="3" class="light">
                                <center>End User</center>
                            </th>
                            <th rowspan="2" class="dark">Handover Person ID</th>
                            <th rowspan="2" class="light">Created By</th>
                            <th rowspan="2" class="light">Created At</th>
                            <th rowspan="2" class="dark">Last Updated By</th>
                            <th rowspan="2" class="dark">Last Updated At</th>
                            <th rowspan="2" class="light">Sales Support Data Confirmation By</th>
                            <th rowspan="2" class="light">Sales Support Data Confirmation At</th>
                            <th rowspan="2" class="dark">Finance Approval By</th>
                            <th rowspan="2" class="dark">Finance Approval At</th>
                            <th rowspan="2" class="light">COO Office Approval By</th>
                            <th rowspan="2" class="light">COO Office Approval At</th>
							<th rowspan="2" class="dark">Total Number Of BOE</th>
						</tr>
						<tr>
							<td class="dark">Name</td>
                            <td class="dark">Email</td>
							<td class="dark">Contact</td>
                            <td class="dark">Address</td>
                            
							<td class="light">Name</td>
                            <td class="light">Email</td>
							<td class="light">Contact</td>
                            @if(isset($type) && $type == 'export_exw')
							<td class="dark">Name</td>
                            <td class="dark">Email</td>
							<td class="dark">Contact</td>
                            @endif
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                            <td class="light">Driver Contact</td>
                            <td class="light">Name</td>
							<td class="light">Details</td>
                            @endif
                            <td class="dark">Vehicle Qty</td>
							<td class="dark">Currency</td>
                            <td class="dark">Amount</td>
                            <td class="dark">Deposit</td>
                            <td class="dark">Balance</td>

                            <td class="light">Location</td>
                            <td class="light">Contact Person</td>
                            <td class="light">Date</td>
                            <td class="light">Trade License</td>
                            <td class="light">Passport</td>
                            <td class="light">Contract</td>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($datas as $key => $data)
						<tr data-id="1">
                            <td>
                                <div class="dropdown">
									<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
									<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-start">
                                        @php
                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','export-cnf-wo-details','local-sale-wo-details']);
                                        @endphp
                                        @if ($hasPermission)
                                        <li>
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="View Details" class="btn btn-sm btn-warning" href="{{route('work-order.show',$data->id ?? '')}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i> View Details
                                            </a>
                                        </li>
                                        @endif
                                        <li>
                                            <a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Edit" class="btn btn-sm btn-info" href="{{route('work-order.edit',$data->id ?? '')}}">
                                            <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                            </a>
                                        </li>
									</ul>
								</div>                         
                            </td>
							<td>{{ ++$i }}</td>
                            <td>{{$data->so_number ?? ''}}</td>
                            <td>{{$data->wo_number ?? ''}}</td>
							<td>@if($data->date != ''){{\Carbon\Carbon::parse($data->date)->format('d M Y') ?? ''}}@endif</td>
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))							
							    <td>{{$data->batch ?? ''}}</td>	
                            @endif						
							<td>{{$data->customer_name ?? ''}}</td>
							<td>{{$data->customer_email ?? ''}}</td>
							<td>{{$data->customer_company_number ?? ''}}</td>
							<td>{{$data->customer_address ?? ''}}</td>
							<td>{{$data->customer_representative_name ?? ''}}</td>
							<td>{{$data->customer_representative_email ?? ''}}</td>
							<td>{{$data->customer_representative_contact ?? ''}}</td>	
                            @if(isset($type) && $type == 'export_exw')													
                                <td>{{$data->freight_agent_name ?? ''}}</td>
                                <td>{{$data->freight_agent_email ?? ''}}</td>
                                <td>{{$data->freight_agent_contact_number ?? ''}}</td>
                            @endif
                            @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                                <td>{{$data->port_of_loading ?? ''}}</td>
                                <td>{{$data->port_of_discharge ?? ''}}</td>
                                <td>{{$data->final_destination ?? ''}}</td>
                                <td>{{$data->transport_type ?? ''}}</td>
                                <td>
									@if($data->brn_file)
										<a href="{{ url('wo/brn_file/' . $data->brn_file) }}" target="_blank">
											<button class="btn btn-primary mb-1 btn-style">View</button>
										</a>
										<a href="{{ url('wo/brn_file/' . $data->brn_file) }}" download>
											<button class="btn btn-info btn-style">Download</button>
										</a>
									@endif
								</td>
                                <td>{{$data->airline ?? ''}}</td>
                                <td>{{$data->airway_bill ?? ''}}</td>
                                <td>{{$data->airway_details ?? ''}}</td>

                                <td>{{$data->brn ?? ''}}</td>
                                <td>{{$data->container_number ?? ''}}</td>
                                
                                <td>{{$data->shipping_line ?? ''}}</td>
                                <td>{{$data->forward_import_code ?? ''}}</td>
                                <td>{{$data->trailer_number_plate ?? ''}}</td>
                                <td>{{$data->transporting_driver_contact_number ?? ''}}</td>

                                <td>{{$data->transportation_company ?? ''}}</td>
                               
                                <td>{{$data->transportation_company_details ?? ''}}</td>
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
                            <td>@if($data->delivery_date != ''){{\Carbon\Carbon::parse($data->delivery_date)->format('d M Y') ?? ''}}@endif</td>
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

							<td>{{$data->CreatedBy->name ?? ''}}</td>
                            <td>@if($data->created_at != ''){{\Carbon\Carbon::parse($data->created_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>

							<td>{{$data->UpdatedBy->name ?? ''}}</td>
                            <td>@if($data->updated_at != '' && $data->updated_by != '' && $data->updated_at != $data->created_at){{\Carbon\Carbon::parse($data->updated_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>

                            <td>{{$data->salesSupportDataConfirmationBy->name ?? ''}}</td>
                            <td>@if($data->sales_support_data_confirmation_at != ''){{\Carbon\Carbon::parse($data->sales_support_data_confirmation_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                            
							<td>{{$data->financeApprovalBy->name ?? ''}}</td>
                            <td>@if($data->finance_approved_at != ''){{\Carbon\Carbon::parse($data->finance_approved_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                            
							<td>{{$data->COOApprovalBy->name ?? ''}}</td>
                            <td>@if($data->coe_office_approved_at != ''){{\Carbon\Carbon::parse($data->coe_office_approved_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
							<td></td>
						</tr>
						@endforeach
					</tbody>
				</table>
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
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});
</script>

@endpush