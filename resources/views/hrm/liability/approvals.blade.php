@extends('layouts.table')
@section('content')
@if(Auth::user()->liability_request_approval['can'] == true)
@if(count($employeePendings) > 0 || count($employeeApproved) > 0 || count($employeeRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Liability Request Approvals By Employee
	</h4>
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
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#employee-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#employee-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#employee-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="employee-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeePendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.liability.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="employee-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="employee-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($employeeRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($ReportingManagerPendings) > 0 || count($ReportingManagerApproved) > 0 || count($ReportingManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Liability Request Approvals By Reporting Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#reporting-manager-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#reporting-manager-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#reporting-manager-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="reporting-manager-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>												
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true )
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.liability.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="reporting-manager-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="reporting-manager-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($ReportingManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($financeManagerPendings) > 0 || count($financeManagerApproved) > 0 || count($financeManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Liability Request Approvals By Finance Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#finance-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#finance-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#finance-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="finance-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($financeManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.liability.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="finance-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($financeManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="finance-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($financeManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($HRManagerPendings) > 0 || count($HRManagerApproved) > 0 || count($HRManagerRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Liability Request Approvals By HR Manager
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#hr-manager-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-manager-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#hr-manager-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="hr-manager-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.liability.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hr-manager-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="hr-manager-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($HRManagerRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
</br>
@if(count($divisionHeadPendings) > 0 || count($divisionHeadApproved) > 0 || count($divisionHeadRejected) > 0)
<div class="card-header">
	<h4 class="card-title">
		Employee Liability Request Approvals By Division Head
	</h4>
</div>
<div class="portfolio">
	<ul class="nav nav-pills nav-fill" id="my-tab">
		<li class="nav-item">
			<a class="nav-link active" data-bs-toggle="pill" href="#division-head-pending-joining-report">Pending </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#division-head-approved-joining-report">Approved </a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#division-head-rejected-joining-report">Rejected </a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="division-head-pending-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="pending-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadPendings as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
								@if(isset($data->is_auth_user_can_approve) && $data->is_auth_user_can_approve != '')
								@if(isset($data->is_auth_user_can_approve['can_approve']))
								@if($data->is_auth_user_can_approve['can_approve'] == true)
								<button title="Approve" type="button" class="btn btn-success btn-sm"  data-bs-toggle="modal"
									data-bs-target="#approve-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-up" aria-hidden="true"></i> 
								</button>
								<button title="Reject" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
									data-bs-target="#reject-employee-liability-request-{{$data->id}}">
								<i class="fa fa-thumbs-down" aria-hidden="true"></i> 
								</button>
								@endif
								@endif
								@endif
							</td>
							@include('hrm.liability.approve_reject_modal')
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="division-head-approved-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="approved-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadApproved as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="division-head-rejected-joining-report">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rejected-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Date</th>
							<th>Liability Type</th>
							<th>Liability Code</th>
							<th>Employee Name</th>
							<th>Designation</th>
							<th>Passport Number</th>
							<th>Joining Date</th>
							<th>Department</th>
							<th>Location</th>
							<th>Total Amount</th>
							<th>Number of Installments</th>
							<th>Amount per Installment</th>
							<th>Reason</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($divisionHeadRejected as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>
								@if($data->request_date != '')
								{{\Carbon\Carbon::parse($data->request_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->liability_type ?? '' }}</td>
							<td>{{ $data->liability_code ?? '' }}</td>
							<td>{{ $data->user->name ?? '' }}</td>
							<td>{{ $data->user->empProfile->designation->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->passport_number ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->company_joining_date != '')
								{{\Carbon\Carbon::parse($data->user->empProfile->company_joining_date)->format('d M Y')}}
								@endif												
							</td>
							<td>{{ $data->user->empProfile->department->name ?? ''}}</td>
							<td>{{ $data->user->empProfile->location->name ?? ''}}</td>
							<td>{{ $data->total_amount ?? ''}}</td>
							<td>{{ $data->no_of_installments ?? ''}}</td>
							<td>{{ $data->amount_per_installment ?? ''}}</td>
							<td>{{ $data->reason ?? ''}}</td>
							<td>
								<a title="View Details" class="btn btn-sm btn-warning" href="{{route('employee_liability.show',$data->id)}}">
								<i class="fa fa-eye" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endif
@endif
@endsection