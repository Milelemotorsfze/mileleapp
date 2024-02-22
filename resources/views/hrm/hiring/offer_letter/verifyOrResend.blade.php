@extends('layouts.table')
	@section('content')
		@canany(['verify-candidates-documents','send-candidate-documents-request-form'])
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
								<table id="data-selling-price-histories-table" class="table table-striped table-editable table-edits table">
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
											<td>{{\Carbon\Carbon::parse($data->interviewSummary->offer_letter_send_at)->format('d M Y') ?? ''}}</td>
											<td>{{ $data->interviewSummary->offerLetterSendBy->name ?? ''}}</td>
											<td>{{\Carbon\Carbon::parse($data->interviewSummary->offer_signed_at)->format('d M Y') ?? ''}}</td>
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
											<td>{{\Carbon\Carbon::parse($data->interviewSummary->offer_letter_send_at)->format('d M Y') ?? ''}}</td>
											<td>{{ $data->interviewSummary->offerLetterSendBy->name ?? ''}}</td>
											<td>{{\Carbon\Carbon::parse($data->interviewSummary->offer_signed_at)->format('d M Y') ?? ''}}</td>
											<td>{{ $data->interviewSummary->offerLetterVerifiedBy->name ?? ''}}</td>											
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
		@endif
        @endcanany  
@endsection
@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
	    $('.status-reject-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
	    $('.status-approve-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
	    function approveOrRejectHiringrequest(id, status) {
			var comment = $("#comment-"+id).val();
			var current_approve_position = $("#current_approve_position_"+id).val();
	        let url = '{{ route('employee-hiring-request.request-action') }}';
	        if(status == 'rejected') {
	            var message = 'Reject';
	        }else{
	            var message = 'Approve';
	        }
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee hiring request ?',function (e) {
	            if (e) {
	                $.ajax({
	                    type: "POST",
	                    url: url,
	                    dataType: "json",
	                    data: {
	                        id: id,
	                        status: status,
	                        comment: comment,
							current_approve_position: current_approve_position,
	                        _token: '{{ csrf_token() }}'
	                    },
	                    success: function (data) {
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {

							}
	                    }
	                });
	            }
	
	        }).set({title:"Confirmation"})
	    }
	})
	function inputNumberAbs(currentPriceInput) 
	{
	    var id = currentPriceInput.id;
	    var input = document.getElementById(id);
	    var val = input.value;
	    val = val.replace(/^0+|[^\d.]/g, '');
	    if(val.split('.').length>2) 
	    {
	        val =val.replace(/\.+$/,"");
	    }
	    input.value = val;
	}
</script>
@endpush