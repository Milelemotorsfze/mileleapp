@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents','send-candidate-documents-request-form']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">
		Candidate Documents Verification
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
			<a class="nav-link active" data-bs-toggle="pill" href="#data-candidate-info">Verification Awaiting</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-bs-toggle="pill" href="#verified-candidate-info">Verified</a>
		</li>
	</ul>
</div>
<div class="tab-content" id="selling-price-histories" >
	<div class="tab-pane fade show active" id="data-candidate-info">
		<div class="card-body">
			<div class="table-responsive">
				<table id="data-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Father’s Full Name</th>
							<th>Mother’s Full Name</th>
							<th>Marital Status</th>
							<th>Passport Number</th>
							<th>Passport Expiry Date</th>
							<th>Educational Qualification</th>
							<th>Year of Completion</th>
							<th>Religion</th>
							<th>Date Of Birth</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pending as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->first_name ?? $data->interviewSummary->candidate_name ?? '' }}</td>
							<td>{{ $data->last_name ?? '' }}</td>
							<td>{{ $data->name_of_father ?? '' }}</td>
							<td>{{ $data->name_of_mother ?? '' }}</td>
							<td>{{ $data->maritalStatus->name ?? '' }}</td>
							<td>{{ $data->passport_number ?? '' }}</td>
							<td>@if(isset($data)&& $data->passport_expiry_date != ''){{\Carbon\Carbon::parse($data->passport_expiry_date)->format('d M Y')}} @endif</td>
							<td>{{ $data->educational_qualification ?? ''}}</td>
							<td>{{ $data->year_of_completion ?? ''}}</td>
							<td>{{ $data->religionName->name ?? ''}}</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('d M Y') ?? ''}}@endif</td>
							<td>{{ $data->replacement_for_employee_name ?? ''}}</td>
							<td>{{ $data->explanation_of_new_hiring ?? ''}}</td>
							<td>
								<a style="width:100%; margin-top:2px; margin-bottom:2px;" title="Candidate Details" class="btn btn-sm btn-primary" 
									href="{{route('interview-summary-report.show', $data->interview_summary_id)}}">
								<i class="fa fa-user" aria-hidden="true"></i> Candidate Details
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="tab-pane fade show" id="verified-candidate-info">
		<div class="card-body">
			<div class="table-responsive">
				<table id="data-selling-price-histories-table" class="table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Father’s Full Name</th>
							<th>Mother’s Full Name</th>
							<th>Marital Status</th>
							<th>Passport Number</th>
							<th>Passport Expiry Date</th>
							<th>Educational Qualification</th>
							<th>Year of Completion</th>
							<th>Religion</th>
							<th>Date Of Birth</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($verified as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->first_name ?? $data->interviewSummary->candidate_name ?? '' }}</td>
							<td>{{ $data->last_name ?? '' }}</td>
							<td>{{ $data->name_of_father ?? '' }}</td>
							<td>{{ $data->name_of_mother ?? '' }}</td>
							<td>{{ $data->maritalStatus->name ?? '' }}</td>
							<td>{{ $data->passport_number ?? '' }}</td>
							<td>@if(isset($data) && $data->passport_expiry_date != ''){{\Carbon\Carbon::parse($data->passport_expiry_date)->format('d M Y')}} @endif</td>
							<td>{{ $data->educational_qualification ?? ''}}</td>
							<td>{{ $data->year_of_completion ?? ''}}</td>
							<td>{{ $data->religionName->name ?? ''}}</td>
							<td>@if($data->dob != ''){{\Carbon\Carbon::parse($data->dob)->format('d M Y') ?? ''}}@endif</td>
							<td>{{ $data->replacement_for_employee_name ?? ''}}</td>
							<td>{{ $data->explanation_of_new_hiring ?? ''}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
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