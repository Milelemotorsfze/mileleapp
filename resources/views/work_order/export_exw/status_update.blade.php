<div class="modal fade" id="updateStatusModal_{{$data->id}}" tabindex="-1" aria-labelledby="updateStatusModalLabel_{{$data->id}}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel_{{$data->id}}">Update Status for {{$data->wo_number ?? ''}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="StatusForm_{{$data->id}}">
                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="Status_{{$data->id}}" id="StatusActive_{{$data->id}}" value="Active" {{ $data->status == 'Active' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusActive_{{$data->id}}" style="font-size: 14px;">
                                Active
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="Status_{{$data->id}}" id="StatusOnHold_{{$data->id}}" value="On Hold" {{ $data->status == 'On Hold' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusOnHold_{{$data->id}}" style="font-size: 14px;">
                                On Hold
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="Status_{{$data->id}}" id="StatusPartiallyDelivered_{{$data->id}}" value="Partially Delivered" {{ $data->status == 'Partially Delivered' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusPartiallyDelivered_{{$data->id}}" style="font-size: 14px;">
                                Partially Delivered
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="Status_{{$data->id}}" id="StatusSucceeded_{{$data->id}}" value="Succeeded" {{ $data->status == 'Succeeded' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusSucceeded_{{$data->id}}" style="font-size: 14px;">
                                Succeeded
                            </label>
                        </div>
                        <div class="form-check flex-fill d-flex align-items-left justify-content-left">
                            <input class="form-check-input me-1" type="radio" name="Status_{{$data->id}}" id="StatusCancelled_{{$data->id}}" value="Cancelled" {{ $data->status == 'Cancelled' ? 'checked' : '' }}>
                            <label class="form-check-label" for="StatusCancelled_{{$data->id}}" style="font-size: 14px;">
                                Cancelled
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="Comment_{{$data->id}}" class="form-label" style="font-size: 14px;">Add Comment:</label>
                        <textarea class="form-control" id="Comment_{{$data->id}}" name="docComment" rows="3" style="font-size: 14px;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitStatus('{{ $data->id }}', '{{ $data->wo_number }}')">Update Status</button>						
            </div>
        </div>
    </div>
</div>   

<script type="text/javascript">
    function submitStatus(workOrderId, woNumber) {
        const selectedStatus = document.querySelector(`#updateStatusModal_${workOrderId} input[name="Status_${workOrderId}"]:checked`).value;

        alertify.confirm(
            'Confirmation Required', 
            `Are you sure you want to update the status for work order ${woNumber} to ${selectedStatus}?`, 
            function() { 
                const comment = document.getElementById(`Comment_${workOrderId}`).value;

                $.ajax({
                    url: '/update-wo-status',
                    method: 'POST',
                    data: {
                        workOrderId: workOrderId,
                        status: selectedStatus,
                        comment: comment,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        alertify.success(response.message);
                        $(`#updateStatusModal_${workOrderId}`).modal('hide');
                        location.reload(); 
                    },
                    error: function(xhr) {
                        alertify.error('Failed to update status');
                    }
                });
            },
            function() { 
                alertify.error('Action canceled');
            }
        );
    }
</script>
