@extends('layouts.main')
<style>
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
        .reply-button {
            margin-top: 10px;
        }
        .replies {
            margin-left: 30px; 
        }
	.texttransform {
	text-transform: capitalize;
	}
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	/* background-color: #c1e1fb !important; */
	}
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	/* color: black!important; */
	/* background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important; */
	background: #072c47 !important;
	}
	.nav-link:focus{
	color: white !important;
	}
	.nav-link:hover {
	color: white !important;
	}
    .form-label {
        font-size:12px!important;
        font-weight: 600!important;
    }
    .data-font {
        font-size:12px!important;
    }
    .table>:not(caption)>*>* {
		padding: .3rem .3rem!important;
		-webkit-box-shadow: inset 0 0 0 0px var(--bs-table-accent-bg)!important;
	}
    table {
        width: 100%;
    }
    th {
		font-size:12px!important;
	}
	td {
		font-size:12px!important;
	}
.my-datatable th {
    border-left: 1px solid #e9e9ef; 
    border-right: 1px solid #e9e9ef; 
    border-top: 1px solid #e9e9ef; 
    border-bottom: 1px solid #e9e9ef; 
    padding: 2px; 
    text-align: left; 
}

.my-datatable td {
    border-left: 1px solid #e9e9ef; 
    border-right: 1px solid #e9e9ef; 
    border-top: 1px solid #e9e9ef; 
    border-bottom: 1px solid #e9e9ef; 
    padding: 2px; 
    text-align: left; 
}

.my-datatable {
    border-collapse: collapse; 
    width: 100%; 
}

