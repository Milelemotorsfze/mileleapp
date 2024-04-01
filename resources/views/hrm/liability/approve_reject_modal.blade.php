<div class="modal fade" id="approve-employee-liability-request-{{$data->id}}"
	tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Liability Request Approval</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-3">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-12">
							<div class="row mt-2">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<label class="form-label font-size-13">Approval By Position</label>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									@if(isset($data->is_auth_user_can_approve['current_approve_position']))
									{{$data->is_auth_user_can_approve['current_approve_position']}}
									@endif
								</div>
							</div>
							<div class="row mt-2">
								<div class="col-lg-6 col-md-6 col-sm-6">
									<label class="form-label font-size-13">Approval By Name</label>
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
				<button type="button" class="btn btn-success status-approve-button"
					data-id="{{ $data->id }}" data-status="approved">Approve</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="reject-employee-liability-request-{{$data->id}}"
	tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Employee Liability Request Rejection</h1>
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
	var comment = '';
	    $('.status-reject-button').click(function (e) {
	     var id = $(this).attr('data-id');
	     var status = $(this).attr('data-status');
		 comment = $("#reject-comment-"+id).val();
	     approveOrRejectHiringrequest(id, status,comment)
	 })
	 $('.status-approve-button').click(function (e) {
	     var id = $(this).attr('data-id');
	     var status = $(this).attr('data-status');
		 comment = $("#comment-"+id).val();
	     approveOrRejectHiringrequest(id, status,comment)
	 })
	    function approveOrRejectHiringrequest(id, status,comment) {
	
	var current_approve_position = $("#current_approve_position_"+id).val();
	     let url = '{{ route('liabilityRequest.action') }}'; 
	     if(status == 'rejected') {
	         var message = 'Reject';
	     }else{
	         var message = 'Approve';
	     }
	     var confirm = alertify.confirm('Are you sure you want to '+ message +' this employee Liability request ?',function (e) {
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
	});
</script>