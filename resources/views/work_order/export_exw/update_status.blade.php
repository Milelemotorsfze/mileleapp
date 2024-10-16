@if(isset($workOrder))
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['can-change-status']);
    @endphp
    @if ($hasPermission)
        <a class="me-2 btn btn-sm btn-info"	href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
        <i class="fa fa-file" aria-hidden="true"></i> Update Status
        </a>
        <!-- Modal -->
        <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateStatusModalLabel">Update Status for {{$workOrder->wo_number ?? ''}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="StatusForm">
                            <div class="d-flex justify-content-between">
                                <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                    <input class="form-check-input me-1" type="radio" name="Status" id="StatusNotInitiated" value="Active" {{ $workOrder->status == 'Active' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="StatusNotInitiated">
                                        Active
                                    </label>
                                </div>

                                <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                    <input class="form-check-input me-1" type="radio" name="Status" id="StatusInProgress" value="On Hold" {{ $workOrder->status == 'On Hold' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="StatusInProgress">
                                        On Hold
                                    </label>
                                </div>
                                <!-- New Status Options -->
                                <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                    <input class="form-check-input me-1" type="radio" name="Status" id="StatusPartiallyDelivered" value="Partially Delivered" {{ $workOrder->status == 'Partially Delivered' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="StatusPartiallyDelivered" style="font-size: 14px;">
                                        Partially Delivered
                                    </label>
                                </div>
                                <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                    <input class="form-check-input me-1" type="radio" name="Status" id="StatusSucceeded" value="Succeeded" {{ $workOrder->status == 'Succeeded' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="StatusSucceeded" style="font-size: 14px;">
                                        Succeeded
                                    </label>
                                </div>
                                <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                                    <input class="form-check-input me-1" type="radio" name="Status" id="StatusCancelled" value="Cancelled" {{ $workOrder->status == 'Cancelled' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="StatusCancelled" style="font-size: 14px;">
                                        Cancelled
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="woStatus" class="form-label">Add Comment:</label>
                                <textarea class="form-control" id="woStatus" name="woStatus" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submitStatus('{{ $workOrder->id }}', '{{ $workOrder->wo_number }}')">Update Status</button>						</div>
                </div>
            </div>
        </div>
    @endif
@endif
<script type="text/javascript">
function submitStatus(workOrderId, woNumber) {
		// Get the selected status
		const selectedStatus = document.querySelector(`#updateStatusModal input[name="Status"]:checked`).value;

		// Display the confirmation dialog
		alertify.confirm(
			'Confirmation Required', // Title of the confirmation dialog
			`Are you sure you want to update the status for work order ${woNumber} to ${selectedStatus}?`, // Message in the dialog
			function() { // If the user clicks "OK"
				const comment = document.getElementById(`woStatus`).value;

				// Perform the AJAX request to update the status
				$.ajax({
					url: '/update-wo-status',
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
						$(`#updateStatusModal`).modal('hide');
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