<div class="modal fade" id="updateDocStatusModal_{{$data->id}}" tabindex="-1" aria-labelledby="updateDocStatusModalLabel_{{$data->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateDocStatusModalLabel_{{$data->id}}">Update Documentation Status for {{$data->wo_number ?? ''}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="docStatusForm_{{$data->id}}">
                    <div class="d-flex justify-content-between">
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="docStatus_{{$data->id}}" id="docStatusNotInitiated_{{$data->id}}" value="Not Initiated" {{ $data->docs_status == 'Not Initiated' ? 'checked' : '' }}>
                            <label class="form-check-label" for="docStatusNotInitiated_{{$data->id}}" style="font-size: 14px;">
                                Not Initiated
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="docStatus_{{$data->id}}" id="docStatusInProgress_{{$data->id}}" value="In Progress" {{ $data->docs_status == 'In Progress' ? 'checked' : '' }}>
                            <label class="form-check-label" for="docStatusInProgress_{{$data->id}}" style="font-size: 14px;">
                                In Progress
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="docStatus_{{$data->id}}" id="docStatusReady_{{$data->id}}" value="Ready" {{ $data->docs_status == 'Ready' ? 'checked' : '' }}>
                            <label class="form-check-label" for="docStatusReady_{{$data->id}}" style="font-size: 14px;">
                                Ready
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="docComment_{{$data->id}}" class="form-label" style="font-size: 14px;">Add Comment:</label>
                        <textarea class="form-control" id="docComment_{{$data->id}}" name="docComment" rows="3" style="font-size: 14px;"></textarea>
                        <span id="docCommentError_{{$data->id}}" class="text-danger"></span>
                    </div>

                    <!-- Declaration Fields - initially hidden -->
                    <div id="declarationFields_{{$data->id}}" style="display: none;">
                        <div class="mb-3">
                            <label for="declarationNumber_{{$data->id}}" class="form-label">Declaration Number:</label>
                            <input type="text" class="form-control" id="declarationNumber_{{$data->id}}" name="declarationNumber" maxlength="13" pattern="\d*" placeholder="Enter 13 digit Declaration Number">
                            <span id="declarationNumberError_{{$data->id}}" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label for="declarationDate_{{$data->id}}" class="form-label">Declaration Date:</label>
                            <input type="date" class="form-control" id="declarationDate_{{$data->id}}" name="declarationDate">
                            <span id="declarationDateError_{{$data->id}}" class="text-danger"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitDocStatus('{{ $data->id }}', '{{ $data->wo_number }}')">Update Status</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Function to toggle the display of Declaration Fields based on the status selection
    function toggleDeclarationFields_{{$data->id}}() {
        const selectedStatus = document.querySelector(`input[name="docStatus_{{$data->id}}"]:checked`).value;
        const declarationFields = document.getElementById('declarationFields_{{$data->id}}');

        if (selectedStatus === 'Ready') {
            declarationFields.style.display = 'block';
        } else {
            declarationFields.style.display = 'none';
        }
    }

    // Event listener for the radio button changes
    document.querySelectorAll(`input[name="docStatus_{{$data->id}}"]`).forEach((radio) => {
        radio.addEventListener('change', toggleDeclarationFields_{{$data->id}});
    });

    // Trigger Declaration fields toggle on modal open
    document.addEventListener('DOMContentLoaded', function() {
        toggleDeclarationFields_{{$data->id}}(); // Ensure fields are toggled correctly on page load
    });

    function submitDocStatus(workOrderId, woNumber) {
        // Clear previous errors
        document.getElementById('docCommentError_{{$data->id}}').textContent = '';
        document.getElementById('declarationNumberError_{{$data->id}}').textContent = '';
        document.getElementById('declarationDateError_{{$data->id}}').textContent = '';

        // Get the selected status
        const selectedStatus = document.querySelector(`#updateDocStatusModal_${workOrderId} input[name="docStatus_${workOrderId}"]:checked`).value;

        // Get Declaration Number and Date (only if status is Ready)
        let declarationNumber = '';
        let declarationDate = '';
        if (selectedStatus === 'Ready') {
            declarationNumber = document.getElementById(`declarationNumber_{{$data->id}}`).value;
            declarationDate = document.getElementById(`declarationDate_{{$data->id}}`).value;

            // Validate Declaration Number only if it contains any value
            if (declarationNumber && declarationNumber.length !== 13) {
                document.getElementById('declarationNumberError_{{$data->id}}').textContent = 'Please enter a valid 13-digit Declaration Number.';
                return;
            }
        }

        const comment = document.getElementById(`docComment_{{$data->id}}`).value;

        // Display confirmation dialog
        alertify.confirm(
            'Confirmation Required', // Title of the confirmation dialog
            `Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus}?`, // Message in the dialog
            function() { // If the user clicks "OK"
                $.ajax({
                    url: '/update-wo-doc-status',
                    method: 'POST',
                    data: {
                        workOrderId: workOrderId,
                        status: selectedStatus,
                        comment: comment,
                        declarationNumber: declarationNumber,
                        declarationDate: declarationDate,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Handle the response (e.g., show a success message, close the modal)
                        alertify.success(response.message);
                        $(`#updateDocStatusModal_${workOrderId}`).modal('hide');
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