.custom-border-top {
    border-top: 2px solid #b3b3b3; 
}
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','current-user-export-exw-wo-details','export-cnf-wo-details','current-user-export-cnf-wo-details','local-sale-wo-details','current-user-local-sale-wo-details']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title form-label">@if(isset($workOrder) && $workOrder->type == 'export_exw') Export EXW @elseif(isset($workOrder) && $workOrder->type == 'export_cnf') Export CNF @elseif(isset($workOrder) && $workOrder->type == 'local_sale') Local Sale @endif Work Order Details</h4>
    <div class="col-12 d-flex flex-wrap float-end">
        <label style="font-size: 119%; margin-right:3px;" class="float-end badge 
            @if($workOrder->status == 'On Hold') badge-soft-warning
            @elseif($workOrder->status == 'Active') badge-soft-success
            @elseif($workOrder->status == 'Cancelled') badge-soft-danger
            @elseif($workOrder->status == 'Succeeded') badge-soft-primary
            @elseif($workOrder->status == 'Partially Delivered') badge-soft-info
            @endif">
            <strong>{{ strtoupper($workOrder->status) ?? ''}}</strong>
        </label>
        <label style="font-size: 119%; margin-right:3px;" class="float-end badge @if($workOrder->sales_support_data_confirmation == 'Confirmed') badge-soft-success @elseif($workOrder->sales_support_data_confirmation == 'Not Confirmed') badge-soft-danger @endif">SALES SUPPORT : <strong>{{ strtoupper($workOrder->sales_support_data_confirmation) ?? ''}}</strong></label>
        @if($workOrder->can_show_coo_approval == 'yes')<label style="font-size: 119%; margin-right:3px;" class="float-end badge @if($workOrder->coo_approval_status == 'Pending') badge-soft-info @elseif($workOrder->coo_approval_status == 'Approved') badge-soft-success @elseif($workOrder->coo_approval_status == 'Rejected') badge-soft-danger @endif">COO OFFICE : <strong>{{ strtoupper($workOrder->coo_approval_status) ?? ''}}</strong></label>@endif
        @if($workOrder->can_show_fin_approval == 'yes')<label style="font-size: 119%; margin-right:3px;" class="float-end badge @if($workOrder->finance_approval_status == 'Pending') badge-soft-info @elseif($workOrder->finance_approval_status == 'Approved') badge-soft-success @elseif($workOrder->finance_approval_status == 'Rejected') badge-soft-danger @endif">FINANCE : <strong>{{ strtoupper($workOrder->finance_approval_status) ?? ''}}</strong></label>@endif
        @if(isset($workOrder))
            @if($workOrder->sales_support_data_confirmation_at != '' && 
                $workOrder->finance_approval_status == 'Approved' && 
                $workOrder->coo_approval_status == 'Approved') 

                @php
                    $badgeClass = '';
                    if ($workOrder->docs_status == 'In Progress') {
                        $badgeClass = 'badge-soft-info';
                    } elseif ($workOrder->docs_status == 'Ready') {
                        $badgeClass = 'badge-soft-success';
                    } elseif ($workOrder->docs_status == 'Not Initiated') {
                        $badgeClass = 'badge-soft-danger';
                    }

                    $labelText = '';
                    if ($workOrder->docs_status == 'In Progress' || $workOrder->docs_status == 'Not Initiated') {
                        $labelText = 'Documentation :';
                    } elseif ($workOrder->docs_status == 'Ready') {
                        $labelText = 'Documents :';
                    }
                @endphp

                <label style="font-size: 119%; margin-right:3px;" class="float-end badge {{ $badgeClass }}">
                    {{ strtoupper($labelText) }} <strong>{{ strtoupper($workOrder->docs_status) ?? '' }}</strong>
                </label>
                <label style="font-size: 119%; margin-right:3px;" class="float-end badge @if($workOrder->vehicles_modification_summary == 'INITIATED') badge-soft-info @elseif($workOrder->vehicles_modification_summary == 'NO MODIFICATIONS') badge-soft-warning @elseif($workOrder->vehicles_modification_summary == 'NOT INITIATED') badge-soft-danger @elseif($workOrder->vehicles_modification_summary == 'COMPLETED') badge-soft-success @else badge-soft-dark @endif">
                    MODIFICATION : <strong>{{ $workOrder->vehicles_modification_summary ?? ''}}</strong>
                </label>
                <label style="font-size: 119%; margin-right:3px;" class="float-end badge @if($workOrder->pdi_summary == 'SCHEDULED') badge-soft-info @elseif($workOrder->pdi_summary == 'NOT INITIATED') badge-soft-danger @elseif($workOrder->pdi_summary == 'COMPLETED') badge-soft-success @else badge-soft-dark @endif">
                    PDI : <strong>{{ $workOrder->pdi_summary ?? ''}}</strong>
                </label>  
                <label style="font-size: 119%; margin-right:3px;" class="float-end badge @if($workOrder->delivery_summary == 'READY') badge-soft-info @elseif($workOrder->delivery_summary == 'ON HOLD') badge-soft-danger @elseif($workOrder->delivery_summary == 'DELIVERED') badge-soft-success @elseif($workOrder->delivery_summary == 'DELIVERED WITH DOCS HOLD') badge-soft-warning @else badge-soft-dark @endif">
                    DELIVERY : <strong>{{ $workOrder->delivery_summary ?? ''}}</strong>
                </label>         
            @endif
        @endif 
    </div>
    <div class="col-12 d-flex flex-wrap align-items-center">
        @if($previous != '')
        <a class="btn btn-sm btn-info me-2" href="{{ route('work-order.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
        @endif
        @if($next != '')
        <a class="btn btn-sm btn-info me-2" href="{{ route('work-order.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
        @endif
        @include('work_order.export_exw.approvals')
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['edit-all-export-exw-work-order','edit-current-user-export-exw-work-order','edit-current-user-export-cnf-work-order','edit-all-export-cnf-work-order','edit-all-local-sale-work-order','edit-current-user-local-sale-work-order']);
        $hasEditConfirmedPermission = Auth::user()->hasPermissionForSelectedRole(['edit-confirmed-work-order']);
		$isDisabled = !$hasEditConfirmedPermission && isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '';
        @endphp
        @if ($hasPermission)
        <a title="Edit" class="btn btn-sm btn-info me-2 {{ $isDisabled ? 'disabled' : '' }}" href="{{ $isDisabled ? 'javascript:void(0);' : route('work-order.edit', $workOrder->id ?? '') }}">
            <i class="fa fa-edit" aria-hidden="true"></i> Edit
        </a>
        @endif
        <a class="btn btn-sm btn-info me-2" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
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
<div class="card-body">
	<div class="tab-content">
		<div class="tab-pane fade show active" id="requests">
			<br>
			<div class="card">
				<div class="card-header" style="background-color:#e8f3fd;">
					<div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <center><label for="choices-single-default" class="form-label"> <strong> Date</strong></label> : <span class="data-font">@if($workOrder->date != ''){{\Carbon\Carbon::parse($workOrder->date)->format('d M Y') ?? ''}}@endif</span></center>
							</div>
						</div>
                        <div class="col-lg-2 col-md-2 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> WO Number</strong></label> : <span class="data-font">{{ $workOrder->wo_number ?? '' }}</span></center>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> SO Number </strong></label> : <span class="data-font">{{ $workOrder->so_number ?? '' }}</span></center>
							</div>
						</div>
                        @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                            <div class="col-lg-2 col-md-2 col-sm-6 col-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <center><label for="choices-single-default" class="form-label"> <strong> Batch </strong></label> : <span class="data-font">@if($workOrder->is_batch == 0) Single @else {{$workOrder->batch ?? ''}} @endif</span></center>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-2 col-md-2 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> Sales Person </strong></label> : <span class="data-font">{{ $workOrder->salesPerson->name ?? '' }}</span></center>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
                    <div class="portfolio">
                        <ul class="nav nav-pills nav-fill" id="my-tab">
                            <li class="nav-item">
                                <a class="nav-link active form-label" data-bs-toggle="pill" href="#general-info"> General and Vehicles-Addons Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#documents"> Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#comments_section"> Comments Section</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#wo_data_history"> WO Data History</a>
                            </li>   
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#wo_vehicle_data_history"> WO Vehicles & Addons Data History</a>
                            </li>                          
                        </ul>
                    </div>
                    </br>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="general-info">
                            <div class="card">
                                <div class="card-header" style="background-color : #e8f3fd!important;">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">General Informations</center>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Name </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_name ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Email </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_email ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Company No. </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_company_number ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Address </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_address ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Rep. Name</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_representative_name ?? 'NA'}}</span>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Rep. Email</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_representative_email ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Rep. Contact</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_representative_contact ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(isset($type) && $type == 'export_exw')
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Freight Agent Name </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->freight_agent_name ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Freight Agent Email </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->freight_agent_email ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Freight Agent Contact </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->freight_agent_contact_number ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Delivery Advise </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{ $workOrder->delivery_advise ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Transfer Of Ownership </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{ $workOrder->showroom_transfer ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif 
                                                @if(isset($type) && $type == 'export_cnf')
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Cross Trade </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{ $workOrder->cross_trade ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(isset($type) && $type == 'local_sale')
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> LTO </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{ $workOrder->lto ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Temporary Exit </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{ $workOrder->temporary_exit ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Port Of Loading </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->port_of_loading ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Port Of Discharge </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->port_of_discharge ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Final Destination </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->final_destination ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> Transport Type </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->transport_type ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    @if($workOrder->transport_type == 'air')                                                
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Airline </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->airline ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Airway Bill </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->airway_bill ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Airway Details </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->airway_details ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($workOrder->transport_type == 'sea') 
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> BRN </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->brn ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Container Number </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->container_number ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Shipping Line </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->shipping_line ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Forward Import Code </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->forward_import_code ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($workOrder->transport_type == 'sea') 
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Trailer Number Plate </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->trailer_number_plate ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Transporting Driver Contact Number </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->transporting_driver_contact_number ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Transportation Company </label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->transportation_company ?? 'NA'}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                            <div class="row">
                                                                <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                    <label for="choices-single-default" class="form-label"> Transportation Company Details</label>
                                                                </div>
                                                                <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                    <span class="data-font">{{$workOrder->transportation_company_details ?? 'NA'}}</span>
                                                                </div>   
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif 
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> SO Vehicle Quantity </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->so_vehicle_quantity ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> SO Total Amount </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->so_total_amount != 0.00) {{$workOrder->so_total_amount ?? 'NA'}} {{$workOrder->currency ?? ''}} @else NA @endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Amount Received </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->amount_received != 0.00) {{$workOrder->amount_received ?? 'NA'}} {{$workOrder->currency ?? ''}} @else NA @endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Balance Amount </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->balance_amount != 0.00) {{$workOrder->balance_amount ?? 'NA'}} {{$workOrder->currency ?? ''}} @else NA @endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Delivery Location </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->delivery_location ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Delivery Contact Name</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->delivery_contact_person ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Delivery Contact No.</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->delivery_contact_person_number ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Delivery Date </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->delivery_date != ''){{\Carbon\Carbon::parse($workOrder->delivery_date)->format('d M Y') ?? 'NA'}} @else NA @endif</span>
                                                        </div> 
                                                    </div>
                                                </div>
                                                @if($workOrder->type == 'export_cnf')
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Prefered Shipping Line</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->preferred_shipping_line_of_customer ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Bill of Loading Details</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->bill_of_loading_details ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Shipper</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->shipper ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Consignee</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->consignee ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Notify Party</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->notify_party ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Special/In Transit Req.</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->special_or_transit_clause_or_request ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Created By</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->CreatedBy->name ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Created At </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->created_at != ''){{\Carbon\Carbon::parse($workOrder->created_at)->format('d M Y,  h:i:s A') ?? 'NA'}}@endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Data Confirmed By</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->sales_support_data_confirmation_at != ''){{$workOrder->salesSupportDataConfirmationBy->name ?? 'NA'}} @else NA @endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Data Confirmed At </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->sales_support_data_confirmation_at != ''){{\Carbon\Carbon::parse($workOrder->sales_support_data_confirmation_at)->format('d M Y,  h:i:s A') ?? 'NA'}}@endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Last Updated By</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->UpdatedBy->name ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Last Updated At </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->updated_at != '' && $workOrder->updated_at != $workOrder->created_at){{\Carbon\Carbon::parse($workOrder->updated_at)->format('d M Y,  h:i:s A') ?? 'NA'}} @else NA @endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Total Number Of BOE:</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->total_number_of_boe != 0){{$workOrder->total_number_of_boe ?? ''}}@endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($workOrder->transport_type == 'air' || $workOrder->transport_type == 'sea')
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label"> BRN Fille </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    @if($workOrder->brn_file)
                                                                        <a href="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" target="_blank">
                                                                            <button class="btn btn-primary m-2 btn-style">View</button>
                                                                        </a>
                                                                        <a href="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" download>
                                                                            <button class="btn btn-info btn-style">Download</button>
                                                                        </a>
                                                                    @else
                                                                        NA
                                                                    @endif
                                                                </span>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Signed PFI </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->signed_pfi)
                                                                    <a href="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Signed Contract </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->signed_contract)
                                                                    <a href="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Payment Receipts </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->payment_receipts)
                                                                    <a href="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> NOC </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->noc)
                                                                    <a href="{{ url('wo/noc/' . $workOrder->noc) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/noc/' . $workOrder->noc) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> End User Trade License </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->enduser_trade_license)
                                                                    <a href="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> End User Passport </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->enduser_passport)
                                                                    <a href="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> End User Contract </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->enduser_contract)
                                                                    <a href="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label">Handover Person ID</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                @if($workOrder->vehicle_handover_person_id)
                                                                    <a href="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" target="_blank">
                                                                        <button class="btn btn-primary m-2 btn-style">View</button>
                                                                    </a>
                                                                    <a href="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" download>
                                                                        <button class="btn btn-info btn-style">Download</button>
                                                                    </a>
                                                                @else
                                                                    NA
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(isset($workOrder->latestDocsStatus))
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label">Docs Status Remarks</label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    <span class="data-font">{{$workOrder->latestDocsStatus->documentation_comment ?? 'NA'}}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label">Docs Status Updated By</label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    <span class="data-font">{{$workOrder->latestDocsStatus->user->name ?? 'NA'}}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label">Docs Status Updated At</label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    <span class="data-font">
                                                                        @if($workOrder->latestDocsStatus->doc_status_changed_at != ''){{\Carbon\Carbon::parse($workOrder->latestDocsStatus->doc_status_changed_at)->format('d M Y,  h:i:s A')}}@endif
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(isset($workOrder->latestStatus))
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label">Status Remarks</label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    <span class="data-font">{{ $workOrder->latestStatus->comment ?? 'NA' }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label">Status Updated By</label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    <span class="data-font">{{ $workOrder->latestStatus->user->name ?? 'NA' }}</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                        <div class="row">
                                                            <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                                <label for="choices-single-default" class="form-label">Status Updated At</label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">
                                                                    <span class="data-font">
                                                                        @if(!empty($workOrder->latestStatus->status_changed_at))
                                                                            {{ \Carbon\Carbon::parse($workOrder->latestStatus->status_changed_at)->format('d M Y, h:i:s A') }}
                                                                        @else
                                                                            NA
                                                                        @endif
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label">Has Claim</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">
                                                                <span class="data-font">
                                                                    {{$workOrder->has_claim ?? ''}}
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="row">
                                                @if(isset($workOrder->boe) && count($workOrder->boe) > 0)
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <table class="table table-striped table-editable table-edits table-condensed">
                                                            <thead style="background-color: #e6f1ff">
                                                                <tr>
                                                                    <th>BOE Number</th>
                                                                    <th>Declaration Number</th>
                                                                    <th>Declaration date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($workOrder->boe as $one)
                                                                    <tr>
                                                                        <td>{{ $one->boe ?? '' }}</td>
                                                                        <td>{{ $one->declaration_number ?? ''}}</td>
                                                                        <td>@if($one->declaration_date != ''){{\Carbon\Carbon::parse($one->declaration_date)->format('d M Y') ?? ''}}@endif</td>                               
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" style="background-color : #e8f3fd!important;">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">Vehicles and Addons Informations (Total Vehicle Count - {{count($workOrder->vehicles) ?? 'No vehicles'}})</center>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
							            <div class="table-responsive" >
                                            <table class="my-datatable table table-striped table-editable table" style="width:100%;">
                                                <tr style="border-bottom:1px solid #b3b3b3; background-color : #e8f3fd!important;">
                                                    <th>Action</th>
                                                    <th>BOE</th>
                                                    <th>VIN</th>
                                                    <th>Brand</th>
                                                    <th>Variant</th>
                                                    <th>Engine</th>
                                                    <th>Model Description</th>
                                                    @php
                                                    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-view-modal-year']);
                                                    @endphp
                                                    @if ($hasPermission)
                                                    <th>Model Year</th>
                                                    @endif
                                                    <th>Document Model Year</th>
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
                                                    <th>Deposit Received</th>
                                                </tr>
                                                @if(isset($workOrder->vehicles) && count($workOrder->vehicles) > 0)
                                                    @foreach($workOrder->vehicles as $vehicle)
                                                    <tr class="custom-border-top" style="background-color : #f6fafe!important;">
                                                        <td>
                                                            @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                                $workOrder->finance_approval_status == 'Approved' && 
                                                                $workOrder->coo_approval_status == 'Approved')                                                           
                                                                <div class="dropdown">
                                                                    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Action">
                                                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                                                    </button>
                                                                    <ul class="dropdown-menu dropdown-menu-start">                                      
                                                                        @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                                            $workOrder->finance_approval_status == 'Approved' && 
                                                                            $workOrder->coo_approval_status == 'Approved' && ($vehicle->modification_or_jobs_to_perform_per_vin != '' || (isset($vehicle->addons) && count($vehicle->addons) > 0)))
                                                                            @php
                                                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['update-vehicle-modification-status']);
                                                                            @endphp
                                                                            @if ($hasPermission)
                                                                                <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatevehModiStatusModal_{{$vehicle->id}}">
                                                                                <i class="fa fa-wrench" aria-hidden="true"></i> Update Modification Status
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                        @php
                                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-modification-status-log']);
                                                                        @endphp
                                                                        @if ($hasPermission && ($vehicle->modification_or_jobs_to_perform_per_vin != '' || (isset($vehicle->addons) && count($vehicle->addons) > 0)))
                                                                            <li>
                                                                                <a class="me-2 btn btn-sm btn-info" style="width:100%; margin-top:2px; margin-bottom:2px;"
                                                                                    href="{{route('vehModiStatusHistory',$vehicle->id)}}">
                                                                                    <i class="fas fa-eye"></i> Modification Status Log
                                                                                </a>
                                                                            </li>
                                                                        @endif	
                                                                        @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                                            $workOrder->finance_approval_status == 'Approved' && 
                                                                            $workOrder->coo_approval_status == 'Approved')
                                                                            @php
                                                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['update-vehicle-pdi-status']);
                                                                            @endphp
                                                                            @if ($hasPermission)
                                                                                <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatevehPDIStatusModal_{{$vehicle->id}}">
                                                                                <i class="fa fa-search-plus" aria-hidden="true"></i> Update PDI Status
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                        @php
                                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-vehicle-pdi-log']);
                                                                        @endphp
                                                                        @if ($hasPermission)
                                                                            <li>
                                                                                <a class="me-2 btn btn-sm btn-info" style="width:100%; margin-top:2px; margin-bottom:2px;"
                                                                                    href="{{route('vehPdiStatusHistory',$vehicle->id)}}">
                                                                                    <i class="fas fa-eye"></i> PDI Status Log
                                                                                </a>
                                                                            </li>
                                                                        @endif	
                                                                        @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                                            $workOrder->finance_approval_status == 'Approved' && 
                                                                            $workOrder->coo_approval_status == 'Approved')
                                                                            @php
                                                                            $hasPermission = Auth::user()->hasPermissionForSelectedRole(['update-wo-vehicle-delivery-status']);
                                                                            @endphp
                                                                            @if ($hasPermission)
                                                                                <a style="width:100%; margin-top:2px; margin-bottom:2px;" class="me-2 btn btn-sm btn-info d-inline-block" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatevehDeliveryStatusModal_{{$vehicle->id}}">
                                                                                <i class="fa fa-car" aria-hidden="true"></i> Update Delivery Status
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                        @php
                                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['wo-vehicle-delivery-status-log']);
                                                                        @endphp
                                                                        @if ($hasPermission)
                                                                            <li>
                                                                                <a class="me-2 btn btn-sm btn-info" style="width:100%; margin-top:2px; margin-bottom:2px;"
                                                                                    href="{{route('vehDeliveryStatusHistory',$vehicle->id)}}">
                                                                                    <i class="fas fa-eye"></i> Delivery Status Log
                                                                                </a>
                                                                            </li>
                                                                        @endif												
                                                                    </ul>
                                                                </div> 
                                                                @include('work_order.export_exw.veh_modi_status_update')   
                                                                @include('work_order.export_exw.veh_pdi_status_update')  
                                                                @include('work_order.export_exw.veh_delivery_status_update')  
                                                            @endif 
                                                        </td>                                                                                                         
                                                        <td>{{$vehicle->boe_number ?? 'NA'}}</td>
                                                        <td>{{$vehicle->vin ?? 'NA'}}</td>
                                                        <td>{{$vehicle->brand ?? 'NA'}}</td>
                                                        <td>{{$vehicle->variant ?? 'NA'}}</td>
                                                        <td>{{$vehicle->engine ?? 'NA'}}</td>
                                                        <td>{{$vehicle->model_description ?? 'NA'}}</td>
                                                        @php
                                                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-view-modal-year']);
                                                        @endphp
                                                        @if ($hasPermission)
                                                        <td>{{$vehicle->model_year ?? 'NA'}}</td>
                                                        @endif
                                                        <td>{{$vehicle->model_year_to_mention_on_documents ?? 'NA'}}</td>
                                                        <td>{{$vehicle->steering ?? 'NA'}}</td>
                                                        <td>{{$vehicle->exterior_colour ?? 'NA'}}</td>
                                                        <td>{{$vehicle->interior_colour ?? 'NA'}}</td>
                                                        <td>{{$vehicle->warehouse ?? 'NA'}}</td>
                                                        <td>{{$vehicle->territory ?? 'NA'}}</td>
                                                        <td>{{$vehicle->preferred_destination ?? 'NA'}}</td>
                                                        <td>{{$vehicle->import_document_type ?? 'NA'}}</td>
                                                        <td>{{$vehicle->ownership_name ?? 'NA'}}</td>
                                                        <td>{{$vehicle->certification_per_vin_name ?? 'NA'}}</td>
                                                        @if(isset($type) && $type == 'export_cnf')
                                                        <td>{{$vehicle->shipment ?? 'NA'}}</td>
                                                        @endif
                                                        <td>{{$vehicle->deposit_received ?? 'NA'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th colspan="2">Modification/Jobs</th>
                                                        <td colspan="17">{{$vehicle->modification_or_jobs_to_perform_per_vin ?? 'NA'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th colspan="2">Special Request/Remarks</th>
                                                        <td colspan="17">{{$vehicle->special_request_or_remarks ?? 'NA'}}</td>
                                                    </tr>
                                                    @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                        $workOrder->finance_approval_status == 'Approved' && 
                                                        $workOrder->coo_approval_status == 'Approved' && ($vehicle->modification_or_jobs_to_perform_per_vin != '' || (isset($vehicle->addons) && count($vehicle->addons) > 0)))
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                @php
                                                                    $badgeClass = '';
                                                                    if ($vehicle->modification_status == 'Initiated') {
                                                                        $badgeClass = 'badge-soft-info';
                                                                    } elseif ($vehicle->modification_status == 'Completed') {
                                                                        $badgeClass = 'badge-soft-success';
                                                                    } elseif ($vehicle->modification_status == 'Not Initiated') {
                                                                        $badgeClass = 'badge-soft-danger';
                                                                    }
                                                                @endphp

                                                                <label style="font-size: 70%; margin-right:3px;" class="float-end badge {{ $badgeClass }}">
                                                                    MODIFICATION : <strong>{{ strtoupper($vehicle->modification_status) ?? '' }}</strong>
                                                                </label>
                                                            </td>
                                                            @if($vehicle->modification_status == 'Initiated')
                                                                <td colspan="3">Exp. Completion : @if(!empty($vehicle->latestModificationStatus->expected_completion_datetime))
                                                                        {{ \Carbon\Carbon::parse($vehicle->latestModificationStatus->expected_completion_datetime)->format('d M Y, h:i:s A') }}
                                                                    @endif
                                                                </td>   
                                                                <td colspan="4">Current Location : {{$vehicle->latestModificationStatus->current_vehicle_location ?? ''}}</td>            
                                                            @elseif($vehicle->modification_status == 'Completed')
                                                                <td colspan="4">Available Location : {{$vehicle->latestModificationStatus->location->name ?? ''}}</td>
                                                            @endif
                                                            <td colspan="
                                                                @if(isset($type) && $type == 'export_cnf') 
                                                                    @if($vehicle->modification_status == 'Initiated') 
                                                                        6
                                                                    @elseif($vehicle->modification_status == 'Completed') 
                                                                        9
                                                                    @elseif($vehicle->modification_status == 'Not Initiated') 
                                                                        13
                                                                    @endif
                                                                @else
                                                                    @if($vehicle->modification_status == 'Initiated') 
                                                                        5
                                                                    @elseif($vehicle->modification_status == 'Completed') 
                                                                        8
                                                                    @elseif($vehicle->modification_status == 'Not Initiated') 
                                                                        12
                                                                    @endif
                                                                @endif">Comment : {{ $vehicle->latestModificationStatus->comment ?? '' }}
                                                            </td>
                                                            <td colspan="5">Updated : {{$vehicle->latestModificationStatus->user->name ?? ''}} - 
                                                                @if(!is_null($vehicle->latestModificationStatus) && !is_null($vehicle->latestModificationStatus->created_at))
                                                                    {{ $vehicle->latestModificationStatus->created_at->format('d M Y,  h:i:s A') }}
                                                                @else
                                                                    Not available
                                                                @endif
                                                            </td>                                                            
                                                        </tr>
                                                    @endif
                                                    @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                        $workOrder->finance_approval_status == 'Approved' && 
                                                        $workOrder->coo_approval_status == 'Approved')
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                @php
                                                                    $badgeClass = '';
                                                                    if ($vehicle->pdi_status == 'Scheduled') {
                                                                        $badgeClass = 'badge-soft-info';
                                                                    } elseif ($vehicle->pdi_status == 'Completed') {
                                                                        $badgeClass = 'badge-soft-success';
                                                                    } elseif ($vehicle->pdi_status == 'Not Initiated') {
                                                                        $badgeClass = 'badge-soft-danger';
                                                                    }
                                                                @endphp

                                                                <label style="font-size: 70%; margin-right:3px;" class="float-end badge {{ $badgeClass }}">
                                                                    PDI : <strong>{{ strtoupper($vehicle->pdi_status) ?? '' }}</strong>
                                                                </label>
                                                            </td>
                                                            @if($vehicle->pdi_status == 'Scheduled')
                                                                <td colspan="3">PDI Sched. Time : @if(!empty($vehicle->latestPdiStatus->pdi_scheduled_at))
                                                                        {{ \Carbon\Carbon::parse($vehicle->latestPdiStatus->pdi_scheduled_at)->format('d M Y, h:i:s A') }}
                                                                    @endif
                                                                </td> 
                                                            @elseif($vehicle->pdi_status == 'Completed') 
                                                                <td>
                                                                    <label class="badge @if($vehicle->latestPdiStatus->passed_status == 'Passed') badge-soft-success @elseif($vehicle->latestPdiStatus->passed_status == 'Failed') badge-soft-danger @endif">QC INSPECTION : <strong>{{ strtoupper($vehicle->latestPdiStatus->passed_status) ?? ''}}</strong></label>
                                                                </td>
                                                                @if($vehicle->latestPdiStatus->passed_status == 'Failed')
                                                                    <td colspan="5">QC Inspection Remarks : {{ $vehicle->latestPdiStatus->qc_inspector_remarks ?? '' }}</td>
                                                                @endif
                                                            @endif
                                                            <td colspan="
                                                                @if(isset($type) && $type == 'export_cnf') 
                                                                    @if($vehicle->pdi_status == 'Scheduled') 
                                                                        10
                                                                    @elseif($vehicle->pdi_status == 'Completed' && $vehicle->latestPdiStatus->passed_status == 'Passed') 
                                                                        10
                                                                    @elseif($vehicle->pdi_status == 'Completed' && $vehicle->latestPdiStatus->passed_status == 'Failed') 
                                                                        9
                                                                    @elseif($vehicle->pdi_status == 'Not Initiated') 
                                                                        13
                                                                    @endif
                                                                @else
                                                                    @if($vehicle->pdi_status == 'Scheduled') 
                                                                        9
                                                                    @elseif($vehicle->pdi_status == 'Completed' && $vehicle->latestPdiStatus->passed_status == 'Passed') 
                                                                        9
                                                                    @elseif($vehicle->pdi_status == 'Completed' && $vehicle->latestPdiStatus->passed_status == 'Failed') 
                                                                        8
                                                                    @elseif($vehicle->pdi_status == 'Not Initiated') 
                                                                        12
                                                                    @endif
                                                                @endif">Comment : {{ $vehicle->latestPdiStatus->comment ?? '' }}
                                                            </td>
                                                            <td colspan="5">Updated : {{$vehicle->latestPdiStatus->user->name ?? ''}} - 
                                                                @if(!is_null($vehicle->latestPdiStatus) && !is_null($vehicle->latestPdiStatus->created_at))
                                                                    {{ $vehicle->latestPdiStatus->created_at->format('d M Y,  h:i:s A') }}
                                                                @else
                                                                    Not available
                                                                @endif
                                                            </td>                                                            
                                                        </tr>
                                                    @endif
                                                    @if($workOrder->sales_support_data_confirmation_at != '' && 
                                                        $workOrder->finance_approval_status == 'Approved' && 
                                                        $workOrder->coo_approval_status == 'Approved')
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                @php
                                                                    $badgeClass = '';
                                                                    if ($vehicle->delivery_status == 'Ready') {
                                                                        $badgeClass = 'badge-soft-info';
                                                                    } elseif ($vehicle->delivery_status == 'Delivered') {
                                                                        $badgeClass = 'badge-soft-success';
                                                                    } elseif ($vehicle->delivery_status == 'On Hold') {
                                                                        $badgeClass = 'badge-soft-danger';
                                                                    } elseif ($vehicle->delivery_status == 'Delivered With Docs Hold') {
                                                                        $badgeClass = 'badge-soft-warning';
                                                                    }
                                                                @endphp
                                                                <label style="font-size: 70%; margin-right: 3px;" class="float-end badge {{ $badgeClass }}">
                                                                    DELIVERY : 
                                                                    <strong>
                                                                        @if(strtoupper($vehicle->delivery_status) == 'DELIVERED')
                                                                            DELIVERED WITH DOCUMENTS
                                                                        @elseif(strtoupper($vehicle->delivery_status) == 'DELIVERED WITH DOCS HOLD')
                                                                            DELIVERED/DOCUMENTS HOLD
                                                                        @else
                                                                            {{ strtoupper($vehicle->delivery_status) ?? '' }}
                                                                        @endif
                                                                    </strong>
                                                                </label>                                                           
                                                            </td>
                                                            @if($vehicle->delivery_status == 'Ready')
                                                                <td colspan="3">Delivery At : @if(!empty($vehicle->latestDeliveryStatus->delivery_at))
                                                                        {{ \Carbon\Carbon::parse($vehicle->latestDeliveryStatus->delivery_at)->format('d M Y') }}
                                                                    @endif
                                                                </td> 
                                                                <td colspan="3">Location : {{ $vehicle->latestDeliveryStatus->locationName->name ?? '' }}</td>

                                                            @elseif($vehicle->delivery_status == 'Delivered') 
                                                                
                                                                    <td colspan="2">GDN Number : {{ $vehicle->latestDeliveryStatus->gdn_number ?? '' }}</td>
                                                                    <td colspan="3">Delivered At : @if(!empty($vehicle->latestDeliveryStatus->delivered_at)){{ \Carbon\Carbon::parse($vehicle->latestDeliveryStatus->delivered_at)->format('d M Y') }}@endif</td>
                                                                    @elseif($vehicle->delivery_status == 'Delivered With Docs Hold')
                                                                <td colspan="3">Delivery At : @if(!empty($vehicle->latestDeliveryStatus->doc_delivery_date))
                                                                        {{ \Carbon\Carbon::parse($vehicle->latestDeliveryStatus->doc_delivery_date)->format('d M Y') }}
                                                                    @endif
                                                                </td> 
                                                            @endif
                                                            <td colspan="
                                                                @if(isset($type) && $type == 'export_cnf') 
                                                                    @if($vehicle->delivery_status == 'Ready') 
                                                                        8
                                                                    @elseif($vehicle->delivery_status == 'Delivered') 
                                                                        8
                                                                    @elseif($vehicle->delivery_status == 'On Hold') 
                                                                        13
                                                                    @elseif($vehicle->delivery_status == 'Delivered With Docs Hold') 
                                                                        10
                                                                    @endif
                                                                @else
                                                                    @if($vehicle->delivery_status == 'Ready') 
                                                                        7
                                                                    @elseif($vehicle->delivery_status == 'Delivered')
                                                                        7
                                                                    @elseif($vehicle->delivery_status == 'On Hold') 
                                                                        12
                                                                    @elseif($vehicle->delivery_status == 'Delivered With Docs Hold') 
                                                                        9
                                                                    @endif
                                                                @endif">Remarks : {{ $vehicle->latestDeliveryStatus->comment ?? '' }}
                                                            </td>
                                                            <td colspan="5">Updated : {{$vehicle->latestDeliveryStatus->user->name ?? ''}} - 
                                                                @if(!is_null($vehicle->latestDeliveryStatus) && !is_null($vehicle->latestDeliveryStatus->created_at))
                                                                    {{ $vehicle->latestDeliveryStatus->created_at->format('d M Y,  h:i:s A') }}dd
                                                                @else
                                                                    Not available
                                                                @endif
                                                            </td>                                                            
                                                        </tr>
                                                    @endif
                                                    @if(isset($vehicle->addons) && count($vehicle->addons) > 0)
                                                        <tr>
                                                            <th></th>
                                                            <th colspan="@if(isset($type) && $type == 'export_cnf') 19 @else 18 @endif">Service Breakdown</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th colspan="2">Created Date & Time</th>
                                                            <th colspan="4">Addon Code</th>
                                                            <th colspan="1">Quantity</th>
                                                            <th colspan="@if(isset($type) && $type == 'export_cnf') 12 @else 11 @endif">Addon Custom Details</th>
                                                        </tr>
                                                            @foreach($vehicle->addons as $addon)
                                                            <tr>
                                                                <td></td>
                                                                <td colspan="2">@if($addon->created_at != ''){{\Carbon\Carbon::parse($addon->created_at)->format('d M Y,  h:i:s A') ?? ''}}@endif</td>
                                                                <td colspan="4">{{$addon->addon_code ?? ''}}</td>
                                                                <td colspan="1">{{$addon->addon_quantity ?? ''}}</td>
                                                                <td colspan="@if(isset($type) && $type == 'export_cnf') 12 @else 11 @endif">{{$addon->addon_description ?? ''}}</td>
                                                            </tr>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="@if(isset($type) && $type == 'export_cnf') 20 @else 19 @endif">
                                                        <center style="font-size:12px;">No vehilces and addons available</center>
                                                    </td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="documents">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">Documents</center>
                                    </h4>
                                </div>
                                <div class="card-body">                                 
                                    <div class="row">
                                        @if($workOrder->brn_file || $workOrder->signed_pfi || $workOrder->signed_contract || $workOrder->payment_receipts ||
                                        $workOrder->noc || $workOrder->enduser_trade_license || $workOrder->enduser_passport || $workOrder->enduser_contract ||
                                        $workOrder->vehicle_handover_person_id)
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->brn_file)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">BRN File</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/brn_file/' . $workOrder->brn_file) }}" alt="BRN File"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->signed_pfi)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Signed PFI</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/signed_pfi/' . $workOrder->signed_pfi) }}" alt="Signed PFI"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->signed_contract)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Signed Contract</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/signed_contract/' . $workOrder->signed_contract) }}" alt="Signed Contract"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->payment_receipts)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Payment Receipts</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/payment_receipts/' . $workOrder->payment_receipts) }}" alt="Payment Receipts"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->noc)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">NOC</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/noc/' . $workOrder->noc) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/noc/' . $workOrder->noc) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/noc/' . $workOrder->noc) }}" alt="NOC"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->enduser_trade_license)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Enduser Trade License</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/enduser_trade_license/' . $workOrder->enduser_trade_license) }}" alt="Enduser Trade License"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->enduser_passport)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Enduser Passport</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/enduser_passport/' . $workOrder->enduser_passport) }}" alt="Enduser Passport"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->enduser_contract)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Enduser Contract</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/enduser_contract/' . $workOrder->enduser_contract) }}" alt="Enduser Contract"></iframe>
                                            @endif
                                        </div>
                                        <div class="col-xxl-4 col-md-4 col-sm-12 text-center mb-5 mt-5">
                                            @if($workOrder->vehicle_handover_person_id)
                                            <div class="row">
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center">
                                                    <h6 class="fw-bold text-center mb-1" style="float:left">Vehicle Handover Person ID</h6>
                                                </div>
                                                <div class="col-xxl-6 col-md-6 col-sm-12 text-center" >
                                                    <a href="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" target="_blank">
                                                    <button class="btn btn-primary m-1 btn-sm" style="float:right">View</button>
                                                    </a>
                                                    <a href="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" download>
                                                    <button class="btn btn-info m-1 btn-sm" style="float:right">Download</button>
                                                    </a>
                                                </div>
                                            </div>
                                            <iframe src="{{ url('wo/vehicle_handover_person_id/' . $workOrder->vehicle_handover_person_id) }}" alt="Vehicle Handover Person ID"></iframe>
                                            @endif
                                        </div>
                                        @else
                                        <p>
                                            <center style="font-size:12px;">No documents available</center>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>                       
                        <div class="tab-pane fade" id="comments_section">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">Comments Section</center>
                                    </h4>
                                </div>
                                <div class="card-body">                            
                                    @include('work_order.export_exw.comments')
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="wo_data_history">
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