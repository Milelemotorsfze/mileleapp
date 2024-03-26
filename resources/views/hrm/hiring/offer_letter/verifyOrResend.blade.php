@extends('layouts.table')
@section('content')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['verify-candidates-documents','send-candidate-documents-request-form']);
@endphp
@if ($hasPermission)
<div class="card-header">
	<h4 class="card-title">
		Candidate Offer Letter Signature Verification
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
				<table id="data-selling-price-histories-table" class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Name</th>
							<th>Designation</th>
							<th>Email</th>
							<th>Contact Number</th>
							<th>Passport Number</th>
							<th>probation Period(Months)</th>
							<th>Basic Salary(AED Per Month)</th>
							<th>Other Allowances(AED Per Month)</th>
							<th>Total Salary(AED Per Month)</th>
							<th>Offer Letter Send At</th>
							<th>Offer Letter Send By</th>
							<th>Offer Letter Signed At</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($pending as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->interviewSummary->candidate_name ?? '' }}</td>
							<td>{{ $data->designation->name ?? '' }}</td>
							<td>{{ $data->interviewSummary->email ?? '' }}</td>
							<td>{{ $data->contact_number ?? '' }}</td>
							<td>{{ $data->passport_number ?? '' }}</td>
							<td>{{ $data->probation_duration_in_months ?? '' }}</td>
							<td>{{ $data->basic_salary ?? ''}}</td>
							<td>{{ $data->other_allowances ?? ''}}</td>
							<td>{{ $data->total_salary ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->interviewSummary) && $data->interviewSummary->offer_letter_send_at != '')
								{{\Carbon\Carbon::parse($data->interviewSummary->offer_letter_send_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->interviewSummary->offerLetterSendBy->name ?? ''}}</td>
							<td>
								@if(isset($data) && $data->offer_signed_at != '')
								{{\Carbon\Carbon::parse($data->interviewSummary->offer_signed_at)->format('d M Y') ?? ''}}
								@endif
							</td>
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
				<table id="data-selling-price-histories-table" class="my-datatable table table-striped table-editable table-edits table">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Name</th>
							<th>Designation</th>
							<th>Email</th>
							<th>Contact Number</th>
							<th>Passport Number</th>
							<th>probation Period(Months)</th>
							<th>Basic Salary(AED Per Month)</th>
							<th>Other Allowances(AED Per Month)</th>
							<th>Total Salary(AED Per Month)</th>
							<th>Offer Letter Send At</th>
							<th>Offer Letter Send By</th>
							<th>Offer Letter Signed At</th>
							<th>Offer Letter Sign Verified By</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<div hidden>{{$i=0;}}</div>
						@foreach ($verified as $key => $data)
						<tr data-id="1">
							<td>{{ ++$i }}</td>
							<td>{{ $data->interviewSummary->candidate_name ?? '' }}</td>
							<td>{{ $data->designation->name ?? '' }}</td>
							<td>{{ $data->interviewSummary->email ?? '' }}</td>
							<td>{{ $data->contact_number ?? '' }}</td>
							<td>{{ $data->passport_number ?? '' }}</td>
							<td>{{ $data->probation_duration_in_months ?? '' }}</td>
							<td>{{ $data->basic_salary ?? ''}}</td>
							<td>{{ $data->other_allowances ?? ''}}</td>
							<td>{{ $data->total_salary ?? ''}}</td>
							<td>
								@if(isset($data) && isset($data->interviewSummary) && $data->interviewSummary->offer_letter_send_at != '')
								{{\Carbon\Carbon::parse($data->interviewSummary->offer_letter_send_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->interviewSummary->offerLetterSendBy->name ?? ''}}</td>
							<td>
								@if(isset($data) && $data->offer_signed_at != '')
								{{\Carbon\Carbon::parse($data->offer_signed_at)->format('d M Y') ?? ''}}
								@endif
							</td>
							<td>{{ $data->interviewSummary->offerLetterVerifieddBy->name ?? ''}}</td>
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
</div>
@else
<div class="card-header">
	<p class="card-title">Sorry ! You don't have permission to access this page</p>
	<a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
	<a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection
