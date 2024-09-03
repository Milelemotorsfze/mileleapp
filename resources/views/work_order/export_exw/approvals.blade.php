
@if(isset($workOrder) && $workOrder->sales_support_data_confirmation_at != '')
	<a title="Revert Sales Support Data Confirmation" class="me-2 btn btn-sm btn-info revert-btn-sales-approval" data-id="{{ $workOrder->id }}">
		<i class="fas fa-hourglass-start" title="Revert Sales Support Data Confirmation"></i> Revert Sales Support Data Confirmation
	</a>
@elseif(isset($workOrder) && $workOrder->sales_support_data_confirmation_at == '')
	<a title="Sales Support Data Confirmation" class="me-2 btn btn-sm btn-info btn-sales-approval" data-id="{{ isset($workOrder) ? $workOrder->id : '' }}">
	<i class="fas fa-hourglass-start"></i> Sales Support Data Confirmation</a>
@endif
@if(isset($workOrder) && isset($workOrder->financePendingApproval))
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['do-finance-approval']);
	@endphp
	@if ($hasPermission)
		<a title="Finance Approval" class="me-2 btn btn-sm btn-info" 
		data-bs-toggle="modal" data-bs-target="#financeApprovalModal">
			<i class="fas fa-hourglass-start" title="Finance Approval"></i> Fin. Approval
		</a>
	@endif
    <!-- Modal -->
    <div class="modal fade" id="financeApprovalModal" tabindex="-1" aria-labelledby="financeApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="financeApprovalModalLabel">Fin. Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="financeComment" class="form-label">Comments</label>
                        <textarea class="form-control" id="financeComment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-danger btn-finance-approval" id="rejectButton" 
					data-id="{{ $workOrder->financePendingApproval->id}}"
					data-status="reject">Reject</button>
                    <button type="button" class="btn btn-sm btn-success btn-finance-approval" id="approveButton" 
					data-id="{{ $workOrder->financePendingApproval->id}}"
					data-status="approve">Approve</button>
                </div>
            </div>
        </div>
    </div>
@endif
@if(isset($workOrder))
@if($workOrder->finance_approval_status != '')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-finance-approval-history']);
	@endphp
	@if ($hasPermission)
		<a class="me-2 btn btn-sm btn-info" 
			href="{{route('fetchFinanceApprovalHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> Fin. Approval Log
		</a>
	@endif
@endif
@endif
@if(isset($workOrder) && isset($workOrder->cooPendingApproval))
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['do-coo-office-approval']);
	@endphp
	@if ($hasPermission)
		<a title="COO Office Approval" class="me-2 btn btn-sm btn-info" 
		data-bs-toggle="modal" data-bs-target="#cooApprovalModal">
			<i class="fas fa-hourglass-start" title="COO Office Approval"></i> COO Office Approval
		</a>
	@endif
    <!-- Modal -->
    <div class="modal fade" id="cooApprovalModal" tabindex="-1" aria-labelledby="cooApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cooApprovalModalLabel">COO Office Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="cooComment" class="form-label">Comments</label>
                        <textarea class="form-control" id="cooComment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-sm btn-danger btn-coe-office-approval" id="rejectButton" 
					data-id="{{ $workOrder->cooPendingApproval->id}}"
					data-status="reject">Reject</button>
                    <button type="button" class="btn btn-sm btn-success btn-coe-office-approval" id="approveButton" 
					data-id="{{ $workOrder->cooPendingApproval->id}}"
					data-status="approve">Approve</button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="approvalModalLabel">COO Office Direct Approval</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <!-- <span aria-hidden="true">&times;</span> -->
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <label for="approvalComments">Comments</label>
            <textarea class="form-control" id="approvalComments" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            <button type="button" class="btn btn-primary" id="submitApproval">Submit</button>
        </div>
        </div>
    </div>
</div>
@if(isset($workOrder))
@if($workOrder->coo_approval_status != '')
	@php
	$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-coo-approval-history']);
	@endphp
	@if ($hasPermission)
		<a class="me-2 btn btn-sm btn-info"
			href="{{route('fetchCooApprovalHistory',$workOrder->id)}}">
			<i class="fas fa-eye"></i> COO Approval Log
		</a>
	@endif
@endif
@endif

<!-- Documentation Status by Logistics -->
@if(isset($workOrder))
	@if($workOrder->sales_support_data_confirmation_at != '' && $workOrder->finance_approval_status == 'Approved' && $workOrder->coo_approval_status == 'Approved')
		@php
		$hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-change-documentation-status']);
		@endphp
		@if ($hasPermission)
			<a class="me-2 btn btn-sm btn-info"	href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateDocStatusModal">
			<i class="fa fa-file" aria-hidden="true"></i> Update Doc Status
			</a>
			<!-- Modal -->
			<div class="modal fade" id="updateDocStatusModal" tabindex="-1" aria-labelledby="updateDocStatusModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="updateDocStatusModalLabel">Update Documentation Status for {{$workOrder->wo_number ?? ''}}</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="docStatusForm">
								<div class="d-flex justify-content-between">
									<div class="form-check flex-fill d-flex align-items-left justify-content-left">
										<input class="form-check-input me-1" type="radio" name="docStatus" id="docStatusNotInitiated" value="Not Initiated" {{ $workOrder->docs_status == 'Not Initiated' ? 'checked' : '' }}>
										<label class="form-check-label" for="docStatusNotInitiated">
											Not Initiated
										</label>
									</div>

									<div class="form-check flex-fill d-flex align-items-left justify-content-left">
										<input class="form-check-input me-1" type="radio" name="docStatus" id="docStatusInProgress" value="In Progress" {{ $workOrder->docs_status == 'In Progress' ? 'checked' : '' }}>
										<label class="form-check-label" for="docStatusInProgress">
											In Progress
										</label>
									</div>

									<div class="form-check flex-fill d-flex align-items-left justify-content-left">
										<input class="form-check-input me-1" type="radio" name="docStatus" id="docStatusReady" value="Ready" {{ $workOrder->docs_status == 'Ready' ? 'checked' : '' }}>
										<label class="form-check-label" for="docStatusReady">
											Ready
										</label>
									</div>
								</div>
								<div class="mb-3 mt-3">
									<label for="docComment" class="form-label">Add Comment:</label>
									<textarea class="form-control" id="docComment" name="docComment" rows="3"></textarea>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" onclick="submitDocStatus('{{ $workOrder->id }}', '{{ $workOrder->wo_number }}')">Update Status</button>						</div>
					</div>
				</div>
			</div>
		@endif
		@php
		$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-doc-status-log']);
		@endphp
		@if ($hasPermission)
			<a class="me-2 btn btn-sm btn-info"
				href="{{route('docStatusHistory',$workOrder->id)}}">
				<i class="fas fa-eye"></i> Doc Status Log
			</a>
		@endif
	@endif
@endif

<script type="text/javascript">

    $(document).ready(function () { 
		$('.btn-sales-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			let url = '{{ route('work-order.sales-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to confirm this work order ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Confirm, It was Confirmed already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.revert-btn-sales-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			let url = '{{ route('work-order.revert-sales-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to revert this work order confirmation ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Revert, It was reverted already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-finance-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			var comments = $('#financeComment').val();
			let url = '{{ route('work-order.finance-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to '+status+' this work order ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							status: status,
							comments: comments,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Approve, It was approved already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-coe-office-approval').click(function (e) { 
			var id = $(this).attr('data-id');
			var status = $(this).attr('data-status');
			var comments = $('#financeComment').val();
			let url = '{{ route('work-order.coe-office-approval') }}';
			var confirm = alertify.confirm('Are you sure you want to '+status+' this work order ?',function (e) {
				if (e) {
					$.ajax({
						type: "POST",
						url: url,
						dataType: "json",
						data: {
							id: id,
							status: status,
							comments: comments,
							_token: '{{ csrf_token() }}'
						},
						success: function (data) {						
							if(data == 'success') {
								window.location.reload();
								alertify.success(status + " Successfully")
							}
							else if(data == 'error') {
								window.location.reload();
								alertify.error("Can't Approve, It was approved already..")
							}
						}
					});
				}
			}).set({title:"Confirmation"})
		})
		$('.btn-coe-office-direct-approval').click(function (e) {
			var id = $(this).attr('data-id');
			$('#approvalModal').data('id', id).modal('show');
		});

		$('#submitApproval').click(function (e) {
			var id = $('#approvalModal').data('id');
			var comments = $('#approvalComments').val();
			let url = '{{ route('work-order.coe-office-approval') }}';

			if (!comments) {
			alertify.error("Please add comments.");
			return;
			}

			var confirm = alertify.confirm('Are you sure you want to approve this work order?', function (e) {
			if (e) {
				$.ajax({
				type: "POST",
				url: url,
				dataType: "json",
				data: {
					id: id,
					comments: comments,
					_token: '{{ csrf_token() }}'
				},
				success: function (data) {
					$('#approvalModal').modal('hide');
					if (data == 'success') {
					window.location.reload();
					alertify.success("Successfully Approved");
					} else if (data == 'error') {
					window.location.reload();
					alertify.error("Can't Approve, It was approved already.");
					}
				}
				});
			}
			}).set({ title: "Confirmation" });
		});
    });
	
	function submitDocStatus(workOrderId, woNumber) {
		// Get the selected status
		const selectedStatus = document.querySelector(`#updateDocStatusModal input[name="docStatus"]:checked`).value;

		// Display the confirmation dialog
		alertify.confirm(
			'Confirmation Required', // Title of the confirmation dialog
			`Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus}?`, // Message in the dialog
			function() { // If the user clicks "OK"
				const comment = document.getElementById(`docComment`).value;

				// Perform the AJAX request to update the status
				$.ajax({
					url: '/update-wo-doc-status',
					method: 'POST',
					data: {
						workOrderId: workOrderId,
						status: selectedStatus,
						comment: comment,
						_token: '{{ csrf_token() }}' // Laravel CSRF token
					},
					success: function(response) {
						// Handle the response (e.g., show a success message, close the modal)
						alertify.success(response.message);
						$(`#updateDocStatusModal`).modal('hide');
						location.reload(); // Reload the page after success
					},
					error: function(xhr) {
						// Handle any errors
						alertify.error('Failed to update status');
					}
				});
			},
			function() { // If the user clicks "Cancel"
				alertify.error('Action canceled');
			}
		);
	}

</script>