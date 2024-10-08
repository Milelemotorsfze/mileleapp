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
                                    <span id="docCommentError" class="text-danger"></span> <!-- Error display -->
                                </div>
                                <!-- Declaration Fields - initially hidden -->
                                <div id="declarationFields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="declarationNumber" class="form-label">Declaration Number:</label>
                                        <input type="text" class="form-control" id="declarationNumber" name="declarationNumber" maxlength="13" pattern="\d*" placeholder="Enter 13 digit Declaration Number">
                                        <span id="declarationNumberError" class="text-danger"></span> <!-- Error display -->
                                    </div>

                                    <div class="mb-3">
                                        <label for="declarationDate" class="form-label">Declaration Date:</label>
                                        <input type="date" class="form-control" id="declarationDate" name="declarationDate">
                                        <span id="declarationDateError" class="text-danger"></span> <!-- Error display -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" onclick="submitDocStatusSingle('{{ $workOrder->id }}', '{{ $workOrder->wo_number }}')">Update Status</button>						</div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endif
<script type="text/javascript">
    // Function to toggle the display of Declaration Fields based on the status selection
    function toggleDeclarationFields() {
        const selectedStatus = document.querySelector('input[name="docStatus"]:checked').value;
        const declarationFields = document.getElementById('declarationFields');

        if (selectedStatus === 'Ready') {
            declarationFields.style.display = 'block';
        } else {
            declarationFields.style.display = 'none';
        }
    }

    // Event listener for the radio button changes
    document.querySelectorAll('input[name="docStatus"]').forEach((radio) => {
        radio.addEventListener('change', toggleDeclarationFields);
    });

    // Trigger Declaration fields toggle on modal open, depending on the selected radio button
    document.addEventListener('DOMContentLoaded', function() {
        toggleDeclarationFields(); // Ensure the fields are shown/hidden correctly based on the pre-selected status
    });

    function submitDocStatusSingle(workOrderId, woNumber) {
        // Clear previous errors
        document.getElementById('docCommentError').textContent = '';
        document.getElementById('declarationNumberError').textContent = '';
        document.getElementById('declarationDateError').textContent = '';

        // Get the selected status
        const selectedStatus = document.querySelector(`#updateDocStatusModal input[name="docStatus"]:checked`).value;

        // Get Declaration Number and Date (only if status is Ready)
        let declarationNumber = '';
		// Get the value of the textarea
		const comment = document.getElementById(`docComment`).value;
		// Display the confirmation dialog
		alertify.confirm(
			'Confirmation Required', // Title of the confirmation dialog
			`Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus}?`, // Message in the dialog
			function() { // If the user clicks "OK"
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