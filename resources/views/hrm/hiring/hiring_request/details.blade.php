<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<style>
	@media (max-width: 575) {
	.col-lg-4.col-md-3.col-sm-6.col-12 span {
	padding-bottom: 20px;
	/* Adjust the value as needed */
	display: block;
	/* Ensure the span is a block-level element */
	}
	}
</style>
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-all-hiring-request-details','view-hiring-request-details-of-current-user']);
@endphp
@if ($hasPermission)
<div class="row">
	<div class="col-xxl-12 col-lg-12 col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-lg-2 col-md-3 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Request Date :</label>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-6 col-12">
						<span>
						@if($data->request_date != '')
						{{\Carbon\Carbon::parse($data->request_date)->format('d M Y') ?? ''}}											
						@endif
						</span>
					</div>
					<div class="col-lg-2 col-md-3 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Current Status :</label>
					</div>
					<div class="col-lg-4 col-md-3 col-sm-6 col-12">
						@if($data->status == 'rejected')
						<label class="badge badge-soft-danger">{{ $data->current_status ?? '' }}</label>
						@elseif($data->status == 'approved')
						<label class="badge badge-soft-success">{{ $data->current_status ?? '' }}</label>
						@else
						<label class="badge badge-soft-info">{{ $data->current_status ?? '' }}</label>
						@endif
					</div>
					<br>
					@if($data->status == 'approved' && $data->final_status == 'closed')
					<p>Closed At : @if($data->closed_comment != '')
						{{ \Carbon\Carbon::parse($data->closed_comment)->format('d M Y, H:i:s') }}
						@endif , 
						Closed By : {{$data->closedBy->name ?? ''}}</br>
						@if($data->closed_comment != '')
						Closed Comment : {{$data->closed_comment}}
						@endif
					</p>
					@elseif($data->status == 'approved' && $data->final_status == 'onhold')
					<p>On Hold At : @if($data->on_hold_at != '')
						{{ \Carbon\Carbon::parse($data->on_hold_at)->format('d M Y, H:i:s') }}
						@endif , 
						On Hold By : {{$data->onHoldBy->name ?? ''}}</br>
						@if($data->on_hold_comment != '')
						On Hold Comment : {{$data->on_hold_comment}}
						@endif
					</p>
					@elseif($data->status == 'approved' && $data->final_status == 'cancelled')
					<p>Cancelled At : @if($data->cancelled_at != '')
						{{ \Carbon\Carbon::parse($data->cancelled_at)->format('d M Y, H:i:s') }}
						@endif
						, 
						Cancelled By : {{$data->cancelledBy->name ?? ''}}</br>
						@if($data->cancelled_comment != '')
						Cancelled Comment : {{$data->cancelled_comment}}
						@endif
					</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xxl-6 col-lg-6 col-md-12">
		<div class="col-xxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Department Information</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Department Name :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->department_name ?? '' }}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Department Location :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->department_location ?? '' }} </br> {{ $data->location->address ?? ''}}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12">
							<label for="choices-single-default" class="form-label"> Requested By :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->requested_by_name ?? '' }}</span>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-6 col-12" >
							<label for="choices-single-default" class="form-label"> Requested Job Title :</label>
						</div>
						<div class="col-lg-7 col-md-7 col-sm-6 col-12">
							<span>{{ $data->requested_job_name ?? '' }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xxl-6 col-lg-6 col-md-12 col-sm-12 col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Position Information</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-5 col-md-5 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Experience Level :</label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-6 col-12">
						<span>{{ $data->experience_level_name ?? '' }}</span>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Salary Range(AED) :</label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-6 col-12">
						<span>{{ $data->salary_range_start_in_aed ?? ''}} - {{$data->salary_range_end_in_aed ?? ''}}</span>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Work Time :</label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-6 col-12">
						<span>{{ $data->work_time_start ?? ''}} - {{$data->work_time_end ?? ''}}</span>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Number Of Openings :</label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-6 col-12">
						<span>{{ $data->number_of_openings ?? '' }}</span>
					</div>
					<div class="col-lg-5 col-md-5 col-sm-6 col-12">
						<label for="choices-single-default" class="form-label"> Type Of Role :</label>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-6 col-12">
						<span>
						{{ $data->type_of_role_name ?? '' }}
						@if($data->type_of_role_name == 'Replacement')
						( for {{ $data->replacement_for_employee_name ?? '' }})
						@endif
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xxl-12 col-lg-12 col-md-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Detailed Explanation Of New Hiring</h4>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<span>{{$data->explanation_of_new_hiring ?? ''}}</span>
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
