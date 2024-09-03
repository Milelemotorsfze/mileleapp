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
    function submitDocStatus(workOrderId, woNumber) {
        // Get the selected status
        const selectedStatus = document.querySelector(`#updateDocStatusModal_${workOrderId} input[name="docStatus_${workOrderId}"]:checked`).value;

        // Display the confirmation dialog
        alertify.confirm(
            'Confirmation Required', // Title of the confirmation dialog
            `Are you sure you want to update the documentation status for work order ${woNumber} to ${selectedStatus}?`, // Message in the dialog
            function() { // If the user clicks "OK"
                const comment = document.getElementById(`docComment_${workOrderId}`).value;

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
