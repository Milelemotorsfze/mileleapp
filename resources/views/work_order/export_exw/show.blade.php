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
            margin-left: 30px; /* Indent replies by 40px */
            margin-top: 10px;
        }
        .reply-button {
            margin-top: 10px;
        }
        .replies {
            margin-left: 30px; /* Indent nested replies by 40px */
        }
	.texttransform {
	text-transform: capitalize;
	}
	/* element.style {
	} */
	.nav-fill .nav-item .nav-link, .nav-justified .nav-item .nav-link {
	width: 99%;
	border: 1px solid #4ba6ef !important;
	background-color: #c1e1fb !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	color: black!important;
	background-image: linear-gradient(to right,#4ba6ef,#4ba6ef,#0065ac)!important;
	}
	.nav-link:focus{
	color: black!important;
	}
	.nav-link:hover {
	color: black!important;
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
        /* border-collapse: collapse; */
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
    /* table.dataTable {
        border-collapse: none!important;
    } */

/* Style for the table headers */
.my-datatable th {
    border-left: 1px solid #e9e9ef; /* Add a left border to each header cell */
    border-right: 1px solid #e9e9ef; /* Add a right border to each header cell */
    border-top: 1px solid #e9e9ef; /* Add a top border to each header cell */
    border-bottom: 1px solid #e9e9ef; /* Add a bottom border to each header cell */
    padding: 2px; /* Add padding for better readability */
    text-align: left; /* Align text to the left */
}

/* Style for the table cells */
.my-datatable td {
    border-left: 1px solid #e9e9ef; /* Add a left border to each cell */
    border-right: 1px solid #e9e9ef; /* Add a right border to each cell */
    border-top: 1px solid #e9e9ef; /* Add a top border to each cell */
    border-bottom: 1px solid #e9e9ef; /* Add a bottom border to each cell */
    padding: 2px; /* Add padding for better readability */
    text-align: left; /* Align text to the left */
}

/* Style for the entire table */
.my-datatable {
    border-collapse: collapse; /* Ensure borders do not double */
    width: 100%; /* Make the table take up the full width */
}

.custom-border-top {
    border-top: 2px solid #b3b3b3; /* Add a custom top border to rows with this class */
}
</style>
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['export-exw-wo-details','export-cnf-wo-details','local-sale-wo-details']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title form-label">@if(isset($workOrder) && $workOrder->type == 'export_exw') Export EXW @elseif(isset($workOrder) && $workOrder->type == 'export_cnf') Export CNF @elseif(isset($workOrder) && $workOrder->type == 'local_sale') Local Sale @endif Work Order Details</h4>
	@if($previous != '')
	<a  class="btn btn-sm btn-info float-first form-label" href="{{ route('work-order.show',$previous) }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous Record</a>
	@endif
	@if($next != '')
	<a  class="btn btn-sm btn-info float-first form-label" href="{{ route('work-order.show',$next) }}" >Next Record <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
	@endif
	<a  class="btn btn-sm btn-info float-end form-label" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
    <div class="row">
        <div class="col-lg-11 col-md-11 col-sm-11 col-11">    
            @include('work_order.export_exw.approvals')
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-1">
            <a style="margin-top:0px; margin-bottom:1.25rem; float:left;" title="Edit" class="btn btn-sm btn-info" href="{{route('work-order.edit',$workOrder->id ?? '')}}">
                <i class="fa fa-edit" aria-hidden="true"></i> Edit
            </a>
        </div>
    </div>
   
	<div class="tab-content">
		<div class="tab-pane fade show active" id="requests">
			<br>
			<div class="card">
				<div class="card-header" style="background-color:#e8f3fd;">
					<div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> Date</strong></label></center>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><span class="data-font">@if($workOrder->date != ''){{\Carbon\Carbon::parse($workOrder->date)->format('d M Y') ?? ''}}@endif</span></center>
							</div>
						</div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> WO Number</strong></label></center>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><span class="data-font">{{ $workOrder->wo_number ?? '' }}</span></center>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-6 col-12">
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><label for="choices-single-default" class="form-label"> <strong> SO Number </strong></label></center>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12">
								<center><span class="data-font">{{ $workOrder->so_number ?? '' }}</span></center>
							</div>
						</div>
                        @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
                            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <center><label for="choices-single-default" class="form-label"> <strong> Batch </strong></label></center>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <center><span class="data-font">{{ $workOrder->batch ?? '' }}</span></center>
                                </div>
                            </div>
                        @endif
					</div>
				</div>
				<div class="card-body">
                    <div class="portfolio">
                        <ul class="nav nav-pills nav-fill" id="my-tab">
                            <li class="nav-item">
                                <a class="nav-link active form-label" data-bs-toggle="pill" href="#general-info"> General Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link form-label" data-bs-toggle="pill" href="#vehicles_addons"> Vehicles & Addons</a>
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
                                <div class="card-header">
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
                                                            <label for="choices-single-default" class="form-label"> Customer Company Email </label>
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
                                                            <label for="choices-single-default" class="form-label"> Customer Representative Name</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_representative_name ?? 'NA'}}</span>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Representative Email</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->customer_representative_email ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Customer Representative Contact</label>
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
                                                                <label for="choices-single-default" class="form-label"> Freight Agent Contact Number </label>
                                                            </div>
                                                            <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                                <span class="data-font">{{$workOrder->freight_agent_contact_number ?? 'NA'}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif 
                                                @if(isset($type) && ($type == 'export_exw' || $type == 'export_cnf'))
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
                                                            <label for="choices-single-default" class="form-label"> Delivery Contact Person Name</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->delivery_contact_person ?? 'NA'}}</span>
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
                                                            <label for="choices-single-default" class="form-label"> Prefered Shipping Line for Customer</label>
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
                                                            <label for="choices-single-default" class="form-label"> Special/In Transit/Other Requests</label>
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
                                                            <span class="data-font">@if($workOrder->created_at != ''){{\Carbon\Carbon::parse($workOrder->created_at)->format('d M Y, H:i:s') ?? 'NA'}}@endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Updated By</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">{{$workOrder->UpdatedBy->name ?? 'NA'}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Updated At </label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->updated_at != '' && $workOrder->updated_at != $workOrder->created_at){{\Carbon\Carbon::parse($workOrder->updated_at)->format('d M Y, H:i:s') ?? 'NA'}} @else NA @endif</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-sm-6 col-12">
                                                            <label for="choices-single-default" class="form-label"> Total Number Of BOE:</label>
                                                        </div>
                                                        <div class="col-lg-7 col-md-7 col-sm-6 col-12">
                                                            <span class="data-font">@if($workOrder->total_number_of_boe == 0){{$workOrder->total_number_of_boe ?? ''}}@endif</span>
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
                                                            <label for="choices-single-default" class="form-label">Vehicle Handover To Person ID</label>
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
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="vehicles_addons">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">
                                        <center style="font-size:12px;">Vehicles and Addons Informations</center>
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="my-datatable table table-striped table-editable table-edits table" style="width:100%;">
                                                <tr style="border-bottom:1px solid #b3b3b3;">
                                                    <th>BOE</th>
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
                                                    <th>Deposit Received</th>
                                                </tr>
                                                @if(isset($workOrder->vehicles) && count($workOrder->vehicles) > 0)
                                                    @foreach($workOrder->vehicles as $vehicle)
                                                    <tr class="custom-border-top">
                                                        <td>{{$vehicle->boe_number ?? 'NA'}}</td>
                                                        <td>{{$vehicle->vin ?? 'NA'}}</td>
                                                        <td>{{$vehicle->brand ?? 'NA'}}</td>
                                                        <td>{{$vehicle->variant ?? 'NA'}}</td>
                                                        <td>{{$vehicle->engine ?? 'NA'}}</td>
                                                        <td>{{$vehicle->model_description ?? 'NA'}}</td>
                                                        <td>{{$vehicle->model_year ?? 'NA'}}</td>
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
                                                        <th colspan="2">Modification/Jobs</th>
                                                        <td colspan="17">{{$vehicle->modification_or_jobs_to_perform_per_vin ?? 'NA'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="2">Special Request/Remarks</th>
                                                        <td colspan="17">{{$vehicle->special_request_or_remarks ?? 'NA'}}</td>
                                                    </tr>
                                                        @if(isset($vehicle->addons) && count($vehicle->addons) > 0)
                                                        <tr>
                                                            <th colspan="19">Service Breakdown</th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="2">Created Date & Time</th>
                                                            <th colspan="4">Addon Code</th>
                                                            <th colspan="1">Quantity</th>
                                                            <th colspan="12">Addon Description</th>
                                                        </tr>
                                                            @foreach($vehicle->addons as $addon)
                                                            <tr>
                                                                <td colspan="2">@if($addon->created_at != ''){{\Carbon\Carbon::parse($addon->created_at)->format('d M Y, H:i:s') ?? ''}}@endif</td>
                                                                <td colspan="4">{{$addon->addon_code ?? ''}}</td>
                                                                <td colspan="1">{{$addon->addon_quantity ?? ''}}</td>
                                                                <td colspan="12">{{$addon->addon_description ?? ''}}</td>
                                                            </tr>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="19">
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
<!-- <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script> -->
<script type="text/javascript">
    $(document).ready(function () { 
		// $('.my-datatable').DataTable();
    });
</script>
@endsection