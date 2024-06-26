<div class="modal fade" id="approve-employee-leave-request-{{$data->id}}"
	tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Leave Request Approval</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-3">
				<div class="col-lg-12">
					<div class="row mt-2">
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">Approval By Position :</label>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							@if(isset($data->is_auth_user_can_approve['current_approve_position']))
							{{$data->is_auth_user_can_approve['current_approve_position']}}
							@endif
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">Approval By Name :</label>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							@if(isset($data->is_auth_user_can_approve['current_approve_person']))
							{{$data->is_auth_user_can_approve['current_approve_person']}}
							@endif
						</div>
						@if(isset($data->is_auth_user_can_approve['current_approve_position']))
						<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
						@endif
						@if(isset($data->is_auth_user_can_approve['current_approve_position']) && $data->is_auth_user_can_approve['current_approve_position'] == 'Employee')
						<input class="form-check-input" type="checkbox" name="comment_check" id="comment-check-{{$data->id}}" data-id="{{ $data->id }}">
						I do confirm that I will report back to duty on the due date as approved by the Management, otherwise the Company will consider me as an absentee as per the Law.
						<span id="checkError_{{$data->id}}" class="required-class invalid-feedback"></span>
						@elseif(isset($data->is_auth_user_can_approve['current_approve_position']) && $data->is_auth_user_can_approve['current_approve_position'] == 'HR Manager')
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">Passport Expiry :</label>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->passport_expiry_date != NULL)
							{{\Carbon\Carbon::parse($data->user->empProfile->passport_expiry_date)->format('d M Y') ?? ''}}
							@endif
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">Visa Expiry :</label>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							@if(isset($data) && isset($data->user) && isset($data->user->empProfile) && $data->user->empProfile->visa_expiry_date != NULL)
							{{\Carbon\Carbon::parse($data->user->empProfile->visa_expiry_date)->format('d M Y') ?? ''}}
							@endif
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">Advance/Loan Balance :</label>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							{{$data->user->advance_or_loan_balance ?? ''}} AED
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">Others :</label>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3">
							<input type="text" name="others" id="others_{{$data->id}}"
								class="form-control widthinput" placeholder="Others"
								aria-label="measurement" aria-describedby="basic-addon2" value="">
						</div>
						@elseif(isset($data->is_auth_user_can_approve['current_approve_position']) && $data->is_auth_user_can_approve['current_approve_position'] == 'Reporting Manager')
						<div class="col-lg-3 col-md-3 col-sm-3">
							<label class="form-label font-size-13">To Be Replaced By :</label>
						</div>
						<div class="col-lg-9 col-md-9 col-sm-9">
							<select class="form-control widthinput to_be_replaced_by" name="to_be_replaced_by" multiple id="to_be_replaced_by_{{$data->id}}" style="width:100%;">
								@foreach($leavePersonReplacedBy as $employee)
								<option value="{{$employee->id}}" @if($employee->id == $data->employee_id) disabled @endif>{{$employee->name}}</option>
								@endforeach
							</select>
						</div>
						@endif
						@if(isset($data->is_auth_user_can_approve['current_approve_position']) && $data->is_auth_user_can_approve['current_approve_position'] != 'Employee')
						<div class="row mt-2">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<label class="form-label font-size-13">Comments</label>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12">
								<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment"></textarea>
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-success status-approve-button"
					data-id="{{ $data->id }}" data-status="approved">Approved</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="reject-employee-leave-request-{{$data->id}}"
	tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Employee leave Request Rejection</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-3">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-12">
							<div class="row mt-2">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<label class="form-label font-size-13">Rejection By Position</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									@if(isset($data->is_auth_user_can_approve['current_approve_position']))
									{{$data->is_auth_user_can_approve['current_approve_position']}}
									@endif
								</div>
							</div>
							<div class="row mt-2">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<label class="form-label font-size-13">Rejection By Name</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									@if(isset($data->is_auth_user_can_approve['current_approve_person']))
									{{$data->is_auth_user_can_approve['current_approve_person']}}
									@endif
								</div>
							</div>
							@if(isset($data->is_auth_user_can_approve['current_approve_position']))
							<input hidden id="current_approve_position_{{$data->id}}" name="current_approve_position" value="{{$data->is_auth_user_can_approve['current_approve_position']}}">
							@endif
							<div class="row mt-2">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<label class="form-label font-size-13">Comments</label>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12">
									<textarea rows="5" id="reject-comment-{{$data->id}}" class="form-control" name="comment"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-danger  status-reject-button" data-id="{{ $data->id }}"
					data-status="rejected">Reject</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		var countReportingManagerPendings = 0;
		countReportingManagerPendings = ReportingManagerPendings.length;
		if(countReportingManagerPendings > 0 ) {
			for(var i=0; i<countReportingManagerPendings; i++) {
				$('#to_be_replaced_by_'+ReportingManagerPendings[i].id).select2({
					allowClear: true,
					placeholder:"Choose To Be Replaced By Employee name",
					dropdownParent: $('#approve-employee-leave-request-'+ReportingManagerPendings[i].id)
				});
			}
		}
		var comment = '';
	    $('.status-reject-button').click(function (e) {
			var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			comment = $("#reject-comment-"+id).val();
			approveOrRejectLeaveRequest(id, status,comment)
		})
		$('.status-approve-button').click(function (e) {
			var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			comment = $("#comment-"+id).val();
			if($("#current_approve_position_"+id).val() == 'Employee') {
				var isChecked=$("#comment-check-"+id).is(":checked");
				if(isChecked == true) {
					removecheckedError(id);
					approveOrRejectLeaveRequest(id, status,comment)
				}
				else {
					showcheckedError(id);
				}
			}
			else {
				approveOrRejectLeaveRequest(id, status,comment)
			}
		})
		function showcheckedError(id) {
		document.getElementById("checkError_"+id).textContent='Please check the box';
		document.getElementById("comment-check-"+id).classList.add("is-invalid");
		document.getElementById("checkError_"+id).classList.add("paragraph-class");
		}
		function removecheckedError(id) {
		document.getElementById("checkError_"+id).textContent="";
		document.getElementById("comment-check-"+id).classList.remove("is-invalid");
		document.getElementById("checkError_"+id).classList.remove("paragraph-class");
		}
		function approveOrRejectLeaveRequest(id, status,comment) { 
			var current_approve_position = $("#current_approve_position_"+id).val(); 
			if(current_approve_position == 'Employee') {
				var isChecked = $("#comment-check-"+id).val();
				if(isChecked == 'on' && status == 'approved') {
					comment = "I do confirm that I will report back to duty on the due date as approved by the Management, otherwise the Company will consider me as an absentee as per the Law.";
				}
			}
			var others = '';
			if(current_approve_position == 'HR Manager') {
				others = $("#others_"+id).val();
			}
			var to_be_replaced_by = '';
			if(current_approve_position == 'Reporting Manager') {
				to_be_replaced_by = $("#to_be_replaced_by_"+id).val();
			}
			let url = '{{ route('leaveRequest.action') }}'; 
			if(status == 'rejected') {
				var message = 'Reject';
			}
			else {
				var message = 'Approve';
			}
			var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee leave request ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							status: status,
							comment: comment,
							others: others,
							current_approve_position: current_approve_position,
							to_be_replaced_by: to_be_replaced_by,
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
	});
</script>