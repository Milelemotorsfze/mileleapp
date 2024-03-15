<div class="modal fade" id="approve-employee-overtime-request-{{$data->id}}"
	tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Overtime Request Approval</h1>
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
						I agreed that no employee will be paid for overtime unless this form has been completed and approved by the direct manager and overtime will be paid on a basic salary. 
						<span id="checkError_{{$data->id}}" class="required-class invalid-feedback"></span>													
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
<div class="modal fade" id="reject-employee-overtime-request-{{$data->id}}"
	tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Overtime Request Rejection</h1>
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
									<textarea rows="5" id="comment-{{$data->id}}" class="form-control" name="comment"></textarea>
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
        $('.status-reject-button').click(function (e) {
	        var id = $(this).attr('data-id');
	        var status = $(this).attr('data-status');
	        approveOrRejectHiringrequest(id, status)
	    })
	    $('.status-approve-button').click(function (e) {
	        var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			if($("#current_approve_position_"+id).val() == 'Employee') {
				var isChecked=$("#comment-check-"+id).is(":checked");
				if(isChecked == true) {
					removeAddonTypeError(id);
					approveOrRejectHiringrequest(id, status)
				}
				else {
					showAddonTypeError(id);
				}
			}
			else {
				approveOrRejectHiringrequest(id, status)
			}
	    })
		function showAddonTypeError(id) {
			document.getElementById("checkError_"+id).textContent='Please check the box';
			document.getElementById("comment-check-"+id).classList.add("is-invalid");
			document.getElementById("checkError_"+id).classList.add("paragraph-class");
		}
		function removeAddonTypeError(id) {
			document.getElementById("checkError_"+id).textContent="";
			document.getElementById("comment-check-"+id).classList.remove("is-invalid");
			document.getElementById("checkError_"+id).classList.remove("paragraph-class");
		}
        function approveOrRejectHiringrequest(id, status) {
			var comment = $("#comment-"+id).val();
			var current_approve_position = $("#current_approve_position_"+id).val();
			if(current_approve_position == 'Employee') {
				var isChecked = $("#comment-check-"+id).val();
				if(isChecked == 'on') {
					comment = "I do confirm that I will report back to duty on the due date as approved by the Management, otherwise the Company will consider me as an absentee as per the Law.";
				}
			}
			var others = '';
			if(current_approve_position == 'HR Manager') {
				others = $("others_"+id).val();
			}
			var to_be_replaced_by = '';
			if(current_approve_position == 'Reporting Manager') {
				to_be_replaced_by = $("#to_be_replaced_by_"+id).val();
			}
	        let url = '{{ route('overtimeRequest.action') }}'; 
	        if(status == 'rejected') {
	            var message = 'Reject';
	        }else{
	            var message = 'Approve';
	        }
	        var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee overtime request ?',function (e) {
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